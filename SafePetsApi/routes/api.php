<?php

use App\Http\Controllers\AdoptantesController;
use App\Http\Controllers\CitasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MascotasController;
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
        Route::put('actualizarMascota/{id}', [MascotasController::class, 'update']);
        Route::delete('eliminarMascota/{id}', [MascotasController::class, 'destroy']);


    });

    // 🔹 Admin o adoptante
    Route::middleware([RoleMiddleware::class . ':1,2'])->group(function () {
        

        Route::get('listarCitas', [CitasController::class, 'index']);
        Route::post('crearCita', [CitasController::class, 'store']);
        Route::get('cita/{id}', [CitasController::class, 'show']);
        Route::put('actualizarCita/{id}', [CitasController::class, 'update']);
        Route::delete('eliminarCita/{id}', [CitasController::class, 'destroy']);

        Route::get('listarAdoptantes', [AdoptantesController::class, 'index']);
        Route::post('crearAdoptante', [AdoptantesController::class, 'store']);
        Route::get('adoptante/{id}', [AdoptantesController::class, 'show']);
        Route::put('actualizarAdoptante/{id}', [AdoptantesController::class, 'update']);
        Route::delete('eliminarAdoptante/{id}', [AdoptantesController::class, 'destroy']);

        Route::get('listarMascotas', [MascotasController::class, 'index']);
        Route::get('mascota/{id}', [MascotasController::class, 'show']);

        Route::put('editarPerfil', [AuthController::class, 'editarPerfil']);
        

    });
});









