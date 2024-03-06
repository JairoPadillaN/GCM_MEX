<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use App\Exports\OCFacturasExport;
use App\viajes;
use App\vehiculos;
use App\clientes;
use App\usuarios;
use App\facturas;
use App\sucursales;
use App\bancos;
use App\dataModel;
use App\cotizaciones;
use App\asignacionesdetalles;
use App\cotizacionServicios;
use App\ordenCompra;
use App\datosPagoServicios;
use App\Exports\UtilidadServicioExport;
use App\Exports\GastoServicioExport;
use DB;
use App\anexGrid;
use App\gastos;
use App\archivosFacturas;
use App\vendedoresAsignadosServicios;

use Session;

//GuardarFacturas
class facturasController extends Controller
{
 
       public function altaFacturas(){
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
              $bancos = bancos::orderby('nombre','asc')
              ->get();
              
              $cliente = clientes::orderby('razonSocial','asc')
              ->get();

              $idServiciosig = facturas::withTrashed()
              ->orderby('idServicios','desc')
              ->take(1)
              ->get();
              $idServ = $idServiciosig[0]->idServicios+1;

              $idFacturasig = facturas::withTrashed()
              ->orderby('idFactura','desc')
              ->take(1)
              ->get();
              $idFact = $idFacturasig[0]->idFactura+1;

            $consultaCotizaciones = cotizaciones::orderby('numeroCotizacion','asc')
            ->WHERE('estatus','=','Aceptada con orden de compra')  
            ->get();

            
              


                return view ('altaFacturas')
                ->with('bancos',$bancos)
                ->with(['idServ'=>$idServ])
                ->with(['idFact'=>$idFact])
                ->with('cliente',$cliente)
                ->with('consultaCotizaciones',$consultaCotizaciones)
                ->with('stipo',$stipo);

            }
        }

        function comboca1(Request $request)
        {
            $idc = $request->get('idc');
            $consulta = sucursales::where('idc','=',$idc)->get();
    
            return view ('com')
            ->with('consulta',$consulta);
        }

        function comboCotizaciones(Request $request)
        {
          $idSucursal = $request->get('idp');

          $consultaCotizaciones =\DB::select("SELECT idCotizacion, numeroCotizacion, idSucursal 
                                              FROM cotizaciones 
                                              WHERE estatus = 'Aceptada con orden de compra' 
                                              AND idSucursal = ? AND idCotizacion 
                                              NOT IN (SELECT idCotizacion FROM facturas WHERE idCotizacion != 'NULL')",[$idSucursal]);

          
          
         
          // return $consultaCotizaciones;
          return view ('comboDeCotizaciones')
            ->with('consultaCotizaciones',$consultaCotizaciones);
        }

        public function GuardarFacturas(Request $request){
            $idFactura = $request -> idFactura;  
            $idServicios = $request -> idServicios;
            $tipoServicio = $request -> tipoServicio;
            $nombreEmpresa = $request -> nombreEmpresa;
            $idSucursal = $request -> idSucursal;
            $idCotizacion = $request -> idCotizacion;

            $numeroFactura = $request -> numeroFactura;
            $fechaVencimiento = $request -> fechaVencimiento;
            $idb = $request -> idb;
            $montoFactura = $request -> montoFactura;
            $periodoPago = $request -> periodoPago;
            $fechaFactura = $request -> fechaFactura;
            $fechaPago = $request -> fechaPago;
            $tipoMoneda = $request -> tipoMoneda;
            $idc = $request -> idc;
            $cambioFactura = $request -> cambioFactura;
            $montoPesos = $request -> montoPesos;
            $saldo = $request -> saldo;
            $cambioReal = $request -> cambioReal;
            $observacionesFactura = $request -> observacionesFactura;
            $ordenCompra = $request -> ordenCompra;
            $factura = $request -> factura;
            $comprobantePago = $request -> comprobantePago;
            $archivo = $request -> archivo;
            $archivoFactura = $request -> archivoFactura;
            $archivoPago = $request -> archivoPago;
            $xmlFactura = $request -> xmlFactura;
//LOS NUEVOS CAMPOS AGREGADOS 16 ABRIL 2020
            $metodoPago = $request -> metodoPago;
            $estatusEntrega = $request -> estatusEntrega;
            $estatusPortal = $request -> estatusPortal;
            $estatusPago = $request -> estatusPago;
            $cashFlow = $request -> cashFlow;
            $complementoPago = $request -> complementoPago;
            $numeroRemision = $request -> numeroRemision;
            $numeroEntrada = $request -> numeroEntrada;
            $archivoRemision = $request -> archivoRemision;
            $numeroDocumento = $request -> numeroDocumento;
            $archivoOtro = $request -> archivoOtro;
            $numeroAnexo1 = $request -> numeroAnexo1;
            $anexo1 = $request -> anexo1;
            $numeroAnexo2 = $request -> numeroAnexo2;
            $anexo2 = $request -> anexo2;
            $numeroAnexo3 = $request -> numeroAnexo3;
            $anexo3 = $request -> anexo3;
            $numeroAnexo4 = $request -> numeroAnexo4;
            $anexo4 = $request -> anexo4;
            $numeroAnexo5 = $request -> numeroAnexo5;
            $anexo5 = $request -> anexo5;
            $numeroAnexo6 = $request -> numeroAnexo6;
            $anexo6 = $request -> anexo6;
            $numeroAnexo7 = $request -> numeroAnexo7;
            $anexo7 = $request -> anexo7;
            $numeroAnexo5 = $request -> numeroAnexo5;
            $anexo5 = $request -> anexo5;
            $numeroAnexo6 = $request -> numeroAnexo6;
            $anexo6 = $request -> anexo6;
            $numeroAnexo7 = $request -> numeroAnexo7;
            $anexo7 = $request -> anexo7;
            $numeroAnexo8 = $request -> numeroAnexo8;
            $anexo8 = $request -> anexo8;
            $numeroAnexo9 = $request -> numeroAnexo9;
            $anexo9 = $request -> anexo9;
            $numeroAnexo10 = $request -> numeroAnexo10;
            $anexo10 = $request -> anexo10;






            $this->validate ($request,[
           // 'numeroFactura'=>['regex:/^[0-9]*$/'],
            // 'fechaFactura'=>['required'],
            // 'fechaPago'=>['required'],
           
            // 'idb'=>['required'],
            'montoFactura'=>['required'],
         
            'periodoPago'=>['required'],
            'idc'=>['required'],
            'idSucursal'=>['required'],
            'estatusEntrega' =>['required'],
            'estatusPortal' =>['required'],
            'estatusPago' =>['required'],
            'cashFlow' =>['required'],
            'saldo'=>['required'],
            'periodoPago'=>['required'],
            //VALIDACION DE LOS ARCHVOS
            // 'archivoFactura'=>['mimes:doc,docx,pdf'],
            /*'xmlFactura'=>['mimes:xml,XML'],*/]);


//Archivo orden de compra

             $file = $request->file('archivo');
        //obtenemos el nombre del archivo
             if($file!=""){
        $archivo =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($archivo,  \File::get($file));
            }
            else{
              $archivo ="Sin archivo";
            }

 //Archivo factura

        $file = $request->file('archivoFactura');
        //obtenemos el nombre del archivo
         if($file!=""){
        $archivoFactura =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($archivoFactura,  \File::get($file));
           }
            else{
              $archivoFactura ="Sin archivo";
            }

//Archivo Comprobate de pago
        $file = $request->file('archivoPago');
        //obtenemos el nombre del archivo
        if($file!=""){
        $archivoPago =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($archivoPago,  \File::get($file));
        }
            else{
              $archivoPago ="Sin archivo";
            }


//Archivo XML de factura 
          $file = $request->file('xmlFactura');
        //obtenemos el nombre del archivo
          if($file!=""){
        $xmlFactura =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($xmlFactura,  \File::get($file));
         }
            else{
              $xmlFactura ="Sin archivo";
            }
//Archivo remision 
          $file = $request->file('archivoRemision');
        //obtenemos el nombre del archivo
          if($file!=""){
        $archivoRemision =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($archivoRemision,  \File::get($file));
         }
            else{
              $archivoRemision ="Sin archivo";
            }

//Archivo otros 
          $file = $request->file('archivoOtro');
        //obtenemos el nombre del archivo
          if($file!=""){
        $archivoOtro =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($archivoOtro,  \File::get($file));
         }
            else{
              $archivoOtro ="Sin archivo";
            }            
//Archivo ADENDA 
          $file = $request->file('archivoAdenda');
        //obtenemos el nombre del archivo
          if($file!=""){
        $archivoAdenda =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($archivoAdenda,  \File::get($file));
         }
            else{
              $archivoAdenda ="Sin archivo";
            }  
            
//Archivo anexo1  
          $file = $request->file('anexo1');
        //obtenemos el nombre del archivo
          if($file!=""){
        $anexo1 =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($anexo1,  \File::get($file));
         }
            else{
              $anexo1 ="Sin archivo";
            }  

//Archivo anexo2
          $file = $request->file('anexo2');
          //obtenemos el nombre del archivo
            if($file!=""){
          $anexo2 =  time()."_".$file->getClientOriginalName();
          //indicamos que queremos guardar un nuevo archivo en el disco local
          \Storage::disk('local')->put($anexo2,  \File::get($file));
           }
              else{
                $anexo2 ="Sin archivo";
              }
//Archivo anexo3 
          $file = $request->file('anexo3');
        //obtenemos el nombre del archivo
          if($file!=""){
        $anexo3 =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($anexo3,  \File::get($file));
         }
            else{
              $anexo3 ="Sin archivo";
            }

//Archivo anexo4  
          $file = $request->file('anexo4');
        //obtenemos el nombre del archivo
          if($file!=""){
        $anexo4 =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($anexo4,  \File::get($file));
         }
            else{
              $anexo4 ="Sin archivo";
            }

//Archivo anexo5  
          $file = $request->file('anexo5');
        //obtenemos el nombre del archivo
          if($file!=""){
        $anexo5 =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($anexo5,  \File::get($file));
         }
            else{
              $anexo5 ="Sin archivo";
            }
//Archivo anexo6  
          $file = $request->file('anexo6');
        //obtenemos el nombre del archivo
          if($file!=""){
        $anexo6 =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($anexo6,  \File::get($file));
         }
            else{
              $anexo6 ="Sin archivo";
            }
//Archivo anexo7  
          $file = $request->file('anexo7');
        //obtenemos el nombre del archivo
          if($file!=""){
        $anexo7 =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($anexo7,  \File::get($file));
         }
            else{
              $anexo7 ="Sin archivo";
            }
//Archivo anexo8  
          $file = $request->file('anexo8');
        //obtenemos el nombre del archivo
          if($file!=""){
        $anexo8 =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($anexo8,  \File::get($file));
         }
            else{
              $anexo8 ="Sin archivo";
            }
//Archivo anexo9  
          $file = $request->file('anexo9');
        //obtenemos el nombre del archivo
          if($file!=""){
        $anexo9 =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($anexo9,  \File::get($file));
         }
            else{
              $anexo9 ="Sin archivo";
            }   
//Archivo anexo10  
          $file = $request->file('anexo10');
        //obtenemos el nombre del archivo
          if($file!=""){
        $anexo10 =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($anexo10,  \File::get($file));
         }
            else{
              $anexo10 ="Sin archivo";
            }            
            
            $fac=new facturas;
            $fac-> idServicios = $request-> idServicios;
            $fac-> idSucursal = $request-> idSucursal;
            $fac-> idCotizacion = $request-> idCotizacion;
            $fac-> nombreEmpresa = $request-> nombreEmpresa;
            $fac-> tipoServicio = $request-> tipoServicio;

            $fac-> numeroFactura = $request-> numeroFactura;
            $fac-> fechaVencimiento = $request-> fechaVencimiento;
            $fac-> idb = $request-> idb;
            $fac-> montoFactura = $request-> montoFactura;
            $fac-> ivaFactura = $request-> ivaFactura;
            $fac-> retencionIva = $request-> retencionIva;
            $fac-> totalConIva = $request-> totalConIva;
            $fac-> periodoPago = $request-> periodoPago;
            $fac-> fechaFactura = $request-> fechaFactura;
            $fac-> fechaPago = $request-> fechaPago;
            $fac-> tipoMoneda = $request-> tipoMoneda;
            $fac-> idc = $request-> idc;
            $fac-> cambioFactura = $request-> cambioFactura;
            $fac-> montoPesos = $request-> montoPesos;
            $fac-> saldo = $request-> saldo;
            $fac-> cambioReal = $request-> cambioReal;
            $fac-> observacionesFactura = $request-> observacionesFactura;
            $fac-> ordenCompra = $request-> ordenCompra;
            $fac-> factura = $request-> factura;
            $fac-> comprobantePago = $request-> comprobantePago;
           // $fac-> xmlFactura = $request-> xmlFactura;
          ////  
            $fac-> metodoPago = $request-> metodoPago;
            $fac-> estatusEntrega = $request-> estatusEntrega;
            $fac-> estatusPortal = $request-> estatusPortal;
            $fac-> estatusPago = $request-> estatusPago;
            $fac-> cashFlow = $request-> cashFlow;
            $fac-> complementoPago = $request-> complementoPago;
            $fac-> numeroRemision = $request-> numeroRemision;
            $fac-> numeroEntrada = $request-> numeroEntrada;
            $fac-> numeroDocumento = $request-> numeroDocumento;
            $fac-> adenda = $request-> adenda;
            $fac-> numeroAnexo1 = $request-> numeroAnexo1;
            $fac-> numeroAnexo2 = $request-> numeroAnexo2; 
            $fac-> numeroAnexo3 = $request-> numeroAnexo3;
            $fac-> numeroAnexo4 = $request-> numeroAnexo4;
            $fac-> numeroAnexo5 = $request-> numeroAnexo5;
            $fac-> numeroAnexo6 = $request-> numeroAnexo6;
            $fac-> numeroAnexo7 = $request-> numeroAnexo7;
            $fac-> numeroAnexo8 = $request-> numeroAnexo8;
            $fac-> numeroAnexo9 = $request-> numeroAnexo9;
            $fac-> numeroAnexo10 = $request-> numeroAnexo10;

            //Subir Archivos
            $fac-> archivo = $archivo;
            $fac-> archivoFactura = $archivoFactura;
            $fac-> archivoPago = $archivoPago;
            $fac-> xmlFactura = $xmlFactura;
            $fac-> archivoRemision = $archivoRemision;
            $fac-> archivoOtro = $archivoOtro;
            $fac-> archivoAdenda = $archivoAdenda;
            $fac-> anexo1 = $anexo1;
            $fac-> anexo2 = $anexo2;
            $fac-> anexo3 = $anexo3;
            $fac-> anexo4 = $anexo4;
            $fac-> anexo5 = $anexo5;
            $fac-> anexo6 = $anexo6;
            $fac-> anexo7 = $anexo7;
            $fac-> anexo8 = $anexo8;
            $fac-> anexo9 = $anexo9;
            $fac-> anexo10 = $anexo10;

          
            $fac-> save();

            $proceso='Registro exitoso de factura';
            $mensaje="Factura registrada correctamente";
            return view ('mensajeFactura')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);
        }

         public function modificarFacturas($idFactura){
            $sname = Session::get('sesionname');
            $sidu = Session::get('sesionidu');
            $spat = Session::get('sesionpaterno');
            $smat = Session::get('sesionmaterno');
            $stipo = Session::get('sesiontipo');
            $gastos = DB::table('gastos')
              ->orderBy('fecha_pago', 'asc')
              ->where('id_factura', $idFactura)
              ->get();
            $contadorGastos = 0;
            foreach($gastos as $g){
              $contadorGastos += 1;
            }
            if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
                Session::flash('error', 'Es necesario logearse antes de continuar');
                return redirect()->route('login');
            }
            else{
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
              INNER JOIN equiposEnActividad AS ea ON ea.idSegActividad=sa.idSegActividad
              INNER JOIN facturas AS f ON f.idFactura=ea.idFactura
              WHERE f.idFactura=$idFactura ORDER BY idSegActividad DESC");
        

              $consulta = facturas::Where('idFactura','=',$idFactura)->get();
              $consultaArchivosFac = archivosFacturas::where('idFactura', '=', $idFactura)->get();
              $cuantosAF = count($consultaArchivosFac);
              
              /////ORDEN DE COMPRA////

              $ordenCompra=\DB::select(" SELECT idOrden,idFactura,codigoOrden, DATE_FORMAT(fechaOrden,'%d %b %Y') as fechaOrden,
              p.razonSocialProv,nombreEmpresa, sucursal, nombreCuenta, numeroCu, moneda, FORMAT(importeOrden,2) as importeOrden,
              FORMAT(ivaCompra,2) as ivaCompra, FORMAT(isrCompra,2) as isrCompra, FORMAT(total,2) as total,FORMAT(totalMXN,2) as totalMXN, estatus, oc.activo,
              oc.xmlFacturaProveedor, oc.pdfFacturaProveedor, DATE_FORMAT(fechaProgPago, '%d %b %Y') as fechaProgPago
              FROM ordenCompra AS oc
              INNER JOIN proveedores AS p ON p.idProveedor=oc.idProveedor
              INNER JOIN sucursales AS s ON s.idSucursal=oc.idSucursal
              INNER JOIN cuentas AS c ON c.idCuenta=oc.idCuenta
              WHERE idFactura=$idFactura");              

              // $productosEnOrdenCompra=\DB::select("SELECT poc.idOrden,oc.codigoOrden,oc.nombreEmpresa,oc.fechaOrden,oc.codigoOrden AS folio,oc.fechaOrden,poc.cantidadOrden AS cantidadProducto,
              // p.razonSocialProv AS proveedor,oc.moneda,oc.estatus,oc.fechaProgPago,
              // CONCAT(c.numeroCu,' - ',c.nombrecuenta) AS cuenta,oc.estatus,oc.fechaProgPago,
              // CONCAT(poc.codigo,' / ',poc.nombreRefaccion) AS sku, poc.precioOrden AS importe, FORMAT(poc.ivaDinero,2) AS iva,
              // FORMAT(poc.isrDinero,2) AS isr,
              // FORMAT(((poc.subtotal + poc.ivaDinero + poc.isrDinero) - poc.precioProducto),2) AS retencion,
              // FORMAT((poc.subtotal + poc.ivaDinero + poc.isrDinero),2) AS subtotal,
              // FORMAT(poc.precioProducto,2) AS total
              // FROM productosOrdenCompra  AS poc
              // LEFT JOIN ordenCompra AS oc ON oc.idOrden = poc.idOrden
              // LEFT JOIN proveedores AS p ON p.idProveedor=oc.idProveedor
              // LEFT JOIN cuentas AS c ON c.idcuenta = oc.idcuenta
              // WHERE poc.idFactura = $idFactura ORDER BY poc.idOrden DESC");

              $productosEnOrdenCompra = $asignadosEnAlmacen=\DB::select("SELECT poc.idOrden,oc.codigoOrden,oc.nombreEmpresa,oc.fechaOrden,oc.codigoOrden AS folio,oc.fechaOrden,pasign.cantidadAservicio as cantidadProducto,
              p.razonSocialProv AS proveedor,oc.moneda,oc.estatus,oc.fechaProgPago,
              CONCAT(c.numeroCu,' - ',c.nombrecuenta) AS cuenta,oc.estatus,oc.fechaProgPago,
              CONCAT(poc.codigo,' / ',poc.nombreRefaccion) AS sku, poc.precioOrden AS importe, 
              FORMAT(((poc.ivaDinero/poc.cantidadOrden)*pasign.cantidadAservicio),2) AS iva,
              FORMAT(((poc.isrDinero/poc.cantidadOrden)*pasign.cantidadAservicio),2) AS isr,
              FORMAT(((poc.retencionDinero/poc.cantidadOrden)*pasign.cantidadAservicio),2) AS retencion,              
              FORMAT((((poc.subtotal/poc.cantidadOrden)*pasign.cantidadAservicio) + ((poc.ivaDinero/poc.cantidadOrden)*pasign.cantidadAservicio) + ((poc.isrDinero/poc.cantidadOrden)*pasign.cantidadAservicio)),2) AS subtotal,
              FORMAT((((((poc.subtotal/poc.cantidadOrden)*pasign.cantidadAservicio) + ((poc.ivaDinero/poc.cantidadOrden)*pasign.cantidadAservicio) + ((poc.isrDinero/poc.cantidadOrden)*pasign.cantidadAservicio))-((poc.retencionDinero/poc.cantidadOrden)*pasign.cantidadAservicio))),2) AS total,
              FORMAT(((((((poc.subtotal/poc.cantidadOrden)*pasign.cantidadAservicio) + ((poc.ivaDinero/poc.cantidadOrden)*pasign.cantidadAservicio) + ((poc.isrDinero/poc.cantidadOrden)*pasign.cantidadAservicio))-((poc.retencionDinero/poc.cantidadOrden)*pasign.cantidadAservicio)))*oc.cambioDolar),2) AS totalMXN
              FROM productosOrdenCompra  AS poc
              LEFT JOIN ordenCompra AS oc ON oc.idOrden = poc.idOrden
              LEFT JOIN proveedores AS p ON p.idProveedor=oc.idProveedor
              LEFT JOIN productosServiciosAsignacion AS pasign ON pasign.idProducto = poc.idProducto AND pasign.idFactura =$idFactura
              LEFT JOIN cuentas AS c ON c.idcuenta = oc.idcuenta
              WHERE poc.idProducto IN (SELECT psa.idProducto FROM productosServiciosAsignacion AS psa WHERE psa.idFactura = $idFactura) ORDER BY poc.idOrden DESC");
              
              // // Convinacion de array $productosEnOrdenCompra y $asignadosEnAlmacen
              // $productosEnOrdenCompra = array_merge($productosEnOrdenCompra, $asignadosEnAlmacen);
              // // ordenandno la union de los array
              // usort($productosEnOrdenCompra,$this->ordenarArray('idOrden','DESC'));

              $cuantasCompras=count($productosEnOrdenCompra);
              $sumaTotal=\DB::select("SELECT 
              FORMAT(SUM(((((((poc.subtotal/poc.cantidadOrden)*pasign.cantidadAservicio) + ((poc.ivaDinero/poc.cantidadOrden)*pasign.cantidadAservicio) + ((poc.isrDinero/poc.cantidadOrden)*pasign.cantidadAservicio))-((poc.retencionDinero/poc.cantidadOrden)*pasign.cantidadAservicio)))*oc.cambioDolar)),2) AS sumaTotalFormat,
              SUM(((((((poc.subtotal/poc.cantidadOrden)*pasign.cantidadAservicio) + ((poc.ivaDinero/poc.cantidadOrden)*pasign.cantidadAservicio) + ((poc.isrDinero/poc.cantidadOrden)*pasign.cantidadAservicio))-((poc.retencionDinero/poc.cantidadOrden)*pasign.cantidadAservicio)))*oc.cambioDolar)) as sumaTotal
              FROM productosOrdenCompra  AS poc
              LEFT JOIN ordenCompra AS oc ON oc.idOrden = poc.idOrden
              LEFT JOIN proveedores AS p ON p.idProveedor=oc.idProveedor
              LEFT JOIN productosServiciosAsignacion AS pasign ON pasign.idProducto = poc.idProducto AND pasign.idFactura =$idFactura
              LEFT JOIN cuentas AS c ON c.idcuenta = oc.idcuenta
              WHERE poc.idProducto IN (SELECT psa.idProducto FROM productosServiciosAsignacion AS psa WHERE psa.idFactura = $idFactura)");


              $consultaOrden = \DB::select("SELECT pv.idPartesVenta, co.idCotizacion, descripcion, codigo, nombreRefaccion, f.nombreEmpresa
              FROM cotizaciones AS co
              INNER JOIN facturas AS f ON f.idCotizacion=co.idCotizacion
              INNER JOIN detallereparaciones AS dr ON dr.idCotizacion=co.idCotizacion
              INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idEquipos
              WHERE f.idFactura=$idFactura
              ORDER BY nombreRefaccion ASC");

              if (count($ordenCompra) >= 1) {
                $consultaProductosOrden = \DB::select("SELECT idOrden FROM productosOrdenCompra WHERE idOrden=?",[$ordenCompra[0]->idOrden]);
                $cuantosProductosEnOrden=count($consultaProductosOrden);
              }else {
                $cuantosProductosEnOrden=0;
              }
              
              
              $cuantasOrden=count($ordenCompra);
        
              $nombreEmpresa=$consulta[0]->nombreEmpresa;

              $consultaCuentas=\DB::select("SELECT idCuenta, nombreCuenta FROM cuentas WHERE empresaCuenta='$nombreEmpresa' AND activo='Si'");

              $consultaProveedor=\DB::select("SELECT idProveedor, razonSocialProv FROM proveedores ORDER BY razonSocialProv ASC");
        
                /////fin de orden compra//////

              //////////orden de compra excel//////////////

              $query= DB::table('ordenCompra as oc')
              ->select('oc.idOrden','oc.idFactura','f.idServicios', 'importeMXN','codigoOrden','oc.nombreEmpresa','estatus','oc.activo',
              'p.razonSocialProv', 'sucursal', 'numeroCu', 'ivaMXN', 'isrMXN', 'totalMXN',
              DB::raw('CONCAT(numeroCu, " - ", nombreCuenta) AS cuenta'),
              DB::raw('CONCAT(moneda, " $", FORMAT(importeOrden,2)) AS importeOrden'),
              DB::raw('CONCAT(moneda, " $", FORMAT(total,2)) AS total'),
              DB::raw('CONCAT(" $", FORMAT(ivaCompra,2)) AS ivaCom'),
              DB::raw('CONCAT(" $", FORMAT(isrCompra,2)) AS isrCom'),
              //DB::raw('IF (moneda="MXN", CONCAT(moneda, " $", FORMAT(importeOrden,2)), CONCAT("MXN", " $", FORMAT(importeOrden*cambioDolar,2))) AS importeOrden'),
              /*DB::raw('IF (moneda="MXN", CONCAT(moneda, " $", FORMAT(ivaCompra,2)), CONCAT("MXN", " $", FORMAT(ivaCompra*cambioDolar,2))) AS ivaCom'),
              DB::raw('IF (moneda="MXN", CONCAT(moneda, " $", FORMAT(isrCompra,2)), CONCAT("MXN", " $", FORMAT(isrCompra*cambioDolar,2))) AS isrCom'),
              DB::raw('IF (moneda="MXN", CONCAT(moneda, " $", FORMAT(total,2)), CONCAT("MXN", " $", FORMAT(total*cambioDolar,2))) AS total'),
              */
              DB::raw('DATE_FORMAT(fechaOrden,"%d %b %Y") AS fechaOrden')
              )
              ->leftJoin('proveedores as p', 'oc.idProveedor', '=', 'p.idProveedor')  
              ->leftJoin('sucursales as s', 'oc.idSucursal', '=', 's.idSucursal')  
              ->leftJoin('cuentas as c', 'oc.idCuenta', '=', 'c.idCuenta')  
              ->leftJoin('facturas as f', 'oc.idFactura', '=', 'f.idFactura')  
              ->where('oc.activo','=','Si')
              ->where('oc.idFactura','=',$idFactura)
              ->orderBy('oc.idOrden', 'DESC')
              ->get();
              //dd($query);

              $consultaOC = $this->json($query);
              
              
              
              //////////fin de compra excel//////////////

              $consultaDatosPago = datosPagoServicios::where('idFactura', '=', $idFactura)->get();
              $cuantosDP = count($consultaDatosPago);

              $totalesDatosPago = datosPagoServicios::where('idFactura', '=', $idFactura)
                  ->select(\DB::raw('SUM(tipoCambioFac) as tipoCambioFacturado'),
                           \DB::raw('SUM(saldoFac) as saldoFacturado'),
                           \DB::raw('SUM(subtotalFac) as subtotalFacturado'),
                           \DB::raw('SUM(ivaFac) as ivaFacturado'),
                           \DB::raw('SUM(montoPesosDP) as montoFacturado'),
                           \DB::raw('SUM(tipoCambioPagado) as tipoCambioPagado'),
                           \DB::raw('SUM(saldoReal) as saldoReal'),
                           \DB::raw('SUM(subtotalFinal) as subtotalFinal'),
                           \DB::raw('SUM(ivaFinal) as ivaFinal'),
                           \DB::raw('SUM(montoReal) as montoReal'),
                           \DB::raw('SUM(difCambiaria) as diferenciaCambiaria')
                  )
                  ->groupBy('idFactura')
                  ->get();

              $totalDP=\DB::select("SELECT idFactura,
              FORMAT(SUM(montoPesosDP),2) as total
              FROM datosPagoServicios 
              WHERE idFactura = $idFactura");


              $cuantosSeguimiento=count($seguimiento);
              
              $consulta = facturas::Where('idFactura','=',$idFactura)->get();

              $sucursalSel= sucursales::where('idSucursal',"=",$consulta[0]->idSucursal)->get();
              $nomsuc =$sucursalSel[0]->sucursal;
              $sucursal = sucursales::where ('idSucursal','!=',$consulta[0]->idSucursal)->get();
          
              $clienteSel= clientes::where('idc',"=",$consulta[0]->idc)->get();
              $nomcli =$clienteSel[0]->razonSocial;
              $cliente = clientes::where ('idc','!=',$consulta[0]->idc)
              ->orderBy('razonSocial', 'asc')
              ->get();

              $bancoSel= bancos::where('idb',"=",$consulta[0]->idb)->get();
              $cuantosBancos = count($bancoSel);

              if($cuantosBancos != 0){
                $nomban = $bancoSel[0]->nombre;
                $idBan = $bancoSel[0]->idb;
                $consultaBancos = \DB::select("SELECT idb, nombre FROM bancos");
              }else{
                $nomban = 0;
                $idBan = 0;
                $consultaBancos = \DB::select("SELECT idb, nombre FROM bancos");
              }
              $bancos = bancos::where ('idb','!=',$consulta[0]->idb)->get();
            

           
            $cotizacionSel= cotizaciones::where('idCotizacion',"=",$consulta[0]->idCotizacion)->get();
            $cuantos = count($cotizacionSel);
            if($cuantos != 0){

              $nomCot = $cotizacionSel[0]->numeroCotizacion;
              $idCot = $cotizacionSel[0]->idCotizacion;
              // $idCotizacionServ = $cotizacionSel[0]->idCotizacion;
              $idSucursal = $consulta[0]->idSucursal;
              $consultaCot2 =\DB::select("SELECT idCotizacion, numeroCotizacion, idSucursal 
              FROM cotizaciones WHERE estatus = 'Aceptada con orden de compra' AND idSucursal = ? AND idCotizacion 
              NOT IN (SELECT idCotizacion FROM facturas WHERE idCotizacion != 'NULL')",[$idSucursal]);

               $equiposNuevosYreparados = \DB::select("SELECT dr.tipoCotizacion,dr.idRepCotizacion,dr.piezas,dr.idEquipos,dr.idCotizacion,dr.descripcion,dr.modelo,dr.numeroCotizacion,dr.notas,dr.semanasEntrega,dr.montoEquipo,
                                          dr.montoFinanciamiento,dr.descuento,dr.tipoDescuento, dr.partida
                                          FROM detallereparaciones AS dr
                                          WHERE idCotizacion=?",[$idCot]);
                $datosEquiposNuevos = \DB::select("SELECT pv.nombreRefaccion,pv.numeroParte,dr.tipoCotizacion,dr.idEquipos
                FROM detallereparaciones AS dr
                INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idEquipos
                WHERE idCotizacion=? AND tipoCotizacion !='reparacion'",[$idCot]);
            
                $datosEquipoCotizado = \DB::select("SELECT re.idEquipos, re.serie,
                CONCAT (re.serie,' - ',te.tipoEquipo,' - ',se.subtipoEquipo) AS equipo
                FROM recepcionEquipos AS re
                INNER JOIN tipoequipos AS te ON te.idTipoEquipo= re.idTipoEquipo
                INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= re.idSubtipoEquipo
                WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones)");
                $cotizacionEditar1 = \DB::select("SELECT co.idCotizacion,co.idc,co.idu,co.idSucursal,co.numeroCotizacion,co.cotizacionOriginal,co.fechaCotizacion,co.estatus,tipoMoneda,
                CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
                co.noEquipos,co.dirigidoA,co.conCopia,
                FORMAT(co.montoTotal,2) AS montoTotal,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus,co.check1,co.textoCheck1,co.check2,co.textoCheck2,co.check3,co.textoCheck3,co.check4,
                co.textoCheck4,co.check5,co.textoCheck5,co.check6,co.textoCheck6,co.check7,co.textoCheck7
                FROM cotizaciones AS co
                INNER JOIN usuarios AS u ON u.idu= co.idu
                INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
                INNER JOIN clientes AS c ON c.idc= co.idc
                WHERE idCotizacion=?",[$idCot]);        

                $equiposReparados = \DB::select("SELECT dr.tipoCotizacion,dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion, dr.descripcion,dr.modelo,dr.numeroCotizacion,dr.notas,re.GCMid,re.complementoGCMid, dr.semanasEntrega, FORMAT(dr.montoEquipo,2) AS montoEquipo,
                FORMAT(dr.montoFinanciamiento,2) AS montoFinanciamiento,dr.descuento,dr.tipoDescuento
                FROM detallereparaciones AS dr
                INNER JOIN recepcionEquipos AS re ON re.idEquipos = dr.idEquipos
                WHERE idCotizacion=?",[$idCot]);

                $consultaPiezas=\DB::select("SELECT pa.idPiezaAsig, r.idRefaccion, r.codigoRefaccion, r.nombreRefaccion, r.serie, pa.cantidadOcupada,  r.presentacion,
                        p.razonSocialProv, pa.fechaCompra, pa.numeroFacturaProv, f.numeroFactura
                        FROM piezasAsignadas AS pa
                        INNER JOIN refacciones AS r ON r.idRefaccion = pa.idRefaccion
                        INNER JOIN proveedores AS p ON p.idProveedor = pa.idProveedor
                        INNER JOIN facturas AS f ON f.idFactura = pa.idFactura
                        INNER JOIN detallereparaciones AS dr ON dr.idRepCotizacion=pa.idRepCotizacion
                        WHERE dr.idCotizacion=?",[$idCot]);
                $cuantasPiezas=count($consultaPiezas);


            }else{
              // $cotizacionSel[0]->numeroCotizacion = 0;
              $nomCot = 0;
              $idCot= 0;
              $idSucursal = $consulta[0]->idSucursal;
            
              $consultaCot2 =\DB::select("SELECT idCotizacion, numeroCotizacion, idSucursal 
                FROM cotizaciones WHERE estatus = 'Aceptada con orden de compra' AND idSucursal = ? AND idCotizacion 
                NOT IN (SELECT idCotizacion FROM facturas WHERE idCotizacion != 'NULL')",[$idSucursal]);
                
            }
            $consultaCotizaciones = cotizaciones::where ('idCotizacion','!=',$consulta[0]->idCotizacion)->get(); 

            $cotizacionAsig= cotizacionServicios::where('idCotizacionServicios',"=",$consulta[0]->idCotizacion)->get();
            $cuantosCotAsig = count($cotizacionAsig);
            $consu = $consulta[0]->tipoServicio;
            $idCoti = $consulta[0]->idCotizacion;

            if($consu == 'Soporte Técnico'){
              $idSucursales = $consulta[0]->idSucursal;
              $idCoti = $consulta[0]->idCotizacion;
              if($idCoti == ''){
                $consultaServicioTecnico = \DB::select("SELECT idCotizacionServicios,numeroCotizacion, estatus FROM cotizacionServicios 
                                                        WHERE estatus='Aceptada con orden de compra' AND idSucursal = $idSucursales");
                $cuantasPiezas=0;
              }else{
                $consultaServicioTecnico = \DB::select("SELECT idCotizacionServicios,numeroCotizacion, estatus FROM cotizacionServicios 
                                                    WHERE estatus='Aceptada con orden de compra' AND idSucursal = $idSucursales 
                                                    AND idCotizacionServicios = (SELECT DISTINCT idCotizacion FROM facturas WHERE idCotizacion = $idCoti AND tipoServicio='Soporte Técnico')");

                $archivoServTec = \DB::select("SELECT archivoCotizacion FROM cotizacionServicios 
                                              WHERE idCotizacionServicios = (SELECT DISTINCT idCotizacion FROM facturas WHERE idCotizacion = $idCoti AND tipoServicio='Soporte Técnico')");
                
                $serviciosEncotizacion = \DB::select("SELECT dcs.idDetalleCotizacionServ,dcs.idCotizacionServicios,
                st.servicioTecnico,dcs.detalle,dcs.observacionCantidad,dcs.numeroCotizacion,dcs.notas,dcs.semanasEntrega,dcs.tipoMoneda,dcs.montoEquipo,dcs.cantidad,
                dcs.montoFinanciamiento,dcs.descuento,dcs.tipoDescuento,dcs.clonEquiposActivo,dcs.agregadoReciente
                FROM detalleCotizacionServicios AS dcs
                LEFT JOIN serviciosTecnicos AS st ON st.idServicioTecnico=dcs.idServicioTecnico
                WHERE idCotizacionServicios=?",[$idCoti]);
                
              }  
            }

            $idCoti=0;
            $serviciosEncotizacion = \DB::select("SELECT dcs.idDetalleCotizacionServ,dcs.idCotizacionServicios,
                st.servicioTecnico,dcs.detalle,dcs.observacionCantidad,dcs.numeroCotizacion,dcs.notas,dcs.semanasEntrega,dcs.tipoMoneda,dcs.montoEquipo,dcs.cantidad,
                dcs.montoFinanciamiento,dcs.descuento,dcs.tipoDescuento,dcs.clonEquiposActivo,dcs.agregadoReciente
                FROM detalleCotizacionServicios AS dcs
                LEFT JOIN serviciosTecnicos AS st ON st.idServicioTecnico=dcs.idServicioTecnico
                WHERE idCotizacionServicios=?",[$idCoti]);

            $consultaArchivo = \DB::select("SELECT v.archivo FROM facturas AS v Where idFactura = $idFactura ");
            $consultaFactura = \DB::select("SELECT v.archivoFactura FROM facturas AS v Where idFactura = $idFactura ");
            $consultaPago = \DB::select("SELECT v.archivoPago FROM facturas AS v Where idFactura = $idFactura ");
            $consultaxml = \DB::select("SELECT v.xmlFactura FROM facturas AS v Where idFactura = $idFactura ");
            $consultaRemision = \DB::select("SELECT v.archivoRemision FROM facturas AS v Where idFactura = $idFactura ");
            $consultaOtro = \DB::select("SELECT v.archivoOtro FROM facturas AS v Where idFactura = $idFactura ");
            $consultaAdenda = \DB::select("SELECT v.archivoAdenda FROM facturas AS v Where idFactura = $idFactura ");
            $anexos = \DB::select("SELECT v.anexo1 FROM facturas AS v Where idFactura = $idFactura ");
            $anexo2 = \DB::select("SELECT v.anexo2 FROM facturas AS v Where idFactura = $idFactura ");
            $anexo3 = \DB::select("SELECT v.anexo3 FROM facturas AS v Where idFactura = $idFactura ");
            $anexo4 = \DB::select("SELECT v.anexo4 FROM facturas AS v Where idFactura = $idFactura ");
            $anexo5 = \DB::select("SELECT v.anexo5 FROM facturas AS v Where idFactura = $idFactura ");
            $anexo6 = \DB::select("SELECT v.anexo6 FROM facturas AS v Where idFactura = $idFactura ");
            $anexo7 = \DB::select("SELECT v.anexo7 FROM facturas AS v Where idFactura = $idFactura ");
            $anexo8 = \DB::select("SELECT v.anexo8 FROM facturas AS v Where idFactura = $idFactura ");
            $anexo9 = \DB::select("SELECT v.anexo9 FROM facturas AS v Where idFactura = $idFactura ");
            $anexo10 = \DB::select("SELECT v.anexo10 FROM facturas AS v Where idFactura = $idFactura ");
            $archivo =\DB::select("SELECT idCotizacion,archivoCotizacion FROM cotizaciones WHERE estatus='Aceptada con orden de compra' AND idCotizacion=?",[$consulta[0]->idCotizacion]);
            $archivo =\DB::select("SELECT idCotizacion,archivoCotizacion FROM cotizaciones WHERE estatus='Aceptada con orden de compra' AND idCotizacion=?",[$consulta[0]->idCotizacion]);

            $consultaPiezas=\DB::select("SELECT pa.idPiezaAsig, r.idRefaccion, r.codigoRefaccion, r.nombreRefaccion, r.serie, pa.cantidadOcupada,  r.presentacion,
                        p.razonSocialProv, pa.fechaCompra, pa.numeroFacturaProv, f.numeroFactura
                        FROM piezasAsignadas AS pa
                        INNER JOIN refacciones AS r ON r.idRefaccion = pa.idRefaccion
                        INNER JOIN proveedores AS p ON p.idProveedor = pa.idProveedor
                        INNER JOIN facturas AS f ON f.idFactura = pa.idFactura
                        INNER JOIN detallereparaciones AS dr ON dr.idRepCotizacion=pa.idRepCotizacion
                        WHERE dr.idCotizacion=?",[$consulta[0]->idCotizacion]);
                $cuantasPiezas=count($consultaPiezas);
				
				///// Reporte de Utilidades/////
                $consultaUtilidad = \DB::select("SELECT t1.idfactura, t1.clavegasto, t1.fechasalida,t1.cuentagasto,t1.tipo, t1.beneficiario, t1.formaPago, FORMAT(t1.subtotal,2 ) AS subTotal, t1.moneda,
                                    t1.ivaTotal, t1.isrTotal, FORMAT(t1.total,2) AS total, t1.divisa,t1.totalpesos
                                    FROM
                                        (SELECT idfactura, CONCAT('OC - ',oc.codigoorden) AS clavegasto, oc.fechaorden AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
                                        'Orden Compra' AS tipo, CONCAT('Prov -  ',p.razonSocialProv) AS beneficiario,formaPago,importeOrden AS subtotal, moneda, ivaCompra AS ivaTotal, isrCompra AS isrTotal, total, moneda AS divisa,
                                        IF(moneda= 'USD', total * cambiodolar, total) AS totalpesos
                                        FROM ordenCompra AS oc
                                        INNER JOIN cuentas AS c ON c.idcuenta = oc.idcuenta
                                        INNER JOIN proveedores AS p ON p.idProveedor = oc.idProveedor
                                        -- WHERE IDFACTURA= $idFactura
                                        WHERE oc.idOrden IN (SELECT psa.idOrden FROM productosServiciosAsignacion AS psa WHERE psa.idFactura= 0)
                                    UNION
                                        SELECT id_factura AS idfactura, CONCAT('REF - ',g.referencia) AS clavegasto,  g.fecha_pago AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
                                        'GASTO' AS tipo, beneficiario, forma_pago AS formaPago, factura AS subtotal, moneda, total_iva AS ivaTotal,total_isr AS isrTotal, total, moneda AS divisa,
                                        IF(moneda='USD', total*cambiodolar,total) AS totalpesos
                                        FROM gastos AS g
                                        INNER JOIN cuentas AS c ON c.idcuenta = g.idCuenta
                                        WHERE ID_FACTURA = $idFactura and g.activo = 'Si')
                                    AS t1
                                    ORDER BY t1.fechasalida ASC");
									$consultaUtilidadServ = $this->utilidadJson($consultaUtilidad);

                 //Nueva Tabla
                 $OrdenConsulta=\DB::select("SELECT t1.idorden,t1.idpartesventa,t1.codigoOrden,t1.fechaOrden,t1.cuenta,t1.formaPago,t1.razonSocialProv,t1.codigo,t1.nombreRefaccion,t1.moneda,t1.subtotal, 
       t1.ivaDinero,t1.isrDinero,t1.precioProducto,t1.montopesos,IF(t1.montofinal IS NULL, 'Pendiente Monto Final',t1.montofinal) AS montofinal
       FROM
(SELECT oc.idorden,poc.idpartesventa,oc.codigoOrden,oc.fechaOrden,CONCAT(cu.numeroCu,'-',cu.nombreCuenta) AS cuenta,oc.formaPago,pro.razonSocialProv,poc.codigo,
                 poc.nombreRefaccion,oc.moneda,poc.subtotal,poc.ivaDinero,poc.isrDinero,poc.precioProducto,
                 IF(oc.moneda = 'MXN',poc.precioProducto,poc.precioProducto*oc.cambioDolar) AS montopesos,
				 cambiodolarfinalnotaentrada(oc.idorden,poc.idpartesventa)  * poc.precioProducto AS montofinal
                 FROM productosOrdenCompra AS poc
                 INNER JOIN ordenCompra AS oc ON oc.idOrden = poc.idOrden
                 INNER JOIN proveedores AS pro ON pro.idProveedor = oc.idProveedor
                 INNER JOIN cuentas AS cu ON cu.idCuenta = oc.idCuenta
                 WHERE oc.activo = 'Si' AND poc.idFactura = $idFactura) AS t1
                 ");
                 $CuantaOrden = count($OrdenConsulta);

                $consultaTotales = \DB::select("SELECT 
                                    TRUNCATE(SUM(t1.ivaTotal),2) AS totaliva, TRUNCATE(SUM(t1.isrTotal),2) AS totalisr, TRUNCATE(SUM(t1.totalpesos),2) AS totalpesos
                                    FROM
                                    (SELECT CONCAT('OC - ',oc.codigoorden) AS clavegasto,importeOrden AS subtotal, moneda, ivaCompra AS ivaTotal, isrCompra AS isrTotal, total, moneda AS divisa,
                                    IF(moneda= 'USD', total * cambiodolar, total) AS totalpesos
                                    FROM ordenCompra AS oc
                                    INNER JOIN cuentas AS c ON c.idcuenta = oc.idcuenta
                                    -- WHERE IDFACTURA= $idFactura
                                    WHERE IDFACTURA= 0
                                    UNION
                                    SELECT CONCAT('REF - ',g.referencia) AS clavegasto, factura AS subtotal, moneda, total_iva AS ivaTotal,total_isr AS isrTotal, total, moneda AS divisa,
                                    IF(moneda='USD', total*cambiodolar,total) AS totalpesos
                                    FROM gastos AS g
                                    INNER JOIN cuentas AS c ON c.idcuenta = g.idCuenta
                                    WHERE ID_FACTURA = $idFactura)
                                    AS t1");
                                    if(count($consultaTotales)>=1)
                                    {
                                      $totalIva =  $consultaTotales[0]->totaliva;
                                      $totalIsr =  $consultaTotales[0]->totalisr;
                                      $totalFinal =  $consultaTotales[0]->totalpesos;
                                    }else{
                                      $totalIva = 0;
                                      $totalIsr = 0;
                                      $totalFinal = 0;
                                    }
                $servicioTotal = \DB::select("SELECT t2.factura, t2.totalservicio
                                    FROM
                                        (SELECT idFactura AS factura, IF(tipoMoneda= 'USD', totalConIva * cambioReal, totalConIva) AS totalservicio
                                        FROM facturas AS f
                                        
                                        WHERE idFactura=$idFactura)
                                    AS t2");
                                    $servT = $servicioTotal[0]->totalservicio;
                ///////////////////////////////
                $totalMxn = \DB::select("SELECT t1.totalpesos, t1.fechasalida
                                        FROM
                                        (SELECT IF(moneda='USD', total*cambiodolar,total) AS totalpesos, fecha_pago AS fechasalida
                                          FROM gastos
                                          WHERE ID_FACTURA = $idFactura AND activo = 'Si')
                                        AS t1
                                        ORDER BY t1.fechasalida ASC");
                ///////////////////////////////
				/////////////Gastos//////////////////
                $gastoServ = \DB::select("SELECT 
                id, 
                id_factura, 
                nombreGasto,
                beneficiario,
                fecha_pago,
                metodo_pago,
                forma_pago,
                referencia, 
                etiquetas, 
                CONCAT(moneda,' ',factura) AS factura, 
                iva, 
                CONCAT(moneda,' ',total_iva) AS total_iva, 
                isr, 
                CONCAT(moneda,' ',total_isr) AS total_isr, 
                CONCAT(moneda,' ',total) AS total, 
                descripcion, 
                moneda, 
                cambiodolar,
				activo,
                IF(moneda='USD',total*cambiodolar,total) AS totalmxn
                FROM gastos 
                WHERE id_factura = $idFactura
                ORDER BY fecha_pago ASC");
                $consultaGastoServ = $this->gastoJson($gastoServ);

                // dd($consultaGastoServ);

                ///////////////////////////////
				$consultaDP = \DB::select("SELECT /* t1.factura, */ SUM(t1.monto) AS monto
							  FROM(
								SELECT IF(f.montoPesos IS NULL, 0, f.montoPesos) AS monto
								FROM facturas AS f
								WHERE f.idFactura = $idFactura
								UNION
								SELECT IF(dp.montoReal IS NULL, 0, dp.montoReal)AS monto
								FROM datosPagoServicios as dp
								WHERE dp.idFactura = $idFactura
							  )AS t1");
							  $cdp = $consultaDP[0]->monto;
                ///////////////////////////////
                /// Vendedores Asignados a Servicios  ///

                $vendedores = usuarios::whereIn('idu', [5, 8, 10, 13, 18])              
                ->orderby('nombreUsuario', 'asc')
                ->select("usuarios.idu")
                ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as vendedor")
                ->get();               
              
                $consultaVas = vendedoresAsignadosServicios::where('idser', '=', $idFactura)
                ->join("usuarios", "vendedoresAsignadosServicios.idvend", "=", "usuarios.idu")->
                select(
                  "vendedoresAsignadosServicios.idvas",
                  "vendedoresAsignadosServicios.idser",
                  "vendedoresAsignadosServicios.idvend",
                  "vendedoresAsignadosServicios.porcentaje",
                  // "usuarios.nombreUsuario as vendedor",
                )
                ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as vendedor")
                ->get();  
              $cuantosVas = count($consultaVas);                                                     

            
            if($consu == 'Soporte Técnico'){
              if($idCoti == ''){
                return view ('editarFacturas',compact( 'consultaOC','consultaGastoServ','consultaUtilidadServ'))
                ->with('cuantasCompras',$cuantasCompras)
                
                ->with('sumaTotal',$sumaTotal[0])
                ->with('cuantosProductosEnOrden',$cuantosProductosEnOrden)
                ->with('ordenCompra',$ordenCompra)
                ->with('productosEnOrdenCompra',$productosEnOrdenCompra)
                ->with('cuantasOrden',$cuantasOrden)
                ->with('consultaCuentas',$consultaCuentas)
                ->with('consultaOrden',$consultaOrden)
                ->with('consultaProveedor',$consultaProveedor)
                ->with('cuantosSeguimiento',$cuantosSeguimiento)
                ->with('seguimiento',$seguimiento)

                  ->with('cuantasPiezas',$cuantasPiezas)
                  ->with('consultaServicioTecnico',$consultaServicioTecnico)
                  ->with('cuantosCotAsig',$cuantosCotAsig)
                  ->with('idCoti',$idCoti)
                  ->with('serviciosEncotizacion',$serviciosEncotizacion)
                  ->with('consu',$consu)

                  ->with('stipo',$stipo)
                  ->with('consulta',$consulta[0])
                  ->with('consultaArchivosFac',$consultaArchivosFac)
                  ->with('cuantosAF',$cuantosAF)
                  ->with('consultaDatosPago',$consultaDatosPago)
                  ->with('cuantosDP',$cuantosDP)
                  ->with('totalDP',$totalDP[0])
                  ->with('bancos',$bancos)
                  ->with('idbsel',$consulta[0]->idb)
                  ->with('cuantosBancos',$cuantosBancos)
                  ->with('consultaBancos',$consultaBancos)
                  ->with('idBan',$idBan)
                  ->with('nomban',$nomban)

                  ->with('consultaCot2',$consultaCot2)
                  ->with('cuantos',$cuantos)
                
                  ->with('consultaCotizaciones',$consultaCotizaciones)
                  ->with('idCotizacionSel',$consulta[0]->idCotizacion)
                  ->with('nomCot',$nomCot)
                  ->with('idCot',$idCot)
                  // ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
                  // ->with('equiposReparados',$equiposReparados)
                  // ->with('datosEquiposNuevos',$datosEquiposNuevos)
                  // ->with('datosEquipoCotizado',$datosEquipoCotizado)
                  // ->with('cotizacionEditar1',$cotizacionEditar1[0])
              
                  ->with('cliente',$cliente)
                  ->with('idclientesel',$consulta[0]->idc)
                  ->with('nomcli',$nomcli)
                  ->with('clienteSel',$clienteSel[0])

                  ->with('consultaArchivo',$consultaArchivo)
                  ->with('consultaFactura',$consultaFactura)
                  ->with('consultaPago',$consultaPago)
                  ->with('consultaxml',$consultaxml)
                  ->with('consultaRemision',$consultaRemision)
                  ->with('consultaOtro',$consultaOtro)
                  ->with('consultaAdenda',$consultaAdenda)
                  ->with('anexos',$anexos)
                  ->with('anexo2',$anexo2)
                  ->with('anexo3',$anexo3)
                  ->with('anexo4',$anexo4)
                  ->with('anexo5',$anexo5)
                  ->with('anexo6',$anexo6)
                  ->with('anexo7',$anexo7)
                  ->with('anexo8',$anexo8)
                  ->with('anexo9',$anexo9)
                  ->with('anexo10',$anexo10)
                  ->with('sucursal',$sucursal)
                  ->with('idSucursal',$consulta[0]->idSucursal)
                  ->with('nomsuc',$nomsuc)
                  ->with('sucursalSel',$sucursalSel[0])
                  ->with('nomsuc',$nomsuc)
                  ->with('archivo',$archivo)
                  ->with('idFactura', $idFactura)
                  ->with('gastos',$gastos)
		              ->with('contadorGastos', $contadorGastos)
                  ->with('empresa', $consulta[0]->nombreEmpresa)
				          ->with('ReporteUtilidad', $consultaUtilidad)
                  ->with('totalIva', $totalIva)
                  ->with('totalIsr', $totalIsr)
                  ->with('totalFinal', $totalFinal)
                  ->with('servicioT', $servT)
				          ->with('totalMxn',$totalMxn)
				          ->with('consultaDP',$consultaDP)
                  ->with('OrdenConsulta', $OrdenConsulta)
                  ->with('CuantaOrden', $CuantaOrden)
                  ->with('idFactura', $idFactura)                  
                  ->with('totalesDatosPago', $totalesDatosPago)
                  ->with('vendedores', $vendedores)
                  ->with('consultaVas',$consultaVas)
                  ->with('cuantosVas', $cuantosVas);


              }else{
                return view ('editarFacturas',compact( 'consultaOC','consultaGastoServ','consultaUtilidadServ'))
                ->with('sumaTotal',$sumaTotal[0])
                ->with('cuantasCompras',$cuantasCompras)
                
                ->with('cuantosProductosEnOrden',$cuantosProductosEnOrden)
                ->with('ordenCompra',$ordenCompra)
                ->with('productosEnOrdenCompra',$productosEnOrdenCompra)
                ->with('cuantasOrden',$cuantasOrden)
                ->with('consultaCuentas',$consultaCuentas)
                ->with('consultaOrden',$consultaOrden)
                ->with('consultaProveedor',$consultaProveedor)
                ->with('cuantosSeguimiento',$cuantosSeguimiento)
                ->with('seguimiento',$seguimiento)
                ->with('cuantasPiezas',$cuantasPiezas)
                ->with('consultaServicioTecnico',$consultaServicioTecnico)
                ->with('archivoServTec',$archivoServTec)
                ->with('cuantosCotAsig',$cuantosCotAsig)
                ->with('idCoti',$idCoti)
                ->with('serviciosEncotizacion',$serviciosEncotizacion)
                ->with('consu',$consu)


                ->with('stipo',$stipo)
                ->with('consulta',$consulta[0])
                ->with('consultaArchivosFac',$consultaArchivosFac)
                ->with('cuantosAF',$cuantosAF)
                ->with('consultaDatosPago',$consultaDatosPago)
                ->with('cuantosDP',$cuantosDP)
                ->with('totalDP',$totalDP[0])
                
                ->with('bancos',$bancos)
                ->with('idbsel',$consulta[0]->idb)
                ->with('cuantosBancos',$cuantosBancos)
                ->with('consultaBancos',$consultaBancos)
                ->with('idBan',$idBan)
                ->with('nomban',$nomban)

                ->with('consultaCot2',$consultaCot2)
                ->with('cuantos',$cuantos)
               
                ->with('consultaCotizaciones',$consultaCotizaciones)
                ->with('idCotizacionSel',$consulta[0]->idCotizacion)
                ->with('nomCot',$nomCot)
                ->with('idCot',$idCot)
                // ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
                // ->with('equiposReparados',$equiposReparados)
                // ->with('datosEquiposNuevos',$datosEquiposNuevos)
                // ->with('datosEquipoCotizado',$datosEquipoCotizado)
                // ->with('cotizacionEditar1',$cotizacionEditar1[0])
             
                ->with('cliente',$cliente)
                ->with('idclientesel',$consulta[0]->idc)
                ->with('nomcli',$nomcli)
                ->with('clienteSel',$clienteSel[0])

                ->with('consultaArchivo',$consultaArchivo)
                ->with('consultaFactura',$consultaFactura)
                ->with('consultaPago',$consultaPago)
                ->with('consultaxml',$consultaxml)
                ->with('consultaRemision',$consultaRemision)
                ->with('consultaOtro',$consultaOtro)
                ->with('consultaAdenda',$consultaAdenda)
                ->with('anexos',$anexos)
                ->with('anexo2',$anexo2)
                ->with('anexo3',$anexo3)
                ->with('anexo4',$anexo4)
                ->with('anexo5',$anexo5)
                ->with('anexo6',$anexo6)
                ->with('anexo7',$anexo7)
                ->with('anexo8',$anexo8)
                ->with('anexo9',$anexo9)
                ->with('anexo10',$anexo10)
                ->with('sucursal',$sucursal)
                ->with('idSucursal',$consulta[0]->idSucursal)
                ->with('nomsuc',$nomsuc)
                ->with('sucursalSel',$sucursalSel[0])
                ->with('nomsuc',$nomsuc)
                ->with('archivo',$archivo)
                ->with('idFactura', $idFactura)
                ->with('gastos',$gastos)
		->with('contadorGastos', $contadorGastos)
                ->with('empresa', $consulta[0]->nombreEmpresa)
				->with('ReporteUtilidad', $consultaUtilidad)
				  ->with('totalIva', $totalIva)
				  ->with('totalIsr', $totalIsr)
				  ->with('totalFinal', $totalFinal)
				  ->with('servicioT', $servT)
				  ->with('totalMxn',$totalMxn)
				  ->with('consultaDP',$consultaDP)
          ->with('OrdenConsulta', $OrdenConsulta)
          ->with('CuantaOrden', $CuantaOrden)
          ->with('idFactura', $idFactura)
          ->with('totalesDatosPago', $totalesDatosPago)
          ->with('vendedores', $vendedores)
          ->with('consultaVas',$consultaVas)
          ->with('cuantosVas', $cuantosVas);
                
                }
              }else{
                if($idCot == ''){
                  return view ('editarFacturas',compact( 'consultaOC','consultaGastoServ','consultaUtilidadServ'))
                  ->with('cuantasCompras',$cuantasCompras)
                  
                  ->with('sumaTotal',$sumaTotal[0])
                  ->with('cuantosProductosEnOrden',$cuantosProductosEnOrden)
                  ->with('ordenCompra',$ordenCompra)
                  ->with('productosEnOrdenCompra',$productosEnOrdenCompra)
                  ->with('cuantasOrden',$cuantasOrden)
                  ->with('consultaCuentas',$consultaCuentas)
                  ->with('consultaOrden',$consultaOrden)
                  ->with('consultaProveedor',$consultaProveedor)
                  ->with('cuantosSeguimiento',$cuantosSeguimiento)
                  ->with('cuantasPiezas',$cuantasPiezas)
                  ->with('cuantosCotAsig',$cuantosCotAsig)
                  ->with('consu',$consu)
  
                  ->with('stipo',$stipo)
                  ->with('idCot',$idCot)
                  ->with('idCoti',$idCoti)
                  
  
                  ->with('consulta',$consulta[0])
                  ->with('consultaArchivosFac',$consultaArchivosFac)
                  ->with('cuantosAF',$cuantosAF)
                  ->with('consultaDatosPago',$consultaDatosPago)
                  ->with('cuantosDP',$cuantosDP)
                  ->with('totalDP',$totalDP[0])
                  // ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
                  // ->with('equiposReparados',$equiposReparados)
                  // ->with('datosEquiposNuevos',$datosEquiposNuevos)
                  // ->with('datosEquipoCotizado',$datosEquipoCotizado)
                  // ->with('cotizacionEditar1',$cotizacionEditar1[0])
                  
                  
                  ->with('bancos',$bancos)
                  ->with('idbsel',$consulta[0]->idb)
                  ->with('cuantosBancos',$cuantosBancos)
                  ->with('consultaBancos',$consultaBancos)
                  ->with('idBan',$idBan)
                  ->with('nomban',$nomban)
  
                  ->with('consultaCot2',$consultaCot2)
                  ->with('cuantos',$cuantos)
                 
                  ->with('consultaCotizaciones',$consultaCotizaciones)
                  ->with('idCotizacionSel',$consulta[0]->idCotizacion)
                  ->with('nomCot',$nomCot)
                  ->with('idCot',$idCot)
               
                  ->with('cliente',$cliente)
                  ->with('idclientesel',$consulta[0]->idc)
                  ->with('nomcli',$nomcli)
                  ->with('clienteSel',$clienteSel[0])
  
                  ->with('consultaArchivo',$consultaArchivo)
                  ->with('consultaFactura',$consultaFactura)
                  ->with('consultaPago',$consultaPago)
                  ->with('consultaxml',$consultaxml)
                  ->with('consultaRemision',$consultaRemision)
                  ->with('consultaOtro',$consultaOtro)
                  ->with('consultaAdenda',$consultaAdenda)
                  ->with('anexos',$anexos)
                  ->with('anexo2',$anexo2)
                  ->with('anexo3',$anexo3)
                  ->with('anexo4',$anexo4)
                  ->with('anexo5',$anexo5)
                  ->with('anexo6',$anexo6)
                  ->with('anexo7',$anexo7)
                  ->with('anexo8',$anexo8)
                  ->with('anexo9',$anexo9)
                  ->with('anexo10',$anexo10)
                  ->with('sucursal',$sucursal)
                  ->with('idSucursal',$consulta[0]->idSucursal)
                  ->with('nomsuc',$nomsuc)
                  ->with('sucursalSel',$sucursalSel[0])
                  ->with('nomsuc',$nomsuc)
                  ->with('archivo',$archivo)
                  ->with('idFactura', $idFactura)
                  ->with('gastos',$gastos)
				  ->with('totalMxn',$totalMxn)
				  ->with('consultaDP',$consultaDP)
		  ->with('contadorGastos', $contadorGastos)
                  ->with('empresa', $consulta[0]->nombreEmpresa)
				  ->with('ReporteUtilidad', $consultaUtilidad)
                  ->with('totalIva', $totalIva)
                  ->with('totalIsr', $totalIsr)
                  ->with('totalFinal', $totalFinal)
                  ->with('servicioT', $servT)
                  ->with('OrdenConsulta', $OrdenConsulta)
                  ->with('CuantaOrden', $CuantaOrden)
                  ->with('idFactura', $idFactura)
                  ->with('totalesDatosPago', $totalesDatosPago)
                  ->with('vendedores', $vendedores)
                  ->with('consultaVas',$consultaVas)
                  ->with('cuantosVas', $cuantosVas);                  

                }else{

                  return view ('editarFacturas',compact( 'consultaOC','consultaGastoServ','consultaUtilidadServ'))
                  ->with('cuantasCompras',$cuantasCompras)
                  
                  ->with('sumaTotal',$sumaTotal[0])
                  ->with('cuantosProductosEnOrden',$cuantosProductosEnOrden)
                  ->with('ordenCompra',$ordenCompra)
                  ->with('productosEnOrdenCompra',$productosEnOrdenCompra)
                  ->with('cuantasOrden',$cuantasOrden)
                  ->with('consultaCuentas',$consultaCuentas)
                  ->with('consultaOrden',$consultaOrden)
                  ->with('consultaProveedor',$consultaProveedor)
                  ->with('cuantosSeguimiento',$cuantosSeguimiento)
                  ->with('seguimiento',$seguimiento)

                  ->with('cuantasPiezas',$cuantasPiezas)
                  ->with('cuantosCotAsig',$cuantosCotAsig)
                  ->with('consu',$consu)
  
                  ->with('stipo',$stipo)
                  ->with('idCot',$idCot)
                  ->with('idCoti',$idCoti)
                  
  
                  ->with('consulta',$consulta[0])
                  ->with('consultaArchivosFac',$consultaArchivosFac)
                  ->with('cuantosAF',$cuantosAF)
                  ->with('consultaDatosPago',$consultaDatosPago)
                  ->with('cuantosDP',$cuantosDP)
                  ->with('totalDP',$totalDP[0])
                  ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
                  ->with('equiposReparados',$equiposReparados)
                  ->with('consultaPiezas',$consultaPiezas)
                  ->with('cuantasPiezas',$cuantasPiezas)
                  ->with('datosEquiposNuevos',$datosEquiposNuevos)
                  ->with('datosEquipoCotizado',$datosEquipoCotizado)
                  ->with('cotizacionEditar1',$cotizacionEditar1[0])
                  
                  
                  ->with('bancos',$bancos)
                  ->with('idbsel',$consulta[0]->idb)
                  ->with('cuantosBancos',$cuantosBancos)
                  ->with('consultaBancos',$consultaBancos)
                  ->with('idBan',$idBan)
                  ->with('nomban',$nomban)
  
                  ->with('consultaCot2',$consultaCot2)
                  ->with('cuantos',$cuantos)
                 
                  ->with('consultaCotizaciones',$consultaCotizaciones)
                  ->with('idCotizacionSel',$consulta[0]->idCotizacion)
                  ->with('nomCot',$nomCot)
                  ->with('idCot',$idCot)
               
                  ->with('cliente',$cliente)
                  ->with('idclientesel',$consulta[0]->idc)
                  ->with('nomcli',$nomcli)
                  ->with('clienteSel',$clienteSel[0])
  
                  ->with('consultaArchivo',$consultaArchivo)
                  ->with('consultaFactura',$consultaFactura)
                  ->with('consultaPago',$consultaPago)
                  ->with('consultaxml',$consultaxml)
                  ->with('consultaRemision',$consultaRemision)
                  ->with('consultaOtro',$consultaOtro)
                  ->with('consultaAdenda',$consultaAdenda)
                  ->with('anexos',$anexos)
                  ->with('anexo2',$anexo2)
                  ->with('anexo3',$anexo3)
                  ->with('anexo4',$anexo4)
                  ->with('anexo5',$anexo5)
                  ->with('anexo6',$anexo6)
                  ->with('anexo7',$anexo7)
                  ->with('anexo8',$anexo8)
                  ->with('anexo9',$anexo9)
                  ->with('anexo10',$anexo10)
                  ->with('sucursal',$sucursal)
                  ->with('idSucursal',$consulta[0]->idSucursal)
                  ->with('nomsuc',$nomsuc)
                  ->with('sucursalSel',$sucursalSel[0])
                  ->with('nomsuc',$nomsuc)
                  ->with('archivo',$archivo)
                  ->with('idFactura', $idFactura)
                  ->with('gastos',$gastos)
		              ->with('contadorGastos', $contadorGastos)
                  ->with('empresa', $consulta[0]->nombreEmpresa)
				          ->with('ReporteUtilidad', $consultaUtilidad)
                  ->with('totalIva', $totalIva)
                  ->with('totalIsr', $totalIsr)
                  ->with('totalFinal', $totalFinal)
                  ->with('servicioT', $servT)
				          ->with('totalMxn',$totalMxn)
				          ->with('consultaDP',$consultaDP)
                  ->with('OrdenConsulta', $OrdenConsulta)
                  ->with('CuantaOrden', $CuantaOrden)
                  ->with('idFactura', $idFactura)
                  ->with('totalesDatosPago', $totalesDatosPago)
                  ->with('vendedores', $vendedores)
                  ->with('consultaVas',$consultaVas)
                  ->with('cuantosVas', $cuantosVas);                

                }
               
                }

            }
        }

        public function json ( $query){

          function options( $value, $value2 ){
              $parameter= Crypt::encrypt($value2);
  
              if($value=='Si'){
                  // $btn = "<a href=".url("/restaurarEquipos/{$value2}")." type='submit' class='btn btn-xs btn-success'>Restaurar</a>";
                  $btn = "<a href='".url("/eliminarOrden/{$value2}")."' type='submit' class='btn btn-xs btn-danger'><i class='ace-icon fa fa-trash-o bigger-120'> Eliminar</i></a><br>".
                          "<a href='".url("/modificarOrden/{$value2}")."' type='submit' class='btn btn-xs btn-info'><i class='ace-icon fa fa-pencil bigger-120'> Editar</i></a><br>".
                          "<a href='".url("/pdfOrdenCompras/{$parameter}")."' type='submit' class='btn btn-xs btn-warning'><i class='ace-icon fa fa-download bigger-120'> Descargar PDF</i></a><br>";
              }else{
                  $btn = "<a href=".url("/restaurarOrden/{$value2}")." type='submit' class='btn btn-xs btn-warning'>Restaurar</a>";
              }
              
              return $btn;
              
          }
  
  
          $consult = array();
          foreach( $query as $value)
          {
              array_push( $consult, array(
                  'id'    => $value->idOrden, 
                  'a'    => $value->idServicios, 
                  'b'    => $value->codigoOrden, 
                  'c'    => $value->fechaOrden, 
                  'd'    => $value->razonSocialProv, 
                  'e'    => $value->sucursal, 
                  'f'    => $value->nombreEmpresa, 
                  'g'    => $value->cuenta, 
                  'h'    => $value->importeOrden, 
                  'i'    => $value->ivaCom, 
                  'j'    => $value->isrCom, 
                  'k'    => $value->total, 
                  'm'    => $value->totalMXN, 
                  'l'    => $value->estatus, 
                  'o'     => options( $value->activo, $value->idOrden, $value->idFactura),
              ) );
  
          }
  
          return $consult;
      }
      
      public function excelOC(Request $request){
        return Excel::download( new OCFacturasExport( $request->data, $request->user), 'reporteOrdenCompra.xlsx' );
    }
	public function gastoJson($gastoServ){
    
    function option( $value, $value2, $value3 ){
      $parameter= Crypt::encrypt($value2, $value3);

      if($value=='Si'){
         
          $btn = "<a href='".url("/baja_logica/{$value2}/{$value3}")."' type='submit' class='btn btn-xs btn-danger'><i class='ace-icon fa fa-trash-o bigger-120'> Eliminar</i></a><br>".
                  "<a href='".url("/editarGastos/{$value2}/{$value3}")."' type='submit' class='btn btn-xs btn-info'><i class='ace-icon fa fa-pencil bigger-120'> Editar</i></a><br>";
      }else{
          $btn = "<a href=".url("/baja_logica/{$value2}/{$value3}")." type='submit' class='btn btn-xs btn-warning'>Restaurar</a>";
      }
      
      return $btn;
      
    }
      $consultaG = array();
      foreach( $gastoServ as $value)
      {
          array_push( $consultaG, array(
              'id'    => $value->id_factura, 
              'a'    => $value->fecha_pago, 
              'b'    => $value->beneficiario, 
              'c'    => $value->metodo_pago, 
              'd'    => $value->referencia, 
              'e'    => $value->etiquetas, 
              'f'    => $value->descripcion, 
              'g'    => $value->factura, 
              'h'    => $value->iva, 
              'i'    => $value->total_iva, 
              'j'    => $value->isr, 
              'k'    => $value->total_isr, 
              'l'    => $value->total, 
              'm'    => $value->totalmxn, 
              'o'     => option( $value->activo, $value->id, $value->id_factura)
          ) );
  
      }
  
      return $consultaG;
  }
  public function utilidadJson($consultaUtilidad){
    $consultaUt = array();
    foreach( $consultaUtilidad as $value)
      {
          array_push( $consultaUt, array(
              'id'    => $value->idfactura, 
              'a'    => $value->clavegasto, 
              'b'    => $value->fechasalida, 
              'c'    => $value->cuentagasto, 
              'd'    => $value->tipo, 
              'e'    => $value->beneficiario, 
              'f'    => $value->formaPago, 
              'g'    => $value->subTotal, 
              'h'    => $value->ivaTotal, 
              'i'    => $value->isrTotal, 
              'j'    => $value->total, 
              'k'    => $value->totalpesos, 
              'o'    => $value->total, 
             // 'l'    => $value->estatus, 
             // 'm'     => options( $value->activo, $value->idOrden),
          ) );
  
      }
  
      return $consultaUt;
  }

        public function editarFacturas(Request $request){
            $idServicios = $request -> idServicios;
            $tipoServicio = $request -> tipoServicio;
            $idSucursal = $request -> idSucursal;
            $idCotizacion = $request -> idCotizacion;
            $idCot2 = $request -> idCot2;
            $nombreEmpresa = $request -> nombreEmpresa;

            $idFactura = $request -> idFactura;
            $numeroFactura = $request -> numeroFactura;
            $fechaVencimiento = $request -> fechaVencimiento;
            $idb = $request -> idb;
            $montoFactura = $request -> montoFactura;
            $periodoPago = $request -> periodoPago;
            $fechaFactura = $request -> fechaFactura;
            $fechaPago = $request -> fechaPago;
            $tipoMoneda = $request -> tipoMoneda;
            $idc = $request -> idc;
            $cambioFactura = $request -> cambioFactura;
            $montoPesos = $request -> montoPesos;
            $saldo = $request -> saldo;
            $cambioReal = $request -> cambioReal;
            $observacionesFactura = $request -> observacionesFactura;
            $ordenCompra = $request -> ordenCompra;
            $factura = $request -> factura;
            $comprobantePago = $request -> comprobantePago;
            $Archivo = $request -> Archivo;
            $metodoPago = $request -> metodoPago;
            $estatusEntrega = $request -> estatusEntrega;
            $estatusPortal = $request -> estatusPortal;
            $estatusPago = $request -> estatusPago;
            $cashFlow = $request -> cashFlow;
            $numeroRemision = $request -> numeroRemision;
            $numeroEntrada = $request -> numeroEntrada;
            $complementoPago = $request -> complementoPago;
            $numeroDocumento = $request -> numeroDocumento;
            $archivoOtro = $request -> archivoOtro;
            $this->validate ($request,[
            // 'fechaFactura'=>['required'],
            // 'fechaPago'=>['required'],
            // 'tipoMoneda'=>['required'],
            // // 'idb'=>['required'],
            // 'montoFactura'=>['required'],
            // 'periodoPago'=>['required'],
            // 'idc'=>['required'],
            // 'idSucursal'=>['required'],
            // 'estatusEntrega' =>['required'],
            // 'estatusPortal' =>['required'],
            // 'estatusPago' =>['required'],
            // 'cashFlow' =>['required'],
            // 'saldo'=>['required'],
            // 'periodoPago'=>['required'],
            'archivoFactura'=>['mimes:doc,docx,pdf'],
            /*'xmlFactura'=>['mimes:xml,XML'],*/]);
//Orden de compra
         $file = $request->file('archivo');
        //obtenemos el nombre del archivo
             if($file!=""){
        if($idCot2){
          $consulta = \DB::update("UPDATE recepcionEquipos SET estatusEntrega='Con OC' WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones WHERE idCotizacion = $idCot2)");
        }
          $archivo =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($archivo,  \File::get($file));
            }
//Factura
            $file2 = $request->file('archivoFactura');
             if($file2!=""){
              if($idCot2){
                $consulta = \DB::update("UPDATE recepcionEquipos SET estatusEntrega='Facturado' WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones WHERE idCotizacion = $idCot2)");
              }
                $archivoFactura =  time()."_".$file2->getClientOriginalName();
        \Storage::disk('local')->put($archivoFactura,  \File::get($file2));
            }
//Comprobante de Pgo
            $file3 = $request->file('archivoPago');
             if($file3!=""){
        $archivoPago =  time()."_".$file3->getClientOriginalName();
        \Storage::disk('local')->put($archivoPago,  \File::get($file3));
            }
//XML
            $file4 = $request->file('xmlFactura');
             if($file4!=""){
        $xmlFactura =  time()."_".$file4->getClientOriginalName();
        \Storage::disk('local')->put($xmlFactura,  \File::get($file4));
            }
//Remision            
            $file5 = $request->file('archivoRemision');
             if($file5!=""){
        $archivoRemision =  time()."_".$file5->getClientOriginalName();
        \Storage::disk('local')->put($archivoRemision,  \File::get($file5));
            }
//Otro           
            $file6 = $request->file('archivoOtro');
             if($file6!=""){
        $archivoOtro =  time()."_".$file6->getClientOriginalName();
        \Storage::disk('local')->put($archivoOtro,  \File::get($file6));
            }
//adenda           
            $file7 = $request->file('archivoAdenda');
             if($file7!=""){
                $archivoAdenda =  time()."_".$file7->getClientOriginalName();
                \Storage::disk('local')->put($archivoAdenda,  \File::get($file7));
              
             }
//anexo1           
            $file8 = $request->file('anexo1');
             if($file8!=""){
                $anexo1 =  time()."_".$file8->getClientOriginalName();
                \Storage::disk('local')->put($anexo1,  \File::get($file8));
              
             }
//anexo2           
            $file9 = $request->file('anexo2');
             if($file9!=""){
                $anexo2 =  time()."_".$file9->getClientOriginalName();
                \Storage::disk('local')->put($anexo2,  \File::get($file9));
              
             }

//anexo3           
            $file10 = $request->file('anexo3');
            if($file10!=""){
               $anexo3 =  time()."_".$file10->getClientOriginalName();
               \Storage::disk('local')->put($anexo3,  \File::get($file10));
             
            }
//anexo4           
            $file11 = $request->file('anexo4');
             if($file11!=""){
                $anexo4 =  time()."_".$file11->getClientOriginalName();
                \Storage::disk('local')->put($anexo4,  \File::get($file11));
              
             }
//archivo5           
            $file12 = $request->file('anexo5');
            if($file12!=""){
               $anexo5 =  time()."_".$file12->getClientOriginalName();
               \Storage::disk('local')->put($anexo5,  \File::get($file12));
             
            }
//archivo6           
            $file13 = $request->file('anexo6');
            if($file13!=""){
               $anexo6 =  time()."_".$file13->getClientOriginalName();
               \Storage::disk('local')->put($anexo6,  \File::get($file13));
             
            }
//archivo7           
            $file14 = $request->file('anexo7');
            if($file14!=""){
               $anexo7 =  time()."_".$file14->getClientOriginalName();
               \Storage::disk('local')->put($anexo7,  \File::get($file14));
             
            }
//archivo8           
            $file15 = $request->file('anexo8');
            if($file15!=""){
               $anexo8 =  time()."_".$file15->getClientOriginalName();
               \Storage::disk('local')->put($anexo8,  \File::get($file15));
             
            }   
//archivo9           
            $file16 = $request->file('anexo9');
            if($file16!=""){
               $anexo9 =  time()."_".$file16->getClientOriginalName();
               \Storage::disk('local')->put($anexo9,  \File::get($file16));
             
            }      
//archivo10           
            $file17 = $request->file('anexo10');
            if($file17!=""){
               $anexo10 =  time()."_".$file17->getClientOriginalName();
               \Storage::disk('local')->put($anexo10,  \File::get($file17));
             
            }                                       

            $fac = facturas::find($idFactura);
            $fac-> idServicios = $request-> idServicios;
            $fac-> tipoServicio = $request-> tipoServicio;
            $fac-> idSucursal = $request-> idSucursal;
            $fac-> idCotizacion = $request-> idCotizacion;
            $fac-> nombreEmpresa = $request-> nombreEmpresa;
            $fac-> numeroFactura = $request-> numeroFactura;
            $fac-> fechaVencimiento = $request-> fechaVencimiento;
            $fac-> idb = $request-> idb;
            $fac-> montoFactura = $request-> montoFactura;
            $fac-> ivaFactura = $request-> ivaFactura;
            $fac-> retencionIva = $request-> retencionIva;
            $fac-> totalConIva = $request-> totalConIva;
            $fac-> periodoPago = $request-> periodoPago;
            $fac-> fechaFactura = $request-> fechaFactura;
            $fac-> fechaPago = $request-> fechaPago;
            $fac-> tipoMoneda = $request-> tipoMoneda;
            $fac-> idc = $request-> idc;
            $fac-> cambioFactura = $request-> cambioFactura;
            $fac-> montoPesos = $request-> montoPesos;
            $fac-> saldo = $request-> saldo;
            $fac-> cambioReal = $request-> cambioReal;
            $fac-> observacionesFactura = $request-> observacionesFactura;
            $fac-> ordenCompra = $request-> ordenCompra;
            $fac-> factura = $request-> factura;
            $fac-> comprobantePago = $request-> comprobantePago;
            $fac-> metodoPago = $request-> metodoPago;
            $fac-> estatusEntrega = $request-> estatusEntrega;
            $fac-> estatusPortal = $request-> estatusPortal;
            $fac-> estatusPago = $request-> estatusPago;
            $fac-> cashFlow = $request-> cashFlow;
            $fac-> numeroRemision = $request-> numeroRemision;
            $fac-> numeroEntrada = $request-> numeroEntrada;
            $fac-> complementoPago = $request-> complementoPago;
            $fac-> numeroDocumento = $request-> numeroDocumento;
            $fac-> adenda = $request-> adenda;
            $fac-> numeroAnexo1 = $request-> numeroAnexo1;
            $fac-> numeroAnexo2 = $request-> numeroAnexo2;
            $fac-> numeroAnexo3 = $request-> numeroAnexo3;
            $fac-> numeroAnexo4 = $request-> numeroAnexo4;
            $fac-> numeroAnexo5 = $request-> numeroAnexo5;
            $fac-> numeroAnexo6 = $request-> numeroAnexo6;
            $fac-> numeroAnexo7 = $request-> numeroAnexo7;
            $fac-> numeroAnexo8 = $request-> numeroAnexo8;
            $fac-> numeroAnexo9 = $request-> numeroAnexo9;
            $fac-> numeroAnexo10 = $request-> numeroAnexo10;

            
            if($file!=""){
                        $fac-> archivo = $archivo;
                  }
            if($file2!=""){
                        $fac-> archivoFactura = $archivoFactura;
                  }
            if($file3!=""){
                        $fac-> archivoPago = $archivoPago;
                  } 
            if($file4!=""){
                        $fac-> xmlFactura = $xmlFactura;
                  }
            if($file5!=""){
                        $fac-> archivoRemision = $archivoRemision;
                  }    
            if($file6!=""){
                        $fac-> archivoOtro = $archivoOtro;
                  }
            if($file7!=""){
                    $fac-> archivoAdenda = $archivoAdenda;
                  }  
            if($file8!=""){
                    $fac-> anexo1 = $anexo1;
                  } 
            if($file9!=""){
                    $fac-> anexo2 = $anexo2;
                  } 
            if($file10!=""){
                    $fac-> anexo3 = $anexo3;
                  } 
            if($file11!=""){
                    $fac-> anexo4 = $anexo4;
                  } 
            if($file12!=""){
                    $fac-> anexo5 = $anexo5;
                  }  
            if($file13!=""){
                    $fac-> anexo6 = $anexo6;
                  }             
            if($file14!=""){
                    $fac-> anexo7 = $anexo7;
                  }  
            if($file15!=""){
                    $fac-> anexo8 = $anexo8;
                  }
            if($file16!=""){
                    $fac-> anexo9 = $anexo9;
                  }   
            if($file17!=""){
                    $fac-> anexo10 = $anexo10;
                  }   

            //Subir Archivos
            $fac-> save();


            $consultaFactura = \DB::select("SELECT v.archivoFactura FROM facturas AS v Where idFactura = $idFactura ");                        
            if ($factura != "" ||  $consultaFactura[0]->archivoFactura != "Sin archivo") {
              $cambiandoEstatusAcotizacion = \DB::UPDATE("UPDATE cotizaciones SET estatus='Facturada' WHERE idCotizacion = ? ",[$idCotizacion]);
              $cambiarEstatus = \DB::select("SELECT idEquipos FROM detallereparaciones WHERE idCotizacion = ? ",[$idCotizacion]);
              $cuantasEquipos = count($cambiarEstatus);

              for ($i=0; $i < $cuantasEquipos; $i++) {
                $cambiandoEstatusEquipos = \DB::UPDATE("UPDATE recepcionEquipos SET estatusEntrega='Facturado' WHERE idEquipos= ?",[$cambiarEstatus[$i]->idEquipos]);
              }
            }

            $proceso='La modificación del servicio fue exitoso';
            $mensaje="Servicio actualizado correctamente";
            return view ('mensajeFactura')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);

        }

          
        public function reporteFacturacion(Request $request){
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
               
              /*$servi = \DB::SELECT("SELECT f.idfactura, f.idservicios, f.numerofactura,obtenclavecotizacion(f.idfactura),'Venta Equipo','Servicio' AS Tiposervicio,
              f.fechaFactura,f.fechaPago,c.razonSocial,s.sucursal,
             f.estatusEntrega,f.estatusPortal,f.estatusPago,f.cashFlow, f.montoFactura,f.totalconiva,
             f.idcotizacion, f.archivo AS archivoorden, f.archivofactura,f.archivoPago,f.xmlFactura,f.archivoRemision,f.archivoAdenda,f.activo
             FROM facturas AS f
             INNER JOIN clientes AS c ON c.idc = f.idc 
             INNER JOIN sucursales AS s ON s.idsucursal = f.idsucursal ORDER BY f.created_at DESC ");*/

             $servi = \DB::SELECT("SELECT t.idfactura,t.idservicios,t.numerofactura,SUBSTR(t.cotizacion,3,255) AS foliocotizacion,t.tiposervicio,t.fechafactura,t.fechapago,t.razonsocial,t.sucursal,
             t.estatusentrega,t.estatusportal,t.cashflow,CONCAT(t.tipomoneda,' $ ',FORMAT(t.montofactura,2)) AS montofactura ,CONCAT(t.tipomoneda,' $ ',FORMAT(t.totalconiva,2)) AS totalconiva,t.idcotizacion,t.archivoorden AS archivoorden,t.archivofactura,t.archivopago,t.xmlfactura,
             t.archivoremision,t.archivoadenda,t.activo,t.created_at,t.estatuspago,t.servicioActivo
             FROM
             (SELECT f.idfactura, f.idservicios, f.numerofactura,obtenclavecotizacion(f.idfactura) AS cotizacion,SUBSTR(obtenclavecotizacion(f.idfactura),1,1) AS tiposervicio,
                           f.fechafactura,f.fechapago,c.razonsocial,s.sucursal,f.tipomoneda,
                          f.estatusentrega,f.estatusportal,f.estatuspago,f.cashflow, f.montoFactura,f.totalconiva,f.created_at,f.servicioActivo,
                          f.idcotizacion, f.archivo AS archivoorden, f.archivofactura,f.archivopago,f.xmlfactura,f.archivoremision,f.archivoadenda,f.activo
                          FROM facturas AS f
                          INNER JOIN clientes AS c ON c.idc = f.idc 
                          INNER JOIN sucursales AS s ON s.idsucursal = f.idsucursal ORDER BY f.created_at DESC) AS t");
              
                //return $servi;
                return view('reporteFacturacion')
                ->with('servi', $servi);                
            }


        }


        public function reporteFacturas(Request $request){
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
               
                return view('reporteFacturas');                
            }
        }
    public function getDataFacturas(Request $request) {
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

        $aCargo = \DB::select("SELECT iduAsignado FROM usuariosacargos WHERE idu =$sidu");
          if (count($aCargo) > 0) {
            for ($i=0; $i < count($aCargo) ; $i++) {
              $usuariosReporte[$i] = $aCargo[$i]->iduAsignado;
            }
            array_push($usuariosReporte,$sidu);
          }else{
            $usuariosReporte[0]=$sidu;
          }

        $anexGrid = new anexGrid();
        $idServicios = '';
        $estatusEntrega = '';
        $estatusPortal= '';
        $estatusPago = '';
        $cashFlow = '';
        $cliente = '';
        $sucursal = '';
        $numeroFactura = '';
        $numeroCotizacion = '';
        $numeroServicio = '';


        foreach($anexGrid->filtros as $f)
        {
          if($f['columna'] == 'idServicios' && $f['valor'] != ''){
            $idServicios = $f['valor'];
          }
          if($f['columna'] == 'numeroFactura' && $f['valor'] != ''){
            $numeroFactura = $f['valor'];
          }
          if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
            $cliente = $f['valor'];
          }
          if($f['columna'] == 'razonSocial' && $f['valor'] == ''){
            $cliente = '';
          }

          if($f['columna'] == 'sucursales.sucursal' && $f['valor'] != ''){
            $sucursal = $f['valor'];
          }
          if($f['columna'] == 'sucursales.sucursal' && $f['valor'] == ''){
            $sucursal = '';
          }             

          if($f['columna'] == 'estatusEntrega'&& $f['valor'] != ''){
            $estatusEntrega = $f['valor'];
          } 
          if($f['columna'] == 'estatusEntrega' && $f['valor'] == '') {
            $estatusEntrega = '';
          }
          
          if($f['columna'] == 'estatusPortal'&& $f['valor'] != ''){
            $estatusPortal = $f['valor'];
          }
          if ($f['columna'] == 'estatusPortal' && $f['valor'] == '') {
            $estatusPortal = '';
          }
          
          if($f['columna'] == 'estatusPago'&& $f['valor'] != ''){
            $estatusPago = $f['valor'];
          }
          if ($f['columna'] == 'estatusPago' && $f['valor'] == '') {
            $estatusPago = '';
          }

          if($f['columna'] == 'cashFlow'&& $f['valor'] != ''){
            $cashFlow = $f['valor'];
          }
          if ($f['columna'] == 'cashFlow' && $f['valor'] == '') {
            $cashFlow = '';
          }
          if ($f['columna'] == 'numeroServicio' && $f['valor'] == '') {
            $numeroServicio = '';
          }
          if($f['columna'] == 'numeroServicio'&& $f['valor'] != ''){
            $numeroServicio = $f['valor'];
          }
          
          if($f['columna'] == 'numeroCotizacion' && $f['valor'] == ''){
            $numeroCotizacion = '';
          }
          if($f['columna'] == 'numeroCotizacion' && $f['valor'] != ''){
            $numeroCotizacion = $f['valor'];
          }
        }

        $order;
        $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';
          
          if ($stipo == 'Administrador' || $stipo == 'Compras') { 

            $registros = DB::table('facturas')            
              ->select(
                  'idServicios',
                  'numeroFactura',
                  'facturas.idc',
                  'facturas.idSucursal',
                  // DB::raw('cotizaciones.numeroCotizacion AS numeroCoti'),
                  // DB::raw('IF(cotizacionServicios.numeroCotizacion is null,"-",cotizacionServicios.numeroCotizacion) AS numeroSer'),
                  DB::raw('IF(facturas.tipoServicio="Soporte Técnico",cotizacionServicios.numeroCotizacion,"-") AS numeroSer'),
                  DB::raw('IF(facturas.tipoServicio!="Soporte Técnico",cotizaciones.numeroCotizacion,"-") AS numeroCoti'),
                  DB::raw('DATE_FORMAT(fechaFactura,"%d %b %Y") AS fecha'),
                  DB::raw('DATE_FORMAT(fechaPago,"%d %b %Y") AS fechap'),
                  DB::raw('COUNT(asignacionesdetalles.`idu`) AS canti'),
                  'clientes.razonSocial',
                  'sucursales.sucursal',
                  'estatusEntrega',
                  'estatusPortal',
                  'estatusPago',
                  'cashFlow',
                  'tipoServicio',
                  DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(montoFactura,2)) AS monto'),
                  'archivo',
                  'archivoFactura',
                  'archivoPago',
                  DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(totalConIva,2)) AS total'),
                  'archivo',
                  'archivoFactura',
                  'archivoPago',
                  'xmlFactura',
                  'archivoRemision',
                  'archivoAdenda',
                  'facturas.idFactura',
                  'facturas.activo',
                  'facturas.servicioActivo',
                  'cotizaciones.archivoCotizacion',
                  'cotizacionServicios.archivoCotizacion AS archivoCotizacionServicios',
                  'cotizaciones.idCotizacion',
                  'cotizacionServicios.idCotizacionServicios'
              )
              ->leftJoin('clientes', 'facturas.idc', '=', 'clientes.idc')  
              ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
              ->leftJoin('asignacionesdetalles', 'facturas.idFactura', '=', 'asignacionesdetalles.idFactura')
              ->leftJoin('cotizaciones', 'facturas.idCotizacion', '=', 'cotizaciones.idCotizacion')
              ->leftJoin('cotizacionServicios', 'facturas.idCotizacion', '=', 'cotizacionServicios.idCotizacionServicios')
              ->where('idServicios', '>', 0)
              ->where('idServicios', 'like', '%'.$idServicios.'%')
              ->where('numeroFactura', 'like', '%'.$numeroFactura.'%')
              ->where('estatusPago', 'like', '%'.$estatusPago.'%')
              ->where('estatusPortal', 'like', '%'.$estatusPortal.'%')
              ->where('estatusEntrega', 'like', '%'.$estatusEntrega.'%')
              ->where('cashFlow', 'like', '%'.$cashFlow.'%')
              ->where('razonSocial', 'like', '%'.$cliente.'%')
              ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')

              // ->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
              // ->orwhere('cotizacionServicios.numeroCotizacion', 'like', '%'.$numeroServicio.'%')
              // ->orwherenull('cotizaciones.numeroCotizacion','cotizacionServicios.numeroCotizacion')                    


              ->where(function($query) {
                $anexGrid = new anexGrid();
                $numeroCotizacion='';
                $conNull = false;
                foreach($anexGrid->filtros as $f)
                  {
                    if($f['columna'] == 'numeroCotizacion' && $f['valor'] != ''){
                      $numeroCotizacion = $f['valor'];
                      $conNull = true;
                    }
                  }

                  if($conNull){
                    $query->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
                    ->where('cotizaciones.numeroCotizacion', '!=', '');
                  }else{
                    $query->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
                    ->orwherenull('cotizaciones.numeroCotizacion');
                  }
              })

              ->where(function($query) {
                $anexGrid = new anexGrid();
                $numeroServicio='';
                $conNull = false;
                foreach($anexGrid->filtros as $f)
                  {
                    if($f['columna'] == 'numeroServicio' && $f['valor'] != ''){
                      $numeroServicio = $f['valor'];
                      $conNull = true;
                    }
                  }
                if($conNull){
                  $query->where('cotizacionServicios.numeroCotizacion', 'like', '%'.$numeroServicio.'%')
                  ->where('cotizacionServicios.numeroCotizacion', '!=', '');
                }else{
                  $query->where('cotizacionServicios.numeroCotizacion', 'like', '%'.$numeroServicio.'%')
                  ->orwherenull('cotizacionServicios.numeroCotizacion');
                }
              })



              ->groupBy('facturas.idFactura')
              ->orderBy($anexGrid->columna, $order)
              ->skip($anexGrid->pagina)
              ->take($anexGrid->limite)
            ->get();

            $total = DB::table('facturas')            
              ->select('idServicios','numeroFactura','facturas.idc','facturas.idSucursal',
              // DB::raw('cotizaciones.numeroCotizacion AS numeroCoti'),
              // DB::raw('IF(cotizacionServicios.numeroCotizacion is null,"-",cotizacionServicios.numeroCotizacion) AS numeroSer'),
              DB::raw('IF(facturas.tipoServicio="Soporte Técnico",cotizacionServicios.numeroCotizacion,"-") AS numeroSer'),
              DB::raw('IF(facturas.tipoServicio!="Soporte Técnico",cotizaciones.numeroCotizacion,"-") AS numeroCoti'),
              DB::raw('DATE_FORMAT(fechaFactura,"%d %b %Y") AS fecha'),
              DB::raw('DATE_FORMAT(fechaPago,"%d %b %Y") AS fechap'),
              DB::raw('COUNT(asignacionesdetalles.`idu`) AS canti'),
              'clientes.razonSocial','sucursales.sucursal','estatusEntrega','estatusPortal','estatusPago','cashFlow','tipoServicio',
              DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(montoFactura,2)) AS monto'),'archivo','archivoFactura','archivoPago',
              DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(totalConIva,2)) AS total'),
              'archivo','archivoFactura','archivoPago','xmlFactura','archivoRemision','archivoAdenda','facturas.idFactura','facturas.activo','facturas.servicioActivo','cotizaciones.archivoCotizacion','cotizacionServicios.archivoCotizacion AS archivoCotizacionServicios','cotizaciones.idCotizacion','cotizacionServicios.idCotizacionServicios')
              ->leftJoin('clientes', 'facturas.idc', '=', 'clientes.idc')  
              ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
              ->leftJoin('asignacionesdetalles', 'facturas.idFactura', '=', 'asignacionesdetalles.idFactura')
              ->leftJoin('cotizaciones', 'facturas.idCotizacion', '=', 'cotizaciones.idCotizacion')
              ->leftJoin('cotizacionServicios', 'facturas.idCotizacion', '=', 'cotizacionServicios.idCotizacionServicios')
              ->where('idServicios', '>', 0)
              ->where('idServicios', 'like', '%'.$idServicios.'%')
              ->where('numeroFactura', 'like', '%'.$numeroFactura.'%')
              ->where('estatusPago', 'like', '%'.$estatusPago.'%')
              ->where('estatusPortal', 'like', '%'.$estatusPortal.'%')
              ->where('estatusEntrega', 'like', '%'.$estatusEntrega.'%')
              ->where('cashFlow', 'like', '%'.$cashFlow.'%')
              ->where('razonSocial', 'like', '%'.$cliente.'%')
              ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
              ->where(function($query) {
                $anexGrid = new anexGrid();
                $numeroCotizacion='';
                $conNull = false;
                foreach($anexGrid->filtros as $f)
                  {
                    if($f['columna'] == 'numeroCotizacion' && $f['valor'] != ''){
                      $numeroCotizacion = $f['valor'];
                      $conNull = true;
                    }
                  }

                  if($conNull){
                    $query->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
                    ->where('cotizaciones.numeroCotizacion', '!=', '');
                  }else{
                    $query->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
                    ->orwherenull('cotizaciones.numeroCotizacion');
                  }
              })

              ->where(function($query) {
                $anexGrid = new anexGrid();
                $numeroServicio='';
                $conNull = false;
                foreach($anexGrid->filtros as $f)
                  {
                    if($f['columna'] == 'numeroServicio' && $f['valor'] != ''){
                      $numeroServicio = $f['valor'];
                      $conNull = true;
                    }
                  }
                if($conNull){
                  $query->where('cotizacionServicios.numeroCotizacion', 'like', '%'.$numeroServicio.'%')
                  ->where('cotizacionServicios.numeroCotizacion', '!=', '');
                }else{
                  $query->where('cotizacionServicios.numeroCotizacion', 'like', '%'.$numeroServicio.'%')
                  ->orwherenull('cotizacionServicios.numeroCotizacion');
                }
              })->count();

          }else{  
            
            // * Consultas para vendedores
              
            $registros = DB::table('facturas')            
            ->select('idServicios','numeroFactura','facturas.idc','facturas.idSucursal',
                // DB::raw('cotizaciones.numeroCotizacion AS numeroCoti'),
                // DB::raw('IF(cotizacionServicios.numeroCotizacion is null,"-",cotizacionServicios.numeroCotizacion) AS numeroSer'),
                DB::raw('IF(facturas.tipoServicio="Soporte Técnico",cotizacionServicios.numeroCotizacion,"-") AS numeroSer'),
                DB::raw('IF(facturas.tipoServicio!="Soporte Técnico",cotizaciones.numeroCotizacion,"-") AS numeroCoti'),
                DB::raw('DATE_FORMAT(fechaFactura,"%d %b %Y") AS fecha'),
                DB::raw('DATE_FORMAT(fechaPago,"%d %b %Y") AS fechap'),
                DB::raw('COUNT(asignacionesdetalles.`idu`) AS canti'),
                'clientes.razonSocial',
                'sucursales.sucursal',
                'estatusEntrega',
                'estatusPortal',
                'estatusPago',
                'cashFlow',
                'tipoServicio',
                DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(montoFactura,2)) AS monto'),'archivo','archivoFactura','archivoPago',
                DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(totalConIva,2)) AS total'),
                'archivo',
                'archivoFactura',
                'archivoPago',
                'xmlFactura',
                'archivoRemision',
                'archivoAdenda',
                'facturas.idFactura',
                'facturas.activo',
                'facturas.servicioActivo',
                'cotizaciones.archivoCotizacion',
                'cotizacionServicios.archivoCotizacion AS archivoCotizacionServicios',
                'cotizaciones.idCotizacion',
                'cotizacionServicios.idCotizacionServicios'
            )
            ->leftJoin('clientes', 'facturas.idc', '=', 'clientes.idc')  
            ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
            ->leftJoin('asignacionesdetalles', 'facturas.idFactura', '=', 'asignacionesdetalles.idFactura')
            ->leftJoin('cotizaciones', 'facturas.idCotizacion', '=', 'cotizaciones.idCotizacion')
            ->leftJoin('cotizacionServicios', 'facturas.idCotizacion', '=', 'cotizacionServicios.idCotizacionServicios')
            ->where('idServicios', '>', 0)
            ->where('idServicios', 'like', '%'.$idServicios.'%')
            ->where('numeroFactura', 'like', '%'.$numeroFactura.'%')
            ->where('estatusPago', 'like', '%'.$estatusPago.'%')
            ->where('estatusPortal', 'like', '%'.$estatusPortal.'%')
            ->where('estatusEntrega', 'like', '%'.$estatusEntrega.'%')
            ->where('cashFlow', 'like', '%'.$cashFlow.'%')
            ->where('razonSocial', 'like', '%'.$cliente.'%')
            ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')  
            // ->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            // ! ->whereIn('cotizaciones.idu',$usuariosReporte)   /////////AGREGADO EL 28-12-21////////////
            // ! ->orwhereIn('cotizacionServicios.idu',$usuariosReporte)   /////////AGREGADO EL 14-01-22////////////

            ->where(function($query) {
              $anexGrid = new anexGrid();
              $numeroCotizacion='';
              $conNull = false;
              foreach($anexGrid->filtros as $f)
                {
                  if($f['columna'] == 'numeroCotizacion' && $f['valor'] != ''){
                    $numeroCotizacion = $f['valor'];
                    $conNull = true;
                  }
                }

                if($conNull){
                  $query->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
                  ->where('cotizaciones.numeroCotizacion', '!=', '');
                }else{
                  $query->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
                  ->orwherenull('cotizaciones.numeroCotizacion');
                }
            })

            ->where(function($query) {
              $anexGrid = new anexGrid();
              $numeroServicio='';
              $conNull = false;
              foreach($anexGrid->filtros as $f)
                {
                  if($f['columna'] == 'numeroServicio' && $f['valor'] != ''){
                    $numeroServicio = $f['valor'];
                    $conNull = true;
                  }
                }
              if($conNull){
                $query->where('cotizacionServicios.numeroCotizacion', 'like', '%'.$numeroServicio.'%')
                ->where('cotizacionServicios.numeroCotizacion', '!=', '');
              }else{
                $query->where('cotizacionServicios.numeroCotizacion', 'like', '%'.$numeroServicio.'%')
                ->orwherenull('cotizacionServicios.numeroCotizacion');
              }
            })
            
      /////////COMENTADO EL 28-12-21 DE AQUI////////////
          // ->where('asignacionesdetalles.idu', '=', $sidu)                 
              // ->whereIn('asignacionesdetalles.idu',  function($query){
              //   $sidu = Session::get('sesionidu');
              //   $query->select('iduAsignado')
              //   ->from('usuariosacargos as uc')
              //   ->where('uc.idu','=',$sidu)
              //   ->orwhere('asignacionesdetalles.idu', '=', $sidu)  ;
              // })
      /////////COMENTADO EL 28-12-21 HASTA AQUI :)////////////
                            
              // ->whereIn('facturas.idFactura',  function($query){
              // $sidu = Session::get('sesionidu');
              //  $query->select('facturas.idFactura')
            //   ->from('asignacionesdetalles')
            //   ->where('idu','=',$sidu);
            // })
              ->groupBy('facturas.idFactura')
              ->orderBy($anexGrid->columna, $order)
              ->skip($anexGrid->pagina)
              ->take($anexGrid->limite)
            ->get();
                
            $total = DB::table('facturas')            
            ->select('idServicios','numeroFactura','facturas.idc','facturas.idSucursal',
            // DB::raw('cotizaciones.numeroCotizacion AS numeroCoti'),
            // DB::raw('IF(cotizacionServicios.numeroCotizacion is null,"-",cotizacionServicios.numeroCotizacion) AS numeroSer'),
            DB::raw('IF(facturas.tipoServicio="Soporte Técnico",cotizacionServicios.numeroCotizacion,"-") AS numeroSer'),
            DB::raw('IF(facturas.tipoServicio!="Soporte Técnico",cotizaciones.numeroCotizacion,"-") AS numeroCoti'),
            DB::raw('DATE_FORMAT(fechaFactura,"%d %b %Y") AS fecha'),
            DB::raw('DATE_FORMAT(fechaPago,"%d %b %Y") AS fechap'),
            DB::raw('COUNT(asignacionesdetalles.`idu`) AS canti'),
            'clientes.razonSocial','sucursales.sucursal','estatusEntrega','estatusPortal','estatusPago','cashFlow','tipoServicio',
            DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(montoFactura,2)) AS monto'),'archivo','archivoFactura','archivoPago',
            DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(totalConIva,2)) AS total'),
            'archivo','archivoFactura','archivoPago','xmlFactura','archivoRemision','archivoAdenda','facturas.idFactura','facturas.activo','facturas.servicioActivo','cotizaciones.archivoCotizacion','cotizacionServicios.archivoCotizacion AS archivoCotizacionServicios','cotizaciones.idCotizacion','cotizacionServicios.idCotizacionServicios')
            ->leftJoin('clientes', 'facturas.idc', '=', 'clientes.idc')  
            ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
            ->leftJoin('asignacionesdetalles', 'facturas.idFactura', '=', 'asignacionesdetalles.idFactura')
            ->leftJoin('cotizaciones', 'facturas.idCotizacion', '=', 'cotizaciones.idCotizacion')
            ->leftJoin('cotizacionServicios', 'facturas.idCotizacion', '=', 'cotizacionServicios.idCotizacionServicios')
            ->where('idServicios', '>', 0)
            ->where('idServicios', 'like', '%'.$idServicios.'%')
            ->where('numeroFactura', 'like', '%'.$numeroFactura.'%')
            ->where('estatusPago', 'like', '%'.$estatusPago.'%')
            ->where('estatusPortal', 'like', '%'.$estatusPortal.'%')
            ->where('estatusEntrega', 'like', '%'.$estatusEntrega.'%')
            ->where('cashFlow', 'like', '%'.$cashFlow.'%')
            ->where('razonSocial', 'like', '%'.$cliente.'%')
            ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')                 
            // ->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            // ->whereIn('cotizaciones.idu',$usuariosReporte) /////////AGREGADO EL 28-12-21////////////
            // ->orwhereIn('cotizacionServicios.idu',$usuariosReporte)   /////////AGREGADO EL 14-01-22////////////
            
            ->where(function($query) {
              $anexGrid = new anexGrid();
              $numeroCotizacion='';
              $conNull = false;
              foreach($anexGrid->filtros as $f)
                {
                  if($f['columna'] == 'numeroCotizacion' && $f['valor'] != ''){
                    $numeroCotizacion = $f['valor'];
                    $conNull = true;
                  }
                }

                if($conNull){
                  $query->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
                  ->where('cotizaciones.numeroCotizacion', '!=', '');
                }else{
                  $query->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
                  ->orwherenull('cotizaciones.numeroCotizacion');
                }
            })

            ->where(function($query) {
              $anexGrid = new anexGrid();
              $numeroServicio='';
              $conNull = false;
              foreach($anexGrid->filtros as $f)
                {
                  if($f['columna'] == 'numeroServicio' && $f['valor'] != ''){
                    $numeroServicio = $f['valor'];
                    $conNull = true;
                  }
                }
              if($conNull){
                $query->where('cotizacionServicios.numeroCotizacion', 'like', '%'.$numeroServicio.'%')
                ->where('cotizacionServicios.numeroCotizacion', '!=', '');
              }else{
                $query->where('cotizacionServicios.numeroCotizacion', 'like', '%'.$numeroServicio.'%')
                ->orwherenull('cotizacionServicios.numeroCotizacion');
              }
            })

        /////////COMENTADO EL 28-12-21 DE AQUI////////////
              // ->whereIn('facturas.idFactura',  function($query){
              //   $sidu = Session::get('sesionidu');
              //   $query->select('idFactura')
              //   ->from('asignacionesdetalles')
              //   ->where('idu','=',$sidu);
              // })

        /////////COMENTADO EL 28-12-21 HASTA AQUI ////////////
            ->count();

          }
          
          $data = array(
            'total' => $total,
            'data' => $registros
          );

          return response()->json($data, 200);
      }
    }
            public function reporteFacturasAbajo(Request $request){
              $sname = Session::get('sesionname');
              $sidu = Session::get('sesionidu');
              $spat = Session::get('sesionpaterno');
              $smat = Session::get('sesionmaterno');
              $stipo = Session::get('sesiontipo');
              
              $fechaInicio = $request->fechaInicio;
              $fechaFin = $request->fechaFin;
              //   if($stipo == 'Administrador'){
              //     $consulta = \DB::select("SELECT v.idServicios,v.estatusEntrega,
              //     v.estatusPago, v.estatusPortal, v.cashFlow,
              //     v.archivoRemision, v.archivo,v.archivoFactura,v.archivoPago,
              //     v.xmlFactura, v.numeroFactura,v.idFactura,v.activo,v.fechaFactura,
              //     DATE_FORMAT(v.fechaFactura,'%d %b %Y') AS fecha, 
              //     DATE_FORMAT(v.fechaPago,'%d %b %Y') AS fechap, v.montoFactura,
              //     v.tipoMoneda,v.archivoAdenda,s.sucursal AS suc, c.razonSocial AS cli 
              //     FROM facturas AS v
              //     LEFT JOIN sucursales AS s ON s.idSucursal = v.idSucursal
              //     LEFT JOIN clientes AS c ON c.idc = v.idc
		          //     WHERE fechaFactura>=? AND fechaFactura<=?",[$fechaFactura,$fechaFin]);
              // }else{
              //     $consulta = \DB::select("SELECT v.idServicios,v.estatusEntrega,
              //     v.estatusPago, v.estatusPortal, v.cashFlow,
              //     v.archivoRemision, v.archivo,v.archivoFactura,v.archivoPago,
              //     v.xmlFactura, v.numeroFactura,v.idFactura,v.activo,v.fechaFactura,
              //     DATE_FORMAT(v.fechaFactura,'%d %b %Y') AS fecha, 
              //     DATE_FORMAT(v.fechaPago,'%d %b %Y') AS fechap, v.montoFactura,
              //     v.tipoMoneda,v.archivoAdenda,s.sucursal AS suc, c.razonSocial AS cli 
              //     FROM facturas AS v
              //     LEFT JOIN sucursales AS s ON s.idSucursal = v.idSucursal
              //     LEFT JOIN clientes AS c ON c.idc = v.idc
		          //     WHERE fechaFactura>=? AND fechaFactura<=? AND idFactura IN(SELECT idFactura AS fac FROM asignacionesdetalles WHERE idu=?)",[$fechaFactura,$fechaFin,$sidu]);
              // }
            
              // $cuantos = count($consulta);
              return view ('reporteFacturasAbajo')
                ->with('fechaInicio',$fechaInicio)
                ->with('fechaFin',$fechaFin);
            }

        
        public function getDataFacturasAbajo(Request $request) {
              
              $sname = Session::get('sesionname');
              $sidu = Session::get('sesionidu');
              $spat = Session::get('sesionpaterno');
              $smat = Session::get('sesionmaterno');
              $stipo = Session::get('sesiontipo');
              
              $fechaInicio = $request->fechaInicio;
              $fechaFin = $request->fechaFin;
               
              if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
                  Session::flash('error', 'Es necesario logearse antes de continuar');
                  return redirect()->route('login');
              }
              else{

                $aCargo = \DB::select("SELECT iduAsignado FROM usuariosacargos WHERE idu =$sidu");
                if (count($aCargo) > 0) {
                  for ($i=0; $i < count($aCargo) ; $i++) {
                    $usuariosReporte[$i] = $aCargo[$i]->iduAsignado;
                  }
                  array_push($usuariosReporte,$sidu);
                }else{
                  $usuariosReporte[0]=$sidu;
                }
  
              $anexGrid = new anexGrid();
              $idServicios = '';
              $estatusEntrega = '';
              $estatusPortal= '';
              $estatusPago = '';
              $cashFlow = '';
              $cliente = '';
              $sucursal = '';
              $numeroFactura = '';
              $numeroCotizacion = '';


              foreach($anexGrid->filtros as $f)
              {
                if($f['columna'] == 'idServicios' && $f['valor'] != ''){
                  $idServicios = $f['valor'];
                }
                if($f['columna'] == 'numeroFactura' && $f['valor'] != ''){
                  $numeroFactura = $f['valor'];
                }
                if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
                  $cliente = $f['valor'];
                }
                if($f['columna'] == 'razonSocial' && $f['valor'] == ''){
                  $cliente = '';
                }

                if($f['columna'] == 'sucursales.sucursal' && $f['valor'] != ''){
                  $sucursal = $f['valor'];
                }
                if($f['columna'] == 'sucursales.sucursal' && $f['valor'] == ''){
                  $sucursal = '';
                }             

                if($f['columna'] == 'estatusEntrega'&& $f['valor'] != ''){
                  $estatusEntrega = $f['valor'];
                } 
                if($f['columna'] == 'estatusEntrega' && $f['valor'] == '') {
                  $estatusEntrega = '';
                }
                
                if($f['columna'] == 'estatusPortal'&& $f['valor'] != ''){
                  $estatusPortal = $f['valor'];
                }
                if ($f['columna'] == 'estatusPortal' && $f['valor'] == '') {
                  $estatusPortal = '';
                }
                
                if($f['columna'] == 'estatusPago'&& $f['valor'] != ''){
                  $estatusPago = $f['valor'];
                }
                if ($f['columna'] == 'estatusPago' && $f['valor'] == '') {
                  $estatusPago = '';
                }

                if($f['columna'] == 'cashFlow'&& $f['valor'] != ''){
                  $cashFlow = $f['valor'];
                }
                if ($f['columna'] == 'cashFlow' && $f['valor'] == '') {
                  $cashFlow = '';
                }
                if($f['columna'] == 'numeroCotizacion'&& $f['valor'] != ''){
                  $numeroCotizacion = $f['valor'];
                }
                if ($f['columna'] == 'numeroCotizacion' && $f['valor'] == '') {
                  $numeroCotizacion = '';
                }
              }

              $order;
              $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';
                
                if ($stipo == 'Administrador') {  
  
                    $registros = DB::table('facturas')            
                      ->select('idServicios','numeroFactura','facturas.idc','facturas.idSucursal',
                      // DB::raw('cotizaciones.numeroCotizacion AS numeroCoti'),
                      // DB::raw('IF(cotizacionServicios.numeroCotizacion is null,"-",cotizacionServicios.numeroCotizacion) AS numeroSer'),
                      DB::raw('IF(facturas.tipoServicio="Soporte Técnico",cotizacionServicios.numeroCotizacion,"-") AS numeroSer'),
                      DB::raw('IF(facturas.tipoServicio!="Soporte Técnico",cotizaciones.numeroCotizacion,"-") AS numeroCoti'),
                      DB::raw('DATE_FORMAT(fechaFactura,"%d %b %Y") AS fecha'),
                      DB::raw('DATE_FORMAT(fechaPago,"%d %b %Y") AS fechap'),
                      DB::raw('COUNT(asignacionesdetalles.`idu`) AS canti'),
                      'clientes.razonSocial','sucursales.sucursal','estatusEntrega','estatusPortal','estatusPago','cashFlow','tipoServicio',
                      DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(montoFactura,2)) AS monto'),
                      DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(totalConIva,2)) AS total'),
                      'archivo','archivoFactura','archivoPago','archivo','archivoFactura','archivoPago','xmlFactura','archivoRemision','archivoAdenda','facturas.idFactura','facturas.activo','facturas.servicioActivo','cotizaciones.archivoCotizacion','cotizacionServicios.archivoCotizacion AS archivoCotizacionServicios','cotizaciones.idCotizacion','cotizacionServicios.idCotizacionServicios')
                      ->leftJoin('clientes', 'facturas.idc', '=', 'clientes.idc')  
                      ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
                      ->leftJoin('asignacionesdetalles', 'facturas.idFactura', '=', 'asignacionesdetalles.idFactura')
                      ->leftJoin('cotizaciones', 'facturas.idCotizacion', '=', 'cotizaciones.idCotizacion')
                      ->leftJoin('cotizacionServicios', 'facturas.idCotizacion', '=', 'cotizacionServicios.idCotizacionServicios')
                      ->where('idServicios', '>', 0)
                      ->where('idServicios', 'like', '%'.$idServicios.'%')
                      ->where('numeroFactura', 'like', '%'.$numeroFactura.'%')
                      ->where('estatusPago', 'like', '%'.$estatusPago.'%')
                      ->where('estatusPortal', 'like', '%'.$estatusPortal.'%')
                      ->where('estatusEntrega', 'like', '%'.$estatusEntrega.'%')
                      ->where('cashFlow', 'like', '%'.$cashFlow.'%')
                      ->where('razonSocial', 'like', '%'.$cliente.'%')
                      ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                      ->where(function($query) {
                        $anexGrid = new anexGrid();
                        $numeroCotizacion='';
                        foreach($anexGrid->filtros as $f)
                          {
                            if($f['columna'] == 'numeroCotizacion' && $f['valor'] != ''){
                              $numeroCotizacion = $f['valor'];
                            }
                          }
                        $query->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
                        ->orwherenull('cotizaciones.numeroCotizacion');
                      })
  
                      ->where(function($query) {
                        $anexGrid = new anexGrid();
                        $numeroServicio='';
                        foreach($anexGrid->filtros as $f)
                          {
                            if($f['columna'] == 'numeroServicio' && $f['valor'] != ''){
                              $numeroServicio = $f['valor'];
                            }
                          }
                        $query->where('cotizacionServicios.numeroCotizacion', 'like', '%'.$numeroServicio.'%')
                        ->orwherenull('cotizacionServicios.numeroCotizacion');
                      })
                      ->where('fechaFactura','>=',$fechaInicio)                     
                      ->where('fechaFactura','<=',$fechaFin)       
                      ->groupBy('facturas.idFactura')               
                      ->orderBy($anexGrid->columna, $order)
                      ->skip($anexGrid->pagina)
                      ->take($anexGrid->limite)
                    ->get();
                        
                    $total = DB::table('facturas')            
                      ->select('idServicios','numeroFactura','facturas.idc','facturas.idSucursal',
                      // DB::raw('cotizaciones.numeroCotizacion AS numeroCoti'),
                      // DB::raw('IF(cotizacionServicios.numeroCotizacion is null,"-",cotizacionServicios.numeroCotizacion) AS numeroSer'),
                      DB::raw('IF(facturas.tipoServicio="Soporte Técnico",cotizacionServicios.numeroCotizacion,"-") AS numeroSer'),
                      DB::raw('IF(facturas.tipoServicio!="Soporte Técnico",cotizaciones.numeroCotizacion,"-") AS numeroCoti'),
                      DB::raw('DATE_FORMAT(fechaFactura,"%d %b %Y") AS fecha'),
                      DB::raw('DATE_FORMAT(fechaPago,"%d %b %Y") AS fechap'),
                      DB::raw('COUNT(asignacionesdetalles.`idu`) AS canti'),
                      'clientes.razonSocial','sucursales.sucursal','estatusEntrega','estatusPortal','estatusPago','cashFlow','tipoServicio',
                      DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(montoFactura,2)) AS monto'),
                      DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(totalConIva,2)) AS total'),
                      'archivo','archivoFactura','archivoPago','archivo','archivoFactura','archivoPago','xmlFactura','archivoRemision','archivoAdenda','facturas.idFactura','facturas.activo','facturas.servicioActivo','cotizaciones.archivoCotizacion','cotizacionServicios.archivoCotizacion AS archivoCotizacionServicios','cotizaciones.idCotizacion','cotizacionServicios.idCotizacionServicios')
                      ->leftJoin('clientes', 'facturas.idc', '=', 'clientes.idc')  
                      ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
                      ->leftJoin('asignacionesdetalles', 'facturas.idFactura', '=', 'asignacionesdetalles.idFactura')
                      ->leftJoin('cotizaciones', 'facturas.idCotizacion', '=', 'cotizaciones.idCotizacion')
                      ->leftJoin('cotizacionServicios', 'facturas.idCotizacion', '=', 'cotizacionServicios.idCotizacionServicios')
                      ->where('idServicios', '>', 0)
                      ->where('idServicios', 'like', '%'.$idServicios.'%')
                      ->where('numeroFactura', 'like', '%'.$numeroFactura.'%')
                      ->where('estatusPago', 'like', '%'.$estatusPago.'%')
                      ->where('estatusPortal', 'like', '%'.$estatusPortal.'%')
                      ->where('estatusEntrega', 'like', '%'.$estatusEntrega.'%')
                      ->where('cashFlow', 'like', '%'.$cashFlow.'%')
                      ->where('razonSocial', 'like', '%'.$cliente.'%')
                      ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                      ->where(function($query) {
                        $anexGrid = new anexGrid();
                        $numeroCotizacion='';
                        foreach($anexGrid->filtros as $f)
                          {
                            if($f['columna'] == 'numeroCotizacion' && $f['valor'] != ''){
                              $numeroCotizacion = $f['valor'];
                            }
                          }
                        $query->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
                        ->orwherenull('cotizaciones.numeroCotizacion');
                      })
  
                      ->where(function($query) {
                        $anexGrid = new anexGrid();
                        $numeroServicio='';
                        foreach($anexGrid->filtros as $f)
                          {
                            if($f['columna'] == 'numeroServicio' && $f['valor'] != ''){
                              $numeroServicio = $f['valor'];
                            }
                          }
                        $query->where('cotizacionServicios.numeroCotizacion', 'like', '%'.$numeroServicio.'%')
                        ->orwherenull('cotizacionServicios.numeroCotizacion');
                      })
                      ->where('fechaFactura','>=',$fechaInicio)                     
                      ->where('fechaFactura','<=',$fechaFin) 
                    ->count();
  
                }else{                                               
                  
                    $registros = DB::table('facturas')            
                      ->select('idServicios','numeroFactura','facturas.idc','facturas.idSucursal',
                      // DB::raw('cotizaciones.numeroCotizacion AS numeroCoti'),
                      // DB::raw('IF(cotizacionServicios.numeroCotizacion is null,"-",cotizacionServicios.numeroCotizacion) AS numeroSer'),
                      DB::raw('IF(facturas.tipoServicio="Soporte Técnico",cotizacionServicios.numeroCotizacion,"-") AS numeroSer'),
                      DB::raw('IF(facturas.tipoServicio!="Soporte Técnico",cotizaciones.numeroCotizacion,"-") AS numeroCoti'),
                      DB::raw('DATE_FORMAT(fechaFactura,"%d %b %Y") AS fecha'),
                      DB::raw('DATE_FORMAT(fechaPago,"%d %b %Y") AS fechap'),
                      DB::raw('COUNT(asignacionesdetalles.`idu`) AS canti'),
                      'clientes.razonSocial','sucursales.sucursal','estatusEntrega','estatusPortal','estatusPago','cashFlow','tipoServicio',
                      DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(montoFactura,2)) AS monto'),
                      DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(totalConIva,2)) AS total'),
                      'archivo','archivoFactura','archivoPago','archivo','archivoFactura','archivoPago','xmlFactura','archivoRemision','archivoAdenda','facturas.idFactura','facturas.activo','facturas.servicioActivo','cotizaciones.archivoCotizacion','cotizacionServicios.archivoCotizacion AS archivoCotizacionServicios','cotizaciones.idCotizacion','cotizacionServicios.idCotizacionServicios')
                      ->leftJoin('clientes', 'facturas.idc', '=', 'clientes.idc')  
                      ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
                      ->leftJoin('asignacionesdetalles', 'facturas.idFactura', '=', 'asignacionesdetalles.idFactura')
                      ->leftJoin('cotizaciones', 'facturas.idCotizacion', '=', 'cotizaciones.idCotizacion')
                      ->leftJoin('cotizacionServicios', 'facturas.idCotizacion', '=', 'cotizacionServicios.idCotizacionServicios')
                      ->where('idServicios', '>', 0)
                      ->where('idServicios', 'like', '%'.$idServicios.'%')
                      ->where('numeroFactura', 'like', '%'.$numeroFactura.'%')
                      ->where('estatusPago', 'like', '%'.$estatusPago.'%')
                      ->where('estatusPortal', 'like', '%'.$estatusPortal.'%')
                      ->where('estatusEntrega', 'like', '%'.$estatusEntrega.'%')
                      ->where('cashFlow', 'like', '%'.$cashFlow.'%')
                      ->where('razonSocial', 'like', '%'.$cliente.'%')
                      ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                      ->where(function($query) {
                        $anexGrid = new anexGrid();
                        $numeroCotizacion='';
                        foreach($anexGrid->filtros as $f)
                          {
                            if($f['columna'] == 'numeroCotizacion' && $f['valor'] != ''){
                              $numeroCotizacion = $f['valor'];
                            }
                          }
                        $query->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
                        ->orwherenull('cotizaciones.numeroCotizacion');
                      })
  
                      ->where(function($query) {
                        $anexGrid = new anexGrid();
                        $numeroServicio='';
                        foreach($anexGrid->filtros as $f)
                          {
                            if($f['columna'] == 'numeroServicio' && $f['valor'] != ''){
                              $numeroServicio = $f['valor'];
                            }
                          }
                        $query->where('cotizacionServicios.numeroCotizacion', 'like', '%'.$numeroServicio.'%')
                        ->orwherenull('cotizacionServicios.numeroCotizacion');
                      })
                      ->where('fechaFactura','>=',$fechaInicio)                     
                      ->where('fechaFactura','<=',$fechaFin)      
                      ->whereIn('cotizaciones.idu',$usuariosReporte)   /////////AGREGADO EL 28-12-21////////////
                      ->orwhereIn('cotizacionServicios.idu',$usuariosReporte)   /////////AGREGADO EL 14-01-22////////////

                      // ->whereIn('asignacionesdetalles.idu',  function($query){
                      //   $sidu = Session::get('sesionidu');
                      //   $query->select('iduAsignado')
                      //   ->from('usuariosacargos as uc')
                      //   ->where('uc.idu','=',$sidu)
                      //   ->orwhere('asignacionesdetalles.idu', '=', $sidu)  ;
                      // })

                      // ->whereIn('facturas.idFactura',  function($query){
                      //   $sidu = Session::get('sesionidu');
                      //   $query->select('idFactura')
                      //   ->from('asignacionesdetalles')
                      //   ->where('idu','=',$sidu);
                      // })
                      ->groupBy('facturas.idFactura')
                      ->orderBy($anexGrid->columna, $order)
                      ->skip($anexGrid->pagina)
                      ->take($anexGrid->limite)
                    ->get();
                        
                    $total = DB::table('facturas')            
                      ->select('idServicios','numeroFactura','facturas.idc','facturas.idSucursal',
                      // DB::raw('cotizaciones.numeroCotizacion AS numeroCoti'),
                      // DB::raw('IF(cotizacionServicios.numeroCotizacion is null,"-",cotizacionServicios.numeroCotizacion) AS numeroSer'),
                      DB::raw('IF(facturas.tipoServicio="Soporte Técnico",cotizacionServicios.numeroCotizacion,"-") AS numeroSer'),
                      DB::raw('IF(facturas.tipoServicio!="Soporte Técnico",cotizaciones.numeroCotizacion,"-") AS numeroCoti'),
                      DB::raw('DATE_FORMAT(fechaFactura,"%d %b %Y") AS fecha'),
                      DB::raw('DATE_FORMAT(fechaPago,"%d %b %Y") AS fechap'),
                      DB::raw('COUNT(asignacionesdetalles.`idu`) AS canti'),
                      'clientes.razonSocial','sucursales.sucursal','estatusEntrega','estatusPortal','estatusPago','cashFlow','tipoServicio',
                      DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(montoFactura,2)) AS monto'),
                      DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(totalConIva,2)) AS total'),
                      'archivo','archivoFactura','archivoPago','archivo','archivoFactura','archivoPago','xmlFactura','archivoRemision','archivoAdenda','facturas.idFactura','facturas.activo','facturas.servicioActivo','cotizaciones.archivoCotizacion','cotizacionServicios.archivoCotizacion AS archivoCotizacionServicios','cotizaciones.idCotizacion','cotizacionServicios.idCotizacionServicios')
                      ->leftJoin('clientes', 'facturas.idc', '=', 'clientes.idc')  
                      ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
                      ->leftJoin('asignacionesdetalles', 'facturas.idFactura', '=', 'asignacionesdetalles.idFactura')
                      ->leftJoin('cotizaciones', 'facturas.idCotizacion', '=', 'cotizaciones.idCotizacion')
                      ->leftJoin('cotizacionServicios', 'facturas.idCotizacion', '=', 'cotizacionServicios.idCotizacionServicios','cotizacionServicios.archivoCotizacion AS archivoCotizacionServicios')
                      ->where('idServicios', '>', 0)
                      ->where('idServicios', 'like', '%'.$idServicios.'%')
                      ->where('numeroFactura', 'like', '%'.$numeroFactura.'%')
                      ->where('estatusPago', 'like', '%'.$estatusPago.'%')
                      ->where('estatusPortal', 'like', '%'.$estatusPortal.'%')
                      ->where('estatusEntrega', 'like', '%'.$estatusEntrega.'%')
                      ->where('cashFlow', 'like', '%'.$cashFlow.'%')
                      ->where('razonSocial', 'like', '%'.$cliente.'%')
                      ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                      ->where(function($query) {
                        $anexGrid = new anexGrid();
                        $numeroCotizacion='';
                        foreach($anexGrid->filtros as $f)
                          {
                            if($f['columna'] == 'numeroCotizacion' && $f['valor'] != ''){
                              $numeroCotizacion = $f['valor'];
                            }
                          }
                        $query->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
                        ->orwherenull('cotizaciones.numeroCotizacion');
                      })
  
                      ->where(function($query) {
                        $anexGrid = new anexGrid();
                        $numeroServicio='';
                        foreach($anexGrid->filtros as $f)
                          {
                            if($f['columna'] == 'numeroServicio' && $f['valor'] != ''){
                              $numeroServicio = $f['valor'];
                            }
                          }
                        $query->where('cotizacionServicios.numeroCotizacion', 'like', '%'.$numeroServicio.'%')
                        ->orwherenull('cotizacionServicios.numeroCotizacion');
                      })
                      ->where('fechaFactura','>=',$fechaInicio)                     
                      ->where('fechaFactura','<=',$fechaFin) 
                      ->whereIn('cotizaciones.idu',$usuariosReporte)   /////////AGREGADO EL 28-12-21////////////
                      ->orwhereIn('cotizacionServicios.idu',$usuariosReporte)   /////////AGREGADO EL 14-01-22////////////
                    ->count();
  
                  }
                  
                  $data = array(
                    'total' => $total,
                    'data' => $registros
                  );
  
                  return response()->json($data, 200);
                }
        }

          public function eliminarFacturas ($idFactura){/*Rerecibe este parametro y lo guarda en esa variable*/
             $facturas = \DB::UPDATE("update facturas
             set activo ='No' where idFactura=$idFactura");

           $proceso ="Eliminacion de factura";
           $mensaje="La Factura ha sido desactivada correctamente";
           //return redirect('reporteFacturas');
           return redirect('reporteFacturacion');
    }

     public function restaurarFacturas ($idFactura){ //restarura el valos de NO a SI en el campo activo
           $facturas = \DB::UPDATE("update facturas
           set activo ='si' where idFactura=$idFactura");

           $proceso ="Restauracion de factura";
           $mensaje="La factura ha sido activada correctamente";
            //return redirect('reporteFacturas');
            return redirect('reporteFacturacion');
    }

    public function activarServicio ($idFactura){ //cambia el valor a SI en el campo servicioActivo
      $facturas = \DB::UPDATE("update facturas
      set servicioActivo ='Activado' where idFactura=$idFactura");

      $proceso ="Activación de factura";
      $mensaje="La factura ha sido activada correctamente";
    //return redirect('reporteFacturas');
    return redirect('reporteFacturacion');
    }

    public function cerrarServicio ($idFactura){ //cambia el valor a NO en el campo servicioActivo
      $facturas = \DB::UPDATE("update facturas
      set servicioActivo ='Cerrado' where idFactura=$idFactura");

      $proceso ="Cierre de factura";
      $mensaje="La factura ha sido cerrada correctamente";
      //return redirect('reporteFacturas');
      return redirect('reporteFacturacion');
    }

public function downloadFile($src){
        if(is_file($src)){
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $content_type = finfo_file($finfo, $src);
            finfo_close($finfo);
            $file_name = basename($src).PHP_EOL;
            $size = filesize($src);
            header("Content-Type: $content_type");
            header("Content-Disposition: attachment; filename=$file_name");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: $size");
            readfile($src);
            return true;
        } else{
            return false;
        }
    }

    public function downloadPago(){
        if(!$this->downloadFile(app_path()."public/archivos/1584341446_docs5.pdf")){
            return redirect()->back();
        }
    }
  
  public function asignarCotizacion(Request $request){
  $idCotizacion = $request->idCotizacion;
  
  $archivo =\DB::select("SELECT idCotizacion,archivoCotizacion FROM cotizaciones WHERE estatus='Aceptada con orden de compra' AND idCotizacion=?",[$idCotizacion]);
  
  return view('archivoCotizacion')
  ->with('archivo',$archivo)
  ->with('idCotizacion',$idCotizacion);

}


public function agregarArchivos(Request $request){
  $idFactura = $request -> idFactura;
  $idu = $request -> idu;
  $nombreUsuario = $request -> nombreUsuario;
  $tipoArchivo = $request -> tipoArchivo;
  $numeroArchivo = $request -> numeroArchivo;
  $archivoFac = $request -> archivoFac;
  $observacionesAF = $request -> observacionesAF;


  $file = $request->file('archivoFac');
        //obtenemos el nombre del archivo
             if($file!=""){
        $archivoFac =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($archivoFac,  \File::get($file));
            }
            else{
              $archivoFac ="Sin archivo";
            }

            $aF=new archivosFacturas;
            $aF-> idFactura = $request-> idFactura;
            $aF-> idu = $request-> idu;
            $aF-> nombreUsuario = $request-> nombreUsuario;
            $aF-> tipoArchivo = $request-> tipoArchivo;
            $aF-> numeroArchivo = $request-> numeroArchivo;
            $aF-> archivoFac = $archivoFac;
            $aF-> observacionesAF = $request-> observacionesAF;
            $aF-> save();

            $consultaArchivosFac = archivosFacturas::where('idFactura', '=', $idFactura)->get();

            // return $consulta;
        return view("reportearchivosfac")
        ->with('consultaArchivosFac',$consultaArchivosFac);

}

public function borrarArchivosFac(Request $request){
  $idArchivoFactura=$request->idArchivoFactura;
  $idFactura=$request->idFactura;

  // return $idArchivoFactura;
    $cuantos = 0;  
    $aF = archivosFacturas::find($idArchivoFactura)->delete();
  
    
      $consultaArchivosFac=\DB::select("SELECT * from archivosFacturas
      WHERE idFactura = $idFactura");
  
  return view('reportearchivosfac')
      ->with('cuantos',$cuantos)
      ->with('consultaArchivosFac',$consultaArchivosFac);
}

public function agregarDatosPago(Request $request){

  $idFactura = $request->idFactura;
  $numeroFacturaDP = $request->numeroFacturaDP;
  $tipoCambioFac = $request->tipoCambioFac;
  $fechaDatosPago = $request->fechaDatosPago;
  $saldoFac = $request->saldoFac;
  $montoPesosDP = $request->montoPesosDP;
  $observaciones = $request->observaciones;
  $tipoCambioPagado = $request -> tipoCambioPagado;
  $montoReal = $request -> montoReal;
  $ivaFinal = $request -> ivaFinal;
  $subtotalFinal = $request -> subtotalFinal;
  $ivaFac = $request -> ivaFac;
  $subtotalFac = $request -> subtotalFac;
  $saldoReal = $request -> saldoReal;
  $difCambiaria = $request -> difCambiaria;
  $fechafactura = $request -> fechafactura;
  



  $datosP=new datosPagoServicios;
  $datosP-> idFactura = $request-> idFactura;
  $datosP-> numeroFacturaDP = $request-> numeroFacturaDP;
  $datosP-> tipoCambioFac = $request-> tipoCambioFac;
  $datosP-> saldoFac = $request-> saldoFac;
  $datosP-> fechaDatosPago = $request->fechaDatosPago;
  $datosP-> montoPesosDP = $request-> montoPesosDP;
  $datosP-> tipoCambioPagado = $request-> tipoCambioPagado;
  $datosP-> montoReal = $request-> montoReal;
  $datosP-> ivaFinal = $request-> ivaFinal;
  $datosP-> subtotalFinal = $request-> subtotalFinal;
  $datosP-> ivaFac = $request-> ivaFac;
  $datosP-> subtotalFac = $request-> subtotalFac;
  $datosP-> saldoReal = $request-> saldoReal;
  $datosP-> difCambiaria = $request-> difCambiaria;
  $datosP-> observaciones = $request-> observaciones;
  $datosP-> pagada = $request-> pagada;
  $datosP-> fechafactura = $request->fechafact;
  $datosP-> save();

  $consultaDatosPago = datosPagoServicios::where('idFactura', '=', $idFactura)->get();
  $totalDP=\DB::select("SELECT idFactura,
  FORMAT(SUM(montoPesosDP),2) as total
  FROM datosPagoServicios 
  WHERE idFactura = $idFactura");

      return view("reporteDatosPago")
      ->with('totalDP', $totalDP[0])
      ->with('consultaDatosPago',$consultaDatosPago);


}


  public function borrarDatosPago(Request $request){
    $idDatosPago=$request->idDatosPago;
    $idFactura=$request->idFactura;
  
    // return $idDatosPago;
      $cuantosDP = 0;  
      $datosP = datosPagoServicios::find($idDatosPago)->delete();
    
      
      $consultaDatosPago = datosPagoServicios::where('idFactura', '=', $idFactura)->get();
      $totalDP=\DB::select("SELECT idFactura,
      FORMAT(SUM(montoPesosDP),2) as total
      FROM datosPagoServicios 
      WHERE idFactura = $idFactura");
    
    return view('reporteDatosPago')
        ->with('cuantosDP',$cuantosDP)
        ->with('totalDP', $totalDP[0])
        ->with('consultaDatosPago',$consultaDatosPago);
  }

  public function editarInfoDatosPago(Request $request){
    $idDatosPago=$request->idDatosPago;
    $idFactura=$request->idFactura;    
    
    $datosPago = \DB::select("SELECT * FROM datosPagoServicios WHERE idDatosPago=? AND idFactura=?",[$idDatosPago,$idFactura]);

    return view('editarInfoDatosPago')
    ->with('datosPago',$datosPago[0])
    ->with('idDatosPago',$idDatosPago)
    ->with('idFactura',$idFactura);
  }

  public function guardarDatosPagoEdit(Request $request){
    $idDatosPago=$request->idDatosPago;
    $idFactura=$request->idFactura;

    $datosP = datosPagoServicios::find($idDatosPago);
    $datosP-> idFactura = $request-> idFactura;
    $datosP-> numeroFacturaDP = $request-> numeroFacturaDP;
    $datosP-> tipoCambioFac = $request-> tipoCambioFac;
    $datosP-> saldoFac = $request-> saldoFac;
    $datosP-> fechaDatosPago = $request->fechaDatosPago;
    $datosP-> montoPesosDP = $request-> montoPesosDP;
    $datosP-> tipoCambioPagado = $request-> tipoCambioPagado;
    $datosP-> montoReal = $request-> montoReal;
    $datosP-> ivaFinal = $request-> ivaFinal;
    $datosP-> subtotalFinal = $request-> subtotalFinal;
    $datosP-> ivaFac = $request-> ivaFac;
    $datosP-> subtotalFac = $request-> subtotalFac;
    $datosP-> saldoReal = $request-> saldoReal;
    $datosP-> difCambiaria = $request-> difCambiaria;
    $datosP-> observaciones = $request-> observaciones;
    $datosP-> pagada = $request-> pagada;
    $datosP-> fechafactura = $request->fechafact;
    $datosP-> save();

    $consultaDatosPago = datosPagoServicios::where('idFactura', '=', $idFactura)->get();
    $totalDP=\DB::select("SELECT idFactura,
    FORMAT(SUM(montoPesosDP),2) as total
    FROM datosPagoServicios 
    WHERE idFactura = $idFactura");

        return view("reporteDatosPago")
        ->with('totalDP', $totalDP[0])
        ->with('consultaDatosPago',$consultaDatosPago);


  }
public function UtExcel(){
    return Excel::download(new UtilidadServExport, 'utilidad.xlsx');
  }  
   public function excelUtilidadServicio(Request $request){
    return Excel::download( new UtilidadServicioExport( $request->data, $request->user), 'reporteUtilidadServicio.xlsx' );
}
public function excelGastoServicio(Request $request){
  return Excel::download( new GastoServicioExport( $request->data, $request->user), 'reporteGastoServicio.xlsx' );
}

  public function ordenarArray($clave,$orden=null) {
    return function ($a, $b) use ($clave,$orden) {
      $result=  ($orden=="DESC") ? strnatcmp($b->$clave, $a->$clave) :  strnatcmp($a->$clave, $b->$clave);
      return $result;
    };
  }

  public function agregarVendedorPorcentaje(Request $request){
    $idFactura = $request->idFactura;
    
    $sumaPorcentajes = \DB::table('vendedoresAsignadosServicios')
        ->where('idser', $idFactura)
        ->sum('porcentaje');
        
    if ($sumaPorcentajes + $request->porcentajeVendedor <= 100) {
        $vas = new vendedoresAsignadosServicios;
        $vas->idser = $request->idFactura;
        $vas->idvend = $request->vendedor;
        $vas->porcentaje = $request->porcentajeVendedor;
        $vas->save();

        $consultaVas = vendedoresAsignadosServicios::where('idser', '=', $idFactura)
            ->join("usuarios", "vendedoresAsignadosServicios.idvend", "=", "usuarios.idu")
            ->select(
                "vendedoresAsignadosServicios.idvas",
                "vendedoresAsignadosServicios.idser",
                "vendedoresAsignadosServicios.idvend",
                "vendedoresAsignadosServicios.porcentaje",
                // "usuarios.nombreUsuario as vendedor",
            )
            ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as vendedor")
            ->get();    

            $cuantosVas = count($consultaVas);
        return view("reporteVendedorPorcentaje")
            ->with('cuantosVas', $cuantosVas)
            ->with('consultaVas', $consultaVas);
    } else {
        return response()->json(['mensaje' => 'La suma de los porcentajes mayor al 100%']);
    }
}


  public function borrarVendedorPorcentaje(Request $request){
    $idvas=$request->idvas;
    $idFactura=$request->idFactura;
  
    // return $idDatosPago;
      $vas = vendedoresAsignadosServicios::find($idvas)->delete();
    
      
      $consultaVas = vendedoresAsignadosServicios::where('idser', '=', $idFactura)
    ->join("usuarios", "vendedoresAsignadosServicios.idvend", "=", "usuarios.idu")->
    select(
      "vendedoresAsignadosServicios.idvas",
      "vendedoresAsignadosServicios.idser",
      "vendedoresAsignadosServicios.idvend",
      "vendedoresAsignadosServicios.porcentaje",
      // "usuarios.nombreUsuario as vendedor",
    )
    ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as vendedor")
    ->get(); 
    $cuantosVas = count($consultaVas);
    return view("reporteVendedorPorcentaje")   
    ->with('cuantosVas',$cuantosVas)     
        ->with('consultaVas',$consultaVas);
  }
}
