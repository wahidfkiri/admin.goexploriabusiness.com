<?php


use Illuminate\Support\Facades\Route;
use Vendor\MapMarker\Controllers\MapPointController;

Auth::routes();

Route::middleware(['web','auth'])->group(function () {
    
    Route::resource('map-points', MapPointController::class);

    // API endpoints for map
    Route::get('/api/map-points', [MapPointController::class, 'getMapPoints'])->name('api.map-points');
    Route::get('/api/map', [MapPointController::class, 'map'])->name('map-points.map');
    // Routes pour la gestion AJAX de la galerie
    Route::delete('/api/map-points/gallery/image/{id}', [MapPointController::class, 'deleteGalleryImage'])
    ->name('map-points.gallery.image.delete');
    Route::delete('/api/map-points/gallery/video/{id}', [MapPointController::class, 'deleteGalleryVideo'])
    ->name('map-points.gallery.video.delete');
    Route::post('/api/map-points/gallery/reorder', [MapPointController::class, 'reorderGalleryImages'])
    ->name('map-points.gallery.reorder');
});
