<?php

namespace App\Http\Controllers;

use App\Models\Mascotas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MascotasController extends Controller
{
    public function index()
    {
        $mascotas = Mascotas::all()->map(function ($mascota) {
            if ($mascota->imagen) {
                $mascota->imagen = asset('storage/' . $mascota->imagen);
            }
            return $mascota;
        });

        return response()->json($mascotas);
    }

    // ➕ Registrar nueva mascota (con imagen)
    public function store(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string|max:255',
            'raza' => 'nullable|string|max:255',
            'edad' => 'required|integer|min:0',
            'sexo' => 'required|string|in:Macho,Hembra',
            'tamano' => 'required|string|max:50',
            'fecha_ingreso' => 'required|date',
            'estado_salud' => 'required|string|max:255',
            'estado' => 'required|string|in:Disponible,Adoptado,En tratamiento',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validador->fails()) {
            return response()->json($validador->errors(), 422);
        }

        // 📸 Guardar imagen si existe
        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreArchivo = time() . '_' . $imagen->getClientOriginalName();
            $rutaImagen = $imagen->storeAs('mascotas', $nombreArchivo, 'public');
        }

        $mascota = Mascotas::create([
            'nombre' => $request->nombre,
            'especie' => $request->especie,
            'raza' => $request->raza,
            'edad' => $request->edad,
            'sexo' => $request->sexo,
            'tamano' => $request->tamano,
            'fecha_ingreso' => $request->fecha_ingreso,
            'estado_salud' => $request->estado_salud,
            'estado' => $request->estado,
            'descripcion' => $request->descripcion,
            'imagen' => $rutaImagen,
        ]);

        return response()->json($mascota, 201);
    }

    // 🔍 Mostrar mascota específica
    public function show(string $id)
    {
        $mascota = Mascotas::find($id);

        if (!$mascota) {
            return response()->json(['message' => 'Mascota no encontrada'], 404);
        }

        return response()->json($mascota);
    }

    // ✏️ Actualizar mascota (con reemplazo de imagen)
    public function update(Request $request, string $id)
    {
        $mascota = Mascotas::find($id);

        if (!$mascota) {
            return response()->json(['message' => 'Mascota no encontrada'], 404);
        }

        $validador = Validator::make($request->all(), [
            'nombre' => 'string|max:255',
            'especie' => 'string|max:255',
            'raza' => 'string|max:255',
            'edad' => 'integer|min:0',
            'sexo' => 'string|in:Macho,Hembra',
            'tamano' => 'string|max:50',
            'fecha_ingreso' => 'date',
            'estado_salud' => 'string|max:255',
            'estado' => 'string|in:Disponible,Adoptado,En tratamiento',
            'descripcion' => 'string|nullable',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validador->fails()) {
            return response()->json($validador->errors(), 422);
        }

        // 📸 Si hay nueva imagen, eliminar la anterior y guardar la nueva
        if ($request->hasFile('imagen')) {
            if ($mascota->imagen && Storage::disk('public')->exists($mascota->imagen)) {
                Storage::disk('public')->delete($mascota->imagen);
            }

            $imagen = $request->file('imagen');
            $nombreArchivo = time() . '_' . $imagen->getClientOriginalName();
            $rutaImagen = $imagen->storeAs('mascotas', $nombreArchivo, 'public');
            $mascota->imagen = $rutaImagen;
        }

        $mascota->update($request->except('imagen'));
        $mascota->save();

        return response()->json($mascota);
    }

    // 🗑️ Eliminar mascota (y su imagen)
    public function destroy(string $id)
    {
        $mascota = Mascotas::find($id);

        if (!$mascota) {
            return response()->json(['message' => 'Mascota no encontrada'], 404);
        }

        if ($mascota->imagen && Storage::disk('public')->exists($mascota->imagen)) {
            Storage::disk('public')->delete($mascota->imagen);
        }

        $mascota->delete();
        return response()->json(['message' => 'Mascota eliminada correctamente']);
    }
}
