@extends('layouts.app')
@section('titulo', 'Corte de Caja')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="bg-vortex-navy text-white px-6 py-5 text-center">
            <i data-lucide="calculator" class="w-10 h-10 mx-auto mb-2"></i>
            <h1 class="text-xl font-bold">Corte de Caja</h1>
            <p class="text-sm text-slate-300">{{ $cajero->nombre }} {{ $cajero->apellido }} · {{ $cajero->cargo }}</p>
            <p class="text-xs text-slate-400 mt-1">Turno desde {{ \Carbon\Carbon::parse($inicio)->locale('es')->isoFormat('D MMM, h:mm a') }}</p>
        </div>

        <div class="p-6 space-y-4">
            {{-- Resumen --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-slate-50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-slate-800">{{ $cantidad }}</p>
                    <p class="text-xs text-slate-500">Ventas del turno</p>
                </div>
                <div class="bg-green-50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-vortex-green2">${{ number_format($totalVendido, 2) }}</p>
                    <p class="text-xs text-slate-500">Total vendido</p>
                </div>
            </div>

            {{-- Desglose por método --}}
            <div class="border border-slate-100 rounded-xl divide-y divide-slate-100 text-sm">
                <div class="flex justify-between px-4 py-2.5"><span class="text-slate-500 flex items-center gap-2"><i data-lucide="banknote" class="w-4 h-4"></i> Ventas en efectivo</span><span class="font-semibold text-slate-700">${{ number_format($efectivo, 2) }}</span></div>
                <div class="flex justify-between px-4 py-2.5"><span class="text-slate-500 flex items-center gap-2"><i data-lucide="credit-card" class="w-4 h-4"></i> Ventas con tarjeta</span><span class="font-semibold text-slate-700">${{ number_format($tarjeta, 2) }}</span></div>
            </div>

            {{-- Caja física --}}
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm space-y-2">
                <div class="flex justify-between"><span class="text-slate-600">Fondo de cambio inicial</span><span class="font-semibold text-slate-700">${{ number_format($fondo, 2) }}</span></div>
                <div class="flex justify-between"><span class="text-slate-600">+ Ventas en efectivo</span><span class="font-semibold text-slate-700">${{ number_format($efectivo, 2) }}</span></div>
                <div class="flex justify-between border-t border-amber-200 pt-2"><span class="text-slate-700 font-medium">= Efectivo en caja ahora</span><span class="font-bold text-slate-800">${{ number_format($efectivoEnCaja, 2) }}</span></div>
            </div>

            {{-- Ganado sin el fondo --}}
            <div class="bg-vortex-green text-white rounded-xl p-4 text-center">
                <p class="text-xs opacity-90">Quitando el fondo de ${{ number_format($fondo, 2) }}, lo ganado en efectivo es:</p>
                <p class="text-3xl font-extrabold mt-1">${{ number_format($ganadoEfectivo, 2) }}</p>
            </div>

            {{-- Botones --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('pos.index') }}" class="flex-1 border border-slate-200 text-slate-600 rounded-lg py-2.5 text-sm text-center hover:bg-slate-50">Volver</a>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white rounded-lg py-2.5 text-sm font-medium flex items-center justify-center gap-2">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Confirmar y cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
