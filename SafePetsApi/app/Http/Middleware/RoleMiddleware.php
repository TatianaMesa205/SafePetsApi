<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        // Verificar si hay usuario autenticado
        if (!$user) {
            return response()->json(['error' => 'Token no válido o no proporcionado'], 401);
        }

        // Verificar si el rol del usuario está permitido
        // Se remplazo roles por id_roles
        if (!in_array($user->id_roles, $roles)) {
            return response()->json(['error' => 'Rol no autorizado'], 403);
        }

        return $next($request);
    }
}
