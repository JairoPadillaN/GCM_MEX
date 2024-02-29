<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class partesVenta extends Model
{
    protected $table='partesVenta';
    protected $primaryKey='idPartesVenta';
    protected $fillable=['idPartesVenta','nombreRefaccion','numeroParte','modelo','codigo','presentacion','unidades','precioCompra','precioVenta',
                            'ubicacion','estatus','tipoProducto','cantidad','activo','idMarcaRefa','idTipoRefacciones','parapaquete'];
    protected $date=['delete_at'];

}
