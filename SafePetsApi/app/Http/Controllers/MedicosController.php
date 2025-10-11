<?php

namespace App\Http\Controllers;

use App\Models\Medicos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicosController extends Controller
{
    public function index(){
        $medicos = Medicos::with('especialidades:id,nombre_e')->get();
        return response()->json($medicos);
    }


    public function store(Request $request){
        $validador = Validator::make($request->all(),[
            'id_especialidades' => 'required|exists:especialidades,id',
            'nombre_m' => 'required|string|max:255',
            'apellido_m' => 'required|string|max:255',
            'edad' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
        ]);

        if ($validador->fails()) {
            return response()->json($validador->errors(), 422);
        }

        $medico = Medicos::create($request->all());
        return response()->json($medico, 201);
    }




    public function show(string $id){
        $medico = Medicos::find($id);
        
        if (!$medico) {
            return response()->json(['message' => 'Medico no encontrado'], 404);
        }

        return response()->json($medico);
    }




    public function update(Request $request, string $id){

        $medico = Medicos::find($id);

        if (!$medico) {
            return response()->json(['message' => 'Medico no encontrad'], 404);
        }

        $validador = Validator::make($request->all(), [
            'id_especialidades' => 'exists:especialidades,id',
            'nombre_m' => 'string|max:255',
            'apellido_m' => 'string|max:255',
            'edad' => 'string|max:255',
            'telefono' => 'string|max:255',
        ]);

        if ($validador->fails()) {
            return response()->json($validador->errors(), 422);
        }

        $medico->update($request->all());
        return response()->json($medico);
    }




    public function destroy(string $id){

        $medico = Medicos::find($id);

        if (!$medico) {
            return response()->json(['message' => 'Medico no encontrado'], 404);
        }

        $medico->delete();
        return response()->json(null, 204);
    }

    // CONSULTAS ADICIONALES

    public function listar20Anios(){

        $medicos = Medicos::where('edad', '>', 20)->get();
        return response()->json($medicos);
    }

    public function listarMedicosCardiologia(){
        
        $medicos = Medicos::whereHas('especialidades', function($q){
        $q->where('nombre_e', 'Cardiología');
        })->get();

        return response()->json($medicos);
    }

    public function contadorMedicos()
    {
        $cantidad = Medicos::count();

        return response()->json([
            'cantidad_medicos' => $cantidad
        ]);
    }   



}
