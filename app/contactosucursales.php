<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class contactosucursales extends Model
{
    protected $primaryKey='idContactoSuc';
    protected $fillable=[
        'idContactoSuc',
        'idSucursal',
        'idc',
        'puesto',
        'nombreCont',
        'telefonoCont',
        'correoCont',
        'extension',
        'whatsapp',
        'contactoAdicional'];
	protected $date=['delete_at'];

}
