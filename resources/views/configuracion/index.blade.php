@extends('layouts.app')
@section('titulo', 'Configuración')

@section('content')
<div class="mb-5">
    <h2 class="text-2xl font-bold text-slate-800">Configuración</h2>
    <p class="text-sm text-slate-500">Ajustes generales del sistema (solo Administrador)</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    {{-- Datos de la empresa --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="font-semibold text-slate-700 mb-4 flex items-center gap-2"><i data-lucide="store" class="w-5 h-5 text-vortex-green"></i> Datos de la empresa</h3>

        @if (session('exito'))
            <div class="mb-4 flex items-center gap-2 rounded-lg bg-green-50 border border-green-200 text-green-700 px-4 py-2.5 text-sm"><i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('exito') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-600 px-3 py-2 text-xs"><ul class="list-disc pl-4">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form method="POST" action="{{ route('configuracion.update') }}">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-slate-600 mb-1">Nombre de la empresa *</label>
                    <input type="text" name="nombre_empresa" value="{{ old('nombre_empresa', $config->nombre_empresa) }}" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-slate-600 mb-1">Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $config->direccion) }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $config->telefono) }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Correo</label>
                    <input type="email" name="correo" value="{{ old('correo', $config->correo) }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">NIT</label>
                    <input type="text" name="nit" value="{{ old('nit', $config->nit) }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">IVA (%) *</label>
                    <input type="number" step="0.01" min="0" max="100" name="iva_porcentaje" value="{{ old('iva_porcentaje', $config->iva_porcentaje) }}" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Moneda *</label>
                    <input type="text" name="moneda" value="{{ old('moneda', $config->moneda ?? '$') }}" required maxlength="10" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
                </div>
            </div>
            <div class="flex justify-end mt-5">
                <button type="submit" class="flex items-center gap-2 bg-vortex-green hover:bg-vortex-green2 text-white px-5 py-2.5 rounded-lg text-sm font-medium"><i data-lucide="save" class="w-4 h-4"></i> Guardar cambios</button>
            </div>
        </form>
    </div>

    {{-- Info del sistema --}}
    <div class="bg-white rounded-xl border border-slate-200 p-6 h-fit">
        <h3 class="font-semibold text-slate-700 mb-4 flex items-center gap-2"><i data-lucide="info" class="w-5 h-5 text-blue-500"></i> Información del sistema</h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between"><span class="text-slate-500">Sistema</span><span class="font-medium text-slate-700">Supermercado POS</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Versión PHP</span><span class="font-medium text-slate-700">{{ PHP_VERSION }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Laravel</span><span class="font-medium text-slate-700">{{ app()->version() }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Productos</span><span class="font-medium text-slate-700">{{ \App\Models\Producto::count() }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Clientes</span><span class="font-medium text-slate-700">{{ \App\Models\Cliente::count() }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Empleados</span><span class="font-medium text-slate-700">{{ \App\Models\Empleado::count() }}</span></div>
        </div>
        <p class="text-xs text-slate-400 mt-4 pt-3 border-t border-slate-100">Instituto Nacional de Acajutla · Módulo 3.1 · Vortex Epix</p>
    </div>
</div>
@endsection
