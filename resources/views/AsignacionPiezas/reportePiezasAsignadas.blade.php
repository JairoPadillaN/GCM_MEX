
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr style="background-color: #D5F5E3;">                                
            <th><font SIZE=2>SKU</font></th>
            <th><font SIZE=2>Nombre</font></th>
            <th><font SIZE=2>Serial/Modelo</font></th>
            <th><font SIZE=2>Cantidad</font></th>
            <th><font SIZE=2>Unidad de medida</font></th>
            <th><font SIZE=2>Factura Proveedor</font></th>
            <th><font SIZE=2>Proveedor</font></th>
            <th><font SIZE=2>Fecha Compra</font></th> 
            <th><font SIZE=2>Facturado a Servicio</font></th> 
            <th><font SIZE=2>Opciones</font></th> 
        </tr>
        </thead>
        <tbody>
        @foreach($consultaPiezas as $piezas)
        <tr>
            <td>{{$piezas->codigoRefaccion}}</td>
            <td>{{$piezas->nombreRefaccion}}</td>
            <td>{{$piezas->serie}}</td>
            <td>{{$piezas->cantidadOcupada}}</td>
            <td>{{$piezas->presentacion}}</td>
            <td>{{$piezas->numeroFacturaProv}}</td>
            <td>{{$piezas->razonSocialProv}}</td>
            <td>{{$piezas->fechaCompra}}</td>
            <td>{{$piezas->numeroFactura}}</td>
            <td>
                <form action="">
                    @csrf
                    <input type="hidden" value="{{$piezas->idPiezaAsig}}" name="idPiezaAsig">
                    <input type="hidden" value="{{$piezas->idRefaccion}}" name="idRefaccion">
                    <input type="hidden" value="{{$idParte}}" name="idParte">
                    <input type="hidden" value="{{$piezas->cantidadOcupada}}" name="cantidadEliminar">
                    <input type="hidden" value="{{$idRepCotizacion}}" name="idRepCotizacion">
                    <button type="button" class="btn btn-sm btn-danger borrarPieza" style='width:40px; height: 35px;'>
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<script>
$(document).ready(function(){
    $(".borrarPieza").click(function(){
        // alert("borrando");
        var idParte = $("#idParte").val();
        var idRepCotizacion = $("#idRepCotizacion").val();
        $("#reporte").load('{{url('borrarAsignacion')}}' + '?r=' + Date.now() + $(this).closest('form').serialize()); 
        $("#idRefaccion").load('{{url('comboCodigoPiezas')}}' + '?r=' + Date.now() + '&idParte=' + idParte);
        $("#cambio").load('{{url('cambiarBoton')}}' + '?r=' + Date.now() + '&idRepCotizacion=' + idRepCotizacion);
        $("#idPendiente").load('{{url('cambiarPendiente')}}' + '?r=' + Date.now() + '&idRepCotizacion=' + idRepCotizacion);
    });
});
</script>