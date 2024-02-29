<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchivoscontablesDetalle extends Model
{
    protected $table = 'ArchivoscontablesDetalle';
    protected $primaryKey = 'idacd';
    protected $fillable =[
        'idacd',
        'idac',
        'fecha',
        'iduser',
        'nombre',
        'descripcion',
        'archivo'
    ];
}
