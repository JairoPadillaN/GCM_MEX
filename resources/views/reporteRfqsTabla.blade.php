<table id="example" class="display" style="width: 100%">
    <thead>
        <tr>
            <th class="no-display">idrfq</th>
            <th>RFQ</th>
            <th>Fecha de solicitud</th>
            <th>Vendedor</th>
            <th>Nivel de solicitud</th>
            <th>Cliente</th>
            <th>Estatus</th>
            <th>Fecha Entrega cotización a cliente</th>
            <th>Fecha estimada de entrega</th>
            <th>Observaciones</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($consulta as $c)
            <tr>
                <td>{{ $c->idrfq }}</td>
                <td>{{ $c->cmgrfq }}</td>
                <td>{{ date('d-m-Y', strtotime($c->fechasolicitud)) }}</td>
                <td>{{ $c->nombre }}</td>
                <td>{{ $c->nivelsolicitud }}</td>
                <td>{{ $c->razonSocial }}</td>
                <td>
                    @if ($c->estatus == '')
                        Sin estatus
                        @if (Session::get('sesiontipo') == 'Compras' || Session::get('sesiontipo') == 'Administrador')
                            <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                                @csrf
                                <input type="hidden" value="{{ $c->idrfq }}" name="idrfq">
                                <a href="#" class="editarInfoEstatus1">
                                    Agregar
                                </a>
                            </form>
                        @endif
                    @elseif($c->estatus != '')
                        {{ $c->estatus }}
                        @if (Session::get('sesiontipo') == 'Compras' || Session::get('sesiontipo') == 'Administrador')
                            <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                                @csrf
                                <input type="hidden" value="{{ $c->idrfq }}" name="idrfq">
                                <a href="#" class="editarInfoEstatus1">Cambiar</a>
                            </form>
                        @elseif (Session::get('sesiontipo') == 'Vendedor')
                            <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                                @csrf
                                <input type="hidden" value="{{ $c->idrfq }}" name="idrfq">
                                <a href="#" class="editarInfoEstatus1">Cambiar</a>
                            </form>
                        @endif
                    @endif
                </td>
                <td>
                    @if ($c->fechaentrega == '1900-01-01')
                        Sin fecha asignada
                    @else
                        {{ date('d-m-Y', strtotime($c->fechaentrega)) }}
                </td>
        @endif
        <td>
            @if ($c->fechaestimadaentrega == '')
                Sin fecha asignada
                @if (Session::get('sesiontipo') == 'Compras' || Session::get('sesiontipo') == 'Administrador')
                    <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                        @csrf
                        <input type="hidden" value="{{ $c->idrfq }}" name="idrfq">
                        <a href="#" class="editarInfoFecha1">Agregar</a>
                    </form>
                @endif
            @elseif($c->fechaestimadaentrega != '')
                {{ date('d-m-Y', strtotime($c->fechaestimadaentrega)) }}
                @if (Session::get('sesiontipo') == 'Compras' || Session::get('sesiontipo') == 'Administrador')
                    <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                        @csrf
                        <input type="hidden" value="{{ $c->idrfq }}" name="idrfq">
                        <a href="#" class="editarInfoFecha1">Cambiar</a>
                    </form>
                @endif
            @endif
        </td>
        <td>
            @if ($c->observaciones == '')
                Sin observaciones

                <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                    @csrf
                    <input type="hidden" value="{{ $c->idrfq }}" name="idrfq">
                    <a href="#" class="editarInfoObservacion1">Agregar</a>
                </form>
            @elseif($c->observaciones != '')
                Con observaciones

                <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                    @csrf
                    <input type="hidden" value="{{ $c->idrfq }}" name="idrfq">
                    <a href="#" class="editarInfoObservacion1">Ver / Cambiar</a>
                </form>
            @endif

        </td>
        <td>
            @if (Session::get('sesiontipo') == 'Compras' || Session::get('sesiontipo') == 'Administrador')
                <a href="{{-- {{ route('modificarRfq', ['idrfq' => $c->idrfq]) }} --}}">
                    <a href="{{ route('detalleRfq', ['idrfq' => $c->idrfq]) }}" target="blank">
                        <button type="button" class="btn btn-success">
                            <i class="ace-icon fa fa-eye bigger-120"></i>
                        </button>
                    </a>
                </a>
            @endif
            @if (Session::get('sesiontipo') == 'Compras' || Session::get('sesiontipo') == 'Administrador')
                <a href="{{ route('reporteCardex', ['idrfq' => $c->idrfq]) }}" target="blank">
                    <!-- ←Cardex-->
                    <button type="button" class="btn btn-warning">
                        <i class="ace-icon fa fa-bar-chart-o bigger-120"></i>
                    </button>
                </a>
            @endif
            @if (Session::get('sesiontipo') == 'Vendedor')
                <!-- @if ($c->deleted_at)
                    <a href="{{ route('activarRfq', ['idrfq' => $c->idrfq]) }}">
                        <button type="button" class="btn btn-warning">Activar</button>
                    </a>
                @else -->
                    <a href="{{ route('modificarRfq', ['idrfq' => $c->idrfq]) }}">
                        <button type="button" class="btn btn-info">
                            <i class="ace-icon fa fa-pencil bigger"></i>
                        </button>
                    </a>

                    <!-- <a href="{{ route('desactivarRfq', ['idrfq' => $c->idrfq]) }}">
                        <button type="button" class="btn btn-danger">
                            <i class="ace-icon fa fa-trash-o bigger-150"></i>
                        </button>
                    </a>
                @endif -->
            @endif

        </td>
        </tr>
        @endforeach
    </tbody>
</table>


<script>
    $(document).ready(function() {
        // Setup - add a text input to each footer cell
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#example thead tr').clone(true).addClass('filters').appendTo('#example thead');
            var table = $('#example').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel',
                ],
                orderCellsTop: true,
                fixedHeader: true,
                responsive: false,
                scrollX: true,
                // Definir tamaño de columnas segun su index
                columnDefs: [{
                    targets: 'no-display',
                    orderable: true,
                    visible: false
                }, {
                    targets: 1,
                    width: "30px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                        $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }
                }, {
                    targets: 2,
                    width: "20px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                        $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }
                }, {
                    targets: 3,
                    width: "200px"
                }, {
                    targets: 4,
                    width: "50px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                        $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }
                }, {
                    targets: 5,
                    width: "200"
                }, {
                    targets: 6,
                    width: "170px"
                }, {
                    targets: 7,
                    width: "20px"
                }, {
                    targets: 8,
                    width: "110px"
                }, {
                    targets: 9,
                    width: "120px"
                }, {
                    targets: 10,
                    width: "50px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                        $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }
                }, ],
                "order": [
                    [0, "desc"]
                ],
                // Poner tabla en español se puede modificar textos de ser necesario
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
                    }
                },
                // pageLength: 100,
                initComplete: function() {
                    var api = this.api();
                    // For each column
                    api.columns().eq(0).each(function(colIdx) {
                        // Set the header cell to contain the input element
                        var cell = $('.filters th').eq($(api.column(colIdx).header()).index());
                        var title = $(cell).text();
                        $(cell).html('<input type="text" placeholder="' + title + '" />');
                        // On every keypress in this input
                        $('input', $('.filters th').eq($(api.column(colIdx).header()).index()))
                            .off('keyup change')
                            .on('keyup change', function(e) {
                                e.stopPropagation();
                                // Get the search value
                                $(this).attr('title', $(this).val());
                                var regexr =
                                    '({search})'; //$(this).parents('th').find('select').val();
                                var cursorPosition = this.selectionStart;
                                // Search the column for that value
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
                }
            });            
        });

        $(document).on('click', '.editarInfoEstatus1', function(e) {
            e.preventDefault();
            $('#modalInfoEstatus').load('{{ url('editarInfoEstatus') }}' + '?' + $(this).closest(
                'form').serialize());
        });

        $(document).on('click', '.editarInfoFecha1', function(e) {
            e.preventDefault();
            $('#modalInfoFecha').load('{{ url('editarInfoFecha') }}' + '?' + $(this).closest('form')
                .serialize());
        });

        $(document).on('click', '.editarInfoObservacion1', function(e) {
            e.preventDefault();
            $('#modalInfoObservacion').load('{{ url('editarInfoObservacion') }}' + '?' + $(this)
                .closest('form').serialize());
        });

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
</style>