<!-- Este boton se le da clic automaticamente con el script de abajo para que abra el modal-->
<button type="button" class="" data-toggle="modal" data-target="#exampleModal" id="activar" style= "visibility:hidden">
</button>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div style="text-align:right">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close" onclick="cerrarM()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                

                <form action="">
                    <div id="actualizandoFechaEntrega">
                        <input type="hidden" name="idOrden" value="{{$idOrden}}">                        
                        <input type="hidden" name="tipoReporte" value="{{$tipoReporte}}">                        
                        <div class="" style="background-color:#F3FAF9">
                            <div>
                                <center>
                                    <br>
                                    <b>MODIFICAR FECHA DE ENTREGA</b>
                                    <br>
                                </center>
                            </div>
                            <div class="">
                                <div>*Nueva fecha de entrega:</div>                                
                                <input type="date" name="fechaEntregaProveedor" id="fechaEntregaProveedor" class="form-control rounded-0">
                                <br>
                            </div>
                            <div class="">
                                <div>*Motivo del cambio:</div>
                                <textarea name="notasCambioFechaEntregaProveedor" id="notasCambioFechaEntregaProveedor" cols="30" rows="10" class="form-control rounded-0"></textarea>
                                <br>
                            </div>                            
                            <div>
                                <center>
                                    <button type="button" class="btn btn-success btn-sm form-control guardarModificacionFecha">
                                        <span class="glyphicon glyphicon-plus-sign"></span> Guardar cambio
                                    </button>
                                </center>
                            </div>
                        </div>                                                
                    </div>
                    
                    <div id="actualizacionFechaOk" style="display:none;">
                        <br>
                        <div class="alert alert-success" role="alert">
                            <center>
                                Fecha de entrega modificada exitosamente.
                            </center>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.sinBorder{
    /* border-top: 0px;
    border-right: 0px;
    border-bottom: 1px solid black;
    border-left: 0px; */
    border-style: none;
}
</style>


<script>
    $(document).ready(function() {        
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
        // $(".modal").removeClass("modal fade bd-example-modal-lg in");
        $("#activar").click();
    });

    function cerrarM() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
        // $(".modal").removeClass("modal fade bd-example-modal-lg in");
    }


    $(".guardarModificacionFecha").click(function() {
        var fechaEntregaProveedor = $("#fechaEntregaProveedor").val();        
        var notasCambioFechaEntregaProveedor = $("#notasCambioFechaEntregaProveedor").val();        

        if (notasCambioFechaEntregaProveedor == '' || fechaEntregaProveedor == '') {
            
            if(fechaEntregaProveedor == ''){ 
                $("#fechaEntregaProveedor").css('border-color', 'red');
                $("#fechaEntregaProveedor").css('color', 'red');
                alert("La nueva fecha es obligatoria.");
            }else{
                $("#notasCambioFechaEntregaProveedor").css('border-color', 'red');
                $("#notasCambioFechaEntregaProveedor").css('color', 'red');
                alert("El motivo del cambio es obligatorio.");
            }
            
        }else{            
            
            $(".tablaConCambios").load('{{url('guardarModificacionFechaEntregaProveedor')}}' + '?' + $(this).closest('form').serialize());
            $('#actualizandoFechaEntrega').css('display', 'none');
            $('#actualizacionFechaOk').css('display', 'block');
            $(".modal-open").css('padding-right', '');
            $(".modal-open").removeClass("modal-open");
            $(".modal-backdrop").removeClass("modal-backdrop fade in");
        }
    });

</script>