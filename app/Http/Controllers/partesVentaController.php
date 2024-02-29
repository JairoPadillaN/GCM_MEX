<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\marcaRefaccion;
use App\tiporefacciones;
use App\detallepaquetes;
use App\partesVenta;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Exports\PartesExport;
use App\anexGrid;
use App\costoEnvioDetalle;
use Session;
use DB;

class partesVentaController extends Controller
{
    public function altaPartesVenta(){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
        
        if($sname == '' or $sidu =='' or $stipo==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        // $marca = marcaRefaccion::orderBy('marcaRefaccion','asc')->get();
        $marca=\DB::select("SELECT * FROM marcasRefaccion ORDER BY marcaRefaccion ASC");
        return view('altaPartesVenta')
        ->with('stipo',$stipo)
        ->with('marca',$marca);
    }
    public function guardarParteVenta(Request $request){
        $idMarcaRefa = $request->idMarcaRefa;
        $idTipoRefacciones= $request->idTipoRefacciones;
        $numeroParte= $request->numeroParte;
        $parapaquete= $request->parapaquete;
        $this->validate($request,[
            'idMarcaRefa' => ['required'],
            'idTipoRefacciones' => ['required'],
            'numeroParte' => ['required']
        ]);
        $parte = new partesVenta;
        $parte ->nombreRefaccion = $request->nombreRefaccion;
        $parte ->numeroParte = $request->numeroParte;
        $parte ->serie = $request->serie;
        $parte ->codigo = $request->codigo;
        $parte ->modelo = $request->modelo;
        $parte ->notaInterna = $request->notaInterna;
        $parte ->skuEquivalente = $request->skuEquivalente;
        $parte ->semanasEntrega = $request->semanasEntrega;
        $parte ->presentacion = $request->presentacion;
        $parte ->unidades = $request->unidades;
        $parte ->ubicacion = $request->ubicacion;
        $parte ->precioCompraPe = $request->precioCompraPe;
        $parte ->precioVentaPe = $request->precioVentaPe;
        $parte ->precioCompraDol = $request->precioCompraDol;
        $parte ->precioVentaDol = $request->precioVentaDol;
        /* $parte ->fechaActualizacion = $request->fechaActualizacion; */
        $parte->fechaActualizacion = \Carbon\Carbon::now()->setTimezone('America/Mexico_City');
        $parte ->estatus = $request->estatus;
        $parte ->tipoProducto = $request->tipoProducto;
        $parte ->cantidad = $request->cantidad;
        $parte ->activo = 'Si';
        $parte ->idMarcaRefa = $request->idMarcaRefa;
        $parte ->idTipoRefacciones = $request->idTipoRefacciones;
        $parte ->parapaquete = $request->parapaquete;
        $parte ->codigoHts = $request->codigoHTS;
        $parte ->valorHts = $request->valorHTS;
        $parte ->codigoIgi = $request->codigoIGI;
        $parte ->valorIgi = $request->valorIGI;
        $parte ->largo = $request->largoInch;
        $parte ->ancho = $request->anchoInch;
        $parte ->alto = $request->altoInch;
        $parte ->peso = $request->pesoLb;
        $parte->created_at = \Carbon\Carbon::now()->setTimezone('America/Mexico_City');
        $parte->updated_at = \Carbon\Carbon::now()->setTimezone('America/Mexico_City');
        $parte->save();
        $proceso='Alta de partes';
        $mensaje="Parte registrada correctamente";
        return view ('mensajePartesVenta')
        ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);
    }

    public function reportePartesVenta(){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
        
        if($sname == '' or $sidu =='' or $stipo==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        return view ('reportePartesVenta')
        ->with('stipo',$stipo);
    }

    public function filtroPartesVenta(){
        $anexGrid = new anexGrid();
        $order;
        $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

        $nombreRefaccion = '';
        $marcaRefaccion = '';
        $nombreTipoRefaccion = '';
        $codigo = '';
        $numeroParte = '';
        $serie = '';
        $presentacion = '';
        $tipoProducto = '';
        $estatus = '';
        $cantidad = '';

        foreach($anexGrid->filtros as $f)
            {
                if($f['columna'] == 'nombreRefaccion' && $f['valor'] != ''){
                    $nombreRefaccion = $f['valor'];
                }
                if($f['columna'] == 'marcaRefaccion' && $f['valor'] != ''){
                    $marcaRefaccion = $f['valor'];
                }
                if($f['columna'] == 'nombreTipoRefaccion' && $f['valor'] != ''){
                    $nombreTipoRefaccion = $f['valor'];
                }
                if($f['columna'] == 'codigo' && $f['valor'] != ''){
                    $codigo = $f['valor'];
                }
                if($f['columna'] == 'numeroParte' && $f['valor'] != ''){
                    $numeroParte = $f['valor'];
                }
                if($f['columna'] == 'serie' && $f['valor'] != ''){
                    $serie = $f['valor'];
                }
                if($f['columna'] == 'presentacion' && $f['valor'] != ''){
                    $presentacion = $f['valor'];
                }
                if($f['columna'] == 'estatus' && $f['valor'] != ''){
                    $estatus = $f['valor'];
                }
                if($f['columna'] == 'tipoProducto' && $f['valor'] != ''){
                    $tipoProducto = $f['valor'];
                }
                if($f['columna'] == 'cantidad' && $f['valor'] != ''){
                    $cantidad = $f['valor'];
                }
            }


        $registros = DB::table('partesVenta')            
                ->select('idPartesVenta','nombreRefaccion','marcaRefaccion','nombreTipoRefaccion','numeroParte','codigo',
                            'partesVenta.activo','presentacion','estatus','tipoProducto','cantidad',
                            DB::raw('CONCAT_WS(" / Equiv. ",codigo,skuEquivalente) AS skuEquivalente'),
                            DB::raw('CONCAT("MXN $ ", FORMAT(precioVentaPe,2)) AS precioVentaPe'),
                            DB::raw('CONCAT("USD $ ", FORMAT(precioVentaDol,2)) AS precioVentaDol'))
                ->leftJoin('marcasRefaccion', 'partesVenta.idMarcaRefa', '=', 'marcasRefaccion.idMarcaRefa')  
                ->leftJoin('tiporefacciones', 'partesVenta.idTipoRefacciones', '=', 'tiporefacciones.idTipoRefacciones')  
                ->where('nombreRefaccion','like','%'.$nombreRefaccion.'%')
                ->where('marcaRefaccion','like','%'.$marcaRefaccion.'%')
                ->where('nombreTipoRefaccion','like','%'.$nombreTipoRefaccion.'%')
                ->where('numeroParte','like','%'.$numeroParte.'%')
                ->where('cantidad','like','%'.$cantidad.'%')
                ->where('codigo','like','%'.$codigo.'%')
                ->where('presentacion','like','%'.$presentacion.'%')
                ->where('tipoProducto','like','%'.$tipoProducto.'%')
                ->where('estatus','like','%'.$estatus.'%')
                ->where(function($query) {
                    $anexGrid = new anexGrid();
                    $skuEquivalente='';
                    foreach($anexGrid->filtros as $f)
                      {
                        if($f['columna'] == 'skuEquivalente' && $f['valor'] != ''){
                          $skuEquivalente = $f['valor'];
                        }
                      }
                    $query->where('codigo', 'like', '%'.$skuEquivalente.'%')
                    ->orwhere('skuEquivalente', 'like', '%'.$skuEquivalente.'%');
                  })
                ->orderBy($anexGrid->columna, $order)
                ->skip($anexGrid->pagina)
                ->take($anexGrid->limite)
                ->get();
        
        $total = DB::table('partesVenta')            
                ->select('idPartesVenta','nombreRefaccion','marcaRefaccion','nombreTipoRefaccion','numeroParte','codigo',
                            'partesVenta.activo','presentacion','estatus','tipoProducto','cantidad',
                            DB::raw('CONCAT("MXN $ ", FORMAT(precioVentaPe,2)) AS precioVentaPe'),
                            DB::raw('CONCAT("USD $ ", FORMAT(precioVentaDol,2)) AS precioVentaDol'))
                ->leftJoin('marcasRefaccion', 'partesVenta.idMarcaRefa', '=', 'marcasRefaccion.idMarcaRefa')  
                ->leftJoin('tiporefacciones', 'partesVenta.idTipoRefacciones', '=', 'tiporefacciones.idTipoRefacciones')  
                ->where('nombreRefaccion','like','%'.$nombreRefaccion.'%')
                ->where('marcaRefaccion','like','%'.$marcaRefaccion.'%')
                ->where('nombreTipoRefaccion','like','%'.$nombreTipoRefaccion.'%')
                ->where('numeroParte','like','%'.$numeroParte.'%')
                ->where('cantidad','like','%'.$cantidad.'%')
                ->where('codigo','like','%'.$codigo.'%')
                ->where('presentacion','like','%'.$presentacion.'%')
                ->where('tipoProducto','like','%'.$tipoProducto.'%')
                ->where('estatus','like','%'.$estatus.'%')
                ->count();

        $data = array(
            'total' => $total,
            'data' => $registros
        );

     

        return response()->json($data, 200);
    }

    public function filtroEquiposVenta(){
        $anexGrid = new anexGrid();
        $order;
        $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

        $nombreRefaccion = '';
        $marcaRefaccion = '';
        $nombreTipoRefaccion = '';
        $codigo = '';
        $numeroParte = '';
        $serie = '';
        $presentacion = '';
        $tipoProducto = '';
        $estatus = '';
        $cantidad = '';

        foreach($anexGrid->filtros as $f)
            {
                if($f['columna'] == 'nombreRefaccion' && $f['valor'] != ''){
                    $nombreRefaccion = $f['valor'];
                }
                if($f['columna'] == 'marcaRefaccion' && $f['valor'] != ''){
                    $marcaRefaccion = $f['valor'];
                }
                if($f['columna'] == 'nombreTipoRefaccion' && $f['valor'] != ''){
                    $nombreTipoRefaccion = $f['valor'];
                }
                if($f['columna'] == 'codigo' && $f['valor'] != ''){
                    $codigo = $f['valor'];
                }
                if($f['columna'] == 'numeroParte' && $f['valor'] != ''){
                    $numeroParte = $f['valor'];
                }
                if($f['columna'] == 'serie' && $f['valor'] != ''){
                    $serie = $f['valor'];
                }
                if($f['columna'] == 'presentacion' && $f['valor'] != ''){
                    $presentacion = $f['valor'];
                }
                if($f['columna'] == 'estatus' && $f['valor'] != ''){
                    $estatus = $f['valor'];
                }
                if($f['columna'] == 'tipoProducto' && $f['valor'] != ''){
                    $tipoProducto = $f['valor'];
                }
                if($f['columna'] == 'cantidad' && $f['valor'] != ''){
                    $cantidad = $f['valor'];
                }
            }


        $registros = DB::table('partesVenta')            
                ->select('idPartesVenta','nombreRefaccion','marcaRefaccion','nombreTipoRefaccion','numeroParte','codigo',
                            'partesVenta.activo','presentacion','estatus','tipoProducto','cantidad',
                            DB::raw('CONCAT_WS(" / Equiv. ",codigo,skuEquivalente) AS skuEquivalente'),
                            DB::raw('CONCAT("MXN $ ", FORMAT(precioVentaPe,2)) AS precioVentaPe'),
                            DB::raw('CONCAT("USD $ ", FORMAT(precioVentaDol,2)) AS precioVentaDol'))
                ->leftJoin('marcasRefaccion', 'partesVenta.idMarcaRefa', '=', 'marcasRefaccion.idMarcaRefa')  
                ->leftJoin('tiporefacciones', 'partesVenta.idTipoRefacciones', '=', 'tiporefacciones.idTipoRefacciones')  
                ->where('nombreRefaccion','like','%'.$nombreRefaccion.'%')
                ->where('marcaRefaccion','like','%'.$marcaRefaccion.'%')
                ->where('nombreTipoRefaccion','like','%'.$nombreTipoRefaccion.'%')
                ->where('numeroParte','like','%'.$numeroParte.'%')
                ->where('cantidad','like','%'.$cantidad.'%')
                ->where('codigo','like','%'.$codigo.'%')
                ->where('presentacion','like','%'.$presentacion.'%')
                ->where('tipoProducto','like','%'.$tipoProducto.'%')
                ->where('estatus','like','%'.$estatus.'%')
                ->where(function($query) {
                    $anexGrid = new anexGrid();
                    $skuEquivalente='';
                    foreach($anexGrid->filtros as $f)
                      {
                        if($f['columna'] == 'skuEquivalente' && $f['valor'] != ''){
                          $skuEquivalente = $f['valor'];
                        }
                      }
                    $query->where('codigo', 'like', '%'.$skuEquivalente.'%')
                    ->orwhere('skuEquivalente', 'like', '%'.$skuEquivalente.'%');
                  })
                ->orderBy($anexGrid->columna, $order)
                ->skip($anexGrid->pagina)
                ->take($anexGrid->limite)
                ->get();
        
        $total = DB::table('partesVenta')            
                ->select('idPartesVenta','nombreRefaccion','marcaRefaccion','nombreTipoRefaccion','numeroParte','codigo',
                            'partesVenta.activo','presentacion','estatus','tipoProducto','cantidad',
                            DB::raw('CONCAT("MXN $ ", FORMAT(precioVentaPe,2)) AS precioVentaPe'),
                            DB::raw('CONCAT("USD $ ", FORMAT(precioVentaDol,2)) AS precioVentaDol'))
                ->leftJoin('marcasRefaccion', 'partesVenta.idMarcaRefa', '=', 'marcasRefaccion.idMarcaRefa')  
                ->leftJoin('tiporefacciones', 'partesVenta.idTipoRefacciones', '=', 'tiporefacciones.idTipoRefacciones')  
                ->where('nombreRefaccion','like','%'.$nombreRefaccion.'%')
                ->where('marcaRefaccion','like','%'.$marcaRefaccion.'%')
                ->where('nombreTipoRefaccion','like','%'.$nombreTipoRefaccion.'%')
                ->where('numeroParte','like','%'.$numeroParte.'%')
                ->where('cantidad','like','%'.$cantidad.'%')
                ->where('codigo','like','%'.$codigo.'%')
                ->where('presentacion','like','%'.$presentacion.'%')
                ->where('tipoProducto','like','%'.$tipoProducto.'%')
                ->where('estatus','like','%'.$estatus.'%')
                ->count();

        $data = array(
            'total' => $total,
            'data' => $registros
        );

     

        return response()->json($data, 200);

    }


    public function modificarPartesVenta($idPartesVenta){
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
        if($sname == '' or $sidu =='' or $stipo==''){
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        
        $consulta = partesVenta::where('idPartesVenta', '=', $idPartesVenta)->get();
        $idr=$consulta[0]->idTipoRefacciones;
        $idsel=$consulta[0]->idMarcaRefa;
        $marcaSel = marcaRefaccion::where('idMarcaRefa','=', $consulta[0]->idMarcaRefa)->get();
        $nombreMarca = $marcaSel[0]->marcaRefaccion;
        $marca = marcaRefaccion::where('idMarcaRefa','!=', $consulta[0]->idMarcaRefa)->get();
        // $marca = marcaRefaccion::orderBy('idMarcaRefa','asc')->get();
        $refaccionSel = tiporefacciones::where('idTipoRefacciones', '=', $consulta[0]->idTipoRefacciones)->get();
        $tipoRefaccion = $refaccionSel[0]->nombreTipoRefaccion;
        $refaccion = tiporefacciones::where('idTipoRefacciones', '!=', $consulta[0]->idTipoRefacciones)->get();
        $refa = \DB::select("SELECT * FROM tiporefacciones WHERE idTipoRefacciones!=$idr AND idMarcaRefa = $idsel");        
        $consultaCE = \DB::table('costoEnvioDetalle')
        ->where('idPartesVenta', '=', $idPartesVenta)
        ->get();

        $cuantosCE = count($consultaCE);

        if($cuantosCE != 0){
            $consultaCED = \DB::select("SELECT *  FROM costoEnvioDetalle WHERE idPartesVenta = $idPartesVenta AND activo = 'Si'");
        }
                
        return view ('editarPartesVenta')
        ->with('marca',$marca)
        ->with('tipoRefaccion',$tipoRefaccion)
        ->with('nombreMarca',$nombreMarca)
        ->with('refa',$refa)
        ->with('idsel',$idsel)
        ->with('stipo',$stipo)
        ->with('idr',$idr)
        ->with('cuantosCE',$cuantosCE)
        ->with('consultaCED', $cuantosCE != 0 ? $consultaCED : null)
        ->with('consulta',$consulta[0]);
        // return $idsel;
    }

    public function editarPartesVenta (Request $request){
        $idMarcaRefa=$request-> idMarcaRefa;
        $idTipoRefacciones=$request-> idTipoRefacciones;
        $codigo=$request-> codigo;
        $serie=$request-> serie;
        $numeroParte=$request-> numeroParte;
        $modelo=$request-> modelo;
        $precioVentaPe=$request-> precioVentaPe;
        $precioVentaDol=$request-> precioVentaDol;
        $idPartesVenta = $request->idPartesVenta;
        $parte = partesVenta::find($idPartesVenta);

        // Foto para la pagina web
        $file = $request->file('fotoWeb');
        //obtenemos el nombre del archivo
        if($file!=""){
        $fotoWeb =  time()."_".$file->getClientOriginalName();
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::putFileAs('/archivosWeb/fotosWeb',$file,$fotoWeb);
        }
            else{
              $fotoWeb = $parte ->fotoWeb;
            }

            // Archivo 1

        $file1 = $request->file('archUnoWeb');
        if($file1!=""){
            $archUnoWeb =  time()."_A1_".$file1->getClientOriginalName();
            //indicamos que queremos guardar un nuevo archivo en el disco local
            \Storage::putFileAs('/archivosWeb',$file1,$archUnoWeb);
            }
                else{
                    $archUnoWeb = $parte ->archUnoWeb ;
                }
                // Archivo 2
        $file2 = $request->file('archDosWeb');
        if($file2!=""){
            $archDosWeb =  time()."_A2_".$file2->getClientOriginalName();
            //indicamos que queremos guardar un nuevo archivo en el disco local
            \Storage::putFileAs('/archivosWeb',$file2,$archDosWeb);
            }
                else{
                    $archDosWeb = $parte ->archDosWeb;
                }
                // Archivo 3
        $file3 = $request->file('archTresWeb');
        if($file3!=""){
            $archTresWeb =  time()."_A3_".$file3->getClientOriginalName();
            //indicamos que queremos guardar un nuevo archivo en el disco local
            \Storage::putFileAs('/archivosWeb',$file3,$archTresWeb);
            }
                else{
                    $archTresWeb = $parte ->archTresWeb;
                }
                // Archivo 4
        $file4 = $request->file('archCuatroWeb');
        if($file4!=""){
            $archCuatroWeb =  time()."_A4_".$file4->getClientOriginalName();
            //indicamos que queremos guardar un nuevo archivo en el disco local
            \Storage::putFileAs('/archivosWeb',$file4,$archCuatroWeb);
            }
                else{
                    $archCuatroWeb = $parte ->archCuatroWeb;
                }


        $idPartesVenta = $request->idPartesVenta;
        $parte = partesVenta::find($idPartesVenta);
        $parte ->nombreRefaccion = $request->nombreRefaccion;
        $parte ->numeroParte = $request->numeroParte;
        $parte ->serie = $request->serie;
        $parte ->modelo = $request->modelo;
        $parte ->codigo = $request->codigo;
        $parte ->notaInterna = $request->notaInterna;
        $parte ->skuEquivalente = $request->skuEquivalente;
        $parte ->semanasEntrega = $request->semanasEntrega;
        $parte ->presentacion = $request->presentacion;
        $parte ->unidades = $request->unidades;
        $parte ->ubicacion = $request->ubicacion;
        $parte ->precioCompraPe = $request->precioCompraPe;
        $parte ->precioVentaPe = $request->precioVentaPe;
        $parte ->precioCompraDol = $request->precioCompraDol;
        $parte ->precioVentaDol = $request->precioVentaDol;
        $parte ->fechaActualizacion = $request->fechaActualizacion;        
        $parte ->estatus = $request->estatus;
        $parte ->tipoProducto = $request->tipoProducto;
        $parte ->cantidad = $request->cantidad;
        $parte ->activo = 'Si';
        $parte ->idMarcaRefa = $request->idMarcaRefa;
        $parte ->idTipoRefacciones = $request->idTipoRefacciones;
        $parte ->parapaquete = $request->parapaquete;
        $parte ->visualWeb = $request->visualWeb;
        $parte ->visualCostoWeb = $request->visualCostoWeb;
        $parte ->descWeb = $request->descWeb;
        $parte ->costoWeb = $request->costoWeb;
        $parte ->caractWeb = $request->caractWeb;
        $parte ->descLongWeb = $request->descLongWeb;
        $parte ->codifWeb = $request->codifWeb;
        $parte ->datosTecWeb = $request->datosTecWeb;
        $parte ->diagramWeb = $request->diagramWeb;
        $parte ->simbolWeb = $request->simbolWeb;
        $parte ->consElectrWeb = $request->consElectrWeb;
        $parte ->dimWeb = $request->dimWeb;
        $parte ->datosPedWeb = $request->datosPedWeb;
        $parte ->addressWeb = $request->addressWeb;
        $parte ->nombreWeb = $request->nombreWeb;
        $parte ->fotoWeb = $fotoWeb;
        $parte ->archUnoWeb = $archUnoWeb;
        $parte ->archDosWeb = $archDosWeb;
        $parte ->archTresWeb = $archTresWeb;
        $parte ->archCuatroWeb = $archCuatroWeb;
        $parte ->codigoHts = $request->codigoHTS;
        $parte ->valorHts = $request->valorHTS;
        $parte ->codigoIgi = $request->codigoIGI;
        $parte ->valorIgi = $request->valorIGI;
        $parte ->largo = $request->largoInch;
        $parte ->ancho = $request->anchoInch;
        $parte ->alto = $request->altoInch;
        $parte ->peso = $request->pesoLb;
        $parte->updated_at = \Carbon\Carbon::now()->setTimezone('America/Mexico_City');

        $parte->save();

        detallepaquetes::where('idPartesVenta', '=', $idPartesVenta)
        ->update([
        'idMarcaRefa' => $idMarcaRefa,
        'idTipoRefacciones' => $idTipoRefacciones,
        'codigo' => $codigo,
        'serie' => $serie,
        'numeroParte' => $numeroParte,
        'modelo' => $modelo,
        'precioVentaPe' => $precioVentaPe,
        'precioVentaDol' => $precioVentaDol
        ]);
     



        $proceso='Modificación de partes';
        $mensaje="Parte modificada correctamente";
        return view ('mensajePartesVenta')
        ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);
    }

    public function eliminarPartesVenta($idPartesVenta){
        $partes = \DB::UPDATE("update partesVenta
        set activo ='No' where idPartesVenta=$idPartesVenta");

        $proceso ="Eliminación de partes";
        $mensaje="La parte ha sido desactivada correctamente";
        return view('mensajePartesVenta')
            ->with('proceso',$proceso)
            ->with('mensaje',$mensaje);
    }
    public function restaurarPartesVenta($idPartesVenta){
        $partes = \DB::UPDATE("update partesVenta
        set activo ='Si' where idPartesVenta=$idPartesVenta");

        $proceso ="Restauración de partes";
        $mensaje="La parte ha sido activada correctamente";
        return view('mensajePartesVenta')
            ->with('proceso',$proceso)
            ->with('mensaje',$mensaje);
    }

    public function comboTipoRefaccion(Request $request){
        $idMarcaRefa = $request->idMarcaRefa;
        $consulta = tiporefacciones::where('idMarcaRefa', '=', $idMarcaRefa)->orderBy('nombreTipoRefaccion','asc')->get();
        return view('comboTipoRefaccion')
        ->with('consulta',$consulta);
    }

    public function generarCodigo(Request $request){
        $idMarcaRefa = $request->idMarcaRefa;
        $idTipoRefacciones = $request->idTipoRefacciones;
        $numeroParte = $request->numeroParte;

        $marca= \DB::select("SELECT codigoMarca from marcasRefaccion where idMarcaRefa=?", [$idMarcaRefa]);
        $refacciones = \DB::select("SELECT codigoTipoRef FROM tiporefacciones WHERE idTipoRefacciones=?", [$idTipoRefacciones]);
        return view('codigoTipoRefaccion')
        ->with('marca',$marca[0])
        ->with('refacciones',$refacciones[0])
        ->with('numeroParte',$numeroParte);
    }

    public function guardarCostoEnvio(Request $request){   
        $idPartesVenta = $request->idPartesVentaCE;         
        $costos = new costoEnvioDetalle();
        $costos->idPartesVenta = $request->idPartesVentaCE;
        $costos->compañia = $request->compañia;
        $costos->moneda = $request->monedaCE;
        $costos->monto = $request->montoCE;
        $costos->servicio = $request->servicioCE;
        $costos->fecha = $request->fechaCE;        
        $costos->activo = "Si";        
        $costos->save();

        $consulta = \DB::select("SELECT * from costoEnvioDetalle WHERE idPartesVenta=$idPartesVenta AND activo = 'Si' ");
        return view('reporteCostosDeEnvio')
        ->with('consultaCED',$consulta);
    }

    public function borrarCostoEnvio(Request $request)
    {
        $idced = $request->idced;
        $idPartesVenta = $request->idPartesVentaCE;
        $costoEnvio = costoEnvioDetalle::find($idced);

        if ($costoEnvio) {
            $costoEnvio->activo = 'No';
            $costoEnvio->save();
        }

        $consulta = \DB::select("SELECT * from costoEnvioDetalle WHERE idPartesVenta=$idPartesVenta AND activo = 'Si' ");
        return view('reporteCostosDeEnvio')
        ->with('consultaCED',$consulta);
    }
}
