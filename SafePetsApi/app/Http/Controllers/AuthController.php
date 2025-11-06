<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Usuarios;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // ✅ Registro de usuario
    public function registrar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_usuario' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:8',
            'id_roles' => 'required|in:1,2', // solo se aceptan roles válidos (admin/adoptante)
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Crear el usuario
        $usuario = Usuarios::create([
            'nombre_usuario' => $request->nombre_usuario,
            'email' => $request->email,
            'contrasena' => Hash::make($request->password),
            'id_roles' => $request->id_roles,
        ]);

        // Crear el token
        $token = $usuario->createToken('auth_token')->plainTextToken;

        // Asignar nombre del rol
        $rol = $usuario->id_roles == 1 ? 'admin' : 'adoptante';

        return response()->json([
            'nombre_usuario' => $usuario->nombre_usuario,
            'email' => $usuario->email,
            'rol' => $rol,
            'created_at' => $usuario->created_at,
            'token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    // ✅ Inicio de sesión
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $usuario = Usuarios::where('email', $request->email)->firstOrFail();

        $token = $usuario->createToken('auth_token')->plainTextToken;

        $rol = $usuario->id_roles == 1 ? 'admin' : 'adoptante';

        return response()->json([
            'message' => 'Bienvenido ' . $usuario->nombre_usuario,
            'rol' => $rol,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'usuario' => $usuario
        ]);
    }

    // ✅ Cierre de sesión
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Has cerrado sesión correctamente y el token fue eliminado'
        ]);
    }

    // ✅ Información del usuario autenticado
    public function me(Request $request)
    {
        $usuario = Auth::user();

        $rol = $usuario->id_roles == 1 ? 'admin' : 'adoptante';

        return response()->json([
            'success' => true,
            'usuario' => $usuario,
            'rol' => $rol
        ], 200);
    }
}
