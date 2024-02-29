<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class estatusEntregaNotasEntrada extends Model
{
    protected $table='estatusEntregaNotasEntrada';
    protected $primaryKey = 'idEstatusEntregaNotasEntrada';
    protected $fillable=[
        'idEstatusEntregaNotasEntrada',
        'estatusEntregaNota'
    ];

}
