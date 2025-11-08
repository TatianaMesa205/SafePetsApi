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
    Route::group(['middleware' => RoleMiddleware::class . ':admin'], function () {

        Route::post('crearMascota', [MascotasController::class, 'store']);
        Route::put('actualizarMascota/{id}', [MascotasController::class, 'update']);
        Route::delete('eliminarMascota/{id}', [MascotasController::class, 'destroy']);


    });

    // 🔹 Admin o adoptante
    Route::middleware([RoleMiddleware::class . ':admin,adoptante'])->group(function () {
        

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
        

    });
});












// Route::get('listarCitas', [CitasController::class, 'index']);
// Route::post('crearCitas', [CitasController::class, 'store']);
// Route::get('citas/{id}', [CitasController::class, 'show']);
// Route::put('actualizarCitas/{id}', [CitasController::class, 'update']);
// Route::delete('eliminarCitas/{id}', [CitasController::class, 'destroy']);

// Route::get('listarConsultorios', [ConsultoriosController::class, 'index']);
// Route::post('crearConsultorios', [ConsultoriosController::class, 'store']);
// Route::get('consultorios/{id}', [ConsultoriosController::class, 'show']);
// Route::put('actualizarConsultorios/{id}', [ConsultoriosController::class, 'update']);
// Route::delete('eliminarConsultorios/{id}', [ConsultoriosController::class, 'destroy']);

// Route::get('listarEspecialidades', [EspecialidadesController::class, 'index']);
// Route::post('crearEspecialidades', [EspecialidadesController::class, 'store']);
// Route::get('especialidades/{id}', [EspecialidadesController::class, 'show']);
// Route::put('actualizarEspecialidades/{id}', [EspecialidadesController::class, 'update']);
// Route::delete('eliminarEspecialidades/{id}', [EspecialidadesController::class, 'destroy']);

// Route::get('listarMedicos', [MedicosController::class, 'index']);
// Route::post('crearMedicos', [MedicosController::class, 'store']);
// Route::get('medicos/{id}', [MedicosController::class, 'show']);
// Route::put('actualizarMedicos/{id}', [MedicosController::class, 'update']);
// Route::delete('eliminarMedicos/{id}', [MedicosController::class, 'destroy']);

// Route::get('listarPacientes', [PacientesController::class, 'index']);
// Route::post('crearPacientes', [PacientesController::class, 'store']);
// Route::get('pacientes/{id}', [PacientesController::class, 'show']);
// Route::put('actualizarPacientes/{id}', [PacientesController::class, 'update']);
// Route::delete('eliminarPacientes/{id}', [PacientesController::class, 'destroy']);

// CONSULTAS ADICIONALES

// Route::get('listarHotmail', [PacientesController::class, 'listarHotmail']);
// Route::get('listar20Anios', [MedicosController::class, 'listar20Anios']);
// Route::get('listarConsultoriosSegundoP', [ConsultoriosController::class, 'listarConsultoriosSegundoP']);
// Route::get('listarMenores', [PacientesController::class, 'listarMenores']);
// Route::get('listarCitasActivas', [CitasController::class, 'listarCitasActivas']);
// Route::get('listarApellidosM', [PacientesController::class, 'listarApellidosM']);
// Route::get('listarCitasGripa', [CitasController::class, 'listarCitasGripa']);
// Route::get('listarMedicosCardiologia', [MedicosController::class, 'listarMedicosCardiologia']);
// Route::get('listarPacientesBogota', [PacientesController::class, 'listarPacientesBogota']);
// Route::get('listarCitasPacientes30', [CitasController::class, 'listarCitasPacientes30']);

