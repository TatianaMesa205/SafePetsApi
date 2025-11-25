<?php

namespace App\Http\Controllers;

use App\Models\Citas;
use App\Models\Adoptantes;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\CitaCanceladaMail;

class CitasController extends Controller
{

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

    public function historialPorEmail($email)
    {
        // Buscar adoptante
        $adoptante = Adoptantes::where('email', $email)->first();

        if (!$adoptante) {
            return response()->json([
                'success' => false,
                'message' => 'No existe un adoptante con este email'
            ], 404);
        }

        // Obtener citas relacionadas (incluir nombre de mascota)
        $citas = Citas::where('id_adoptantes', $adoptante->id_adoptantes)
                    ->with(['mascota:id_mascotas,nombre'])
                    ->orderBy('fecha_cita', 'desc')
                    ->get();

        return response()->json([
            'success' => true,
            'adoptante' => $adoptante,
            'citas' => $citas
        ], 200);
    }

    public function cancelar(Request $request, $id)
    {
        // Buscar la cita por su id
        $cita = Citas::where('id_citas', $id)->first();

        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada.'], 404);
        }

        // Cambiar estado a Cancelada
        $cita->estado = 'Cancelada';
        $cita->save();

        // Obtener correos de administradores
        $adminEmails = Usuarios::where('id_roles', 1)->pluck('email')->toArray();

        // Enviar correo al admin
        if (!empty($adminEmails)) {
            Mail::to($adminEmails)->send(new CitaCanceladaMail($cita));
        }

        return response()->json(['message' => 'Cita cancelada correctamente.'], 200);
    }


}
