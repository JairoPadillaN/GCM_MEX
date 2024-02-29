<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class refacciones extends Model
{
    protected $table='refacciones';
    protected $primaryKey='idRefaccion';
    protected $fillable=['idRefaccion','nombreRefaccion','numeroParte','serie','idProveedor','precioUltimo','precioLista','ubicacion','observaciones','fotoPlaca','fotoPrincipal','idTipoRefacciones','activo'];
    protected $date=['delete_at'];

}
