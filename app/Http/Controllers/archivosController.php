<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;

class ImgController extends Controller
{
	
  public function automatizacion(){
  
			
			return view ('automatizacion');
}

	public function portada(){
  
			
			return view ('portada');
}

	public function ventas(){
  
			
			return view ('ventas');
}

	public function husky(){
  
			
			return view ('husky');
}
	public function productos2(){
  
			
			return view ('productos2');
}
}
