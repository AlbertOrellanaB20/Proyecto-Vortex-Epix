<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'factura';
    protected $primaryKey = 'id_factura';
    public $timestamps = false;

    protected $fillable = ['metodo_pago', 'total', 'numero_factura', 'fecha', 'id_cliente', 'id_empleado'];

    public function venta()
    {
        return $this->hasOne(Venta::class, 'id_factura', 'id_factura');
    }
}
