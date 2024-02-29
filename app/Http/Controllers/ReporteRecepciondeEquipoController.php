<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EquiposServiciosExport;
use App\Exports\EquiposPorTecnicoExport;
use App\facturas;
use App\partesreparacion;
use App\anexGrid;
use Session;
use DB;

class ReporteRecepciondeEquipoController extends Controller
{
    public function reporteEquiposServicios(Request $request){
        
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
            
            return view( 'reporteEquiposServicios');

        }
    }

    public function excelEquiposServicios(Request $request){
        return Excel::download( new EquiposServiciosExport( $request->data, $request->user), 'reporteEquiposServicios.xlsx' );
    }

    public function reporteEquiposServiciosAbajo(Request $request){
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

            
            $query= \DB::SELECT("SELECT f.idservicios,f.factura,f.fechafactura,f.fechapago,coti.idcotizacion,coti.numerocotizacion,c.razonsocial,s.sucursal,CONCAT(re.gcmid,'-',re.complementoGCMid) AS gcmid,
            dr.descripcion,re.serie,
            re.marca,re.modelo,re.ubicacionequipo,te.tipoequipo, st.subtipoequipo  
            FROM
            facturas  AS f
            INNER JOIN cotizaciones AS coti ON coti.idcotizacion  = f.idcotizacion
            INNER JOIN sucursales AS s ON s.idsucursal = f.idsucursal
            INNER JOIN clientes AS c ON c.idc = f.idc
            INNER JOIN detallereparaciones AS dr ON dr.idcotizacion = coti.idcotizacion
            INNER JOIN recepcionEquipos AS re ON re.idequipos = dr.idequipos
            INNER JOIN tipoequipos AS te ON te.idtipoequipo = re.idtipoequipo
            INNER JOIN subtipoequipos AS st ON st.idsubtipoequipo = re.idsubtipoequipo
            WHERE dr.tipocotizacion  = 'reparacion' AND f.tiposervicio= 'REPARACIONES' AND f.activo = 'si' AND f.fechafactura >= '$fechaInicio' and f.fechafactura <= '$fechaFin'");

            $consulta = $this->json($query);
            return view( 'reporteEquiposServiciosAbajo', compact( 'consulta'))
            ->with('fecha1',$fechaInicio)
            ->with('fecha2',$fechaFin);

        }
    }

    public function dataReporteEquiposServicios(){
        $stipo = Session::get('sesiontipo');
        $sidu = Session::get('sesionidu');

        if($stipo == 'Vendedor'){
            $data = \DB::SELECT("SELECT * FROM (
                SELECT f.idservicios,
                IFNULL(f.factura,'') AS factura,
                IFNULL(DATE_FORMAT(f.fechapago,'%d %b %Y' ),'') AS fechapago,
                coti.idcotizacion,
                IFNULL(coti.numerocotizacion,'') AS numerocotizacion,
                c.razonsocial,
                s.sucursal,
                CONCAT(re.gcmid,'-',re.complementoGCMid) AS gcmid,
                dr.descripcion,
                dr.notas,
                re.serie,
                re.marca,
                re.modelo,
                IFNULL(re.ubicacionequipo,'') AS ubicacionequipo,
                te.tipoequipo,
                st.subtipoequipo
                FROM facturas  AS f
                LEFT JOIN cotizaciones AS coti ON coti.idcotizacion  = f.idcotizacion
                LEFT JOIN sucursales AS s ON s.idsucursal = f.idsucursal
                LEFT JOIN clientes AS c ON c.idc = f.idc
                LEFT JOIN detallereparaciones AS dr ON dr.idcotizacion = coti.idcotizacion
                LEFT JOIN recepcionEquipos AS re ON re.idequipos = dr.idequipos
                LEFT JOIN tipoequipos AS te ON te.idtipoequipo = re.idtipoequipo
                LEFT JOIN subtipoequipos AS st ON st.idsubtipoequipo = re.idsubtipoequipo
                WHERE dr.tipocotizacion  = 'reparacion' AND f.tiposervicio= 'REPARACIONES' AND f.activo = 'si' AND coti.idu = $sidu
                ) AS T1 
                ORDER BY T1.idcotizacion DESC
            ");
        }else{
            $data = \DB::SELECT("SELECT * FROM (
                SELECT f.idservicios,
                IFNULL(f.factura,'') AS factura,
                IFNULL(f.fechapago,'') AS fechapago,
                coti.idcotizacion,
                IFNULL(coti.numerocotizacion,'') AS numerocotizacion,
                c.razonsocial,
                s.sucursal,
                CONCAT(re.gcmid,'-',re.complementoGCMid) AS gcmid,
                dr.descripcion,
                dr.notas,
                re.serie,
                re.marca,
                re.modelo,
                IFNULL(re.ubicacionequipo,'') AS ubicacionequipo,
                te.tipoequipo,
                st.subtipoequipo
                FROM facturas  AS f
                LEFT JOIN cotizaciones AS coti ON coti.idcotizacion  = f.idcotizacion
                LEFT JOIN sucursales AS s ON s.idsucursal = f.idsucursal
                LEFT JOIN clientes AS c ON c.idc = f.idc
                LEFT JOIN detallereparaciones AS dr ON dr.idcotizacion = coti.idcotizacion
                LEFT JOIN recepcionEquipos AS re ON re.idequipos = dr.idequipos
                LEFT JOIN tipoequipos AS te ON te.idtipoequipo = re.idtipoequipo
                LEFT JOIN subtipoequipos AS st ON st.idsubtipoequipo = re.idsubtipoequipo
                WHERE dr.tipocotizacion  = 'reparacion'AND f.tiposervicio= 'REPARACIONES' AND f.activo = 'si'
                ) AS T1 
                ORDER BY T1.idcotizacion DESC
            ");
        }

        return response()->json($data, 200);
    }

}
