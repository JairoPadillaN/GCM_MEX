<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class seguimientoactividades extends Model
{
    use SoftDeletes; 
    protected $table='seguimientoactividades';
    protected $primaryKey='idSegActividad';
    protected $fillable=['idSegActividad',
    'idu',
    'nombreUsuario',
    'idArea',
    'fechaCreacion',
    'folio',
    'asunto',
    'idActividad',
    'fechaInicio',
    'fechaTermino',
    'horaInicio',
    'horaTermino',
    'detalleSeguimiento',
    'estadoSeguimiento',
    'importanciaSeguimiento',
    'archivo1',
    'archivo2',
    'archivo3',
    'link1',
    'link2',
    'link3',
    'activo'];
    protected $date=['deleted_at'];

}
