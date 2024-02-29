<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class serviciosTecnicos extends Model
{
	protected $table = 'serviciosTecnicos';
	protected $primaryKey = 'idServicioTecnico';
	protected $fillable=['idServicioTecnico',
	'servicioTecnico',
	'claveServicio',
	'detalleServicio',	
	'montoPesos',
	'montoDolares',
	'unidadMedida',
	'activo'];
}
