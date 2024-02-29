<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class detalleCotizacionServicios extends Model
{
	use SoftDeletes; 
	protected $table = 'detalleCotizacionServicios';
	protected $primaryKey = 'idDetalleCotizacionServ';
	protected $fillable=[
	'idDetalleCotizacionServ',
	'idCotizacionServicios',
	'idServicioTecnico',
	'detalle',
	'numeroCotizacion',
	'notas',
	'semanasEntrega',
	'tipoMoneda',
	'montoServicioOriginal',
	'montoEquipo',
	'cantidad',
	'observacionCantidad',
	'montoFinanciamiento',
	'descuento',
	'tipoDescuento',
	'clonEquiposActivo',
	'agregadoReciente',];
	protected $date=['delete_at'];
}
