<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class movimientos extends Model

{
	use SoftDeletes;
    protected $primaryKey='idm';
    protected $fillable=['idm','fecha', 'tipo', 'razon','monto', 'detalle','idTipoGasto','idu', 'idc', 'activo'];
    protected $date=['delete_at'];
}
