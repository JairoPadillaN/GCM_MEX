<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\usuarios;
use App\Archivoscontables;
use App\ArchivoscontablesDetalle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use DB;

class ArchivoscontableController extends Controller
{
    public function archivosContab()
    {
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {

            $consultacion = \DB::select("SELECT ac.idac,ac.mes,ac.ano,ac.empresa,cuantosporcategoria(ac.idac) AS archivos, ac.deleted_at FROM Archivoscontables AS ac");

            $consulta = \DB::select("SELECT * FROM Archivoscontables");
            $cuantos = count($consulta);
            return view('archivosContab')
                ->with('consulta', $consulta)
                ->with('consultacion', $consultacion)
                ->with('cuantos', $cuantos);
        }
    }


    public function altaContable()
    {
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {

            return view('altaContable');
        }
    }

    public function guardarContable(Request $request)
    {
        $this->validate($request, [
            "mes" => "required",
            "ano" => "required",
            "empresa" => "required",
        ], [
            "mes.required" => "El campo Mes es requerido",
            "año.required" => "El campo Año es requerido",
            "empresa.required" => "El campo Empresa es requerido",
        ]);

        $contable = new Archivoscontables();
        $contable->mes = $request->mes;
        $contable->ano = $request->ano;
        $contable->empresa = $request->empresa;
        $contable->save();

        return redirect()->route("archivosContab");
    }

    public function desactivarCont($idac)
    {
        $contables = Archivoscontables::find($idac);
        $contables->delete();

        return redirect()->route("archivosContab");
    }

    public function activarCont($idac)
    {
        $contable = Archivoscontables::withTrashed()->where('idac', $idac)->restore();
        return redirect()->route("archivosContab");
    }

    public function altaArchivos($idac)
    {
        //  return $idac;
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {
            $consultar = \DB::select("select  * from Archivoscontables
            where idac = $idac");

            $consultarchivos = \DB::select("select *  from ArchivoscontablesDetalle where idac = $idac");
            $cuantos = count($consultarchivos);
            
            //dd($consultarchivos);

            return view('detalleContable')
                ->with('consultar', $consultar[0])
                ->with('consultarchivos', $consultarchivos)                
                ->with('cuantos', $cuantos);
        }
    }
    public function guardarArchivos(Request $request)
    {

        $idac = $request->idac;

        $file = $request->file('archivo');
        //obtenemos el nombre del archivo
        if ($file != "") {
            $archivo =  time() . "_" . $file->getClientOriginalName();
            //indicamos que queremos guardar un nuevo archivo
            Storage::disk('local')->put($archivo,  File::get($file));
        } else {
            $archivo = "Sin archivo";
        }

        $archivos = new ArchivoscontablesDetalle();
        $archivos->idac = $request->idac;
        $archivos->fecha = $request->fecha;
        $archivos->iduser = $request->iduser;
        $archivos->nombre = $request->nombre;
        $archivos->descripcion = $request->descripcion;
        $archivos->archivo = $archivo;
        $archivos->activo = 'Si';
        $archivos->save();

        $consultarchivos = \DB::select("SELECT * FROM ArchivoscontablesDetalle WHERE idac = $idac");

        return view('detalleContableDos')
            ->with('consultarchivos', $consultarchivos);
    }


    public function editarContable(Request $request)
    {
        $idac = $request->idac;
        $mes = $request->mes;
        $ano = $request->ano;
        $empresa = $request->empresa;

        $contables = Archivoscontables::find($idac);
        $contables->mes = $request->mes;
        $contables->ano = $request->ano;
        $contables->empresa = $request->empresa;
        $contables->save();

        $consultar = \DB::select("select  * from Archivoscontables
        where idac = $idac");

        $consultarchivos = \DB::select("select *  from ArchivoscontablesDetalle where idac = $idac");
        $cuantos = count($consultarchivos);


        return view("detalleContable")
            ->with('consultar', $consultar[0])
            ->with('cuantos', $cuantos)
            ->with('consultarchivos', $consultarchivos);
    }

    public function  borrarDetalle(Request $request)
    {
        $idac = $request->idac;
        $idacd = $request->idacd;
        $detalle = ArchivoscontablesDetalle::find($idacd);

        if ($detalle) {
            $detalle->activo = 'No';
            $detalle->save();
        }

        $consultarchivos = \DB::select("SELECT * FROM ArchivoscontablesDetalle WHERE idac = $idac");
        return view('detalleContableDos')
            ->with('consultarchivos', $consultarchivos);
    }

    public function reporteDetalle(Request $request)
    {
        $idac = $request->idac;        
        $consultarchivos = \DB::select("select *  from ArchivoscontablesDetalle where idac = $idac");     
        $cuantos = count($consultarchivos);        
        return view('reporteDetalle')
            ->with('consultarchivos', $consultarchivos)
            ->with('cuantos', $cuantos);
    }
}
