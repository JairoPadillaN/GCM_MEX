<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;                                                                                   
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;                                  

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use App\usuarios as User;

class EquiposTallerExport implements FromView, ShouldAutoSize, WithStyles, WithColumnWidths, WithDrawings
{
    public function __construct( $data, $user )
    {

        $this->data = $data;
        $this->user = $user;

    }

    public function query()
    {
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

        $query = DB::table('partesreparacion')            
        ->select('idParte','recepcionEquipos.vistaSuperior','fotoParte','GCMidParte','nombreParte','queReparacion','nombreEstatus',
        DB::raw('CONCAT_WS("-",marca,modelo) AS marcaModelo'),
        DB::raw('DATE_FORMAT(fechaRecepcion,"%d %b %Y") AS fechaRecepcion'),
        DB::raw('DATE_FORMAT(fechaEntrega,"%d %b %Y") AS fechaEntrega'))      
        ->leftJoin('recepcionEquipos', 'partesreparacion.idEquipos', '=', 'recepcionEquipos.idEquipos')                
        ->leftJoin('estatusreparaciones', 'partesreparacion.idEstatus', '=', 'estatusreparaciones.idEstatus')
        ->whereIn( 'idParte', $this->data )
        ->orderBy('idParte', 'desc')
        ->get();

            $consulta = $this->json( $query );

            return $consulta;

    }

    public function values ( $value, $type )
    {
        
            $val = ( $value == 'Sin archivo' || $value == null || $value == '' ) ? '' : $value;

            $image = ( \File::exists('public/archivos/'.$value) ) ? $value : '';
            
            return $image;
        
        
    }

    public function json ( $query )
    {
        $consult = array();

        foreach( $query as $value )
        {
          array_push( $consult, array(
                'a'     => $this->values( $value->vistaSuperior, 1),
                'b'     => $this->values( $value->fotoParte, 1),
                'c'     => $value->GCMidParte,
                'd'     => $value->marcaModelo,
                'e'     => $value->nombreParte,
                'f'     => $value->queReparacion,
                'g'     => $value->fechaRecepcion==''?"Sin fecha":$value->fechaRecepcion,
                'h'     => $value->fechaEntrega==''?"Sin fecha":$value->fechaEntrega,
                'i'     => $value->nombreEstatus,
            ) );
  
        }
  
        return $consult;
    }

    public function view(): View
    {
        $data = $this->query();

        return view( 'tablaExcelTaller', compact( 'data' ) );
    }

    public function styles ( Worksheet $sheet )
    {
        $data = $this->data;

        $sheet->getRowDimension( 1 )->setRowHeight( 30 );
        $sheet->getRowDimension( 2 )->setRowHeight( 30 );

        for ( $i = 0; $i <= count( $data ); $i++ )
        {
            $sheet->getRowDimension( $i + 3  )->setRowHeight( 50 );
        }

        $sheet->getStyle( 'A3' )->getAlignment()->setVertical( \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER )->setWrapText( true );
        $sheet->getStyle( 'B3' )->getAlignment()->setVertical( \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER )->setWrapText( true );
        $sheet->getStyle( 'C3' )->getAlignment()->setVertical( \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER )->setWrapText( true );
        $sheet->getStyle( 'D3' )->getAlignment()->setVertical( \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER )->setWrapText( true );
        $sheet->getStyle( 'E3' )->getAlignment()->setVertical( \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER )->setWrapText( true );
        $sheet->getStyle( 'F3' )->getAlignment()->setVertical( \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER )->setWrapText( true );
        $sheet->getStyle( 'G3' )->getAlignment()->setVertical( \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER )->setWrapText( true );
        $sheet->getStyle( 'H3' )->getAlignment()->setVertical( \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER )->setWrapText( true );
        $sheet->getStyle( 'I3' )->getAlignment()->setVertical( \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER )->setWrapText( true );

    }
    
    public function drawings()
    {
        $images = $this->query();

        $array = array();

        function cells( $value, $column )
        {
            $val = intval($value) + 4;
            $cel = $column.$val;
            
            return $cel;
        }

        foreach($images as $key => $image)
        {
            if( $image['a'] != null || $image['a'] != '' )
            {
                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('This is my logo');
                $drawing->setPath(public_path('/archivos/'.$image['a']));
                $drawing->setHeight(50);
                $drawing->setCoordinates(cells($key,"A"));

                array_push( $array, $drawing );
            }

            if( $image['b'] != null || $image['b'] != '' )
            {

                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('This is my logo');
                $drawing->setPath(public_path('/archivos/'.$image['b']));
                $drawing->setHeight(50);
                $drawing->setCoordinates(cells($key,"B"));

                array_push($array, $drawing);
            }

        }

        return $array;
    }

    public function columnWidths() : array
    {
        return [
            'A' => 30,
            'B' => 30,
        ];
    }

} 
