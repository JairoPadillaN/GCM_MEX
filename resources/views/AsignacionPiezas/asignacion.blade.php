@extends('principal')
@section('contenido')
<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h2>Asignación de piezas</h2>
        </div>
        
        <div class="panel-body">
            <div class="form-group col-md-6">
                <div class="sub-title">A que servicio se asignaran las piezas</div>
                <div>
                    <select name="idFactura" id="idFactura" class="form-control rounded-0">
                        <option value="">Selecciona Factura/Cliente</option>    
                        @foreach($consultaFacturas as $conFac)
                            <option value="{{$conFac->idFactura}}">{{$conFac->numeroFactura}} - {{$conFac->razonSocial}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-group col-md-12">
                <div id="infoCotizacion"></div>
            </div>

        </div><!-- //final body -->
        
    </div>
</div>

<script>
$(document).ready(function(){
    $("#idFactura").change(function(){
        $("#infoCotizacion").load('{{url('informacionCotizacion')}}' + '?r=' + Date.now() + '&idFactura=' + $("#idFactura").val());
        // $("#reporteRefacciones").load('{{url('cancelarEntrada')}}' + '?r=' + Date.now() + '&idEntrada=' + $("#idEntrada").val());
    });
});
</script>
@stop