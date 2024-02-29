<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class asignaciones extends Model
{
    protected $primaryKey='idFacturaAsig';
    protected $fillable=['idFacturaAsig','numeroFactura','fecha'];
	protected $date=['delete_at'];

}
