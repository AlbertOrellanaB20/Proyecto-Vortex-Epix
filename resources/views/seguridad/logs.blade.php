@extends('layouts.app')
@section('titulo', 'Bitácora del Sistema')

@php
    function colorMetodo($m) {
        return match($m) {
            'POST'   => 'bg-green-100 text-green-700',
            'PUT', 'PATCH' => 'bg-blue-100 text-blue-700',
            'DELETE' => 'bg-red-100 text-red-700',
            default  => 'bg-slate-100 text-slate-600',
        };
    }
@endphp

@section('content')
<div class="mb-5">
    <h2 class="text-2xl font-bold text-slate-800">Bitácora del Sistema</h2>
    <p class="text-sm text-slate-500">Registro de actividad: quién hizo qué y cuándo (solo Administrador)</p>
</div>

{{-- Estadísticas --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between">
        <div><p class="text-sm text-slate-500">Total de Registros</p><p class="text-3xl font-bold text-slate-800">{{ number_format($total) }}</p></div>
        <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center"><i data-lucide="scroll-text" class="w-6 h-6 text-violet-500"></i></div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between">
        <div><p class="text-sm text-slate-500">Actividad de Hoy</p><p class="text-3xl font-bold text-vortex-green2">{{ number_format($hoy) }}</p></div>
        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center"><i data-lucide="activity" class="w-6 h-6 text-vortex-green2"></i></div>
    </div>
</div>

{{-- Buscador --}}
<form method="GET" class="mb-5">
    <div class="relative">
        <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
        <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar por usuario, acción, módulo o IP..."
               class="w-full bg-white border border-slate-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
    </div>
</form>

{{-- Tabla --}}
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100"><h3 class="font-semibold text-slate-700">Registros recientes ({{ $logs->count() }})</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium">Fecha y hora</th>
                    <th class="px-4 py-3 font-medium">Usuario</th>
                    <th class="px-4 py-3 font-medium">Acción</th>
                    <th class="px-4 py-3 font-medium">Módulo</th>
                    <th class="px-4 py-3 font-medium text-center">Método</th>
                    <th class="px-4 py-3 font-medium">Ruta</th>
                    <th class="px-4 py-3 font-medium">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($logs as $l)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 text-slate-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($l->fecha)->format('d/m/Y H:i:s') }}</td>
                    <td class="px-4 py-3 font-medium text-slate-700">{{ $l->usuario ?: '—' }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $l->accion }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $l->modulo }}</td>
                    <td class="px-4 py-3 text-center"><span class="inline-block px-2 py-0.5 rounded text-xs font-semibold {{ colorMetodo($l->metodo) }}">{{ $l->metodo }}</span></td>
                    <td class="px-4 py-3 font-mono text-xs text-slate-400">{{ $l->ruta }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-slate-400">{{ $l->ip }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-slate-400">No hay registros todavía. La actividad se irá guardando conforme se use el sistema.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
