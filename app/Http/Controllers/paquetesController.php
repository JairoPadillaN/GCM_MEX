<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\paquetes;
use App\detallepaquetes;
use App\marcaRefaccion;
use App\tiporefacciones;
use App\refaccionesreparacionpartes;
use App\partesVenta;

class paquetesController extends Controller
{
  public function altaPaquetes(){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
          Session::flash('error', 'Es necesario logearse antes de continuar');
          return redirect()->route('login');
    }
    else
    {			
      
		  return view ('altaPaquetes');
    }
  }

  public function guardarPaquetes(Request $request){
  		
       $nombrePaquete = $request -> nombrePaquete;

       $paquete = new paquetes;
       $paquete->idPaquete = $request->idPaquete;
       $paquete->nombrePaquete = $request->nombrePaquete;
       $paquete-> activo='Si';
       $paquete->save();


        $proceso='Registro de paquetes';
        $mensaje="Paquete registrado correctamente";
        return view ('mensajePaquetes')
        ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);
      

  }

  public function reportePaquetes(Request $request)
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
          
         

		    $consulta = \DB::select("SELECT idPaquete, nombrePaquete,activo FROM paquetes ORDER BY nombrePaquete ASC");
        // $pesos = $consulta[0]->idPaquete;
        
        $monto = \DB::select("SELECT idPaquete,FORMAT(SUM(precioVentaPe),2) AS pesos,FORMAT(SUM(precioVentaDol),2) AS dolares 
        , COUNT(idPaquete) AS canti
        FROM detallepaquetes 
        GROUP BY idPaquete");

          // return $monto;
        // return $pesos[0]->pesos;
        return view ('reportePaquetes')
        ->with('consulta',$consulta)
        ->with('monto',$monto);

      }
		
    }
	
	 public function modificarPaquetes($idPaquete){

           $consulta = paquetes::Where ('idPaquete','=',$idPaquete)->get();
           return view ('editarPaquetes')
           ->with ('consulta', $consulta[0]);
    }

    public function editaPaquetes(Request $request){
      $idPaquete = $request -> idPaquete;
      $nombrePaquete = $request -> nombrePaquete;

               $this->validate ($request,['nombrePaquete'=>['required']]);

              $paquete = paquetes::find($idPaquete);
              $paquete-> idPaquete = $request-> idPaquete;
              $paquete-> nombrePaquete = $request-> nombrePaquete;
              $paquete-> save();

              $proceso='Modificación de paquetes';
              $mensaje="Registro modificado correctamente";
              return view ('mensajePaquetes')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);

      
    }

	public function eliminarPaquetes ($idPaquete){/*Rerecibe este parametro y lo guarda en esa variable*/
             $paquete = \DB::UPDATE("update paquetes
             set activo ='No' where idPaquete=$idPaquete");

           $proceso ="Eliminación de paquetes";
           $mensaje="El registro ha sido eliminado correctamente";
           return view('mensajePaquetes')
              ->with('proceso',$proceso)
              ->with('mensaje',$mensaje);
    }
	
	  public function restaurarPaquetes ($idPaquete){ //restarura el valos de NO a SI en el campo activo
           $paquete= \DB::UPDATE("update paquetes
           set activo ='Si' where idPaquete=$idPaquete");

           $proceso ="Restauración de paquetes";
           $mensaje="El registro ha sido restaurado correctamente";
           return view('mensajePaquetes')
                ->with('proceso',$proceso)
                ->with('mensaje',$mensaje);
    }

    public function comboTipoRef(Request $request){
      $idMarcaRefa = $request->idMarcaRefa;
      $consulta = tiporefacciones::where('idMarcaRefa', '=', $idMarcaRefa)->get();
      return view('comboTipoRefaccion')
      ->with('consulta',$consulta);
  }
  
  public function comboNumeroSKU(Request $request){
    
    $idTipoRefacciones = $request->idTipoRefacciones;

    $consulta = \DB::select("SELECT idPartesVenta,numeroParte,nombreRefaccion,codigo,activo FROM partesVenta 
    WHERE parapaquete = 'Si' AND idTipoRefacciones = $idTipoRefacciones");
    // $consulta = partesVenta::where('idTipoRefacciones', '=', $idTipoRefacciones)->get();
    return view('comboNumeroSKU')
    ->with('consulta',$consulta);
  }
  
  public function serie(Request $request){
      
    $idPartesVenta = $request->idPartesVenta;
    $consultaSerie = \DB::select("SELECT serie FROM partesVenta WHERE idPartesVenta = $idPartesVenta");
    return view('cajitaSerie')
    ->with('consultaSerie',$consultaSerie[0]);
  }
  
  public function modelo(Request $request){
      
    $idPartesVenta = $request->idPartesVenta;
    $consultaModelo = \DB::select("SELECT modelo FROM partesVenta WHERE idPartesVenta = $idPartesVenta");
    return view('cajitaModelo')
    ->with('consultaModelo',$consultaModelo[0]);
    
  }
  
  public function codigo(Request $request){
      
    $idPartesVenta = $request->idPartesVenta;
    $consultaCodigo = \DB::select("SELECT codigo FROM partesVenta WHERE idPartesVenta = $idPartesVenta");
    return view('cajitaCodigo')
    ->with('consultaCodigo',$consultaCodigo[0]);
    
  }

  public function precioVentaPe(Request $request){
      
    $idPartesVenta = $request->idPartesVenta;
    $consultaPesos = \DB::select("SELECT precioVentaPe FROM partesVenta WHERE idPartesVenta = $idPartesVenta");
    return view('cajitaPesos')
    ->with('consultaPesos',$consultaPesos[0]);
    
  }

  public function precioVentaDol(Request $request){
      
    $idPartesVenta = $request->idPartesVenta;
    $consultaDolares = \DB::select("SELECT precioVentaDol FROM partesVenta WHERE idPartesVenta = $idPartesVenta");
    return view('cajitaDolares')
    ->with('consultaDolares',$consultaDolares[0]);
    
  }

  public function agregarPartePaquete($idPaquete){

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
      $consultaPaquetes = \DB::select("SELECT idPaquete, nombrePaquete from paquetes WHERE idPaquete = $idPaquete"); 

      $consultaPaq= \DB::select("SELECT idDetallePaquete,idPaquete FROM detallepaquetes WHERE idPaquete=$idPaquete");
      $cuantosPaq = count($consultaPaq);

      $consultaDetPaq = \DB::select("SELECT dp.idDetallePaquete,dp.idPaquete,p.nombrePaquete,pv.numeroParte,mr.marcaRefaccion,pv.nombreRefaccion,
      tr.nombreTipoRefaccion,dp.codigo,dp.serie,dp.modelo,FORMAT(dp.precioVentaDol,2) AS precioVentaDol,
      FORMAT(dp.precioVentaPe,2) AS precioVentaPe,dp.apareceCoti
      FROM detallepaquetes AS dp
      INNER JOIN paquetes AS p ON p.idPaquete = dp.idPaquete
      INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dp.idPartesVenta
      INNER JOIN marcasRefaccion AS mr ON mr.idMarcaRefa = dp.idMarcaRefa
      INNER JOIN tiporefacciones AS tr ON tr.idTipoRefacciones = dp.idTipoRefacciones WHERE dp.idPaquete = $idPaquete");

      return view('agregarPartePaquete')
      ->with('consultaPaquetes',$consultaPaquetes[0])
      ->with('consultaDetPaq',$consultaDetPaq)
      ->with('cuantosPaq',$cuantosPaq);

    }
  }

  public function altaAgregarPaq(Request $request){
      $idPaquete = $request->idPaquete;

      
      $marca = marcaRefaccion::orderby('idMarcaRefa', 'asc')->get();
      
      return view('altaAgregarPaq')
      ->with('idPaquete',$idPaquete)
      ->with('marca',$marca);
     

    }

    public function guardarPac(Request $request){
      $idPaquete = $request -> idPaquete;
      $idPartesVenta = $request -> idPartesVenta;
      $idMarcaRefa = $request -> idMarcaRefa;
      $idTipoRefacciones = $request -> idTipoRefacciones;
      $codigo = $request -> codigo;
      $serie = $request -> serie;
      $modelo = $request -> modelo;
      $precioVentaPe = $request -> precioVentaPe;
      $precioVentaDol = $request -> precioVentaDol;
      $apareceCoti = $request -> apareceCoti;

      $asignaciones=\DB::select("SELECT COUNT(*) AS cuantos
      FROM detallepaquetes
      WHERE idPaquete= ? AND idPartesVenta =? AND idTipoRefacciones=?",[$idPaquete,$idPartesVenta,$idTipoRefacciones]);
      $cuantos = $asignaciones[0]->cuantos;

      if($cuantos==0){
      $detPac = new detallepaquetes;
      $detPac-> idPaquete =$request-> idPaquete;
      $detPac-> idPartesVenta =$request-> idPartesVenta;
      $detPac-> idMarcaRefa =$request-> idMarcaRefa;
      $detPac-> idTipoRefacciones =$request-> idTipoRefacciones;
      $detPac-> serie = $request-> serie;
      $detPac-> modelo =$request-> modelo;
      $detPac-> codigo =$request-> codigo;
      $detPac-> precioVentaPe =$request-> precioVentaPe;
      $detPac-> precioVentaDol =$request-> precioVentaDol;
      $detPac-> apareceCoti =$request-> apareceCoti;
      $detPac-> save();

      $agregado = \DB::select("SELECT dp.idDetallePaquete,dp.idPaquete,p.nombrePaquete,pv.numeroParte,pv.nombreRefaccion,
      mr.marcaRefaccion,tr.nombreTipoRefaccion,dp.codigo,dp.serie,dp.modelo,FORMAT(dp.precioVentaDol,2) AS precioVentaDol,FORMAT(dp.precioVentaPe,2) AS precioVentaPe,dp.apareceCoti
      FROM detallepaquetes AS dp
      INNER JOIN paquetes AS p ON p.idPaquete = dp.idPaquete
      INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dp.idPartesVenta
      INNER JOIN marcasRefaccion AS mr ON mr.idMarcaRefa = dp.idMarcaRefa
      INNER JOIN tiporefacciones AS tr ON tr.idTipoRefacciones = dp.idTipoRefacciones WHERE dp.idPaquete = $idPaquete");
      
      return view('detallePartesPac')
      ->with('agregado',$agregado)
      ->with('cuantos',$cuantos);
      
    }else{
      $agregado = \DB::select("SELECT dp.idDetallePaquete,dp.idPaquete,p.nombrePaquete,pv.numeroParte,pv.nombreRefaccion,
      mr.marcaRefaccion,tr.nombreTipoRefaccion,dp.codigo,dp.serie,dp.modelo,FORMAT(dp.precioVentaDol,2) AS precioVentaDol,FORMAT(dp.precioVentaPe,2) AS precioVentaPe,dp.apareceCoti
      FROM detallepaquetes AS dp
      INNER JOIN paquetes AS p ON p.idPaquete = dp.idPaquete
      INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dp.idPartesVenta
      INNER JOIN marcasRefaccion AS mr ON mr.idMarcaRefa = dp.idMarcaRefa
      INNER JOIN tiporefacciones AS tr ON tr.idTipoRefacciones = dp.idTipoRefacciones WHERE dp.idPaquete = $idPaquete");
      
      return view('detallePartesPac')
      ->with('agregado',$agregado)
      ->with('cuantos',$cuantos);



    }


    }

    public function borrarPartePaq(Request $request ){  
      $sname = Session::get('sesionname');
      $sidu = Session::get('sesionidu');
      $stipo = Session::get('sesiontipo');
  
      if($sname == '' or $sidu =='' or $stipo==''){
        Session::flash('error', 'Es necesario logearse antes de continuar');
        return redirect()->route('login');
      }
      else{  
  
        $paqBorrar = $request->idDetallePaquete; 
        $paqueteBuscar = $request->idPaquete;
  
        
        // $cuantos = 0;
        
        $paq = detallepaquetes::find($paqBorrar)->delete();
        
        $agregado = \DB::select("SELECT dp.idDetallePaquete,dp.idPaquete,p.nombrePaquete,pv.numeroParte,mr.marcaRefaccion,pv.nombreRefaccion,
        tr.nombreTipoRefaccion,dp.codigo,dp.serie,dp.modelo,FORMAT(dp.precioVentaDol,2) AS precioVentaDol,FORMAT(dp.precioVentaPe,2) AS precioVentaPe,dp.apareceCoti
        FROM detallepaquetes AS dp
        INNER JOIN paquetes AS p ON p.idPaquete = dp.idPaquete
        INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dp.idPartesVenta
        INNER JOIN marcasRefaccion AS mr ON mr.idMarcaRefa = dp.idMarcaRefa
        INNER JOIN tiporefacciones AS tr ON tr.idTipoRefacciones = dp.idTipoRefacciones WHERE dp.idPaquete = $paqueteBuscar");
        
        return view('detallePartesPac')
        ->with('agregado',$agregado);
      }
    }

    public function borrarPartePaq2(Request $request ){  
      $sname = Session::get('sesionname');
      $sidu = Session::get('sesionidu');
      $stipo = Session::get('sesiontipo');
  
      if($sname == '' or $sidu =='' or $stipo==''){
        Session::flash('error', 'Es necesario logearse antes de continuar');
        return redirect()->route('login');
      }
      else{  
        $paqBorrar = $request->idDetallePaquete; 
        $paqueteBuscar = $request->idPaquete;

        $consultaPaquetes = \DB::select("SELECT idPaquete, nombrePaquete from paquetes WHERE idPaquete = $paqueteBuscar"); 

      $consultaPaq= \DB::select("SELECT idDetallePaquete,idPaquete FROM detallepaquetes WHERE idPaquete=$paqueteBuscar");
      $cuantosPaq = count($consultaPaq);

      $consultaDetPaq = \DB::select("SELECT dp.idDetallePaquete,dp.idPaquete,p.nombrePaquete,pv.numeroParte,mr.marcaRefaccion,pv.nombreRefaccion,
      tr.nombreTipoRefaccion,dp.codigo,dp.serie,dp.modelo,FORMAT(dp.precioVentaDol,2) AS precioVentaDol,
      FORMAT(dp.precioVentaPe,2) AS precioVentaPe,dp.apareceCoti
      FROM detallepaquetes AS dp
      INNER JOIN paquetes AS p ON p.idPaquete = dp.idPaquete
      INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dp.idPartesVenta
      INNER JOIN marcasRefaccion AS mr ON mr.idMarcaRefa = dp.idMarcaRefa
      INNER JOIN tiporefacciones AS tr ON tr.idTipoRefacciones = dp.idTipoRefacciones WHERE dp.idPaquete = $paqueteBuscar");
  
        
        // $cuantos = 0;
        
        $paq = detallepaquetes::find($paqBorrar)->delete();
        
        $agregado2 = \DB::select("SELECT dp.idDetallePaquete,dp.idPaquete,p.nombrePaquete,pv.numeroParte,mr.marcaRefaccion,pv.nombreRefaccion,
        tr.nombreTipoRefaccion,dp.codigo,dp.serie,dp.modelo,FORMAT(dp.precioVentaDol,2) AS precioVentaDol,
        FORMAT(dp.precioVentaPe,2) AS precioVentaPe,dp.apareceCoti
        FROM detallepaquetes AS dp
        INNER JOIN paquetes AS p ON p.idPaquete = dp.idPaquete
        INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dp.idPartesVenta
        INNER JOIN marcasRefaccion AS mr ON mr.idMarcaRefa = dp.idMarcaRefa
        INNER JOIN tiporefacciones AS tr ON tr.idTipoRefacciones = dp.idTipoRefacciones WHERE dp.idPaquete = $paqueteBuscar");
        
        return view('detallePartesPac2')
        ->with('consultaPaquetes',$consultaPaquetes[0])
        ->with('consultaDetPaq',$consultaDetPaq)
        ->with('cuantosPaq',$cuantosPaq)
        ->with('agregado2',$agregado2);
      }
    }
    
    // public function agregarPac(Request $request){
    //     // return "controlador";
    //     $paquetes = \DB::select("SELECT idPaquete, nombrePaquete,activo FROM paquetes");

    //     return view('comboPaquete')
    //     ->with('paquetes',$paquetes);
    // }

    public function detallePac(Request $request){
      
      
      $idPaquete = $request->idPaquete;
      $idParte = $request->idParte;
      $tipoProducto = $request->tipoProducto;

      $detalle = detallepaquetes::where('idPaquete','=',$idPaquete)->get();
     
     
      foreach($detalle as $d){
        refaccionesreparacionpartes::create([
                      'idParte' => $request->idParte,
                      'tipoProducto' => $request->tipoProducto,
                      'idPaquete' => $d->idPaquete,            
                      'idPartesVenta' => $d->idPartesVenta , 
                      'idTipoRefacciones' => $d->idTipoRefacciones,
                      'precioPesos' => $d->precioVentaPe,
                      'precioDolar' => $d->precioVentaDol,
                      'apareceEnCoti' => $d->apareceCoti]);
      }


      $consultaRefaccionesAsignados=\DB::select("SELECT rp.idRefaccRepParte,rp.idParte,pv.codigo,FORMAT(rp.precioDolar,2) AS precioVentaDol, pv.nombreRefaccion,
      rt.nombreTipoRefaccion,rp.tipoProducto,p.nombrePaquete,rp.apareceEnCoti FROM refaccionesreparacionpartes AS rp
      LEFT JOIN partesVenta AS pv ON pv.idPartesVenta = rp.idPartesVenta
      LEFT JOIN tiporefacciones AS rt ON rt.idTipoRefacciones = rp.idTipoRefacciones
      LEFT JOIN paquetes AS p ON p.idPaquete = rp.idPaquete
      WHERE idParte=?",[$idParte]);
      $cuantasRefaccionesYaHay = count($consultaRefaccionesAsignados);

      $consultaSumaRef = \DB::select("SELECT FORMAT(SUM(precioDolar),2) as total2 FROM refaccionesreparacionpartes
     WHERE idParte = $idParte");

      $refAsignadasExisten=\DB::select("SELECT COUNT(*) AS cuantos
      FROM refaccionesreparacionpartes
      WHERE idParte= ? AND idRefaccion = $d->idPartesVenta AND idTipoRefacciones=$d->idTipoRefacciones ",[$idParte]);
      $cuantos = $refAsignadasExisten[0]->cuantos;

        return view('reporteRefaccionesParte')
        ->with('cuantos',$cuantos)
        ->with('cuantasRefaccionesYaHay',$cuantasRefaccionesYaHay)
        ->with('consultaRefaccionesAsignados',$consultaRefaccionesAsignados)
        ->with('consultaSumaRef',$consultaSumaRef[0]);

   

    }




}