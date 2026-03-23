<?php

use Illuminate\Support\Facades\Route;
use Vendor\Cms\Controllers\ThemeController;
use Vendor\Cms\Controllers\PageController;
use Vendor\Cms\Controllers\SettingController;

Route::prefix('etablissement/{etablissement}')->middleware(['auth:sanctum'])->group(function () {
    
    // Themes routes
    Route::apiResource('themes', ThemeController::class);
    Route::post('themes/{theme}/activate', [ThemeController::class, 'activate']);
    Route::post('themes/upload', [ThemeController::class, 'upload']);
    
    // Pages routes
    Route::apiResource('pages', PageController::class);
    Route::post('pages/{page}/publish', [PageController::class, 'publish']);
    Route::post('pages/{page}/unpublish', [PageController::class, 'unpublish']);
    Route::post('pages/{page}/duplicate', [PageController::class, 'duplicate']);
    
    // Settings routes
    Route::get('settings', [SettingController::class, 'index']);
    Route::post('settings', [SettingController::class, 'store']);
    Route::get('settings/{group}', [SettingController::class, 'group']);
    Route::delete('settings/{key}', [SettingController::class, 'destroy']);
});