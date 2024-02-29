<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class estatusReparaciones extends Model
{
    protected $table='estatusreparaciones';
    protected $primaryKey='idEstatus';
    protected $fillable=['idEstatus','nombreEstatus','activo'];
    protected $date=['delete_at'];
}
