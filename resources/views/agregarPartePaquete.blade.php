@extends('principal')
@section('contenido')
<!-- jjjjjjjjjjjjjjjjjjjjjjjjjjj -->
<script type="text/javascript">
$(document).ready(function() {
    $("#agrega").click(function() {
        // alert("hola");
        $("#agregarPaq").load('{{url('altaAgregarPaq')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
    });
});
</script>

<script type="text/javascript">
$(function() {
    $('.borrarPartePaq').click(
        function() {
            // alert("borrar");
            $("#reporte").load('{{url('borrarPartePaq2')}}' + '?' + $(this).closest('form').serialize());
        }
    );
});
</script>

<form method='POST'>
    <div class="col-xs-12" style="margin-top:-55px">
        <div class="panel panel-default" >
            <div class="panel-heading">
                <h1>Agregar al paquete</h1><br>
            </div>

            <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="sub-title"> Paquete:</div>
                    
                    {{Form::text('nombrePaquete',$consultaPaquetes->nombrePaquete,['class' => 'form-control','readonly'])}}
                    {{Form::hidden('idPaquete',$consultaPaquetes->idPaquete,['class' => 'form-control','readonly'])}}
                </div> 
            </div>           
                <br>
                <div class="" align="right">
                     <button type="button" class="btn btn-success btn-default" id="agrega">
                        <span class="glyphicon glyphicon-plus-sign"></span>
                    </button>
                    <a href="{{asset('reportePaquetes')}}">
                        <button type="button" class="btn btn-default">Regresar</button>
                    </a>
                </div>
                <br>
                <div id='agregarPaq'>
                    @if($cuantosPaq < 1) <div class="alert alert-warning" role="alert" align="center">
                        <label for="">No tiene nada agregado este paquete.</label>
                </div>
                @else
                <div class="table-responsive" id="reporte">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr style=" background-color: #C5EBFB;">
                                <th>SKU</th>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Tipo</th>
                                <th>Costo Venta Pesos</th>
                                <th>Costo Venta Dólares</th>
                                <th>SKU en cotización</th>
                                <th>
                                    <center>Operaciones</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($consultaDetPaq as $cdp)
            <tr>
                <td>{{$cdp->codigo}}</td>
                <td>{{$cdp->nombreRefaccion}}</td>
                <td>{{$cdp->marcaRefaccion}}</td>
                <td>{{$cdp->nombreTipoRefaccion}}</td>
                <td style="text-align:right;">MXN ${{$cdp->precioVentaPe}}</td>
                <td style="text-align:right;">USD ${{$cdp->precioVentaDol}}</td>
                <td style="text-align:right;">{{$cdp->apareceCoti}}</td>
                <td>
                    <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                        name='frmdo{{$cdp->idDetallePaquete}}' id='frmdo{{$cdp->idDetallePaquete}}' target='_self'>
                        <input type='hidden' value='{{$cdp->idDetallePaquete}}' name='idDetallePaquete' id='idDetallePaquete'>
                        <input type='hidden' value='{{$cdp->idPaquete}}' name='idPaquete' id='idPaquete'>
                        <button type="button" class="btn btn-sm btn-danger borrarPartePaq">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
                            
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
    </div>
</form>
@stop