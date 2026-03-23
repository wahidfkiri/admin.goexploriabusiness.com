<?php

use Illuminate\Support\Facades\Route;
use Vendor\Cms\Controllers\Web\PublicPageController;
use Vendor\Cms\Controllers\Web\ThemeController;
use Vendor\Cms\Controllers\Admin\PageController as AdminPageController;
use Vendor\Cms\Controllers\Admin\ThemeController as AdminThemeController;
use Vendor\Cms\Controllers\Admin\SettingController as AdminSettingController;
use Vendor\Cms\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Routes publiques (frontend)
|--------------------------------------------------------------------------
*/
Route::middleware(['web'])->group(function () {
    // Pages publiques
    Route::get('/', [PublicPageController::class, 'home'])->name('cms.home');
    Route::get('/page/{slug}', [PublicPageController::class, 'show'])->name('cms.page.show');
    
    // Route pour les assets des thèmes - CORRIGÉE
    Route::get('/themes/{etablissementId}/{themeId}/assets/{path}', [PublicPageController::class, 'asset'])
        ->where('path', '.*')
        ->name('cms.theme.asset');
    
    // Route de fallback
    Route::fallback([PublicPageController::class, 'fallback']);
});

/*
|--------------------------------------------------------------------------
| Routes administrateur (backend)
|--------------------------------------------------------------------------
*/
Auth::routes();

Route::middleware(['web', 'auth'])->group(function () {
    
    Route::prefix('admin/cms')->group(function () {
        
        // Dashboard CMS
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('cms.admin.dashboard');
        
        // Routes pour la gestion des pages
        Route::resource('pages', AdminPageController::class)->names('cms.admin.pages');
        Route::post('pages/{page}/publish', [AdminPageController::class, 'publish'])->name('cms.admin.pages.publish');
        Route::post('pages/{page}/unpublish', [AdminPageController::class, 'unpublish'])->name('cms.admin.pages.unpublish');
        Route::post('pages/{page}/duplicate', [AdminPageController::class, 'duplicate'])->name('cms.admin.pages.duplicate');
        Route::get('pages/{page}/preview', [AdminPageController::class, 'preview'])->name('cms.admin.pages.preview');
        Route::post('pages/{page}/save-content', [AdminPageController::class, 'saveContent'])->name('cms.admin.pages.save-content');
        
        // Routes pour la gestion des configurations
        Route::get('settings', [AdminSettingController::class, 'index'])->name('cms.admin.settings.index');
        Route::post('settings', [AdminSettingController::class, 'update'])->name('cms.admin.settings.update');
        Route::get('settings/{group}', [AdminSettingController::class, 'group'])->name('cms.admin.settings.group');
        Route::post('settings/bulk', [AdminSettingController::class, 'bulkUpdate'])->name('cms.admin.settings.bulk');
        
        // Routes pour les médias (si nécessaire)
        Route::get('media', [AdminMediaController::class, 'index'])->name('cms.admin.media.index');
        Route::post('media/upload', [AdminMediaController::class, 'upload'])->name('cms.admin.media.upload');
        Route::delete('media/{media}', [AdminMediaController::class, 'destroy'])->name('cms.admin.media.destroy');
        
        // Theme routes
        Route::get('/themes', [AdminThemeController::class, 'index'])->name('cms.admin.themes.index');
        Route::post('/themes', [AdminThemeController::class, 'store'])->name('cms.admin.themes.store');
        Route::post('/themes/{id}/activate', [AdminThemeController::class, 'activate'])->name('cms.admin.themes.activate');
        Route::post('/themes/{id}/deactivate', [AdminThemeController::class, 'deactivate'])->name('cms.admin.themes.deactivate');
        Route::delete('/themes/{id}', [AdminThemeController::class, 'destroy'])->name('cms.admin.themes.destroy');
        Route::get('/themes/{id}/edit', [AdminThemeController::class, 'edit'])->name('cms.admin.themes.edit');
        Route::put('/themes/{id}', [AdminThemeController::class, 'update'])->name('cms.admin.themes.update');
        
        // IMPORTANT: Route de prévisualisation - doit être dans le groupe auth
        Route::get('/themes/{id}/preview', [AdminThemeController::class, 'preview'])->name('cms.admin.themes.preview');
    });
});

// Si vous voulez que la prévisualisation soit accessible sans authentification (optionnel)
// Décommentez cette partie si vous voulez permettre la prévisualisation publique
/*
Route::middleware(['web'])->group(function () {
    Route::get('/preview/theme/{id}', [AdminThemeController::class, 'publicPreview'])->name('cms.themes.public.preview');
});
*/