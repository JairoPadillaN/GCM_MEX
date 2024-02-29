<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
class estados extends Model
{
    protected $primaryKey = 'id';
	protected $fillable=['id','estado'];
	protected $date=['deleted_at'];
}
