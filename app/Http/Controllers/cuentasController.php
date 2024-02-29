<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\cuentas;
use App\login;
use Session;


class cuentasController extends Controller
{

public function altaCuentas(){
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

         return view ('altaCuentas');
    }
}


    public function guardarCuentas(Request $request)
    {
        $empresaCuenta=$request->empresaCuenta;
        $nombreCuenta=$request->nombreCuenta;
        $numeroCu=$request->numeroCu;
        $descripcionCuenta=$request->descripcionCuenta;
        $monedaCuenta=$request->monedaCuenta;
        $saldoCuenta=$request->saldoCuenta;

        // $this->validate ($request,[
        // 'nombre'=>['required'],
        // 'numeroDeCuenta'=>['required'],
        // ]);

    	$cuentas= new cuentas;
        $cuentas-> idCuenta=$request->idCuenta;
        $cuentas-> empresaCuenta=$request->empresaCuenta;
        $cuentas-> nombreCuenta=$request->nombreCuenta;
        $cuentas-> numeroCu=$request->numeroCu;
        $cuentas-> descripcionCuenta=$request->descripcionCuenta;
        $cuentas-> monedaCuenta=$request->monedaCuenta;
        $cuentas-> saldoCuenta=$request->saldoCuenta;
        $cuentas-> activo='Si';
        $cuentas->save();


         $proceso='Alta de cuentas';
        $mensaje="Registro guardado correctamente";
        return view ('mensajeCuentas')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
    }



    public function modificarCuentas($idCuenta){
        $consulta = cuentas::Where ('idCuenta','=',$idCuenta)
        ->get();
        
        return view ('editarCuentas')
        ->with ('consulta', $consulta[0]);
    }

    public function editarCuentas(Request $request)
    {
        $idCuenta=$request->idCuenta;
        $empresaCuenta=$request->empresaCuenta;
        $nombreCuenta=$request->nombreCuenta;
        $numeroCu=$request->numeroCu;
        $descripcionCuenta=$request->descripcionCuenta;
        $monedaCuenta=$request->monedaCuenta;
        $saldoCuenta=$request->saldoCuenta;

//         $this->validate ($request,[
//             'nombre'=>['required'],
//             'numeroDeCuenta'=>['required'],
//             ]);

        $cuentas = cuentas::find($idCuenta);
        $cuentas-> empresaCuenta=$request->empresaCuenta;
        $cuentas-> nombreCuenta=$request->nombreCuenta;
        $cuentas-> numeroCu=$request->numeroCu;
        $cuentas-> descripcionCuenta=$request->descripcionCuenta;
        $cuentas-> monedaCuenta=$request->monedaCuenta;
        $cuentas-> saldoCuenta=$request->saldoCuenta;
        $cuentas->save();

        $proceso='Modificación de cuenta';
        $mensaje="Registro modificado correctamente";
        return view ('mensajeCuentas')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
    }


      public function reporteCuentas(Request $request)
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
            $consulta = \DB::select("SELECT * FROM cuentas");
                //vista de la tabla
                return view('reporteCuentas')
                            ->with(['consulta'=>$consulta]);
            }
    }


    public function reporteCuentasAbajo(Request $request){        
        
        $nombreOempresa = "%".str_replace(' ', '-',$request->nombreOempresa)."%";        
                        
        if($nombreOempresa != '')
            $consulta = \DB::select("SELECT * FROM cuentas
            WHERE nombreCuenta LIKE ? OR empresaCuenta LIKE ?",[$nombreOempresa,$nombreOempresa]);
        else{
            $consulta = \DB::select("SELECT * FROM cuentas WHERE activo='Si' ");
        }
        
        $cuantos = count($consulta);

        return view ('reporteCuentasAbajo')
        ->with('cuantos',$cuantos)
        ->with('consulta',$consulta);
    }

    public function eliminarCuentas($idCuenta){/*Rerecibe este parametro y lo guarda en esa variable*/
        $cuentas  = \DB::UPDATE("update cuentas
        set activo ='No' where idCuenta=$idCuenta");

      $proceso ="Eliminación de cuentas";
      $mensaje="La cuenta ha sido eliminada correctamente";
      return view('mensajeCuentas')
         ->with('proceso',$proceso)
         ->with('mensaje',$mensaje);
}

 public function restaurarCuentas ($idCuenta){ //restarura el valos de NO a SI en el campo activo
      $cuentas = \DB::UPDATE("update cuentas
      set activo ='Si' where idCuenta=$idCuenta");

      $proceso ="Restauración de cuentas";
      $mensaje="La cuenta ha sido activada correctamente";
      return view('mensajeCuentas')
           ->with('proceso',$proceso)
           ->with('mensaje',$mensaje);
}
}
