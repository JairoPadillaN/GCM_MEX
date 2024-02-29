<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class asignacionesdetalles extends Model
{
    protected $primaryKey='idAsigDet';
    protected $fillable=['idAsigDet','idFactura','fecha','porcentaje','idu'];
	protected $date=['delete_at'];

}
