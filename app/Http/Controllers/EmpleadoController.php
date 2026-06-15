<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    private array $cargos = ['Administrador', 'Cajero', 'Supervisor', 'Inventario'];

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
        $cargos  = $this->cargos;

        return view('empleados.index', compact('empleados', 'total', 'activos', 'cargos', 'buscar'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre'   => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'usuario'  => ['required', 'string', 'max:50', 'unique:empleados,usuario'],
            'password' => ['required', 'string', 'min:3'],
            'cargo'    => ['required', 'in:Administrador,Cajero,Supervisor,Inventario'],
            'correo'   => ['nullable', 'email', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'estado'   => ['required', 'in:Activo,Inactivo'],
            'departamento'       => ['nullable', 'string', 'max:100'],
            'salario'            => ['nullable', 'numeric', 'min:0'],
            'fecha_contratacion' => ['nullable', 'date'],
        ]);

        Empleado::create($datos); // la contraseña se encripta sola (cast 'hashed')

        return redirect()->route('empleados.index')->with('exito', 'Usuario creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $datos = $request->validate([
            'nombre'   => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'usuario'  => ['required', 'string', 'max:50', 'unique:empleados,usuario,' . $id . ',id_empleado'],
            'password' => ['nullable', 'string', 'min:3'],
            'cargo'    => ['required', 'in:Administrador,Cajero,Supervisor,Inventario'],
            'correo'   => ['nullable', 'email', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'estado'   => ['required', 'in:Activo,Inactivo'],
            'departamento'       => ['nullable', 'string', 'max:100'],
            'salario'            => ['nullable', 'numeric', 'min:0'],
            'fecha_contratacion' => ['nullable', 'date'],
        ]);

        // Si no escriben contraseña nueva, se deja la actual
        if (empty($datos['password'])) {
            unset($datos['password']);
        }

        $empleado->update($datos);

        return redirect()->route('empleados.index')->with('exito', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        // Seguridad: nadie puede borrar su propia cuenta
        if ((int) $id === (int) auth()->id()) {
            return redirect()->route('empleados.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        Empleado::findOrFail($id)->delete();

        return redirect()->route('empleados.index')->with('exito', 'Usuario eliminado correctamente.');
    }
}
