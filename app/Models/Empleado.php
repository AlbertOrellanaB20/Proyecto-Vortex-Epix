<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Empleado extends Authenticatable
{
    use Notifiable;

    /**
     * Tabla y llave primaria personalizadas.
     */
    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado';

    /**
     * Campos asignables masivamente.
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'usuario',
        'password',
        'cargo',
        'correo',
        'telefono',
    ];

    /**
     * Campos ocultos en la serialización.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts. 'hashed' aplica bcrypt automáticamente al asignar la contraseña.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Nombre completo del empleado (atributo de conveniencia).
     */
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }
}
