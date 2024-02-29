<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class proveedores extends Model
{
    protected $table='proveedores';
    protected $primaryKey='idProveedor';
    protected $fillable=['idProveedor',
    'tituloProv',
    'nombreProv',
    'segundoNomProv',
    'apellidoProv',
    'razonSocialProv',
    'rfcProveedor',
    'calleProv',
    'ciudadProv',
    'codigoPostalProv',
    'estadoProv',
    'paisProv',
    'notasProv',
    'usuarioProv',
    'passwordProv',
    'correoProveedor',
    'telefonoProveedor',
    'movilProv',
    'faxProv',
    'otroProv',
    'sitioWebProv',
    'condicionesProv',
    'contactoProveedor',
    'diasPago',
    'diasVigencia',
    'archivoCuentas',
    'activo'];
    protected $date=['delete_at'];
}
