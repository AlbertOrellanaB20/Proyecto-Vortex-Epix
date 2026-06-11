<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Panel') · Vortex Epix</title>

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

    {{-- Contenido --}}
    <div class="flex-1 flex flex-col min-w-0">
        {{-- Barra superior --}}
        <header class="bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between sticky top-0 z-20">
            <div>
                <h1 class="text-lg font-semibold text-slate-800">@yield('titulo', 'Panel')</h1>
                <p class="text-xs text-slate-400">{{ ucfirst(\Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY')) }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span id="reloj" class="text-sm text-slate-500 flex items-center gap-1">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </span>
                <div class="text-right leading-tight">
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
</script>
@yield('scripts')
</body>
</html>
