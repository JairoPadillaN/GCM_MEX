<?php

namespace App\Exports;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Exports\GastosExport;
use DB;
use Session;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithPreCalculateFormulas;
use \Illuminate\Support\Collection;

class GastosExport implements FromCollection, WithHeadings, WithEvents,WithPreCalculateFormulas
{
    protected $fechaInicio;
    protected $fechaFin;
    protected $nombreGasto;
    protected $beneficiario;
    protected $empresa;
    protected $cuenta;
    protected $cliente;
    protected $sucursal;
    protected $referencia;

    public function __construct($fechaInicio = null,$fechaFin = null,$nombreGasto = null,$beneficiario = null,$empresa = null,$cuenta = null,$cliente = null,$sucursal = null,$referencia = null)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin; 
        $this->nombreGasto = $nombreGasto;
        $this->beneficiario = $beneficiario;
        $this->empresa = $empresa;
        $this->cuenta = $cuenta;
        $this->cliente = $cliente;
        $this->sucursal = $sucursal;
        $this->referencia = $referencia;    
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {

        return [
            
            'Folio de Factura',
            'Folio de Servicio',
            'Nombre del gasto',
            'Beneficiario',
            'Empresa',
            'Cuenta',
            'Cliente',
            'Sucursal',
            'Fecha de Pago',
            'Referencia',
            'Factura',
            'Total IVA',
            'Total ISR',
            'Total',
            'Activo',

        ];
    }
    public function collection()
    {
    $stipo = Session::get('sesiontipo');
    $idusu = Session::get('idusu');



    if ($stipo == 'Administrador') {
       $query = DB::table('gastos')            
                    ->select('gastos.id_factura','facturas.idServicios','gastos.nombreGasto','gastos.beneficiario','cuentas.empresaCuenta as empresa','cuentas.nombreCuenta','facturas.nombreEmpresa as cliente','sucursales.sucursal',DB::raw('DATE_FORMAT(gastos.fecha_pago, "%d/%m/%Y") as fecha_pago'),'referencia','etiquetas','gastos.factura','gastos.total_iva','gastos.total_isr',DB::raw('(CASE WHEN gastos.moneda = "USD" THEN TRUNCATE(gastos.total*cambioDolar,2) ELSE gastos.total END) AS total'),'gastos.activo')
                    ->leftJoin('cuentas', 'gastos.idCuenta', '=', 'cuentas.idCuenta')
                    ->leftJoin('facturas', 'gastos.id_factura', '=', 'facturas.idFactura')
                    ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
                    ->where('gastos.activo','=','Si');
        if($this->fechaInicio!='null'){
                $query->where('gastos.fecha_pago','>=',$this->fechaInicio); 
        }
        if($this->fechaFin!='null'){
                $query->where('gastos.fecha_pago','<=',$this->fechaFin);
        }
        if($this->nombreGasto!='null'){
                $query->where('gastos.nombreGasto','LIKE','%'.$this->nombreGasto.'%');
        }
        if($this->beneficiario!='null'){
                $query->where('gastos.beneficiario','LIKE','%'.$this->beneficiario.'%');
        }
        if($this->empresa!='null'){
                $query->where('cuentas.empresaCuenta','LIKE','%'.$this->empresa.'%');
        }
        if($this->cuenta!='null'){
                 $query->where('cuentas.nombreCuenta','LIKE','%'.$this->cuenta.'%');
        }
        if($this->cliente!='null'){
                 $query->where('facturas.nombreEmpresa','LIKE','%'.$this->cliente.'%');
        }
        if($this->sucursal!='null'){
                 $query->where('sucursales.sucursal','LIKE','%'.$this->sucursal.'%');
        }
        if($this->referencia!='null'){
                 $query->where('gastos.referencia','LIKE','%'.$this->referencia.'%');
        }
        
    $data = $query->orderBy('gastos.fecha_pago', 'DESC')->get(); 
      $count = count($data) +1; 
    //return $data;
    //$export = [];
    foreach($data as $e)
          {
              $ex_array[]=array(
                
                'id_factura'=>$e->id_factura,
                'idServicios'=>$e->idServicios,
                'nombreGasto'=>$e->nombreGasto,
                'beneficiario'=>$e->beneficiario,
                'empresa'=>$e->empresa,
                'nombreCuenta'=>$e->nombreCuenta,
                'cliente'=>$e->cliente,
                'sucursal'=>$e->sucursal,
                'fecha_pago'=>$e->fecha_pago,
                'referencia' => $e->referencia,
                'factura' => $e->factura,
                'total_iva' => $e->total_iva,
                'total_isr' => $e->total_isr,
                'total' => $e->total,
                'activo' => $e->activo

            );
    
    }
    array_push($ex_array,['Total:','', '','','','','','','','','','=SUM(L2:L'.$count.')','=SUM(M2:M'.$count.')', '=SUM(N2:N'.$count.')','=SUM(O2:O'.$count.')','']);
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