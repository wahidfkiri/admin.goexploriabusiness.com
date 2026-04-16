<?php

namespace Vendor\AdsManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Throwable;

class AdTrackingController extends Controller
{
    /**
     * Tracking pixel d'impression (image GIF 1×1)
     */
    public function trackImpression(Request $request, int $adId)
    {
        try {
            $ad = DB::table('ads')->find($adId);

            if ($ad && $ad->status === 'active') {
                $placementId = (int)$request->query('pid', 0);
                $ip          = $request->ip();
                $isUnique    = $this->isUniqueImpression($adId, $ip);

                DB::table('ad_impressions')->insert([
                    'ad_id'           => $adId,
                    'placement_id'    => $placementId ?: null,
                    'etablissement_id'=> $request->query('eid') ?: null,
                    'ip_address'      => $ip,
                    'user_agent'      => substr($request->userAgent() ?? '', 0, 255),
                    'page_url'        => substr($request->query('url', $request->headers->get('referer', '')), 0, 500),
                    'referrer'        => substr($request->headers->get('referer', ''), 0, 500),
                    'user_id'         => auth()->id(),
                    'is_unique'       => $isUnique,
                    'viewed_at'       => now(),
                ]);
            }
        } catch (Throwable $e) {
            Log::warning('AdTracking::impression', ['ad_id' => $adId, 'error' => $e->getMessage()]);
        }

        return $this->pixelResponse();
    }

    /**
     * Tracking de clic (POST puis redirection)
     */
    public function trackClick(Request $request, int $adId)
    {
        try {
            $ad = DB::table('ads')->find($adId);

            if ($ad && $ad->status === 'active') {
                $ip        = $request->ip();
                $isFraud   = $this->detectFraud($adId, $ip);

                $cost = match ($ad->pricing_model) {
                    'cpc'  => $isFraud ? 0 : (float)$ad->rate,
                    default=> 0,
                };

                DB::table('ad_clicks')->insert([
                    'ad_id'           => $adId,
                    'placement_id'    => (int)$request->input('pid', 0) ?: null,
                    'etablissement_id'=> $request->input('eid') ?: null,
                    'ip_address'      => $ip,
                    'user_agent'      => substr($request->userAgent() ?? '', 0, 255),
                    'page_url'        => substr($request->headers->get('referer', ''), 0, 500),
                    'referrer'        => substr($request->headers->get('referer', ''), 0, 500),
                    'user_id'         => auth()->id(),
                    'is_fraud'        => $isFraud,
                    'cost'            => $cost,
                    'clicked_at'      => now(),
                ]);

                // Incrémenter budget dépensé si CPC
                if (!$isFraud && $cost > 0) {
                    DB::table('ads')->where('id', $adId)->increment('budget_spent', $cost);
                }

                // Vérifier budget / limite de clics
                $this->checkAdLimits($ad, $adId);
            }
        } catch (Throwable $e) {
            Log::warning('AdTracking::click', ['ad_id' => $adId, 'error' => $e->getMessage()]);
        }

        // Si appel AJAX, retourner JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true]);
        }

        // Sinon rediriger vers la destination
        $destination = $ad->destination_url ?? '/';
        return redirect()->away($destination);
    }

    /**
     * Pixel GIF transparent
     */
    protected function pixelResponse()
    {
        $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        return response($pixel, 200)
            ->header('Content-Type', 'image/gif')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    /**
     * Vérifie si c'est une impression unique (par IP / 24h)
     */
    protected function isUniqueImpression(int $adId, string $ip): bool
    {
        $key = "ad_imp_{$adId}_{$ip}";
        if (Cache::has($key)) return false;
        Cache::put($key, true, 86400);
        return true;
    }

    /**
     * Détection de fraude simple : trop de clics depuis la même IP sur 1h
     */
    protected function detectFraud(int $adId, string $ip): bool
    {
        $threshold = config('ads-manager.click_fraud_threshold', 10);
        $count = DB::table('ad_clicks')
            ->where('ad_id', $adId)
            ->where('ip_address', $ip)
            ->where('clicked_at', '>=', now()->subHour())
            ->count();
        return $count >= $threshold;
    }

    /**
     * Vérifie et désactive l'annonce si les limites sont atteintes
     */
    protected function checkAdLimits($ad, int $adId): void
    {
        $deactivate = false;

        if ($ad->impression_limit) {
            $impressions = DB::table('ad_impressions')->where('ad_id', $adId)->count();
            if ($impressions >= $ad->impression_limit) $deactivate = true;
        }

        if ($ad->click_limit) {
            $clicks = DB::table('ad_clicks')->where('ad_id', $adId)->where('is_fraud', false)->count();
            if ($clicks >= $ad->click_limit) $deactivate = true;
        }

        if ($ad->budget_total && $ad->budget_spent >= $ad->budget_total) {
            $deactivate = true;
        }

        if ($ad->end_date && now()->toDateString() > $ad->end_date) {
            $deactivate = true;
        }

        if ($deactivate) {
            DB::table('ads')->where('id', $adId)->update(['status' => 'expired', 'updated_at' => now()]);
            Log::info('Annonce expirée automatiquement', ['ad_id' => $adId]);
        }
    }
}