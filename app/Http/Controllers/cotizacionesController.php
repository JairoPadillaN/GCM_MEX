<?php
//haber q pasa
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\usuarios;
use App\asignacionesdetalles;
use App\facturas;
use App\clientes;
use App\sucursales;
use App\cotizaciones;
use App\recepcionEquipos;
use App\partesreparacion;
use App\detallereparaciones;
use App\serviciostalleres;
use App\serviciosreparacionpartes;
use App\alcances;
use App\partesVenta;
use App\refaccionesreparacionpartes;
use App\refaccionesEnCotizacion;
use App\detallepaquetes;
use App\contactosucursales;

// use App\marcasRefaccion;
// Este nombre lo colocaron mal pero asi se esta usuando 
use App\marcaRefaccion;

use Session;
use Carbon\Carbon;
use DB;
use PDF;
use App\anexGrid;


class cotizacionesController extends Controller{

  public function cotizaciones(){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
        Session::flash('error', 'Es necesario logearse antes de continuar');
        return redirect()->route('login');
    }
    else{

      if ($stipo == 'Administrador') {
        $cliente = \DB::select("SELECT * FROM clientes WHERE tipoCliente='activo' ORDER BY razonSocial ASC");
      }else{
        
        $cliente = DB::table('clientes')
        ->where('tipoCliente','=','activo')        
        ->whereIn('idc',function($query){          
          $sidu = Session::get('sesionidu');
          //Obteniendo usuarios a cargo
          $aCargo = \DB::select("SELECT iduAsignado FROM usuariosacargos WHERE idu =$sidu");
          if (count($aCargo) > 0) {
            for ($i=0; $i < count($aCargo) ; $i++) {
              $usuariosReporte[$i] = $aCargo[$i]->iduAsignado;
            }
            array_push($usuariosReporte,$sidu);
          }else{
            $usuariosReporte[0]=$sidu;
          }
          
          $query->select('idc')
          ->from('sucursales')          
          ->whereIn('idu',$usuariosReporte);
        })
        ->orderBy('razonSocial', 'asc')
        ->get();
      }

      return view ('altaCotizacion')
      ->with('stipo',$stipo)
      ->with('cliente',$cliente);
    }
  }

  function altaCotizacion(Request $request){
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');
    $idc = $request->get('idc');

    //Obteniendo usuarios a cargo
    $aCargo = \DB::select("SELECT iduAsignado FROM usuariosacargos WHERE idu =$sidu");
    if (count($aCargo) > 0) {
      for ($i=0; $i < count($aCargo) ; $i++) {
        $usuariosReporte[$i] = $aCargo[$i]->iduAsignado;
      }
      array_push($usuariosReporte,$sidu);
    }else{
      $usuariosReporte[0]=$sidu;
    }
    
    if ($stipo == 'Administrador') {
      $sucursales = \DB::select("SELECT * FROM sucursales WHERE idc=? AND activo='Si' ORDER BY sucursal ASC",[$idc]);
    }else{

      $sucursales = DB::table('sucursales')
        ->where('activo','=','si')
        ->where('idc','=',$idc)
        ->whereIn('idu',$usuariosReporte)
        ->orderBy('sucursal', 'asc')
      ->get();
      
    }
    // $idSiguiente = \DB::table('cotizaciones')->max('idCotizacion') + 1;

    $equiposCotizados = \DB::select("SELECT dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion,
    dr.numeroCotizacion, dr.semanasEntrega, dr.montoEquipo FROM detallereparaciones AS dr 
    WHERE idEquipos IN (SELECT idEquipos FROM recepcionEquipos WHERE idc=?) AND dr.tipoCotizacion='reparacion' GROUP BY dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion, dr.numeroCotizacion, dr.semanasEntrega, dr.montoEquipo ",[$idc]);
    $cuantosCotizados = count($equiposCotizados);

    $diasDePago = \DB::select("SELECT diasDePago FROM clientes WHERE idc= $idc");

    $comboUsuariosAdmon = usuarios::where('tipo','=','Administrador','AND','activo','=','Si')->orderBy('nombreUsuario', 'ASC')->get();
    $comboUsuariosVendedor = usuarios::where('tipo','=','Vendedor','AND','activo','=','Si')->orderBy('nombreUsuario', 'ASC')->get();

    $comboConCopia= \DB::select("SELECT co.nombreCont,s.sucursal from contactosucursales as co INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal where co.idc=$idc");
    
    return view('altaVersionCotizacion')
    ->with('diasDePago',$diasDePago[0]->diasDePago)
    // ->with('idSiguiente',$idSiguiente)
    ->with('sucursales',$sucursales)
    ->with('comboUsuariosAdmon',$comboUsuariosAdmon)
    ->with('comboUsuariosVendedor',$comboUsuariosVendedor)
    ->with('comboConCopia',$comboConCopia)
    ->with('equiposCotizados',$equiposCotizados)
    ->with('cuantosCotizados',$cuantosCotizados)
    ->with('idc',$idc);
  }

  function reporteCotizacionSucursal(Request $request){
    $idSucursal = $request->get('idSucursal');
    $equiposCotizados = \DB::select("SELECT dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion,
    dr.numeroCotizacion, dr.semanasEntrega, dr.montoEquipo FROM detallereparaciones AS dr WHERE idEquipos IN (SELECT idEquipos FROM recepcionEquipos WHERE idSucursal=?) GROUP BY dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion, dr.numeroCotizacion, dr.semanasEntrega, dr.montoEquipo ",[$idSucursal]);
    $cuantosCotizados = count($equiposCotizados);

    return $idSucursal;
  }

  function generarNumeroCotizacion(Request $request){
    $idSucursal = $request->get('idSucursal');

    $gcmid = \DB::select("SELECT gcmid FROM sucursales WHERE idSucursal = $idSucursal");
    $gcmidNuevo= $gcmid[0]->gcmid;

    // $fechaCotizacion = $request->get('fechaCotizacion');
    // $fechaCotizacion=Carbon::parse($fechaCotizacion);
    // $fechaNueva=$fechaCotizacion->format('dmY');

    if ($idSucursal == 557 || $idSucursal == 556 || $idSucursal == 555) {
      $consecutivoCotizacionBD = DB::table('cotizaciones')->select(DB::raw('MAX(numConsecutivo) as consecutivoDB'))
      ->where('numeroCotizacion','like','PMI%')->get();
    }else{
      $consecutivoCotizacionBD = DB::table('cotizaciones')->select(DB::raw('MAX(numConsecutivo) as consecutivoDB'))
      ->where('idSucursal','=',$idSucursal)->get();
    }
    $consecutivo =$consecutivoCotizacionBD[0]->consecutivoDB;

    if($consecutivo==0){
      $numConsecutivo = 1;
    }
    else{
      $numConsecutivo = $consecutivo + 1;
    }
    $consecutivoCotizacion = str_pad($numConsecutivo,6,"0",STR_PAD_LEFT);

    $cadenaNumeroCotizacionOriginal = $gcmidNuevo.'-'.$consecutivoCotizacion;
    $cadenaNumeroCotizacion = $gcmidNuevo.'-'.$consecutivoCotizacion.'-1';

    $activarEditar='no';
    return view('numeroCotizacion')
    ->with('activarEditar',$activarEditar)
    ->with('cadenaNumeroCotizacion',$cadenaNumeroCotizacion)
    ->with('cadenaNumeroCotizacionOriginal',$cadenaNumeroCotizacionOriginal)
    ->with('numConsecutivo',$numConsecutivo);
  }

  function comboContactosCotizacion(Request $request){
    $idSucursal = $request->get('idSucursal');
    $sucursales = contactosucursales::where('idSucursal','=',$idSucursal)->get();

    return view('comboContacto')
    ->with('consulta',$sucursales);//como estoy usando la vista de ashly, yo mando la variable $sucursales pero en la vista espera la variable consulta por eso mando el alias consulta
  }

  function comboPartes(Request $request){
    $idMarcaRefa = $request->get('idMarcaRefa');
    $tipoCotizacion = $request->get('tipoCotizacion');
    
    if($tipoCotizacion=="nuevo"){      
      $tipoCotizacion ="Nuevo";
      $partes = \DB::select("SELECT idPartesVenta,numeroParte,nombreRefaccion,codigo FROM partesVenta WHERE tipoProducto = ? AND idMarcaRefa =? AND parapaquete = 'No' and activo = 'Si' ORDER BY codigo ASC",[$tipoCotizacion,$idMarcaRefa]);
    }else{
      $partes = \DB::select("SELECT idPartesVenta,numeroParte,nombreRefaccion,codigo FROM partesVenta WHERE tipoProducto != 'Nuevo' AND idMarcaRefa =? AND parapaquete = 'No'  and activo = 'Si' ORDER BY codigo ASC",[$idMarcaRefa]);
    }
    

    return view('comboPartes')
    ->with('partes',$partes);
  }

  function precioParte(Request $request){
    $idPartesVenta = $request->get('idPartesVenta');
    $tipoMoneda = $request->get('tipoMoneda');
    $cambioDolar = $request->get('cambioDolar');
    if ($tipoMoneda == 'MXN') {
      $validandoPrecioParte = \DB::select("SELECT precioVentaPe,precioVentaDol,presentacion FROM partesVenta WHERE idPartesVenta = $idPartesVenta");
      $precioPesos = $validandoPrecioParte[0]->precioVentaPe;
        if ( $precioPesos == "" || $precioPesos == 0 ) {
          $precioParte = $validandoPrecioParte[0]->precioVentaDol * $cambioDolar;
          $presentacion =$validandoPrecioParte[0]->presentacion;
        }else{
          $precioParteC = \DB::select("SELECT precioVentaPe,presentacion FROM partesVenta WHERE idPartesVenta = $idPartesVenta");
          $precioParte = $precioParteC[0]->precioVentaPe;
          $presentacion =$precioParteC[0]->presentacion;
        }
    }else{
      $precioParteC = \DB::select("SELECT precioVentaDol,presentacion FROM partesVenta WHERE idPartesVenta = $idPartesVenta");
      $precioParte = $precioParteC[0]->precioVentaDol;
      $presentacion =$precioParteC[0]->presentacion;
    }

    return view("precioParte")
    ->with('presentacion',$presentacion)
    ->with('precioParte',$precioParte);
  }

  function modeloParte(Request $request){
    $idPartesVenta = $request->get('idPartesVenta');
    $modeloParte = \DB::select("SELECT modelo FROM partesVenta WHERE idPartesVenta = $idPartesVenta");    

    return view("modeloParte")
    ->with('modeloParte',$modeloParte[0]);
  }

  function fechaActualizacionParte(Request $request){
    $idPartesVenta = $request->get('idPartesVenta');
    $fechaActualizacionParte = \DB::select("SELECT fechaActualizacion FROM partesVenta WHERE idPartesVenta = $idPartesVenta");    
    $fechaActual = Carbon::now();
    $fechaActualizacion = Carbon::createFromFormat('Y-m-d', $fechaActualizacionParte[0]->fechaActualizacion);
    $diferenciaDias = $fechaActual->diff($fechaActualizacion)->days;
    return view("fechaActualizacionParte")
        ->with('fechaActualizacionParte', $fechaActualizacionParte[0])
        ->with('fechaActual', $fechaActual->format('Y-m-d'))
        ->with('diferenciaDias', $diferenciaDias);
}

  function descripcionParte(Request $request){
    $idPartesVenta = $request->get('idPartesVenta');
    $descripcionParte = \DB::select("SELECT CONCAT(codigo,' ',nombreRefaccion) AS descripcion FROM partesVenta WHERE idPartesVenta = $idPartesVenta");    

    return view("descripcionParte")
    ->with('descripcionParte',$descripcionParte[0]);
  }

  function notaInternaYskuEquivalente(Request $request){
    $idPartesVenta = $request->get('idPartesVenta');
    $notaInternaYskuEquivalente = \DB::select("SELECT notaInterna,skuEquivalente FROM partesVenta WHERE idPartesVenta = $idPartesVenta");    

    return view("notaInternaYskuEquivalente")
    ->with('notaInternaYskuEquivalente',$notaInternaYskuEquivalente[0]);
  }

  function semanasEntregaParte(Request $request){
    $idPartesVenta = $request->get('idPartesVenta');
    $semanasEntrega = \DB::select("SELECT semanasEntrega FROM partesVenta WHERE idPartesVenta = $idPartesVenta");    

    return view("semanasEntrega")
    ->with('semanasEntrega',$semanasEntrega[0]);
  }

  function comboEquiposCotizacion(Request $request){
    $idSucursal = $request->get('idSucursal');
    $comboEquipos = \DB::select("SELECT re.idEquipos, re.GCMid, re.complementoGCMid,activo, re.idc  
        FROM recepcionEquipos AS  re where
     re.idSucursal = ? ORDER BY re.GCMid,re.complementoGCMid ASC",[$idSucursal]);

    return view('comboEquiposCotizacion')
    ->with('comboEquipos',$comboEquipos);
  }

  public function creandoIdCotizacion(Request $request){

    $numeroCotizacionVista = $request-> numeroCotizacionVista;
    $estatus = $request-> estatus;

    $nombreCliete = \DB::select("SELECT razonSocial FROM clientes WHERE idc=?",[$request-> idc]);
    $nombreSucursal = \DB::select("SELECT sucursal FROM sucursales WHERE idSucursal=?",[$request-> idSucursal]);

    $cotizacion = new cotizaciones;
    $cotizacion-> idu = $request-> idu;
    $cotizacion-> nombreUsuario = $request-> nombreUsuario;
    $cotizacion-> idc = $request-> idc;
    $cotizacion-> fechaCotizacion = $request-> fechaCotizacion;
    $cotizacion-> idSucursal = $request-> idSucursal;
    $cotizacion-> clienteSucursal = $nombreCliete[0]->razonSocial.' / '.$nombreSucursal[0]->sucursal;
    $cotizacion-> numeroCotizacion = $numeroCotizacionVista;
    $cotizacion-> dirigidoA = $request-> dirigidoA;
    $cotizacion-> conCopia = $request-> conCopia;
    $cotizacion-> estatus = $estatus;
    $cotizacion-> archivoCotizacion = 'Sin archivo';
    $cotizacion-> noEquipos = 0;
    $cotizacion-> montoTotal = 0.00;
    $cotizacion-> tipoMoneda = $request-> tipoMoneda;
    $cotizacion-> cambio = $request-> cambio;
    $cotizacion-> cotizacionOriginal = $request-> numeroCotizacion;
    $cotizacion-> numConsecutivo = $request-> numConsecutivo;
    $cotizacion-> numCotizacion = 1;
    if ($estatus == "Cancelada") {
      $cotizacion-> clonActual = 'No';
    }else{
      $cotizacion-> clonActual = 'Si';
    }
    $cotizacion-> activo='Si';
    $cotizacion-> check1 = $request-> check1;
    $cotizacion-> textoCheck1 = $request-> textoCheck1;
    $cotizacion-> check2 = $request-> check2;
    $cotizacion-> textoCheck2 = $request-> textoCheck2;
    $cotizacion-> check3 = $request-> check3;
    $cotizacion-> textoCheck3 = $request-> textoCheck3;
    $cotizacion-> check4 = $request-> check4;
    $cotizacion-> textoCheck4 = $request-> textoCheck4;
    $cotizacion-> check5 = $request-> check5;
    $cotizacion-> textoCheck5 = $request-> textoCheck5;
    $cotizacion-> check6 = $request-> check6;
    $cotizacion-> textoCheck6 = $request-> textoCheck6;
    $cotizacion-> check7 = $request-> check7;
    $cotizacion-> textoCheck7 = $request-> textoCheck7;
    $cotizacion-> check8 = $request-> check8;
    $cotizacion-> textoCheck8 = $request-> textoCheck8;
    $cotizacion-> save();
    
    $cuantosidCotizacion= \DB::select("SELECT idCotizacion FROM cotizaciones WHERE numeroCotizacion=?",[$numeroCotizacionVista]);
    $idCotizacionBorrar= \DB::select("SELECT MAX(idCotizacion) as idCotizacion FROM cotizaciones WHERE numeroCotizacion=?",[$numeroCotizacionVista]);
        
    $idCotizacionCreada = $idCotizacionBorrar[0]->idCotizacion;
    
    if (count($cuantosidCotizacion)>1) {
      $borrarCotizacion= \DB::delete("DELETE FROM cotizaciones WHERE idCotizacion=?",[$idCotizacionBorrar[0]->idCotizacion]);
      $idCotizacionCreada = 0;
    }

    return view('idCotizacion')
    ->with('idCotizacion',$idCotizacionCreada);

  }
  
  public function validarCotizacionesErroneas(Request $request){

    $numeroCotizacion = $request->get('numeroCotizacionVista');
    
    $numRepetido = \DB::select("SELECT idCotizacion FROM cotizaciones WHERE numeroCotizacion=?",[$numeroCotizacion]);
    $haynumRepetido = count($numRepetido);
    
    return '<div class="alert alert-warning" role="alert">Ya existe</div>
            <button type="button" class="btn btn-success btn-sm" id="nuevaVersion">
              <span class="glyphicon glyphicon-plus-sign"></span> Agregar equipos
            </button>
            <button type="button" class="btn btn-danger btn-sm cancelarAlta">
                Cancelar cotización
            </button>'
    ;


  }

  public function guardarCotizacion(Request $request){
    $numeroCotizacionVista = $request-> numeroCotizacionVista;
    $idCotizacion = $request-> idCotizacion;
    $estatus = $request-> estatus;
    
    $idc = $request-> idc;
    $empresa= \DB::select("SELECT empresaPertenece FROM clientes WHERE idc = ?",[$idc]);

    $file = $request->file('archivoCotizacion');
    if($file!=""){
      $ldate = date('Ymd_His_');
      $img = $file->getClientOriginalName();
      $img2 = $ldate.$img;
      \Storage::disk('local')->put($img2, \File::get($file));
    }
    else{
      $img2 ="Sin archivo";
    }

    $cotizacion = cotizaciones::find($idCotizacion);
    $cotizacion-> idCotizacion = $idCotizacion;
    $cotizacion-> idu = $request-> idu;
    $cotizacion-> idc = $request-> idc;
    $cotizacion-> fechaCotizacion = $request-> fechaCotizacion;
    $cotizacion-> idSucursal = $request-> idSucursal;
    $cotizacion-> numeroCotizacion = $request-> numeroCotizacionVista;
    $cotizacion-> dirigidoA = $request-> dirigidoA;
    $cotizacion-> conCopia = $request-> conCopia;
    $cotizacion-> estatus = $estatus;
    $cotizacion-> archivoCotizacion = $img2;
    // $cotizacion-> montoTotal = ROUND($request-> montoTotal,2);
    $cotizacion-> tipoMoneda = $request-> tipoMoneda;
    $cotizacion-> cambio = $request-> cambio;
    $cotizacion-> cotizacionOriginal = $request-> numeroCotizacion;
    $cotizacion-> numConsecutivo = $request-> numConsecutivo;
    $cotizacion-> numCotizacion = 1;
    if ($estatus == "Cancelada") {
      $cotizacion-> clonActual = 'No';
    }else{
      $cotizacion-> clonActual = 'Si';
    }
    $cotizacion-> activo='Si';
    $cotizacion-> check1 = $request-> check1;
    $cotizacion-> textoCheck1 = $request-> textoCheck1;
    $cotizacion-> check2 = $request-> check2;
    $cotizacion-> textoCheck2 = $request-> textoCheck2;
    $cotizacion-> check3 = $request-> check3;
    $cotizacion-> textoCheck3 = $request-> textoCheck3;
    $cotizacion-> check4 = $request-> check4;
    $cotizacion-> textoCheck4 = $request-> textoCheck4;
    $cotizacion-> check5 = $request-> check5;
    $cotizacion-> textoCheck5 = $request-> textoCheck5;
    $cotizacion-> check6 = $request-> check6;
    $cotizacion-> textoCheck6 = $request-> textoCheck6;
    $cotizacion-> check7 = $request-> check7;
    $cotizacion-> textoCheck7 = $request-> textoCheck7;
    $cotizacion-> check8 = $request-> check8;
    $cotizacion-> textoCheck8 = $request-> textoCheck8;
    $cotizacion-> save();

    // $idSiguiente = \DB::table('cotizaciones')->max('idCotizacion');//estas dos lineas corrigen el idCotizacion en detllereparaciones para relacionarlas correctamente
    // $corrigiendoidCotizacionenTablaDetalle = \DB::UPDATE("UPDATE detallereparaciones SET idCotizacion = ? WHERE numeroCotizacion= ?",[$idSiguiente,$numeroCotizacionVista]);

    $montoTotal= \DB::select("SELECT idCotizacion, ROUND(SUM(montoFinanciamiento),2) AS montoTotal FROM detallereparaciones WHERE idCotizacion = ? GROUP BY idCotizacion",[$idCotizacion]);
    $noEquipos = \DB::select("SELECT idCotizacion, COUNT(idCotizacion) AS noEquipos FROM detallereparaciones WHERE idCotizacion = ? GROUP BY idCotizacion",[$idCotizacion]);

    // se agregó 'AND idEquipos IS NOT NULL' ya que contaba los id nullos y con esa condicion ya no toma los idEquipo nullos en el conteo para determinar que un idequipo esta repetido
    $repetidos= \DB::select("SELECT idEquipos,tipoCotizacion FROM detallereparaciones WHERE idCotizacion = ? AND idEquipos IS NOT NULL GROUP BY idEquipos,tipoCotizacion HAVING COUNT(*)>1;",[$idCotizacion]);
    $cuantosRepetidos = count($repetidos);
    if($cuantosRepetidos > 0){      
      $siRepetidos = \DB::UPDATE("UPDATE cotizaciones SET repetidos = 'si' WHERE idCotizacion= ?",[$idCotizacion]);
    // $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizaciones SET montoTotal = '0', noEquipos=? WHERE idCotizacion= ?",[$noEquipos[0]->noEquipos,$idCotizacion]);
    $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizaciones SET montoTotal = ?, noEquipos=? WHERE idCotizacion= ?",[$montoTotal[0]->montoTotal,$noEquipos[0]->noEquipos,$idCotizacion]);
    }else{
      $siRepetidos = \DB::UPDATE("UPDATE cotizaciones SET repetidos = 'no' WHERE idCotizacion= ?",[$idCotizacion]);
      $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizaciones SET montoTotal = ?, noEquipos=? WHERE idCotizacion= ?",[$montoTotal[0]->montoTotal,$noEquipos[0]->noEquipos,$idCotizacion]);
    }

    // return redirect()->route('cotizaciones');//retorna al metodo o ruta cotizaciones

    if ($estatus == "Cancelada") {      
      $refaccionesExistentes =DB::table('refaccionesEnCotizacion')
      ->select('refaccionesEnCotizacion.idParte')
      ->where('refaccionesEnCotizacion.idCotizacion', '=',$idCotizacion)
      ->groupBy('refaccionesEnCotizacion.idParte')
      ->get();
      foreach($refaccionesExistentes as $rE){
        $borrarRefacciones= \DB::delete("DELETE FROM refaccionesreparacionpartes WHERE idParte=?",[$rE->idParte]);
      }

    }
        
    //LIMPIANDO REFACCIONES EN LAS PRATES DE EQUIPOS POR SI EL ESTATUS AUN NO ES ACEPTADO CON ORDEN DE COMPRA
    $refaccionesExistentes =DB::table('refaccionesEnCotizacion')
    ->select('refaccionesEnCotizacion.idParte')
    ->where('refaccionesEnCotizacion.idCotizacion', '=',$idCotizacion)
    ->groupBy('refaccionesEnCotizacion.idParte')
    ->get();
    foreach($refaccionesExistentes as $rE){
      $borrandoRefacciones= \DB::delete("DELETE FROM refaccionesreparacionpartes WHERE idParte=?",[$rE->idParte]);
    }
    
    // Creando servicio en tabla facturas
    if ($estatus == "Aceptada con orden de compra") {

      $refaccionesExistentes =DB::table('refaccionesEnCotizacion')
      ->select('refaccionesEnCotizacion.idParte')
      ->where('refaccionesEnCotizacion.idCotizacion', '=',$idCotizacion)
      ->groupBy('refaccionesEnCotizacion.idParte')
      ->get();
      foreach($refaccionesExistentes as $rE){
        $borrarRefacciones= \DB::delete("DELETE FROM refaccionesreparacionpartes WHERE idParte=?",[$rE->idParte]);
      }
            
      $guardarRefacciones = refaccionesEnCotizacion::where('idCotizacion','=',$idCotizacion)->get();      
      
      foreach($guardarRefacciones as $gR)
        {refaccionesreparacionpartes::create([      
          'idParte' => $gR-> idParte,
          'idTipoRefacciones' => $gR-> idTipoRefacciones,
          'idPaquete' => $gR-> idPaquete,
          'idPartesVenta' => $gR-> idPartesVenta,
          'tipoProducto' => $gR-> tipoProducto,
          'precioPesos' => $gR-> precioPesos,
          'precioDolar' => $gR-> precioDolar,
          'apareceEnCoti' => $gR-> apareceEnCoti
        ]);
      }

      $idServiciosig = facturas::withTrashed()->orderby('idServicios','desc')->take(1)->get();
      $idServ = $idServiciosig[0]->idServicios+1;
      $idc = $request-> idc;
      $diasDePago = \DB::select("SELECT diasDePago FROM clientes WHERE idc= $idc");

      $file = $request->file('archivo');
      if($file!=""){
        $archivo =  time()."_".$file->getClientOriginalName();
        \Storage::disk('local')->put($archivo,  \File::get($file));
      }else{
        $archivo ="Sin archivo";
      }

      $fac=new facturas;
      $fac-> idServicios = $idServ;

      // opciones que anterior mente estaban antes del conflicto con el idServicios 217 13/05/2022
      // $fac-> tipoServicio = '$empresa[0]->empresaPertenece';
      // $fac-> nombreEmpresa = "C&M GROUP";
      
      // opciones que sustituyeron a las anteriores por del conflicto con el idServicios 217 13/05/2022
      $fac-> tipoServicio = "Reparaciones";
      $fac-> nombreEmpresa = $empresa[0]->empresaPertenece;
      
      // $fac-> fechaVencimiento = "0000-00-00";
      // $fac-> idb = $request-> idb;
      // if($cuantosRepetidos > 0){
      //   $fac-> montoFactura = "0";
      // }else{
        $fac-> montoFactura = $montoTotal[0]->montoTotal;
      // }
      $fac-> periodoPago = $diasDePago[0]->diasDePago;
      $fac-> numeroFactura = "-";
      // $fac-> fechaFactura = "0000-00-00";
      // $fac-> fechaPago = "0000-00-00";
      $fac-> tipoMoneda = $request-> tipoMoneda;
      $fac-> estatusEntrega = "Pendiente";
      $fac-> estatusPago = "Pendiente";
      $fac-> metodoPago = "PUE";
      $fac-> estatusPortal = "Pendiente en subir";
      $fac-> cashFlow = "No";
      $fac-> idc = $idc;
      $fac-> idSucursal = $request-> idSucursal;
      $fac-> idCotizacion = $idCotizacion;
      $fac-> ordenCompra = $request-> ordenCompra;
      $fac-> archivo = $archivo;
      $fac-> archivoFactura = "Sin archivo";
      $fac-> archivoPago = "Sin archivo";
      $fac-> xmlFactura = "Sin archivo";
      $fac-> archivoRemision = "Sin archivo";
      $fac-> archivoAdenda = "Sin archivo";
      $fac-> save();

      $idFactura = \DB::select("SELECT idFactura FROM facturas WHERE tipoServicio !='Soporte Técnico' AND idCotizacion= $idCotizacion");
      $asigDetalles = new asignacionesdetalles;
      $asigDetalles-> idFactura = $idFactura[0]->idFactura;
      $asigDetalles-> fecha = $request-> fechaCotizacion;
      $asigDetalles-> idu = $request-> idu;
      $asigDetalles->save();

      $cambiarEstatus = \DB::select("SELECT idEquipos FROM detallereparaciones WHERE idCotizacion = ? ",[$idCotizacion]);
      $cuantasEquipos = count($cambiarEstatus);

      for ($i=0; $i < $cuantasEquipos; $i++) {
        $cambiandoEstatusEquipos = \DB::UPDATE("UPDATE recepcionEquipos SET estatusEntrega='Con OC' WHERE idEquipos= ?",[$cambiarEstatus[$i]->idEquipos]);
      }
    }

    $proceso='Alta de cotizaciones';
    $mensaje="Cotización creada correctamente";

    return view ('mensajeCotizaciones')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);

  }

  public function reporteVersionesCotizacion(Request $request){
    $idc = $request-> idc;
    $fechaInicio = $request-> fechaInicio;
    $fechaFin = $request-> fechaFin;

    if ($fechaInicio == ''){$fechaInicio="0000-00-00";}
    if ($fechaFin == ''){$fechaFin="9999-99-99";}

    return view ('reporteVersiones')
    ->with('fechaInicio',$fechaInicio)
    ->with('fechaFin',$fechaFin)
    ->with('idc',$idc);
    // if ($idc == "todos") {
    //   $consulta = \DB::select("SELECT co.idCotizacion, co.numeroCotizacion, co.fechaCotizacion,co.clonActual,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
    //   co.noEquipos,CONCAT('$',' ',co.tipoMoneda,' ',co.montoTotal) AS montoMoneda,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus
    //   FROM cotizaciones AS co
    //   INNER JOIN usuarios AS u ON u.idu= co.idu
    //   INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
    //   INNER JOIN clientes AS c ON c.idc= co.idc
    //   WHERE fechaCotizacion>=? AND fechaCotizacion<=?
    //   ORDER BY numeroCotizacion",[$fechaInicio,$fechaFin]);
    // }else{
    //   $consulta = \DB::select("SELECT co.idCotizacion, co.numeroCotizacion, co.fechaCotizacion,co.clonActual,
    //   CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
    //   co.noEquipos,
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

  public function anexgridVersionesCotizacionConFiltros(Request $request){

    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $spat = Session::get('sesionpaterno');
    $smat = Session::get('sesionmaterno');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }else{
      
      // Obteniendo usuarios a cargo de un admin
      $aCargo = \DB::select("SELECT iduAsignado FROM usuariosacargos WHERE idu =$sidu");
      if (count($aCargo) > 0) {
        for ($i=0; $i < count($aCargo) ; $i++) {
          $usuariosReporte[$i] = $aCargo[$i]->iduAsignado;
        }
        array_push($usuariosReporte,$sidu);
      }else{
        $usuariosReporte[0]=$sidu;
      }

      $idc = $request-> idc;
      $fechaInicio = $request-> fechaInicio;
      $fechaFin = $request-> fechaFin;

      // if ($fechaInicio == ''){$fechaInicio="0000-00-00";}
      // if ($fechaFin == ''){$fechaFin="9999-99-99";}

      $anexGrid = new anexGrid();
      $clienteSucursal = '';
      $numeroCotizacion = '';
      $nombreUsuario= '';
      $estatus= '';

      foreach($anexGrid->filtros as $f){
        if($f['columna'] == 'numeroCotizacion' && $f['valor'] != ''){
          $numeroCotizacion = $f['valor'];
        }
        if($f['columna'] == 'numeroCotizacion' && $f['valor'] == ''){
          $numeroCotizacion = '';
        }

        if($f['columna'] == 'nombreUsuario' && $f['valor'] != ''){
          $nombreUsuario = $f['valor'];
        }
        if($f['columna'] == 'nombreUsuario' && $f['valor'] == ''){
          $nombreUsuario = '';
        }

        if($f['columna'] == 'estatus' && $f['valor'] != ''){
          $estatus = $f['valor'];
        }
        if($f['columna'] == 'estatus' && $f['valor'] == ''){
          $estatus = '';
        }

        if($f['columna'] == 'clienteSucursal' && $f['valor'] != ''){
          $clienteSucursal = $f['valor'];
        }
        if($f['columna'] == 'clienteSucursal' && $f['valor'] == ''){
          $clienteSucursal = '';
        }
      }

      $order;
      $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

      if ($stipo == 'Administrador') {

        if ($idc == "todos") {
          $registros = DB::table('cotizaciones')
            ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
            ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
            ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
            ->where('estatus', 'like', '%'.$estatus.'%')
            ->where('estatus', '!=','Cancelada')
            ->where('fechaCotizacion','>=',$fechaInicio)
            ->where('fechaCotizacion','<=',$fechaFin)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
          ->get();

          $total = DB::table('cotizaciones')
            ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
            ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
            ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
            ->where('estatus', 'like', '%'.$estatus.'%')
            ->where('estatus', '!=','Cancelada')
            ->where('fechaCotizacion','>=',$fechaInicio)
            ->where('fechaCotizacion','<=',$fechaFin)
          ->count();

        }else{

          $registros = DB::table('cotizaciones')
            ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
            ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
            ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
            ->where('estatus', 'like', '%'.$estatus.'%')
            ->where('estatus', '!=','Cancelada')
            ->where('fechaCotizacion','>=',$fechaInicio)
            ->where('fechaCotizacion','<=',$fechaFin)
            ->where('idc','=',$idc)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
          ->get();

          $total = DB::table('cotizaciones')
            ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
            ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
            ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
            ->where('estatus', 'like', '%'.$estatus.'%')
            ->where('estatus', '!=','Cancelada')
            ->where('fechaCotizacion','>=',$fechaInicio)
            ->where('fechaCotizacion','<=',$fechaFin)
            ->where('idc','=',$idc)
          ->count();
        }
      }else{
        if ($idc == "todos") {
          $registros = DB::table('cotizaciones')
            ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
            ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
            ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
            ->where('estatus', 'like', '%'.$estatus.'%')
            ->where('estatus', '!=','Cancelada')
            ->where('fechaCotizacion','>=',$fechaInicio)
            ->where('fechaCotizacion','<=',$fechaFin)
            ->whereIn('cotizaciones.idu',$usuariosReporte)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
          ->get();

          $total = DB::table('cotizaciones')
            ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
            ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
            ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
            ->where('estatus', 'like', '%'.$estatus.'%')
            ->where('estatus', '!=','Cancelada')
            ->where('fechaCotizacion','>=',$fechaInicio)
            ->where('fechaCotizacion','<=',$fechaFin)
            ->whereIn('cotizaciones.idu',$usuariosReporte)
          ->count();

        }else{

          $registros = DB::table('cotizaciones')
            ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
            ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
            ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
            ->where('estatus', 'like', '%'.$estatus.'%')
            ->where('estatus', '!=','Cancelada')
            ->where('fechaCotizacion','>=',$fechaInicio)
            ->where('fechaCotizacion','<=',$fechaFin)
            ->where('idc','=',$idc)
            ->whereIn('cotizaciones.idu',$usuariosReporte)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
          ->get();

          $total = DB::table('cotizaciones')
            ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
            ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
            ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
            ->where('estatus', 'like', '%'.$estatus.'%')
            ->where('estatus', '!=','Cancelada')
            ->where('fechaCotizacion','>=',$fechaInicio)
            ->where('fechaCotizacion','<=',$fechaFin)
            ->where('idc','=',$idc)
            ->whereIn('cotizaciones.idu',$usuariosReporte)

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

  public function anexgridVersionesCanceladasConFiltros(Request $request){

    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $spat = Session::get('sesionpaterno');
    $smat = Session::get('sesionmaterno');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }else{

      // Obteniendo usuarios a cargo de un admin
      $aCargo = \DB::select("SELECT iduAsignado FROM usuariosacargos WHERE idu =$sidu");
      if (count($aCargo) > 0) {
        for ($i=0; $i < count($aCargo) ; $i++) {
          $usuariosReporte[$i] = $aCargo[$i]->iduAsignado;
        }
        array_push($usuariosReporte,$sidu);
      }else{
        $usuariosReporte[0]=$sidu;
      }

      $idc = $request-> idc;
      $fechaInicio = $request-> fechaInicio;
      $fechaFin = $request-> fechaFin;

      // if ($fechaInicio == ''){$fechaInicio="0000-00-00";}
      // if ($fechaFin == ''){$fechaFin="9999-99-99";}

      $anexGrid = new anexGrid();
      $clienteSucursal = '';
      $numeroCotizacion = '';
      $nombreUsuario= '';
      $estatus= '';

      foreach($anexGrid->filtros as $f){
        if($f['columna'] == 'numeroCotizacion' && $f['valor'] != ''){
          $numeroCotizacion = $f['valor'];
        }
        if($f['columna'] == 'numeroCotizacion' && $f['valor'] == ''){
          $numeroCotizacion = '';
        }

        if($f['columna'] == 'nombreUsuario' && $f['valor'] != ''){
          $nombreUsuario = $f['valor'];
        }
        if($f['columna'] == 'nombreUsuario' && $f['valor'] == ''){
          $nombreUsuario = '';
        }

        if($f['columna'] == 'estatus' && $f['valor'] != ''){
          $estatus = $f['valor'];
        }
        if($f['columna'] == 'estatus' && $f['valor'] == ''){
          $estatus = '';
        }

        if($f['columna'] == 'clienteSucursal' && $f['valor'] != ''){
          $clienteSucursal = $f['valor'];
        }
        if($f['columna'] == 'clienteSucursal' && $f['valor'] == ''){
          $clienteSucursal = '';
        }
      }

      $order;
      $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

      if ($stipo == 'Administrador') {

        if ($idc == "todos") {
          $registros = DB::table('cotizaciones')
            ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
            ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
            ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
            ->where('estatus', '=','Cancelada')
            ->where('fechaCotizacion','>=',$fechaInicio)
            ->where('fechaCotizacion','<=',$fechaFin)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
          ->get();

          $total = DB::table('cotizaciones')
            ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
            ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
            ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
            ->where('estatus', '=','Cancelada')
            ->where('fechaCotizacion','>=',$fechaInicio)
            ->where('fechaCotizacion','<=',$fechaFin)
          ->count();

        }else{

          $registros = DB::table('cotizaciones')
            ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
            ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
            ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
            ->where('estatus', '=','Cancelada')
            ->where('fechaCotizacion','>=',$fechaInicio)
            ->where('fechaCotizacion','<=',$fechaFin)
            ->where('idc','=',$idc)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
          ->get();

          $total = DB::table('cotizaciones')
            ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
            ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
            ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
            ->where('estatus', '=','Cancelada')
            ->where('fechaCotizacion','>=',$fechaInicio)
            ->where('fechaCotizacion','<=',$fechaFin)
            ->where('idc','=',$idc)
          ->count();
        }

      }else{

        if ($idc == "todos") {
          $registros = DB::table('cotizaciones')
            ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
            ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
            ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
            ->where('estatus', '=','Cancelada')
            ->where('fechaCotizacion','>=',$fechaInicio)
            ->where('fechaCotizacion','<=',$fechaFin)
            ->whereIn('cotizaciones.idu',$usuariosReporte)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
          ->get();

          $total = DB::table('cotizaciones')
            ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
            ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
            ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
            ->where('estatus', '=','Cancelada')
            ->where('fechaCotizacion','>=',$fechaInicio)
            ->where('fechaCotizacion','<=',$fechaFin)
            ->whereIn('cotizaciones.idu',$usuariosReporte)
          ->count();

        }else{

          $registros = DB::table('cotizaciones')
            ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
            ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
            ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
            ->where('estatus', '=','Cancelada')
            ->where('fechaCotizacion','>=',$fechaInicio)
            ->where('fechaCotizacion','<=',$fechaFin)
            ->where('idc','=',$idc)
            ->whereIn('cotizaciones.idu',$usuariosReporte)
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
          ->get();

          $total = DB::table('cotizaciones')
            ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
            DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
            DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
            ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
            ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
            ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
            ->where('estatus', '=','Cancelada')
            ->where('fechaCotizacion','>=',$fechaInicio)
            ->where('fechaCotizacion','<=',$fechaFin)
            ->where('idc','=',$idc)
            ->whereIn('cotizaciones.idu',$usuariosReporte)
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

  public function anexgridVersionesCotizacion(Request $request){

    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $spat = Session::get('sesionpaterno');
    $smat = Session::get('sesionmaterno');
    $stipo = Session::get('sesiontipo');

    // Obteniendo usuarios a cargo de un admin
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
    $clienteSucursal = '';
    $numeroCotizacion = '';
    $nombreUsuario= '';      
    $estatus= '';

    foreach($anexGrid->filtros as $f){
      if($f['columna'] == 'numeroCotizacion' && $f['valor'] != ''){
        $numeroCotizacion = $f['valor'];
      }
      if($f['columna'] == 'numeroCotizacion' && $f['valor'] == ''){
        $numeroCotizacion = '';
      }
      
      if($f['columna'] == 'nombreUsuario' && $f['valor'] != ''){
        $nombreUsuario = $f['valor'];
      }
      if($f['columna'] == 'nombreUsuario' && $f['valor'] == ''){
        $nombreUsuario = '';
      }

      if($f['columna'] == 'estatus' && $f['valor'] != ''){
        $estatus = $f['valor'];
      }
      if($f['columna'] == 'estatus' && $f['valor'] == ''){
        $estatus = '';
      }

      if($f['columna'] == 'clienteSucursal' && $f['valor'] != ''){
        $clienteSucursal = $f['valor'];
      }
      if($f['columna'] == 'clienteSucursal' && $f['valor'] == ''){
        $clienteSucursal = '';
      }
    }

    $order;
    $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

    if ($stipo == 'Administrador') {

      $registros = DB::table('cotizaciones')
        ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
        DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
        DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
        ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
        ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
        ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
        ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')          
        ->where('estatus', 'like', '%'.$estatus.'%')
        ->where('estatus', '!=','Cancelada')
        ->orderBy($anexGrid->columna, $order)
        ->skip($anexGrid->pagina)
        ->take($anexGrid->limite)
      ->get();

      $total = DB::table('cotizaciones')
        ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
        DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
        DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
        ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
        ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
        ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
        ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')          
        ->where('estatus', 'like', '%'.$estatus.'%')
        ->where('estatus', '!=','Cancelada')
      ->count();

    }else{

      $registros = DB::table('cotizaciones')
        ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
        DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
        DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
        ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
        ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
        ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
        ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
        ->where('estatus', 'like', '%'.$estatus.'%')
        ->where('estatus', '!=','Cancelada')
        ->whereIn('cotizaciones.idu',$usuariosReporte)
        ->orderBy($anexGrid->columna, $order)
        ->skip($anexGrid->pagina)
        ->take($anexGrid->limite)
      ->get();

      $total = DB::table('cotizaciones')
        ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
        DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
        DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
        ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
        ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
        ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
        ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
        ->where('estatus', 'like', '%'.$estatus.'%')
        ->where('estatus', '!=','Cancelada')
        ->whereIn('cotizaciones.idu',$usuariosReporte)
      ->count();
    }

    $data = array(
      'total' => $total,
      'data' => $registros
    );

    return response()->json($data, 200);
  
  }

  public function anexgridVersionesCanceladas(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $spat = Session::get('sesionpaterno');
    $smat = Session::get('sesionmaterno');
    $stipo = Session::get('sesiontipo');

    // Obteniendo usuarios a cargo de un admin
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
    $clienteSucursal = '';
    $numeroCotizacion = '';
    $nombreUsuario= '';
    $estatus= '';

    foreach($anexGrid->filtros as $f){
      if($f['columna'] == 'numeroCotizacion' && $f['valor'] != ''){
        $numeroCotizacion = $f['valor'];
      }
      if($f['columna'] == 'numeroCotizacion' && $f['valor'] == ''){
        $numeroCotizacion = '';
      }

      if($f['columna'] == 'nombreUsuario' && $f['valor'] != ''){
        $nombreUsuario = $f['valor'];
      }
      if($f['columna'] == 'nombreUsuario' && $f['valor'] == ''){
        $nombreUsuario = '';
      }

      if($f['columna'] == 'estatus' && $f['valor'] != ''){
        $estatus = $f['valor'];
      }
      if($f['columna'] == 'estatus' && $f['valor'] == ''){
        $estatus = '';
      }

      if($f['columna'] == 'clienteSucursal' && $f['valor'] != ''){
        $clienteSucursal = $f['valor'];
      }
      if($f['columna'] == 'clienteSucursal' && $f['valor'] == ''){
        $clienteSucursal = '';
      }
    }

    $order;
    $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

    if ($stipo == 'Administrador') {

      $registros = DB::table('cotizaciones')
        ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
        DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
        DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
        ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
        ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
        ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
        ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
        ->where('estatus', '=','Cancelada')
        ->orderBy($anexGrid->columna, $order)
        ->skip($anexGrid->pagina)
        ->take($anexGrid->limite)
      ->get();

      $total = DB::table('cotizaciones')
        ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
        DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
        DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
        ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
        ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
        ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
        ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
        ->where('estatus', '=','Cancelada')
      ->count();

    }else{

      $registros = DB::table('cotizaciones')
        ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
        DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
        DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
        ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
        ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
        ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
        ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
        ->where('estatus', '=','Cancelada')
        ->whereIn('cotizaciones.idu',$usuariosReporte)
        ->orderBy($anexGrid->columna, $order)
        ->skip($anexGrid->pagina)
        ->take($anexGrid->limite)
      ->get();

      $total = DB::table('cotizaciones')
        ->select('idCotizacion','numeroCotizacion','fechaCotizacion','clonActual','noEquipos','estatus','cotizaciones.created_at','cotizaciones.nombreUsuario','repetidos','numCotizacion','clienteSucursal',
        DB::raw('CONCAT(usuarios.nombreUsuario," ", usuarios.aPaterno," ", usuarios.aMaterno) AS usuario'),
        DB::raw('CONCAT(tipoMoneda," ","$",FORMAT(montoTotal,2)) AS montoMoneda'))
        ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
        ->where('numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
        ->where('clienteSucursal', 'like', '%'.$clienteSucursal.'%')
        ->where('cotizaciones.nombreUsuario', 'like', '%'.$nombreUsuario.'%')
        ->where('estatus', '=','Cancelada')
        ->whereIn('cotizaciones.idu',$usuariosReporte)
      ->count();

    }

    $data = array(
      'total' => $total,
      'data' => $registros
    );

    return response()->json($data, 200);
  
  }

  public function actualizarCotizacion(Request $request){
    $idCotizacion = $request->get('idCotizacion');

    $consultaCotizacion = cotizaciones::find($idCotizacion);
    $clonCotizacion = $consultaCotizacion->replicate();
    $clonCotizacion->save();

    $consultaCotizacionEquipos = detallereparaciones::where('idCotizacion','=',$idCotizacion)->get();
    $asignacionesOriginales = \DB::select("SELECT MAX(created_at) AS asignacionOriginal FROM detallereparaciones WHERE idCotizacion=?",[$idCotizacion]);
    for ($i=0; $i < count($consultaCotizacionEquipos) ; $i++) {
      $clonCotizacion = $consultaCotizacionEquipos[$i]->replicate();
      $clonCotizacion->save();
    }

    $consultaCotizacionRefacciones = refaccionesEnCotizacion::where('idCotizacion','=',$idCotizacion)->get();
    $asignacionesOriginalesRefaccion = \DB::select("SELECT MAX(created_at) AS asignacionOriginal FROM refaccionesEnCotizacion WHERE idCotizacion=?",[$idCotizacion]);
    for ($i=0; $i < count($consultaCotizacionRefacciones) ; $i++) {
      $clonCotizacionRefaccion = $consultaCotizacionRefacciones[$i]->replicate();
      $clonCotizacionRefaccion->save();
    }

    $desactivandoOriginal = \DB::UPDATE("UPDATE cotizaciones SET clonActual = 'No', estatus='Cancelada' WHERE idCotizacion = ? ",[$idCotizacion]);

    $numeroCotizacion=\DB::select("SELECT numeroCotizacion,cotizacionOriginal FROM cotizaciones WHERE idCotizacion=?",[$idCotizacion]);

    $cotizacionEditar = \DB::select("SELECT co.idCotizacion,co.idc,co.idu,co.idSucursal,co.numeroCotizacion,co.cotizacionOriginal,co.fechaCotizacion,co.estatus,tipoMoneda,
    CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
    co.noEquipos,co.dirigidoA,co.conCopia,
    co.montoTotal,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus,co.check1,co.textoCheck1,co.check2,co.textoCheck2,co.check3,co.textoCheck3,co.check4,
		co.textoCheck4,co.check5,co.textoCheck5,co.check6,co.textoCheck6,co.check7,co.textoCheck7,co.check8,co.textoCheck8
    FROM cotizaciones AS co
    INNER JOIN usuarios AS u ON u.idu= co.idu
    INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
    INNER JOIN clientes AS c ON c.idc= co.idc
    WHERE idCotizacion=(SELECT MAX(idCotizacion) AS idCotizacionEditar FROM cotizaciones WHERE numeroCotizacion =?)",[$numeroCotizacion[0]->numeroCotizacion]);

    $idc=$cotizacionEditar[0]->idc;
    $comboUsuariosAdmon = usuarios::where('tipo','=','Administrador','AND','activo','=','Si')->orderBy('nombreUsuario', 'ASC')->get();
    $comboUsuariosVendedor = usuarios::where('tipo','=','Vendedor','AND','activo','=','Si')->orderBy('nombreUsuario', 'ASC')->get();
    $comboConCopia= \DB::select("SELECT co.nombreCont,s.sucursal from contactosucursales as co INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal where co.idc=$idc");

    $fotoCotizacionEditar = \DB::select("SELECT archivoCotizacion FROM cotizaciones WHERE idCotizacion=(SELECT MAX(idCotizacion) AS idCotizacionEditar FROM cotizaciones WHERE numeroCotizacion =?)",[$numeroCotizacion[0]->numeroCotizacion]);

    $idCotizacionClon = \DB::select("SELECT MAX(idCotizacion) AS idCotizacionClon FROM cotizaciones WHERE numeroCotizacion =?",[$numeroCotizacion[0]->numeroCotizacion]);
    $consecutivoVersion = \DB::select("SELECT MAX(numCotizacion)+1 AS consecutivo FROM cotizaciones WHERE cotizacionOriginal=?",[$numeroCotizacion[0]->cotizacionOriginal]);
    $consecutivoVersionCorregido = \DB::UPDATE("UPDATE cotizaciones  SET numCotizacion = ? WHERE idCotizacion = ?",[$consecutivoVersion[0]->consecutivo,$idCotizacionClon[0]->idCotizacionClon]);


    $clonNumeroCotizacion = $cotizacionEditar[0]->cotizacionOriginal.'-'.$consecutivoVersion[0]->consecutivo;

    $numeroCotizacionVersionNEW = \DB::UPDATE("UPDATE cotizaciones  SET numeroCotizacion = ? WHERE idCotizacion = ?",[$clonNumeroCotizacion,$idCotizacionClon[0]->idCotizacionClon]);

    $corrigiendoidCotizacionClonREfaccion = \DB::UPDATE("UPDATE refaccionesEnCotizacion 
    SET idCotizacion = ? WHERE idCotizacion=? AND created_at > ?",
    [$idCotizacionClon[0]->idCotizacionClon,$idCotizacion,$asignacionesOriginalesRefaccion[0]->asignacionOriginal]);    
    $corrigiendoidCotizacionClon = \DB::UPDATE("UPDATE detallereparaciones SET idCotizacion = ?,numeroCotizacion = ? WHERE numeroCotizacion = ? AND created_at > ?",[$idCotizacionClon[0]->idCotizacionClon,$clonNumeroCotizacion,$numeroCotizacion[0]->numeroCotizacion,$asignacionesOriginales[0]->asignacionOriginal]);
    $desactivandoEquiposOriginales = \DB::UPDATE("UPDATE detallereparaciones SET clonEquiposActivo = 'No' WHERE idCotizacion = ? ",[$idCotizacion]);

    $idSucursal = $cotizacionEditar[0]->idSucursal;
    $comboEquipos = \DB::select("SELECT idEquipos, GCMid, complementoGCMid,activo,idc  
    FROM recepcionEquipos WHERE idEquipos NOT IN(SELECT dr.idEquipos FROM detallereparaciones AS dr WHERE dr.clonEquiposActivo='Si' AND dr.tipoCotizacion='reparacion' AND dr.idEquipos IS NOT NULL) AND idSucursal = ? ORDER BY GCMid,complementoGCMid asc ",[$idSucursal]);
    $comboEquiposenCotizacion = \DB::select("SELECT re.idEquipos, re.GCMid, re.complementoGCMid,activo, re.idc FROM recepcionEquipos AS  re where re.idSucursal = ? ORDER BY re.GCMid,re.complementoGCMid ASC",[$idSucursal]);
    $cuantosEquiposCotizados=\DB::select("SELECT COUNT(*) AS cuantos FROM detallereparaciones WHERE idCotizacion = ? ",[$idCotizacionClon[0]->idCotizacionClon]);

    $equiposReparados = \DB::select("SELECT dr.tipoCotizacion,dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion, dr.descripcion,dr.modelo,dr.numeroCotizacion,dr.notas,re.GCMid,re.complementoGCMid, dr.semanasEntrega, FORMAT(dr.montoEquipo,2) AS montoEquipo,
    FORMAT(dr.montoFinanciamiento,2) AS montoFinanciamiento,dr.descuento,dr.tipoDescuento
    FROM detallereparaciones AS dr
    INNER JOIN recepcionEquipos AS re ON re.idEquipos = dr.idEquipos
    WHERE idCotizacion = ? ",[$idCotizacionClon[0]->idCotizacionClon]);
    
    $equiposNuevosYreparados = \DB::select("SELECT *
    FROM detallereparaciones AS dr
    WHERE idCotizacion = ? ",[$idCotizacionClon[0]->idCotizacionClon]);

    $datosEquiposNuevos = \DB::select("SELECT pv.nombreRefaccion,pv.numeroParte,dr.tipoCotizacion,dr.idEquipos,dr.idPartesVenta
    FROM detallereparaciones AS dr
    INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idPartesVenta
    WHERE idCotizacion = ? AND tipoCotizacion !='reparacion'",[$idCotizacionClon[0]->idCotizacionClon]);
    
    $datosEquipoCotizado = \DB::select("SELECT re.idEquipos, re.serie,
    CONCAT (re.serie,' - ',te.tipoEquipo,' - ',se.subtipoEquipo) AS equipo
    FROM recepcionEquipos AS re
    INNER JOIN tipoequipos AS te ON te.idTipoEquipo= re.idTipoEquipo
    INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= re.idSubtipoEquipo
    WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones)");

    $cotizacionEditar1 = \DB::select("SELECT co.idCotizacion,co.idc,co.idu,co.idSucursal,co.numeroCotizacion,co.cotizacionOriginal,co.fechaCotizacion,co.estatus,tipoMoneda,co.nombreUsuario,
    CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
    co.noEquipos,co.dirigidoA,co.conCopia,
    co.cambio,co.montoTotal,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus,co.check1,co.textoCheck1,co.check2,co.textoCheck2,co.check3,co.textoCheck3,co.check4,
    co.textoCheck4,co.check5,co.textoCheck5,co.check6,co.textoCheck6,co.check7,co.textoCheck7,co.check8,co.textoCheck8
    FROM cotizaciones AS co
    INNER JOIN usuarios AS u ON u.idu= co.idu
    INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
    INNER JOIN clientes AS c ON c.idc= co.idc
    WHERE idCotizacion=(SELECT MAX(idCotizacion) AS idCotizacionEditar FROM cotizaciones WHERE idCotizacion =?)",[$idCotizacionClon[0]->idCotizacionClon]);

    $sucursales = \DB::select("SELECT * FROM contactosucursales WHERE idSucursal=?",[$cotizacionEditar1[0]->idSucursal]);

    $suma = \DB::select("SELECT FORMAT(SUM(dr.montoFinanciamiento),2) AS sumaTotal
    FROM detallereparaciones AS dr
    WHERE idCotizacion = ? ",[$idCotizacionClon[0]->idCotizacionClon]);
    
    $partidaSiguiente = \DB::table('detallereparaciones')->where('idCotizacion','=',$idCotizacion)->max('partida') + 1;

    return view('editarVersionCotizacion')
    ->with('numeroCotizacionVersionNEW',$numeroCotizacionVersionNEW)
    ->with('cuantosEquiposCotizados',$cuantosEquiposCotizados[0]->cuantos)
    ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
    ->with('equiposReparados',$equiposReparados)
    ->with('datosEquiposNuevos',$datosEquiposNuevos)
    ->with('datosEquipoCotizado',$datosEquipoCotizado)
    ->with('idSucursal',$idSucursal)
    ->with('comboEquipos',$comboEquipos)
    ->with('comboEquiposenCotizacion',$comboEquiposenCotizacion)
    ->with('comboUsuariosAdmon',$comboUsuariosAdmon)
    ->with('comboUsuariosVendedor',$comboUsuariosVendedor)
    ->with('comboConCopia',$comboConCopia)
    ->with('fotoCotizacionEditar',$fotoCotizacionEditar)
    ->with('idCotizacion',$idCotizacion)
    ->with('cotizacionEditar1',$cotizacionEditar1[0])
    ->with('suma',$suma[0]->sumaTotal)
    ->with('partidaSiguiente',$partidaSiguiente)
    ->with('consulta',$sucursales);
  }

  

  public function editarCotizacion(Request $request){
    $idCotizacion = $request->get('idCotizacion');

    $cotizacionEditar = \DB::select("SELECT co.idCotizacion,co.idc,co.idu,co.idSucursal,co.cambio,co.numeroCotizacion,co.fechaCotizacion,co.estatus,tipoMoneda,co.nombreUsuario,
    CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
    co.noEquipos,co.dirigidoA,co.conCopia,
    co.montoTotal,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus,co.check1,co.textoCheck1,co.check2,co.textoCheck2,co.check3,co.textoCheck3,co.check4,
		co.textoCheck4,co.check5,co.textoCheck5,co.check6,co.textoCheck6,co.check7,co.textoCheck7,co.check8,co.textoCheck8
    FROM cotizaciones AS co
    INNER JOIN usuarios AS u ON u.idu= co.idu
    INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
    INNER JOIN clientes AS c ON c.idc= co.idc
    WHERE idCotizacion=?",[$idCotizacion]);

    $fotoCotizacionEditar = \DB::select("SELECT archivoCotizacion FROM cotizaciones WHERE idCotizacion=?",[$idCotizacion]);

    $sucursales = \DB::select("SELECT * FROM contactosucursales WHERE idSucursal=?",[$cotizacionEditar[0]->idSucursal]);
    $idc=$cotizacionEditar[0]->idc;
    $comboUsuariosAdmon = usuarios::where('tipo','=','Administrador','AND','activo','=','Si')->orderBy('nombreUsuario', 'ASC')->get();
    $comboUsuariosVendedor = usuarios::where('tipo','=','Vendedor','AND','activo','=','Si')->orderBy('nombreUsuario', 'ASC')->get();
    $comboConCopia= \DB::select("SELECT co.nombreCont,s.sucursal from contactosucursales as co INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal where co.idc=$idc");

    return view('editarCotizacion')
    ->with('fotoCotizacionEditar',$fotoCotizacionEditar)
    ->with('cotizacionEditar',$cotizacionEditar[0])
    ->with('comboUsuariosAdmon',$comboUsuariosAdmon)
    ->with('comboUsuariosVendedor',$comboUsuariosVendedor)
    ->with('comboConCopia',$comboConCopia)
    ->with('consulta',$sucursales);
  }

  public function reparaciones(Request $request){
    $idc = $request->get('idc');
    $tipoCotizacion = $request->get('tipoCotizacion');
    $idSucursal = $request->get('idSucursal');
    $idEquipos = $request->get('idEquipos');
    $numeroCotizacion = $request->get('numeroCotizacionVista');
    $idCotizacion = $request->get('idCotizacion');
    $tipoMoneda = $request->get('tipoMoneda');
    $cambioDolar = $request->get('cambio');

    $consulta = recepcionEquipos::where ('idEquipos','=',$idEquipos)->get();
    $partes = partesreparacion::where ('idEquipos','=',$idEquipos)->get();
    $campoDescripcion = \DB::select("SELECT CONCAT(t.tipoEquipo,' - ',r.marca,' - ',r.modelo) as descripcion FROM recepcionEquipos AS r
    LEFT JOIN tipoequipos AS t ON t.idTipoEquipo = r.idTipoEquipo WHERE r.idEquipos=?",[$idEquipos]); 

    $sumaPreciosServicios = \DB::select("SELECT SUM(s.precioPesos) AS precioPesos,SUM(s.precioDolar) AS precioDolar
    FROM serviciosreparacionpartes AS s
    LEFT JOIN serviciostalleres AS st ON st.idServicioTaller = s.idServicioTaller
    LEFT JOIN alcances AS a ON a.idAlcance = s.idAlcance
    LEFT JOIN partesreparacion AS p ON p.idParte = s.idParte
    WHERE s.idParte IN (SELECT idParte FROM partesreparacion WHERE idEquipos=?)",[$idEquipos]);
    
    $sumaPreciosRefacciones =\DB::select("SELECT SUM(rp.precioPesos) AS precioPesos,SUM(rp.precioDolar) AS precioDolar
    FROM partesreparacion AS pr
    INNER JOIN refaccionesreparacionpartes AS rp ON rp.idParte = pr.idParte
    INNER JOIN tiporefacciones AS tr ON tr.idTipoRefacciones = rp.idTipoRefacciones
    WHERE idEquipos=?",[$idEquipos]);
    
    $preciosServicios = \DB::select("SELECT s.precioPesos,s.precioDolar
    FROM serviciosreparacionpartes AS s
    LEFT JOIN serviciostalleres AS st ON st.idServicioTaller = s.idServicioTaller
    LEFT JOIN alcances AS a ON a.idAlcance = s.idAlcance
    LEFT JOIN partesreparacion AS p ON p.idParte = s.idParte
    WHERE s.idParte IN (SELECT idParte FROM partesreparacion WHERE idEquipos=?)",[$idEquipos]);
    
    $preciosRefacciones =\DB::select("SELECT rp.precioPesos,rp.precioDolar
    FROM partesreparacion AS pr
    INNER JOIN refaccionesreparacionpartes AS rp ON rp.idParte = pr.idParte
    INNER JOIN tiporefacciones AS tr ON tr.idTipoRefacciones = rp.idTipoRefacciones
    WHERE idEquipos=?",[$idEquipos]);

    $sumaPesosServicios=0;
    $sumaPesosRefacciones=0;
    $sumaPesosServiciosTotal=0;
    $sumaPesosRefaccionesTotal=0;
    for ($i=0; $i < count($preciosServicios); $i++) { 
      if($preciosServicios[$i]->precioPesos == 0.00 && $preciosServicios[$i]->precioDolar != ""){        
        $sumaPesosServicios = $preciosServicios[$i]->precioDolar * $cambioDolar;
        $sumaPesosServiciosTotal = $sumaPesosServiciosTotal + $sumaPesosServicios;
        
      }
    }

    for ($i=0; $i < count($preciosRefacciones); $i++) { 
      if($preciosRefacciones[$i]->precioPesos == 0.00 && $preciosRefacciones[$i]->precioDolar != ""){
        $sumaPesosRefacciones = $preciosRefacciones[$i]->precioDolar * $cambioDolar;
        $sumaPesosRefaccionesTotal = $sumaPesosRefaccionesTotal + $sumaPesosRefacciones;        
      }
    }

    if($tipoMoneda == 'MXN'){
      $monto = $sumaPreciosServicios[0]->precioPesos + $sumaPreciosRefacciones[0]->precioPesos;
      $monto = $monto + $sumaPesosServiciosTotal + $sumaPesosRefaccionesTotal;
    }else{
      $monto = $sumaPreciosServicios[0]->precioDolar + $sumaPreciosRefacciones[0]->precioDolar;
    }

    $equiposReparados = \DB::select("SELECT dr.tipoCotizacion,dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion, dr.descripcion,dr.modelo,dr.numeroCotizacion,dr.notas,re.GCMid,re.complementoGCMid, dr.semanasEntrega, FORMAT(dr.montoEquipo,2) AS montoEquipo,
    FORMAT(dr.montoFinanciamiento,2) AS montoFinanciamiento,dr.descuento,dr.tipoDescuento
    FROM detallereparaciones AS dr
    INNER JOIN recepcionEquipos AS re ON re.idEquipos = dr.idEquipos
    WHERE dr.idCotizacion=?",[$idCotizacion]);
    
    $equiposNuevosYreparados = \DB::select("SELECT *
    FROM detallereparaciones AS dr
    WHERE dr.idCotizacion=?",[$idCotizacion]);
    $cuantos = count($equiposNuevosYreparados);

    $datosEquiposNuevos = \DB::select("SELECT pv.nombreRefaccion,pv.numeroParte,dr.tipoCotizacion,dr.idEquipos,dr.idPartesVenta
    FROM detallereparaciones AS dr
    INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idPartesVenta
    WHERE dr.idCotizacion=? AND tipoCotizacion !='reparacion'",[$idCotizacion]);
    
    $datosEquipoCotizado = \DB::select("SELECT re.idEquipos, re.serie,
    CONCAT (re.serie,' - ',te.tipoEquipo,' - ',se.subtipoEquipo) AS equipo
    FROM recepcionEquipos AS re
    INNER JOIN tipoequipos AS te ON te.idTipoEquipo= re.idTipoEquipo
    INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= re.idSubtipoEquipo
    WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones)");

    $equiposCotizados = \DB::select("SELECT dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion, dr.numeroCotizacion, dr.semanasEntrega, dr.montoEquipo FROM detallereparaciones AS dr WHERE idEquipos IN (SELECT idEquipos FROM recepcionEquipos WHERE idc=?)",[$idc]);
    $cuantosCotizados = count($equiposCotizados);

    $diasDePago = \DB::select("SELECT diasDePago FROM clientes WHERE idc= $idc");

    $suma = \DB::select("SELECT FORMAT(SUM(dr.montoFinanciamiento),2) AS sumaTotal
    FROM detallereparaciones AS dr
    WHERE numeroCotizacion=?",[$idCotizacion]);

    $marca = marcaRefaccion::orderby('marcaRefaccion', 'asc')->get();
    $tipoParte = partesVenta::orderby('idPartesVenta', 'asc')->where('activo','=','Si')->get();
    $paquetes = \DB::select("SELECT idPaquete, nombrePaquete,activo FROM paquetes");
    $partesEquipos = \DB::select("SELECT idParte,idEquipos,GCMidParte,nombreParte FROM partesreparacion WHERE idEquipos=$idEquipos ORDER BY GCMidParte ASC ");
    
    $partidaSiguiente = \DB::table('detallereparaciones')->where('idCotizacion','=',$idCotizacion)->max('partida') + 1;
    
    $estatusCotizacion = \DB::select("SELECT estatus FROM cotizaciones WHERE idCotizacion = ?",[$idCotizacion]);
    // $refacciones =\DB::select("SELECT rf.idParte,p.GCMidParte,rf.tipoProducto,pq.nombrePaquete,rf.idPaquete,rf.apareceEnCoti,pv.codigo,pv.nombreRefaccion,FORMAt(rf.precioPesos,2) AS precioPesos,FORMAT(rf.precioDolar,2) AS precioDolar
    // FROM refaccionesreparacionpartes AS rf  
    // LEFT JOIN partesreparacion AS p ON p.idParte = rf.idParte
    // LEFT JOIN partesVenta AS pv ON pv.idPartesVenta = rf.idPartesVenta
    // LEFT JOIN paquetes AS pq ON pq.idPaquete = rf.idPaquete
    // WHERE idEquipos=?
    // ORDER BY rf.idPaquete",[$idEquipos]);
    // $cuantasRefacciones = count($refacciones);

    return view("reparaciones")
    ->with('marca',$marca)
    ->with('tipoParte',$tipoParte)
    ->with('paquetes',$paquetes)
    ->with('partidaSiguiente',$partidaSiguiente)
    ->with('partesEquipos',$partesEquipos)
    ->with('idEquipos',$idEquipos)
    ->with('diasDePago',$diasDePago[0]->diasDePago)
    ->with('idSucursal',$idSucursal)
    ->with('numeroCotizacion',$numeroCotizacion)
    ->with('idCotizacion',$idCotizacion)
    ->with('consulta',$consulta[0])
    ->with('partes',$partes)
    ->with('campoDescripcion',$campoDescripcion[0])    
    ->with('cuantos',$cuantos)
    ->with('suma',$suma[0]->sumaTotal)
    ->with('monto',$monto)
    ->with('tipoMoneda',$tipoMoneda)
    ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
    ->with('equiposReparados',$equiposReparados)
    ->with('datosEquiposNuevos',$datosEquiposNuevos)
    ->with('datosEquipoCotizado',$datosEquipoCotizado)
    ->with('equiposCotizados',$equiposCotizados)
    ->with('tipoCotizacion',$tipoCotizacion)
    ->with('cuantosCotizados',$cuantosCotizados)
    ->with('estatusCotizacion',$estatusCotizacion[0]->estatus);
  }

  public function agregarRefaccionParteCotizaciones(Request $request){
    $idCotizacion = $request-> idCotizacion;
    $partida = $request-> partida;
    $idParte = $request->idParte;
    $idPartesVenta = $request->idRefaccion;
    // $idRefaccion = $request->idRefaccion;
    $idTipoRefacciones = $request->idTipoRefacciones;
    $apareceEnCoti = $request->apareceEnCoti;
    $tipoMoneda = $request->tipoMoneda;

    $refCot = new refaccionesEnCotizacion;
    $refCot-> idCotizacion = $idCotizacion;
    $refCot-> partida = $partida;
    $refCot-> idEquipos = $request-> idEquipos;
    $refCot-> idParte = $request-> idParte;
    $refCot-> idPartesVenta = $request-> idRefaccion;
    $refCot-> tipoProducto = $request-> tipoProducto;
    $refCot-> precioPesos = $request-> precioPesos;
    $refCot-> precioDolar = $request-> precioDolar;
    // $refCot-> idRefaccion = $request-> idRefaccion;
    $refCot-> idTipoRefacciones = $request-> idTipoRefacciones;
    $refCot-> apareceEnCoti = $request-> apareceEnCoti;
    $refCot->save();

    $refacciones =\DB::select("SELECT rf.idParte,rf.partida,rf.idRefaccionCotizacion,rf.idCotizacion,
    p.GCMidParte,rf.tipoProducto,pq.nombrePaquete,
    rf.idPaquete,rf.apareceEnCoti,pv.codigo,
    pv.nombreRefaccion,
    FORMAt(rf.precioPesos,2) AS precioPesos,
    FORMAT(rf.precioDolar,2) AS precioDolar
    FROM refaccionesEnCotizacion AS rf  
    LEFT JOIN partesreparacion AS p ON p.idParte = rf.idParte
    LEFT JOIN partesVenta AS pv ON pv.idPartesVenta = rf.idPartesVenta
    LEFT JOIN paquetes AS pq ON pq.idPaquete = rf.idPaquete
    WHERE rf.idCotizacion = ? AND partida = ?",[$idCotizacion,$partida]);
    $cuantasRefacciones = count($refacciones);

    $sumaPrecios =\DB::select("SELECT SUM(precioPesos) AS precioPesos, SUM(precioDolar) AS precioDolar
    FROM refaccionesEnCotizacion WHERE idCotizacion = ? AND partida = ?",[$idCotizacion,$partida]);
   
    return view('reporteRefaccionesCotizacion')
    ->with('cuantasRefacciones',$cuantasRefacciones)
    ->with('refacciones',$refacciones)
    ->with('tipoMoneda',$tipoMoneda)
    ->with('sumaPrecios',$sumaPrecios[0]);
    
  }

  public function refaccionesAsignadasCotizacion(Request $request){
    $idCotizacion = $request-> idCotizacion;
    $partida = $request-> partida;
    $tipoMoneda = $request->tipoMoneda;

    $refacciones =\DB::select("SELECT rf.idParte,rf.partida,rf.idRefaccionCotizacion,rf.idCotizacion,
    p.GCMidParte,rf.tipoProducto,pq.nombrePaquete,
    rf.idPaquete,rf.apareceEnCoti,pv.codigo,
    pv.nombreRefaccion,
    FORMAt(rf.precioPesos,2) AS precioPesos,
    FORMAT(rf.precioDolar,2) AS precioDolar
    FROM refaccionesEnCotizacion AS rf  
    LEFT JOIN partesreparacion AS p ON p.idParte = rf.idParte
    LEFT JOIN partesVenta AS pv ON pv.idPartesVenta = rf.idPartesVenta
    LEFT JOIN paquetes AS pq ON pq.idPaquete = rf.idPaquete
    WHERE rf.idCotizacion = ? AND rf.partida = ?",[$idCotizacion,$partida]);
    $cuantasRefacciones = count($refacciones);

    $sumaPrecios =\DB::select("SELECT SUM(precioPesos) AS precioPesos, SUM(precioDolar) AS precioDolar
    FROM refaccionesEnCotizacion WHERE idCotizacion = ? AND partida = ?",[$idCotizacion,$partida]);
    return view('reporteRefaccionesCotizacion')
    ->with('cuantasRefacciones',$cuantasRefacciones)
    ->with('refacciones',$refacciones)
    ->with('tipoMoneda',$tipoMoneda)
    ->with('sumaPrecios',$sumaPrecios[0]);
  }

  public function agregarPaqueteCotizacio(Request $request){
    $idCotizacion = $request-> idCotizacion;
    $partida = $request-> partida;
    $idParte = $request->idParte;
    $idPartesVenta = $request->idRefaccion;
    $idTipoRefacciones = $request->idTipoRefacciones;
    $apareceEnCoti = $request->apareceEnCoti;
    $tipoMoneda = $request->tipoMoneda; 
      
    $idPaquete = $request->idPaquete;
    $tipoProducto = $request->tipoProducto;

    $detalle = detallepaquetes::where('idPaquete','=',$idPaquete)->get();
   
   
    foreach($detalle as $d){
      refaccionesEnCotizacion::create([
        'idCotizacion' => $request->idCotizacion,
        'partida' => $request->partida,
        'idEquipos' => $request->idEquipos,
        'idParte' => $request->idParte,        
        'idPartesVenta' => $d->idPartesVenta,
        'tipoProducto' => $request->tipoProducto,
        'idTipoRefacciones' => $d->idTipoRefacciones,
        'precioPesos' => $d->precioVentaPe,
        'precioDolar' => $d->precioVentaDol,
        'apareceEnCoti' => $d->apareceCoti,
        'idPaquete' => $d->idPaquete
      ]);
    }
    
    $refacciones =\DB::select("SELECT rf.idParte,rf.partida,rf.idRefaccionCotizacion,rf.idCotizacion,
    p.GCMidParte,rf.tipoProducto,pq.nombrePaquete,
    rf.idPaquete,rf.apareceEnCoti,pv.codigo,
    pv.nombreRefaccion,
    FORMAt(rf.precioPesos,2) AS precioPesos,
    FORMAT(rf.precioDolar,2) AS precioDolar
    FROM refaccionesEnCotizacion AS rf  
    LEFT JOIN partesreparacion AS p ON p.idParte = rf.idParte
    LEFT JOIN partesVenta AS pv ON pv.idPartesVenta = rf.idPartesVenta
    LEFT JOIN paquetes AS pq ON pq.idPaquete = rf.idPaquete
    WHERE rf.idCotizacion = ? AND partida = ?",[$idCotizacion,$partida]);
    $cuantasRefacciones = count($refacciones);

    $sumaPrecios =\DB::select("SELECT SUM(precioPesos) AS precioPesos, SUM(precioDolar) AS precioDolar
    FROM refaccionesEnCotizacion WHERE idCotizacion = ? AND partida = ?",[$idCotizacion,$partida]);
   
    return view('reporteRefaccionesCotizacion')
    ->with('cuantasRefacciones',$cuantasRefacciones)
    ->with('refacciones',$refacciones)
    ->with('tipoMoneda',$tipoMoneda)
    ->with('sumaPrecios',$sumaPrecios[0]);
  }

  public function borrarRefaccionParteCotizacion(Request $request){
    $idRefaccionBorrar = $request->idRefaccionBorrar;
    $idCotizacion = $request->idCotizacionBorrar;
    $partida = $request->partidaBorrar;
    $tipoMoneda= $request->tipoMoneda;

    $borro= \DB::delete("DELETE FROM refaccionesEnCotizacion WHERE idRefaccionCotizacion=?",[$idRefaccionBorrar]);

    $refacciones =\DB::select("SELECT rf.idParte,rf.partida,rf.idRefaccionCotizacion,rf.idCotizacion,
    p.GCMidParte,rf.tipoProducto,pq.nombrePaquete,
    rf.idPaquete,rf.apareceEnCoti,pv.codigo,
    pv.nombreRefaccion,
    FORMAt(rf.precioPesos,2) AS precioPesos,
    FORMAT(rf.precioDolar,2) AS precioDolar
    FROM refaccionesEnCotizacion AS rf  
    LEFT JOIN partesreparacion AS p ON p.idParte = rf.idParte
    LEFT JOIN partesVenta AS pv ON pv.idPartesVenta = rf.idPartesVenta
    LEFT JOIN paquetes AS pq ON pq.idPaquete = rf.idPaquete
    WHERE rf.idCotizacion = ? AND partida = ?",[$idCotizacion,$partida]);
    $cuantasRefacciones = count($refacciones);

    $sumaPrecios =\DB::select("SELECT SUM(precioPesos) AS precioPesos, SUM(precioDolar) AS precioDolar
    FROM refaccionesEnCotizacion WHERE idCotizacion = ? AND partida = ?",[$idCotizacion,$partida]);
   
    return view('reporteRefaccionesCotizacion')
    ->with('cuantasRefacciones',$cuantasRefacciones)
    ->with('refacciones',$refacciones)
    ->with('tipoMoneda',$tipoMoneda)
    ->with('sumaPrecios',$sumaPrecios[0]);
  }

  public function cotizacionEquipoNuevo(Request $request){
    $idc = $request->get('idc');
    $tipoCotizacion = $request->get('tipoCotizacion');
    $idSucursal = $request->get('idSucursal');
    $idEquipos = $request->get('idEquipos');
    $numeroCotizacion = $request->get('numeroCotizacionVista');
    if(isset($numeroCotizacion)){
      $numeroCotizacion = $request->get('numeroCotizacionVista'); 
    }else{
      $numeroCotizacion = $request->get('numeroCotizacion');
    }
    $idCotizacion = $request->get('idCotizacion');
    $tipoMoneda = $request->get('tipoMoneda');
    $cambioDolar = $request->get('cambio');

    $diasDePago = \DB::select("SELECT diasDePago FROM clientes WHERE idc= $idc");

    $marcasEquiposNuevos = \DB::select("SELECT idMarcaRefa,marcaRefaccion FROM marcasRefaccion WHERE activo = 'Si' ORDER BY marcaRefaccion ASC");
    // $equiposNuevos = \DB::select("SELECT numeroParte, precioVentaPe FROM partesVenta ORDER BY numeroParte ASC");

    $equiposReparados = \DB::select("SELECT dr.tipoCotizacion,dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion, dr.descripcion,dr.modelo,dr.numeroCotizacion,dr.notas,re.GCMid,re.complementoGCMid, dr.semanasEntrega, FORMAT(dr.montoEquipo,2) AS montoEquipo,
    FORMAT(dr.montoFinanciamiento,2) AS montoFinanciamiento,dr.descuento,dr.tipoDescuento
    FROM detallereparaciones AS dr
    INNER JOIN recepcionEquipos AS re ON re.idEquipos = dr.idEquipos
    WHERE dr.idCotizacion=?",[$idCotizacion]);
    
    $equiposNuevosYreparados = \DB::select("SELECT *
    FROM detallereparaciones AS dr
    WHERE dr.idCotizacion=?",[$idCotizacion]);
    $cuantos = count($equiposNuevosYreparados);

    $datosEquiposNuevos = \DB::select("SELECT pv.nombreRefaccion,pv.numeroParte,dr.tipoCotizacion,dr.idEquipos,dr.idPartesVenta
    FROM detallereparaciones AS dr
    INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idPartesVenta
    WHERE dr.idCotizacion=? AND tipoCotizacion !='reparacion'",[$idCotizacion]);

    $datosEquipoCotizado = \DB::select("SELECT re.idEquipos, re.serie,
    CONCAT (re.serie,' - ',te.tipoEquipo,' - ',se.subtipoEquipo) AS equipo
    FROM recepcionEquipos AS re
    INNER JOIN tipoequipos AS te ON te.idTipoEquipo= re.idTipoEquipo
    INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= re.idSubtipoEquipo
    WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones)");

    $suma = \DB::select("SELECT FORMAT(SUM(dr.montoFinanciamiento),2) AS sumaTotal
    FROM detallereparaciones AS dr
    WHERE dr.idCotizacion=?",[$idCotizacion]);

    $partidaSiguiente = \DB::table('detallereparaciones')->where('idCotizacion','=',$idCotizacion)->max('partida') + 1;

    $estatusCotizacion = \DB::select("SELECT estatus FROM cotizaciones WHERE idCotizacion = ?",[$idCotizacion]);

    return view("cotizarNuevo")
    ->with('partidaSiguiente',$partidaSiguiente)
    ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
    ->with('equiposReparados',$equiposReparados)
    ->with('datosEquiposNuevos',$datosEquiposNuevos)
    ->with('cuantos',$cuantos)
    ->with('suma',$suma[0]->sumaTotal)
    ->with('datosEquipoCotizado',$datosEquipoCotizado)
    ->with('diasDePago',$diasDePago[0]->diasDePago)
    ->with('idSucursal',$idSucursal)
    ->with('marcasEquiposNuevos',$marcasEquiposNuevos)
    ->with('numeroCotizacion',$numeroCotizacion)
    ->with('idCotizacion',$idCotizacion)
    ->with('tipoCotizacion',$tipoCotizacion)
    ->with('tipoMoneda',$tipoMoneda)
    ->with('cambioDolar',$cambioDolar)
    ->with('estatusCotizacion',$estatusCotizacion[0]->estatus);
  }

  public function cotizacionRefurbished(Request $request){
    $idc = $request->get('idc');
    $tipoCotizacion = $request->get('tipoCotizacion');
    $idSucursal = $request->get('idSucursal');
    $idEquipos = $request->get('idEquipos');
    $numeroCotizacion = $request->get('numeroCotizacionVista');
    if(isset($numeroCotizacion)){
      $numeroCotizacion = $request->get('numeroCotizacionVista'); 
    }else{
      $numeroCotizacion = $request->get('numeroCotizacion');
    }
    $idCotizacion = $request->get('idCotizacion');
    $tipoMoneda = $request->get('tipoMoneda');
    $cambioDolar = $request->get('cambio');

    $diasDePago = \DB::select("SELECT diasDePago FROM clientes WHERE idc= $idc");

    $marcasEquiposNuevos = \DB::select("SELECT idMarcaRefa,marcaRefaccion FROM marcasRefaccion WHERE activo = 'Si' ORDER BY marcaRefaccion ASC");
    // $equiposNuevos = \DB::select("SELECT numeroParte, precioVentaPe FROM partesVenta ORDER BY numeroParte ASC");

    $equiposReparados = \DB::select("SELECT dr.tipoCotizacion,dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion, dr.descripcion,dr.modelo,dr.numeroCotizacion,dr.notas,re.GCMid,re.complementoGCMid, dr.semanasEntrega, FORMAT(dr.montoEquipo,2) AS montoEquipo,
    FORMAT(dr.montoFinanciamiento,2) AS montoFinanciamiento,dr.descuento,dr.tipoDescuento
    FROM detallereparaciones AS dr
    INNER JOIN recepcionEquipos AS re ON re.idEquipos = dr.idEquipos
    WHERE dr.idCotizacion=?",[$idCotizacion]);
    
    $equiposNuevosYreparados = \DB::select("SELECT *
    FROM detallereparaciones AS dr
    WHERE dr.idCotizacion=?",[$idCotizacion]);
    $cuantos = count($equiposNuevosYreparados);

    $datosEquiposNuevos = \DB::select("SELECT pv.nombreRefaccion,pv.numeroParte,dr.tipoCotizacion,dr.idEquipos,dr.idPartesVenta
    FROM detallereparaciones AS dr
    INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idPartesVenta
    WHERE dr.idCotizacion=? AND tipoCotizacion !='reparacion'",[$idCotizacion]);

    $datosEquipoCotizado = \DB::select("SELECT re.idEquipos, re.serie,
    CONCAT (re.serie,' - ',te.tipoEquipo,' - ',se.subtipoEquipo) AS equipo
    FROM recepcionEquipos AS re
    INNER JOIN tipoequipos AS te ON te.idTipoEquipo= re.idTipoEquipo
    INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= re.idSubtipoEquipo
    WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones)");

    $suma = \DB::select("SELECT FORMAT(SUM(dr.montoFinanciamiento),2) AS sumaTotal
    FROM detallereparaciones AS dr
    WHERE dr.idCotizacion=?",[$idCotizacion]);
    
    $partidaSiguiente = \DB::table('detallereparaciones')->where('idCotizacion','=',$idCotizacion)->max('partida') + 1;
    
    $estatusCotizacion = \DB::select("SELECT estatus FROM cotizaciones WHERE idCotizacion = ?",[$idCotizacion]);

    return view("cotizarRefurbished")
    ->with('partidaSiguiente',$partidaSiguiente)
    ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
    ->with('equiposReparados',$equiposReparados)
    ->with('datosEquiposNuevos',$datosEquiposNuevos)
    ->with('cuantos',$cuantos)
    ->with('suma',$suma[0]->sumaTotal)
    ->with('datosEquipoCotizado',$datosEquipoCotizado)
    ->with('diasDePago',$diasDePago[0]->diasDePago)
    ->with('idSucursal',$idSucursal)
    ->with('marcasEquiposNuevos',$marcasEquiposNuevos)
    ->with('numeroCotizacion',$numeroCotizacion)
    ->with('idCotizacion',$idCotizacion)
    ->with('tipoCotizacion',$tipoCotizacion)
    ->with('tipoMoneda',$tipoMoneda)
    ->with('cambioDolar',$cambioDolar)
    ->with('estatusCotizacion',$estatusCotizacion[0]->estatus);
    
  }

  public function editarAsignacionEquiposCotizacion(Request $request){
    $idc = $request->get('idc');
    $tipoCotizacion = $request->get('tipoCotizacion');
    $idEquipos = $request->get('idEquipos');
    $numeroCotizacion = $request->get('numeroCotizacion');
    $idCotizacion = $request->get('idCotizacion');
    $tipoMoneda = $request->get('tipoMoneda');
    $cambioDolar = $request->get('cambio');


    // $tipoCotizacion =\DB::select("SELECT tipoCotizacion FROM detallereparaciones WHERE idCotizacion=?",[$idCotizacion]);
    // $tipoCotizacion=$tipoCotizacion[0]->tipoCotizacion;

    $idSucursalConsulta = \DB::select("SELECT idSucursal FROM cotizaciones WHERE idCotizacion=?",[$idCotizacion]);
    $idSucursal = $idSucursalConsulta[0]->idSucursal;
    $consulta = recepcionEquipos::where ('idEquipos','=',$idEquipos)->get();
    $partes = partesreparacion::where ('idEquipos','=',$idEquipos)->get();
    $cuantasPartes = count($partes);
    $campoDescripcion = \DB::select("SELECT CONCAT(t.tipoEquipo,' - ',r.marca,' - ',r.modelo) as descripcion FROM recepcionEquipos AS r
    LEFT JOIN tipoequipos AS t ON t.idTipoEquipo = r.idTipoEquipo WHERE r.idEquipos=?",[$idEquipos]);

    $refacciones =\DB::select("SELECT rf.idParte,p.GCMidParte,rf.tipoProducto,pq.nombrePaquete,rf.idPaquete,rf.apareceEnCoti,
    pv.codigo,pv.nombreRefaccion,FORMAt(rf.precioPesos,2) AS precioPesos,FORMAT(rf.precioDolar,2) AS precioDolar
    FROM refaccionesreparacionpartes AS rf  
    LEFT JOIN partesreparacion AS p ON p.idParte = rf.idParte
    LEFT JOIN partesVenta AS pv ON pv.idPartesVenta = rf.idPartesVenta
    LEFT JOIN paquetes AS pq ON pq.idPaquete = rf.idPaquete
    WHERE idEquipos=?
    ORDER BY rf.idPaquete",[$idEquipos]);
    $cuantasRefacciones = count($refacciones);

    $equiposReparados = \DB::select("SELECT dr.tipoCotizacion,dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion, dr.descripcion,dr.modelo,dr.numeroCotizacion,dr.notas,re.GCMid,re.complementoGCMid, dr.semanasEntrega, FORMAT(dr.montoEquipo,2) AS montoEquipo,
    FORMAT(dr.montoFinanciamiento,2) AS montoFinanciamiento,dr.descuento,dr.tipoDescuento
    FROM detallereparaciones AS dr
    INNER JOIN recepcionEquipos AS re ON re.idEquipos = dr.idEquipos
    WHERE numeroCotizacion=?",[$numeroCotizacion]);
    
    $equiposNuevosYreparados = \DB::select("SELECT *
    FROM detallereparaciones AS dr
    WHERE numeroCotizacion=?",[$numeroCotizacion]);
    $cuantos = count($equiposNuevosYreparados);

    $datosEquiposNuevos = \DB::select("SELECT pv.nombreRefaccion,pv.numeroParte,dr.tipoCotizacion,dr.idEquipos,dr.idPartesVenta
    FROM detallereparaciones AS dr
    INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idPartesVenta
    WHERE numeroCotizacion=? AND tipoCotizacion !='reparacion'",[$numeroCotizacion]);

    $datosEquipoCotizado = \DB::select("SELECT re.idEquipos, re.serie,
    CONCAT (re.serie,' - ',te.tipoEquipo,' - ',se.subtipoEquipo) AS equipo
    FROM recepcionEquipos AS re
    INNER JOIN tipoequipos AS te ON te.idTipoEquipo= re.idTipoEquipo
    INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= re.idSubtipoEquipo
    WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones)");

    $equiposCotizados = \DB::select("SELECT dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion, dr.numeroCotizacion, dr.semanasEntrega, dr.montoEquipo FROM detallereparaciones AS dr WHERE idEquipos IN (SELECT idEquipos FROM recepcionEquipos WHERE idc=?)",[$idc]);
    $cuantosCotizados = count($equiposCotizados);

    $diasDePago = \DB::select("SELECT diasDePago FROM clientes WHERE idc= $idc");

    $sumaPreciosServicios = \DB::select("SELECT SUM(s.precioPesos) AS precioPesos,SUM(s.precioDolar) AS precioDolar
    FROM serviciosreparacionpartes AS s
    LEFT JOIN serviciostalleres AS st ON st.idServicioTaller = s.idServicioTaller
    LEFT JOIN alcances AS a ON a.idAlcance = s.idAlcance
    LEFT JOIN partesreparacion AS p ON p.idParte = s.idParte
    WHERE s.idParte IN (SELECT idParte FROM partesreparacion WHERE idEquipos=?)",[$idEquipos]);
    
    $sumaPreciosRefacciones =\DB::select("SELECT SUM(rp.precioPesos) AS precioPesos,SUM(rp.precioDolar) AS precioDolar
    FROM partesreparacion AS pr
    INNER JOIN refaccionesreparacionpartes AS rp ON rp.idParte = pr.idParte
    INNER JOIN tiporefacciones AS tr ON tr.idTipoRefacciones = rp.idTipoRefacciones
    WHERE idEquipos=?",[$idEquipos]);
    
    $preciosServicios = \DB::select("SELECT s.precioPesos,s.precioDolar
    FROM serviciosreparacionpartes AS s
    LEFT JOIN serviciostalleres AS st ON st.idServicioTaller = s.idServicioTaller
    LEFT JOIN alcances AS a ON a.idAlcance = s.idAlcance
    LEFT JOIN partesreparacion AS p ON p.idParte = s.idParte
    WHERE s.idParte IN (SELECT idParte FROM partesreparacion WHERE idEquipos=?)",[$idEquipos]);
    
    $preciosRefacciones =\DB::select("SELECT rp.precioPesos,rp.precioDolar
    FROM partesreparacion AS pr
    INNER JOIN refaccionesreparacionpartes AS rp ON rp.idParte = pr.idParte
    INNER JOIN tiporefacciones AS tr ON tr.idTipoRefacciones = rp.idTipoRefacciones
    WHERE idEquipos=?",[$idEquipos]);

    $suma = \DB::select("SELECT FORMAT(SUM(dr.montoFinanciamiento),2) AS sumaTotal
    FROM detallereparaciones AS dr
    WHERE numeroCotizacion=?",[$numeroCotizacion]);

    $sumaPesosServicios=0;
    $sumaPesosRefacciones=0;
    $sumaPesosServiciosTotal=0;
    $sumaPesosRefaccionesTotal=0;
    for ($i=0; $i < count($preciosServicios); $i++) { 
      if($preciosServicios[$i]->precioPesos == 0.00 && $preciosServicios[$i]->precioDolar != ""){        
        $sumaPesosServicios = $preciosServicios[$i]->precioDolar * $cambioDolar;
        $sumaPesosServiciosTotal = $sumaPesosServiciosTotal + $sumaPesosServicios;
        
      }
    }

    for ($i=0; $i < count($preciosRefacciones); $i++) { 
      if($preciosRefacciones[$i]->precioPesos == 0.00 && $preciosRefacciones[$i]->precioDolar != ""){
        $sumaPesosRefacciones = $preciosRefacciones[$i]->precioDolar * $cambioDolar;
        $sumaPesosRefaccionesTotal = $sumaPesosRefaccionesTotal + $sumaPesosRefacciones;        
      }
    }

    if($tipoMoneda == 'MXN'){
      $monto = $sumaPreciosServicios[0]->precioPesos + $sumaPreciosRefacciones[0]->precioPesos;
      $monto = $monto + $sumaPesosServiciosTotal + $sumaPesosRefaccionesTotal;
    }else{
      $monto = $sumaPreciosServicios[0]->precioDolar + $sumaPreciosRefacciones[0]->precioDolar;
    }

    $marca = marcaRefaccion::orderby('marcaRefaccion', 'asc')->get();
    $tipoParte = partesVenta::orderby('idPartesVenta', 'asc')->get();
    $paquetes = \DB::select("SELECT idPaquete, nombrePaquete,activo FROM paquetes order by nombrePaquete asc");
    $partesEquipos = \DB::select("SELECT idParte,idEquipos,GCMidParte,nombreParte FROM partesreparacion WHERE idEquipos=$idEquipos order by GCMidParte asc ");        
    
    $partidaSiguiente = \DB::table('detallereparaciones')->where('idCotizacion','=',$idCotizacion)->max('partida') + 1;

    $estatusCotizacion = \DB::select("SELECT estatus FROM cotizaciones WHERE idCotizacion = ?",[$idCotizacion]);

    return view("editarAsignacionEquiposCotizacion")
    ->with('marca',$marca)
    ->with('tipoParte',$tipoParte)
    ->with('paquetes',$paquetes)
    ->with('partidaSiguiente',$partidaSiguiente)
    ->with('partesEquipos',$partesEquipos)
    ->with('idEquipos',$idEquipos)
    ->with('tipoCotizacion',$tipoCotizacion)
    ->with('diasDePago',$diasDePago[0]->diasDePago)
    ->with('idSucursal',$idSucursal)
    ->with('numeroCotizacion',$numeroCotizacion)
    ->with('idCotizacion',$idCotizacion)
    ->with('consulta',$consulta[0])
    ->with('campoDescripcion',$campoDescripcion[0])
    ->with('partes',$partes)
    ->with('cuantasPartes',$cuantasPartes)
    ->with('refacciones',$refacciones)
    ->with('cuantasRefacciones',$cuantasRefacciones)
    ->with('cuantos',$cuantos)
    ->with('suma',$suma[0]->sumaTotal)
    ->with('monto',$monto)
    ->with('tipoMoneda',$tipoMoneda)
    ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
    ->with('equiposReparados',$equiposReparados)
    ->with('datosEquiposNuevos',$datosEquiposNuevos)
    ->with('datosEquipoCotizado',$datosEquipoCotizado)
    ->with('equiposCotizados',$equiposCotizados)
    ->with('cuantosCotizados',$cuantosCotizados)
    ->with('estatusCotizacion',$estatusCotizacion[0]->estatus);
  }

  public function rollbackCotizacion(Request $request){
    $idCotizacion = $request->get('idCotizacion');
    
    $regresarEstatus = \DB::select("SELECT idEquipos,estatusAnteriorEquipo FROM detallereparaciones WHERE idCotizacion = ? AND tipoCotizacion='reparacion'",[$idCotizacion]);
    $cuantasR = count($regresarEstatus);

    for ($i=0; $i < $cuantasR; $i++) {
      $cambiandoEstatus = \DB::UPDATE("UPDATE recepcionEquipos SET estatusEntrega=? WHERE idEquipos= ?",[$regresarEstatus[$i]->estatusAnteriorEquipo,$regresarEstatus[$i]->idEquipos]);
    } 

    
      
    $versionActual = \DB::table('cotizaciones')->select('numCotizacion','cotizacionOriginal','estatus')->where('idCotizacion','=',$idCotizacion)->get();    
    $versionAnterior = $versionActual[0]->numCotizacion - 1;
    $cotizacionParaActivar = \DB::table('cotizaciones')->select('idCotizacion')->where('numCotizacion','=',$versionAnterior)->where('cotizacionOriginal','=',$versionActual[0]->cotizacionOriginal)->get();
    
    if($versionAnterior > 0){
      
      $activandoCotizacionAnterior = \DB::UPDATE("UPDATE cotizaciones SET clonActual = 'Si', estatus=? WHERE idCotizacion = ? ",[$versionActual[0]->estatus,$cotizacionParaActivar[0]->idCotizacion]);
      $activandoEquiposOriginales = \DB::UPDATE("UPDATE detallereparaciones SET clonEquiposActivo = 'Si' WHERE idCotizacion = ? AND tipoCotizacion='reparacion'",[$idCotizacion]);
      
    }

    $refaccionesExistentes =DB::table('refaccionesEnCotizacion')
    ->select('refaccionesEnCotizacion.idParte')
    ->where('refaccionesEnCotizacion.idCotizacion', '=',$idCotizacion)
    ->groupBy('refaccionesEnCotizacion.idParte')
    ->get();
    foreach($refaccionesExistentes as $rE){
      $borrandoRefacciones= \DB::delete("DELETE FROM refaccionesreparacionpartes WHERE idParte=?",[$rE->idParte]);
    }    
    $borrandoEquiposOriginales = \DB::delete("DELETE FROM detallereparaciones WHERE idCotizacion = ? AND tipoCotizacion='reparacion'",[$idCotizacion]);
    $borrandoRefaccionesEnCotizacion = \DB::delete("DELETE FROM refaccionesEnCotizacion WHERE idCotizacion = ? ",[$idCotizacion]);
    $borrandoCotiacion= \DB::delete("DELETE FROM cotizaciones WHERE idCotizacion=?",[$idCotizacion]);

  }

  public function guardarEditarCotizacion(Request $request){
    $idCotizacion = $request-> idCotizacion;
    $estatus = $request-> estatus;
    $montoN = $request-> montoTotal;
    $ordenCompra = $request-> ordenCompra;


    $idc = $request-> idc;
    $empresa= \DB::select("SELECT empresaPertenece FROM clientes WHERE idc = ?",[$idc]);

    $file = $request->file('archivoCotizacion');
    if($file!=""){
      $ldate = date('Ymd_His_');
      $img = $file->getClientOriginalName();
      $img2 = $ldate.$img;
      \Storage::disk('local')->put($img2, \File::get($file));
    }

    $cotizacion = cotizaciones::find($idCotizacion);

    if($file!=""){$cotizacion-> archivoCotizacion=$img2;}
    $cotizacion-> idCotizacion = $idCotizacion;
    $cotizacion-> idu = $request-> idu;
    $cotizacion-> nombreUsuario = $request-> nombreUsuario;
    $cotizacion-> idc = $request-> idc;
    $cotizacion-> fechaCotizacion = $request-> fechaCotizacion;
    $cotizacion-> numeroCotizacion = $request-> numeroCotizacion;
    $cotizacion-> dirigidoA = $request-> dirigidoA;
    $cotizacion-> conCopia = $request-> conCopia;
    $cotizacion-> estatus = $estatus;
    $cotizacion-> montoTotal = ROUND($request-> montoTotal,2);
    $cotizacion-> tipoMoneda = $request-> tipoMoneda;
    $cotizacion-> cambio = $request-> cambio;
    // $cotizacion-> numCotizacion = $request-> numCotizacion;
    if ($estatus == "Cancelada") {
        $cotizacion-> clonActual = 'No';

        $regresarEstatus = \DB::select("SELECT idEquipos,estatusAnteriorEquipo FROM detallereparaciones WHERE idCotizacion = ? ",[$idCotizacion]);
        $cuantasR = count($regresarEstatus);

        for ($i=0; $i < $cuantasR; $i++) {
          $cambiandoEstatus = \DB::UPDATE("UPDATE recepcionEquipos SET estatusEntrega=? WHERE idEquipos= ?",[$regresarEstatus[$i]->estatusAnteriorEquipo,$regresarEstatus[$i]->idEquipos]);
        }
        $desactivandoEquiposOriginales = \DB::UPDATE("UPDATE detallereparaciones SET clonEquiposActivo = 'No' WHERE idCotizacion = ? ",[$idCotizacion]);

        $refaccionesExistentes =DB::table('refaccionesEnCotizacion')
        ->select('refaccionesEnCotizacion.idParte')
        ->where('refaccionesEnCotizacion.idCotizacion', '=',$idCotizacion)
        ->groupBy('refaccionesEnCotizacion.idParte')
        ->get();
        foreach($refaccionesExistentes as $rE){
          $borrarRefacciones= \DB::delete("DELETE FROM refaccionesreparacionpartes WHERE idParte=?",[$rE->idParte]);
        }

    }else{
      $cotizacion-> clonActual = 'Si';
    }
    $cotizacion-> activo='Si';
    $cotizacion-> check1 = $request-> check1;
    $cotizacion-> textoCheck1 = $request-> textoCheck1;
    $cotizacion-> check2 = $request-> check2;
    $cotizacion-> textoCheck2 = $request-> textoCheck2;
    $cotizacion-> check3 = $request-> check3;
    $cotizacion-> textoCheck3 = $request-> textoCheck3;
    $cotizacion-> check4 = $request-> check4;
    $cotizacion-> textoCheck4 = $request-> textoCheck4;
    $cotizacion-> check5 = $request-> check5;
    $cotizacion-> textoCheck5 = $request-> textoCheck5;
    $cotizacion-> check6 = $request-> check6;
    $cotizacion-> textoCheck6 = $request-> textoCheck6;
    $cotizacion-> check7 = $request-> check7;
    $cotizacion-> textoCheck7 = $request-> textoCheck7;
    $cotizacion-> check8 = $request-> check8;
    $cotizacion-> textoCheck8 = $request-> textoCheck8;
    $cotizacion-> save();

    //LIMPIANDO REFACCIONES EN LAS PRATES DE EQUIPOS POR SI EL ESTATUS AUN NO ES ACEPTADO CON ORDEN DE COMPRA
    $refaccionesExistentes =DB::table('refaccionesEnCotizacion')
    ->select('refaccionesEnCotizacion.idParte')
    ->where('refaccionesEnCotizacion.idCotizacion', '=',$idCotizacion)
    ->groupBy('refaccionesEnCotizacion.idParte')
    ->get();
    foreach($refaccionesExistentes as $rE){
      $borrandoRefacciones= \DB::delete("DELETE FROM refaccionesreparacionpartes WHERE idParte=?",[$rE->idParte]);
    }
    
    // Creando servicio en tabla facturas
    if ($estatus == "Aceptada con orden de compra") {

      $refaccionesExistentes =DB::table('refaccionesEnCotizacion')
      ->select('refaccionesEnCotizacion.idParte')
      ->where('refaccionesEnCotizacion.idCotizacion', '=',$idCotizacion)
      ->groupBy('refaccionesEnCotizacion.idParte')
      ->get();
      foreach($refaccionesExistentes as $rE){
        $borrarRefacciones= \DB::delete("DELETE FROM refaccionesreparacionpartes WHERE idParte=?",[$rE->idParte]);
      }
            
      $guardarRefacciones = refaccionesEnCotizacion::where('idCotizacion','=',$idCotizacion)->get();      
      
      foreach($guardarRefacciones as $gR)
        {refaccionesreparacionpartes::create([      
          'idParte' => $gR-> idParte,
          'idTipoRefacciones' => $gR-> idTipoRefacciones,
          'idPaquete' => $gR-> idPaquete,
          'idPartesVenta' => $gR-> idPartesVenta,
          'tipoProducto' => $gR-> tipoProducto,
          'precioPesos' => $gR-> precioPesos,
          'precioDolar' => $gR-> precioDolar,
          'apareceEnCoti' => $gR-> apareceEnCoti
        ]);
      }
      

      $consultandoSiYaTieneServicioAsignado = \DB::select("SELECT idFactura,ordenCompra FROM facturas WHERE tipoServicio !='Soporte Técnico' AND idCotizacion = ? ",[$idCotizacion]);
      $cuantosAsignados = count($consultandoSiYaTieneServicioAsignado);

      if($cuantosAsignados >= 1){ 
        $file = $request->file('archivo');    
        if($file!=""){
          $ldate = date('Ymd_His_');
          $img = $file->getClientOriginalName();
          $img2 = $ldate.$img;
          \Storage::disk('local')->put($img2, \File::get($file));
        }
        $fac = facturas::find($consultandoSiYaTieneServicioAsignado[0]->idFactura);
        if($file!=""){
          $actualizandoCotizacionEnFactura = \DB::UPDATE("UPDATE facturas SET archivo = ?
          WHERE idFactura = ?",[$img2,$consultandoSiYaTieneServicioAsignado[0]->idFactura]);
        }         
        $actualizandoCotizacionEnFactura = \DB::UPDATE("UPDATE facturas SET idCotizacion = ?, montoFactura=?, ordenCompra=? WHERE idFactura = ?",[$idCotizacion,$montoN,$consultandoSiYaTieneServicioAsignado[0]->ordenCompra,$consultandoSiYaTieneServicioAsignado[0]->idFactura]);
      }else{
		  
        $idServiciosig = facturas::withTrashed()->orderby('idServicios','desc')->take(1)->get();
		if (count($idServiciosig)>=1)
		{	
        $idServ = $idServiciosig[0]->idServicios+1;
		}
		else
		{$idServ= 1;
	     }
        $idc = $request-> idc;
        $diasDePago = \DB::select("SELECT diasDePago FROM clientes WHERE idc= $idc");

        $file = $request->file('archivo');
        if($file!=""){
          $archivo =  time()."_".$file->getClientOriginalName();
          \Storage::disk('local')->put($archivo,  \File::get($file));
        }else{
          $archivo ="Sin archivo";
        }

        $fac=new facturas;
        $fac-> idServicios = $idServ;
        $fac-> tipoServicio = "Reparaciones";
        $fac-> nombreEmpresa = $empresa[0]->empresaPertenece;
        // $fac-> fechaVencimiento = "0000-00-00";
        // $fac-> idb = $request-> idb;
        $fac-> montoFactura = ROUND($request-> montoTotal,2);
        $fac-> periodoPago = $diasDePago[0]->diasDePago;
        $fac-> numeroFactura = "-";
        // $fac-> fechaFactura = "0000-00-00";
        // $fac-> fechaPago = "0000-00-00";
        $fac-> tipoMoneda = $request-> tipoMoneda;
        $fac-> estatusEntrega = "Pendiente";
        $fac-> estatusPago = "Pendiente";
        $fac-> metodoPago = "PUE";
        $fac-> estatusPortal = "Pendiente en subir";
        $fac-> cashFlow = "No";
        $fac-> idc = $idc;
        $fac-> idSucursal = $request-> idSucursal;
        $fac-> idCotizacion = $idCotizacion;
        $fac-> ordenCompra = $request-> ordenCompra;
        $fac-> archivo = $archivo;
        $fac-> archivoFactura = "Sin archivo";
        $fac-> archivoPago = "Sin archivo";
        $fac-> xmlFactura = "Sin archivo";
        $fac-> archivoRemision = "Sin archivo";
        $fac-> archivoAdenda = "Sin archivo";
        $fac-> save();
		//prueba

        $idFactura = \DB::select("SELECT idFactura FROM facturas WHERE tipoServicio !='Soporte Técnico' AND idCotizacion= $idCotizacion");
        $asigDetalles = new asignacionesdetalles;
        $asigDetalles-> idFactura = $idFactura[0]->idFactura;
        $asigDetalles-> fecha = $request-> fechaCotizacion;
        $asigDetalles-> idu = $request-> idu;
        $asigDetalles->save();
      }

      $cambiarEstatus = \DB::select("SELECT idEquipos FROM detallereparaciones WHERE idCotizacion = ? ",[$idCotizacion]);
      $cuantasEquipos = count($cambiarEstatus);

      for ($i=0; $i < $cuantasEquipos; $i++) {
        $cambiandoEstatusEquipos = \DB::UPDATE("UPDATE recepcionEquipos SET estatusEntrega='Con OC' WHERE idEquipos= ?",[$cambiarEstatus[$i]->idEquipos]);
      }
    }

    $proceso='Modificación de cotizaciones';
    $mensaje="Cotización actualizada correctamente";

    return view ('mensajeCotizaciones')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);

  }

  public function reporteDeReparacionesAbajo(Request $request){
    $idEquipos = $request->get('idEquipos');
    $idPartesVenta = $request->get('idPartesVenta');    
    $tipoCotizacion = $request->get('tipoCotizacion');
    $partida = $request-> partida;
    $partidaSiguiente = $partida +1;

    if ($tipoCotizacion == "reparacion") {
      $estatusAnteriror = \DB::select("SELECT estatusEntrega FROM recepcionEquipos WHERE idEquipos=?",[$idEquipos]);
      $estatusAnteriror=$estatusAnteriror[0]->estatusEntrega;
    }

    $idCotizacion = $request->get('idCotizacion');
    $idSucursal = $request->get('idSucursal');
    $numeroCotizacion = $request->get('numeroCotizacion');
    $tipoMoneda = $request->get('tipoMoneda');
    $descuento = $request-> descuento;

    if ($tipoCotizacion == "reparacion") {
      $reparacionesExisten=\DB::select("SELECT COUNT(*) AS cuantos FROM detallereparaciones WHERE idEquipos = ? AND clonEquiposActivo = 'Si'",[$idEquipos]);
      $cuantos = $reparacionesExisten[0]->cuantos;
    }else{
      $reparacionesExisten=\DB::select("SELECT COUNT(*) AS cuantos FROM detallereparaciones WHERE idEquipos = ? AND clonEquiposActivo = 'Si'",[$idEquipos]);
      $cuantos = $reparacionesExisten[0]->cuantos;
    }

    // if($cuantos==0){

    $reparacion = new detallereparaciones;
    $reparacion-> partida = $partida;
    $reparacion-> tipoCotizacion = $request-> tipoCotizacion;
    $reparacion-> idEquipos = $request-> idEquipos;
    $reparacion-> idPartesVenta = $request-> idPartesVenta;
    $reparacion-> descripcion = $request-> descripcion;
    $reparacion-> skuEquivalente = $request-> skuEquivalente;
    $reparacion-> verSku = $request-> verSku;
    $reparacion-> modelo = $request-> modelo;
    if ($tipoCotizacion == "reparacion") {
      $reparacion-> estatusAnteriorEquipo = $estatusAnteriror;
    }
    if($tipoCotizacion != "reparacion"){
      $reparacion-> piezas = $request-> piezas;
    }else{
      $reparacion-> piezas = 1;
    }      
    $reparacion-> aplicaDescuento = $request-> aplicaDescuento;
    $reparacion-> diasPago = $request-> diasDePago;
    $reparacion-> idCotizacion = $request-> idCotizacion;
    $reparacion-> numeroCotizacion = $request-> numeroCotizacion;
    $reparacion-> semanasEntrega = $request-> semanasEntrega;
    $reparacion-> tipoMoneda = $request-> tipoMoneda;
    $reparacion-> notas = $request-> notas;
    $reparacion-> precioVentaOriginal = $request-> precioVenta;
    $reparacion-> montoEquipoInicial = $request-> montoEquipoInicial;
    if($tipoCotizacion != "reparacion"){
      //corregir el monto
      $reparacion-> montoEquipo = $request-> montoEquipoInicial;
    }else{
      $reparacion-> montoEquipo = $request-> montoEquipo;        
      $reparacion-> precioVentaOriginal = $request-> montoEquipoInicial;
    }
    if($descuento==''){
      $reparacion-> montoFinanciamiento = $request-> montoEquipo;
    }else{
      $reparacion-> montoFinanciamiento = $request-> montoFinanciamiento;
    }
    $reparacion-> tipoDescuento = $request-> tipoDescuento;
    $reparacion-> descuento = $request-> descuento;
    $reparacion-> clonEquiposActivo = 'Si';
    $reparacion-> agregadoReciente = 'No';
    $reparacion-> save();

    if ($tipoCotizacion == "reparacion") {
      $cambiandoEstatus = \DB::UPDATE("UPDATE recepcionEquipos SET estatusEntrega='Cotizado' WHERE idEquipos= ?",[$idEquipos]);
    }

    $equiposReparados = \DB::select("SELECT dr.tipoCotizacion,dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion, dr.descripcion,dr.modelo,dr.numeroCotizacion,dr.notas,re.GCMid,re.complementoGCMid, dr.semanasEntrega, FORMAT(dr.montoEquipo,2) AS montoEquipo,
    FORMAT(dr.montoFinanciamiento,2) AS montoFinanciamiento,dr.descuento,dr.tipoDescuento
    FROM detallereparaciones AS dr
    INNER JOIN recepcionEquipos AS re ON re.idEquipos = dr.idEquipos
    WHERE dr.idCotizacion=?",[$idCotizacion]);

    $equiposNuevosYreparados = \DB::select("SELECT *
    FROM detallereparaciones AS dr
    WHERE dr.idCotizacion=?",[$idCotizacion]);
    $cuantasReparaciones = count($equiposNuevosYreparados);
    
    $suma = \DB::select("SELECT FORMAT(SUM(dr.montoFinanciamiento),2) AS sumaTotal
    FROM detallereparaciones AS dr
    WHERE dr.idCotizacion=?",[$idCotizacion]);

    $datosEquiposNuevos = \DB::select("SELECT pv.nombreRefaccion,pv.numeroParte,dr.tipoCotizacion,dr.idEquipos,dr.idPartesVenta
    FROM detallereparaciones AS dr
    INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idPartesVenta
    WHERE dr.idCotizacion=? AND tipoCotizacion !='reparacion'",[$idCotizacion]);

    $datosEquipoCotizado = \DB::select("SELECT re.idEquipos, re.serie,
    CONCAT (re.serie,' - ',te.tipoEquipo,' - ',se.subtipoEquipo) AS equipo
    FROM recepcionEquipos AS re
    INNER JOIN tipoequipos AS te ON te.idTipoEquipo= re.idTipoEquipo
    INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= re.idSubtipoEquipo
    WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones)");


    // $montoTotal= \DB::select("SELECT idCotizacion, ROUND(SUM(montoFinanciamiento),2) AS montoTotal FROM detallereparaciones WHERE idCotizacion = ? GROUP BY idCotizacion",[$idCotizacion]);
    $montoTotal= \DB::select("SELECT idCotizacion, SUM(montoFinanciamiento) AS montoTotal FROM detallereparaciones WHERE idCotizacion = ? GROUP BY idCotizacion",[$idCotizacion]);
    $noEquipos = \DB::select("SELECT idCotizacion, COUNT(idCotizacion) AS noEquipos FROM detallereparaciones WHERE idCotizacion = ? GROUP BY idCotizacion",[$idCotizacion]);

    // se agregó 'AND idEquipos IS NOT NULL' ya que contaba los id nullos y con esa condicion ya no toma los idEquipo nullos en el conteo para determinar que un idequipo esta repetido
    $repetidos= \DB::select("SELECT idEquipos,tipoCotizacion FROM detallereparaciones WHERE idCotizacion = ? AND idEquipos IS NOT NULL GROUP BY idEquipos,tipoCotizacion HAVING COUNT(*)>1;",[$idCotizacion]);
    $cuantosRepetidos = count($repetidos);
    if($cuantosRepetidos > 0){      
        $siRepetidos = \DB::UPDATE("UPDATE cotizaciones SET repetidos = 'si' WHERE idCotizacion= ?",[$idCotizacion]);        
      // $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizaciones SET montoTotal = '0', noEquipos=? WHERE idCotizacion= ?",[$noEquipos[0]->noEquipos,$idCotizacion]);
      $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizaciones SET montoTotal = ?, noEquipos=? WHERE idCotizacion= ?",[$montoTotal[0]->montoTotal,$noEquipos[0]->noEquipos,$idCotizacion]);
    }else{
      $siRepetidos = \DB::UPDATE("UPDATE cotizaciones SET repetidos = 'no' WHERE idCotizacion= ?",[$idCotizacion]);
      $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizaciones SET montoTotal = ?, noEquipos=? WHERE idCotizacion= ?",[$montoTotal[0]->montoTotal,$noEquipos[0]->noEquipos,$idCotizacion]);
    }

    // return json_decode();
    return response()->json(200);
    // $estatusCotizacion = \DB::select("SELECT estatus FROM cotizaciones WHERE idCotizacion = ?",[$idCotizacion]);

    // return view ('reporteReparacionesAbajo')
    // ->with('partidaSiguiente',$partidaSiguiente)
    // ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
    // ->with('equiposReparados',$equiposReparados)
    // ->with('datosEquiposNuevos',$datosEquiposNuevos)
    // ->with('numeroCotizacion',$numeroCotizacion)
    // ->with('idSucursal',$idSucursal)
    // ->with('tipoMoneda',$tipoMoneda)
    // ->with('idEquipos',$idEquipos)
    // ->with('cuantos',$cuantos)
    // ->with('suma',$suma[0]->sumaTotal)
    // ->with('tipoCotizacion',$tipoCotizacion)
    // ->with('datosEquipoCotizado',$datosEquipoCotizado)
    // ->with('cuantasReparaciones',$cuantasReparaciones)
    // ->with('estatusCotizacion',$estatusCotizacion[0]->estatus);
  }

  public function editarEquiposEnCotizacion(Request $request){
    $idEquipos = $request->get('idEquipos');
    $tipoCotizacion = $request->get('tipoCotizacion');
    if ($tipoCotizacion == "reparacion") {
      $estatusAnteriror = \DB::select("SELECT estatusEntrega FROM recepcionEquipos WHERE idEquipos=?",[$idEquipos]);    
      $estatusAnteriror=$estatusAnteriror[0]->estatusEntrega;
    }
    $idCotizacion = $request->get('idCotizacion');
    $idSucursal = $request->get('idSucursal');
    $tipoMoneda = $request->get('tipoMoneda');
    $numeroCotizacion = $request->get('numeroCotizacion');
    $descuento = $request-> descuento;
    $partida = $request-> partida;
    $partidaSiguiente = $partida +1;


    $reparacionesExisten=\DB::select("SELECT * FROM detallereparaciones 
    WHERE idEquipos = ? AND clonEquiposActivo='Si'",[$idEquipos]);
    $cuantos = count($reparacionesExisten);

    // if($cuantos==0){

    $reparacion = new detallereparaciones;
    $reparacion-> partida = $partida;
    $reparacion-> tipoCotizacion = $request-> tipoCotizacion;
    $reparacion-> idEquipos = $request-> idEquipos;
    $reparacion-> descripcion = $request-> descripcion;
    $reparacion-> modelo = $request-> modelo;
    if ($tipoCotizacion == "reparacion") {
      $reparacion-> estatusAnteriorEquipo = $estatusAnteriror;
    }
    if($tipoCotizacion != "reparacion"){
      $reparacion-> piezas = $request-> piezas;
    }else{
      $reparacion-> piezas = 1;
    }
    $reparacion-> idCotizacion = $request-> idCotizacion;
    $reparacion-> numeroCotizacion = $request-> numeroCotizacion;
    $reparacion-> semanasEntrega = $request-> semanasEntrega;
    $reparacion-> tipoMoneda = $request-> tipoMoneda;
    $reparacion-> notas = $request-> notas;
    $reparacion-> precioVentaOriginal = $request-> precioVenta;
    $reparacion-> montoEquipoInicial = $request-> montoEquipoInicial;
    if($tipoCotizacion != "reparacion"){
      //corregir el monto
      $reparacion-> montoEquipo = $request-> montoEquipoInicial;
    }else{
      $reparacion-> montoEquipo = $request-> montoEquipo;        
    }
    if($descuento==''){
      $reparacion-> montoFinanciamiento = $request-> montoEquipo;
    }else{
      $reparacion-> montoFinanciamiento = $request-> montoFinanciamiento;
    }
    $reparacion-> tipoDescuento = $request-> tipoDescuento;
    $reparacion-> descuento = $request-> descuento;
    $reparacion-> clonEquiposActivo ='Si';
    $reparacion-> agregadoReciente = 'Si';
    $reparacion-> save();

    if ($tipoCotizacion == "reparacion") {
      $cambiandoEstatus = \DB::UPDATE("UPDATE recepcionEquipos SET estatusEntrega='Cotizado' WHERE idEquipos= ?",[$idEquipos]);
    }
    
    $equiposReparados = \DB::select("SELECT dr.tipoCotizacion,dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion, dr.descripcion,dr.modelo,dr.numeroCotizacion,dr.notas,re.GCMid,re.complementoGCMid, dr.semanasEntrega, FORMAT(dr.montoEquipo,2) AS montoEquipo,
    FORMAT(dr.montoFinanciamiento,2) AS montoFinanciamiento,dr.descuento,dr.tipoDescuento
    FROM detallereparaciones AS dr
    INNER JOIN recepcionEquipos AS re ON re.idEquipos = dr.idEquipos
    WHERE numeroCotizacion=?",[$numeroCotizacion]);

    $equiposNuevosYreparados = \DB::select("SELECT *
    FROM detallereparaciones AS dr
    WHERE numeroCotizacion=?",[$numeroCotizacion]);
    $cuantasReparaciones = count($equiposNuevosYreparados);

    $suma = \DB::select("SELECT FORMAT(SUM(dr.montoFinanciamiento),2) AS sumaTotal
    FROM detallereparaciones AS dr
    WHERE numeroCotizacion=?",[$numeroCotizacion]);

    $datosEquiposNuevos = \DB::select("SELECT pv.nombreRefaccion,pv.numeroParte,dr.tipoCotizacion,dr.idEquipos,dr.idPartesVenta
    FROM detallereparaciones AS dr
    INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idPartesVenta
    WHERE numeroCotizacion=? AND tipoCotizacion !='reparacion'",[$numeroCotizacion]);

    $datosEquipoCotizado = \DB::select("SELECT re.idEquipos, re.serie,
    CONCAT (re.serie,' - ',te.tipoEquipo,' - ',se.subtipoEquipo) AS equipo
    FROM recepcionEquipos AS re
    INNER JOIN tipoequipos AS te ON te.idTipoEquipo= re.idTipoEquipo
    INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= re.idSubtipoEquipo
    WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones)");
    
    $estatusCotizacion = \DB::select("SELECT estatus FROM cotizaciones WHERE idCotizacion = ?",[$idCotizacion]);

    return view ('reporteReparacionesAbajo')
    ->with('partidaSiguiente',$partidaSiguiente)
    ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
    ->with('equiposReparados',$equiposReparados)
    ->with('datosEquiposNuevos',$datosEquiposNuevos)
    ->with('numeroCotizacion',$numeroCotizacion)
    ->with('idSucursal',$idSucursal)
    ->with('tipoCotizacion',$tipoCotizacion)
    ->with('tipoMoneda',$tipoMoneda)
    ->with('idEquipos',$idEquipos)
    ->with('cuantos',$cuantos)
    ->with('suma',$suma[0]->sumaTotal)
    ->with('datosEquipoCotizado',$datosEquipoCotizado)
    ->with('cuantasReparaciones',$cuantasReparaciones)
    ->with('estatusCotizacion',$estatusCotizacion[0]->estatus);

    // }else{
    //   $rep = \DB::select("SELECT dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion,dr.descripcion,dr.numeroCotizacion,re.GCMid,re.complementoGCMid, dr.semanasEntrega, FORMAT(dr.montoEquipo,2) AS montoEquipo,
    //   FORMAT(dr.montoFinanciamiento,2) AS montoFinanciamiento,dr.descuento,dr.tipoDescuento
    //   FROM detallereparaciones AS dr
    //   INNER JOIN recepcionEquipos AS re ON re.idEquipos = dr.idEquipos
    //   WHERE numeroCotizacion=?",[$numeroCotizacion]);
    //   $cuantasReparaciones = count($rep);

    //   $datosEquipoCotizado = \DB::select("SELECT re.idEquipos, re.serie,
    //   CONCAT (re.serie,' - ',te.tipoEquipo,' - ',se.subtipoEquipo) AS equipo
    //   FROM recepcionEquipos AS re
    //   INNER JOIN tipoequipos AS te ON te.idTipoEquipo= re.idTipoEquipo
    //   INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= re.idSubtipoEquipo
    //   WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones)");

    //   return view ('reporteReparacionesAbajo')
    //   ->with('numeroCotizacion',$numeroCotizacion)
    //   ->with('idSucursal',$idSucursal)
    //   ->with('tipoMoneda',$tipoMoneda)
    //   ->with('idEquipos',$idEquipos)
    //   ->with('cuantos',$cuantos)
    //   ->with('rep',$rep)
    //   ->with('datosEquipoCotizado',$datosEquipoCotizado)
    //   ->with('cuantasReparaciones',$cuantasReparaciones);
    // }
  }

  public function guardarModificacionCotizacion(Request $request){
    $idCotizacion = $request-> idCotizacion;
    $estatus = $request-> estatus;
    $ordenCompra = $request-> ordenCompra;

    $idc = $request-> idc;
    $empresa= \DB::select("SELECT empresaPertenece FROM clientes WHERE idc = ?",[$idc]);

    $file = $request->file('archivoCotizacion');
    if($file!=""){
      $ldate = date('Ymd_His_');
      $img = $file->getClientOriginalName();
      $img2 = $ldate.$img;
      \Storage::disk('local')->put($img2, \File::get($file));
    }

    $cotizacion = cotizaciones::find($idCotizacion);

    if($file!=""){$cotizacion-> archivoCotizacion=$img2;}
    $cotizacion-> idu = $request-> idu;
    $cotizacion-> idc = $request-> idc;
    $cotizacion-> fechaCotizacion = $request-> fechaCotizacion;
    // $cotizacion-> idSucursal = $request-> idSucursal;
    $cotizacion-> numeroCotizacion = $request-> numeroCotizacion;
    $cotizacion-> dirigidoA = $request-> dirigidoA;
    $cotizacion-> conCopia = $request-> conCopia;
    $cotizacion-> estatus = $estatus;
    $cotizacion-> montoTotal = ROUND($request-> montoTotal,2);
    $cotizacion-> tipoMoneda = $request-> tipoMoneda;
    $cotizacion-> cambio = $request-> cambio;

    // $cotizacion-> numCotizacion = $request-> numCotizacion;
    if ($estatus == "Cancelada") {
      $cotizacion-> clonActual = 'No';

      $regresarEstatus = \DB::select("SELECT idEquipos,estatusAnteriorEquipo FROM detallereparaciones WHERE idCotizacion = ? ",[$idCotizacion]);
      $cuantasR = count($regresarEstatus);

      for ($i=0; $i < $cuantasR; $i++) {
        $cambiandoEstatus = \DB::UPDATE("UPDATE recepcionEquipos SET estatusEntrega=? WHERE idEquipos= ?",[$regresarEstatus[$i]->estatusAnteriorEquipo,$regresarEstatus[$i]->idEquipos]);
      }
      $desactivandoEquiposOriginales = \DB::UPDATE("UPDATE detallereparaciones SET clonEquiposActivo = 'No' WHERE idCotizacion = ? ",[$idCotizacion]);

      $refaccionesExistentes =DB::table('refaccionesEnCotizacion')
      ->select('refaccionesEnCotizacion.idParte')
      ->where('refaccionesEnCotizacion.idCotizacion', '=',$idCotizacion)
      ->groupBy('refaccionesEnCotizacion.idParte')
      ->get();
      foreach($refaccionesExistentes as $rE){
        $borrarRefacciones= \DB::delete("DELETE FROM refaccionesreparacionpartes WHERE idParte=?",[$rE->idParte]);
      }

    }else{
      $cotizacion-> clonActual = 'Si';
    }
    $cotizacion-> activo='Si';
    $cotizacion-> check1 = $request-> check1;
    $cotizacion-> textoCheck1 = $request-> textoCheck1;
    $cotizacion-> check2 = $request-> check2;
    $cotizacion-> textoCheck2 = $request-> textoCheck2;
    $cotizacion-> check3 = $request-> check3;
    $cotizacion-> textoCheck3 = $request-> textoCheck3;
    $cotizacion-> check4 = $request-> check4;
    $cotizacion-> textoCheck4 = $request-> textoCheck4;
    $cotizacion-> check5 = $request-> check5;
    $cotizacion-> textoCheck5 = $request-> textoCheck5;
    $cotizacion-> check6 = $request-> check6;
    $cotizacion-> textoCheck6 = $request-> textoCheck6;
    $cotizacion-> check7 = $request-> check7;
    $cotizacion-> textoCheck7 = $request-> textoCheck7;
    $cotizacion-> check8 = $request-> check8;
    $cotizacion-> textoCheck8 = $request-> textoCheck8;
    $cotizacion-> save();

    // $idSiguiente = \DB::table('cotizaciones')->max('idCotizacion');//estas dos lineas corrigen el idCotizacion en detllereparaciones para relacionarlas correctamente
    // $corrigiendoidCotizacionenTablaDetalle = \DB::UPDATE("UPDATE detallereparaciones SET idCotizacion = ? WHERE numeroCotizacion= ?",[$idSiguiente,$numeroCotizacion]);

    $montoTotal= \DB::select("SELECT idCotizacion, ROUND(SUM(montoFinanciamiento),2) AS montoTotal FROM detallereparaciones WHERE idCotizacion = ? GROUP BY idCotizacion",[$idCotizacion]);

    $noEquipos = \DB::select("SELECT idCotizacion, COUNT(idCotizacion) AS noEquipos FROM detallereparaciones WHERE idCotizacion = ? GROUP BY idCotizacion",[$idCotizacion]);

    $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizaciones SET montoTotal = ?, noEquipos=? WHERE idCotizacion= ?",[$montoTotal[0]->montoTotal,$noEquipos[0]->noEquipos,$idCotizacion]);

    // se agregó 'AND idEquipos IS NOT NULL' ya que contaba los id nullos y con esa condicion ya no toma los idEquipo nullos en el conteo para determinar que un idequipo esta repetido
    $repetidos= \DB::select("SELECT idEquipos,tipoCotizacion FROM detallereparaciones WHERE idCotizacion = ? AND idEquipos IS NOT NULL GROUP BY idEquipos,tipoCotizacion HAVING COUNT(*)>1;",[$idCotizacion]);
    $cuantosRepetidos = count($repetidos);
    if($cuantosRepetidos > 0){      
      $siRepetidos = \DB::UPDATE("UPDATE cotizaciones SET repetidos = 'si' WHERE idCotizacion= ?",[$idCotizacion]);
    // $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizaciones SET montoTotal = '0', noEquipos=? WHERE idCotizacion= ?",[$noEquipos[0]->noEquipos,$idCotizacion]);
    $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizaciones SET montoTotal = ?, noEquipos=? WHERE idCotizacion= ?",[$montoTotal[0]->montoTotal,$noEquipos[0]->noEquipos,$idCotizacion]);
    }else{
      $siRepetidos = \DB::UPDATE("UPDATE cotizaciones SET repetidos = 'no' WHERE idCotizacion= ?",[$idCotizacion]);
      $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizaciones SET montoTotal = ?, noEquipos=? WHERE idCotizacion= ?",[$montoTotal[0]->montoTotal,$noEquipos[0]->noEquipos,$idCotizacion]);
    }

    // return redirect()->route('cotizaciones');//retorna al metodo o ruta cotizaciones

    //LIMPIANDO REFACCIONES EN LAS PRATES DE EQUIPOS POR SI EL ESTATUS AUN NO ES ACEPTADO CON ORDEN DE COMPRA
    $refaccionesExistentes =DB::table('refaccionesEnCotizacion')
    ->select('refaccionesEnCotizacion.idParte')
    ->where('refaccionesEnCotizacion.idCotizacion', '=',$idCotizacion)
    ->groupBy('refaccionesEnCotizacion.idParte')
    ->get();
    foreach($refaccionesExistentes as $rE){
      $borrandoRefacciones= \DB::delete("DELETE FROM refaccionesreparacionpartes WHERE idParte=?",[$rE->idParte]);
    }

    if ($estatus == "Aceptada con orden de compra") {

      $refaccionesExistentes =DB::table('refaccionesEnCotizacion')
      ->select('refaccionesEnCotizacion.idParte')
      ->where('refaccionesEnCotizacion.idCotizacion', '=',$idCotizacion)
      ->groupBy('refaccionesEnCotizacion.idParte')
      ->get();
      foreach($refaccionesExistentes as $rE){
        $borrarRefacciones= \DB::delete("DELETE FROM refaccionesreparacionpartes WHERE idParte=?",[$rE->idParte]);
      }
            
      $guardarRefacciones = refaccionesEnCotizacion::where('idCotizacion','=',$idCotizacion)->get();      
      
      foreach($guardarRefacciones as $gR)
        {refaccionesreparacionpartes::create([      
          'idParte' => $gR-> idParte,
          'idTipoRefacciones' => $gR-> idTipoRefacciones,
          'idPaquete' => $gR-> idPaquete,
          'idPartesVenta' => $gR-> idPartesVenta,
          'tipoProducto' => $gR-> tipoProducto,
          'precioPesos' => $gR-> precioPesos,
          'precioDolar' => $gR-> precioDolar,
          'apareceEnCoti' => $gR-> apareceEnCoti
        ]);
      }

      $idCotizacionAnterior = $request-> idCotizacionAnterior;
      $consultandoSiYaTieneServicioAsignado = \DB::select("SELECT idFactura,ordenCompra FROM facturas WHERE tipoServicio !='Soporte Técnico' AND idCotizacion = ? ",[$idCotizacionAnterior]);
      $cuantosAsignados = count($consultandoSiYaTieneServicioAsignado);

      if($cuantosAsignados >= 1){
        // if($cuantosRepetidos > 0){
        //   $montoTotal = "0";
        // }else{
          $montoTotal= $montoTotal[0]->montoTotal;
        // }        

        $file = $request->file('archivo');    
        if($file!=""){
          $ldate = date('Ymd_His_');
          $img = $file->getClientOriginalName();
          $img2 = $ldate.$img;
          \Storage::disk('local')->put($img2, \File::get($file));
        }
        $fac = facturas::find($consultandoSiYaTieneServicioAsignado[0]->idFactura);
        if($file!=""){
          $actualizandoCotizacionEnFactura = \DB::UPDATE("UPDATE facturas SET archivo = ?
          WHERE idFactura = ?",[$img2,$consultandoSiYaTieneServicioAsignado[0]->idFactura]);
        }         
        $actualizandoCotizacionEnFactura = \DB::UPDATE("UPDATE facturas SET idCotizacion = ?, montoFactura=?, ordenCompra=? WHERE idFactura = ?",[$idCotizacion,$montoTotal,$consultandoSiYaTieneServicioAsignado[0]->ordenCompra,$consultandoSiYaTieneServicioAsignado[0]->idFactura]);

      }else{
        $idServiciosig = facturas::withTrashed()->orderby('idServicios','desc')->take(1)->get();
        $idServ = $idServiciosig[0]->idServicios+1;
        $idc = $request-> idc;
        $diasDePago = \DB::select("SELECT diasDePago FROM clientes WHERE idc= $idc");

        $file = $request->file('archivo');
        if($file!=""){
          $archivo =  time()."_".$file->getClientOriginalName();
          \Storage::disk('local')->put($archivo,  \File::get($file));
        }else{
          $archivo ="Sin archivo";
        }

        $fac=new facturas;
        $fac-> idServicios = $idServ;
        $fac-> tipoServicio = "Reparaciones";
        $fac-> nombreEmpresa = $empresa[0]->empresaPertenece;
        // $fac-> fechaVencimiento = "0000-00-00";
        // $fac-> idb = $request-> idb;
        // if($cuantosRepetidos > 0){
        //   $fac-> montoFactura = "0";
        // }else{
          $fac-> montoFactura = $montoTotal[0]->montoTotal;
        // }
        $fac-> periodoPago = $diasDePago[0]->diasDePago;
        $fac-> numeroFactura = "-";
        // $fac-> fechaFactura = "0000-00-00";
        // $fac-> fechaPago = "0000-00-00";
        $fac-> tipoMoneda = $request-> tipoMoneda;
        $fac-> estatusEntrega = "Pendiente";
        $fac-> estatusPago = "Pendiente";
        $fac-> metodoPago = "PUE";
        $fac-> estatusPortal = "Pendiente en subir";
        $fac-> cashFlow = "No";
        $fac-> idc = $idc;
        $fac-> idSucursal = $request-> idSucursal;
        $fac-> idCotizacion = $idCotizacion;
        $fac-> ordenCompra = $request-> ordenCompra;
        $fac-> archivo = $archivo;
        $fac-> archivoFactura = "Sin archivo";
        $fac-> archivoPago = "Sin archivo";
        $fac-> xmlFactura = "Sin archivo";
        $fac-> archivoRemision = "Sin archivo";
        $fac-> archivoAdenda = "Sin archivo";
        $fac-> save();

        $idFactura = \DB::select("SELECT idFactura FROM facturas WHERE tipoServicio !='Soporte Técnico' AND idCotizacion= $idCotizacion");
        $asigDetalles = new asignacionesdetalles;
        $asigDetalles-> idFactura = $idFactura[0]->idFactura;
        $asigDetalles-> fecha = $request-> fechaCotizacion;
        $asigDetalles-> idu = $request-> idu;
        $asigDetalles->save();
      }

      $cambiarEstatus = \DB::select("SELECT idEquipos FROM detallereparaciones WHERE idCotizacion = ? ",[$idCotizacion]);
      $cuantasEquipos = count($cambiarEstatus);
      for ($i=0; $i < $cuantasEquipos; $i++) {
        $cambiandoEstatusEquipos = \DB::UPDATE("UPDATE recepcionEquipos SET estatusEntrega='Con OC' WHERE idEquipos= ?",[$cambiarEstatus[$i]->idEquipos]);
      }
    }

    $proceso='Actualización de cotizaciones';
    $mensaje="Cotización actualizada correctamente";

    return view ('mensajeCotizaciones')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);

  }

  public function borrarReparacion(Request $request){
    $idCotizacion = $request->idCotizacionB;
    $partida = $request->partidaBorrar;
    $tipoCotizacion = $request->get('tipoCotizacionB');
    $idEquipos = $request->get('idEquiposB');
    $idRepCotizacion = $request->idRepCotizacion;
    $idSucursal = $request->get('idSucursal');
    $tipoMoneda = $request->get('tipoMonedaB');
    $numeroCotizacion = $request->get('numeroCotizacionB');

    if ($tipoCotizacion == "reparacion") {
      $regresarEstatus = \DB::select("SELECT estatusAnteriorEquipo FROM detallereparaciones WHERE idEquipos=?",[$idEquipos]);
      $regresarEstatus=$regresarEstatus[0]->estatusAnteriorEquipo;
      $cambiandoEstatus = \DB::UPDATE("UPDATE recepcionEquipos SET estatusEntrega=? WHERE idEquipos= ?",[$regresarEstatus,$idEquipos]);
    }

    $borro= \DB::delete("DELETE FROM detallereparaciones WHERE idRepCotizacion=?",[$idRepCotizacion]);
    $borrarRefacciones= \DB::delete("DELETE FROM refaccionesEnCotizacion WHERE idCotizacion=? AND partida = ?",[$idCotizacion,$partida]);

    // $equiposReparados = \DB::select("SELECT dr.tipoCotizacion,dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion, dr.descripcion,dr.modelo,dr.numeroCotizacion,dr.notas,re.GCMid,re.complementoGCMid, dr.semanasEntrega, FORMAT(dr.montoEquipo,2) AS montoEquipo,
    // FORMAT(dr.montoFinanciamiento,2) AS montoFinanciamiento,dr.descuento,dr.tipoDescuento
    // FROM detallereparaciones AS dr
    // INNER JOIN recepcionEquipos AS re ON re.idEquipos = dr.idEquipos
    // WHERE numeroCotizacion=?",[$numeroCotizacion]);

    // $equiposNuevosYreparados = \DB::select("SELECT *
    // FROM detallereparaciones AS dr
    // WHERE numeroCotizacion=?",[$numeroCotizacion]);
    // $cuantasReparaciones = count($equiposNuevosYreparados);

    // $datosEquiposNuevos = \DB::select("SELECT pv.nombreRefaccion,pv.numeroParte,dr.tipoCotizacion,dr.idEquipos,dr.idPartesVenta
    // FROM detallereparaciones AS dr
    // INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idPartesVenta
    // WHERE numeroCotizacion=? AND tipoCotizacion !='reparacion'",[$numeroCotizacion]);

    // $datosEquipoCotizado = \DB::select("SELECT re.idEquipos,re.serie,
    // CONCAT (re.serie,' - ',te.tipoEquipo,' - ',se.subtipoEquipo) AS equipo
    // FROM recepcionEquipos AS re
    // INNER JOIN tipoequipos AS te ON te.idTipoEquipo= re.idTipoEquipo
    // INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= re.idSubtipoEquipo
    // WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones)");

    if ($tipoCotizacion == "reparacion") {
      $reparacionesExisten=\DB::select("SELECT COUNT(*) AS cuantos FROM detallereparaciones WHERE idRepCotizacion = ? ",[$idRepCotizacion]);
      $cuantos = $reparacionesExisten[0]->cuantos;
    }else{
      $reparacionesExisten=\DB::select("SELECT COUNT(*) AS cuantos FROM detallereparaciones WHERE idRepCotizacion = ? ",[$idRepCotizacion]);
      $cuantos = $reparacionesExisten[0]->cuantos;
    }

    // $suma = \DB::select("SELECT FORMAT(SUM(dr.montoFinanciamiento),2) AS sumaTotal
    // FROM detallereparaciones AS dr
    // WHERE numeroCotizacion=?",[$numeroCotizacion]);

    // $partidaSiguiente = \DB::table('detallereparaciones')->where('idCotizacion','=',$idCotizacion)->max('partida') + 1;


    $montoTotal= \DB::select("SELECT idCotizacion, ROUND(SUM(montoFinanciamiento),2) AS montoTotal FROM detallereparaciones WHERE idCotizacion = ? GROUP BY idCotizacion",[$idCotizacion]);
    $noEquipos = \DB::select("SELECT idCotizacion, COUNT(idCotizacion) AS noEquipos FROM detallereparaciones WHERE idCotizacion = ? GROUP BY idCotizacion",[$idCotizacion]);

    if (count($noEquipos) >=1) {

      // se agregó 'AND idEquipos IS NOT NULL' ya que contaba los id nullos y con esa condicion ya no toma los idEquipo nullos en el conteo para determinar que un idequipo esta repetido
      $repetidos= \DB::select("SELECT idEquipos,tipoCotizacion FROM detallereparaciones WHERE idCotizacion = ? AND idEquipos IS NOT NULL GROUP BY idEquipos,tipoCotizacion HAVING COUNT(*)>1;",[$idCotizacion]);
      $cuantosRepetidos = count($repetidos);
      if($cuantosRepetidos > 0){      
        $siRepetidos = \DB::UPDATE("UPDATE cotizaciones SET repetidos = 'si' WHERE idCotizacion= ?",[$idCotizacion]);
      // $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizaciones SET montoTotal = '0', noEquipos=? WHERE idCotizacion= ?",[$noEquipos[0]->noEquipos,$idCotizacion]);
      $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizaciones SET montoTotal = ?, noEquipos=? WHERE idCotizacion= ?",[$montoTotal[0]->montoTotal,$noEquipos[0]->noEquipos,$idCotizacion]);
      }else{
        $siRepetidos = \DB::UPDATE("UPDATE cotizaciones SET repetidos = 'no' WHERE idCotizacion= ?",[$idCotizacion]);
        $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizaciones SET montoTotal = ?, noEquipos=? WHERE idCotizacion= ?",[$montoTotal[0]->montoTotal,$noEquipos[0]->noEquipos,$idCotizacion]);
      }
    
    }
    
    return response()->json(200);
    // $estatusCotizacion = \DB::select("SELECT estatus FROM cotizaciones WHERE idCotizacion = ?",[$idCotizacion]);

    // return view ('reporteReparacionesAbajo')
    // ->with('partidaSiguiente',$partidaSiguiente)
    // ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
    // ->with('equiposReparados',$equiposReparados)
    // ->with('datosEquiposNuevos',$datosEquiposNuevos)
    // ->with('numeroCotizacion',$numeroCotizacion)
    // ->with('idSucursal',$idSucursal)
    // ->with('idEquipos',$idEquipos)
    // ->with('tipoMoneda',$tipoMoneda)
    // ->with('cuantos',$cuantos)
    // ->with('suma',$suma[0]->sumaTotal)
    // ->with('tipoCotizacion',$tipoCotizacion)
    // ->with('datosEquipoCotizado',$datosEquipoCotizado)
    // ->with('cuantasReparaciones',$cuantasReparaciones)
    // ->with('estatusCotizacion',$estatusCotizacion[0]->estatus);
  }

  public function cancelarVersion(Request $request){
    $idCotizacion = $request->idCotizacion;
    $idCotizacionAnteriorVista = $request->idCotizacionAnterior;
    $idc = $request->idc;
    $estatus = $request->estatusAnterior;
    $cotizacionOriginal= $request->cotizacionOriginal;
    
    $consecutivoAnterior=\DB::select("SELECT (numCotizacion-1) AS cotizacionAnterior FROM cotizaciones WHERE idCotizacion = ?",[$idCotizacion]);  
    $idCotizacionAnterior=\DB::select("SELECT idCotizacion FROM cotizaciones WHERE cotizacionOriginal=? AND numCotizacion = ?",[$cotizacionOriginal,$consecutivoAnterior[0]->cotizacionAnterior]);   
    $activandoVersionAnterior = \DB::UPDATE("UPDATE cotizaciones SET clonActual ='Si',estatus=? WHERE numCotizacion= ? AND idCotizacion= ?",[$estatus,$consecutivoAnterior[0]->cotizacionAnterior,$idCotizacionAnterior[0]->idCotizacion]);
    $activandoEquiposAnteriores = \DB::UPDATE("UPDATE detallereparaciones SET clonEquiposActivo ='Si'  WHERE idCotizacion= ?",[$idCotizacionAnterior[0]->idCotizacion]);
    $cambiandoEstatus = \DB::UPDATE("UPDATE recepcionEquipos SET estatusEntrega='Cotizado' WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones WHERE idCotizacion= ?)",[$idCotizacionAnterior[0]->idCotizacion]);
    
    $equiposRecienAgregados = \DB::select("SELECT idEquipos,estatusAnteriorEquipo FROM detallereparaciones WHERE idCotizacion= ? AND agregadoReciente='Si'",[$idCotizacion]);
    for ($i=0; $i < count($equiposRecienAgregados) ; $i++) {
      $idEquipos = $equiposRecienAgregados[$i]->idEquipos;
      $estatusAnteriror = $equiposRecienAgregados[$i]->estatusAnteriorEquipo;
      $cambiandoEstatus = \DB::UPDATE("UPDATE recepcionEquipos SET estatusEntrega=? WHERE idEquipos = ?",[$estatusAnteriror,$idEquipos]);
    }

    $borrarClonDetalle= \DB::delete("DELETE FROM detallereparaciones WHERE idCotizacion=?",[$idCotizacion]);
    $borrarClonCotizacion= \DB::delete("DELETE FROM cotizaciones WHERE idCotizacion=?",[$idCotizacion]);
    $borrarRefacciones= \DB::delete("DELETE FROM refaccionesEnCotizacion WHERE idCotizacion=?",[$idCotizacion]);

    return;

    // // $consulta = \DB::select("SELECT co.idCotizacion, co.numeroCotizacion, co.fechaCotizacion,co.clonActual,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
    // // co.noEquipos,CONCAT('$',' ',co.tipoMoneda,' ',co.montoTotal) AS montoMoneda,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus
    // // FROM cotizaciones AS co
    // // INNER JOIN usuarios AS u ON u.idu= co.idu
    // // INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
    // // INNER JOIN clientes AS c ON c.idc= co.idc
    // // WHERE co.idc=?",[$idc]);
    // // $cuantos = count($consulta);

    // $fechaInicio = '0000-00-00';
    // $fechaFin = '9999-99-99';
    // return view ('reporteVersiones')
    // // ->with('cuantos',$cuantos)
    // // ->with('consulta',$consulta)
    // ->with('fechaInicio',$fechaInicio)
    // ->with('fechaFin',$fechaFin)
    // ->with('idc',$idc);
  }

  public function cancelarAltaVersion(Request $request){
    $idCotizacion = $request->idCotizacion;    
    $borrarCotizacion= \DB::delete("DELETE FROM cotizaciones WHERE idCotizacion=?",[$idCotizacion]);
    $borrarRefacciones= \DB::delete("DELETE FROM refaccionesEnCotizacion WHERE idCotizacion=?",[$idCotizacion]);

    return;    
  }

  public function verCotizacion(Request $request){
    $idCotizacion = $request->get('idCotizacion');

    $cotizacionEditar1 = \DB::select("SELECT co.idCotizacion,co.idc,co.idu,co.idSucursal,co.numeroCotizacion,co.cotizacionOriginal,co.fechaCotizacion,co.estatus,tipoMoneda,co.noEquipos,
    CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
    co.noEquipos,co.dirigidoA,co.conCopia,
    FORMAT(co.montoTotal,2) AS montoTotal,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus,co.check1,co.textoCheck1,co.check2,co.textoCheck2,co.check3,co.textoCheck3,co.check4,
    co.textoCheck4,co.check5,co.textoCheck5,co.check6,co.textoCheck6,co.check7,co.textoCheck7,co.check8,co.textoCheck8
    FROM cotizaciones AS co
    INNER JOIN usuarios AS u ON u.idu= co.idu
    INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
    INNER JOIN clientes AS c ON c.idc= co.idc
    WHERE idCotizacion=?",[$idCotizacion]);

    $fotoCotizacionEditar = \DB::select("SELECT archivoCotizacion FROM cotizaciones WHERE idCotizacion=?",[$idCotizacion]);
    $archivoOrdenCompra = \DB::select("SELECT archivo FROM facturas WHERE idCotizacion=?",[$idCotizacion]);

    $equiposReparados = \DB::select("SELECT dr.tipoCotizacion,dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion, dr.descripcion,dr.modelo,dr.numeroCotizacion,dr.notas,re.GCMid,re.complementoGCMid, dr.semanasEntrega, FORMAT(dr.montoEquipo,2) AS montoEquipo,
    FORMAT(dr.montoFinanciamiento,2) AS montoFinanciamiento,dr.descuento,dr.tipoDescuento
    FROM detallereparaciones AS dr
    INNER JOIN recepcionEquipos AS re ON re.idEquipos = dr.idEquipos
    WHERE idCotizacion=?",[$idCotizacion]);
    
    $equiposNuevosYreparados = \DB::select("SELECT *
    FROM detallereparaciones AS dr
    WHERE idCotizacion=?",[$idCotizacion]);
    $cuantos = count($equiposNuevosYreparados);

    $datosEquiposNuevos = \DB::select("SELECT pv.nombreRefaccion,pv.numeroParte,dr.tipoCotizacion,dr.idEquipos,dr.idPartesVenta
    FROM detallereparaciones AS dr
    INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idPartesVenta
    WHERE idCotizacion=? AND tipoCotizacion !='reparacion'",[$idCotizacion]);

    $datosEquipoCotizado = \DB::select("SELECT re.idEquipos, re.serie,
    CONCAT (re.serie,' - ',te.tipoEquipo,' - ',se.subtipoEquipo) AS equipo
    FROM recepcionEquipos AS re
    INNER JOIN tipoequipos AS te ON te.idTipoEquipo= re.idTipoEquipo
    INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= re.idSubtipoEquipo
    WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones)");

    ////// Consulta para el seguimiento de actividades ////
    $seguimiento=\DB::select("SELECT sa.idSegActividad,sa.folio,  DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacionFormato, 
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
    INNER JOIN cotizaciones AS co ON co.idCotizacion=ea.idCotizacion
    WHERE co.idCotizacion=$idCotizacion
    ORDER BY idSegActividad DESC");

    $cuantosSegui=count($seguimiento);
  
    return view('verCotizacion')
    ->with('cuantosSegui',$cuantosSegui)
    ->with('seguimiento',$seguimiento)
    ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
    ->with('equiposReparados',$equiposReparados)
    ->with('datosEquiposNuevos',$datosEquiposNuevos)
    ->with('idCotizacion',$idCotizacion)    
    ->with('datosEquipoCotizado',$datosEquipoCotizado)
    ->with('archivoOrdenCompra',$archivoOrdenCompra)
    ->with('fotoCotizacionEditar',$fotoCotizacionEditar)
    ->with('cotizacionEditar1',$cotizacionEditar1[0]);
  }

  public function pdfCotizacion(Request $request){
    $idCotizacion = $request->get('idCotizacion');
    $verTotal = $request->get('verTotal');
    $publicidad = $request->get('pld');
    $verCambio = $request->get('cD');
    $stipo = Session::get('sesiontipo');

    $consultaCotizacion = \DB::select("SELECT co.idCotizacion,c.razonSocial,co.cambio,co.idSucursal,co.dirigidoA,co.conCopia,co.numeroCotizacion,co.fechaCotizacion,co.estatus,co.tipoMoneda,co.idc,
    CONCAT(u.nombreUsuario,' ', u.aPaterno) AS usuario,
    co.montoTotal,c.razonSocial AS cliente, s.sucursal AS sucursal,CONCAT(s.calle,' ',s.num,' ',s.colonia) AS direccion, co.estatus,co.check1,co.textoCheck1,co.check2,co.textoCheck2,co.check3,co.textoCheck3,co.check4,
		co.textoCheck4,co.check5,co.textoCheck5,co.check6,co.textoCheck6,co.check7,co.textoCheck7,co.check8,co.textoCheck8
    FROM cotizaciones AS co
    INNER JOIN usuarios AS u ON u.idu= co.idu
    INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
    INNER JOIN clientes AS c ON c.idc= co.idc
    WHERE idCotizacion=?",[$idCotizacion]);
    $cadenaFecha= $consultaCotizacion[0]->fechaCotizacion;
    $arrayFecha=explode('-',$cadenaFecha);

    $dirigidoA= $consultaCotizacion[0]->dirigidoA;
    $conCopia= $consultaCotizacion[0]->conCopia;
    $idc= $consultaCotizacion[0]->idc;
    $consultaContacto1 = \DB::select("SELECT telefonoCont,correoCont FROM contactosucursales WHERE nombreCont = ? AND idc=?",[$dirigidoA,$idc]);
    $cuantosContactos1 = count($consultaContacto1);
    $consultaContacto2 = \DB::select("SELECT telefonoCont,correoCont FROM contactosucursales WHERE nombreCont = ? AND idc=?",[$conCopia,$idc]);
    $cuantosContactos2 = count($consultaContacto1);    

    $consultaEquiposReparados = \DB::select("SELECT * FROM recepcionEquipos AS re WHERE re.idEquipos IN (SELECT dtr.idEquipos FROM detallereparaciones AS dtr WHERE dtr.idCotizacion =?)",[$idCotizacion]);
    $consultaEquiposNuevosYreparados = \DB::select("SELECT pv.idPartesVenta,mr.marcaRefaccion 
    FROM partesVenta AS pv
    INNER JOIN marcasRefaccion AS mr ON mr.idMarcaRefa = pv.idMarcaRefa
    WHERE idPartesVenta IN (SELECT idEquipos FROM detallereparaciones WHERE idCotizacion =?)",[$idCotizacion]);

    $datosEquipoCotizado = \DB::select("SELECT re.idEquipos,re.servicio, re.serie,
    CONCAT (re.serie,' - ',te.tipoEquipo,' - ',se.subtipoEquipo) AS equipo
    FROM recepcionEquipos AS re
    INNER JOIN tipoequipos AS te ON te.idTipoEquipo= re.idTipoEquipo
    INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= re.idSubtipoEquipo
    WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones)");

    // $servicios = \DB::select("SELECT p.idParte,p.GCMidParte,s.idAlcance,a.alcance,st.nombreServTaller
    // FROM serviciosreparacionpartes AS s
    // LEFT JOIN serviciostalleres AS st ON st.idServicioTaller = s.idServicioTaller
    // LEFT JOIN alcances AS a ON a.idAlcance = s.idAlcance
    // LEFT JOIN partesreparacion AS p ON p.idParte = s.idParte
    // WHERE s.idParte IN (SELECT idParte FROM partesreparacion WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones WHERE idCotizacion =?))  AND tieneCotizacion !='No' ORDER BY alcance ASC",[$idCotizacion]);
    // $cuantosServicios = count($servicios);

    $refacciones =\DB::select("SELECT rf.idRefaccionCotizacion,rf.idCotizacion,
    rf.partida,rf.idEquipos,rf.idParte,rf.precioPesos,rf.precioDolar,
    rf.apareceEnCoti,rf.idPaquete,p.GCMidParte,
    pv.codigo,pv.nombreRefaccion,pv.tipoProducto,pq.nombrePaquete
    FROM refaccionesEnCotizacion AS rf  
    LEFT JOIN paquetes AS pq ON pq.idPaquete = rf.idPaquete
    LEFT JOIN partesreparacion AS p ON p.idParte = rf.idParte
    LEFT JOIN partesVenta AS pv ON pv.idPartesVenta = rf.idPartesVenta
    WHERE rf.idParte IN (SELECT idParte FROM partesreparacion WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones WHERE rf.idCotizacion =?) AND rf.apareceEnCoti = 'Si')
    ORDER BY rf.idPaquete",[$idCotizacion]);
    $cuantasRefacciones = count($refacciones);
    
    $refaccionesIndividuales = DB::table('refaccionesEnCotizacion')->select('partida')->where('idPaquete','=',NULL)->where('idCotizacion','=',$idCotizacion)->get();
    
    $paquetes =\DB::select("SELECT rf.idPaquete,rf.partida,rf.idCotizacion,rf.idEquipos,rf.idParte,pq.nombrePaquete
    FROM refaccionesEnCotizacion AS rf  
    LEFT JOIN paquetes AS pq ON pq.idPaquete = rf.idPaquete    
    WHERE rf.idParte IN (SELECT idParte FROM partesreparacion WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones WHERE rf.idCotizacion =?) AND apareceEnCoti = 'Si' AND rf.idPaquete != '')
    GROUP BY rf.idPaquete,rf.partida,rf.idCotizacion,rf.idEquipos,rf.idParte,pq.nombrePaquete
    ORDER BY rf.partida",[$idCotizacion]);

    $partes = \DB::select("SELECT idEquipos,idParte,GCMidParte FROM partesreparacion WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones WHERE idCotizacion =?)",[$idCotizacion]);    
    $equiposReparados = \DB::select("SELECT dr.tipoCotizacion,dr.idRepCotizacion,dr.idEquipos,dr.partida,
    dr.idCotizacion, dr.descripcion,dr.numeroCotizacion,dr.notas,re.GCMid,re.complementoGCMid, 
    dr.semanasEntrega,dr.montoEquipo,
    dr.montoFinanciamiento,dr.descuento,dr.tipoDescuento
    FROM detallereparaciones AS dr
    INNER JOIN recepcionEquipos AS re ON re.idEquipos = dr.idEquipos
    WHERE idCotizacion=?",[$idCotizacion]);

    $equiposNuevosYreparados = \DB::select("SELECT *
    FROM detallereparaciones AS dr
    WHERE idCotizacion=?",[$idCotizacion]);
    // $cuantasReparaciones = count($equiposNuevosYreparados);

    $datosEquiposNuevos = \DB::select("SELECT pv.codigo,pv.nombreRefaccion,pv.numeroParte,dr.tipoCotizacion,dr.idEquipos,dr.modelo,dr.partida,pv.presentacion,dr.idPartesVenta,
    (SELECT marcaRefaccion FROM marcasRefaccion WHERE marcasRefaccion.idMarcaRefa =  pv.idMarcaRefa) AS marcaRefaccion
    FROM detallereparaciones AS dr
    INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idPartesVenta
    WHERE idCotizacion=? AND tipoCotizacion !='reparacion'",[$idCotizacion]);
    
    // return array_chunk($refacciones,10);
    
    $pdf = \PDF::loadView('cotizacionPDF',['consultaCotizacion'=>$consultaCotizacion[0],'consultaEquiposReparados'=>$consultaEquiposReparados,
    'consultaEquiposNuevosYreparados'=>$consultaEquiposNuevosYreparados,'equiposReparados'=>$equiposReparados,'cuantasRefacciones'=>$cuantasRefacciones,
    'datosEquiposNuevos'=>$datosEquiposNuevos,'datosEquipoCotizado'=>$datosEquipoCotizado,'refacciones'=>$refacciones,
    'partes'=>$partes,'equiposNuevosYreparados'=>$equiposNuevosYreparados,'paquetes'=>$paquetes,'refaccionesIndividuales'=>$refaccionesIndividuales,
    'arrayFecha'=>$arrayFecha,'verTotal'=>$verTotal,'verCambio'=>$verCambio,'publicidad'=>$publicidad,
    'consultaContacto1'=>$consultaContacto1,'consultaContacto2'=>$consultaContacto2,
    'cuantosContactos1'=>$cuantosContactos1,'cuantosContactos2'=>$cuantosContactos2,'tipoSesion'=>$stipo]);
        
    return $pdf->stream($consultaCotizacion[0]->numeroCotizacion.'.pdf');
    // return ($refaccionesIndividuales);
  }

  public function detallePartidasCotizacion(Request $request){
    $idCotizacion = $request->get('idCotizacion');
    $partida = $request->get('partida');
    $tipoMoneda = $request->get('tipoMoneda');

    $refaccionesIndividuales =DB::table('refaccionesEnCotizacion')
    ->select('refaccionesEnCotizacion.idRefaccionCotizacion','refaccionesEnCotizacion.idCotizacion','refaccionesEnCotizacion.partida','refaccionesEnCotizacion.idEquipos',
    'refaccionesEnCotizacion.idParte',DB::raw('FORMAT(refaccionesEnCotizacion.precioPesos,2) as precioPesos'),DB::raw('FORMAT(refaccionesEnCotizacion.precioDolar,2) as precioDolar'),'refaccionesEnCotizacion.apareceEnCoti','refaccionesEnCotizacion.idPaquete',
    'partesreparacion.GCMidParte','partesVenta.codigo','partesVenta.nombreRefaccion','partesVenta.tipoProducto','paquetes.nombrePaquete')
    ->leftJoin('paquetes', 'refaccionesEnCotizacion.idPaquete', '=', 'paquetes.idPaquete')
    ->leftJoin('partesreparacion', 'refaccionesEnCotizacion.idParte', '=', 'partesreparacion.idParte')
    ->leftJoin('partesVenta', 'refaccionesEnCotizacion.idPartesVenta', '=', 'partesVenta.idPartesVenta')
    // ->where('refaccionesEnCotizacion.apareceEnCoti', '=','Si')
    ->where('refaccionesEnCotizacion.idCotizacion', '=',$idCotizacion)
    ->where('refaccionesEnCotizacion.partida', '=',$partida)
    ->where('refaccionesEnCotizacion.idPaquete', '=',NULL)
    ->orderBy('refaccionesEnCotizacion.partida', 'ASC')
    ->get();    
    $cuantasRefacciones = count($refaccionesIndividuales);
    
    $paquetes =DB::table('refaccionesEnCotizacion')
    ->select('refaccionesEnCotizacion.idRefaccionCotizacion','refaccionesEnCotizacion.idCotizacion','refaccionesEnCotizacion.partida','refaccionesEnCotizacion.idEquipos',
    'refaccionesEnCotizacion.idParte',DB::raw('FORMAT(refaccionesEnCotizacion.precioPesos,2) as precioPesos'),DB::raw('FORMAT(refaccionesEnCotizacion.precioDolar,2) as precioDolar'),'refaccionesEnCotizacion.apareceEnCoti','refaccionesEnCotizacion.idPaquete',
    'partesreparacion.GCMidParte','partesVenta.codigo','partesVenta.nombreRefaccion','partesVenta.tipoProducto','paquetes.nombrePaquete')
    ->leftJoin('paquetes', 'refaccionesEnCotizacion.idPaquete', '=', 'paquetes.idPaquete')
    ->leftJoin('partesreparacion', 'refaccionesEnCotizacion.idParte', '=', 'partesreparacion.idParte')
    ->leftJoin('partesVenta', 'refaccionesEnCotizacion.idPartesVenta', '=', 'partesVenta.idPartesVenta')
    // ->where('refaccionesEnCotizacion.apareceEnCoti', '=','Si')
    ->where('refaccionesEnCotizacion.idCotizacion', '=',$idCotizacion)
    ->where('refaccionesEnCotizacion.partida', '=',$partida)
    ->where('refaccionesEnCotizacion.idPaquete', '!=',NULL)
    ->orderBy('refaccionesEnCotizacion.partida', 'ASC')
    ->get();    
    $cuantosPaquetes = count($paquetes);

    $paquetesNombres =\DB::select("SELECT rf.idPaquete,rf.partida,rf.idCotizacion,rf.idEquipos,rf.idParte,
    pq.nombrePaquete
    FROM refaccionesEnCotizacion AS rf  
    LEFT JOIN paquetes AS pq ON pq.idPaquete = rf.idPaquete    
    WHERE rf.idCotizacion =? AND rf.partida = ? AND apareceEnCoti = 'Si' AND rf.idPaquete != ''
    GROUP BY rf.idPaquete,rf.partida,rf.idCotizacion,rf.idEquipos,rf.idParte,pq.nombrePaquete
    ORDER BY rf.partida ASC",[$idCotizacion,$partida]);

    $equiposNuevosYreparados = \DB::select("SELECT *,dr.montoEquipoInicial
    FROM detallereparaciones AS dr
    WHERE dr.idCotizacion=? AND dr.partida=?",[$idCotizacion,$partida]);

    return view('detallePartidasCotizacion')
    ->with('refaccionesIndividuales',$refaccionesIndividuales)
    ->with('cuantasRefacciones',$cuantasRefacciones)
    ->with('paquetes',$paquetes)
    ->with('cuantosPaquetes',$cuantosPaquetes)
    ->with('paquetesNombres',$paquetesNombres)
    ->with('tipoMoneda',$tipoMoneda)
    ->with('equiposNuevosYreparados',$equiposNuevosYreparados[0]);

  }

  public function modificarMontoPartidaCotizacion(Request $request){
    $idCotizacion = $request->get('idCotizacion');
    $partida = $request->get('partida');    
    
    $equiposNuevosYreparados = \DB::select("SELECT * FROM detallereparaciones WHERE idCotizacion=? AND partida=?",[$idCotizacion,$partida]);

    return view('modificarMontoPartidaCotizacion')
    ->with('datos',$equiposNuevosYreparados)
    ->with('idCotizacion',$idCotizacion)
    ->with('partida',$partida);
  }

  public function guardarModificacionMontoPartidaCotizacion(Request $request){
    $idCotizacion = $request->get('idCotizacion');
    $partida = $request->get('partida');
    $partidaSiguiente = $partida +1;

    
    $idRepCotizacion = \DB::select("SELECT idRepCotizacion FROM detallereparaciones WHERE idCotizacion = ? AND partida = ?",[$idCotizacion,$partida]);
    
    $detallePartidasCotizacion = detallereparaciones::find($idRepCotizacion[0]->idRepCotizacion);
    $detallePartidasCotizacion-> montoEquipoInicial = $request-> montoEquipo / $request-> piezas;

    $detallePartidasCotizacion-> piezas = $request-> piezas;
    $detallePartidasCotizacion-> montoEquipo = $request-> montoEquipo / $request-> piezas;
    $detallePartidasCotizacion-> precioVentaOriginal = $request-> montoEquipoInicial;
    if( $request-> descuento ==''){
      $detallePartidasCotizacion-> montoFinanciamiento = $request-> montoEquipo;
    }else{
      $detallePartidasCotizacion-> montoFinanciamiento = $request-> montoFinanciamiento;
    }
    $detallePartidasCotizacion-> semanasEntrega = $request-> semanasEntrega;
    $detallePartidasCotizacion-> aplicaDescuento = $request-> aplicaDescuento;
    $detallePartidasCotizacion-> tipoDescuento = $request-> tipoDescuento;
    $detallePartidasCotizacion-> descuento = $request-> descuento;
    $detallePartidasCotizacion-> notas = $request-> notas;
    $detallePartidasCotizacion-> diasPago = $request-> diasDePago;
    $detallePartidasCotizacion-> save();
    
    return response()->json(200);
    // $equiposReparados = \DB::select("SELECT dr.tipoCotizacion,dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion, dr.descripcion,dr.modelo,dr.numeroCotizacion,dr.notas,re.GCMid,re.complementoGCMid, dr.semanasEntrega, FORMAT(dr.montoEquipo,2) AS montoEquipo,
    // FORMAT(dr.montoFinanciamiento,2) AS montoFinanciamiento,dr.descuento,dr.tipoDescuento
    // FROM detallereparaciones AS dr
    // INNER JOIN recepcionEquipos AS re ON re.idEquipos = dr.idEquipos
    // WHERE dr.idCotizacion=?",[$idCotizacion]);

    // $equiposNuevosYreparados = \DB::select("SELECT *
    // FROM detallereparaciones AS dr
    // WHERE dr.idCotizacion=?",[$idCotizacion]);
    // $cuantasReparaciones = count($equiposNuevosYreparados);
    
    // $suma = \DB::select("SELECT FORMAT(SUM(dr.montoFinanciamiento),2) AS sumaTotal
    // FROM detallereparaciones AS dr
    // WHERE dr.idCotizacion=?",[$idCotizacion]);

    // $datosEquiposNuevos = \DB::select("SELECT pv.nombreRefaccion,pv.numeroParte,dr.tipoCotizacion,dr.idEquipos,dr.idPartesVenta
    // FROM detallereparaciones AS dr
    // INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idPartesVenta
    // WHERE dr.idCotizacion=? AND tipoCotizacion !='reparacion'",[$idCotizacion]);

    // $datosEquipoCotizado = \DB::select("SELECT re.idEquipos, re.serie,
    // CONCAT (re.serie,' - ',te.tipoEquipo,' - ',se.subtipoEquipo) AS equipo
    // FROM recepcionEquipos AS re
    // INNER JOIN tipoequipos AS te ON te.idTipoEquipo= re.idTipoEquipo
    // INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= re.idSubtipoEquipo
    // WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones)");


    // $montoTotal= \DB::select("SELECT ROUND(SUM(montoFinanciamiento),2) AS montoTotal FROM detallereparaciones WHERE idCotizacion = ? GROUP BY idCotizacion",[$idCotizacion]);
    

    // $corrigiendoMontoTotalCotizacion = \DB::UPDATE("UPDATE cotizaciones SET montoTotal = ? WHERE idCotizacion= ?",[$montoTotal[0]->montoTotal,$idCotizacion]);
    
    // $consultaCotizacion = \DB::select("SELECT co.idCotizacion,c.razonSocial,co.cambio,co.idSucursal,co.dirigidoA,co.conCopia,co.numeroCotizacion,co.fechaCotizacion,co.estatus,co.tipoMoneda,co.idc,
    // CONCAT(u.nombreUsuario,' ', u.aPaterno) AS usuario,
    // co.montoTotal,c.razonSocial AS cliente, s.sucursal AS sucursal,CONCAT(s.calle,' ',s.num,' ',s.colonia) AS direccion, co.estatus,co.check1,co.textoCheck1,co.check2,co.textoCheck2,co.check3,co.textoCheck3,co.check4,
		// co.textoCheck4,co.check5,co.textoCheck5,co.check6,co.textoCheck6,co.check7,co.textoCheck7,co.check8,co.textoCheck8
    // FROM cotizaciones AS co
    // INNER JOIN usuarios AS u ON u.idu= co.idu
    // INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
    // INNER JOIN clientes AS c ON c.idc= co.idc
    // WHERE idCotizacion=?",[$idCotizacion]);
    

    // return view ('reporteReparacionesAbajo')
    // ->with('partidaSiguiente',$partidaSiguiente)
    // ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
    // ->with('equiposReparados',$equiposReparados)
    // ->with('datosEquiposNuevos',$datosEquiposNuevos)
    // ->with('numeroCotizacion',$consultaCotizacion[0]->numeroCotizacion)
    // ->with('idSucursal',$consultaCotizacion[0]->idSucursal)
    // ->with('tipoMoneda',$consultaCotizacion[0]->tipoMoneda)
    // ->with('suma',$suma[0]->sumaTotal)    
    // ->with('datosEquipoCotizado',$datosEquipoCotizado)
    // ->with('cuantasReparaciones',$cuantasReparaciones)
    // ->with('estatusCotizacion',$consultaCotizacion[0]->estatus);

  }

  public function preciosRefaccionesCot(request $request){
    $idPartesVenta = $request->get('idPartesVenta');
    $tipoMoneda = $request->get('tipoMoneda');
    if ($tipoMoneda == 'MXN') {
      $precios= \DB::select("SELECT precioVentaPe as precio FROM partesVenta WHERE idPartesVenta=?",[$idPartesVenta]);
      $precios = $precios[0]->precio;
    }else {
      $precios= \DB::select("SELECT precioVentaDol as precio FROM partesVenta WHERE idPartesVenta=?",[$idPartesVenta]);
      $precios = $precios[0]->precio;
    }
    

    return '<b>Costo: </b>'.$tipoMoneda.' $'.$precios;
  }

  public function reactivarCotizacion(request $request){
    $idCotizacion = $request->get('idCotizacion');
    
    $cambiandoEstatusAcotizacion = \DB::UPDATE("UPDATE cotizaciones SET estatus='Aceptada con orden de compra' WHERE idCotizacion = ? ",[$idCotizacion]);
    $cambiarEstatus = \DB::select("SELECT idEquipos FROM detallereparaciones WHERE idCotizacion = ? ",[$idCotizacion]);
    $cuantasEquipos = count($cambiarEstatus);

    for ($i=0; $i < $cuantasEquipos; $i++) {
      $cambiandoEstatusEquipos = \DB::UPDATE("UPDATE recepcionEquipos SET estatusEntrega='con OC' WHERE idEquipos= ?",[$cambiarEstatus[$i]->idEquipos]);
    }

    return;
  }
}