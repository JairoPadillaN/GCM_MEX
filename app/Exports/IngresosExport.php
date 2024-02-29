<?php

namespace App\Exports;

use App\gastosOperativos;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IngresosExport implements FromView, ShouldAutoSize, WithTitle, WithStyles
{
    private $fechaInicio;
    private $fechaFinal;
    private $empresa;

    public function __construct($fechaInicio, $fechaFinal, $empresa )
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFinal = $fechaFinal;
        $this->empresa = $empresa;
    }
    public function view(): View
    {
        $ingresos = DB::select("SELECT dps.fechaDatosPago,fact.idservicios,CONCAT(c.razonSocial, ' - ',s.sucursal) 
        AS cliente,dps.numeroFacturaDP,dps.tipoCambioPagado,dps.subtotalFinal,dps.ivaFinal,dps.saldoReal,dps.montoReal,dps.difCambiaria,dps.observaciones
        FROM datosPagoServicios AS dps
        INNER JOIN facturas AS fact ON fact.idFactura = dps.idFactura
        INNER JOIN clientes AS c ON c.idc = fact.idc
        INNER JOIN sucursales AS s ON fact.idSucursal = s.idsucursal
        WHERE dps.fechaDatosPago>='$this->fechaInicio' AND  dps.fechaDatosPago<='$this->fechaFinal' AND dps.pagada = 'Si' AND fact.nombreEmpresa = '$this->empresa'");    

        $resultados = DB::select("SELECT t2.ventas,t2.costodeventa,t2.utilidadbruta,t2.gastosdeoperacion,t2.gastodeventa,t2.gastoadministrativo,t2.utilidaddeoperacion,t2.CIF,t2.utilidadantesdeimpuestos,
        t2.utilidadantesdeimpuestos * .30 AS ISR30, t2.utilidadantesdeimpuestos * .10 AS PTU10,
        t2.utilidadantesdeimpuestos - (t2.utilidadantesdeimpuestos * .30) -  (t2.utilidadantesdeimpuestos * .10) AS utilidadneta,
        t2.IMPUESTOS,t2.INTERES,t2.GASTOFINANCIERO
        FROM
        (SELECT t1.ventas,t1.costodeventa,t1.ventas-t1.costodeventa AS utilidadbruta,t1.gastodeventa+t1.gastoadministrativo AS gastosdeoperacion,
        t1.gastodeventa,t1.gastoadministrativo,(t1.ventas-t1.costodeventa) - (t1.gastodeventa+t1.gastoadministrativo) AS utilidaddeoperacion,
        t1.gastofinanciero + t1.impuestos + t1.interes AS CIF,
        ((t1.ventas-t1.costodeventa) - (t1.gastodeventa+t1.gastoadministrativo)) - (t1.gastofinanciero + t1.impuestos + t1.interes) AS utilidadantesdeimpuestos,
        t1.IMPUESTOS,t1.INTERES,t1.GASTOFINANCIERO
        FROM
        (SELECT totalfacturado('$this->fechaInicio','$this->fechaFinal','$this->empresa') AS ventas,
        SUM(IF(go.etiquetas='COSTO DE VENTA', IF(go.moneda='MXN',go.factura,go.factura * go.cambiodolar),0)) AS COSTODEVENTA,
        SUM(IF(go.etiquetas='GASTO ADMINISTRATIVO', IF(go.moneda='MXN',go.factura,go.factura * go.cambiodolar),0)) AS GASTOADMINISTRATIVO,
        SUM(IF(go.etiquetas='GASTO DE VENTA', IF(go.moneda='MXN',go.factura,go.factura * go.cambiodolar),0)) AS GASTODEVENTA,
        SUM(IF(go.etiquetas='GASTO FINANCIERO', IF(go.moneda='MXN',go.factura,go.factura * go.cambiodolar),0) )AS GASTOFINANCIERO,
        SUM(IF(go.etiquetas='IMPUESTOS', IF(go.moneda='MXN',go.factura,go.factura * go.cambiodolar),0)) AS IMPUESTOS,
        SUM(IF(go.etiquetas='INTERES', IF(go.moneda='MXN',go.factura,go.factura * go.cambiodolar),0)) AS INTERES
        FROM gastosOperativos AS go
        INNER JOIN cuentas AS cu ON cu.idcuenta = go.idcuenta
        WHERE fecha_pago >='$this->fechaInicio' AND fecha_pago <='$this->fechaFinal' AND go.activo = 'Si' AND go.empresa = '$this->empresa' ORDER BY fecha_pago ASC) AS t1) AS t2");

        $periodo=[$this->fechaInicio,$this->fechaFinal];


        return view('ingresos', [
            'ingresos' => $ingresos,            
            'resultados' => $resultados,            
            'periodo' => $periodo,            
        ]);
    }

    public function styles(Worksheet $sheet)
    {    

    $sheet->getStyle('E')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);       
    $sheet->getStyle('F')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);       
    $sheet->getStyle('G')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);       
    $sheet->getStyle('H')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);       
    $sheet->getStyle('I')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);       
    $sheet->getStyle('J')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);       
    return [               
    ];
}


    public function title(): string
    {
        return 'INGRESOS';
    } 
}
