<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class invprospectos extends Model
{
    protected $table='invprospectos';
    protected $primaryKey='idInvProspectos';
    protected $fillable=['idInvProspectos','fechaAlta','empresaSeguimiento','idu','registradoPor','idc','idSucursal','descripcionActividad','activo'];
    
}
