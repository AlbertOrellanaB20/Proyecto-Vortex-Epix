@extends('layouts.app')
@section('titulo', 'Inicio')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-800">¡Bienvenido, {{ auth()->user()->nombre }} {{ auth()->user()->apellido }}!</h2>
    <p class="text-sm text-slate-500">Has iniciado sesión como <span class="text-vortex-green2 font-medium">{{ auth()->user()->cargo }}</span>. El login y el control de acceso (RBAC) funcionan correctamente.</p>
</div>

{{-- Accesos rápidos según el rol (RBAC) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @php $cargo = auth()->user()->cargo; @endphp

    @if (in_array($cargo, ['Administrador','Cajero','Supervisor']))
    <a href="{{ route('pos.index') }}" class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md hover:border-vortex-green/40 transition flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center"><i data-lucide="shopping-cart" class="w-6 h-6 text-vortex-green2"></i></div>
        <div><p class="font-semibold text-slate-700">Punto de Venta</p><p class="text-xs text-slate-400">Escanear y cobrar productos</p></div>
    </a>
    @endif

    @if (in_array($cargo, ['Administrador','Supervisor','Inventario']))
    <a href="{{ route('productos.index') }}" class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md hover:border-vortex-green/40 transition flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center"><i data-lucide="package" class="w-6 h-6 text-blue-500"></i></div>
        <div><p class="font-semibold text-slate-700">Productos</p><p class="text-xs text-slate-400">Gestionar el catálogo</p></div>
    </a>
    @endif

    @if (in_array($cargo, ['Administrador','Inventario']))
    <a href="{{ route('inventario.index') }}" class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md hover:border-vortex-green/40 transition flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center"><i data-lucide="boxes" class="w-6 h-6 text-amber-500"></i></div>
        <div><p class="font-semibold text-slate-700">Inventario</p><p class="text-xs text-slate-400">Stock y reabastecimiento</p></div>
    </a>
    @endif
</div>

<div class="mt-6 bg-white rounded-xl border border-slate-200 p-5 text-sm text-slate-500">
    <p class="flex items-center gap-2 text-slate-600 font-medium mb-1"><i data-lucide="info" class="w-4 h-4"></i> Nota</p>
    Este es un dashboard base. El módulo de Dashboard completo (estadísticas y gráficas) corresponde a Bryan Steve.
</div>
@endsection
