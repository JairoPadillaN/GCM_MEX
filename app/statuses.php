<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class statuses extends Model
{
	use SoftDeletes; 
	protected $primaryKey = 'idc';
	protected $fillable=['idstatuses','nombreestatus'];
	protected $date=['deleted_at'];
}
