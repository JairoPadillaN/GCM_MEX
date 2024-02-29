<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class notasEntrada extends Model
{
    protected $table='notasEntrada';
    protected $primaryKey = 'idNotaEntrada';
    protected $fillable=[
        'idNotaEntrada',
        'idOrden',
        'numeroNota',
        'fechaEntrega',
        'detallesEntrega',
        'montoNotaTotal',
        'aplicaAnticipo',
        'porcentajeAnticipo',
        'cantidadAnticipo',
        'fechaPagoProgramada_total',
        'fechaPagoProgramada_anticipo',
        'fechaRealPago_total',
        'fechaRealPago_anticipo',
        'montoComplemento_total',
        'montoComplemento_anticipo',
        'idu',
        'activo'
    ];

}
