<?php

use Illuminate\Support\Facades\Route;

Route::prefix('theme')->group(function () {

   Route::get('/{slug}/preview', function ($slug) {
        $menu = \App\Models\Menu::where('slug', $slug)->firstOrFail();
        return view('theme::preview', compact('menu'));
    })->name('theme.preview');




    Route::prefix('ecommerce')->group(function () {
        Route::get('page-1', function () {
            return view('theme::ecommerce.index-1');
        });
        Route::get('page-2', function () {
            return view('theme::ecommerce.index-2');
        });
        Route::get('page-3', function () {
            return view('theme::ecommerce.index-3');
        });
    });
    Route::prefix('travel')->group(function () {
        Route::get('page-1', function () {
            return view('theme::travel.index-1');
        });
        Route::get('page-2', function () {
            return view('theme::travel.index-2');
        });
        Route::get('page-3', function () {
            return view('theme::travel.index-3');
        });
        Route::get('page-4', function () {
            return view('theme::travel.index-4');
        });
    });
    Route::prefix('media')->group(function () {
        Route::get('page-1', function () {
            return view('theme::media.index-1');
        });
        Route::get('page-2', function () {
            return view('theme::media.index-2');
        });
        Route::get('page-3', function () {
            return view('theme::media.index-3');
        });
    });
    Route::prefix('seo')->group(function () {
        Route::get('page-1', function () {
            return view('theme::seo.index-1');
        });
        Route::get('page-2', function () {
            return view('theme::seo.index-2');
        });
    });
    Route::prefix('business')->group(function () {
        Route::get('page-1', function () {
            return view('theme::business.index-1');
        });
    });
    Route::prefix('marketing')->group(function () {
        Route::get('page-1', function () {
            return view('theme::marketing.index-1');
        });
    });
    Route::prefix('web')->group(function () {
        Route::get('page-1', function () {
            return view('theme::web.index-1');
        });
    });
    Route::prefix('meteo')->group(function () {
        Route::get('page-1', function () {
            return view('theme::meteo.index-1');
        });
    });
});
