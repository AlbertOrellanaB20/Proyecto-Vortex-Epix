<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    public $timestamps = false;

    // ============================================================
    //  DESCUENTO POR VENCIMIENTO PRÓXIMO  (se puede ajustar aquí)
    // ============================================================
    const DIAS_AVISO_VENCIMIENTO = 30;    // si vence dentro de 30 días o menos
    const DESCUENTO_VENCIMIENTO  = 0.20;  // se le aplica 20% de descuento

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

    // Días que faltan para vencer (negativo = ya venció, null = sin fecha)
    public function diasParaVencer(): ?int
    {
        if (!$this->fecha_vencimiento) return null;
        $venc = Carbon::parse($this->fecha_vencimiento)->startOfDay();
        $hoy  = Carbon::today('America/El_Salvador');
        return (int) round(($venc->timestamp - $hoy->timestamp) / 86400);
    }

    // ¿Está por vencer? (dentro de los próximos N días y todavía no vencido)
    public function porVencer(): bool
    {
        $d = $this->diasParaVencer();
        return $d !== null && $d >= 0 && $d <= self::DIAS_AVISO_VENCIMIENTO;
    }

    // Precio que SE COBRA: con descuento si está por vencer, si no el normal.
    public function precioVigente(): float
    {
        $precio = (float) $this->precio;
        return $this->porVencer() ? round($precio * (1 - self::DESCUENTO_VENCIMIENTO), 2) : round($precio, 2);
    }

    // Porcentaje de descuento aplicado (0 si no aplica)
    public function porcentajeDescuento(): int
    {
        return $this->porVencer() ? (int) round(self::DESCUENTO_VENCIMIENTO * 100) : 0;
    }
}
