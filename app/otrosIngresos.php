<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class otrosIngresos extends Model
{
    protected $table='otrosIngresos';
    protected $primaryKey='idoi';
    protected $filleable=['idoi',
    'fecha',
    'monto',
    'moneda',
    'tipocambio',
    'tipoingreso',
    'descripcion',
    'condicion',
    'archivo1',
    'archivo2',
    'archivo3',
    'empresa',
    //'cambiom',
    'activo',];
    protected $dates = ['fecha'];
}
