@extends('principal')
@section('contenido')
<div class="col-md-12">
    <!-- Advanced Tables -->
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1>Reporte de servicios de taller</h1>
        </div>
        <div class="panel-body">
            <a href="{{asset('altaServTaller')}}"><button type="button" class="btn btn-primary ">Agregar nuevo
                    servicio</button></a>
            <br><br>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Nombre del servicio</th>
                            <th>Alcance</th>
                            
                            @if($stipo=='Administrador')
                            <th>Costo interno</th>
                            <th>Costo externo</th>
                            @endif
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consulta as $tr)
                        <tr>
                            <td>{{$tr->nombreServTaller}}</td>
                            <td>{{$tr->alcance}}</td>
                            
                            @if($stipo=='Administrador')
                            <td>{{$tr->costoInterno}}</td>
                            <td>{{$tr->costoExterno}}</td>
                            @endif
                            <td>@if($tr->activo=='Si')
                                <a href="{{URL::action('servicioTallerController@eliminarServTaller',['idServicioTaller'=>$tr->idServicioTaller])}}"
                                    type="submit" class="btn btn-xs btn-danger"><i
                                        class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
                                </a> <br>

                                <a href="{{URL::action('servicioTallerController@modificarServTaller',['idServicioTaller'=>$tr->idServicioTaller])}}"
                                    class="btn btn-xs btn-info" style="width:67px; margin-top:5px">
                                    <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
                                </a>
                                @else
                                <a href="{{URL::action('servicioTallerController@restaurarServTaller',['idServicioTaller'=>$tr->idServicioTaller])}}"
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