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
                        <input type="hidden" name="idrfqM" value="{{ $idRfq }}">
                        <input type="hidden" name="marcaskudetalle" value="{{ $consulta->idMarca }}">
                        <input type="hidden" id="idrfqdetalleM" name="idrfqdetalleM" value="{{ $idRfqDetalle }}">
                        <div class="form-group">


                            <div class="sub-title">Categoría</div>
                            <select name="tipoM" id="tipoM" class="form-control">
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->idTipoRefacciones }}"
                                        {{ $selectedTipo == $tipo->idTipoRefacciones ? 'selected' : '' }}>
                                        {{ $tipo->nombreTipoRefaccion }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="sub-title">SKU</div>
                            <select name="skuM" id="skuM" class="form-control">
                                @foreach ($skus as $sku)
                                    <option value="{{ $sku->idPartesVenta }}"
                                        {{ $selectedSku == $sku->idPartesVenta ? 'selected' : '' }}>
                                        {{ $sku->parte }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="sub-title">Modelo</div>
                                    <input type="text" name="modeloM" id="modeloM" class="form-control" readonly
                                        value="{{ $consulta->modelo }}">
                                </div>
                                <div class="col-md-4">
                                    <div class="sub-title">Tipo</div>
                                    <input type="text" name="tipM" id="tipM" class="form-control"
                                        value="{{ $consulta->tip }}">
                                </div>
                                <div class="col-md-4">
                                    <div class="sub-title">Cantidad</div>
                                    <input type="text" name="cantidadM" id="cantidadM" class="form-control" required
                                        data-error-message="El campo cantidad es requerido"
                                        value="{{ $consulta->cantidad }}">
                                    <span class="error-message"></span>
                                </div>
                            </div>

                            <div class="sub-title">Notas</div>
                            <textarea name="notasM" id="notasM" rows="3" class="form-control">{{ $consulta->notas }}</textarea>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="sub-title">Foto / Archivo</div>
                                    <div class="file-container">
                                        <div class="file-input-wrapper">
                                            <div class="file-input">
                                                {{ Form::file('foto1M', ['id' => 'foto1M']) }}
                                            </div>
                                            @if ($consulta->foto1 != 'Sin foto')
                                                <button type="button" class="btn btn-sm btn-danger eliminar-foto1"
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
                                                {{ Form::file('foto2M', ['id' => 'foto2M']) }}
                                            </div>
                                            @if ($consulta->foto2 != 'Sin foto')
                                                <button type="button" class="btn btn-sm btn-danger eliminar-foto2"
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
                                                {{ Form::file('foto3M', ['id' => 'foto3M']) }}
                                            </div>
                                            @if ($consulta->foto3 != 'Sin foto')
                                                <button type="button" class="btn btn-sm btn-danger eliminar-foto3"
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
                                                {{ Form::file('foto4M', ['id' => 'foto4M']) }}
                                            </div>
                                            @if ($consulta->foto4 != 'Sin foto')
                                                <button type="button" class="btn btn-sm btn-danger eliminar-foto4"
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
                                                {{ Form::file('foto5M', ['id' => 'foto5M']) }}
                                            </div>
                                            @if ($consulta->foto5 != 'Sin foto')
                                                <button type="button" class="btn btn-sm btn-danger eliminar-foto5"
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
                            <button type="button" class="btn btn-success btn-sm rounded-0 guardarProd">
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


    $(".guardarProd").click(function(e) {
        e.preventDefault();

        var form = $(this).closest('form')[0];
        var formData = new FormData(form);

        $.ajax({
            url: '{{ url('guardarModifProd') }}',
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


    jQuery("#cantidadM").on("input", function() {
        jQuery(this).val(jQuery(this).val().replace(/[^0-9]/g, ''));
    })

    $("#tipoM").change(function() {
        document.getElementById("modeloM").value = "";
        $('#skuM').empty();
        $('#skuM').append($('<option>', {
            value: "",
            text: 'Seleccione SKU'
        }));
        var tipo_value = $(this).val();
        $("#skuM").load('{{ url('comboSKU') }}' + '?r=' + Date.now() + '&tipo=' + this
            .options[
                this
                .selectedIndex].value);
    });

    $('#skuM').on('change', function() {
        $('#modeloM').val(""); // Borrar el contenido del input #modelo

        var sku_value = $(this).val();
        if ($.trim(sku_value) != "") {
            $.get('{{ route('obtModelo') }}', {
                id: sku_value // Envía el id del sku seleccionado
            }, function(modelo) {
                $('#modeloM').val(modelo);
            });
        }
    });

    $('#foto1M').on('change', function() {
        var fileInput = $('#foto1M');
        var nombreArchivo = $('#nombreArchivo1');

        if (fileInput[0].files.length > 0) {
            nombreArchivo1.hide();
        } else {
            nombreArchivo1.show();
        }
    });
    $('#foto2M').on('change', function() {
        var fileInput = $('#foto2M');
        var nombreArchivo = $('#nombreArchivo2');

        if (fileInput[0].files.length > 0) {
            nombreArchivo2.hide();
        } else {
            nombreArchivo2.show();
        }
    });
    $('#foto3M').on('change', function() {
        var fileInput = $('#foto3M');
        var nombreArchivo = $('#nombreArchivo3');

        if (fileInput[0].files.length > 0) {
            nombreArchivo3.hide();
        } else {
            nombreArchivo3.show();
        }
    });
    $('#foto4M').on('change', function() {
        var fileInput = $('#foto4M');
        var nombreArchivo = $('#nombreArchivo4');

        if (fileInput[0].files.length > 0) {
            nombreArchivo4.hide();
        } else {
            nombreArchivo4.show();
        }
    });
    $('#foto5M').on('change', function() {
        var fileInput = $('#foto5M');
        var nombreArchivo = $('#nombreArchivo5');

        if (fileInput[0].files.length > 0) {
            nombreArchivo5.hide();
        } else {
            nombreArchivo5.show();
        }
    });

    $(".eliminar-foto1").on("click", function() {
        var idRfqDetalle = $("#idrfqdetalleM").val();
        var url = "/eliminarFoto1/" + idRfqDetalle; // Construir la URL manualmente
        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                $("#nombreArchivo1").hide();
                $(".eliminar-foto1").hide();
                alert(response.message);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });    


    $(".eliminar-foto2").on("click", function() {
        var idRfqDetalle = $("#idrfqdetalleM").val();
        var url = "/eliminarFoto2/" + idRfqDetalle; // Construir la URL manualmente
        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                $("#nombreArchivo2").hide();
                $(".eliminar-foto2").hide();
                alert(response.message);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });
    $(".eliminar-foto3").on("click", function() {
        var idRfqDetalle = $("#idrfqdetalleM").val();
        var url = "/eliminarFoto3/" + idRfqDetalle; // Construir la URL manualmente
        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                $("#nombreArchivo3").hide();
                $(".eliminar-foto3").hide();
                alert(response.message);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });
    $(".eliminar-foto4").on("click", function() {
        var idRfqDetalle = $("#idrfqdetalleM").val();
        var url = "/eliminarFoto4/" + idRfqDetalle; // Construir la URL manualmente
        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                $("#nombreArchivo4").hide();
                $(".eliminar-foto4").hide();
                alert(response.message);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });
    $(".eliminar-foto5").on("click", function() {
        var idRfqDetalle = $("#idrfqdetalleM").val();
        var url = "/eliminarFoto5/" + idRfqDetalle; // Construir la URL manualmente
        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                $("#nombreArchivo5").hide();
                $(".eliminar-foto5").hide();
                alert(response.message);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });
</script>
