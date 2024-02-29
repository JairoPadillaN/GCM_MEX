<?php

namespace App\Exports;

use App\partesVenta;
use Maatwebsite\Excel\Concerns\FromCollection;

class PartesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       // return partesVenta::take(1)->get();
    }
    /*protected $proj_id;

    public function __construct($proj_id)
    {
       $this->proj_id = $proj_id;
    }

    public function collection()
    {
        return Unit::where('project_id', $this->proj_id)->get();
    }*/

}
