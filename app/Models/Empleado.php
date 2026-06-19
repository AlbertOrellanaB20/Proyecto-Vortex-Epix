<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Empleado extends Authenticatable
{
    use Notifiable;

    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado';
    public $timestamps = false; // la tabla empleados NO tiene created_at / updated_at

    protected $fillable = [
        'nombre', 'apellido', 'usuario', 'password', 'cargo', 'correo', 'telefono',
        'estado', 'departamento', 'salario', 'fecha_contratacion',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }
}
