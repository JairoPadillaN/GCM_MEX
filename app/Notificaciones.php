<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notificaciones extends Model
{
    protected $table = 'Notificaciones';
    protected $primaryKey = 'idnot';
    protected $fillable = [
        'idnot',
        'fecha',
        'accion',
        'mensaje',
        'visto', 
        'idumanda',
        'idurecibe'
    ];
}
