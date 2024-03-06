<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vendedoresAsignadosServicios extends Model
{
    protected $table = 'vendedoresAsignadosServicios';
    protected $primaryKey = 'idvas';
    protected $fillable =[
        'idvend',
        'idser',
        'porcentaje'
    ];

    public $timestamps = false;
}
