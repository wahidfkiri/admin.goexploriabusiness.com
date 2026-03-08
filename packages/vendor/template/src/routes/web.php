<?php 

use Illuminate\Support\Facades\Route;

Route::get('/template/preview/{templateId}', function ($templateId) {
    $template = \App\Models\Template::findOrFail($templateId);
    return view('template::index', compact('template'));
});