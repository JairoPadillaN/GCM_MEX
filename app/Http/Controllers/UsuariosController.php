<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\usuarios;
use App\usuariosacargos;
use Session;

class UsuariosController extends Controller
{
  public function AltaUsuarios(){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
        Session::flash('error', 'Es necesario logearse antes de continuar');
        return redirect()->route('login');
    }
    else{
      $consulta= usuarios::orderby('idu','desc')
       ->take(1)
       ->get();
       return view ('altaUsuarios');
     }
  }

  public function GuardarUsuarios(Request $request){
    $nombreUsuario = $request-> nombreUsuario;
    $aPaterno = $request-> aPaterno;
    $aMaterno = $request-> aMaterno;
    $correo = $request-> correo;
    $password = $request-> password;
    $tipo = $request-> tipo;    
    $empresaSeguimiento = $request-> empresaSeguimiento;
    $calle = $request-> calle;
    $num = $request-> num;
    $colonia = $request-> colonia;
    $municipio = $request-> municipio;
    $estado = $request-> estado;
    $telCasa = $request-> telCasa;
    $telCel = $request-> telCel;
    $licencia = $request-> licencia;

    $this->validate ($request,[
    'nombreUsuario'=>['required'],
    'aPaterno'=>['required'],
    'aMaterno'=>['required'],
    'correo'=>'required|email',
    'password'=>['required'],
    'tipo'=>['required'],
    'empresaSeguimiento'=>['required'],
    'calle'=>['required'],
    'num'=>['regex:/^[0-9]*$/'],
    'colonia'=>['required'],
    'estado'=>['required'],
    'telCasa'=>['regex:/^[0-9]{10}$/'],
    'telCel'=>['regex:/^[0-9]{10}$/'],
    'licencia'=>['required'],
    'imagen'=>'mimes:jpeg,png,gif,jpg',]);
    
    $file = $request->file('imagen');
    if($file!=""){
      $ldate = date('Ymd_His_');
      $img = $file->getClientOriginalName();
      $img2 = $ldate.$img;
      \Storage::disk('local')->put($img2, \File::get($file));
    }
    else{
      $img2 ="sinfoto.png";
    }

    $usuario=new usuarios;
    $usuario-> nombreUsuario = $request-> nombreUsuario;
    $usuario-> aPaterno = $request-> aPaterno;
    $usuario-> aMaterno = $request-> aMaterno;
    $usuario-> correo = $request-> correo;
    $usuario-> password = md5($request->password);
    $usuario-> tipo = $request-> tipo;
    $usuario-> empresaSeguimiento = $request-> empresaSeguimiento;
    $usuario-> calle = $request-> calle;
    $usuario-> num = $request-> num;
    $usuario-> colonia = $request-> colonia;
    $usuario-> municipio = $request-> municipio;
    $usuario-> estado = $request-> estado;
    $usuario-> telCasa = $request-> telCasa;
    $usuario-> telCel = $request-> telCel;
    $usuario-> licencia = $request-> licencia;
    $usuario-> imagen=$img2;
    $usuario-> activo='Si';
    $usuario-> save();

    $proceso='Alta de usuarios';
    $mensaje="Registro guardado correctamente";
  
    return view ('mensajeUsuarios')
      ->with('proceso',$proceso)
      ->with('mensaje',$mensaje);
  }

  public function ReporteUsuarios(){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
        Session::flash('error', 'Es necesario logearse antes de continuar');
        return redirect()->route('login');
    }
    else{
      $consulta = \DB::select("SELECT p.idu,p.nombreUsuario,p.aPaterno,
      p.aMaterno,p.correo,p.password,p.imagen,p.tipo,p.empresaSeguimiento,p.calle,p.num,p.colonia,p.municipio,p.estado,
      p.telCasa,p.telCel,p.licencia,p.activo FROM usuarios AS p");
      return view ('reporteUsuarios')->with('consulta',$consulta);
    }
  }

  public function modificarUsuario($idu){

    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');


    $consulta = usuarios::Where ('idu','=',$idu)->get();
    $tipoUsuario = $consulta[0]->tipo;
    $consultaImg = \DB::select("SELECT p.imagen FROM usuarios AS p Where idu = $idu ");

    $usuarios = \DB::select("SELECT idu, CONCAT(nombreUsuario,' ',aPaterno,' ',aMaterno) AS usuario,tipo 
    FROM usuarios 
    WHERE tipo = '$tipoUsuario' AND activo = 'Si' AND idu != $idu" );

    $us = \DB::select("SELECT COUNT(*) AS cuantos 
        FROM usuariosacargos
        WHERE idu = $idu");
      
      $cuantos= $us[0]->cuantos;

      $usuariosAsignados=\DB::select("SELECT usc.idACargo,usc.idu, usc.iduAsignado,CONCAT(u.nombreUsuario,' ',u.aPaterno,' ',u.aMaterno) AS usuario
        FROM usuariosacargos AS usc
        INNER JOIN usuarios AS u ON u.idu = usc.iduAsignado
        WHERE usc.idu = $idu");

    return view ('editarUsuarios')
    ->with('consultaImg',$consultaImg)
    ->with ('consulta', $consulta[0])
    ->with('usuarios',$usuarios)
    ->with('cuantos',$cuantos)
    ->with('usuariosAsignados',$usuariosAsignados);
  }

  public function editarUsuario(Request $request){
    $idu=$request-> idu;//Esta valor lo toma del text oculto en el formulario es el id para saber a cual de modificara
    $nombreUsuario = $request-> nombreUsuario;
    $aPaterno = $request-> aPaterno;
    $aMaterno = $request-> aMaterno;
    $correo = $request-> correo;
    $password = $request-> password;
    $tipo = $request-> tipo;
    $empresaSeguimiento = $request-> empresaSeguimiento;
    $calle = $request-> calle;
    $num = $request-> num;
    $colonia = $request-> colonia;
    $municipio = $request-> municipio;
    $estado = $request-> estado;
    $telCasa = $request-> telCasa;
    $telCel = $request-> telCel;
    $licencia = $request-> licencia;

    $this->validate ($request,[
    'nombreUsuario'=>['required'],
    'aPaterno'=>['required'],
    'aMaterno'=>['required'],
    'correo'=>['regex:/^[a-z, A-Z,@,0-9,.,_,-]*$/'],
    'password'=>['required'],
    'tipo'=>['required'],
    'empresaSeguimiento'=>['required'],
    'calle'=>['regex:/^[A-Z, a-z, ,á,é,i,ó,ú,ñ,0-9]*$/'],
    'num'=>['regex:/^[0-9]*$/'],
    'colonia'=>['required'],
    'estado'=>['required'],
    'telCasa'=>['regex:/^[0-9]{10}$/'],
    'telCel'=>['regex:/^[0-9]{10}$/'],
    'licencia'=>['required'],
    'imagen'=>'mimes:jpeg,png,gif,jpg',]);

    $file = $request->file('imagen');
    if($file!=""){
      $ldate = date('Ymd_His_');
      $img = $file->getClientOriginalName();
      $img2 = $ldate.$img;
      \Storage::disk('local')->put($img2, \File::get($file));
    }
      $usuario = usuarios::find($idu);
    if($file!=""){
      $usuario->imagen=$img2;
    }

    $usuario-> idu=$request-> idu;
    $usuario-> nombreUsuario = $request-> nombreUsuario;
    $usuario-> aPaterno = $request-> aPaterno;
    $usuario-> aMaterno = $request-> aMaterno;
    $usuario-> correo = $request-> correo;
    $usuario-> password = md5($request->password);
    $usuario-> tipo = $request-> tipo;
    $usuario-> empresaSeguimiento = $request-> empresaSeguimiento;
    $usuario-> calle = $request-> calle;
    $usuario-> num = $request-> num;
    $usuario-> colonia = $request-> colonia;
    $usuario-> municipio = $request-> municipio;
    $usuario-> estado = $request-> estado;
    $usuario-> telCasa = $request-> telCasa;
    $usuario-> telCel = $request-> telCel;
    $usuario-> licencia = $request-> licencia;
    $usuario-> save();

    $proceso='Modificacion de usuarios';
    $mensaje="Usuario modificado correctamente";
    return view ('mensajeUsuarios')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }

  public function eliminarUsuario ($idu){/*Rerecibe este parametro y lo guarda en esa variable*/
    $usuario = \DB::UPDATE("update usuarios set activo ='No' where idu=$idu");

    $proceso ="Eliminacion de usuarios";
    $mensaje="El usuario ha sido desactivado correctamente";
    return view('mensajeUsuarios')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }

  public function restaurarUsuario ($idu){ //restarura el valos de NO a SI en el campo activo
    $usuario = \DB::UPDATE("update usuarios set activo ='Si' where idu=$idu");

    $proceso ="Restauracion de usuarios";
    $mensaje="El usuario ha sido activado correctamente";
    return view('mensajeUsuarios')
    ->with('proceso',$proceso)
    ->with('mensaje',$mensaje);
  }


  public function asignarUsuarios(Request $request){
    $idu = $request->idu;
    $iduAsignado = $request->iduAsignado;

       $us = \DB::select("SELECT COUNT(*) AS cuantos 
        FROM usuariosacargos
        WHERE idu = $idu AND iduAsignado = $iduAsignado");
      
      $cuantos= $us[0]->cuantos;

    if($cuantos == 0){
      $usuarios = new usuariosacargos;
      $usuarios->idu=$request->idu;
      $usuarios->iduAsignado=$request->iduAsignado;
      $usuarios->save();   

        $usuariosAsignados=\DB::select("SELECT usc.idACargo,usc.idu, usc.iduAsignado,CONCAT(u.nombreUsuario,' ',u.aPaterno,' ',u.aMaterno) AS usuario
        FROM usuariosacargos AS usc
        INNER JOIN usuarios AS u ON u.idu = usc.iduAsignado
        WHERE usc.idu = $idu");

      return view('reporteUsuariosAsignados')
      ->with('cuantos',$cuantos)
      ->with('usuariosAsignados',$usuariosAsignados);

    }else{

      $usuariosAsignados=\DB::select("SELECT usc.idACargo,usc.idu, usc.iduAsignado,CONCAT(u.nombreUsuario,' ',u.aPaterno,' ',u.aMaterno) AS usuario
        FROM usuariosacargos AS usc
        INNER JOIN usuarios AS u ON u.idu = usc.iduAsignado
        WHERE usc.idu = $idu");

        return view('reporteUsuariosAsignados')
        ->with('cuantos',$cuantos)
        ->with('usuariosAsignados',$usuariosAsignados);


    }

  }

  public function borrarUsuariosAsignados(Request $request){
    $idACargo=$request->idACargo;
    $idu=$request->idu;

    // return $idACargo;
      $cuantos = 0;  
      $usuarios = usuariosacargos::find($idACargo)->delete();
    
      
        $usuariosAsignados=\DB::select("SELECT usc.idACargo,usc.idu, usc.iduAsignado,CONCAT(u.nombreUsuario,' ',u.aPaterno,' ',u.aMaterno) AS usuario
        FROM usuariosacargos AS usc
        INNER JOIN usuarios AS u ON u.idu = usc.iduAsignado
        WHERE usc.idu = $idu");
    
    return view('reporteUsuariosAsignados')
        ->with('cuantos',$cuantos)
        ->with('usuariosAsignados',$usuariosAsignados);
  }
  
}
