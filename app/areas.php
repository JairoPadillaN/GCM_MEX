<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class areas extends Model
{
 
    protected $primaryKey='idArea';
    protected $fillable=['idArea','nombreArea','activo'];
    protected $date=['delete_at'];

}
