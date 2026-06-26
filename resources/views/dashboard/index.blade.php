@extends('layouts.app')
@section('titulo', 'Dashboard Principal')

@php
    $topLabels = $top->map(fn($t) => $t->producto->nombre ?? 'N/A')->values();
    $topData = $top->map(fn($t) => (int) $t->total_cant)->values();
@endphp

@section('content')
<div class="mb-5">
    <h2 class="text-2xl font-bold text-slate-800">Dashboard Principal</h2>
    <p class="text-sm text-slate-500">Resumen general del supermercado</p>
</div>

@if($oculto)
<div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-xl p-4 mb-5 flex items-center gap-2 text-sm">
    <i data-lucide="lock" class="w-5 h-5 shrink-0"></i> Estás viendo este módulo como <strong>Administrador</strong>. Por confidencialidad, los datos de ventas e ingresos están ocultos.
</div>
@endif

{{-- Tarjetas --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between">
        <div><p class="text-sm text-slate-500">Ventas del Día</p><p class="text-2xl font-bold text-slate-800">{{ $oculto ? '••••••' : '$'.number_format($ventasHoy, 2) }}</p></div>
        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center"><i data-lucide="dollar-sign" class="w-6 h-6 text-vortex-green2"></i></div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between">
        <div><p class="text-sm text-slate-500">Productos Vendidos (hoy)</p><p class="text-2xl font-bold text-slate-800">{{ $oculto ? '••••' : number_format($productosHoy) }}</p></div>
        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center"><i data-lucide="package-check" class="w-6 h-6 text-blue-500"></i></div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between">
        <div><p class="text-sm text-slate-500">Stock Bajo</p><p class="text-2xl font-bold {{ $stockBajo > 0 ? 'text-red-500' : 'text-slate-800' }}">{{ $stockBajo }}</p></div>
        <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center"><i data-lucide="alert-triangle" class="w-6 h-6 text-red-500"></i></div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between">
        <div><p class="text-sm text-slate-500">Ingresos Totales</p><p class="text-2xl font-bold text-vortex-green2">{{ $oculto ? '••••••' : '$'.number_format($ingresosTotales, 2) }}</p></div>
        <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center"><i data-lucide="trending-up" class="w-6 h-6 text-emerald-500"></i></div>
    </div>
</div>

{{-- Gráficas --}}
@if($oculto)
<div class="bg-amber-50 border border-amber-200 rounded-xl p-8 mb-5 text-center text-amber-700">
    <i data-lucide="bar-chart-3" class="w-8 h-8 mx-auto mb-2"></i>
    <p class="font-medium">Gráficas de ventas ocultas</p>
    <p class="text-sm">Por confidencialidad, las gráficas de ventas no se muestran al Administrador.</p>
</div>
@else
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
    <div class="bg-white rounded-xl border border-slate-200 p-5"><h3 class="font-semibold text-slate-700 mb-3">Ventas de la Semana</h3><canvas id="chartSemana" height="150"></canvas></div>
    <div class="bg-white rounded-xl border border-slate-200 p-5"><h3 class="font-semibold text-slate-700 mb-3">Productos Más Vendidos</h3><canvas id="chartTop" height="150"></canvas></div>
</div>
@endif

{{-- Listas --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    {{-- Ventas recientes --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="font-semibold text-slate-700 mb-3">Ventas Recientes</h3>
        <div class="space-y-2">
            @forelse ($recientes as $v)
            <div class="flex items-center justify-between border border-slate-100 rounded-lg px-3 py-2.5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center"><i data-lucide="receipt" class="w-4 h-4 text-vortex-green2"></i></div>
                    <div><p class="text-sm font-medium text-slate-700">N° {{ $v->factura->numero_factura ?? $v->id_venta }}</p><p class="text-xs text-slate-400">{{ $v->detalles->sum('cantidad') }} productos · {{ \Carbon\Carbon::parse($v->fecha)->format('d/m H:i') }}</p></div>
                </div>
                <span class="font-semibold text-vortex-green2">{{ $oculto ? '••••' : '$'.number_format($v->total, 2) }}</span>
            </div>
            @empty
            <p class="text-sm text-slate-400 py-4 text-center">No hay ventas todavía.</p>
            @endforelse
        </div>
    </div>

    {{-- Stock bajo --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="font-semibold text-slate-700 mb-3 flex items-center gap-2"><i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i> Productos con Stock Bajo</h3>
        <div class="space-y-2">
            @forelse ($productosBajos as $p)
            <div class="flex items-center justify-between border border-red-100 bg-red-50/40 rounded-lg px-3 py-2.5">
                <div><p class="text-sm font-medium text-slate-700">{{ $p->nombre }}</p><p class="text-xs text-slate-400">Mínimo: {{ $p->stock_minimo }}</p></div>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600">{{ $p->stock }} u.</span>
            </div>
            @empty
            <p class="text-sm text-slate-400 py-4 text-center">Todo el stock está en orden. 👍</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
@unless($oculto)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const sinLeyenda = { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } };
    new Chart(document.getElementById('chartSemana'), {
        type: 'line',
        data: { labels: @json($labelsSemana), datasets: [{ data: @json($datosSemana), borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,.12)', fill: true, tension: .35, pointBackgroundColor: '#22c55e' }] },
        options: sinLeyenda
    });
    new Chart(document.getElementById('chartTop'), {
        type: 'bar',
        data: { labels: @json($topLabels), datasets: [{ data: @json($topData), backgroundColor: '#22c55e', borderRadius: 6 }] },
        options: { indexAxis: 'y', ...sinLeyenda }
    });
</script>
@endunless
@endsection
