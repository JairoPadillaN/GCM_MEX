<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class usuariosacargos extends Model
{
 

    protected $primaryKey='idACargo';
    protected $fillable=['idACargo',
    'idu',
    'iduAsignado',
    ];
    protected $date=['delete_at'];

}
