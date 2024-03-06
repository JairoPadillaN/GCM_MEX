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
                <h1 class="">Reporte de SKU's vendidos por servicio</h1>
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
                    
                    
                    <table id="TablaSKUv" class="table-bordered display">
                        <thead>
                            <tr style="background-color: #C5EBFB">
                                <!--<th class="hidden">idserv</th>-->
                                <th style="background-color: #C5EBFB;">Servicios</th> <!-- 1 o pos 0 -->
                                <th style="background-color: #C5EBFB;">Factura</th>
                                <th style="background-color: #C5EBFB;">Fecha Factura</th>
                                <th style="background-color: #C5EBFB;">Fecha Pago</th>
                                <th style="background-color: #C5EBFB;">Número Cotización</th>
                                <th style="background-color: #C5EBFB;">Razón Social</th>
                                <th style="background-color: #C5EBFB;">Sucursal</th>
                                <th style="background-color: #C5EBFB;">Nombre Refacción</th>
                                <th style="background-color: #C5EBFB;">Numero de parte</th>
                                <th style="background-color: #C5EBFB;">Serie</th>
                                <th style="background-color: #C5EBFB;">Modelo</th>
                                <th style="background-color: #C5EBFB;">SKU</th>
                                <th style="background-color: #C5EBFB;">Nombre Tipo Refacción</th>
                                <th style="background-color: #C5EBFB;">Marca Refacción</th>
                                <th style="background-color: #C5EBFB;">Moneda</th>
                                <th style="background-color: #C5EBFB;">Piezas</th>
                                <th style="background-color: #C5EBFB;">Monto Total (Pesos)</th>
                                <th style="background-color: #C5EBFB;">Como se Cotizó</th>
                                <th style="background-color: #C5EBFB;">Notas</th><!-- 19 o pos 18 -->
                            </tr>
                        </thead> 
                        <tbody>
                          @foreach ($vendido as $ven)
                          <tr>
                            <td>{{ $ven->idservicios }}</td>

                            @if ($ven->factura == null)
                            <td>Sin factura</td>                                    
                            @else
                            <td>{{ $ven->factura }}</td>
                            @endif

                            <td>{{ \Carbon\Carbon::parse($ven->fechafactura)->format('d/m/Y') }}</td>

                            @if($ven->fechapago == NULL)
                            <td>Sin fecha de pago</td>
                            @else
                            <td>{{ \Carbon\Carbon::parse($ven->fechapago)->format('d/m/Y') }}</td>
                            @endif

                            <td>{{ $ven->numerocotizacion }}</td>
                            <td>{{ $ven->razonsocial }}</td>
                            <td>{{ $ven->sucursal }}</td>
                            <td>{{ $ven->nombrerefaccion }}</td>
                            <td>{{ $ven->numeroparte }}</td>
                            <td>{{ $ven->serie }}</td>
                            <td>{{ $ven->modelo }}</td>
                            <td>{{ $ven->sku}}</td>
                            <td>{{ $ven->nombreTipoRefaccion }}</td>
                            <td>{{ $ven->marcarefaccion }}</td>
                            <td>{{ $ven->moneda }}</td>
                            <td>{{ $ven->piezas }}</td>
                            <td>$ {{ number_format($ven->montototalpesos, 2, '.', ',') }}</td>
                            <td>{{ $ven->comosecotizo}}</td>
                            <td>{{ $ven->notas}}</td>
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
            let dateStr = data[2];
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


            $('#TablaSKUv thead tr').clone(true).addClass('filters').appendTo('#TablaSKUv thead');
            var table = $('#TablaSKUv').DataTable({
                dom: 'Bfrtip',
                //dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>rt<"row"<"col-sm-3"l><"col-sm-3"i><"col-sm-6"p>>',//En este dom se muestra el apartado de cuantos registros mostrar
                buttons: ['excel'],
                orderCellsTop: true,
                fixedHeader: true,
                
                scrollX: true,
                order: [
                    [0, "desc"]//Ordenar tabla
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
                    api
                .columns()
                .eq(0)
                .each(function (colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $('.filters th').eq(
                        $(api.column(colIdx).header()).index()
                    );
                    var title = $(cell).text();
                    $(cell).html('<input type="text" placeholder="' + title + '" />');
 
                    // On every keypress in this input
                    $(
                        'input',
                        $('.filters th').eq($(api.column(colIdx).header()).index())
                    )
                        .off('keyup change')
                        .on('change', function (e) {
                            // Get the search value
                            $(this).attr('title', $(this).val());
                            var regexr = '({search})'; //$(this).parents('th').find('select').val();
 
                            var cursorPosition = this.selectionStart;
                            // Search the column for that value
                            api
                                .column(colIdx)
                                .search(
                                    this.value != ''
                                        ? regexr.replace('{search}', '(((' + this.value + ')))')
                                        : '',
                                    this.value != '',
                                    this.value == ''
                                )
                                .draw();
                        })
                        .on('keyup', function (e) {
                            e.stopPropagation();
 
                            $(this).trigger('change');
                            $(this)
                                .focus()[0]
                                .setSelectionRange(cursorPosition, cursorPosition);
                        });
                    });
                },
                columnDefs: [
                    {targets: 0,
                    width: "50px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
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
                    width: "100px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 5,
                    width: "110px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'left'); // centrar el contenido de la celda
                    }},
                    {targets: 6,
                    width: "110px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'left'); // centrar el contenido de la celda
                    }},
                    {targets: 7,
                    width: "250px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'left'); // centrar el contenido de la celda
                    }},
                    {targets: 8,
                    width: "110px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'left'); // centrar el contenido de la celda
                    }},
                    {targets: 9,
                    width: "60px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                    }},
                    {targets: 10,
                    width: "60px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                    }},
                    {targets: 11,
                    width: "60px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                    }},
                    {targets: 12,
                    width: "60px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                    }},
                    {targets: 13,
                    width: "60px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                    }},
                    /*{targets: 14,
                    width: "60px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                    }},*/
                    /*{targets: 15,
                    width: "10px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                    }},*/
                    {targets: 16,
                    width: "80px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'left'); // centrar el contenido de la celda
                    }},
                    {targets: 17,
                    width: "120px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'left'); // centrar el contenido de la celda
                    }},
                    {targets: 18,
                    width: "450px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                    }}
            ],
            // Mostrar los botones de paginacion
            pagingType: 'full_numbers',
            // Numero de registros por pagina
            lengthMenu: [10,15,25,50,100],
            });

            document.querySelectorAll('#min, #max').forEach((el) => {
                el.addEventListener('change', () => $('#TablaSKUv').DataTable().draw());
            });

            $('.dt-buttons').appendTo($('#botonExcel').parent());

            $('#exportarExcel').on('click', function() {
            table.button(0).trigger();
             });


            var searchInput = $('.dataTables_filter input').clone(true);

        $('#searchContainer').append(searchInput);
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

    .dataTables_wrapper .dataTables_filter {
        display: none;
    }

    .search-controls {
        width: 650px;
        text-align: right;
    }
</style>

@stop
