<?php 
use Illuminate\Support\Facades\Route;
use Vendor\Activitie\Controllers\CategoryController;
use Vendor\Activitie\Controllers\ActivityController;

Auth::routes();
Route::middleware(['auth','web'])->group(function () {
// Routes pour les catégories
Route::resource('categories', CategoryController::class);

// Routes AJAX pour les catégories
Route::get('categories/statistics/data', [CategoryController::class, 'getStatistics'])
    ->name('categories.statistics');
Route::get('categories/search/autocomplete', [CategoryController::class, 'search'])
    ->name('categories.search');
Route::get('categories/dropdown/list', [CategoryController::class, 'getForDropdown'])
    ->name('categories.dropdown');
Route::post('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
    ->name('categories.toggle-status');
Route::get('categories/export/data', [CategoryController::class, 'export'])
    ->name('categories.export');
Route::post('categories/bulk-update', [CategoryController::class, 'bulkUpdate'])
    ->name('categories.bulk-update');


Route::resource('activities', ActivityController::class);
Route::get('activities/statistics', [ActivityController::class, 'getStatistics'])->name('activities.statistics');
Route::post('activities/{activity}/toggle-status', [ActivityController::class, 'toggleStatus'])->name('activities.toggle-status');
Route::post('activities/bulk-update', [ActivityController::class, 'bulkUpdate'])->name('activities.bulk-update');
Route::get('activities/search', [ActivityController::class, 'search'])->name('activities.search');
Route::get('activities/by-category/{categorie}', [ActivityController::class, 'getByCategory'])->name('activities.by-category');
Route::get('api/activities/check-slug', [ActivityController::class, 'checkSlug'])->name('activities.check-slug');
});