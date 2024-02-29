<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Session;
use DB;
use App\cotizaciones;
use App\contactosucursales;
use App\equiposEnActividad;
use App\seguimientoactividades;
use App\actividades;
use App\facturas;
use App\recepcionEquipos;
use App\areas;
use App\clientes;
use App\sucursales;
use App\usuarios;
use App\asignacionseguimientos;
use App\detalleseguimientos;
use DateTime;
use App\anexGrid;
use Mail;

class seguimientoActController extends Controller
{
  public function altaSeg(){
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

              $idSegSig = seguimientoactividades::withTrashed()
              ->orderby('idSegActividad','desc')
              ->take(1)
              ->get();

              $cuantos = count($idSegSig);

              if($cuantos==0)
              {
                $idSig = 1;
              }
              else {
                $idSig = $idSegSig[0]->folio+1;
              }
              
              $act = actividades::orderby('nombreActividad','asc')
              ->get();

              $area = areas::orderby('nombreArea','asc')
              ->get();

              $cliente = clientes::orderby('razonSocial','asc')
              ->get();
		
			return view ('altaSeguimiento')
      ->with('sname',$sname)
      ->with('sidu',$sidu)
      ->with('idSig',$idSig)
      ->with('act',$act)
      ->with('area',$area)
      ->with('cliente',$cliente);
}
}

public function guardarSeg(Request $request)
{
        $fechaCreacion=$request->fechaCreacion;
        $idu=$request->idu;
        $idc=$request->idc;
        $idSucursal=$request->idSucursal;
        $folio=$request->folio;
        $asunto=$request->asunto;
        $idActividad=$request->idActividad;
        $fechaInicio=$request->fechaInicio;
        $fechaTermino=$request->fechaTermino;
        $horaInicio=$request->horaInicio;
        $horaTermino=$request->horaTermino;
        $detalleSeguimiento=$request->detalleSeguimiento;
        $estadoSeguimiento=$request->estadoSeguimiento;
        $importanciaSeguimiento=$request->importanciaSeguimiento;
        $nombreUsuario=$request->nombreUsuario;
        $idArea=$request->idArea;
        $link1=$request->link1;
        $link2=$request->link2;
        $link3=$request->link3;

        $this->validate ($request,[
          'idc'=>['required'],
          'idSucursal'=>['required'],
          'idArea'=>['required'],
          'idActividad'=>['required'],
          ]);

  //archivo 1
        $file = $request->file('archivo1');
        //obtenemos el nombre del archivo
         if($file!=""){
        $archivo1 =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($archivo1,  \File::get($file));
           }
            else{
              $archivo1 ="Sin archivo";
            }
//archivo 2
        $file = $request->file('archivo2');
        //obtenemos el nombre del archivo
         if($file!=""){
        $archivo2 =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($archivo2,  \File::get($file));
           }
            else{
              $archivo2 ="Sin archivo";
            }
  
//archivo 3
        $file = $request->file('archivo3');
        //obtenemos el nombre del archivo
         if($file!=""){
        $archivo3 =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($archivo3,  \File::get($file));
           }
            else{
              $archivo3 ="Sin archivo";
            }



         

        // /*Valida la infromacion que esta entrando y que el usuario no debe dejarlo vacio*/
        // $this->validate ($request,[
        // 'nombreActividad'=>['regex:/^[A-Z][a-z, A-Z, ,á,é,í,ó,ú,ñ, 0-9]*$/'],
    
        // ]);

          $seg = new seguimientoactividades;
          $seg->idSegActividad = $request->idSegActividad;
          $seg->fechaCreacion = $request->fechaCreacion;
          $seg->idu = $request->idu;
          $seg->idc = $request->idc;
          $seg->idSucursal = $request->idSucursal;
          $seg->folio = $request->folio;
          $seg->asunto = $request->asunto;
          $seg->idActividad = $request->idActividad;
          $seg->fechaInicio = $request->fechaInicio;
          $seg->fechaTermino = $request->fechaTermino;
          $seg->horaInicio = $request->horaInicio;
          $seg->horaTermino = $request->horaTermino;
          $seg->detalleSeguimiento = $request->detalleSeguimiento;
          $seg->estadoSeguimiento = $request->estadoSeguimiento;
          $seg->importanciaSeguimiento = $request->importanciaSeguimiento;
          $seg->nombreUsuario = $request->nombreUsuario;
          $seg->idArea = $request->idArea;
          $seg->link1 = $request->link1;
          $seg->link2 = $request->link2;
          $seg->link3 = $request->link3;
		      $seg-> activo='Si';

           //Subir Archivos
           $seg-> archivo1 = $archivo1;
           $seg-> archivo2 = $archivo2;
           $seg-> archivo3 = $archivo3;
        
           $seg->save();



          $proceso ="Alta de actividades";
          $mensaje="Registro guardado correctamente";
          return view('mensajeSeguimiento')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
          
        }
	 
	
	 public function modificarSeg($idSegActividad){
           $consulta = seguimientoactividades::Where ('idSegActividad','=',$idSegActividad)->get();

            $usuarios = \DB::select("SELECT * FROM usuarios ORDER BY nombreUsuario ASC");
            $seguimiento = \DB::select("SELECT ag.idAsigSeguimiento, ag.idSegActividad, ag.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo
              FROM asignacionseguimientos AS ag
              INNER JOIN usuarios AS u ON ag.idu = u.idu
              WHERE idSegActividad = $idSegActividad");
            $cuantosSeg = count($seguimiento);
      
            
            $clienteSel= clientes::where('idc',"=",$consulta[0]->idc)->get();
            $nomcli =$clienteSel[0]->razonSocial;
            $cliente = clientes::where ('idc','!=',$consulta[0]->idc)->get();
    
            $sucursalSel= sucursales::where('idSucursal',"=",$consulta[0]->idSucursal)->get();
            $nomSuc =$sucursalSel[0]->sucursal;
            $sucursal = sucursales::where ('idSucursal','!=',$consulta[0]->idSucursal)->get();

            $equipos= recepcionEquipos::where('idSucursal',"=",$consulta[0]->idSucursal)->get();
            $facturas= facturas::where('idSucursal',"=",$consulta[0]->idSucursal)->get();
            $contactoSucursal= contactosucursales::where('idSucursal',"=",$consulta[0]->idSucursal)->get();
            $idSucursalCon=$consulta[0]->idSucursal ;
            $cotizacion = \DB::select("SELECT numeroCotizacion, idCotizacion FROM cotizaciones WHERE clonActual = 'Si' AND idSucursal=$idSucursalCon");

           $consultaUsuario=usuarios::Where('idu','=', $consulta[0]->idu)->get();
           $tipo = $consultaUsuario[0]->tipo;

          $areaSel= areas::where('idArea',"=",$consulta[0]->idArea)->get();
          $nomArea =$areaSel[0]->nombreArea;
          $area = areas::where ('idArea','!=',$consulta[0]->idArea)->get();
           
          $actSel= actividades::where('idActividad',"=",$consulta[0]->idActividad)->get();
          $nomAct =$actSel[0]->nombreActividad;
          $act = actividades::where ('idActividad','!=',$consulta[0]->idActividad)->get();

          $archivo1 = \DB::select("SELECT sa.archivo1 FROM seguimientoactividades AS sa Where idSegActividad = $idSegActividad ");
          $archivo2 = \DB::select("SELECT sa.archivo2 FROM seguimientoactividades AS sa Where idSegActividad = $idSegActividad ");
          $archivo3 = \DB::select("SELECT sa.archivo3 FROM seguimientoactividades AS sa Where idSegActividad = $idSegActividad ");

          $cuantosExis=0;
          $consultaEquipos = \DB::select("SELECT  ea.idSegActividad, ea.idEquipoActividad,e.idEquipos,e.folioRecepcion,e.vistaSuperior,e.vistaFrente,e.textoQr,e.placa_1,e.estatus,
            e.importancia,SUBSTR(datosCertificado,1,100) as datos,
            COUNT(partesreparacion.`idParte`) AS canti,
            CONCAT (e.GCMid, '-', e.complementoGCMid)AS gcmid,
            CONCAT(c.razonSocial,'-',s.sucursal) AS cliSuc,
            DATE_FORMAT(e.fechaRecoleccion,'%d %b %Y') AS fecha,
            DATE_FORMAT(e.fechaRegistro,'%d %b %Y') AS fechar,e.personaEntrega,
            CONCAT (e.serie,'-',te.tipoEquipo,'-',se.subtipoEquipo) AS equipo,
            CONCAT (e.marca,'-',e.modelo) AS marcaModelo, e.activo
            FROM recepcionEquipos AS e
            INNER JOIN clientes AS c ON c.idc= e.idc
            INNER JOIN sucursales AS s ON s.idSucursal= e.idSucursal
            INNER JOIN tipoequipos AS te ON te.idTipoEquipo= e.idTipoEquipo
            INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= e.idSubtipoEquipo
            INNER JOIN equiposEnActividad AS ea ON ea.idEquipos = e.idEquipos
            LEFT JOIN partesreparacion ON partesreparacion.`idEquipos`= e.`idEquipos`
            WHERE ea.idSegActividad=$idSegActividad
            GROUP BY  ea.idSegActividad, ea.idEquipoActividad,e.idEquipos, e.folioRecepcion, e.vistaSuperior,e.vistaFrente,e.placa_1, e.idu,e.textoQr,e.estatus,e.importancia,datos,gcmid,
            cliSuc,e.personaEntrega,equipo,marcaModelo,fecha, e.activo
            ORDER BY e.folioRecepcion DESC");
          $cuantosEquipos = count($consultaEquipos);
          
          $cuantosFac=0;
            $consultaFacturas = \DB::select("SELECT ea.idSegActividad, ea.idEquipoActividad, v.idServicios,v.estatusEntrega,
                v.estatusPago, v.estatusPortal, v.cashFlow,
                v.archivoRemision, v.archivo,v.archivoFactura,v.archivoPago,
                v.xmlFactura, v.numeroFactura,v.idFactura,v.activo,v.fechaFactura,
                DATE_FORMAT(v.fechaFactura,'%d %b %Y') AS fecha, 
                DATE_FORMAT(v.fechaPago,'%d %b %Y') AS fechap, v.montoFactura,
                v.tipoMoneda,v.archivoAdenda,s.sucursal AS suc, c.razonSocial AS cli 
                FROM facturas AS v
                LEFT JOIN sucursales AS s ON s.idSucursal = v.idSucursal
                LEFT JOIN clientes AS c ON c.idc = v.idc
                INNER JOIN equiposEnActividad AS ea ON ea.idFactura = v.idFactura
                WHERE ea.idSegActividad=$idSegActividad order by v.idServicios DESC");
            $cuantosServicios=count($consultaFacturas);
            $cuantosContac=0;
            $consultaContactos=\DB::select("SELECT ea.idSegActividad, ea.idEquipoActividad, cs.idContactoSuc, puesto, nombreCont, telefonoCont, correoCont, extension, whatsapp, contactoAdicional
                  FROM contactosucursales AS cs
                  INNER JOIN equiposEnActividad AS ea ON ea.idContactoSuc = cs.idContactoSuc
                  WHERE ea.idSegActividad=$idSegActividad ORDER BY cs.idContactoSuc DESC");
            $cuantosContactos=count($consultaContactos);

            $cuantosCoti=0;
            $consultaCotizacion = \DB::select("SELECT ea.idSegActividad, ea.idEquipoActividad,co.idCotizacion, co.numeroCotizacion, co.fechaCotizacion,co.clonActual,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
            co.noEquipos,CONCAT('$',' ',co.tipoMoneda,' ',co.montoTotal) AS montoMoneda,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus
            FROM cotizaciones AS co
            INNER JOIN usuarios AS u ON u.idu= co.idu
            INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
            INNER JOIN clientes AS c ON c.idc= co.idc
            INNER JOIN equiposEnActividad AS ea ON ea.idCotizacion = co.idCotizacion
            WHERE ea.idSegActividad=$idSegActividad
            ORDER BY numeroCotizacion");
            $cuantasCotizacion=count($consultaCotizacion);
            $botonDetalle="activo";


          
          return view ('editarSeguimiento')
           ->with ('cuantosCoti', $cuantosCoti)
           ->with ('cuantasCotizacion', $cuantasCotizacion)
           ->with ('consultaCotizacion', $consultaCotizacion)
           ->with ('consultaContactos', $consultaContactos)
           ->with ('cuantosContactos', $cuantosContactos)
           ->with ('cuantosContac', $cuantosContac)
           ->with ('consultaFacturas', $consultaFacturas)
           ->with ('cuantosServicios', $cuantosServicios)
           ->with ('cuantosFac', $cuantosFac)
           ->with ('cuantosExis', $cuantosExis)
           ->with ('cuantosEquipos', $cuantosEquipos)
           ->with ('consultaEquipos', $consultaEquipos)
           ->with ('facturas', $facturas)
           ->with ('equipos', $equipos)
           ->with ('botonDetalle', $botonDetalle)

           ->with ('cotizacion', $cotizacion)
           ->with ('contactoSucursal', $contactoSucursal)
           ->with ('consulta', $consulta[0])
           ->with ('usuarios', $usuarios)
           ->with ('tipo', $tipo)
           
           ->with('cliente',$cliente)
           ->with('idclientesel',$consulta[0]->idc)
           ->with('nomcli',$nomcli)
           ->with('areaSel',$areaSel[0])

           ->with('area',$area)
           ->with('idAreaSel',$consulta[0]->idArea)
           ->with('nomArea',$nomArea)
           ->with('clienteSel',$clienteSel[0])

           ->with('sucursal',$sucursal)
           ->with('idSucursalSel',$consulta[0]->idSucursal)
           ->with('nomSuc',$nomSuc)
           ->with('sucursalSel',$sucursalSel[0])

           ->with('act',$act)
           ->with('idActSel',$consulta[0]->idActividad)
           ->with('nomAct',$nomAct)
           ->with('actSel',$actSel[0])
           ->with('archivo1',$archivo1)
           ->with('archivo2',$archivo2)
           ->with('archivo3',$archivo3)
           ->with('seguimiento',$seguimiento)
           ->with('cuantosSeg',$cuantosSeg);
    }

	 public function editarSeg(Request $request){
           $idSegActividad= $request-> idSegActividad;//Esta valor lo toma del text oculto en el formulario es el id para saber a cual de modificara
           $fechaCreacion=$request->fechaCreacion;
           $idu=$request->idu;
           $idc=$request->idc;
           $idSucursal=$request->idSucursal;
           $folio=$request->folio;
           $asunto=$request->asunto;
           $idActividad=$request->idActividad;
           $fechaInicio=$request->fechaInicio;
           $fechaTermino=$request->fechaTermino;
           $horaInicio=$request->horaInicio;
           $horaTermino=$request->horaTermino;
           $detalleSeguimiento=$request->detalleSeguimiento;
           $estadoSeguimiento=$request->estadoSeguimiento;
           $importanciaSeguimiento=$request->importanciaSeguimiento;
           $nombreUsuario=$request->nombreUsuario;
           $idArea=$request->idArea;
           $link1=$request->link1;
           $link2=$request->link2;
           $link3=$request->link3;

          //  $this->validate ($request,['nombreActividad'=>['regex:/^[A-Z][a-z, A-Z, ,á,é,i,ó,ú,ñ,0-9]*$/',]]);

// archivo1
          $file1 = $request->file('archivo1');
          if($file1!=""){
             $archivo1 =  time()."_".$file1->getClientOriginalName();
             \Storage::disk('local')->put($archivo1,  \File::get($file1));
           
          }

// archivo2
          $file2 = $request->file('archivo2');
          if($file2!=""){
             $archivo2 =  time()."_".$file2->getClientOriginalName();
             \Storage::disk('local')->put($archivo2,  \File::get($file2));
           
          }
// archivo3
          $file3 = $request->file('archivo3');
          if($file3!=""){
             $archivo3 =  time()."_".$file3->getClientOriginalName();
             \Storage::disk('local')->put($archivo3,  \File::get($file3));
           
          }

           $seg = seguimientoactividades::find($idSegActividad);
           $seg->fechaCreacion = $request->fechaCreacion;
           $seg->idu = $request->idu;
           $seg->idc = $request->idc;
           $seg->idSucursal = $request->idSucursal;
           $seg->folio = $request->folio;
           $seg->asunto = $request->asunto;
           $seg->idActividad = $request->idActividad;
           $seg->fechaInicio = $request->fechaInicio;
           $seg->fechaTermino = $request->fechaTermino;
           $seg->horaInicio = $request->horaInicio;
           $seg->horaTermino = $request->horaTermino;
           $seg->detalleSeguimiento = $request->detalleSeguimiento;
           $seg->estadoSeguimiento = $request->estadoSeguimiento;
           $seg->importanciaSeguimiento = $request->importanciaSeguimiento;
           $seg->nombreUsuario = $request->nombreUsuario;
           $seg->idArea = $request->idArea;
           $seg->link1 = $request->link1;
           $seg->link2 = $request->link2;
           $seg->link3 = $request->link3;

          //  archivos
          if($file1!=""){
            $seg-> archivo1 = $archivo1;
          }

          if($file2!=""){
            $seg-> archivo2 = $archivo2;
          }

          if($file3!=""){
            $seg-> archivo3 = $archivo3;
          }
           
           $seg-> save();

           $proceso='Modificación de actividades';
           $mensaje="Actividad modificada correctamente";
           return view ('mensajeSeguimiento')
             ->with('proceso',$proceso)
             ->with('mensaje',$mensaje);
    }

	public function eliminarSeg ($idSegActividad){/*Rerecibe este parametro y lo guarda en esa variable*/
             $seg = \DB::UPDATE("update seguimientoactividades
             set activo ='No' where idSegActividad=$idSegActividad");

           $proceso ="Eliminación de actividades";
           $mensaje="La actividad ha sido desactivada correctamente";
           return view('mensajeSeguimiento')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);
    }
	
	  public function restaurarSeg ($idSegActividad){ //restarura el valos de NO a SI en el campo activo
           $seg= \DB::UPDATE("update seguimientoactividades           
           set activo ='Si' where idSegActividad=$idSegActividad");

           $proceso ="Restauración de actividades";
           $mensaje="La actividad ha sido activada correctamente";
           return view('mensajeSeguimiento')
                ->with('proceso',$proceso)
                ->with('mensaje',$mensaje);
    }
	
    public function agregarUsuariosAct ($idSegActividad){ //restarura el valos de NO a SI en el campo activo
      $consulta = seguimientoactividades::Where ('idSegActividad','=',$idSegActividad)->get();

      $usuarios = \DB::select("SELECT * FROM usuarios ORDER BY nombreUsuario ASC");
      $seguimiento = \DB::select("SELECT ag.idAsigSeguimiento, ag.idSegActividad, ag.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, ag.queRealiza
        FROM asignacionseguimientos AS ag
        INNER JOIN usuarios AS u ON ag.idu = u.idu
        WHERE idSegActividad = $idSegActividad");
      $cuantosSeg = count($seguimiento);


          return view('agregarUsuariosAct')
          ->with ('consulta', $consulta[0])
          ->with ('usuarios', $usuarios)  
          ->with('seguimiento',$seguimiento)
          ->with('cuantosSeg',$cuantosSeg);

  }

  public function correoUsuario(Request $request){
    $idu=$request->idu;
    $consultaCorreo=\DB::select("SELECT correo FROM usuarios WHERE idu=$idu");
    return view('correoUsuario')
    ->with('consultaCorreo',$consultaCorreo[0]);

  }

    public function seguimientoUsuarios(Request $request){
      $idSegActividad=$request->idSegActividad;
      $idu=$request->idu;
      $queRealiza=$request->queRealiza;
      $correo=$request->correo;

        $asignacionesUs = \DB::select("SELECT COUNT(*) AS cuantos 
        FROM asignacionseguimientos
        WHERE idSegActividad = $idSegActividad  AND idu = $idu");

        $cuantos = $asignacionesUs[0]->cuantos;

      
        if($cuantos == 0){
          $seg = new asignacionseguimientos;
          $seg->idSegActividad = $request->idSegActividad;
          $seg->idu = $request->idu;
          $seg->queRealiza = $request->queRealiza;
          $seg->save();
          
          $consultaActividad=\DB::select("SELECT nombreUsuario, c.razonSocial, su.sucursal,asunto, importanciaSeguimiento, DATE_FORMAT(s.fechaCreacion, '%d %b %y') as fechaCreacion,
          a.nombreArea, act.nombreActividad, CONCAT(DATE_FORMAT(s.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(s.fechaTermino, '%d %b %y')) AS periodo
          FROM seguimientoactividades AS s
          INNER JOIN clientes AS c ON c.idc=s.idc
          INNER JOIN sucursales AS su ON su.idSucursal=s.idSucursal
          INNER JOIN areas AS a ON a.idArea=s.idArea
          INNER JOIN actividades AS act ON act.idActividad=s.idActividad
          WHERE s.idSegActividad=$idSegActividad");


          // // Datos de correo
          // $subject = "Actividad Asignada";
          // $datos = array(
          //   'queRealiza'=>$queRealiza,
          //     'consulta'=>$consultaActividad[0]);
          // Mail::send('infoCorreoActividad', $datos, function($msj) use($subject,$correo){
          //     $msj->from("citas.germancontrolmotion@gmail.com","German Control Motion");
          //     $msj->subject($subject);
          //     $msj->to($correo);
          // });
          

          $seguimiento = \DB::select("SELECT ag.idAsigSeguimiento, ag.idSegActividad, ag.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, ag.queRealiza
          FROM asignacionseguimientos AS ag
          INNER JOIN usuarios AS u ON ag.idu = u.idu
          WHERE ag.idSegActividad = $idSegActividad");
        
          return view('asignacionseguimiento')
          ->with('seguimiento',$seguimiento)
          ->with('cuantos',$cuantos);



        }else{

            $seguimiento = \DB::select("SELECT ag.idAsigSeguimiento, ag.idSegActividad, ag.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, ag.queRealiza
            FROM asignacionseguimientos AS ag
            INNER JOIN usuarios AS u ON ag.idu = u.idu
            WHERE ag.idSegActividad = $idSegActividad");
          
          return view('asignacionseguimiento')
          ->with('seguimiento',$seguimiento)
          ->with('cuantos',$cuantos);

        }

          
    }

    public function borrarSeg(Request $request){
      $segBorrar=$request->idAsigSeguimiento;
      $idBuscar=$request->idSegActividad;

        $cuantos = 0;  

        $seg = asignacionseguimientos::find($segBorrar)->delete();
        
        $seguimiento = \DB::select("SELECT ag.idAsigSeguimiento, ag.idSegActividad, ag.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, ag.queRealiza
        FROM asignacionseguimientos AS ag
        INNER JOIN usuarios AS u ON ag.idu = u.idu
        WHERE idSegActividad = $idBuscar");
      
      return view('asignacionseguimiento')
      ->with('seguimiento',$seguimiento)
      ->with('cuantos',$cuantos);
    }

    public function detalleSeg ($idSegActividad){
      
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
      
          $botonDetalle='desactivo';
          $cuantosExis=0;
          $consultaEquipos = \DB::select("SELECT  ea.idSegActividad, ea.idEquipoActividad,e.idEquipos,e.folioRecepcion,e.vistaSuperior,e.vistaFrente,e.textoQr,e.placa_1,e.estatus,
            e.importancia,SUBSTR(datosCertificado,1,100) as datos,
            COUNT(partesreparacion.`idParte`) AS canti,
            CONCAT (e.GCMid, '-', e.complementoGCMid)AS gcmid,
            CONCAT(c.razonSocial,'-',s.sucursal) AS cliSuc,
            DATE_FORMAT(e.fechaRecoleccion,'%d %b %Y') AS fecha,
            DATE_FORMAT(e.fechaRegistro,'%d %b %Y') AS fechar,e.personaEntrega,
            CONCAT (e.serie,'-',te.tipoEquipo,'-',se.subtipoEquipo) AS equipo,
            CONCAT (e.marca,'-',e.modelo) AS marcaModelo, e.activo
            FROM recepcionEquipos AS e
            INNER JOIN clientes AS c ON c.idc= e.idc
            INNER JOIN sucursales AS s ON s.idSucursal= e.idSucursal
            INNER JOIN tipoequipos AS te ON te.idTipoEquipo= e.idTipoEquipo
            INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= e.idSubtipoEquipo
            INNER JOIN equiposEnActividad AS ea ON ea.idEquipos = e.idEquipos
            LEFT JOIN partesreparacion ON partesreparacion.`idEquipos`= e.`idEquipos`
            WHERE ea.idSegActividad=$idSegActividad
            GROUP BY  ea.idSegActividad, ea.idEquipoActividad,e.idEquipos, e.folioRecepcion, e.vistaSuperior,e.vistaFrente,e.placa_1, e.idu,e.textoQr,e.estatus,e.importancia,datos,gcmid,
            cliSuc,e.personaEntrega,equipo,marcaModelo,fecha, e.activo
            ORDER BY e.folioRecepcion DESC");
          $cuantosEquipos = count($consultaEquipos);
          
          $cuantosFac=0;
            $consultaFacturas = \DB::select("SELECT ea.idSegActividad, ea.idEquipoActividad, v.idServicios,v.estatusEntrega,
                v.estatusPago, v.estatusPortal, v.cashFlow,
                v.archivoRemision, v.archivo,v.archivoFactura,v.archivoPago,
                v.xmlFactura, v.numeroFactura,v.idFactura,v.activo,v.fechaFactura,
                DATE_FORMAT(v.fechaFactura,'%d %b %Y') AS fecha, 
                DATE_FORMAT(v.fechaPago,'%d %b %Y') AS fechap, v.montoFactura,
                v.tipoMoneda,v.archivoAdenda,s.sucursal AS suc, c.razonSocial AS cli 
                FROM facturas AS v
                LEFT JOIN sucursales AS s ON s.idSucursal = v.idSucursal
                LEFT JOIN clientes AS c ON c.idc = v.idc
                INNER JOIN equiposEnActividad AS ea ON ea.idFactura = v.idFactura
                WHERE ea.idSegActividad=$idSegActividad order by v.idServicios DESC");
            $cuantosServicios=count($consultaFacturas);
            $cuantosContac=0;
            $consultaContactos=\DB::select("SELECT ea.idSegActividad, ea.idEquipoActividad, cs.idContactoSuc, puesto, nombreCont, telefonoCont, correoCont, extension, whatsapp, contactoAdicional
                  FROM contactosucursales AS cs
                  INNER JOIN equiposEnActividad AS ea ON ea.idContactoSuc = cs.idContactoSuc
                  WHERE ea.idSegActividad=$idSegActividad ORDER BY cs.idContactoSuc DESC");
            $cuantosContactos=count($consultaContactos);

            $cuantosCoti=0;
            $consultaCotizacion = \DB::select("SELECT ea.idSegActividad, ea.idEquipoActividad,co.idCotizacion, co.numeroCotizacion, co.fechaCotizacion,co.clonActual,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
            co.noEquipos,CONCAT('$',' ',co.tipoMoneda,' ',co.montoTotal) AS montoMoneda,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus
            FROM cotizaciones AS co
            INNER JOIN usuarios AS u ON u.idu= co.idu
            INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
            INNER JOIN clientes AS c ON c.idc= co.idc
            INNER JOIN equiposEnActividad AS ea ON ea.idCotizacion = co.idCotizacion
            WHERE ea.idSegActividad=$idSegActividad
            ORDER BY numeroCotizacion");
            $cuantasCotizacion=count($consultaCotizacion);

                              
                $cuantosusuarios = \DB::select("SELECT * FROM asignacionseguimientos WHERE idSegActividad = $idSegActividad");
                              
                $cuantos = count($cuantosusuarios);

                if($cuantos == 0){

                  $consulta = \DB::select("SELECT sa.idSegActividad,sa.folio,  DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacion, sa.asunto,u.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, DATE_FORMAT(fechaInicio,'%d %b %Y') AS fechaInicio, DATE_FORMAT(fechaTermino,'%d %b %Y') AS fechaTermino,
                                            sa.importanciaSeguimiento, sa.activo, ar.nombreArea
                                        FROM seguimientoactividades AS sa
                                        INNER JOIN usuarios AS u ON sa.idu = u.idu
                                        INNER JOIN areas AS ar ON sa.idArea = ar.idArea
                                        WHERE idSegActividad = $idSegActividad");
                  return view('detalleSeguimiento2')
                  ->with ('cuantasCotizacion', $cuantasCotizacion)
                  ->with ('cuantosCoti', $cuantosCoti)
                  ->with ('consultaCotizacion', $consultaCotizacion)
                  ->with ('consultaContactos', $consultaContactos)
                  ->with ('cuantosContactos', $cuantosContactos)
                  ->with ('cuantosContac', $cuantosContac)
                  ->with ('consultaFacturas', $consultaFacturas)
                  ->with ('cuantosServicios', $cuantosServicios)
                  ->with ('cuantosFac', $cuantosFac)
                  ->with ('cuantosExis', $cuantosExis)
                  ->with ('cuantosEquipos', $cuantosEquipos)
                  ->with ('consultaEquipos', $consultaEquipos)
                  ->with ('botonDetalle', $botonDetalle)
                  
                  ->with('consulta',$consulta)
                  ->with('cuantos',$cuantos);
                  

                }else{
                  

                  $consulta = \DB::select("SELECT sa.idSegActividad,sa.folio,  DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacion, sa.asunto,sa.detalleSeguimiento,u.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, 
                                        DATE_FORMAT(fechaInicio,'%d %b %Y') AS fechaInicio, DATE_FORMAT(fechaTermino,'%d %b %Y') AS fechaTermino,
                                        sa.archivo1,sa.archivo2,sa.archivo3, sa.link1, sa.link2, sa.link3,
                                            sa.importanciaSeguimiento, sa.activo, ar.nombreArea
                                        FROM seguimientoactividades AS sa
                                        INNER JOIN usuarios AS u ON sa.idu = u.idu
                                        INNER JOIN areas AS ar ON sa.idArea = ar.idArea
                                        WHERE idSegActividad = $idSegActividad");
                $seguimientoA = \DB::select("SELECT t1.idSegActividad,t1.idu,t1.actor,t1.porcentaje,t1.numeroseguimientos,t1.ultimafecha,t1.tipo,t1.queRealiza,
                                        IF(t1.porcentaje=100 AND t1.ultimafecha<= t1.fechatermino AND t1.numeroseguimientos >0,'Cerrada en Tiempo',
                                        IF(t1.porcentaje<100 AND t1.ultimafecha<= t1.fechatermino AND t1.numeroseguimientos >0,'Seguimiento en Tiempo',
                                        IF(t1.numeroseguimientos = 0,'Sin Avance','Fuera de tiempo'))) AS statusactividad
                                        FROM(
                                        SELECT a.idSegActividad, a.idu,CONCAT(u.nombreusuario,' ',u.apaterno,' ',u.amaterno) AS actor,IF(ultimoporcentaje($idSegActividad,a.idu)IS NULL,0,ultimoporcentaje($idSegActividad,a.idu)) AS porcentaje,
                                        seguimiento($idSegActividad, a.idu ) AS numeroseguimientos,IF(ultimafecha($idSegActividad,a.idu) IS NULL,'',ultimafecha($idSegActividad,a.idu))AS ultimafecha,sa.fechatermino,u.tipo,a.queRealiza
                                        FROM asignacionseguimientos AS a
                                        INNER JOIN usuarios AS u ON u.idu = a.idu
                                        INNER JOIN seguimientoactividades AS sa ON sa.idSegActividad = a.idSegActividad
                                        WHERE a.idSegActividad= $idSegActividad) AS t1");

                $porcentaje= \DB::select("SELECT t1.idsegactividad,TRUNCATE(AVG(t1.porcentaje),0) AS porcentaje
                                          FROM
                                          (SELECT idsegactividad, idu,IF(ultimoporcentaje($idSegActividad,idu)IS NULL,0,ultimoporcentaje($idSegActividad,idu)) AS porcentaje
                                          FROM asignacionseguimientos
                                          WHERE idsegactividad= $idSegActividad) AS t1
                                          GROUP BY t1.idsegactividad");
                                                                                

                $detalle = \DB::select("SELECT * FROM detalleseguimientos
                                WHERE idSegActividad = $idSegActividad AND idu = $sidu");

                $atendidoPor =\DB::select("SELECT CONCAT(SUM(r),' de ',COUNT(*)) AS atendidopor
                                            FROM
                                            (SELECT a.idsegactividad, a.idu,seguimiento($idSegActividad, a.idu ) , IF(seguimiento($idSegActividad, a.idu )>=1,1,0) AS r
                                            FROM asignacionseguimientos AS a
                                            INNER JOIN usuarios AS u ON u.idu = a.idu
                                            WHERE idsegactividad= $idSegActividad) AS t1
                                            GROUP BY t1.idsegactividad");

                
     
                return view('detalleSeguimiento')
                ->with ('cuantasCotizacion', $cuantasCotizacion)
                ->with ('cuantosCoti', $cuantosCoti)
                ->with ('consultaCotizacion', $consultaCotizacion)
                ->with ('consultaContactos', $consultaContactos)
                ->with ('cuantosContactos', $cuantosContactos)
                ->with ('cuantosContac', $cuantosContac)
                ->with ('consultaFacturas', $consultaFacturas)
                ->with ('cuantosServicios', $cuantosServicios)
                ->with ('cuantosFac', $cuantosFac)
                ->with ('cuantosExis', $cuantosExis)
                ->with ('cuantosEquipos', $cuantosEquipos)
                ->with ('consultaEquipos', $consultaEquipos)
                ->with ('botonDetalle', $botonDetalle)
     
                ->with('consulta',$consulta)
                ->with('seguimientoA',$seguimientoA)
                ->with('cuantos',$cuantos)
                ->with('porcentaje',$porcentaje[0])
                ->with('atendidoPor',$atendidoPor[0])
                ->with('detalle',$detalle);
                }

       
      }
    }


    public function seguimientos ($idSegActividad){
      
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
          $botonDetalle='desactivo';
          $cuantosExis=0;
          $consultaEquipos = \DB::select("SELECT  ea.idSegActividad, ea.idEquipoActividad,e.idEquipos,e.folioRecepcion,e.vistaSuperior,e.vistaFrente,e.textoQr,e.placa_1,e.estatus,
            e.importancia,SUBSTR(datosCertificado,1,100) as datos,
            COUNT(partesreparacion.`idParte`) AS canti,
            CONCAT (e.GCMid, '-', e.complementoGCMid)AS gcmid,
            CONCAT(c.razonSocial,'-',s.sucursal) AS cliSuc,
            DATE_FORMAT(e.fechaRecoleccion,'%d %b %Y') AS fecha,
            DATE_FORMAT(e.fechaRegistro,'%d %b %Y') AS fechar,e.personaEntrega,
            CONCAT (e.serie,'-',te.tipoEquipo,'-',se.subtipoEquipo) AS equipo,
            CONCAT (e.marca,'-',e.modelo) AS marcaModelo, e.activo
            FROM recepcionEquipos AS e
            INNER JOIN clientes AS c ON c.idc= e.idc
            INNER JOIN sucursales AS s ON s.idSucursal= e.idSucursal
            INNER JOIN tipoequipos AS te ON te.idTipoEquipo= e.idTipoEquipo
            INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= e.idSubtipoEquipo
            INNER JOIN equiposEnActividad AS ea ON ea.idEquipos = e.idEquipos
            LEFT JOIN partesreparacion ON partesreparacion.`idEquipos`= e.`idEquipos`
            WHERE ea.idSegActividad=$idSegActividad
            GROUP BY  ea.idSegActividad, ea.idEquipoActividad,e.idEquipos, e.folioRecepcion, e.vistaSuperior,e.vistaFrente,e.placa_1, e.idu,e.textoQr,e.estatus,e.importancia,datos,gcmid,
            cliSuc,e.personaEntrega,equipo,marcaModelo,fecha, e.activo
            ORDER BY e.folioRecepcion DESC");
          $cuantosEquipos = count($consultaEquipos);
          
          $cuantosFac=0;
            $consultaFacturas = \DB::select("SELECT ea.idSegActividad, ea.idEquipoActividad, v.idServicios,v.estatusEntrega,
                v.estatusPago, v.estatusPortal, v.cashFlow,
                v.archivoRemision, v.archivo,v.archivoFactura,v.archivoPago,
                v.xmlFactura, v.numeroFactura,v.idFactura,v.activo,v.fechaFactura,
                DATE_FORMAT(v.fechaFactura,'%d %b %Y') AS fecha, 
                DATE_FORMAT(v.fechaPago,'%d %b %Y') AS fechap, v.montoFactura,
                v.tipoMoneda,v.archivoAdenda,s.sucursal AS suc, c.razonSocial AS cli 
                FROM facturas AS v
                LEFT JOIN sucursales AS s ON s.idSucursal = v.idSucursal
                LEFT JOIN clientes AS c ON c.idc = v.idc
                INNER JOIN equiposEnActividad AS ea ON ea.idFactura = v.idFactura
                WHERE ea.idSegActividad=$idSegActividad order by v.idServicios DESC");
            $cuantosServicios=count($consultaFacturas);
            $cuantosContac=0;
            $consultaContactos=\DB::select("SELECT ea.idSegActividad, ea.idEquipoActividad, cs.idContactoSuc, puesto, nombreCont, telefonoCont, correoCont, extension, whatsapp, contactoAdicional
                  FROM contactosucursales AS cs
                  INNER JOIN equiposEnActividad AS ea ON ea.idContactoSuc = cs.idContactoSuc
                  WHERE ea.idSegActividad=$idSegActividad ORDER BY cs.idContactoSuc DESC");
            $cuantosContactos=count($consultaContactos);

            $cuantosCoti=0;
            $consultaCotizacion = \DB::select("SELECT ea.idSegActividad, ea.idEquipoActividad,co.idCotizacion, co.numeroCotizacion, co.fechaCotizacion,co.clonActual,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
            co.noEquipos,CONCAT('$',' ',co.tipoMoneda,' ',co.montoTotal) AS montoMoneda,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus
            FROM cotizaciones AS co
            INNER JOIN usuarios AS u ON u.idu= co.idu
            INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
            INNER JOIN clientes AS c ON c.idc= co.idc
            INNER JOIN equiposEnActividad AS ea ON ea.idCotizacion = co.idCotizacion
            WHERE ea.idSegActividad=$idSegActividad
            ORDER BY numeroCotizacion");
            $cuantasCotizacion=count($consultaCotizacion);

          
                 $consulta = \DB::select("SELECT sa.idSegActividad,sa.folio,  DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacion, sa.asunto,sa.detalleSeguimiento,u.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, 
                                        DATE_FORMAT(fechaInicio,'%d %b %Y') AS fechaInicio, DATE_FORMAT(fechaTermino,'%d %b %Y') AS fechaTermino,
                                        sa.archivo1,sa.archivo2,sa.archivo3, sa.link1, sa.link2, sa.link3,
                                            sa.importanciaSeguimiento, sa.activo, ar.nombreArea
                                        FROM seguimientoactividades AS sa
                                        INNER JOIN usuarios AS u ON sa.idu = u.idu
                                        INNER JOIN areas AS ar ON sa.idArea = ar.idArea
                                        WHERE idSegActividad = $idSegActividad");

               $idSegSig = detalleseguimientos::orderBy('idDetSeg','desc')
              ->where('idSegActividad','=',$idSegActividad)
              ->where('idu','=',$sidu)
              ->take(1)
              ->get();

              $cuantos = count($idSegSig);

              if($cuantos==0)
              {
                $idSig = 1;
              }
              else {
                $idSig = $idSegSig[0]->folio+1;
              }

                $seguimiento = \DB::select("SELECT * FROM detalleseguimientos 
                WHERE idu = $sidu AND idSegActividad = $idSegActividad ");

                $cuantosSeg = count($seguimiento);

                  $seguimientosRealizados = \DB::select("SELECT t1.idSegActividad,t1.idu,t1.actor,t1.porcentaje,t1.numeroseguimientos,t1.ultimafecha,t1.tipo,t1.queRealiza,
                                                        IF(t1.porcentaje=100 AND t1.ultimafecha<= t1.fechatermino AND t1.numeroseguimientos >0,'Cerrada en Tiempo',
                                                        IF(t1.porcentaje<100 AND t1.ultimafecha<= t1.fechatermino AND t1.numeroseguimientos >0,'Seguimiento en Tiempo',
                                                        IF(t1.numeroseguimientos = 0,'Sin Avance','Fuera de tiempo'))) AS statusactividad
                                                        FROM(
                                                        SELECT a.idSegActividad, a.idu,CONCAT(u.nombreusuario,' ',u.apaterno,' ',u.amaterno) AS actor,IF(ultimoporcentaje($idSegActividad,a.idu)IS NULL,0,ultimoporcentaje($idSegActividad,a.idu)) AS porcentaje,
                                                        seguimiento($idSegActividad, a.idu ) AS numeroseguimientos,IF(ultimafecha($idSegActividad,a.idu) IS NULL,'',ultimafecha($idSegActividad,a.idu))AS ultimafecha,sa.fechatermino,u.tipo,a.queRealiza
                                                        FROM asignacionseguimientos AS a
                                                        INNER JOIN usuarios AS u ON u.idu = a.idu
                                                        INNER JOIN seguimientoactividades AS sa ON sa.idSegActividad = a.idSegActividad
                                                        WHERE a.idSegActividad= $idSegActividad ) AS t1");

                  $porcentajeUltimoUs = \DB::select("SELECT a.idSegActividad, a.idu,CONCAT(u.nombreusuario,' ',u.apaterno,' ',u.amaterno) AS actor,u.tipo,IF(ultimoporcentaje($idSegActividad,a.idu)IS NULL,0,ultimoporcentaje($idSegActividad,a.idu)) AS porcentaje,
                                                      seguimiento($idSegActividad, a.idu ) AS numeroseguimientos
                                                      FROM asignacionseguimientos AS a
                                                      INNER JOIN usuarios AS u ON u.idu = a.idu
                                                      WHERE idSegActividad= $idSegActividad AND u.idu = $sidu");
                
                
                $info = \DB::select(" SELECT sa.idSegActividad,sa.folio, sa.nombreUsuario, u.tipo, sa.idu
                                        FROM seguimientoactividades AS sa
                                        INNER JOIN usuarios AS u ON sa.idu = u.idu
                                        WHERE idSegActividad = $idSegActividad");

                  $detalle = \DB::select("SELECT * FROM detalleseguimientos
                                WHERE idSegActividad = $idSegActividad AND idu = $sidu");
                  
                  $cuantosDet = count ($detalle);
                  
                  $porcentaje= \DB::select("SELECT t1.idsegactividad,TRUNCATE(AVG(t1.porcentaje),0) AS porcentaje
                                          FROM
                                          (SELECT idsegactividad, idu,IF(ultimoporcentaje($idSegActividad,idu)IS NULL,0,ultimoporcentaje($idSegActividad,idu)) AS porcentaje
                                          FROM asignacionseguimientos
                                          WHERE idsegactividad= $idSegActividad) AS t1
                                          GROUP BY t1.idsegactividad");

                $atendidoPor =\DB::select("SELECT CONCAT(SUM(r),' de ',COUNT(*)) AS atendidopor
                                            FROM
                                            (SELECT a.idsegactividad, a.idu,seguimiento($idSegActividad, a.idu ) , IF(seguimiento($idSegActividad, a.idu )>=1,1,0) AS r
                                            FROM asignacionseguimientos AS a
                                            INNER JOIN usuarios AS u ON u.idu = a.idu
                                            WHERE idsegactividad= $idSegActividad) AS t1
                                            GROUP BY t1.idsegactividad");

                  // $evidencia = \DB::select("SELECT * FROM evidenciaseguimientos");
              
              return view('detalleSeguimientoUs')
              ->with ('cuantasCotizacion', $cuantasCotizacion)
              ->with ('cuantosCoti', $cuantosCoti)
              ->with ('consultaCotizacion', $consultaCotizacion)
              ->with ('consultaContactos', $consultaContactos)
              ->with ('cuantosContactos', $cuantosContactos)
              ->with ('cuantosContac', $cuantosContac)
              ->with ('consultaFacturas', $consultaFacturas)
              ->with ('cuantosServicios', $cuantosServicios)
              ->with ('cuantosFac', $cuantosFac)
              ->with ('cuantosExis', $cuantosExis)
              ->with ('cuantosEquipos', $cuantosEquipos)
              ->with ('consultaEquipos', $consultaEquipos)
              ->with ('botonDetalle', $botonDetalle)

              ->with('consulta',$consulta)
              ->with('idSig',$idSig)
              ->with('cuantosSeg',$cuantosSeg)
              ->with('seguimiento',$seguimiento)
              ->with('seguimientosRealizados',$seguimientosRealizados)
              ->with('porcentajeUltimoUs',$porcentajeUltimoUs[0])
              ->with('cuantosDet',$cuantosDet)
              ->with('detalle',$detalle)
              // ->with('evidencia',$evidencia)
              ->with('porcentaje',$porcentaje[0])
              ->with('atendidoPor',$atendidoPor[0])
              ->with('info',$info[0]);
      }
    }

    public function seguimientoPorUsuario(Request $request){
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
      $idu = $request->idu;
      $tipousuario = $request->tipousuario;
      $idSegActividad = $request->idSegActividad;
      $folio=$request->folio;
      $fechaDet=$request->fechaDet;
      $detalleSeg=$request->detalleSeg;
      $fechaSiguiente=$request->fechaSiguiente;
      $porcentaje=$request->porcentaje;
      $resultadoSeg=$request->resultadoSeg;
      $notasSeg=$request->notasSeg;
      $propuestaSeg=$request->propuestaSeg;
      $estado=$request->estado;
      $detalleArchivo1=$request->detalleArchivo1;
      $detalleArchivo2=$request->detalleArchivo2;
      $detalleArchivo3=$request->detalleArchivo3;
      $detalleArchivo4=$request->detalleArchivo4;
      $detalleArchivo5=$request->detalleArchivo5;
      $verArchivo1=$request->verArchivo1;
      $verArchivo2=$request->verArchivo2;
      $verArchivo3=$request->verArchivo3;
      $verArchivo4=$request->verArchivo4;
      $verArchivo5=$request->verArchivo5;



      $this->validate ($request,[
        'detalleSeg'=>['required'],
        'porcentaje'=>['required'],
        ]);

      // archivo de evidencia
      $file = $request->file('archivo1');
      if($file!=""){
      $ldate = date('Ymd_His_');
      $foto = $file->getClientOriginalName();
      $archivo1 = $ldate.$foto;
      \Storage::disk('local')->put($archivo1, \File::get($file));
      }
      else{
      $archivo1 ="Sin archivo";
      }

      // archivo 2

       $file = $request->file('archivo2');
       if($file!=""){
       $ldate = date('Ymd_His_');
       $foto = $file->getClientOriginalName();
       $archivo2 = $ldate.$foto;
       \Storage::disk('local')->put($archivo2, \File::get($file));
       }
       else{
       $archivo2 ="Sin archivo";
       }
      

      //  archivo3
       
       $file = $request->file('archivo3');
       if($file!=""){
       $ldate = date('Ymd_His_');
       $foto = $file->getClientOriginalName();
       $archivo3 = $ldate.$foto;
       \Storage::disk('local')->put($archivo3, \File::get($file));
       }
       else{
       $archivo3 ="Sin archivo";
       }

    // archivo 4
      $file = $request->file('archivo4');
      if($file!=""){
      $ldate = date('Ymd_His_');
      $foto = $file->getClientOriginalName();
      $archivo4 = $ldate.$foto;
      \Storage::disk('local')->put($archivo4, \File::get($file));
      }
      else{
      $archivo4 ="Sin archivo";
      }

      // archivo 5
       
       $file = $request->file('archivo5');
       if($file!=""){
       $ldate = date('Ymd_His_');
       $foto = $file->getClientOriginalName();
       $archivo5 = $ldate.$foto;
       \Storage::disk('local')->put($archivo5, \File::get($file));
       }
       else{
       $archivo5 ="Sin archivo";
       }



        $segui = \DB::select("SELECT * FROM detalleseguimientos
                                WHERE idSegActividad = $idSegActividad");
        $cuantosDet = count ($segui);

          $seg = new detalleseguimientos;
          $seg->idu = $request->idu;
          $seg->tipousuario = $request->tipousuario;
          $seg->idSegActividad = $request->idSegActividad;
          $seg->folio = $request->folio;
          $seg->fechaDet = $request->fechaDet;
          $seg->detalleSeg = $request->detalleSeg;
          $seg->fechaSiguiente = $request->fechaSiguiente;
          $seg->porcentaje = $request->porcentaje;
          $seg->resultadoSeg=$request->resultadoSeg;
          $seg->notasSeg=$request->notasSeg;
          $seg->propuestaSeg=$request->propuestaSeg;
          $seg->estado = $request->estado;
          $seg->detalleArchivo1=$request->detalleArchivo1;
          $seg->detalleArchivo2=$request->detalleArchivo2;
          $seg->detalleArchivo3=$request->detalleArchivo3;
          $seg->detalleArchivo4=$request->detalleArchivo4;
          $seg->detalleArchivo5=$request->detalleArchivo5;
          $seg->verArchivo1=$request->verArchivo1;
          $seg->verArchivo2=$request->verArchivo2;
          $seg->verArchivo3=$request->verArchivo3;
          $seg->verArchivo4=$request->verArchivo4;
          $seg->verArchivo5=$request->verArchivo5;
          $seg->archivo1=$archivo1;
          $seg->archivo2=$archivo2;
          $seg->archivo3=$archivo3;
          $seg->archivo4=$archivo4;
          $seg->archivo5=$archivo5;
          $seg->estatusAtencion = 'En tiempo';
          $seg->acuseRecibido = 'Si';
          $seg->save();

          // $detalle = \DB::select("SELECT * FROM detalleseguimientos
          // WHERE idSegActividad = $idSegActividad")
           $consulta = \DB::select("SELECT sa.idSegActividad,sa.folio,  DATE_FORMAT(fechaCreacion,'%d %b %Y') AS fechaCreacion, sa.asunto,u.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo, DATE_FORMAT(fechaInicio,'%d %b %Y') AS fechaInicio, DATE_FORMAT(fechaTermino,'%d %b %Y') AS fechaTermino,
                                            sa.importanciaSeguimiento, sa.activo, ar.nombreArea
                                        FROM seguimientoactividades AS sa
                                        INNER JOIN usuarios AS u ON sa.idu = u.idu
                                        INNER JOIN areas AS ar ON sa.idArea = ar.idArea
                                        WHERE idSegActividad = $idSegActividad");

                $seguimiento = \DB::select("SELECT  ds.idDetSeg,ds.estatusAtencion, ds.acuseRecibido, ds.idu, u.nombreUsuario, u.aPaterno, u.aMaterno, u.tipo
                                          FROM detalleseguimientos AS ds
                                          INNER JOIN usuarios AS u ON ds.idu = u.idu
                                          WHERE ds.idSegActividad = $idSegActividad AND ds.idu = $sidu LIMIT 1");
                
                $info = \DB::select(" SELECT sa.idSegActividad,sa.folio, sa.nombreUsuario, u.tipo, sa.idu
                                        FROM seguimientoactividades AS sa
                                        INNER JOIN usuarios AS u ON sa.idu = u.idu
                                        WHERE idSegActividad = $idSegActividad");

                  $detalle = \DB::select("SELECT idDetSeg,idSegActividad,folio,fechaDet,detalleSeg,estado,resultadoSeg,
                                  notasSeg,propuestaSeg,porcentaje,created_at FROM detalleseguimientos
                                WHERE idSegActividad = $idSegActividad AND idu = $sidu");
                  
                  $cuantosDet = count ($detalle);

                  $porcentaje = \DB::select("SELECT MAX(porcentaje) AS porcentaje
                                              FROM detalleseguimientos 
                                              WHERE idSegActividad = $idSegActividad AND idu = $sidu");

                  // $evidencia = \DB::select("SELECT * FROM evidenciaseguimientos");;



              return redirect()->back()
              ->with('detalle',$detalle)
              ->with('cuantosDet',$cuantosDet)
              ->with('consulta',$consulta)
              ->with('seguimiento',$seguimiento)
              ->with('cuantosDet',$cuantosDet)
              // ->with('detalle',$detalle)
              // ->with('evidencia',$evidencia)
              ->with('porcentaje',$porcentaje[0])
              ->with('info',$info[0]);
    }
  }
    public function borrarSegUs(Request $request){ //salian todos los del usuario
      $sname = Session::get('sesionname');
      $sidu = Session::get('sesionidu');
      $stipo = Session::get('sesiontipo');
 
      $segBorrar=$request->idDetSeg;
      $idBuscar=$request->idSegActividad;
      // return $segBorrar;

      $seg = detalleseguimientos::find($segBorrar)->delete();
        
      $detalle = \DB::select("SELECT idDetSeg,idSegActividad,folio,fechaDet,detalleSeg,estado,resultadoSeg,
                                  notasSeg,propuestaSeg,porcentaje,created_at FROM detalleseguimientos
                                WHERE idSegActividad = $idBuscar AND idu = $sidu");
    
    return view('detalleSegUsuario')
    ->with('detalle',$detalle);
    }

    public function archivosSeg(Request $request){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
        $idDetSeg=$request->idDetSeg;

          // return $idDetSeg;
      
          $consulta = \DB::select("SELECT detalleArchivo1, detalleArchivo2,detalleArchivo3,detalleArchivo4,detalleArchivo5,
          verArchivo1,verArchivo2,verArchivo3,verArchivo4,verArchivo5,
          archivo1,archivo2,archivo3,archivo4,archivo5,idu,tipousuario
          FROM detalleseguimientos WHERE idDetSeg = $idDetSeg");
      
      return view('evidencias')
      ->with('consulta',$consulta[0])
      ->with('sname',$sname)
      ->with('stipo',$stipo)
      ->with('sidu',$sidu);

      
    }

    
    /////////////////MODAL////////////////////

    public function modalSeguimientos(Request $request){

      $idUsuario=$request->idUsuario;
      $idSegActividad=$request->idSegActividad;

      $detalle = \DB::select("SELECT * FROM detalleseguimientos
      WHERE idSegActividad = $idSegActividad AND idu = $idUsuario");

      $cuantosSeg = count($detalle);
      
      return view ('modalSeguimientos')
      ->with('idUsuario',$idUsuario)
      ->with('idSegActividad',$idSegActividad)
      ->with('detalle',$detalle)
      ->with('cuantosSeg',$cuantosSeg);
    }


    ///////////Vista de archivos para el modal///////////////////

    public function archivosSeg2(Request $request){
      $idDetSeg=$request->idDetSeg;

      $sname = Session::get('sesionname');
      $sidu = Session::get('sesionidu');
      $stipo = Session::get('sesiontipo');

        // return $idDetSeg;
    
      $consulta = \DB::select("SELECT detalleArchivo1, detalleArchivo2,detalleArchivo3,detalleArchivo4,detalleArchivo5,
                            verArchivo1,verArchivo2,verArchivo3,verArchivo4,verArchivo5,
                            archivo1,archivo2,archivo3,archivo4,archivo5,idu,tipousuario
                            FROM detalleseguimientos WHERE idDetSeg = $idDetSeg");

    
    return view('evidenciasParaConsulta')
    ->with('consulta',$consulta[0])
    ->with('sname',$sname)
    ->with('sidu',$sidu)
    ->with('stipo',$stipo);
    
  }

  
  public function comboActividades(Request $request){
  
    $idArea = $request->get('idArea');
    $consulta = actividades::where('idArea','=',$idArea)
    ->orderBy('nombreActividad','ASC')
    ->get();

          return view ('comboActividades')
          ->with('consulta',$consulta);


  }

    public function infoUsuarios(Request $request){
      $idu=$request->idu;
      $idSegActividad=$request->idSegActividad;

      $detalle = \DB::select("SELECT * FROM detalleseguimientos
                              WHERE idSegActividad = $idSegActividad AND idu = $idu");
    
    return view('detallePorUsuarioSeg')
    ->with('detalle',$detalle);



    }

  public function anexgridSeguimientoAsignadas(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');
    $anexGrid = new anexGrid();

    $folio='';
    $nombreUsuario='';
    $importanciaSeguimiento='';
    $nombreArea='';

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
    }
    $order;
    $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

    if($stipo=='Administrador'){
        $registros = DB::table('seguimientoactividades AS sa')            
                    ->select('idSegActividad','sa.folio','asunto','nombreUsuario','c.razonSocial','importanciaSeguimiento','a.nombreArea','sa.activo',
                    DB::raw('CONCAT(c.razonSocial," / ",s.sucursal) AS cliente'),
                    DB::raw('CONCAT(DATE_FORMAT(fechaInicio,"%d %b %Y")," - ",DATE_FORMAT(fechaTermino,"%d %b %Y")) AS periodo'),
                    DB::raw('DATE_FORMAT(fechaCreacion,"%d %b %Y") AS fecha'))
                    ->leftJoin('clientes as c', 'sa.idc', '=', 'c.idc') 
                    ->leftJoin('sucursales as s', 'sa.idSucursal', '=', 's.idSucursal') 
                    ->leftJoin('areas as a', 'sa.idArea', '=', 'a.idArea') 
                    ->where('sa.folio', 'like', '%'.$folio.'%')
                    ->where('nombreUsuario', 'like', '%'.$nombreUsuario.'%')
                    ->where('importanciaSeguimiento', 'like', '%'.$importanciaSeguimiento.'%')
                    ->where('nombreArea', 'like', '%'.$nombreArea.'%')
                    ->where(function($query) {
                      $anexGrid = new anexGrid();
                      $cliente='';
                      foreach($anexGrid->filtros as $f)
                        {
                          if($f['columna'] == 'cliente' && $f['valor'] != ''){
                            $cliente = $f['valor'];
                          }
                        }
                      $query->where('c.razonSocial', 'like', '%'.$cliente.'%')
                      ->orwhere('s.sucursal', 'like', '%'.$cliente.'%');
                    })
                    
                    ->groupBy('sa.idSegActividad')
                    ->orderBy('idSegActividad','desc')
                    ->skip($anexGrid->pagina)
                    ->take($anexGrid->limite)
                    ->get();

        $total = DB::table('seguimientoactividades AS sa')            
                    ->select('idSegActividad','sa.folio','asunto','nombreUsuario','c.razonSocial','importanciaSeguimiento','a.nombreArea','sa.activo',
                    DB::raw('CONCAT(c.razonSocial," / ",s.sucursal) AS cliente'),
                    DB::raw('CONCAT(DATE_FORMAT(fechaInicio,"%d %b %Y")," - ",DATE_FORMAT(fechaTermino,"%d %b %Y")) AS periodo'),
                    DB::raw('DATE_FORMAT(fechaCreacion,"%d %b %Y") AS fecha'))
                    ->leftJoin('clientes as c', 'sa.idc', '=', 'c.idc') 
                    ->leftJoin('sucursales as s', 'sa.idSucursal', '=', 's.idSucursal') 
                    ->leftJoin('areas as a', 'sa.idArea', '=', 'a.idArea') 
                    ->where('sa.folio', 'like', '%'.$folio.'%')
                    ->where('nombreUsuario', 'like', '%'.$nombreUsuario.'%')
                    ->where('importanciaSeguimiento', 'like', '%'.$importanciaSeguimiento.'%')
                    ->where('nombreArea', 'like', '%'.$nombreArea.'%')
                    ->where(function($query) {
                      $anexGrid = new anexGrid();
                      $cliente='';
                      foreach($anexGrid->filtros as $f)
                        {
                          if($f['columna'] == 'cliente' && $f['valor'] != ''){
                            $cliente = $f['valor'];
                          }
                        }
                      $query->where('c.razonSocial', 'like', '%'.$cliente.'%')
                      ->orwhere('s.sucursal', 'like', '%'.$cliente.'%');
                    })
                    ->count();
    }else{
        $registros = DB::table('seguimientoactividades AS sa')            
                    ->select('sa.idSegActividad','sa.folio','asunto','nombreUsuario','c.razonSocial','importanciaSeguimiento','a.nombreArea','sa.activo',
                    DB::raw('CONCAT(c.razonSocial," / ",s.sucursal) AS cliente'),
                    DB::raw('CONCAT(DATE_FORMAT(fechaInicio,"%d %b %Y")," - ",DATE_FORMAT(fechaTermino,"%d %b %Y")) AS periodo'),
                    DB::raw('DATE_FORMAT(fechaCreacion,"%d %b %Y") AS fecha'))
                    ->leftJoin('clientes as c', 'sa.idc', '=', 'c.idc') 
                    ->leftJoin('sucursales as s', 'sa.idSucursal', '=', 's.idSucursal') 
                    ->leftJoin('areas as a', 'sa.idArea', '=', 'a.idArea') 
                    // ->leftJoin('detalleseguimientos as ds', 'sa.idSegActividad', '=', 'ds.idSegActividad') 
                    ->leftJoin('asignacionseguimientos as as', 'sa.idSegActividad', '=', 'as.idSegActividad') 
                    ->where('sa.folio', 'like', '%'.$folio.'%')
                    ->where('nombreUsuario', 'like', '%'.$nombreUsuario.'%')
                    ->where('importanciaSeguimiento', 'like', '%'.$importanciaSeguimiento.'%')
                    ->where('nombreArea', 'like', '%'.$nombreArea.'%')
                    ->where('as.idu', 'like', '%'.$sidu.'%')
                    ->where(function($query) {
                      $anexGrid = new anexGrid();
                      $cliente='';
                      foreach($anexGrid->filtros as $f)
                        {
                          if($f['columna'] == 'cliente' && $f['valor'] != ''){
                            $cliente = $f['valor'];
                          }
                        }
                      $query->where('c.razonSocial', 'like', '%'.$cliente.'%')
                      ->orwhere('s.sucursal', 'like', '%'.$cliente.'%');
                    })
                    
                    ->groupBy('sa.idSegActividad')
                    ->orderBy('idSegActividad','desc')
                    ->skip($anexGrid->pagina)
                    ->take($anexGrid->limite)
                    ->get();
        $total = DB::table('seguimientoactividades AS sa')            
                    ->select('sa.idSegActividad','sa.folio','asunto','nombreUsuario','c.razonSocial','importanciaSeguimiento','a.nombreArea','sa.activo',
                    DB::raw('CONCAT(c.razonSocial," / ",s.sucursal) AS cliente'),
                    DB::raw('CONCAT(DATE_FORMAT(fechaInicio,"%d %b %Y")," - ",DATE_FORMAT(fechaTermino,"%d %b %Y")) AS periodo'),
                    DB::raw('DATE_FORMAT(fechaCreacion,"%d %b %Y") AS fecha'))
                    ->leftJoin('clientes as c', 'sa.idc', '=', 'c.idc') 
                    ->leftJoin('sucursales as s', 'sa.idSucursal', '=', 's.idSucursal') 
                    ->leftJoin('areas as a', 'sa.idArea', '=', 'a.idArea') 
                    // ->leftJoin('detalleseguimientos as ds', 'sa.idSegActividad', '=', 'ds.idSegActividad') 
                    ->leftJoin('asignacionseguimientos as as', 'sa.idSegActividad', '=', 'as.idSegActividad') 
                    ->where('sa.folio', 'like', '%'.$folio.'%')
                    ->where('nombreUsuario', 'like', '%'.$nombreUsuario.'%')
                    ->where('importanciaSeguimiento', 'like', '%'.$importanciaSeguimiento.'%')
                    ->where('nombreArea', 'like', '%'.$nombreArea.'%')
                    ->where('as.idu', 'like', '%'.$sidu.'%')
                    ->where(function($query) {
                      $anexGrid = new anexGrid();
                      $cliente='';
                      foreach($anexGrid->filtros as $f)
                        {
                          if($f['columna'] == 'cliente' && $f['valor'] != ''){
                            $cliente = $f['valor'];
                          }
                        }
                      $query->where('c.razonSocial', 'like', '%'.$cliente.'%')
                      ->orwhere('s.sucursal', 'like', '%'.$cliente.'%');
                    })
                    
                    ->count();
                    
    }

    $data = array(
        'total' => $total,
        'data' => $registros
    );

    return response()->json($data, 200);  

  }































  public function reporteSeguimientoVista(Request $request){
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
            

      // $actividadesPorcentajes= \DB::select("SELECT idSegActividad,MAX(porcentaje) AS porcentaje FROM detalleseguimientos GROUP BY idSegActividad");
      $actividadesPorcentajes= \DB::select("SELECT idSegActividad,AVG(porcentaje)AS porcentaje FROM (SELECT idSegActividad,MAX(porcentaje)AS porcentaje ,idu  FROM detalleseguimientos  GROUP BY idSegActividad,idu) AS t1 GROUP BY idSegActividad");
      $yaAsignadosConsulta= \DB::select("SELECT idSegActividad AS idSegActividad FROM asignacionseguimientos WHERE idu=$sidu GROUP BY idSegActividad");                                           

      if (count($actividadesPorcentajes) >= 1) {
        for ($i=0; $i < count($actividadesPorcentajes) ; $i++) {
          if ($actividadesPorcentajes[$i]->porcentaje >= 100) {
            $actividadesCerradas[$i]=$actividadesPorcentajes[$i]->idSegActividad;                        
          }else{
            $actividadesCerradas[$i]=0;
          }      
        }
      }else{
        $actividadesCerradas=[0];
      }
      
      if (count($yaAsignadosConsulta) >= 1) {
        for ($i=0; $i < count($yaAsignadosConsulta) ; $i++) {
          if (count($yaAsignadosConsulta) > 0) {
            $yaAsignados[$i]=$yaAsignadosConsulta[$i]->idSegActividad;
          }else{
            $yaAsignados[$i]=0;
          }      
        }
      }else{
        $yaAsignados=[0];
      }     

      if($stipo == 'Administrador'){      	
        ///////////////////////////////// CREADAS /////////////////////////////////
  
        $cerradasCreadas = DB::table('seguimientoactividades AS sa')            
        ->select('sa.idSegActividad')
        ->whereIn('sa.idSegActividad',$actividadesCerradas)
        // ->where('sa.activo','=','Si')
        ->where('sa.idu','=',$sidu)
        ->get();
        $cuantasCerradasCreadas = count($cerradasCreadas);
  
        $abiertasCreadas = DB::table('seguimientoactividades AS sa')            
        ->select('sa.idSegActividad')
        ->whereNotIn('sa.idSegActividad',$actividadesCerradas)
        // ->where('sa.activo','=','Si')
        ->where('sa.idu','=',$sidu)
        ->get();
        $cuantasAbiertasCreadas = count($abiertasCreadas);
        
        $expiranCreadas = DB::table('seguimientoactividades AS sa')            
        ->select('sa.idSegActividad')
        ->where('sa.fechaTermino','>=',$semana[0])
        ->where('sa.fechaTermino','<=',$semana[6])
        ->whereNotIn('sa.idSegActividad',$actividadesCerradas)
        // ->where('sa.activo','=','Si')
        ->where('sa.idu','=',$sidu)
        ->get();
        $cuantasExpiranCreadas = count($expiranCreadas);
        
        $seguimientoCreadas = DB::table('detalleseguimientos AS det')
        ->select('det.idSegActividad')
        ->where('det.fechaSiguiente','>=',$semana[0])
        ->where('det.fechaSiguiente','<=',$semana[6])
        ->whereNotIn('det.idSegActividad',$actividadesCerradas)
        // ->where('det.activo','=','Si')
        ->where('det.idu','=',$sidu)
        ->get();
        $cuantasSeguimientoCreadas = count($seguimientoCreadas);
          
        ///////////////////////////////// ASIGNADAS /////////////////////////////////
        
        $cerradasAsignadas = DB::table('seguimientoactividades AS sa')            
        ->select('sa.idSegActividad')
        ->whereIn('sa.idSegActividad',$actividadesCerradas)        
        ->whereIn('sa.idSegActividad', function ($yaAsignados) {
          $sidu = Session::get('sesionidu');
          $yaAsignados->select('idSegActividad')->from('asignacionseguimientos')->where('idu','=',$sidu);
        })
        // ->where('sa.activo','=','Si')
        ->get();
        $cuantasCerradasAsignadas = count($cerradasAsignadas);
  
        $abiertasAsignadas = DB::table('seguimientoactividades AS sa')            
        ->select('sa.idSegActividad')
        ->whereNotIn('sa.idSegActividad',$actividadesCerradas)
        ->whereIn('sa.idSegActividad', function ($yaAsignados) {
          $sidu = Session::get('sesionidu');
          $yaAsignados->select('idSegActividad')->from('asignacionseguimientos')->where('idu','=',$sidu);
        })
        // ->where('sa.activo','=','Si')
        ->get();
        $cuantasAbiertasAsignadas = count($abiertasAsignadas);
  
        $expiranAsignadas = DB::table('seguimientoactividades AS sa')            
        ->select('sa.idSegActividad')
        ->where('sa.fechaTermino','>=',$semana[0])
        ->where('sa.fechaTermino','<=',$semana[6])
        ->whereNotIn('sa.idSegActividad',$actividadesCerradas)
        ->whereIn('sa.idSegActividad', function ($yaAsignados) {
          $sidu = Session::get('sesionidu');
          $yaAsignados->select('idSegActividad')->from('asignacionseguimientos')->where('idu','=',$sidu);
        })
        // ->where('sa.activo','=','Si')
        ->get();
        $cuantasExpiranAsignadas = count($expiranAsignadas);
        
        $seguimientoAsignadas = DB::table('detalleseguimientos AS det')            
        ->select('det.idSegActividad',DB::raw('max(det.fechaSiguiente)'))
        ->where('det.fechaSiguiente','>=',$semana[0])
        ->where('det.fechaSiguiente','<=',$semana[6])
        ->whereNotIn('det.idSegActividad',$actividadesCerradas)
        ->whereIn('det.idSegActividad', function ($yaAsignados) {
          $sidu = Session::get('sesionidu');
          $yaAsignados->select('idSegActividad')->from('asignacionseguimientos')->where('idu','=',$sidu);
        })
        // ->where('det.activo','=','Si')
        ->groupBy('det.idSegActividad')
        ->get();        
        $cuantasSeguimientoAsignadas = count($seguimientoAsignadas);

        if (count($seguimientoAsignadas) >= 1) {
          for ($i=0; $i < count($seguimientoAsignadas) ; $i++) {        
              $seguimientoAsignadas[$i]=$seguimientoAsignadas[$i]->idSegActividad;                                
          }
        }else{
          $seguimientoAsignadas=[0];
        }
        
      }else{        
        $actividadesPorcentajes= \DB::select("SELECT idSegActividad,AVG(porcentaje)AS porcentaje FROM (SELECT idSegActividad,MAX(porcentaje)AS porcentaje ,idu  FROM detalleseguimientos  WHERE idu =$sidu GROUP BY idSegActividad,idu) AS t1 GROUP BY idSegActividad");
        if (count($actividadesPorcentajes) >= 1) {
          for ($i=0; $i < count($actividadesPorcentajes) ; $i++) {
            if ($actividadesPorcentajes[$i]->porcentaje >= 100) {
              $actividadesCerradas[$i]=$actividadesPorcentajes[$i]->idSegActividad;                        
            }else{
              $actividadesCerradas[$i]=0;
            }      
          }
        }else{
          $actividadesCerradas=[0];
        }
        
        ///////////////////////////////// CREADAS /////////////////////////////////
        $cerradasCreadas = DB::table('seguimientoactividades AS sa')            
        ->select('sa.idSegActividad')
        ->whereIn('sa.idSegActividad',$actividadesCerradas)        
        ->where('sa.idu','=',$sidu)
        ->get();
        $cuantasCerradasCreadas = count($cerradasCreadas);
        
  
        $abiertasCreadas = DB::table('seguimientoactividades AS sa')            
        ->select('sa.idSegActividad')
        ->whereNotIn('sa.idSegActividad',$actividadesCerradas)        
        ->where('sa.idu','=',$sidu)
        ->get();
        $cuantasAbiertasCreadas = count($abiertasCreadas);
        
        $expiranCreadas = DB::table('seguimientoactividades AS sa')            
        ->select('sa.idSegActividad')
        ->where('sa.fechaTermino','>=',$semana[0])
        ->where('sa.fechaTermino','<=',$semana[6])
        ->whereNotIn('sa.idSegActividad',$actividadesCerradas)        
        ->where('sa.idu','=',$sidu)
        ->get();
        $cuantasExpiranCreadas = count($expiranCreadas);

        $seguimientoCreadas = DB::table('detalleseguimientos AS det')
        ->select('det.idSegActividad')
        ->where('det.fechaSiguiente','>=',$semana[0])
        ->where('det.fechaSiguiente','<=',$semana[6])
        ->whereNotIn('det.idSegActividad',$actividadesCerradas)
        // ->where('det.activo','=','Si')
        ->where('det.idu','=',$sidu)
        ->get();
        $cuantasSeguimientoCreadas = count($seguimientoCreadas);
  
        ///////////////////////////////// ASIGNADAS /////////////////////////////////
        $cerradasAsignadas = DB::table('seguimientoactividades AS sa')            
        ->select('sa.idSegActividad')
        ->whereIn('sa.idSegActividad',$actividadesCerradas)        
        ->whereIn('sa.idSegActividad', function ($yaAsignados) {
          $sidu = Session::get('sesionidu');
          $yaAsignados->select('idSegActividad')->from('asignacionseguimientos')->where('idu','=',$sidu);
        })
        // ->where('sa.activo','=','Si')
        ->get();
        $cuantasCerradasAsignadas = count($cerradasAsignadas);
  
        $abiertasAsignadas = DB::table('seguimientoactividades AS sa')            
        ->select('sa.idSegActividad')
        ->whereNotIn('sa.idSegActividad',$actividadesCerradas)
        ->whereIn('sa.idSegActividad', function ($yaAsignados) {
          $sidu = Session::get('sesionidu');
          $yaAsignados->select('idSegActividad')->from('asignacionseguimientos')->where('idu','=',$sidu);
        })
        // ->where('sa.activo','=','Si')
        ->get();
        $cuantasAbiertasAsignadas = count($abiertasAsignadas);
  
        $expiranAsignadas = DB::table('seguimientoactividades AS sa')            
        ->select('sa.idSegActividad')
        ->where('sa.fechaTermino','>=',$semana[0])
        ->where('sa.fechaTermino','<=',$semana[6])
        ->whereNotIn('sa.idSegActividad',$actividadesCerradas)
        ->whereIn('sa.idSegActividad', function ($yaAsignados) {
          $sidu = Session::get('sesionidu');
          $yaAsignados->select('idSegActividad')->from('asignacionseguimientos')->where('idu','=',$sidu);
        })
        // ->where('sa.activo','=','Si')
        ->get();
        $cuantasExpiranAsignadas = count($expiranAsignadas);

        $seguimientoAsignadas = DB::table('detalleseguimientos AS det')            
        ->select('det.idSegActividad',DB::raw('max(det.fechaSiguiente)'))
        ->where('det.fechaSiguiente','>=',$semana[0])
        ->where('det.fechaSiguiente','<=',$semana[6])
        ->whereNotIn('det.idSegActividad',$actividadesCerradas)
        ->whereIn('det.idSegActividad', function ($yaAsignados) {
          $sidu = Session::get('sesionidu');
          $yaAsignados->select('idSegActividad')->from('asignacionseguimientos')->where('idu','=',$sidu);
        })
        // ->where('det.activo','=','Si')
        ->groupBy('det.idSegActividad')
        ->get();        
        $cuantasSeguimientoAsignadas = count($seguimientoAsignadas);
        
        if (count($seguimientoAsignadas) >= 1) {
          for ($i=0; $i < count($seguimientoAsignadas) ; $i++) {        
              $seguimientoAsignadas[$i]=$seguimientoAsignadas[$i]->idSegActividad;                                
          }
        }else{
          $seguimientoAsignadas=[0];
        }
        
      }
      
      $cadenaDEids= $cerradasCreadas.",".$abiertasCreadas.",".$expiranCreadas.",".$seguimientoCreadas;
      $limpiando=str_replace('[],', "", $cadenaDEids);
      $limpiando=str_replace('{"idSegActividad":', "", $limpiando);
      $limpiando=str_replace('[', "", $limpiando);
      $limpiando=str_replace('}', "", $limpiando);
      $idParaConsultar=str_replace(']', "", $limpiando);  
      if($seguimientoAsignadas[0]==0){
        $seguimientoAsignadas="0";
      }

      return view ('reporteSeguimiento')
        ->with('cerradasCreadas',$cerradasCreadas)
        ->with('abiertasCreadas',$abiertasCreadas)
        ->with('expiranCreadas',$expiranCreadas)
        ->with('seguimientoCreadas',$seguimientoCreadas)
        ->with('cerradasAsignadas',$cerradasAsignadas)
        ->with('abiertasAsignadas',$abiertasAsignadas)
        ->with('expiranAsignadas',$expiranAsignadas)
        ->with('seguimientoAsignadas',$seguimientoAsignadas)
        ->with('cuantasCerradasCreadas',$cuantasCerradasCreadas)
        ->with('cuantasAbiertasCreadas',$cuantasAbiertasCreadas)
        ->with('cuantasExpiranCreadas',$cuantasExpiranCreadas)
        ->with('cuantasSeguimientoCreadas',$cuantasSeguimientoCreadas)
        ->with('cuantasCerradasAsignadas',$cuantasCerradasAsignadas)
        ->with('cuantasAbiertasAsignadas',$cuantasAbiertasAsignadas)
        ->with('cuantasExpiranAsignadas',$cuantasExpiranAsignadas)
        ->with('cuantasSeguimientoAsignadas',$cuantasSeguimientoAsignadas)
        ->with('stipo',$stipo)
        ->with('idParaConsultar',$idParaConsultar);
      
    }
    
  }
  
  public function anexgridSeguimiento(Request $request){
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
        if($f['columna'] == 'nombreActividad' && $f['valor'] != ''){
            $nombreActividad = $f['valor'];
          }
        if($f['columna'] == 'cliente' && $f['valor'] != ''){
            $cliente = $f['valor'];
          }
    }
    $order;

    $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';
  
    $actividadescerradas= \DB::select("SELECT AVG(porcentaje) FROM detalleseguimientos WHERE idu=$sidu");
  
    if($stipo=='Administrador'){
      $registros=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
                        t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
                        FROM
                        (SELECT sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,
                        CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,
                          IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                          seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
                        FROM seguimientoactividades AS sa
                        INNER JOIN clientes AS c ON c.idc = sa.idc
                        INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
                        INNER JOIN areas AS a ON a.idArea = sa.idArea
                        INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
                        LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
                        where t1.folio like '%$folio%' and t1.nombreUsuario like '%$nombreUsuario%'and t1.importanciaSeguimiento like '%$importanciaSeguimiento%'
                        and t1.nombreArea like '%$nombreArea%'and t1.cliente like '%$cliente%' and t1.nombreActividad like '%$nombreActividad%' 
                        GROUP BY t1.idSegActividad 
                        ORDER BY $anexGrid->columna $anexGrid->columna_orden
                        LIMIT $anexGrid->pagina, $anexGrid->limite");
  
    }else{
      $registros=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
                        t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
                        FROM
                        (SELECT sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,
                        CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,
                          IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                          seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
                        FROM seguimientoactividades AS sa
                        INNER JOIN clientes AS c ON c.idc = sa.idc
                        INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
                        INNER JOIN areas AS a ON a.idArea = sa.idArea
                        INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
                        LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad
                        where sa.idu=$sidu)AS t1
                        where t1.folio like '%$folio%' and t1.nombreUsuario like '%$nombreUsuario%'and t1.importanciaSeguimiento like '%$importanciaSeguimiento%'
                        and t1.nombreArea like '%$nombreArea%'and t1.cliente like '%$cliente%' and t1.nombreActividad like '%$nombreActividad%' 
                        
                        GROUP BY t1.idSegActividad 
                        ORDER BY $anexGrid->columna $anexGrid->columna_orden
                        LIMIT $anexGrid->pagina, $anexGrid->limite");

    }
    
    $total = count($registros);
  
    $data = array(
        'total' => $total,
        'data' => $registros
    );
  
    return response()->json($data, 200);  
  
  }
  
  public function reporteFiltrosVista(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');
  
    
    $tipoReporte = $request ->tipoReporte;
    $idSeguimientos = $request ->idSeguimientos;
    if ($idSeguimientos =="[]") {
      $idSeguimientos = "[0]";
    }
    
    $fechaInicio = $request -> fechaInicio;
    $fechaFin = $request -> fechaFin;
    if ($fechaInicio == ''){$fechaInicio="0000-00-00";}
    if ($fechaFin == ''){$fechaFin="9999-99-99";}
  
  
    if($sname == '' or $sidu =='' or $stipo==''){
        Session::flash('error', 'Es necesario logearse antes de continuar');
        return redirect()->route('login');
    }else{
        
      return view ('reporteSeguimientoConFiltro')
      ->with('fechaInicio',$fechaInicio)
      ->with('fechaFin',$fechaFin)
      ->with('idSeguimientos',$idSeguimientos)
      ->with('tipoReporte',$tipoReporte);
    }              
  }
    
  public function anexgridSeguimientoConFiltro(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');
    $anexGrid = new anexGrid();
    
    $idSeguimientos = $request ->idSeguimientos;
    $limpiando=str_replace('{"idSegActividad":', "", $idSeguimientos);
    $limpiando=str_replace('[', "", $limpiando);
    $limpiando=str_replace('}', "", $limpiando);
    $idParaConsultar=str_replace(']', "", $limpiando);  
    // $idParaConsultar= explode(',',$limpiando);  
  
    $fechaInicio = $request -> fechaInicio;
    $fechaFin = $request -> fechaFin;
    if ($fechaInicio == ''){$fechaInicio="0000-00-00";}
    if ($fechaFin == ''){$fechaFin="9999-99-99";}
  
    $folio='';
    $nombreUsuario='';
    $importanciaSeguimiento='';
    $nombreArea='';
    $cliente='';
    $nombreActividad='';
  
    foreach($anexGrid->filtros as $f){
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
      if($f['columna'] == 'nombreActividad' && $f['valor'] != ''){
          $nombreActividad = $f['valor'];
        }
      if($f['columna'] == 'cliente' && $f['valor'] != ''){
          $cliente = $f['valor'];
        }
    }
  
    $order;
    $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';
    
    $registros=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.fechaInicio,
      t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
      t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,TRUNCATE (AVG(t1.porcentajePersonal),0) AS porcentajePersonal,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor,DATE_FORMAT(t1.fechasig, '%d %b %y') AS fechasig
      FROM
      (SELECT sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.fechaInicio,
      CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,
        IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,IF(ultimafechasiguiente(sa.idSegActividad,$sidu) IS NULL,' ',ultimafechasiguiente(sa.idSegActividad,$sidu))AS fechasig,
        IF(ultimoporcentaje(sa.idSegActividad,$sidu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,$sidu)) AS porcentajePersonal,
        seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
      FROM seguimientoactividades AS sa
      INNER JOIN clientes AS c ON c.idc = sa.idc
      INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
      INNER JOIN areas AS a ON a.idArea = sa.idArea
      INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
      LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad WHERE sa.idSegActividad IN ($idParaConsultar) AND (fechaInicio >=? AND fechaInicio <=?))AS t1
      where t1.folio like '%$folio%' and t1.nombreUsuario like '%$nombreUsuario%'and t1.importanciaSeguimiento like '%$importanciaSeguimiento%'
      and t1.nombreArea like '%$nombreArea%'and t1.cliente like '%$cliente%' and t1.nombreActividad like '%$nombreActividad%' 
      GROUP BY t1.idSegActividad 
      ORDER BY $anexGrid->columna $anexGrid->columna_orden
      LIMIT $anexGrid->pagina, $anexGrid->limite",[$fechaInicio,$fechaFin]);
        
    $total = count($registros);
  
    $data = array(
        'total' => $total,
        'data' => $registros
    );
  
    return response()->json($data, 200);  
  
  }


  public function agregarEquiposActividad(Request $request){
    $idEquipos=$request->idEquipos;
    $idFactura=$request->idFactura;
    $idContactoSuc=$request->idContactoSuc;
    $idCotizacion=$request->idCotizacion;
    $idSegActividad=$request->idSegActividad;

    // $consultaEquipos=\DB::select("SELECT * FROM recepcionEquipos WHERE idEquipos=$idEquipos");
    $botonDetalle="activo";
    

      if($idEquipos!=''){
        $existenEquipos = \DB::select("SELECT idEquipos FROM equiposEnActividad WHERE idEquipos=$idEquipos AND idSegActividad=$idSegActividad");
        $cuantosExis=count($existenEquipos);

        if($cuantosExis!=1){
          $equipos = new equiposEnActividad;
          $equipos->idEquipos=$request->idEquipos;
          $equipos->idSegActividad=$request->idSegActividad;
          $equipos->idFactura=$request->idFactura;
          $equipos->idContactoSuc=$request->idContactoSuc;
          $equipos->save();     
            $consultaEquipos = \DB::select("SELECT  ea.idSegActividad, ea.idEquipoActividad,e.idEquipos,e.folioRecepcion,e.vistaSuperior,e.vistaFrente,e.textoQr,e.placa_1,e.estatus,
                  e.importancia,SUBSTR(datosCertificado,1,100) as datos,
                  COUNT(partesreparacion.`idParte`) AS canti,
                  CONCAT (e.GCMid, '-', e.complementoGCMid)AS gcmid,
                  CONCAT(c.razonSocial,'-',s.sucursal) AS cliSuc,
                  DATE_FORMAT(e.fechaRecoleccion,'%d %b %Y') AS fecha,
                  DATE_FORMAT(e.fechaRegistro,'%d %b %Y') AS fechar,e.personaEntrega,
                  CONCAT (e.serie,'-',te.tipoEquipo,'-',se.subtipoEquipo) AS equipo,
                  CONCAT (e.marca,'-',e.modelo) AS marcaModelo, e.activo
                  FROM recepcionEquipos AS e
                  INNER JOIN clientes AS c ON c.idc= e.idc
                  INNER JOIN sucursales AS s ON s.idSucursal= e.idSucursal
                  INNER JOIN tipoequipos AS te ON te.idTipoEquipo= e.idTipoEquipo
                  INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= e.idSubtipoEquipo
                  INNER JOIN equiposEnActividad AS ea ON ea.idEquipos = e.idEquipos
                  LEFT JOIN partesreparacion ON partesreparacion.`idEquipos`= e.`idEquipos`
                  WHERE ea.idSegActividad=$idSegActividad
                  GROUP BY  ea.idSegActividad, ea.idEquipoActividad,e.idEquipos, e.folioRecepcion, e.vistaSuperior,e.vistaFrente,e.placa_1, e.idu,e.textoQr,e.estatus,e.importancia,datos,gcmid,
                  cliSuc,e.personaEntrega,equipo,marcaModelo,fecha, e.activo
                  ORDER BY e.folioRecepcion DESC");
            $cuantos = count($consultaEquipos);
            return view('reporteEquiposActividad')
            ->with('cuantosExis',$cuantosExis)
            ->with('botonDetalle',$botonDetalle)
            ->with('consultaEquipos',$consultaEquipos);
            // return $existenEquipos;

        }else{
          // $cuantosExis=0;
          $consultaEquipos = \DB::select("SELECT  ea.idSegActividad, ea.idEquipoActividad,e.idEquipos,e.folioRecepcion,e.vistaSuperior,e.vistaFrente,e.textoQr,e.placa_1,e.estatus,
                e.importancia,SUBSTR(datosCertificado,1,100) as datos,
                COUNT(partesreparacion.`idParte`) AS canti,
                CONCAT (e.GCMid, '-', e.complementoGCMid)AS gcmid,
                CONCAT(c.razonSocial,'-',s.sucursal) AS cliSuc,
                DATE_FORMAT(e.fechaRecoleccion,'%d %b %Y') AS fecha,
                DATE_FORMAT(e.fechaRegistro,'%d %b %Y') AS fechar,e.personaEntrega,
                CONCAT (e.serie,'-',te.tipoEquipo,'-',se.subtipoEquipo) AS equipo,
                CONCAT (e.marca,'-',e.modelo) AS marcaModelo, e.activo
                FROM recepcionEquipos AS e
                INNER JOIN clientes AS c ON c.idc= e.idc
                INNER JOIN sucursales AS s ON s.idSucursal= e.idSucursal
                INNER JOIN tipoequipos AS te ON te.idTipoEquipo= e.idTipoEquipo
                INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= e.idSubtipoEquipo
                INNER JOIN equiposEnActividad AS ea ON ea.idEquipos = e.idEquipos
                LEFT JOIN partesreparacion ON partesreparacion.`idEquipos`= e.`idEquipos`
                WHERE ea.idSegActividad=$idSegActividad
                GROUP BY  ea.idSegActividad, ea.idEquipoActividad,e.idEquipos, e.folioRecepcion, e.vistaSuperior,e.vistaFrente,e.placa_1, e.idu,e.textoQr,e.estatus,e.importancia,datos,gcmid,
                cliSuc,e.personaEntrega,equipo,marcaModelo,fecha, e.activo
                ORDER BY e.folioRecepcion DESC");
          $cuantos = count($consultaEquipos);
          return view('reporteEquiposActividad')
          ->with('cuantosExis',$cuantosExis)
          ->with('botonDetalle',$botonDetalle)
          ->with('consultaEquipos',$consultaEquipos);
          // return $existenEquipos;
        }

        
      }
      if($idFactura!=''){
        $existenFacturas = \DB::select("SELECT idEquipos FROM equiposEnActividad WHERE idFactura=$idFactura AND idSegActividad=$idSegActividad");
        $cuantosFac=count($existenFacturas);
        if($cuantosFac!=1){
          $equipos = new equiposEnActividad;
          $equipos->idEquipos=$request->idEquipos;
          $equipos->idSegActividad=$request->idSegActividad;
          $equipos->idFactura=$request->idFactura;
          $equipos->idContactoSuc=$request->idContactoSuc;
          $equipos->save();     

          $consultaFacturas = \DB::select("SELECT ea.idSegActividad, ea.idEquipoActividad, v.idServicios,v.estatusEntrega,
                  v.estatusPago, v.estatusPortal, v.cashFlow,
                  v.archivoRemision, v.archivo,v.archivoFactura,v.archivoPago,
                  v.xmlFactura, v.numeroFactura,v.idFactura,v.activo,v.fechaFactura,
                  DATE_FORMAT(v.fechaFactura,'%d %b %Y') AS fecha, 
                  DATE_FORMAT(v.fechaPago,'%d %b %Y') AS fechap, v.montoFactura,
                  v.tipoMoneda,v.archivoAdenda,s.sucursal AS suc, c.razonSocial AS cli 
                  FROM facturas AS v
                  LEFT JOIN sucursales AS s ON s.idSucursal = v.idSucursal
                  LEFT JOIN clientes AS c ON c.idc = v.idc
                  INNER JOIN equiposEnActividad AS ea ON ea.idFactura = v.idFactura
                  WHERE ea.idSegActividad=$idSegActividad order by v.idServicios DESC");
          $cuantosServicios=count($consultaFacturas);
            
          return view('reporteServiciosActividad')
          ->with('cuantosFac',$cuantosFac)
          ->with('botonDetalle',$botonDetalle)
          ->with('consultaFacturas',$consultaFacturas);
          // return $idFactura;
        }else{
          $consultaFacturas = \DB::select("SELECT ea.idSegActividad, ea.idEquipoActividad, v.idServicios,v.estatusEntrega,
                v.estatusPago, v.estatusPortal, v.cashFlow,
                v.archivoRemision, v.archivo,v.archivoFactura,v.archivoPago,
                v.xmlFactura, v.numeroFactura,v.idFactura,v.activo,v.fechaFactura,
                DATE_FORMAT(v.fechaFactura,'%d %b %Y') AS fecha, 
                DATE_FORMAT(v.fechaPago,'%d %b %Y') AS fechap, v.montoFactura,
                v.tipoMoneda,v.archivoAdenda,s.sucursal AS suc, c.razonSocial AS cli 
                FROM facturas AS v
                LEFT JOIN sucursales AS s ON s.idSucursal = v.idSucursal
                LEFT JOIN clientes AS c ON c.idc = v.idc
                INNER JOIN equiposEnActividad AS ea ON ea.idFactura = v.idFactura
                WHERE ea.idSegActividad=$idSegActividad order by v.idServicios DESC");
          $cuantosServicios=count($consultaFacturas);
            
          return view('reporteServiciosActividad')
          ->with('cuantosFac',$cuantosFac)
          ->with('botonDetalle',$botonDetalle)
          ->with('consultaFacturas',$consultaFacturas);
          // return $idFactura;
        }
      }
      if($idContactoSuc!=''){
        $existenContact = \DB::select("SELECT idEquipos FROM equiposEnActividad WHERE idContactoSuc=$idContactoSuc AND idSegActividad=$idSegActividad");
        $cuantosContac=count($existenContact);
        if($cuantosContac!=1){
          $equipos = new equiposEnActividad;
          $equipos->idEquipos=$request->idEquipos;
          $equipos->idSegActividad=$request->idSegActividad;
          $equipos->idFactura=$request->idFactura;
          $equipos->idContactoSuc=$request->idContactoSuc;
          $equipos->save(); 

          $consultaContactos=\DB::select("SELECT ea.idSegActividad, ea.idEquipoActividad, cs.idContactoSuc, puesto, nombreCont, telefonoCont, correoCont, extension, whatsapp, contactoAdicional
          FROM contactosucursales AS cs
          INNER JOIN equiposEnActividad AS ea ON ea.idContactoSuc = cs.idContactoSuc
          WHERE ea.idSegActividad=$idSegActividad ORDER BY cs.idContactoSuc DESC");
          $cuantosContactos=count($consultaContactos);

          return view('reporteContactosActividad')
          ->with('cuantosContac',$cuantosContac)
          ->with('botonDetalle',$botonDetalle)
          ->with('consultaContactos',$consultaContactos);

        }else{
          $consultaContactos=\DB::select("SELECT ea.idSegActividad, ea.idEquipoActividad, cs.idContactoSuc, puesto, nombreCont, telefonoCont, correoCont, extension, whatsapp, contactoAdicional
          FROM contactosucursales AS cs
          INNER JOIN equiposEnActividad AS ea ON ea.idContactoSuc = cs.idContactoSuc
          WHERE ea.idSegActividad=$idSegActividad ORDER BY cs.idContactoSuc DESC");
          $cuantosContactos=count($consultaContactos);

          return view('reporteContactosActividad')
          ->with('cuantosContac',$cuantosContac)
          ->with('botonDetalle',$botonDetalle)
          ->with('consultaContactos',$consultaContactos);
        }
        
      }
      if($idCotizacion!=''){
        $existenCoti = \DB::select("SELECT idEquipos FROM equiposEnActividad WHERE idCotizacion=$idCotizacion AND idSegActividad=$idSegActividad");
        $cuantosCoti=count($existenCoti);
        if($cuantosCoti!=1){
          $equipos = new equiposEnActividad;
          $equipos->idEquipos=$request->idEquipos;
          $equipos->idSegActividad=$request->idSegActividad;
          $equipos->idFactura=$request->idFactura;
          $equipos->idContactoSuc=$request->idContactoSuc;
          $equipos->idCotizacion=$request->idCotizacion;
          $equipos->save(); 

          $consultaCotizacion = \DB::select("SELECT ea.idSegActividad, ea.idEquipoActividad,co.idCotizacion, co.numeroCotizacion, co.fechaCotizacion,co.clonActual,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
          co.noEquipos,CONCAT('$',' ',co.tipoMoneda,' ',co.montoTotal) AS montoMoneda,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus
          FROM cotizaciones AS co
          INNER JOIN usuarios AS u ON u.idu= co.idu
          INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
          INNER JOIN clientes AS c ON c.idc= co.idc
          INNER JOIN equiposEnActividad AS ea ON ea.idCotizacion = co.idCotizacion
          WHERE ea.idSegActividad=$idSegActividad
          ORDER BY numeroCotizacion");
          $cuantasCotizacion=count($consultaCotizacion);

          return view('reporteCotizacionActividad')
          ->with('cuantosCoti',$cuantosCoti)
          ->with('botonDetalle',$botonDetalle)
          ->with('consultaCotizacion',$consultaCotizacion);

        }else{
          $consultaCotizacion = \DB::select("SELECT ea.idSegActividad, ea.idEquipoActividad, co.idCotizacion, co.numeroCotizacion, co.fechaCotizacion,co.clonActual,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
          co.noEquipos,CONCAT('$',' ',co.tipoMoneda,' ',co.montoTotal) AS montoMoneda,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus
          FROM cotizaciones AS co
          INNER JOIN usuarios AS u ON u.idu= co.idu
          INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
          INNER JOIN clientes AS c ON c.idc= co.idc
          INNER JOIN equiposEnActividad AS ea ON ea.idCotizacion = co.idCotizacion
          WHERE ea.idSegActividad=$idSegActividad
          ORDER BY numeroCotizacion");
          $cuantasCotizacion=count($consultaCotizacion);

          return view('reporteCotizacionActividad')
          ->with('cuantosCoti',$cuantosCoti)
          ->with('botonDetalle',$botonDetalle)
          ->with('consultaCotizacion',$consultaCotizacion);
        }
        
      }
  }


  public function borrarEquipoActividad(Request $request){
    $idEquipos=$request->idEquipoBorrar;
    $idFactura=$request->idFacturaBorrar;
    $idContactoSuc=$request->idContactoBorrar;
    $idCotizacion=$request->idCotizacionBorrar;

    $idEquipoActividad=$request->idEquipoActBorrar;
    $idSegActividad=$request->idSegBorrar;

    $borro= \DB::delete("DELETE FROM equiposEnActividad WHERE idEquipoActividad=?",[$idEquipoActividad]);
    $botonDetalle="activo";

    if($idEquipos!=''){
      $cuantosExis=0;
      $consultaEquipos = \DB::select("SELECT  ea.idSegActividad, ea.idEquipoActividad,e.idEquipos,e.folioRecepcion,e.vistaSuperior,e.vistaFrente,e.textoQr,e.placa_1,e.estatus,
            e.importancia,SUBSTR(datosCertificado,1,100) as datos,
            COUNT(partesreparacion.`idParte`) AS canti,
            CONCAT (e.GCMid, '-', e.complementoGCMid)AS gcmid,
            CONCAT(c.razonSocial,'-',s.sucursal) AS cliSuc,
            DATE_FORMAT(e.fechaRecoleccion,'%d %b %Y') AS fecha,
            DATE_FORMAT(e.fechaRegistro,'%d %b %Y') AS fechar,e.personaEntrega,
            CONCAT (e.serie,'-',te.tipoEquipo,'-',se.subtipoEquipo) AS equipo,
            CONCAT (e.marca,'-',e.modelo) AS marcaModelo, e.activo
            FROM recepcionEquipos AS e
            INNER JOIN clientes AS c ON c.idc= e.idc
            INNER JOIN sucursales AS s ON s.idSucursal= e.idSucursal
            INNER JOIN tipoequipos AS te ON te.idTipoEquipo= e.idTipoEquipo
            INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= e.idSubtipoEquipo
            INNER JOIN equiposEnActividad AS ea ON ea.idEquipos = e.idEquipos
            LEFT JOIN partesreparacion ON partesreparacion.`idEquipos`= e.`idEquipos`
            WHERE ea.idSegActividad=$idSegActividad
            GROUP BY  ea.idSegActividad, ea.idEquipoActividad,e.idEquipos, e.folioRecepcion, e.vistaSuperior,e.vistaFrente,e.placa_1, e.idu,e.textoQr,e.estatus,e.importancia,datos,gcmid,
            cliSuc,e.personaEntrega,equipo,marcaModelo,fecha, e.activo
            ORDER BY e.folioRecepcion DESC");
      $cuantos = count($consultaEquipos);
      return view('reporteEquiposActividad')
      ->with('botonDetalle',$botonDetalle)
      ->with('cuantosExis',$cuantosExis)
      ->with('consultaEquipos',$consultaEquipos);
      // return $idEquipos;
    }
    if($idFactura!=''){
      $cuantosFac=0;
      $consultaFacturas = \DB::select("SELECT ea.idSegActividad, ea.idEquipoActividad, v.idServicios,v.estatusEntrega,
              v.estatusPago, v.estatusPortal, v.cashFlow,
              v.archivoRemision, v.archivo,v.archivoFactura,v.archivoPago,
              v.xmlFactura, v.numeroFactura,v.idFactura,v.activo,v.fechaFactura,
              DATE_FORMAT(v.fechaFactura,'%d %b %Y') AS fecha, 
              DATE_FORMAT(v.fechaPago,'%d %b %Y') AS fechap, v.montoFactura,
              v.tipoMoneda,v.archivoAdenda,s.sucursal AS suc, c.razonSocial AS cli 
              FROM facturas AS v
              LEFT JOIN sucursales AS s ON s.idSucursal = v.idSucursal
              LEFT JOIN clientes AS c ON c.idc = v.idc
              INNER JOIN equiposEnActividad AS ea ON ea.idFactura = v.idFactura
              WHERE ea.idSegActividad=$idSegActividad order by v.idServicios DESC");
      $cuantosServicios=count($consultaFacturas);
        
      return view('reporteServiciosActividad')
      ->with('botonDetalle',$botonDetalle)
      ->with('cuantosFac',$cuantosFac)
      ->with('consultaFacturas',$consultaFacturas);
      // return $idFactura;
    }
    if($idContactoSuc!=''){
      $cuantosContac=0;
      $consultaContactos=\DB::select("SELECT ea.idSegActividad, ea.idEquipoActividad, cs.idContactoSuc, puesto, nombreCont, telefonoCont, correoCont, extension, whatsapp, contactoAdicional
                  FROM contactosucursales AS cs
                  INNER JOIN equiposEnActividad AS ea ON ea.idContactoSuc = cs.idContactoSuc
                  WHERE ea.idSegActividad=$idSegActividad ORDER BY cs.idContactoSuc DESC");
            $cuantosContactos=count($consultaContactos);
      return view('reporteContactosActividad')
      ->with('botonDetalle',$botonDetalle)
      ->with('cuantosContac',$cuantosContac)
      ->with('consultaContactos',$consultaContactos);
      // return $idContactoSuc;
    }
    if($idCotizacion!=''){
      $cuantosCoti=0;
      $consultaCotizacion = \DB::select("SELECT ea.idSegActividad, ea.idEquipoActividad, co.idCotizacion, co.numeroCotizacion, co.fechaCotizacion,co.clonActual,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
          co.noEquipos,CONCAT('$',' ',co.tipoMoneda,' ',co.montoTotal) AS montoMoneda,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus
          FROM cotizaciones AS co
          INNER JOIN usuarios AS u ON u.idu= co.idu
          INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
          INNER JOIN clientes AS c ON c.idc= co.idc
          INNER JOIN equiposEnActividad AS ea ON ea.idCotizacion = co.idCotizacion
          WHERE ea.idSegActividad=$idSegActividad
          ORDER BY numeroCotizacion");
          $cuantasCotizacion=count($consultaCotizacion);

          return view('reporteCotizacionActividad')
          ->with('cuantosCoti',$cuantosCoti)
          ->with('botonDetalle',$botonDetalle)
          ->with('consultaCotizacion',$consultaCotizacion);
    }


  }

  public function reporteActividadesSeg(){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');
    
    $cliente = \DB::select("SELECT * FROM clientes WHERE tipoCliente='activo' ORDER BY razonSocial ASC");

    if($stipo=='Administrador'){
      $consulta = \DB::select("SELECT t1.idArea,t1.nombreArea,TRUNCATE (AVG(t1.porcentaje),0)as porcentaje, 
          actabiertas(t1.idArea, $sidu, '$stipo') AS abiertas,
          actcerradas(t1.idArea, $sidu, '$stipo') AS cerradas,
          actcanceladas(t1.idArea, $sidu, '$stipo') AS canceladas,
          COUNT(*) AS total FROM
          (SELECT sa.idSegActividad,sa.idArea,IF(porc(sa.idArea, sa.idSegActividad)IS NULL,0,porc(sa.idArea, sa.idSegActividad)) AS porcentaje,
          a.nombreArea
          FROM seguimientoactividades AS sa
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          WHERE sa.activo ='Si') AS t1
          GROUP BY t1.idArea");
    }else{
      $consulta = \DB::select("SELECT t1.idArea,t1.nombreArea,TRUNCATE (AVG(t1.porcentaje),0)as porcentaje, 
          actabiertas(t1.idArea, $sidu, '$stipo') AS abiertas,
          actcerradas(t1.idArea, $sidu, '$stipo') AS cerradas,
          actcanceladas(t1.idArea, $sidu, '$stipo') AS canceladas,
          COUNT(*) AS total FROM
          (SELECT sa.idSegActividad,sa.idArea,IF(porc(sa.idArea, sa.idSegActividad)IS NULL,0,porc(sa.idArea, sa.idSegActividad)) AS porcentaje,
          a.nombreArea
          FROM seguimientoactividades AS sa
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          WHERE sa.activo ='Si'
          AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
          OR sa.idu = $sidu)) AS t1
          GROUP BY t1.idArea");

    }
    // $consulta=\DB::select("SELECT sa.idArea,a.nombreArea,
    //                       actabiertas(sa.idArea) AS abiertas, 
    //                       actcerradas(sa.idArea) AS cerradas, 
    //                       actcanceladas(sa.idArea) AS canceladas,
    //                       COUNT(*) AS total
    //                       FROM seguimientoactividades AS sa
    //                       INNER JOIN areas AS a ON a.idArea = sa.idArea
    //                       Where sa.activo='Si'
    //                       GROUP BY sa.idArea");
                          
    return view ('reporteActividadesSeg')
    ->with('cliente',$cliente)
    ->with('consulta',$consulta);
  }

  public function reporteActividadesSegAbajo(Request $request ){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    $filtroPor = $request->filtroPor;
    $areaOEmpresa = $request->areaOEmpresa;
    $fechaInicio = $request->fechaInicio;
    $fechaFin = $request->fechaFin;
    $empresa = $request->empresa;

    if($stipo=='Administrador'){
      if($areaOEmpresa=='Empresa'){
      
        $idArea=0;
        $idActividad=0;
        $idCliente = implode(', ',(array)$empresa);
        if($idCliente=="todos"){
  
          if($filtroPor == 'fechaInicio'){
              $consultaActividad=\DB::select("SELECT sa.idArea,c.razonSocial,s.sucursal,sa.idc,
              empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
              empresaCerradas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
              empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
              COUNT(*) AS total
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
              WHERE sa.activo='Si' AND sa.fechaInicio>=? AND sa.fechaInicio<=?
              GROUP BY sa.idArea, sa.idc, s.sucursal",[$fechaInicio, $fechaFin]);
          }else{
              $consultaActividad=\DB::select("SELECT sa.idArea,c.razonSocial,s.sucursal,sa.idc,
              empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
              empresaCerradas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
              empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
              COUNT(*) AS total
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
              WHERE sa.activo='Si' AND sa.fechaTermino>=? AND sa.fechaTermino<=?
              GROUP BY sa.idArea, sa.idc, s.sucursal",[$fechaInicio, $fechaFin]);
          }
        }else{
          $idCliente = implode(', ',$empresa);
  
          if($filtroPor == 'fechaInicio'){
              $consultaActividad=\DB::select("SELECT sa.idArea,c.razonSocial,s.sucursal,sa.idc,
              empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
              empresaCerradas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
              empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
              COUNT(*) AS total
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
              WHERE sa.idc IN ($idCliente) and sa.activo='Si' AND sa.fechaInicio>=? AND sa.fechaInicio<=?
              GROUP BY sa.idArea, sa.idc, s.sucursal",[$fechaInicio, $fechaFin]);
          }else{
              $consultaActividad=\DB::select("SELECT sa.idArea,c.razonSocial,s.sucursal,sa.idc,
              empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
              empresaCerradas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
              empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas,  
              COUNT(*) AS total
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
              WHERE sa.idc IN ($idCliente) and sa.activo='Si' AND sa.fechaTermino>=? AND sa.fechaTermino<=?
              GROUP BY sa.idArea, sa.idc, s.sucursal",[$fechaInicio, $fechaFin]);
          }
        }
          
        return view("reporteEmpresaSeg")
        ->with('consultaActividad',$consultaActividad)
        ->with('fechaInicio',$fechaInicio)
        ->with('fechaFin',$fechaFin)
        ->with('idArea',$idArea)
        ->with('idActividad',$idActividad)
        ->with('areaOEmpresa',$areaOEmpresa)
        ->with('filtroPor',$filtroPor);
  
      }else{ //else de $areaOEmpresa
        if($filtroPor == 'fechaInicio'){
        
          $consultaActividad=\DB::select("SELECT sa.idArea,a.nombreArea,
          abiertasAbajo(sa.idArea,'$filtroPor', '$fechaInicio', '$fechaFin',  $sidu, '$stipo') AS abiertas, 
          cerradasAbajo(sa.idArea, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
          canceladasAbajo(sa.idArea,'$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas,  
          COUNT(*) AS total
          FROM seguimientoactividades AS sa
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          WHERE sa.activo='Si' and sa.fechaInicio>=? AND sa.fechaInicio<=?
          GROUP BY sa.idArea",[$fechaInicio, $fechaFin]);
    
        }else{
     
          $consultaActividad=\DB::select("SELECT sa.idArea,a.nombreArea,
          abiertasAbajo(sa.idArea,'$filtroPor', '$fechaInicio', '$fechaFin',  $sidu, '$stipo') AS abiertas, 
          cerradasAbajo(sa.idArea, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
          canceladasAbajo(sa.idArea,'$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas,  
          COUNT(*) AS total
          FROM seguimientoactividades AS sa
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          WHERE sa.activo='Si' AND sa.fechaTermino>=? AND sa.fechaTermino<=?
          GROUP BY sa.idArea",[$fechaInicio, $fechaFin]);
    
        }
        return view ('reporteActividadesSegAbajo')
        ->with('consultaActividad',$consultaActividad)
        ->with('fechaInicio',$fechaInicio)
        ->with('fechaFin',$fechaFin)
        ->with('areaOEmpresa',$areaOEmpresa)
        ->with('filtroPor',$filtroPor);
      }
    }else{ //else de $stipo
      if($areaOEmpresa=='Empresa'){
      
        $idArea=0;
        $idActividad=0;
        $idCliente = implode(', ',(array)$empresa);
        if($idCliente=="todos"){
  
          if($filtroPor == 'fechaInicio'){
              $consultaActividad=\DB::select("SELECT sa.idArea,c.razonSocial,s.sucursal,sa.idc,
              empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
              empresaCerradas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
              empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
              COUNT(*) AS total
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
              WHERE sa.activo='Si' AND sa.fechaInicio>=? AND sa.fechaInicio<=?
              AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
              OR sa.idu = $sidu)
              GROUP BY sa.idArea, sa.idc, s.sucursal",[$fechaInicio, $fechaFin]);
          }else{
              $consultaActividad=\DB::select("SELECT sa.idArea,c.razonSocial,s.sucursal,sa.idc,
              empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
              empresaCerradas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
              empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
              COUNT(*) AS total
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
              WHERE sa.activo='Si' AND sa.fechaTermino>=? AND sa.fechaTermino<=?
              AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
              OR sa.idu = $sidu)
              GROUP BY sa.idArea, sa.idc, s.sucursal",[$fechaInicio, $fechaFin]);
          }
        }else{
          $idCliente = implode(', ',$empresa);
  
          if($filtroPor == 'fechaInicio'){
              $consultaActividad=\DB::select("SELECT sa.idArea,c.razonSocial,s.sucursal,sa.idc,
              empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
              empresaCerradas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
              empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
              COUNT(*) AS total
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
              WHERE sa.idc IN ($idCliente) and sa.activo='Si' AND sa.fechaInicio>=? AND sa.fechaInicio<=?
              GROUP BY sa.idArea, sa.idc, s.sucursal",[$fechaInicio, $fechaFin]);
          }else{
              $consultaActividad=\DB::select("SELECT sa.idArea,c.razonSocial,s.sucursal,sa.idc,
              empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
              empresaCerradas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
              empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
              COUNT(*) AS total
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
              WHERE sa.idc IN ($idCliente) and sa.activo='Si' AND sa.fechaTermino>=? AND sa.fechaTermino<=?
              GROUP BY sa.idArea, sa.idc, s.sucursal",[$fechaInicio, $fechaFin]);
          }
        }
          
        return view("reporteEmpresaSeg")
        ->with('consultaActividad',$consultaActividad)
        ->with('fechaInicio',$fechaInicio)
        ->with('fechaFin',$fechaFin)
        ->with('idArea',$idArea)
        ->with('idActividad',$idActividad)
        ->with('areaOEmpresa',$areaOEmpresa)
        ->with('filtroPor',$filtroPor);
  
      }else{
        if($filtroPor == 'fechaInicio'){ 
          $consultaActividad=\DB::select("SELECT sa.idArea,a.nombreArea,
          abiertasAbajo(sa.idArea,'$filtroPor', '$fechaInicio', '$fechaFin',  $sidu, '$stipo') AS abiertas, 
          cerradasAbajo(sa.idArea, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
          canceladasAbajo(sa.idArea,'$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
          COUNT(*) AS total
          FROM seguimientoactividades AS sa
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          WHERE sa.activo='Si' and sa.fechaInicio>=? AND sa.fechaInicio<=?
          AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
          OR sa.idu = $sidu)
          GROUP BY sa.idArea",[$fechaInicio, $fechaFin]);
    
        }else{
          $consultaActividad=\DB::select("SELECT sa.idArea,a.nombreArea,
          abiertasAbajo(sa.idArea,'$filtroPor', '$fechaInicio', '$fechaFin',  $sidu, '$stipo') AS abiertas, 
          cerradasAbajo(sa.idArea, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
          canceladasAbajo(sa.idArea,'$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
          COUNT(*) AS total
          FROM seguimientoactividades AS sa
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          WHERE sa.activo='Si' AND sa.fechaTermino>=? AND sa.fechaTermino<=?
          AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
          OR sa.idu = $sidu)
          GROUP BY sa.idArea",[$fechaInicio, $fechaFin]);
    
        }
        return view ('reporteActividadesSegAbajo')
        ->with('consultaActividad',$consultaActividad)
        ->with('fechaInicio',$fechaInicio)
        ->with('fechaFin',$fechaFin)
        ->with('areaOEmpresa',$areaOEmpresa)
        ->with('filtroPor',$filtroPor);
      }
    }
  
    
  }

  public function reporteTipoSeg(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    $idArea=$request->idArea;
    $filtroPor = $request->filtroPor;
    $fechaInicio = $request->fechaInicio;
    $fechaFin = $request->fechaFin;


    if($stipo=='Administrador'){
      if($filtroPor==''){
        $fechaInicio = '1900-01-01';
        $fechaFin = '1900-01-01';
        $consultaActividad=\DB::select("SELECT act.nombreActividad, sa.idArea,sa.idActividad,a.nombreArea,
        tipoactabiertas(sa.idActividad, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
        tipoactcerradas(sa.idActividad, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
        tipoactcanceladas(sa.idActividad, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
        COUNT(*) AS total
        FROM seguimientoactividades AS sa
        INNER JOIN areas AS a ON a.idArea = sa.idArea
        INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
        WHERE sa.idArea = $idArea AND sa.activo='Si'
        GROUP BY sa.idActividad
        ORDER BY act.nombreActividad ASC");
      }else{
        if($filtroPor=='fechaInicio'){
          $consultaActividad=\DB::select("SELECT act.nombreActividad, sa.idArea,sa.idActividad,a.nombreArea,
          tipoactabiertas(sa.idActividad, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
          tipoactcerradas(sa.idActividad, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
          tipoactcanceladas(sa.idActividad, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
          COUNT(*) AS total
          FROM seguimientoactividades AS sa
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
          WHERE sa.idArea = $idArea AND sa.activo='Si'AND sa.fechaInicio>=? AND sa.fechaInicio<=?
          GROUP BY sa.idActividad
          ORDER BY act.nombreActividad ASC",[$fechaInicio, $fechaFin]);
        }else{
          $consultaActividad=\DB::select("SELECT act.nombreActividad, sa.idArea,sa.idActividad,a.nombreArea,
          tipoactabiertas(sa.idActividad, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
          tipoactcerradas(sa.idActividad, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
          tipoactcanceladas(sa.idActividad, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
          COUNT(*) AS total
          FROM seguimientoactividades AS sa
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
          WHERE sa.idArea = $idArea AND sa.activo='Si'AND sa.fechaTermino>=? AND sa.fechaTermino<=?
          GROUP BY sa.idActividad
          ORDER BY act.nombreActividad ASC",[$fechaInicio, $fechaFin]);
        }
      }
    }else{ //else de stipo
      if($filtroPor==''){
        $fechaInicio = '1900-01-01';
        $fechaFin = '1900-01-01';
        $consultaActividad=\DB::select("SELECT act.nombreActividad, sa.idArea,sa.idActividad,a.nombreArea,
            tipoactabiertas(sa.idActividad, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            tipoactcerradas(sa.idActividad, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            tipoactcanceladas(sa.idActividad, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            WHERE sa.idArea = $idArea AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            GROUP BY sa.idActividad
            ORDER BY act.nombreActividad ASC");
      }else{
        if($filtroPor=='fechaInicio'){
          $consultaActividad=\DB::select("SELECT act.nombreActividad, sa.idArea,sa.idActividad,a.nombreArea,
          tipoactabiertas(sa.idActividad, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            tipoactcerradas(sa.idActividad, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            tipoactcanceladas(sa.idActividad, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
          COUNT(*) AS total
          FROM seguimientoactividades AS sa
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
          WHERE sa.idArea = $idArea AND sa.activo='Si'AND sa.fechaInicio>=? AND sa.fechaInicio<=?
          AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
          OR sa.idu = $sidu)
          GROUP BY sa.idActividad
          ORDER BY act.nombreActividad ASC",[$fechaInicio, $fechaFin]);
        }else{
          $consultaActividad=\DB::select("SELECT act.nombreActividad, sa.idArea,sa.idActividad,a.nombreArea,
          tipoactabiertas(sa.idActividad, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
          tipoactcerradas(sa.idActividad, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
          tipoactcanceladas(sa.idActividad, '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
          COUNT(*) AS total
          FROM seguimientoactividades AS sa
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
          WHERE sa.idArea = $idArea AND sa.activo='Si'AND sa.fechaTermino>=? AND sa.fechaTermino<=?
          AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
          OR sa.idu = $sidu)
          GROUP BY sa.idActividad
          ORDER BY act.nombreActividad ASC",[$fechaInicio, $fechaFin]);
        }
      }
    }
    
    
    return view("reporteAreaSeg")
    ->with('consultaActividad',$consultaActividad)
    ->with('fechaInicio',$fechaInicio)
    ->with('fechaFin',$fechaFin)
    ->with('filtroPor',$filtroPor);
  }

  public function reporteTrabajadorSeg(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    $idArea=$request->idArea;
    $idc=$request->idc;
    $idActividad=$request->idActividad;
    $filtroPor = $request->filtroPor;
    $fechaInicio = $request->fechaInicio;
    $fechaFin = $request->fechaFin;
    $areaOEmpresa = $request->areaOEmpresa;


    if($stipo=='Administrador'){
      //boton de trabajador sin fechas
      if($filtroPor==''){
        $fechaInicio = '1900-01-01';
        $fechaFin = '1900-01-01';

        if($idArea!=0 && $idActividad==''){
            $idc=0;
            $idActividad=0;
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idArea=$idArea AND sa.activo='Si'
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu,sa.idArea");
        }
        if($idArea!=0 && $idActividad!=0 && $idc==''){
            $idc=0;
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idActividad=$idActividad AND sa.activo='Si'
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu,sa.idArea");
        }
        if($idArea!=0 && $idActividad==0 && $idc!=0){
          $idActividad=0;
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idc=$idc AND sa.activo='Si'
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu,sa.idArea");
        }
        if($idArea!=0 && $idActividad!=0 && $idc!=0){
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idc=$idc and sa.idActividad=$idActividad AND sa.activo='Si'
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu,sa.idArea");
        }
        
      }
      //boton de trabajador fecha de inicio
      if($filtroPor=='fechaInicio'){
        if($idArea!=0 && $idActividad==''){
            $idc=0;
            $idActividad=0;
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idArea=$idArea AND sa.activo='Si'
            AND sa.fechaInicio>=? AND sa.fechaInicio<=?
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc==''){
            $idc=0;
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idActividad=$idActividad AND sa.activo='Si'
            AND sa.fechaInicio>=? AND sa.fechaInicio<=?
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad==0 && $idc!=0){
          $idActividad=0;
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idc=$idc AND sa.activo='Si'
            AND sa.fechaInicio>=? AND sa.fechaInicio<=?
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc!=0){
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idc=$idc and sa.idActividad=$idActividad AND sa.activo='Si'
            AND sa.fechaInicio>=? AND sa.fechaInicio<=?
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        
      }

      //boton de trabajador fecha de termino
      if($filtroPor=='fechaTermino'){
        if($idArea!=0 && $idActividad==''){
            $idc=0;
            $idActividad=0;
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idArea=$idArea AND sa.activo='Si'
            AND sa.fechaTermino>=? AND sa.fechaTermino<=?
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc==''){
            $idc=0;
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idActividad=$idActividad AND sa.activo='Si'
            AND sa.fechaTermino>=? AND sa.fechaTermino<=?
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad==0 && $idc!=0){
          $idActividad=0;
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idc=$idc AND sa.activo='Si'
            AND sa.fechaTermino>=? AND sa.fechaTermino<=?
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc!=0){
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idc=$idc and sa.idActividad=$idActividad AND sa.activo='Si'
            AND sa.fechaTermino>=? AND sa.fechaTermino<=?
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        
      }

      if($areaOEmpresa=='Empresa'){
        if($filtroPor=='fechaInicio'){
          $idActividad=0;
          $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
          trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
          trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
          trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
          COUNT(*) AS total
          FROM seguimientoactividades AS sa
          INNER JOIN usuarios AS usu ON usu.idu = sa.idu
          WHERE sa.idc=$idc AND sa.activo='Si'
          AND sa.fechaInicio>=? AND sa.fechaInicio<=?
          GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        if($filtroPor=='fechaTermino'){
          $idActividad=0;
          $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
          trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
          trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
          trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
          COUNT(*) AS total
          FROM seguimientoactividades AS sa
          INNER JOIN usuarios AS usu ON usu.idu = sa.idu
          WHERE sa.idc=$idc AND sa.activo='Si'
          AND sa.fechaTermino>=? AND sa.fechaTermino<=?
          GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        
      }
    }else{
      //boton de trabajador sin fechas
      if($filtroPor==''){
        $fechaInicio = '1900-01-01';
        $fechaFin = '1900-01-01';

        if($idArea!=0 && $idActividad==''){
            $idc=0;
            $idActividad=0;
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idArea=$idArea AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu,sa.idArea");
        }
        if($idArea!=0 && $idActividad!=0 && $idc==''){
            $idc=0;
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idActividad=$idActividad AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu,sa.idArea");
        }
        if($idArea!=0 && $idActividad==0 && $idc!=0){
          $idActividad=0;
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idc=$idc AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu,sa.idArea");
        }
        if($idArea!=0 && $idActividad!=0 && $idc!=0){
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idc=$idc and sa.idActividad=$idActividad AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu,sa.idArea");
        }
        
      }
      //boton de trabajador fecha de inicio
      if($filtroPor=='fechaInicio'){
        if($idArea!=0 && $idActividad==''){
            $idc=0;
            $idActividad=0;
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idArea=$idArea AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            AND sa.fechaInicio>=? AND sa.fechaInicio<=?
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc==''){
            $idc=0;
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idActividad=$idActividad AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            AND sa.fechaInicio>=? AND sa.fechaInicio<=?
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad==0 && $idc!=0){
          $idActividad=0;
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idc=$idc AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            AND sa.fechaInicio>=? AND sa.fechaInicio<=?
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc!=0){
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idc=$idc and sa.idActividad=$idActividad AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            AND sa.fechaInicio>=? AND sa.fechaInicio<=?
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        
      }

      //boton de trabajador fecha de termino
      if($filtroPor=='fechaTermino'){
        if($idArea!=0 && $idActividad==''){
            $idc=0;
            $idActividad=0;
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idArea=$idArea AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            AND sa.fechaTermino>=? AND sa.fechaTermino<=?
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc==''){
            $idc=0;
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idActividad=$idActividad AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            AND sa.fechaTermino>=? AND sa.fechaTermino<=?
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad==0 && $idc!=0){
          $idActividad=0;
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idc=$idc AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            AND sa.fechaTermino>=? AND sa.fechaTermino<=?
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc!=0){
            $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
            trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
            trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
            trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN usuarios AS usu ON usu.idu = sa.idu
            WHERE sa.idc=$idc and sa.idActividad=$idActividad AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            AND sa.fechaTermino>=? AND sa.fechaTermino<=?
            GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        
      }

      if($areaOEmpresa=='Empresa'){
        if($filtroPor=='fechaInicio'){
          $idActividad=0;
          $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
          trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
          trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
          trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
          COUNT(*) AS total
          FROM seguimientoactividades AS sa
          INNER JOIN usuarios AS usu ON usu.idu = sa.idu
          WHERE sa.idc=$idc AND sa.activo='Si'
          AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
          OR sa.idu = $sidu)
          AND sa.fechaInicio>=? AND sa.fechaInicio<=?
          GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        if($filtroPor=='fechaTermino'){
          $idActividad=0;
          $consultaActividad=\DB::select("SELECT usu.nombreUsuario, usu.tipo,usu.idu,sa.idArea,
          trabAbiertas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS abiertas, 
          trabCanceladas(sa.idu, '$idArea','$idActividad','$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS canceladas, 
          trabCerradas(sa.idu, '$idArea','$idActividad', '$idc', '$areaOEmpresa', '$filtroPor', '$fechaInicio', '$fechaFin', $sidu, '$stipo') AS cerradas, 
          COUNT(*) AS total
          FROM seguimientoactividades AS sa
          INNER JOIN usuarios AS usu ON usu.idu = sa.idu
          WHERE sa.idc=$idc AND sa.activo='Si'
          AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
          OR sa.idu = $sidu)
          AND sa.fechaTermino>=? AND sa.fechaTermino<=?
          GROUP BY usu.nombreUsuario, abiertas, canceladas, cerradas, usu.tipo, usu.idu, sa.idArea",[$fechaInicio, $fechaFin]);
        }
        
      }
    

    }
    

    return view("reporteTrabajadorSeg")
    // ->with('idArea',$idArea)
    ->with('idActividad',$idActividad)
    ->with('fechaInicio',$fechaInicio)
    ->with('fechaFin',$fechaFin)
    ->with('filtroPor',$filtroPor)
    ->with('idc',$idc)
    ->with('areaOEmpresa',$areaOEmpresa)
    ->with('consultaActividad',$consultaActividad);

    // return $request;
  }

  public function reporteEmpresaSeg(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    $idArea=$request->idArea;
    $idActividad=$request->idActividad;
    $filtroPor = $request->filtroPor;
    $fechaInicio = $request->fechaInicio;
    $fechaFin = $request->fechaFin;
    $areaOEmpresa = $request->areaOEmpresa;
    $idCliente=0;


    if($stipo=='Administrador'){
      //Boton sin fechas 
      if($filtroPor==''){
        $fechaInicio = '1900-01-01';
        $fechaFin = '1900-01-01';
        if($idArea!=0 && $idActividad==''){
            $idActividad=0;
            $consultaActividad=\DB::select("SELECT sa.idArea, c.razonSocial,s.sucursal,sa.idc,
            empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
            empresaCerradas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
            empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
            WHERE sa.idArea=$idArea AND sa.activo='Si'
            GROUP BY c.razonSocial, s.sucursal, sa.idc");
        }
        if($idArea!=0 && $idActividad!=0){
            $consultaActividad=\DB::select("SELECT sa.idArea, c.razonSocial,s.sucursal,sa.idc,
            empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
            empresaCerradas(sa.idc, '$idArea',$idActividad, '$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
            empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
            WHERE sa.idActividad=$idActividad AND sa.activo='Si'
            GROUP BY sa.idc,s.sucursal, sa.idArea");
        }
      }

      //Boton fecha de inicio
      if($filtroPor=='fechaInicio'){
        if($idArea!=0 && $idActividad==''){
            $idActividad=0;
            $consultaActividad=\DB::select("SELECT sa.idArea, c.razonSocial,s.sucursal,sa.idc,
            empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
            empresaCerradas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
            empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
            WHERE sa.idArea=$idArea AND sa.activo='Si'
            AND sa.fechaInicio>=? AND sa.fechaInicio<=?
            GROUP BY sa.idArea, sa.idc, s.sucursal",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0){
            $consultaActividad=\DB::select("SELECT sa.idArea, c.razonSocial,s.sucursal,sa.idc,
            empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
            empresaCerradas(sa.idc, '$idArea',$idActividad, '$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
            empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
            WHERE sa.idActividad=$idActividad AND sa.activo='Si'
            AND sa.fechaInicio>=? AND sa.fechaInicio<=?
            GROUP BY sa.idArea, sa.idc, s.sucursal",[$fechaInicio, $fechaFin]);
        }
      }

      //Boton fecha de inicio
      if($filtroPor=='fechaTermino'){
        if($idArea!=0 && $idActividad==''){
            $idActividad=0;
            $consultaActividad=\DB::select("SELECT sa.idArea, c.razonSocial,s.sucursal,sa.idc,
            empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
            empresaCerradas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
            empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
            WHERE sa.idArea=$idArea AND sa.activo='Si'
            AND sa.fechaTermino>=? AND sa.fechaTermino<=?
            GROUP BY sa.idArea, sa.idc, s.sucursal",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0){
            $consultaActividad=\DB::select("SELECT sa.idArea, c.razonSocial,s.sucursal,sa.idc,
            empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
            empresaCerradas(sa.idc, '$idArea',$idActividad, '$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
            empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
            WHERE sa.idActividad=$idActividad AND sa.activo='Si'
            AND sa.fechaTermino>=? AND sa.fechaTermino<=?
            GROUP BY sa.idArea, sa.idc, s.sucursal",[$fechaInicio, $fechaFin]);
        }
      }
    }else{
      //Boton sin fechas 
      if($filtroPor==''){
        $fechaInicio = '1900-01-01';
        $fechaFin = '1900-01-01';
        if($idArea!=0 && $idActividad==''){
            $idActividad=0;
            $consultaActividad=\DB::select("SELECT sa.idArea, c.razonSocial,s.sucursal,sa.idc,
            empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
            empresaCerradas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
            empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
            WHERE sa.idArea=$idArea AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            GROUP BY c.razonSocial, s.sucursal, sa.idc");
        }
        if($idArea!=0 && $idActividad!=0){
            $consultaActividad=\DB::select("SELECT sa.idArea, c.razonSocial,s.sucursal,sa.idc,
            empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
            empresaCerradas(sa.idc, '$idArea',$idActividad, '$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
            empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
            WHERE sa.idActividad=$idActividad AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            GROUP BY sa.idc,s.sucursal, sa.idArea");
        }
      }

      //Boton fecha de inicio
      if($filtroPor=='fechaInicio'){
        if($idArea!=0 && $idActividad==''){
            $idActividad=0;
            $consultaActividad=\DB::select("SELECT sa.idArea, c.razonSocial,s.sucursal,sa.idc,
            empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
            empresaCerradas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
            empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
            WHERE sa.idArea=$idArea AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            AND sa.fechaInicio>=? AND sa.fechaInicio<=?
            GROUP BY sa.idArea, sa.idc, s.sucursal",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0){
            $consultaActividad=\DB::select("SELECT sa.idArea, c.razonSocial,s.sucursal,sa.idc,
            empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
            empresaCerradas(sa.idc, '$idArea',$idActividad, '$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
            empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
            WHERE sa.idActividad=$idActividad AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            AND sa.fechaInicio>=? AND sa.fechaInicio<=?
            GROUP BY sa.idArea, sa.idc, s.sucursal",[$fechaInicio, $fechaFin]);
        }
      }

      //Boton fecha de inicio
      if($filtroPor=='fechaTermino'){
        if($idArea!=0 && $idActividad==''){
            $idActividad=0;
            $consultaActividad=\DB::select("SELECT sa.idArea, c.razonSocial,s.sucursal,sa.idc,
            empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
            empresaCerradas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
            empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
            WHERE sa.idArea=$idArea AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            AND sa.fechaTermino>=? AND sa.fechaTermino<=?
            GROUP BY sa.idArea, sa.idc, s.sucursal",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0){
            $consultaActividad=\DB::select("SELECT sa.idArea, c.razonSocial,s.sucursal,sa.idc,
            empresaAbiertas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS abiertas, 
            empresaCerradas(sa.idc, '$idArea',$idActividad, '$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS cerradas, 
            empresaCanceladas(sa.idc, '$idArea',$idActividad,'$filtroPor', '$fechaInicio', '$fechaFin','$idCliente', $sidu, '$stipo') AS canceladas, 
            COUNT(*) AS total
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal=sa.idSucursal
            WHERE sa.idActividad=$idActividad AND sa.activo='Si'
            AND (sa.idu IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR sa.idu = $sidu)
            AND sa.fechaTermino>=? AND sa.fechaTermino<=?
            GROUP BY sa.idArea, sa.idc, s.sucursal",[$fechaInicio, $fechaFin]);
        }
      }
    }


   
        
    return view("reporteEmpresaSeg")
    ->with('idArea',$idArea)
    ->with('idActividad',$idActividad)
    ->with('fechaInicio',$fechaInicio)
    ->with('fechaFin',$fechaFin)
    ->with('filtroPor',$filtroPor)
    ->with('areaOEmpresa',$areaOEmpresa)
    ->with('consultaActividad',$consultaActividad);
  }

  public function reportePorAct(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');


    $idArea=$request->idArea;
    $idActividad=$request->idActividad;
    $idu=$request->idu;
    $idc=$request->idc;
    $filtroPor = $request->filtroPor;
    $fechaInicio = $request->fechaInicio;
    $fechaFin = $request->fechaFin;
    $areaOEmpresa = $request->areaOEmpresa;


    if($stipo=='Administrador'){
      //boton de actividad sin fechas
      if($filtroPor==''){
        $fechaInicio = '1900-01-01';
        $fechaFin = '1900-01-01';

        if($idArea!=0 && $idActividad==''){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
          t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
          FROM
          (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,
          CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
            IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
            seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
          FROM seguimientoactividades AS sa
          INNER JOIN clientes AS c ON c.idc = sa.idc
          INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
          LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
          WHERE t1.idArea=$idArea AND t1.activo='Si'
          GROUP BY t1.idSegActividad");
        }
        if($idArea!=0 && $idActividad!=0 && $idc==''){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
              t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
              FROM
              (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,
              CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
                IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
              INNER JOIN areas AS a ON a.idArea = sa.idArea
              INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
              LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
              WHERE t1.idActividad=$idActividad AND t1.activo='Si'
              GROUP BY t1.idSegActividad");
        }
        if($idArea!=0 && $idActividad==0 && $idc==0 && $idu!=0){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idUsuario=$idu AND t1.activo='Si'
            GROUP BY t1.idSegActividad");
        }
        if($idArea!=0 && $idActividad==0 && $idc!=0 && $idu==''){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
              t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
              FROM
              (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,
              CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
                IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
              INNER JOIN areas AS a ON a.idArea = sa.idArea
              INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
              LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
              WHERE t1.idc=$idc AND t1.activo='Si'
              GROUP BY t1.idSegActividad");
        }
        if($idArea!=0 && $idActividad!=0 && $idc==0 && $idu!=0){
          // return 'hola';
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idUsuario=$idu AND t1.idActividad = $idActividad AND t1.idArea=$idArea AND t1.activo='Si'
            GROUP BY t1.idSegActividad");
        }
        if($idArea!=0 && $idActividad!=0 && $idc!=0 && $idu!=0){
          // return 'hola';
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idUsuario=$idu AND t1.idActividad = $idActividad AND t1.idc=$idc AND t1.idArea=$idArea AND t1.activo='Si'
            GROUP BY t1.idSegActividad");
        }
        if($idArea!=0 && $idActividad!=0 && $idc!=0 && $idu==''){
          // return 'hola';
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idActividad = $idActividad AND t1.idc=$idc AND t1.idArea=$idArea AND t1.activo='Si'
            GROUP BY t1.idSegActividad");
        }
        if($idArea!=0 && $idActividad==0 && $idc!=0 && $idu!=0){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
              t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
              FROM
              (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,
              CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
                IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
              INNER JOIN areas AS a ON a.idArea = sa.idArea
              INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
              LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
              WHERE t1.idc=$idc and t1.idUsuario=$idu AND t1.activo='Si'
              GROUP BY t1.idSegActividad");
        }
      }

      //boton de actividad fecha inicio
      if($filtroPor=='fechaInicio'){

        if($idArea!=0 && $idActividad==''){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
          t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
          FROM
          (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
          CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
            IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
            seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
          FROM seguimientoactividades AS sa
          INNER JOIN clientes AS c ON c.idc = sa.idc
          INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
          LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
          WHERE t1.idArea=$idArea AND t1.activo='Si'
          AND t1.fechaInicio>=? AND t1.fechaInicio<=?
          GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc==''){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
              t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
              FROM
              (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
              CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
                IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
              INNER JOIN areas AS a ON a.idArea = sa.idArea
              INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
              LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
              WHERE t1.idActividad=$idActividad AND t1.activo='Si'
              AND t1.fechaInicio>=? AND t1.fechaInicio<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad==0 && $idc==0 && $idu!=0){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idUsuario=$idu AND t1.idArea=$idArea AND t1.activo='Si'
            AND t1.fechaInicio>=? AND t1.fechaInicio<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad==0 && $idc!=0 && $idu==''){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
              t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
              FROM
              (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
              CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
                IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
              INNER JOIN areas AS a ON a.idArea = sa.idArea
              INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
              LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
              WHERE t1.idc=$idc AND t1.activo='Si'
              AND t1.fechaInicio>=? AND t1.fechaInicio<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc==0 && $idu!=0){
          // return 'hola';
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idUsuario=$idu AND t1.idActividad = $idActividad AND t1.idArea=$idArea AND t1.activo='Si'
            AND t1.fechaInicio>=? AND t1.fechaInicio<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc!=0 && $idu!=0){
          // return 'hola';
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idUsuario=$idu AND t1.idActividad = $idActividad AND t1.idc=$idc AND t1.idArea=$idArea AND t1.activo='Si'
            AND t1.fechaInicio>=? AND t1.fechaInicio<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc!=0 && $idu==''){
          // return 'hola';
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idActividad = $idActividad AND t1.idc=$idc AND t1.idArea=$idArea AND t1.activo='Si'
            AND t1.fechaInicio>=? AND t1.fechaInicio<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad==0 && $idc!=0 && $idu!=0){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
              t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
              FROM
              (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
              CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
                IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
              INNER JOIN areas AS a ON a.idArea = sa.idArea
              INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
              LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
              WHERE t1.idc=$idc and t1.idUsuario=$idu AND t1.activo='Si'
              AND t1.fechaInicio>=? AND t1.fechaInicio<=?
              GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
      }

      //boton de actividad fecha termino
      if($filtroPor=='fechaTermino'){

        if($idArea!=0 && $idActividad==''){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
          t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
          FROM
          (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
          CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
            IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
            seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
          FROM seguimientoactividades AS sa
          INNER JOIN clientes AS c ON c.idc = sa.idc
          INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
          LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
          WHERE t1.idArea=$idArea AND t1.activo='Si'
          AND t1.fechaTermino>=? AND t1.fechaTermino<=?
          GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc==''){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
              t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
              FROM
              (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
              CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
                IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
              INNER JOIN areas AS a ON a.idArea = sa.idArea
              INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
              LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
              WHERE t1.idActividad=$idActividad AND t1.activo='Si'
              AND t1.fechaTermino>=? AND t1.fechaTermino<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad==0 && $idc==0 && $idu!=0){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idUsuario=$idu AND t1.idArea=$idArea AND t1.activo='Si'
            AND t1.fechaTermino>=? AND t1.fechaTermino<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad==0 && $idc!=0 && $idu==''){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
              t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
              FROM
              (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
              CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
                IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
              INNER JOIN areas AS a ON a.idArea = sa.idArea
              INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
              LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
              WHERE t1.idc=$idc AND t1.activo='Si'
              AND t1.fechaTermino>=? AND t1.fechaTermino<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc==0 && $idu!=0){
          // return 'hola';
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idUsuario=$idu AND t1.idActividad = $idActividad AND t1.idArea=$idArea AND t1.activo='Si'
            AND t1.fechaTermino>=? AND t1.fechaTermino<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc!=0 && $idu!=0){
          // return 'hola';
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idUsuario=$idu AND t1.idActividad = $idActividad AND t1.idc=$idc AND t1.idArea=$idArea AND t1.activo='Si'
            AND t1.fechaTermino>=? AND t1.fechaTermino<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc!=0 && $idu==''){
          // return 'hola';
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idActividad = $idActividad AND t1.idc=$idc AND t1.idArea=$idArea AND t1.activo='Si'
            AND t1.fechaTermino>=? AND t1.fechaTermino<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad==0 && $idc!=0 && $idu!=0){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
              t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
              FROM
              (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
              CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
                IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
              INNER JOIN areas AS a ON a.idArea = sa.idArea
              INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
              LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
              WHERE t1.idc=$idc and t1.idUsuario=$idu AND t1.activo='Si'
              AND t1.fechaTermino>=? AND t1.fechaTermino<=?
              GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        
      }

      if($areaOEmpresa=='Empresa'){
        if($filtroPor=='fechaInicio'){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
          t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
          FROM
          (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
          CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
            IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
            seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
          FROM seguimientoactividades AS sa
          INNER JOIN clientes AS c ON c.idc = sa.idc
          INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
          LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
          WHERE t1.idc=$idc AND t1.activo='Si'
          AND t1.fechaInicio>=? AND t1.fechaInicio<=?
          GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($filtroPor=='fechaTermino'){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
          t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
          FROM
          (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
          CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
            IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
            seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
          FROM seguimientoactividades AS sa
          INNER JOIN clientes AS c ON c.idc = sa.idc
          INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
          LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
          WHERE t1.idc=$idc AND t1.activo='Si'
          AND t1.fechaTermino>=? AND t1.fechaTermino<=?
          GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
      }
    }else{

      if($filtroPor==''){

        if($idArea!=0 && $idActividad==''){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
          t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
          FROM
          (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
          CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
            IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
            seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
          FROM seguimientoactividades AS sa
          INNER JOIN clientes AS c ON c.idc = sa.idc
          INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
          LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
          WHERE t1.idArea=$idArea AND t1.activo='Si'
          AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
          OR t1.idUsuario = $sidu)
          GROUP BY t1.idSegActividad");
        }
        if($idArea!=0 && $idActividad!=0 && $idc==''){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
              t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
              FROM
              (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
              CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
                IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
              INNER JOIN areas AS a ON a.idArea = sa.idArea
              INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
              LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
              WHERE t1.idActividad=$idActividad AND t1.activo='Si'
              AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR t1.idUsuario = $sidu)
            GROUP BY t1.idSegActividad");
        }
        if($idArea!=0 && $idActividad==0 && $idc==0 && $idu!=0){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idUsuario=$idu AND t1.idArea=$idArea AND t1.activo='Si'
            AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR t1.idUsuario = $sidu)
            GROUP BY t1.idSegActividad");
        }
        if($idArea!=0 && $idActividad==0 && $idc!=0 && $idu==''){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
              t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
              FROM
              (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
              CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
                IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
              INNER JOIN areas AS a ON a.idArea = sa.idArea
              INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
              LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
              WHERE t1.idc=$idc AND t1.activo='Si'
              AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
              OR t1.idUsuario = $sidu)
              GROUP BY t1.idSegActividad");
        }
        if($idArea!=0 && $idActividad!=0 && $idc==0 && $idu!=0){
          // return 'hola';
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idUsuario=$idu AND t1.idActividad = $idActividad AND t1.idArea=$idArea AND t1.activo='Si'
            AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR t1.idUsuario = $sidu)
            GROUP BY t1.idSegActividad");
        }
        if($idArea!=0 && $idActividad!=0 && $idc!=0 && $idu!=0){
          // return 'hola';
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idUsuario=$idu AND t1.idActividad = $idActividad AND t1.idc=$idc AND t1.idArea=$idArea AND t1.activo='Si'
            AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR t1.idUsuario = $sidu)
            GROUP BY t1.idSegActividad");
        }
        if($idArea!=0 && $idActividad!=0 && $idc!=0 && $idu==''){
          // return 'hola';
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idActividad = $idActividad AND t1.idc=$idc AND t1.idArea=$idArea AND t1.activo='Si'
            AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR t1.idUsuario = $sidu)
            GROUP BY t1.idSegActividad");
        }
        if($idArea!=0 && $idActividad==0 && $idc!=0 && $idu!=0){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
              t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
              FROM
              (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
              CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
                IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
              INNER JOIN areas AS a ON a.idArea = sa.idArea
              INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
              LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
              WHERE t1.idc=$idc and t1.idUsuario=$idu AND t1.activo='Si'
              AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
              OR t1.idUsuario = $sidu)
              GROUP BY t1.idSegActividad");
        }
      }

      //boton de actividad fecha inicio
      if($filtroPor=='fechaInicio'){

        if($idArea!=0 && $idActividad==''){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
          t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
          FROM
          (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
          CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
            IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
            seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
          FROM seguimientoactividades AS sa
          INNER JOIN clientes AS c ON c.idc = sa.idc
          INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
          LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
          WHERE t1.idArea=$idArea AND t1.activo='Si'
          AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
          OR t1.idUsuario = $sidu)
          AND t1.fechaInicio>=? AND t1.fechaInicio<=?
          GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc==''){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
              t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
              FROM
              (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
              CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
                IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
              INNER JOIN areas AS a ON a.idArea = sa.idArea
              INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
              LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
              WHERE t1.idActividad=$idActividad AND t1.activo='Si'
              AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR t1.idUsuario = $sidu)
              AND t1.fechaInicio>=? AND t1.fechaInicio<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad==0 && $idc==0 && $idu!=0){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idUsuario=$idu AND t1.idArea=$idArea AND t1.activo='Si'
            AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR t1.idUsuario = $sidu)
            AND t1.fechaInicio>=? AND t1.fechaInicio<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad==0 && $idc!=0 && $idu==''){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
              t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
              FROM
              (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
              CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
                IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
              INNER JOIN areas AS a ON a.idArea = sa.idArea
              INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
              LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
              WHERE t1.idc=$idc AND t1.activo='Si'
              AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
              OR t1.idUsuario = $sidu)
              AND t1.fechaInicio>=? AND t1.fechaInicio<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc==0 && $idu!=0){
          // return 'hola';
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idUsuario=$idu AND t1.idActividad = $idActividad AND t1.idArea=$idArea AND t1.activo='Si'
            AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR t1.idUsuario = $sidu)
            AND t1.fechaInicio>=? AND t1.fechaInicio<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc!=0 && $idu!=0){
          // return 'hola';
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idUsuario=$idu AND t1.idActividad = $idActividad AND t1.idc=$idc AND t1.idArea=$idArea AND t1.activo='Si'
            AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR t1.idUsuario = $sidu)
            AND t1.fechaInicio>=? AND t1.fechaInicio<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc!=0 && $idu==''){
          // return 'hola';
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idActividad = $idActividad AND t1.idc=$idc AND t1.idArea=$idArea AND t1.activo='Si'
            AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR t1.idUsuario = $sidu)
            AND t1.fechaInicio>=? AND t1.fechaInicio<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad==0 && $idc!=0 && $idu!=0){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
              t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
              FROM
              (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
              CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
                IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
              INNER JOIN areas AS a ON a.idArea = sa.idArea
              INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
              LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
              WHERE t1.idc=$idc and t1.idUsuario=$idu AND t1.activo='Si'
              AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
              OR t1.idUsuario = $sidu)
              AND t1.fechaInicio>=? AND t1.fechaInicio<=?
              GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
      }
      //boton de actividad fecha inicio
      if($filtroPor=='fechaTermino'){

        if($idArea!=0 && $idActividad==''){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
          t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
          FROM
          (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
          CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
            IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
            seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
          FROM seguimientoactividades AS sa
          INNER JOIN clientes AS c ON c.idc = sa.idc
          INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
          LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
          WHERE t1.idArea=$idArea AND t1.activo='Si'
          AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
          OR t1.idUsuario = $sidu)
          AND t1.fechaTermino>=? AND t1.fechaTermino<=?
          GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc==''){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
              t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
              FROM
              (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
              CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
                IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
              INNER JOIN areas AS a ON a.idArea = sa.idArea
              INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
              LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
              WHERE t1.idActividad=$idActividad AND t1.activo='Si'
              AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR t1.idUsuario = $sidu)
              AND t1.fechaTermino>=? AND t1.fechaTermino<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad==0 && $idc==0 && $idu!=0){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idUsuario=$idu AND t1.idArea=$idArea AND t1.activo='Si'
            AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR t1.idUsuario = $sidu)
            AND t1.fechaTermino>=? AND t1.fechaTermino<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad==0 && $idc!=0 && $idu==''){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
              t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
              FROM
              (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
              CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
                IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
              INNER JOIN areas AS a ON a.idArea = sa.idArea
              INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
              LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
              WHERE t1.idc=$idc AND t1.activo='Si'
              AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
              OR t1.idUsuario = $sidu)
              AND t1.fechaTermino>=? AND t1.fechaTermino<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc==0 && $idu!=0){
          // return 'hola';
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idUsuario=$idu AND t1.idActividad = $idActividad AND t1.idArea=$idArea AND t1.activo='Si'
            AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR t1.idUsuario = $sidu)
            AND t1.fechaTermino>=? AND t1.fechaTermino<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc!=0 && $idu!=0){
          // return 'hola';
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idUsuario=$idu AND t1.idActividad = $idActividad AND t1.idc=$idc AND t1.idArea=$idArea AND t1.activo='Si'
            AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR t1.idUsuario = $sidu)
            AND t1.fechaTermino>=? AND t1.fechaTermino<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad!=0 && $idc!=0 && $idu==''){
          // return 'hola';
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
            t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
            FROM
            (SELECT sa.idArea,sa.idActividad, sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
            CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
              IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
              seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
            FROM seguimientoactividades AS sa
            INNER JOIN clientes AS c ON c.idc = sa.idc
            INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
            INNER JOIN areas AS a ON a.idArea = sa.idArea
            INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
            LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
            WHERE t1.idActividad = $idActividad AND t1.idc=$idc AND t1.idArea=$idArea AND t1.activo='Si'
            AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
            OR t1.idUsuario = $sidu)
            AND t1.fechaTermino>=? AND t1.fechaTermino<=?
            GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($idArea!=0 && $idActividad==0 && $idc!=0 && $idu!=0){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
              t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
              FROM
              (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
              CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
                IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
                seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
              FROM seguimientoactividades AS sa
              INNER JOIN clientes AS c ON c.idc = sa.idc
              INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
              INNER JOIN areas AS a ON a.idArea = sa.idArea
              INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
              LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
              WHERE t1.idc=$idc and t1.idUsuario=$idu AND t1.activo='Si'
              AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
              OR t1.idUsuario = $sidu)
              AND t1.fechaTermino>=? AND t1.fechaTermino<=?
              GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
      }

      if($areaOEmpresa=='Empresa'){
        if($filtroPor=='fechaInicio'){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
          t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
          FROM
          (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
          CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
            IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
            seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
          FROM seguimientoactividades AS sa
          INNER JOIN clientes AS c ON c.idc = sa.idc
          INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
          LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
          WHERE t1.idc=$idc AND t1.activo='Si'
          AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
          OR t1.idUsuario = $sidu)
          AND t1.fechaInicio>=? AND t1.fechaInicio<=?
          GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
        if($filtroPor=='fechaTermino'){
          $seguimientoA=DB::select("SELECT t1.activo,t1.idSegActividad ,t1.folio,t1.fechaCreacion,t1.asunto,t1.nombreUsuario,t1.razonSocial,t1.sucursal,t1.periodo,t1.cliente, t1.importanciaSeguimiento,
          t1.nombreArea,t1.nombreActividad,TRUNCATE (AVG(t1.porcentaje),0) AS porcentaje,CONCAT(SUM(t1.r),' de ',COUNT(t1.idu)) AS atendidopor
          FROM
          (SELECT sa.idArea,sa.idSegActividad,sa. folio, DATE_FORMAT(sa.fechaCreacion, '%d %b %y') as fechaCreacion, sa.asunto, sa.nombreUsuario,c.razonSocial, s.sucursal,sa.activo,sa.idc,sa.fechaInicio, sa.fechaTermino,
          CONCAT(c.razonSocial,' - ',s.sucursal) AS cliente, CONCAT(DATE_FORMAT(sa.fechaInicio, '%d %b %y'),' - ',DATE_FORMAT(sa.fechaTermino, '%d %b %y')) AS periodo, sa.importanciaSeguimiento, a.nombreArea, act.nombreActividad,asig.idu,sa.idu AS idUsuario,
            IF(ultimoporcentaje(sa.idSegActividad,asig.idu)IS NULL,0,ultimoporcentaje(sa.idSegActividad,asig.idu)) AS porcentaje,
            seguimiento(sa.idSegActividad, asig.idu ),IF(seguimiento(sa.idSegActividad, asig.idu )>=1,1,0) AS r
          FROM seguimientoactividades AS sa
          INNER JOIN clientes AS c ON c.idc = sa.idc
          INNER JOIN sucursales AS s ON s.idSucursal = sa.idSucursal
          INNER JOIN areas AS a ON a.idArea = sa.idArea
          INNER JOIN actividades AS act ON act.idActividad = sa.idActividad
          LEFT JOIN asignacionseguimientos AS asig ON asig.idSegActividad = sa.idSegActividad)AS t1
          WHERE t1.idc=$idc AND t1.activo='Si'
          AND (t1.idUsuario IN (SELECT iduAsignado FROM usuariosacargos WHERE idu=$sidu)
          OR t1.idUsuario = $sidu)
          AND t1.fechaTermino>=? AND t1.fechaTermino<=?
          GROUP BY t1.idSegActividad",[$fechaInicio, $fechaFin]);
        }
      }
    }
  

    


    
    return view("reportePorAct")
    ->with('seguimientoA',$seguimientoA);
    // return $seguimientoA;
    
  }

  public function reporteAvance(Request $request){
    $idSegActividad=$request->idSegActividad;
    $seguimientoA = \DB::select("SELECT t1.idSegActividad,t1.idu,t1.actor,t1.porcentaje,t1.numeroseguimientos,t1.ultimafecha,t1.tipo,t1.queRealiza,
                                        IF(t1.porcentaje=100 AND t1.ultimafecha<= t1.fechatermino AND t1.numeroseguimientos >0,'Cerrada en Tiempo',
                                        IF(t1.porcentaje<100 AND t1.ultimafecha<= t1.fechatermino AND t1.numeroseguimientos >0,'Seguimiento en Tiempo',
                                        IF(t1.numeroseguimientos = 0,'Sin Avance','Fuera de tiempo'))) AS statusactividad
                                        FROM(
                                        SELECT a.idSegActividad, a.idu,CONCAT(u.nombreusuario,' ',u.apaterno,' ',u.amaterno) AS actor,IF(ultimoporcentaje($idSegActividad,a.idu)IS NULL,0,ultimoporcentaje($idSegActividad,a.idu)) AS porcentaje,
                                        seguimiento($idSegActividad, a.idu ) AS numeroseguimientos,IF(ultimafecha($idSegActividad,a.idu) IS NULL,'',ultimafecha($idSegActividad,a.idu))AS ultimafecha,sa.fechatermino,u.tipo,a.queRealiza
                                        FROM asignacionseguimientos AS a
                                        INNER JOIN usuarios AS u ON u.idu = a.idu
                                        INNER JOIN seguimientoactividades AS sa ON sa.idSegActividad = a.idSegActividad
                                        WHERE a.idSegActividad= $idSegActividad) AS t1");

    $porcentaje= \DB::select("SELECT t1.idsegactividad,TRUNCATE(AVG(t1.porcentaje),0) AS porcentaje
                              FROM
                              (SELECT idsegactividad, idu,IF(ultimoporcentaje($idSegActividad,idu)IS NULL,0,ultimoporcentaje($idSegActividad,idu)) AS porcentaje
                              FROM asignacionseguimientos
                              WHERE idsegactividad= $idSegActividad) AS t1
                              GROUP BY t1.idsegactividad");
                                                                    


    $atendidoPor =\DB::select("SELECT CONCAT(SUM(r),' de ',COUNT(*)) AS atendidopor
                                FROM
                                (SELECT a.idsegactividad, a.idu,seguimiento($idSegActividad, a.idu ) , IF(seguimiento($idSegActividad, a.idu )>=1,1,0) AS r
                                FROM asignacionseguimientos AS a
                                INNER JOIN usuarios AS u ON u.idu = a.idu
                                WHERE idsegactividad= $idSegActividad) AS t1
                                GROUP BY t1.idsegactividad");

// ->with('consulta',$consulta)
//                 ->with('seguimientoA',$seguimientoA)
//                 ->with('cuantos',$cuantos)
//                 ->with('porcentaje',$porcentaje[0])
//                 ->with('atendidoPor',$atendidoPor[0])
//                 ->with('detalle',$detalle);

    return view("reporteAvance")
    ->with('atendidoPor',$atendidoPor[0])
    ->with('porcentaje',$porcentaje[0])
    ->with('seguimientoA',$seguimientoA);
  }

  
}
