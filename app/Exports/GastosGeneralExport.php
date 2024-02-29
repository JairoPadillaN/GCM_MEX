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

class GastosGeneralExport implements FromView, ShouldAutoSize
{

    public function __construct( $data, $user ){
        $this->data = $data;
        
    }

    public function query(){            
            
            $query = \DB::table('REPORTEGASTOSGENERAL')
                ->select('id_factura',
                'folioServicio',
                'numFactura',
                'nombregasto',
                'beneficiario',
                'empresa',
                'nombrecuenta',
                'razonsocial',
                'sucursal',
                'fecha_pago',
                'referencia',
                'moneda',
                'factura',
                'TotalIva',
                'IVAMXN',
                'TotalISR',
                'ISRMXN',
                'MontoTotal',
                'TotalMXN')
                ->whereIn('id_factura', $this->data)
                ->get();
            

        $consulta = $this->json( $query );

        return $consulta;
    }

    public function sumaTotalGastos(){ 

        $sumaTotalGastos = \DB::table('REPORTEGASTOSGENERAL')
        ->select(
            DB::raw('CONCAT(" $", FORMAT(SUM(IVAMXN),2)) AS IVAMXN'),
            DB::raw('CONCAT(" $", FORMAT(SUM(ISRMXN),2)) AS ISRMXN'), 
            DB::raw('CONCAT(" $", FORMAT(SUM(TotalMXN),2)) AS TotalMXN'),
        )
        ->whereIn('id_factura',$this->data)
        ->get();

        return $sumaTotalGastos;

    }


    public function json ( $query){

        $consult = array();
        foreach( $query as $value)
        {
            array_push( $consult, array(
                'id'   => $value->id_factura,
                'a'    => $value->folioServicio, 
                'b'    => $value->numFactura, 
                'c'    => $value->nombregasto, 
                'd'    => $value->beneficiario, 
                'e'    => $value->empresa, 
                'f'    => $value->nombrecuenta, 
                'g'    => $value->razonsocial, 
                'h'    => $value->sucursal, 
                'i'    => $value->fecha_pago, 
                'j'    => $value->referencia, 
                'k'    => $value->moneda,
                'l'    => $value->factura,
                'm'    => $value->TotalIva,
                'n'    => $value->IVAMXN,
                'o'    => $value->TotalISR,
                'p'    => $value->ISRMXN,
                'q'    => $value->MontoTotal,
                'r'    => $value->totalMXN,  

            ) );

        }

        return $consult;
    }

    public function view(): View
    {
        $data = $this->query();
        $suma = $this->sumaTotalGastos();
        // dd($data);

        return view( 'excelGastosGeneral', compact( 'data','suma') );
    }


}