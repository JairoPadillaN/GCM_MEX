<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Session;
use App\gastosOperativos;
use App\gastos;
use App\Http\Controllers\gastos as gastos1;
use App\facturas;
use App\anexGrid;
use DB;
use File;
use App\Exports\GastosOperativosExport;
use Maatwebsite\Excel\Facades\Excel;

class gastosOperativosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
    
        if ($sname == '' or $sidu == '' or $stipo == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {
            $gastos = GastosOperativos::all();
            $bancos = \DB::select("SELECT idb,nombre FROM bancos WHERE activo='si'");
            $cuentasData = \DB::table('cuentas')->where('activo', 'Si')->pluck('nombreCuenta', 'idCuenta')->toArray();
    
            return view('gastosOperativos')
                ->with('gastos', $gastos)
                ->with('bancos', $bancos)
                ->with('cuentasData', $cuentasData);
        }
    }
    
    public function ind()
    {
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
    
        if ($sname == '' or $sidu == '' or $stipo == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {
            $gastos = GastosOperativos::all();
            $bancos = \DB::select("SELECT idb,nombre FROM bancos WHERE activo='si'");
            $cuentasData = \DB::table('cuentas')->where('activo', 'Si')->pluck('nombreCuenta', 'idCuenta')->toArray();
    
            return view('gastosOperativos2')
                ->with('gastos', $gastos)
                ->with('bancos', $bancos)
                ->with('cuentasData', $cuentasData);
        }
    }

    public function getDataGastos(Request $request, $fechaInicio=null, $fechaFin=null) {
        //return [$fechaInicio, $fechaFin];
        try{
            $anexGrid = new anexGrid();

            $beneficiario = '';
            $referencia = '';
            $etiquetas = '';
            $descripcion = '';
            $total = 0;

            $wh = "id > 0";

            foreach($anexGrid->filtros as $f){
                //Filtro para beneficiario
                if($f['columna'] == 'beneficiario' && $f['valor'] != ''){
                    $beneficiario = $f['valor'];
                }
                if($f['columna'] == 'beneficiario' && $f['valor'] == ''){
                    $beneficiario = '';
                }
                if($f['columna'] == 'empresa' && $f['valor'] != ''){
                    $empresa = $f['valor'];
                }
                if($f['columna'] == 'cuenta' && $f['valor'] != ''){
                    $cuenta = $f['valor'];
                }
                if($f['columna'] == 'referencia' && $f['valor'] != ''){
                    $referencia = $f['valor'];
                }
                if ($f['columna'] == 'referencia' && $f['valor'] == '') {
                    $referencia = '';
                }
  
                if($f['columna'] == 'etiquetas' && $f['valor'] != ''){
                    $etiquetas = $f['valor'];
                }
                if ($f['columna'] == 'etiquetas' && $f['valor'] == '') {
                    $etiquetas = '';
                }
                if($f['columna'] == 'descripcion' && $f['valor'] != ''){
                    $descripcion = $f['valor'];
                }
                if ($f['columna'] == 'descripcion' && $f['valor'] == '') {
                    $descripcion = '';
                }
            }
            
            if($fechaInicio !== null && $fechaFin !== null){
                $gastos = DB::table('gastosOperativos')
                    ->leftJoin('cuentas', 'gastosOperativos.idCuenta', '=', 'cuentas.idCuenta')
                    ->select('id', 'beneficiario', 'forma_pago', \DB::raw('cuentas.empresaCuenta as empresa'), \DB::raw('UPPER(cuentas.nombreCuenta) as cuenta'), 'referencia', 'etiquetas', 'descripcion', 'factura', 'iva', 'total_iva', 'isr', 'total_isr', 'total', 'gastosOperativos.activo', 'moneda', 'cambioDolar',
                            'fecha_pago')
                    ->where('beneficiario', 'like', '%'.$beneficiario.'%')
                    ->where('gastosOperativos.referencia', 'like', '%'.$referencia.'%')
                    ->where('gastosOperativos.etiquetas', 'like', '%'.$etiquetas.'%')
                    ->where('gastosOperativos.descripcion', 'like', '%'.$descripcion.'%')
                    ->where('fecha_pago', '>=',$fechaInicio)
                    ->where('fecha_pago', '<=', $fechaFin)
                    ->orderBy('fecha_pago', 'desc')
                    ->skip($anexGrid->pagina)
                    ->take($anexGrid->limite)
                    ->get();
                $total = DB::table('gastosOperativos')
                    ->select('gastosOperativos.fecha_pago', 'gastosOperativos.beneficiario', 'gastosOperativos.forma_pago', 'gastosOperativos.referencia', 'gastosOperativos.etiquetas', 'gastosOperativos.descripcion', 'gastosOperativos.factura', 'gastosOperativos.iva', 'gastosOperativos.total_iva', 'gastosOperativos.isr', 'gastosOperativos.total_isr', 'gastosOperativos.total', 'gastosOperativos.activo')
                    ->where('fecha_pago', '>=',$fechaInicio)
                    ->where('fecha_pago', '<=', $fechaFin)
                    ->count();
            }else{
                $gastos = DB::table('gastosOperativos')
                    ->leftJoin('cuentas', 'gastosOperativos.idCuenta', '=', 'cuentas.idCuenta')
                    ->select('gastosOperativos.id', 'gastosOperativos.beneficiario', 'gastosOperativos.forma_pago', \DB::raw('UPPER(cuentas.empresaCuenta) as empresa'), \DB::raw('cuentas.nombreCuenta as cuenta'), 'gastosOperativos.referencia', 'gastosOperativos.etiquetas', 'gastosOperativos.descripcion', 'gastosOperativos.factura', 'gastosOperativos.iva', 'gastosOperativos.total_iva', 'gastosOperativos.isr', 'gastosOperativos.total_isr', 'gastosOperativos.total', 'gastosOperativos.activo', 'gastosOperativos.moneda', 'gastosOperativos.cambioDolar',
                            'gastosOperativos.fecha_pago')
                    ->where('gastosOperativos.beneficiario', 'like', '%'.$beneficiario.'%')
                    ->where('gastosOperativos.referencia', 'like', '%'.$referencia.'%')
                    ->where('gastosOperativos.etiquetas', 'like', '%'.$etiquetas.'%')
                    ->where('gastosOperativos.descripcion', 'like', '%'.$descripcion.'%')
                    ->orderBy('gastosOperativos.fecha_pago', 'desc')
                    ->skip($anexGrid->pagina)
                    ->take($anexGrid->limite)
                    ->get();
                $total = DB::table('gastosOperativos')
                    ->select('gastosOperativos.fecha_pago', 'gastosOperativos.beneficiario', 'gastosOperativos.forma_pago', 'gastosOperativos.referencia', 'gastosOperativos.etiquetas', 'gastosOperativos.descripcion', 'gastosOperativos.factura', 'gastosOperativos.iva', 'gastosOperativos.total_iva', 'gastosOperativos.isr', 'gastosOperativos.total_isr', 'gastosOperativos.total', 'gastosOperativos.activo')
                    ->count();
            }
            
            $data = array(
                'data' => $gastos,
                'total' => $total
            );

            return response()->json($data, 200);
        }
        catch(Exception $e){
            die($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

        if($sname == '' or $sidu == '' or $stipo == ''){
            Session::flash('error', 'Es necesario logearse antes de continar');
            return redirect()->route('login');
        }else{
            //return $cuentas;
            return view("altaGastosOperativos");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

        if($sname == '' or $sidu == '' or $stipo == ''){
            Session::flash('error', 'Es necesario logearse antes de continar');
            return redirect()->route('login');
        }else{
            try{
                $gastos = DB::table('gastosOperativos')
                            ->insert([
                                'nombreGasto' => $request->nombreGasto,
                                'beneficiario' => $request->beneficiario,
                                'BANCO' => $request->BANCO,
                                'idCuenta' => $request->idCuenta,
                                'fecha_pago' => $request->fecha_pago,
                                'metodo_pago' => $request->metodo_pago,
                                'forma_pago' => $request->forma_pago,
                                'credito_dias' => $request->credito_dias == 'di' ? "No aplica" : $request->credito_dias,
                                'referencia' => $request->referencia,
                                'cambioDolar' => $request->cambioDolar,
                                'empresa' => $request->empresa,
                                'moneda' => $request->moneda,
                                'etiquetas' => $request->etiquetas,
                                'factura' => $request->factura,
                                'iva' => $request->iva,
                                'total_iva' => $request->total_iva,
                                'isr' => $request->isr,
                                'total_isr' => $request->total_isr,
                                'total' => $request->total,
                                'descripcion' => $request->descripcion,
                                'created_at' => date('Y-m-d h:i:s'),
                                'updated_at' => date('Y-m-d h:i:s'),
                            ]);
                //$gastos = GastosOperativos::create($request->all());
                return redirect()->route('GastosOperativos');
           }
            catch(Exception $e){
                return "Ocurrio un error";
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $fechaInicio = $_GET['fechaInicio'];
        $fechaFin = $_GET['fechaFin'];
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

        if($sname == '' or $sidu == '' or $stipo == ''){
            Session::flash('error', 'Es necesario logearse antes de continar');
            return redirect()->route('login');
        }else{
            $gastos = GastosOperativos::all();
            $bancos=\DB::select("SELECT idb,nombre FROM bancos WHERE activo='si'");
            $cuentas=\DB::select("SELECT * FROM cuentas WHERE activo='Si'");
            return view('gastosOperativos')
                ->with('gastos', $gastos)
                ->with('bancos', $bancos)
                ->with('cuentas', $cuentas)
                ->with('fechaInicio', $fechaInicio)
                ->with('fechaFin', $fechaFin);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

        if($sname == '' or $sidu == '' or $stipo == ''){
            Session::flash('error', 'Es necesario logearse antes de continar');
            return redirect()->route('login');
        }
         if ($stipo == 'Administrador') { 
 
        $gasto = GastosOperativos::find($id);
        //$id_factura = $gasto[0]->id_factura;
        //$servicio_id = \DB::select("SELECT idServicios FROM facturas WHERE idFactura=(SELECT id_factura FROM gastosOperativos WHERE id=$id)");
        //var_dump( $servicio_id[0]->idServicios );
        $archivos=\DB::select("SELECT id,nombre_archivo, descripcion,activo,created_at,updated_at FROM archivos_gastosOperativos WHERE id_gastosOperativos=$id ORDER BY activo ASC");
        $cuentas = \DB::select("SELECT idCuenta,empresaCuenta,numeroCu,nombreCuenta FROM cuentas");
        $bancos=\DB::select("SELECT idb,nombre FROM bancos WHERE activo='si'");
        //$cuentas = \DB::select("SELECT * FROM cuentas WHERE empresaCuenta = $gasto->empresa");
        $forma_pago= explode ( "','", substr (\DB::select("SHOW COLUMNS FROM gastos LIKE 'forma_pago'")[0]->Type,6,-2));
        $metodo_pago= explode ( "','", substr (\DB::select("SHOW COLUMNS FROM gastos LIKE 'metodo_pago'")[0]->Type,6,-2));
        $credito_dias= explode ( "','", substr (\DB::select("SHOW COLUMNS FROM gastos LIKE 'credito_dias'")[0]->Type,6,-2));
         //var_dump( $p[0]);
            return view('editarGastosOperativos')
                ->with('gasto',$gasto)
                ->with('archivos',$archivos)
                ->with('cuentas',$cuentas)
                ->with('metodo_pago',$metodo_pago)
                ->with('credito_dias',$credito_dias)
                ->with('bancos', $bancos);
         } else{
         return redirect()->back();
       
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        
        $this->validate($request,[
            'nombreGasto'=>'required', 
            'beneficiario'=>'required', 
            'fecha_pago'=>'required', 
            'credito_dias'=>'required',
            'forma_pago'=>'required', 
            'metodo_pago'=>'required', 
            'etiquetas'=>'required', 
            'referencia'=>'required', 
            'factura'=>'required', 
            'idCuenta'=>'required', 
            'iva'=>'required|numeric',
            'isr'=>'required|numeric', 
            'descripcion'=>'required'
        ]);
 
        $gasto = GastosOperativos::find($id);
        $gasto->nombreGasto = $request->nombreGasto;
        $gasto->beneficiario = $request->beneficiario;
        if($request->idCuenta != null){
            $gasto->idCuenta = $request->idCuenta;
        }
        $gasto->fecha_pago = $request->fecha_pago;/*date("d/m/y", strtotime($request->fecha_pago));*/
        $gasto->metodo_pago = $request->metodo_pago;
        $gasto->cambioDolar = $request->cambioDolar;
        $gasto->forma_pago = $request->forma_pago;
        $gasto->credito_dias = $request->credito_dias == 'No aplica' ? 'No aplica' : substr($request->credito_dias, 0, 2);
        $gasto->referencia = $request->referencia;
        $gasto->etiquetas = $request->etiquetas;
        $gasto->factura = $request->factura;
        $gasto->iva = $request->iva;
        $gasto->total_iva = $request->total_iva ;
        $gasto->isr = $request->isr;
        $gasto->total_isr = $request->total_isr ;
        $gasto->empresa = $request->empresa;
        if($request->BANCO != null){
            $gasto->banco = $request->BANCO;
        }
        $gasto->total = $request->total;
        $gasto->descripcion = $request->descripcion;

        $gasto->save();

        $gasto = GastosOperativos::find($id);
        //$id_factura = $gasto[0]->id_factura;
        //$servicio_id = \DB::select("SELECT idServicios FROM facturas WHERE idFactura=(SELECT id_factura FROM gastosOperativos WHERE id=$id)");
        //var_dump( $servicio_id[0]->idServicios );
        $archivos=\DB::select("SELECT id,nombre_archivo, descripcion,activo,created_at,updated_at FROM archivos_gastosOperativos WHERE id_gastosOperativos=$id ORDER BY activo ASC");
        $cuentas = \DB::select("SELECT idCuenta,empresaCuenta,numeroCu,nombreCuenta FROM cuentas");
        $bancos=\DB::select("SELECT idb,nombre FROM bancos WHERE activo='si'");
        //$cuentas = \DB::select("SELECT * FROM cuentas WHERE empresaCuenta = $gasto->empresa");
        $forma_pago= explode ( "','", substr (\DB::select("SHOW COLUMNS FROM gastos LIKE 'forma_pago'")[0]->Type,6,-2));
        $metodo_pago= explode ( "','", substr (\DB::select("SHOW COLUMNS FROM gastos LIKE 'metodo_pago'")[0]->Type,6,-2));
        $credito_dias= explode ( "','", substr (\DB::select("SHOW COLUMNS FROM gastos LIKE 'credito_dias'")[0]->Type,6,-2));
         //var_dump( $p[0]);
            return view('editarGastosOperativos')
                ->with('gasto',$gasto)
                ->with('archivos', $archivos)
                ->with('cuentas',$cuentas)
                ->with('metodo_pago',$metodo_pago)
                ->with('credito_dias',$credito_dias)
                ->with('bancos', $bancos)
                ->with('mensaje', "Registro actualizado correctamente");
    }

    public function upload(Request $request, $id){
        $this->validate($request,['file'=>'required','descripcion_file'=>'required']);    
        $file = $request->file('file');
        $created_at = date('Y-m-d h:i:s');
        $descripcion = $request->descripcion_file;
        if($request->hasFile('file')){
            // foreach ($files as $file) {
            // $file->store('users/' . $this->user->id . '/messages');
			$fechaxx = date_create();
            $name = date_timestamp_get($fechaxx) . strtolower($file->getClientOriginalName());
            $file->move(public_path().'/archivos', $name);  
            \DB::insert("INSERT INTO archivos_gastosOperativos (id_gastosOperativos,nombre_archivo,descripcion, created_at) VALUES($id,'$name','$descripcion', '$created_at')");
            //}
            return redirect()->back()->with('success','Comprobante subido correctamente');
        }
        //var_dump($request);
    }

    public function download($name){
        $file=public_path().'/archivos/'.$name;
        return Response::download($file);    
    }

    public function cuentas($empresa){
        $query = \DB::select("SELECT idCuenta,nombreCuenta FROM cuentas WHERE empresaCuenta='$empresa'"); 
        return json_encode($query);   
    }

    public function baja_logica($id)
    {
      $sname = Session::get('sesionname');
      $sidu = Session::get('sesionidu');
      $stipo = Session::get('sesiontipo');
  
      if($sname == '' or $sidu =='' or $stipo==''){
        Session::flash('error', 'Es necesario logearse antes de continuar');
        return redirect()->route('login');
      }
      else{
        $gasto = GastosOperativos::all()->where('id', $id)->first();
        if($gasto->activo == "Si"){
          $gasto->activo = "No";
          $gasto->save();
          return redirect()->route('GastosOperativos');
        }else if($gasto->activo == "No"){
          $gasto->activo = "Si";
          $gasto->save();
          return redirect()->route('GastosOperativos');
        }
      }
    }
    public function baja_logica_comprobante($id){
        $activo = $_POST['activo'];
        $file = $_POST['file'];
        if($activo=='Si'){
            $query = \DB::update("UPDATE archivos_gastosOperativos SET activo='No' WHERE id=$id");
            if( $query == 1){
                $result = 'Comprobante desactivado correctamente';
            } 
        } else if ($activo=='No'){
            $query = \DB::delete("DELETE FROM archivos_gastos WHERE id=$id");
            if( $query == 1){
                File::delete(public_path().'/archivos/'.$file);
                $result = 'Comprobante eliminado completamente';
            }
        }
        echo json_encode($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function gastosOperativosEmpresa(Request $request, $id){
        $gasto = GastosOperativos::find($id);

        $gasto->empresa = $request->empresa;
        $gasto->idCuenta = $request->idCuenta;

        $gasto->save();
        return redirect()->back();
    }

    public function exportar_excel_gastosOperativos($fechaInicio, $fechaFin, $filtro, $value){

        $nombre_file = 'reporte_gastos'.date('Y-m-d h:i:s');

        return Excel::download(new GastosOperativosExport($fechaInicio, $fechaFin, $filtro, $value), $nombre_file.'.xlsx');
    }

    public function getServiciosList($id){
        $comboFacturas =  \DB::select('SELECT f.idServicios,f.idc,f.idSucursal, 
        c.razonSocial AS cliente, s.sucursal As sucursal FROM facturas as f              
        INNER JOIN sucursales AS s ON s.idSucursal= f.idSucursal
        INNER JOIN clientes AS c ON c.idc= f.idc
        ORDER BY idServicios DESC');

        //$newServicios = array_unique($newServicios, SORT_REGULAR);
        return $comboFacturas;
    }

    public function getGastosList($idGasto){
        $consultaGastos = \DB::select("SELECT t2.id,t2.idServicios,c.razonSocial AS cliente,s.sucursal AS sucursal,t2.total,t2.created_at
            FROM(
            SELECT t1.id,t1.idServicios,fact.idc,fact.idSucursal,t1.total,t1.created_at
            FROM(
                SELECT id,idServicios,total,created_at FROM gastos WHERE idGastoOperativo = $idGasto AND activo = 'Si'
                ) AS t1   
            INNER JOIN facturas AS fact ON fact.idServicios= t1.idServicios
            ) AS t2
            INNER JOIN clientes AS c ON c.idc= t2.idc
            INNER JOIN sucursales AS s ON s.idSucursal= t2.idSucursal
            ORDER BY t2.created_at DESC");
        return $consultaGastos;
    }

    public function deleteGastoServicio($idGasto){
        $gasto = Gastos::find($idGasto);
        $gasto->delete();
        return 'Ok';
    }

    public function asignarServicioGasto(Request $request){

        $gastos = \DB::table('gastos')->select('idServicios')
                        ->where('idGastoOperativo', $request->idGastoOperativo)
                        ->where('idServicios', $request->idServicio)
                        ->get();
        if(count($gastos) == 1){
            return "registro_repetido";
        }

        $idFactura = Facturas::all()->where('idServicios', '=', $request->idServicio)->first();
        $request->request->add(array('id_factura' => "$idFactura->idFactura"));
        $total = $request->total;
        //return $total;

        $gastoOperativo = GastosOperativos::find($request->idGastoOperativo);

        $gasto = new Gastos;
        $gasto->id_factura = $idFactura->idFactura; 
        $gasto->nombreGasto = $gastoOperativo->nombreGasto; 
        $gasto->beneficiario = $gastoOperativo->beneficiario; 
        $gasto->BANCO = $gastoOperativo->BANCO; 
        $gasto->idCuenta = $gastoOperativo->idCuenta; 
        $gasto->metodo_pago = $gastoOperativo->metodo_pago; 
        $gasto->fecha_pago = $gastoOperativo->fecha_pago; 
        $gasto->forma_pago = $gastoOperativo->forma_pago; 
        $gasto->credito_dias = $gastoOperativo->credito_dias == "No aplica" || $gastoOperativo->credito_dias == "di" ? null : $gastoOperativo->credito_dias; 
        $gasto->referencia = $gastoOperativo->referencia; 
        $gasto->etiquetas = $gastoOperativo->etiquetas; 
        $gasto->factura = $total; 
        $gasto->empresa = $gastoOperativo->empresa; 
        $gasto->iva = 0; 
        $gasto->total_iva = 0; 
        $gasto->isr = 0; 
        $gasto->total_isr = 0; 
        $gasto->total = $total; 
        $gasto->descripcion = $gastoOperativo->descripcion; 
        $gasto->TIPOGASTO = null; 
        $gasto->activo = 'Si'; 
        $gasto->moneda = $gastoOperativo->moneda; 
        $gasto->cambioDolar = $gastoOperativo->cambioDolar; 
        $gasto->created_at = date('Y-m-d h:i:s'); 
        $gasto->updated_at = date('Y-m-d h:i:s'); 
        $gasto->idServicios = $request->idServicio;
        $gasto->idGastoOperativo = $request->idGastoOperativo;

        $gasto->save();

        return $gastoOperativo;
    }
}
