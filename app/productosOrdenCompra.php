<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class productosOrdenCompra extends Model
{
    protected $table='productosOrdenCompra';
    protected $primaryKey = 'idProducto';
    protected $fillable=['idProducto', 'idOrden','idPartesVenta','nombreRefaccion', 'codigo','precioOrden',
                        'cantidadOrden','ivaOrden','isrOrden','idFactura'];

}
