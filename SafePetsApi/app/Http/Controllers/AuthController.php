<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Usuarios;
use App\Models\Adoptantes;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function registrar(Request $request)
    {
        // ✅ Validar los campos con reglas y mensajes personalizados
        $validator = Validator::make(
            $request->all(),
            [
                'nombre_usuario' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:usuarios,nombre_usuario', // validar que no exista
                    'regex:/^\S+$/', // no permitir espacios
                ],
                'email' => 'required|string|email|max:255|unique:usuarios',
                'password' => 'required|string|min:6',
                'id_roles' => 'required|integer|in:1,2',
            ],
            [
                // 📌 Mensajes personalizados
                'nombre_usuario.unique' => 'Este nombre de usuario ya está en uso',
                'nombre_usuario.regex' => 'El nombre de usuario no puede contener espacios',
                'email.unique' => 'El email no debe estar previamente registrado',
            ]
        );

        // ✋ Si falla la validación
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en los datos enviados',
                'errors' => $validator->errors(),
            ], 422);
        }

        // ✅ Crear el usuario
        $usuario = Usuarios::create([
            'nombre_usuario' => $request->nombre_usuario,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_roles' => $request->id_roles,
        ]);

        // Crear token
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'rol' => $usuario->id_roles == 1 ? 'admin' : 'adoptante',
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


    // ✅ Inicio de sesión con email O nombre_usuario
    public function login(Request $request)
    {
        // Validar que el campo identificador venga lleno
        $validator = Validator::make($request->all(), [
            'identificador' => 'required|string',
            'password' => 'required|string',
        ], [
            'identificador.required' => 'Por favor ingresa tu correo o nombre de usuario.',
            'password.required' => 'Por favor ingresa la contraseña.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        $identificador = $request->identificador;

        // 📌 Buscar por email O nombre_usuario
        $usuario = Usuarios::where('email', $identificador)
                    ->orWhere('nombre_usuario', $identificador)
                    ->first();

        // Verificar existencia del usuario
        if (!$usuario) {
            return response()->json([
                'message' => 'El usuario no está registrado.'
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
            'message' => 'Has cerrado sesión correctamente'
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

    public function actualizarPerfilCompleto(Request $request)
    {
        $user = auth()->user();

        // Actualizar tabla usuarios
        $user->nombre_usuario = $request->nombre_usuario;
        $user->save();

        // Actualizar tabla adoptantes
        $adoptante = Adoptantes::where('email', $user->email)->first();

        if ($adoptante) {
            $adoptante->nombre_completo = $request->nombre_completo;
            $adoptante->cedula = $request->cedula;
            $adoptante->telefono = $request->telefono;
            $adoptante->direccion = $request->direccion;
            $adoptante->save();
        }

        return response()->json([
            "success" => true,
            "message" => "Perfil actualizado correctamente"
        ]);
    }

}
