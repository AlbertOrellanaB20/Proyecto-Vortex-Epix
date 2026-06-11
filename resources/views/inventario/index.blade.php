@extends('layouts.app')
@section('titulo', 'Control de Inventario')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Control de Inventario</h2>
        <p class="text-sm text-slate-500">Monitorea el stock de productos en tiempo real</p>
    </div>
    <a href="{{ route('inventario.exportar') }}" class="flex items-center gap-2 bg-teal-500 hover:bg-teal-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition">
        <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Exportar a Excel
    </a>
</div>

{{-- Tarjetas de estadísticas --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between">
        <div><p class="text-sm text-slate-500">Total de Productos</p><p class="text-3xl font-bold text-slate-800">{{ $totalProductos }}</p></div>
        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center"><i data-lucide="package" class="w-6 h-6 text-vortex-green2"></i></div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between">
        <div><p class="text-sm text-slate-500">Stock Total</p><p class="text-3xl font-bold text-slate-800">{{ number_format($stockTotal) }}</p><p class="text-xs text-slate-400">unidades</p></div>
        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center"><i data-lucide="boxes" class="w-6 h-6 text-blue-500"></i></div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between">
        <div><p class="text-sm text-slate-500">Productos con Stock Bajo</p><p class="text-3xl font-bold text-red-500">{{ $productosBajos->count() }}</p>@if($productosBajos->count())<p class="text-xs text-red-400">¡Reabastecer pronto!</p>@endif</div>
        <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center"><i data-lucide="alert-triangle" class="w-6 h-6 text-red-500"></i></div>
    </div>
</div>

{{-- Panel de stock bajo --}}
@if ($productosBajos->count())
<div class="bg-red-50 border border-red-200 rounded-xl p-5 mb-5">
    <h3 class="flex items-center gap-2 text-red-600 font-semibold mb-3"><i data-lucide="alert-triangle" class="w-5 h-5"></i> Productos con Stock Bajo</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach ($productosBajos as $p)
        <div class="bg-white rounded-lg border border-red-100 p-4 flex items-center justify-between">
            <div>
                <p class="font-medium text-slate-700">{{ $p->nombre }}</p>
                <p class="text-xs text-slate-400 font-mono">{{ $p->codigo_barras }}</p>
                <p class="text-xs text-slate-500 mt-1">Mínimo: {{ $p->stock_minimo ?? 10 }}</p>
            </div>
            <div class="text-right">
                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600 mb-2">{{ $p->stock }}</span><br>
                <button onclick="abrirReabastecer({{ $p->id_producto }}, '{{ addslashes($p->nombre) }}')" class="text-xs text-vortex-green2 font-medium hover:underline">Reabastecer</button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Buscador --}}
<form method="GET" class="mb-5">
    <div class="relative">
        <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
        <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar en inventario por código, nombre o categoría..."
               class="w-full bg-white border border-slate-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
    </div>
</form>

{{-- Tabla inventario completo --}}
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100"><h3 class="font-semibold text-slate-700">Inventario Completo ({{ $productos->count() }})</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-5 py-3 font-medium">Código</th>
                    <th class="px-5 py-3 font-medium">Producto</th>
                    <th class="px-5 py-3 font-medium">Categoría</th>
                    <th class="px-5 py-3 font-medium text-center">Stock Actual</th>
                    <th class="px-5 py-3 font-medium text-center">Stock Mínimo</th>
                    <th class="px-5 py-3 font-medium text-center">Estado</th>
                    <th class="px-5 py-3 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($productos as $p)
                @php $bajo = $p->stock <= ($p->stock_minimo ?? 10); @endphp
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $p->codigo_barras }}</td>
                    <td class="px-5 py-3 font-medium text-slate-700">{{ $p->nombre }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ $p->categoria }}</td>
                    <td class="px-5 py-3 text-center font-bold {{ $bajo ? 'text-red-500' : 'text-vortex-green2' }}">{{ $p->stock }}</td>
                    <td class="px-5 py-3 text-center text-slate-500">{{ $p->stock_minimo ?? 10 }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $bajo ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-700' }}">
                            <i data-lucide="{{ $bajo ? 'alert-circle' : 'check-circle' }}" class="w-3 h-3"></i> {{ $bajo ? 'Bajo' : 'Normal' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <button onclick="abrirReabastecer({{ $p->id_producto }}, '{{ addslashes($p->nombre) }}')" class="inline-flex items-center gap-1 text-xs text-vortex-green2 font-medium hover:underline">
                            <i data-lucide="plus" class="w-3 h-3"></i> Reabastecer
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-slate-400">Sin productos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Reabastecer --}}
<div id="modalReabastecer" class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-sm shadow-xl">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">Reabastecer producto</h3>
            <button onclick="cerrarReabastecer()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="POST" action="{{ route('inventario.reabastecer') }}" class="p-5">
            @csrf
            <input type="hidden" name="id_producto" id="r_id">
            <p class="text-sm text-slate-500 mb-3">Producto: <span id="r_nombre" class="font-medium text-slate-700"></span></p>
            <label class="block text-xs font-medium text-slate-600 mb-1">Cantidad a agregar</label>
            <input type="number" name="cantidad" min="1" required value="10" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
            <div class="flex justify-end gap-2 mt-5">
                <button type="button" onclick="cerrarReabastecer()" class="px-4 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded-lg text-sm bg-vortex-green hover:bg-vortex-green2 text-white font-medium">Reabastecer</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const modalR = document.getElementById('modalReabastecer');
    function abrirReabastecer(id, nombre) {
        document.getElementById('r_id').value = id;
        document.getElementById('r_nombre').textContent = nombre;
        modalR.classList.remove('hidden'); modalR.classList.add('flex');
        lucide.createIcons();
    }
    function cerrarReabastecer() { modalR.classList.add('hidden'); modalR.classList.remove('flex'); }
</script>
@endsection
