<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class piezasAsignadas extends Model
{
    protected $table='piezasAsignadas';
    protected $primaryKey='idPiezaAsig';
    protected $fillable=['idPiezaAsig','fechaAsginacion','asignadoPor','tipoProducto','idMarcaRefa','idTipoRefacciones','idRefaccion','numeroFacturaProv','precioVenta',
                            'idProveedor','fechaCompra','cantidadOcupada','cantidad','idParte','idFactura','idRepCotizacion'];
    protected $date=['delete_at'];
}
