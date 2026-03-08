<?php

use Illuminate\Support\Facades\Route;
use Vendor\Setting\Controllers\PageController;


Auth::routes();

Route::middleware(['auth','web'])->group(function () {
     Route::prefix('settings')->name('settings.')->group(function () {
        Route::resource('/pages', PageController::class);
     });
});
