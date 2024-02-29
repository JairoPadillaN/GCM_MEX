<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\servicios;

class ServiciosPaginaController extends Controller
{
    public function servicios(Request $req){
        $title = 'Servicios';

        // $consult = \DB::select("SELECT pv.idpartesventa, pv.nombreweb, pv.fotoWeb, pv.caractWeb, pv.visualCostoWeb, pv.costoWeb
        // FROM partesVenta AS pv WHERE pv.presentacion = 'servicio' AND pv.visualweb = 'si' AND pv.activo = 'si'");


        return view('paginaweb.servicios')
        ->with('title', $title);
        // ->with('consulta', $consult);
    }


}