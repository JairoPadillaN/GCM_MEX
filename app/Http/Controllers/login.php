<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\usuarios;
use Session;

class login extends Controller
{


    public function login()
    {
   return view('login');

    }

     public function validauser(Request $request)
    {
    $correo= $request->input('correo');
    $password= md5($request->input('password'));
    $consulta = usuarios::where('correo','=',$correo)
                                    ->where('password','=',$password)
                                    ->where('activo','=','Si')
                                    ->get();
            if(count($consulta)==0 or $consulta[0]->activo== 'No')
                {
                    Session::flash('error','El usuario no existe o la contraseña no es correcta');
                    return redirect()->route('login');
                }
            else    {

                Session::put('sesionname',$consulta[0]->nombreUsuario);
                Session::put('sesionpaterno',$consulta[0]->aPaterno);
                Session::put('sesionmaterno',$consulta[0]->aMaterno);
                 Session::put('sesionidu',$consulta[0]->idu);
                  Session::put('sesiontipo',$consulta[0]->tipo);

                 /* $sname = Session::get('sesionname');
                  $sidu = Session::get('sesionidu');
                  $stipo = Session::get('sesiontipo');
                  echo $sname . ''. $sidu . ''. $stipo;*/

                  return redirect()->route('Inicio');

                    }

                }

            public function principal()
             {
                      $sname = Session::get('sesionname');
                  $sidu = Session::get('sesionidu');
                  $spat = Session::get('sesionpaterno');
                  $smat = Session::get('sesionmaterno');
                  $stipo = Session::get('sesiontipo');
                  if($sname == '' or $sidu == '' or $stipo==''  or $spat=='' or $smat=='')
                  {
                    Session::flash('error','Es necesario loguearse antes de continuar');
                    return redirect()-> route('login');

                  }
                  else{


                   return view('principal');

                  }

             }



         public function cerrarsesionuser()
        {
            Session::forget('sesionname');
            Session::forget('sesionidu');
            Session::forget('sesiontipo');
            Session::flush();
            Session::flash('error','Sesion Cerrada Correctamente');
            return redirect()->route('login');

        }
/////////////////////////////CIERRA LOGIN////////////////////////////////////////////777                  
    }
