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
                <h1>Archivos contables</h1>
            </div>
            <div class="panel-body" style="overflow-x: auto;">
            @if (Session::get('sesiontipo') == 'Contador')
                    
                    @else
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ asset('altaContable') }}">
                                <button type="button" class="btn btn-primary">Nuevo</button>
                            </a>
                        </div>
                    </div>
                    @endif
                <div class="row">
                </div>
                <br><br>
                @if ($cuantos > 0)
                    <div id="tablaContables">
                        <table class="display" id="Cont" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="no-display">idc</th>
                                    <th>Mes</th>
                                    <th>Año</th>
                                    <th>Empresa</th>
                                    <th>Archivos subidos</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($consultacion as $cons)
                                    <tr>
                                        <td>{{ $cons->idac }}</td>
                                        <td>{{ $cons->mes }}</td>
                                        <td>{{ $cons->ano }}</td>
                                        <td>{{ $cons->empresa }}</td>
                                        <td>
                                            <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                                                @csrf
                                                <input type="hidden" value="{{ $cons->idac }}" name="idac">
                                                <a href="{{ route('reporteDetalle') }}" class="verarchivos">Archivos
                                                    {{ $cons->archivos }}</a>
                                            </form>
                                        </td>
                                        <td>
                                            @if ($cons->deleted_at)
                                                <a href="{{ route('activarCont', ['idac' => $cons->idac]) }}">
                                                    <button type="button" class="btn btn-warning">Activar</button>
                                                </a>
                                            @else
                                                <a href="{{ route('desactivarCont', ['idac' => $cons->idac]) }}">
                                                    <button type="button" class="btn btn-danger">
                                                        <i class="ace-icon fa fa-trash-o bigger-150"></i>
                                                    </button>
                                                    <a href="{{ route('altaArchivos', ['idac' => $cons->idac]) }}">
                                                        <button type="button" class="btn btn-info">
                                                            <i class="ace-icon fa fa-pencil bigger"></i>
                                                        </button>
                                                    </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning" role="alert" align="center">
                        <label for="">No hay archivos...</label>
                    </div>
                @endif

            </div>
        </div>
    </div>
    <div id="modalArchivos"></div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#Cont thead tr');
            var table = $('#Cont').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                columnDefs: [{
                    targets: 'no-display',
                    orderable: true,
                    visible: false
                }, ],
                "order": [
                    [2, "desc"]
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
            });


            $('#tablaContables').on('click', '.verarchivos', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                var url = '{{ route('reporteDetalle') }}' + '?' + form.serialize();
                $('#modalArchivos').load(url);
            });

            if ('{{ Session::get('sesiontipo') }}' === 'Contador') {
            table.column(5).visible(false);
        }

        });
    </script>

@stop
