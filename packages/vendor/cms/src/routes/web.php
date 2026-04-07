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
| Routes publiques (frontend)
|--------------------------------------------------------------------------
*/
Route::middleware(['web'])->group(function () {

    Route::prefix('/company/{etablissementId}')->name('cms.company.')->group(function () {

        Route::get('/', [WebThemeController::class, 'home'])->name('home');
        Route::get('/page/{slug}', [WebThemeController::class, 'showPage'])->name('page');
        Route::get('/sitemap.xml', [WebThemeController::class, 'sitemap'])->name('sitemap');
        Route::get('/robots.txt', [WebThemeController::class, 'robots'])->name('robots');

        Route::get('/preview/theme/{id}', [WebThemeController::class, 'publicPreview'])->name('preview.theme');
        Route::get('/preview/theme/{themeId}/page/{pageId}', [WebThemeController::class, 'previewPage'])->name('preview.page');
        Route::get('/preview/theme/{id}/quick', [WebThemeController::class, 'quickPreview'])->name('preview.quick');

        Route::get('/api/pages', [PublicPageController::class, 'getPages'])->name('api.pages');
        Route::get('/api/pages/{slug}', [PublicPageController::class, 'getPageBySlug'])->name('api.page');
        Route::get('/search', [PublicPageController::class, 'search'])->name('search');
        Route::get('/search/ajax', [PublicPageController::class, 'searchAjax'])->name('search.ajax');
        Route::get('/contact', [PublicPageController::class, 'contact'])->name('contact');
        Route::post('/contact/send', [PublicPageController::class, 'sendContact'])->name('contact.send');
        Route::post('/newsletter/subscribe', [PublicPageController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
        Route::get('/newsletter/unsubscribe/{token}', [PublicPageController::class, 'unsubscribeNewsletter'])->name('newsletter.unsubscribe');
        Route::post('/page/check-password', [PublicPageController::class, 'checkPassword'])->name('page.check-password');
        Route::get('/clear-preview', [WebThemeController::class, 'clearPreview'])->name('clear-preview');
    });

    Route::get('/themes/{etablissementId}/{themeSlug}/assets/{path}', [WebThemeController::class, 'asset'])
        ->where('path', '.*')
        ->name('cms.theme.asset');

    Route::fallback(function () {
        $etablissement = \App\Models\Etablissement::first();
        if ($etablissement) {
            return redirect()->route('cms.company.home', ['etablissementId' => $etablissement->id]);
        }
        abort(404, 'Page non trouvée');
    });

    
// Routes publiques pour robots et sitemap
Route::get('/company/{etablissementSlug}/robots.txt', [SettingController::class, 'robots']);
Route::get('/company/{etablissementSlug}/sitemap.xml', [SettingController::class, 'sitemap']);
});

/*
|--------------------------------------------------------------------------
| Routes administrateur (backend)
|--------------------------------------------------------------------------
*/
Auth::routes();

Route::middleware(['web', 'auth'])->group(function () {

    // ------------------------------------------------------------------
    // API pages (hors préfixe établissement)
    // ------------------------------------------------------------------
    Route::prefix('admin/cms/api/pages')->name('cms.admin.')->group(function () {
        Route::get('/{id}/load', [PageController::class, 'loadPageContent'])->name('pages.load');
        Route::put('/{id}', [PageController::class, 'updatePageContent']);
    });

    // ==================================================================
    // THÈMES GLOBAUX — hors préfixe établissement
    // URL: admin/cms/themes/*
    // ==================================================================
    Route::prefix('admin/cms/themes')->name('cms.admin.themes.')->group(function () {
        // Liste & upload global
        Route::get('/', [AdminThemeController::class, 'index'])->name('index');
        Route::post('/', [AdminThemeController::class, 'store'])->name('store');

        // Actions sur un thème global
        Route::delete('/{id}', [AdminThemeController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/duplicate', [AdminThemeController::class, 'duplicate'])->name('duplicate');
        Route::post('/{id}/upload-preview', [AdminThemeController::class, 'uploadPreview'])->name('upload-preview');
        Route::get('/export', [AdminThemeController::class, 'export'])->name('export');
    });

    // ==================================================================
    // ROUTES PAR ÉTABLISSEMENT
    // URL: admin/cms/{etablissementId}/*
    // ==================================================================
    Route::prefix('admin/cms/{etablissementId}')->name('cms.admin.')->group(function () {

    // Routes SEO
Route::prefix('/seo')->name('seo.')->group(function () {
    Route::get('/', [AdminSettingController::class, 'getSeoSettings'])->name('get');
    Route::post('/preview', [AdminSettingController::class, 'previewSeo'])->name('preview');
});


        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

        // Settings
        Route::prefix('/settings')->name('settings.')->group(function () {
            Route::get('/', [AdminSettingController::class, 'index'])->name('index');
            Route::post('/', [AdminSettingController::class, 'update'])->name('update');
            Route::get('/{group}', [AdminSettingController::class, 'group'])->name('group');
            Route::post('/bulk', [AdminSettingController::class, 'bulkUpdate'])->name('bulk');
            Route::post('/reset', [AdminSettingController::class, 'reset'])->name('reset');
        });

        // ------------------------------------------------------------------
        // Actions thèmes scoped à l'établissement (lier/activer/prévisualiser)
        // URL: admin/cms/{etablissementId}/themes/{id}/*
        // ------------------------------------------------------------------
        Route::prefix('/themes')->name('etab.themes.')->group(function () {
            Route::post('/{id}/activate', [AdminThemeController::class, 'activate'])->name('activate');
            Route::post('/{id}/deactivate', [AdminThemeController::class, 'deactivate'])->name('deactivate');
            Route::post('/{id}/attach', [AdminThemeController::class, 'attach'])->name('attach');
            Route::delete('/{id}/detach', [AdminThemeController::class, 'detach'])->name('detach');
            Route::get('/{id}/preview', [AdminThemeController::class, 'preview'])->name('preview');
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

        // Blocks
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

        // Media
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

/*
|--------------------------------------------------------------------------
| API publique
|--------------------------------------------------------------------------
*/
Route::prefix('api/cms')->middleware(['web'])->group(function () {
    Route::prefix('company/{etablissementId}')->name('cms.api.')->group(function () {
        Route::get('/pages', [PublicPageController::class, 'getPagesApi'])->name('pages');
        Route::get('/pages/{slug}', [PublicPageController::class, 'getPageApi'])->name('page');
        Route::get('/search', [PublicPageController::class, 'searchApi'])->name('search');
        Route::post('/newsletter/subscribe', [PublicPageController::class, 'subscribeApi'])->name('newsletter.subscribe');
        Route::post('/contact', [PublicPageController::class, 'contactApi'])->name('contact');
    });
});

Route::post('/webhook/cms/{token}', [PublicPageController::class, 'webhook'])->name('cms.webhook');