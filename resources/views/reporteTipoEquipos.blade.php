@extends('principal')
@section('contenido')

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1>Reporte tipos de equipos</h1> <br>
			<a href="{{asset('altaTipoEquipos')}}"><button type="button" class="btn btn-primary">Agregar nuevo
                    tipo</button></a>
        </div>

        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Tipo de equipo</th>
                            <th><center>Opciones</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consulta as $t)
                        <tr>
                            <td>{{$t->tipoEquipo}}</td>
                            <td align="center">@if($t->activo=='si')
                                <a href="{{URL::action('TipoEquiposController@eliminarTipoEquipos',['idTipoEquipo'=>$t->idTipoEquipo])}}"
                                    type="submit" class="btn btn-xs btn-danger"><i
                                        class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
                                </a>

                                <a href="{{URL::action('TipoEquiposController@modificarTipoEquipos',['idTipoEquipo'=>$t->idTipoEquipo])}}"
                                    class="btn btn-xs btn-info">
                                    <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
                                </a>
                                @else
                                <a href="{{URL::action('TipoEquiposController@restaurarTipoEquipos',['idTipoEquipo'=>$t->idTipoEquipo])}}"
                                    class="btn btn-xs btn-warning"> Restaurar</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop