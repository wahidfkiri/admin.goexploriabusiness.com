<?php

namespace Vendor\AdsManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * AdWidgetController
 * ──────────────────────────────────────────────────────────────
 * Expose une API + un script JS universel permettant d'afficher
 * des publicités sur N'IMPORTE QUELLE page :
 *
 *  • Pages Laravel internes  → @adZone() ou <div class="ad-zone">
 *  • Sites externes (autre domaine) → inclusion d'un <script> tag
 *
 * Endpoints :
 *   GET  /ads-widget/zone/{code}        → JSON : liste des annonces
 *   GET  /ads-widget/render/{code}      → HTML prêt à injecter (CORS ouvert)
 *   GET  /ads-widget/loader.js          → Script JS universel à inclure
 *   GET  /ads-widget/banner/{id}        → HTML d'une bannière unique
 * ──────────────────────────────────────────────────────────────
 */
class AdWidgetController extends Controller
{
    /* ------------------------------------------------------------------ */
    /*  JSON API : données brutes de la zone                               */
    /* ------------------------------------------------------------------ */

    public function zoneJson(Request $request, string $code)
    {
        try {
            $placement = $this->getPlacement($code);
            if (! $placement) {
                return response()->json(['ads' => [], 'zone' => $code]);
            }

            $ads = $this->getActiveAds($placement->id);

            $payload = $ads->map(fn ($ad) => [
                'id'              => $ad->id,
                'titre'           => $ad->titre,
                'type'            => $ad->type,
                'image_url'       => $ad->image_path ? asset('storage/' . $ad->image_path) : null,
                'video_url'       => $ad->video_url,
                'html_content'    => $ad->html_content,
                'text_content'    => $ad->text_content,
                'destination_url' => $ad->destination_url,
                'open_new_tab'    => (bool) $ad->open_new_tab,
                'width'           => $ad->width  ?? $placement->width  ?? 300,
                'height'          => $ad->height ?? $placement->height ?? 250,
                'track_impression'=> route('ads-manager.track.impression', $ad->id),
                'track_click'     => route('ads-manager.track.click',      $ad->id),
            ]);

            return response()
                ->json(['ads' => $payload, 'zone' => $code, 'placement_id' => $placement->id])
                ->header('Access-Control-Allow-Origin', '*');

        } catch (Throwable $e) {
            Log::warning('AdWidgetController::zoneJson', ['code' => $code, 'error' => $e->getMessage()]);
            return response()->json(['ads' => [], 'error' => 'unavailable'], 200)
                ->header('Access-Control-Allow-Origin', '*');
        }
    }

    /* ------------------------------------------------------------------ */
    /*  HTML RENDER : fragment HTML prêt-à-injecter (CORS ouvert)         */
    /* ------------------------------------------------------------------ */

    public function zoneHtml(Request $request, string $code)
    {
        try {
            $context = [
                'etablissement_id' => $request->integer('eid')   ?: null,
                'page'             => $request->string('page')    ?: null,
                'audience'         => $request->string('audience') ?: null,
            ];

            $service = app(\Vendor\AdsManager\Services\AdDisplayService::class);
            $html    = $context['etablissement_id'] || $context['page']
                ? $service->renderZoneTargeted($code, array_filter($context))
                : $service->renderZone($code);

            return response($html ?: '')
                ->header('Content-Type', 'text/html; charset=utf-8')
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Cache-Control', 'no-store');

        } catch (Throwable $e) {
            return response('', 200)->header('Access-Control-Allow-Origin', '*');
        }
    }

    /* ------------------------------------------------------------------ */
    /*  HTML RENDER : bannière unique par ID                               */
    /* ------------------------------------------------------------------ */

    public function bannerHtml(int $id)
    {
        try {
            $html = app(\Vendor\AdsManager\Services\AdDisplayService::class)->renderBanner($id);
            return response($html ?: '')
                ->header('Content-Type', 'text/html; charset=utf-8')
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Cache-Control', 'no-store');
        } catch (Throwable $e) {
            return response('', 200)->header('Access-Control-Allow-Origin', '*');
        }
    }

    /* ------------------------------------------------------------------ */
    /*  SCRIPT JS UNIVERSEL : loader.js                                    */
    /* ------------------------------------------------------------------ */

    public function loaderJs(Request $request)
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $version = config('ads-manager.widget_version', '1.0');

        $js = <<<JS
/**
 * AdsManager Universal Widget v{$version}
 * Incluez ce script sur n'importe quelle page pour afficher vos publicités.
 *
 * USAGE — Pages internes Laravel :
 *   <div class="am-zone" data-zone="sidebar_right"></div>
 *
 * USAGE — Sites externes (autre domaine) :
 *   <script src="{$baseUrl}/ads-widget/loader.js" async></script>
 *   <div class="am-zone" data-zone="sidebar_right"></div>
 *
 * OPTIONS data-* sur le div :
 *   data-zone        (requis) code de la zone publicitaire
 *   data-eid         id de l'établissement (ciblage)
 *   data-page        contexte de page : home, detail, list, blog, search
 *   data-audience    audience : students, parents, staff, all
 *   data-align       left | center (défaut) | right
 *
 * USAGE — Banner unique par ID :
 *   <div class="am-banner" data-id="42"></div>
 */
(function (window, document) {
  'use strict';

  var BASE = '{$baseUrl}';
  var RENDER_ZONE   = BASE + '/ads-widget/render/';
  var RENDER_BANNER = BASE + '/ads-widget/banner/';

  /* -----------------------------------------------------------
   * Charge le HTML d'une zone et l'injecte dans le conteneur
   * ----------------------------------------------------------- */
  function loadZone(container) {
    var code     = container.getAttribute('data-zone');
    var eid      = container.getAttribute('data-eid')      || '';
    var page     = container.getAttribute('data-page')     || '';
    var audience = container.getAttribute('data-audience') || '';
    var align    = container.getAttribute('data-align')    || 'center';

    if (!code) return;

    var url = RENDER_ZONE + encodeURIComponent(code);
    var params = [];
    if (eid)      params.push('eid='      + encodeURIComponent(eid));
    if (page)     params.push('page='     + encodeURIComponent(page));
    if (audience) params.push('audience=' + encodeURIComponent(audience));
    if (params.length) url += '?' + params.join('&');

    container.style.textAlign = align;
    container.setAttribute('data-am-loading', '1');

    fetch(url, { method: 'GET', mode: 'cors', cache: 'no-store' })
      .then(function (res) { return res.text(); })
      .then(function (html) {
        if (html && html.trim()) {
          container.innerHTML = html;
          // Exécuter les scripts inline injectés
          executeInlineScripts(container);
        }
        container.removeAttribute('data-am-loading');
      })
      .catch(function () {
        container.removeAttribute('data-am-loading');
      });
  }

  /* -----------------------------------------------------------
   * Charge le HTML d'une bannière unique et l'injecte
   * ----------------------------------------------------------- */
  function loadBanner(container) {
    var id = container.getAttribute('data-id');
    if (!id) return;

    var url   = RENDER_BANNER + encodeURIComponent(id);
    var align = container.getAttribute('data-align') || 'center';

    container.style.textAlign = align;

    fetch(url, { method: 'GET', mode: 'cors', cache: 'no-store' })
      .then(function (res) { return res.text(); })
      .then(function (html) {
        if (html && html.trim()) {
          container.innerHTML = html;
          executeInlineScripts(container);
        }
      })
      .catch(function () {});
  }

  /* -----------------------------------------------------------
   * Ré-exécute les <script> injectés dynamiquement
   * (nécessaire car innerHTML ne les exécute pas)
   * ----------------------------------------------------------- */
  function executeInlineScripts(container) {
    var scripts = container.querySelectorAll('script');
    scripts.forEach(function (oldScript) {
      var newScript = document.createElement('script');
      if (oldScript.src) {
        newScript.src = oldScript.src;
      } else {
        newScript.textContent = oldScript.textContent;
      }
      oldScript.parentNode.replaceChild(newScript, oldScript);
    });
  }

  /* -----------------------------------------------------------
   * Initialise tous les conteneurs présents dans la page
   * ----------------------------------------------------------- */
  function init() {
    // Zones
    var zones = document.querySelectorAll('.am-zone[data-zone]:not([data-am-loading])');
    zones.forEach(function (el) { loadZone(el); });

    // Banners
    var banners = document.querySelectorAll('.am-banner[data-id]:not([data-am-loading])');
    banners.forEach(function (el) { loadBanner(el); });
  }

  /* -----------------------------------------------------------
   * API publique (window.AdsManager)
   * ----------------------------------------------------------- */
  window.AdsManager = {
    version : '{$version}',
    baseUrl  : BASE,

    /** Charge manuellement une zone dans un élément */
    loadZone: function (elementOrSelector, zoneCode, options) {
      var el = typeof elementOrSelector === 'string'
        ? document.querySelector(elementOrSelector)
        : elementOrSelector;
      if (!el) return;
      options = options || {};
      if (zoneCode) el.setAttribute('data-zone', zoneCode);
      if (options.eid)      el.setAttribute('data-eid',      options.eid);
      if (options.page)     el.setAttribute('data-page',     options.page);
      if (options.audience) el.setAttribute('data-audience', options.audience);
      if (options.align)    el.setAttribute('data-align',    options.align);
      loadZone(el);
    },

    /** Charge manuellement une bannière par ID */
    loadBanner: function (elementOrSelector, adId) {
      var el = typeof elementOrSelector === 'string'
        ? document.querySelector(elementOrSelector)
        : elementOrSelector;
      if (!el) return;
      if (adId) el.setAttribute('data-id', adId);
      loadBanner(el);
    },

    /** Ré-initialise toutes les zones et bannières de la page */
    refresh: function () { init(); },
  };

  /* -----------------------------------------------------------
   * Auto-init au chargement du DOM
   * ----------------------------------------------------------- */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

}(window, document));
JS;

        return response($js)
            ->header('Content-Type', 'application/javascript; charset=utf-8')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /* ------------------------------------------------------------------ */
    /*  HELPERS                                                             */
    /* ------------------------------------------------------------------ */

    protected function getPlacement(string $code): ?object
    {
        return Cache::remember("ad_placement_{$code}", 300, fn () =>
            DB::table('ad_placements')
                ->where('code', $code)
                ->where('is_active', true)
                ->first()
        );
    }

    protected function getActiveAds(int $placementId)
    {
        $today = now()->toDateString();

        return DB::table('ads')
            ->join('ad_placement', 'ads.id', '=', 'ad_placement.ad_id')
            ->where('ad_placement.placement_id', $placementId)
            ->where('ad_placement.is_active', true)
            ->where('ads.status', 'active')
            ->where(fn ($q) => $q->whereNull('ads.start_date')->orWhere('ads.start_date', '<=', $today))
            ->where(fn ($q) => $q->whereNull('ads.end_date')->orWhere('ads.end_date', '>=', $today))
            ->where(fn ($q) => $q->whereNull('ads.budget_total')
                ->orWhereRaw('ads.budget_total > ads.budget_spent'))
            ->select('ads.*')
            ->orderBy('ads.priority')
            ->limit(config('ads-manager.max_ads_per_zone', 3))
            ->get();
    }
}
