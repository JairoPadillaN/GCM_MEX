<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\usuarios;
use App\proveedores;
use Session;

class loginProv extends Controller
{
/////////////////////////ACCESO DE PROVEEDORES LOGIN/////////////////////////
        public function accesoProveedores()
        {
        return view('accesoProveedores');

        }

    
    public function validauserProv(Request $request)
    {
    $correoProveedor= $request->input('correoProveedor');
    $passwordProv= md5($request->input('passwordProv'));

    $consulta = proveedores::where('correoProveedor','=',$correoProveedor)
                                    ->where('passwordProv','=',$passwordProv)
                                    ->where('activo','=','Si')
                                    ->get();

                        // return $consulta;
            if(count($consulta)==0 or $consulta[0]->activo== 'No')
                {
                    Session::flash('error','El proveedor no existe o la contraseña no es correcta');
                    return redirect()->route('accesoProveedores');
                }
            else    {

                Session::put('sesionnameprov',$consulta[0]->nombreProv);
                Session::put('sesionsegnom',$consulta[0]->segundoNomProv);
                Session::put('sesionap',$consulta[0]->apellidoProv);
                 Session::put('sesionidproveedor',$consulta[0]->idProveedor);
                //   Session::put('sesiontipo',$consulta[0]->tipo);

                //   $snameprov = Session::get('sesionnameprov');
                //   $sidprov = Session::get('sesionidproveedor');
                //   $ssegnom = Session::get('sesionsegnom');
                //   $sap = Session::get('sesionap');
                //   echo $snameprov . ''. $sidprov . ''. $ssegnom;

                  return redirect()->route('inicioProveedor');

                    }

                }


                public function principalProv()
             {
                  $snameprov = Session::get('sesionnameprov');
                  $sidprov = Session::get('sesionidproveedor');
                  $ssegnom = Session::get('sesionsegnom');
                  $sap = Session::get('sesionap');
                //   $stipo = Session::get('sesiontipo');
                  if( $sidprov == '' )
                  {
                    Session::flash('error','Es necesario loguearse antes de continuar');
                    return redirect()-> route('accesoProveedores');

                  }
                  else{


                   return view('principalProv');

                  }

             }

             public function cerrarsesionprov()
             {
                 Session::forget('sesionnameprov');
                 Session::forget('sesionidproveedor');
                //  Session::forget('sesiontipo');
                 Session::flush();
                 Session::flash('error','Sesión cerrada correctamente');
                 
                 return redirect()->route('accesoProveedores');
     
             }     
                  
}
