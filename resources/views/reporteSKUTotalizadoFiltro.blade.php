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
    <div class="panel panel-default" style="margin-top: -45px">
        <div class="panel-heading clearfix">
            <h1> Reporte Top de ventas 
                <a href="{{ route('reporteTopVentas') }}" 
                    class="btn btn-link"  
                    data-toggle="tooltip" 
                    data-placement="right" 
                    title="Regresar">
                    <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
                </a>
            </h1>
            <h5>Mostrando ventas del <strong>{{ \Carbon\Carbon::parse($FecIni)->format('d/m/Y') }}</strong> al <strong>{{ \Carbon\Carbon::parse($FecFin)->format('d/m/Y') }}</strong> </h6>
        </div>
        <div class="panel-body">
            <div id="list" class="table-responsive">
                <table border="0" cellspacing="5" cellpadding="5" class="date-table">
                    <tbody>
                        <tr>
                            <td>
                                <form action="{{ route('reporteTopVentas.filtro') }}" method="get">

                                    <label for="ini">Fecha Inicio:  </label>
                                    <input type="date" id="ini" name="ini" style="margin-left: 15px" value="{{ $FecIni }}" required>
    
                                    <label for="fin" style="margin-left: 15px">Fecha Fin:  </label>
                                    <input type="date" id="fin" name="fin" style="margin-left: 15px" value="{{ $FecFin }}" required>
    
                                    <button class="btn btn-success sm" style="margin-left: 15px" type="submit"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Generar consulta</button>    
                    
                                </form>
                            </td>
                            <td>

                                <div id="botonExcel">
                                    
                                </div>
                            </td>

                        </tr>
                    </tbody>
                </table>

                <table id="TablaTotalizados" class="table table-striped  display" cellspacing="0" width="100%">
                    <thead>
                        <tr style="backgroun-color: #E0E0E0">
                            {{-- <th style="background-color: #C5EBFB visibility:collapse; display:none;">Fecha</th> --}}
                            <th style="background-color: #C5EBFB">SKU</th>
                            <th style="background-color: #C5EBFB">Concepto</th>
                            <th style="background-color: #C5EBFB">Marca</th>
                            <th style="background-color: #C5EBFB">Total de Piezas</th>
                            <th style="background-color: #C5EBFB">Total $MXN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($totalFecha as $tot)
                        <tr>
                            {{-- <td style="visibility:collapse; display:none;">{{ \Carbon\Carbon::parse($tot->fecha)->format('d/m/Y') }}</td> --}}
                            <td>{{$tot->sku}}</td>
                            <td>{{$tot->nombreTipoRefaccion}}</td>
                            <td>{{$tot->marcarefaccion}}</td>
                            <td>{{$tot->totalpiezas}}</td>
                            <td>{{number_format($tot->totalmontopesos,2)}}</td>
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
    $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>

<script>
    $(document).ready(function() {
        $('#botonfiltrar').click(function() {
            var startDate = $('#FecIni').val();
            var endDate = $('#FecFin').val();

            $.ajax({
                url: "{{ route('reporteTopVentas.filtro') }}",
                type: 'POST',
                data: {
                    ini: FecIni,
                    fin: FecFin,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#TablaTotalizados tbody').empty();
                    data.data.forEach(function(item) {
                        $('#TablaTotalizados tbody').append(`
                            <tr>
                                <td>${item.sku}</td>
                                <td>${item.nombreTipoRefaccion}</td>
                                <td>${item.marcarefaccion}</td>
                                <td>${item.total_piezas}</td>
                                <td>${item.total_montopesos}</td>
                            </tr>
                        `);
                    });
                }
            });
        });
    });
    </script>

<script>
    $(document).ready(function() {

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

    minDate = new DateTime('#min', {
        format: 'DD/MM/YYYY'
    });
    maxDate = new DateTime('#max', {
        format: 'DD/MM/YYYY'
    });


        $('#TablaTotalizados thead tr').clone(true).addClass('filters').appendTo('#TablaTotalizados thead');
        var table = $('#TablaTotalizados').DataTable({
            dom: 'Bfrtip',
            buttons: ['excel'],
            orderCellsTop: true,
            fixedHeader: true,
            responsive: false,
            scrollX: true,
            order: [
                [0, "desc"]
            ],
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
            columnDefs: [
                /* {targets: 0,
                    type: 'date',
                    width: "120px",
                    render: function (data, type, full, meta) {
                    if (type === 'sort' || type === 'type') {
                        return moment(data, 'DD/MM/YYYY').format('YYYY-MM-DD');
                    }
                    return data;
                    },
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }}, */
                    {targets: 0,
                width: "150px",
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'center'); // centrar el contenido de la celda
                }},
                {targets: 1,
                width: "150px",
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'center'); // centrar el contenido de la celda
                }},
                {targets: 2,
                width: "150px",
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'center'); // centrar el contenido de la celda
                }},
                {targets: 3,
                width: "100px",
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'center'); // centrar el contenido de la celda
                }},
                {targets: 4,
                width: "100px",
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                }},
        ],
        /*             // Mostrar los botones de paginacion
            pagingType: 'full_numbers', */
            // Numero de registros por pagina
            lengthMenu: [100],
        });

        document.querySelectorAll('#min, #max').forEach((el) => {
            el.addEventListener('change', () => $('#TablaTotalizados').DataTable().draw());
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

 input {
     width: 200px;
     padding: 5px;
     border: 1px solid #ddd;
     background-color: #fff;
     font-size: 14px;
     border-radius: 5px;
 }
 </style>

@stop