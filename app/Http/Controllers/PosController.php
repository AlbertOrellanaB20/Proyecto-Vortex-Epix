<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use App\Models\Factura;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('nombre')->get();

        // Productos para la cuadrícula del POS (con precio ya descontado si están por vencer)
        $productos = Producto::orderBy('nombre')->get()->map(function ($p) {
            return [
                'id'              => $p->id_producto,
                'nombre'          => $p->nombre,
                'precio'          => $p->precioVigente(),
                'precio_original' => round((float) $p->precio, 2),
                'descuento'       => $p->porcentajeDescuento(),
                'stock'           => (int) $p->stock,
                'imagen'          => $p->imagen,
                'categoria'       => $p->categoria,
            ];
        })->values();

        return view('pos.index', compact('clientes', 'productos'));
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
                'id'              => $producto->id_producto,
                'nombre'          => $producto->nombre,
                'precio'          => $producto->precioVigente(),
                'precio_original' => round((float) $producto->precio, 2),
                'descuento'       => $producto->porcentajeDescuento(),
                'stock'           => (int) $producto->stock,
                'imagen'          => $producto->imagen,
                'categoria'       => $producto->categoria,
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
            'id_cliente'       => ['nullable', 'exists:clientes,id_cliente'],
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
                $subtotal += $producto->precioVigente() * $item['cantidad'];
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
                'id_cliente'     => $datos['id_cliente'] ?? null,
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
                    'precio_unitario' => $producto->precioVigente(),
                    'subtotal'        => round($producto->precioVigente() * $item['cantidad'], 2),
                ]);
                $producto->decrement('stock', $item['cantidad']);
            }

            // Acumular puntos al cliente (si se selecciono uno)
            $puntosGanados = 0;
            $clienteNombre = 'Consumidor Final';
            if (!empty($datos['id_cliente'])) {
                $cliente = Cliente::find($datos['id_cliente']);
                if ($cliente) {
                    $puntosGanados = Cliente::puntosPorCompra($total);
                    $cliente->puntos += $puntosGanados;
                    $cliente->nivel_fidelidad = Cliente::nivelPorPuntos($cliente->puntos);
                    $cliente->registrarMovimientoPuntos();
                    $cliente->save();
                    $clienteNombre = $cliente->nombre . ' ' . $cliente->apellido;
                }
            }

            DB::commit();

            // Enviar la factura por correo. Devuelve un mensaje de estado que se
            // muestra en pantalla (así SABES si se envió o por qué no se envió).
            $correoEstado = $this->enviarFacturaCorreo($datos, $venta);

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
                'cliente'        => $clienteNombre,
                'puntos_ganados' => $puntosGanados,
                'correo'         => $correoEstado,
            ]);
        } catch (\Exception $e) {
            // Si algo falla, se revierte TODO (no se corrompe el inventario)
            DB::rollBack();
            return response()->json(['ok' => false, 'mensaje' => $e->getMessage()], 422);
        }
    }

    // Envía la factura por correo y devuelve un mensaje de estado para mostrarlo en pantalla.
    private function enviarFacturaCorreo(array $datos, Venta $venta): string
    {
        if ($datos['tipo_documento'] !== 'Factura') {
            return ''; // No es factura: no se envía correo.
        }
        if (empty($datos['id_cliente'])) {
            return 'No se seleccionó un cliente de la lista, por eso no se envió la factura por correo.';
        }
        $cliente = Cliente::find($datos['id_cliente']);
        if (!$cliente || empty($cliente->correo)) {
            return 'El cliente seleccionado no tiene un correo registrado.';
        }
        try {
            \Illuminate\Support\Facades\Mail::to($cliente->correo)->send(new \App\Mail\FacturaMail($venta));
            return 'Factura enviada al correo ' . $cliente->correo;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Correo de factura no enviado: ' . $e->getMessage());
            return 'No se pudo enviar el correo (' . $e->getMessage() . ')';
        }
    }

    // Comprobante imprimible: ticket (PDF del tamaño del recibo) o factura (página completa)
    public function comprobante(Request $request, $id)
    {
        $venta = Venta::with(['detalles.producto', 'factura.cliente', 'empleado'])->findOrFail($id);
        $tipo = $request->query('tipo', 'Ticket');

        if ($tipo === 'Factura') {
            return view('pos.factura', compact('venta'));
        }

        // TICKET: si DomPDF está instalado, se genera un PDF del tamaño EXACTO del recibo (80 mm de ancho)
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $mm = 2.83465; // 1 mm = 2.83465 puntos
            $altoMm = 100 + ($venta->detalles->count() * 12); // el alto se ajusta a la cantidad de productos
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pos.ticket_pdf', compact('venta'))
                ->setPaper([0, 0, 80 * $mm, $altoMm * $mm]);
            $numero = $venta->factura->numero_factura ?? $venta->id_venta;
            return $pdf->stream("ticket-{$numero}.pdf");
        }

        // Respaldo: si aún no instalan DomPDF, se muestra el ticket en HTML
        return view('pos.ticket', compact('venta'));
    }

    // ============================================================
    //  PRUEBA DE CORREO (diagnóstico). Visita en el navegador:
    //   /probar-correo?email=TUCORREO@gmail.com            -> correo simple
    //   /probar-correo?email=TUCORREO@gmail.com&venta=53   -> envía la factura de la venta 53
    //  Muestra el ERROR REAL si algo falla. Puedes borrar este
    //  método y su ruta cuando ya funcione.
    // ============================================================
    public function probarCorreo(Request $request)
    {
        $email = $request->query('email');
        if (!$email) {
            return 'Agrega ?email=TUCORREO@gmail.com al final de la URL. (Opcional: &venta=ID para enviar una factura real.)';
        }
        try {
            $ventaId = $request->query('venta');
            if ($ventaId) {
                $venta = Venta::findOrFail($ventaId);
                \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\FacturaMail($venta));
                return '✅ FACTURA de la venta ' . e($ventaId) . ' enviada a ' . e($email) . '. Revisa tu bandeja de entrada y la carpeta de SPAM.';
            }
            \Illuminate\Support\Facades\Mail::raw('Correo de prueba del sistema Supermercado. Si lees esto, el correo SI funciona.', function ($m) use ($email) {
                $m->to($email)->subject('Prueba de correo - Supermercado');
            });
            return '✅ Correo de prueba ENVIADO a ' . e($email) . '. Revisa tu bandeja de entrada y la carpeta de SPAM. Si llegó, las facturas también se enviarán.';
        } catch (\Throwable $e) {
            return '<h3 style="font-family:sans-serif;">❌ ERROR al enviar el correo:</h3>'
                 . '<pre style="white-space:pre-wrap;color:#b91c1c;font-size:13px;">' . e($e->getMessage()) . '</pre>'
                 . '<p style="font-family:sans-serif;">Revisa: (1) el archivo .env con los datos MAIL_*, (2) que corriste <b>php artisan config:clear</b>, (3) que la clave de aplicación de Gmail sea la correcta.</p>';
        }
    }
}
