<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GastozExport; 
use App\Exports\MultipleSheetsGastosOperativosExport;
use App\gastos;
use App\facturas;
use Session;
use DB;

class reporteDeGastozController extends Controller
{

    public function reporteGastoz(){
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
        $query= \DB::SELECT("SELECT t1.idfactura,t1.servicio, t1.factura, t1.gasto, t1.beneficiario, t1.empresa, t1.cuenta, t1.razonsocial, t1.sucursal, t1.fecha, t1.referencia,
                t1.subtotal, FORMAT(t1.iva,2)AS iva, FORMAT(t1.isr,2) AS isr, t1.total, t1.moneda, t1.totalmxn
                FROM( SELECT
                f.idfactura AS idfactura,
                f.idservicios AS servicio,
                f.numerofactura AS factura,
                g.nombregasto AS gasto,
                g.beneficiario AS beneficiario,
                IF(g.empresa IS NULL, '-',g.empresa) AS empresa,
                c.nombrecuenta AS cuenta,
                cli.razonsocial AS razonsocial,
                s.sucursal AS sucursal,
                g.fecha_pago AS fecha,
                g.referencia AS referencia,
                CONCAT(g.factura, ' ', g.moneda)AS subtotal,
                g.total_iva AS iva,
                g.total_isr AS isr,
                CONCAT(g.total, ' ' , g.moneda) AS total,
                IF(g.moneda IS NULL, 'MXN', g.moneda) AS moneda,
                TRUNCATE(IF(g.moneda='USD',g.total*g.cambiodolar,g.total),2) AS totalmxn
                FROM gastos AS g
                INNER JOIN facturas AS f ON f.idfactura = g.id_factura
                INNER JOIN cuentas AS c ON c.idcuenta = g.idcuenta
                INNER JOIN sucursales AS s ON s.idsucursal = f.idsucursal
                INNER JOIN clientes AS cli ON cli.idc = s.idc
                ORDER BY servicio ASC
                ) AS t1");               
                /* $query = \DB::table('REPORTEGASTOSGRAL')
                        ->select(
                            'idfactura',
                            'servicio',
                            'factura',
                            'gasto',
                            'beneficiario',
                            'empresa',
                            'cuenta',
                            'razonsocial',
                            'sucursal',
                            DB::raw('DATE_FORMAT(fecha,"%d/%m/%Y") as fecha'),
                            'referencia',
                            'subtotal',
                            'iva',
                            'isr',
                            'total',
                            'moneda',
                            'totalmxn')
                            ->get(); */
                    $consulta = $this->json($query);

        return view( 'reporteDeGastoz', compact( 'consulta'))
            ->with('cuentas', $cuentas);
        
        
        // return view('reporteOrdenesCompra')
        // ->with('sumaTotales', $sumaTotales[0]);
        }
    }
    public function json ( $query){

        $consult = array();
        foreach( $query as $value)
        {
            array_push( $consult, array(
                'id'   => $value->idfactura,
                'a'    => $value->servicio, 
                'b'    => $value->factura, 
                'c'    => $value->gasto, 
                'd'    => $value->beneficiario, 
                'e'    => $value->empresa, 
                'f'    => $value->cuenta, 
                'g'    => $value->razonsocial, 
                'h'    => $value->sucursal, 
                'i'    => $value->fecha, 
                'j'    => $value->referencia, 
                'k'    => $value->subtotal, 
                'l'    => $value->iva,
                'm'    => $value->isr,
                'n'    => $value->total,
                'o'    => $value->totalmxn,   
                
                
            ) );

        }

        return $consult;
    }
 

    public function excelReporteGastoz(Request $request){
        return Excel::download( new GastozExport( $request->data, $request->user), 'reporteGastos.xlsx' );
    }

    public function reporteGastozAbajo(Request $request){
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

        
        $query= \DB::SELECT("SELECT t1.idfactura,t1.servicio, t1.factura, t1.gasto, t1.beneficiario, t1.empresa, t1.cuenta, t1.razonsocial, t1.sucursal, t1.fecha, t1.referencia,
                            t1.subtotal, FORMAT(t1.iva,2)AS iva, FORMAT(t1.isr,2) AS isr, t1.total, t1.moneda, t1.totalmxn
                            FROM( SELECT
                            f.idfactura AS idfactura,
                            f.idservicios AS servicio,
                            f.numerofactura AS factura,
                            g.nombregasto AS gasto,
                            g.beneficiario AS beneficiario,
                            IF(g.empresa IS NULL, '-',g.empresa) AS empresa,
                            c.nombrecuenta AS cuenta,
                            cli.razonsocial AS razonsocial,
                            s.sucursal AS sucursal,
                            g.fecha_pago AS fecha,
                            g.referencia AS referencia,
                            CONCAT(g.factura, ' ', g.moneda)AS subtotal,
                            g.total_iva AS iva,
                            g.total_isr AS isr,
                            CONCAT(g.total, ' ' , g.moneda) AS total,
                            IF(g.moneda IS NULL, 'MXN', g.moneda) AS moneda,
                            TRUNCATE(IF(g.moneda='USD',g.total*g.cambiodolar,g.total),2) AS totalmxn
                            FROM gastos AS g
                            INNER JOIN facturas AS f ON f.idfactura = g.id_factura
                            INNER JOIN cuentas AS c ON c.idcuenta = g.idcuenta
                            INNER JOIN sucursales AS s ON s.idsucursal = f.idsucursal
                            INNER JOIN clientes AS cli ON cli.idc = s.idc
                            WHERE g.fecha_pago >= '$fechaInicio' and g.fecha_pago <= '$fechaFin'
                            ) AS t1");
                            
                    
                
                            
        $consulta = $this->json($query);
        return view( 'reporteGastozAbajo', compact( 'consulta'))
        ->with('fecha1',$fechaInicio)
        ->with('fecha2',$fechaFin);


        }
    }

    public function reporteGastosOperativos()
    {
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {
            
            return view('reporteGastosOperativos');                
        }
    }

    public function reporteCostoVenta(Request $request)
    {
        $fechaInicio = $request->fechaInicio;
        $fechaFinal = $request->fechaFinal;
        $empresa = $request->empresa;
		$cadena = $request->cadena;
       // $timestamp = now()->timestamp; 
        
        //return now();
        return Excel::download(new MultipleSheetsGastosOperativosExport($fechaInicio, $fechaFinal, $empresa), 'reporte_' . $cadena . '.xlsx');
    } 

}