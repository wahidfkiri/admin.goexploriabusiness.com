<?php

namespace Vendor\AdsManager\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Throwable;

/**
 * AdBudgetService
 * Gère les budgets publicitaires :
 *  - Incrémentation au fil des clics/impressions (CPM/CPC)
 *  - Vérification des limites en temps réel
 *  - Alertes de budget faible
 */
class AdBudgetService
{
    /**
     * Calcule et enregistre le coût d'une impression (CPM)
     */
    public function recordImpressionCost(int $adId): void
    {
        try {
            $ad = DB::table('ads')->find($adId);
            if (!$ad || $ad->pricing_model !== 'cpm') return;

            // CPM = coût pour 1000 impressions
            $costPerImpression = (float)$ad->rate / 1000;

            if ($costPerImpression > 0) {
                DB::table('ads')->where('id', $adId)->increment('budget_spent', $costPerImpression);
                $this->checkDailyBudget($ad, $adId);
                $this->clearBudgetCache($adId);
            }
        } catch (Throwable $e) {
            Log::warning('AdBudgetService::recordImpressionCost', ['ad_id' => $adId, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Calcule et enregistre le coût d'un clic (CPC)
     */
    public function recordClickCost(int $adId, bool $isFraud = false): float
    {
        if ($isFraud) return 0;

        try {
            $ad = DB::table('ads')->find($adId);
            if (!$ad || $ad->pricing_model !== 'cpc') return 0;

            $cost = (float)$ad->rate;
            if ($cost > 0) {
                DB::table('ads')->where('id', $adId)->increment('budget_spent', $cost);
                $this->clearBudgetCache($adId);
            }
            return $cost;
        } catch (Throwable $e) {
            Log::warning('AdBudgetService::recordClickCost', ['ad_id' => $adId, 'error' => $e->getMessage()]);
            return 0;
        }
    }

    /**
     * Vérifie si l'annonce a encore du budget disponible
     */
    public function hasBudgetAvailable(int $adId): bool
    {
        return Cache::remember("ad_budget_ok_{$adId}", 60, function () use ($adId) {
            $ad = DB::table('ads')->find($adId);
            if (!$ad) return false;
            if ($ad->budget_total === null) return true;
            return (float)$ad->budget_spent < (float)$ad->budget_total;
        });
    }

    /**
     * Vérifie le budget journalier (approximation)
     */
    public function checkDailyBudget($ad, int $adId): void
    {
        if (!$ad->budget_daily) return;

        $today = now()->toDateString();
        $dailySpent = DB::table('ad_daily_reports')
            ->where('ad_id', $adId)
            ->where('report_date', $today)
            ->value('revenue') ?? 0;

        if ((float)$dailySpent >= (float)$ad->budget_daily) {
            Log::info('AdBudgetService: budget journalier atteint', [
                'ad_id'        => $adId,
                'budget_daily' => $ad->budget_daily,
                'spent_today'  => $dailySpent,
            ]);
            // On ne désactive pas l'annonce — juste un log.
            // Pour désactiver temporairement, utiliser AdExpiryService.
        }
    }

    /**
     * Résumé budgétaire d'une annonce
     */
    public function getBudgetSummary(int $adId): array
    {
        $ad = DB::table('ads')->find($adId);
        if (!$ad) return [];

        $spent     = (float)($ad->budget_spent ?? 0);
        $total     = $ad->budget_total ? (float)$ad->budget_total : null;
        $remaining = $total !== null ? max(0, $total - $spent) : null;
        $pct       = ($total && $total > 0) ? min(100, round(($spent / $total) * 100, 1)) : null;

        return [
            'total'     => $total,
            'spent'     => $spent,
            'remaining' => $remaining,
            'pct'       => $pct,
            'daily'     => $ad->budget_daily ? (float)$ad->budget_daily : null,
            'alert'     => $pct !== null && $pct >= 80,
        ];
    }

    /**
     * Recharge le budget (ex: facturation mensuelle)
     */
    public function resetBudget(int $adId, float $newTotal = null): void
    {
        $data = ['budget_spent' => 0, 'updated_at' => now()];
        if ($newTotal !== null) $data['budget_total'] = $newTotal;

        DB::table('ads')->where('id', $adId)->update($data);
        $this->clearBudgetCache($adId);

        Log::info('AdBudgetService: budget réinitialisé', ['ad_id' => $adId, 'new_total' => $newTotal]);
    }

    protected function clearBudgetCache(int $adId): void
    {
        Cache::forget("ad_budget_ok_{$adId}");
        Cache::forget("ad_{$adId}");
    }
}