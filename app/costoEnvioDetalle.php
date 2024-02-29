<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class costoEnvioDetalle extends Model
{
    protected $table = 'costoEnvioDetalle';
    protected $primaryKey = 'idced';
    protected $fillable =[
        'idPartesVenta',
        'compañia',
        'moneda',
        'monto',
        'servicio',
        'fecha'
    ];
}
