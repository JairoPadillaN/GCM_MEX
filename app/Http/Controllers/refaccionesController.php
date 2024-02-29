<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\recepcionEquipos;
use App\proveedores;
use App\tiporefacciones;
use App\refacciones;
use App\partesreparacion;
use App\talleres;
use App\estatusReparaciones;
use App\marcaRefaccion;
use App\anexGrid;
use DB;

class refaccionesController extends Controller
{
  public function altaRefacciones(){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }
    else{
      $proveedor = proveedores::orderby('idProveedor','asc')->get();
      $tipoRef = tiporefacciones::orderby('idTipoRefacciones','asc')->get();

      return view ('altaRefacciones')
      ->with('proveedor',$proveedor)
      ->with('tipoRef',$tipoRef);
    }
  }

  public function guardarRefacciones(Request $request){
    
    $nombreRefaccion=$request->nombreRefaccion;
    $numeroParte=$request->numeroParte;
    $idProveedor=$request->idProveedor;
    $precioUltimo=$request->precioUltimo;
    $precioLista=$request->precioLista;
    $ubicacion=$request->ubicacion;
    $estatus=$request->estatus;
    $idTipoRefacciones=$request->idTipoRefacciones;
    

    $this->validate ($request,[
      'nombreRefaccion'=>['required'],
      'numeroParte'=>['required'],
      'idProveedor'=>['required'],
      'precioUltimo'=>['required'],
      'precioLista'=>['required'],
      'ubicacion'=>['required'],
      'estatus'=>['required'],
      'idTipoRefacciones'=>['required']
      ]);

    $refaccion = new refacciones;
    $refaccion->nombreRefaccion=$request->nombreRefaccion;
    $refaccion->numeroParte=$request->numeroParte;
    $refaccion->serie=$request->serie;
    $refaccion->idProveedor=$request->idProveedor;
    $refaccion->precioUltimo=$request->precioUltimo;
    $refaccion->precioLista=$request->precioLista;
    $refaccion->ubicacion=$request->ubicacion;
    $refaccion->estatus=$request->estatus;
    $refaccion->idTipoRefacciones=$request->idTipoRefacciones;
  	$refaccion->activo='Si';
    $refaccion->save();

    $proceso='Alta de refacciones';
    $mensaje="Registro guardado correctamente";
    return view ('mensajeRefacciones')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }
  
  public function reporteRefacciones(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }
    else{
      $consulta = \DB::select("SELECT r.idRefaccion,r.nombreRefaccion, r.numeroParte, r.activo, r.precioUltimo, r.precioLista, r.ubicacion, r.estatus, r.idProveedor, r.idTipoRefacciones  FROM refacciones AS r
      INNER JOIN proveedores AS p ON p.idProveedor= r.idProveedor
      INNER JOIN tiporefacciones AS tr ON tr.idTipoRefacciones= r.idTipoRefacciones");

      $consulta2 = \DB::select("SELECT i.idParte,i.idRefaccion,n.GCMidParte FROM refaccionesreparacionpartes AS i
      INNER JOIN partesreparacion AS n ON n.idParte = i.idParte");

      return view ('reporteRefacciones')
      ->with('consulta',$consulta)
      ->with('consulta2',$consulta2);
    } 
  }
    
  public function modificarRefacciones($idRefaccion){
    // $consultaEquipos = recepcionEquipos::Where ('idRefaccion','=',$idRefaccion)->get();
    $consultaEquipos = DB::table( 'recepcionEquipos as r' )
      ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
      ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
      ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
      ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
      ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
      ->select(
          'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo','c.razonSocial', 's.sucursal',
          'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo',
          DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
          DB::raw(' COUNT( p.idParte ) as canti '),
          DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
          DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
          DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
          DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
          DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
      )
      ->whereIn( 'r.idRefaccion', [$idRefaccion] )
      ->groupBy(
                  'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
                  'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                  'r.estatus', 'r.importancia', 'datos', 'gcmid',
                  'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
      )
      ->orderBy( 'r.idEquipos', 'DESC' )
      ->get();

    $consultaDisponible=\DB::select("SELECT r.idRefaccion, codigoRefaccion, nombreRefaccion, serie, pa.cantidadOcupada,
    pa.numeroFacturaProv, presentacion, p.razonSocialProv, pa.fechaCompra, f.numeroFactura, r.idEntrada
    FROM refacciones AS r
    INNER JOIN proveedores AS p ON p.idProveedor=r.idProveedor
    INNER JOIN piezasAsignadas AS pa ON pa.idRefaccion = r.idRefaccion
    INNER JOIN facturas AS f ON f.idFactura = pa.idFactura
    WHERE r.idRefaccion = ?",[$idRefaccion]);
    $cuantoDisponible=count($consultaDisponible);

    $equipos = count($consultaEquipos);
    $consultaRef = refacciones::Where ('idRefaccion','=',$idRefaccion)->get();

    $ordenCompra=$consultaRef[0]->idMarcaRefa;
// dd($ordenCompra);
    if($ordenCompra!=''){
      $marcaSel= marcaRefaccion::where('idMarcaRefa',"=",$consultaRef[0]->idMarcaRefa)->get();
      $nombreMarca =$marcaSel[0]->marcaRefaccion;
      $marca= marcaRefaccion::where('idMarcaRefa',"!=",$consultaRef[0]->idMarcaRefa)->get();
      
      $refSel= tiporefacciones::where('idTipoRefacciones',"=",$consultaRef[0]->idTipoRefacciones)->get();
      $nomref =$refSel[0]->nombreTipoRefaccion;
      $ref = tiporefacciones::where ('idTipoRefacciones','!=',$consultaRef[0]->idTipoRefacciones)->get();
    }else{
      $refSel= "Sin ref";
      $nombreMarca="Sin Marca";
      $nomref="Sin Marca";
      $ref = tiporefacciones::where ('idTipoRefacciones','!=',1)->get();
    }
    

    $provSel= proveedores::where('idProveedor',"=",$consultaRef[0]->idProveedor)->get();
    $nomprov =$provSel[0]->razonSocialProv;
    $prov = proveedores::where ('idProveedor','!=',$consultaRef[0]->idProveedor)->get();

    $consulta = refacciones::Where ('idRefaccion','=',$idRefaccion)->get();
    $consulta2 = \DB::select("SELECT rp.idParte,rp.idRefaccion,p.GCMidParte, p.nombreParte
    FROM refacciones AS r
    INNER JOIN refaccionesreparacionpartes AS rp
    INNER JOIN partesreparacion AS p
    ON rp.idParte=p.idParte
    WHERE $idRefaccion=rp.idRefaccion");
    if($consulta2){
      $mensaje='bien';
      $idParte =$consulta2[0]->idParte;
      $consultaParte = \DB::select("SELECT idTaller,idEstatus, quienllevo, quienRecibio, prioridadRep,
      GCMidParte, nombreParte, fechaSalida, queReparacion, fechaEntrega, fechaRecepcion, ordenCompra,
      DATE_FORMAT(fechaSalida,'%d %b %Y') AS fecha,
      DATE_FORMAT(fechaEntrega,'%d %b %Y') AS fechaEn,
      DATE_FORMAT(fechaRecepcion,'%d %b %Y') AS fechaRe,
      montoReparacion, tipoMoneda FROM partesreparacion WHERE idParte =?",[$idParte]);

      $consultaArchivoOC = \DB::select("SELECT p.archivoOrdenCompra FROM partesreparacion AS p WHERE idParte=?",[$idParte]);
      $consultaFotoParte = \DB::select("SELECT p.fotoParte FROM partesreparacion AS p WHERE idParte=?",[$idParte]);

      $tallerSel= talleres::where('idTaller',"=",$consultaParte[0]->idTaller)->get();
      $nombreTaller =$tallerSel[0]->nombreTaller;
      $tipoTaller =$tallerSel[0]->tipoTaller;
      $taller = talleres::where ('idTaller','!=',$consultaParte[0]->idTaller)->get();

      $estatusSel= estatusReparaciones::where('idEstatus',"=",$consultaParte[0]->idEstatus)->get();
      $nombreEstatus =$estatusSel[0]->nombreEstatus;
      $estatusReparado = estatusReparaciones::where ('idEstatus','!=',$consultaParte[0]->idEstatus)->get();

    }else{
      $consultaParte='0';
      $nombreEstatus='-';
      $estatusReparado='-';
      $nombreTaller='-';
      $tipoTaller='-';
      $consultaArchivoOC='-';
      $consultaFotoParte='-';
    }
    
    if($equipos=="0"){
      $consultaEqui = $consultaEquipos;
    }else{
      $consultaEqui = $consultaEquipos[0];
    }
    

    // return view('editarRefacciones') /// se cambia de vista 
    return view('detalleRefaccion')
    
    ->with('consultaEquipos',$consultaEqui)
    ->with('equipos',$equipos)
    ->with('cuantoDisponible',$cuantoDisponible)
    ->with('consultaDisponible',$consultaDisponible)
    ->with('ref',$ref)
    ->with('idTipoSel',$consultaRef[0]->idTipoRefacciones)
    ->with('nomref',$nomref)
    ->with('nombreMarca',$nombreMarca)
    ->with('refSel',$refSel[0])
    ->with('idProvSel',$consultaRef[0]->idProveedor)
    ->with('provSel',$provSel[0])
    ->with('nomprov',$nomprov)
    ->with('prov',$prov)
    ->with('nombreEstatus',$nombreEstatus)
    ->with('estatusReparado',$estatusReparado)
    ->with('nombreTaller',$nombreTaller)
    ->with('tipoTaller',$tipoTaller)
    ->with('consultaArchivoOC',$consultaArchivoOC)
    ->with('consultaFotoParte',$consultaFotoParte)
    ->with('consultaParte',$consultaParte[0])
    ->with('consulta',$consulta[0]);
    // return $consultaDis;
  }
  
  public function editarRefacciones(Request $request){
    $idRefaccion=$request->idRefaccion;
    $nombreRefaccion=$request->nombreRefaccion;
    $numeroParte=$request->numeroParte;
    $idProveedor=$request->idProveedor;
    $precioUltimo=$request->precioUltimo;
    $precioLista=$request->precioLista;
    $ubicacion=$request->ubicacion;
    $estatus=$request->estatus;
    $idTipoRefacciones=$request->idTipoRefacciones;

    $this->validate ($request,[
      'nombreRefaccion'=>['required'],
      'numeroParte'=>['required'],
      'idProveedor'=>['required'],
      'precioUltimo'=>['required'],
      'precioLista'=>['required'],
      'ubicacion'=>['required'],
      'estatus'=>['required'],
      'idTipoRefacciones'=>['required']
    ]);

    $refaccion = refacciones::find($idRefaccion);
    $refaccion-> idRefaccion= $request-> idRefaccion;//Esta valor lo toma del text oculto en el formulario es el id para saber a cual de modificara
    $refaccion->nombreRefaccion=$request->nombreRefaccion;
    $refaccion->numeroParte=$request->numeroParte;
    $refaccion->serie=$request->serie;
    $refaccion->idProveedor=$request->idProveedor;
    $refaccion->precioUltimo=$request->precioUltimo;
    $refaccion->precioLista=$request->precioLista;
    $refaccion->ubicacion=$request->ubicacion;
    $refaccion->estatus=$request->estatus;
    $refaccion->idTipoRefacciones=$request->idTipoRefacciones;
    $refaccion-> save();

    $proceso='Modificación de refacciones';
    $mensaje="Registro modificado correctamente";
    return view ('mensajeRefacciones')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }
  
  public function eliminarRefacciones ($idRefaccion){/*Rerecibe este parametro y lo guarda en esa variable*/
    $subtipoE = \DB::UPDATE("update refacciones set activo ='No' where idRefaccion=$idRefaccion");

    $proceso='Eliminación de refacciones';
    $mensaje="Registro eliminado correctamente";
    return view ('mensajeRefacciones')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }
    
  public function restaurarRefacciones ($idRefaccion){ //restarura el valos de NO a SI en el campo activo
    $subtipoE= \DB::UPDATE("update refacciones set activo ='Si' where idRefaccion=$idRefaccion");
    
    $proceso ="Restauración de refacciones";
    $mensaje="El registro ha sido activado correctamente";
    return view('mensajeRefacciones')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }
  

  public function mensaje(){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }
    else{

      return view ('mensaje');
    }
  }

  public function filtroRefacciones(){
    $anexGrid = new anexGrid();
        $order;
        $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

        $nombreRefaccion = '';
        $numeroFactura = '';
        $numeroParte = '';
        $ubicacion = '';
        $estatus = '';
        $tipoProducto = '';
        $tipoEntrada = '';

        foreach($anexGrid->filtros as $f)
            {
                if($f['columna'] == 'nombreRefaccion' && $f['valor'] != ''){
                    $nombreRefaccion = $f['valor'];
                }
                if($f['columna'] == 'numeroFactura' && $f['valor'] != ''){
                    $numeroFactura = $f['valor'];
                }
                if($f['columna'] == 'numeroParte' && $f['valor'] != ''){
                    $numeroParte = $f['valor'];
                }
                if($f['columna'] == 'ubicacion' && $f['valor'] != ''){
                    $ubicacion = $f['valor'];
                }
                if($f['columna'] == 'tipoProducto' && $f['valor'] != ''){
                    $tipoProducto = $f['valor'];
                }
                if($f['columna'] == 'tipoEntrada' && $f['valor'] != ''){
                    $tipoEntrada = $f['valor'];
                }
                
            }


        $registros = DB::table('refacciones')            
        ->select('refacciones.idRefaccion', 'nombreRefaccion', 'numeroParte','ubicacion','refacciones.estatus','refacciones.tipoProducto',
        'precioUltimo', 'GCMidParte','refacciones.activo', 
        'folioRecepcion', 'fotoPrincipal','fotoPlaca', 'cantidad','cantidadOcupada',
        DB::raw('cantidad-cantidadOcupada AS total'),
        DB::raw('IF(refacciones.idEntradaOrden IS NULL,entradasAlmacen.numeroFactura, eao.numeroFactura) AS numeroFactura'),
        DB::raw('IF(refacciones.idEntradaOrden IS NULL,"Normal", "Orden de compra") AS tipoEntrada'),
        DB::raw('IF (refacciones.idEntradaOrden IS NULL, DATE_FORMAT(entradasAlmacen.fechaCompra,"%d %b %Y"), DATE_FORMAT(eao.fechaCompra,"%d %b %Y")) AS fecha'),
        DB::raw('IF (refacciones.idEntradaOrden IS NULL,CONCAT(entradasAlmacen.moneda, " $ ", FORMAT(precioUltimo,2)),CONCAT(eao.moneda, " $ ", FORMAT(precioUltimo,2))) AS precio'))
        ->leftJoin('entradasAlmacen','entradasAlmacen.idEntrada','=','refacciones.idEntrada')
        ->leftJoin('entradasAlmacenOrdenCompra as eao','eao.idEntradaOrden','=','refacciones.idEntradaOrden')
        ->leftJoin('refaccionesreparacionpartes','refaccionesreparacionpartes.idRefaccion','=','refacciones.idRefaccion')
        ->leftJoin('partesreparacion','partesreparacion.idParte','=','refaccionesreparacionpartes.idParte')
        ->leftJoin('recepcionEquipos','recepcionEquipos.idRefaccion','=','refacciones.idRefaccion')
        // ->where('numeroFactura', 'like', '%'.$numeroFactura.'%')
        ->where('nombreRefaccion', 'like', '%'.$nombreRefaccion.'%')
        ->where('numeroParte', 'like', '%'.$numeroParte.'%')
        ->where('ubicacion', 'like', '%'.$ubicacion.'%')
        ->where('refacciones.tipoProducto', 'like', '%'.$tipoProducto.'%')
        
        ->orderBy($anexGrid->columna, $order)
        ->skip($anexGrid->pagina)
        ->take($anexGrid->limite)
        ->get();

        $total = DB::table('refacciones')            
        ->select('refacciones.idRefaccion', 'nombreRefaccion', 'numeroParte','ubicacion','estatus','refacciones.tipoProducto','fechaCompra',
        'numeroFactura', 'precioUltimo', 'GCMidParte', 'refacciones.activo','fotoPrincipal','fotoPlaca','cantidad','cantidadOcupada',
        DB::raw('cantidad-cantidadOcupada AS total'),
        DB::raw('DATE_FORMAT(entradasAlmacen.fechaCompra,"%d %b %Y") AS fecha'),
        DB::raw('CONCAT(entradasAlmacen.moneda, " $ ", FORMAT(precioUltimo,2)) AS precio'))
        ->leftJoin('entradasAlmacen','entradasAlmacen.idEntrada','=','refacciones.idEntrada')
        ->leftJoin('refaccionesreparacionpartes','refaccionesreparacionpartes.idRefaccion','=','refacciones.idRefaccion')
        ->leftJoin('partesreparacion','partesreparacion.idParte','=','refaccionesreparacionpartes.idParte')
        // ->where('numeroFactura', 'like', '%'.$numeroFactura.'%')
        ->where('nombreRefaccion', 'like', '%'.$nombreRefaccion.'%')
        ->where('numeroParte', 'like', '%'.$numeroParte.'%')
        ->where('ubicacion', 'like', '%'.$ubicacion.'%')
        ->where('refacciones.tipoProducto', 'like', '%'.$tipoProducto.'%')
        ->count();
                
    
        $data = array(
            'total' => $total,
            'data' => $registros
        );
    return response()->json($data, 200);
        

  }
}