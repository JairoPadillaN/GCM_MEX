<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class cotizaciones extends Model
{
	protected $primaryKey = 'idCotizacion';
	protected $fillable=[
		'idCotizacion',
		'idc',
		'idu',
		'idFactura',
		'fechaCotizacion',
		'idSucursal',
		'numeroCotizacion',
		'dirigidoA',
		'conCopia',
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
