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

class EquiposPorTecnicoExport implements FromView, ShouldAutoSize
{

    public function __construct( $data, $user ){
        $this->data = $data;
        
    }

    public function query(){

            $query = \DB::table('REPORTEEQUIPOSPORTECNICO as rspt')
                ->select('rspt.idequipos','rspt.gcmid','rspt.gcmidparte','rspt.nombreparte','rspt.fecharegistro','rspt.fechasalida','rspt.sucursal','rspt.razonsocial','rspt.marca','rspt.modelo','rspt.serie','rspt.ultimacot','rspt.numerocotizacion','rspt.busquedaservicio')
                ->whereIn('rspt.idequipos',$this->data)
                ->get();
            
            // dd($query);                    
            

        $consulta = $this->json( $query );

        return $consulta;
    }



    public function json ( $query){

        $consult = array();
        foreach( $query as $value)
        {
            array_push( $consult, array(
                'id'    => $value->idequipos,
                'a'    => $value->gcmid,
                'b'    => $value->gcmidparte, 
                'c'    => $value->nombreparte,
                'd'    => $value->fecharegistro,
                'e'    => $value->fechasalida, 
                'f'    => $value->sucursal, 
                'g'    => $value->razonsocial, 
                'h'    => $value->marca, 
                'i'    => $value->modelo,
                'j'    => $value->serie,
                'k'    => $value->ultimacot,
                'l'    => $value->numerocotizacion, 
                'm'    => $value->busquedaservicio, 
                
                 
                 
                
                
            ) );

        }

        return $consult;
    }

    public function view(): View
    {
        $data = $this->query();
        return view( 'excelEquiposPorTecnico', compact( 'data') );
    }


}
