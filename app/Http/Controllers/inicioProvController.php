<?php
namespace App\Http\Controllers;
use App\proveedores;
use App\ordenCompra;
use App\productosOrdenCompra;
use Session;


class inicioProvController extends Controller
{
  public function inicioProveedor(){

    $snameprov = Session::get('sesionnameprov');
    $sidprov = Session::get('sesionidproveedor');
    $ssegnom = Session::get('sesionsegnom');
    $sap = Session::get('sesionap');

    if( $sidprov =='' ){
      Session::flash('error','Es necesario loguearse antes de continuar');
      return redirect()->route('accesoProveedores');
    }
    else {
      
      $consultaArchivosProveedor = \DB::select("SELECT IFNULL(v.archivoCuentas,'Sin archivo') as archivoCuentas, IFNULL(v.constanciaFiscal,'Sin archivo') as constanciaFiscal 
      FROM proveedores AS v Where idProveedor = $sidprov ");
      
      // $consultaAntes= \DB::select("SELECT oc.codigoOrden,oc.idProveedor,oc.fechaOrden,oc.fechaEntregaProveedor,
      // oc.fechaProgPago,oc.fechaDeposito,oc.total,oc.moneda,oc.montoComplementoPago,
      // oc.monedaComplementoPago,oc.estatus,oc.folioConfirmacion,oc.idOrden,oc.pdfFacturaProveedor,
      // oc.xmlFacturaProveedor,oc.evidenciaTransferencia,oc.pdfComplementoProveedor,
      // oc.xmlComplementoProveedor,oc.estatusProveedor,oc.notasCambioFechaEntregaProveedor   
      // FROM ordenCompra AS oc 
      // WHERE oc.idProveedor=$sidprov 
      // AND oc.idOrden IN (SELECT poc.idOrden FROM productosOrdenCompra AS poc)
      // ORDER BY oc.idOrden DESC");

      $ordendesCompra= \DB::select("SELECT oc.codigoOrden,oc.idProveedor,
      DATE_FORMAT(oc.fechaOrden,'%d/%m/%Y') AS fechaOrden,
      CONCAT(oc.moneda,' $',oc.total) AS montoOrden,
      oc.total,oc.moneda,oc.idOrden,
      (SELECT COUNT(idNotaEntrada) FROM notasEntrada WHERE notasEntrada.idOrden = oc.idOrden AND (pdfComplemento_total_restante IS NULL OR xmlComplemento_total_restante IS NULL))AS notasPendientes,
      (SELECT COUNT(idNotaEntrada) FROM notasEntrada WHERE notasEntrada.idOrden = oc.idOrden AND (pdfComplemento_total_restante IS NOT NULL OR xmlComplemento_total_restante IS NOT NULL)) AS notasCerradas
      FROM ordenCompra AS oc 
      WHERE oc.idProveedor= $sidprov
      AND oc.idOrden IN (SELECT poc.idOrden FROM productosOrdenCompra AS poc)
      ORDER BY oc.idOrden DESC");
      
      return view ('inicioProveedor')
      ->with('consultaArchivosProveedor', $consultaArchivosProveedor[0])
      ->with('ordendesCompra', $ordendesCompra);
	  }
  }

  public function consultaOrdenesCompra(){
    $sidprov = Session::get('sesionidproveedor');

    $ordendesCompra= \DB::select("SELECT oc.codigoOrden,oc.idProveedor,
    DATE_FORMAT(oc.fechaOrden,'%d/%m/%Y') AS fechaOrden,
    oc.total,oc.moneda,oc.idOrden,
    (SELECT COUNT(idNotaEntrada) FROM notasEntrada WHERE notasEntrada.idOrden = oc.idOrden AND (pdfComplemento_total_restante IS NULL OR xmlComplemento_total_restante IS NULL))AS notasPendientes,
    (SELECT COUNT(idNotaEntrada) FROM notasEntrada WHERE notasEntrada.idOrden = oc.idOrden AND (pdfComplemento_total_restante IS NOT NULL OR xmlComplemento_total_restante IS NOT NULL)) AS notasCerradas
    FROM ordenCompra AS oc 
    WHERE oc.idProveedor= $sidprov
    AND oc.idOrden IN (SELECT poc.idOrden FROM productosOrdenCompra AS poc)
    ORDER BY oc.idOrden DESC");
      
    return response()->json($ordendesCompra, 200);
  }

  public function cuentasProveedor(){

    $snameprov = Session::get('sesionnameprov');
    $sidprov = Session::get('sesionidproveedor');
    $ssegnom = Session::get('sesionsegnom');
    $sap = Session::get('sesionap');

    if($sidprov =='' ){
      Session::flash('error','Es necesario loguearse antes de continuar');
      return redirect()->route('accesoProveedores');
    }
    else {
      
      $consultaArchivosProveedor = \DB::select("SELECT IFNULL(v.archivoCuentas,'Sin archivo') as archivoCuentas, IFNULL(v.constanciaFiscal,'Sin archivo') as constanciaFiscal FROM proveedores AS v Where idProveedor = $sidprov ");
      $consulta = ordenCompra::where('idProveedor','=',$sidprov)->where('activo','=','Si')->orderBy('fechaOrden','DESC')->get();
      
      return view ('cuentasProveedor')
      ->with('consultaArchivosProveedor', $consultaArchivosProveedor[0])
      ->with('sidprov', $sidprov)
      ->with('consulta', $consulta);
	  }
  }

  public function ocProv(){
    $snameprov = Session::get('sesionnameprov');
    $sidprov = Session::get('sesionidproveedor');
    $ssegnom = Session::get('sesionsegnom');
    // $sap = Session::get('sesionap');

    if($sidprov ==''){
      Session::flash('error','Es necesario loguearse antes de continuar');
      return redirect()->route('accesoProveedores');
    }
    else {
      
      $consulta = ordenCompra::where('idProveedor','=',$sidprov)->where('activo','=','Si')->orderBy('fechaOrden','DESC')->get();      
                                    
      return view("ocProv")
      ->with('consulta', $consulta);
    }

  }
}
