<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class cotizacionServicios extends Model
{
	protected $table = 'cotizacionServicios';
	protected $primaryKey = 'idCotizacionServicios';
	protected $fillable=[
		'idCotizacionServicios',
		'idc',
		'idu',
		'idFactura',
		'fechaCotizacion',
		'idSucursal',
		'numeroCotizacion',
		'dirigidoA',
		'conCopia',
		'estatusCotizacionAnterior',
		'estatus',
		'archivoCotizacion',
		'noEquipos',
		'montoTotal',
		'montoFinanciamiento',
		'tipoDescuento',
		'descuento',
		'tipoMoneda',
		'cambio',
		'cotizacionOriginal',
		'numConsecutivo',
		'numCotizacion',
		'clonActual',
		'activo',
		'check1',
		'textoCheck1',
		'check2',
		'textoCheck2',
		'check3',
		'textoCheck3',
		'check4',
		'textoCheck4',
		'check5',
		'textoCheck5',
		'check6',
		'textoCheck6',
		'check7',
		'textoCheck7',
	];
}
