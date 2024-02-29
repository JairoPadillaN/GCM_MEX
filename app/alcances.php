<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class alcances extends Model
{
	use SoftDeletes; 
	protected $primaryKey = 'idAlcance';
	protected $fillable=['idAlcance','alcance','activo'];
	protected $date=['delete_at'];

}
