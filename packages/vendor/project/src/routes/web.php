<?php

use Illuminate\Support\Facades\Route;
use Vendor\Project\Controllers\ProjectController;
use Illuminate\Support\Facades\Auth;
use Vendor\Project\Controllers\TaskController;


Auth::routes();
Route::middleware(['auth','web'])->group(function () {

// Projects routes
// Dans routes/web.php

// Routes supplémentaires pour les projets
Route::prefix('projects')->name('projects.')->group(function() {
    Route::get('statistics/data', [ProjectController::class, 'statistics'])->name('statistics');
    Route::post('bulk-delete', [ProjectController::class, 'bulkDestroy'])->name('bulk-destroy');
    Route::get('export', [ProjectController::class, 'export'])->name('export');
    Route::get('summary', [ProjectController::class, 'summary'])->name('summary');
    Route::get('/calendar', [ProjectController::class, 'calendar'])->name('calendar');
    // Routes pour un projet spécifique
    Route::prefix('{project}')->group(function() {
        Route::get('tasks', [ProjectController::class, 'tasks'])->name('tasks');
        Route::get('timeline', [ProjectController::class, 'timeline'])->name('timeline');
        Route::get('gantt', [ProjectController::class, 'gantt'])->name('gantt');
        Route::post('duplicate', [ProjectController::class, 'duplicate'])->name('duplicate');
        Route::patch('status', [ProjectController::class, 'updateStatus'])->name('update-status');
    });
});

Route::resource('projects', ProjectController::class);

// ==================== ROUTES TÂCHES ====================
    Route::prefix('tasks')->name('tasks.')->group(function() {
        // Liste des tâches (avec filtres)
        Route::get('/', [TaskController::class, 'index'])->name('index');
        
        // Création d'une tâche
        Route::get('/create', [TaskController::class, 'create'])->name('create');
        Route::post('/', [TaskController::class, 'store'])->name('store');
        
        // Routes pour les statistiques et exports
        Route::get('/statistics/data', [TaskController::class, 'statistics'])->name('statistics');
        Route::get('/export', [TaskController::class, 'export'])->name('export');
        Route::post('/bulk-delete', [TaskController::class, 'bulkDestroy'])->name('bulk-destroy');
        
        // Routes pour une tâche spécifique
        Route::prefix('{task}')->group(function() {
            // Affichage et modification
            Route::get('/', [TaskController::class, 'show'])->name('show');
            Route::get('/edit', [TaskController::class, 'edit'])->name('edit');
            Route::put('/', [TaskController::class, 'update'])->name('update');
            Route::delete('/', [TaskController::class, 'destroy'])->name('destroy');
            
            // Actions spécifiques
            Route::patch('/toggle-status', [TaskController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/duplicate', [TaskController::class, 'duplicate'])->name('duplicate');
            Route::post('/assign', [TaskController::class, 'assign'])->name('assign');
            Route::get('/comments', [TaskController::class, 'comments'])->name('comments');
            Route::post('/comments', [TaskController::class, 'addComment'])->name('add-comment');
            
            // Routes pour les dates techniques
            Route::patch('/test-date', [TaskController::class, 'updateTestDate'])->name('update-test-date');
            Route::patch('/integration-date', [TaskController::class, 'updateIntegrationDate'])->name('update-integration-date');
            Route::patch('/push-prod-date', [TaskController::class, 'updatePushProdDate'])->name('update-push-prod-date');

    // Routes pour les fichiers
    Route::prefix('files')->name('files.')->group(function () {
        Route::get('/', [TaskController::class, 'getFiles'])->name('index');
        Route::post('/upload', [TaskController::class, 'uploadFile'])->name('upload');
        Route::post('/upload-multiple', [TaskController::class, 'uploadMultipleFiles'])->name('upload-multiple');
        Route::get('/{file}/download', [TaskController::class, 'downloadFile'])->name('download');
        Route::get('/{file}/preview', [TaskController::class, 'previewFile'])->name('preview');
        Route::delete('/{file}', [TaskController::class, 'deleteFile'])->name('delete');
        Route::patch('/{file}/description', [TaskController::class, 'updateFileDescription'])->name('description');
        Route::patch('/{file}/toggle-public', [TaskController::class, 'toggleFilePublic'])->name('toggle-public');
    });

        // Route pour nettoyer les fichiers expirés (à protéger par middleware admin)
        Route::post('/tasks/clean-expired-files', [TaskController::class, 'cleanExpiredFiles'])->name('tasks.files.clean-expired');
        });
    });
});