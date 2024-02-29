@extends('principal')
@section('contenido')

<div class="col-md-12">
    <!-- Advanced Tables -->
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1>Reporte de tipos de refacción</h1>
        </div><br>
        <div class="panel-body">
            <a href="{{asset('altaTipoRef')}}"><button type="button" class="btn btn-primary ">Agregar nuevo tipo
                    de refacción</button></a>
            <br><br>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>

                            <th>Marca</th>
                            <th>Nombre del tipo de refacción</th>
                            <th width='300px;'>Código</th>
                            <th width='300px;'>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consulta as $tr)
                        <tr>
                            <td>{{$tr->marcaRefaccion}}</td>
                            <td>{{$tr->nombreTipoRefaccion}}</td>
                            <td>{{$tr->codigoTipoRef}}</td>
                            

                            <td align='center'>@if($tr->activo=='Si')
                                <a href="{{URL::action('tipoRefController@eliminarTipoRef',['idTipoRefacciones'=>$tr->idTipoRefacciones])}}"
                                    type="submit" class="btn btn-xs btn-danger"><i
                                        class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
                                </a>

                                <a href="{{URL::action('tipoRefController@modificarTipoRef',['idTipoRefacciones'=>$tr->idTipoRefacciones])}}"
                                    class="btn btn-xs btn-info" style="width:67px; margin-top:5px">
                                    <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
                                </a>
                                @else
                                <a href="{{URL::action('tipoRefController@restaurarTipoRef',['idTipoRefacciones'=>$tr->idTipoRefacciones])}}"
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