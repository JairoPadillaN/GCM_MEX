<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Session;
use App\proveedores;
use App\ordenCompra;
use App\cuentasproveedores;
class proveedoresController extends Controller
{
  public function altaProveedores(){
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
			$consulta= proveedores::orderby('idProveedor','desc')
               ->take(1)
               ->get();
			return view ('altaProveedores');
}
}

public function guardarProveedores(Request $request)
{
        $tituloProv=$request->tituloProv;
        $nombreProv=$request->nombreProv;
        $segundoNomProv=$request->segundoNomProv;
        $apellidoProv=$request->apellidoProv;      
        $razonSocialProv=$request->razonSocialProv;
        $rfcProveedor=$request->rfcProveedor;
        $iniciales=$request->iniciales;
        $calleProv=$request->calleProv;
        $ciudadProv=$request->ciudadProv;
        $codigoPostalProv=$request->codigoPostalProv;
        $estadoProv=$request->estadoProv;
        $paisProv=$request->paisProv;
        $notasProv=$request->notasProv;
        $usuarioProv=$request->usuarioProv;
        $passwordProv=$request->passwordProv;
        $correoProveedor=$request->correoProveedor;
        $telefonoProveedor=$request->telefonoProveedor;
        $movilProv=$request->movilProv;
        $faxProv=$request->faxProv;
        $otroProv=$request->otroProv;
        $sitioWebProv=$request->sitioWebProv;
        $condicionesProv=$request->condicionesProv;
        $diasPago=$request->diasPago;
        $diasVigencia=$request->diasVigencia;
        // $contactoProveedor=$request->contactoProveedor;
        // $telefonoProveedor=$request->telefonoProveedor;
        
        // $this->validate ($request,[
        // 'razonSocialProv'=>['required'],
        // 'rfcProveedor'=>['required'],
        // 'correoProveedor'=>['email']
        // ]);


        $file = $request->file('archivoCuentas');
        //obtenemos el nombre del archivoCuentas
        if($file!=""){
          $archivoCuentas =  time()."_".$file->getClientOriginalName();
          //indicamos que queremos guardar un nuevo archivoCuentas en el disco local
          \Storage::disk('local')->put($archivoCuentas,  \File::get($file));
        }else{
          $archivoCuentas ="Sin archivo";
        }

        $file2 = $request->file('archivoConstanciaFiscal');
        //obtenemos el nombre del archivoConstanciaFiscal
        if($file2!=""){
          $archivoConstanciaFiscal =  time()."_".$file2->getClientOriginalName();
          //indicamos que queremos guardar un nuevo archivoConstanciaFiscal en el disco local
          \Storage::disk('local')->put($archivoConstanciaFiscal,  \File::get($file2));
        }else{
          $archivoConstanciaFiscal ="Sin archivo";
        }

          $proveedor = new proveedores;
          $proveedor->idProveedor = $request->idProveedor;
          $proveedor->tituloProv = $request->tituloProv;
          $proveedor->nombreProv = $request->nombreProv;
          $proveedor->segundoNomProv = $request->segundoNomProv;
          $proveedor->apellidoProv = $request->apellidoProv;

          $proveedor->tituloProv2=$request->tituloProv2;
          $proveedor->nombreProv2=$request->nombreProv2;
          $proveedor->segundoNomProv2=$request->segundoNomProv2;
          $proveedor->apellidoProv2=$request->apellidoProv2;
          $proveedor->correoProveedor2=$request->correoProveedor2;

          $proveedor->razonSocialProv = $request->razonSocialProv;
          $proveedor->rfcProveedor = $request->rfcProveedor;
          $proveedor->iniciales = $request->iniciales;
          $proveedor->calleProv = $request->calleProv;
          $proveedor->ciudadProv = $request->ciudadProv;
          $proveedor->codigoPostalProv = $request->codigoPostalProv;
          $proveedor->estadoProv = $request->estadoProv;
          $proveedor->paisProv = $request->paisProv;
          $proveedor->notasProv = $request->notasProv;
          $proveedor->usuarioProv = $request->usuarioProv;
          $proveedor-> passwordProv = md5($request->passwordProv);
          $proveedor->correoProveedor = $request->correoProveedor;
          $proveedor->telefonoProveedor = $request->telefonoProveedor;
          $proveedor->telefonoProveedor2 = $request->telefonoProveedor2;
          $proveedor->movilProv = $request->movilProv;
          $proveedor->faxProv = $request->faxProv;
          $proveedor->otroProv = $request->otroProv;
          $proveedor->sitioWebProv = $request->sitioWebProv;
          $proveedor->condicionesProv = $request->condicionesProv;
          $proveedor->diasPago = $request->diasPago;
          $proveedor->diasVigencia = $request->diasVigencia;
          $proveedor-> archivoCuentas = $archivoCuentas;
          $proveedor-> constanciaFiscal = $archivoConstanciaFiscal;
          $proveedor-> region = $request->region;
          $proveedor->activo='Si';
          $proveedor->save();

          $proceso ="Alta de proveedores";
          $mensaje="Registro guardado correctamente";
          return view('mensajeProveedor')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
          
}

  public function reporteProveedores(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }else{
      // return $stipo;
      // if ($stipo=="Administrador") {
        $consulta = \DB::select("SELECT * FROM proveedores");
      // }else{
      //   $consulta = \DB::select("SELECT * FROM proveedores WHERE region='Nacional'");
      // }
      
      return view ('reporteProveedores')
      ->with('consulta',$consulta);
    }
		
  }
	
	 public function modificarProveedores($idProveedor){
           $consulta = proveedores::Where ('idProveedor','=',$idProveedor)->get();

           $cuentas = \DB::select("SELECT COUNT(*) AS cuantos 
            FROM cuentasproveedores
            WHERE idProveedor = $idProveedor");
    
            $cuantos= $cuentas[0]->cuantos;

            $cuentasagregadas=\DB::select("SELECT idCuentaProv, ap.idProveedor, p.nombreProv,tipoCuenta,numeroCuentaProv, clabeInterProv,tipoMonedaProv,
            bancoProv, sucursal, direccion, observaciones FROM cuentasproveedores AS ap
            INNER JOIN proveedores AS p ON p.idProveedor = ap.idProveedor
            WHERE p.idProveedor = $idProveedor");

            $consultaArchivoCu = \DB::select("SELECT v.archivoCuentas,v.constanciaFiscal FROM proveedores AS v Where idProveedor = $idProveedor ");



           return view ('editarProveedores')
           ->with ('consulta', $consulta[0])
           ->with ('cuantos', $cuantos)
           ->with ('cuentasagregadas', $cuentasagregadas)
           ->with ('consultaArchivoCu', $consultaArchivoCu);
           
    }

	 public function editarProveedores(Request $request){
      $idProveedor=$request->idProveedor;
      $tituloProv=$request->tituloProv;
      $nombreProv=$request->nombreProv;
      $segundoNomProv=$request->segundoNomProv;
      $apellidoProv=$request->apellidoProv;
      $razonSocialProv=$request->razonSocialProv;
      $rfcProveedor=$request->rfcProveedor;
      $iniciales=$request->iniciales;
      $calleProv=$request->calleProv;
      $ciudadProv=$request->ciudadProv;
      $codigoPostalProv=$request->codigoPostalProv;
      $estadoProv=$request->estadoProv;
      $paisProv=$request->paisProv;
      $notasProv=$request->notasProv;
      $usuarioProv=$request->usuarioProv;
      $passwordProv=$request->passwordProv;
      $correoProveedor=$request->correoProveedor;
      $telefonoProveedor=$request->telefonoProveedor;
      $movilProv=$request->movilProv;
      $faxProv=$request->faxProv;
      $otroProv=$request->otroProv;
      $sitioWebProv=$request->sitioWebProv;
      $condicionesProv=$request->condicionesProv;
      $diasPago=$request->diasPago;
      $diasVigencia=$request->diasVigencia;
      $archivoCuentas=$request->archivoCuentas;
          
      $file = $request->file('archivoCuentas');        
      if($file!=""){
        $archivoCuentas =  time()."_".$file->getClientOriginalName();
        \Storage::disk('local')->put($archivoCuentas,  \File::get($file));
      }
        
      $file2 = $request->file('constanciaFiscal');
      if($file2!=""){
        $archivoConstanciaFiscal =  time()."_".$file2->getClientOriginalName();
        \Storage::disk('local')->put($archivoConstanciaFiscal,  \File::get($file2));
      }  

      $proveedor = proveedores::find($idProveedor);
      $proveedor->tituloProv = $request->tituloProv;
      $proveedor->nombreProv = $request->nombreProv;
      $proveedor->segundoNomProv = $request->segundoNomProv;
      $proveedor->apellidoProv = $request->apellidoProv;
      $proveedor->tituloProv2=$request->tituloProv2;
      $proveedor->nombreProv2=$request->nombreProv2;
      $proveedor->segundoNomProv2=$request->segundoNomProv2;
      $proveedor->apellidoProv2=$request->apellidoProv2;
      $proveedor->correoProveedor2=$request->correoProveedor2;
      $proveedor->razonSocialProv = $request->razonSocialProv;
      $proveedor->rfcProveedor = $request->rfcProveedor;
      $proveedor->iniciales = $request->iniciales;
      $proveedor->calleProv = $request->calleProv;
      $proveedor->ciudadProv = $request->ciudadProv;
      $proveedor->codigoPostalProv = $request->codigoPostalProv;
      $proveedor->estadoProv = $request->estadoProv;
      $proveedor->paisProv = $request->paisProv;
      $proveedor->notasProv = $request->notasProv;
      $proveedor->usuarioProv = $request->usuarioProv;
      $proveedor-> passwordProv = md5($request->passwordProv);
      $proveedor->correoProveedor = $request->correoProveedor;
      $proveedor->telefonoProveedor = $request->telefonoProveedor;
      $proveedor->telefonoProveedor2 = $request->telefonoProveedor2;
      $proveedor->movilProv = $request->movilProv;
      $proveedor->faxProv = $request->faxProv;
      $proveedor->otroProv = $request->otroProv;
      $proveedor->sitioWebProv = $request->sitioWebProv;
      $proveedor->condicionesProv = $request->condicionesProv;
      $proveedor->diasPago = $request->diasPago;
      $proveedor->diasVigencia = $request->diasVigencia;           
      if($file!=""){
      $proveedor-> archivoCuentas = $archivoCuentas;
      }
      if($file2!=""){
      $proveedor-> constanciaFiscal = $archivoConstanciaFiscal;
      }
      $proveedor-> region = $request-> region;
      $proveedor-> save();

      $proceso='Modificación de proveedor';
      $mensaje="Proveedor modificado correctamente";
      return view ('mensajeProveedor')
        ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);
    }

    public function eliminarProveedores ($idProveedor){/*Rerecibe este parametro y lo guarda en esa variable*/
             $proveedor = \DB::UPDATE("update proveedores
             set activo ='No' where idProveedor=$idProveedor");

           $proceso ="Eliminación de proveedor";
           $mensaje="El proveedor ha sido desactivado correctamente";
           return view('mensajeProveedor')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);
    }
	
	  public function restaurarProveedores($idProveedor){ //restarura el valos de NO a SI en el campo activo
           $proveedor= \DB::UPDATE("update proveedores
           set activo ='Si' where idProveedor=$idProveedor");

           $proceso ="Restauración de proveedor";
           $mensaje="El proveedor ha sido activado correctamente";
           return view('mensajeProveedor')
                ->with('proceso',$proceso)
                ->with('mensaje',$mensaje);
    }

    public function subirArProv($idOrden){ //vista para subir archivos de orden de compra(proveedor)
      // return $idOrden;
      $idOrden = Crypt::decrypt($idOrden);
      $consulta = ordenCompra::where('idOrden','=',$idOrden)
                                    ->where('activo','=','Si')
                                    ->orderBy('fechaOrden','DESC')
                                    ->get();
                          

      
      $length = 10; 
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      $fechaActual = date('d-m-Y');
      $dia = date('d');
      $mes = date('m');
      $año = date('Y');
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      
      $folioConfirmacion = $año.$mes.$dia.'-'.$randomString;
                  
      $sumaTotalImpuestos=\DB::select("SELECT oc.moneda, oc.idFactura, FORMAT(SUM(precioProducto),2) AS total,
      FORMAT(SUM(isrDinero),2) as isrDineroTotal,
      FORMAT(SUM(ivaDinero),2) as ivaDineroTotal,
      FORMAT(SUM(subtotal),2) as importe
      FROM productosOrdenCompra AS pc
      INNER JOIN ordenCompra as oc ON pc.idOrden=oc.idOrden
      WHERE pc.idOrden= $idOrden");
      
      return view("archivosOCprov", compact('folioConfirmacion'))
      ->with('sumaTotalImpuestos',$sumaTotalImpuestos[0])
      ->with('idOrden',$idOrden)
      ->with('consulta',$consulta);
          
    }

    public function agregarCuentas (Request $request){
      $idProveedor=$request-> idProveedor;
      $tipoCuenta=$request->tipoCuenta;
      $nombreRespCuenta=$request->nombreRespCuenta;
      $numeroCuentaProv=$request->numeroCuentaProv;
      $clabeInterProv=$request->clabeInterProv;
      $tipoMonedaProv=$request->tipoMonedaProv;
      $bancoProv=$request->bancoProv;
      $sucursal=$request->sucursal;
      $direccion=$request->direccion;
      $observaciones=$request->observaciones;

      $cuentas = \DB::select("SELECT COUNT(*) AS cuantos 
      FROM cuentasproveedores
      WHERE idProveedor = $idProveedor");
    
      $cuantos= $cuentas[0]->cuantos;

      $cuentasProv = new cuentasproveedores;
      $cuentasProv->idProveedor = $request->idProveedor;
      $cuentasProv->tipoCuenta = $request->tipoCuenta;
      $cuentasProv->nombreRespCuenta = $request->nombreRespCuenta;
      $cuentasProv->numeroCuentaProv = $request->numeroCuentaProv;
      $cuentasProv->clabeInterProv = $request->clabeInterProv;
      $cuentasProv->tipoMonedaProv = $request->tipoMonedaProv;
      $cuentasProv->bancoProv = $request->bancoProv;
      $cuentasProv->sucursal = $request->sucursal;
      $cuentasProv->direccion = $request->direccion;
      $cuentasProv->observaciones = $request->observaciones;
      $cuentasProv->save();

      // return "Guardado";

      $cuentasagregadas=\DB::select("SELECT idCuentaProv, ap.idProveedor, p.nombreProv,tipoCuenta, numeroCuentaProv, clabeInterProv,tipoMonedaProv,
      bancoProv, sucursal, direccion, observaciones FROM cuentasproveedores AS ap
      INNER JOIN proveedores AS p ON p.idProveedor = ap.idProveedor
      WHERE p.idProveedor = $idProveedor");

    return view('reporteCuentasProv')
    ->with('cuantos',$cuantos)
    ->with('cuentasagregadas',$cuentasagregadas);

    }

    public function borrarCuenta(Request $request){
      $idCuentaProv=$request->idCuentaProv;
      $idProveedor=$request->idProveedor;
  
      // return $idACargo;
        // $cuantos = 0;  
        $cuentas = cuentasproveedores::find($idCuentaProv)->delete();
      
        
        $cuentasagregadas=\DB::select("SELECT idCuentaProv, ap.idProveedor, p.nombreProv, numeroCuentaProv, clabeInterProv,tipoMonedaProv,
        bancoProv, sucursal, direccion, observaciones FROM cuentasproveedores AS ap
        INNER JOIN proveedores AS p ON p.idProveedor = ap.idProveedor
        WHERE p.idProveedor = $idProveedor");
  
      return view('reporteCuentasProv')
      // ->with('cuantos',$cuantos)
      ->with('cuentasagregadas',$cuentasagregadas);
	
    }


  public function guardarCuentasProveedor(Request $request){
    $idProveedor=$request->idProveedor;
            
    $file = $request->file('cuentaBancaria1');
    //obtenemos el nombre del archivoCuentaBancaria1
    if($file!=""){
      $archivoCuentaBancaria1 =  time()."_".$file->getClientOriginalName();
      //indicamos que queremos guardar un nuevo archivoCuentaBancaria1 en el disco local
      \Storage::disk('local')->put($archivoCuentaBancaria1,  \File::get($file));
    }

    $file2 = $request->file('constanciaFiscal');
    //obtenemos el nombre del archivoConstanciaFiscal
    if($file2!=""){
      $archivoConstanciaFiscal =  time()."_".$file2->getClientOriginalName();
      //indicamos que queremos guardar un nuevo archivoConstanciaFiscal en el disco local
      \Storage::disk('local')->put($archivoConstanciaFiscal,  \File::get($file2));
    }

    $proveedor = proveedores::find($idProveedor);
    // $proveedor-> cuentaBancaria1 = $archivoCuentaBancaria1;
    if($file!=""){
      $proveedor-> archivoCuentas = $archivoCuentaBancaria1;
    }
    if($file2!=""){
      $proveedor-> constanciaFiscal = $archivoConstanciaFiscal;
    }
    $proveedor-> save();

    $mensaje='Archivos agregados correctamente';
    $tipoMensaje="ok";
    return view ('mensajeXMLyPDF')
    ->with('mensaje',$mensaje)
    ->with('tipoMensaje',$tipoMensaje)    
    ->with('fc',$fc='');
          
  }

}
