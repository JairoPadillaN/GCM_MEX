<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CuentasContablesExport;
use App\gastos;
use App\facturas;
use Session;
use DB;

class cuentasContablesController extends Controller
{

    public function reporteCuentasContables(){
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
        $query= \DB::SELECT("SELECT t1.id, t1.clavegasto, t1.fechasalida,t1.cuentagasto,t1.tipo, t1.beneficiario, t1.formaPago, FORMAT(t1.subtotal,2 ) AS subTotal, t1.moneda,
                                t1.ivaTotal, t1.isrTotal, FORMAT(t1.total,2) AS total, t1.divisa,t1.totalpesos,t1.idcuenta,t1.empresa
                                FROM
                                    (SELECT CONCAT('OC - ',oc.codigoorden) AS clavegasto, oc.fechaorden AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
                                    'Orden Compra' AS tipo, CONCAT('Prov -  ',p.razonSocialProv) AS beneficiario,formaPago,importeOrden AS subtotal, moneda, ivaCompra AS ivaTotal, isrCompra AS isrTotal, total, moneda AS divisa,
                                    IF(moneda= 'USD', total * cambiodolar, total) AS totalpesos,c.idCuenta AS idcuenta, oc.idOrden AS id, c.empresaCuenta AS empresa
                                    FROM ordenCompra AS oc
                                    INNER JOIN cuentas AS c ON c.idcuenta = oc.idcuenta
                                    INNER JOIN proveedores AS p ON p.idProveedor = oc.idProveedor
                                    
                                UNION
                                    SELECT CONCAT('REF - ',g.referencia) AS clavegasto,  g.fecha_pago AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
                                    'GASTO' AS tipo, beneficiario, forma_pago AS formaPago, factura AS subtotal, moneda, total_iva AS ivaTotal,total_isr AS isrTotal, total, moneda AS divisa,
                                    IF(moneda='USD', total*cambiodolar,total) AS totalpesos,c.idCuenta AS idcuenta, g.id AS id, g.empresa AS empresa
                                    FROM gastos AS g
                                    INNER JOIN cuentas AS c ON c.idcuenta = g.idCuenta)
                                AS t1
                                ORDER BY t1.fechasalida ASC");
                
                        $consulta = $this->json($query);

        return view( 'reporteCuentasContables', compact( 'consulta'))
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
                'id'    => $value->id,
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
                'l'    => $value->divisa, 
                'm'    => $value->empresa, 
                
            ) );

        }

        return $consult;
    }
 

    public function excelCuentasContables(Request $request){
        return Excel::download( new CuentasContablesExport( $request->data, $request->user), 'reporteCuentas.xlsx' );
    }

    public function reporteCuentasContablesAbajo(Request $request){
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
        $nombreEmpresa = $request->nombreEmpresa;
        $cuenta = $request->cuenta;
        $idCuenta = implode(', ',(array)$cuenta);

        if($idCuenta=="Todos"){
            $idCuenta = implode(', ',(array)$cuenta);
            if($nombreEmpresa=="gcm"){
                $query= \DB::SELECT("SELECT t1.id, t1.clavegasto, t1.fechasalida,t1.cuentagasto,t1.tipo, t1.beneficiario, t1.formaPago, FORMAT(t1.subtotal,2 ) AS subTotal, t1.moneda,
                                    t1.ivaTotal, t1.isrTotal, FORMAT(t1.total,2) AS total, t1.divisa,t1.totalpesos,t1.idcuenta,t1.empresa
                                    FROM
                                        (SELECT CONCAT('OC - ',oc.codigoorden) AS clavegasto, oc.fechaorden AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
                                        'Orden Compra' AS tipo, CONCAT('Prov -  ',p.razonSocialProv) AS beneficiario,formaPago,importeOrden AS subtotal, moneda, ivaCompra AS ivaTotal, isrCompra AS isrTotal, total, moneda AS divisa,
                                        IF(moneda= 'USD', total * cambiodolar, total) AS totalpesos,c.idCuenta AS idcuenta, oc.idOrden AS id, c.empresaCuenta AS empresa
                                        FROM ordenCompra AS oc
                                        INNER JOIN cuentas AS c ON c.idcuenta = oc.idcuenta
                                        INNER JOIN proveedores AS p ON p.idProveedor = oc.idProveedor
                                        
                                    UNION
                                        SELECT CONCAT('REF - ',g.referencia) AS clavegasto,  g.fecha_pago AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
                                        'GASTO' AS tipo, beneficiario, forma_pago AS formaPago, factura AS subtotal, moneda, total_iva AS ivaTotal,total_isr AS isrTotal, total, moneda AS divisa,
                                        IF(moneda='USD', total*cambiodolar,total) AS totalpesos,c.idCuenta AS idcuenta, g.id AS id, g.empresa AS empresa
                                        FROM gastos AS g
                                        INNER JOIN cuentas AS c ON c.idcuenta = g.idCuenta)
                                    AS t1
                                    WHERE t1.fechasalida>=$fechaInicio AND t1.fechasalida<=$fechaFin AND t1.empresa = 'gcm'
                                    ORDER BY t1.fechasalida ASC");
            }elseif($nombreEmpresa=="cym"){
                $query= \DB::SELECT("SELECT t1.id, t1.clavegasto, t1.fechasalida,t1.cuentagasto,t1.tipo, t1.beneficiario, t1.formaPago, FORMAT(t1.subtotal,2 ) AS subTotal, t1.moneda,
                            t1.ivaTotal, t1.isrTotal, FORMAT(t1.total,2) AS total, t1.divisa,t1.totalpesos,t1.idcuenta,t1.empresa
                            FROM
                                (SELECT CONCAT('OC - ',oc.codigoorden) AS clavegasto, oc.fechaorden AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
                                'Orden Compra' AS tipo, CONCAT('Prov -  ',p.razonSocialProv) AS beneficiario,formaPago,importeOrden AS subtotal, moneda, ivaCompra AS ivaTotal, isrCompra AS isrTotal, total, moneda AS divisa,
                                IF(moneda= 'USD', total * cambiodolar, total) AS totalpesos,c.idCuenta AS idcuenta, oc.idOrden AS id, c.empresaCuenta AS empresa
                                FROM ordenCompra AS oc
                                INNER JOIN cuentas AS c ON c.idcuenta = oc.idcuenta
                                INNER JOIN proveedores AS p ON p.idProveedor = oc.idProveedor
                                
                            UNION
                                SELECT CONCAT('REF - ',g.referencia) AS clavegasto,  g.fecha_pago AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
                                'GASTO' AS tipo, beneficiario, forma_pago AS formaPago, factura AS subtotal, moneda, total_iva AS ivaTotal,total_isr AS isrTotal, total, moneda AS divisa,
                                IF(moneda='USD', total*cambiodolar,total) AS totalpesos,c.idCuenta AS idcuenta, g.id AS id, g.empresa AS empresa
                                FROM gastos AS g
                                INNER JOIN cuentas AS c ON c.idcuenta = g.idCuenta)
                            AS t1
                            WHERE t1.fechasalida>=$fechaInicio AND t1.fechasalida<=$fechaFin AND t1.empresa = 'cym'
                            ORDER BY t1.fechasalida ASC");

            }elseif($nombreEmpresa=="surja"){
                $query= \DB::SELECT("SELECT t1.id, t1.clavegasto, t1.fechasalida,t1.cuentagasto,t1.tipo, t1.beneficiario, t1.formaPago, FORMAT(t1.subtotal,2 ) AS subTotal, t1.moneda,
                        t1.ivaTotal, t1.isrTotal, FORMAT(t1.total,2) AS total, t1.divisa,t1.totalpesos,t1.idcuenta,t1.empresa
                        FROM
                            (SELECT CONCAT('OC - ',oc.codigoorden) AS clavegasto, oc.fechaorden AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
                            'Orden Compra' AS tipo, CONCAT('Prov -  ',p.razonSocialProv) AS beneficiario,formaPago,importeOrden AS subtotal, moneda, ivaCompra AS ivaTotal, isrCompra AS isrTotal, total, moneda AS divisa,
                            IF(moneda= 'USD', total * cambiodolar, total) AS totalpesos,c.idCuenta AS idcuenta, oc.idOrden AS id, c.empresaCuenta AS empresa
                            FROM ordenCompra AS oc
                            INNER JOIN cuentas AS c ON c.idcuenta = oc.idcuenta
                            INNER JOIN proveedores AS p ON p.idProveedor = oc.idProveedor
                            
                        UNION
                            SELECT CONCAT('REF - ',g.referencia) AS clavegasto,  g.fecha_pago AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
                            'GASTO' AS tipo, beneficiario, forma_pago AS formaPago, factura AS subtotal, moneda, total_iva AS ivaTotal,total_isr AS isrTotal, total, moneda AS divisa,
                            IF(moneda='USD', total*cambiodolar,total) AS totalpesos,c.idCuenta AS idcuenta, g.id AS id, g.empresa AS empresa
                            FROM gastos AS g
                            INNER JOIN cuentas AS c ON c.idcuenta = g.idCuenta)
                        AS t1
                        WHERE t1.fechasalida>=$fechaInicio AND t1.fechasalida<=$fechaFin AND t1.empresa = 'surja'
                        ORDER BY t1.fechasalida ASC");
            }else{
                $query= \DB::SELECT("SELECT t1.id, t1.clavegasto, t1.fechasalida,t1.cuentagasto,t1.tipo, t1.beneficiario, t1.formaPago, FORMAT(t1.subtotal,2 ) AS subTotal, t1.moneda,
                        t1.ivaTotal, t1.isrTotal, FORMAT(t1.total,2) AS total, t1.divisa,t1.totalpesos,t1.idcuenta,t1.empresa
                        FROM
                            (SELECT CONCAT('OC - ',oc.codigoorden) AS clavegasto, oc.fechaorden AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
                            'Orden Compra' AS tipo, CONCAT('Prov -  ',p.razonSocialProv) AS beneficiario,formaPago,importeOrden AS subtotal, moneda, ivaCompra AS ivaTotal, isrCompra AS isrTotal, total, moneda AS divisa,
                            IF(moneda= 'USD', total * cambiodolar, total) AS totalpesos,c.idCuenta AS idcuenta, oc.idOrden AS id, c.empresaCuenta AS empresa
                            FROM ordenCompra AS oc
                            INNER JOIN cuentas AS c ON c.idcuenta = oc.idcuenta
                            INNER JOIN proveedores AS p ON p.idProveedor = oc.idProveedor
                            
                        UNION
                            SELECT CONCAT('REF - ',g.referencia) AS clavegasto,  g.fecha_pago AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
                            'GASTO' AS tipo, beneficiario, forma_pago AS formaPago, factura AS subtotal, moneda, total_iva AS ivaTotal,total_isr AS isrTotal, total, moneda AS divisa,
                            IF(moneda='USD', total*cambiodolar,total) AS totalpesos,c.idCuenta AS idcuenta, g.id AS id, g.empresa AS empresa
                            FROM gastos AS g
                            INNER JOIN cuentas AS c ON c.idcuenta = g.idCuenta)
                        AS t1
                        WHERE t1.fechasalida>=$fechaInicio AND t1.fechasalida<=$fechaFin 
                        ORDER BY t1.fechasalida ASC");

            }
                   
        }else{
            $idCuenta = implode(', ',(array)$cuenta);
            $query =\DB::select("SELECT t1.id, t1.clavegasto, t1.fechasalida,t1.cuentagasto,t1.tipo, t1.beneficiario, t1.formaPago, FORMAT(t1.subtotal,2 ) AS subTotal, t1.moneda,
                    t1.ivaTotal, t1.isrTotal, FORMAT(t1.total,2) AS total, t1.divisa,t1.totalpesos,t1.idcuenta,t1.empresa
                    FROM
                        (SELECT CONCAT('OC - ',oc.codigoorden) AS clavegasto, oc.fechaorden AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
                        'Orden Compra' AS tipo, CONCAT('Prov -  ',p.razonSocialProv) AS beneficiario,formaPago,importeOrden AS subtotal, moneda, ivaCompra AS ivaTotal, isrCompra AS isrTotal, total, moneda AS divisa,
                        IF(moneda= 'USD', total * cambiodolar, total) AS totalpesos,c.idCuenta AS idcuenta, oc.idOrden AS id, c.empresaCuenta AS empresa
                        FROM ordenCompra AS oc
                        INNER JOIN cuentas AS c ON c.idcuenta = oc.idcuenta
                        INNER JOIN proveedores AS p ON p.idProveedor = oc.idProveedor
                        
                    UNION
                        SELECT CONCAT('REF - ',g.referencia) AS clavegasto,  g.fecha_pago AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
                        'GASTO' AS tipo, beneficiario, forma_pago AS formaPago, factura AS subtotal, moneda, total_iva AS ivaTotal,total_isr AS isrTotal, total, moneda AS divisa,
                        IF(moneda='USD', total*cambiodolar,total) AS totalpesos,c.idCuenta AS idcuenta, g.id AS id, g.empresa AS empresa
                        FROM gastos AS g
                        INNER JOIN cuentas AS c ON c.idcuenta = g.idCuenta)
                    AS t1
            WHERE t1.idcuenta IN ($idCuenta) AND t1.fechasalida>=? AND t1.fechasalida<=?
            ORDER BY t1.fechasalida ASC",[$fechaInicio,$fechaFin]);



        }
                
        
        $consulta = $this->json($query);
        return view( 'reporteCuentasContablesAbajo', compact( 'consulta'));


        }
    }

    public function cuentasgcm(Request $request){
        $cuentas = \DB::select("SELECT * FROM cuentas WHERE activo='Si' AND empresaCuenta = 'gcm' ORDER BY nombreCuenta ASC");

        return view('comboCuentasCont')
        ->with('cuentas', $cuentas);
    }

    public function cuentascym(Request $request){
        $cuentas = \DB::select("SELECT * FROM cuentas WHERE activo='Si' AND empresaCuenta = 'cym' ORDER BY nombreCuenta ASC");

        return view('comboCuentasCont')
        ->with('cuentas', $cuentas);
    }
    public function cuentassurja(Request $request){
        $cuentas = \DB::select("SELECT * FROM cuentas WHERE activo='Si' AND empresaCuenta = 'surja' ORDER BY nombreCuenta ASC");

        return view('comboCuentasCont')
        ->with('cuentas', $cuentas);
    }

}