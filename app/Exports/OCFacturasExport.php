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

class OCFacturasExport implements FromView, ShouldAutoSize
{

    public function __construct( $data, $user ){
        $this->data = $data;
    }

    public function query(){
        $query= DB::table('ordenCompra as oc')
        ->select('oc.idOrden','oc.idFactura','f.idServicios','codigoOrden','oc.nombreEmpresa','estatus','oc.activo',
        'p.razonSocialProv', 'sucursal', 'numeroCu','oc.tipoServicio','oc.nombreEmpresa','oc.moneda','oc.cambioDolar',
        DB::raw('CONCAT(numeroCu, " - ", nombreCuenta) AS cuenta'),
        DB::raw('CONCAT(moneda, " $", FORMAT(importeOrden,2)) AS importeOrden'),
        DB::raw('CONCAT(" $", FORMAT(ivaCompra,2)) AS ivaCom'),
        DB::raw('CONCAT(" $", FORMAT(isrCompra,2)) AS isrCom'),
        DB::raw('CONCAT(moneda, " $", FORMAT(total,2)) AS total'),
        DB::raw('CONCAT(moneda, " $", FORMAT(totalMXN,2)) AS totalMXN'),
        DB::raw('DATE_FORMAT(fechaOrden,"%d %b %Y") AS fechaOrden')
        )
        ->leftJoin('proveedores as p', 'oc.idProveedor', '=', 'p.idProveedor')  
        ->leftJoin('sucursales as s', 'oc.idSucursal', '=', 's.idSucursal')  
        ->leftJoin('cuentas as c', 'oc.idCuenta', '=', 'c.idCuenta')  
        ->leftJoin('facturas as f', 'oc.idFactura', '=', 'f.idFactura')  
        ->whereIn( 'oc.idOrden', $this->data )
        ->orderBy('oc.idOrden', 'DESC')
        ->get();

        $consulta = $this->json( $query );

        return $consulta;
    }

    public function sumaTotales(){ 

        $sumaTotales=DB::table('ordenCompra as oc')
        ->select(
        DB::raw('CONCAT("MXN", " $", FORMAT(SUM(importeMXN),2)) AS importe'),
        DB::raw('CONCAT(" $", FORMAT(SUM(ivaMXN),2)) AS iva'),
        DB::raw('CONCAT(" $", FORMAT(SUM(isrMXN),2)) AS isr'), 
        DB::raw('CONCAT("MXN", " $", FORMAT(SUM(totalMXN),2)) AS total')
        )
        ->where('oc.activo','=','Si')
        ->whereIn( 'oc.idOrden', $this->data )
        // ->groupBy('oc.idOrden')
        ->orderBy('oc.idOrden', 'DESC')
        ->get();

        return $sumaTotales;

    }


    public function json ( $query){

        $consult = array();
        foreach( $query as $value)
        {
            array_push( $consult, array(
                'id'    => $value->idOrden, 
                'a'    => $value->idServicios, 
                'b'    => $value->codigoOrden, 
                'c'    => $value->fechaOrden, 
                'd'    => $value->razonSocialProv, 
                'e'    => $value->sucursal, 
                'f'    => $value->nombreEmpresa, 
                'g'    => $value->cuenta, 
                'h'    => $value->importeOrden, 
                'i'    => $value->ivaCom, 
                'j'    => $value->isrCom, 
                'k'    => $value->total, 
                'm'    => $value->totalMXN, 
                'l'    => $value->estatus, 
                'p'    => $value->tipoServicio, 
                'q'    => $value->moneda,
                'r'    => $value->cambioDolar, 
                
            ) );

        }

        return $consult;
    }

    public function view(): View
    {
        $data = $this->query();
        $suma = $this->sumaTotales();

        return view( 'excelOCF', compact( 'data','suma' ) );
    }


}
