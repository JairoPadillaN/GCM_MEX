<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class archivosNotaEntrada extends Model
{
    protected $table='archivosNotaEntrada';
    protected $primaryKey = 'idArchivoNotaEntrada';
    protected $fillable=[
        'idArchivoNotaEntrada',
        'idNotaEntrada',
        'esAnticipo',
        'esProveedor',
        'tipo',
        'nombreArchivo',
        'montoArchivos',
        'descripcion',
    ];

}
