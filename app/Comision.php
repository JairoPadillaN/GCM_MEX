<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Comision extends Model
{
 
    protected $table='Comisiones';
    protected $primaryKey='idc';
    protected $fillable=[
		'idc',
		'idFactura',
        'idUsuario',
        'porcentaje',
        'monto',
        'created_at',
        'updated_at',
    ];

}
