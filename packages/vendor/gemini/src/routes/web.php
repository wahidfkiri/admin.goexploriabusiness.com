<?php

use Illuminate\Support\Facades\Route;
use Vendor\Gemini\Controllers\GeminiController;


Auth::routes();
Route::middleware(['auth','web'])->group(function () {
    Route::post('/gemini/generate', [GeminiController::class, 'generate'])->name('gemini.generate');
    Route::get('/test/gemini', function() {
        return 'Gemini package is working!';
    })->name('gemini.test');
});