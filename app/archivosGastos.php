<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo de la tabla archivos_gastos, recibe la clave foranea del modelo gastos
 * @return {null}
 */
class archivosGastos extends Model
{

    protected $primaryKey = "id";
    protected $fillable = [
        'id_gasto',
        'nombre_archivo',
        'descripcion',
        'activo',
        'created_at',
        'updated_at'
        
    ];
}
