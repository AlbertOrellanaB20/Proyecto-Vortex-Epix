<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vortex Epix') · Sistema de Gestión de Supermercado</title>

    {{-- Tailwind CSS vía CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Lucide Icons vía CDN --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    {{-- Paleta exacta del tema Vortex Epix (extraída del Figma) --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'vortex-bg':      '#0f172a', // Fondo oscuro casi negro
                        'vortex-navy':    '#0A1F44', // Azul marino profundo (sidebar)
                        'vortex-navy-2':  '#1A3A5F', // Azul marino claro
                        'vortex-neon':    '#22c55e', // Verde neón / éxito
                        'vortex-neon-2':  '#00FF41', // Verde neón brillante
                        'vortex-sky':     '#00BFFF', // Azul cielo (acento)
                        'vortex-danger':  '#EF4444', // Rojo alerta
                    },
                    fontFamily: {
                        sans: ['Poppins', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>

    {{-- Fuente Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Patrón de grid sutil sobre el fondo oscuro */
        .vortex-grid {
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }
        /* Glow neón para el botón principal */
        .neon-glow {
            box-shadow: 0 0 18px rgba(34, 197, 94, 0.55), 0 0 4px rgba(34, 197, 94, 0.4);
            transition: box-shadow .25s ease, transform .1s ease;
        }
        .neon-glow:hover {
            box-shadow: 0 0 28px rgba(34, 197, 94, 0.8), 0 0 8px rgba(34, 197, 94, 0.6);
        }
        .neon-glow:active { transform: translateY(1px); }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="relative min-h-screen w-full flex items-center justify-center p-4
                bg-gradient-to-br from-vortex-bg via-vortex-navy to-vortex-navy-2">
        {{-- Capa de patrón grid --}}
        <div class="absolute inset-0 vortex-grid pointer-events-none"></div>

        {{-- Contenido de cada vista --}}
        <div class="relative w-full max-w-md">
            @yield('content')
        </div>
    </div>

    {{-- Inicializar íconos Lucide --}}
    <script>lucide.createIcons();</script>
    @yield('scripts')
</body>
</html>