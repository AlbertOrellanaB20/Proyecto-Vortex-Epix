<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Ventas · Supermercado</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
    @media print { @page { size: A4; margin: 14mm; } .no-print { display:none; } body { background:#fff; } }
    body { font-family: 'Segoe UI', Arial, sans-serif; }
</style>
</head>
<body class="bg-slate-100 py-8">
<div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-8">
    <div class="flex items-center justify-between border-b-2 border-green-500 pb-4 mb-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-green-500 flex items-center justify-center text-white font-bold text-xl">VE</div>
            <div><h1 class="text-xl font-bold text-slate-800">Supermercado</h1><p class="text-xs text-slate-500">Reporte de Ventas</p></div>
        </div>
        <div class="text-right text-xs text-slate-500">
            <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>
            @if($desde || $hasta)<p>Rango: {{ $desde ?: 'inicio' }} a {{ $hasta ?: 'hoy' }}</p>@endif
        </div>
    </div>

    <div class="grid grid-cols-4 gap-3 mb-6">
        <div class="bg-slate-50 rounded-lg p-3 text-center"><p class="text-xs text-slate-500">Ventas Totales</p><p class="text-lg font-bold text-green-600">${{ number_format($ventasTotales, 2) }}</p></div>
        <div class="bg-slate-50 rounded-lg p-3 text-center"><p class="text-xs text-slate-500">Productos</p><p class="text-lg font-bold">{{ number_format($productosVendidos) }}</p></div>
        <div class="bg-slate-50 rounded-lg p-3 text-center"><p class="text-xs text-slate-500">Documentos</p><p class="text-lg font-bold">{{ $ventas->count() }}</p></div>
        <div class="bg-slate-50 rounded-lg p-3 text-center"><p class="text-xs text-slate-500">Ticket Prom.</p><p class="text-lg font-bold">${{ number_format($ticketPromedio, 2) }}</p></div>
    </div>

    <h2 class="font-semibold text-slate-700 mb-2">Productos más vendidos</h2>
    <table class="w-full text-sm mb-6 border border-slate-200">
        <thead class="bg-slate-800 text-white text-left"><tr><th class="px-3 py-2">Producto</th><th class="px-3 py-2 text-right">Cantidad vendida</th></tr></thead>
        <tbody>
            @forelse ($top as $t)
            <tr class="border-t border-slate-100"><td class="px-3 py-2">{{ $t->producto->nombre ?? 'N/A' }}</td><td class="px-3 py-2 text-right font-semibold">{{ $t->total_cant }}</td></tr>
            @empty
            <tr><td colspan="2" class="px-3 py-4 text-center text-slate-400">Sin datos.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2 class="font-semibold text-slate-700 mb-2">Detalle de documentos</h2>
    <table class="w-full text-xs border border-slate-200">
        <thead class="bg-slate-100 text-slate-600 text-left"><tr><th class="px-2 py-2">N°</th><th class="px-2 py-2">Fecha</th><th class="px-2 py-2">Método</th><th class="px-2 py-2 text-right">Total</th></tr></thead>
        <tbody>
            @foreach ($ventas as $v)
            <tr class="border-t border-slate-100"><td class="px-2 py-1.5">{{ $v->factura->numero_factura ?? $v->id_venta }}</td><td class="px-2 py-1.5">{{ \Carbon\Carbon::parse($v->fecha)->format('d/m/Y') }}</td><td class="px-2 py-1.5">{{ $v->factura->metodo_pago ?? '-' }}</td><td class="px-2 py-1.5 text-right">${{ number_format($v->total, 2) }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="no-print mt-6 flex gap-2">
        <button onclick="window.print()" class="bg-green-500 hover:bg-green-600 text-white rounded-lg px-5 py-2 text-sm">🖨 Imprimir / Guardar PDF</button>
        <a href="{{ route('reportes.index') }}" class="border border-slate-200 text-slate-600 rounded-lg px-5 py-2 text-sm">Volver</a>
    </div>
</div>
</body>
</html>
