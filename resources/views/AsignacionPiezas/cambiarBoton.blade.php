@if($consultaSolicitada->tipoCotizacion!='reparacion')
    <div id="cambio">
        @if($consultaSolicitada->piezas != $consultaSolicitada->piezasOcupadas)
            <button type="button" id="guardarPieza2" class="btn btn-success">
                <span class="glyphicon glyphicon-plus-sign"></span> Agregar
            </button>
        @else
            <div style="text-align:center;">
                <b>Piezas asignadas</b>
            </div>
        @endif
    </div>
@else  
<button type="button" id="guardarPieza2" class="btn btn-success">
                <span class="glyphicon glyphicon-plus-sign"></span> Agregar
            </button>
            @endif


<script>

$("#guardarPieza2").click(function(e){
        
    var idRefaccion = $("#idRefaccion").val();
    var validarCantidad = $("#piezasOcupar").val();
    

    if(idRefaccion=="" || validarCantidad=="" ){
        if(idRefaccion=='' ){
        $("#idRefaccion").css('border-color', 'red');
        $("#idRefaccion").css('color', 'red');  
        alert("Elige un SKU");
        }else if(validarCantidad=='' || validarCantidad==0){
        $("#piezasOcupar").css('border-color', 'red');
        $("#piezasOcupar").css('color', 'red');  
        alert("Ingresa una cantidad");
        }
    }else{
        var solicitada = +document.getElementById("piezasSolicitada").value;
        var ocupar = +document.getElementById("piezasOcupar").value;
        var cantidad = +document.getElementById("cantidad").value;
        var total = +document.getElementById("total").value;
        var idParte = $("#idParte").val();
        var idRepCotizacion = $("#idRepCotizacion").val();
        var totalOcupada=total+ocupar;

            // alert(totalOcupada);
        if(totalOcupada<=solicitada){
            if(cantidad>=ocupar){
                if(solicitada>=ocupar){
                    $("#reporte").load('{{url('guardarAsignacion')}}' + '?r=' + Date.now() + $(this).closest('form').serialize()); 
                    document.getElementById('mensaje').style.display = 'none';
                    document.getElementById('mensajeDisponible').style.display = 'none';
                    document.getElementById('mensajeTotal').style.display = 'none';
                    $("#idRefaccion, #proveedor, #numFactura, #fecha, #cantidad, #piezasOcupar").val('');
                    $("#idRefaccion").load('{{url('comboCodigoPiezas')}}' + '?r=' + Date.now() + '&idParte=' + idParte);
                    $("#cambio").load('{{url('cambiarBoton')}}' + '?r=' + Date.now() + '&idRepCotizacion=' + idRepCotizacion);
                    $("#idPendiente").load('{{url('cambiarPendiente')}}' + '?r=' + Date.now() + '&idRepCotizacion=' + idRepCotizacion);
                    
                    
                }else{
                    document.getElementById('mensaje').style.display = 'block';
                    e.preventDefault();
                }
            }else{
                document.getElementById('mensajeDisponible').style.display = 'block';
                    e.preventDefault();
            }
        }else{
            document.getElementById('mensajeTotal').style.display = 'block';
                    e.preventDefault();   
        }
        
    }   
});  
</script>