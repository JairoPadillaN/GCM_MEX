<?php

namespace App\Exports;

use App\gastosOperativos;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class GastosOperativosTodosExport implements FromView, ShouldAutoSize, WithTitle, WithStyles/* , WithColumnFormatting */
{
    private $fechaInicio;
    private $fechaFinal;
    private $empresa;
    private $numCostoVenta;
    private $numGastoAdmin;
    private $numGastoVenta;
    private $numGastoFinan;
    private $numImpuestos;
    private $numInteres;

    public function __construct($fechaInicio, $fechaFinal, $empresa )
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFinal = $fechaFinal;
        $this->empresa = $empresa;
    }

    public function view(): View
    {
        // dd($this->fechaInicio, $this->fechaFinal, $this->empresa);
        
        // $costoVenta = DB::select("SELECT go.fecha_pago,go.nombreGasto,go.beneficiario,CONCAT(cu.numeroCu,' ',cu.nombreCuenta)
        // AS cuentacontable,go.forma_pago,go.referencia,go.etiquetas,go.descripcion,go.factura AS subtotal,go.iva,go.total_iva,
        // go.isr,go.total_isr,go.total AS totalenpesos
        // FROM gastosOperativos AS go
        // INNER JOIN cuentas AS cu ON cu.idcuenta = go.idcuenta
        // WHERE fecha_pago >='$this->fechaInicio' AND fecha_pago <='$this->fechaFinal' AND go.activo = 'Si' AND go.empresa = '$this->empresa'
        // AND go.etiquetas = 'COSTO DE VENTA'
        // ORDER BY fecha_pago ASC");    
        
        $costoVenta = DB::select("SELECT go.fecha_pago,go.nombreGasto,go.beneficiario,CONCAT(cu.numeroCu,' ',cu.nombreCuenta) AS
        cuentacontable,go.forma_pago,go.referencia,go.etiquetas, go.moneda,
        IF(go.moneda='MXN',go.factura,go.factura * go.cambiodolar) AS subtotal, go.iva,
        IF(go.moneda='MXN',go.total_iva,go.total_iva* go.cambiodolar) AS total_iva, go.isr,
        IF(go.moneda='MXN',go.total_isr,go.total_isr* go.cambiodolar) AS total_isr,
        IF(go.moneda='MXN',go.total,go.total* go.cambiodolar) AS totalenpesos,go.descripcion
        FROM gastosOperativos AS go INNER JOIN cuentas AS cu ON cu.idcuenta = go.idcuenta
        WHERE fecha_pago >='$this->fechaInicio' AND fecha_pago <='$this->fechaFinal' AND go.activo = 'Si' AND go.empresa = '$this->empresa' AND
        go.etiquetas = 'COSTO DE VENTA' ORDER BY fecha_pago ASC");
        

        $gastoAdmin = DB::select("SELECT go.fecha_pago,go.nombreGasto,go.beneficiario,CONCAT(cu.numeroCu,' ',cu.nombreCuenta) AS
        cuentacontable,go.forma_pago,go.referencia,go.etiquetas,
        go.moneda,
        IF(go.moneda='MXN',go.factura,go.factura * go.cambiodolar) AS subtotal,
        go.iva,
        IF(go.moneda='MXN',go.total_iva,go.total_iva* go.cambiodolar) AS total_iva,
        go.isr,
        IF(go.moneda='MXN',go.total_isr,go.total_isr* go.cambiodolar) AS total_isr,
        IF(go.moneda='MXN',go.total,go.total* go.cambiodolar) AS totalenpesos,go.descripcion
        FROM gastosOperativos AS go
        INNER JOIN cuentas AS cu ON cu.idcuenta = go.idcuenta
        WHERE fecha_pago >='$this->fechaInicio' AND fecha_pago <='$this->fechaFinal' AND go.activo = 'Si' AND go.empresa = '$this->empresa' AND
        go.etiquetas = 'GASTO ADMINISTRATIVO' ORDER BY fecha_pago ASC"); 

        $gastoVenta = DB::select("SELECT go.fecha_pago,go.nombreGasto,go.beneficiario,CONCAT(cu.numeroCu,' ',cu.nombreCuenta) AS
        cuentacontable,go.forma_pago,go.referencia,go.etiquetas,
        go.moneda,
        IF(go.moneda='MXN',go.factura,go.factura * go.cambiodolar) AS subtotal,
        go.iva,
        IF(go.moneda='MXN',go.total_iva,go.total_iva* go.cambiodolar) AS total_iva,
        go.isr,
        IF(go.moneda='MXN',go.total_isr,go.total_isr* go.cambiodolar) AS total_isr,
        IF(go.moneda='MXN',go.total,go.total* go.cambiodolar) AS totalenpesos,go.descripcion
        FROM gastosOperativos AS go
        INNER JOIN cuentas AS cu ON cu.idcuenta = go.idcuenta
        WHERE fecha_pago >='$this->fechaInicio' AND fecha_pago <='$this->fechaFinal' AND go.activo = 'Si' AND go.empresa = '$this->empresa' AND
        go.etiquetas = 'GASTO DE VENTA' ORDER BY fecha_pago ASC"); 
        
        $gastoFinan = DB::select("SELECT go.fecha_pago,go.nombreGasto,go.beneficiario,CONCAT(cu.numeroCu,' ',cu.nombreCuenta) AS
        cuentacontable,go.forma_pago,go.referencia,go.etiquetas,
        go.moneda,
        IF(go.moneda='MXN',go.factura,go.factura * go.cambiodolar) AS subtotal,
        go.iva,
        IF(go.moneda='MXN',go.total_iva,go.total_iva* go.cambiodolar) AS total_iva,
        go.isr,
        IF(go.moneda='MXN',go.total_isr,go.total_isr* go.cambiodolar) AS total_isr,
        IF(go.moneda='MXN',go.total,go.total* go.cambiodolar) AS totalenpesos,go.descripcion
        FROM gastosOperativos AS go
        INNER JOIN cuentas AS cu ON cu.idcuenta = go.idcuenta
        WHERE fecha_pago >='$this->fechaInicio' AND fecha_pago <='$this->fechaFinal' AND go.activo = 'Si' AND go.empresa = '$this->empresa' AND
        go.etiquetas = 'GASTO FINANCIERO' ORDER BY fecha_pago ASC"); 
        
        $impuestos = DB::select("SELECT go.fecha_pago,go.nombreGasto,go.beneficiario,CONCAT(cu.numeroCu,' ',cu.nombreCuenta) AS
        cuentacontable,go.forma_pago,go.referencia,go.etiquetas,
        go.moneda,
        IF(go.moneda='MXN',go.factura,go.factura * go.cambiodolar) AS subtotal,
        go.iva,
        IF(go.moneda='MXN',go.total_iva,go.total_iva* go.cambiodolar) AS total_iva,
        go.isr,
        IF(go.moneda='MXN',go.total_isr,go.total_isr* go.cambiodolar) AS total_isr,
        IF(go.moneda='MXN',go.total,go.total* go.cambiodolar) AS totalenpesos,go.descripcion
        FROM gastosOperativos AS go
        INNER JOIN cuentas AS cu ON cu.idcuenta = go.idcuenta
        WHERE fecha_pago >='$this->fechaInicio' AND fecha_pago <='$this->fechaFinal' AND go.activo = 'Si' AND go.empresa = '$this->empresa' AND
        go.etiquetas = 'IMPUESTOS' ORDER BY fecha_pago ASC"); 

        $interes = DB::select("SELECT go.fecha_pago,go.nombreGasto,go.beneficiario,CONCAT(cu.numeroCu,' ',cu.nombreCuenta) AS
        cuentacontable,go.forma_pago,go.referencia,go.etiquetas,
        go.moneda,
        IF(go.moneda='MXN',go.factura,go.factura * go.cambiodolar) AS subtotal,
        go.iva,
        IF(go.moneda='MXN',go.total_iva,go.total_iva* go.cambiodolar) AS total_iva,
        go.isr,
        IF(go.moneda='MXN',go.total_isr,go.total_isr* go.cambiodolar) AS total_isr,
        IF(go.moneda='MXN',go.total,go.total* go.cambiodolar) AS totalenpesos,go.descripcion
        FROM gastosOperativos AS go
        INNER JOIN cuentas AS cu ON cu.idcuenta = go.idcuenta
        WHERE fecha_pago >='$this->fechaInicio' AND fecha_pago <='$this->fechaFinal' AND go.activo = 'Si' AND go.empresa = '$this->empresa' AND
        go.etiquetas = 'INTERES' ORDER BY fecha_pago ASC");

        $this->numCostoVenta = count($costoVenta);
        $this->numGastoAdmin = count($gastoAdmin);
        $this->numGastoVenta = count($gastoVenta);
        $this->numGastoFinan = count($gastoFinan);
        $this->numImpuestos = count($impuestos);
        $this->numInteres = count($interes);

        // $resultados = DB::select("SELECT t2.ventas,t2.costodeventa,t2.utilidadbruta,t2.gastosdeoperacion,t2.gastodeventa,t2.gastoadministrativo,t2.utilidaddeoperacion,t2.CIF,t2.utilidadantesdeimpuestos,
        // t2.utilidadantesdeimpuestos * .30 AS ISR30, t2.utilidadantesdeimpuestos * .10 AS PTU10,
        // t2.utilidadantesdeimpuestos - (t2.utilidadantesdeimpuestos * .30) -  (t2.utilidadantesdeimpuestos * .10) AS utilidadneta
        // FROM(SELECT t1.ventas,t1.costodeventa,t1.ventas-t1.costodeventa AS utilidadbruta,t1.gastodeventa+t1.gastoadministrativo AS gastosdeoperacion,
        // t1.gastodeventa,t1.gastoadministrativo,(t1.ventas-t1.costodeventa) - (t1.gastodeventa+t1.gastoadministrativo) AS utilidaddeoperacion,
        // t1.gastofinanciero + t1.impuestos + t1.interes AS CIF,
        // ((t1.ventas-t1.costodeventa) - (t1.gastodeventa+t1.gastoadministrativo)) - (t1.gastofinanciero + t1.impuestos + t1.interes) AS utilidadantesdeimpuestos
        // FROM(SELECT totalfacturado('$this->fechaInicio','$this->fechaFinal','$this->empresa') AS ventas,
        // SUM(IF(go.etiquetas='COSTO DE VENTA', go.factura,0)) AS COSTODEVENTA,
        // SUM(IF(go.etiquetas='GASTO ADMINISTRATIVO', go.factura,0)) AS GASTOADMINISTRATIVO,
        // SUM(IF(go.etiquetas='GASTO DE VENTA', go.factura,0)) AS GASTODEVENTA,
        // SUM(IF(go.etiquetas='GASTO FINANCIERO', go.factura,0) )AS GASTOFINANCIERO,
        // SUM(IF(go.etiquetas='IMPUESTOS', go.factura,0)) AS IMPUESTOS,
        // SUM(IF(go.etiquetas='INTERES', go.factura,0)) AS INTERES FROM gastosOperativos AS go
        // INNER JOIN cuentas AS cu ON cu.idcuenta = go.idcuenta
        // WHERE fecha_pago >='$this->fechaInicio' AND fecha_pago <='$this->fechaFinal' AND go.activo = 'Si' AND go.empresa = '$this->empresa'
        // ORDER BY fecha_pago ASC) AS t1) AS t2");
        
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

        return view('gastosOperativosTodos', [
            'costoVenta' => $costoVenta,
            'gastoAdmin' => $gastoAdmin,
            'gastoVenta' => $gastoVenta,
            'gastoFinan' => $gastoFinan,
            'impuestos' => $impuestos,
            'interes' => $interes,
            'resultados' => $resultados,
            'periodo' => $periodo,
        ]);
    }   


    // public function columnFormats(): array
    // {
    //     return [
    //         'A' => NumberFormat::FORMAT_DATE_DDMMYYYY, 
    //         'I'=> NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
    //         'K'=> NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
    //         'M'=> NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
    //         'N'=> NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
    //     ];
    // }

    public function styles(Worksheet $sheet)
{

    // $sheet->getStyle('A')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);
    $sheet->getStyle('I')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);
    $sheet->getStyle('K')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);
    $sheet->getStyle('M')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);
    $sheet->getStyle('N')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);
    // $sheet->getStyle('J')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
    // $sheet->getStyle('L')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);    


    return [
        // Estilo para las tablas
        'A' . ($this->numCostoVenta + 5) . ':O' . ($this->numCostoVenta + 5) => [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, // Grosor grueso
                    'color' => ['rgb' => 'CCC'],
                ],
            ],
        ],

        'A' . ($this->numGastoAdmin + $this->numCostoVenta + 8) . ':O' . ($this->numGastoAdmin + $this->numCostoVenta + 8) => [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, // Grosor grueso
                    'color' => ['rgb' => 'CCC'],
                ],
            ],
        ],

        'A' . ($this->numGastoVenta + $this->numGastoAdmin + $this->numCostoVenta + 11) . ':O' . ($this->numGastoVenta + $this->numGastoAdmin + $this->numCostoVenta + 11) => [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, // Grosor grueso
                    'color' => ['rgb' => 'CCC'],
                ],
            ],
        ],

        'A' . ($this->numGastoFinan + $this->numGastoVenta + $this->numGastoAdmin + $this->numCostoVenta + 14) . ':O' . ($this->numGastoFinan + $this->numGastoVenta + $this->numGastoAdmin + $this->numCostoVenta + 14) => [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, // Grosor grueso
                    'color' => ['rgb' => 'CCC'],
                ],
            ],
        ],

        'A' . ($this->numImpuestos + $this->numGastoFinan + $this->numGastoVenta + $this->numGastoAdmin + $this->numCostoVenta + 17) . ':O' . ($this->numImpuestos + $this->numGastoFinan + $this->numGastoVenta + $this->numGastoAdmin + $this->numCostoVenta + 17) => [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, // Grosor grueso
                    'color' => ['rgb' => 'CCC'],
                ],
            ],
        ],

        'A' . ($this->numInteres + $this->numImpuestos + $this->numGastoFinan + $this->numGastoVenta + $this->numGastoAdmin + $this->numCostoVenta + 20) . ':O' . ($this->numInteres + $this->numImpuestos + $this->numGastoFinan + $this->numGastoVenta + $this->numGastoAdmin + $this->numCostoVenta + 20) => [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, // Grosor grueso
                    'color' => ['rgb' => 'CCC'],
                ],
            ],
        ],        
    ];
}


    public function title(): string
    {
        return 'GASTOS';
    }     
}
