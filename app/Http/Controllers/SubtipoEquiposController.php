<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\tipoequipos;
use App\subtipoequipos;
class SubtipoEquiposController extends Controller
{
  public function altaSubtipoEquipos(){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }
    else{
      $tipo = tipoequipos::orderby('tipoEquipo','asc')->get();
      return view ('altaSubtipoEquipos')
      ->with('tipo',$tipo);
    }
  }

  public function GuardarSubtipoEquipos(Request $request){
    $idTipoEquipo=$request->idTipoEquipo;
    $subtipoEquipo=$request->subtipoEquipo;

    $this->validate ($request,[
      'idTipoEquipo'=>['required'],
      'subtipoEquipo'=>['required']
      ]);

    $subtipoE = new subtipoequipos;
    $subtipoE->idTipoEquipo=$request->idTipoEquipo;
    $subtipoE->subtipoEquipo=$request->subtipoEquipo;
  	$subtipoE->activo='si';
    $subtipoE->save();

    $proceso='Alta de Subtipos de Equipos';
    $mensaje="Registro guardado correctamente";
    return view ('mensajeSubtipoEquipos')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }
  
  public function reporteSubtipoEquipos(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }
    else{
      $consulta = \DB::select("SELECT s.idSubtipoEquipo,s.subtipoEquipo,s.activo,t.tipoEquipo As tip FROM subtipoequipos as s
      INNER JOIN tipoequipos AS t ON t.idTipoEquipo= s.idTipoEquipo");
      return view ('reporteSubtipoEquipos')
      ->with('consulta',$consulta);
    } 
  }
    
  public function modificarSubtipoEquipos($idSubtipoEquipo){
    $consultaTipos = subtipoEquipos::Where ('idSubtipoEquipo','=',$idSubtipoEquipo)->get();
    $tipoSel= tipoequipos::where('idTipoEquipo',"=",$consultaTipos[0]->idTipoEquipo)->get();
    $nomtip =$tipoSel[0]->tipoEquipo;
    $tipo = tipoequipos::where ('idTipoEquipo','!=',$consultaTipos[0]->idTipoEquipo)->get();

    $consulta = subtipoEquipos::Where ('idSubtipoEquipo','=',$idSubtipoEquipo)->get();

    return view('editarSubtipoEquipos')
    ->with('tipo',$tipo)
    ->with('idtiposel',$consultaTipos[0]->idTipoEquipo)
    ->with('nomtip',$nomtip)
    ->with('tipoSel',$tipoSel[0])
    ->with('consulta',$consulta[0]);
  }
  
  public function editarSubtipoEquipos(Request $request){
    $idTipoEquipo= $request-> idTipoEquipo;//Esta valor lo toma del text oculto en el formulario es el id para saber a cual de modificara
    $idSubtipoEquipo= $request-> idSubtipoEquipo;//Esta valor lo toma del text oculto en el formulario es el id para saber a cual de modificara
    $subtipoEquipo= $request-> subtipoEquipo;

    $this->validate ($request,[
      'idTipoEquipo'=>['required'],
      'subtipoEquipo'=>['required']
    ]);

    $subtipoE = subtipoequipos::find($idSubtipoEquipo);
    $subtipoE-> idSubtipoEquipo= $request-> idSubtipoEquipo;//Esta valor lo toma del text oculto en el formulario es el id para saber a cual de modificara
    $subtipoE-> idTipoEquipo = $request-> idTipoEquipo;
    $subtipoE-> subtipoEquipo = $request-> subtipoEquipo;
    $subtipoE-> save();

    $proceso='Modificación de Subtipos de Equipos';
    $mensaje="Registro modificado correctamente";
    return view ('mensajeSubtipoEquipos')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }
  
  public function eliminarSubtipoEquipos ($idSubtipoEquipo){/*Rerecibe este parametro y lo guarda en esa variable*/
    $subtipoE = \DB::UPDATE("update subtipoequipos set activo ='no' where idSubtipoEquipo=$idSubtipoEquipo");

    $proceso='Eliminación de Subtipos de Equipos';
    $mensaje="Registro eliminado correctamente";
    return view ('mensajeSubtipoEquipos')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }
    
  public function restaurarSubtipoEquipos ($idSubtipoEquipo){ //restarura el valos de NO a SI en el campo activo
    $subtipoE= \DB::UPDATE("update subtipoequipos set activo ='si' where idSubtipoEquipo=$idSubtipoEquipo");
    
    $proceso ="Restauración de Subtipos de Equipos";
    $mensaje="El registro ha sido activado correctamente";
    return view('mensajeSubtipoEquipos')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }
  
}