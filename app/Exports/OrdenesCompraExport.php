<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;                                                                                   
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrdenesCompraExport implements FromView, ShouldAutoSize
{

    public function __construct($registros,$sumaTotales){
        $this->data = $registros;
        $this->sumaTotales = $sumaTotales;
    }

    public function json ($registros){
        $consulta = array();
        foreach( $registros as $value){
            array_push( $consulta, array(
                'idOrden' => $value->idOrden,
                'codigoOrden' => $value->codigoOrden,
                'fecha_formato' => $value->fecha_formato,
                'razonSocialProv' => $value->razonSocialProv,
                'nombreEmpresa' => $value->nombreEmpresa,
                'importeOrden' => $value->importeOrden,
                'iva' => $value->iva,
                'isr' => $value->isr,
                'retencion' => $value->retencion,
                'totalOrden' => $value->totalOrden,
                'totalMXN' => $value->totalMXN,
                'estatus' => $value->estatus,
                'totalFinalPagado' => $value->totalFinalPagado,
            ) );
        }
        return $consulta;
    }

    public function view(): View
    {
        $datos = $this->json($this->data);
        $sumaTotales = $this->sumaTotales;

        return view('excelOrdenCompra',compact( 'datos','sumaTotales'));
    }


}
