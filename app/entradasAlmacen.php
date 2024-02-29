<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class entradasAlmacen extends Model
{
    protected $table='entradasAlmacen';
    protected $primaryKey = 'idEntradaAlmacen';
	protected $fillable=[
		'idu',
		'nombreUsuario',
		'fechaEntrada',
		'idProveedor',
		'recibidoPor',
		'observaciones',
	];

}