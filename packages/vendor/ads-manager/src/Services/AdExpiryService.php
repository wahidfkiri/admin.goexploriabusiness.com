<?php

namespace Vendor\AdsManager\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * AdExpiryService
 * Vérifie et désactive les annonces expirées :
 *  - Date de fin dépassée
 *  - Budget total épuisé
 *  - Limite d'impressions atteinte
 *  - Limite de clics atteinte
 */
class AdExpiryService
{
    /**
     * Parcourt toutes les annonces actives et expire celles qui ont atteint leurs limites.
     *
     * @return int Nombre d'annonces expirées
     */
    public function expireOutdatedAds(): int
    {
        $today   = now()->toDateString();
        $expired = 0;

        // Annonces actives uniquement
        $activeAds = DB::table('ads')->where('status', 'active')->get();

        foreach ($activeAds as $ad) {
            $shouldExpire = false;
            $reason       = '';

            // 1. Date de fin dépassée
            if ($ad->end_date && $today > $ad->end_date) {
                $shouldExpire = true;
                $reason       = 'date_end_passed';
            }

            // 2. Budget total épuisé
            if (!$shouldExpire && $ad->budget_total !== null && $ad->budget_spent >= $ad->budget_total) {
                $shouldExpire = true;
                $reason       = 'budget_exhausted';
            }

            // 3. Limite d'impressions atteinte
            if (!$shouldExpire && $ad->impression_limit) {
                $count = DB::table('ad_impressions')->where('ad_id', $ad->id)->count();
                if ($count >= $ad->impression_limit) {
                    $shouldExpire = true;
                    $reason       = 'impression_limit_reached';
                }
            }

            // 4. Limite de clics atteinte
            if (!$shouldExpire && $ad->click_limit) {
                $count = DB::table('ad_clicks')
                    ->where('ad_id', $ad->id)
                    ->where('is_fraud', false)
                    ->count();
                if ($count >= $ad->click_limit) {
                    $shouldExpire = true;
                    $reason       = 'click_limit_reached';
                }
            }

            if ($shouldExpire) {
                try {
                    DB::table('ads')->where('id', $ad->id)->update([
                        'status'     => 'expired',
                        'updated_at' => now(),
                    ]);
                    Log::info('AdExpiryService: annonce expirée', [
                        'ad_id'  => $ad->id,
                        'titre'  => $ad->titre,
                        'reason' => $reason,
                    ]);
                    $expired++;
                } catch (Throwable $e) {
                    Log::error('AdExpiryService: erreur expiration', ['ad_id' => $ad->id, 'error' => $e->getMessage()]);
                }
            }
        }

        return $expired;
    }

    /**
     * Réactive une annonce expirée si ses conditions sont valides
     * (nouvelle date de fin, budget rechargé, etc.)
     */
    public function reactivateAd(int $adId): bool
    {
        $ad = DB::table('ads')->find($adId);
        if (!$ad || $ad->status !== 'expired') return false;

        $today = now()->toDateString();

        // Vérifier que les conditions ne sont plus bloquantes
        if ($ad->end_date && $today > $ad->end_date) return false;
        if ($ad->budget_total !== null && $ad->budget_spent >= $ad->budget_total) return false;

        DB::table('ads')->where('id', $adId)->update([
            'status'     => 'active',
            'updated_at' => now(),
        ]);

        Log::info('AdExpiryService: annonce réactivée', ['ad_id' => $adId]);
        return true;
    }
}