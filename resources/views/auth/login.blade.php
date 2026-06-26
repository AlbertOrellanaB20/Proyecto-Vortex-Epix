@extends('layouts.auth')

@section('title', 'Iniciar Sesión')

@section('content')
<style>
    /* Entrada de la tarjeta */
    @keyframes vortexEntrar {
        from { opacity: 0; transform: translateY(24px) scale(.98); }
        to   { opacity: 1; transform: none; }
    }
    .vortex-entrar { animation: vortexEntrar .6s cubic-bezier(.22,1,.36,1); }

    /* Flotar suave del ícono */
    @keyframes vortexFlotar {
        0%,100% { transform: translateY(0); }
        50%     { transform: translateY(-6px); }
    }
    .vortex-flotar { animation: vortexFlotar 3s ease-in-out infinite; }

    /* Aparición escalonada de los campos */
    @keyframes vortexAparecer {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: none; }
    }
    .campo-anim { opacity: 0; animation: vortexAparecer .5s ease forwards; }
    .campo-anim.d1 { animation-delay: .15s; }
    .campo-anim.d2 { animation-delay: .30s; }
    .campo-anim.d3 { animation-delay: .45s; }
</style>

<div class="vortex-entrar bg-white rounded-2xl shadow-2xl overflow-hidden">
    <div class="px-8 pt-8 pb-6">

        {{-- Logo del supermercado + título --}}
        <div class="text-center space-y-3">
            <div class="vortex-flotar mx-auto w-24 h-24 rounded-full bg-white flex items-center justify-center
                        shadow-lg overflow-hidden">
                <img src="/img/logomercado.png" alt="Supermercado" class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-vortex-navy to-vortex-sky
                           bg-clip-text text-transparent">
                    Supermercado
                </h1>
                <p class="text-sm text-gray-500 mt-1">Sistema de Gestión de Supermercado</p>
            </div>
        </div>

        {{-- Mensaje de error (login incorrecto) --}}
        @if ($errors->any())
            <div class="mt-6 flex items-start gap-2 rounded-lg border border-vortex-danger/30
                        bg-vortex-danger/10 px-4 py-3 text-sm text-vortex-danger">
                <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- Formulario --}}
        <form method="POST" action="{{ route('login.attempt') }}" class="mt-6 space-y-5">
            @csrf

            {{-- Usuario --}}
            <div class="space-y-2 campo-anim d1">
                <label for="usuario" class="block text-sm font-medium text-vortex-navy">Usuario</label>
                <div class="relative">
                    <i data-lucide="user" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                    <input id="usuario" name="usuario" type="text" autofocus
                           value="{{ old('usuario') }}"
                           placeholder="Ingrese su usuario"
                           class="w-full h-11 pl-10 pr-3 rounded-lg bg-gray-100 border border-transparent
                                  text-vortex-navy placeholder-gray-400
                                  focus:bg-white focus:border-vortex-neon focus:ring-2 focus:ring-vortex-neon/40
                                  outline-none transition">
                </div>
            </div>

            {{-- Contraseña (con botón mostrar/ocultar) --}}
            <div class="space-y-2 campo-anim d2">
                <label for="password" class="block text-sm font-medium text-vortex-navy">Contraseña</label>
                <div class="relative">
                    <i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                    <input id="password" name="password" type="password"
                           placeholder="Ingrese su contraseña"
                           class="w-full h-11 pl-10 pr-11 rounded-lg bg-gray-100 border border-transparent
                                  text-vortex-navy placeholder-gray-400
                                  focus:bg-white focus:border-vortex-neon focus:ring-2 focus:ring-vortex-neon/40
                                  outline-none transition">
                    <button type="button" id="togglePassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-vortex-neon transition">
                        <i data-lucide="eye" id="iconoOjo" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            {{-- Botón verde neón --}}
            <div class="campo-anim d3">
                <button type="submit"
                        class="neon-glow w-full h-11 rounded-lg bg-vortex-neon hover:bg-green-500
                               text-white font-semibold text-base flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-5 h-5"></i>
                    Iniciar Sesión
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

@section('scripts')
<script>
    // Mostrar / ocultar contraseña (interactividad)
    const btnToggle = document.getElementById('togglePassword');
    const inputPass = document.getElementById('password');
    const iconoOjo  = document.getElementById('iconoOjo');
    btnToggle.addEventListener('click', () => {
        const esPass = inputPass.type === 'password';
        inputPass.type = esPass ? 'text' : 'password';
        iconoOjo.setAttribute('data-lucide', esPass ? 'eye-off' : 'eye');
        lucide.createIcons();
        inputPass.focus();
    });
</script>
@endsection
