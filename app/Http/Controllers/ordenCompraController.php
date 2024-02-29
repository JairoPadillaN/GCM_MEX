<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdenesCompraExport;
use App\productosOrdenCompra;
use App\cuentas;
use App\productosPorEntradaAlmacen;
use App\productosServiciosAsignacion;
use App\gruposAsignacionesEntradas;
use App\entradasAlmacen;
use App\entradasAlmacenOrdenCompra;
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
use Session;
use DB;
use Mail;

class ordenCompraController extends Controller{

    public function altaOrdenCompra(){
        $consultaProveedor=\DB::select("SELECT idProveedor, razonSocialProv FROM proveedores ORDER BY razonSocialProv ASC");
        $cliente = clientes::orderby('razonSocial','asc')
        ->get();    
        
        return view('altaOrdenCompra')
        ->with('consultaProveedor',$consultaProveedor)
        ->with('cliente',$cliente);
    }

    public function obtenerCuentas(Request $request){
        $nombreEmpresa = $request->get('nombreEmpresa');
        $consultaCuentas=\DB::select("SELECT idCuenta, nombreCuenta, numeroCu FROM cuentas
        WHERE empresaCuenta=? AND activo='Si'
        ORDER BY numeroCu ASC",[$nombreEmpresa]);

        return view('comboCuentasEmpresa')
        ->with('consultaCuentas',$consultaCuentas);
    }

    public function obtenerSku(Request $request){
        $idFactura = $request->get('idFactura');
        
        $consultaOrden = \DB::select("SELECT pv.idPartesVenta,pv.codigo,pv.nombreRefaccion 
        FROM partesVenta AS pv WHERE pv.idPartesVenta IN (
            SELECT idPartesVenta FROM refaccionesEnCotizacion WHERE idCotizacion IN (
                SELECT f.idCotizacion FROM facturas AS f WHERE f.idCotizacion != '' AND f.activo='si' AND f.idFactura= $idFactura
            ) AND apareceEnCoti = 'Si'
        ) 
        OR
        pv.idPartesVenta IN ( 
            SELECT dc.idPartesVenta FROM detallereparaciones AS dc WHERE dc.idCotizacion IN (
                SELECT f.idCotizacion FROM facturas AS f WHERE f.idCotizacion != '' AND f.activo='si' AND f.idFactura= $idFactura
            )
        )");

        return view('comboSkuDeFacturas')->with('consultaOrden',$consultaOrden);
    }

    public function obtenerPartesVenta(Request $request){
        $idMarcaRefa = $request->get('idMarcaRefa');
        $consultaPartesVenta= partesVenta::Where('activo','=','Si')->where('idMarcaRefa','=',$idMarcaRefa)->orderBy('codigo','ASC')->get();
        
        return view('comboSkuPartesVenta')->with('consultaPartesVenta',$consultaPartesVenta);
    }

    public function obtenerEquiposEnFactura(Request $request){
        $idFactura = $request->get('idFactura');
        $consultaEquipos=\DB::select("SELECT idEquipos,GCMid,complementoGCMid FROM recepcionEquipos WHERE idEquipos IN (
            SELECT idEquipos FROM detallereparaciones WHERE idCotizacion IN (       
                SELECT idCotizacion FROM facturas WHERE idFactura=$idFactura
            ) AND idEquipos >= 1 GROUP BY idEquipos
        )");

        return view('comboEquiposEnFactura')->with('consultaEquipos',$consultaEquipos);
    }

    public function obtenerPartesEquiposEnFactura(Request $request){
        $idEquipo = $request->get('idEquipo');
        $consultaPartesEquipo=\DB::select("SELECT idParte,GCMidParte,nombreParte FROM partesreparacion WHERE idEquipos = $idEquipo");

        return view('comboPartesEquiposEnFactura')->with('consultaPartesEquipo',$consultaPartesEquipo);
    }

    public function reporteOrdenesCompra(){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else{
            return view( 'reporteOrdenesCompra2');
        }
    }
    public function reporteOrdenesCompra2(){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else{
            return view( 'reporteOrdenesCompra');
        }
    }

    public function anexgridOrdenesCompra(){
        $anexGrid = new anexGrid();
        $order;
        $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

        $fechaInicioOrden = '0000-00-00';
        $fechaFinOrden = '9999-12-31';
        $codigoOrden = '';
        $razonSocialProv = '';
        $nombreEmpresa = '';
        $estatus = $anexGrid->parametros[0];        
        
        // if($anexGrid->parametros[0] == "No"){
        //     $comparacion ='=';
        // }else{
        //     $comparacion ='!=';
        // }

        foreach($anexGrid->filtros as $f){
            if($f['columna'] == 'codigoOrden' && $f['valor'] != ''){
                $codigoOrden = $f['valor'];
            }
            if($f['columna'] == 'razonSocialProv' && $f['valor'] != ''){
                $razonSocialProv = $f['valor'];
            }
            if($f['columna'] == 'nombreEmpresa' && $f['valor'] != ''){
                $nombreEmpresa = $f['valor'];
            }
            // if($f['columna'] == 'estatus' && $f['valor'] != ''){
            //     $estatus = $f['valor'];
            // }
            if($f['columna'] == 'fechaInicioOrden' && $f['valor'] != ''){
                $fechaInicioOrden = $f['valor'];
            }
            if($f['columna'] == 'fechaFinOrden' && $f['valor'] != ''){
                $fechaFinOrden = $f['valor'];
            }
        }

        $registros= DB::table('ordenCompra as oc')
            ->select(
                'oc.idOrden',
                DB::raw('IF(oc.oc_mov_internos = 1,CONCAT("Entradas directas ",oc.nombreEmpresa),oc.codigoOrden) AS codigoOrden'),
                DB::raw('DATE_FORMAT(oc.fechaOrden,"%d/%m/%Y") AS fecha_formato'),
                DB::raw('(oc.fechaOrden) AS fechaInicioOrden'),
                DB::raw('(oc.fechaOrden) AS fechaFinOrden'),
                'p.razonSocialProv',
                'oc.nombreEmpresa',
                DB::raw('CONCAT(oc.moneda, " $", FORMAT(oc.importeOrden,2)) AS importeOrden'),
                DB::raw('CONCAT(" $", FORMAT(oc.ivaCompra,2)) AS iva'),
                DB::raw('CONCAT(" $", FORMAT(oc.isrCompra,2)) AS isr'),
                DB::raw('CONCAT(" $", FORMAT(oc.retencionCompra,2)) AS retencion'),
                DB::raw('CONCAT(oc.moneda, " $", FORMAT(total,2)) AS totalOrden'),
                DB::raw('CONCAT("MXN $", FORMAT(oc.totalMXN,2)) AS totalMXN'),
                'oc.estatus',
                DB::raw('CONCAT(oc.monedaComplementoPago, " $", FORMAT(oc.montoComplementoPago,2)) AS totalFinalPagado'),
                'oc.total AS totalSinFormat'
            )
            ->leftJoin('proveedores as p', 'oc.idProveedor', '=', 'p.idProveedor')
            ->where(DB::raw('IF(oc.oc_mov_internos = 1,CONCAT("Entradas directas ",oc.nombreEmpresa),oc.codigoOrden)'), 'like', '%'.$codigoOrden.'%')
            ->where('p.razonSocialProv', 'like', '%'.$razonSocialProv.'%')
            ->where('oc.nombreEmpresa', 'like', '%'.$nombreEmpresa.'%')
            // ->where('oc.estatus', 'like', '%'.$estatus.'%')
            ->where('oc.fechaOrden', '>=', $fechaInicioOrden)
            ->where('oc.fechaOrden', '<=', $fechaFinOrden)
            // ->where('oc.estatus','=','Cancelado')
            ->where('oc.activo','=',$estatus)
            ->where('oc.oc_mov_internos','=',0)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
        ->get();

        $total= DB::table('ordenCompra as oc')
            ->leftJoin('proveedores as p', 'oc.idProveedor', '=', 'p.idProveedor')
            ->where(DB::raw('IF(oc.oc_mov_internos = 1,CONCAT("Entradas directas ",oc.nombreEmpresa),oc.codigoOrden)'), 'like', '%'.$codigoOrden.'%')
            ->where('p.razonSocialProv', 'like', '%'.$razonSocialProv.'%')
            ->where('oc.nombreEmpresa', 'like', '%'.$nombreEmpresa.'%')
            // ->where('oc.estatus', 'like', '%'.$estatus.'%')
            ->where('oc.fechaOrden', '>=', $fechaInicioOrden)
            ->where('oc.fechaOrden', '<=', $fechaFinOrden)
            // ->where('oc.estatus','=','Cancelado')
            ->where('oc.activo','=',$estatus)
            ->where('oc.oc_mov_internos','=',0)
        ->count();
        
        $sumaTotales = $this->consultaSumaTotales($codigoOrden,$razonSocialProv,$nombreEmpresa,$estatus,$fechaInicioOrden,$fechaFinOrden,$estatus);
        
        $data = array(
            'total' => $total,
            'data' => $registros,
            'sumaTotales' => $sumaTotales
        );

        return response()->json($data, 200);
    }

    public function guardarOrdenCompra(Request $request){
        $diasPlazo=$request->diasPlazo;
        $tipoPago=$request->tipoPago;
        $idFactura=$request->idFactura;
        $idProveedor=$request->idProveedor;

        if($tipoPago=='contado'){
            $diasPlazo=0;
        }else{
            $diasPlazo=$request->diasPlazo;
        }

        $file = $request->file('cotizacionProveedor');
        if($file!=""){
            $ldate = date('Ymd_His_');
            $cotizacion = $file->getClientOriginalName();
            $cotizacionProveedor = $ldate.$cotizacion;
            \Storage::disk('local')->put($cotizacionProveedor, \File::get($file));
        }

        $orden= new ordenCompra;
        $orden->idProveedor =$request->idProveedor;
        $orden->idCuentaProv =$request->idCuentaProv;
        $orden->idSucursal =$request->idSucursal;
        $orden->tipoServicio =$request->tipoServicio;
        $orden->codigoOrden =$request->codigoOrden;
        $orden->nombreEmpresa =$request->nombreEmpresa;
        $orden->fechaOrden =$request->fechaOrden;
        $orden->fechaProgPago =$request->fechaProgPago;
        $orden->fechaDeposito =$request->fechaDeposito;
        $orden->idPartesVenta =$request->idPartesVenta;
        $orden->idFactura =$request->idFactura;
        $orden->idCuenta =$request->idCuenta;
        $orden->moneda =$request->moneda;
        $orden->cambioDolar =$request->cambioDolar;
        $orden->estatus =$request->estatus;
        $orden->notasOrden =$request->notasOrden;
        $orden->formaPago =$request->formaPago;
        $orden->cfdi =$request->cfdi;
        $orden->formato =$request->formato;
        $orden->paymentTerms =$request->paymentTerms;
        $orden->idu =$request->idu;
        $orden->estatusProveedor =$request->estatusProveedor;
        $orden->fechaEntregaProveedor =$request->fechaEntregaProveedor;
        $orden->domicilioEntrega =$request->domicilioEntrega;
        $orden->vigenciaCotizacion =$request->vigenciaCotizacion;
        $orden->cotizacionProveedor =$cotizacionProveedor;
        $orden->importeOrden =0;
        $orden->ivaCompra =0;
        $orden->isrCompra =0;
        $orden->total =0;
        $orden->importeMXN =0;
        $orden->totalMXN =0;
        $orden->isrMXN =0;
        $orden->ivaMXN =0;
        $orden->tipoPago =$request->tipoPago;
        $orden->diasPlazo =$diasPlazo;
        $orden->incoterm =$request->incoterm;
        $orden->detalleIncoterm =$request->detalleIncoterm;
        $orden->vendorQuote =$request->vendorQuote;
        $orden->dueDate =$request->dueDate;
        $orden->detalleMetodoEnvio =$request->detalleMetodoEnvio;
        $orden->billTo =$request->billTo;
        $orden->telAdicional =$request->telAdicional;
        $orden->correoAdicional =$request->correoAdicional;
        $orden->mostrarRfc =$request->mostrarRfc;
        $orden->mostrarTaxId =$request->mostrarTaxId;
        $orden-> save();
    
        $proceso='Registro exitoso de orden de compra';
        $mensaje="Orden registrada correctamente";
        return view ('mensajeOrdenCompra')
        ->with('proceso',$proceso)
        ->with('idFactura',$idFactura)
        ->with('mensaje',$mensaje);
    }

    public function modificarOrden($idOrden){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else{
        
        $consulta = \DB::select("SELECT * from ordenCompra WHERE idOrden=$idOrden");
        $idCuenta=$consulta[0]->idCuenta;        
        $nombreEmpresa=$consulta[0]->nombreEmpresa;
        $idu=$consulta[0]->idu;
        
        $registradoPor=\DB::select("SELECT * FROM usuarios WHERE idu=$idu");
        // consultas para obtener las facturas, equipos y partes para venta y mandarlos a los combos del tab productos
        // $consultaFactura = facturas::Where('activo','=','si')->orderBy('idServicios','desc')->get();
        $consultaFactura = \DB::select("SELECT f.idFactura,f.idServicios,f.numeroFactura,c.razonSocial AS cliente,s.sucursal 
        FROM facturas AS f
        INNER JOIN sucursales AS s ON s.idSucursal= f.idSucursal
        INNER JOIN clientes AS c ON c.idc= f.idc
        WHERE f.idCotizacion != '' AND f.activo='si' ORDER BY idServicios DESC");
        $consultaFacturaConEquipos = $consultaFactura;
        $consultaMarcaPartesVenta= marcaRefaccion::Where('activo','=','Si')->orderBy('marcaRefaccion','asc')->get();

        // $sucursalSel= sucursales::where('idSucursal',"=",$consulta[0]->idSucursal)->get();
        // $nomsuc =$sucursalSel[0]->sucursal;
        
        $proveedorSel= proveedores::where('idProveedor',"=",$consulta[0]->idProveedor)->get();
        $nomProveedor =$proveedorSel[0]->razonSocialProv;
        // $consultaProveedor= proveedores::where('idProveedor',"!=",$consulta[0]->idProveedor)->get();
        $consultaProveedor=\DB::select("SELECT idProveedor, razonSocialProv FROM proveedores where idProveedor != ? ORDER BY razonSocialProv ASC ",[$consulta[0]->idProveedor]);

        $cuentaProvSel= cuentasproveedores::where('idCuentaProv',"=",$consulta[0]->idCuentaProv)->get();
        $numCuentaProv =$cuentaProvSel[0]->numeroCuentaProv;
        $consultaCuentaProv= cuentasproveedores::where('idCuentaProv',"!=",$consulta[0]->idCuentaProv)
        ->where('idProveedor',"=",$consulta[0]->idProveedor)->get();

        $cuentaSel= cuentas::where('idCuenta',"=",$consulta[0]->idCuenta)->get();        
        $nomCuenta =$cuentaSel[0]->nombreCuenta;
        $numeroCuenta =$cuentaSel[0]->numeroCu;
        $consultaCuenta=\DB::select("SELECT idCuenta, nombreCuenta, numeroCu FROM cuentas 
        WHERE empresaCuenta='$nombreEmpresa' AND activo='Si'
        ORDER BY numeroCu ASC");

        /////CONSULTAS PARA EL REPORTE////
        $ordenCompra=\DB::select("SELECT idProducto, po.idOrden, po.idPartesVenta,po.tipoServicio,
        po.nombreRefaccion, po.vendorPartNumber, notasProducto, codigo, po.cantidadOrden, oc.cambioDolar,po.idFactura,f.idServicios,
        FORMAT(subtotal,2) as subtotal, ivaOrden, FORMAT(ivaDinero,2) ivaDinero, isrOrden,
        FORMAT(isrDinero,2) as isrDinero, FORMAT(precioProducto,2) as precioProducto,
        FORMAT(retencionDinero,2) as retencionDinero,retencion,
        FORMAT(po.precioOrden,2) as precioOrden,po.moneda, idRefaccionCotizacion, idRepcotizacion
        FROM productosOrdenCompra as po
        inner join ordenCompra as oc on oc.idOrden=po.idOrden
        left join facturas as f on f.idFactura=po.idFactura
        WHERE po.idOrden=$idOrden");
        $cuantosProductos=count($ordenCompra);
        
        $sumaTotal=\DB::select("SELECT oc.moneda, oc.idFactura, FORMAT(SUM(precioProducto),2) AS total,
        FORMAT(SUM(isrDinero),2) as isrDineroTotal,
        FORMAT(SUM(ivaDinero),2) as ivaDineroTotal,
        FORMAT(SUM(retencionDinero),2) as retencionDineroTotal,
        FORMAT(SUM(subtotal),2) as importe
        FROM productosOrdenCompra AS pc
        INNER JOIN ordenCompra as oc ON pc.idOrden=oc.idOrden
        WHERE pc.idOrden= $idOrden");
        
        /////TERMINAN CONSULTAS PARA EL REPORTE////

        // archivos

        $pdfProv = \DB::select("SELECT oc.pdfFacturaProveedor FROM ordenCompra AS oc Where idOrden= $idOrden");
        $xmlProv = \DB::select("SELECT oc.xmlFacturaProveedor FROM ordenCompra AS oc Where idOrden= $idOrden");
        $transf = \DB::select("SELECT oc.evidenciaTransferencia FROM ordenCompra AS oc Where idOrden= $idOrden");
        $xmlComp = \DB::select("SELECT oc.xmlComplementoProveedor FROM ordenCompra AS oc Where idOrden= $idOrden");
        $pdfComp = \DB::select("SELECT oc.pdfComplementoProveedor FROM ordenCompra AS oc Where idOrden= $idOrden");
        $archivoPaq1 = \DB::select("SELECT oc.archivoPaqueteria1 FROM ordenCompra AS oc Where idOrden= $idOrden");
        $archivoPaq2 = \DB::select("SELECT oc.archivoPaqueteria2 FROM ordenCompra AS oc Where idOrden= $idOrden");
        $archivoPaq3 = \DB::select("SELECT oc.archivoPaqueteria3 FROM ordenCompra AS oc Where idOrden= $idOrden");
        $archivoPaq4 = \DB::select("SELECT oc.archivoPaqueteria4 FROM ordenCompra AS oc Where idOrden= $idOrden");
        
        return view('editarOrdenCompra')
        ->with('registradoPor',$registradoPor[0])
        ->with('sumaTotal',$sumaTotal[0])
        ->with('ordenCompra',$ordenCompra)
        ->with('cuantosProductos',$cuantosProductos)

        ->with('cuentaProvSel',$cuentaProvSel[0])
        ->with('numCuentaProv',$numCuentaProv)
        ->with('consultaCuentaProv',$consultaCuentaProv)

        ->with('proveedorSel',$proveedorSel[0])
        ->with('nomProveedor',$nomProveedor)
        ->with('consultaProveedor', $consultaProveedor)
        ->with('consultaCuenta', $consultaCuenta)
        ->with('nomCuenta', $nomCuenta)
        ->with('numeroCuenta', $numeroCuenta)
        ->with('cuentaSel', $cuentaSel[0])
        ->with('consultaFactura', $consultaFactura)
        ->with('consultaFacturaConEquipos', $consultaFacturaConEquipos)
        ->with('consultaMarcaPartesVenta', $consultaMarcaPartesVenta)
        // ->with('nomsuc', $nomsuc)
        ->with('consulta', $consulta[0])
        
        // archivos
        ->with('pdfProv',$pdfProv)
        ->with('xmlProv',$xmlProv)
        ->with('transf',$transf)
        ->with('pdfComp',$pdfComp)
        ->with('xmlComp',$xmlComp)
        ->with('archivoPaq1',$archivoPaq1)
        ->with('archivoPaq2',$archivoPaq2)
        ->with('archivoPaq3',$archivoPaq3)
        ->with('archivoPaq4',$archivoPaq4);
        }
    }

    public function editarOrdenCompra(Request $request){
        $fechaDeposito = $request->fechaDeposito;
        $idOrden=$request->idOrden;
        $idProveedor=$request->idProveedor;
        $cambioDolar =$request->cambioDolarOrden;

        // if($request->estatus == 'Pagado'){

        //     $consultaProveedores = \DB::select("SELECT * from proveedores WHERE idProveedor = $request->idProveedor");  
        //     $correoProveedor = $consultaProveedores[0]->correoProveedor;
        //     $razonSocial = $consultaProveedores[0]->razonSocialProv; 
        //     $consultaOrden = \DB::select("SELECT * FROM ordenCompra WHERE idOrden = $request->idOrden"); 
            
        //     // // DATOS PARA ENVIO DEL CORREO
        //     $subject = 'Transferencia realizada.';
        //     $datos = array(
        //         'fechaDeposito'=>$fechaDeposito,
        //         // 'consultaProv'=>$consultaProveedores[0],
        //         'consultaOrd'=>$consultaOrden[0]); //DATOS QUE SE ENVIAN A LA VISTA DEL CORREO
        //     Mail::send('correoTrans', $datos, function($msj) use($subject,$correoProveedor){ //VISTA, DATOS QUE ENVIA, ASUNTO Y A QUE CORREO SE ENVIA
        //         $msj->from("informacion@germancontrolmotion.com","German Control Motion"); //REMITENTE
        //         $msj->subject($subject); //SE ENVIA EL ASUNTO
        //         $msj->to($correoProveedor); //A QUIEN SE ENVIA(CORREO)
        //     });

        // }

    
        $consultaProd = \DB::select("SELECT * FROM productosOrdenCompra WHERE idOrden = $request->idOrden"); 
        $cuantosProd = count($consultaProd);

        $idOrden=$request->idOrden;
        $diasPlazo=$request->diasPlazo;
        $tipoPago=$request->tipoPago;
        $idFactura=$request->idFactura;

        if($tipoPago=='Contado'){
            $diasPlazo=0;
        }else{
            $diasPlazo=$request->diasPlazo2;
        }

        $file = $request->file('cotizacionProveedor');
            if($file!=""){
                $ldate = date('Ymd_His_');
                $cotizacion = $file->getClientOriginalName();
                $cotizacionProveedor = $ldate.$cotizacion;
                \Storage::disk('local')->put($cotizacionProveedor, \File::get($file));
            }else{
                $cotizacion = ordenCompra::find($idOrden);
                $cotizacionProveedor= $cotizacion->cotizacionProveedor;
            }
        
        
    

        $orden = ordenCompra::find($idOrden);
        $orden->idProveedor =$request->idProveedor;
        $orden->idCuentaProv =$request->idCuentaProv;
        $orden->idCuenta =$request->idCuenta;
        $orden->idSucursal =$request->idSucursal;
        $orden->tipoServicio =$request->tipoServicio;
        $orden->codigoOrden =$request->codigoOrden;
        $orden->nombreEmpresa =$request->nombreEmpresa;
        $orden->fechaOrden =$request->fechaOrden;
        $orden->fechaProgPago =$request->fechaProgPago;
        $orden->fechaDeposito =$request->fechaDeposito;

        // if($request->estatus == 'Sin programar pago' && $cuantosProd != 0){
        //      //CORREO DE CREACION ORDEN DE COMPRA//

        //     $consultaProveedores = \DB::select("SELECT * from proveedores WHERE idProveedor = $idProveedor");  
        //     $consultaOrden = \DB::select("SELECT * FROM ordenCompra WHERE idOrden = $request->idOrden"); 
        //     $correoProveedor = $consultaProveedores[0]->correoProveedor; 
        //     // dd($correoProveedor);
        //     $subject = 'Nueva orden de compra'; 
        //     $datos = array(
        //         'consultaOrd'=>$consultaOrden[0]);
        //         Mail::send('correoOC', $datos, function($msj) use($subject,$correoProveedor){ 
        //         $msj->from("informacion@germancontrolmotion.com","German Control Motion");
        //         $msj->subject($subject); 
        //         $msj->to($correoProveedor);
        //     });
            
        // }

        $file = $request->file('evidenciaTransferencia');
        if($file!=""){
            $ldate = date('Ymd_His_');
            $evidenciaTransferencia = $file->getClientOriginalName();
            $evidenciaTransferenciaName = $ldate.$evidenciaTransferencia;
            \Storage::disk('local')->put($evidenciaTransferenciaName, \File::get($file));
        }else{
            $evidenciaTransferenciaName ='';
        }

        // if($request->estatus == 'Pago programado'){            
        //     $orden->evidenciaTransferencia =$evidenciaTransferenciaName;
        //     $orden->monedaComplementoPago =$request->monedaComplementoPago;
        //     $orden->montoComplementoPago =$request->montoComplementoPago;
        //     $orden->cambioComplementoPago =$request->cambiodolarComplemento;
        // }
        $orden->idPartesVenta =$request->idPartesVenta;
        $orden->idFactura =$request->idFactura;
        $orden->moneda =$request->monedaOrden;
        $orden->cambioDolar =$cambioDolar;
        // return $request->fechaProgPago;
        // if (($request->estatus == 'Sin programar pago' || $request->estatus == 'Archivos agregados, En espera de programación de pago') && $request->fechaProgPago != '') {
        //     $orden->estatus = 'Pago programado';
        // }else if($request->estatus == 'Pago programado' && $request->fechaDeposito != '' && $request->formato == "México"){
        //     $orden->estatus = 'Pagado';
        // }else if($request->estatus == 'Pago programado' && $request->fechaDeposito != '' && $request->formato == "CMG USA"){
        //     $orden->estatus = 'Pagado con complementos de pago';
        // }else{
        //     $orden->estatus =$request->estatus;
        // }
        
        $orden->notasOrden =$request->notasOrden;
        $orden->formaPago =$request->formaPago;
        $orden->cfdi =$request->cfdi;
        $orden->formato =$request->formato;
        $orden->paymentTerms =$request->paymentTerms;
        $orden->idu =$request->idu;
        $orden->estatusProveedor =$request->estatusProveedor;
        $orden->fechaEntregaProveedor =$request->fechaEntregaProveedor;
        $orden->domicilioEntrega =$request->domicilioEntrega;
        $orden->vigenciaCotizacion =$request->vigenciaCotizacion;
        $orden->cotizacionProveedor =$cotizacionProveedor;
        $orden->tipoPago =$request->tipoPago;
        $orden->diasPlazo =$diasPlazo;
        $orden->incoterm =$request->incoterm;
        $orden->detalleIncoterm =$request->detalleIncoterm;
        $orden->vendorQuote =$request->vendorQuote;
        $orden->dueDate =$request->dueDate;
        $orden->detalleMetodoEnvio =$request->detalleMetodoEnvio;
        $orden->billTo =$request->billTo;
        $orden->telAdicional =$request->telAdicional;
        $orden->correoAdicional =$request->correoAdicional;
        $orden->mostrarRfc =$request->mostrarRfc;
        $orden->mostrarTaxId =$request->mostrarTaxId;
        $orden-> save(); 

        // obtenemos los nuevos totales de una consulta en donde se suman los dineros de todo lo que se agrega a la orden
        $nuevosTotalesOrden=\DB::select("SELECT SUM(subtotal) AS importeOrden, SUM(ivaDinero) AS ivaCompra, SUM(isrDinero) AS isrCompra, SUM(precioProducto) AS total FROM productosOrdenCompra WHERE idOrden=$idOrden");
        // obteniendo los totales de la consulta para pasarlos al ubdate y actualizar el total de toda la orden
        $importeOrden=$nuevosTotalesOrden[0]->importeOrden;
        $ivaCompra=$nuevosTotalesOrden[0]->ivaCompra;
        $isrCompra=$nuevosTotalesOrden[0]->isrCompra;
        $total=$nuevosTotalesOrden[0]->total;
        
        // /obteniendo totales para el tipo de moneda
        if($request->monedaOrden=="USD"){
            if($cambioDolar < 1){
                $cambioDolar = 1;
            }
            $importeMXN=$importeOrden*$cambioDolar;
            $totalMXN=$total*$cambioDolar;
            $ivaMXN=$ivaCompra*$cambioDolar;
            $isrMXN=$isrCompra*$cambioDolar;
        }
        else{
            $importeMXN=$importeOrden;
            $totalMXN=$total;
            $ivaMXN=$ivaCompra;
            $isrMXN=$isrCompra;
        }

        // actualizando el total de la orden de compra cada que se agrega un nuevo producto
        $actualizarTotalOrden=
        \DB::select("UPDATE ordenCompra SET total=?, importeOrden=?, ivaCompra=?, isrCompra=?,importeMXN=?, totalMXN=?, ivaMXN=?, isrMXN=?
        WHERE idOrden = ?",[$total, $importeOrden, $ivaCompra, $isrCompra,$importeMXN, $totalMXN, $ivaMXN, $isrMXN, $idOrden]);

        $proceso='Registro exitoso de orden de compra';
        $mensaje="Orden registrada correctamente";
        return view ('mensajeOrdenCompra')
        ->with('idFactura',$idFactura)
        ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);
    }

    public function registrarProductos(Request $request){
        $idFactura=$request->idFactura;
        $idFacturaConEquipos=$request->idFacturaConEquipos;
        
        // igualamos las variables de idfactura por si se esta guardando un equipo de reparacion 
        if($idFactura == ''){
            $idFactura = $idFacturaConEquipos;
        }

        $gcmid=$request->gcmid;
        $idCuenta=$request->idCuenta;

        $cantidadOrden=$request->cantidadOrden;
        $precioOrden=$request->precioOrden;
        $subtotal=$precioOrden*$cantidadOrden;        

        $ivaOrden=$request->ivaOrden;
        $ivaDinero=($ivaOrden/100)*$subtotal;

        $isrOrden=$request->isrOrden;
        $isrDinero=($isrOrden/100)*$subtotal;
                
        $retencion=$request->retencion;
        $retencionDinero=($retencion/100)*($subtotal + $isrDinero + $ivaDinero);
        
        $idPartesVenta=$request->idPartesVenta;
        $idOrden=$request->idOrden;

        // $precioProducto=$subtotal+$ivaDinero+$isrDinero;
        $precioProducto=$request->totalCalculo;

        $moneda=$request->monedaOrden;
        $cambioDolar= $request->cambioDolarOrden == "" ? 1 : $request->cambioDolarOrden;

        $idOcupar=$request->idOcupar;
        $separador = "-";
        $idConsulta = explode($separador, $idOcupar);

        $precioFormato=number_format($precioProducto, 2 );
            
        $consulta = \DB::select("SELECT * from ordenCompra WHERE idOrden=$idOrden");

        $orden= new productosOrdenCompra;
        $orden->idOrden = $request->idOrden;
        $orden->idFactura = $idFactura;
        $orden->tipoServicio = $request->tipoEntrada;
        $orden->idEquipos = $request->idEquipo;
        $orden->idParte = $request->idParte;
        $orden->idPartesVenta = $request->idPartesVenta;
        $orden->nombreRefaccion = $request->nombreRefaccion;
        $orden->codigo = $request->codigo;
        $orden->precioOrden = $request->precioOrden;
        $orden->cantidadOrden = $request->cantidadOrden;
        $orden->moneda = $moneda;
        $orden->subtotal = $subtotal;
        $orden->ivaOrden = $request->ivaOrden;
        $orden->ivaDinero = $ivaDinero;
        $orden->retencion = $request->retencion;
        $orden->retencionDinero = $retencionDinero;
        $orden->isrOrden = $request->isrOrden;
        $orden->isrDinero = $isrDinero;
        $orden->precioProducto = $precioProducto;
        $orden->notasProducto = $request->notasProducto;
        $orden->vendorPartNumber = $request->vendorPartNumber;
        // $orden->idRepcotizacion = $idRepcotizacion;
        // $orden->idRefaccionCotizacion = $idRefaccionCotizacion;
        $orden-> save();

        // Crando la asignacion del producto a servicio
        // if($idFactura != ''){
        //     $this->crearAsignacion($orden->idProducto , $request);
        // }

        // obtenemos los nuevos totales de una consulta en donde se suman los dineros de todo lo que se agrega a la orden
        $nuevosTotalesOrden=\DB::select("SELECT SUM(subtotal) AS importeOrden, SUM(ivaDinero) AS ivaCompra, SUM(isrDinero) AS isrCompra, SUM(retencionDinero) AS retencionCompra, SUM(precioProducto) AS total FROM productosOrdenCompra WHERE idOrden=$idOrden");
        // obteniendo los totales de la consulta para pasarlos al ubdate y actualizar el total de toda la orden
        $importeOrden=$nuevosTotalesOrden[0]->importeOrden;
        $ivaCompra=$nuevosTotalesOrden[0]->ivaCompra;
        $isrCompra=$nuevosTotalesOrden[0]->isrCompra;
        $retencionCompra=$nuevosTotalesOrden[0]->retencionCompra;
        $total=$nuevosTotalesOrden[0]->total;
        
        // /obteniendo totales para el tipo de moneda
        if($moneda=="USD"){
            if($cambioDolar < 1){
                $cambioDolar = 1;
            }
            $importeMXN=$importeOrden*$cambioDolar;
            $totalMXN=$total*$cambioDolar;
            $ivaMXN=$ivaCompra*$cambioDolar;
            $isrMXN=$isrCompra*$cambioDolar;
            $retencionMXN=$retencionCompra*$cambioDolar;
        }
        else{
            $importeMXN=$importeOrden;
            $totalMXN=$total;
            $ivaMXN=$ivaCompra;
            $isrMXN=$isrCompra;
            $retencionMXN=$retencionCompra;
        }

        // actualizando el total de la orden de compra cada que se agrega un nuevo producto
        $actualizarTotalOrden=
        \DB::select("UPDATE ordenCompra SET total=?, importeOrden=?, ivaCompra=?, isrCompra=?, retencionCompra=?,importeMXN=?, totalMXN=?, ivaMXN=?, isrMXN=?, retencionMXN=?
        WHERE idOrden = ?",[$total, $importeOrden, $ivaCompra, $isrCompra,$retencionCompra,$importeMXN, $totalMXN, $ivaMXN, $isrMXN, $retencionMXN, $idOrden]);

        /////CONSULTAS PARA EL REPORTE////
        $ordenCompra=\DB::select("SELECT idProducto, po.idOrden, po.idPartesVenta, po.nombreRefaccion,po.tipoServicio,
        notasProducto,vendorPartNumber, codigo, po.cantidadOrden, oc.cambioDolar,po.idFactura,f.idServicios,
        FORMAT(subtotal,2) as subtotal, ivaOrden, FORMAT(ivaDinero,2) ivaDinero, isrOrden,
        FORMAT(isrDinero,2) as isrDinero, FORMAT(precioProducto,2) as precioProducto,
        FORMAT(retencionDinero,2) as retencionDinero,retencion,
        FORMAT(po.precioOrden,2) as precioOrden,po.moneda, idRefaccionCotizacion, idRepcotizacion
        FROM productosOrdenCompra as po
        inner join ordenCompra as oc on oc.idOrden=po.idOrden
        left join facturas as f on f.idFactura=po.idFactura
        WHERE po.idOrden=$idOrden");
        $cuantosProductos=count($ordenCompra);
        
        $sumaTotal=\DB::select("SELECT oc.moneda, oc.idFactura, FORMAT(SUM(precioProducto),2) AS total,
        FORMAT(SUM(isrDinero),2) as isrDineroTotal,
        FORMAT(SUM(ivaDinero),2) as ivaDineroTotal,
        FORMAT(SUM(retencionDinero),2) as retencionDineroTotal,
        FORMAT(SUM(subtotal),2) as importe
        FROM productosOrdenCompra AS pc
        INNER JOIN ordenCompra as oc ON pc.idOrden=oc.idOrden
        WHERE pc.idOrden= $idOrden");
        /////TERMINAN CONSULTAS PARA EL REPORTE////

        return view('reporteOrdenAgregada')
        ->with('sumaTotal',$sumaTotal[0])
        ->with('consulta',$consulta[0])
        ->with('ordenCompra',$ordenCompra);
    }

    public function comboCuentaProv(Request $request){
        $idProveedor=$request->idProveedor;
        $consulta=\DB::select("SELECT idProveedor, idCuentaProv, numeroCuentaProv, bancoProv, tipoMonedaProv FROM cuentasproveedores WHERE idProveedor=$idProveedor");
        
        return view('comboCuentaProv')->with('consulta', $consulta);
    }

    public function inicialesProv(Request $request){
        $idProveedor=$request->idProveedor;
        $consultaProv=\DB::select("SELECT idOrden, idFactura, p.iniciales
        FROM ordenCompra AS oc
        INNER JOIN proveedores AS p ON p.idProveedor=oc.idProveedor
        WHERE oc.idProveedor=$idProveedor");
        $numero=count($consultaProv);
        
        $consecutivo=$numero+1;
        $consecutivoCotizacion = str_pad($consecutivo,6,"0",STR_PAD_LEFT);        
        $consulta=\DB::select("SELECT iniciales FROM proveedores WHERE idProveedor=$idProveedor");

        $folio = $consulta[0]->iniciales.'-'.$consecutivoCotizacion;
        
        return view('inicialesProv')
        ->with('folio', $folio);
    }

    public function infoSkuOrden(Request $request){        
        $idPartesVenta=$request->idPartesVenta;
        $idParte=$request->idParte;
        
        if ($idPartesVenta != '') {
            $consulta=\DB::select("SELECT pv.codigo,pv.nombreRefaccion, pv.idPartesVenta
            FROM partesVenta AS pv WHERE pv.idPartesVenta=$idPartesVenta");
        }else{
            $consulta=\DB::select("SELECT GCMidParte as codigo,nombreParte as nombreRefaccion, ' ' as idPartesVenta FROM partesreparacion WHERE idParte = $idParte");
        }        

        return view('comboSkuOrden')->with('consulta',$consulta[0]);
    
    }

    public function cantidadSolicitada(Request $request){
        $idPartesVenta=$request->idPartesVenta;
        $idOcupar=$request->idOcupar;
        $separador = "-";
        $idConsulta = explode($separador, $idOcupar);

        $idFactura=$request->idFactura;

        $consulta=\DB::select("SELECT (dr.piezas-dr.cantidadOrden) AS piezas
        FROM facturas AS f
        INNER JOIN detallereparaciones AS dr ON dr.idCotizacion = f.idCotizacion
        WHERE idRepCotizacion=$idConsulta[0]");
        $cuantasPiezas=count($consulta);

        if($idConsulta[1]==0){
            $consulta;
            return view('cantidadSolicitadaOrden')
            ->with('cuantasPiezas',$cuantasPiezas)
            ->with('consulta',$consulta[0]);
            
        }else{
            $consulta=1;    
            return view('cantidadSolicitadaOrden')
            ->with('cuantasPiezas',$cuantasPiezas)
            ->with('consulta',$consulta);
        }
    }

    public function infoServicioOrden(Request $request){
        $idDetalleCotizacionServ=$request->idDetalleCotizacionServ;
        $consulta=\DB::select("SELECT dc.idDetalleCotizacionServ, dc.idServicioTecnico, st.claveServicio, st.servicioTecnico, dc.cantidad
        FROM detalleCotizacionServicios AS dc
        INNER JOIN serviciosTecnicos AS st ON st.idServicioTecnico = dc.idServicioTecnico
        WHERE dc.idDetalleCotizacionServ=$idDetalleCotizacionServ");
        
        return view('infoServicioOrden')->with('consulta',$consulta[0]);

    }

    public function cambiarCantidad(Request $request){
        $idDetalleCotizacionServ=$request->idDetalleCotizacionServ;
        $consulta=\DB::select("SELECT dc.idDetalleCotizacionServ, dc.cantidad
        FROM detalleCotizacionServicios AS dc
        INNER JOIN serviciosTecnicos AS st ON st.idServicioTecnico = dc.idServicioTecnico
        WHERE dc.idDetalleCotizacionServ=$idDetalleCotizacionServ");
        
        return view('cambiarCantidadServicio')
            ->with('consulta',$consulta[0]);
    }

    public function cambiarBotonOrden(Request $request){
        $idFactura=$request->idFactura;
        $consultaOrden = \DB::select("SELECT dr.idRepcotizacion AS detalle,0 AS refacciones, CONCAT (dr.idRepcotizacion, '-', 0) AS idOcupar,CONCAT (re.GCMid,'-',re.complementoGCMid) AS gcmid, pv.idPartesVenta, co.idCotizacion, codigo, nombreRefaccion, f.nombreEmpresa, dr.piezas
        FROM cotizaciones AS co
        INNER JOIN facturas AS f ON f.idCotizacion=co.idCotizacion
        INNER JOIN detallereparaciones AS dr ON dr.idCotizacion=co.idCotizacion
        INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idEquipos
        LEFT JOIN recepcionEquipos AS re ON re.idEquipos=dr.idEquipos
        WHERE f.idFactura=$idFactura AND dr.piezas!=dr.cantidadOrden
                    
        UNION     
                    
        SELECT 0, rc.idRefaccionCotizacion, CONCAT (0, '-', rc.idRefaccionCotizacion) AS idOcupar,CONCAT (re.GCMid,'-',re.complementoGCMid) AS gcmid, pv.idPartesVenta, co.idCotizacion, codigo, pv.nombreRefaccion, f.nombreEmpresa,rc.idRefaccionCotizacion
        FROM cotizaciones AS co
        INNER JOIN facturas AS f ON f.idCotizacion=co.idCotizacion
        INNER JOIN refaccionesEnCotizacion AS rc ON rc.idCotizacion=co.idCotizacion
        INNER JOIN partesVenta AS pv ON pv.idPartesVenta = rc.idPartesVenta
        INNER JOIN recepcionEquipos AS re ON re.idEquipos=rc.idEquipos
        WHERE f.idFactura=$idFactura AND rc.cantidadOrden=0 
        ORDER BY gcmid, nombreRefaccion ASC");

        $cuantos=count($consultaOrden);
        return view('cambiarBotonOrden')
        ->with('cuantos', $cuantos);
    }

    public function cambiarComboProducto(Request $request){
        $idFactura=$request->idFactura;

        $consultaOrden = \DB::select("SELECT dr.idRepcotizacion AS detalle,0 AS refacciones, CONCAT (dr.idRepcotizacion, '-', 0) AS idOcupar,CONCAT (re.GCMid,'-',re.complementoGCMid) AS gcmid, pv.idPartesVenta, co.idCotizacion, codigo, nombreRefaccion, f.nombreEmpresa, dr.piezas
        FROM cotizaciones AS co
        INNER JOIN facturas AS f ON f.idCotizacion=co.idCotizacion
        INNER JOIN detallereparaciones AS dr ON dr.idCotizacion=co.idCotizacion
        INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idEquipos
        LEFT JOIN recepcionEquipos AS re ON re.idEquipos=dr.idEquipos
        WHERE f.idFactura=$idFactura AND dr.piezas!=dr.cantidadOrden
                    
        UNION     
                    
        SELECT 0, rc.idRefaccionCotizacion, CONCAT (0, '-', rc.idRefaccionCotizacion) AS idOcupar,CONCAT (re.GCMid,'-',re.complementoGCMid) AS gcmid, pv.idPartesVenta, co.idCotizacion, codigo, pv.nombreRefaccion, f.nombreEmpresa,rc.idRefaccionCotizacion
        FROM cotizaciones AS co
        INNER JOIN facturas AS f ON f.idCotizacion=co.idCotizacion
        INNER JOIN refaccionesEnCotizacion AS rc ON rc.idCotizacion=co.idCotizacion
        INNER JOIN partesVenta AS pv ON pv.idPartesVenta = rc.idPartesVenta
        INNER JOIN recepcionEquipos AS re ON re.idEquipos=rc.idEquipos
        WHERE f.idFactura=$idFactura AND rc.cantidadOrden=0

        ORDER BY gcmid, nombreRefaccion ASC");

        $cuantos=count($consultaOrden);
        return view('cambiarComboProducto')
        ->with('consultaOrden', $consultaOrden)
        ->with('cuantos', $cuantos);
    }

    public function borrarProductoOrden(Request $request){
        $idProducto=$request->idProducto;
        $idFactura=$request->idFacturaBorrar;
        $cantidadOrden=$request->cantidadBorrar;
        $idPartesVenta=$request->idPartes;
        $idOrden=$request->idOrden;
        $precioProducto=$request->precioBorrar;
        $subtotal=$request->precioOrdenBorrar;
        $ivaDinero=$request->ivaBorrar;
        $isrDinero=$request->isrBorrar;
        $retencionDinero=$request->retencionBorrar;

        $idRepcotizacion=$request->idRepcotizacion;
        $idRefaccionCotizacion=$request->idRefaccionCotizacion;

        $moneda=$request->monedaOrden;
        $cambioDolar=$request->cambioDolar;    

        $consultaCuenta=\DB::select("SELECT oc.idCuenta FROM cuentas AS c 
                        INNER JOIN ordenCompra AS oc ON oc.idCuenta = c.idCuenta
                        WHERE oc.idOrden=$idOrden");
        $idCuenta=$consultaCuenta[0]->idCuenta;

        // $actualizarCuenta=\DB::select("UPDATE cuentas SET saldoCuenta = saldoCuenta + ? WHERE idCuenta=?",[$precioProducto,$idCuenta]);

        $borro= \DB::delete("DELETE FROM productosOrdenCompra WHERE idProducto=?",[$idProducto]);
        
        // Borrando la asignacion del producto
        $this->eliminarAsignacion($idProducto);

        // obtenemos los nuevos totales de una consulta en donde se suman los dineros de todo lo que resulte depues de eliminar un producto
        $nuevosTotalesOrden=\DB::select("SELECT SUM(subtotal) AS importeOrden, SUM(ivaDinero) AS ivaCompra, SUM(isrDinero) AS isrCompra, SUM(retencionDinero) AS retencionCompra, SUM(precioProducto) AS total FROM productosOrdenCompra WHERE idOrden=$idOrden");
        // obteniendo los totales de la consulta para pasarlos al ubdate y actualizar el total de toda la orden
        $importeOrden=$nuevosTotalesOrden[0]->importeOrden;
        $ivaCompra=$nuevosTotalesOrden[0]->ivaCompra;
        $isrCompra=$nuevosTotalesOrden[0]->isrCompra;
        $retencionCompra=$nuevosTotalesOrden[0]->retencionCompra;
        $total=$nuevosTotalesOrden[0]->total;
        
        // /obteniendo totales para el tipo de moneda
        if($moneda=="USD"){
            if($cambioDolar < 1){
                $cambioDolar = 1;
            }
            $importeMXN=$importeOrden*$cambioDolar;
            $totalMXN=$total*$cambioDolar;
            $ivaMXN=$ivaCompra*$cambioDolar;
            $isrMXN=$isrCompra*$cambioDolar;
            $retencionMXN=$retencionCompra*$cambioDolar;
        }
        else{
            $importeMXN=$importeOrden;
            $totalMXN=$total;
            $ivaMXN=$ivaCompra;
            $isrMXN=$isrCompra;
            $retencionMXN=$retencionCompra;
        }

        // actualizando el total de la orden de compra cada que se agrega un nuevo producto
        $actualizarTotalOrden=
        \DB::select("UPDATE ordenCompra SET total=?, importeOrden=?, ivaCompra=?, isrCompra=?, retencionCompra=?,importeMXN=?, totalMXN=?, ivaMXN=?, isrMXN=?, retencionMXN=?
        WHERE idOrden = ?",[$total, $importeOrden, $ivaCompra, $isrCompra,$retencionCompra,$importeMXN, $totalMXN, $ivaMXN, $isrMXN, $retencionMXN, $idOrden]);

        /////CONSULTAS PARA EL REPORTE////
        $ordenCompra=\DB::select("SELECT po.idProducto, po.idOrden, po.idPartesVenta,po.tipoServicio,
        po.nombreRefaccion, po.notasProducto,po.vendorPartNumber, po.codigo, po.cantidadOrden, po.idFactura, oc.cambioDolar,f.idServicios,
        FORMAT(po.subtotal,2) as subtotal, po.ivaOrden, FORMAT(po.ivaDinero,2) ivaDinero, po.isrOrden,
        FORMAT(po.isrDinero,2) as isrDinero, FORMAT(po.precioProducto,2) as precioProducto,
        FORMAT(retencionDinero,2) as retencionDinero,retencion,
        FORMAT(po.precioOrden,2) as precioOrden,po.moneda, po.idRefaccionCotizacion, po.idRepcotizacion
        FROM productosOrdenCompra as po
        inner join ordenCompra as oc on oc.idOrden=po.idOrden
        left join facturas as f on f.idFactura=po.idFactura
        WHERE po.idOrden=$idOrden");
        $cuantosProductos=count($ordenCompra);
        
        $sumaTotal=\DB::select("SELECT oc.moneda, oc.idFactura, FORMAT(SUM(precioProducto),2) AS total,
        FORMAT(SUM(isrDinero),2) as isrDineroTotal,
        FORMAT(SUM(ivaDinero),2) as ivaDineroTotal,
        FORMAT(SUM(retencionDinero),2) as retencionDineroTotal,
        FORMAT(SUM(subtotal),2) as importe
        FROM productosOrdenCompra AS pc
        INNER JOIN ordenCompra as oc ON pc.idOrden=oc.idOrden
        WHERE pc.idOrden= $idOrden");
        $consulta = \DB::select("SELECT * from ordenCompra WHERE idOrden=$idOrden");
                /////TERMINAN CONSULTAS PARA EL REPORTE////

        return view('reporteOrdenAgregada')
        ->with('sumaTotal',$sumaTotal[0])
        ->with('consulta',$consulta[0])
        ->with('ordenCompra',$ordenCompra);
    }

    public function eliminarOrden($idOrden){
        // $idOrden=$request->idOrden;
        $consulta=\DB::select("SELECT * FROM ordenCompra WHERE idOrden=$idOrden");
        $idFactura=$consulta[0]->idFactura;

        $idRepcotizacionEliminar=\DB::select(" SELECT idRepcotizacion 
            FROM productosOrdenCompra AS pc
            INNER JOIN ordenCompra AS oc ON oc.idOrden=pc.idOrden
            WHERE oc.idOrden=$idOrden");

        $idRefaccionCotizacionEliminar=\DB::select(" SELECT idRefaccionCotizacion 
            FROM productosOrdenCompra AS pc
            INNER JOIN ordenCompra AS oc ON oc.idOrden=pc.idOrden
            WHERE oc.idOrden=$idOrden");

        for($i=0; $i<count($idRefaccionCotizacionEliminar); $i++){
            $idRefaccionCotizacion=$idRefaccionCotizacionEliminar[$i]->idRefaccionCotizacion;
            $actualizar=\DB::select("UPDATE refaccionesEnCotizacion SET cantidadOrden = 0 WHERE idRefaccionCotizacion = ?",[$idRefaccionCotizacion]);
        }
            
        for($i=0; $i<count($idRepcotizacionEliminar); $i++){
            $idRepcotizacion=$idRepcotizacionEliminar[$i]->idRepcotizacion;
            $actualizar=\DB::select("UPDATE detallereparaciones SET cantidadOrden = 0 WHERE idRepCotizacion = ?",[$idRepcotizacion]);
        }
        
        $consultaCuenta=\DB::select("SELECT oc.idCuenta, oc.total FROM cuentas AS c INNER JOIN ordenCompra AS oc ON oc.idCuenta = c.idCuenta WHERE oc.idOrden=$idOrden");
        $idCuenta=$consultaCuenta[0]->idCuenta;
        $total=$consultaCuenta[0]->total;
        // $actualizarCuenta=\DB::select("UPDATE cuentas SET saldoCuenta = saldoCuenta + ? WHERE idCuenta=?",[$total,$idCuenta]);
        //$actualizarTotalOrden=\DB::select("UPDATE ordenCompra SET total = 0, importeOrden = 0, ivaCompra= 0, isrCompra = 0 WHERE idOrden = ?",[$idOrden]);
        //$borro= \DB::delete("DELETE FROM productosOrdenCompra WHERE idOrden=?",[$idOrden]);

        $facturas = \DB::UPDATE("update ordenCompra set activo ='No' where idOrden=$idOrden");

        $proceso ="Eliminación de orden de compra";
        $mensaje="La orden de compra ha sido desactivada correctamente";
        return view ('mensajeOrdenCompra')
        ->with('idFactura',$idFactura)
        ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);
        // return $request;
    }

    public function restaurarOrden($idOrden){
        $consulta=\DB::select("SELECT * FROM ordenCompra WHERE idOrden=$idOrden");
        $idFactura=$consulta[0]->idFactura;

        $facturas = \DB::UPDATE("update ordenCompra
            set activo ='Si' where idOrden=$idOrden");

        $proceso ="Restauración de orden de compra";
        $mensaje="La orden de compra ha sido activada correctamente";
        return view ('mensajeOrdenCompra')
        ->with('idFactura',$idFactura)
        ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);
    }

    public function modificarFechaEntregaProveedor(Request $request){
        $idOrden=$request->idOrden;
        $tipoReporte=$request->tipoReporte;
        
        return view('modificarFechaEntregaProveedor')->with('idOrden',$idOrden)->with('tipoReporte',$tipoReporte);

    }

    public function guardarModificacionFechaEntregaProveedor(Request $request){
        $idOrden=$request->idOrden;
        $tipoReporte=$request->tipoReporte;
        $fechaEntregaProveedor=$request->fechaEntregaProveedor;
        $notasCambioFechaEntregaProveedor=$request->notasCambioFechaEntregaProveedor;

        $idProveedor = \DB::select("SELECT idProveedor FROM ordenCompra Where idOrden=?",[$idOrden]);
        // $consultaProveedores = \DB::select("SELECT * from proveedores WHERE idProveedor = ?",[$idProveedor]);  
        $consultaOrden = \DB::select("SELECT * FROM ordenCompra WHERE idOrden = $idOrden"); 
        $correoAdmin ="al221811708@gmail.com" ; //"finanzas@gcmgdl.com";
	    $subject = 'Modificación de fecha de entrega.';
        $datos = array(
            'fechaEntregaProveedor'=>$fechaEntregaProveedor,
            'motivo'=>$notasCambioFechaEntregaProveedor,
            // 'consultaprov'=>$consultaProveedores[0],
            'consultaOrd'=>$consultaOrden[0]
            ); 
        Mail::send('correoModFechaReg', $datos, function($msj) use($subject,$correoAdmin){ 
            $msj->from("informacion@germancontrolmotion.com","German Control Motion"); //zyqaiipittxoufzn
            $msj->subject($subject); 
            $msj->to($correoAdmin); 
        });

        $orden = ordenCompra::find($idOrden);
        $orden->fechaEntregaProveedor =$request->fechaEntregaProveedor;
        $orden->notasCambioFechaEntregaProveedor =$request->notasCambioFechaEntregaProveedor;
        $orden-> save();

        if ($tipoReporte == '2') {            
            $consulta = ordenCompra::where('idOrden','=',$idOrden)->where('activo','=','Si')->orderBy('fechaOrden','DESC')->get();
        }else{
            $idProveedor = \DB::select("SELECT idProveedor FROM ordenCompra Where idOrden=?",[$idOrden]);
            $consulta = ordenCompra::where('idProveedor','=',$idProveedor[0]->idProveedor)->where('activo','=','Si')->orderBy('fechaOrden','DESC')->get();
        }

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
                    
        $sumaTotalImpuestos=\DB::select("SELECT oc.moneda, oc.idFactura, FORMAT(SUM(precioProducto),2) AS total,
        FORMAT(SUM(isrDinero),2) as isrDineroTotal,
        FORMAT(SUM(ivaDinero),2) as ivaDineroTotal,
        FORMAT(SUM(subtotal),2) as importe
        FROM productosOrdenCompra AS pc
        INNER JOIN ordenCompra as oc ON pc.idOrden=oc.idOrden
        WHERE pc.idOrden= $idOrden");
        
        return view("reporteOrdenesCompraProveedor", compact('folioConfirmacion'))
        ->with('sumaTotalImpuestos',$sumaTotalImpuestos[0])
        ->with('idOrden',$idOrden)
        ->with('consulta',$consulta)
        ->with('tipoReporte',$tipoReporte);

    }

    public function cargarModalDetalleOrden(Request $request){
        $idOrden=$request->idOrden;
        $consulta = \DB::select("SELECT * from ordenCompra WHERE idOrden=$idOrden");
        $pdfProv = \DB::select("SELECT oc.pdfFacturaProveedor FROM ordenCompra AS oc Where idOrden= $idOrden");
        $xmlProv = \DB::select("SELECT oc.xmlFacturaProveedor FROM ordenCompra AS oc Where idOrden= $idOrden");
        $transf = \DB::select("SELECT oc.evidenciaTransferencia FROM ordenCompra AS oc Where idOrden= $idOrden");
        $xmlComp = \DB::select("SELECT oc.xmlComplementoProveedor FROM ordenCompra AS oc Where idOrden= $idOrden");
        $pdfComp = \DB::select("SELECT oc.pdfComplementoProveedor FROM ordenCompra AS oc Where idOrden= $idOrden");
        $archivoPaq1 = \DB::select("SELECT oc.archivoPaqueteria1 FROM ordenCompra AS oc Where idOrden= $idOrden");
        $archivoPaq2 = \DB::select("SELECT oc.archivoPaqueteria2 FROM ordenCompra AS oc Where idOrden= $idOrden");
        $archivoPaq3 = \DB::select("SELECT oc.archivoPaqueteria3 FROM ordenCompra AS oc Where idOrden= $idOrden");
        $archivoPaq4 = \DB::select("SELECT oc.archivoPaqueteria4 FROM ordenCompra AS oc Where idOrden= $idOrden");
        
        return view('modalDetalleArchivosOrdenCompra')
        ->with('consulta', $consulta[0])
        ->with('pdfProv',$pdfProv)
        ->with('xmlProv',$xmlProv)
        ->with('transf',$transf)
        ->with('pdfComp',$pdfComp)
        ->with('xmlComp',$xmlComp)
        ->with('archivoPaq1',$archivoPaq1)
        ->with('archivoPaq2',$archivoPaq2)
        ->with('archivoPaq3',$archivoPaq3)
        ->with('archivoPaq4',$archivoPaq4);
    }

    public function reporteProductosEnOC(Request $request){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else{
            $registros = \DB::SELECT("SELECT oc.fechaOrden,oc.codigoOrden,oc.nombreEmpresa AS Empresa,pro.razonSocialProv,
            IF(fact.idservicios IS NULL,'-',fact.idservicios) AS servicio
            ,poc.codigo,poc.nombreRefaccion,
            oc.moneda,poc.cantidadOrden, poc.precioOrden AS `precio unitario USDMXN`,
            IF(poc.moneda ='USD',oc.cambioDolar * poc.precioOrden, poc.precioOrden) AS 'precio unitario MXN',
            poc.notasProducto
            FROM productosOrdenCompra AS poc
            INNER JOIN ordenCompra AS oc ON oc.idorden = poc.idorden
            INNER JOIN proveedores AS pro ON pro.idProveedor = oc.idProveedor
            LEFT JOIN facturas AS fact ON fact.idfactura = poc.idFactura
            ORDER BY fechaOrden DESC");

            return view('reporteProductosEnOC')
            ->with('registros', $registros);

        }
    }

    /*public function consultaProductosOC(){
        $consultaProducto= \DB::select("SELECT
        (SELECT oc.codigoOrden FROM ordenCompra AS oc WHERE oc.idOrden = (SELECT ne.idOrden FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada)) AS ordenCompra,
        (SELECT DATE_FORMAT(oc.fechaOrden,'%d/%m/%Y') FROM ordenCompra AS oc WHERE oc.idOrden = (SELECT ne.idOrden FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada)) AS fechaOrden,
        (SELECT ne.numeroNota FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS numeroNota,
        (SELECT p.razonSocialProv FROM proveedores AS p WHERE p.idProveedor = (SELECT oc.idProveedor FROM ordenCompra AS oc WHERE oc.idOrden = (SELECT ne.idOrden FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada))) AS proveedor,
        (SELECT oc.nombreEmpresa FROM ordenCompra AS oc WHERE oc.idOrden = (SELECT ne.idOrden FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada)) AS empresa,
        (SELECT poc.codigo FROM productosOrdenCompra AS poc WHERE poc.idProducto = dne.idProducto) AS sku,
        (SELECT poc.vendorPartNumber FROM productosOrdenCompra AS poc WHERE poc.idProducto = dne.idProducto) AS vpn,
        (dne.piezasRecibidas) AS cantidad,
        (SELECT DATE_FORMAT(ne.fechaEntrega,'%d/%m/%Y') FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS fechaEntrega,
        (SELECT ne.aplicaAnticipo FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS aplicaAnticipo,
        (SELECT ne.estatusPago FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS estatusPago,
        (SELECT ne.estatusPago_anticipo FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS estatusPago_anticipo,
        (SELECT IFNULL(ne.numGuia,'') FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS numGuia,
        (SELECT ne.ciudadOrigen FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS ciudadOrigen,
        (SELECT ne.ciudadDestino FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS ciudadDestino,
        (SELECT  IFNULL(ne.paqueteria,'') FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS paqueteria,
        (SELECT IFNULL(ne.numGuia2,'') FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS numGuia2,
        (SELECT ne.ciudadOrigen2 FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS ciudadOrigen2,
        (SELECT ne.ciudadDestino2 FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS ciudadDestino2,
        (SELECT  IFNULL(ne.paqueteria2,'') FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS paqueteria2,
        (SELECT ne.folioFacturaImportacion FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS folioFacturaImportacion,
        (SELECT ne.folioGastosImportacion FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS folioGastosImportacion,
        (SELECT ne.metodoEntrega FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS metodoEntrega,
        (SELECT ne.detalleEntrega FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS detalleEntrega,
        (SELECT ne.archivoPaqueteria1 FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS archivoPaqueteria1,
        (SELECT ne.archivoPaqueteria2 FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS archivoPaqueteria2,
        (SELECT ne.archivoPaqueteria3 FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS archivoPaqueteria3,
        (SELECT ne.archivoPaqueteria4 FROM notasEntrada AS ne WHERE ne.idNotaEntrada = dne.idNotaEntrada) AS archivoPaqueteria4
        FROM detalleNotasEntrada AS dne
        ORDER BY dne.idDetalleNotasEntrada DESC");
        
        return response()->json($consultaProducto, 200);
    }*/

    // Funcion para crear el registro en la tabla de asignaciones 
    // siempre y cuando el producto agregado en la orde tenfa un idFactura
    public function crearAsignacion($idProducto, $request){

        $datos_oc = \DB::select("SELECT oc.idProveedor,oc.idOrden,oc.nombreEmpresa,fechaOrden FROM ordenCompra AS oc WHERE oc.idOrden = $request->idOrden");
        $datos_parte_venta_producto = \DB::select("SELECT pv.numeroParte, pv.serie, pv.modelo, pv.codigo FROM partesVenta AS pv WHERE pv.idPartesVenta = (SELECT poc.IdPartesVenta FROM productosOrdenCompra AS poc WHERE poc.idProducto = $idProducto)");
        // $datos_producto_en_oc = \DB::select("SELECT poc.idFactura FROM productosOrdenCompra AS poc WHERE poc.idProducto = $idProducto");
        $nombre_usuario = Session::get('sesionname')." ".Session::get('sesionpaterno')." ".Session::get('sesionmaterno');

        $entrada = new entradasAlmacen;
        $entrada->idu = Session::get('sesionidu');
        $entrada->nombreUsuario = $nombre_usuario;
        $entrada->fechaEntrada = $datos_oc[0]->fechaOrden;
        $entrada->idProveedor = $datos_oc[0]->idProveedor;
        $entrada->recibidoPor = $nombre_usuario;
        $entrada->save();
        // recuperando el id del nuevo registro creado
        $utima_entrada = $entrada->idEntradaAlmacen;

        $productoPorEntrada = new productosPorEntradaAlmacen;
        $productoPorEntrada->idEntradaAlmacen = $utima_entrada;
		$productoPorEntrada->idu = Session::get('sesionidu');
		$productoPorEntrada->nombreUsuario = $nombre_usuario;
		$productoPorEntrada->fechaEntrada = $datos_oc[0]->fechaOrden;
		$productoPorEntrada->idOrden = $datos_oc[0]->idOrden;
		$productoPorEntrada->idProveedor = $datos_oc[0]->idProveedor;
		$productoPorEntrada->empresa = $datos_oc[0]->nombreEmpresa;
		$productoPorEntrada->almacen = $datos_oc[0]->nombreEmpresa;
		$productoPorEntrada->recibidoPor = $nombre_usuario;
		$productoPorEntrada->idProducto = $idProducto;
        // $productoPorEntrada->idNotaEntrada = $datos_oc[0]->idNotaEntrada;
        // $productoPorEntrada->idDetalleNotasEntrada = $datos_oc[0]->idDetalleNotasEntrada;
		$productoPorEntrada->numeroParte = $datos_parte_venta_producto[0]->numeroParte;
		$productoPorEntrada->numeroSerie = $datos_parte_venta_producto[0]->serie;
		$productoPorEntrada->modelo = $datos_parte_venta_producto[0]->modelo;
		$productoPorEntrada->presentacion = "Pieza";
        $productoPorEntrada->fotoPlaca = "Sin archivo";
		$productoPorEntrada->fotoPrincipal = "Sin archivo";
		// $productoPorEntrada->unidadesPorPresentacion =
		$productoPorEntrada->cantidadRecibida = $request->cantidadOrden;
		// $productoPorEntrada->observaciones = $datos_oc[0]->observaciones;
        // Si ya tienen numero de factura, entonces ya esta asignada a un servicio
        $productoPorEntrada->estatusAsignacion = 1;
        $productoPorEntrada->idFactura = $request->idFactura;
    
        $productoPorEntrada->save();
        
        // recuperando el id del nuevo registro creado
        $producto_entrada_generada = $productoPorEntrada->idProductoEntrada;

        $asignacion = new productosServiciosAsignacion;
        $asignacion->idOrden = $request->idOrden;
        $asignacion->idProductoEntrada = $producto_entrada_generada;
        $asignacion->idProducto = $idProducto;
        $asignacion->idFactura = $request->idFactura;
        $asignacion->tipo_asignacion = "a_servicio";
        $asignacion->idProductoEntrada_referencia = 0;
        $asignacion->cantidadAservicio = $request->cantidadOrden;
        $asignacion->fechaAsignacion = $datos_oc[0]->fechaOrden;
        $asignacion->detalles = $request->notasProducto;
        $asignacion->save();
        
        return;
    }

    public function eliminarAsignacion($idProducto){
        // Eliminamos la entrada por si existe
        \DB::delete("DELETE FROM productosPorEntradaAlmacen WHERE idProducto=?",[$idProducto]);

        // eliminamos el registro de la BD
        \DB::delete("DELETE FROM productosServiciosAsignacion WHERE idProducto=?",[$idProducto]);
        return;
    }

    public function consultaParaExcelOC(Request $request){
        
        if(is_null($request->fechaInicioOrden)){
            $fechaInicioOrden = '0000-00-00';
        }else{
            $fechaInicioOrden = $request->fechaInicioOrden;
        }
        
        if(is_null($request->fechaFinOrden)){
            $fechaFinOrden = '9999-12-31';
        }else{
            $fechaFinOrden = $request->fechaFinOrden;
        }

        if($request->reporte == "all"){            
            $comparacion ='!=';
        }else{
            $comparacion ='=';
        }

        $registros= DB::table('ordenCompra as oc')
            ->select(
                'oc.idOrden',
                'oc.codigoOrden',
                DB::raw('DATE_FORMAT(oc.fechaOrden,"%d/%m/%Y") AS fecha_formato'),
                'p.razonSocialProv',
                'oc.nombreEmpresa',
                DB::raw('CONCAT(oc.moneda, " $", FORMAT(oc.importeOrden,2)) AS importeOrden'),
                DB::raw('CONCAT(" $", FORMAT(oc.ivaCompra,2)) AS iva'),
                DB::raw('CONCAT(" $", FORMAT(oc.isrCompra,2)) AS isr'),
                DB::raw('CONCAT(" $", FORMAT(oc.retencionCompra,2)) AS retencion'),
                DB::raw('CONCAT(oc.moneda, " $", FORMAT(total,2)) AS totalOrden'),
                DB::raw('CONCAT("MXN $", FORMAT(oc.totalMXN,2)) AS totalMXN'),
                'oc.estatus',
                DB::raw('CONCAT(oc.monedaComplementoPago, " $", FORMAT(oc.montoComplementoPago,2)) AS totalFinalPagado'),
            )
            ->leftJoin('proveedores as p', 'oc.idProveedor', '=', 'p.idProveedor')
            ->where('oc.codigoOrden', 'like', '%'.$request->codigoOrden.'%')
            ->where('p.razonSocialProv', 'like', '%'.$request->proveedor.'%')
            ->where('oc.nombreEmpresa', 'like', '%'.$request->empresa.'%')
            ->where('oc.estatus', 'like', '%'.$request->estatus.'%')
            ->where('oc.fechaOrden', '>=', $fechaInicioOrden)
            ->where('oc.fechaOrden', '<=', $fechaFinOrden)
            // ->where('oc.estatus','=','Si')
            ->where('oc.activo','=','Si')
            ->orderBy('oc.idOrden','DESC')
        ->get();

        $sumaTotales = $this->consultaSumaTotales($request->codigoOrden,$request->razonSocialProv,$request->nombreEmpresa,$request->estatus,$fechaInicioOrden,$fechaFinOrden,'Si');
        
        return $this->excelOrdenesCompra($registros, $sumaTotales);
    }

    public function excelOrdenesCompra($registros, $sumaTotales){
        return Excel::download( new OrdenesCompraExport($registros, $sumaTotales), 'reporte_oc.xlsx' );
    }

    public function consultaSumaTotales($codigoOrden,$proveedor,$empresa,$estatus,$fechaInicioOrden,$fechaFinOrden,$comparacion){
        $sumaTotales=DB::table('ordenCompra as oc')
            ->select(
                DB::raw('CONCAT("MXN", " $", FORMAT(SUM(importeMXN),2)) AS importe'),
                DB::raw('CONCAT(" $", FORMAT(SUM(ivaMXN),2)) AS iva'),
                DB::raw('CONCAT(" $", FORMAT(SUM(isrMXN),2)) AS isr'),
                DB::raw('CONCAT(" $", FORMAT(SUM(retencionMXN),2)) AS retencion'), 
                DB::raw('CONCAT("MXN", " $", FORMAT(SUM(totalMXN),2)) AS totalMXN')
            )
            ->leftJoin('proveedores as p', 'oc.idProveedor', '=', 'p.idProveedor')
            ->where('oc.codigoOrden', 'like', '%'.$codigoOrden.'%')
            ->where('p.razonSocialProv', 'like', '%'.$proveedor.'%')
            ->where('oc.nombreEmpresa', 'like', '%'.$empresa.'%')
            // ->where('oc.estatus', 'like', '%'.$estatus.'%')
            ->where('oc.fechaOrden', '>=', $fechaInicioOrden)
            ->where('oc.fechaOrden', '<=', $fechaFinOrden)
            ->where('oc.activo','=',$comparacion)
        ->get();

        $sumaTotales = $sumaTotales[0]->totalMXN;

        return $sumaTotales;
    }

    public function anexgrid_get_ProductosOC(){
        $anexGrid = new anexGrid();
        $order;
        $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'DESC';

        $folioOrden = '';
        $sku = '';
        $producto = '';
        $servicio = '';
        $fechaInicioOrden = '0000-00-00';
        $fechaFinOrden = '9999-12-31';
        $proveedor = '';
        $empresa = '';

        foreach($anexGrid->filtros as $f){
            if($f['columna'] == 'folioOrden' && $f['valor'] != ''){
                $folioOrden = $f['valor'];
            }
            if($f['columna'] == 'sku' && $f['valor'] != ''){
                $sku = $f['valor'];
            }
            if($f['columna'] == 'producto' && $f['valor'] != ''){
                $producto = $f['valor'];
            }
            if($f['columna'] == 'servicio' && $f['valor'] != ''){
                $servicio = $f['valor'];
            }
            if($f['columna'] == 'fechaInicioOrden' && $f['valor'] != ''){
                $fechaInicioOrden = $f['valor'];
            }
            if($f['columna'] == 'fechaFinOrden' && $f['valor'] != ''){
                $fechaFinOrden = $f['valor'];
            }
            if($f['columna'] == 'proveedor' && $f['valor'] != ''){
                $proveedor = $f['valor'];
            }
            if($f['columna'] == 'empresa' && $f['valor'] != ''){
                $empresa = $f['valor'];
            }                
        }

        $registros= DB::table('productosOrdenCompra as poc')
            ->select(
                DB::raw('(oc.codigoOrden) AS folioOrden'),
                DB::raw('(poc.codigo) AS sku'),
                DB::raw('(poc.nombreRefaccion) AS producto'),
                DB::raw('(poc.cantidadOrden) AS cantidad'),
                DB::raw('IFNULL(f.idServicios,"-") AS servicio'),
                'oc.notasOrden',
                DB::raw('DATE_FORMAT(oc.fechaOrden,"%d/%m/%Y") AS fecha_formato'),
                DB::raw('(oc.fechaOrden) AS fechaInicioOrden'),
                DB::raw('(oc.fechaOrden) AS fechaFinOrden'),
                DB::raw('(p.razonSocialProv) AS proveedor'),
                DB::raw('(oc.nombreEmpresa) AS empresa')
            )
            ->leftJoin('ordenCompra as oc', 'poc.idOrden', '=', 'oc.idOrden')
            ->leftJoin('proveedores as p', 'oc.idProveedor', '=', 'p.idProveedor')
            ->leftJoin('facturas as f', 'poc.idFactura', '=', 'f.idFactura')
            ->where('oc.codigoOrden', 'like', '%'.$folioOrden.'%')            
            ->where('poc.codigo', 'like', '%'.$sku.'%')
            ->where('poc.nombreRefaccion', 'like', '%'.$producto.'%')
            // Esta validacion es poque el campo idServicios contienen nulos
            ->where(function($query) {
                $anexGrid = new anexGrid();
                $servicio='';
                
                foreach($anexGrid->filtros as $f){
                    if($f['columna'] == 'servicio' && $f['valor'] != ''){
                        $servicio = $f['valor'];
                    }
                }
                
                if($servicio == '-' || $servicio ==''){
                    $query->where('f.idServicios', 'like', '%'.$servicio.'%')
                    ->orWhereNull('f.idServicios');
                }else{
                    $query->where('f.idServicios', 'like', '%'.$servicio.'%');
                }
            })
            ->where('p.razonSocialProv', 'like', '%'.$proveedor.'%')
            ->where('oc.nombreEmpresa', 'like', '%'.$empresa.'%')
            ->where('oc.fechaOrden', '>=', $fechaInicioOrden)
            ->where('oc.fechaOrden', '<=', $fechaFinOrden)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
        ->get();

        $total= DB::table('productosOrdenCompra as poc')
            ->leftJoin('ordenCompra as oc', 'poc.idOrden', '=', 'oc.idOrden')
            ->leftJoin('proveedores as p', 'oc.idProveedor', '=', 'p.idProveedor')
            ->leftJoin('facturas as f', 'poc.idFactura', '=', 'f.idFactura')
            ->where('oc.codigoOrden', 'like', '%'.$folioOrden.'%')
            ->where('poc.codigo', 'like', '%'.$sku.'%')
            ->where('poc.nombreRefaccion', 'like', '%'.$producto.'%')
            // Esta validacion es poque el campo idServicios contienen nulos
            ->where(function($query) {
                $anexGrid = new anexGrid();
                $servicio='';
                
                foreach($anexGrid->filtros as $f){
                    if($f['columna'] == 'servicio' && $f['valor'] != ''){
                        $servicio = $f['valor'];
                    }
                }
                
                if($servicio == '-' || $servicio ==''){
                    $query->where('f.idServicios', 'like', '%'.$servicio.'%')
                    ->orWhereNull('f.idServicios');
                }else{
                    $query->where('f.idServicios', 'like', '%'.$servicio.'%');
                }
            })
            ->where('p.razonSocialProv', 'like', '%'.$proveedor.'%')
            ->where('oc.nombreEmpresa', 'like', '%'.$empresa.'%')
            ->where('oc.fechaOrden', '>=', $fechaInicioOrden)
            ->where('oc.fechaOrden', '<=', $fechaFinOrden)
        ->count();
        
        $data = array(
            'total' => $total,
            'data' => $registros
        );

        return response()->json($data, 200);
    }

    public function reporteProductosEnNotaEntrada(){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else{

            // Si el usaurio es de compras solo mostrará las ordenes que esten en español(Nacionales)
            // if ($stipo == 'Compras') {
            //     $idioma = 'Español';
            // }else{
            //     $idioma = 's';
            // }

            return view('reporteProductosEnNotaEntrada');
            
        }
    }

    public function validar_oc_mov_internos(){
        $consulta = \DB::select("SELECT idOrden from ordenCompra WHERE activo='si' AND oc_mov_internos=1");
        return $consulta;
    }

    // public function reporteOrdenesCompra2(){        
    //     return view('reporteOrdenesCompra2');
    // }
    public function tablaOrdenesActivas(){    
        $consulta = \DB::SELECT('SELECT idorden, oc.activo, codigoorden, fechaorden, p.razonSocialProv, nombreempresa, moneda,
        IF(moneda = "MXN", CONCAT("MXN $ ", FORMAT(importeorden, 2)), CONCAT("USD $ ", FORMAT(importeorden, 2))) AS importeorden,
        CONCAT("$ ", FORMAT(ivacompra, 2)) AS ivacompra,
        CONCAT("$ ", isrcompra) AS isrcompra,
        CONCAT("$ ", retencioncompra) AS retencioncompra,
        IF(moneda = "MXN", CONCAT("MXN $ ", FORMAT(total, 2)), CONCAT("USD $ ", FORMAT(total, 2))) AS total,
        CONCAT("MXN $ ", FORMAT(totalMXN, 2)) AS totalMXN, statusordenesdecompra(idorden) AS estatus,
        IF(montofinalpagadoOC(idorden) IS NULL, "MXN $ 0.00", montofinalpagadoOC(idorden)) AS PagoFinal,
        IF(cuantosprodOC(idorden) >= 1, 1, 0) AS iconoPDF
        FROM ordenCompra AS oc
        INNER JOIN proveedores AS p ON p.idProveedor = oc.idProveedor
        WHERE oc.activo = "Si" AND codigoorden NOT IN("CYM-000001", "POJ-000001", "YAG-000001", "GCM-000001", "SUR-000002", "CMUSA-000002")
        ORDER BY oc.fechaorden DESC');
        $cuantos = count($consulta);  
        $totalMXN = \DB::SELECT("SELECT CONCAT(SUM(totalMXN)) AS sumaTotalMXN
        FROM ordenCompra AS oc
        INNER JOIN proveedores AS p ON p.idProveedor = oc.idProveedor
        WHERE oc.activo='Si' AND codigoorden NOT IN('CYM-000001','POJ-000001','YAG-000001','GCM-000001','SUR-000002','CMUSA-000002')");  
        return view('tablaOrdenesCompraActivas')
        ->with("cuantos", $cuantos)
        ->with("totalMXN", $totalMXN)
        ->with("consulta", $consulta);
    }
    public function tablaOrdenesCanceladas(){   
        $consulta = \DB::SELECT('SELECT idorden, oc.activo, codigoorden, fechaorden, p.razonSocialProv, nombreempresa, moneda,
        IF(moneda = "MXN", CONCAT("MXN $ ", FORMAT(importeorden, 2)), CONCAT("USD $ ", FORMAT(importeorden, 2))) AS importeorden,
        CONCAT("$ ", FORMAT(ivacompra, 2)) AS ivacompra,
        CONCAT("$ ", isrcompra) AS isrcompra,
        CONCAT("$ ", retencioncompra) AS retencioncompra,
        IF(moneda = "MXN", CONCAT("MXN $ ", FORMAT(total, 2)), CONCAT("USD $ ", FORMAT(total, 2))) AS total,
        CONCAT("MXN $ ", FORMAT(totalMXN, 2)) AS totalMXN, statusordenesdecompra(idorden) AS estatus,
        IF(montofinalpagadoOC(idorden) IS NULL, "MXN $ 0.00", montofinalpagadoOC(idorden)) AS PagoFinal,
        IF(cuantosprodOC(idorden) >= 1, 1, 0) AS iconoPDF
        FROM ordenCompra AS oc
        INNER JOIN proveedores AS p ON p.idProveedor = oc.idProveedor
        WHERE oc.activo = "No" AND codigoorden NOT IN("CYM-000001", "POJ-000001", "YAG-000001", "GCM-000001", "SUR-000002", "CMUSA-000002")
        ORDER BY oc.fechaorden DESC');
  
        $cuantos = count($consulta);    
        $totalMXN = \DB::SELECT("SELECT CONCAT(SUM(totalMXN)) AS sumaTotalMXN
        FROM ordenCompra AS oc
        INNER JOIN proveedores AS p ON p.idProveedor = oc.idProveedor
        WHERE oc.activo='No' AND codigoorden NOT IN('CYM-000001','POJ-000001','YAG-000001','GCM-000001','SUR-000002','CMUSA-000002')");  
        return view('tablaOrdenesCompraCanceladas')
        ->with("cuantos", $cuantos)
        ->with("totalMXN", $totalMXN)
        ->with("consulta", $consulta);
    }
    public function editarProductoOrden(Request $request)
    {
        $idProducto = $request->idProducto;
        $idOrden = $request->idOrden;
        $idPartesVenta = $request->idPartes;
        $idFactura = $request->idFacturaTabla;
        
        if($idPartesVenta){
        $consulta = productosOrdenCompra::where('idProducto', '=', $idProducto)
            ->join("partesVenta", "productosOrdenCompra.idPartesVenta", "=", "partesVenta.idPartesVenta")
            ->select(
                "productosOrdenCompra.idProducto",
                "productosOrdenCompra.idOrden",
                "productosOrdenCompra.idPartesVenta",
                "productosOrdenCompra.nombreRefaccion",
                "productosOrdenCompra.codigo",
                DB::raw('ROUND(productosOrdenCompra.precioOrden, 2) as precioOrden'),
                "productosOrdenCompra.cantidadOrden",
                DB::raw('ROUND(productosOrdenCompra.precioProducto, 2) as precioProducto'),
                "productosOrdenCompra.ivaOrden",
                "productosOrdenCompra.retencion",
                "productosOrdenCompra.isrOrden",
                "productosOrdenCompra.idFactura",   
                "productosOrdenCompra.vendorPartNumber",   
                "productosOrdenCompra.notasProducto",   
                "partesVenta.nombreRefaccion as descripcion",             
                "partesVenta.codigo as sku",             
            )            
            ->first(); 
        }else{
            $consulta = productosOrdenCompra::where('idProducto', '=', $idProducto)            
            ->select(
                "productosOrdenCompra.idProducto",
                "productosOrdenCompra.idOrden",
                "productosOrdenCompra.idPartesVenta",
                "productosOrdenCompra.nombreRefaccion",
                "productosOrdenCompra.codigo",
                DB::raw('ROUND(productosOrdenCompra.precioOrden, 2) as precioOrden'),
                "productosOrdenCompra.cantidadOrden",
                DB::raw('ROUND(productosOrdenCompra.precioProducto, 2) as precioProducto'),
                "productosOrdenCompra.ivaOrden",
                "productosOrdenCompra.retencion",
                "productosOrdenCompra.isrOrden",
                "productosOrdenCompra.idFactura",   
                "productosOrdenCompra.vendorPartNumber",   
                "productosOrdenCompra.notasProducto",                   
            )            
            ->first(); 
        }

        // dd($consulta);

        if($idPartesVenta){
            if($idFactura){
                $opcionCombo=\DB::select("SELECT f.idFactura, CONCAT(f.idServicios,'-',c.razonSocial,'-',s.sucursal) AS paracombo
                FROM productosOrdenCompra AS poc
                INNER JOIN facturas AS f ON f.idFactura = poc.idFactura
                INNER JOIN clientes AS c ON c.idc =f.idc 
                INNER JOIN sucursales AS s ON s.idSucursal = f.idSucursal
                WHERE poc.idPartesVenta = $idPartesVenta AND poc.idOrden = 168 AND poc.idFactura = $idFactura");
        }
        
        $consultaOpcionesCombo=\DB::select("SELECT f.idFactura, CONCAT(f.idServicios,'-',c.razonSocial,'-',s.sucursal) AS paracombo
        FROM facturas AS f
        INNER JOIN clientes AS c ON c.idc =f.idc
        INNER JOIN sucursales AS s ON s.idSucursal = f.idSucursal
        ORDER BY f.idfactura DESC"); 
        }
            
            // dd($consulta);
            // dd($opcionCombo);

            if($idPartesVenta){
                return view('editarProductoOrden')
                ->with('idProducto', $idProducto)                                 
                ->with('idOrden', $idOrden)                                 
                ->with('idFactura', $idFactura)                                 
                ->with('idPartesVenta', $idPartesVenta)
                ->with('opcionCombo', $idFactura ? $opcionCombo : null)                               
                ->with('consultaOpcionesCombo', $consultaOpcionesCombo)                                 
                ->with('consulta', $consulta);                              
            }else{
                return view('editarProductoOrdenEquipos')
                ->with('idProducto', $idProducto)                                 
                ->with('idOrden', $idOrden)                                 
                ->with('idFactura', $idFactura)                                 
                ->with('idPartesVenta', $idPartesVenta)
                ->with('consulta', $consulta);                              
            }
    }

    public function guardarModifProductoOrden(Request $request){  
        $idOrden= $request->idOrdenM;      
        $precioOrden = $request->precioOrdenM;
        $cantidadOrden = $request->cantidadOrdenM;
        $subtotal = $precioOrden * $cantidadOrden;    

        $ivaOrden=$request->ivaOrdenM;
        $ivaDinero=($ivaOrden/100)*$subtotal;

        $isrOrden=$request->isrOrdenM;
        $isrDinero=($isrOrden/100)*$subtotal;
                
        $retencion=$request->retencionM;
        $retencionDinero=($retencion/100)*($subtotal + $isrDinero + $ivaDinero);

        $moneda=$request->monedaOrden;
        $cambioDolar=$request->cambioDolar; 
        
        $productosOrdenCompra = productosOrdenCompra::find($request->idProductoM);
                
        $productosOrdenCompra->idOrden = $request->idOrdenM;
        $productosOrdenCompra->tipoServicio = $request->tipoEntradaM;
        $productosOrdenCompra->idPartesVenta = $request->idPartesVentaM;
        $productosOrdenCompra->idFactura = $request->facturaValue;
        $productosOrdenCompra->precioOrden = $request->precioOrdenM;
        $productosOrdenCompra->cantidadOrden = $request->cantidadOrdenM;
        $productosOrdenCompra->subtotal = $subtotal;
        $productosOrdenCompra->ivaOrden = $request->ivaOrdenM;
        $productosOrdenCompra->ivaDinero = $ivaDinero;
        $productosOrdenCompra->isrOrden = $request->isrOrdenM;
        $productosOrdenCompra->isrDinero = $isrDinero;
        $productosOrdenCompra->retencion = $request->retencionM;
        $productosOrdenCompra->retencionDinero = $retencionDinero;
        $productosOrdenCompra->precioProducto = $request->totalCalculoM;
        $productosOrdenCompra->notasProducto = $request->notasProductoM;
        $productosOrdenCompra->vendorPartNumber = $request->vendorPartNumberM;
        $productosOrdenCompra->save();
        // $orden->idFactura = $idFactura;

        // obtenemos los nuevos totales de una consulta en donde se suman los dineros de todo lo que resulte depues de eliminar un producto
        $nuevosTotalesOrden=\DB::select("SELECT SUM(subtotal) AS importeOrden, SUM(ivaDinero) AS ivaCompra, SUM(isrDinero) AS isrCompra, SUM(retencionDinero) AS retencionCompra, SUM(precioProducto) AS total FROM productosOrdenCompra WHERE idOrden=$idOrden");
        // obteniendo los totales de la consulta para pasarlos al ubdate y actualizar el total de toda la orden
        $importeOrden=$nuevosTotalesOrden[0]->importeOrden;
        $ivaCompra=$nuevosTotalesOrden[0]->ivaCompra;
        $isrCompra=$nuevosTotalesOrden[0]->isrCompra;
        $retencionCompra=$nuevosTotalesOrden[0]->retencionCompra;
        $total=$nuevosTotalesOrden[0]->total;
        
        // /obteniendo totales para el tipo de moneda
        if($moneda=="USD"){
            if($cambioDolar < 1){
                $cambioDolar = 1;
            }
            $importeMXN=$importeOrden*$cambioDolar;
            $totalMXN=$total*$cambioDolar;
            $ivaMXN=$ivaCompra*$cambioDolar;
            $isrMXN=$isrCompra*$cambioDolar;
            $retencionMXN=$retencionCompra*$cambioDolar;
        }
        else{
            $importeMXN=$importeOrden;
            $totalMXN=$total;
            $ivaMXN=$ivaCompra;
            $isrMXN=$isrCompra;
            $retencionMXN=$retencionCompra;
        }

        // actualizando el total de la orden de compra cada que se agrega un nuevo producto
        $actualizarTotalOrden=
        \DB::select("UPDATE ordenCompra SET total=?, importeOrden=?, ivaCompra=?, isrCompra=?, retencionCompra=?,importeMXN=?, totalMXN=?, ivaMXN=?, isrMXN=?, retencionMXN=?
        WHERE idOrden = ?",[$total, $importeOrden, $ivaCompra, $isrCompra,$retencionCompra,$importeMXN, $totalMXN, $ivaMXN, $isrMXN, $retencionMXN, $idOrden]);

        /////CONSULTAS PARA EL REPORTE////
        $ordenCompra=\DB::select("SELECT po.idProducto, po.idOrden, po.idPartesVenta,po.tipoServicio,
        po.nombreRefaccion, po.notasProducto,po.vendorPartNumber, po.codigo, po.cantidadOrden, po.idFactura, oc.cambioDolar,f.idServicios,
        FORMAT(po.subtotal,2) as subtotal, po.ivaOrden, FORMAT(po.ivaDinero,2) ivaDinero, po.isrOrden,
        FORMAT(po.isrDinero,2) as isrDinero, FORMAT(po.precioProducto,2) as precioProducto,
        FORMAT(retencionDinero,2) as retencionDinero,retencion,
        FORMAT(po.precioOrden,2) as precioOrden,po.moneda, po.idRefaccionCotizacion, po.idRepcotizacion
        FROM productosOrdenCompra as po
        inner join ordenCompra as oc on oc.idOrden=po.idOrden
        left join facturas as f on f.idFactura=po.idFactura
        WHERE po.idOrden=$idOrden");
        $cuantosProductos=count($ordenCompra);
        
        $sumaTotal=\DB::select("SELECT oc.moneda, oc.idFactura, FORMAT(SUM(precioProducto),2) AS total,
        FORMAT(SUM(isrDinero),2) as isrDineroTotal,
        FORMAT(SUM(ivaDinero),2) as ivaDineroTotal,
        FORMAT(SUM(retencionDinero),2) as retencionDineroTotal,
        FORMAT(SUM(subtotal),2) as importe
        FROM productosOrdenCompra AS pc
        INNER JOIN ordenCompra as oc ON pc.idOrden=oc.idOrden
        WHERE pc.idOrden= $idOrden");
        $consulta = \DB::select("SELECT * from ordenCompra WHERE idOrden=$idOrden");
                /////TERMINAN CONSULTAS PARA EL REPORTE////

        return view('reporteOrdenAgregada')
        ->with('sumaTotal',$sumaTotal[0])
        ->with('consulta',$consulta[0])
        ->with('ordenCompra',$ordenCompra);            
    }
}
