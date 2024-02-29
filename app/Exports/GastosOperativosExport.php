<?php

namespace App\Exports;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Exports\GastosOperativosExport;
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

class GastosOperativosExport implements FromView, ShouldAutoSize{

    protected $nombreGasto;
    protected $beneficiario;
    protected $idCuenta;
    protected $empresa;
    protected $cuenta;
    protected $cliente;
    protected $sucursal;
    protected $referencia;
    protected $etiquetas;

    public function __construct($fechaInicio = null, $fechaFin = null ,$filtro = null, $value = null){
        $this->filtro = $filtro;
        $this->value = $value;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function query(){
        $stipo = Session::get('sesiontipo');
        $idusu = Session::get('idusu');

        if ($stipo == 'Administrador') {
            if($this->filtro != 'null' && $this->value != 'null'){
                $query = DB::table('gastosOperativos')
                ->join('cuentas', 'gastosOperativos.idCuenta', '=', 'cuentas.idCuenta')            
                ->select('fecha_pago',
                         'nombreGasto',
                         'beneficiario',
                         'cuentas.nombreCuenta as nombreCuenta', // Agregado el alias y la columna "nombreCuenta"
                         'forma_pago',
                         'referencia', 
                         'etiquetas',
                         'descripcion',
                         'factura',
                         'iva',
                         'total_iva',
                         'isr',
                         'total_isr',
                         'total',
                         'moneda',
                         'cambioDolar')
                ->where($this->filtro, 'like', '%'.$this->value.'%');
            }else{
                $query = DB::table('gastosOperativos')
                            ->join('cuentas', 'gastosOperativos.idCuenta', '=', 'cuentas.idCuenta')              
                            ->select('fecha_pago',
                                    'nombreGasto',
                                    'beneficiario',
                                    'cuentas.nombreCuenta as nombreCuenta',
                                    'forma_pago',
                                    'referencia',
                                    'etiquetas',
                                    'descripcion',
                                    'factura',
                                    'iva',
                                    'total_iva', 
                                    'isr',
                                    'total_isr',
                                    'total',
                                    'moneda',
                                    'cambioDolar');
            }
            if($this->fechaInicio != 'null' && $this->fechaFin != 'null'){
                $query = $query->whereBetween('fecha_pago', [$this->fechaInicio, $this->fechaFin]);
            }
            $data = $query->orderBy('fecha_pago', 'DESC')->get(); 
            $count = count($data) +1; 
            
            $consulta = $this->json($data);

            return $consulta;
        }
    
        
    }

    public function sumaTotal(){
        $sumaTotal = \DB::table('gastosOperativos')
                        ->select('moneda', 'cambioDolar','total');
        if($this->filtro != "null"){
            $sumaTotal = $sumaTotal->where($this->filtro,'like', '%'.$this->value.'%');
        }
        if($this->fechaInicio != 'null' && $this->fechaFin != 'null'){
            $sumaTotal = $sumaTotal->whereBetween('fecha_pago', [$this->fechaInicio, $this->fechaFin]);
        }
        
        $sumaTotal = $sumaTotal->get();
        
        $suma = 0;
        foreach($sumaTotal as $e){
            $temp_total = $e->moneda == "USD" ? floatval($e->total * $e->cambioDolar) : floatval($e->total);
            $suma = $suma + $temp_total;
        }
        return number_format($suma, 2, '.', ',');
    }
    
    public function json($query){
        
        foreach($query as $e){
            $consult[] = array(
                'a'=>$e->fecha_pago,
                'b'=>$e->nombreGasto,
                'c'=>$e->beneficiario,
                'd'=>$e->forma_pago,
                'e'=>$e->referencia,
                'f'=>$e->etiquetas,
                'g'=>$e->descripcion,
                'h'=>$e->moneda.' $'.$e->factura,
                'i'=>$e->iva.'%',
                'j'=>$e->moneda.' $'.$e->total_iva,
                'k'=>$e->isr.'%',
                'l'=>$e->moneda.' $'.$e->total_isr,
                'm'=>$e->moneda.' $'.$e->total,
                'n'=>$total_en_pesos = $e->moneda == "USD" ? 'MXN $'.$e->total * $e->cambioDolar : 'MXN $'.$e->total,
                'o'=>$e->nombreCuenta 
            );
        }

        return $consult;
    }
    
    public function view(): View
    {
        $data = $this->query();
        $suma = $this->sumaTotal();
        // dd($data);

        return view( 'excelGastosOperativos', compact( 'data',  'suma'));
    }
}