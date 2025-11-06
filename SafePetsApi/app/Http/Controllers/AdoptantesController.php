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

    // ➕ Registrar un nuevo adoptante
    public function store(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|email|max:255|unique:adoptantes,correo',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:100',
            'ocupacion' => 'nullable|string|max:100',
            'motivo_adopcion' => 'nullable|string',
            'estado' => 'required|string|in:Pendiente,Aprobado,Rechazado',
        ]);

        if ($validador->fails()) {
            return response()->json($validador->errors(), 422);
        }

        $adoptante = Adoptantes::create($request->all());
        return response()->json($adoptante, 201);
    }

    // 🔍 Mostrar un adoptante específico
    public function show(string $id)
    {
        $adoptante = Adoptantes::find($id);

        if (!$adoptante) {
            return response()->json(['message' => 'Adoptante no encontrado'], 404);
        }

        return response()->json($adoptante);
    }

    // ✏️ Actualizar un adoptante
    public function update(Request $request, string $id)
    {
        $adoptante = Adoptantes::find($id);

        if (!$adoptante) {
            return response()->json(['message' => 'Adoptante no encontrado'], 404);
        }

        $validador = Validator::make($request->all(), [
            'nombre' => 'string|max:255',
            'apellido' => 'string|max:255',
            'correo' => 'email|max:255|unique:adoptantes,correo,' . $id . ',id_adoptante',
            'telefono' => 'string|max:20',
            'direccion' => 'string|max:255',
            'ciudad' => 'string|max:100',
            'ocupacion' => 'string|max:100|nullable',
            'motivo_adopcion' => 'string|nullable',
            'estado' => 'string|in:Pendiente,Aprobado,Rechazado',
        ]);

        if ($validador->fails()) {
            return response()->json($validador->errors(), 422);
        }

        $adoptante->update($request->all());
        return response()->json($adoptante);
    }

    // 🗑️ Eliminar un adoptante
    public function destroy(string $id)
    {
        $adoptante = Adoptantes::find($id);

        if (!$adoptante) {
            return response()->json(['message' => 'Adoptante no encontrado'], 404);
        }

        $adoptante->delete();
        return response()->json(['message' => 'Adoptante eliminado correctamente']);
    }
}
