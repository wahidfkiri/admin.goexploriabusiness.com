<?php


use Illuminate\Support\Facades\Route;
use Vendor\MapMarker\Controllers\MapPointController;

Auth::routes();

Route::middleware(['web','auth'])->group(function () {
    
    Route::resource('map-points', MapPointController::class);

    // API endpoints for map
    Route::get('/api/map-points', [MapPointController::class, 'getMapPoints'])->name('api.map-points');
    Route::get('/api/map', [MapPointController::class, 'map'])->name('map-points.map');
});
