@extends('principal')
@section('contenido')

<div class="header">
    <h1 class="page-header">Reporte de Tipos de gastos</h1>
</div>
<div id="page-inner">

    <div class="row">
        <div class="col-md-5">
            <a href="{{asset('AltaTipoGastos')}}"><button type="button" class="btn btn-primary">Agregar nuevo tipo de
                    gasto</button></a>
        </div>
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default">
                <div class="panel-heading">Tipos de gastos registrados</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre del gasto</th>
                                    <th>Tipo de gasto</th>
                                    <!--th>Activo</th-->
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consulta as $c)
                                <tr>
                                    <td class="sorting_1">{{$c->idTipoGastos}}</td>
                                    <td class="sorting_1">{{$c->nombreTipoGasto}}</td>
                                    <td class="sorting_1">{{$c->tipo}}</td>
                                    <!--td class="sorting_1">{{$c->activo}}</td-->
                                    <td>@if($c->activo=='Si')
                                        <a href="{{URL::action('TipoGastosController@eliminarTipoGastos',['idTipoGastos'=>$c->idTipoGastos])}}"
                                            class="btn btn-xs btn-danger"><i class="ace-icon fa fa-trash-o bigger-120">
                                                Eliminar</i></a>
                                        <a href="{{URL::action('TipoGastosController@modificarTipoGastos',['idTipoGastos'=>$c->idTipoGastos])}}"
                                            class="btn btn-xs btn-info" style="width:67px;"><i
                                                class="ace-icon fa fa-pencil bigger-120"> Editar</i></a>
                                        @else
                                        <a href="{{URL::action('TipoGastosController@restaurarTipoGastos',['idTipoGastos'=>$c->idTipoGastos])}}"
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
</div>
@stop