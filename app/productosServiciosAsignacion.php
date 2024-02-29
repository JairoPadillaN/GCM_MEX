<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class productosServiciosAsignacion extends Model
{
    protected $table='productosServiciosAsignacion';
    protected $primaryKey = 'idProductoServAsig';
	protected $fillable=[
		'idGrupoAsignacion',
		'idOrden',
		'idProducto',
		'idFactura',
		'cantidadEnAlmacen',
		'cantidadAservicio',
		'fechaAsignacion',
		'quienLLevo',
		'detalles',
		'evidencia',
	];
}