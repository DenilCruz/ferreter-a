<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//modelo comunicador de la bse de datos al sistema del caso de uso gestionar proveedor c3 - ferreteria ciclo 3 - si1
class NotaCompra extends Model
{
    protected $table = 'notacompra';
    protected $primaryKey = 'nro';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'nro',
        'fecha',
        'total',
        'ci_proveedor',
        'id_pago'
    ];

    /**
     * Relación con el proveedor
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'ci_proveedor', 'ci');
    }

    /**
     * Relación con el método de pago
     */
    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'id_pago', 'id');
    }

    /**
     * Detalles de la nota de compra
     */
    public function detalles()
    {
        return $this->hasMany(DetalleNotaCompra::class, 'nro_factura', 'nro');
    }
}
