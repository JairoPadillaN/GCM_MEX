@extends('principal')
@section('contenido')
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

    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Reporte de servicios</h1>
            </div>
            <br>
            <div class="panel-body">
                <div id="list" class="table-responsive">
                    
                    <table border="0" cellspacing="5" cellpadding="5" class="date-table">
                        <tbody>
                            <tr>
                                <td class="label-cell" style="font-size: 14px; font-weight:bold;">Fecha Inicial:</td>
                                <td><input class="date-input" type="text" id="min" name="min"></td>
                                <td class="label-cell" style="font-size: 14px; font-weight:bold;">Fecha Final:</td>
                                <td><input class="date-input" type="text" id="max" name="max"></td>
                                <td>
                                    <div id="botonExcel"></div>
                                </td>
                                <td class="search-controls" style="font-size: 14px; font-weight:bold;">
                                    Buscar:
                                </td>
                                <td>
                                    <div id="searchContainer"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    
                    <table id="TablaServ" class="table table-striped table-bordered display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="no-display" hidden>idCot</th><!-- 1 o pos 0 -->
                                <th style="no-display" hidden>Folio de<br> Servicio</th>
                                <th style="background-color: #C5EBFB;">Folio de<br> Servicio</th>
                                <th style="background-color: #C5EBFB;">Fecha de<br> creación</th>
                                <th style="background-color: #C5EBFB;">Numero<br> de factura</th>
                                
                                <th style="background-color: #C5EBFB;">Cotización de<br> Equipo/Servicio</th>
                                <th style="background-color: #C5EBFB;">Fecha de<br> Facturación</th>
                                <th style="background-color: #C5EBFB;">Fecha de<br> Pago</th>
                                <th style="background-color: #C5EBFB;">Cliente</th>
                                <th style="background-color: #C5EBFB;">Sucursal</th>
                                <th style="background-color: #C5EBFB;">Estatus de<br> entrega</th>
                                <th style="background-color: #C5EBFB;">Estatus Portal</th>
                                <th style="background-color: #C5EBFB;">Estatus Pago</th>
                                <th style="background-color: #C5EBFB;">Cash<br> Flow</th>
                                <th style="background-color: #C5EBFB;">Monto<br> Cotización</th>
                                <th style="background-color: #C5EBFB;">Total</th>
                                <th style="background-color: #C5EBFB;">Descarga de Archivos</th>
                                <th style="background-color: #C5EBFB;">Opciones</th><!-- 18 o pos 17 -->
                            </tr>
                        </thead> 
                        <tbody>
                          @foreach ($servi as $se)
                          <tr>
                            <td class="hidden">{{ $se->idcotizacion }}</td>
                            <td class="hidden">{{ $se->idfactura }}</td>
                            <td>{{ $se->idservicios }}</td>
                            <td>{{  \Carbon\Carbon::parse($se->created_at)->format('d/m/Y') }}</td>
                            
                            <td>{{ $se->numerofactura }}</td>
                            
                            <td>{{ $se->foliocotizacion }}</td>
                            <td>{{ \Carbon\Carbon::parse($se->fechafactura)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($se->fechapago)->format('d/m/Y') }}</td>
                            <td style="white-space: pre-line;">{{ $se->razonsocial }}</td>
                            <td style="white-space: pre-line;">{{ $se->sucursal }}</td>
                            <td>{{ $se->estatusentrega }}</td>
                            <td>{{ $se->estatusportal }}</td>
                            <td>{{ $se->estatuspago }}</td>
                            <td>{{ $se->cashflow }}</td>
                            <td>{{ $se->montofactura }}</td>
                            <td>{{ $se->totalconiva }}</td>
                            <td> <!-- ↓Aqui va la logica de la descarga de archivos↓ -->
                               
                                <!-- Creación de PDF -->
                                @if ($se->tiposervicio == 'P')
                                <a href="{{ route('pdfCotizacion', ['idCotizacion' => $se->idcotizacion, 'verTotal' => 'Si', 'pld' => 1, 'cD' => 'Si']) }}" target="_blank">
                                    <button type="button" class="btn btn-success">
                                        <i class="ace-icon fa fa-file-pdf-o bigger-120"> Cotizacion del Sistema ↓</i>
                                    </button>
                                    </a>       
                                @elseif ($se->tiposervicio == 'S')
                                <a href="{{ route('pdfCotizacionServicios',['idCotizacionServicios' => $se->idcotizacion, 'verTotal' => 'Si', 'pld' => 1, 'cD' => 'Si']) }}" target="blank">
                                    <button type="button" class="btn btn-success">
                                        <i class="ace-icon fa fa-pdf-o bigger-120"> Cotizacion del Sistema ↓</i> 
                                    </button>
                                    </a>
                                @else
                                <button type="button" class="btn btn-light" disabled>
                                    <i class="ace-icon fa fa-file-pdf-o bigger-120"> Cotizacion del Sistema ↓</i> 
                                </button>
                                @endif

                            
                            
                            @if (Session::get('sesiontipo') == 'Administrador' || Session::get('sesiontipo') == 'Vendedor' || Session::get('sesiontipo') == 'Tecnico' || Session::get('sesionidu') == 18)

                                @if ($se->archivoorden == 'Sin archivo')
                                <button type="button" class="btn btn-light" disabled>
                                    <i class="ace-icon fa fa-file-archive-o bigger-120"> Orden ↓</i> 
                                </button>
                                @else
                                <a href="{{ asset('archivos/' . $se->archivoorden) }}" target="blank">
                                    <button type="button" class="btn btn-success">
                                        <i class="ace-icon fa fa-file-archive-o bigger-120"> Orden ↓</i> 
                                    </button>
                                    </a>     
                                @endif
                                <br>   
                                @if ($se->archivofactura == 'Sin archivo')
                                <button type="button" class="btn btn-light" disabled>
                                    <i class="ace-icon fa fa-file-archive-o bigger-120"> Factura ↓</i> 
                                </button>
                                @else
                                <a href="{{ asset('archivos/' . $se->archivofactura) }}" target="blank">
                                    <button type="button" class="btn btn-success">
                                        <i class="ace-icon fa fa-file-archive-o bigger-120"> Factura ↓</i> 
                                    </button>
                                    </a>     
                                @endif
                                    
                                @if ($se->archivopago == 'Sin archivo')
                                <button type="button" class="btn btn-light" disabled>
                                    <i class="ace-icon fa fa-file-archive-o bigger-120"> Pago ↓</i> 
                                </button>
                                @else
                                <a href="{{ asset('archivos/' . $se->archivopago) }}" target="blank">
                                    <button type="button" class="btn btn-success">
                                        <i class="ace-icon fa fa-file-archive-o bigger-120"> Pago ↓</i> 
                                    </button>
                                    </a>     
                                @endif

                                @if ($se->xmlfactura == 'Sin archivo')
                                <button type="button" class="btn btn-light" disabled>
                                    <i class="ace-icon fa fa-file-archive-o bigger-120"> XML ↓</i> 
                                </button>
                                @else
                                <a href="{{ asset('archivos/' . $se->xmlfactura) }}" target="blank">
                                    <button type="button" class="btn btn-success">
                                        <i class="ace-icon fa fa-file-archive-o bigger-120"> XML ↓</i> 
                                    </button>
                                    </a>     
                                @endif
                                    <br>
                                @if ($se->archivoremision == 'Sin archivo')
                                <button type="button" class="btn btn-light" disabled>
                                    <i class="ace-icon fa fa-file-archive-o bigger-120"> Remisión ↓</i> 
                                </button>
                                @else
                                <a href="{{ asset('archivos/' . $se->archivoremision) }}" target="blank">
                                    <button type="button" class="btn btn-success">
                                        <i class="ace-icon fa fa-file-archive-o bigger-120"> Remisión ↓</i> 
                                    </button>
                                    </a>     
                                @endif
                                
                                @if ($se->archivoadenda == 'Sin archivo')
                                <button type="button" class="btn btn-light" disabled>
                                    <i class="ace-icon fa fa-file-archive-o bigger-120"> Adenda ↓</i> 
                                </button>
                                @else
                                <a href="{{ asset('archivos/' . $se->archivoadenda) }}" target="blank">
                                    <button type="button" class="btn btn-success">
                                        <i class="ace-icon fa fa-file-archive-o bigger-120"> Adenda ↓</i> 
                                    </button>
                                    </a>     
                                @endif
                                
                            @endif 
                            </td>
                            <td><!-- ↓Aqui va la logica de las Acciones↓ -->

                            @if ($se->activo == 'si') 
                                <!-- Editar Factura  -->
                                @if (Session::get('sesiontipo') == 'Administrador' || Session::get('sesiontipo') == 'Vendedor' || Session::get('sesiontipo') == 'Almacen')
                                <a href="{{ route('modificarFacturas',['idFactura'=>$se->idfactura]) }}">
                                <button type="button" class="btn btn-info">
                                    <i class="ace-icon fa fa-pencil bigger-120"></i> 
                                </button>
                                </a>     
                                @endif

                                <!-- Eliminar Factura -->
                                @if (Session::get('sesiontipo') == 'Administrador')
                                    <a href="{{ route('eliminarFacturas',['idFactura'=>$se->idfactura]) }}">
                                        <button type="button" class="btn btn-danger">
                                    <i class="ace-icon fa fa-trash bigger-120"></i> 
                                        </button>
                                    </a>     
                                @endif
                                <br>
                                <!-- Cerrar y Abrir Servicio -->
                                @if (Session::get('sesiontipo') == 'Administrador')
                                    @if ($se->servicioActivo == '' || $se->servicioActivo == 'Activado')
                                <a href="{{ route('cerrarServicio',['idFactura'=>$se->idfactura]) }}">
                                    <button type="button" class="btn btn-danger">
                                        <i class="ace-icon fa fa-remove bigger-120"> Cerrar </i> 
                                    </button>
                                    </a>
                                    @elseif ($se->servicioActivo == 'Cerrado')
                                <a href="{{ route('activarServicio',['idFactura'=>$se->idfactura]) }}">
                                    <button type="button" class="btn btn-warning">
                                        <i class="ace-icon fa fa-retweet bigger-120"> Activar </i> 
                                    </button>
                                    </a>
                                    @endif     
                                @endif
                            @elseif ($se->activo == 'No')
                                @if (Session::get('sesiontipo') == 'Administrador')
                                <a href="{{ route('restaurarFacturas',['idFactura'=>$se->idfactura]) }}">
                                    <button type="button" class="btn btn-secondary">
                                        <i class="ace-icon fa fa-refresh bigger-120"> Restaurar </i> 
                                    </button>
                                </a>     
                                @endif                   
                            @endif     
                            </td>
                          </tr>        
                          @endforeach 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">

    <script>
        $(document).ready(function() {

           DataTable.ext.search.push(function(settings, data, dataIndex) {
            let minDateStr = minDate.val();
            let maxDateStr = maxDate.val();
            let dateStr = data[3];
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

        minDate = new DateTime('#min', {
            format: 'DD/MM/YYYY'
        });
        maxDate = new DateTime('#max', {
            format: 'DD/MM/YYYY'
        });


            $('#TablaServ thead tr').clone(true).addClass('filters').appendTo('#TablaServ thead');
            var table = $('#TablaServ').DataTable({
                //dom: 'Bfrtip',
                dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>rt<"row"<"col-sm-3"l><"col-sm-3"i><"col-sm-6"p>>',//En este dom se muestra el apartado de cuantos registros mostrar
                buttons: ['excel'],
                orderCellsTop: true,
                fixedHeader: true,
                scrollX: true,
                order: [
                    [1, "desc"]//Ordenar tabla
                ],
                columnDefs: [
                    {targets: 1,
                    width: "50px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 2,
                    type: 'date',
                    width: "70px",
                    render: function (data, type, full, meta) {
                    if (type === 'sort' || type === 'type') {
                        return moment(data, 'DD/MM/YYYY').format('YYYY-MM-DD');//Esto se agrega para que se muestre la fecha mas reciente primero
                    }
                    return data;
                    },
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 3,
                    width: "70px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 4,
                    width: "70px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 5,
                    width: "110px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 6,
                    width: "110px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 7,
                    width: "70px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 8,
                    width: "70px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                    }},
                    {targets: 9,
                    width: "150px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                    }},
                    {targets: 10,
                    width: "120px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                    }},
                    {targets: 11,
                    width: "100px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 12,
                    width: "120px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                    }},
                    {targets: 13,
                    width: "100px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 14,
                    width: "50px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 15,
                    width: "120px",
                    },
                    {targets: 16,
                    width: "120px",
                    },
                    {targets: 17,
                    width: "150px",
                    },
                    
            ],
                language: {
                info:           "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty:      "Mostrando 0 a 0 de 0 registros",
                lengthMenu:     "Mostrar _MENU_ registros",
                search:         "Buscar:",
                loadingRecords: "Loading...",
                processing:     "Procesando...",
                zeroRecords:    "No hay registros aún",
                paginate: {
            // previous: "Anterior",
            // next: "Siguiente"
                    next: '→',
                    previous: '←',
                    first:'Inicio',
                    last:'Ultimo'
                },
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
            },
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
                                var regexr = '({search})';
                                var cursorPosition = this.selectionStart;
                                api
                                    .column(colIdx)
                                    .search((this.value != "") ?
                                        regexr.replace('{search}', '(((' + this.value +
                                            ')))') : "", this.value != "", this.value == "")
                                    .draw();
                                $(this).focus()[0].setSelectionRange(cursorPosition,
                                    cursorPosition);
                            });
                    });
                },
                
            // Mostrar los botones de paginacion
            pagingType: 'full_numbers',
            // Numero de registros por pagina
            lengthMenu: [10,15,25,55,105],
            });

            document.querySelectorAll('#min, #max').forEach((el) => {
                el.addEventListener('change', () => $('#TablaServ').DataTable().draw());
            });

            $('.dt-buttons').appendTo($('#botonExcel').parent());

            $('#exportarExcel').on('click', function() {
            table.button(0).trigger();
             });


            var searchInput = $('.dataTables_filter input').clone(true);

        $('#searchContainer').append(searchInput);

        //Columnas que no se mostraran dependiendo del tipo de sesion
        if ('{{ Session::get('sesiontipo') }}' === 'Compras') {
            table.column(11).visible(false);
            table.column(17).visible(false);     
            table.column(15).visible(false);
            table.column(14).visible(false);
            table.column(13).visible(false);
            table.column(12).visible(false);
            table.column(10).visible(false);
        }else if('{{ Session::get('sesiontipo') }}' === 'Almacen'){
            //table.column(17).visible(false);
            table.column(16).visible(false);     
            table.column(15).visible(false);
            table.column(14).visible(false);
            table.column(13).visible(false);
        }else if('{{ Session::get('sesiontipo') }}' === 'Tecnico'){
            table.column(18).visible(false);
        }
        });
    </script>


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

    .dataTables_filter {
        display: none;
    }

    .search-controls {
        width: 650px;
        text-align: right;
    }
    
</style>
@stop