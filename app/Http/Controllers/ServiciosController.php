<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\servicios;
class ServiciosController extends Controller
{
    public function altaServicios(){
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
			$consulta= servicios::orderby('idServicio','desc')
               ->take(1)
               ->get();
			return view ('altaServicios');
}
}

public function GuardarServicios(Request $request)
{
        $nombreServicio=$request->nombreServicio;
         

        /*Valida la infromacion que esta entrando y que el usuario no debe dejarlo vacio*/
        $this->validate ($request,[
        'nombreServicio'=>['regex:/^[A-Z][a-z, A-Z, ,á,é,í,ó,ú,ñ, 0-9]*$/'],
    
        ]);

          $servicio = new servicios;
          $servicio->idServicio = $request->idServicio;
          $servicio->nombreServicio = $request->nombreServicio;
		  $servicio-> activo='si';
          $servicio->save();

          $proceso ="Alta servicios";
          $mensaje="Registro guardado correctamente";
          return view('mensajeServicios')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
          
        }
	 public function reporteServicios(Request $request)
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
            
		$consulta = \DB::select("SELECT s.idServicio, s.nombreServicio, s.activo FROM servicios as s");
        return view ('reporteServicios')->with('consulta',$consulta);
      }
		

    }
	
	 public function modificarServicios($idServicio){
           $consulta = servicios::Where ('idServicio','=',$idServicio)->get();
           return view ('editarServicios')
           ->with ('consulta', $consulta[0]);
    }
	 public function editarServicios(Request $request){
           $idServicio= $request-> idServicio;//Esta valor lo toma del text oculto en el formulario es el id para saber a cual de modificara
           $nombreServicio= $request-> nombreServicio;

           $this->validate ($request,['nombreServicio'=>['regex:/^[A-Z][a-z, A-Z, ,á,é,i,ó,ú,ñ,0-9]*$/',]]);

           $servicio = servicios::find($idServicio);
           $servicio-> idServicio = $request-> idServicio;
           $servicio-> nombreServicio = $request-> nombreServicio;
           $servicio-> save();

           $proceso='Modificación de servicios';
           $mensaje="Servicio modificado correctamente";
           return view ('mensajeServicios')
             ->with('proceso',$proceso)
             ->with('mensaje',$mensaje);
    }
	public function eliminarServicios ($idServicio){/*Rerecibe este parametro y lo guarda en esa variable*/
             $servicio = \DB::UPDATE("update servicios
             set activo ='No' where idServicio=$idServicio");

           $proceso ="Eliminación de servicios";
           $mensaje="El servicio ha sido desactivado correctamente";
           return view('mensajeServicios')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);
    }
	
	  public function restaurarServicios ($idServicio){ //restarura el valos de NO a SI en el campo activo
           $servicio= \DB::UPDATE("update servicios
           set activo ='si' where idServicio=$idServicio");

           $proceso ="Restauración de servicios";
           $mensaje="El servicio ha sido activado correctamente";
           return view('mensajeServicios')
                ->with('proceso',$proceso)
                ->with('mensaje',$mensaje);
    }
	
}
