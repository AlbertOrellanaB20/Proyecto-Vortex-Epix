<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    // Reúne todos los datos de los reportes según el rango de fechas
    private function datos(Request $request): array
    {
        $desde = $request->query('desde');
        $hasta = $request->query('hasta');

        $ventas = Venta::with(['factura', 'detalles'])
            ->when($desde, fn ($q) => $q->whereDate('fecha', '>=', $desde))
            ->when($hasta, fn ($q) => $q->whereDate('fecha', '<=', $hasta))
            ->get();

        $idVentas = $ventas->pluck('id_venta');

        // Tarjetas
        $ventasTotales    = round($ventas->sum('total'), 2);
        $productosVendidos = (int) DetalleVenta::whereIn('id_venta', $idVentas)->sum('cantidad');
        $clientesActivos  = Cliente::count();
        $ticketPromedio   = $ventas->count() ? round($ventasTotales / $ventas->count(), 2) : 0;

        // Ventas por día de la semana (0=Dom ... 6=Sáb)
        $porDia = array_fill(0, 7, 0.0);
        // Ventas por mes (0=Ene ... 11=Dic)
        $porMes = array_fill(0, 12, 0.0);
        // Métodos de pago
        $metodos = ['Efectivo' => 0.0, 'Tarjeta' => 0.0];

        foreach ($ventas as $v) {
            $fecha = \Carbon\Carbon::parse($v->fecha);
            $porDia[$fecha->dayOfWeek] += (float) $v->total;
            $porMes[$fecha->month - 1] += (float) $v->total;
            $mp = $v->factura->metodo_pago ?? 'Efectivo';
            $metodos[$mp] = ($metodos[$mp] ?? 0) + (float) $v->total;
        }

        // Top productos vendidos
        $top = DetalleVenta::with('producto')
            ->whereIn('id_venta', $idVentas)
            ->select('id_producto', DB::raw('SUM(cantidad) as total_cant'))
            ->groupBy('id_producto')->orderByDesc('total_cant')->limit(8)->get();

        return [
            'desde' => $desde, 'hasta' => $hasta, 'ventas' => $ventas,
            'ventasTotales' => $ventasTotales, 'productosVendidos' => $productosVendidos,
            'clientesActivos' => $clientesActivos, 'ticketPromedio' => $ticketPromedio,
            'porDia' => array_map(fn ($n) => round($n, 2), array_values($porDia)),
            'porMes' => array_map(fn ($n) => round($n, 2), array_values($porMes)),
            'metodos' => $metodos, 'top' => $top,
        ];
    }

    public function index(Request $request)
    {
        return view('reportes.index', $this->datos($request));
    }

    // Exportar a Excel (HTML que abre Excel con formato)
    public function exportarExcel(Request $request)
    {
        $d = $this->datos($request);
        $html = view('reportes.export', $d)->render();
        $nombre = 'reporte_ventas_' . date('Y-m-d') . '.xls';
        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$nombre\"",
        ]);
    }

    // Exportar a PDF (página imprimible; el navegador guarda como PDF)
    public function exportarPdf(Request $request)
    {
        return view('reportes.pdf', $this->datos($request));
    }
}
