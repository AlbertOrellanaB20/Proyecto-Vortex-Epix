@extends('layouts.app')
@section('titulo', 'Gestión de Productos')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Gestión de Productos</h2>
        <p class="text-sm text-slate-500">Administra el catálogo de productos del supermercado</p>
    </div>
    <button onclick="abrirModalProducto()" class="flex items-center gap-2 bg-vortex-green hover:bg-vortex-green2 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition">
        <i data-lucide="plus" class="w-4 h-4"></i> Agregar Producto
    </button>
</div>

{{-- Buscador --}}
<form method="GET" class="mb-5">
    <div class="relative">
        <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
        <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar productos por código, nombre o categoría..."
               class="w-full bg-white border border-slate-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
    </div>
</form>

{{-- Tabla --}}
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100">
        <h3 class="font-semibold text-slate-700">Listado de Productos ({{ $productos->count() }})</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-5 py-3 font-medium">Código</th>
                    <th class="px-5 py-3 font-medium">Nombre</th>
                    <th class="px-5 py-3 font-medium">Categoría</th>
                    <th class="px-5 py-3 font-medium">Precio</th>
                    <th class="px-5 py-3 font-medium">Stock</th>
                    <th class="px-5 py-3 font-medium">Vencimiento</th>
                    <th class="px-5 py-3 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($productos as $p)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $p->codigo_barras }}</td>
                    <td class="px-5 py-3 font-medium text-slate-700 flex items-center gap-2">
                        <i data-lucide="package" class="w-4 h-4 text-vortex-green"></i> {{ $p->nombre }}
                    </td>
                    <td class="px-5 py-3 text-slate-500">{{ $p->categoria }}</td>
                    <td class="px-5 py-3 font-semibold text-vortex-green2">${{ number_format($p->precio, 2) }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $p->stock <= ($p->stock_minimo ?? 10) ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-700' }}">
                            {{ $p->stock }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-slate-500">{{ $p->fecha_vencimiento ? \Carbon\Carbon::parse($p->fecha_vencimiento)->format('d/m/Y') : '—' }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <button title="Editar"
                                onclick='editarProducto(@json($p))'
                                class="p-1.5 rounded-md text-blue-500 hover:bg-blue-50"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                            <form method="POST" action="{{ route('productos.destroy', $p->id_producto) }}"
                                  onsubmit="return confirm('¿Eliminar el producto {{ $p->nombre }}?');">
                                @csrf @method('DELETE')
                                <button title="Eliminar" class="p-1.5 rounded-md text-red-500 hover:bg-red-50"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-slate-400">No hay productos que coincidan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Agregar / Editar --}}
<div id="modalProducto" class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-lg shadow-xl">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h3 id="modalTitulo" class="font-semibold text-slate-800">Nuevo Producto</h3>
            <button onclick="cerrarModalProducto()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="formProducto" method="POST" action="{{ route('productos.store') }}" class="p-5">
            @csrf
            <input type="hidden" name="_method" id="metodoForm" value="POST">
            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-600 px-3 py-2 text-xs">
                    <ul class="list-disc pl-4">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Código de barras *</label>
                    <input type="text" name="codigo_barras" id="f_codigo" required maxlength="13"
                           class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40" placeholder="750100000001">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Categoría *</label>
                    <select name="categoria" id="f_categoria" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
                        <option value="">Seleccionar</option>
                        @foreach ($categorias as $c)<option value="{{ $c }}">{{ $c }}</option>@endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-slate-600 mb-1">Nombre del Producto *</label>
                    <input type="text" name="nombre" id="f_nombre" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40" placeholder="Leche Entera 1L">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Marca (opcional)</label>
                    <input type="text" name="marca" id="f_marca" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40" placeholder="Diana">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Precio ($) *</label>
                    <input type="number" step="0.01" min="0" name="precio" id="f_precio" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Stock inicial *</label>
                    <input type="number" min="0" name="stock" id="f_stock" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40" placeholder="0">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Stock mínimo (opcional)</label>
                    <input type="number" min="0" name="stock_minimo" id="f_stockmin" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40" placeholder="10">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Vencimiento (opcional)</label>
                    <input type="date" name="fecha_vencimiento" id="f_venc" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-5">
                <button type="button" onclick="cerrarModalProducto()" class="px-4 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded-lg text-sm bg-vortex-green hover:bg-vortex-green2 text-white font-medium">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const modal = document.getElementById('modalProducto');
    const form = document.getElementById('formProducto');
    const baseUrl = "{{ url('/productos') }}";

    function abrirModalProducto() {
        form.action = "{{ route('productos.store') }}";
        document.getElementById('metodoForm').value = 'POST';
        document.getElementById('modalTitulo').textContent = 'Nuevo Producto';
        form.reset();
        modal.classList.remove('hidden'); modal.classList.add('flex');
        lucide.createIcons();
    }
    function editarProducto(p) {
        form.action = baseUrl + '/' + p.id_producto;
        document.getElementById('metodoForm').value = 'PUT';
        document.getElementById('modalTitulo').textContent = 'Editar Producto';
        document.getElementById('f_codigo').value = p.codigo_barras || '';
        document.getElementById('f_categoria').value = p.categoria || '';
        document.getElementById('f_nombre').value = p.nombre || '';
        document.getElementById('f_marca').value = p.marca || '';
        document.getElementById('f_precio').value = p.precio || '';
        document.getElementById('f_stock').value = p.stock || 0;
        document.getElementById('f_stockmin').value = p.stock_minimo || '';
        document.getElementById('f_venc').value = p.fecha_vencimiento || '';
        modal.classList.remove('hidden'); modal.classList.add('flex');
        lucide.createIcons();
    }
    function cerrarModalProducto() { modal.classList.add('hidden'); modal.classList.remove('flex'); }
    // Si hubo errores de validación, reabrir el modal
    @if ($errors->any()) abrirModalProducto(); @endif
</script>
@endsection
