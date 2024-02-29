<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\partesVenta;
use App\marcaRefaccion;
use App\tiporefacciones;
use App\piezasAsignadas;
use Session;
use DB;

class AsignacionPiezasController extends Controller
{
    public function asignacionPiezas(){
        $consultaFacturas=\DB::select("SELECT idFactura, numeroFactura, razonSocial 
        FROM facturas AS f INNER JOIN clientes AS c ON c.idc = f.idc WHERE idFactura != '' AND idCotizacion !='' ORDER BY idFactura DESC");
        
        return view('AsignacionPiezas.asignacion')
        ->with('consultaFacturas',$consultaFacturas);
    }

    public function informacionCotizacion(Request $request){
        $idFactura=$request->idFactura;
        $consultaCotizacion = \DB::select("SELECT f.idFactura, co.idCotizacion,co.idc,co.idu,co.idSucursal,co.numeroCotizacion,
                                co.cotizacionOriginal,co.fechaCotizacion,co.estatus,co.tipoMoneda,
                                CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
                                co.noEquipos,co.dirigidoA,co.conCopia,
                                FORMAT(co.montoTotal,2) AS montoTotal,c.razonSocial AS cliente, s.sucursal AS sucursal, 
                                co.estatus,co.check1,co.textoCheck1,co.check2,co.textoCheck2,co.check3,co.textoCheck3,co.check4,
                                co.textoCheck4,co.check5,co.textoCheck5,co.check6,co.textoCheck6,co.check7,co.textoCheck7
                                FROM cotizaciones AS co
                                INNER JOIN facturas AS f ON f.idCotizacion= co.idCotizacion
                                INNER JOIN usuarios AS u ON u.idu= co.idu
                                INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
                                INNER JOIN clientes AS c ON c.idc= co.idc
                                WHERE idFactura=?",[$idFactura]);
        $idCotizacion=$consultaCotizacion[0]->idCotizacion;
        $equiposReparados = \DB::select("SELECT dr.tipoCotizacion,dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion, dr.descripcion,dr.modelo,dr.numeroCotizacion,dr.notas,re.GCMid,re.complementoGCMid, dr.semanasEntrega, FORMAT(dr.montoEquipo,2) AS montoEquipo,
                                FORMAT(dr.montoFinanciamiento,2) AS montoFinanciamiento,dr.descuento,dr.tipoDescuento
                                FROM detallereparaciones AS dr
                                INNER JOIN recepcionEquipos AS re ON re.idEquipos = dr.idEquipos
                                WHERE idCotizacion=?",[$idCotizacion]);
        
        $equiposNuevosYreparados = \DB::select("SELECT dr.tipoCotizacion,dr.idRepCotizacion,dr.piezas,dr.piezasOcupadas,dr.idEquipos,dr.idCotizacion,
                                dr.descripcion,dr.modelo,dr.numeroCotizacion,dr.notas,dr.semanasEntrega,dr.montoEquipo,dr.partida,
                                dr.montoFinanciamiento,dr.descuento,dr.tipoDescuento
                                FROM detallereparaciones AS dr
                                WHERE idCotizacion=?",[$idCotizacion]);
        $idRepCotizacion=$equiposNuevosYreparados[0]->idRepCotizacion;
                                $cuantos = count($equiposNuevosYreparados);
        $datosEquiposNuevos = \DB::select("SELECT pv.nombreRefaccion,pv.numeroParte,dr.tipoCotizacion,dr.idEquipos, pv.codigo
                                FROM detallereparaciones AS dr
                                INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idEquipos
                                WHERE idCotizacion=? AND tipoCotizacion !='reparacion'",[$idCotizacion]);
                            
        $datosEquipoCotizado = \DB::select("SELECT re.idEquipos, re.serie,
                                CONCAT (re.serie,' - ',te.tipoEquipo,' - ',se.subtipoEquipo) AS equipo
                                FROM recepcionEquipos AS re
                                INNER JOIN tipoequipos AS te ON te.idTipoEquipo= re.idTipoEquipo
                                INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= re.idSubtipoEquipo
                                WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones)");

        return view('AsignacionPiezas.infoCotizacion')
        ->with('idFactura',$idFactura)
        ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
        ->with('equiposReparados',$equiposReparados)
        ->with('datosEquipoCotizado',$datosEquipoCotizado)
        ->with('datosEquiposNuevos',$datosEquiposNuevos)
        ->with('consultaCotizacion',$consultaCotizacion[0]);
        // return $idFactura;
        
    }

    public function servicioAsignado(Request $request){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
        $idParte=$request->idEquipos;
        $idFactura=$request->idFactura;
        $idRepCotizacion=$request->idRepCotizacion;
        $tipoCotizacion=$request->tipoCotizacion;
        $idCotizacion=$request->idCotizacion;

        $consultaSolicitada=\DB::select("SELECT piezas, piezasOcupadas, (piezas-piezasOcupadas) as pendientes FROM detallereparaciones where idRepCotizacion = ?",[$idRepCotizacion]);
        
        $consultaPartesVenta=\DB::select("SELECT pv.idPartesVenta, idRefaccion, pv.tipoProducto, pv.idMarcaRefa, pv.idTipoRefacciones,pv.codigo
                                FROM partesVenta AS pv
                                INNER JOIN refacciones AS r ON r.idPartesVEnta=pv.idPartesVenta
                                WHERE pv.idPartesVenta=?",[$idParte]);
        $cuantosResgistros=count($consultaPartesVenta);

        if($tipoCotizacion=='reparacion'){
            
            $tipo=\DB::select("SELECT DISTINCT pv.tipoProducto
                                FROM refacciones AS r
                                INNER JOIN partesVenta AS pv ON pv.idPartesVenta = r.idPartesVenta
                                WHERE pv.presentacion !='Servicio'");

            $consultaPiezas=\DB::select("SELECT pa.idPiezaAsig, r.idRefaccion, r.codigoRefaccion, r.nombreRefaccion, r.serie, pa.cantidadOcupada,  r.presentacion,
                            p.razonSocialProv, pa.fechaCompra, pa.numeroFacturaProv, f.numeroFactura
                            FROM piezasAsignadas AS pa
                            INNER JOIN refacciones AS r ON r.idRefaccion = pa.idRefaccion
                            INNER JOIN proveedores AS p ON p.idProveedor = pa.idProveedor
                            INNER JOIN facturas AS f ON f.idFactura = pa.idFactura
                            INNER JOIN detallereparaciones AS dr ON dr.idRepCotizacion=pa.idRepCotizacion
                            WHERE pa.idParte=? AND dr.idRepCotizacion=?",[$idParte, $idRepCotizacion]);
                            
            $cantidadTotal=\DB::select("SELECT SUM(pa.cantidadOcupada) AS total
                            FROM piezasAsignadas AS pa
                            INNER JOIN refacciones AS r ON r.idRefaccion = pa.idRefaccion
                            WHERE pa.idParte = ?", [$idParte]); 

            return view("AsignacionPiezas.servicioReparacion")
            ->with('tipo',$tipo)
            ->with('idParte',$idParte)
            ->with('consultaSolicitada',$consultaSolicitada[0])
            ->with('idFactura',$idFactura)
            ->with('idRepCotizacion',$idRepCotizacion)
            ->with('consultaPiezas',$consultaPiezas)
            ->with('idCotizacion',$idCotizacion)
            ->with('cuantosResgistros',$cuantosResgistros);
        }else{
            if($cuantosResgistros!=0){
                $marcaSel= marcaRefaccion::where('idMarcaRefa',"=",$consultaPartesVenta[0]->idMarcaRefa)->get();
                $marca=$marcaSel[0]->marcaRefaccion;

                $tipoRefaSel= tiporefacciones::where('idTipoRefacciones',"=",$consultaPartesVenta[0]->idTipoRefacciones)->get();
                $tipoRefa=$tipoRefaSel[0]->nombreTipoRefaccion;
                $idTipoRef=$tipoRefaSel[0]->idTipoRefacciones;
                
                $consultaCodigoParte=\DB::select("SELECT idRefaccion, codigoRefaccion, nombreRefaccion, ea.numeroFactura, r.activo
                                        FROM refacciones AS r
                                        INNER JOIN entradasAlmacen AS ea ON ea.idEntrada = r.idEntrada
                                        WHERE  r.cantidad>r.cantidadOcupada AND idTipoRefacciones = ? AND idPartesVenta =?",[$idTipoRef, $idParte]);

                $consultaPiezas=\DB::select("SELECT pa.idPiezaAsig, r.idRefaccion, r.codigoRefaccion, r.nombreRefaccion, r.serie, pa.cantidadOcupada,  r.presentacion,
                                        p.razonSocialProv, pa.fechaCompra, pa.numeroFacturaProv, f.numeroFactura
                                        FROM piezasAsignadas AS pa
                                        INNER JOIN refacciones AS r ON r.idRefaccion = pa.idRefaccion
                                        INNER JOIN proveedores AS p ON p.idProveedor = pa.idProveedor
                                        INNER JOIN facturas AS f ON f.idFactura = pa.idFactura
                                        WHERE pa.idParte = ?",[$idParte]);
                                        $cantidadTotal=\DB::select("SELECT SUM(pa.cantidadOcupada) AS total
                                        FROM piezasAsignadas AS pa
                                        INNER JOIN refacciones AS r ON r.idRefaccion = pa.idRefaccion
                                        WHERE pa.idParte = ?", [$idParte]);

                return view("AsignacionPiezas.servicioAsignado")
                ->with('cantidadTotal',$cantidadTotal[0])
                ->with('cuantosResgistros',$cuantosResgistros)
                ->with('consultaSolicitada',$consultaSolicitada[0])
                ->with('consultaPiezas',$consultaPiezas)
                ->with('idParte',$idParte)
                ->with('idFactura',$idFactura)
                ->with('idRepCotizacion',$idRepCotizacion)
                ->with('sname',$sname)
                ->with('marca',$marca)
                ->with('tipoRefa',$tipoRefa)
                ->with('consultaCodigoParte',$consultaCodigoParte)
                ->with('consultaPartesVenta',$consultaPartesVenta[0]);
            }else{
                return view("AsignacionPiezas.servicioAsignado")
                ->with('cuantosResgistros',$cuantosResgistros);
            }
        }


        // return $cuantosResgistros;
    }
    public function combiarInputs(Request $request){
        $idRefaccion=$request->idRefaccion;
        $idParte=$request->idParte;
        $idRepCotizacion=$request->idRepCotizacion;
        $idCotizacion=$request->idCotizacion;

        $consultaSolicitada=\DB::select("SELECT piezas, piezasOcupadas,tipoCotizacion, (piezas-piezasOcupadas) as pendientes FROM detallereparaciones where idRepCotizacion = ?",[$idRepCotizacion]);
        $consulta=\DB::select("SELECT cantidad, cantidadOcupada, (cantidad-cantidadOcupada) as existencia, numeroFactura,
                        DATE_FORMAT(fechaCompra,'%d %b %Y') as fechaCompra, r.idProveedor, razonSocialProv 
                        FROM refacciones AS r
                        INNER JOIN entradasAlmacen AS ea ON r.idEntrada=ea.idEntrada
                        INNER JOIN proveedores AS p ON r.idProveedor=p.idProveedor
                        WHERE idRefaccion=?",[$idRefaccion]);

        $cantidadTotal=\DB::select("SELECT SUM(piezasOcupadas) AS total
                        FROM detallereparaciones AS dr
                        WHERE idEquipos = ? AND idRepCotizacion=?", [$idParte, $idRepCotizacion]);

        $refaccionEnCoti=\DB::select("SELECT rc.idPartesVenta FROM refaccionesEnCotizacion AS rc
                                    INNER JOIN refacciones AS r ON r.idPartesVenta=rc.idPartesVenta
                                    WHERE r.idRefaccion=? AND rc.idCotizacion=?",[$idRefaccion,$idCotizacion]);
        if($refaccionEnCoti){
            $enCotizacion='Si';
        }else{
            $enCotizacion='No';
        }


        return view("AsignacionPiezas.infoFactura")
        ->with("enCotizacion",$enCotizacion)
        ->with("consultaSolicitada",$consultaSolicitada[0])
        ->with("consultaSolicitada",$consultaSolicitada[0])
        ->with("cantidadTotal",$cantidadTotal[0])
        ->with("consulta",$consulta[0]);
        // return $idRepCotizacion;

    }

    public function guardarAsignacion(Request $request){
        $idParte=$request->idParte;
        $cantidadOcupada=$request->cantidadOcupada;
        $idRefaccion=$request->idRefaccion;
        $idRepCotizacion=$request->idRepCotizacion;

        $piezas = new piezasAsignadas($request->input());
        $piezas->save();


        $actualizar=\DB::select("UPDATE refacciones SET cantidadOcupada = cantidadOcupada + ? WHERE idRefaccion = ?",[$cantidadOcupada,$idRefaccion]);
        $actualizar2=\DB::select("UPDATE detallereparaciones SET piezasOcupadas = piezasOcupadas + ? WHERE idRepCotizacion = ?",[$cantidadOcupada,$idRepCotizacion]);

        $consultaPiezas=\DB::select("SELECT pa.idPiezaAsig, r.idRefaccion, r.codigoRefaccion, r.nombreRefaccion, r.serie, pa.cantidadOcupada,  r.presentacion,
                            p.razonSocialProv, pa.fechaCompra, pa.numeroFacturaProv, f.numeroFactura
                            FROM piezasAsignadas AS pa
                            INNER JOIN refacciones AS r ON r.idRefaccion = pa.idRefaccion
                            INNER JOIN proveedores AS p ON p.idProveedor = pa.idProveedor
                            INNER JOIN facturas AS f ON f.idFactura = pa.idFactura
                            INNER JOIN detallereparaciones AS dr ON dr.idRepCotizacion=pa.idRepCotizacion
                            WHERE pa.idParte=? AND dr.idRepCotizacion=?",[$idParte, $idRepCotizacion]);
        
        $cantidadTotal=\DB::select("SELECT SUM(piezasOcupadas) AS total
                        FROM detallereparaciones AS dr
                        WHERE idEquipos = ? AND idRepCotizacion=?", [$idParte, $idRepCotizacion]);
        return view("AsignacionPiezas.reportePiezasAsignadas")
        ->with('idParte',$idParte)
        ->with('idRepCotizacion',$idRepCotizacion)
        ->with('cantidadTotal',$cantidadTotal[0])
        ->with('consultaPiezas',$consultaPiezas);
        // return $cantidadTotal;

    }

    public function comboCodigoPiezas(Request $request){
        $idParte=$request->idParte;
        $consultaPartesVenta=\DB::select("SELECT pv.idPartesVenta, idRefaccion, pv.tipoProducto, pv.idMarcaRefa, pv.idTipoRefacciones,pv.codigo
                            FROM partesVenta AS pv
                            INNER JOIN refacciones AS r ON r.idPartesVEnta=pv.idPartesVenta
                            WHERE pv.idPartesVenta=?",[$idParte]);
        
        $tipoRefaSel= tiporefacciones::where('idTipoRefacciones',"=",$consultaPartesVenta[0]->idTipoRefacciones)->get();
                $tipoRefa=$tipoRefaSel[0]->nombreTipoRefaccion;
                $idTipoRef=$tipoRefaSel[0]->idTipoRefacciones;

        $consultaCodigoParte=\DB::select("SELECT idRefaccion, codigoRefaccion, nombreRefaccion, ea.numeroFactura, r.activo
                            FROM refacciones AS r
                            INNER JOIN entradasAlmacen AS ea ON ea.idEntrada = r.idEntrada
                            WHERE  r.cantidad>r.cantidadOcupada AND idTipoRefacciones = ? AND idPartesVenta =?",[$idTipoRef, $idParte]);
        return view("AsignacionPiezas.comboCodigoPiezas")
        ->with('consultaCodigoParte',$consultaCodigoParte);
    }

    public function cambiarPendiente(Request $request){
        $idRepCotizacion=$request->idRepCotizacion;
        $consultaSolicitada=\DB::select("SELECT piezas, piezasOcupadas, (piezas-piezasOcupadas) as pendientes FROM detallereparaciones where idRepCotizacion = ?",[$idRepCotizacion]);
        
        return view('AsignacionPiezas.pendiente')
        ->with('consultaSolicitada',$consultaSolicitada[0]);
    }

    public function cambiarBoton(Request $request){
        $idRepCotizacion=$request->idRepCotizacion;
        $consultaSolicitada=\DB::select("SELECT piezas, piezasOcupadas,tipoCotizacion FROM detallereparaciones where idRepCotizacion = ?",[$idRepCotizacion]);
        
        return view('AsignacionPiezas.cambiarBoton')
        ->with('consultaSolicitada',$consultaSolicitada[0]);

    }

    public function borrarAsignacion(Request $request){
        $idParte=$request->idParte;
        $idPiezaAsig=$request->idPiezaAsig;
        $idRefaccion=$request->idRefaccion;
        $idRepCotizacion=$request->idRepCotizacion;
        $cantidadEliminar=$request->cantidadEliminar;

        $actualizar=\DB::select("UPDATE refacciones SET cantidadOcupada = cantidadOcupada - ? WHERE idRefaccion = ?",[$cantidadEliminar,$idRefaccion]);
        $actualizar2=\DB::select("UPDATE detallereparaciones SET piezasOcupadas = piezasOcupadas - ? WHERE idRepCotizacion = ?",[$cantidadEliminar,$idRepCotizacion]);

        $borro= \DB::delete("DELETE FROM piezasAsignadas WHERE idPiezaAsig=?",[$idPiezaAsig]);

        $consultaPiezas=\DB::select("SELECT pa.idPiezaAsig, r.idRefaccion, r.codigoRefaccion, r.nombreRefaccion, r.serie, pa.cantidadOcupada,  r.presentacion,
                            p.razonSocialProv, pa.fechaCompra, pa.numeroFacturaProv, f.numeroFactura
                            FROM piezasAsignadas AS pa
                            INNER JOIN refacciones AS r ON r.idRefaccion = pa.idRefaccion
                            INNER JOIN proveedores AS p ON p.idProveedor = pa.idProveedor
                            INNER JOIN facturas AS f ON f.idFactura = pa.idFactura
                            INNER JOIN detallereparaciones AS dr ON dr.idRepCotizacion=pa.idRepCotizacion
                            WHERE pa.idParte=? AND dr.idRepCotizacion=?",[$idParte, $idRepCotizacion]);
        
        $cantidadTotal=\DB::select("SELECT SUM(piezasOcupadas) AS total
                        FROM detallereparaciones AS dr
                        WHERE idEquipos = ? AND idRepCotizacion=?", [$idParte, $idRepCotizacion]);
        return view("AsignacionPiezas.reportePiezasAsignadas")
        ->with('idParte',$idParte)
        ->with('idRepCotizacion',$idRepCotizacion)
        ->with('cantidadTotal',$cantidadTotal[0])
        ->with('consultaPiezas',$consultaPiezas);

        // return $request;
    }

    public function detalleServicioPiezas(Request $request){
        $idParte=$request->idEquipos;
        $idRepCotizacion=$request->idRepCotizacion;

        $consultaPiezas=\DB::select("SELECT pa.idPiezaAsig, r.idRefaccion, r.codigoRefaccion, r.nombreRefaccion, r.serie, pa.cantidadOcupada,  r.presentacion,
                            p.razonSocialProv, pa.fechaCompra, pa.numeroFacturaProv, f.numeroFactura
                            FROM piezasAsignadas AS pa
                            INNER JOIN refacciones AS r ON r.idRefaccion = pa.idRefaccion
                            INNER JOIN proveedores AS p ON p.idProveedor = pa.idProveedor
                            INNER JOIN facturas AS f ON f.idFactura = pa.idFactura
                            INNER JOIN detallereparaciones AS dr ON dr.idRepCotizacion=pa.idRepCotizacion
                            WHERE pa.idParte=? AND dr.idRepCotizacion=?",[$idParte, $idRepCotizacion]);

        $cantidadTotal=\DB::select("SELECT SUM(pa.cantidadOcupada) AS total
                                FROM piezasAsignadas AS pa
                                INNER JOIN refacciones AS r ON r.idRefaccion = pa.idRefaccion
                                WHERE pa.idParte = ?", [$idParte]);
        return view("AsignacionPiezas.detalleServicio")
        ->with('consultaPiezas',$consultaPiezas);
    }

    public function comboMarcaPiezas(Request $request){
        $tipoProducto=$request->tipoProducto;
        $idCotizacion=$request->idCotizacion;

        $consultaMarcas=\DB::select("SELECT DISTINCT marcaRefaccion, m.idMarcaRefa FROM marcasRefaccion AS m
                INNER JOIN refacciones AS r ON r.idMarcaRefa = m.idMarcaRefa
                INNER JOIN partesVenta AS pv ON pv.idPartesVenta=r.idPartesVenta
                WHERE pv.presentacion !='Servicio' AND m.activo='Si' AND pv.tipoProducto LIKE '$tipoProducto%'");
        return view('AsignacionPiezas.comboMarcas')
        ->with('consultaMarcas',$consultaMarcas);
        // return $tipoProducto;
    }

    public function comboTipoRefPiezas(Request $request){
        $idMarca=$request->idMarca;
        $tipoProducto=$request->tipoProducto;
        $idCotizacion=$request->idCotizacion;

        $consultaTipoRef=\DB::select("SELECT DISTINCT tr.idTipoRefacciones, tr.nombreTipoRefaccion
        FROM tiporefacciones AS tr
        INNER JOIN refacciones AS r ON r.idTipoRefacciones = tr.idTipoRefacciones
        INNER JOIN partesVenta AS pv ON pv.idPartesVenta=r.idPartesVenta
        WHERE pv.tipoProducto LIKE '$tipoProducto%' AND tr.idMarcaRefa=? AND tr.activo='Si'",[$idMarca]);

        return view('AsignacionPiezas.comboTipoRef')
        ->with('consultaTipoRef',$consultaTipoRef);
    }

    public function comboRefaccionPiezas(Request $request){
        $idTipoRefacciones=$request->idTipoRefacciones;
        $tipoProducto=$request->tipoProducto;
        $idCotizacion=$request->idCotizacion;
    
        $consultaRefaccion=\DB::select("SELECT r.idRefaccion, r.nombreRefaccion, r.codigoRefaccion, ea.numeroFactura
                                FROM refacciones AS r
                                INNER JOIN partesVenta AS pv ON pv.idPartesVenta = r.idPartesVenta
                                INNER JOIN entradasAlmacen AS ea ON ea.idEntrada = r.idEntrada
                                WHERE pv.tipoProducto LIKE '$tipoProducto%' AND r.idTipoRefacciones=? 
                                AND r.cantidad != r.cantidadOcupada AND r.activo='Si'",[$idTipoRefacciones]);
        return view('AsignacionPiezas.comboRefaccion')
        ->with('consultaRefaccion',$consultaRefaccion);
    }
}
