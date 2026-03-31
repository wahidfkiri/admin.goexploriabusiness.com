<?php

use Illuminate\Support\Facades\Route;
use Vendor\Cms\Controllers\Web\PublicPageController;
use Vendor\Cms\Controllers\Web\WebThemeController;
use Vendor\Cms\Controllers\Admin\PageController;
use Vendor\Cms\Controllers\Admin\AdminThemeController;
use Vendor\Cms\Controllers\Admin\SettingController as AdminSettingController;
use Vendor\Cms\Controllers\Admin\DashboardController;
use Vendor\Cms\Controllers\Admin\MediaController;
use Vendor\Cms\Controllers\Admin\BlockController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Routes publiques (frontend) avec préfixe /company/{etablissementId}
|--------------------------------------------------------------------------
*/
Route::middleware(['web'])->group(function () {
    
    // Redirection de la racine vers le premier établissement
    // Route::get('/', function () {
    //     $etablissement = \App\Models\Etablissement::first();
    //     if ($etablissement) {
    //         return redirect()->route('cms.company.home', ['etablissementId' => $etablissement->id]);
    //     }
    //     abort(404, 'Aucun établissement trouvé');
    // });
    
    // ============================================
    // Routes avec préfixe /company/{etablissementId}
    // ============================================
    Route::prefix('/company/{etablissementId}')->name('cms.company.')->group(function () {
        
        // Page d'accueil - ACCEPTE LE PARAMETRE GET preview_theme
        Route::get('/', [WebThemeController::class, 'home'])->name('home');
        
        // Pages dynamiques - ACCEPTE LE PARAMETRE GET preview_theme
        Route::get('/page/{slug}', [WebThemeController::class, 'showPage'])->name('page');
        
        // Sitemap et robots
        Route::get('/sitemap.xml', [WebThemeController::class, 'sitemap'])->name('sitemap');
        Route::get('/robots.txt', [WebThemeController::class, 'robots'])->name('robots');
        
        // Routes de prévisualisation
        Route::get('/preview/theme/{id}', [WebThemeController::class, 'publicPreview'])->name('preview.theme');
        Route::get('/preview/theme/{themeId}/page/{pageId}', [WebThemeController::class, 'previewPage'])->name('preview.page');
        Route::get('/preview/theme/{id}/quick', [WebThemeController::class, 'quickPreview'])->name('preview.quick');
        
        // Routes des thèmes
        Route::get('/themes', [WebThemeController::class, 'index'])->name('themes.index');
        Route::get('/themes/{id}', [WebThemeController::class, 'show'])->name('themes.show');
        Route::get('/themes/{id}/download', [WebThemeController::class, 'download'])->name('themes.download');
        
        // API
        Route::get('/api/pages', [PublicPageController::class, 'getPages'])->name('api.pages');
        Route::get('/api/pages/{slug}', [PublicPageController::class, 'getPageBySlug'])->name('api.page');
        
        // Recherche
        Route::get('/search', [PublicPageController::class, 'search'])->name('search');
        Route::get('/search/ajax', [PublicPageController::class, 'searchAjax'])->name('search.ajax');
        
        // Contact
        Route::get('/contact', [PublicPageController::class, 'contact'])->name('contact');
        Route::post('/contact/send', [PublicPageController::class, 'sendContact'])->name('contact.send');
        
        // Newsletter
        Route::post('/newsletter/subscribe', [PublicPageController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
        Route::get('/newsletter/unsubscribe/{token}', [PublicPageController::class, 'unsubscribeNewsletter'])->name('newsletter.unsubscribe');
        
        // Mot de passe
        Route::post('/page/check-password', [PublicPageController::class, 'checkPassword'])->name('page.check-password');
        
        // Nettoyer la prévisualisation
        Route::get('/clear-preview', [WebThemeController::class, 'clearPreview'])->name('clear-preview');
    });
    
    // Route pour les assets des thèmes (en dehors du préfixe company)
    Route::get('/themes/{etablissementId}/{themeSlug}/assets/{path}', [WebThemeController::class, 'asset'])
    ->where('path', '.*')
    ->name('cms.theme.asset');
    
    // Route de fallback
    Route::fallback(function () {
        $etablissement = \App\Models\Etablissement::first();
        if ($etablissement) {
            return redirect()->route('cms.company.home', ['etablissementId' => $etablissement->id]);
        }
        abort(404, 'Page non trouvée');
    });
});

/*
|--------------------------------------------------------------------------
| Routes administrateur (backend)
|--------------------------------------------------------------------------
*/
Auth::routes();

Route::middleware(['web', 'auth'])->group(function () {

    Route::prefix('admin/cms/api/pages')->name('cms.admin.')->group(function () {
        Route::get('/{id}/load', [PageController::class, 'loadPageContent'])->name('pages.load');
        Route::put('/{id}', [PageController::class, 'updatePageContent']);
    });
    
    Route::prefix('admin/cms/{etablissementId}')->name('cms.admin.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
        
        // Configuration
        Route::prefix('/settings')->name('settings.')->group(function () {
            Route::get('/', [AdminSettingController::class, 'index'])->name('index');
            Route::post('/', [AdminSettingController::class, 'update'])->name('update');
            Route::get('/{group}', [AdminSettingController::class, 'group'])->name('group');
            Route::post('/bulk', [AdminSettingController::class, 'bulkUpdate'])->name('bulk');
            Route::post('/reset', [AdminSettingController::class, 'reset'])->name('reset');
        });
        
        // Thèmes
        Route::prefix('/themes')->name('themes.')->group(function () {
           Route::post('/', [AdminThemeController::class, 'store'])->name('store');
            Route::get('/', [AdminThemeController::class, 'index'])->name('index');
            Route::get('/create', [AdminThemeController::class, 'create'])->name('create');
            Route::get('/{id}', [AdminThemeController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [AdminThemeController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminThemeController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminThemeController::class, 'destroy'])->name('destroy');
            
            Route::post('/{id}/activate', [AdminThemeController::class, 'activate'])->name('activate');
            Route::post('/{id}/deactivate', [AdminThemeController::class, 'deactivate'])->name('deactivate');
            Route::get('/{id}/preview', [AdminThemeController::class, 'preview'])->name('preview');
            Route::post('/{id}/duplicate', [AdminThemeController::class, 'duplicate'])->name('duplicate');
            Route::post('/{id}/upload-preview', [AdminThemeController::class, 'uploadPreview'])->name('upload-preview');
            Route::delete('/{id}/delete-preview', [AdminThemeController::class, 'deletePreview'])->name('delete-preview');
            
            Route::post('/bulk/delete', [AdminThemeController::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('/bulk/activate', [AdminThemeController::class, 'bulkActivate'])->name('bulk-activate');
            Route::get('/export', [AdminThemeController::class, 'export'])->name('export');
        });
        
        // Pages
        Route::prefix('/pages')->name('pages.')->group(function () {
            Route::get('/', [PageController::class, 'index'])->name('index');
            Route::get('/create', [PageController::class, 'create'])->name('create');
            Route::post('/', [PageController::class, 'store'])->name('store');
            Route::get('/{id}', [PageController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [PageController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PageController::class, 'update'])->name('update');
            Route::delete('/{id}', [PageController::class, 'destroy'])->name('destroy');
            
            Route::post('/{id}/publish', [PageController::class, 'publish'])->name('publish');
            Route::post('/{id}/unpublish', [PageController::class, 'unpublish'])->name('unpublish');
            Route::post('/{id}/duplicate', [PageController::class, 'duplicate'])->name('duplicate');
            Route::post('/{id}/save-content', [PageController::class, 'saveContent'])->name('save-content');
            Route::get('/{id}/preview', [PageController::class, 'preview'])->name('preview');
            Route::post('/{id}/set-as-home', [PageController::class, 'setAsHome'])->name('set-as-home');
            
            Route::post('/bulk/delete', [PageController::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('/bulk/status', [PageController::class, 'bulkUpdateStatus'])->name('bulk-status');
            Route::get('/export', [PageController::class, 'export'])->name('export');
            Route::get('/data/all', [PageController::class, 'getAllData'])->name('data');
            Route::get('/data/{id}', [PageController::class, 'getData'])->name('data.single');
            
            Route::post('/{id}/restore', [PageController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force', [PageController::class, 'forceDelete'])->name('force-delete');
            Route::post('/reorder', [PageController::class, 'reorder'])->name('reorder');

            Route::get('/{id}/edit-content', [PageController::class, 'editContent'])->name('edit-content');
            Route::put('/{id}/update-content', [PageController::class, 'updateContent'])->name('update-content');
        });

        // Dans le groupe admin
Route::prefix('/blocks')->name('blocks.')->group(function () {
    Route::get('/', [BlockController::class, 'index'])->name('index');
    Route::get('/api', [BlockController::class, 'api'])->name('api');
    Route::get('/categories', [BlockController::class, 'categories'])->name('categories');
});
        
        // Cache
        Route::prefix('/cache')->name('cache.')->group(function () {
            Route::post('/clear', [DashboardController::class, 'clearCache'])->name('clear');
            Route::post('/clear-pages', [DashboardController::class, 'clearPageCache'])->name('clear-pages');
            Route::post('/clear-themes', [DashboardController::class, 'clearThemeCache'])->name('clear-themes');
        });
        
        // Maintenance
        Route::prefix('/maintenance')->name('maintenance.')->group(function () {
            Route::post('/enable', [DashboardController::class, 'enableMaintenance'])->name('enable');
            Route::post('/disable', [DashboardController::class, 'disableMaintenance'])->name('disable');
        });
        
        // Backup
        Route::prefix('/backup')->name('backup.')->group(function () {
            Route::get('/', [DashboardController::class, 'backup'])->name('index');
            Route::post('/create', [DashboardController::class, 'createBackup'])->name('create');
            Route::delete('/{file}', [DashboardController::class, 'deleteBackup'])->name('delete');
            Route::get('/download/{file}', [DashboardController::class, 'downloadBackup'])->name('download');
        });
        
        // Logs
        Route::prefix('/logs')->name('logs.')->group(function () {
            Route::get('/', [DashboardController::class, 'logs'])->name('index');
            Route::delete('/clear', [DashboardController::class, 'clearLogs'])->name('clear');
            Route::get('/download', [DashboardController::class, 'downloadLogs'])->name('download');
        });
        
        // Profil
        Route::prefix('/profile')->name('profile.')->group(function () {
            Route::get('/', [DashboardController::class, 'profile'])->name('index');
            Route::put('/', [DashboardController::class, 'updateProfile'])->name('update');
            Route::put('/password', [DashboardController::class, 'updatePassword'])->name('password');
        });
        
        // Notifications
        Route::prefix('/notifications')->name('notifications.')->group(function () {
            Route::get('/', [DashboardController::class, 'notifications'])->name('index');
            Route::post('/{id}/read', [DashboardController::class, 'markAsRead'])->name('read');
            Route::post('/read-all', [DashboardController::class, 'markAllAsRead'])->name('read-all');
        });

        // Dans le groupe admin
Route::prefix('/media')->name('media.')->group(function () {
    Route::get('/', [MediaController::class, 'index'])->name('index');
    Route::post('/upload', [MediaController::class, 'upload'])->name('upload');
    Route::delete('/{id}', [MediaController::class, 'destroy'])->name('destroy');
    Route::post('/bulk/delete', [MediaController::class, 'bulkDelete'])->name('bulk-delete');
    Route::put('/{id}', [MediaController::class, 'update'])->name('update');
    Route::get('/folder/{folder}', [MediaController::class, 'folder'])->name('folder');
    Route::post('/folder/create', [MediaController::class, 'createFolder'])->name('create-folder');
    Route::get('/export', [MediaController::class, 'export'])->name('export');
    Route::get('/{id}', [MediaController::class, 'getMedia'])->name('get');
});
    });
});

// Routes API
Route::prefix('api/cms')->middleware(['web'])->group(function () {
    
    Route::prefix('company/{etablissementId}')->name('cms.api.')->group(function () {
        Route::get('/pages', [PublicPageController::class, 'getPagesApi'])->name('pages');
        Route::get('/pages/{slug}', [PublicPageController::class, 'getPageApi'])->name('page');
        Route::get('/search', [PublicPageController::class, 'searchApi'])->name('search');
        Route::post('/newsletter/subscribe', [PublicPageController::class, 'subscribeApi'])->name('newsletter.subscribe');
        Route::post('/contact', [PublicPageController::class, 'contactApi'])->name('contact');
    });
});

// Webhook
Route::post('/webhook/cms/{token}', [PublicPageController::class, 'webhook'])->name('cms.webhook');