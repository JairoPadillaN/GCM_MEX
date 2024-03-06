@extends('principal')
@section('contenido')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.13.6/dataRender/datetime.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.11.6/sorting/datetime-moment.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.rawgit.com/xdan/datetimepicker/v0.6.3/build/jquery.datetimepicker.min.css">
    <script src="https://cdn.rawgit.com/xdan/datetimepicker/v0.6.3/build/jquery.datetimepicker.full.min.js"></script>
    
    <!-- DataTables CSS y JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>

    <!-- Moment.js -->
    <script src="https://momentjs.com/downloads/moment.min.js"></script>

    <!-- Otros scripts -->
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.9/js/dataTables.fixedHeader.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.bootstrap.min.js"></script>

    <!-- DateTimePicker -->
    <link rel="stylesheet" href="https://cdn.rawgit.com/xdan/datetimepicker/v0.6.3/build/jquery.datetimepicker.min.css">
    <script src="https://cdn.rawgit.com/xdan/datetimepicker/v0.6.3/build/jquery.datetimepicker.full.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">
    

    <script>
        $(document).ready(function() {
            $(".cargar").click();
        });
    </script>
    <!-- vista de Gastos Operativos                       -->
    <div class="col-xs-12">
        <div class="panel panel-default" style="margin-top:-55px">
            <div class="panel-heading">
                <h1>Gastos operativos</h1>
            </div>
            <div class="panel-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="cargar" class="nav-link-active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                            role="tab" aria-controls="pills-home" aria-selected="true">Información del gasto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-agregar"
                            role="tab" aria-controls="pills-contact">Agregar gasto</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-content" id="pills-tabContent" style="margin-top: 10px">

                        <!-- * Tabla de gastos operativos -->
                        <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                            <div class="row" style="width: 100%">
                                <table border="0" cellspacing="5" cellpadding="5" class="date-table">
                                    <tbody>
                                        <tr>
                                            <td class="label-cell" style="font-size: 14px; font-weight:bold;">Fecha Inicial:</td>
                                            <td><input class="date-input" type="text" id="min" name="min"></td>
                                            <td class="label-cell" style="font-size: 14px; font-weight:bold;">Fecha Final:</td>
                                            <td><input class="date-input" type="text" id="max" name="max"></td>
                                            <td><div id="botonExcel"></div></td>
                                            <td class="search-controls" style="font-size: 14px; font-weight:bold;">Buscar:</td>
                                            <td><div id="searchContainer" style="width: 100px;"></div></td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                               
                                
                                
                            </div>
                            <div id="table-gastos-operativos" class="table-responsive">
                                <table id="TablaEX">
                                    <thead>
                                        <tr style="background-color: #E0E0E0">
                                            <th style="background-color: #C5EBFB;">Fecha de pago</th>
                                            <th style="background-color: #C5EBFB;">Beneficiario</th>
                                            <th style="background-color: #C5EBFB;">Forma de pago</th>
                                            <th style="background-color: #C5EBFB;">Cuenta contable</th>
                                            <th style="background-color: #C5EBFB;">Empresa</th>
                                            <th style="background-color: #C5EBFB;">Referencia</th>
                                            <th style="background-color: #C5EBFB;">Etiqueta</th>
                                            <th style="background-color: #C5EBFB;">Descripción</th>
                                            <th style="background-color: #C5EBFB;">Subtotal</th>
                                            <th style="background-color: #C5EBFB;">IVA %</th>
                                            <th style="background-color: #C5EBFB;">Total iva</th>
                                            <th style="background-color: #C5EBFB;">ISR %</th>
                                            <th style="background-color: #C5EBFB;">Total isr</th>
                                            <th style="background-color: #C5EBFB;">Total</th>
                                            <th style="background-color: #C5EBFB;">Total en pesos</th>
                                            <th style="background-color: #C5EBFB;">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($gastos as $gasto)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($gasto->fecha_pago)->format('d/m/Y') }}</td>
                                                <td>{{ $gasto->beneficiario }}</td>
                                                <td>{{ $gasto->forma_pago }}</td>
                                                <td>
                                                    {{ $cuentasData[$gasto->idCuenta] }} </td>
                                                <td>{{ $gasto->empresa }}</td>
                                                <td>{{ $gasto->referencia }}</td>
                                                <td>{{ $gasto->etiquetas }}</td>
                                                <td>{{ $gasto->descripcion }}</td>
                                                <td>${{ number_format($gasto->factura, 2, '.', ',') }}</td>
                                                <td>{{ $gasto->iva }}%</td>
                                                <td>${{ number_format($gasto->total_iva, 2, '.', ',') }}</td>
                                                <td>{{ $gasto->isr }}%</td>
                                                <td>${{ number_format($gasto->total_isr, 2, '.', ',') }}</td>
                                                <td>${{ number_format($gasto->total, 2, '.', ',') }}</td>

                                                <td>{{ $gasto->moneda == 'USD' ? 'MXN $' . number_format($gasto->cambioDolar * $gasto->total, 2, '.', ',') : $gasto->moneda . ' $' . number_format($gasto->total, 2, '.', ',') }}
                                                </td>

                                                <td>
                                                    <div class="text-center">
                                                        @if ($gasto->activo == 'Si')
                                                            <button onclick="confirmarEliminacion({{ $gasto->id }})"
                                                                class="btn btn-xs btn-danger">
                                                                <i class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
                                                            </button>
                                                            <a href="{{ route('EditarGasto', ['idGasto' => $gasto->id]) }}"
                                                                class="btn btn-xs btn-info">
                                                                <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('baja_logica_go', ['id' => $gasto->id]) }}"
                                                                class="btn btn-xs btn-warning">
                                                                <span>Restaurar</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>


                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>

                        <!-- * Formulario para nuevos gastos operativos -->

                        <div class="tab-pane fade" id="pills-agregar" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div class="col-md-12">
                                <div class="sub-title">
                                    <small>*Campos obligatorios</small>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::open(['route' => 'guardarGastoOperativo', 'id' => 'formAltaGastos', 'files' => true]) }}
                                {{ Form::token() }}
                                <div class="sub-title">*Nombre del gasto</div>
                                <div>
                                    {{ Form::text('nombreGasto', old('nombreGasto'), ['class' => 'form-control', 'id' => 'nombreGasto', 'placeholder' => 'Ejemplo: Vuelo']) }}
                                </div>
                                <div class="sub-title">*Beneficiario</div>
                                <div>
                                    {{ Form::text('beneficiario', old('beneficiario'), ['class' => 'form-control', 'id' => 'beneficiario', 'placeholder' => 'Ejemplo: #']) }}
                                </div>
                                <div class="sub-title">*Empresa</div>
                                <div>
                                    <select name="empresa" id="empresa" class="form-control" aria-label="Empresa">
                                        <option value="cym">CYM</option>
                                        <option value="gcm">GCM</option>
                                        <option value="pedro">PEDRO</option>
                                        <option value="surja">SURJA</option>
                                        <option value="yaneth">YANETH</option>
                                    </select>
                                </div>
                                <div class="sub-title">*Cuenta</div>
                                <div>
                                    <select class="form-control" id="cuentas" name ="idCuenta"></select>
                                </div>
                                <div class="sub-title">*Banco</div>
                                <div id="banco_input">
                                    <select name="BANCO" id="banco" class="form-control" aria-label="Banco">
                                        <option selected disabled>Elige una opción</option>
                                        @foreach ($bancos as $banco)
                                            <option value="{{ $banco->idb }}">{{ $banco->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sub-title">*Fecha de pago</div>
                                <div>
                                    {{ Form::date('fecha_pago', old('fecha_pago'), ['class' => 'form-control', 'id' => 'fecha_pago']) }}
                                </div>
                                <div class="sub-title">*Etiqueta</div>
                                <div>
                                    <select name="etiquetas" id="etiquetas" class="form-control">
                                        <option value="">Seleccione etiqueta</option>
                                        <option value="COSTO DE VENTA"
                                            @if (old('etiquetas') == 'COSTO DE VENTA') {{ 'selected' }} @endif>COSTO DE VENTA
                                        </option>
                                        <option value="GASTO ADMINISTRATIVO"
                                            @if (old('etiquetas') == 'GASTO ADMINISTRATIVO') {{ 'selected' }} @endif>GASTO
                                            ADMINISTRATIVO</option>
                                        <option value="GASTO FINANCIERO"
                                            @if (old('etiquetas') == 'GASTO FINANCIERO') {{ 'selected' }} @endif>GASTO FINANCIERO
                                        </option>
                                        <option value="GASTO DE VENTA"
                                            @if (old('etiquetas') == 'GASTO DE VENTA') {{ 'selected' }} @endif>GASTO DE VENTA
                                        </option>
                                        <option value="IMPUESTOS"
                                            @if (old('etiquetas') == 'IMPUESTOS') {{ 'selected' }} @endif>IMPUESTOS</option>
                                        <option value="INTERES"
                                            @if (old('etiquetas') == 'INTERES') {{ 'selected' }} @endif>INTERES</option>
                                    </select>
                                </div>
                                <div class="sub-title">Referencias</div>
                                <div>
                                    {{ Form::text('referencia', old('referencia'), ['class' => 'form-control', 'id' => 'referencia']) }}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <table width="100%">
                                    <tr>
                                        <td colspan="2">
                                            <div class="sub-title">*Moneda</div>
                                        </td>
                                        <td>
                                            <div class="sub-title">Tipo de cambio</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="radio" name="moneda" id="mxn" value="MXN"
                                                checked>MXN</td>
                                        <td><input type="radio" name="moneda" id="usd" value="USD">USD</td>
                                        <td id="cambio_dolar_input" width="60%">
                                            <input type="text" name="cambioDolar" id="cambio_dolar"
                                                class="form-control" value="1.00" readonly>
                                        </td>
                                    </tr>
                                </table>
                                <div class="sub-title">*Metodo de pago</div>
                                <div>
                                    <table width="100%">
                                        <tbody>
                                            <tr>
                                                <td>PPD - Pago en parcialidades o diferido<input type="radio"
                                                        name="metodo_pago" id="formaPago_credito" value="PPD" checked>
                                                </td>
                                                <td>PUE - Pago en una sola exhibicion<input type="radio"
                                                        name="metodo_pago" id="formaPago_efectivo" value="PUE"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="sub-title">*Periodo de pago</div>
                                <div class="credito_dias_input">
                                    <select class="form-control" name="credito_dias" id="credito_dias">
                                        <option value="7" name="credito_dias">7 dias</option>
                                        <option value="10" name="credito_dias">10 dias</option>
                                        <option value="14" name="credito_dias">14 dias</option>
                                        <option value="30" name="credito_dias">30 dias</option>
                                        <option value="45" name="credito_dias">45 dias</option>
                                        <option value="60" name="credito_dias">60 dias</option>
                                    </select>
                                </div>
                                <div class="sub-title">*Forma de pago</div>
                                <div class="forma_pago">
                                    <select name="forma_pago" id="forma_pago" class="form-control">
                                        <option value="01 Efectivo">Efectivo</option>
                                        <option value="02 Cheque nominativo">Cheque nominativo</option>
                                        <option value="03 Transferencia electronica">Transferencia electronica</option>
                                        <option value="04 Tarjeta de credito">Tarjeta de credito</option>
                                        <option value="05 Monedero electronico">Monedero electronico</option>
                                        <option value="06 Dinero electronico">Dinero electronico</option>
                                        <option value="08 Vales de despensa">Vales de despensa</option>
                                        <option value="28 Tarjeta de debito">Tarjeta de debito</option>
                                        <option value="29 Tarjeta de servicios">Tarjeta de servicios</option>
                                        <option value="99 No definir">No definir</option>
                                        <option value="NA No aplica">No aplica</option>
                                        <option value="30 Aplicacion de anticipos">Aplicacion de anticipos</option>
                                        <option value="14 Pago por consignacion">Pago por consignacion</option>
                                        <option value="15 Condonacion">Condonacion</option>
                                        <option value="17 Compensacion">Compensacion</option>
                                        <option value="27 Satisfaccion del acreedor">Satisfaccion del acreedor</option>
                                        <option value="25 Remision de deuda">Remision de deuda</option>
                                    </select>
                                </div>
                                <div class="sub-title">*Subtotal</div>
                                <div>
                                    {{ Form::text('factura', old('factura'), ['class' => 'form-control', 'id' => 'subtotal']) }}
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="sub-title">*IVA</div>
                                        <div>
                                            {{ Form::text('iva', old('iva'), ['class' => 'form-control', 'id' => 'iva']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="sub-title">Total IVA</div>
                                        <div>
                                            {{ Form::text('total_iva', old('total_iva'), ['class' => 'form-control', 'id' => 'total_iva', 'readonly']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="sub-title">*Retencion de ISR</div>
                                        <div>
                                            {{ Form::text('isr', old('isr'), ['class' => 'form-control', 'id' => 'isr']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="sub-title">Total ISR</div>
                                        <div>
                                            {{ Form::text('total_isr', old('total_isr'), ['class' => 'form-control', 'id' => 'total_isr', 'readonly']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="sub-title">Total</div>
                                <div>
                                    {{ Form::text('total', old('total'), ['class' => 'form-control', 'id' => 'total', 'readonly']) }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="sub-title">*Descripcion</div>
                                <div>
                                    <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="5"></textarea>
                                </div>
                                <center style="margin-top: 10px">
                                    {{ Form::submit('Guardar', ['class' => 'btn btn-default', 'id' => 'btn_guardar', 'style' => 'width: 15%']) }}
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- La etiqueta meta es necesaria si la peticion es de tipo POST -->
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script src="{{ asset('assets/js/CosmoScript.js') }}"></script>

            <script>
                function confirmarEliminacion(id) {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: 'No podrás revertir esto',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminarlo'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'baja_logica_go/' + id;
                        }
                    });
                }
            </script>
            <script>
                $(document).ready(function() {
                    //Siempre se debe crear la funcion de filtrado de fechas antes del inicio del script del datatable
                    DataTable.ext.search.push(function(settings, data, dataIndex) {
                        let minDateStr = minDate.val();
                        let maxDateStr = maxDate.val();
                        let dateStr = data[0];
                        let minDateMoment = moment(minDateStr, 'DD/MM/YYYY', true);
                        let maxDateMoment = moment(maxDateStr, 'DD/MM/YYYY', true);
                        let dateMoment = moment(dateStr, 'DD/MM/YYYY', true);

                        if (
                            (minDateStr === null && maxDateStr === null) ||
                            (minDateStr === null && dateMoment.isSameOrBefore(maxDateMoment)) ||
                            (minDateMoment.isSameOrBefore(dateMoment) && maxDateStr === null) ||
                            (minDateMoment.isSameOrBefore(dateMoment) && dateMoment.isSameOrBefore(maxDateMoment))
                        ) {
                            return true;
                        }

                        return false;
                    });
                    //↓ En esta sección se utiliza el id de los rangos colocados en el input de arriba
                    var minDate = new DateTime('#min', {
                        format: 'DD/MM/YYYY'
                    });

                    var maxDate = new DateTime('#max', {
                        format: 'DD/MM/YYYY'
                    });


                    //↓ Aca inicia el script del datatable el id "TablaEX" se utiliza para inicializarlo y aplicar la funcion de filtrado
                    $('#TablaEX thead tr').clone(true).addClass('filters').appendTo('#TablaEX thead');
                    var table = $('#TablaEX').DataTable({
                        dom: 'Bfrtip',
                        //Boton del excel para importar los datos de la tabla
                        buttons: ['excel'],
                        orderCellsTop: true,
                        fixedHeader: true,
                        responsive: false,
                        scrollX: true,
                        //Ordenamiento
                        order: [
                            [0, "desc"]
                        ],
                        //Idioma de los mensajes
                        language: {
                            "sProcessing": "Procesando...",
                            "sLengthMenu": "Mostrar _MENU_ registros",
                            "sZeroRecords": "No se encontraron resultados",
                            "sEmptyTable": "Ningún dato disponible en esta tabla",
                            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                            "sInfoPostFix": "",
                            "sSearch": "Buscar:",
                            "sUrl": "",
                            "sInfoThousands": ",",
                            "sLoadingRecords": "Cargando...",
                            "oPaginate": {
                                "sFirst": "Primero",
                                "sLast": "Último",
                                "sNext": "Siguiente",
                                "sPrevious": "Anterior"
                            },
                            "oAria": {
                                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            },
                            //Idioma del calendario del rango de fechas
                            "datetime": {
                                "previous": "Anterior",
                                "next": "Próximo",
                                "hours": "Horas",
                                "minutes": "Minutos",
                                "seconds": "Segundos",
                                "unknown": "-",
                                "amPm": [
                                    "AM",
                                    "PM"
                                ],
                                "months": {
                                    "0": "Enero",
                                    "1": "Febrero",
                                    "2": "Marzo",
                                    "3": "Abril",
                                    "4": "Mayo",
                                    "5": "Junio",
                                    "6": "Julio",
                                    "7": "Agosto",
                                    "8": "Septiembre",
                                    "9": "Octubre",
                                    "10": "Noviembre",
                                    "11": "Diciembre"
                                },
                                "weekdays": [
                                    "Dom",
                                    "Lun",
                                    "Mar",
                                    "Mié",
                                    "Jue",
                                    "Vie",
                                    "Sáb"
                                ]
                            }
                        }, //Filtros del head
                        initComplete: function() {
                            var api = this.api();
                            api.columns().eq(0).each(function(colIdx) {
                                var cell = $('.filters th').eq($(api.column(colIdx).header()).index());
                                var title = $(cell).text();
                                $(cell).html('<input type="text" placeholder="' + title + '" />');
                                $('input', $('.filters th').eq($(api.column(colIdx).header()).index()))
                                    .off('keyup change')
                                    .on('keyup change', function(e) {
                                        e.stopPropagation();
                                        $(this).attr('title', $(this).val());
                                        var regexr =
                                            '({search})';
                                        var cursorPosition = this.selectionStart;
                                        api
                                            .column(colIdx)
                                            .search((this.value != "") ? regexr.replace('{search}',
                                                    '(((' + this.value + ')))') : "", this.value !=
                                                "", this.value == "")
                                            .draw();
                                        $(this).focus()[0].setSelectionRange(cursorPosition,
                                            cursorPosition);
                                    });
                            });
                        },
                        /*↓En esta seccion se cambia el tamaño de lso campos y posicion del texto
                        empezando desde la poscision 0*/
                        columnDefs: [{
                                targets: 0,
                                type: 'date',
                                width: "120px",
                                render: function(data, type, full, meta) {
                                    if (type === 'sort' || type === 'type') {
                                        return moment(data, 'DD/MM/YYYY').format(
                                            'YYYY-MM-DD'
                                            ); /*← Cuando se importe una fecha colocar este codigo */
                                    }
                                    return data;
                                },
                                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                                }
                            },
                            {
                                targets: 1,
                                width: "150px",
                                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                    $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                                }
                            },
                            {
                                targets: 2,
                                width: "150px",
                                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                    $(td).css('text-align', 'left'); // centrar el contenido de la celda
                                }
                            },
                            {
                                targets: 3,
                                width: "120px",
                                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                                }
                            },
                            {
                                targets: 4,
                                width: "50px",
                                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                    $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                                }
                            },
                            {
                                targets: 7,
                                width: "200px",
                                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                                    $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                                }
                            }


                        ]
                    });
                    //↓ Cuando se ejecute el filtrado la tabla cambiara en tiempo real
                    document.querySelectorAll('#min, #max').forEach((el) => {
                        el.addEventListener('change', () => $('#TablaEX').DataTable().draw());
                    });

                    //↓ Posicion de los botones de excel y capo de busqueda
                    $('.dt-buttons').appendTo($('#botonExcel').parent());

                    $('#exportarExcel').on('click', function() {
                        table.button(0).trigger();
                    });


                    var searchInput = $('.dataTables_filter input').clone(true);

                    $('#searchContainer').append(searchInput);
                });
            </script>



            <script src="{{ asset('js/jquery.min.js') }}"></script>
            <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
            <script type="text/javascript">
                document.addEventListener("DOMContentLoaded", () => {
                    cuentasEmpresa();

                    document.querySelector('#empresa').addEventListener('change', () => {
                        document.querySelector('#cuentas').innerHTML = '';
                        cuentasEmpresa();
                    });

                    if (document.querySelector("#subtotal").value != 0) {
                        calcTotal();
                    }
                    initialValues();
                    calcTotal();
                    document.querySelector("#formAltaGastos").addEventListener("submit", validacion);
                    document.querySelector("#mxn").addEventListener("change", (e => {
                        if (e.target.checked) {
                            document.querySelector("#cambio_dolar").setAttribute("readonly", "");
                            document.querySelector("#cambio_dolar").value = "1.0";
                        }
                    }));
                    document.querySelector("#usd").addEventListener("change", (e => {
                        if (e.target.checked) {
                            document.querySelector("#cambio_dolar").removeAttribute("readonly");
                            document.querySelector("#cambio_dolar").value = "";
                        }
                    }));
                    document.querySelector("#formaPago_efectivo").addEventListener("change", (e) => {
                        if (e.target.checked) {
                            document.querySelector(".credito_dias_input").innerHTML =
                                `<input type="text" name="credito_dias" id="credito_dias" class="form-control" readonly>`;
                            document.querySelector("#credito_dias").value = "No aplica";
                        }
                    });
                    document.querySelector("#formaPago_credito").addEventListener("change", (e) => {
                        if (e.target.checked) {
                            document.querySelector(".credito_dias_input").innerHTML = `<select class="form-control" name="credito_dias" id="credito_dias">
                        <option value="7_dias" name="credito_dias">7 dias</option>
                        <option value="10_dias" name="credito_dias">10 dias</option>
                        <option value="14_dias" name="credito_dias">14 dias</option>
                        <option value="30_dias" name="credito_dias">30 dias</option>
                        <option value="45_dias" name="credito_dias">45 dias</option>
                        <option value="60_dias" name="credito_dias">60 dias</option>
                    </select>`;
                        }
                    });
                });

                function cuentasEmpresa() {
                    let empresa = document.querySelector('#empresa').value;
                    axios.get('cuentasporEmpresa/' + empresa).then((response) => {
                        let cuentas = response.data;
                        cuentas.forEach((e) => {
                            let tableOption = document.createElement('option');
                            tableOption.value = e.id;
                            tableOption.innerHTML = `${e.numeroCu} - ${e.nombre}`;
                            document.querySelector('#cuentas').appendChild(tableOption);
                        });
                    });
                }

                function calcTotal() {
                    let subtotal = document.querySelector("#subtotal");
                    let iva = document.querySelector("#iva");
                    let isr = document.querySelector("#isr");
                    var total_iva = 0;
                    var total_isr = 0;
                    let numberFormat = Intl.NumberFormat("es-MX", {
                        style: "currency",
                        currency: "MXN"
                    });
                    if (subtotal.value != "" && iva.value != "") {
                        total_iva = parseFloat(subtotal.value) * (parseFloat(iva.value) / 100);
                        document.querySelector("#total_iva").value = total_iva.toFixed(2);
                    }
                    if (subtotal.value != "" && isr.value != "") {
                        total_isr = parseFloat(subtotal.value) * (parseFloat(isr.value) / 100);
                        document.querySelector("#total_isr").value = total_isr.toFixed(2);
                    }
                    if (subtotal.value != "" && iva.value != "" && isr.value != "") {
                        total = parseFloat(subtotal.value) + total_iva + total_isr;
                        document.querySelector("#total").value = total.toFixed(2);
                    }

                    subtotal.addEventListener("change", () => {
                        if (iva.value != "") {
                            total_iva = parseFloat(subtotal.value) * (parseFloat(iva.value) / 100);
                            document.querySelector("#total_iva").value = total_iva.toFixed(2);
                        }
                        if (isr.value != "") {
                            total_isr = parseFloat(subtotal.value) * (parseFloat(isr.value) / 100);
                            document.querySelector("#total_isr").value = total_isr.toFixed(2);
                        }
                        if (iva.value != "" && isr.value != "") {
                            total = parseFloat(subtotal.value) + total_iva + total_isr;
                            document.querySelector("#total").value = total.toFixed(2);
                        }
                    });
                    iva.addEventListener("change", () => {
                        if (subtotal.value != "") {
                            total_iva = parseFloat(subtotal.value) * (parseFloat(iva.value) / 100);
                            document.querySelector("#total_iva").value = total_iva.toFixed(2);
                        }
                        if (isr.value != "") {
                            total_isr = parseFloat(subtotal.value) * (parseFloat(isr.value) / 100);
                            document.querySelector("#total_isr").value = total_isr.toFixed(2);
                        }
                        if (iva.value != "" && isr.value != "") {
                            total = parseFloat(subtotal.value) + total_iva + total_isr;
                            document.querySelector("#total").value = total.toFixed(2);
                        }
                    });
                    isr.addEventListener("change", () => {
                        if (subtotal.value != "") {
                            total_iva = parseFloat(subtotal.value) * (parseFloat(iva.value) / 100);
                            document.querySelector("#total_iva").value = total_iva.toFixed(2);
                        }
                        if (subtotal.value != "") {
                            total_isr = parseFloat(subtotal.value) * (parseFloat(isr.value) / 100);
                            document.querySelector("#total_isr").value = total_isr.toFixed(2);
                        }
                        if (iva.value != "" && isr.value != "") {
                            total = parseFloat(subtotal.value) + total_iva + total_isr;
                            document.querySelector("#total").value = total.toFixed(2);
                        }
                    });
                }

                function initialValues() {
                    document.querySelector("#iva").value = 16;
                    document.querySelector("#isr").value = 20;
                }

                function validacion(e) {
                    e.preventDefault();

                    //Variables para la validacion
                    let nombreGasto = document.querySelector("#nombreGasto");
                    let beneficiario = document.querySelector("#beneficiario");
                    let banco = document.querySelector("#banco");
                    let empresa = document.querySelector("#empresa");
                    let cuenta = document.querySelector("#cuentas");
                    let formaPago_credito = document.querySelector("#formaPago_credito");
                    let formaPago_efectivo = document.querySelector("#formaPago_efectivo");
                    let referencia = document.querySelector("#referencia");
                    let etiquetas = document.querySelector("#etiquetas");
                    let subtotal = document.querySelector("#subtotal");
                    let total = document.querySelector("#total");
                    let descripcion = document.querySelector("#descripcion");
                    let cambio_dolar = document.querySelector('#cambio_dolar');
                    let btn_guardar = document.querySelector("#btn_guardar");

                    //Validacion del nombre
                    if (nombreGasto.value.length == 0) {
                        alert("No se asigno un nombre al gasto");
                        return;
                    }
                    //Validacion del beneficiario
                    if (beneficiario.value.length == 0) {
                        alert("No se asigno un beneficiario");
                        return;
                    }

                    //Validar la fecha
                    if (fecha_pago.value.length == 0) {
                        alert("No se adjunto una fecha");
                        return;
                    } else {
                        let today = new Date();
                        let fecha = fecha_pago.value;
                        let anio = fecha.substr(0, 4);
                        let mes = fecha.substr(5, 2);
                        let dia = fecha.substr(8, 2);

                        if (today.getFullYear() < anio) {
                            alert("Fecha invalida");
                            return;
                        } else {
                            if (today.getFullYear() == anio) {
                                if (today.getMonth() + 1 < mes) {
                                    alert("Fecha invalida");
                                    return;
                                } else {
                                    if (today.getMonth() + 1 == mes) {
                                        if (today.getDate() < dia) {
                                            alert("Fecha invalida");
                                            return;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    //Validar las etiquetas
                    if (etiquetas.value.length == 0) {
                        alert("No se asigno una etiqueta");
                        return;
                    }

                    //Validar la referencia
                    /*
                    if(parseInt(referencia.value)){
                        console.log(typeof(parseInt(referencia.value)));
                    }else{
                        alert("El numero de referencia no es valido");
                        return;
                    }
                    
                    */
                    //Validar el subtotal
                    if (parseInt(subtotal.value)) {
                        console.log(typeof(parseInt(subtotal.value)));
                    } else {
                        alert("El subtotal no es valido");
                        return;
                    }

                    if (cambio_dolar.value != 'No aplica') {
                        if (parseInt(cambio_dolar.value)) {
                            console.log(typeof(parseInt(subtotal.value)));
                        } else {
                            alert("El subtotal no es valido");
                            return;
                        }
                    }
                    /*

                    //Validar el subtotal
                    if(parseInt(total.value)){
                        console.log(typeof(parseInt(total.value)));
                    }else{
                        alert("El total no es valido");
                        return;
                    }
                    */
                    //Validar el impuesto
                    //if(parseInt(impuesto.value)){
                    //    console.log(typeof(parseInt(impuesto.value)));
                    //}else{
                    //    alert("Valor del impuesto no es valido");
                    //    return;
                    //}

                    this.submit();
                }
            </script>
            <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

            <style>
                thead input {
                    width: 100%;
                }

                button:focus {
                    outline: none;
                }

                .btn:focus {
                    outline: none;
                }

                .dataTables_wrapper table {
                    text-align: center;
                }

                .dataTables_wrapper table thead th {
                    text-align: center;
                }

                .hidden {
                    display: none;
                }

                .date-table {
                    width: auto;
                    margin-bottom: 20px;
                }

                .date-table td {
                    padding: 5px;
                    vertical-align: middle;
                }

                .date-input {
                    width: 200px;
                    padding: 5px;
                    border: 1px solid #ddd;
                    background-color: #fff;
                    font-size: 14px;
                    border-radius: 5px;
                }

                .dataTables_wrapper .dataTables_filter {
                    display: none;
                }

                .search-controls {
                    width: 650px;
                    text-align: right;
                }
            </style>
            <!-- termina vista gastos                         -->
        @stop
