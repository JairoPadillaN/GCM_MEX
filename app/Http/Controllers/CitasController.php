<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\clientes;
use App\sucursales;
use App\usuarios;
use App\citas;
use App\login;
use DB;
use Session;
use Mail;

class CitasController extends Controller
{
    public function AltaCitas(){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
        
        if($sname == '' or $sidu =='' or $stipo==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else{
            $cliente = clientes::orderby('razonSocial','asc')->get();
            $sucursal = sucursales::orderby('sucursal','asc')->get();
            $usuario = usuarios::orderby('nombreUsuario','asc')->get();
            
            return view('altaCitas')
            ->with('cliente',$cliente)
            ->with('sucursal',$sucursal)
            ->with('usuario',$usuario);
        }

         
    }
    function infoCita(Request $request){
        $idc = $request->idc;
        $idSucursal = $request->idSucursal;
        $contacto = $request->contacto;

        $cliente = \DB::Select("SELECT razonSocial FROM clientes WHERE idc =?",[$idc]);
        $NewCliente = $cliente[0]->razonSocial;

        $sucursal = \DB::Select("SELECT sucursal FROM sucursales WHERE idSucursal =?",[$idSucursal]);
        $NewSucursal = $sucursal[0]->sucursal;
        


        return "Cliente: ".$NewCliente." "."-------"." "."Sucursal: ".$NewSucursal." "."-------"." "."Contacto: ".$contacto;

      }

    public function comboSucursalesActivo(Request $request){

        $idc =$request -> idc;

        $consulta = sucursales::where('idc','=',$idc)->get();
        
        return view ('comboSucursalesActivo')
        ->with('consulta',$consulta);
        // return $consulta;

    }

    public function comboCon(Request $request){
        $idSucursal =$request -> idSucursal;
        $consulta = sucursales::where('idSucursal','=',$idSucursal)->get();
        return view ('comboContacto')
        ->with('consulta',$consulta);
    }
    

    public function GuardarCitas(Request $request){
        
        $fechaAlta=$request->fechaAlta;
        $empresaSeguimiento=$request->empresaSeguimiento;
        $idu=$request->idu;
        $registradoPor=$request->registradoPor;
        $idc=$request->idc;
        $idSucursal=$request->idSucursal;
        $contacto=$request->contacto;
        $fechaCita=$request->fechaCita;
        $hora=$request->hora;
        $lugar=$request->lugar;
        $responsable=$request->responsable;
        $observacionCita=$request->observacionCita;

       
        $this->validate ($request,[
            'fechaAlta'=>['required'],
            'empresaSeguimiento'=>['required'],
            'idc'=>['required'],
            'idu'=>['required'],
            'responsable'=>['required'],
            'contacto'=>['required'],
            
            'idSucursal'=>['required'],
            'fechaCita'=>['required'],
            
        ]);
            // consultas para correo
        $consultaCliente=clientes::Where('idc','=', $idc)->get();
        $nombreCliente = $consultaCliente[0]->razonSocial;
        $cliente=clientes::Where('idc','!=', $idc)->get();

        $consultaSucursal=sucursales::Where('idSucursal','=', $idSucursal)->get();
        $nombreSucursal = $consultaSucursal[0]->sucursal;
        
        
        $consultaUsuario=usuarios::Where('idu','=', $responsable)->get();
        $correoUsuario = $consultaUsuario[0]->correo;
        $nombre = $consultaUsuario[0]->nombreUsuario;
        $paterno = $consultaUsuario[0]->aPaterno;
        $materno = $consultaUsuario[0]->aMaterno;
        $consultaUsuario=usuarios::Where('idu','!=', $responsable)->get();

        // Datos de correo
        $subject = "Cita asignada";
        $datos = array(
            'fechaAlta'=>$fechaAlta,
            'empresaSeguimiento'=>$empresaSeguimiento,
            'registradoPor'=>$registradoPor,
            'nombreCliente'=>$nombreCliente,
            'nombreSucursal'=>$nombreSucursal,
            'contacto'=>$contacto,
            'fechaCita'=>$fechaCita,
            'hora'=>$hora,
            'lugar'=>$lugar,
            'nombre'=>$nombre,
            'paterno'=>$paterno,
            'materno'=>$materno,
            'observacionCita'=>$observacionCita);
        Mail::send('correoCitas', $datos, function($msj) use($subject,$correoUsuario){
            $msj->from("citas.germancontrolmotion@gmail.com","German Control Motion");
            $msj->subject($subject);
            $msj->to($correoUsuario);
        });

        $cita = new citas;
        
        $cita->empresaSeguimiento=$request->empresaSeguimiento;
        $cita->fechaAlta=$request->fechaAlta;
        $cita->idu=$request->idu;
        $cita->registradoPor=$request->registradoPor;
        $cita->idc=$request->idc;
        $cita->idSucursal=$request->idSucursal;
        $cita->contacto=$request->contacto;
        $cita->fechaCita=$request->fechaCita;
        $cita->hora=$request->hora;
        $cita->lugar=$request->lugar;
        $cita->responsable=$request->responsable;
        $cita->observacionCita=$request->observacionCita;
        $cita->estatus='Espera';
        $cita->activo="Si";
        $cita->save();
        
        $proceso='Alta de citas';
        $mensaje="Registro guardado correctamente";
        return view ('mensajeCitas')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
        //  return $contacto;
        
    }

    public function reporteCitas(Request $request){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
        
        if($sname == '' or $sidu =='' or $stipo==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else{
        $sidu = Session::get('sesionidu');
        $consultaCreadas = \DB::select("SELECT c.idCita, c.fechaAlta, DATE_FORMAT(c.fechaAlta,'%d %b %Y') AS fechaFormato,c.empresaSeguimiento,
                             cli.razonSocial,s.sucursal, c.registradoPor, c.idu,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS us,
                            c.observacionCita, c.responsable, c.resultados, c.estatus, c.activo
                            FROM citas AS c INNER JOIN sucursales AS s
                            ON s.idSucursal=c.idSucursal
                            INNER JOIN usuarios AS u
                            ON u.idu=c.responsable
                            INNER JOIN clientes AS cli
                            ON cli.idc=c.idc 
                            WHERE c.idu=?", [$sidu]);

        $consultaAsignadas = \DB::select("SELECT c.idCita, c.fechaAlta, DATE_FORMAT(c.fechaAlta,'%d %b %Y') AS fechaFormato,c.empresaSeguimiento, cli.razonSocial,s.sucursal, 
                            c.registradoPor, c.idu,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS us,
                            c.observacionCita, c.responsable, c.resultados, c.estatus, c.activo
                            FROM citas AS c INNER JOIN sucursales AS s
                            ON s.idSucursal=c.idSucursal
                            INNER JOIN usuarios AS u
                            ON u.idu=c.responsable
                            INNER JOIN clientes AS cli
                            ON cli.idc=c.idc 
                            WHERE u.idu=?", [$sidu]);
        return view ('reporteCitas')
        ->with('sidu',$sidu)
        ->with('stipo',$stipo)
        ->with('consultaCreadas',$consultaCreadas)
        ->with('consultaAsignadas',$consultaAsignadas);
        }
 }
 public function reporteCitasAsignada(Request $request){
    $sidu = Session::get('sesionidu');
    $fechaInicio=$request->fechaInicio;
    $fechaFin=$request->fechaFin;
    
    $consultaFechas = \DB::select("SELECT c.idCita, c.fechaAlta,DATE_FORMAT(c.fechaAlta,'%d %b %Y') AS fechaFormato, c.empresaSeguimiento, cli.razonSocial,s.sucursal, 
                        c.registradoPor, c.idu,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS us,
                        c.observacionCita, c.responsable, c.resultados, c.estatus, c.activo
                        FROM citas AS c INNER JOIN sucursales AS s
                        ON s.idSucursal=c.idSucursal
                        INNER JOIN usuarios AS u
                        ON u.idu=c.responsable
                        INNER JOIN clientes AS cli
                        ON cli.idc=c.idc 
                        WHERE u.idu=? AND c.fechaAlta>=? AND c.fechaAlta<=? ", [$sidu,$fechaInicio,$fechaFin]);
        $cuantos=count($consultaFechas);

    return view ('reporteCitaAsignada')
    ->with('sidu',$sidu)
    ->with('cuantos',$cuantos)
    ->with('fechaInicio',$fechaInicio)
    ->with('fechaFin',$fechaFin)
    ->with('consultaFechas',$consultaFechas);

// return $fechaInicio;
}

public function reporteCitasCreada(Request $request){
    $sidu = Session::get('sesionidu');
    $fechaInicio=$request->fechaInicio;
    $fechaFin=$request->fechaFin;
    
    $consultaFechas = \DB::select("SELECT c.idCita, c.fechaAlta,DATE_FORMAT(c.fechaAlta,'%d %b %Y') AS fechaFormato, c.empresaSeguimiento, cli.razonSocial,s.sucursal, 
                        c.registradoPor, c.idu,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS us,
                        c.observacionCita, c.responsable, c.resultados, c.estatus, c.activo
                        FROM citas AS c INNER JOIN sucursales AS s
                        ON s.idSucursal=c.idSucursal
                        INNER JOIN usuarios AS u
                        ON u.idu=c.responsable
                        INNER JOIN clientes AS cli
                        ON cli.idc=c.idc 
                        WHERE c.idu=? AND fechaAlta BETWEEN ? AND ? ", [$sidu,$fechaInicio,$fechaFin]);
        $cuantos=count($consultaFechas);

    return view ('reporteCitaCreada')
    ->with('sidu',$sidu)
    ->with('fechaInicio',$fechaInicio)
    ->with('fechaFin',$fechaFin)
    ->with('cuantos',$cuantos)
    ->with('consultaFechas',$consultaFechas);

// return $fechaInicio;
}
    public function detalleCitas($idCita){
        
        $consulta = citas::Where ('idCita','=',$idCita)->get();
        
        $consultaCliente=clientes::Where('idc','=', $consulta[0]->idc)->get();
        $nombreCliente = $consultaCliente[0]->razonSocial;
        $cliente=clientes::Where('idc','!=', $consulta[0]->idc)->get();
     

        $consultaSucursal=sucursales::Where('idSucursal','=', $consulta[0]->idSucursal)->get();
        $nombreSucursal = $consultaSucursal[0]->sucursal;
        $sucursal=sucursales::Where('idSucursal','!=', $consulta[0]->idSucursal)->get();

        $consultaUsuario=usuarios::Where('idu','=', $consulta[0]->idu)->get();
        $nombreUsuario = $consultaUsuario[0]->nombreUsuario;
        $paterno = $consultaUsuario[0]->aPaterno;
        $materno = $consultaUsuario[0]->aMaterno;
        $usuario=usuarios::Where('idu','=', $consulta[0]->idu)->get();

        $consultaCheckServicios = \DB::select("SELECT serviciosOfrecer FROM citas WHERE idCita = $idCita");
        $cadenaCheckServicios=$consultaCheckServicios[0]->serviciosOfrecer;
        $arrayCheckServicios=explode(', ', $cadenaCheckServicios);

        $consultaCheckMarcas = \DB::select("SELECT marcas FROM citas WHERE idCita = $idCita");
        $cadenaCheckMarcas=$consultaCheckMarcas[0]->marcas;
        $arrayCheckMarcas=explode(', ', $cadenaCheckMarcas);
        
            return view('detalleCitas')
            ->with('consulta',$consulta[0])
            ->with('cliente',$cliente)
            ->with('arrayCheckServicios',$arrayCheckServicios)
            ->with('arrayCheckMarcas',$arrayCheckMarcas)
            ->with('sucursal',$sucursal)
            ->with('usuario',$usuario)
            ->with('idClienteSel',$consulta[0]->idc)
            ->with('idSucursalSel',$consulta[0]->idSucursal)
            ->with('idUsuarioSel',$consulta[0]->idu)
            ->with('nombreCliente',$nombreCliente)
            ->with('nombreUsuario',$nombreUsuario)
            ->with('paterno',$paterno)
            ->with('materno',$materno)
            ->with('nombreSucursal',$nombreSucursal);

    }

    public function modificarCita($idCita){
        $consulta = citas::Where ('idCita','=',$idCita)->get();

        $consultaCliente=clientes::Where('idc','=', $consulta[0]->idc)->get();
        $nombreCliente = $consultaCliente[0]->razonSocial;
        $cliente=clientes::Where('idc','!=', $consulta[0]->idc)->get();
        $clienteOrden = clientes::orderby('razonSocial','asc')->get();

        $consultaModal = citas::Where ('idCita','=',$idCita)->get();
        $consultaSucursal=sucursales::Where('idSucursal','=', $consulta[0]->idSucursal)->get();
        $sucursalSel= sucursales::where('idSucursal',"=",$consultaSucursal[0]->idSucursal)->get();
        $nombreSucursal = $consultaSucursal[0]->sucursal;
        $contactoVentas = $consultaSucursal[0]->contactoVentas;
        $idSucursal = $consultaSucursal[0]->idSucursal;
        $sucursal=sucursales::Where('idSucursal','!=', $consulta[0]->idSucursal)->get();

        $consultaCita=citas::Where('contacto','=', $consulta[0]->contacto)->get();
        $nombreContacto = $consultaCita[0]->contacto;
        $cita=citas::Where('contacto','!=', $consulta[0]->contacto)->get();

        $consultaUsuario=usuarios::Where('idu','=', $consulta[0]->responsable)->get();
        $nombreUsuario = $consultaUsuario[0]->nombreUsuario;
        $paterno = $consultaUsuario[0]->aPaterno;
        $materno = $consultaUsuario[0]->aMaterno;
        $usuarioOrden = usuarios::orderby('nombreUsuario','asc')->get();

        $consultaCheckServicios = \DB::select("SELECT serviciosOfrecer FROM citas WHERE idCita = $idCita");
        $cadenaCheckServicios=$consultaCheckServicios[0]->serviciosOfrecer;
        $arrayCheckServicios=explode(', ', $cadenaCheckServicios);

        $consultaCheckMarcas = \DB::select("SELECT marcas FROM citas WHERE idCita = $idCita");
        $cadenaCheckMarcas=$consultaCheckMarcas[0]->marcas;
        $arrayCheckMarcas=explode(', ', $cadenaCheckMarcas);

        // Check sucursales
        $consultaCheckServiciosSucursales = \DB::select("SELECT s.servicios FROM sucursales AS s WHERE idSucursal=$idSucursal ");
        $cadenaCheckServiciosSucursales= $consultaCheckServiciosSucursales[0]->servicios;
        $arrayCheckServiciosSucursales=explode(', ',$cadenaCheckServiciosSucursales);

        $consultaCheckMarcasSucursales = \DB::select("SELECT s.marcas FROM sucursales AS s WHERE idSucursal=$idSucursal ");
        $cadenaCheckMarcasSucursales= $consultaCheckMarcasSucursales[0]->marcas;
        $arrayCheckMarcasSucursales=explode(', ',$cadenaCheckMarcasSucursales);
        
            return view('editarCita')
            ->with('consulta',$consulta[0])
            ->with('cliente',$cliente)
            ->with('arrayCheckServicios',$arrayCheckServicios)
            ->with('arrayCheckMarcas',$arrayCheckMarcas)
            ->with('arrayCheckServiciosSucursales',$arrayCheckServiciosSucursales)
            ->with('arrayCheckMarcasSucursales',$arrayCheckMarcasSucursales)
            ->with('clienteOrden',$clienteOrden)
            ->with('usuarioOrden',$usuarioOrden)
            ->with('idClienteSel',$consulta[0]->idc)
            ->with('idSucursalSel',$consulta[0]->idSucursal)
            ->with('idSucursal',$idSucursal)
            ->with('idUsuarioSel',$consulta[0]->responsable)
            ->with('nombreCliente',$nombreCliente)
            ->with('nombreUsuario',$nombreUsuario)
            ->with('paterno',$paterno)
            ->with('materno',$materno)
            ->with('nombreContacto',$nombreContacto)
            ->with('contactoVentas',$contactoVentas)
            ->with('consultaSucursal',$consultaSucursal[0])
            ->with('sucursalSel',$sucursalSel)
            ->with('nombreSucursal',$nombreSucursal);
        //  return $consultaSucursal;
    }

    public function modificarCitaAsignada($idCita){
        
        $consulta = citas::Where ('idCita','=',$idCita)->get();
        

        $consultaCliente=clientes::Where('idc','=', $consulta[0]->idc)->get();
        $nombreCliente = $consultaCliente[0]->razonSocial;
        $cliente=clientes::Where('idc','!=', $consulta[0]->idc)->get();
        $clienteOrden = clientes::orderby('razonSocial','asc')->get();

        $consultaSucursal=sucursales::Where('idSucursal','=', $consulta[0]->idSucursal)->get();
        $nombreSucursal = $consultaSucursal[0]->sucursal;
        $idSucursal = $consultaSucursal[0]->idSucursal;
        //$nombreContacto = $consultaSucursal[0]->contacto;
        $sucursal=sucursales::Where('idSucursal','!=', $consulta[0]->idSucursal)->get();

        $consultaCita=citas::Where('contacto','=', $consulta[0]->contacto)->get();
        //$nombreSucursal = $consultaSucursal[0]->sucursal;
        $nombreContacto = $consultaCita[0]->contacto;
        $cita=citas::Where('contacto','!=', $consulta[0]->contacto)->get();

        $consultaUsuario=usuarios::Where('idu','=', $consulta[0]->responsable)->get();
        $nombreUsuario = $consultaUsuario[0]->nombreUsuario;
        $paterno = $consultaUsuario[0]->aPaterno;
        $materno = $consultaUsuario[0]->aMaterno;
        $usuario=usuarios::Where('idu','=', $consulta[0]->idu)->get();
        $usuarioOrden = usuarios::orderby('nombreUsuario','asc')->get();

        $consultaCheckServicios = \DB::select("SELECT serviciosOfrecer FROM citas WHERE idCita = $idCita");
        $cadenaCheckServicios=$consultaCheckServicios[0]->serviciosOfrecer;
        $arrayCheckServicios=explode(', ', $cadenaCheckServicios);

        $consultaCheckMarcas = \DB::select("SELECT marcas FROM citas WHERE idCita = $idCita");
        $cadenaCheckMarcas=$consultaCheckMarcas[0]->marcas;
        $arrayCheckMarcas=explode(', ', $cadenaCheckMarcas);

        // Check sucursales
        $consultaCheckServiciosSucursales = \DB::select("SELECT s.servicios FROM sucursales AS s WHERE idSucursal=$idSucursal ");
        $cadenaCheckServiciosSucursales= $consultaCheckServiciosSucursales[0]->servicios;
        $arrayCheckServiciosSucursales=explode(', ',$cadenaCheckServiciosSucursales);

        $consultaCheckMarcasSucursales = \DB::select("SELECT s.marcas FROM sucursales AS s WHERE idSucursal=$idSucursal ");
        $cadenaCheckMarcasSucursales= $consultaCheckMarcasSucursales[0]->marcas;
        $arrayCheckMarcasSucursales=explode(', ',$cadenaCheckMarcasSucursales);

        // return $consultaCheckServicios;
            return view('editarCitaAsignada')
            ->with('consulta',$consulta[0])
            ->with('cliente',$cliente)
            ->with('arrayCheckServicios',$arrayCheckServicios)
            ->with('arrayCheckMarcas',$arrayCheckMarcas)
            ->with('clienteOrden',$clienteOrden)
            ->with('sucursal',$sucursal)
            ->with('usuario',$usuario)
            ->with('usuarioOrden',$usuarioOrden)
            ->with('idClienteSel',$consulta[0]->idc)
            ->with('idSucursalSel',$consulta[0]->idSucursal)
            ->with('idUsuarioSel',$consulta[0]->responsable)
            ->with('nombreCliente',$nombreCliente)
            ->with('nombreUsuario',$nombreUsuario)
            ->with('paterno',$paterno)
            ->with('materno',$materno)
            ->with('almacenSel',$consulta[0]->contactoAlmacen)
            ->with('ventasSel',$consulta[0]->contactoVentas)
            ->with('nombreContacto',$nombreContacto)
            ->with('consultaSucursal',$consultaSucursal[0])
            ->with('arrayCheckServiciosSucursales',$arrayCheckServiciosSucursales)
            ->with('arrayCheckMarcasSucursales',$arrayCheckMarcasSucursales)
            ->with('nombreSucursal',$nombreSucursal);
    }

    function comboSucEditar(Request $request)
    {
        $idc = $request->get('idc');
        $sucursales = sucursales::where('idc','=',$idc,'AND','activo','=','Si')->get();

        return view ('combop')
        ->with('sucursales',$sucursales);
    }

    public function comboConEditar(Request $request){
        $idSucursal =$request->get('idSucursal');
        $consulta = sucursales::where('idSucursal','=',$idSucursal,'AND','activo','=','Si')->get();
        return view ('comboContacto')
        ->with('consulta',$consulta);
    }

    public function editarCitas(Request $request){
        $idCita=$request->idCita;
        $fechaAlta=$request->fechaAlta;
        $empresaSeguimiento=$request->empresaSeguimiento;
        $idu=$request->idu;
        $registradoPor=$request->registradoPor;
        $idc=$request->idc;
        $idSucursal=$request->idSucursal;
        // return $idSucursal;
        $idContacto=$request->idContacto;
        $contacto=$request->contacto;
        $fechaCita=$request->fechaCita;
        $hora=$request->hora;
        $lugar=$request->lugar;
        $responsable=$request->responsable;
        $observacionCita=$request->observacionCita;
        $fechaReal=$request->fechaReal;
        $resultados=$request->resultados;
        // $serviciosOfrecer=$request->serviciosOfrecer;
        // $marcas=$request->marcas;
        $etatus=$request->estatus;
        $activo=$request->activo;

        $this->validate ($request,[
            'fechaAlta'=>['required'],
            'empresaSeguimiento'=>['required'],
            'idc'=>['required'],
            'idu'=>['required'],
            'responsable'=>['required'],
            
            'idSucursal'=>['required'],
            'fechaCita'=>['required'],
            
        ]);

        // consultas para correo
        $consultaCliente=clientes::Where('idc','=', $idc)->get();
        $nombreCliente = $consultaCliente[0]->razonSocial;
        $cliente=clientes::Where('idc','!=', $idc)->get();

        $consultaSucursal=sucursales::Where('idSucursal','=', $idSucursal)->get();
        $nombreSucursal = $consultaSucursal[0]->sucursal;
        
        
        $consultaUsuario=usuarios::Where('idu','=', $responsable)->get();
        $correoUsuario = $consultaUsuario[0]->correo;
        $nombre = $consultaUsuario[0]->nombreUsuario;
        $paterno = $consultaUsuario[0]->aPaterno;
        $materno = $consultaUsuario[0]->aMaterno;
        $consultaUsuario=usuarios::Where('idu','!=', $responsable)->get();

        // Datos de correo
        $subject = "Cita asignada modificada";
        $datos = array(
            'fechaAlta'=>$fechaAlta,
            'empresaSeguimiento'=>$empresaSeguimiento,
            'registradoPor'=>$registradoPor,
            'nombreCliente'=>$nombreCliente,
            'nombreSucursal'=>$nombreSucursal,
            'contacto'=>$contacto,
            'fechaCita'=>$fechaCita,
            'hora'=>$hora,
            'lugar'=>$lugar,
            'nombre'=>$nombre,
            'paterno'=>$paterno,
            'materno'=>$materno,
            'observacionCita'=>$observacionCita);
        Mail::send('correoCitas', $datos, function($msj) use($subject,$correoUsuario){
            $msj->from("citas.germancontrolmotion@gmail.com","German Control Motion");
            $msj->subject($subject);
            $msj->to($correoUsuario);
        });

        
        $cita=citas::find($idCita);
        $cita->idCita=$request->idCita;
        $cita->empresaSeguimiento=$request->empresaSeguimiento;
        $cita->fechaAlta=$request->fechaAlta;
        $cita->idu=$request->idu;
        $cita->registradoPor=$request->registradoPor;
        $cita->idc=$request->idc;
        $cita->idSucursal=$request->idSucursal;
        $cita->contacto=$request->contacto;
        $cita->fechaCita=$request->fechaCita;
        $cita->hora=$request->hora;
        $cita->lugar=$request->lugar;
        $cita->responsable=$request->responsable;
        $cita->observacionCita=$request->observacionCita;
        $cita->fechaReal=$request->fechaReal;
        $cita->resultados=$request->resultados;
        // $cita->serviciosOfrecer=$request->serviciosOfrecer;
        // $cita->marcas=$request->marcas;
        $cita->estatus=$request->estatus;
        $cita->activo="Si";
        $cita->save();
        
        $proceso='Modificación de citas creadas';
        $mensaje="Registro guardado correctamente";
        return view ('mensajeCitas')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
        
    }

    public function editarCitasAsignada(Request $request){
        
        $idCita=$request->idCita;
        $fechaAlta=$request->fechaAlta;
        $empresaSeguimiento=$request->empresaSeguimiento;
        $idu=$request->idu;
        $registradoPor=$request->registradoPor;
        $idc=$request->idc;
        $idSucursal=$request->idSucursal;
        $idContacto=$request->idContacto;
        $fechaCita=$request->fechaCita;
        $hora=$request->hora;
        $lugar=$request->lugar;
        $responsable=$request->responsable;
        $observacionCita=$request->observacionCita;
        $fechaReal=$request->fechaReal;
        $resultados=$request->resultados;
        // $serviciosOfrecer=$request->serviciosOfrecer;
        // $marcas=$request->marcas;
        $etatus=$request->estatus;
        $activo=$request->activo;

        // $arrayserviciosOfrecer = $request->serviciosOfrecer;
        // $cadenaServicios = implode(', ',$arrayserviciosOfrecer);

        // $arrayMarcas = $request-> marcas;
        // $cadenaMarcas = implode(', ',$arrayMarcas);

        
        $this->validate ($request,[
            'fechaAlta'=>['required'],
            'empresaSeguimiento'=>['required'],
            'idc'=>['required'],
            'idu'=>['required'],
            'responsable'=>['required'],
            
            'idSucursal'=>['required'],
            'fechaCita'=>['required'],
            
        ]);
        
        $cita=citas::find($idCita);
        $cita->idCita=$request->idCita;
        $cita->empresaSeguimiento=$request->empresaSeguimiento;
        $cita->fechaAlta=$request->fechaAlta;
        $cita->idu=$request->idu;
        $cita->registradoPor=$request->registradoPor;
        $cita->idc=$request->idc;
        $cita->idSucursal=$request->idSucursal;
        $cita->contacto=$request->contacto;
        $cita->fechaCita=$request->fechaCita;
        $cita->hora=$request->hora;
        $cita->lugar=$request->lugar;
        $cita->responsable=$request->responsable;
        $cita->observacionCita=$request->observacionCita;
        $cita->fechaReal=$request->fechaReal;
        $cita->resultados=$request->resultados;
        $cita->estatus=$request->estatus;
        $cita->activo="Si";
        $cita->save();
        // $cita->serviciosOfrecer=$request->serviciosOfrecer;
        // $cita->marcas=$request->marcas;
        // $cita->serviciosOfrecer=$cadenaServicios;
        // $cita->marcas=$cadenaMarcas;
        
        
        $proceso='Modificación de citas asignadas';
        $mensaje="Registro guardado correctamente";
        return view ('mensajeCitas')
        ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);
        
    }


    public function eliminarCitas ($idCita){/*Rerecibe este parametro y lo guarda en esa variable*/
        $prospectos = \DB::UPDATE("update citas
        set activo ='No' where idCita=$idCita");

    $proceso ="Eliminación de citas";
    $mensaje="La cita ha sido desactivada correctamente";
    return view('mensajeCitas')
        ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);
}
    public function restaurarCitas ($idCita){/*Rerecibe este parametro y lo guarda en esa variable*/
        $prospectos = \DB::UPDATE("update citas
        set activo ='Si' where idCita=$idCita");

        $proceso ="Restauración de citas";
        $mensaje="La cita ha sido ativada correctamente";
        return view('mensajeCitas')
            ->with('proceso',$proceso)
            ->with('mensaje',$mensaje);
    }

    public function enviarCorreo(){
        return view('correoCitas');
    }

    public function editarModal(equest $request){
        $idSucursal=$request->get('idSucursal');
 
  $consultaClientes = sucursales::Where ('idSucursal','=',$idSucursal)->get();
      $clienteSel= clientes::where('idc',"=",$consultaClientes[0]->idc)->get();
      $nomcli =$clienteSel[0]->razonSocial;
      $cliente = clientes::where ('idc','!=',$consultaClientes[0]->idc)->get();

      $consulta = sucursales::Where ('idSucursal','=',$idSucursal)->get();
        
        $consultaCheckServicios = \DB::select("SELECT s.servicios FROM sucursales AS s WHERE idSucursal=$idSucursal ");
        $cadenaCheckServicios= $consultaCheckServicios[0]->servicios;
        $arrayCheckServicios=explode(', ',$cadenaCheckServicios);

        $consultaCheckMarcas = \DB::select("SELECT s.marcas FROM sucursales AS s WHERE idSucursal=$idSucursal ");
        $cadenaCheckMarcas= $consultaCheckMarcas[0]->marcas;
        $arrayCheckMarcas=explode(', ',$cadenaCheckMarcas);

        return view ('editarModal')
        ->with('idSucursal',$idSucursal)
        ->with('cliente',$cliente)
        ->with('idclientesel',$consultaClientes[0]->idc)
        ->with('nomcli',$nomcli)
        ->with('arrayCheckServicios',$arrayCheckServicios)
        ->with('arrayCheckMarcas',$arrayCheckMarcas)
        ->with('consulta',$consulta);
  
    }

}