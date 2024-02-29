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
use Session;

use App\usuarios as User;

class XdnExport implements FromView, ShouldAutoSize, WithStyles, WithColumnWidths, WithDrawings
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
        $consultaEmpresa = User::select('empresaSeguimiento')->find($this->user);


        if( $consultaEmpresa->empresaSeguimiento === "GCMyCYM"  || $stipo == 'Administrador' || $stipo == 'Vendedor'  )
        {

            $query = DB::table( 'recepcionEquipos as r' )
                        ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                        ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                        ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                        ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                        ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                        ->select(
                            'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                            'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo',
                            DB::raw(' SUBSTR( r.datosCertificado, 1, 100) as datos '),
                            DB::raw(' COUNT( p.idParte ) as canti '),
                            DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                            DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                            DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                            DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '), 
                            DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                        )
                        ->whereIn( 'r.idEquipos', $this->data )
                        ->groupBy(
                                    'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 
                                    'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                    'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                    'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                        )
                        ->orderBy( 'r.idEquipos', 'DESC' )

                        ->get();

            $consulta = $this->json( $query );

            return $consulta;

        }

        $empresa = User::select( 'empresaSeguimiento' )->find( $sidu );

        $query = DB::table( 'recepcionEquipos as r' )
                        ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                        ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                        ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                        ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                        ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                        ->select(
                            'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                            'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega','ubicacionEquipo',
                            DB::raw(' SUBSTR( r.datosCertificado, 1, 100) as datos '),
                            DB::raw(' COUNT( p.idParte ) as canti '),
                            DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                            DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                            DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                            DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '), 
                            DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                        )
                        ->whereIn( 'r.nombreEmpresa', [ $empresa->empresaSeguimiento ] )
                        ->whereIn( 'r.idEquipos', $this->data )
                        ->groupBy(
                                    'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 
                                    'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                    'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                    'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                        )
                        ->orderBy( 'r.idEquipos', 'DESC' )
                        ->get();

        $consulta = $this->json( $query );

        return $consulta;
    }

    public function values ( $value, $type )
    {
        if ( $type == 1 )
        {
            $val = ( $value == 'Sin archivo' || $value == null || $value == '' ) ? '' : $value;

            $image = ( \File::exists('public/archivos/'.$value) ) ? $value : '';
            
            return $image;
        
        }
        else
        {
            $alta  = 'circulo-rojo-png-4.png';
            $media = 'circulo-amarillo.png';
            $baja  = 'circulo-verde.png';

            if( $value == 'Alta' ) 
            {
                $val = $alta;
            }
            if( $value == 'Media' )
            {
                $val = $media;
            }
            if( $value == 'Baja' )
            {
                $val = $baja;
            }

            return $val;
        }
    }

    public function json ( $query )
    {
        $consult = array();

        foreach( $query as $value )
        {
	        array_push( $consult, array(
                'a'     => $this->values( $value->vistaSuperior, 1 ),
                'b'     => $this->values( $value->vistaFrente, 1 ),
                'c'     => $this->values( $value->placa_1, 1 ),
                'd'     => $value->folioRecepcion,
                'e'     => $value->gcmid,
                'f'     => $value->fecha,
                'g'     => $value->cliSuc,
                'h'     => $value->datos,
                'i'     => $value->equipo,
                'j'     => $value->marcaModelo,
                'k'     => $this->values( $value->importancia, 2 ),
                'l'     => $value->estatus,
                'm'     => $value->estatusEntrega,
                'n'     => $value->canti,
                'q'     => $value->ubicacionEquipo,
            ) );

        }

        return $consult;
    }

    public function view(): View
    {
        $data = $this->query();

        return view( 'tablaExcelEquipos', compact( 'data' ) );
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
        $sheet->getStyle( 'J3' )->getAlignment()->setVertical( \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER )->setWrapText( true );
        $sheet->getStyle( 'K3' )->getAlignment()->setVertical( \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER )->setWrapText( true );
        $sheet->getStyle( 'L3' )->getAlignment()->setVertical( \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER )->setWrapText( true );
        $sheet->getStyle( 'M3' )->getAlignment()->setVertical( \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER )->setWrapText( true );
        $sheet->getStyle( 'N3' )->getAlignment()->setVertical( \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER )->setWrapText( true );

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

            if( $image['c'] != null || $image['c'] != '')
            {

                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('This is my logo');
                $drawing->setPath(public_path('/archivos/'.$image['c']));
                $drawing->setHeight(50);
                $drawing->setCoordinates(cells($key,"C"));

                array_push($array, $drawing);
            }

            if( $image['k'] != null || $image['k'] != '')
            {

                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('This is my logo');
                $drawing->setPath(public_path('/archivos/'.$image['k']));
                $drawing->setHeight(50);
                $drawing->setCoordinates(cells($key,"K"));

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
            'C' => 30,
            'K' => 20,
        ];
    }

} 
