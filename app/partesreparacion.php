<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class partesreparacion extends Model
{
	use SoftDeletes;
	protected $table = 'partesreparacion';
	protected $primaryKey = 'idParte';
	protected $fillable=['idParte',
	'idEquipos',
	'GCMid',
	'GCMidParte',
	'numParte',
	'nombreParte',
	'fechaSalida',
	'queReparacion',
	'fechaRecepcion',
	'fechaEntrega',
	'ordenCompra',
	'archivoOrdenCompra',
	'montoReparacion',
	'tipoMoneda',
	'idTaller',
	'quienllevo',
	'quienRecibio',
	'prioridadRep',
	'fotoParte',
	'idEstatus',
	'seguimientoTecnico'];
	protected $date=['delete_at'];

}
