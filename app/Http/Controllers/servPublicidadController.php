<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\clientes;
use App\sucursales;
use App\usuarios;
use App\serviciosPublicidad;

class servPublicidadController extends Controller
{
    public function altaServPublicidad(){
            
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
        
        if($sname == '' or $sidu =='' or $stipo==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else{
            $cliente = clientes::orderby('razonSocial','asc')->get();
            return view ('altaServPublicidad')
            ->with('cliente',$cliente);
        }
    }

    public function guardarServPublicidad(Request $request){

        $empresaSeguimiento=$request-> empresaSeguimiento;
        $idc=$request-> idc;
        $idSucursal=$request-> idSucursal;        
        $estatus=$request-> estatus;        
        $arrayTipoPublicidad = $request-> tipoPublicidad;
        $cadenaTipoPublicidad = implode(', ',$arrayTipoPublicidad);

        $this->validate ($request,[
            'empresaSeguimiento'=>['required'],
            'idc'=>['required'],
            'idSucursal'=>['required'],
            'estatus'=>['required']
        ]);
        
        $servP = new serviciosPublicidad;
        $servP->fechaAlta=$request->fechaAlta;
        $servP->empresaSeguimiento=$request->empresaSeguimiento;
        $servP->idu=$request->idu;
        $servP->idc=$request->idc;
        $servP->idSucursal=$request->idSucursal;
        $servP->contacto=$request->contacto;
        $servP->tipoPublicidad=$cadenaTipoPublicidad;
        $servP->descripcionActividad=$request->descripcionActividad;
        $servP->contesto=$request->contesto;
        $servP->estatus=$request->estatus;
        $servP->activo="Si";
        $servP->save();
        
        $proceso='Alta de Servicios de ublicidad';
        $mensaje="Registro guardado correctamente";
        return view ('mensajeServPublicidad')
        ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);        
    }

    public function reporteServPublicidad(){

        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        
        if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }else{
            
            if ($stipo == 'Administrador') {

                $consulta = \DB::select("SELECT sp.idServPublicidad, sp.fechaAlta, sp.empresaSeguimiento, sp.idu, 
                    c.razonSocial,s.sucursal,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS us,
                    sp.contacto, sp.tipoPublicidad,descripcionActividad,contesto, sp.estatus, sp.activo
                    FROM serviciosPublicidad AS sp 
                    INNER JOIN sucursales AS s ON s.idSucursal=sp.idSucursal
                    INNER JOIN usuarios AS u ON u.idu=sp.idu
                    INNER JOIN clientes AS c ON c.idc=sp.idc"
                );            

            }else{

                $consulta = \DB::select("SELECT sp.idServPublicidad, sp.fechaAlta, sp.empresaSeguimiento, sp.idu, 
                    c.razonSocial,s.sucursal,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS us,
                    sp.contacto, sp.tipoPublicidad,descripcionActividad,contesto, sp.estatus, sp.activo
                    FROM serviciosPublicidad AS sp 
                    INNER JOIN sucursales AS s ON s.idSucursal=sp.idSucursal
                    INNER JOIN usuarios AS u ON u.idu=sp.idu
                    INNER JOIN clientes AS c ON c.idc=sp.idc
                    WHERE sp.activo='Si' AND sp.idu = ?",[$sidu]
                );                                                
            }
            
            $cuantos= count($consulta);
            return view ('reporteServPublicidad')
            ->with('cuantos',$cuantos)
            ->with('consulta',$consulta);
        }
    }    

    public function reporteServPublicidadAbajo(Request $request){
        $fechaInicio=$request->fechaInicio;
        $fechaFin=$request->fechaFin;
        $publicidad =\DB::select("SELECT p.idServPublicidad,DATE_FORMAT(p.fechaAlta,'%d %b %Y') AS fecha, cli.razonSocial, s.sucursal,u.idu,u.nombreUsuario,p.contacto, p.descripcionActividad,p.contesto,p.estatus,p.activo
                        FROM serviciosPublicidad AS p 
                        INNER JOIN clientes AS cli ON cli.idc = p.idc
                        INNER JOIN sucursales AS s ON s.idSucursal = p.idSucursal
                        INNER JOIN usuarios AS u ON u.idu = p.idu
                        WHERE fechaAlta>=? AND fechaAlta<=?",[$fechaInicio,$fechaFin]);
        
        $cuantos = count ($publicidad);
        
        return view ('reporteServPublicidadAbajo')
        ->with('fechaInicio',$fechaInicio)
        ->with('fechaFin',$fechaFin)
        ->with('publicidad',$publicidad)
        ->with('cuantos',$cuantos);
    
    }

    public function modificarServPublicidad($idServPublicidad){
        
        $consulta = \DB::select("SELECT sp.idServPublicidad, sp.fechaAlta, sp.empresaSeguimiento,sp.idu,sp.idc,sp.idSucursal,
            c.razonSocial,s.sucursal,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS us,
            sp.contacto, sp.tipoPublicidad,descripcionActividad,contesto, sp.estatus, sp.activo
            FROM serviciosPublicidad AS sp 
            INNER JOIN sucursales AS s ON s.idSucursal=sp.idSucursal
            INNER JOIN usuarios AS u ON u.idu=sp.idu
            INNER JOIN clientes AS c ON c.idc=sp.idc
            WHERE idServPublicidad = ?",[$idServPublicidad]
        );
        
        $consultaCliente=clientes::Where('idc','=', $consulta[0]->idc)->get();
        $nombreCliente = $consultaCliente[0]->razonSocial;        
        $clientesVariosYpersonal=clientes::Where('idc','!=', $consulta[0]->idc)->get();
        $losOtrosclientesOrden = clientes::orderby('razonSocial','asc')->get();

        $consultaSucursal = sucursales::where('idSucursal',"=",$consulta[0]->idSucursal)->get();
        $nombreSucursal = $consultaSucursal[0]->sucursal;
        $idSucursal=$consultaSucursal[0]->idSucursal;
        
        $consultaContacto=serviciosPublicidad::Where('contacto','=', $consulta[0]->contacto)->get();
        $nombreContacto = $consultaContacto[0]->contacto;

        $cadenaTipoPublicidad=$consulta[0]->tipoPublicidad;
        $arrayCheckTipoPublicidad=explode(', ',$cadenaTipoPublicidad);

        // Check sucursales
        $consultaCheckServicios = \DB::select("SELECT s.servicios FROM sucursales AS s WHERE idSucursal=$idSucursal ");
        $cadenaCheckServicios= $consultaCheckServicios[0]->servicios;
        $arrayCheckServicios=explode(', ',$cadenaCheckServicios);

        $consultaCheckMarcas = \DB::select("SELECT s.marcas FROM sucursales AS s WHERE idSucursal=$idSucursal ");
        $cadenaCheckMarcas= $consultaCheckMarcas[0]->marcas;
        $arrayCheckMarcas=explode(', ',$cadenaCheckMarcas);

        return view('editarServPublicidad')
        ->with('idclienteConsulta',$consulta[0]->idc)
        ->with('nombreCliente',$nombreCliente)
        ->with('clientesVariosYpersonal',$clientesVariosYpersonal)
        ->with('losOtrosclientesOrden',$losOtrosclientesOrden)
        ->with('idSucursalConsulta',$consulta[0]->idSucursal)
        ->with('nombreSucursal',$nombreSucursal)
        ->with('nombreContacto',$nombreContacto)
        ->with('arrayCheckTipoPublicidad',$arrayCheckTipoPublicidad)
        ->with('consultaSucursal',$consultaSucursal[0])   
        ->with('idSucursal',$idSucursal)   
        ->with('arrayCheckServicios',$arrayCheckServicios)
        ->with('arrayCheckMarcas',$arrayCheckMarcas)     
        ->with('consulta',$consulta[0]);        
    }

    public function editarServPublicidad(Request $request){
        
        $idServPublicidad=$request->idServPublicidad;
        $empresaSeguimiento=$request->empresaSeguimiento;
        $idc=$request->idc;
        $idSucursal=$request->idSucursal;
        $estatus=$request-> estatus;
        $arrayTipoPublicidad = $request-> tipoPublicidad;
        $cadenaTipoPublicidad = implode(', ',$arrayTipoPublicidad);

        $this->validate ($request,[
            'empresaSeguimiento'=>['required'],
            'idc'=>['required'],
            'idSucursal'=>['required'],
            'estatus'=>['required']
        ]);
        
        $servP = serviciosPublicidad::find($idServPublicidad);
        $servP->idServPublicidad=$request->idServPublicidad;
        $servP->fechaAlta=$request->fechaAlta;
        $servP->empresaSeguimiento=$request->empresaSeguimiento;
        $servP->idu=$request->idu;
        $servP->idc=$request->idc;
        $servP->idSucursal=$request->idSucursal;
        $servP->contacto=$request->contacto;
        $servP->tipoPublicidad=$cadenaTipoPublicidad;
        $servP->descripcionActividad=$request->descripcionActividad;
        $servP->contesto=$request->contesto;
        $servP->estatus=$request->estatus;
        $servP->activo="Si";
        $servP->save();
        
        $proceso='Modificación de servicios de publicidad';
        $mensaje="Registro editado correctamente";
        return view ('mensajeServPublicidad')
        ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);        
    }

    function eliminarServPublicidad($idServPublicidad){
        
        $elimiarSerP = \DB::UPDATE("update serviciosPublicidad set activo ='No' where idServPublicidad=$idServPublicidad");

        $proceso ="Eliminación de servicios de publicidad";
        $mensaje="El registro ha sido desactivado correctamente";
        return view('mensajeServPublicidad')
        ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);
        
    }

    function restaurarServPublicidad($idServPublicidad){
        
        $elimiarSerP = \DB::UPDATE("update serviciosPublicidad set activo ='Si' where idServPublicidad=$idServPublicidad");

        $proceso ="Restauración de servicios de publicidad";
        $mensaje="El registro ha sido activado correctamente";
        return view('mensajeServPublicidad')
        ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);
        
    }



}