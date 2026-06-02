<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerificarRol
{
    /**
     * Verifica que el empleado autenticado tenga uno de los cargos permitidos.
     *
     * Uso en rutas:
     *   Route::get('/empleados', ...)->middleware('rol:Administrador');
     *   Route::get('/pos', ...)->middleware('rol:Administrador,Cajero,Supervisor');
     *
     * El campo `cargo` de la tabla empleados define el rol:
     *   Administrador | Cajero | Supervisor | Inventario
     */
    public function handle(Request $request, Closure $next, string ...$rolesPermitidos): Response
    {
        // 1. Debe haber sesión activa.
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $empleado = Auth::user();

        // 2. El Administrador siempre tiene acceso total.
        if ($empleado->cargo === 'Administrador') {
            return $next($request);
        }

        // 3. Verificar que el cargo del empleado esté en la lista permitida.
        if (!in_array($empleado->cargo, $rolesPermitidos, true)) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
