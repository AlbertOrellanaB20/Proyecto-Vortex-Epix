<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->query('buscar');

        $empleados = Empleado::query()
            ->when($buscar, function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('apellido', 'like', "%{$buscar}%")
                  ->orWhere('usuario', 'like', "%{$buscar}%")
                  ->orWhere('cargo', 'like', "%{$buscar}%")
                  ->orWhere('departamento', 'like', "%{$buscar}%");
            })->orderBy('id_empleado')->get();

        $total   = Empleado::count();
        $activos = Empleado::where('estado', 'Activo')->count();
        $cargos  = ['Administrador', 'Cajero', 'Supervisor', 'Inventario'];

        return view('empleados.index', compact('empleados', 'total', 'activos', 'cargos', 'buscar'));
    }

    // nombre/apellido solo letras, usuario sin espacios, teléfono 8 dígitos
    private function reglas($id = null): array
    {
        $u = $id ? ',' . $id . ',id_empleado' : '';
        return [
            'nombre'   => ['required', 'regex:/^[\pL\s\'\.\-]+$/u', 'max:100'],
            'apellido' => ['required', 'regex:/^[\pL\s\'\.\-]+$/u', 'max:100'],
            'usuario'  => ['required', 'regex:/^[a-zA-Z0-9_.]+$/', 'max:50', 'unique:empleados,usuario' . $u],
            'password' => [$id ? 'nullable' : 'required', 'string', 'min:3'],
            'cargo'    => ['required', 'in:Administrador,Cajero,Supervisor,Inventario'],
            'correo'   => ['nullable', 'email', 'max:100'],
            'telefono' => ['nullable', 'regex:/^\d{4}-?\d{4}$/'],
            'estado'   => ['required', 'in:Activo,Inactivo'],
            'departamento'       => ['nullable', 'string', 'max:100'],
            'salario'            => ['nullable', 'numeric', 'min:0'],
            'fecha_contratacion' => ['nullable', 'date'],
        ];
    }

    private function mensajes(): array
    {
        return [
            'nombre.regex'   => 'El nombre solo puede contener letras.',
            'apellido.regex' => 'El apellido solo puede contener letras.',
            'usuario.regex'  => 'El usuario no debe llevar espacios (solo letras, números, punto o guion bajo).',
            'usuario.unique' => 'Ese usuario ya existe.',
            'telefono.regex' => 'El teléfono debe tener 8 dígitos (ejemplo: 7777-7777).',
            'correo.email'   => 'El correo no tiene un formato válido.',
            'salario.numeric'=> 'El salario debe ser un número.',
        ];
    }

    public function store(Request $request)
    {
        $datos = $request->validate($this->reglas(), $this->mensajes());
        Empleado::create($datos); // la contraseña se encripta sola (cast 'hashed')
        return redirect()->route('empleados.index')->with('exito', 'Usuario creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);
        $datos = $request->validate($this->reglas($id), $this->mensajes());

        if (empty($datos['password'])) {
            unset($datos['password']);
        }

        $empleado->update($datos);
        return redirect()->route('empleados.index')->with('exito', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        if ((int) $id === (int) auth()->id()) {
            return redirect()->route('empleados.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        Empleado::findOrFail($id)->delete();
        return redirect()->route('empleados.index')->with('exito', 'Usuario eliminado correctamente.');
    }
}
