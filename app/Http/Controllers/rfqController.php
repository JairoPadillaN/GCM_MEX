<?php

namespace App\Http\Controllers;

use App\clientes;
use App\contactosucursales;
use App\marcaRefaccion;
use App\partesVenta;
use App\tiporefacciones;
use App\rfqs;
use App\rfqsDetalle;
use App\sucursales;
use App\Cardex;
use App\Notificaciones;
use App\usuarios;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class rfqController extends Controller
{
    //
    public function reporteRfqs()
    {
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {
            if ($stipo == 'Compras' || $sidu == 18 || $stipo == 'Administrador') {
                $consulta = rfqs::withTrashed()->join("usuarios", "rfqs.idu", "=", "usuarios.idu")->join("clientes", "rfqs.idclie", "=", "clientes.idc")
                    ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
                    ->select(
                        "rfqs.idrfq",
                        "rfqs.idu",
                        "rfqs.idclie",
                        "rfqs.idsuc",
                        "rfqs.ciudad",
                        "rfqs.estatus",
                        "rfqs.cmgrfq",
                        "rfqs.idcont",
                        "rfqs.nivelsolicitud",
                        "rfqs.fechasolicitud",
                        "rfqs.fechapropuesta",
                        "rfqs.fechaentrega",
                        "rfqs.fechaestimadaentrega",
                        "rfqs.observaciones",
                        "rfqs.deleted_at",
                        "clientes.razonSocial as razonSocial",
                        "sucursales.sucursal as sucursal",
                    )
                    ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")
                    ->orderBy('fechasolicitud', 'DESC')
                    ->get();

                $cuantosrfq = count($consulta);
            } else if ($stipo == 'Vendedor') {
                $consulta = rfqs::withTrashed()
                    ->join("usuarios", "rfqs.idu", "=", "usuarios.idu")
                    ->join("clientes", "rfqs.idclie", "=", "clientes.idc")
                    ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
                    ->select(
                        "rfqs.idrfq",
                        "rfqs.idu",
                        "rfqs.idclie",
                        "rfqs.idsuc",
                        "rfqs.ciudad",
                        "rfqs.estatus",
                        "rfqs.cmgrfq",
                        "rfqs.idcont",
                        "rfqs.nivelsolicitud",
                        "rfqs.fechasolicitud",
                        "rfqs.fechaentrega",
                        "rfqs.fechapropuesta",
                        "rfqs.fechaestimadaentrega",
                        "rfqs.observaciones",
                        "rfqs.deleted_at",
                        "clientes.razonSocial as razonSocial",
                        "sucursales.sucursal as sucursal",
                    )
                    ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")
                    ->where('rfqs.idu', $sidu)
                    ->orderBy('fechasolicitud', 'DESC')
                    ->get();


                $cuantosrfq = count($consulta);
            }
        }
        IF ($stipo==='Compras' or $sidu ===10 or $sidu ===1)
        {
            $consultasol = \DB::SELECT ("SELECT COUNT(*) AS cu
            FROM Notificaciones
            WHERE created_at >= DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -7 DAY), '%Y/%m/%d')
            AND (visto IS NULL OR visto = 0) and idurecibe = 1");
            $cuantoss = $consultasol[0]->cu;

        }
        if ($stipo ==='Vendedor' and  $sidu<> 18)
        {
            $consultasol = \DB::SELECT ("SELECT COUNT(*) AS cu
            FROM Notificaciones
            WHERE created_at >= DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -7 DAY), '%Y/%m/%d')
            AND (visto IS NULL OR visto = 0) and idurecibe = $sidu");
            $cuantoss = $consultasol[0]->cu;


        }
        if ($stipo ==='Vendedor' and $sidu===18)
        {
            $consultasol = \DB::SELECT ("SELECT COUNT(*) AS cu
            FROM Notificaciones
            WHERE created_at >= DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -7 DAY), '%Y/%m/%d')
            AND (visto IS NULL OR visto = 0) and idurecibe <>1");
            $cuantoss = $consultasol[0]->cu;


        }



        return view('reporteRfq',compact('stipo'))->with("cuantosrfq", $cuantosrfq)->with("consulta", $consulta)->with("cuantoss",$cuantoss);
    }

    public function altaRfq(Request $request)
    {
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {

            $marcas = marcaRefaccion::where('activo', 'Si')
                ->orderBy('marcaRefaccion', 'ASC')
                ->get(['idMarcaRefa', 'marcaRefaccion']);
            $selectedMarca = $request->old('idmarca'); // obtener marca seleccionada en el formulario anterior


            $clientes = clientes::where('activo', 'si')
                ->where('tipocliente', 'activo')
                ->orderBy('razonSocial', 'ASC')
                ->get(['idc', 'razonSocial']);
            $selectedCliente = $request->old('idc'); // obtener cliente seleccionado en el formulario anterior
            $sucursales = []; // iniciar arreglo de sucursales vacio


            if ($selectedCliente) { // si hay un cliente seleccionado, carga las sucursales correspondientes y guardarlas en el arreglo
                $sucursales = sucursales::where('idc', $selectedCliente)
                    ->where('activo', 'Si')
                    ->where('tipoSucursal', 'activo')
                    ->orderBy('sucursal')
                    ->pluck('sucursal', 'idsucursal'); // Obtener arreglo de estos atributos
            }

            $selectedSucursal = $request->old('ids'); // obtener la sucursal seleccionada en el formulario anterior

            $contactos = []; // iniciar arreglo de contactos vacio


            if ($selectedSucursal) { // si hay una sucursal seleccionada, cargar los contactos correspondientes y guardarlas en el arreglo
                $contactos = contactosucursales::selectRaw("CONCAT(puesto,' - ',nombreCont) AS contacto, idContactoSuc")
                    ->where('idSucursal', $selectedSucursal)
                    ->orderBy('puesto')
                    ->pluck('contacto', 'idContactoSuc'); // Obtener arreglo de estos atributos
            }

            $selectedContacto = $request->old('contacto'); // obtener el contacto seleccionado en el formulario anterior

            // idrfq incremental y poner valor 1 si no hay registros


            // $consulta = rfqs::orderBy("idrfq", "desc")->take(1)->get();
            // $cuantos = count($consulta);
            // if ($cuantos == 0) {
            //     $idsiguiente = 1;
            // } else {
            //     $idsiguiente = $consulta[0]->idrfq + 1;
            // }

            $fecha = Carbon::now()->setTimezone("America/Mexico_City");
            $sname = Session::get('sesionname'); // Obtener session name del usuario
            $date = Carbon::now(); // variable para guardar date/fecha
        }
        return view('rfqalta') // enviar data a la vista
            ->with('marcas', $marcas)
            ->with('clientes', $clientes)
            ->with('sucursales', $sucursales)
            ->with('contactos', $contactos)
            ->with('selectedMarca', $selectedMarca)
            ->with('selectedCliente', $selectedCliente)
            ->with('selectedSucursal', $selectedSucursal)
            ->with('selectedContacto', $selectedContacto)
            ->with('inicial', $sname[0])
            // ->with('idsiguiente', $idsiguiente)
            ->with('date', $date)
            ->with('fecha', $fecha);
    }

    public function guardarRfq(Request $request)
    {
        // Validacion de campos
        $this->validate($request, [
            "solicitud" => "required",
            "idc" => "required",
            "ids" => "required",
            "idmarca" => "required",
            "contacto" => "required",
            "ciudad" => "required",
            "cmgrfq" => "required",
        ], [
            "solicitud.required" => "El campo de nivel de solicitud es requerido",
            "idc.required" => "El campo cliente es requerido",
            "ids.required" => "El campo sucursal es requerido",
            "idmarca.required" => "El campo marca es requerido",
            "contacto.required" => "El campo contacto es requerido",
            "ciudad.required" => "El campo ciudad es requerido",
            "cmgrfq.required" => "El campo cmgrfq es requerido",
        ]);
        $existente = rfqs::where('cmgrfq', $request->cmgrfq)->first();
        if ($existente) {
            return redirect()->back()->with('error', 'Ya se generó un RFQ para esa marca y sucursal este día.');
        } else {
            // Nuevo rfq
            $rfq = new rfqs();
            //$rfq->idrfq = $request->idrfq;
            $rfq->idu = $request->idu;
            $rfq->idclie = $request->idc;
            $rfq->idsuc = $request->ids;
            $rfq->idmarca = $request->idmarca;
            $rfq->ciudad = $request->ciudad;
            $rfq->cmgrfq = $request->cmgrfq;
            //$rfq->consecutivo = $request->consecutivo; // Asignar el valor del input hidden
            $rfq->idcont = $request->contacto;
            $rfq->nivelsolicitud = $request->solicitud;
            $rfq->estatus = "Solicitado";
            $rfq->fechasolicitud = $request->fechaSolicitud;
            $rfq->fechapropuesta = $request->fechaPropuesta;
            $rfq->fechaentrega = "1900-01-01"; /*$request->fechaEntrega;*/
            $rfq->notasrfq = $request->notasrfq;
            $rfq->created_at = Carbon::now()->setTimezone("America/Mexico_City");
            $rfq->save();

            $notificacion = new Notificaciones();
            $notificacion->accion = "Solicitado";
            $notificacion->mensaje = "Nuevo Kardex - RFQ: " . $request->cmgrfq;
            $notificacion->idumanda = $rfq->idu;
            $notificacion->idurecibe = 1;
            $notificacion->visto = 0;
            $notificacion->fecha = Carbon::now()->setTimezone("America/Mexico_City"); // Fecha actual usando la clase Carbon
            $notificacion->created_at = Carbon::now()->setTimezone("America/Mexico_City");
			$notificacion->save();

            // Session::flash('mensaje', "El RFQ $request->idrfq ha sido creado correctamente");
            return redirect()->route("reporteRfqs");
        }
    }

    public function modificarRfq(Request $request, $idrfq)
    {
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {

            $consulta = rfqs::withoutTrashed()
                ->join("usuarios", "rfqs.idu", "=", "usuarios.idu")
                ->join("clientes", "rfqs.idclie", "=", "clientes.idc")
                ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
                ->join("marcasRefaccion", "rfqs.idmarca", "=", "marcasRefaccion.idMarcaRefa")
                ->select(
                    "rfqs.idrfq",
                    "rfqs.idu",
                    "rfqs.idclie",
                    "rfqs.idsuc",
                    "rfqs.idmarca",
                    "rfqs.ciudad",
                    "rfqs.cmgrfq",
                    "rfqs.estatus",
                    //"rfqs.consecutivo",
                    "rfqs.idcont",
                    "rfqs.nivelsolicitud",
                    "rfqs.fechasolicitud",
                    "rfqs.fechapropuesta",
                    "rfqs.fechaentrega",
                    "rfqs.notasrfq",
                    "clientes.razonSocial as razonSocial",
                    "marcasRefaccion.marcaRefaccion as marcaRefaccion",
                    "sucursales.sucursal as sucursal"
                )
                // seleccionar linea que contiene el registro y de ese registro concatenar nombre aPaterno y aMaterno
                ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")
                ->where("idrfq", $idrfq)
                ->get();

            // $idRfqDetalleSig = rfqsDetalle::orderBy("idRfqDetalle", "desc")->take(1)->get();
            // $cuantos = count($idRfqDetalleSig);
            // if ($cuantos == 0) {
            //     $idsiguiente = 1;
            // } else {
            //     $idsiguiente = $idRfqDetalleSig[0]->idRfqDetalle + 1;
            // }

            $marcas = marcaRefaccion::where('activo', 'si')
                ->orderBy('marcaRefaccion', 'ASC')
                ->get(['idMarcaRefa', 'marcaRefaccion']);

            $selectedMarca = $consulta[0]->idmarca;
            $selectedMarca = $request->old('idmarca', $selectedMarca);

            $clientes = clientes::where('activo', 'si')
                ->where('tipocliente', 'activo')
                ->orderBy('razonSocial', 'ASC')
                ->get(['idc', 'razonSocial']);

            $selectedCliente = $consulta[0]->idclie; // asignar de nuestra consulta el registro a selectedCliente
            $selectedCliente = $request->old('idc', $selectedCliente); // si hay un error obtener cliente seleccionado en el formulario anterior y asignarlo como selectedCliente   

            // $sucursal = sucursales::where('idc', $selectedCliente)
            //     ->where('activo', 'Si')
            //     ->where('tipoSucursal', 'activo')
            //     ->orderBy('sucursal')
            //     ->pluck('sucursal', 'idsucursal');

            $selectedSucursal = $consulta[0]->idsuc; // asignar de nuestra consulta el registro a selectedSucursal
            $selectedSucursal = $request->old('ids', $selectedSucursal); // si hay un error obtener sucursal seleccionada en el formulario anterior y asignarlo como selectedSucursal 

            $selectedContacto = $consulta[0]->idcont; // asignar de nuestra consulta el registro a selectedSucursal
            $selectedContacto = $request->old('contacto', $selectedContacto); // si hay un error obtener sucursal seleccionada en el formulario anterior y asignarlo como selectedSucursal 

            // Segun el selectedCliente asignado de la consulta o del formulario anterior obtenemos sus sucursales
            $sucursales = sucursales::where('idc', $selectedCliente)
                ->where('activo', 'Si')
                ->where('tipoSucursal', 'activo')
                ->orderBy('sucursal')
                ->get();

            // Segun el selectedCliente asignado de la consulta o del formulario anterior obtenemos sus sucursales
            $contactos = contactosucursales::selectRaw("CONCAT(puesto,' - ',nombreCont) AS contacto, idContactoSuc")
                ->where('idSucursal', $selectedSucursal)
                ->orderBy('puesto')
                ->get();

            $tipos = tiporefacciones::where('idMarcaRefa', $selectedMarca)
                ->where('activo', 'Si')
                ->orderBy('nombreTipoRefaccion')
                ->get();

            $consultaProductosRfq = rfqsDetalle::where('idRfq', '=', $idrfq)
                ->join("tiporefacciones", "rfqsDetalle.idTipoRefa", "=", "tiporefacciones.idTipoRefacciones")
                ->join("partesVenta", "rfqsDetalle.idPartesVenta", "=", "partesVenta.idPartesVenta")
                ->select(
                    "rfqsDetalle.idRfqDetalle",
                    "rfqsDetalle.idRfq",
                    "rfqsDetalle.cantidad",
                    "rfqsDetalle.modelo",
                    "rfqsDetalle.notas",
                    "rfqsDetalle.tipo",
                    "rfqsDetalle.tip",
                    "rfqsDetalle.sku_generico",
                    "rfqsDetalle.foto1",
                    "rfqsDetalle.foto2",
                    "rfqsDetalle.foto3",
                    "rfqsDetalle.foto4",
                    "rfqsDetalle.foto5",
                    "tiporefacciones.nombreTipoRefaccion as tipoRefa",
                )
                ->selectRaw("concat(partesVenta.codigo, ' ', partesVenta.nombreRefaccion) as sku")
                ->selectRaw("concat(rfqsDetalle.sku_generico) as skugenerico")
                ->get();

            $cuantosProductosRfq = count($consultaProductosRfq);
        }
        return view("modificarRfq") // enviar data a la vista
            ->with('inicial', $sname[0])
            ->with('consulta', $consulta[0])
            // ->with('idsiguiente', $idsiguiente)
            ->with('clientes', $clientes)
            ->with('sucursales', $sucursales)
            ->with('contactos', $contactos)
            ->with('marcas', $marcas)
            ->with('tipos', $tipos)
            ->with('selectedMarca', $selectedMarca)
            ->with('selectedCliente', $selectedCliente)
            ->with('selectedSucursal', $selectedSucursal)
            ->with('selectedContacto', $selectedContacto)
            ->with('consultaProductosRfq', $consultaProductosRfq)
            ->with('cuantosProductosRfq', $cuantosProductosRfq);
    }


    public function guardarModificacionRfq(Request $request)
    {
        // Validacion de campos
        $this->validate($request, [
            "solicitud" => "required",
            "idc" => "required",
            "ids" => "required",
            "idmarca" => "required",
            "contacto" => "required",
            "ciudad" => "required",
            "cmgrfq" => "required",
        ], [
            "solicitud.required" => "El campo de nivel de solicitud es requerido",
            "idc.required" => "El campo cliente es requerido",
            "idmarca.required" => "El campo marca es requerido",
            "ids.required" => "El campo sucursal es requerido",
            "contacto.required" => "El campo contacto es requerido",
            "ciudad.required" => "El campo ciudad es requerido",
            "cmgrfq.required" => "El campo cmgrfq es requerido",
        ]);

        $existente = rfqs::where('cmgrfq', $request->cmgrfq)
            ->where('idrfq', '!=', $request->idrfq)
            ->first();
        if ($existente) {
            return redirect()->back()->with('error', 'Ya se generó un RFQ para esa marca y sucursal este día.');
        } else {
            // Modificacion del rfq
            $rfq = rfqs::withTrashed()->find($request->idrfq);
            //$rfq->idrfq = $request->idrfq;
            $rfq->idu = $request->idu;
            $rfq->idmarca = $request->idmarca;
            $rfq->idclie = $request->idc;
            $rfq->idsuc = $request->ids;
            $rfq->ciudad = $request->ciudad;
            $rfq->cmgrfq = $request->cmgrfq;
            $rfq->idcont = $request->contacto;
            $rfq->nivelsolicitud = $request->solicitud;
            $rfq->fechasolicitud = $request->fechaSolicitud;
            $rfq->fechapropuesta = $request->fechaPropuesta;
            if ($request->fechaentrega == "1900-01-01") {
                $rfq->fechaentrega = "1900-01-01";
            } else {
                $rfq->fechaentrega = $request->fechaEntrega;
            }
            $rfq->notasrfq = $request->notasrfq;
            $rfq->save();

            // Session::flash('mensaje', "El RFQ $request->idrfq ha sido modificado correctamente");
            return redirect()->route("reporteRfqs");
        }
    }

    public function desactivarRfq($idrfq)
    {
        $rfqs = rfqs::find($idrfq);
        $rfqs->delete();

        //Session::flash('mensaje', "rfq desactivado correctamente");
        return redirect()->route("reporteRfqs");
    }

    public function activarRfq($idrfq)
    {
        $rfq = rfqs::withTrashed()->where('idrfq', $idrfq)->restore();
        return redirect()->route("reporteRfqs");
    }

    public function agregarProductosRfq(Request $request)
    {
        $idRfq = $request->idrfq;

        $file = $request->file('foto1');
        // dd($file);
        //obtenemos el nombre del archivo
        if ($file != "") {
            $foto1 =  time() . "_" . $file->getClientOriginalName();
            //indicamos que queremos guardar un nuevo archivo
            Storage::disk('local')->put($foto1,  File::get($file));
        } else {
            $foto1 = "Sin foto";
        }

        $file2 = $request->file('foto2');
        if ($file2 != "") {
            $foto2 =  time() . "_" . $file2->getClientOriginalName();
            Storage::disk('local')->put($foto2,  File::get($file2));
        } else {
            $foto2 = "Sin foto";
        }

        $file3 = $request->file('foto3');
        if ($file3 != "") {
            $foto3 =  time() . "_" . $file3->getClientOriginalName();
            Storage::disk('local')->put($foto3,  File::get($file3));
        } else {
            $foto3 = "Sin foto";
        }

        $file4 = $request->file('foto4');
        if ($file4 != "") {
            $foto4 =  time() . "_" . $file4->getClientOriginalName();
            Storage::disk('local')->put($foto4,  File::get($file4));
        } else {
            $foto4 = "Sin foto";
        }

        $file5 = $request->file('foto5');
        if ($file5 != "") {
            $foto5 =  time() . "_" . $file5->getClientOriginalName();
            Storage::disk('local')->put($foto5,  File::get($file5));
        } else {
            $foto5 = "Sin foto";
        }

        $rfqDetalle = new rfqsDetalle();
        //$rfqDetalle->idRfqDetalle = $request->idrfqdetalle;
        $rfqDetalle->idRfq = $request->idrfq;
        $rfqDetalle->idMarca = $request->marcaskudetalle;
        $rfqDetalle->tipo = $request->tiposku;
        $rfqDetalle->idTipoRefa = $request->tipo;
        $rfqDetalle->idPartesVenta = $request->sku;
        $rfqDetalle->modelo = $request->modelo;
        $rfqDetalle->tip = $request->tip;
        $rfqDetalle->sku_generico = $request->sinsku;
        $rfqDetalle->cantidad = $request->cantidad;
        $rfqDetalle->notas = $request->notas;
        $rfqDetalle->foto1 = $foto1;
        $rfqDetalle->foto2 = $foto2;
        $rfqDetalle->foto3 = $foto3;
        $rfqDetalle->foto4 = $foto4;
        $rfqDetalle->foto5 = $foto5;


        $rfqDetalle->save();


        $consulta = rfqsDetalle::where('idRfq', '=', $idRfq)
            ->join("tiporefacciones", "rfqsDetalle.idTipoRefa", "=", "tiporefacciones.idTipoRefacciones")
            ->join("partesVenta", "rfqsDetalle.idPartesVenta", "=", "partesVenta.idPartesVenta")
            ->select(
                "rfqsDetalle.idRfqDetalle",
                "rfqsDetalle.idRfq",
                "rfqsDetalle.cantidad",
                "rfqsDetalle.modelo",
                "rfqsDetalle.notas",
                "rfqsDetalle.tipo",
                "rfqsDetalle.tip",
                "rfqsDetalle.sku_generico",
                "rfqsDetalle.foto1",
                "rfqsDetalle.foto2",
                "rfqsDetalle.foto3",
                "rfqsDetalle.foto4",
                "rfqsDetalle.foto5",
                "tiporefacciones.nombreTipoRefaccion as tipoRefa",
            )
            ->selectRaw("concat(partesVenta.codigo, ' ', partesVenta.nombreRefaccion) as sku")
            ->selectRaw("concat(rfqsDetalle.sku_generico) as skugenerico")

            ->get();

        // return $consulta;
        return view("reporteproductosrfq")
            ->with('consulta', $consulta);
    }

    public function borrarProductosRfq(Request $request)
    {
        $idRfqDetalle = $request->idRfqDetalle;
        $idRfq = $request->idRfq;

        $rfqDetalle = rfqsDetalle::find($idRfqDetalle)->delete();

        $consulta = rfqsDetalle::where('idRfq', '=', $idRfq)
            ->join("tiporefacciones", "rfqsDetalle.idTipoRefa", "=", "tiporefacciones.idTipoRefacciones")
            ->join("partesVenta", "rfqsDetalle.idPartesVenta", "=", "partesVenta.idPartesVenta")
            ->select(
                "rfqsDetalle.idRfqDetalle",
                "rfqsDetalle.idRfq",
                "rfqsDetalle.cantidad",
                "rfqsDetalle.modelo",
                "rfqsDetalle.notas",
                "rfqsDetalle.tipo",
                "rfqsDetalle.tip",
                "rfqsDetalle.sku_generico",
                "rfqsDetalle.foto1",
                "rfqsDetalle.foto2",
                "rfqsDetalle.foto3",
                "rfqsDetalle.foto4",
                "rfqsDetalle.foto5",
                "tiporefacciones.nombreTipoRefaccion as tipoRefa",
            )
            ->selectRaw("concat(partesVenta.codigo, ' ', partesVenta.nombreRefaccion) as sku")
            ->selectRaw("concat(partesVenta.nombreRefaccion, ' - ', rfqsDetalle.modelo) as skugenerico")
            ->get();

        // return $consulta;
        return view("reporteproductosrfq")
            ->with('consulta', $consulta);
    }

    // public function altaRfqDetalle(Request $request)
    // {
    //     // Validacion de campos
    //     $this->validate($request, [
    //         "marca" => "required",
    //         "tipo" => "required",
    //         "sku" => "required",
    //         "modelo" => "required",
    //         "cantidad" => "required",
    //         "notas" => "required",
    //         "tip" => "required",
    //         "foto1" => "image|mimes:gif,jpg,jpeg,png"
    //     ], [
    //         "marca.required" => "El campo marca es requerido",
    //         "tipo.required" => "El campo tipo es requerido",
    //         "sku.required" => "El campo sku es requerido",
    //         "modelo.required" => "El campo modelo es requerido",
    //         "cantidad.required" => "El campo cantidad es requerido",
    //         "notas.required" => "El campo notas es requerido",
    //         "tip.required" => "El campo tip es requerido",
    //     ]);

    //     $file = $request->file("foto1");
    //     if ($file) {
    //         $img = $file->getClientOriginalName();
    //         $img2 = $request->idrfqdetalle . $img;
    //         Storage::disk("local")->put($img2, File::get($file));
    //     } else {
    //         $img2 = "sin-foto.jpg";
    //     }

    //     // Modificacion del rfq
    //     $rfqDetalle = new rfqsDetalle();
    //     $rfqDetalle->idRfqDetalle = $request->idrfqdetalle;
    //     $rfqDetalle->idRfq = $request->idrfq;
    //     $rfqDetalle->idMarcaR = $request->marca;
    //     $rfqDetalle->idTipoRefa = $request->tipo;
    //     $rfqDetalle->idPartesVenta = $request->sku;
    //     $rfqDetalle->cantidad = $request->cantidad;
    //     $rfqDetalle->notas = $request->notas;
    //     $rfqDetalle->foto1 = $request->foto1;
    //     $rfqDetalle->save();

    //     // Session::flash('mensaje', "El RFQ $request->idrfq ha sido modificado correctamente");
    //     return redirect()->route("reporteRfqs");
    // }


    public function verFotosRfq(Request $request)
    {
        $idRfqDetalle = $request->idRfqDetalle;

        $consulta = rfqsDetalle::where('idRfqDetalle', '=', $idRfqDetalle)
            ->select(
                "foto1",
                "foto2",
                "foto3",
                "foto4",
                "foto5",
            )
            ->get();

        return view("verfotosrfq")
            ->with('consulta', $consulta);
    }


    // public function guardarEstatusRfq(Request $request, $idrfq)
    // {
    //     $rfq = rfqs::withTrashed()->find($idrfq);
    //     $rfq->estatus = $request->estatus;
    //     $rfq->save();

    //     $sidu = Session::get('sesionidu');
    //     $tipo = Session::get('sesiontipo');


    //     if ($tipo == 'Vendedor') {
    //         $consulta = rfqs::withTrashed()
    //             ->join("usuarios", "rfqs.idu", "=", "usuarios.idu")
    //             ->join("clientes", "rfqs.idclie", "=", "clientes.idc")
    //             ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
    //             ->select(
    //                 "rfqs.idrfq",
    //                 "rfqs.idu",
    //                 "rfqs.idclie",
    //                 "rfqs.idsuc",
    //                 "rfqs.ciudad",
    //                 "rfqs.estatus",
    //                 "rfqs.cmgrfq",
    //                 "rfqs.idcont",
    //                 "rfqs.nivelsolicitud",
    //                 "rfqs.fechasolicitud",
    //                 "rfqs.fechaentrega",
    //                 "rfqs.deleted_at",
    //                 "clientes.razonSocial as razonSocial",
    //                 "sucursales.sucursal as sucursal",
    //             )
    //             ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")
    //             ->where('rfqs.idu', $sidu)
    //             ->orderBy('cmgrfq')
    //             ->get();
    //     } else if ($tipo == 'Jefe de ventas' || 'Compras') {
    //         $consulta = rfqs::withTrashed()->join("usuarios", "rfqs.idu", "=", "usuarios.idu")->join("clientes", "rfqs.idclie", "=", "clientes.idc")
    //             ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
    //             ->select(
    //                 "rfqs.idrfq",
    //                 "rfqs.idu",
    //                 "rfqs.idclie",
    //                 "rfqs.idsuc",
    //                 "rfqs.ciudad",
    //                 "rfqs.estatus",
    //                 "rfqs.cmgrfq",
    //                 "rfqs.idcont",
    //                 "rfqs.nivelsolicitud",
    //                 "rfqs.fechasolicitud",
    //                 "rfqs.fechaentrega",
    //                 "rfqs.deleted_at",
    //                 "clientes.razonSocial as razonSocial",
    //                 "sucursales.sucursal as sucursal",
    //             )
    //             ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")
    //             ->orderBy('cmgrfq')
    //             ->get();
    //     }
    //     return view('reporteRfq')
    //         ->with('consulta', $consulta);
    // }

    public function editarInfoEstatus(Request $request)
    {
        $idrfq = $request->idrfq;
        $nombreTab = $request->nombreTab;

        $selectedEstatus = rfqs::where('idrfq', $idrfq)->value('estatus'); // ← Esta consulta trae el estatus de la tabla que se selecione para mostrarlo posteriormente en la vista
        $estatus = rfqs::where('idrfq', $idrfq)->value('estatus');
        $fechas = rfqs::where('idrfq', $idrfq)->value('fechaentrega');
        return view('editarInfoEstatus', compact('selectedEstatus'))
            ->with('estatus', $estatus)
            ->with('fechas', $fechas)
            ->with('nombreTab', $nombreTab)
            ->with('idrfq', $idrfq);
    }
    public function editarInfoFecha(Request $request)
    {
        $idrfq = $request->idrfq;
        $nombreTab = $request->nombreTab;

        $fecha = rfqs::where('idrfq', $idrfq)->value('fechaestimadaentrega');
        return view('editarInfoFecha')
            ->with('fecha', $fecha)
            ->with('nombreTab', $nombreTab)
            ->with('idrfq', $idrfq);
    }
    public function editarInfoObservacion(Request $request)
    {
        $idrfq = $request->idrfq;
        $nombreTab = $request->nombreTab;

        $observacion = rfqs::where('idrfq', $idrfq)->value('observaciones');
        return view('editarInfoObservacion')
            ->with('observacion', $observacion)
            ->with('nombreTab', $nombreTab)
            ->with('idrfq', $idrfq);
    }

    public function guardarDatosEstatus(Request $request)
    {
        $sidu = Session::get('sesionidu');
        $tipo = Session::get('sesiontipo');
        $sname = Session::get('sesionname');
       
	    $consulta = \DB::select("SELECT *  FROM rfqs WHERE idrfq  = $request->idrfq");
		$statusanterior = $consulta[0]->estatus;
		$cmgrfq= $consulta[0]->cmgrfq;
		$observaciones = $consulta[0]->observaciones;
		     if($request->estatus!="" and $request->estatus != $statusanterior)
		{                 
				    $cardex = new Cardex();
                    $cardex->idrfq = $request->idrfq;
                    $cardex->fecha = Carbon::now()->setTimezone("America/Mexico_City");
                    $cardex->idusu = $sidu;
                    $cardex->usucam = $sname;
                    $cardex->cmgr = $cmgrfq;
					$cardex->idstatusA = $request->estatus;
					$cardex->idstatusD = $statusanterior;
                    $cardex->observaciones = $observaciones;
                    $cardex->save();
		
       
        if ($tipo == 'Compras' or $tipo =='Administrador') {
            $idrfq = $request->idrfq;
            //$fechas = $request->fechaentrega;
            $rfq = rfqs::find($idrfq);
            $rfq->estatus = $request->estatus;
            //$rfq->fechaentrega = $fechas;
            $rfq->save();
        } else {
            $idrfq = $request->idrfq;
            $fechas = $request->fechaentrega;
            $rfq = rfqs::find($idrfq);
            $rfq->estatus = $request->estatus;
            $rfq->fechaentrega = $fechas;
            $rfq->save();
        }

         // Mensajes según el estatus del RFQ
         $mensaje = '';
         switch ($request->estatus) {
             case 'Cancelado':
                 $mensaje ="El kardex  $cmgrfq ha sido cancelado";;
                 break;
             case 'Precio de lista en sistema':
                 $mensaje ="El kardex $cmgrfq ya tiene precio de lista en sistema";
                 break;
             case 'Revisar kardex':
                 $mensaje = "Revisar observaciones al kardex $cmgrfq";
                 break;
             case 'Equipo Obsoleto':
                 $mensaje = "Equipo obsoleto del kardex $cmgrfq";
                 break;
             case 'En tránsito a Laredo':
                 $mensaje = "Item(s) del kardex $cmgrfq en tránsito a Laredo";
                 break;
             case 'En tránsito a oficinas GCM':
                 $mensaje = "Item(s) del kardex $cmgrfq en tránsito a oficina GCM";
                 break;
 
         }
         if($request->estatus=='Cancelado' or $request->estatus=='Precio de lista en sistema' or $request->estatus=='Revisar kardex' or $request->estatus=='Equipo Obsoleto' or $request->estatus=='En tránsito a Laredo' or $request->estatus=='En tránsito a oficinas GCM') 
         {
     $rfqnotificaciones = \DB::select("select * from rfqs where idrfq = $request->idrfq");
      $idcreorfq= $rfqnotificaciones[0]->idu;
      // Guardar notificación
      $notificacion = new Notificaciones();
      $notificacion->idumanda = $idcreorfq; // ID del usuario que cambió el estatus (el que realiza la acción)
      $notificacion->idurecibe =  $idcreorfq; // ID del usuario que creó el RFQ
      $notificacion->accion = $request->estatus;
      $notificacion->mensaje = $mensaje;
      $notificacion->visto = 0;
      $notificacion->fecha = Carbon::now()->setTimezone("America/Mexico_City"); // Fecha actual usando la clase Carbon
       $notificacion->created_at = Carbon::now()->setTimezone("America/Mexico_City");
		
      $notificacion->save();
         }
 $cambio = 1;
		}
		else
		{
			$cambio = 0;
		}
        if ($sidu == 18) {
            $consulta = rfqs::withTrashed()
                ->join("usuarios", "rfqs.idu", "=", "usuarios.idu")
                ->join("clientes", "rfqs.idclie", "=", "clientes.idc")
                ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
                ->select(
                    "rfqs.idrfq",
                    "rfqs.idu",
                    "rfqs.idclie",
                    "rfqs.idsuc",
                    "rfqs.ciudad",
                    "rfqs.estatus",
                    "rfqs.cmgrfq",
                    "rfqs.idcont",
                    "rfqs.nivelsolicitud",
                    "rfqs.fechasolicitud",
                    "rfqs.fechapropuesta",
                    "rfqs.fechaentrega",
                    "rfqs.fechaestimadaentrega",
                    "rfqs.observaciones",
                    "rfqs.deleted_at",
                    "clientes.razonSocial as razonSocial",
                    "sucursales.sucursal as sucursal",
                )
                ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")

                ->orderBy('cmgrfq')
                ->get();
        } else if ($tipo == 'Vendedor') {
            $consulta = rfqs::withTrashed()
                ->join("usuarios", "rfqs.idu", "=", "usuarios.idu")
                ->join("clientes", "rfqs.idclie", "=", "clientes.idc")
                ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
                ->select(
                    "rfqs.idrfq",
                    "rfqs.idu",
                    "rfqs.idclie",
                    "rfqs.idsuc",
                    "rfqs.ciudad",
                    "rfqs.estatus",
                    "rfqs.cmgrfq",
                    "rfqs.idcont",
                    "rfqs.nivelsolicitud",
                    "rfqs.fechasolicitud",
                    "rfqs.fechapropuesta",
                    "rfqs.fechaentrega",
                    "rfqs.fechaestimadaentrega",
                    "rfqs.observaciones",
                    "rfqs.deleted_at",
                    "clientes.razonSocial as razonSocial",
                    "sucursales.sucursal as sucursal",
                )
                ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")
                ->where('rfqs.idu', $sidu)
                ->orderBy('cmgrfq')
                ->get();
        } else if ($tipo == 'Administrador' || 'Compras') {
            $consulta = rfqs::withTrashed()
                ->join("usuarios", "rfqs.idu", "=", "usuarios.idu")
                ->join("clientes", "rfqs.idclie", "=", "clientes.idc")
                ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
                ->select(
                    "rfqs.idrfq",
                    "rfqs.idu",
                    "rfqs.idclie",
                    "rfqs.idsuc",
                    "rfqs.ciudad",
                    "rfqs.estatus",
                    "rfqs.cmgrfq",
                    "rfqs.idcont",
                    "rfqs.nivelsolicitud",
                    "rfqs.fechasolicitud",
                    "rfqs.fechapropuesta",
                    "rfqs.fechaentrega",
                    "rfqs.fechaestimadaentrega",
                    "rfqs.observaciones",
                    "rfqs.deleted_at",
                    "clientes.razonSocial as razonSocial",
                    "sucursales.sucursal as sucursal",
                )
                ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")
                //->where('rfqs.idu', $sidu)
                ->orderBy('cmgrfq')
                ->get();
        }

        return view("reporteRfqsTabla")
            ->with('consulta', $consulta);
    }

    public function guardarDatosFecha(Request $request)
    {
        $idrfq = $request->idrfq;
        $fecha = $request->fechaestimadaentrega;
        $rfq = rfqs::find($idrfq);
        $rfq->fechaestimadaentrega = $fecha;
        $rfq->save();

        $sidu = Session::get('sesionidu');
        $tipo = Session::get('sesiontipo');


        if ($tipo == 'Vendedor') {
            $consulta = rfqs::withTrashed()
                ->join("usuarios", "rfqs.idu", "=", "usuarios.idu")
                ->join("clientes", "rfqs.idclie", "=", "clientes.idc")
                ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
                ->select(
                    "rfqs.idrfq",
                    "rfqs.idu",
                    "rfqs.idclie",
                    "rfqs.idsuc",
                    "rfqs.ciudad",
                    "rfqs.estatus",
                    "rfqs.cmgrfq",
                    "rfqs.idcont",
                    "rfqs.nivelsolicitud",
                    "rfqs.fechasolicitud",
                    "rfqs.fechapropuesta",
                    "rfqs.fechaentrega",
                    "rfqs.fechaestimadaentrega",
                    "rfqs.observaciones",
                    "rfqs.deleted_at",
                    "clientes.razonSocial as razonSocial",
                    "sucursales.sucursal as sucursal",
                )
                ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")
                ->where('rfqs.idu', $sidu)
                ->orderBy('cmgrfq')
                ->get();
        } else if ($tipo == 'Administrador' || 'Compras') {
            $consulta = rfqs::withTrashed()->join("usuarios", "rfqs.idu", "=", "usuarios.idu")->join("clientes", "rfqs.idclie", "=", "clientes.idc")
                ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
                ->select(
                    "rfqs.idrfq",
                    "rfqs.idu",
                    "rfqs.idclie",
                    "rfqs.idsuc",
                    "rfqs.ciudad",
                    "rfqs.estatus",
                    "rfqs.cmgrfq",
                    "rfqs.idcont",
                    "rfqs.nivelsolicitud",
                    "rfqs.fechasolicitud",
                    "rfqs.fechapropuesta",
                    "rfqs.fechaentrega",
                    "rfqs.fechaestimadaentrega",
                    "rfqs.observaciones",
                    "rfqs.deleted_at",
                    "clientes.razonSocial as razonSocial",
                    "sucursales.sucursal as sucursal",
                )
                ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")
                ->orderBy('cmgrfq')
                ->get();
        }

        return view("reporteRfqsTabla")
            ->with('consulta', $consulta);
    }

    public function guardarDatosObservacion(Request $request)
    {
		$sidu = Session::get('sesionidu');
        $tipo = Session::get('sesiontipo');
        $sname = Session::get('sesionname');
    //guarda cardex observacion
        $consulta = \DB::select("SELECT *  FROM rfqs WHERE idrfq  = $request->idrfq");
		$statusanterior = $consulta[0]->estatus;
		$cmgrfq= $consulta[0]->cmgrfq;
		$status = $consulta[0]->observaciones;
		          
				    $cardex = new Cardex();
                    $cardex->idrfq = $request->idrfq;
                    $cardex->fecha = Carbon::now()->setTimezone("America/Mexico_City");
                    $cardex->idusu = $sidu;
                    $cardex->usucam = $sname;
                    $cardex->cmgr = $cmgrfq;
					$cardex->idstatusA = $statusanterior;
					$cardex->idstatusD = $statusanterior;
                    $cardex->observaciones = $request->observaciones;
                    $cardex->save();
					
        $idrfq = $request->idrfq;
        $observacion = $request->observaciones;
        $rfq = rfqs::find($idrfq);
        $rfq->observaciones = $observacion;
        $rfq->save();

        $rfqnotificaciones = \DB::select("select * from rfqs where idrfq = $request->idrfq");
     $idcreorfq= $rfqnotificaciones[0]->idu;
     // Guardar notificación
     $notificacion = new Notificaciones();
     if( $tipo=='Vendedor')
     {
     $notificacion->idumanda = $idcreorfq; // ID del usuario que cambió el estatus (el que realiza la acción)
     $notificacion->idurecibe =  1; // ID del usuario que creó el RFQ
     }
     if($tipo =='Compras' or $sidu ==10)
     {
        $notificacion->idumanda = $idcreorfq; // ID del usuario que cambió el estatus (el que realiza la acción)
        $notificacion->idurecibe = $idcreorfq;
     }
     $notificacion->accion = "Observaciones";
     $notificacion->mensaje = "Revisar observaciones al kardex $cmgrfq";
     $notificacion->fecha = Carbon::now()->setTimezone("America/Mexico_City"); // Fecha actual usando la clase Carbon
     $notificacion->created_at = Carbon::now()->setTimezone("America/Mexico_City");
     $notificacion->visto = 0;
     $notificacion->save();


        $sidu = Session::get('sesionidu');
        $tipo = Session::get('sesiontipo');


        if ($tipo == 'Vendedor') {
            $consulta = rfqs::withTrashed()
                ->join("usuarios", "rfqs.idu", "=", "usuarios.idu")
                ->join("clientes", "rfqs.idclie", "=", "clientes.idc")
                ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
                ->select(
                    "rfqs.idrfq",
                    "rfqs.idu",
                    "rfqs.idclie",
                    "rfqs.idsuc",
                    "rfqs.ciudad",
                    "rfqs.estatus",
                    "rfqs.cmgrfq",
                    "rfqs.idcont",
                    "rfqs.nivelsolicitud",
                    "rfqs.fechasolicitud",
                    "rfqs.fechapropuesta",
                    "rfqs.fechaentrega",
                    "rfqs.fechaestimadaentrega",
                    "rfqs.observaciones",
                    "rfqs.deleted_at",
                    "clientes.razonSocial as razonSocial",
                    "sucursales.sucursal as sucursal",
                )
                ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")
                ->where('rfqs.idu', $sidu)
                ->orderBy('cmgrfq')
                ->get();
        } else if ($tipo == 'Jefe de ventas' || 'Compras') {
            $consulta = rfqs::withTrashed()->join("usuarios", "rfqs.idu", "=", "usuarios.idu")->join("clientes", "rfqs.idclie", "=", "clientes.idc")
                ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
                ->select(
                    "rfqs.idrfq",
                    "rfqs.idu",
                    "rfqs.idclie",
                    "rfqs.idsuc",
                    "rfqs.ciudad",
                    "rfqs.estatus",
                    "rfqs.cmgrfq",
                    "rfqs.idcont",
                    "rfqs.nivelsolicitud",
                    "rfqs.fechasolicitud",
                    "rfqs.fechapropuesta",
                    "rfqs.fechaentrega",
                    "rfqs.fechaestimadaentrega",
                    "rfqs.observaciones",
                    "rfqs.deleted_at",
                    "clientes.razonSocial as razonSocial",
                    "sucursales.sucursal as sucursal",
                )
                ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")
                ->orderBy('cmgrfq')
                ->get();
        }

        
        //guardado de cardex en rfq 
       /* if ($sidu == 'vendedor' || 18) {
            $rfq = rfqs::all();
            $cardexExists = false; // Variable para indicar si ya existe un registro en Cardex con los mismos datos

            foreach ($rfq as $value) {
                // Verificar si ya existe un registro en Cardex con los mismos datos
                $existingCardex = Cardex::where('idrfq', $value->idrfq)
                    ->where('idusu', $value->idu)
                    ->where('idstatusA', $value->estatus)
                    ->where('observaciones', $value->observaciones)
                    ->first();

                if ($existingCardex) {
                    $cardexExists = true; // Actualizar la variable indicando que ya existe un registro
                } else {
                    // Si no existe un registro en Cardex con los mismos datos, crear uno nuevo
                    $cardex = new Cardex();
                    $cardex->idrfq = $value->idrfq;
                    $cardex->fecha = date('Y-m-d');
                    $cardex->idusu = $value->idu;
                    $cardex->usucam = $sname;
                    $cardex->cmgr = $value->cmgrfq;
                    // Obtener el último registro de Cardex correspondiente a idrfq
                    $ultimoCardex = Cardex::where('idrfq', $value->idrfq)->latest()->first();

                    if ($ultimoCardex) {
                        // Asignar el valor anterior de idstatusA a idstatusD
                        $cardex->idstatusD = $ultimoCardex->idstatusA;
                    } else {
                        // Si no hay registros anteriores, asignar un valor por defecto
                        $cardex->idstatusD = 'Sin estatus anterior';
                    }

                    $cardex->idstatusA = $value->estatus;
                    $cardex->observaciones = $value->observaciones;

                    $cardex->save();
                }
            }
        }*/

        return view("reporteRfqsTabla")
            ->with('consulta', $consulta);
    }

    public function detalleRfq(Request $request, $idrfq)
    {
        $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $spat = Session::get('sesionpaterno');
        $smat = Session::get('sesionmaterno');
        $stipo = Session::get('sesiontipo');
        if ($sname == '' or $sidu == '' or $stipo == '' or $spat == '' or $smat == '') {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        } else {

            $consulta = rfqs::withoutTrashed()
                ->join("usuarios", "rfqs.idu", "=", "usuarios.idu")
                ->join("clientes", "rfqs.idclie", "=", "clientes.idc")
                ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
                ->join("contactosucursales", "rfqs.idcont", "=", "contactosucursales.idContactoSuc")
                ->join("marcasRefaccion", "rfqs.idmarca", "=", "marcasRefaccion.idMarcaRefa")
                ->select(
                    "rfqs.idrfq",
                    "rfqs.idu",
                    "rfqs.idmarca",
                    "rfqs.idclie",
                    "rfqs.idsuc",
                    "rfqs.ciudad",
                    "rfqs.cmgrfq",
                    "rfqs.notasrfq",
                    //"rfqs.consecutivo",
                    "rfqs.idcont",
                    "rfqs.nivelsolicitud",
                    "rfqs.fechasolicitud",
                    "rfqs.fechaestimadaentrega",
                    "rfqs.fechapropuesta",
                    "rfqs.fechaentrega",
                    "clientes.razonSocial as razonSocial",
                    "sucursales.sucursal as sucursal",
                    "marcasRefaccion.marcaRefaccion as marcaRefa"
                )
                ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")
                ->selectRaw("concat(puesto,' - ',nombreCont) as contacto")
                ->where("idrfq", $idrfq)
                ->get();

            $consultaProductosRfq = rfqsDetalle::where('idRfq', '=', $idrfq)
                ->join("tiporefacciones", "rfqsDetalle.idTipoRefa", "=", "tiporefacciones.idTipoRefacciones")
                ->join("partesVenta", "rfqsDetalle.idPartesVenta", "=", "partesVenta.idPartesVenta")
                ->select(
                    "rfqsDetalle.idRfqDetalle",
                    "rfqsDetalle.idRfq",
                    "rfqsDetalle.cantidad",
                    "rfqsDetalle.modelo",
                    "rfqsDetalle.notas",
                    "rfqsDetalle.tipo",
                    "rfqsDetalle.tip",
                    "rfqsDetalle.sku_generico",
                    "rfqsDetalle.foto1",
                    "rfqsDetalle.foto2",
                    "rfqsDetalle.foto3",
                    "rfqsDetalle.foto4",
                    "rfqsDetalle.foto5",
                    "tiporefacciones.nombreTipoRefaccion as tipoRefa",
                )
                ->selectRaw("concat(partesVenta.codigo, ' ', partesVenta.nombreRefaccion) as sku")
                ->selectRaw("concat(rfqsDetalle.sku_generico) as skugenerico")

                ->get();

            $cuantosProductosRfq = count($consultaProductosRfq);
        }
        return view("detallerfq")
            ->with('inicial', $sname[0])
            ->with('consulta', $consulta[0])
            ->with('consultaProductosRfq', $consultaProductosRfq)
            ->with('cuantosProductosRfq', $cuantosProductosRfq);
    }

    // Solicitudes AJAX

    // Esta funcion recibe idc del script y segun ese id genera una consulta y retorna un JSON con la sucursal y el idsucursal
    public function obtSucursales(Request $request)
    {
        if ($request->ajax()) {
            $sucursales = sucursales::where('idc', $request->idc)
                ->where('activo', 'Si')
                ->where('tipoSucursal', 'activo')
                ->orderBy('sucursal')
                ->pluck('sucursal', 'idsucursal'); // Obtener arreglo de estos atributos
            return response()->json($sucursales);
        }
    }

    // Esta funcion recibe id del script y segun ese id genera una consulta y retorna un JSON con calle , colonia y num
    public function obtCiudad(Request $request)
    {
        if ($request->ajax()) {
            $ciudad = sucursales::where('idsucursal', $request->id)
                ->first(['calle', 'colonia', 'num']); // Obtener primer coincidencia
            // Asociacion de data del JSON
            return response()->json([
                'calle' => $ciudad->calle,
                'colonia' => $ciudad->colonia,
                'num' => $ciudad->num,
            ]);
        }
    }

    // Esta funcion recibe id del script y segun ese id genera consultas y retorna un JSON con el gcmid y consecutivo correspondiente
    public function obtGcmidMarca(Request $request)
    {
        if ($request->ajax()) {
            $gcmid = sucursales::where('idsucursal', $request->id)->value('gcmid');
            $marca = marcaRefaccion::where('idMarcaRefa', $request->idmarca)->value('MarcaRFQ');
            return response()->json([
                'gcmid' => $gcmid,
                'marca' => $marca
            ]);
        }
    }

    // Esta funcion recibe id del script y segun ese id genera una consulta y retorna un JSON los contactos
    public function obtContactos(Request $request)
    {
        if ($request->ajax()) {
            $contactos = contactosucursales::selectRaw("CONCAT(puesto,' - ',nombreCont) AS contacto, idContactoSuc")
                ->where('idSucursal', $request->id)
                ->orderBy('puesto')
                ->pluck('contacto', 'idContactoSuc'); // Obtener arreglo de estos atributos
            return response()->json($contactos);
        }
    }

    // Esta funcion recibe idc del script y segun ese id genera una consulta y retorna un JSON con la sucursal y el idsucursal
    public function obtTipos(Request $request)
    {
        if ($request->ajax()) {
            $tipos = tiporefacciones::where('idMarcaRefa', $request->id)
                ->where('activo', 'Si')
                ->orderBy('nombreTipoRefaccion')
                ->pluck('nombreTipoRefaccion', 'idTipoRefacciones'); // Obtener arreglo de estos atributos
            return response()->json($tipos);
        }
    }

    // Esta funcion recibe id del script y segun ese id genera una consulta y retorna un JSON los contactos
    public function obtSkus(Request $request)
    {
        if ($request->ajax()) {
            $skus = partesVenta::selectRaw("CONCAT(codigo,' - ',nombreRefaccion) AS parte, idPartesVenta")
                ->where('idTipoRefacciones', $request->id)
                ->where('activo', 'Si')
                ->whereIn('tipoProducto', ['Nuevo', 'Refurbished y/o Repair'])
                ->where('presentacion', '!=', 'Servicio')
                ->orderBy('codigo')
                ->pluck('parte', 'idPartesVenta'); // Obtener arreglo de estos atributos
            return response()->json($skus);
        }
    }

    public function obtModelo(Request $request)
    {
        if ($request->ajax()) {
            $modelo = partesVenta::where('idPartesVenta', $request->id)
                ->where('activo', 'Si')
                ->whereIn('tipoProducto', ['Nuevo', 'Refurbished y/o Repair'])
                ->where('presentacion', '!=', 'Servicio')
                ->value('modelo'); // value solo devuelve un unico valor de la condicion
            // Asociacion de data del JSON
            return $modelo;
        }
    }

    public function obtGenerico(Request $request)
    {
        if ($request->ajax()) {
            $id_marca = $request->input('id_marca');

            $generico = tiporefacciones::where('idMarcaRefa', $id_marca)
                ->select('idTipoRefacciones', 'nombreTipoRefaccion')
                ->first();

            return response()->json($generico);
        }
    }

    public function obtGenericoSku(Request $request)
    {
        if ($request->ajax()) {
            $sku = partesVenta::where('idTipoRefacciones', $request->id)
                ->value('idPartesVenta');
            return $sku;
        }
    }

    function comboSucursal(Request $request)
    {
        $sucursales = sucursales::where('idc', $request->idc)
            ->where('activo', 'Si')
            ->where('tipoSucursal', 'activo')
            ->orderBy('sucursal')
            ->get();

        return view('comboSuc')
            ->with('sucursales', $sucursales);
    }

    function comboContacto(Request $request)
    {

        $contactos = contactosucursales::selectRaw("CONCAT(puesto,' - ',nombreCont) AS contacto, idContactoSuc")
            ->where('idSucursal', $request->ids)
            ->orderBy('puesto')
            ->get();

        return view('comboCont')
            ->with('contactos', $contactos);
    }

    function comboTipo(Request $request)
    {
        $tipos = tiporefacciones::where('idMarcaRefa', $request->idMarca)
            ->where('activo', 'Si')
            ->orderBy('nombreTipoRefaccion')
            ->get();

        return view('comboTip')
            ->with('tipos', $tipos);
    }

    function comboSKU(Request $request)
    {
        $skus = partesVenta::selectRaw("CONCAT(codigo,' - ',nombreRefaccion) AS parte, idPartesVenta")
            ->where('idTipoRefacciones', $request->tipo)
            ->where('activo', 'Si')
            ->whereIn('tipoProducto', ['Nuevo', 'Refurbished y/o Repair'])
            ->where('presentacion', '!=', 'Servicio')
            ->orderBy('codigo')
            ->get();

        return view('combSKU')
            ->with('skus', $skus);
    }

    public function editarProductoRfq(Request $request)
    {
        $idRfqDetalle = $request->idRfqDetalle;
        $idRfq = $request->idRfq;
        $consulta = rfqsDetalle::where('idRfqDetalle', '=', $idRfqDetalle)
            ->join("tiporefacciones", "rfqsDetalle.idTipoRefa", "=", "tiporefacciones.idTipoRefacciones")
            ->join("partesVenta", "rfqsDetalle.idPartesVenta", "=", "partesVenta.idPartesVenta")
            ->select(
                "rfqsDetalle.idRfqDetalle",
                "rfqsDetalle.idRfq",
                "rfqsDetalle.idMarca",
                "rfqsDetalle.cantidad",
                "rfqsDetalle.modelo",
                "rfqsDetalle.notas",
                "rfqsDetalle.tip",
                "rfqsDetalle.tipo",
                "rfqsDetalle.sku_generico",
                "rfqsDetalle.foto1",
                "rfqsDetalle.foto2",
                "rfqsDetalle.foto3",
                "rfqsDetalle.foto4",
                "rfqsDetalle.foto5",
                "tiporefacciones.nombreTipoRefaccion as tipoRefa",
            )
            ->selectRaw("concat(partesVenta.codigo, ' ', partesVenta.nombreRefaccion) as sku")
            ->selectRaw("concat(partesVenta.nombreRefaccion, ' - ', rfqsDetalle.modelo) as skugenerico")
            ->first();

        $marca = $consulta->idMarca;

        if ($consulta && $consulta->tipo === 'SKU') {
            $selectedTipo = rfqsDetalle::where('idRfqDetalle', $idRfqDetalle)
                ->value('idTipoRefa');

            $tipos = tiporefacciones::where('idMarcaRefa', $marca)
                ->where('activo', 'Si')
                ->orderBy('nombreTipoRefaccion')
                ->get();

            $selectedSku = rfqsDetalle::where('idRfqDetalle', $idRfqDetalle)
                ->value('idPartesVenta');

            $skus = partesVenta::selectRaw("CONCAT(codigo,' - ',nombreRefaccion) AS parte, idPartesVenta")
                ->where('idTipoRefacciones', $selectedTipo)
                ->where('activo', 'Si')
                ->whereIn('tipoProducto', ['Nuevo', 'Refurbished y/o Repair'])
                ->where('presentacion', '!=', 'Servicio')
                ->orderBy('codigo')
                ->get();


            return view('editarProducto')
                ->with('consulta', $consulta)
                ->with('idRfq', $idRfq)
                ->with('idRfqDetalle', $idRfqDetalle)
                ->with('tipos', $tipos)
                ->with('selectedTipo', $selectedTipo)
                ->with('skus', $skus)
                ->with('selectedSku', $selectedSku)
                ->with('marca', $marca);
        }

        return view('editarProductoGenerico')
            ->with('consulta', $consulta)
            ->with('idRfq', $idRfq)
            ->with('idRfqDetalle', $idRfqDetalle);
    }

    public function eliminarFoto1($id)
    {
        $detalle = rfqsDetalle::findOrFail($id);
        if ($detalle->foto1 != 'Sin foto') {
            Storage::disk('local')->delete($detalle->foto1);

            $detalle->foto1 = 'Sin foto';
            $detalle->save();
        }
        return response()->json(['message' => 'Foto eliminada correctamente']);
    }
    public function eliminarFoto2($id)
    {
        $detalle = rfqsDetalle::findOrFail($id);
        if ($detalle->foto2 != 'Sin foto') {
            Storage::disk('local')->delete($detalle->foto2);

            $detalle->foto2 = 'Sin foto';
            $detalle->save();
        }
        return response()->json(['message' => 'Foto eliminada correctamente']);
    }
    public function eliminarFoto3($id)
    {
        $detalle = rfqsDetalle::findOrFail($id);
        if ($detalle->foto3 != 'Sin foto') {
            Storage::disk('local')->delete($detalle->foto3);

            $detalle->foto3 = 'Sin foto';
            $detalle->save();
        }
        return response()->json(['message' => 'Foto eliminada correctamente']);
    }
    public function eliminarFoto4($id)
    {
        $detalle = rfqsDetalle::findOrFail($id);
        if ($detalle->foto4 != 'Sin foto') {
            Storage::disk('local')->delete($detalle->foto4);

            $detalle->foto4 = 'Sin foto';
            $detalle->save();
        }
        return response()->json(['message' => 'Foto eliminada correctamente']);
    }
    public function eliminarFoto5($id)
    {
        $detalle = rfqsDetalle::findOrFail($id);
        if ($detalle->foto5 != 'Sin foto') {
            Storage::disk('local')->delete($detalle->foto5);

            $detalle->foto5 = 'Sin foto';
            $detalle->save();
        }
        return response()->json(['message' => 'Foto eliminada correctamente']);
    }

    public function guardarModifProd(Request $request)
    {
        $idRfqDetalle = $request->idrfqdetalleM;
        $marca = $request->rfqmarca;
        $nombreArchivos = array();
        $rfqDetalle = rfqsDetalle::find($idRfqDetalle);

        // Almacenar los valores actuales de las fotos
        $valoresActuales = [
            'foto1' => $rfqDetalle->foto1,
            'foto2' => $rfqDetalle->foto2,
            'foto3' => $rfqDetalle->foto3,
            'foto4' => $rfqDetalle->foto4,
            'foto5' => $rfqDetalle->foto5,
        ];

        for ($i = 1; $i < 6; $i++) {
            $archivo = $request->file('foto' . $i . 'M');

            if ($archivo && $archivo->isValid()) {
                $ldate = date('Ymd_His_');
                $nombreArchivo = $archivo->getClientOriginalName();
                $nombreArchivo = $ldate . $nombreArchivo;
                $nombreArchivo = str_replace(" ", "_", $nombreArchivo);
                Storage::disk('local')->put($nombreArchivo, File::get($archivo));
            } else {
                // Conservar el valor actual si no se recibió un nuevo archivo
                $nombreArchivo = $valoresActuales['foto' . $i];
            }

            array_push($nombreArchivos, $nombreArchivo);
        }

        $rfqDetalle = rfqsDetalle::find($idRfqDetalle);
        $rfqDetalle->idTipoRefa = $request->tipoM;
        $rfqDetalle->idPartesVenta = $request->skuM;
        $rfqDetalle->modelo = $request->modeloM;
        $rfqDetalle->tip = $request->tipM;
        $rfqDetalle->cantidad = $request->cantidadM;
        $rfqDetalle->notas = $request->notasM;
        $rfqDetalle->foto1 = $nombreArchivos[0];
        $rfqDetalle->foto2 = $nombreArchivos[1];
        $rfqDetalle->foto3 = $nombreArchivos[2];
        $rfqDetalle->foto4 = $nombreArchivos[3];
        $rfqDetalle->foto5 = $nombreArchivos[4];
        $rfqDetalle->save();

        $idRfq = $rfqDetalle->idRfq;
        $consulta = rfqsDetalle::where('idRfq', '=', $idRfq)
            ->join("tiporefacciones", "rfqsDetalle.idTipoRefa", "=", "tiporefacciones.idTipoRefacciones")
            ->join("partesVenta", "rfqsDetalle.idPartesVenta", "=", "partesVenta.idPartesVenta")
            ->select(
                "rfqsDetalle.idRfqDetalle",
                "rfqsDetalle.idRfq",
                "rfqsDetalle.cantidad",
                "rfqsDetalle.modelo",
                "rfqsDetalle.notas",
                "rfqsDetalle.tipo",
                "rfqsDetalle.tip",
                "rfqsDetalle.sku_generico",
                "rfqsDetalle.foto1",
                "rfqsDetalle.foto2",
                "rfqsDetalle.foto3",
                "rfqsDetalle.foto4",
                "rfqsDetalle.foto5",
                "tiporefacciones.nombreTipoRefaccion as tipoRefa",
            )
            ->selectRaw("concat(partesVenta.codigo, ' ', partesVenta.nombreRefaccion) as sku")
            ->selectRaw("concat(rfqsDetalle.sku_generico) as skugenerico")
            ->get();



        return view("reporteproductosrfq")
            ->with('consulta', $consulta)
            ->with('marca', $marca);
    }

    public function guardarModifProdG(Request $request)
    {
        $idRfqDetalle = $request->idrfqdetalleG;

        $rfqDetalle = rfqsDetalle::find($idRfqDetalle);

        // Almacenar los valores actuales de las fotos
        $valoresActuales = [
            'foto1' => $rfqDetalle->foto1,
            'foto2' => $rfqDetalle->foto2,
            'foto3' => $rfqDetalle->foto3,
            'foto4' => $rfqDetalle->foto4,
            'foto5' => $rfqDetalle->foto5,
        ];

        $nombreArchivos = array();
        for ($i = 1; $i < 6; $i++) {
            $archivo = $request->file('foto' . $i . 'G');

            if ($archivo && $archivo->isValid()) {
                $ldate = date('Ymd_His_');
                $nombreArchivo = $archivo->getClientOriginalName();
                $nombreArchivo = $ldate . $nombreArchivo;
                $nombreArchivo = str_replace(" ", "_", $nombreArchivo);
                Storage::disk('local')->put($nombreArchivo, File::get($archivo));
            } else {
                // Conservar el valor actual si no se recibió un nuevo archivo
                $nombreArchivo = $valoresActuales['foto' . $i];
            }

            array_push($nombreArchivos, $nombreArchivo);
        }
        $rfqDetalle = rfqsDetalle::find($idRfqDetalle);
        $rfqDetalle->sku_generico = $request->skuG;
        $rfqDetalle->modelo = $request->modeloG;
        $rfqDetalle->tip = $request->tipG;
        $rfqDetalle->cantidad = $request->cantidadG;
        $rfqDetalle->notas = $request->notasG;
        $rfqDetalle->foto1 = $nombreArchivos[0];
        $rfqDetalle->foto2 = $nombreArchivos[1];
        $rfqDetalle->foto3 = $nombreArchivos[2];
        $rfqDetalle->foto4 = $nombreArchivos[3];
        $rfqDetalle->foto5 = $nombreArchivos[4];
        $rfqDetalle->save();

        $idRfq = $rfqDetalle->idRfq;
        $consulta = rfqsDetalle::where('idRfq', '=', $idRfq)
            ->join("tiporefacciones", "rfqsDetalle.idTipoRefa", "=", "tiporefacciones.idTipoRefacciones")
            ->join("partesVenta", "rfqsDetalle.idPartesVenta", "=", "partesVenta.idPartesVenta")
            ->select(
                "rfqsDetalle.idRfqDetalle",
                "rfqsDetalle.idRfq",
                "rfqsDetalle.cantidad",
                "rfqsDetalle.modelo",
                "rfqsDetalle.notas",
                "rfqsDetalle.tipo",
                "rfqsDetalle.tip",
                "rfqsDetalle.sku_generico",
                "rfqsDetalle.foto1",
                "rfqsDetalle.foto2",
                "rfqsDetalle.foto3",
                "rfqsDetalle.foto4",
                "rfqsDetalle.foto5",
                "tiporefacciones.nombreTipoRefaccion as tipoRefa",
            )
            ->selectRaw("concat(partesVenta.codigo, ' ', partesVenta.nombreRefaccion) as sku")
            ->selectRaw("concat(rfqsDetalle.sku_generico) as skugenerico")
            ->get();



        return view("reporteproductosrfq")
            ->with('consulta', $consulta);
    }
    public function modalNotificacion(Request $request)
    {
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
        $sname = Session::get('sesionname');
        if ($stipo === 'Compras' or $sidu === 10 or $sidu ===1) {
            $consulta = \DB::SELECT("SELECT *, DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') AS fecha_hora
            FROM Notificaciones
            WHERE created_at >= DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -7 DAY), '%Y/%m/%d')
            AND idurecibe = 1
            AND (visto IS NULL OR visto = 0)
            ORDER BY created_at DESC");
            $cuantos = count($consulta);
            return view('modalNoti')
                ->with('consulta', $consulta)
                ->with('cuantos', $cuantos);
        }
        if ($stipo === 'Vendedor' and  $sidu <> 18) {
            $consulta = \DB::SELECT("SELECT *, DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') AS fecha_hora
            FROM Notificaciones
            WHERE created_at >= DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -7 DAY), '%Y/%m/%d')
            AND idurecibe = $sidu
            AND (visto IS NULL OR visto = 0)
            ORDER BY created_at DESC");
            $cuantos = count($consulta);
            return view('modalNoti')
                ->with('consulta', $consulta)
                ->with('cuantos', $cuantos);
        }
        if ($stipo === 'Vendedor' and $sidu === 18) {
            $consulta = \DB::SELECT("SELECT *, DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') AS fecha_hora
            FROM Notificaciones
            WHERE created_at >= DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -7 DAY), '%Y/%m/%d')
            AND idurecibe <>1
            AND (visto IS NULL OR visto = 0)
            ORDER BY created_at DESC");
            $cuantos = count($consulta);
            return view('modalNoti')
                ->with('consulta', $consulta)
                ->with('cuantos', $cuantos);
        }
    }

    public function visto(Request $request){
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
        $sname = Session::get('sesionname');

        $idnot = $request -> idnot;
        $noti = Notificaciones::find($idnot);

        if($noti){
        $noti->visto = 1;
        $noti->save();
        }

        if ($stipo === 'Compras' or $sidu === 10 or $sidu ===1) {
            $consulta = \DB::SELECT("SELECT *, DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') AS fecha_hora FROM Notificaciones
            WHERE created_at >= DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -7 DAY), '%Y/%m/%d')
            AND idurecibe = 1
            AND (visto = 1 OR visto = 0)
            ORDER BY created_at DESC");
            $cuantos = count($consulta);
            return view('modalNotiDos')
                ->with('consulta', $consulta)
                ->with('cuantos', $cuantos);
        }
        if ($stipo === 'Vendedor' and  $sidu <> 18) {
            $consulta = \DB::SELECT("SELECT *, DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') AS fecha_hora FROM Notificaciones
            WHERE created_at >= DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -7 DAY), '%Y/%m/%d')
            AND idurecibe = $sidu
            AND (visto = 1 OR visto = 0)
            ORDER BY created_at DESC");
            $cuantos = count($consulta);
            return view('modalNotiDos')
                ->with('consulta', $consulta)
                ->with('cuantos', $cuantos);
        }
        if ($stipo === 'Vendedor' and $sidu === 18) {
            $consulta = \DB::SELECT("SELECT *, DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') AS fecha_hora FROM Notificaciones
            WHERE created_at >= DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -7 DAY), '%Y/%m/%d')
            AND idurecibe <>1
            AND (visto = 1 OR visto = 0)
            ORDER BY created_at DESC");
            $cuantos = count($consulta);
            return view('modalNotiDos')
                ->with('consulta', $consulta)
                ->with('cuantos', $cuantos);
        }

    }


    public function cerrar(){

        // Buscar y actualizar los registros de 1 a 2
       Notificaciones::where('visto', 1)->update(['visto' => 2]);
   }

    public function tablaActivos (Request $request)
    {
        // $data = [];
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

        if ($stipo == 'Compras' || $sidu == 18 || $stipo == 'Administrador') {
            $consulta = rfqs::withTrashed()
                ->join("usuarios", "rfqs.idu", "=", "usuarios.idu")
                ->join("clientes", "rfqs.idclie", "=", "clientes.idc")
                ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
                ->select(
                    "rfqs.idrfq",
                    "rfqs.idu",
                    "rfqs.idclie",
                    "rfqs.idsuc",
                    "rfqs.ciudad",
                    "rfqs.estatus",
                    "rfqs.cmgrfq",
                    "rfqs.idcont",
                    "rfqs.nivelsolicitud",
                    "rfqs.fechasolicitud",
                    "rfqs.fechaentrega",
                    "rfqs.fechapropuesta",
                    "rfqs.fechaestimadaentrega",
                    "rfqs.observaciones",
                    "rfqs.deleted_at",
                    "clientes.razonSocial as razonSocial",
                    "sucursales.sucursal as sucursal",
                )
                ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")
                ->whereNotIn('rfqs.estatus', ['Entregado', 'Cancelado'])
                ->orderBy('fechasolicitud', 'DESC')
                ->get();

            $cuantosrfq = count($consulta);

        } else if ($stipo == 'Vendedor') {
            $consulta = rfqs::withTrashed()
                ->join("usuarios", "rfqs.idu", "=", "usuarios.idu")
                ->join("clientes", "rfqs.idclie", "=", "clientes.idc")
                ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
                ->select(
                    "rfqs.idrfq",
                    "rfqs.idu",
                    "rfqs.idclie",
                    "rfqs.idsuc",
                    "rfqs.ciudad",
                    "rfqs.estatus",
                    "rfqs.cmgrfq",
                    "rfqs.idcont",
                    "rfqs.nivelsolicitud",
                    "rfqs.fechasolicitud",
                    "rfqs.fechaentrega",
                    "rfqs.fechapropuesta",
                    "rfqs.fechaestimadaentrega",
                    "rfqs.observaciones",
                    "rfqs.deleted_at",
                    "clientes.razonSocial as razonSocial",
                    "sucursales.sucursal as sucursal",
                )
                ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")
                ->where('rfqs.idu', $sidu)
                ->whereNotIn('rfqs.estatus', ['Entregado', 'Cancelado'])
                ->orderBy('fechasolicitud', 'DESC')
                ->get();

            $cuantosrfq = count($consulta);
        }

        // return view('tablaActivos', ['data' => $data])->with("cuantosrfq", $cuantosrfq);
        return view('tablaActivos')->with("consulta", $consulta)->with("cuantosrfq", $cuantosrfq);
    }

    public function tablaEntregados (Request $request)
    {
        // $data = [];
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

        if ($stipo == 'Compras' || $sidu == 18 || $stipo == 'Administrador') {
            $consulta = rfqs::withTrashed()->join("usuarios", "rfqs.idu", "=", "usuarios.idu")->join("clientes", "rfqs.idclie", "=", "clientes.idc")
                ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
                ->select(
                    "rfqs.idrfq",
                    "rfqs.idu",
                    "rfqs.idclie",
                    "rfqs.idsuc",
                    "rfqs.ciudad",
                    "rfqs.estatus",
                    "rfqs.cmgrfq",
                    "rfqs.idcont",
                    "rfqs.nivelsolicitud",
                    "rfqs.fechasolicitud",
                    "rfqs.fechapropuesta",
                    "rfqs.fechaentrega",
                    "rfqs.fechaestimadaentrega",
                    "rfqs.observaciones",
                    "rfqs.deleted_at",
                    "clientes.razonSocial as razonSocial",
                    "sucursales.sucursal as sucursal",
                )
                ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")
                ->where('rfqs.estatus', 'Entregado')
                ->orderBy('fechasolicitud', 'DESC')
                ->get();

            $cuantosrfq = count($consulta);
        } else if ($stipo == 'Vendedor') {
            $consulta = rfqs::withTrashed()
                ->join("usuarios", "rfqs.idu", "=", "usuarios.idu")
                ->join("clientes", "rfqs.idclie", "=", "clientes.idc")
                ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
                ->select(
                    "rfqs.idrfq",
                    "rfqs.idu",
                    "rfqs.idclie",
                    "rfqs.idsuc",
                    "rfqs.ciudad",
                    "rfqs.estatus",
                    "rfqs.cmgrfq",
                    "rfqs.idcont",
                    "rfqs.nivelsolicitud",
                    "rfqs.fechasolicitud",
                    "rfqs.fechaentrega",
                    "rfqs.fechapropuesta",
                    "rfqs.fechaestimadaentrega",
                    "rfqs.observaciones",
                    "rfqs.deleted_at",
                    "clientes.razonSocial as razonSocial",
                    "sucursales.sucursal as sucursal",
                )
                ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")
                ->where('rfqs.idu', $sidu)
                ->where('rfqs.estatus', 'Entregado')
                ->orderBy('fechasolicitud', 'DESC')
                ->get();

            $cuantosrfq = count($consulta);
        }

        // return view('tablaEntregados', ['data' => $data])->with("cuantosrfq", $cuantosrfq);
        return view('tablaEntregados')->with("cuantosrfq", $cuantosrfq)->with("consulta", $consulta);
    }

    public function tablaCancelados (Request $request)
    {
        // $data = [];
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

        if ($stipo == 'Compras' || $sidu == 18 || $stipo == 'Administrador') {
            $consulta = rfqs::withTrashed()->join("usuarios", "rfqs.idu", "=", "usuarios.idu")->join("clientes", "rfqs.idclie", "=", "clientes.idc")
                ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
                ->select(
                    "rfqs.idrfq",
                    "rfqs.idu",
                    "rfqs.idclie",
                    "rfqs.idsuc",
                    "rfqs.ciudad",
                    "rfqs.estatus",
                    "rfqs.cmgrfq",
                    "rfqs.idcont",
                    "rfqs.nivelsolicitud",
                    "rfqs.fechasolicitud",
                    "rfqs.fechapropuesta",
                    "rfqs.fechaentrega",
                    "rfqs.fechaestimadaentrega",
                    "rfqs.observaciones",
                    "rfqs.deleted_at",
                    "clientes.razonSocial as razonSocial",
                    "sucursales.sucursal as sucursal",
                )
                ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")
                ->where('rfqs.estatus', 'Cancelado')
                ->orderBy('fechasolicitud', 'DESC')
                ->get();

            $cuantosrfq = count($consulta);
        } else if ($stipo == 'Vendedor') {
            $consulta = rfqs::withTrashed()
                ->join("usuarios", "rfqs.idu", "=", "usuarios.idu")
                ->join("clientes", "rfqs.idclie", "=", "clientes.idc")
                ->join("sucursales", "rfqs.idsuc", "=", "sucursales.idSucursal")
                ->select(
                    "rfqs.idrfq",
                    "rfqs.idu",
                    "rfqs.idclie",
                    "rfqs.idsuc",
                    "rfqs.ciudad",
                    "rfqs.estatus",
                    "rfqs.cmgrfq",
                    "rfqs.idcont",
                    "rfqs.nivelsolicitud",
                    "rfqs.fechasolicitud",
                    "rfqs.fechaentrega",
                    "rfqs.fechapropuesta",
                    "rfqs.fechaestimadaentrega",
                    "rfqs.observaciones",
                    "rfqs.deleted_at",
                    "clientes.razonSocial as razonSocial",
                    "sucursales.sucursal as sucursal",
                )
                ->selectRaw("concat(usuarios.nombreUsuario, ' ', usuarios.aPaterno, ' ', usuarios.aMaterno) as nombre")
                ->where('rfqs.idu', $sidu)
                ->where('rfqs.estatus', 'Cancelado')
                ->orderBy('fechasolicitud', 'DESC')
                ->get();

            $cuantosrfq = count($consulta);
        }

        // return view('tablaCancelados', ['data' => $data])->with("cuantosrfq", $cuantosrfq);
        return view('tablaCancelados')->with("cuantosrfq", $cuantosrfq)->with("consulta", $consulta);
    }

    public function modalDetalle(Request $request){
        $idrfq = $request->idrfq;
        $nombreTab = $request->nombreTab;

        $consultaPs = rfqsDetalle::where('idRfq', '=', $idrfq)
        ->join("tiporefacciones", "rfqsDetalle.idTipoRefa", "=", "tiporefacciones.idTipoRefacciones")
        ->join("partesVenta", "rfqsDetalle.idPartesVenta", "=", "partesVenta.idPartesVenta")
        ->select(
            "rfqsDetalle.idRfqDetalle",
            "rfqsDetalle.idRfq",
            "rfqsDetalle.cantidad",
            "rfqsDetalle.modelo",
            "rfqsDetalle.notas",
            "rfqsDetalle.tipo",
            "rfqsDetalle.tip",
            "rfqsDetalle.sku_generico",
            "rfqsDetalle.foto1",
            "rfqsDetalle.foto2",
            "rfqsDetalle.foto3",
            "rfqsDetalle.foto4",
            "rfqsDetalle.foto5",
            "tiporefacciones.nombreTipoRefaccion as tipoRefa",
        )
        ->selectRaw("concat(partesVenta.codigo, ' ', partesVenta.nombreRefaccion) as sku")
        ->selectRaw("concat(rfqsDetalle.sku_generico) as skugenerico")

        ->get();

    $cuantosPs = count($consultaPs);


    //dd($consultaPs);

    return view("modalDetalle")
    ->with('nombreTab', $nombreTab)
    ->with('idrfq', $idrfq)
    ->with('consultaPs', $consultaPs)
    ->with('cuantosPs', $cuantosPs);

        //return view('modalDetalle');
    }
}
