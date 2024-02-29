<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Exports\FacturasExport;
use App\Exports\FacturaExport;
use Maatwebsite\Excel\Facades\Excel;
use Session;

class HomeController  extends Controller
{

	public function export(){
        return Excel::download(new FacturasExport, 'facturas.xlsx');
    }
	

	public function expor(){

        return Excel::download(new FacturaExport, 'fac.xlsx');
    }
	
  
}
