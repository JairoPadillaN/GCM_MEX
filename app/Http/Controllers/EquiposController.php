<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use App\Exports\XdnExport;
use App\Exports\EquiposEmpresaExport;
use App\Exports\EquiposTallerExport;
use Maatwebsite\Excel\Facades\Excel;
use App\alcances;
use App\clientes;
use App\recepcionEquipos;
use App\usuarios;
use App\sucursales;
use App\tipoequipos;
use App\subtipoequipos;
use App\talleres;
use App\serviciostalleres;
use App\estatusReparaciones;
use App\marcaRefaccion;
use App\partesreparacion;
use App\serviciosreparacionpartes;
use App\refacciones;
use App\facturas;
use App\detallereparaciones;
use App\tiporefacciones;
use App\proveedores;
use App\partesVenta;
use App\refaccionesreparacionpartes;
use App\usuarios as User;
use DB;
use PDF;
use QrCode;
use Session;
use App\anexGrid;

class EquiposController extends Controller
{

       public function AltaEquipos(Request $request){

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


              $sidu = Session::get('sesionidu');
              // $cliente = clientes::orderby('razonSocial','asc')->get();
              $sucursal = sucursales::orderby('sucursal','asc')->get();
              $tipoEquipo = tipoequipos::orderby('tipoEquipo','asc')->get();
              $subtipoEquipo = subtipoequipos::orderby('subtipoEquipo','asc')->get();
              $marca = \DB::select("SELECT  DISTINCT r.idMarcaRefa, marcaRefaccion, mr.activo FROM refacciones AS r INNER JOIN marcasRefaccion AS mr
              ON r.idMarcaRefa=mr.idMarcaRefa
              WHERE r.tipoProducto = 'Para cambio'");


              $consulta= \DB::select("SELECT idEquipos FROM recepcionEquipos");
              $cuantos = count($consulta);

              if($cuantos==0){
                $idEqu = 1;
              }
              else
              {
                $idEqu = $cuantos+ 1;
              }


              $folio = \DB::select("SELECT Foliorec($idEqu) as f");

              $fv=$folio[0]->f;

              return view ('altaEquipos')
                ->with('sucursal',$sucursal)
                ->with('tipoEquipo',$tipoEquipo)
                ->with('folio',$fv)
                ->with('subtipoEquipo',$subtipoEquipo)
                ->with('marca',$marca)
                ->with('cliente',$cliente);
            }
        }

        function qrcodeEquipo(Request $request){
          $textoDelCampo = $request->textoQr;
          return QrCode::size(117)->generate($textoDelCampo);
        }

        function comboca1(Request $request)
        {
          $sidu = Session::get('sesionidu');
          $stipo = Session::get('sesiontipo');
            $idc = $request->get('idc');
            // $consulta = sucursales::where('idc','=',$idc)->get();

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

            return view ('com')
            ->with('sucursales',$sucursales);
        }

        function comboca2(Request $request)
        {
            $idTipoEquipo = $request->get('idTipoEquipo');
            $consulta = subtipoequipos::where('idTipoEquipo','=',$idTipoEquipo)->get();

            return view ('comEquipos')
            ->with('consulta',$consulta);
        }

        function generagcmid(Request $request)
        {
          $idp = $request->get('idp');
          $consultagcmid = \DB::select("SELECT gcmid FROM sucursales WHERE idSucursal=? ",[$idp]);
          $gcmid=$consultagcmid[0]->gcmid;

          // $consulta = \DB::select("SELECT CONCAT(c.razonSocial,'-',s.sucursal)AS cliSuc
          // FROM recepcionEquipos AS e
          // INNER JOIN sucursales AS s ON s.idSucursal= e.idSucursal
          // INNER JOIN clientes AS c ON c.idc= e.idc ");

        return view ('cajagcmid')
        ->with('gcmid2',$gcmid);
        // ->with('consulta',$consulta);
        }

        function info(Request $request){
          $idc = $request->idc;
          $idSucursal = $request->idSucursal;

          $cliente = \DB::Select("SELECT razonSocial FROM clientes WHERE idc =?",[$idc]);
          $NewCliente = $cliente[0]->razonSocial;

          $sucursal = \DB::Select("SELECT sucursal FROM sucursales WHERE idSucursal =?",[$idSucursal]);
          $NewSucursal = $sucursal[0]->sucursal;


          return "Cliente: ".$NewCliente." "."-------"." "."Sucursal: ".$NewSucursal;

        }

        function generafolio(Request $request)
        {
          $nombreEmpresa = $request->get('nombreEmpresa');

          $folioret = \DB::select("SELECT foliorecgcm('gcm') as f");
          $fv=$folioret[0]->f;

        return view ('cajafolio')
         ->with('folioret',$fv);
         }

        function generacym(Request $request) {
          $nombreEmpresa1 = $request->get('nombreEmpresa');

          $foliocym = \DB::select("SELECT foliorecgcm('cym') as f");
          $fv=$foliocym[0]->f;

          return view ('cajacym')
         ->with('foliocym',$fv);

        }

        function generasurja(Request $request) {
          $nombreEmpresa2 = $request->get('nombreEmpresa');

          $foliosurja = \DB::select("SELECT foliorecgcm('surja') as f");
          $fv=$foliosurja[0]->f;

          return view ('cajasurja')
         ->with('foliosurja',$fv);

        }

        public function GuardarEquipos(Request $request){
          $folioRecepcion = $request ->folioRecepcion;
          $idu = $request ->idu;
          $nombreEmpresa = $request ->nombreEmpresa;
          $usuarioEquipos = $request ->usuarioEquipos;
          $fechaRecoleccion = $request ->fechaRecoleccion;
          $fechaRegistro = $request ->fechaRegistro;
          $idc = $request ->idc;
          $idSucursal = $request ->idSucursal;
          $personaEntrega = $request ->personaEntrega;
          $personaRecibe = $request ->personaRecibe;
          $numeroDocumentoSalida = $request ->numeroDocumentoSalida;
          $GCMid = $request ->GCMid;
          $complementoGCMid = $request ->complementoGCMid;
          $idTipoEquipo = $request ->idTipoEquipo;
          $idSubtipoEquipo = $request ->idSubtipoEquipo;
          $serie = $request ->serie;
          $datosCertificado = $request ->datosCertificado;
          $marca = $request ->marca;
          $modelo = $request ->modelo;
          $estatus = $request ->estatus;
          $estatusEntrega = $request ->estatusEntrega;
          $recurso = $request ->recurso;
          $importancia = $request ->importancia;
          $descripcionFalla = $request ->descripcionFalla;
          $activo = $request ->activo;

          if($datosCertificado ==""){
            $datosCertificado = " ";
          }else{
            $datosCertificado;
          }

          if($recurso ==""){
            $recurso = " ";
          }else{
            $recurso;
          }

          // archivos
          $archivoValeSalida = $request ->archivoValeSalida;
          $archivoDocumentoSalida = $request ->archivoDocumentoSalida;
          $vistaSuperior = $request ->vistaSuperior;
          $vistaFrente = $request ->vistaFrente;
          $vistaTrasera = $request ->vistaTrasera;
          $lateralIzquierda = $request ->lateralIzquierda;
          $lateralDerecha = $request ->lateralDerecha;
          $placa_1 = $request ->placa_1;
          $placa_2 = $request ->placa_2;

          $this->validate ($request,[
              //  'fechaFactura'=>['required']

               //VALIDACION DE LOS ARCHVOS
               'achivoValeSalida'=>['mimes:jpeg,png,gif,jpg'],
               'archivoDocumentoSalida'=>['mimes:png,jpg,jpeg,gif,doc,docx,pdf'],
               'serie'=>['required'],
               'marca'=>['required'],
               'modelo'=>['required'],
              //  'recurso'=>['required'],
               'idc'=>['required'],
               'idSucursal'=>['required'],
               'estatus'=>['required'],
               'idTipoEquipo'=>['required'],
               'GCMid'=>['required'],
               'complementoGCMid'=>['required'],
               'personaEntrega'=>['required'],
               'personaRecibe'=>['required'],
               'idSubtipoEquipo'=>['required'],
               'importancia'=>['required'],
               'vistaSuperior'=>['mimes:jpeg,png,gif,jpg'],
               'vistaFrente'=>['mimes:jpeg,png,gif,jpg'],
               'vistaTrasera'=>['mimes:jpeg,png,gif,jpg'],
               'lateralIzquierda'=>['mimes:jpeg,png,gif,jpg'],
               'lateralDerecha'=>['mimes:jpeg,png,gif,jpg'],
               'placa_1'=>['mimes:jpeg,png,gif,jpg'],
               'placa_2'=>['mimes:jpeg,png,gif,jpg']
          ]);

        //QR
            $textoQrV = $request ->textoQr;
            if($textoQrV == ""){
              $textoQr="SinEtiquetaQr";
            }
            else{
              $textoQr = $request->textoQr;
            }
        // archivo vale salida
                $file = $request->file('archivoValeSalida');
              if($file!=""){
                $ldate = date('Ymd_His_');
                $img = $file->getClientOriginalName();
                $img2 = $ldate.$img;
                \Storage::disk('local')->put($img2, \File::get($file));
              }
              else{
                $img2 ="Sin archivo";
              }

        // archivo documento salida
                $file = $request->file('archivoDocumentoSalida');
              if($file!=""){
                $ldate = date('Ymd_His_');
                $img3 = $file->getClientOriginalName();
                $img4 = $ldate.$img3;
                \Storage::disk('local')->put($img4, \File::get($file));
              }
              else{
                $img4 ="Sin archivo";
              }

        // vista superior
                $file = $request->file('vistaSuperior');
              if($file!=""){
                $ldate = date('Ymd_His_');
                $img5 = $file->getClientOriginalName();
                $img6 = $ldate.$img5;
                \Storage::disk('local')->put($img6, \File::get($file));
              }
              else{
                $img6 ="Sin archivo";
              }

        // vista frente
                $file = $request->file('vistaFrente');
              if($file!=""){
                $ldate = date('Ymd_His_');
                $img7 = $file->getClientOriginalName();
                $img8 = $ldate.$img7;
                \Storage::disk('local')->put($img8, \File::get($file));
              }
              else{
                $img8 ="Sin archivo";
              }

        // vista trasera
                $file = $request->file('vistaTrasera');
              if($file!=""){
                $ldate = date('Ymd_His_');
                $img9 = $file->getClientOriginalName();
                $img10 = $ldate.$img9;
                \Storage::disk('local')->put($img10, \File::get($file));
              }
              else{
                $img10 ="Sin archivo";
              }
        // lateral izquierda
                $file = $request->file('lateralIzquierda');
              if($file!=""){
                $ldate = date('Ymd_His_');
                $img11 = $file->getClientOriginalName();
                $img12 = $ldate.$img11;
                \Storage::disk('local')->put($img12, \File::get($file));
              }
              else{
                $img12 ="Sin archivo";
              }

        // lateral derecha
                $file = $request->file('lateralDerecha');
              if($file!=""){
                $ldate = date('Ymd_His_');
                $img13 = $file->getClientOriginalName();
                $img14 = $ldate.$img13;
                \Storage::disk('local')->put($img14, \File::get($file));
              }
              else{
                $img14 ="Sin archivo";
              }

        // placa 1
                $file = $request->file('placa_1');
              if($file!=""){
                $ldate = date('Ymd_His_');
                $img15 = $file->getClientOriginalName();
                $img16 = $ldate.$img15;
                \Storage::disk('local')->put($img16, \File::get($file));
              }
              else{
                $img16 ="Sin archivo";
              }
        // placa 2
                $file = $request->file('placa_2');
              if($file!=""){
                $ldate = date('Ymd_His_');
                $img17 = $file->getClientOriginalName();
                $img18 = $ldate.$img17;
                \Storage::disk('local')->put($img18, \File::get($file));
              }
              else{
                $img18 ="Sin archivo";
              }

            $clavequesigue =\DB::select("SELECT idequipos FROM recepcionEquipos
			 ORDER BY idequipos desc limit 1");
			$sigue = $clavequesigue[0]->idequipos + 1;
            

  // return $datosCertificado;
            $equipos=new recepcionEquipos;
			$equipos->idEquipos = $sigue;
            $equipos-> folioRecepcion = $request-> folioRecepcion;
            $equipos-> idu = $request-> idu;
            $equipos-> nombreEmpresa = $request -> nombreEmpresa;
            $equipos-> usuarioEquipos = $request -> usuarioEquipos;
            $equipos-> fechaRecoleccion = $request-> fechaRecoleccion;
            $equipos-> fechaRegistro = $request-> fechaRegistro;
            $equipos-> idc = $request-> idc;
            $equipos-> idSucursal = $request-> idSucursal;
            $equipos-> personaEntrega = $request-> personaEntrega;
            $equipos-> personaRecibe = $request-> personaRecibe;
            $equipos-> numeroDocumentoSalida = $request-> numeroDocumentoSalida;
            $equipos-> GCMid = $request-> GCMid;
            $equipos-> complementoGCMid = $request -> complementoGCMid;
            $equipos-> idTipoEquipo = $request-> idTipoEquipo;
            $equipos-> idSubtipoEquipo = $request-> idSubtipoEquipo;
            $equipos-> serie = $request-> serie;
            $equipos-> ubicacionEquipo = $request-> ubicacionEquipo;
            $equipos-> datosCertificado = $datosCertificado;
            
            // variables refaccion para cambio
            $equipos-> cambio = $request-> cambio;
            $equipos-> idRefaccion = $request-> idRefaccion;

            // $equipos-> servicio = $request-> servicio;
            $equipos-> marca = $request-> marca;
            $equipos-> modelo = $request-> modelo;
            $equipos-> estatus = $request-> estatus;
            $equipos-> estatusEntrega = $request-> estatusEntrega;
            $equipos-> recurso = $recurso;
            $equipos-> importancia = $request-> importancia;
            $equipos-> descripcionFalla = $request-> descripcionFalla;
            $equipos-> textoQr = $textoQr;
            $equipos-> seguimiento = $request-> seguimiento;
            
            $equipos-> activo = 'Si';


            // archivos
            $equipos-> archivoValeSalida=$img2;
            $equipos-> archivoDocumentoSalida = $img4;
            $equipos-> vistaSuperior = $img6;
            $equipos-> vistaFrente = $img8;
            $equipos-> vistaTrasera = $img10;
            $equipos-> lateralIzquierda = $img12;
            $equipos-> lateralDerecha = $img14;
            $equipos-> placa_1 = $img16;
            $equipos-> placa_2 = $img18;

            $equipos -> save();

            $proceso='Registro exitoso del equipo';
            $mensaje="Equipo registrado correctamente";
            return view ('mensajeEquipos')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);

      }
      public function reporteEquipos()
      {
          $sidu = Session::get('sesionidu');
          $stipo = Session::get('sesiontipo');

          $aCargo = \DB::select("SELECT iduAsignado FROM usuariosacargos WHERE idu =$sidu");
      if (count($aCargo) > 0) {
        for ($i=0; $i < count($aCargo) ; $i++) {
          $usuariosReporte[$i] = $aCargo[$i]->iduAsignado;
        }
        array_push($usuariosReporte,$sidu);
      }else{
        $usuariosReporte[0]=$sidu;
      }
          $consultaEmpesa = \DB::select("SELECT empresaSeguimiento FROM usuarios WHERE idu = $sidu"); //WHERE idu = ?,[$sidu]
        $seguimiento=$consultaEmpesa[0]->empresaSeguimiento;

          if( $seguimiento == "GCMyCYM" || $stipo == 'Administrador')
          {
              $usuario = User::orderby( 'nombreUsuario', 'asc' )->get();

              $query = DB::table( 'recepcionEquipos as r' )
                          ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                          ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                          ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                          ->where('seguimiento', '=','Si')
                          ->where('ubicacionEquipo', '=','En almacen')
                          ->orWhereNull('seguimiento')
                          ->select(
                              'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                              'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo','r.equipoActivo',
                              
                              DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                              DB::raw(' COUNT( p.idParte ) as canti '),
                              DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                              DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                              DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                              DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
                              DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                          )
                          ->groupBy(
                                      'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
                                      'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                      'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                      'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                          )
                          ->orderBy( 'r.idEquipos', 'DESC' )
                          ->get();

              $query2 = DB::table( 'recepcionEquipos as r' )
                          ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                          ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                          ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                          ->where('seguimiento', '=','Si')
                          ->where('ubicacionEquipo', '=','Cotizado en sitio')
                          ->select(
                              'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                              'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo','r.equipoActivo',
                              
                              DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                              DB::raw(' COUNT( p.idParte ) as canti '),
                              DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                              DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                              DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                              DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
                              DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                          )
                          ->groupBy(
                                      'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
                                      'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                      'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                      'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                          )
                          ->orderBy( 'r.idEquipos', 'DESC' )
                          ->get();

              $query3 = DB::table( 'recepcionEquipos as r' )
                          ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                          ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                          ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                          ->where('seguimiento', '=','No')
                          
                          ->select(
                              'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                              'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo','r.equipoActivo',
                              
                              DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                              DB::raw(' COUNT( p.idParte ) as canti '),
                              DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                              DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                              DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                              DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
                              DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                          )
                          ->groupBy(
                                      'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
                                      'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                      'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                      'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                          )
                          ->orderBy( 'r.idEquipos', 'DESC' )
                          ->get();

              $consulta = $this->json($query);
              $consulta2 = $this->json2($query2);
              $consulta3 = $this->jsonSeguimiento($query3);

              $clientes = DB::table( 'recepcionEquipos as r' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->select(
                              'r.idc', 'c.razonSocial'
                          )
                          ->groupBy( 'r.idc' )
                          ->orderBy( 'c.razonSocial', 'ASC' )
                          ->get();

              return view( 'reporteEquipos', compact( 'usuario', 'consulta','consulta2','consulta3', 'clientes' ) );

          }else{
              $usuario = User::orderby( 'nombreUsuario', 'asc' )->get();

              $query = DB::table( 'recepcionEquipos as r' )
                          ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                          ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                          ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                          ->where('seguimiento', '=','Si')
                          ->where('ubicacionEquipo', '=','En almacen')
                          ->whereIn('r.idu',$usuariosReporte)
                          // ->where('r.idu', '=',$sidu)
                          ->orWhereNull('seguimiento')
                          
                          ->select(
                              'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                              'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo','r.equipoActivo',
                              
                              DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                              DB::raw(' COUNT( p.idParte ) as canti '),
                              DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                              DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                              DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                              DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
                              DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                          )
                          
                          ->groupBy(
                                      'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
                                      'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                      'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                      'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                          )
                          ->orderBy( 'r.idEquipos', 'DESC' )
                          ->get();

              $query2 = DB::table( 'recepcionEquipos as r' )
                          ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                          ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                          ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                          ->where('seguimiento', '=','Si')
                          ->where('ubicacionEquipo', '=','Cotizado en sitio')
                          // ->where('r.idu', '=',$sidu)
                          ->whereIn('r.idu',$usuariosReporte)
                          ->select(
                              'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                              'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo','r.equipoActivo',
                              
                              DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                              DB::raw(' COUNT( p.idParte ) as canti '),
                              DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                              DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                              DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                              DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
                              DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                          )
                          ->groupBy(
                                      'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
                                      'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                      'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                      'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                          )
                          ->orderBy( 'r.idEquipos', 'DESC' )
                          ->get();

              $query3 = DB::table( 'recepcionEquipos as r' )
                          ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                          ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                          ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                          ->where('seguimiento', '=','No')
                          ->whereIn('r.idu',$usuariosReporte)
                          // ->where('r.idu', '=',$sidu)
                          ->select(
                              'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                              'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo','r.equipoActivo',
                              
                              DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                              DB::raw(' COUNT( p.idParte ) as canti '),
                              DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                              DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                              DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                              DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
                              DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                          )
                          ->groupBy(
                                      'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
                                      'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                      'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                      'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                          )
                          ->orderBy( 'r.idEquipos', 'DESC' )
                          ->get();

              $consulta = $this->json($query);
              $consulta2 = $this->json2($query2);
              $consulta3 = $this->jsonSeguimiento($query3);

              $clientes = DB::table( 'recepcionEquipos as r' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->select(
                              'r.idc', 'c.razonSocial'
                          )
                          ->groupBy( 'r.idc' )
                          ->orderBy( 'c.razonSocial', 'ASC' )
                          ->get();

              return view( 'reporteEquipos', compact( 'usuario', 'consulta','consulta2','consulta3', 'clientes' ) );

          }
     
    }

      public function reporteEquiposAbajo(Request $request)
      {
          $fechaInicio=$request->fechaInicio;
          $fechaFin=$request->fechaFin;
          $sidu = Session::get('sesionidu');
          $stipo = Session::get('sesiontipo');
          $consultaEmpresa = User::select('empresaSeguimiento')->find($sidu);

          $aCargo = \DB::select("SELECT iduAsignado FROM usuariosacargos WHERE idu =$sidu");
          if (count($aCargo) > 0) {
            for ($i=0; $i < count($aCargo) ; $i++) {
              $usuariosReporte[$i] = $aCargo[$i]->iduAsignado;
            }
            array_push($usuariosReporte,$sidu);
          }else{
            $usuariosReporte[0]=$sidu;
          }


          if( $consultaEmpresa->empresaSeguimiento === "GCMyCYM" || $stipo == 'Administrador')
          {
              $usuario = User::orderby( 'nombreUsuario', 'asc' )->get();

              $query = DB::table( 'recepcionEquipos as r' )
                          ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                          ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                          ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                          ->where('seguimiento', '=','Si')
                          ->where('ubicacionEquipo', '=','En almacen')
                          ->orWhereNull('seguimiento')
                          ->where('fechaRegistro','>=',$fechaInicio)
                          ->where('fechaRegistro','<=',$fechaFin)
                          ->select(
                              'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                              'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo','r.equipoActivo',
                              DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                              DB::raw(' COUNT( p.idParte ) as canti '),
                              DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                              DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                              DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                              DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
                              DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                          )
                          
                          ->groupBy(
                                      'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
                                      'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                      'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                      'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                          )
                          ->orderBy( 'r.idEquipos', 'DESC' )
                          ->get();

              $query2 = DB::table( 'recepcionEquipos as r' )
                          ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                          ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                          ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                          ->where('seguimiento', '=','Si')
                          ->where('ubicacionEquipo', '=','Cotizado en sitio')
                          ->select(
                              'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                              'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo','r.equipoActivo',
                              DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                              DB::raw(' COUNT( p.idParte ) as canti '),
                              DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                              DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                              DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                              DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
                              DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                          )
                          ->where('fechaRegistro','>=',$fechaInicio)
                          ->where('fechaRegistro','<=',$fechaFin)
                          ->groupBy(
                                      'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
                                      'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                      'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                      'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                          )
                          ->orderBy( 'r.idEquipos', 'DESC' )
                          ->get();
              $query3 = DB::table( 'recepcionEquipos as r' )
                          ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                          ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                          ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                          ->where('seguimiento', '=','No')
                
                          ->select(
                              'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                              'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo','r.equipoActivo',
                              DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                              DB::raw(' COUNT( p.idParte ) as canti '),
                              DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                              DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                              DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                              DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
                              DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                          )
                          ->where('fechaRegistro','>=',$fechaInicio)
                          ->where('fechaRegistro','<=',$fechaFin)
                          ->groupBy(
                                      'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
                                      'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                      'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                      'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                          )
                          ->orderBy( 'r.idEquipos', 'DESC' )
                          ->get();

              $consulta = $this->json($query);
              $consulta2 = $this->json2($query2);
              $consulta3 = $this->jsonSeguimiento($query3);

              $clientes = DB::table( 'recepcionEquipos as r' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->select(
                              'r.idc', 'c.razonSocial'
                          )
                          ->groupBy( 'r.idc' )
                          ->orderBy( 'c.razonSocial', 'ASC' )
                          ->get();

              return view( 'reporteEquiposAbajo', compact( 'usuario', 'consulta','consulta2','consulta3', 'clientes' ) );

          }else{
              $usuario = User::orderby( 'nombreUsuario', 'asc' )->get();

              $query = DB::table( 'recepcionEquipos as r' )
                          ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                          ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                          ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                          ->where('seguimiento', '=','Si')
                          ->where('ubicacionEquipo', '=','En almacen')
                          ->whereIn('r.idu',$usuariosReporte)
                          // ->where('r.idu', '=',$sidu)
                          ->orWhereNull('seguimiento')
                          ->where('fechaRegistro','>=',$fechaInicio)
                          ->where('fechaRegistro','<=',$fechaFin)
                          ->select(
                              'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                              'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo','r.equipoActivo',
                              DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                              DB::raw(' COUNT( p.idParte ) as canti '),
                              DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                              DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                              DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                              DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
                              DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                          )
                          
                          ->groupBy(
                                      'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
                                      'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                      'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                      'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                          )
                          ->orderBy( 'r.idEquipos', 'DESC' )
                          ->get();

              $query2 = DB::table( 'recepcionEquipos as r' )
                          ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                          ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                          ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                          ->where('seguimiento', '=','Si')
                          ->where('ubicacionEquipo', '=','Cotizado en sitio')
                          ->whereIn('r.idu',$usuariosReporte)
                          // ->where('r.idu', '=',$sidu)
                          ->select(
                              'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                              'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo','r.equipoActivo',
                              DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                              DB::raw(' COUNT( p.idParte ) as canti '),
                              DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                              DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                              DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                              DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
                              DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                          )
                          ->where('fechaRegistro','>=',$fechaInicio)
                          ->where('fechaRegistro','<=',$fechaFin)
                          ->groupBy(
                                      'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
                                      'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                      'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                      'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                          )
                          ->orderBy( 'r.idEquipos', 'DESC' )
                          ->get();
              $query3 = DB::table( 'recepcionEquipos as r' )
                          ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                          ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                          ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                          ->where('seguimiento', '=','No')
                          ->whereIn('r.idu',$usuariosReporte)
                          // ->where('r.idu', '=',$sidu)
                          ->select(
                              'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                              'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo','r.equipoActivo',
                              DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                              DB::raw(' COUNT( p.idParte ) as canti '),
                              DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                              DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                              DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                              DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
                              DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                          )
                          ->where('fechaRegistro','>=',$fechaInicio)
                          ->where('fechaRegistro','<=',$fechaFin)
                          ->groupBy(
                                      'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
                                      'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                      'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                      'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                          )
                          ->orderBy( 'r.idEquipos', 'DESC' )
                          ->get();

              $consulta = $this->json($query);
              $consulta2 = $this->json2($query2);
              $consulta3 = $this->jsonSeguimiento($query3);

              $clientes = DB::table( 'recepcionEquipos as r' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->select(
                              'r.idc', 'c.razonSocial'
                          )
                          ->groupBy( 'r.idc' )
                          ->orderBy( 'c.razonSocial', 'ASC' )
                          ->get();

              return view( 'reporteEquiposAbajo', compact( 'usuario', 'consulta','consulta2','consulta3', 'clientes' ) );

          }

      }

      public function reportePorEmpresa()
      {

          $sidu = Session::get('sesionidu');
          $stipo = Session::get('sesiontipo');
          $consultaEmpesa = \DB::select("SELECT empresaSeguimiento FROM usuarios WHERE idu = $sidu"); //WHERE idu = ?,[$sidu]
        $seguimiento=$consultaEmpesa[0]->empresaSeguimiento;
        $consultaEmpresa = User::select('empresaSeguimiento')->find($sidu);

        $aCargo = \DB::select("SELECT iduAsignado FROM usuariosacargos WHERE idu =$sidu");
        if (count($aCargo) > 0) {
          for ($i=0; $i < count($aCargo) ; $i++) {
            $usuariosReporte[$i] = $aCargo[$i]->iduAsignado;
          }
          array_push($usuariosReporte,$sidu);
        }else{
          $usuariosReporte[0]=$sidu;
        }

          if( $seguimiento == "GCMyCYM" || $stipo == 'Administrador')
          {
              $usuario = User::orderby( 'nombreUsuario', 'asc' )->get();

              $query = DB::table( 'recepcionEquipos as r' )
                          ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                          ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                          ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                          ->select(
                              'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                              'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo','r.equipoActivo',
                              DB::raw(' SUBSTR( r.datosCertificado, 1, 100) as datos '),
                              DB::raw(' COUNT( p.idParte ) as canti '),
                              DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                              DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                              DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                              DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
                              DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                          )
                          
                          ->groupBy(
                                      'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
                                      'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                      'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                      'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                          )
                          ->orderBy( 'r.idEquipos', 'DESC' )
                          ->get();

              $consulta = $this->jsonEmpresa($query);

              $clientes = DB::table( 'recepcionEquipos as r' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->select(
                              'r.idc', 'c.razonSocial'
                          )
                          ->groupBy( 'r.idc' )
                          ->orderBy( 'c.razonSocial', 'ASC' )
                          ->get();

              return view( 'reporteGCM', compact( 'usuario', 'consulta', 'clientes' ) );

          }else{
              $usuario = User::orderby( 'nombreUsuario', 'asc' )->get();

              $query = DB::table( 'recepcionEquipos as r' )
                          ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                          ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                          ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                          // ->where('r.idu', '=',$sidu)
                          ->whereIn('r.idu',$usuariosReporte)
                          ->select(
                              'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                              'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo','r.equipoActivo',
                              DB::raw(' SUBSTR( r.datosCertificado, 1, 100) as datos '),
                              DB::raw(' COUNT( p.idParte ) as canti '),
                              DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                              DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                              DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                              DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
                              DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                          )
                          ->groupBy(
                                      'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
                                      'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                      'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                      'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                          )
                          ->orderBy( 'r.idEquipos', 'DESC' )
                          ->get();

              $consulta = $this->jsonEmpresa($query);

              $clientes = DB::table( 'recepcionEquipos as r' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->select(
                              'r.idc', 'c.razonSocial'
                          )
                          ->groupBy( 'r.idc' )
                          ->orderBy( 'c.razonSocial', 'ASC' )
                          ->get();

              return view( 'reporteGCM', compact( 'usuario', 'consulta', 'clientes' ) );

          }

      }

  public function dataReportePorEmpresa(){

    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');
    $consultaEmpesa = \DB::select("SELECT empresaSeguimiento FROM usuarios WHERE idu = $sidu"); //WHERE idu = ?,[$sidu]
    $seguimiento=$consultaEmpesa[0]->empresaSeguimiento;
    $consultaEmpresa = User::select('empresaSeguimiento')->find($sidu);

    $aCargo = \DB::select("SELECT iduAsignado FROM usuariosacargos WHERE idu =$sidu");
    if (count($aCargo) > 0) {
      for ($i=0; $i < count($aCargo) ; $i++) {
        $usuariosReporte[$i] = $aCargo[$i]->iduAsignado;
      }
      array_push($usuariosReporte,$sidu);
    }else{
      $usuariosReporte[0]=$sidu;
    }
    
    if( $seguimiento == "GCMyCYM" || $stipo == 'Administrador'){
      $consulta = DB::table( 'recepcionEquipos as r' )
        ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
        ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
        ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
        ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
        ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
        ->select(
            'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
            'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo','r.equipoActivo',
            DB::raw(' SUBSTR( r.datosCertificado, 1, 100) as datos '),
            DB::raw(' COUNT( p.idParte ) as canti '),
            DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
            DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
            DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
            DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
            DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
        )
        ->groupBy(
          'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
          'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
          'r.estatus', 'r.importancia', 'datos', 'gcmid',
          'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
        )
        ->orderBy( 'r.idEquipos', 'DESC' )
      ->get();

    }else{
      $consulta = DB::table( 'recepcionEquipos as r' )
        ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
        ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
        ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
        ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
        ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
        // ->where('r.idu', '=',$sidu)
        ->whereIn('r.idu',$usuariosReporte)
        ->select(
            'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
            'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo','r.equipoActivo',
            DB::raw(' SUBSTR( r.datosCertificado, 1, 100) as datos '),
            DB::raw(' COUNT( p.idParte ) as canti '),
            DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
            DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
            DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
            DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
            DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
        )
        ->groupBy(
          'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
          'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
          'r.estatus', 'r.importancia', 'datos', 'gcmid',
          'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
        )
        ->orderBy( 'r.idEquipos', 'DESC' )
      ->get();

    }
    
    return response()->json($consulta, 200);
  }

      public function reportePorEmpresaAbajo(Request $request)
      {
          $fechaInicio=$request->fechaInicio;
          $fechaFin=$request->fechaFin;
          $sidu = Session::get('sesionidu');
          $stipo = Session::get('sesiontipo');
          $consultaEmpresa = User::select('empresaSeguimiento')->find($sidu);

          $aCargo = \DB::select("SELECT iduAsignado FROM usuariosacargos WHERE idu =$sidu");
          if (count($aCargo) > 0) {
            for ($i=0; $i < count($aCargo) ; $i++) {
              $usuariosReporte[$i] = $aCargo[$i]->iduAsignado;
            }
            array_push($usuariosReporte,$sidu);
          }else{
            $usuariosReporte[0]=$sidu;
          }

          if( $consultaEmpresa->empresaSeguimiento === "GCMyCYM" || $stipo == 'Administrador' )
          {
              $usuario = User::orderby( 'nombreUsuario', 'asc' )->get();

              $query = DB::table( 'recepcionEquipos as r' )
                          ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                          ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                          ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                          ->select(
                              'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                              'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','r.equipoActivo',
                              DB::raw(' SUBSTR( r.datosCertificado, 1, 100) as datos '),
                              DB::raw(' COUNT( p.idParte ) as canti '),
                              DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                              DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                              DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                              DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
                              DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                          )
                          ->where('fechaRegistro','>=',$fechaInicio)
                          ->where('fechaRegistro','<=',$fechaFin)
                          ->groupBy(
                                      'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
                                      'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                      'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                      'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                          )
                          ->orderBy( 'r.idEquipos', 'DESC' )
                          ->get();

              $consulta = $this->jsonEmpresa($query);

              $clientes = DB::table( 'recepcionEquipos as r' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->select(
                              'r.idc', 'c.razonSocial'
                          )
                          ->groupBy( 'r.idc' )
                          ->orderBy( 'c.razonSocial', 'ASC' )
                          ->get();

              return view( 'reportePorEmpresaAbajo', compact( 'usuario', 'consulta', 'clientes' ) );

          }else{

            $usuario = User::orderBy('nombreUsuario','asc')->get();

            $empresa = User::select( 'empresaSeguimiento' )->find( $sidu );

            $query = DB::table( 'recepcionEquipos as r' )
                          ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                          ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                          ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                          // ->where('r.idu', '=',$sidu)
                          ->whereIn('r.idu',$usuariosReporte)
                          ->select(
                              'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                              'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','r.equipoActivo',
                              DB::raw(' SUBSTR( r.datosCertificado, 1, 100) as datos '),
                              DB::raw(' COUNT( p.idParte ) as canti '),
                              DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                              DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                              DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                              DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '),
                              DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                          )
                          ->whereIn( 'r.nombreEmpresa', [$empresa->empresaSeguimiento] )
                          ->where('fechaRegistro','>=',$fechaInicio)
                          ->where('fechaRegistro','<=',$fechaFin)
                          ->groupBy(
                                      'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior',
                                      'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                      'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                      'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                          )
                          ->orderBy( 'r.idEquipos', 'DESC' )
                          ->get();


              $consulta =  $this->jsonEmpresa( $query ); // Json Estructura

              $clientes = DB::table( 'recepcionEquipos as r' )
                          ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                          ->select(
                              'r.idc', 'c.razonSocial'
                          )
                          ->groupBy( 'r.idc' )
                          ->orderBy( 'c.razonSocial', 'ASC' )
                          ->get();

              return view( 'reportePorEmpresaAbajo', compact( 'usuario', 'consulta', 'clientes' ) );

          }
    }

      public function json ( $query)
      {
          $sidu = Session::get('sesionidu');
          $stipo = Session::get('sesiontipo');
          function val ( $value )
          {

              $val = ( $value == 'Sin archivo' || $value == null || $value == '' ) ? '' : asset( 'archivos/'.$value );

              $image = ( \File::exists('archivos/'.$value) ) ? str_replace(' ', '%20', $val) : '';

              return $image;
          }

          function import( $value )
          {
              $alta = asset('archivos/circulo-rojo-png-4.png');
              $media = asset('archivos/circulo-amarillo.png');
              $baja = asset('archivos/archivos/circulo-verde.png');

              if( $value == 'Alta' )
              {
                  $val = $alta;
              }
              if( $value == 'Media' )
              {
                  $val = $media;
              }
              if( $value == 'Baja' )
              {
                  $val = $baja;
              }

              return $val;
          }

          function options( $value, $value2, $value3 )
          {
            $sidu = Session::get('sesionidu');
            $stipo = Session::get('sesiontipo');
            
              if($stipo=='Administrador'){
                $btn = "<a href=".url("/restaurarEquipos/{$value2}")." type='submit' class='btn btn-xs btn-success'>Restaurar</a>";

                if( $value == 'Si' && $value3 == 'Cerrado')
                {
                  
                    $btn = "<a href='".url("/dividirEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-warning'><span class='glyphicon glyphicon-wrench'></span> Dividir en partes</a><br>".
                          "<a href='".url("/eliminarEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-danger'><i class='ace-icon fa fa-trash-o bigger-120'> Eliminar</i></a><br>".
                          "<a href='".url("/activarEquipo/{$value2}")."' type='submit' class='btn btn-xs btn-success'><i class='fa fa-check'>Activar</i></a><br>".
                          "<a href='".url("/modificarEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-info'><i class='ace-icon fa fa-pencil bigger-120'> Editar</i></a>";
                }else{
                  $btn = "<a href='".url("/dividirEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-warning'><span class='glyphicon glyphicon-wrench'></span> Dividir en partes</a><br>".
                          "<a href='".url("/eliminarEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-danger'><i class='ace-icon fa fa-trash-o bigger-120'> Eliminar</i></a><br>".
                          "<a href='".url("/cerrarEquipo/{$value2}")."' type='submit' class='btn btn-xs btn-danger'><i class='fa fa-remove'>Cerrar</i></a><br>".
                          "<a href='".url("/modificarEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-info'><i class='ace-icon fa fa-pencil bigger-120'> Editar</i></a>";

                }

                return $btn;
              }else{
                $btn = "<a href=".url("/restaurarEquipos/{$value2}")." type='submit' class='btn btn-xs btn-success'>Restaurar</a>";

                if( $value == 'Si' && $value3 == 'Cerrado')
                {
                  
                    $btn = "<a href='".url("/dividirEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-warning'><span class='glyphicon glyphicon-wrench'></span> Dividir en partes</a><br>".
                          "<a href='".url("/eliminarEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-danger'><i class='ace-icon fa fa-trash-o bigger-120'> Eliminar</i></a><br>".
                          
                          "<a href='".url("/modificarEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-info'><i class='ace-icon fa fa-pencil bigger-120'> Editar</i></a>";
                }else{
                  $btn = "<a href='".url("/dividirEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-warning'><span class='glyphicon glyphicon-wrench'></span> Dividir en partes</a><br>".
                          "<a href='".url("/eliminarEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-danger'><i class='ace-icon fa fa-trash-o bigger-120'> Eliminar</i></a><br>".
                          
                          "<a href='".url("/modificarEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-info'><i class='ace-icon fa fa-pencil bigger-120'> Editar</i></a>";

                }

                return $btn;
              }
          }


          $consult = array();

          foreach( $query as $value)
          {
            array_push( $consult, array(
              'id'    => $value->idEquipos,
                  'a'     => val( $value->vistaSuperior ),
                  'b'     => val( $value->vistaFrente ),
                  'c'     => val( $value->placa_1 ),
                  'd'     => $value->folioRecepcion,
                  'e'     => $value->gcmid,
                  'f'     => $value->fecha,
                  'g'     => $value->cliSuc,
                  'h'     => $value->datos,
                  'i'     => $value->equipo,
                  'j'     => $value->marcaModelo,
                  'k'     => import( $value->importancia ),
                  'l'     => $value->estatus,
                  'm'     => $value->estatusEntrega,
                  'n'     => $value->canti,
                  'o'     => options( $value->activo, $value->idEquipos, $value->equipoActivo),
                  'p'     => $value->personaEntrega,
                  'q'     => $value->ubicacionEquipo,
              ) );

          }

          return $consult;
      }
      public function json2 ( $query2 )
      {
          function val2 ( $value )
          {

              $val = ( $value == 'Sin archivo' || $value == null || $value == '' ) ? '' : asset( 'archivos/'.$value );

              $image = ( \File::exists('archivos/'.$value) ) ? str_replace(' ', '%20', $val) : '';

              return $image;
          }

          function import2( $value )
          {
              $alta = asset('archivos/circulo-rojo-png-4.png');
              $media = asset('archivos/circulo-amarillo.png');
              $baja = asset('archivos/archivos/circulo-verde.png');

              if( $value == 'Alta' )
              {
                  $val = $alta;
              }
              if( $value == 'Media' )
              {
                  $val = $media;
              }
              if( $value == 'Baja' )
              {
                  $val = $baja;
              }

              return $val;
          }

          function options2( $value, $value2 )
          {

              $btn = "<a href=".url("/restaurarEquipos/{$value2}")." type='submit' class='btn btn-xs btn-success'>Restaurar</a>";

              if( $value === 'Si' )
              {
                  $btn = "<a href='".url("/dividirEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-warnin'><span class='glyphicon glyphicon-wrench'></span> Dividir en partes</a><br>".
                         "<a href='".url("/eliminarEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-danger'><i class='ace-icon fa fa-trash-o bigger-120'> Eliminar</i></a><br>".
                         "<a href='".url("/modificarEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-info'><i class='ace-icon fa fa-pencil bigger-120'> Editar</i></a>";
              }

              return $btn;
          }


          $consult = array();

          foreach( $query2 as $value )
          {
            array_push( $consult, array(
              'id'    => $value->idEquipos,
                  'a'     => val( $value->vistaSuperior ),
                  'b'     => val( $value->vistaFrente ),
                  'c'     => val( $value->placa_1 ),
                  'd'     => $value->folioRecepcion,
                  'e'     => $value->gcmid,
                  'f'     => $value->fecha,
                  'g'     => $value->cliSuc,
                  'h'     => $value->datos,
                  'i'     => $value->equipo,
                  'j'     => $value->marcaModelo,
                  'k'     => import( $value->importancia ),
                  'l'     => $value->estatus,
                  'm'     => $value->estatusEntrega,
                  'n'     => $value->canti,
                  'o'     => options2( $value->activo, $value->idEquipos ),
                  'p'     => $value->personaEntrega,
                  'q'     => $value->ubicacionEquipo,
              ) );

          }

          return $consult;
      }
      public function jsonSeguimiento ( $query3 )
      {
          function val3 ( $value )
          {

              $val = ( $value == 'Sin archivo' || $value == null || $value == '' ) ? '' : asset( 'archivos/'.$value );

              $image = ( \File::exists('archivos/'.$value) ) ? str_replace(' ', '%20', $val) : '';

              return $image;
          }

          function import3( $value )
          {
              $alta = asset('archivos/circulo-rojo-png-4.png');
              $media = asset('archivos/circulo-amarillo.png');
              $baja = asset('archivos/archivos/circulo-verde.png');

              if( $value == 'Alta' )
              {
                  $val = $alta;
              }
              if( $value == 'Media' )
              {
                  $val = $media;
              }
              if( $value == 'Baja' )
              {
                  $val = $baja;
              }

              return $val;
          }

          function options3( $value, $value2 )
          {

              $btn = "<a href=".url("/restaurarEquipos/{$value2}")." type='submit' class='btn btn-xs btn-success'>Restaurar</a>";

              if( $value === 'Si' )
              {
                  $btn = "<a href='".url("/dividirEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-warnin'><span class='glyphicon glyphicon-wrench'></span> Dividir en partes</a><br>".
                         "<a href='".url("/eliminarEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-danger'><i class='ace-icon fa fa-trash-o bigger-120'> Eliminar</i></a><br>".
                         "<a href='".url("/modificarEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-info'><i class='ace-icon fa fa-pencil bigger-120'> Editar</i></a>";
              }

              return $btn;
          }


          $consult = array();

          foreach( $query3 as $value )
          {
            array_push( $consult, array(
              'id'    => $value->idEquipos,
                  'a'     => val( $value->vistaSuperior ),
                  'b'     => val( $value->vistaFrente ),
                  'c'     => val( $value->placa_1 ),
                  'd'     => $value->folioRecepcion,
                  'e'     => $value->gcmid,
                  'f'     => $value->fecha,
                  'g'     => $value->cliSuc,
                  'h'     => $value->datos,
                  'i'     => $value->equipo,
                  'j'     => $value->marcaModelo,
                  'k'     => import( $value->importancia ),
                  'l'     => $value->estatus,
                  'm'     => $value->estatusEntrega,
                  'n'     => $value->canti,
                  'o'     => options3( $value->activo, $value->idEquipos ),
                  'p'     => $value->personaEntrega,
                  'q'     => $value->ubicacionEquipo,
              ) );

          }

          return $consult;
      }
      public function jsonEmpresa ( $query )
      {
          function val ( $value )
          {

              $val = ( $value == 'Sin archivo' || $value == null || $value == '' ) ? '' : asset( 'public/archivos/'.$value );

              $image = ( \File::exists('public/archivos/'.$value) ) ? str_replace(' ', '%20', $val) : '';

              return $image;
          }

          function import( $value )
          {
              $alta = asset('public/archivos/circulo-rojo-png-4.png');
              $media = asset('public/archivos/circulo-amarillo.png');
              $baja = asset('public/archivos/archivos/circulo-verde.png');

              if( $value == 'Alta' )
              {
                  $val = $alta;
              }
              if( $value == 'Media' )
              {
                  $val = $media;
              }
              if( $value == 'Baja' )
              {
                  $val = $baja;
              }

              return $val;
          }

          function options( $value, $value2 )
          {

              $btn = "<a href=".url("/restaurarEquipos/{$value2}")." type='submit' class='btn btn-xs btn-success'>Restaurar</a>";

              if( $value === 'Si' )
              {
                  $btn = "<a href='".url("/detallePorEmpresa/{$value2}")."' type='submit' class='btn btn-xs btn-danger'>Detalle del equipo</i></a>";
              }

              return $btn;
          }


          $consult = array();

          foreach( $query as $value )
          {
            array_push( $consult, array(
              'id'    => $value->idEquipos,
                  'a'     => val( $value->vistaSuperior ),
                  'b'     => val( $value->vistaFrente ),
                  'c'     => val( $value->placa_1 ),
                  'd'     => $value->folioRecepcion,
                  'e'     => $value->gcmid,
                  'f'     => $value->fecha,
                  'g'     => $value->cliSuc,
                  'h'     => $value->datos,
                  'i'     => $value->equipo,
                  'j'     => $value->marcaModelo,
                  'k'     => import( $value->importancia ),
                  'l'     => $value->estatus,
                  'm'     => $value->estatusEntrega,
                  'n'     => $value->canti,
                  'o'     => options( $value->activo, $value->idEquipos ),
                  'p'     => $value->personaEntrega,
              ) );

          }

          return $consult;
      }

      public function excelEquipos ( Request $request )
      {
          return Excel::download( new XdnExport( $request->data, $request->user ), 'reporte.xlsx' );
      }

      public function excelEquiposEmpresa ( Request $request )
      {
          return Excel::download( new EquiposEmpresaExport( $request->data, $request->user ), 'reporte_por_empresa.xlsx' );
      }
      public function excelTaller ( Request $request )
      {
          return Excel::download( new EquiposTallerExport( $request->data, $request->user ), 'reporte_taller.xlsx' );
      }

      public function reporteGCM(Request $request){
          $consulta = \DB::select("SELECT e.idEquipos,e.folioRecepcion,e.vistaSuperior,e.vistaFrente,e.textoQr,e.placa_1,e.estatus,e.importancia,SUBSTR(datosCertificado,1,100) as datos,
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
          LEFT JOIN partesreparacion ON partesreparacion.`idEquipos`= e.`idEquipos`
          WHERE nombreEmpresa='gcm'
          GROUP BY e.idEquipos, e.folioRecepcion, e.vistaSuperior,e.vistaFrente,e.placa_1, e.idu,e.textoQr,e.estatus,e.importancia,datos,gcmid,
          cliSuc,e.personaEntrega,equipo,marcaModelo,fecha, e.activo
          ORDER BY e.folioRecepcion DESC");

        $cuantos = count($consulta);
        return view('reporteEquiposAbajo')
        ->with('consulta',$consulta)
        ->with('cuantos',$cuantos);
      }
      public function reporteCYM(Request $request){
        $consulta = \DB::select("SELECT e.idEquipos,e.folioRecepcion,e.vistaSuperior,e.vistaFrente,e.textoQr,e.placa_1,e.estatus,e.importancia,SUBSTR(datosCertificado,1,100) as datos,
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
        LEFT JOIN partesreparacion ON partesreparacion.`idEquipos`= e.`idEquipos`
        WHERE nombreEmpresa='cym'
        GROUP BY e.idEquipos, e.folioRecepcion, e.vistaSuperior,e.vistaFrente,e.placa_1, e.idu,e.textoQr,e.estatus,e.importancia,datos,gcmid,
        cliSuc,e.personaEntrega,equipo,marcaModelo,fecha, e.activo
        ORDER BY e.folioRecepcion DESC");

        $cuantos = count($consulta);
        return view('reporteEquiposAbajo')
        ->with('consulta',$consulta)
        ->with('cuantos',$cuantos);
    }
    public function reporteGCMyCYM(Request $request){
      $consulta = \DB::select("SELECT e.idEquipos,e.folioRecepcion,e.vistaSuperior,e.vistaFrente,e.textoQr,e.placa_1,e.estatus,e.importancia,SUBSTR(datosCertificado,1,100) as datos,
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
      LEFT JOIN partesreparacion ON partesreparacion.`idEquipos`= e.`idEquipos`
      GROUP BY e.idEquipos
      ORDER BY e.folioRecepcion DESC");

      $cuantos = count($consulta);
      return view('reporteEquiposAbajo')
      ->with('consulta',$consulta)
      ->with('cuantos',$cuantos);
  }

  
      public function comboTipoRefaccionCambio(Request $request){
        $idMarcaRefa=$request->idMarcaRefa;
        $consultaRef=\DB::select("SELECT DISTINCT tr.idTipoRefacciones, nombreTipoRefaccion, tr.activo
                                  FROM tiporefacciones AS tr
                                  INNER JOIN refacciones AS r
                                  ON r.idTipoRefacciones=tr.idTipoRefacciones
                                  WHERE tr.idMarcaRefa=? AND r.tipoProducto='Para cambio' order by nombreTipoRefaccion asc",[$idMarcaRefa]);
        return view ('comboTipoRefaccionCambio')
        ->with('consultaRef',$consultaRef);
      } 
      public function comboRefaccionesCambio(Request $request){
        $idTipoRefacciones=$request->idTipoRefacciones;
        $consulta =\DB::select("SELECT idRefaccion, nombreRefaccion, activo, codigoRefaccion, serie
                            FROM refacciones AS r
                            WHERE idTipoRefacciones=? AND tipoProducto='Para cambio'
                            AND NOT EXISTS (SELECT * FROM recepcionEquipos AS re WHERE re.idRefaccion = r.idRefaccion)",[$idTipoRefacciones]);
        return view('commboRefaccionesCambio')
        ->with('consulta',$consulta);
      }
      public function datosRefaccion(Request $request){
        $idRefaccion=$request->idRefaccion;
        $consultaRefacciones = refacciones::Where ('idRefaccion','=',$idRefaccion)->get();
        return view('datosRefaccion')
        ->with('consultaRefacciones',$consultaRefacciones[0]);
      }
      public function datosRefaccion2(Request $request){
        $idRefaccion=$request->idRefaccion;
        $consultaRefacciones = refacciones::Where ('idRefaccion','=',$idRefaccion)->get();
        return view('datosRefaccion2')
        ->with('consultaRefacciones',$consultaRefacciones[0]);
      }
      // public function eliminarCambio(Request $request){
      //   $idEquipos = $request->idEquipos;
      //   $eliminarCambio=\DB::select("UPDATE recepcionEquipos SET cambio = 'No', idRefaccion = '0' WHERE idEquipos = ?", [$idEquipos]);
      //   return $idEquipos;
      // }
      public function modificarEquipos($idEquipos){

        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

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
                    INNER JOIN equiposEnActividad AS ea ON ea.idSegActividad=sa.idSegActividad
                    INNER JOIN recepcionEquipos AS e ON e.idEquipos=ea.idEquipos
                    WHERE e.idEquipos = $idEquipos ORDER BY idSegActividad DESC");
        
        $cuantosSeguimiento=count($seguimiento);



        $consultaClientes = recepcionEquipos::Where ('idEquipos','=',$idEquipos)->get();
        $clienteSel= clientes::where('idc',"=",$consultaClientes[0]->idc)->get();
        $nomcli =$clienteSel[0]->razonSocial;
        // $cliente = clientes::where ('idc','!=',$consultaClientes[0]->idc)->get();
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

        $consultaSucursal = recepcionEquipos::Where ('idEquipos','=',$idEquipos)->get();
        $sucursalSel= sucursales::where('idSucursal',"=",$consultaSucursal[0]->idSucursal)->get();
        $nomSuc =$sucursalSel[0]->sucursal;
        $sucursal = sucursales::where ('idSucursal','!=',$consultaSucursal[0]->idSucursal)->get();

        $consultaTipos = recepcionEquipos::Where ('idEquipos','=',$idEquipos)->get();
        $tipoSel= tipoequipos::where('idTipoEquipo',"=",$consultaTipos[0]->idTipoEquipo)->get();
        $nomTipo =$tipoSel[0]->tipoEquipo;
        $tipo = tipoequipos::where ('idTipoEquipo','!=',$consultaTipos[0]->idTipoEquipo)->get();

        $consultaSubtipos = recepcionEquipos::Where ('idEquipos','=',$idEquipos)->get();
        $subtipoSel= subtipoequipos::where('idSubtipoEquipo',"=",$consultaSubtipos[0]->idSubtipoEquipo)->get();
        $nomSubtipo =$subtipoSel[0]->subtipoEquipo;
        $subtipo = subtipoequipos::where ('idSubtipoEquipo','!=',$consultaSubtipos[0]->idSubtipoEquipo)->get();

        $consulta = recepcionEquipos::Where ('idEquipos','=',$idEquipos)->get();

        $cvistaSuperior = \DB::select("SELECT v.vistaSuperior FROM recepcionEquipos AS v Where idEquipos = $idEquipos ");
        $cvistaFrente = \DB::select("SELECT v.vistaFrente FROM recepcionEquipos AS v Where idEquipos = $idEquipos ");
        $cvistaTrasera = \DB::select("SELECT v.vistaTrasera FROM recepcionEquipos AS v Where idEquipos = $idEquipos ");
        $clateralIzquierda = \DB::select("SELECT v.lateralIzquierda FROM recepcionEquipos AS v Where idEquipos = $idEquipos ");
        $clateralDerecha = \DB::select("SELECT v.lateralDerecha FROM recepcionEquipos AS v Where idEquipos = $idEquipos ");
        $cplaca_1 = \DB::select("SELECT v.placa_1 FROM recepcionEquipos AS v Where idEquipos = $idEquipos ");
        $cplaca_2 = \DB::select("SELECT v.placa_2 FROM recepcionEquipos AS v Where idEquipos = $idEquipos ");
        $carchivoValeSalida = \DB::select("SELECT v.archivoValeSalida FROM recepcionEquipos AS v Where idEquipos = $idEquipos ");
        $carchivoDocumentoSalida = \DB::select("SELECT v.archivoDocumentoSalida FROM recepcionEquipos AS v Where idEquipos = $idEquipos ");

        $datosFactura = \DB::select("SELECT fechaFactura,numeroFactura,factura,ordenCompra
        FROM facturas
        WHERE idCotizacion IN (SELECT idCotizacion FROM detallereparaciones WHERE idEquipos =?)",[$idEquipos]);
        $cuantosdatosFactura= count($datosFactura);
        $subtipoEquipo = subtipoequipos::orderby('subtipoEquipo','asc')->get();
        $marca = \DB::select("SELECT  DISTINCT r.idMarcaRefa, marcaRefaccion, mr.activo FROM refacciones AS r INNER JOIN marcasRefaccion AS mr
                                ON r.idMarcaRefa=mr.idMarcaRefa
                                WHERE r.tipoProducto = 'Para cambio'");
        $consultaRefaccion = \DB::select("SELECT marcaRefaccion,nombreTipoRefaccion,nombreRefaccion, r.idRefaccion,r.tipoProducto,
        idEquipos, tr.idTipoRefacciones, mr.idMarcaRefa, numeroParte, r.serie, r.codigoRefaccion, r.observaciones, fotoPlaca, fotoPrincipal
        FROM refacciones AS r
        INNER JOIN marcasRefaccion AS mr ON r.idMarcaRefa = mr.idMarcaRefa
        INNER JOIN tiporefacciones AS tr ON r.idTipoRefacciones = tr.idTipoRefacciones
        INNER JOIN recepcionEquipos AS re ON r.idRefaccion = re.idRefaccion
        WHERE idEquipos = ?",[$idEquipos]);
        
        if($consultaRefaccion == true){
          $nomMarca =$consultaRefaccion[0]->marcaRefaccion;
          $nombreTipoRefaccion =$consultaRefaccion[0]->nombreTipoRefaccion;
          $refSin = tipoRefacciones::where ('idTipoRefacciones','!=',$consultaRefaccion[0]->idTipoRefacciones)
          ->where ('idMarcaRefa','=',$consultaRefaccion[0]->idMarcaRefa)->get();
          $nombreRefaccion =$consultaRefaccion[0]->nombreRefaccion;
          $refaccionUso = refacciones::where ('idRefaccion','!=',$consultaRefaccion[0]->idRefaccion)
          ->where ('idTipoRefacciones','=',$consultaRefaccion[0]->idTipoRefacciones)
          ->where ('tipoProducto','=','Para cambio')->get();
          $parte =$consultaRefaccion[0]->numeroParte;
          $serie =$consultaRefaccion[0]->serie;
          $observaciones =$consultaRefaccion[0]->observaciones;
          $codigo =$consultaRefaccion[0]->codigoRefaccion;
          $fotoPlaca =$consultaRefaccion[0]->fotoPlaca;
          $fotoPrincipal =$consultaRefaccion[0]->fotoPrincipal;
        }else{
    
          $nomMarca =' ';
          $nombreTipoRefaccion =' ';
          $nombreRefaccion =' ';
          $nombreRefaccion =' ';
          $refSin =' ';
          $refaccionUso =' ';
          $parte =' ';
          $serie =' ';
          $codigo =' ';
          $observaciones =' ';
          $fotoPlaca =' ';
          $fotoPrincipal =' ';
        }


        return view('editarEquipos')
        ->with('cuantosSeguimiento',$cuantosSeguimiento)
        ->with('seguimiento',$seguimiento)
        ->with('refSin',$refSin)
        ->with('refaccionUso',$refaccionUso)
        ->with('nomMarca',$nomMarca)
        ->with('nombreTipoRefaccion',$nombreTipoRefaccion)
        ->with('nombreRefaccion',$nombreRefaccion)
        ->with('parte',$parte)
        ->with('serie',$serie)
        ->with('codigo',$codigo)
        ->with('observaciones',$observaciones)
        ->with('fotoPlaca',$fotoPlaca)
        ->with('fotoPrincipal',$fotoPrincipal)
        ->with('marca',$marca)
        ->with('consulta',$consulta[0])
        ->with('cuantosdatosFactura',$cuantosdatosFactura)
        ->with('cliente',$cliente)
        ->with('idclientesel',$consultaClientes[0]->idc)
        ->with('nomcli',$nomcli)
        ->with('clienteSel',$clienteSel[0])
        ->with('sucursal',$sucursal)
        ->with('idSucursalSel',$consultaSucursal[0]->idSucursal)
        ->with('nomSuc',$nomSuc)
        ->with('sucursalSel',$sucursalSel[0])
        ->with('tipo',$tipo)
        ->with('idTipoSel',$consultaTipos[0]->idTipoEquipo)
        ->with('nomTipo',$nomTipo)
        ->with('tipoSel',$tipoSel[0])
        ->with('subtipo',$subtipo)
        ->with('idSubtipoSel',$consultaSubtipos[0]->idSubtipoEquipo)
        ->with('nomSubtipo',$nomSubtipo)
        ->with('subtipoSel',$subtipoSel[0])
        ->with('cvistaSuperior',$cvistaSuperior)
        ->with('cvistaFrente',$cvistaFrente)
        ->with('cvistaTrasera',$cvistaTrasera)
        ->with('clateralIzquierda',$clateralIzquierda)
        ->with('clateralDerecha',$clateralDerecha)
        ->with('cplaca_1',$cplaca_1)
        ->with('cplaca_2',$cplaca_2)
        ->with('carchivoValeSalida',$carchivoValeSalida)
        ->with('carchivoDocumentoSalida',$carchivoDocumentoSalida);
        // ->with('refaccionSel',$refaccionSel[0])
        // ->with('nomRefaccion',$nomRefaccion);
 }
  public function editarEquipos(Request $request){
        $idEquipos= $request-> idEquipos;//Esta valor lo toma del text oculto en el formulario es el id para saber a cual de modificara
        $folioRecepcion = $request -> folioRecepcion;
        $idu = $request -> idu;
        $nombreEmpresa = $request -> nombreEmpresa;
        $usuarioEquipos = $request -> usuarioEquipos;
        $fechaRecoleccion = $request -> fechaRecoleccion;
        $fechaRegistro = $request -> fechaRegistro;
        $idc = $request -> idc;
        $idSucursal = $request -> idSucursal;
        $personaEntrega = $request -> personaEntrega;
        $personaRecibe = $request -> personaRecibe;
        $numeroDocumentoSalida = $request -> numeroDocumentoSalida;
        $GCMid = $request -> GCMid;
        $complementoGCMid = $request -> complementoGCMid;
        $idTipoEquipo = $request -> idTipoEquipo;
        $idSubtipoEquipo = $request -> idSubtipoEquipo;
        $serie = $request -> serie;
        $datosCertificado = $request -> datosCertificado;
        $marca = $request -> marca;
        $modelo = $request -> modelo;
        $estatus = $request -> estatus;
        $estatusEntrega = $request -> estatusEntrega;
        $recurso = $request -> recurso;
        $importancia = $request -> importancia;
        $descripcionFalla = $request -> descripcionFalla;
        $testRunInicial = $request -> testRunInicial;
        $testRunFinal = $request -> testRunFinal;
        $feedbackInicial = $request -> feedbackInicial;
        $feedbackFinal = $request -> feedbackFinal;
        $balerosInicial = $request -> balerosInicial;
        $balerosFinal = $request -> balerosFinal;
        $gearBoxInicial = $request -> gearBoxInicial;
        $gearBoxFinal = $request -> gearBoxFinal;
        $imanesInicial = $request -> imanesInicial;
        $imanesFinal = $request -> imanesFinal;
        $debanadosInicial = $request -> debanadosInicial;
        $debanadosFinal = $request -> debanadosFinal;
        $encoderInicial = $request -> encoderInicial;
        $encoderFinal = $request -> encoderFinal;
        $corrienteVer = $request -> corrienteVer;
        $corrienteHor = $request -> corrienteHor;
        $corrienteAx = $request -> corrienteAx;
        $tensionVer = $request -> tensionVer;
        $tensionHor = $request -> tensionHor;
        $tensionAx = $request -> tensionAx;
        $tempMotor = $request -> tempMotor;
        $tempTransmision = $request -> tempTransmision;
        $vibracionVer = $request -> vibracionVer;
        $vibracionHor = $request -> vibracionHor;
        $vibracionAx = $request -> vibracionAx;
        $enfriamientoVer = $request -> enfriamientoVer;
        $enfriamientoHor = $request -> enfriamientoHor;
        $enfriamientoAx = $request -> enfriamientoAx;



        if($datosCertificado ==""){
          $datosCertificado = " ";
        }else{
          $datosCertificado;
        }

        if($recurso ==""){
          $recurso = " ";
        }else{
          $recurso;
        }
        $this->validate ($request,[
          //  'fechaFactura'=>['required']

           //VALIDACION DE LOS ARCHVOS
          'achivoValeSalida'=>['mimes:jpeg,png,gif,jpg'],
          'archivoDocumentoSalida'=>['mimes:png,jpg,jpeg,gif,doc,docx,pdf'],
          'serie'=>['required'],
          'marca'=>['required'],
          'modelo'=>['required'],
          'idc'=>['required'],
          'idSucursal'=>['required'],
          'estatus'=>['required'],
          // 'recurso'=>['required'],
          'idTipoEquipo'=>['required'],
          'GCMid'=>['required'],
          'complementoGCMid'=>['required'],
          'personaEntrega'=>['required'],
          'personaRecibe'=>['required'],
          'idSubtipoEquipo'=>['required'],
          'importancia'=>['required'],
          'vistaSuperior'=>['mimes:jpeg,png,gif,jpg'],
          'vistaFrente'=>['mimes:jpeg,png,gif,jpg'],
          'vistaTrasera'=>['mimes:jpeg,png,gif,jpg'],
          'lateralIzquierda'=>['mimes:jpeg,png,gif,jpg'],
          'lateralDerecha'=>['mimes:jpeg,png,gif,jpg'],
          'placa_1'=>['mimes:jpeg,png,gif,jpg'],
          'placa_2'=>['mimes:jpeg,png,gif,jpg']
           ]);

           $textoQrV = $request ->textoQr;
            if($textoQrV != ""){
              $textoQr = $request->textoQr;
            }
            else{
              $textoQr="SinEtiquetaQr";
            }


        $file1 = $request->file('vistaSuperior');
        //obtenemos el nombre del foto vista superior
             if($file1!=""){
        $vistaSuperior =  time()."_".$file1->getClientOriginalName();
        //indicamos que queremos guardar un nuevo vistaSuperior en el disco local
        \Storage::disk('local')->put($vistaSuperior,  \File::get($file1));
            }

        $file2 = $request->file('vistaFrente');
        //obtenemos el nombre del foto vista superior
             if($file2!=""){
        $vistaFrente =  time()."_".$file2->getClientOriginalName();
        //indicamos que queremos guardar un nuevo vistaFrente en el disco local
        \Storage::disk('local')->put($vistaFrente,  \File::get($file2));
            }
        $file3 = $request->file('vistaTrasera');
        //obtenemos el nombre del foto vista superior
             if($file3!=""){
        $vistaTrasera =  time()."_".$file3->getClientOriginalName();
        //indicamos que queremos guardar un nuevo vistaTrasera en el disco local
        \Storage::disk('local')->put($vistaTrasera,  \File::get($file3));
            }
        $file4 = $request->file('lateralIzquierda');
        //obtenemos el nombre del foto vista superior
             if($file4!=""){
        $lateralIzquierda =  time()."_".$file4->getClientOriginalName();
        //indicamos que queremos guardar un nuevo lateralIzquierda en el disco local
        \Storage::disk('local')->put($lateralIzquierda,  \File::get($file4));
            }
        $file5 = $request->file('lateralDerecha');
        //obtenemos el nombre del foto vista superior
             if($file5!=""){
        $lateralDerecha =  time()."_".$file5->getClientOriginalName();
        //indicamos que queremos guardar un nuevo lateralDerecha en el disco local
        \Storage::disk('local')->put($lateralDerecha,  \File::get($file5));
            }
        $file6 = $request->file('placa_1');
        //obtenemos el nombre del foto vista superior
             if($file6!=""){
        $placa_1 =  time()."_".$file6->getClientOriginalName();
        //indicamos que queremos guardar un nuevo file6 en el disco local
        \Storage::disk('local')->put($placa_1,  \File::get($file6));
            }
        $file7 = $request->file('placa_2');
        //obtenemos el nombre del foto vista superior
             if($file7!=""){
        $placa_2 =  time()."_".$file7->getClientOriginalName();
        //indicamos que queremos guardar un nuevo file6 en el disco local
        \Storage::disk('local')->put($placa_2,  \File::get($file7));
            }

        $file8 = $request->file('archivoValeSalida');
        //obtenemos el nombre del foto vista superior
             if($file8!=""){
        $archivoValeSalida =  time()."_".$file8->getClientOriginalName();
        //indicamos que queremos guardar un nuevo file6 en el disco local
        \Storage::disk('local')->put($archivoValeSalida,  \File::get($file8));
            }

        $file9 = $request->file('archivoDocumentoSalida');
        //obtenemos el nombre del foto vista superior
             if($file9!=""){
        $archivoDocumentoSalida =  time()."_".$file9->getClientOriginalName();
        //indicamos que queremos guardar un nuevo file6 en el disco local
        \Storage::disk('local')->put($archivoDocumentoSalida,  \File::get($file9));
            }

            $idRefaccion = $request-> idRefaccion;
            if($idRefaccion == ""){
              $idRefaccion = $request-> idRefaccionOld;
            }else{
              $idRefaccion = $request-> idRefaccion;
            }

        $equipos = recepcionEquipos::find($idEquipos);
        $equipos-> idEquipos = $request-> idEquipos;
        $equipos-> folioRecepcion = $request-> folioRecepcion;
        $equipos-> idu = $request-> idu;
        $equipos-> nombreEmpresa = $request -> nombreEmpresa;
        $equipos-> usuarioEquipos = $request -> usuarioEquipos;
        $equipos-> fechaRecoleccion = $request-> fechaRecoleccion;
        $equipos-> fechaRegistro = $request-> fechaRegistro;
        $equipos-> idc = $request-> idc;
        $equipos-> idSucursal = $request-> idSucursal;
        $equipos-> personaEntrega = $request-> personaEntrega;
        $equipos-> personaRecibe = $request-> personaRecibe;
        $equipos-> numeroDocumentoSalida = $request-> numeroDocumentoSalida;
        $equipos-> GCMid = $request-> GCMid;
        $equipos-> complementoGCMid = $request-> complementoGCMid;
        $equipos-> idTipoEquipo = $request-> idTipoEquipo;
        $equipos-> idSubtipoEquipo = $request-> idSubtipoEquipo;
        $equipos-> serie = $request-> serie;
        $equipos-> ubicacionEquipo = $request-> ubicacionEquipo;
        // $equipos-> servicio = $request-> servicio;
        $equipos-> datosCertificado = $datosCertificado;
        // variables refaccion para cambio
        $equipos-> cambio = $request-> cambio;
        $equipos-> idRefaccion = $idRefaccion;
        $equipos-> seguimiento = $request-> seguimiento;
        $equipos-> marca = $request-> marca;
        $equipos-> modelo = $request-> modelo;
        $equipos-> estatus = $request-> estatus;
        $equipos-> estatusEntrega = $request-> estatusEntrega;
        $equipos-> recurso = $recurso;
        $equipos-> importancia = $request-> importancia;
        $equipos-> descripcionFalla = $request-> descripcionFalla;
        $equipos-> textoQr = $textoQr;
        $equipos-> testRunInicial = $request-> testRunInicial;
        $equipos-> testRunFinal = $request-> testRunFinal;
        $equipos-> feedbackInicial = $request-> feedbackInicial;
        $equipos-> feedbackFinal = $request-> feedbackFinal;
        $equipos-> balerosInicial = $request-> balerosInicial;
        $equipos-> balerosFinal = $request-> balerosFinal;
        $equipos-> gearBoxInicial = $request-> gearBoxInicial;
        $equipos-> gearBoxFinal = $request-> gearBoxFinal;
        $equipos-> imanesInicial = $request-> imanesInicial;
        $equipos-> imanesFinal = $request-> imanesFinal;
        $equipos-> debanadosInicial = $request-> debanadosInicial;
        $equipos-> debanadosFinal = $request-> debanadosFinal;
        $equipos-> encoderInicial = $request-> encoderInicial;
        $equipos-> encoderFinal = $request-> encoderFinal;
        $equipos-> corrienteVer = $request-> corrienteVer;
        $equipos-> corrienteHor = $request-> corrienteHor;
        $equipos-> corrienteAx = $request-> corrienteAx;
        $equipos-> tensionVer = $request-> tensionVer;
        $equipos-> tensionHor = $request-> tensionHor;
        $equipos-> tensionAx = $request-> tensionAx;
        $equipos-> tempMotor = $request-> tempMotor;
        $equipos-> tempTransmision = $request-> tempTransmision;
        $equipos-> vibracionVer = $request-> vibracionVer;
        $equipos-> vibracionHor = $request-> vibracionHor;
        $equipos-> vibracionAx = $request-> vibracionAx;
        $equipos-> enfriamientoVer = $request-> enfriamientoVer;
        $equipos-> enfriamientoHor = $request-> enfriamientoHor;
        $equipos-> enfriamientoAx = $request-> enfriamientoAx;


        if($file1!=""){
                        $equipos-> vistaSuperior = $vistaSuperior;
                  }
        if($file2!=""){
                        $equipos-> vistaFrente = $vistaFrente;
                  }
        if($file3!=""){
                        $equipos-> vistaTrasera = $vistaTrasera;
                  }
        if($file4!=""){
                        $equipos-> lateralIzquierda = $lateralIzquierda;
                  }
        if($file5!=""){
                        $equipos-> lateralDerecha = $lateralDerecha;
                  }
        if($file6!=""){
                        $equipos-> placa_1 = $placa_1;
                  }

        if($file7!=""){
                        $equipos-> placa_2 = $placa_2;
                  }
        if($file8!=""){
                        $equipos-> archivoValeSalida = $archivoValeSalida;
                  }
        if($file9!=""){
                        $equipos-> archivoDocumentoSalida = $archivoDocumentoSalida;
                  }


        $equipos-> save();

        $proceso='Modificación de equipos';
        $mensaje="Equipo modificado correctamente";
        return view ('mensajeEquipos')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
        // return $request;
 }
 public function eliminarEquipos ($idEquipos){/*Rerecibe este parametro y lo guarda en esa variable*/
          $equipos = \DB::UPDATE("update recepcionEquipos
          set activo ='No' where idEquipos=$idEquipos");

        $proceso ="Eliminación de equipos";
        $mensaje="El equipo ha sido desactivado correctamente";
        return view('mensajeEquipos')
           ->with('proceso',$proceso)
           ->with('mensaje',$mensaje);
 }

   public function restaurarEquipos ($idEquipos){ //restarura el valos de NO a SI en el campo activo
        $marca= \DB::UPDATE("update recepcionEquipos
        set activo ='Si' where idEquipos=$idEquipos");

        $proceso ="Restauración de equipos";
        $mensaje="El equipo ha sido activado correctamente";
        return view('mensajeEquipos')
             ->with('proceso',$proceso)
             ->with('mensaje',$mensaje);
 }

 ########################################################## DIVIDIR EQUIPOS EN PARTES ####################################################
  public function dividirEquipos($idEquipos){
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
    $consultap = \DB::select("SELECT e.idEquipos, e.folioRecepcion,e.nombreEmpresa,e.usuarioEquipos,
    e.serie,e.datosCertificado,e.recurso,c.razonSocial,s.sucursal,te.tipoEquipo,se.subtipoEquipo,
    CONCAT (e.GCMid, '-', e.complementoGCMid)AS gcmid,e.equipoActivo
    FROM recepcionEquipos AS e
    INNER JOIN sucursales AS s ON s.idSucursal= e.idSucursal
    INNER JOIN clientes AS c ON c.idc= e.idc
    INNER JOIN tipoequipos AS te ON te.idTipoEquipo= e.idTipoEquipo
    INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= e.idSubtipoEquipo WHERE idEquipos =?",[$idEquipos]);

    $consultaPartesCreadas = \DB::select("SELECT p.idParte,p.fotoParte,p.nombreParte,p.idEquipos,p.GCMidParte,p.fechaSalida,p.fechaRecepcion,p.prioridadRep,e.nombreEstatus,
    CONCAT (t.nombreTaller,' - ',t.tipoTaller) AS nombreTaller
    FROM partesreparacion AS p
    LEFT JOIN estatusreparaciones AS e ON e.idEstatus = p.idEstatus
    LEFT JOIN talleres AS t ON t.idTaller = p.idTaller WHERE idEquipos=?",[$idEquipos]);

    $consultaCantidad= \DB::select("SELECT idParte FROM partesreparacion WHERE idEquipos=?",[$idEquipos]);
    $cuantasPartes = count($consultaCantidad);

    return view('dividirEquipos')
    ->with('cuantasPartes',$cuantasPartes)
    ->with('consultaPartesCreadas',$consultaPartesCreadas)
    ->with('stipo',$stipo)
    ->with('consultap',$consultap[0]);
    }
  }

  public function altaDividirParteAbajo(Request $request){
    $gcmid = $request->gcmid;
    $idEquipos = $request->idEquipos;

    $comboTaller = talleres::orderby('nombreTaller','asc')->get();
    $comboEstatus = estatusreparaciones::orderby('nombreEstatus','asc')->get();

    // $idConsulta= \DB::select("SELECT idParte FROM partesreparacion");
    // $cuantosid = count($idConsulta);
    $ultimaParteCreadaConsulta = DB::table('partesreparacion')->select(DB::raw('MAX(numParte) as ultimaParteBD'))->where('idEquipos','=',$idEquipos)->get();
    $ultimaParteCreada =$ultimaParteCreadaConsulta[0]->ultimaParteBD;

    if($ultimaParteCreada==0){
      $idParteSiguiente = 1;
    }
    else{
      $idParteSiguiente = $ultimaParteCreada + 1;
    }

    $GCMidParte=$gcmid."-".$idParteSiguiente;

    return view('altaDividirParteAbajo')
    ->with('idParteSiguiente',$idParteSiguiente)
    ->with('gcmid',$gcmid)
    ->with('idEquipos',$idEquipos)
    ->with('comboEstatus',$comboEstatus)
    ->with('comboTaller',$comboTaller)
    ->with('GCMidParte',$GCMidParte);
  }

  public function guardarParteCreada(Request $request){
    $idEquipos = $request->idEquipos;
    $GCMid = $request->GCMid;
    $GCMidParte = $request->GCMidParte;
    $numParte = $request->numParte;
    $nombreParte = $request->nombreParte;
    $fechaSalida = $request->fechaSalida;
    $queReparacion = $request->queReparacion;
    $fechaRecepcion = $request->fechaRecepcion;
    $fechaEntrega = $request->fechaEntrega;
    $ordenCompra = $request->ordenCompra;
    $archivoOrdenCompra = $request->archivoOrdenCompra;
    $montoReparacion = $request->montoReparacion;
    $tipoMoneda = $request->tipoMoneda;
    $idTaller = $request->idTaller;
    $quienllevo = $request->quienllevo;
    $quienRecibio = $request->quienRecibio;
    $prioridadRep = $request->prioridadRep;
    $idEstatus = $request->idEstatus;
    $seguimientoTecnico = $request->seguimientoTecnico;

    // $this->validate ($request,[
    //   'fotoParte'=>['mimes:jpeg,png,gif,jpg']
    // ]);

    $file = $request->file('archivoOrdenCompra');
    if($file!=""){
      $ldate = date('Ymd_His_');
      $img = $file->getClientOriginalName();
      $img1 = $ldate.$img;
      \Storage::disk('local')->put($img1, \File::get($file));
    }
    else{
      $img1 ="Sin archivo";
    }

    $file = $request->file('fotoParte');
    if($file!=""){
      $ldate = date('Ymd_His_');
      $img = $file->getClientOriginalName();
      $img2 = $ldate.$img;
      \Storage::disk('local')->put($img2, \File::get($file));
    }
    else{
      $img2 ="Sin archivo";
    }

    $partes = new partesreparacion;
    $partes-> idEquipos =$request-> idEquipos;
    $partes-> GCMid =$request-> GCMid;
    $partes-> GCMidParte =$request-> GCMidParte;
    $partes-> numParte = $request-> numParte;
    $partes-> nombreParte =$request-> nombreParte;
    $partes-> fechaSalida =$request-> fechaSalida;
    $partes-> queReparacion =$request-> queReparacion;
    $partes-> fechaRecepcion =$request-> fechaRecepcion;
    $partes-> fechaEntrega =$request-> fechaEntrega;
    $partes-> ordenCompra =$request-> ordenCompra;
    $partes-> montoReparacion =$request-> montoReparacion;
    $partes-> tipoMoneda = $request-> tipoMoneda;
    $partes-> idTaller =$request-> idTaller;
    $partes-> quienllevo =$request-> quienllevo;
    $partes-> quienRecibio =$request-> quienRecibio;
    $partes-> prioridadRep =$request-> prioridadRep;
    $partes-> idEstatus =$request-> idEstatus;
    $partes-> seguimientoTecnico =$request-> seguimientoTecnico;
    $partes-> archivoOrdenCompra=$img1;
    $partes-> fotoParte=$img2;
    $partes-> save();

    $proceso='División de Equipos';
    $mensaje="Parte creada correctamente";
    return view ('mensajePartes')
    ->with('idEquipos',$idEquipos)
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }

  public function editarParteAbajo(Request $request){
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
    $idParteEditar = $request->idParte;
    $consultaParte = \DB::select("SELECT * FROM partesreparacion WHERE idParte =?",[$idParteEditar]);
    $consultaArchivoOC = \DB::select("SELECT p.archivoOrdenCompra FROM partesreparacion AS p WHERE idParte=?",[$idParteEditar]);
    $consultaFotoParte = \DB::select("SELECT p.fotoParte FROM partesreparacion AS p WHERE idParte=?",[$idParteEditar]);

    $tallerSel= talleres::where('idTaller',"=",$consultaParte[0]->idTaller)->get();
    $nombreTaller =$tallerSel[0]->nombreTaller;
    $tipoTaller =$tallerSel[0]->tipoTaller;
    $taller = talleres::where ('idTaller','!=',$consultaParte[0]->idTaller)->get();

    $estatusSel= estatusReparaciones::where('idEstatus',"=",$consultaParte[0]->idEstatus)->get();
    $nombreEstatus =$estatusSel[0]->nombreEstatus;
    $estatusReparado = estatusReparaciones::where ('idEstatus','!=',$consultaParte[0]->idEstatus)->get();
    
    $paquetes = \DB::select("SELECT idPaquete, nombrePaquete,activo FROM paquetes");

    $equipoActivo = \DB::select("SELECT pr.idEquipos, re.equipoActivo FROM partesreparacion AS pr
                INNER JOIN recepcionEquipos AS re ON re.idEquipos = pr.idEquipos
                WHERE idParte = $idParteEditar");

    $activarTab=1;
    return view('editarParteAbajo')
    ->with('activarTab',$activarTab)
    ->with('consultaArchivoOC',$consultaArchivoOC)
    ->with('consultaFotoParte',$consultaFotoParte)
    ->with('taller',$taller)
    ->with('idTaller',$consultaParte[0]->idTaller)
    ->with('nombreTaller',$nombreTaller)
    ->with('tipoTaller',$tipoTaller)
    ->with('tallerSel',$tallerSel[0])
    ->with('idTallerSel',$consultaParte[0]->idTaller)
    ->with('estatusReparado',$estatusReparado)
    ->with('idEstatus',$consultaParte[0]->idEstatus)
    ->with('nombreEstatus',$nombreEstatus)
    ->with('estatusSel',$estatusSel[0])
    ->with('idEstatusSel',$consultaParte[0]->idEstatus)
    ->with('consultaParte',$consultaParte[0])
    ->with('stipo',$stipo)
    ->with('paquetes',$paquetes)
    ->with('equipoActivo',$equipoActivo[0]);
    }
  }

  public function guardarEdicionParte(Request $request){
    $idParte = $request->idParte;
    $idEquipos = $request->idEquipos;
    $GCMid = $request->GCMid;
    $GCMidParte = $request->GCMidParte;
    $numParte = $request->numParte;
    $nombreParte = $request->nombreParte;
    $fechaSalida = $request->fechaSalida;
    $queReparacion = $request->queReparacion;
    $fechaRecepcion = $request->fechaRecepcion;
    $fechaEntrega = $request->fechaEntrega;
    $ordenCompra = $request->ordenCompra;
    $montoReparacion = $request->montoReparacion;
    $tipoMoneda = $request->tipoMoneda;
    $idTaller = $request->idTaller;
    $quienllevo = $request->quienllevo;
    $quienRecibio = $request->quienRecibio;
    $prioridadRep = $request->prioridadRep;
    $idEstatus = $request->idEstatus;
    $seguimientoTecnico = $request->seguimientoTecnico;

    $file1 = $request->file('archivoOrdenCompra');
    if($file1!=""){
      $ldate = date('Ymd_His_');
      $img = $file1->getClientOriginalName();
      $img1 = $ldate.$img;
      \Storage::disk('local')->put($img1, \File::get($file1));
    }

    $file2 = $request->file('fotoParte');
    if($file2!=""){
      $ldate = date('Ymd_His_');
      $img = $file2->getClientOriginalName();
      $img2 = $ldate.$img;
      \Storage::disk('local')->put($img2, \File::get($file2));
    }

    $partes = partesreparacion::find($idParte);
    if($file1!=""){
      $partes->archivoOrdenCompra=$img1;
    }
    if($file2!=""){
      $partes->fotoParte=$img2;
    }

    $partes-> idParte = $request-> idParte;
    $partes-> idEquipos =$request-> idEquipos;
    $partes-> GCMid =$request-> GCMid;
    $partes-> GCMidParte =$request-> GCMidParte;
    $partes-> numParte = $request->numParte;
    $partes-> nombreParte =$request-> nombreParte;
    $partes-> fechaSalida =$request-> fechaSalida;
    $partes-> queReparacion =$request-> queReparacion;
    $partes-> fechaRecepcion =$request-> fechaRecepcion;
    $partes-> fechaEntrega =$request-> fechaEntrega;
    $partes-> ordenCompra =$request-> ordenCompra;
    $partes-> montoReparacion =$request-> montoReparacion;
    $partes-> tipoMoneda = $request-> tipoMoneda;
    $partes-> idTaller =$request-> idTaller;
    $partes-> quienllevo =$request-> quienllevo;
    $partes-> quienRecibio =$request-> quienRecibio;
    $partes-> prioridadRep =$request-> prioridadRep;
    $partes-> idEstatus =$request-> idEstatus;
    $partes-> seguimientoTecnico =$request-> seguimientoTecnico;
    $partes-> save();

    $proceso='División de Equipos';
    $mensaje="Datos de la pieza modificados correctamente";
    return view ('mensajePartes')
    ->with('idEquipos',$idEquipos)
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);

  }

  public function borrarParteCreada(Request $request){
    $idParteBorrar = $request->idParteBorrar;
    $idEquipos = $request->idEquipos;

    $borro= \DB::delete("DELETE FROM partesreparacion WHERE idParte=?",[$idParteBorrar]);

    $consultaPartesCreadas = \DB::select("SELECT p.idParte,p.nombreParte,p.idEquipos,p.GCMidParte,p.fechaSalida,p.fechaRecepcion,p.prioridadRep,e.nombreEstatus,
    CONCAT (t.nombreTaller,' - ',t.tipoTaller) AS nombreTaller
    FROM partesreparacion AS p
    LEFT JOIN estatusreparaciones AS e ON e.idEstatus = p.idEstatus
    LEFT JOIN talleres AS t ON t.idTaller = p.idTaller WHERE idEquipos=?",[$idEquipos]);

    return view('reportitoDePartesCreadas')
    ->with('consultaPartesCreadas',$consultaPartesCreadas);

  }

  public function serviciosParteCreada(Request $request){
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

      $idParteServicios = $request->idParte;
      $GCMidParte = \DB::select("SELECT p.idParte,p.GCMidParte FROM partesreparacion AS p WHERE idParte=?",[$idParteServicios]);

      $consultaServiciosAsignados=\DB::select("SELECT s.idServReparacionParte,s.idParte,s.tieneCotizacion,st.nombreServTaller,a.alcance,FORMAT(s.precioDolar,2)AS precioDolar FROM serviciosreparacionpartes AS s
      LEFT JOIN serviciostalleres AS st ON st.idServicioTaller = s.idServicioTaller
      LEFT JOIN alcances AS a ON a.idAlcance = s.idAlcance
      WHERE idParte=?",[$idParteServicios]);
      $cuantasYaHay = count($consultaServiciosAsignados);

      $consultaRefaccionesAsignados=\DB::select("SELECT rp.idRefaccRepParte,rp.idParte,pv.codigo,FORMAT(rp.precioDolar,2) AS precioVentaDol, pv.nombreRefaccion,
      rt.nombreTipoRefaccion,rp.tipoProducto,p.nombrePaquete,rp.apareceEnCoti FROM refaccionesreparacionpartes AS rp
      LEFT JOIN partesVenta AS pv ON pv.idPartesVenta = rp.idPartesVenta
      LEFT JOIN tiporefacciones AS rt ON rt.idTipoRefacciones = rp.idTipoRefacciones
      LEFT JOIN paquetes AS p ON p.idPaquete = rp.idPaquete
      WHERE idParte=?",[$idParteServicios]);
      $cuantasRefaccionesYaHay = count($consultaRefaccionesAsignados);

      $marca = marcaRefaccion::orderby('idMarcaRefa', 'asc')->get();
      $tipoParte = partesVenta::orderby('idPartesVenta', 'asc')->get();

      $comboTipoRefacciones = tiporefacciones::orderby('nombreTipoRefaccion','asc')->get();
      $comboAlcances = alcances::orderby('alcance','asc')->get();
      $proveedor = proveedores::orderby('razonSocialProv','asc')->get();
      $resultado = refacciones::orderby('idRefaccion','desc')
        ->take(1)
        ->get();
        $idRef = $resultado[0]->idRefaccion +1;

      $consultaQr1 = \DB::select("SELECT * FROM partesreparacion WHERE idParte=?",[$idParteServicios]);
      $consultaQr2 = \DB::select("SELECT e.idEquipos,t.tipoEquipo,s.subtipoEquipo FROM recepcionEquipos AS e
      LEFT JOIN tipoequipos AS t ON t.idTipoEquipo = e.idTipoEquipo
      LEFT JOIN subtipoequipos AS s ON s.idSubtipoEquipo = e.idSubtipoEquipo
      WHERE idEquipos=(SELECT idEquipos FROM partesreparacion WHERE idParte=?)",[$idParteServicios]);

      $consultaSuma = \DB::select("SELECT FORMAT(SUM(precioDolar),2) as total FROM serviciosreparacionpartes
      WHERE idParte = $idParteServicios");

      $consultaSumaRef = \DB::select("SELECT FORMAT(SUM(precioDolar),2) as total2 FROM refaccionesreparacionpartes
      WHERE idParte = $idParteServicios");

      $paquetes = \DB::select("SELECT idPaquete, nombrePaquete,activo FROM paquetes");
      $equipoActivo = \DB::select("SELECT pr.idEquipos, re.equipoActivo FROM partesreparacion AS pr
      INNER JOIN recepcionEquipos AS re ON re.idEquipos = pr.idEquipos
      WHERE idParte = $idParteServicios");

      if($stipo == 'Tecnico'){
        $activarTab=3;
      }else{
        $activarTab=2;
      }

      return view('editarParteAbajo')
      ->with('marca',$marca)
      ->with('tipoParte',$tipoParte)
      ->with('activarTab',$activarTab)
      ->with('idParteServicios',$idParteServicios)
      ->with('cuantasYaHay',$cuantasYaHay)
      ->with('consultaServiciosAsignados',$consultaServiciosAsignados)
      ->with('cuantasRefaccionesYaHay',$cuantasRefaccionesYaHay)
      ->with('consultaRefaccionesAsignados',$consultaRefaccionesAsignados)
      ->with('GCMidParte',$GCMidParte[0])
      ->with('consultaQr1',$consultaQr1[0])
      ->with('consultaQr2',$consultaQr2[0])
      ->with('comboTipoRefacciones',$comboTipoRefacciones)
      ->with('comboAlcances',$comboAlcances)
      ->with('proveedor',$proveedor)
      ->with('idRef',$idRef)
      ->with('consultaSuma',$consultaSuma[0])
      ->with('consultaSumaRef',$consultaSumaRef[0])
      ->with('stipo',$stipo)
      ->with('paquetes',$paquetes)
      ->with('equipoActivo',$equipoActivo[0]);

    }
  }

  public function comboServTaller(request $request){
    $idAlcance = $request->get('idAlcance');
    $comboServiciosTaller= \DB::select("SELECT * FROM serviciostalleres WHERE idAlcance=?",[$idAlcance]);

    return view('comboServTaller')
    ->with('comboServiciosTaller',$comboServiciosTaller);
  }

  public function preciosRefacciones(request $request){
    $idPartesVenta = $request->get('idPartesVenta');
    $precios= \DB::select("SELECT precioVentaPe, precioVentaDol FROM partesVenta WHERE idPartesVenta=?",[$idPartesVenta]);

    return view('preciosRefacciones')
    ->with('precios',$precios[0]);
  }

  public function preciosServicios(request $request){
    $idServicioTaller = $request->get('idServicioTaller');
    $precios= \DB::select("SELECT costoInterno, costoExterno FROM serviciostalleres WHERE idServicioTaller=?",[$idServicioTaller]);

    return view('preciosServTaller')
    ->with('precios',$precios[0]);
  }

  public function asignarServicioTallerParte(Request $request){
    $idParte = $request->idParteServicio;
    $idAlcance = $request->idAlcance;
    $idServicioTaller = $request->idServicioTaller;
    $tieneCotizacion = $request->tieneCotizacion;

    // $montoCotizacion = $request->montoCotizacion;

    $serviciosExistentes=\DB::select("SELECT COUNT(*) AS cuantos
    FROM serviciosreparacionpartes
    WHERE idParte= ? AND idServicioTaller =?",[$idParte,$idServicioTaller]);
    $cuantos = $serviciosExistentes[0]->cuantos;

    if($cuantos==0){

      $servRepParte = new serviciosreparacionpartes;
      $servRepParte-> idParte = $request-> idParteServicio;
      $servRepParte-> idServicioTaller = $request-> idServicioTaller;
      $servRepParte-> idAlcance = $request->idAlcance;
      $servRepParte-> tieneCotizacion = $request-> tieneCotizacion;
      $servRepParte-> precioPesos = $request-> precioPesos;
      $servRepParte-> precioDolar = $request-> precioDolar;
      // $servRepParte-> montoCotizacion = $request-> montoCotizacion;
      $servRepParte->save();

      $consultaServiciosAsignados=\DB::select("SELECT s.idServReparacionParte,s.idParte,s.tieneCotizacion,st.nombreServTaller,a.alcance,FORMAT(s.precioDolar,2)AS precioDolar FROM serviciosreparacionpartes AS s
      LEFT JOIN serviciostalleres AS st ON st.idServicioTaller = s.idServicioTaller
      LEFT JOIN alcances AS a ON a.idAlcance = s.idAlcance
      WHERE idParte=?",[$idParte]);
      $cuantasYaHay = count($consultaServiciosAsignados);

      $consultaSuma = \DB::select("SELECT FORMAT(SUM(precioDolar),2) as total FROM serviciosreparacionpartes
        WHERE idParte = $idParte");

      return view('reporteServiciosTallerParte')
      ->with('cuantos',$cuantos)
      ->with('cuantasYaHay',$cuantasYaHay)
      ->with('consultaServiciosAsignados',$consultaServiciosAsignados)
      ->with('consultaSuma',$consultaSuma[0]);

    }else{
      $consultaServiciosAsignados=\DB::select("SELECT s.idServReparacionParte,s.idParte,s.tieneCotizacion,st.nombreServTaller,a.alcance,FORMAT(s.precioDolar,2)AS precioDolar FROM serviciosreparacionpartes AS s
      LEFT JOIN serviciostalleres AS st ON st.idServicioTaller = s.idServicioTaller
      LEFT JOIN alcances AS a ON a.idAlcance = s.idAlcance
      WHERE idParte=?",[$idParte]);
      $cuantasYaHay = count($consultaServiciosAsignados);
      
      $consultaSuma = \DB::select("SELECT FORMAT(SUM(precioDolar),2) as total FROM serviciosreparacionpartes
        WHERE idParte = $idParte");

      return view('reporteServiciosTallerParte')
      ->with('cuantos',$cuantos)
      ->with('cuantasYaHay',$cuantasYaHay)
      ->with('consultaServiciosAsignados',$consultaServiciosAsignados)
      ->with('consultaSuma',$consultaSuma[0]);
    }
  }

  public function borrarServicioReparacionParte(Request $request){
    $idParte = $request->idParte;
    $idServReparacionParte = $request->idServicioBorrar;

    $borro= \DB::delete("DELETE FROM serviciosreparacionpartes WHERE idServReparacionParte=?",[$idServReparacionParte]);


    $consultaServiciosAsignados=\DB::select("SELECT s.idServReparacionParte,s.idParte,s.tieneCotizacion,st.nombreServTaller,a.alcance,FORMAT(s.precioDolar,2)AS precioDolar FROM serviciosreparacionpartes AS s
    LEFT JOIN serviciostalleres AS st ON st.idServicioTaller = s.idServicioTaller
    LEFT JOIN alcances AS a ON a.idAlcance = s.idAlcance
    WHERE idParte=?",[$idParte]);
    $cuantasYaHay = count($consultaServiciosAsignados);
    $cuantos=0;
    $consultaSuma = \DB::select("SELECT FORMAT(SUM(precioDolar),2) as total FROM serviciosreparacionpartes
    WHERE idParte = $idParte");
    return view('reporteServiciosTallerParte')
    ->with('cuantos',$cuantos)
    ->with('cuantasYaHay',$cuantasYaHay)
    ->with('consultaServiciosAsignados',$consultaServiciosAsignados)
    ->with('consultaSuma',$consultaSuma[0]);
  }

  public function agregarRefaccionParte(Request $request){
    $idParte = $request->idParte;
    $idPartesVenta = $request->idRefaccion;
    // $idRefaccion = $request->idRefaccion;
    $idTipoRefacciones = $request->idTipoRefacciones;
    $apareceEnCoti = $request->apareceEnCoti;

    $refAsignadasExisten=\DB::select("SELECT COUNT(*) AS cuantos
    FROM refaccionesreparacionpartes
    WHERE idParte= ? AND idRefaccion =? AND idTipoRefacciones=?",[$idParte,$idPartesVenta,$idTipoRefacciones]);
    $cuantos = $refAsignadasExisten[0]->cuantos;


    if($cuantos==0){

      $servRepParte = new refaccionesreparacionpartes;
      $servRepParte-> idParte = $request-> idParte;
      $servRepParte-> idPartesVenta = $request-> idRefaccion;
      $servRepParte-> tipoProducto = $request-> tipoProducto;
      $servRepParte-> precioPesos = $request-> precioPesos;
      $servRepParte-> precioDolar = $request-> precioDolar;
      // $servRepParte-> idRefaccion = $request-> idRefaccion;
      $servRepParte-> idTipoRefacciones = $request-> idTipoRefacciones;
      $servRepParte-> apareceEnCoti = $request-> apareceEnCoti;
      $servRepParte->save();

      $consultaRefaccionesAsignados=\DB::select("SELECT rp.idRefaccRepParte,rp.idParte,pv.codigo,FORMAT(rp.precioDolar,2) AS precioVentaDol, pv.nombreRefaccion,
      rt.nombreTipoRefaccion,rp.tipoProducto,p.nombrePaquete,rp.apareceEnCoti FROM refaccionesreparacionpartes AS rp
      LEFT JOIN partesVenta AS pv ON pv.idPartesVenta = rp.idPartesVenta
      LEFT JOIN tiporefacciones AS rt ON rt.idTipoRefacciones = rp.idTipoRefacciones
      LEFT JOIN paquetes AS p ON p.idPaquete = rp.idPaquete
      WHERE idParte=?",[$idParte]);
      $cuantasRefaccionesYaHay = count($consultaRefaccionesAsignados);

      $consultaSumaRef = \DB::select("SELECT FORMAT(SUM(precioDolar),2) as total2 FROM refaccionesreparacionpartes
     WHERE idParte = $idParte");
     

      return view('reporteRefaccionesParte')
      ->with('cuantos',$cuantos)
      ->with('cuantasRefaccionesYaHay',$cuantasRefaccionesYaHay)
      ->with('consultaRefaccionesAsignados',$consultaRefaccionesAsignados)
      ->with('consultaSumaRef',$consultaSumaRef[0]);

    }else{
      $consultaRefaccionesAsignados=\DB::select("SELECT rp.idRefaccRepParte,rp.idParte,pv.codigo,FORMAT(rp.precioDolar,2) AS precioVentaDol, pv.nombreRefaccion,
      rt.nombreTipoRefaccion,rp.tipoProducto,p.nombrePaquete,rp.apareceEnCoti FROM refaccionesreparacionpartes AS rp
      LEFT JOIN partesVenta AS pv ON pv.idPartesVenta = rp.idPartesVenta
      LEFT JOIN tiporefacciones AS rt ON rt.idTipoRefacciones = rp.idTipoRefacciones
      LEFT JOIN paquetes AS p ON p.idPaquete = rp.idPaquete
      WHERE idParte=?",[$idParte]);
      $cuantasRefaccionesYaHay = count($consultaRefaccionesAsignados);
      
      $consultaSumaRef = \DB::select("SELECT FORMAT(SUM(precioDolar),2) as total2 FROM refaccionesreparacionpartes
     WHERE idParte = $idParte");

      return view('reporteRefaccionesParte')
      ->with('cuantos',$cuantos)
      ->with('cuantasRefaccionesYaHay',$cuantasRefaccionesYaHay)
      ->with('consultaRefaccionesAsignados',$consultaRefaccionesAsignados)
      ->with('consultaSumaRef',$consultaSumaRef[0]);
    }
  }

  public function comboRefacciones(Request $request){
    $tipoProducto = $request->get('tipoProducto');
    $idTipoRefacciones = $request->get('idTipoRefacciones');
    // $comboRefacciones = refacciones::where('idTipoRefacciones','=',$idTipoRefacciones,'AND','activo','=','Si')->get();
    $comboRefacciones = \DB::select("SELECT * FROM partesVenta WHERE tipoProducto = ? AND activo = 'Si' AND idTipoRefacciones = ? order by codigo asc",[$tipoProducto, $idTipoRefacciones]);
    return view('comboRefaccionesPartes')
    ->with('comboRefacciones',$comboRefacciones);
  }

  public function borrarRefaccionParte(Request $request){
    $idParte = $request->idParte;
    $idRefaccionBorrar = $request->idRefaccionBorrar;

    $borro= \DB::delete("DELETE FROM refaccionesreparacionpartes WHERE idRefaccRepParte=?",[$idRefaccionBorrar]);

    $consultaRefaccionesAsignados=\DB::select("SELECT rp.idRefaccRepParte,rp.idParte,pv.codigo,FORMAT(rp.precioDolar,2) AS precioVentaDol, pv.nombreRefaccion,
		rt.nombreTipoRefaccion,rp.tipoProducto,p.nombrePaquete,rp.apareceEnCoti FROM refaccionesreparacionpartes AS rp
    LEFT JOIN partesVenta AS pv ON pv.idPartesVenta = rp.idPartesVenta
    LEFT JOIN tiporefacciones AS rt ON rt.idTipoRefacciones = rp.idTipoRefacciones
    LEFT JOIN paquetes AS p ON p.idPaquete = rp.idPaquete
    WHERE idParte=?",[$idParte]);
    $cuantasRefaccionesYaHay = count($consultaRefaccionesAsignados);
    $cuantos=0;

    $consultaSumaRef = \DB::select("SELECT FORMAT(SUM(precioDolar),2) as total2 FROM refaccionesreparacionpartes
     WHERE idParte = $idParte");

    return view('reporteRefaccionesParte')
    ->with('cuantos',$cuantos)
    ->with('cuantasRefaccionesYaHay',$cuantasRefaccionesYaHay)
    ->with('consultaRefaccionesAsignados',$consultaRefaccionesAsignados)
    ->with('consultaSumaRef',$consultaSumaRef[0]);
  }

  public function pdfQrParte($idParteEditar){

    $consultaQr1 = \DB::select("SELECT * FROM partesreparacion WHERE idParte=?",[$idParteEditar]);
    $consultaQr2 = \DB::select("SELECT e.idEquipos,t.tipoEquipo,s.subtipoEquipo FROM recepcionEquipos AS e
    LEFT JOIN tipoequipos AS t ON t.idTipoEquipo = e.idTipoEquipo
    LEFT JOIN subtipoequipos AS s ON s.idSubtipoEquipo = e.idSubtipoEquipo
    WHERE idEquipos=(SELECT idEquipos FROM partesreparacion WHERE idParte=?)",[$idParteEditar]);

    $pdf = \PDF::loadView('codigoQrPartes',['consultaQr1'=>$consultaQr1[0]],['consultaQr2'=>$consultaQr2[0]]);
    return $pdf->stream('etiquetaQr.pdf');
  }


  public function nuevaRefaccion(Request $request){

    $idParte=$request->idParte;
    $nombreRefaccion=$request->nombreRefaccion;
    $numeroParte=$request->numeroParte;
    $idProveedor=$request->idProveedor;
    $precioUltimo=$request->precioUltimo;
    $precioLista=$request->precioLista;
    $ubicacion=$request->ubicacion;
    $estatus=$request->estatus;
    $idTipoRefacciones=$request->idTipoRefacciones;

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

  }

  // public function reportePorEmpresa(){
  //   $clientes = \DB::select("SELECT re.idc, c.razonSocial FROM recepcionEquipos AS re
  //                                     INNER JOIN clientes AS c ON c.idc = re.idc
  //                                     GROUP BY idc
  //                                     ORDER BY razonSocial ASC");
  //   return view('reporteGCM')
  //   ->with('clientes',$clientes);
  // }


  // public function reportePorEmpresaAbajo(Request $request){

  //   $idc = $request -> idc;
  //   $fechaInicio = $request -> fechaInicio;
  //   $fechaFin = $request -> fechaFin;

    // $consulta = \DB::select("SELECT e.idEquipos,e.folioRecepcion,e.vistaSuperior,e.vistaFrente,e.textoQr,e.placa_1,e.estatus,e.importancia,
    // COUNT(partesreparacion.`idParte`) AS canti,
    // CONCAT (e.GCMid, '-', e.complementoGCMid)AS gcmid,
    // CONCAT(c.razonSocial,'-',s.sucursal) AS cliSuc,
    // DATE_FORMAT(e.fechaRecoleccion,'%d %b %Y') AS fecha,
    // DATE_FORMAT(e.fechaRegistro,'%d %b %Y') AS fechar,e.personaEntrega,
    // CONCAT (e.serie,'-',te.tipoEquipo,'-',se.subtipoEquipo) AS equipo,
    // CONCAT (e.marca,'-',e.modelo) AS marcaModelo, e.activo
    // FROM recepcionEquipos AS e
    // INNER JOIN clientes AS c ON c.idc= e.idc
    // INNER JOIN sucursales AS s ON s.idSucursal= e.idSucursal
    // INNER JOIN tipoequipos AS te ON te.idTipoEquipo= e.idTipoEquipo
    // INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= e.idSubtipoEquipo
    // LEFT JOIN partesreparacion ON partesreparacion.`idEquipos`= e.`idEquipos`
    // WHERE e.idc=?
    // GROUP BY e.idEquipos
    // ORDER BY e.fechaRecoleccion DESC",[$idc]);

  //   $consulta = \DB::select("SELECT e.idEquipos,e.folioRecepcion,e.vistaSuperior,e.vistaFrente,e.textoQr,e.placa_1,e.estatus,e.importancia,SUBSTR(datosCertificado,1,100) as datos,
  //           COUNT(partesreparacion.`idParte`) AS canti,
  //           CONCAT (e.GCMid, '-', e.complementoGCMid)AS gcmid,
  //           CONCAT(c.razonSocial,'-',s.sucursal) AS cliSuc,
  //           DATE_FORMAT(e.fechaRecoleccion,'%d %b %Y') AS fecha,
  //           DATE_FORMAT(e.fechaRegistro,'%d %b %Y') AS fechar,e.personaEntrega,
  //           CONCAT (e.serie,'-',te.tipoEquipo,'-',se.subtipoEquipo) AS equipo,
  //           CONCAT (e.marca,'-',e.modelo) AS marcaModelo, e.activo
  //           FROM recepcionEquipos AS e
  //           INNER JOIN clientes AS c ON c.idc= e.idc
  //           INNER JOIN sucursales AS s ON s.idSucursal= e.idSucursal
  //           INNER JOIN tipoequipos AS te ON te.idTipoEquipo= e.idTipoEquipo
  //           INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= e.idSubtipoEquipo
  //           LEFT JOIN partesreparacion ON partesreparacion.`idEquipos`= e.`idEquipos`
  //           WHERE fechaRecoleccion>=? AND fechaRecoleccion<=?
  //           GROUP BY e.idEquipos, e.folioRecepcion, e.vistaSuperior,e.vistaFrente,e.placa_1, e.idu,e.textoQr,e.estatus,e.importancia,datos,gcmid,
  //           cliSuc,e.personaEntrega,equipo,marcaModelo,fecha, e.activo
  //           ORDER BY e.folioRecepcion DESC",[$fechaInicio,$fechaFin]);
  //   $cuantos = count($consulta);


  //   return view('reportePorEmpresaAbajo')
  //   //->with('consulta',$consulta)
  //   ->with('fechaInicio',$fechaInicio)
  //   ->with('fechaFin',$fechaFin)
  //   ->with('cuantos',$cuantos);
  // }

  public function detallePorEmpresa($idEquipos){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $spat = Session::get('sesionpaterno');
    $smat = Session::get('sesionmaterno');
    $stipo = Session::get('sesiontipo');
    $consultaEmpesa = \DB::select("SELECT empresaSeguimiento FROM usuarios "); //WHERE idu = ?,[$sidu]
        $seguimiento=$consultaEmpesa[0]->empresaSeguimiento;

    if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
        Session::flash('error', 'Es necesario logearse antes de continuar');
        return redirect()->route('login');
    }
    else{

    $consultap = \DB::select("SELECT e.idEquipos, e.folioRecepcion,e.nombreEmpresa,e.usuarioEquipos,e.serie,c.razonSocial,
    s.sucursal,te.tipoEquipo,se.subtipoEquipo,
    CONCAT (e.GCMid, '-', e.complementoGCMid)AS gcmid, e.recurso, e.textoQr
    FROM recepcionEquipos AS e
    INNER JOIN sucursales AS s ON s.idSucursal= e.idSucursal
    INNER JOIN clientes AS c ON c.idc= e.idc
    INNER JOIN tipoequipos AS te ON te.idTipoEquipo= e.idTipoEquipo
    INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= e.idSubtipoEquipo WHERE idEquipos =?",[$idEquipos]);

    $consultaClientes = recepcionEquipos::Where ('idEquipos','=',$idEquipos)->get();
    $clienteSel= clientes::where('idc',"=",$consultaClientes[0]->idc)->get();
    $nomcli =$clienteSel[0]->razonSocial;
    $cliente = clientes::where ('idc','!=',$consultaClientes[0]->idc)->get();

    $consultaSucursal = recepcionEquipos::Where ('idEquipos','=',$idEquipos)->get();
    $sucursalSel= sucursales::where('idSucursal',"=",$consultaSucursal[0]->idSucursal)->get();
    $nomSuc =$sucursalSel[0]->sucursal;
    $sucursal = sucursales::where ('idSucursal','!=',$consultaSucursal[0]->idSucursal)->get();

    $consultaTipos = recepcionEquipos::Where ('idEquipos','=',$idEquipos)->get();
    $tipoSel= tipoequipos::where('idTipoEquipo',"=",$consultaTipos[0]->idTipoEquipo)->get();
    $nomTipo =$tipoSel[0]->tipoEquipo;
    $tipo = tipoequipos::where ('idTipoEquipo','!=',$consultaTipos[0]->idTipoEquipo)->get();

    $consultaSubtipos = recepcionEquipos::Where ('idEquipos','=',$idEquipos)->get();
    $subtipoSel= subtipoequipos::where('idSubtipoEquipo',"=",$consultaSubtipos[0]->idSubtipoEquipo)->get();
    $nomSubtipo =$subtipoSel[0]->subtipoEquipo;
    $subtipo = subtipoequipos::where ('idSubtipoEquipo','!=',$consultaSubtipos[0]->idSubtipoEquipo)->get();

    $consulta = recepcionEquipos::Where ('idEquipos','=',$idEquipos)->get();

    $cvistaSuperior = \DB::select("SELECT v.vistaSuperior FROM recepcionEquipos AS v Where idEquipos = $idEquipos ");
    $cvistaFrente = \DB::select("SELECT v.vistaFrente FROM recepcionEquipos AS v Where idEquipos = $idEquipos ");
    $cvistaTrasera = \DB::select("SELECT v.vistaTrasera FROM recepcionEquipos AS v Where idEquipos = $idEquipos ");
    $clateralIzquierda = \DB::select("SELECT v.lateralIzquierda FROM recepcionEquipos AS v Where idEquipos = $idEquipos ");
    $clateralDerecha = \DB::select("SELECT v.lateralDerecha FROM recepcionEquipos AS v Where idEquipos = $idEquipos ");
    $cplaca_1 = \DB::select("SELECT v.placa_1 FROM recepcionEquipos AS v Where idEquipos = $idEquipos ");
    $cplaca_2 = \DB::select("SELECT v.placa_2 FROM recepcionEquipos AS v Where idEquipos = $idEquipos ");
    $carchivoValeSalida = \DB::select("SELECT v.archivoValeSalida FROM recepcionEquipos AS v Where idEquipos = $idEquipos ");
    $carchivoDocumentoSalida = \DB::select("SELECT v.archivoDocumentoSalida FROM recepcionEquipos AS v Where idEquipos = $idEquipos ");

    $consultaPartesCreadas = \DB::select("SELECT p.idParte,p.nombreParte,p.idEquipos,p.GCMidParte,p.fechaSalida,p.fechaRecepcion,p.prioridadRep,e.nombreEstatus,
    CONCAT (t.nombreTaller,' - ',t.tipoTaller) AS nombreTaller
    FROM partesreparacion AS p
    LEFT JOIN estatusreparaciones AS e ON e.idEstatus = p.idEstatus
    LEFT JOIN talleres AS t ON t.idTaller = p.idTaller WHERE idEquipos=?",[$idEquipos]);

    $consultaCantidad= \DB::select("SELECT idParte FROM partesreparacion WHERE idEquipos=?",[$idEquipos]);
    $cuantasPartes = count($consultaCantidad);

    $infoCotizacion = \DB::select("SELECT CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario, dp.idCotizacion, dp.idEquipos, dp.clonEquiposActivo,
    c.fechaCotizacion, s.sucursal,c.idSucursal, c.numeroCotizacion, c.dirigidoA, c.conCopia, FORMAT(c.montoTotal,2) AS montoTotal, c.tipoMoneda,c.estatus,
    c.check1,c.textoCheck1,c.check2,c.textoCheck2,c.check3,c.textoCheck3,c.check4,c.textoCheck4,c.check5,c.textoCheck5,c.check6,c.textoCheck6,c.check7,c.textoCheck7
    FROM cotizaciones AS c
    INNER JOIN detallereparaciones AS dp ON c.idcotizacion = dp.idcotizacion
    INNER JOIN usuarios AS u ON c.idu = u.idu
    INNER JOIN sucursales AS s ON c.idSucursal= s.idSucursal
    WHERE idEquipos = $idEquipos  AND clonEquiposActivo ='Si'");

    $cuantasCot = count($infoCotizacion);

    $archivoDeCotizacion = \DB::select("SELECT archivoCotizacion FROM cotizaciones WHERE idCotizacion IN(SELECT idCotizacion FROM detallereparaciones WHERE idEquipos=$idEquipos AND clonEquiposActivo = 'Si')");

    $equiposCotizados = \DB::select("SELECT dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion,dr.numeroCotizacion,re.GCMid,re.complementoGCMid, dr.semanasEntrega, FORMAT(dr.montoEquipo,2) AS montoEquipo,dr.notas,
    FORMAT(dr.montoFinanciamiento,2) AS montoFinanciamiento,dr.descuento,dr.tipoDescuento
    FROM detallereparaciones AS dr
    INNER JOIN recepcionEquipos AS re ON re.idEquipos = dr.idEquipos
    WHERE dr.idEquipos = $idEquipos AND clonEquiposActivo ='Si'");

    $datosEquipoCotizado = \DB::select("SELECT re.idEquipos, re.serie,
    CONCAT (re.serie,' - ',te.tipoEquipo,' - ',se.subtipoEquipo) AS equipo
    FROM recepcionEquipos AS re
    INNER JOIN tipoequipos AS te ON te.idTipoEquipo= re.idTipoEquipo
    INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= re.idSubtipoEquipo
    WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones)");
    // $cuantasCot = count($infoCotizacion);

    $datosFacturaPorEquipo = \DB::select("SELECT coti.numeroCotizacion,idFactura,ordenCompra,factura,comprobantePago,numeroRemision,numeroEntrada,adenda,numeroDocumento,
    numeroAnexo1,numeroAnexo2,numeroAnexo3,numeroAnexo4,numeroAnexo5
    FROM facturas AS f
    INNER JOIN cotizaciones AS coti ON coti.idCotizacion = f.idCotizacion
    WHERE f.idCotizacion IN(SELECT idCotizacion FROM detallereparaciones WHERE idEquipos=$idEquipos AND clonEquiposActivo = 'Si')");


    $consultaArchivo =\DB::select("SELECT archivo FROM facturas WHERE idCotizacion IN(SELECT idCotizacion FROM detallereparaciones WHERE idEquipos=$idEquipos AND clonEquiposActivo = 'Si')");
    $consultaFactura = \DB::select("SELECT archivoFactura FROM facturas WHERE idCotizacion IN(SELECT idCotizacion FROM detallereparaciones WHERE idEquipos=$idEquipos AND clonEquiposActivo = 'Si')");
    $consultaPago = \DB::select("SELECT archivoPago FROM facturas WHERE idCotizacion IN(SELECT idCotizacion FROM detallereparaciones WHERE idEquipos=$idEquipos AND clonEquiposActivo = 'Si')");
    $consultaxml = \DB::select("SELECT xmlFactura FROM facturas WHERE idCotizacion IN(SELECT idCotizacion FROM detallereparaciones WHERE idEquipos=$idEquipos AND clonEquiposActivo = 'Si')");
    $consultaRemision = \DB::select("SELECT archivoRemision FROM facturas WHERE idCotizacion IN(SELECT idCotizacion FROM detallereparaciones WHERE idEquipos=$idEquipos AND clonEquiposActivo = 'Si')");
    $consultaOtro = \DB::select("SELECT archivoOtro FROM facturas WHERE idCotizacion IN(SELECT idCotizacion FROM detallereparaciones WHERE idEquipos=$idEquipos AND clonEquiposActivo = 'Si')");
    $consultaAdenda = \DB::select("SELECT archivoAdenda FROM facturas WHERE idCotizacion IN(SELECT idCotizacion FROM detallereparaciones WHERE idEquipos=$idEquipos AND clonEquiposActivo = 'Si')");
    $anexos = \DB::select("SELECT anexo1 FROM facturas WHERE idCotizacion IN(SELECT idCotizacion FROM detallereparaciones WHERE idEquipos=$idEquipos AND clonEquiposActivo = 'Si')");
    $anexo2 = \DB::select("SELECT anexo2 FROM facturas WHERE idCotizacion IN(SELECT idCotizacion FROM detallereparaciones WHERE idEquipos=$idEquipos AND clonEquiposActivo = 'Si')");
    $anexo3 = \DB::select("SELECT anexo3 FROM facturas WHERE idCotizacion IN(SELECT idCotizacion FROM detallereparaciones WHERE idEquipos=$idEquipos AND clonEquiposActivo = 'Si')");
    $anexo4 = \DB::select("SELECT anexo4 FROM facturas WHERE idCotizacion IN(SELECT idCotizacion FROM detallereparaciones WHERE idEquipos=$idEquipos AND clonEquiposActivo = 'Si')");
    $anexo5 = \DB::select("SELECT anexo5 FROM facturas WHERE idCotizacion IN(SELECT idCotizacion FROM detallereparaciones WHERE idEquipos=$idEquipos AND clonEquiposActivo = 'Si')");
    $archivo =\DB::select("SELECT idCotizacion,archivoCotizacion FROM cotizaciones WHERE idCotizacion IN(SELECT idCotizacion FROM detallereparaciones WHERE idEquipos=$idEquipos)");

    $cuantosDatosFactura = count($datosFacturaPorEquipo);

    if($cuantosDatosFactura >= 1){
          $numeroFactura = $datosFacturaPorEquipo[0]->idFactura;

          $usuarioValido = \DB::select("SELECT ae.idFactura, ae.idu, u.tipo FROM asignacionesdetalles AS ae
          INNER JOIN usuarios AS u ON u.idu = ae.idu
          WHERE ae.idFactura = $numeroFactura");

          $usuarioAsignado = $usuarioValido[0]->idu;

    } else{

      $numeroFactura = 0;
      $usuarioValido = \DB::select("SELECT ae.idFactura, ae.idu, u.tipo FROM asignacionesdetalles AS ae
      INNER JOIN usuarios AS u ON u.idu = ae.idu
      WHERE ae.idFactura = $numeroFactura");
      $usuarioAsignado = 0;
    }



    if($stipo == 'Administrador' || $usuarioAsignado == $sidu){
      $visible = 1;

    }else{
      $visible = 0;
    }


    if($cuantasCot >=1 && $cuantosDatosFactura >= 1){

    return view('detallePorEmpresa')
    ->with('consulta',$consulta[0])
    ->with('cliente',$cliente)
    ->with('idclientesel',$consultaClientes[0]->idc)
    ->with('nomcli',$nomcli)
    ->with('clienteSel',$clienteSel[0])
    ->with('sucursal',$sucursal)
    ->with('idSucursalSel',$consultaSucursal[0]->idSucursal)
    ->with('nomSuc',$nomSuc)
    ->with('sucursalSel',$sucursalSel[0])
    ->with('tipo',$tipo)
    ->with('idTipoSel',$consultaTipos[0]->idTipoEquipo)
    ->with('nomTipo',$nomTipo)
    ->with('tipoSel',$tipoSel[0])
    ->with('subtipo',$subtipo)
    ->with('idSubtipoSel',$consultaSubtipos[0]->idSubtipoEquipo)
    ->with('nomSubtipo',$nomSubtipo)
    ->with('subtipoSel',$subtipoSel[0])
    ->with('cvistaSuperior',$cvistaSuperior)
    ->with('cvistaFrente',$cvistaFrente)
    ->with('cvistaTrasera',$cvistaTrasera)
    ->with('clateralIzquierda',$clateralIzquierda)
    ->with('clateralDerecha',$clateralDerecha)
    ->with('cplaca_1',$cplaca_1)
    ->with('cplaca_2',$cplaca_2)
    ->with('carchivoValeSalida',$carchivoValeSalida)
    ->with('carchivoDocumentoSalida',$carchivoDocumentoSalida)
    ->with('cuantasPartes',$cuantasPartes)
    ->with('consultaPartesCreadas',$consultaPartesCreadas)
    ->with('consultap',$consultap[0])
    ->with('infoCotizacion',$infoCotizacion[0])
    ->with('archivoDeCotizacion',$archivoDeCotizacion)
    ->with('equiposCotizados',$equiposCotizados)
    ->with('datosEquipoCotizado',$datosEquipoCotizado)
    ->with('datosFacturaPorEquipo',$datosFacturaPorEquipo[0])
    ->with('consultaArchivo',$consultaArchivo)
    ->with('consultaFactura',$consultaFactura)
    ->with('consultaPago',$consultaPago)
    ->with('consultaxml',$consultaxml)
    ->with('consultaRemision',$consultaRemision)
    ->with('consultaOtro',$consultaOtro)
    ->with('consultaAdenda',$consultaAdenda)
    ->with('anexos',$anexos)
    ->with('anexo2',$anexo2)
    ->with('anexo3',$anexo3)
    ->with('anexo4',$anexo4)
    ->with('anexo5',$anexo5)
    ->with('archivo',$archivo)
    ->with('visible',$visible)
    ->with('usuarioAsignado',$usuarioAsignado);
    }

    if($cuantasCot == 0 && $cuantosDatosFactura == 0){
      return view('detallePorEmpresa2')
      ->with('consulta',$consulta[0])
      ->with('cliente',$cliente)
      ->with('idclientesel',$consultaClientes[0]->idc)
      ->with('nomcli',$nomcli)
      ->with('clienteSel',$clienteSel[0])
      ->with('sucursal',$sucursal)
      ->with('idSucursalSel',$consultaSucursal[0]->idSucursal)
      ->with('nomSuc',$nomSuc)
      ->with('sucursalSel',$sucursalSel[0])
      ->with('tipo',$tipo)
      ->with('idTipoSel',$consultaTipos[0]->idTipoEquipo)
      ->with('nomTipo',$nomTipo)
      ->with('tipoSel',$tipoSel[0])
      ->with('subtipo',$subtipo)
      ->with('idSubtipoSel',$consultaSubtipos[0]->idSubtipoEquipo)
      ->with('nomSubtipo',$nomSubtipo)
      ->with('subtipoSel',$subtipoSel[0])
      ->with('cvistaSuperior',$cvistaSuperior)
      ->with('cvistaFrente',$cvistaFrente)
      ->with('cvistaTrasera',$cvistaTrasera)
      ->with('clateralIzquierda',$clateralIzquierda)
      ->with('clateralDerecha',$clateralDerecha)
      ->with('cplaca_1',$cplaca_1)
      ->with('cplaca_2',$cplaca_2)
      ->with('carchivoValeSalida',$carchivoValeSalida)
      ->with('carchivoDocumentoSalida',$carchivoDocumentoSalida)
      ->with('cuantasPartes',$cuantasPartes)
      ->with('consultaPartesCreadas',$consultaPartesCreadas)
      ->with('consultap',$consultap[0])
      ->with('visible',$visible)
      ->with('usuarioAsignado',$usuarioAsignado);
    }

    if($cuantasCot >=1 && $cuantosDatosFactura == 0){

      return view('detallePorEmpresa3')
      ->with('consulta',$consulta[0])
      ->with('cliente',$cliente)
      ->with('idclientesel',$consultaClientes[0]->idc)
      ->with('nomcli',$nomcli)
      ->with('clienteSel',$clienteSel[0])
      ->with('sucursal',$sucursal)
      ->with('idSucursalSel',$consultaSucursal[0]->idSucursal)
      ->with('nomSuc',$nomSuc)
      ->with('sucursalSel',$sucursalSel[0])
      ->with('tipo',$tipo)
      ->with('idTipoSel',$consultaTipos[0]->idTipoEquipo)
      ->with('nomTipo',$nomTipo)
      ->with('tipoSel',$tipoSel[0])
      ->with('subtipo',$subtipo)
      ->with('idSubtipoSel',$consultaSubtipos[0]->idSubtipoEquipo)
      ->with('nomSubtipo',$nomSubtipo)
      ->with('subtipoSel',$subtipoSel[0])
      ->with('cvistaSuperior',$cvistaSuperior)
      ->with('cvistaFrente',$cvistaFrente)
      ->with('cvistaTrasera',$cvistaTrasera)
      ->with('clateralIzquierda',$clateralIzquierda)
      ->with('clateralDerecha',$clateralDerecha)
      ->with('cplaca_1',$cplaca_1)
      ->with('cplaca_2',$cplaca_2)
      ->with('carchivoValeSalida',$carchivoValeSalida)
      ->with('carchivoDocumentoSalida',$carchivoDocumentoSalida)
      ->with('cuantasPartes',$cuantasPartes)
      ->with('consultaPartesCreadas',$consultaPartesCreadas)
      ->with('consultap',$consultap[0])
      ->with('infoCotizacion',$infoCotizacion[0])
      ->with('archivoDeCotizacion',$archivoDeCotizacion)
      ->with('equiposCotizados',$equiposCotizados)
      ->with('datosEquipoCotizado',$datosEquipoCotizado)
      ->with('visible',$visible)
      ->with('usuarioAsignado',$usuarioAsignado);
      }

  }
  }
  public function detallePorParte(Request $request){
    $idParte = $request->idParte;

    $consultaParte = \DB::select("SELECT * FROM partesreparacion WHERE idParte =?",[$idParte]);
    $consultaArchivoOC = \DB::select("SELECT p.archivoOrdenCompra FROM partesreparacion AS p WHERE idParte=?",[$idParte]);
    $consultaFotoParte = \DB::select("SELECT p.fotoParte FROM partesreparacion AS p WHERE idParte=?",[$idParte]);

    $tallerSel= talleres::where('idTaller',"=",$consultaParte[0]->idTaller)->get();
    $nombreTaller =$tallerSel[0]->nombreTaller;
    $tipoTaller =$tallerSel[0]->tipoTaller;
    $taller = talleres::where ('idTaller','!=',$consultaParte[0]->idTaller)->get();

    $estatusSel= estatusReparaciones::where('idEstatus',"=",$consultaParte[0]->idEstatus)->get();
    $nombreEstatus =$estatusSel[0]->nombreEstatus;
    $estatusReparado = estatusReparaciones::where ('idEstatus','!=',$consultaParte[0]->idEstatus)->get();

    $GCMidParte = \DB::select("SELECT p.idParte,p.GCMidParte FROM partesreparacion AS p WHERE idParte=?",[$idParte]);

    $consultaServiciosAsignados=\DB::select("SELECT s.idServReparacionParte,s.idParte,st.nombreServTaller,FORMAT(s.precioDolar,2)AS precioDolar FROM serviciosreparacionpartes AS s
    LEFT JOIN serviciostalleres AS st ON st.idServicioTaller = s.idServicioTaller WHERE idParte=?",[$idParte]);
    $cuantasYaHay = count($consultaServiciosAsignados);

    $consultaRefaccionesAsignados=\DB::select("SELECT rp.idRefaccRepParte,rp.idParte,pv.codigo,FORMAT(rp.precioDolar,2) AS precioVentaDol, pv.nombreRefaccion,
		rt.nombreTipoRefaccion,rp.tipoProducto,p.nombrePaquete,rp.apareceEnCoti FROM refaccionesreparacionpartes AS rp
    LEFT JOIN partesVenta AS pv ON pv.idPartesVenta = rp.idPartesVenta
    LEFT JOIN tiporefacciones AS rt ON rt.idTipoRefacciones = rp.idTipoRefacciones
    LEFT JOIN paquetes AS p ON p.idPaquete = rp.idPaquete
    WHERE idParte=?",[$idParte]);
    $cuantasRefaccionesYaHay = count($consultaRefaccionesAsignados);

    $consultaQr1 = \DB::select("SELECT * FROM partesreparacion WHERE idParte=?",[$idParte]);
    $consultaQr2 = \DB::select("SELECT e.idEquipos,t.tipoEquipo,s.subtipoEquipo FROM recepcionEquipos AS e
    LEFT JOIN tipoequipos AS t ON t.idTipoEquipo = e.idTipoEquipo
    LEFT JOIN subtipoequipos AS s ON s.idSubtipoEquipo = e.idSubtipoEquipo
    WHERE idEquipos=(SELECT idEquipos FROM partesreparacion WHERE idParte=?)",[$idParte]);

    return view('detallePorParteAbajo')
    ->with('consultaArchivoOC',$consultaArchivoOC)
    ->with('consultaFotoParte',$consultaFotoParte)
    ->with('taller',$taller)
    ->with('idTaller',$consultaParte[0]->idTaller)
    ->with('nombreTaller',$nombreTaller)
    ->with('tipoTaller',$tipoTaller)
    ->with('tallerSel',$tallerSel[0])
    ->with('idTallerSel',$consultaParte[0]->idTaller)
    ->with('estatusReparado',$estatusReparado)
    ->with('idEstatus',$consultaParte[0]->idEstatus)
    ->with('nombreEstatus',$nombreEstatus)
    ->with('estatusSel',$estatusSel[0])
    ->with('idEstatusSel',$consultaParte[0]->idEstatus)
    ->with('consultaParte',$consultaParte[0])
    ->with('GCMidParte',$GCMidParte[0])
    ->with('cuantasYaHay',$cuantasYaHay)
    ->with('cuantasRefaccionesYaHay',$cuantasRefaccionesYaHay)
    ->with('consultaQr1',$consultaQr1[0])
    ->with('consultaQr2',$consultaQr2[0])
    ->with('consultaRefaccionesAsignados',$consultaRefaccionesAsignados)
    ->with('consultaServiciosAsignados',$consultaServiciosAsignados);
  }

  public function reportePorTaller(){

    $talleres = talleres::orderby('nombreTaller','asc')->get();

    $query = DB::table('partesreparacion')
                    ->select('idParte','recepcionEquipos.vistaSuperior','fotoParte','GCMidParte','nombreParte','queReparacion','nombreEstatus',
                    DB::raw('CONCAT_WS("-",marca,modelo) AS marcaModelo'),
                    DB::raw('DATE_FORMAT(fechaRecepcion,"%d %b %Y") AS fechaRecepcion'),
                    DB::raw('DATE_FORMAT(fechaEntrega,"%d %b %Y") AS fechaEntrega'))
                    ->leftJoin('recepcionEquipos', 'partesreparacion.idEquipos', '=', 'recepcionEquipos.idEquipos')
                    ->leftJoin('estatusreparaciones', 'partesreparacion.idEstatus', '=', 'estatusreparaciones.idEstatus')

                    ->orderBy('idParte', 'desc')
                    ->get();
    $consulta = $this->jsonTaller($query);
    return view( 'reportePorTaller', compact( 'talleres', 'consulta') );

  }

  public function jsonTaller ( $query )
  {
      function val ( $value )
      {

          $val = ( $value == 'Sin archivo' || $value == null || $value == '' ) ? '' : asset( 'public/archivos/'.$value );

          $image = ( \File::exists('public/archivos/'.$value) ) ? str_replace(' ', '%20', $val) : '';

          return $image;
      }


      $consult = array();

      foreach( $query as $value )
      {
        array_push( $consult, array(
          'id'    => $value->idParte,
              'a'     => val( $value->vistaSuperior ),
              'b'     => val( $value->fotoParte ),
              'c'     => $value->GCMidParte,
              'd'     => $value->marcaModelo,
              'e'     => $value->nombreParte,
              'f'     => $value->queReparacion,
              'g'     => $value->fechaRecepcion==''?"<div style = 'color:red'>Sin fecha</div>":$value->fechaRecepcion,
              'h'     => $value->fechaEntrega==''?"<div style = 'color:red'>Sin fecha </div>":$value->fechaEntrega,
              'i'     => $value->nombreEstatus,
          ) );

      }

      return $consult;
  }

  public function reportePorTallerAbajo(Request $request){
    $idTaller = $request -> idTaller;
    $fechaInicio = $request -> fechaInicio;
    $fechaFin = $request -> fechaFin;
    $idEquipos = $request -> idEquipos;

    if($idEquipos=="Todos"){
      $reporte=\DB::select("SELECT pr.idParte, pr.GCMidParte, pr.GCMid, pr.nombreParte, pr.queReparacion, pr.prioridadRep,pr.fotoParte,re.modelo,re.marca,re.vistaSuperior,er.nombreEstatus,
      DATE_FORMAT(pr.fechaSalida,'%d %b %Y') AS fecha,
      DATE_FORMAT(pr.fechaEntrega,'%d %b %Y') AS fechaE
      FROM partesreparacion AS pr
      INNER JOIN recepcionEquipos AS re ON re.idEquipos = pr.idEquipos
      INNER JOIN estatusreparaciones AS er ON er.idEstatus = pr.idEstatus
      WHERE fechaSalida>=? AND fechaSalida<=? AND idTaller=?
      ORDER BY idParte",[$fechaInicio,$fechaFin,$idTaller]);

    }else{
      $reporte=\DB::select("SELECT pr.idParte, pr.GCMidParte, pr.GCMid, pr.nombreParte, pr.queReparacion, pr.prioridadRep,pr.fotoParte,re.modelo,re.marca,re.vistaSuperior,er.nombreEstatus,
      DATE_FORMAT(pr.fechaSalida,'%d %b %Y') AS fecha,
      DATE_FORMAT(pr.fechaEntrega,'%d %b %Y') AS fechaE
      FROM partesreparacion AS pr
      INNER JOIN recepcionEquipos AS re ON re.idEquipos = pr.idEquipos
      INNER JOIN estatusreparaciones AS er ON er.idEstatus = pr.idEstatus
      WHERE fechaSalida>=? AND fechaSalida<=? AND idTaller=? AND re.idEquipos=?
      ORDER BY idParte",[$fechaInicio,$fechaFin,$idTaller,$idEquipos]);

    }

    $cuantos = count($reporte);

    return view('reportePorTallerAbajo')
    ->with('reporte',$reporte)
    ->with('idTaller',$idTaller)
    ->with('fechaInicio',$fechaInicio)
    ->with('fechaFin',$fechaFin)
    ->with('idEquipos',$idEquipos)
    ->with('cuantos',$cuantos);
  }

  public function comboModelos(request $request){
    $idTaller = $request ->get('idTaller');

    $consulta = \DB::select("SELECT pr.idTaller, re.modelo, re.idEquipos  FROM partesreparacion AS pr
    INNER JOIN recepcionEquipos AS re ON re.idEquipos = pr.idEquipos
    WHERE idTaller=?",[$idTaller]);

    return view ('comboModelos')
    ->with('consulta',$consulta);

  }

  public function descargarPDF(Request $request){
    $idTaller = $request -> idTaller;
    $fechaInicio = $request -> fechaInicio;
    $fechaFin = $request -> fechaFin;
    $idEquipos = $request -> idEquipos;

    if($idEquipos=='Todos'){
      $reporte=\DB::select("SELECT pr.idParte, pr.GCMidParte, pr.GCMid, pr.nombreParte, pr.queReparacion, pr.prioridadRep,pr.fotoParte,re.modelo,re.marca,re.vistaSuperior,er.nombreEstatus,
      DATE_FORMAT(pr.fechaSalida,'%d %b %Y') AS fecha,
      DATE_FORMAT(pr.fechaEntrega,'%d %b %Y') AS fechaE
      FROM partesreparacion AS pr
      INNER JOIN recepcionEquipos AS re ON re.idEquipos = pr.idEquipos
      INNER JOIN estatusreparaciones AS er ON er.idEstatus = pr.idEstatus
      WHERE fechaSalida>=? AND fechaSalida<=? AND idTaller=?
      ORDER BY idParte",[$fechaInicio,$fechaFin,$idTaller]);

      $taller=\DB::select("SELECT nombreTaller FROM talleres WHERE idTaller=?",[$idTaller]);
    }else{
      $reporte=\DB::select("SELECT pr.idParte, pr.GCMidParte, pr.GCMid, pr.nombreParte, pr.queReparacion, pr.prioridadRep,pr.fotoParte,re.modelo,re.marca,re.vistaSuperior,er.nombreEstatus,
      DATE_FORMAT(pr.fechaSalida,'%d %b %Y') AS fecha,
      DATE_FORMAT(pr.fechaEntrega,'%d %b %Y') AS fechaE
      FROM partesreparacion AS pr
      INNER JOIN recepcionEquipos AS re ON re.idEquipos = pr.idEquipos
      INNER JOIN estatusreparaciones AS er ON er.idEstatus = pr.idEstatus
      WHERE fechaSalida>=? AND fechaSalida<=? AND idTaller=? AND re.idEquipos=?
      ORDER BY idParte",[$fechaInicio,$fechaFin,$idTaller,$idEquipos]);

      $taller=\DB::select("SELECT nombreTaller FROM talleres WHERE idTaller=?",[$idTaller]);
    }

    $pdf = \PDF::loadView('reportePorTallerPDF',['reporte'=>$reporte, 'fechaInicio'=>$fechaInicio, 'fechaFin'=>$fechaFin, 'taller'=>$taller[0]]);
    $pdf ->setPaper('letter','landscape');
    return $pdf->stream('reportePorTallerPDF.pdf');
  }
  public function filtroEquipos(){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $spat = Session::get('sesionpaterno');
    $smat = Session::get('sesionmaterno');
    $stipo = Session::get('sesiontipo');
    $consultaEmpesa = \DB::select("SELECT empresaSeguimiento FROM usuarios "); //WHERE idu = ?,[$sidu]
        $seguimiento=$consultaEmpesa[0]->empresaSeguimiento;

    if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
        Session::flash('error', 'Es necesario logearse antes de continuar');
        return redirect()->route('login');
    }
    else{
            $anexGrid = new anexGrid();

            $cliente='';
            $folio='';
            $datos='';
            $estatus='';
            $estatusEntrega='';
            $entrego='';

            foreach($anexGrid->filtros as $f)
                {
                  if($f['columna'] == 'folioRecepcion' && $f['valor'] != ''){
                    $folio = $f['valor'];
                  }

                  if($f['columna'] == 'estatus' && $f['valor'] != ''){
                    $estatus = $f['valor'];
                  }
                  if($f['columna'] == 'estatusEntrega' && $f['valor'] != ''){
                    $estatusEntrega = $f['valor'];
                  }
                  if($f['columna'] == 'personaEntrega' && $f['valor'] != ''){
                    $entrego = $f['valor'];
                  }
                }

      $order;
      $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

      if ($seguimiento =="GCMyCYM" || ($stipo == 'Administrador' || $stipo == 'Vendedor')) {
        $registros = DB::table('recepcionEquipos')
                        ->select('recepcionEquipos.idEquipos','vistaSuperior','vistaFrente','placa_1','folioRecepcion',
                        'importancia','estatus','recepcionEquipos.activo','personaEntrega','recurso','estatusEntrega',
                        DB::raw('DATE_FORMAT(fechaRegistro,"%d %b %Y") AS fecha'),
                        DB::raw('CONCAT_WS("-",recepcionEquipos.GCMid,complementoGCMid) AS gcmid'),
                        DB::raw('CONCAT_WS(" / ",razonSocial,sucursales.sucursal) AS clienteSucursal'),
                        DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                        // DB::raw('SUBSTR(datosCertificado,1,100) as datos'),
                        DB::raw('CONCAT_WS("-",serie,tipoEquipo,subtipoEquipo) AS equipo'),
                        DB::raw('CONCAT_WS("-",marca,modelo) AS marcaModelo'),
                        DB::raw('COUNT(partesreparacion.`idParte`) AS partes'))
                        ->leftJoin('clientes', 'recepcionEquipos.idc', '=', 'clientes.idc')
                        ->leftJoin('sucursales', 'recepcionEquipos.idSucursal', '=', 'sucursales.idSucursal')
                        ->leftJoin('tipoequipos', 'recepcionEquipos.idTipoEquipo', '=', 'tipoequipos.idTipoEquipo')
                        ->leftJoin('subtipoequipos', 'recepcionEquipos.idSubtipoEquipo', '=', 'subtipoequipos.idSubtipoEquipo')
                        ->leftJoin('partesreparacion', 'recepcionEquipos.idEquipos', '=', 'partesreparacion.idEquipos')
                        ->where('recepcionEquipos.idEquipos', '>', 0)
                        ->where('folioRecepcion', 'like', '%'.$folio.'%')
                        ->where('datosCertificado', 'like', '%'.$datos.'%')
                        ->where('estatus', 'like', '%'.$estatus.'%')
                        // ->where('estatusEntrega', 'like', '%'.$estatusEntrega.'%') ///Pendiente
                        ->where('personaEntrega', 'like', '%'.$entrego.'%')
                        /////FILTRA LAS COLUMNAS CONCATENADAS/////
                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $gcmid='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'complementoGCMid' && $f['valor'] != ''){
                                $gcmid = $f['valor'];
                              }
                            }
                          $query->where('recepcionEquipos.GCMid', 'like', '%'.$gcmid.'%')
                          ->orwhere('complementoGCMid', 'like', '%'.$gcmid.'%');
                        })
                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $datos='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'datosCertificado' && $f['valor'] != ''){
                                $datos = $f['valor'];
                              }
                            }
                          $query->where('datosCertificado', 'like', '%'.$datos.'%')
                          ->orwhere('recurso', 'like', '%'.$datos.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $cliente='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
                                $cliente = $f['valor'];
                              }
                            }
                          $query->where('razonSocial', 'like', '%'.$cliente.'%')
                          ->orwhere('sucursales.sucursal', 'like', '%'.$cliente.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $equipo='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'serieEquipo' && $f['valor'] != ''){
                                $equipo = $f['valor'];
                              }
                            }
                          $query->where('serie', 'like', '%'.$equipo.'%')
                          ->orwhere('tipoEquipo', 'like', '%'.$equipo.'%')
                          ->orwhere('subtipoEquipo', 'like', '%'.$equipo.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $marcaModelo='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'marca' && $f['valor'] != ''){
                                $marcaModelo = $f['valor'];
                              }
                            }
                          $query->where('marca', 'like', '%'.$marcaModelo.'%')
                          ->orwhere('modelo', 'like', '%'.$marcaModelo.'%');
                        })
                        ///Termina filtros columnas concatenadas////
                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $estatusEntrega='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'estatusEntrega' && $f['valor'] != ''){
                                $estatusEntrega = $f['valor'];
                              }
                            }
                          $query->wherenull('estatusEntrega')
                          ->orwhere('estatusEntrega', 'like', '%'.$estatusEntrega.'%');
                        })
                        ->groupBy('recepcionEquipos.idEquipos')
                        ->orderBy($anexGrid->columna, $order)
                        ->skip($anexGrid->pagina)
                        ->take($anexGrid->limite)
                      ->get();

        $total = DB::table('recepcionEquipos')
                      ->select('recepcionEquipos.idEquipos','vistaSuperior','vistaFrente','placa_1','folioRecepcion', 'importancia',
                      'estatus','recepcionEquipos.activo','personaEntrega','estatusEntrega',
                      DB::raw('DATE_FORMAT(fechaRegistro,"%d %b %Y") AS fecha'),
                      DB::raw('CONCAT_WS(" ",recepcionEquipos.GCMid,complementoGCMid) AS gcmid'),
                      DB::raw('CONCAT_WS("--",razonSocial,sucursales.sucursal) AS clienteSucursal'),
                      DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                      // DB::raw('SUBSTR(datosCertificado,1,100) as datos'),
                      DB::raw('CONCAT_WS("-",serie,tipoEquipo,subtipoEquipo) AS equipo'),
                      DB::raw('CONCAT_WS("-",marca,modelo) AS marcaModelo'),
                      DB::raw('COUNT(partesreparacion.`idParte`) AS partes'))
                      ->leftJoin('clientes', 'recepcionEquipos.idc', '=', 'clientes.idc')
                      ->leftJoin('sucursales', 'recepcionEquipos.idSucursal', '=', 'sucursales.idSucursal')
                      ->leftJoin('tipoequipos', 'recepcionEquipos.idTipoEquipo', '=', 'tipoequipos.idTipoEquipo')
                      ->leftJoin('subtipoequipos', 'recepcionEquipos.idSubtipoEquipo', '=', 'subtipoequipos.idSubtipoEquipo')
                      ->leftJoin('partesreparacion', 'recepcionEquipos.idEquipos', '=', 'partesreparacion.idEquipos')
                    ->where('recepcionEquipos.idEquipos', '>', 0)
                    ->where('folioRecepcion', 'like', '%'.$folio.'%')
                    ->where('datosCertificado', 'like', '%'.$datos.'%')
                    ->where('estatus', 'like', '%'.$estatus.'%')
                    // ->where('estatusEntrega', 'like', '%'.$estatusEntrega.'%') //Pendiente
                    ->where('personaEntrega', 'like', '%'.$entrego.'%')
                        /////FILTRA LAS COLUMNAS CONCATENADAS/////
                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $datos='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'datosCertificado' && $f['valor'] != ''){
                                $datos = $f['valor'];
                              }
                            }
                          $query->where('datosCertificado', 'like', '%'.$datos.'%')
                          ->orwhere('recurso', 'like', '%'.$datos.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $gcmid='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'complementoGCMid' && $f['valor'] != ''){
                                $gcmid = $f['valor'];
                              }
                            }
                          $query->where('recepcionEquipos.GCMid', 'like', '%'.$gcmid.'%')
                          ->orwhere('complementoGCMid', 'like', '%'.$gcmid.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $cliente='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
                                $cliente = $f['valor'];
                              }
                            }
                          $query->where('razonSocial', 'like', '%'.$cliente.'%')
                          ->orwhere('sucursales.sucursal', 'like', '%'.$cliente.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $equipo='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'serieEquipo' && $f['valor'] != ''){
                                $equipo = $f['valor'];
                              }
                            }
                          $query->where('serie', 'like', '%'.$equipo.'%')
                          ->orwhere('tipoEquipo', 'like', '%'.$equipo.'%')
                          ->orwhere('subtipoEquipo', 'like', '%'.$equipo.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $marcaModelo='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'marca' && $f['valor'] != ''){
                                $marcaModelo = $f['valor'];
                              }
                            }
                          $query->where('marca', 'like', '%'.$marcaModelo.'%')
                          ->orwhere('modelo', 'like', '%'.$marcaModelo.'%');
                        })
                        ///Termina filtros columnas concatenadas////
                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $estatusEntrega='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'estatusEntrega' && $f['valor'] != ''){
                                $estatusEntrega = $f['valor'];
                              }
                            }
                          $query->wherenull('estatusEntrega')
                          ->orwhere('estatusEntrega', 'like', '%'.$estatusEntrega.'%');
                        })
                    ->count();

        }else{
          $registros = DB::table('recepcionEquipos')
                        ->select('recepcionEquipos.idEquipos','vistaSuperior','vistaFrente','placa_1','folioRecepcion',
                        'importancia','estatus','recepcionEquipos.activo','personaEntrega','estatusEntrega',
                        DB::raw('DATE_FORMAT(fechaRegistro,"%d %b %Y") AS fecha'),
                        DB::raw('CONCAT_WS("-",recepcionEquipos.GCMid,complementoGCMid) AS gcmid'),
                        DB::raw('CONCAT_WS(" / ",razonSocial,sucursales.sucursal) AS clienteSucursal'),
                        DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                        // DB::raw('SUBSTR(datosCertificado,1,100) as datos'),
                        DB::raw('CONCAT_WS("-",serie,tipoEquipo,subtipoEquipo) AS equipo'),
                        DB::raw('CONCAT_WS("-",marca,modelo) AS marcaModelo'),
                        DB::raw('COUNT(partesreparacion.`idParte`) AS partes'))
                        ->leftJoin('clientes', 'recepcionEquipos.idc', '=', 'clientes.idc')
                        ->leftJoin('sucursales', 'recepcionEquipos.idSucursal', '=', 'sucursales.idSucursal')
                        ->leftJoin('tipoequipos', 'recepcionEquipos.idTipoEquipo', '=', 'tipoequipos.idTipoEquipo')
                        ->leftJoin('subtipoequipos', 'recepcionEquipos.idSubtipoEquipo', '=', 'subtipoequipos.idSubtipoEquipo')
                        ->leftJoin('partesreparacion', 'recepcionEquipos.idEquipos', '=', 'partesreparacion.idEquipos')
                        ->where('recepcionEquipos.idEquipos', '>', 0)
                        ->where('folioRecepcion', 'like', '%'.$folio.'%')
                        ->where('datosCertificado', 'like', '%'.$datos.'%')
                        ->where('estatus', 'like', '%'.$estatus.'%')
                        ->where('personaEntrega', 'like', '%'.$entrego.'%')
                        /////FILTRA LAS COLUMNAS CONCATENADAS/////
                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $datos='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'datosCertificado' && $f['valor'] != ''){
                                $datos = $f['valor'];
                              }
                            }
                          $query->where('datosCertificado', 'like', '%'.$datos.'%')
                          ->orwhere('recurso', 'like', '%'.$datos.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $gcmid='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'complementoGCMid' && $f['valor'] != ''){
                                $gcmid = $f['valor'];
                              }
                            }
                          $query->where('recepcionEquipos.GCMid', 'like', '%'.$gcmid.'%')
                          ->orwhere('complementoGCMid', 'like', '%'.$gcmid.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $cliente='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
                                $cliente = $f['valor'];
                              }
                            }
                          $query->where('razonSocial', 'like', '%'.$cliente.'%')
                          ->orwhere('sucursales.sucursal', 'like', '%'.$cliente.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $equipo='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'serieEquipo' && $f['valor'] != ''){
                                $equipo = $f['valor'];
                              }
                            }
                          $query->where('serie', 'like', '%'.$equipo.'%')
                          ->orwhere('tipoEquipo', 'like', '%'.$equipo.'%')
                          ->orwhere('subtipoEquipo', 'like', '%'.$equipo.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $marcaModelo='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'marca' && $f['valor'] != ''){
                                $marcaModelo = $f['valor'];
                              }
                            }
                          $query->where('marca', 'like', '%'.$marcaModelo.'%')
                          ->orwhere('modelo', 'like', '%'.$marcaModelo.'%');
                        })
                        ///Termina filtros columnas concatenadas////
                        ->whereIn('recepcionEquipos.idEquipos',  function($query){
                          $sidu = Session::get('sesionidu');
                          $query->select('recepcionEquipos.idEquipos')
                          ->from('recepcionEquipos')
                          ->where('idu','=',$sidu);
                        })
                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $estatusEntrega='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'estatusEntrega' && $f['valor'] != ''){
                                $estatusEntrega = $f['valor'];
                              }
                            }
                          $query->wherenull('estatusEntrega')
                          ->orwhere('estatusEntrega', 'like', '%'.$estatusEntrega.'%');
                        })
                        ->groupBy('recepcionEquipos.idEquipos')
                        ->orderBy($anexGrid->columna, $order)
                        ->skip($anexGrid->pagina)
                        ->take($anexGrid->limite)
                      ->get();
          $total = DB::table('recepcionEquipos')
                      ->select('recepcionEquipos.idEquipos','vistaSuperior','vistaFrente','placa_1','folioRecepcion', 'importancia',
                      'estatus','recepcionEquipos.activo','personaEntrega','estatusEntrega',
                      DB::raw('DATE_FORMAT(fechaRegistro,"%d %b %Y") AS fecha'),
                      DB::raw('CONCAT_WS(" ",recepcionEquipos.GCMid,complementoGCMid) AS gcmid'),
                      DB::raw('CONCAT_WS("--",razonSocial,sucursales.sucursal) AS clienteSucursal'),
                      DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                      // DB::raw('SUBSTR(datosCertificado,1,100) as datos'),
                      DB::raw('CONCAT_WS("-",serie,tipoEquipo,subtipoEquipo) AS equipo'),
                      DB::raw('CONCAT_WS("-",marca,modelo) AS marcaModelo'),
                      DB::raw('COUNT(partesreparacion.`idParte`) AS partes'))
                      ->leftJoin('clientes', 'recepcionEquipos.idc', '=', 'clientes.idc')
                      ->leftJoin('sucursales', 'recepcionEquipos.idSucursal', '=', 'sucursales.idSucursal')
                      ->leftJoin('tipoequipos', 'recepcionEquipos.idTipoEquipo', '=', 'tipoequipos.idTipoEquipo')
                      ->leftJoin('subtipoequipos', 'recepcionEquipos.idSubtipoEquipo', '=', 'subtipoequipos.idSubtipoEquipo')
                      ->leftJoin('partesreparacion', 'recepcionEquipos.idEquipos', '=', 'partesreparacion.idEquipos')
                    ->where('recepcionEquipos.idEquipos', '>', 0)
                    ->where('folioRecepcion', 'like', '%'.$folio.'%')
                    ->where('datosCertificado', 'like', '%'.$datos.'%')
                    ->where('estatus', 'like', '%'.$estatus.'%')
                    ->where('personaEntrega', 'like', '%'.$entrego.'%')
                        /////FILTRA LAS COLUMNAS CONCATENADAS/////
                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $datos='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'datosCertificado' && $f['valor'] != ''){
                                $datos = $f['valor'];
                              }
                            }
                          $query->where('datosCertificado', 'like', '%'.$datos.'%')
                          ->orwhere('recurso', 'like', '%'.$datos.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $gcmid='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'complementoGCMid' && $f['valor'] != ''){
                                $gcmid = $f['valor'];
                              }
                            }
                          $query->where('recepcionEquipos.GCMid', 'like', '%'.$gcmid.'%')
                          ->orwhere('complementoGCMid', 'like', '%'.$gcmid.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $cliente='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
                                $cliente = $f['valor'];
                              }
                            }
                          $query->where('razonSocial', 'like', '%'.$cliente.'%')
                          ->orwhere('sucursales.sucursal', 'like', '%'.$cliente.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $equipo='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'serieEquipo' && $f['valor'] != ''){
                                $equipo = $f['valor'];
                              }
                            }
                          $query->where('serie', 'like', '%'.$equipo.'%')
                          ->orwhere('tipoEquipo', 'like', '%'.$equipo.'%')
                          ->orwhere('subtipoEquipo', 'like', '%'.$equipo.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $marcaModelo='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'marca' && $f['valor'] != ''){
                                $marcaModelo = $f['valor'];
                              }
                            }
                          $query->where('marca', 'like', '%'.$marcaModelo.'%')
                          ->orwhere('modelo', 'like', '%'.$marcaModelo.'%');
                        })
                        ///Termina filtros columnas concatenadas////
                        ->whereIn('recepcionEquipos.idEquipos',  function($query){
                          $sidu = Session::get('sesionidu');
                          $query->select('recepcionEquipos.idEquipos')
                          ->from('recepcionEquipos')
                          ->where('idu','=',$sidu);
                        })
                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $estatusEntrega='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'estatusEntrega' && $f['valor'] != ''){
                                $estatusEntrega = $f['valor'];
                              }
                            }
                          $query->wherenull('estatusEntrega')
                          ->orwhere('estatusEntrega', 'like', '%'.$estatusEntrega.'%');
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

  public function filtroEquiposAbajo(Request $request){
    $fechaInicio = $request->fechaInicio;
    $fechaFin = $request->fechaFin;
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $spat = Session::get('sesionpaterno');
    $smat = Session::get('sesionmaterno');
    $stipo = Session::get('sesiontipo');
    $consultaEmpesa = \DB::select("SELECT empresaSeguimiento FROM usuarios WHERE idu =?",[$sidu]);
    $seguimiento=$consultaEmpesa[0]->empresaSeguimiento;
    if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
        Session::flash('error', 'Es necesario logearse antes de continuar');
        return redirect()->route('login');
    }
    else{
      $anexGrid = new anexGrid();

      $cliente='';
      $folio='';
      $datos='';
      $estatus='';

        foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'folioRecepcion' && $f['valor'] != ''){
                $folio = $f['valor'];
              }

              if($f['columna'] == 'estatus' && $f['valor'] != ''){
                $estatus = $f['valor'];
              }
            }

      $order;
      $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';
      if ($seguimiento =="GCMyCYM"|| ($stipo == 'Administrador' || $stipo == 'Vendedor')) {
        $registros = DB::table('recepcionEquipos')
                        ->select('recepcionEquipos.idEquipos','vistaSuperior','vistaFrente','placa_1','folioRecepcion',
                        'importancia','estatus','recepcionEquipos.activo','personaEntrega','estatusEntrega',
                        DB::raw('DATE_FORMAT(fechaRegistro,"%d %b %Y") AS fecha'),
                        DB::raw('CONCAT_WS("-",recepcionEquipos.GCMid,complementoGCMid) AS gcmid'),
                        DB::raw('CONCAT_WS(" / ",razonSocial,sucursales.sucursal) AS clienteSucursal'),
                        DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                        // DB::raw('SUBSTR(datosCertificado,1,100) as datos'),
                        DB::raw('CONCAT_WS("-",serie,tipoEquipo,subtipoEquipo) AS equipo'),
                        DB::raw('CONCAT_WS("-",marca,modelo) AS marcaModelo'),
                        DB::raw('COUNT(partesreparacion.`idParte`) AS partes'))
                        ->leftJoin('clientes', 'recepcionEquipos.idc', '=', 'clientes.idc')
                        ->leftJoin('sucursales', 'recepcionEquipos.idSucursal', '=', 'sucursales.idSucursal')
                        ->leftJoin('tipoequipos', 'recepcionEquipos.idTipoEquipo', '=', 'tipoequipos.idTipoEquipo')
                        ->leftJoin('subtipoequipos', 'recepcionEquipos.idSubtipoEquipo', '=', 'subtipoequipos.idSubtipoEquipo')
                        ->leftJoin('partesreparacion', 'recepcionEquipos.idEquipos', '=', 'partesreparacion.idEquipos')
                        ->where('recepcionEquipos.idEquipos', '>', 0)
                        ->where('folioRecepcion', 'like', '%'.$folio.'%')
                        ->where('datosCertificado', 'like', '%'.$datos.'%')
                        ->where('estatus', 'like', '%'.$estatus.'%')
                        ->where('fechaRegistro','>=',$fechaInicio)
                          ->where('fechaRegistro','<=',$fechaFin)
                        /////FILTRA LAS COLUMNAS CONCATENADAS/////
                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $datos='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'datosCertificado' && $f['valor'] != ''){
                                $datos = $f['valor'];
                              }
                            }
                          $query->where('datosCertificado', 'like', '%'.$datos.'%')
                          ->orwhere('recurso', 'like', '%'.$datos.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $gcmid='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'complementoGCMid' && $f['valor'] != ''){
                                $gcmid = $f['valor'];
                              }
                            }
                          $query->where('recepcionEquipos.GCMid', 'like', '%'.$gcmid.'%')
                          ->orwhere('complementoGCMid', 'like', '%'.$gcmid.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $cliente='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
                                $cliente = $f['valor'];
                              }
                            }
                          $query->where('razonSocial', 'like', '%'.$cliente.'%')
                          ->orwhere('sucursales.sucursal', 'like', '%'.$cliente.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $equipo='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'serieEquipo' && $f['valor'] != ''){
                                $equipo = $f['valor'];
                              }
                            }
                          $query->where('serie', 'like', '%'.$equipo.'%')
                          ->orwhere('tipoEquipo', 'like', '%'.$equipo.'%')
                          ->orwhere('subtipoEquipo', 'like', '%'.$equipo.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $marcaModelo='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'marca' && $f['valor'] != ''){
                                $marcaModelo = $f['valor'];
                              }
                            }
                          $query->where('marca', 'like', '%'.$marcaModelo.'%')
                          ->orwhere('modelo', 'like', '%'.$marcaModelo.'%');
                        })
                        ///Termina filtros columnas concatenadas////
                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $estatusEntrega='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'estatusEntrega' && $f['valor'] != ''){
                                $estatusEntrega = $f['valor'];
                              }
                            }
                          $query->wherenull('estatusEntrega')
                          ->orwhere('estatusEntrega', 'like', '%'.$estatusEntrega.'%');
                        })
                        ->groupBy('recepcionEquipos.idEquipos')
                        ->orderBy($anexGrid->columna, $order)
                        ->skip($anexGrid->pagina)
                        ->take($anexGrid->limite)
                      ->get();

        $total = DB::table('recepcionEquipos')
                    ->select('recepcionEquipos.idEquipos','vistaSuperior','vistaFrente','placa_1','folioRecepcion', 'importancia',
                    'estatus','recepcionEquipos.activo','personaEntrega','estatusEntrega',
                    DB::raw('DATE_FORMAT(fechaRegistro,"%d %b %Y") AS fecha'),
                    DB::raw('CONCAT_WS(" ",recepcionEquipos.GCMid,complementoGCMid) AS gcmid'),
                    DB::raw('CONCAT_WS("--",razonSocial,sucursales.sucursal) AS clienteSucursal'),
                    DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                    // DB::raw('SUBSTR(datosCertificado,1,100) as datos'),
                    DB::raw('CONCAT_WS("-",serie,tipoEquipo,subtipoEquipo) AS equipo'),
                    DB::raw('CONCAT_WS("-",marca,modelo) AS marcaModelo'),
                    DB::raw('COUNT(partesreparacion.`idParte`) AS partes'))
                    ->leftJoin('clientes', 'recepcionEquipos.idc', '=', 'clientes.idc')
                    ->leftJoin('sucursales', 'recepcionEquipos.idSucursal', '=', 'sucursales.idSucursal')
                    ->leftJoin('tipoequipos', 'recepcionEquipos.idTipoEquipo', '=', 'tipoequipos.idTipoEquipo')
                    ->leftJoin('subtipoequipos', 'recepcionEquipos.idSubtipoEquipo', '=', 'subtipoequipos.idSubtipoEquipo')
                    ->leftJoin('partesreparacion', 'recepcionEquipos.idEquipos', '=', 'partesreparacion.idEquipos')
                    ->where('recepcionEquipos.idEquipos', '>', 0)
                    ->where('folioRecepcion', 'like', '%'.$folio.'%')
                    ->where('datosCertificado', 'like', '%'.$datos.'%')
                    ->where('estatus', 'like', '%'.$estatus.'%')
                    ->where('fechaRegistro','>=',$fechaInicio)
                    ->where('fechaRegistro','<=',$fechaFin)
                      /////FILTRA LAS COLUMNAS CONCATENADAS/////
                      ->where(function($query) {
                        $anexGrid = new anexGrid();
                        $datos='';
                        foreach($anexGrid->filtros as $f)
                          {
                            if($f['columna'] == 'datosCertificado' && $f['valor'] != ''){
                              $datos = $f['valor'];
                            }
                          }
                        $query->where('datosCertificado', 'like', '%'.$datos.'%')
                        ->orwhere('recurso', 'like', '%'.$datos.'%');
                      })

                      ->where(function($query) {
                        $anexGrid = new anexGrid();
                        $gcmid='';
                        foreach($anexGrid->filtros as $f)
                          {
                            if($f['columna'] == 'complementoGCMid' && $f['valor'] != ''){
                              $gcmid = $f['valor'];
                            }
                          }
                        $query->where('recepcionEquipos.GCMid', 'like', '%'.$gcmid.'%')
                        ->orwhere('complementoGCMid', 'like', '%'.$gcmid.'%');
                      })

                      ->where(function($query) {
                        $anexGrid = new anexGrid();
                        $cliente='';
                        foreach($anexGrid->filtros as $f)
                          {
                            if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
                              $cliente = $f['valor'];
                            }
                          }
                        $query->where('razonSocial', 'like', '%'.$cliente.'%')
                        ->orwhere('sucursales.sucursal', 'like', '%'.$cliente.'%');
                      })

                      ->where(function($query) {
                        $anexGrid = new anexGrid();
                        $equipo='';
                        foreach($anexGrid->filtros as $f)
                          {
                            if($f['columna'] == 'serieEquipo' && $f['valor'] != ''){
                              $equipo = $f['valor'];
                            }
                          }
                        $query->where('serie', 'like', '%'.$equipo.'%')
                        ->orwhere('tipoEquipo', 'like', '%'.$equipo.'%')
                        ->orwhere('subtipoEquipo', 'like', '%'.$equipo.'%');
                      })

                      ->where(function($query) {
                        $anexGrid = new anexGrid();
                        $marcaModelo='';
                        foreach($anexGrid->filtros as $f)
                          {
                            if($f['columna'] == 'marca' && $f['valor'] != ''){
                              $marcaModelo = $f['valor'];
                            }
                          }
                        $query->where('marca', 'like', '%'.$marcaModelo.'%')
                        ->orwhere('modelo', 'like', '%'.$marcaModelo.'%');
                      })
                      ///Termina filtros columnas concatenadas////
                      ->where(function($query) {
                        $anexGrid = new anexGrid();
                        $estatusEntrega='';
                        foreach($anexGrid->filtros as $f)
                          {
                            if($f['columna'] == 'estatusEntrega' && $f['valor'] != ''){
                              $estatusEntrega = $f['valor'];
                            }
                          }
                        $query->wherenull('estatusEntrega')
                        ->orwhere('estatusEntrega', 'like', '%'.$estatusEntrega.'%');
                      })
                  ->count();

      }else{
        $registros = DB::table('recepcionEquipos')
                        ->select('recepcionEquipos.idEquipos','vistaSuperior','vistaFrente','placa_1','folioRecepcion',
                        'importancia','estatus','recepcionEquipos.activo','personaEntrega','estatusEntrega',
                        DB::raw('DATE_FORMAT(fechaRegistro,"%d %b %Y") AS fecha'),
                        DB::raw('CONCAT_WS("-",recepcionEquipos.GCMid,complementoGCMid) AS gcmid'),
                        DB::raw('CONCAT_WS(" / ",razonSocial,sucursales.sucursal) AS clienteSucursal'),
                        DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                        // DB::raw('SUBSTR(datosCertificado,1,100) as datos'),
                        DB::raw('CONCAT_WS("-",serie,tipoEquipo,subtipoEquipo) AS equipo'),
                        DB::raw('CONCAT_WS("-",marca,modelo) AS marcaModelo'),
                        DB::raw('COUNT(partesreparacion.`idParte`) AS partes'))
                        ->leftJoin('clientes', 'recepcionEquipos.idc', '=', 'clientes.idc')
                        ->leftJoin('sucursales', 'recepcionEquipos.idSucursal', '=', 'sucursales.idSucursal')
                        ->leftJoin('tipoequipos', 'recepcionEquipos.idTipoEquipo', '=', 'tipoequipos.idTipoEquipo')
                        ->leftJoin('subtipoequipos', 'recepcionEquipos.idSubtipoEquipo', '=', 'subtipoequipos.idSubtipoEquipo')
                        ->leftJoin('partesreparacion', 'recepcionEquipos.idEquipos', '=', 'partesreparacion.idEquipos')
                        ->where('recepcionEquipos.idEquipos', '>', 0)
                        ->where('folioRecepcion', 'like', '%'.$folio.'%')
                        ->where('datosCertificado', 'like', '%'.$datos.'%')
                        ->where('estatus', 'like', '%'.$estatus.'%')
                        ->where('fechaRegistro','>=',$fechaInicio)
                          ->where('fechaRegistro','<=',$fechaFin)
                        /////FILTRA LAS COLUMNAS CONCATENADAS/////
                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $datos='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'datosCertificado' && $f['valor'] != ''){
                                $datos = $f['valor'];
                              }
                            }
                          $query->where('datosCertificado', 'like', '%'.$datos.'%')
                          ->orwhere('recurso', 'like', '%'.$datos.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $gcmid='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'complementoGCMid' && $f['valor'] != ''){
                                $gcmid = $f['valor'];
                              }
                            }
                          $query->where('recepcionEquipos.GCMid', 'like', '%'.$gcmid.'%')
                          ->orwhere('complementoGCMid', 'like', '%'.$gcmid.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $cliente='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
                                $cliente = $f['valor'];
                              }
                            }
                          $query->where('razonSocial', 'like', '%'.$cliente.'%')
                          ->orwhere('sucursales.sucursal', 'like', '%'.$cliente.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $equipo='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'serieEquipo' && $f['valor'] != ''){
                                $equipo = $f['valor'];
                              }
                            }
                          $query->where('serie', 'like', '%'.$equipo.'%')
                          ->orwhere('tipoEquipo', 'like', '%'.$equipo.'%')
                          ->orwhere('subtipoEquipo', 'like', '%'.$equipo.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $marcaModelo='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'marca' && $f['valor'] != ''){
                                $marcaModelo = $f['valor'];
                              }
                            }
                          $query->where('marca', 'like', '%'.$marcaModelo.'%')
                          ->orwhere('modelo', 'like', '%'.$marcaModelo.'%');
                        })
                        ///Termina filtros columnas concatenadas////
                        ->whereIn('recepcionEquipos.idEquipos',  function($query){
                          $sidu = Session::get('sesionidu');
                          $query->select('recepcionEquipos.idEquipos')
                          ->from('recepcionEquipos')
                          ->where('idu','=',$sidu);
                        })
                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $estatusEntrega='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'estatusEntrega' && $f['valor'] != ''){
                                $estatusEntrega = $f['valor'];
                              }
                            }
                          $query->wherenull('estatusEntrega')
                          ->orwhere('estatusEntrega', 'like', '%'.$estatusEntrega.'%');
                        })
                        ->groupBy('recepcionEquipos.idEquipos')
                        ->orderBy($anexGrid->columna, $order)
                        ->skip($anexGrid->pagina)
                        ->take($anexGrid->limite)
                      ->get();
        $total = DB::table('recepcionEquipos')
                      ->select('recepcionEquipos.idEquipos','vistaSuperior','vistaFrente','placa_1','folioRecepcion', 'importancia',
                      'estatus','recepcionEquipos.activo','personaEntrega','estatusEntrega',
                      DB::raw('DATE_FORMAT(fechaRegistro,"%d %b %Y") AS fecha'),
                      DB::raw('CONCAT_WS(" ",recepcionEquipos.GCMid,complementoGCMid) AS gcmid'),
                      DB::raw('CONCAT_WS("--",razonSocial,sucursales.sucursal) AS clienteSucursal'),
                      DB::raw('CONCAT_WS(" / ",(SUBSTR(datosCertificado,1,100)),recurso) AS datos'),
                      // DB::raw('SUBSTR(datosCertificado,1,100) as datos'),
                      DB::raw('CONCAT_WS("-",serie,tipoEquipo,subtipoEquipo) AS equipo'),
                      DB::raw('CONCAT_WS("-",marca,modelo) AS marcaModelo'),
                      DB::raw('COUNT(partesreparacion.`idParte`) AS partes'))
                      ->leftJoin('clientes', 'recepcionEquipos.idc', '=', 'clientes.idc')
                      ->leftJoin('sucursales', 'recepcionEquipos.idSucursal', '=', 'sucursales.idSucursal')
                      ->leftJoin('tipoequipos', 'recepcionEquipos.idTipoEquipo', '=', 'tipoequipos.idTipoEquipo')
                      ->leftJoin('subtipoequipos', 'recepcionEquipos.idSubtipoEquipo', '=', 'subtipoequipos.idSubtipoEquipo')
                      ->leftJoin('partesreparacion', 'recepcionEquipos.idEquipos', '=', 'partesreparacion.idEquipos')
                      ->where('recepcionEquipos.idEquipos', '>', 0)
                      ->where('folioRecepcion', 'like', '%'.$folio.'%')
                      ->where('datosCertificado', 'like', '%'.$datos.'%')
                      ->where('estatus', 'like', '%'.$estatus.'%')
                      ->where('fechaRegistro','>=',$fechaInicio)
                      ->where('fechaRegistro','<=',$fechaFin)
                        /////FILTRA LAS COLUMNAS CONCATENADAS/////
                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $datos='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'datosCertificado' && $f['valor'] != ''){
                                $datos = $f['valor'];
                              }
                            }
                          $query->where('datosCertificado', 'like', '%'.$datos.'%')
                          ->orwhere('recurso', 'like', '%'.$datos.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $gcmid='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'complementoGCMid' && $f['valor'] != ''){
                                $gcmid = $f['valor'];
                              }
                            }
                          $query->where('recepcionEquipos.GCMid', 'like', '%'.$gcmid.'%')
                          ->orwhere('complementoGCMid', 'like', '%'.$gcmid.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $cliente='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
                                $cliente = $f['valor'];
                              }
                            }
                          $query->where('razonSocial', 'like', '%'.$cliente.'%')
                          ->orwhere('sucursales.sucursal', 'like', '%'.$cliente.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $equipo='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'serieEquipo' && $f['valor'] != ''){
                                $equipo = $f['valor'];
                              }
                            }
                          $query->where('serie', 'like', '%'.$equipo.'%')
                          ->orwhere('tipoEquipo', 'like', '%'.$equipo.'%')
                          ->orwhere('subtipoEquipo', 'like', '%'.$equipo.'%');
                        })

                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $marcaModelo='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'marca' && $f['valor'] != ''){
                                $marcaModelo = $f['valor'];
                              }
                            }
                          $query->where('marca', 'like', '%'.$marcaModelo.'%')
                          ->orwhere('modelo', 'like', '%'.$marcaModelo.'%');
                        })
                        ///Termina filtros columnas concatenadas////
                        ->whereIn('recepcionEquipos.idEquipos',  function($query){
                          $sidu = Session::get('sesionidu');
                          $query->select('recepcionEquipos.idEquipos')
                          ->from('recepcionEquipos')
                          ->where('idu','=',$sidu);
                        })
                        ->where(function($query) {
                          $anexGrid = new anexGrid();
                          $estatusEntrega='';
                          foreach($anexGrid->filtros as $f)
                            {
                              if($f['columna'] == 'estatusEntrega' && $f['valor'] != ''){
                                $estatusEntrega = $f['valor'];
                              }
                            }
                          $query->wherenull('estatusEntrega')
                          ->orwhere('estatusEntrega', 'like', '%'.$estatusEntrega.'%');
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

  public function filtroTaller(){
    $anexGrid = new anexGrid();
    $gcmid='';
    $nombreParte='';
    $queReparacion='';
    $nombreEstatus='';
    foreach($anexGrid->filtros as $f)
          {
            if($f['columna'] == 'GCMidParte' && $f['valor'] != ''){
              $gcmid = $f['valor'];
            }
            if($f['columna'] == 'nombreParte' && $f['valor'] != ''){
              $nombreParte = $f['valor'];
            }
            if($f['columna'] == 'queReparacion' && $f['valor'] != ''){
              $queReparacion = $f['valor'];
            }
            if($f['columna'] == 'nombreEstatus' && $f['valor'] != ''){
              $nombreEstatus = $f['valor'];
            }
          }


    $order;
    $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

    $registros = DB::table('partesreparacion')
                    ->select('idParte','recepcionEquipos.vistaSuperior','fotoParte','GCMidParte','nombreParte','queReparacion','nombreEstatus',
                    DB::raw('CONCAT_WS("-",marca,modelo) AS marcaModelo'),
                    DB::raw('DATE_FORMAT(fechaRecepcion,"%d %b %Y") AS fechaRecepcion'),
                    DB::raw('DATE_FORMAT(fechaEntrega,"%d %b %Y") AS fechaEntrega'))
                    ->leftJoin('recepcionEquipos', 'partesreparacion.idEquipos', '=', 'recepcionEquipos.idEquipos')
                    ->leftJoin('estatusreparaciones', 'partesreparacion.idEstatus', '=', 'estatusreparaciones.idEstatus')
                    ->where('GCMidParte', 'like', '%'.$gcmid.'%')
                    ->where('nombreParte', 'like', '%'.$nombreParte.'%')
                    ->where('queReparacion', 'like', '%'.$queReparacion.'%')
                    ->where('nombreEstatus', 'like', '%'.$nombreEstatus.'%')
                    ->where(function($query) {
                      $anexGrid = new anexGrid();
                      $marcaModelo='';
                      foreach($anexGrid->filtros as $f)
                        {
                          if($f['columna'] == 'marca' && $f['valor'] != ''){
                            $marcaModelo = $f['valor'];
                          }
                        }
                      $query->where('marca', 'like', '%'.$marcaModelo.'%')
                      ->orwhere('modelo', 'like', '%'.$marcaModelo.'%');
                    })
                    ->orderBy($anexGrid->columna, $order)
                    ->skip($anexGrid->pagina)
                    ->take($anexGrid->limite)
                  ->get();

                  $total = DB::table('partesreparacion')
                  ->select('idParte','recepcionEquipos.vistaSuperior','fotoParte','GCMidParte','nombreParte','queReparacion','nombreEstatus',
                    DB::raw('CONCAT_WS("-",marca,modelo) AS marcaModelo'),
                    DB::raw('DATE_FORMAT(fechaRecepcion,"%d %b %Y") AS fechaRecepcion'),
                    DB::raw('DATE_FORMAT(fechaEntrega,"%d %b %Y") AS fechaEntrega'))
                    ->leftJoin('recepcionEquipos', 'partesreparacion.idEquipos', '=', 'recepcionEquipos.idEquipos')
                    ->leftJoin('estatusreparaciones', 'partesreparacion.idEstatus', '=', 'estatusreparaciones.idEstatus')
                    ->where('GCMidParte', 'like', '%'.$gcmid.'%')
                    ->where('nombreParte', 'like', '%'.$nombreParte.'%')
                    ->where('queReparacion', 'like', '%'.$queReparacion.'%')
                    ->where('nombreEstatus', 'like', '%'.$nombreEstatus.'%')
                    ->where(function($query) {
                      $anexGrid = new anexGrid();
                      $marcaModelo='';
                      foreach($anexGrid->filtros as $f)
                        {
                          if($f['columna'] == 'marca' && $f['valor'] != ''){
                            $marcaModelo = $f['valor'];
                          }
                        }
                      $query->where('marca', 'like', '%'.$marcaModelo.'%')
                      ->orwhere('modelo', 'like', '%'.$marcaModelo.'%');
                    })
                ->count();
      $data = array(
        'total' => $total,
        'data' => $registros
      );

      return response()->json($data, 200);
  }

  public function pdfEquipoReparado(Request $request){
    $idEquipo = $request->get('eWqt');

    $partes = partesreparacion::where ('idEquipos','=',$idEquipo)->get();

    $consulta = \DB::select("SELECT e.idEquipos, e.datosCertificado,e.folioRecepcion,e.recurso,e.serie,
    e.testRunInicial,e.testRunFinal,e.feedbackInicial,e.feedbackFinal,
    e.balerosInicial,e.balerosFinal,e.gearBoxInicial,e.gearBoxFinal,
    e.imanesInicial,e.imanesFinal,e.debanadosInicial,e.debanadosFinal,
    e.encoderInicial,e.encoderFinal,e.corrienteVer,e.corrienteHor,e.corrienteAx,
    e.tensionVer,e.tensionHor,e.tensionAx,e.tempMotor,e.tempTransmision,
    e.vibracionVer,e.vibracionHor,e.vibracionAx,e.enfriamientoVer,
    e.enfriamientoHor,e.enfriamientoAx,
    e.vistaSuperior,e.vistaFrente,e.placa_1,
    SUBSTR(datosCertificado,1,100) as datos,
    CONCAT (e.GCMid, '-', e.complementoGCMid)AS gcmid,c.razonSocial,c.numeroProveedor,s.sucursal,
    CONCAT (e.serie,'-',te.tipoEquipo,'-',se.subtipoEquipo) AS equipo,
    CONCAT (e.marca,'-',e.modelo) AS marcaModelo, DATE_FORMAT(e.fechaRegistro,'%d %b %Y') AS fecha
    FROM recepcionEquipos AS e
    INNER JOIN sucursales AS s ON s.idSucursal= e.idSucursal
    INNER JOIN clientes AS c ON c.idc= e.idc
    INNER JOIN tipoequipos AS te ON te.idTipoEquipo= e.idTipoEquipo
    INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= e.idSubtipoEquipo
    LEFT JOIN partesreparacion ON partesreparacion.idEquipos= e.idEquipos
    WHERE e.idEquipos=$idEquipo");

    $servicios = \DB::select("SELECT s.idServReparacionParte,s.idParte,p.GCMidParte,st.nombreServTaller,a.alcance
    FROM serviciosreparacionpartes AS s
    LEFT JOIN serviciostalleres AS st ON st.idServicioTaller = s.idServicioTaller
    LEFT JOIN alcances AS a ON a.idAlcance = s.idAlcance
    LEFT JOIN partesreparacion AS p ON p.idParte = s.idParte
    WHERE s.idParte IN (SELECT idParte FROM partesreparacion WHERE idEquipos=?)",[$idEquipo]);

    $datosFactura = \DB::select("SELECT fechaFactura,numeroFactura,factura,ordenCompra
    FROM facturas
    WHERE idCotizacion IN (SELECT idCotizacion FROM detallereparaciones WHERE idEquipos =?)",[$idEquipo]);

    // return view('pdfEquipoReparado')
    // ->with('consulta',$consulta[0])
    // ->with('partes',$partes)
    // ->with('servicios',$servicios);


    $pdf = \PDF::loadView('pdfEquipoReparado',['consulta'=>$consulta[0],'datosFactura'=>$datosFactura[0],'partes'=>$partes,'servicios'=>$servicios])->setPaper('a4', 'landscape');
    return $pdf->stream('etiquetaQr.pdf');
  }

  public function activarEquipo ($idEquipos){ 
    $equipos = \DB::UPDATE("update recepcionEquipos
    set equipoActivo ='Activado' where idEquipos=$idEquipos");

    $proceso ="Activación de Equipo";
    $mensaje="El equipo ha sido activado correctamente";
    return redirect('reporteEquipos');

  }

  public function cerrarEquipo ($idEquipos){ 
    $equipos = \DB::UPDATE("update recepcionEquipos
    set equipoActivo ='Cerrado' where idEquipos=$idEquipos");

    $proceso ="Cierre de Equipo";
    $mensaje="El equipo ha sido cerrado correctamente";
    return redirect('reporteEquipos');

  }

}

