<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->query('buscar');

        $logs = Bitacora::query()
            ->when($buscar, function ($q) use ($buscar) {
                $q->where('usuario', 'like', "%{$buscar}%")
                  ->orWhere('accion', 'like', "%{$buscar}%")
                  ->orWhere('modulo', 'like', "%{$buscar}%")
                  ->orWhere('ip', 'like', "%{$buscar}%");
            })
            ->orderByDesc('id')->limit(300)->get();

        $total = Bitacora::count();
        $hoy   = Bitacora::whereDate('fecha', today())->count();

        return view('seguridad.logs', compact('logs', 'total', 'hoy', 'buscar'));
    }
}
