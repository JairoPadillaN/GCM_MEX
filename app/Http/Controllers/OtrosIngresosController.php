<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\otrosIngresos;
use Carbon\Carbon;
use DB;

class OtrosIngresosController extends Controller
{
   public function reporteOtrosing(Request $request){
    $sname = Session::get('sesionname');
            $sidu = Session::get('sesionidu');
            $spat = Session::get('sesionpaterno');
            $smat = Session::get('sesionmaterno');
            $stipo = Session::get('sesiontipo');
            if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
                Session::flash('error', 'Es necesario logearse antes de continuar');
                return redirect()->route('login');
            }
            else{
                $cons = \DB::select("SELECT * FROM otrosIngresos");

                return view('reporteOtrosIngresos')
                ->with('cons', $cons);

            }

   }

   public function altaIngresos(Request $request){
    $sname = Session::get('sesionname');
            $sidu = Session::get('sesionidu');
            $spat = Session::get('sesionpaterno');
            $smat = Session::get('sesionmaterno');
            $stipo = Session::get('sesiontipo');
            if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
                Session::flash('error', 'Es necesario logearse antes de continuar');
                return redirect()->route('login');
            }
            else{
                

                return view('altaIngresos');

            }

   }

   public function guardarIngreso(Request $request){
    $fecha = $request-> fecha;
    $monto = $request-> monto;
    $moneda = $request-> moneda;
    $tipocambio = $request-> tipocambio;
    $tipoingreso = $request-> tipoingreso;
    $condicion = $request-> condicion;
    $descripcion = $request-> descripcion;
    $empresa = $request-> empresa;

    $this->validate($request, [
        "fecha" => "required",
        "monto" => "required",
        "moneda" => "required",
        "tipocambio" => "required",
        "tipoingreso" => "required",
        "condicion" => "required",
        "empresa" => "required",
    ],
    [
        "monto.required" => "Se necesita el Monto para continuar",
        "moneda.required" => "Seleccione un tipo de Moneda para continuar",
        "tipocambio.required" => "Se necesita el Tipo de Cambio para continuar",
        "tipoingreso.required" => "Se necesita el Tipo de ingreso para continuar",
        "condicion.required" => "Se necesita el campo Condición para continuar",
        "empresa.required" => "Se necesita la empresa para continuar",
    ]);

    $file = $request->file('archivo1');
    if($file != ""){
        $archivo1 = time() . "_" . $file->getClientOriginalName();
        Storage::disk('local')->put($archivo1, File::get($file));
        }else{
            $archivo1 = "Sin archivo";
        }

    $file1 = $request->file('archivo2');
    if($file1 != ""){
        $archivo2 = time() . "_" . $file1->getClientOriginalName();
        Storage::disk('local')->put($archivo2, File::get($file1));
        }else{
            $archivo2 = "Sin archivo";
        }

    $file2 = $request->file('archivo3');
    if($file2 != ""){
        $archivo3 = time() . "_" . $file2->getClientOriginalName();
        Storage::disk('local')->put($archivo3, File::get($file2));
        }else{
            $archivo3 = "Sin archivo";
        }

    $ingreso=new otrosIngresos;
    $ingreso-> fecha = $request-> fecha;
    $ingreso-> monto = $request-> monto;
    $ingreso-> moneda = $request-> moneda;
    $ingreso-> tipocambio = $request-> tipocambio;
    $ingreso-> tipoingreso = $request-> tipoingreso;
    $ingreso-> condicion = $request-> condicion;
    $ingreso-> descripcion = $request-> descripcion;
    $ingreso-> empresa = $request-> empresa;
    $ingreso-> archivo1 = $archivo1;
    $ingreso-> archivo2 = $archivo2;
    $ingreso-> archivo3 = $archivo3;
    /*if ($request->moneda == "MXN") {
        $ingreso->cambiom = 1;
    }else {
        $ingreso->cambiom = 0;
    }*/
    
    $ingreso->save();

    $proceso='Ingreso guardado correctamente';
    $mensaje='¿Desea volver al reporte?';

    return view('mensajeIngresos')
    ->with('proceso', $proceso)
    ->with('mensaje', $mensaje);
    
   }

   public function editarIngreso(Request $request, $idoi){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $spat = Session::get('sesionpaterno');
    $smat = Session::get('sesionmaterno');
    $stipo = Session::get('sesiontipo');
    if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
        Session::flash('error', 'Es necesario logearse antes de continuar');
        return redirect()->route('login');
    } else {
        $consul = otrosIngresos::Where ('idoi', '=', $idoi)->get();
        $consultaArch = \DB::select("SELECT a.archivo1, a.archivo2, a.archivo3  FROM otrosIngresos AS a Where idoi = $idoi ");

        return view('editarIngresos')
        ->with('consul', $consul[0])
        ->with('consultaArch', $consultaArch);


    }
   }

   public function modificarIngreso(Request $request){
    $idoi = $request-> idoi;//Para saber cual guarda
    $fecha = $request-> fecha;
    $monto = $request-> monto;
    $moneda = $request-> moneda;
    $tipocambio = $request-> tipocambio;
    $tipoingreso = $request-> tipoingreso; 
    $condicion = $request-> condicion;
    $descripcion = $request-> descripcion;
    $empresa = $request-> empresa;

    $this->validate($request,[
        "fecha"=>"required",
        "monto"=>"required",
        "moneda"=>"required",
        "tipocambio"=>"required",
        "tipoingreso"=>"required",
        "condicion"=>"required",
        "empresa"=>"required",
    ],
    [
        "monto.required" => "Se necesita el campo monto para continuar",
        "moneda.required" => "Seleccione un tipo moneda para continuar",
        "tipocambio.required" => "Se necesita el campo onto para continuar",
        "tipoingreso.required" => "Se necesita el campo Monto para continuar",
        "condicion.required" => "Se necesita el campo Monto para continuar",
    ]);

    $file = $request->file('archivo1');
    if($file != ""){
        $archivo1 = time() . "_" . $file->getClientOriginalName();
        Storage::disk('local')->put($archivo1, File::get($file));
        }else{
            $archivo1 = "Sin archivo";
        }
        $file1 = $request->file('archivo2');
        if($file1 != ""){
            $archivo2 = time() . "_" . $file1->getClientOriginalName();
            Storage::disk('local')->put($archivo2, File::get($file1));
            }else{
                $archivo2 = "Sin archivo";
            }
    
        $file2 = $request->file('archivo3');
        if($file2 != ""){
            $archivo3 = time() . "_" . $file2->getClientOriginalName();
            Storage::disk('local')->put($archivo3, File::get($file2));
            }else{
                $archivo3 = "Sin archivo";
            }
    
        
             // Obtener los nombres de los archivos actuales
    $consultaArch = \DB::select("SELECT archivo1, archivo2, archivo3 FROM otrosIngresos WHERE idoi = $idoi");

    // Actualizar el archivo 1
    if ($request->hasFile('archivo1')) {
        $archivo1 = time() . "_" . $request->file('archivo1')->getClientOriginalName();
        Storage::disk('local')->put($archivo1, File::get($request->file('archivo1')));
    } else {
        $archivo1 = $consultaArch[0]->archivo1; // Conservar el archivo actual
    }

    // Actualizar el archivo 2
    if ($request->hasFile('archivo2')) {
        $archivo2 = time() . "_" . $request->file('archivo2')->getClientOriginalName();
        Storage::disk('local')->put($archivo2, File::get($request->file('archivo2')));
    } else {
        $archivo2 = $consultaArch[0]->archivo2; // Conservar el archivo actual
    }

    // Actualizar el archivo 3
    if ($request->hasFile('archivo3')) {
        $archivo3 = time() . "_" . $request->file('archivo3')->getClientOriginalName();
        Storage::disk('local')->put($archivo3, File::get($request->file('archivo3')));
    } else {
        $archivo3 = $consultaArch[0]->archivo3; // Conservar el archivo actual
    }


    // Actualizar los registros en la base de datos
        $modif = otrosIngresos::find($idoi);
        $modif-> fecha = $request-> fecha;
        $modif-> monto = $request-> monto;
        $modif-> moneda = $request-> moneda;
        $modif-> tipocambio = $request-> tipocambio;
        $modif-> tipoingreso = $request-> tipoingreso;
        $modif-> condicion = $request-> condicion;
        $modif-> descripcion = $request-> descripcion;
        $modif-> empresa = $request-> empresa;
        $modif-> archivo1 = $archivo1;
        $modif-> archivo2 = $archivo2;
        $modif-> archivo3 = $archivo3;
        /*if ($request->moneda == "MXN") {
            $modif->cambiom = 1;
        }else {
            $modif->cambiom = 0;
        }*/
        $modif->save();


    $proceso='Editado y guardado correctamente';
    $mensaje='¿Desea volver al reporte?';

    return view('mensajeIngresos')
    ->with('proceso', $proceso)
    ->with('mensaje', $mensaje);
   }

public function eliminarIngreso($idoi){
    $ingre = \DB::UPDATE("UPDATE otrosIngresos SET activo='No' WHERE idoi=$idoi");

    return redirect('reporteOtrosing');
}

public function restaurarIngreso($idoi){
    $ingre = \DB::UPDATE("UPDATE otrosIngresos SET activo='Si' WHERE idoi=$idoi");

    return redirect('reporteOtrosing');
}


}
