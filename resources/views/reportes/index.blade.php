@extends('layouts.app')
@section('titulo', 'Reportes y Análisis')

@php
    $topLabels = $top->map(fn($t) => $t->producto->nombre ?? 'N/A')->values();
    $topData = $top->map(fn($t) => (int) $t->total_cant)->values();
    $q = array_filter(['desde' => $desde, 'hasta' => $hasta]);
@endphp

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Reportes y Análisis</h2>
        <p class="text-sm text-slate-500">Estadísticas y métricas del negocio</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('reportes.excel', $q) }}" class="flex items-center gap-2 bg-teal-500 hover:bg-teal-600 text-white px-3 py-2 rounded-lg text-sm font-medium"><i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Exportar Excel</a>
        <a href="{{ route('reportes.pdf', $q) }}" target="_blank" class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-medium"><i data-lucide="file-text" class="w-4 h-4"></i> Exportar PDF</a>
    </div>
</div>

{{-- Filtro de fechas --}}
<form method="GET" class="bg-white rounded-xl border border-slate-200 p-4 mb-5 flex flex-wrap items-end gap-3">
    <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">Fecha desde</label>
        <input type="date" name="desde" value="{{ $desde }}" class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">Fecha hasta</label>
        <input type="date" name="hasta" value="{{ $hasta }}" class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
    </div>
    <button type="submit" class="bg-vortex-green hover:bg-vortex-green2 text-white px-4 py-2 rounded-lg text-sm font-medium">Filtrar</button>
    @if($desde || $hasta)<a href="{{ route('reportes.index') }}" class="px-4 py-2 rounded-lg text-sm text-slate-500 hover:bg-slate-100">Limpiar</a>@endif
</form>

{{-- Tarjetas --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    <div class="bg-white rounded-xl border border-slate-200 p-5"><p class="text-sm text-slate-500">Ventas Totales</p><p class="text-2xl font-bold text-vortex-green2">${{ number_format($ventasTotales, 2) }}</p></div>
    <div class="bg-white rounded-xl border border-slate-200 p-5"><p class="text-sm text-slate-500">Productos Vendidos</p><p class="text-2xl font-bold text-slate-800">{{ number_format($productosVendidos) }}</p></div>
    <div class="bg-white rounded-xl border border-slate-200 p-5"><p class="text-sm text-slate-500">Clientes Registrados</p><p class="text-2xl font-bold text-slate-800">{{ number_format($clientesActivos) }}</p></div>
    <div class="bg-white rounded-xl border border-slate-200 p-5"><p class="text-sm text-slate-500">Ticket Promedio</p><p class="text-2xl font-bold text-slate-800">${{ number_format($ticketPromedio, 2) }}</p></div>
</div>

{{-- Gráficas --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    <div class="bg-white rounded-xl border border-slate-200 p-5"><h3 class="font-semibold text-slate-700 mb-3">Ventas por Día de la Semana</h3><canvas id="chartDia" height="140"></canvas></div>
    <div class="bg-white rounded-xl border border-slate-200 p-5"><h3 class="font-semibold text-slate-700 mb-3">Ventas Mensuales</h3><canvas id="chartMes" height="140"></canvas></div>
    <div class="bg-white rounded-xl border border-slate-200 p-5"><h3 class="font-semibold text-slate-700 mb-3">Métodos de Pago</h3><canvas id="chartPago" height="140"></canvas></div>
    <div class="bg-white rounded-xl border border-slate-200 p-5"><h3 class="font-semibold text-slate-700 mb-3">Productos Más Vendidos</h3><canvas id="chartTop" height="140"></canvas></div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const VERDE = '#22c55e', AZUL = '#3b82f6', TEAL = '#14b8a6';
    const sinLeyenda = { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } };

    new Chart(document.getElementById('chartDia'), {
        type: 'bar',
        data: { labels: ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'], datasets: [{ data: @json($porDia), backgroundColor: VERDE, borderRadius: 6 }] },
        options: sinLeyenda
    });
    new Chart(document.getElementById('chartMes'), {
        type: 'bar',
        data: { labels: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'], datasets: [{ data: @json($porMes), backgroundColor: AZUL, borderRadius: 6 }] },
        options: sinLeyenda
    });
    new Chart(document.getElementById('chartPago'), {
        type: 'doughnut',
        data: { labels: ['Efectivo','Tarjeta'], datasets: [{ data: [{{ $metodos['Efectivo'] ?? 0 }}, {{ $metodos['Tarjeta'] ?? 0 }}], backgroundColor: [VERDE, AZUL] }] },
        options: { plugins: { legend: { position: 'bottom' } } }
    });
    new Chart(document.getElementById('chartTop'), {
        type: 'bar',
        data: { labels: @json($topLabels), datasets: [{ data: @json($topData), backgroundColor: TEAL, borderRadius: 6 }] },
        options: { indexAxis: 'y', ...sinLeyenda }
    });
</script>
@endsection
