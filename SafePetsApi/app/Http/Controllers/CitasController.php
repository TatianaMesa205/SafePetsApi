<?php

namespace App\Http\Controllers;

use App\Models\Citas;
use App\Models\Adoptantes;
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
            'fecha_cita' => 'required|date_format:Y-m-d H:i:s|after_or_equal:now',
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

    public function validarCitaActiva($email)
    {
        // Buscar adoptante por email (MISMO ESTILO QUE obtenerAdoptante)
        $adoptante = Adoptantes::where('email', $email)->first();

        if (!$adoptante) {
            return response()->json([
                'existe' => false,
                'cita_activa' => false
            ], 404);
        }

        // Buscar si tiene citas Pendiente o Confirmada
        $tieneCitaActiva = Citas::where('id_adoptantes', $adoptante->id_adoptantes)
            ->whereIn('estado', ['Pendiente', 'Confirmada'])
            ->exists();

        return response()->json([
            'existe' => true,
            'cita_activa' => $tieneCitaActiva
        ], 200);
    }



}
