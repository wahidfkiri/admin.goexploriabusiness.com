<?php 

use Illuminate\Support\Facades\Route;
use Vendor\Plugins\Controllers\PluginController;


Auth::routes();

Route::middleware(['web','auth'])->group(function () {
  Route::prefix('modules')->name('modules.')->group(function () {
    // Main view
    Route::get('/', [PluginController::class, 'index'])->name('index');
    Route::get('/details/{id}', [PluginController::class, 'show'])->name('show');
    
    // AJAX Routes
    Route::get('/get-plugins', [PluginController::class, 'getPlugins'])->name('get-plugins');
    Route::get('/stats', [PluginController::class, 'getStats'])->name('stats');
    Route::get('/categories', [PluginController::class, 'getCategories'])->name('categories');
    
    // Plugin CRUD (manuel)
    Route::post('/store', [PluginController::class, 'store'])->name('store');
    Route::put('/update/{id}', [PluginController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [PluginController::class, 'destroy'])->name('destroy');
    
    // Plugin actions
    Route::post('/activate/{id}', [PluginController::class, 'activate'])->name('activate');
    Route::post('/deactivate/{id}', [PluginController::class, 'deactivate'])->name('deactivate');
    
    // Plugin settings
    Route::get('/settings/{id}', [PluginController::class, 'getSettings'])->name('settings.get');
    Route::put('/settings/{id}', [PluginController::class, 'updateSettings'])->name('settings.update');

    // Dans routes/web.php
    Route::get('/get-active-plugins', [PluginController::class, 'getActivePlugins'])->name('modules.get-active');
});
});




