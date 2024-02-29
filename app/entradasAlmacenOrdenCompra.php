<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class entradasAlmacenOrdenCompra extends Model
{
    protected $table='entradasAlmacenOrdenCompra';
    protected $primaryKey = 'idEntradaOrden';
	protected $fillable=[
		'fechaCompra',
		'idProveedor',
		'numeroFactura',
		'montoFactura',
		'recibidoPor',
		'archivoFactura',
		'moneda',
		'activo',
	];

}
