<?php 

use Illuminate\Support\Facades\Route;
use Vendor\Administration\Controllers\SliderController;
use Vendor\Administration\Controllers\MenuController;
use Vendor\Administration\Controllers\MenuPageController;
use Vendor\Administration\Controllers\PublicPageController;
use Vendor\Administration\Controllers\BlockController;
use Vendor\Administration\Controllers\TemplateController;
use Vendor\Administration\Controllers\LocationController;
use Vendor\Administration\Controllers\PlanController;
use Vendor\Administration\Controllers\AbonnementController;



Auth::routes();

Route::middleware(['auth','web'])->group(function () {


Route::prefix('api/locations')->group(function () {
    Route::get('/countries', [LocationController::class, 'getCountries']);
    Route::get('/countries/{countryId}/provinces', [LocationController::class, 'getProvinces']);
    Route::get('/provinces/{provinceId}/regions', [LocationController::class, 'getRegions']);
    Route::get('/regions/{regionId}/villes', [LocationController::class, 'getVilles']);
    Route::get('/search', [LocationController::class, 'search']);
});
// Routes pour les sliders
Route::prefix('sliders')->group(function () {
    Route::get('/', [SliderController::class, 'index'])->name('sliders.index');
    Route::post('/', [SliderController::class, 'store'])->name('sliders.store');
    Route::get('/{id}', [SliderController::class, 'show'])->name('sliders.show');
    Route::put('/{id}', [SliderController::class, 'update'])->name('sliders.update');
    Route::delete('/{id}', [SliderController::class, 'destroy'])->name('sliders.destroy');
    
    // Routes supplémentaires
    Route::post('/{id}/toggle-status', [SliderController::class, 'toggleStatus'])->name('sliders.toggle-status');
    Route::post('/update-order', [SliderController::class, 'updateOrder'])->name('sliders.update-order');
    Route::get('/statistics/data', [SliderController::class, 'statistics'])->name('sliders.statistics');
    Route::get('/{id}/preview', [SliderController::class, 'preview'])->name('sliders.preview');
});

    Route::prefix('menus')->name('menus.')->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('index');
        Route::get('/statistics', [MenuController::class, 'statistics'])->name('statistics');
        Route::get('/categories', [MenuController::class, 'getCategories'])->name('categories');
        Route::get('/activities', [MenuController::class, 'getActivities'])->name('activities');
        Route::get('/parents', [MenuController::class, 'getParentMenus'])->name('parents');
        Route::get('/subparents', [MenuController::class, 'getSubParentMenus'])->name('subparents');
        Route::get('/all-parents', [MenuController::class, 'getAllParentMenus'])->name('all-parents');
        
        Route::post('/', [MenuController::class, 'store'])->name('store');
        Route::get('/{menu}/edit', [MenuController::class, 'edit'])->name('edit');
        Route::put('/{menu}', [MenuController::class, 'update'])->name('update');
        Route::delete('/{menu}', [MenuController::class, 'destroy'])->name('destroy');
        
        Route::post('/{menu}/move/{direction}', [MenuController::class, 'move'])->name('move');
        
        // Routes pour la gestion des pages
        Route::get('/{menu}/page', [MenuPageController::class, 'edit'])->name('page.edit');
        Route::post('/{menu}/page', [MenuPageController::class, 'update'])->name('page.update');
        Route::post('/{menu}/page/publish', [MenuPageController::class, 'publish'])->name('page.publish');
        Route::post('/{menu}/page/unpublish', [MenuPageController::class, 'unpublish'])->name('page.unpublish');
        Route::get('/{menu}/page/preview', [MenuPageController::class, 'preview'])->name('page.preview');
        
        // Routes pour les révisions
        Route::get('/{menu}/revisions', [MenuPageController::class, 'revisions'])->name('page.revisions');
        Route::post('/{menu}/revisions/{revision}/restore', [MenuPageController::class, 'restoreRevision'])->name('page.restore-revision');
        
        // Route pour activer/désactiver les pages
        Route::post('/{menu}/toggle-page', [MenuPageController::class, 'togglePage'])->name('page.toggle');

        // routes/web.php - dans le groupe admin/menus
        Route::post('/{menu}/page/update-settings', [MenuPageController::class, 'updateSettings'])->name('page.update-settings');
        Route::post('/{menu}/page/update-seo', [MenuPageController::class, 'updateSeo'])->name('page.update-seo');
        Route::get('/{menu}/revisions/{revision}/preview', [MenuPageController::class, 'previewRevision'])->name('page.revision.preview');

        Route::get('/api/blocks/data', [BlockController::class, 'getBlocksData'])->name('page.blocks.data');
        Route::get('/api/template/{menu}', [BlockController::class, 'apiShow'])->name('page.template.blocks');

        Route::post('/templates/save', [TemplateController::class, 'store'])->name('menus.templates.save');

    });



// Plans routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::resource('plans', PlanController::class);
    Route::post('/plans/{id}/toggle-status', [PlanController::class, 'toggleStatus'])->name('plans.toggle-status');
    Route::post('/plans/reorder', [PlanController::class, 'reorder'])->name('plans.reorder');


// Plan media management
Route::delete('/plans/{planId}/media/{mediaId}', [PlanController::class, 'deleteMedia'])->name('plans.media.delete');
Route::post('/plans/{planId}/media/{mediaId}/primary', [PlanController::class, 'setPrimaryMedia'])->name('plans.media.primary');

// Plan destinations management
Route::prefix('plans/{planId}/destinations')->group(function () {
    Route::get('/', [PlanController::class, 'getDestinations'])->name('plans.destinations.index');
    Route::post('/', [PlanController::class, 'storeDestination'])->name('plans.destinations.store');
    Route::put('/{destinationId}', [PlanController::class, 'updateDestination'])->name('plans.destinations.update');
    Route::delete('/{destinationId}', [PlanController::class, 'deleteDestination'])->name('plans.destinations.delete');
    Route::post('/reorder', [PlanController::class, 'reorderDestinations'])->name('plans.destinations.reorder');
});
 
    // Plan media management (AJAX endpoints called from edit page)
    Route::delete('/plans/{planId}/media/{mediaId}',   [PlanController::class, 'deleteMedia'])->name('plans.media.delete');
    Route::post('/plans/{planId}/media/{mediaId}/primary', [PlanController::class, 'setPrimaryMedia'])->name('plans.media.primary');
    
    // Abonnements routes - Ordre important ! Les routes spécifiques doivent venir avant resource
    Route::get('/abonnements/export', [AbonnementController::class, 'export'])->name('abonnements.export');
    Route::get('/abonnements/export-etablissements', [AbonnementController::class, 'exportEtablissements'])->name('abonnements.export-etablissements');
    Route::get('/abonnements/etablissements', [AbonnementController::class, 'etablissements'])->name('abonnements.etablissements');
    Route::get('/abonnements/historique/{id}', [AbonnementController::class, 'historique'])->name('abonnements.historique');
    Route::post('/abonnements/{id}/cancel', [AbonnementController::class, 'cancel'])->name('abonnements.cancel');
    Route::post('/abonnements/{id}/renew', [AbonnementController::class, 'renew'])->name('abonnements.renew');
    Route::get('/abonnements/{id}/print', [AbonnementController::class, 'print'])->name('abonnements.print');
    
    // Resource doit être après les routes spécifiques
    Route::resource('abonnements', AbonnementController::class);

    
});


// Route publique pour afficher les pages
Route::get('/pages/{slug}', [PublicPageController::class, 'show'])->name('pages.show');
Route::get('/menu/{menu}/page', [PublicPageController::class, 'showByMenu'])->name('menu.page.show');

Route::get('/menus/template/edit/{id}', function($id) {
    $template = \App\Models\Menu::findOrFail($id);
    
    
    
    return view('administration::menus.page-editor', [
        'template' => $template,
        'template_id' => $id
    ]);
})->name('template.edit');

});

Route::get('/menus/template/view/{id}', function ($id) {
    $menu = \App\Models\Menu::findOrFail($id);
    return view('administration::pages.view', [
        'menu' => $menu,
        'content' => $menu->page_content,
        'styles' => $menu->page_styles,
        'meta' => $menu->page_meta,
    ]);
})->name('menus.template.view');

