@extends('principal')
@section('contenido')

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1>Reporte subtipos de equipos</h1> <br>
			<a href="{{asset('altaSubtipoEquipos')}}"><button type="button" class="btn btn-primary">Agregar nuevo
                    subtipo</button></a>
        </div>

        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Tipo de equipo</th>
                            <th>Subtipo de equipo</th>
                            <th><center>Opciones</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consulta as $s)
                        <tr>
                            <td>{{$s->tip}}</td>
                            <td>{{$s->subtipoEquipo}}</td>
                            @if(Session::get('sesiontipo')=="Administrador")
                                <td>@if($s->activo=='si')
                                    <a href="{{URL::action('SubtipoEquiposController@eliminarSubtipoEquipos',['idSubtipoEquipo'=>$s->idSubtipoEquipo])}}"
                                        type="submit" class="btn btn-xs btn-danger"><i
                                            class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
                                    </a>

                                    <a href="{{URL::action('SubtipoEquiposController@modificarSubtipoEquipos',['idSubtipoEquipo'=>$s->idSubtipoEquipo])}}"
                                        class="btn btn-xs btn-info">
                                        <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
                                    </a>
                                    @else
                                    <a href="{{URL::action('SubtipoEquiposController@restaurarSubtipoEquipos',['idSubtipoEquipo'=>$s->idSubtipoEquipo])}}"
                                        class="btn btn-xs btn-warning"> Restaurar</a>
                                    @endif
                                </td>
                            @else
                                <td align="center">
                                    @if($s->activo=='si')
                                        <a href="{{URL::action('SubtipoEquiposController@modificarSubtipoEquipos',['idSubtipoEquipo'=>$s->idSubtipoEquipo])}}"
                                            class="btn btn-xs btn-info">
                                            <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
                                        </a>
                                    @endif
                                </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop