<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ordenCompra extends Model
{
    protected $table='ordenCompra';
    protected $primaryKey = 'idOrden';
    protected $fillable=['idOrden','idProveedor','idSucursal','tipoServicio','nombreEmpresa','fechaOrden','idPartesVenta','nombreRefaccion',
                            'codigo','precioOrden','cantidadOrden','importeOrden','ivaOrden','isrOrden','total','idFactura','folioConfirmacion'];

}
