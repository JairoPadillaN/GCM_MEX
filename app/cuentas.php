<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class cuentas extends Model
{
	use SoftDeletes; 
	protected $primaryKey = 'idCuenta';
	protected $fillable=[
    'idCuenta',
    'empresaCuenta',
    'nombreCuenta ',
    'numeroCu',
    'descripcionCuenta',
    'monedaCuenta',
    'saldoCuenta',
    'activo'
  ];
	protected $date=['deleted_at'];
}
