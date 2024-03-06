<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultipleSheetsGlobalVentasExport implements WithMultipleSheets 
{
    protected $fecha;
    protected $empresa;
    
    public function __construct($fecha, $empresa )
    {
        $this->fecha = $fecha;
        $this->empresa = $empresa;
    }
    
    public function sheets(): array 
    {   
        return [            
            new DetalleFacturasExport($this->fecha,$this->empresa),            
            new ReporteEmpresaExport($this->fecha,$this->empresa),            
            new ReporteVendedorExport($this->fecha,$this->empresa),            
        ];
    }    
}
