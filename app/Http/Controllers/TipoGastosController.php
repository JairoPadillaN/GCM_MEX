<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\tipogastos;
use Session;
class TipoGastosController extends Controller
{
    public function AltaTipoGastos(){
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
           $consulta= tipogastos::orderby('idTipoGastos','desc')
               ->take(1)
               ->get();
               return view ('altaTipoGastos');
    }
  }

    public function GuardarTipoGastos(Request $request){
           $nombreTipoGasto = $request-> nombreTipoGasto;
           $tipo = $request-> tipo;

           $this->validate ($request,['nombreTipoGasto'=>['regex:/^[A-Z][a-z, A-Z, ,á,é,i,ó,ú,ñ,0-9]*$/'],
          'tipo'=>['required']]);

           $tipoG = new tipogastos;
           $tipoG-> nombreTipoGasto = $request-> nombreTipoGasto;
           $tipoG-> tipo = $request-> tipo;
           $tipoG-> activo='Si';
           $tipoG-> save();

           $proceso='Alta de tipos de gastos';
           $mensaje="Registro guardado correctamente";
           return view ('mensajeTipoGastos')
             ->with('proceso',$proceso)
             ->with('mensaje',$mensaje);
    }

    public function ReporteTipoGastos(){
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
           $consulta = \DB::select("SELECT t.idTipoGastos,t.nombreTipoGasto,t.tipo,t.activo FROM tipogastos AS t");
           return view ('reporteTipoGastos')->with('consulta',$consulta);
    }
  }
    public function modificarTipoGastos($idTipoGastos){
           $consulta = tipogastos::Where ('idTipoGastos','=',$idTipoGastos)->get();
           return view ('editarTipoGastos')
           ->with ('consulta', $consulta[0]);
    }

    public function editarTipoGastos(Request $request){
           $idTipoGastos = $request-> idTipoGastos;//Esta valor lo toma del text oculto en el formulario es el id para saber a cual de modificara
           $nombreTipoGasto = $request-> nombreTipoGasto;
           $tipo = $request-> tipo;

           $this->validate ($request,['nombreTipoGasto'=>['regex:/^[A-Z][a-z, A-Z, ,á,é,i,ó,ú,ñ,0-9]*$/',]]);

           $tipoG = tipogastos::find($idTipoGastos);
           $tipoG-> idTipoGastos = $request-> idTipoGastos;
           $tipoG-> nombreTipoGasto = $request-> nombreTipoGasto;
           $tipoG-> tipo = $request-> tipo;
           $tipoG-> save();

           $proceso='Modificación de tipogastos';
           $mensaje="Usuario modificado correctamente";
           return view ('mensajeTipoGastos')
             ->with('proceso',$proceso)
             ->with('mensaje',$mensaje);
    }

    public function eliminarTipoGastos ($idTipoGastos){/*Rerecibe este parametro y lo guarda en esa variable*/
           $tipoG = \DB::UPDATE("update tipogastos
           set activo ='No' where idTipoGastos=$idTipoGastos");

           $proceso ="Eliminación de tipogastos";
           $mensaje="El usuario ha sido desactivado correctamente";
           return view('mensajeTipoGastos')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);
    }

    public function restaurarTipoGastos ($idTipoGastos){ //restarura el valos de NO a SI en el campo activo
           $tipoG = \DB::UPDATE("update tipogastos
           set activo ='Si' where idTipoGastos=$idTipoGastos");

           $proceso ="Restauración de tipogastos";
           $mensaje="El usuario ha sido activado correctamente";
           return view('mensajeTipoGastos')
                ->with('proceso',$proceso)
                ->with('mensaje',$mensaje);
    }


}
