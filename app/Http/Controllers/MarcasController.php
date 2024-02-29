<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\marcas;
class MarcasController extends Controller
{
  public function AltaMarcas(){
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
			$consulta= marcas::orderby('idMarca','desc')
               ->take(1)
               ->get();
			return view ('altaMarcas');
}
}

public function GuardarMarcas(Request $request)
{
        $nombreMarca=$request->nombreMarca;
         

        /*Valida la infromacion que esta entrando y que el usuario no debe dejarlo vacio*/
        $this->validate ($request,[
        'nombreMarca'=>['regex:/^[A-Z][a-z, A-Z, ,á,é,í,ó,ú,ñ, 0-9]*$/'],
    
        ]);

          $marca = new marcas;
          $marca->idMarca = $request->idMarca;
          $marca->nombreMarca = $request->nombreMarca;
		      $marca-> activo='si';
          $marca->save();

          $proceso ="Alta marcas";
          $mensaje="Registro guardado correctamente";
          return view('mensajeMarcas')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
          
        }
	 public function reporteMarcas(Request $request)
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
          

		$consulta = \DB::select("SELECT m.idMarca, m.nombreMarca, m.activo FROM marcas as m");
        return view ('reporteMarcas')->with('consulta',$consulta);
      }
		
    }
	
	 public function modificarMarcas($idMarca){
           $consulta = marcas::Where ('idMarca','=',$idMarca)->get();
           return view ('editarMarcas')
           ->with ('consulta', $consulta[0]);
    }
	 public function editarMarcas(Request $request){
           $idMarca= $request-> idMarca;//Esta valor lo toma del text oculto en el formulario es el id para saber a cual de modificara
           $nombreMarca= $request-> nombreMarca;

           $this->validate ($request,['nombreMarca'=>['regex:/^[A-Z][a-z, A-Z, ,á,é,i,ó,ú,ñ,0-9]*$/',]]);

           $marca = marcas::find($idMarca);
           $marca-> idMarca = $request-> idMarca;
           $marca-> nombreMarca = $request-> nombreMarca;
           $marca-> save();

           $proceso='Modificación de marcas';
           $mensaje="Marca modificada correctamente";
           return view ('mensajeMarcas')
             ->with('proceso',$proceso)
             ->with('mensaje',$mensaje);
    }
	public function eliminarMarcas ($idMarca){/*Rerecibe este parametro y lo guarda en esa variable*/
             $marca = \DB::UPDATE("update marcas
             set activo ='no' where idMarca=$idMarca");

           $proceso ="Eliminación de marcas";
           $mensaje="La marca ha sido desactivada correctamente";
           return view('mensajeMarcas')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);
    }
	
	  public function restaurarMarcas ($idMarca){ //restarura el valos de NO a SI en el campo activo
           $marca= \DB::UPDATE("update marcas
           set activo ='si' where idMarca=$idMarca");

           $proceso ="Restauración de marcas";
           $mensaje="La marca ha sido activada correctamente";
           return view('mensajeMarcas')
                ->with('proceso',$proceso)
                ->with('mensaje',$mensaje);
    }
	


}
