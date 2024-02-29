<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class rangoComisiones extends Model
{
 
    protected $table='rangocomisiones';
    protected $primaryKey='id';
    protected $fillable=[
		'id',
		'rangoInicial',
        'rangoFinal',
        'porcentajeComision',
        'vigente',
        'created_at',
        'updated_at',
    ];

}
