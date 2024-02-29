<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class cuentasproveedores extends Model
{
 
    protected $table='cuentasproveedores';
    protected $primaryKey='idCuentaProv';
    protected $fillable=['idCuentaProv',
    'idProveedor',
    'tipoCuenta',
    'nombreRespCuenta',
    'numeroCuentaProv',
    'clabeInterProv',
    'tipoMonedaProv',
    'bancoProv',
    'sucursal',
    'direccion',
    'observaciones'
    ];
    protected $date=['delete_at'];

}
