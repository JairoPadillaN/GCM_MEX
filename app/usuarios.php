<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class usuarios extends Model
{
	protected $primaryKey = 'idu';
	protected $fillable=['idu','nombreUsuario','aPaterno','aMaterno','correo','password','imagen','tipo','empresaSeguimiento',
  'calle','numero','colonia','municipio','estado','telCasa','telCel','licencia','activo'];
}
