<?php

namespace App\Http\Controllers;

use App\Models\Pacientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PacientesController extends Controller
{
    public function index(){
        $pacientes = Pacientes::all();

        return response()->json($pacientes);
    }




    public function store(Request $request){
        $validador = Validator::make($request->all(),[
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'documento' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'required|string|max:255',
        ]);

        if ($validador->fails()) {
            return response()->json($validador->errors(), 422);
        }

        $paciente = Pacientes::create($request->all());
        return response()->json($paciente, 201);
    }




    public function show(string $id){
        $paciente = Pacientes::find($id);
        
        if (!$paciente) {
            return response()->json(['message' => 'Paciente no encontrada'], 404);
        }

        return response()->json($paciente);
    }




    public function update(Request $request, string $id){

        $paciente = Pacientes::find($id);

        if (!$paciente) {
            return response()->json(['message' => 'Paciente no encontrado'], 404);
        }

        $validador = Validator::make($request->all(), [
            'nombre' => 'string|max:255',
            'apellido' => 'string|max:255',
            'documento' => 'string|max:255',
            'telefono' => 'string|max:255',
            'email' => 'string|max:255',
            'fecha_nacimiento' => 'date',
            'direccion' => 'string|max:255',
        ]);

        if ($validador->fails()) {
            return response()->json($validador->errors(), 422);
        }

        $paciente->update($request->all());
        return response()->json($paciente);
    }




    public function destroy(string $id){

        $paciente = Pacientes::find($id);

        if (!$paciente) {
            return response()->json(['message' => 'Paciente no encontrado'], 404);
        }

        $paciente->delete();
        return response()->json(null, 204);
    }

    // CONSULTAS ADICIONALES

    public function listarHotmail(){
        
        $pacientes = Pacientes::where('email', 'like', '%@hotmail.%')->get();
        return response()->json($pacientes);
    }

    public function listarMenores(){

        $pacientes = Pacientes::where('fecha_nacimiento', '>', now()->subYears(18))->get();
        return response()->json($pacientes);
    }

    public function listarApellidosM(){

        $pacientes = Pacientes::where('apellido', 'like', 'M%')->get();
        return response()->json($pacientes);
    }

    public function listarPacientesBogota(){
        
        $pacientes = Pacientes::where('direccion', 'like', '%Bogotá%')->get();
        return response()->json($pacientes);
    }

    public function contadorPacientes()
    {
        $cantidad = Pacientes::count();

        return response()->json([
            'cantidad_pacientes' => $cantidad
        ]);
    }




}