<?php

namespace App\Exports;

use App\gastosOperativos;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EstadoDeResultadosExport implements FromView, ShouldAutoSize, WithTitle, WithStyles
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
        $resultados = DB::select("SELECT t2.ventas,t2.costodeventa,t2.utilidadbruta,t2.gastosdeoperacion,t2.gastodeventa,t2.gastoadministrativo,t2.utilidaddeoperacion,t2.CIF,t2.utilidadantesdeimpuestos,
        IF(t2.utilidadantesdeimpuestos<0,0,t2.utilidadantesdeimpuestos * .30) AS ISR30,
        IF(t2.utilidadantesdeimpuestos<0,0, t2.utilidadantesdeimpuestos * .10)AS PTU10,
        t2.utilidadantesdeimpuestos - IF(t2.utilidadantesdeimpuestos<0,0,t2.utilidadantesdeimpuestos * .30) -  IF(t2.utilidadantesdeimpuestos<0,0, t2.utilidadantesdeimpuestos * .10) AS utilidadneta,
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
        WHERE fecha_pago >='$this->fechaInicio' AND fecha_pago <='$this->fechaFinal' AND go.activo = 'Si' AND go.empresa = '$this->empresa' 
        ORDER BY fecha_pago ASC) AS t1) AS t2");
        
        $porcentajes = DB::select("SELECT t3.ventas/t3.ventas*100 AS porventas,t3.costodeventa/t3.ventas*100 AS porcostoventa,t3.utilidadbruta/t3.ventas*100 AS portutilidadbruta,
        t3.gastosdeoperacion/t3.ventas*100 AS porgastosdeoperacion,t3.gastodeventa/t3.ventas*100 AS porgastodeventa,t3.gastoadministrativo/t3.ventas*100 AS porgastoadministrativo,
        t3.utilidaddeoperacion/t3.ventas*100 AS porutilidadoperacion,t3.cif/t3.ventas*100 AS porcif,
        t3.utilidadantesdeimpuestos/t3.ventas*100 AS porutilantesimpuestos,
        t3.ISR30/t3.ventas*100 AS porISR30,
        t3.PTU10/t3.ventas*100 AS porPTU10,
        t3.utilidadneta/t3.ventas*100 AS porutilidadneta
        FROM
        (SELECT t2.ventas,t2.costodeventa,t2.utilidadbruta,t2.gastosdeoperacion,t2.gastodeventa,t2.gastoadministrativo,t2.utilidaddeoperacion,t2.CIF,t2.utilidadantesdeimpuestos,
        IF(t2.utilidadantesdeimpuestos<0,0,t2.utilidadantesdeimpuestos * .30) AS ISR30,
        IF(t2.utilidadantesdeimpuestos<0,0, t2.utilidadantesdeimpuestos * .10) AS PTU10,
        t2.utilidadantesdeimpuestos -IF(t2.utilidadantesdeimpuestos<0,0,t2.utilidadantesdeimpuestos * .30) -  IF(t2.utilidadantesdeimpuestos<0,0, t2.utilidadantesdeimpuestos * .10) AS utilidadneta,
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
        WHERE fecha_pago >='$this->fechaInicio' AND fecha_pago <='$this->fechaFinal' AND go.activo = 'Si' AND go.empresa = '$this->empresa' 
        ORDER BY fecha_pago ASC) AS t1) AS t2) AS t3");
        
        // dd($porcentajes);
        $periodo=[$this->fechaInicio,$this->fechaFinal];
        $empresa=$this->empresa;
        if ($empresa === 'gcm') {
            $nombreEmpresa = 'GERMAN CONTROL MOTION';
        } else {
            $nombreEmpresa = $empresa;
        }
        // dd($empresa);
        // dd($nombreEmpresa);
        
        return view('estadoDeResultados', [
            'resultados' => $resultados,   
            'periodo' => $periodo,         
            'porcentajes' => $porcentajes,         
            'nombreEmpresa' => $nombreEmpresa,         
        ]);
    }

    public function styles(Worksheet $sheet)
    {
    $tableRange = 'A5:C18';
    $col2Range = 'B5:B18';
    $col3Range = 'C5:C18';

    $sheet->getStyle('B')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);
    $sheet->getStyle('C')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
    return [
        $tableRange => [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, // Grosor grueso
                    'color' => ['rgb' => '000000'], // Color del borde
                ],
            ],
        ],
        $col2Range => [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, // Grosor grueso
                    'color' => ['rgb' => '000000'], // Color del borde
                ],
            ],
        ],        
        $col3Range => [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, // Grosor grueso
                    'color' => ['rgb' => '000000'], // Color del borde
                ],
            ],
        ],        
    ];
}

    public function title(): string
    {
        return 'ESTADO DE RESULTADOS';
    } 
}
