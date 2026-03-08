<?php

use Vendor\Destination\Controllers\DestinationController;
use Vendor\Destination\Controllers\ContinentController;
use Vendor\Destination\Controllers\CountryController;
use Vendor\Destination\Controllers\ProvinceController;
use Vendor\Destination\Controllers\RegionController;
use Vendor\Destination\Controllers\VilleController;
use Vendor\Destination\Controllers\PageController;
use Vendor\Destination\Controllers\BlockController;
use Vendor\Destination\Controllers\EditeurController;
use Vendor\Destination\Controllers\MenuController;
use Vendor\Destination\Controllers\MenuPageController;
use Illuminate\Support\Facades\Route;


Auth::routes();

Route::middleware(['auth','web'])->group(function () {


    // Routes pour les activités
Route::prefix('/countries/activities')->group(function () {
    Route::get('/{countrieId}', [\Vendor\Destination\Controllers\Countries\ActivityController::class, 'index'])->name('countries.activities.index');
    Route::get('/create', [\Vendor\Destination\Controllers\Countries\ActivityController::class, 'create'])->name('countries.activities.create');
    Route::post('/store', [\Vendor\Destination\Controllers\Countries\ActivityController::class, 'store'])->name('countries.activities.store');
    Route::get('/{id}', [\Vendor\Destination\Controllers\Countries\ActivityController::class, 'show'])->name('countries.activities.show');
    Route::get('/{id}/edit', [\Vendor\Destination\Controllers\Countries\ActivityController::class, 'edit'])->name('countries.activities.edit');
    Route::put('/{id}', [\Vendor\Destination\Controllers\Countries\ActivityController::class, 'update'])->name('countries.activities.update');
    Route::delete('/{id}', [\Vendor\Destination\Controllers\Countries\ActivityController::class, 'destroy'])->name('countries.activities.destroy');
    Route::put('/{id}/toggle-status', [\Vendor\Destination\Controllers\Countries\ActivityController::class, 'toggleStatus'])->name('countries.activities.toggle-status');
    Route::get('/statistics/{countrieId}', [\Vendor\Destination\Controllers\Countries\ActivityController::class, 'statistics'])->name('countries.activities.statistics');
});


Route::prefix('countries/country-medias')->group(function () {
    Route::get('/', [\Vendor\Destination\Controllers\Countries\CountryMediaController::class, 'index']);
    Route::post('/', [\Vendor\Destination\Controllers\Countries\CountryMediaController::class, 'store']);
    Route::get('/statistics', [\Vendor\Destination\Controllers\Countries\CountryMediaController::class, 'statistics']);
    Route::get('/{id}', [\Vendor\Destination\Controllers\Countries\CountryMediaController::class, 'show']);
    Route::put('/{id}', [\Vendor\Destination\Controllers\Countries\CountryMediaController::class, 'update']);
    Route::delete('/{id}', [\Vendor\Destination\Controllers\Countries\CountryMediaController::class, 'destroy']);
    Route::post('/{id}/toggle-status', [\Vendor\Destination\Controllers\Countries\CountryMediaController::class, 'toggleStatus']);
    Route::post('/{id}/toggle-featured', [\Vendor\Destination\Controllers\Countries\CountryMediaController::class, 'toggleFeatured']);
});

Route::prefix('countries/places')->group(function () {
    Route::get('/', [\Vendor\Destination\Controllers\Countries\PlaceController::class, 'index']);
    Route::post('/', [\Vendor\Destination\Controllers\Countries\PlaceController::class, 'store']);
    Route::get('/statistics', [\Vendor\Destination\Controllers\Countries\PlaceController::class, 'statistics']);
    Route::get('/categories', [\Vendor\Destination\Controllers\Countries\PlaceController::class, 'categories']);
    Route::get('/map-data', [\Vendor\Destination\Controllers\Countries\PlaceController::class, 'mapData']);
    Route::get('/{id}', [\Vendor\Destination\Controllers\Countries\PlaceController::class, 'show']);
    Route::put('/{id}', [\Vendor\Destination\Controllers\Countries\PlaceController::class, 'update']);
    Route::delete('/{id}', [\Vendor\Destination\Controllers\Countries\PlaceController::class, 'destroy']);
    Route::post('/{id}/toggle-status', [\Vendor\Destination\Controllers\Countries\PlaceController::class, 'toggleStatus']);
    Route::post('/{id}/toggle-featured', [\Vendor\Destination\Controllers\Countries\PlaceController::class, 'toggleFeatured']);
});
// Routes pour les destinations
Route::prefix('destinations')->group(function () {
    Route::get('/', [DestinationController::class, 'index'])->name('destinations.index');
    Route::post('/', [DestinationController::class, 'store'])->name('destinations.store');
    Route::get('/{id}', [DestinationController::class, 'show'])->name('destinations.show');
    Route::put('/{id}', [DestinationController::class, 'update'])->name('destinations.update');
    Route::delete('/{id}', [DestinationController::class, 'destroy'])->name('destinations.destroy');
    
    // Routes supplémentaires
    Route::post('/{id}/restore', [DestinationController::class, 'restore'])->name('destinations.restore');
    Route::delete('/{id}/force-delete', [DestinationController::class, 'forceDelete'])->name('destinations.force-delete');
    Route::post('/{id}/toggle-status', [DestinationController::class, 'toggleStatus'])->name('destinations.toggle-status');
    Route::get('/statistics', [DestinationController::class, 'statistics'])->name('destinations.statistics');
    
});

Route::prefix('admin')->group(function () {
Route::resource('continents', ContinentController::class);
});
// Routes supplémentaires
Route::get('admin/continents/{continent}/countries', [ContinentController::class, 'getCountries'])
    ->name('continents.countries');
Route::get('/admin/continents/statistics/data', [ContinentController::class, 'getStatistics'])
    ->name('continents.statistics');
Route::put('/admin/continents/{continent}/toggle-status', [ContinentController::class, 'toggleStatus'])
    ->name('continents.toggle-status');
// Routes pour les localisations
Route::get('/secteurs', [DestinationController::class, 'getSecteurs'])->name('secteurs.index');



// Routes pour les pays
Route::resource('countries', CountryController::class);

// Routes AJAX pour les pays
Route::get('countries/statistics/data', [CountryController::class, 'getStatistics'])
    ->name('countries.statistics');
Route::get('countries/{country}/provinces', [CountryController::class, 'getProvinces'])
    ->name('countries.provinces');
Route::get('countries/by-continent/{continentCode}', [CountryController::class, 'getByContinent'])
    ->name('countries.by-continent');
Route::get('countries/search/autocomplete', [CountryController::class, 'search'])
    ->name('countries.search');
Route::put('/countries/{country}/toggle-status', [CountryController::class, 'toggleStatus'])
    ->name('countries.toggle-status');


    // Routes pour les provinces
Route::resource('provinces', ProvinceController::class);

// Routes AJAX pour les provinces
Route::get('provinces/statistics/data', [ProvinceController::class, 'getStatistics'])
    ->name('provinces.statistics');
Route::get('provinces/{province}/regions', [ProvinceController::class, 'getRegions'])
    ->name('provinces.regions');
Route::get('provinces/{province}/villes', [ProvinceController::class, 'getVilles'])
    ->name('provinces.villes');
Route::get('provinces/by-country/{countryCode}', [ProvinceController::class, 'getByCountry'])
    ->name('provinces.by-country');
Route::get('provinces/search/autocomplete', [ProvinceController::class, 'search'])
    ->name('provinces.search');
Route::get('provinces/page/{provinceId}', [ProvinceController::class, 'page'])
    ->name('provinces.pages');
Route::put('/provinces/{province}/toggle-status', [ProvinceController::class, 'toggleStatus'])
    ->name('provinces.toggle-status');


    // Routes pour les régions
Route::resource('regions', RegionController::class);

// Routes AJAX pour les régions
Route::get('regions/statistics/data', [RegionController::class, 'getStatistics'])
    ->name('regions.statistics');
Route::get('regions/{region}/cities', [RegionController::class, 'getCities'])
    ->name('regions.cities');
Route::get('regions/{region}/secteurs', [RegionController::class, 'getSecteurs'])
    ->name('regions.secteurs');
Route::get('regions/by-province/{provinceCode}', [RegionController::class, 'getByProvince'])
    ->name('regions.by-province');
Route::get('regions/search/autocomplete', [RegionController::class, 'search'])
    ->name('regions.search');
Route::get('regions/by-classification/{classification}', [RegionController::class, 'getByClassification'])
    ->name('regions.by-classification');
Route::get('regions/export/data', [RegionController::class, 'export'])
    ->name('regions.export');
Route::post('regions/{id}/restore', [RegionController::class, 'restore'])
    ->name('regions.restore');

// Routes pour les villes
Route::resource('villes', VilleController::class);

// Routes AJAX pour les villes
Route::get('villes/statistics/data', [VilleController::class, 'getStatistics'])
    ->name('villes.statistics');
Route::get('villes/by-country/{countryCode}', [VilleController::class, 'getByCountry'])
    ->name('villes.by-country');
Route::get('villes/by-province/{provinceCode}', [VilleController::class, 'getByProvince'])
    ->name('villes.by-province');
Route::get('villes/by-region/{regionCode}', [VilleController::class, 'getByRegion'])
    ->name('villes.by-region');
Route::get('villes/search/autocomplete', [VilleController::class, 'search'])
    ->name('villes.search');
Route::get('villes/by-status/{status}', [VilleController::class, 'getByStatus'])
    ->name('villes.by-status');
Route::get('villes/by-classification/{classification}', [VilleController::class, 'getByClassification'])
    ->name('villes.by-classification');
Route::get('villes/export/data', [VilleController::class, 'export'])
    ->name('villes.export');
Route::post('villes/{id}/restore', [VilleController::class, 'restore'])
    ->name('villes.restore');

// Routes pour les dépendances
Route::get('villes/country/{countryId}/provinces', [VilleController::class, 'getProvincesByCountry'])
    ->name('villes.provinces-by-country');
Route::get('villes/province/{provinceId}/regions', [VilleController::class, 'getRegionsByProvince'])
    ->name('villes.regions-by-province');
Route::get('villes/region/{regionId}/secteurs', [VilleController::class, 'getSecteursByRegion'])
    ->name('villes.secteurs-by-region');



Route::get('/api/destinations', [DestinationController::class, 'getActiveDestinations'])->name('destinations.active');
Route::get('/destination/{id}', [DestinationController::class, 'show'])->name('destination.show');




// Routes principales pour les pages
Route::prefix('pages')->name('pages.')->group(function () {
    // Liste des pages
    Route::get('/', [PageController::class, 'index'])->name('index');
    
    // Création
    Route::get('/create', [PageController::class, 'create'])->name('create');
    Route::post('/', [PageController::class, 'store'])->name('store');
    
    // Par slug
    Route::get('/{slug}', [PageController::class, 'show'])->name('show');
    
    // Édition
    Route::get('/{id}/edit', [PageController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PageController::class, 'update'])->name('update');
    
    // Suppression
    Route::delete('/{id}', [PageController::class, 'destroy'])->name('destroy');
    
    // Statistiques
    Route::get('/statistics', [PageController::class, 'statistics'])->name('statistics');
    
    // Recherche
    Route::get('/search', [PageController::class, 'search'])->name('search');
    
    // Par type
    Route::get('/type/{type?}', [PageController::class, 'pagesByType'])->name('byType');
    
    // Toggle status
    Route::post('/{id}/toggle-status', [PageController::class, 'toggleStatus'])->name('toggleStatus');
    
    // Générer slug
    Route::post('/generate-slug', [PageController::class, 'generateSlug'])->name('generateSlug');
    
    // Export
    Route::get('/export', [PageController::class, 'export'])->name('export');
    
    // Bulk actions
    Route::post('/bulk-actions', [PageController::class, 'bulkActions'])->name('bulkActions');
});

// Routes pour création de pages par destination spécifique
Route::prefix('create-page')->name('pages.create.')->group(function () {
    Route::get('/continent/{continent}', [PageController::class, 'createForDestination'])
        ->name('continent');
    
    Route::get('/country/{country}', [PageController::class, 'createForDestination'])
        ->name('country');
    
    Route::get('/region/{region}', [PageController::class, 'createForDestination'])
        ->name('region');
    
    Route::get('/province/{province}', [PageController::class, 'createForDestination'])
        ->name('province');
    
    Route::get('/city/{city}', [PageController::class, 'createForDestination'])
        ->name('city');
});

// Routes API pour les pages
Route::prefix('api/pages')->name('api.pages.')->group(function () {
    // Récupérer toutes les pages (JSON)
    Route::get('/', [PageController::class, 'index'])->name('index');
    Route::get('/data/{page}', [PageController::class, 'showPage']);
    // Statistiques
    Route::get('/statistics', [PageController::class, 'statistics'])->name('statistics');
    
    // Destinations par type
    Route::get('/destinations/{type}', [PageController::class, 'getDestinations'])->name('destinations');
    
    // Pages par destination
    Route::get('/{type}/{id}/pages', [PageController::class, 'getPagesByDestination'])->name('byDestination');
    
    // Pages pour dropdown
    Route::get('/{type}/{id}/dropdown', [PageController::class, 'getPagesForDropdown'])->name('dropdown');
    
    // Recherche API
    Route::get('/search', [PageController::class, 'apiSearch'])->name('search');
    
    // Preview
    Route::post('/preview', [PageController::class, 'preview'])->name('preview');
    
    // CRUD API
    Route::post('/', [PageController::class, 'store'])->name('store');
    Route::put('/{id}', [PageController::class, 'update'])->name('update');
    Route::delete('/{id}', [PageController::class, 'destroy'])->name('destroy');
    Route::post('/save', [EditeurController::class, 'store']);
});

// Routes spécifiques pour chaque type de destination
Route::prefix('api')->name('api.')->group(function () {
    // Continents
    Route::prefix('continents/{continent}')->group(function () {
        Route::get('/pages', [PageController::class, 'getPagesByDestination'])
            ->name('continent.pages');
    });
    
    // Pays
    Route::prefix('countries/{country}')->group(function () {
        Route::get('/pages', [PageController::class, 'getPagesByDestination'])
            ->name('country.pages');
    });
    
    // Régions
    Route::prefix('regions/{region}')->group(function () {
        Route::get('/pages', [PageController::class, 'getPagesByDestination'])
            ->name('region.pages');
    });
    
    // Provinces
    Route::prefix('provinces/{province}')->group(function () {
        Route::get('/pages', [PageController::class, 'getPagesByDestination'])
            ->name('province.pages');
    });
    
    // Villes
    Route::prefix('cities/{city}')->group(function () {
        Route::get('/pages', [PageController::class, 'getPagesByDestination'])
            ->name('city.pages');
    });
});

    Route::get('/pages/edit/{id}', function($id) {
    $template = \App\Models\Page::findOrFail($id);
    
    
    
    return view('destination::provinces.builder.index', [
        'template' => $template,
        'template_id' => $id
    ]);
})->name('template.edit');

Route::prefix('api/pages/blocks')->group(function () {
    // Récupérer tous les blocs (pour l'éditeur)
    Route::get('/data', [BlockController::class, 'getBlocks']);
    
    // Ajouter un bloc dans l'éditeur
    Route::post('/add-to-editor', [BlockController::class, 'addToEditor']);
    
    // Sauvegarder comme nouveau bloc
    Route::post('/save', [BlockController::class, 'storeFromEditor']);
    Route::post('/save-as-block', [BlockController::class, 'saveAsBlock']);
    
    // Catégories et types
    Route::get('/categories', [BlockController::class, 'getCategories']);
    Route::get('/website-types', [BlockController::class, 'getWebsiteTypes']);
    
    // Statistiques
    Route::get('/stats', [BlockController::class, 'getStats']);
    
    // Recherche
    Route::get('/search', [BlockController::class, 'search']);
    
    // Code d'un bloc spécifique
    Route::get('/{id}/code', [BlockController::class, 'getBlockCode']);
    
    // CRUD complet
    Route::apiResource('/', BlockController::class)->except(['create', 'edit']);
    
    // Import
    Route::post('/import', [BlockController::class, 'import']);
});



Route::prefix('admin/menus')->name('destinations.menus.')->group(function () {
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



});