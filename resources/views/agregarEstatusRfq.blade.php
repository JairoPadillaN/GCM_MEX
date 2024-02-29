<!-- Este boton se le da clic automaticamente con el script de abajo para que abra el modal-->
<button type="button" class="" data-toggle="modal" data-target="#exampleModal" id="activar"
    style="visibility:hidden">
</button>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div style="text-align:right">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close"
                        onclick="cerrarM()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="row" style="background-color:#F3FAF9">
                    <div>
                        <center>
                            <h5 class="modal-title">Estatus</h5>
                        </center>
                    </div>
                </div>
                <form action="{{ route('guardarEstatusRfq') }}" method="GET">
                    @csrf
                    <div id="actualizarEstatus">
                        <input type="text" name="idrfq" value="{{ $idrfq }}">
                        <div id='combop'>
                            <select name='estatus' id='estatus' class="form-control">
                                <option value="">Seleccione Estatus</option>
                                <option value="Cotizando" {{ $estatus == 'Cotizando' ? 'selected' : '' }}>Cotizando
                                </option>
                                <option value="En cálculos de importación"
                                    {{ $estatus == 'En cálculos de importación' ? 'selected' : '' }}>En cálculos de
                                    importación</option>
                                <option value="Precio de lista en sistema"
                                    {{ $estatus == 'Precio de lista en sistema' ? 'selected' : '' }}>Precio de lista en
                                    sistema</option>
                                <option value="Cotización enviada al cliente"
                                    {{ $estatus == 'Cotización enviada al cliente' ? 'selected' : '' }}>Cotización
                                    enviada al cliente</option>
                                <option value="En espera de OC" {{ $estatus == 'En espera de OC' ? 'selected' : '' }}>En
                                    espera de OC</option>
                                <option value="PO enviada" {{ $estatus == 'PO enviada' ? 'selected' : '' }}>PO enviada
                                </option>
                                <option value="En tránsito a Laredo"
                                    {{ $estatus == 'En tránsito a Laredo' ? 'selected' : '' }}>En tránsito a Laredo
                                </option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div style="text-align:center">
                    <button type="button" class="btn btn-success btn-sm rounded-0 guardarEstatus">
                        <span class="glyphicon glyphicon-plus-sign"></span> Guardar estatus
                    </button>
                </div>
            </div>
        </div>

        <script>
            function cerrarM() {
                $('#exampleModal').modal('hide');
            }

            $(document).ready(function() {
                $("#activar").click();

                $(".guardarEstatus").click(function() {
                    $("#reporterfqs").load('{{ url('guardarEstatusRfq') }}' + '?' + $(this).closest('form')
                        .serialize());
                });
            });
        </script>


        <style>
            .sinBorder {
                /* border-top: 0px;
    border-right: 0px;
    border-bottom: 1px solid black;
    border-left: 0px; */
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
