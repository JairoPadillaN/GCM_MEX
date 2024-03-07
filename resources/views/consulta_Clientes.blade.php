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
            <h1>Reporte de Clientes</h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4 mb-4" align="left">
                    <a href="{{asset('alta_Clientes')}}">
                        <button type="button" class="btn btn-primary">
                            <i class="fa-solid fa-user-plus"></i>
                            Nuevo Cliente</button></a>                        
                </div>
            </div><br>
            <div class="table-responsive tablaClientes" id="list">
                <table class="table table-striped display" id="reporteClientes" cellspacing="0" width="100%">
                    <thead>
                        <tr style="background-color: #C5EBFB">
                            <th style="background-color: #C5EBFB visibility:collapse; display:none;">ID</th>
                            <th style="background-color: #c5ebfb text-align: center">Razón Social</th>
                            <th style="background-color: #C5EBFB text-align: center">RFC</th>
                            <th style="background-color: #C5EBFB text-align: center">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consulta as $cl)
                        <tr>
                            <td style="visibility:collapse; display:none;">{{$cl->id}}</td>
                            <td class="sorting_1">{{$cl->razonSocial}}</td>
                            <td class="sorting_1">{{$cl->rfc}}</td>
                            @if(Session::get('sesiontipo')=="Administrador")
                            <td align="center">                          
                                    <a href="{{URL::action('ClientesController@eliminar_Clientes',['idc'=>$cl->idc])}}"
                                        type="submit" class="btn btn-xs btn-danger eliminarCliente"><i
                                            class="ace-icon fa fa-trash-o bigger-120"></i></a>                            
                                    <a href="{{URL::action('ClientesController@modifica_Clientes',['idc'=>$cl->idc])}}"
                                        class="btn btn-xs btn-info"><i
                                            class="ace-icon fa fa-pencil bigger-120"></i></a>
                                </td>
                            @else
                                @if($cl->activo=='si')
                                <td align="center">                          
                                    <a href="{{URL::action('ClientesController@modifica_Clientes',['idc'=>$cl->idc])}}"
                                        class="btn btn-xs btn-info" style="width:67px;"><i
                                            class="ace-icon fa fa-pencil bigger-120"></i></a>
                                </td>
                                @endif
                            @endif
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

        $('#reporteClientes thead tr').clone(true).addClass('filters').appendTo('#reporteClientes thead');
        var table = $('#reporteClientes').DataTable({
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

            initComplete: function(settings, json) {
                var api = this.api();

                // Configurar filtros en la fila del encabezado
                api.columns().eq(0).each(function(colIdx) {
                    var cell = $('.filters th').eq($(api.column(colIdx).header()).index());
                    var title = $(cell).text();

                    // Ocultar el input para la columna 4 
                    if (colIdx !== 3) {
                        $(cell).html('<input type="text" placeholder="' + title + '" />');
                        $('input', $(cell))
                            .off('keyup change')
                            .on('keyup change', function(e) {
                                e.stopPropagation();
                                $(this).attr('title', $(this).val());
                                var regexr = '({search})';
                                var cursorPosition = this.selectionStart;

                                // Aplicar búsqueda solo si no es la columna 3
                                api.column(colIdx).search((this.value != "") ? regexr.replace('{search}',
                                    '(((' + this.value + ')))') : "", this.value != "", this.value == "")
                                    .draw();

                                $(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);
                            });
                    } else {
                        // Ocultar el input para las columna 4
                        $(cell).empty();
                    }
                });

                // Deshabilitar la búsqueda para las columnas 4 y 5 después de la inicialización
                api.columns([3]).search('', false, false).draw(false);
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
                width: "100px",
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'center'); // centrar el contenido de la celda
                }},
        ],
/*             // Mostrar los botones de paginacion
            pagingType: 'full_numbers', */
            // Numero de registros por pagina
            lengthMenu: [20,50,100],
        });

        document.querySelectorAll('#min, #max').forEach((el) => {
            el.addEventListener('change', () => $('#reporteClientes').DataTable().draw());
        });


        var searchInput = $('.dataTables_filter input').clone(true);

        $('#searchContainer').append(searchInput);


        /*----------------------Eliminar archivo-------------------------*/

        $(document).on('click', '.eliminarCliente', function(event) {
        event.preventDefault();

        const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
        title: "¿Está seguro de eliminar este cliente?",
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
            text: "El cliente ha sido eliminado.",
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
            text: "Aún se conserva la información del cliente",
            icon: "error"
            });
        }
        });
    });
    if ('{{ Session::get('sesiontipo') }}' === 'Contador') {
            table.column(5).visible(false);}
        
    
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