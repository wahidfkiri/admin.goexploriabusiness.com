<?php

use Illuminate\Support\Facades\Route;
use Vendor\Chatbot\Controllers\Web\ChatWidgetController;
use Vendor\Chatbot\Controllers\Web\ChatAdminController;

/*
|--------------------------------------------------------------------------
| Routes publiques — widget embarquable
|--------------------------------------------------------------------------
*/
Route::middleware(['web'])->group(function () {

    // Assets du widget (JS + CSS dynamiques avec config par établissement)
    Route::get('/chatbot/{etablissementId}/widget.js', [ChatWidgetController::class, 'widgetJs'])
        ->name('chatbot.widget.js');

    Route::get('/chatbot/{etablissementId}/widget.css', [ChatWidgetController::class, 'widgetCss'])
        ->name('chatbot.widget.css');

    Route::get('/chatbot/{etablissementId}/iframe', [ChatWidgetController::class, 'iframe'])
        ->name('chatbot.widget.iframe');

    /*
    |--------------------------------------------------------------------------
    | Routes admin — protégées par auth
    |--------------------------------------------------------------------------
    */
    Route::prefix('/company/{etablissementId}/chatbot')
        ->name('chatbot.admin.')
        ->middleware(['auth'])
        ->group(function () {

            // Dashboard agent
            Route::get('/', [ChatAdminController::class, 'dashboard'])->name('dashboard');

            // Détail d'une room (interface de réponse agent)
            Route::get('/rooms/{roomId}', [ChatAdminController::class, 'roomDetail'])->name('room');

            // Historique conversations fermées
            Route::get('/history', [ChatAdminController::class, 'history'])->name('history');

            // Configuration (quick replies, bot flows, widget)
            Route::get('/settings', [ChatAdminController::class, 'settings'])->name('settings');

            // Rapports & stats
            Route::get('/reports', [ChatAdminController::class, 'reports'])->name('reports');

            // CRUD Bot Flows
            Route::post('/flows', [ChatAdminController::class, 'storeFlow'])->name('flows.store');
            Route::put('/flows/{flowId}', [ChatAdminController::class, 'updateFlow'])->name('flows.update');
            Route::delete('/flows/{flowId}', [ChatAdminController::class, 'destroyFlow'])->name('flows.destroy');
        });
});