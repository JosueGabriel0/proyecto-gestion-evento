<?php

use App\Infrastructure\Http\Controllers\Administrador\AuthController;
use App\Infrastructure\Http\Controllers\Administrador\EscuelaController;
use App\Infrastructure\Http\Controllers\Administrador\FacultadController;
use App\Infrastructure\Http\Controllers\Administrador\FilialController;
use App\Infrastructure\Http\Controllers\Administrador\RoleController;
use App\Infrastructure\Http\Controllers\Administrador\UserPersonaController;
use App\Infrastructure\Http\Middleware\CheckRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 🔓 Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 🔐 Rutas protegidas con Passport
Route::middleware(['auth:api'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // 🧭 SOLO SUPER ADMIN
    Route::middleware([CheckRole::class . ':ROLE_SUPER_ADMIN'])->group(function () {
        Route::get('/filiales/paginated', [FilialController::class, 'paginated']);
        Route::get('/filiales/search', [FilialController::class, 'search']);
        Route::apiResource('filiales', FilialController::class);
        Route::post('/filiales/{id}', [FilialController::class, 'update']);
    });

    // 🧩 SUPER ADMIN + ADMIN
    Route::middleware([CheckRole::class . ':ROLE_SUPER_ADMIN,ROLE_ADMIN'])->group(function () {

        // FACULTADES
        Route::apiResource('facultades', FacultadController::class);
        Route::post('/facultades/{id}', [FacultadController::class, 'update']);

        // 🏫 ESCUELAS (corregido)
        Route::get('/escuelas/paginated', [EscuelaController::class, 'paginated']);
        Route::get('/escuelas/search', [EscuelaController::class, 'search']);
        Route::apiResource('escuelas', EscuelaController::class);
        Route::post('/escuelas/{id}', [EscuelaController::class, 'update']);

        // ROLES
        Route::get('/roles/paginated', [RoleController::class, 'paginated']);
        Route::get('/roles/search', [RoleController::class, 'search']);
        Route::apiResource('roles', RoleController::class)->except(['create', 'edit']);
        Route::post('/roles/{id}', [RoleController::class, 'update']);

        // USUARIOS
        Route::apiResource('usuarios', UserPersonaController::class);
        Route::post('/usuarios/{id}', [UserPersonaController::class, 'update']);
    });
});