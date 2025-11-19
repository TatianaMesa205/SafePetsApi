<?php

namespace App\Http\Controllers;

use App\Models\Publicaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicacionesController extends Controller
{
    // 📌 Listar todas las publicaciones
    public function index()
    {
        $publicaciones = Publicaciones::all()->map(function ($pub) {
            if ($pub->foto) {
                $pub->foto = url('publicaciones/' . $pub->foto);
            }
            return $pub;
        });

        return response()->json($publicaciones);
    }

    // ➕ Registrar nueva publicación (con imagen)
    public function store(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'tipo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_publicacion' => 'required|date',
            'contacto' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validador->fails()) {
            return response()->json($validador->errors(), 422);
        }

        // 📸 Guardar imagen si existe
        $rutaFoto = null;
        if ($request->hasFile('foto')) {
            $imagen = $request->file('foto');
            $nombreArchivo = time() . '_' . $imagen->getClientOriginalName();

            // Carpeta compartida
            $rutaDestino = base_path('../../CarpetaCompartida/Publicaciones');

            // Guardar imagen
            $imagen->move($rutaDestino, $nombreArchivo);

            // Guardar solo nombre del archivo
            $rutaFoto = $nombreArchivo;
        }

        $publicacion = Publicaciones::create([
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'fecha_publicacion' => $request->fecha_publicacion,
            'contacto' => $request->contacto,
            'foto' => $rutaFoto,
        ]);

        return response()->json($publicacion, 201);
    }
}
