@extends('layouts.app')
@section('titulo', 'Inicio')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Saludo --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center mb-6">
        <div class="w-20 h-20 rounded-full bg-vortex-green/10 flex items-center justify-center mx-auto mb-4">
            <span class="text-3xl font-bold text-vortex-green2">{{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-800">¡Bienvenido, {{ auth()->user()->nombre }}! 👋</h1>
        <p class="text-slate-500 mt-1">{{ auth()->user()->cargo }} · {{ \Carbon\Carbon::now('America/El_Salvador')->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
        <p class="text-slate-400 text-sm mt-3">Usa el menú de la izquierda para trabajar. ¡Que tengas un excelente día!</p>
    </div>

    {{-- Accesos rápidos según el rol --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @php $cargo = auth()->user()->cargo; @endphp

        {{-- POS: cajero (vende), admin e inventario (solo consulta) --}}
        @if (in_array($cargo, ['Cajero','Administrador','Inventario']))
        <a href="{{ route('pos.index') }}" class="bg-white rounded-xl border border-slate-200 p-5 hover:border-vortex-green hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-vortex-green/10 flex items-center justify-center shrink-0">
                <i data-lucide="shopping-cart" class="w-6 h-6 text-vortex-green2"></i>
            </div>
            <div>
                <p class="font-semibold text-slate-700">Ventas (POS)</p>
                <p class="text-xs text-slate-400">{{ $cargo === 'Cajero' ? 'Registrar una nueva venta' : 'Consultar el punto de venta' }}</p>
            </div>
        </a>
        @endif

        {{-- Clientes: solo cajero (dato confidencial) --}}
        @if ($cargo === 'Cajero')
        <a href="{{ route('clientes.index') }}" class="bg-white rounded-xl border border-slate-200 p-5 hover:border-vortex-green hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-vortex-green/10 flex items-center justify-center shrink-0">
                <i data-lucide="users" class="w-6 h-6 text-vortex-green2"></i>
            </div>
            <div>
                <p class="font-semibold text-slate-700">Clientes</p>
                <p class="text-xs text-slate-400">Agregar o consultar clientes</p>
            </div>
        </a>
        @endif

        {{-- Productos, Inventario, Proveedores: admin e inventario --}}
        @if (in_array($cargo, ['Administrador','Inventario']))
        <a href="{{ route('productos.index') }}" class="bg-white rounded-xl border border-slate-200 p-5 hover:border-vortex-green hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-vortex-green/10 flex items-center justify-center shrink-0">
                <i data-lucide="package" class="w-6 h-6 text-vortex-green2"></i>
            </div>
            <div>
                <p class="font-semibold text-slate-700">Productos</p>
                <p class="text-xs text-slate-400">Ver y administrar productos</p>
            </div>
        </a>
        <a href="{{ route('inventario.index') }}" class="bg-white rounded-xl border border-slate-200 p-5 hover:border-vortex-green hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-vortex-green/10 flex items-center justify-center shrink-0">
                <i data-lucide="boxes" class="w-6 h-6 text-vortex-green2"></i>
            </div>
            <div>
                <p class="font-semibold text-slate-700">Inventario</p>
                <p class="text-xs text-slate-400">Controlar el stock</p>
            </div>
        </a>
        <a href="{{ route('proveedores.index') }}" class="bg-white rounded-xl border border-slate-200 p-5 hover:border-vortex-green hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-vortex-green/10 flex items-center justify-center shrink-0">
                <i data-lucide="truck" class="w-6 h-6 text-vortex-green2"></i>
            </div>
            <div>
                <p class="font-semibold text-slate-700">Proveedores</p>
                <p class="text-xs text-slate-400">Gestionar proveedores</p>
            </div>
        </a>
        @endif

        {{-- Empleados y Configuración: solo administrador --}}
        @if ($cargo === 'Administrador')
        <a href="{{ route('empleados.index') }}" class="bg-white rounded-xl border border-slate-200 p-5 hover:border-vortex-green hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-vortex-green/10 flex items-center justify-center shrink-0">
                <i data-lucide="id-card" class="w-6 h-6 text-vortex-green2"></i>
            </div>
            <div>
                <p class="font-semibold text-slate-700">Empleados</p>
                <p class="text-xs text-slate-400">Administrar usuarios del sistema</p>
            </div>
        </a>
        <a href="{{ route('configuracion.index') }}" class="bg-white rounded-xl border border-slate-200 p-5 hover:border-vortex-green hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-vortex-green/10 flex items-center justify-center shrink-0">
                <i data-lucide="settings" class="w-6 h-6 text-vortex-green2"></i>
            </div>
            <div>
                <p class="font-semibold text-slate-700">Configuración</p>
                <p class="text-xs text-slate-400">Ajustes del sistema</p>
            </div>
        </a>
        @endif
    </div>
</div>
@endsection
