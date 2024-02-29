<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class actividades extends Model
{
 
    protected $primaryKey='idActividad';
    protected $fillable=['idActividad','nombreActividad','activo'];
    protected $date=['delete_at'];

}
