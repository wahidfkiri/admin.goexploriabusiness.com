<?php

use Illuminate\Support\Facades\Route;
use Vendor\Editor\Controllers\EditorController;
use Vendor\Editor\Controllers\TemplateController;
use Vendor\Editor\Controllers\BlockController;
use Illuminate\Support\Facades\Auth;


Auth::routes();
    Route::get('templates/display/{templateId}', [TemplateController::class, 'previewTemplate'])->name('templates.display');
    
Route::middleware(['auth','web'])->group(function () {
    Route::post('templates/save', [TemplateController::class, 'store'])->name('templates.save');
    Route::get('/editor', [EditorController::class, 'index'])->name('editor');
    Route::get('templates/list', [TemplateController::class, 'list'])->name('templates');
    Route::delete('templates/delete/{id}', [TemplateController::class, 'destroy'])->name('templates.delete');
    Route::get('/template/edit/{id}', function($id) {
    $template = \App\Models\Template::findOrFail($id);
    
    
    
    return view('editor::index', [
        'template' => $template,
        'template_id' => $id
    ]);
})->name('template.edit');

Route::prefix('api/blocks')->group(function () {
    // Récupérer tous les blocs (pour l'éditeur)
    Route::get('/data', [BlockController::class, 'getBlocks']);
    
    // Ajouter un bloc dans l'éditeur
    Route::post('/add-to-editor', [BlockController::class, 'addToEditor']);
    
    // Sauvegarder comme nouveau bloc
    Route::post('/save', [BlockController::class, 'storeFromEditor']);
    Route::post('/save-as-block', [BlockController::class, 'saveAsBlock']);
    
    // Catégories et types
    Route::get('/categories', [BlockController::class, 'getCategories']);
    Route::get('/website-types', [BlockController::class, 'getWebsiteTypes']);
    
    // Statistiques
    Route::get('/stats', [BlockController::class, 'getStats']);
    
    // Recherche
    Route::get('/search', [BlockController::class, 'search']);
    
    // Code d'un bloc spécifique
    Route::get('/{id}/code', [BlockController::class, 'getBlockCode']);
    
    // CRUD complet
    Route::apiResource('/', BlockController::class)->except(['create', 'edit']);
    
    // Import
    Route::post('/import', [BlockController::class, 'import']);
});
});