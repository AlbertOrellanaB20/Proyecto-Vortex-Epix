<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Panel') · Supermercado</title>
    <link rel="icon" type="image/png" href="/img/logomercado.png">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: {
                    'vortex': { 'green': '#22c55e', 'green2': '#16a34a', 'navy': '#0f172a', 'navy2': '#1e293b' }
                },
                fontFamily: { sans: ['Poppins', 'system-ui', 'sans-serif'] }
            } }
        };
    </script>
    @yield('head')
</head>
<body class="font-sans bg-slate-100 text-slate-800">
<div class="flex min-h-screen">

    {{-- Menú lateral --}}
    @include('partials.sidebar')

    {{-- Fondo oscuro al abrir el menú en móvil --}}
    <div id="backdrop" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden"></div>

    {{-- Contenido --}}
    <div class="flex-1 flex flex-col min-w-0">
        {{-- Barra superior --}}
        <header class="bg-white border-b border-slate-200 px-4 sm:px-6 py-3 flex items-center justify-between sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <button id="abrirSidebar" class="lg:hidden text-slate-600 hover:text-vortex-green" aria-label="Abrir menu">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div>
                    <h1 class="text-base sm:text-lg font-semibold text-slate-800">@yield('titulo', 'Panel')</h1>
                    <p class="hidden sm:block text-xs text-slate-400">{{ ucfirst(\Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY')) }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span id="reloj" class="hidden sm:flex text-sm text-slate-500 items-center gap-1">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </span>
                <div class="hidden sm:block text-right leading-tight">
                    <p class="text-sm font-medium text-slate-700">{{ auth()->user()->nombre }} {{ auth()->user()->apellido }}</p>
                    <p class="text-xs text-vortex-green font-medium uppercase">{{ auth()->user()->cargo }}</p>
                </div>
                <div class="w-9 h-9 rounded-full bg-vortex-green text-white flex items-center justify-center font-semibold">
                    {{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}
                </div>
            </div>
        </header>

        {{-- Mensajes flash --}}
        @if (session('exito'))
            <div class="mx-6 mt-4 flex items-center gap-2 rounded-lg bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm">
                <i data-lucide="check-circle" class="w-5 h-5"></i> {{ session('exito') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mx-6 mt-4 flex items-center gap-2 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                <i data-lucide="alert-circle" class="w-5 h-5"></i> {{ session('error') }}
            </div>
        @endif

        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
</div>

<script>
    lucide.createIcons();

    // Menú lateral responsive (abrir / cerrar en móvil)
    (function () {
        const sidebar  = document.getElementById('sidebar');
        const backdrop = document.getElementById('backdrop');
        const abrir  = () => { sidebar.classList.remove('-translate-x-full'); backdrop.classList.remove('hidden'); };
        const cerrar = () => { sidebar.classList.add('-translate-x-full'); backdrop.classList.add('hidden'); };
        document.getElementById('abrirSidebar')?.addEventListener('click', abrir);
        document.getElementById('cerrarSidebar')?.addEventListener('click', cerrar);
        backdrop?.addEventListener('click', cerrar);
    })();

    // Reloj en vivo (hilo de tiempo, como pedía el Sprint 1)
    function actualizarReloj() {
        const r = document.getElementById('reloj');
        if (r) {
            const ahora = new Date().toLocaleTimeString('es-SV', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            r.innerHTML = '<i data-lucide="clock" class="w-4 h-4"></i>' + ahora;
            lucide.createIcons();
        }
    }
    setInterval(actualizarReloj, 1000); actualizarReloj();

    // ===== Formato automático de teléfono (8 dígitos + guion: 7777-7777) =====
    // Se aplica a cualquier input con la clase "js-telefono".
    function formatearTelefonoSV(el) {
        let v = el.value.replace(/\D/g, '').slice(0, 8); // solo números, máximo 8
        if (v.length > 4) v = v.slice(0, 4) + '-' + v.slice(4); // guion en medio
        el.value = v;
    }
    document.querySelectorAll('.js-telefono').forEach(function (el) {
        el.setAttribute('maxlength', '9');      // 8 dígitos + el guion
        el.setAttribute('inputmode', 'numeric'); // teclado numérico en celular
        el.addEventListener('keypress', function (e) {
            if (!/[0-9]/.test(e.key)) e.preventDefault(); // bloquea letras
        });
        el.addEventListener('input', function () { formatearTelefonoSV(el); });
    });
</script>
@yield('scripts')
</body>
</html>
