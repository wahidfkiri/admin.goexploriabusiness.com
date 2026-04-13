
<?php

use Vendor\Chatbot\Controllers\InternalChatController;
use Vendor\Chatbot\Controllers\InternalPollController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Chat Interne — API
|--------------------------------------------------------------------------
| Protégé par auth:sanctum (ou 'auth' si vous n'utilisez pas Sanctum).
| Préfixe: /api/internal-chat
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->prefix('/internal-chat')->name('api')->group(function () {

    // ── Rooms ──
    Route::get('/rooms',                             [InternalChatController::class, 'roomsList'])->name('rooms');
    Route::post('/direct',                           [InternalChatController::class, 'startDirect'])->name('direct');
    Route::post('/group',                            [InternalChatController::class, 'startGroup'])->name('group');
    Route::put('/rooms/{roomId}/group',              [InternalChatController::class, 'updateGroup'])->name('group.update')->whereNumber('roomId');
    Route::delete('/rooms/{roomId}/leave',           [InternalChatController::class, 'leaveGroup'])->name('leave')->whereNumber('roomId');
    Route::delete('/rooms/{roomId}',                 [InternalChatController::class, 'deleteRoom'])->name('room.delete')->whereNumber('roomId');

    // ── Messages ──
    Route::post('/rooms/{roomId}/messages',          [InternalChatController::class, 'sendMessage'])->name('send')->whereNumber('roomId');
    Route::post('/rooms/{roomId}/files',             [InternalChatController::class, 'sendFile'])->name('file')->whereNumber('roomId');
    Route::delete('/rooms/{roomId}/messages/{msgId}',[InternalChatController::class, 'deleteMessage'])->name('message.delete')->whereNumber(['roomId', 'msgId']);
    Route::get('/rooms/{roomId}/messages',           [InternalChatController::class, 'loadMessages'])->name('messages.load')->whereNumber('roomId');

    // ── Lecture & présence ──
    Route::post('/rooms/{roomId}/read',              [InternalChatController::class, 'markRead'])->name('read')->whereNumber('roomId');
    Route::post('/heartbeat',                        [InternalChatController::class, 'heartbeat'])->name('heartbeat');

    // ── Long-Polling & Typing ──
    Route::get('/rooms/{roomId}/poll',               [InternalPollController::class, 'poll'])->name('poll')->whereNumber('roomId');
    Route::post('/rooms/{roomId}/typing',            [InternalPollController::class, 'typing'])->name('typing')->whereNumber('roomId');

    // ── Recherche utilisateurs ──
    Route::get('/users/search',                      [InternalChatController::class, 'searchUsers'])->name('users.search');
});
