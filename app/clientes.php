<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class clientes extends Model
{
	use SoftDeletes; 
	protected $primaryKey = 'idc';
	protected $fillable=['idc','razonSocial','rfc','sucursal','numeroProveedor','contacto','fechaDePago','diasDePago','lng','lat','direccion', 'empresaPertenece',
  'contactoVentas','calle','num','colonia','estado','id','municipio','cp','correoVentas','telVentas','extenVentas',
  'contactoGerente','correoGerente','telGerente','extenGerente',
  'contactoCompras','correoCompras','telCompras','extenCompras',
  'contactoPlantel','correoPlantel','telPlantel','extenPlantel',
  'contactoAlmacen','correoAlmacen','telAlmacen','extenAlmacen',
  'nombreCuentasPC','telefonoCuentasPC','correoCuentasPC','extencionCuentasPC','nombreCuentasPP','telefonoCuentasPP','correoCuentasPP','extencionCuentasPP'];
	protected $date=['deleted_at'];
}
