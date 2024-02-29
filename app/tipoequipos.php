<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tipoequipos extends Model
{
 
    protected $primaryKey='idTipoEquipo';
    protected $fillable=['idTipoEquipo','tipoEquipo','activo'];

}
