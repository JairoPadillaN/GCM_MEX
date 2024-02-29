<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class rfqs extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'idrfq';
    protected $fillable = [
        'idrfq',
        'idu',
        'idclie',
        'idsuc',
        'idcont',
        'ciudad',
        'cmgrfq',
        'nivelsolicitud',
        'fechasolicitud',
        'estatus',
        'fechapropuesta',
        'fechaentrega',
        'fechaestimadaentrega',
        'observaciones',        
    ];
    public function cardex()
    {
        return $this->hasMany(Cardex::class, 'idrfq')->orderBy('fecha', 'desc');
    }
}
