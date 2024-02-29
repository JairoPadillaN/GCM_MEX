<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\clientes;
use App\seguimientoactividades;
use App\login;
use App\estados;
use App\detallepacs;
use Session;


class ClientesController extends Controller
{

public function altaClientes(){
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

    /*$clavequesig = clientes::withTrashed()->orderBy('idc','desc')->take(1)->get();
        $ids=$clavequesig[0]->idc+1;
         $estados =estados::all();*/

         $clavequesig=clientes::orderby('idc','desc')
     			->take(1)
     			->get();
          return view ('altaClientes');


    /*return view ('altaClientes')->with(['ids'=>$ids])->with(['estados' => $estados]);*/

    $consulta= clientes::orderby('idu','desc')
               ->take(1)
               ->get();
               return view ('altaUsuarios');

    }
}


    public function GuardarClientes(Request $request)
    {

        $razonSocial=$request-> razonSocial;
        $rfc=$request-> rfc;
        $contacto=$request-> contacto;
        $diasDePago=$request-> diasDePago;
        $fechaDePago=$request-> fechaDePago;
        $calle = $request-> calle;
        $numeroProveedor = $request-> numeroProveedor;
        // $sucursal = $request-> sucursal;
        $num = $request-> num;
        $colonia = $request-> colonia;
        $tipoCliente=$request-> tipoCliente;
        $empresaPertenece=$request-> empresaPertenece;
       /* $lat=$request-> lat;
        $lng=$request-> lng;
        $direccion=$request-> direccion;*/
        // $contactoVentas=$request-> contactoVentas;
        // $correoVentas=$request-> correoVentas;
        // $telVentas=$request-> telVentas;
        // $contactoGerente=$request-> contactoGerente;
        // $correoGerente=$request-> correoGerente;
        // $telGerente=$request-> telGerente;

        // $contactoCompras=$request-> contactoCompras;
        // $correoCompras=$request-> correoCompras;
        // $telCompras=$request-> telCompras;

        // $nombreCuentasPP=$request-> nombreCuentasPP;
        // $correoCuentasPP=$request-> correoCuentasPP;
        // $telefonoCuentasPP=$request-> telefonoCuentasPP;
        // $extencionCuentasPP=$request-> extencionCuentasPP;

        // $nombreCuentasPC=$request-> nombreCuentasPC;
        // $correoCuentasPC=$request-> correoCuentasPC;
        // $telefonoCuentasPC=$request-> telefonoCuentasPC;
        // $extencionCuentasPC=$request-> extencionCuentasPC;


        $this->validate ($request,[
        'razonSocial'=>['required'],
        'rfc'=>['required'],
        // 'sucursal'=>['required'],
        'diasDePago'=>['required'],
        'calle'=>['required'],
        'num'=>['required'],
        'tipoCliente'=>['required'],
        'colonia'=>['required'],
        ]);



    	$cli= new clientes;
        $cli-> idc=$request->idc;

        $cli-> razonSocial=$request->razonSocial;
        $cli-> rfc=$request->rfc;
        // $cli-> sucursal=$request->sucursal;
        $cli-> contacto=$request->contacto;
        $cli-> fechaDePago=$request->fechaDePago;
        $cli-> diasDePago=$request->diasDePago;
        // $cli-> lat=$request->lat;
        // $cli-> lng=$request->lng;
        // $cli-> direccion=$request->direccion;
        $cli-> calle=$request->calle;
        $cli-> numeroProveedor=$request->numeroProveedor;
        $cli-> num=$request->num;
        $cli-> colonia=$request->colonia;
        $cli-> estado=$request->estado;
        $cli-> municipio=$request->municipio;
        $cli-> tipoCliente=$request->tipoCliente;
        $cli-> empresaPertenece=$request->empresaPertenece;
		// $cli-> contactoVentas=$request->contactoVentas;
        // $cli-> correoVentas=$request->correoVentas;
        // $cli-> telVentas=$request->telVentas;
        // $cli-> extenVentas=$request->extenVentas;
        // $cli-> contactoGerente=$request->contactoGerente;
        // $cli-> correoGerente=$request->correoGerente;
        // $cli-> telGerente=$request->telGerente;
        // $cli-> extenGerente=$request->extenGerente;
        // $cli-> contactoCompras=$request->contactoCompras;
        // $cli-> correoCompras=$request->correoCompras;
        // $cli-> telCompras=$request->telCompras;
        // $cli-> extenCompras=$request->extenCompras;

        // $cli-> contactoPlantel=$request->contactoPlantel;
        // $cli-> correoPlantel=$request->correoPlantel;
        // $cli-> telPlantel=$request->telPlantel;
        // $cli-> extenPlantel=$request->extenPlantel;

        // $cli-> contactoAlmacen=$request->contactoAlmacen;
        // $cli-> correoAlmacen=$request->correoAlmacen;
        // $cli-> telAlmacen=$request->telAlmacen;
        // $cli-> extenAlmacen=$request->extenAlmacen;


        // $cli-> nombreCuentasPP=$request->nombreCuentasPP;
        // $cli-> telefonoCuentasPP=$request->telefonoCuentasPP;
        // $cli-> correoCuentasPP=$request->correoCuentasPP;
        // $cli-> extencionCuentasPP=$request->extencionCuentasPP;

        // $cli-> nombreCuentasPC=$request->nombreCuentasPC;
        // $cli-> telefonoCuentaspC=$request->telefonoCuentasPC;
        // $cli-> correoCuentasPC=$request->correoCuentasPC;
        // $cli-> extencionCuentasPC=$request->extencionCuentasPC;

        $cli->save();


         $proceso='Alta de cliente';
        $mensaje="Registro guardado correctamente";
        return view ('mensajeClientes')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
    }



    public function modificaClientes($idc){
        $stipo = Session::get('sesiontipo');
        $consulta = clientes::Where ('idc','=',$idc)->get();

        $seguimiento = \DB::select("SELECT sa.idSegActividad,sa.folio,  DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacionFormato, 
                                sa.asunto,u.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, DATE_FORMAT(fechaInicio,'%d %b %Y') AS fechaInicioFormato,
                                DATE_FORMAT(fechaTermino,'%d %b %Y') AS fechaTerminoFormato,nombreActividad,
                                sa.importanciaSeguimiento, sa.activo, ar.nombreArea,CONCAT(c.razonSocial,' / ',s.sucursal) AS clienteSucursal
                                FROM seguimientoactividades AS sa
                                INNER JOIN usuarios AS u ON sa.idu = u.idu
                                INNER JOIN areas AS ar ON sa.idArea = ar.idArea
                                INNER JOIN sucursales AS s ON s.idSucursal= sa.idSucursal
                                INNER JOIN clientes AS c ON c.idc= sa.idc
                                INNER JOIN actividades AS act ON act.idActividad= sa.idActividad
                                WHERE c.idc=?
                                ORDER BY idSegActividad DESC",[$idc]);
        
                                
        $cuantos=count($seguimiento);

        // $consulta = clientes::orderby('razonSocial','asc')->get();
        return view('editaClientes')
        // ->with('atendidoPor',$atendidoPor[0])
        ->with('cuantos',$cuantos)
        ->with('seguimiento',$seguimiento)
        ->with('stipo',$stipo)
        ->with('consulta',$consulta[0]);

    }
    public function modificaClientes2(Request $request)
    {

        $idc=$request-> idc;
        $razonSocial=$request-> razonSocial;
        $rfc=$request-> rfc;
        // $sucursal=$request-> sucursal;
        $contacto=$request-> contacto;
        $diasDePago=$request-> diasDePago;
        $fechaDePago=$request-> fechaDePago;
        $calle = $request-> calle;
        $numeroProveedor = $request-> numeroProveedor;
        $num = $request-> num;
        $colonia = $request-> colonia;
        $tipoCliente=$request-> tipoCliente;
        $empresaPertenece=$request-> empresaPertenece;
       /* $lat=$request-> lat;
        $lng=$request-> lng;
        $direccion=$request-> direccion;*/
        // $contactoVentas=$request->contactoVentas;
        // $correoVentas=$request->correoVentas;
        // $telVentas=$request->telVentas;
        // $extenVentas=$request->extenVentas;

        // $contactoGerente=$request->contactoGerente;
        // $correoGerente=$request->correoGerente;
        // $telGerente=$request->telGerente;
        // $extenGerente=$request->extenGerente;

        // $contactoCompras=$request->contactoCompras;
        // $correoCompras=$request->correoCompras;
        // $telCompras=$request->telCompras;
        // $extenCompras=$request->extenCompras;

        // $nombreCuentasPP=$request->nombreCuentasPP;
        // $telefonoCuentasPP=$request->telefonoCuentasPP;
        // $correoCuentasPP=$request->correoCuentasPP;
        // $extencionCuentasPP=$request->extencionCuentasPP;

        // $nombreCuentasPC=$request->nombreCuentasPC;
        // $telefonoCuentasPC=$request->telefonoCuentasPC;
        // $correoCuentasPC=$request->correoCuentasPC;
        // $extencionCuentasPC=$request->extencionCuentasPC;

        $this->validate ($request,[
        'razonSocial'=>['required'],
        'contacto'=>['required'],
        'diasDePago'=>['required'],
        'calle'=>['required'],
        'rfc'=>['required'],
        'num'=>['required'],
        // 'sucursal'=>['required'],
        'colonia'=>['required'],
        ]);

        $clie = clientes::find($idc);
        $clie-> idc = $request-> idc;
        $clie->razonSocial=$request->razonSocial;
        $clie->rfc=$request->rfc;
        // $clie->sucursal=$request->sucursal;
        $clie->contacto=$request->contacto;
        $clie->diasDePago=$request->diasDePago;
        //$clie->lat=$request->lat;
        //$clie->lng=$request->lng;
        //$clie->direccion=$request->direccion;
        $clie->calle=$request->calle;
        $clie->numeroProveedor=$request->numeroProveedor;
        $clie->num=$request->num;
        $clie->colonia=$request->colonia;
        $clie->tipoCliente=$request->tipoCliente;
        $clie->empresaPertenece=$request->empresaPertenece;

        // $clie->contactoVentas=$request->contactoVentas;
        // $clie->correoVentas=$request->correoVentas;
        // $clie->telVentas=$request->telVentas;
        // $clie->extenVentas=$request->extenVentas;

        // $clie->contactoGerente=$request->contactoGerente;
        // $clie->correoGerente=$request->correoGerente;
        // $clie->telGerente=$request->telGerente;
        // $clie->extenGerente=$request->extenGerente;

        // $clie->contactoCompras=$request->contactoCompras;
        // $clie->correoCompras=$request->correoCompras;
        // $clie->telCompras=$request->telCompras;
        // $clie->extenCompras=$request->extenCompras;

        // $clie->contactoPlantel=$request->contactoPlantel;
        // $clie->correoPlantel=$request->correoPlantel;
        // $clie->telPlantel=$request->telPlantel;
        // $clie->extenPlantel=$request->extenPlantel;

        // $clie->contactoAlmacen=$request->contactoAlmacen;
        // $clie->correoAlmacen=$request->correoAlmacen;
        // $clie->telAlmacen=$request->telAlmacen;
        // $clie->extenAlmacen=$request->extenAlmacen;

        // $clie->nombreCuentasPP=$request->nombreCuentasPP;
        // $clie->telefonoCuentasPP=$request->telefonoCuentasPP;
        // $clie->correoCuentasPP=$request->correoCuentasPP;
        // $clie->extencionCuentasPP=$request->extencionCuentasPP;

        // $clie->nombreCuentasPC=$request->nombreCuentasPC;
        // $clie->telefonoCuentasPC=$request->telefonoCuentasPC;
        // $clie->correoCuentasPC=$request->correoCuentasPC;
        // $clie->extencionCuentasPC=$request->extencionCuentasPC;

        $clie->save();

        $proceso='Modificacion de clientes';
        $mensaje="Registro modificado correctamente";
        return view ('mensajeClientes')
         ->with('proceso',$proceso)
         ->with('mensaje',$mensaje);

    }


      public function consultaClientes(Request $request)
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
            
            $consulta = clientes::orderby('razonSocial','asc')->get();
            
            return view('consultaClientes')
            ->with(['consulta'=>$consulta]);


            
            }



    }

    public function reporteClientesAbajo(Request $request){        
        $razonSocial = $request->razonSocial;
        $rfc = $request->rfc;
        $buscaRazon="%".$razonSocial."%";
        $buscaRFC="%".$rfc."%";
         if($razonSocial==""){
           $reporte=\DB::select("SELECT razonSocial,rfc,idc FROM clientes WHERE rfc LIKE ? ORDER BY razonSocial ASC",[$buscaRFC]);
         }
         else{
            $reporte=\DB::select("SELECT razonSocial,rfc,idc FROM clientes WHERE razonSocial LIKE ? AND rfc LIKE ? ORDER BY razonSocial ASC",[$buscaRazon,$buscaRFC]);
         }

        $cuantos = count($reporte);
        return view ('reporteClientesAbajo')
        ->with('cuantos',$cuantos)
        ->with('reporte',$reporte);
    }

     public function eliminarClientes(Request $request)
   {

        $idc = $request->idc;
        clientes::destroy($idc);

        return redirect('consultaClientes?idc='.$idc);
   }













}
