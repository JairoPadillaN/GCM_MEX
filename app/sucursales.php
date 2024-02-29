<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class sucursales extends Model
{
	use SoftDeletes; 
	protected $primaryKey = 'idSucursal';
	protected $fillable=['idSucursal','sucursal','idu',
  'contactoVentas','calle','num','giro','zonaGeografica','servicios','marcas','colonia','iniciales','correoVentas','telVentas','extenVentas',
  'contactoGerente','correoGerente','telGerente','extenGerente',
  'contactoCompras','correoCompras','telCompras','extenCompras',
  'contactoPlantel','correoPlantel','telPlantel','extenPlantel',
  'contactoAlmacen','correoAlmacen','telAlmacen','extenAlmacen',
  'nombreCuentasPC','telefonoCuentasPC','correoCuentasPC','extencionCuentasPC',
  'nombreCuentasPP','telefonoCuentasPP','correoCuentasPP','extencionCuentasPP'];
}