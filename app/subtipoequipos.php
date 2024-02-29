<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class subtipoequipos extends Model
{
 
    protected $primaryKey='idSubtipoEquipo';
    protected $fillable=['idSubtipoEquipo','idTipoEquipo','subtipoEquipo','activo'];
    protected $date=['delete_at'];

}
