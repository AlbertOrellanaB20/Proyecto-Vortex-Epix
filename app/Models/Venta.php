<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $primaryKey = 'id_venta';
    public $timestamps = false;

    protected $fillable = ['fecha', 'total', 'id_factura', 'id_empleado', 'impuesto'];

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta', 'id_venta');
    }

    public function factura()
    {
        return $this->belongsTo(Factura::class, 'id_factura', 'id_factura');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }
}
