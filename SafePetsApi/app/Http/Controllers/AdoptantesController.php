<?php

namespace App\Http\Controllers;

use App\Models\Adoptantes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdoptantesController extends Controller
{
    // 📋 Listar todos los adoptantes
    public function index()
    {
        $adoptantes = Adoptantes::all();
        return response()->json($adoptantes);
    }

    public function store(Request $request)
    {
        // Validación de datos
        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:150',
            'cedula'          => 'required|string|max:30',
            'telefono'        => 'required|string|max:30',
            'email'           => 'required|email',
            'direccion'       => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errores' => $validator->errors()
            ], 422);
        }

        // Busca por email (si ya existe, actualiza)
        $adoptante = Adoptantes::where('email', $request->email)->first();

        if ($adoptante) {
            // Actualizar datos
            $adoptante->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Datos del adoptante actualizados correctamente.',
                'data' => $adoptante
            ], 200);
        }

        // Crear nuevo adoptante
        $nuevo = Adoptantes::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Adoptante registrado correctamente.',
            'data' => $nuevo
        ], 201);
    }

    public function obtenerAdoptante($email)
    {
        $adoptante = Adoptantes::where('email', $email)->first();

        if (!$adoptante) {
            return response()->json([
                'message' => 'No existe un adoptante con este email'
            ], 404);
        }

        return response()->json($adoptante, 200);
    }


    public function verificar($email)
    {
        // Buscar si existe un registro en adoptantes con ese email
        $existe = Adoptantes::where('email', $email)->exists();

        return response()->json($existe, 200);
    }
}
