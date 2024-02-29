<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchivosUnicos extends Model
{
    /* use HasFactory; */
    protected $table = 'archivosUnicos';
    protected $primarykey = 'id';
    protected $fillable = [
        'nombreArchivo',
        'descripcion',
        'fecha',
        'archivo',
        'id_usuario',
        'activo'];
    protected $guarded = [];


}
