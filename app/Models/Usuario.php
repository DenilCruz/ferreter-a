<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'ci';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'ci', 'nombre', 'apellido', 'telefono', 'sexo', 'email', 'domicilio', 'tipoPersona', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Alias para que Laravel reconozca 'nombre' como 'name'
     */
    public function getNameAttribute()
    {
        return $this->nombre;
    }

    /**
     * Relación con los datos de empleado (si es tipoPersona = 'E')
     */
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'ci', 'ci');
    }
}
