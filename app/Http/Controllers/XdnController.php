<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use App\Exports\XdnExport;
use Maatwebsite\Excel\Facades\Excel;

use App\usuarios as User;

class XdnController extends Controller
{
    public function index()
    {
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');
        $consultaEmpresa = User::select('empresaSeguimiento')->find($sidu);

        if( $consultaEmpresa->empresaSeguimiento === "GCMyCYM" )
        {
            $usuario = User::orderby( 'nombreUsuario', 'asc' )->get();

            $query = DB::table( 'recepcionEquipos as r' )
                        ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                        ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                        ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                        ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                        ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                        ->select(
                            'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                            'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega',
                            DB::raw(' SUBSTR( r.datosCertificado, 1, 100) as datos '),
                            DB::raw(' COUNT( p.idParte ) as canti '),
                            DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                            DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                            DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                            DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '), 
                            DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                        )
                        ->groupBy(
                                    'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 
                                    'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                    'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                    'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                        )
                        ->orderBy( 'r.folioRecepcion', 'DESC' )
                        ->get();

            $consulta = $this->json($query);

            $clientes = DB::table( 'recepcionEquipos as r' )
                        ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                        ->select(
                            'r.idc', 'c.razonSocial'
                        )
                        ->groupBy( 'r.idc' )
                        ->orderBy( 'c.razonSocial', 'ASC' )
                        ->get();

            return view( 'XDN.brs', compact( 'usuario', 'consulta', 'clientes' ) );

        }
        
        $usuario = User::orderBy('nombreUsuario','asc')->get();

        $empresa = User::select( 'empresaSeguimiento' )->find( $sidu );

        $query = DB::table( 'recepcionEquipos as r' )
                        ->join( 'sucursales as s', 's.idSucursal', '=', 'r.idSucursal' )
                        ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                        ->join( 'tipoequipos as t', 't.idTipoEquipo', '=', 'r.idTipoEquipo'  )
                        ->join( 'subtipoequipos as su', 'su.idSubtipoEquipo', '=', 'r.idSubtipoEquipo' )
                        ->leftJoin( 'partesreparacion as p', 'p.idEquipos', '=', 'r.idEquipos' )
                        ->select(
                            'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 'r.vistaFrente', 'r.placa_1', 'r.activo',
                            'r.idu', 'r.textoQr', 'r.estatus' , 'r.estatusEntrega', 'r.importancia', 'r.personaEntrega',
                            DB::raw(' SUBSTR( r.datosCertificado, 1, 100) as datos '),
                            DB::raw(' COUNT( p.idParte ) as canti '),
                            DB::raw(' CONCAT( r.GCMid, "-", r.complementoGCMid ) as gcmid '),
                            DB::raw(' CONCAT( c.razonSocial, "-", s.sucursal ) as cliSuc '),
                            DB::raw(' CONCAT( r.serie, "-", t.tipoEquipo, "-", su.subtipoEquipo ) AS equipo '),
                            DB::raw(' CONCAT( r.marca, "-", r.modelo ) AS marcaModelo '), 
                            DB::raw(' DATE_FORMAT( r.fechaRegistro,"%d %b %Y" ) as fecha'),
                        )
                        ->whereIn( 'r.nombreEmpresa', [$empresa->empresaSeguimiento] )
                        ->groupBy(
                                    'r.idEquipos', 'r.folioRecepcion', 'r.vistaSuperior', 
                                    'r.vistaFrente', 'r.placa_1', 'r.idu', 'r.textoQr',
                                    'r.estatus', 'r.importancia', 'datos', 'gcmid',
                                    'cliSuc', 'r.personaEntrega', 'equipo', 'marcaModelo', 'fecha', 'r.activo'
                        )
                        ->orderBy( 'r.folioRecepcion', 'DESC' )
                        ->get();

            $consulta =  $this->json( $query ); // Json Estructura

            $clientes = DB::table( 'recepcionEquipos as r' )
                        ->join( 'clientes as c', 'c.idc', '=', 'r.idc' )
                        ->select(
                            'r.idc', 'c.razonSocial'
                        )
                        ->groupBy( 'r.idc' )
                        ->orderBy( 'c.razonSocial', 'ASC' )
                        ->get();

            return view( 'XDN.brs', compact( 'usuario', 'consulta', 'clientes' ) );

    }

    public function json ( $query )
    {
        function val ( $value )
        {

            $val = ( $value == 'Sin archivo' || $value == null || $value == '' ) ? '' : asset( 'public/archivos/'.$value );

            $image = ( \File::exists('public/archivos/'.$value) ) ? str_replace(' ', '%20', $val) : '';
            
            return $image;
        }

        function import( $value )
        {
            $alta = asset('public/archivos/circulo-rojo-png-4.png');
            $media = asset('public/archivos/circulo-amarillo.png');
            $baja = asset('public/archivos/archivos/circulo-verde.png');

            if( $value == 'Alta' ) 
            {
                $val = $alta;
            }
            if( $value == 'Media' )
            {
                $val = $media;
            }
            if( $value == 'Baja' )
            {
                $val = $baja;
            }

            return $val;
        }

        function options( $value, $value2 )
        {

            $btn = "<a href=".url("/restaurarEquipos/{$value2}")." type='submit' class='btn btn-xs btn-success'>Restaurar</a>";

            if( $value === 'Si' )
            {
                $btn = "<a href='".url("/dividirEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-warnin'><span class='glyphicon glyphicon-wrench'></span> Dividir en partes</a><br>".
                       "<a href='".url("/eliminarEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-danger'><i class='ace-icon fa fa-trash-o bigger-120'> Eliminar</i></a><br>".
                       "<a href='".url("/modificarEquipos/{$value2}")."' type='submit' class='btn btn-xs btn-info'><i class='ace-icon fa fa-pencil bigger-120'> Editar</i></a>";
            }

            return $btn;
        }


        $consult = array();

        foreach( $query as $value )
        {
	        array_push( $consult, array(
		        'id'    => $value->idEquipos,
                'a'     => val( $value->vistaSuperior ),
                'b'     => val( $value->vistaFrente ),
                'c'     => val( $value->placa_1 ),
                'd'     => $value->folioRecepcion,
                'e'     => $value->gcmid,
                'f'     => $value->fecha,
                'g'     => $value->cliSuc,
                'h'     => $value->datos,
                'i'     => $value->equipo,
                'j'     => $value->marcaModelo,
                'k'     => import( $value->importancia ),
                'l'     => $value->estatus,
                'm'     => $value->estatusEntrega,
                'n'     => $value->canti,
                'o'     => options( $value->activo, $value->idEquipos ),
            ) );

        }

        return $consult;
    }

    public function excel ( Request $request )
    {
        return Excel::download( new XdnExport( $request->data, $request->user ), 'reporte.xlsx' );
    }

}
