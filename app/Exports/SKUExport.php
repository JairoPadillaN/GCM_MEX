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

class SKUExport implements FromView, ShouldAutoSize
{

    public function __construct( $data, $user ){
        $this->data = $data;
        
    }

    public function query(){        

       /*  $query= \DB::SELECT("SELECT f.idservicios,f.factura,f.fechafactura,f.fechapago,  coti.numerocotizacion,c.razonsocial, 
        s.sucursal,
        pv.nombrerefaccion,dr.piezas,pv.numeroparte,pv.serie,pv.modelo,pv.codigo AS sku,
        tr.nombreTipoRefaccion,mr.marcarefaccion,'PARTIDAS EN COTIZACION' AS comosecotizo
        FROM facturas  AS f
        INNER JOIN cotizaciones AS coti ON coti.idcotizacion  = f.idcotizacion
        INNER JOIN sucursales AS s ON s.idsucursal = f.idsucursal
        INNER JOIN clientes AS c ON c.idc = f.idc
        INNER JOIN detallereparaciones AS dr ON dr.idcotizacion = coti.idcotizacion
        INNER JOIN partesVenta AS pv ON pv.idPartesVenta = dr.idequipos
        INNER JOIN tiporefacciones AS tr ON pv.idTipoRefacciones = tr.idTipoRefacciones
        INNER JOIN marcasRefaccion AS mr ON tr.idMarcaRefa = mr.idMarcaRefa
        WHERE (dr.tipocotizacion  = 'nuevo' OR dr.tipocotizacion  = 'refurbished') AND  (f.tiposervicio= 'Reparaciones' OR f.tiposervicio= 'Venta de refacciones') AND f.activo = 'si'
        UNION
        SELECT f.idservicios,f.factura,f.fechafactura,f.fechapago,  coti.numerocotizacion,c.razonsocial, 
        s.sucursal,
        pv.nombrerefaccion,'1' AS piezas,pv.numeroparte,pv.serie,pv.modelo,pv.codigo AS sku,
        tr.nombreTipoRefaccion,mr.marcarefaccion,'DENTRO DE EQUIPOS' AS comosecotizo
        FROM facturas  AS f
        INNER JOIN cotizaciones AS coti ON coti.idcotizacion  = f.idcotizacion
        INNER JOIN sucursales AS s ON s.idsucursal = f.idsucursal
        INNER JOIN clientes AS c ON c.idc = f.idc
        INNER JOIN refaccionesEnCotizacion AS rc ON rc.idcotizacion = coti.idcotizacion
        INNER JOIN partesVenta AS pv ON pv.idPartesVenta = rc.idpartesventa
        INNER JOIN tiporefacciones AS tr ON pv.idTipoRefacciones = tr.idTipoRefacciones
        INNER JOIN marcasRefaccion AS mr ON tr.idMarcaRefa = mr.idMarcaRefa
        WHERE (rc.tipoproducto  = 'nuevo' OR rc.tipoproducto  = 'refurbished') AND  (f.tiposervicio= 'Reparaciones' OR f.tiposervicio= 'Venta de refacciones') AND f.activo = 'si' 
                    "); */             
        $query = DB::table('REPORTESKU')
            ->select(
            'idservicios',
            'factura',
            DB::raw('DATE_FORMAT(fechafactura,"%d/%m/%Y") as fechafactura'),
            DB::raw('DATE_FORMAT(fechapago,"%d/%m/%Y") as fechapago'),
            'numerocotizacion',
            'razonsocial',
            'sucursal',
            'nombrerefaccion',
            'piezas',
            'numeroparte',
            'serie',
            'modelo',
            'sku',
            'nombreTipoRefaccion',
            'marcarefaccion',
            'comosecotizo')
            ->whereIn('idservicios',$this->data)
            ->get();       
            

        $consulta = $this->json( $query );

        return $consulta;
    }


    public function json ( $query){

        $consult = array();
        foreach( $query as $value)
        {
            array_push( $consult, array(
                'id'   => $value->idservicios,
                'a'    => $value->factura,  
                'b'    => $value->fechafactura, 
                'c'    => $value->fechapago, 
                'd'    => $value->numerocotizacion, 
                'e'    => $value->razonsocial, 
                'f'    => $value->sucursal, 
                'g'    => $value->nombrerefaccion, 
                'h'    => $value->piezas, 
                'i'    => $value->numeroparte, 
                'j'    => $value->serie, 
                'k'    => $value->modelo, 
                'l'    => $value->sku, 
                'm'    => $value->nombreTipoRefaccion, 
                'n'    => $value->marcarefaccion, 
                'o'    => $value->comosecotizo,  
            ) );

        }

        return $consult;
    }

    public function view(): View
    {
        $data = $this->query();
       // $suma = $this->sumaTotalUtilidad();
        // dd($data);

        return view( 'excelSKU', compact( 'data') );
    }


}
