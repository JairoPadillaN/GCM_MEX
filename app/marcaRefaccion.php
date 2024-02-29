<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class marcaRefaccion extends Model
{
    protected $table='marcasRefaccion';
    protected $primaryKey='idMarcaRefa';
    protected $fillable=['idMarcaRefa','marcaRefaccion','activo'];
    protected $date=['delete_at'];

}
