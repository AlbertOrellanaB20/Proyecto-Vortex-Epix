@extends('layouts.app')
@section('titulo', 'Clientes Frecuentes')

@php
    function colorNivel($n) {
        return match($n) {
            'Diamante' => 'bg-cyan-100 text-cyan-700',
            'Oro'      => 'bg-yellow-100 text-yellow-700',
            'Plata'    => 'bg-slate-200 text-slate-600',
            default    => 'bg-amber-100 text-amber-700',
        };
    }
@endphp

@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Clientes Frecuentes</h2>
        <p class="text-sm text-slate-500">Programa de fidelización con puntos y niveles</p>
    </div>
    <button onclick="abrirModalCliente()" class="flex items-center gap-2 bg-vortex-green hover:bg-vortex-green2 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition">
        <i data-lucide="user-plus" class="w-4 h-4"></i> Agregar Cliente
    </button>
</div>

{{-- Estadísticas --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-5">
    <div class="bg-white rounded-xl border border-slate-200 p-4"><p class="text-xs text-slate-500">Total Clientes</p><p class="text-2xl font-bold text-slate-800">{{ $total }}</p></div>
    <div class="bg-white rounded-xl border border-slate-200 p-4"><p class="text-xs text-amber-600">Bronce</p><p class="text-2xl font-bold text-amber-600">{{ $bronce }}</p></div>
    <div class="bg-white rounded-xl border border-slate-200 p-4"><p class="text-xs text-slate-500">Plata</p><p class="text-2xl font-bold text-slate-500">{{ $plata }}</p></div>
    <div class="bg-white rounded-xl border border-slate-200 p-4"><p class="text-xs text-yellow-600">Oro</p><p class="text-2xl font-bold text-yellow-600">{{ $oro }}</p></div>
    <div class="bg-white rounded-xl border border-slate-200 p-4"><p class="text-xs text-cyan-600">Diamante</p><p class="text-2xl font-bold text-cyan-600">{{ $diamante }}</p></div>
    <div class="bg-vortex-green rounded-xl p-4 text-white"><p class="text-xs opacity-90">Total Puntos</p><p class="text-2xl font-bold">{{ number_format($totalPuntos) }}</p></div>
</div>

{{-- Buscador --}}
<form method="GET" class="mb-5">
    <div class="relative">
        <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
        <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar por código, nombre, correo o teléfono..."
               class="w-full bg-white border border-slate-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
    </div>
</form>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    {{-- Tabla --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100"><h3 class="font-semibold text-slate-700">Listado de Clientes ({{ $clientes->count() }})</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 text-left">
                    <tr>
                        <th class="px-4 py-3 font-medium">Código</th>
                        <th class="px-4 py-3 font-medium">Cliente</th>
                        <th class="px-4 py-3 font-medium">Contacto</th>
                        <th class="px-4 py-3 font-medium text-center">Puntos</th>
                        <th class="px-4 py-3 font-medium text-center">Nivel</th>
                        <th class="px-4 py-3 font-medium text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($clientes as $c)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-mono text-xs text-vortex-green2 font-semibold">{{ $c->codigo_cliente ?? '—' }}</td>
                        <td class="px-4 py-3 font-medium text-slate-700">{{ $c->nombre }} {{ $c->apellido }}</td>
                        <td class="px-4 py-3 text-slate-500 text-xs">
                            @if($c->correo)<div>{{ $c->correo }}</div>@endif
                            @if($c->telefono)<div>{{ $c->telefono }}</div>@endif
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-slate-700">{{ number_format($c->puntos) }}</td>
                        <td class="px-4 py-3 text-center"><span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ colorNivel($c->nivel_fidelidad) }}">{{ $c->nivel_fidelidad }}</span></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1.5">
                                <button title="Sumar puntos" onclick="abrirPuntos({{ $c->id_cliente }}, '{{ addslashes($c->nombre) }}')" class="p-1.5 rounded-md text-vortex-green2 hover:bg-green-50"><i data-lucide="plus-circle" class="w-4 h-4"></i></button>
                                <button title="Ver QR" onclick="verQR('{{ $c->codigo_cliente }}', '{{ addslashes($c->nombre) }}')" class="p-1.5 rounded-md text-slate-500 hover:bg-slate-100"><i data-lucide="qr-code" class="w-4 h-4"></i></button>
                                <button title="Editar" onclick='editarCliente(@json($c))' class="p-1.5 rounded-md text-blue-500 hover:bg-blue-50"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                                <form method="POST" action="{{ route('clientes.destroy', $c->id_cliente) }}" onsubmit="return confirm('¿Eliminar a {{ $c->nombre }}?');">
                                    @csrf @method('DELETE')
                                    <button title="Eliminar" class="p-1.5 rounded-md text-red-500 hover:bg-red-50"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-slate-400">No hay clientes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top 10 --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5 h-fit">
        <h3 class="font-semibold text-slate-700 mb-3 flex items-center gap-2"><i data-lucide="trophy" class="w-5 h-5 text-yellow-500"></i> Top 10 Clientes</h3>
        <div class="space-y-2">
            @forelse ($top as $i => $c)
            <div class="flex items-center gap-3 text-sm">
                <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-bold shrink-0">{{ $i + 1 }}</span>
                <div class="flex-1 min-w-0"><p class="font-medium text-slate-700 truncate">{{ $c->nombre }} {{ $c->apellido }}</p></div>
                <span class="font-bold text-vortex-green2">{{ number_format($c->puntos) }}</span>
            </div>
            @empty
            <p class="text-sm text-slate-400">Sin datos.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Modal Cliente --}}
<div id="modalCliente" class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-lg shadow-xl">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h3 id="modalClienteTitulo" class="font-semibold text-slate-800">Nuevo Cliente</h3>
            <button onclick="cerrarModalCliente()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="formCliente" method="POST" action="{{ route('clientes.store') }}" class="p-5">
            @csrf
            <input type="hidden" name="_method" id="metodoCliente" value="POST">
            @if ($errors->any())<div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-600 px-3 py-2 text-xs"><ul class="list-disc pl-4">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Nombre *</label><input type="text" name="nombre" id="c_nombre" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Apellido *</label><input type="text" name="apellido" id="c_apellido" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Correo</label><input type="email" name="correo" id="c_correo" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Teléfono</label><input type="text" name="telefono" id="c_telefono" placeholder="7777-7777" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
                <div class="col-span-2"><label class="block text-xs font-medium text-slate-600 mb-1">Dirección</label><input type="text" name="direccion" id="c_direccion" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Puntos iniciales</label><input type="number" name="puntos" id="c_puntos" min="0" value="0" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
            </div>
            <p class="text-xs text-slate-400 mt-2">El código (CL0001…) y el nivel se asignan automáticamente.</p>
            <div class="flex justify-end gap-2 mt-5">
                <button type="button" onclick="cerrarModalCliente()" class="px-4 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded-lg text-sm bg-vortex-green hover:bg-vortex-green2 text-white font-medium">Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Puntos --}}
<div id="modalPuntos" class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-sm shadow-xl">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100"><h3 class="font-semibold text-slate-800">Sumar puntos por compra</h3><button onclick="cerrarPuntos()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button></div>
        <form method="POST" action="{{ route('clientes.puntos') }}" class="p-5">
            @csrf
            <input type="hidden" name="id_cliente" id="p_id">
            <p class="text-sm text-slate-500 mb-3">Cliente: <span id="p_nombre" class="font-medium text-slate-700"></span></p>
            <label class="block text-xs font-medium text-slate-600 mb-1">Monto de la compra ($)</label>
            <input type="number" name="monto" step="0.01" min="0.01" required value="10" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
            <p class="text-xs text-slate-400 mt-1">1 dólar = 1 punto. El nivel se actualiza solo.</p>
            <div class="flex justify-end gap-2 mt-5"><button type="button" onclick="cerrarPuntos()" class="px-4 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100">Cancelar</button><button type="submit" class="px-4 py-2 rounded-lg text-sm bg-vortex-green hover:bg-vortex-green2 text-white font-medium">Sumar puntos</button></div>
        </form>
    </div>
</div>

{{-- Modal QR --}}
<div id="modalQR" class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-xs shadow-xl text-center p-6">
        <div class="flex items-center justify-between mb-3"><h3 class="font-semibold text-slate-800">Tarjeta de fidelización</h3><button onclick="cerrarQR()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button></div>
        <p id="qr_nombre" class="text-sm font-medium text-slate-700 mb-1"></p>
        <p id="qr_codigo" class="font-mono text-vortex-green2 font-bold mb-3"></p>
        <img id="qr_img" src="" alt="QR" class="mx-auto rounded-lg border border-slate-200" width="180" height="180">
        <p class="text-xs text-slate-400 mt-3">El cajero puede escanear este código para identificar al cliente.</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const modalC = document.getElementById('modalCliente');
    const formC = document.getElementById('formCliente');
    const baseClientes = "{{ url('/clientes') }}";

    function abrirModalCliente() {
        formC.action = "{{ route('clientes.store') }}"; document.getElementById('metodoCliente').value = 'POST';
        document.getElementById('modalClienteTitulo').textContent = 'Nuevo Cliente'; formC.reset();
        modalC.classList.remove('hidden'); modalC.classList.add('flex'); lucide.createIcons();
    }
    function editarCliente(c) {
        formC.action = baseClientes + '/' + c.id_cliente; document.getElementById('metodoCliente').value = 'PUT';
        document.getElementById('modalClienteTitulo').textContent = 'Editar Cliente';
        c_nombre.value=c.nombre||''; c_apellido.value=c.apellido||''; c_correo.value=c.correo||'';
        c_telefono.value=c.telefono||''; c_direccion.value=c.direccion||''; c_puntos.value=c.puntos||0;
        modalC.classList.remove('hidden'); modalC.classList.add('flex'); lucide.createIcons();
    }
    function cerrarModalCliente() { modalC.classList.add('hidden'); modalC.classList.remove('flex'); }

    function abrirPuntos(id, nombre) { document.getElementById('p_id').value=id; document.getElementById('p_nombre').textContent=nombre; document.getElementById('modalPuntos').classList.remove('hidden'); document.getElementById('modalPuntos').classList.add('flex'); }
    function cerrarPuntos() { document.getElementById('modalPuntos').classList.add('hidden'); document.getElementById('modalPuntos').classList.remove('flex'); }

    function verQR(codigo, nombre) {
        document.getElementById('qr_nombre').textContent = nombre;
        document.getElementById('qr_codigo').textContent = codigo;
        document.getElementById('qr_img').src = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + encodeURIComponent(codigo);
        document.getElementById('modalQR').classList.remove('hidden'); document.getElementById('modalQR').classList.add('flex');
    }
    function cerrarQR() { document.getElementById('modalQR').classList.add('hidden'); document.getElementById('modalQR').classList.remove('flex'); }

    @if ($errors->any()) abrirModalCliente(); @endif
</script>
@endsection
