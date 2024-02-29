<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteGeneralDeGastosExport implements FromView, ShouldAutoSize, WithTitle, WithStyles
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
        $reporteGastosGral = DB::select("SELECT go.fecha_pago,go.nombreGasto,go.beneficiario,CONCAT(cu.numeroCu,' ',cu.nombreCuenta) 
        AS cuentacontable,go.forma_pago,go.referencia,go.etiquetas,go.moneda,
        IF(go.moneda='MXN',go.factura,go.factura * go.cambiodolar) AS subtotal,
        go.iva,
        IF(go.moneda='MXN',go.total_iva,go.total_iva* go.cambiodolar) AS total_iva,
        go.isr,
        IF(go.moneda='MXN',go.total_isr,go.total_isr* go.cambiodolar) AS total_isr,
        IF(go.moneda='MXN',go.total,go.total* go.cambiodolar) AS totalenpesos,go.descripcion
        FROM gastosOperativos AS go
        INNER JOIN cuentas AS cu ON cu.idcuenta = go.idcuenta
        WHERE fecha_pago >='$this->fechaInicio' AND fecha_pago <='$this->fechaFinal' AND go.activo = 'Si' AND go.empresa = '$this->empresa' 
        ORDER BY etiquetas,fecha_pago ASC");    

        
        // dd($subtotal);
        $periodo=[$this->fechaInicio,$this->fechaFinal];
    
    
    return view('reporteGeneralDeGastos', [
        'reporteGastosGral' => $reporteGastosGral,            
        'periodo' => $periodo,
    ]);
    }

    public function styles(Worksheet $sheet)
    {        

    $sheet->getStyle('I')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);       
    $sheet->getStyle('K')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);       
    $sheet->getStyle('M')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);       
    $sheet->getStyle('J')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);         
    $sheet->getStyle('L')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);     
    return [            
    ];
}

public function title(): string
    {
        return 'REPORTE GENERAL DE GASTOS';
    }
}
{
    /**
    * @return \Illuminate\Support\Collection
    */    
}
