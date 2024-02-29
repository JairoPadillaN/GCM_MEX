<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use DB;
use Session;
use File;
use App\anexGrid;
use App\gastos;
use App\archivosGastos;
use App\Exports\GastosExport;
use App\Exports\GastoServExport;
use Maatwebsite\Excel\Facades\Excel;

class gastosController extends Controller
{
    /**
     * Display a listing of the resource.
     * Consulta los gastos de acuerdo al id del servicio
     *@param $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empresa = $_GET['empresa'];
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

        $cuentas=\DB::select("SELECT * FROM cuentas WHERE empresaCuenta='$empresa' AND activo='Si'");
        $bancos=\DB::select("SELECT idb,nombre FROM bancos WHERE activo='si'");
        if($sname == '' or $sidu == '' or $stipo == ''){
            Session::flash('error', 'Es necesario logearse antes de continar');
            return redirect()->route('login');
        }else{
            //return $cuentas;
            return view("altaGastos", ['cuentas' => $cuentas,'bancos' => $bancos]);
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
        //return $request;
        $gastos = Gastos::create($request->all());
        
        //return $request;
        return redirect()->route('modificarFacturas', $request->id_factura);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function cuentasEmpresa($empresa){
      $cuentas = \DB::select("SELECT idCuenta as id, nombreCuenta as nombre, numeroCu 
                              FROM cuentas 
                              WHERE empresaCuenta = '$empresa' 
                              AND activo = 'si'");
      return $cuentas;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function edit($id, $idServicio)
    {   
         //\DB::select("SELECT
       //$formas_pago = 
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

        if($sname == '' or $sidu == '' or $stipo == ''){
            Session::flash('error', 'Es necesario logearse antes de continar');
            return redirect()->route('login');
        }
         if ($stipo == 'Administrador' or $stipo =='Almacen') { 
 
        $gasto = Gastos::find($id);
        //$id_factura = $gasto[0]->id_factura;
        $servicio_id = \DB::select("SELECT idServicios FROM facturas WHERE idFactura=(SELECT id_factura FROM gastos WHERE id=$id)");
        //var_dump( $servicio_id[0]->idServicios );
        $archivos=\DB::select("SELECT id,nombre_archivo, descripcion,activo,created_at,updated_at FROM archivos_gastos WHERE id_gasto=$id ORDER BY activo ASC");
        $forma_pago= explode ( "','", substr (DB::select("SHOW COLUMNS FROM gastos LIKE 'forma_pago'")[0]->Type,6,-2));
        $metodo_pago= explode ( "','", substr (DB::select("SHOW COLUMNS FROM gastos LIKE 'metodo_pago'")[0]->Type,6,-2));
        $credito_dias= explode ( "','", substr (DB::select("SHOW COLUMNS FROM gastos LIKE 'credito_dias'")[0]->Type,6,-2));
        $bancos=\DB::select("SELECT idb,nombre FROM bancos WHERE activo='si'");
         //var_dump( $p[0]);
            return view('editarGastos')->with('gasto',$gasto)->with('archivos',$archivos)->with('servicio_id',$servicio_id)->with('idServicio', $idServicio)->with('metodo_pago',$metodo_pago)->with('credito_dias',$credito_dias)->with('bancos',$bancos);
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
    public function update(Request $request, $id)

    {
        $a = ($request->metodo_pago=='PPD') ? 'required' : '';
         $this->validate($request,['nombreGasto'=>'required', 'beneficiario'=>'required', 'fecha_pago'=>'required', 'credito_dias'=>$a,'forma_pago'=>'required', 'metodo_pago'=>'required', 'etiquetas'=>'required', 'referencia'=>'required', 'subtotal'=>'required', 'cuenta'=>'required', 'banco'=>'required','moneda'=> 'required','empresa'=>'required','iva'=>'required|numeric','isr'=>'required|numeric', 'descripcion'=>'required']);

        //Gastos::find($id)->update($request->all());
         $gasto = $request->nombreGasto;
         $beneficiario = $request->beneficiario;
         $cuenta = $request->cuenta;
         $banco = $request->banco;
         $fecha_pago = $request->fecha_pago;/*date("d/m/y", strtotime($request->fecha_pago));*/
         $metodo_pago = $request->metodo_pago;
         $forma_pago = $request->forma_pago;
         $credito_dias = ($request->metodo_pago!='PPD') ? "credito_dias=NULL," : "credito_dias='$request->credito_dias',";
         $referencia = $request->referencia;
         $etiquetas = $request->etiquetas;
         $factura = $request->subtotal;
         $moneda = $request->moneda;
         $iva = $request->iva;
         $total_iva = $factura*($iva/100) ;
         $isr = $request->isr;
         $total_isr = $factura*($isr/100) ;
         $total = $request->total;
         $descripcion = $request->descripcion;

        $factura_id = \DB::select("SELECT id_factura FROM gastos WHERE id=$id");
        $query = \DB::update("UPDATE gastos SET nombreGasto='$gasto', beneficiario='$beneficiario', banco=$banco,idCuenta='$cuenta', fecha_pago='$fecha_pago',metodo_pago='$metodo_pago',forma_pago='$forma_pago',$credito_dias referencia='$referencia',etiquetas='$etiquetas',factura=$factura,moneda='$moneda',iva=$iva,total_iva=$total_iva,isr=$isr,total_isr=$total_isr,total=$total,descripcion='$descripcion' WHERE id=$id");
        //if( $query == 1){
          $result = 'Gasto actualizado correctamente';
        //}else{
          //$result = 'Algo salió mal vuelve a intentarlo';
        //}
        
        //var_dump( $factura_id[0]->idFactura);
        return redirect()->route('modificarFacturas',['idFactura'=>$factura_id[0]->id_factura])->with('success',$result);  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function upload(Request $request, $id)
    {
    $this->validate($request,['file'=>'required','descripcion_file'=>'required']);    
    $file = $request->file('file');
    $descripcion = $request->descripcion_file;
    if($request->hasFile('file'))
     {
   // foreach ($files as $file) {
       // $file->store('users/' . $this->user->id . '/messages');
       $fechaxx = date_create();
            $name = date_timestamp_get($fechaxx) . strtolower($file->getClientOriginalName());
        $file->move(public_path().'/archivos', $name);  
        \DB::insert("INSERT INTO archivos_gastos (id_gasto,nombre_archivo,descripcion) VALUES($id,'$name','$descripcion')");
    //}
    return redirect()->back()->with('success','Comprobante subido correctamente');
   
     }
//var_dump($request);
     }

    public function download($name)
    {
        $file=public_path().'/archivos/'.$name;
        return Response::download($file);
        
    }
    public function destroy($id)
    {
         // archivosGastos::find($id)->delete();
    }
    public function cuentas(Request $request)
    {
    $query = \DB::select("SELECT idCuenta,CASE WHEN numeroCu IS NULL THEN nombreCuenta ELSE CONCAT(numeroCU,' - ',nombreCuenta) END AS Cuenta FROM cuentas WHERE empresaCuenta='$request->empresa'"); 

    return json_encode($query);   
    }
     public function cuenta_gasto(Request $request)
    {
    $query = \DB::select("SELECT idCuenta FROM gastos WHERE id=$request->id"); 

    return json_encode($query);   
    }
    public function empresa_gasto(Request $request)
    {
    $query = \DB::select("SELECT empresaCuenta FROM cuentas WHERE idCuenta=(SELECT idCuenta FROM gastos WHERE id=$request->id)"); 

    return json_encode($query);   
    }

    public function baja_logica($id, $id_factura)
    {
      $sname = Session::get('sesionname');
      $sidu = Session::get('sesionidu');
      $stipo = Session::get('sesiontipo');
  
      if($sname == '' or $sidu =='' or $stipo==''){
        Session::flash('error', 'Es necesario logearse antes de continuar');
        return redirect()->route('login');
      }
      else{
        $gasto = Gastos::all()->where('id', $id)->first();
        if($gasto->activo == "Si"){
          $gasto->activo = "No";
          $gasto->save();
          return redirect()->route('modificarFacturas', $id_factura);
        }else if($gasto->activo == "No"){
          $gasto->activo = "Si";
          $gasto->save();
          return redirect()->route('modificarFacturas', $id_factura);
        }
      }
    }
    public function baja_logica_comprobante($id)
    {
        $activo = $_POST['activo'];
        $file = $_POST['file'];
        if($activo=='Si'){
        $query = \DB::update("UPDATE archivos_gastos SET activo='No' WHERE id=$id");
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

    public function reporte_gastos(Request $request){
    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }
    else{
      //$clientes1 = clientes::orderby('razonSocial','asc')->get();
      //$consulta = \DB::select("SELECT idCuenta,empresaCuenta FROM cuentas GROUP BY empresaCuenta;");
      //return $consulta;
      return view ('reporteGastos');
    }
  }

  public function app_reporte_gastos(Request $request){
    $registros = DB::table('gastos')            
    ->select('facturas.numeroFactura','gastos.id','nombreGasto','beneficiario','facturas.idServicios','cuentas.empresaCuenta as empresa','metodo_pago','clientes.razonSocial as cliente','sucursales.sucursal','forma_pago','credito_dias','referencia','etiquetas','descripcion','gastos.activo',
     DB::raw('DATE_FORMAT(gastos.fecha_pago, "%d/%m/%Y") as fecha_pago'),
     DB::raw('CONCAT(cuentas.numeroCu," - ",cuentas.nombreCuenta) AS nombreCuenta'),
     DB::raw('IF(gastos.moneda IS NULL, CONCAT("MXN $",gastos.total_iva), CONCAT("MXN $",gastos.total_iva)) AS total_iva'),
     DB::raw('IF(gastos.moneda IS NULL, CONCAT("MXN $",gastos.total_isr), CONCAT("MXN $",gastos.total_isr)) AS total_isr'),
     DB::raw('(CASE WHEN gastos.moneda IS NULL OR gastos.moneda="" THEN CONCAT("SIN MONEDA $",gastos.total) WHEN gastos.moneda IS NOT NULL AND gastos.moneda!="USD" THEN CONCAT("USD $",TRUNCATE(gastos.total/cambioDolar,2)) WHEN gastos.moneda = "USD" THEN CONCAT("USD $",gastos.total) ELSE 1 END) AS total_USD'),
     DB::raw('(CASE WHEN gastos.moneda IS NULL THEN CONCAT("MXN $",gastos.total) WHEN gastos.moneda IS NOT NULL AND gastos.moneda!="USD" THEN CONCAT(gastos.moneda," $",gastos.total) WHEN gastos.moneda = "USD" THEN CONCAT("MXN $",TRUNCATE(gastos.total*cambioDolar,2)) ELSE 1 END) AS total_MXN'))
    ->leftJoin('cuentas', 'gastos.idCuenta', '=', 'cuentas.idCuenta')
    ->leftJoin('facturas', 'gastos.id_factura', '=', 'facturas.idFactura')
    ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
    ->leftJoin('clientes', 'clientes.idc', '=', 'facturas.idc')
    ->where('gastos.activo','=','Si')->limit(100)->get();
      return response()->json($registros);
    }
  

  public function gastos_totales(Request $request){

    $sname = Session::get('sesionname');
    $sidu = Session::get('sesionidu');
    $stipo = Session::get('sesiontipo');

    if($sname == '' or $sidu =='' or $stipo==''){
      Session::flash('error', 'Es necesario logearse antes de continuar');
      return redirect()->route('login');
    }
    else{
      $fecha_inicio = $request->fecha_inicio!='' ? 'AND G.fecha_pago >= "'.$request->fecha_inicio.'"' : '';
      $fecha_fin = $request->fecha_fin!='' ? 'AND G.fecha_pago <= "'.$request->fecha_fin.'"' : '';
      $nombreGasto = $request->nombreGasto!='' ? 'AND G.nombreGasto like "%'.$request->nombreGasto.'%"' : '';
      $beneficiario = $request->beneficiario!='' ? 'AND G.beneficiario like "%'.$request->beneficiario.'%"' : '';
      $empresa = $request->empresa!='' ? 'AND C.empresaCuenta like "%'.$request->empresa.'%"' : '';
      $cuenta = $request->cuenta!='' ? 'AND C.nombreCuenta like "%'.$request->cuenta.'%"' : '';
      $cliente = $request->cliente!='' ? 'AND CL.razonSocial like "%'.$request->cliente.'%"' : '';
      $sucursal = $request->sucursal!='' ? 'AND S.sucursal like "%'.$request->sucursal.'%"' : '';
      $referencia = $request->referencia!='' ? 'AND G.referencia like "%'.$request->referencia.'%"' : '';
  
      $consulta = \DB::select("SELECT CONCAT(IFNULL(G.moneda,'MXN'),' $') moneda, IFNULL(SUM(G.factura), 0) sum_factura,IFNULL(SUM(G.iva), 0) sum_iva,IFNULL(SUM(G.total_iva), 0) sum_total_iva,IFNULL(SUM(G.isr), 0) sum_isr,IFNULL(SUM(G.total_isr), 0) sum_total_isr,CASE WHEN G.moneda='USD' THEN G.total WHEN G.moneda IS NULL OR G.moneda='' THEN 0 ELSE G.total/G.cambioDolar END sum_total1,IF(G.moneda='USD', G.total*G.cambioDolar,G.total) sum_total FROM gastos G LEFT JOIN cuentas C ON G.idCuenta=C.idCuenta LEFT JOIN facturas F ON F.idFactura=G.id_factura LEFT JOIN clientes CL ON CL.idc=F.idc LEFT JOIN sucursales S ON S.idSucursal=F.idSucursal WHERE G.activo='Si' AND G.id IS NOT NULL $fecha_inicio $fecha_fin $nombreGasto $beneficiario $empresa $cuenta $cliente $sucursal $referencia GROUP BY G.id ORDER BY fecha_pago DESC;");
      
      return json_encode($consulta);
    }
  }
  public function getDataGastos(Request $request) {
            $sname = Session::get('sesionname');
            $sidu = Session::get('sesionidu');
            $spat = Session::get('sesionpaterno');
            $smat = Session::get('sesionmaterno');
            $stipo = Session::get('sesiontipo');
            if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
                Session::flash('error', 'Es necesario logearse antes de continuar');
                return redirect()->route('login');
            }
            else{

              $anexGrid = new anexGrid();
              $nombreGasto = '';
              $beneficiario = '';
              $empresa = '';
              $nombreCuenta = '';
              $cliente = '';
              $sucursal = '';
              $referencia = '';
              $etiquetas = '';
              $descripcion = '';

              foreach($anexGrid->filtros as $f)
              {
                if($f['columna'] == 'nombreGasto' && $f['valor'] != ''){
                  $nombreGasto = $f['valor'];
                }
                if($f['columna'] == 'nombreGasto' && $f['valor'] == ''){
                  $nombreGasto = '';
                }
                if($f['columna'] == 'beneficiario' && $f['valor'] != ''){
                  $beneficiario = $f['valor'];
                }
                if($f['columna'] == 'beneficiario' && $f['valor'] == ''){
                  $beneficiario = '';
                }
                if($f['columna'] == 'empresa' && $f['valor'] != ''){
                  $empresa = $f['valor'];
                }
                if($f['columna'] == 'empresa' && $f['valor'] == ''){
                  $empresa = '';
                }
                if($f['columna'] == 'nombreCuenta' && $f['valor'] != ''){
                  $nombreCuenta = $f['valor'];
                }
                if($f['columna'] == 'nombreCuenta' && $f['valor'] == ''){
                  $nombreCuenta = '';
                }             

                if($f['columna'] == 'cliente' && $f['valor'] != ''){
                  $cliente = $f['valor'];
                } 
                if($f['columna'] == 'cliente' && $f['valor'] == '') {
                  $cliente = '';
                }
                
                if($f['columna'] == 'sucursal' && $f['valor'] != ''){
                  $sucursal = $f['valor'];
                }
                if ($f['columna'] == 'sucursal' && $f['valor'] == '') {
                  $sucursal = '';
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
              $order;
              $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';
                
                if ($stipo == 'Administrador') { 
                  
                  $registros = DB::table('gastos')            
                    ->select('facturas.numeroFactura','gastos.id','nombreGasto','beneficiario','facturas.idServicios','cuentas.empresaCuenta as empresa','metodo_pago','clientes.razonSocial as cliente','sucursales.sucursal','forma_pago','credito_dias','referencia','etiquetas','descripcion','gastos.activo',
                     DB::raw('DATE_FORMAT(gastos.fecha_pago, "%d/%m/%Y") as fecha_pago'),
                     DB::raw('CONCAT(cuentas.numeroCu," - ",cuentas.nombreCuenta) AS nombreCuenta'),
                     DB::raw('IF(gastos.moneda IS NULL, CONCAT("MXN $",gastos.total_iva), CONCAT("MXN $",gastos.total_iva)) AS total_iva'),
                     DB::raw('IF(gastos.moneda IS NULL, CONCAT("MXN $",gastos.total_isr), CONCAT("MXN $",gastos.total_isr)) AS total_isr'),
                     DB::raw('(CASE WHEN gastos.moneda IS NULL OR gastos.moneda="" THEN CONCAT("SIN MONEDA $",gastos.total) WHEN gastos.moneda IS NOT NULL AND gastos.moneda!="USD" THEN CONCAT("USD $",TRUNCATE(gastos.total/cambioDolar,2)) WHEN gastos.moneda = "USD" THEN CONCAT("USD $",gastos.total) ELSE 1 END) AS total_USD'),
                     DB::raw('(CASE WHEN gastos.moneda IS NULL THEN CONCAT("MXN $",gastos.total) WHEN gastos.moneda IS NOT NULL AND gastos.moneda!="USD" THEN CONCAT(gastos.moneda," $",gastos.total) WHEN gastos.moneda = "USD" THEN CONCAT("MXN $",TRUNCATE(gastos.total*cambioDolar,2)) ELSE 1 END) AS total_MXN'))
                    ->leftJoin('cuentas', 'gastos.idCuenta', '=', 'cuentas.idCuenta')
                    ->leftJoin('facturas', 'gastos.id_factura', '=', 'facturas.idFactura')
                    ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
                    ->leftJoin('clientes', 'clientes.idc', '=', 'facturas.idc')
                    ->where('gastos.activo','=','Si')
                    ->where('nombreGasto', 'like', '%'.$nombreGasto.'%')
                    ->where('beneficiario', 'like', '%'.$beneficiario.'%')
                    ->where('cuentas.empresaCuenta', 'like', '%'.$empresa.'%')
                    ->where('cuentas.nombreCuenta', 'like', '%'.$nombreCuenta.'%')
                    ->where('clientes.razonSocial', 'like', '%'.$cliente.'%')
                    ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                    ->where('gastos.referencia', 'like', '%'.$referencia.'%')
                    ->where('gastos.etiquetas', 'like', '%'.$etiquetas.'%')
                    ->where('gastos.descripcion', 'like', '%'.$descripcion.'%')
                    ->orderBy($anexGrid->columna, $order)
                    ->skip($anexGrid->pagina)
                    ->take($anexGrid->limite)
                  ->get();

                  $total = DB::table('gastos')            
                    ->select('facturas.numeroFactura','gastos.id','nombreGasto','beneficiario','facturas.idServicios','cuentas.empresaCuenta as empresa','metodo_pago','clientes.razonSocial as cliente','sucursales.sucursal','forma_pago','credito_dias','referencia','etiquetas','descripcion','gastos.activo',
                     DB::raw('DATE_FORMAT(gastos.fecha_pago, "%d/%m/%Y") as fecha_pago'),
                     DB::raw('CONCAT(cuentas.numeroCu," - ",cuentas.nombreCuenta) AS nombreCuenta'),
                     DB::raw('IF(gastos.moneda IS NULL, CONCAT("MXN $",gastos.total_iva), CONCAT("MXN $",gastos.total_iva)) AS total_iva'),
                     DB::raw('IF(gastos.moneda IS NULL, CONCAT("MXN $",gastos.total_isr), CONCAT("MXN $",gastos.total_isr)) AS total_isr'),
                     DB::raw('(CASE WHEN gastos.moneda IS NULL THEN CONCAT("USD $",gastos.total) WHEN gastos.moneda IS NOT NULL AND gastos.moneda!="USD" THEN CONCAT("USD $",TRUNCATE(gastos.total/cambioDolar,2)) WHEN gastos.moneda = "USD" THEN CONCAT("USD $",gastos.total) ELSE 1 END) AS total_USD'),
                     DB::raw('(CASE WHEN gastos.moneda IS NULL THEN CONCAT("MXN $",gastos.total) WHEN gastos.moneda IS NOT NULL AND gastos.moneda!="USD" THEN CONCAT(gastos.moneda," $",gastos.total) WHEN gastos.moneda = "USD" THEN CONCAT("MXN $",TRUNCATE(gastos.total*cambioDolar,2)) ELSE 1 END) AS total_MXN'))
                    ->leftJoin('cuentas', 'gastos.idCuenta', '=', 'cuentas.idCuenta')
                    ->leftJoin('facturas', 'gastos.id_factura', '=', 'facturas.idFactura')
                    ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
                    ->leftJoin('clientes', 'clientes.idc', '=', 'facturas.idc')
                  ->where('gastos.activo','=','Si')
                  ->where('nombreGasto', 'like', '%'.$nombreGasto.'%')
                    ->where('beneficiario', 'like', '%'.$beneficiario.'%')
                    ->where('cuentas.empresaCuenta', 'like', '%'.$empresa.'%')
                    ->where('cuentas.nombreCuenta', 'like', '%'.$nombreCuenta.'%')
                    ->where('clientes.razonSocial', 'like', '%'.$cliente.'%')
                    ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                    ->where('gastos.referencia', 'like', '%'.$referencia.'%')
                    ->where('gastos.etiquetas', 'like', '%'.$etiquetas.'%')
                    ->where('gastos.descripcion', 'like', '%'.$descripcion.'%')
                  ->count();
                }
                
                $data = array(
                  'total' => $total,
                  'data' => $registros
                );

                return response()->json($data, 200);
            }
      }
      public function reporteGastosAbajo(Request $request){
              $sname = Session::get('sesionname');
              $sidu = Session::get('sesionidu');
              $spat = Session::get('sesionpaterno');
              $smat = Session::get('sesionmaterno');
              $stipo = Session::get('sesiontipo');
              
              $fechaInicio = $request->fechaInicio;
              $fechaFin = $request->fechaFin;
              $reset = $request->limpiar;
              
             return view ('reporteGastosAbajo')
                ->with('fechaInicio',$fechaInicio)
                ->with('fechaFin',$fechaFin)
                ->with('reset',$reset);

            }
              public function getDataGastosAbajo(Request $request) {

            $sname = Session::get('sesionname');
            $sidu = Session::get('sesionidu');
            $spat = Session::get('sesionpaterno');
            $smat = Session::get('sesionmaterno');
            $stipo = Session::get('sesiontipo');
           
            if($sname == '' or $sidu =='' or $stipo=='' or $spat=='' or $smat==''){
                Session::flash('error', 'Es necesario logearse antes de continuar');
                return redirect()->route('login');
            }
            else{

            $anexGrid = new anexGrid();
            $nombreGasto = '';
            $beneficiario = '';
            $empresa = '';
            $nombreCuenta = '';
            $cliente = '';
            $sucursal = '';
            $referencia = '';
            $etiquetas = '';
            $descripcion = '';

              foreach($anexGrid->filtros as $f)
              {
                if($f['columna'] == 'nombreGasto' && $f['valor'] != ''){
                  $nombreGasto = $f['valor'];
                }
                if($f['columna'] == 'nombreGasto' && $f['valor'] == ''){
                  $nombreGasto = '';
                }
                if($f['columna'] == 'beneficiario' && $f['valor'] != ''){
                  $beneficiario = $f['valor'];
                }
                if($f['columna'] == 'beneficiario' && $f['valor'] == ''){
                  $beneficiario = '';
                }
                if($f['columna'] == 'empresa' && $f['valor'] != ''){
                  $empresa = $f['valor'];
                }
                if($f['columna'] == 'empresa' && $f['valor'] == ''){
                  $empresa = '';
                }
                if($f['columna'] == 'nombreCuenta' && $f['valor'] != ''){
                  $nombreCuenta = $f['valor'];
                }
                if($f['columna'] == 'nombreCuenta' && $f['valor'] == ''){
                  $nombreCuenta = '';
                }             

                if($f['columna'] == 'cliente' && $f['valor'] != ''){
                  $cliente = $f['valor'];
                } 
                if($f['columna'] == 'cliente' && $f['valor'] == '') {
                  $cliente = '';
                }
                
                if($f['columna'] == 'sucursal' && $f['valor'] != ''){
                  $sucursal = $f['valor'];
                }
                if ($f['columna'] == 'sucursal' && $f['valor'] == '') {
                  $sucursal = '';
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
            $reset = $anexGrid->parametros['reset'];
            $fechaInicio = $anexGrid->parametros['fechaInicio'];
            $fechaFin = $anexGrid->parametros['fechaFin'];

              $order;
              $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';
                
                if ($stipo == 'Administrador') { 
                  if($reset==0){
                  $query = DB::table('gastos')            
                    ->select('facturas.numeroFactura','gastos.id','nombreGasto','beneficiario','facturas.idServicios','cuentas.empresaCuenta as empresa','metodo_pago','clientes.razonSocial as cliente','sucursales.sucursal','forma_pago','credito_dias','referencia','etiquetas','descripcion','gastos.activo',
                     DB::raw('DATE_FORMAT(gastos.fecha_pago, "%d/%m/%Y") as fecha_pago'),
                     DB::raw('CONCAT(cuentas.numeroCu," - ",cuentas.nombreCuenta) AS nombreCuenta'),
                     DB::raw('IF(gastos.moneda IS NULL, CONCAT("MXN $",gastos.total_iva), CONCAT("MXN $",gastos.total_iva)) AS total_iva'),
                     DB::raw('IF(gastos.moneda IS NULL, CONCAT("MXN $",gastos.total_isr), CONCAT("MXN $",gastos.total_isr)) AS total_isr'),
                     DB::raw('(CASE WHEN gastos.moneda IS NULL THEN CONCAT("USD $",gastos.total) WHEN gastos.moneda IS NOT NULL AND gastos.moneda!="USD" THEN CONCAT("USD $",TRUNCATE(gastos.total/cambioDolar,2)) WHEN gastos.moneda = "USD" THEN CONCAT("USD $",gastos.total) ELSE 1 END) AS total_USD'),
                     DB::raw('(CASE WHEN gastos.moneda IS NULL THEN CONCAT("MXN $",gastos.total) WHEN gastos.moneda IS NOT NULL AND gastos.moneda!="USD" THEN CONCAT(gastos.moneda," $",gastos.total) WHEN gastos.moneda = "USD" THEN CONCAT("MXN $",TRUNCATE(gastos.total*cambioDolar,2)) ELSE 1 END) AS total_MXN'))
                    ->leftJoin('cuentas', 'gastos.idCuenta', '=', 'cuentas.idCuenta')
                    ->leftJoin('facturas', 'gastos.id_factura', '=', 'facturas.idFactura')
                    ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
                    ->leftJoin('clientes', 'clientes.idc', '=', 'facturas.idc')
                    ->where('gastos.activo','=','Si')
                    ->where('nombreGasto', 'like', '%'.$nombreGasto.'%')
                    ->where('beneficiario', 'like', '%'.$beneficiario.'%')
                    ->where('cuentas.empresaCuenta', 'like', '%'.$empresa.'%')
                    ->where('cuentas.nombreCuenta', 'like', '%'.$nombreCuenta.'%')
                    ->where('clientes.razonSocial', 'like', '%'.$cliente.'%')
                    ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                    ->where('gastos.referencia', 'like', '%'.$referencia.'%')
                    ->where('gastos.etiquetas', 'like', '%'.$etiquetas.'%')
                    ->where('gastos.descripcion', 'like', '%'.$descripcion.'%');
                    if($fechaInicio!=''){
                     $query->where('fecha_pago','>=',$fechaInicio); 
                    }
                    if($fechaFin!=''){
                     $query->where('fecha_pago','<=',$fechaFin);
                    }
                    
                    $registros = $query->orderBy($anexGrid->columna, $order)
                    ->skip($anexGrid->pagina)
                    ->take($anexGrid->limite)
                  ->get();

                  $query2 = DB::table('gastos')            
                    ->select('facturas.numeroFactura','gastos.id','nombreGasto','beneficiario','facturas.idServicios','cuentas.empresaCuenta as empresa','metodo_pago','clientes.razonSocial as cliente','sucursales.sucursal','forma_pago','credito_dias','referencia','etiquetas','descripcion','gastos.activo',
                     DB::raw('DATE_FORMAT(gastos.fecha_pago, "%d/%m/%Y") as fecha_pago'),
                     DB::raw('CONCAT(cuentas.numeroCu," - ",cuentas.nombreCuenta) AS nombreCuenta'),
                     DB::raw('IF(gastos.moneda IS NULL, CONCAT("MXN $",gastos.total_iva), CONCAT("MXN $",gastos.total_iva)) AS total_iva'),
                     DB::raw('IF(gastos.moneda IS NULL, CONCAT("MXN $",gastos.total_isr), CONCAT("MXN $",gastos.total_isr)) AS total_isr'),
                     DB::raw('(CASE WHEN gastos.moneda IS NULL THEN CONCAT("USD $",gastos.total) WHEN gastos.moneda IS NOT NULL AND gastos.moneda!="USD" THEN CONCAT("USD $",TRUNCATE(gastos.total/cambioDolar,2)) WHEN gastos.moneda = "USD" THEN CONCAT("USD $",gastos.total) ELSE 1 END) AS total_USD'),
                     DB::raw('(CASE WHEN gastos.moneda IS NULL THEN CONCAT("MXN $",gastos.total) WHEN gastos.moneda IS NOT NULL AND gastos.moneda!="USD" THEN CONCAT(gastos.moneda," $",gastos.total) WHEN gastos.moneda = "USD" THEN CONCAT("MXN $",TRUNCATE(gastos.total*cambioDolar,2)) ELSE 1 END) AS total_MXN'))
                    ->leftJoin('cuentas', 'gastos.idCuenta', '=', 'cuentas.idCuenta')
                    ->leftJoin('facturas', 'gastos.id_factura', '=', 'facturas.idFactura')
                    ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
                    ->leftJoin('clientes', 'clientes.idc', '=', 'facturas.idc')
                    ->where('gastos.activo','=','Si')
                    ->where('nombreGasto', 'like', '%'.$nombreGasto.'%')
                    ->where('beneficiario', 'like', '%'.$beneficiario.'%')
                    ->where('cuentas.empresaCuenta', 'like', '%'.$empresa.'%')
                    ->where('cuentas.nombreCuenta', 'like', '%'.$nombreCuenta.'%')
                    ->where('clientes.razonSocial', 'like', '%'.$cliente.'%')
                    ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                    ->where('gastos.referencia', 'like', '%'.$referencia.'%')
                    ->where('gastos.etiquetas', 'like', '%'.$etiquetas.'%')
                    ->where('gastos.descripcion', 'like', '%'.$descripcion.'%');
                  if($fechaInicio!=''){
                     $query2->where('fecha_pago','>=',$fechaInicio); 
                    }
                    if($fechaFin!=''){
                     $query2->where('fecha_pago','<=',$fechaFin);
                    } 
                  $total = $query2->count();
                }else if($reset==1){
                  $registros = DB::table('gastos')            
                    ->select('facturas.numeroFactura','gastos.id','nombreGasto','beneficiario','facturas.idServicios','cuentas.empresaCuenta as empresa','metodo_pago','clientes.razonSocial as cliente','sucursales.sucursal','forma_pago','credito_dias','referencia','etiquetas','descripcion','gastos.activo',
                     DB::raw('DATE_FORMAT(gastos.fecha_pago, "%d/%m/%Y") as fecha_pago'),
                     DB::raw('CONCAT(cuentas.numeroCu," - ",cuentas.nombreCuenta) AS nombreCuenta'),
                     DB::raw('IF(gastos.moneda IS NULL, CONCAT("MXN $",gastos.total_iva), CONCAT("MXN $",gastos.total_iva)) AS total_iva'),
                     DB::raw('IF(gastos.moneda IS NULL, CONCAT("MXN $",gastos.total_isr), CONCAT("MXN $",gastos.total_isr)) AS total_isr'),
                     DB::raw('(CASE WHEN gastos.moneda IS NULL THEN CONCAT("USD $",gastos.total) WHEN gastos.moneda IS NOT NULL AND gastos.moneda!="USD" THEN CONCAT("USD $",TRUNCATE(gastos.total/cambioDolar,2)) WHEN gastos.moneda = "USD" THEN CONCAT("USD $",gastos.total) ELSE 1 END) AS total_USD'),
                     DB::raw('(CASE WHEN gastos.moneda IS NULL THEN CONCAT("MXN $",gastos.total) WHEN gastos.moneda IS NOT NULL AND gastos.moneda!="USD" THEN CONCAT(gastos.moneda," $",gastos.total) WHEN gastos.moneda = "USD" THEN CONCAT("MXN $",TRUNCATE(gastos.total*cambioDolar,2)) ELSE 1 END) AS total_MXN'))
                    ->leftJoin('cuentas', 'gastos.idCuenta', '=', 'cuentas.idCuenta')
                    ->leftJoin('facturas', 'gastos.id_factura', '=', 'facturas.idFactura')
                    ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
                    ->leftJoin('clientes', 'clientes.idc', '=', 'facturas.idc')
                    ->where('gastos.activo','=','Si')
                    ->where('nombreGasto', 'like', '%'.$nombreGasto.'%')
                    ->where('beneficiario', 'like', '%'.$beneficiario.'%')
                    ->where('cuentas.empresaCuenta', 'like', '%'.$empresa.'%')
                    ->where('cuentas.nombreCuenta', 'like', '%'.$nombreCuenta.'%')
                    ->where('clientes.razonSocial', 'like', '%'.$cliente.'%')
                    ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                    ->where('gastos.referencia', 'like', '%'.$referencia.'%')
                    ->where('gastos.etiquetas', 'like', '%'.$etiquetas.'%')
                    ->where('gastos.descripcion', 'like', '%'.$descripcion.'%')
                    ->orderBy($anexGrid->columna, $order)
                    ->skip($anexGrid->pagina)
                    ->take($anexGrid->limite)
                  ->get();

                  $total = DB::table('gastos')            
                    ->select('facturas.numeroFactura','gastos.id','nombreGasto','beneficiario','facturas.idServicios','cuentas.empresaCuenta as empresa','metodo_pago','clientes.razonSocial as cliente','sucursales.sucursal','forma_pago','credito_dias','referencia','etiquetas','descripcion','gastos.activo',
                     DB::raw('DATE_FORMAT(gastos.fecha_pago, "%d/%m/%Y") as fecha_pago'),
                     DB::raw('CONCAT(cuentas.numeroCu," - ",cuentas.nombreCuenta) AS nombreCuenta'),
                     DB::raw('IF(gastos.moneda IS NULL, CONCAT("MXN $",gastos.total_iva), CONCAT("MXN $",gastos.total_iva)) AS total_iva'),
                     DB::raw('IF(gastos.moneda IS NULL, CONCAT("MXN $",gastos.total_isr), CONCAT("MXN $",gastos.total_isr)) AS total_isr'),
                     DB::raw('(CASE WHEN gastos.moneda IS NULL THEN CONCAT("USD $",gastos.total) WHEN gastos.moneda IS NOT NULL AND gastos.moneda!="USD" THEN CONCAT("USD $",TRUNCATE(gastos.total/cambioDolar,2)) WHEN gastos.moneda = "USD" THEN CONCAT("USD $",gastos.total) ELSE 1 END) AS total_USD'),
                     DB::raw('(CASE WHEN gastos.moneda IS NULL THEN CONCAT("MXN $",gastos.total) WHEN gastos.moneda IS NOT NULL AND gastos.moneda!="USD" THEN CONCAT(gastos.moneda," $",gastos.total) WHEN gastos.moneda = "USD" THEN CONCAT("MXN $",TRUNCATE(gastos.total*cambioDolar,2)) ELSE 1 END) AS total_MXN'))
                    ->leftJoin('cuentas', 'gastos.idCuenta', '=', 'cuentas.idCuenta')
                    ->leftJoin('facturas', 'gastos.id_factura', '=', 'facturas.idFactura')
                    ->leftJoin('sucursales', 'facturas.idSucursal', '=', 'sucursales.idSucursal')
                    ->leftJoin('clientes', 'clientes.idc', '=', 'facturas.idc') 
                    ->where('nombreGasto', 'like', '%'.$nombreGasto.'%')
                    ->where('gastos.activo','=','Si')
                    ->where('beneficiario', 'like', '%'.$beneficiario.'%')
                    ->where('cuentas.empresaCuenta', 'like', '%'.$empresa.'%')
                    ->where('cuentas.nombreCuenta', 'like', '%'.$nombreCuenta.'%')
                    ->where('clientes.razonSocial', 'like', '%'.$cliente.'%')
                    ->where('sucursales.sucursal', 'like', '%'.$sucursal.'%')
                    ->where('gastos.referencia', 'like', '%'.$referencia.'%')
                    ->where('gastos.etiquetas', 'like', '%'.$etiquetas.'%')
                    ->where('gastos.descripcion', 'like', '%'.$descripcion.'%')  
                  ->count();
                }
                }
                
                $data = array(
                  'total' => $total,
                  'data' => $registros,
                );

                return response()->json($data, 200);
            }
              }
              public function excel_gastos($fecha_inicio,$fecha_fin,$nombreGasto,$beneficiario,$empresa,$cuenta,$cliente,$sucursal,$referencia)
                 {
      //return $empresa;
      $fi1 = date('d-m-Y', strtotime($fecha_inicio));
      $ff1 = date('d-m-Y', strtotime($fecha_fin));
      $fi = $fecha_inicio!='null' ? '_desde_'.$fi1 : '' ;   
      $ff = $fecha_fin!='null' ? '_hasta_'.$ff1 : '' ;    
      $nombreGasto = $nombreGasto!='null' ? $nombreGasto : '' ;   
      $beneficiario = $beneficiario!='null' ? $beneficiario : '' ; 
      $empresa = $empresa!='null' ? $empresa : '' ;
      $cuenta = $cuenta!='null' ? $cuenta : '' ;
      $cliente = $cliente!='null' ? $cliente : '' ; 
      $sucursal = $sucursal!='null' ? $sucursal : '' ;
      $referencia = $referencia!='null' ? $referencia : '' ;
      $nombre_file = 'gastos'.$fi.''.$ff;  
      return Excel::download(new GastosExport($fecha_inicio,$fecha_fin,$nombreGasto,$beneficiario,$empresa,$cuenta,$cliente,$sucursal,$referencia), $nombre_file.'.xlsx');   
    }
public function gastoServExcel(){
      return Excel::download(new GastoServExport, 'gastos.xlsx');
    }
}
