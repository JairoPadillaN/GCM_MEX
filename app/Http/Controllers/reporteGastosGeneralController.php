<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GastosGeneralExport; 
use App\gastos;
use App\facturas;
use Session;
use DB;

class reporteGastosGeneralController extends Controller
{

    public function reporteGastosGeneral(){
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

        $cuentas = \DB::select("SELECT * FROM cuentas WHERE activo='Si' ORDER BY nombreCuenta ASC");
        $query = DB::table('REPORTEGASTOSGENERAL')
                    ->select('id_factura',
                    'folioServicio',
                    'numFactura',
                    'nombregasto',
                    'beneficiario',
                    'empresa',
                    'nombrecuenta',
                    'razonsocial',
                    'sucursal',
                    'fecha_pago',
                    'referencia',
                    'moneda',
                    'factura',
                    'TotalIva',
                    'IVAMXN',
                    'TotalISR',
                    'ISRMXN',
                    'MontoTotal',
                    'TotalMXN')
                    ->OrderBy('folioServicio','DESC')
                    ->get();
                        
                        $consulta = $this->json($query);

        return view( 'reporteGeneralGastos', compact( 'consulta'))
            ->with('cuentas', $cuentas);

        }
    }
    public function json ( $query){

        $consult = array();
        foreach( $query as $value)
        {
            array_push( $consult, array(
                'id'   => $value->id_factura,
                'a'    => $value->folioServicio, 
                'b'    => $value->numFactura, 
                'c'    => $value->nombregasto, 
                'd'    => $value->beneficiario, 
                'e'    => $value->empresa, 
                'f'    => $value->nombrecuenta, 
                'g'    => $value->razonsocial, 
                'h'    => $value->sucursal, 
                'i'    => $value->fecha_pago, 
                'j'    => $value->referencia, 
                'k'    => $value->moneda,
                'l'    => $value->factura,
                'm'    => $value->TotalIva,
                'n'    => $value->IVAMXN,
                'o'    => $value->TotalISR,
                'p'    => $value->ISRMXN,
                'q'    => $value->MontoTotal,
                'r'    => $value->totalMXN,    
                
            ) );

        }

        return $consult;
    }
 

    public function excelGastosGeneral(Request $request){
        return Excel::download( new GastosGeneralExport( $request->data, $request->user), 'reporteGastos.xlsx' );
    }

    public function reporteGeneralGastosAbajo(Request $request){
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

        $query = \DB::SELECT("SELECT 
        g.id_factura,
        f.idservicios AS 'folioServicio',
        f.numerofactura AS 'numFactura',
        g.nombregasto,
        g.beneficiario,
        g.empresa,
        c.nombrecuenta,
        cli.razonsocial,
        s.sucursal,
        g.fecha_pago,
        g.referencia,
        g.factura,
        g.total_iva AS 'TotalIva',
        IF(g.moneda = 'USD', g.cambioDolar*g.total_iva,g.total_iva) AS 'IVAMXN',
        g.total_isr AS 'TotalISR',
        IF(g.moneda = 'USD', g.cambioDolar*g.total_isr,g.total_isr) AS 'ISRMXN',
        g.total AS 'MontoTotal',
        g.moneda,
        TRUNCATE(
          IF(g.moneda = 'MXN', g.total, g.total * g.cambiodolar),2) AS totalMXN 
      FROM
        gastos AS g 
        INNER JOIN facturas AS f 
          ON f.idfactura = g.id_factura 
        INNER JOIN cuentas AS c 
          ON c.idcuenta = g.idcuenta 
        INNER JOIN sucursales AS s 
          ON s.idsucursal = f.idsucursal 
        INNER JOIN clientes AS cli 
          ON cli.idc = s.idc
          WHERE g.fecha_pago >= '$fechaInicio' AND g.fecha_pago <= '$fechaFin' ");
                            
        $consulta = $this->json($query);
        return view( 'reporteGeneralGastosAbajo', compact( 'consulta'))
        ->with('fecha1',$fechaInicio)
        ->with('fecha2',$fechaFin);


        }
    }

}
/* CREATE VIEW REPORTEGASTOSGENERAL AS
SELECT 
  f.idservicios AS "folioServicio",
  f.numerofactura AS "numFactura",
  g.nombregasto,
  g.beneficiario,
  g.empresa,
  c.nombrecuenta,
  cli.razonsocial,
  s.sucursal,
  g.fecha_pago,
  g.referencia,
  g.factura,
  g.total_iva AS "TotalIva",
  IF(g.moneda = 'USD', g.cambioDolar*g.total_iva,g.total_iva) AS 'IVAMXN',
  g.total_isr AS "TotalISR",
  IF(g.moneda = 'USD', g.cambioDolar*g.total_isr,g.total_isr) AS 'ISRMXN',
  g.total AS "MontoTotal",
  g.moneda,
  TRUNCATE(
    IF(g.moneda = "MXN", g.total, g.total * g.cambiodolar),2) AS totalMXN 
FROM
  gastos AS g 
  INNER JOIN facturas AS f 
    ON f.idfactura = g.id_factura 
  INNER JOIN cuentas AS c 
    ON c.idcuenta = g.idcuenta 
  INNER JOIN sucursales AS s 
    ON s.idsucursal = f.idsucursal 
  INNER JOIN clientes AS cli 
    ON cli.idc = s.idc  */