<!-- Este boton se le da clic automaticamente con el script de abajo para que abra el modal-->
<button type="button" class="" data-toggle="modal" data-target="#exampleModal3" id="activar3"
    style="visibility:hidden">
</button>
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal3" tabindex="-1" role="dialog"
    aria-labelledby="exampleModal3Label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <div style="text-align:right">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close"
                        onclick="cerrarM()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <h5 class="modal-title" id="exampleModal3Label">Observaciones</h5>

            </div>
            <div class="modal-body">
                <form action="">
                    <div id="actualizandoObservacion">
                        <input type="hidden" name="nombreTab" value="{{ $nombreTab }}">
                        <input type="hidden" name="idrfq" value="{{ $idrfq }}">
                        <div class="form-group">
                            <label for="dni">Observaciones:</label>
                            <div>
                                <textarea name="observaciones" id="observaciones" rows="7" class="form-control">{{ $observacion }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success btn-sm rounded-0 guardarDatosObservacion">
                                <span class="glyphicon glyphicon-plus-sign"></span> Guardar observaciones
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="cerrarM()">
                                <span aria-hidden="true">Cerrar</span>
                            </button>
                        </div>
                    </div>
                    <div id="actualizacionObservacionOk" style="display:none;">
                        <br>
                        <div class="alert alert-success" role="alert">
                            <center>
                                Observaciones modificadas correctamente.
                            </center>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .sinBorder {
        border-style: none;
    }

    .modal-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .vertical-center {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    textarea {
        resize: none;
    }
</style>

<script>
    $(document).ready(function() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
        $("#activar3").click();
    });

    function cerrarM() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
    }

    $(".guardarDatosObservacion").click(function(e) {
        e.preventDefault();
        var nombreTab = $(this).closest('form').find('input[name="nombreTab"]').val();

        $.ajax({
            url: '{{ url('guardarDatosObservacion') }}',
            type: 'GET',
            data: $(this).closest('form').serialize(),
            success: function(response) {
                $(document).trigger('cargarTabla', nombreTab);

                $('#actualizandoObservacion').css('display', 'none');
                $('#actualizacionObservacionOk').css('display', 'block');
                $(".modal-open").css('padding-right', '');
                $(".modal-open").removeClass("modal-open");
                $(".modal-backdrop").removeClass("modal-backdrop fade in");
            },
            error: function(error) {
                // Maneja cualquier error que pueda ocurrir
            }
        });
    });
</script>
