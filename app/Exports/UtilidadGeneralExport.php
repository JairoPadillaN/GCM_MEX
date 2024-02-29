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

class UtilidadGeneralExport implements FromView, ShouldAutoSize
{

    public function __construct( $data, $user ){
        $this->data = $data;
        
    }

    public function query(){


            $query = \DB::table('REPORTEUTILIDADES3 as ru')
                ->select('ru.idfactura','ru.idservicios','ru.numerofactura','ru.fechafactura','ru.fechapago','ru.cliente','ru.sucursal','ru.montofactura','ru.montopesos','ru.totalgastos','ru.totaloc','ru.utilidad')
                ->whereIn('ru.idfactura',$this->data)
                // ->orWhere('rg.tipo',$this->data)
                ->get();
            
            // dd($query);                    
            

        $consulta = $this->json( $query );

        return $consulta;
    }

    public function sumaTotalUtilidad(){ 

        $sumaTotalUtilidad = \DB::table('REPORTEUTILIDADES3 as ru')
        ->select(
            DB::raw('CONCAT(" $", FORMAT(SUM(ru.totalgastos),2)) AS totalgastos'),
            DB::raw('CONCAT(" $", FORMAT(SUM(ru.totaloc),2)) AS totaloc'), 
            DB::raw('CONCAT(" $", FORMAT(SUM(ru.utilidad),2)) AS utilidad'),
        )
        ->whereIn('ru.idfactura',$this->data)
        ->get();

        return $sumaTotalUtilidad;

    }


    public function json ( $query){

        $consult = array();
        foreach( $query as $value)
        {
            array_push( $consult, array(
                'a'    => $value->idservicios, 
                'b'    => $value->numerofactura, 
                'c'    => $value->fechafactura, 
                'd'    => $value->fechapago, 
                'e'    => $value->cliente, 
                'f'    => $value->sucursal, 
                'g'    => $value->montofactura, 
                'h'    => $value->montopesos, 
                'i'    => $value->totalgastos, 
                'j'    => $value->totaloc, 
                'k'    => $value->utilidad,  
            ) );

        }

        return $consult;
    }

    public function view(): View
    {
        $data = $this->query();
        $suma = $this->sumaTotalUtilidad();
        // dd($data);

        return view( 'excelUtilidades', compact( 'data','suma') );
    }


}
