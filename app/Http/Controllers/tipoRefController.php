<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\tiporefacciones;
use App\marcaRefaccion;
class tipoRefController extends Controller
{
  public function altaTipoRef(){
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
      $consulta= tiporefacciones::orderby('idTipoRefacciones','desc')
          ->take(1)
          ->get();;
      $marca = marcaRefaccion::orderby('idMarcaRefa', 'asc')->get();
              
      return view ('altaTipoRefaccion')
      ->with('marca',$marca);
}
}

public function guardarTipoRef(Request $request)
{
        $nombreTipoRefaccion=$request->nombreTipoRefaccion;
        $idMarcaRefa=$request->idMarcaRefa;
        $codigoTipoRef=$request->codigoTipoRef;
        
        /*Valida la infromacion que esta entrando y que el usuario no debe dejarlo vacio*/
        $this->validate ($request,[
        'nombreTipoRefaccion'=>['required'],
        'idMarcaRefa'=>['required'],
        'codigoTipoRef'=>['required'],
    
        ]);

          $tipoRef = new tiporefacciones;
          $tipoRef->idTipoRefacciones = $request->idTipoRefacciones;
          $tipoRef->nombreTipoRefaccion = $request->nombreTipoRefaccion;
          $tipoRef->idMarcaRefa = $request->idMarcaRefa;
          $tipoRef->codigoTipoRef = $request->codigoTipoRef;
		      $tipoRef->activo='Si';
          $tipoRef->save();

          $proceso ="Alta de tipo de refacción";
          $mensaje="Registro guardado correctamente";
          return view('mensajeTipoRef')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
          return $request;
          
        }
	 public function reporteTipoRef(Request $request)
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
          

    $consulta = \DB::select("SELECT tr.idTipoRefacciones, tr.idMarcaRefa, tr.codigoTipoRef, mr.marcaRefaccion, tr.nombreTipoRefaccion, tr.activo 
      FROM tiporefacciones as tr
      INNER JOIN marcasRefaccion as mr
      WHERE tr.idMarcaRefa=mr.idMarcaRefa");
    
        return view ('reporteTipoRef')
            ->with('consulta',$consulta);
        // return $marca;
      }
		
    }
	
	 public function modificarTipoRef($idTipoRefacciones){
           $consulta = tiporefacciones::Where ('idTipoRefacciones','=',$idTipoRefacciones)->get();
           $marcaSel = marcaRefaccion::where ('idMarcaRefa', '=', $consulta[0]->idMarcaRefa)->get();
           $marca = $marcaSel[0]->marcaRefaccion;
           $marcaNoSel = marcaRefaccion::where ('idMarcaRefa', '!=', $consulta[0]->idMarcaRefa)->get();
           return view ('editarTipoRef')
           ->with ('marca', $marca)
           ->with ('marcaNoSel', $marcaNoSel)
           ->with ('idMarcaSel', $marcaSel[0]->idMarcaRefa)

           ->with ('consulta', $consulta[0]);
    }
	 public function editarTipoRef(Request $request){
           $idTipoRefacciones= $request-> idTipoRefacciones;//Esta valor lo toma del text oculto en el formulario es el id para saber a cual de modificara
           $nombreTipoRefaccion= $request-> nombreTipoRefaccion;
           $codigoTipoRef= $request-> codigoTipoRef;

           $this->validate ($request,['nombreTipoRefaccion'=>['required',]]);

           $tipoRef = tiporefacciones::find($idTipoRefacciones);
           $tipoRef-> idTipoRefacciones = $request-> idTipoRefacciones;
           $tipoRef-> nombreTipoRefaccion = $request-> nombreTipoRefaccion;
           $tipoRef-> codigoTipoRef = $request-> codigoTipoRef;
           $tipoRef-> save();

           $proceso='Modificación de tipos de refacción';
           $mensaje="Marca modificada correctamente";
           return view ('mensajeTipoRef')
             ->with('proceso',$proceso)
             ->with('mensaje',$mensaje);
          // return $request;
    }
	public function eliminarTipoRef ($idTipoRefacciones){/*Rerecibe este parametro y lo guarda en esa variable*/
             $marca = \DB::UPDATE("update tiporefacciones
             set activo ='No' where idTipoRefacciones=$idTipoRefacciones");

           $proceso ="Eliminación de tipos de refacción";
           $mensaje="El tipo de refacción ha sido desactivado correctamente";
           return view('mensajeTipoRef')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);
    }
	
	  public function restaurarTipoRef ($idTipoRefacciones){ //restarura el valos de NO a SI en el campo activo
           $marca= \DB::UPDATE("update tiporefacciones
           set activo ='Si' where idTipoRefacciones=$idTipoRefacciones");

           $proceso ="Restauración de tipos de refacción";
           $mensaje="El tipo de refacción ha sido activado correctamente";
           return view('mensajeTipoRef')
                ->with('proceso',$proceso)
                ->with('mensaje',$mensaje);
    }
	


}