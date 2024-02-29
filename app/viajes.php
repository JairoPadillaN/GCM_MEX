<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class viajes extends Model
{
	use SoftDeletes;
	protected $primaryKey = 'idViaje';
	protected $fillable=['idViaje','fechaInicio','fechaFin',
	'idVehiculo','idu','tipoVije','montoGasto','kmActual',
	'kmFinal','idc','idSucursal','motivoViaje','fotoEvidencia','liquidoFrenos','parabrisas',
	'llantasSalida','lucesSalida','anticongelante','unidadLimpia','unidadLimpiaEntrega','observacionSalida',
	'golpes','rayaduras','llantasEntrega','lucesEntrega','observacionEntrega','estatus','activo'];
	protected $date=['delete_at'];

}
