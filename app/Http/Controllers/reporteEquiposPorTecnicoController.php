<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EquiposServiciosExport;
use App\Exports\EquiposPorTecnicoExport;
use App\facturas;
use App\partesreparacion;
use Session;
use DB;


class reporteEquiposPorTecnicoController extends Controller
{
    public function reporteEquiposPorTecnico(){
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
      
        $query= \DB::SELECT("SELECT t1.idequipos,t1.gcmid,t1.gcmidparte,t1.nombreparte,t1.fecharegistro,t1.fechasalida,t1.sucursal,t1.razonsocial,t1.marca,t1.modelo,t1.serie,t1.ultimacot,t1.numerocotizacion,
        buscaservicio(t1.ultimacot) AS busquedaservicio
        FROM
        (SELECT pr.idequipos, pr.gcmid, pr.gcmidparte,pr.nombreparte,pr.fechasalida,s.sucursal,c.razonsocial,
        re.marca,re.fecharegistro,re.modelo,re.serie,ultimacotizacionequipo(pr.idequipos) AS ultimacot, coti.numeroCotizacion
        FROM partesreparacion AS pr
        INNER JOIN recepcionEquipos AS re ON pr.idequipos = re.idequipos 
        INNER JOIN sucursales AS s ON s.idsucursal = re.idsucursal
        INNER JOIN clientes AS c ON c.idc = re.idc
        INNER JOIN cotizaciones AS coti ON coti.idcotizacion = ultimacotizacionequipo(pr.idequipos)) AS t1");
      
      $consulta = $this->json($query);
      
      return view( 'reporteEquiposPorTecnico', compact( 'consulta'));
        
      
        }
      }
      public function reporteEquiposPorTecnico_app(){
        $query= DB::table('partesreparacion')->select()->limit(100)->get();
      return response()->json($query);
      }

      public function json($query){
      
          $consult = array();
          foreach( $query as $value)
          {
            array_push( $consult, array(
              'id'    => $value->idequipos,
              'a'    => $value->gcmid,
              'b'    => $value->gcmidparte, 
              'c'    => $value->nombreparte,
              'd'    => $value->fecharegistro,
              'e'    => $value->fechasalida, 
              'f'    => $value->sucursal, 
              'g'    => $value->razonsocial, 
              'h'    => $value->marca, 
              'i'    => $value->modelo,
              'j'    => $value->serie,
              'k'    => $value->ultimacot,
              'l'    => $value->numeroCotizacion, 
              'm'    => $value->busquedaservicio, 
                  
              ) );
      
          }
      
          return $consult;
      }

      
      public function excelEquiposPorTecnico(Request $request){
        return Excel::download( new EquiposPorTecnicoExport( $request->data, $request->user), 'reporteEquiposPorTecnico.xlsx' );
    } 
    public function reporteEquiposPorTecnicoAbajo(Request $request){
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
      $fechaInicio=$request->fechaInicio;
      $fechaFin=$request->fechaFin;
    
      
      $query= \DB::SELECT("SELECT t1.idequipos,t1.gcmid,t1.gcmidparte,t1.nombreparte,t1.fecharegistro,t1.fechasalida,t1.sucursal,t1.razonsocial,t1.marca,t1.modelo,t1.serie,t1.ultimacot,t1.numerocotizacion,
        buscaservicio(t1.ultimacot) AS busquedaservicio
        FROM 
        (SELECT pr.idequipos, pr.gcmid, pr.gcmidparte,pr.nombreparte,pr.fechasalida,s.sucursal,c.razonsocial,
        re.marca,re.fecharegistro,re.modelo,re.serie,ultimacotizacionequipo(pr.idequipos) AS ultimacot, coti.numeroCotizacion
        FROM partesreparacion AS pr
        INNER JOIN recepcionEquipos AS re ON pr.idequipos = re.idequipos 
        INNER JOIN sucursales AS s ON s.idsucursal = re.idsucursal
        INNER JOIN clientes AS c ON c.idc = re.idc
        INNER JOIN cotizaciones AS coti ON coti.idcotizacion = ultimacotizacionequipo(pr.idequipos)  WHERE  re.fecharegistro >= '$fechaInicio' AND re.fecharegistro <= '$fechaFin') AS t1");
                          
                  
              
      
      $consulta = $this->json($query);
      return view( 'reporteEquiposPorTecnicoAbajo', compact( 'consulta'))
      ->with('fecha1',$fechaInicio)
      ->with('fecha2',$fechaFin);
    
    
      }
    }

    
}
