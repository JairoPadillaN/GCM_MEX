<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultipleSheetsGastosOperativosExport implements WithMultipleSheets 
{
    protected $fechaInicio;
    protected $fechaFinal;
    protected $empresa;
    
    public function __construct($fechaInicio, $fechaFinal, $empresa )
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFinal = $fechaFinal;
        $this->empresa = $empresa;
    }
    
    public function sheets(): array 
    {   
        return [
            new EstadoDeResultadosExport($this->fechaInicio,$this->fechaFinal,$this->empresa),
            new IngresosExport($this->fechaInicio,$this->fechaFinal,$this->empresa),
            new GastosOperativosTodosExport($this->fechaInicio,$this->fechaFinal,$this->empresa),
            new ReporteCuentaContableExport($this->fechaInicio,$this->fechaFinal,$this->empresa),
            new ReporteGeneralDeGastosExport($this->fechaInicio,$this->fechaFinal,$this->empresa),
        ];
    }    
}
