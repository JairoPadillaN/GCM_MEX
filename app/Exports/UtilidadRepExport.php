<?php

namespace App\Exports;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\ordenCompra;
use App\cuentas;
use App\proveedores;
use Session;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\facturasController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;
use \Illuminate\Support\Collection;

class UtilidadRepExport implements FromCollection, WithHeadings
{   protected $fechaInicio;
    protected $fechaFin; 
    protected $idservicios;
    protected $fechafactura;
    protected $fechapago;
    protected $cliente;
    protected $sucursal;
    protected $montofactura;
    protected $montopesos;
    protected $numerofactura;  
    protected $totalgastos;
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($fechaInicio = null,$fechaFin = null,$idservicios = null,$fechafactura = null,$fechapago = null,$cliente = null,$sucursal = null,$montofactura = null, $numerofactura = null, $montopesos = null, $totalgastos = null)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin; 
        $this->idservicios = $idservicios;
        $this->fechafactura = $fechafactura;
        $this->fechapago = $fechapago;
        $this->cliente = $cliente;
        $this->sucursal = $sucursal;
        $this->montofactura = $montofactura;
        $this->montopesos = $montopesos;
        $this->numerofactura = $numerofactura;  
        $this->totalgastos = $totalgastos;    
    }
    public function headings(): array
    {
        return[
            'Folio de Servicio asignado ',
            'Folio de la Factura',
            'Fecha del Servicio',
            'Fecha del pago',
            'Cliente',
            'Sucursal',
            'Monto Cotización',
            'Total',
            'Total Gastos',
            'Total Compras',
            'Utilidad'
        ];
    }
    public function collection()
    {
    $stipo = Session::get('sesiontipo');
    $idusu = Session::get('idusu');



    if ($stipo == 'Administrador') {
       $query = DB::table('REPORTEUTILIDADES')            
                    ->select('idservicios', 
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
                    'utilidad');
                    
       /*if($this->fechaInicio!='null'){
            $query->where('fechafactura','>=',$this->fechaInicio); 
        }
        if($this->fechaFin!='null'){
                $query->where('fechafactura','<=',$this->fechaFin);
        }*/
        if($this->idservicios!='null'){
                $query->where('idservicios','LIKE','%'.$this->idservicios.'%');
        }
        if($this->numerofactura!='null'){
                $query->where('numerofactura','LIKE','%'.$this->numerofactura.'%');
        }
        if($this->cliente!='null'){
            $query->where('cliente','LIKE','%'.$this->cliente.'%');
        }
        if($this->sucursal!='null'){
                    $query->where('sucursal','LIKE','%'.$this->sucursal.'%');
        }
        if($this->montofactura!='null')
        {
                $query->where('montofactura','LIKE','%'.$this->montofactura.'%');
        }
        if($this->montopesos!='null')
        {
                 $query->where('montopesos','LIKE','%'.$this->montopesos.'%');
        }
        
        if($this->totalgastos!='null'){
                 $query->where('totalgastos','LIKE','%'.$this->totalgastos.'%');
        }
        
    $data = $query->orderBy('fechafactura', 'DESC')->get(); 
      $count = count($data) +1; 
    //return $data;
    //$export = [];
    foreach($data as $e)
          {
              $ex_array[]=array(
                
                'idservicios'=>$e->idservicios,
                'numerofactura'=>$e->numerofactura,
                'fechafactura'=>$e->fechafactura,
                'fechapago'=>$e->fechapago,
                'cliente'=>$e->cliente,
                'sucursal'=>$e->sucursal,
                'montofactura'=>$e->montofactura,
                'montopesos' => $e->montopesos,
                'totalgastos' => $e->totalgastos,
                'totaloc' => $e->totaloc,
                'utilidad' => $e->utilidad,


            );
    
    }
    array_push($ex_array,['','','','','','Total:','','=SUM(H2:H'.$count.')','=SUM(I2:I'.$count.')','=SUM(J2:J'.$count.')','=SUM(K2:K'.$count.')']);
        return new Collection($ex_array);
  }
    
    else{
          
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
