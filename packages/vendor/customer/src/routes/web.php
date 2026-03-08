<?php

use Vendor\Customer\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;



Auth::routes();
Route::middleware(['auth','web'])->group(function () {
// Routes pour Customer
Route::resource('customers', CustomerController::class);

// Routes additionnelles
Route::resource('customers', CustomerController::class);
Route::post('/customers/bulk-delete', [CustomerController::class, 'bulkDelete'])->name('customers.bulk.delete');
Route::prefix('customers')->name('customers.')->group(function () {
    Route::get('/export', [CustomerController::class, 'export'])->name('export');
    Route::post('/bulk-delete', [CustomerController::class, 'bulkDelete'])->name('bulk.delete');
    Route::get('/statistics', [CustomerController::class, 'statistics'])->name('statistics');
    Route::get('/search', [CustomerController::class, 'search'])->name('search');
});

// Pour AJAX (API-like)
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
});
});