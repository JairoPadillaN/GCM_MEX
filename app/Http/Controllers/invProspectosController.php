<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\invprospectos;
use App\clientes;
use App\sucursales;
class invProspectosController extends Controller
{
  public function altaProspectos(){
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
        
      
			$consulta= invprospectos::orderby('idInvProspectos','desc')
               ->take(1)
               ->get();
      $cliente = clientes::orderby('razonSocial','asc')->get();
      
      return view ('altaProspectos')
      ->with('cliente',$cliente);
      
}
}

public function obtenerId(Request $request){
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

        return view ('obtenerId')
        ->with('idSucursal',$idSucursal)
        ->with('cliente',$cliente)
        ->with('idclientesel',$consultaClientes[0]->idc)
        ->with('nomcli',$nomcli)
        ->with('arrayCheckServicios',$arrayCheckServicios)
        ->with('arrayCheckMarcas',$arrayCheckMarcas)
        ->with('consulta',$consulta);
        
  
}

public function comboSuc(Request $request){
$idc=$request->get('idc');

// $consulta = sucursales::where('idc','=',$idc)->get();

//             return view ('com')
//             ->with('consulta',$consulta);


            $idc = $request->get('idc');
            $sucursales = sucursales::where('idc','=',$idc,'AND','activo','=','Si')->get();
    
            return view ('combop')
            ->with('sucursales',$sucursales);

}

public function guardarProspectos(Request $request)
{
        $fechaAlta=$request->fechaAlta;
        $empresaSeguimiento=$request->empresaSeguimiento;
        $idu=$request->idu;
        $registradoPor=$request->registradoPor;
        $idc=$request->idc;
        $idSucursal=$request->idSucursal;
        $descripcionActividad=$request->descripcionActividad;
        
        $this->validate ($request,[
        'fechaAlta'=>['required'],
        'empresaSeguimiento'=>['required'],
        'registradoPor'=>['required'],
        'idc'=>['required'],
        'idSucursal'=>['required']
        ]);

          $prospectos = new invprospectos;
          $prospectos->idInvProspectos = $request->idInvProspectos;
          $prospectos->fechaAlta = $request->fechaAlta;
          $prospectos->empresaSeguimiento = $request->empresaSeguimiento;
          $prospectos->idu = $request->idu;
          $prospectos->registradoPor = $request->registradoPor;
          $prospectos->idc = $request->idc;
          $prospectos->idSucursal = $request->idSucursal;
          $prospectos->descripcionActividad = $request->descripcionActividad;
          $prospectos->activo = 'Si';
          $prospectos->save();

          $proceso ="Alta de investigación de prospectos";
          $mensaje="Registro guardado correctamente";
          return view('mensajeProspectos')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
          
}

	 public function reporteProspectos(Request $request)
    {
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
          

		$consulta = \DB::select("SELECT ip.idInvProspectos,DATE_FORMAT(ip.fechaAlta,'%d %b %Y') AS fechaAlta, ip.empresaSeguimiento,s.sucursal, c.razonSocial, ip.descripcionActividad,ip.registradoPor,ip.activo
                              FROM invprospectos AS ip
                              INNER JOIN sucursales AS s ON s.idSucursal = ip.idSucursal
                              INNER JOIN clientes AS c ON c.idc = ip.idc");
        return view ('reporteProspectos')
        ->with('consulta',$consulta);
      }
		
    }

    public function reporteProspectosAbajo(Request $request){
      $fechaInicio = $request->fechaInicio;
      $fechaFin = $request->fechaFin;

      $reporte = \DB::select("SELECT ip.idInvProspectos,DATE_FORMAT(ip.fechaAlta,'%d %b %Y') AS fechaAlta, ip.empresaSeguimiento,s.sucursal, c.razonSocial, ip.descripcionActividad,ip.registradoPor,ip.activo
                              FROM invprospectos AS ip
                              INNER JOIN sucursales AS s ON s.idSucursal = ip.idSucursal
                              INNER JOIN clientes AS c ON c.idc = ip.idc
                              WHERE fechaAlta>=? AND fechaAlta<=?
                              ",[$fechaInicio,$fechaFin]);

      $cuantos = count ($reporte);
      return view('reporteProspectosAbajo')
      ->with('reporte',$reporte)
      ->with('cuantos',$cuantos)
      ->with('fechaInicio',$fechaInicio)
      ->with('fechaFin',$fechaFin);

    }
	
	 public function modificarProspectos($idInvProspectos){
        $consulta = invprospectos::Where ('idInvProspectos','=',$idInvProspectos)->get();

        $consultaClientes = invprospectos::Where ('idInvProspectos','=',$idInvProspectos)->get();
        $clienteSel= clientes::where('idc',"=",$consultaClientes[0]->idc)->get();
        $nomcli =$clienteSel[0]->razonSocial;
        $cliente = clientes::where ('idc','!=',$consultaClientes[0]->idc)->get();

        $consultaSucursal = invprospectos::Where ('idInvProspectos','=',$idInvProspectos)->get();
        $sucursalSel= sucursales::where('idSucursal',"=",$consultaSucursal[0]->idSucursal)->get();
        $idSucursal=$sucursalSel[0]->idSucursal;
        $nomSuc =$sucursalSel[0]->sucursal;
        $gcmid =$sucursalSel[0]->gcmid;
        $colonia =$sucursalSel[0]->colonia;
        $sucursal = sucursales::where ('idSucursal','!=',$consultaSucursal[0]->idSucursal)->get();

        $consultaCheckServicios = \DB::select("SELECT s.servicios FROM sucursales AS s WHERE idSucursal=$idSucursal ");
        $cadenaCheckServicios= $consultaCheckServicios[0]->servicios;
        $arrayCheckServicios=explode(', ',$cadenaCheckServicios);

        $consultaCheckMarcas = \DB::select("SELECT s.marcas FROM sucursales AS s WHERE idSucursal=$idSucursal ");
        $cadenaCheckMarcas= $consultaCheckMarcas[0]->marcas;
        $arrayCheckMarcas=explode(', ',$cadenaCheckMarcas);


        //  return $gcmid;        

          return view ('editarProspectos')
          ->with ('sucursal', $sucursal[0])
          ->with('arrayCheckServicios',$arrayCheckServicios)
          ->with('arrayCheckMarcas',$arrayCheckMarcas)
          ->with('consulta',$consulta[0])
          ->with('cliente',$cliente)
          ->with('idclientesel',$consultaClientes[0]->idc)
          ->with('nomcli',$nomcli)
          ->with('clienteSel',$clienteSel[0])
          ->with('sucursal',$sucursal)
          ->with('idSucursalSel',$consultaSucursal[0]->idSucursal)
          ->with('gcmid',$gcmid)
          ->with('colonia',$colonia)
          ->with('nomSuc',$nomSuc)
          ->with('sucursalSel',$sucursalSel[0]);
    }
	 public function editarProspectos(Request $request){
          $idInvProspectos=$request->idInvProspectos;
          $fechaAlta=$request->fechaAlta;
          $empresaSeguimiento=$request->empresaSeguimiento;
          $idu=$request->idu;
          $registradoPor=$request->registradoPor;
          $idc=$request->idc;
          $idSucursal=$request->idSucursal;
          $descripcionActividad=$request->descripcionActividad;
              
            $this->validate ($request,[
              'fechaAlta'=>['required'],
              'empresaSeguimiento'=>['required'],
              'registradoPor'=>['required'],
              'idc'=>['required'],
              'idSucursal'=>['required']
            ]);

           $prospectos = invprospectos::find($idInvProspectos);
           $prospectos->idInvProspectos = $request->idInvProspectos;
           $prospectos->fechaAlta = $request->fechaAlta;
           $prospectos->empresaSeguimiento = $request->empresaSeguimiento;
           $prospectos->idu = $request->idu;
           $prospectos->registradoPor = $request->registradoPor;
           $prospectos->idc = $request->idc;
           $prospectos->idSucursal = $request->idSucursal;
           $prospectos->descripcionActividad = $request->descripcionActividad;
           $prospectos-> save();

           $proceso='Modificación de prospectos';
           $mensaje="Prospecto modificado correctamente";
           return view ('mensajeProspectos')
             ->with('proceso',$proceso)
             ->with('mensaje',$mensaje);
    }

    public function eliminarProspectos ($idInvProspectos){/*Rerecibe este parametro y lo guarda en esa variable*/
             $prospectos = \DB::UPDATE("update invprospectos
             set activo ='No' where idInvProspectos=$idInvProspectos");

           $proceso ="Eliminación de prospectos";
           $mensaje="El prospecto ha sido desactivado correctamente";
           return view('mensajeProspectos')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);
    }
	
	  public function restaurarProspectos($idInvProspectos){ //restarura el valos de NO a SI en el campo activo
           $prospectos= \DB::UPDATE("update invprospectos
           set activo ='Si' where idInvProspectos=$idInvProspectos");

           $proceso ="Restauración de prospectos";
           $mensaje="El prospecto ha sido activado correctamente";
           return view('mensajeProspectos')
                ->with('proceso',$proceso)
                ->with('mensaje',$mensaje);
    }

    public function edicionSuc(Request $request){
      $idSucursal = $request->get('idSucursal');
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

        
        return view('altaProspectos')
        ->with('cuantosHay',$cuantosHay)
        ->with('cliente',$cliente)
        ->with('idSucursal',$idSucursal)
        ->with('idclientesel',$consultaClientes[0]->idc)
        ->with('nomcli',$nomcli)
        ->with('clienteSel',$clienteSel[0])
        ->with('arrayCheckServicios',$arrayCheckServicios)
        ->with('arrayCheckMarcas',$arrayCheckMarcas)
        ->with('consulta',$consulta[0]);
      
     
        //  return $idSucursal;
      
    }
    public function editarSucursalProspecto(Request $request){
      $idSucursal=$request-> idSucursal;

      $arrayServicios = $request-> servicios;
      $cadenaServicios = implode(', ',$arrayServicios);
      
      $arrayMarcas = $request-> marcas;
      $cadenaMarcas = implode(', ',$arrayMarcas);


      $suc = sucursales::find($idSucursal);
      $suc->idc = $request-> idc;
      $suc->idSucursal = $request-> idSucursal;
      $suc->sucursal=$request->sucursal;
      $suc->gcmid=$request->gcmid;
      $suc->calle=$request->calle;
      $suc->colonia=$request->colonia;
      $suc->num=$request->num;
      $suc->tipoSucursal=$request->tipoSucursal;
      $suc->giro=$request->giro;
      $suc->servicios=$cadenaServicios;
      $suc->marcas=$cadenaMarcas;      

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
      
      return view ('obtenerId');
      // return "hola";
      
      

  }


    

}
