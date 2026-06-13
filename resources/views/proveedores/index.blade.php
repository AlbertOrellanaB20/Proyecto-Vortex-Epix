@extends('layouts.app')
@section('titulo', 'Gestión de Proveedores')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Gestión de Proveedores</h2>
        <p class="text-sm text-slate-500">Administra la red de proveedores del supermercado</p>
    </div>
    <button onclick="abrirModalProv()" class="flex items-center gap-2 bg-vortex-green hover:bg-vortex-green2 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition">
        <i data-lucide="plus" class="w-4 h-4"></i> Agregar Proveedor
    </button>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between">
        <div><p class="text-sm text-slate-500">Total Proveedores</p><p class="text-3xl font-bold text-slate-800">{{ $total }}</p></div>
        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center"><i data-lucide="truck" class="w-6 h-6 text-vortex-green2"></i></div>
    </div>
</div>

<form method="GET" class="mb-5">
    <div class="relative">
        <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
        <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar por empresa, categoría, correo o teléfono..."
               class="w-full bg-white border border-slate-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
    </div>
</form>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100"><h3 class="font-semibold text-slate-700">Listado de Proveedores ({{ $proveedores->count() }})</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-5 py-3 font-medium">Empresa</th>
                    <th class="px-5 py-3 font-medium">Categoría</th>
                    <th class="px-5 py-3 font-medium">Teléfono</th>
                    <th class="px-5 py-3 font-medium">Correo</th>
                    <th class="px-5 py-3 font-medium">Dirección</th>
                    <th class="px-5 py-3 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($proveedores as $p)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3 font-medium text-slate-700 flex items-center gap-2"><i data-lucide="building-2" class="w-4 h-4 text-vortex-green"></i> {{ $p->nombre_empresa }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ $p->categoria ?? '—' }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ $p->telefono ?? '—' }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ $p->correo ?? '—' }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ $p->direccion ?? '—' }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <button title="Editar" onclick='editarProv(@json($p))' class="p-1.5 rounded-md text-blue-500 hover:bg-blue-50"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                            <form method="POST" action="{{ route('proveedores.destroy', $p->id_proveedor) }}" onsubmit="return confirm('¿Eliminar el proveedor {{ $p->nombre_empresa }}?');">
                                @csrf @method('DELETE')
                                <button title="Eliminar" class="p-1.5 rounded-md text-red-500 hover:bg-red-50"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-slate-400">No hay proveedores.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Proveedor --}}
<div id="modalProv" class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-lg shadow-xl">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h3 id="modalProvTitulo" class="font-semibold text-slate-800">Nuevo Proveedor</h3>
            <button onclick="cerrarModalProv()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="formProv" method="POST" action="{{ route('proveedores.store') }}" class="p-5">
            @csrf
            <input type="hidden" name="_method" id="metodoProv" value="POST">
            @if ($errors->any())<div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-600 px-3 py-2 text-xs"><ul class="list-disc pl-4">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2"><label class="block text-xs font-medium text-slate-600 mb-1">Nombre de la empresa *</label><input type="text" name="nombre_empresa" id="pr_empresa" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Categoría</label><input type="text" name="categoria" id="pr_categoria" placeholder="Abarrotes, Bebidas..." class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Teléfono</label><input type="text" name="telefono" id="pr_telefono" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Correo</label><input type="email" name="correo" id="pr_correo" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Dirección</label><input type="text" name="direccion" id="pr_direccion" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
            </div>
            <div class="flex justify-end gap-2 mt-5">
                <button type="button" onclick="cerrarModalProv()" class="px-4 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded-lg text-sm bg-vortex-green hover:bg-vortex-green2 text-white font-medium">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const modalP = document.getElementById('modalProv');
    const formP = document.getElementById('formProv');
    const baseProv = "{{ url('/proveedores') }}";
    function abrirModalProv() { formP.action="{{ route('proveedores.store') }}"; document.getElementById('metodoProv').value='POST'; document.getElementById('modalProvTitulo').textContent='Nuevo Proveedor'; formP.reset(); modalP.classList.remove('hidden'); modalP.classList.add('flex'); lucide.createIcons(); }
    function editarProv(p) { formP.action=baseProv+'/'+p.id_proveedor; document.getElementById('metodoProv').value='PUT'; document.getElementById('modalProvTitulo').textContent='Editar Proveedor'; pr_empresa.value=p.nombre_empresa||''; pr_categoria.value=p.categoria||''; pr_telefono.value=p.telefono||''; pr_correo.value=p.correo||''; pr_direccion.value=p.direccion||''; modalP.classList.remove('hidden'); modalP.classList.add('flex'); lucide.createIcons(); }
    function cerrarModalProv() { modalP.classList.add('hidden'); modalP.classList.remove('flex'); }
    @if ($errors->any()) abrirModalProv(); @endif
</script>
@endsection
