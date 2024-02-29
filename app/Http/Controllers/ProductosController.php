<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\partesVenta;
use App\Models\marcasRefaccion;
use App\Models\tiporefacciones;
use PDF;

class ProductosController extends Controller
{
    public function productos(Request $req){
        $title = 'Productos';

        // $consulta = \DB::select("SELECT pv.idpartesventa, pv.nombreWeb, pv.visualCostoWeb, pv.costoWeb,
        // m.idmarcarefa, m.marcarefaccion , tr.nombretiporefaccion, tr.idtiporefacciones
        // FROM partesVenta AS pv
        // INNER JOIN tiporefacciones AS tr ON pv.idTipoRefacciones = pv.idTipoRefacciones
        // INNER JOIN marcasRefaccion as m ON m.idmarcarefa = pv.idmarcarefa
        // WHERE pv.visualWeb = 'si'
        // AND tr.activo = 'si'
        // AND  pv.presentacion = 'pieza'
        // -- GROUP BY pv.nombreRefaccion
        // ORDER BY m.marcarefaccion ASC");
        
        $consulta =  partesVenta::join('tiporefacciones', 'partesVenta.idTipoRefacciones','=', 'tiporefacciones.idTipoRefacciones')
        ->join('marcasRefaccion','marcasRefaccion.idMarcaRefa','=', 'partesVenta.idMarcaRefa')
        ->select('marcasRefaccion.idmarcarefa', 'marcasRefaccion.marcarefaccion','tiporefacciones.nombretiporefaccion',
        'tiporefacciones.idtiporefacciones', 'partesVenta.idpartesventa', 'partesVenta.nombreWeb' , 'partesVenta.descWeb','partesVenta.fotoWeb',
        'partesVenta.visualCostoWeb', 'partesVenta.costoWeb' ,'partesVenta.archUnoWeb' )
        ->where('partesVenta.activo', 'Si') 
        ->where('partesVenta.visualWeb', 'Si') 
        ->where('tiporefacciones.activo', 'Si')
        ->where('partesVenta.presentacion', 'pieza')
        // ->groupBY('tiporefacciones.nombretiporefaccion')
        ->orderBy('marcasRefaccion.marcarefaccion', 'asc' )
        ->get();
        
        // dd($consulta);

        return view('paginaweb.productospagina')
        ->with('consulta',$consulta)
        ->with('title',$title);

    }

    public function detalle(partesventa $partesventa){
        $this->idMarca = $partesventa['idMarcaRefa'];
        $this->idRefa = $partesventa['idTipoRefacciones'];
        $title = 'Caracteristicas';

        $infoProduct = partesVenta::join('tiporefacciones', 'partesVenta.idTipoRefacciones','=', 'tiporefacciones.idTipoRefacciones')
        ->select('partesVenta.idPartesVenta', 'partesVenta.idMarcaRefa', 'partesVenta.nombreWeb','partesVenta.fotoWeb','partesVenta.nombreRefaccion', 'partesVenta.archUnoWeb', 'partesVenta.archDosWeb',
        'partesVenta.archTresWeb','partesVenta.archCuatroWeb','partesVenta.descWeb','partesVenta.caractWeb', 'partesVenta.descLongWeb', 
        'partesVenta.codifWeb', 'partesVenta.datosTecWeb', 'partesVenta.diagramWeb', 'partesVenta.simbolWeb', 
        'partesVenta.consElectrWeb', 'partesVenta.dimWeb' )
        ->where('partesVenta.idPartesVenta','=', $partesventa['idPartesVenta'])
        ->get();


        $consulta = \DB::select("SELECT m.marcarefaccion , tr.nombretiporefaccion, tr.idtiporefacciones
        FROM marcasRefaccion AS m 
        INNER JOIN tiporefacciones AS tr ON tr.idmarcarefa = m.idmarcarefa
        WHERE tr.activo = 'si'
        AND m.idmarcarefa = $this->idMarca AND tr.idtiporefacciones = $this->idRefa
        ORDER BY m.marcarefaccion, tr.nombretiporefaccion ASC");

        // dd($consulta);

        return view('paginaweb.productos_detalle')
        ->with('title', $title)
        ->with('consulta',$consulta)
        ->with('infoProduct', $infoProduct);
    }


    public function categoria(marcasrefaccion $marcasrefaccion){      
        $title = 'cat';

        $categories = marcasRefaccion::join('partesVenta','marcasRefaccion.idMarcaRefa','=', 'partesVenta.idMarcaRefa')
        ->select('partesVenta.idMarcaRefa', 'marcasRefaccion.marcaRefaccion')
        ->where('partesVenta.visualWeb', 'Si') 
        ->where('marcasRefaccion.activo', 'Si')
        ->groupBY('partesVenta.idMarcaRefa','marcasRefaccion.idMarcaRefa')
        ->get();

        $cc= $marcasrefaccion['idMarcaRefa'];

        $subcategories = tiporefacciones::join('marcasRefaccion', 'tiporefacciones.idMarcaRefa', '=', 'marcasRefaccion.idMarcaRefa')
        ->join('partesVenta', 'tiporefacciones.idTipoRefacciones', '=', 'partesVenta.idTipoRefacciones')
        ->select('tiporefacciones.idTipoRefacciones', 'tiporefacciones.nombreTipoRefaccion', 'tiporefacciones.idMarcaRefa', 'marcasRefaccion.marcaRefaccion')
        ->where('tiporefacciones.activo', 'Si')
        ->where('partesVenta.visualWeb', 'Si')
        ->where('tiporefacciones.idMarcaRefa' ,'=', $cc)
        ->get();

        for($i=0; $i < count($subcategories); $i++){
            $idRef = $subcategories[$i]['idTipoRefacciones'];
            
            $sqlinfo = partesVenta::join('tiporefacciones', 'partesVenta.idTipoRefacciones','=', 'tiporefacciones.idTipoRefacciones')
            ->select('partesVenta.idPartesVenta', 'partesVenta.nombreRefaccion', 'partesVenta.caractWeb', 'partesVenta.visualCostoWeb', 
            'partesVenta.fotoWeb', 'partesVenta.costoWeb', 'tiporefacciones.idTipoRefacciones', 'tiporefacciones.nombreTipoRefaccion' )
            ->where('partesVenta.visualWeb', 'Si')
            ->where('partesVenta.activo', 'Si')
            ->where('partesVenta.idTipoRefacciones' ,'=', $idRef)
            ->get();
        
            $arrinfo[$i] = $sqlinfo;   
            
        } 

        
        // dd($arrinfo[0]['fotoWeb']);


        return view('productos_subcategoria')
        ->with('title', $title)
        ->with('marcasrefaccion', $marcasrefaccion)
        ->with('subcategories', $subcategories)
        // ->with('arrInfoCat', $infoCat)
        ->with('arrInfo', $arrinfo);
    }

    
    public function downloadFile($pdf){
        
        $pdfv = '/archivos/archivosWeb/'. $pdf;
        

        $file=public_path().'/archivos/archivosWeb/'.$pdf;
        return Response::download($file);

        
        // return response()->file('/archivos/archivosWeb/'.$pdf);
        // return Response::download('/archivos/archivosWeb/', $pdf, [], 'inline');
        
    }

    

   
}