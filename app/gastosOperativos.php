<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class gastosOperativos extends Model
{
    protected $table = "gastosOperativos";
    protected $primaryKey = "id";
    protected $fillable = [
        'nombreGasto',
        'beneficiario',
        'idCuenta',
        'metodo_pago',
        'fecha_pago',
        'forma_pago',
        'credito_dias',
        'referencia',
        'etiquetas',
        'factura',
        'iva',
        'total_iva',
        'isr',
        'total_isr',
        'total',
        'descripcion',
        'activo',
        'created_at',
        'updated_at',
    ];
}
