@extends('layouts.auth')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
    <div class="px-8 pt-8 pb-6">

        {{-- Ícono carrito verde + título --}}
        <div class="text-center space-y-3">
            <div class="mx-auto w-16 h-16 bg-vortex-neon rounded-2xl flex items-center justify-center
                        shadow-lg shadow-vortex-neon/40">
                <i data-lucide="shopping-cart" class="w-9 h-9 text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-vortex-navy to-vortex-sky
                           bg-clip-text text-transparent">
                    Vortex Epix
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
            <div class="space-y-2">
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

            {{-- Contraseña --}}
            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-vortex-navy">Contraseña</label>
                <div class="relative">
                    <i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                    <input id="password" name="password" type="password"
                           placeholder="Ingrese su contraseña"
                           class="w-full h-11 pl-10 pr-3 rounded-lg bg-gray-100 border border-transparent
                                  text-vortex-navy placeholder-gray-400
                                  focus:bg-white focus:border-vortex-neon focus:ring-2 focus:ring-vortex-neon/40
                                  outline-none transition">
                </div>
            </div>

            {{-- Botón verde neón --}}
            <button type="submit"
                    class="neon-glow w-full h-11 rounded-lg bg-vortex-neon hover:bg-green-500
                           text-white font-semibold text-base flex items-center justify-center gap-2">
                <i data-lucide="log-in" class="w-5 h-5"></i>
                Iniciar Sesión
            </button>
        </form>

        {{-- Lista de usuarios del sistema --}}
        <div class="mt-6 p-4 bg-gray-100 rounded-lg">
            <p class="font-semibold text-vortex-navy text-sm mb-3">Usuarios del sistema:</p>
            <div class="grid grid-cols-2 gap-x-4 gap-y-3 text-xs">
                @php
                    $usuarios = [
                        ['usuario' => 'steve',     'rol' => 'Administrador'],
                        ['usuario' => 'diego',     'rol' => 'Cajero'],
                        ['usuario' => 'rudy',      'rol' => 'Cajero'],
                        ['usuario' => 'alberto',   'rol' => 'Supervisor'],
                        ['usuario' => 'alejandro', 'rol' => 'Inventario'],
                    ];
                @endphp
                @foreach ($usuarios as $u)
                    <div class="flex items-start gap-1.5">
                        <i data-lucide="user-round" class="w-3.5 h-3.5 text-gray-500 mt-0.5"></i>
                        <div class="leading-tight">
                            <p class="text-gray-700">
                                <span class="font-medium text-vortex-navy">{{ $u['usuario'] }}</span> / 123
                            </p>
                            <p class="text-[10px] text-vortex-neon font-medium">{{ $u['rol'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection