<?php

namespace Vendor\AdsManager\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Throwable;

class AdDisplayService
{
    public function __construct(
        protected ?AdTargetingService $targetingService = null
    ) {
        $this->targetingService ??= new AdTargetingService();
    }

    // ----------------------------------------------------------------
    // PUBLIC API
    // ----------------------------------------------------------------

    /**
     * Rend une zone d'annonces par code de placement.
     *
     * Usage Blade : @adZone('sidebar_right')
     *               @adZone('sidebar_right', ['etablissement_id' => 5])
     *
     * @param  string $placementCode  Code unique de l'emplacement
     * @param  array  $context        Contexte optionnel (etablissement_id, page, audience)
     */
    public function renderZone(string $placementCode, array $context = []): string
    {
        try {
            $placement = $this->getPlacement($placementCode);
            if (! $placement) return '';

            $ads = $this->getAdsForPlacement($placement->id, $context);
            if ($ads->isEmpty()) return '';

            return $this->wrapZone($placementCode, $ads, $placement);

        } catch (Throwable $e) {
            Log::warning('AdDisplayService::renderZone', [
                'code'  => $placementCode,
                'error' => $e->getMessage(),
            ]);
            return '';
        }
    }

    /**
     * Rend une zone avec ciblage contextuel avancé.
     *
     * Usage Blade : @adZoneTargeted('sidebar_right', ['etablissement_id' => 5, 'page' => 'detail'])
     *
     * @param  string $placementCode
     * @param  array  $context
     */
    public function renderZoneTargeted(string $placementCode, array $context = []): string
    {
        try {
            $placement = $this->getPlacement($placementCode);
            if (! $placement) return '';

            $context['placement_id'] = $placement->id;
            $limit = min($placement->max_ads ?? 1, config('ads-manager.max_ads_per_zone', 3));
            $ads   = $this->targetingService->getTargetedAds($context, $limit);

            if ($ads->isEmpty()) return '';

            return $this->wrapZone($placementCode, $ads, $placement, 'ads-zone--targeted');

        } catch (Throwable $e) {
            Log::warning('AdDisplayService::renderZoneTargeted', [
                'code'  => $placementCode,
                'error' => $e->getMessage(),
            ]);
            return '';
        }
    }

    /**
     * Rend une bannière directe par ID d'annonce.
     *
     * Usage Blade : @adBanner(42)
     *
     * @param  int $adId
     */
    public function renderBanner(int $adId): string
    {
        try {
            $ad = Cache::remember("ad_{$adId}", 120, fn () =>
                DB::table('ads')
                    ->where('id', $adId)
                    ->where('status', 'active')
                    ->first()
            );

            if (! $ad) return '';

            return $this->renderSingleAd($ad, null);

        } catch (Throwable $e) {
            Log::warning('AdDisplayService::renderBanner', [
                'ad_id' => $adId,
                'error' => $e->getMessage(),
            ]);
            return '';
        }
    }

    // ----------------------------------------------------------------
    // INTERNAL HELPERS
    // ----------------------------------------------------------------

    protected function getPlacement(string $placementCode): ?object
    {
        return Cache::remember("ad_placement_{$placementCode}", 300, fn () =>
            DB::table('ad_placements')
                ->where('code', $placementCode)
                ->where('is_active', true)
                ->first()
        );
    }

    protected function wrapZone(string $code, $ads, $placement, string $extraClass = ''): string
    {
        $cls  = trim('ads-zone ' . $extraClass);
        $html = '<div class="' . e($cls) . '" data-placement="' . e($code) . '" '
              . 'style="display:flex;flex-direction:column;gap:8px;align-items:center;">';
        foreach ($ads as $ad) {
            $html .= $this->renderSingleAd($ad, $placement);
        }
        return $html . '</div>';
    }

    protected function getAdsForPlacement(int $placementId, array $context = [])
    {
        $today = now()->toDateString();

        return DB::table('ads')
            ->join('ad_placement', 'ads.id', '=', 'ad_placement.ad_id')
            ->where('ad_placement.placement_id', $placementId)
            ->where('ad_placement.is_active', true)
            ->where('ads.status', 'active')
            ->where(fn ($q) => $q->whereNull('ads.start_date')->orWhere('ads.start_date', '<=', $today))
            ->where(fn ($q) => $q->whereNull('ads.end_date')->orWhere('ads.end_date', '>=', $today))
            ->where(fn ($q) => $q->whereNull('ads.impression_limit')
                ->orWhereRaw('ads.impression_limit > (SELECT COUNT(*) FROM ad_impressions WHERE ad_id = ads.id)'))
            ->where(fn ($q) => $q->whereNull('ads.budget_total')
                ->orWhereRaw('ads.budget_total > ads.budget_spent'))
            ->select('ads.*')
            ->orderBy('ads.priority')
            ->limit(config('ads-manager.max_ads_per_zone', 3))
            ->get();
    }

    protected function renderSingleAd($ad, $placement): string
    {
        try {
            $trackClick      = route('ads-manager.track.click', $ad->id);
            $trackImpression = route('ads-manager.track.impression', $ad->id);
            $placementId     = $placement ? $placement->id : 0;
            $width           = (int) ($ad->width  ?? ($placement->width  ?? 300));
            $height          = (int) ($ad->height ?? ($placement->height ?? 250));

            $html  = '<div class="ad-unit" ';
            $html .= 'data-ad-id="' . $ad->id . '" ';
            $html .= 'data-placement-id="' . $placementId . '" ';
            $html .= 'style="width:' . $width . 'px;max-width:100%;display:inline-block;'
                   . 'position:relative;vertical-align:top;">';

            $html .= '<div style="font-size:9px;text-align:center;color:#94a3b8;'
                   . 'letter-spacing:1px;text-transform:uppercase;margin-bottom:2px;">Publicité</div>';

            $html .= match ($ad->type) {
                'image' => $this->renderImageAd($ad, $trackClick, $width, $height),
                'html'  => $this->renderHtmlAd($ad, $trackClick),
                'video' => $this->renderVideoAd($ad, $trackClick, $width, $height),
                'text'  => $this->renderTextAd($ad, $trackClick),
                default => '',
            };

            // Tracking pixel impression
            $html .= '<img src="' . $trackImpression . '?pid=' . $placementId . '" '
                   . 'width="1" height="1" alt="" '
                   . 'style="position:absolute;opacity:0;pointer-events:none;bottom:0;right:0;">';

            $html .= '</div>';

            // JS click tracker (initialisation unique)
            $html .= '<script>(function(){'
                   . 'var el=document.querySelector("[data-ad-id=\"' . $ad->id . '\"]");'
                   . 'if(el&&!el._adsInit){'
                   .   'el._adsInit=true;'
                   .   'el.addEventListener("click",function(){'
                   .     'var csrf=document.querySelector("meta[name=csrf-token]");'
                   .     'fetch("' . $trackClick . '",{method:"POST",'
                   .       'headers:{"X-CSRF-TOKEN":csrf?csrf.content:"","Content-Type":"application/json"}});'
                   .   '});'
                   . '}'
                   . '})();</script>';

            return $html;

        } catch (Throwable $e) {
            Log::warning('AdDisplayService::renderSingleAd', [
                'ad_id' => $ad->id ?? 0,
                'error' => $e->getMessage(),
            ]);
            return '';
        }
    }

    protected function renderImageAd($ad, string $trackClick, int $w, int $h): string
    {
        $src    = $ad->image_path ? asset('storage/' . $ad->image_path) : '';
        $target = $ad->open_new_tab ? 'target="_blank" rel="noopener noreferrer"' : '';
        $csrf   = 'document.querySelector(\'meta[name=csrf-token]\')?.content||\'\' ';

        return '<a href="' . e($ad->destination_url ?? '#') . '" ' . $target . ' '
             . 'onclick="fetch(\'' . $trackClick . '\','
             . '{method:\'POST\',headers:{\'X-CSRF-TOKEN\':' . $csrf . '}});" '
             . 'style="display:block;">'
             . '<img src="' . e($src) . '" alt="' . e($ad->titre) . '" loading="lazy" '
             . 'style="width:' . $w . 'px;height:' . $h . 'px;object-fit:cover;'
             . 'border-radius:8px;display:block;">'
             . '</a>';
    }

    protected function renderHtmlAd($ad, string $trackClick): string
    {
        $dest = e($ad->destination_url ?? '#');
        $csrf = 'document.querySelector(\'meta[name=csrf-token]\')?.content||\'\'';
        return '<div onclick="window.open(\'' . $dest . '\',\'_blank\');'
             . 'fetch(\'' . $trackClick . '\',{method:\'POST\','
             . 'headers:{\'X-CSRF-TOKEN\':\'' . $csrf . '\'}});" '
             . 'style="cursor:pointer;border-radius:8px;overflow:hidden;">'
             . $ad->html_content
             . '</div>';
    }

    protected function renderVideoAd($ad, string $trackClick, int $w, int $h): string
    {
        $target = $ad->open_new_tab ? 'target="_blank" rel="noopener noreferrer"' : '';
        return '<a href="' . e($ad->destination_url ?? '#') . '" ' . $target . ' style="display:block;">'
             . '<video width="' . $w . '" height="' . $h . '" autoplay muted loop playsinline '
             . 'style="border-radius:8px;display:block;object-fit:cover;">'
             . '<source src="' . e($ad->video_url) . '">'
             . '</video></a>';
    }

    protected function renderTextAd($ad, string $trackClick): string
    {
        $target = $ad->open_new_tab ? 'target="_blank" rel="noopener noreferrer"' : '';
        return '<a href="' . e($ad->destination_url ?? '#') . '" ' . $target . ' '
             . 'style="display:block;padding:14px 16px;background:#f8fafc;'
             . 'border:1px solid #e2e8f0;border-radius:8px;text-decoration:none;color:#1e293b;">'
             . '<strong style="font-size:14px;display:block;margin-bottom:4px;">' . e($ad->titre) . '</strong>'
             . ($ad->text_content
                 ? '<span style="font-size:12px;color:#64748b;">'
                   . e(\Vendor\AdsManager\Helpers\Helper::truncate($ad->text_content, 100))
                   . '</span>'
                 : '')
             . '<span style="font-size:11px;color:#667eea;display:block;margin-top:6px;">En savoir plus →</span>'
             . '</a>';
    }
}
