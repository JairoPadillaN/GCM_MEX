<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\movimientos;
use App\usuarios;
use App\clientes;
use App\tipogastos;
use Session;

class MovimientosController extends Controller
{
  public function AltaMovimientos(){

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
		$consulta=movimientos::orderby('idm','desc')
			->take(1)
			->get();

		$tipoG = tipogastos::orderby('nombreTipoGasto','asc')->get();
		$cli = clientes::orderby('razonSocial','asc')->get();
		$usuario = usuarios::orderby('nombreUsuario','asc')->get();


		return view ('altaMovimientos')
			->with('tipoG',$tipoG)
			->with('usuario',$usuario)
			->with('cli',$cli);
  }
}



	public function GuardarMov(Request $request){

		$fecha = $request-> fecha;
		$tipo = $request ->tipo;
		$razon = $request ->razon;
		$monto = $request->monto;
		$detalle =$request->detalle;
		$idTipoGastos = $request->idTipoGastos;
		$idu = $request-> idu;
		$idc = $request->idc;


	$this->validate ($request,[
		'razon'=>['regex:/^[A-Z][a-z,A-Z, ,0-9 ]*$/'],
		'monto'=>['regex:/^[0-9]*$/'],
		'detalle'=>['regex:/^[A-Z][a-z,A-Z, ,0-9]*$/'],
	]);

	$movimiento= new movimientos;
	$movimiento-> fecha=$request->fecha;
	$movimiento-> tipo=$request->tipo;
	$movimiento-> razon =$request->razon;
	$movimiento-> monto=$request->monto;
	$movimiento-> detalle=$request->detalle;
	$movimiento-> idTipoGastos=$request->idTipoGastos;
	$movimiento-> idu=$request->idu;
	$movimiento-> idc=$request->idc;
	$movimiento-> activo='si';
	$movimiento ->save();

	$proceso='Alta de Movimientos';
    $mensaje="Registro guardado correctamente";
    return view ('mensajeMovimientos')
      ->with('proceso',$proceso)
      ->with('mensaje',$mensaje);

	}

	      public function reporteMovimientos(Request $request)
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


		$consulta= \DB::select("SELECT m.idm,m.fecha,m.tipo, tg.nombreTipoGasto AS tipoGasto, u.nombreUsuario AS us,m.razon, c.razonSocial AS cli, m.monto, m.detalle, m.activo
				FROM movimientos AS m
				INNER JOIN tipogastos AS tg ON tg.idTipoGastos = m.idTipoGastos
				INNER JOIN usuarios AS u ON u.idu=m.idu
				INNER JOIN clientes AS c ON c.idc=m.idc");
		return view ('reporteMovimientos')
			->with ('consulta',$consulta);

	}

    }

	public function modificarMovimientos($idm){
		$consulta = movimientos:: Where('idm', '=', $idm)
					->get();

		$clienteSel= clientes::where('idc',"=",$consulta[0]->idc)
					->get();
		$nomcli =$clienteSel[0]->razonSocial;
		$cli = clientes::where ('idc','!=',$consulta[0]->idc)
					->get();


		$tipoGastosSel= tipogastos::where('idTipoGastos',"=",$consulta[0]->idTipoGastos)
					->get();
		$nomTipoGastos =$tipoGastosSel[0]->nombreTipoGasto;
		$tipoG= tipogastos::where ('idTipoGastos','!=',$consulta[0]->idTipoGastos)
					->get();


		$usuarioSel= usuarios::where('idu',"=",$consulta[0]->idu)
					->get();
		$nomusuario =$usuarioSel[0]->nombreUsuario;
		$aPaterno=$usuarioSel[0]->aPaterno;
		$aMaterno =$usuarioSel[0]->aMaterno;
		$usuario= usuarios::where ('idu','!=',$consulta[0]->idu)
					->get();

			return view ('editaMovimientos')
			->with('consulta',$consulta[0])
			->with('tipoG',$tipoG)
			->with('usuario',$usuario)
			->with('cli',$cli)
			->with('idclientesel',$consulta[0]->idc)
			->with('nomcli',$nomcli)
			->with('idtipoGastossel',$consulta[0]->idTipoGastos)
			->with('nomTipoGastos',$nomTipoGastos)
			->with('idusuariosel',$consulta[0]->idu)
			->with('nomusuario',$nomusuario)
			->with('aPaterno',$aPaterno)
			->with('aMaterno',$aMaterno)
			;

	}

	public function editarMovimientos(Request $request){
		$idm=$request->idm;
		$fecha = $request-> fecha;
		$tipo = $request ->tipo;
		$razon = $request ->razon;
		$monto = $request->monto;
		$detalle =$request->detalle;
		$idTipoGastos = $request->idTipoGastos;
		$idu = $request-> idu;
		$idc = $request->idc;


	$this->validate ($request,[
		'razon'=>['regex:/^[A-Z][a-z,A-Z, ,0-9 ]*$/'],
		'monto'=>['regex:/^[0-9]*$/'],
		'detalle'=>['regex:/^[A-Z][a-z,A-Z, ,0-9]*$/'],
	]);

	$movimiento = movimientos::find($idm);
    $movimiento-> idm=$request->idm;
    $movimiento-> fecha=$request->fecha;
	$movimiento-> tipo=$request->tipo;
	$movimiento-> razon =$request->razon;
	$movimiento-> monto=$request->monto;
	$movimiento-> detalle=$request->detalle;
	$movimiento-> idTipoGastos=$request->idTipoGastos;
	$movimiento-> idu=$request->idu;
	$movimiento-> idc=$request->idc;
        $movimiento-> save();


		$proceso='Modificacion de Movimientos';
        $mensaje="Movimiento modificado correctamente";
        return view ('mensajeMovimientos')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
	}

 public function eliminarMovimientos ($idm){/*Rerecibe este parametro y lo guarda en esa variable*/
             $movimiento = \DB::UPDATE("update movimientos
             set activo ='No' where idm=$idm");

           $proceso ="Eliminacion de movimientos";
           $mensaje="El movimiento ha sido desactivado correctamente";
           return view('mensajeMovimientos')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);
    }

	  public function restaurarMovimientos ($idm){ //restarura el valos de NO a SI en el campo activo
           $movimiento= \DB::UPDATE("update movimientos
           set activo ='si' where idm=$idm");

           $proceso ="Restauracion de movimientos";
           $mensaje="El movimiento ha sido activado correctamente";
           return view('mensajeMovimientos')
                ->with('proceso',$proceso)
                ->with('mensaje',$mensaje);
    }

}
