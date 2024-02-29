<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\serviciostalleres;
use App\alcances;
class servicioTallerController extends Controller
{
  public function altaServTaller(){
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
			    $comboAlcances = alcances::orderby('alcance','asc')->get();

          return view ('altaServTaller')
          ->with('stipo',$stipo)
          ->with('comboAlcances',$comboAlcances);
}
}

public function guardarServTaller(Request $request)
{
        $idAlcance=$request->idAlcance;
        $nombreServTaller=$request->nombreServTaller;
        $costoInterno=$request->costoInterno;
        $costoExterno=$request->costoExterno;
        
        /*Valida la infromacion que esta entrando y que el usuario no debe dejarlo vacio*/
        $this->validate ($request,[
          'idAlcance'=>['required'],
          'nombreServTaller'=>['required']    
        ]);

          $servTaller = new serviciostalleres;
          $servTaller->idServicioTaller = $request->idServicioTaller;
          $servTaller->idAlcance = $request->idAlcance;
          $servTaller->nombreServTaller = $request->nombreServTaller;
          $servTaller->activo='Si';

          if($costoInterno==''){
            $servTaller->costoInterno = '0.00';
          }else{
            $servTaller->costoInterno = $request->costoInterno;
          }

          if($costoExterno==''){
            $servTaller->costoExterno = '0.00';
          }else{
            $servTaller->costoExterno = $request->costoExterno;
          }
          $servTaller->save();

          $proceso ="Alta de servicio de taller";
          $mensaje="Registro guardado correctamente";
          return view('mensajeServTaller')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
          
        }
	 public function reporteServTaller(Request $request)
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
          
	  	$consulta = \DB::select("SELECT st.idServicioTaller,a.alcance, st.nombreServTaller, st.costoInterno, st.costoExterno ,st.activo 
      FROM serviciostalleres as st
      LEFT JOIN alcances AS a ON a.idAlcance = st.idAlcance");
       
      return view ('reporteServTaller')
        ->with('stipo',$stipo)
        ->with('consulta',$consulta);
      }
		
    }
	
	 public function modificarServTaller($idServicioTaller){
    $stipo = Session::get('sesiontipo');

           $consulta = serviciostalleres::Where ('idServicioTaller','=',$idServicioTaller)->get();
           
           $consultaAlcances = alcances::where('idAlcance',"=",$consulta[0]->idAlcance)->get();
          $nombreAlcance=$consultaAlcances[0]->alcance;
          $losOtrosAlcancesOrden = alcances::orderby('alcance','asc')->get();

           return view ('editarServTaller')
           ->with ('consulta', $consulta[0])
           ->with('nombreAlcance',$nombreAlcance)
          ->with('idAlcanceConsulta',$consulta[0]->idAlcance)
          ->with('stipo',$stipo)
          ->with('losOtrosAlcancesOrden',$losOtrosAlcancesOrden);
    }
	 public function editarServTaller(Request $request){

    $idServicioTaller = $request-> idServicioTaller;
    $idAlcance=$request->idAlcance;
    $nombreServTaller=$request->nombreServTaller;
    $costoInterno=$request->costoInterno;
    $costoExterno=$request->costoExterno;
    
    $this->validate ($request,[
      'idAlcance'=>['required'],
      'nombreServTaller'=>['required']
    ]);


          $servTaller = serviciostalleres::find($idServicioTaller);
          $servTaller-> idServicioTaller = $request-> idServicioTaller;
          $servTaller-> idAlcance = $request->idAlcance;
          $servTaller-> nombreServTaller = $request-> nombreServTaller;
          $servTaller-> costoInterno = $request-> costoInterno;
          $servTaller-> costoExterno = $request-> costoExterno;
          
          $servTaller-> save();

          $proceso='Modificación de servicios de taller';
          $mensaje="Servicio modificada correctamente";
          return view ('mensajeServTaller')
            ->with('proceso',$proceso)
            ->with('mensaje',$mensaje);
    }

	public function eliminarServTaller ($idServicioTaller){/*Rerecibe este parametro y lo guarda en esa variable*/
             $marca = \DB::UPDATE("update serviciostalleres
             set activo ='No' where idServicioTaller=$idServicioTaller");

           $proceso ="Eliminación de servicios de taller";
           $mensaje="El servicio ha sido desactivado correctamente";
           return view('mensajeServTaller')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);
    }
	
	  public function restaurarServTaller ($idServicioTaller){ //restarura el valos de NO a SI en el campo activo
           $marca= \DB::UPDATE("update serviciostalleres
           set activo ='Si' where idServicioTaller=$idServicioTaller");

           $proceso ="Restauración de servicios de taller";
           $mensaje="El servicio ha sido activado correctamente";
           return view('mensajeServTaller')
                ->with('proceso',$proceso)
                ->with('mensaje',$mensaje);
    }
	


}