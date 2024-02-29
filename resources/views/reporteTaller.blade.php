@extends('principal')
@section('contenido')

<div class="col-md-12">
    <!-- Advanced Tables -->
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1>Reporte de talleres</h1>
        </div><br>

        <div class="panel-body">
            <a href="{{asset('altaTaller')}}"><button type="button" class="btn btn-primary ">Agregar nuevo
                    taller</button></a>
            <br><br>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Nombre del taller</th>
                            <th>Tipo de taller</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consulta as $t)
                        <tr>
                            <td>{{$t->nombreTaller}}</td>
                            <td>{{$t->tipoTaller}}</td>
                            <td>@if($t->activo=='Si')
                                <a href="{{URL::action('talleresController@eliminarTaller',['idTaller'=>$t->idTaller])}}"
                                    type="submit" class="btn btn-xs btn-danger"><i
                                        class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
                                </a> <br>

                                <a href="{{URL::action('talleresController@modificarTaller',['idTaller'=>$t->idTaller])}}"
                                    class="btn btn-xs btn-info" style="width:67px; margin-top:5px">
                                    <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
                                </a>
                                @else
                                <a href="{{URL::action('talleresController@restaurarTaller',['idTaller'=>$t->idTaller])}}"
                                    class="btn btn-xs btn-warning" style="width:67px">
                                    Restaurar</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--End Advanced Tables -->
</div>
@stop