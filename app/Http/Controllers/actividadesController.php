<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\actividades;
use App\areas;
class actividadesController extends Controller
{
  public function altaActividades(){
    $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
        $area = areas::orderby('nombreArea','asc')->get();

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
			return view ('altaActividades')
      ->with('area',$area);
}
}

public function guardarActividades(Request $request)
{
        $nombreActividad=$request->nombreActividad;
         

        // /*Valida la infromacion que esta entrando y que el usuario no debe dejarlo vacio*/
        // $this->validate ($request,[
        // 'nombreActividad'=>['regex:/^[A-Z][a-z, A-Z, ,á,é,í,ó,ú,ñ, 0-9]*$/'],
    
        // ]);

          $act = new actividades;
          $act->idActividad = $request->idActividad;
          $act->idArea = $request->idArea;
          $act->nombreActividad = $request->nombreActividad;
		      $act-> activo='Si';
          $act->save();

          $proceso ="Alta actividades";
          $mensaje="Registro guardado correctamente";
          return view('mensajeActividades')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
          // return $request;
          
        }
	 public function reporteActividades(Request $request)
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
          

		    $consulta = \DB::select("SELECT ar.nombreArea, idActividad, nombreActividad, ac.activo 
                                FROM actividades AS ac
                                INNER JOIN areas AS ar ON ar.idArea=ac.idArea");
        return view ('reporteActividades')->with('consulta',$consulta);
      }
		
    }
	
	 public function modificarActividades($idActividad){
          $consulta = actividades::Where ('idActividad','=',$idActividad)->get();
          $areaSel= areas::where('idArea',"=",$consulta[0]->idArea)->get();
          $nomArea =$areaSel[0]->nombreArea;
          $area= areas::where('idArea',"!=",$consulta[0]->idArea)->get();
          
           return view ('editarActividades')
           ->with ('areaSel', $areaSel[0])
           ->with ('area', $area)
           ->with ('nomArea', $nomArea)
           ->with ('consulta', $consulta[0]);
          // return $nomArea;
    }

	 public function editarActividades(Request $request){
           $idActividad= $request-> idActividad;//Esta valor lo toma del text oculto en el formulario es el id para saber a cual de modificara
           $nombreActividad= $request-> nombreActividad;

          //  $this->validate ($request,['nombreActividad'=>['regex:/^[A-Z][a-z, A-Z, ,á,é,i,ó,ú,ñ,0-9]*$/',]]);

           $act = actividades::find($idActividad);
           $act-> idActividad = $request-> idActividad;
           $act->idArea = $request->idArea;
           $act-> nombreActividad = $request-> nombreActividad;
           $act-> save();

           $proceso='Modificación de actividades';
           $mensaje="Actividad modificada correctamente";
           return view ('mensajeActividades')
             ->with('proceso',$proceso)
             ->with('mensaje',$mensaje);
    }

	public function eliminarActividades ($idActividad){/*Rerecibe este parametro y lo guarda en esa variable*/
             $act = \DB::UPDATE("update actividades
             set activo ='No' where idActividad=$idActividad");

           $proceso ="Eliminación de actividades";
           $mensaje="La actividad ha sido desactivada correctamente";
           return view('mensajeActividades')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);
    }
	
	  public function restaurarActividades ($idActividad){ //restarura el valos de NO a SI en el campo activo
           $act= \DB::UPDATE("update actividades
           set activo ='Si' where idActividad=$idActividad");

           $proceso ="Restauración de actividades";
           $mensaje="La actividad ha sido activada correctamente";
           return view('mensajeActividades')
                ->with('proceso',$proceso)
                ->with('mensaje',$mensaje);
    }
	


}
