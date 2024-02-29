<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\areas;
class areasController extends Controller
{
  public function altaAreas(){
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
			// $consulta= marcas::orderby('idMarca','desc')
      //          ->take(1)
      //          ->get();
			return view ('altaAreas');
}
}

public function guardarAreas(Request $request)
{
        $nombreArea=$request->nombreArea;

          $area = new areas;
          $area->idArea = $request->idArea;
          $area->nombreArea = $request->nombreArea;
		      $area-> activo='Si';
          $area->save();

          $proceso ="Alta áreas";
          $mensaje="Registro guardado correctamente";
          return view('mensajeAreas')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
          
        }
	 public function reporteAreas(Request $request)
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
          

	    	$consulta = \DB::select("SELECT idArea,nombreArea, activo FROM areas");
        return view ('reporteAreas')
          ->with('consulta',$consulta);
      }
		
    }
	
	 public function modificarAreas($idArea){
           $consulta = areas::Where ('idArea','=',$idArea)->get();
           return view ('editarAreas')
           ->with ('consulta', $consulta[0]);
    }
	 public function editarAreas(Request $request){
           $idArea= $request-> idArea;//Esta valor lo toma del text oculto en el formulario es el id para saber a cual de modificara
           $nombreArea= $request-> nombreArea;

           $area = areas::find($idArea);
           $area-> idArea = $request-> idArea;
           $area-> nombreArea = $request-> nombreArea;
           $area-> save();

           $proceso='Modificación de áreas';
           $mensaje="Área modificada correctamente";
           return view ('mensajeAreas')
             ->with('proceso',$proceso)
             ->with('mensaje',$mensaje);
    }
	public function eliminarAreas ($idArea){/*Rerecibe este parametro y lo guarda en esa variable*/
             $area = \DB::UPDATE("update areas
             set activo ='No' where idArea=$idArea");

           $proceso ="Eliminación de áreas";
           $mensaje="El área ha sido desactivada correctamente";
           return view('mensajeAreas')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);
    }
	
	  public function restaurarAreas ($idArea){ //restarura el valos de NO a SI en el campo activo
           $area= \DB::UPDATE("update areas
           set activo ='Si' where idArea=$idArea");

           $proceso ="Restauración de áreas";
           $mensaje="El área ha sido activada correctamente";
           return view('mensajeAreas')
                ->with('proceso',$proceso)
                ->with('mensaje',$mensaje);
    }
	


}
