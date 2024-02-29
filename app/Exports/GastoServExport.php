<?php

namespace App\Exports;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\gastos;
use App\descripcions;
use App\proveedores;
use DB;
use Session;
use App\Http\Controllers\facturasController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;
use \Illuminate\Support\Collection;

class GastoServExport implements FromCollection, WithHeadings
{
    protected $fecha_pago;
    protected $beneficiario; 
    protected $forma_pago;
    protected $referencia;
    protected $etiquetas;
    protected $descripcion;
    protected $factura;
    protected $iva;
    protected $total_iva;
    protected $isr;  
    protected $total_isr;
    protected $total;
    protected $totalMXN;

    public function __construct($fecha_pago = null,
    $beneficiario = null,
    $forma_pago = null,
    $referencia = null,
    $etiquetas = null,
    $descripcion = null,
    $factura = null,
    $iva = null,
    $total_iva = null,
    $isr = null,
    $total_isr = null,
    $total = null,
    $totalMXN = null)
    {
        $this->fecha_pago = $fecha_pago;
        $this->beneficiario = $beneficiario; 
        $this->forma_pago = $forma_pago;
        $this->referencia = $referencia;
        $this->etiquetas = $etiquetas;
        $this->descripcion = $descripcion;
        $this->factura = $factura;
        $this->iva = $iva;
        $this->total_iva = $total_iva; 
        $this->isr = $isr; 
        $this->total_isr = $total_isr; 
        $this->total = $total; 
        $this->totalMXN = $totalMXN;    
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return[
            'Fecha de Pago',
            'Beneficiario',
            'Forma de Pago',
            'Referencia',
            'Etiqueta',
            'Descripción',
            'Subtotal',
            'IVA %',
            'IVA $',
            'ISR %',
            'ISR $',
            'Moneda',
            'Total',
            'Total $'
        ];
    }
    public function collection()
    {
    $stipo = Session::get('sesiontipo');
    $idusu = Session::get('idusu');

    if ($stipo == 'Administrador'){
        $query = DB::table('gastos')
                     ->select(
                     'fecha_pago',
                     'beneficiario',
                     'forma_pago',
                     'referencia',
                     'etiquetas',
                     'descripcion',
                     'factura',
                     'iva',
                     'total_iva',
                     'isr',
                     'total_isr',
                     'moneda',
                     DB::raw('(factura + total_iva + total_isr)as total'),
                     DB::raw('IF(moneda = "USD",total*cambiodolar,total) as totalMXN')                     
                     );
                /*if($this->fecha_pago!='null'){
                        $query->where('fecha_pago','>=',$this->fecha_pago); 
                }
                if($this->beneficiario!='null'){
                        $query->where('beneficiario','<=',$this->beneficiario);
                }
                if($this->forma_pago!='null'){
                        $query->where('forma_pago','LIKE','%'.$this->forma_pago.'%');
                }
                if($this->referencia!='null'){
                        $query->where('referencia','LIKE','%'.$this->referencia.'%');
                }
                if($this->etiquetas!='null'){
                        $query->where('etiquetas','LIKE','%'.$this->etiquetas.'%');
                }
                if($this->descripcion!='null'){
                         $query->where('descripcion','LIKE','%'.$this->descripcion.'%');
                }
                if($this->factura!='null'){
                         $query->where('factura','LIKE','%'.$this->factura.'%');
                }
                if($this->iva!='null'){
                         $query->where('iva','LIKE','%'.$this->iva.'%');
                }
                if($this->total_iva!='null'){
                         $query->where('total_iva','LIKE','%'.$this->total_iva.'%');
                }
                if($this->isr!='null'){
                    $query->where('isr','LIKE','%'.$this->isr.'%');
                }
                if($this->total_isr!='null'){
                    $query->where('total_isr','LIKE','%'.$this->total_isr.'%');
                }
                if($this->total!='null'){
                    $query->where('total','LIKE','%'.$this->total.'%');
                }
                if($this->totalMXN!='null'){
                    $query->where('totalMXN','LIKE','%'.$this->totalMXN.'%');
                }*/
        $data = $query->orderby('fecha_pago','DESC')->get();
        $count = count($data)+1;
        foreach($data as $e)
        {
            $ex_array[]=array(
                'fecha_pago'=>$e->fecha_pago,
                'beneficiario'=>$e->beneficiario,
                'forma_pago'=>$e->forma_pago,
                'referencia'=>$e->referencia,
                'etiquetas'=>$e->etiquetas,
                'descripcion'=>$e->descripcion,
                'factura'=>$e->factura,
                'iva'=>$e->iva,
                'total_iva'=>$e->total_iva,
                'isr'=>$e->isr,
                'total_isr'=>$e->total_isr,
                'moneda'=>$e->moneda,
                'total'=>$e->total,
                'totalMXN'=>$e->totalMXN
            );
        }
        array_push($ex_array,['','','','','','Total','','','','','','','','=SUM(N2:N'.$count.')']);
        return new Collection($ex_array);
     
       }   else{
          
    }
    
        
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
   
                $event->sheet->getDelegate()->getStyle('A1:P1')
                                ->getFont()
                                ->setBold(true);
   
            },
        ];
    }
    
}
