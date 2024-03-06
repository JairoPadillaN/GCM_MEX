<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DetalleFacturasExport implements FromView, ShouldAutoSize, WithTitle, WithStyles
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
        $detallefacturas = DB::select("SELECT f.idservicios,c.razonsocial,cadenavendedoresreporte(f.idfactura) AS vendedores,f.nombreEmpresa,dps.subtotalfac,dps.ivafac,dps.montopesosDP,dps.numeroFacturaDP,dps.fechafactura
        FROM facturas AS f
        LEFT JOIN datosPagoServicios AS dps ON dps.idfactura = f.idfactura
        INNER JOIN clientes AS c ON c.idc = f.idc
        WHERE f.activo = 'si' AND f.nombreEmpresa='$this->empresa' AND 
        YEAR(dps.fechafactura) = '$this->fecha'
        ORDER BY c.razonsocial ASC");
        
        $cantidad = is_array($detallefacturas) ? count($detallefacturas) : 0;
        $this->cuantos = $cantidad + 3;

        return view('detalleFacturas', [
            'detallefacturas' => $detallefacturas,                                 
        ]);
    }

    public function styles(Worksheet $sheet)
    {    
    $tableRange = 'A3:I' . $this->cuantos;
    $sheet->getStyle('E')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);           
    $sheet->getStyle('F')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);           
    $sheet->getStyle('G')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);           
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
        return 'Detalle de facturas';
    } 
}
