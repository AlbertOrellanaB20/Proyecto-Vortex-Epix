@php
    $f = $venta->factura;
    // Se recalcula desde los productos para que SIEMPRE coincida con la página del POS
    $subtotal = round($venta->detalles->sum('subtotal'), 2);
    $iva = round($subtotal * 0.13, 2);
    $total = round($subtotal + $iva, 2);
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ticket #{{ $f->numero_factura ?? $venta->id_venta }}</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { background:#e5e7eb; font-family:'Courier New', monospace; padding:20px 0; display:flex; flex-direction:column; align-items:center; }
    .recibo { background:#fff; width:280px; padding:14px 14px; color:#111; font-size:11.5px; line-height:1.45; box-shadow:0 1px 6px rgba(0,0,0,.12); }
    .center { text-align:center; }
    .bold { font-weight:bold; }
    .linea { border-top:1px dashed #9ca3af; margin:7px 0; }
    .fila { display:flex; justify-content:space-between; }
    .logo { width:30px; height:30px; background:#22c55e; color:#fff; border-radius:7px; display:inline-flex; align-items:center; justify-content:center; font-weight:bold; font-size:13px; }
    table { width:100%; border-collapse:collapse; font-size:11px; }
    td { padding:1px 0; vertical-align:top; }
    .acciones { margin-top:16px; display:flex; gap:8px; width:280px; }
    .btn { flex:1; text-align:center; padding:9px; border-radius:8px; font-family:system-ui, sans-serif; font-size:13px; text-decoration:none; cursor:pointer; border:none; }
    .btn-print { background:#22c55e; color:#fff; }
    .btn-back { background:#fff; color:#475569; border:1px solid #cbd5e1; }

    /* AL IMPRIMIR: papel angosto tipo recibo (80 mm), sin fondo ni botones */
    @media print {
        @page { size: 80mm auto; margin: 0; }
        html, body { width: 80mm; background:#fff; padding:0; margin:0; display:block; }
        .recibo { width: 80mm; box-shadow:none; padding: 4mm 4mm; font-size:12px; }
        .acciones, .no-print { display:none !important; }
    }
</style>
</head>
<body>
    <div class="recibo">
        <div class="center">
            <div class="logo">VE</div>
            <div class="bold" style="font-size:14px; margin-top:4px;">VORTEX EPIX</div>
            <div>Sistema de Gestión de Supermercado</div>
            <div>Tel: 7000-0000 · El Salvador</div>
        </div>
        <div class="linea"></div>
        <div class="center bold">TICKET DE VENTA</div>
        <div class="center">N° {{ $f->numero_factura ?? $venta->id_venta }}</div>
        <div class="linea"></div>
        <div>Fecha: {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}</div>
        <div>Cajero: {{ $venta->empleado->nombre ?? '' }} {{ $venta->empleado->apellido ?? '' }}</div>
        <div>Pago: {{ $f->metodo_pago ?? '—' }}</div>
        <div class="linea"></div>
        <table>
            <tr class="bold"><td>Cant</td><td>Producto</td><td style="text-align:right;">Total</td></tr>
            @foreach ($venta->detalles as $d)
            <tr>
                <td>{{ $d->cantidad }}</td>
                <td>{{ $d->producto->nombre ?? 'Producto' }}<br><span style="color:#6b7280;">${{ number_format($d->precio_unitario,2) }} c/u</span></td>
                <td style="text-align:right;">${{ number_format($d->subtotal,2) }}</td>
            </tr>
            @endforeach
        </table>
        <div class="linea"></div>
        <div class="fila"><span>Subtotal:</span><span>${{ number_format($subtotal,2) }}</span></div>
        <div class="fila"><span>IVA (13%):</span><span>${{ number_format($iva,2) }}</span></div>
        <div class="fila bold" style="font-size:14px; margin-top:3px;"><span>TOTAL:</span><span>${{ number_format($total,2) }}</span></div>
        <div class="linea"></div>
        <div class="center">¡Gracias por su compra!</div>
        <div class="center" style="font-size:10px; color:#6b7280; margin-top:4px;">Vortex Epix · INA Módulo 3.1</div>
    </div>

    <div class="acciones no-print">
        <button class="btn btn-print" onclick="window.print()">🖨 Imprimir</button>
        <a class="btn btn-back" href="{{ route('pos.index') }}">Volver al POS</a>
    </div>
</body>
</html>
