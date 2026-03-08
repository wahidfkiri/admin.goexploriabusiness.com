<?php

use Illuminate\Support\Facades\Route;
use Vendor\Etablissement\Controllers\EtablissementController;


Auth::routes();

Route::middleware(['auth','web'])->group(function () {
Route::resource('etablissements', EtablissementController::class);
Route::get('etablissements/edit/{etablissement}', [EtablissementController::class, 'edit'])->name('etablissements.edit');
Route::get('etablissements/statistics/data', [EtablissementController::class, 'statistics'])
    ->name('etablissements.statistics');

Route::prefix('api')->group(function () {
    Route::post('/villes/search', [EtablissementController::class, 'search'])->name('api.villes.search');
    Route::get('/activities', [EtablissementController::class, 'getActivities'])->name('api.activities.index');
});
});
