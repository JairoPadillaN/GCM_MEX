@extends('principal')
@section('contenido')
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1>Detalles kardex</h1>
            </div>
            <div class="panel-body" style="overflow-x: auto;">
                <div class="row">
                </div>
                <br><br>
                <div id="tablaCardex">
                    <!-- Aqui va una condicional por si no hay nada en el cardex-->
                    <!--<br>
                        <div class="alert alert-warning" role="alert" align="center">
                            <label for="">Sin modificaciones del cardex</label>
                        </div>-->
                    <table class="display" id="Cardex" style="width:100%">
                        <thead>
                            <tr>
                                <th class="no-display">idcardex</th>
                                <th>Fecha de cambio</th>
                                <th>RFQ</th>
                                <th>Usuario que realizo el cambio</th>
                                <th>Estatus Anterior</th>
                                <th>Nuevo Estatus</th>
                                <th>Obsevaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cardex as $car)
                                <tr>
                                    <td>{{ $car->idcardex }}</td>
                                    <td>{{ date('d-m-Y', strtotime($car->fecha)) }}</td>
                                    <td>{{ $car->cmgr }}</td>
                                    <td>{{ $car->usucam }}</td>
                                    <td>{{ $car->idstatusD }}</td>
                                    <td>{{ $car->idstatusA }}</td>
                                    <td>
                                        @if ($car->observaciones == '')
                                            No hay observaciones
                                        @else
                                            {{ $car->observaciones }}
                                    </td>
                            @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>


    <script>
        $(document).ready(function() {
            var table = $('#Cardex').DataTable({
                columnDefs: [{
                    targets: 'no-display',
                    orderable: true,
                    visible: false
                }, ],
                "order": [
                    [0, "desc"]
                ],
                language: {
                    "sSearch": "Buscar:",
                },
                paging: false,
                info: false,
                fixedHeader: {
                    header: true,
                    footer: false
                }

            });
        });
    </script>

    <style>
        thead input {
            width: 100%;
        }
    </style>
@stop
