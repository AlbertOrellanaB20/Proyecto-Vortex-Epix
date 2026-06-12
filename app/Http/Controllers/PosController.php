<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use App\Models\Factura;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        return view('pos.index');
    }

    // Buscar producto por código (escaneado o tecleado). El profe pidió SOLO por código.
    public function buscar(Request $request)
    {
        $codigo = trim($request->query('codigo', ''));
        if ($codigo === '') {
            return response()->json(['ok' => false, 'mensaje' => 'Código vacío'], 422);
        }

        // 1) Buscar por código de barras exacto
        $producto = Producto::where('codigo_barras', $codigo)->first();

        // 2) Si no aparece, intentar por ID (acepta "5", "P005", "005")
        if (!$producto) {
            $numero = (int) ltrim(preg_replace('/[^0-9]/', '', $codigo), '0');
            if ($numero > 0) {
                $producto = Producto::find($numero);
            }
        }

        if (!$producto) {
            return response()->json(['ok' => false, 'mensaje' => 'Producto no encontrado'], 404);
        }
        if ($producto->stock <= 0) {
            return response()->json(['ok' => false, 'mensaje' => 'Sin stock disponible'], 409);
        }

        return response()->json([
            'ok' => true,
            'producto' => [
                'id'     => $producto->id_producto,
                'nombre' => $producto->nombre,
                'precio' => (float) $producto->precio,
                'stock'  => $producto->stock,
                'imagen' => $producto->imagen,
                'categoria' => $producto->categoria,
            ],
        ]);
    }

    // Procesar el cobro: TRANSACCIÓN ATÓMICA (BEGIN / COMMIT / ROLLBACK)
    public function cobrar(Request $request)
    {
        $datos = $request->validate([
            'items'            => ['required', 'array', 'min:1'],
            'items.*.id'       => ['required', 'integer'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
            'metodo_pago'      => ['required', 'in:Efectivo,Tarjeta'],
            'tipo_documento'   => ['required', 'in:Ticket,Factura'],
            'efectivo'         => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $productos = [];

            // Validar stock de TODOS los productos antes de tocar nada
            foreach ($datos['items'] as $item) {
                $producto = Producto::lockForUpdate()->find($item['id']);
                if (!$producto) {
                    throw new \Exception("Un producto del carrito ya no existe.");
                }
                if ($producto->stock < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente de {$producto->nombre} (quedan {$producto->stock}).");
                }
                $subtotal += $producto->precio * $item['cantidad'];
                $productos[$producto->id_producto] = $producto;
            }

            $impuesto = round($subtotal * 0.13, 2);
            $total    = round($subtotal + $impuesto, 2);

            // Si es efectivo, validar que alcance
            if ($datos['metodo_pago'] === 'Efectivo') {
                $efectivo = (float) ($datos['efectivo'] ?? 0);
                if ($efectivo < $total) {
                    throw new \Exception("El efectivo recibido no cubre el total.");
                }
            }

            // Crear factura
            $numero = (Factura::max('numero_factura') ?? 1000) + 1;
            $factura = Factura::create([
                'metodo_pago'    => $datos['metodo_pago'],
                'total'          => $total,
                'numero_factura' => $numero,
                'fecha'          => now()->toDateString(),
                'id_cliente'     => null,
                'id_empleado'    => auth()->id(),
            ]);

            // Crear venta
            $venta = Venta::create([
                'fecha'       => now(),
                'total'       => $total,
                'id_factura'  => $factura->id_factura,
                'id_empleado' => auth()->id(),
                'impuesto'    => $impuesto,
            ]);

            // Detalles + descontar stock en tiempo real
            foreach ($datos['items'] as $item) {
                $producto = $productos[$item['id']];
                DetalleVenta::create([
                    'id_venta'        => $venta->id_venta,
                    'id_producto'     => $producto->id_producto,
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $producto->precio,
                    'subtotal'        => round($producto->precio * $item['cantidad'], 2),
                ]);
                $producto->decrement('stock', $item['cantidad']);
            }

            DB::commit();

            $vuelto = $datos['metodo_pago'] === 'Efectivo'
                ? round((float) $datos['efectivo'] - $total, 2) : 0;

            return response()->json([
                'ok' => true,
                'id_venta'       => $venta->id_venta,
                'numero_factura' => $numero,
                'subtotal'       => round($subtotal, 2),
                'impuesto'       => $impuesto,
                'total'          => $total,
                'vuelto'         => $vuelto,
                'tipo'           => $datos['tipo_documento'],
            ]);
        } catch (\Exception $e) {
            // Si algo falla, se revierte TODO (no se corrompe el inventario)
            DB::rollBack();
            return response()->json(['ok' => false, 'mensaje' => $e->getMessage()], 422);
        }
    }

    // Comprobante imprimible: ticket (recibo pequeño) o factura (página completa)
    public function comprobante(Request $request, $id)
    {
        $venta = Venta::with(['detalles.producto', 'factura', 'empleado'])->findOrFail($id);
        $tipo = $request->query('tipo', 'Ticket');
        $vista = $tipo === 'Factura' ? 'pos.factura' : 'pos.ticket';
        return view($vista, compact('venta'));
    }
}
