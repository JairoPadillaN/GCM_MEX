<?php

namespace App\Exports;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\viajes;
use App\vehiculos;
use App\clientes;
use App\usuarios;
use App\facturas;
use App\bancos;
use App\statuses;
use App\Exports\FacturasExport;
use DB;
use Session;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FacturasExport implements FromCollection,WithHeadings
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {

        return [
            
            'Folio de Factura',
            'Fecha de Facturacion ',
            'Fecha de Vencimiento ',
            'Fecha de Pago',
            'Banco',
            'Tipo de Moneda',
            'Monto de la Factura',
            'Periodo de Pago',
            'Cliente',
            'Sucursal',
            'Saldo',
            'Tipo Cambio Factura',
            'Tipo cambio Real',
            'Monto en Pesos',
            'Orden de Compra',
            'Factura',
            'Comprobante de Pago',
            'Metodo de Pago',
            'Estatus de Entrega',
            'estatus en Portal',
            'Estatus de Pago',
            'Cash Flow',
            'Nùmero de Remisiòn',
            'NùMero de Entrada',
            'Complemento del Pago',
            'Observaciones',


        ];
    }
    public function collection()
    {
    $stipo = Session::get('sesiontipo');
    $idusu = Session::get('idusu');



    if ($stipo == 'Administrador') {
       $consulta = DB::table('facturas')
    ->join('bancos', 'facturas.idb', '=', 'bancos.idb')
    ->join('clientes', 'facturas.idc', '=', 'clientes.idc')
    ->join('sucursales','facturas.idSucursal','=','sucursales.idSucursal')
    ->select('facturas.numeroFactura','facturas.fechaFactura','fechaPago','fechaVencimiento','bancos.nombre','tipoMoneda','montoFactura','periodoPago','clientes.razonSocial','sucursales.sucursal','saldo','cambioFactura','cambioReal','montoPesos','ordenCompra','factura','comprobantePago','metodoPago','estatusEntrega','estatusPortal','estatusPago','cashFlow','numeroRemision','numeroEntrada','complementoPago','observacionesFactura')
    ->get(); 

    return $consulta; 
    }
    else{
        $consulta = DB::table('facturas')
    ->join('bancos', 'facturas.idb', '=', 'bancos.idb')
    ->join('clientes', 'facturas.idc', '=', 'clientes.idc')
    ->join('sucursales','facturas.idSucursal','=','sucursales.idSucursal')
    ->select('facturas.numeroFactura','facturas.fechaFactura','fechaPago','fechaVencimiento','bancos.nombre','tipoMoneda','montoFactura','periodoPago','clientes.razonSocial','sucursales.sucursal','saldo','cambioFactura','cambioReal','montoPesos','ordenCompra','factura','comprobantePago','metodoPago','estatusEntrega','estatusPortal','estatusPago','cashFlow','numeroRemision','numeroEntrada','complementoPago','observacionesFactura')
    ->whereIn('idFactura')->select('asignacionesdetalles')


    //WHERE idFactura IN(SELECT idFactura AS fac FROM asignacionesdetalles WHERE idu=?)",[$sidu]
    ->get(); 

    return $consulta; 
    }
    



        
    }
}