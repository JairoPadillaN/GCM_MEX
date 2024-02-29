<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rfqsDetalle extends Model
{
    protected $table = 'rfqsDetalle';
    protected $primaryKey = 'idRfqDetalle';
    protected $fillable = [
        'idRfqDetalle',
        'idRfq',
        'idMarcaR',
        'idTipoRefa',
        'idPartesVenta',
        "tip",
        'cantidad',
        'notas',
        'foto1',
        'foto2',
        'foto3',
        'foto4',
        'foto5',
    ];
}
