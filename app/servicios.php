<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class servicios extends Model
{
    protected $primaryKey='idServicio';
    protected $fillable=['idServicio','nombreServicio','activo'];
    protected $date=['delete_at'];
}
