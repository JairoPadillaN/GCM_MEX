<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class tipogastos extends Model
{
  	protected $primaryKey = 'idTipoGastos';
  	protected $fillable=['idTipoGastos','nombreTipoGasto','tipo','activo'];
  }
