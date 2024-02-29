<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class serviciosreparacionpartes extends Model
{
	use SoftDeletes;
	protected $table = 'serviciosreparacionpartes';
	protected $primaryKey = 'idServReparacionParte';
	protected $fillable=[
	'idServReparacionParte',
	'idParte',
	'idAlcance',
	'idServicioTaller',
	'tieneCotizacion',
	'precioPesos',
	'precioDolar'];
	protected $date=['delete_at'];

}
