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
<title>Factura N° {{ $f->numero_factura ?? $venta->id_venta }}</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { background:#e5e7eb; font-family:'Segoe UI', Arial, sans-serif; color:#1f2937; padding:24px; }
    .hoja { background:#fff; width:800px; max-width:100%; margin:0 auto; padding:40px; box-shadow:0 2px 12px rgba(0,0,0,.08); }
    .top { display:flex; justify-content:space-between; align-items:flex-start; border-bottom:3px solid #16a34a; padding-bottom:18px; }
    .empresa { display:flex; gap:12px; }
    .logo { width:54px; height:54px; background:#22c55e; color:#fff; border-radius:12px; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:22px; }
    .empresa h1 { font-size:22px; color:#0f172a; }
    .empresa p { font-size:12px; color:#64748b; line-height:1.5; }
    .docbox { border:2px solid #16a34a; border-radius:10px; padding:12px 18px; text-align:center; min-width:230px; }
    .docbox .tipo { font-size:13px; font-weight:bold; color:#16a34a; letter-spacing:.5px; }
    .docbox .num { font-size:20px; font-weight:bold; margin:4px 0; }
    .docbox .fecha { font-size:12px; color:#64748b; }
    .cliente { display:flex; justify-content:space-between; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:14px 18px; margin:22px 0; font-size:13px; }
    .cliente .et { color:#94a3b8; font-size:11px; text-transform:uppercase; }
    table { width:100%; border-collapse:collapse; margin-top:6px; font-size:13px; }
    thead th { background:#0f172a; color:#fff; padding:10px; text-align:left; font-weight:600; }
    thead th.r, tbody td.r { text-align:right; }
    thead th.c, tbody td.c { text-align:center; }
    tbody td { padding:9px 10px; border-bottom:1px solid #e5e7eb; }
    tbody tr:nth-child(even) { background:#f8fafc; }
    .totales { display:flex; justify-content:flex-end; margin-top:18px; }
    .totales table { width:300px; }
    .totales td { padding:7px 10px; font-size:14px; }
    .totales .tot { background:#16a34a; color:#fff; font-weight:bold; font-size:16px; }
    .pie { margin-top:30px; display:flex; justify-content:space-between; font-size:12px; color:#64748b; border-top:1px dashed #cbd5e1; padding-top:16px; }
    .firma { margin-top:40px; text-align:center; font-size:12px; color:#64748b; }
    .firma .l { border-top:1px solid #94a3b8; width:220px; margin:0 auto 4px; }
    .acciones { width:800px; max-width:100%; margin:18px auto 0; display:flex; gap:10px; }
    .btn { flex:0 0 auto; padding:10px 20px; border-radius:8px; font-size:14px; text-decoration:none; cursor:pointer; border:none; }
    .btn-print { background:#22c55e; color:#fff; }
    .btn-back { background:#fff; color:#475569; border:1px solid #cbd5e1; }
    @media print {
        @page { size: A4; margin: 14mm; }
        body { background:#fff; padding:0; }
        .hoja { box-shadow:none; width:100%; padding:0; }
        .no-print { display:none !important; }
    }
</style>
</head>
<body>
    <div class="hoja">
        <div class="top">
            <div class="empresa">
                <div class="logo">VE</div>
                <div>
                    <h1>Vortex Epix</h1>
                    <p>Sistema de Gestión de Supermercado<br>
                    Acajutla, Sonsonate, El Salvador<br>
                    Tel: 7000-0000 · NRC: 000000-0 · NIT: 0000-000000-000-0</p>
                </div>
            </div>
            <div class="docbox">
                <div class="tipo">FACTURA DE CONSUMIDOR FINAL</div>
                <div class="num">N° {{ str_pad($f->numero_factura ?? $venta->id_venta, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="fecha">Fecha: {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}<br>Hora: {{ \Carbon\Carbon::parse($venta->fecha)->format('H:i') }}</div>
            </div>
        </div>

        <div class="cliente">
            <div>
                <div class="et">Cliente</div>
                <div>Consumidor Final</div>
            </div>
            <div>
                <div class="et">Atendido por</div>
                <div>{{ $venta->empleado->nombre ?? '' }} {{ $venta->empleado->apellido ?? '' }}</div>
            </div>
            <div>
                <div class="et">Forma de pago</div>
                <div>{{ $f->metodo_pago ?? '—' }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="c" style="width:40px;">#</th>
                    <th class="c" style="width:60px;">Cant.</th>
                    <th>Descripción</th>
                    <th class="r" style="width:110px;">P. Unitario</th>
                    <th class="r" style="width:110px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($venta->detalles as $i => $d)
                <tr>
                    <td class="c">{{ $i + 1 }}</td>
                    <td class="c">{{ $d->cantidad }}</td>
                    <td>{{ $d->producto->nombre ?? 'Producto' }}</td>
                    <td class="r">${{ number_format($d->precio_unitario, 2) }}</td>
                    <td class="r">${{ number_format($d->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totales">
            <table>
                <tr><td>Subtotal</td><td class="r">${{ number_format($subtotal, 2) }}</td></tr>
                <tr><td>IVA (13%)</td><td class="r">${{ number_format($iva, 2) }}</td></tr>
                <tr class="tot"><td>TOTAL A PAGAR</td><td class="r">${{ number_format($total, 2) }}</td></tr>
            </table>
        </div>

        <div class="firma">
            <div class="l"></div>
            Firma y sello
        </div>

        <div class="pie">
            <span>Documento generado por el sistema Vortex Epix.</span>
            <span>Instituto Nacional de Acajutla · Módulo 3.1</span>
        </div>
    </div>

    <div class="acciones no-print">
        <button class="btn btn-print" onclick="window.print()">🖨 Imprimir factura</button>
        <a class="btn btn-back" href="{{ route('pos.index') }}">Volver al POS</a>
    </div>
</body>
</html>
