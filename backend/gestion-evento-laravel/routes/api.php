<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFullController;
use App\Http\Middleware\CheckRole;

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas con Passport
Route::middleware(['auth:api'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Roles super admin y admin
    Route::middleware([CheckRole::class . ':ROLE_SUPER_ADMIN,ROLE_ADMIN'])->group(function () {
        Route::apiResource('roles', RoleController::class);
    });

    // Roles super admin y admin
    Route::middleware([CheckRole::class . ':ROLE_SUPER_ADMIN,ROLE_ADMIN'])->group(function () {
        Route::apiResource('usersFull', UserFullController::class);
    });

});