<?php

namespace App\Exports;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Exports\VentasMesExport;
use DB;
use Session;

use Maatwebsite\Excel\Concerns\FromView; 
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;
use \Illuminate\Support\Collection;

class VentasMesExport implements FromView, ShouldAutoSize{
    protected $month; 
    protected $year; 
    protected $filterEmpresa;
    protected $filterVendedor; 
    protected $filter;

    public function __construct($month, $year, $filterEmpresa, $filterVendedor, $filter){
        $this->month = $month;
        $this->year = $year;
        $this->filterEmpresa = $filterEmpresa;
        $this->filterVendedor = $filterVendedor;
        $this->filter = $filter;
    }

    public function query(){
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
                 'facturas.tipoMoneda',
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
        ->where('facturas.fechaFactura', 'like', $this->year.'-'.$this->month.'-%')
        ->orderBy('fechaFactura', 'desc')
        ->get();
            
                if($this->filter == 'all'){
                    $newFacturas = $facturas;
                }
                if($this->filterEmpresa == 'true' && $this->filter != 'all'){
                    foreach($facturas as $factura){
                        if($factura->idc == $this->filter){
                            array_push($newFacturas, $factura);
                        }
                    }
                }
                if($this->filterVendedor == 'true' && $this->filter != 'all'){
                    foreach($facturas as $factura){
                        if($factura->idu == $this->filter){
                            array_push($newFacturas, $factura);
                        }
                    }
                }
    
                return $this->json($newFacturas);
    }

    public function json($query){

        foreach($query as $e){
            $consult[] = array(
                'b' => $e->fechaFactura,
                'c' => $e->fechaVencimiento,
                'd' => $e->tipoMoneda,
                'e' => $e->estatusPago,
                'f' => $e->periodoPago,
                'g' => $e->nombreUsuario,
                'h' => $e->numeroFactura,
                'i' => $e->idServicios, 
                'j' => $e->razonSocial,  
                'k' => $e->saldo, 
                'l' => $e->cambioFactura,
                'm' => $e->subtotal, 
                'n' => $e->iva,
                'o' => $e->montoPesos
            );

        }
        return $consult;
    }

    public function view():View{
        $data = $this->query();
        $sub_report = $this->filterEmpresa === 'true' ? $this->getEmpresaReport() : $this->getVendedoresReport();
        $month = $this->month;
        $year = $this->year;
        return view('excelVentasMes')
            ->with('month', $month)
            ->with('data', $data)
            ->with('subreport', $sub_report)
            ->with('filter', $this->filterEmpresa)
            ->with('year', $year);
    }

    public function getEmpresaReport(){
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
            $subtotal = 0;
            $iva = 0;
            $facturas = \DB::table('facturas')
                ->leftJoin('datosPagoServicios', 'facturas.idFactura', '=', 'datosPagoServicios.idFactura')
                ->join('clientes', 'facturas.idc', '=', 'clientes.idc')
                //->join('cotizaciones', 'facturas.idCotizacion', '=', 'cotizaciones.idCotizacion')
                //->join('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
                ->select(\DB::raw('clientes.idc, 
                                clientes.razonSocial as nombre, 
                                concat("$", format(SUM(ifnull(datosPagoServicios.montoPesosDP, facturas.montoPesos)), 2)) as montoPesos'),
                         \DB::raw('concat("$", format(SUM(datosPagoServicios.subtotalFinal), 2)) as subtotal'),
                         \DB::raw('concat("$", format(SUM(datosPagoServicios.ivaFinal), 2)) as iva'))
                ->where('facturas.fechaFactura', 'like', $this->year.'-'.$this->month."-%")
                ->orderBy('fechaFactura', 'desc')
                ->groupBy('razonSocial')
                ->get();

            if($this->filter != 'all'){
                foreach($facturas as $factura){
                    if($factura->idc == $this->filter){
                        array_push($newFacturas, $factura);
                        $suma += floatval(str_replace('$', '', str_replace(',', '', $factura->montoPesos)));
                        $subtotal += floatval(str_replace('$', '', str_replace(',', '', $factura->subtotal)));
                        $iva += floatval(str_replace('$', '', str_replace(',', '', $factura->iva)));
                    }
                }
            }else{
                $newFacturas = $facturas;
                foreach($facturas as $factura){
                    $suma += floatval(str_replace('$', '', str_replace(',', '', $factura->montoPesos)));
                    $subtotal += floatval(str_replace('$', '', str_replace(',', '', $factura->subtotal)));
                    $iva += floatval(str_replace('$', '', str_replace(',', '', $factura->iva)));
                }
            }

            return $this->sub_report_json($newFacturas, "$".number_format($suma, 2, '.', ','), "$".number_format($subtotal, 2, '.', ','), "$".number_format($iva, 2, '.', ','));

        }
    }
    public function getVendedoresReport(){
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
            $subtotal = 0;
            $iva = 0;
            $facturas = \DB::table('facturas')
                //->join('clientes', 'facturas.idc', '=', 'clientes.idc')
                ->leftJoin('datosPagoServicios', 'facturas.idFactura', '=', 'datosPagoServicios.idFactura')
                ->join('cotizaciones', 'facturas.idCotizacion', '=', 'cotizaciones.idCotizacion')
                ->join('usuarios', 'cotizaciones.idu', '=', 'usuarios.idu')
                ->select('usuarios.idu',
                         \DB::raw('usuarios.nombreUsuario as nombre, 
                                   concat("$", format(SUM(ifnull(datosPagoServicios.montoPesosDP, facturas.montoPesos)), 2)) as montoPesos'),
                         \DB::raw('concat("$", format(SUM(datosPagoServicios.subtotalFinal), 2)) as subtotal'),
                         \DB::raw('concat("$", format(SUM(datosPagoServicios.ivaFinal), 2)) as iva')
                        )
                ->where('facturas.fechaFactura', 'like', '%-'.$this->month."-%")
                ->where('facturas.fechaFactura', 'like', $this->year.'%')
                ->orderBy('facturas.fechaFactura', 'desc')
                ->groupBy('usuarios.nombreUsuario')
                ->get();
            if($this->filter != 'all'){
                foreach($facturas as $factura){
                    if($factura->idu == $this->filter){
                        array_push($newFacturas, $factura);
                        $suma += floatval(str_replace('$', '', str_replace(',', '', $factura->montoPesos)));
                        $subtotal += floatval(str_replace('$', '', str_replace(',', '', $factura->subtotal)));
                        $iva += floatval(str_replace('$', '', str_replace(',', '', $factura->iva)));
                    }
                }
            }else{
                $newFacturas = $facturas;
                foreach($facturas as $factura){
                    $suma += floatval(str_replace('$', '', str_replace(',', '', $factura->montoPesos)));
                    $subtotal += floatval(str_replace('$', '', str_replace(',', '', $factura->subtotal)));
                    $iva += floatval(str_replace('$', '', str_replace(',', '', $factura->iva)));
                }
            }
            return $this->sub_report_json($newFacturas, "$".number_format($suma, 2, '.', ','), "$".number_format($subtotal, 2, '.', ','), "$".number_format($iva, 2, '.', ','));
        }
    }

    public function sub_report_json($query, $suma, $subtotal, $iva){
        foreach($query as $e){
            $consult[] = array(
                'b' => $e->nombre,
                'c' => $e->montoPesos,
            );
        }
        array_push($consult,['b'=>$subtotal, 'c'=>$iva, 'd'=>$suma]);
        return $consult;
    }
}