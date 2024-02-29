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
                <h1>Detalle RFQ
                </h1>
            </div>
            <div class="panel-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="cargar" class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#orden"
                            role="tab" aria-controls="pills-home" aria-selected="true">Datos de solicitud</a>
                    </li>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade" id="orden" role="tabpanel" aria-labelledby="pills-home-tab">
                        <!-- DATOS DE ORDEN -->
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="sub-title">Usuario</div>
                                <div>
                                    <input type="text" name="usuarioRfqs" class="form-control" readonly="true"
                                        value="{{ $consulta->nombre }}">
                                </div>

                                <div class="sub-title">Marca</div>
                                <div>
                                    <input type="text" name="idc" class="form-control" readonly="true"
                                        value="{{ $consulta->marcaRefa }}">
                                </div>

                                <div class="sub-title">Cliente</div>
                                <div>
                                    <input type="text" name="idc" class="form-control" readonly="true"
                                        value="{{ $consulta->razonSocial }}">
                                </div>

                                <div class="sub-title">Sucursal:</div>
                                <input type="text" name="ids" class="form-control" readonly="true"
                                    value="{{ $consulta->sucursal }}">

                                <div class="sub-title">Ciudad</div>
                                <div>
                                    <input type="text" name="ciudad" id="ciudad" class="form-control" readonly
                                        value="{{ $consulta->ciudad }}">
                                </div>

                                <div>
                                    <div class="sub-title">CMGRFQ#</div>
                                    <input type="text" name="cmgrfq" id="cmgrfq" class="form-control" readonly
                                        value="{{ $consulta->cmgrfq }}">
                                </div>

                                <div class="sub-title">Contacto:</div>
                                {{-- <select name="contacto" id="contacto" class="form-control" disabled>
                                    @foreach ($contactos as $contacto)
                                        <option value="{{ $contacto->idContactoSuc }}"
                                            {{ $selectedContacto == $contacto->idContactoSuc ? 'selected' : '' }}>
                                            {{ $contacto->contacto }}
                                        </option>
                                    @endforeach
                                </select> --}}
                                <input type="text" name="contacto" id="contacto" class="form-control" readonly
                                    value="{{ $consulta->contacto }}">

                            </div>
                            <div class="form-group col-md-6">
                                <div class="sub-title">Nivel de solicitud</div>
                                <input type="text" name="cmgrfq" id="cmgrfq" class="form-control" readonly
                                    value="{{ $consulta->nivelsolicitud }}">

                                <div class="sub-title"> Fecha Solicitud </div>
                                <div>
                                    <input type="date" name="fechaSolicitud" readonly
                                        value="{{ $consulta->fechasolicitud }}" class="form-control">
                                </div>


                                 <div class="sub-title"> Fecha propuesta entrega a cliente </div>
                                <div>
                                    <input type="date" name="fechapropuesta" readonly
                                        value="{{ $consulta->fechapropuesta }}" class="form-control">
                                </div>

                                @if($consulta->fechaentrega <> "1900-01-01")
                                <div class="sub-title"> Fecha entrega cotización a cliente </div>
                                <div>
                                    <input type="date" name="fechaEntrega" value="{{ $consulta->fechaentrega }}"
                                        class="form-control" min="{{ date('Y-m-d') }}" readonly>
                                </div>
                                @endif

                                
                                @if ($consulta->fechaestimadaentrega)
                                <div class="sub-title">Fecha estimada de entrega</div>
                                    <div>
                                        <input type="date" name="fechaSolicitud" readonly value="{{ $consulta->fechaestimadaentrega }}" class="form-control">
                                    </div>
                                @endif
                                <br>

                                <div class="sub-title"> Notas </div>
                                <div>
                                    <textarea name="notasrfq" id="notasrfq" rows="5" class="form-control" readonly>{{ $consulta->notasrfq }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
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
                                                            <td style="color:red" align="center">Sin fotos / archivos</td>
                                                        @else
                                                            <td align="center">

                                                                <form action='' method='POST'
                                                                    enctype='application/x-www-form-urlencoded'>
                                                                    @csrf
                                                                    <input type="hidden" value="{{ $c->idRfqDetalle }}"
                                                                        name="idRfqDetalle">
                                                                    <input type="hidden" value="{{ $c->idRfq }}"
                                                                        name="idRfq">
                                                                    <a href="{{ route('verFotosRfq') }}"
                                                                        class="verfotos">Ver</a>
                                                                </form>
                                                            </td>
                                                        @endif
                                                        <td>{{ $c->notas }}</td>
                                                    </tr>
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
    <div id="modalFotos"></div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#reporteProductos').on('click', '.verfotos', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                var url = '{{ route('verFotosRfq') }}' + '?' + form.serialize();
                $('#modalFotos').load(url);
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

        .fila {
            width: 80px;
            text-align: center;
        }

        td {
            text-align: center;
        }
    </style>
@stop
