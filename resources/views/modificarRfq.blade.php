@extends('principal')
@section('contenido')

    <script type="text/javascript">
        $(document).ready(function() {
            $(".cargar").click();
        });
    </script>

    <div class="col-xs-12">
        <div class="panel panel-default" style="margin-top:-55px">
            <div class="panel-heading">
                <h1>Editar RFQ
                </h1>
            </div>
            <div class="panel-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="cargar" class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#orden"
                            role="tab" aria-controls="pills-home" aria-selected="true">Datos de solicitud</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#producto" role="tab"
                            aria-controls="pills-home" aria-selected="true">SKU en solicitud</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade" id="orden" role="tabpanel" aria-labelledby="pills-home-tab">
                        {{ Form::open(['route' => 'guardarModificacionRfq', 'id' => 'formularioModifRfq', 'files' => true]) }}
                        {{ Form::token() }}
                        <!-- DATOS DE ORDEN -->
                        <div class="row">
                            <div class="form-group col-md-6">
                                <input type="hidden" name="idrfq" id="idrfq" value="{{ $consulta->idrfq }}">
                                <div class="sub-title">Usuario</div>
                                <div>
                                    <input type="hidden" name="idu" id="idu"
                                        value="{{ Session::get('sesionidu') }}">
                                    <input type="text" name="usuarioRfqs" class="form-control" readonly="true"
                                        value="{{ Session::get('sesionname') }} {{ Session::get('sesionpaterno') }} {{ Session::get('sesionmaterno') }}">
                                </div>

                                <div class="sub-title">*Marca</div>
                                <div>
                                    <select name="idmarca" id="idmarca" class="form-control" required>
                                        @foreach ($marcas as $marca)
                                            <option value="{{ $marca->idMarcaRefa }}"
                                                {{ $selectedMarca == $marca->idMarcaRefa ? 'selected' : '' }}>
                                                {{ $marca->marcaRefaccion }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('idmarca')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                    @if (session('error'))
                                        <div class="error-message">{{ session('error') }}</div>
                                    @endif
                                </div>

                                <div class="sub-title">*Cliente</div>
                                <div>
                                    <select name="idc" id="idc" class="form-control" required>
                                        @foreach ($clientes as $cliente)
                                            <option value="{{ $cliente->idc }}"
                                                {{ $selectedCliente == $cliente->idc ? 'selected' : '' }}>
                                                {{ $cliente->razonSocial }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('idc')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="sub-title">*Sucursal</div>
                                <select name="ids" id="ids" class="form-control" required>
                                    @foreach ($sucursales as $sucursal)
                                        <option value="{{ $sucursal->idSucursal }}"
                                            {{ $selectedSucursal == $sucursal->idSucursal ? 'selected' : '' }}>
                                            {{ $sucursal->sucursal }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ids')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror




                                <div class="sub-title">Ciudad</div>
                                <div>
                                    <input type="text" name="ciudad" id="ciudad" class="form-control" readonly
                                        required value="{{ $consulta->ciudad }}">
                                    @error('ciudad')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>

                                    <div class="sub-title">CMGRFQ#</div>
                                    <input type="text" name="cmgrfq" id="cmgrfq" class="form-control" readonly
                                        required value="{{ $consulta->cmgrfq }}">
                                    @error('cmgrfq')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="sub-title">*Contacto</div>
                                <select name="contacto" id="contacto" class="form-control" required>
                                    @foreach ($contactos as $contacto)
                                        <option value="{{ $contacto->idContactoSuc }}"
                                            {{ $selectedContacto == $contacto->idContactoSuc ? 'selected' : '' }}>
                                            {{ $contacto->contacto }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('contacto')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror


                            </div>
                            <div class="col-md-6">
                                <div class="sub-title">*Nivel de solicitud</div>

                                <select name='solicitud' id='solicitud' class="form-control" required>
                                    <option value="Maquina Parada"
                                        {{ $consulta->nivelsolicitud == 'Maquina Parada' ? 'selected' : '' }}>
                                        Máquina Parada</option>
                                    <option value="Mariconeando"
                                        {{ $consulta->nivelsolicitud == 'Mariconeando' ? 'selected' : '' }}>
                                        Mariconeando</option>
                                    <option value="Para stock"
                                        {{ $consulta->nivelsolicitud == 'Para stock' ? 'selected' : '' }}>Para
                                        stock</option>
                                </select>
                                @error('solicitud')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror



                                <div class="sub-title"> Fecha Solicitud </div>
                                <div>
                                    <input type="date" name="fechaSolicitud" readonly
                                        value="{{ $consulta->fechasolicitud }}" class="form-control">
                                </div>


                                <div class="sub-title"> Fecha Entrega </div>
                                <div>
                                    <input type="date" name="fechaEntrega" value="{{ $consulta->fechaentrega }}"
                                        class="form-control" min="{{ date('Y-m-d') }}">
                                </div>

                                <div class="sub-title"> Notas </div>
                                <div>
                                    <textarea name="notasrfq" id="notasrfq" rows="5" class="form-control">{{ $consulta->notasrfq }}</textarea>
                                </div>
                                <br>
                                <div class="row row-boton">
                                    <button type="button" id="guardar" name="guardar"
                                        class="btn btn-primary">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}

                    <!-- TAB DE PRODUCTOS -->
                    <div class="tab-pane fade" id="producto" role="tabpanel" aria-labelledby="pills-home-tab">
                        {{ Form::open(['id' => 'formularioSkus', 'files' => true]) }}
                        {{ Form::token() }}
                        <div class="row">
                            <div class="form-group col-md-12">
                                <input type="hidden" name="idrfq" id="idrfq" value="{{ $consulta->idrfq }}">
                                <input type="hidden" name="marcaskudetalle" id="marcaskudetalle"
                                    value="{{ $consulta->idmarca }}">

                                <div class="sub-title">SKU existente</div>
                                <div>
                                    Si <input type="radio" name="tiposku" id="siSeleccionado"
                                        value="SKU">&nbsp;&nbsp;&nbsp;
                                    No <input type="radio" name="tiposku" id="noSeleccionado" value="Generico">
                                </div>


                                <div id="contenedor-elementos">

                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="sub-title">Modelo</div>
                                        <input type="text" name="modelo" id="modelo" class="form-control"
                                            readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="sub-title">Tipo</div>
                                        <input type="text" name="tip" id="tip" class="form-control">

                                    </div>

                                    <div class="col-md-4">
                                        <div class="sub-title">Cantidad</div>
                                        <input type="text" name="cantidad" id="cantidad" class="form-control"
                                            required data-error-message="El campo cantidad es requerido">
                                        <span class="error-message"></span>
                                    </div>
                                </div>


                                <div class="form-group col-md-12">
                                    <div class="row">
                                        <div class="sub-title">Notas</div>
                                        <textarea name="notas" id="notas" rows="3" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="sub-title">Foto / Archivo</div>
                                        <input type="file" name="foto1" id="foto1" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="sub-title">Foto / Archivo</div>
                                        <input type="file" name="foto2" id="foto2" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="sub-title">Foto / Archivo</div>
                                        <input type="file" name="foto3" id="foto3" class="form-control">
                                    </div>
                                </div>
                                <div class="row custom-row">
                                    <div class="col-md-4">
                                        <div class="sub-title">Foto / Archivo</div>
                                        <input type="file" name="foto4" id="foto4" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="sub-title">Foto / Archivo</div>
                                        <input type="file" name="foto5" id="foto5" class="form-control">
                                    </div>
                                </div>
                                <div class="row custom-btn-row">
                                    <button type="button" class="btn btn-primary" id="agregarProducto">
                                        <span class="glyphicon glyphicon-plus-sign"></span> Agregar producto
                                    </button>
                                </div>

                                <div id="reporteProductos">
                                    <br>
                                    @if ($cuantosProductosRfq == 0)
                                        <br>

                                        <div class="alert alert-warning" role="alert" align="center">
                                            <label for="">Sin productos agregados</label>
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover"
                                                id="dataTables-example" style="width:90%" align="center">
                                                <thead>
                                                    <tr style="background-color: #D6EAF8;">
                                                        <th class="fila">Existente</th>
                                                        <th class="fila">SKU</th>
                                                        <th class="fila">Categoría</th>
                                                        <th class="fila">Tipo</th>
                                                        <th class="fila">Modelo</th>
                                                        <th class="fila">Cantidad</th>
                                                        <th class="fila">Fotos / Archivos</th>
                                                        <th class="fila">Notas</th>
                                                        <th class="fila">Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($consultaProductosRfq as $c)
                                                        <tr>
                                                            @if ($c->tipo == 'SKU')
                                                                <td>Si</td>
                                                            @else
                                                                <td>No</td>
                                                            @endif
                                                            @if ($c->tipo == 'SKU')
                                                                <td>{{ $c->sku }}</td>
                                                            @else
                                                                <td>{{ $c->skugenerico }}</td>
                                                            @endif
                                                            <td>{{ $c->tipoRefa }}</td>
                                                            <td>{{ $c->tip }}</td>
                                                            <td>{{ $c->modelo }}</td>
                                                            <td>{{ $c->cantidad }}</td>
                                                            @if (
                                                                $c->foto1 == 'Sin foto' &&
                                                                    $c->foto2 == 'Sin foto' &&
                                                                    $c->foto3 == 'Sin foto' &&
                                                                    $c->foto4 == 'Sin foto' &&
                                                                    $c->foto5 == 'Sin foto')
                                                                <td style="color:red" align="center">Sin fotos / archivos
                                                                </td>
                                                            @else
                                                                <td align="center">

                                                                    <form action='' method='POST'
                                                                        enctype='application/x-www-form-urlencoded'>
                                                                        @csrf
                                                                        <input type="hidden"
                                                                            value="{{ $c->idRfqDetalle }}"
                                                                            name="idRfqDetalle">
                                                                        <input type="hidden" value="{{ $c->idRfq }}"
                                                                            name="idRfq">
                                                                        <a href="{{ route('verFotosRfq') }}"
                                                                            class="verfotos">Ver</a>
                                                                    </form>


                                                                </td>
                                                            @endif

                                                            <td>{{ $c->notas }}</td>
                                                            <td>
                                                                <div id="botonesProds"
                                                                    style="display: flex; justify-content: center;">
                                                                    <form action='' method='POST'
                                                                        enctype='application/x-www-form-urlencoded'>
                                                                        @csrf
                                                                        <input type="hidden"
                                                                            value="{{ $c->idRfqDetalle }}"
                                                                            name="idRfqDetalle">
                                                                        <input type="hidden" value="{{ $c->idRfq }}"
                                                                            name="idRfq">
                                                                        <input type="hidden"
                                                                            value="{{ $consulta->idmarca }}"
                                                                            name="rfqmarca">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-info editarProductoRfq"
                                                                            style="width:40px; height:35px; margin-right: 5px;">
                                                                            <i class="ace-icon fa fa-pencil bigger"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                    </form>
                                                                    <form action='' method='POST'
                                                                        enctype='application/x-www-form-urlencoded'>
                                                                        @csrf
                                                                        <input type="hidden"
                                                                            value="{{ $c->idRfqDetalle }}"
                                                                            name="idRfqDetalle">
                                                                        <input type="hidden" value="{{ $c->idRfq }}"
                                                                            name="idRfq">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-danger borrarProductoRfq"
                                                                            style="width:40px; height:35px; margin-left: 5px;">
                                                                            <i class="fa fa-trash-o"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>


                            </div>
                        </div>
                        {{ Form::close() }}
                        <br><br>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div id="modalFotos"></div>
    <div id="modalEditar"></div>

    <script type="text/javascript">
        $(document).ready(function() {
            function habilitarGuardar() {
                const idmarca = $("#idmarca").val();
                const idc = $("#idc").val();
                const ids = $("#ids").val();
                const ciudad = $("#ciudad").val();
                const cmgrfq = $("#cmgrfq").val();
                const contacto = $("#contacto").val();
                const solicitud = $("#solicitud").val();
                const fechaEntrega = $("#fechaEntrega").val();
                const notasrfq = $("#notasrfq").val();

                if (
                    idmarca !== "" &&
                    idc !== "" &&
                    ids !== "" &&
                    ciudad !== "" &&
                    cmgrfq !== "" &&
                    contacto !== "" &&
                    solicitud !== "" &&
                    fechaEntrega !== ""
                ) {
                    $("#guardar").prop("disabled", false);
                } else {
                    $("#guardar").prop("disabled", true);
                }
            }

            $("#idmarca, #idc, #ids, #ciudad, #cmgrfq, #contacto, #solicitud, #fechaEntrega, #notasrfq").on("input",
                habilitarGuardar);

            habilitarGuardar();


            $("#idc").change(function() {
                $("#guardar").prop("disabled", true);
                $("#ids").load('{{ url('comboSucursal') }}' + '?r=' + Date.now() + '&idc=' + this.options[
                    this
                    .selectedIndex].value);
                // alert("mensaje correcto")

                $('#ids').empty();
                $('#ids').append($('<option>', {
                    value: "",
                    text: 'Seleccione sucursal'
                }));
                document.getElementById("ciudad").value = "";
                document.getElementById("cmgrfq").value = "";
                $('#contacto').empty();
                $('#contacto').append($('<option>', {
                    value: "",
                    text: 'Seleccione contacto'
                }));
            });

            $("#ids").change(function() {
                $("#contacto").load('{{ url('comboContacto') }}' + '?r=' + Date.now() + '&ids=' + this
                    .options[
                        this
                        .selectedIndex].value);
                var ids_value = $('#ids').val() // Tomamos el valor del select por su id = ids
                $.get('{{ route('obtCiudad') }}', { //Hacer llamada get a la ruta que tiene logica para obtener ciudad
                    id: ids_value // envíamos el id de la sucursal seleccionada
                }, function(ciudad) {
                    var ciudad_value = ciudad.calle + ', ' + ciudad.colonia + ', ' + ciudad
                        .num; // Concatenamos la direccion en una variable
                    $('#ciudad').val(
                        ciudad_value); // Cambiamos el value del input con id=ciudad
                });

                $.get('{{ route('obtGcmidMarca') }}', {
                    id: ids_value, // enviamos el id de la sucursal seleccionada
                    idmarca: $('#idmarca').val() // enviamos el id de la marca seleccionada
                }, function(data) {
                    var $cmgrfq_value = '{{ $inicial . date('ymd') }}' + '_' + data.gcmid +
                        '_' + data.marca; // Concatenamos para tener la cadena 
                    $('#cmgrfq').val($cmgrfq_value);
                });

                // alert("mensaje correcto")
            });

            $("#idmarca").on('change', function() {
                var idmarca_value = $(this).val();
                var ids_value = $("#ids").val();

                if ($.trim(idmarca_value) != "" && $.trim(ids_value) != "") {
                    $.get('{{ route('obtGcmidMarca') }}', {
                        id: ids_value, // enviamos el id de la sucursal seleccionada
                        idmarca: idmarca_value // enviamos el id de la marca seleccionada
                    }, function(data) {
                        var $cmgrfq_value = '{{ $inicial . date('ymd') }}' + '_' + data
                            .gcmid +
                            '_' + data.marca; // Concatenamos para tener la cadena 
                        $('#cmgrfq').val($cmgrfq_value);
                    });
                }
            });


            $('#guardar').on('click', function() {
                $('#formularioModifRfq').submit();
            });

            // Obtener el contenedor de elementos
            var contenedorElementos = $('#contenedor-elementos');

            function generarContenidoAdicionalSi() {
                // Generar contenido adicional para "Si"
                contenedorElementos.html(`
        <div class="sub-title">Categoría</div>
        <select name="tipo" id="tipo" class="form-control" required data-error-message="El campo Categoría es requerido">
                                        <option value="">Seleccione Categoría</option>
                                        @foreach ($tipos as $tipo)
                                            <option value="{{ $tipo->idTipoRefacciones }}"
                                                {{ old('tipo') == $tipo->idTipoRefacciones ? 'selected' : '' }}>
                                                {{ $tipo->nombreTipoRefaccion }}
                                            </option>
                                        @endforeach
                                    </select>
        <div class="error-message">@error('tipo'){{ $message }}@enderror</div>

        <div class="sub-title">SKU</div>
        <select name="sku" id="sku" class="form-control" required data-error-message="El campo SKU es requerido">>
            <option value="">Seleccione SKU</option>
        </select>
        <span class="error-message"></span>       
        
        <input type="hidden" name="sinsku" id="sinsku" class="form-control">
    `);

                $('#modelo').val(''); // Dejar el campo #modelo vacío

                $("#tipo").change(function() {
                    document.getElementById("modelo").value = "";
                    $('#sku').empty();
                    $('#sku').append($('<option>', {
                        value: "",
                        text: 'Seleccione SKU'
                    }));
                    var tipo_value = $(this).val();
                    $("#sku").load('{{ url('comboSKU') }}' + '?r=' + Date.now() + '&tipo=' + this
                        .options[
                            this
                            .selectedIndex].value);
                });

                $('#sku').on('change', function() {
                    $('#modelo').val(""); // Borrar el contenido del input #modelo

                    var sku_value = $(this).val();
                    if ($.trim(sku_value) != "") {
                        $.get('{{ route('obtModelo') }}', {
                            id: sku_value // Envía el id del sku seleccionado
                        }, function(modelo) {
                            $('#modelo').val(modelo);
                        });
                    }
                });
            }

            function generarContenidoAdicionalNo() {
                // Generar contenido adicional para "No"
                contenedorElementos.html(`
        <input type="hidden" name="tipo" id="tipo" class="form-control" required data-error-message="El campo Tipo es requerido">

        <input type="hidden" name="sku" id="sku" class="form-control">

        <div class="sub-title">SKU</div>
        <input type="text" name="sinsku" id="sinsku" class="form-control">
        <span class="error-message"></span>
    `);

                $('#sinsku').val('Genérico');

                var id_marca_generico = 57;
                $.get('{{ route('obtGenerico') }}', {
                    id_marca: id_marca_generico
                }, function(generico) {
                    $('#tipo').val(generico.idTipoRefacciones);

                    var id_tipo = $('#tipo')
                        .val(); // Corrección: agregar el símbolo "$" antes de abrir el paréntesis
                    $.get('{{ route('obtGenericoSku') }}', {
                        id: id_tipo
                    }, function(sku) {
                        $('#sku').val(sku);
                    });
                });
            }


            generarContenidoAdicionalSi();

            $('#siSeleccionado').prop('checked', true); // Marcar el radio "siSeleccionado" inicialmente

            $('#siSeleccionado').change(function() {
                if ($(this).is(':checked')) {
                    $('#modelo').attr('readonly', true);
                    generarContenidoAdicionalSi();
                }
            });

            $('#noSeleccionado').change(function() {
                if ($(this).is(':checked')) {
                    $('#modelo').attr('readonly', false);
                    generarContenidoAdicionalNo();
                }
            });

            jQuery("#cantidad").on("input", function() {
                jQuery(this).val(jQuery(this).val().replace(/[^0-9]/g, ''));
            })

            $("#reporteProductos").on("click", ".borrarProductoRfq", function() {
                $("#reporteProductos").load('{{ url('borrarProductosRfq') }}' + '?r=' + Date.now() + $(
                    this).closest('form').serialize());
            });

            $('#reporteProductos').on('click', '.verfotos', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                var url = '{{ route('verFotosRfq') }}' + '?' + form.serialize();
                $('#modalFotos').load(url);
            });

            // Validar el formulario
            function validarCampos() {
                var camposObligatorios = document.querySelectorAll('[required]:not(:disabled)');
                var camposVacios = [];

                camposObligatorios.forEach(function(campo) {
                    if (!campo.value && !campo.closest('.hidden')) {
                        var mensajeError = campo.dataset.errorMessage || 'Este campo es obligatorio';
                        campo.setCustomValidity(mensajeError);
                        if (campo.nextElementSibling) {
                            var errorSpan = campo.nextElementSibling;
                            errorSpan.textContent = mensajeError;
                        }
                    } else {
                        campo.setCustomValidity('');
                        if (campo.nextElementSibling) {
                            var errorSpan = campo.nextElementSibling;
                            errorSpan.textContent = '';
                        }
                    }
                });

                if (camposVacios.length > 0) {
                    return false;
                } else {
                    return true;
                }
            }


            $('#agregarProducto').click(function(e) {
                e.preventDefault();

                if (validarCampos()) {
                    var f = this;
                    var formData = new FormData(document.getElementById("formularioSkus"));
                    $.ajax({
                            url: "{{ route('agregarProductosRfq') }}",
                            type: "post",
                            dataType: "html",
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                        })
                        .done(function(res) {
                            $("#reporteProductos").html(res);
                            $("#formularioSkus")[0].reset();
                            //$('#siSeleccionado').prop('checked', true).trigger('change');

                            generarContenidoAdicionalSi();
                            $('#siSeleccionado').prop('checked', true);

                            // Asignar evento de borrar a los nuevos botones
                            $(".borrarProductoRfq").click(function() {
                                $("#reporteProductos").load('{{ url('borrarProductosRfq') }}' +
                                    '?r=' + Date.now() + $(this).closest('form').serialize()
                                );
                            });
                        }).fail(function(jqXHR, textStatus, errorThrown) {});
                }
            });

            $(".editarProductoRfq").click(function(e) {
                $('#modalEditar').load('{{ url('editarProductoRfq') }}' + '?' + $(this).closest(
                    'form').serialize());
                e.preventDefault();
            });

        });
    </script>


    <style>
        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        /* Style the buttons inside the tab */
        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            font-size: 17px;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current tablink class */
        .tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }

        .row-boton {
            display: flex;
            justify-content: center;
        }

        .error-message {
            color: red;
            font-size: 14px;
            font-style: italic;
        }

        span.error-message {
            color: red;
            font-size: 14px;
            font-style: italic;
        }

        .custom-row {
            display: flex;
            justify-content: space-around;
        }

        .row.custom-btn-row {
            display: flex;
            justify-content: space-around;
        }

        .row.custom-btn-row {
            margin-top: 20px;
        }

        textarea {
            resize: none;
        }

        .fila {
            width: 80px;
            text-align: center;
        }

        td {
            text-align: center;
        }

        label.error {
            color: red;
            font-size: 14px;
            font-style: italic;
        }

        #botonesProds {
            white-space: nowrap;
        }

        .editarProductoRfq,
        .borrarProductoRfq {
            display: inline-block;
            margin-right: 5px;
        }
    </style>
@stop
