<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class marcas extends Model
{
 
    protected $primaryKey='idMarca';
    protected $fillable=['idMarca','nombreMarca','activo'];
    protected $date=['delete_at'];

}
