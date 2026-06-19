<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarjeta de Fidelidad - {{ $cliente->nombre }} {{ $cliente->apellido }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex flex-col items-center justify-center p-6">

    @php
        $empresa = $config->nombre_empresa ?? 'Vortex Epix';
        $colores = [
            'Bronce'   => 'from-amber-700 to-amber-500',
            'Plata'    => 'from-slate-500 to-slate-300',
            'Oro'      => 'from-yellow-500 to-yellow-300',
            'Diamante' => 'from-cyan-500 to-blue-400',
        ];
        $gradiente = $colores[$cliente->nivel_fidelidad] ?? 'from-emerald-600 to-emerald-400';
        $qr = 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' . urlencode($cliente->codigo_cliente);
    @endphp

    <!-- TARJETA -->
    <div class="w-[420px] rounded-2xl shadow-2xl overflow-hidden bg-white">

        <!-- Encabezado -->
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-400 px-6 py-5 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-widest opacity-90">Tarjeta de Cliente Frecuente</p>
                    <h1 class="text-2xl font-bold">{{ $empresa }}</h1>
                </div>
                <div class="text-4xl">🛒</div>
            </div>
        </div>

        <!-- Cuerpo -->
        <div class="px-6 py-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <p class="text-xs text-slate-400 uppercase">Titular</p>
                    <p class="text-xl font-semibold text-slate-800">{{ $cliente->nombre }} {{ $cliente->apellido }}</p>

                    <p class="mt-3 text-xs text-slate-400 uppercase">Código de cliente</p>
                    <p class="text-lg font-mono font-bold text-emerald-600 tracking-wider">{{ $cliente->codigo_cliente }}</p>

                    <div class="mt-3 inline-block px-3 py-1 rounded-full text-white text-sm font-semibold bg-gradient-to-r {{ $gradiente }}">
                        Nivel {{ $cliente->nivel_fidelidad }}
                    </div>
                </div>

                <!-- QR -->
                <div class="text-center">
                    <img src="{{ $qr }}" alt="Código QR" class="w-32 h-32 rounded-lg border border-slate-200">
                    <p class="text-[10px] text-slate-400 mt-1">Escanéame</p>
                </div>
            </div>

            <!-- Puntos -->
            <div class="mt-5 grid grid-cols-2 gap-3">
                <div class="bg-emerald-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-bold text-emerald-600">{{ number_format($cliente->puntos) }}</p>
                    <p class="text-xs text-slate-500">Puntos acumulados</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-bold text-slate-700">${{ number_format($cliente->valorDescuento(), 2) }}</p>
                    <p class="text-xs text-slate-500">En descuentos</p>
                </div>
            </div>

            <p class="mt-4 text-center text-[11px] text-slate-400">
                Cada 100 puntos equivalen a $1 en descuentos. ¡Gracias por tu preferencia!
            </p>
        </div>
    </div>

    <!-- Botones (no se imprimen) -->
    <div class="no-print mt-6 flex gap-3">
        <button onclick="window.print()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium shadow">
            🖨️ Imprimir tarjeta
        </button>
        <a href="{{ route('clientes.index') }}" class="px-5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg font-medium shadow">
            ← Volver a Clientes
        </a>
    </div>

</body>
</html>
