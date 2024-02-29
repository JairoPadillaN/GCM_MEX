<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class profesiones extends Model
{
	use SoftDeletes; 
	protected $primaryKey = 'id';
	protected $fillable=[
		'id',
		'Nombre'];
	protected $date=['delete_at'];

}
