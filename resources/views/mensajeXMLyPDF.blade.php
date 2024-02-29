@extends('principalProv')
@section('contenidoProv')
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">

        <div class="panel-body">        
            <center>            
                @if($tipoMensaje == "ok")
                    <div class="alert alert-success" role="alert">
                        <h4>    
                            {{$mensaje}}
                        <h4>
                        <h4>{{$fc}}</h4>
                        <h2><span class="glyphicon glyphicon-ok" style="color:#3c763d"></span></h2>
                        <br>
                        <td>
                            <a href="{{asset('inicioProveedor')}}">
                                <button type="button" class="btn btn-success">Reporte de Ordenes de Compra</button>
                            </a>
                        </td>
                    </div>
                @else
                    <div class="alert alert-danger" role="alert">
                        <h4>    
                            @foreach($mensaje as $msj)
                                <div style="text-align:left">{{$msj}}</div>
                            @endforeach
                        <h4>
                        <h2><span class="glyphicon glyphicon-remove" style="color:#BB1B16"></span></h2>
                        <br>
                        <td>
                            @if($tipoMensaje == "noAceptada")
                                <a href="{{asset('inicioProveedor')}}">
                                    <button type="button" class="btn btn-danger">Reporte de Ordenes de Compra</button>
                                </a>                            
                            @else
                                <?php $parameter= Crypt::encrypt($idOrden); ?>
                                <a href="{{URL::action('proveedoresController@subirArProv',[$parameter])}}">                            
                                    <button type="button" class="btn btn-danger">Agregar nuevos archivos</button>
                                </a>
                            @endif
                        </td>
                    </div>
                @endif
            </center>            
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $(".cargar").click();
});
</script>

@stop