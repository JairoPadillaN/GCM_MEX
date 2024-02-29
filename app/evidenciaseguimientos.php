<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class evidenciaseguimientos extends Model
{
 
    protected $table='evidenciaseguimientos';
    protected $primaryKey='idEvidencia';
    protected $fillable=['idEvidencia',
    'idDetSeg',
    'idSegActividad',
    'detalleArchivo',
    'archivoEv'];
    protected $date=['delete_at'];

}
