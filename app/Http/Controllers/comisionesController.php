<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\rangoComisiones;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use App\Exports\OCFacturasExport;
use App\viajes;
use App\vehiculos;
use App\clientes;
use App\usuarios;
use App\facturas;
use App\sucursales;
use App\bancos;
use App\Comision;
use App\dataModel;
use App\cotizaciones;
use App\asignacionesdetalles;
use App\cotizacionServicios;
use App\ordenCompra;
use App\datosPagoServicios;
use App\Exports\UtilidadServicioExport;
use App\Exports\GastoServicioExport;
use DB;
use App\anexGrid;
use App\gastos;
use App\archivosFacturas;

class comisionesController extends Controller
{
    public function indexrango(){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
        
        

        if($sname == '' or $sidu =='' or $stipo=='')
        {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        if($stipo == 'Administrador')
        {
            $comisiones = rangoComisiones::orderBy('rangoInicial')->get()->all();

            return view('comisionesRango')->with('comisiones', $comisiones);

        }else{
            Session::flash('error', 'Usuario no autorizado');
            return redirect()->route('login');
        }
    }

    public function storerango(Request $request){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
        $rangook = rangoComisiones::where('rangoFinal','<=',$request->rangoFinal)->where('rangoInicial','>=',$request->rangoInicial)->where('vigente',1)->count();

        if($stipo == 'Administrador')
        {
            if ($request->rangoInicial < $request->rangoFinal){
            $comision = new rangoComisiones($request->all());
            
            $comision->porcentajeComision = $request->porcentajeComision;
            $comision->vigente = $request->vigente;
            if($rangook==0){
                $comision->rangoInicial = $request->rangoInicial;
                $comision->rangoFinal = $request->rangoFinal;
                $comision->save();
                return redirect()->route('indexrango');
            }
            $comision->save();
            Session::flash('error', 'Hay rangos interfiriendo con el nuevo rango');
            return redirect()->route('indexrango')->with('alert','rangono');
        }else{
            Session::flash('error', 'El rango inicial no puede ser menor que el final');
            return redirect()->route('createrango')->with('alert','rangono');
        }
            
        }
        else
        {
            Session::flash('error', 'Usuario no autorizado');
            return redirect()->route('login');

        }

    }

    public function getDataFacturas(Request $request) {
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

        $aCargo = \DB::select("SELECT iduAsignado FROM usuariosacargos WHERE idu =$sidu");
          if (count($aCargo) > 0) {
            for ($i=0; $i < count($aCargo) ; $i++) {
              $usuariosReporte[$i] = $aCargo[$i]->iduAsignado;
            }
            array_push($usuariosReporte,$sidu);
          }else{
            $usuariosReporte[0]=$sidu;
          }

        $anexGrid = new anexGrid();
        $idServicios = '';
        $estatusEntrega = '';
        $estatusPortal= '';
        $estatusPago = '';
        $sumusuarios = '';
        $cashFlow = '';
        $cliente = '';
        $sucursal = '';
        $numeroFactura = '';
        $numeroCotizacion = '';
        $numeroServicio = '';


        foreach($anexGrid->filtros as $f)
        {
          if($f['columna'] == 'idServicios' && $f['valor'] != ''){
            $idServicios = $f['valor'];
          }
          if($f['columna'] == 'numeroFactura' && $f['valor'] != ''){
            $numeroFactura = $f['valor'];
          }
          if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
            $cliente = $f['valor'];
          }
          if($f['columna'] == 'razonSocial' && $f['valor'] == ''){
            $cliente = '';
          }

          if($f['columna'] == 'sucursales.sucursal' && $f['valor'] != ''){
            $sucursal = $f['valor'];
          }
          if($f['columna'] == 'sucursales.sucursal' && $f['valor'] == ''){
            $sucursal = '';
          }             

          if($f['columna'] == 'estatusEntrega'&& $f['valor'] != ''){
            $estatusEntrega = $f['valor'];
          } 
          if($f['columna'] == 'estatusEntrega' && $f['valor'] == '') {
            $estatusEntrega = '';
          }
          
          if($f['columna'] == 'estatusPortal'&& $f['valor'] != ''){
            $estatusPortal = $f['valor'];
          }
          if ($f['columna'] == 'estatusPortal' && $f['valor'] == '') {
            $estatusPortal = '';
          }
          
          if($f['columna'] == 'estatusPago'&& $f['valor'] != ''){
            $estatusPago = $f['valor'];
          }
          if ($f['columna'] == 'estatusPago' && $f['valor'] == '') {
            $estatusPago = '';
          }
          if ($f['columna'] == 'usuarios' && $f['valor'] == '') {
            $usuarios = '';
          }
          if ($f['columna'] == 'usuarios' && $f['valor'] == '') {
            $usuarios = '';
          }

          if($f['columna'] == 'cashFlow'&& $f['valor'] != ''){
            $cashFlow = $f['valor'];
          }
          if ($f['columna'] == 'cashFlow' && $f['valor'] == '') {
            $cashFlow = '';
          }
          if ($f['columna'] == 'numeroServicio' && $f['valor'] == '') {
            $numeroServicio = '';
          }
          if($f['columna'] == 'numeroServicio'&& $f['valor'] != ''){
            $numeroServicio = $f['valor'];
          }
          
          if($f['columna'] == 'numeroCotizacion' && $f['valor'] == ''){
            $numeroCotizacion = '';
          }
          if($f['columna'] == 'numeroCotizacion' && $f['valor'] != ''){
            $numeroCotizacion = $f['valor'];
          }
        }

        $order;
        $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';
          
          if ($stipo == 'Administrador' || $stipo == 'Compras') { 

            $registros = DB::table('facturas')            
              ->select('idServicios','numeroFactura','facturas.idc','facturas.idSucursal',
              // DB::raw('cotizaciones.numeroCotizacion AS numeroCoti'),
              // DB::raw('IF(cotizacionServicios.numeroCotizacion is null,"-",cotizacionServicios.numeroCotizacion) AS numeroSer'),
              DB::raw('IF(facturas.tipoServicio="Soporte Técnico",cotizacionServicios.numeroCotizacion,"-") AS numeroSer'),
              DB::raw('IF(facturas.tipoServicio!="Soporte Técnico",cotizaciones.numeroCotizacion,"-") AS numeroCoti'),
              DB::raw('DATE_FORMAT(fechaFactura,"%d %b %Y") AS fecha'),
              DB::raw('DATE_FORMAT(fechaPago,"%d %b %Y") AS fechap'),
              DB::raw('COUNT(asignacionesdetalles.`idu`) AS canti'),
              DB::raw('COUNT(Comisiones.`idUsuario`) AS sumusuario'),
//                DB::raw('SELECT COUNT(idUsuario) as usuario, C.idFactura
// FROM Comisiones C , facturas F  WHERE C.idFactura = F.idFactura GROUP BY C.idFactura'),
              'clientes.razonSocial','sucursales.sucursal','estatusEntrega','estatusPortal','estatusPago','cashFlow','tipoServicio',
              DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(montoFactura,2)) AS monto'),'archivo','archivoFactura','archivoPago',
              DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(totalConIva,2)) AS total'),
              'archivo','archivoFactura','archivoPago','xmlFactura','archivoRemision','archivoAdenda','facturas.idFactura','facturas.activo','facturas.servicioActivo','cotizaciones.archivoCotizacion','cotizacionServicios.archivoCotizacion AS archivoCotizacionServicios','cotizaciones.idCotizacion','cotizacionServicios.idCotizacionServicios')
              ->leftJoin('clientes', 'facturas.idc', '=', 'clientes.idc') 
               ->leftJoin('Comisiones', 'facturas.idFactura', '=', 'Comisiones.idFactura') 
              ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
              ->leftJoin('asignacionesdetalles', 'facturas.idFactura', '=', 'asignacionesdetalles.idFactura')
              ->leftJoin('cotizaciones', 'facturas.idCotizacion', '=', 'cotizaciones.idCotizacion')
              ->leftJoin('cotizacionServicios', 'facturas.idCotizacion', '=', 'cotizacionServicios.idCotizacionServicios')
              ->where('idServicios', '>', 0)
              ->where('idServicios', 'like', '%'.$idServicios.'%')
              ->where('numeroFactura', 'like', '%'.$numeroFactura.'%')
              ->where('estatusPago', 'like', '%'.$estatusPago.'%')
              ->where('estatusPortal', 'like', '%'.$estatusPortal.'%')
              ->where('estatusEntrega', 'like', '%'.$estatusEntrega.'%')
              ->where('cashFlow', 'like', '%'.$cashFlow.'%')
              ->where('razonSocial', 'like', '%'.$cliente.'%')
              ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')

              // ->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
              // ->orwhere('cotizacionServicios.numeroCotizacion', 'like', '%'.$numeroServicio.'%')
              // ->orwherenull('cotizaciones.numeroCotizacion','cotizacionServicios.numeroCotizacion')                    


              ->where(function($query) {
                $anexGrid = new anexGrid();
                $numeroCotizacion='';
                foreach($anexGrid->filtros as $f)
                  {
                    if($f['columna'] == 'numeroCotizacion' && $f['valor'] != ''){
                      $numeroCotizacion = $f['valor'];
                    }
                  }
                $query->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
                ->orwherenull('cotizaciones.numeroCotizacion');
              })

              ->where(function($query) {
                $anexGrid = new anexGrid();
                $numeroServicio='';
                foreach($anexGrid->filtros as $f)
                  {
                    if($f['columna'] == 'numeroServicio' && $f['valor'] != ''){
                      $numeroServicio = $f['valor'];
                    }
                  }
                $query->where('cotizacionServicios.numeroCotizacion', 'like', '%'.$numeroServicio.'%')
                ->orwherenull('cotizacionServicios.numeroCotizacion');
              })



              ->groupBy('facturas.idFactura')
              ->orderBy($anexGrid->columna, $order)
              ->skip($anexGrid->pagina)
              ->take($anexGrid->limite)
            ->get();

            $total = DB::table('facturas')            
            ->select('idServicios','numeroFactura','facturas.idc','facturas.idSucursal',
            // DB::raw('cotizaciones.numeroCotizacion AS numeroCoti'),
            // DB::raw('IF(cotizacionServicios.numeroCotizacion is null,"-",cotizacionServicios.numeroCotizacion) AS numeroSer'),
            DB::raw('IF(facturas.tipoServicio="Soporte Técnico",cotizacionServicios.numeroCotizacion,"-") AS numeroSer'),
            DB::raw('IF(facturas.tipoServicio!="Soporte Técnico",cotizaciones.numeroCotizacion,"-") AS numeroCoti'),
            DB::raw('DATE_FORMAT(fechaFactura,"%d %b %Y") AS fecha'),
            DB::raw('DATE_FORMAT(fechaPago,"%d %b %Y") AS fechap'),
            DB::raw('COUNT(asignacionesdetalles.`idu`) AS canti'),
            DB::raw('COUNT(Comisiones.`idUsuario`) AS sumusuario'),
            'clientes.razonSocial','sucursales.sucursal','estatusEntrega','estatusPortal','estatusPago','cashFlow','tipoServicio',
            DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(montoFactura,2)) AS monto'),'archivo','archivoFactura','archivoPago',
            DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(totalConIva,2)) AS total'),
            'archivo','archivoFactura','archivoPago','xmlFactura','archivoRemision','archivoAdenda','facturas.idFactura','facturas.activo','facturas.servicioActivo','cotizaciones.archivoCotizacion','cotizacionServicios.archivoCotizacion AS archivoCotizacionServicios','cotizaciones.idCotizacion','cotizacionServicios.idCotizacionServicios')
            ->leftJoin('clientes', 'facturas.idc', '=', 'clientes.idc') 
            ->leftJoin('Comisiones', 'facturas.idFactura', '=', 'Comisiones.idFactura')  
            ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
            ->leftJoin('asignacionesdetalles', 'facturas.idFactura', '=', 'asignacionesdetalles.idFactura')
            ->leftJoin('cotizaciones', 'facturas.idCotizacion', '=', 'cotizaciones.idCotizacion')
            ->leftJoin('cotizacionServicios', 'facturas.idCotizacion', '=', 'cotizacionServicios.idCotizacionServicios')
            ->where('idServicios', '>', 0)
            ->where('idServicios', 'like', '%'.$idServicios.'%')
            ->where('numeroFactura', 'like', '%'.$numeroFactura.'%')
            ->where('estatusPago', 'like', '%'.$estatusPago.'%')
            ->where('estatusPortal', 'like', '%'.$estatusPortal.'%')
            ->where('estatusEntrega', 'like', '%'.$estatusEntrega.'%')
            ->where('cashFlow', 'like', '%'.$cashFlow.'%')
            ->where('razonSocial', 'like', '%'.$cliente.'%')
            ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
            ->where(function($query) {
              $anexGrid = new anexGrid();
              $numeroCotizacion='';
              foreach($anexGrid->filtros as $f)
                {
                  if($f['columna'] == 'numeroCotizacion' && $f['valor'] != ''){
                    $numeroCotizacion = $f['valor'];
                  }
                }
              $query->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
              ->orwherenull('cotizaciones.numeroCotizacion');
            })

            ->where(function($query) {
              $anexGrid = new anexGrid();
              $numeroServicio='';
              foreach($anexGrid->filtros as $f)
                {
                  if($f['columna'] == 'numeroServicio' && $f['valor'] != ''){
                    $numeroServicio = $f['valor'];
                  }
                }
              $query->where('cotizacionServicios.numeroCotizacion', 'like', '%'.$numeroServicio.'%')
              ->orwherenull('cotizacionServicios.numeroCotizacion');
            })
            
            ->count();

          }else{                            
              
            $registros = DB::table('facturas')            
            ->select('idServicios','numeroFactura','facturas.idc','facturas.idSucursal',
            // DB::raw('cotizaciones.numeroCotizacion AS numeroCoti'),
            // DB::raw('IF(cotizacionServicios.numeroCotizacion is null,"-",cotizacionServicios.numeroCotizacion) AS numeroSer'),
            DB::raw('IF(facturas.tipoServicio="Soporte Técnico",cotizacionServicios.numeroCotizacion,"-") AS numeroSer'),
            DB::raw('IF(facturas.tipoServicio!="Soporte Técnico",cotizaciones.numeroCotizacion,"-") AS numeroCoti'),
            DB::raw('DATE_FORMAT(fechaFactura,"%d %b %Y") AS fecha'),
            DB::raw('DATE_FORMAT(fechaPago,"%d %b %Y") AS fechap'),
            DB::raw('COUNT(asignacionesdetalles.`idu`) AS canti'),
            DB::raw('COUNT(Comisiones.`idUsuario`) AS sumusuario'),
            'clientes.razonSocial','sucursales.sucursal','estatusEntrega','estatusPortal','estatusPago','cashFlow','tipoServicio',
            DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(montoFactura,2)) AS monto'),'archivo','archivoFactura','archivoPago',
            DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(totalConIva,2)) AS total'),
            'archivo','archivoFactura','archivoPago','xmlFactura','archivoRemision','archivoAdenda','facturas.idFactura','facturas.activo','facturas.servicioActivo','cotizaciones.archivoCotizacion','cotizacionServicios.archivoCotizacion AS archivoCotizacionServicios','cotizaciones.idCotizacion','cotizacionServicios.idCotizacionServicios')
            ->leftJoin('clientes', 'facturas.idc', '=', 'clientes.idc') 
            ->leftJoin('Comisiones', 'facturas.idFactura', '=', 'Comisiones.idFactura')  
            ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
            ->leftJoin('asignacionesdetalles', 'facturas.idFactura', '=', 'asignacionesdetalles.idFactura')
            ->leftJoin('cotizaciones', 'facturas.idCotizacion', '=', 'cotizaciones.idCotizacion')
            ->leftJoin('cotizacionServicios', 'facturas.idCotizacion', '=', 'cotizacionServicios.idCotizacionServicios')
            ->where('idServicios', '>', 0)
            ->where('idServicios', 'like', '%'.$idServicios.'%')
            ->where('numeroFactura', 'like', '%'.$numeroFactura.'%')
            ->where('estatusPago', 'like', '%'.$estatusPago.'%')
            ->where('estatusPortal', 'like', '%'.$estatusPortal.'%')
            ->where('estatusEntrega', 'like', '%'.$estatusEntrega.'%')
            ->where('cashFlow', 'like', '%'.$cashFlow.'%')
            ->where('razonSocial', 'like', '%'.$cliente.'%')
            ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')  
            ->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            ->whereIn('cotizaciones.idu',$usuariosReporte)   /////////AGREGADO EL 28-12-21////////////
            ->orwhereIn('cotizacionServicios.idu',$usuariosReporte)   /////////AGREGADO EL 14-01-22////////////

            ->where(function($query) {
              $anexGrid = new anexGrid();
              $numeroCotizacion='';
              foreach($anexGrid->filtros as $f)
                {
                  if($f['columna'] == 'numeroCotizacion' && $f['valor'] != ''){
                    $numeroCotizacion = $f['valor'];
                  }
                }
              $query->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
              ->orwherenull('cotizaciones.numeroCotizacion');
            })

            ->where(function($query) {
              $anexGrid = new anexGrid();
              $numeroServicio='';
              foreach($anexGrid->filtros as $f)
                {
                  if($f['columna'] == 'numeroServicio' && $f['valor'] != ''){
                    $numeroServicio = $f['valor'];
                  }
                }
              $query->where('cotizacionServicios.numeroCotizacion', 'like', '%'.$numeroServicio.'%')
              ->orwherenull('cotizacionServicios.numeroCotizacion');
            })
            
      /////////COMENTADO EL 28-12-21 DE AQUI////////////
          // ->where('asignacionesdetalles.idu', '=', $sidu)                 
              // ->whereIn('asignacionesdetalles.idu',  function($query){
              //   $sidu = Session::get('sesionidu');
              //   $query->select('iduAsignado')
              //   ->from('usuariosacargos as uc')
              //   ->where('uc.idu','=',$sidu)
              //   ->orwhere('asignacionesdetalles.idu', '=', $sidu)  ;
              // })
      /////////COMENTADO EL 28-12-21 HASTA AQUI :)////////////
                            
              // ->whereIn('facturas.idFactura',  function($query){
              // $sidu = Session::get('sesionidu');
              //  $query->select('facturas.idFactura')
            //   ->from('asignacionesdetalles')
            //   ->where('idu','=',$sidu);
            // })
              ->groupBy('facturas.idFactura')
              ->orderBy($anexGrid->columna, $order)
              ->skip($anexGrid->pagina)
              ->take($anexGrid->limite)
            ->get();
                
            $total = DB::table('facturas')            
            ->select('idServicios','numeroFactura','facturas.idc','facturas.idSucursal',
            // DB::raw('cotizaciones.numeroCotizacion AS numeroCoti'),
            // DB::raw('IF(cotizacionServicios.numeroCotizacion is null,"-",cotizacionServicios.numeroCotizacion) AS numeroSer'),
            DB::raw('IF(facturas.tipoServicio="Soporte Técnico",cotizacionServicios.numeroCotizacion,"-") AS numeroSer'),
            DB::raw('IF(facturas.tipoServicio!="Soporte Técnico",cotizaciones.numeroCotizacion,"-") AS numeroCoti'),
            DB::raw('DATE_FORMAT(fechaFactura,"%d %b %Y") AS fecha'),
            DB::raw('DATE_FORMAT(fechaPago,"%d %b %Y") AS fechap'),
            DB::raw('COUNT(asignacionesdetalles.`idu`) AS canti'),
            DB::raw('COUNT(Comisiones.`idUsuario`) AS sumusuario'),
            'clientes.razonSocial','sucursales.sucursal','estatusEntrega','estatusPortal','estatusPago','cashFlow','tipoServicio',
            DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(montoFactura,2)) AS monto'),'archivo','archivoFactura','archivoPago',
            DB::raw('CONCAT(facturas.tipoMoneda,"$",FORMAT(totalConIva,2)) AS total'),
            'archivo','archivoFactura','archivoPago','xmlFactura','archivoRemision','archivoAdenda','facturas.idFactura','facturas.activo','facturas.servicioActivo','cotizaciones.archivoCotizacion','cotizacionServicios.archivoCotizacion AS archivoCotizacionServicios','cotizaciones.idCotizacion','cotizacionServicios.idCotizacionServicios')
            ->leftJoin('clientes', 'facturas.idc', '=', 'clientes.idc')  
            ->leftJoin('Comisiones', 'facturas.idFactura', '=', 'Comisiones.idFactura')  
            ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
            ->leftJoin('asignacionesdetalles', 'facturas.idFactura', '=', 'asignacionesdetalles.idFactura')
            ->leftJoin('cotizaciones', 'facturas.idCotizacion', '=', 'cotizaciones.idCotizacion')
            ->leftJoin('cotizacionServicios', 'facturas.idCotizacion', '=', 'cotizacionServicios.idCotizacionServicios')
            ->where('idServicios', '>', 0)
            ->where('idServicios', 'like', '%'.$idServicios.'%')
            ->where('numeroFactura', 'like', '%'.$numeroFactura.'%')
            ->where('estatusPago', 'like', '%'.$estatusPago.'%')
            ->where('estatusPortal', 'like', '%'.$estatusPortal.'%')
            ->where('estatusEntrega', 'like', '%'.$estatusEntrega.'%')
            ->where('cashFlow', 'like', '%'.$cashFlow.'%')
            ->where('razonSocial', 'like', '%'.$cliente.'%')
            ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')                 
            ->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
            ->whereIn('cotizaciones.idu',$usuariosReporte) /////////AGREGADO EL 28-12-21////////////
            ->orwhereIn('cotizacionServicios.idu',$usuariosReporte)   /////////AGREGADO EL 14-01-22////////////
            
            ->where(function($query) {
              $anexGrid = new anexGrid();
              $numeroCotizacion='';
              foreach($anexGrid->filtros as $f)
                {
                  if($f['columna'] == 'numeroCotizacion' && $f['valor'] != ''){
                    $numeroCotizacion = $f['valor'];
                  }
                }
              $query->where('cotizaciones.numeroCotizacion', 'like', '%'.$numeroCotizacion.'%')
              ->orwherenull('cotizaciones.numeroCotizacion');
            })

            ->where(function($query) {
              $anexGrid = new anexGrid();
              $numeroServicio='';
              foreach($anexGrid->filtros as $f)
                {
                  if($f['columna'] == 'numeroServicio' && $f['valor'] != ''){
                    $numeroServicio = $f['valor'];
                  }
                }
              $query->where('cotizacionServicios.numeroCotizacion', 'like', '%'.$numeroServicio.'%')
              ->orwherenull('cotizacionServicios.numeroCotizacion');
            })

        /////////COMENTADO EL 28-12-21 DE AQUI////////////
              // ->whereIn('facturas.idFactura',  function($query){
              //   $sidu = Session::get('sesionidu');
              //   $query->select('idFactura')
              //   ->from('asignacionesdetalles')
              //   ->where('idu','=',$sidu);
              // })

        /////////COMENTADO EL 28-12-21 HASTA AQUI ////////////
            ->count();

          }
          
          $data = array(
            'total' => $total,
            'data' => $registros
          );

          return response()->json($data, 200);
      }
    }

    public function crearComision(Request $request){
      $sname = Session::get('sesionname');
      $sidu = Session::get('sesionidu');
      $stipo = Session::get('sesiontipo');
      $duplicado = \DB::select("SELECT idUsuario FROM Comisiones WHERE idUsuario = $request->idUsuario AND `idFactura`= $request->idFactura");
      

      if($stipo == 'Administrador')
      {

        if (count($duplicado) == 0){
          $comision = new Comision($request->all());
          $comision->idFactura = $request->idFactura;
          $comision->porcentaje = $request->porcentaje;
          $comision->idUsuario = $request->idUsuario;
          $comision->save();
          return back();
        }else{
          Session::flash('error', 'El empleado ya ha sido registrado en el servicio');
          return back();
        }
      }
      else
      {
          Session::flash('error', 'Usuario no autorizado');
          return redirect()->route('login');

      }

  }

    public function editrango(Request $request){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
        $rangook = rangoComisiones::where('rangoFinal','<=',$request->rangoFinal)->where('rangoInicial','>=',$request->rangoInicial)->where('vigente',1)->count();

        if($stipo == 'Administrador')
        {
            if ($request->rangoInicial < $request->rangoFinal){
            $comision = rangoComisiones::where('id',$request->id)->first();
            
            $comision->porcentajeComision = $request->porcentajeComision;
            $comision->vigente = $request->vigente;
            if($rangook==0){
                $comision->rangoInicial = $request->rangoInicial;
                $comision->rangoFinal = $request->rangoFinal;
                $comision->update();
                return redirect()->route('indexrango');
            }
            $comision->update();
            Session::flash('error', 'Hay rangos interfiriendo con el nuevo rango');
            return redirect()->route('indexrango')->with('alert','rangono');
        }else{
            Session::flash('error', 'El rango inicial no puede ser menor que el final');
            return redirect()->route('updaterango')->with('alert','rangono');
        }
            
        }
        else
        {
            Session::flash('error', 'Usuario no autorizado');
            return redirect()->route('login');

        }

    }

    public function createrango(Request $request){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

        if($stipo == 'Administrador')
        {
            
            return view('crearRangoComision');
            
        }
        else
        {
            Session::flash('error', 'Usuario no autorizado');
            return redirect()->route('login');

        }

    }

    public function updaterango($idrango){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

        if($stipo == 'Administrador')
        {
            $rangocomision = rangoComisiones::where('id',$idrango)->first(); 
            return view('updateRangoComision')->with('rangocomision', $rangocomision);
            
        }
        else
        {
            Session::flash('error', 'Usuario no autorizado');
            return redirect()->route('login');

        }

    }

    public function deleterango(Request $request){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

        if($stipo == 'Administrador')
        {
            $comision = rangoComisiones::where('id',$request->id)->first();
            $comision->vigente = 0;
            $comision->update();
            return redirect()->route('indexrango');
        }
        else
        {
            Session::flash('error', 'Usuario no autorizado');
            return redirect()->route('login');

        }

    }

    public function deletecomision(Request $request){
      $sname = Session::get('sesionname');
      $sidu = Session::get('sesionidu');
      $stipo = Session::get('sesiontipo');

      if($stipo == 'Administrador')
      {
          $comision = Comision::where('idc',$request->id)->first();
          $comision->delete();
          return back();
      }
      else
      {
          Session::flash('error', 'Usuario no autorizado');
          return redirect()->route('login');

      }

  }

    public function modificarComisiones($idFactura){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        $gastos = DB::table('gastos')
          ->orderBy('fecha_pago', 'asc')
          ->where('id_factura', $idFactura)
          ->get();
        $contadorGastos = 0;
        foreach($gastos as $g){
          $contadorGastos += 1;
        }
        if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else{
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
          INNER JOIN facturas AS f ON f.idFactura=ea.idFactura
          WHERE f.idFactura=$idFactura ORDER BY idSegActividad DESC");
    

          $consulta = facturas::Where('idFactura','=',$idFactura)->get();
          $consultaArchivosFac = archivosFacturas::where('idFactura', '=', $idFactura)->get();
          $cuantosAF = count($consultaArchivosFac);
          
          /////ORDEN DE COMPRA////

          $ordenCompra=\DB::select(" SELECT idOrden,idFactura,codigoOrden, DATE_FORMAT(fechaOrden,'%d %b %Y') as fechaOrden,
          p.razonSocialProv,nombreEmpresa, sucursal, nombreCuenta, numeroCu, moneda, FORMAT(importeOrden,2) as importeOrden,
          FORMAT(ivaCompra,2) as ivaCompra, FORMAT(isrCompra,2) as isrCompra, FORMAT(total,2) as total,FORMAT(totalMXN,2) as totalMXN, estatus, oc.activo,
          oc.xmlFacturaProveedor, oc.pdfFacturaProveedor, DATE_FORMAT(fechaProgPago, '%d %b %Y') as fechaProgPago
          FROM ordenCompra AS oc
          INNER JOIN proveedores AS p ON p.idProveedor=oc.idProveedor
          INNER JOIN sucursales AS s ON s.idSucursal=oc.idSucursal
          INNER JOIN cuentas AS c ON c.idCuenta=oc.idCuenta
          WHERE idFactura=$idFactura");
          // dd($ordenCompra);
          $cuantasCompras=count($ordenCompra);

          $sumaTotal=\DB::select(" SELECT FORMAT(SUM(totalMXN),2) AS sumaTotal, 
          FORMAT(SUM(importeOrden),2) AS sumaImporte,
          FORMAT(SUM(ivaCompra),2) AS sumaIva,
          FORMAT(SUM(isrCompra),2) AS sumaIsr
          FROM ordenCompra
          WHERE idFactura=$idFactura");


          $consultaOrden = \DB::select("SELECT pv.idPartesVenta, co.idCotizacion, descripcion, codigo, nombreRefaccion, f.nombreEmpresa
          FROM cotizaciones AS co
          INNER JOIN facturas AS f ON f.idCotizacion=co.idCotizacion
          INNER JOIN detallereparaciones AS dr ON dr.idCotizacion=co.idCotizacion
          INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idEquipos
          WHERE f.idFactura=$idFactura
          ORDER BY nombreRefaccion ASC");

          if (count($ordenCompra) >= 1) {
            $consultaProductosOrden = \DB::select("SELECT idOrden FROM productosOrdenCompra WHERE idOrden=?",[$ordenCompra[0]->idOrden]);
            $cuantosProductosEnOrden=count($consultaProductosOrden);
          }else {
            $cuantosProductosEnOrden=0;
          }
          
          
          $cuantasOrden=count($ordenCompra);
    
          $nombreEmpresa=$consulta[0]->nombreEmpresa;

          $consultaCuentas=\DB::select("SELECT idCuenta, nombreCuenta FROM cuentas WHERE empresaCuenta='$nombreEmpresa' AND activo='Si'");

          $consultaProveedor=\DB::select("SELECT idProveedor, razonSocialProv FROM proveedores ORDER BY razonSocialProv ASC");
    
            /////fin de orden compra//////

          //////////orden de compra excel//////////////

          $query= DB::table('ordenCompra as oc')
          ->select('oc.idOrden','oc.idFactura','f.idServicios', 'importeMXN','codigoOrden','oc.nombreEmpresa','estatus','oc.activo',
          'p.razonSocialProv', 'sucursal', 'numeroCu', 'ivaMXN', 'isrMXN', 'totalMXN',
          DB::raw('CONCAT(numeroCu, " - ", nombreCuenta) AS cuenta'),
          DB::raw('CONCAT(moneda, " $", FORMAT(importeOrden,2)) AS importeOrden'),
          DB::raw('CONCAT(moneda, " $", FORMAT(total,2)) AS total'),
          DB::raw('CONCAT(" $", FORMAT(ivaCompra,2)) AS ivaCom'),
          DB::raw('CONCAT(" $", FORMAT(isrCompra,2)) AS isrCom'),
          //DB::raw('IF (moneda="MXN", CONCAT(moneda, " $", FORMAT(importeOrden,2)), CONCAT("MXN", " $", FORMAT(importeOrden*cambioDolar,2))) AS importeOrden'),
          /*DB::raw('IF (moneda="MXN", CONCAT(moneda, " $", FORMAT(ivaCompra,2)), CONCAT("MXN", " $", FORMAT(ivaCompra*cambioDolar,2))) AS ivaCom'),
          DB::raw('IF (moneda="MXN", CONCAT(moneda, " $", FORMAT(isrCompra,2)), CONCAT("MXN", " $", FORMAT(isrCompra*cambioDolar,2))) AS isrCom'),
          DB::raw('IF (moneda="MXN", CONCAT(moneda, " $", FORMAT(total,2)), CONCAT("MXN", " $", FORMAT(total*cambioDolar,2))) AS total'),
          */
          DB::raw('DATE_FORMAT(fechaOrden,"%d %b %Y") AS fechaOrden')
          )
          ->leftJoin('proveedores as p', 'oc.idProveedor', '=', 'p.idProveedor')  
          ->leftJoin('sucursales as s', 'oc.idSucursal', '=', 's.idSucursal')  
          ->leftJoin('cuentas as c', 'oc.idCuenta', '=', 'c.idCuenta')  
          ->leftJoin('facturas as f', 'oc.idFactura', '=', 'f.idFactura')  
          ->where('oc.activo','=','Si')
          ->where('oc.idFactura','=',$idFactura)
          ->orderBy('oc.idOrden', 'DESC')
          ->get();
          //dd($query);

          $consultaOC = $this->json($query);
          
          
          
          //////////fin de compra excel//////////////

          $consultaDatosPago = datosPagoServicios::where('idFactura', '=', $idFactura)->get();
          $cuantosDP = count($consultaDatosPago);

          $totalDP=\DB::select("SELECT idFactura,
          FORMAT(SUM(montoPesosDP),2) as total
          FROM datosPagoServicios 
          WHERE idFactura = $idFactura");


          $cuantosSeguimiento=count($seguimiento);
          
          $consulta = facturas::Where('idFactura','=',$idFactura)->get();

          $sucursalSel= sucursales::where('idSucursal',"=",$consulta[0]->idSucursal)->get();
          $nomsuc =$sucursalSel[0]->sucursal;
          $sucursal = sucursales::where ('idSucursal','!=',$consulta[0]->idSucursal)->get();
      
          $clienteSel= clientes::where('idc',"=",$consulta[0]->idc)->get();
          $nomcli =$clienteSel[0]->razonSocial;
          $cliente = clientes::where ('idc','!=',$consulta[0]->idc)
          ->orderBy('razonSocial', 'asc')
          ->get();

          $bancoSel= bancos::where('idb',"=",$consulta[0]->idb)->get();
          $cuantosBancos = count($bancoSel);

          if($cuantosBancos != 0){
            $nomban = $bancoSel[0]->nombre;
            $idBan = $bancoSel[0]->idb;
            $consultaBancos = \DB::select("SELECT idb, nombre FROM bancos");
          }else{
            $nomban = 0;
            $idBan = 0;
            $consultaBancos = \DB::select("SELECT idb, nombre FROM bancos");
          }
          $bancos = bancos::where ('idb','!=',$consulta[0]->idb)->get();
        

       
        $cotizacionSel= cotizaciones::where('idCotizacion',"=",$consulta[0]->idCotizacion)->get();
        $empleados = \DB::select("SELECT idu, nombreUsuario  FROM usuarios");
        $comisionesAll = \DB::select("SELECT * FROM Comisiones AS com
        INNER JOIN usuarios AS usu ON usu.idu = com.idUsuario WHERE idFactura=$idFactura");
        // $datac = array();
        // foreach ($comisionesAll as $comision){
        //   $rangocomision = \DB::select("SELECT porcentajeComision FROM rangocomisiones WHERE vigente = 1 AND rangoInicial <= $comision->monto AND rangoFinal >= $comision->monto ");
        //   $comision->rangocomision = $rangocomision;
        //   array_push($datac, $comision);
        // }
        
        $cuantos = count($cotizacionSel);
        if($cuantos != 0){

          $nomCot = $cotizacionSel[0]->numeroCotizacion;
          $idCot = $cotizacionSel[0]->idCotizacion;
          // $idCotizacionServ = $cotizacionSel[0]->idCotizacion;
          $idSucursal = $consulta[0]->idSucursal;
          $consultaCot2 =\DB::select("SELECT idCotizacion, numeroCotizacion, idSucursal 
          FROM cotizaciones WHERE estatus = 'Aceptada con orden de compra' AND idSucursal = ? AND idCotizacion 
          NOT IN (SELECT idCotizacion FROM facturas WHERE idCotizacion != 'NULL')",[$idSucursal]);

           $equiposNuevosYreparados = \DB::select("SELECT dr.tipoCotizacion,dr.idRepCotizacion,dr.piezas,dr.idEquipos,dr.idCotizacion,dr.descripcion,dr.modelo,dr.numeroCotizacion,dr.notas,dr.semanasEntrega,dr.montoEquipo,
                                      dr.montoFinanciamiento,dr.descuento,dr.tipoDescuento, dr.partida
                                      FROM detallereparaciones AS dr
                                      WHERE idCotizacion=?",[$idCot]);
            $datosEquiposNuevos = \DB::select("SELECT pv.nombreRefaccion,pv.numeroParte,dr.tipoCotizacion,dr.idEquipos
            FROM detallereparaciones AS dr
            INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idEquipos
            WHERE idCotizacion=? AND tipoCotizacion !='reparacion'",[$idCot]);
        
            $datosEquipoCotizado = \DB::select("SELECT re.idEquipos, re.serie,
            CONCAT (re.serie,' - ',te.tipoEquipo,' - ',se.subtipoEquipo) AS equipo
            FROM recepcionEquipos AS re
            INNER JOIN tipoequipos AS te ON te.idTipoEquipo= re.idTipoEquipo
            INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= re.idSubtipoEquipo
            WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones)");
            $cotizacionEditar1 = \DB::select("SELECT co.idCotizacion,co.idc,co.idu,co.idSucursal,co.numeroCotizacion,co.cotizacionOriginal,co.fechaCotizacion,co.estatus,tipoMoneda,
            CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS usuario,
            co.noEquipos,co.dirigidoA,co.conCopia,
            FORMAT(co.montoTotal,2) AS montoTotal,c.razonSocial AS cliente, s.sucursal AS sucursal, co.estatus,co.check1,co.textoCheck1,co.check2,co.textoCheck2,co.check3,co.textoCheck3,co.check4,
            co.textoCheck4,co.check5,co.textoCheck5,co.check6,co.textoCheck6,co.check7,co.textoCheck7
            FROM cotizaciones AS co
            INNER JOIN usuarios AS u ON u.idu= co.idu
            INNER JOIN sucursales AS s ON s.idSucursal= co.idSucursal
            INNER JOIN clientes AS c ON c.idc= co.idc
            WHERE idCotizacion=?",[$idCot]);        

            $equiposReparados = \DB::select("SELECT dr.tipoCotizacion,dr.idRepCotizacion,dr.idEquipos,dr.idCotizacion, dr.descripcion,dr.modelo,dr.numeroCotizacion,dr.notas,re.GCMid,re.complementoGCMid, dr.semanasEntrega, FORMAT(dr.montoEquipo,2) AS montoEquipo,
            FORMAT(dr.montoFinanciamiento,2) AS montoFinanciamiento,dr.descuento,dr.tipoDescuento
            FROM detallereparaciones AS dr
            INNER JOIN recepcionEquipos AS re ON re.idEquipos = dr.idEquipos
            WHERE idCotizacion=?",[$idCot]);

            $consultaPiezas=\DB::select("SELECT pa.idPiezaAsig, r.idRefaccion, r.codigoRefaccion, r.nombreRefaccion, r.serie, pa.cantidadOcupada,  r.presentacion,
                    p.razonSocialProv, pa.fechaCompra, pa.numeroFacturaProv, f.numeroFactura
                    FROM piezasAsignadas AS pa
                    INNER JOIN refacciones AS r ON r.idRefaccion = pa.idRefaccion
                    INNER JOIN proveedores AS p ON p.idProveedor = pa.idProveedor
                    INNER JOIN facturas AS f ON f.idFactura = pa.idFactura
                    INNER JOIN detallereparaciones AS dr ON dr.idRepCotizacion=pa.idRepCotizacion
                    WHERE dr.idCotizacion=?",[$idCot]);
            $cuantasPiezas=count($consultaPiezas);


        }else{
          // $cotizacionSel[0]->numeroCotizacion = 0;
          $nomCot = 0;
          $idCot= 0;
          $idSucursal = $consulta[0]->idSucursal;
        
          $consultaCot2 =\DB::select("SELECT idCotizacion, numeroCotizacion, idSucursal 
            FROM cotizaciones WHERE estatus = 'Aceptada con orden de compra' AND idSucursal = ? AND idCotizacion 
            NOT IN (SELECT idCotizacion FROM facturas WHERE idCotizacion != 'NULL')",[$idSucursal]);
            
        }
        $consultaCotizaciones = cotizaciones::where ('idCotizacion','!=',$consulta[0]->idCotizacion)->get(); 

        $cotizacionAsig= cotizacionServicios::where('idCotizacionServicios',"=",$consulta[0]->idCotizacion)->get();
        $cuantosCotAsig = count($cotizacionAsig);
        $consu = $consulta[0]->tipoServicio;
        $idCoti = $consulta[0]->idCotizacion;

        if($consu == 'Soporte Técnico'){
        $idSucursales = $consulta[0]->idSucursal;
        $idCoti = $consulta[0]->idCotizacion;
          if($idCoti == ''){
            $consultaServicioTecnico = \DB::select("SELECT idCotizacionServicios,numeroCotizacion, estatus FROM cotizacionServicios 
                                                    WHERE estatus='Aceptada con orden de compra' AND idSucursal = $idSucursales");
            $cuantasPiezas=0;
          }else{
            $consultaServicioTecnico = \DB::select("SELECT idCotizacionServicios,numeroCotizacion, estatus FROM cotizacionServicios 
                                                WHERE estatus='Aceptada con orden de compra' AND idSucursal = $idSucursales 
                                                AND idCotizacionServicios = (SELECT idCotizacion FROM facturas WHERE idCotizacion = $idCoti)");

            $archivoServTec = \DB::select("SELECT archivoCotizacion FROM cotizacionServicios 
                                          WHERE idCotizacionServicios = (SELECT idCotizacion FROM facturas WHERE idCotizacion = $idCoti)");
            
            $serviciosEncotizacion = \DB::select("SELECT dcs.idDetalleCotizacionServ,dcs.idCotizacionServicios,
            st.servicioTecnico,dcs.detalle,dcs.observacionCantidad,dcs.numeroCotizacion,dcs.notas,dcs.semanasEntrega,dcs.tipoMoneda,dcs.montoEquipo,dcs.cantidad,
            dcs.montoFinanciamiento,dcs.descuento,dcs.tipoDescuento,dcs.clonEquiposActivo,dcs.agregadoReciente
            FROM detalleCotizacionServicios AS dcs
            LEFT JOIN serviciosTecnicos AS st ON st.idServicioTecnico=dcs.idServicioTecnico
            WHERE idCotizacionServicios=?",[$idCoti]);
            
          }  
        }

        $idCoti=0;
        $serviciosEncotizacion = \DB::select("SELECT dcs.idDetalleCotizacionServ,dcs.idCotizacionServicios,
            st.servicioTecnico,dcs.detalle,dcs.observacionCantidad,dcs.numeroCotizacion,dcs.notas,dcs.semanasEntrega,dcs.tipoMoneda,dcs.montoEquipo,dcs.cantidad,
            dcs.montoFinanciamiento,dcs.descuento,dcs.tipoDescuento,dcs.clonEquiposActivo,dcs.agregadoReciente
            FROM detalleCotizacionServicios AS dcs
            LEFT JOIN serviciosTecnicos AS st ON st.idServicioTecnico=dcs.idServicioTecnico
            WHERE idCotizacionServicios=?",[$idCoti]);

        $consultaArchivo = \DB::select("SELECT v.archivo FROM facturas AS v Where idFactura = $idFactura ");
        $consultaFactura = \DB::select("SELECT v.archivoFactura FROM facturas AS v Where idFactura = $idFactura ");
        $consultaPago = \DB::select("SELECT v.archivoPago FROM facturas AS v Where idFactura = $idFactura ");
        $consultaxml = \DB::select("SELECT v.xmlFactura FROM facturas AS v Where idFactura = $idFactura ");
        $consultaRemision = \DB::select("SELECT v.archivoRemision FROM facturas AS v Where idFactura = $idFactura ");
        $consultaOtro = \DB::select("SELECT v.archivoOtro FROM facturas AS v Where idFactura = $idFactura ");
        $consultaAdenda = \DB::select("SELECT v.archivoAdenda FROM facturas AS v Where idFactura = $idFactura ");
        $anexos = \DB::select("SELECT v.anexo1 FROM facturas AS v Where idFactura = $idFactura ");
        $anexo2 = \DB::select("SELECT v.anexo2 FROM facturas AS v Where idFactura = $idFactura ");
        $anexo3 = \DB::select("SELECT v.anexo3 FROM facturas AS v Where idFactura = $idFactura ");
        $anexo4 = \DB::select("SELECT v.anexo4 FROM facturas AS v Where idFactura = $idFactura ");
        $anexo5 = \DB::select("SELECT v.anexo5 FROM facturas AS v Where idFactura = $idFactura ");
        $anexo6 = \DB::select("SELECT v.anexo6 FROM facturas AS v Where idFactura = $idFactura ");
        $anexo7 = \DB::select("SELECT v.anexo7 FROM facturas AS v Where idFactura = $idFactura ");
        $anexo8 = \DB::select("SELECT v.anexo8 FROM facturas AS v Where idFactura = $idFactura ");
        $anexo9 = \DB::select("SELECT v.anexo9 FROM facturas AS v Where idFactura = $idFactura ");
        $anexo10 = \DB::select("SELECT v.anexo10 FROM facturas AS v Where idFactura = $idFactura ");
        $archivo =\DB::select("SELECT idCotizacion,archivoCotizacion FROM cotizaciones WHERE estatus='Aceptada con orden de compra' AND idCotizacion=?",[$consulta[0]->idCotizacion]);
        $archivo =\DB::select("SELECT idCotizacion,archivoCotizacion FROM cotizaciones WHERE estatus='Aceptada con orden de compra' AND idCotizacion=?",[$consulta[0]->idCotizacion]);

        $consultaPiezas=\DB::select("SELECT pa.idPiezaAsig, r.idRefaccion, r.codigoRefaccion, r.nombreRefaccion, r.serie, pa.cantidadOcupada,  r.presentacion,
                    p.razonSocialProv, pa.fechaCompra, pa.numeroFacturaProv, f.numeroFactura
                    FROM piezasAsignadas AS pa
                    INNER JOIN refacciones AS r ON r.idRefaccion = pa.idRefaccion
                    INNER JOIN proveedores AS p ON p.idProveedor = pa.idProveedor
                    INNER JOIN facturas AS f ON f.idFactura = pa.idFactura
                    INNER JOIN detallereparaciones AS dr ON dr.idRepCotizacion=pa.idRepCotizacion
                    WHERE dr.idCotizacion=?",[$consulta[0]->idCotizacion]);
            $cuantasPiezas=count($consultaPiezas);
            
            ///// Reporte de Utilidades/////
            $consultaUtilidad = \DB::select("SELECT t1.idfactura, t1.clavegasto, t1.fechasalida,t1.cuentagasto,t1.tipo, t1.beneficiario, t1.formaPago, FORMAT(t1.subtotal,2 ) AS subTotal, t1.moneda,
                                t1.ivaTotal, t1.isrTotal, FORMAT(t1.total,2) AS total, t1.divisa,t1.totalpesos
                                FROM
                                    (SELECT idfactura, CONCAT('OC - ',oc.codigoorden) AS clavegasto, oc.fechaorden AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
                                    'Orden Compra' AS tipo, CONCAT('Prov -  ',p.razonSocialProv) AS beneficiario,formaPago,importeOrden AS subtotal, moneda, ivaCompra AS ivaTotal, isrCompra AS isrTotal, total, moneda AS divisa,
                                    IF(moneda= 'USD', total * cambiodolar, total) AS totalpesos
                                    FROM ordenCompra AS oc
                                    INNER JOIN cuentas AS c ON c.idcuenta = oc.idcuenta
                                    INNER JOIN proveedores AS p ON p.idProveedor = oc.idProveedor
                                    WHERE IDFACTURA= $idFactura
                                UNION
                                    SELECT id_factura AS idfactura, CONCAT('REF - ',g.referencia) AS clavegasto,  g.fecha_pago AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
                                    'GASTO' AS tipo, beneficiario, forma_pago AS formaPago, factura AS subtotal, moneda, total_iva AS ivaTotal,total_isr AS isrTotal, total, moneda AS divisa,
                                    IF(moneda='USD', total*cambiodolar,total) AS totalpesos
                                    FROM gastos AS g
                                    INNER JOIN cuentas AS c ON c.idcuenta = g.idCuenta
                                    WHERE ID_FACTURA = $idFactura)
                                AS t1
                                ORDER BY t1.fechasalida ASC");
                                $consultaUtilidadServ = $this->utilidadJson($consultaUtilidad);

            $consultaTotales = \DB::select("SELECT 
                                TRUNCATE(SUM(t1.ivaTotal),2) AS totaliva, TRUNCATE(SUM(t1.isrTotal),2) AS totalisr, TRUNCATE(SUM(t1.totalpesos),2) AS totalpesos
                                FROM
                                (SELECT CONCAT('OC - ',oc.codigoorden) AS clavegasto,importeOrden AS subtotal, moneda, ivaCompra AS ivaTotal, isrCompra AS isrTotal, total, moneda AS divisa,
                                IF(moneda= 'USD', total * cambiodolar, total) AS totalpesos
                                FROM ordenCompra AS oc
                                INNER JOIN cuentas AS c ON c.idcuenta = oc.idcuenta
                                WHERE IDFACTURA= $idFactura
                                UNION
                                SELECT CONCAT('REF - ',g.referencia) AS clavegasto, factura AS subtotal, moneda, total_iva AS ivaTotal,total_isr AS isrTotal, total, moneda AS divisa,
                                IF(moneda='USD', total*cambiodolar,total) AS totalpesos
                                FROM gastos AS g
                                INNER JOIN cuentas AS c ON c.idcuenta = g.idCuenta
                                WHERE ID_FACTURA = $idFactura)
                                AS t1");
                                if(count($consultaTotales)>=1)
                                {
                                  $totalIva =  $consultaTotales[0]->totaliva;
                                  $totalIsr =  $consultaTotales[0]->totalisr;
                                  $totalFinal =  $consultaTotales[0]->totalpesos;
                                }else{
                                  $totalIva = 0;
                                  $totalIsr = 0;
                                  $totalFinal = 0;
                                }
            $servicioTotal = \DB::select("SELECT t2.factura, t2.totalservicio
                                FROM
                                    (SELECT idFactura AS factura, IF(tipoMoneda= 'USD', totalConIva * cambioReal, totalConIva) AS totalservicio
                                    FROM facturas AS f
                                    
                                    WHERE idFactura=$idFactura)
                                AS t2");
                                $servT = $servicioTotal[0]->totalservicio;
            ///////////////////////////////
            
            ///////////////////////////////
            $totalMxn = \DB::select("SELECT t1.totalpesos, t1.fechasalida
                                    FROM
                                    (SELECT IF(moneda='USD', total*cambiodolar,total) AS totalpesos, fecha_pago AS fechasalida
                                      FROM gastos
                                      WHERE ID_FACTURA = $idFactura AND activo = 'Si')
                                    AS t1
                                    ORDER BY t1.fechasalida ASC");
            ///////////////////////////////
            /////////////Gastos//////////////////
            $gastoServ = \DB::select("SELECT 
            id, 
            id_factura, 
            nombreGasto,
            beneficiario,
            fecha_pago,
            metodo_pago,
            forma_pago,
            referencia, 
            etiquetas, 
            CONCAT(moneda,' ',factura) AS factura, 
            iva, 
            CONCAT(moneda,' ',total_iva) AS total_iva, 
            isr, 
            CONCAT(moneda,' ',total_isr) AS total_isr, 
            CONCAT(moneda,' ',total) AS total, 
            descripcion, 
            moneda, 
            cambiodolar,
            activo,
            IF(moneda='USD',total*cambiodolar,total) AS totalmxn
            FROM gastos 
            WHERE id_factura = $idFactura
            ORDER BY fecha_pago ASC");
            $consultaGastoServ = $this->gastoJson($gastoServ);

            // dd($consultaGastoServ);

            ///////////////////////////////

            ///////////////////////////////
        
        if($consu == 'Soporte Técnico'){
          if($idCoti == ''){
            return view ('editarComisiones',compact( 'consultaOC','consultaGastoServ','consultaUtilidadServ'))
            ->with('cuantasCompras',$cuantasCompras)
            ->with('sumaTotal',$sumaTotal[0])
            ->with('cuantosProductosEnOrden',$cuantosProductosEnOrden)
            ->with('ordenCompra',$ordenCompra)
            ->with('cuantasOrden',$cuantasOrden)
            ->with('consultaCuentas',$consultaCuentas)
            ->with('consultaOrden',$consultaOrden)
            ->with('consultaProveedor',$consultaProveedor)
            ->with('cuantosSeguimiento',$cuantosSeguimiento)
            ->with('seguimiento',$seguimiento)
            ->with('comisionesAll',$comisionesAll)


              ->with('cuantasPiezas',$cuantasPiezas)
              ->with('consultaServicioTecnico',$consultaServicioTecnico)
              ->with('cuantosCotAsig',$cuantosCotAsig)
              ->with('idCoti',$idCoti)
              ->with('serviciosEncotizacion',$serviciosEncotizacion)
              ->with('consu',$consu)

              ->with('stipo',$stipo)
              ->with('consulta',$consulta[0])
              ->with('consultaArchivosFac',$consultaArchivosFac)
              ->with('cuantosAF',$cuantosAF)
              ->with('consultaDatosPago',$consultaDatosPago)
              ->with('cuantosDP',$cuantosDP)
              ->with('totalDP',$totalDP[0])
              
              
              ->with('bancos',$bancos)
              ->with('idbsel',$consulta[0]->idb)
              ->with('cuantosBancos',$cuantosBancos)
              ->with('consultaBancos',$consultaBancos)
              ->with('idBan',$idBan)
              ->with('nomban',$nomban)

              ->with('consultaCot2',$consultaCot2)
              ->with('cuantos',$cuantos)
            
              ->with('consultaCotizaciones',$consultaCotizaciones)
              ->with('idCotizacionSel',$consulta[0]->idCotizacion)
              ->with('nomCot',$nomCot)
              ->with('idCot',$idCot)
              // ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
              // ->with('equiposReparados',$equiposReparados)
              // ->with('datosEquiposNuevos',$datosEquiposNuevos)
              // ->with('datosEquipoCotizado',$datosEquipoCotizado)
              // ->with('cotizacionEditar1',$cotizacionEditar1[0])
          
              ->with('cliente',$cliente)
              ->with('idclientesel',$consulta[0]->idc)
              ->with('nomcli',$nomcli)
              ->with('clienteSel',$clienteSel[0])

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
              ->with('anexo6',$anexo6)
              ->with('anexo7',$anexo7)
              ->with('anexo8',$anexo8)
              ->with('anexo9',$anexo9)
              ->with('anexo10',$anexo10)
              ->with('sucursal',$sucursal)
              ->with('idSucursal',$consulta[0]->idSucursal)
              ->with('nomsuc',$nomsuc)
              ->with('sucursalSel',$sucursalSel[0])
              ->with('nomsuc',$nomsuc)
              ->with('archivo',$archivo)
              ->with('idFactura', $idFactura)
              ->with('gastos',$gastos)
      ->with('contadorGastos', $contadorGastos)
              ->with('empresa', $consulta[0]->nombreEmpresa)
              ->with('ReporteUtilidad', $consultaUtilidad)
              ->with('totalIva', $totalIva)
              ->with('totalIsr', $totalIsr)
              ->with('totalFinal', $totalFinal)
              ->with('servicioT', $servT)
              ->with('totalMxn',$totalMxn);


          }else{
            return view ('editarComisiones',compact( 'consultaOC','consultaGastoServ','consultaUtilidadServ'))
            ->with('sumaTotal',$sumaTotal[0])
            ->with('cuantasCompras',$cuantasCompras)
            ->with('cuantosProductosEnOrden',$cuantosProductosEnOrden)
            ->with('ordenCompra',$ordenCompra)
            ->with('cuantasOrden',$cuantasOrden)
            ->with('consultaCuentas',$consultaCuentas)
            ->with('consultaOrden',$consultaOrden)
            ->with('comisionesAll',$comisionesAll)
            ->with('consultaProveedor',$consultaProveedor)
            ->with('cuantosSeguimiento',$cuantosSeguimiento)
            ->with('seguimiento',$seguimiento)
            ->with('cuantasPiezas',$cuantasPiezas)
            ->with('consultaServicioTecnico',$consultaServicioTecnico)
            ->with('archivoServTec',$archivoServTec)
            ->with('cuantosCotAsig',$cuantosCotAsig)
            ->with('idCoti',$idCoti)
            ->with('serviciosEncotizacion',$serviciosEncotizacion)
            ->with('consu',$consu)


            ->with('stipo',$stipo)
            ->with('consulta',$consulta[0])
            ->with('consultaArchivosFac',$consultaArchivosFac)
            ->with('cuantosAF',$cuantosAF)
            ->with('consultaDatosPago',$consultaDatosPago)
            ->with('cuantosDP',$cuantosDP)
            ->with('totalDP',$totalDP[0])
            
            ->with('bancos',$bancos)
            ->with('idbsel',$consulta[0]->idb)
            ->with('cuantosBancos',$cuantosBancos)
            ->with('consultaBancos',$consultaBancos)
            ->with('idBan',$idBan)
            ->with('nomban',$nomban)

            ->with('consultaCot2',$consultaCot2)
            ->with('cuantos',$cuantos)
           
            ->with('consultaCotizaciones',$consultaCotizaciones)
            ->with('idCotizacionSel',$consulta[0]->idCotizacion)
            ->with('nomCot',$nomCot)
            ->with('idCot',$idCot)
            // ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
            // ->with('equiposReparados',$equiposReparados)
            // ->with('datosEquiposNuevos',$datosEquiposNuevos)
            // ->with('datosEquipoCotizado',$datosEquipoCotizado)
            // ->with('cotizacionEditar1',$cotizacionEditar1[0])
         
            ->with('cliente',$cliente)
            ->with('idclientesel',$consulta[0]->idc)
            ->with('nomcli',$nomcli)
            ->with('clienteSel',$clienteSel[0])

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
            ->with('anexo6',$anexo6)
            ->with('anexo7',$anexo7)
            ->with('anexo8',$anexo8)
            ->with('anexo9',$anexo9)
            ->with('anexo10',$anexo10)
            ->with('sucursal',$sucursal)
            ->with('idSucursal',$consulta[0]->idSucursal)
            ->with('nomsuc',$nomsuc)
            ->with('sucursalSel',$sucursalSel[0])
            ->with('nomsuc',$nomsuc)
            ->with('archivo',$archivo)
            ->with('idFactura', $idFactura)
            ->with('gastos',$gastos)
    ->with('contadorGastos', $contadorGastos)
            ->with('empresa', $consulta[0]->nombreEmpresa)
            ->with('ReporteUtilidad', $consultaUtilidad)
              ->with('totalIva', $totalIva)
              ->with('totalIsr', $totalIsr)
              ->with('totalFinal', $totalFinal)
              ->with('servicioT', $servT)
              ->with('totalMxn',$totalMxn);
            
            }
          }else{
            if($idCot == ''){
              return view ('editarComisiones',compact( 'consultaOC','consultaGastoServ','consultaUtilidadServ'))
              ->with('cuantasCompras',$cuantasCompras)
              ->with('sumaTotal',$sumaTotal[0])
              ->with('cuantosProductosEnOrden',$cuantosProductosEnOrden)
              ->with('ordenCompra',$ordenCompra)
              ->with('cuantasOrden',$cuantasOrden)
              ->with('consultaCuentas',$consultaCuentas)
              ->with('consultaOrden',$consultaOrden)
              ->with('consultaProveedor',$consultaProveedor)
              ->with('cuantosSeguimiento',$cuantosSeguimiento)
              ->with('cuantasPiezas',$cuantasPiezas)
              ->with('cuantosCotAsig',$cuantosCotAsig)
              ->with('consu',$consu)

              ->with('stipo',$stipo)
              ->with('idCot',$idCot)
              ->with('idCoti',$idCoti)
              

              ->with('consulta',$consulta[0])
              ->with('consultaArchivosFac',$consultaArchivosFac)
              ->with('cuantosAF',$cuantosAF)
              ->with('consultaDatosPago',$consultaDatosPago)
              ->with('cuantosDP',$cuantosDP)
              ->with('totalDP',$totalDP[0])
              // ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
              // ->with('equiposReparados',$equiposReparados)
              // ->with('datosEquiposNuevos',$datosEquiposNuevos)
              // ->with('datosEquipoCotizado',$datosEquipoCotizado)
              // ->with('cotizacionEditar1',$cotizacionEditar1[0])
              
              
              ->with('bancos',$bancos)
              ->with('idbsel',$consulta[0]->idb)
              ->with('cuantosBancos',$cuantosBancos)
              ->with('consultaBancos',$consultaBancos)
              ->with('idBan',$idBan)
              ->with('nomban',$nomban)

              ->with('consultaCot2',$consultaCot2)
              ->with('cuantos',$cuantos)
             
              ->with('consultaCotizaciones',$consultaCotizaciones)
              ->with('idCotizacionSel',$consulta[0]->idCotizacion)
              ->with('nomCot',$nomCot)
              ->with('idCot',$idCot)
           
              ->with('cliente',$cliente)
              ->with('idclientesel',$consulta[0]->idc)
              ->with('nomcli',$nomcli)
              ->with('clienteSel',$clienteSel[0])

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
              ->with('anexo6',$anexo6)
              ->with('anexo7',$anexo7)
              ->with('anexo8',$anexo8)
              ->with('anexo9',$anexo9)
              ->with('anexo10',$anexo10)
              ->with('sucursal',$sucursal)
              ->with('idSucursal',$consulta[0]->idSucursal)
              ->with('nomsuc',$nomsuc)
              ->with('sucursalSel',$sucursalSel[0])
              ->with('nomsuc',$nomsuc)
              ->with('archivo',$archivo)
              ->with('idFactura', $idFactura)
              ->with('gastos',$gastos)
              ->with('totalMxn',$totalMxn)
      ->with('contadorGastos', $contadorGastos)
              ->with('empresa', $consulta[0]->nombreEmpresa)
              ->with('ReporteUtilidad', $consultaUtilidad)
              ->with('totalIva', $totalIva)
              ->with('totalIsr', $totalIsr)
              ->with('totalFinal', $totalFinal)
              ->with('servicioT', $servT)
              ->with('empleados', $empleados)
              ->with('comisionesAll', $comisionesAll);
              
              

            }else{

              return view ('editarComisiones',compact( 'consultaOC','consultaGastoServ','consultaUtilidadServ'))
              ->with('cuantasCompras',$cuantasCompras)
              ->with('sumaTotal',$sumaTotal[0])
              ->with('cuantosProductosEnOrden',$cuantosProductosEnOrden)
              ->with('ordenCompra',$ordenCompra)
              ->with('cuantasOrden',$cuantasOrden)
              ->with('consultaCuentas',$consultaCuentas)
              ->with('consultaOrden',$consultaOrden)
              ->with('consultaProveedor',$consultaProveedor)
              ->with('cuantosSeguimiento',$cuantosSeguimiento)
              ->with('seguimiento',$seguimiento)

              ->with('cuantasPiezas',$cuantasPiezas)
              ->with('cuantosCotAsig',$cuantosCotAsig)
              ->with('consu',$consu)

              ->with('stipo',$stipo)
              ->with('idCot',$idCot)
              ->with('idCoti',$idCoti)
              

              ->with('consulta',$consulta[0])
              ->with('consultaArchivosFac',$consultaArchivosFac)
              ->with('cuantosAF',$cuantosAF)
              ->with('consultaDatosPago',$consultaDatosPago)
              ->with('cuantosDP',$cuantosDP)
              ->with('totalDP',$totalDP[0])
              ->with('equiposNuevosYreparados',$equiposNuevosYreparados)
              ->with('equiposReparados',$equiposReparados)
              ->with('consultaPiezas',$consultaPiezas)
              ->with('cuantasPiezas',$cuantasPiezas)
              ->with('datosEquiposNuevos',$datosEquiposNuevos)
              ->with('datosEquipoCotizado',$datosEquipoCotizado)
              ->with('cotizacionEditar1',$cotizacionEditar1[0])
              
              
              ->with('bancos',$bancos)
              ->with('idbsel',$consulta[0]->idb)
              ->with('cuantosBancos',$cuantosBancos)
              ->with('consultaBancos',$consultaBancos)
              ->with('idBan',$idBan)
              ->with('nomban',$nomban)

              ->with('consultaCot2',$consultaCot2)
              ->with('cuantos',$cuantos)
             
              ->with('consultaCotizaciones',$consultaCotizaciones)
              ->with('idCotizacionSel',$consulta[0]->idCotizacion)
              ->with('nomCot',$nomCot)
              ->with('idCot',$idCot)
           
              ->with('cliente',$cliente)
              ->with('idclientesel',$consulta[0]->idc)
              ->with('nomcli',$nomcli)
              ->with('clienteSel',$clienteSel[0])

              ->with('empleados', $empleados)
              ->with('comisionesAll', $comisionesAll)

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
              ->with('anexo6',$anexo6)
              ->with('anexo7',$anexo7)
              ->with('anexo8',$anexo8)
              ->with('anexo9',$anexo9)
              ->with('anexo10',$anexo10)
              ->with('sucursal',$sucursal)
              ->with('idSucursal',$consulta[0]->idSucursal)
              ->with('nomsuc',$nomsuc)
              ->with('sucursalSel',$sucursalSel[0])
              ->with('nomsuc',$nomsuc)
              ->with('archivo',$archivo)
              ->with('idFactura', $idFactura)
              ->with('gastos',$gastos)
      ->with('contadorGastos', $contadorGastos)
              ->with('empresa', $consulta[0]->nombreEmpresa)
              ->with('ReporteUtilidad', $consultaUtilidad)
              ->with('totalIva', $totalIva)
              ->with('totalIsr', $totalIsr)
              ->with('totalFinal', $totalFinal)
              ->with('servicioT', $servT)
              ->with('totalMxn',$totalMxn);
              

            }
           
            }

        }
    }
    public function json ( $query){

      function options( $value, $value2 ){
          $parameter= Crypt::encrypt($value2);

          if($value=='Si'){
              // $btn = "<a href=".url("/restaurarEquipos/{$value2}")." type='submit' class='btn btn-xs btn-success'>Restaurar</a>";
              $btn = "<a href='".url("/eliminarOrden/{$value2}")."' type='submit' class='btn btn-xs btn-danger'><i class='ace-icon fa fa-trash-o bigger-120'> Eliminar</i></a><br>".
                      "<a href='".url("/modificarOrden/{$value2}")."' type='submit' class='btn btn-xs btn-info'><i class='ace-icon fa fa-pencil bigger-120'> Editar</i></a><br>".
                      "<a href='".url("/pdfOrdenCompras/{$parameter}")."' type='submit' class='btn btn-xs btn-warning'><i class='ace-icon fa fa-download bigger-120'> Descargar PDF</i></a><br>";
          }else{
              $btn = "<a href=".url("/restaurarOrden/{$value2}")." type='submit' class='btn btn-xs btn-warning'>Restaurar</a>";
          }
          
          return $btn;
          
      }


      $consult = array();
      foreach( $query as $value)
      {
          array_push( $consult, array(
              'id'    => $value->idOrden, 
              'a'    => $value->idServicios, 
              'b'    => $value->codigoOrden, 
              'c'    => $value->fechaOrden, 
              'd'    => $value->razonSocialProv, 
              'e'    => $value->sucursal, 
              'f'    => $value->nombreEmpresa, 
              'g'    => $value->cuenta, 
              'h'    => $value->importeOrden, 
              'i'    => $value->ivaCom, 
              'j'    => $value->isrCom, 
              'k'    => $value->total, 
              'm'    => $value->totalMXN, 
              'l'    => $value->estatus, 
              'o'     => options( $value->activo, $value->idOrden, $value->idFactura),
          ) );

      }

      return $consult; 
  }
  
  public function excelOC(Request $request){
    return Excel::download( new OCFacturasExport( $request->data, $request->user), 'reporteOrdenCompra.xlsx' );
}
public function utilidadJson($consultaUtilidad){
  $consultaUt = array();
  foreach( $consultaUtilidad as $value)
    {
        array_push( $consultaUt, array(
            'id'    => $value->idfactura, 
            'a'    => $value->clavegasto, 
            'b'    => $value->fechasalida, 
            'c'    => $value->cuentagasto, 
            'd'    => $value->tipo, 
            'e'    => $value->beneficiario, 
            'f'    => $value->formaPago, 
            'g'    => $value->subTotal, 
            'h'    => $value->ivaTotal, 
            'i'    => $value->isrTotal, 
            'j'    => $value->total, 
            'k'    => $value->totalpesos, 
            'o'    => $value->total, 
           // 'l'    => $value->estatus, 
           // 'm'     => options( $value->activo, $value->idOrden),
        ) );

    }

    return $consultaUt;
}

      public function editarFacturas(Request $request){
          $idServicios = $request -> idServicios;
          $tipoServicio = $request -> tipoServicio;
          $idSucursal = $request -> idSucursal;
          $idCotizacion = $request -> idCotizacion;
          $idCot2 = $request -> idCot2;
          $nombreEmpresa = $request -> nombreEmpresa;

          $idFactura = $request -> idFactura;
          $numeroFactura = $request -> numeroFactura;
          $fechaVencimiento = $request -> fechaVencimiento;
          $idb = $request -> idb;
          $montoFactura = $request -> montoFactura;
          $periodoPago = $request -> periodoPago;
          $fechaFactura = $request -> fechaFactura;
          $fechaPago = $request -> fechaPago;
          $tipoMoneda = $request -> tipoMoneda;
          $idc = $request -> idc;
          $cambioFactura = $request -> cambioFactura;
          $montoPesos = $request -> montoPesos;
          $saldo = $request -> saldo;
          $cambioReal = $request -> cambioReal;
          $observacionesFactura = $request -> observacionesFactura;
          $ordenCompra = $request -> ordenCompra;
          $factura = $request -> factura;
          $comprobantePago = $request -> comprobantePago;
          $Archivo = $request -> Archivo;
          $metodoPago = $request -> metodoPago;
          $estatusEntrega = $request -> estatusEntrega;
          $estatusPortal = $request -> estatusPortal;
          $estatusPago = $request -> estatusPago;
          $cashFlow = $request -> cashFlow;
          $numeroRemision = $request -> numeroRemision;
          $numeroEntrada = $request -> numeroEntrada;
          $complementoPago = $request -> complementoPago;
          $numeroDocumento = $request -> numeroDocumento;
          $archivoOtro = $request -> archivoOtro;
          $this->validate ($request,[
          // 'fechaFactura'=>['required'],
          // 'fechaPago'=>['required'],
          // 'tipoMoneda'=>['required'],
          // // 'idb'=>['required'],
          // 'montoFactura'=>['required'],
          // 'periodoPago'=>['required'],
          // 'idc'=>['required'],
          // 'idSucursal'=>['required'],
          // 'estatusEntrega' =>['required'],
          // 'estatusPortal' =>['required'],
          // 'estatusPago' =>['required'],
          // 'cashFlow' =>['required'],
          // 'saldo'=>['required'],
          // 'periodoPago'=>['required'],
          'archivoFactura'=>['mimes:doc,docx,pdf'],
          /*'xmlFactura'=>['mimes:xml,XML'],*/]);
//Orden de compra
       $file = $request->file('archivo');
      //obtenemos el nombre del archivo
           if($file!=""){
      if($idCot2){
        $consulta = \DB::update("UPDATE recepcionEquipos SET estatusEntrega='Con OC' WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones WHERE idCotizacion = $idCot2)");
      }
        $archivo =  time()."_".$file->getClientOriginalName();
      //indicamos que queremos guardar un nuevo archivo en el disco local
      \Storage::disk('local')->put($archivo,  \File::get($file));
          }
//Factura
          $file2 = $request->file('archivoFactura');
           if($file2!=""){
            if($idCot2){
              $consulta = \DB::update("UPDATE recepcionEquipos SET estatusEntrega='Facturado' WHERE idEquipos IN (SELECT idEquipos FROM detallereparaciones WHERE idCotizacion = $idCot2)");
            }
              $archivoFactura =  time()."_".$file2->getClientOriginalName();
      \Storage::disk('local')->put($archivoFactura,  \File::get($file2));
          }
//Comprobante de Pgo
          $file3 = $request->file('archivoPago');
           if($file3!=""){
      $archivoPago =  time()."_".$file3->getClientOriginalName();
      \Storage::disk('local')->put($archivoPago,  \File::get($file3));
          }
//XML
          $file4 = $request->file('xmlFactura');
           if($file4!=""){
      $xmlFactura =  time()."_".$file4->getClientOriginalName();
      \Storage::disk('local')->put($xmlFactura,  \File::get($file4));
          }
//Remision            
          $file5 = $request->file('archivoRemision');
           if($file5!=""){
      $archivoRemision =  time()."_".$file5->getClientOriginalName();
      \Storage::disk('local')->put($archivoRemision,  \File::get($file5));
          }
//Otro           
          $file6 = $request->file('archivoOtro');
           if($file6!=""){
      $archivoOtro =  time()."_".$file6->getClientOriginalName();
      \Storage::disk('local')->put($archivoOtro,  \File::get($file6));
          }
//adenda           
          $file7 = $request->file('archivoAdenda');
           if($file7!=""){
              $archivoAdenda =  time()."_".$file7->getClientOriginalName();
              \Storage::disk('local')->put($archivoAdenda,  \File::get($file7));
            
           }
//anexo1           
          $file8 = $request->file('anexo1');
           if($file8!=""){
              $anexo1 =  time()."_".$file8->getClientOriginalName();
              \Storage::disk('local')->put($anexo1,  \File::get($file8));
            
           }
//anexo2           
          $file9 = $request->file('anexo2');
           if($file9!=""){
              $anexo2 =  time()."_".$file9->getClientOriginalName();
              \Storage::disk('local')->put($anexo2,  \File::get($file9));
            
           }

//anexo3           
          $file10 = $request->file('anexo3');
          if($file10!=""){
             $anexo3 =  time()."_".$file10->getClientOriginalName();
             \Storage::disk('local')->put($anexo3,  \File::get($file10));
           
          }
//anexo4           
          $file11 = $request->file('anexo4');
           if($file11!=""){
              $anexo4 =  time()."_".$file11->getClientOriginalName();
              \Storage::disk('local')->put($anexo4,  \File::get($file11));
            
           }
//archivo5           
          $file12 = $request->file('anexo5');
          if($file12!=""){
             $anexo5 =  time()."_".$file12->getClientOriginalName();
             \Storage::disk('local')->put($anexo5,  \File::get($file12));
           
          }
//archivo6           
          $file13 = $request->file('anexo6');
          if($file13!=""){
             $anexo6 =  time()."_".$file13->getClientOriginalName();
             \Storage::disk('local')->put($anexo6,  \File::get($file13));
           
          }
//archivo7           
          $file14 = $request->file('anexo7');
          if($file14!=""){
             $anexo7 =  time()."_".$file14->getClientOriginalName();
             \Storage::disk('local')->put($anexo7,  \File::get($file14));
           
          }
//archivo8           
          $file15 = $request->file('anexo8');
          if($file15!=""){
             $anexo8 =  time()."_".$file15->getClientOriginalName();
             \Storage::disk('local')->put($anexo8,  \File::get($file15));
           
          }   
//archivo9           
          $file16 = $request->file('anexo9');
          if($file16!=""){
             $anexo9 =  time()."_".$file16->getClientOriginalName();
             \Storage::disk('local')->put($anexo9,  \File::get($file16));
           
          }      
//archivo10           
          $file17 = $request->file('anexo10');
          if($file17!=""){
             $anexo10 =  time()."_".$file17->getClientOriginalName();
             \Storage::disk('local')->put($anexo10,  \File::get($file17));
           
          }                                       

          $fac = facturas::find($idFactura);
          $fac-> idServicios = $request-> idServicios;
          $fac-> tipoServicio = $request-> tipoServicio;
          $fac-> idSucursal = $request-> idSucursal;
          $fac-> idCotizacion = $request-> idCotizacion;
          $fac-> nombreEmpresa = $request-> nombreEmpresa;
          $fac-> numeroFactura = $request-> numeroFactura;
          $fac-> fechaVencimiento = $request-> fechaVencimiento;
          $fac-> idb = $request-> idb;
          $fac-> montoFactura = $request-> montoFactura;
          $fac-> ivaFactura = $request-> ivaFactura;
          $fac-> retencionIva = $request-> retencionIva;
          $fac-> totalConIva = $request-> totalConIva;
          $fac-> periodoPago = $request-> periodoPago;
          $fac-> fechaFactura = $request-> fechaFactura;
          $fac-> fechaPago = $request-> fechaPago;
          $fac-> tipoMoneda = $request-> tipoMoneda;
          $fac-> idc = $request-> idc;
          $fac-> cambioFactura = $request-> cambioFactura;
          $fac-> montoPesos = $request-> montoPesos;
          $fac-> saldo = $request-> saldo;
          $fac-> cambioReal = $request-> cambioReal;
          $fac-> observacionesFactura = $request-> observacionesFactura;
          $fac-> ordenCompra = $request-> ordenCompra;
          $fac-> factura = $request-> factura;
          $fac-> comprobantePago = $request-> comprobantePago;
          $fac-> metodoPago = $request-> metodoPago;
          $fac-> estatusEntrega = $request-> estatusEntrega;
          $fac-> estatusPortal = $request-> estatusPortal;
          $fac-> estatusPago = $request-> estatusPago;
          $fac-> cashFlow = $request-> cashFlow;
          $fac-> numeroRemision = $request-> numeroRemision;
          $fac-> numeroEntrada = $request-> numeroEntrada;
          $fac-> complementoPago = $request-> complementoPago;
          $fac-> numeroDocumento = $request-> numeroDocumento;
          $fac-> adenda = $request-> adenda;
          $fac-> numeroAnexo1 = $request-> numeroAnexo1;
          $fac-> numeroAnexo2 = $request-> numeroAnexo2;
          $fac-> numeroAnexo3 = $request-> numeroAnexo3;
          $fac-> numeroAnexo4 = $request-> numeroAnexo4;
          $fac-> numeroAnexo5 = $request-> numeroAnexo5;
          $fac-> numeroAnexo6 = $request-> numeroAnexo6;
          $fac-> numeroAnexo7 = $request-> numeroAnexo7;
          $fac-> numeroAnexo8 = $request-> numeroAnexo8;
          $fac-> numeroAnexo9 = $request-> numeroAnexo9;
          $fac-> numeroAnexo10 = $request-> numeroAnexo10;

          
          if($file!=""){
                      $fac-> archivo = $archivo;
                }
          if($file2!=""){
                      $fac-> archivoFactura = $archivoFactura;
                }
          if($file3!=""){
                      $fac-> archivoPago = $archivoPago;
                } 
          if($file4!=""){
                      $fac-> xmlFactura = $xmlFactura;
                }
          if($file5!=""){
                      $fac-> archivoRemision = $archivoRemision;
                }    
          if($file6!=""){
                      $fac-> archivoOtro = $archivoOtro;
                }
          if($file7!=""){
                  $fac-> archivoAdenda = $archivoAdenda;
                }  
          if($file8!=""){
                  $fac-> anexo1 = $anexo1;
                } 
          if($file9!=""){
                  $fac-> anexo2 = $anexo2;
                } 
          if($file10!=""){
                  $fac-> anexo3 = $anexo3;
                } 
          if($file11!=""){
                  $fac-> anexo4 = $anexo4;
                } 
          if($file12!=""){
                  $fac-> anexo5 = $anexo5;
                }  
          if($file13!=""){
                  $fac-> anexo6 = $anexo6;
                }             
          if($file14!=""){
                  $fac-> anexo7 = $anexo7;
                }  
          if($file15!=""){
                  $fac-> anexo8 = $anexo8;
                }
          if($file16!=""){
                  $fac-> anexo9 = $anexo9;
                }   
          if($file17!=""){
                  $fac-> anexo10 = $anexo10;
                }   

          //Subir Archivos
          $fac-> save();


          $consultaFactura = \DB::select("SELECT v.archivoFactura FROM facturas AS v Where idFactura = $idFactura ");                        
          if ($factura != "" ||  $consultaFactura[0]->archivoFactura != "Sin archivo") {
            $cambiandoEstatusAcotizacion = \DB::UPDATE("UPDATE cotizaciones SET estatus='Facturada' WHERE idCotizacion = ? ",[$idCotizacion]);
            $cambiarEstatus = \DB::select("SELECT idEquipos FROM detallereparaciones WHERE idCotizacion = ? ",[$idCotizacion]);
            $cuantasEquipos = count($cambiarEstatus);

            for ($i=0; $i < $cuantasEquipos; $i++) {
              $cambiandoEstatusEquipos = \DB::UPDATE("UPDATE recepcionEquipos SET estatusEntrega='Facturado' WHERE idEquipos= ?",[$cambiarEstatus[$i]->idEquipos]);
            }
          }

          $proceso='La modificación del servicio fue exitoso';
          $mensaje="Servicio actualizado correctamente";
          return view ('mensajeFactura')
            ->with('proceso',$proceso)
            ->with('mensaje',$mensaje);

      }
      public function gastoJson($gastoServ){
    
        function option( $value, $value2, $value3 ){
          $parameter= Crypt::encrypt($value2, $value3);
    
          if($value=='Si'){
             
              $btn = "<a href='".url("/baja_logica/{$value2}/{$value3}")."' type='submit' class='btn btn-xs btn-danger'><i class='ace-icon fa fa-trash-o bigger-120'> Eliminar</i></a><br>".
                      "<a href='".url("/editarGastos/{$value2}/{$value3}")."' type='submit' class='btn btn-xs btn-info'><i class='ace-icon fa fa-pencil bigger-120'> Editar</i></a><br>";
          }else{
              $btn = "<a href=".url("/baja_logica/{$value2}/{$value3}")." type='submit' class='btn btn-xs btn-warning'>Restaurar</a>";
          }
          
          return $btn;
          
        }
          $consultaG = array();
          foreach( $gastoServ as $value)
          {
              array_push( $consultaG, array(
                  'id'    => $value->id_factura, 
                  'a'    => $value->fecha_pago, 
                  'b'    => $value->beneficiario, 
                  'c'    => $value->metodo_pago, 
                  'd'    => $value->referencia, 
                  'e'    => $value->etiquetas, 
                  'f'    => $value->descripcion, 
                  'g'    => $value->factura, 
                  'h'    => $value->iva, 
                  'i'    => $value->total_iva, 
                  'j'    => $value->isr, 
                  'k'    => $value->total_isr, 
                  'l'    => $value->total, 
                  'm'    => $value->totalmxn, 
                  'o'     => option( $value->activo, $value->id, $value->id_factura)
              ) );
      
          }
      
          return $consultaG;
      }
}
