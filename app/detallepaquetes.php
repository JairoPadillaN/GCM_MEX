<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class detallepaquetes extends Model
{
    protected $primaryKey='idDetallePaquete';
    protected $fillable=[
        'idDetallePaquete',
        'idPaquete',
        'idPartesVenta',
        'idMarcaRefa',
        'idTipoRefacciones',
        'numeroParte',
        'codigo',
        'serie',
        'modelo',
        'precioVentaPe',
        'precioVentaDol',
        'apareceCoti'];
	protected $date=['delete_at'];

}
