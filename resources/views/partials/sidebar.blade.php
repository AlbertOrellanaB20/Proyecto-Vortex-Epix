@php
    $cargo = auth()->user()->cargo;
    // Menú con los roles permitidos por cada módulo (RBAC, según la tabla del Sprint 2)
    $menu = [
        ['ruta' => 'dashboard',          'icono' => 'layout-dashboard', 'texto' => 'Inicio',      'roles' => ['Administrador','Cajero','Supervisor','Inventario']],
        ['ruta' => 'pos.index',          'icono' => 'shopping-cart',    'texto' => 'Ventas POS',  'roles' => ['Administrador','Cajero','Supervisor']],
        ['ruta' => 'productos.index',    'icono' => 'package',          'texto' => 'Productos',   'roles' => ['Administrador','Supervisor','Inventario']],
        ['ruta' => 'inventario.index',   'icono' => 'boxes',            'texto' => 'Inventario',  'roles' => ['Administrador','Inventario']],
        ['ruta' => 'clientes.index',     'icono' => 'users',            'texto' => 'Clientes',    'roles' => ['Administrador','Supervisor']],
        ['ruta' => 'proveedores.index',  'icono' => 'truck',            'texto' => 'Proveedores', 'roles' => ['Administrador','Inventario']],
        ['ruta' => 'empleados.index',    'icono' => 'id-card',          'texto' => 'Empleados',   'roles' => ['Administrador','Supervisor']],
        ['ruta' => 'facturacion.index',  'icono' => 'file-text',        'texto' => 'Facturación', 'roles' => ['Administrador','Supervisor']],
        ['ruta' => 'reportes.index',     'icono' => 'bar-chart-3',      'texto' => 'Reportes',    'roles' => ['Administrador','Supervisor']],
    ];
@endphp
<aside class="w-64 bg-vortex-navy text-slate-300 flex flex-col shrink-0">
    {{-- Logo --}}
    <div class="px-5 py-5 flex items-center gap-3 border-b border-white/10">
        <div class="w-10 h-10 rounded-xl bg-vortex-green flex items-center justify-center shrink-0">
            <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
        </div>
        <div class="leading-tight">
            <p class="text-white font-bold text-lg">Vortex Epix</p>
            <p class="text-xs text-slate-400">Sistema POS</p>
        </div>
    </div>

    {{-- Usuario --}}
    <div class="px-5 py-4 flex items-center gap-3 border-b border-white/10">
        <div class="w-9 h-9 rounded-full bg-vortex-green text-white flex items-center justify-center font-semibold shrink-0">
            {{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}
        </div>
        <div class="leading-tight min-w-0">
            <p class="text-white text-sm font-medium truncate">{{ auth()->user()->nombre }} {{ auth()->user()->apellido }}</p>
            <p class="text-xs text-vortex-green uppercase">{{ $cargo }}</p>
        </div>
    </div>

    {{-- Menú (solo lo que el rol puede ver) --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        @foreach ($menu as $item)
            @if (in_array($cargo, $item['roles']))
                @php $activo = request()->routeIs($item['ruta']); @endphp
                <a href="{{ route($item['ruta']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition
                          {{ $activo ? 'bg-vortex-green text-white font-medium' : 'hover:bg-white/10' }}">
                    <i data-lucide="{{ $item['icono'] }}" class="w-5 h-5 shrink-0"></i>
                    {{ $item['texto'] }}
                </a>
            @endif
        @endforeach
    </nav>

    {{-- Cerrar sesión --}}
    <div class="px-3 py-4 border-t border-white/10">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-red-500/20 hover:text-red-300 transition">
                <i data-lucide="log-out" class="w-5 h-5"></i> Cerrar Sesión
            </button>
        </form>
    </div>
</aside>
