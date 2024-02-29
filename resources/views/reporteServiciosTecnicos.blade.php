@extends('principal')
@section('contenido')


<div class="col-md-12">
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1 class="">Reporte de servicios</h1>
        </div><br>
        <div class="panel-body">

            <div class="panel-body">
                <div class="panel-heading"><a href="{{asset('altaServicioTecnico')}}"><button type="button"
                            class="btn btn-primary ">Agregar nuevo servicio</button></a></div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Nombre</th>
                                <th>Detalle</th>
                                <th>Unidad de medida</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($consulta as $st)
                            <tr>
                                <td>{{$st->claveServicio}}</td>
                                <td>{{$st->servicioTecnico}}</td>
                                <td>{{$st->detalleServicio}}</td>
                                <td>{{$st->unidadMedida}}</td>
                                <td>@if($st->activo=='si')
                                    <a href="{{URL::action('serviciosTecnicosController@eliminarServicioTecnico',['idServicioTecnico'=>$st->idServicioTecnico])}}"
                                        type="submit" class="btn btn-xs btn-danger"><i
                                            class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
                                    </a> <br>
                                    <a href="{{URL::action('serviciosTecnicosController@modificarServicioTecnico',['idServicioTecnico'=>$st->idServicioTecnico])}}"
                                        class="btn btn-xs btn-info" style="width:67px; margin-top:5px">
                                        <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
                                    </a>
                                    @else
                                    <a href="{{URL::action('serviciosTecnicosController@restaurarServicioTecnico',['idServicioTecnico'=>$st->idServicioTecnico])}}"
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
</div>
@stop