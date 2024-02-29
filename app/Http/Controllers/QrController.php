<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QrController extends Controller
{
    public function make(){

    $file = public_path('codigosQr/pedritoQr.png');
    return \QRCode::text('hola Joss, eres el amor de mi vida y te amo mucho me encantas preciosa!! uuu la la!! chuladaaa de niña!!')->setOutfile($file)->png();    
    }
}
