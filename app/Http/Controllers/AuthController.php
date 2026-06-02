<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de login.
     */
    public function login()
    {
        // Si ya hay una sesión activa, mandar directo al dashboard.
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Procesa el intento de inicio de sesión.
     * Autenticación manual contra la tabla `empleados` con sesiones Laravel.
     */
    public function attempt(Request $request)
    {
        // 1. Validar que vengan ambos campos.
        $credenciales = $request->validate([
            'usuario'  => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'usuario.required'  => 'El usuario es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        // 2. Intentar autenticar. Auth::attempt compara la contraseña
        //    con el hash bcrypt almacenado automáticamente.
        if (Auth::attempt($credenciales)) {
            // Regenerar la sesión para prevenir session fixation.
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        // 3. Credenciales incorrectas -> volver con mensaje de error en rojo.
        return back()
            ->withInput($request->only('usuario'))
            ->withErrors(['usuario' => 'Usuario o contraseña incorrectos.']);
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
