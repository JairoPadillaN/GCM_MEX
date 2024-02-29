<?php
namespace App\Http\Controllers;
use Session;


class InicioController extends Controller
{
  public function Inicio()
	{
    $sname=Session::get('sesionname');
    $sidu=Session::get('sesionidu');
    $stipo=Session::get('sesiontipo');

    if($sname=='' or $sidu =='' or $stipo==''){
      Session::flash('error','Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }
    else {
    return view ('inicio');
	}
}

  public function Principal()
{
    return view ('principal');
  }

  public function index()
{
    return view ('index');
  }



  public function iniciop()
  {
  	return view('iniciop');
  }
  public function ventaRefacciones()
  {
    return view('ventaRefacciones');
  }
   public function solucionesHusky()
  {
    return view('solucionesHusky');
  }
   public function productos()
  {
    return view('productos');
  }
   public function contacto()
  {
    return view('contacto');
  }

//////////////////////////////INICIO DE PROVEEDORES/////////////////////////////////////////////
  public function inicioProveedor()
	{
                  $snameprov = Session::get('sesionnameprov');
                  $sidprov = Session::get('sesionidproveedor');
                  $ssegnom = Session::get('sesionsegnom');
                  $sap = Session::get('sesionap');

    if($snameprov=='' or $sidprov =='' or $ssegnom=='' or $sap==''){
      Session::flash('error','Es necesario loguearse antes de continuar');
      return redirect()->route('accesoProveedores');
    }
    else {
    return view ('inicioProveedor');
	}
}


}
