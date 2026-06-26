<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $config = Configuracion::firstOrCreate(['id' => 1], ['nombre_empresa' => 'Supermercado']);
        return view('configuracion.index', compact('config'));
    }

    public function update(Request $request)
    {
        // El nombre de empresa SÍ puede llevar números; el teléfono no; el IVA entre 0 y 100.
        $datos = $request->validate([
            'nombre_empresa' => ['required', 'string', 'max:150'],
            'direccion'      => ['nullable', 'string', 'max:200'],
            'telefono'       => ['nullable', 'regex:/^\d{4}-?\d{4}$/'],
            'correo'         => ['nullable', 'email', 'max:100'],
            'nit'            => ['nullable', 'string', 'max:50'],
            'iva_porcentaje' => ['required', 'numeric', 'min:0', 'max:100'],
            'moneda'         => ['required', 'string', 'max:10'],
        ], [
            'telefono.regex'     => 'El teléfono debe tener 8 dígitos (ejemplo: 7777-7777).',
            'correo.email'       => 'El correo no tiene un formato válido.',
            'iva_porcentaje.max' => 'El IVA debe estar entre 0 y 100.',
            'iva_porcentaje.min' => 'El IVA no puede ser negativo.',
        ]);

        $config = Configuracion::firstOrCreate(['id' => 1]);
        $config->update($datos);

        return redirect()->route('configuracion.index')->with('exito', 'Configuración guardada correctamente.');
    }
}
