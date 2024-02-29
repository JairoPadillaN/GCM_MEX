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

class UtilidadServicioExport implements FromView, ShouldAutoSize
{

    public function __construct( $data, $user ){
        $this->data = $data;
        
    }

    public function query(){
  
            
            $query = \DB::table('UTILIDADSERVICIO')
            ->select('idfactura',
            'clavegasto',
            'fechasalida',
            'cuentagasto',
            'tipo',
            'beneficiario',
            'formaPago',
            'subTotal',
            'moneda',
            'ivaTotal',
            'isrTotal',
            'total',
            'divisa',
            'totalpesos')
            ->whereIn('idfactura',$this->data)
            ->get();
            

        $consulta = $this->json( $query );

        return $consulta;
    }

    public function json ( $query){

        $consult = array();
        foreach( $query as $value)
        {
            array_push( $consult, array(
                'id'   => $value->idfactura,
                'a'    => $value->clavegasto, 
                'b'    => $value->fechasalida, 
                'c'    => $value->cuentagasto, 
                'd'    => $value->tipo, 
                'e'    => $value->beneficiario, 
                'f'    => $value->formaPago, 
                'g'    => $value->subTotal, 
                'h'    => $value->ivaTotal, 
                'i'    => $value->isrTotal, 
                'j'    => $value->total, 
                'k'    => $value->totalpesos,  
            ) );

        }

        return $consult;
    }

    public function view(): View
    {
        $data = $this->query();
        // $suma = $this->sumaTotales();
        // dd($data);

        return view( 'excelUtilidadServicio', compact( 'data') );
    }


}
