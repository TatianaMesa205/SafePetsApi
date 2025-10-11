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

        if (!$user) {
            return response()->json(['error' => 'Token no válido o no proporcionado'], 401);
        }

        if (!in_array($user->role, $roles)) {
            return response()->json(['error' => 'Rol no autorizado'], 403);
        }

        return $next($request);
    }

}