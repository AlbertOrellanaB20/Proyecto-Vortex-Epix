@php
    $f = $venta->factura;
    $subtotal = round($venta->detalles->sum('subtotal'), 2);
    $iva = round($subtotal * 0.13, 2);
    $total = round($subtotal + $iva, 2);
    $numero = str_pad($f->numero_factura ?? $venta->id_venta, 6, '0', STR_PAD_LEFT);
    $logoPath = public_path('img/logomercado.png');
    $logo = (extension_loaded('gd') && file_exists($logoPath)) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: 'DejaVu Sans', Arial, sans-serif; color:#1f2937; font-size:12px; }
    .top { width:100%; border-bottom:3px solid #16a34a; padding-bottom:12px; }
    .top td { vertical-align:top; }
    .empresa h1 { font-size:20px; color:#0f172a; }
    .empresa p { font-size:11px; color:#64748b; line-height:1.5; }
    .docbox { border:2px solid #16a34a; border-radius:8px; padding:8px 12px; text-align:center; }
    .docbox .tipo { font-size:11px; font-weight:bold; color:#16a34a; }
    .docbox .num { font-size:17px; font-weight:bold; margin:3px 0; }
    .docbox .fecha { font-size:11px; color:#64748b; }
    .cliente { width:100%; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; margin:16px 0; }
    .cliente td { padding:10px 14px; font-size:12px; }
    .cliente .et { color:#94a3b8; font-size:10px; text-transform:uppercase; }
    table.items { width:100%; border-collapse:collapse; margin-top:6px; font-size:12px; }
    table.items thead th { background:#0f172a; color:#fff; padding:8px; text-align:left; }
    table.items th.r, table.items td.r { text-align:right; }
    table.items th.c, table.items td.c { text-align:center; }
    table.items tbody td { padding:7px 8px; border-bottom:1px solid #e5e7eb; }
    .totales { width:100%; border-collapse:collapse; }
    .totales td { padding:6px 10px; font-size:13px; }
    .totales .tot td { background:#16a34a; color:#fff; font-weight:bold; font-size:15px; }
    .firma { margin-top:36px; text-align:center; font-size:11px; color:#64748b; }
    .firma .l { border-top:1px solid #94a3b8; width:200px; margin:0 auto 4px; }
    .pie { margin-top:24px; font-size:11px; color:#64748b; border-top:1px dashed #cbd5e1; padding-top:12px; text-align:center; }
</style>
</head>
<body>
    <table class="top">
        <tr>
            <td style="width:62%;">
                <table>
                    <tr>
                        <td style="width:72px;">
                            @if($logo)<img src="{{ $logo }}" style="width:60px; height:60px;">@endif
                        </td>
                        <td class="empresa">
                            <h1>Supermercado</h1>
                            <p>Sistema de Gestión de Supermercado<br>
                            Acajutla, Sonsonate, El Salvador<br>
                            Tel: 7000-0000 · NIT: 0000-000000-000-0</p>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:38%;">
                <div class="docbox">
                    <div class="tipo">FACTURA DE CONSUMIDOR FINAL</div>
                    <div class="num">N° {{ $numero }}</div>
                    <div class="fecha">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }} · {{ \Carbon\Carbon::parse($venta->fecha)->format('H:i') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <table class="cliente">
        <tr>
            <td style="width:40%;"><div class="et">Cliente</div>{{ $f->cliente ? $f->cliente->nombre . ' ' . $f->cliente->apellido : 'Consumidor Final' }}</td>
            <td style="width:35%;"><div class="et">Atendido por</div>{{ $venta->empleado->nombre ?? '' }} {{ $venta->empleado->apellido ?? '' }}</td>
            <td style="width:25%;"><div class="et">Forma de pago</div>{{ $f->metodo_pago ?? '—' }}</td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th class="c" style="width:35px;">#</th>
                <th class="c" style="width:50px;">Cant.</th>
                <th>Descripción</th>
                <th class="r" style="width:100px;">P. Unitario</th>
                <th class="r" style="width:100px;">Total</th>
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

    {{-- Totales alineados a la derecha (tabla envoltura para que DomPDF lo respete) --}}
    <table style="width:100%; margin-top:14px;">
        <tr>
            <td></td>
            <td style="width:280px;">
                <table class="totales">
                    <tr><td>Subtotal</td><td class="r">${{ number_format($subtotal, 2) }}</td></tr>
                    <tr><td>IVA (13%)</td><td class="r">${{ number_format($iva, 2) }}</td></tr>
                    <tr class="tot"><td>TOTAL A PAGAR</td><td class="r">${{ number_format($total, 2) }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="firma">
        <div class="l"></div>
        Firma y sello
    </div>

    <div class="pie">
        <strong>PROYECTO ESTUDIANTIL — Documento no válido como factura real.</strong><br>
        Generado por el sistema del Supermercado · Instituto Nacional de Acajutla, Módulo 3.1
    </div>
</body>
</html>
