<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\anexGrid;
use DB;

class ejemploController extends Controller
{
    public function ejemploAnexgrid(){
        return view ('ejemploAnexgrid');
    }

    public function filtroEjemplo(){
        $anexGrid = new anexGrid();
        $order;
        $anexGrid->columna_orden != '' ? $order= $anexGrid->columna_orden : $order = 'ASC';

        $nombreRefaccion = '';
        $marcaRefaccion = '';
        $nombreTipoRefaccion = '';
        $presentacion = '';
        $estatus = '';

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
                if($f['columna'] == 'presentacion' && $f['valor'] != ''){
                    $presentacion = $f['valor'];
                }
                if($f['columna'] == 'estatus' && $f['valor'] != ''){
                    $estatus = $f['valor'];
                }
            }


        $registros = DB::table('partesVenta')            
                ->select('idPartesVenta','nombreRefaccion','marcaRefaccion','nombreTipoRefaccion', 'partesVenta.activo','presentacion','estatus')
                ->leftJoin('marcasRefaccion', 'partesVenta.idMarcaRefa', '=', 'marcasRefaccion.idMarcaRefa')  
                ->leftJoin('tiporefacciones', 'partesVenta.idTipoRefacciones', '=', 'tiporefacciones.idTipoRefacciones')  
                ->where('nombreRefaccion','like','%'.$nombreRefaccion.'%')
                ->where('marcaRefaccion','like','%'.$marcaRefaccion.'%')
                ->where('nombreTipoRefaccion','like','%'.$nombreTipoRefaccion.'%')
                ->where('presentacion','like','%'.$presentacion.'%')
                ->where('estatus','like','%'.$estatus.'%')
                ->orderBy($anexGrid->columna, $order)
                ->skip($anexGrid->pagina)
                ->take($anexGrid->limite)
                ->get();
        
        $total = DB::table('partesVenta')            
                ->select('idPartesVenta','nombreRefaccion','marcaRefaccion','nombreTipoRefaccion','partesVenta.activo','presentacion','estatus')
                ->leftJoin('marcasRefaccion', 'partesVenta.idMarcaRefa', '=', 'marcasRefaccion.idMarcaRefa')  
                ->leftJoin('tiporefacciones', 'partesVenta.idTipoRefacciones', '=', 'tiporefacciones.idTipoRefacciones')  
                ->where('nombreRefaccion','like','%'.$nombreRefaccion.'%')
                ->where('marcaRefaccion','like','%'.$marcaRefaccion.'%')
                ->where('nombreTipoRefaccion','like','%'.$nombreTipoRefaccion.'%')
                ->where('presentacion','like','%'.$presentacion.'%')
                ->where('estatus','like','%'.$estatus.'%')
                ->count();

        $data = array(
            'total' => $total,
            'data' => $registros
        );
        return response()->json($data, 200);
    }

    public function reporteZing(){
        $data = \DB::select("SELECT e.idEquipos, e.folioRecepcion, e.vistaSuperior,e.vistaFrente,e.placa_1, e.idu,e.textoQr,e.estatus,e.estatusEntrega, e.importancia, SUBSTR(datosCertificado,1,100) as datos,
            COUNT(partesreparacion.`idParte`) AS canti,
            CONCAT (e.GCMid, '-', e.complementoGCMid)AS gcmid,
            CONCAT(c.razonSocial,'-',s.sucursal)AS cliSuc,e.personaEntrega,
            CONCAT (e.serie,'-',te.tipoEquipo,'-',se.subtipoEquipo) AS equipo,
            CONCAT (e.marca,'-',e.modelo) AS marcaModelo, DATE_FORMAT(e.fechaRegistro,'%d %b %Y') AS fecha, e.activo
            FROM recepcionEquipos AS e
            INNER JOIN sucursales AS s ON s.idSucursal= e.idSucursal
            INNER JOIN clientes AS c ON c.idc= e.idc
            INNER JOIN tipoequipos AS te ON te.idTipoEquipo= e.idTipoEquipo
            INNER JOIN subtipoequipos AS se ON se.idSubtipoEquipo= e.idSubtipoEquipo
            LEFT JOIN partesreparacion ON partesreparacion.`idEquipos`= e.`idEquipos`
            GROUP BY e.idEquipos, e.folioRecepcion, e.vistaSuperior,e.vistaFrente,e.placa_1, e.idu,e.textoQr,e.estatus,e.importancia,datos,gcmid,
            cliSuc,e.personaEntrega,equipo,marcaModelo,fecha, e.activo
            ORDER BY e.folioRecepcion DESC");
        // $data = DB::select("select * from partesVenta");
        return view('reporteZing',compact('data'));
        // return $data;
    }
}
