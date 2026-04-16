<?php

namespace Vendor\AdsManager\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Throwable;

class AdDisplayService
{
    /**
     * Rend une zone complète d'annonces (par code de placement)
     */
    public function renderZone(string $placementCode, array $context = []): string
    {
        try {
            $placement = Cache::remember("ad_placement_{$placementCode}", 300, function () use ($placementCode) {
                return DB::table('ad_placements')
                    ->where('code', $placementCode)
                    ->where('is_active', true)
                    ->first();
            });

            if (!$placement) return '';

            $ads = $this->getAdsForPlacement($placement->id, $context);

            if ($ads->isEmpty()) return '';

            $html = '<div class="ads-zone" data-placement="' . e($placementCode) . '">';
            foreach ($ads as $ad) {
                $html .= $this->renderSingleAd($ad, $placement);
            }
            $html .= '</div>';

            return $html;

        } catch (Throwable $e) {
            Log::warning('AdDisplayService::renderZone error', ['code' => $placementCode, 'error' => $e->getMessage()]);
            return '';
        }
    }

    /**
     * Rend une bannière directe par ID d'annonce
     */
    public function renderBanner(int $adId): string
    {
        try {
            $ad = Cache::remember("ad_{$adId}", 120, function () use ($adId) {
                return DB::table('ads')->where('id', $adId)->where('status', 'active')->first();
            });

            if (!$ad) return '';

            return $this->renderSingleAd($ad, null);

        } catch (Throwable $e) {
            Log::warning('AdDisplayService::renderBanner error', ['ad_id' => $adId, 'error' => $e->getMessage()]);
            return '';
        }
    }

    /**
     * Récupère les annonces actives pour un emplacement
     */
    protected function getAdsForPlacement($placementId, array $context = [])
    {
        $today = now()->toDateString();

        return DB::table('ads')
            ->join('ad_placement', 'ads.id', '=', 'ad_placement.ad_id')
            ->where('ad_placement.placement_id', $placementId)
            ->where('ad_placement.is_active', true)
            ->where('ads.status', 'active')
            ->where(function ($q) use ($today) {
                $q->whereNull('ads.start_date')->orWhere('ads.start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('ads.end_date')->orWhere('ads.end_date', '>=', $today);
            })
            ->where(function ($q) {
                $q->whereNull('ads.impression_limit')
                  ->orWhereRaw('ads.impression_limit > (SELECT COUNT(*) FROM ad_impressions WHERE ad_id = ads.id)');
            })
            ->where(function ($q) {
                $q->whereNull('ads.budget_total')
                  ->orWhereRaw('ads.budget_total > ads.budget_spent');
            })
            ->select('ads.*')
            ->orderBy('ads.priority')
            ->limit(config('ads-manager.max_ads_per_zone', 3))
            ->get();
    }

    /**
     * Construit le HTML d'une seule annonce
     */
    protected function renderSingleAd($ad, $placement): string
    {
        $trackClick = route('ads-manager.track.click', $ad->id);
        $trackImpression = route('ads-manager.track.impression', $ad->id);
        $placementId = $placement ? $placement->id : 0;

        $width  = $ad->width  ?? ($placement->width  ?? 300);
        $height = $ad->height ?? ($placement->height ?? 250);

        $html = '<div class="ad-unit" '
              . 'data-ad-id="' . $ad->id . '" '
              . 'data-placement-id="' . $placementId . '" '
              . 'style="width:' . $width . 'px;max-width:100%;display:inline-block;position:relative;">'
              . '<div class="ad-label">Publicité</div>';

        $inner = match ($ad->type) {
            'image' => $this->renderImageAd($ad, $trackClick, $width, $height),
            'html'  => $this->renderHtmlAd($ad, $trackClick),
            'video' => $this->renderVideoAd($ad, $trackClick, $width, $height),
            'text'  => $this->renderTextAd($ad, $trackClick),
            default => '',
        };

        $html .= $inner;

        // Tracking pixel impression
        $html .= '<img src="' . $trackImpression . '?pid=' . $placementId . '" '
               . 'width="1" height="1" style="position:absolute;opacity:0;pointer-events:none;" alt="">';

        $html .= '</div>';

        // JS inline pour tracker le clic
        $html .= '<script>'
               . '(function(){var el=document.querySelector("[data-ad-id=\'' . $ad->id . '\']");'
               . 'if(el){el.addEventListener("click",function(){'
               . 'fetch("' . $trackClick . '",{method:"POST",headers:{"X-CSRF-TOKEN":document.querySelector("meta[name=csrf-token]")?.content||""}});'
               . '});}})();'
               . '</script>';

        return $html;
    }

    protected function renderImageAd($ad, string $trackClick, int $w, int $h): string
    {
        $src = $ad->image_path ? asset('storage/' . $ad->image_path) : '';
        return '<a href="' . e($ad->destination_url) . '" '
             . ($ad->open_new_tab ? 'target="_blank" rel="noopener"' : '') . ' '
             . 'onclick="fetch(\'' . $trackClick . '\',{method:\'POST\'});" '
             . 'style="display:block;">'
             . '<img src="' . e($src) . '" alt="' . e($ad->titre) . '" '
             . 'style="width:' . $w . 'px;height:' . $h . 'px;object-fit:cover;border-radius:8px;" loading="lazy">'
             . '</a>';
    }

    protected function renderHtmlAd($ad, string $trackClick): string
    {
        return '<div onclick="window.open(\'' . e($ad->destination_url) . '\',\'_blank\');fetch(\'' . $trackClick . '\',{method:\'POST\'});" '
             . 'style="cursor:pointer;">'
             . $ad->html_content
             . '</div>';
    }

    protected function renderVideoAd($ad, string $trackClick, int $w, int $h): string
    {
        return '<a href="' . e($ad->destination_url) . '" target="_blank" rel="noopener">'
             . '<video width="' . $w . '" height="' . $h . '" autoplay muted loop style="border-radius:8px;">'
             . '<source src="' . e($ad->video_url) . '">'
             . '</video></a>';
    }

    protected function renderTextAd($ad, string $trackClick): string
    {
        return '<a href="' . e($ad->destination_url) . '" '
             . ($ad->open_new_tab ? 'target="_blank" rel="noopener"' : '') . ' '
             . 'style="display:block;padding:12px;background:#f8fafc;border:1px solid #e2e8f0;'
             . 'border-radius:8px;text-decoration:none;color:#1e293b;">'
             . '<strong>' . e($ad->titre) . '</strong>'
             . ($ad->text_content ? '<p style="font-size:13px;color:#64748b;margin:4px 0 0;">' . e($ad->text_content) . '</p>' : '')
             . '</a>';
    }
}