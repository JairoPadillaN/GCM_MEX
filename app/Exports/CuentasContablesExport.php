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

class CuentasContablesExport implements FromView, ShouldAutoSize
{

    public function __construct( $data, $user ){
        $this->data = $data;
        
    }

    public function query(){

            $query = \DB::table('REPORTEGASTO as rg')
                ->select('rg.id','rg.clavegasto','rg.fechasalida','rg.cuentagasto','rg.tipo','rg.beneficiario','rg.formaPago','rg.subTotal','rg.ivaTotal','rg.isrTotal','rg.total','rg.divisa','rg.totalpesos','rg.empresa')
                ->whereIn('rg.id',$this->data)
                ->get();
             
            

        $consulta = $this->json( $query );

        return $consulta;
    }

    public function sumaTotales(){ 
        
        $sumaTotales = \DB::table('REPORTEGASTO as rg')
                ->select(
                    DB::raw('CONCAT("MXN", " $", FORMAT(SUM(rg.totalpesos),2)) AS total')
                )
                ->whereIn('rg.id',$this->data)
                ->get();

        return $sumaTotales;

    }


    public function json ( $query){

        $consult = array();
        foreach( $query as $value)
        {
            array_push( $consult, array(
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
                'l'    => $value->divisa, 
                'm'    => $value->empresa, 
            ) );

        }

        return $consult;
    }

    public function view(): View
    {
        $data = $this->query();
        $suma = $this->sumaTotales();
        

        return view( 'excelCuentasContables', compact( 'data','suma') );
    }


}
