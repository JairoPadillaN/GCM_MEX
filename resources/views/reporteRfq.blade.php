@extends('principal')
@section('contenido')
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".cargar").click();
        });
    </script>

    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1>Reporte RFQs</h1>
            </div>
            <div class="panel-body" style="overflow-x: auto;">
                @if (Session::get('sesiontipo') == 'Vendedor')
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ asset('altaRfq') }}">
                                <button type="button" class="btn btn-primary">Crear RFQ</button>
                            </a>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success notiButton" id="notiButton">
                            <i class="ace-icon fa fa-bell bigger-120"></i>
                            Tienes {{ $cuantoss }} Notificaciones de RFQ's
                        </button>
                    </div>

                </div>

                <div class="panel-body">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class=" cargar nav-link active" id="pills-orden-tab" data-toggle="pill" href="#orden"
                                role="tab" aria-controls="pills-home" aria-selected="true" data-tab="orden">Activos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-entregados-tab" data-toggle="pill" href="#entregados"
                                role="tab" aria-controls="pills-home" aria-selected="true"
                                data-tab="entregados">Entregados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-cancelados-tab" data-toggle="pill" href="#cancelados"
                                role="tab" aria-controls="pills-home" aria-selected="true"
                                data-tab="cancelados">Cancelados</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade" id="orden" role="tabpanel" aria-labelledby="pills-home-tab">
                            <br><br>
                            <div id="tabla-activos">
                            </div>
                        </div>
                        <!-- TAB DE ENTREGADOS -->
                        <div class="tab-pane fade" id="entregados" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div id="tabla-entregados">
                            </div>
                        </div>
                        <!-- TAB DE CANCELADOS -->
                        <div class="tab-pane fade" id="cancelados" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div id="tabla-cancelados">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modalInfoEstatus"></div>
    <div id="modalInfoFecha"></div>
    <div id="modalInfoObservacion"></div>
    <div id="modalNotificacion"></div>
    <div id="modalDetalle"></div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>


    {{-- Script datatable --}}
    <script>
        $(document).ready(function() {
            var sesionTipo = "{{ $stipo }}";            
            var activeTable = null; // Variable para mantener la tabla activa                    

            $(document).on('cargarTabla', function(event, nombreTab) {
                cargarTabla(nombreTab);
            });

            // Función para cargar el contenido de las pestañas
            function cargarTabla(tabId) {
                var $tablaContainer;
                var ruta;

                if (tabId === 'orden') {
                    $tablaContainer = $('#tabla-activos');
                    ruta = '{{ route('tablaActivos') }}';
                } else if (tabId === 'entregados') {
                    $tablaContainer = $('#tabla-entregados');
                    ruta = '{{ route('tablaEntregados') }}';
                } else if (tabId === 'cancelados') {
                    $tablaContainer = $('#tabla-cancelados');
                    ruta = '{{ route('tablaCancelados') }}';
                }

                $.ajax({
                    url: ruta,
                    type: 'GET',
                    dataType: 'html',
                    success: function(data) {
                        $tablaContainer.html(data); // Coloca el contenido dentro del div seleccionado

                        activeTable = $tablaContainer.find('table').DataTable({
                            dom: 'Bfrtip',
                            buttons: [
                                'excel',
                            ],
                            orderCellsTop: true,
                            fixedHeader: true,
                            responsive: false,
                            // Definir tamaño de columnas segun su index
                            columnDefs: [{
                                targets: 'no-display',
                                orderable: true,
                                visible: false
                            }, {
                                targets: 1,                                
                                createdCell: function(td, cellData, rowData, rowIndex,
                                    colIndex) {
                                    $(td).css('text-align',
                                        'center'
                                    );
                                }
                            }, {
                                targets: 2,                                
                                createdCell: function(td, cellData, rowData, rowIndex,
                                    colIndex) {
                                    $(td).css('text-align',
                                        'center'
                                    );
                                }
                            }, 
                            {
                                targets: 3,
                                visible: (sesionTipo === 'Vendedor') ? false : true,
                            }, {
                                targets: 4,
                                createdCell: function(td, cellData, rowData, rowIndex,
                                    colIndex) {
                                    $(td).css('text-align',
                                        'center'
                                    );
                                }
                            }, {
                                targets: 10,
                                createdCell: function(td, cellData, rowData, rowIndex,
                                    colIndex) {
                                    $(td).css('text-align',
                                        'center'
                                    );
                                }
                            }, ],
                            "order": [
                                [0, "desc"]
                            ],
                            // scrollX: true,
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
                                api.columns().eq(0).each(function(colIdx) {
                                    var cell = $('.filters th').eq($(api.column(
                                        colIdx).header()).index());
                                    var title = $(cell).text();
                                    $(cell).html(
                                        '<input type="text" placeholder="' +
                                        title + '" />');
                                    $('input', $('.filters th').eq($(api.column(
                                            colIdx).header()).index()))
                                        .off('keyup change')
                                        .on('keyup change', function(e) {
                                            e.stopPropagation();
                                            $(this).attr('title', $(this)
                                                .val());
                                            var regexr =
                                                '({search})';
                                            var cursorPosition = this
                                                .selectionStart;
                                            api
                                                .column(colIdx)
                                                .search((this.value != "") ?
                                                    regexr.replace('{search}',
                                                        '(((' + this.value +
                                                        ')))') : "", this
                                                    .value !=
                                                    "", this.value == "")
                                                .draw();
                                            $(this).focus()[0]
                                                .setSelectionRange(
                                                    cursorPosition,
                                                    cursorPosition);
                                        });
                                });
                            }
                        });
                    }
                });
            }

            cargarTabla('orden');

            function limpiarContenidoTablas() {
                $('#tabla-activos').empty();
                $('#tabla-entregados').empty();
                $('#tabla-cancelados').empty();
                destruirTabla();
            }

            $('.nav-link').click(function() {
                var tabId = $(this).data('tab');
                limpiarContenidoTablas(); // Limpia el contenido antes de cargar la nueva tabla
                cargarTabla(tabId); // Cargar la tabla
            });

            function destruirTabla() {
                if (activeTable !== null) {
                    activeTable.destroy(); // Destruye la tabla actual si existe
                    activeTable = null; // Limpia la variable
                }
            }

            $(document).on('click', '.editarInfoEstatus', function(e) {
                e.preventDefault();
                $('#modalInfoEstatus').load('{{ url('editarInfoEstatus') }}' + '?' + $(this).closest(
                    'form').serialize());
            });

            $(document).on('click', '.editarInfoFecha', function(e) {
                e.preventDefault();
                $('#modalInfoFecha').load('{{ url('editarInfoFecha') }}' + '?' + $(this).closest('form')
                    .serialize());
            });

            $(document).on('click', '.editarInfoObservacion', function(e) {
                e.preventDefault();
                $('#modalInfoObservacion').load('{{ url('editarInfoObservacion') }}' + '?' + $(this)
                    .closest('form').serialize());
            });

            $(document).on('click', '.notiButton', function(e) {
                e.preventDefault();
                $('#modalNotificacion').load('{{ url('modalNotificacion') }}' + '?' + $(this)
                    .closest('form').serialize());
            });

            $(document).on('click', '.mDetalle', function(e) {
                e.preventDefault();
                $('#modalDetalle').load('{{ url('modalDetalle') }}' + '?' + $(this)
                    .closest('form').serialize());
            });
        });
    </script>

    <style>
        .hide {
            display: none;
        }
    </style>

@stop