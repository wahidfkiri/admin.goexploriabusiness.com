<?php

use Illuminate\Support\Facades\Route;
use Vendor\AdsManager\Controllers\AdController;
use Vendor\AdsManager\Controllers\AdPlacementController;
use Vendor\AdsManager\Controllers\AdTrackingController;
use Vendor\AdsManager\Controllers\AdReportController;

/*
|--------------------------------------------------------------------------
| Routes du module Ads Manager
|--------------------------------------------------------------------------
*/

// -----------------------------------------------------------------------
// ROUTES PUBLIQUES — tracking (appelées depuis les pages publiques)
// -----------------------------------------------------------------------
Route::prefix('ads')->name('ads-manager.')->group(function () {

    // Impression pixel (image GET)
    Route::get('/track/impression/{ad}', [AdTrackingController::class, 'trackImpression'])
        ->name('track.impression')
        ->where('ad', '[0-9]+');

    // Clic (POST ajax + GET redirection)
    Route::match(['get', 'post'], '/track/click/{ad}', [AdTrackingController::class, 'trackClick'])
        ->name('track.click')
        ->where('ad', '[0-9]+');
});

// -----------------------------------------------------------------------
// ROUTES PROTÉGÉES — back-office
// -----------------------------------------------------------------------
Route::middleware(['auth', 'web'])->group(function () {

    /* ==================================================================
     * ANNONCES
     * ================================================================== */
    Route::prefix('ads-manager/ads')->name('ads-manager.ads.')->group(function () {

        // CRUD standard
        Route::get('/',                 [AdController::class, 'index']    )->name('index');
        Route::get('/create',           [AdController::class, 'create']   )->name('create');
        Route::post('/',                [AdController::class, 'store']    )->name('store');
        Route::get('/{id}',             [AdController::class, 'show']     )->name('show');
        Route::get('/{id}/edit',        [AdController::class, 'edit']     )->name('edit');
        Route::put('/{id}',             [AdController::class, 'update']   )->name('update');
        Route::delete('/{id}',          [AdController::class, 'destroy']  )->name('destroy');

        // Actions de modération
        Route::post('/{id}/approve',    [AdController::class, 'approve']  )->name('approve');
        Route::post('/{id}/reject',     [AdController::class, 'reject']   )->name('reject');
        Route::post('/{id}/pause',      [AdController::class, 'pause']    )->name('pause');
        Route::post('/{id}/activate',   [AdController::class, 'activate'] )->name('activate');
        Route::post('/{id}/duplicate',  [AdController::class, 'duplicate'])->name('duplicate');

        // Prévisualisation & stats JSON
        Route::get('/{id}/preview',     [AdController::class, 'preview']  )->name('preview');
        Route::get('/{id}/stats',       [AdController::class, 'stats']    )->name('stats');
    });

    /* ==================================================================
     * EMPLACEMENTS (PLACEMENTS)
     * ================================================================== */
    Route::prefix('ads-manager/placements')->name('ads-manager.placements.')->group(function () {

        Route::get('/',                          [AdPlacementController::class, 'index']       )->name('index');
        Route::get('/create',                    [AdPlacementController::class, 'create']      )->name('create');
        Route::post('/',                         [AdPlacementController::class, 'store']       )->name('store');
        Route::get('/{id}/edit',                 [AdPlacementController::class, 'edit']        )->name('edit');
        Route::put('/{id}',                      [AdPlacementController::class, 'update']      )->name('update');
        Route::delete('/{id}',                   [AdPlacementController::class, 'destroy']     )->name('destroy');
        Route::post('/{id}/toggle',              [AdPlacementController::class, 'toggleActive'])->name('toggle');
        Route::get('/{id}/snippet',              [AdPlacementController::class, 'getSnippet']  )->name('snippet');
    });

    /* ==================================================================
     * RAPPORTS & STATISTIQUES
     * ================================================================== */
    Route::prefix('ads-manager/reports')->name('ads-manager.reports.')->group(function () {

        Route::get('/',              [AdReportController::class, 'index']      )->name('index');
        Route::get('/ad/{id}',       [AdReportController::class, 'adReport']   )->name('ad');
        Route::post('/aggregate',    [AdReportController::class, 'aggregate']  )->name('aggregate');

        // API JSON pour les graphiques
        Route::get('/api/overview',  [AdReportController::class, 'apiOverview'])->name('api.overview');
        Route::get('/api/ad/{id}',   [AdReportController::class, 'apiAdStats'] )->name('api.ad');
    });
});

Route::middleware('web')->group(function () {
    // Route de test pour vérifier que le module est chargé
    Route::get('/ads-manager/test', function () {
        return view('ads-manager::etablissement-detail-with-ads', ['ads' => \Vendor\AdsManager\Services\AdTargetingService::getTargetedAds([
            'etablissement_id' => 1,
            'page'             => 'detail',
            'audience'         => 'students',
        ])]);
    });
});