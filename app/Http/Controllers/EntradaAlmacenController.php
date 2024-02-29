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
use App\anexGrid;
use App\notasEntrada;
use App\detalleNotasEntrada;
use App\productosOrdenCompra;
use App\ordenCompra;
use DB;
use QrCode;
use PDF;
use Illuminate\Support\Facades\Storage;


class EntradaAlmacenController extends Controller
{

    public function altaEntradaAlmacen(){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');        

        if($sname == '' or $sidu =='' or $stipo==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else{
            
            $proveedor = DB::table('proveedores')          
           ->where('activo', '=','Si')
           ->orderBy('razonSocialProv','asc')
           ->get();

           $consultaMarcaPartesVenta= marcaRefaccion::Where('activo','=','Si')->orderBy('marcaRefaccion','asc')->get();

            return view ('altaEntradaAlmacen')
            ->with('consultaMarcaPartesVenta',$consultaMarcaPartesVenta)
            ->with('proveedor',$proveedor);
        }

    }

    public function obtenerOCproveedor(Request $request){
        $idProveedor=$request->idProveedor;

        // Obteniendo OC con productos que aún no esten recibidos, que la OC este activa que coincida con el idProveedor
        $ordenesCompra=\DB::select("SELECT idOrden,IF(oc_mov_internos = 1,CONCAT('Entradas directas ',nombreEmpresa),codigoOrden) as codigoOrden FROM ordenCompra WHERE idOrden IN (
            SELECT idOrden FROM notasEntrada WHERE notasEntrada.idNotaEntrada IN (
                SELECT idNotaEntrada FROM detalleNotasEntrada WHERE estatusIngresoAlmacen = 0 GROUP BY idNotaEntrada
            ) GROUP BY idOrden
        ) AND idProveedor = $idProveedor");

        return $ordenesCompra;
    }

    public function obtenerEmpresaAndProvedor(Request $request){
        $idOrden=$request->idOrden;

        $registros = DB::table('ordenCompra')
        ->select('codigoOrden','nombreEmpresa','idProveedor')    
        ->where('idOrden', '=', $idOrden)
        ->get();
    
        return $registros;
    }

    public function obtenerNotasEntradaOC(Request $request){
        $notasEntrada = \DB::select("SELECT idNotaEntrada,numeroNota 
        FROM notasEntrada 
        WHERE idOrden = $request->idOrden 
        AND notasEntrada.idNotaEntrada IN (
                SELECT idNotaEntrada FROM detalleNotasEntrada WHERE estatusIngresoAlmacen = 0 GROUP BY idNotaEntrada
            )
        ORDER BY numeroNota ASC");
        return $notasEntrada;
    }

    public function obtenerProductosNE(Request $request){
        $idNotaEntrada=$request->idNotaEntrada;
        $productosEnNota = \DB::select("SELECT idDetalleNotasEntrada, idProducto,
        (SELECT CONCAT(poc.codigo,' / ',poc.nombreRefaccion) FROM productosOrdenCompra AS poc 
            WHERE poc.idProducto = dNe.idProducto
        ) AS producto
        FROM detalleNotasEntrada AS dNe WHERE dNe.idNotaEntrada = $idNotaEntrada AND dNe.estatusIngresoAlmacen = 0");

        return $productosEnNota;
    }

    public function obtenerDatosProductos(Request $request){
        $idDetalleNotasEntrada=$request->idDetalleNotasEntrada;

        $datosProdcuto = \DB::select("SELECT 
        dNe.idDetalleNotasEntrada,dNe.idProducto,dNe.cantidadIngresadaAlmacen,dNe.estatusIngresoAlmacen,
        (dNe.piezasRecibidas - dNe.cantidadIngresadaAlmacen) AS pendientePorIngresar,
        (SELECT numeroParte FROM partesVenta WHERE partesVenta.idPartesVenta = (
            SELECT idPartesVenta FROM productosOrdenCompra AS poc WHERE poc.idProducto = dNe.idProducto)
        ) AS numeroParte,
        (SELECT idFactura FROM productosOrdenCompra AS poc WHERE poc.idProducto = dNe.idProducto) AS idFactura
        FROM detalleNotasEntrada AS dNe WHERE dNe.idDetalleNotasEntrada = $idDetalleNotasEntrada");

        return $datosProdcuto;
    }

    public function crearEntradaAlmacen(Request $request){
        
        $entrada = new entradasAlmacen;
        $entrada->idu=$request->idu;
        $entrada->nombreUsuario=$request->nombreUsuarioLogin;
        $entrada->fechaEntrada=$request->fechaEntrada;
        $entrada->idProveedor=$request->idProveedor;
        $entrada->recibidoPor=$request->recibidoPor;
        $entrada->save();
        
        return $entrada->idEntradaAlmacen;
    }

    public function agregarProductosAentrada(Request $request){
        $idProducto = $request->idProducto;
        $idDetalleNotasEntrada = $request->idDetalleNotasEntrada;
        $cantidadRecibida = $request->cantidadRecibida;

        $motivo_de_entrada = $request->op_motivo_entrada;
        
        // Se cmabia a siempre nada para que no entre al if ya que jeol pidio que ya no se manejaran las entradas
        // por devolucionesmermas etc 08/08/2023
        // $motivo_de_entrada = "";

        $producto_sin_oc = false;
        if ($motivo_de_entrada != "") {
            $producto_sin_oc = true;

            // Consulta para datos del producto
            $datos_producto = \DB::select("SELECT pv.codigo,pv.nombreRefaccion 
            FROM partesVenta AS pv WHERE pv.idPartesVenta = $request->idPartesVenta");

            $producto_orden = new productosOrdenCompra;
            $producto_orden->idOrden = $request->idOrden;
            $producto_orden->tipoServicio = 1;
            $producto_orden->idPartesVenta = $request->idPartesVenta;
            $producto_orden->cantidadOrden = $request->cantidadRecibida;
            $producto_orden->codigo = $datos_producto[0]->codigo;
            $producto_orden->nombreRefaccion = $datos_producto[0]->nombreRefaccion;
            // $producto_orden->notasProducto = "Producto ya existente en almacen y solo fue ingresado al sistema";
            $producto_orden-> save();
    
            $idProducto = $producto_orden->idProducto;
    
            $detalleNota = new detalleNotasEntrada;
            $detalleNota->idNotaEntrada = $request->idNotaEntrada;
            $detalleNota->idProducto = $idProducto;
            $detalleNota->piezasPendientes = 0;
            $detalleNota->piezasRecibidas = $cantidadRecibida;
            $detalleNota->cantidadIngresadaAlmacen = $cantidadRecibida;
            $detalleNota->estatusIngresoAlmacen = 1;
            $detalleNota->save();
            $idDetalleNotasEntrada = $detalleNota->idDetalleNotasEntrada;
        }

        // vista placa
        $file = $request->file('fotoPlaca');
        if($file!=""){
            $ldate = date('Ymd_His_');
            $foto = $file->getClientOriginalName();
            $fotoPlaca = $ldate.$foto;
            \Storage::disk('local')->put($fotoPlaca, \File::get($file));
        }else{
            $fotoPlaca ="Sin archivo";
        }

        // vista principal
        $file2 = $request->file('fotoPrincipal');
        if($file2!=""){
            $ldate = date('Ymd_His_');
            $foto = $file2->getClientOriginalName();
            $fotoPrincipal = $ldate.$foto;
            \Storage::disk('local')->put($fotoPrincipal, \File::get($file2));
        }else{
            $fotoPrincipal ="Sin archivo";
        }
        
        $productoPorEntrada = new productosPorEntradaAlmacen;
        $productoPorEntrada->idEntradaAlmacen = $request->idEntradaAlmacen;
		$productoPorEntrada->idu = $request->idu;
		$productoPorEntrada->nombreUsuario = $request->nombreUsuarioLogin;
		$productoPorEntrada->fechaEntrada = $request->fechaEntrada;
		$productoPorEntrada->idOrden = $request->idOrden;
		$productoPorEntrada->idProveedor = $request->idProveedor;
		$productoPorEntrada->empresa = $request->nombreEmpresa;
		$productoPorEntrada->almacen = $request->almacen;
		$productoPorEntrada->recibidoPor = $request->recibidoPor;
		$productoPorEntrada->idProducto = $idProducto;
        $productoPorEntrada->idNotaEntrada = $request->idNotaEntrada;
        $productoPorEntrada->idDetalleNotasEntrada = $idDetalleNotasEntrada;
		$productoPorEntrada->idFactura = $request->idFactura;
		$productoPorEntrada->numeroParte = $request->numeroParte;
		$productoPorEntrada->numeroSerie = $request->numeroSerie;
		$productoPorEntrada->modelo = $request->modeloProducto;
		$productoPorEntrada->presentacion = $request->presentacion;
		$productoPorEntrada->unidadesPorPresentacion = $request->unidadesPorPresentacion;
		$productoPorEntrada->cantidadRecibida = $cantidadRecibida;
		$productoPorEntrada->fotoPlaca = $fotoPlaca;
		$productoPorEntrada->fotoPrincipal = $fotoPrincipal;
		$productoPorEntrada->observaciones = $request->observaciones;
        // Si ya tienen numero de factura, entonces ya esta asignada a un servicio
        if($request->idFactura == '' || is_null($request->idFactura)){
            $productoPorEntrada->estatusAsignacion = 0;
            $asignada_flag = false;
        }else{
            $productoPorEntrada->estatusAsignacion = 1;
            $productoPorEntrada->cantidadAsignada = $cantidadRecibida;
            $asignada_flag = true;
        }
        $productoPorEntrada->save();


        // recuperando el id del nuevo registro creado
        $idProductoEntrada = $productoPorEntrada->idProductoEntrada;

        if($asignada_flag){

            $asignacion = new productosServiciosAsignacion;
            $asignacion->idGrupoAsignacion = 0;
            $asignacion->idOrden = $request->idOrden;
            $asignacion->idProductoEntrada = $idProductoEntrada;
            $asignacion->idProducto = $idProducto;
            // $asignacion->idProducto_destino = $request->idProducto_destino;
            $asignacion->idFactura = $request->idFactura;
            $asignacion->tipo_asignacion = "a_servicio";
            $asignacion->cantidadAservicio = $cantidadRecibida;
            $asignacion->fechaAsignacion = $request->fechaEntrada;
            // $asignacion->quienLLevo = "ocurre";
            $asignacion->evidencia = "Sin archivo";
            $asignacion->save();
        }

        // Se envia tipo de actualizacion 1 = "sumar cantidad"
        $tipoActualizacion = 1;

        if($producto_sin_oc){
            return;
        }else{
            return $this->actualizarCantidadesEntrega($idProducto , $request->idDetalleNotasEntrada , $cantidadRecibida , $tipoActualizacion);
        }
    }

    public function eliminarProductoEntrada(Request $request){
        $idProductoEntrada=$request->idProductoEntrada;

        $producto= DB::table('productosPorEntradaAlmacen as pea')
            ->select('pea.idDetalleNotasEntrada','pea.idProducto','pea.idEntradaAlmacen','pea.cantidadRecibida','pea.fotoPlaca','pea.fotoPrincipal')            
            ->where('pea.idProductoEntrada','=',$idProductoEntrada)        
        ->get();

        // Primero eliminamos los archivos del servidor
        \Storage::delete($producto[0]->fotoPlaca);
        \Storage::delete($producto[0]->fotoPrincipal);
        
        // eliminamos el registro de la BD
        \DB::delete("DELETE FROM productosPorEntradaAlmacen WHERE idProductoEntrada=?",[$idProductoEntrada]);
        \DB::delete("DELETE FROM productosServiciosAsignacion WHERE idProductoEntrada=?",[$idProductoEntrada]);

        // Se envia tipo de actualizacion 0 = "restar cantidad"
        $tipoActualizacion = 0;
        $idProducto =$producto[0]->idProducto;
        $cantidadRecibida = $producto[0]->cantidadRecibida;
        $idDetalleNotasEntrada = $producto[0]->idDetalleNotasEntrada;

        return $this->actualizarCantidadesEntrega($idProducto , $idDetalleNotasEntrada , $cantidadRecibida , $tipoActualizacion);
    }

    public function actualizarCantidadesEntrega($idProducto , $idDetalleNotasEntrada , $cantidadRecibida, $tipoActualizacion){
        // Consulta para obtener las cantidades actuales del producto
        $producto= DB::table('detalleNotasEntrada')
            ->select('piezasRecibidas','cantidadIngresadaAlmacen')
            ->where('idProducto','=',$idProducto)
            ->where('idDetalleNotasEntrada','=',$idDetalleNotasEntrada)
        ->get();

        // Si el tipoActualizacion es = 1 entonces se agrego un producto ala entrada de lo contrario es una eliminacion de enrtrada de producto
        if($tipoActualizacion == 1){
            // Suma de la cantidad ya entregada con la nueva entrega
            $cantidadEntregada = $producto[0]->cantidadIngresadaAlmacen + $cantidadRecibida;
        }else{
            // Resta de la cantidad ya entregada menos la del producto eliminado
            if($producto[0]->cantidadIngresadaAlmacen > 0){
                $cantidadEntregada = $producto[0]->cantidadIngresadaAlmacen - $cantidadRecibida;
            }
        }

        // Actualización de la cantidad entregada
        \DB::select("UPDATE detalleNotasEntrada SET cantidadIngresadaAlmacen = ? WHERE idProducto = ? AND idDetalleNotasEntrada = ?",[$cantidadEntregada,$idProducto,$idDetalleNotasEntrada]);

        // Actualización del estatus de entrega, si la cantidad entregada es igual a la 
        // cantidad que se pide en la OC entonces el estatus es " 1 = entregado" de lo contrario es "0 = incompleto"
        if($cantidadEntregada == $producto[0]->piezasRecibidas){
            \DB::select("UPDATE detalleNotasEntrada SET estatusIngresoAlmacen = 1 WHERE idProducto = ? AND idDetalleNotasEntrada = ?",[$idProducto,$idDetalleNotasEntrada]);
        }else{
            \DB::select("UPDATE detalleNotasEntrada SET estatusIngresoAlmacen = 0 WHERE idProducto = ? AND idDetalleNotasEntrada = ?",[$idProducto,$idDetalleNotasEntrada]);
        }
        
        return;
    }

    public function anexgrid_get_agregadosAentrada(){

        $anexGrid = new anexGrid();
        $order;
        $idEntradaAlmacen = $anexGrid->parametros[0];
        $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'DESC';

        $registros= DB::table('productosPorEntradaAlmacen as pea')
            ->select('idProductoEntrada',
                DB::raw('(poc.codigo) AS sku'),
                DB::raw('(poc.nombreRefaccion) AS producto'),
                DB::raw('(oc.codigoOrden) AS ordenCompra'),
                'pea.numeroSerie',
                'pea.cantidadRecibida',
                'pea.observaciones',
            )
            ->leftJoin('ordenCompra as oc', 'pea.idOrden', '=', 'oc.idOrden')
            ->leftJoin('productosOrdenCompra as poc', 'pea.idProducto', '=', 'poc.idProducto')
            ->where('pea.idEntradaAlmacen','=',$idEntradaAlmacen)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
        ->get();

        $total= DB::table('productosPorEntradaAlmacen as pea')
            ->select('idProductoEntrada',
                DB::raw('(poc.codigo) AS sku'),
                DB::raw('(poc.nombreRefaccion) AS producto'),
                DB::raw('(oc.codigoOrden) AS ordenCompra'),
                'pea.numeroSerie',
                'pea.cantidadRecibida',
                'pea.observaciones',
            )
            ->leftJoin('ordenCompra as oc', 'pea.idOrden', '=', 'oc.idOrden')
            ->leftJoin('productosOrdenCompra as poc', 'pea.idProducto', '=', 'poc.idProducto')
            ->where('pea.idEntradaAlmacen','=',$idEntradaAlmacen)
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

    public function reporteEntradaAlmacen(){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');        

        if($sname == '' or $sidu =='' or $stipo==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else{
            return view('reporteEntradaAlmacen')
            ->with('sesiontipo',$stipo);
        }
    }

    public function anexgrid_get_reporteProductosPorEntrada(){
        $anexGrid = new anexGrid();
        $order;
        $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'DESC';
        
        $anexGrid->parametros[0] == 'all' ? $estatusAsignacion = '' : $estatusAsignacion = $anexGrid->parametros[0];
        $folioOrden = '';
        $notaEntrada = '';
        $sku = '';
        $producto = '';
        $fechaInicioEntrada = '0000-00-00';
        $fechaFinEntrada = '9999-12-31';
        $proveedor = '';
        $empresa = '';
        $almacen = '';

        foreach($anexGrid->filtros as $f){
            if($f['columna'] == 'ordenCompra' && $f['valor'] != ''){
                $folioOrden = $f['valor'];
            }
            if($f['columna'] == 'sku' && $f['valor'] != ''){
                $sku = $f['valor'];
            }
            // if($f['columna'] == 'notaEntrada' && $f['valor'] != ''){
            //     $notaEntrada = $f['valor'];
            // }
            if($f['columna'] == 'producto' && $f['valor'] != ''){
                $producto = $f['valor'];
            }
            if($f['columna'] == 'fechaInicioEntrada' && $f['valor'] != ''){
                $fechaInicioEntrada = $f['valor'];
            }           
            if($f['columna'] == 'fechaFinEntrada' && $f['valor'] != ''){
                $fechaFinEntrada = $f['valor'];
            }
            if($f['columna'] == 'proveedor' && $f['valor'] != ''){
                $proveedor = $f['valor'];
            }
            if($f['columna'] == 'empresa' && $f['valor'] != ''){
                $empresa = $f['valor'];
            }
            if($f['columna'] == 'almacen' && $f['valor'] != ''){
                $almacen = $f['valor'];
            }
        }

        $registros= DB::table('productosPorEntradaAlmacen as en')
            ->select('en.idProductoEntrada',
                DB::raw('(ea.nombreUsuario) AS registradoPor'),
                'ea.recibidoPor',
                'en.numeroParte',
                'en.presentacion',
                'en.unidadesPorPresentacion',
                DB::raw('IF(oc.oc_mov_internos = 1,CONCAT("Entradas directas ",oc.nombreEmpresa),oc.codigoOrden) AS ordenCompra'),
                DB::raw('IFNULL(ne.numeroNota,"-") AS notaEntrada'),
                DB::raw('(poc.codigo) AS sku'),
                DB::raw('(poc.nombreRefaccion) AS producto'),
                DB::raw('(en.cantidadRecibida) AS cantidadRecibida'),
                DB::raw('IFNULL(f.idServicios,"-") AS servicio'),
                'en.observaciones',
                DB::raw('DATE_FORMAT(en.fechaEntrada,"%d/%m/%Y") AS fecha_formato'),
                DB::raw('(en.fechaEntrada) AS fechaInicioEntrada'),
                DB::raw('(en.fechaEntrada) AS fechaFinEntrada'),
                DB::raw('(p.razonSocialProv) AS proveedor'),
                'en.empresa',
                'en.almacen',
                DB::raw('CONCAT(IFNULL(en.numeroSerie,"-")," / ",IFNULL(en.modelo,"-")) AS serieYmodelo'),
                DB::raw('(en.cantidadRecibida - en.cantidadAsignada) AS cantidadStock'),
                'en.fotoPlaca',
                'en.fotoPrincipal',
                'en.cantidadAsignada'
            )
            ->leftJoin('entradasAlmacen as ea', 'en.idEntradaAlmacen', '=', 'ea.idEntradaAlmacen')
            ->leftJoin('ordenCompra as oc', 'en.idOrden', '=', 'oc.idOrden')
            ->leftJoin('proveedores as p', 'oc.idProveedor', '=', 'p.idProveedor')
            ->leftJoin('notasEntrada as ne', 'en.idNotaEntrada', '=', 'ne.idNotaEntrada')
            ->leftJoin('facturas as f', 'en.idFactura', '=', 'f.idFactura')
            ->leftJoin('productosOrdenCompra as poc', 'en.idProducto', '=', 'poc.idProducto')
            ->where(DB::raw('IF(oc.oc_mov_internos = 1,CONCAT("Entradas directas ",oc.nombreEmpresa),oc.codigoOrden)'), 'like', '%'.$folioOrden.'%')
            // ->where('ne.numeroNota', 'like', '%'.$notaEntrada.'%')
            // Esta validacion es poque los campos de NOTA ENTRADA contienen nulos
            // ->where(function($query) {
            //     $anexGrid = new anexGrid();
            //     $notaEntrada='';
                
            //     foreach($anexGrid->filtros as $f){
            //         if($f['columna'] == 'notaEntrada' && $f['valor'] != ''){
            //             $notaEntrada = $f['valor'];
            //         }
            //     }
                
            //     if($notaEntrada == '-' || $notaEntrada ==''){
            //         $query->where('f.idNotaEntrada', 'like', '%'.$notaEntrada.'%')
            //         ->orWhereNull('f.idNotaEntrada');
            //     }else{
            //         $query->where('f.idNotaEntrada', 'like', '%'.$notaEntrada.'%');
            //     }
            // })
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
            // ->where('en.empresa', 'like', '%'.$empresa.'%')
            ->where('en.almacen', 'like', '%'.$almacen.'%')
            ->where('en.fechaEntrada', '>=', $fechaInicioEntrada)
            ->where('en.fechaEntrada', '<=', $fechaFinEntrada)
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
            ->where('en.estatusAsignacion', 'like', '%'.$estatusAsignacion.'%')
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
        ->get();

        $total= DB::table('productosPorEntradaAlmacen as en')
            ->select('en.idProductoEntrada',
                DB::raw('(ea.nombreUsuario) AS registradoPor'),
                'ea.recibidoPor',
                'en.numeroParte',
                'en.presentacion',
                'en.unidadesPorPresentacion',
                DB::raw('(oc.codigoOrden) AS ordenCompra'),
                DB::raw('(ne.numeroNota) AS notaEntrada'),
                DB::raw('(poc.codigo) AS sku'),
                DB::raw('(poc.nombreRefaccion) AS producto'),
                DB::raw('(en.cantidadRecibida) AS cantidadRecibida'),
                DB::raw('IFNULL(f.idServicios,"-") AS servicio'),
                'en.observaciones',
                DB::raw('DATE_FORMAT(en.fechaEntrada,"%d/%m/%Y") AS fecha_formato'),
                DB::raw('(en.fechaEntrada) AS fechaInicioEntrada'),
                DB::raw('(en.fechaEntrada) AS fechaFinEntrada'),
                DB::raw('(p.razonSocialProv) AS proveedor'),
                'en.empresa',
                'en.almacen',
                DB::raw('CONCAT(IFNULL(en.numeroSerie,"-")," / ",IFNULL(en.modelo,"-")) AS serieYmodelo'),
                DB::raw('(en.cantidadRecibida - en.cantidadAsignada) AS cantidadStock'),
                'en.fotoPlaca',
                'en.fotoPrincipal'
            )
            ->leftJoin('entradasAlmacen as ea', 'en.idEntradaAlmacen', '=', 'ea.idEntradaAlmacen')
            ->leftJoin('ordenCompra as oc', 'en.idOrden', '=', 'oc.idOrden')
            ->leftJoin('proveedores as p', 'oc.idProveedor', '=', 'p.idProveedor')
            ->leftJoin('notasEntrada as ne', 'en.idNotaEntrada', '=', 'ne.idNotaEntrada')
            ->leftJoin('facturas as f', 'en.idFactura', '=', 'f.idFactura')
            ->leftJoin('productosOrdenCompra as poc', 'en.idProducto', '=', 'poc.idProducto')
            ->where(DB::raw('IF(oc.oc_mov_internos = 1,CONCAT("Entradas directas ",oc.nombreEmpresa),oc.codigoOrden)'), 'like', '%'.$folioOrden.'%')
            ->where( 'ne.numeroNota', 'like', '%'.$notaEntrada.'%')
            ->where('poc.codigo', 'like', '%'.$sku.'%')
            ->where('poc.nombreRefaccion', 'like', '%'.$producto.'%')
            // Esta validacion es poque el campo idServicios contienen nulos
            ->where(function($query){
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
            // ->where('en.empresa', 'like', '%'.$empresa.'%')
            ->where('en.almacen', 'like', '%'.$almacen.'%')
            ->where('en.fechaEntrada', '>=', $fechaInicioEntrada)
            ->where('en.fechaEntrada', '<=', $fechaFinEntrada)
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
            ->where('en.estatusAsignacion', 'like', '%'.$estatusAsignacion.'%')
        ->count();
        
        $data = array(
            'total' => $total,
            'data' => $registros
        );

        return response()->json($data, 200);
    }

    function generarEtiquetaEntradaAlmacen(Request $request){
        $registros= DB::table('productosPorEntradaAlmacen as pea')
            ->select(
                // 'idProductoEntrada',
                'prov.razonSocialProv',
                DB::raw('(poc.codigo) AS sku'),
                DB::raw('(poc.nombreRefaccion) AS descripcion'),
                DB::raw('(oc.codigoOrden) AS ordenCompra'),
                DB::raw('DATE_FORMAT(pea.fechaEntrada,"%d/%m/%Y") AS fechaEntrada'),
                'pea.numeroSerie',
                'pea.empresa'
                // 'pea.cantidadRecibida'
            )
            ->leftJoin('ordenCompra as oc', 'pea.idOrden', '=', 'oc.idOrden')
            ->leftJoin('productosOrdenCompra as poc', 'pea.idProducto', '=', 'poc.idProducto')
            ->leftJoin('proveedores as prov', 'pea.idProveedor', '=', 'prov.idProveedor')
            ->where('pea.idProductoEntrada','=',$request->idProductoEntrada)
        ->get();
        
        $texto = $registros[0]->empresa."\n".$registros[0]->sku."\n".$registros[0]->descripcion."\n".$registros[0]->ordenCompra."\n".$registros[0]->fechaEntrada."\n".$registros[0]->numeroSerie;        
        
        $pdf = \PDF::loadView('etiquetaEntradaAlmacen',['registros'=>$registros[0],'texto'=>$texto]);
        return $pdf->stream('etiqueta.pdf');
    }

    public function obtener_datos_entrada(Request $request){
        $idProductoEntrada=$request->idProductoEntrada;

        $datos = \DB::select("SELECT * FROM productosPorEntradaAlmacen WHERE idProductoEntrada = $idProductoEntrada");
        return $datos;
    }

    public function editarProductoEntrada(Request $request){

        // vista placa
        $file = $request->file('fotoPlaca_edit');
        if($file!=""){
            $ldate = date('Ymd_His_');
            $foto = $file->getClientOriginalName();
            $fotoPlaca = $ldate.$foto;
            \Storage::disk('local')->put($fotoPlaca, \File::get($file));
        }else{
            $fotoPlaca ="Sin archivo";
        }

        // vista principal
        $file2 = $request->file('fotoPrincipal_edit');
        if($file2!=""){
            $ldate = date('Ymd_His_');
            $foto = $file2->getClientOriginalName();
            $fotoPrincipal = $ldate.$foto;
            \Storage::disk('local')->put($fotoPrincipal, \File::get($file2));
        }else{
            $fotoPrincipal ="Sin archivo";
        }
        

        $productoPorEntrada = productosPorEntradaAlmacen::find($request->idProductoEntrada_edit);
		$productoPorEntrada->numeroParte = $request->numeroParte_edit;
		$productoPorEntrada->numeroSerie = $request->numeroSerie_edit;
		$productoPorEntrada->modelo = $request->modeloProducto_edit;
		$productoPorEntrada->presentacion = $request->presentacion_edit;
		$productoPorEntrada->unidadesPorPresentacion = $request->unidadesPorPresentacion_edit;
        $productoPorEntrada->fotoPlaca = $fotoPlaca;
		$productoPorEntrada->fotoPrincipal = $fotoPrincipal;
		$productoPorEntrada->observaciones = $request->observaciones_edit;
        $productoPorEntrada->save();

        return json_encode(array("status" => true));
    }
}
