<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Archivoscontables extends Model
{
    use SoftDeletes;
    protected $table = 'Archivoscontables';
    protected $primaryKey = 'idac';
    protected $fillable =[
        'idac',
        'mes',
        'ano',
        'empresa'
    ];
}
