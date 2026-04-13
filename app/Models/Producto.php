<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'idproducto';
    public $timestamps = false;
    // Esto permite que Laravel inserte datos en estos campos
    protected $fillable = ['idproducto', 'nombre', 'descripcion', 'precio', 'cantidad', 'id_categoria', 'id_marca'];

    public function categoria()
    {
    // Un producto pertenece a una categoría
    return $this->belongsTo(Categoria::class, 'id_categoria', 'idcategoria');
    }
}