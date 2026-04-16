<?php

namespace Vendor\AdsManager\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AdReportService
{
    /**
     * Statistiques globales de toutes les annonces
     */
    public function globalStats(): array
    {
        try {
            $today = now()->toDateString();
            $monthStart = now()->startOfMonth()->toDateString();

            return [
                'total_ads'          => DB::table('ads')->count(),
                'active_ads'         => DB::table('ads')->where('status', 'active')->count(),
                'pending_ads'        => DB::table('ads')->where('status', 'pending')->count(),
                'total_impressions'  => DB::table('ad_impressions')->count(),
                'total_clicks'       => DB::table('ad_clicks')->where('is_fraud', false)->count(),
                'impressions_today'  => DB::table('ad_impressions')->whereDate('viewed_at', $today)->count(),
                'clicks_today'       => DB::table('ad_clicks')->whereDate('clicked_at', $today)->where('is_fraud', false)->count(),
                'revenue_month'      => DB::table('ad_daily_reports')->where('report_date', '>=', $monthStart)->sum('revenue'),
                'total_placements'   => DB::table('ad_placements')->count(),
                'active_placements'  => DB::table('ad_placements')->where('is_active', true)->count(),
                'avg_ctr'            => $this->calculateGlobalCtr(),
            ];
        } catch (Throwable $e) {
            Log::error('AdReportService::globalStats', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Statistiques d'une annonce précise
     */
    public function adStats(int $adId): array
    {
        try {
            $impressions = DB::table('ad_impressions')->where('ad_id', $adId)->count();
            $clicks      = DB::table('ad_clicks')->where('ad_id', $adId)->where('is_fraud', false)->count();
            $fraudClicks = DB::table('ad_clicks')->where('ad_id', $adId)->where('is_fraud', true)->count();
            $revenue     = DB::table('ad_daily_reports')->where('ad_id', $adId)->sum('revenue');

            return [
                'impressions'       => $impressions,
                'unique_impressions' => DB::table('ad_impressions')->where('ad_id', $adId)->where('is_unique', true)->count(),
                'clicks'            => $clicks,
                'fraud_clicks'      => $fraudClicks,
                'ctr'               => $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0,
                'revenue'           => $revenue,
                'impressions_today' => DB::table('ad_impressions')->where('ad_id', $adId)->whereDate('viewed_at', now())->count(),
                'clicks_today'      => DB::table('ad_clicks')->where('ad_id', $adId)->whereDate('clicked_at', now())->where('is_fraud', false)->count(),
                'timeline'          => $this->adTimeline($adId, 30),
            ];
        } catch (Throwable $e) {
            Log::error('AdReportService::adStats', ['ad_id' => $adId, 'error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Timeline journalière d'une annonce (N derniers jours)
     */
    public function adTimeline(int $adId, int $days = 30): array
    {
        return DB::table('ad_daily_reports')
            ->where('ad_id', $adId)
            ->where('report_date', '>=', now()->subDays($days)->toDateString())
            ->orderBy('report_date')
            ->select('report_date', 'impressions', 'clicks', 'ctr', 'revenue')
            ->get()
            ->toArray();
    }

    /**
     * Classement des annonces par impressions
     */
    public function topAds(int $limit = 10, string $by = 'impressions'): \Illuminate\Support\Collection
    {
        return DB::table('ads')
            ->leftJoin('ad_daily_reports', 'ads.id', '=', 'ad_daily_reports.ad_id')
            ->select(
                'ads.id', 'ads.titre', 'ads.status', 'ads.format',
                DB::raw('SUM(ad_daily_reports.impressions) as total_impressions'),
                DB::raw('SUM(ad_daily_reports.clicks) as total_clicks'),
                DB::raw('SUM(ad_daily_reports.revenue) as total_revenue'),
                DB::raw('AVG(ad_daily_reports.ctr) as avg_ctr')
            )
            ->groupBy('ads.id', 'ads.titre', 'ads.status', 'ads.format')
            ->orderByDesc('total_' . ($by === 'revenue' ? 'revenue' : ($by === 'clicks' ? 'clicks' : 'impressions')))
            ->limit($limit)
            ->get();
    }

    /**
     * Performance par emplacement
     */
    public function placementStats(): \Illuminate\Support\Collection
    {
        return DB::table('ad_placements')
            ->leftJoin('ad_daily_reports', 'ad_placements.id', '=', 'ad_daily_reports.placement_id')
            ->select(
                'ad_placements.id',
                'ad_placements.nom',
                'ad_placements.code',
                'ad_placements.position',
                DB::raw('SUM(ad_daily_reports.impressions) as total_impressions'),
                DB::raw('SUM(ad_daily_reports.clicks) as total_clicks'),
                DB::raw('SUM(ad_daily_reports.revenue) as total_revenue')
            )
            ->groupBy('ad_placements.id', 'ad_placements.nom', 'ad_placements.code', 'ad_placements.position')
            ->orderByDesc('total_impressions')
            ->get();
    }

    /**
     * Agrège les données du jour (à lancer via scheduler)
     */
    public function aggregateDaily(string $date = null): void
    {
        $date = $date ?? now()->toDateString();

        try {
            $data = DB::table('ad_impressions')
                ->leftJoin('ad_clicks', function ($join) use ($date) {
                    $join->on('ad_impressions.ad_id', '=', 'ad_clicks.ad_id')
                         ->whereDate('ad_clicks.clicked_at', $date)
                         ->where('ad_clicks.is_fraud', false);
                })
                ->whereDate('ad_impressions.viewed_at', $date)
                ->select(
                    'ad_impressions.ad_id',
                    'ad_impressions.placement_id',
                    DB::raw('COUNT(ad_impressions.id) as impressions'),
                    DB::raw('COUNT(CASE WHEN ad_impressions.is_unique THEN 1 END) as unique_impressions'),
                    DB::raw('COUNT(ad_clicks.id) as clicks')
                )
                ->groupBy('ad_impressions.ad_id', 'ad_impressions.placement_id')
                ->get();

            foreach ($data as $row) {
                $ctr     = $row->impressions > 0 ? round(($row->clicks / $row->impressions) * 100, 2) : 0;
                $ad      = DB::table('ads')->find($row->ad_id);
                $rate    = $ad ? $ad->rate : 0;
                $revenue = match ($ad?->pricing_model) {
                    'cpm'  => ($row->impressions / 1000) * $rate,
                    'cpc'  => $row->clicks * $rate,
                    default => 0,
                };

                DB::table('ad_daily_reports')->updateOrInsert(
                    ['ad_id' => $row->ad_id, 'placement_id' => $row->placement_id, 'report_date' => $date],
                    [
                        'impressions'        => $row->impressions,
                        'unique_impressions'  => $row->unique_impressions,
                        'clicks'             => $row->clicks,
                        'ctr'                => $ctr,
                        'revenue'            => $revenue,
                        'updated_at'         => now(),
                        'created_at'         => now(),
                    ]
                );

                // Mettre à jour le budget dépensé
                if ($ad && $revenue > 0) {
                    DB::table('ads')->where('id', $row->ad_id)
                        ->increment('budget_spent', $revenue);
                }
            }

            Log::info('AdReportService::aggregateDaily terminé', ['date' => $date, 'rows' => $data->count()]);

        } catch (Throwable $e) {
            Log::error('AdReportService::aggregateDaily', ['date' => $date, 'error' => $e->getMessage()]);
        }
    }

    protected function calculateGlobalCtr(): float
    {
        $impressions = DB::table('ad_impressions')->count();
        $clicks      = DB::table('ad_clicks')->where('is_fraud', false)->count();
        return $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0;
    }
}