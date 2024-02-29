<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class archivosFacturas extends Model
{
 

    protected $table='archivosFacturas';
    protected $primaryKey='idArchivoFactura';
    protected $fillable=['idArchivoFactura',
    'idFactura',
    'idu',
    'nombreUsuario',
    'tipoArchivo',
    'numeroArchivo',
    'archivoFac',
    'observacionesAF',
    ];
    protected $date=['delete_at'];

}
