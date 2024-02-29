<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SKUExport;
use App\Http\Controllers\cotizacionesController;
use App\facturas;
use App\cotizaciones;
use App\sucursales;
use App\clientes;
use App\detallereparaciones;
use App\partesVenta;
use App\tiporefacciones;
use App\marcasRefaccion;
use Session;

use App\usuarios;
use App\asignacionesdetalles;

use App\recepcionEquipos;
use App\partesreparacion;

use App\serviciostalleres;
use App\serviciosreparacionpartes;
use App\alcances;

use App\refaccionesreparacionpartes;
use App\refaccionesEnCotizacion;
use App\detallepaquetes;
use App\contactosucursales;
use DB;

class reporteSKUServicioController extends Controller
{

    public function reporteSKUServicio()
    {
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {

            /*  $cuentas = \DB::select("SELECT * FROM cuentas WHERE activo='Si' ORDER BY nombreCuenta ASC");
        /* $query= \DB::SELECT("SELECT f.idservicios,f.factura,f.fechafactura,f.fechapago,  coti.numerocotizacion,c.razonsocial, 
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
                    "); 
                      
        $query = DB::table('REPORTESKU')
            ->select('idservicios',
            // DB::raw('IF(factura is null, "----",factura) as factura')
            'factura',
            DB::raw('DATE_FORMAT(fechafactura,"%d/%m/%Y") as fechafactura'),
            DB::raw('DATE_FORMAT(fechapago,"%d/%m/%Y") as fechapago'),
            'idcotizacion',
            'numerocotizacion',
            'razonsocial',
            'sucursal',
            'nombrerefaccion', 
            'piezas',
            'numeroparte',
            // 'serie',
            DB::raw('IF(serie is null, "*-*-*",serie) as serie'),
            // 'modelo',
            DB::raw('IF(modelo is null, "-*-*-",modelo) as modelo'),
            'sku',
            'nombreTipoRefaccion',
            'marcarefaccion',
            'comosecotizo')
            ->orderBy('idservicios','ASC')
            ->get();
                        
*/
            return view('reporteSKUServicio');
            //->with('cuentas', $cuentas) 
            //->with('query', $query);
        }
    }


    public function peticionSKU()
    {
        $stipo = Session::get('sesiontipo');


        if ($stipo == 'Compras') {
            $combo = \DB::table('REPORTESKU')
                ->select(
                    'idservicios',
                    // DB::raw('IF(factura is null, "----",factura) as factura')
                    'factura',
                    DB::raw('DATE_FORMAT(fechafactura,"%d/%m/%Y") as fechafactura'),
                    DB::raw('DATE_FORMAT(fechapago,"%d/%m/%Y") as fechapago'),
                    'idcotizacion',
                    'numerocotizacion',
                    'razonsocial',
                    'sucursal',
                    'notas',
                    'nombrerefaccion',
                    'piezas',
                    'numeroparte',
                    // 'serie',
                    DB::raw('IF(serie is null, "*-*-*",serie) as serie'),
                    // 'modelo',
                    DB::raw('IF(modelo is null, "-*-*-",modelo) as modelo'),
                    'sku',
                    'nombreTipoRefaccion',
                    'marcarefaccion',
                    'comosecotizo'
                )
                ->orderBy('idservicios', 'DESC')
                ->get();
        } else {

            $combo = \DB::table('REPORTESKU')
                ->select(
                    'idservicios',
                    // DB::raw('IF(factura is null, "----",factura) as factura')
                    'factura',
                    DB::raw('DATE_FORMAT(fechafactura,"%d/%m/%Y") as fechafactura'),
                    DB::raw('DATE_FORMAT(fechapago,"%d/%m/%Y") as fechapago'),
                    'idcotizacion',
                    'numerocotizacion',
                    'razonsocial',
                    'sucursal',
                    'notas',
                    'nombrerefaccion',
                    'piezas',
                    'numeroparte',
                    // 'serie',
                    DB::raw('IF(serie is null, "*-*-*",serie) as serie'),
                    // 'modelo',
                    DB::raw('IF(modelo is null, "-*-*-",modelo) as modelo'),
                    'sku',
                    'nombreTipoRefaccion',
                    'marcarefaccion',
                    'comosecotizo'
                )
                ->orderBy('idservicios', 'DESC')
                ->get();
        }
        return response()->json($combo, 200);
    }

    public function reporteSKUCotizacion()
    {
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {
            $cotizacion = \DB::SELECT("SELECT coti.idCotizacion , coti.fechacotizacion,coti.numeroCotizacion,
                    c.razonSocial,s.sucursal,
                    mr.marcaRefaccion,tr.nombreTipoRefaccion,pv.codigo,dr.descripcion,dr.modelo,
                    dr.tipoMoneda,dr.piezas,dr.montofinanciamiento,dr.notas       
                    FROM detallereparaciones AS dr
                    INNER JOIN cotizaciones AS coti ON coti.idcotizacion = dr.idcotizacion
                    INNER JOIN partesVenta AS pv ON pv.idpartesVenta = dr.idpartesVenta
                    INNER JOIN marcasRefaccion AS mr ON mr.idmarcaRefa = pv.idMarcaRefa
                    INNER JOIN tiporefacciones AS tr ON tr.idTipoRefacciones = pv.idTipoRefacciones
                    INNER JOIN clientes AS c ON c.idc = coti.idc 
                    INNER JOIN sucursales AS s ON s.idSucursal = coti.idSucursal
                    WHERE (dr.tipoCotizacion = 'nuevo' OR dr.tipoCotizacion = 'refurbished')
                        AND coti.clonActual = 'Si' AND pv.activo = 'Si' 
            ");
            $cuantaCoti = count($cotizacion);



            return view('reporteSKUCotizacion')
                ->with('cotizacion', $cotizacion)
                ->with('cuantaCoti', $cuantaCoti);
        }
    }


    /*    public function json($query){

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
                'p'    => $value->idcotizacion, 
                
                
            ));

        }

        return $consult;
    }*/


    public function excelSKU(Request $request)
    {
        return Excel::download(new SKUExport($request->data, $request->user), 'reporteSKU.xlsx');
    }
    public function reporteSKUServicioAbajo(Request $request)
    {
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {
            $fechaInicio = $request->fechaInicio;
            $fechaFin = $request->fechaFin;


            $query = \DB::SELECT("SELECT f.idservicios,f.factura,f.fechafactura,f.fechapago, coti.idcotizacion ,coti.numerocotizacion,c.razonsocial, 
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
        SELECT f.idservicios,f.factura,f.fechafactura,f.fechapago, coti.idcotizacion ,coti.numerocotizacion,c.razonsocial, 
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
        WHERE (rc.tipoproducto  = 'nuevo' OR rc.tipoproducto  = 'refurbished') AND  (f.tiposervicio= 'Reparaciones' OR f.tiposervicio= 'Venta de refacciones') AND f.activo = 'si' AND (fechafactura >= '$fechaInicio' and fechafactura <= '$fechaFin')
                    ");




            $consulta = $this->json($query);
            return view('reporteSKUServicioAbajo', compact('consulta'))
                ->with('fecha1', $fechaInicio)
                ->with('fecha2', $fechaFin);
        }
    }

    public function reporteSKUTotalizado()
    {
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {

            $totalizado = \DB::SELECT("SELECT t1.sku,t1.nombreTipoRefaccion,t1.marcarefaccion,SUM(t1.piezas) AS totalpiezas,SUM(t1.montototalpesos)  AS totalmontopesos
            FROM
            (SELECT
              f.idServicios          AS idservicios,
              IF( f.factura IS NULL,'Sin Factura', f.factura )         AS factura,
              f.fechaFactura         AS fechafactura,
              IF(f.fechaPago IS NULL,'Sin Fecha pago', f.fechaPago)           AS fechapago,
              coti.idCotizacion      AS idcotizacion,
              coti.numeroCotizacion  AS numerocotizacion,
              c.razonSocial          AS razonsocial,
              s.sucursal             AS sucursal,
              pv.nombreRefaccion     AS nombrerefaccion,
                pv.numeroParte         AS numeroparte,
              pv.serie               AS serie,
              pv.modelo              AS modelo,
              pv.codigo              AS sku,
              tr.nombreTipoRefaccion AS nombreTipoRefaccion,
              mr.marcaRefaccion      AS marcarefaccion,
              coti.tipoMoneda        AS moneda,
              dr.piezas              AS piezas,
              IF(coti.tipoMoneda = 'USD',coti.cambio * dr.montofinanciamiento,dr.montofinanciamiento )AS montototalpesos,
            
              'PARTIDAS EN COTIZACION'   AS comosecotizo,
              dr.notas AS notas
            FROM (((((((facturas f
                JOIN cotizaciones coti ON ((coti.idCotizacion = f.idCotizacion)))
                JOIN sucursales s ON ((s.idSucursal = f.idSucursal)))
                JOIN clientes c ON ((c.idc = f.idc)))
                JOIN detallereparaciones dr ON ((dr.idCotizacion = coti.idCotizacion)))
                JOIN partesVenta pv ON ((pv.idPartesVenta = dr.idPartesVenta)))
                JOIN tiporefacciones tr ON ((pv.idTipoRefacciones = tr.idTipoRefacciones)))
               JOIN marcasRefaccion mr ON ((tr.idMarcaRefa = mr.idMarcaRefa)))
            WHERE (((dr.tipoCotizacion = 'nuevo')
                     OR (dr.tipoCotizacion = 'refurbished'))
                   AND ((f.tipoServicio = 'Reparaciones')
                         OR (f.tipoServicio = 'Venta de refacciones'))
                   AND (f.activo = 'si')
                   AND (coti.estatus <> 'cancelada')
                   AND (coti.clonActual = 'Si')
                   AND (coti.activo = 'Si') AND f.numeroFactura  <>'-' AND f.numeroFactura IS NOT NULL )
                   UNION SELECT
                    f.idServicios           AS idservicios,
                    f.factura               AS factura,
                    f.fechaFactura          AS fechafactura,
                    f.fechaPago             AS fechapago,
                    coti.idCotizacion       AS idcotizacion,
                    coti.numeroCotizacion   AS numerocotizacion,
                    c.razonSocial           AS razonsocial,
                    s.sucursal              AS sucursal,
                        pv.nombreRefaccion     AS nombrerefaccion,
                        pv.numeroParte          AS numeroparte,
                    pv.serie                AS serie,
                    pv.modelo               AS modelo,
                    pv.codigo               AS sku,
                    tr.nombreTipoRefaccion  AS nombreTipoRefaccion,
                    mr.marcaRefaccion       AS marcarefaccion,
                    coti.tipoMoneda        AS moneda,
                    '1'                         AS piezas,
                    IF(coti.tipoMoneda = 'USD',coti.cambio * rc.precioDolar,rc.precioDolar)AS montototalpesos,
                    
                    'DENTRO DE EQUIPOS'         AS comosecotizo,
                    'notas' AS Notas
                    FROM (((((((facturas f
                        JOIN cotizaciones coti ON ((coti.idCotizacion = f.idCotizacion)))
                        JOIN sucursales s ON ((s.idSucursal = f.idSucursal)))
                        JOIN clientes c ON ((c.idc = f.idc)))
                        JOIN refaccionesEnCotizacion rc ON ((rc.idCotizacion = coti.idCotizacion)))
                        JOIN partesVenta pv ON ((pv.idPartesVenta = rc.idPartesVenta)))
                        JOIN tiporefacciones tr ON ((pv.idTipoRefacciones = tr.idTipoRefacciones)))
                        JOIN marcasRefaccion mr ON ((tr.idMarcaRefa = mr.idMarcaRefa)))
                    WHERE (((rc.tipoProducto = 'nuevo')
                            OR (rc.tipoProducto = 'refurbished'))
                            AND ((f.tipoServicio = 'Reparaciones')
                            OR (f.tipoServicio = 'Venta de refacciones'))
                            AND (f.activo = 'si')AND  f.numeroFactura  <>'-' AND f.numeroFactura IS NOT NULL
                            )) AS t1
             GROUP BY t1.sku,t1.nombreTipoRefaccion,t1.marcarefaccion");

            return view('reporteSKUTotalizado')
                ->with('totalizado', $totalizado);
        }
    }

    /*     public function reporteSKUTotalizadoFiltro(Request $request){
        $FecIni = $request->input('ini');
        $FecFin = $request->input('fin');

        $query ="SELECT t1.sku,t1.nombreTipoRefaccion,t1.marcarefaccion,SUM(t1.piezas) AS totalpiezas,SUM(t1.montototalpesos)  AS totalmontopesos
        FROM
        (SELECT
          pv.codigo              AS sku,
          tr.nombreTipoRefaccion AS nombreTipoRefaccion,
          mr.marcaRefaccion      AS marcarefaccion,
          dr.piezas              AS piezas,
          IF(coti.tipoMoneda = 'USD',coti.cambio * dr.montofinanciamiento,dr.montofinanciamiento )AS montototalpesos
        FROM (((((((facturas f
                 JOIN cotizaciones coti
                   ON ((coti.idCotizacion = f.idCotizacion)))
                JOIN sucursales s
                  ON ((s.idSucursal = f.idSucursal)))
               JOIN clientes c
                 ON ((c.idc = f.idc)))
              JOIN detallereparaciones dr
                ON ((dr.idCotizacion = coti.idCotizacion)))
             JOIN partesVenta pv
               ON ((pv.idPartesVenta = dr.idPartesVenta)))
            JOIN tiporefacciones tr
              ON ((pv.idTipoRefacciones = tr.idTipoRefacciones)))
           JOIN marcasRefaccion mr
             ON ((tr.idMarcaRefa = mr.idMarcaRefa)))
        WHERE (((dr.tipoCotizacion = 'nuevo')
                 OR (dr.tipoCotizacion = 'refurbished'))
               AND ((f.tipoServicio = 'Reparaciones')
                     OR (f.tipoServicio = 'Venta de refacciones'))
               AND (f.activo = 'si')
               AND (coti.estatus <> 'cancelada')
               AND (coti.clonActual = 'Si')
               AND (coti.activo = 'Si') AND f.numeroFactura  <>'-' AND f.numeroFactura IS NOT NULL 
               AND f.fechaFactura>= $FecIni AND f.fechaFactura<= $FecFin)
               UNION SELECT
                                                   
                pv.codigo               AS sku,
                tr.nombreTipoRefaccion  AS nombreTipoRefaccion,
                mr.marcaRefaccion       AS marcarefaccion,
                '1'                         AS piezas,
                IF(coti.tipoMoneda = 'USD',coti.cambio * rc.precioDolar,rc.precioDolar)AS montototalpesos
                
    
                FROM (((((((facturas f
                        JOIN cotizaciones coti
                            ON ((coti.idCotizacion = f.idCotizacion)))
                        JOIN sucursales s
                        ON ((s.idSucursal = f.idSucursal)))
                        JOIN clientes c
                        ON ((c.idc = f.idc)))
                    JOIN refaccionesEnCotizacion rc
                        ON ((rc.idCotizacion = coti.idCotizacion)))
                    JOIN partesVenta pv
                        ON ((pv.idPartesVenta = rc.idPartesVenta)))
                    JOIN tiporefacciones tr
                    ON ((pv.idTipoRefacciones = tr.idTipoRefacciones)))
                    JOIN marcasRefaccion mr
                    ON ((tr.idMarcaRefa = mr.idMarcaRefa)))
                WHERE (((rc.tipoProducto = 'nuevo')
                        OR (rc.tipoProducto = 'refurbished'))
                        AND ((f.tipoServicio = 'Reparaciones')
                            OR (f.tipoServicio = 'Venta de refacciones'))
                        AND (f.activo = 'si')AND  f.numeroFactura  <>'-' AND f.numeroFactura IS NOT NULL
                        AND f.fechaFactura>= $FecIni AND f.fechaFactura<= $FecFin)) AS t1
         GROUP BY  t1.sku,t1.nombreTipoRefaccion,t1.marcarefaccion";

        $result = facturas::hydrate(DB::select($query, [$FecIni, $FecFin]))->toArray();

        return response()->json([
            'data' => $result,
        ]);

    } */

    public function reporteSKUTotalizadoFiltro(Request $request)
    {
        $FecIni = $request->input('ini');
        $FecFin = $request->input('fin');

        $totalFecha = \DB::SELECT("SELECT t1.sku,t1.nombreTipoRefaccion,t1.marcarefaccion,SUM(t1.piezas) AS totalpiezas,SUM(t1.montototalpesos)  AS totalmontopesos
        FROM
        (SELECT
          f.idServicios          AS idservicios,
          IF( f.factura IS NULL,'Sin Factura', f.factura )         AS factura,
          f.fechaFactura         AS fechafactura,
          IF(f.fechaPago IS NULL,'Sin Fecha pago', f.fechaPago)           AS fechapago,
          coti.idCotizacion      AS idcotizacion,
          coti.numeroCotizacion  AS numerocotizacion,
          c.razonSocial          AS razonsocial,
          s.sucursal             AS sucursal,
          pv.nombreRefaccion     AS nombrerefaccion,
            pv.numeroParte         AS numeroparte,
          pv.serie               AS serie,
          pv.modelo              AS modelo,
          pv.codigo              AS sku,
          tr.nombreTipoRefaccion AS nombreTipoRefaccion,
          mr.marcaRefaccion      AS marcarefaccion,
          coti.tipoMoneda        AS moneda,
          dr.piezas              AS piezas,
          IF(coti.tipoMoneda = 'USD',coti.cambio * dr.montofinanciamiento,dr.montofinanciamiento )AS montototalpesos,
        
          'PARTIDAS EN COTIZACION'   AS comosecotizo,
          dr.notas AS notas
        FROM (((((((facturas f
                 JOIN cotizaciones coti
                   ON ((coti.idCotizacion = f.idCotizacion)))
                JOIN sucursales s
                  ON ((s.idSucursal = f.idSucursal)))
               JOIN clientes c
                 ON ((c.idc = f.idc)))
              JOIN detallereparaciones dr
                ON ((dr.idCotizacion = coti.idCotizacion)))
             JOIN partesVenta pv
               ON ((pv.idPartesVenta = dr.idPartesVenta)))
            JOIN tiporefacciones tr
              ON ((pv.idTipoRefacciones = tr.idTipoRefacciones)))
           JOIN marcasRefaccion mr
             ON ((tr.idMarcaRefa = mr.idMarcaRefa)))
        WHERE (((dr.tipoCotizacion = 'nuevo')
                 OR (dr.tipoCotizacion = 'refurbished'))
               AND ((f.tipoServicio = 'Reparaciones')
                     OR (f.tipoServicio = 'Venta de refacciones'))
               AND (f.activo = 'si')
               AND (coti.estatus <> 'cancelada')
               AND (coti.clonActual = 'Si')
               AND (coti.activo = 'Si') AND f.numeroFactura  <>'-' AND f.numeroFactura IS NOT NULL 
               AND f.fechaFactura>='$FecIni' AND f.fechaFactura<='$FecFin')UNION SELECT
                                                    f.idServicios           AS idservicios,
                                                    f.factura               AS factura,
                                                    f.fechaFactura          AS fechafactura,
                                                    f.fechaPago             AS fechapago,
                                                    coti.idCotizacion       AS idcotizacion,
                                                    coti.numeroCotizacion   AS numerocotizacion,
                                                    c.razonSocial           AS razonsocial,
                                                    s.sucursal              AS sucursal,
                                                     pv.nombreRefaccion     AS nombrerefaccion,
                                                     pv.numeroParte          AS numeroparte,
                                                    pv.serie                AS serie,
                                                    pv.modelo               AS modelo,
                                                    pv.codigo               AS sku,
                                                    tr.nombreTipoRefaccion  AS nombreTipoRefaccion,
                                                    mr.marcaRefaccion       AS marcarefaccion,
                                                    coti.tipoMoneda        AS moneda,
                                                    '1'                         AS piezas,
                                                    IF(coti.tipoMoneda = 'USD',coti.cambio * rc.precioDolar,rc.precioDolar)AS montototalpesos,
                                                   
                                                    'DENTRO DE EQUIPOS'         AS comosecotizo,
                                                    'notas' AS Notas
                                                  FROM (((((((facturas f
                                                           JOIN cotizaciones coti
                                                             ON ((coti.idCotizacion = f.idCotizacion)))
                                                          JOIN sucursales s
                                                            ON ((s.idSucursal = f.idSucursal)))
                                                         JOIN clientes c
                                                           ON ((c.idc = f.idc)))
                                                        JOIN refaccionesEnCotizacion rc
                                                          ON ((rc.idCotizacion = coti.idCotizacion)))
                                                       JOIN partesVenta pv
                                                         ON ((pv.idPartesVenta = rc.idPartesVenta)))
                                                      JOIN tiporefacciones tr
                                                        ON ((pv.idTipoRefacciones = tr.idTipoRefacciones)))
                                                     JOIN marcasRefaccion mr
                                                       ON ((tr.idMarcaRefa = mr.idMarcaRefa)))
                                                  WHERE (((rc.tipoProducto = 'nuevo')
                                                           OR (rc.tipoProducto = 'refurbished'))
                                                         AND ((f.tipoServicio = 'Reparaciones')
                                                               OR (f.tipoServicio = 'Venta de refacciones'))
                                                         AND (f.activo = 'si')AND  f.numeroFactura  <>'-' AND f.numeroFactura IS NOT NULL
                                                         AND f.fechaFactura>='$FecIni' AND f.fechaFactura<='$FecFin')) AS t1
         GROUP BY t1.sku,t1.nombreTipoRefaccion,t1.marcarefaccion");

        return view('reporteSKUTotalizadoFiltro')
            ->with('totalFecha', $totalFecha)
            ->with('FecIni', $FecIni)
            ->with('FecFin', $FecFin);
    }
}
