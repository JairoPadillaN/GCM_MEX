<?php

namespace App;

use Illuminate\Database\Eloquent\Model;



/**
 * Modelo de la tabla gastos, recibe la llave foranea de la tabla facturas
 * @return {null}
 */
class gastos extends Model
{
    

    protected $primaryKey = "id";
    protected $fillable = [
        'id_factura',
        'nombreGasto',
        'beneficiario',
        'BANCO',
        'idCuenta',
        'metodo_pago',
        'fecha_pago',
        'forma_pago',
        'credito_dias',
        'referencia',
        'etiquetas',
        'factura',
        'empresa',
        'iva',
        'total_iva',
        'isr',
        'total_isr',
        'total',
        'descripcion',
        'TIPOGASTO',
        'activo',
        'moneda',
        'cambioDolar',
        'created_at',
        'updated_at',
    ];
}
