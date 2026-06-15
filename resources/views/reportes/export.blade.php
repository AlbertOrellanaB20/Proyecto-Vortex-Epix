<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel">
<head><meta charset="UTF-8">
<style>
    table { border-collapse: collapse; font-family: Calibri, Arial, sans-serif; }
    td, th { border: 0.5pt solid #b0b0b0; padding: 5px 8px; font-size: 11pt; }
    .titulo { font-size: 18pt; font-weight: bold; color: #16a34a; border: none; }
    .sub { font-size: 12pt; font-weight: bold; color: #0f172a; border: none; }
    .meta { font-size: 10pt; color: #555; border: none; }
    th.cab { background-color: #0f172a; color: #fff; font-weight: bold; text-align: center; }
    .num { text-align: center; } .dinero { text-align: right; }
    .resumen { background-color: #dcfce7; font-weight: bold; }
</style></head>
<body>
<table>
    <tr><td class="titulo" colspan="7">VORTEX EPIX</td></tr>
    <tr><td class="sub" colspan="7">Reporte de Ventas — Sistema de Gestión de Supermercado</td></tr>
    <tr><td class="meta" colspan="7">Generado: {{ now()->format('d/m/Y H:i') }} @if($desde || $hasta) · Rango: {{ $desde ?: 'inicio' }} a {{ $hasta ?: 'hoy' }} @endif</td></tr>
    <tr><td colspan="7" style="border:none;">&nbsp;</td></tr>

    <tr class="resumen"><td colspan="2">Ventas Totales</td><td class="dinero" colspan="5">${{ number_format($ventasTotales, 2) }}</td></tr>
    <tr class="resumen"><td colspan="2">Productos Vendidos</td><td class="num" colspan="5">{{ $productosVendidos }}</td></tr>
    <tr class="resumen"><td colspan="2">Ticket Promedio</td><td class="dinero" colspan="5">${{ number_format($ticketPromedio, 2) }}</td></tr>
    <tr class="resumen"><td colspan="2">Total Documentos</td><td class="num" colspan="5">{{ $ventas->count() }}</td></tr>
    <tr><td colspan="7" style="border:none;">&nbsp;</td></tr>

    <tr>
        <th class="cab">N° Documento</th><th class="cab">Fecha</th><th class="cab">Cajero</th>
        <th class="cab">Método</th><th class="cab">Subtotal</th><th class="cab">IVA</th><th class="cab">Total</th>
    </tr>
    @foreach ($ventas as $v)
    <tr>
        <td class="num">{{ $v->factura->numero_factura ?? $v->id_venta }}</td>
        <td>{{ \Carbon\Carbon::parse($v->fecha)->format('d/m/Y H:i') }}</td>
        <td>{{ $v->empleado->nombre ?? '' }} {{ $v->empleado->apellido ?? '' }}</td>
        <td>{{ $v->factura->metodo_pago ?? '-' }}</td>
        <td class="dinero">{{ number_format($v->total - $v->impuesto, 2) }}</td>
        <td class="dinero">{{ number_format($v->impuesto, 2) }}</td>
        <td class="dinero">{{ number_format($v->total, 2) }}</td>
    </tr>
    @endforeach
</table>
</body>
</html>
