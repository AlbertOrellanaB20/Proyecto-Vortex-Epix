<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->query('buscar');

        $productos = Producto::query()
            ->when($buscar, function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('codigo_barras', 'like', "%{$buscar}%")
                  ->orWhere('categoria', 'like', "%{$buscar}%");
            })
            ->orderBy('id_producto')->get();

        // Estadísticas
        $totalProductos = Producto::count();
        $stockTotal = Producto::sum('stock');
        $productosBajos = Producto::whereColumn('stock', '<=', 'stock_minimo')->get();

        return view('inventario.index', compact('productos', 'totalProductos', 'stockTotal', 'productosBajos', 'buscar'));
    }

    // Reabastecer: sumar stock a un producto
    public function reabastecer(Request $request)
    {
        $datos = $request->validate([
            'id_producto' => ['required', 'exists:productos,id_producto'],
            'cantidad'    => ['required', 'integer', 'min:1'],
        ]);

        $producto = Producto::findOrFail($datos['id_producto']);
        $producto->increment('stock', $datos['cantidad']);

        return redirect()->route('inventario.index')->with('exito', "Se reabastecieron {$datos['cantidad']} unidades de {$producto->nombre}.");
    }

    // Exportar inventario a Excel (HTML que Excel abre con formato profesional)
    public function exportar()
    {
        $productos = Producto::orderBy('categoria')->orderBy('nombre')->get();
        $totalStock = $productos->sum('stock');
        $fecha = ucfirst(now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY, HH:mm'));

        $html = view('inventario.export', compact('productos', 'totalStock', 'fecha'))->render();

        $nombre = 'inventario_vortex_' . date('Y-m-d') . '.xls';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$nombre\"",
        ]);
    }
}
