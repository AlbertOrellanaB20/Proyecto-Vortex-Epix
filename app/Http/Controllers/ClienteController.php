<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->query('buscar');

        $clientes = Cliente::query()
            ->when($buscar, function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('apellido', 'like', "%{$buscar}%")
                  ->orWhere('codigo_cliente', 'like', "%{$buscar}%")
                  ->orWhere('correo', 'like', "%{$buscar}%")
                  ->orWhere('telefono', 'like', "%{$buscar}%");
            })
            ->orderBy('id_cliente')->get();

        // Estadísticas (dashboard del documento)
        $total       = Cliente::count();
        $bronce      = Cliente::where('nivel_fidelidad', 'Bronce')->count();
        $plata       = Cliente::where('nivel_fidelidad', 'Plata')->count();
        $oro         = Cliente::where('nivel_fidelidad', 'Oro')->count();
        $diamante    = Cliente::where('nivel_fidelidad', 'Diamante')->count();
        $totalPuntos = Cliente::sum('puntos');
        $top         = Cliente::orderByDesc('puntos')->limit(10)->get();

        return view('clientes.index', compact('clientes', 'total', 'bronce', 'plata', 'oro', 'diamante', 'totalPuntos', 'top', 'buscar'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre'    => ['required', 'string', 'max:100'],
            'apellido'  => ['required', 'string', 'max:100'],
            'correo'    => ['nullable', 'email', 'max:100', 'unique:clientes,correo'],
            'telefono'  => ['nullable', 'string', 'max:20', 'unique:clientes,telefono'],
            'direccion' => ['nullable', 'string'],
            'puntos'    => ['nullable', 'integer', 'min:0'],
        ]);

        $datos['puntos'] = $datos['puntos'] ?? 0;
        $datos['nivel_fidelidad'] = Cliente::nivelPorPuntos($datos['puntos']);

        $cliente = Cliente::create($datos);
        // Código único automático CL0001, CL0002... (con el id)
        $cliente->codigo_cliente = 'CL' . str_pad($cliente->id_cliente, 4, '0', STR_PAD_LEFT);
        $cliente->save();

        return redirect()->route('clientes.index')->with('exito', "Cliente registrado con código {$cliente->codigo_cliente}.");
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $datos = $request->validate([
            'nombre'    => ['required', 'string', 'max:100'],
            'apellido'  => ['required', 'string', 'max:100'],
            'correo'    => ['nullable', 'email', 'max:100', 'unique:clientes,correo,' . $id . ',id_cliente'],
            'telefono'  => ['nullable', 'string', 'max:20', 'unique:clientes,telefono,' . $id . ',id_cliente'],
            'direccion' => ['nullable', 'string'],
            'puntos'    => ['nullable', 'integer', 'min:0'],
        ]);

        $datos['puntos'] = $datos['puntos'] ?? $cliente->puntos;
        $datos['nivel_fidelidad'] = Cliente::nivelPorPuntos($datos['puntos']);

        $cliente->update($datos);

        return redirect()->route('clientes.index')->with('exito', 'Cliente actualizado correctamente.');
    }

    public function destroy($id)
    {
        Cliente::findOrFail($id)->delete();
        return redirect()->route('clientes.index')->with('exito', 'Cliente eliminado correctamente.');
    }

    // Sumar puntos por una compra (1 dólar = 1 punto) y actualizar el nivel automáticamente
    public function agregarPuntos(Request $request)
    {
        $datos = $request->validate([
            'id_cliente' => ['required', 'exists:clientes,id_cliente'],
            'monto'      => ['required', 'numeric', 'min:0.01'],
        ]);

        $cliente = Cliente::findOrFail($datos['id_cliente']);
        $ganados = (int) round($datos['monto']); // 1 dólar = 1 punto
        $nivelAnterior = $cliente->nivel_fidelidad;

        $cliente->puntos += $ganados;
        $cliente->nivel_fidelidad = Cliente::nivelPorPuntos($cliente->puntos);
        $cliente->save();

        $msg = "Se agregaron {$ganados} puntos a {$cliente->nombre}. Total: {$cliente->puntos}.";
        if ($nivelAnterior !== $cliente->nivel_fidelidad) {
            $msg .= " ¡Subió de {$nivelAnterior} a {$cliente->nivel_fidelidad}!";
        }

        return redirect()->route('clientes.index')->with('exito', $msg);
    }
}
