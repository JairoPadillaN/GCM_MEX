@extends('principal')
@section('contenido')


<div class="col-md-12">
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1 class="">Reporte de Facturas</h1>
        </div><br>
        <div class="panel-body">

            <div class="panel-body">
                <div class="panel-heading"><a href="{{asset('altaAlcances')}}"><button type="button"
                            class="btn btn-primary ">Agregar nuevo alcance</button></a></div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Nombre del alcance</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($consulta as $a)
                            <tr>
                                <td>{{$a->alcance}}</td>
                                <td>@if($a->activo=='si')
                                    <a href="{{URL::action('alcancesController@eliminarAlcances',['idAlcance'=>$a->idAlcance])}}"
                                        type="submit" class="btn btn-xs btn-danger"><i
                                            class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
                                    </a> <br>
                                    <a href="{{URL::action('alcancesController@modificarAlcances',['idAlcance'=>$a->idAlcance])}}"
                                        class="btn btn-xs btn-info" style="width:67px; margin-top:5px">
                                        <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
                                    </a>
                                    @else
                                    <a href="{{URL::action('alcancesController@restaurarAlcances',['idAlcance'=>$a->idAlcance])}}"
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