<!-- Este boton se le da clic automaticamente con el script de abajo para que abra el modal-->
<button type="button" class="" data-toggle="modal" data-target="#exampleModal2" id="activar2"
    style="visibility:hidden">
</button>
<!-- Modal -->

<div class="modal fade bd-example-modal-lg" data-backdrop="static" data-keyboard="false" id="exampleModal2" tabindex="-1" role="dialog"
    aria-labelledby="exampleModal2Label" aria-hidden="true"  style="display: block;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <div style="text-align:right">

                    </button>
                </div>
                <h5 class="modal-title" id="exampleModal2Label">Notificaciones de RFQ's</h5>

            </div>
            <div class="modal-body">


                        <div class="table-responsive">
                            <div id="notiVista">
                            <table class="table table-striped table-hover" id="Not" style="width:100%">
                                <thead >
                                    <tr style="background-color: #B0B0B0"">

                                        <th>Acción</th>
                                        <th>Mensaje</th>
                                        <th>Fecha/Hora</th>
                                        <th>Visto</th>
                                        </tr>
                                </thead>
                                <tbody>
                                    @if($cuantos>=1)
                                    @foreach ($consulta as $consu)
                                        <tr>

                                            <td>{{$consu->accion}}</td>
                                            <td>{{$consu->mensaje}}</td>
                                            <td>{{$consu->fecha_hora}} hrs</td>
                                            <td>
                                                <center>
                                                @if ($consu->visto === 1)
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                class="btn btn-success btn-circle btn-sm visto" disabled>
                                            <i class="fa fa-check-square-o fa-lg" aria-hidden="true" ></i>
                                                </button>
                                            </center>
                                                @else
                                                <center>
                                                <form action="" method="POST"
                                                enctype="application/x-www-form-urlencoded"
                                                name="frmdo{{$consu->idnot}}"
                                                id="frmdo{{$consu->idnot}}" target="_self">

                                                <input type="hidden"
                                                value="{{ $consu->idnot }}"
                                                name="idnot" id="idnot">

                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                class="btn btn-primary btn-circle btn-sm visto">
                                            <i class="fa fa-square-o fa-lg" aria-hidden="true"></i>
                                            </button>
                                        </center>
                                                </form>
                                            @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    @else
                                    <tr>

                                        <td colspan= 4><center>No hay notificaciones</center></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-danger" id="cerrarModal" data-dismiss="modal" onclick="cerrarM()">
                                <span aria-hidden="true">Cerrar</span>
                            </button>
                        </div>
                    </div>
                    <div id="actualizacionFechaOk" style="display:none;">
                        <br>
                    </div>

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
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 30px;
    }

    .vertical-center {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .btn-circle.btn-sm {
            width: 30px;
            height: 30px;
            padding: 6px 0px;
            border-radius: 15px;
            font-size: 8px;
            text-align: center;
        }
</style>

<script>
    $(document).ready(function() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
        $("#activar2").click();

        // Evitar que el modal se cierre haciendo clic fuera de él
         $("#exampleModal2").modal({
            backdrop: 'static',
            keyboard: false
        });

        //↓ Refrescar seccion del div y realizar la accion
        $("#notiVista").on("click", ".visto", function() {
                $("#notiVista").load('{{ url('visto') }}' + '?' + $(this).closest('form').serialize());
            });

         // ↓ Recargar la página cuando se da clic al boton cerrar
         $("#cerrarModal").click(function() {
            window.location.reload();
        });

    });

    /*function cerrarM() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
    }*/
</script>

