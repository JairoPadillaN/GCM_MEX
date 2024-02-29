@extends('principal')
@section('contenido')
<div class="col-md-12">
    <!-- Advanced Tables -->
    <div class="panel panel-default" style="margin-top:-55px">
        <h1>Reporte de áreas</h1><br><br>
        <div class="panel-heading"><a href="{{asset('altaAreas')}}"><button type="button"
                    class="btn btn-primary ">Agregar nueva área</button></a></div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>

                            <th>Nombre del área</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consulta as $area)
                        <tr>

                            <td>{{$area->nombreArea}}</td>

                            <td>@if($area->activo=='Si')
                                <a href="{{URL::action('areasController@eliminarAreas',['idArea'=>$area->idArea])}}"
                                    type="submit" class="btn btn-xs btn-danger"><i
                                        class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
                                </a> <br>

                                <a href="{{URL::action('areasController@modificarAreas',['idArea'=>$area->idArea])}}"
                                    class="btn btn-xs btn-info" style="width:67px; margin-top:5px">
                                    <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
                                </a>
                                @else
                                <a href="{{URL::action('areasController@restaurarAreas',['idArea'=>$area->idArea])}}"
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