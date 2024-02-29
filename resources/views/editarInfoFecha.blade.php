<!-- Este boton se le da clic automaticamente con el script de abajo para que abra el modal-->
<button type="button" class="" data-toggle="modal" data-target="#exampleModal2" id="activar2"
    style="visibility:hidden">
</button>
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal2" tabindex="-1" role="dialog"
    aria-labelledby="exampleModal2Label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <div style="text-align:right">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close"
                        onclick="cerrarM()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <h5 class="modal-title" id="exampleModal2Label">Fecha estimada de entrega</h5>

            </div>
            <div class="modal-body">
                <form action="">
                    <div id="actualizandoFecha">
                        <input type="hidden" name="nombreTab" value="{{ $nombreTab }}">
                        <input type="hidden" name="idrfq" value="{{ $idrfq }}">
                        <div class="form-group">
                            <label for="dni">Fecha estimada entrega:</label>
                            <div>
                                <input type="date" name="fechaestimadaentrega"
                                    value="{{ $fecha ? $fecha : date('Y-m-d') }}" class="form-control"
                                    min="{{ date('Y-m-d', strtotime('-1 day')) }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success btn-sm rounded-0 guardarDatosFecha">
                                <span class="glyphicon glyphicon-plus-sign"></span> Guardar fecha
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="cerrarM()">
                                <span aria-hidden="true">Cerrar</span>
                            </button>
                        </div>
                    </div>
                    <div id="actualizacionFechaOk" style="display:none;">
                        <br>
                        <div class="alert alert-success" role="alert">
                            <center>
                                Fecha modificada correctamente.
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
</style>

<script>
    $(document).ready(function() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
        $("#activar2").click();
    });

    function cerrarM() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
    }

    $(".guardarDatosFecha").click(function(e) {
        e.preventDefault();
        var nombreTab = $(this).closest('form').find('input[name="nombreTab"]').val();

        $.ajax({
            url: '{{ url('guardarDatosFecha') }}',
            type: 'GET',
            data: $(this).closest('form').serialize(),
            success: function(response) {
                $(document).trigger('cargarTabla', nombreTab);
                $('#actualizandoFecha').css('display', 'none');
                $('#actualizacionFechaOk').css('display', 'block');
                $(".modal-open").css('padding-right', '');
                $(".modal-open").removeClass("modal-open");
                $(".modal-backdrop").removeClass("modal-backdrop fade in");
            },
            error: function(error) {}
        });
    });
</script>
