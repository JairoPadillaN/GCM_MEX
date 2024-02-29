@extends('principal')
@section('contenido')

<div class="col-md-12">
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1 class="">Reporte por empresa</h1>
        </div><br>
        <div class="panel-body">
            <div class="" style="display: flex; justify-content: flex-end">
                <a href="{{asset('altaEquipos')}}">
                    <button type="button" class="btn btn-primary ">Agregar nuevo equipo</button>
                </a>
            </div>
            <br>
            <table id="TbReportePorEmpresa" class="table table-striped table-bordered display" style="width:100%;">
                <thead class="bg-gray-dark color-palette">
                    <tr style="background-color:#C5EBFB">
                        <th>idEquipos</th>
                        <!-- <th>Foto principal</th>
                        <th>Foto vista-frontal</th>
                        <th>Foto placa 1</th> -->
                        <th>Folio de recepción</th>
                        <th>GCM ID</th>
                        <th>Fecha de entrada</th>
                        <th>Cliente - Sucursal</th>
                        <th>Quien entrego</th>
                        <th>Equipo</th>
                        <th>Marca - Modelo</th>
                        <th>Importancia</th>
                        <!-- Campo oculto solo para reporte excel -->
                        <th>Importancia</th>
                        <th>Estatus Reparación</th>
                        <th>Estatus Entrega</th>
                        <th>Piezas divididas</th>
                        <th>Opciones</th>
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
        width: 80%;
    }
</style>

<script>
    window.addEventListener('DOMContentLoaded', (event) => {

        // Setup - add a text input to each footer cell
        $('#TbReportePorEmpresa thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#TbReportePorEmpresa thead');

        // Creando el cuerpo de la tabla con dataTable y ajax
        var  tabla = $("#TbReportePorEmpresa").dataTable({
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
                    if(colIdx == 13){
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();
                        $(cell).html('');
                    }
                    if(colIdx != 13 && colIdx != 0 && colIdx != 9) {
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
                    }
                });
            },

            // Petición para llenar la tabla
            "ajax": {
                url: 'dataReportePorEmpresa',
                dataSrc: ''
            },
            "columns": [
                {data: 'idEquipos',visible:false},
                 {data: 'vistaSuperior', render: function(data, type, row) {
                     return `<img src="{!! asset('archivos/${data}')!!}" height="130px" width="130px" target="_blank" onerror="this.remove()">`;
                 }},
                 {data: 'vistaFrente', render: function(data, type, row) {
                     return `<img src="{!! asset('archivos/${data}')!!}" height="130px" width="130px" target="_blank" onerror="this.remove()">`;
                }},
                {data: 'placa_1', render: function(data, type, row) {
                    return `<img src="{!! asset('archivos/${data}')!!}" height="130px" width="130px" target="_blank" onerror="this.remove()">`;
                }},
                {data: 'folioRecepcion'},
                {data: 'gcmid'},
                {data: 'fecha'},
                {data: 'cliSuc'},
                {data: 'personaEntrega'},
                {data: 'equipo'},
                {data: 'marcaModelo'},
                {data: 'importancia', render: function(data, type, row) {
                    var importancia = "";
                    if (data == 'Alta') {
                        importancia = `<div style="background-color: #D52F24;width: 70px;height: 70px;border-radius: 50%;"></div>`;
                    }else if(data == 'Media') {
                        importancia = `<div style="background-color: #E7DF35;width: 70px;height: 70px;border-radius: 50%;"></div>`;
                    }else{
                        importancia = `<div style="background-color: #31C125;width: 70px;height: 70px;border-radius: 50%;"></div>`;
                    }
                    return importancia;
                }},
                {data: 'importancia', visible:false},
                {data: 'estatus'},
                {data: 'estatusEntrega'},
                {data: 'canti'},
                {data: 'activo', render: function(data, type, row) {
                    var btn = `<a href="restaurarEquipos/${row.idEquipos}" type='submit' class='btn btn-xs btn-success'>Restaurar</a>`;
                    if (data == "Si" ||  data == "si"){
                        btn = `<a href="detallePorEmpresa/${row.idEquipos}" type='submit' class='btn btn-xs btn-danger'>Detalle del equipo</i></a>`;
                    }

                    return btn;
                }},
            ],
            // Indicamos el indice de la columna a ordenar y tipo de ordenamiento
            order: [[0, 'desc']],
            // Habilitar o deshabilitar el ordenable en las columnas
            'columnDefs': [ {
            //     'targets': [7], /* table column index */
            //     'orderable': false, /* true or false */
            }],
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
            lengthMenu: [5,10,25,50,100],
            // Botones para exportar información
            dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>rt<"row"<"col-sm-3"l><"col-sm-3"i><"col-sm-6"p>>',
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Excel',
                        exportOptions: {
                            columns: [1,2,3,4,5,6,7,9,10,11,12]
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