<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'ci';
    public $timestamps = false;
    protected $fillable = ['ci', 'nombre', 'apellido', 'telefono', 'sexo', 'correo', 'domicilio', 'tipoPersona'];
}
