<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class empleados extends Model
{
	use SoftDeletes; 
	protected $primaryKey = 'id';
	protected $fillable=[
		'id',
		'Correo',
		'Nombre',
		'Apellido',
		'Sexo',
		'Sueldo',
		'Profesion_id',
		'FechaNacimiento',
		'FechaRegistro'];
	protected $date=['delete_at'];

}
