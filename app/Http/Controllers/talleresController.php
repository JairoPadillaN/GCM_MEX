<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\talleres;
class talleresController extends Controller
{
  public function altaTaller(){
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
			$consulta= talleres::orderby('idTaller','desc')
               ->take(1)
               ->get();
			return view ('altaTaller');
}
}

public function guardarTaller(Request $request)
{
        $nombreTaller=$request->nombreTaller;
        $tipoTaller=$request->tipoTaller;
        
        $this->validate ($request,[
        'nombreTaller'=>['required'],
        ]);

          $taller = new talleres;
          $taller->idTaller = $request->idTaller;
          $taller->nombreTaller = $request->nombreTaller;
          $taller->tipoTaller = $request->tipoTaller;
		      $taller->activo='Si';
          $taller->save();

          $proceso ="Alta de taller";
          $mensaje="Registro guardado correctamente";
          return view('mensajeTaller')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
          
}

	 public function reporteTaller(Request $request)
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
          

		$consulta = \DB::select("SELECT t.idTaller, t.nombreTaller, t.tipoTaller, t.activo FROM talleres as t");
        return view ('reporteTaller')
        ->with('consulta',$consulta);
      }
		
    }
	
	 public function modificarTaller($idTaller){
           $consulta = talleres::Where ('idTaller','=',$idTaller)->get();

           return view ('editarTaller')
           ->with ('consulta', $consulta[0]);
    }
	 public function editarTaller(Request $request){
           $idTaller= $request-> idTaller;//Esta valor lo toma del text oculto en el formulario es el id para saber a cual de modificara
           $nombreTaller= $request-> nombreTaller;
           $tipoTaller= $request-> tipoTaller;

           $this->validate ($request,
           ['nombreTaller'=>['required']
           ]);

           $taller = talleres::find($idTaller);
           $taller-> idTaller = $request-> idTaller;
           $taller-> nombreTaller = $request-> nombreTaller;
           $taller-> tipoTaller = $request-> tipoTaller;
           $taller-> save();

           $proceso='Modificación de taller';
           $mensaje="Taller modificado correctamente";
           return view ('mensajeTaller')
             ->with('proceso',$proceso)
             ->with('mensaje',$mensaje);
    }

    public function eliminarTaller ($idTaller){/*Rerecibe este parametro y lo guarda en esa variable*/
             $taller = \DB::UPDATE("update talleres
             set activo ='No' where idTaller=$idTaller");

           $proceso ="Eliminación de taller";
           $mensaje="El taller ha sido desactivado correctamente";
           return view('mensajeTaller')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);
    }
	
	  public function restaurarTaller($idTaller){ //restarura el valos de NO a SI en el campo activo
           $taller= \DB::UPDATE("update talleres
           set activo ='Si' where idTaller=$idTaller");

           $proceso ="Restauración de taller";
           $mensaje="El taller ha sido activado correctamente";
           return view('mensajeTaller')
                ->with('proceso',$proceso)
                ->with('mensaje',$mensaje);
    }
	


}
