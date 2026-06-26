<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;

class FacturacionController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->query('buscar');

        $ventas = Venta::with(['factura.cliente', 'empleado', 'detalles'])
            ->when($buscar, function ($q) use ($buscar) {
                $q->whereHas('factura', fn ($f) => $f->where('numero_factura', 'like', "%{$buscar}%"));
            })
            ->orderByDesc('id_venta')->get();

        // Estadísticas (sobre todas las ventas)
        $totalDocumentos = Venta::count();
        $totalFacturado  = Venta::sum('total');
        $pendientes      = 0; // toda venta del POS queda pagada

        return view('facturacion.index', compact('ventas', 'totalDocumentos', 'totalFacturado', 'pendientes', 'buscar'));
    }
}
