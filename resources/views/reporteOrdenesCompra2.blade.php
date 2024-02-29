@extends('principal')

@section('contenido')
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.13.6/dataRender/datetime.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/plug-ins/1.11.6/sorting/datetime-moment.js"></script> --}}


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">

    <script type="text/javascript">
        $(document).ready(function() {
            $(".cargar").click();
        });
    </script>
    <style>
        .margin {
            margin: 4px;
        }
    </style>

    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Reporte Ordenes de Compra</h1>
            </div>
            <div class="panel-body" style="overflow-x: auto;">

                <div class="panel-body">

                    <div class="row">
                        <div class="col-sm-12" style="margin-bottom:10px;text-align:right">
                            <a href="{{ URL::action('ordenCompraController@altaOrdenCompra') }}">
                                <button type="button" class="btn btn-primary ">Agregar nueva orden</button>
                            </a>
                        </div>
                    </div>

                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class=" cargar nav-link active" id="pills-activas-tab" data-toggle="pill" href="#activas"
                                role="tab" aria-controls="pills-home" aria-selected="true"
                                data-tab="activas">Activas</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="pills-canceladas-tab" data-toggle="pill" href="#canceladas"
                                role="tab" aria-controls="pills-home" aria-selected="true"
                                data-tab="canceladas">Canceladas</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade" id="activas" role="tabpanel" aria-labelledby="pills-home-tab">
                            <br><br>
                            <!-- TAB DE activas -->
                            <div id="tabla-activas">
                                
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="canceladas" role="tabpanel" aria-labelledby="pills-home-tab">
                            <br><br>
                            <!-- TAB DE CANCELADOS -->
                            <div id="tabla-canceladas">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {            
            var activeTable = null; // Variable para mantener la tabla activa    

            function destruirTabla() {
                if (activeTable !== null) {
                    activeTable.destroy();
                    activeTable = null;
                }
            }

            $(document).on('cargarTabla', function(event, nombreTab) {
                limpiarContenidoTablas();
                destruirTabla(); // Asegúrate de destruir la tabla antes de cargar una nueva
                let minDate, maxDate;
                cargarTabla(nombreTab);
            });

            function cargarTabla(tabId) {
                var $tablaContainer;
                var ruta;

                if (tabId === 'activas') {
                    $tablaContainer = $('#tabla-activas');
                    ruta = '{{ route('tablaOrdenesActivas') }}';
                } else if (tabId === 'canceladas') {
                    $tablaContainer = $('#tabla-canceladas');
                    ruta = '{{ route('tablaOrdenesCanceladas') }}';
                }

                $.ajax({
                    url: ruta,
                    type: 'GET',
                    dataType: 'html',
                    success: function(data) {
                        $tablaContainer.html(data); 
                    }
                });
            }

            cargarTabla('activas');

            function limpiarContenidoTablas() {
                $('#tabla-activas').empty();
                $('#tabla-canceladas').empty();
                destruirTabla();
            }

            $('.nav-link').click(function() {
                var tabId = $(this).data('tab');
                limpiarContenidoTablas(); // Limpia el contenido antes de cargar la nueva tabla
                cargarTabla(tabId); // Cargar la tabla
            });
            
        });
    </script>
@stop
