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
                <h5 class="modal-title" id="exampleModal2Label">Archivos subidos</h5>

            </div>
            <div class="modal-body">
                <form action="">
                    <div id="Reporte">
                        @if ($cuantos == 0)
                            <br>

                            <div class="alert alert-warning" role="alert" align="center">
                                <label for="">Sin archivos agregados...</label>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr style="background-color: #E8E8E8;">
                                            <th width="15%">
                                                <font SIZE=2>Fecha</font>
                                            </th>
                                            <th width="20%">
                                                <font SIZE=2>Nombre</font>
                                            </th>
                                            <th width="25%">
                                                <font SIZE=2>Descripción</font>
                                            </th>
                                            <th width="30%">
                                                <font SIZE=2>Agregado por:</font>
                                            </th>
                                            <th width="10%">
                                                <font SIZE=2>Archivo</font>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($consultarchivos as $ca)
                                            @if ($ca->activo === 'Si')
                                                <tr>
                                                    <td>{{ $ca->fecha }}</td>
                                                    <td>{{ $ca->nombre }}</td>
                                                    <td>{{ $ca->descripcion }}</td>
                                                    <td>{{ $ca->iduser }}</td>
                                                    <td align="center">
                                                        @if ($ca->archivo !== 'Sin archivo')
                                                            <div class="archivo-container">
                                                                <a target="_blank"
                                                                    href="{{ asset('archivos/' . $ca->archivo) }}">
                                                                    <i class="fa fa-file"></i>
                                                                </a>
                                                            </div>
                                                        @else
                                                            {{ $ca->archivo }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <div class="modal-footer">

                            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="cerrarM()">
                                <span aria-hidden="true">Cerrar</span>
                            </button>
                        </div>
                    </div>
                    <div id="actualizacionFechaOk" style="display:none;">
                        <br>
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

    .contenedor-fotos {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        margin-top: 30px;
    }

    .contenedor {
        text-align: center;
    }

    .contenedor img {
        display: block;
        margin: 0 auto;
    }

    .contenedor p {
        text-align: center;
        font-size: 11px;
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
</script>
