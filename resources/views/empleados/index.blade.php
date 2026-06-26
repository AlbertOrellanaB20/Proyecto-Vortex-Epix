@extends('layouts.app')
@section('titulo', 'Gestión de Usuarios')

@php
    function colorCargo($c) {
        return match($c) {
            'Administrador' => 'bg-violet-100 text-violet-700',
            'Supervisor'    => 'bg-blue-100 text-blue-700',
            'Cajero'        => 'bg-green-100 text-green-700',
            'Inventario'    => 'bg-amber-100 text-amber-700',
            default         => 'bg-slate-100 text-slate-600',
        };
    }
@endphp

@section('content')
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Gestión de Usuarios</h2>
        <p class="text-sm text-slate-500">Administra los empleados del sistema y sus roles</p>
    </div>
    <button onclick="abrirModalEmp()" class="flex items-center gap-2 bg-vortex-green hover:bg-vortex-green2 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition">
        <i data-lucide="user-plus" class="w-4 h-4"></i> Agregar Usuario
    </button>
</div>

{{-- Estadísticas --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between">
        <div><p class="text-sm text-slate-500">Total Empleados</p><p class="text-3xl font-bold text-slate-800">{{ $total }}</p></div>
        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center"><i data-lucide="users" class="w-6 h-6 text-blue-500"></i></div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between">
        <div><p class="text-sm text-slate-500">Activos</p><p class="text-3xl font-bold text-vortex-green2">{{ $activos }}</p></div>
        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center"><i data-lucide="user-check" class="w-6 h-6 text-vortex-green2"></i></div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between">
        <div><p class="text-sm text-slate-500">Inactivos</p><p class="text-3xl font-bold text-slate-800">{{ $total - $activos }}</p></div>
        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center"><i data-lucide="user-x" class="w-6 h-6 text-slate-500"></i></div>
    </div>
</div>

{{-- Buscador --}}
<form method="GET" class="mb-5">
    <div class="relative">
        <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
        <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar por nombre, usuario, cargo o departamento..."
               class="w-full bg-white border border-slate-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
    </div>
</form>

{{-- Tabla --}}
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100"><h3 class="font-semibold text-slate-700">Listado de Empleados ({{ $empleados->count() }})</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium">Empleado</th>
                    <th class="px-4 py-3 font-medium">Usuario</th>
                    <th class="px-4 py-3 font-medium">Cargo</th>
                    <th class="px-4 py-3 font-medium">Contacto</th>
                    <th class="px-4 py-3 font-medium">Departamento</th>
                    <th class="px-4 py-3 font-medium text-right">Salario</th>
                    <th class="px-4 py-3 font-medium text-center">Estado</th>
                    <th class="px-4 py-3 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($empleados as $e)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium text-slate-700">{{ $e->nombre }} {{ $e->apellido }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-slate-500">{{ $e->usuario }}</td>
                    <td class="px-4 py-3"><span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ colorCargo($e->cargo) }}">{{ $e->cargo }}</span></td>
                    <td class="px-4 py-3 text-slate-500 text-xs">
                        @if($e->correo)<div>{{ $e->correo }}</div>@endif
                        @if($e->telefono)<div>{{ $e->telefono }}</div>@endif
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $e->departamento ?? '—' }}</td>
                    <td class="px-4 py-3 text-right text-slate-600">{{ $e->salario ? '$' . number_format($e->salario, 2) : '—' }}</td>
                    <td class="px-4 py-3 text-center"><span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $e->estado === 'Activo' ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-500' }}">{{ $e->estado ?? 'Activo' }}</span></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <button title="Editar" onclick='editarEmp(@json($e))' class="p-1.5 rounded-md text-blue-500 hover:bg-blue-50"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                            <form method="POST" action="{{ route('empleados.destroy', $e->id_empleado) }}" onsubmit="return confirm('¿Eliminar al usuario {{ $e->nombre }}?');">
                                @csrf @method('DELETE')
                                <button title="Eliminar" class="p-1.5 rounded-md text-red-500 hover:bg-red-50"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-5 py-10 text-center text-slate-400">No hay usuarios.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Usuario --}}
<div id="modalEmp" class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-2xl shadow-xl max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 sticky top-0 bg-white">
            <h3 id="modalEmpTitulo" class="font-semibold text-slate-800">Nuevo Usuario</h3>
            <button onclick="cerrarModalEmp()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="formEmp" method="POST" action="{{ route('empleados.store') }}" class="p-5">
            @csrf
            <input type="hidden" name="_method" id="metodoEmp" value="POST">
            @if ($errors->any())<div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-600 px-3 py-2 text-xs"><ul class="list-disc pl-4">@foreach ($errors->all() as $er)<li>{{ $er }}</li>@endforeach</ul></div>@endif
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Nombre *</label><input type="text" name="nombre" id="e_nombre" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Apellido *</label><input type="text" name="apellido" id="e_apellido" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Usuario *</label><input type="text" name="usuario" id="e_usuario" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
                <div><label class="block text-xs font-medium text-slate-600 mb-1" id="lblPass">Contraseña *</label><input type="text" name="password" id="e_password" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40" placeholder="Mínimo 3 caracteres"></div>
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Cargo / Rol *</label>
                    <select name="cargo" id="e_cargo" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
                        <option value="">Seleccionar</option>
                        @foreach ($cargos as $c)<option value="{{ $c }}">{{ $c }}</option>@endforeach
                    </select>
                </div>
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Estado *</label>
                    <select name="estado" id="e_estado" required class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40">
                        <option value="Activo">Activo</option><option value="Inactivo">Inactivo</option>
                    </select>
                </div>
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Correo</label><input type="email" name="correo" id="e_correo" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Teléfono</label><input type="text" name="telefono" id="e_telefono" class="js-telefono w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Departamento</label><input type="text" name="departamento" id="e_departamento" placeholder="Ventas, Inventario..." class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Salario ($)</label><input type="number" step="0.01" min="0" name="salario" id="e_salario" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
                <div><label class="block text-xs font-medium text-slate-600 mb-1">Fecha de contratación</label><input type="date" name="fecha_contratacion" id="e_fecha" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-vortex-green/40"></div>
            </div>
            <div class="flex justify-end gap-2 mt-5">
                <button type="button" onclick="cerrarModalEmp()" class="px-4 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded-lg text-sm bg-vortex-green hover:bg-vortex-green2 text-white font-medium">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const modalE = document.getElementById('modalEmp');
    const formE = document.getElementById('formEmp');
    const baseEmp = "{{ url('/empleados') }}";

    function abrirModalEmp() {
        formE.action = "{{ route('empleados.store') }}"; document.getElementById('metodoEmp').value = 'POST';
        document.getElementById('modalEmpTitulo').textContent = 'Nuevo Usuario'; formE.reset();
        document.getElementById('lblPass').textContent = 'Contraseña *';
        document.getElementById('e_password').setAttribute('required', 'required');
        document.getElementById('e_password').placeholder = 'Mínimo 3 caracteres';
        modalE.classList.remove('hidden'); modalE.classList.add('flex'); lucide.createIcons();
    }
    function editarEmp(e) {
        formE.action = baseEmp + '/' + e.id_empleado; document.getElementById('metodoEmp').value = 'PUT';
        document.getElementById('modalEmpTitulo').textContent = 'Editar Usuario';
        e_nombre.value=e.nombre||''; e_apellido.value=e.apellido||''; e_usuario.value=e.usuario||'';
        e_cargo.value=e.cargo||''; e_estado.value=e.estado||'Activo'; e_correo.value=e.correo||'';
        e_telefono.value=e.telefono||''; e_departamento.value=e.departamento||''; e_salario.value=e.salario||'';
        e_fecha.value=e.fecha_contratacion||''; e_password.value='';
        document.getElementById('lblPass').textContent = 'Contraseña (dejar vacío para no cambiar)';
        document.getElementById('e_password').removeAttribute('required');
        document.getElementById('e_password').placeholder = 'Sin cambios';
        modalE.classList.remove('hidden'); modalE.classList.add('flex'); lucide.createIcons();
    }
    function cerrarModalEmp() { modalE.classList.add('hidden'); modalE.classList.remove('flex'); }
    @if ($errors->any()) abrirModalEmp(); @endif
</script>
@endsection
