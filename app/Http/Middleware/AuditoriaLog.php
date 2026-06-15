<?php

namespace App\Http\Middleware;

use App\Models\Bitacora;
use Closure;
use Illuminate\Http\Request;

class AuditoriaLog
{
    // Registra en la bitácora las acciones importantes del sistema
    public function handle(Request $request, Closure $next)
    {
        $usuarioAntes = auth()->user(); // se guarda antes (sirve para el cierre de sesión)

        $response = $next($request);

        try {
            $ruta = $request->path();
            $metodo = $request->method();

            if ($ruta === 'login' && $metodo === 'POST' && auth()->check()) {
                $this->registrar(auth()->user(), 'Inició sesión', 'Acceso', $request);
            } elseif ($ruta === 'logout' && $usuarioAntes) {
                $this->registrar($usuarioAntes, 'Cerró sesión', 'Acceso', $request);
            } elseif (auth()->check() && in_array($metodo, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                $this->registrar(auth()->user(), $this->describir($metodo, $ruta), $this->modulo($ruta), $request);
            }
        } catch (\Throwable $e) {
            // Si algo falla al guardar el log, NO se rompe la aplicación
        }

        return $response;
    }

    private function registrar($usuario, string $accion, string $modulo, Request $request): void
    {
        Bitacora::create([
            'id_empleado' => $usuario->id_empleado ?? null,
            'usuario'     => $usuario->usuario ?? '',
            'accion'      => $accion,
            'modulo'      => $modulo,
            'metodo'      => $request->method(),
            'ruta'        => '/' . $request->path(),
            'ip'          => $request->ip(),
            'fecha'       => now(),
        ]);
    }

    private function modulo(string $ruta): string
    {
        $seg = explode('/', $ruta)[0] ?? '';
        return ucfirst($seg ?: 'Sistema');
    }

    private function describir(string $metodo, string $ruta): string
    {
        $map = ['POST' => 'Creó / registró', 'PUT' => 'Actualizó', 'PATCH' => 'Actualizó', 'DELETE' => 'Eliminó'];
        return ($map[$metodo] ?? $metodo) . ' en ' . $this->modulo($ruta);
    }
}
