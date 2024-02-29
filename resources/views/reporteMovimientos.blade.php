@extends('principal')
@section('contenido')

<div class="col-md-12">
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1>Reporte de Movimientos</h1> <br><br>

            <a href="{{asset('altaMovimientos')}}"><button type="button" class="btn btn-primary ">Agregar nuevo
                    movimiento</button></a>
        </div>


        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <!---<th>Id</th>--->
                            <th>Fecha</th>

                            <th>Tipo de gasto</th>
                            <th>Empleado</th>
                            <!---<th>Razon</th>--->
                            <!--<th>Cliente</th> -->
                            <th>Monto</th>
                            <!--	<th>Detalle</th> -->
                            <!---<th>Activo</th>--->
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consulta as $c)
                        <tr>
                            <!---<td>{{$c->idm}}</td>--->
                            <td>{{$c->fecha}}</td>
                            <!--<td>{{$c->tipo}}</td>-->
                            <td>{{$c->tipoGasto}}</td>
                            <!---<td>{{$c->us}}</td>--->
                            <!---<td>{{$c->razon}}</td>--->
                            <td>{{$c->us}}</td>
                            <td>{{$c->monto}}</td>
                            <!--  <td>{{$c->detalle}}</td>-->
                            <!---<td>{{$c->activo}}</td>-->

                            <td>@if($c->activo=='si')
                                <a href="{{URL::action('MovimientosController@eliminarMovimientos',['idm'=>$c->idm])}}"
                                    type="submit" class="btn btn-xs btn-danger"><i
                                        class="ace-icon fa fa-trash-o bigger-120">Eliminar</i>
                                </a>

                                <a href="{{URL::action('MovimientosController@modificarMovimientos',['idm'=>$c->idm])}}"
                                    class="btn btn-xs btn-info" style="width:67px; margin-top:5px">
                                    <i class="ace-icon fa fa-pencil bigger-120">Editar</i>
                                </a>
                                @else
                                <a href="{{URL::action('MovimientosController@restaurarMovimientos',['idm'=>$c->idm])}}"
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
</div>
@stop