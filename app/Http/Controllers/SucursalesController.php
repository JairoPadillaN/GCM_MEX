<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\sucursales;
use App\clientes;
use App\usuarios;
use App\contactosucursales;
use App\login;
use Session;
use App\anexGrid;
use DB;
use DateTime;


class SucursalesController extends Controller{

    public function altaSucursales(){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

        if($sname == '' or $sidu =='' or $stipo=='')
        {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else
        {   
            $cuantosHay=0;
            $cliente = clientes::orderby('razonSocial','asc')->get();
            $consulta= sucursales::orderby('idSucursal','desc')
               ->take(1)
               ->get();
            return view ('altaSucursales')
            ->with('cuantosHay',$cuantosHay)
            ->with('cliente',$cliente);
        }
    }

    public function GuardarSucursales(Request $request){

        $sucursal = $request-> sucursal;
        $idc = $request-> idc;
        $calle = $request-> calle;
        $num = $request-> num;
        $colonia = $request-> colonia;
        $gcmid = $request -> gcmid;
        $tipoSucursal=$request-> tipoSucursal;
        
        $arrayServicios = $request-> servicios;
        $cadenaServicios = implode(', ',$arrayServicios);
        
        $arrayMarcas = $request-> marcas;
        $cadenaMarcas = implode(', ',$arrayMarcas);
        

        $existencia = \DB::select("SELECT gcmid FROM sucursales WHERE gcmid=?",[$gcmid]);
        $cuantosHay = count($existencia); 
        
        if($cuantosHay < 1){
            $contactoVentas=$request-> contactoVentas;
            $correoVentas=$request-> correoVentas;
            $telVentas=$request-> telVentas;
            $contactoGerente=$request-> contactoGerente;
            $correoGerente=$request-> correoGerente;
            $telGerente=$request-> telGerente;
        
            $contactoCompras=$request-> contactoCompras;
            $correoCompras=$request-> correoCompras;
            $telCompras=$request-> telCompras;
        
            $nombreCuentasPP=$request-> nombreCuentasPP;
            $correoCuentasPP=$request-> correoCuentasPP;
            $telefonoCuentasPP=$request-> telefonoCuentasPP;
            $extencionCuentasPP=$request-> extencionCuentasPP;
        
            $nombreCuentasPC=$request-> nombreCuentasPC;
            $correoCuentasPC=$request-> correoCuentasPC;
            $telefonoCuentasPC=$request-> telefonoCuentasPC;
            $extencionCuentasPC=$request-> extencionCuentasPC;
        
            $contactoPlanta=$request-> contactoPlanta;
            $correoPlanta=$request-> correoPlanta;
            $telPlanta=$request-> telPlanta;
            $extenPlanta=$request-> extenPlanta;
        
            $contactoAlmacen=$request-> contactoAlmacen;
            $correoAlmacen=$request-> correoAlmacen;
            $telAlmacen=$request-> telAlmacen;
            $extenAlmacen=$request-> extenAlmacen;
        
            $this->validate ($request,[
            'sucursal'=>['required'],
            'idc'=>['required'],
            'calle'=>['required'],
            'num'=>['required'],
            'colonia'=>['required'],
            'gcmid'=>['required'],
            'tipoSucursal'=>['required']
            ]);
        
            $suc= new sucursales;
            $suc-> idSucursal=$request->idSucursal;
            $suc-> sucursal=$request->sucursal;
            $suc-> idc = $request-> idc;
            $suc-> calle=$request->calle;
            $suc-> num=$request->num;
            $suc-> colonia=$request->colonia;
            $suc-> tipoSucursal=$request->tipoSucursal;
            $suc-> gcmid=$request->gcmid;
            $suc-> giro=$request->giro;
            $suc-> zonaGeografica=$request->zonaGeografica;
            $suc-> servicios=$cadenaServicios;
            $suc-> marcas=$cadenaMarcas;
        
            $suc-> contactoVentas=$request->contactoVentas;
            $suc-> correoVentas=$request->correoVentas;
            $suc-> telVentas=$request->telVentas;
            $suc-> extenVentas=$request->extenVentas;
            $suc-> contactoGerente=$request->contactoGerente;
            $suc-> correoGerente=$request->correoGerente;
            $suc-> telGerente=$request->telGerente;
            $suc-> extenGerente=$request->extenGerente;
            $suc-> contactoCompras=$request->contactoCompras;
            $suc-> correoCompras=$request->correoCompras;
            $suc-> telCompras=$request->telCompras;
            $suc-> extenCompras=$request->extenCompras;
        
            $suc-> contactoPlanta=$request->contactoPlanta;
            $suc-> correoPlanta=$request->correoPlanta;
            $suc-> telPlanta=$request->telPlanta;
            $suc-> extenPlanta=$request->extenPlanta;
        
            $suc-> contactoAlmacen=$request->contactoAlmacen;
            $suc-> correoAlmacen=$request->correoAlmacen;
            $suc-> telAlmacen=$request->telAlmacen;
            $suc-> extenAlmacen=$request->extenAlmacen;
        
            $suc-> nombreCuentasPP=$request->nombreCuentasPP;
            $suc-> telefonoCuentasPP=$request->telefonoCuentasPP;
            $suc-> correoCuentasPP=$request->correoCuentasPP;
            $suc-> extencionCuentasPP=$request->extencionCuentasPP;
        
            $suc-> nombreCuentasPC=$request->nombreCuentasPC;
            $suc-> telefonoCuentaspC=$request->telefonoCuentasPC;
            $suc-> correoCuentasPC=$request->correoCuentasPC;
            $suc-> extencionCuentasPC=$request->extencionCuentasPC;
        
            $suc->save();
        
            $proceso='Alta de sucursal';
            $mensaje="Registro guardado correctamente";
            return view ('mensajeSucursales')
            ->with('cuantosHay',$cuantosHay)
            ->with('proceso',$proceso)
            ->with('mensaje',$mensaje);
        
        }else{
            $cliente = clientes::orderby('razonSocial','asc')->get();
            $consulta= sucursales::orderby('idSucursal','desc')
            ->take(1)
            ->get();
            return view ('altaSucursales')
            ->with('cuantosHay',$cuantosHay)
            ->with('gcmid',$gcmid)
            ->with('cliente',$cliente);
        }
    }

    public function modificarSucursales($idSucursal){
        $stipo = Session::get('sesiontipo');
        $cuantosHay=0;
        //$consulta = \DB::select("SELECT * FROM sucursales where idSucursal=$idSucursal");
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

        $contactos = contactosucursales::Where ('idSucursal','=',$idSucursal)->get();
        $cuantos = count($contactos);
        $seguimiento = \DB::select("SELECT sa.idSegActividad,sa.folio,  DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacionFormato, 
                sa.asunto,u.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, DATE_FORMAT(fechaInicio,'%d %b %Y') AS fechaInicioFormato,
                DATE_FORMAT(fechaTermino,'%d %b %Y') AS fechaTerminoFormato,nombreActividad,
                sa.importanciaSeguimiento, sa.activo, ar.nombreArea,CONCAT(c.razonSocial,' / ',s.sucursal) AS clienteSucursal
                FROM seguimientoactividades AS sa
                INNER JOIN usuarios AS u ON sa.idu = u.idu
                INNER JOIN areas AS ar ON sa.idArea = ar.idArea
                INNER JOIN sucursales AS s ON s.idSucursal= sa.idSucursal
                INNER JOIN clientes AS c ON c.idc= sa.idc
                INNER JOIN actividades AS act ON act.idActividad= sa.idActividad
                WHERE s.idSucursal=?
                ORDER BY idSegActividad DESC",[$idSucursal]);
        $cuantosSegui=count($seguimiento);

            $usuarioSel= usuarios::where('idu',"=",$consulta[0]->idu)->get();
            $cuantosUsuarios = count($usuarioSel);

            if($cuantosUsuarios != 0){
              $nomus = $usuarioSel[0]->nombreUsuario;
              $aPat = $usuarioSel[0]->aPaterno;
              $aMat = $usuarioSel[0]->aMaterno;
              $idUsu= $usuarioSel[0]->idu;
              $consultaUsu = \DB::select("SELECT idu, CONCAT(nombreUsuario,' ', aPaterno,' ', aMaterno) AS usuario, tipo
                                          FROM usuarios
                                          WHERE tipo = 'Vendedor'
                                          ORDER BY nombreUsuario ASC");
            }else{
              $nomus = 0;
              $aPat = 0;
              $aMat = 0;
              $idUsu= 0;
              $consultaUsu = \DB::select("SELECT idu, CONCAT(nombreUsuario,' ', aPaterno,' ', aMaterno) AS usuario, tipo
                                          FROM usuarios
                                          WHERE tipo = 'Vendedor'
                                          ORDER BY nombreUsuario ASC");
            }
            $usuarios = usuarios::where ('idu','!=',$consulta[0]->idu)->get();

            

        
        
        return view('editarSucursales')

        ->with('usuarios',$usuarios)
        ->with('idusel',$consulta[0]->idu)
        ->with('cuantosUsuarios',$cuantosUsuarios)
        ->with('consultaUsu',$consultaUsu)
        ->with('idUsu',$idUsu)
        ->with('nomus',$nomus)
        ->with('aPat',$aPat)
        ->with('aMat',$aMat)

        ->with('stipo',$stipo)
        ->with('cuantosSegui',$cuantosSegui)
        ->with('seguimiento',$seguimiento)
        ->with('cuantosHay',$cuantosHay)
        ->with('cliente',$cliente)
        ->with('idclientesel',$consultaClientes[0]->idc)
        ->with('nomcli',$nomcli)
        ->with('clienteSel',$clienteSel[0])
        ->with('arrayCheckServicios',$arrayCheckServicios)
        ->with('arrayCheckMarcas',$arrayCheckMarcas)
        ->with('consulta',$consulta[0])
        ->with('contactos',$contactos)
        ->with('cuantos',$cuantos);
    }

    public function editarSucursales(Request $request){
        $idSucursal=$request-> idSucursal;
        $sucursal=$request-> sucursal;
        $idc = $request-> idc;
        $idu = $request-> idu;
        $calle = $request-> calle;
        $num = $request-> num;
        $colonia = $request-> colonia;
        $gcmid = $request-> gcmid;
        $tipoSucursal=$request-> tipoSucursal;

        $contactoVentas=$request->contactoVentas;
        $correoVentas=$request->correoVentas;
        $telVentas=$request->telVentas;
        $extenVentas=$request->extenVentas;

        $contactoGerente=$request->contactoGerente;
        $correoGerente=$request->correoGerente;
        $telGerente=$request->telGerente;
        $extenGerente=$request->extenGerente;

        $contactoCompras=$request->contactoCompras;
        $correoCompras=$request->correoCompras;
        $telCompras=$request->telCompras;
        $extenCompras=$request->extenCompras;

        $nombreCuentasPP=$request->nombreCuentasPP;
        $telefonoCuentasPP=$request->telefonoCuentasPP;
        $correoCuentasPP=$request->correoCuentasPP;
        $extencionCuentasPP=$request->extencionCuentasPP;

        $nombreCuentasPC=$request->nombreCuentasPC;
        $telefonoCuentasPC=$request->telefonoCuentasPC;
        $correoCuentasPC=$request->correoCuentasPC;
        $extencionCuentasPC=$request->extencionCuentasPC;

        $arrayServicios = $request-> servicios;
        $cadenaServicios = implode(', ',$arrayServicios);
        
        $arrayMarcas = $request-> marcas;
        $cadenaMarcas = implode(', ',$arrayMarcas);

        $this->validate ($request,[
        'calle'=>['required'],
        'idc'=>['required'],
        'num'=>['required'],
        'sucursal'=>['required'],
        'colonia'=>['required'],
        'gcmid'=>['required']
        ]);

        $suc = sucursales::find($idSucursal);
        $suc->idSucursal = $request-> idSucursal;
        $suc->sucursal=$request->sucursal;
        $suc->idc = $request-> idc;
        $suc->idu = $request-> idu;
        $suc->calle=$request->calle;
        $suc->num=$request->num;
        $suc->colonia=$request->colonia;
        $suc->gcmid=$request->gcmid;
        $suc-> zonaGeografica=$request->zonaGeografica;
        $suc->tipoSucursal=$request->tipoSucursal;
        
        $suc-> giro=$request->giro;
        $suc-> servicios=$cadenaServicios;
        $suc-> marcas=$cadenaMarcas;

        $suc->contactoVentas=$request->contactoVentas;
        $suc->correoVentas=$request->correoVentas;
        $suc->telVentas=$request->telVentas;
        $suc->extenVentas=$request->extenVentas;

        $suc->contactoGerente=$request->contactoGerente;
        $suc->correoGerente=$request->correoGerente;
        $suc->telGerente=$request->telGerente;
        $suc->extenGerente=$request->extenGerente;

        $suc->contactoCompras=$request->contactoCompras;
        $suc->correoCompras=$request->correoCompras;
        $suc->telCompras=$request->telCompras;
        $suc->extenCompras=$request->extenCompras;

        $suc->contactoPlanta=$request->contactoPlanta;
        $suc->correoPlanta=$request->correoPlanta;
        $suc->telPlanta=$request->telPlanta;
        $suc->extenPlanta=$request->extenPlanta;

        $suc->contactoAlmacen=$request->contactoAlmacen;
        $suc->correoAlmacen=$request->correoAlmacen;
        $suc->telAlmacen=$request->telAlmacen;
        $suc->extenAlmacen=$request->extenAlmacen;

        $suc->nombreCuentasPP=$request->nombreCuentasPP;
        $suc->telefonoCuentasPP=$request->telefonoCuentasPP;
        $suc->correoCuentasPP=$request->correoCuentasPP;
        $suc->extencionCuentasPP=$request->extencionCuentasPP;

        $suc->nombreCuentasPC=$request->nombreCuentasPC;
        $suc->telefonoCuentasPC=$request->telefonoCuentasPC;
        $suc->correoCuentasPC=$request->correoCuentasPC;
        $suc->extencionCuentasPC=$request->extencionCuentasPC;

        $suc->save();

        $proceso='Modificación de sucursales';
        $mensaje="Registro modificado correctamente";
        return view ('mensajeSucursales')
        ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);

    }


    public function reporteSucursales(Request $request){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

        if($sname == '' or $sidu =='' or $stipo=='')
        {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else
        {

        $consulta = \DB::select("SELECT s.idSucursal,s.sucursal,s.activo, c.razonSocial AS cli FROM sucursales AS s
        INNER JOIN clientes AS c ON c.idc= s.idc");

        $cliente = clientes::orderby('razonSocial','asc')->get();

        return view ('reporteSucursales')
        ->with('cliente',$cliente)
        ->with('consulta',$consulta);
        }

    }

    public function reporteSucursalesAbajo(Request $request){        
        $idc = $request->idc;
        $sucursal = $request->sucursal;
        $buscaSuc="%".$sucursal."%";
        if($idc==""){
            $reporte=\DB::select("SELECT s.idSucursal,s.sucursal,s.activo, c.razonSocial AS cli, c.idc 
            FROM sucursales AS s
            INNER JOIN clientes AS c ON c.idc= s.idc WHERE s.sucursal LIKE ?",[$buscaSuc]);
        }
        else{
            $reporte=\DB::select("SELECT s.idSucursal,s.sucursal,s.activo, c.razonSocial AS cli, c.idc 
            FROM sucursales AS s
            INNER JOIN clientes AS c ON c.idc= s.idc WHERE c.idc =? AND s.sucursal LIKE ?",[$idc,$buscaSuc]);
        }

        $cuantos = count($reporte);

        return view ('reporteSucursalesAbajo')
        ->with('cuantos',$cuantos)
        ->with('reporte',$reporte);
    }


    public function eliminarSucursales ($idSucursal){/*Rerecibe este parametro y lo guarda en esa variable*/
        $suc = \DB::UPDATE("update sucursales set activo ='No' where idSucursal=$idSucursal");

      $proceso ="Eliminacion de Sucursales";
      $mensaje="El Sucursales ha sido desactivado correctamente";
      return view('mensajeSucursales')
         ->with('proceso',$proceso)
         ->with('mensaje',$mensaje);
    }

    public function restaurarSucursales ($idSucursal){ //restarura el valos de NO a SI en el campo activo
      $suc = \DB::UPDATE("update sucursales
      set activo ='Si' where idSucursal=$idSucursal");

      $proceso ="Restauracion de Sucursales";
      $mensaje="El Sucursales ha sido activado correctamente";
      return view('mensajeSucursales')
           ->with('proceso',$proceso)
           ->with('mensaje',$mensaje);
    }

    ////////////////////CONTACTOS///////////////////////////

    public function contactoSuc (Request $request){ 
        $idc = $request->idc;
        $idSucursal = $request->idSucursal;
        $puesto = $request->puesto;
        $nombreCont = $request->nombreCont;
        $correoCont = $request->correoCont;
        $telefonoCont = $request->telefonoCont;
        $extension = $request->extension;
        $whatsapp = $request->whatsapp;
        $contactoAdicional = $request->contactoAdicional;

        $contactos=\DB::select("SELECT COUNT(*) AS cuantos
            FROM contactosucursales
            WHERE idSucursal= $idSucursal");
        $cuantos = $contactos[0]->cuantos; 
        
       
    
      $cont = new contactosucursales;
      $cont-> idSucursal = $request-> idSucursal;
      $cont-> idc = $request-> idc;
      $cont-> puesto = $request-> puesto;
      $cont-> nombreCont = $request-> nombreCont;
      $cont-> correoCont = $request-> correoCont;
      $cont-> telefonoCont = $request-> telefonoCont;
      $cont-> extension = $request-> extension;
      $cont-> whatsapp = $request-> whatsapp;
      $cont-> contactoAdicional = $request-> contactoAdicional;
      $cont->save();
    
      $contactos = contactosucursales::Where ('idSucursal','=',$idSucursal)->get();
        
        return view('detalleContactos')
        ->with('contactos',$contactos)
        ->with('cuantos',$cuantos);
      }


      public function borrarCont(Request $request ){  
    
          $contBorrar = $request->idContactoSuc; 
          $contBuscar = $request->idSucursal;
          
          $cont = contactosucursales::find($contBorrar)->delete();
        
          $contactos = contactosucursales::Where ('idSucursal','=',$contBuscar)->get();
        
          return view('detalleContactos')
          ->with('contactos',$contactos);
      }




    ///////////////   ESTO VA EN EL CONTROLADOR DE SEGUIMIENTO ////////
    //   public function reporteSeguimiento(){
    //       return view ('AsignacionPiezas.reporteSeguimientoPrue');
    //   }

      public function reporteSeguimiento(Request $request){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
    
        if($sname == '' or $sidu =='' or $stipo==''){
          Session::flash('error', 'Es necesario logearse antes de continuar');
          return redirect()->route('login');
        }else{
    
          $fecha= date('Y-m-d');
          $date_obj = new DateTime($fecha); // Crear un objeto de fecha
          $num_day = $date_obj->format('w'); // 0-dom, 1-lun, ... 6-sab
          $date_obj->modify("-$num_day day"); // Posicionar el objeto en domingo
          $semana = array();
          for($i=0; $i<7; $i++) {
            $semana[] = $date_obj->format('Y-m-d');
            $date_obj->modify('+1 day'); // Incrementar el objeto 1 dia
          }
              
          if($stipo == 'Administrador'){
              $consulta = \DB::select("SELECT sa.idSegActividad,sa.folio,  DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacionFormato,
            sa.asunto,u.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, DATE_FORMAT(fechaInicio,'%d %b %Y') AS fechaInicioFormato,
            DATE_FORMAT(fechaTermino,'%d %b %Y') AS fechaTerminoFormato,CONCAT(c.razonSocial,' / ',s.sucursal) AS clienteSucursal,
            sa.importanciaSeguimiento, sa.activo, ar.nombreArea, asigs.idu
            FROM seguimientoactividades AS sa
            LEFT JOIN usuarios AS u ON sa.idu = u.idu
            LEFT JOIN areas AS ar ON sa.idArea = ar.idArea
            LEFT JOIN asignacionseguimientos AS asigs ON asigs.idSegActividad = sa.idSegActividad
            LEFT JOIN sucursales AS s ON s.idSucursal= sa.idSucursal
            LEFT JOIN clientes AS c ON c.idc= sa.idc
            WHERE sa.idSegActividad NOT IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a)
            ORDER BY sa.idSegActividad DESC");        
    
            $cerradas = \DB::select("SELECT sa.idSegActividad FROM seguimientoactividades AS sa                
            WHERE sa.idSegActividad IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100)
            AND sa.idSegActividad NOT IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a)
            ORDER BY idSegActividad DESC");
            $cuantasCerradas=count($cerradas);
    
            $abiertas = \DB::select("SELECT sa.idSegActividad,sa.idu FROM seguimientoactividades AS sa        
            WHERE sa.idSegActividad NOT IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100)
            AND sa.idSegActividad NOT IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a)
            ORDER BY idSegActividad DESC");
            $cuantasAbiertas=count($abiertas);
    
            $expiran = \DB::select("SELECT sa.idSegActividad FROM seguimientoactividades AS sa
            WHERE sa.idSegActividad NOT IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100)
            AND (sa.fechaTermino >= ? AND sa.fechaTermino <= ?)
            AND sa.idSegActividad NOT IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a)
            ORDER BY idSegActividad DESC",[$semana[0],$semana[6]]);
            $cuantasExpiran=count($expiran);
    
    
            $consultaActividadesAsignadas = \DB::select("SELECT sa.idSegActividad,sa.folio, DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacionFormato,CONCAT(c.razonSocial,' / ',s.sucursal) AS clienteSucursal,
            sa.asunto,u.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, DATE_FORMAT(fechaInicio,'%d %b %Y') AS fechaInicioFormato,
            DATE_FORMAT(fechaTermino,'%d %b %Y') AS fechaTerminoFormato, sa.importanciaSeguimiento, sa.activo, ar.nombreArea
            FROM seguimientoactividades AS sa
            LEFT JOIN usuarios AS u ON sa.idu = u.idu
            LEFT JOIN areas AS ar ON sa.idArea = ar.idArea
            LEFT JOIN sucursales AS s ON s.idSucursal= sa.idSucursal
            LEFT JOIN clientes AS c ON c.idc= sa.idc
            WHERE sa.idSegActividad IN (SELECT asignadas.idSegActividad FROM asignacionseguimientos AS asignadas)
            ORDER BY idSegActividad DESC");
    
            $cerradasAsignadas = \DB::select("SELECT sa.idSegActividad FROM seguimientoactividades AS sa                
            WHERE sa.idSegActividad IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100) 
            AND sa.idSegActividad IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a)
            ORDER BY idSegActividad DESC");
            $cuantasCerradasAsignadas=count($cerradasAsignadas);
    
            $abiertasAsignadas = \DB::select("SELECT sa.idSegActividad,sa.idu FROM seguimientoactividades AS sa        
            WHERE sa.idSegActividad NOT IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100)
            AND sa.idSegActividad IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a)
            ORDER BY idSegActividad DESC");
            $cuantasAbiertasAsignadas=count($abiertasAsignadas);
    
            $expiranAsignadas = \DB::select("SELECT sa.idSegActividad FROM seguimientoactividades AS sa
            WHERE sa.idSegActividad NOT IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100)
            AND (sa.fechaTermino >= ? AND sa.fechaTermino <= ?)
            AND sa.idSegActividad IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a)
            ORDER BY idSegActividad DESC",[$semana[0],$semana[6]]);
            $cuantasExpiranAsignadas=count($expiranAsignadas);
    
    
            return view ('AsignacionPiezas.reporteSeguimientoPrue')
            ->with('consulta',$consulta)
            ->with('cuantasCerradas',$cuantasCerradas)
            ->with('cuantasAbiertas',$cuantasAbiertas)
            ->with('cuantasExpiran',$cuantasExpiran)
            ->with('cuantasCerradasAsignadas',$cuantasCerradasAsignadas)
            ->with('cuantasAbiertasAsignadas',$cuantasAbiertasAsignadas)
            ->with('cuantasExpiranAsignadas',$cuantasExpiranAsignadas)
            ->with('consultaActividadesAsignadas',$consultaActividadesAsignadas)
            ->with('stipo',$stipo);
    
          }else{
            $consulta = \DB::select("SELECT sa.idSegActividad,sa.folio,  DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacionFormato,
            sa.asunto,u.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, DATE_FORMAT(fechaInicio,'%d %b %Y') AS fechaInicioFormato,
            DATE_FORMAT(fechaTermino,'%d %b %Y') AS fechaTerminoFormato,CONCAT(c.razonSocial,' / ',s.sucursal) AS clienteSucursal,
            sa.importanciaSeguimiento, sa.activo, ar.nombreArea, asigs.idu
            FROM seguimientoactividades AS sa
            LEFT JOIN usuarios AS u ON sa.idu = u.idu
            LEFT JOIN areas AS ar ON sa.idArea = ar.idArea
            LEFT JOIN asignacionseguimientos AS asigs ON asigs.idSegActividad = sa.idSegActividad
            LEFT JOIN sucursales AS s ON s.idSucursal= sa.idSucursal
            LEFT JOIN clientes AS c ON c.idc= sa.idc
            WHERE sa.idu = $sidu AND sa.idSegActividad NOT IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a WHERE a.idu = $sidu)
            ORDER BY sa.idSegActividad DESC");        
    
            $cerradas = \DB::select("SELECT sa.idSegActividad FROM seguimientoactividades AS sa                
            WHERE sa.idSegActividad IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100 AND idu=?)
            AND sa.idSegActividad NOT IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a WHERE a.idu = $sidu)
            ORDER BY idSegActividad DESC",[$sidu]);
            $cuantasCerradas=count($cerradas);
    
            $abiertas = \DB::select("SELECT sa.idSegActividad,sa.idu FROM seguimientoactividades AS sa        
            WHERE sa.idSegActividad NOT IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100 AND idu =?)
            AND sa.idu =? AND sa.idSegActividad NOT IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a WHERE a.idu = $sidu)
            ORDER BY idSegActividad DESC",[$sidu,$sidu]);
            $cuantasAbiertas=count($abiertas);
    
            $expiran = \DB::select("SELECT sa.idSegActividad FROM seguimientoactividades AS sa
            WHERE sa.idu = $sidu
            AND sa.idSegActividad NOT IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100 AND idu =?)
            AND (sa.fechaTermino >= ? AND sa.fechaTermino <= ?)
            AND sa.idSegActividad NOT IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a WHERE a.idu = $sidu)
            ORDER BY idSegActividad DESC",[$sidu,$semana[0],$semana[6]]);
            $cuantasExpiran=count($expiran);
    
    
            $consultaActividadesAsignadas = \DB::select("SELECT sa.idSegActividad,sa.folio, DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacionFormato,CONCAT(c.razonSocial,' / ',s.sucursal) AS clienteSucursal,
            sa.asunto,u.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, DATE_FORMAT(fechaInicio,'%d %b %Y') AS fechaInicioFormato,
            DATE_FORMAT(fechaTermino,'%d %b %Y') AS fechaTerminoFormato, sa.importanciaSeguimiento, sa.activo, ar.nombreArea
            FROM seguimientoactividades AS sa
            LEFT JOIN usuarios AS u ON sa.idu = u.idu
            LEFT JOIN areas AS ar ON sa.idArea = ar.idArea
            LEFT JOIN sucursales AS s ON s.idSucursal= sa.idSucursal
            LEFT JOIN clientes AS c ON c.idc= sa.idc
            WHERE sa.idSegActividad IN (SELECT asignadas.idSegActividad FROM asignacionseguimientos AS asignadas WHERE idu=?)
            ORDER BY idSegActividad DESC",[$sidu]);
    
            $cerradasAsignadas = \DB::select("SELECT sa.idSegActividad FROM seguimientoactividades AS sa                
            WHERE sa.idSegActividad IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100 AND idu=?) 
            AND sa.idSegActividad IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a WHERE a.idu = $sidu)
            ORDER BY idSegActividad DESC",[$sidu]);
            $cuantasCerradasAsignadas=count($cerradasAsignadas);
    
            $abiertasAsignadas = \DB::select("SELECT sa.idSegActividad,sa.idu FROM seguimientoactividades AS sa        
            WHERE sa.idSegActividad NOT IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100 AND idu =?)
            AND sa.idSegActividad IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a WHERE a.idu = $sidu)
            ORDER BY idSegActividad DESC",[$sidu,$sidu]);
            $cuantasAbiertasAsignadas=count($abiertasAsignadas);
    
            $expiranAsignadas = \DB::select("SELECT sa.idSegActividad FROM seguimientoactividades AS sa
            WHERE sa.idu = $sidu
            AND sa.idSegActividad NOT IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100 AND idu =?)
            AND (sa.fechaTermino >= ? AND sa.fechaTermino <= ?)
            AND sa.idSegActividad IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a WHERE a.idu = $sidu)
            ORDER BY idSegActividad DESC",[$sidu,$semana[0],$semana[6]]);
            $cuantasExpiranAsignadas=count($expiranAsignadas);
    
    
            return view ('AsignacionPiezas.reporteSeguimientoPrue')
            ->with('consulta',$consulta)
            ->with('cuantasCerradas',$cuantasCerradas)
            ->with('cuantasAbiertas',$cuantasAbiertas)
            ->with('cuantasExpiran',$cuantasExpiran)
            ->with('cuantasCerradasAsignadas',$cuantasCerradasAsignadas)
            ->with('cuantasAbiertasAsignadas',$cuantasAbiertasAsignadas)
            ->with('cuantasExpiranAsignadas',$cuantasExpiranAsignadas)
            ->with('consultaActividadesAsignadas',$consultaActividadesAsignadas)
            ->with('stipo',$stipo);
          }      
            
        }
            
      }

      
  public function reporteCerradasPrueba(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    $tipoReporte = $request ->tipoReporte;
    $fechaInicio = $request -> fechaInicio;
    $fechaFin = $request -> fechaFin;
    if ($fechaInicio == ''){$fechaInicio="0000-00-00";}
    if ($fechaFin == ''){$fechaFin="9999-99-99";}


    if($sname == '' or $sidu =='' or $stipo==''){
        Session::flash('error', 'Es necesario logearse antes de continuar');
        return redirect()->route('login');
    }else{
        
      if($stipo == 'Administrador'){
        $consulta = \DB::select("SELECT sa.idSegActividad,sa.folio,DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacionFormato,sa.fechaInicio,CONCAT(c.razonSocial,' / ',s.sucursal) AS clienteSucursal,
        sa.asunto,u.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, DATE_FORMAT(fechaInicio,'%d %b %Y') AS fechaInicioFormato,
        DATE_FORMAT(fechaTermino,'%d %b %Y') AS fechaTerminoFormato,sa.importanciaSeguimiento, sa.activo, ar.nombreArea, asigs.idu
        FROM seguimientoactividades AS sa
        LEFT JOIN usuarios AS u ON sa.idu = u.idu
        LEFT JOIN areas AS ar ON sa.idArea = ar.idArea
        LEFT JOIN asignacionseguimientos AS asigs ON asigs.idSegActividad = sa.idSegActividad
        LEFT JOIN sucursales AS s ON s.idSucursal= sa.idSucursal
        LEFT JOIN clientes AS c ON c.idc= sa.idc
        WHERE sa.idSegActividad IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100)
        AND sa.idSegActividad NOT IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a)
        ORDER BY idSegActividad DESC");            
        
        $consultaActividadesAsignadas = \DB::select("SELECT sa.idSegActividad,sa.folio, DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacionFormato,sa.fechaInicio,CONCAT(c.razonSocial,' / ',s.sucursal) AS clienteSucursal,
        sa.asunto,u.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, DATE_FORMAT(fechaInicio,'%d %b %Y') AS fechaInicioFormato,
        DATE_FORMAT(fechaTermino,'%d %b %Y') AS fechaTerminoFormato, sa.importanciaSeguimiento, sa.activo, ar.nombreArea
        FROM seguimientoactividades AS sa
        LEFT JOIN usuarios AS u ON sa.idu = u.idu
        LEFT JOIN areas AS ar ON sa.idArea = ar.idArea
        LEFT JOIN sucursales AS s ON s.idSucursal= sa.idSucursal
        LEFT JOIN clientes AS c ON c.idc= sa.idc
        WHERE sa.idSegActividad IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100)
        AND sa.idSegActividad IN (SELECT asignadas.idSegActividad FROM asignacionseguimientos AS asignadas)
        ORDER BY idSegActividad DESC");

        return view ('AsignacionPiezas.reporteConFiltro')
        ->with('actividad','cerrada')
        ->with('tipoReporte',$tipoReporte)
        ->with('consulta',$consulta)
        ->with('consultaActividadesAsignadas',$consultaActividadesAsignadas)
        ->with('stipo',$stipo);

      }else{
        $consulta = \DB::select("SELECT sa.idSegActividad,sa.folio,DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacionFormato,sa.fechaInicio,CONCAT(c.razonSocial,' / ',s.sucursal) AS clienteSucursal,
        sa.asunto,u.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, DATE_FORMAT(fechaInicio,'%d %b %Y') AS fechaInicioFormato,
        DATE_FORMAT(fechaTermino,'%d %b %Y') AS fechaTerminoFormato,sa.importanciaSeguimiento, sa.activo, ar.nombreArea, asigs.idu
        FROM seguimientoactividades AS sa
        LEFT JOIN usuarios AS u ON sa.idu = u.idu
        LEFT JOIN areas AS ar ON sa.idArea = ar.idArea
        LEFT JOIN asignacionseguimientos AS asigs ON asigs.idSegActividad = sa.idSegActividad
        LEFT JOIN sucursales AS s ON s.idSucursal= sa.idSucursal
        LEFT JOIN clientes AS c ON c.idc= sa.idc
        WHERE sa.idu = $sidu AND sa.idSegActividad IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100)
        AND sa.idSegActividad NOT IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a WHERE a.idu = $sidu)
        ORDER BY idSegActividad DESC");            
        
        $consultaActividadesAsignadas = \DB::select("SELECT sa.idSegActividad,sa.folio, DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacionFormato,sa.fechaInicio,CONCAT(c.razonSocial,' / ',s.sucursal) AS clienteSucursal,
        sa.asunto,u.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, DATE_FORMAT(fechaInicio,'%d %b %Y') AS fechaInicioFormato,
        DATE_FORMAT(fechaTermino,'%d %b %Y') AS fechaTerminoFormato, sa.importanciaSeguimiento, sa.activo, ar.nombreArea
        FROM seguimientoactividades AS sa
        LEFT JOIN usuarios AS u ON sa.idu = u.idu
        LEFT JOIN areas AS ar ON sa.idArea = ar.idArea
        LEFT JOIN sucursales AS s ON s.idSucursal= sa.idSucursal
        LEFT JOIN clientes AS c ON c.idc= sa.idc
        WHERE sa.idSegActividad IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100 AND idu=?) 
        AND sa.idSegActividad IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a WHERE a.idu = $sidu)
        ORDER BY idSegActividad DESC",[$sidu]);

        return view ('AsignacionPiezas.reporteConFiltro')
        ->with('actividad','cerrada')
        ->with('tipoReporte',$tipoReporte)
        ->with('consulta',$consulta)
        ->with('consultaActividadesAsignadas',$consultaActividadesAsignadas)
        ->with('stipo',$stipo);
      }              
    }		
  }

  public function reporteAbiertasPrueba(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    $tipoReporte = $request ->tipoReporte;
    $fechaInicio = $request -> fechaInicio;
    $fechaFin = $request -> fechaFin;
    if ($fechaInicio == ''){$fechaInicio="0000-00-00";}
    if ($fechaFin == ''){$fechaFin="9999-99-99";}


    if($sname == '' or $sidu =='' or $stipo==''){
        Session::flash('error', 'Es necesario logearse antes de continuar');
        return redirect()->route('login');
    }else{
        
      if($stipo == 'Administrador'){
        $consulta = \DB::select("SELECT sa.idSegActividad,sa.folio,DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacionFormato,sa.fechaInicio,CONCAT(c.razonSocial,' / ',s.sucursal) AS clienteSucursal,
        sa.asunto,u.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, DATE_FORMAT(fechaInicio,'%d %b %Y') AS fechaInicioFormato,
        DATE_FORMAT(fechaTermino,'%d %b %Y') AS fechaTerminoFormato,sa.importanciaSeguimiento, sa.activo, ar.nombreArea, asigs.idu
        FROM seguimientoactividades AS sa
        LEFT JOIN usuarios AS u ON sa.idu = u.idu
        LEFT JOIN areas AS ar ON sa.idArea = ar.idArea
        LEFT JOIN asignacionseguimientos AS asigs ON asigs.idSegActividad = sa.idSegActividad
        LEFT JOIN sucursales AS s ON s.idSucursal= sa.idSucursal
        LEFT JOIN clientes AS c ON c.idc= sa.idc
        WHERE sa.idSegActividad NOT IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100)
        AND sa.idSegActividad NOT IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a)
        ORDER BY idSegActividad DESC");

        $consultaActividadesAsignadas = \DB::select("SELECT sa.idSegActividad,sa.folio, DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacionFormato,sa.fechaInicio,CONCAT(c.razonSocial,' / ',s.sucursal) AS clienteSucursal,
        sa.asunto,u.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, DATE_FORMAT(fechaInicio,'%d %b %Y') AS fechaInicioFormato,
        DATE_FORMAT(fechaTermino,'%d %b %Y') AS fechaTerminoFormato, sa.importanciaSeguimiento, sa.activo, ar.nombreArea
        FROM seguimientoactividades AS sa
        LEFT JOIN usuarios AS u ON sa.idu = u.idu
        LEFT JOIN areas AS ar ON sa.idArea = ar.idArea
        LEFT JOIN sucursales AS s ON s.idSucursal= sa.idSucursal
        LEFT JOIN clientes AS c ON c.idc= sa.idc
        WHERE sa.idSegActividad NOT IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100)        
        AND sa.idSegActividad IN (SELECT asignadas.idSegActividad FROM asignacionseguimientos AS asignadas)
        ORDER BY idSegActividad DESC");

        return view ('AsignacionPiezas.reporteConFiltro')
        ->with('actividad','abierta')
        ->with('tipoReporte',$tipoReporte)
        ->with('consulta',$consulta)
        ->with('consultaActividadesAsignadas',$consultaActividadesAsignadas)
        ->with('stipo',$stipo);
      }else{
        $consulta = \DB::select("SELECT sa.idSegActividad,sa.folio,DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacionFormato,sa.fechaInicio,CONCAT(c.razonSocial,' / ',s.sucursal) AS clienteSucursal,
        sa.asunto,u.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, DATE_FORMAT(fechaInicio,'%d %b %Y') AS fechaInicioFormato,
        DATE_FORMAT(fechaTermino,'%d %b %Y') AS fechaTerminoFormato,sa.importanciaSeguimiento, sa.activo, ar.nombreArea, asigs.idu
        FROM seguimientoactividades AS sa
        LEFT JOIN usuarios AS u ON sa.idu = u.idu
        LEFT JOIN areas AS ar ON sa.idArea = ar.idArea
        LEFT JOIN asignacionseguimientos AS asigs ON asigs.idSegActividad = sa.idSegActividad
        LEFT JOIN sucursales AS s ON s.idSucursal= sa.idSucursal
        LEFT JOIN clientes AS c ON c.idc= sa.idc
        WHERE sa.idu = $sidu 
        AND sa.idSegActividad NOT IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100)
        AND sa.idSegActividad NOT IN (SELECT a.idSegActividad FROM asignacionseguimientos AS a WHERE a.idu = $sidu)
        ORDER BY idSegActividad DESC");

        $consultaActividadesAsignadas = \DB::select("SELECT sa.idSegActividad,sa.folio, DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacionFormato,sa.fechaInicio,CONCAT(c.razonSocial,' / ',s.sucursal) AS clienteSucursal,
        sa.asunto,u.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, DATE_FORMAT(fechaInicio,'%d %b %Y') AS fechaInicioFormato,
        DATE_FORMAT(fechaTermino,'%d %b %Y') AS fechaTerminoFormato, sa.importanciaSeguimiento, sa.activo, ar.nombreArea
        FROM seguimientoactividades AS sa
        LEFT JOIN usuarios AS u ON sa.idu = u.idu
        LEFT JOIN areas AS ar ON sa.idArea = ar.idArea
        LEFT JOIN sucursales AS s ON s.idSucursal= sa.idSucursal
        LEFT JOIN clientes AS c ON c.idc= sa.idc
        WHERE sa.idSegActividad NOT IN (SELECT dts.idSegActividad FROM detalleseguimientos AS dts WHERE porcentaje = 100)
        AND sa.idSegActividad IN (SELECT asignadas.idSegActividad FROM asignacionseguimientos AS asignadas WHERE asignadas.idu=$sidu)
        ORDER BY idSegActividad DESC");

    return view ('AsignacionPiezas.reporteConFiltro')
    ->with('actividad','abierta')
        ->with('tipoReporte',$tipoReporte)
        ->with('consulta',$consulta)
        ->with('consultaActividadesAsignadas',$consultaActividadesAsignadas)
        ->with('stipo',$stipo);
      }              
    }		
  }

  public function anexgridCreadas(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');
    $idSegActividad = $request->idSegActividad;

    $anexGrid = new anexGrid();

    $folio='';
    $nombreUsuario='';
    $importanciaSeguimiento='';
    $nombreArea='';
    $cliente='';
    $nombreActividad='';

    foreach($anexGrid->filtros as $f)
    {
        if($f['columna'] == 'folio' && $f['valor'] != ''){
            $folio = $f['valor'];
          }
        if($f['columna'] == 'nombreUsuario' && $f['valor'] != ''){
            $nombreUsuario = $f['valor'];
          }
        if($f['columna'] == 'importanciaSeguimiento' && $f['valor'] != ''){
            $importanciaSeguimiento = $f['valor'];
          }
        if($f['columna'] == 'nombreArea' && $f['valor'] != ''){
            $nombreArea = $f['valor'];
          }
        if($f['columna'] == 'cliente' && $f['valor'] != ''){
            $cliente = $f['valor'];
          }
        if($f['columna'] == 'nombreActividad' && $f['valor'] != ''){
            $nombreActividad = $f['valor'];
          }
    }
    $order;

    $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

    if($stipo=='Administrador'){
        $registros=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
                        t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,TRUNCATE (AVG(t1.porcentajePersonal),0) AS porcentajePersonal,CONCAT(SUM(t1.r),' de ',COUNT(*)) AS atendidopor
                        FROM
                        (SELECT sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,
                        CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,
                          IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                          IF(ultimoporcentaje(sa.idSegActividad,$sidu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,$sidu)) AS porcentajePersonal,
                          seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
                        FROM seguimientoactividades AS sa
                        INNER JOIN clientes AS c ON c.idc = sa.idc
                        INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
                        INNER JOIN areas AS a ON a.idArea = sa.idArea
                        INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
                        INNER JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
                        where t1.folio like '%$folio%' and t1.nombreUsuario like '%$nombreUsuario%'and t1.importanciaSeguimiento like '%$importanciaSeguimiento%'
                        and t1.nombreArea like '%$nombreArea%'and t1.cliente like '%$cliente%' and t1.nombreActividad like '%$nombreActividad%' 
                        GROUP BY t1.idSegActividad 
                        ORDER BY $anexGrid->columna $anexGrid->columna_orden
                        LIMIT $anexGrid->pagina, $anexGrid->limite");
    }else{
        $registros=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
                        t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,TRUNCATE (AVG(t1.porcentajePersonal),0) AS porcentajePersonal,CONCAT(SUM(t1.r),' de ',COUNT(*)) AS atendidopor
                        FROM
                        (SELECT sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,
                        CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,
                          IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                          IF(ultimoporcentaje(sa.idSegActividad,$sidu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,$sidu)) AS porcentajePersonal,
                          seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
                        FROM seguimientoactividades AS sa
                        INNER JOIN clientes AS c ON c.idc = sa.idc
                        INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
                        INNER JOIN areas AS a ON a.idArea = sa.idArea
                        INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
                        INNER JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad
                        where sa.idu=$sidu)AS t1
                        where t1.folio like '%$folio%' and t1.nombreUsuario like '%$nombreUsuario%'and t1.importanciaSeguimiento like '%$importanciaSeguimiento%'
                        and t1.nombreArea like '%$nombreArea%'and t1.cliente like '%$cliente%' and t1.nombreActividad like '%$nombreActividad%' 
                        GROUP BY t1.idSegActividad 
                        ORDER BY $anexGrid->columna $anexGrid->columna_orden
                        LIMIT $anexGrid->pagina, $anexGrid->limite");

    }
        $total =count($registros);

    $data = array(
        'total' => $total,
        'data' => $registros
    );

    return response()->json($data, 200); 
    // return $request; 

  }

}