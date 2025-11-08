<?php

namespace App\Http\Controllers;

use App\Models\Citas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CitasController extends Controller
{
    // 📋 Listar todas las citas
    public function index()
    {
        $citas = Citas::with(['adoptante', 'mascota'])->get();

        return response()->json($citas);
    }

    // ➕ Crear una nueva cita
    public function store(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'id_adoptantes' => 'required|exists:adoptantes,id_adoptantes',
            'id_mascotas' => 'required|exists:mascotas,id_mascotas',
            'fecha_cita' => 'required|date|after_or_equal:today',
            'estado' => 'nullable|string|in:Pendiente,Confirmada,Cancelada,Completada',
            'motivo' => 'required|string|max:255',
        ]);

        if ($validador->fails()) {
            return response()->json($validador->errors(), 422);
        }

        $cita = Citas::create([
            'id_adoptantes' => $request->id_adoptantes,
            'id_mascotas' => $request->id_mascotas,
            'fecha_cita' => $request->fecha_cita,
            'estado' => $request->estado ?? 'Pendiente',
            'motivo' => $request->motivo,
        ]);

        return response()->json([
            'message' => 'Cita registrada exitosamente',
            'data' => $cita
        ], 201);
    }

    // 🔍 Mostrar una cita específica
    public function show(string $id)
    {
        $cita = Citas::with(['adoptante', 'mascota'])->find($id);

        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }

        return response()->json($cita);
    }

    // ✏️ Actualizar una cita
    public function update(Request $request, string $id)
    {
        $cita = Citas::find($id);

        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }

        $validador = Validator::make($request->all(), [
            'fecha_cita' => 'date|after_or_equal:today',
            'estado' => 'string|in:Pendiente,Confirmada,Cancelada,Completada',
            'motivo' => 'string|max:255',
        ]);

        if ($validador->fails()) {
            return response()->json($validador->errors(), 422);
        }

        $cita->update($request->all());

        return response()->json([
            'message' => 'Cita actualizada correctamente',
            'data' => $cita
        ]);
    }

    // 🗑️ Eliminar una cita
    public function destroy(string $id)
    {
        $cita = Citas::find($id);

        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }

        $cita->delete();

        return response()->json(['message' => 'Cita eliminada correctamente']);
    }

    // 🔎 Citas por adoptante
    public function citasPorAdoptante($id_adoptantes)
    {
        $citas = Citas::where('id_adoptantes', $id_adoptantes)
            ->with('mascota')
            ->get();

        if ($citas->isEmpty()) {
            return response()->json(['message' => 'No se encontraron citas para este adoptante'], 404);
        }

        return response()->json($citas);
    }

}
