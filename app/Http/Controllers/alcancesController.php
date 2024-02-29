<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\alcances;
class alcancesController extends Controller
{
  public function altaAlcances(){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
          Session::flash('error', 'Es necesario logearse antes de continuar');
          return redirect()->route('login');
    }
    else
    {			
		  return view ('altaAlcances');
    }
  }

  public function guardarAlcance(Request $request){
    $alcance=$request->alcance;

    $this->validate ($request,[
        'alcance'=>['required'], 
    ]);

          $alcance = new alcances;
          $alcance-> alcance = $request->alcance;
		      $alcance-> activo='si';
          $alcance->save();

          $proceso ="Alta de Alcances";
          $mensaje="Registro guardado correctamente";
          return view('mensajeAlcances')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
          
        }
   
        public function reporteAlcances(Request $request){
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
          

		$consulta = \DB::select("SELECT a.idAlcance, a.alcance, a.activo FROM alcances as a");
        return view ('reporteAlcances')->with('consulta',$consulta);
      }
		
    }
	
	 public function modificarAlcances($idAlcance){
           $consulta = alcances::Where ('idAlcance','=',$idAlcance)->get();
           return view ('editarAlcances')
           ->with ('consulta', $consulta[0]);
    }

	 public function editarAlcances(Request $request){
           $idAlcance= $request-> idAlcance;//Esta valor lo toma del text oculto en el formulario es el id para saber a cual de modificara
           $alcance= $request-> alcance;

           $this->validate ($request,['alcance'=>['required']]);

           $alcance = alcances::find($idAlcance);
           $alcance-> idAlcance = $request-> idAlcance;
           $alcance-> alcance = $request-> alcance;
           $alcance-> save();

           $proceso='Modificación de alcances';
           $mensaje="Registro modificado correctamente";
           return view ('mensajeAlcances')
             ->with('proceso',$proceso)
             ->with('mensaje',$mensaje);
    }
	public function eliminarAlcances ($idAlcance){/*Rerecibe este parametro y lo guarda en esa variable*/
             $alcance = \DB::UPDATE("update alcances
             set activo ='no' where idAlcance=$idAlcance");

           $proceso ="Eliminación de alcances";
           $mensaje="El registro ha sido eliminado correctamente";
           return view('mensajeAlcances')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);
    }
	
	  public function restaurarAlcances ($idAlcance){ //restarura el valos de NO a SI en el campo activo
           $alcance= \DB::UPDATE("update alcances
           set activo ='si' where idAlcance=$idAlcance");

           $proceso ="Restauración de alcances";
           $mensaje="El registro ha sido restaurado correctamente";
           return view('mensajeAlcances')
                ->with('proceso',$proceso)
                ->with('mensaje',$mensaje);
    }
	


}