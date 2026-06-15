<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $table = 'bitacora';
    public $timestamps = false;

    protected $fillable = ['id_empleado', 'usuario', 'accion', 'modulo', 'metodo', 'ruta', 'ip', 'fecha'];
}
