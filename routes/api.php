<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    OpenAIController,
    TemplateController,
    ProjectController
};
use App\Http\Controllers\Api\ChatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('chat')->group(function () {
    Route::post('/send', [ChatController::class, 'chat']);
    Route::post('/with-context', [ChatController::class, 'chatWithContext']);
    Route::get('/history', [ChatController::class, 'getHistory']);
    Route::delete('/clear', [ChatController::class, 'clearHistory']);
});
Route::prefix('v1')->group(function () {
    
    // Public routes
    Route::get('/status', function () {
        return response()->json([
            'status' => 'online',
            'version' => '1.0.0',
            'timestamp' => now()
        ]);
    });
    
    // Authentication
    Route::post('/auth/login', [\App\Http\Controllers\AuthController::class, 'apiLogin']);
    Route::post('/auth/register', [\App\Http\Controllers\AuthController::class, 'apiRegister']);
    Route::post('/auth/logout', [\App\Http\Controllers\AuthController::class, 'apiLogout'])->middleware('auth:sanctum');
    
    // Protected routes
    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        
        // User info
        Route::get('/user', function () {
            return response()->json(auth()->user());
        });
        
        // Templates API
        Route::apiResource('templates', TemplateController::class);
        Route::get('/templates/search/{query}', [TemplateController::class, 'search']);
        Route::post('/templates/{id}/clone', [TemplateController::class, 'clone']);
        
        // Projects API
        Route::apiResource('projects', ProjectController::class);
        Route::post('/projects/{id}/publish', [ProjectController::class, 'publish']);
        
        // OpenAI API
        Route::prefix('ai')->group(function () {
            Route::post('/generate', [OpenAIController::class, 'generate']);
            Route::post('/optimize', [OpenAIController::class, 'optimize']);
            Route::post('/chat', [OpenAIController::class, 'chat']);
            Route::post('/code', [OpenAIController::class, 'code']);
            Route::post('/variations', [OpenAIController::class, 'variations']);
            Route::post('/improve', [OpenAIController::class, 'improve']);
            Route::get('/models', [OpenAIController::class, 'models']);
            Route::get('/usage/stats', [OpenAIController::class, 'usageStats']);
        });
        
        // Export API
        Route::post('/export/html', function () {
            return response()->json([
                'status' => 'success',
                'message' => 'HTML export endpoint'
            ]);
        });
        
        Route::post('/export/zip', function () {
            return response()->json([
                'status' => 'success',
                'message' => 'ZIP export endpoint'
            ]);
        });
    });
    
    // Demo routes (public but limited)
    Route::prefix('demo')->middleware('throttle:20,1440')->group(function () {
        Route::post('/ai/generate', [OpenAIController::class, 'demoGenerate']);
        Route::get('/templates', [TemplateController::class, 'demoTemplates']);
        Route::get('/templates/{id}', [TemplateController::class, 'demoShow']);
    });
});