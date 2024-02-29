<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class vehiculos extends Model
{
	use SoftDeletes;
	protected $primaryKey = 'idVehiculo';
	protected $fillable=['idVehiculo','nombreVehiculo','tipoVehiculo','kmActual','serial','placas',
	'toneladas','motor','transmision','empresaAseguradora','numPoliza','descripcionPoliza','idMarca','activo'];
	protected $date=['delete_at'];

}
