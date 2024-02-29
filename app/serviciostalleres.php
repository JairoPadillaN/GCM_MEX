<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class serviciostalleres extends Model
{
    protected $table='serviciostalleres';
    protected $primaryKey='idServicioTaller';
    protected $fillable=[
        'idServicioTaller',
        'idAlcance',
        'nombreServTaller',
        'costoInterno',
        'costoExterno',
        'activo'];
    protected $date=['delete_at'];

}
