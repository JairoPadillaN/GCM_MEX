<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;
use App\anexGrid;
use App\refacciones;
use App\usuarios;
use App\proveedores;
use App\ordenCompra;
use App\productosOrdenCompra;
use App\entradasAlmacenOrdenCompra;

class EntradaOrdenCompraController extends Controller
{
    public function altaEntradaOrdenCompra(){
        $idSiguiente = \DB::table('entradasAlmacenOrdenCompra')->max('idEntradaOrden') + 1;
        $proveedor = proveedores::orderby('idProveedor','asc')->get();
        return view ('altaEntradaOrdenCompra')
        ->with('proveedor', $proveedor)
        ->with('idSiguiente', $idSiguiente);
    }

    public function comboNumeroOrden(Request $request){
        $idProveedor=$request->idProveedor;
        $consultaCodigo=\DB::select("SELECT idOrden, codigoOrden FROM ordenCompra WHERE idProveedor=$idProveedor and activo='Si' and cotizacionProveedor IS NOT NULL");
        return view ('comboNumeroFactura')
        ->with('consultaCodigo', $consultaCodigo);
    }
    public function infoOrdenCompra(Request $request){
        $idOrden=$request->idOrden;
        $consultaOrden=\DB::select("SELECT total, codigoOrden, moneda,cotizacionProveedor FROM ordenCompra WHERE idOrden=$idOrden");

        return view("infoOrdenCompra")
        ->with('consultaOrden',$consultaOrden[0]);
    }

    public function productosOrdenCompra(Request $request){
        $idOrden=$request->idOrden;
        $consultaProductosOrden=\DB::select("SELECT * FROM productosOrdenCompra WHERE idOrden=$idOrden");

        return view("comboProductosOrden")
        ->with('consultaProductosOrden',$consultaProductosOrden);
    }

    public function datosProducto(Request $request){
        $idProducto=$request->idProducto;
        $consultaProductosOrden=\DB::select("SELECT idProducto,pv.nombreRefaccion, pc.idPartesVenta, pv.numeroParte, pv.serie, pv.codigo
                    FROM productosOrdenCompra pc
                    INNER JOIN partesVenta AS pv ON pv.idPartesVenta=pc.idPartesVenta
                    WHERE pc.idProducto=$idProducto");

        return view("datosProductoOrden")
        ->with('consultaProductosOrden',$consultaProductosOrden[0]);
    }

    public function datosProducto2(Request $request){
        $idProducto=$request->idProducto;
        $consultaProductosOrden=\DB::select("SELECT idProducto,pv.nombreRefaccion, pc.idPartesVenta, oc.idOrden,oc.moneda,
        (pc.precioOrden+(pc.precioOrden*(pc.ivaOrden/100))+(pc.precioOrden*(pc.isrOrden/100))) AS precioCompraDolares, 
        IF(oc.moneda='USD',((pc.precioOrden+(pc.precioOrden*(pc.ivaOrden/100)))*oc.cambioDolar), (pc.precioOrden+(pc.precioOrden*(pc.ivaOrden/100))+(pc.precioOrden*(pc.isrOrden/100)))) AS precioCompraPesos,
        (pv.precioVentaPe) AS precioVentaPe,
        (pv.precioVentaDol) AS precioVentaDol, pc.cantidadOrden
        FROM productosOrdenCompra pc
        INNER JOIN partesVenta AS pv ON pv.idPartesVenta=pc.idPartesVenta
        INNER JOIN ordenCompra AS oc ON oc.idOrden =pc.idOrden
        WHERE pc.idProducto=$idProducto");

        return view("datosProductoOrden2")
        ->with('consultaProductosOrden',$consultaProductosOrden[0]);
    }

    public function guardarEntradaOrden(Request $request){
        // return $request;
        $idOrden=$request->idOrden;

        $archivo = ordenCompra::find($idOrden);
        $archivoFactura=$archivo->cotizacionProveedor;

        $entrada = new entradasAlmacenOrdenCompra;
        $entrada->fechaCompra=$request->fechaCompra;
        $entrada->idProveedor=$request->idProveedor;
        $entrada->idu=$request->idu;
        $entrada->idOrden=$request->idOrden;
        $entrada->numeroFactura=$request->numeroFactura;
        $entrada->montoFactura=$request->montoFactura;
        $entrada->recibidoPor=$request->recibidoPor;
        $entrada->moneda=$request->moneda;
        $entrada->archivoFactura=$archivoFactura;
        $entrada->activo='Si';
        $entrada->save();
        $proceso='Registro entrada de ordenes de compra';
        $mensaje="Entrada registrada correctamente";
        return view ('mensajeEntradaOrden')
        ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);
    }

    public function reporteEntradaOrdenCompra(){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
        return view('reporteEntradaOrdenCompra')
        ->with('sesiontipo',$stipo);
    }
    public function filtroEntradaOrden(){
        
        $anexGrid = new anexGrid();
        $order;
        $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

        $razonSocialProv = '';
        $numeroFactura = '';

        foreach($anexGrid->filtros as $f)
            {
                if($f['columna'] == 'razonSocialProv' && $f['valor'] != ''){
                    $razonSocialProv = $f['valor'];
                }
                if($f['columna'] == 'numeroFactura' && $f['valor'] != ''){
                    $numeroFactura = $f['valor'];
                }
            }


        $registros = DB::table('entradasAlmacenOrdenCompra as ea')            
        ->select('ea.idEntradaOrden','numeroFactura','fechaCompra','razonSocialProv','archivoFactura','ea.activo',
        DB::raw('COUNT(`idRefaccion`) AS numeroItems'),
        DB::raw('CONCAT(ea.moneda, " $ ", FORMAT(montoFactura,2)) AS montoFactura'),
        DB::raw('DATE_FORMAT(fechaCompra,"%d %b %Y") AS fecha'))
        ->leftJoin('proveedores', 'ea.idProveedor', '=', 'proveedores.idProveedor')  
        ->leftJoin('refacciones', 'ea.idEntradaOrden', '=', 'refacciones.idEntradaOrden')  
        ->where('razonSocialProv', 'like', '%'.$razonSocialProv.'%')
        ->where('numeroFactura', 'like', '%'.$numeroFactura.'%')
        ->groupBy('ea.idEntradaOrden')
        ->orderBy($anexGrid->columna, $order)
        ->skip($anexGrid->pagina)
        ->take($anexGrid->limite)
        ->get();

        $total = DB::table('entradasAlmacenOrdenCompra as ea')            
        ->select('ea.idEntradaOrden','numeroFactura','fechaCompra','razonSocialProv','archivoFactura','ea.activo',
        DB::raw('CONCAT(ea.moneda, " $ ", montoFactura) AS montoFactura'),
        DB::raw('DATE_FORMAT(fechaCompra,"%d %b %Y") AS fecha'))
        ->leftJoin('proveedores', 'ea.idProveedor', '=', 'proveedores.idProveedor')  
        ->where('razonSocialProv', 'like', '%'.$razonSocialProv.'%')
        ->count();
                
    
        $data = array(
            'total' => $total,
            'data' => $registros
        );
        return response()->json($data, 200);
        // return $fechaInicio;
        

    }

    public function modificarEntradaOrden($idEntradaOrden){
        $consulta = entradasAlmacenOrdenCompra::where('idEntradaOrden', '=', $idEntradaOrden)->get();
        $usuario = usuarios::where('idu', '=', $consulta[0]->idu)->get();

        $proveedorSel= proveedores::where('idProveedor',"=",$consulta[0]->idProveedor)->get();
        $nombreProveedor=$proveedorSel[0]->razonSocialProv;
        $proveedor= proveedores::where('idProveedor',"!=",$consulta[0]->idProveedor)->get();

        $productosOrden=productosOrdenCompra::where('idOrden',"=", $consulta[0]->idOrden)->get();

        $consultaRefacciones =\DB::select("SELECT fotoPlaca, fotoPrincipal, numeroParte, codigoRefaccion, nombreRefaccion,
                                            presentacion,idRefaccion, idEntrada, idEntradaOrden, cantidadOcupada,
                                            cantidad, observaciones, moneda, FORMAT(precioLista,2) AS precioLista,
                                            FORMAT((cantidad*precioLista),2) as precioTotal,
                                            FORMAT((cantidad*precioCompraDol),2) as precioTotalDol,
                                            precioCompraDol
                                            FROM refacciones WHERE idEntradaOrden= $idEntradaOrden");
        $sumaTotal=\DB::select("SELECT FORMAT(SUM(precioLista*cantidad),2) AS total, FORMAT(SUM(precioCompraDol*cantidad),2) AS totalDol FROM refacciones WHERE idEntradaOrden= $idEntradaOrden");
        return view('editarEntradaOrdenCompra')
        ->with('sumaTotal',$sumaTotal[0])
        ->with('consultaRefacciones',$consultaRefacciones)
        ->with('productosOrden',$productosOrden)
        ->with('proveedorSel',$proveedorSel[0])
        ->with('idProvSel',$consulta[0]->idProveedor)
        ->with('nombreProveedor',$nombreProveedor)
        ->with('proveedor',$proveedor)
        ->with('usuario', $usuario[0])
        ->with('consulta', $consulta[0]);
    }

    public function editarEntradaOrden(Request $request){
        $idOrden=$request->idOrden;
        $idEntradaOrden=$request->idEntradaEditarOrden;

        $archivo = ordenCompra::find($idOrden);
        $archivoFactura=$archivo->cotizacionProveedor;

        // dd($request);
        $entrada = entradasAlmacenOrdenCompra::find($idEntradaOrden);
        $entrada->fechaCompra=$request->fechaCompra;
        $entrada->idProveedor=$request->idProveedor;
        $entrada->idu=$request->idu;
        $entrada->idOrden=$request->idOrden;
        $entrada->numeroFactura=$request->numeroFactura;
        $entrada->montoFactura=$request->montoFactura;
        $entrada->recibidoPor=$request->recibidoPor;
        $entrada->moneda=$request->moneda;
        $entrada->archivoFactura=$archivoFactura;
        $entrada->activo='Si';
        $entrada->save();
        $proceso='Registro entrada de ordenes de compra';
        $mensaje="Entrada registrada correctamente";
        return view ('mensajeEntradaOrden')
        ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);

    }

    public function eliminarEntradaOrden ($idEntradaOrden){/*Rerecibe este parametro y lo guarda en esa variable*/
        $entradas = \DB::UPDATE("update entradasAlmacenOrdenCompra
        set activo ='No' where idEntradaOrden=$idEntradaOrden");

        $proceso ="Eliminación de entradas";
        $mensaje="La entrada ha sido desactivada correctamente";
        return view('mensajeEntradaOrden')
            ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);
    }   
    public function restaurarEntradaOrden ($idEntradaOrden){/*Rerecibe este parametro y lo guarda en esa variable*/
        $entradas = \DB::UPDATE("update entradasAlmacenOrdenCompra
        set activo ='Si' where idEntradaOrden=$idEntradaOrden");

        $proceso ="Restauración de entradas";
        $mensaje="La entrada ha sido activada correctamente";
        return view('mensajeEntradaOrden')
            ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);
    }   

}
