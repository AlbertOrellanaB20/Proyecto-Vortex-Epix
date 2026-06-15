<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $config = Configuracion::firstOrCreate(['id' => 1], ['nombre_empresa' => 'Vortex Epix']);
        return view('configuracion.index', compact('config'));
    }

    public function update(Request $request)
    {
        $datos = $request->validate([
            'nombre_empresa' => ['required', 'string', 'max:150'],
            'direccion'      => ['nullable', 'string', 'max:200'],
            'telefono'       => ['nullable', 'string', 'max:50'],
            'correo'         => ['nullable', 'email', 'max:100'],
            'nit'            => ['nullable', 'string', 'max:50'],
            'iva_porcentaje' => ['required', 'numeric', 'min:0', 'max:100'],
            'moneda'         => ['required', 'string', 'max:10'],
        ]);

        $config = Configuracion::firstOrCreate(['id' => 1]);
        $config->update($datos);

        return redirect()->route('configuracion.index')->with('exito', 'Configuración guardada correctamente.');
    }
}
