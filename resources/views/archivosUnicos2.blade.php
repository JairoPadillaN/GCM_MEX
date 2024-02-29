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
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://kit.fontawesome.com/6832f025eb.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="col-md-12">
    <div class="panel panel-default" style="margin-top: -45px">
        <div class="panel-heading clearfix">
            <h1> Archivos Únicos </h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <a href="{{url('altaArchivoUnico')}}" type="button" class="btn btn-success"><i class="fa-solid fa-plus"></i> Nuevo</a>
                </div>
{{--                 <div class="col-md-4 mb-4 text-right" style="font-size: 20px">
                    <label for="">Buscar: </label>
                </div>
                <div class="col-md-4 mb-4">
                    <div id="searchContainer"></div>
                </div> --}}
            </div><br>
            <div id="list" class="table-responsive tablaUnicos">

                <table id="archivosUnicos" class="table table-striped  display" cellspacing="0" width="100%">
                    <thead>
                        <tr style="backgroun-color: #E0E0E0">
                            <th style="background-color: #C5EBFB visibility:collapse; display:none;">ID</th>
                            <th style="background-color: #C5EBFB; text-align: center">Nombre del Archivo</th>
                            {{-- <th style="background-color: #C5EBFB">Descripción</th> --}}
                            <th style="background-color: #C5EBFB; text-align: center">Usuario</th>
                            <th style="background-color: #C5EBFB; text-align: center">Fecha</th>
                            <th style="background-color: #C5EBFB; text-align: center">Archivo</th>
                            <th style="background-color: #C5EBFB; text-align: center">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($archivosUnicos as $U)
                        <tr>
                            <td style="visibility:collapse; display:none;">{{$U->id}}</td>
                            <td>{{$U->nombreArchivo}}</td>
                            {{-- <td>{{$U->descripcion}}</td> --}}
                            <td>{{$U->id_usuario}}</td>
                            <td>{{ \Carbon\Carbon::parse($U->fecha)->format('d/m/Y') }}</td>
                            <td>

                                    <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                                        @csrf
                                        <input type="hidden" value="{{ $U->id }}" name="id">
                                        <a href="{{ route('reporteArchivoUnico') }}" class="verarchivos">Archivos
                                        </a>
                                    </form>

                            </td>
                            <td>

                                    <a 
                                    class="btn btn-info editarArchivo" 
                                    href="{{route('obtenerArchivoUnico',$U->id)}}">
                                    <i class="fa-regular fa-pen-to-square"></i></a>
                                    <a 
                                    class="btn btn-danger eliminarArchivo" 
                                    href="{{route('eliminarArchivoUnico', $U->id)}}">
                                    <i class="fa-regular fa-trash-can"></i></a>

                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>                
            </div>
        </div>
    </div>
</div>

<div id="modalArchivos"></div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="sweetalert2.all.min.js"></script>

<script>
    function mostrarToastArchivoGuardado() {
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


    function mostrarToastArchivoErrorGuardado() {
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

    function mostrarToastArchivoEliminado() {
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
        mostrarToastArchivoGuardado();
    @elseif(session('error'))
        mostrarToastArchivoErrorGuardado();
    @elseif(session('status'))
    mostrarToastArchivoEliminado();
    @endif
</script>

<script>
    $(document).ready(function() {

/*         DataTable.ext.search.push(function(settings, data, dataIndex) {
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
    }); */


        $('#archivosUnicos thead tr').clone(true).addClass('filters').appendTo('#archivosUnicos thead');
        var table = $('#archivosUnicos').DataTable({
            buttons: [''],
            dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>rt<"row"<"col-sm-3"l><"col-sm-3"i><"col-sm-6"p>>',//En este dom se muestra el apartado de cuantos registros mostrar
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
/*             initComplete: function() {
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
            }, */
            initComplete: function(settings, json) {
                var api = this.api();

                // Configurar filtros en la fila del encabezado
                api.columns().eq(0).each(function(colIdx) {
                    var cell = $('.filters th').eq($(api.column(colIdx).header()).index());
                    var title = $(cell).text();

                    // Ocultar el input para las columnas 4 y 5
                    if (colIdx !== 4 && colIdx !== 5) {
                        $(cell).html('<input type="text" placeholder="' + title + '" />');
                        $('input', $(cell))
                            .off('keyup change')
                            .on('keyup change', function(e) {
                                e.stopPropagation();
                                $(this).attr('title', $(this).val());
                                var regexr = '({search})';
                                var cursorPosition = this.selectionStart;

                                // Aplicar búsqueda solo si no es la columna 4 o 5
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
                api.columns([4, 5]).search('', false, false).draw(false);
            },
            columnDefs: [
                {targets: 0,
                width: "auto",
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'left'); // centrar el contenido de la celda
                }},
                {targets: 1,
                width: "auto",
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'left'); // centrar el contenido de la celda
                }},
                {targets: 2,
                width: "auto",
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'left'); // centrar el contenido de la celda
                }},
                {targets: 3,
                    type: 'date',
                    width: "auto",
                    render: function (data, type, full, meta) {
                    if (type === 'sort' || type === 'type') {
                        return moment(data, 'DD/MM/YYYY').format('YYYY-MM-DD');
                    }
                    return data;
                    },
                    createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                    $(td).css('text-align', 'center'); // centrar el contenido de la celda
                    }},
/*                 {targets: 3,
                width: "auto",
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'center'); // centrar el contenido de la celda
                }}, */
                {targets: 4,
                width: "auto",
                
                orderable: false,
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'center'); // centrar el contenido de la celda
                }},
                {targets: 5,
                width: "auto",
                
                orderable: false,
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'center'); // centrar el contenido de la celda
                }},
        ],
/*             // Mostrar los botones de paginacion
            pagingType: 'full_numbers', */
            // Numero de registros por pagina
            lengthMenu: [10,20,50,100],
        });

        document.querySelectorAll('#min, #max').forEach((el) => {
            el.addEventListener('change', () => $('#archivosUnicos').DataTable().draw());
        });

/*         $('.dt-buttons').appendTo($('#botonExcel').parent());

        $('#exportarExcel').on('click', function() {
        table.button(0).trigger();
         }); */


        var searchInput = $('.dataTables_filter input').clone(true);

        $('#searchContainer').append(searchInput);

        $('.tablaUnicos').on('click', '.verarchivos', function(e) {
                    e.preventDefault();
                    var form = $(this).closest('form');
                    var url = '{{ route('reporteArchivoUnico') }}' + '?' + form.serialize();
                    $('#modalArchivos').load(url);
                });

        /*----------------------Eliminar archivo-------------------------*/

        $(document).on('click', '.eliminarArchivo', function(event) {
        event.preventDefault();

        const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
        title: "¿Está seguro de eliminar este archivo?",
        text: "¡Está acción no se puede revertir!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Eliminar!",
        cancelButtonText: "No, Cancelar!",
        reverseButtons: true
        }).then((result) => {
        if (result.isConfirmed) {
            swalWithBootstrapButtons.fire({
            title: "¡Eliminado!",
            text: "Su archivo ha sido eliminado.",
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
            text: "Aún se conserva el archivo",
            icon: "error"
            });
        }
        });
    });
    });

</script>




<style>
    thead input {
     width: auto;
 }

 button:focus {
     outline: none;
 }

 .btn:focus {
     outline: none;
 }

/*  .dataTables_wrapper table {
     text-align: center;
 }

 .dataTables_wrapper table thead th {
     text-align: center;
 } */

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
     width: auto;
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
     width: auto;
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