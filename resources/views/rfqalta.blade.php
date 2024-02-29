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
                <h1>Registrar Rfq
                </h1>
            </div>
            <div class="panel-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="cargar" class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#orden"
                            role="tab" aria-controls="pills-home" aria-selected="true">Datos de solicitud</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#producto" role="tab"
                            aria-controls="pills-home" aria-selected="true" disabled>SKU en solicitud</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade" id="orden" role="tabpanel" aria-labelledby="pills-home-tab">
                        <!--Primer tab --->
                        <form action="{{ route('guardarRfq') }}" method="POST" id="formularioRfqAlta">
                            @csrf
                            <div class="form-group col-md-6">
                                {{-- <input type="hidden" name="idrfq" id="idrfq" value="{{ $idsiguiente }}"> --}}
                                <div class="sub-title">Usuario</div>
                                <div>
                                    <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu') !!}">
                                    <input type="text" name="usuarioRfqs" class="form-control" readonly="true"
                                        value="{{ Session::get('sesionname') }} {{ Session::get('sesionpaterno') }} {{ Session::get('sesionmaterno') }}">
                                </div>

                                <div class="sub-title">*Marca</div>
                                <div>
                                    <select name="idmarca" id="idmarca" class="form-control" required>
                                        <option value="" selected>Seleccione marca</option>
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
                                        <option value="">Seleccione cliente</option>
                                        @foreach ($clientes as $cliente)
                                            <option value="{{ $cliente->idc }}"
                                                {{ old('idc') == $cliente->idc ? 'selected' : '' }}>
                                                {{ $cliente->razonSocial }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('idc')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="sub-title">*Sucursal</div>
                                @if ($errors->first('ids'))
                                @endif
                                <div id='combop'>
                                    <select name='ids' id='ids' class="form-control" required>
                                        <option value="">Seleccione sucursal</option>
                                        @foreach ($sucursales as $sucursal => $sucursalNombre)
                                            <option value="{{ $sucursal }}"
                                                {{ old('ids', $selectedSucursal) == $sucursal ? 'selected' : '' }}>
                                                {{ $sucursalNombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ids')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="sub-title">Ciudad</div>
                                <div>
                                    <input type="text" name="ciudad" id="ciudad" class="form-control" readonly
                                        required value="{{ old('ciudad') }}">
                                    @error('ciudad')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="sub-title">CMGRFQ#</div>
                                <div>

                                    <input type="text" name="cmgrfq" id="cmgrfq" class="form-control" readonly
                                        required value="{{ old('cmgrfq') }}">
                                    @error('cmgrfq')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- <input type="hidden" name="consecutivo" id="consecutivo" value="{{ old('consecutivo') }}"> --}}


                                <div class="sub-title">*Contacto</div>
                                @if ($errors->first('contacto'))
                                    <i> {{ $errors->first('contacto') }}</i>
                                @endif
                                <div id='combop'>
                                    <select name='contacto' id='contacto' class="form-control" required>
                                        <option value="">Seleccione contacto</option>
                                        @foreach ($contactos as $contacto => $contactoNombre)
                                            <option value="{{ $contacto }}"
                                                {{ old('contacto', $selectedContacto) == $contacto ? 'selected' : '' }}>
                                                {{ $contactoNombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('contacto')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="sub-title">*Nivel de solicitud</div>

                                    <div id='combop'>
                                        <select name='solicitud' id='solicitud' class="form-control" required>
                                            <option value="">Seleccione nivel de solicitud</option>
                                            <option value="Maquina Parada"
                                                {{ old('solicitud') == 'Maquina Parada' ? 'selected' : '' }}>Máquina
                                                Parada
                                            </option>
                                            <option value="Mariconeando"
                                                {{ old('solicitud') == 'Mariconeando' ? 'selected' : '' }}>Mariconeando
                                            </option>
                                            <option value="Para stock"
                                                {{ old('solicitud') == 'Para stock' ? 'selected' : '' }}>Para stock
                                            </option>
                                        </select>
                                        @error('solicitud')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="sub-title"> Fecha solicitud </div>
                                    <div>
                                        <input type="date" name="fechaSolicitud" readonly value="{{ $fecha->format('Y-m-d') }}" class="form-control">
                                    </div>

                                </div>
                                
                                <div class="row">
                                    <div class="sub-title"> Fecha propuesta entrega a cliente </div>
                                    <div>
                                        <input type="date" name="fechaPropuesta" value="{{ date('Y-m-d') }}"
                                            class="form-control">
                                    </div>

                                </div>

                               

                                <div class="row">
                                    <div class="sub-title"> Notas </div>
                                    <div>
                                        <textarea name="notasrfq" id="notasrfq" rows="5" class="form-control">{{ old('notasrfq') }}</textarea>
                                    </div>
                                </div>

                                <div class="row row-boton">
                                    <input type="submit" id="guardarbtn" value="Crear Kardex" class="btn btn-primary"
                                        disabled>
                                </div>

                        </form>
                    </div>
                    <!--Segundo tab --->
                    <div class="tab-pane fade disabled" id="2">

                    </div>
                </div>
            </div>


        </div>
    </div>
    </div>

    <script type="text/javascript">
        document.getElementById("tab2").querySelector("a").disabled = true;
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            const formulario = document.getElementById("formularioRfqAlta");

            formulario.addEventListener("input", function() {
                if (formulario.checkValidity()) {
                    $("#guardarbtn").prop("disabled",
                        false);
                } else {
                    $("#guardarbtn").prop("disabled",
                        true);
                }
            });
            // $("#idc").on('click', function() {
            //     $('#ids').empty();
            //     $('#ids').append($('<option>', {
            //         value: "",
            //         text: 'Seleccione sucursal'
            //     }));
            //     document.getElementById("ciudad").value = "";
            //     document.getElementById("cmgrfq").value = "";
            //     $('#contacto').empty();
            //     $('#contacto').append($('<option>', {
            //         value: "",
            //         text: 'Seleccione contacto'
            //     }));
            //     var idc_value = $(this).val(); // Tomamos el valor del select por su id = idc
            //     if ($.trim(idc_value) != "") { // Quitamos espacios y si es diferente a "" continua
            //         $.get('{{ route('obtSucursales') }}', { //Hacer llamada get a la ruta que tiene logica para obtener sucursales
            //             idc: idc_value // envíamos el id del cliente seleccionado
            //         }, function(sucursales) {
            //             $('#ids').empty(); // Vacia el contenido del select on id = ids
            //             $('#ids').append($('<option>', {
            //                 value: "",
            //                 text: 'Seleccione sucursal'
            //             }));
            //             $.each(sucursales, function(index, value) { // for each
            //                 $('#ids').append("<option value='" + index + "'>" + value +
            //                     "</option>"
            //                 ); // Agrega elementos al select con metodo append
            //             })
            //             $('#ids').trigger(
            //                 'change'); // Dispara el evento 'change' del select con id = ids
            //         });
            //     }
            // });
            $("#idc").change(function() {
                $('#ids').empty();
                $('#ciudad').val("");
                $('#cmgrfq').val("");
                $('#contacto').empty();
                $("#guardarbtn").prop("disabled",
                    true);
                $("#ids").load('{{ url('comboSucursal') }}' + '?r=' + Date.now() + '&idc=' + this.options[
                    this
                    .selectedIndex].value);
                // alert("mensaje correcto")

                // $('#ids').empty();
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
                    var formattedDate = '{{ $fecha->format('ymd') }}';
                    var $cmgrfq_value = '{{ $inicial }}' + formattedDate + '_' + data.gcmid + '_' + data.marca;
                    $('#cmgrfq').val($cmgrfq_value);
                });

                // alert("mensaje correcto")
            });

            // $('#ids').on('click', function() {
            //     document.getElementById("ciudad").value = "";
            //     document.getElementById("cmgrfq").value = "";
            //     $('#contacto').empty();
            //     $('#contacto').append($('<option>', {
            //         value: "",
            //         text: 'Seleccione contacto'
            //     }));
            //     var ids_value = $(this).val(); // Tomamos el valor del select por su id = ids
            //     if ($.trim(ids_value) != "") {
            //         $.get('{{ route('obtCiudad') }}', { //Hacer llamada get a la ruta que tiene logica para obtener ciudad
            //             id: ids_value // envíamos el id de la sucursal seleccionada
            //         }, function(ciudad) {
            //             var ciudad_value = ciudad.calle + ', ' + ciudad.colonia + ', ' + ciudad
            //                 .num; // Concatenamos la direccion en una variable
            //             $('#ciudad').val(
            //                 ciudad_value); // Cambiamos el value del input con id=ciudad
            //         });

            //         $.get('{{ route('obtGcmidMarca') }}', {
            //             id: ids_value, // enviamos el id de la sucursal seleccionada
            //             idmarca: $('#idmarca').val() // enviamos el id de la marca seleccionada
            //         }, function(data) {
            //             var $cmgrfq_value = '{{ $inicial . date('ymd') }}' + '_' + data.gcmid +
            //                 '_' + data.marca; // Concatenamos para tener la cadena 
            //             $('#cmgrfq').val($cmgrfq_value);
            //         });


            //         $.get('{{ route('obtContactos') }}', { //Hacer llamada get a la ruta que tiene logica para obtener contactos
            //             id: ids_value // envíamos el id de la sucursal seleccionado
            //         }, function(contactos) {
            //             $('#contacto').empty(); // Vacia el contenido del select con id = contacto
            //             $('#contacto').append($('<option>', {
            //                 value: "",
            //                 text: 'Seleccione contacto'
            //             }));
            //             $.each(contactos, function(index, value) { // for each
            //                 $('#contacto').append("<option value='" + index + "'>" + value +
            //                     "</option>"
            //                 ); // Agrega elementos al select con metodo append
            //             })
            //         });
            //     }
            // });

            $("#idmarca").on('change', function() {
                var idmarca_value = $(this).val();
                var ids_value = $("#ids").val();

                if ($.trim(idmarca_value) != "" && $.trim(ids_value) != "") {
                    $.get('{{ route('obtGcmidMarca') }}', {
                        id: ids_value, // enviamos el id de la sucursal seleccionada
                        idmarca: idmarca_value // enviamos el id de la marca seleccionada
                    }, function(data) {
                        // Formatea la fecha almacenada en la variable $fecha
                        var formattedDate = '{{ $fecha->format('ymd') }}';

                        // Concatena la fecha con los otros valores
                        var $cmgrfq_value = '{{ $inicial }}' + formattedDate + '_' + data
                            .gcmid + '_' + data.marca;

                        $('#cmgrfq').val($cmgrfq_value);
                    });
                }
            });
        });
    </script>


    <style>
        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

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

        .tab button:hover {
            background-color: #ddd;
        }

        .tab button.active {
            background-color: #ccc;
        }

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

        #guardarbtn {
            margin-top: 15px;
        }

        button:focus {
            outline: none;
        }

        #pills-profile-tab {
            color: gray
        }

        .btn:focus {
            outline: none;
        }

        .nav-pills .nav-link[disabled] {
            cursor: not-allowed;
            pointer-events: none;
            color: gray;
            text-decoration: none;
        }
    </style>
@stop
