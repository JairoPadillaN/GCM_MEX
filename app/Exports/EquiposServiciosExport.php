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

class EquiposServiciosExport implements FromView, ShouldAutoSize
{

    public function __construct( $data, $user ){
        $this->data = $data;
        
    }

    public function query(){

            $query = \DB::table('REPORTEEQUIPOSERVICIO as rse')
                ->select('rse.idservicios','rse.factura','rse.fechafactura','rse.fechapago','rse.idcotizacion','rse.numerocotizacion','rse.razonsocial','rse.sucursal','rse.gcmid','rse.descripcion','rse.serie','rse.marca','rse.modelo','rse.ubicacionequipo','rse.tipoequipo','rse.subtipoequipo')
                ->whereIn('rse.gcmid',$this->data)
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
                'id'    => $value->idservicios,
                'a'    => $value->factura,
                'b'    => $value->idcotizacion, 
                'c'    => $value->numerocotizacion,
                'd'    => $value->fechafactura,
                'e'    => $value->fechapago, 
                'f'    => $value->razonsocial, 
                'g'    => $value->sucursal, 
                'h'    => $value->gcmid, 
                'i'    => $value->descripcion,
                'j'    => $value->tipoequipo,
                'k'    => $value->subtipoequipo,
                'l'    => $value->serie, 
                'm'    => $value->marca, 
                'n'    => $value->modelo, 
                'o'    => $value->ubicacionequipo, 
                 
                 
                
                
            ) );

        }

        return $consult;
    }

    public function view(): View
    {
        $data = $this->query();
        return view( 'excelEquiposServicios', compact( 'data') );
    }


}
