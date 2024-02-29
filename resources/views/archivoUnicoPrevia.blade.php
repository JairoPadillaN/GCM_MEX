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
                <h5 class="modal-title" id="exampleModal2Label">Vista Previa</h5>

            </div>
            <div class="modal-body">
                <form action="">
                    <div id="Reporte">
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
                                        @foreach ($consultarArchivoUnico as $CAU)
                                            @if ($CAU->activo == '1')
                                                <tr>
                                                    <td>{{ $CAU->fecha }}</td>
                                                    <td>{{ $CAU->nombreArchivo }}</td>
                                                    <td>{{ $CAU->descripcion }}</td>
                                                    <td>{{ $CAU->id_usuario }}</td>
                                                    <td align="center">
                                                            <div class="archivo-container">
                                                                <a target="_blank"
                                                                    href="{{ asset('archivos/' . $CAU->archivo) }}">
                                                                    <i class="fa fa-file"></i>
                                                                </a>
                                                            </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-4 text-center">
                                    <div class="mb-3">
                                        @if ($CAU->archivo && (
                                          pathinfo($CAU->archivo, PATHINFO_EXTENSION) == 'JPG' || 
                                          pathinfo($CAU->archivo, PATHINFO_EXTENSION) == 'jpg' || 
                                          pathinfo($CAU->archivo, PATHINFO_EXTENSION) == 'PNG' || 
                                          pathinfo($CAU->archivo, PATHINFO_EXTENSION) == 'png' || 
                                          pathinfo($CAU->archivo, PATHINFO_EXTENSION) == 'JPEG'||
                                          pathinfo($CAU->archivo, PATHINFO_EXTENSION) == 'jpeg'))
                                            <p>Archivo actual:</p>
                                            <img src="{{ url('archivos/' . $CAU->archivo) }}" alt="Vista previa" style="max-width: 50%; height: auto;">
                                        @elseif ($CAU->archivo)
                                        @php
                                            $fileExtension = pathinfo($CAU->archivo, PATHINFO_EXTENSION);
                                        @endphp
                                        
                                        @if ($fileExtension == 'pdf')
                                            <iframe style="float:center;" src = "{{asset('/ViewerJS/#../archivos/' . $CAU->archivo)}}" width='800' height='389' allowfullscreen webkitallowfullscreen></iframe>
                                        @else
                                            <div class="alert alert-warning">Vista previa no disponible para este tipo de archivo</div>
                                        @endif
                                        @else
                                            <div class="alert alert-warning">Vista previa no disponible para este tipo de archivo</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        <div class="modal-footer">
                            
                            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="cerrarM()">
                                <span aria-hidden="true">Cerrar</span>
                            </button>
                        </div>
                    </div>
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
        $("#activar2").click();});

    function cerrarM() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
    }
</script>
