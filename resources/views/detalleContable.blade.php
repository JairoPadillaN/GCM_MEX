@extends('principal')
@section('contenido')

    {{ Form::open(['route' => 'editarContable', 'id' => 'formularioArchivo', 'files' => true]) }}
    {{ Form::token() }}
    <div class="col-xs-12">
        <div class="panel panel-default" style="margin-top:-55px">
            <div class="panel-heading">
                <h1>Registrar Archivos</h1>
            </div>
            <div class="panel-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="cargar" class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                            role="tab" aria-controls="pills-home">Datos de archivo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                            aria-controls="pills-profile" aria-selected="false">Registrar Archivos</a>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        {{-- <form action="{{ route('editarContable') }}" method="POST" enctype="multipart/form-data">
                            @csrf --}}
                        <input type="hidden" value="{{ $consultar->idac }}" name="idac" id="idac">
                        <div class="sub-title">Mes</div>
                        <div>
                            <select name="mes" id="mes" class="form-control">
                                @foreach (['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre', 'Declaración anual'] as $option)
                                    @if ($consultar->mes === $option)
                                        <option value="{{ $option }}" selected>{{ $option }}</option>
                                    @else
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="sub-title">Año</div>
                        <div>
                            <select name="ano" id="ano" class="form-control">
                                @foreach (['2017', '2018', '2019', '2020', '2021', '2022', '2023', '2024', '2025', '2026', '2027', '2028'] as $option)
                                    @if ($consultar->ano === $option)
                                        <option value="{{ $option }}" selected>{{ $option }}</option>
                                    @else
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="sub-title">Empresa</div>
                        <div>
                            <select name="empresa" id="empresa" class="form-control">
                                @foreach (['GCM', 'CYM', 'SURJA', 'CMIN', 'PEDRO', 'YANETH'] as $option)
                                    @if ($consultar->empresa === $option)
                                        <option value="{{ $option }}" selected>{{ $option }}</option>
                                    @else
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endif
                                @endforeach
                            </select>

                        </div>
                        <br>
                        <div class="col-auto">
                            <button type="button" name="guardar" id="guardar" class="btn btn-primary">Actualizar
                                Contable</button>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        {{-- {{ Form::open(['route' => 'guardarArchivos', 'id' => 'formularioArchivo', 'files' => true]) }}
                        @csrf --}}
                        <input type="hidden" value="{{ $consultar->idac }}" name="idac" id="idac">
                        <div class="form-group col-md-8">
                            <div>
                                <div class="row">
                                    <div class="sub-title">Fecha</div>
                                    <div>
                                        {{ Form::date('fecha', old('fecha'), ['class' => 'form-control', 'required' => 'required']) }}
                                        <span class="error-message" id="error-fecha"></span>
                                    </div>

                                    <div class="sub-title">Nombre:</div>
                                    <div>
                                        {{ Form::text('nombre', old('nombre'), ['class' => 'form-control', 'required' => 'required']) }}
                                        <span class="error-message" id="error-nombre"></span>
                                    </div>

                                    <div class="sub-title">Agregado por:</div>
                                    <div>
                                        <input type="text" name="iduser" id="iduser" class="form-control"
                                            readonly="true"
                                            value="{!! Session::get('sesionname') !!} {!! Session::get('sesionpaterno') !!} {!! Session::get('sesionmaterno') !!}"></input>
                                    </div>

                                    <div class="sub-title">Archivo</div>
                                    <div>
                                        {{ Form::file('archivo', ['class' => 'form-control', 'required' => 'required']) }}
                                        <span class="error-message" id="error-archivo"></span>
                                    </div>

                                    <div class="sub-title">Descripción:</div>
                                    <div>
                                        {{ Form::textarea('descripcion', old('descripcion'), ['class' => 'form-control']) }}
                                    </div>

                                </div>

                                <div class="form-group col-md-12">
                                    <br><br>
                                    <div class="row custom-btn-row">
                                        <button type="button" class="btn btn-success" id="agregarArchivo">
                                            <span class="glyphicon glyphicon-plus-sign"></span>Agregar
                                        </button>
                                    </div>
                                </div>
                                <br><br>
                                <div class="form-group col-md-12">
                                    <div id="reportearchivo">
                                        @if ($cuantos == 0)
                                            <br>

                                            <div class="alert alert-warning" role="alert" align="center">
                                                <label for="">Sin archivos agregados este mes...</label>
                                            </div>
                                        @else
                                            <br>
                                            <!-- TIENE CUENTAS -->
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover"
                                                    id="dataTables-example">
                                                    <thead>
                                                        <tr style="background-color: #E8E8E8;">
                                                            <th width="10%">
                                                                <font SIZE=2>Fecha</font>
                                                            </th>
                                                            <th width="15%">
                                                                <font SIZE=2>Nombre</font>
                                                            </th>
                                                            <th width="35%">
                                                                <font SIZE=2>Descripción</font>
                                                            </th>
                                                            <th width="30%">
                                                                <font SIZE=2>Agregado por:</font>
                                                            </th>
                                                            <th width="10%">
                                                                <font SIZE=2>Archivo</font>
                                                            </th>
                                                            <th width="10%">
                                                                <font SIZE=2>Opciones</font>
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

                                                                    <td align="center">
                                                                        <form action='' method='POST'
                                                                            enctype='application/x-www-form-urlencoded'
                                                                            name='frmdo{{ $ca->idac }}'
                                                                            id='frmdo{{ $ca->idac }}' target='_self'>
                                                                            @csrf
                                                                            <input type='hidden'
                                                                                value='{{ $ca->idacd }}'
                                                                                name='idacd' id='idacd'>
                                                                            <input type='hidden'
                                                                                value='{{ $ca->idac }}'
                                                                                name='idac' id='idac'>
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-danger borrar">
                                                                                <i class="fa fa-trash-o"
                                                                                    aria-hidden="true"></i>
                                                                            </button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $(".cargar").click();

            $('#guardar').on('click', function() {
                $('#formularioArchivo').submit();
            });

            $("#reportearchivo").on("click", ".borrar", function() {
                $("#reportearchivo").load('{{ url('borrarDetalle') }}' + '?r=' + Date.now() + $(
                    this).closest('form').serialize());
            });


            function validarCampos() {
                var camposObligatorios = document.querySelectorAll('[required]:not(:disabled)');
                var camposVacios = [];

                camposObligatorios.forEach(function(campo) {
                    if (!campo.value && !campo.closest('.hidden')) {
                        var mensajeError = campo.dataset.errorMessage || 'Este campo es obligatorio';
                        campo.setCustomValidity(mensajeError);
                        camposVacios.push(campo.name);
                        var errorSpan = campo.nextElementSibling;
                        errorSpan.textContent = mensajeError;
                    } else {
                        campo.setCustomValidity('');
                        var errorSpan = campo.nextElementSibling;
                        errorSpan.textContent = '';
                    }
                });

                if (camposVacios.length > 0) {
                    return false;
                } else {
                    return true;
                }
            }

            $('#agregarArchivo').click(function(e) {
                e.preventDefault();
                if (validarCampos()) {
                    var f = this;
                    var formData = new FormData(document.getElementById("formularioArchivo"));
                    $.ajax({
                            url: "{{ route('guardarArchivos') }}",
                            type: "post",
                            dataType: "html",
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                        })
                        .done(function(res) {
                            $("#reportearchivo").html(res);
                            $("#formularioArchivo")[0].reset();

                            $(".borrar").click(function() {
                                $("#reportearchivo").load('{{ url('borrarDetalle') }}' +
                                    '?r=' + Date.now() + $(this).closest('form').serialize()
                                );
                            });
                        }).fail(function(jqXHR, textStatus, errorThrown) {});
                }
            });
        });
    </script>

    <style>
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
    </style>
@stop
