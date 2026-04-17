<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'rol';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['nombre', 'descripcion'];

    public function asignaciones()
    {
        return $this->hasMany(EstadoRol::class, 'id_rol', 'id');
    }
}
