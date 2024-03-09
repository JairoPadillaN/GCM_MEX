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
<script src="https://kit.fontawesome.com/6832f025eb.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Reporte de Viajes</h1>
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
                                </td>
                                <td>
                                    <a href="{{asset('altaSalida_Viajes')}}">
                                        <button type="button" class="btn btn-primary ">
                                            <i class="fa-solid fa-car"></i>
                                             Nuevo viaje
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    
                    <table id="TablaViajes" class="table-bordered display" cellspacing="0" width="100%">
                        <thead>
                            <tr style="background-color: #C5EBFB">
                                <th style="background-color: #C5EBFB;">Fecha</th> 
                                <th style="background-color: #C5EBFB;">Conductor</th>
                                <th style="background-color: #C5EBFB;">Empresa</th>
                                <th style="background-color: #C5EBFB;">Sucursal</th>
                                <th style="background-color: #C5EBFB;">Auto</th>
                                <th style="background-color: #C5EBFB;">Kilometros Recorridos</th>
                                <th style="background-color: #C5EBFB;">Monto</th>
                                <th style="background-color: #C5EBFB;">Estatus</th>
                                <th style="background-color: #C5EBFB;">Opciones</th>
                            </tr>
                        </thead> 
                        <tbody>
                            @forEach($consulta as $repViaje)
                            <tr>
                                <td class="sorting_1">{{\Carbon\Carbon::parse($repViaje->fechaInicio)->format('d/m/Y')}}</td>
                                <td class="sorting_1">{{$repViaje->us}}</td>
                                <td class="sorting_1">{{$repViaje->cli}}</td>
                                <td class="sorting_1">{{$repViaje->suc}}</td>
                                <td class="sorting_1">{{$repViaje->veh}}</td>
                                <td class="sorting_1">{{$repViaje->kmr}} Km</td>
                                <td class="sorting_1">${{$repViaje->montoGasto}}.00</td>
                                <td class="sorting_1">{{$repViaje->estatus}}</td>
                                <td>
                                    @if($repViaje->activo =='No') 
                                        <a href="{{URL::action('ViajesController@restaurarSalida_Viajes',['idViaje'=>$repViaje->idViaje])}}"
                                        class="btn btn-xs btn-warning restaurarViaje" style="width:107px"><i class="fa-solid fa-arrow-rotate-right"></i> Restaurar</a>
                                    @endif 
                                    @if($repViaje->estatus =='En curso') 
                                        <a href="{{URL::action('ViajesController@altaRegreso_Viajes',['idViaje'=>$repViaje->idViaje])}}"
                                        class="btn btn-xs btn-success" style="width:107px; "><i class="fa fa-check"></i> Finalizar viaje</a> 
                                        <a href="{{URL::action('ViajesController@modificarSalida_Viajes',['idViaje'=>$repViaje->idViaje])}}"
                                        class="btn btn-xs btn-info" style="width:107px; "><i class="ace-icon fa fa-pencil bigger-120"></i> Editar salida</a>
                                        <a href="{{URL::action('ViajesController@eliminarSalida_Viajes',['idViaje'=>$repViaje->idViaje])}}"
                                        class="btn btn-xs btn-danger eliminarViaje" style="width:107px; "><i class="ace-icon fa fa-trash-o bigger-120"></i> Eliminar Salida</a> 
                                    @else 
                                        <a href="{{URL::action('ViajesController@detalle_Viajes',['idViaje'=>$repViaje->idViaje])}}"
                                        class="btn btn-xs btn-danger" style="background-color:#FA8072; width:107px;"><i class="fa-solid fa-circle-info"></i> Detalle del viaje</a> 
                                    @if($repViaje->estatus =='Finalizado') 
                                        <a href="{{URL::action('ViajesController@modificar_Viajes',['idViaje'=>$repViaje->idViaje])}}"
                                        class="btn btn-xs btn-info" style="width:107px; "><i class="ace-icon fa fa-pencil bigger-120"></i> Editar viaje</a>
                                    @endif 
                                    @endif
                                </td>
                            </tr>
                            @endforEach
                        </tbody>
                    </table>
                   

                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <script>
        function mostrarToastGuardado() {
            const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
    
        Toast.fire({
            icon: "success",
            title: "{{ session('success') }}"
        });
        }
    
    
        function mostrarToastErrorGuardado() {
            const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
    
        Toast.fire({
            icon: "error",
            title: "{{ session('error') }}"
        });
        }
    
        function mostrarToastEliminado() {
            const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
    
        Toast.fire({
            icon: "warning",
            title: "{{ session('status') }}"
        });
        }
    
        @if(session('success'))
            mostrarToastGuardado();
        @elseif(session('error'))
            mostrarToastErrorGuardado();
        @elseif(session('status'))
        mostrarToastEliminado();
        @endif
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


            $('#TablaViajes thead tr').clone(true).addClass('filters').appendTo('#TablaViajes thead');
            var table = $('#TablaViajes').DataTable({
                dom: 'Bfrtip',
                dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>rt<"row"<"col-sm-3"l><"col-sm-3"i><"col-sm-6"p>>',//En este dom se muestra el apartado de cuantos registros mostrar
                buttons: [''],
                orderCellsTop: true,
                fixedHeader: true,
                scrollX: true,
                order: [
                    [0, "desc"]//Ordenar tabla
                ],
                language: {
                info:           "Mostrando _START_ a _END_ de _TOTAL_ registros",
                sInfoFiltered:  "(filtrado de un total de _MAX_ registros)",
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
            initComplete: function(settings, json) {
                var api = this.api();

                // Configurar filtros en la fila del encabezado
                api.columns().eq(0).each(function(colIdx) {
                    var cell = $('.filters th').eq($(api.column(colIdx).header()).index());
                    var title = $(cell).text();

                    // Ocultar el input para las columnas 4 y 5
                    if (colIdx !== 5 && colIdx !== 8) {
                        $(cell).html('<input type="text" placeholder="' + title + '" />');
                        $('input', $(cell))
                            .off('keyup change')
                            .on('keyup change', function(e) {
                                e.stopPropagation();
                                $(this).attr('title', $(this).val());
                                var regexr = '({search})';
                                var cursorPosition = this.selectionStart;

                                // Aplicar búsqueda solo si no es la columna 5 y 8
                                api.column(colIdx).search((this.value != "") ? regexr.replace('{search}',
                                    '(((' + this.value + ')))') : "", this.value != "", this.value == "")
                                    .draw();

                                $(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);
                            });
                    } else {
                        // Ocultar el input para las columnas 4 y 5
                        $(cell).empty();
                    }
                });

                // Deshabilitar la búsqueda para las columnas 4 y 5 después de la inicialización
                api.columns([5, 8]).search('', false, false).draw(false);
            },
                columnDefs: [
                    {targets: 2,
                    width: "100px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 1,
                    width: "100px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 0,
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
                    width: "150px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 4,
                    width: "100px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 5,
                    width: "30px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 6,
                    width: "50px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 7,
                    width: "70px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
                    {targets: 8,
                    width: "250px",
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
            ],
            // Mostrar los botones de paginacion
            pagingType: 'full_numbers',
            // Numero de registros por pagina
            lengthMenu: [10,15,25,50,100],
            });

            document.querySelectorAll('#min, #max').forEach((el) => {
                el.addEventListener('change', () => $('#TablaViajes').DataTable().draw());
            });

            $('.dt-buttons').appendTo($('#botonExcel').parent());

            $('#exportarExcel').on('click', function() {
            table.button(0).trigger();
             });


            var searchInput = $('.dataTables_filter input').clone(true);

            $('#searchContainer').append(searchInput);

                /*----------------------Eliminar Viaje-------------------------*/

                $(document).on('click', '.eliminarViaje', function(event) {
                event.preventDefault();

                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                title: "¿Está seguro de eliminar este viaje?",
                text: "¡Está acción se puede revertir!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Si, Eliminar!",
                cancelButtonText: "No, Cancelar!",
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    swalWithBootstrapButtons.fire({
                    title: "¡Eliminado!",
                    text: "Su viaje ha sido eliminado.",
                    icon: "success"
                    });

                    // Redirect to the link's href attribute after the user confirms the deletion
                    window.location.href = $(this).attr('href');
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire({
                    title: "Cancelado",
                    text: "Aún se conserva el viaje",
                    icon: "error"
                    });
                }
                });
            });

                /*----------------------Restaurar Viaje-------------------------*/

                $(document).on('click', '.restaurarViaje', function(event) {
                event.preventDefault();

                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                title: "¿Está seguro de restaurar este viaje?",
                text: "¡Está acción pondra en activo el viaje cancelado!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Si, Restaurar!",
                cancelButtonText: "No, Cancelar!",
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    swalWithBootstrapButtons.fire({
                    title: "¡Restaurado!",
                    text: "Su viaje ha sido restaurado.",
                    icon: "success"
                    });

                    // Redirect to the link's href attribute after the user confirms the deletion
                    window.location.href = $(this).attr('href');
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire({
                    title: "Cancelado",
                    text: "El viaje sigue cancelado",
                    icon: "error"
                    });
                }
                });
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
