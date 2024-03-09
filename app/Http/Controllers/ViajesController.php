<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\viajes;
use App\gastos;
use App\vehiculos;
use App\clientes;
use App\usuarios;
use App\facturas;
use App\sucursales;
use App\marcas;
use App\anexGrid;
use DB;
use Session;

class ViajesController extends Controller{

  public function altaSalidaViajes(){
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
      $sidu = Session::get('sesionidu');              
      $vehiculo = \DB::select('SELECT * FROM vehiculos WHERE idVehiculo NOT IN (SELECT idVehiculo FROM viajes WHERE estatus = "En curso") ORDER BY nombreVehiculo ASC');
      $cliente = clientes::orderby('razonSocial','asc')->get();

      return view ('altaSalidaViajes')
      ->with('vehiculo',$vehiculo)
      ->with('cliente',$cliente);
    }
  }

  public function comboca(Request $request){
    $idc = $request->get('idc');
    $sucursales = sucursales::where('idc','=',$idc,'AND','activo','=','Si')->get();

    return view ('combop')
    ->with('sucursales',$sucursales);
  }

  public function GuardarSalidaViajes(Request $request){
    $idu = $request -> idu;
    $usuarioViaje = $request-> usuarioViaje;
    $fechaInicio = $request-> fechaInicio;
    $idVehiculo = $request-> idVehiculo;
    $tipoViaje = $request-> tipoViaje;
    $idc = $request-> idc;
    $idSucursal = $request-> idSucursal;
    $montoGasto = $request-> montoGasto;
    //  $motivoViaje = $request-> motivoViaje;
    $kmActual = $request-> kmActual;
    $fotoEvidenciaSalida = $request-> fotoEvidenciaSalida;
    $liquidoFrenos = $request-> liquidoFrenos;
    $parabrisas = $request-> parabrisas;
    $llantasSalida = $request-> llantasSalida;
    $lucesSalida = $request-> lucesSalida;
    $anticongelante = $request-> anticongelante;
    $unidadLimpia = $request-> unidadLimpia;
    //  $observacionSalida = $request-> observacionSalida;

    $this->validate ($request,[
    'tipoViaje'=>['required'],
    'idVehiculo'=>['required'],
    'idc'=>['required'],
    'idSucursal'=>['required'],
    'montoGasto'=>['required'],
      //'motivoViaje'=>['regex:/^[A-Z][a-z,A-Z, ,0-9]*$/'],
    'kmActual'=>['regex:/^[0-9]*$/'],
    'fotoEvidenciaSalida'=>['mimes:jpeg,png,gif,jpg'],
    'liquidoFrenos'=>['required'],
    'parabrisas'=>['required'],
    'llantasSalida'=>['required'],
    'lucesSalida'=>['required'],
    'unidadLimpia'=>['required'],
    'anticongelante'=>['required'],]);
      //'observacionSalida'=>'regex:/^[A-Z][a-z,A-Z, ,0-9]*$/',]);

    $file = $request->file('fotoEvidenciaSalida');
    if($file!=""){
      $ldate = date('Ymd_His_');
      $img = $file->getClientOriginalName();
      $img2 = $ldate.$img;
      \Storage::disk('local')->put($img2, \File::get($file));
    }
    else{
      $img2 ="sinfoto.png";
    }

    $viaje=new viajes;
    $viaje-> idu = $request-> idu;
    $viaje-> usuarioViaje = $request-> usuarioViaje;
    $viaje-> fechaInicio = $request-> fechaInicio;
    $viaje-> idVehiculo = $request-> idVehiculo;
    $viaje-> tipoViaje = $request-> tipoViaje;
    $viaje-> idc = $request-> idc;
    $viaje-> idSucursal = $request-> idSucursal;
    $viaje-> montoGasto = $request-> montoGasto;
    $viaje-> motivoViaje = $request-> motivoViaje;
    $viaje-> kmActual = $request-> kmActual;
    $viaje-> fotoEvidenciaSalida = $img2;
    $viaje-> liquidoFrenos = $request-> liquidoFrenos;
    $viaje-> parabrisas = $request-> parabrisas;
    $viaje-> llantasSalida = $request-> llantasSalida;
    $viaje-> lucesSalida = $request-> lucesSalida;
    $viaje-> unidadLimpia = $request-> unidadLimpia;
    $viaje-> anticongelante = $request-> anticongelante;
    $viaje-> observacionSalida = $request-> observacionSalida;
    $viaje-> activo='Si';
    $viaje-> estatus='En curso';
    $viaje-> save();

    $proceso='Registro de salidas';
    $mensaje="Salida registrada correctamente";
    return view ('mensajeSalidaViajes')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }

  public function reporteViajes(Request $request){
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
      $idu = $request->idu;
      Session::put('idusu',$idu);
        
      if ($stipo == 'Administrador') {
        $usuario = usuarios::orderby('nombreUsuario','asc')->get();
        $consulta = \DB::select("SELECT v.idViaje, v.activo,v.montoGasto, v.estatus, (v.kmFinal- v.kmActual) AS kmr,
        v.kmFinal,v.usuarioViaje, v.fechaInicio,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS us, vh.nombreVehiculo AS veh,
        c.razonSocial AS cli, s.sucursal As suc FROM viajes as v
        INNER JOIN vehiculos AS vh ON vh.idVehiculo=v.idVehiculo
        INNER JOIN usuarios AS u ON u.idu= v.idu
        INNER JOIN sucursales AS s ON s.idSucursal= v.idSucursal
        INNER JOIN clientes AS c ON c.idc= v.idc
        ORDER BY v.fechaInicio DESC");

        return view ('reporteViajes')
        ->with('usuario',$usuario)
        ->with('consulta',$consulta);
      }
      else{
        $usuario = usuarios::orderby('nombreUsuario','asc')->get();
        $consulta = \DB::select("SELECT v.idViaje, v.activo,v.montoGasto, v.estatus, (v.kmFinal- v.kmActual) AS kmr,
        v.kmFinal,v.usuarioViaje, v.fechaInicio,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS us, vh.nombreVehiculo AS veh,
        c.razonSocial AS cli, s.sucursal As suc FROM viajes as v
        INNER JOIN vehiculos AS vh ON vh.idVehiculo=v.idVehiculo
        INNER JOIN usuarios AS u ON u.idu= v.idu
        INNER JOIN sucursales AS s ON s.idSucursal= v.idSucursal
        INNER JOIN clientes AS c ON c.idc= v.idc
        WHERE idViaje IN(SELECT idViaje AS via FROM viajes WHERE idu=?)
        ORDER BY v.fechaInicio DESC",[$sidu]);

        return view ('reporteViajes')
        ->with('usuario',$usuario)
        ->with('consulta',$consulta);
      }
    }
  }

  public function reporteViajesAbajo(Request $request){
    $fechaInicio = $request->fechaInicio;
    $fechaFin = $request->fechaFin;
    $idu = $request->idu;
    // echo $fechaInicio .  $fechaFin . $idu;

    $reporte=\DB::select("SELECT v.idViaje, DATE_FORMAT(v.fechaInicio,'%d %b %Y') AS fecha, v.usuarioViaje, (v.kmFinal- v.kmActual) AS kmr,v.montoGasto,
    v.estatus,v.activo,vh.nombreVehiculo,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS us, em.razonSocial FROM viajes
    AS v INNER JOIN vehiculos AS vh ON vh.idVehiculo = v.idVehiculo
    INNER JOIN usuarios AS u ON u.idu= v.idu
    INNER JOIN clientes AS em ON em.idc = v.idc WHERE fechaInicio>=? AND fechaInicio<=? AND v.idu=?
    GROUP BY v.idViaje,v.fechaInicio,usuarioViaje,kmr,vh.nombreVehiculo,em.razonSocial,v.montoGasto,v.estatus,v.activo,u.nombreUsuario,u.aPaterno,u.aMaterno
    ORDER BY v.fechaInicio DESC",[$fechaInicio,$fechaFin,$idu]);
    //echo "hola";
    //     return $reporte;
    $totales=\DB::select("SELECT COUNT(*) AS cuantos, SUM(t1.kmr) AS totalkm,sum(t1.montoGasto) as totalpago
    FROM (SELECT DATE_FORMAT(v.fechaInicio,'%d %b %Y') AS fecha,
    v.usuarioViaje, (v.kmFinal- v.kmActual) AS kmr,v.estatus,v.activo, vh.nombreVehiculo, em.razonSocial,v.montoGasto FROM viajes
    AS v INNER JOIN vehiculos AS vh ON vh.idVehiculo = v.idVehiculo
    INNER JOIN clientes AS em ON em.idc = v.idc WHERE fechaInicio>=? AND fechaInicio<=? AND v.idu=?
      GROUP BY v.idViaje,v.fechaInicio,usuarioViaje,kmr,vh.nombreVehiculo,em.razonSocial,v.montoGasto,v.estatus,v.activo
    ORDER BY v.fechaInicio DESC) AS t1",[$fechaInicio,$fechaFin,$idu]);

    $cuantos = count($reporte);

    /*        $usuario = usuarios::orderby('nombreUsuario','asc')->get();
    $consulta = \DB::select("SELECT v.idViaje, v.activo,v.montoGasto, v.estatus, v.kmActual,
    v.kmFinal,v.usuarioViaje, v.fechaInicio, vh.nombreVehiculo AS veh,
    c.razonSocial AS cli FROM viajes as v
    INNER JOIN vehiculos AS vh ON vh.idVehiculo=v.idVehiculo
    INNER JOIN clientes AS c ON c.idc= v.idc");*/

    return view ('reporteViajesAbajo')
    ->with('cuantos',$cuantos)
    ->with('totales',$totales[0])
    ->with('reporte',$reporte);
  }

  public function altaRegresoViajes($idViaje){
    $consultaImg = \DB::select("SELECT km.fotoEvidenciaSalida FROM viajes AS km Where idViaje = $idViaje ");
    $consulta = viajes::Where ('idViaje','=',$idViaje)->take(1)->get();
    
    //return $consulta;
    // $vehiculoSel= vehiculos::where('idVehiculo',"=",$consulta[0]->idVehiculo)->get();
    // $nomVehiculo =$vehiculoSel[0]->nombreVehiculo;
    // $placas=$vehiculoSel[0]->placas;
    // $idMarca =$vehiculoSel[0]->idMarca;
    $vehiculo= vehiculos::where ('idVehiculo','=',$consulta[0]->idVehiculo)->get();
    //return $vehiculo;
    $clienteSel= clientes::where('idc',"=",$consulta[0]->idc)->get();
    $nomcli =$clienteSel[0]->razonSocial;
    $cliente = clientes::where ('idc','!=',$consulta[0]->idc)->get();
    
    $sucursalSel= sucursales::where('idSucursal',"=",$consulta[0]->idSucursal)->get();
    $nomsuc =$sucursalSel[0]->sucursal;
    $sucursal = sucursales::where ('idSucursal','!=',$consulta[0]->idSucursal)->get();      

    return view ('altaRegresoViajes')
    ->with('consulta',$consulta[0])
    ->with('consultaImg',$consultaImg)
    ->with('vehiculo',$vehiculo)
    // ->with('idVehiculosel',$consulta[0]->idVehiculo)
    // ->with('nomVehiculo',$nomVehiculo)
    // ->with('placas',$placas)
    // ->with('idMarca',$idMarca)
    // ->with('vehiculoSel',$vehiculoSel[0])
    ->with('cliente',$cliente)
    ->with('idclientesel',$consulta[0]->idc)
    ->with('nomcli',$nomcli)
    ->with('clienteSel',$clienteSel[0])
    ->with('sucursal',$sucursal)
    ->with('idSucursal',$consulta[0]->idSucursal)
    ->with('nomsuc',$nomsuc)
    ->with('sucursalSel',$sucursalSel[0]);
  }

  public function GuardarRegresoViajes(Request $request){
    //return $request;
    $idViaje=$request-> idViaje;
    $idVehiculo=$request-> idVehiculo;
    $fechaFin=$request-> fechaFin;
    $kmFinal=$request-> kmFinal;
    $fotoEvidenciaRegreso=$request-> fotoEvidenciaRegreso;
    $golpes=$request-> golpes;
    $rayaduras=$request-> rayaduras;
    $llantasEntrega=$request-> llantasEntrega;
    $lucesEntrega=$request-> lucesEntrega;
    $unidadLimpiaEntrega=$request-> unidadLimpiaEntrega;
    $observacionEntrega=$request-> observacionEntrega;

    $this->validate ($request,[
    'kmFinal'=>'required|numeric|min:$kmActual',
    'golpes'=>['required'],
    'rayaduras'=>['required'],
    'llantasEntrega'=>['required'],
    'lucesEntrega'=>['required'],
    'unidadLimpiaEntrega'=>['required'],
    'fotoEvidenciaRegreso'=>['mimes:jpeg,png,gif,jpg'],]);
    //'observacionEntrega'=>'required',]);

    $file = $request->file('fotoEvidenciaRegreso');
    if($file!=""){
      $ldate = date('Ymd_His_');
      $img = $file->getClientOriginalName();
      $img2 = $ldate.$img;
      \Storage::disk('local')->put($img2, \File::get($file));
    }
    else{
      $img2 ="sinfoto.png";
    }

    //$actualizakm=\DB::update("UPDATE vehiculos SET kmActual='$kmFinal' WHERE idVehiculo=idVehiculo");
			
    $vehiculos = vehiculos::find($idVehiculo);
    $vehiculos->kmActual = $kmFinal;
    //return $vehiculos;
    $vehiculos ->save();

    $viajes = viajes::where('idViaje', $idViaje)->get();
    $via=viajes::find($idViaje);
    $via-> fechaFin= $request-> fechaFin;
    $via-> kmFinal= $request-> kmFinal;
    $via-> fotoEvidenciaRegreso = $img2;
    $via-> golpes= $request-> golpes;
    $via-> rayaduras= $request-> rayaduras;
    $via-> llantasEntrega= $request-> llantasEntrega;
    $via-> lucesEntrega= $request-> lucesEntrega;
    $via-> unidadLimpiaEntrega=$request-> unidadLimpiaEntrega;
    $via-> observacionEntrega= $request-> observacionEntrega;
    $via-> estatus= 'Finalizado';
    $via-> save();

    $proceso='Finalización de Viajes';
    $mensaje="Viaje finalizado correctamente";
    return view ('mensajeSalidaViajes')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }

  public function eliminarSalidaViajes($idViaje){/*Recibe este parametro y lo guarda en esa variable*/
    $viajes = \DB::UPDATE("update viajes
    set activo ='No', estatus='Cancelado' where idViaje=$idViaje");

    $proceso ="Eliminacion de salidas";
    $mensaje="La salida ha sido cancelada correctamente";
    
    return view('mensajeSalidaViajes')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }

  public function restaurarSalidaViajes($idViaje){ //restarura el valos de NO a SI en el campo activo
    $viajes= \DB::UPDATE("update viajes
    set activo ='Si' , estatus='En curso'where idViaje=$idViaje");

    $proceso ="Restauracion de Viajes";
    $mensaje="El Viaje ha sido activado correctamente";
    return view('mensajeSalidaViajes')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }

  public function detalleViajes($idViaje){
    $consultaImg = \DB::select("SELECT p.fotoEvidenciaSalida FROM viajes AS p Where idViaje = $idViaje ");
    $consultaImg2 = \DB::select("SELECT p.fotoEvidenciaRegreso FROM viajes AS p Where idViaje = $idViaje ");
    $consulta = viajes::Where ('idViaje','=',$idViaje)->take(1)->get();

    $clienteSel= clientes::where('idc',"=",$consulta[0]->idc)->get();
    $nomcli =$clienteSel[0]->razonSocial;
    $cliente = clientes::where ('idc','=',$consulta[0]->idc)->get();


    $vehiculoSel= vehiculos::where('idVehiculo',"=",$consulta[0]->idVehiculo)->get();
    $nomVehiculo =$vehiculoSel[0]->nombreVehiculo;
    $placas=$vehiculoSel[0]->placas;
    $idMarca =$vehiculoSel[0]->idMarca;
    $vehiculo= vehiculos::where ('idVehiculo','=',$consulta[0]->idVehiculo)->get();
    
    $sucursalSel= sucursales::where('idSucursal',"=",$consulta[0]->idSucursal)->get();
    $nomsuc =$sucursalSel[0]->sucursal;
    $sucursal = sucursales::where ('idSucursal','!=',$consulta[0]->idSucursal)->get();      

    return view ('detalleViajes')
    ->with('consulta',$consulta[0])
    ->with('vehiculo',$vehiculo)
    ->with('idVehiculosel',$consulta[0]->idVehiculo)
    ->with('nomVehiculo',$nomVehiculo)
    ->with('placas',$placas)
    ->with('idMarca',$idMarca)
    ->with('vehiculoSel',$vehiculoSel[0])
    ->with('cliente',$cliente)
    ->with('idclientesel',$consulta[0]->idc)
    ->with('nomcli',$nomcli)
    ->with('clienteSel',$clienteSel[0])
    ->with('consultaImg',$consultaImg)
    ->with('consultaImg2',$consultaImg2)
    ->with('sucursal',$sucursal)
    ->with('idSucursal',$consulta[0]->idSucursal)
    ->with('nomsuc',$nomsuc)
    ->with('sucursalSel',$sucursalSel[0]);

  }

  public function modificarSalidaViajes($idViaje){
    $consultaImg = \DB::select("SELECT p.fotoEvidenciaSalida FROM viajes AS p Where idViaje = $idViaje ");
    $consulta = viajes::Where ('idViaje','=',$idViaje)->get();

    $clienteSel= clientes::where('idc',"=",$consulta[0]->idc)->get();
    $nomcli =$clienteSel[0]->razonSocial;
    $cliente = clientes::where ('idc','!=',$consulta[0]->idc)->get();
    $clientec = clientes::orderby('razonSocial','asc')->get();

    
    $sucursalSel= sucursales::where('idSucursal',"=",$consulta[0]->idSucursal)->get();
    $nomsuc =$sucursalSel[0]->sucursal;
    $sucursal = sucursales::where ('idSucursal','!=',$consulta[0]->idSucursal)->get();

    $vehiculoSel= vehiculos::where('idVehiculo',"=",$consulta[0]->idVehiculo)->get();
    $nomVehiculo =$vehiculoSel[0]->nombreVehiculo;
    $placas=$vehiculoSel[0]->placas;
    $idMarca =$vehiculoSel[0]->idMarca;
    // $vehiculo= vehiculos::where ('idVehiculo','!=',$consulta[0]->idVehiculo)->get();
    $vehiculo = \DB::select('SELECT * FROM vehiculos WHERE idVehiculo NOT IN (SELECT idVehiculo FROM viajes WHERE estatus = "En curso") AND idVehiculo !=?',[$consulta[0]->idVehiculo]);                          

    return view ('editarSalidaViajes')
    ->with('consulta',$consulta[0])
    ->with('clientec',$clientec)
    ->with('vehiculo',$vehiculo)
    ->with('idVehiculosel',$consulta[0]->idVehiculo)
    ->with('nomVehiculo',$nomVehiculo)
    ->with('placas',$placas)
    ->with('idMarca',$idMarca)
    ->with('vehiculoSel',$vehiculoSel[0])
    ->with('cliente',$cliente)
    ->with('idclientesel',$consulta[0]->idc)
    ->with('nomcli',$nomcli)
    ->with('clienteSel',$clienteSel[0])                
    ->with('sucursal',$sucursal)
    ->with('idSucursal',$consulta[0]->idSucursal)
    ->with('nomsuc',$nomsuc)
    ->with('sucursalSel',$sucursalSel[0])
    ->with('consultaImg',$consultaImg);

  }

  public function editarSalidaViajes(Request $request){
    $idViaje = $request-> idViaje;
    $usuarioViaje = $request-> usuarioViaje;
    $fechaInicio = $request-> fechaInicio;
    $idVehiculo = $request-> idVehiculo;
    $tipoViaje = $request-> tipoViaje;
    $idc = $request-> idc;
    $idSucursal = $request-> idSucursal;
    $montoGasto = $request-> montoGasto;
    $motivoViaje = $request-> motivoViaje;
    $kmActual = $request-> kmActual;
    $liquidoFrenos = $request-> liquidoFrenos;
    $parabrisas = $request-> parabrisas;
    $llantasSalida = $request-> llantasSalida;
    $lucesSalida = $request-> lucesSalida;
    $anticongelante = $request-> anticongelante;
    $unidadLimpia = $request-> unidadLimpia;
    $observacionSalida = $request-> observacionSalida;

    $this->validate ($request,[
    'tipoViaje'=>['required'],
    'idVehiculo'=>['required'],
    'idc'=>['required'],
    'idSucursal'=>['required'],
    'montoGasto'=>['required'],
    //'motivoViaje'=>['regex:/^[A-Z][a-z,A-Z, ,0-9]*$/'],
    'kmActual'=>['regex:/^[0-9]*$/'],
    'fotoEvidenciaSalida'=>['mimes:jpeg,png,gif,jpg'],
    'liquidoFrenos'=>['required'],
    'parabrisas'=>['required'],
    'llantasSalida'=>['required'],
    'lucesSalida'=>['required'],
    'unidadLimpia'=>['required'],
    'anticongelante'=>['required'],]);
    //'observacionSalida'=>'regex:/^[A-Z][a-z,A-Z, ,0-9]*$/',]);

    $file = $request->file('fotoEvidenciaSalida');
    if($file!=""){
      $ldate = date('Ymd_His_');
      $img = $file->getClientOriginalName();
      $img2 = $ldate.$img;
      \Storage::disk('local')->put($img2, \File::get($file));
    }
      $via = viajes::find($idViaje);
    if($file!=""){
          $via->fotoEvidenciaSalida=$img2;
    }

    $via-> idViaje = $request-> idViaje;
    $via-> usuarioViaje = $request-> usuarioViaje;
    $via-> fechaInicio = $request-> fechaInicio;
    $via-> idVehiculo = $request-> idVehiculo;
    $via-> tipoViaje = $request-> tipoViaje;
    $via-> idc = $request-> idc;
    $via-> idSucursal = $request-> idSucursal;
    $via-> montoGasto = $request-> montoGasto;
    $via-> motivoViaje = $request-> motivoViaje;
    $via-> kmActual = $request-> kmActual;
    $via-> liquidoFrenos = $request-> liquidoFrenos;
    $via-> parabrisas = $request-> parabrisas;
    $via-> llantasSalida = $request-> llantasSalida;
    $via-> lucesSalida = $request-> lucesSalida;
    $via-> anticongelante = $request-> anticongelante;
    $via-> unidadLimpia = $request-> unidadLimpia;
    $via-> observacionSalida = $request-> observacionSalida;
    $via-> activo='Si';
    $via-> estatus='En curso';
    $via-> save();

    $proceso='Modificación de salidas';
    $mensaje="Salida modificada correctamente";
    return view ('mensajeSalidaViajes')
      ->with('proceso',$proceso)
      ->with('mensaje',$mensaje);

  }

  public function modificarViajes($idViaje){
    $consultaImg = \DB::select("SELECT p.fotoEvidenciaSalida FROM viajes AS p Where idViaje = $idViaje ");
    $consultaImg2 = \DB::select("SELECT p.fotoEvidenciaRegreso FROM viajes AS p Where idViaje = $idViaje ");
    $consulta = viajes::Where('idViaje','=',$idViaje)->get();

    $clienteSel=clientes::where('idc',"=",$consulta[0]->idc)->get();
    $nomcli=$clienteSel[0]->razonSocial;
    $cliente=clientes::where ('idc','!=',$consulta[0]->idc)->get();
    $clientec = clientes::orderby('razonSocial','asc')->get();

    $sucursalSel= sucursales::where('idSucursal',"=",$consulta[0]->idSucursal)->get();
    $nomsuc =$sucursalSel[0]->sucursal;
    $sucursal = sucursales::where ('idSucursal','!=',$consulta[0]->idSucursal)->get();
    
    $vehiculoSel=vehiculos::where('idVehiculo',"=",$consulta[0]->idVehiculo)->get();
    $nomVehiculo=$vehiculoSel[0]->nombreVehiculo;
    $placas=$vehiculoSel[0]->placas;
    $tipoV=$vehiculoSel[0]->tipoVehiculo;
    // $vehiculo=vehiculos::orderby('nombreVehiculo','asc')->get();
    $vehiculo = \DB::select('SELECT * FROM vehiculos WHERE idVehiculo NOT IN (SELECT idVehiculo FROM viajes WHERE estatus = "En curso") AND idVehiculo !=?',[$consulta[0]->idVehiculo]);

    $comboFacturas =  \DB::select('SELECT f.idServicios,f.idc,f.idSucursal, 
    c.razonSocial AS cliente, s.sucursal As sucursal FROM facturas as f              
    INNER JOIN sucursales AS s ON s.idSucursal= f.idSucursal
    INNER JOIN clientes AS c ON c.idc= f.idc
    ORDER BY idServicios DESC');

    $consultaGastos = \DB::select("SELECT t2.id,t2.idServicios,c.razonSocial AS cliente,s.sucursal AS sucursal,t2.total,t2.created_at
    FROM(
      SELECT t1.id,t1.idServicios,fact.idc,fact.idSucursal,t1.total,t1.created_at
      FROM(
          SELECT id,idServicios,total,created_at FROM gastos WHERE idViaje = $idViaje
        ) AS t1   
      INNER JOIN facturas AS fact ON fact.idServicios= t1.idServicios
    ) AS t2
    INNER JOIN clientes AS c ON c.idc= t2.idc
    INNER JOIN sucursales AS s ON s.idSucursal= t2.idSucursal
    ORDER BY t2.created_at DESC");

    //$marcaSel=marcas::where('idMarca',"=",$consulta[0]->idMarca)->get();
    //$nomMarca=$marcaSel[0]->nombreMarca;
    //$marca=marcas::where ('idMarca','!=',$consulta[0]->idMarca)->get();

    return view ('editarViajes')
    ->with('consulta',$consulta[0])
    ->with('clientec',$clientec)
    //Datos del para ver la marca del vehiculo
    //->with('marcaSel',$marcaSel[0])
    //->with('nomMarca',$nomMarca)
    //->with('marca',$marca)
    //->with('idmarcasel',$consulta[0]->idMarca)
    //Datos del vehiculo
    ->with('vehiculoSel',$vehiculoSel[0])
    ->with('nomVehiculo',$nomVehiculo)
    ->with('placas',$placas)
    ->with('tipoV',$tipoV)
    ->with('vehiculo',$vehiculo)
    ->with('idVehiculosel',$consulta[0]->idVehiculo)
    //Datos del cliente
    ->with('clienteSel',$clienteSel[0])
    ->with('nomcli',$nomcli)
    ->with('cliente',$cliente)
    ->with('idclientesel',$consulta[0]->idc)
    //Imagenes
    ->with('consultaImg',$consultaImg)
    ->with('consultaImg2',$consultaImg2)
    ->with('sucursal',$sucursal)
    ->with('idSucursal',$consulta[0]->idSucursal)
    ->with('nomsuc',$nomsuc)
    ->with('sucursalSel',$sucursalSel[0])
    ->with('comboFacturas',$comboFacturas)
    ->with('consultaGastos',$consultaGastos);
  }

  public function editarViajes(Request $request){
    $idViaje = $request-> idViaje;
    $idVehiculo=$request-> idVehiculo;
    $usuarioViaje = $request-> usuarioViaje;
    $fechaInicio = $request-> fechaInicio;
    $idVehiculo = $request-> idVehiculo;
    $tipoViaje = $request-> tipoViaje;
    $idc = $request-> idc;
    $idSucursal = $request-> idSucursal;
    $montoGasto = $request-> montoGasto;
    $motivoViaje = $request-> motivoViaje;
    $kmActual = $request-> kmActual;
    $liquidoFrenos = $request-> liquidoFrenos;
    $parabrisas = $request-> parabrisas;
    $llantasSalida = $request-> llantasSalida;
    $lucesSalida = $request-> lucesSalida;
    $anticongelante = $request-> anticongelante;
    $observacionSalida = $request-> observacionSalida;
    $fechaFin=$request-> fechaFin;
    $kmFinal=$request-> kmFinal;
    $golpes=$request-> golpes;
    $rayaduras=$request-> rayaduras;
    $llantasEntrega=$request-> llantasEntrega;
    $lucesEntrega=$request-> lucesEntrega;
    $unidadLimpia=$request-> unidadLimpia;
    $unidadLimpiaEntrega=$request-> unidadLimpiaEntrega;
    $observacionEntrega=$request-> observacionEntrega;

    $this->validate ($request,[
    'tipoViaje'=>['required'],
    'idVehiculo'=>['required'],
    'idc'=>['required'],
    'idSucursal'=>['required'],
    'montoGasto'=>['required'],
    //'motivoViaje'=>['regex:/^[A-Z][a-z,A-Z, ,0-9]*$/'],
    'kmActual'=>['regex:/^[0-9]*$/'],
    'fotoEvidenciaSalida'=>['mimes:jpeg,png,gif,jpg'],
    'liquidoFrenos'=>['required'],
    'parabrisas'=>['required'],
    'llantasSalida'=>['required'],
    'lucesSalida'=>['required'],
    'anticongelante'=>['required'],
    //'observacionSalida'=>['regex:/^[A-Z][a-z,A-Z, ,0-9]*$/'],
    'kmFinal'=>['regex:/^[0-9]*$/'],
    'golpes'=>['required'],
    'rayaduras'=>['required'],
    'unidadLimpia'=>['required'],
    'unidadLimpiaEntrega'=>['required'],
    'rayaduras'=>['required'],
    'llantasEntrega'=>['required'],
    'fotoEvidenciaRegreso'=>['mimes:jpeg,png,gif,jpg'],
    'lucesEntrega'=>['required'],]);
    //'observacionEntrega'=>'required',]);

    $file = $request->file('fotoEvidenciaSalida');
    if($file!=""){
      $ldate = date('Ymd_His_');
      $img = $file->getClientOriginalName();
      $img2 = $ldate.$img;
      \Storage::disk('local')->put($img2, \File::get($file));
    }


    $file2 = $request->file('fotoEvidenciaRegreso');
    if($file2!=""){
      $ldate = date('Ymd_His_');
      $img3 = $file2->getClientOriginalName();
      $img4 = $ldate.$img3;
      \Storage::disk('local')->put($img4, \File::get($file2));
    }


    $vehiculos = vehiculos::find($idVehiculo);
    $vehiculos->kmActual = $kmFinal;
    //return $vehiculos;
    $vehiculos ->save();

    $viaj = viajes::find($idViaje);
    if($file!=""){
          $viaj->fotoEvidenciaSalida=$img2;
    }
    if($file2!=""){
          $viaj->fotoEvidenciaRegreso=$img4;
    }
    $viaj-> idViaje = $request-> idViaje;
    $viaj-> usuarioViaje = $request-> usuarioViaje;
    $viaj-> fechaInicio = $request-> fechaInicio;
    $viaj-> fechaFin = $request-> fechaFin;
    $viaj-> idVehiculo = $request-> idVehiculo;
    $viaj-> tipoViaje = $request-> tipoViaje;
    $viaj-> idc = $request-> idc;
    $viaj-> idSucursal = $request-> idSucursal;
    $viaj-> montoGasto = $request-> montoGasto;
    $viaj-> motivoViaje = $request-> motivoViaje;
    $viaj-> kmActual = $request-> kmActual;
    $viaj-> liquidoFrenos = $request-> liquidoFrenos;
    $viaj-> parabrisas = $request-> parabrisas;
    $viaj-> llantasSalida = $request-> llantasSalida;
    $viaj-> lucesSalida = $request-> lucesSalida;
    $viaj-> anticongelante = $request-> anticongelante;
    $viaj-> observacionSalida = $request-> observacionSalida;
    $viaj-> kmFinal = $request-> kmFinal;
    $viaj-> liquidoFrenos = $request-> liquidoFrenos;
    $viaj-> golpes = $request-> golpes;
    $viaj-> rayaduras = $request-> rayaduras;
    $viaj-> llantasEntrega = $request-> llantasEntrega;
    $viaj-> lucesEntrega = $request-> lucesEntrega;
    $viaj-> unidadLimpia=$request-> unidadLimpia;
    $viaj-> unidadLimpiaEntrega=$request-> unidadLimpiaEntrega;
    $viaj-> observacionEntrega = $request-> observacionEntrega;
    $viaj-> observacionSalida = $request-> observacionSalida;
    $viaj-> save();

    $proceso='Modificación de viajes';
    $mensaje="Viaje modificada correctamente";
    return view ('mensajeSalidaViajes')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);


  }

  public function filtroViajes(){
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
      $anexGrid = new anexGrid();

      $conductor='';
      $cliente='';
      $sucursal='';
      $nombreVehiculo='';
      $estatus='';
      foreach($anexGrid->filtros as $f){
        if($f['columna'] == 'usuarioViaje' && $f['valor'] != ''){
            $conductor = $f['valor'];
        }
        if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
            $cliente = $f['valor'];
        }
        if($f['columna'] == 'sucursal' && $f['valor'] != ''){
            $sucursal = $f['valor'];
        }
        if($f['columna'] == 'nombreVehiculo' && $f['valor'] != ''){
            $nombreVehiculo = $f['valor'];
        }
        if($f['columna'] == 'estatus' && $f['valor'] != ''){
            $estatus = $f['valor'];
        }
      }

      $order;
      $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

      if ($stipo == 'Administrador') { 
        $registros = DB::table('viajes')            
        ->select('idViaje','fechaInicio','usuarioViaje','razonSocial','sucursales.sucursal','nombreVehiculo','viajes.estatus','viajes.activo',
        DB::raw('DATE_FORMAT(fechaInicio,"%d %b %Y") AS fecha'),
        DB::raw('CONCAT("$",FORMAT(montoGasto,2)) AS montoGasto'),
        DB::raw('(viajes.kmFinal - viajes.kmActual) AS kilometros'))                    
        ->leftJoin('clientes', 'viajes.idc', '=', 'clientes.idc')  
        ->leftJoin('sucursales', 'viajes.idSucursal', '=', 'sucursales.idSucursal')  
        ->leftJoin('vehiculos', 'viajes.idVehiculo', '=', 'vehiculos.idVehiculo') 
        ->where('idViaje', '>', 0)
        ->where('usuarioViaje', 'like', '%'.$conductor.'%') 
        ->where('razonSocial', 'like', '%'.$cliente.'%') 
        ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%') 
        ->where('nombreVehiculo', 'like', '%'.$nombreVehiculo.'%') 
        ->where('estatus', 'like', '%'.$estatus.'%') 
        ->orderBy($anexGrid->columna, $order)
        ->skip($anexGrid->pagina)
        ->take($anexGrid->limite)
        ->get();
        
        $total = DB::table('viajes')            
        ->select('idViaje','fechaInicio','usuarioViaje','razonSocial','sucursales.sucursal','nombreVehiculo','viajes.estatus','viajes.activo',
        DB::raw('DATE_FORMAT(fechaInicio,"%d %b %Y") AS fecha'),
        DB::raw('CONCAT("$",FORMAT(montoGasto,2)) AS montoGasto'),
        DB::raw('(viajes.kmFinal - viajes.kmActual) AS kilometros'))                    
        ->leftJoin('clientes', 'viajes.idc', '=', 'clientes.idc')  
        ->leftJoin('sucursales', 'viajes.idSucursal', '=', 'sucursales.idSucursal')  
        ->leftJoin('vehiculos', 'viajes.idVehiculo', '=', 'vehiculos.idVehiculo') 
        ->where('idViaje', '>', 0)
        ->where('usuarioViaje', 'like', '%'.$conductor.'%') 
        ->where('razonSocial', 'like', '%'.$cliente.'%') 
        ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%') 
        ->where('nombreVehiculo', 'like', '%'.$nombreVehiculo.'%') 
        ->where('estatus', 'like', '%'.$estatus.'%') 
        ->orderBy($anexGrid->columna, $order)
        ->skip($anexGrid->pagina)
        ->take($anexGrid->limite)
        ->count();

      }else{      
        $registros = DB::table('viajes')            
        ->select('idViaje','fechaInicio','usuarioViaje','razonSocial','sucursales.sucursal','nombreVehiculo','viajes.estatus','viajes.activo',
        DB::raw('DATE_FORMAT(fechaInicio,"%d %b %Y") AS fecha'),
        DB::raw('CONCAT("$",FORMAT(montoGasto,2)) AS montoGasto'),
        DB::raw('(viajes.kmFinal - viajes.kmActual) AS kilometros'))                    
        ->leftJoin('clientes', 'viajes.idc', '=', 'clientes.idc')  
        ->leftJoin('sucursales', 'viajes.idSucursal', '=', 'sucursales.idSucursal')  
        ->leftJoin('vehiculos', 'viajes.idVehiculo', '=', 'vehiculos.idVehiculo') 
        ->where('idViaje', '>', 0)
        ->where('usuarioViaje', 'like', '%'.$conductor.'%') 
        ->where('razonSocial', 'like', '%'.$cliente.'%') 
        ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%') 
        ->where('nombreVehiculo', 'like', '%'.$nombreVehiculo.'%') 
        ->where('estatus', 'like', '%'.$estatus.'%') 
        ->whereIn('idViaje',  function($query){
        $sidu = Session::get('sesionidu');
        $query->select('idViaje')
        ->from('viajes')
        ->where('idu','=',$sidu);
        })
        ->orderBy($anexGrid->columna, $order)
        ->skip($anexGrid->pagina)
        ->take($anexGrid->limite)
        ->get();
      
        $total = DB::table('viajes')            
        ->select('idViaje','fechaInicio','usuarioViaje','razonSocial','sucursales.sucursal','nombreVehiculo','viajes.estatus','viajes.activo',
        DB::raw('DATE_FORMAT(fechaInicio,"%d %b %Y") AS fecha'),
        DB::raw('CONCAT("$",FORMAT(montoGasto,2)) AS montoGasto'),
        DB::raw('(viajes.kmFinal - viajes.kmActual) AS kilometros'))                    
        ->leftJoin('clientes', 'viajes.idc', '=', 'clientes.idc')  
        ->leftJoin('sucursales', 'viajes.idSucursal', '=', 'sucursales.idSucursal')  
        ->leftJoin('vehiculos', 'viajes.idVehiculo', '=', 'vehiculos.idVehiculo') 
        ->where('idViaje', '>', 0)
        ->where('usuarioViaje', 'like', '%'.$conductor.'%') 
        ->where('razonSocial', 'like', '%'.$cliente.'%') 
        ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%') 
        ->where('nombreVehiculo', 'like', '%'.$nombreVehiculo.'%') 
        ->where('estatus', 'like', '%'.$estatus.'%') 
        ->whereIn('idViaje',  function($query){
          $sidu = Session::get('sesionidu');
          $query->select('idViaje')
          ->from('viajes')
          ->where('idu','=',$sidu);
        })
        ->count();

      }
        
      $data = array(
      'total' => $total,
      'data' => $registros
      );

      return response()->json($data, 200);
        
    }
  }

  public function asignarServicioAviaje(Request $request){ 
    
    $idServicios = $request-> idServicios;
    $idViaje = $request-> idViaje;
    $consultaFactura = DB::table('facturas')->select('idFactura','montoFactura','totalConIva')->where('idServicios', '=', $idServicios)->get();
    $idFactura = $consultaFactura[0]->idFactura;

    $datosViaje = \DB::select("SELECT v.motivoViaje,CONCAT(vh.nombreVehiculo,' / ',v.usuarioViaje) as descripcion,v.montoGasto FROM viajes as v
    INNER JOIN vehiculos AS vh ON vh.idVehiculo=v.idVehiculo
    WHERE idViaje = $idViaje");
    if($datosViaje[0]->motivoViaje == ''){
      $motivoViaje = '';
    }else{
      $motivoViaje = $datosViaje[0]->motivoViaje;
    }

    $gastos = new gastos;
    $gastos-> BANCO = 5;
    $gastos-> forma_pago='03 Transferencia electronica';
    $gastos-> etiquetas= $motivoViaje;
    $gastos-> id_factura = $idFactura;
    $gastos-> beneficiario = "EFECTIVALE";
    $gastos-> nombreGasto = "Gasolina";
    $gastos-> idCuenta = 265;
    $gastos-> TIPOGASTO = 'G';
    $gastos-> idServicios =$idServicios;
    $gastos-> idViaje = $idViaje;
    $gastos-> fecha_pago = $request-> fechaInicio;
    $gastos-> iva = 16.00;    
    $gastos-> metodo_pago = 'PPD';
    $gastos-> credito_dias = '7';
    $gastos-> referencia = $motivoViaje;
    $gastos-> factura = '0.0'; //SUBTOTAL
    $gastos-> total_iva = '0.0';
    $gastos-> isr = '0.0';
    $gastos-> total_isr = '0.0';
    $gastos-> total = '0.0';
    $gastos-> descripcion = $datosViaje[0]->descripcion;
    $gastos-> activo = 'Si';
    $gastos-> moneda = '';
    $gastos-> cambioDolar = '0.0';
    $gastos-> save();    

    $cuantosGastosAsignados = DB::table('gastos')->where('idViaje', '=', $idViaje)->count();    
    $nuevoTotal = $datosViaje[0]->montoGasto / $cuantosGastosAsignados;
    
    $subtotal =($nuevoTotal/1.16);
    $totalIva = $nuevoTotal - $subtotal;

    $cambiandoTotal = \DB::UPDATE("UPDATE gastos SET total = ?,factura =?, total_iva = ? WHERE idViaje = ?",[$nuevoTotal,$subtotal,$totalIva,$idViaje]);  

    $consultaGastos = \DB::select("SELECT t2.id,t2.idServicios,c.razonSocial AS cliente,s.sucursal AS sucursal,t2.total,t2.created_at
    FROM(
      SELECT t1.id,t1.idServicios,fact.idc,fact.idSucursal,t1.total,t1.created_at
      FROM(
          SELECT id,idServicios,total,created_at FROM gastos WHERE idViaje = $idViaje
        ) AS t1   
      INNER JOIN facturas AS fact ON fact.idServicios= t1.idServicios
    ) AS t2
    INNER JOIN clientes AS c ON c.idc= t2.idc
    INNER JOIN sucursales AS s ON s.idSucursal= t2.idSucursal
    ORDER BY t2.created_at DESC");

    return view ('reporteGastosViajesAsignados')
    ->with('consultaGastos',$consultaGastos);
  }

  public function eliminarServicioAviaje(Request $request){ 
    $idGasto = $request-> idGasto;
    
    $idViaje = DB::table('gastos')->select('idViaje')->where('id', '=', $idGasto)->get();
    $idViaje= $idViaje[0]->idViaje;
    
    $eliminandoAsignacion= \DB::delete("DELETE FROM gastos WHERE id=?",[$idGasto]);

    $cuantosGastosAsignados = DB::table('gastos')->where('idViaje', '=', $idViaje)->count();
    if ($cuantosGastosAsignados > 0) {
      $totalViaje = \DB::select("SELECT montoGasto FROM viajes WHERE idViaje = $idViaje");
      $nuevoTotal = $totalViaje[0]->montoGasto / $cuantosGastosAsignados;
            
      $subtotal =($nuevoTotal/1.16);
      $totalIva = $nuevoTotal - $subtotal;

      $cambiandoTotal = \DB::UPDATE("UPDATE gastos SET total = ?,factura =?, total_iva = ? WHERE idViaje = ?",[$nuevoTotal,$subtotal,$totalIva,$idViaje]);  
    }    

    $consultaGastos = \DB::select("SELECT t2.id,t2.idServicios,c.razonSocial AS cliente,s.sucursal AS sucursal,t2.total,t2.created_at
    FROM(
      SELECT t1.id,t1.idServicios,fact.idc,fact.idSucursal,t1.total,t1.created_at
      FROM(
          SELECT id,idServicios,total,created_at FROM gastos WHERE idViaje = $idViaje
        ) AS t1   
      INNER JOIN facturas AS fact ON fact.idServicios= t1.idServicios
    ) AS t2
    INNER JOIN clientes AS c ON c.idc= t2.idc
    INNER JOIN sucursales AS s ON s.idSucursal= t2.idSucursal
    ORDER BY t2.created_at DESC");

    return view ('reporteGastosViajesAsignados')
    ->with('consultaGastos',$consultaGastos);
  }

  /* Inicia Funciones Nuevas de Viajes */

  public function reporte_Viajes(Request $request){
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
      $idu = $request->idu;
      Session::put('idusu',$idu);
        
      if ($stipo == 'Administrador') {
        $usuario = usuarios::orderby('nombreUsuario','asc')->get();
        $consulta = \DB::select("SELECT v.idViaje, v.activo,v.montoGasto, v.estatus, (v.kmFinal- v.kmActual) AS kmr,
        v.kmFinal,v.usuarioViaje, v.fechaInicio,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS us, vh.nombreVehiculo AS veh,
        c.razonSocial AS cli, s.sucursal As suc FROM viajes as v
        INNER JOIN vehiculos AS vh ON vh.idVehiculo=v.idVehiculo
        INNER JOIN usuarios AS u ON u.idu= v.idu
        INNER JOIN sucursales AS s ON s.idSucursal= v.idSucursal
        INNER JOIN clientes AS c ON c.idc= v.idc
        ORDER BY v.fechaInicio DESC");

        return view ('reporte_Viajes')
        ->with('usuario',$usuario)
        ->with('consulta',$consulta);
      }
      else{
        $usuario = usuarios::orderby('nombreUsuario','asc')->get();
        $consulta = \DB::select("SELECT v.idViaje, v.activo,v.montoGasto, v.estatus, (v.kmFinal- v.kmActual) AS kmr,
        v.kmFinal,v.usuarioViaje, v.fechaInicio,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS us, vh.nombreVehiculo AS veh,
        c.razonSocial AS cli, s.sucursal As suc FROM viajes as v
        INNER JOIN vehiculos AS vh ON vh.idVehiculo=v.idVehiculo
        INNER JOIN usuarios AS u ON u.idu= v.idu
        INNER JOIN sucursales AS s ON s.idSucursal= v.idSucursal
        INNER JOIN clientes AS c ON c.idc= v.idc
        WHERE idViaje IN(SELECT idViaje AS via FROM viajes WHERE idu=?)
        ORDER BY v.fechaInicio DESC",[$sidu]);

        return view ('reporte_Viajes')
        ->with('usuario',$usuario)
        ->with('consulta',$consulta);
      }
    }
  }

  public function altaSalida_Viajes(){
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
      $sidu = Session::get('sesionidu');              
      $vehiculo = \DB::select('SELECT * FROM vehiculos WHERE idVehiculo NOT IN (SELECT idVehiculo FROM viajes WHERE estatus = "En curso") ORDER BY nombreVehiculo ASC');
      $cliente = clientes::orderby('razonSocial','asc')->get();

      return view ('altaSalida_Viajes')
      ->with('vehiculo',$vehiculo)
      ->with('cliente',$cliente);
    }
  }

  public function GuardarSalida_Viajes(Request $request){
    $idu = $request -> idu;
    $usuarioViaje = $request-> usuarioViaje;
    $fechaInicio = $request-> fechaInicio;
    $idVehiculo = $request-> idVehiculo;
    $tipoViaje = $request-> tipoViaje;
    $idc = $request-> idc;
    $idSucursal = $request-> idSucursal;
    $montoGasto = $request-> montoGasto;
    //  $motivoViaje = $request-> motivoViaje;
    $kmActual = $request-> kmActual;
    $fotoEvidenciaSalida = $request-> fotoEvidenciaSalida;
    $liquidoFrenos = $request-> liquidoFrenos;
    $parabrisas = $request-> parabrisas;
    $llantasSalida = $request-> llantasSalida;
    $lucesSalida = $request-> lucesSalida;
    $anticongelante = $request-> anticongelante;
    $unidadLimpia = $request-> unidadLimpia;
    //  $observacionSalida = $request-> observacionSalida;

    $this->validate ($request,[
    'tipoViaje'=>['required'],
    'idVehiculo'=>['required'],
    'idc'=>['required'],
    'idSucursal'=>['required'],
    'montoGasto'=>['required'],
      //'motivoViaje'=>['regex:/^[A-Z][a-z,A-Z, ,0-9]*$/'],
    'kmActual'=>['regex:/^[0-9]*$/'],
    'fotoEvidenciaSalida'=>['mimes:jpeg,png,gif,jpg'],
    'liquidoFrenos'=>['required'],
    'parabrisas'=>['required'],
    'llantasSalida'=>['required'],
    'lucesSalida'=>['required'],
    'unidadLimpia'=>['required'],
    'anticongelante'=>['required'],]);
      //'observacionSalida'=>'regex:/^[A-Z][a-z,A-Z, ,0-9]*$/',]);

    $file = $request->file('fotoEvidenciaSalida');
    if($file!=""){
      $ldate = date('Ymd_His_');
      $img = $file->getClientOriginalName();
      $img2 = $ldate.$img;
      \Storage::disk('local')->put($img2, \File::get($file));
    }
    else{
      $img2 ="sinfoto.png";
    }

    $viaje=new viajes;
    $viaje-> idu = $request-> idu;
    $viaje-> usuarioViaje = $request-> usuarioViaje;
    $viaje-> fechaInicio = $request-> fechaInicio;
    $viaje-> idVehiculo = $request-> idVehiculo;
    $viaje-> tipoViaje = $request-> tipoViaje;
    $viaje-> idc = $request-> idc;
    $viaje-> idSucursal = $request-> idSucursal;
    $viaje-> montoGasto = $request-> montoGasto;
    $viaje-> motivoViaje = $request-> motivoViaje;
    $viaje-> kmActual = $request-> kmActual;
    $viaje-> fotoEvidenciaSalida = $img2;
    $viaje-> liquidoFrenos = $request-> liquidoFrenos;
    $viaje-> parabrisas = $request-> parabrisas;
    $viaje-> llantasSalida = $request-> llantasSalida;
    $viaje-> lucesSalida = $request-> lucesSalida;
    $viaje-> unidadLimpia = $request-> unidadLimpia;
    $viaje-> anticongelante = $request-> anticongelante;
    $viaje-> observacionSalida = $request-> observacionSalida;
    $viaje-> activo='Si';
    $viaje-> estatus='En curso';
    $viaje-> save();

    return redirect()->route('reporte_Viajes')->with('success','El viaje de '. $viaje->usuarioViaje .' para el '. $viaje->fechaInicio .' ha sido guardado correctamente');
  }

  public function altaRegreso_Viajes($idViaje){
    $consultaImg = \DB::select("SELECT km.fotoEvidenciaSalida FROM viajes AS km Where idViaje = $idViaje ");
    $consulta = viajes::Where ('idViaje','=',$idViaje)->take(1)->get();
    
    //return $consulta;
    // $vehiculoSel= vehiculos::where('idVehiculo',"=",$consulta[0]->idVehiculo)->get();
    // $nomVehiculo =$vehiculoSel[0]->nombreVehiculo;
    // $placas=$vehiculoSel[0]->placas;
    // $idMarca =$vehiculoSel[0]->idMarca;
    $vehiculo= vehiculos::where ('idVehiculo','=',$consulta[0]->idVehiculo)->get();
    //return $vehiculo;
    $clienteSel= clientes::where('idc',"=",$consulta[0]->idc)->get();
    $nomcli =$clienteSel[0]->razonSocial;
    $cliente = clientes::where ('idc','!=',$consulta[0]->idc)->get();
    
    $sucursalSel= sucursales::where('idSucursal',"=",$consulta[0]->idSucursal)->get();
    $nomsuc =$sucursalSel[0]->sucursal;
    $sucursal = sucursales::where ('idSucursal','!=',$consulta[0]->idSucursal)->get();      

    return view ('altaRegreso_Viajes')
    ->with('consulta',$consulta[0])
    ->with('consultaImg',$consultaImg)
    ->with('vehiculo',$vehiculo)
    // ->with('idVehiculosel',$consulta[0]->idVehiculo)
    // ->with('nomVehiculo',$nomVehiculo)
    // ->with('placas',$placas)
    // ->with('idMarca',$idMarca)
    // ->with('vehiculoSel',$vehiculoSel[0])
    ->with('cliente',$cliente)
    ->with('idclientesel',$consulta[0]->idc)
    ->with('nomcli',$nomcli)
    ->with('clienteSel',$clienteSel[0])
    ->with('sucursal',$sucursal)
    ->with('idSucursal',$consulta[0]->idSucursal)
    ->with('nomsuc',$nomsuc)
    ->with('sucursalSel',$sucursalSel[0]);
  }

  public function GuardarRegreso_Viajes(Request $request){
    //return $request;
    $idViaje=$request-> idViaje;
    $idVehiculo=$request-> idVehiculo;
    $fechaFin=$request-> fechaFin;
    $kmFinal=$request-> kmFinal;
    $fotoEvidenciaRegreso=$request-> fotoEvidenciaRegreso;
    $golpes=$request-> golpes;
    $rayaduras=$request-> rayaduras;
    $llantasEntrega=$request-> llantasEntrega;
    $lucesEntrega=$request-> lucesEntrega;
    $unidadLimpiaEntrega=$request-> unidadLimpiaEntrega;
    $observacionEntrega=$request-> observacionEntrega;

    $this->validate ($request,[
    'kmFinal'=>'required|numeric|min:$kmActual',
    'golpes'=>['required'],
    'rayaduras'=>['required'],
    'llantasEntrega'=>['required'],
    'lucesEntrega'=>['required'],
    'unidadLimpiaEntrega'=>['required'],
    'fotoEvidenciaRegreso'=>['mimes:jpeg,png,gif,jpg'],]);
    //'observacionEntrega'=>'required',]);

    $file = $request->file('fotoEvidenciaRegreso');
    if($file!=""){
      $ldate = date('Ymd_His_');
      $img = $file->getClientOriginalName();
      $img2 = $ldate.$img;
      \Storage::disk('local')->put($img2, \File::get($file));
    }
    else{
      $img2 ="sinfoto.png";
    }

    //$actualizakm=\DB::update("UPDATE vehiculos SET kmActual='$kmFinal' WHERE idVehiculo=idVehiculo");
			
    $vehiculos = vehiculos::find($idVehiculo);
    $vehiculos->kmActual = $kmFinal;
    //return $vehiculos;
    $vehiculos ->save();

    $viajes = viajes::where('idViaje', $idViaje)->get();
    $via=viajes::find($idViaje);
    $via-> fechaFin= $request-> fechaFin;
    $via-> kmFinal= $request-> kmFinal;
    $via-> fotoEvidenciaRegreso = $img2;
    $via-> golpes= $request-> golpes;
    $via-> rayaduras= $request-> rayaduras;
    $via-> llantasEntrega= $request-> llantasEntrega;
    $via-> lucesEntrega= $request-> lucesEntrega;
    $via-> unidadLimpiaEntrega=$request-> unidadLimpiaEntrega;
    $via-> observacionEntrega= $request-> observacionEntrega;
    $via-> estatus= 'Finalizado';
    $via-> save();

    return redirect()->route('reporte_Viajes')->with('success','El viaje de '. $via->usuarioViaje .' ha sido finalizado correctamente');
  }

  public function detalle_Viajes($idViaje){
    $consultaImg = \DB::select("SELECT p.fotoEvidenciaSalida FROM viajes AS p Where idViaje = $idViaje ");
    $consultaImg2 = \DB::select("SELECT p.fotoEvidenciaRegreso FROM viajes AS p Where idViaje = $idViaje ");
    $consulta = viajes::Where ('idViaje','=',$idViaje)->take(1)->get();

    $clienteSel= clientes::where('idc',"=",$consulta[0]->idc)->get();
    $nomcli =$clienteSel[0]->razonSocial;
    $cliente = clientes::where ('idc','=',$consulta[0]->idc)->get();


    $vehiculoSel= vehiculos::where('idVehiculo',"=",$consulta[0]->idVehiculo)->get();
    $nomVehiculo =$vehiculoSel[0]->nombreVehiculo;
    $placas=$vehiculoSel[0]->placas;
    $idMarca =$vehiculoSel[0]->idMarca;
    $vehiculo= vehiculos::where ('idVehiculo','=',$consulta[0]->idVehiculo)->get();
    
    $sucursalSel= sucursales::where('idSucursal',"=",$consulta[0]->idSucursal)->get();
    $nomsuc =$sucursalSel[0]->sucursal;
    $sucursal = sucursales::where ('idSucursal','!=',$consulta[0]->idSucursal)->get();      

    return view ('detalle_Viajes')
    ->with('consulta',$consulta[0])
    ->with('vehiculo',$vehiculo)
    ->with('idVehiculosel',$consulta[0]->idVehiculo)
    ->with('nomVehiculo',$nomVehiculo)
    ->with('placas',$placas)
    ->with('idMarca',$idMarca)
    ->with('vehiculoSel',$vehiculoSel[0])
    ->with('cliente',$cliente)
    ->with('idclientesel',$consulta[0]->idc)
    ->with('nomcli',$nomcli)
    ->with('clienteSel',$clienteSel[0])
    ->with('consultaImg',$consultaImg)
    ->with('consultaImg2',$consultaImg2)
    ->with('sucursal',$sucursal)
    ->with('idSucursal',$consulta[0]->idSucursal)
    ->with('nomsuc',$nomsuc)
    ->with('sucursalSel',$sucursalSel[0]);

  }

  public function modificar_Viajes($idViaje){
    $consultaImg = \DB::select("SELECT p.fotoEvidenciaSalida FROM viajes AS p Where idViaje = $idViaje ");
    $consultaImg2 = \DB::select("SELECT p.fotoEvidenciaRegreso FROM viajes AS p Where idViaje = $idViaje ");
    $consulta = viajes::Where('idViaje','=',$idViaje)->get();

    $clienteSel=clientes::where('idc',"=",$consulta[0]->idc)->get();
    $nomcli=$clienteSel[0]->razonSocial;
    $cliente=clientes::where ('idc','!=',$consulta[0]->idc)->get();
    $clientec = clientes::orderby('razonSocial','asc')->get();

    $sucursalSel= sucursales::where('idSucursal',"=",$consulta[0]->idSucursal)->get();
    $nomsuc =$sucursalSel[0]->sucursal;
    $sucursal = sucursales::where ('idSucursal','!=',$consulta[0]->idSucursal)->get();
    
    $vehiculoSel=vehiculos::where('idVehiculo',"=",$consulta[0]->idVehiculo)->get();
    $nomVehiculo=$vehiculoSel[0]->nombreVehiculo;
    $placas=$vehiculoSel[0]->placas;
    $tipoV=$vehiculoSel[0]->tipoVehiculo;
    // $vehiculo=vehiculos::orderby('nombreVehiculo','asc')->get();
    $vehiculo = \DB::select('SELECT * FROM vehiculos WHERE idVehiculo NOT IN (SELECT idVehiculo FROM viajes WHERE estatus = "En curso") AND idVehiculo !=?',[$consulta[0]->idVehiculo]);

    $comboFacturas =  \DB::select('SELECT f.idServicios,f.idc,f.idSucursal, 
    c.razonSocial AS cliente, s.sucursal As sucursal FROM facturas as f              
    INNER JOIN sucursales AS s ON s.idSucursal= f.idSucursal
    INNER JOIN clientes AS c ON c.idc= f.idc
    ORDER BY idServicios DESC');

    $consultaGastos = \DB::select("SELECT t2.id,t2.idServicios,c.razonSocial AS cliente,s.sucursal AS sucursal,t2.total,t2.created_at
    FROM(
      SELECT t1.id,t1.idServicios,fact.idc,fact.idSucursal,t1.total,t1.created_at
      FROM(
          SELECT id,idServicios,total,created_at FROM gastos WHERE idViaje = $idViaje
        ) AS t1   
      INNER JOIN facturas AS fact ON fact.idServicios= t1.idServicios
    ) AS t2
    INNER JOIN clientes AS c ON c.idc= t2.idc
    INNER JOIN sucursales AS s ON s.idSucursal= t2.idSucursal
    ORDER BY t2.created_at DESC");

    //$marcaSel=marcas::where('idMarca',"=",$consulta[0]->idMarca)->get();
    //$nomMarca=$marcaSel[0]->nombreMarca;
    //$marca=marcas::where ('idMarca','!=',$consulta[0]->idMarca)->get();

    return view ('editar_Viajes')
    ->with('consulta',$consulta[0])
    ->with('clientec',$clientec)
    //Datos del para ver la marca del vehiculo
    //->with('marcaSel',$marcaSel[0])
    //->with('nomMarca',$nomMarca)
    //->with('marca',$marca)
    //->with('idmarcasel',$consulta[0]->idMarca)
    //Datos del vehiculo
    ->with('vehiculoSel',$vehiculoSel[0])
    ->with('nomVehiculo',$nomVehiculo)
    ->with('placas',$placas)
    ->with('tipoV',$tipoV)
    ->with('vehiculo',$vehiculo)
    ->with('idVehiculosel',$consulta[0]->idVehiculo)
    //Datos del cliente
    ->with('clienteSel',$clienteSel[0])
    ->with('nomcli',$nomcli)
    ->with('cliente',$cliente)
    ->with('idclientesel',$consulta[0]->idc)
    //Imagenes
    ->with('consultaImg',$consultaImg)
    ->with('consultaImg2',$consultaImg2)
    ->with('sucursal',$sucursal)
    ->with('idSucursal',$consulta[0]->idSucursal)
    ->with('nomsuc',$nomsuc)
    ->with('sucursalSel',$sucursalSel[0])
    ->with('comboFacturas',$comboFacturas)
    ->with('consultaGastos',$consultaGastos);
  }

  public function editar_Viajes(Request $request){
    $idViaje = $request-> idViaje;
    $idVehiculo=$request-> idVehiculo;
    $usuarioViaje = $request-> usuarioViaje;
    $fechaInicio = $request-> fechaInicio;
    $idVehiculo = $request-> idVehiculo;
    $tipoViaje = $request-> tipoViaje;
    $idc = $request-> idc;
    $idSucursal = $request-> idSucursal;
    $montoGasto = $request-> montoGasto;
    $motivoViaje = $request-> motivoViaje;
    $kmActual = $request-> kmActual;
    $liquidoFrenos = $request-> liquidoFrenos;
    $parabrisas = $request-> parabrisas;
    $llantasSalida = $request-> llantasSalida;
    $lucesSalida = $request-> lucesSalida;
    $anticongelante = $request-> anticongelante;
    $observacionSalida = $request-> observacionSalida;
    $fechaFin=$request-> fechaFin;
    $kmFinal=$request-> kmFinal;
    $golpes=$request-> golpes;
    $rayaduras=$request-> rayaduras;
    $llantasEntrega=$request-> llantasEntrega;
    $lucesEntrega=$request-> lucesEntrega;
    $unidadLimpia=$request-> unidadLimpia;
    $unidadLimpiaEntrega=$request-> unidadLimpiaEntrega;
    $observacionEntrega=$request-> observacionEntrega;

    $this->validate ($request,[
    'tipoViaje'=>['required'],
    'idVehiculo'=>['required'],
    'idc'=>['required'],
    'idSucursal'=>['required'],
    'montoGasto'=>['required'],
    //'motivoViaje'=>['regex:/^[A-Z][a-z,A-Z, ,0-9]*$/'],
    'kmActual'=>['regex:/^[0-9]*$/'],
    'fotoEvidenciaSalida'=>['mimes:jpeg,png,gif,jpg'],
    'liquidoFrenos'=>['required'],
    'parabrisas'=>['required'],
    'llantasSalida'=>['required'],
    'lucesSalida'=>['required'],
    'anticongelante'=>['required'],
    //'observacionSalida'=>['regex:/^[A-Z][a-z,A-Z, ,0-9]*$/'],
    'kmFinal'=>['regex:/^[0-9]*$/'],
    'golpes'=>['required'],
    'rayaduras'=>['required'],
    'unidadLimpia'=>['required'],
    'unidadLimpiaEntrega'=>['required'],
    'rayaduras'=>['required'],
    'llantasEntrega'=>['required'],
    'fotoEvidenciaRegreso'=>['mimes:jpeg,png,gif,jpg'],
    'lucesEntrega'=>['required'],]);
    //'observacionEntrega'=>'required',]);

    $file = $request->file('fotoEvidenciaSalida');
    if($file!=""){
      $ldate = date('Ymd_His_');
      $img = $file->getClientOriginalName();
      $img2 = $ldate.$img;
      \Storage::disk('local')->put($img2, \File::get($file));
    }


    $file2 = $request->file('fotoEvidenciaRegreso');
    if($file2!=""){
      $ldate = date('Ymd_His_');
      $img3 = $file2->getClientOriginalName();
      $img4 = $ldate.$img3;
      \Storage::disk('local')->put($img4, \File::get($file2));
    }


    $vehiculos = vehiculos::find($idVehiculo);
    $vehiculos->kmActual = $kmFinal;
    //return $vehiculos;
    $vehiculos ->save();

    $viaj = viajes::find($idViaje);
    if($file!=""){
          $viaj->fotoEvidenciaSalida=$img2;
    }
    if($file2!=""){
          $viaj->fotoEvidenciaRegreso=$img4;
    }
    $viaj-> idViaje = $request-> idViaje;
    $viaj-> usuarioViaje = $request-> usuarioViaje;
    $viaj-> fechaInicio = $request-> fechaInicio;
    $viaj-> fechaFin = $request-> fechaFin;
    $viaj-> idVehiculo = $request-> idVehiculo;
    $viaj-> tipoViaje = $request-> tipoViaje;
    $viaj-> idc = $request-> idc;
    $viaj-> idSucursal = $request-> idSucursal;
    $viaj-> montoGasto = $request-> montoGasto;
    $viaj-> motivoViaje = $request-> motivoViaje;
    $viaj-> kmActual = $request-> kmActual;
    $viaj-> liquidoFrenos = $request-> liquidoFrenos;
    $viaj-> parabrisas = $request-> parabrisas;
    $viaj-> llantasSalida = $request-> llantasSalida;
    $viaj-> lucesSalida = $request-> lucesSalida;
    $viaj-> anticongelante = $request-> anticongelante;
    $viaj-> observacionSalida = $request-> observacionSalida;
    $viaj-> kmFinal = $request-> kmFinal;
    $viaj-> liquidoFrenos = $request-> liquidoFrenos;
    $viaj-> golpes = $request-> golpes;
    $viaj-> rayaduras = $request-> rayaduras;
    $viaj-> llantasEntrega = $request-> llantasEntrega;
    $viaj-> lucesEntrega = $request-> lucesEntrega;
    $viaj-> unidadLimpia=$request-> unidadLimpia;
    $viaj-> unidadLimpiaEntrega=$request-> unidadLimpiaEntrega;
    $viaj-> observacionEntrega = $request-> observacionEntrega;
    $viaj-> observacionSalida = $request-> observacionSalida;
    $viaj-> save();

    return redirect()->route('reporte_Viajes')->with('success','El viaje de '. $viaj->usuarioViaje .' ha sido editado correctamente');


  }

  public function comboca_(Request $request){
    $idc = $request->get('idc');
    $sucursales = sucursales::where('idc','=',$idc,'AND','activo','=','Si')->get();

    return view ('combo_p')
    ->with('sucursales',$sucursales);
  }

  public function modificarSalida_Viajes($idViaje){
    $consultaImg = \DB::select("SELECT p.fotoEvidenciaSalida FROM viajes AS p Where idViaje = $idViaje ");
    $consulta = viajes::Where ('idViaje','=',$idViaje)->get();

    $clienteSel= clientes::where('idc',"=",$consulta[0]->idc)->get();
    $nomcli =$clienteSel[0]->razonSocial;
    $cliente = clientes::where ('idc','!=',$consulta[0]->idc)->get();
    $clientec = clientes::orderby('razonSocial','asc')->get();

    
    $sucursalSel= sucursales::where('idSucursal',"=",$consulta[0]->idSucursal)->get();
    $nomsuc =$sucursalSel[0]->sucursal;
    $sucursal = sucursales::where ('idSucursal','!=',$consulta[0]->idSucursal)->get();

    $vehiculoSel= vehiculos::where('idVehiculo',"=",$consulta[0]->idVehiculo)->get();
    $nomVehiculo =$vehiculoSel[0]->nombreVehiculo;
    $placas=$vehiculoSel[0]->placas;
    $idMarca =$vehiculoSel[0]->idMarca;
    // $vehiculo= vehiculos::where ('idVehiculo','!=',$consulta[0]->idVehiculo)->get();
    $vehiculo = \DB::select('SELECT * FROM vehiculos WHERE idVehiculo NOT IN (SELECT idVehiculo FROM viajes WHERE estatus = "En curso") AND idVehiculo !=?',[$consulta[0]->idVehiculo]);                          

    return view ('editarSalida_Viajes')
    ->with('consulta',$consulta[0])
    ->with('clientec',$clientec)
    ->with('vehiculo',$vehiculo)
    ->with('idVehiculosel',$consulta[0]->idVehiculo)
    ->with('nomVehiculo',$nomVehiculo)
    ->with('placas',$placas)
    ->with('idMarca',$idMarca)
    ->with('vehiculoSel',$vehiculoSel[0])
    ->with('cliente',$cliente)
    ->with('idclientesel',$consulta[0]->idc)
    ->with('nomcli',$nomcli)
    ->with('clienteSel',$clienteSel[0])                
    ->with('sucursal',$sucursal)
    ->with('idSucursal',$consulta[0]->idSucursal)
    ->with('nomsuc',$nomsuc)
    ->with('sucursalSel',$sucursalSel[0])
    ->with('consultaImg',$consultaImg);

  }

  public function editarSalida_Viajes(Request $request){
    $idViaje = $request-> idViaje;
    $usuarioViaje = $request-> usuarioViaje;
    $fechaInicio = $request-> fechaInicio;
    $idVehiculo = $request-> idVehiculo;
    $tipoViaje = $request-> tipoViaje;
    $idc = $request-> idc;
    $idSucursal = $request-> idSucursal;
    $montoGasto = $request-> montoGasto;
    $motivoViaje = $request-> motivoViaje;
    $kmActual = $request-> kmActual;
    $liquidoFrenos = $request-> liquidoFrenos;
    $parabrisas = $request-> parabrisas;
    $llantasSalida = $request-> llantasSalida;
    $lucesSalida = $request-> lucesSalida;
    $anticongelante = $request-> anticongelante;
    $unidadLimpia = $request-> unidadLimpia;
    $observacionSalida = $request-> observacionSalida;

    $this->validate ($request,[
    'tipoViaje'=>['required'],
    'idVehiculo'=>['required'],
    'idc'=>['required'],
    'idSucursal'=>['required'],
    'montoGasto'=>['required'],
    //'motivoViaje'=>['regex:/^[A-Z][a-z,A-Z, ,0-9]*$/'],
    'kmActual'=>['regex:/^[0-9]*$/'],
    'fotoEvidenciaSalida'=>['mimes:jpeg,png,gif,jpg'],
    'liquidoFrenos'=>['required'],
    'parabrisas'=>['required'],
    'llantasSalida'=>['required'],
    'lucesSalida'=>['required'],
    'unidadLimpia'=>['required'],
    'anticongelante'=>['required'],]);
    //'observacionSalida'=>'regex:/^[A-Z][a-z,A-Z, ,0-9]*$/',]);

    $file = $request->file('fotoEvidenciaSalida');
    if($file!=""){
      $ldate = date('Ymd_His_');
      $img = $file->getClientOriginalName();
      $img2 = $ldate.$img;
      \Storage::disk('local')->put($img2, \File::get($file));
    }
      $via = viajes::find($idViaje);
    if($file!=""){
          $via->fotoEvidenciaSalida=$img2;
    }

    $via-> idViaje = $request-> idViaje;
    $via-> usuarioViaje = $request-> usuarioViaje;
    $via-> fechaInicio = $request-> fechaInicio;
    $via-> idVehiculo = $request-> idVehiculo;
    $via-> tipoViaje = $request-> tipoViaje;
    $via-> idc = $request-> idc;
    $via-> idSucursal = $request-> idSucursal;
    $via-> montoGasto = $request-> montoGasto;
    $via-> motivoViaje = $request-> motivoViaje;
    $via-> kmActual = $request-> kmActual;
    $via-> liquidoFrenos = $request-> liquidoFrenos;
    $via-> parabrisas = $request-> parabrisas;
    $via-> llantasSalida = $request-> llantasSalida;
    $via-> lucesSalida = $request-> lucesSalida;
    $via-> anticongelante = $request-> anticongelante;
    $via-> unidadLimpia = $request-> unidadLimpia;
    $via-> observacionSalida = $request-> observacionSalida;
    $via-> activo='Si';
    $via-> estatus='En curso';
    $via-> save();

    return redirect()->route('reporte_Viajes')->with('success','El viaje de '. $via->usuarioViaje .' ha sido editado correctamente');

  }

  public function eliminarSalida_Viajes($idViaje){/*Recibe este parametro y lo guarda en esa variable*/
    $viajes = \DB::UPDATE("update viajes
    set activo ='No', estatus='Cancelado' where idViaje=$idViaje");
    
    return redirect()->route('reporte_Viajes')->with('success','El viaje ha sido eliminado correctamente');
  }

  public function restaurarSalida_Viajes($idViaje){ //restarura el valos de NO a SI en el campo activo
    $viajes= \DB::UPDATE("update viajes
    set activo ='Si' , estatus='En curso'where idViaje=$idViaje");

    return redirect()->route('reporte_Viajes')->with('success','El viaje ha sido restaurado correctamente');
  }

  /* Finalizan Funciones Nuevas de Viajes */

}