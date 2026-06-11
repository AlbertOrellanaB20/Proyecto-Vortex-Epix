<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
<meta charset="UTF-8">
<style>
    table { border-collapse: collapse; font-family: Calibri, Arial, sans-serif; }
    td, th { border: 0.5pt solid #b0b0b0; padding: 5px 8px; font-size: 11pt; }
    .titulo { font-size: 18pt; font-weight: bold; color: #16a34a; border: none; }
    .sub { font-size: 12pt; font-weight: bold; color: #0f172a; border: none; }
    .meta { font-size: 10pt; color: #555; border: none; }
    th.cab { background-color: #0f172a; color: #ffffff; font-weight: bold; text-align: center; }
    .num { text-align: center; }
    .dinero { text-align: right; }
    .normal { background-color: #dcfce7; color: #166534; font-weight: bold; text-align: center; }
    .bajo { background-color: #fee2e2; color: #b91c1c; font-weight: bold; text-align: center; }
    .totfila { background-color: #f1f5f9; font-weight: bold; }
</style>
</head>
<body>
<table>
    <tr><td class="titulo" colspan="11">VORTEX EPIX</td></tr>
    <tr><td class="sub" colspan="11">Reporte de Inventario — Sistema de Gestión de Supermercado</td></tr>
    <tr><td class="meta" colspan="11">Generado: {{ $fecha }}</td></tr>
    <tr><td class="meta" colspan="11">Total de productos: {{ $productos->count() }}  ·  Stock total: {{ number_format($totalStock) }} unidades</td></tr>
    <tr><td colspan="11" style="border:none;">&nbsp;</td></tr>
    <tr>
        <th class="cab">Código de Barras</th>
        <th class="cab">Producto</th>
        <th class="cab">Categoría</th>
        <th class="cab">Marca</th>
        <th class="cab">Stock Actual</th>
        <th class="cab">Stock Mínimo</th>
        <th class="cab">Stock Máximo</th>
        <th class="cab">Estado</th>
        <th class="cab">Precio ($)</th>
        <th class="cab">Precio c/IVA ($)</th>
        <th class="cab">Vencimiento</th>
    </tr>
    @foreach ($productos as $p)
    @php $bajo = $p->stock <= ($p->stock_minimo ?? 10); @endphp
    <tr>
        <td>{{ $p->codigo_barras }}</td>
        <td>{{ $p->nombre }}</td>
        <td>{{ $p->categoria }}</td>
        <td>{{ $p->marca }}</td>
        <td class="num">{{ $p->stock }}</td>
        <td class="num">{{ $p->stock_minimo }}</td>
        <td class="num">{{ $p->stock_maximo }}</td>
        <td class="{{ $bajo ? 'bajo' : 'normal' }}">{{ $bajo ? 'BAJO' : 'NORMAL' }}</td>
        <td class="dinero">{{ number_format($p->precio, 2) }}</td>
        <td class="dinero">{{ number_format($p->precio_con_iva, 2) }}</td>
        <td class="num">{{ $p->fecha_vencimiento ? \Carbon\Carbon::parse($p->fecha_vencimiento)->format('d/m/Y') : '—' }}</td>
    </tr>
    @endforeach
    <tr class="totfila">
        <td colspan="4" style="text-align:right;">TOTALES</td>
        <td class="num">{{ $productos->sum('stock') }}</td>
        <td colspan="6"></td>
    </tr>
</table>
</body>
</html>
