<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class citas extends Model
{
    protected $table='citas';
    protected $primaryKey='idCita';
    protected $fillable=['idCita','fechaAlta','empresaSeguimiento','idu','registradoPor','idc','idSucursal','contacto',
    'fechaCita', 'hora', 'lugar', 'responsable', 'observacionCita', 'fechaReal', 'resultados', 'serviciosOfrecer', 'marcas','estatus','activo'];
    
}