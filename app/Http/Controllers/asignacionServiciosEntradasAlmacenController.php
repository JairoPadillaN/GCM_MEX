<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\proveedores;
use App\facturas;
use App\tiporefacciones;
use App\refacciones;
use App\entradasAlmacen;
use App\entradasAlmacenOrdenCompra;
use App\usuarios;
use App\marcaRefaccion;
use App\productosPorEntradaAlmacen;
use App\productosServiciosAsignacion;
use App\gruposAsignacionesEntradas;
use App\anexGrid;
use DB;
use Illuminate\Support\Facades\Storage;


class asignacionServiciosEntradasAlmacenController extends Controller
{

    public function altaAsignacionProductosServicios(){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');        

        if($sname == '' or $sidu =='' or $stipo==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else{

            $ordenesCompra = $this->obtenerOrdenesCompra();
            
            $servicios = DB::table('facturas')
            ->select('idFactura','idServicios')
            ->where('activo', '=', 'si')
            ->where('activo', '=', 'Si')
            ->orderBy('idServicios','DESC')
            ->get();

            return view ('asignacionServiciosEntradas')
            ->with('ordenesCompra',$ordenesCompra)
            ->with('servicios',$servicios);
        }

    }

    public function asignacionProductosServiciosModal(Request $request){        
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');        

        if($sname == '' or $sidu =='' or $stipo==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else{
            
            $idOrden=$request->idOrden;
            $idProductoEntrada=$request->idProducto;
            $cantidadEnAlmacen=$request->cantidad;

            $ordenesCompra = $this->obtenerOrdenesCompra();

            $servicios = DB::table('facturas')
            ->select('idFactura','idServicios')
            ->where('activo', '=', 'si')
            ->where('activo', '=', 'Si')
            ->orderBy('idServicios','DESC')
            ->get();            

            return view ('asignacionServiciosEntradasModal')
            ->with('idOrden',$idOrden)
            ->with('idProductoEntrada',$idProductoEntrada)
            ->with('cantidadEnAlmacen',$cantidadEnAlmacen)
            ->with('ordenesCompra',$ordenesCompra)
            ->with('servicios',$servicios);
        }

    }

    public function obtenerOrdenesCompra(){
        $ordenesCompra = DB::table('productosPorEntradaAlmacen as pea')
        ->select('pea.idOrden','ordenCompra.codigoOrden')
        ->leftJoin('ordenCompra', 'pea.idOrden', '=', 'ordenCompra.idOrden')
        ->where('pea.estatusAsignacion','=',0)
        ->where('ordenCompra.activo', '=', 'Si')
        ->groupBy('pea.idOrden')
        ->orderBy('ordenCompra.codigoOrden','DESC')
        ->get();

        return $ordenesCompra;
    }

    public function obtenerProductosSinServicio(Request $request){
        $idOrden=$request->idOrden;
        
        $productosEnOC = DB::table('productosPorEntradaAlmacen as pea')
        ->select('pea.idProducto','pea.idProductoEntrada',
        DB::raw('CONCAT((SELECT pv_2.codigo FROM partesVenta as pv_2 WHERE pv_2.idPartesVenta = poc.idPartesVenta),IFNULL(pea.numeroSerie,poc.nombreRefaccion)) as producto'),
        DB::raw('(SELECT IFNULL(pea.numeroSerie,poc.nombreRefaccion) FROM partesVenta as pv_3 WHERE pv_3.idPartesVenta = poc.idPartesVenta) as codigo'))
        // DB::raw('CONCAT(poc.codigo," / ",IFNULL(pea.numeroSerie,poc.nombreRefaccion)) AS producto'))
        ->leftJoin('productosOrdenCompra as poc', 'pea.idProducto', '=', 'poc.idProducto')
        ->where('pea.idOrden', '=', $idOrden)
        ->where('pea.estatusAsignacion','=',0)
        // ->whereNull('pea.idFactura')
        ->orderBy('poc.codigo','asc')
        ->get();

        // $productosEnOC = DB::table('productosPorEntradaAlmacen as pea')
        // ->select('pea.idProducto','pea.idProductoEntrada',
        // DB::raw('CONCAT(productosOrdenCompra.codigo," / ",IFNULL(pea.numeroSerie,productosOrdenCompra.nombreRefaccion)) AS producto'))
        // ->leftJoin('productosOrdenCompra', 'pea.idProducto', '=', 'productosOrdenCompra.idProducto')
        // ->where('pea.idOrden', '=', $idOrden)
        // ->where('pea.estatusAsignacion','=',0)
        // // ->whereNull('pea.idFactura')
        // ->orderBy('productosOrdenCompra.codigo','asc')
        // ->get();

        return $productosEnOC;
    }

    public function obtenerDatosProductosSinServicio(Request $request){
        $idProductoEntrada=$request->idProductoEntrada;

        $datosProducto = DB::table('productosPorEntradaAlmacen as pea')
        ->select('cantidadAsignada','pea.idProductoEntrada','idProducto','pea.idOrden',
        DB::raw('(cantidadRecibida - cantidadAsignada) AS sinAsignar'))        
        ->where('idProductoEntrada', '=', $idProductoEntrada)
        ->get();

        return $datosProducto;
    }

    public function crearGrupoAsignacion(Request $request){
        $datosFormulario = $request;

        if($datosFormulario->idGrupoAsignacion == '' || is_null($datosFormulario->idGrupoAsignacion)){
            $grupo = new gruposAsignacionesEntradas;
            $grupo->save();
            
            $idGrupoAsignacion = $grupo->idGrupoAsignacion;
        }else{
            $idGrupoAsignacion = $datosFormulario->idGrupoAsignacion;
        }

        return $this->asignarProductoServicio($idGrupoAsignacion , $datosFormulario);
    }

    public function asignarProductoServicio($idGrupoAsignacion, $datosFormulario){
        
        // SI ES UNA ASIGNACION A OC, CRAMOS LA ENTRADA DEL PRODUCTO PRIMERO, EL PRODUCTO EN ALMACEN PASARA A SER EL ID_PRODUCTO_REFERENCIA
        // Y EL CAMPO PRODCTO LO OCUAPARA EL PRODUCTO DE LA ORDEN A LA QUE SE ESTA MOVIENDO
        if($datosFormulario->tipoAsignacion == "a_oc"){
            $datos_para_crear_entrada = $datosFormulario;
            $idProductoEntrada_referencia = $this->CrearEntradaAlmacen($datos_para_crear_entrada);
            // $idProductoEntrada = $idProductoEntrada_referencia;
        }else{
            $idProductoEntrada_referencia = 0;
            // $idProductoEntrada = $datosFormulario->idProductoEntrada;
        }

        
        // evidencias
        $file = $datosFormulario->file('evidencia');
        if($file!=""){
            $ldate = date('Ymd_His_');
            $foto = $file->getClientOriginalName();
            $evidencia = $ldate.$foto;
            \Storage::disk('local')->put($evidencia, \File::get($file));
        }else{
            $evidencia ="Sin archivo";
        }
        
        $asignacion = new productosServiciosAsignacion;
        $asignacion->idGrupoAsignacion = $idGrupoAsignacion;
		$asignacion->idOrden = $datosFormulario->idOrden;
        
        $asignacion->oc_destino = $datosFormulario->oc_destino;
		
        $asignacion->idProductoEntrada = $datosFormulario->idProductoEntrada;
        // $asignacion->idProductoEntrada = $idProductoEntrada;
        $asignacion->idProductoEntrada_referencia = $idProductoEntrada_referencia;
        
		$asignacion->idProducto = $datosFormulario->idProducto;
        $asignacion->idProducto_destino = $datosFormulario->idProducto_destino;
		$asignacion->idFactura = $datosFormulario->idFactura;
        $asignacion->id_factura_referencia = $datosFormulario->id_factura_referencia;

        $asignacion->tipo_asignacion = $datosFormulario->tipoAsignacion;
        $asignacion->motivo_salida = $datosFormulario->op_motivo_salida;
		$asignacion->cantidadAservicio = $datosFormulario->cantidadAservicio;
		$asignacion->fechaAsignacion = $datosFormulario->fechaAsignacion;
		$asignacion->quienLLevo = $datosFormulario->quienLLevo;
		$asignacion->detalles = $datosFormulario->detalles;
		$asignacion->evidencia = $evidencia;

        $asignacion->save();

        // Se envia tipo de actualizacion 1 = "sumar cantidad asignada"
        $tipoActualizacion = 1;
        $idProductoEntrada = $datosFormulario->idProductoEntrada;
        $cantidadAservicio = $datosFormulario->cantidadAservicio;
        $idFactura = $datosFormulario->idFactura;

        return $this->actualizarCantidadesAsignada($idProductoEntrada , $cantidadAservicio , $tipoActualizacion, $idGrupoAsignacion , $idFactura);
    }

    public function eliminarAsignacion(Request $request){
        $idProductoServAsig=$request->idProductoServAsig;

        $asignacion= DB::table('productosServiciosAsignacion as psa')
            ->select('psa.idProductoEntrada','psa.idGrupoAsignacion','psa.cantidadAservicio','psa.evidencia','psa.idProductoEntrada_referencia','psa.tipo_asignacion')
            ->where('psa.idProductoServAsig','=',$idProductoServAsig)
        ->get();

        // Primero eliminamos los archivos del servidor
        \Storage::delete($asignacion[0]->evidencia);
        
        // eliminamos el registro de la BD
        \DB::delete("DELETE FROM productosServiciosAsignacion WHERE idProductoServAsig=?",[$idProductoServAsig]);

        // Si fue una asignacion a oc, se eliminará la entrada a almacen que se crea automaticanente
        if($asignacion[0]->tipo_asignacion == "a_oc"){
            $this->eliminarProductoEntrada($asignacion[0]->idProductoEntrada_referencia);
            
            \DB::delete("DELETE FROM productosServiciosAsignacion WHERE idProductoEntrada=?",[$asignacion[0]->idProductoEntrada_referencia]);

        }
        
        // Se envia tipo de actualizacion 0 = "restar cantidad"
        $tipoActualizacion = 0;
        $idProductoEntrada =$asignacion[0]->idProductoEntrada;
        $cantidadAservicio = $asignacion[0]->cantidadAservicio;
        $idGrupoAsignacion = $asignacion[0]->idGrupoAsignacion;
        $idFactura = '';

        return $this->actualizarCantidadesAsignada($idProductoEntrada , $cantidadAservicio , $tipoActualizacion, $idGrupoAsignacion, $idFactura);
    }

    public function actualizarCantidadesAsignada($idProductoEntrada , $cantidadAservicio, $tipoActualizacion, $idGrupoAsignacion, $idFactura){
        // Consulta para obtener las cantidades actuales del producto
        $productoEnAlmacen= DB::table('productosPorEntradaAlmacen')
            ->select('cantidadRecibida','cantidadAsignada')
            ->where('idProductoEntrada','=',$idProductoEntrada)        
        ->get();

        // Si el tipoActualizacion es = 1 entonces se asigno un producto a servicio, de lo contrario es una eliminacion de una asignacion
        if($tipoActualizacion == 1){
            // Suma de la cantidad ya asignada con la nueva entrega
            $cantidadAsignada = $productoEnAlmacen[0]->cantidadAsignada + $cantidadAservicio;
        }else{
            // Resta de la cantidad ya asignada menos la del producto eliminado
            $cantidadAsignada = $productoEnAlmacen[0]->cantidadAsignada - $cantidadAservicio;
        }

        // Actualización de la cantidad de productos asignados a servicios
        \DB::select("UPDATE productosPorEntradaAlmacen SET cantidadAsignada = ? WHERE idProductoEntrada = ?",[$cantidadAsignada,$idProductoEntrada]);

        // Actualización del estatus de asignacion, si la cantidad asignada es igual a la 
        // cantidad que entró al almacen entonces el estatus es " 1 = asignado" de lo contrario es "0 = sin asignar"
        if($cantidadAsignada == $productoEnAlmacen[0]->cantidadRecibida){
            // \DB::select("UPDATE productosPorEntradaAlmacen SET estatusAsignacion = 1, idFactura = ? WHERE idProductoEntrada = ?",[$idFactura, $idProductoEntrada]);
            \DB::select("UPDATE productosPorEntradaAlmacen SET estatusAsignacion = 1 WHERE idProductoEntrada = ?",[$idProductoEntrada]);
        }else{
            \DB::select("UPDATE productosPorEntradaAlmacen SET estatusAsignacion = 0 WHERE idProductoEntrada = ?",[$idProductoEntrada]);
        }
        
        return $idGrupoAsignacion;
        // return $this->consultaProductosAsignados($idGrupoAsignacion);
    }

    public function consultaProductosAsignados(Request $request){
        $idGrupoAsignacion=$request->idGrupoAsignacion;

        $registros= DB::table('productosServiciosAsignacion as psa')
            ->select('psa.idProductoServAsig','psa.idProductoEntrada',
                DB::raw('(poc.codigo) AS sku'),
                DB::raw('(poc.nombreRefaccion) AS producto'),
                DB::raw('(oc.codigoOrden) AS ordenCompra'),
                DB::raw("CONCAT(IF(tipo_asignacion='a_servicio','Servicio: ',IF(tipo_asignacion='a_oc','Orden de Compra: ','Salida por ')),IF(tipo_asignacion='a_servicio',f.idServicios,IF(tipo_asignacion='a_oc',oc_2.codigoOrden,IF(psa.motivo_salida='1','Garantía',IF(psa.motivo_salida='2','Merma','Devolución'))))) as asignacion"),
                'pea.numeroSerie',
                'f.idServicios',
                'psa.cantidadAservicio'
            )
            ->leftJoin('ordenCompra as oc', 'psa.idOrden', '=', 'oc.idOrden')
            ->leftJoin('ordenCompra as oc_2', 'psa.oc_destino', '=', 'oc_2.idOrden')
            ->leftJoin('productosOrdenCompra as poc', 'psa.idProducto', '=', 'poc.idProducto')
            ->leftJoin('productosPorEntradaAlmacen as pea', 'psa.idProductoEntrada', '=', 'pea.idProductoEntrada')
            ->leftJoin('facturas as f', 'psa.idFactura', '=', 'f.idFactura')
            ->leftJoin('facturas as f_2', 'psa.id_factura_referencia', '=', 'f_2.idFactura')
            ->where('psa.idGrupoAsignacion','=',$idGrupoAsignacion)
            ->orderBy('psa.fechaAsignacion','DESC')
        ->get();
                
        return $registros;
    }

    public function consultaProductosAsignadosPorOC(Request $request){
        $idOrden=$request->idOrden;

        $registros= DB::table('productosServiciosAsignacion as psa')
            ->select('psa.idProductoServAsig','psa.idProductoEntrada',
                DB::raw('(poc.codigo) AS sku'),
                DB::raw('(poc.nombreRefaccion) AS producto'),
                DB::raw('(oc.codigoOrden) AS ordenCompra'),
                DB::raw("CONCAT(IF(tipo_asignacion='a_servicio','Servicio: ',IF(tipo_asignacion='a_oc','Orden de Compra: ','Salida por ')),IF(tipo_asignacion='a_servicio',f.idServicios,IF(tipo_asignacion='a_oc',oc_2.codigoOrden,IF(psa.motivo_salida='1','Garantía',IF(psa.motivo_salida='2','Merma','Devolución'))))) as asignacion"),
                'pea.numeroSerie',
                'f.idServicios',
                'psa.cantidadAservicio'
            )
            ->leftJoin('ordenCompra as oc', 'psa.idOrden', '=', 'oc.idOrden')
            ->leftJoin('ordenCompra as oc_2', 'psa.oc_destino', '=', 'oc_2.idOrden')
            ->leftJoin('productosOrdenCompra as poc', 'psa.idProducto', '=', 'poc.idProducto')
            ->leftJoin('productosPorEntradaAlmacen as pea', 'psa.idProductoEntrada', '=', 'pea.idProductoEntrada')
            ->leftJoin('facturas as f', 'psa.idFactura', '=', 'f.idFactura')
            ->leftJoin('facturas as f_2', 'psa.id_factura_referencia', '=', 'f_2.idFactura')
            ->where('psa.idOrden','=',$idOrden)
            ->orderBy('psa.fechaAsignacion','DESC')
        ->get();
                
        return $registros;
    }

    public function reporteAsignacionProductosServicios(){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');        

        if($sname == '' or $sidu =='' or $stipo==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else{
            return view('reporteAsignacionProductosServicios')
            ->with('sesiontipo',$stipo);
        }
    }

    public function anexgrid_get_reporteProductos_asignados_servicio(){
        $anexGrid = new anexGrid();
        $order;
        $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'DESC';

        $folioOrden = '';
        $sku = '';
        $producto = '';
        $servicio = '';
        $fechaInicioAsignacion = '0000-00-00';
        $fechaFinAsignacion = '9999-12-31';
        $proveedor = '';

        foreach($anexGrid->filtros as $f){
            if($f['columna'] == 'ordenCompra' && $f['valor'] != ''){
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
            if($f['columna'] == 'fechaInicioAsignacion' && $f['valor'] != ''){
                $fechaInicioAsignacion = $f['valor'];
            }           
            if($f['columna'] == 'fechaFinAsignacion' && $f['valor'] != ''){
                $fechaFinAsignacion = $f['valor'];
            }
            if($f['columna'] == 'proveedor' && $f['valor'] != ''){
                $proveedor = $f['valor'];
            }
        }

        $registros= DB::table('productosServiciosAsignacion as psa')
            ->select('psa.idProductoServAsig','psa.idGrupoAsignacion',
                DB::raw('IF(oc.oc_mov_internos = 1,CONCAT("Entradas directas ",oc.nombreEmpresa),oc.codigoOrden) AS ordenCompra'),
                DB::raw('(poc.codigo) AS sku'),
                DB::raw('(poc.nombreRefaccion) AS producto'),
                DB::raw('(psa.cantidadAservicio) AS cantidadAsignada'),
                DB::raw('IFNULL(f.idServicios,"-") AS servicio'),
                'psa.detalles',
                DB::raw('DATE_FORMAT(psa.fechaAsignacion,"%d/%m/%Y") AS fecha_formato'),
                DB::raw('(psa.fechaAsignacion) AS fechaInicioAsignacion'),
                DB::raw('(psa.fechaAsignacion) AS fechaFinAsignacion'),
                DB::raw('(p.razonSocialProv) AS proveedor'),
                'oc.nombreEmpresa',
                DB::raw('CONCAT(IFNULL(en.numeroSerie,"-")," / ",IFNULL(en.modelo,"-")) AS serieYmodelo'),
                'en.almacen'
            )
            ->leftJoin('ordenCompra as oc', 'psa.idOrden', '=', 'oc.idOrden')
            ->leftJoin('facturas as f', 'psa.idFactura', '=', 'f.idFactura')
            ->leftJoin('productosOrdenCompra as poc', 'psa.idProducto', '=', 'poc.idProducto')
            ->leftJoin('productosPorEntradaAlmacen as en', 'psa.idProductoEntrada', '=', 'en.idProductoEntrada')
            ->leftJoin('proveedores as p', 'oc.idProveedor', '=', 'p.idProveedor')
            // ->leftJoin('proveedores as p', 'en.idProveedor', '=', 'p.idProveedor')
            ->where('psa.tipo_asignacion', '=', "a_servicio")
            ->where(DB::raw('IF(oc.oc_mov_internos = 1,CONCAT("Entradas directas ",oc.nombreEmpresa),oc.codigoOrden)'), 'like', '%'.$folioOrden.'%')
            ->where('poc.codigo', 'like', '%'.$sku.'%')
            ->where('poc.nombreRefaccion', 'like', '%'.$producto.'%')
            ->where('f.idServicios', 'like', '%'.$servicio.'%')
            ->where('p.razonSocialProv', 'like', '%'.$proveedor.'%')
            ->where('psa.fechaAsignacion', '>=', $fechaInicioAsignacion)
            ->where('psa.fechaAsignacion', '<=', $fechaFinAsignacion)
            ->where(function($query) {
                $anexGrid = new anexGrid();
                $serieYmodelo='';
                
                foreach($anexGrid->filtros as $f){
                    if($f['columna'] == 'serieYmodelo' && $f['valor'] != ''){
                        $serieYmodelo = $f['valor'];
                    }
                }
                
                if($serieYmodelo == '-' || $serieYmodelo ==''){
                    $query->where('en.numeroSerie', 'like', '%'.$serieYmodelo.'%')
                    ->orWhere('en.modelo', 'like', '%'.$serieYmodelo.'%')
                    ->orWhereNull('en.numeroSerie')
                    ->orWhereNull('en.modelo');
                }else{
                    $query->where('en.numeroSerie', 'like', '%'.$serieYmodelo.'%')
                    ->orWhere('en.modelo', 'like', '%'.$serieYmodelo.'%');
                }
            })
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
        ->get();

        $total= DB::table('productosServiciosAsignacion as psa')
            ->select('psa.idProductoServAsig','psa.idGrupoAsignacion',
                DB::raw('(oc.codigoOrden) AS ordenCompra'),
                DB::raw('(poc.codigo) AS sku'),
                DB::raw('(poc.nombreRefaccion) AS producto'),
                DB::raw('(psa.cantidadAservicio) AS cantidadAsignada'),
                DB::raw('IFNULL(f.idServicios,"-") AS servicio'),
                'psa.detalles',
                DB::raw('DATE_FORMAT(psa.fechaAsignacion,"%d/%m/%Y") AS fecha_formato'),
                DB::raw('(psa.fechaAsignacion) AS fechaInicioAsignacion'),
                DB::raw('(psa.fechaAsignacion) AS fechaFinAsignacion'),
                DB::raw('(p.razonSocialProv) AS proveedor'),
                'oc.nombreEmpresa',
                DB::raw('CONCAT(IFNULL(en.numeroSerie,"-")," / ",IFNULL(en.modelo,"-")) AS serieYmodelo')
            )
            ->leftJoin('ordenCompra as oc', 'psa.idOrden', '=', 'oc.idOrden')
            ->leftJoin('facturas as f', 'psa.idFactura', '=', 'f.idFactura')
            ->leftJoin('productosOrdenCompra as poc', 'psa.idProducto', '=', 'poc.idProducto')
            ->leftJoin('productosPorEntradaAlmacen as en', 'psa.idProductoEntrada', '=', 'en.idProductoEntrada')
            ->leftJoin('proveedores as p', 'oc.idProveedor', '=', 'p.idProveedor')
            // ->leftJoin('proveedores as p', 'en.idProveedor', '=', 'p.idProveedor')
            ->where('psa.tipo_asignacion', '=', "a_servicio")
            ->where(DB::raw('IF(oc.oc_mov_internos = 1,CONCAT("Entradas directas ",oc.nombreEmpresa),oc.codigoOrden)'), 'like', '%'.$folioOrden.'%')
            ->where('poc.codigo', 'like', '%'.$sku.'%')
            ->where('poc.nombreRefaccion', 'like', '%'.$producto.'%')
            ->where('f.idServicios', 'like', '%'.$servicio.'%')
            ->where('p.razonSocialProv', 'like', '%'.$proveedor.'%')
            ->where('psa.fechaAsignacion', '>=', $fechaInicioAsignacion)
            ->where('psa.fechaAsignacion', '<=', $fechaFinAsignacion)
            ->where(function($query) {
                $anexGrid = new anexGrid();
                $serieYmodelo='';
                
                foreach($anexGrid->filtros as $f){
                    if($f['columna'] == 'serieYmodelo' && $f['valor'] != ''){
                        $serieYmodelo = $f['valor'];
                    }
                }
                
                if($serieYmodelo == '-' || $serieYmodelo ==''){
                    $query->where('en.numeroSerie', 'like', '%'.$serieYmodelo.'%')
                    ->orWhere('en.modelo', 'like', '%'.$serieYmodelo.'%')
                    ->orWhereNull('en.numeroSerie')
                    ->orWhereNull('en.modelo');
                }else{
                    $query->where('en.numeroSerie', 'like', '%'.$serieYmodelo.'%')
                    ->orWhere('en.modelo', 'like', '%'.$serieYmodelo.'%');
                }
            })
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
        ->count();
        
        $data = array(
            'total' => $total,
            'data' => $registros
        );

        return response()->json($data, 200);
    }

    public function anexgrid_get_reporteProductos_asignados_a_oc(){
        $anexGrid = new anexGrid();
        $order;
        $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'DESC';

        $folioOrden = '';
        $sku = '';
        $producto = '';
        $servicio = '';
        $fechaInicioAsignacion = '0000-00-00';
        $fechaFinAsignacion = '9999-12-31';
        $proveedor = '';

        foreach($anexGrid->filtros as $f){
            if($f['columna'] == 'ordenCompra' && $f['valor'] != ''){
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
            if($f['columna'] == 'fechaInicioAsignacion' && $f['valor'] != ''){
                $fechaInicioAsignacion = $f['valor'];
            }           
            if($f['columna'] == 'fechaFinAsignacion' && $f['valor'] != ''){
                $fechaFinAsignacion = $f['valor'];
            }
            if($f['columna'] == 'proveedor' && $f['valor'] != ''){
                $proveedor = $f['valor'];
            }
        }

        $registros= DB::table('productosServiciosAsignacion as psa')
            ->select('psa.idProductoServAsig','psa.idGrupoAsignacion',
                DB::raw('IF(oc.oc_mov_internos = 1,CONCAT("Entradas directas ",oc.nombreEmpresa),oc.codigoOrden) AS ordenCompra'),
                DB::raw('(poc.codigo) AS sku'),
                DB::raw('(poc.nombreRefaccion) AS producto'),
                DB::raw('(psa.cantidadAservicio) AS cantidadAsignada'),
                DB::raw('IFNULL(f.idServicios,"-") AS servicio_referencia'),
                'psa.detalles',
                DB::raw('DATE_FORMAT(psa.fechaAsignacion,"%d/%m/%Y") AS fecha_formato'),
                DB::raw('(psa.fechaAsignacion) AS fechaInicioAsignacion'),
                DB::raw('(psa.fechaAsignacion) AS fechaFinAsignacion'),
                DB::raw('(p.razonSocialProv) AS proveedor'),
                'oc.nombreEmpresa',
                DB::raw('CONCAT(IFNULL(en.numeroSerie,"-")," / ",IFNULL(en.modelo,"-")) AS serieYmodelo'),
                DB::raw("oc_2.codigoOrden as oc_asignada"),
                'en.almacen'

            )
            ->leftJoin('ordenCompra as oc', 'psa.idOrden', '=', 'oc.idOrden')
            ->leftJoin('ordenCompra as oc_2', 'psa.oc_destino', '=', 'oc_2.idOrden')
            ->leftJoin('proveedores as p', 'oc.idProveedor', '=', 'p.idProveedor')
            ->leftJoin('facturas as f', 'psa.id_factura_referencia', '=', 'f.idFactura')
            ->leftJoin('productosOrdenCompra as poc', 'psa.idProducto', '=', 'poc.idProducto')
            ->leftJoin('productosPorEntradaAlmacen as en', 'psa.idProductoEntrada', '=', 'en.idProductoEntrada')
            ->where('psa.tipo_asignacion', '=', "a_oc")
            ->where(DB::raw('IF(oc.oc_mov_internos = 1,CONCAT("Entradas directas ",oc.nombreEmpresa),oc.codigoOrden)'), 'like', '%'.$folioOrden.'%')
            ->where('poc.codigo', 'like', '%'.$sku.'%')
            ->where('poc.nombreRefaccion', 'like', '%'.$producto.'%')
            ->where('f.idServicios', 'like', '%'.$servicio.'%')
            ->where('p.razonSocialProv', 'like', '%'.$proveedor.'%')
            ->where('psa.fechaAsignacion', '>=', $fechaInicioAsignacion)
            ->where('psa.fechaAsignacion', '<=', $fechaFinAsignacion)
            ->where(function($query) {
                $anexGrid = new anexGrid();
                $serieYmodelo='';
                
                foreach($anexGrid->filtros as $f){
                    if($f['columna'] == 'serieYmodelo' && $f['valor'] != ''){
                        $serieYmodelo = $f['valor'];
                    }
                }
                
                if($serieYmodelo == '-' || $serieYmodelo ==''){
                    $query->where('en.numeroSerie', 'like', '%'.$serieYmodelo.'%')
                    ->orWhere('en.modelo', 'like', '%'.$serieYmodelo.'%')
                    ->orWhereNull('en.numeroSerie')
                    ->orWhereNull('en.modelo');
                }else{
                    $query->where('en.numeroSerie', 'like', '%'.$serieYmodelo.'%')
                    ->orWhere('en.modelo', 'like', '%'.$serieYmodelo.'%');
                }
            })
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
        ->get();

        $total= DB::table('productosServiciosAsignacion as psa')
            ->select('psa.idProductoServAsig','psa.idGrupoAsignacion',
                DB::raw('(oc.codigoOrden) AS ordenCompra'),
                DB::raw('(poc.codigo) AS sku'),
                DB::raw('(poc.nombreRefaccion) AS producto'),
                DB::raw('(psa.cantidadAservicio) AS cantidadAsignada'),
                DB::raw('IFNULL(f.idServicios,"-") AS servicio'),
                'psa.detalles',
                DB::raw('DATE_FORMAT(psa.fechaAsignacion,"%d/%m/%Y") AS fecha_formato'),
                DB::raw('(psa.fechaAsignacion) AS fechaInicioAsignacion'),
                DB::raw('(psa.fechaAsignacion) AS fechaFinAsignacion'),
                DB::raw('(p.razonSocialProv) AS proveedor'),
                'oc.nombreEmpresa',
                DB::raw('CONCAT(IFNULL(en.numeroSerie,"-")," / ",IFNULL(en.modelo,"-")) AS serieYmodelo')
            )
            ->leftJoin('ordenCompra as oc', 'psa.idOrden', '=', 'oc.idOrden')
            ->leftJoin('proveedores as p', 'oc.idProveedor', '=', 'p.idProveedor')
            ->leftJoin('facturas as f', 'psa.id_factura_referencia', '=', 'f.idFactura')
            ->leftJoin('productosOrdenCompra as poc', 'psa.idProducto', '=', 'poc.idProducto')
            ->leftJoin('productosPorEntradaAlmacen as en', 'psa.idProductoEntrada', '=', 'en.idProductoEntrada')
            ->where('psa.tipo_asignacion', '=', "a_oc")
            ->where(DB::raw('IF(oc.oc_mov_internos = 1,CONCAT("Entradas directas ",oc.nombreEmpresa),oc.codigoOrden)'), 'like', '%'.$folioOrden.'%')
            ->where('poc.codigo', 'like', '%'.$sku.'%')
            ->where('poc.nombreRefaccion', 'like', '%'.$producto.'%')
            ->where('f.idServicios', 'like', '%'.$servicio.'%')
            ->where('p.razonSocialProv', 'like', '%'.$proveedor.'%')
            ->where('psa.fechaAsignacion', '>=', $fechaInicioAsignacion)
            ->where('psa.fechaAsignacion', '<=', $fechaFinAsignacion)
            ->where(function($query) {
                $anexGrid = new anexGrid();
                $serieYmodelo='';
                
                foreach($anexGrid->filtros as $f){
                    if($f['columna'] == 'serieYmodelo' && $f['valor'] != ''){
                        $serieYmodelo = $f['valor'];
                    }
                }
                
                if($serieYmodelo == '-' || $serieYmodelo ==''){
                    $query->where('en.numeroSerie', 'like', '%'.$serieYmodelo.'%')
                    ->orWhere('en.modelo', 'like', '%'.$serieYmodelo.'%')
                    ->orWhereNull('en.numeroSerie')
                    ->orWhereNull('en.modelo');
                }else{
                    $query->where('en.numeroSerie', 'like', '%'.$serieYmodelo.'%')
                    ->orWhere('en.modelo', 'like', '%'.$serieYmodelo.'%');
                }
            })
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
        ->count();
        
        $data = array(
            'total' => $total,
            'data' => $registros
        );

        return response()->json($data, 200);
    }

    public function anexgrid_get_reporteProductos_otras_salidas(){
        $anexGrid = new anexGrid();
        $order;
        $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'DESC';

        $folioOrden = '';
        $sku = '';
        $producto = '';
        $servicio = '';
        $fechaInicioAsignacion = '0000-00-00';
        $fechaFinAsignacion = '9999-12-31';
        $proveedor = '';

        foreach($anexGrid->filtros as $f){
            if($f['columna'] == 'ordenCompra' && $f['valor'] != ''){
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
            if($f['columna'] == 'fechaInicioAsignacion' && $f['valor'] != ''){
                $fechaInicioAsignacion = $f['valor'];
            }           
            if($f['columna'] == 'fechaFinAsignacion' && $f['valor'] != ''){
                $fechaFinAsignacion = $f['valor'];
            }
            if($f['columna'] == 'proveedor' && $f['valor'] != ''){
                $proveedor = $f['valor'];
            }
        }

        $registros= DB::table('productosServiciosAsignacion as psa')
            ->select('psa.idProductoServAsig','psa.idGrupoAsignacion',
                DB::raw('IF(oc.oc_mov_internos = 1,CONCAT("Entradas directas ",oc.nombreEmpresa),oc.codigoOrden) AS ordenCompra'),
                DB::raw('(poc.codigo) AS sku'),
                DB::raw('(poc.nombreRefaccion) AS producto'),
                DB::raw('(psa.cantidadAservicio) AS cantidadAsignada'),
                // DB::raw('IFNULL(f.idServicios,"-") AS servicio_referencia'),
                'psa.detalles',
                // 'psa.motivo_salida',
                DB::raw('IF(psa.motivo_salida=1,"Garantía",IF(psa.motivo_salida=2,"Merma","Descripción")) AS motivo_salida'),
                DB::raw('DATE_FORMAT(psa.fechaAsignacion,"%d/%m/%Y") AS fecha_formato'),
                DB::raw('(psa.fechaAsignacion) AS fechaInicioAsignacion'),
                DB::raw('(psa.fechaAsignacion) AS fechaFinAsignacion'),
                DB::raw('(p.razonSocialProv) AS proveedor'),
                'oc.nombreEmpresa',
                DB::raw('CONCAT(IFNULL(en.numeroSerie,"-")," / ",IFNULL(en.modelo,"-")) AS serieYmodelo'),
                // DB::raw("oc_2.codigoOrden as oc_asignada"),
                'en.almacen'

            )
            ->leftJoin('ordenCompra as oc', 'psa.idOrden', '=', 'oc.idOrden')
            ->leftJoin('ordenCompra as oc_2', 'psa.oc_destino', '=', 'oc_2.idOrden')
            ->leftJoin('proveedores as p', 'oc.idProveedor', '=', 'p.idProveedor')
            ->leftJoin('facturas as f', 'psa.id_factura_referencia', '=', 'f.idFactura')
            ->leftJoin('productosOrdenCompra as poc', 'psa.idProducto', '=', 'poc.idProducto')
            ->leftJoin('productosPorEntradaAlmacen as en', 'psa.idProductoEntrada', '=', 'en.idProductoEntrada')
            ->where('psa.tipo_asignacion', '=', "a_otro")
            ->where(DB::raw('IF(oc.oc_mov_internos = 1,CONCAT("Entradas directas ",oc.nombreEmpresa),oc.codigoOrden)'), 'like', '%'.$folioOrden.'%')
            ->where('poc.codigo', 'like', '%'.$sku.'%')
            ->where('poc.nombreRefaccion', 'like', '%'.$producto.'%')
            // ->where('f.idServicios', 'like', '%'.$servicio.'%')
            ->where('p.razonSocialProv', 'like', '%'.$proveedor.'%')
            ->where('psa.fechaAsignacion', '>=', $fechaInicioAsignacion)
            ->where('psa.fechaAsignacion', '<=', $fechaFinAsignacion)
            ->where(function($query) {
                $anexGrid = new anexGrid();
                $serieYmodelo='';
                
                foreach($anexGrid->filtros as $f){
                    if($f['columna'] == 'serieYmodelo' && $f['valor'] != ''){
                        $serieYmodelo = $f['valor'];
                    }
                }
                
                if($serieYmodelo == '-' || $serieYmodelo ==''){
                    $query->where('en.numeroSerie', 'like', '%'.$serieYmodelo.'%')
                    ->orWhere('en.modelo', 'like', '%'.$serieYmodelo.'%')
                    ->orWhereNull('en.numeroSerie')
                    ->orWhereNull('en.modelo');
                }else{
                    $query->where('en.numeroSerie', 'like', '%'.$serieYmodelo.'%')
                    ->orWhere('en.modelo', 'like', '%'.$serieYmodelo.'%');
                }
            })
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
        ->get();

        $total= DB::table('productosServiciosAsignacion as psa')
            ->select('psa.idProductoServAsig','psa.idGrupoAsignacion',
                DB::raw('(oc.codigoOrden) AS ordenCompra'),
                DB::raw('(poc.codigo) AS sku'),
                DB::raw('(poc.nombreRefaccion) AS producto'),
                DB::raw('(psa.cantidadAservicio) AS cantidadAsignada'),
                // DB::raw('IFNULL(f.idServicios,"-") AS servicio_referencia'),
                'psa.detalles',
                // 'psa.motivo_salida',
                DB::raw('IF(psa.motivo_salida=1,"Garantía",IF(psa.motivo_salida=2,"Merma","Descripción")) AS motivo_salida'),
                DB::raw('DATE_FORMAT(psa.fechaAsignacion,"%d/%m/%Y") AS fecha_formato'),
                DB::raw('(psa.fechaAsignacion) AS fechaInicioAsignacion'),
                DB::raw('(psa.fechaAsignacion) AS fechaFinAsignacion'),
                DB::raw('(p.razonSocialProv) AS proveedor'),
                'oc.nombreEmpresa',
                DB::raw('CONCAT(IFNULL(en.numeroSerie,"-")," / ",IFNULL(en.modelo,"-")) AS serieYmodelo'),
                // DB::raw("oc_2.codigoOrden as oc_asignada"),
                'en.almacen'

            )
            ->leftJoin('ordenCompra as oc', 'psa.idOrden', '=', 'oc.idOrden')
            ->leftJoin('ordenCompra as oc_2', 'psa.oc_destino', '=', 'oc_2.idOrden')
            ->leftJoin('proveedores as p', 'oc.idProveedor', '=', 'p.idProveedor')
            ->leftJoin('facturas as f', 'psa.id_factura_referencia', '=', 'f.idFactura')
            ->leftJoin('productosOrdenCompra as poc', 'psa.idProducto', '=', 'poc.idProducto')
            ->leftJoin('productosPorEntradaAlmacen as en', 'psa.idProductoEntrada', '=', 'en.idProductoEntrada')
            ->where('psa.tipo_asignacion', '=', "a_otro")
            ->where(DB::raw('IF(oc.oc_mov_internos = 1,CONCAT("Entradas directas ",oc.nombreEmpresa),oc.codigoOrden)'), 'like', '%'.$folioOrden.'%')
            ->where('poc.codigo', 'like', '%'.$sku.'%')
            ->where('poc.nombreRefaccion', 'like', '%'.$producto.'%')
            // ->where('f.idServicios', 'like', '%'.$servicio.'%')
            ->where('p.razonSocialProv', 'like', '%'.$proveedor.'%')
            ->where('psa.fechaAsignacion', '>=', $fechaInicioAsignacion)
            ->where('psa.fechaAsignacion', '<=', $fechaFinAsignacion)
            ->where(function($query) {
                $anexGrid = new anexGrid();
                $serieYmodelo='';
                
                foreach($anexGrid->filtros as $f){
                    if($f['columna'] == 'serieYmodelo' && $f['valor'] != ''){
                        $serieYmodelo = $f['valor'];
                    }
                }
                
                if($serieYmodelo == '-' || $serieYmodelo ==''){
                    $query->where('en.numeroSerie', 'like', '%'.$serieYmodelo.'%')
                    ->orWhere('en.modelo', 'like', '%'.$serieYmodelo.'%')
                    ->orWhereNull('en.numeroSerie')
                    ->orWhereNull('en.modelo');
                }else{
                    $query->where('en.numeroSerie', 'like', '%'.$serieYmodelo.'%')
                    ->orWhere('en.modelo', 'like', '%'.$serieYmodelo.'%');
                }
            })
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
        ->count();
        
        $data = array(
            'total' => $total,
            'data' => $registros
        );

        return response()->json($data, 200);
    }

    public function reporteRastreoAsignacion_productos(){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');        

        if($sname == '' or $sidu =='' or $stipo==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else{
            
            $servicios = DB::table('facturas')
            ->select('idFactura','idServicios')
            ->where('activo', '=', 'si')
            ->where('activo', '=', 'Si')
            ->orderBy('idServicios','DESC')
            ->get();
            
            return view ('reporte_rastreo_asignacion')
            ->with('servicios',$servicios);
        }
    }

    public function obtenerOrdenesCompra_destino($id){

        $ordenesCompra = \DB::select("SELECT poc.idOrden, oc.codigoOrden FROM productosOrdenCompra AS poc
        LEFT JOIN ordenCompra AS oc ON oc.idOrden = poc.idOrden
        WHERE poc.idPartesVenta = (
            SELECT poc_2.idPartesVenta FROM productosPorEntradaAlmacen AS pea
            LEFT JOIN productosOrdenCompra AS poc_2 ON poc_2.idProducto = pea.idProducto
            WHERE pea.idProductoEntrada = $id
        )");

        return $ordenesCompra;
    }

    public function obetener_cantidad_solicitada_en_oc(Request $request){

        $oc_destino =$request->oc_destino;
        $idProductoEntrada=$request->idProductoEntrada;

        $cantidad_asignada = \DB::select("SELECT
            IFNULL(SUM(psa.cantidadAservicio),0) AS cantidad_asignada 
            FROM productosServiciosAsignacion  as psa WHERE psa.oc_destino = $oc_destino AND psa.idProducto = (
                SELECT pea.idProducto FROM productosPorEntradaAlmacen AS pea
                WHERE pea.idProductoEntrada = $idProductoEntrada
            )"
        );

        $cantidad_solicitada = \DB::select("SELECT
            poc.cantidadOrden,poc.idProducto
            FROM productosOrdenCompra AS poc
            WHERE poc.idOrden = $oc_destino AND poc.idPartesVenta = (
                SELECT poc_2.idPartesVenta FROM productosOrdenCompra as poc_2 WHERE poc_2.idProducto =(
                    SELECT pea.idProducto FROM productosPorEntradaAlmacen AS pea
                    WHERE pea.idProductoEntrada = $idProductoEntrada
                )
            )
            "
        );

        // $cantidad_porasignar = $cantidad_solicitada[0]->cantidadOrden - $cantidad_asignada[0]->cantidad_asignada;

        return array("cantidad_solicitada" => $cantidad_solicitada[0]->cantidadOrden, "cantidad_entregada" => $cantidad_asignada[0]->cantidad_asignada,"idProducto_destino" => $cantidad_solicitada[0]->idProducto);
    }

    public function consulta_asignaciones_oc(){
        
        $condicion = ["psa.idProductoServAsig",">",0];
        
        $consulta = DB::table('productosServiciosAsignacion as psa')
            ->select('psa.idProductoServAsig','psa.idGrupoAsignacion',
                DB::raw('(oc.codigoOrden) AS ordenCompra'),
                DB::raw('(oc.fechaOrden) AS fecha_oc'),
                DB::raw('(poc.codigo) AS sku'),
                DB::raw('(poc.nombreRefaccion) AS producto'),
                DB::raw('(poc.cantidadOrden) AS cantidad_producto_en_oc'),
                DB::raw('(psa.cantidadAservicio) AS cantidadAsignada'),
                DB::raw('IFNULL(f.idServicios,"-") AS servicio_referencia'),
                'psa.detalles',
                DB::raw('DATE_FORMAT(psa.fechaAsignacion,"%d/%m/%Y") AS fecha_formato'),
                DB::raw('(psa.fechaAsignacion) AS fechaInicioAsignacion'),
                DB::raw('(psa.fechaAsignacion) AS fechaFinAsignacion'),
                DB::raw('(p.razonSocialProv) AS proveedor'),
                'oc.nombreEmpresa',
                DB::raw('CONCAT(IFNULL(en.numeroSerie,"-")," / ",IFNULL(en.modelo,"-")) AS serieYmodelo'),
                DB::raw("oc_2.codigoOrden as oc_asignada"),
                DB::raw("(oc_2.fechaOrden) AS fecha_oc_destino"),
                DB::raw("CONCAT(IF(tipo_asignacion='a_servicio','Servicio: ','Orden de Compra: '),IF(tipo_asignacion='a_servicio',f.idServicios,oc_2.codigoOrden)) as asignacion"),
                DB::raw("IF(tipo_asignacion='a_servicio',f.idServicios,f_2.idServicios) AS servicio_asignado"),
            )
            ->leftJoin('ordenCompra as oc', 'psa.idOrden', '=', 'oc.idOrden')
            ->leftJoin('ordenCompra as oc_2', 'psa.oc_destino', '=', 'oc_2.idOrden')
            ->leftJoin('proveedores as p', 'oc.idProveedor', '=', 'p.idProveedor')
            ->leftJoin('facturas as f', 'psa.idFactura', '=', 'f.idFactura')
            ->leftJoin('facturas as f_2', 'psa.id_factura_referencia', '=', 'f_2.idFactura')
            ->leftJoin('productosOrdenCompra as poc', 'psa.idProducto', '=', 'poc.idProducto')
            ->leftJoin('productosPorEntradaAlmacen as en', 'psa.idProductoEntrada', '=', 'en.idProductoEntrada')
            ->where('psa.tipo_asignacion', '=', "a_oc")
            ->where([$condicion])
        ->get();

        return $consulta;
    }

    public function consulta_rastreo_asignaciones($id = 0){
        $consulta_asignaciones_oc = \DB::select("SELECT
            -- MIN(psa.idProductoServAsig) AS idProductoServAsig_min,
            psa.idOrden
            ,p.razonSocialProv AS proveedor
            ,MAX(pea.almacen) AS almacen
            ,oc.codigoOrden AS asignacion
            FROM productosServiciosAsignacion AS psa
            LEFT JOIN ordenCompra AS oc ON oc.idOrden = psa.idOrden
            LEFT JOIN proveedores AS p ON p.idProveedor = oc.idProveedor
            LEFT JOIN productosPorEntradaAlmacen AS pea ON pea.idProductoEntrada = psa.idProductoEntrada
            WHERE psa.id_factura_referencia = $id  OR psa.idFactura = $id
            GROUP BY psa.idOrden
            order by psa.idOrden
        ");

        $consulta_asignacion_servicio = \DB::select("SELECT
            MAX(psa.idOrden) as idOrden
            ,pea.empresa AS proveedor
            ,'-' AS almacen
            ,fac.idServicios AS asignacion
            FROM productosServiciosAsignacion AS psa
            LEFT JOIN productosPorEntradaAlmacen AS pea ON pea.idProductoEntrada = psa.idProductoEntrada
            LEFT JOIN facturas AS fac ON fac.idFactura = psa.idFactura
            WHERE psa.idFactura = $id
            GROUP BY pea.empresa
        ");


        $consulta = array_merge($consulta_asignaciones_oc, $consulta_asignacion_servicio);
        
        return $consulta;
    }

    public function consulta_detalle_rastreo_asignacion($id = 0){
        // $oc_asignadas = \DB::select("SELECT psa.idOrden, psa.oc_destino FROM productosServiciosAsignacion AS psa
        // LEFT JOIN productosOrdenCompra AS poc ON poc.idProducto = psa.idProducto
        // WHERE psa.idProductoServAsig = $id");

        // $consulta = \DB::select("SELECT
        // oc.codigoOrden,
        // oc.moneda,
        // poc.codigo AS sku,
        // DATE_FORMAT(oc.fechaOrden,'%d/%m/%Y') AS fechaOrden,
        // poc.cantidadOrden,
        // FORMAT(poc.precioOrden,2) AS costo,
        // IFNULL(pasign.cantidadAservicio,poc.cantidadOrden) as cantidad,
        // FORMAT((poc.precioOrden * IFNULL(pasign.cantidadAservicio,poc.cantidadOrden)),2) as subtotal
        // FROM productosOrdenCompra AS poc
        // LEFT JOIN ordenCompra AS oc ON oc.idOrden = poc.idOrden
        // LEFT JOIN productosServiciosAsignacion AS pasign ON pasign.oc_destino = poc.idOrden
        // WHERE poc.idOrden = $id");

        $consulta = \DB::select("SELECT
        oc.codigoOrden,
        poc.codigo AS sku,
        oc.moneda,
        -- pv.numeroParte, pv.serie, pv.modelo, pv.codigo
        DATE_FORMAT(psa.fechaAsignacion,'%d/%m/%Y') AS fechaAsignacion,
        psa.cantidadAservicio,
        pea.almacen
        -- FORMAT(psa.precioOrden,2) AS costo,
        -- IFNULL(pasign.cantidadAservicio,psa.cantidadOrden) as cantidad,
        -- FORMAT((psa.precioOrden * IFNULL(pasign.cantidadAservicio,psa.cantidadOrden)),2) as subtotal
        FROM productosServiciosAsignacion AS psa
        LEFT JOIN ordenCompra AS oc ON oc.idOrden = psa.idOrden
        LEFT JOIN productosOrdenCompra AS poc ON poc.idProducto = psa.idProducto
        LEFT JOIN productosPorEntradaAlmacen AS pea ON pea.idProductoEntrada = psa.idProductoEntrada
        WHERE psa.idOrden = $id
        ORDER BY sku");

        return $consulta;
    }

    public function crearEntradaAlmacen($datos_para_crear_entrada){

        // $datos_oc = \DB::select("SELECT oc.idProveedor,oc.idOrden,oc.nombreEmpresa FROM ordenCompra AS oc WHERE oc.idOrden = (SELECT poc.idOrden FROM productosOrdenCompra AS poc WHERE poc.idProducto = $datos_para_crear_entrada->idProducto)");
        $datos_oc = \DB::select("SELECT oc.idProveedor,oc.idOrden,oc.nombreEmpresa FROM ordenCompra AS oc WHERE oc.idOrden = $datos_para_crear_entrada->oc_destino");
        $datos_parte_venta_producto = \DB::select("SELECT pv.numeroParte, pv.serie, pv.modelo, pv.codigo FROM partesVenta AS pv WHERE pv.idPartesVenta = (SELECT poc.IdPartesVenta FROM productosOrdenCompra AS poc WHERE poc.idProducto = $datos_para_crear_entrada->idProducto_destino)");
        $datos_producto_en_oc = \DB::select("SELECT poc.idFactura FROM productosOrdenCompra AS poc WHERE poc.idProducto = $datos_para_crear_entrada->idProducto_destino");
        $nombre_usuario = Session::get('sesionname')." ".Session::get('sesionpaterno')." ".Session::get('sesionmaterno');

        $entrada = new entradasAlmacen;
        $entrada->idu = Session::get('sesionidu');
        $entrada->nombreUsuario = $nombre_usuario;
        $entrada->fechaEntrada = $datos_para_crear_entrada->fechaAsignacion;
        $entrada->idProveedor = $datos_oc[0]->idProveedor;
        $entrada->recibidoPor = $nombre_usuario;
        $entrada->save();
        // recuperando el id del nuevo registro creado
        $utima_entrada = $entrada->idEntradaAlmacen;

        $productoPorEntrada = new productosPorEntradaAlmacen;
        $productoPorEntrada->idEntradaAlmacen = $utima_entrada;
		$productoPorEntrada->idu = Session::get('sesionidu');
		$productoPorEntrada->nombreUsuario = $nombre_usuario;
		$productoPorEntrada->fechaEntrada = $datos_para_crear_entrada->fechaAsignacion;
		$productoPorEntrada->idOrden = $datos_para_crear_entrada->oc_destino;
		$productoPorEntrada->idProveedor = $datos_oc[0]->idProveedor;
		$productoPorEntrada->empresa = $datos_oc[0]->nombreEmpresa;
		$productoPorEntrada->almacen = $datos_oc[0]->nombreEmpresa;
		$productoPorEntrada->recibidoPor = $nombre_usuario;
		$productoPorEntrada->idProducto = $datos_para_crear_entrada->idProducto_destino;
        // $productoPorEntrada->idProducto = $datos_para_crear_entrada->idProducto;
        // $productoPorEntrada->idNotaEntrada = $datos_oc[0]->idNotaEntrada;
        // $productoPorEntrada->idDetalleNotasEntrada = $datos_oc[0]->idDetalleNotasEntrada;
		$productoPorEntrada->numeroParte = $datos_parte_venta_producto[0]->numeroParte;
		$productoPorEntrada->numeroSerie = $datos_parte_venta_producto[0]->serie;
		$productoPorEntrada->modelo = $datos_parte_venta_producto[0]->modelo;
		$productoPorEntrada->presentacion = "Pieza";
        $productoPorEntrada->fotoPlaca = "Sin archivo";
		$productoPorEntrada->fotoPrincipal = "Sin archivo";
		// $productoPorEntrada->unidadesPorPresentacion =
		$productoPorEntrada->cantidadRecibida = $datos_para_crear_entrada->cantidadAservicio;
		// $productoPorEntrada->observaciones = $datos_oc[0]->observaciones;

        // Si ya tienen numero de factura, entonces ya esta asignada a un servicio
        if($datos_producto_en_oc[0]->idFactura == '' || is_null($datos_producto_en_oc[0]->idFactura)){
            $productoPorEntrada->estatusAsignacion = 0;
            $asignada_flag = false;
        }else{
            $productoPorEntrada->estatusAsignacion = 1;
            $productoPorEntrada->idFactura = $datos_para_crear_entrada->id_factura_referencia;
            $asignada_flag = true;
        }
        $productoPorEntrada->save();
        // recuperando el id del nuevo registro creado
        $idProductoEntrada = $productoPorEntrada->idProductoEntrada;

        if($asignada_flag){

            $asignacion = new productosServiciosAsignacion;
            $asignacion->idGrupoAsignacion = 0;
            $asignacion->idOrden = $datos_para_crear_entrada->oc_destino;
            $asignacion->idProductoEntrada = $idProductoEntrada;
            $asignacion->idProducto = $datos_para_crear_entrada->idProducto;
            $asignacion->idProducto_destino = $datos_para_crear_entrada->idProducto_destino;
            $asignacion->idFactura = $datos_para_crear_entrada->id_factura_referencia;
            $asignacion->tipo_asignacion = "a_servicio";
            $asignacion->cantidadAservicio = $datos_para_crear_entrada->cantidadAservicio;
            $asignacion->fechaAsignacion = $datos_para_crear_entrada->fechaAsignacion;
            $asignacion->quienLLevo = $datos_para_crear_entrada->quienLLevo;
            $asignacion->detalles = $datos_para_crear_entrada->detalles;
            $asignacion->evidencia = "Sin archivo";
            $asignacion->save();
    
            // Se envia tipo de actualizacion 1 = "sumar cantidad asignada"
            $tipoActualizacion = 1;
            $idProductoEntrada = $idProductoEntrada;
            $cantidadAservicio = $datos_para_crear_entrada->cantidadAservicio;
            $idFactura = $datos_para_crear_entrada->id_factura_referencia;
    
            $this->actualizarCantidadesAsignada($idProductoEntrada , $cantidadAservicio , $tipoActualizacion, 0 , $idFactura);
        }

        // recuperando el id del nuevo registro creado
        return $idProductoEntrada;
    }

    public function eliminarProductoEntrada($idProductoEntrada){
        // eliminamos el registro de la BD
        \DB::delete("DELETE FROM productosPorEntradaAlmacen WHERE idProductoEntrada=?",[$idProductoEntrada]);
        
        return;
    }

    public function obtener_cantidad_solicitada_servicio(Request $request){
        $idProductoEntrada=$request->idProductoEntrada;
        $idFactura=$request->idFactura;

        $cantidad_solicitada = \DB::select("SELECT piezas FROM detallereparaciones WHERE idCotizacion = (
            SELECT idCotizacion FROM facturas WHERE idFactura=$idFactura) AND idPartesVenta = (            
                SELECT idPartesVenta FROM productosOrdenCompra WHERE idProducto =(
                    SELECT idProducto FROM productosPorEntradaAlmacen WHERE idProductoEntrada = $idProductoEntrada
                )
            )"
        );

        $cantidad_asignada = \DB::select("SELECT COUNT(idProductoServAsig) as asignadas FROM productosServiciosAsignacion 
            WHERE idFactura = $idFactura AND idProducto = (
                SELECT idProducto FROM productosPorEntradaAlmacen WHERE idProductoEntrada = $idProductoEntrada
            )"
        );

        return array("cantidad_solicitada" => $cantidad_solicitada[0]->piezas, "cantidad_asignada" => $cantidad_asignada[0]->asignadas);
    }

    public function otras_salidas_almacen(){
        return "ok";
    }

}
