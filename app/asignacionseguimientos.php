<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class asignacionseguimientos extends Model
{
 
    protected $table='asignacionseguimientos';
    protected $primaryKey='idAsigSeguimiento';
    protected $fillable=['idAsigSeguimiento',
    'idu',
    'idSegActividad',
    'queRealiza',
    ];
    protected $date=['delete_at'];

}
