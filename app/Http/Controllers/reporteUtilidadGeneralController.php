<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UtilidadGeneralExport; 
use App\gastos;
use App\facturas;
use Session;
use DB;

class reporteUtilidadGeneralController extends Controller
{

    public function reporteUtilidadGeneral(){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else{

        $cuentas = \DB::select("SELECT * FROM cuentas WHERE activo='Si' ORDER BY nombreCuenta ASC");
				$query = DB::table('REPORTEUTILIDADES3')            
                    ->select('idfactura',
                    'idservicios', 
                    'numerofactura',
                    DB::raw('DATE_FORMAT(fechafactura,"%d/%m/%Y") as fechafactura'),
                    DB::raw('DATE_FORMAT(fechapago,"%d/%m/%Y") as fechapago'),
                    'cliente',
                    'sucursal',
                    'tipomoneda',
                    'montofactura',
                    'montopesos',
                    'totalgastos',
                    'totaloc',
                    'utilidad')
                    ->get();
                        
                        $consulta = $this->json($query);

        return view( 'reporteGeneralUtilidades', compact( 'consulta'))
            ->with('cuentas', $cuentas);
        
        
        // return view('reporteOrdenesCompra')
        // ->with('sumaTotales', $sumaTotales[0]);
        }
    }
    public function json ( $query){

        $consult = array();
        foreach( $query as $value)
        {
            array_push( $consult, array(
                'id'   => $value->idfactura,
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
 

    public function excelUtilidadGeneral(Request $request){
        return Excel::download( new UtilidadGeneralExport( $request->data, $request->user), 'reporteUtilidades.xlsx' );
    }

    public function reporteUtilidadesAbajo(Request $request){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else{
        $fechaInicio=$request->fechaInicio;
        $fechaFin=$request->fechaFin;

        
        $query= \DB::SELECT("SELECT t1.idfactura, t1.idservicios, t1.numerofactura, t1.fechafactura, t1.fechapago, t1.cliente, t1.sucursal, t1.tipomoneda, format(t1.montofactura,2) AS montofactura,
                    format(t1.montopesos,2) AS montopesos, format(t1.totalgastos,2) AS totalgastos, format(t1.totaloc,2) AS totaloc, format(t1.montopesos - t1.totalgastos - t1.totaloc,2) AS utilidad
                    FROM
                    (SELECT idfactura, idservicios, numerofactura, IF(fechafactura IS NULL, ' - ', fechafactura) AS fechafactura, IF(fechapago IS NULL,' - ',fechapago) AS fechapago,
                    c.razonSocial AS cliente, s.sucursal AS sucursal,tipomoneda,cambioreal, montofactura,
                    IF(IF(tipomoneda='USD', cambioreal * montofactura, montofactura) IS NULL,0, IF(tipomoneda='USD', cambioreal * montofactura, montofactura))AS montopesos ,
                    IF(totalgastosfactura(idfactura)IS NULL,0,totalgastosfactura(idfactura)) AS totalgastos,
                    IF(totalordenescomprafactura(idfactura)IS NULL,0, totalordenescomprafactura(idfactura)) AS totaloc
                    FROM facturas AS f
                    INNER JOIN clientes AS c ON c.idc = f.idc
                    INNER JOIN sucursales AS s ON s.idSucursal = f.idSucursal
                    where fechafactura >= '$fechaInicio' and fechafactura <= '$fechaFin'
                    )AS t1
                    ");
                            
                    
                
        
        $consulta = $this->json($query);
        return view( 'reporteGeneralUtilidadesAbajo', compact( 'consulta'))
        ->with('fecha1',$fechaInicio)
        ->with('fecha2',$fechaFin);


        }
    }

}

/* CREATE VIEW REPORTEUTILIDADES3 AS
SELECT t1.idfactura, t1.idservicios, t1.numerofactura, t1.fechafactura, t1.fechapago, t1.cliente, t1.sucursal, t1.tipomoneda,t1.montofactura,
                t1.montopesos, t1.totalgastos, t1.totaloc, (t1.montofactura - t1.totalgastos - t1.totaloc) AS utilidad
                FROM
                (SELECT idfactura, idservicios, numerofactura, IF(fechafactura IS NULL, ' - ', fechafactura) AS fechafactura, IF(fechapago IS NULL,' - ',fechapago) AS fechapago,
                c.razonSocial AS cliente, s.sucursal AS sucursal,tipomoneda,cambioreal,
                IF(IF(totalmontofactura1(idfactura) = 0,totalmontofactura2(idfactura),totalmontofactura1(idfactura))IS NULL,0,IF(totalmontofactura1(idfactura) = 0,totalmontofactura2(idfactura),totalmontofactura1(idfactura))) AS montofactura,
                IF(IF(tipomoneda='USD', cambioreal * montofactura, montofactura) IS NULL,0, IF(tipomoneda='USD', cambioreal * montofactura, montofactura))AS montopesos ,
                IF(totalgastoServicio(idfactura)IS NULL,0,totalgastoServicio(idfactura)) AS totalgastos,
                IF(totalordenescomprafactura(idfactura)IS NULL,0, totalordenescomprafactura(idfactura)) AS totaloc
                FROM facturas AS f
                INNER JOIN clientes AS c ON c.idc = f.idc
                INNER JOIN sucursales AS s ON s.idSucursal = f.idSucursal
                )AS t1
                ORDER BY t1.fechafactura DESC
                
                
                DELIMITER$$
                CREATE FUNCTION totalmontofactura1(idfactura2 INT) RETURNS DOUBLE
                BEGIN
                DECLARE montofactura DOUBLE;
                SELECT IF(f.montoPesos IS NULL, 0, f.montoPesos) INTO montofactura
		FROM facturas AS f
		WHERE f.idFactura = idfactura2;
		RETURN montofactura;
		END$$
		
		SELECT totalmontofactura1(287)
		
		
		
		DELIMITER$$
                CREATE FUNCTION totalmontofactura2(idfactura2 INT) RETURNS DOUBLE
                BEGIN
                DECLARE montofactura DOUBLE;
                SELECT SUM(IF(dp.montoReal IS NULL, 0, dp.montoReal)) INTO montofactura
		FROM datosPagoServicios AS dp
		WHERE dp.idFactura = idfactura2;
		RETURN montofactura;
		END$$
		
		SELECT totalmontofactura2(287)
		
		
		DELIMITER$$
                CREATE FUNCTION totalgastoServicio(idfactura2 INT) RETURNS DOUBLE
                BEGIN
                DECLARE totalgastos DOUBLE;
		SELECT SUM(IF(moneda = 'USD',cambioDolar*total,total)) INTO totalgastos
                FROM gastos
                WHERE id_factura = idfactura2;
		RETURN totalgastos;
		END$$
		
		SELECT totalgastoServicio(288) */