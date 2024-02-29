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
            <h1 class="">Reporte de proveedores</h1>
        </div><br>
        <div class="panel-body"> 
            <a href="{{asset('altaProveedores')}}"><button type="button" class="btn btn-primary ">Agregar nuevo
                    proveedor</button></a>
            <br><br>
            <div class="table-responsive">
                <table id="example" class="display" style="width:100%">
                    <thead class="bg-gray-dark color-palette">
                        <tr style="background-color:#93acff41;">
                            <th>Nombre de proveedor</th>
                            <th>Razón Social</th>
                            <th>RFC</th>
                            <th>Iniciales </th>
                            <th>Correo </th>
                            <th>Usuario</th>
                            <th>Teléfono </th>
                            <th>Condiciones </th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consulta as $p)
                        <tr>
                            <td class="sorting_1">{{$p->nombreProv}} {{$p->segundoNomProv}} {{$p->apellidoProv}}</td>
                            <td class="sorting_1">{{$p->razonSocialProv}}</td>
                            <td class="sorting_1">{{$p->rfcProveedor}}</td>
                            <td class="sorting_1">{{$p->iniciales}}</td>
                            <td class="sorting_1">{{$p->correoProveedor}}</td>
                            <td class="sorting_1">{{$p->usuarioProv}}</td>
                            <td class="sorting_1">{{$p->telefonoProveedor}}</td>
                            <td class="sorting_1">{{$p->condicionesProv}}</td>
                            <td align="center">
                                @if($p->activo=='Si')
                                <a href="{{URL::action('proveedoresController@eliminarProveedores',['idProveedor'=>$p->idProveedor])}}"
                                    type="submit" class="btn btn-xs btn-danger"><i
                                        class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i></a>

                                <a href="{{URL::action('proveedoresController@modificarProveedores',['idProveedor'=>$p->idProveedor])}}"
                                    class="btn btn-xs btn-info" style="width:67px;"><i
                                        class="ace-icon fa fa-pencil bigger-120"> Editar</i></a>
                                @else
                                <a href="{{URL::action('proveedoresController@restaurarProveedores',['idProveedor'=>$p->idProveedor])}}"
                                    class="btn btn-xs btn-warning" style="width:67px">
                                    Restaurar</a>
                                @endif
                            </td>
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
<style>
    thead input {
        width: 100%;
    }
</style>
<script> 
$(document).ready(function () {
    // Setup - add a text input to each footer cell
    $('#example thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#example thead');
 
    var table = $('#example').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
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
        initComplete: function () {
            var api = this.api();
 
            // For each column
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
    });
});
</script>
@stop