@extends('principal')
@section('contenido')
    <script type="text/javascript">
        $(document).ready(function() {

            $("#guardar").click(function() {
                $("#formularioParteVenta").validate({
                    onclick: false,
                    onkeyup: false,
                    ignore: [],
                    rules: {
                        idMarcaRefa: {
                            required: true
                        },
                        idTipoRefacciones: {
                            required: true
                        },
                        numeroParte: {
                            required: true
                        },
                        cantidad: {
                            required: true
                        },
                    },
                    messages: {
                        idMarcaRefa: {
                            required: 'Ingresa la marca'
                        },
                        idTipoRefacciones: {
                            required: 'Ingresa tipo de refacción'
                        },
                        numeroParte: {
                            required: 'Ingresa el número de parte'
                        },
                        cantidad: {
                            required: 'Ingresa una cantidad'
                        },
                    },
                    errorPlacement: function(error, element) {
                        element.siblings('.error-message').remove();
                        element.after('<span class="error-message">' + error.text() +
                            '</span>');
                    }
                });
            });

        });
    </script>
    {{ Form::open(['route' => 'guardarParteVenta', 'id' => 'formularioParteVenta', 'files' => true]) }}
    {{ Form::token() }}
    <div class="col-xs-12">
        <div class="panel panel-default" style="margin-top:-55px">
            <div class="panel-heading">
                <h1>Partes para venta</h1>
            </div>
            <div class="panel-body">
                <div class="form-group col-md-6">
                    <div class="sub-title">Nombre SKU:</div>
                    <div>
                        {{ Form::text('nombreRefaccion', old('nombreRefaccion'), ['class' => 'form-control', 'placeholder' => 'Ejemplo: Electrico', 'id' => 'nombreRefaccion']) }}
                    </div>
                    <div class="sub-title">*Marca:</div>
                    <div>
                        @if ($errors->first('idMarcaRefa'))
                            <i> {{ $errors->first('idMarcaRefa') }}</i>
                        @endif
                        <select name='idMarcaRefa' class="form-control" id="idMarcaRefa">
                            <option value="">Seleccione una marca</option>
                            @foreach ($marca as $mr)
                                @if ($mr->activo == 'Si')
                                    <option value='{{ $mr->idMarcaRefa }}'>{{ $mr->marcaRefaccion }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="sub-title">*Tipo SKU:</div>
                    <div>
                        @if ($errors->first('idTipoRefacciones'))
                            <i> {{ $errors->first('idTipoRefacciones') }}</i>
                        @endif
                        <select name='idTipoRefacciones' class="form-control" id="idTipoRefacciones">
                            <option value="">Seleccione un tipo de refacción</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="sub-title">*Número SKU:</div>
                            <div>
                                @if ($errors->first('numeroParte'))
                                    <i> {{ $errors->first('numeroParte') }}</i>
                                @endif
                                {{ Form::text('numeroParte', old('numeroParte'), ['class' => 'form-control', 'placeholder' => 'Ejemplo: E-1 ó 1652', 'id' => 'parte']) }}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="sub-title">Código: </div>
                            <div id='codigoRefaccion'>
                                {{ Form::text('codigo', old('codigo'), ['class' => 'form-control', 'id' => 'codigo', 'readonly']) }}
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="sub-title">SKU Equivalente: </div>
                            <div>
                                {{ Form::text('skuEquivalente', old('skuEquivalente'), ['class' => 'form-control', 'placeholder' => 'Ejemplo: E-1 ó 1652']) }}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="sub-title">Semanas de entrega: </div>
                            <div class="">
                                <select name='semanasEntrega' id='semanasEntrega' class="form-control">
                                    <option value="">Seleccionar opción</option>
                                    <option value='Stock'>Stock</option>
                                    <option value='2-4 días hábiles (bussines days)'>2-4 días hábiles (bussines days)
                                    </option>
                                    <option value='De 3 a 5 días(Days)'>De 3 a 5 días(Days)</option>
                                    <option value='5-7 días hábiles (bussines days)'>5-7 días hábiles (bussines days)
                                    </option>
                                    <option value='7-11 días hábiles (bussines days)'>7-11 días hábiles (bussines days)
                                    </option>
                                    <option value='11-15 días hábiles (bussines days)'>11-15 días hábiles (bussines days)
                                    </option>
                                    <option value='4 semanas (Weeks)'>4 semanas (Weeks)</option>
                                    <option value='5 semanas (Weeks)'>5 semanas (Weeks)</option>
                                    <option value='6 semanas (Weeks)'>6 semanas (Weeks)</option>
                                    <option value='7 semanas (Weeks)'>7 semanas (Weeks)</option>
                                    <option value='8 semanas (Weeks)'>8 semanas (Weeks)</option>
                                    <option value='9 semanas (Weeks)'>9 semanas (Weeks)</option>
                                    <option value='10 semanas (Weeks)'>10 semanas (Weeks)</option>
                                    <option value='10 a 12 semanas (Weeks)'>10 a 12 semanas (Weeks)</option>
                                    <option value='12 a 14 semanas (Weeks)'>12 a 14 semanas (Weeks)</option>
                                    <option value='13 a 15 semanas (Weeks)'>13 a 15 semanas (Weeks)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="sub-title">Número de serie:</div>
                            <div>
                                {{ Form::text('serie', old('serie'), ['class' => 'form-control', 'placeholder' => 'Ejemplo: ABC123165-2']) }}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="sub-title">Modelo: </div>
                            <div>
                                {{ Form::text('modelo', old('modelo'), ['class' => 'form-control', 'placeholder' => 'Ejemplo: ABC123165']) }}
                            </div>
                        </div>
                    </div>
                    <div class="sub-title">Nota interna para venta: </div>
                    <div>
                        {{ Form::text('notaInterna', old('notaInterna'), ['class' => 'form-control', 'placeholder' => 'Agregar información adicional']) }}
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="sub-title">Presentación:</div>
                            <div>
                                Pieza {{ Form::radio('presentacion', 'Pieza', true) }}&nbsp;&nbsp;&nbsp;
                                Caja {{ Form::radio('presentacion', 'Caja') }}&nbsp;&nbsp;&nbsp;
                                Equipo {{ Form::radio('presentacion', 'Equipo') }}&nbsp;&nbsp;&nbsp;
                                Servicio {{ Form::radio('presentacion', 'Servicio') }}&nbsp;&nbsp;&nbsp;
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="sub-title">Unidades por presentación:</div>
                            <div>
                                {{ Form::text('unidades', old('unidades'), ['class' => 'form-control', 'placeholder' => 'Ejemplo: 12']) }}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="sub-title">Códigos aduanales</div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Código HTS:</div>
                                    {{ Form::text('codigoHTS', old('codigoHTS'), ['id' => 'codigoHTS', 'class' => 'form-control', 'placeholder' => 'Ejemplo: 8481.50.20']) }}
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Valor HTS (%):</div>
                                    {{ Form::text('valorHTS', old('valorHTS'), ['id' => 'valorHTS', 'class' => 'form-control', 'placeholder' => 'Ejemplo: 2.1']) }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Código IGI:</div>
                                    {{ Form::text('codigoIGI', old('codigoIGI'), ['id' => 'codigoIGI', 'class' => 'form-control', 'placeholder' => 'Ejemplo: 8481.50.21']) }}
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Valor IGI (%):</div>
                                    {{ Form::text('valorIGI', old('valorIGI'), ['id' => 'valorIGI', 'class' => 'form-control', 'placeholder' => 'Ejemplo: 15']) }}
                                </div>
                            </div>

                            <div class="sub-title">Dimensiones y peso</div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="sub-title mr-2">Largo (L):</div>
                                    <div class="contenedor-linea">
                                        {{ Form::text('largoInch', old('largoInch'), ['id' => 'largoInch', 'class' => 'form-control mb-2', 'placeholder' => 'Ejemplo: 9.1']) }}
                                        inch.
                                        {{ Form::text('largoCm', old('largoCm'), ['id' => 'largoCm', 'class' => 'form-control ml-2 mb-2', 'readonly']) }}
                                        cm.
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="sub-title mr-2">Ancho (W):</div>
                                    <div class="contenedor-linea">
                                        {{ Form::text('anchoInch', old('anchoInch'), ['id' => 'anchoInch', 'class' => 'form-control mb-2', 'placeholder' => 'Ejemplo: 10.2']) }}
                                        inch.
                                        {{ Form::text('anchoCm', old('anchoCm'), ['id' => 'anchoCm', 'class' => 'form-control ml-2 mb-2', 'readonly']) }}
                                        cm.
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="sub-title mr-2">Alto (H):</div>
                                    <div class="contenedor-linea">
                                        {{ Form::text('altoInch', old('altoInch'), ['id' => 'altoInch', 'class' => 'form-control mb-2', 'placeholder' => 'Ejemplo: 30']) }}
                                        inch.
                                        {{ Form::text('altoCm', old('altoCm'), ['id' => 'altoCm', 'class' => 'form-control ml-2 mb-2', 'readonly']) }}
                                        cm.
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="sub-title mr-2">Peso:</div>
                                    <div class="contenedor-linea">
                                        {{ Form::text('pesoLb', old('pesoLb'), ['id' => 'pesoLb', 'class' => 'form-control mb-2', 'placeholder' => 'Ejemplo: 15.5']) }}
                                        lb.
                                        {{ Form::text('pesoKg', old('pesoKg'), ['id' => 'pesoKg', 'class' => 'form-control ml-2 mb-2', 'readonly']) }}
                                        kg.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="form-group col-md-6">
                <div class="sub-title">Ubicación:</div>
                <div>
                    {{ Form::text('ubicacion', old('ubicacion'), ['class' => 'form-control', 'placeholder' => 'Ejemplo: Bodega']) }}
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="sub-title">Precio unitario compra(pesos):</div>
                        <div>
                            {{ Form::text('precioCompraPe', old('precioCompraPe'), ['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00']) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="sub-title">Precio unitario venta(pesos):</div>
                        <div>
                            {{ Form::text('precioVentaPe', old('precioVentaPe'), ['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00']) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="sub-title">Precio unitario compra(dolares):</div>
                        <div>
                            {{ Form::text('precioCompraDol', old('precioCompraDol'), ['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00']) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="sub-title">Precio unitario venta(dolares):</div>
                        <div>
                            {{ Form::text('precioVentaDol', old('precioVentaDol'), ['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00']) }}
                        </div>
                    </div>
                </div>
                <div class="sub-title">Fecha de actualización</div>
                <div>
                    {{ Form::date('fechaActualizacion', \Carbon\Carbon::now()->setTimezone('America/Mexico_City'), ['class' => 'form-control','readonly']) }}
                </div>

                <div class="sub-title">Estatus:</div>
                @if ($errors->first('estatus'))
                    <i> {{ $errors->first('estatus') }}</i>
                @endif
                <div>
                    Stock {{ Form::radio('estatus', 'Stock', true) }}&nbsp;&nbsp;&nbsp;
                    Solicitado {{ Form::radio('estatus', 'Solicitado') }}&nbsp;&nbsp;&nbsp;
                    Enviado a reparar {{ Form::radio('estatus', 'Enviado a reparar') }}
                </div><br>
                <div class="sub-title">Tipo producto:</div>
                <div>
                    @if ($errors->first('tipoProducto'))
                        <i> {{ $errors->first('tipoProducto') }}</i>
                    @endif
                    Refurbished y/o Repair
                    {{ Form::radio('tipoProducto', 'Refurbished y/o Repair', true) }}&nbsp;&nbsp;&nbsp;
                    Nuevo {{ Form::radio('tipoProducto', 'Nuevo') }}&nbsp;&nbsp;&nbsp;
                </div>
                <div class="sub-title">* Cantidad en stock:</div>
                <div>
                    @if ($errors->first('cantidad'))
                        <i> {{ $errors->first('cantidad') }}</i>
                    @endif
                    {{ Form::text('cantidad', old('cantidad'), ['class' => 'form-control', 'placeholder' => 'Ejemplo: 3', 'id' => 'cantidad']) }}
                </div><br>

                <div class="sub-title">Exclusivo para paquete:</div>
                <div>
                    @if ($errors->first('parapaquete'))
                        <i> {{ $errors->first('parapaquete') }}</i>
                    @endif
                    Si {{ Form::radio('parapaquete', 'Si') }}&nbsp;&nbsp;&nbsp;
                    No {{ Form::radio('parapaquete', 'No', true) }}&nbsp;&nbsp;&nbsp;
                </div>

            </div>

        </div>
        <center> {{ Form::submit('Guardar', ['class' => 'btn  btn-success', 'id' => 'guardar']) }}
        </center><br>
    </div>

    </div>

    <script>
        $("#idMarcaRefa").change(function() {
            // alert("hola");
            $('#codigo').val('');
            $("#idTipoRefacciones").load('{{ url('comboTipoRefaccion') }}' + '?r=' + Date.now() + '&idMarcaRefa=' +
                this.options[this.selectedIndex].value);
        });
        $("#parte, #idTipoRefacciones").change(function() {
            // alert("asf");
            $("#codigoRefaccion").load('{{ url('generarCodigo') }}' + '?' + $(this).closest('form').serialize());
        });

        $(document).ready(function() {

            jQuery("#codigoHTS").on("input", function() {
                this.value = this.value.replace(/^(.{10}).*$/, '$1');
            });


            jQuery("#valorHTS").on("input", function() {
                this.value = this.value.replace(/^(\d{0,2}(\.\d{0,1})?)?.*/, '$1');
            });
            jQuery("#codigoIGI").on("input", function() {
                this.value = this.value.replace(/^(.{10}).*$/, '$1');
            });

            jQuery("#valorIGI").on("input", function() {
                this.value = this.value.replace(/^(\d{0,2}(\.\d{0,1})?)?.*/, '$1');
            });


            jQuery("#largoInch").on("input", function() {
                this.value = this.value.replace(/^(\d{0,10}(\.\d{0,1})?)?.*/, '$1');
            });


            jQuery("#anchoInch").on("input", function() {
                this.value = this.value.replace(/^(\d{0,10}(\.\d{0,1})?)?.*/, '$1');
            });

            jQuery("#altoInch").on("input", function() {
                this.value = this.value.replace(/^(\d{0,10}(\.\d{0,1})?)?.*/, '$1');
            });

            jQuery("#pesoLb").on("input", function() {
                this.value = this.value.replace(/^(\d{0,10}(\.\d{0,1})?)?.*/, '$1');
            });

            const largoInchInput = document.getElementById('largoInch');
            const largoCmInput = document.getElementById('largoCm');

            largoInchInput.addEventListener('input', function() {
                const largoInchValue = largoInchInput.value;

                if (largoInchValue === '' || isNaN(largoInchValue)) {
                    largoCmInput.value = '';
                } else {
                    const largoCmValue = largoInchValue * 2.54;
                    largoCmInput.value = largoCmValue.toFixed(1);
                }
            });

            const anchoInchInput = document.getElementById('anchoInch');
            const anchoCmInput = document.getElementById('anchoCm');

            anchoInchInput.addEventListener('input', function() {
                const anchoInchValue = anchoInchInput.value;

                if (anchoInchValue === '' || isNaN(anchoInchValue)) {
                    anchoCmInput.value = '';
                } else {
                    const anchoCmValue = anchoInchValue * 2.54;
                    anchoCmInput.value = anchoCmValue.toFixed(1);
                }
            });

            const altoInchInput = document.getElementById('altoInch');
            const altoCmInput = document.getElementById('altoCm');

            altoInchInput.addEventListener('input', function() {
                const altoInchValue = altoInchInput.value;

                if (altoInchValue === '' || isNaN(altoInchValue)) {
                    altoCmInput.value = '';
                } else {
                    const altoCmValue = altoInchValue * 2.54;
                    altoCmInput.value = altoCmValue.toFixed(1);
                }
            });

            const pesoLbInput = document.getElementById('pesoLb');
            const pesoKgInput = document.getElementById('pesoKg');

            pesoLbInput.addEventListener('input', function() {
                const pesoLbValue = pesoLbInput.value;

                if (pesoLbValue === '' || isNaN(pesoLbValue)) {
                    pesoKgInput.value = '';
                } else {
                    const pesoKgValue = pesoLbValue * 0.453592;
                    pesoKgInput.value = pesoKgValue.toFixed(1);
                }
            });
        });
    </script>
    <style>
        input.error {
            border-color: #f00 !important;
        }

        small.required {
            color: #f00;
        }

        textarea.error {
            border-color: #f00 !important;
        }

        .error {
            color: #f00 !important;
        }

        .row .contenedor-linea {
            display: flex;
            align-items: center;
        }

        .error-message {
            color: red;
            font-size: 12px;
        }
    </style>

@stop
