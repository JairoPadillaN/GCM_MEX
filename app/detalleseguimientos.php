<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class detalleseguimientos extends Model
{
    protected $table='detalleseguimientos';
    protected $primaryKey='idDetSeg';
    protected $fillable=['idDetSeg',
    'idSegActividad',
    'folio',
    'fechaDet',
    'idu',
    'tipousuario',
    'detalleSeg',
    'fechaSiguiente',
    'porcentaje',
    'estado',
    'estatusAtencion',
    'resultadoSeg',
    'notasSeg',
    'propuestaSeg',
    'acuseRecibido',
    'detalleArchivo1',
    'verArchivo1',
    'archivo1',
    'detalleArchivo2',
    'verArchivo2',
    'archivo2',
    'detalleArchivo3',
    'verArchivo3',
    'archivo3',
    'detalleArchivo4',
    'verArchivo4',
    'archivo4',
    'detalleArchivo5',
    'verArchivo5',
    'archivo5',];
    protected $date=['deleted_at'];

}
