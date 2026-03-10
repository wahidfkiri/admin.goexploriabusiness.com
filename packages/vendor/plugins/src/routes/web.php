<?php 

use Illuminate\Support\Facades\Route;


Auth::routes();

Route::middleware(['web','auth'])->group(function () {
    // Define your plugin routes here
    Route::get('/plugins', function () {
        return view('plugins::index');
    })->name('plugins.index');
});