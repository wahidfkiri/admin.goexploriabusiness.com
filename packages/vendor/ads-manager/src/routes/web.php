<?php

use Illuminate\Support\Facades\Route;
use Vendor\AdsManager\Controllers\AdController;
use Vendor\AdsManager\Controllers\AdPlacementController;
use Vendor\AdsManager\Controllers\AdTrackingController;
use Vendor\AdsManager\Controllers\AdReportController;
use Vendor\AdsManager\Controllers\AdWidgetController;

/*
|--------------------------------------------------------------------------
| Routes du module Ads Manager
|--------------------------------------------------------------------------
*/

// -----------------------------------------------------------------------
// ROUTES PUBLIQUES — tracking pixel (appelées depuis les pages publiques)
// -----------------------------------------------------------------------
Route::prefix('ads')->name('ads-manager.')->group(function () {

    Route::get('/track/impression/{ad}', [AdTrackingController::class, 'trackImpression'])
        ->name('track.impression')
        ->where('ad', '[0-9]+');

    Route::match(['get', 'post'], '/track/click/{ad}', [AdTrackingController::class, 'trackClick'])
        ->name('track.click')
        ->where('ad', '[0-9]+');
});

// -----------------------------------------------------------------------
// ROUTES WIDGET — CORS ouvert, sans auth
// Permettent d'afficher des pubs sur n'importe quel site (interne/externe)
// -----------------------------------------------------------------------
Route::prefix('ads-widget')->name('ads-manager.widget.')->group(function () {

    // Script JS universel
    // Inclusion externe : <script src="https://votresite.com/ads-widget/loader.js" async></script>
    Route::get('/loader.js', [AdWidgetController::class, 'loaderJs'])
        ->name('loader');

    // Fragment HTML d'une zone (chargé par fetch dans loader.js)
    // GET /ads-widget/render/sidebar_right?eid=5&page=detail
    Route::get('/render/{code}', [AdWidgetController::class, 'zoneHtml'])
        ->name('render')
        ->where('code', '[a-z0-9_]+');

    // Fragment HTML d'une bannière unique
    // GET /ads-widget/banner/42
    Route::get('/banner/{id}', [AdWidgetController::class, 'bannerHtml'])
        ->name('banner')
        ->where('id', '[0-9]+');

    // JSON API (pour intégrations JS custom)
    // GET /ads-widget/zone/sidebar_right.json
    Route::get('/zone/{code}.json', [AdWidgetController::class, 'zoneJson'])
        ->name('zone.json')
        ->where('code', '[a-z0-9_]+');
});

// -----------------------------------------------------------------------
// ROUTES PROTÉGÉES — back-office admin
// -----------------------------------------------------------------------
Route::middleware(['auth', 'web'])->group(function () {

    /* ==================================================================
     * ANNONCES
     * ================================================================== */
    Route::prefix('ads-manager/ads')->name('ads-manager.ads.')->group(function () {

        Route::get('/',                [AdController::class, 'index']    )->name('index');
        Route::get('/create',          [AdController::class, 'create']   )->name('create');
        Route::post('/',               [AdController::class, 'store']    )->name('store');
        Route::get('/{id}',            [AdController::class, 'show']     )->name('show');
        Route::get('/{id}/edit',       [AdController::class, 'edit']     )->name('edit');
        Route::put('/{id}',            [AdController::class, 'update']   )->name('update');
        Route::delete('/{id}',         [AdController::class, 'destroy']  )->name('destroy');

        Route::post('/{id}/approve',   [AdController::class, 'approve']  )->name('approve');
        Route::post('/{id}/reject',    [AdController::class, 'reject']   )->name('reject');
        Route::post('/{id}/pause',     [AdController::class, 'pause']    )->name('pause');
        Route::post('/{id}/activate',  [AdController::class, 'activate'] )->name('activate');
        Route::post('/{id}/duplicate', [AdController::class, 'duplicate'])->name('duplicate');

        Route::get('/{id}/preview',    [AdController::class, 'preview']  )->name('preview');
        Route::get('/{id}/stats',      [AdController::class, 'stats']    )->name('stats');
    });

    /* ==================================================================
     * EMPLACEMENTS (PLACEMENTS)
     * ================================================================== */
    Route::prefix('ads-manager/placements')->name('ads-manager.placements.')->group(function () {

        Route::get('/',             [AdPlacementController::class, 'index']       )->name('index');
        Route::get('/create',       [AdPlacementController::class, 'create']      )->name('create');
        Route::post('/',            [AdPlacementController::class, 'store']       )->name('store');
        Route::get('/{id}/edit',    [AdPlacementController::class, 'edit']        )->name('edit');
        Route::put('/{id}',         [AdPlacementController::class, 'update']      )->name('update');
        Route::delete('/{id}',      [AdPlacementController::class, 'destroy']     )->name('destroy');
        Route::post('/{id}/toggle', [AdPlacementController::class, 'toggleActive'])->name('toggle');
        Route::get('/{id}/snippet', [AdPlacementController::class, 'getSnippet'] )->name('snippet');
    });

    /* ==================================================================
     * RAPPORTS & STATISTIQUES
     * ================================================================== */
    Route::prefix('ads-manager/reports')->name('ads-manager.reports.')->group(function () {

        Route::get('/',            [AdReportController::class, 'index']      )->name('index');
        Route::get('/ad/{id}',     [AdReportController::class, 'adReport']   )->name('ad');
        Route::post('/aggregate',  [AdReportController::class, 'aggregate']  )->name('aggregate');

        Route::get('/api/overview',[AdReportController::class, 'apiOverview'])->name('api.overview');
        Route::get('/api/ad/{id}', [AdReportController::class, 'apiAdStats'] )->name('api.ad');
    });
});
