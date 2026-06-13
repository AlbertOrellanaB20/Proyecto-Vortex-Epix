@php
    $f = $venta->factura;
    // Se recalcula desde los productos para que coincida con la página del POS
    $subtotal = round($venta->detalles->sum('subtotal'), 2);
    $iva = round($subtotal * 0.13, 2);
    $total = round($subtotal + $iva, 2);
@endphp
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    * { margin: 0; padding: 0; }
    body { font-family: 'DejaVu Sans Mono', monospace; font-size: 9px; color: #000; line-height: 1.4; }
    .center { text-align: center; }
    .bold { font-weight: bold; }
    .big { font-size: 12px; }
    .linea { border-bottom: 1px dashed #555; margin: 4px 0; font-size: 0; line-height: 0; }
    table { width: 100%; border-collapse: collapse; }
    td { vertical-align: top; }
    .right { text-align: right; }
    .gris { color: #555; }
</style>
</head>
<body>
    <div class="center bold big">VORTEX EPIX</div>
    <div class="center">Sistema de Gestión de Supermercado</div>
    <div class="center">Tel: 7000-0000 · El Salvador</div>
    <div class="linea"></div>
    <div class="center bold">TICKET DE VENTA</div>
    <div class="center">N° {{ $f->numero_factura ?? $venta->id_venta }}</div>
    <div class="linea"></div>
    <div>Fecha: {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}</div>
    <div>Cajero: {{ $venta->empleado->nombre ?? '' }} {{ $venta->empleado->apellido ?? '' }}</div>
    <div>Pago: {{ $f->metodo_pago ?? '-' }}</div>
    <div class="linea"></div>
    <table>
        <tr class="bold">
            <td width="14%">Cant</td>
            <td width="56%">Producto</td>
            <td width="30%" class="right">Total</td>
        </tr>
        @foreach ($venta->detalles as $d)
        <tr>
            <td>{{ $d->cantidad }}</td>
            <td>{{ $d->producto->nombre ?? 'Producto' }}<br><span class="gris">${{ number_format($d->precio_unitario, 2) }} c/u</span></td>
            <td class="right">${{ number_format($d->subtotal, 2) }}</td>
        </tr>
        @endforeach
    </table>
    <div class="linea"></div>
    <table>
        <tr><td>Subtotal:</td><td class="right">${{ number_format($subtotal, 2) }}</td></tr>
        <tr><td>IVA (13%):</td><td class="right">${{ number_format($iva, 2) }}</td></tr>
        <tr class="bold big"><td>TOTAL:</td><td class="right">${{ number_format($total, 2) }}</td></tr>
    </table>
    <div class="linea"></div>
    <div class="center">¡Gracias por su compra!</div>
    <div class="center gris" style="font-size: 8px;">Vortex Epix · INA Módulo 3.1</div>
</body>
</html>
