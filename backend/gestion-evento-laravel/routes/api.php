<?php

use App\Infrastructure\Http\Controllers\Administrador\AuthController;
use App\Infrastructure\Http\Controllers\Administrador\EscuelaController;
use App\Infrastructure\Http\Controllers\Administrador\FacultadController;
use App\Infrastructure\Http\Controllers\Administrador\RoleController;
use App\Infrastructure\Http\Controllers\Administrador\UserController;
use App\Infrastructure\Http\Controllers\SuperAdministrador\FilialController;
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
    Route::middleware([CheckRole::class . ':ROLE_SUPER_ADMIN'])->group(function () {

        Route::get('/filiales/paginated', [FilialController::class, 'paginated']);
        Route::get('/filiales/search',    [FilialController::class, 'search']);
        Route::apiResource('filiales', FilialController::class);
        Route::post('/filiales/{id}', [FilialController::class, 'update']);
    });

    Route::middleware([CheckRole::class . ':ROLE_SUPER_ADMIN,ROLE_ADMIN'])->group(function () {
        Route::get('/filiales', [FilialController::class, 'index']);

        Route::get('/facultades/filial/{filialId}', [FacultadController::class, 'getByFilial']);
        Route::get('/facultades/paginated', [FacultadController::class, 'paginated']);
        Route::get('/facultades/search',    [FacultadController::class, 'search']);
        Route::apiResource('facultades', FacultadController::class);
        Route::post('/facultades/{id}', [FacultadController::class, 'update']);

        Route::get('/escuelas/facultad/{facultadId}', [EscuelaController::class, 'getByFacultad']);
        Route::get('/escuelas/paginated', [EscuelaController::class, 'paginated']);
        Route::get('/escuelas/search',    [EscuelaController::class, 'search']);
        Route::apiResource('escuelas', EscuelaController::class);
        Route::post('/escuelas/{id}', [EscuelaController::class, 'update']);

        Route::get('/roles/paginated', [RoleController::class, 'paginated']);
        Route::get('/roles/search',    [RoleController::class, 'search']);
        Route::apiResource('roles', RoleController::class)->except(['create', 'edit']);
        Route::post('/roles/{id}', [RoleController::class, 'update']);

        Route::get('/users/paginated', [UserController::class, 'index']);
        Route::get('/users/search',    [UserController::class, 'search']);
        Route::apiResource('users', UserController::class);
        Route::post('/users/{id}', [UserController::class, 'update']);

        Route::get('/ponencias/paginated', [FacultadController::class, 'paginated']);
        Route::get('/ponencias/search',    [FacultadController::class, 'search']);
        Route::apiResource('ponencias', FacultadController::class);
        Route::post('/ponencias/{id}', [FacultadController::class, 'update']);
    });
});
