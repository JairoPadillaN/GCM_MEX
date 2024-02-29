<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class refaccionesreparacionpartes extends Model
{
	use SoftDeletes;
	protected $table = 'refaccionesreparacionpartes';
	protected $primaryKey = 'idRefaccRepParte';
	protected $fillable=[
	'idRefaccRepParte',
	'idParte',
	'idTipoRefacciones',
	'idPartesVenta',
	'idPaquete',
	'idRefaccion',
	'tipoProducto',
	'precioPesos',
	'precioDolar',
	'apareceEnCoti'];
	protected $date=['delete_at'];

}
