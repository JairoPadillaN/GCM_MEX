<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class refaccionesEnCotizacion extends Model
{
	use SoftDeletes;
	protected $table = 'refaccionesEnCotizacion';
	protected $primaryKey = 'idRefaccionCotizacion';
	protected $fillable=[
	'idCotizacion',
	'partida',
	'idEquipos',
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
