<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tiporefacciones extends Model
{
    protected $table='tiporefacciones';
    protected $primaryKey='idTipoRefacciones';
    protected $fillable=['idTipoRefacciones','nombreTipoRefaccion','activo'];
    protected $date=['delete_at'];

}
