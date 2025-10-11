<?php

namespace App\Http\Controllers;

use App\Models\Consultorios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsultoriosController extends Controller
{
    public function index(){
        $consultorios = Consultorios::all();

        return response()->json($consultorios);
    }




    public function store(Request $request){
        $validador = Validator::make($request->all(),[
            'numero' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
        ]);

        if ($validador->fails()) {
            return response()->json($validador->errors(), 422);
        }

        $consultorio = Consultorios::create($request->all());
        return response()->json($consultorio, 201);
    }




    public function show(string $id){
        $consultorio = Consultorios::find($id);
        
        if (!$consultorio) {
            return response()->json(['message' => 'Consultorio no encontrada'], 404);
        }

        return response()->json($consultorio);
    }




    public function update(Request $request, string $id){

        $consultorio = Consultorios::find($id);

        if (!$consultorio) {
            return response()->json(['message' => 'Consultorio no encontrada'], 404);
        }

        $validador = Validator::make($request->all(), [
            'numero' => 'string|max:255',
            'ubicacion' => 'string|max:255',
        ]);

        if ($validador->fails()) {
            return response()->json($validador->errors(), 422);
        }

        $consultorio->update($request->all());
        return response()->json($consultorio);
    }




    public function destroy(string $id){

        $consultorio = Consultorios::find($id);

        if (!$consultorio) {
            return response()->json(['message' => 'Consultorio no encontrada'], 404);
        }

        $consultorio->delete();
        return response()->json(null, 204);
    }

    // CONSULTAS ADICIONALES

    public function listarConsultoriosSegundoP(){
        $consultorios = Consultorios::where('ubicacion', 'like', '%piso 2%')->get();
        return response()->json($consultorios);
    }
 
    


}
