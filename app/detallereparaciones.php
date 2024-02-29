<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class detallereparaciones extends Model
{
	use SoftDeletes; 
	protected $table = 'detallereparaciones';
	protected $primaryKey = 'idRepCotizacion';
	protected $fillable=['idRepCotizacion',
	'idCotizacion',
	'partida',
	'tipoCotizacion',
	'idEquipos',
	'descripcion',
	'skuEquivalente',
	'verSku',
	'modelo',
	'estatusAnteriorEquipo',
	'numeroCotizacion',
	'notas',
	'piezas',
	'semanasEntrega',
	'tipoMoneda',
	'precioVentaOriginal',
	'montoEquipo',
	'montoFinanciamiento',
	'tipoDescuento',
	'descuento',	
	'clonEquiposActivo',
	'piezasOcupadas',
	'agregadoReciente',];
	protected $date=['delete_at'];

}
