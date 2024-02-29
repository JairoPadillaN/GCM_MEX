<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdenesCompraExport;
use App\productosOrdenCompra;
use App\cuentas;
use App\productosServiciosAsignacion;
use App\proveedores;
use App\cuentasproveedores;
use App\facturas;
use App\sucursales;
use App\ordenCompra;
use App\clientes;
use App\recepcionEquipos;
use App\partesVenta;
// este archivo de marcas esta mal por el nombre pero asi se esta usando
use App\marcaRefaccion;
use App\anexGrid;
use App\notasEntrada;
use App\estatusEntregaNotasEntrada;
use App\estatusPagoNotasEntrada;
use App\detalleNotasEntrada;
use App\archivosNotaEntrada;
use Carbon\Carbon;
use Session;
use DB;
use Mail;

class notasEntradaController extends Controller{

    public function notasEntrada($idOrden){
        $stipo = Session::get('sesiontipo');

        $monedaOC= DB::table('ordenCompra')
        ->select('moneda')
        ->where('idOrden', '=', $idOrden)
        ->get();

        return view('notasEntrada')
        ->with('monedaOC',$monedaOC[0]->moneda)
        ->with('idOrden',$idOrden)
        ->with('tipoUsuario',$stipo);
    }

    public function consultaNotasEntrada($idOrden){
        $consulta=\DB::select("SELECT
            idNotaEntrada,
            idOrden,
            numeroNota,
            detallesEntrega,
            monedaNota,
            montoNotaTotal,
            aplicaAnticipo,
            porcentajeAnticipo,
            cantidadAnticipo,
            montoNotaRestante,
            monedaComplemento_anticipo,
            montoComplemento_anticipo,
            monedaComplemento_total,
            montoComplemento_total,
            pdfFactura_anticipo,
            xmlFactura_anticipo,
            pdfFactura_total_restante,
            xmlFactura_total_restante,
            evidenciaPago_anticipo,
            evidenciaPago_total_restante,
            pdfComplemento_anticipo,
            xmlComplemento_anticipo,
            pdfComplemento_total_restante,
            xmlComplemento_total_restante,
            DATE_FORMAT(fechaEntrega,'%d/%m/%Y') AS fechaEntrega,
            DATE_FORMAT(fechaPagoProgramada_total,'%d/%m/%Y') AS fechaPagoProgramada_total,
            DATE_FORMAT(fechaPagoProgramada_anticipo,'%d/%m/%Y') AS fechaPagoProgramada_anticipo,
            DATE_FORMAT(fechaRealPago_total,'%d/%m/%Y') AS fechaRealPago_total,
            DATE_FORMAT(fechaRealPago_anticipo,'%d/%m/%Y') AS fechaRealPago_anticipo,
            procesoAnticiposFinalizado,
            metodoEntrega,
            detalleEntrega,
            numGuia,
            ciudadOrigen,
            ciudadDestino,
            paqueteria,
            numGuia2,
            ciudadOrigen2,
            ciudadDestino2,
            paqueteria2,
            folioFacturaImportacion,
            folioGastosImportacion,
            archivoPaqueteria1,
            archivoPaqueteria2,
            archivoPaqueteria3,
            archivoPaqueteria4,
            idu,
            activo,
            (SELECT formato FROM ordenCompra WHERE ordenCompra.idOrden = notasEntrada.idOrden) AS formato,
            -- Montos y moneda anticipo
            (SELECT IFNULL(cambioDolar,1) FROM ordenCompra WHERE ordenCompra.idOrden = notasEntrada.idOrden) AS cambioDolar,
            (SELECT (cantidadAnticipo * IFNULL(cambioDolar,1)) FROM ordenCompra WHERE ordenCompra.idOrden = notasEntrada.idOrden) AS anticipoMXN,
            (SELECT (montoNotaRestante * IFNULL(cambioDolar,1)) FROM ordenCompra WHERE ordenCompra.idOrden = notasEntrada.idOrden) AS totalRestanteMXN,
            -- Montos y moneda anticipo de complementos
            (SELECT IFNULL(cambioDolar_anticipo,1) FROM ordenCompra WHERE ordenCompra.idOrden = notasEntrada.idOrden) AS cambioDolar_anticipoComplemento,
            (SELECT (montoComplemento_anticipo * IFNULL(cambioDolar_anticipo,1)) FROM ordenCompra WHERE ordenCompra.idOrden = notasEntrada.idOrden) AS anticipoComplementoMXN,
            -- Montos y moneda atotal o restante de complementos
            (SELECT IFNULL(cambioDolar_total,1) FROM ordenCompra WHERE ordenCompra.idOrden = notasEntrada.idOrden) AS cambioDolar_totalComplemento,
            (SELECT (montoComplemento_total * IFNULL(cambioDolar_total,1)) FROM ordenCompra WHERE ordenCompra.idOrden = notasEntrada.idOrden) AS totalRestanteComplementoMXN
            FROM notasEntrada
            WHERE idOrden = $idOrden AND activo = 'Si'"
        );
        
        return response()->json($consulta, 200);
    }

    public function crearNumeroNota($idOrden){
        // public function crearNumeroNota(Request $request){
        // $idOrden = $request->get('idOrden');

        $datosOrden = \DB::select("SELECT codigoOrden FROM ordenCompra WHERE idOrden = $idOrden");
        $codigoOrden = $datosOrden[0]->codigoOrden;

        $consulta = \DB::select("SELECT numeroNota FROM notasEntrada WHERE activo = 'Si' AND idOrden = $idOrden ORDER BY numeroNota DESC");

        if(count($consulta) < 1 ){
            $numeroNota = $codigoOrden."-01";
        }else{
            $ultimaNota = explode("-",$consulta[0]->numeroNota);
            $consecutivo = intval($ultimaNota[2]) + 1;
            $consecutivo = str_pad($consecutivo,2,"0",STR_PAD_LEFT);
            $numeroNota = $codigoOrden.'-'.$consecutivo;
        }

        return $numeroNota;
        // return response()->json($numeroNota, 200);
    }

    public function getProductosOC(Request $request){
        $idOrden=$request->idOrden;

        $productosEnOC = DB::table('productosOrdenCompra')
        ->select('idProducto','idPartesVenta','idParte','vendorPartNumber','notasProducto','codigo','nombreRefaccion','vendorPartNumber','notasProducto',
        DB::raw('precioOrden AS precioUnidad'),
        DB::raw('(ivaDinero / cantidadOrden) AS ivaUnidad'),
        DB::raw('CONCAT(IFNULL(codigo,"")," / ",IFNULL(nombreRefaccion,"")," / ",IFNULL(vendorPartNumber,"")) AS producto'),
        // DB::raw('CONCAT(codigo," / ",nombreRefaccion," / ",vendorPartNumber," / ",notasProducto) AS producto'),
        DB::raw('(cantidadOrden - cantidadNotaEntrada)AS faltaEntregar'),
        'cantidadOrden', 'cantidadNotaEntrada','estatusNotaEntrada')
        ->where('idOrden', '=', $idOrden)
        ->where('estatusNotaEntrada','=',0)
        ->orderBy('codigo','ASC')
        ->get();

        return $productosEnOC;
    }

    public function crearNotaEntrada(Request $request){
        $idOrden = $request->get('idOrden');
        $numeroNota = $this->crearNumeroNota($idOrden);

        $entrada = new notasEntrada;
        $entrada->idOrden = $idOrden;
        $entrada->numeroNota = $numeroNota;
        $entrada->idu = $request->idUsuarioCreador;
        $entrada->monedaNota = $request->moneda;
        $entrada->activo = "Si";
        $entrada->save();
        
        $idNotaCreada= $entrada->idNotaEntrada;

        $data = array(
            'numeroNota' => $numeroNota,
            'idNotaEntrada' => $idNotaCreada
        );

        return response()->json($data, 200);
    }

    public function agregarProductoNotaEntrada(Request $request){
        
        $detalleNota = new detalleNotasEntrada;
        $detalleNota->idNotaEntrada = $request->idNotaEntrada;
        $detalleNota->idProducto = $request->idProducto;
        $detalleNota->piezasPendientes = $request->piezasPendientes;
        $detalleNota->piezasRecibidas = $request->piezasRecibidas;
        $detalleNota->subtotal = $request->subtotal;
        $detalleNota->iva = $request->iva;
        $detalleNota->total = $request->total;
        $detalleNota->save();

        // Se envia tipo de actualizacion 1 = "sumar cantidad"
        $tipoActualizacion = 1;

        $this->actualizarCantidadesNotaEntrada($request->idProducto , $request->piezasRecibidas , $tipoActualizacion);

        return $this->consultaProdcutosPorNotaEntrada($request->idNotaEntrada);
    }

    public function actualizarCantidadesNotaEntrada($idProducto , $piezasRecibidas, $tipoActualizacion){
        // Consulta para obtener las cantidades actuales del producto
        $producto= DB::table('productosOrdenCompra')
            ->select('cantidadOrden','cantidadNotaEntrada')
            ->where('idProducto','=',$idProducto)        
        ->get();

        // Si el tipoActualizacion es = 1 entonces se agrego un producto ala entrada de lo contrario es una eliminacion de enrtrada de producto
        if($tipoActualizacion == 1){
            // Suma de la cantidad ya entregada con la nueva entrega
            $cantidadEntregada = $producto[0]->cantidadNotaEntrada + $piezasRecibidas;
        }else{
            // Resta de la cantidad ya entregada menos la del producto eliminado mientras haya 1 o mas
            if($producto[0]->cantidadNotaEntrada > 0){
                $cantidadEntregada = $producto[0]->cantidadNotaEntrada - $piezasRecibidas;
            }
        }

        // Actualización de la cantidad entregada
        \DB::select("UPDATE productosOrdenCompra SET cantidadNotaEntrada = ? WHERE idProducto = ?",[$cantidadEntregada,$idProducto]);

        // Actualización del estatus de entrega, si la cantidad entregada es igual a la 
        // cantidad que se pide en la OC entonces el estatus es " 1 = entregado" de lo contrario es "0 = incompleto"
        if($cantidadEntregada == $producto[0]->cantidadOrden){
            \DB::select("UPDATE productosOrdenCompra SET estatusNotaEntrada = 1 WHERE idProducto = ?",[$idProducto]);
        }else{
            \DB::select("UPDATE productosOrdenCompra SET estatusNotaEntrada = 0 WHERE idProducto = ?",[$idProducto]);
        }
        
        return;
    }

    public function consultaProdcutosPorNotaEntrada($idNotaEntrada){
        $consulta = \DB::select("SELECT
            (SELECT idServicios FROM facturas WHERE facturas.idFactura = (SELECT idFactura FROM productosOrdenCompra WHERE productosOrdenCompra.idProducto = detalleNotasEntrada.idProducto)) AS servicio,
            idDetalleNotasEntrada,
            idNotaEntrada,
            (SELECT CONCAT(codigo,' / ',nombreRefaccion) FROM productosOrdenCompra WHERE productosOrdenCompra.idProducto = detalleNotasEntrada.idProducto) AS sku,
            (SELECT vendorPartNumber FROM productosOrdenCompra WHERE productosOrdenCompra.idProducto = detalleNotasEntrada.idProducto) AS vpn,
            piezasRecibidas,
            subtotal,
            iva,
            total,
            (SELECT monedaNota FROM notasEntrada WHERE notasEntrada.idNotaEntrada = detalleNotasEntrada.idNotaEntrada) AS monedaNota,
            idProducto
            FROM detalleNotasEntrada
            WHERE idNotaEntrada = $idNotaEntrada
            ORDER BY idDetalleNotasEntrada DESC
        ");

        return response()->json($consulta, 200);
    }

    public function eliminarProductoNotaEntrada(Request $request){
        $idDetalleNotasEntrada = $request->idDetalleNotasEntrada;
        
        $idNotaEntrada = detalleNotasEntrada::select('idNotaEntrada')->Where('idDetalleNotasEntrada','=',$idDetalleNotasEntrada)->get();
        // eliminamos el registro de la BD
        \DB::delete("DELETE FROM detalleNotasEntrada WHERE idDetalleNotasEntrada = $idDetalleNotasEntrada");

        // Se envia tipo de actualizacion 0 = "restar cantidad"
        $tipoActualizacion = 0;
        $idProducto =$request->idProducto;
        $piezasRecibidas = $request->piezasRecibidas;

        $this->actualizarCantidadesNotaEntrada($idProducto , $piezasRecibidas , $tipoActualizacion);
        
        // return response()->json(200);
        return $this->consultaProdcutosPorNotaEntrada($idNotaEntrada[0]->idNotaEntrada);
    }

    public function guardarDatosNotaEntrada(Request $request){
        $entrada = notasEntrada::find($request->idNotaEntrada);
        $entrada->aplicaAnticipo = $request->aplicaAnticipo == "on" ? 1 : 0;
        $entrada->porcentajeAnticipo = !empty($request->porcentajeAnticipo) ? $request->porcentajeAnticipo : 0;
        $entrada->cantidadAnticipo = !empty($request->cantidadAnticipo) ? $request->cantidadAnticipo : 0;
        $entrada->montoNotaTotal = $request->montoNotaTotal;
        $entrada->montoNotaRestante = $request->montoNotaTotal - $request->cantidadAnticipo;
        $entrada->idNotaEntrada = $request->idNotaEntrada;
        $entrada->save();

        return response()->json(200);

    }

    public function eliminarNotaEntrada(Request $request){
        $idNotaEntrada = $request->idNotaEntrada;

        $productos = detalleNotasEntrada::Where('idNotaEntrada','=',$idNotaEntrada)->get();
        
        foreach ($productos as $key => $value) {
            $this->actualizarCantidadesNotaEntrada($value['idProducto'],$value['piezasRecibidas'],0);
        }
        
        \DB::delete("DELETE FROM detalleNotasEntrada WHERE idNotaEntrada = $idNotaEntrada");
        // \DB::UPDATE("UPDATE notasEntrada SET activo ='No' WHERE idNotaEntrada = $idNotaEntrada");
        \DB::delete("DELETE FROM notasEntrada WHERE idNotaEntrada = $idNotaEntrada");
        
        
        return response()->json(200);

    }

    public function actualizarFechasNotaEntrada(Request $request){
        $idNotaEntrada = $request->idNotaEntradaFechas;
        $fechaEntrega = $request->fechaEntrega;
        $fechaPagoProgramada_anticipo = $request->fechaPagoProgramada_anticipo;
        $fechaPagoProgramada_total = $request->fechaPagoProgramada_total;
        $fechaRealPago_anticipo = $request->fechaRealPago_anticipo;
        $fechaRealPago_total = $request->fechaRealPago_total;


        $notaEntrada = \DB::select("SELECT numeroNota,
        (SELECT razonSocialProv FROM proveedores AS p WHERE p.idProveedor = 
        (SELECT idProveedor FROM ordenCompra oc WHERE oc.idOrden = ne.idOrden)) AS proveedor,
        (SELECT correoProveedor FROM proveedores AS p WHERE p.idProveedor = 
        (SELECT idProveedor FROM ordenCompra oc WHERE oc.idOrden = ne.idOrden)) AS correoProveedor,
        (SELECT correo FROM usuarios AS u WHERE u.idu = ne.idu) AS correo
        FROM notasEntrada AS ne WHERE idNotaEntrada =?",[$idNotaEntrada]);
        $fecha = "";
        
        if(!empty($fechaEntrega)){
            \DB::UPDATE("UPDATE notasEntrada SET fechaEntrega = '$fechaEntrega' WHERE idNotaEntrada = $idNotaEntrada");
            $fecha = $fechaEntrega;
            $subject = 'Fecha de entrega registrada.';
            $vista = "correoFechaReg";
            $correo = $notaEntrada[0]->correo;
        }

        if(!empty($fechaPagoProgramada_anticipo)){
            \DB::UPDATE("UPDATE notasEntrada SET fechaPagoProgramada_anticipo = '$fechaPagoProgramada_anticipo', estatusPago_anticipo = 2 WHERE idNotaEntrada = $idNotaEntrada");
            $fecha = $fechaPagoProgramada_anticipo;
            $subject = 'Fecha programada de pago, anticipo.';
            $vista = "correoPagoProgramado";
            $correo = $notaEntrada[0]->correoProveedor;
        }

        if(!empty($fechaPagoProgramada_total)){
            \DB::UPDATE("UPDATE notasEntrada SET fechaPagoProgramada_total = '$fechaPagoProgramada_total', estatusPago = 2 WHERE idNotaEntrada = $idNotaEntrada");
            $fecha = $fechaPagoProgramada_total;
            $subject = 'Fecha programada de pago.';
            $vista = "correoPagoProgramado";
            $correo = $notaEntrada[0]->correoProveedor;
        }

        if(!empty($fechaRealPago_anticipo)){
            \DB::UPDATE("UPDATE notasEntrada SET fechaRealPago_anticipo = '$fechaRealPago_anticipo', estatusPago_anticipo = 3 WHERE idNotaEntrada = $idNotaEntrada");
            $fecha = $fechaPagoProgramada_total;
            $subject = 'Pago realizado, anticipo.';
            $vista = "correoTrans";
            $correo = $notaEntrada[0]->correoProveedor;
        }

        if(!empty($fechaRealPago_total)){
            \DB::UPDATE("UPDATE notasEntrada SET fechaRealPago_total = '$fechaRealPago_total', estatusPago = 3 WHERE idNotaEntrada = $idNotaEntrada");
            $fecha = $fechaRealPago_total;
            $subject = 'Pago realizado.';
            $vista = "correoTrans";
            $correo = $notaEntrada[0]->correoProveedor;
        }

        $datos = array(
            'fecha' => $fecha,
            'notaEntrada' => $notaEntrada[0]->numeroNota,
            'proveedor' => $notaEntrada[0]->proveedor,
        );

        $this->enviarCorreo($vista, $datos, $subject, $notaEntrada[0]->correo);

        return response()->json(200);
        
    }

    public function guardarDetallesEntrega(Request $request){
        $idNotaEntrada = $request->idNotaEntradaDetallesEntrega;
        
        $nombreArchivos = array();

        for ($i=1; $i < 5; $i++) { 
            $archivo = $request->file('archivoPaqueteria'.$i);

            if($archivo != ""){
                // Subiendo el pdf al servidor
                $ldate = date('Ymd_His_');
                $nombreArchivo = $archivo->getClientOriginalName();
                $nombreArchivo = $ldate.$nombreArchivo;
                $nombreArchivo = str_replace(" ","_",$nombreArchivo);
                \Storage::disk('local')->put($nombreArchivo, \File::get($archivo));
            }else{
                $nombreArchivo = "";
            }
            array_push($nombreArchivos,$nombreArchivo);
        }

        \DB::UPDATE("UPDATE notasEntrada SET 
        metodoEntrega = '$request->metodoEntrega',
        detalleEntrega = '$request->detalleEntrega',
        numGuia = '$request->numGuia',
        ciudadOrigen = '$request->ciudadOrigen',
        ciudadDestino = '$request->ciudadDestino',
        paqueteria = '$request->paqueteria',
        numGuia2 = '$request->numGuia2',
        ciudadOrigen2 = '$request->ciudadOrigen2',
        ciudadDestino2 = '$request->ciudadDestino2',
        paqueteria2 = '$request->paqueteria2',
        folioFacturaImportacion = '$request->folioFacturaImportacion',
        folioGastosImportacion = '$request->folioGastosImportacion',
        archivoPaqueteria1 = '$nombreArchivos[0]',
        archivoPaqueteria2 = '$nombreArchivos[1]',
        archivoPaqueteria3 = '$nombreArchivos[2]',
        archivoPaqueteria4 = '$nombreArchivos[3]'
        WHERE idNotaEntrada = $idNotaEntrada");
        
        return response()->json(200);
    }

    public function subirEvidenciaPago(Request $request){
        $idNotaEntrada = $request->idNotaEntradaArchivosEvidencia;
        $esAnticipo = $request->esAnticipoEvidencias;
        
        $consulta = \DB::select("SELECT (SELECT formato FROM ordenCompra AS oc WHERE oc.idOrden = ne.idOrden) AS formato
        FROM notasEntrada AS ne WHERE ne.idNotaEntrada = $idNotaEntrada");

        $archivoEvidencia = $request->file('evidencia');
        if($archivoEvidencia!=""){
            $ldate = date('Ymd_His_');
            $nombreEvidencia = $archivoEvidencia->getClientOriginalName();
            $nombreEvidencia = $ldate.$nombreEvidencia;
            \Storage::disk('local')->put($nombreEvidencia, \File::get($archivoEvidencia));

            if($esAnticipo == 1){
                \DB::UPDATE("UPDATE notasEntrada SET evidenciaPago_anticipo = '$nombreEvidencia' WHERE idNotaEntrada = $idNotaEntrada");
                if ($consulta[0]->formato == "CMG USA") {
                    \DB::UPDATE("UPDATE notasEntrada SET procesoAnticiposFinalizado = 1 WHERE idNotaEntrada = $idNotaEntrada");
                }
            }else if($esAnticipo == 0){
                \DB::UPDATE("UPDATE notasEntrada SET evidenciaPago_total_restante = '$nombreEvidencia' WHERE idNotaEntrada = $idNotaEntrada");
            }

            // \DB::UPDATE("UPDATE notasEntrada SET procesoAnticiposFinalizado = 1 WHERE idNotaEntrada = $idNotaEntrada");
            // \DB::UPDATE("UPDATE notasEntrada SET procesoAnticiposFinalizado = 1 WHERE idNotaEntrada = $idNotaEntrada");
        }
        
        return response()->json(200);
    }
    
    public function guardarMontosComplementos(Request $request){
        $idNotaEntrada = $request->idNotaEntradaMontosComplementos;
        
        $montoComplemento_anticipo = $request->montoComplemento_anticipo;
        $montoComplemento_total = $request->montoComplemento_total;
        $cambioDolar_anticipo = 1;
        $cambioDolar_total = 1;
        
        if(!empty($request->cambioDolar_anticipo)){
            $cambioDolar_anticipo = $request->cambioDolar_anticipo;
        }

        if(!empty($request->cambioDolar_total)){
            $cambioDolar_total = $request->cambioDolar_total;
        }

        if(!empty($montoComplemento_anticipo)){
            \DB::UPDATE("UPDATE notasEntrada SET montoComplemento_anticipo = $montoComplemento_anticipo, monedaComplemento_anticipo = '$request->monedaComplemento_anticipo', cambioDolar_anticipo = '$cambioDolar_anticipo' WHERE idNotaEntrada = $idNotaEntrada");
        }

        if(!empty($montoComplemento_total)){
            \DB::UPDATE("UPDATE notasEntrada SET montoComplemento_total = $montoComplemento_total, monedaComplemento_total = '$request->monedaComplemento_total', cambioDolar_total = '$cambioDolar_total' WHERE idNotaEntrada = $idNotaEntrada");
        }
        
        return response()->json(200);
    }

    public function subirPDFyXML(Request $request){
        $idNotaEntrada = $request->idNotaEntradaPDFyXML;
        $esAnticipo = $request->esAnticipo;
        $esComplemento = $request->esComplemento;

        // Datos del proveedor de la orden de compra
        $datosProveedor= \DB::select("SELECT oc.idProveedor,
        (SELECT rfcProveedor FROM proveedores WHERE proveedores.idProveedor = oc.idProveedor) AS rfcProveedor
        FROM ordenCompra AS oc
        WHERE oc.idOrden = (SELECT ne.idorden FROM notasEntrada AS ne WHERE ne.idNotaEntrada = $idNotaEntrada)");
        $rfcProveedor = $datosProveedor[0]->rfcProveedor;

        // Datos de la factura y xml del proveedor para validar
        $datosNotaEntrada = \DB::select("SELECT
        numeroNota,
        monedaNota,
        montoNotaTotal,
        aplicaAnticipo,
        porcentajeAnticipo,
        cantidadAnticipo,
        montoNotaRestante,
        monedaComplemento_anticipo,
        montoComplemento_anticipo,
        monedaComplemento_total,
        montoComplemento_total,
        pdfFactura_anticipo,
        xmlFactura_anticipo,
        pdfFactura_total_restante,
        xmlFactura_total_restante,
        (select cambioDolar from ordenCompra as oc where oc.idOrden = notasEntrada.idOrden) as cambioDolar_oc,
        (select nombreEmpresa from ordenCompra as oc where oc.idOrden = notasEntrada.idOrden) as empresa,
        (SELECT razonSocialProv FROM proveedores AS p WHERE p.idProveedor = 
        (SELECT idProveedor FROM ordenCompra oc WHERE oc.idOrden = notasEntrada.idOrden)) AS proveedor,
        (SELECT correoProveedor FROM proveedores AS p WHERE p.idProveedor = 
        (SELECT idProveedor FROM ordenCompra oc WHERE oc.idOrden = notasEntrada.idOrden)) AS correoProveedor,
        (SELECT correo FROM usuarios AS u WHERE u.idu = notasEntrada.idu) AS correo
        FROM notasEntrada 
        WHERE idNotaEntrada = $idNotaEntrada");

        // Obteniendo las imagenes del formulario
        $archivoPdf = $request->file('archivoPdf');
        $archivoXml = $request->file('archivoXml');
        
        if($archivoPdf !=""){
            // Subiendo el pdf al servidor
            $ldate = date('Ymd_His_');
            $nombrePDF = $archivoPdf->getClientOriginalName();
            $nombrePDF = $ldate.$nombrePDF;
            $nombrePDF = str_replace(" ","_",$nombrePDF);
            \Storage::disk('local')->put($nombrePDF, \File::get($archivoPdf));
        }else{
            $nombrePDF = "";
        }

        if($archivoXml !=""){
            // // subiendo xml al servidor
            $ldate = date('Ymd_His_');
            $nombreXML = $archivoXml->getClientOriginalName();
            $nombreXML = $ldate.$nombreXML;
            $nombreXML = str_replace(" ","_",$nombreXML);
            \Storage::disk('local')->put($nombreXML, \File::get($archivoXml));
        }else{
            $nombreXML = "";
            $validacion = "ok";
        }

        // CREANDO FOLIO CONFIRMACION
        $length = 10;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        $fechaActual = date('d-m-Y');
        $dia = date('d');
        $mes = date('m');
        $año = date('Y');
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $folioConfirmacion = $año.$mes.$dia.'-'.$randomString;

        //DATOS QUE ENVIA A LA VISTA DEL CORREO
        $datos = array(
            'folioConfirmacion' => $folioConfirmacion,
            'numeroNota' => $datosNotaEntrada[0]->numeroNota,
            'proveedor' => $datosNotaEntrada[0]->proveedor
        );

        // guardando el nombre de los archivos en la BD
        if ($esComplemento == 0) {

            if($esAnticipo == 1){
                
                if($nombreXML != ""){
                    $validacion = $this->validarPdfyXml($nombreXML,$datosNotaEntrada[0]->cantidadAnticipo,$datosNotaEntrada[0]->monedaNota,$rfcProveedor,$nombrePDF,$datosNotaEntrada[0]->empresa,$datosNotaEntrada[0]->cambioDolar_oc);
                    if($validacion == "ok"){
                        \DB::UPDATE("UPDATE notasEntrada SET pdfFactura_anticipo = '$nombrePDF', xmlFactura_anticipo = '$nombreXML' WHERE idNotaEntrada = $idNotaEntrada");
                    }
                }else{
                    \DB::UPDATE("UPDATE notasEntrada SET pdfFactura_anticipo = '$nombrePDF'WHERE idNotaEntrada = $idNotaEntrada");

                    \DB::UPDATE("UPDATE notasEntrada SET folioConfirmacion = '$folioConfirmacion' WHERE idNotaEntrada = $idNotaEntrada");
                }

            }else if($esAnticipo == 0){
                if($nombreXML != ""){
                    $validacion = $this->validarPdfyXml($nombreXML,$datosNotaEntrada[0]->montoNotaRestante,$datosNotaEntrada[0]->monedaNota,$rfcProveedor,$nombrePDF,$datosNotaEntrada[0]->empresa,$datosNotaEntrada[0]->cambioDolar_oc);
                    if($validacion == "ok"){
                        \DB::UPDATE("UPDATE notasEntrada SET pdfFactura_total_restante = '$nombrePDF', xmlFactura_total_restante = '$nombreXML' WHERE idNotaEntrada = $idNotaEntrada");
                    }
                }else{
                    \DB::UPDATE("UPDATE notasEntrada SET pdfFactura_total_restante = '$nombrePDF' WHERE idNotaEntrada = $idNotaEntrada");
                }
            }

            if ($validacion == "ok") {
                // CORREO AL PROVEEDOR
                $subject = 'Confirmación de carga de archivos';
                $this->enviarCorreo("correoArcProv", $datos, $subject,$datosNotaEntrada[0]->correoProveedor);
    
                // CORREO AL CREADOR DE LA NOTA
                $subject = 'Archivos para nota de entrada';
                $this->enviarCorreo("correoConfAdmin", $datos, $subject,$datosNotaEntrada[0]->correo);

                $msj = "Archivos agregados correctamente, folio de confirmación: ".$folioConfirmacion;
            }else{
                $msj = $validacion;
            }

        }else if($esComplemento == 1){

            if($esAnticipo == 1){
                
                if($nombreXML != ""){
                    $validacion = $this->validarPdfyXml($nombreXML,$datosNotaEntrada[0]->montoComplemento_anticipo,$datosNotaEntrada[0]->monedaComplemento_anticipo,$rfcProveedor,$nombrePDF,$datosNotaEntrada[0]->empresa);
                    if($validacion == "ok"){
                        \DB::UPDATE("UPDATE notasEntrada SET pdfComplemento_anticipo = '$nombrePDF', xmlComplemento_anticipo = '$nombreXML' WHERE idNotaEntrada = $idNotaEntrada");
                        // Finzalizamos el proceso de anticipos
                        \DB::UPDATE("UPDATE notasEntrada SET procesoAnticiposFinalizado = 1 WHERE idNotaEntrada = $idNotaEntrada");
                    }
                }else{
                    \DB::UPDATE("UPDATE notasEntrada SET pdfComplemento_anticipo = '$nombrePDF' WHERE idNotaEntrada = $idNotaEntrada");
                    // Finzalizamos el proceso de anticipos
                    \DB::UPDATE("UPDATE notasEntrada SET procesoAnticiposFinalizado = 1 WHERE idNotaEntrada = $idNotaEntrada");
                }

            }else if($esAnticipo == 0){
                if($nombreXML != ""){
                    $validacion = $this->validarPdfyXml($nombreXML,$datosNotaEntrada[0]->montoComplemento_total,$datosNotaEntrada[0]->monedaComplemento_total,$rfcProveedor,$nombrePDF,$datosNotaEntrada[0]->empresa);
                    if($validacion == "ok"){
                        \DB::UPDATE("UPDATE notasEntrada SET pdfComplemento_total_restante = '$nombrePDF', xmlComplemento_total_restante = '$nombreXML' WHERE idNotaEntrada = $idNotaEntrada");
                    }
                }else{
                    \DB::UPDATE("UPDATE notasEntrada SET pdfComplemento_total_restante = '$nombrePDF' WHERE idNotaEntrada = $idNotaEntrada");
                }
            }

            if ($validacion == "ok") {
                // CORREO AL CREADOR DE LA NOTA
                $subject = "Complementos de pago.";
                $this->enviarCorreo("correoComp", $datos, $subject,$datosNotaEntrada[0]->correo);
                
                $msj = "Se ha notificado la carga del complemento de pago";
            }else{
                $msj = $validacion;
            }
            
        }

        return response()->json(["validacion" => $validacion, "msj" => $msj],200);
    }

    public function validarPdfyXml($archivoXML,$montoNotaEntrada,$monedaNotaEntrada,$rfcProveedor,$archivoPDF,$empresa,$cambioDolar_oc = 1){
        // cambio dola de la oc para validar contra xml de USD
        if (is_null($cambioDolar_oc) || $cambioDolar_oc < 1) {
            $cambioDolar_oc = 1;
        }
        
        // Obteniendo el archivo XML de su ubicacion
        $xml_path = asset('archivos/'.$archivoXML);
        
        $xmlString = file_get_contents($xml_path);
        $xmlObject = simplexml_load_string($xmlString);
    
        $json = json_encode($xmlObject);
        $phpArray = json_decode($json, true); 
    
        $factura = simplexml_load_string($xmlString);
        $namespaces = $factura -> getNamespaces(true);
        $factura -> registerXPathNamespace('c', $namespaces['cfdi']);

        // Obteniendo RFC emisor y receptor del XML
        foreach ($factura->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){ 
          $rfcEmisorEnXml = ($Emisor['Rfc']);      
        }
        foreach ($factura->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){ 
          $rfcReceptorEnXml = ($Receptor['Rfc']);      
        }    
        // Obteniendo moneda y total del XML
        foreach ($phpArray as $key => $value) {
          $totalEnXml = ($value["Total"]);
          $monedaEnXml = ($value["Moneda"]);
        }
        
        switch ($empresa) {
            case 'CYM':
                $rfcReceptorNotaEntrada = "TOHA800103P69";
            break;
            case 'GCM':
                $rfcReceptorNotaEntrada = "GCM1809125LA";
            break;
            case 'SURJA':
                $rfcReceptorNotaEntrada = "SIN131106V82";
            break;
            case 'POJ':
                $rfcReceptorNotaEntrada = "OEJP740203NI2";
            break;
            case 'YAGA':
                $rfcReceptorNotaEntrada = "GOAY921221EH3";
            break;
            
            default:
                $rfcReceptorNotaEntrada = "";
            break;
        }

        if($monedaNotaEntrada != $monedaEnXml){
            if($monedaEnXml == "USD"){
                $totalEnXml = round($totalEnXml * $cambioDolar_oc, 2, PHP_ROUND_HALF_UP);
            }
            if($monedaNotaEntrada == "USD"){
                $montoNotaEntrada = round($montoNotaEntrada * $cambioDolar_oc, 2, PHP_ROUND_HALF_UP);
            }

            $monedaNotaEntrada = $monedaEnXml;
        }
        
        // Igualando los montos sin decimales para validar sin decimales
        $montoNotaEntrada = intval($montoNotaEntrada);
        $totalEnXml = intval($totalEnXml);

        // Validando que los datos del XML coincidan con la Nota de entrada
        if($montoNotaEntrada == $totalEnXml && $monedaNotaEntrada == $monedaEnXml && $rfcProveedor== $rfcEmisorEnXml && $rfcReceptorNotaEntrada == $rfcReceptorEnXml){

            $mensaje = "ok";
            return $mensaje;
        }else{

            \File::delete('public/archivos/'.$archivoXML);
            \File::delete('public/archivos/'.$archivoPDF);

            $mensaje = array();

            if($montoNotaEntrada != $totalEnXml){
                array_push($mensaje,'El total de los archivos agregados no coincide con el total de la Nota de entrada.');
            }
            if($monedaNotaEntrada != $monedaEnXml){
                array_push($mensaje,'La moneda de los archivos agregados no coincide con la moneda de la Nota de entrada.');
            }
            if($rfcProveedor != $rfcEmisorEnXml){
                array_push($mensaje,'El RFC emisor de los archivos agregados no coincide con el RFC de la Nota de entrada.');
            }
            if($rfcReceptorNotaEntrada != $rfcReceptorEnXml){
                array_push($mensaje,'El RFC receptor de los archivos agregados no coincide con el RFC de la Nota de entrada.');
            }

            // return response()->json(["status" => "error", "msj" => $mensaje],200);
            return $mensaje;
        }

    }

    public function reportePagosProveedores(){
        $stipo = Session::get('sesiontipo');
        
        return view('reportePagosProveedor')
        ->with('tipoUsuario',$stipo);
    }

    public function consultaReportePagosProveedores(){
        $consultaPagos= \DB::select("SELECT
        idNotaEntrada,idOrden,
        (SELECT oc.codigoOrden FROM ordenCompra AS oc WHERE oc.idOrden = ne.idOrden) AS ordenCompra,
        (SELECT DATE_FORMAT(oc.fechaOrden,'%d/%m/%Y') FROM ordenCompra AS oc WHERE oc.idOrden = ne.idOrden) AS fechaOrden,
        (SELECT p.razonSocialProv FROM proveedores AS p WHERE p.idProveedor = (SELECT oc.idProveedor FROM ordenCompra AS oc WHERE oc.idOrden = ne.idOrden)) AS proveedor,
        (SELECT oc.nombreEmpresa FROM ordenCompra AS oc WHERE oc.idOrden = ne.idOrden) AS empresa,
        montoNotaTotal,
        (SELECT (IFNULL(oc.cambioDolar,1) * ne.montoNotaTotal) FROM ordenCompra AS oc WHERE oc.idOrden = ne.idOrden) AS totalMxnOrden,

        (SELECT formato FROM ordenCompra AS oc WHERE oc.idOrden = ne.idOrden) AS formato,
        -- Montos y moneda anticipo
        (SELECT IFNULL(cambioDolar,1) FROM ordenCompra AS oc WHERE oc.idOrden = ne.idOrden) AS cambioDolar,
        (SELECT (cantidadAnticipo * IFNULL(cambioDolar,1)) FROM ordenCompra AS oc WHERE oc.idOrden = ne.idOrden) AS anticipoMXN,
        (SELECT (montoNotaRestante * IFNULL(cambioDolar,1)) FROM ordenCompra AS oc WHERE oc.idOrden = ne.idOrden) AS totalRestanteMXN,
        -- Montos y moneda anticipo de complementos
        (SELECT IFNULL(cambioDolar_anticipo,1) FROM ordenCompra AS oc WHERE oc.idOrden = ne.idOrden) AS cambioDolar_anticipoComplemento,
        (SELECT (montoComplemento_anticipo * IFNULL(cambioDolar_anticipo,1)) FROM ordenCompra AS oc WHERE oc.idOrden = ne.idOrden) AS anticipoComplementoMXN,
        -- Montos y moneda atotal o restante de complementos
        (SELECT IFNULL(cambioDolar_total,1) FROM ordenCompra AS oc WHERE oc.idOrden = ne.idOrden) AS cambioDolar_totalComplemento,
        (SELECT (montoComplemento_total * IFNULL(cambioDolar_total,1)) FROM ordenCompra AS oc WHERE oc.idOrden = ne.idOrden) AS totalRestanteComplementoMXN,

        numeroNota,
        ('-') AS estatusEntrega,
        ('-') AS estatusPago,
        pdfComplemento_anticipo,
        pdfComplemento_total_restante,
        pdfFactura_anticipo,
        pdfFactura_total_restante,
        evidenciaPago_anticipo,
        evidenciaPago_total_restante,
        monedaNota,
        aplicaAnticipo,
        porcentajeAnticipo,
        cantidadAnticipo,
        montoNotaRestante,
        monedaComplemento_anticipo,
        montoComplemento_anticipo,
        monedaComplemento_total,
        montoComplemento_total,
        estatusEntrega,
        estatusPago,
        estatusPago_anticipo,
        metodoEntrega,
        detalleEntrega,
        IFNULL(numGuia,'') as numGuia,
        ciudadOrigen,
        ciudadDestino,
         IFNULL(paqueteria,'') as paqueteria,
        IFNULL(numGuia2,'') as numGuia2,
        ciudadOrigen2,
        ciudadDestino2,
         IFNULL(paqueteria2,'') as paqueteria2,
        folioFacturaImportacion,
        folioGastosImportacion,
        archivoPaqueteria1,
        archivoPaqueteria2,
        archivoPaqueteria3,
        archivoPaqueteria4
        FROM notasEntrada AS ne");
        
        return response()->json($consultaPagos, 200);
    }

    public function reiniciarCamposNotaEntrada(Request $request){
        $campo = $request->campo;
        $idNotaEntrada = $request->idNotaEntrada;

        switch ($campo) {
            case "fechaEntrega":
                \DB::UPDATE("UPDATE notasEntrada SET fechaEntrega = NULL WHERE idNotaEntrada = $idNotaEntrada");
            break;
            case "detallesEntrega":
                \DB::UPDATE("UPDATE notasEntrada SET
                    metodoEntrega = NULL,
                    detalleEntrega = NULL,
                    numGuia = NULL,
                    ciudadOrigen = NULL,
                    ciudadDestino = NULL,
                    paqueteria = NULL,
                    numGuia2 = NULL,
                    ciudadOrigen2 = NULL,
                    ciudadDestino2 = NULL,
                    paqueteria2 = NULL,
                    folioFacturaImportacion = NULL,
                    folioGastosImportacion = NULL,
                    archivoPaqueteria1 = NULL,
                    archivoPaqueteria2 = NULL,
                    archivoPaqueteria3 = NULL,
                    archivoPaqueteria4 = NULL
                WHERE idNotaEntrada = $idNotaEntrada");
            break;
            case "facturasAnticipo":
                \DB::UPDATE("UPDATE notasEntrada SET pdfFactura_anticipo = NULL, xmlFactura_anticipo = NULL WHERE idNotaEntrada = $idNotaEntrada");
            break;
            case "facturasTotalRestante":
                \DB::UPDATE("UPDATE notasEntrada SET pdfFactura_total_restante = NULL, xmlFactura_total_restante = NULL WHERE idNotaEntrada = $idNotaEntrada");
            break;
            case "fechaPagoProgramada_anticipo":
                \DB::UPDATE("UPDATE notasEntrada SET fechaPagoProgramada_anticipo = NULL, estatusPago_anticipo = 1 WHERE idNotaEntrada = $idNotaEntrada");
            break;
            case "fechaPagoProgramada_total":
                \DB::UPDATE("UPDATE notasEntrada SET fechaPagoProgramada_total = NULL, estatusPago = 1 WHERE idNotaEntrada = $idNotaEntrada");
            break;
            case "fechaRealPago_anticipo":
                \DB::UPDATE("UPDATE notasEntrada SET fechaRealPago_anticipo = NULL, estatusPago_anticipo = 2 WHERE idNotaEntrada = $idNotaEntrada");
            break;
            case "fechaRealPago_total":
                \DB::UPDATE("UPDATE notasEntrada SET fechaRealPago_total = NULL, estatusPago = 2 WHERE idNotaEntrada = $idNotaEntrada");
            break;
            case "evidenciaPago_anticipo":
                \DB::UPDATE("UPDATE notasEntrada SET evidenciaPago_anticipo = NULL WHERE idNotaEntrada = $idNotaEntrada");
            break;
            case "evidenciaPago_total_restante":
                \DB::UPDATE("UPDATE notasEntrada SET evidenciaPago_total_restante = NULL WHERE idNotaEntrada = $idNotaEntrada");
            break;
            case "montoComplemento_anticipo":
                \DB::UPDATE("UPDATE notasEntrada SET montoComplemento_anticipo = NULL, monedaComplemento_anticipo = NULL, cambioDolar_anticipo = NULL WHERE idNotaEntrada = $idNotaEntrada");
            break;
            case "montoComplemento_total":
                \DB::UPDATE("UPDATE notasEntrada SET montoComplemento_total = NULL, monedaComplemento_total = NULL, cambioDolar_total = NULL WHERE idNotaEntrada = $idNotaEntrada");
            break;
            case "archivosComplemento_anticipo":
                \DB::UPDATE("UPDATE notasEntrada SET pdfComplemento_anticipo = NULL, xmlComplemento_anticipo = NULL WHERE idNotaEntrada = $idNotaEntrada");
            break;
            case "archivosComplemento_total_restante":
                \DB::UPDATE("UPDATE notasEntrada SET pdfComplemento_total_restante = NULL, xmlComplemento_total_restante = NULL WHERE idNotaEntrada = $idNotaEntrada");
            break;
        }
    }

    public function consultaDetallesNotaEntrada($idNotaEntrada){
        $consulta=\DB::select("SELECT
            idNotaEntrada,
            idOrden,
            numeroNota,
            detallesEntrega,
            monedaNota,
            montoNotaTotal,
            aplicaAnticipo,
            porcentajeAnticipo,
            cantidadAnticipo,
            montoNotaRestante,
            monedaComplemento_anticipo,
            montoComplemento_anticipo,
            monedaComplemento_total,
            montoComplemento_total,
            pdfFactura_anticipo,
            xmlFactura_anticipo,
            pdfFactura_total_restante,
            xmlFactura_total_restante,
            evidenciaPago_anticipo,
            evidenciaPago_total_restante,
            pdfComplemento_anticipo,
            xmlComplemento_anticipo,
            pdfComplemento_total_restante,
            xmlComplemento_total_restante,
            DATE_FORMAT(fechaEntrega,'%d/%m/%Y') AS fechaEntrega,
            DATE_FORMAT(fechaPagoProgramada_total,'%d/%m/%Y') AS fechaPagoProgramada_total,
            DATE_FORMAT(fechaPagoProgramada_anticipo,'%d/%m/%Y') AS fechaPagoProgramada_anticipo,
            DATE_FORMAT(fechaRealPago_total,'%d/%m/%Y') AS fechaRealPago_total,
            DATE_FORMAT(fechaRealPago_anticipo,'%d/%m/%Y') AS fechaRealPago_anticipo,
            procesoAnticiposFinalizado,
            metodoEntrega,
            detalleEntrega,
            numGuia,
            ciudadOrigen,
            ciudadDestino,
            paqueteria,
            numGuia2,
            ciudadOrigen2,
            ciudadDestino2,
            paqueteria2,
            folioFacturaImportacion,
            folioGastosImportacion,
            archivoPaqueteria1,
            archivoPaqueteria2,
            archivoPaqueteria3,
            archivoPaqueteria4,
            idu,
            activo,
            (SELECT formato FROM ordenCompra WHERE ordenCompra.idOrden = notasEntrada.idOrden) AS formato,
            -- Montos y moneda anticipo
            (SELECT IFNULL(cambioDolar,1) FROM ordenCompra WHERE ordenCompra.idOrden = notasEntrada.idOrden) AS cambioDolar,
            (SELECT (cantidadAnticipo * IFNULL(cambioDolar,1)) FROM ordenCompra WHERE ordenCompra.idOrden = notasEntrada.idOrden) AS anticipoMXN,
            (SELECT (montoNotaRestante * IFNULL(cambioDolar,1)) FROM ordenCompra WHERE ordenCompra.idOrden = notasEntrada.idOrden) AS totalRestanteMXN,
            -- Montos y moneda anticipo de complementos
            (SELECT IFNULL(cambioDolar_anticipo,1) FROM ordenCompra WHERE ordenCompra.idOrden = notasEntrada.idOrden) AS cambioDolar_anticipoComplemento,
            (SELECT (montoComplemento_anticipo * IFNULL(cambioDolar_anticipo,1)) FROM ordenCompra WHERE ordenCompra.idOrden = notasEntrada.idOrden) AS anticipoComplementoMXN,
            -- Montos y moneda atotal o restante de complementos
            (SELECT IFNULL(cambioDolar_total,1) FROM ordenCompra WHERE ordenCompra.idOrden = notasEntrada.idOrden) AS cambioDolar_totalComplemento,
            (SELECT (montoComplemento_total * IFNULL(cambioDolar_total,1)) FROM ordenCompra WHERE ordenCompra.idOrden = notasEntrada.idOrden) AS totalRestanteComplementoMXN
            FROM notasEntrada
            WHERE idNotaEntrada = $idNotaEntrada AND activo = 'Si'"
        );
        
        return response()->json($consulta, 200);
    }

    public function enviarCorreo($vista, $datos, $subject, $correoDestino){
        // Mail::send($vista, $datos, function($msj) use($subject,$correoDestino){ 
        //     $msj->from("informacion@germancontrolmotion.com","German Control Motion");
        //     $msj->subject($subject); 
        //     $msj->to($correoDestino); 
        // });
        return;
    }
}