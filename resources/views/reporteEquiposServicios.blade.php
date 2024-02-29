@extends('principal')
@section('contenido')

<div class="col-md-12">
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1 class="">Reporte de Equipos en Servicios</h1>
        </div><br>
        <div class="panel-body">
            <table id="TbReporteEquiposServicios" class="table table-striped table-bordered display">
                <thead class="bg-gray-dark color-palette">
                    <tr style="background-color:#C5EBFB;">
                        <th>Servicio</th>
                        <th style="text-align: center; !important;">Factura</th>
                        <th style="text-align: center; !important;">Cotización</th>
                        <th style="text-align: center; !important;">Fecha Pago</th>
                        <th style="text-align: center; !important;">Razón social</th>
                        <th style="text-align: center; !important;">Sucursal</th>
                        <th style="text-align: center; !important;">GCMID</th>
                        <th style="text-align: center; !important;">Descripción</th>
                        <th style="text-align: center; !important;">TIpo</th>
                        <th style="text-align: center; !important;">Subtipo</th>
                        <th style="text-align: center; !important;">Serie</th>
                        <th style="text-align: center; !important;">Marca</th>
                        <th style="text-align: center; !important;">Modelo</th>
                        <th style="text-align: center; !important;">Ubicación</th>
                        <th style="text-align: center; !important;">Notas</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<style>
    li.paginate_button {
        padding: 0em !important;
    }
    thead input {
        width: 100%;
    }

    /* Ensure that the demo table scrolls */
    th { white-space: nowrap; }
    div.dataTables_wrapper {
        margin: 0 auto;
    }
 
    div.container {
        width: 100%;
    }
</style>

<script>
    window.addEventListener('DOMContentLoaded', (event) => {

        // Setup - add a text input to each footer cell
        $('#TbReporteEquiposServicios thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#TbReporteEquiposServicios thead');

        // Creando el cuerpo de la tabla con dataTable y ajax
        var  tabla = $("#TbReporteEquiposServicios").dataTable({
            // Apartado para filtro por columna
            orderCellsTop: true,
            fixedHeader: true,
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
                    $(cell).html('<input type="text" placeholder="' + title + '" class=""/>');
 
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

            // Petición para llenar la tabla
            "ajax": {
                url: 'dataReporteEquiposServicios',
                dataSrc: ''
            },
            "columns": [
                {data: 'idservicios', render: function(data, type, row) {
                    return `<a href="modificarFacturas/${data}" target="_blank">${data}</a>`;
                }},
                {data: 'factura', render: function(data, type, row) {
                    return `<a href="modificarFacturas/${row.idservicios}" target="_blank">${data}</a>`;
                }},
                {data: 'numerocotizacion', render: function(data, type, row) {
                    return `<a href="pdfCotizacion?idCotizacion=${row.idcotizacion}&verTotal=Si&pld=1&cD=Si" target="_blank">${data}</a>`;
                }},
                {data: 'fechapago',visible: true},
                {data: 'razonsocial', visible: true},
                {data: 'sucursal', visible: true},
                {data: 'gcmid', visible: true},
                {data: 'descripcion', visible: true},
                {data: 'tipoequipo', visible: true},
                {data: 'subtipoequipo', visible: true},
                {data: 'serie', visible: true},
                {data: 'marca', visible: true},
                {data: 'modelo', visible: true},
                {data: 'ubicacionequipo', visible: true}, 
                {data: 'notas', visible: true}
            ],

            columnDefs: [
                {targets: 14,
                width: "500px",
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                }},
                {targets: 7,
                width: "200px",
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                }},
                {targets: 4,
                width: "300px",
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                }},
                {targets: 5,
                width: "300px",
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'justify'); // centrar el contenido de la celda
                }},
                {targets: 2,
                width: "100px",
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'center'); // centrar el contenido de la celda
                }},
                {targets: 3,
                width: "50px",
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'center'); // centrar el contenido de la celda
                }},
                {targets: 0,
                width: "50px",
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'center'); // centrar el contenido de la celda
                }},
                {targets: 1,
                width: "50px",
                createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
                $(td).css('text-align', 'center'); // centrar el contenido de la celda
                }},
            ],
            // Indicamos el indice de la columna a ordenar y tipo de ordenamiento
            order: [[0, 'desc']],
            // Habilitar o deshabilitar el ordenable en las columnas
            // 'columnDefs': [ {
            //     'targets': [7], /* table column index */
            //     'orderable': false, /* true or false */
            // }],
            // Cambiamos a espeañol el idioma de los mensajes
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
                    next: '>',
                    previous: '<',
                    first:'Inicio',
                    last:'Ultimo'
                },
            },
            // Mostrar los botones de paginación Inicio y Ultimo
            pagingType: 'full_numbers',
            // Registros por pagína
            lengthMenu: [6,12,24,50,100],
            // Botones para exportar información
            dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>rt<"row"<"col-sm-3"l><"col-sm-3"i><"col-sm-6"p>>',
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Excel',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13]
                        },
                        styles: {
                            tableHeader: {
                                alignment: 'center'
                            },
                        }
                    }
                ],
            // Hacer el datatable responsive
            // rowReorder: {
            //     selector: 'td:nth-child(2)'
            // },
            // responsive: true,
            /* Para habilitar el scroll's, quitar las lineas de responsive */
            // scrollY: 200, //Scroll vertial
            scrollX: true, //Scroll horizonta
        })

    });
</script>

@stop