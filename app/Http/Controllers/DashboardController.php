<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();

        // Tarjetas
        $ventasHoy       = round(Venta::whereDate('fecha', $hoy)->sum('total'), 2);
        $idVentasHoy     = Venta::whereDate('fecha', $hoy)->pluck('id_venta');
        $productosHoy    = (int) DetalleVenta::whereIn('id_venta', $idVentasHoy)->sum('cantidad');
        $stockBajo       = Producto::whereColumn('stock', '<=', 'stock_minimo')->count();
        $ingresosTotales = round(Venta::sum('total'), 2);

        // Ventas de la semana (últimos 7 días)
        $labelsSemana = [];
        $datosSemana  = [];
        for ($i = 6; $i >= 0; $i--) {
            $dia = Carbon::today()->subDays($i);
            $labelsSemana[] = ucfirst($dia->locale('es')->isoFormat('ddd'));
            $datosSemana[]  = round(Venta::whereDate('fecha', $dia)->sum('total'), 2);
        }

        // Productos más vendidos (top 6)
        $top = DetalleVenta::with('producto')
            ->select('id_producto', DB::raw('SUM(cantidad) as total_cant'))
            ->groupBy('id_producto')->orderByDesc('total_cant')->limit(6)->get();

        // Ventas recientes (últimas 5) y productos con stock bajo
        $recientes      = Venta::with(['factura', 'detalles'])->orderByDesc('id_venta')->limit(5)->get();
        $productosBajos = Producto::whereColumn('stock', '<=', 'stock_minimo')->orderBy('stock')->limit(6)->get();

        return view('dashboard.index', compact(
            'ventasHoy', 'productosHoy', 'stockBajo', 'ingresosTotales',
            'labelsSemana', 'datosSemana', 'top', 'recientes', 'productosBajos'
        ));
    }
}
