<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class bancos extends Model
{
	use SoftDeletes; 
	protected $primaryKey = 'idb';
	protected $fillable=['idb','nombre','numeroDeCuenta','moneda','activo'];
}
