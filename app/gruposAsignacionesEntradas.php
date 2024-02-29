<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class gruposAsignacionesEntradas extends Model
{
    protected $table='gruposAsignacionesEntradas';
    protected $primaryKey = 'idGrupoAsignacion';
	protected $fillable=[];
}