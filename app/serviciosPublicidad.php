<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class serviciosPublicidad extends Model
{
	use SoftDeletes;
	protected $table = 'serviciosPublicidad';
	protected $primaryKey = 'idServPublicidad';
	protected $fillable=[
		'idServPublicidad',
		'fechaAlta',
		'empresaSeguimiento',
		'idu',
		'idc',
		'idSucursal',
		'contacto',
		'tipoPublicidad',
		'descripcionActividad',
		'contesto',
		'estatus',
		'activo'
	];
	protected $date=['delete_at'];

}
