<!-- Este boton se le da clic automaticamente con el script de abajo para que abra el modal-->
<button type="button" class="" data-toggle="modal" data-target="#exampleModal1" id="activar1"
    style="visibility:hidden">
</button>
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal1" tabindex="-1" role="dialog"
    aria-labelledby="exampleModal1Label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <div style="text-align:right">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close"
                        onclick="cerrarM()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <h5 class="modal-title" id="exampleModal1Label">Editar producto</h5>

            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div id="actualizandoRfq">
                        <input type="hidden" name="idrfqG" value="{{ $idRfq }}">
                        <input type="hidden" name="marcaskudetalle" id="marcaskudetalle"
                            value="{{ $consulta->idmarca }}">
                        <input type="hidden" id="idrfqdetalleG" name="idrfqdetalleG" value="{{ $idRfqDetalle }}">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="sub-title">SKU</div>
                                    <input type="text" name="skuG" id="skuG" class="form-control"
                                        value="{{ $consulta->sku_generico }}">

                                </div>
                                <div class="col-md-6">
                                    <div class="sub-title">Modelo</div>
                                    <input type="text" name="modeloG" id="modeloG" class="form-control"
                                        value="{{ $consulta->modelo }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="sub-title">Tipo</div>
                                    <input type="text" name="tipG" id="tipG" class="form-control"
                                        value="{{ $consulta->tip }}">
                                </div>
                                <div class="col-md-6">
                                    <div class="sub-title">Cantidad</div>
                                    <input type="text" name="cantidadG" id="cantidadG" class="form-control" required
                                        data-error-message="El campo cantidad es requerido"
                                        value="{{ $consulta->cantidad }}">
                                    <span class="error-message"></span>
                                </div>
                            </div>

                            <div class="sub-title">Notas</div>
                            <textarea name="notasG" id="notasG" rows="3" class="form-control">{{ $consulta->notas }}</textarea>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="sub-title">Foto / Archivo</div>
                                    <div class="file-container">
                                        <div class="file-input-wrapper">
                                            <div class="file-input">
                                                <input type="file" name="foto1G" id="foto1G"
                                                    class="form-control">
                                            </div>
                                            @if ($consulta->foto1 != 'Sin foto')
                                                <button type="button" class="btn btn-sm btn-danger eliminar-foto1G"
                                                    style="width:40px; height:35px; margin-left: 5px;">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                            @endif
                                        </div>
                                        @if ($consulta->foto1 != 'Sin foto')
                                            <div id="nombreArchivo1" style="font-size: 11px;">{{ $consulta->foto1 }}
                                            </div>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="sub-title">Foto / Archivo</div>
                                    <div class="file-container">
                                        <div class="file-input-wrapper">
                                            <div class="file-input">
                                                <input type="file" name="foto2G" id="foto2G"
                                                    class="form-control">
                                            </div>
                                            @if ($consulta->foto2 != 'Sin foto')
                                                <button type="button" class="btn btn-sm btn-danger eliminar-foto2G"
                                                    style="width:40px; height:35px; margin-left: 5px;">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                            @endif
                                        </div>
                                        @if ($consulta->foto2 != 'Sin foto')
                                            <div id="nombreArchivo2" style="font-size: 11px;">{{ $consulta->foto2 }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="sub-title">Foto / Archivo</div>
                                    <div class="file-container">
                                        <div class="file-input-wrapper">
                                            <div class="file-input">
                                                <input type="file" name="foto3G" id="foto3G"
                                                    class="form-control">
                                            </div>
                                            @if ($consulta->foto3 != 'Sin foto')
                                                <button type="button" class="btn btn-sm btn-danger eliminar-foto3G"
                                                    style="width:40px; height:35px; margin-left: 5px;">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                            @endif
                                        </div>
                                        @if ($consulta->foto3 != 'Sin foto')
                                            <div id="nombreArchivo3" style="font-size: 11px;">{{ $consulta->foto3 }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="sub-title">Foto / Archivo</div>
                                    <div class="file-container">
                                        <div class="file-input-wrapper">
                                            <div class="file-input">
                                                <input type="file" name="foto4G" id="foto4G"
                                                    class="form-control">
                                            </div>
                                            @if ($consulta->foto4 != 'Sin foto')
                                                <button type="button" class="btn btn-sm btn-danger eliminar-foto4G"
                                                    style="width:40px; height:35px; margin-left: 5px;">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                            @endif
                                        </div>
                                        @if ($consulta->foto4 != 'Sin foto')
                                            <div id="nombreArchivo4" style="font-size: 11px;">{{ $consulta->foto4 }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row custom-row">
                                <div class="col-md-6">
                                    <div class="sub-title">Foto / Archivo</div>
                                    <div class="file-container">
                                        <div class="file-input-wrapper">
                                            <div class="file-input">
                                                <input type="file" name="foto5G" id="foto5G"
                                                    class="form-control">
                                            </div>
                                            @if ($consulta->foto5 != 'Sin foto')
                                                <button type="button" class="btn btn-sm btn-danger eliminar-foto5G"
                                                    style="width:40px; height:35px; margin-left: 5px;">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                            @endif
                                        </div>
                                        @if ($consulta->foto5 != 'Sin foto')
                                            <div id="nombreArchivo5" style="font-size: 11px;">{{ $consulta->foto5 }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success btn-sm rounded-0 guardarProdG">
                                <span class="glyphicon glyphicon-plus-sign"></span> Guardar
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="cerrarM()">
                                <span aria-hidden="true">Cerrar</span>
                            </button>
                        </div>
                    </div>
                    <div id="actualizacionOk" style="display:none;">
                        <br>
                        <div class="alert alert-success" role="alert">
                            <center>
                                Producto modificado correctamente.
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

    .custom-row {
        display: flex;
        justify-content: space-around;
    }

    .file-container {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .file-input-wrapper {
        display: flex;
        align-items: center;
    }

    .file-input {
        display: flex;
        align-items: center;
    }

    .file-input>input {
        margin-right: 5px;
    }
</style>

<script>
    $(document).ready(function() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
        $("#activar1").click();
    });

    function cerrarM() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
    }

    $(".guardarProdG").click(function(e) {
        e.preventDefault();

        var form = $(this).closest('form')[0];
        var formData = new FormData(form);

        $.ajax({
            url: '{{ url('guardarModifProdG') }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#reporteProductos').html(response);
                $('#actualizandoRfq').css('display', 'none');
                $('#actualizacionOk').css('display', 'block');
                $(".modal-open").css('padding-right', '');
                $(".modal-open").removeClass("modal-open");
                $(".modal-backdrop").removeClass("modal-backdrop fade in");
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    jQuery("#cantidadG").on("input", function() {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9]/g, ''));
    })


    $('#foto1G').on('change', function() {
        var fileInput = $('#foto1G');
        var nombreArchivo = $('#nombreArchivo1');

        if (fileInput[0].files.length > 0) {
            nombreArchivo1.hide();
        } else {
            nombreArchivo1.show();
        }
    });
    $('#foto2G').on('change', function() {
        var fileInput = $('#foto2G');
        var nombreArchivo = $('#nombreArchivo2');

        if (fileInput[0].files.length > 0) {
            nombreArchivo2.hide();
        } else {
            nombreArchivo2.show();
        }
    });
    $('#foto3G').on('change', function() {
        var fileInput = $('#foto3G');
        var nombreArchivo = $('#nombreArchivo3');

        if (fileInput[0].files.length > 0) {
            nombreArchivo3.hide();
        } else {
            nombreArchivo3.show();
        }
    });
    $('#foto4G').on('change', function() {
        var fileInput = $('#foto4G');
        var nombreArchivo = $('#nombreArchivo4');

        if (fileInput[0].files.length > 0) {
            nombreArchivo4.hide();
        } else {
            nombreArchivo4.show();
        }
    });
    $('#foto5G').on('change', function() {
        var fileInput = $('#foto5G');
        var nombreArchivo = $('#nombreArchivo5');

        if (fileInput[0].files.length > 0) {
            nombreArchivo5.hide();
        } else {
            nombreArchivo5.show();
        }
    });

    $(".eliminar-foto1G").on("click", function() {
        var idRfqDetalle = $("#idrfqdetalleG").val();
        var url = "/eliminarFoto1/" + idRfqDetalle; // Construir la URL manualmente
        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                $("#nombreArchivo1").hide();
                $(".eliminar-foto1G").hide();
                alert(response.message);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });
    $(".eliminar-foto2G").on("click", function() {
        var idRfqDetalle = $("#idrfqdetalleG").val();
        var url = "/eliminarFoto2/" + idRfqDetalle; // Construir la URL manualmente
        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                $("#nombreArchivo2").hide();
                $(".eliminar-foto2G").hide();
                alert(response.message);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });
    $(".eliminar-foto3G").on("click", function() {
        var idRfqDetalle = $("#idrfqdetalleG").val();
        var url = "/eliminarFoto3/" + idRfqDetalle; // Construir la URL manualmente
        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                $("#nombreArchivo3").hide();
                $(".eliminar-foto3G").hide();
                alert(response.message);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });
    $(".eliminar-foto4G").on("click", function() {
        var idRfqDetalle = $("#idrfqdetalleG").val();
        var url = "/eliminarFoto4/" + idRfqDetalle; // Construir la URL manualmente
        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                $("#nombreArchivo4").hide();
                $(".eliminar-foto4G").hide();
                alert(response.message);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });
    $(".eliminar-foto5G").on("click", function() {
        var idRfqDetalle = $("#idrfqdetalleG").val();
        var url = "/eliminarFoto5/" + idRfqDetalle; // Construir la URL manualmente
        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                $("#nombreArchivo5").hide();
                $(".eliminar-foto5G").hide();
                alert(response.message);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });
</script>
