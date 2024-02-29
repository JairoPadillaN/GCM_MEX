<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class paquetes extends Model
{
	use SoftDeletes; 
	protected $primaryKey = 'idPaquete';
	protected $fillable=[
	'idPaquete',
	'nombrePaquete',
	'costoPaquete',
	'activo'];
	protected $date=['delete_at'];

}
