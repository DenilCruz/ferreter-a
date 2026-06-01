<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// modelo comunicador de la bse de datos al sistema del caso de uso gestionar proveedor c3 - ferreteria ciclo 3 - si1
class DetalleNotaCompra extends Model
{
    protected $table = 'detallenotacompra';
    
    // Al ser una tabla de detalles con clave compuesta, deshabilitamos la clave primaria por defecto
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'nro_factura',
        'id_producto',
        'precio_unitario',
        'cantidad'
    ];

    /**
     * Relación con la nota de compra principal
     */
    public function compra()
    {
        return $this->belongsTo(NotaCompra::class, 'nro_factura', 'nro');
    }

    /**
     * Relación con el producto adquirido
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'idproducto');
    }
}
