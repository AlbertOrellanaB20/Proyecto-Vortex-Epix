<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // Lista de productos con buscador opcional
    public function index(Request $request)
    {
        $buscar = $request->query('buscar');

        $productos = Producto::query()
            ->when($buscar, function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('codigo_barras', 'like', "%{$buscar}%")
                  ->orWhere('categoria', 'like', "%{$buscar}%");
            })
            ->orderBy('id_producto')
            ->get();

        $categorias = ['Alimentos', 'Bebidas', 'Snacks', 'Lacteos', 'Panaderia', 'Limpieza'];

        return view('productos.index', compact('productos', 'categorias', 'buscar'));
    }

    // Guardar un producto nuevo
    public function store(Request $request)
    {
        $datos = $request->validate([
            'codigo_barras' => ['required', 'string', 'max:13', 'unique:productos,codigo_barras'],
            'nombre'        => ['required', 'string', 'max:100'],
            'categoria'     => ['required', 'string', 'max:100'],
            'marca'         => ['nullable', 'string', 'max:100'],
            'precio'        => ['required', 'numeric', 'min:0'],
            'stock'         => ['required', 'integer', 'min:0'],
            'stock_minimo'  => ['nullable', 'integer', 'min:0'],
            'fecha_vencimiento' => ['nullable', 'date'],
        ], [], ['codigo_barras' => 'código de barras']);

        // El IVA (13%) se calcula automáticamente
        $datos['precio_con_iva'] = round($datos['precio'] * 1.13, 2);
        $datos['stock_minimo'] = $datos['stock_minimo'] ?? 10;

        Producto::create($datos);

        return redirect()->route('productos.index')->with('exito', 'Producto agregado correctamente.');
    }

    // Actualizar un producto
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $datos = $request->validate([
            'codigo_barras' => ['required', 'string', 'max:13', 'unique:productos,codigo_barras,' . $id . ',id_producto'],
            'nombre'        => ['required', 'string', 'max:100'],
            'categoria'     => ['required', 'string', 'max:100'],
            'marca'         => ['nullable', 'string', 'max:100'],
            'precio'        => ['required', 'numeric', 'min:0'],
            'stock'         => ['required', 'integer', 'min:0'],
            'stock_minimo'  => ['nullable', 'integer', 'min:0'],
            'fecha_vencimiento' => ['nullable', 'date'],
        ], [], ['codigo_barras' => 'código de barras']);

        $datos['precio_con_iva'] = round($datos['precio'] * 1.13, 2);

        $producto->update($datos);

        return redirect()->route('productos.index')->with('exito', 'Producto actualizado correctamente.');
    }

    // Eliminar un producto
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('productos.index')->with('exito', 'Producto eliminado correctamente.');
    }
}
