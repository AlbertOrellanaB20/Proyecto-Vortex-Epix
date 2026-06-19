<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'apellido', 'correo', 'codigo_cliente',
        'telefono', 'direccion', 'puntos', 'nivel_fidelidad',
    ];

    // ============================================================
    //  ECONOMÍA DE PUNTOS (Santiago)
    //  Estos dos números definen TODO el programa de fidelidad.
    //  Se pueden ajustar aquí sin tocar nada más.
    // ============================================================

    // Cuántos puntos gana el cliente por cada $1 de compra.
    // (1 = gana 1 punto por dólar. Si quieres que sea más exigente,
    //  baja este número, por ejemplo 0.5 = 1 punto por cada $2.)
    const PUNTOS_POR_DOLAR = 1;

    // Cuántos puntos se necesitan para $1 de descuento.
    // (100 = cada punto vale $0.01, o sea el supermercado devuelve
    //  solo el 1% de la compra: rentable y realista.)
    const PUNTOS_PARA_UN_DOLAR = 100;

    // Calcula cuántos puntos gana una compra (redondea hacia abajo).
    public static function puntosPorCompra($monto): int
    {
        return (int) floor($monto * self::PUNTOS_POR_DOLAR);
    }

    // Devuelve el valor en dólares de los puntos actuales del cliente.
    public function valorDescuento(): float
    {
        return round($this->puntos / self::PUNTOS_PARA_UN_DOLAR, 2);
    }

    // Nivel de fidelidad según los puntos acumulados.
    public static function nivelPorPuntos($puntos)
    {
        if ($puntos >= 3000) return 'Diamante';
        if ($puntos >= 1500) return 'Oro';
        if ($puntos >= 500)  return 'Plata';
        return 'Bronce';
    }
}
