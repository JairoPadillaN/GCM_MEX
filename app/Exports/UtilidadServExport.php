<?php

namespace App\Exports;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\ordenCompra;
use App\cuentas;
use App\proveedores;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\facturasController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;
use \Illuminate\Support\Collection;

class UtilidadServExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return[
            'Folio',
            'Fecha',
            'Cuenta',
            'Tipo',
            'Beneficiario',
            'Forma de Pago',
            'Importe',
            'IVA',
            'ISR',
            'Total',
            'totalMXN'
        ];
    }
    public function collection()
    {
   
        $query = DB::select("SELECT t1.clavegasto, t1.fechasalida,t1.cuentagasto,t1.tipo, t1.beneficiario, t1.formaPago, FORMAT(t1.subtotal,2 ) AS subTotal, t1.moneda,
        t1.ivaTotal, t1.isrTotal, FORMAT(t1.total,2) AS total, t1.divisa,t1.totalpesos
        FROM
            (SELECT CONCAT('OC - ',oc.codigoorden) AS clavegasto, oc.fechaorden AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
            'Orden Compra' AS tipo, CONCAT('Prov -  ',p.razonSocialProv) AS beneficiario,formaPago,importeOrden AS subtotal, moneda, ivaCompra AS ivaTotal, isrCompra AS isrTotal, total, moneda AS divisa,
            IF(moneda= 'USD', total * cambiodolar, total) AS totalpesos
            FROM ordenCompra AS oc
            INNER JOIN cuentas AS c ON c.idcuenta = oc.idcuenta
            INNER JOIN proveedores AS p ON p.idProveedor = oc.idProveedor
            
        UNION
            SELECT CONCAT('REF - ',g.referencia) AS clavegasto,  g.fecha_pago AS fechasalida, CONCAT(c.numeroCu,' ',c.nombrecuenta) AS cuentagasto,
            'GASTO' AS tipo, beneficiario, forma_pago AS formaPago, factura AS subtotal, moneda, total_iva AS ivaTotal,total_isr AS isrTotal, total, moneda AS divisa,
            IF(moneda='USD', total*cambiodolar,total) AS totalpesos
            FROM gastos AS g
            INNER JOIN cuentas AS c ON c.idcuenta = g.idCuenta
            )
        AS t1
        ");
        ;
    $data = $query;
    $count = count($data)+1;
    foreach($data as $e){
        $ex_array[]=array(
            'clavegasto'=>$e->clavegasto,
            'fechasalida'=>$e->fechasalida,
            'cuentagasto'=>$e->cuentagasto,
            'tipo'=>$e->tipo,
            'beneficiario'=>$e->beneficiario,
            'formaPago'=>$e->formaPago,
            'subTotal'=>$e->subTotal,
            'ivaTotal'=>$e->ivaTotal,
            'isrTotal'=>$e->isrTotal,
            'total'=>$e->total,
            'totalpesos'=>$e->totalpesos
        );
    }
    return new Collection($ex_array);
    }
}
