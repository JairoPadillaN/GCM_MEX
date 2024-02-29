<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class datosPagoServicios extends Model
{
 

    protected $table='datosPagoServicios';
    protected $primaryKey='idDatosPago';
    protected $fillable=['idDatosPago',
    'idFactura',
    'numeroFacturaDP',
    'tipoCambioFac',
    'saldoFac',
    'montoPesosDP',
    'tipoCambioPagado',
    'montoTotal',
    'observaciones',
    'montoReal',
    'ivaFinal',
    'subtotalFinal',
    'ivaFac',
    'subtotalFac',
    'difCambiaria',
    'fechaDatosPago'
    ];
    protected $dates = ['delete_at',
    'fechaDatosPago'];
}
