<?php

namespace App\Http\Controllers;

use App\ArchivosUnicos;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ArchivosUnicosController extends Controller
{
    /**Creamos una instancia nueva del controlador 
     * para verificar que el usuario este logueado */
/*     public function __construct() { 
        $this->middleware('auth'); 
    } */

    public function index(){

        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {

            $archivosUnicos = \DB::select("SELECT * FROM archivosUnicos WHERE activo = 1");

            return view('archivosUnicos2', compact('archivosUnicos',$archivosUnicos));
        }

    }

    
    public function AltaArchivoUnico(){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {

            return view('archivosUnicosAlta');
        }
    }

    public function GuardarArchivoUnico(Request $request){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {

            date_default_timezone_set('America/Mexico_City');
            $usuario = $sname." ".$spat." ".$smat;
            $file = $request->file('archivo');

            if ($file != "") {
                $archivo =  time() . "_" . $file->getClientOriginalName();
                
                Storage::disk('local')->put($archivo,  File::get($file));

                $archivosUnicos = new ArchivosUnicos();
                $archivosUnicos -> nombreArchivo = $request -> nombreArchivo;
                $archivosUnicos -> descripcion = $request -> descripcion;
                $archivosUnicos -> fecha = $request-> fecha;
                $archivosUnicos -> archivo = $archivo;
                $archivosUnicos -> id_usuario = $usuario;
                $archivosUnicos -> created_at = \Carbon\Carbon::now()->setTimezone('America/Mexico_City');
                $archivosUnicos -> updated_at = \Carbon\Carbon::now()->setTimezone('America/Mexico_City');
                $archivosUnicos -> deleted_at = \Carbon\Carbon::now()->setTimezone('America/Mexico_City');
                $archivosUnicos -> save();

                return redirect()->route('archivosUnicos')->with('success','Archivo guardado correctamente.')->with('message','Archivo Guardado Correctamente');
            } else {
                return redirect()->route('archivosUnicos')->with('error','El archivo no pudo guardarse.')->with('message','El archivo no pudo guardarse');
            }
        }
    }

    public function reporteArchivoUnico(Request $request){
        $idArchivo = $request->id;
        $consultarArchivoUnico = \DB::select("SELECT * FROM archivosUnicos where id = $idArchivo");
        return view('archivoUnicoPrevia')  -> with ('consultarArchivoUnico',$consultarArchivoUnico);
    }

    public function editarArUn(Request $request){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {
            $idArchivo = $request -> id;
            dd($idArchivo);
            $U = \DB::select("SELECT * FROM archivosUnicos WHERE id = $idArchivo");
            return view('archivosUnicosModificar') -> with('consultarArchivoUnico', $U);
        }
    }

    public function editarArchivoUnico(Request $request) {
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {
            date_default_timezone_set('America/Mexico_City');
/*             $file = $request->file('archivo');

            if ($file != "") {
                $archivo =  time() . "_" . $file->getClientOriginalName();
                
                Storage::disk('local')->put($archivo,  File::get($file));

                $id = $request ->id;

                $archivosUnicos =  ArchivosUnicos::find($id);
                $archivosUnicos -> nombreArchivo = $request -> nombreArchivo;
                $archivosUnicos -> descripcion = $request -> descripcion;
                $archivosUnicos -> fecha = $request-> fecha;
                $archivosUnicos -> archivo = $archivo;
                $archivosUnicos -> updated_at = \Carbon\Carbon::now()->setTimezone('America/Mexico_City');
                $archivosUnicos -> update();

                return redirect()->route('archivosUnicos')->with('success','Archivo editado correctamente.')->with('message','Archivo Editado Correctamente');
            } else {
                return redirect()->route('archivosUnicos')->with('error','El archivo no fue editado.')->with('message','El archivo no fue editado');
            } */

            $archivo = $request->file('archivo');

            if ($archivo) {
                // Si se ha subido un nuevo archivo
                $nombreArchivo = time() . "_" . $archivo->getClientOriginalName();
                Storage::disk('local')->put($nombreArchivo, File::get($archivo));

                // Actualizar la base de datos con el nuevo archivo
                /* $archivosUnicos->archivo = $nombreArchivo; */

                $id = $request ->id;
                // Resto de la lógica para actualizar otros campos
                $archivosUnicos =  ArchivosUnicos::find($id);
                $archivosUnicos->nombreArchivo = $request->nombreArchivo;
                $archivosUnicos->descripcion = $request->descripcion;
                $archivosUnicos->fecha = $request->fecha;
                $archivosUnicos->archivo = $nombreArchivo;
                $archivosUnicos->updated_at = now()->setTimezone('America/Mexico_City');
                $archivosUnicos->update();
    
                return redirect()->route('archivosUnicos')->with('success', 'Archivo editado correctamente.')->with('message', 'Archivo Editado Correctamente');
    
            } else {

                $id = $request ->id;
                // Resto de la lógica para actualizar otros campos
                $archivosUnicos =  ArchivosUnicos::find($id);
                $archivosUnicos->nombreArchivo = $request->nombreArchivo;
                $archivosUnicos->descripcion = $request->descripcion;
                $archivosUnicos->fecha = $request->fecha;     
                $archivosUnicos->updated_at = now()->setTimezone('America/Mexico_City');
                $archivosUnicos->update();
    
                return redirect()->route('archivosUnicos')->with('success', 'Archivo editado correctamente.')->with('message', 'Archivo Editado Correctamente');
    
            }

        
        }
    }

    public function desactivarArchivoUnico($id) {

        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {

                $archivosUnicos =  ArchivosUnicos::find($id);
                $archivosUnicos -> activo = '0';
                $archivosUnicos -> updated_at = \Carbon\Carbon::now()->setTimezone('America/Mexico_City');
                $archivosUnicos -> deleted_at = \Carbon\Carbon::now()->setTimezone('America/Mexico_City');
                $archivosUnicos -> update();

                return redirect()->route('archivosUnicos')->with('status','Archivo eliminado correctamente.')->with('message','Archivo Eliminado Correctamente');            
        
        }
    }

    public function obtenerArchivoUnico($id) {

        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {

                $archivosUnicos = \DB::select("SELECT * FROM archivosUnicos WHERE id = $id");
                

                return view('archivosUnicosModificar')->with('archivosUnicos',$archivosUnicos);
        
        }
    }

 
}
