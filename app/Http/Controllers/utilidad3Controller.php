<?php

namespace App\Http\Controllers;

use App\facturas;
use App\ordenCompra;
use App\gastos;
use App\Http\Controllers\facturasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use App\Exports\UtilidadRepExport;
use Maatwebsite\Excel\Facades\Excel;


class utilidad3Controller extends Controller
{   

    public function consulta()
    {        
       $consultaUtilidad = \DB::select("SELECT t1.clavegasto, t1.fechasalida,t1.cuentagasto,t1.tipo, t1.beneficiario, t1.formaPago, FORMAT(t1.subtotal,2 ) AS subTotal, t1.moneda,
       t1.ivaTotal, t1.isrTotal, FORMAT(t1.total,2) AS total, t1.divisa,t1.totalpesos
       FROM
           (SELECT CONCAT('OC - ',oc.codigoorden) AS clavegasto, oc.fechaorden AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
           'Orden Compra' AS tipo, CONCAT('Prov -  ',p.razonSocialProv) AS beneficiario,formaPago,importeOrden AS subtotal, moneda, ivaCompra AS ivaTotal, isrCompra AS isrTotal, total, moneda AS divisa,
           IF(moneda= 'USD', total * cambiodolar, total) AS totalpesos
           FROM ordenCompra AS oc
           INNER JOIN cuentas AS c ON c.idcuenta = oc.idcuenta
           INNER JOIN proveedores AS p ON p.idProveedor = oc.idProveedor
           WHERE IDFACTURA= $idFactura
       UNION
           SELECT CONCAT('REF - ',g.referencia) AS clavegasto,  g.fecha_pago AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
           'GASTO' AS tipo, beneficiario, forma_pago AS formaPago, factura AS subtotal, moneda, total_iva AS ivaTotal,total_isr AS isrTotal, total, moneda AS divisa,
           IF(moneda='USD', total*cambiodolar,total) AS totalpesos
           FROM gastos AS g
           INNER JOIN cuentas AS c ON c.idcuenta = g.idCuenta
           WHERE ID_FACTURA = $idFactura)
       AS t1
       ORDER BY t1.fechasalida ASC");

       $consultaUtilidad2 = \DB::select("SELECT SUM(t1.totalpesos) AS TOTAL
       FROM
           (SELECT IF(moneda= 'USD', total * cambiodolar, total) AS totalpesos
           FROM ordenCompra AS oc
           INNER JOIN cuentas AS c ON c.idcuenta = oc.idcuenta
           WHERE IDFACTURA= $idFactura
       UNION
           SELECT IF(moneda='USD', total*cambiodolar,total) AS totalpesos
           FROM gastos AS g
           INNER JOIN cuentas AS c ON c.idcuenta = g.idCuenta
           WHERE ID_FACTURA = $idFactura)
       AS t1");

       $totalUtilidad = $consultaUtilidad2[0]->TOTAL;

       $consultaUtilidad3 = \DB::select("SELECT t2.factura, t2.totalservicio
       FROM
           (SELECT idFactura AS factura, IF(tipoMoneda= 'USD', totalConIva * cambioFactura, totalConIva) AS totalservicio
           FROM facturas AS f
           
           WHERE idFactura=120)
       AS t2");


        return view ("editarFacturas")->with('ReporteUtilidad', $consultaUtilidad)->with('total', $totalUtilidad) ;
    }

    public function reporte()
    {   $fechaInicio = null;
        $fechaFin = null;

        $reporteUtilidad = \DB::select("SELECT t1.idfactura, t1.idservicios, t1.numerofactura, t1.fechafactura, t1.fechapago, t1.cliente, t1.sucursal, t1.tipomoneda, TRUNCATE(t1.montofactura,2) AS montofactura,
        TRUNCATE(t1.montopesos,2) AS montopesos, t1.totalgastos, t1.totaloc, TRUNCATE(t1.montopesos - t1.totalgastos - t1.totaloc,2) AS utilidad
        FROM
        (SELECT idfactura, idservicios, numerofactura, IF(fechafactura IS NULL, ' - ', fechafactura) AS fechafactura, IF(fechapago IS NULL,' - ',fechapago) AS fechapago,
        c.razonSocial AS cliente, s.sucursal AS sucursal,tipomoneda,cambioreal, montofactura,
        IF(IF(tipomoneda='USD', cambioreal * montofactura, montofactura) IS NULL,0, IF(tipomoneda='USD', cambioreal * montofactura, montofactura))AS montopesos ,
        IF(totalgastosfactura(idfactura)IS NULL,0,totalgastosfactura(idfactura)) AS totalgastos,
        IF(totalordenescomprafactura(idfactura)IS NULL,0, totalordenescomprafactura(idfactura)) AS totaloc
        FROM facturas AS f
        INNER JOIN clientes AS c ON c.idc = f.idc
        INNER JOIN sucursales AS s ON s.idSucursal = f.idSucursal
        )AS t1
        ");  
    
        return view ("reporteUtilidad3")
        ->with('ReporteUtilidades', $reporteUtilidad)
        ->with('ReporteUtilidadesI', $fechaInicio)
        ->with('ReporteUtilidadesF', $fechaFin);
    }     
   
    public function filtro(Request $request)
    {
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;       
        
        
        $reporteUtilidad = \DB::select("SELECT t1.idfactura, t1.idservicios, t1.numerofactura, t1.fechafactura, t1.fechapago, t1.cliente, t1.sucursal, t1.tipomoneda, TRUNCATE(t1.montofactura,2) AS montofactura,
        TRUNCATE(t1.montopesos,2) AS montopesos, t1.totalgastos, t1.totaloc, TRUNCATE(t1.montopesos - t1.totalgastos - t1.totaloc,2) AS utilidad
        FROM
        (SELECT idfactura, idservicios, numerofactura, IF(fechafactura IS NULL, ' - ', fechafactura) AS fechafactura, IF(fechapago IS NULL,' - ',fechapago) AS fechapago,
        c.razonSocial AS cliente, s.sucursal AS sucursal,tipomoneda,cambioreal, montofactura,
        IF(IF(tipomoneda='USD', cambioreal * montofactura, montofactura) IS NULL,0, IF(tipomoneda='USD', cambioreal * montofactura, montofactura))AS montopesos ,
        IF(totalgastosfactura(idfactura)IS NULL,0,totalgastosfactura(idfactura)) AS totalgastos,
        IF(totalordenescomprafactura(idfactura)IS NULL,0, totalordenescomprafactura(idfactura)) AS totaloc
        FROM facturas AS f
        INNER JOIN clientes AS c ON c.idc = f.idc
        INNER JOIN sucursales AS s ON s.idSucursal = f.idSucursal
        where fechafactura >= '$fechaInicio' and fechafactura <= '$fechaFin'
        )AS t1
        ");        
        return view ("reporteUtilidad3")
            ->with('ReporteUtilidades', $reporteUtilidad)
            ->with('ReporteUtilidadesI', $fechaInicio)
            ->with('ReporteUtilidadesF', $fechaFin);
    }   
        
    public function filtrof(Request $request)
    {   
        $fechaInicio = $request->fechaInicio;
        $fechaFin  = $request->fechaFin;
        $folio       = $request->folio;

        
        $reporteUtilidad = \DB::select("SELECT t1.idfactura, t1.idservicios, t1.numerofactura, t1.fechafactura, t1.fechapago,
        t1.cliente, t1.sucursal, t1.tipomoneda, TRUNCATE(t1.montofactura,2) AS montofactura,
        TRUNCATE(t1.montopesos,2) AS montopesos, t1.totalgastos, t1.totaloc, TRUNCATE(t1.montopesos - t1.totalgastos - t1.totaloc,2) AS utilidad
        FROM
        (SELECT idfactura, idservicios, numerofactura, IF(fechafactura IS NULL, ' - ', fechafactura) AS fechafactura, IF(fechapago IS NULL,' - ',fechapago) AS fechapago,
        c.razonSocial AS cliente, s.sucursal AS sucursal,tipomoneda,cambioreal, montofactura,
        IF(IF(tipomoneda='USD', cambioreal * montofactura, montofactura) IS NULL,0, IF(tipomoneda='USD', cambioreal * montofactura, montofactura))AS montopesos ,
        IF(totalgastosfactura(idfactura)IS NULL,0,totalgastosfactura(idfactura)) AS totalgastos,
        IF(totalordenescomprafactura(idfactura)IS NULL,0, totalordenescomprafactura(idfactura)) AS totaloc
        FROM facturas AS f
        INNER JOIN clientes AS c ON c.idc = f.idc
        INNER JOIN sucursales AS s ON s.idSucursal = f.idSucursal
        where idservicios = '$folio'
       
        ) AS t1
        ");

        return view("reporteUtilidad3")
             ->with('ReporteUtilidades', $reporteUtilidad);
    }

    public function filtroFF(Request $request)
    {   
        $fechaInicio = $request->fechaInicio;
        $fechaFin  = $request->fechaFin;
        $folioF       = $request->folioF;

        
        $reporteUtilidad = \DB::select("SELECT t1.idfactura, t1.idservicios, t1.numerofactura, t1.fechafactura, t1.fechapago,
        t1.cliente, t1.sucursal, t1.tipomoneda, TRUNCATE(t1.montofactura,2) AS montofactura,
        TRUNCATE(t1.montopesos,2) AS montopesos, t1.totalgastos, t1.totaloc, TRUNCATE(t1.montopesos - t1.totalgastos - t1.totaloc,2) AS utilidad
        FROM
        (SELECT idfactura, idservicios, numerofactura, IF(fechafactura IS NULL, ' - ', fechafactura) AS fechafactura, IF(fechapago IS NULL,' - ',fechapago) AS fechapago,
        c.razonSocial AS cliente, s.sucursal AS sucursal,tipomoneda,cambioreal, montofactura,
        IF(IF(tipomoneda='USD', cambioreal * montofactura, montofactura) IS NULL,0, IF(tipomoneda='USD', cambioreal * montofactura, montofactura))AS montopesos ,
        IF(totalgastosfactura(idfactura)IS NULL,0,totalgastosfactura(idfactura)) AS totalgastos,
        IF(totalordenescomprafactura(idfactura)IS NULL,0, totalordenescomprafactura(idfactura)) AS totaloc
        FROM facturas AS f
        INNER JOIN clientes AS c ON c.idc = f.idc
        INNER JOIN sucursales AS s ON s.idSucursal = f.idSucursal
        where numerofactura = '$folioF'
       
        ) AS t1
        ");

        return view("reporteUtilidad3")
             ->with('ReporteUtilidades', $reporteUtilidad);
    }

    public function filtroC(Request $request)
    {   
        $fechaInicio = $request->fechaInicio;
        $fechaFin  = $request->fechaFin;
        $cliente     = $request->cliente;

        
        $reporteUtilidad = \DB::select("SELECT t1.idfactura, t1.idservicios, t1.numerofactura, t1.fechafactura, t1.fechapago,
        t1.cliente, t1.sucursal, t1.tipomoneda, TRUNCATE(t1.montofactura,2) AS montofactura,
        TRUNCATE(t1.montopesos,2) AS montopesos, t1.totalgastos, t1.totaloc, TRUNCATE(t1.montopesos - t1.totalgastos - t1.totaloc,2) AS utilidad
        FROM
        (SELECT idfactura, idservicios, numerofactura, IF(fechafactura IS NULL, ' - ', fechafactura) AS fechafactura, IF(fechapago IS NULL,' - ',fechapago) AS fechapago,
        c.razonSocial AS cliente, s.sucursal AS sucursal,tipomoneda,cambioreal, montofactura,
        IF(IF(tipomoneda='USD', cambioreal * montofactura, montofactura) IS NULL,0, IF(tipomoneda='USD', cambioreal * montofactura, montofactura))AS montopesos,
        IF(totalgastosfactura(idfactura)IS NULL,0,totalgastosfactura(idfactura)) AS totalgastos,
        IF(totalordenescomprafactura(idfactura)IS NULL,0, totalordenescomprafactura(idfactura)) AS totaloc
        FROM facturas AS f
        INNER JOIN clientes AS c ON c.idc = f.idc
        INNER JOIN sucursales AS s ON s.idSucursal = f.idSucursal
       /* where c.razonSocial like '%$cliente%' and fechafactura >= '$fechaInicio' and fechafactura <= '$fechaFin'*/
       where c.razonSocial like '%$cliente%' and fechafactura >= '$fechaInicio' and fechafactura <= '$fechaFin'
        ) AS t1
        ");

        return view("reporteUtilidad3")
             ->with('ReporteUtilidades', $reporteUtilidad);
    }
    public function UtilidadRep(){
        return Excel::download(new UtilidadRepExport, 'Reporte_Utilidades.xlsx');
      }
}