<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CambiarPasswordsSeeder extends Seeder
{
    /**
     * Cambia las contraseñas "123" por contraseñas seguras
     * (mayúscula + número + símbolo) y las guarda con hash bcrypt.
     * Usa DB::table para no tocar columnas de fecha que la tabla no tiene.
     */
    public function run(): void
    {
        $nuevas = [
            'rudy'      => 'Rudy@2025',
            'diego'     => 'Diego#2025',
            'steve'     => 'Steve$2025',
            'alberto'   => 'Alberto&25',
            'alejandro' => 'Aleja@2025',
        ];

        foreach ($nuevas as $usuario => $clave) {
            DB::table('empleados')
                ->where('usuario', $usuario)
                ->update(['password' => Hash::make($clave)]);
        }
    }
}
