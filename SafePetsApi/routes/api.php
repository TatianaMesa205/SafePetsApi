<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MascotasController;
use App\Http\Controllers\VacunasController;
use App\Http\Controllers\AdoptantesController;
use App\Http\Controllers\CitasController;
use App\Http\Controllers\PublicacionesController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('registrar', [AuthController::class, 'registrar']);
Route::post('login', [AuthController::class, 'login']);



// 🔹 Middleware principal de Sanctum
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);


    // 🔹 Solo admin
    Route::group(['middleware' => RoleMiddleware::class . ':1'], function () {

        Route::post('crearMascota', [MascotasController::class, 'store']);

    });

    // 🔹 Admin o adoptante
    Route::middleware([RoleMiddleware::class . ':1,2'])->group(function () {
        
        Route::get('listarMascotas', [MascotasController::class, 'index']);
        Route::get('mascota/{id}', [MascotasController::class, 'show']);

        Route::get('listarVacunas/{id_mascotas}', [VacunasController::class, 'index']);

        Route::put('editarPerfil', [AuthController::class, 'editarPerfil']);

        Route::get('verificarAdoptante/{email}', [AdoptantesController::class, 'verificar']);
        Route::post('registrarAdoptante', [AdoptantesController::class, 'store']);
        Route::get('obtenerAdoptante/{email}', [AdoptantesController::class, 'obtenerAdoptante']);

        Route::post('solicitarCita', [CitasController::class, 'store']);
        Route::get('validarCitaActiva/{email}', [CitasController::class, 'validarCitaActiva']);

        Route::get('listarPublicaciones', [PublicacionesController::class, 'index']);
        Route::post('crearPublicacion', [PublicacionesController::class, 'store']);
        
    });
});









