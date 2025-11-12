<?php

namespace App\Http\Controllers;

use App\Models\VacunasMascotas;
use Illuminate\Http\Request;

class VacunasController extends Controller
{
    public function index($id_mascotas)
    {
        $vacunas = VacunasMascotas::with('vacuna')
            ->where('id_mascotas', $id_mascotas)
            ->get();

        return response()->json($vacunas);
    }
}

