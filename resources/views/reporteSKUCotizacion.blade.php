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
                <h1 class="">Reporte de SKU's en Cotización</h1>
            </div>
            <br>
            <div class="panel-body">
                <div id="list" class="table-responsive">
                    <!--
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
                    -->
                    @if ($cuantaCoti == 0)
                    <br>
                    <div class="alert alert-warning" role="alert" align="center">
                        <label for="">¡Sin SKU's registrados!</label>
                    </div> 
                    @else
                    <table id="TablaSKUc" class="table-bordered display">
                        <thead>
                            <tr style="background-color: #E0E0E0">
                                <th class="hidden">idcot</th>
                                <th style="background-color: #E0E0E0;">Fecha Cotización</th><!-- 1 o pos 0 -->
                                <th style="background-color: #E0E0E0;">Número Cotización</th>
                                <th style="background-color: #E0E0E0;">Cliente</th>
                                <th style="background-color: #E0E0E0;">Sucutrsal</th>
                                <th style="background-color: #E0E0E0;">Marca</th>
                                <th style="background-color: #E0E0E0;">Tipo</th>
                                <th style="background-color: #E0E0E0;">SKU</th>
                                <th style="background-color: #E0E0E0;">Descripción</th>
                                <th style="background-color: #E0E0E0;">Modelo</th>
                                <th style="background-color: #E0E0E0;">Moneda</th>
                                <th style="background-color: #E0E0E0;">Cantidad</th>
                                <th style="background-color: #E0E0E0;">Total</th>
                                <th style="background-color: #E0E0E0;">Notas</th><!-- 13 o pos 12 -->
                            </tr>
                        </thead> 
                        <tbody>
                            @foreach ($cotizacion as $cot)
                        <tr>
                            <td class="hidden">{{ $cot->idCotizacion }}</td>
                            <td>{{ \Carbon\Carbon::parse($cot->fechacotizacion)->format('d/m/Y') }}</td>
                            <td>
                                <a href="pdfCotizacion?idCotizacion={{ $cot->idCotizacion }}&verTotal=Si&pld=1&cD=Si" target="_blank">
                                    {{ $cot->numeroCotizacion }}
                                </a>
                            </td>
                            <td>{{ $cot->razonSocial }}</td>
                            <td>{{ $cot->sucursal }}</td>
                            <td>{{ $cot->marcaRefaccion }}</td>
                            <td>{{ $cot->nombreTipoRefaccion }}</td>
                            <td>{{ $cot->codigo }}</td>
                            <td>{{ $cot->descripcion }}</td>
                            <td>{{ $cot->modelo }}</td>
                            <td>{{ $cot->tipoMoneda }}</td>
                            <td>{{ $cot->piezas }}</td>
                            <td>$ {{ number_format($cot->montofinanciamiento, 2, '.', ',') }}</td>
                            <td>{{ $cot->notas }}</td>
                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">

    <script>
        $(document).ready(function() {

           /* DataTable.ext.search.push(function(settings, data, dataIndex) {
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

        minDate = new DateTime('#min', {
            format: 'DD/MM/YYYY'
        });
        maxDate = new DateTime('#max', {
            format: 'DD/MM/YYYY'
        });*/


            $('#TablaSKUc thead tr').clone(true).addClass('filters').appendTo('#TablaSKUc thead');
            var table = $('#TablaSKUc').DataTable({
                //dom: 'Bfrtip',
                dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>rt<"row"<"col-sm-3"l><"col-sm-3"i><"col-sm-6"p>>',//En este dom se muestra el apartado de cuantos registros mostrar
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
                    {targets: 7,
                    width: "150px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 4,
                    width: "150px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'left'); // centrar el contenido de la celda
                    }},
                    {targets: 1,
                    type: 'date',
                    width: "50px",
                    render: function (data, type, full, meta) {
                    if (type === 'sort' || type === 'type') {
                        return moment(data, 'DD/MM/YYYY').format('YYYY-MM-DD');//Esto se agrega para que se muestre la fecha mas reciente primero
                    }
                    return data;
                    },
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 2,
                    width: "100px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 10,
                    width: "50px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 11,
                    width: "50px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 12,
                    width: "80px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'Justify'); // centrar el contenido de la celda
                    }},
                    {targets: 13,
                    width: "450px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                    }},
                    {targets: 3,
                    width: "250px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                    }},
                    {targets: 9,
                    width: "20px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                    }}
            ],
            // Mostrar los botones de paginacion
            pagingType: 'full_numbers',
            // Numero de registros por pagina
            lengthMenu: [10,15,25,50,100],
            });

           /* document.querySelectorAll('#min, #max').forEach((el) => {
                el.addEventListener('change', () => $('#TablaOC').DataTable().draw());
            });

            $('.dt-buttons').appendTo($('#botonExcel').parent());

            $('#exportarExcel').on('click', function() {
            table.button(0).trigger();
             });


            var searchInput = $('.dataTables_filter input').clone(true);

        $('#searchContainer').append(searchInput);
        */
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
        display: true;
    }

    .search-controls {
        width: 650px;
        text-align: right;
    }
    </style>
@stop
