<?php

namespace Vendor\AdsManager\Services;

use Illuminate\Support\Facades\DB;

/**
 * AdTargetingService
 * Filtre les annonces selon les critères de ciblage :
 *  - Établissement
 *  - Catégorie
 *  - Page context
 *  - Audience
 *  - Date de validité
 *  - Budget disponible
 */
class AdTargetingService
{
    /**
     * Retourne les annonces valides pour un contexte donné
     *
     * @param array $context [
     *   'etablissement_id' => int|null,
     *   'page'             => string|null,   // 'home', 'detail', 'list', 'blog', 'search'
     *   'audience'         => string|null,   // 'students', 'parents', 'staff', 'all'
     *   'placement_id'     => int|null,
     * ]
     */
    public function getTargetedAds(array $context = [], int $limit = 3): \Illuminate\Support\Collection
    {
        $today = now()->toDateString();

        $query = DB::table('ads')
            ->where('ads.status', 'active')
            ->where(function ($q) use ($today) {
                $q->whereNull('ads.start_date')->orWhere('ads.start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('ads.end_date')->orWhere('ads.end_date', '>=', $today);
            })
            ->where(function ($q) {
                $q->whereNull('ads.budget_total')
                  ->orWhereRaw('ads.budget_total > ads.budget_spent');
            })
            ->where(function ($q) {
                $q->whereNull('ads.impression_limit')
                  ->orWhereRaw('ads.impression_limit > (SELECT COUNT(*) FROM ad_impressions WHERE ad_id = ads.id)');
            })
            ->where(function ($q) {
                $q->whereNull('ads.click_limit')
                  ->orWhereRaw('ads.click_limit > (SELECT COUNT(*) FROM ad_clicks WHERE ad_id = ads.id AND is_fraud = 0)');
            });

        // Filtrer par emplacement
        if (!empty($context['placement_id'])) {
            $query->join('ad_placement', 'ads.id', '=', 'ad_placement.ad_id')
                  ->where('ad_placement.placement_id', $context['placement_id'])
                  ->where('ad_placement.is_active', true);
        }

        // Récupérer toutes les annonces candidates
        $candidates = $query->select('ads.*')->orderBy('ads.priority')->get();

        // Filtrage applicatif (JSON fields)
        return $candidates->filter(function ($ad) use ($context) {
            return $this->matchesContext($ad, $context);
        })->take($limit)->values();
    }

    /**
     * Vérifie si une annonce correspond au contexte
     */
    public function matchesContext($ad, array $context): bool
    {
        // Établissement
        if (!empty($context['etablissement_id'])) {
            $targets = json_decode($ad->target_etablissements ?? '[]', true);
            if (!empty($targets) && !in_array('all', $targets) && !in_array($context['etablissement_id'], $targets)) {
                return false;
            }
        }

        // Page context
        if (!empty($context['page'])) {
            $pages = json_decode($ad->target_pages ?? '[]', true);
            if (!empty($pages) && !in_array($context['page'], $pages)) {
                return false;
            }
        }

        // Audience
        if (!empty($context['audience']) && $ad->target_audience && $ad->target_audience !== 'all') {
            if ($ad->target_audience !== $context['audience']) {
                return false;
            }
        }

        return true;
    }

    /**
     * Retourne les annonces d'un emplacement avec ciblage complet
     */
    public function getAdsForPlacementCode(string $placementCode, array $context = []): \Illuminate\Support\Collection
    {
        $placement = DB::table('ad_placements')
            ->where('code', $placementCode)
            ->where('is_active', true)
            ->first();

        if (!$placement) return collect();

        $context['placement_id'] = $placement->id;
        $limit = min($placement->max_ads, config('ads-manager.max_ads_per_zone', 3));

        return $this->getTargetedAds($context, $limit);
    }
}