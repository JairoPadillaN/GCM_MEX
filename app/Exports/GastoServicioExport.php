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

class GastoServicioExport implements FromView, ShouldAutoSize
{

    public function __construct( $data, $user ){
        $this->data = $data;
        
    }

    public function query(){

            $query = \DB::table('gastos')
                ->select(
                'id', 
                'id_factura', 
                'nombreGasto', 
                'beneficiario', 
                'fecha_pago', 
                'metodo_pago', 
                'forma_pago', 
                'referencia', 
                'etiquetas', 
                'factura', 
                'iva', 
                'total_iva', 
                'isr', 
                'total_isr', 
                'total', 
                'descripcion', 
                'moneda', 
                'cambiodolar',
                DB::raw('IF(moneda= "USD", total * cambiodolar, total) AS totalpesos'))
                ->whereIn('id_factura',$this->data)
                ->get();              
            

        $consulta = $this->json( $query );

        return $consulta;
    }

    public function json ( $query){

        $consult = array();
        foreach( $query as $value)
        {
            array_push( $consult, array(
                'id'   => $value->id_factura,
                'a'    => $value->fecha_pago, 
                'b'    => $value->beneficiario, 
                'c'    => $value->forma_pago, 
                'd'    => $value->referencia, 
                'e'    => $value->etiquetas, 
                'f'    => $value->descripcion, 
                'g'    => $value->factura, 
                'h'    => $value->iva, 
                'i'    => $value->total_iva, 
                'j'    => $value->isr, 
                'k'    => $value->total_isr,
                'l'    => $value->total,
                'm'    => $value->totalpesos,
            ) );

        }

        return $consult;
    }

    public function view(): View
    {
        $data = $this->query();
        
        return view( 'excelGastosServicio', compact( 'data') );
    }


}
