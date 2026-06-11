<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    public $timestamps = false;

    protected $fillable = [
        'categoria', 'marca', 'fecha_vencimiento', 'nombre', 'precio',
        'id_proveedor', 'stock', 'precio_con_iva', 'codigo_barras',
        'imagen', 'stock_minimo', 'stock_maximo',
    ];

    // Relación con proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    // Estado del stock: "Bajo" si llegó o bajó del mínimo, si no "Normal"
    public function getEstadoStockAttribute(): string
    {
        return $this->stock <= ($this->stock_minimo ?? 10) ? 'Bajo' : 'Normal';
    }
}
