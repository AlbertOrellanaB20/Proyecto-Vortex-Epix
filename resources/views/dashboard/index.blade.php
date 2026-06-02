<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard · Vortex Epix</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
{{-- NOTA: Dashboard temporal solo para probar el login + RBAC.
     Lo reemplaza el módulo de Dashboard del compañero correspondiente. --}}
<body class="bg-slate-900 text-white min-h-screen flex items-center justify-center font-sans">
    <div class="text-center space-y-4">
        <div class="mx-auto w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center">
            <i data-lucide="shopping-cart" class="w-9 h-9 text-white"></i>
        </div>
        <h1 class="text-3xl font-bold">¡Bienvenido, {{ auth()->user()->nombre_completo }}!</h1>
        <p class="text-slate-400">
            Cargo: <span class="text-green-400 font-medium">{{ auth()->user()->cargo }}</span>
        </p>
        <p class="text-sm text-slate-500">Login y RBAC funcionando correctamente.</p>

        <form method="POST" action="{{ route('logout') }}" class="pt-4">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-red-500 hover:bg-red-600 font-semibold">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                Cerrar Sesión
            </button>
        </form>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>