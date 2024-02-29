<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\usuarios;
use Session;
use App\anexGrid;
use DB;
use DateTime;
use App\seguimientoactividades;
use App\actividades;
use App\areas;
use App\clientes;
use App\sucursales;
use App\asignacionseguimientos;
use App\detalleseguimientos;
use App\evidenciaseguimientos;

class reporteActividadesController extends Controller
{
  public function reporteDeActividades(){
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
   
      return view ('reporteDeActividades')
      ->with('stipo', $stipo);
}
}

public function reporteSeguimientoInicio(Request $request){
  
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
    $actividadesPorcentajes= \DB::select("SELECT idSegActividad,AVG(porcentaje)AS porcentaje FROM (SELECT idSegActividad,MAX(porcentaje)AS porcentaje ,idu  FROM detalleseguimientos GROUP BY idSegActividad,idu) AS t1 GROUP BY idSegActividad");
    $yaAsignadosConsulta= \DB::select("SELECT idSegActividad AS idSegActividad FROM asignacionseguimientos WHERE idu=$sidu GROUP BY idSegActividad");                                           

    for ($i=0; $i < count($actividadesPorcentajes) ; $i++) {
      if ($actividadesPorcentajes[$i]->porcentaje >= 100) {
        $actividadesCerradas[]=$actividadesPorcentajes[$i]->idSegActividad;          
      }      
    }
    for ($i=0; $i < count($yaAsignadosConsulta) ; $i++) {
      if (count($yaAsignadosConsulta) > 0) {
        $yaAsignados[]=$yaAsignadosConsulta[$i]->idSegActividad;
      }      
    }      

    if($stipo == 'Administrador'){      	
      ///////////////////////////////// CREADAS /////////////////////////////////

      $cerradasCreadas = DB::table('seguimientoactividades AS sa')            
      ->select('sa.idSegActividad')
      ->whereIn('sa.idSegActividad',$actividadesCerradas)
      // ->where('sa.activo','=','Si')
      ->get();
      $cuantasCerradasCreadas = count($cerradasCreadas);

      $abiertasCreadas = DB::table('seguimientoactividades AS sa')            
      ->select('sa.idSegActividad')
      ->whereNotIn('sa.idSegActividad',$actividadesCerradas)
      // ->where('sa.activo','=','Si')
      ->get();
      $cuantasAbiertasCreadas = count($abiertasCreadas);

      $expiranCreadas = DB::table('seguimientoactividades AS sa')            
      ->select('sa.idSegActividad')
      ->where('sa.fechaTermino','>=',$semana[0])
      ->where('sa.fechaTermino','<=',$semana[6])
      ->whereNotIn('sa.idSegActividad',$actividadesCerradas)
      // ->where('sa.activo','=','Si')
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
      $actividadesPorcentajes= \DB::select("SELECT idSegActividad,AVG(porcentaje)AS porcentaje FROM (SELECT idSegActividad,MAX(porcentaje)AS porcentaje ,idu  FROM detalleseguimientos GROUP BY idSegActividad,idu) AS t1 GROUP BY idSegActividad");
      for ($i=0; $i < count($actividadesPorcentajes) ; $i++) {
        if ($actividadesPorcentajes[$i]->porcentaje >= 100) {
          $actividadesCerradas[]=$actividadesPorcentajes[$i]->idSegActividad;          
        }      
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
    $limpiando=str_replace('{"idSegActividad":', "", $cadenaDEids);
    $limpiando=str_replace('[', "", $limpiando);
    $limpiando=str_replace('}', "", $limpiando);
    $idParaConsultar=str_replace(']', "", $limpiando); 
    if($seguimientoAsignadas[0]==0){
      $seguimientoAsignadas="0";
    } 
    
    return view ('reporteSeguimientoInicio')
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

public function reporteProspectosAct(){
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
    if($stipo == 'Administrador'){
      
      $usuario = usuarios::orderby('nombreUsuario','asc')->get();
      $prospectos=\DB::select("SELECT ip.idInvProspectos, DATE_FORMAT (ip.fechaAlta,'%d %b %Y') AS fecha, cli.razonSocial, s.sucursal,ip.descripcionActividad,ip.registradoPor,ip.activo
                                      FROM invprospectos AS ip
                                      INNER JOIN clientes AS cli ON cli.idc = ip.idc
                                      INNER JOIN sucursales AS s ON s.idSucursal = ip.idSucursal");
    
      return view ('reporteProspectosAct')
      ->with('prospectos',$prospectos)
      ->with('stipo',$stipo)
      ->with('usuario',$usuario);

    }else{

      $usuario = usuarios::orderby('nombreUsuario','asc')->get();
      $prospectos=\DB::select("SELECT ip.idInvProspectos, DATE_FORMAT (ip.fechaAlta,'%d %b %Y') AS fecha,ip.idu, cli.razonSocial, s.sucursal,ip.descripcionActividad,ip.registradoPor,ip.activo
                                      FROM invprospectos AS ip
                                      INNER JOIN clientes AS cli ON cli.idc = ip.idc
                                      INNER JOIN sucursales AS s ON s.idSucursal = ip.idSucursal
                                      WHERE idu=?",[$sidu]);
    
      return view ('reporteProspectosAct')
      ->with('prospectos',$prospectos)
      ->with('stipo',$stipo)
      ->with('usuario',$usuario);



    }
}
}

public function reporteProspectosActAbajo(Request $request){
  $fechaInicio=$request->fechaInicio;
  $fechaFin=$request->fechaFin;
  $idu=$request->idu;
    if($idu == ''){
    $prospectos=\DB::select("SELECT ip.idInvProspectos, DATE_FORMAT (ip.fechaAlta,'%d %b %Y') AS fecha, cli.razonSocial, s.sucursal,ip.descripcionActividad,ip.idu,ip.registradoPor,ip.activo
                                      FROM invprospectos AS ip
                                      INNER JOIN clientes AS cli ON cli.idc = ip.idc
                                      INNER JOIN sucursales AS s ON s.idSucursal = ip.idSucursal
                                      WHERE fechaAlta>=? AND fechaAlta<=?",[$fechaInicio,$fechaFin]);
    }else{
      $prospectos=\DB::select("SELECT ip.idInvProspectos, DATE_FORMAT (ip.fechaAlta,'%d %b %Y') AS fecha, cli.razonSocial, s.sucursal,ip.descripcionActividad,ip.idu,ip.registradoPor,ip.activo
                                      FROM invprospectos AS ip
                                      INNER JOIN clientes AS cli ON cli.idc = ip.idc
                                      INNER JOIN sucursales AS s ON s.idSucursal = ip.idSucursal
                                      WHERE fechaAlta>=? AND fechaAlta<=? AND idu=? ",[$fechaInicio,$fechaFin,$idu]);
      
}
$cuanto = count($prospectos);
  
  return view('reporteProspectosActAbajo')
  ->with('fechaInicio',$fechaInicio)
  ->with('fechaFin',$fechaFin)
  ->with('prospectos',$prospectos)
  ->with('cuanto',$cuanto);
  // return $prospectos;
}

////ANEXGRID DE PROSPECTOS ////

public function filtroProspectosAct(){
      $sname = Session::get('sesionname');
      $sidu = Session::get('sesionidu');
      $stipo = Session::get('sesiontipo');
      $anexGrid = new anexGrid();

      $cliente = '';
      $sucursal = '';
      $registradoPor = '';
      foreach($anexGrid->filtros as $f)
              {
                if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
                  $cliente = $f['valor'];
                }
                if($f['columna'] == 'sucursal' && $f['valor'] != ''){
                  $sucursal = $f['valor'];
                }
                if($f['columna'] == 'registradoPor' && $f['valor'] != ''){
                  $registradoPor = $f['valor'];
                }
              }
      $order;
      $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

      if($stipo == 'Administrador'){

        $registros = DB::table('invprospectos')            
                      ->select('idInvProspectos', 'fechaAlta','descripcionActividad','razonSocial','sucursales.sucursal','registradoPor','invprospectos.activo',
                      DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'))
                      ->leftJoin('clientes', 'invprospectos.idc', '=', 'clientes.idc')  
                      ->leftJoin('sucursales', 'invprospectos.idSucursal', '=', 'sucursales.idSucursal')  
                      ->where('razonSocial', 'like', '%'.$cliente.'%')
                      ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                      ->where('registradoPor', 'like', '%'.$registradoPor.'%')
                      ->where(function($query) {
                        $anexGrid = new anexGrid();
                        $descripcionActividad='';
                        foreach($anexGrid->filtros as $f)
                          {
                            if($f['columna'] == 'descripcionActividad' && $f['valor'] != ''){
                              $descripcionActividad = $f['valor'];
                            }  
                          }
                        $query->wherenull('descripcionActividad')
                        ->orwhere('descripcionActividad', 'like', '%'.$descripcionActividad.'%');
                      })
                      ->groupBy('idInvProspectos')
                      ->orderBy($anexGrid->columna, $order)
                      ->skip($anexGrid->pagina)
                      ->take($anexGrid->limite)
                    ->get();
        $total = DB::table('invprospectos')            
                    ->select('idInvProspectos', 'fechaAlta','descripcionActividad','razonSocial','sucursales.sucursal','registradoPor','invprospectos.activo',
                    DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'))
                    ->leftJoin('clientes', 'invprospectos.idc', '=', 'clientes.idc')  
                    ->leftJoin('sucursales', 'invprospectos.idSucursal', '=', 'sucursales.idSucursal')  
                    ->where('razonSocial', 'like', '%'.$cliente.'%')
                    ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                    ->where(function($query) {
                      $anexGrid = new anexGrid();
                      $descripcionActividad='';
                      foreach($anexGrid->filtros as $f)
                        {
                          if($f['columna'] == 'descripcionActividad' && $f['valor'] != ''){
                            $descripcionActividad = $f['valor'];
                          }  
                        }
                      $query->wherenull('descripcionActividad')
                      ->orwhere('descripcionActividad', 'like', '%'.$descripcionActividad.'%');
                    })
                    ->count();
      }else{
        $registros = DB::table('invprospectos')            
              ->select('idInvProspectos', 'fechaAlta','descripcionActividad','razonSocial','sucursales.sucursal','registradoPor','invprospectos.activo',
              DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'))
              ->leftJoin('clientes', 'invprospectos.idc', '=', 'clientes.idc')  
              ->leftJoin('sucursales', 'invprospectos.idSucursal', '=', 'sucursales.idSucursal')  
              ->where('razonSocial', 'like', '%'.$cliente.'%')
              ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
              ->where('registradoPor', 'like', '%'.$registradoPor.'%')
              ->whereIn('idInvProspectos',  function($query){
                $sidu = Session::get('sesionidu');
                $query->select('idInvProspectos')
                ->from('invprospectos')
                ->where('idu','=',$sidu);
              })
              ->where(function($query) {
                $anexGrid = new anexGrid();
                $descripcionActividad='';
                foreach($anexGrid->filtros as $f)
                  {
                    if($f['columna'] == 'descripcionActividad' && $f['valor'] != ''){
                      $descripcionActividad = $f['valor'];
                    }  
                  }
                $query->wherenull('descripcionActividad')
                ->orwhere('descripcionActividad', 'like', '%'.$descripcionActividad.'%');
              })
              ->groupBy('idInvProspectos')
              ->orderBy($anexGrid->columna, $order)
              ->skip($anexGrid->pagina)
              ->take($anexGrid->limite)
              ->get();
        $total = DB::table('invprospectos')            
              ->select('idInvProspectos', 'fechaAlta','descripcionActividad','razonSocial','sucursales.sucursal','registradoPor','invprospectos.activo',
              DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'))
              ->leftJoin('clientes', 'invprospectos.idc', '=', 'clientes.idc')  
              ->leftJoin('sucursales', 'invprospectos.idSucursal', '=', 'sucursales.idSucursal')  
              ->where('razonSocial', 'like', '%'.$cliente.'%')
              ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
              ->whereIn('idInvProspectos',  function($query){
                $sidu = Session::get('sesionidu');
                $query->select('idInvProspectos')
                ->from('invprospectos')
                ->where('idu','=',$sidu);
              })
              ->where(function($query) {
                $anexGrid = new anexGrid();
                $descripcionActividad='';
                foreach($anexGrid->filtros as $f)
                  {
                    if($f['columna'] == 'descripcionActividad' && $f['valor'] != ''){
                      $descripcionActividad = $f['valor'];
                    }  
                  }
                $query->wherenull('descripcionActividad')
                ->orwhere('descripcionActividad', 'like', '%'.$descripcionActividad.'%');
              })
              ->count();
      }

      $data = array(
        'total' => $total,
        'data' => $registros
      );

      return response()->json($data, 200);
}
public function filtroProspectosActAbajo(Request $request){
      $fechaInicio=$request->fechaInicio;
      $fechaFin=$request->fechaFin;
      $sname = Session::get('sesionname');
      $sidu = Session::get('sesionidu');
      $stipo = Session::get('sesiontipo');
      $anexGrid = new anexGrid();

      $cliente = '';
      $sucursal = '';
      $registradoPor = '';
      foreach($anexGrid->filtros as $f)
              {
                if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
                  $cliente = $f['valor'];
                }
                if($f['columna'] == 'sucursal' && $f['valor'] != ''){
                  $sucursal = $f['valor'];
                }
                if($f['columna'] == 'registradoPor' && $f['valor'] != ''){
                  $registradoPor = $f['valor'];
                }
              }
      $order;
      $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

      if($stipo == 'Administrador'){

        $registros = DB::table('invprospectos')            
                      ->select('idInvProspectos', 'fechaAlta','descripcionActividad','razonSocial','sucursales.sucursal','registradoPor','invprospectos.activo',
                      DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'))
                      ->leftJoin('clientes', 'invprospectos.idc', '=', 'clientes.idc')  
                      ->leftJoin('sucursales', 'invprospectos.idSucursal', '=', 'sucursales.idSucursal')  
                      ->where('razonSocial', 'like', '%'.$cliente.'%')
                      ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                      ->where('registradoPor', 'like', '%'.$registradoPor.'%')
                      ->where('fechaAlta','>=',$fechaInicio)                     
                      ->where('fechaAlta','<=',$fechaFin)    
                      ->where(function($query) {
                        $anexGrid = new anexGrid();
                        $descripcionActividad='';
                        foreach($anexGrid->filtros as $f)
                          {
                            if($f['columna'] == 'descripcionActividad' && $f['valor'] != ''){
                              $descripcionActividad = $f['valor'];
                            }  
                          }
                        $query->wherenull('descripcionActividad')
                        ->orwhere('descripcionActividad', 'like', '%'.$descripcionActividad.'%');
                      })
                      ->groupBy('idInvProspectos')
                      ->orderBy($anexGrid->columna, $order)
                      ->skip($anexGrid->pagina)
                      ->take($anexGrid->limite)
                    ->get();
        $total = DB::table('invprospectos')            
                    ->select('idInvProspectos', 'fechaAlta','descripcionActividad','razonSocial','sucursales.sucursal','registradoPor','invprospectos.activo',
                    DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'))
                    ->leftJoin('clientes', 'invprospectos.idc', '=', 'clientes.idc')  
                    ->leftJoin('sucursales', 'invprospectos.idSucursal', '=', 'sucursales.idSucursal')  
                    ->where('razonSocial', 'like', '%'.$cliente.'%')
                    ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                    ->where('fechaAlta','>=',$fechaInicio)                     
                    ->where('fechaAlta','<=',$fechaFin)    
                    ->where(function($query) {
                      $anexGrid = new anexGrid();
                      $descripcionActividad='';
                      foreach($anexGrid->filtros as $f)
                        {
                          if($f['columna'] == 'descripcionActividad' && $f['valor'] != ''){
                            $descripcionActividad = $f['valor'];
                          }  
                        }
                      $query->wherenull('descripcionActividad')
                      ->orwhere('descripcionActividad', 'like', '%'.$descripcionActividad.'%');
                    })
                    ->count();
      }else{
        $registros = DB::table('invprospectos')            
              ->select('idInvProspectos', 'fechaAlta','descripcionActividad','razonSocial','sucursales.sucursal','registradoPor','invprospectos.activo',
              DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'))
              ->leftJoin('clientes', 'invprospectos.idc', '=', 'clientes.idc')  
              ->leftJoin('sucursales', 'invprospectos.idSucursal', '=', 'sucursales.idSucursal')  
              ->where('razonSocial', 'like', '%'.$cliente.'%')
              ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
              ->where('registradoPor', 'like', '%'.$registradoPor.'%')
              ->where('fechaAlta','>=',$fechaInicio)                     
              ->where('fechaAlta','<=',$fechaFin)    
              ->whereIn('idInvProspectos',  function($query){
                $sidu = Session::get('sesionidu');
                $query->select('idInvProspectos')
                ->from('invprospectos')
                ->where('idu','=',$sidu);
              })
              ->where(function($query) {
                $anexGrid = new anexGrid();
                $descripcionActividad='';
                foreach($anexGrid->filtros as $f)
                  {
                    if($f['columna'] == 'descripcionActividad' && $f['valor'] != ''){
                      $descripcionActividad = $f['valor'];
                    }  
                  }
                $query->wherenull('descripcionActividad')
                ->orwhere('descripcionActividad', 'like', '%'.$descripcionActividad.'%');
              })
              ->groupBy('idInvProspectos')
              ->orderBy($anexGrid->columna, $order)
              ->skip($anexGrid->pagina)
              ->take($anexGrid->limite)
              ->get();
        $total = DB::table('invprospectos')            
              ->select('idInvProspectos', 'fechaAlta','descripcionActividad','razonSocial','sucursales.sucursal','registradoPor','invprospectos.activo',
              DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'))
              ->leftJoin('clientes', 'invprospectos.idc', '=', 'clientes.idc')  
              ->leftJoin('sucursales', 'invprospectos.idSucursal', '=', 'sucursales.idSucursal')  
              ->where('razonSocial', 'like', '%'.$cliente.'%')
              ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
              ->where('fechaAlta','>=',$fechaInicio)                     
              ->where('fechaAlta','<=',$fechaFin)    
              ->whereIn('idInvProspectos',  function($query){
                $sidu = Session::get('sesionidu');
                $query->select('idInvProspectos')
                ->from('invprospectos')
                ->where('idu','=',$sidu);
              })
              ->where(function($query) {
                $anexGrid = new anexGrid();
                $descripcionActividad='';
                foreach($anexGrid->filtros as $f)
                  {
                    if($f['columna'] == 'descripcionActividad' && $f['valor'] != ''){
                      $descripcionActividad = $f['valor'];
                    }  
                  }
                $query->wherenull('descripcionActividad')
                ->orwhere('descripcionActividad', 'like', '%'.$descripcionActividad.'%');
              })
              ->count();
      }

      $data = array(
        'total' => $total,
        'data' => $registros
      );

      return response()->json($data, 200);
}


public function reportePublicidadAct(){
  $sidu = Session::get('sesionidu');
  $stipo = Session::get('sesiontipo');
    if($stipo == 'Administrador'){
      $usuario = usuarios::orderby('nombreUsuario','asc')->get();
      $publicidad =\DB::select("SELECT p.idServPublicidad, DATE_FORMAT(p.fechaAlta,'%d %b %Y') AS fecha, cli.razonSocial, s.sucursal,u.nombreUsuario,p.contacto, p.descripcionActividad,p.contesto,p.estatus,p.activo
      FROM serviciosPublicidad AS p 
      INNER JOIN clientes AS cli ON cli.idc = p.idc
      INNER JOIN sucursales AS s ON s.idSucursal = p.idSucursal
      INNER JOIN usuarios AS u ON u.idu = p.idu");
    }else{
      $usuario = usuarios::orderby('nombreUsuario','asc')->get();
      $publicidad =\DB::select("SELECT p.idServPublicidad, DATE_FORMAT(p.fechaAlta,'%d %b %Y') AS fecha, cli.razonSocial, s.sucursal,u.nombreUsuario,p.contacto, p.descripcionActividad,p.contesto,p.estatus,p.activo
      FROM serviciosPublicidad AS p 
      INNER JOIN clientes AS cli ON cli.idc = p.idc
      INNER JOIN sucursales AS s ON s.idSucursal = p.idSucursal
      INNER JOIN usuarios AS u ON u.idu = p.idu
      WHERE p.idu = ?",[$sidu]);
    }

  return view ('reportePublicidadAct')
  ->with('publicidad',$publicidad)
  ->with('stipo',$stipo)
  ->with('usuario',$usuario);
}

public function reportePublicidadActAbajo(Request $request){
  $fechaInicio=$request->fechaInicio;
  $fechaFin=$request->fechaFin;
  $idu=$request->idu;
    if($idu == ''){
      $publicidad =\DB::select("SELECT p.idServPublicidad,DATE_FORMAT(p.fechaAlta,'%d %b %Y') AS fecha, cli.razonSocial, s.sucursal,u.idu,u.nombreUsuario,p.contacto, p.descripcionActividad,p.contesto,p.estatus,p.activo
                                FROM serviciosPublicidad AS p 
                                INNER JOIN clientes AS cli ON cli.idc = p.idc
                                INNER JOIN sucursales AS s ON s.idSucursal = p.idSucursal
                                INNER JOIN usuarios AS u ON u.idu = p.idu
                                WHERE fechaAlta>=? AND fechaAlta<=?",[$fechaInicio,$fechaFin]);
    }else{
      $publicidad =\DB::select("SELECT p.idServPublicidad,DATE_FORMAT(p.fechaAlta,'%d %b %Y') AS fecha, cli.razonSocial, s.sucursal,u.idu,u.nombreUsuario,p.contacto, p.descripcionActividad,p.contesto,p.estatus,p.activo
                                FROM serviciosPublicidad AS p 
                                INNER JOIN clientes AS cli ON cli.idc = p.idc
                                INNER JOIN sucursales AS s ON s.idSucursal = p.idSucursal
                                INNER JOIN usuarios AS u ON u.idu = p.idu
                                WHERE fechaAlta>=? AND fechaAlta<=? AND u.idu=?",[$fechaInicio,$fechaFin,$idu]);
    }
$cuantos = count ($publicidad);
   
return view ('reportePublicidadActAbajo')
->with('fechaInicio',$fechaInicio)
->with('fechaFin',$fechaFin)
->with('publicidad',$publicidad)
->with('cuantos',$cuantos);

}



////ANEXGRID DE PUBLICIDAD ////
public function filtroPublicidadAct(){
  $sname = Session::get('sesionname');
  $sidu = Session::get('sesionidu');
  $stipo = Session::get('sesiontipo');
  $anexGrid = new anexGrid();

  $cliente = '';
  $sucursal = '';
  $nombreUsuario = '';
  $contacto = '';
  $estatus = '';
  foreach($anexGrid->filtros as $f)
          {
            if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
              $cliente = $f['valor'];
            }
            if($f['columna'] == 'sucursal' && $f['valor'] != ''){
              $sucursal = $f['valor'];
            }
            if($f['columna'] == 'nombreUsuario' && $f['valor'] != ''){
              $nombreUsuario = $f['valor'];
            }
            if($f['columna'] == 'contacto' && $f['valor'] != ''){
              $contacto = $f['valor'];
            }
            if($f['columna'] == 'estatus' && $f['valor'] != ''){
              $estatus = $f['valor'];
            }
          }
  $order;
  $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

  if($stipo == 'Administrador'){
      $registros = DB::table('serviciosPublicidad')            
                    ->select('idServPublicidad', 'fechaAlta','descripcionActividad','razonSocial','sucursales.sucursal','serviciosPublicidad.activo',
                    'nombreUsuario', 'serviciosPublicidad.contacto','contesto','serviciosPublicidad.estatus','tipoPublicidad',
                    DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'))
                    ->leftJoin('clientes', 'serviciosPublicidad.idc', '=', 'clientes.idc')  
                    ->leftJoin('sucursales', 'serviciosPublicidad.idSucursal', '=', 'sucursales.idSucursal')  
                    ->leftJoin('usuarios', 'serviciosPublicidad.idu', '=', 'usuarios.idu')  
                    ->where('razonSocial', 'like', '%'.$cliente.'%')
                    ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                    ->where('nombreUsuario', 'like', '%'.$nombreUsuario.'%')
                    ->where('serviciosPublicidad.contacto', 'like', '%'.$contacto.'%')
                    ->where('estatus', 'like', '%'.$estatus.'%')
                    ->where(function($query) {
                      $anexGrid = new anexGrid();
                      $contesto='';
                      foreach($anexGrid->filtros as $f)
                        {
                          if($f['columna'] == 'contesto' && $f['valor'] != ''){
                            $contesto = $f['valor'];
                          }  
                        }
                      $query->wherenull('contesto')
                      ->orwhere('contesto', 'like', '%'.$contesto.'%');
                    })
                    ->where(function($query) {
                      $anexGrid = new anexGrid();
                      $descripcionActividad='';
                      foreach($anexGrid->filtros as $f)
                        {
                          if($f['columna'] == 'descripcionActividad' && $f['valor'] != ''){
                            $descripcionActividad = $f['valor'];
                          }  
                        }
                      $query->wherenull('descripcionActividad')
                      ->orwhere('descripcionActividad', 'like', '%'.$descripcionActividad.'%');
                    })
                    ->groupBy('idServPublicidad')
                    ->orderBy($anexGrid->columna, $order)
                    ->skip($anexGrid->pagina)
                    ->take($anexGrid->limite)
                  ->get();
      $total = DB::table('serviciosPublicidad')            
                  ->select('idServPublicidad', 'fechaAlta','descripcionActividad','razonSocial','sucursales.sucursal','serviciosPublicidad.activo',
                  'nombreUsuario','serviciosPublicidad.contacto','contesto','serviciosPublicidad.estatus','tipoPublicidad',
                  DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'))
                  ->leftJoin('clientes', 'serviciosPublicidad.idc', '=', 'clientes.idc')  
                  ->leftJoin('sucursales', 'serviciosPublicidad.idSucursal', '=', 'sucursales.idSucursal')  
                  ->leftJoin('usuarios', 'serviciosPublicidad.idu', '=', 'usuarios.idu')  
                  ->where('razonSocial', 'like', '%'.$cliente.'%')
                  ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                  ->where('nombreUsuario', 'like', '%'.$nombreUsuario.'%')
                  ->where('serviciosPublicidad.contacto', 'like', '%'.$contacto.'%')
                  ->where('estatus', 'like', '%'.$estatus.'%')
                  ->where(function($query) {
                    $anexGrid = new anexGrid();
                    $contesto='';
                    foreach($anexGrid->filtros as $f)
                      {
                        if($f['columna'] == 'contesto' && $f['valor'] != ''){
                          $contesto = $f['valor'];
                        }  
                      }
                    $query->wherenull('contesto')
                    ->orwhere('contesto', 'like', '%'.$contesto.'%');
                  })
                  ->where(function($query) {
                    $anexGrid = new anexGrid();
                    $descripcionActividad='';
                    foreach($anexGrid->filtros as $f)
                      {
                        if($f['columna'] == 'descripcionActividad' && $f['valor'] != ''){
                          $descripcionActividad = $f['valor'];
                        }  
                      }
                    $query->wherenull('descripcionActividad')
                    ->orwhere('descripcionActividad', 'like', '%'.$descripcionActividad.'%');
                  })
                  ->count();
  }else{
      $registros = DB::table('serviciosPublicidad')            
                  ->select('idServPublicidad', 'fechaAlta','descripcionActividad','razonSocial','sucursales.sucursal','serviciosPublicidad.activo',
                  'nombreUsuario', 'serviciosPublicidad.contacto','contesto','serviciosPublicidad.estatus','tipoPublicidad',
                  DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'))
                  ->leftJoin('clientes', 'serviciosPublicidad.idc', '=', 'clientes.idc')  
                  ->leftJoin('sucursales', 'serviciosPublicidad.idSucursal', '=', 'sucursales.idSucursal')  
                  ->leftJoin('usuarios', 'serviciosPublicidad.idu', '=', 'usuarios.idu')  
                  ->where('razonSocial', 'like', '%'.$cliente.'%')
                  ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                  ->where('nombreUsuario', 'like', '%'.$nombreUsuario.'%')
                  ->where('serviciosPublicidad.contacto', 'like', '%'.$contacto.'%')
                  ->where('estatus', 'like', '%'.$estatus.'%')
                  ->where(function($query) {
                    $anexGrid = new anexGrid();
                    $contesto='';
                    foreach($anexGrid->filtros as $f)
                      {
                        if($f['columna'] == 'contesto' && $f['valor'] != ''){
                          $contesto = $f['valor'];
                        }  
                      }
                    $query->wherenull('contesto')
                    ->orwhere('contesto', 'like', '%'.$contesto.'%');
                  })
                  ->where(function($query) {
                    $anexGrid = new anexGrid();
                    $descripcionActividad='';
                    foreach($anexGrid->filtros as $f)
                      {
                        if($f['columna'] == 'descripcionActividad' && $f['valor'] != ''){
                          $descripcionActividad = $f['valor'];
                        }  
                      }
                    $query->wherenull('descripcionActividad')
                    ->orwhere('descripcionActividad', 'like', '%'.$descripcionActividad.'%');
                  })
                  ->whereIn('idServPublicidad',  function($query){
                    $sidu = Session::get('sesionidu');
                    $query->select('idServPublicidad')
                    ->from('serviciosPublicidad')
                    ->where('idu','=',$sidu);
                  })
                  ->groupBy('idServPublicidad')
                  ->orderBy($anexGrid->columna, $order)
                  ->skip($anexGrid->pagina)
                  ->take($anexGrid->limite)
                ->get();
      $total = DB::table('serviciosPublicidad')            
                  ->select('idServPublicidad', 'fechaAlta','descripcionActividad','razonSocial','sucursales.sucursal','serviciosPublicidad.activo',
                  'nombreUsuario','serviciosPublicidad.contacto','contesto','serviciosPublicidad.estatus','tipoPublicidad',
                  DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'))
                  ->leftJoin('clientes', 'serviciosPublicidad.idc', '=', 'clientes.idc')  
                  ->leftJoin('sucursales', 'serviciosPublicidad.idSucursal', '=', 'sucursales.idSucursal')  
                  ->leftJoin('usuarios', 'serviciosPublicidad.idu', '=', 'usuarios.idu')  
                  ->where('razonSocial', 'like', '%'.$cliente.'%')
                  ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                  ->where('nombreUsuario', 'like', '%'.$nombreUsuario.'%')
                  ->where('serviciosPublicidad.contacto', 'like', '%'.$contacto.'%')
                  ->where('estatus', 'like', '%'.$estatus.'%')
                  ->where(function($query) {
                    $anexGrid = new anexGrid();
                    $contesto='';
                    foreach($anexGrid->filtros as $f)
                      {
                        if($f['columna'] == 'contesto' && $f['valor'] != ''){
                          $contesto = $f['valor'];
                        }  
                      }
                    $query->wherenull('contesto')
                    ->orwhere('contesto', 'like', '%'.$contesto.'%');
                  })
                  ->where(function($query) {
                    $anexGrid = new anexGrid();
                    $descripcionActividad='';
                    foreach($anexGrid->filtros as $f)
                      {
                        if($f['columna'] == 'descripcionActividad' && $f['valor'] != ''){
                          $descripcionActividad = $f['valor'];
                        }  
                      }
                    $query->wherenull('descripcionActividad')
                    ->orwhere('descripcionActividad', 'like', '%'.$descripcionActividad.'%');
                  })
                  ->whereIn('idServPublicidad',  function($query){
                    $sidu = Session::get('sesionidu');
                    $query->select('idServPublicidad')
                    ->from('serviciosPublicidad')
                    ->where('idu','=',$sidu);
                  })
                  ->count();
  }
  $data = array(
    'total' => $total,
    'data' => $registros
  );

  return response()->json($data, 200);
}

public function filtroPublicidadActAbajo(Request $request){
  $fechaInicio=$request->fechaInicio;
  $fechaFin=$request->fechaFin;
  $sname = Session::get('sesionname');
  $sidu = Session::get('sesionidu');
  $stipo = Session::get('sesiontipo');
  $anexGrid = new anexGrid();

  $cliente = '';
  $sucursal = '';
  $nombreUsuario = '';
  $contacto = '';
  $estatus = '';
  foreach($anexGrid->filtros as $f)
          {
            if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
              $cliente = $f['valor'];
            }
            if($f['columna'] == 'sucursal' && $f['valor'] != ''){
              $sucursal = $f['valor'];
            }
            if($f['columna'] == 'nombreUsuario' && $f['valor'] != ''){
              $nombreUsuario = $f['valor'];
            }
            if($f['columna'] == 'contacto' && $f['valor'] != ''){
              $contacto = $f['valor'];
            }
            if($f['columna'] == 'estatus' && $f['valor'] != ''){
              $estatus = $f['valor'];
            }
          }
  $order;
  $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

  if($stipo == 'Administrador'){
      $registros = DB::table('serviciosPublicidad')            
                    ->select('idServPublicidad', 'fechaAlta','descripcionActividad','razonSocial','sucursales.sucursal','serviciosPublicidad.activo',
                    'nombreUsuario', 'serviciosPublicidad.contacto','contesto','serviciosPublicidad.estatus','tipoPublicidad',
                    DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'))
                    ->leftJoin('clientes', 'serviciosPublicidad.idc', '=', 'clientes.idc')  
                    ->leftJoin('sucursales', 'serviciosPublicidad.idSucursal', '=', 'sucursales.idSucursal')  
                    ->leftJoin('usuarios', 'serviciosPublicidad.idu', '=', 'usuarios.idu')  
                    ->where('razonSocial', 'like', '%'.$cliente.'%')
                    ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                    ->where('nombreUsuario', 'like', '%'.$nombreUsuario.'%')
                    ->where('serviciosPublicidad.contacto', 'like', '%'.$contacto.'%')
                    ->where('estatus', 'like', '%'.$estatus.'%')
                    ->where('fechaAlta','>=',$fechaInicio)                     
                    ->where('fechaAlta','<=',$fechaFin)    
                    ->where(function($query) {
                      $anexGrid = new anexGrid();
                      $contesto='';
                      foreach($anexGrid->filtros as $f)
                        {
                          if($f['columna'] == 'contesto' && $f['valor'] != ''){
                            $contesto = $f['valor'];
                          }  
                        }
                      $query->wherenull('contesto')
                      ->orwhere('contesto', 'like', '%'.$contesto.'%');
                    })
                    ->where(function($query) {
                      $anexGrid = new anexGrid();
                      $descripcionActividad='';
                      foreach($anexGrid->filtros as $f)
                        {
                          if($f['columna'] == 'descripcionActividad' && $f['valor'] != ''){
                            $descripcionActividad = $f['valor'];
                          }  
                        }
                      $query->wherenull('descripcionActividad')
                      ->orwhere('descripcionActividad', 'like', '%'.$descripcionActividad.'%');
                    })
                    ->groupBy('idServPublicidad')
                    ->orderBy($anexGrid->columna, $order)
                    ->skip($anexGrid->pagina)
                    ->take($anexGrid->limite)
                  ->get();
      $total = DB::table('serviciosPublicidad')            
                  ->select('idServPublicidad', 'fechaAlta','descripcionActividad','razonSocial','sucursales.sucursal','serviciosPublicidad.activo',
                  'nombreUsuario','serviciosPublicidad.contacto','contesto','serviciosPublicidad.estatus','tipoPublicidad',
                  DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'))
                  ->leftJoin('clientes', 'serviciosPublicidad.idc', '=', 'clientes.idc')  
                  ->leftJoin('sucursales', 'serviciosPublicidad.idSucursal', '=', 'sucursales.idSucursal')  
                  ->leftJoin('usuarios', 'serviciosPublicidad.idu', '=', 'usuarios.idu')  
                  ->where('razonSocial', 'like', '%'.$cliente.'%')
                  ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                  ->where('nombreUsuario', 'like', '%'.$nombreUsuario.'%')
                  ->where('serviciosPublicidad.contacto', 'like', '%'.$contacto.'%')
                  ->where('estatus', 'like', '%'.$estatus.'%')
                  ->where('fechaAlta','>=',$fechaInicio)                     
                  ->where('fechaAlta','<=',$fechaFin)    
                  ->where(function($query) {
                    $anexGrid = new anexGrid();
                    $contesto='';
                    foreach($anexGrid->filtros as $f)
                      {
                        if($f['columna'] == 'contesto' && $f['valor'] != ''){
                          $contesto = $f['valor'];
                        }  
                      }
                    $query->wherenull('contesto')
                    ->orwhere('contesto', 'like', '%'.$contesto.'%');
                  })
                  ->where(function($query) {
                    $anexGrid = new anexGrid();
                    $descripcionActividad='';
                    foreach($anexGrid->filtros as $f)
                      {
                        if($f['columna'] == 'descripcionActividad' && $f['valor'] != ''){
                          $descripcionActividad = $f['valor'];
                        }  
                      }
                    $query->wherenull('descripcionActividad')
                    ->orwhere('descripcionActividad', 'like', '%'.$descripcionActividad.'%');
                  })
                  ->count();
  }else{
      $registros = DB::table('serviciosPublicidad')            
                  ->select('idServPublicidad', 'fechaAlta','descripcionActividad','razonSocial','sucursales.sucursal','serviciosPublicidad.activo',
                  'nombreUsuario', 'serviciosPublicidad.contacto','contesto','serviciosPublicidad.estatus','tipoPublicidad',
                  DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'))
                  ->leftJoin('clientes', 'serviciosPublicidad.idc', '=', 'clientes.idc')  
                  ->leftJoin('sucursales', 'serviciosPublicidad.idSucursal', '=', 'sucursales.idSucursal')  
                  ->leftJoin('usuarios', 'serviciosPublicidad.idu', '=', 'usuarios.idu')  
                  ->where('razonSocial', 'like', '%'.$cliente.'%')
                  ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                  ->where('nombreUsuario', 'like', '%'.$nombreUsuario.'%')
                  ->where('serviciosPublicidad.contacto', 'like', '%'.$contacto.'%')
                  ->where('estatus', 'like', '%'.$estatus.'%')
                  ->where('fechaAlta','>=',$fechaInicio)                     
                  ->where('fechaAlta','<=',$fechaFin)   
                  ->where(function($query) {
                    $anexGrid = new anexGrid();
                    $contesto='';
                    foreach($anexGrid->filtros as $f)
                      {
                        if($f['columna'] == 'contesto' && $f['valor'] != ''){
                          $contesto = $f['valor'];
                        }  
                      }
                    $query->wherenull('contesto')
                    ->orwhere('contesto', 'like', '%'.$contesto.'%');
                  })
                  ->where(function($query) {
                    $anexGrid = new anexGrid();
                    $descripcionActividad='';
                    foreach($anexGrid->filtros as $f)
                      {
                        if($f['columna'] == 'descripcionActividad' && $f['valor'] != ''){
                          $descripcionActividad = $f['valor'];
                        }  
                      }
                    $query->wherenull('descripcionActividad')
                    ->orwhere('descripcionActividad', 'like', '%'.$descripcionActividad.'%');
                  }) 
                  ->whereIn('idServPublicidad',  function($query){
                    $sidu = Session::get('sesionidu');
                    $query->select('idServPublicidad')
                    ->from('serviciosPublicidad')
                    ->where('idu','=',$sidu);
                  })
                  ->groupBy('idServPublicidad')
                  ->orderBy($anexGrid->columna, $order)
                  ->skip($anexGrid->pagina)
                  ->take($anexGrid->limite)
                ->get();
      $total = DB::table('serviciosPublicidad')            
                  ->select('idServPublicidad', 'fechaAlta','descripcionActividad','razonSocial','sucursales.sucursal','serviciosPublicidad.activo',
                  'nombreUsuario','serviciosPublicidad.contacto','contesto','serviciosPublicidad.estatus','tipoPublicidad',
                  DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'))
                  ->leftJoin('clientes', 'serviciosPublicidad.idc', '=', 'clientes.idc')  
                  ->leftJoin('sucursales', 'serviciosPublicidad.idSucursal', '=', 'sucursales.idSucursal')  
                  ->leftJoin('usuarios', 'serviciosPublicidad.idu', '=', 'usuarios.idu')  
                  ->where('razonSocial', 'like', '%'.$cliente.'%')
                  ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                  ->where('nombreUsuario', 'like', '%'.$nombreUsuario.'%')
                  ->where('serviciosPublicidad.contacto', 'like', '%'.$contacto.'%')
                  ->where('estatus', 'like', '%'.$estatus.'%')
                  ->where('fechaAlta','>=',$fechaInicio)                     
                  ->where('fechaAlta','<=',$fechaFin)    
                  ->where(function($query) {
                    $anexGrid = new anexGrid();
                    $contesto='';
                    foreach($anexGrid->filtros as $f)
                      {
                        if($f['columna'] == 'contesto' && $f['valor'] != ''){
                          $contesto = $f['valor'];
                        }  
                      }
                    $query->wherenull('contesto')
                    ->orwhere('contesto', 'like', '%'.$contesto.'%');
                  })
                  ->where(function($query) {
                    $anexGrid = new anexGrid();
                    $descripcionActividad='';
                    foreach($anexGrid->filtros as $f)
                      {
                        if($f['columna'] == 'descripcionActividad' && $f['valor'] != ''){
                          $descripcionActividad = $f['valor'];
                        }  
                      }
                    $query->wherenull('descripcionActividad')
                    ->orwhere('descripcionActividad', 'like', '%'.$descripcionActividad.'%');
                  })
                  ->whereIn('idServPublicidad',  function($query){
                    $sidu = Session::get('sesionidu');
                    $query->select('idServPublicidad')
                    ->from('serviciosPublicidad')
                    ->where('idu','=',$sidu);
                  })
                  ->count();
  }
  $data = array(
    'total' => $total,
    'data' => $registros
  );

  return response()->json($data, 200);
}

public function reporteCitasAsignadas(Request $request){

  $sname = Session::get('sesionname');
  $sidu = Session::get('sesionidu');
  $stipo = Session::get('sesiontipo');
  $usuario = usuarios::orderby('nombreUsuario','asc')->get();

  if($sname == '' or $sidu =='' or $stipo=='')
  {
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
  }
  else
  {
    if($stipo == 'Administrador'){
      $citasAsignadas = \DB::select("SELECT c.idCita, c.fechaAlta, DATE_FORMAT(c.fechaAlta,'%d %b %Y') AS fecha,c.empresaSeguimiento, cli.razonSocial,s.sucursal, 
            c.registradoPor, c.idu,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS us,
            c.observacionCita, c.responsable, c.resultados, c.estatus, c.activo
            FROM citas AS c 
            INNER JOIN sucursales AS s ON s.idSucursal=c.idSucursal
            INNER JOIN usuarios AS u ON u.idu=c.responsable
            INNER JOIN clientes AS cli ON cli.idc=c.idc");
    }else{
      $citasAsignadas = \DB::select("SELECT c.idCita, c.fechaAlta, DATE_FORMAT(c.fechaAlta,'%d %b %Y') AS fecha,c.empresaSeguimiento, cli.razonSocial,s.sucursal, 
            c.registradoPor, c.idu,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS us,
            c.observacionCita, c.responsable, c.resultados, c.estatus, c.activo
            FROM citas AS c 
            INNER JOIN sucursales AS s ON s.idSucursal=c.idSucursal
            INNER JOIN usuarios AS u ON u.idu=c.responsable
            INNER JOIN clientes AS cli ON cli.idc=c.idc 
            WHERE c.responsable=?", [$sidu]);
    }

    //  $citasAsignadas= \DB::select("SELECT DATE_FORMAT(c.fechaAlta,'%d %b %Y') AS fecha, cli.razonSocial, s.sucursal, c.registradoPor,c.responsable, c.observacionCita, 
    //                                                   c.resultados, c.estatus, c.activo,c.idCita,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS us
    //                           FROM citas AS c
    //                           INNER JOIN clientes AS cli ON cli.idc = c.idc
    //                           INNER JOIN sucursales AS s ON s.idSucursal = c.idSucursal
    //                           INNER JOIN usuarios AS u ON u.idu=c.responsable
    //                           WHERE responsable = ? AND WEEKOFYEAR(fechaAlta)=WEEKOFYEAR(NOW())",[$sidu]);
    $cuantasAsignadas = count($citasAsignadas);

    return view('reporteCitasAsignadas')
    ->with('sname',$sname)
    ->with('sidu',$sidu)
    ->with('stipo',$stipo)
    ->with('usuario',$usuario)
    ->with('citasAsignadas',$citasAsignadas)
    ->with('cuantasAsignadas',$cuantasAsignadas);
}
}

public function reporteCitasAsignadasAbajo(Request $request){
  $sname = Session::get('sesionname');
  $sidu = Session::get('sesionidu');
  $stipo = Session::get('sesiontipo');

  if($sname == '' or $sidu =='' or $stipo=='')
  {
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
  }
  else{

  $fechaInicio=$request->fechaInicio;
  $fechaFin=$request->fechaFin;
  $idu = $request->idu;
  
  if($idu == ''){
    $citasAsignadas = \DB::select("SELECT c.idCita, c.fechaAlta, DATE_FORMAT(c.fechaAlta,'%d %b %Y') AS fecha,c.empresaSeguimiento, cli.razonSocial,s.sucursal, 
                c.registradoPor, c.idu,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS us,
                c.observacionCita, c.responsable, c.resultados, c.estatus, c.activo
                FROM citas AS c 
                INNER JOIN sucursales AS s ON s.idSucursal=c.idSucursal
                INNER JOIN usuarios AS u ON u.idu=c.responsable
                INNER JOIN clientes AS cli ON cli.idc=c.idc 
                WHERE fechaAlta>=? AND fechaAlta<=? AND c.responsable=? ", [$fechaInicio, $fechaFin,$sidu]);
  }else{
    $citasAsignadas = \DB::select("SELECT c.idCita, c.fechaAlta, DATE_FORMAT(c.fechaAlta,'%d %b %Y') AS fecha,c.empresaSeguimiento, cli.razonSocial,s.sucursal, 
                c.registradoPor, c.idu,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS us,
                c.observacionCita, c.responsable, c.resultados, c.estatus, c.activo
                FROM citas AS c 
                INNER JOIN sucursales AS s ON s.idSucursal=c.idSucursal
                INNER JOIN usuarios AS u ON u.idu=c.responsable
                INNER JOIN clientes AS cli ON cli.idc=c.idc 
                WHERE fechaAlta>=? AND fechaAlta<=? AND c.responsable=? ", [$fechaInicio, $fechaFin,$idu]);
  }
  
    // $citasAsignadas= \DB::select("SELECT DATE_FORMAT(c.fechaAlta,'%d %b %Y') AS fecha, cli.razonSocial, s.sucursal, c.registradoPor,c.responsable, c.observacionCita, 
    //                                                   c.resultados, c.estatus, c.activo,c.idCita,CONCAT(u.nombreUsuario,' ', u.aPaterno,' ', u.aMaterno) AS us
    //                           FROM citas AS c
    //                           INNER JOIN clientes AS cli ON cli.idc = c.idc
    //                           INNER JOIN sucursales AS s ON s.idSucursal = c.idSucursal
    //                           INNER JOIN usuarios AS u ON u.idu=c.responsable
    //                           WHERE fechaAlta>=? AND fechaAlta<=? AND responsable=? AND WEEKOFYEAR(fechaAlta)=WEEKOFYEAR(NOW())",[$fechaInicio,$fechaFin,$sidu]);
    
    $cuantasAsignadas = count($citasAsignadas);

  
  return view('reporteCitasAsignadasAbajo')
  ->with('fechaInicio',$fechaInicio)
  ->with('fechaFin',$fechaFin)
  ->with('citasAsignadas',$citasAsignadas)
  ->with('cuantasAsignadas',$cuantasAsignadas);

}
}

////ANEXGRID DE CITAS ASIGNADAS ////

public function filtroCitasAsignadasAct(){
  $sname = Session::get('sesionname');
  $sidu = Session::get('sesionidu');
  $stipo = Session::get('sesiontipo');
  $anexGrid = new anexGrid();

  $cliente = '';
  $sucursal = '';
  $registradoPor = '';
  $estatus = '';
  $observacionCita = '';
  foreach($anexGrid->filtros as $f)
          {
            if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
              $cliente = $f['valor'];
            }
            if($f['columna'] == 'sucursal' && $f['valor'] != ''){
              $sucursal = $f['valor'];
            }
            if($f['columna'] == 'registradoPor' && $f['valor'] != ''){
              $registradoPor = $f['valor'];
            }
            if($f['columna'] == 'estatus' && $f['valor'] != ''){
              $estatus = $f['valor'];
            }
            if($f['columna'] == 'observacionCita' && $f['valor'] != ''){
              $observacionCita = $f['valor'];
            }
          }
  $order;
  $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

  if($stipo == 'Administrador'){
        $registros = DB::table('citas')            
        ->select('idCita', 'fechaAlta','observacionCita','razonSocial','sucursales.sucursal','citas.activo','registradoPor',
        'resultados','estatus','responsable',
        DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'),
        DB::raw('CONCAT(nombreUsuario," ", aPaterno, " ", aMaterno) AS atendida'))
        ->leftJoin('clientes', 'citas.idc', '=', 'clientes.idc')  
        ->leftJoin('sucursales', 'citas.idSucursal', '=', 'sucursales.idSucursal')  
        ->leftJoin('usuarios', 'citas.responsable', '=', 'usuarios.idu')  
        ->where('razonSocial', 'like', '%'.$cliente.'%')
        ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
        ->where('registradoPor', 'like', '%'.$registradoPor.'%')
        ->where('observacionCita', 'like', '%'.$observacionCita.'%')
        // ->where('estatus', 'like', '%'.$estatus.'%')
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $atendida='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'atendida' && $f['valor'] != ''){
                $atendida = $f['valor'];
              }  
            }
          $query->where('nombreUsuario', 'like', '%'.$atendida.'%')
          ->orwhere('aPaterno', 'like', '%'.$atendida.'%')
          ->orwhere('aMaterno', 'like', '%'.$atendida.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $estatus='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'estatus' && $f['valor'] != ''){
                $estatus = $f['valor'];
              }  
            }
          $query->wherenull('estatus')
          ->orwhere('estatus', 'like', '%'.$estatus.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $resultados='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'resultados' && $f['valor'] != ''){
                $resultados = $f['valor'];
              }  
            }
          $query->wherenull('resultados')
          ->orwhere('resultados', 'like', '%'.$resultados.'%');
        })
        ->groupBy('idCita')
        ->orderBy($anexGrid->columna, $order)
        ->skip($anexGrid->pagina)
        ->take($anexGrid->limite)
      ->get();
    
          $total = DB::table('citas')            
          ->select('idCita', 'fechaAlta','observacionCita','razonSocial','sucursales.sucursal','citas.activo','registradoPor',
        'resultados','estatus','responsable',
        DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'),
        DB::raw('CONCAT(nombreUsuario," ", aPaterno, " ", aMaterno) AS atendida'))
        ->leftJoin('clientes', 'citas.idc', '=', 'clientes.idc')  
        ->leftJoin('sucursales', 'citas.idSucursal', '=', 'sucursales.idSucursal')  
        ->leftJoin('usuarios', 'citas.responsable', '=', 'usuarios.idu')  
        ->where('razonSocial', 'like', '%'.$cliente.'%')
        ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
        ->where('registradoPor', 'like', '%'.$registradoPor.'%')
        ->where('observacionCita', 'like', '%'.$observacionCita.'%')
        // ->where('estatus', 'like', '%'.$estatus.'%')
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $atendida='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'atendida' && $f['valor'] != ''){
                $atendida = $f['valor'];
              }  
            }
          $query->where('nombreUsuario', 'like', '%'.$atendida.'%')
          ->orwhere('aPaterno', 'like', '%'.$atendida.'%')
          ->orwhere('aMaterno', 'like', '%'.$atendida.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $estatus='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'estatus' && $f['valor'] != ''){
                $estatus = $f['valor'];
              }  
            }
          $query->wherenull('estatus')
          ->orwhere('estatus', 'like', '%'.$estatus.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $resultados='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'resultados' && $f['valor'] != ''){
                $resultados = $f['valor'];
              }  
            }
          $query->wherenull('resultados')
          ->orwhere('resultados', 'like', '%'.$resultados.'%');
        })
          ->count();

  }else{
    $registros = DB::table('citas')            
        ->select('idCita', 'fechaAlta','observacionCita','razonSocial','sucursales.sucursal','citas.activo','registradoPor',
        'resultados','estatus','responsable',
        DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'),
        DB::raw('CONCAT(nombreUsuario," ", aPaterno, " ", aMaterno) AS atendida'))
        ->leftJoin('clientes', 'citas.idc', '=', 'clientes.idc')  
        ->leftJoin('sucursales', 'citas.idSucursal', '=', 'sucursales.idSucursal')  
        ->leftJoin('usuarios', 'citas.responsable', '=', 'usuarios.idu')  
        ->where('razonSocial', 'like', '%'.$cliente.'%')
        ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
        ->where('registradoPor', 'like', '%'.$registradoPor.'%')
        ->where('observacionCita', 'like', '%'.$observacionCita.'%')
        // ->where('estatus', 'like', '%'.$estatus.'%')
        ->whereIn('idCita',  function($query){
          $sidu = Session::get('sesionidu');
          $query->select('idCita')
          ->from('citas')
          ->where('responsable','=',$sidu);
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $atendida='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'atendida' && $f['valor'] != ''){
                $atendida = $f['valor'];
              }  
            }
          $query->where('nombreUsuario', 'like', '%'.$atendida.'%')
          ->orwhere('aPaterno', 'like', '%'.$atendida.'%')
          ->orwhere('aMaterno', 'like', '%'.$atendida.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $estatus='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'estatus' && $f['valor'] != ''){
                $estatus = $f['valor'];
              }  
            }
          $query->wherenull('estatus')
          ->orwhere('estatus', 'like', '%'.$estatus.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $resultados='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'resultados' && $f['valor'] != ''){
                $resultados = $f['valor'];
              }  
            }
          $query->wherenull('resultados')
          ->orwhere('resultados', 'like', '%'.$resultados.'%');
        })
        ->groupBy('idCita')
        ->orderBy($anexGrid->columna, $order)
        ->skip($anexGrid->pagina)
        ->take($anexGrid->limite)
      ->get();
    
          $total = DB::table('citas')            
          ->select('idCita', 'fechaAlta','observacionCita','razonSocial','sucursales.sucursal','citas.activo','registradoPor',
        'resultados','estatus','responsable',
        DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'),
        DB::raw('CONCAT(nombreUsuario," ", aPaterno, " ", aMaterno) AS atendida'))
        ->leftJoin('clientes', 'citas.idc', '=', 'clientes.idc')  
        ->leftJoin('sucursales', 'citas.idSucursal', '=', 'sucursales.idSucursal')  
        ->leftJoin('usuarios', 'citas.responsable', '=', 'usuarios.idu')  
        ->where('razonSocial', 'like', '%'.$cliente.'%')
        ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
        ->where('registradoPor', 'like', '%'.$registradoPor.'%')
        ->where('observacionCita', 'like', '%'.$observacionCita.'%')
        // ->where('estatus', 'like', '%'.$estatus.'%')
        ->whereIn('idCita',  function($query){
          $sidu = Session::get('sesionidu');
          $query->select('idCita')
          ->from('citas')
          ->where('responsable','=',$sidu);
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $atendida='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'atendida' && $f['valor'] != ''){
                $atendida = $f['valor'];
              }  
            }
          $query->where('nombreUsuario', 'like', '%'.$atendida.'%')
          ->orwhere('aPaterno', 'like', '%'.$atendida.'%')
          ->orwhere('aMaterno', 'like', '%'.$atendida.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $estatus='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'estatus' && $f['valor'] != ''){
                $estatus = $f['valor'];
              }  
            }
          $query->wherenull('estatus')
          ->orwhere('estatus', 'like', '%'.$estatus.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $resultados='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'resultados' && $f['valor'] != ''){
                $resultados = $f['valor'];
              }  
            }
          $query->wherenull('resultados')
          ->orwhere('resultados', 'like', '%'.$resultados.'%');
        })
          ->count();


  }

  
              $data = array(
                'total' => $total,
                'data' => $registros
              );

              return response()->json($data, 200);
}


public function filtroCitasAsignadasActAbajo(Request $request){
  $fechaInicio=$request->fechaInicio;
  $fechaFin=$request->fechaFin;
  $sname = Session::get('sesionname');
  $sidu = Session::get('sesionidu');
  $stipo = Session::get('sesiontipo');
  $anexGrid = new anexGrid();

  $cliente = '';
  $sucursal = '';
  $registradoPor = '';
  $estatus = '';
  $observacionCita = '';

  foreach($anexGrid->filtros as $f)
          {
            if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
              $cliente = $f['valor'];
            }
            if($f['columna'] == 'sucursal' && $f['valor'] != ''){
              $sucursal = $f['valor'];
            }
            if($f['columna'] == 'registradoPor' && $f['valor'] != ''){
              $registradoPor = $f['valor'];
            }
            if($f['columna'] == 'estatus' && $f['valor'] != ''){
              $estatus = $f['valor'];
            }
            if($f['columna'] == 'observacionCita' && $f['valor'] != ''){
              $observacionCita = $f['valor'];
            }
          }
  $order;
  $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

  if($stipo == 'Administrador'){
          $registros = DB::table('citas')            
                ->select('idCita', 'fechaAlta','observacionCita','razonSocial','sucursales.sucursal','citas.activo','registradoPor',
                'resultados','estatus','responsable',
                DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'),
                DB::raw('CONCAT(nombreUsuario," ", aPaterno, " ", aMaterno) AS atendida'))
                ->leftJoin('clientes', 'citas.idc', '=', 'clientes.idc')  
                ->leftJoin('sucursales', 'citas.idSucursal', '=', 'sucursales.idSucursal')  
                ->leftJoin('usuarios', 'citas.responsable', '=', 'usuarios.idu')  
                ->where('razonSocial', 'like', '%'.$cliente.'%')
                ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                ->where('registradoPor', 'like', '%'.$registradoPor.'%')
                ->where('observacionCita', 'like', '%'.$observacionCita.'%')
                ->where('fechaAlta','>=',$fechaInicio)                     
                ->where('fechaAlta','<=',$fechaFin)
                // ->where('estatus', 'like', '%'.$estatus.'%')
                ->where(function($query) {
                  $anexGrid = new anexGrid();
                  $atendida='';
                  foreach($anexGrid->filtros as $f)
                    {
                      if($f['columna'] == 'atendida' && $f['valor'] != ''){
                        $atendida = $f['valor'];
                      }  
                    }
                  $query->where('nombreUsuario', 'like', '%'.$atendida.'%')
                  ->orwhere('aPaterno', 'like', '%'.$atendida.'%')
                  ->orwhere('aMaterno', 'like', '%'.$atendida.'%');
                })
                ->where(function($query) {
                  $anexGrid = new anexGrid();
                  $estatus='';
                  foreach($anexGrid->filtros as $f)
                    {
                      if($f['columna'] == 'estatus' && $f['valor'] != ''){
                        $estatus = $f['valor'];
                      }  
                    }
                  $query->wherenull('estatus')
                  ->orwhere('estatus', 'like', '%'.$estatus.'%');
                })
                ->where(function($query) {
                  $anexGrid = new anexGrid();
                  $resultados='';
                  foreach($anexGrid->filtros as $f)
                    {
                      if($f['columna'] == 'resultados' && $f['valor'] != ''){
                        $resultados = $f['valor'];
                      }  
                    }
                  $query->wherenull('resultados')
                  ->orwhere('resultados', 'like', '%'.$resultados.'%');
                })
                ->groupBy('idCita')
                ->orderBy($anexGrid->columna, $order)
                ->skip($anexGrid->pagina)
                ->take($anexGrid->limite)
                ->get();
            
          $total = DB::table('citas')            
                  ->select('idCita', 'fechaAlta','observacionCita','razonSocial','sucursales.sucursal','citas.activo','registradoPor',
                  'resultados','estatus','responsable',
                  DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'),
                  DB::raw('CONCAT(nombreUsuario," ", aPaterno, " ", aMaterno) AS atendida'))
                  ->leftJoin('clientes', 'citas.idc', '=', 'clientes.idc')  
                  ->leftJoin('sucursales', 'citas.idSucursal', '=', 'sucursales.idSucursal')  
                  ->leftJoin('usuarios', 'citas.responsable', '=', 'usuarios.idu')  
                  ->where('razonSocial', 'like', '%'.$cliente.'%')
                  ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                  ->where('registradoPor', 'like', '%'.$registradoPor.'%')
                  ->where('observacionCita', 'like', '%'.$observacionCita.'%')
                  ->where('fechaAlta','>=',$fechaInicio)                     
                  ->where('fechaAlta','<=',$fechaFin)
                  // ->where('estatus', 'like', '%'.$estatus.'%')
                  ->where(function($query) {
                    $anexGrid = new anexGrid();
                    $atendida='';
                    foreach($anexGrid->filtros as $f)
                      {
                        if($f['columna'] == 'atendida' && $f['valor'] != ''){
                          $atendida = $f['valor'];
                        }  
                      }
                    $query->where('nombreUsuario', 'like', '%'.$atendida.'%')
                    ->orwhere('aPaterno', 'like', '%'.$atendida.'%')
                    ->orwhere('aMaterno', 'like', '%'.$atendida.'%');
                  })
                  ->where(function($query) {
                    $anexGrid = new anexGrid();
                    $estatus='';
                    foreach($anexGrid->filtros as $f)
                      {
                        if($f['columna'] == 'estatus' && $f['valor'] != ''){
                          $estatus = $f['valor'];
                        }  
                      }
                    $query->wherenull('estatus')
                    ->orwhere('estatus', 'like', '%'.$estatus.'%');
                  })
                  ->where(function($query) {
                    $anexGrid = new anexGrid();
                    $resultados='';
                    foreach($anexGrid->filtros as $f)
                      {
                        if($f['columna'] == 'resultados' && $f['valor'] != ''){
                          $resultados = $f['valor'];
                        }  
                      }
                    $query->wherenull('resultados')
                    ->orwhere('resultados', 'like', '%'.$resultados.'%');
                  })
                    ->count();

  }else{
        $registros = DB::table('citas')            
            ->select('idCita', 'fechaAlta','observacionCita','razonSocial','sucursales.sucursal','citas.activo','registradoPor',
            'resultados','estatus','responsable',
            DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'),
            DB::raw('CONCAT(nombreUsuario," ", aPaterno, " ", aMaterno) AS atendida'))
            ->leftJoin('clientes', 'citas.idc', '=', 'clientes.idc')  
            ->leftJoin('sucursales', 'citas.idSucursal', '=', 'sucursales.idSucursal')  
            ->leftJoin('usuarios', 'citas.responsable', '=', 'usuarios.idu')  
            ->where('razonSocial', 'like', '%'.$cliente.'%')
            ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
            ->where('registradoPor', 'like', '%'.$registradoPor.'%')
            ->where('observacionCita', 'like', '%'.$observacionCita.'%')
            ->where('fechaAlta','>=',$fechaInicio)                     
            ->where('fechaAlta','<=',$fechaFin)    
            // ->where('estatus', 'like', '%'.$estatus.'%')
            ->whereIn('idCita',  function($query){
              $sidu = Session::get('sesionidu');
              $query->select('idCita')
              ->from('citas')
              ->where('responsable','=',$sidu);
            })
            ->where(function($query) {
              $anexGrid = new anexGrid();
              $atendida='';
              foreach($anexGrid->filtros as $f)
                {
                  if($f['columna'] == 'atendida' && $f['valor'] != ''){
                    $atendida = $f['valor'];
                  }  
                }
              $query->where('nombreUsuario', 'like', '%'.$atendida.'%')
              ->orwhere('aPaterno', 'like', '%'.$atendida.'%')
              ->orwhere('aMaterno', 'like', '%'.$atendida.'%');
            })
            ->where(function($query) {
              $anexGrid = new anexGrid();
              $estatus='';
              foreach($anexGrid->filtros as $f)
                {
                  if($f['columna'] == 'estatus' && $f['valor'] != ''){
                    $estatus = $f['valor'];
                  }  
                }
              $query->wherenull('estatus')
              ->orwhere('estatus', 'like', '%'.$estatus.'%');
            })
            ->where(function($query) {
              $anexGrid = new anexGrid();
              $resultados='';
              foreach($anexGrid->filtros as $f)
                {
                  if($f['columna'] == 'resultados' && $f['valor'] != ''){
                    $resultados = $f['valor'];
                  }  
                }
              $query->wherenull('resultados')
              ->orwhere('resultados', 'like', '%'.$resultados.'%');
            })
            ->groupBy('idCita')
            ->orderBy($anexGrid->columna, $order)
            ->skip($anexGrid->pagina)
            ->take($anexGrid->limite)
          ->get();
        
        $total = DB::table('citas')            
              ->select('idCita', 'fechaAlta','observacionCita','razonSocial','sucursales.sucursal','citas.activo','registradoPor',
            'resultados','estatus','responsable',
            DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'),
            DB::raw('CONCAT(nombreUsuario," ", aPaterno, " ", aMaterno) AS atendida'))
            ->leftJoin('clientes', 'citas.idc', '=', 'clientes.idc')  
            ->leftJoin('sucursales', 'citas.idSucursal', '=', 'sucursales.idSucursal')  
            ->leftJoin('usuarios', 'citas.responsable', '=', 'usuarios.idu')  
            ->where('razonSocial', 'like', '%'.$cliente.'%')
            ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
            ->where('registradoPor', 'like', '%'.$registradoPor.'%')
            ->where('observacionCita', 'like', '%'.$observacionCita.'%')
            ->where('fechaAlta','>=',$fechaInicio)                     
            ->where('fechaAlta','<=',$fechaFin)
            // ->where('estatus', 'like', '%'.$estatus.'%')
            ->whereIn('idCita',  function($query){
              $sidu = Session::get('sesionidu');
              $query->select('idCita')
              ->from('citas')
              ->where('responsable','=',$sidu);
            })
            ->where(function($query) {
              $anexGrid = new anexGrid();
              $atendida='';
              foreach($anexGrid->filtros as $f)
                {
                  if($f['columna'] == 'atendida' && $f['valor'] != ''){
                    $atendida = $f['valor'];
                  }  
                }
              $query->where('nombreUsuario', 'like', '%'.$atendida.'%')
              ->orwhere('aPaterno', 'like', '%'.$atendida.'%')
              ->orwhere('aMaterno', 'like', '%'.$atendida.'%');
            })
            ->where(function($query) {
              $anexGrid = new anexGrid();
              $estatus='';
              foreach($anexGrid->filtros as $f)
                {
                  if($f['columna'] == 'estatus' && $f['valor'] != ''){
                    $estatus = $f['valor'];
                  }  
                }
              $query->wherenull('estatus')
              ->orwhere('estatus', 'like', '%'.$estatus.'%');
            })
            ->where(function($query) {
              $anexGrid = new anexGrid();
              $resultados='';
              foreach($anexGrid->filtros as $f)
                {
                  if($f['columna'] == 'resultados' && $f['valor'] != ''){
                    $resultados = $f['valor'];
                  }  
                }
              $query->wherenull('resultados')
              ->orwhere('resultados', 'like', '%'.$resultados.'%');
            })
              ->count();
  }

  
              $data = array(
                'total' => $total,
                'data' => $registros
              );

              return response()->json($data, 200);
}



public function reporteCitasCreadas(){

  $sname = Session::get('sesionname');
  $sidu = Session::get('sesionidu');
  $stipo = Session::get('sesiontipo');
  $usuario = usuarios::orderby('nombreUsuario','asc')->get();

  if($sname == '' or $sidu =='' or $stipo=='')
  {
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
  }
  else{
    if($stipo == 'Administrador'){
      $citasCreadas = \DB::select("SELECT c.idCita, c.fechaAlta, DATE_FORMAT(c.fechaAlta,'%d %b %Y') AS fecha,c.empresaSeguimiento,
      cli.razonSocial,s.sucursal, c.registradoPor, c.idu,u.nombreUsuario, u.aPaterno, u.aMaterno,
      c.observacionCita, c.responsable, c.resultados, c.estatus, c.activo
      FROM citas AS c 
      INNER JOIN sucursales AS s ON s.idSucursal=c.idSucursal
      INNER JOIN usuarios AS u ON u.idu=c.responsable
      INNER JOIN clientes AS cli ON cli.idc=c.idc");
    }else{
      $citasCreadas = \DB::select("SELECT c.idCita, c.fechaAlta, DATE_FORMAT(c.fechaAlta,'%d %b %Y') AS fecha,c.empresaSeguimiento,
      cli.razonSocial,s.sucursal, c.registradoPor, c.idu,u.nombreUsuario, u.aPaterno, u.aMaterno,
      c.observacionCita, c.responsable, c.resultados, c.estatus, c.activo
      FROM citas AS c 
      INNER JOIN sucursales AS s ON s.idSucursal=c.idSucursal
      INNER JOIN usuarios AS u ON u.idu=c.responsable
      INNER JOIN clientes AS cli ON cli.idc=c.idc 
      WHERE c.idu=?", [$sidu]);
    }

    // $citasCreadas= \DB::select("SELECT DATE_FORMAT(c.fechaAlta,'%d %b %Y') AS fecha, cli.razonSocial, s.sucursal,c.idu, c.registradoPor,c.responsable, c.observacionCita,
    //                                 c.resultados, c.estatus, c.activo, c.idCita,u.nombreUsuario,u.aPaterno,u.aMaterno
    //                             FROM citas AS c
    //                             INNER JOIN clientes AS cli ON cli.idc = c.idc
    //                             INNER JOIN sucursales AS s ON s.idSucursal = c.idSucursal
    //                             INNER JOIN usuarios AS u ON u.idu = c.responsable
    //                             WHERE c.idu = ? AND WEEKOFYEAR(fechaAlta)=WEEKOFYEAR(NOW())",[$sidu]);
  
    
    $cuantasCreadas = count($citasCreadas);

    return view('reporteCitasCreadas')
    ->with('sname',$sname)
    ->with('stipo',$stipo)
    ->with('usuario',$usuario)
    ->with('citasCreadas',$citasCreadas)
    ->with('cuantasCreadas',$cuantasCreadas);
  }
}

public function reporteCitasCreadasAbajo(Request $request){
  $fechaInicio=$request->fechaInicio;
  $fechaFin=$request->fechaFin;
  $idu = $request->idu;

  $sname = Session::get('sesionname');
  $sidu = Session::get('sesionidu');
  $stipo = Session::get('sesiontipo');

  if($sname == '' or $sidu =='' or $stipo=='')
  {
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
  }
  else{

    if($idu == ''){
      $citasCreadas = \DB::select("SELECT c.idCita, c.fechaAlta, DATE_FORMAT(c.fechaAlta,'%d %b %Y') AS fecha,c.empresaSeguimiento, cli.razonSocial,s.sucursal, 
      c.registradoPor, c.idu,u.nombreUsuario, u.aPaterno, u.aMaterno,
      c.observacionCita, c.responsable, c.resultados, c.estatus, c.activo
      FROM citas AS c 
      INNER JOIN sucursales AS s ON s.idSucursal=c.idSucursal
      INNER JOIN usuarios AS u ON u.idu=c.responsable
      INNER JOIN clientes AS cli ON cli.idc=c.idc 
      WHERE fechaAlta>=? AND fechaAlta<=? AND c.idu=? ", [$fechaInicio, $fechaFin,$sidu]);
    }else{
      $citasCreadas = \DB::select("SELECT c.idCita, c.fechaAlta, DATE_FORMAT(c.fechaAlta,'%d %b %Y') AS fecha,c.empresaSeguimiento, cli.razonSocial,s.sucursal, 
      c.registradoPor, c.idu,u.nombreUsuario, u.aPaterno,u.aMaterno,
      c.observacionCita, c.responsable, c.resultados, c.estatus, c.activo
      FROM citas AS c 
      INNER JOIN sucursales AS s ON s.idSucursal=c.idSucursal
      INNER JOIN usuarios AS u ON u.idu=c.responsable
      INNER JOIN clientes AS cli ON cli.idc=c.idc 
      WHERE fechaAlta>=? AND fechaAlta<=? AND c.idu=? ", [$fechaInicio, $fechaFin,$idu]);
    }

    // $citasCreadas= \DB::select("SELECT DATE_FORMAT(c.fechaAlta,'%d %b %Y') AS fecha, cli.razonSocial, s.sucursal,c.idu, c.registradoPor,c.responsable, c.observacionCita,
    //                                 c.resultados, c.estatus, c.activo, c.idCita,u.nombreUsuario,u.aPaterno,u.aMaterno
    //                             FROM citas AS c
    //                             INNER JOIN clientes AS cli ON cli.idc = c.idc
    //                             INNER JOIN sucursales AS s ON s.idSucursal = c.idSucursal
    //                             INNER JOIN usuarios AS u ON u.idu = c.responsable
    //                             WHERE idu = ? AND fechaAlta>=? AND fechaAlta<=? AND WEEKOFYEAR(fechaAlta)=WEEKOFYEAR(NOW())",[$sidu,$fechaInicio,$fechaFin]);
    
    $cuantasCreadas = count($citasCreadas);

    return view('reporteCitasCreadasAbajo')
  ->with('fechaInicio',$fechaInicio)
  ->with('fechaFin',$fechaFin)
  ->with('citasCreadas',$citasCreadas)
  ->with('cuantasCreadas',$cuantasCreadas);


  }
}

////ANEXGRID DE CITAS CREADAS ////

public function filtroCitasCreadasAct(){
  $sname = Session::get('sesionname');
  $sidu = Session::get('sesionidu');
  $stipo = Session::get('sesiontipo');
  $anexGrid = new anexGrid();

  $cliente = '';
  $sucursal = '';
  $registradoPor = '';
  $estatus = '';
  $observacionCita = '';
  $resultados = '';
  foreach($anexGrid->filtros as $f)
          {
            if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
              $cliente = $f['valor'];
            }
            if($f['columna'] == 'sucursal' && $f['valor'] != ''){
              $sucursal = $f['valor'];
            }
            if($f['columna'] == 'registradoPor' && $f['valor'] != ''){
              $registradoPor = $f['valor'];
            }
            if($f['columna'] == 'estatus' && $f['valor'] != ''){
              $estatus = $f['valor'];
            }
            if($f['columna'] == 'observacionCita' && $f['valor'] != ''){
              $observacionCita = $f['valor'];
            }
            if($f['columna'] == 'resultados' && $f['valor'] != ''){
              $resultados = $f['valor'];
            }
          }
  $order;
  $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

  if($stipo == 'Administrador'){
        $registros = DB::table('citas')            
        ->select('idCita', 'fechaAlta','observacionCita','razonSocial','sucursales.sucursal','citas.activo','registradoPor',
        'resultados','estatus','responsable',
        DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'),
        DB::raw('CONCAT(nombreUsuario," ", aPaterno, " ", aMaterno) AS atendida'))
        ->leftJoin('clientes', 'citas.idc', '=', 'clientes.idc')  
        ->leftJoin('sucursales', 'citas.idSucursal', '=', 'sucursales.idSucursal')  
        ->leftJoin('usuarios', 'citas.responsable', '=', 'usuarios.idu')  
        ->where('razonSocial', 'like', '%'.$cliente.'%')
        ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
        ->where('registradoPor', 'like', '%'.$registradoPor.'%')
        ->where('observacionCita', 'like', '%'.$observacionCita.'%')
        // ->where('estatus', 'like', '%'.$estatus.'%')
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $atendida='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'atendida' && $f['valor'] != ''){
                $atendida = $f['valor'];
              }  
            }
          $query->where('nombreUsuario', 'like', '%'.$atendida.'%')
          ->orwhere('aPaterno', 'like', '%'.$atendida.'%')
          ->orwhere('aMaterno', 'like', '%'.$atendida.'%');
        })
        ///Pendiente//
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $estatus='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'estatus' && $f['valor'] != ''){
                $estatus = $f['valor'];
              }  
            }
          $query->wherenull('estatus')
          ->orwhere('estatus', 'like', '%'.$estatus.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $resultados='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'resultados' && $f['valor'] != ''){
                $resultados = $f['valor'];
              }  
            }
          $query->wherenull('resultados')
          ->orwhere('resultados', 'like', '%'.$resultados.'%');
        })
        ->groupBy('idCita')
        ->orderBy($anexGrid->columna, $order)
        ->skip($anexGrid->pagina)
        ->take($anexGrid->limite)
      ->get();
    
          $total = DB::table('citas')            
          ->select('idCita', 'fechaAlta','observacionCita','razonSocial','sucursales.sucursal','citas.activo','registradoPor',
        'resultados','estatus','responsable',
        DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'),
        DB::raw('CONCAT(nombreUsuario," ", aPaterno, " ", aMaterno) AS atendida'))
        ->leftJoin('clientes', 'citas.idc', '=', 'clientes.idc')  
        ->leftJoin('sucursales', 'citas.idSucursal', '=', 'sucursales.idSucursal')  
        ->leftJoin('usuarios', 'citas.responsable', '=', 'usuarios.idu')  
        ->where('razonSocial', 'like', '%'.$cliente.'%')
        ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
        ->where('registradoPor', 'like', '%'.$registradoPor.'%')
        ->where('observacionCita', 'like', '%'.$observacionCita.'%')
        // ->where('estatus', 'like', '%'.$estatus.'%')
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $atendida='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'atendida' && $f['valor'] != ''){
                $atendida = $f['valor'];
              }  
            }
          $query->where('nombreUsuario', 'like', '%'.$atendida.'%')
          ->orwhere('aPaterno', 'like', '%'.$atendida.'%')
          ->orwhere('aMaterno', 'like', '%'.$atendida.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $estatus='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'estatus' && $f['valor'] != ''){
                $estatus = $f['valor'];
              }  
            }
          $query->wherenull('estatus')
          ->orwhere('estatus', 'like', '%'.$estatus.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $resultados='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'resultados' && $f['valor'] != ''){
                $resultados = $f['valor'];
              }  
            }
          $query->wherenull('resultados')
          ->orwhere('resultados', 'like', '%'.$resultados.'%');
        })
          ->count();

  }else{
    $registros = DB::table('citas')            
        ->select('idCita', 'fechaAlta','observacionCita','razonSocial','sucursales.sucursal','citas.activo','registradoPor',
        'resultados','estatus','responsable',
        DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'),
        DB::raw('CONCAT(nombreUsuario," ", aPaterno, " ", aMaterno) AS atendida'))
        ->leftJoin('clientes', 'citas.idc', '=', 'clientes.idc')  
        ->leftJoin('sucursales', 'citas.idSucursal', '=', 'sucursales.idSucursal')  
        ->leftJoin('usuarios', 'citas.responsable', '=', 'usuarios.idu')  
        ->where('razonSocial', 'like', '%'.$cliente.'%')
        ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
        ->where('registradoPor', 'like', '%'.$registradoPor.'%')
        ->where('observacionCita', 'like', '%'.$observacionCita.'%')
        // ->where('estatus', 'like', '%'.$estatus.'%')
        ->whereIn('idCita',  function($query){
          $sidu = Session::get('sesionidu');
          $query->select('idCita')
          ->from('citas')
          ->where('idu','=',$sidu);
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $atendida='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'atendida' && $f['valor'] != ''){
                $atendida = $f['valor'];
              }  
            }
          $query->where('nombreUsuario', 'like', '%'.$atendida.'%')
          ->orwhere('aPaterno', 'like', '%'.$atendida.'%')
          ->orwhere('aMaterno', 'like', '%'.$atendida.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $estatus='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'estatus' && $f['valor'] != ''){
                $estatus = $f['valor'];
              }  
            }
          $query->wherenull('estatus')
          ->orwhere('estatus', 'like', '%'.$estatus.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $resultados='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'resultados' && $f['valor'] != ''){
                $resultados = $f['valor'];
              }  
            }
          $query->wherenull('resultados')
          ->orwhere('resultados', 'like', '%'.$resultados.'%');
        })
        ->groupBy('idCita')
        ->orderBy($anexGrid->columna, $order)
        ->skip($anexGrid->pagina)
        ->take($anexGrid->limite)
      ->get();
    
          $total = DB::table('citas')            
          ->select('idCita', 'fechaAlta','observacionCita','razonSocial','sucursales.sucursal','citas.activo','registradoPor',
        'resultados','estatus','responsable',
        DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'),
        DB::raw('CONCAT(nombreUsuario," ", aPaterno, " ", aMaterno) AS atendida'))
        ->leftJoin('clientes', 'citas.idc', '=', 'clientes.idc')  
        ->leftJoin('sucursales', 'citas.idSucursal', '=', 'sucursales.idSucursal')  
        ->leftJoin('usuarios', 'citas.responsable', '=', 'usuarios.idu')  
        ->where('razonSocial', 'like', '%'.$cliente.'%')
        ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
        ->where('registradoPor', 'like', '%'.$registradoPor.'%')
        ->where('observacionCita', 'like', '%'.$observacionCita.'%')
        // ->where('estatus', 'like', '%'.$estatus.'%')
        ->whereIn('idCita',  function($query){
          $sidu = Session::get('sesionidu');
          $query->select('idCita')
          ->from('citas')
          ->where('idu','=',$sidu);
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $atendida='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'atendida' && $f['valor'] != ''){
                $atendida = $f['valor'];
              }  
            }
          $query->where('nombreUsuario', 'like', '%'.$atendida.'%')
          ->orwhere('aPaterno', 'like', '%'.$atendida.'%')
          ->orwhere('aMaterno', 'like', '%'.$atendida.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $estatus='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'estatus' && $f['valor'] != ''){
                $estatus = $f['valor'];
              }  
            }
          $query->wherenull('estatus')
          ->orwhere('estatus', 'like', '%'.$estatus.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $resultados='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'resultados' && $f['valor'] != ''){
                $resultados = $f['valor'];
              }  
            }
          $query->wherenull('resultados')
          ->orwhere('resultados', 'like', '%'.$resultados.'%');
        })
          ->count();


  }

  
              $data = array(
                'total' => $total,
                'data' => $registros
              );

              return response()->json($data, 200);
}

public function filtroCitasCreadasActAbajo(Request $request){
  $fechaInicio=$request->fechaInicio;
  $fechaFin=$request->fechaFin;
  $sname = Session::get('sesionname');
  $sidu = Session::get('sesionidu');
  $stipo = Session::get('sesiontipo');
  $anexGrid = new anexGrid();

  $cliente = '';
  $sucursal = '';
  $registradoPor = '';
  $estatus = '';
  $observacionCita = '';
  foreach($anexGrid->filtros as $f)
          {
            if($f['columna'] == 'razonSocial' && $f['valor'] != ''){
              $cliente = $f['valor'];
            }
            if($f['columna'] == 'sucursal' && $f['valor'] != ''){
              $sucursal = $f['valor'];
            }
            if($f['columna'] == 'registradoPor' && $f['valor'] != ''){
              $registradoPor = $f['valor'];
            }
            if($f['columna'] == 'estatus' && $f['valor'] != ''){
              $estatus = $f['valor'];
            }
            if($f['columna'] == 'observacionCita' && $f['valor'] != ''){
              $observacionCita = $f['valor'];
            }
          }
  $order;
  $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

  if($stipo == 'Administrador'){
        $registros = DB::table('citas')            
        ->select('idCita', 'fechaAlta','observacionCita','razonSocial','sucursales.sucursal','citas.activo','registradoPor',
        'resultados','estatus','responsable',
        DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'),
        DB::raw('CONCAT(nombreUsuario," ", aPaterno, " ", aMaterno) AS atendida'))
        ->leftJoin('clientes', 'citas.idc', '=', 'clientes.idc')  
        ->leftJoin('sucursales', 'citas.idSucursal', '=', 'sucursales.idSucursal')  
        ->leftJoin('usuarios', 'citas.responsable', '=', 'usuarios.idu')  
        ->where('razonSocial', 'like', '%'.$cliente.'%')
        ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
        ->where('registradoPor', 'like', '%'.$registradoPor.'%')
        ->where('observacionCita', 'like', '%'.$observacionCita.'%')
        ->where('fechaAlta','>=',$fechaInicio)                     
        ->where('fechaAlta','<=',$fechaFin)
        // ->where('estatus', 'like', '%'.$estatus.'%')
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $atendida='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'atendida' && $f['valor'] != ''){
                $atendida = $f['valor'];
              }  
            }
          $query->where('nombreUsuario', 'like', '%'.$atendida.'%')
          ->orwhere('aPaterno', 'like', '%'.$atendida.'%')
          ->orwhere('aMaterno', 'like', '%'.$atendida.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $estatus='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'estatus' && $f['valor'] != ''){
                $estatus = $f['valor'];
              }  
            }
          $query->wherenull('estatus')
          ->orwhere('estatus', 'like', '%'.$estatus.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $resultados='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'resultados' && $f['valor'] != ''){
                $resultados = $f['valor'];
              }  
            }
          $query->wherenull('resultados')
          ->orwhere('resultados', 'like', '%'.$resultados.'%');
        })
        ->groupBy('idCita')
        ->orderBy($anexGrid->columna, $order)
        ->skip($anexGrid->pagina)
        ->take($anexGrid->limite)
      ->get();
    
          $total = DB::table('citas')            
          ->select('idCita', 'fechaAlta','observacionCita','razonSocial','sucursales.sucursal','citas.activo','registradoPor',
        'resultados','estatus','responsable',
        DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'),
        DB::raw('CONCAT(nombreUsuario," ", aPaterno, " ", aMaterno) AS atendida'))
        ->leftJoin('clientes', 'citas.idc', '=', 'clientes.idc')  
        ->leftJoin('sucursales', 'citas.idSucursal', '=', 'sucursales.idSucursal')  
        ->leftJoin('usuarios', 'citas.responsable', '=', 'usuarios.idu')  
        ->where('razonSocial', 'like', '%'.$cliente.'%')
        ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
        ->where('registradoPor', 'like', '%'.$registradoPor.'%')
        ->where('observacionCita', 'like', '%'.$observacionCita.'%')
        ->where('fechaAlta','>=',$fechaInicio)                     
        ->where('fechaAlta','<=',$fechaFin)
        // ->where('estatus', 'like', '%'.$estatus.'%')
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $atendida='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'atendida' && $f['valor'] != ''){
                $atendida = $f['valor'];
              }  
            }
          $query->where('nombreUsuario', 'like', '%'.$atendida.'%')
          ->orwhere('aPaterno', 'like', '%'.$atendida.'%')
          ->orwhere('aMaterno', 'like', '%'.$atendida.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $estatus='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'estatus' && $f['valor'] != ''){
                $estatus = $f['valor'];
              }  
            }
          $query->wherenull('estatus')
          ->orwhere('estatus', 'like', '%'.$estatus.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $resultados='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'resultados' && $f['valor'] != ''){
                $resultados = $f['valor'];
              }  
            }
          $query->wherenull('resultados')
          ->orwhere('resultados', 'like', '%'.$resultados.'%');
        })
          ->count();

  }else{
    $registros = DB::table('citas')            
        ->select('idCita', 'fechaAlta','observacionCita','razonSocial','sucursales.sucursal','citas.activo','registradoPor',
        'resultados','estatus','responsable',
        DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'),
        DB::raw('CONCAT(nombreUsuario," ", aPaterno, " ", aMaterno) AS atendida'))
        ->leftJoin('clientes', 'citas.idc', '=', 'clientes.idc')  
        ->leftJoin('sucursales', 'citas.idSucursal', '=', 'sucursales.idSucursal')  
        ->leftJoin('usuarios', 'citas.responsable', '=', 'usuarios.idu')  
        ->where('razonSocial', 'like', '%'.$cliente.'%')
        ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
        ->where('registradoPor', 'like', '%'.$registradoPor.'%')
        ->where('observacionCita', 'like', '%'.$observacionCita.'%')
        ->where('fechaAlta','>=',$fechaInicio)                     
          ->where('fechaAlta','<=',$fechaFin)
        // ->where('estatus', 'like', '%'.$estatus.'%')
        ->whereIn('idCita',  function($query){
          $sidu = Session::get('sesionidu');
          $query->select('idCita')
          ->from('citas')
          ->where('idu','=',$sidu);
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $atendida='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'atendida' && $f['valor'] != ''){
                $atendida = $f['valor'];
              }  
            }
          $query->where('nombreUsuario', 'like', '%'.$atendida.'%')
          ->orwhere('aPaterno', 'like', '%'.$atendida.'%')
          ->orwhere('aMaterno', 'like', '%'.$atendida.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $estatus='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'estatus' && $f['valor'] != ''){
                $estatus = $f['valor'];
              }  
            }
          $query->wherenull('estatus')
          ->orwhere('estatus', 'like', '%'.$estatus.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $resultados='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'resultados' && $f['valor'] != ''){
                $resultados = $f['valor'];
              }  
            }
          $query->wherenull('resultados')
          ->orwhere('resultados', 'like', '%'.$resultados.'%');
        })
        ->groupBy('idCita')
        ->orderBy($anexGrid->columna, $order)
        ->skip($anexGrid->pagina)
        ->take($anexGrid->limite)
      ->get();
    
          $total = DB::table('citas')            
          ->select('idCita', 'fechaAlta','observacionCita','razonSocial','sucursales.sucursal','citas.activo','registradoPor',
        'resultados','estatus','responsable',
        DB::raw('DATE_FORMAT(fechaAlta,"%d %b %Y") AS fecha'),
        DB::raw('CONCAT(nombreUsuario," ", aPaterno, " ", aMaterno) AS atendida'))
        ->leftJoin('clientes', 'citas.idc', '=', 'clientes.idc')  
        ->leftJoin('sucursales', 'citas.idSucursal', '=', 'sucursales.idSucursal')  
        ->leftJoin('usuarios', 'citas.responsable', '=', 'usuarios.idu')  
        ->where('razonSocial', 'like', '%'.$cliente.'%')
        ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
        ->where('registradoPor', 'like', '%'.$registradoPor.'%')
        ->where('observacionCita', 'like', '%'.$observacionCita.'%')
        ->where('fechaAlta','>=',$fechaInicio)                     
        ->where('fechaAlta','<=',$fechaFin)
        // ->where('estatus', 'like', '%'.$estatus.'%')
        ->whereIn('idCita',  function($query){
          $sidu = Session::get('sesionidu');
          $query->select('idCita')
          ->from('citas')
          ->where('idu','=',$sidu);
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $atendida='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'atendida' && $f['valor'] != ''){
                $atendida = $f['valor'];
              }  
            }
          $query->where('nombreUsuario', 'like', '%'.$atendida.'%')
          ->orwhere('aPaterno', 'like', '%'.$atendida.'%')
          ->orwhere('aMaterno', 'like', '%'.$atendida.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $estatus='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'estatus' && $f['valor'] != ''){
                $estatus = $f['valor'];
              }  
            }
          $query->wherenull('estatus')
          ->orwhere('estatus', 'like', '%'.$estatus.'%');
        })
        ->where(function($query) {
          $anexGrid = new anexGrid();
          $resultados='';
          foreach($anexGrid->filtros as $f)
            {
              if($f['columna'] == 'resultados' && $f['valor'] != ''){
                $resultados = $f['valor'];
              }  
            }
          $query->wherenull('resultados')
          ->orwhere('resultados', 'like', '%'.$resultados.'%');
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
