<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;                                                                                   
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
// use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;                           
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class GastozExport implements FromView, ShouldAutoSize
{

    public function __construct( $data, $user ){
        $this->data = $data;
        
    }

    public function query(){        

        /* $query= \DB::SELECT("SELECT t1.idfactura,t1.servicio, t1.factura, t1.gasto, t1.beneficiario, t1.empresa, t1.cuenta, t1.razonsocial, t1.sucursal, t1.fecha, t1.referencia,
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
        ) AS t1");  */      

        $query = \DB::table('REPORTEGASTOSGRAL')
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
            ->whereIn('idfactura',$this->data)
            ->orderby('servicio','ASC')
            ->get();
            

        $consulta = $this->json( $query );

        return $consulta;
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

    public function view(): View
    {
        $data = $this->query();

        return view( 'excelReporteGastoz', compact( 'data') );
    }


}

//------------Vista------------------------
/* CREATE VIEW REPORTEGASTOSGRAL AS
SELECT t1.idfactura,t1.servicio, t1.factura, t1.gasto, t1.beneficiario, t1.empresa, t1.cuenta, t1.razonsocial, t1.sucursal, t1.fecha, t1.referencia,
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
                ) AS t1 */