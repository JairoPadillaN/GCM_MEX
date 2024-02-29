@extends('principal')

@section('contenido')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha512.js"></script>
<script src='https://cdn.zinggrid.com/zinggrid.min.js' defer></script> -->

<style>
    li.paginate_button {
        padding: 0em !important;
    }
    thead input {
        width: 100%;
    }

    /* Ensure that the demo table scrolls */
    .noWrap { white-space: nowrap; }
    div.dataTables_wrapper {
        margin: 0 auto;
    }
 
    div.container {
        width: 80%;
    }
    /* #tablaProductosNota tr {
        text-align: center;
    } */
</style>

<div class="col-md-12">
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1 class="">Productos en Notas de entrada</h1>
        </div>
        <div class="panel-body">

            <!-- <div class="row">
                <div class="col-sm-12" style="margin-bottom:10px;text-align:right">
                    <a href="{{URL::action('ordenCompraController@altaOrdenCompra')}}">
                        <button type="button" class="btn btn-primary ">Agregar nueva orden</button>
                    </a>
                </div>
            </div> -->

            <table id="tablaReporteProductosEnOC" class="table table-striped table-bordered display" data-order='[[0, "desc"]]' style="width:100%;">
                <thead class="">
                    <tr style="background-color:#C5EBFB">
                        <th>Orden compra</th>
                        <th>Fecha</th>
                        <th>Nota entrada</th>
                        <th>Proveedor</th>
                        <th>Empresa</th>
                        <th>SKU</th>
                        <th>VPN</th>
                        <th>Cantidad</th>
                        <th>Fecha entrega</th>
                        <th>Número de guía</th>
                        <th>Estatus pago</th>
                        <th>Detalles de entrega</th>
                    </tr>
                </thead>
            </table>

            
        </div>
    </div>
</div>

<!-- ========== Modal detalles entrega ============================================================-->
    <div class="modal fade" id="modalDetallesEntrega">
        <div class="modal-dialog modal-lg" id="contenedorSizeModal">
            <div class="modal-content">
                
                <form action="/guardarDetallesEntrega" id="frmDetallesEntrega" method="post">
                    <!-- Cabecero del modal -->
                    <div class="modal-header" style="padding:10px">
                        <!-- modal header  -->
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                        <h4 class="modal-title" style="padding-top:0px"><strong>Detalles de entrega</strong></h4>
                    </div>


                    <!-- Cuerpo del modal -->
                    <div class="modal-body">
                        <!-- Campos ocultos -->
                        <div hidden>
                            <input type="text" name="idNotaEntradaDetallesEntrega" id="idNotaEntradaDetallesEntrega">
                        </div>

                        <div class="" id="contenedorVerDetallesEntrega" style="">
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <label for="verNumGuia" class="col-form-label">Número de guia:</label>
                                    <input type="text" class="form-control" id="verNumGuia" readonly>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="verCiudadOrigen" class="col-form-label">Ciudad origen:</label>
                                    <input type="text" class="form-control" id="verCiudadOrigen" readonly>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="verCiudadDestino" class="col-form-label">Ciudad destino:</label>
                                    <input type="text" class="form-control" id="verCiudadDestino" readonly>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="verPaqueteria" class="col-form-label">Paqueteria:</label>
                                    <input type="text" class="form-control" id="verPaqueteria" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <label for="verNumGuia2" class="col-form-label">Número de guia:</label>
                                    <input type="text" class="form-control" id="verNumGuia2" readonly>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="verCiudadOrigen2" class="col-form-label">Ciudad origen:</label>
                                    <input type="text" class="form-control" id="verCiudadOrigen2" readonly>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="verCiudadDestino2" class="col-form-label">Ciudad destino:</label>
                                    <input type="text" class="form-control" id="verCiudadDestino2" readonly>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="verPaqueteria2" class="col-form-label">Paqueteria:</label>
                                    <input type="text" class="form-control" id="verPaqueteria2" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="verFolioFacturaImportacion" class="col-form-label">Folio Factura de importación:</label>
                                    <input type="text" class="form-control" id="verFolioFacturaImportacion" readonly>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="verFolioGastosImportacion" class="col-form-label">Folio de gastos de importación:</label>
                                    <input type="text" class="form-control" id="verFolioGastosImportacion" readonly>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="verMetodoEntrega" class="col-form-label">Metodo de entrega:</label>
                                    <input type="text" class="form-control" id="verMetodoEntrega" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="verDetalleEntrega" class="col-form-label">Detalles de entrega: </label>
                                        <textarea id="verDetalleEntrega" rows="5" class="form-control rounded-0" readonly></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" id="contenedorImg"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                </form>
                    
            </div>
        </div>
    </div> 
<!-- /.modal -->

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    $(document).ready(function(){
        crearTabla()
    });

    $('#modalDetallesEntrega').on('hide.bs.modal', limpiarFrmDetallesEntrega);

    function crearTabla(){
        // Setup - add a text input to each footer cell
        $('#tablaReporteProductosEnOC thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#tablaReporteProductosEnOC thead');

        // Creando el cuerpo de la tabla con dataTable y ajax
        tabla = $("#tablaReporteProductosEnOC").dataTable({
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
                                            var column = this;
                    console.log(column)
                    // Apartado que quita el titulo a los filtros, quitar solo a ...
                    // if(colIdx == ){
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();
                        $(cell).html('');
                    // }
                    // APlicar FIltros SOlo a las Primeras 6 columnas
                    if(colIdx < 7 || colIdx == 8 || colIdx == 9) {
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

                    if(colIdx == 10){
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();

                        var select = $(`
                        <select style="width: 100%;height:26px">
                            <option value="p">Todos</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="Pago programado">Pago programado</option>
                            <option value="Pagado">Pagado</option>
                        </select>`)
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

                        $(cell).html(select);
                    }
                });
            },

            // Petición para llenar la tabla
            "ajax": {
                url: `/consultaProductosOC`,
                dataSrc: ''
            },
            "columns": [
                {data: 'ordenCompra', visible:true},
                {data: 'fechaOrden', visible:true},
                {data: 'numeroNota', visible:true},
                {data: 'proveedor', visible:true},
                {data: 'empresa', visible:true},
                {data: 'sku', visible:true},
                {data: 'vpn', visible:true},
                {data: 'cantidad', visible:true},
                {data: 'fechaEntrega', render: function ( data, type, row ) {
                        var contenido = "";
                        if(data != null){
                            contenido = data;
                        }else{
                            contenido = "Sin Fecha";
                        }
                        return contenido;
                    }
                },
                {data: 'numGuia', render: function ( data, type, row ) {
                        var contenido = `${row.paqueteria} ${data}`;

                        if(row.numGuia2 != null && row.numGuia2 != ""){
                            contenido += `<br>${row.paqueteria2} ${row.numGuia2}`;
                        }

                        return contenido;
                    }
                },
                {data: 'estatusPago', render: function ( data, type, row ) {
                        var contenido = "";

                        if(row.aplicaAnticipo == 1){
                            if(row.estatusPago_anticipo == 1){
                                contenido += "Anticipo: Pendiente<br>";
                            }else if (row.estatusPago_anticipo == 2) {
                                contenido += "Anticipo: Pago programado<br>";
                            }else{
                                contenido += "Anticipo: Pagado<br>";
                            }
                        }


                        if(data == 1){
                            contenido += "Total: Pendiente";
                        }else if (data == 2) {
                            contenido += "Total: Pago programado";
                        }else{
                            contenido += "Total: Pagado";
                        }

                        return contenido;
                    }
                },
                // Detalles de entrega
                {data: 'null', render: function ( data, type, row ) {
                        var contenido = "";

                        if (row.numGuia == null || row.numGuia == "") {
                            contenido = "Pendientes";
                        }else{
                            contenido = `<button type="button" style="width: 100%" class="btn btn-default btn-sm btnDetallesEntrega">Ver detalles</button><br>`;
                        }

                        return contenido;
                    }
                },

            ],
            // Indicamos el indice de la columna a ordenar y tipo de ordenamiento
            order: [[1, 'desc']],
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
                            columns: [0,1,2,3,4,5,6,7,8,9,10]
                        },
                        styles: {
                            tableHeader: {
                                alignment: 'center'
                            },
                        }
                    }
                ],
            columnDefs: [
                { className: 'dt-center', targets: [2,4,7,8,9,10,11] },
                // { className: 'dt-right', targets: [4,5] },
                { className: 'noWrap', targets: [0,1,2,4,5,6,7,8,9,10]}
            ],
            // Hacer el datatable responsive
            // rowReorder: {
            //     selector: 'td:nth-child(2)'
            // },
            // responsive: true,
            /* Para habilitar el scroll's, quitar las lineas de responsive */
            // scrollY: 200, //Scroll vertial
            scrollX: true, //Scroll horizonta
        });

        // Agregar detalles de entrega
        $('#tablaReporteProductosEnOC tbody').on('click', '.btnDetallesEntrega', function () {
            var dataRow = $("#tablaReporteProductosEnOC").DataTable().row($(this).parents('tr')).data();

            var metodoEntrega = "";
            if (dataRow.metodoEntrega == 1) {
                metodoEntrega = "Ocurre";
            }else if(dataRow.metodoEntrega == 2){
                metodoEntrega = "Entrega a domicilio";
            }else {
                metodoEntrega = "Entrega en oficina";
            }

            document.querySelector("#verMetodoEntrega").value = metodoEntrega;
            document.querySelector("#verDetalleEntrega").value = dataRow.detalleEntrega;
            document.querySelector("#verNumGuia").value = dataRow.numGuia;
            document.querySelector("#verCiudadOrigen").value = dataRow.ciudadOrigen;
            document.querySelector("#verCiudadDestino").value = dataRow.ciudadDestino;
            document.querySelector("#verPaqueteria").value = dataRow.paqueteria;
            document.querySelector("#verNumGuia2").value = dataRow.numGuia2;
            document.querySelector("#verCiudadOrigen2").value = dataRow.ciudadOrigen2;
            document.querySelector("#verCiudadDestino2").value = dataRow.ciudadDestino2;
            document.querySelector("#verPaqueteria2").value = dataRow.paqueteria2;
            document.querySelector("#verFolioFacturaImportacion").value = dataRow.folioFacturaImportacion;
            document.querySelector("#verFolioGastosImportacion").value = dataRow.folioGastosImportacion;
            img = [dataRow.archivoPaqueteria1,dataRow.archivoPaqueteria2,dataRow.archivoPaqueteria3,dataRow.archivoPaqueteria4];
            
            img.forEach(nombreImagen => {
                if(nombreImagen != "" && nombreImagen != null){
                    document.querySelector("#contenedorImg").innerHTML += `
                    <div class="col-sm-3">
                        <a target="_blank" href="{{asset('archivos/${nombreImagen}')}}">
                            <img src="{{asset('img/package.png')}}" height=80 width=80>
                            <h6>Descargar evidencia</h6>
                        </a>
                    </div>`;
                }
            });

            $('#modalDetallesEntrega').modal('show');

        });
    }

    function limpiarFrmDetallesEntrega(){
        $('#frmDetallesEntrega')[0].reset();
        $("#contenedorImg").html("");
    }
</script>
@stop