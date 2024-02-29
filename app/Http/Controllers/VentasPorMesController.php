<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\facturas;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\VentasMesExport;

class VentasPorMesController extends Controller
{
    public function index(){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }else{
            $year = date('Y');
            $month = date('m');
            $clientes = \DB::table('clientes')->select('idc', 'razonSocial')->get();
            $vendedores = \DB::table('usuarios')->select('idu', 'nombreUsuario')->get();
            return view('reporteVentasIndex')
                ->with('year', $year)
                ->with('month', $month)
                ->with('empresas', $clientes)
                ->with('vendedores', $vendedores);
        }
    }

    public function getVentasData($month, $year, $filterEmpresa, $filterVendedor, $filter){

        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }else{
            $newFacturas = array();
            $facturas = \DB::table('facturas')
                ->join('datosPagoServicios', 'facturas.idFactura', '=', 'datosPagoServicios.idFactura')
                ->leftJoin('clientes', 'facturas.idc', '=', 'clientes.idc')
                ->leftJoin('cotizaciones', 'facturas.idCotizacion', '=', 'cotizaciones.idCotizacion')
                ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
                ->select('facturas.idFactura', 
                         'clientes.idc',
                         'usuarios.idu',
                         \DB::raw('date_format(fechaFactura, "%d/%m/%Y") as fechaFactura'),
                         \DB::raw('date_format(datosPagoServicios.created_at, "%d/%m/%Y") as fechaVencimiento'), 
                         \DB::raw('if(datosPagoServicios.tipoCambioPagado > 1, "USD", "MXN") as tipoMoneda'),
                         'facturas.estatusPago', 
                         'facturas.periodoPago', 
                         \DB::raw('ifnull(usuarios.nombreUsuario,
                                    "Sin definir") as nombreUsuario'), 
                         \DB::raw('ifnull(datosPagoServicios.numeroFacturaDP, facturas.numeroFactura) as numeroFactura'), 
                         'facturas.idServicios',  
                         'clientes.razonSocial', 
                         \DB::raw('ifnull(concat("$", format(datosPagoServicios.tipoCambioPagado, 2)), concat("$", format(facturas.cambioFactura, 2))) as cambioFactura'), 
                         \DB::raw('ifnull(concat("$", format(datosPagoServicios.saldoReal, 2)), concat("$", format(facturas.saldo, 2))) as saldo'), 
                         \DB::raw('ifnull(concat("$", format(datosPagoServicios.subtotalFinal, 2)), "Sin definir") as subtotal'),
                         \DB::raw('ifnull(concat("$", format(datosPagoServicios.ivaFinal, 2)), "Sin definir") as iva'),
                         \DB::raw('ifnull(concat("$", format(datosPagoServicios.montoReal, 2)), concat("$", format(facturas.montoPesos, 2))) as montoPesos')
                        )
                ->where('facturas.fechaFactura', 'like', $year.'-'.$month.'-%')
                ->orderBy('fechaFactura', 'desc')
                ->get();
            
                if($filter == 'all'){
                    $newFacturas = $facturas;
                }
                if($filterEmpresa == 'true' && $filter != 'all'){
                    foreach($facturas as $factura){
                        if($factura->idc == $filter){
                            array_push($newFacturas, $factura);
                        }
                    }
                }
                if($filterVendedor == 'true' && $filter != 'all'){
                    foreach($facturas as $factura){
                        if($factura->idu == $filter){
                            array_push($newFacturas, $factura);
                        }
                    }
                }
    
            return response($newFacturas, 200);
        }
    }

    public function excel_reporte_ventas($month, $year, $filterEmpresa, $filterVendedor, $filter){
        $file_name = "Reporte_ventas_".$month."_".$year;
        return Excel::download(new VentasMesExport($month, $year, $filterEmpresa, $filterVendedor, $filter), $file_name.'.xlsx');
    }

    public function getEmpresaReport($month, $year, $filter){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }else{
            \DB::statement("SET SQL_MODE=''");
            $newFacturas = array();
            $suma = 0;
            $facturas = \DB::table('facturas')
                ->join('datosPagoServicios', 'facturas.idFactura', '=', 'datosPagoServicios.idFactura')
                ->leftJoin('clientes', 'facturas.idc', '=', 'clientes.idc')
                //->join('cotizaciones', 'facturas.idCotizacion', '=', 'cotizaciones.idCotizacion')
                //->join('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
                ->select(\DB::raw('clientes.idc, 
                                clientes.razonSocial, 
                                concat("$", format(SUM(ifnull(datosPagoServicios.montoPesosDP, facturas.montoPesos)), 2)) as montoPesos'))
                ->where('facturas.fechaFactura', 'like', $year.'-'.$month."-%")
                ->orderBy('fechaFactura', 'desc')
                ->groupBy('razonSocial')
                ->get();

            if($filter != 'all'){
                foreach($facturas as $factura){
                    if($factura->idc == $filter){
                        array_push($newFacturas, $factura);
                        $suma += floatval(str_replace('$', '', str_replace(',', '', $factura->montoPesos)));
                    }
                }
            }else{
                $newFacturas = $facturas;
                foreach($facturas as $factura){
                    $suma += floatval(str_replace('$', '', str_replace(',', '', $factura->montoPesos)));
                }
            }
            return ['response'=>$newFacturas, 'suma'=>'$'.number_format($suma, 2, '.', ',')];
        }
    }
    public function getVendedoresReport($month, $year, $filter){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }else{
            \DB::statement("SET SQL_MODE=''");
            $newFacturas = array();
            $suma = 0;
            $facturas = \DB::table('facturas')
                //->join('clientes', 'facturas.idc', '=', 'clientes.idc')
                ->join('datosPagoServicios', 'facturas.idFactura', '=', 'datosPagoServicios.idFactura')
                ->leftJoin('cotizaciones', 'facturas.idCotizacion', '=', 'cotizaciones.idCotizacion')
                ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
                ->select(\DB::raw('usuarios.idu, 
                                   usuarios.nombreUsuario, 
                                   concat("$", format(SUM(ifnull(datosPagoServicios.montoPesosDP, facturas.montoPesos)), 2)) as montoPesos'))
                ->where('facturas.fechaFactura', 'like', '%-'.$month."-%")
                ->where('facturas.fechaFactura', 'like', $year.'%')
                ->orderBy('fechaFactura', 'desc')
                ->groupBy('nombreUsuario')
                ->get();
            if($filter != 'all'){
                foreach($facturas as $factura){
                    if($factura->idu == $filter){
                        array_push($newFacturas, $factura);
                        $suma += floatval(str_replace('$', '', str_replace(',', '', $factura->montoPesos)));
                    }
                }
            }else{
                $newFacturas = $facturas;
                foreach($facturas as $factura){
                    $suma += floatval(str_replace('$', '', str_replace(',', '', $factura->montoPesos)));
                }
            }
            return ['response'=>$newFacturas, 'suma'=>'$'.number_format($suma, 2, '.', ',')];
        }
    }
    public function getEmpresaTotal($month, $year, $filter){
        $suma = 0;
        $subtotal = 0;
        $iva = 0;
        $newFacturas = [];
        $total = \DB::table('facturas')
            ->join('datosPagoServicios', 'facturas.idFactura', '=', 'datosPagoServicios.idFactura')
            ->leftJoin('clientes', 'facturas.idc', '=', 'clientes.idc')
            ->select('clientes.idc', 
                      \DB::raw('concat("$", format(SUM(ifnull(datosPagoServicios.montoPesosDP, facturas.montoPesos)), 2)) as total'),
                      \DB::raw('concat("$", format(SUM(datosPagoServicios.subtotalFinal), 2)) as subtotal'),
                      \DB::raw('concat("$", format(SUM(datosPagoServicios.ivaFinal), 2)) as iva')
                      )
            ->where('facturas.fechaFactura', 'like', $year.'-'.$month."-%")
            ->groupBy('idc')
            ->get();
        if($filter != 'all'){
            foreach($total as $factura){
                if($factura->idc == $filter){
                    array_push($newFacturas, $factura);
                    $suma += floatval(str_replace('$', '', str_replace(',', '', $factura->total)));
                    $subtotal += floatval(str_replace('$', '', str_replace(',', '', $factura->subtotal)));
                    $iva += floatval(str_replace('$', '', str_replace(',', '', $factura->iva)));
                }
            }
        }else{
            $newFacturas = $total;
            foreach($total as $factura){
                $suma += floatval(str_replace('$', '', str_replace(',', '', $factura->total)));
                $subtotal += floatval(str_replace('$', '', str_replace(',', '', $factura->subtotal)));
                $iva += floatval(str_replace('$', '', str_replace(',', '', $factura->iva)));
            }
        }
        return ['suma'=>number_format($suma, 2, '.', ','), 'subtotal'=>number_format($subtotal, 2, '.', ','), 'iva'=>number_format($iva, 2, '.', ',')];
    }
    public function getVendedoresTotal($month, $year, $filter){
        $newFacturas = [];
        $suma = 0;
        $subtotal = 0;
        $iva = 0;
        $total = \DB::table('facturas')
            ->join('datosPagoServicios', 'facturas.idFactura', '=', 'datosPagoServicios.idFactura')
            ->leftJoin('cotizaciones', 'facturas.idCotizacion', '=', 'cotizaciones.idCotizacion')
            ->leftJoin('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
            ->select('usuarios.idu', 
                      \DB::raw('concat("$", format(SUM(ifnull(datosPagoServicios.montoPesosDP, facturas.montoPesos)), 2)) as total'),
                      \DB::raw('concat("$", format(SUM(datosPagoServicios.subtotalFinal), 2)) as subtotal'),
                      \DB::raw('concat("$", format(SUM(datosPagoServicios.ivaFinal), 2)) as iva')
                    )
            ->where('facturas.fechaFactura', 'like', $year.'-'.$month."-%")
            ->groupBy('usuarios.idu')
            ->get();
        if($filter != 'all'){
            foreach($total as $t){
                if($t->idu == $filter){
                    array_push($newFacturas, $t);
                    $suma += floatval(str_replace('$', '', str_replace(',', '', $t->total)));
                    $subtotal += floatval(str_replace('$', '', str_replace(',', '', $t->subtotal)));
                    $iva += floatval(str_replace('$', '', str_replace(',', '', $t->iva)));
                }
            }
        }else{
            $newTotal = $total;
            foreach($total as $t){
                $suma += floatval(str_replace('$', '', str_replace(',', '', $t->total)));
                $subtotal += floatval(str_replace('$', '', str_replace(',', '', $t->subtotal)));
                $iva += floatval(str_replace('$', '', str_replace(',', '', $t->iva)));
            }
        }
        return ['suma'=>number_format($suma, 2, '.', ','), 'subtotal'=>number_format($subtotal, 2, '.', ','), 'iva'=>number_format($iva, 2, '.', ',')];
    }
}