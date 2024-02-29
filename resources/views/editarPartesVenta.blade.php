@extends('principal')
@section('contenido')
    <style>
        .thumbnail {
            border: 1px solid #ddd;
            /* Gray border */
            border-radius: 4px;
            /* Rounded border */
            padding: 5px;
            /* Some padding */
            width: 150px;
            /* Set a small width */
        }

        /* Add a hover effect (blue shadow) */
        .thumbnail:hover {
            box-shadow: 0 0 2px 1px rgba(0, 140, 186, 0.5);
        }
    </style>
    <script type="text/javascript">
        $(".guardarCambios").click(function() {
            // alert("id Refaccion")
            $("#cambiosPagWeb").load('{{ url('cambiosWeb') }}' + '?r=' + Date.now() + $(this).closest('form')
                .serialize());
        });
        $(document).ready(function() {
            $(".cargar").click();

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

            const anchoInchInput = document.getElementById('anchoInch');
            const anchoCmInput = document.getElementById('anchoCm');

            function convertirAncho() {
                const anchoInchValue = anchoInchInput.value;

                if (anchoInchValue === '' | isNaN(anchoInchValue)) {
                    anchoCmInput.value = '';
                } else {
                    const anchoCmValue = anchoInchValue * 2.54;
                    anchoCmInput.value = anchoCmValue.toFixed(1);
                }
            }

            const largoInchInput = document.getElementById('largoInch');
            const largoCmInput = document.getElementById('largoCm');

            function convertirLargo() {
                const largoInchValue = largoInchInput.value;

                if (largoInchValue === '' | isNaN(largoInchValue)) {
                    largoCmInput.value = '';
                } else {
                    const largoCmValue = largoInchValue * 2.54;
                    largoCmInput.value = largoCmValue.toFixed(1);
                }
            }

            const altoInchInput = document.getElementById('altoInch');
            const altoCmInput = document.getElementById('altoCm');

            function convertirAlto() {
                const altoInchValue = altoInchInput.value;

                if (altoInchValue === '' | isNaN(altoInchValue)) {
                    altoCmInput.value = '';
                } else {
                    const altoCmValue = altoInchValue * 2.54;
                    altoCmInput.value = altoCmValue.toFixed(1);
                }
            }

            const pesoLbInput = document.getElementById('pesoLb');
            const pesoKgInput = document.getElementById('pesoKg');

            function convertirPeso() {
                const pesoLbValue = pesoLbInput.value;

                if (pesoLbValue === '' | isNaN(pesoLbValue)) {
                    pesoKgInput.value = '';
                } else {
                    const pesoKgValue = pesoLbValue * 0.453592;
                    pesoKgInput.value = pesoKgValue.toFixed(1);
                }
            }

            convertirAncho();
            convertirLargo();
            convertirAlto();
            convertirPeso();
        });
    </script>
    {{ Form::open(['route' => 'editarPartesVenta', 'files' => true, 'id' => 'formularioParteVenta']) }}
    {{ Form::token() }}

    <div class="col-xs-12">
        <div class="panel panel-default" style="margin-top:-55px">
            <div class="panel-heading">

            </div>
            <div class="panel-body">

                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="cargar" class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                            role="tab" aria-controls="pills-home" aria-selected="true">Información</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#actividades" role="tab"
                            aria-controls="pills-contact" aria-selected="false">Pagina Web</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#envios" role="tab"
                            aria-controls="pills-contact" aria-selected="false">Costos de Envío</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <br>
                        <h1>Partes para venta</h1>
                        <br>
                        <div class="form-group col-md-6">
                            <div class="sub-title">Nombre SKU:</div>
                            <div>
                                {{ Form::text('nombreRefaccion', $consulta->nombreRefaccion, ['class' => 'form-control', 'placeholder' => 'Ejemplo: Electrico', 'id' => 'nombreRefaccion']) }}
                                {{ Form::hidden('idPartesVenta', $consulta->idPartesVenta, ['class' => 'form-control', 'placeholder' => 'Ejemplo: Electrico']) }}
                            </div>
                            <div class="sub-title">* Marca:</div>
                            <div>
                                @if ($errors->first('marca'))
                                    <i> {{ $errors->first('marca') }}</i>
                                @endif
                                <select name='idMarcaRefa' class="form-control" id="idMarcaRefa">
                                    <option value="{{ $idsel }}">{{ $nombreMarca }}</option>
                                    @foreach ($marca as $mr)
                                        @if ($mr->activo == 'Si')
                                            <option value='{{ $mr->idMarcaRefa }}'>{{ $mr->marcaRefaccion }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="sub-title">* Tipo SKU:</div>
                            <div>
                                @if ($errors->first('idTipoRefacciones'))
                                    <i> {{ $errors->first('idTipoRefacciones') }}</i>
                                @endif
                                <select name='idTipoRefacciones' class="form-control" id="idTipoRefacciones">
                                    <option value="{{ $idr }}">{{ $tipoRefaccion }}</option>
                                    @foreach ($refa as $refa)
                                        @if ($refa->activo == 'Si')
                                            <option value='{{ $refa->idTipoRefacciones }}'>
                                                {{ $refa->nombreTipoRefaccion }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">* Número SKU:</div>
                                    <div>
                                        {{ Form::text('numeroParte', $consulta->numeroParte, ['class' => 'form-control', 'placeholder' => 'Ejemplo: E-1 ó 1652', 'id' => 'parte']) }}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Código: </div>
                                    <div id='codigoRefaccion'>
                                        {{ Form::text('codigo', $consulta->codigo, ['class' => 'form-control', 'readonly' => 'true', 'id' => 'codigo']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">SKU Equivalente: </div>
                                    <div>
                                        {{ Form::text('skuEquivalente', $consulta->skuEquivalente, ['class' => 'form-control', 'placeholder' => 'Ejemplo: E-1 ó 1652']) }}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Semanas de entrega: </div> {{-- Aqui debe de ir el trigger del script --}}
                                    <div class="" id='ActFecha-entrega'>
                                        <select name='semanasEntrega' id='semanasEntrega' class="form-control">
                                            @if ($consulta->semanasEntrega != '')
                                                <option value="{{ $consulta->semanasEntrega }}">
                                                    {{ $consulta->semanasEntrega }}</option>
                                            @else
                                                <option value="">Seleccionar opción</option>
                                            @endif
                                            <option value='Stock'>Stock</option>
                                            <option value='2-4 días hábiles (bussines days)'>2-4 días hábiles (bussines
                                                days)</option>
                                            <option value='De 3 a 5 días(Days)'>De 3 a 5 días(Days)</option>
                                            <option value='5-7 días hábiles (bussines days)'>5-7 días hábiles (bussines
                                                days)</option>
                                            <option value='7-11 días hábiles (bussines days)'>7-11 días hábiles (bussines
                                                days)</option>
                                            <option value='11-15 días hábiles (bussines days)'>11-15 días hábiles (bussines
                                                days)</option>
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
                                        {{ Form::text('serie', $consulta->serie, ['class' => 'form-control', 'placeholder' => 'Ejemplo: ABC123165-2']) }}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Modelo: </div>
                                    <div>
                                        {{ Form::text('modelo', $consulta->modelo, ['class' => 'form-control', 'placeholder' => 'Ejemplo: ABC123165']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="sub-title">Nota interna para venta: </div>
                            <div>
                                {{ Form::text('notaInterna', $consulta->notaInterna, ['class' => 'form-control', 'placeholder' => 'Agregar información adicional']) }}
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Presentación:</div>
                                    <div>
                                        @if ($consulta->presentacion == 'Pieza')
                                            Pieza {{ Form::radio('presentacion', 'Pieza', true) }}&nbsp;&nbsp;&nbsp;
                                            Caja {{ Form::radio('presentacion', 'Caja') }}&nbsp;&nbsp;&nbsp;
                                            Equipo {{ Form::radio('presentacion', 'Equipo') }}&nbsp;&nbsp;&nbsp;
                                            Servicio {{ Form::radio('presentacion', 'Servicio') }}&nbsp;&nbsp;&nbsp;
                                        @else
                                            @if ($consulta->presentacion == 'Caja')
                                                Pieza {{ Form::radio('presentacion', 'Pieza') }}&nbsp;&nbsp;&nbsp;
                                                Caja {{ Form::radio('presentacion', 'Caja', true) }}&nbsp;&nbsp;&nbsp;
                                                Equipo {{ Form::radio('presentacion', 'Equipo') }}&nbsp;&nbsp;&nbsp;
                                                Servicio {{ Form::radio('presentacion', 'Servicio') }}&nbsp;&nbsp;&nbsp;
                                            @else
                                                @if ($consulta->presentacion == 'Equipo')
                                                    Pieza {{ Form::radio('presentacion', 'Pieza') }}&nbsp;&nbsp;&nbsp;
                                                    Caja {{ Form::radio('presentacion', 'Caja') }}&nbsp;&nbsp;&nbsp;
                                                    Equipo
                                                    {{ Form::radio('presentacion', 'Equipo', true) }}&nbsp;&nbsp;&nbsp;
                                                    Servicio
                                                    {{ Form::radio('presentacion', 'Servicio') }}&nbsp;&nbsp;&nbsp;
                                                @else
                                                    Pieza {{ Form::radio('presentacion', 'Pieza') }}&nbsp;&nbsp;&nbsp;
                                                    Caja {{ Form::radio('presentacion', 'Caja') }}&nbsp;&nbsp;&nbsp;
                                                    Equipo {{ Form::radio('presentacion', 'Equipo') }}&nbsp;&nbsp;&nbsp;
                                                    Servicio
                                                    {{ Form::radio('presentacion', 'Servicio', true) }}&nbsp;&nbsp;&nbsp;
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Unidades por presentación:</div>
                                    <div>
                                        {{ Form::text('unidades', $consulta->unidades, ['class' => 'form-control', 'placeholder' => 'Ejemplo: 12']) }}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Códigos aduanales</div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="sub-title">Código HTS:</div>
                                            {{ Form::text('codigoHTS', $consulta->codigoHts, ['id' => 'codigoHTS', 'class' => 'form-control', 'placeholder' => 'Ejemplo: 8481.50.20']) }}
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="sub-title">Valor HTS (%):</div>
                                            {{ Form::text('valorHTS', $consulta->valorHts, ['id' => 'valorHTS', 'class' => 'form-control', 'placeholder' => 'Ejemplo: 2.1']) }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="sub-title">Código IGI:</div>
                                            {{ Form::text('codigoIGI', $consulta->codigoIgi, ['id' => 'codigoIGI', 'class' => 'form-control', 'placeholder' => 'Ejemplo: 8481.50.21']) }}
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="sub-title">Valor IGI (%):</div>
                                            {{ Form::text('valorIGI', $consulta->valorIgi, ['id' => 'valorIGI', 'class' => 'form-control', 'placeholder' => 'Ejemplo: 15']) }}
                                        </div>
                                    </div>

                                    <div class="sub-title">Dimensiones y peso</div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="sub-title mr-2">Largo (L):</div>
                                            <div class="contenedor-linea">
                                                {{ Form::text('largoInch', $consulta->largo, ['id' => 'largoInch', 'class' => 'form-control mb-2', 'placeholder' => 'Ejemplo: 9.1']) }}
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
                                                {{ Form::text('anchoInch', $consulta->ancho, ['id' => 'anchoInch', 'class' => 'form-control mb-2', 'placeholder' => 'Ejemplo: 10.2']) }}
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
                                                {{ Form::text('altoInch', $consulta->alto, ['id' => 'altoInch', 'class' => 'form-control mb-2', 'placeholder' => 'Ejemplo: 30']) }}
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
                                                {{ Form::text('pesoLb', $consulta->peso, ['id' => 'pesoLb', 'class' => 'form-control mb-2', 'placeholder' => 'Ejemplo: 15.5']) }}
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
                                {{ Form::text('ubicacion', $consulta->ubicacion, ['class' => 'form-control', 'placeholder' => 'Ejemplo: Bodega']) }}
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Precio unitario compra (pesos):</div> {{-- aqui debe de ir el trigger del script --}}
                                    <div id='ActFecha-cPesos'>
                                        {{ Form::text('precioCompraPe', $consulta->precioCompraPe, ['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00']) }}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Precio unitario venta (pesos):</div> {{-- aqui debe de ir el trigger del script --}}
                                    <div id='ActFecha-vPesos'>
                                        {{ Form::text('precioVentaPe', $consulta->precioVentaPe, ['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Precio unitario compra (dolares):</div> {{-- aqui debe de ir el trigger del script --}}
                                    <div id='ActFecha-cDolares'>
                                        {{ Form::text('precioCompraDol', $consulta->precioCompraDol, ['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00']) }}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Precio unitario venta (dolares):</div> {{-- aqui debe de ir el trigger del script --}}
                                    <div id='ActFecha-vDolares'>
                                        {{ Form::text('precioVentaDol', $consulta->precioVentaDol, ['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="sub-title">Fecha de actualización</div>
                            <div>
                                {{ Form::date('fechaActualizacion', $consulta->fechaActualizacion, ['class' => 'form-control','id' => 'fechaActualizacion','readonly']) }}
                            </div>
                            <div class="sub-title">Estatus:</div> 
                            <div id='ActFecha-estatus'>
                                @if ($consulta->estatus == 'Stock')
                                    Stock {{ Form::radio('estatus', 'Stock', true) }}&nbsp;&nbsp;&nbsp;
                                    Solicitado {{ Form::radio('estatus', 'Solicitado') }}&nbsp;&nbsp;&nbsp;
                                    Enviado a reparar {{ Form::radio('estatus', 'Enviado a reparar') }}
                                @elseif($consulta->estatus == 'Solicitado')
                                    Stock {{ Form::radio('estatus', 'Stock') }}&nbsp;&nbsp;&nbsp;
                                    Solicitado {{ Form::radio('estatus', 'Solicitado', true) }}&nbsp;&nbsp;&nbsp;
                                    Enviado a reparar {{ Form::radio('estatus', 'Enviado a reparar') }}
                                @else
                                    Stock {{ Form::radio('estatus', 'Stock') }}&nbsp;&nbsp;&nbsp;
                                    Solicitado {{ Form::radio('estatus', 'Solicitado') }}&nbsp;&nbsp;&nbsp;
                                    Enviado a reparar {{ Form::radio('estatus', 'Enviado a reparar', true) }}
                                @endif
                            </div><br>
                            <div class="sub-title">Tipo producto:</div>
                            <div>
                                @if ($consulta->tipoProducto == 'Refurbished y/o Repair')
                                    Refurbished y/o Repair
                                    {{ Form::radio('tipoProducto', 'Refurbished y/o Repair', true) }}&nbsp;&nbsp;&nbsp;
                                    Nuevo {{ Form::radio('tipoProducto', 'Nuevo') }}&nbsp;&nbsp;&nbsp;
                                @else
                                    Refurbished y/o Repair
                                    {{ Form::radio('tipoProducto', 'Refurbished y/o Repair') }}&nbsp;&nbsp;&nbsp;
                                    Nuevo {{ Form::radio('tipoProducto', 'Nuevo', true) }}&nbsp;&nbsp;&nbsp;
                                @endif
                            </div>
                            <div class="sub-title">* Cantidad en stock:</div>
                            <div>
                                @if ($errors->first('cantidad'))
                                    <i> {{ $errors->first('cantidad') }}</i>
                                @endif
                                {{ Form::text('cantidad', $consulta->cantidad, ['class' => 'form-control', 'placeholder' => 'Ejemplo: 3', 'id' => 'cantidad']) }}
                            </div><br>

                            <div class="sub-title">Exclusivo para paquete:</div>
                            <div>
                                @if ($errors->first('parapaquete'))
                                    <i> {{ $errors->first('parapaquete') }}</i>
                                @endif
                                @if ($consulta->parapaquete == 'Si')
                                    Si {{ Form::radio('parapaquete', 'Si', true) }}&nbsp;&nbsp;&nbsp;
                                    No {{ Form::radio('parapaquete', 'No') }}&nbsp;&nbsp;&nbsp;
                                @else
                                    Si {{ Form::radio('parapaquete', 'Si') }}&nbsp;&nbsp;&nbsp;
                                    No {{ Form::radio('parapaquete', 'No', true) }}&nbsp;&nbsp;&nbsp;
                                @endif
                            </div>

                        </div>





                    </div>
                    <div class="tab-pane fade" id="actividades" role="tabpanel" aria-labelledby="pills-contact-tab">
                        <br>
                        <h1>Informacion para pagina web</h1>
                        <br>


                        <div class="form-group col-md-6">
                            <div class="sub-title">Aparece en la pagina web:</div>
                            <div>
                                @if ($errors->first('visualWeb'))
                                    <i> {{ $errors->first('visualWeb') }}</i>
                                @endif
                                @if ($consulta->visualWeb == 'Si')
                                    Si {{ Form::radio('visualWeb', 'Si', true) }}&nbsp;&nbsp;&nbsp;
                                    No {{ Form::radio('visualWeb', 'No') }}&nbsp;&nbsp;&nbsp;
                                @else
                                    Si {{ Form::radio('visualWeb', 'Si') }}&nbsp;&nbsp;&nbsp;
                                    No {{ Form::radio('visualWeb', 'No', true) }}&nbsp;&nbsp;&nbsp;
                                @endif

                            </div>
                            <div class="sub-title">Nombre para la pagina web:</div>
                            <div>
                                {{ Form::text('nombreWeb', $consulta->nombreWeb, ['class' => 'form-control', 'placeholder' => 'Nombre', 'id' => 'nombrePagWeb']) }}

                            </div>
                            <div class="sub-title">Descripcion para la pagina web:</div>
                            <div>
                                {{ Form::text('descWeb', $consulta->descWeb, ['class' => 'form-control', 'placeholder' => 'Descripcion', 'id' => 'descripcionPagWeb']) }}

                            </div>
                            <div class="sub-title">Foto para la pagina web:</div>
                            <div>
                                @if ($consulta->fotoWeb != null)
                                    <a target="_blank" href="/archivos/archivosWeb/fotosWeb/{{ $consulta->fotoWeb }}">
                                        <img class="thumbnail"
                                            src="/public/archivos/archivosWeb/fotosWeb/{{ $consulta->fotoWeb }}">
                                    </a>
                                @else
                                @endif
                                <div>
                                    {{ Form::file('fotoWeb', ['class' => 'form-control', 'id' => 'archivoFoto']) }}
                                </div>

                            </div>
                            <div class="form-group col-md-6">
                                <div class="sub-title">Se visualiza el costo:</div>
                                <div>
                                    @if ($errors->first('visualCostoWeb'))
                                        <i> {{ $errors->first('visualWeb') }}</i>
                                    @endif
                                    @if ($consulta->visualWeb == 'Si')
                                        Si {{ Form::radio('visualCostoWeb', 'Si', true) }}&nbsp;&nbsp;&nbsp;
                                        No {{ Form::radio('visualCostoWeb', 'No') }}&nbsp;&nbsp;&nbsp;
                                    @else
                                        Si {{ Form::radio('visualCostoWeb', 'Si') }}&nbsp;&nbsp;&nbsp;
                                        No {{ Form::radio('visualCostoWeb', 'No', true) }}&nbsp;&nbsp;&nbsp;
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="sub-title">Costo:</div>
                                <div>
                                    {{ Form::text('costoWeb', $consulta->costoWeb, ['class' => 'form-control', 'placeholder' => 'Costo', 'id' => 'costoPagWeb']) }}
                                </div>


                            </div>
                            <hr> <!-- linea de division -->
                            <div class="form-group col-md-6">
                                <div class="sub-title">Archivo 1:</div>
                                @if ($consulta->archUnoWeb != null)
                                    <a target="_blank" href="/archivos/archivosWeb/{{ $consulta->archUnoWeb }}">
                                        <i class="fa fa-file-pdf-o fa-5x" aria-hidden="true"
                                            style="color: #750404;background-color: #fff;border-color: #4d42ff59;"></i>
                                    </a>
                                @else
                                @endif
                                <div id="archivoUno">
                                    {{ Form::file('archUnoWeb', ['class' => 'form-control', 'id' => 'archivoUno']) }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">

                                <div class="sub-title">Archivo 2:</div>
                                @if ($consulta->archDosWeb != null)
                                    <a target="_blank" href="/archivos/archivosWeb/{{ $consulta->archDosWeb }}">
                                        <i class="fa fa-file-pdf-o fa-5x" aria-hidden="true"
                                            style="color: #750404;background-color: #fff;border-color: #4d42ff59;"></i>
                                    </a>
                                @else
                                @endif
                                <div id="archivoDos">
                                    {{ Form::file('archDosWeb', ['class' => 'form-control', 'id' => 'archivoDos']) }}
                                </div>
                                <div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="sub-title">Archivo 3:</div>
                                @if ($consulta->archTresWeb != null)
                                    <a target="_blank" href="/archivos/archivosWeb/{{ $consulta->archTresWeb }}">
                                        <i class="fa fa-file-pdf-o fa-5x" aria-hidden="true"
                                            style="color: #750404;background-color: #fff;border-color: #4d42ff59;"></i>
                                    </a>
                                @else
                                @endif
                                <div id="archivoTres">
                                    {{ Form::file('archTresWeb', ['class' => 'form-control', 'id' => 'archivoTres']) }}
                                </div>
                                <div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="sub-title">Archivo 4:</div>
                                @if ($consulta->archCuatroWeb != null)
                                    <a target="_blank" href="/archivos/archivosWeb/{{ $consulta->archCuatroWeb }}">
                                        <i class="fa fa-file-pdf-o fa-5x" aria-hidden="true"
                                            style="color: #750404;background-color: #fff;border-color: #4d42ff59;"></i>
                                    </a>
                                @else
                                @endif
                                <div id="archivoCuatro">
                                    {{ Form::file('archCuatroWeb', ['class' => 'form-control', 'id' => 'archivoCuatro']) }}
                                </div>
                                <div>
                                </div>
                            </div>
                            <div class="sub-title">Caracteristicas:</div>
                            <textarea name="caractWeb" rows="3" class="form-control rounded-0" placeholder="Caracteristicas">{{ $consulta->caractWeb }}</textarea>
                            <div class="sub-title">Descripcion del producto:</div>
                            <textarea name="descLongWeb" rows="3" class="form-control rounded-0" placeholder="Descripcion del producto">{{ $consulta->descLongWeb }}</textarea>
                            <div class="sub-title">Codigo de identificacion:</div>
                            <textarea name="codifWeb" rows="3" class="form-control rounded-0" placeholder="Codigo de identificacion">{{ $consulta->codifWeb }}</textarea>
                        </div>
                        <div class="form-group col-md-6">

                            <div class="sub-title">Datos tecnicos:</div>
                            <textarea name="datosTecWeb" rows="3" class="form-control rounded-0" placeholder="Datos tecnicos">{{ $consulta->datosTecWeb }}</textarea>
                            <div class="sub-title">Diagramas / curvas caracteristicas:</div>
                            <textarea name="diagramWeb" rows="3" class="form-control rounded-0"
                                placeholder="Diagramas / curvas caracteristicas">{{ $consulta->diagramWeb }}</textarea>
                            <div class="sub-title">Simbolos / planos de conexiones:</div>
                            <textarea name="simbolWeb" rows="3" class="form-control rounded-0"
                                placeholder="Simbolos / planos de conexiones">{{ $consulta->simbolWeb }}</textarea>
                            <div class="sub-title">Conexion electrica:</div>
                            <textarea name="consElectrWeb" rows="3" class="form-control rounded-0" placeholder="Conexion electrica">{{ $consulta->consElectrWeb }}</textarea>
                            <div class="sub-title">Dimensiones:</div>
                            <textarea name="dimWeb" rows="3" class="form-control rounded-0" placeholder="Dimensiones">{{ $consulta->dimWeb }}</textarea>
                            <div class="sub-title">Datos para el pedido:</div>
                            <textarea name="datosPedWeb" rows="3" class="form-control rounded-0" placeholder="Datos para el pedido">{{ $consulta->datosPedWeb }}</textarea>
                            <div class="sub-title">Addresses:</div>
                            <textarea name="addressWeb" rows="3" class="form-control rounded-0" placeholder="Addresses">{{ $consulta->addressWeb }}</textarea>




                        </div>
                    </div>
                    {{ Form::close() }}
                    <div class="tab-pane fade" id="envios" role="tabpanel" aria-labelledby="pills-contact-tab">
                        <br>
                        @include('costosEnvioTab')
                    </div>

                    <div id="cambiosPagWeb">
                    </div>
                </div>
            </div>
            <center>
                <center> {{ Form::submit('Guardar cambios', ['class' => 'btn  btn-success', 'id' => 'guardar']) }}
                </center><br>
        </div>
    </div>





    <script>
        $("#idMarcaRefa").change(function() {
            // alert("hola");
            $('#codigo').val('');
            $("#idTipoRefacciones").load('{{ url('comboTipoRefaccion') }}' + '?r=' + Date.now() +
                '&idMarcaRefa=' +
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {

/*     $('#ActFecha-entrega').change(function() {
        let fechaActual = new Date();
        let fechaActualizacion = fechaActual.toLocaleString('en-US', { timeZone: 'America/Mexico_City' });
        $('#fechaActualizacion').val(new Date(fechaActualizacion).toISOString().split('T')[0]);
        console.log(fechaActualizacion);
    }); */
    $('#ActFecha-entrega').change(function() {
    let fechaActual = new Date();
    fechaActual.setHours(0,0,0,0); // Elimina la hora, minutos, segundos y milisegundos
    let fechaActualizacion = fechaActual.toLocaleString('en-US', { timeZone: 'America/Mexico_City' });
    $('#fechaActualizacion').val(new Date(fechaActualizacion).toISOString().split('T')[0]);
    console.log(fechaActualizacion);
    });

    $('#ActFecha-cPesos').change(function() {
        let fechaActual = new Date();
        fechaActual.setHours(0,0,0,0); // Elimina la hora, minutos, segundos y milisegundos
    let fechaActualizacion = fechaActual.toLocaleString('en-US', { timeZone: 'America/Mexico_City' });
    /* fechaActualizacion = fechaActualizacion.substring(0, 10); */
    $('#fechaActualizacion').val(new Date(fechaActualizacion).toISOString().split('T')[0]);
    console.log(fechaActualizacion);
    });

    $('#ActFecha-vPesos').change(function() {
        let fechaActual = new Date();
        fechaActual.setHours(0,0,0,0); // Elimina la hora, minutos, segundos y milisegundos
    let fechaActualizacion = fechaActual.toLocaleString('en-US', { timeZone: 'America/Mexico_City' });
    /* fechaActualizacion = fechaActualizacion.substring(0, 10); */
    $('#fechaActualizacion').val(new Date(fechaActualizacion).toISOString().split('T')[0]);
    console.log(fechaActualizacion);
    });

    $('#ActFecha-cDolares').change(function() {
        let fechaActual = new Date();
        fechaActual.setHours(0,0,0,0); // Elimina la hora, minutos, segundos y milisegundos
    let fechaActualizacion = fechaActual.toLocaleString('en-US', { timeZone: 'America/Mexico_City' });
    /* fechaActualizacion = fechaActualizacion.substring(0, 10); */
    $('#fechaActualizacion').val(new Date(fechaActualizacion).toISOString().split('T')[0]);
    console.log(fechaActualizacion);
    });

    $('#ActFecha-vDolares').change(function() {
        let fechaActual = new Date();
        fechaActual.setHours(0,0,0,0); // Elimina la hora, minutos, segundos y milisegundos
    let fechaActualizacion = fechaActual.toLocaleString('en-US', { timeZone: 'America/Mexico_City' });
    /* fechaActualizacion = fechaActualizacion.substring(0, 10); */
    $('#fechaActualizacion').val(new Date(fechaActualizacion).toISOString().split('T')[0]);
    console.log(fechaActualizacion);
    });

    $('#ActFecha-estatus').change(function() {
        let fechaActual = new Date();
        fechaActual.setHours(0,0,0,0); // Elimina la hora, minutos, segundos y milisegundos
    let fechaActualizacion = fechaActual.toLocaleString('en-US', { timeZone: 'America/Mexico_City' });
    /* fechaActualizacion = fechaActualizacion.substring(0, 10); */
    $('#fechaActualizacion').val(new Date(fechaActualizacion).toISOString().split('T')[0]);
    console.log(fechaActualizacion);
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
