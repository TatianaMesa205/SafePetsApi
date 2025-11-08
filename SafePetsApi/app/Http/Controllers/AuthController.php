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
        // Validar los campos
        $validator = Validator::make($request->all(), [
            'nombre_usuario' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:6',
            'id_roles' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        // Crear el usuario
        $usuario = Usuarios::create([
            'nombre_usuario' => $request->nombre_usuario,
            'email' => $request->email,
            'contrasena' => Hash::make($request->password), // 🔒 Encripta la contraseña
            'id_roles' => $request->id_roles
        ]);

        // Crear token
        $token = $usuario->createToken('auth_token')->plainTextToken;

        // Determinar rol
        $rol = $usuario->id_roles == 1 ? 'admin' : 'adoptante';

        // Respuesta
        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'rol' => $rol,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'usuario' => $usuario
        ], 201);
    }

    // ✅ Inicio de sesión
    public function login(Request $request)
    {
        // Validar campos obligatorios
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Buscar usuario por email
        $usuario = Usuarios::where('email', $request->email)->first();

        // Verificar si existe y si la contraseña es correcta
        if (!$usuario || !Hash::check($request->password, $usuario->contrasena)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Crear token de acceso
        $token = $usuario->createToken('auth_token')->plainTextToken;

        // Determinar rol
        $rol = $usuario->id_roles == 1 ? 'admin' : 'adoptante';

        // Respuesta con datos
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

    public function editarPerfil(Request $request)
    {
        $usuario = $request->user();

        $validator = Validator::make($request->all(), [
            'nombre_usuario' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $usuario->nombre_usuario = $request->nombre_usuario;

        if (!empty($request->password)) {
            $usuario->contrasena = Hash::make($request->password); // 👈 usa "password" recibido
        }

        $usuario->save();

        return response()->json([
            'success' => true,
            'message' => 'Perfil actualizado correctamente',
            'usuario' => $usuario
        ]);
    }



}
