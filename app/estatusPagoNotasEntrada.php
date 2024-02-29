<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class estatusPagoNotasEntrada extends Model
{
    protected $table='estatusPagoNotasEntrada';
    protected $primaryKey = 'idEstatusPagoNotasEntrada';
    protected $fillable=[
        'idEstatusPagoNotasEntrada',
        'estatusPagoNota'
    ];

}
