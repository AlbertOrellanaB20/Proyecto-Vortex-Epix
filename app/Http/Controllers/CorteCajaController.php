<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CorteCajaController extends Controller
{
    // Fondo de cambio (vuelto) que cada cajero tiene en su caja.
    // Se puede cambiar aquí si el profe pide otro monto.
    const FONDO_CAMBIO = 50.00;

    public function index(Request $request)
    {
        $cajero = auth()->user();

        // Inicio del turno = desde que el cajero inició sesión.
        // Si no existe (sesión vieja), se usa el inicio del día de hoy.
        $inicio = $request->session()->get('turno_inicio')
            ?? Carbon::today('America/El_Salvador')->toDateTimeString();

        // SOLO las ventas de ESTE cajero en ESTE turno (cada cajero ve datos distintos).
        $ventas = Venta::with('factura')
            ->where('id_empleado', $cajero->id_empleado)
            ->where('fecha', '>=', $inicio)
            ->get();

        $cantidad     = $ventas->count();
        $totalVendido = round($ventas->sum('total'), 2);
        $efectivo     = round($ventas->filter(fn ($v) => optional($v->factura)->metodo_pago === 'Efectivo')->sum('total'), 2);
        $tarjeta      = round($ventas->filter(fn ($v) => optional($v->factura)->metodo_pago === 'Tarjeta')->sum('total'), 2);

        $fondo          = self::FONDO_CAMBIO;
        $efectivoEnCaja = round($fondo + $efectivo, 2); // lo que físicamente debería haber en la caja
        $ganadoEfectivo = $efectivo;                    // ventas en efectivo, SIN contar el fondo de $50

        return view('corte.index', compact(
            'cajero', 'inicio', 'cantidad', 'totalVendido',
            'efectivo', 'tarjeta', 'fondo', 'efectivoEnCaja', 'ganadoEfectivo'
        ));
    }
}
