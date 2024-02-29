<?php

namespace App;

use App\rfqs;
use App\usuarios;
use Illuminate\Database\Eloquent\Model;

class Cardex extends Model
{
    protected $table = 'Cardex';
protected $primaryKey = 'idcardex';

protected $fillable =[
    'idrfq',
    'cmgr',
    'fecha',
    'idusu',
    'usucam',
    'idstatusA',
    'idstatusD',
    'observaciones',
];

/**
 * Summary of rfq
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
 */
public function rfq()
{
    return $this->belongsTo(rfqs::class, 'idrfq');
}

public function idusu()
{
    return $this->belongsTo(usuarios::class, 'idusu');
}

}
