<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\usuarios;
use App\clientes;
use App\sucursales;
use App\ordenCompra;
use App\proveedores;
use App\contactosucursales;

use Session;
use Carbon\Carbon;
use DB;
use PDF;
use Mail;
use App\anexGrid;


class pdfXmlOrdenComprasController extends Controller{

  public function pdfOrdenCompras($idOrden){

    $idOrden = Crypt::decrypt($idOrden);

    $consultaOrden = \DB::select("SELECT oc.idOrden,oc.idProveedor,oc.codigoOrden,oc.notasOrden,oc.cfdi,oc.domicilioEntrega,oc.tipoPago,oc.formato,oc.formaPago,
    oc.paymentTerms,oc.fechaOrden,oc.precioOrden,oc.importeOrden,FORMAT(oc.total,2) as total,oc.moneda,CONCAT(u.nombreUsuario,' ', u.aPaterno) AS usuario,u.telCel,u.correo,
    pv.razonSocialProv,pv.rfcProveedor,oc.vendorQuote,oc.detalleMetodoEnvio,oc.billTo,oc.telAdicional,oc.correoAdicional,
    oc.mostrarRfc,oc.mostrarTaxId,DATE_FORMAT(oc.dueDate,'%d/%M/%Y') as dueDate,
    CONCAT(pv.nombreProv,' ',IFNULL(pv.segundoNomProv,''),' ',IFNULL(pv.apellidoProv,'')) as nombreVendedor,
    CONCAT(IFNULL(CONCAT('/ ',pv.nombreProv2),''),' ',IFNULL(pv.segundoNomProv2,''),' ',IFNULL(pv.apellidoProv2,'')) as nombreVendedor2,
    CONCAT(pv.telefonoProveedor,' ',IFNULL(pv.movilProv,'')) as telefonoProveedor,pv.correoProveedor,
    IFNULL(CONCAT(', ',pv.telefonoProveedor2),'') as telefonoProveedor2,
    IFNULL(CONCAT(', ',pv.correoProveedor2),'') as correoProveedor2 ,
    CONCAT(pv.calleProv,' ',pv.ciudadProv,' ',pv.estadoProv,' ',pv.codigoPostalProv) as direccionProveedor,oc.nombreEmpresa,
    oc.incoterm,oc.detalleIncoterm
    FROM ordenCompra AS oc 
    INNER JOIN proveedores AS pv ON pv.idProveedor = oc.idProveedor
    INNER JOIN usuarios AS u ON u.idu= oc.idu
    WHERE idOrden=?",[$idOrden]);
    
    $consultaProductosOrden = \DB::select("SELECT poc.idOrden,poc.notasProducto,poc.idEquipos,IFNULL(e.placa_1,'Sin archivo') as placa1,IFNULL(e.placa_2,'Sin archivo') as placa2,
    poc.codigo,poc.cantidadOrden,poc.nombreRefaccion,poc.vendorPartNumber,
    FORMAT(poc.subtotal,2) as subtotal, poc.ivaOrden, FORMAT(poc.ivaDinero,2) ivaDinero, poc.isrOrden,
    FORMAT(poc.isrDinero,2) as isrDinero, FORMAT(poc.precioProducto,2) as precioProducto,
    FORMAT(poc.precioOrden,2) as precioOrden,poc.moneda, poc.idRefaccionCotizacion, poc.idRepcotizacion
    FROM productosOrdenCompra as poc
    LEFT JOIN recepcionEquipos as e ON e.idEquipos=poc.idEquipos    
    WHERE idOrden=?",[$idOrden]);

    $sumaTotalImpuestos=\DB::select("SELECT oc.moneda, oc.idFactura, FORMAT(SUM(precioProducto),2) AS total,
    FORMAT(SUM(isrDinero),2) as isrDineroTotal,
    FORMAT(SUM(ivaDinero),2) as ivaDineroTotal,
    FORMAT(SUM(retencionDinero),2) as retencionDineroTotal,
    FORMAT(SUM(subtotal),2) as importe
    FROM productosOrdenCompra AS pc
    INNER JOIN ordenCompra as oc ON pc.idOrden=oc.idOrden
    WHERE pc.idOrden= $idOrden");
    // return $consultaOrden;
    $cadenaFecha= $consultaOrden[0]->fechaOrden;
    $arrayFecha=explode('-',$cadenaFecha);

    // if ($consultaOrden[0]->formato == 'Español') {      
    //   $pdf = \PDF::loadView('pdfOrdenCompra',['consultaOrden'=>$consultaOrden,'consultaProductosOrden'=>$consultaProductosOrden,'arrayFecha'=>$arrayFecha,'sumaTotalImpuestos'=>$sumaTotalImpuestos[0]]);
    //   return $pdf->stream($cadenaFecha= $consultaOrden[0]->codigoOrden.'.pdf');
    // }else{
      $pdf = \PDF::loadView('pdfOrdenCompraIngles',['consultaOrden'=>$consultaOrden,'consultaProductosOrden'=>$consultaProductosOrden,'arrayFecha'=>$arrayFecha,'sumaTotalImpuestos'=>$sumaTotalImpuestos[0]]);
      return $pdf->stream($cadenaFecha= $consultaOrden[0]->codigoOrden.'.pdf');
    // }
  }

  public function guardarXmlAndPdf(Request $request){
    $idOrden=$request->idOrden;
    $folioConfirmacion=$request->folioConfirmacion;    

    $consultaTotalOrden = \DB::select("SELECT oc.total,oc.moneda,oc.xmlFacturaProveedor,oc.pdfFacturaProveedor,oc.idProveedor,pv.rfcProveedor 
    FROM ordenCompra AS oc 
    INNER JOIN proveedores AS pv ON pv.idProveedor = oc.idProveedor WHERE idOrden=?",[$idOrden]);

    $file = $request->file('pdfFacturaProveedor');
    if($file!=""){
      $ldate = date('Ymd_His_');
      $pdfFacturaProveedor = $file->getClientOriginalName();
      $pdfFacturaProveedorName = $ldate.$pdfFacturaProveedor;
      $pdfFacturaProveedorName = str_replace(" ","_",$pdfFacturaProveedorName);
      \Storage::disk('local')->put($pdfFacturaProveedorName, \File::get($file));
    }

    $file = $request->file('xmlFacturaProveedor');
    if($file!=""){
      $ldate = date('Ymd_His_');
      $xmlFacturaProveedor = $file->getClientOriginalName();
      $xmlFacturaProveedorName = $ldate.$xmlFacturaProveedor;
      $xmlFacturaProveedorName = str_replace(" ","_",$xmlFacturaProveedorName);      
      \Storage::disk('local')->put($xmlFacturaProveedorName, \File::get($file));
      
      $ordenCompra = ordenCompra::find($idOrden);        
      $ordenCompra-> pdfFacturaProveedor = $pdfFacturaProveedorName;
      $ordenCompra-> xmlFacturaProveedor = $xmlFacturaProveedorName;
      $ordenCompra-> save();
      
      
      $totalOrdenCompra= $consultaTotalOrden[0]->total;
      $monedaOrdenCompra= $consultaTotalOrden[0]->moneda;
      $idProveedor= $consultaTotalOrden[0]->idProveedor;
      $rfcProveedor= $consultaTotalOrden[0]->rfcProveedor;
      
      // Obteniendo el archivo XML de su ubicacion
      $xml_path = asset('public/archivos/'.$consultaTotalOrden[0]->xmlFacturaProveedor);
      // $xml = simplexml_load_file($xml_path); 
      
      $xmlString = file_get_contents($xml_path);
      $xmlObject = simplexml_load_string($xmlString);
  
      $json = json_encode($xmlObject);
      $phpArray = json_decode($json, true); 
  
      // obteniendo los RFC del XML
      $factura = simplexml_load_string($xmlString);
      $namespaces = $factura -> getNamespaces(true);
      $factura -> registerXPathNamespace('c', $namespaces['cfdi']);
      
      foreach ($factura->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){ 
        $rfcEmisorEnXml = ($Emisor['Rfc']);      
      }
      foreach ($factura->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){ 
        $rfcReceptorEnXml = ($Receptor['Rfc']);      
      }    
  
      foreach ($phpArray as $key => $value) {
        $totalEnXml = ($value["Total"]);
        $monedaEnXml = ($value["Moneda"]);
      }

    }else {
      $xmlFacturaProveedorName = 'Sin archivo';
      $totalOrdenCompra = 1;
      $totalEnXml = 1;
      $monedaOrdenCompra = 1;
      $monedaEnXml = 1;
      $rfcProveedor= 1;
      $rfcEmisorEnXml = 1;
      $rfcReceptorEnXml ='GCM1809125LA';
      $idProveedor= $consultaTotalOrden[0]->idProveedor;
      
      $ordenCompra = ordenCompra::find($idOrden);        
      $ordenCompra-> pdfFacturaProveedor = $pdfFacturaProveedorName;
      $ordenCompra-> xmlFacturaProveedor = $xmlFacturaProveedorName;
      $ordenCompra-> save();    
    }

    if($totalOrdenCompra == $totalEnXml && $monedaOrdenCompra == $monedaEnXml && $rfcProveedor== $rfcEmisorEnXml && 
    ('GCM1809125LA'== $rfcReceptorEnXml || 'TOHA800103P69'== $rfcReceptorEnXml || 'SIN131106V82'== $rfcReceptorEnXml || 'OEJP740203NI2'== $rfcReceptorEnXml || 'GOAY921221EH3'== $rfcReceptorEnXml)){
      $mensaje='Archivos agregados correctamente';
      $tipoMensaje="ok";

      //CORREO DE PROVEEDOR PARA CARGA DE PDF Y XML FACTURA///

      $consultaProveedores = \DB::select("SELECT * from proveedores WHERE idProveedor = $idProveedor");  
      $correoProveedor = $consultaProveedores[0]->correoProveedor; //CORREO
      $razonSocial = $consultaProveedores[0]->razonSocialProv; //RAZON SOCIAL
      $consultaOrden = \DB::select("SELECT * FROM ordenCompra WHERE idOrden = $idOrden"); //CONSULTA PARA ENVIAR EN EL CORREO EL CODIGO DE LA OC
	      // DATOS PARA ENVIO DEL CORREO
	      $subject = 'Confirmación de carga de archivos'; //ASUNTO DEL CORREO
        $datos = array(
            'folioConfirmacion'=>$folioConfirmacion,
            'consulta'=>$consultaOrden[0]); //DATOS QUE SE ENVIAN A LA VISTA DEL CORREO
        Mail::send('correoArcProv', $datos, function($msj) use($subject,$correoProveedor){ //VISTA, DATOS QUE ENVIA, ASUNTO Y A QUE CORREO SE ENVIA
            $msj->from("informacion@germancontrolmotion.com","German Control Motion"); //REMITENTE
            $msj->subject($subject); //SE ENVIA EL ASUNTO
            $msj->to($correoProveedor); //A QUIEN SE ENVIA(CORREO)
        });
        
        $fc = "Su folio de confirmación es $folioConfirmacion"; //MENSAJE QUE SALE EN LA VISTA DE "mensajeXMLyPDF"

        
        //CORREO QUE SE LE ENVIA CREADOR DE LA OC PARA NOTIFICAR LA CARGA DE LA FACTURA DE L OC ANTERIORMENTE ERA SOLO AL CORREO DEL ADMIN
        
        $correoCreadorOc = \DB::select("SELECT (SELECT u.correo FROM usuarios AS u WHERE u.idu = oc.idu) AS correo 
        FROM ordenCompra AS oc WHERE idOrden = $idOrden");
        $correoCreadorOc = $correoCreadorOc[0]->correo;
        // $correoAdmin = "finanzas@gcmgdl.com";//cambiar al de finanzas@gcmgdl.com (este solo es prueba)
        $subject = 'Archivos para orden de compra.';//ASUNTO
        $datos = array(
            'consultaProv'=>$consultaProveedores[0],
            'consultaOrd'=>$consultaOrden[0]); //DATOS QUE ENVIA A LA VISTA DEL CORREO
        Mail::send('correoConfAdmin', $datos, function($msj) use($subject,$correoCreadorOc){
            $msj->from("informacion@germancontrolmotion.com","German Control Motion");
            $msj->subject($subject);
            $msj->to($correoCreadorOc);
        });


      $ordenCompra = ordenCompra::find($idOrden);        
      $ordenCompra-> estatus = "Archivos agregados, En espera de programación de pago";
      $ordenCompra-> folioConfirmacion = $folioConfirmacion;
      $ordenCompra-> save();

      //AQUI HAS LO DEL CORREO JOSS

      return view ('mensajeXMLyPDF')        
      ->with('mensaje',$mensaje)
      ->with('tipoMensaje',$tipoMensaje)
      ->with('idOrden',$idOrden)
      ->with('idProveedor', $request->idProveedor)
      ->with('fc',$fc);

    }else{
      
      \File::delete('public/archivos/'.$consultaTotalOrden[0]->xmlFacturaProveedor);
      \File::delete('public/archivos/'.$consultaTotalOrden[0]->pdfFacturaProveedor);
      
      $ordenCompra = ordenCompra::find($idOrden);        
      $ordenCompra-> pdfFacturaProveedor ="";
      $ordenCompra-> xmlFacturaProveedor ="";
      $ordenCompra-> save();    

      if($totalOrdenCompra != $totalEnXml){
        $mensaje1='El total de los archivos agregados no coincide con el total de la orden de compra.-';
      }else{
        $mensaje1='';
      }
      
      if($monedaOrdenCompra != $monedaEnXml){
        $mensaje2='La moneda de los archivos agregados no coincide con la moneda de la orden de compra.-';
      }else{
        $mensaje2='';
      }
      if($rfcProveedor != $rfcEmisorEnXml){
        $mensaje3='El RFC emisor de los archivos agregados no coincide con el RFC de la orden de compra.-';
      }else{
        $mensaje3='';
      }
      if('GCM1809125LA' != $rfcReceptorEnXml && 'TOHA800103P69' != $rfcReceptorEnXml && 'SIN131106V82' != $rfcReceptorEnXml && 'OEJP740203NI2'!= $rfcReceptorEnXml && 'GOAY921221EH3' != $rfcReceptorEnXml){
        $mensaje4='El RFC receptor de los archivos agregados no coincide con el RFC de la orden de compra.-';
      }else{
        $mensaje4='';
      }

      $mensaje = $mensaje1.$mensaje2.$mensaje3.$mensaje4;
      $mensaje = explode("-",$mensaje);
      // return $mensaje;
      $tipoMensaje="error";
      return view ('mensajeXMLyPDF')        
      ->with('mensaje',$mensaje)
      ->with('tipoMensaje',$tipoMensaje)
      ->with('idOrden',$idOrden)
      ->with('idProveedor',$idProveedor);
    }
    
  }

  public function guardarComplementoXmlAndPdf(Request $request){
    $idOrden=$request->idOrden;
    $folioConfirmacion=$request->folioConfirmacion;    

    $consultaTotalOrden = \DB::select("SELECT oc.montoComplementoPago,oc.monedaComplementoPago,oc.xmlComplementoProveedor,oc.pdfComplementoProveedor,oc.idProveedor,pv.rfcProveedor 
    FROM ordenCompra AS oc 
    INNER JOIN proveedores AS pv ON pv.idProveedor = oc.idProveedor WHERE idOrden=?",[$idOrden]);

    $file = $request->file('pdfComplementoProveedor');
    if($file!=""){
      $ldate = date('Ymd_His_');
      $pdfComplementoProveedor = $file->getClientOriginalName();
      $pdfComplementoProveedorName = $ldate.$pdfComplementoProveedor;
      $pdfComplementoProveedorName = str_replace(" ","_",$pdfComplementoProveedorName);      
      \Storage::disk('local')->put($pdfComplementoProveedorName, \File::get($file));
    }

    $file = $request->file('xmlComplementoProveedor');
    if($file!=""){
      
      $ldate = date('Ymd_His_');
      $xmlComplementoProveedor = $file->getClientOriginalName();
      $xmlComplementoProveedorName = $ldate.$xmlComplementoProveedor;
      $xmlComplementoProveedorName = str_replace(" ","_",$xmlComplementoProveedorName);      
      \Storage::disk('local')->put($xmlComplementoProveedorName, \File::get($file));

      $ordenCompra = ordenCompra::find($idOrden);        
      $ordenCompra-> pdfComplementoProveedor = $pdfComplementoProveedorName;
      $ordenCompra-> xmlComplementoProveedor = $xmlComplementoProveedorName;
      $ordenCompra-> save();        


      $totalOrdenCompra= $consultaTotalOrden[0]->montoComplementoPago;
      $monedaOrdenCompra= $consultaTotalOrden[0]->monedaComplementoPago;
      $idProveedor= $consultaTotalOrden[0]->idProveedor;
      $rfcProveedor= $consultaTotalOrden[0]->rfcProveedor;

      // Obteniendo el archivo XML de su ubicacion
      $xml_path = asset('public/archivos/'.$consultaTotalOrden[0]->xmlComplementoProveedor);
      // $xml = simplexml_load_file($xml_path); 
      
      $xmlString = file_get_contents($xml_path);
      $xmlObject = simplexml_load_string($xmlString);

      $json = json_encode($xmlObject);
      $phpArray = json_decode($json, true); 

      // obteniendo los RFC del XML
      $factura = simplexml_load_string($xmlString);
      $namespaces = $factura -> getNamespaces(true);
      $factura -> registerXPathNamespace('c', $namespaces['cfdi']);
      
      foreach ($factura->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){ 
        $rfcEmisorEnXml = ($Emisor['Rfc']);      
      }
      foreach ($factura->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){ 
        $rfcReceptorEnXml = ($Receptor['Rfc']);      
      }    

      // Se agregaron estas lineas ya que para los complementos se evalua el nodo de pago10:DoctoRelacionado
      foreach ($factura->xpath('//cfdi:Comprobante//pago10:DoctoRelacionado') as $value){ 
        $totalEnXml = ($value["ImpPagado"]);
        $monedaEnXml = ($value["MonedaDR"]);      
      } 
      
      // foreach ($phpArray as $key => $value) {
      //   $totalEnXml = ($value["ImpPagado"]);
      //   $monedaEnXml = ($value["MonedaDR"]);
      // }

    }else {
      
      $xmlComplementoProveedorName = 'Sin archivo';
      $totalOrdenCompra = 1;
      $totalEnXml = 1;
      $monedaOrdenCompra = 1;
      $monedaEnXml = 1;
      $rfcProveedor= 1;
      $rfcEmisorEnXml = 1;
      $rfcReceptorEnXml ='GCM1809125LA';
      $idProveedor= $consultaTotalOrden[0]->idProveedor;
      
      $ordenCompra = ordenCompra::find($idOrden);        
      $ordenCompra-> pdfComplementoProveedor = $pdfComplementoProveedorName;
      $ordenCompra-> xmlComplementoProveedor = $xmlComplementoProveedorName;
      $ordenCompra-> save();        

    }    

    if($totalOrdenCompra == $totalEnXml && $monedaOrdenCompra == $monedaEnXml && $rfcProveedor== $rfcEmisorEnXml && 
    ('GCM1809125LA'== $rfcReceptorEnXml || 'TOHA800103P69'== $rfcReceptorEnXml || 'SIN131106V82'== $rfcReceptorEnXml || 'OEJP740203NI2'== $rfcReceptorEnXml || 'GOAY921221EH3'== $rfcReceptorEnXml)){
      $mensaje='Archivos de complemento agregados correctamente';
      $tipoMensaje="ok";      
      
      //CORREO DE NOTIFICACION PARA CARGA DEL COMPLEMENTO DE PAGO (ADMIN)
      $consultaProveedores = \DB::select("SELECT * from proveedores WHERE idProveedor = $idProveedor");
      $correoProveedor = $consultaProveedores[0]->correoProveedor;
      $razonSocial = $consultaProveedores[0]->razonSocialProv;
      $consultaOrden = \DB::select("SELECT * FROM ordenCompra WHERE idOrden = $idOrden");
      
      $fc = "Se ha notificado la carga del complemento de pago"; //SE ENVIA A LA VISTA DE "mensajeXMLyPDF"

      $correoCreadorOc = \DB::select("SELECT (SELECT u.correo FROM usuarios AS u WHERE u.idu = oc.idu) AS correo 
      FROM ordenCompra AS oc WHERE idOrden = $idOrden");
      $correoCreadorOc = $correoCreadorOc[0]->correo;
      // $correoAdmin = "finanzas@gcmgdl.com";//cambiar al de finanzas@gcmgdl.com (este solo es prueba)
      $subject = 'Complementos de pago.';
      $datos = array(
          'consultaProv'=>$consultaProveedores[0],
          'consultaOrd'=>$consultaOrden[0]);
      Mail::send('correoComp', $datos, function($msj) use($subject,$correoCreadorOc){
          $msj->from("informacion@germancontrolmotion.com","German Control Motion");
          $msj->subject($subject);
          $msj->to($correoCreadorOc);
      });


      $ordenCompra = ordenCompra::find($idOrden);        
      $ordenCompra-> estatus ="Pagado con complementos de pago";
      $ordenCompra-> save();    

      return view ('mensajeXMLyPDF')        
      ->with('mensaje',$mensaje)
      ->with('tipoMensaje',$tipoMensaje)
      ->with('idOrden',$idOrden)
      ->with('idProveedor',$request->idProveedor)
      ->with('fc',$fc);

    }else{
      
      \File::delete('public/archivos/'.$consultaTotalOrden[0]->xmlComplementoProveedor);
      \File::delete('public/archivos/'.$consultaTotalOrden[0]->pdfComplementoProveedor);
      
      $ordenCompra = ordenCompra::find($idOrden);        
      $ordenCompra-> pdfComplementoProveedor ="";
      $ordenCompra-> xmlComplementoProveedor ="";
      $ordenCompra-> save();    

      if($totalOrdenCompra != $totalEnXml){
        $mensaje1='El total de los archivos agregados no coincide con el total de la orden de compra.-';
      }else{
        $mensaje1='';
      }
      
      if($monedaOrdenCompra != $monedaEnXml){
        $mensaje2='La moneda de los archivos agregados no coincide con la moneda de la orden de compra.-';
      }else{
        $mensaje2='';
      }
      if($rfcProveedor != $rfcEmisorEnXml){
        $mensaje3='El RFC emisor de los archivos agregados no coincide con el RFC de la orden de compra.-';
      }else{
        $mensaje3='';
      }
      if('GCM1809125LA' != $rfcReceptorEnXml && 'TOHA800103P69' != $rfcReceptorEnXml && 'SIN131106V82' != $rfcReceptorEnXml && 'OEJP740203NI2' != $rfcReceptorEnXml && 'GOAY921221EH3' != $rfcReceptorEnXml){
        $mensaje4='El RFC receptor de los archivos agregados no coincide con el RFC de la orden de compra.-';
      }else{
        $mensaje4='';
      }

      $mensaje = $mensaje1.$mensaje2.$mensaje3.$mensaje4;
      $mensaje = explode("-",$mensaje);
      // return $mensaje;
      $tipoMensaje="error";
      return view ('mensajeXMLyPDF')        
      ->with('mensaje',$mensaje)
      ->with('tipoMensaje',$tipoMensaje)
      ->with('idOrden',$idOrden)
      ->with('idProveedor',$idProveedor);
    }
    
  }

  public function confirmarOrdenCompra($idOrden,$siNo){
    $idOrden = Crypt::decrypt($idOrden);
    $siNo = Crypt::decrypt($siNo);
    
    $consulta = ordenCompra::where('idOrden','=',$idOrden)->where('activo','=','Si')->orderBy('fechaOrden','DESC')->get();
    $sumaTotalImpuestos=\DB::select("SELECT oc.moneda, oc.idFactura, FORMAT(SUM(precioProducto),2) AS total,
    FORMAT(SUM(isrDinero),2) as isrDineroTotal,
    FORMAT(SUM(ivaDinero),2) as ivaDineroTotal,
    FORMAT(SUM(subtotal),2) as importe
    FROM productosOrdenCompra AS pc
    INNER JOIN ordenCompra as oc ON pc.idOrden=oc.idOrden
    WHERE pc.idOrden= $idOrden");
    
    return view("confirmacionProveedorOrdenCompra")
    ->with('sumaTotalImpuestos',$sumaTotalImpuestos[0])
    ->with('idOrden',$idOrden)
    ->with('consulta',$consulta)
    ->with('siNo',$siNo);
      
    
  }

  public function guardarConfirmacionOrdenCompra(Request $request){
    $idOrden=$request->idOrden; 
    $idProveedor=$request->idProveedor;    

    if ($request->siNo == 'si') {
      
      if ($request->paso == 2) {
        
        $file = $request->file('archivoPaqueteria1');
        if($file!=""){
          $ldate = date('Ymd_His_');
          $archivoPaqueteria1 = $file->getClientOriginalName();
          $archivoPaqueteria1Name = $ldate.$archivoPaqueteria1;
          \Storage::disk('local')->put($archivoPaqueteria1Name, \File::get($file));
        }else{
          $archivoPaqueteria1Name='';
        }
  
        $file = $request->file('archivoPaqueteria2');
        if($file!=""){
          $ldate = date('Ymd_His_');
          $archivoPaqueteria2 = $file->getClientOriginalName();
          $archivoPaqueteria2Name = $ldate.$archivoPaqueteria2;
          \Storage::disk('local')->put($archivoPaqueteria2Name, \File::get($file));
        }else{
          $archivoPaqueteria2Name='';
        }
        
        $file = $request->file('archivoPaqueteria3');
        if($file!=""){
          $ldate = date('Ymd_His_');
          $archivoPaqueteria3 = $file->getClientOriginalName();
          $archivoPaqueteria3Name = $ldate.$archivoPaqueteria3;
          \Storage::disk('local')->put($archivoPaqueteria3Name, \File::get($file));
        }else{
          $archivoPaqueteria3Name='';
        }

        $file = $request->file('archivoPaqueteria4');
        if($file!=""){
          $ldate = date('Ymd_His_');
          $archivoPaqueteria4 = $file->getClientOriginalName();
          $archivoPaqueteria4Name = $ldate.$archivoPaqueteria4;
          \Storage::disk('local')->put($archivoPaqueteria4Name, \File::get($file));
        }else{
          $archivoPaqueteria4Name='';
        }

      }
  
      $ordenCompra = ordenCompra::find($idOrden);
      if ($request->paso == 1) {
        $ordenCompra-> fechaEntregaProveedor = $request->fechaEntregaProveedor;
        
        $consultaProveedores = \DB::select("SELECT * from proveedores WHERE idProveedor = $idProveedor");  
        // $correoProveedor = $consultaProveedores[0]->correoProveedor; 
        
        $correoCreadorOc = \DB::select("SELECT (SELECT u.correo FROM usuarios AS u WHERE u.idu = oc.idu) AS correo 
        FROM ordenCompra AS oc WHERE idOrden = $idOrden");
        $correoCreadorOc = $correoCreadorOc[0]->correo;
        
        // $correoAdmin ="al221811708@gmail.com"; //"finanzas@gcmgdl.com";
        $razonSocial = $consultaProveedores[0]->razonSocialProv;
        $ordenCompra-> estatusProveedor = 'Pendiente de aceptación, con fecha entrega'; 
        $consultaOrden = \DB::select("SELECT * FROM ordenCompra WHERE idOrden = $idOrden"); 
	      $subject = 'Fecha de entrega registrada.';
        $datos = array(
            'fechaEntregaProveedor'=>$ordenCompra->fechaEntregaProveedor,
            'consultaprov'=>$consultaProveedores[0],
            'consultaOrd'=>$consultaOrden[0],
            ); 
        Mail::send('correoFechaReg', $datos, function($msj) use($subject,$correoCreadorOc){ 
            $msj->from("informacion@germancontrolmotion.com","German Control Motion"); //zyqaiipittxoufzn
            $msj->subject($subject); 
            $msj->to($correoCreadorOc); 
        });

      }
      if ($request->paso == 2) {

        $consultaProveedores = \DB::select("SELECT * from proveedores WHERE idProveedor = $idProveedor");  
        $correoProveedor = $consultaProveedores[0]->correoProveedor; 
        $razonSocial = $consultaProveedores[0]->razonSocialProv; 
        $consultaOrden = \DB::select("SELECT * FROM ordenCompra WHERE idOrden = $idOrden"); 
	      $subject = 'Aceptación de orden de compra';
        $datos = array(
            'consultaprov'=>$consultaProveedores[0],
            'consultaOrd'=>$consultaOrden[0]); 
        Mail::send('correoAprobOC', $datos, function($msj) use($subject,$correoProveedor){ 
            $msj->from("informacion@germancontrolmotion.com","German Control Motion"); //zyqaiipittxoufzn
            $msj->subject($subject); 
            $msj->to($correoProveedor); 
        });

        $ordenCompra-> metodoEntrega = $request->metodoEntrega;
        $ordenCompra-> detalleEntrega = $request->detalleEntrega;
        $ordenCompra-> archivoPaqueteria1 = $archivoPaqueteria1Name;
        $ordenCompra-> archivoPaqueteria2 = $archivoPaqueteria2Name;
        $ordenCompra-> archivoPaqueteria3 = $archivoPaqueteria3Name;
        $ordenCompra-> archivoPaqueteria4 = $archivoPaqueteria4Name;
        $ordenCompra-> estatusProveedor = 'Aceptado con fecha de entrega';
      }
      $ordenCompra-> save();    
    
      if ($request->paso == 2) {
        $mensaje = 'Confirmación finalizada con éxito';
      }else{
        $mensaje = 'fecha de entrega agregada con éxito';
      }
      $tipoMensaje = "ok";

    }else {
        
      $consultaProveedores = \DB::select("SELECT * from proveedores WHERE idProveedor = $idProveedor");  
      $correoProveedor = $consultaProveedores[0]->correoProveedor; 
      $razonSocial = $consultaProveedores[0]->razonSocialProv; 
      $consultaOrden = \DB::select("SELECT * FROM ordenCompra WHERE idOrden = $idOrden"); 
      $subject = 'Rechazo de orden de compra';
      $datos = array(
          'consultaprov'=>$consultaProveedores[0],
          'consulta'=>$consultaOrden[0]); 
      Mail::send('correoRechOC', $datos, function($msj) use($subject,$correoProveedor){ 
          $msj->from("informacion@germancontrolmotion.com","German Control Motion"); 
          $msj->subject($subject); 
          $msj->to($correoProveedor); 
      });
      
      $ordenCompra = ordenCompra::find($idOrden);
      $ordenCompra-> motivoRechazoOrden = $request->motivoRechazoOrden;
      $ordenCompra-> estatusProveedor = 'No aceptado';      
      $ordenCompra-> save();
      
      $mensaje = 'Orden de compra no aceptada';
      $tipoMensaje = "noAceptada";
    
    }

    $fc='';
    
    
    return view ('mensajeXMLyPDF')        
    ->with('mensaje',$mensaje)
    ->with('tipoMensaje',$tipoMensaje)
    ->with('idOrden',$idOrden)    
    ->with('fc',$fc);
  }

  public function encriptarIdPdf(Request $request){
    return Crypt::encrypt($request->idOrden);
  }
}