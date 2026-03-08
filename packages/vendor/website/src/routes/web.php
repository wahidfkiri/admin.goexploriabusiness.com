<?php
use Illuminate\Support\Facades\Route;
use Vendor\Website\Controllers\WebsiteController;



Auth::routes();
Route::middleware(['auth','web'])->group(function () {
Route::resource('websites', WebsiteController::class);

// Routes additionnelles
Route::prefix('websites')->name('websites.')->group(function () {
    Route::post('/bulk-delete', [WebsiteController::class, 'bulkDelete'])->name('bulk.delete');
    Route::post('/bulk-activate', [WebsiteController::class, 'bulkActivate'])->name('bulk.activate');
    Route::post('/bulk-deactivate', [WebsiteController::class, 'bulkDeactivate'])->name('bulk.deactivate');
    Route::post('/{website}/status', [WebsiteController::class, 'changeStatus'])->name('status.change');
    Route::get('/statistics', [WebsiteController::class, 'statistics'])->name('statistics');
    Route::get('/user/{user}', [WebsiteController::class, 'byUser'])->name('by.user');
});
});