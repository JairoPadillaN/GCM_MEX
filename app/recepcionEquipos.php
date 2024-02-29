<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class recepcionEquipos extends Model
{
	use SoftDeletes;
	protected $table = 'recepcionEquipos';
	protected $primaryKey = 'idEquipos';
	protected $fillable=['idEquipos',
	'folioRecepcion',
	'idu',
	'nombreEmpresa',
	'fechaRecoleccion',
	'fechaRegistro',
	'idc',
	'idSucursal',
	'personaEntrega',
	'personaRecibe',
	'numeroDocumentoSalida',
	'ubicacionEquipo',
	'archivoValeSalida',
	'archivoDocumentoSalida',
	'GCMid',
	'complementoGCMid',
	'idTipoEquipo',
	'idSubtipoEquipo',
	'serie',
	'datosCertificado',
	'servicio',
	'marca',
	'modelo',
	'estatus',
	'estatusEntrega',
	'recurso',
	'descripcionFalla',
	'vistaSuperior',
	'vistaFrente',
	'vistaTrasera',
	'lateralIzquierda',
	'lateralDerecha',
	'placa_1',
	'placa_2',
	'textoQr',
	'testRunInicial',
	'testRunFinal',
	'feedbackInicial',
	'feedbackFinal',
	'balerosInicial',
	'balerosFinal',
	'gearBoxInicial',
	'gearBoxFinal',
	'imanesInicial',
	'imanesFinal',
	'debanadosInicial',
	'debanadosFinal',
	'encoderInicial',
	'encoderFinal',
	'corrienteVer',
	'corrienteHor',
	'corrienteAx',
	'tensionVer',
	'tensionHor',
	'tensionAx',
	'tempMotor',
	'tempTransmision',
	'vibracionVer',
	'vibracionHor',
	'vibracionAx',
	'enfriamientoVer',
	'enfriamientoHor',
	'enfriamientoAx',
	'activo'];
	protected $date=['delete_at'];

}
