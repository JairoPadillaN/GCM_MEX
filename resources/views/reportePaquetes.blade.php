@extends('principal')
@section('contenido')

<div class="col-md-12">
    <!-- Advanced Tables -->
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1>Reporte de paquetes</h1><br>
            <a href="{{asset('altaPaquetes')}}"><button type="button" class="btn btn-primary ">Agregar nuevo
                    paquete</button></a>
            <br>
        </div>

        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Nombre del paquete</th>
                            <th>Valor de venta pesos (MXN) </th>
                            <th>Valor de venta dólares (USD)</th>
                            <th>Número de partes / servicios </th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($consulta as $p)
                        <tr>
                            <td>{{$p->nombrePaquete}}</td>
                            
                               
                                    <td style="text-align:right;">@foreach($monto as $m)
                                            @if($m->idPaquete == $p->idPaquete && $m->pesos != "")
                                                MXN $ {{$m->pesos}} 
                                            @endif
                                        @endforeach
                                    </td>
                                    <td style="text-align:right;">@foreach($monto as $m)
                                             @if($m->idPaquete == $p->idPaquete && $m->dolares != "")
                                               USD $ {{$m->dolares}} 
                                             @endif
                                        @endforeach
                                    </td>
                                    <td style="text-align:right;">@foreach($monto as $m)
                                             @if($m->idPaquete == $p->idPaquete)
                                                 {{$m->canti}}
                                             @endif
                                        @endforeach
                                    </td>

                            <td>@if($p->activo=='Si')
                                <a href="{{URL::action('paquetesController@agregarPartePaquete',['idPaquete'=>$p->idPaquete])}}"
                                    type="submit" class="btn btn-xs btn-warning"><i
                                        class="ace-icon fa fa-book bigger-120"> Agregar al paquete</i>
                                </a> <br>

                                <a href="{{URL::action('paquetesController@eliminarPaquetes',['idPaquete'=>$p->idPaquete])}}"
                                    type="submit" class="btn btn-xs btn-danger"><i
                                        class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
                                </a> 

                                <a href="{{URL::action('paquetesController@modificarPaquetes',['idPaquete'=>$p->idPaquete])}}"
                                    class="btn btn-xs btn-info" style="width:67px; margin-top:5px">
                                    <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
                                </a>
                                @else
                                <a href="{{URL::action('paquetesController@restaurarPaquetes',['idPaquete'=>$p->idPaquete])}}"
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