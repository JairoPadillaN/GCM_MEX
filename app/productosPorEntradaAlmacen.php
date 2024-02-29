<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class productosPorEntradaAlmacen extends Model
{
    protected $table='productosPorEntradaAlmacen';
    protected $primaryKey = 'idProductoEntrada';
	protected $fillable=[
		'idEntradaAlmacen',
		'idu',
		'nombreUsuario',
		'fechaEntrada',
		'idOrden',
		'idProveedor',
		'empresa',
		'almacen',
		'recibidoPor',
		'idProducto',
		'idFactura',
		'numeroParte',
		'numeroSerie',
		'modelo',
		'presentacion',
		'unidadesPorPresentacion',
		'cantidadSolicitada',
		'cantidadRecibida',
		'fotoPlaca',
		'fotoPrincipal',
		'observaciones',
		'cantidadAsignada',
		'estatusAsignacion',
	];
}