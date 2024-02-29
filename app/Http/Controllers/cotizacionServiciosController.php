<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\contactosucursales;
use App\usuarios;
use App\detalleCotizacionServicios;
use App\clientes;
use App\sucursales;
use App\cotizacionServicios;
use App\serviciosTecnicos;
use App\asignacionesdetalles;
use App\facturas;
use Session;
use Carbon\Carbon;
use DB;
use PDF;
use App\anexGrid;


class cotizacionServiciosController extends Controller
{

  public function cotizacionServicios()
  {
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if ($sname == '' or $sidu == '' or $stipo == '') {
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    } else {

      if ($stipo == 'Administrador') {
        $cliente = \DB::select("SELECT * FROM clientes WHERE tipoCliente='activo' ORDER BY razonSocial ASC");
      } else {
        $cliente = DB::table('clientes')
          ->where('tipoCliente', '=', 'activo')
          ->whereIn('idc', function ($query) {
            $sidu = Session::get('sesionidu');
            //Obteniendo usuarios a cargo
            $aCargo = \DB::select("SELECT iduAsignado FROM usuariosacargos WHERE idu =$sidu");
            if (count($aCargo) > 0) {
              for ($i = 0; $i < count($aCargo); $i++) {
                $usuariosReporte[$i] = $aCargo[$i]->iduAsignado;
              }
              array_push($usuariosReporte, $sidu);
            } else {
              $usuariosReporte[0] = $sidu;
            }

            $query->select('idc')
              ->from('sucursales')
              ->whereIn('idu', $usuariosReporte);
          })
          ->orderBy('razonSocial', 'asc')
          ->get();
      }

      return view('altaCotizacionServicios')
        ->with('cliente', $cliente);
    }
  }

  function altaCotizacionServicios(Request $request)
  {
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');
    //Obteniendo usuarios a cargo
    $aCargo = \DB::select("SELECT iduAsignado FROM usuariosacargos WHERE idu =$sidu");
    if (count($aCargo) > 0) {
      for ($i = 0; $i < count($aCargo); $i++) {
        $usuariosReporte[$i] = $aCargo[$i]->iduAsignado;
      }
      array_push($usuariosReporte, $sidu);
    } else {
      $usuariosReporte[0] = $sidu;
    }

    $idc = $request->get('idc');
    if ($stipo == 'Administrador') {
      $sucursales = \DB::select("SELECT * FROM sucursales WHERE idc=? AND activo='Si' ORDER BY sucursal ASC", [$idc]);
    } else {
      $sucursales = DB::table('sucursales')
        ->where('activo', '=', 'si')
        ->where('idc', '=', $idc)
        ->whereIn('idu', $usuariosReporte)
        ->orderBy('sucursal', 'asc')
        ->get();
    }
    // $idSiguiente = \DB::table('cotizacionServicios')->max('idCotizacionServicios') + 1;

    $equiposCotizados = \DB::select("SELECT * FROM cotizacionServicios WHERE idc=? ", [$idc]);
    $cuantosCotizados = count($equiposCotizados);

    $diasDePago = \DB::select("SELECT diasDePago FROM clientes WHERE idc= $idc");

    $comboServiciosTecnicos = \DB::select("SELECT * FROM serviciosTecnicos where activo = 'Si' ORDER BY servicioTecnico ASC;");
    $comboUsuariosAdmon = usuarios::where('tipo', '=', 'Administrador', 'AND', 'activo', '=', 'Si')->orderBy('nombreUsuario', 'ASC')->get();
    $comboUsuariosVendedor = usuarios::where('tipo', '=', 'Vendedor', 'AND', 'activo', '=', 'Si')->orderBy('nombreUsuario', 'ASC')->get();

    $comboConCopia = \DB::select("SELECT co.nombreCont,s.sucursal from contactosucursales as co INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal where co.idc=$idc");

    return view('altaVersionCotizacionServicios')
      ->with('diasDePago', $diasDePago[0]->diasDePago)
      // ->with('idSiguiente',$idSiguiente)
      ->with('sucursales', $sucursales)
      ->with('comboUsuariosAdmon', $comboUsuariosAdmon)
      ->with('comboUsuariosVendedor', $comboUsuariosVendedor)
      ->with('comboServiciosTecnicos', $comboServiciosTecnicos)
      ->with('comboConCopia', $comboConCopia)
      ->with('equiposCotizados', $equiposCotizados)
      ->with('cuantosCotizados', $cuantosCotizados)
      ->with('idc', $idc);
  }

  public function creandoIdCotizacionServicios(Request $request)
  {

    $numeroCotizacionVista = $request->numeroCotizacionVista;
    $estatus = $request->estatus;
    $idc = $request->get('idc');

    $nombreCliete = \DB::select("SELECT razonSocial FROM clientes WHERE idc=?", [$idc]);
    $nombreSucursal = \DB::select("SELECT sucursal FROM sucursales WHERE idSucursal=?", [$request->idSucursal]);

    $cotizacion = new cotizacionServicios;
    $cotizacion->idu = $request->idu;
    $cotizacion->nombreUsuario = $request->nombreUsuario;
    $cotizacion->idc = $idc;
    $cotizacion->fechaCotizacion = $request->fechaCotizacion;
    $cotizacion->idSucursal = $request->idSucursal;
    $cotizacion->clienteSucursal = $nombreCliete[0]->razonSocial . ' / ' . $nombreSucursal[0]->sucursal;
    $cotizacion->numeroCotizacion = $request->numeroCotizacionVista;
    $cotizacion->dirigidoA = $request->dirigidoA;
    $cotizacion->conCopia = $request->conCopia;
    $cotizacion->estatus = $estatus;
    $cotizacion->archivoCotizacion = 'Sin archivo';
    $cotizacion->noServicios = 0;
    $cotizacion->montoTotal = 0.00;
    $cotizacion->tipoMoneda = $request->tipoMoneda;
    $cotizacion->cambio = $request->cambio;
    $cotizacion->cotizacionOriginal = $request->numeroCotizacion;
    $cotizacion->numConsecutivo = $request->numConsecutivo;
    $cotizacion->numCotizacion = 1;
    if ($estatus == "Cancelada") {
      $cotizacion->clonActual = 'No';
    } else {
      $cotizacion->clonActual = 'Si';
    }
    $cotizacion->activo = 'Si';
    $cotizacion->check1 = $request->check1;
    $cotizacion->textoCheck1 = $request->textoCheck1;
    $cotizacion->check2 = $request->check2;
    $cotizacion->textoCheck2 = $request->textoCheck2;
    $cotizacion->check3 = $request->check3;
    $cotizacion->textoCheck3 = $request->textoCheck3;
    $cotizacion->check4 = $request->check4;
    $cotizacion->textoCheck4 = $request->textoCheck4;
    $cotizacion->check5 = $request->check5;
    $cotizacion->textoCheck5 = $request->textoCheck5;
    $cotizacion->check6 = $request->check6;
    $cotizacion->textoCheck6 = $request->textoCheck6;
    $cotizacion->check7 = $request->check7;
    $cotizacion->textoCheck7 = $request->textoCheck7;
    $cotizacion->check8 = $request->check8;
    $cotizacion->textoCheck8 = $request->textoCheck8;
    $cotizacion->save();

    $cuantosidCotizacion = \DB::select("SELECT idCotizacionServicios FROM cotizacionServicios WHERE numeroCotizacion=?", [$numeroCotizacionVista]);
    $idCotizacionBorrar = \DB::select("SELECT MAX(idCotizacionServicios) as idCotizacionServicios FROM cotizacionServicios WHERE numeroCotizacion=?", [$numeroCotizacionVista]);

    $idCotizacionCreada = $idCotizacionBorrar[0]->idCotizacionServicios;

    if (count($cuantosidCotizacion) > 1) {
      $borrarCotizacion = \DB::delete("DELETE FROM cotizacionServicios WHERE idCotizacionServicios=?", [$idCotizacionBorrar[0]->idCotizacionServicios]);
      $idCotizacionCreada = 0;
    }

    $comboServiciosTecnicos = \DB::select("SELECT * FROM serviciosTecnicos where activo = 'Si' ORDER BY servicioTecnico ASC;");
    $diasDePago = \DB::select("SELECT diasDePago FROM clientes WHERE idc= $idc");


    return view('idCotizacionServicios')
      ->with('comboServiciosTecnicos', $comboServiciosTecnicos)
      ->with('diasDePago', $diasDePago[0]->diasDePago)
      ->with('idCotizacionServicios', $idCotizacionCreada);
  }

  function generarNumeroCotizacionServicios(Request $request)
  {
    $idSucursal = $request->get('idSucursal');

    $gcmid = \DB::select("SELECT gcmid FROM sucursales WHERE idSucursal = $idSucursal");
    $gcmidNuevo = $gcmid[0]->gcmid;

    // $fechaCotizacion = $request->get('fechaCotizacion');
    // $fechaCotizacion=Carbon::parse($fechaCotizacion);
    // $fechaNueva=$fechaCotizacion->format('dmY');

    if ($idSucursal == 557 || $idSucursal == 556 || $idSucursal == 555) {
      $consecutivoCotizacionBD = DB::table('cotizacionServicios')->select(DB::raw('MAX(numConsecutivo) as consecutivoDB'))
        ->where('numeroCotizacion', 'like', 'PMI-%')->get();
    } else {
      $consecutivoCotizacionBD = DB::table('cotizacionServicios')->select(DB::raw('MAX(numConsecutivo) as consecutivoDB'))
        ->where('idSucursal', '=', $idSucursal)->get();
    }
    $consecutivo = $consecutivoCotizacionBD[0]->consecutivoDB;

    if ($consecutivo == 0) {
      $numConsecutivo = 1;
    } else {
      $numConsecutivo = $consecutivo + 1;
    }
    $consecutivoCotizacion = str_pad($numConsecutivo, 6, "0", STR_PAD_LEFT);

    $cadenaNumeroCotizacionOriginal = $gcmidNuevo . '-S-' . $consecutivoCotizacion;
    $cadenaNumeroCotizacion = $gcmidNuevo . '-S-' . $consecutivoCotizacion . '-1';

    $activarEditar = 'no';
    return view('numeroCotizacion')
      ->with('activarEditar', $activarEditar)
      ->with('cadenaNumeroCotizacion', $cadenaNumeroCotizacion)
      ->with('cadenaNumeroCotizacionOriginal', $cadenaNumeroCotizacionOriginal)
      ->with('numConsecutivo', $numConsecutivo);
  }

  public function detalleServicio(Request $request)
  {
    $idServicioTecnico = $request->get('idServicioTecnico');

    $detalleServicio = \DB::select("SELECT detalleServicio FROM serviciosTecnicos WHERE idServicioTecnico=? ", [$idServicioTecnico]);

    return view('detalleServicio')
      ->with('detalleServicio', $detalleServicio[0]);
  }

  public function montoServicio(Request $request)
  {
    $idServicioTecnico = $request->get('idServicioTecnico');
    $tipoMoneda = $request->get('tipoMoneda');
    $cambioDolar = $request->get('cambio');
    if ($tipoMoneda == 'MXN') {
      $validandoMonto = \DB::select("SELECT montoPesos,montoDolares FROM serviciosTecnicos WHERE idServicioTecnico=? ", [$idServicioTecnico]);
      $montoPesos = $validandoMonto[0]->montoPesos;
      if ($montoPesos == "" || $montoPesos == 0) {
        $montoServicio = $validandoMonto[0]->montoDolares * $cambioDolar;
      } else {
        $montoServicio = \DB::select("SELECT montoPesos FROM serviciosTecnicos WHERE idServicioTecnico=? ", [$idServicioTecnico]);
        $montoServicio = $montoServicio[0]->montoPesos;
      }
    } else {
      $montoServicio = \DB::select("SELECT montoDolares FROM serviciosTecnicos WHERE idServicioTecnico=? ", [$idServicioTecnico]);
      $montoServicio = $montoServicio[0]->montoDolares;
    }

    return view('montoServicio')
      ->with('montoServicio', $montoServicio);
  }

  public function unidadMedida(Request $request)
  {
    $idServicioTecnico = $request->get('idServicioTecnico');

    $unidadMedida = \DB::select("SELECT unidadMedida FROM serviciosTecnicos WHERE idServicioTecnico=? ", [$idServicioTecnico]);

    return view('unidadMedidaServicio')
      ->with('unidadMedida', $unidadMedida[0]);
  }

  //esta mal escrito el nombre del metodo pero asi ya esta en todos lados
  public function asignarSercicioCotizacion(Request $request)
  {
    $idCotizacionServicios = $request->get('idCotizacionServicios');
    $idSucursal = $request->get('idSucursal');
    $numeroCotizacion = $request->get('numeroCotizacionVista');
    $tipoMoneda = $request->get('tipoMoneda');
    $descuento = $request->descuento;

    $detalle = new detalleCotizacionServicios;
    $detalle->idCotizacionServicios = $idCotizacionServicios;
    $detalle->idServicioTecnico = $request->idServicioTecnico;
    $detalle->detalle = $request->detalle;
    $detalle->numeroCotizacion = $request->numeroCotizacionVista;
    $detalle->semanasEntrega = $request->semanasEntrega;
    $detalle->tipoMoneda = $request->tipoMoneda;
    $detalle->montoEquipo = $request->montoEquipoInicial;
    $detalle->cantidad = $request->cantidad;
    $detalle->observacionCantidad = $request->observacionCantidad;
    $detalle->notas = $request->notas;
    $detalle->montoServicioOriginal = $request->montoServicio;
    if ($descuento == '') {
      $detalle->montoFinanciamiento = $request->montoEquipo;
    } else {
      $detalle->montoFinanciamiento = $request->montoFinanciamiento;
    }
    $detalle->aplicaDescuento = $request->aplicaDescuento;
    $detalle->tipoDescuento = $request->tipoDescuento;
    $detalle->diasPago = $request->diasPago;
    $detalle->descuento = $request->descuento;
    $detalle->clonEquiposActivo = 'Si';
    $detalle->agregadoReciente = 'No';
    $detalle->save();

    $serviciosEncotizacion = \DB::select("SELECT dcs.idDetalleCotizacionServ,dcs.idCotizacionServicios,
    st.servicioTecnico,dcs.detalle,dcs.observacionCantidad,dcs.numeroCotizacion,dcs.notas,dcs.semanasEntrega,dcs.tipoMoneda,dcs.montoEquipo,dcs.cantidad,
    dcs.montoFinanciamiento,dcs.descuento,dcs.tipoDescuento,dcs.clonEquiposActivo,dcs.agregadoReciente
    FROM detalleCotizacionServicios AS dcs
    LEFT JOIN serviciosTecnicos AS st ON st.idServicioTecnico=dcs.idServicioTecnico
    WHERE idCotizacionServicios = ?", [$idCotizacionServicios]);
    $cuantasServicios = count($serviciosEncotizacion);

    $suma = \DB::select("SELECT FORMAT(SUM(dr.montoFinanciamiento),2) AS sumaTotal
    FROM detalleCotizacionServicios AS dr
    WHERE idCotizacionServicios = ?", [$idCotizacionServicios]);

    $montoTotal = \DB::select("SELECT idCotizacionServicios, ROUND(SUM(montoFinanciamiento),2) AS montoTotal FROM detalleCotizacionServicios WHERE idCotizacionServicios = ? GROUP BY idCotizacionServicios", [$idCotizacionServicios]);
    $noServicios = \DB::select("SELECT idCotizacionServicios, COUNT(idCotizacionServicios) AS noServicios FROM detalleCotizacionServicios WHERE idCotizacionServicios = ? GROUP BY idCotizacionServicios", [$idCotizacionServicios]);

    $repetidos = \DB::select("SELECT idServicioTecnico FROM detalleCotizacionServicios WHERE idCotizacionServicios = ? GROUP BY idServicioTecnico HAVING COUNT(*)>1;", [$idCotizacionServicios]);
    $cuantosRepetidos = count($repetidos);
    if ($cuantosRepetidos > 0) {
      $siRepetidos = \DB::UPDATE("UPDATE cotizacionServicios SET repetidos = 'si' WHERE idCotizacionServicios= ?", [$idCotizacionServicios]);
      //   $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizacionServicios SET montoTotal = '0', noServicios=? WHERE idCotizacionServicios= ?",[$noServicios[0]->noServicios,$idCotizacionServicios]);
      $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizacionServicios SET montoTotal = ?, noServicios=? WHERE idCotizacionServicios= ?", [$montoTotal[0]->montoTotal, $noServicios[0]->noServicios, $idCotizacionServicios]);
    } else {
      $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizacionServicios SET montoTotal = ?, noServicios=? WHERE idCotizacionServicios= ?", [$montoTotal[0]->montoTotal, $noServicios[0]->noServicios, $idCotizacionServicios]);
    }

    $estatusCotizacion = \DB::select("SELECT estatus FROM cotizacionServicios WHERE idCotizacionServicios = ?", [$idCotizacionServicios]);

    return view('reporteCotizacionServiciosAbajo')
      ->with('serviciosEncotizacion', $serviciosEncotizacion)
      ->with('numeroCotizacion', $numeroCotizacion)
      ->with('idSucursal', $idSucursal)
      ->with('suma', $suma[0]->sumaTotal)
      ->with('tipoMoneda', $tipoMoneda)
      ->with('cuantasServicios', $cuantasServicios)
      ->with('estatusCotizacion', $estatusCotizacion[0]->estatus);
  }

  public function guardarCotizacionServicios(Request $request)
  {
    $numeroCotizacionVista = $request->numeroCotizacionVista;
    $idCotizacionServicios = $request->idCotizacionServicios;
    $estatus = $request->estatus;

    $idc = $request->idc;
    $empresa = \DB::select("SELECT empresaPertenece FROM clientes WHERE idc = ?", [$idc]);

    $file = $request->file('archivoCotizacion');
    if ($file != "") {
      $ldate = date('Ymd_His_');
      $img = $file->getClientOriginalName();
      $img2 = $ldate . $img;
      \Storage::disk('local')->put($img2, \File::get($file));
    } else {
      $img2 = "Sin archivo";
    }

    $cotizacion = cotizacionServicios::find($idCotizacionServicios);
    $cotizacion->idCotizacionServicios = $idCotizacionServicios;
    $cotizacion->idu = $request->idu;
    $cotizacion->nombreUsuario = $request->nombreUsuario;
    $cotizacion->idc = $request->idc;
    $cotizacion->fechaCotizacion = $request->fechaCotizacion;
    $cotizacion->idSucursal = $request->idSucursal;
    $cotizacion->numeroCotizacion = $request->numeroCotizacionVista;
    $cotizacion->dirigidoA = $request->dirigidoA;
    $cotizacion->conCopia = $request->conCopia;
    $cotizacion->estatus = $estatus;
    $cotizacion->archivoCotizacion = $img2;
    $cotizacion->montoTotal = ROUND($request->montoTotal, 2);
    $cotizacion->tipoMoneda = $request->tipoMoneda;
    $cotizacion->cambio = $request->cambio;
    $cotizacion->cotizacionOriginal = $request->numeroCotizacion;
    $cotizacion->numConsecutivo = $request->numConsecutivo;
    $cotizacion->numCotizacion = 1;
    if ($estatus == "Cancelada") {
      $cotizacion->clonActual = 'No';
    } else {
      $cotizacion->clonActual = 'Si';
    }
    $cotizacion->activo = 'Si';
    $cotizacion->check1 = $request->check1;
    $cotizacion->textoCheck1 = $request->textoCheck1;
    $cotizacion->check2 = $request->check2;
    $cotizacion->textoCheck2 = $request->textoCheck2;
    $cotizacion->check3 = $request->check3;
    $cotizacion->textoCheck3 = $request->textoCheck3;
    $cotizacion->check4 = $request->check4;
    $cotizacion->textoCheck4 = $request->textoCheck4;
    $cotizacion->check5 = $request->check5;
    $cotizacion->textoCheck5 = $request->textoCheck5;
    $cotizacion->check6 = $request->check6;
    $cotizacion->textoCheck6 = $request->textoCheck6;
    $cotizacion->check7 = $request->check7;
    $cotizacion->textoCheck7 = $request->textoCheck7;
    $cotizacion->check8 = $request->check8;
    $cotizacion->textoCheck8 = $request->textoCheck8;
    $cotizacion->save();

    // $idSiguiente = \DB::table('cotizacionServicios')->max('idCotizacionServicios');//estas dos lineas corrigen el idCotizacionServicios en detllereparaciones para relacionarlas correctamente
    // $corrigiendoidCotizacionenTablaDetalle = \DB::UPDATE("UPDATE detalleCotizacionServicios SET idCotizacionServicios = ? WHERE numeroCotizacion= ?",[$idSiguiente,$numeroCotizacionVista]);

    $montoTotal = \DB::select("SELECT idCotizacionServicios, ROUND(SUM(montoFinanciamiento),2) AS montoTotal FROM detalleCotizacionServicios WHERE idCotizacionServicios = ? GROUP BY idCotizacionServicios", [$idCotizacionServicios]);
    $noServicios = \DB::select("SELECT idCotizacionServicios, COUNT(idCotizacionServicios) AS noServicios FROM detalleCotizacionServicios WHERE idCotizacionServicios = ? GROUP BY idCotizacionServicios", [$idCotizacionServicios]);

    // $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizacionServicios SET montoTotal = ?, noServicios=? WHERE idCotizacionServicios= ?",[$montoTotal[0]->montoTotal,$noServicios[0]->noServicios,$idSiguiente]);

    $repetidos = \DB::select("SELECT idServicioTecnico FROM detalleCotizacionServicios WHERE idCotizacionServicios = ? GROUP BY idServicioTecnico HAVING COUNT(*)>1;", [$idCotizacionServicios]);
    $cuantosRepetidos = count($repetidos);
    if ($cuantosRepetidos > 0) {
      $siRepetidos = \DB::UPDATE("UPDATE cotizacionServicios SET repetidos = 'si' WHERE idCotizacionServicios= ?", [$idCotizacionServicios]);
      //   $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizacionServicios SET montoTotal = '0', noServicios=? WHERE idCotizacionServicios= ?",[$noServicios[0]->noServicios,$idCotizacionServicios]);
      $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizacionServicios SET montoTotal = ?, noServicios=? WHERE idCotizacionServicios= ?", [$montoTotal[0]->montoTotal, $noServicios[0]->noServicios, $idCotizacionServicios]);
    } else {
      $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizacionServicios SET montoTotal = ?, noServicios=? WHERE idCotizacionServicios= ?", [$montoTotal[0]->montoTotal, $noServicios[0]->noServicios, $idCotizacionServicios]);
    }

    // Creando servicio en tabla facturas
    if ($estatus == "Aceptada con orden de compra") {
      $idServiciosig = facturas::withTrashed()->orderby('idServicios', 'desc')->take(1)->get();
      $idServ = $idServiciosig[0]->idServicios + 1;
      $idc = $request->idc;
      $diasDePago = \DB::select("SELECT diasDePago FROM clientes WHERE idc= $idc");

      $file = $request->file('archivo');
      if ($file != "") {
        $archivo =  time() . "_" . $file->getClientOriginalName();
        \Storage::disk('local')->put($archivo,  \File::get($file));
      } else {
        $archivo = "Sin archivo";
      }

      $fac = new facturas;
      $fac->idServicios = $idServ;
      $fac->tipoServicio = "Soporte Técnico";
      $fac->nombreEmpresa = $empresa[0]->empresaPertenece;
      // $fac-> fechaVencimiento = "0000-00-00";
      // $fac-> idb = $request-> idb;
      // if($cuantosRepetidos > 0){
      //   $fac-> montoFactura = "0";
      // }else{
      $fac->montoFactura = $montoTotal[0]->montoTotal;
      // }
      $fac->periodoPago = $diasDePago[0]->diasDePago;
      $fac->numeroFactura = "-";
      // $fac-> fechaFactura = "0000-00-00";
      // $fac-> fechaPago = "0000-00-00";
      $fac->tipoMoneda = $request->tipoMoneda;
      $fac->estatusEntrega = "Pendiente";
      $fac->estatusPago = "Pendiente";
      $fac->metodoPago = "PUE";
      $fac->estatusPortal = "Pendiente en subir";
      $fac->cashFlow = "No";
      $fac->idc = $idc;
      $fac->idSucursal = $request->idSucursal;
      $fac->idCotizacion = $idCotizacionServicios;
      $fac->ordenCompra = $request->ordenCompra;
      $fac->archivo = $archivo;
      $fac->archivoFactura = "Sin archivo";
      $fac->archivoPago = "Sin archivo";
      $fac->xmlFactura = "Sin archivo";
      $fac->archivoRemision = "Sin archivo";
      $fac->archivoAdenda = "Sin archivo";
      $fac->save();

      $idFactura = \DB::select("SELECT idFactura FROM facturas WHERE tipoServicio='Soporte Técnico' AND idCotizacion= $idCotizacionServicios");
      $asigDetalles = new asignacionesdetalles;
      $asigDetalles->idFactura = $idFactura[0]->idFactura;
      $asigDetalles->fecha = $request->fechaCotizacion;
      $asigDetalles->idu = $request->idu;
      $asigDetalles->save();
    }

    $proceso = 'Alta de cotización de servicios';
    $mensaje = "Cotización creada correctamente";

    return view('mensajeCotizacionesServicios')
      ->with('proceso', $proceso)
      ->with('mensaje', $mensaje);
  }

  public function reporteVersionesServiciosCotizacion(Request $request)
  {
    $idc = $request->idc;
    $fechaInicio = $request->fechaInicio;
    $fechaFin = $request->fechaFin;

    if ($fechaInicio == '') {
      $fechaInicio = "0000-00-00";
    }
    if ($fechaFin == '') {
      $fechaFin = "9999-99-99";
    }

    return view('reporteVersionesServiciosCotizacion')
      ->with('fechaInicio', $fechaInicio)
      ->with('fechaFin', $fechaFin)
      ->with('idc', $idc);
    // if ($idc == "todos") {
    //   $consulta = \DB::select("SELECT co.idCotizacion, co.numeroCotizacion, co.fechaCotizacion,co.clonActual,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
    //   co.noServicios,CONCAT('$',' ',co.tipoMoneda,' ',co.montoTotal) AS montoMoneda,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus
    //   FROM cotizaciones AS co
    //   INNER JOIN usuarios AS u ON u.idu= co.idu
    //   INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
    //   INNER JOIN clientes AS c ON c.idc= co.idc
    //   WHERE fechaCotizacion>=? AND fechaCotizacion<=?
    //   ORDER BY numeroCotizacion",[$fechaInicio,$fechaFin]);
    // }else{
    //   $consulta = \DB::select("SELECT co.idCotizacion, co.numeroCotizacion, co.fechaCotizacion,co.clonActual,
    //   CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
    //   co.noServicios,
    //   CONCAT('$',' ',co.tipoMoneda,' ',co.montoTotal) AS montoMoneda,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus
    //   FROM cotizaciones AS co
    //   INNER JOIN usuarios AS u ON u.idu= co.idu
    //   INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
    //   INNER JOIN clientes AS c ON c.idc= co.idc
    //   WHERE co.idc=? AND fechaCotizacion>=? AND fechaCotizacion<=?
    //   ORDER BY numeroCotizacion",[$idc,$fechaInicio,$fechaFin]);
    // }

    // $cuantos = count($consulta);
    // return view ('reporteVersiones')
    // ->with('cuantos',$cuantos)
    // ->with('consulta',$consulta);
  }

  public function anexgridVersionesServiciosCotizacionConFiltros(Request $request)
  {

    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $spat = Session::get('sesionpaterno');
    $smat = Session::get('sesionmaterno');
    $stipo = Session::get('sesiontipo');

    if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    } else {

      // Obteniendo usuarios a cargo de un admin
      $aCargo = \DB::select("SELECT iduAsignado FROM usuariosacargos WHERE idu =$sidu");
      if (count($aCargo) > 0) {
        for ($i = 0; $i < count($aCargo); $i++) {
          $usuariosReporte[$i] = $aCargo[$i]->iduAsignado;
        }
        array_push($usuariosReporte, $sidu);
      } else {
        $usuariosReporte[0] = $sidu;
      }

      $idc = $request->idc;
      $fechaInicio = $request->fechaInicio;
      $fechaFin = $request->fechaFin;

      // if ($fechaInicio == ''){$fechaInicio="0000-00-00";}
      // if ($fechaFin == ''){$fechaFin="9999-99-99";}

      $anexGrid = new anexGrid();
      $clienteSucursal = '';
      $numeroCotizacion = '';
      $nombreUsuario = '';
      $estatus = '';

      foreach ($anexGrid->filtros as $f) {
        if ($f['columna'] == 'numeroCotizacion' && $f['valor'] != '') {
          $numeroCotizacion = $f['valor'];
        }

        if ($f['columna'] == 'nombreUsuario' && $f['valor'] != '') {
          $nombreUsuario = $f['valor'];
        }

        if ($f['columna'] == 'estatus' && $f['valor'] != '') {
          $estatus = $f['valor'];
        }

        if ($f['columna'] == 'clienteSucursal' && $f['valor'] != '') {
          $clienteSucursal = $f['valor'];
        }
      }

      $order;
      $anexGrid->columna_orden != '' ? $order = $anexGrid->columna_orden : $order = 'ASC';

      if ($stipo == 'Administrador') {

        if ($idc == "todos") {
          $registros = DB::table('cotizacionServicios')
            ->select(
              'idCotizacionServicios',
              'numeroCotizacion',
              'cotizacionServicios.created_at',
              'fechaCotizacion',
              'clonActual',
              'noServicios',
              'estatus',
              'cotizacionServicios.nombreUsuario',
              'repetidos',
              'numCotizacion',
              'clienteSucursal',
              DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
              DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
            )
            ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
            ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
            ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
            ->where('estatus', 'like', '%' . $estatus . '%')
            ->where('estatus', '!=', 'Cancelada')
            ->where('fechaCotizacion', '>=', $fechaInicio)
            ->where('fechaCotizacion', '<=', $fechaFin)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
            ->get();

          $total = DB::table('cotizacionServicios')
            ->select(
              'idCotizacionServicios',
              'numeroCotizacion',
              'cotizacionServicios.created_at',
              'fechaCotizacion',
              'clonActual',
              'noServicios',
              'estatus',
              'cotizacionServicios.nombreUsuario',
              'repetidos',
              'numCotizacion',
              'clienteSucursal',
              DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
              DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
            )
            ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
            ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
            ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
            ->where('estatus', 'like', '%' . $estatus . '%')
            ->where('estatus', '!=', 'Cancelada')
            ->where('fechaCotizacion', '>=', $fechaInicio)
            ->where('fechaCotizacion', '<=', $fechaFin)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
            ->count();
        } else {

          $registros = DB::table('cotizacionServicios')
            ->select(
              'idCotizacionServicios',
              'numeroCotizacion',
              'cotizacionServicios.created_at',
              'fechaCotizacion',
              'clonActual',
              'noServicios',
              'estatus',
              'cotizacionServicios.nombreUsuario',
              'repetidos',
              'numCotizacion',
              'clienteSucursal',
              DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
              DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
            )
            ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
            ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
            ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
            ->where('estatus', 'like', '%' . $estatus . '%')
            ->where('estatus', '!=', 'Cancelada')
            ->where('fechaCotizacion', '>=', $fechaInicio)
            ->where('fechaCotizacion', '<=', $fechaFin)
            ->where('idc', '=', $idc)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
            ->get();

          $total = DB::table('cotizacionServicios')
            ->select(
              'idCotizacionServicios',
              'numeroCotizacion',
              'cotizacionServicios.created_at',
              'fechaCotizacion',
              'clonActual',
              'noServicios',
              'estatus',
              'cotizacionServicios.nombreUsuario',
              'repetidos',
              'numCotizacion',
              'clienteSucursal',
              DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
              DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
            )
            ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
            ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
            ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
            ->where('estatus', 'like', '%' . $estatus . '%')
            ->where('estatus', '!=', 'Cancelada')
            ->where('fechaCotizacion', '>=', $fechaInicio)
            ->where('fechaCotizacion', '<=', $fechaFin)
            ->where('idc', '=', $idc)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
            ->count();
        }
      } else {
        if ($idc == "todos") {
          $registros = DB::table('cotizacionServicios')
            ->select(
              'idCotizacionServicios',
              'numeroCotizacion',
              'cotizacionServicios.created_at',
              'fechaCotizacion',
              'clonActual',
              'noServicios',
              'estatus',
              'cotizacionServicios.nombreUsuario',
              'repetidos',
              'numCotizacion',
              'clienteSucursal',
              DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
              DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
            )
            ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
            ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
            ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
            ->where('estatus', 'like', '%' . $estatus . '%')
            ->where('estatus', '!=', 'Cancelada')
            ->where('fechaCotizacion', '>=', $fechaInicio)
            ->where('fechaCotizacion', '<=', $fechaFin)
            ->whereIn('cotizacionServicios.idu', $usuariosReporte)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
            ->get();

          $total = DB::table('cotizacionServicios')
            ->select(
              'idCotizacionServicios',
              'numeroCotizacion',
              'cotizacionServicios.created_at',
              'fechaCotizacion',
              'clonActual',
              'noServicios',
              'estatus',
              'cotizacionServicios.nombreUsuario',
              'repetidos',
              'numCotizacion',
              'clienteSucursal',
              DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
              DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
            )
            ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
            ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
            ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
            ->where('estatus', 'like', '%' . $estatus . '%')
            ->where('estatus', '!=', 'Cancelada')
            ->where('fechaCotizacion', '>=', $fechaInicio)
            ->where('fechaCotizacion', '<=', $fechaFin)
            ->whereIn('cotizacionServicios.idu', $usuariosReporte)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
            ->count();
        } else {

          $registros = DB::table('cotizacionServicios')
            ->select(
              'idCotizacionServicios',
              'numeroCotizacion',
              'cotizacionServicios.created_at',
              'fechaCotizacion',
              'clonActual',
              'noServicios',
              'estatus',
              'cotizacionServicios.nombreUsuario',
              'repetidos',
              'numCotizacion',
              'clienteSucursal',
              DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
              DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
            )
            ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
            ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
            ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
            ->where('estatus', 'like', '%' . $estatus . '%')
            ->where('estatus', '!=', 'Cancelada')
            ->where('fechaCotizacion', '>=', $fechaInicio)
            ->where('fechaCotizacion', '<=', $fechaFin)
            ->where('idc', '=', $idc)
            ->whereIn('cotizacionServicios.idu', $usuariosReporte)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
            ->get();

          $total = DB::table('cotizacionServicios')
            ->select(
              'idCotizacionServicios',
              'numeroCotizacion',
              'cotizacionServicios.created_at',
              'fechaCotizacion',
              'clonActual',
              'noServicios',
              'estatus',
              'cotizacionServicios.nombreUsuario',
              'repetidos',
              'numCotizacion',
              'clienteSucursal',
              DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
              DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
            )
            ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
            ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
            ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
            ->where('estatus', 'like', '%' . $estatus . '%')
            ->where('estatus', '!=', 'Cancelada')
            ->where('fechaCotizacion', '>=', $fechaInicio)
            ->where('fechaCotizacion', '<=', $fechaFin)
            ->where('idc', '=', $idc)
            ->whereIn('cotizacionServicios.idu', $usuariosReporte)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
            ->count();
        }
      }

      $data = array(
        'total' => $total,
        'data' => $registros
      );

      return response()->json($data, 200);
    }
  }

  public function anexgridVersionesServiciosCanceladasConFiltros(Request $request)
  {

    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $spat = Session::get('sesionpaterno');
    $smat = Session::get('sesionmaterno');
    $stipo = Session::get('sesiontipo');

    if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    } else {

      // Obteniendo usuarios a cargo de un admin
      $aCargo = \DB::select("SELECT iduAsignado FROM usuariosacargos WHERE idu =$sidu");
      if (count($aCargo) > 0) {
        for ($i = 0; $i < count($aCargo); $i++) {
          $usuariosReporte[$i] = $aCargo[$i]->iduAsignado;
        }
        array_push($usuariosReporte, $sidu);
      } else {
        $usuariosReporte[0] = $sidu;
      }

      $idc = $request->idc;
      $fechaInicio = $request->fechaInicio;
      $fechaFin = $request->fechaFin;

      // if ($fechaInicio == ''){$fechaInicio="0000-00-00";}
      // if ($fechaFin == ''){$fechaFin="9999-99-99";}

      $anexGrid = new anexGrid();
      $clienteSucursal = '';
      $numeroCotizacion = '';
      $nombreUsuario = '';
      $estatus = '';

      foreach ($anexGrid->filtros as $f) {
        if ($f['columna'] == 'numeroCotizacion' && $f['valor'] != '') {
          $numeroCotizacion = $f['valor'];
        }

        if ($f['columna'] == 'nombreUsuario' && $f['valor'] != '') {
          $nombreUsuario = $f['valor'];
        }

        if ($f['columna'] == 'estatus' && $f['valor'] != '') {
          $estatus = $f['valor'];
        }

        if ($f['columna'] == 'clienteSucursal' && $f['valor'] != '') {
          $clienteSucursal = $f['valor'];
        }
      }

      $order;
      $anexGrid->columna_orden != '' ? $order = $anexGrid->columna_orden : $order = 'ASC';

      if ($stipo == 'Administrador') {

        if ($idc == "todos") {
          $registros = DB::table('cotizacionServicios')
            ->select(
              'idCotizacionServicios',
              'numeroCotizacion',
              'cotizacionServicios.created_at',
              'fechaCotizacion',
              'clonActual',
              'noServicios',
              'estatus',
              'cotizacionServicios.nombreUsuario',
              'repetidos',
              'numCotizacion',
              'clienteSucursal',
              DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
              DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
            )
            ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
            ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
            ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
            ->where('estatus', '=', 'Cancelada')
            ->where('fechaCotizacion', '>=', $fechaInicio)
            ->where('fechaCotizacion', '<=', $fechaFin)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
            ->get();

          $total = DB::table('cotizacionServicios')
            ->select(
              'idCotizacionServicios',
              'numeroCotizacion',
              'cotizacionServicios.created_at',
              'fechaCotizacion',
              'clonActual',
              'noServicios',
              'estatus',
              'cotizacionServicios.nombreUsuario',
              'repetidos',
              'numCotizacion',
              'clienteSucursal',
              DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
              DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
            )
            ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
            ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
            ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
            ->where('estatus', '=', 'Cancelada')
            ->where('fechaCotizacion', '>=', $fechaInicio)
            ->where('fechaCotizacion', '<=', $fechaFin)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
            ->count();
        } else {

          $registros = DB::table('cotizacionServicios')
            ->select(
              'idCotizacionServicios',
              'numeroCotizacion',
              'cotizacionServicios.created_at',
              'fechaCotizacion',
              'clonActual',
              'noServicios',
              'estatus',
              'cotizacionServicios.nombreUsuario',
              'repetidos',
              'numCotizacion',
              'clienteSucursal',
              DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
              DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
            )
            ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
            ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
            ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
            ->where('estatus', '=', 'Cancelada')
            ->where('fechaCotizacion', '>=', $fechaInicio)
            ->where('fechaCotizacion', '<=', $fechaFin)
            ->where('idc', '=', $idc)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
            ->get();

          $total = DB::table('cotizacionServicios')
            ->select(
              'idCotizacionServicios',
              'numeroCotizacion',
              'cotizacionServicios.created_at',
              'fechaCotizacion',
              'clonActual',
              'noServicios',
              'estatus',
              'cotizacionServicios.nombreUsuario',
              'repetidos',
              'numCotizacion',
              'clienteSucursal',
              DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
              DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
            )
            ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
            ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
            ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
            ->where('estatus', '=', 'Cancelada')
            ->where('fechaCotizacion', '>=', $fechaInicio)
            ->where('fechaCotizacion', '<=', $fechaFin)
            ->where('idc', '=', $idc)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
            ->count();
        }
      } else {

        if ($idc == "todos") {
          $registros = DB::table('cotizacionServicios')
            ->select(
              'idCotizacionServicios',
              'numeroCotizacion',
              'cotizacionServicios.created_at',
              'fechaCotizacion',
              'clonActual',
              'noServicios',
              'estatus',
              'cotizacionServicios.nombreUsuario',
              'repetidos',
              'numCotizacion',
              'clienteSucursal',
              DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
              DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
            )
            ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
            ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
            ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
            ->where('estatus', '=', 'Cancelada')
            ->where('fechaCotizacion', '>=', $fechaInicio)
            ->where('fechaCotizacion', '<=', $fechaFin)
            ->whereIn('cotizacionServicios.idu', $usuariosReporte)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
            ->get();

          $total = DB::table('cotizacionServicios')
            ->select(
              'idCotizacionServicios',
              'numeroCotizacion',
              'cotizacionServicios.created_at',
              'fechaCotizacion',
              'clonActual',
              'noServicios',
              'estatus',
              'cotizacionServicios.nombreUsuario',
              'repetidos',
              'numCotizacion',
              'clienteSucursal',
              DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
              DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
            )
            ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
            ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
            ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
            ->where('estatus', '=', 'Cancelada')
            ->where('fechaCotizacion', '>=', $fechaInicio)
            ->where('fechaCotizacion', '<=', $fechaFin)
            ->whereIn('cotizacionServicios.idu', $usuariosReporte)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
            ->count();
        } else {

          $registros = DB::table('cotizacionServicios')
            ->select(
              'idCotizacionServicios',
              'numeroCotizacion',
              'cotizacionServicios.created_at',
              'fechaCotizacion',
              'clonActual',
              'noServicios',
              'estatus',
              'cotizacionServicios.nombreUsuario',
              'repetidos',
              'numCotizacion',
              'clienteSucursal',
              DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
              DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
            )
            ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
            ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
            ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
            ->where('estatus', '=', 'Cancelada')
            ->where('fechaCotizacion', '>=', $fechaInicio)
            ->where('fechaCotizacion', '<=', $fechaFin)
            ->where('idc', '=', $idc)
            ->whereIn('cotizacionServicios.idu', $usuariosReporte)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
            ->get();

          $total = DB::table('cotizacionServicios')
            ->select(
              'idCotizacionServicios',
              'numeroCotizacion',
              'cotizacionServicios.created_at',
              'fechaCotizacion',
              'clonActual',
              'noServicios',
              'estatus',
              'cotizacionServicios.nombreUsuario',
              'repetidos',
              'numCotizacion',
              'clienteSucursal',
              DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
              DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
            )
            ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
            ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
            ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
            ->where('estatus', '=', 'Cancelada')
            ->where('fechaCotizacion', '>=', $fechaInicio)
            ->where('fechaCotizacion', '<=', $fechaFin)
            ->where('idc', '=', $idc)
            ->whereIn('cotizacionServicios.idu', $usuariosReporte)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
            ->count();
        }
      }

      $data = array(
        'total' => $total,
        'data' => $registros
      );

      return response()->json($data, 200);
    }
  }

  public function anexgridVersionesServiciosCotizacion(Request $request)
  {

    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $spat = Session::get('sesionpaterno');
    $smat = Session::get('sesionmaterno');
    $stipo = Session::get('sesiontipo');

    if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    } else {

      // Obteniendo usuarios a cargo de un admin
      $aCargo = \DB::select("SELECT iduAsignado FROM usuariosacargos WHERE idu =$sidu");
      if (count($aCargo) > 0) {
        for ($i = 0; $i < count($aCargo); $i++) {
          $usuariosReporte[$i] = $aCargo[$i]->iduAsignado;
        }
        array_push($usuariosReporte, $sidu);
      } else {
        $usuariosReporte[0] = $sidu;
      }

      $anexGrid = new anexGrid();
      $clienteSucursal = '';
      $numeroCotizacion = '';
      $nombreUsuario = '';
      $estatus = '';

      foreach ($anexGrid->filtros as $f) {
        if ($f['columna'] == 'numeroCotizacion' && $f['valor'] != '') {
          $numeroCotizacion = $f['valor'];
        }

        if ($f['columna'] == 'nombreUsuario' && $f['valor'] != '') {
          $nombreUsuario = $f['valor'];
        }

        if ($f['columna'] == 'estatus' && $f['valor'] != '') {
          $estatus = $f['valor'];
        }

        if ($f['columna'] == 'clienteSucursal' && $f['valor'] != '') {
          $clienteSucursal = $f['valor'];
        }
      }

      $order;
      $anexGrid->columna_orden != '' ? $order = $anexGrid->columna_orden : $order = 'ASC';

      if ($stipo == 'Administrador') {

        $registros = DB::table('cotizacionServicios')
          ->select(
            'idCotizacionServicios',
            'numeroCotizacion',
            'cotizacionServicios.created_at',
            'fechaCotizacion',
            'clonActual',
            'noServicios',
            'estatus',
            'cotizacionServicios.nombreUsuario',
            'repetidos',
            'numCotizacion',
            'clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
          )
          ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
          ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
          ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
          ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
          ->where('estatus', 'like', '%' . $estatus . '%')
          ->where('estatus', '!=', 'Cancelada')
          ->orderBy($anexGrid->columna, $order)
          ->skip($anexGrid->pagina)
          ->take($anexGrid->limite)
          ->get();

        $total = DB::table('cotizacionServicios')
          ->select(
            'idCotizacionServicios',
            'numeroCotizacion',
            'cotizacionServicios.created_at',
            'fechaCotizacion',
            'clonActual',
            'noServicios',
            'estatus',
            'cotizacionServicios.nombreUsuario',
            'repetidos',
            'numCotizacion',
            'clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
          )
          ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
          ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
          ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
          ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
          ->where('estatus', 'like', '%' . $estatus . '%')
          ->where('estatus', '!=', 'Cancelada')
          ->orderBy($anexGrid->columna, $order)
          ->skip($anexGrid->pagina)
          ->take($anexGrid->limite)
          ->count();
      } else {

        $registros = DB::table('cotizacionServicios')
          ->select(
            'idCotizacionServicios',
            'numeroCotizacion',
            'cotizacionServicios.created_at',
            'fechaCotizacion',
            'clonActual',
            'noServicios',
            'estatus',
            'cotizacionServicios.nombreUsuario',
            'repetidos',
            'numCotizacion',
            'clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
          )
          ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
          ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
          ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
          ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
          ->where('estatus', 'like', '%' . $estatus . '%')
          ->where('estatus', '!=', 'Cancelada')
          ->whereIn('cotizacionServicios.idu', $usuariosReporte)
          ->orderBy($anexGrid->columna, $order)
          ->skip($anexGrid->pagina)
          ->take($anexGrid->limite)
          ->get();

        $total = DB::table('cotizacionServicios')
          ->select(
            'idCotizacionServicios',
            'numeroCotizacion',
            'cotizacionServicios.created_at',
            'fechaCotizacion',
            'clonActual',
            'noServicios',
            'estatus',
            'cotizacionServicios.nombreUsuario',
            'repetidos',
            'numCotizacion',
            'clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
          )
          ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
          ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
          ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
          ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
          ->where('estatus', 'like', '%' . $estatus . '%')
          ->where('estatus', '!=', 'Cancelada')
          ->whereIn('cotizacionServicios.idu', $usuariosReporte)
          ->orderBy($anexGrid->columna, $order)
          ->skip($anexGrid->pagina)
          ->take($anexGrid->limite)
          ->count();
      }

      $data = array(
        'total' => $total,
        'data' => $registros
      );

      return response()->json($data, 200);
    }
  }

  public function anexgridVersionesServiciosCanceladas(Request $request)
  {
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $spat = Session::get('sesionpaterno');
    $smat = Session::get('sesionmaterno');
    $stipo = Session::get('sesiontipo');

    if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    } else {

      // Obteniendo usuarios a cargo de un admin
      $aCargo = \DB::select("SELECT iduAsignado FROM usuariosacargos WHERE idu =$sidu");
      if (count($aCargo) > 0) {
        for ($i = 0; $i < count($aCargo); $i++) {
          $usuariosReporte[$i] = $aCargo[$i]->iduAsignado;
        }
        array_push($usuariosReporte, $sidu);
      } else {
        $usuariosReporte[0] = $sidu;
      }

      $anexGrid = new anexGrid();
      $clienteSucursal = '';
      $numeroCotizacion = '';
      $nombreUsuario = '';
      $estatus = '';

      foreach ($anexGrid->filtros as $f) {
        if ($f['columna'] == 'numeroCotizacion' && $f['valor'] != '') {
          $numeroCotizacion = $f['valor'];
        }

        if ($f['columna'] == 'nombreUsuario' && $f['valor'] != '') {
          $nombreUsuario = $f['valor'];
        }

        if ($f['columna'] == 'estatus' && $f['valor'] != '') {
          $estatus = $f['valor'];
        }

        if ($f['columna'] == 'clienteSucursal' && $f['valor'] != '') {
          $clienteSucursal = $f['valor'];
        }
      }

      $order;
      $anexGrid->columna_orden != '' ? $order = $anexGrid->columna_orden : $order = 'ASC';

      if ($stipo == 'Administrador') {

        $registros = DB::table('cotizacionServicios')
          ->select(
            'idCotizacionServicios',
            'numeroCotizacion',
            'cotizacionServicios.created_at',
            'fechaCotizacion',
            'clonActual',
            'noServicios',
            'estatus',
            'cotizacionServicios.nombreUsuario',
            'repetidos',
            'numCotizacion',
            'clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
          )
          ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
          ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
          ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
          ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
          ->where('estatus', '=', 'Cancelada')
          ->orderBy($anexGrid->columna, $order)
          ->skip($anexGrid->pagina)
          ->take($anexGrid->limite)
          ->get();

        $total = DB::table('cotizacionServicios')
          ->select(
            'idCotizacionServicios',
            'numeroCotizacion',
            'cotizacionServicios.created_at',
            'fechaCotizacion',
            'clonActual',
            'noServicios',
            'estatus',
            'cotizacionServicios.nombreUsuario',
            'repetidos',
            'numCotizacion',
            'clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
          )
          ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
          ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
          ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
          ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
          ->where('estatus', '=', 'Cancelada')
          ->orderBy($anexGrid->columna, $order)
          ->skip($anexGrid->pagina)
          ->take($anexGrid->limite)
          ->count();
      } else {

        $registros = DB::table('cotizacionServicios')
          ->select(
            'idCotizacionServicios',
            'numeroCotizacion',
            'cotizacionServicios.created_at',
            'fechaCotizacion',
            'clonActual',
            'noServicios',
            'estatus',
            'cotizacionServicios.nombreUsuario',
            'repetidos',
            'numCotizacion',
            'clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
          )
          ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
          ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
          ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
          ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
          ->where('estatus', '=', 'Cancelada')
          ->whereIn('cotizacionServicios.idu', $usuariosReporte)
          ->orderBy($anexGrid->columna, $order)
          ->skip($anexGrid->pagina)
          ->take($anexGrid->limite)
          ->get();

        $total = DB::table('cotizacionServicios')
          ->select(
            'idCotizacionServicios',
            'numeroCotizacion',
            'cotizacionServicios.created_at',
            'fechaCotizacion',
            'clonActual',
            'noServicios',
            'estatus',
            'cotizacionServicios.nombreUsuario',
            'repetidos',
            'numCotizacion',
            'clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda')
          )
          ->leftJoin('usuarios', 'cotizacionServicios.idu', '=', 'usuarios.idu')
          ->where('numeroCotizacion', 'like', '%' . $numeroCotizacion . '%')
          ->where('clienteSucursal', 'like', '%' . $clienteSucursal . '%')
          ->where('cotizacionServicios.nombreUsuario', 'like', '%' . $nombreUsuario . '%')
          ->where('estatus', '=', 'Cancelada')
          ->whereIn('cotizacionServicios.idu', $usuariosReporte)
          ->orderBy($anexGrid->columna, $order)
          ->skip($anexGrid->pagina)
          ->take($anexGrid->limite)
          ->count();
      }

      $data = array(
        'total' => $total,
        'data' => $registros
      );

      return response()->json($data, 200);
    }
  }

  public function actualizarCotizacionServicios(Request $request)
  {
    $idCotizacionServicios = $request->get('idCotizacionServicios');

    $consultaCotizacion = cotizacionServicios::find($idCotizacionServicios);
    $clonCotizacion = $consultaCotizacion->replicate();
    $clonCotizacion->save();

    $consultaCotizacionServicios = detalleCotizacionServicios::where('idCotizacionServicios', '=', $idCotizacionServicios)->get();
    $asignacionesOriginales = \DB::select("SELECT MAX(created_at) AS asignacionOriginal FROM detalleCotizacionServicios WHERE idCotizacionServicios=?", [$idCotizacionServicios]);
    for ($i = 0; $i < count($consultaCotizacionServicios); $i++) {
      $clonCotizacion = $consultaCotizacionServicios[$i]->replicate();
      $clonCotizacion->save();
    }

    $numeroCotizacion = \DB::select("SELECT numeroCotizacion,cotizacionOriginal FROM cotizacionServicios WHERE idCotizacionServicios=?", [$idCotizacionServicios]);

    $cotizacionAnterior = \DB::select("SELECT co.idCotizacionServicios,co.idc,co.idu,co.idSucursal,co.numeroCotizacion,co.cotizacionOriginal,co.fechaCotizacion,co.estatus,co.tipoMoneda,co.cambio,
    CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
    co.noServicios,co.dirigidoA,co.conCopia,
    co.montoTotal,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus,co.check1,co.textoCheck1,co.check2,co.textoCheck2,co.check3,co.textoCheck3,co.check4,
		co.textoCheck4,co.check5,co.textoCheck5,co.check6,co.textoCheck6,co.check7,co.textoCheck7,co.check8,co.textoCheck8
    FROM cotizacionServicios AS co
    INNER JOIN usuarios AS u ON u.idu= co.idu
    INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
    INNER JOIN clientes AS c ON c.idc= co.idc
    WHERE idCotizacionServicios=(SELECT MAX(idCotizacionServicios) AS idCotizacionEditar FROM cotizacionServicios WHERE numeroCotizacion =?)", [$numeroCotizacion[0]->numeroCotizacion]);

    $idc = $cotizacionAnterior[0]->idc;
    $comboServiciosTecnicos = \DB::select("SELECT * FROM serviciosTecnicos where activo = 'Si' ORDER BY servicioTecnico ASC;");
    $comboUsuariosAdmon = usuarios::where('tipo', '=', 'Administrador', 'AND', 'activo', '=', 'Si')->orderBy('nombreUsuario', 'ASC')->get();
    $comboUsuariosVendedor = usuarios::where('tipo', '=', 'Vendedor', 'AND', 'activo', '=', 'Si')->orderBy('nombreUsuario', 'ASC')->get();
    $comboConCopia = \DB::select("SELECT co.nombreCont,s.sucursal from contactosucursales as co INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal where co.idc=$idc");

    $fotoCotizacionEditar = \DB::select("SELECT archivoCotizacion FROM cotizacionServicios WHERE idCotizacionServicios=(SELECT MAX(idCotizacionServicios) AS idCotizacionEditar FROM cotizacionServicios WHERE numeroCotizacion =?)", [$numeroCotizacion[0]->numeroCotizacion]);

    $idCotizacionServiciosClon = \DB::select("SELECT MAX(idCotizacionServicios) AS idCotizacionClon FROM cotizacionServicios WHERE numeroCotizacion =?", [$numeroCotizacion[0]->numeroCotizacion]);
    $consecutivoVersion = \DB::select("SELECT MAX(numCotizacion)+1 AS consecutivo FROM cotizacionServicios WHERE cotizacionOriginal=?", [$numeroCotizacion[0]->cotizacionOriginal]);
    $consecutivoVersionCorregido = \DB::UPDATE("UPDATE cotizacionServicios  SET numCotizacion = ? WHERE idCotizacionServicios = ?", [$consecutivoVersion[0]->consecutivo, $idCotizacionServiciosClon[0]->idCotizacionClon]);


    $clonNumeroCotizacion = $cotizacionAnterior[0]->cotizacionOriginal . '-' . $consecutivoVersion[0]->consecutivo;

    $numeroCotizacionVersionNEW = \DB::UPDATE("UPDATE cotizacionServicios  SET numeroCotizacion = ? WHERE idCotizacionServicios = ?", [$clonNumeroCotizacion, $idCotizacionServiciosClon[0]->idCotizacionClon]);

    $corrigiendoidCotizacionClon = \DB::UPDATE(
      "UPDATE detalleCotizacionServicios 
    SET idCotizacionServicios = ?,numeroCotizacion = ? 
    WHERE numeroCotizacion = ? AND created_at > ?",
      [
        $idCotizacionServiciosClon[0]->idCotizacionClon, $clonNumeroCotizacion,
        $numeroCotizacion[0]->numeroCotizacion, $asignacionesOriginales[0]->asignacionOriginal
      ]
    );

    $desactivandoEquiposOriginales = \DB::UPDATE("UPDATE detalleCotizacionServicios SET clonEquiposActivo = 'No' WHERE idCotizacionServicios = ? ", [$idCotizacionServicios]);

    $ConsultaEstatusCotizacionAnterior = \DB::select("SELECT estatus FROM cotizacionServicios WHERE idCotizacionServicios=?", [$idCotizacionServicios]);
    $estatusCotizacionAnterior = \DB::UPDATE("UPDATE cotizacionServicios  SET estatusCotizacionAnterior = ? WHERE idCotizacionServicios = ?", [$ConsultaEstatusCotizacionAnterior[0]->estatus, $idCotizacionServiciosClon[0]->idCotizacionClon]);

    $desactivandoOriginal = \DB::UPDATE("UPDATE cotizacionServicios SET clonActual = 'No', estatus='Cancelada' WHERE idCotizacionServicios = ? ", [$idCotizacionServicios]);

    $cuantosEquiposCotizados = \DB::select("SELECT COUNT(*) AS cuantos FROM detalleCotizacionServicios WHERE idCotizacionServicios = ? ", [$idCotizacionServiciosClon[0]->idCotizacionClon]);

    $cotizacionEditar = \DB::select("SELECT co.idCotizacionServicios,co.idc,co.idu,co.idSucursal,co.numeroCotizacion,co.cotizacionOriginal,co.fechaCotizacion,co.estatus,co.tipoMoneda,co.cambio,co.nombreUsuario,
    CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
    co.noServicios,co.dirigidoA,co.conCopia,
    FORMAT(co.montoTotal,2) AS montoTotal,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus,co.check1,co.textoCheck1,co.check2,co.textoCheck2,co.check3,co.textoCheck3,co.check4,
		co.textoCheck4,co.check5,co.textoCheck5,co.check6,co.textoCheck6,co.check7,co.textoCheck7,co.check8,co.textoCheck8
    FROM cotizacionServicios AS co
    INNER JOIN usuarios AS u ON u.idu= co.idu
    INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
    INNER JOIN clientes AS c ON c.idc= co.idc
    WHERE idCotizacionServicios=(SELECT MAX(idCotizacionServicios) AS idCotizacionEditar FROM cotizacionServicios WHERE idCotizacionServicios =?)", [$idCotizacionServiciosClon[0]->idCotizacionClon]);

    $serviciosEncotizacion = \DB::select("SELECT dcs.idDetalleCotizacionServ,dcs.idCotizacionServicios,
    st.servicioTecnico,dcs.detalle,dcs.observacionCantidad,dcs.numeroCotizacion,dcs.notas,dcs.semanasEntrega,dcs.tipoMoneda,dcs.montoEquipo,dcs.cantidad,
    dcs.montoFinanciamiento,dcs.descuento,dcs.tipoDescuento,dcs.clonEquiposActivo,dcs.agregadoReciente
    FROM detalleCotizacionServicios AS dcs
    LEFT JOIN serviciosTecnicos AS st ON st.idServicioTecnico=dcs.idServicioTecnico
    WHERE idCotizacionServicios=?", [$idCotizacionServiciosClon[0]->idCotizacionClon]);

    $sucursales = \DB::select("SELECT * FROM contactosucursales WHERE idSucursal=?", [$cotizacionAnterior[0]->idSucursal]);
    $diasDePago = \DB::select("SELECT diasDePago FROM clientes WHERE idc=?", [$cotizacionAnterior[0]->idc]);

    $suma = \DB::select("SELECT FORMAT(SUM(dr.montoFinanciamiento),2) AS sumaTotal
    FROM detalleCotizacionServicios AS dr
    WHERE idCotizacionServicios=?", [$idCotizacionServiciosClon[0]->idCotizacionClon]);

    return view('editarVersionCotizacionServicios')
      ->with('suma', $suma[0]->sumaTotal)
      ->with('numeroCotizacionVersionNEW', $numeroCotizacionVersionNEW)
      ->with('cuantosEquiposCotizados', $cuantosEquiposCotizados)
      ->with('serviciosEncotizacion', $serviciosEncotizacion)
      ->with('comboUsuariosAdmon', $comboUsuariosAdmon)
      ->with('comboUsuariosVendedor', $comboUsuariosVendedor)
      ->with('comboConCopia', $comboConCopia)
      ->with('comboServiciosTecnicos', $comboServiciosTecnicos)
      ->with('diasDePago', $diasDePago[0]->diasDePago)
      ->with('fotoCotizacionEditar', $fotoCotizacionEditar)
      ->with('idCotizacionServicios', $idCotizacionServicios)
      ->with('cotizacionEditar', $cotizacionEditar[0])
      ->with('consulta', $sucursales);
  }

  public function editarCotizacionServicios(Request $request)
  {
    $idCotizacionServicios = $request->get('idCotizacionServicios');

    $cotizacionEditar = \DB::select("SELECT co.idCotizacionServicios,co.idc,co.idu,co.idSucursal,co.cambio,co.numeroCotizacion,co.fechaCotizacion,co.estatus,tipoMoneda,co.nombreUsuario,
    CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
    co.noServicios,co.dirigidoA,co.conCopia,
    co.montoTotal,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus,co.check1,co.textoCheck1,co.check2,co.textoCheck2,co.check3,co.textoCheck3,co.check4,
		co.textoCheck4,co.check5,co.textoCheck5,co.check6,co.textoCheck6,co.check7,co.textoCheck7,co.check8,co.textoCheck8
    FROM cotizacionServicios AS co
    INNER JOIN usuarios AS u ON u.idu= co.idu
    INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
    INNER JOIN clientes AS c ON c.idc= co.idc
    WHERE idCotizacionServicios=?", [$idCotizacionServicios]);

    $fotoCotizacionEditar = \DB::select("SELECT archivoCotizacion FROM cotizacionServicios WHERE idCotizacionServicios=?", [$idCotizacionServicios]);

    $sucursales = \DB::select("SELECT * FROM contactosucursales WHERE idSucursal=?", [$cotizacionEditar[0]->idSucursal]);
    $idc = $cotizacionEditar[0]->idc;
    $comboUsuariosAdmon = usuarios::where('tipo', '=', 'Administrador', 'AND', 'activo', '=', 'Si')->orderBy('nombreUsuario', 'ASC')->get();
    $comboUsuariosVendedor = usuarios::where('tipo', '=', 'Vendedor', 'AND', 'activo', '=', 'Si')->orderBy('nombreUsuario', 'ASC')->get();
    $comboConCopia = \DB::select("SELECT co.nombreCont,s.sucursal from contactosucursales as co INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal where co.idc=$idc");

    return view('editarCotizacionServicios')
      ->with('fotoCotizacionEditar', $fotoCotizacionEditar)
      ->with('cotizacionEditar', $cotizacionEditar[0])
      ->with('comboUsuariosAdmon', $comboUsuariosAdmon)
      ->with('comboUsuariosVendedor', $comboUsuariosVendedor)
      ->with('comboConCopia', $comboConCopia)
      ->with('consulta', $sucursales);
  }

  public function guardarEditarCotizacionServicios(Request $request)
  {
    $idCotizacionServicios = $request->idCotizacionServicios;
    $estatus = $request->estatus;
    $montoTotal = $request->montoTotal;
    $ordenCompra = $request->ordenCompra;

    $idc = $request->idc;
    $empresa = \DB::select("SELECT empresaPertenece FROM clientes WHERE idc = ?", [$idc]);

    $file = $request->file('archivoCotizacion');
    if ($file != "") {
      $ldate = date('Ymd_His_');
      $img = $file->getClientOriginalName();
      $img2 = $ldate . $img;
      \Storage::disk('local')->put($img2, \File::get($file));
    }

    $cotizacion = cotizacionServicios::find($idCotizacionServicios);

    if ($file != "") {
      $cotizacion->archivoCotizacion = $img2;
    }
    $cotizacion->idCotizacionServicios = $idCotizacionServicios;
    $cotizacion->idu = $request->idu;
    $cotizacion->nombreUsuario = $request->nombreUsuario;
    $cotizacion->idc = $request->idc;
    $cotizacion->fechaCotizacion = $request->fechaCotizacion;
    $cotizacion->numeroCotizacion = $request->numeroCotizacion;
    $cotizacion->dirigidoA = $request->dirigidoA;
    $cotizacion->conCopia = $request->conCopia;
    $cotizacion->estatus = $estatus;
    $cotizacion->montoTotal = ROUND($request->montoTotal, 2);
    $cotizacion->tipoMoneda = $request->tipoMoneda;
    $cotizacion->cambio = $request->cambio;
    // $cotizacion-> numCotizacion = $request-> numCotizacion;
    if ($estatus == "Cancelada") {
      $cotizacion->clonActual = 'No';
      $desactivandoEquiposOriginales = \DB::UPDATE("UPDATE detalleCotizacionServicios SET clonEquiposActivo = 'No' WHERE idCotizacionServicios = ? ", [$idCotizacionServicios]);
    } else {
      $cotizacion->clonActual = 'Si';
    }
    $cotizacion->activo = 'Si';
    $cotizacion->check1 = $request->check1;
    $cotizacion->textoCheck1 = $request->textoCheck1;
    $cotizacion->check2 = $request->check2;
    $cotizacion->textoCheck2 = $request->textoCheck2;
    $cotizacion->check3 = $request->check3;
    $cotizacion->textoCheck3 = $request->textoCheck3;
    $cotizacion->check4 = $request->check4;
    $cotizacion->textoCheck4 = $request->textoCheck4;
    $cotizacion->check5 = $request->check5;
    $cotizacion->textoCheck5 = $request->textoCheck5;
    $cotizacion->check6 = $request->check6;
    $cotizacion->textoCheck6 = $request->textoCheck6;
    $cotizacion->check7 = $request->check7;
    $cotizacion->textoCheck7 = $request->textoCheck7;
    $cotizacion->check8 = $request->check8;
    $cotizacion->textoCheck8 = $request->textoCheck8;
    $cotizacion->save();

    $repetidos = \DB::select("SELECT idServicioTecnico FROM detalleCotizacionServicios 
    WHERE idCotizacionServicios = ? GROUP BY idServicioTecnico HAVING COUNT(*)>1;", [$idCotizacionServicios]);
    $cuantosRepetidos = count($repetidos);

    // Creando servicio en tabla facturas
    if ($estatus == "Aceptada con orden de compra") {

      $consultandoSiYaTieneServicioAsignado = \DB::select("SELECT idFactura FROM facturas WHERE tipoServicio='Soporte Técnico' AND idCotizacion = ? ", [$idCotizacionServicios]);
      $cuantosAsignados = count($consultandoSiYaTieneServicioAsignado);

      if ($cuantosAsignados >= 1) {

        $file = $request->file('archivo');
        if ($file != "") {
          $ldate = date('Ymd_His_');
          $img = $file->getClientOriginalName();
          $img2 = $ldate . $img;
          \Storage::disk('local')->put($img2, \File::get($file));
        }
        $fac = facturas::find($consultandoSiYaTieneServicioAsignado[0]->idFactura);
        if ($file != "") {
          $actualizandoCotizacionEnFactura = \DB::UPDATE("UPDATE facturas SET archivo = ?
          WHERE idFactura = ?", [$img2, $consultandoSiYaTieneServicioAsignado[0]->idFactura]);
        }
        $actualizandoCotizacionEnFactura = \DB::UPDATE("UPDATE facturas SET idCotizacion = ?, montoFactura=?, ordenCompra=? WHERE idFactura = ?", [$idCotizacionServicios, $montoTotal, $ordenCompra, $consultandoSiYaTieneServicioAsignado[0]->idFactura]);

        // $actualizandoCotizacionEnFactura = \DB::UPDATE("UPDATE facturas SET idCotizacion = ? WHERE idFactura = ?",[$idCotizacionServicios,$consultandoSiYaTieneServicioAsignado[0]->idFactura]);
      } else {
        $idServiciosig = facturas::withTrashed()->orderby('idServicios', 'desc')->take(1)->get();
        $idServ = $idServiciosig[0]->idServicios + 1;
        $idc = $request->idc;
        $diasDePago = \DB::select("SELECT diasDePago FROM clientes WHERE idc= $idc");

        $file = $request->file('archivo');
        if ($file != "") {
          $archivo =  time() . "_" . $file->getClientOriginalName();
          \Storage::disk('local')->put($archivo,  \File::get($file));
        } else {
          $archivo = "Sin archivo";
        }

        $fac = new facturas;
        $fac->idServicios = $idServ;
        $fac->tipoServicio = "Soporte Técnico";
        $fac->nombreEmpresa = $empresa[0]->empresaPertenece;
        // $fac-> fechaVencimiento = "0000-00-00";
        // $fac-> idb = $request-> idb;
        $fac->montoFactura = ROUND($request->montoTotal, 2);
        $fac->periodoPago = $diasDePago[0]->diasDePago;
        $fac->numeroFactura = "-";
        // $fac-> fechaFactura = "0000-00-00";
        // $fac-> fechaPago = "0000-00-00";
        $fac->tipoMoneda = $request->tipoMoneda;
        $fac->estatusEntrega = "Pendiente";
        $fac->estatusPago = "Pendiente";
        $fac->metodoPago = "PUE";
        $fac->estatusPortal = "Pendiente en subir";
        $fac->cashFlow = "No";
        $fac->idc = $idc;
        $fac->idSucursal = $request->idSucursal;
        $fac->idCotizacion = $idCotizacionServicios;
        $fac->ordenCompra = $request->ordenCompra;
        $fac->archivo = $archivo;
        $fac->archivoFactura = "Sin archivo";
        $fac->archivoPago = "Sin archivo";
        $fac->xmlFactura = "Sin archivo";
        $fac->archivoRemision = "Sin archivo";
        $fac->archivoAdenda = "Sin archivo";
        $fac->save();

        $idFactura = \DB::select("SELECT idFactura FROM facturas WHERE tipoServicio='Soporte Técnico' AND idCotizacion= $idCotizacionServicios");
        $asigDetalles = new asignacionesdetalles;
        $asigDetalles->idFactura = $idFactura[0]->idFactura;
        $asigDetalles->fecha = $request->fechaCotizacion;
        $asigDetalles->idu = $request->idu;
        $asigDetalles->save();
      }
    }

    $proceso = 'Modificación de cotización de servicios';
    $mensaje = "Cotización actualizada correctamente";

    return view('mensajeCotizacionesServicios')
      ->with('proceso', $proceso)
      ->with('mensaje', $mensaje);
  }

  public function guardarModificacionCotizacionServicios(Request $request)
  {
    $idCotizacionServicios = $request->idCotizacionServicios;
    $estatus = $request->estatus;
    $ordenCompra = $request->ordenCompra;

    $idc = $request->idc;
    $empresa = \DB::select("SELECT empresaPertenece FROM clientes WHERE idc = ?", [$idc]);

    $file = $request->file('archivoCotizacion');
    if ($file != "") {
      $ldate = date('Ymd_His_');
      $img = $file->getClientOriginalName();
      $img2 = $ldate . $img;
      \Storage::disk('local')->put($img2, \File::get($file));
    }

    $cotizacion = cotizacionServicios::find($idCotizacionServicios);

    if ($file != "") {
      $cotizacion->archivoCotizacion = $img2;
    }
    $cotizacion->idu = $request->idu;
    $cotizacion->nombreUsuario = $request->nombreUsuario;
    $cotizacion->idc = $request->idc;
    $cotizacion->fechaCotizacion = $request->fechaCotizacion;
    // $cotizacion-> idSucursal = $request-> idSucursal;
    $cotizacion->numeroCotizacion = $request->numeroCotizacionVista;
    $cotizacion->dirigidoA = $request->dirigidoA;
    $cotizacion->conCopia = $request->conCopia;
    $cotizacion->estatus = $estatus;
    $cotizacion->montoTotal = ROUND($request->montoTotal, 2);
    $cotizacion->tipoMoneda = $request->tipoMoneda;
    $cotizacion->cambio = $request->cambio;

    // $cotizacion-> numCotizacion = $request-> numCotizacion;
    if ($estatus == "Cancelada") {
      $cotizacion->clonActual = 'No';
      $desactivandoEquiposOriginales = \DB::UPDATE("UPDATE detalleCotizacionServicios SET clonEquiposActivo = 'No' WHERE idCotizacionServicios = ? ", [$idCotizacionServicios]);
    } else {
      $cotizacion->clonActual = 'Si';
    }
    $cotizacion->activo = 'Si';
    $cotizacion->check1 = $request->check1;
    $cotizacion->textoCheck1 = $request->textoCheck1;
    $cotizacion->check2 = $request->check2;
    $cotizacion->textoCheck2 = $request->textoCheck2;
    $cotizacion->check3 = $request->check3;
    $cotizacion->textoCheck3 = $request->textoCheck3;
    $cotizacion->check4 = $request->check4;
    $cotizacion->textoCheck4 = $request->textoCheck4;
    $cotizacion->check5 = $request->check5;
    $cotizacion->textoCheck5 = $request->textoCheck5;
    $cotizacion->check6 = $request->check6;
    $cotizacion->textoCheck6 = $request->textoCheck6;
    $cotizacion->check7 = $request->check7;
    $cotizacion->textoCheck7 = $request->textoCheck7;
    $cotizacion->check8 = $request->check8;
    $cotizacion->textoCheck8 = $request->textoCheck8;
    $cotizacion->save();

    // $idSiguiente = \DB::table('cotizaciones')->max('idCotizacion');//estas dos lineas corrigen el idCotizacion en detllereparaciones para relacionarlas correctamente
    // $corrigiendoidCotizacionenTablaDetalle = \DB::UPDATE("UPDATE detallereparaciones SET idCotizacion = ? WHERE numeroCotizacion= ?",[$idSiguiente,$numeroCotizacion]);

    $montoTotal = \DB::select("SELECT idCotizacionServicios, ROUND(SUM(montoFinanciamiento),2) AS montoTotal FROM detalleCotizacionServicios WHERE idCotizacionServicios = ? GROUP BY idCotizacionServicios", [$idCotizacionServicios]);
    $noServicios = \DB::select("SELECT idCotizacionServicios, COUNT(idCotizacionServicios) AS noServicios FROM detalleCotizacionServicios WHERE idCotizacionServicios = ? GROUP BY idCotizacionServicios", [$idCotizacionServicios]);

    $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizacionServicios SET montoTotal = ?, noServicios=? WHERE idCotizacionServicios= ?", [$montoTotal[0]->montoTotal, $noServicios[0]->noServicios, $idCotizacionServicios]);

    $repetidos = \DB::select("SELECT idServicioTecnico FROM detalleCotizacionServicios WHERE idCotizacionServicios = ? GROUP BY idServicioTecnico HAVING COUNT(*)>1;", [$idCotizacionServicios]);
    $cuantosRepetidos = count($repetidos);
    if ($cuantosRepetidos > 0) {
      $siRepetidos = \DB::UPDATE("UPDATE cotizacionServicios SET repetidos = 'si' WHERE idCotizacionServicios= ?", [$idCotizacionServicios]);
      //   $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizacionServicios SET montoTotal = '0', noServicios=? WHERE idCotizacionServicios= ?",[$noServicios[0]->noServicios,$idCotizacionServicios]);
      $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizacionServicios SET montoTotal = ?, noServicios=? WHERE idCotizacionServicios= ?", [$montoTotal[0]->montoTotal, $noServicios[0]->noServicios, $idCotizacionServicios]);
    } else {
      $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizacionServicios SET montoTotal = ?, noServicios=? WHERE idCotizacionServicios= ?", [$montoTotal[0]->montoTotal, $noServicios[0]->noServicios, $idCotizacionServicios]);
    }
    // return redirect()->route('cotizaciones');//retorna al metodo o ruta cotizaciones

    if ($estatus == "Aceptada con orden de compra") {

      $idCotizacionServiciosAnterior = $request->idCotizacionAnterior;
      $consultandoSiYaTieneServicioAsignado = \DB::select("SELECT idFactura FROM facturas WHERE tipoServicio='Soporte Técnico' AND idCotizacion = ? ", [$idCotizacionServiciosAnterior]);
      $cuantosAsignados = count($consultandoSiYaTieneServicioAsignado);

      if ($cuantosAsignados >= 1) {
        // if($cuantosRepetidos > 0){
        //   $montoTotal = "0";
        // }else{
        $montoTotal = $montoTotal[0]->montoTotal;
        // }

        $file = $request->file('archivo');
        if ($file != "") {
          $ldate = date('Ymd_His_');
          $img = $file->getClientOriginalName();
          $img2 = $ldate . $img;
          \Storage::disk('local')->put($img2, \File::get($file));
        }
        $fac = facturas::find($consultandoSiYaTieneServicioAsignado[0]->idFactura);
        if ($file != "") {
          $actualizandoCotizacionEnFactura = \DB::UPDATE("UPDATE facturas SET archivo = ?
            WHERE idFactura = ?", [$img2, $consultandoSiYaTieneServicioAsignado[0]->idFactura]);
        }
        $actualizandoCotizacionEnFactura = \DB::UPDATE("UPDATE facturas SET idCotizacion = ?, montoFactura=?, ordenCompra=? WHERE idFactura = ?", [$idCotizacionServicios, $montoTotal, $ordenCompra, $consultandoSiYaTieneServicioAsignado[0]->idFactura]);

        // $actualizandoCotizacionEnFactura = \DB::UPDATE("UPDATE facturas SET idCotizacion = ?, montoFactura = ? WHERE idFactura = ?",[$idCotizacionServicios,$montoTotal,$consultandoSiYaTieneServicioAsignado[0]->idFactura]);
      } else {
        $idServiciosig = facturas::withTrashed()->orderby('idServicios', 'desc')->take(1)->get();
        $idServ = $idServiciosig[0]->idServicios + 1;
        $idc = $request->idc;
        $diasDePago = \DB::select("SELECT diasDePago FROM clientes WHERE idc= $idc");

        $file = $request->file('archivo');
        if ($file != "") {
          $archivo =  time() . "_" . $file->getClientOriginalName();
          \Storage::disk('local')->put($archivo,  \File::get($file));
        } else {
          $archivo = "Sin archivo";
        }

        $fac = new facturas;
        $fac->idServicios = $idServ;
        $fac->tipoServicio = "Soporte Técnico";
        $fac->nombreEmpresa = $empresa[0]->empresaPertenece;
        // $fac-> fechaVencimiento = "0000-00-00";
        // $fac-> idb = $request-> idb;
        // if($cuantosRepetidos > 0){
        //   $fac-> montoFactura = "0";
        // }else{
        $fac->montoFactura = ROUND($request->montoTotal, 2);
        // }
        $fac->periodoPago = $diasDePago[0]->diasDePago;
        $fac->numeroFactura = "-";
        // $fac-> fechaFactura = "0000-00-00";
        // $fac-> fechaPago = "0000-00-00";
        $fac->tipoMoneda = $request->tipoMoneda;
        $fac->estatusEntrega = "Pendiente";
        $fac->estatusPago = "Pendiente";
        $fac->metodoPago = "PUE";
        $fac->estatusPortal = "Pendiente en subir";
        $fac->cashFlow = "No";
        $fac->idc = $idc;
        $fac->idSucursal = $request->idSucursal;
        $fac->idCotizacion = $idCotizacionServicios;
        $fac->ordenCompra = $request->ordenCompra;
        $fac->archivo = $archivo;
        $fac->archivoFactura = "Sin archivo";
        $fac->archivoPago = "Sin archivo";
        $fac->xmlFactura = "Sin archivo";
        $fac->archivoRemision = "Sin archivo";
        $fac->archivoAdenda = "Sin archivo";
        $fac->save();

        $idFactura = \DB::select("SELECT idFactura FROM facturas WHERE tipoServicio='Soporte Técnico' AND idCotizacion= $idCotizacionServicios");
        $asigDetalles = new asignacionesdetalles;
        $asigDetalles->idFactura = $idFactura[0]->idFactura;
        $asigDetalles->fecha = $request->fechaCotizacion;
        $asigDetalles->idu = $request->idu;
        $asigDetalles->save();
      }
    }

    $proceso = 'Actualización de cotización de servicios';
    $mensaje = "Cotización actualizada correctamente";

    return view('mensajeCotizacionesServicios')
      ->with('proceso', $proceso)
      ->with('mensaje', $mensaje);
  }

  public function borrarServicio(Request $request)
  {
    $idCotizacionServicios = $request->idCotizacionServicios;
    $idDetalleCotizacionServ = $request->idDetalleCotizacionServ;
    $idSucursal = $request->get('idSucursal');
    $tipoMoneda = $request->get('tipoMonedaB');
    $numeroCotizacion = $request->get('numeroCotizacionB');

    $borro = \DB::delete("DELETE FROM detalleCotizacionServicios 
    WHERE idDetalleCotizacionServ=?", [$idDetalleCotizacionServ]);

    $serviciosEncotizacion = \DB::select("SELECT dcs.idDetalleCotizacionServ,dcs.idCotizacionServicios,
    st.servicioTecnico,dcs.detalle,dcs.observacionCantidad,dcs.numeroCotizacion,dcs.notas,dcs.semanasEntrega,dcs.tipoMoneda,dcs.montoEquipo,dcs.cantidad,
    dcs.montoFinanciamiento,dcs.descuento,dcs.tipoDescuento,dcs.clonEquiposActivo,dcs.agregadoReciente
    FROM detalleCotizacionServicios AS dcs
    LEFT JOIN serviciosTecnicos AS st ON st.idServicioTecnico=dcs.idServicioTecnico
    WHERE numeroCotizacion=?", [$numeroCotizacion]);
    $cuantasServicios = count($serviciosEncotizacion);


    $suma = \DB::select("SELECT FORMAT(SUM(dr.montoFinanciamiento),2) AS sumaTotal
    FROM detalleCotizacionServicios AS dr
    WHERE numeroCotizacion=?", [$numeroCotizacion]);

    $estatusCotizacion = \DB::select("SELECT estatus FROM cotizacionServicios WHERE idCotizacionServicios = ?", [$idCotizacionServicios]);

    return view('reporteCotizacionServiciosAbajo')
      ->with('serviciosEncotizacion', $serviciosEncotizacion)
      ->with('numeroCotizacion', $numeroCotizacion)
      ->with('suma', $suma[0]->sumaTotal)
      ->with('idSucursal', $idSucursal)
      ->with('tipoMoneda', $tipoMoneda)
      ->with('cuantasServicios', $cuantasServicios)
      ->with('estatusCotizacion', $estatusCotizacion[0]->estatus);
  }

  public function cancelarVersionServicios(Request $request)
  {
    $idCotizacionServicios = $request->idCotizacionServicios;
    $idCotizacionAnteriorVista = $request->idCotizacionAnterior;
    $idc = $request->idc;
    $cotizacionOriginal = $request->cotizacionOriginal;

    $consecutivoAnterior = \DB::select("SELECT (numCotizacion-1) AS cotizacionAnterior FROM cotizacionServicios WHERE idCotizacionServicios = ?", [$idCotizacionServicios]);

    $idCotizacionServiciosAnterior = \DB::select("SELECT idCotizacionServicios FROM cotizacionServicios 
    WHERE cotizacionOriginal=? AND numCotizacion = ?", [$cotizacionOriginal, $consecutivoAnterior[0]->cotizacionAnterior]);

    $estatusCotizacionAnterior = \DB::select("SELECT estatusCotizacionAnterior FROM cotizacionServicios WHERE idCotizacionServicios = ?", [$idCotizacionServicios]);
    $activandoVersionAnterior = \DB::UPDATE("UPDATE cotizacionServicios SET clonActual ='Si',estatus=? 
    WHERE numCotizacion= ? AND idCotizacionServicios= ?", [$estatusCotizacionAnterior[0]->estatusCotizacionAnterior, $consecutivoAnterior[0]->cotizacionAnterior, $idCotizacionServiciosAnterior[0]->idCotizacionServicios]);

    $activandoEquiposAnteriores = \DB::UPDATE("UPDATE detalleCotizacionServicios SET clonEquiposActivo ='Si' 
    WHERE idCotizacionServicios= ?", [$idCotizacionServiciosAnterior[0]->idCotizacionServicios]);


    $borrarClonDetalle = \DB::delete("DELETE FROM detalleCotizacionServicios WHERE idCotizacionServicios=?", [$idCotizacionServicios]);
    $borrarClonCotizacion = \DB::delete("DELETE FROM cotizacionServicios WHERE idCotizacionServicios=?", [$idCotizacionServicios]);

    return;
  }

  public function cancelarAltaVersionServicios(Request $request)
  {
    $idCotizacionServicios = $request->idCotizacionServicios;
    $borrarCotizacion = \DB::delete("DELETE FROM cotizacionServicios WHERE idCotizacionServicios=?", [$idCotizacionServicios]);

    return;
  }

  public function verCotizacionServicios(Request $request)
  {
    $idCotizacionServicios = $request->get('idCotizacionServicios');

    $cotizacionEditar1 = \DB::select("SELECT co.idCotizacionServicios,co.idc,co.idu,co.idSucursal,co.numeroCotizacion,co.cotizacionOriginal,co.fechaCotizacion,co.estatus,tipoMoneda,
    CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
    co.noServicios,co.dirigidoA,co.conCopia,
    FORMAT(co.montoTotal,2) AS montoTotal,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus,co.check1,co.textoCheck1,co.check2,co.textoCheck2,co.check3,co.textoCheck3,co.check4,
    co.textoCheck4,co.check5,co.textoCheck5,co.check6,co.textoCheck6,co.check7,co.textoCheck7,co.check8,co.textoCheck8
    FROM cotizacionServicios AS co
    INNER JOIN usuarios AS u ON u.idu= co.idu
    INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
    INNER JOIN clientes AS c ON c.idc= co.idc
    WHERE idCotizacionServicios=?", [$idCotizacionServicios]);

    $fotoCotizacionEditar = \DB::select("SELECT archivoCotizacion FROM cotizacionServicios WHERE idCotizacionServicios=?", [$idCotizacionServicios]);
    //checar como diferenciar los id de equipos de los de servicios
    $archivoOrdenCompra = \DB::select("SELECT archivo FROM facturas WHERE idCotizacion=?", [$idCotizacionServicios]);

    $serviciosEncotizacion = \DB::select("SELECT dcs.idDetalleCotizacionServ,dcs.idCotizacionServicios,
    st.servicioTecnico,dcs.detalle,dcs.observacionCantidad,dcs.numeroCotizacion,dcs.notas,dcs.semanasEntrega,dcs.tipoMoneda,dcs.montoEquipo,dcs.cantidad,
    dcs.montoFinanciamiento,dcs.descuento,dcs.tipoDescuento,dcs.clonEquiposActivo,dcs.agregadoReciente
    FROM detalleCotizacionServicios AS dcs
    LEFT JOIN serviciosTecnicos AS st ON st.idServicioTecnico=dcs.idServicioTecnico
    WHERE idCotizacionServicios=?", [$idCotizacionServicios]);

    return view('verCotizacionServicios')
      ->with('idCotizacionServicios', $idCotizacionServicios)
      ->with('serviciosEncotizacion', $serviciosEncotizacion)
      ->with('archivoOrdenCompra', $archivoOrdenCompra)
      ->with('fotoCotizacionEditar', $fotoCotizacionEditar)
      ->with('cotizacionEditar1', $cotizacionEditar1[0]);
  }

  public function pdfCotizacionServicios(Request $request)
  {
    $idCotizacionServicios = $request->get('idCotizacionServicios');
    $verTotal = $request->get('verTotal');
    $publicidad = $request->get('pld');
    $verCambio = $request->get('cD');
    $stipo = Session::get('sesiontipo');

    $consultaCotizacion = \DB::select("SELECT co.idCotizacionServicios,c.razonSocial,co.cambio,co.idSucursal,co.dirigidoA,co.conCopia,co.numeroCotizacion,co.fechaCotizacion,co.estatus,co.tipoMoneda,co.idc,
    CONCAT(u.nombreUsuario,' ', u.aPaterno) AS usuario,
    co.montoTotal,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus,co.check1,co.textoCheck1,co.check2,co.textoCheck2,co.check3,co.textoCheck3,co.check4,
		co.textoCheck4,co.check5,co.textoCheck5,co.check6,co.textoCheck6,co.check7,co.textoCheck7,co.check8,co.textoCheck8
    FROM cotizacionServicios AS co
    INNER JOIN usuarios AS u ON u.idu= co.idu
    INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
    INNER JOIN clientes AS c ON c.idc= co.idc
    WHERE idCotizacionServicios=?", [$idCotizacionServicios]);
    $cadenaFecha = $consultaCotizacion[0]->fechaCotizacion;
    $arrayFecha = explode('-', $cadenaFecha);

    $dirigidoA = $consultaCotizacion[0]->dirigidoA;
    $conCopia = $consultaCotizacion[0]->conCopia;
    $idc = $consultaCotizacion[0]->idc;
    $consultaContacto1 = \DB::select("SELECT telefonoCont,correoCont FROM contactosucursales WHERE nombreCont = ? AND idc=?", [$dirigidoA, $idc]);
    $cuantosContactos1 = count($consultaContacto1);
    $consultaContacto2 = \DB::select("SELECT telefonoCont,correoCont FROM contactosucursales WHERE nombreCont = ? AND idc=?", [$conCopia, $idc]);
    $cuantosContactos2 = count($consultaContacto1);

    $serviciosEncotizacion = \DB::select("SELECT dcs.idDetalleCotizacionServ,dcs.idCotizacionServicios,
    st.servicioTecnico,st.claveServicio,dcs.detalle,dcs.observacionCantidad,dcs.numeroCotizacion,dcs.notas,dcs.semanasEntrega,dcs.tipoMoneda,dcs.montoEquipo,dcs.cantidad,
    dcs.montoFinanciamiento,dcs.descuento,dcs.tipoDescuento,dcs.clonEquiposActivo,dcs.agregadoReciente
    FROM detalleCotizacionServicios AS dcs
    LEFT JOIN serviciosTecnicos AS st ON st.idServicioTecnico=dcs.idServicioTecnico
    WHERE idCotizacionServicios=?", [$idCotizacionServicios]);

    PDF::setOption(config('dompdf'));

    $pdf = \PDF::loadView(
      'cotizacionServiciosPDF',
      [
        'consultaCotizacion' => $consultaCotizacion[0], 'serviciosEncotizacion' => $serviciosEncotizacion, 'arrayFecha' => $arrayFecha,
        'publicidad' => $publicidad, 'verTotal' => $verTotal, 'verCambio' => $verCambio,
        'consultaContacto1' => $consultaContacto1, 'consultaContacto2' => $consultaContacto2,
        'cuantosContactos1' => $cuantosContactos1, 'cuantosContactos2' => $cuantosContactos2, 'tipoSesion' => $stipo
      ]
    );
    return $pdf->stream('cotizacionServicios.pdf');
  }

  public function rollbackCotizacionServicios(Request $request)
  {
    $idCotizacionServicios = $request->get('idCotizacionServicios');

    $versionActual = \DB::table('cotizacionServicios')->select('numCotizacion', 'cotizacionOriginal')->where('idCotizacionServicios', '=', $idCotizacionServicios)->get();
    $versionAnterior = $versionActual[0]->numCotizacion - 1;
    $cotizacionParaActivar = \DB::table('cotizacionServicios')->select('idCotizacionServicios')->where('numCotizacion', '=', $versionAnterior)->where('cotizacionOriginal', '=', $versionActual[0]->cotizacionOriginal)->get();

    if ($versionAnterior > 0) {

      $activandoCotizacionAnterior = \DB::UPDATE("UPDATE cotizacionServicios SET clonActual = 'Si', estatus='En revisión' WHERE idCotizacionServicios = ? ", [$cotizacionParaActivar[0]->idCotizacionServicios]);
      $desactivandoEquiposOriginales = \DB::UPDATE("UPDATE detalleCotizacionServicios SET clonEquiposActivo = 'Si' WHERE idCotizacionServicios = ? ", [$idCotizacionServicios]);
    }

    $borrandoCotiacion = \DB::delete("DELETE FROM cotizacionServicios WHERE idCotizacionServicios=?", [$idCotizacionServicios]);
    $borrandoEquiposOriginales = \DB::delete("DELETE FROM detalleCotizacionServicios WHERE idCotizacionServicios = ? ", [$idCotizacionServicios]);
  }

  public function modificarMontoPartidaCotizacionServicios(Request $request)
  {
    $idCotizacionServicios = $request->get('idCotizacionServicios');
    $idDetalleCotizacionServ = $request->get('idDetalleCotizacionServ');

    $equiposNuevosYreparados = \DB::select("SELECT * FROM detalleCotizacionServicios
    WHERE idCotizacionServicios=? AND idDetalleCotizacionServ=?", [$idCotizacionServicios, $idDetalleCotizacionServ]);

    return view('modificarMontoPartidaCotizacionServicios')
      ->with('datos', $equiposNuevosYreparados)
      ->with('idCotizacionServicios', $idCotizacionServicios)
      ->with('idDetalleCotizacionServ', $idDetalleCotizacionServ);
  }

  public function guardarModificacionMontoPartidaCotizacionServicios(Request $request)
  {
    $idCotizacionServicios = $request->get('idCotizacionServicios');
    $idDetalleCotizacionServ = $request->get('idDetalleCotizacionServ');

    $detallePartidasCotizacion = detalleCotizacionServicios::find($idDetalleCotizacionServ);
    $detallePartidasCotizacion->montoServicioOriginal = $request->montoServicioOriginal;
    $detallePartidasCotizacion->cantidad = $request->cantidad;
    $detallePartidasCotizacion->montoEquipo = $request->montoServicioOriginal;
    if ($request->descuento == '') {
      $detallePartidasCotizacion->montoFinanciamiento = $request->montoEquipo;
    } else {
      $detallePartidasCotizacion->montoFinanciamiento = $request->montoFinanciamiento;
    }
    $detallePartidasCotizacion->semanasEntrega = $request->semanasEntrega;
    $detallePartidasCotizacion->tipoDescuento = $request->tipoDescuento;
    $detallePartidasCotizacion->aplicaDescuento = $request->aplicaDescuento;
    $detallePartidasCotizacion->diasPago = $request->diasDePago;
    $detallePartidasCotizacion->descuento = $request->descuento;
    $detallePartidasCotizacion->notas = $request->notas;
    $detallePartidasCotizacion->save();


    $serviciosEncotizacion = \DB::select("SELECT dcs.idDetalleCotizacionServ,dcs.idCotizacionServicios,
    st.servicioTecnico,dcs.detalle,dcs.observacionCantidad,dcs.numeroCotizacion,dcs.notas,dcs.semanasEntrega,dcs.tipoMoneda,dcs.montoEquipo,dcs.cantidad,
    dcs.montoFinanciamiento,dcs.descuento,dcs.tipoDescuento,dcs.clonEquiposActivo,dcs.agregadoReciente
    FROM detalleCotizacionServicios AS dcs
    LEFT JOIN serviciosTecnicos AS st ON st.idServicioTecnico=dcs.idServicioTecnico
    WHERE idCotizacionServicios = ?", [$idCotizacionServicios]);
    $cuantasServicios = count($serviciosEncotizacion);

    $suma = \DB::select("SELECT FORMAT(SUM(dr.montoFinanciamiento),2) AS sumaTotal
    FROM detalleCotizacionServicios AS dr
    WHERE idCotizacionServicios = ?", [$idCotizacionServicios]);

    $montoTotal = \DB::select("SELECT ROUND(SUM(montoFinanciamiento),2) AS montoTotal FROM detalleCotizacionServicios WHERE idCotizacionServicios = ? GROUP BY idCotizacionServicios", [$idCotizacionServicios]);

    $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizacionServicios SET montoTotal = ? WHERE idCotizacionServicios= ?", [$montoTotal[0]->montoTotal, $idCotizacionServicios]);

    // $estatusCotizacion = \DB::select("SELECT estatus FROM cotizacionServicios WHERE idCotizacionServicios = ?",[$idCotizacionServicios]);

    $cotizacionEditar = \DB::select("SELECT co.idCotizacionServicios,co.idc,co.idu,co.idSucursal,co.numeroCotizacion,co.cotizacionOriginal,co.fechaCotizacion,co.estatus,tipoMoneda,
    CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
    co.noServicios,co.dirigidoA,co.conCopia,
    FORMAT(co.montoTotal,2) AS montoTotal,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus,co.check1,co.textoCheck1,co.check2,co.textoCheck2,co.check3,co.textoCheck3,co.check4,
    co.textoCheck4,co.check5,co.textoCheck5,co.check6,co.textoCheck6,co.check7,co.textoCheck7,co.check8,co.textoCheck8
    FROM cotizacionServicios AS co
    INNER JOIN usuarios AS u ON u.idu= co.idu
    INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
    INNER JOIN clientes AS c ON c.idc= co.idc
    WHERE idCotizacionServicios=?", [$idCotizacionServicios]);

    return view('reporteCotizacionServiciosAbajo')
      ->with('serviciosEncotizacion', $serviciosEncotizacion)
      ->with('numeroCotizacion', $cotizacionEditar[0]->numeroCotizacion)
      ->with('idSucursal', $cotizacionEditar[0]->idSucursal)
      ->with('suma', $suma[0]->sumaTotal)
      ->with('tipoMoneda', $cotizacionEditar[0]->tipoMoneda)
      ->with('cuantasServicios', $cuantasServicios)
      ->with('estatusCotizacion', $cotizacionEditar[0]->estatus);
  }
}
