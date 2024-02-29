<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class equiposEnActividad extends Model
{
	use SoftDeletes; 
	protected $table='equiposEnActividad';
	protected $primaryKey = 'idEquipoActividad';
	protected $fillable=[
		'idEquipoActividad',
		'idEquipos',
		'idSegActividad'];
	protected $date=['delete_at'];

}
