<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\clientes;
use App\contactosucursales;
use App\marcaRefaccion;
use App\partesVenta;
use App\tiporefacciones;
use App\rfqs;
use App\rfqsDetalle;
use App\sucursales;
use Carbon\Carbon;
use App\Cardex;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use DB;

class CardexController extends Controller
{
    
    public function show($idrfq)
    {
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');

     if($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == ''){
        Session::flash('error', 'Es necesario logearse antes de continuar');
        return redirect()->route('login');
     }else{   
            
    $rfq = rfqs::all();
    $cardexExists = false; // Variable para indicar si ya existe un registro en Cardex con los mismos datos

   /* foreach ($rfq as $value) {
    // Verificar si ya existe un registro en Cardex con los mismos datos
    $existingCardex = Cardex::where('idrfq', $value->idrfq)
                            ->where('idusu', $value->idu)
                            ->where('idstatusA', $value->estatus)
                            ->where('observaciones', $value->observaciones)
                            ->first();

    if ($existingCardex) {
        $cardexExists = true; // Actualizar la variable indicando que ya existe un registro
    } else {
        // Si no existe un registro en Cardex con los mismos datos, crear uno nuevo
        $cardex = new Cardex();
        $cardex->idrfq = $value->idrfq;
        $cardex->fecha = date('Y-m-d');
        $cardex->idusu = $value->idu;
        $cardex->usucam = $sname;
        $cardex->cmgr = $value->cmgrfq;
        // Obtener el último registro de Cardex correspondiente a idrfq
        $ultimoCardex = Cardex::where('idrfq', $value->idrfq)->latest()->first();

    if ($ultimoCardex) {
    // Asignar el valor anterior de idstatusA a idstatusD
    $cardex->idstatusD = $ultimoCardex->idstatusA;
    } else {
    // Si no hay registros anteriores, asignar un valor por defecto
    $cardex->idstatusD = 'Sin valor anterior';
    }

    $cardex->idstatusA = $value->estatus;
    $cardex->observaciones = $value->observaciones;
        
    $cardex->save();
    }
}*/

$rfq = rfqs::findOrFail($idrfq);
$cardex = DB::table('Cardex')
    ->join('rfqs', 'Cardex.idrfq', '=', 'rfqs.idrfq')
    ->join('usuarios', 'Cardex.idusu', '=', 'usuarios.idu')
    ->select(
        'Cardex.idcardex',
        'Cardex.created_at',
        //'rfqs.estatus as idstatusA',
        'Cardex.fecha',
        //  DB::raw('DATE_FORMAT(Cardex.fecha, "%Y-%m-%d %H:%i") as fecha_formato'),
         DB::raw('DATE_FORMAT(Cardex.fecha, "%d-%m-%Y") as fecha_formato'), 
        'Cardex.observaciones',
        'usuarios.nombreUsuario as idusu',
        'Cardex.cmgr',
        'Cardex.usucam' 
    )
    ->selectRaw("concat(usuarios.nombreUsuario, '', usuarios.aPaterno, 'usuarios.aMaterni') as nombre")
    ->orderBy('cmgr', 'ASC')
    ->get();
$cardex = $rfq->cardex;

return view('detalleCardex', compact('cardex'));
    }
         
        }
    

   // public function historial($idrfq){
     /*   $rfq = rfqs::all();
$cardexExists = false; // Variable para indicar si ya existe un registro en Cardex con los mismos datos

foreach ($rfq as $value) {
    // Verificar si ya existe un registro en Cardex con los mismos datos
    $existingCardex = Cardex::where('idrfq', $value->idrfq)
                            ->where('idusu', $value->idu)
                            ->where('idstatusA', $value->estatus)
                            ->where('observaciones', $value->observaciones)
                            ->first();

    if ($existingCardex) {
        $cardexExists = true; // Actualizar la variable indicando que ya existe un registro
    } else {
        // Si no existe un registro en Cardex con los mismos datos, crear uno nuevo
        $cardex = new Cardex();
        $cardex->idrfq = $value->idrfq;
        $cardex->fecha = date('Y-m-d');
        $cardex->idusu = $value->idu;
        $cardex->idstatusA = $value->estatus;
        $cardex->observaciones = $value->observaciones;

        $cardex->save();
    }
}

if ($cardexExists) {
    return view('detalleCardex', compact('rfq'))->with('info', 'Los datos ya existen en Cardex.');
} else {
    return view('detalleCardex', compact('rfq'))->with('success', 'Los datos se han guardado exitosamente en Cardex.');
}*/
       
 
   // }
}