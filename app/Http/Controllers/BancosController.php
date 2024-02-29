<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\clientes;
use App\login;
use App\estados;
use App\bancos;
use Session;


class BancosController extends Controller
{

public function altaBancos(){
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

            $consulta= bancos::orderby('idb','desc')
            ->take(1)
            ->get();
         return view ('altaBancos');
    }
}


    public function GuardarBancos(Request $request)
    {
        $nombre=$request-> nombre;
        $numeroDeCuenta=$request-> numeroDeCuenta;

        $this->validate ($request,[
        'nombre'=>['required'],
        'numeroDeCuenta'=>['required'],
        ]);

    	$ban= new bancos;
        $ban-> idb=$request->idb;
        $ban-> nombre=$request->nombre;
        $ban-> numeroDeCuenta=$request->numeroDeCuenta;
        $ban-> moneda=$request->moneda;
        $ban-> activo='si';
        $ban->save();


         $proceso='Alta de bancos';
        $mensaje="Registro guardado correctamente";
        return view ('mensajeBancos')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
    }



    public function modificarBancos($idb){
        $consulta = bancos::Where ('idb','=',$idb)
        ->get();
        
        return view ('editaBancos')
        ->with ('consulta', $consulta[0]);
    }

    public function editarBancos(Request $request)
    {
        $idb=$request->idb;
        $nombre=$request-> nombre;
        $numeroDeCuenta=$request-> numeroDeCuenta;

        $this->validate ($request,[
            'nombre'=>['required'],
            'numeroDeCuenta'=>['required'],
            ]);

        $ban=bancos::find($idb);
        $ban->nombre=$request->nombre;
        $ban->numeroDeCuenta=$request->numeroDeCuenta;
        $ban->moneda=$request->moneda;
        $ban->save();

        $proceso='Modificación de banco';
        $mensaje="Registro modificado correctamente";
        return view ('mensajeBancos')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
    }


      public function reporteBancos(Request $request)
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
            $consulta = \DB::select("SELECT b.idb, b.nombre, b.moneda, b.activo FROM bancos as b");
                //vista de la tabla
                return view('reporteBancos')
                            ->with(['consulta'=>$consulta]);
            }
    }

    public function eliminarBancos($idb){/*Rerecibe este parametro y lo guarda en esa variable*/
        $ban  = \DB::UPDATE("update bancos
        set activo ='no' where idb=$idb");

      $proceso ="Eliminación de bancos";
      $mensaje="El banco ha sido eliminado correctamente";
      return view('mensajeBancos')
         ->with('proceso',$proceso)
         ->with('mensaje',$mensaje);
}

 public function restaurarBancos ($idb){ //restarura el valos de NO a SI en el campo activo
      $ban = \DB::UPDATE("update bancos
      set activo ='si' where idb=$idb");

      $proceso ="Restauración de bancos";
      $mensaje="El banco ha sido activado correctamente";
      return view('mensajeBancos')
           ->with('proceso',$proceso)
           ->with('mensaje',$mensaje);
}
}
