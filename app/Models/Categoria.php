<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categoria';
    protected $primaryKey = 'idcategoria';
    public $timestamps = false;
    protected $fillable = ['idcategoria', 'nombre', 'descripcion', 'id_categoria_padre'];

    // Relación: Una categoría tiene muchos productos
    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_categoria', 'idcategoria');
    }

    // Relación RECURSIVA: Una categoría tiene muchas subcategorías
    public function subcategorias()
    {
        return $this->hasMany(Categoria::class, 'id_categoria_padre', 'idcategoria');
    }
}