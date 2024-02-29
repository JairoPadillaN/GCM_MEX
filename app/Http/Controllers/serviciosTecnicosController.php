<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\serviciosTecnicos;

class serviciosTecnicosController extends Controller{

  public function altaServicioTecnico(){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
          Session::flash('error', 'Es necesario logearse antes de continuar');
          return redirect()->route('login');
    }
    else
    {			
		  return view ('altaServicioTecnico');
    }
  }

  public function guardarServicioTecnico(Request $request){
    $servicioTecnico=$request->servicioTecnico;
    $claveServicio=$request->claveServicio;
    $detalleServicio=$request->detalleServicio;
    $montoPesos=$request->montoPesos;
    $montoDolares=$request->montoDolares;
    $unidadMedida=$request->unidadMedida;

    $this->validate ($request,[
        'servicioTecnico'=>['required'],
        'claveServicio'=>['required'], 
        'montoPesos'=>['required'],
        'montoDolares'=>['required'],
        'unidadMedida'=>['required'],
    ]);

    $servicio = new serviciosTecnicos;
    $servicio-> servicioTecnico = $request->servicioTecnico;
    $servicio-> claveServicio = $request->claveServicio;
    $servicio-> detalleServicio = $request->detalleServicio;
    $servicio-> montoPesos = $request->montoPesos;
    $servicio-> montoDolares = $request->montoDolares;
    $servicio-> unidadMedida = $request->unidadMedida;
    $servicio-> activo='si';
    $servicio->save();

    $proceso ="Alta de servicios";
    $mensaje="Registro guardado correctamente";
    return view('mensajeServiciosTecnicos')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
      
  }
   
  public function reporteServiciosTecnicos(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo=='')
    {
        Session::flash('error', 'Es necesario logearse antes de continuar');
        return redirect()->route('login');
    }else{
      $consulta = \DB::select("SELECT st.idServicioTecnico, st.servicioTecnico,st.claveServicio, st.detalleServicio, st.unidadMedida, st.activo FROM serviciosTecnicos as st");
      return view ('reporteServiciosTecnicos')->with('consulta',$consulta);
    }
  }
	
  public function modificarServicioTecnico($idServicioTecnico){
    $consulta = serviciosTecnicos::Where ('idServicioTecnico','=',$idServicioTecnico)->get();
    return view ('editarServicioTecnico')
    ->with ('consulta', $consulta[0]);
  }

  public function editarServicioTecnico(Request $request){
    $idServicioTecnico= $request-> idServicioTecnico;
    $servicioTecnico= $request-> servicioTecnico;
    $claveServicio=$request->claveServicio;
    $detalleServicio=$request->detalleServicio;
    $montoPesos=$request->montoPesos;
    $montoDolares=$request->montoDolares;
    $unidadMedida=$request->unidadMedida;

    $this->validate ($request,['servicioTecnico'=>['required']]);

    $servicio = serviciosTecnicos::find($idServicioTecnico);
    $servicio-> idServicioTecnico = $request-> idServicioTecnico;
    $servicio-> servicioTecnico = $request-> servicioTecnico;
    $servicio-> claveServicio = $request->claveServicio;
    $servicio-> detalleServicio = $request->detalleServicio;
    $servicio-> montoPesos = $request->montoPesos;
    $servicio-> montoDolares = $request->montoDolares;
    $servicio-> unidadMedida = $request->unidadMedida;
    $servicio-> save();

    $proceso='Modificación de servicios';
    $mensaje="Registro modificado correctamente";
    return view ('mensajeServiciosTecnicos')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }

	public function eliminarServicioTecnico ($idServicioTecnico){/*Rerecibe este parametro y lo guarda en esa variable*/
    $servicio = \DB::UPDATE("update serviciosTecnicos
    set activo ='no' where idServicioTecnico=$idServicioTecnico");

    $proceso ="Eliminación de servicios";
    $mensaje="El registro ha sido eliminado correctamente";
    return view('mensajeServiciosTecnicos')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }
	
  public function restaurarServicioTecnico ($idServicioTecnico){ //restarura el valos de NO a SI en el campo activo
    $servicio= \DB::UPDATE("update serviciosTecnicos
    set activo ='si' where idServicioTecnico=$idServicioTecnico");

    $proceso ="Restauración de servicios";
    $mensaje="El registro ha sido restaurado correctamente";
    return view('mensajeServiciosTecnicos')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }
	


}