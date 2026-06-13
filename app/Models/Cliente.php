<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';
    public $timestamps = false;

    protected $fillable = [
        'codigo_cliente', 'nombre', 'apellido', 'correo',
        'telefono', 'direccion', 'puntos', 'nivel_fidelidad',
    ];

    // Nivel de fidelidad según los puntos acumulados (documento de Edgar)
    public static function nivelPorPuntos(int $puntos): string
    {
        if ($puntos >= 3000) return 'Diamante';
        if ($puntos >= 1500) return 'Oro';
        if ($puntos >= 500)  return 'Plata';
        return 'Bronce';
    }
}
