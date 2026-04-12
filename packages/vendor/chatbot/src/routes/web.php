<?php

use Vendor\Chatbot\Controllers\InternalChatController;
use Vendor\Chatbot\Controllers\InternalPollController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Chat Interne — Vues
|--------------------------------------------------------------------------
| Toutes les vues sont protégées par auth.
| L'URL /chat est accessible depuis la navbar principale.
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','web'])->prefix('/admin/chat')->name('internal.chat.')->group(function () {

    // Page principale (liste des conversations)
    Route::get('/', [InternalChatController::class, 'index'])->name('index');

    // Vue d'une conversation spécifique
    Route::get('/room/{roomId}', [InternalChatController::class, 'room'])
         ->name('room')
         ->whereNumber('roomId');

    // Nouvelle conversation
    Route::get('/new', [InternalChatController::class, 'new'])->name('new');
});


Route::middleware(['auth', 'web'])->prefix('/internal-chat')->name('api.')->group(function () {

    // ── Rooms ──
    Route::get('/rooms',                             [InternalChatController::class, 'roomsList'])->name('rooms');
    Route::post('/direct',                           [InternalChatController::class, 'startDirect'])->name('direct');
    Route::post('/group',                            [InternalChatController::class, 'startGroup'])->name('group');
    Route::put('/rooms/{roomId}/group',              [InternalChatController::class, 'updateGroup'])->name('group.update')->whereNumber('roomId');
    Route::delete('/rooms/{roomId}/leave',           [InternalChatController::class, 'leaveGroup'])->name('leave')->whereNumber('roomId');

    // ── Messages ──
    Route::post('/rooms/{roomId}/messages',          [InternalChatController::class, 'sendMessage'])->name('internal.chat.send')->whereNumber('roomId');
    Route::post('/rooms/{roomId}/files',             [InternalChatController::class, 'sendFile'])->name('internal.chat.file')->whereNumber('roomId');
    Route::delete('/rooms/{roomId}/messages/{msgId}',[InternalChatController::class, 'deleteMessage'])->name('internal.chat.message.delete')->whereNumber(['roomId', 'msgId']);
    Route::get('/rooms/{roomId}/messages',           [InternalChatController::class, 'loadMessages'])->name('internal.chat.messages.load')->whereNumber('roomId');

    // ── Lecture & présence ──
    Route::post('/rooms/{roomId}/read',              [InternalChatController::class, 'markRead'])->name('internal.chat.read')->whereNumber('roomId');
    Route::post('/heartbeat',                        [InternalChatController::class, 'heartbeat'])->name('internal.chat.heartbeat');

    // ── Long-Polling & Typing ──
    Route::get('/rooms/{roomId}/poll',               [InternalPollController::class, 'poll'])->name('internal.chat.poll')->whereNumber('roomId');
    Route::post('/rooms/{roomId}/typing',            [InternalPollController::class, 'typing'])->name('internal.chat.typing')->whereNumber('roomId');

    // ── Recherche utilisateurs ──
    Route::get('/users/search',                      [InternalChatController::class, 'searchUsers'])->name('internal.chat.users.search');
});