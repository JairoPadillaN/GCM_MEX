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

class ReporteCuentaContableExport implements FromView, ShouldAutoSize, WithTitle, WithStyles
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
        $reporteGastos = DB::select("SELECT CONCAT(cu.numeroCu,' ',cu.nombreCuenta) AS cuentacontable,
        SUM(IF(go.moneda='MXN',go.factura,go.factura * go.cambiodolar)) AS subtotal,
        SUM(IF(go.moneda='MXN',go.total_iva,go.total_iva* go.cambiodolar)) AS total_iva,
        SUM(IF(go.moneda='MXN',go.total_isr,go.total_isr* go.cambiodolar))AS total_isr,
        SUM(IF(go.moneda='MXN',go.total,go.total* go.cambiodolar)) AS totalenpesos
        FROM gastosOperativos AS go
        INNER JOIN cuentas AS cu ON cu.idcuenta = go.idcuenta
        WHERE fecha_pago >='$this->fechaInicio' AND fecha_pago <='$this->fechaFinal' AND go.activo = 'Si' AND go.empresa = '$this->empresa' 
        GROUP BY cuentacontable
        ORDER BY cuentacontable ASC");    

        $subtotal = DB::select("SELECT SUM(IF(go.moneda='MXN',go.factura,go.factura * go.cambiodolar)) AS subtotal
        FROM gastosOperativos AS go
        INNER JOIN cuentas AS cu ON cu.idcuenta = go.idcuenta
        WHERE fecha_pago >='$this->fechaInicio' AND fecha_pago <='$this->fechaFinal' AND go.activo = 'Si' AND go.empresa = '$this->empresa'"); 

        // dd($subtotal);
        $periodo=[$this->fechaInicio,$this->fechaFinal];
    
    
    return view('reportePorCuentaContable', [
        'reporteGastos' => $reporteGastos,            
        'subtotal' => $subtotal,            
        'periodo' => $periodo,
    ]);
    }

    public function styles(Worksheet $sheet)
    {    
    // $tableRange = 'A5:E20';
    // $table2Range = 'A21:B21';

    $sheet->getStyle('B')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);       
    $sheet->getStyle('C')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);       
    $sheet->getStyle('D')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);       
    $sheet->getStyle('E')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);           
    
    return [
        // $tableRange => [
        //     'borders' => [
        //         'allBorders' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //             'color' => ['rgb' => '000000'], // Color del borde
        //         ],
        //     ],
        // ],
        // $table2Range => [
        //     'borders' => [
        //         'allBorders' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //             'color' => ['rgb' => '000000'], // Color del borde
        //         ],
        //     ],
        // ],        
    ];
}

public function title(): string
    {
        return 'REPORTE POR CUENTA CONTABLE';
    }
}
