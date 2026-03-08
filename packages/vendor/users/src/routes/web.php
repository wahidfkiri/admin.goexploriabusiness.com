<?php

use Illuminate\Support\Facades\Route;
use Vendor\Users\Controllers\UserController;
use Vendor\Users\Controllers\RoleController;
use Vendor\Users\Controllers\PermissionController;


Auth::routes();

Route::middleware(['auth','web'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
Route::post('/users/{user}/update-roles', [UserController::class, 'updateRoles'])->name('users.update-roles');
Route::post('/users/bulk-update', [UserController::class, 'bulkUpdate'])->name('users.bulk-update');
Route::get('/users/statistics', [UserController::class, 'statistics'])->name('users.statistics');
Route::get('/api/roles', [UserController::class, 'getRoles'])->name('api.roles');


    
    // Routes pour les rôles
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
        Route::get('/permissions', [RoleController::class, 'getPermissions'])->name('permissions');
        Route::get('/{role}', [RoleController::class, 'show'])->name('show');
        Route::post('/{role}/duplicate', [RoleController::class, 'duplicate'])->name('duplicate');
        Route::get('/statistics', [RoleController::class, 'statistics'])->name('statistics');
        Route::get('/export', [RoleController::class, 'export'])->name('export');
    });

    // Routes pour les permissions
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy');
        Route::delete('/bulk', [PermissionController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::get('/groups', [PermissionController::class, 'getGroups'])->name('groups');
        Route::get('/by-group', [PermissionController::class, 'getByGroup'])->name('by-group');
        Route::get('/statistics', [PermissionController::class, 'statistics'])->name('statistics');
        Route::get('/export', [PermissionController::class, 'export'])->name('export');
        Route::post('/sync-roles', [PermissionController::class, 'syncForRoles'])->name('sync-roles');
    });
});
