<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\estatusReparaciones;
class estatusReparacionesController extends Controller
{
  public function altaEstatusReparaciones(){
    $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

        if($sname == '' or $sidu =='' or $stipo=='')
        {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else
        {
			$consulta= estatusreparaciones::orderby('idEstatus','desc')
               ->take(1)
               ->get();
			return view ('altaEstatusReparaciones');
}
}

public function guardarEstatusReparaciones(Request $request)
{
        $nombreEstatus=$request->nombreEstatus;
        
        $this->validate ($request,[
        'nombreEstatus'=>['required'],
        ]);

          $estatusRep = new estatusreparaciones;
          $estatusRep->idEstatus = $request->idEstatus;
          $estatusRep->nombreEstatus = $request->nombreEstatus;
		      $estatusRep-> activo='Si';
          $estatusRep->save();

          $proceso ="Alta de estatus de reparación";
          $mensaje="Registro guardado correctamente";
          return view('mensajeEstatusRep')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
          
        }

	 public function reporteEstatusReparaciones(Request $request)
    {
       $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

        if($sname == '' or $sidu =='' or $stipo=='')
        {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else
        {
          

		$consulta = \DB::select("SELECT e.idEstatus, e.nombreEstatus, e.activo FROM estatusreparaciones as e");
        return view ('reporteEstatusReparaciones')
        ->with('consulta',$consulta);
      }
		
    }
	
	 public function modificarEstatus($idEstatus){
           $consulta = estatusreparaciones::Where ('idEstatus','=',$idEstatus)->get();

           return view ('editarEstatusReparaciones')
           ->with ('consulta', $consulta[0]);
    }
	 public function editarEstatus(Request $request){
           $idEstatus= $request-> idEstatus;//Esta valor lo toma del text oculto en el formulario es el id para saber a cual de modificara
           $nombreEstatus= $request-> nombreEstatus;

           $this->validate ($request,
           ['nombreEstatus'=>['required']
           ]);

           $estatusRep = estatusreparaciones::find($idEstatus);
           $estatusRep-> idEstatus = $request-> idEstatus;
           $estatusRep-> nombreEstatus = $request-> nombreEstatus;
           $estatusRep-> save();

           $proceso='Modificación de estatus de reparación';
           $mensaje="Estatus modificado correctamente";
           return view ('mensajeEstatusRep')
             ->with('proceso',$proceso)
             ->with('mensaje',$mensaje);
    }

    public function eliminarEstatus ($idEstatus){/*Rerecibe este parametro y lo guarda en esa variable*/
             $marca = \DB::UPDATE("update estatusreparaciones
             set activo ='No' where idEstatus=$idEstatus");

           $proceso ="Eliminación de estatus de reparación";
           $mensaje="El estatus ha sido desactivado correctamente";
           return view('mensajeEstatusRep')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);
    }
	
	  public function restaurarEstatus($idEstatus){ //restarura el valos de NO a SI en el campo activo
           $marca= \DB::UPDATE("update estatusreparaciones
           set activo ='Si' where idEstatus=$idEstatus");

           $proceso ="Restauración de estatus de reparación";
           $mensaje="El estatus ha sido activado correctamente";
           return view('mensajeEstatusRep')
                ->with('proceso',$proceso)
                ->with('mensaje',$mensaje);
    }
	


}
