<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class detalleNotasEntrada extends Model
{
    protected $table='detalleNotasEntrada';
    protected $primaryKey = 'idDetalleNotasEntrada';
    protected $fillable=[
        'idDetalleNotasEntrada',
        'idNotaEntrada',
        'idProducto',
        'piezasPendientes',
        'piezasRecibidas',
        'subtotal',
        'iva',
        'total'
    ];

}
