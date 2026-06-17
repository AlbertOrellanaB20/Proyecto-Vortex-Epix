<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->query('buscar');

        $proveedores = Proveedor::query()
            ->when($buscar, function ($q) use ($buscar) {
                $q->where('nombre_empresa', 'like', "%{$buscar}%")
                  ->orWhere('correo', 'like', "%{$buscar}%")
                  ->orWhere('telefono', 'like', "%{$buscar}%")
                  ->orWhere('categoria', 'like', "%{$buscar}%");
            })
            ->orderBy('id_proveedor')->get();

        $total = Proveedor::count();

        return view('proveedores.index', compact('proveedores', 'total', 'buscar'));
    }

    // El nombre de empresa SÍ puede llevar números (ej. "Super 99"); el teléfono no.
    private function reglas(): array
    {
        return [
            'nombre_empresa' => ['required', 'string', 'max:100'],
            'categoria'      => ['nullable', 'string', 'max:100'],
            'telefono'       => ['nullable', 'regex:/^\d{4}-?\d{4}$/'],
            'correo'         => ['nullable', 'email', 'max:100'],
            'direccion'      => ['nullable', 'string', 'max:200'],
        ];
    }

    private function mensajes(): array
    {
        return [
            'telefono.regex' => 'El teléfono debe tener 8 dígitos (ejemplo: 7777-7777).',
            'correo.email'   => 'El correo no tiene un formato válido.',
        ];
    }

    public function store(Request $request)
    {
        $datos = $request->validate($this->reglas(), $this->mensajes());
        Proveedor::create($datos);
        return redirect()->route('proveedores.index')->with('exito', 'Proveedor agregado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $datos = $request->validate($this->reglas(), $this->mensajes());
        $proveedor->update($datos);
        return redirect()->route('proveedores.index')->with('exito', 'Proveedor actualizado correctamente.');
    }

    public function destroy($id)
    {
        Proveedor::findOrFail($id)->delete();
        return redirect()->route('proveedores.index')->with('exito', 'Proveedor eliminado correctamente.');
    }
}
