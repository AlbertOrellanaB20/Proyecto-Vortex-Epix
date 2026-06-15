<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuracion';
    public $timestamps = false;

    protected $fillable = [
        'nombre_empresa', 'direccion', 'telefono', 'correo', 'nit', 'iva_porcentaje', 'moneda',
    ];
}
