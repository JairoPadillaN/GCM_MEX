<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\asignaciones;
use App\asignacionesdetalles;
use App\usuarios;
use App\facturas;
use App\viajes;
use App\vehiculos;
use App\clientes;
use App\bancos;
use DB;

class AsignacionController extends Controller{

  public function asignacion($idFactura){
	  $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }
    else{
      
      $consultaD=\DB::select("SELECT c.contacto,c.razonSocial, c.rfc FROM facturas AS v
      INNER JOIN clientes AS c ON c.idc= v.idc 
      WHERE idFactura=?",[$idFactura]);

      $resultado=\DB::select("SELECT ad.idAsigDet, ad.idu,ad.idFactura,f.numeroFactura
      ,ad.porcentaje, u.nombreUsuario,u.aPaterno,u.aMaterno,u.tipo
      FROM asignacionesdetalles AS ad
      INNER JOIN usuarios AS u ON u.idu=ad.idu 
      INNER JOIN facturas AS f ON f.idFactura=ad.idFactura  
      WHERE ad.idFactura= ?",[$idFactura]);

      $consulta= asignacionesDetalles::orderBy('idAsigDet','desc')
        ->take(1)
        ->get();
                   
      //$usuario = \DB::select("SELECT * FROM usuarios WHERE idu NOT IN (SELECT idu FROM asignacionesdetalles WHERE idFactura = ?)",[$idFactura]);
      $usuario = usuarios::orderby('nombreUsuario','asc')->get();

      $factura = facturas::Where('idFactura','=',$idFactura)->get();
  
			return view ('asignacion')
      ->with('usuario',$usuario)
      ->with('factura',$factura[0])
      ->with('resultado',$resultado)
      ->with('consultaD',$consultaD[0])
      ->with('consulta',$consulta);
    }

  }

  public function reporteAsignacion(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }
    else{
      
      $idFactura = $request->idFactura;
      $consultaFactura=\DB::select("SELECT ad.idAsigDet, 
      ad.idu,ad.idFactura,f.numeroFactura, ad.porcentaje, 
      u.nombreUsuario,u.aPaterno,u.aMaterno,u.tipo
      FROM asignacionesdetalles AS ad
      INNER JOIN usuarios AS u ON u.idu=ad.idu 
      INNER JOIN facturas AS f ON f.idFactura=ad.idFactura 
      WHERE ad.idFactura= ?",[$idFactura]);

      $resultado2=\DB::select("SELECT SUM(porcentaje) as total
      FROM asignacionesdetalles 
      WHERE idFactura = ?",[$idFactura]);

      $consultaD=\DB::select("SELECT c.contacto,c.razonSocial, c.rfc FROM facturas AS v
      INNER JOIN clientes AS c ON c.idc= v.idc 
      WHERE idFactura=?",[$idFactura]);

      return view ('reporteAsignacion')
      ->with('consultaFactura',$consultaFactura)
      ->with('consultaD',$consultaD[0])
      ->with('resultado2',$resultado2[0]);
    }
  }

  public function carrito (Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }
    else{  

      $idf = $request->idFactura;
      $idu= $request->idu;
      // echo $idf. $idu;
      $resultado=\DB::select("SELECT COUNT(*) AS cuantos 
      FROM asignacionesdetalles
      WHERE idFactura= $idf AND idu = $idu"); 
      $cuantos =   $resultado[0]->cuantos;

      if($cuantos==0){
        
        $asigDetalles = new asignacionesdetalles;
        $asigDetalles->fecha =$request->fecha;
        $asigDetalles->idAsigDet = $request->idAsigDet;
        $asigDetalles->idFactura = $request->idFactura;
        $asigDetalles->idu = $request->idu;
        $asigDetalles->porcentaje = $request->porcentaje;
        $asigDetalles->save();

        $idFactura = $request->idFactura;
    
        $resultado=\DB::select("SELECT ad.idAsigDet, ad.idu,ad.idFactura,f.numeroFactura
        ,ad.porcentaje, u.nombreUsuario,u.aPaterno,u.aMaterno,u.tipo
        FROM asignacionesdetalles AS ad
        INNER JOIN usuarios AS u ON u.idu=ad.idu 
        INNER JOIN facturas AS f ON f.idFactura=ad.idFactura  
        WHERE ad.idFactura= ?",[$idFactura]);
            
        $resultado2=\DB::SELECT("SELECT SUM(porcentaje) as total
        FROM asignacionesdetalles 
        WHERE idFactura = ?",[$idFactura]);
        
        $consultaD=\DB::select("SELECT c.contacto,c.razonSocial, c.rfc FROM facturas AS v
        INNER JOIN clientes AS c ON c.idc= v.idc 
        WHERE idFactura=?",[$idFactura]);
  
        return view ('lista')
        ->with('idu',$idu)
        ->with('cuantos',$cuantos)
        ->with('resultado',$resultado)
        ->with('consultaD',$consultaD[0])
        ->with('resultado2',$resultado2[0]);  
      }
      else{
        $idFactura = $request->idFactura;
    
        $resultado=\DB::select("SELECT ad.idAsigDet, ad.idu,ad.idFactura,f.numeroFactura
        ,ad.porcentaje, u.nombreUsuario,u.aPaterno,u.aMaterno,u.tipo
        FROM asignacionesdetalles AS ad
        INNER JOIN usuarios AS u ON u.idu=ad.idu 
        INNER JOIN facturas AS f ON f.idFactura=ad.idFactura  
        WHERE ad.idFactura= ?",[$idFactura]);
            
        $resultado2=\DB::SELECT("SELECT SUM(porcentaje) as total
        FROM asignacionesdetalles 
        WHERE idFactura = ?",[$idFactura]);
        
        $consultaD=\DB::select("SELECT c.contacto,c.razonSocial, c.rfc FROM facturas AS v
        INNER JOIN clientes AS c ON c.idc= v.idc 
        WHERE idFactura=?",[$idFactura]);
  
        return view ('lista')
        ->with('cuantos',$cuantos)
        ->with('resultado',$resultado)
        ->with('consultaD',$consultaD[0])
        ->with('resultado2',$resultado2[0]); 
      }
    }
  }
  
  public function borraAsignacion(Request $request ){  
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }
    else{  

      $detalleBorrar = $request->idAsigDet; 
      $facturaBuscar = $request->idFactura;

      $cuantos = 0;
      
      $asig = asignacionesdetalles::find($detalleBorrar)->delete();
      
      $resultado=\DB::select("SELECT ad.idAsigDet, ad.idu,ad.idFactura,f.numeroFactura
      ,ad.porcentaje, u.nombreUsuario,u.aPaterno,u.aMaterno,u.tipo
      FROM asignacionesdetalles AS ad
      INNER JOIN usuarios AS u ON u.idu=ad.idu 
      INNER JOIN facturas AS f ON f.idFactura=ad.idFactura  
      WHERE ad.idFactura= ?",[$facturaBuscar]);
            
      $resultado2=\DB::SELECT("SELECT SUM(porcentaje) as total
      FROM asignacionesdetalles 
      WHERE idFactura = ?",[$facturaBuscar]);
        
      $consultaD=\DB::select("SELECT c.contacto,c.razonSocial, c.rfc FROM facturas AS v
      INNER JOIN clientes AS c ON c.idc= v.idc 
      WHERE idFactura=?",[$facturaBuscar]);
  
      return view ('lista')
      ->with('cuantos',$cuantos)
      ->with('resultado',$resultado)
      ->with('consultaD',$consultaD[0])
      ->with('resultado2',$resultado2[0]); 
    }
  }

  //////////////////////////////REPORTE FACTURAS ASIGNADAS//////////////////////////////////////////

  public function reporteFacturasAsignadas(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }
    else{
      $clientes1 = clientes::orderby('razonSocial','asc')->get();
      $consulta = \DB::select("SELECT v.estatusEntrega, v.estatusPago, v.estatusPortal,
      COUNT(asignacionesdetalles.`idu`)AS canti,v.cashFlow,v.archivoRemision, v.archivo,v.archivoFactura,v.archivoPago,v.xmlFactura,v.numeroFactura,v.idFactura,v.activo,v.tipoMoneda,
      DATE_FORMAT(v.fechaFactura,'%d %b %Y') AS fecha,
      DATE_FORMAT(v.fechaPago,'%d %b %Y') AS fechap, v.montoFactura,c.razonSocial AS cli FROM facturas AS v
      LEFT JOIN clientes AS c ON c.idc= v.idc
      LEFT JOIN asignacionesdetalles ON asignacionesdetalles.`idFactura` = v.`idFactura`
      GROUP BY v.idFactura
      ORDER BY v.fechaFactura DESC");
      
      return view ('reporteFacturasAsignadas')
      ->with('clientes1',$clientes1)
      ->with('consulta',$consulta);
    }
  }

  public function reporteFacturasAbajoAsignadas(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }
    else{
      $limpiar = $request->limpiar;      
      $fechaInicio = $request->fechaInicio;
      $fechaFin = $request->fechaFin;
      $idc = $request->idc;
      $idc = $request->idc;
      $estatusEntrega = $request->estatusEntrega;
      $estatusPortal = $request->estatusPortal;
      $estatusPago = $request->estatusPago;
      $cashFlow = $request->cashFlow;
      
      // if ($limpiar=="1") {
      //   $consulta = \DB::select("SELECT v.estatusEntrega, v.estatusPago, v.estatusPortal,
      //   COUNT(asignacionesdetalles.`idu`)AS canti,v.cashFlow,v.archivoRemision, v.archivo,v.archivoFactura,v.archivoPago,v.xmlFactura,v.numeroFactura,v.idFactura,v.activo,v.tipoMoneda,
      //   DATE_FORMAT(v.fechaFactura,'%d %b %Y') AS fecha,
      //   DATE_FORMAT(v.fechaPago,'%d %b %Y') AS fechap, v.montoFactura,c.razonSocial AS cli FROM facturas AS v
      //   LEFT JOIN clientes AS c ON c.idc= v.idc
      //   LEFT JOIN asignacionesdetalles ON asignacionesdetalles.`idFactura` = v.`idFactura`
      //   GROUP BY v.idFactura
      //   ORDER BY v.fechaFactura DESC");      
      // }else {
      //   $consulta = \DB::select("SELECT v.estatusEntrega, v.estatusPago, v.estatusPortal,
      //   COUNT(asignacionesdetalles.`idu`)AS canti,
      //   v.cashFlow,v.archivoRemision, v.archivo,v.archivoFactura,v.archivoPago,v.xmlFactura,
      //   v.numeroFactura,v.idFactura,v.activo,v.tipoMoneda,
      //   DATE_FORMAT(v.fechaFactura,'%d %b %Y') AS fecha,
      //   DATE_FORMAT(v.fechaPago,'%d %b %Y') AS fechap, v.montoFactura,c.razonSocial AS cli FROM facturas AS v
      //   LEFT JOIN clientes AS c ON c.idc= v.idc 
      //   LEFT JOIN asignacionesdetalles ON asignacionesdetalles.`idFactura` = v.`idFactura`
      //   WHERE (fechaFactura>=? AND fechaFactura<=? AND v.idc=?) AND (v.estatusEntrega=? OR v.estatusPortal=? OR v.estatusPago=? OR v.cashFlow=?)
      //   GROUP BY v.numeroFactura, v.idFactura,fechap, v.activo,v.montoFactura,c.razonSocial, v.estatusEntrega, v.estatusPortal, v.estatusPago, v.cashFlow
      //   ORDER BY v.fechaFactura DESC",[$fechaInicio,$fechaFin,$idc,$estatusEntrega,$estatusPortal,$estatusPago,$cashFlow]);
      // }
      // $cuantos = count($consulta);

      $consulta = \DB::select("SELECT v.estatusEntrega, v.estatusPago, v.estatusPortal,
        COUNT(asignacionesdetalles.`idu`)AS canti,
        v.cashFlow,v.archivoRemision, v.archivo,v.archivoFactura,v.archivoPago,v.xmlFactura,
        v.numeroFactura,v.idFactura,v.activo,v.tipoMoneda,
        DATE_FORMAT(v.fechaFactura,'%d %b %Y') AS fecha,
        DATE_FORMAT(v.fechaPago,'%d %b %Y') AS fechap, v.montoFactura,c.razonSocial AS cli FROM facturas AS v
        LEFT JOIN clientes AS c ON c.idc= v.idc 
        LEFT JOIN asignacionesdetalles ON asignacionesdetalles.`idFactura` = v.`idFactura`
        WHERE (fechaFactura>=? AND fechaFactura<=?)
        GROUP BY v.numeroFactura, v.idFactura,fechap, v.activo,v.montoFactura,c.razonSocial, v.estatusEntrega, v.estatusPortal, v.estatusPago, v.cashFlow
        ORDER BY v.fechaFactura DESC",[$fechaInicio,$fechaFin]);
      
      $cuantos = count($consulta);
      
      return view ('reporteFacturasAbajoAsignadas')
      ->with('consulta',$consulta)
      ->with('fechaInicio',$fechaInicio)
      ->with('fechaFin',$fechaFin)
      ->with('cuantos',$cuantos);
    }
  }

}
