<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteEmpresaExport implements FromView, ShouldAutoSize, WithTitle, WithStyles
{
    private $fecha;    
    private $empresa;
    private $cuantos;

    public function __construct($fecha, $empresa )
    {
        $this->fecha = $fecha;        
        $this->empresa = $empresa;
        $this->cuantos = 0;
    }
    public function view(): View
    {              
        $ventasempresa = DB::select("SELECT c.razonsocial,cadenavendedoresreporte(f.idfactura) AS vendedores,f.nombreEmpresa,
        SUM(IF(MONTH(dps.fechafactura) = 1,dps.subtotalfac,0)) AS 'Enero',
        SUM(IF(MONTH(dps.fechafactura) = 2,dps.subtotalfac,0))AS 'Febrero',
        SUM(IF(MONTH(dps.fechafactura) = 3,dps.subtotalfac,0)) AS 'Marzo',
        SUM(IF(MONTH(dps.fechafactura) = 4,dps.subtotalfac,0))AS 'Abril',
        SUM(IF(MONTH(dps.fechafactura) = 5,dps.subtotalfac,0)) AS 'Mayo',
        SUM(IF(MONTH(dps.fechafactura) = 6,dps.subtotalfac,0)) AS 'Junio',
        SUM(IF(MONTH(dps.fechafactura) = 7,dps.subtotalfac,0)) AS 'Julio',
        SUM(IF(MONTH(dps.fechafactura) = 8,dps.subtotalfac,0)) AS 'Agosto',
        SUM(IF(MONTH(dps.fechafactura) = 9,dps.subtotalfac,0)) AS 'Septiembre',
        SUM(IF(MONTH(dps.fechafactura) = 10,dps.subtotalfac,0)) AS 'Octubre',
        SUM(IF(MONTH(dps.fechafactura) = 11,dps.subtotalfac,0)) AS 'Noviembre',
        SUM(IF(MONTH(dps.fechafactura) = 12,dps.subtotalfac,0)) AS 'Diciembre'
        FROM facturas AS f
        LEFT JOIN datosPagoServicios AS dps ON dps.idfactura = f.idfactura
        INNER JOIN clientes AS c ON c.idc = f.idc
        WHERE f.activo = 'si' AND f.nombreEmpresa='$this->empresa' AND 
        YEAR(dps.fechafactura) = '$this->fecha'
        GROUP BY c.razonsocial,vendedores ,f.nombreEmpresa
        ORDER BY c.razonsocial ASC");    
        
        $cantidad = is_array($ventasempresa) ? count($ventasempresa) : 0;
        $this->cuantos = $cantidad + 3;
        
        return view('ventasEmpresa', [
            'ventasempresa' => $ventasempresa,                                 
        ]);
    }

    public function styles(Worksheet $sheet)
    {    
    $tableRange = 'A3:O' . $this->cuantos;

    $sheet->getStyle('D')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);           
    $sheet->getStyle('E')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);           
    $sheet->getStyle('F')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);           
    $sheet->getStyle('G')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);           
    $sheet->getStyle('H')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);           
    $sheet->getStyle('I')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);           
    $sheet->getStyle('J')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);           
    $sheet->getStyle('K')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);           
    $sheet->getStyle('L')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);           
    $sheet->getStyle('M')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);           
    $sheet->getStyle('N')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);           
    $sheet->getStyle('O')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);    

    return [   
        $tableRange => [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Grosor grueso
                    'color' => ['rgb' => '000000'], // Color del borde
                ],
            ],
        ],             
    ];
}


    public function title(): string
    {
        return 'Reporte por Empresa';
    } 
}
