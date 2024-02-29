<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class talleres extends Model
{
    protected $table='talleres';
    protected $primaryKey='idTaller';
    protected $fillable=['idTaller','nombreTaller','tipoTaller','activo'];
    protected $date=['delete_at'];
}
