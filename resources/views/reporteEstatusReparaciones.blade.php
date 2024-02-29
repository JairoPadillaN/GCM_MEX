@extends('principal')
@section('contenido')



<div class="col-md-12">
    <!-- Advanced Tables -->
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1>Reporte de estatus de reparación</h1>
        </div>
        <div class="panel-body">
            <a href="{{asset('altaEstatusReparaciones')}}"><button type="button" class="btn btn-primary ">Agregar nuevo
                    estatus de reparación</button></a>
            <br><br>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Nombre del estatus</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consulta as $e)
                        <tr>
                            <td>{{$e->nombreEstatus}}</td>
                            <td>@if($e->activo=='Si')
                                <a href="{{URL::action('estatusReparacionesController@eliminarEstatus',['idEstatus'=>$e->idEstatus])}}"
                                    type="submit" class="btn btn-xs btn-danger"><i
                                        class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
                                </a> <br>

                                <a href="{{URL::action('estatusReparacionesController@modificarEstatus',['idEstatus'=>$e->idEstatus])}}"
                                    class="btn btn-xs btn-info" style="width:67px; margin-top:5px">
                                    <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
                                </a>
                                @else
                                <a href="{{URL::action('estatusReparacionesController@restaurarEstatus',['idEstatus'=>$e->idEstatus])}}"
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