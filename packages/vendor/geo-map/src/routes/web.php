<?php

use Illuminate\Support\Facades\Route;
use Vendor\GeoMap\Controllers\PlaceController;
use Vendor\GeoMap\Controllers\GeoMapController;
use Vendor\GeoMap\Controllers\GeoDataController;




// Page principale
Route::get('/map', function () {
    return view('geo-map::map');
});

// API Routes
Route::prefix('api')->group(function () {
    Route::get('/places', [PlaceController::class, 'index']);
    Route::get('/categories', [PlaceController::class, 'categories']);
    Route::get('/header-data/{countryCode}', [GeoDataController::class, 'getHeaderData']);
    Route::get('/provinces/{countryCode}', [GeoDataController::class, 'getProvinces']);
    Route::get('/regions/{provinceId}', [GeoDataController::class, 'getRegions']);
});

Route::get('continent/page/{continentId}', function ($continentId) {
    return view('geo-map::continents.page', compact('continentId'));
});
Route::get('/countrie/{countrieCode}', [GeoMapController::class, 'getCountrie'])
    ->name('geomap.countrie');
Route::get('/countrie/{countrieCode}/{provinceCode}', [GeoMapController::class, 'getProvince'])
    ->name('geomap.province');
Route::get('/country/{countrieCode}', [GeoMapController::class, 'getCountries'])
    ->name('geomap.countries');