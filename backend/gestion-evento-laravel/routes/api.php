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

        Route::apiResource('filiales', FilialController::class);
        Route::post('/filiales/{id}', [FilialController::class, 'update']);

        Route::apiResource('facultades', FacultadController::class);
        Route::post('/facultades/{id}', [FacultadController::class, 'update']);

        Route::apiResource('escuelas', EscuelaController::class);
        Route::post('/escuelas/{id}', [EscuelaController::class, 'update']);

        Route::get('/roles/paginated', [RoleController::class, 'paginated']);
        Route::get('/roles/search',    [RoleController::class, 'search']);
        Route::apiResource('roles', RoleController::class)->except(['create', 'edit']);
        Route::post('/roles/{id}', [RoleController::class, 'update']);

        Route::apiResource('usuarios', UserPersonaController::class);
        Route::post('/usuarios/{id}', [UserPersonaController::class, 'update']);
    });
});
