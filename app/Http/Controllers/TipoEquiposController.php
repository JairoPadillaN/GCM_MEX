<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\tipoequipos;
class TipoEquiposController extends Controller
{
  public function altaTipoEquipos(){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }
    else{
    	return view ('altaTipoEquipos');
    }
  }

  public function GuardarTipoEquipos(Request $request){
    $tipoEquipo=$request->tipoEquipo;
  
    $this->validate ($request,[
      'tipoEquipo'=>['required']
    ]);

    $tipoE = new tipoequipos;
    $tipoE->tipoEquipo = $request->tipoEquipo;
  	$tipoE->activo='si';
    $tipoE->save();

    $proceso='Alta de tipos de Equipos';
    $mensaje="Registro guardado correctamente";
    return view ('mensajeTipoEquipos')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }
  
  public function reporteTipoEquipos(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }
    else{
  		$consulta = \DB::select("SELECT t.idTipoEquipo,t.tipoEquipo,t.activo FROM tipoequipos as t");
      return view ('reporteTipoEquipos')
      ->with('consulta',$consulta);
    } 
  }
    
  public function modificarTipoEquipos($idTipoEquipo){
    $consulta = tipoequipos::Where ('idTipoEquipo','=',$idTipoEquipo)->get();
    return view ('editarTipoEquipos')
    ->with ('consulta', $consulta[0]);
  }
  
  public function editarTipoEquipos(Request $request){
    $idTipoEquipo= $request-> idTipoEquipo;//Esta valor lo toma del text oculto en el formulario es el id para saber a cual de modificara
    $tipoEquipo= $request-> tipoEquipo;

    $this->validate ($request,[
      'tipoEquipo'=>['required']
    ]);

    $tipoE = tipoequipos::find($idTipoEquipo);
    $tipoE-> idTipoEquipo = $request-> idTipoEquipo;
    $tipoE-> tipoEquipo = $request-> tipoEquipo;
    $tipoE-> save();

    $proceso='Modificación de tipos de Equipos';
    $mensaje="Registro modificado correctamente";
    return view ('mensajeTipoEquipos')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }
  
  public function eliminarTipoEquipos ($idTipoEquipo){/*Rerecibe este parametro y lo guarda en esa variable*/
    $tipoE = \DB::UPDATE("update tipoequipos set activo ='no' where idTipoEquipo=$idTipoEquipo");
    
    $proceso='Eliminación de tipos de Equipos';
    $mensaje="Registro eliminado correctamente";
    return view ('mensajeTipoEquipos')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }
    
  public function restaurarTipoEquipos ($idTipoEquipo){ //restarura el valos de NO a SI en el campo activo
    $tipoE= \DB::UPDATE("update tipoequipos set activo ='si' where idTipoEquipo=$idTipoEquipo");
    
    $proceso ="Restauración de tipos de Equipos";
    $mensaje="El registro ha sido activado correctamente";
    return view('mensajeTipoEquipos')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }
}