<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Usuarios;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function registrar(Request $request)
    {
        // ✅ Validar los campos
        $validator = Validator::make($request->all(), [
            'nombre_usuario' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:6',
            'id_roles' => 'required|integer|in:1,2', // solo 1=admin, 2=adoptante
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        // ✅ Crear el usuario
        $usuario = Usuarios::create([
            'nombre_usuario' => $request->nombre_usuario,
            'email' => $request->email,
            'password' => Hash::make($request->password), // 🔒 Encripta correctamente
            'id_roles' => $request->id_roles,
        ]);

        // ✅ Crear token de acceso
        $token = $usuario->createToken('auth_token')->plainTextToken;

        // ✅ Determinar nombre del rol
        $rol = $usuario->id_roles == 1 ? 'admin' : 'adoptante';

        // ✅ Respuesta JSON
        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'rol' => $rol,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'usuario' => [
                'id' => $usuario->id_usuarios,
                'nombre_usuario' => $usuario->nombre_usuario,
                'email' => $usuario->email,
                'id_roles' => $usuario->id_roles,
            ],
        ], 201);
    }

    // ✅ Inicio de sesión
    public function login(Request $request)
    {
        // Validar formato del email y la contraseña
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns',
            'password' => 'required|string',
        ], [
            'email.required' => 'Por favor ingresa un correo electrónico.',
            'email.email' => 'El correo ingresado no tiene un formato válido.',
            'password.required' => 'Por favor ingresa la contraseña.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Buscar usuario por email
        $usuario = Usuarios::where('email', $request->email)->first();

        // Verificar existencia del usuario
        if (!$usuario) {
            return response()->json([
                'message' => 'El correo ingresado no está registrado.'
            ], 404);
        }

        // Verificar contraseña
        if (!Hash::check($request->password, $usuario->password)) {
            return response()->json([
                'message' => 'La contraseña es incorrecta.'
            ], 401);
        }

        // Crear token
        $token = $usuario->createToken('auth_token')->plainTextToken;

        // Rol
        $rol = $usuario->id_roles == 1 ? 'admin' : 'adoptante';

        // Respuesta
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
        $usuario = $request->user(); // Obtiene el usuario autenticado por Sanctum

        // ✅ Validar datos
        $validator = Validator::make($request->all(), [
            'nombre_usuario' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        // ✅ Actualizar solo los campos enviados
        if ($request->filled('nombre_usuario')) {
            $usuario->nombre_usuario = $request->nombre_usuario;
        }

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password); // 🔒 Encripta correctamente
        }

        $usuario->save();

        return response()->json([
            'success' => true,
            'message' => 'Perfil actualizado correctamente',
            'usuario' => [
                'id_usuarios' => $usuario->id_usuarios,
                'nombre_usuario' => $usuario->nombre_usuario,
                'email' => $usuario->email,
                'id_roles' => $usuario->id_roles,
            ],
        ]);
    }
}
