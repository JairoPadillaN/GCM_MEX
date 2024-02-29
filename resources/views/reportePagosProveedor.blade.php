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
            <h1 class="">Reporte de pagos a proveedores</h1>
        </div>
        <div class="panel-body">

            <!-- <div class="row">
                <div class="col-sm-12" style="margin-bottom:10px;text-align:right">
                    <a href="{{URL::action('ordenCompraController@altaOrdenCompra')}}">
                        <button type="button" class="btn btn-primary ">Agregar nueva orden</button>
                    </a>
                </div>
            </div> -->

            <table id="tablaReportePagosProveedor" class="table table-striped table-bordered display" style="width:100%;">
                <thead class="">
                    <tr style="background-color:#C5EBFB">
                        <th>Orden de compra</th>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th>Empresa</th>
                        <th>Total Nota entrada</th>
                        <th>Total Complemento<br>Nota de entrada</th>
                        <th>Nota de entrada</th>
                        <th>Número de guía</th>
                        <th>Estatus pago</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
            
        </div>
    </div>
</div>

<!-- ========== Modal productos en nota de entrada ============================================================-->
    <div class="modal fade" id="modalNuevaNota">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Cabecero del modal -->

                <div class="modal-header" style="padding:10px">
                    <!-- modal header  -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnCerrarHeadMdl" style="display:;">&times;</button>
                    <h4 class="modal-title" style="padding-top:0px"><strong>Detalles Nota Entrada</strong></h4>
                </div>

                <!-- Cuerpo del modal -->
                <div class="modal-body">
                    <div class="">
                        <div class="row" id="contenedorDetallesNotaEntrada" style="display:;"></div>

                        <div class="table-responsive" style="max-height:250px;">
                            <table class="table table-hover table-striped table-bordered" id="tablaProductosNota">
                                <thead style="position: sticky;top: 0;background-color:#C5EBFB">
                                    <tr>
                                        <th class="hidden">idProducto</th>
                                        @if($tipoUsuario == "Administrador" || $tipoUsuario == "Compras")
                                            <th style="padding: 5px 25px !important;">Servicio</th>
                                        @endif
                                        <th style="padding: 5px 40px !important;">SKU</th>
                                        <th style="padding: 5px 25px !important;">VPN</th>
                                        <th style="padding: 5px 25px !important;">Piezas</th>
                                        <th style="padding: 5px 25px !important;">Subtotal</th>
                                        <th style="padding: 5px 25px !important;">IVA</th>
                                        <th style="padding: 5px 25px !important;">Total</th>
                                        <th id="columnaOpciones"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @if($tipoUsuario == "Administrador" || $tipoUsuario == "Compras")
                                            <td colspan="9" style="text-align:center">Sin registros</td>
                                        @else
                                            <td colspan="8" style="text-align:center">Sin registros</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        @if($tipoUsuario == "Administrador" || $tipoUsuario == "Compras")
                                            <td colspan="4" style="text-align:right"></td>
                                        @else
                                            <td colspan="3" style="text-align:right"></td>
                                        @endif
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> 
<!-- /.modal -->

<!-- ========== Modal tabala detalle nota entrada ============================================================-->
    <div class="modal fade" id="modalTablaDetalleNota">
        <div class="modal-dialog" id="" style="width: 95%;">
            <div class="modal-content">
                <div class="modal-body">
                    <table id="TbreporteNotasEntrada" class="table table-striped table-bordered display" style="width:100%;">
                        <thead class="">
                            <tr style="background-color:#C5EBFB">
                                <th style="text-align: center !important">idNotaEntrada</th>
                                <th style="text-align: center !important">idOrden</th>
                                <th style="text-align: center !important">Nota De Entrada</th>
                                <th style="text-align: center !important">Fecha Entrega</th>
                                <th style="text-align: center !important">Detalles Entrega</th>
                                <th style="text-align: center !important">Subir Factura</th>
                                <th style="text-align: center !important">Monto Nota</th>
                                <th style="text-align: center !important">Fecha Pago Programado</th>
                                <th style="text-align: center !important">Fecha Real Pago</th>
                                <th style="text-align: center !important">Evidencia pago</th>
                                <th style="text-align: center !important">Monto Complemento</th>
                                <th style="text-align: center !important">Complementos de pago</th>
                            </tr>
                        </thead>
                    </table>   
                </div>
            </div>
        </div>
    </div> 
<!-- /.modal -->

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
    
    var tabla;
    sumaSubtotal = 0;
    sumaIva = 0;
    sumaTotal = 0;
    var tipoUsuario = '<?= $tipoUsuario?>';
    var tablaNotasEntrada;

    window.addEventListener('DOMContentLoaded', (event) => {
        const contenidoTablaDetalle = document.querySelector('#tablaProductosNota tbody');
        $('#modalDetallesEntrega').on('hide.bs.modal', limpiarFrmDetallesEntrega);
        $('#modalArchivos').on('hide.bs.modal', limpiarMdlArchivos);
        
        crearTabla()

        function crearTabla(){
            // Setup - add a text input to each footer cell
            $('#tablaReportePagosProveedor thead tr')
            .clone(true)
            .addClass('filters')
            .appendTo('#tablaReportePagosProveedor thead');

            // Creando el cuerpo de la tabla con dataTable y ajax
            tabla = $("#tablaReportePagosProveedor").dataTable({
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
                        if(colIdx < 4 || colIdx == 6 || colIdx ==7) {
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

                        // if(colIdx == 7 || colIdx == 8){
                        if(colIdx == 8){
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
                    url: `/consultaReportePagosProveedores`,
                    dataSrc: ''
                },
                "columns": [
                    {data: 'ordenCompra', visible: true},
                    {data: 'fechaOrden', visible: true},
                    {data: 'proveedor', visible: true},
                    {data: 'empresa', visible:true},
                    {data: 'montoNotaTotal', render: function ( data, type, row ) {
                            // return `${row.monedaNota} $${parseFloat(data).toFixed(2)}`
                            var montos = "";
                            var tituloMsjTabla = "Total";

                            if(row.monedaNota == "USD"){
                                montos += `Cambio Dolar: MXN ${formatoNumerico(row.cambioDolar.toFixed(2))}<br>`;
                            }

                            if(row.aplicaAnticipo == 1){
                                tituloMsjTabla = "Restante";
                                montos +=  `Anticipo: ${row.monedaNota} ${formatoNumerico(row.cantidadAnticipo.toFixed(2))}<br>`;
                                
                                if(row.monedaNota == "USD"){
                                    montos +=  `Anticipo en MXN ${formatoNumerico(row.anticipoMXN.toFixed(2))}<br>`;
                                }
                            }
                            
                            montos += `${tituloMsjTabla}: ${row.monedaNota} ${formatoNumerico(row.montoNotaRestante.toFixed(2))}`;
                            
                            if(row.monedaNota == "USD"){
                                montos +=  `<br>${tituloMsjTabla} en MXN ${formatoNumerico(row.totalRestanteMXN.toFixed(2))}`;
                            }

                            if(row.aplicaAnticipo == 1){
                                montos +=  `<br>Total en MXN ${formatoNumerico((row.anticipoMXN + row.totalRestanteMXN).toFixed(2))}`;
                            }
                            
                            return montos;
                        }
                    },
                    {data: null, render: function ( data, type, row ) {
                            // return `MXN $${parseFloat(data).toFixed(2)}`
                            var contenido = "";
                            var tituloMsjTabla = "Total";
                            
                            // Si aplica anticipo mostramos btn para subir fecha o mostrar la fecha ya guardada
                            if(row.aplicaAnticipo == 1){
                                tituloMsjTabla = "Restante";

                                if(row.montoComplemento_anticipo < 1){
                                    contenido += `-`;
                                }else{
                                    if(row.monedaComplemento_anticipo == "USD"){
                                        contenido += `Cambio Dolar Anticipo: MXN ${formatoNumerico(row.cambioDolar_anticipoComplemento.toFixed(2))}<br>`;
                                    }
                                    
                                    contenido += `Anticipo: ${row.monedaComplemento_anticipo} ${formatoNumerico(row.montoComplemento_anticipo.toFixed(2))}<br>`;
                                    
                                    if(row.monedaComplemento_anticipo == "USD"){
                                        contenido +=  `Anticipo en MXN ${formatoNumerico(row.anticipoComplementoMXN.toFixed(2))}<br>`;
                                    }
                                }
                            }


                            if(row.montoComplemento_total < 1){
                                contenido += `-`;
                            }else{
                                if(row.monedaComplemento_total == "USD"){
                                    contenido += `Cambio Dolar ${tituloMsjTabla}: MXN ${formatoNumerico(row.cambioDolar_totalComplemento.toFixed(2))}<br>`;
                                }

                                contenido += `${tituloMsjTabla}: ${row.monedaComplemento_total} ${formatoNumerico(row.montoComplemento_total.toFixed(2))}`;
                            
                                if(row.monedaComplemento_total == "USD"){
                                    contenido +=  `<br>${tituloMsjTabla} en MXN ${formatoNumerico(row.totalRestanteComplementoMXN.toFixed(2))}`;
                                }

                                if(row.aplicaAnticipo == 1){
                                    contenido +=  `<br>Total en MXN ${formatoNumerico((row.anticipoComplementoMXN + row.totalRestanteComplementoMXN).toFixed(2))}`;
                                }
                            }

                            return contenido;
                        }
                    },
                    {data: 'numeroNota', visible:true},
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
                    // {data: null, render: function ( data, type, row ) {
                    //         return `<button type="button" style="width: 100%" class="btn btn-default btn-sm btnVerArchivos">Ver archivos</button><br>`;
                    //     }
                    // },
                    // OPCIONES
                    { data: null, render: function ( data, type, row ) {
                        return `<button class="btn btn-info btn-sm detalleNotaEntrada" value="${row.idNotaEntrada}"><span class="glyphicon glyphicon-eye-open"></span></button>
                                <button class="btn btn-warning btn-sm tablaDetallesNota" value="${row.idNotaEntrada}"><i class="fa fa-file-text-o"></i></button>`;
                        }
                    },
                ],
                // Indicamos el indice de la columna a ordenar y tipo de ordenamiento
                order: [[0, 'desc']],
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
                            columns: [0,1,2,3,4,5,6,7,8]
                        },
                        styles: {
                            tableHeader: {
                                alignment: 'center'
                            },
                        }
                    }
                ],
                columnDefs: [
                    { className: 'dt-center', targets: [1,2,3,6,7,8,9] },
                    { className: 'dt-right', targets: [4,5] },
                    { className: 'noWrap', targets: [0,1,,3,4,5,6,7,8,9]}
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

            // Muestra el contenido de la nota de entrda
            $('#tablaReportePagosProveedor tbody').on('click', '.detalleNotaEntrada', function () {
                var dataRow = $("#tablaReportePagosProveedor").DataTable().row($(this).parents('tr')).data();
                console.log(dataRow)
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: `/consultaProdcutosPorNotaEntrada/${$(this)[0].value}`,
                    type: "GET",
                    dataType: 'json',
                    success: function (res) {
                        var datosTabla = res;
                        crearTablaProductosEnNota(res);

                        document.querySelector("#contenedorDetallesNotaEntrada").innerHTML = `
                            <div class="form-group col-sm-3">
                                <label for="numeroNota" class="col-form-label">Nota de entrada:</label>
                                <input type="text" class="form-control" readonly="" value="${dataRow.numeroNota}">
                            </div>
                            <div class="col-sm-3">
                                <label class="" for="aplicaAnticipo">Anticipo:</label>
                                <div class="input-group">
                                    <input type="text" min="1" class="form-control" readonly="true" value="${dataRow.porcentajeAnticipo}">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label class="input-group" for="">Total anticipo:</label>
                                <div class="input-group">
                                    <span class="input-group-addon">${dataRow.monedaNota}</span>
                                    <input type="text" min="1" class="form-control" readonly value="${formatoNumerico(dataRow.cantidadAnticipo)}">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label class="input-group" for="">Total Nota De Entrada:</label>
                                <div class="input-group">
                                    <span class="input-group-addon">${dataRow.monedaNota}</span>
                                    <input type="text" min="1" class="form-control" readonly value="${formatoNumerico(dataRow.montoNotaTotal)}">
                                </div>
                            </div>
                        `;

                        $('#modalNuevaNota').modal('show');
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });

            });

            // Ver archivos
            $('#tablaReportePagosProveedor tbody').on('click', '.btnVerArchivos', function () {
                var dataRow = $("#tablaReportePagosProveedor").DataTable().row($(this).parents('tr')).data();

                var contenido = "";
                            if(dataRow.pdfFactura_anticipo != null){
                                contenido += `
                                    <div><strong>Facturas</strong></div>
                                    <a target="_blank" href="{{asset('archivos/${dataRow.pdfFactura_anticipo}')}}">
                                        <img src="{{asset('img/iconpdfdown.png')}}" height=50 width=50>
                                    </a>
                                `;
                            }
                            if(dataRow.pdfFactura_total_restante != null){
                                contenido += `
                                    <a target="_blank" href="{{asset('archivos/${dataRow.pdfFactura_total_restante}')}}">
                                        <img src="{{asset('img/iconpdfdown.png')}}" height=50 width=50>
                                    </a>
                                `;
                            }
                            if(dataRow.pdfComplemento_anticipo != null){
                                contenido += `
                                    <div><strong>Complementos</strong></div>
                                    <a target="_blank" href="{{asset('archivos/${dataRow.pdfComplemento_anticipo}')}}">
                                        <img src="{{asset('img/iconpdfdown.png')}}" height=50 width=50>
                                    </a>
                                `;
                            }
                            if(dataRow.pdfComplemento_total_restante != null){
                                contenido += `
                                    <a target="_blank" href="{{asset('archivos/${dataRow.pdfComplemento_total_restante}')}}">
                                        <img src="{{asset('img/iconpdfdown.png')}}" height=50 width=50>
                                    </a>
                                `;
                            }
                            if(dataRow.evidenciaPago_anticipo != null){
                                contenido += `
                                    <div><strong>Evidencias</strong></div>
                                    <a target="_blank" href="{{asset('archivos/${dataRow.evidenciaPago_anticipo}')}}">
                                        <img src="{{asset('img/transferenciadown.png')}}" height=50 width=50>
                                    </a>
                                `;
                            }
                            if(dataRow.evidenciaPago_total_restante != null){
                                contenido += `
                                    <a target="_blank" href="{{asset('archivos/${dataRow.evidenciaPago_total_restante}')}}">
                                        <img src="{{asset('img/transferenciadown.png')}}" height=50 width=50>
                                    </a>
                                `;
                            }

                document.querySelector("#contenedorArchivos").innerHTML += contenido;

                $('#modalArchivos').modal('show');

            });

            // Agregar detalles de entrega
            $('#tablaReportePagosProveedor tbody').on('click', '.tablaDetallesNota', function () {
                var dataRow = $("#tablaReportePagosProveedor").DataTable().row($(this).parents('tr')).data();
                crearTablaDetalleNota(dataRow.idNotaEntrada)
                $('#modalTablaDetalleNota').modal('show');
            });
        }

        function crearTablaProductosEnNota(data){
            console.log(data)
            var monedaEnTotales = "";
            contenidoTablaDetalle.innerHTML = "";
            
            data.forEach(producto => {
                monedaEnTotales = producto.monedaNota;
                sumaSubtotal = sumaSubtotal + producto.subtotal;
                sumaIva = sumaIva + producto.iva;
                sumaTotal = sumaTotal + producto.total;

                const trDatos = document.createElement("tr");
                
                // Creamos el <td> idProducto
                let td_idProducto = document.createElement("td");
                td_idProducto.className = "hidden";
                td_idProducto.textContent = producto.idProducto;
                trDatos.appendChild(td_idProducto);

                // Creamos el <td> servicio
                let td_servicio = document.createElement("td");
                td_servicio.setAttribute("align", "center");
                td_servicio.textContent = producto.servicio;
                trDatos.appendChild(td_servicio);

                // Creamos el <td> sku
                let td_Sku = document.createElement("td");
                td_Sku.textContent = producto.sku;
                trDatos.appendChild(td_Sku);

                // Creamos el <td> vpn
                let td_vpn = document.createElement("td");
                td_vpn.textContent = producto.vpn;
                trDatos.appendChild(td_vpn);

                // Creamos el <td> piezasRecibidas
                let td_piezasRecibidas = document.createElement("td");
                td_piezasRecibidas.setAttribute("align", "center");
                td_piezasRecibidas.textContent = producto.piezasRecibidas;
                trDatos.appendChild(td_piezasRecibidas);

                // Creamos el <td> subtotal
                let td_subtotal = document.createElement("td");
                td_subtotal.setAttribute("align", "right");
                td_subtotal.style.cssText = 'white-space: nowrap;';
                td_subtotal.textContent = `${producto.monedaNota} ${formatoNumerico(producto.subtotal.toFixed(2))}`;
                trDatos.appendChild(td_subtotal);

                // Creamos el <td> iva
                let td_iva = document.createElement("td");
                td_iva.setAttribute("align", "right");
                td_iva.style.cssText = 'white-space: nowrap;';
                td_iva.textContent = `${producto.monedaNota} ${formatoNumerico(producto.iva.toFixed(2))}`;
                trDatos.appendChild(td_iva);

                // Creamos el <td> total
                let td_total = document.createElement("td");
                td_total.setAttribute("align", "right");
                td_total.style.cssText = 'white-space: nowrap;';
                td_total.textContent = `${producto.monedaNota} ${formatoNumerico(producto.total.toFixed(2))}`;
                trDatos.appendChild(td_total);

                // Totalmente agregamos el <tr> al cuerpo de la tabla
                contenidoTablaDetalle.appendChild(trDatos);
            })

            const trTotal = document.createElement("tr");
            
            // Creamos el <td> del total
            let td_total = document.createElement("td");
            if(tipoUsuario == "Administrador" || tipoUsuario == "Compras"){
                td_total.setAttribute("colspan", "4");            
            }else{
                td_total.setAttribute("colspan", "3");
            }
            td_total.setAttribute("align", "right");
            td_total.textContent = "Totales:";
            trTotal.appendChild(td_total);

            // Creamos el <td> del Subtotal
            let td_valorSubtotal = document.createElement("td");           
            td_valorSubtotal.setAttribute("align", "right");
            td_valorSubtotal.style.cssText = 'white-space: nowrap;';
            td_valorSubtotal.textContent = `${monedaEnTotales} ${formatoNumerico(sumaSubtotal.toFixed(2))}`;
            trTotal.appendChild(td_valorSubtotal);

            // Creamos el <td> del Iva
            let td_valorIva = document.createElement("td");
            td_valorIva.setAttribute("align", "right");
            td_valorIva.style.cssText = 'white-space: nowrap;';
            td_valorIva.textContent = `${monedaEnTotales} ${formatoNumerico(sumaIva.toFixed(2))}`;
            trTotal.appendChild(td_valorIva);
            
            // Creamos el <td> del total
            let td_valorTotal = document.createElement("td");         
            td_valorTotal.setAttribute("align", "right");
            td_valorTotal.style.cssText = 'white-space: nowrap;';
            td_valorTotal.textContent = `${monedaEnTotales} ${formatoNumerico(sumaTotal.toFixed(2))}`;
            trTotal.appendChild(td_valorTotal);
            contenidoTablaDetalle.appendChild(trTotal);
        }

        function crearTablaDetalleNota(idNotaENtrada){
            
            if(tablaNotasEntrada !== undefined){
                $('#TbreporteNotasEntrada').DataTable().clear().destroy();
            }
            
    
            // Creando el cuerpo de la tabla con dataTable y ajax
            tablaNotasEntrada = $("#TbreporteNotasEntrada").dataTable({
    
                // Petición para llenar la tabla
                "ajax": {
                    url: `/consultaDetallesNotaEntrada/${idNotaENtrada}`,
                    dataSrc: ''
                },
                "columns": [
                    {data: 'idNotaEntrada', visible: false},
                    {data: 'idOrden', visible: false},
                    {data: 'numeroNota', visible: true},
                    {data: 'fechaEntrega', render: function(data, type, row) {
                        var contenido = "";
                            if(data == null){
                                contenido +=  `Sin Fecha`;
                            }else{
                                contenido +=  data;
                            }

                            return contenido
                        }
                    },
                    {data: 'detallesEntrega', render: function(data, type, row) {
                        var contenido = "";
                            if (row.numGuia == null) {
                                contenido = `Sin detalles`;
                            } else {
                                contenido = `<button type="button" style="width: 100%" class="btn btn-default btn-sm btnDetallesEntrega">Ver detalles</button>`;
                            }

                            return contenido;
                        }
                    },
                    // COLUMNA SUBIR FACTURAS
                    {data: null, render: function(data, type, row) {
                            var contenido = "";
                            var tituloMsjTabla = "Total";

                            if(row.formato != "CMG USA"){
                                // Si aplica anticipo y no hay archviso Mostramos mensaje o mostrar los archivos si ya se subieron
                                if (row.aplicaAnticipo == 1){
                                    tituloMsjTabla = "Restante";
                                    if (row.pdfFactura_anticipo == null || row.xmlFactura_anticipo == null) {
                                        contenido += `Anticipo: Sin Archivo<br>`;
                                    }else{
                                        contenido += `<div class="">
                                                        <a target="_blank" href="{{asset('archivos/${row.pdfFactura_anticipo}')}}">
                                                            <img src="{{asset('img/iconpdfdown.png')}}" height=50 width=50>
                                                        </a>
                                                        <a target="_blank" href="{{asset('archivos/${row.xmlFactura_anticipo}')}}">
                                                            <img src="{{asset('img/iconxmldown.png')}}" height=50 width=50>
                                                        </a>
                                                    </div>`;
                                    }
                                }

                                // Si aplicaba anticipo y  ya se subieron todos los archivos de anticipo o no aplicaba antcipo se visualiza
                                // mensaje sin archivso o para ver los archivos subidos
                                if(row.procesoAnticiposFinalizado == 1 || row.aplicaAnticipo == 0){
                                    if (row.pdfFactura_total_restante == null || row.xmlFactura_total_restante == null) {
                                        contenido += `<br>${tituloMsjTabla}: Sin Archivo`;
                                    }else{
                                        contenido += `<div class="">
                                                        <a target="_blank" href="{{asset('archivos/${row.pdfFactura_total_restante}')}}">
                                                            <img src="{{asset('img/iconpdfdown.png')}}" height=50 width=50>
                                                        </a>
                                                        <a target="_blank" href="{{asset('archivos/${row.xmlFactura_total_restante}')}}">
                                                            <img src="{{asset('img/iconxmldown.png')}}" height=50 width=50>
                                                        </a>
                                                    </div>`;
                                    }
                                }
                            }else{
                                // Si aplica anticipo y no hay archviso Mostramos mensaje o mostrar los archivos si ya se subieron
                                if (row.aplicaAnticipo == 1){
                                    tituloMsjTabla = "Restante";
                                    if (row.pdfFactura_anticipo == null) {
                                        contenido += `Anticipo: Sin Archivo<br>`;
                                    }else{
                                        contenido += `<div class="">
                                                        <a target="_blank" href="{{asset('archivos/${row.pdfFactura_anticipo}')}}">
                                                            <img src="{{asset('img/iconpdfdown.png')}}" height=50 width=50>
                                                        </a>
                                                    </div>`;
                                    }
                                }

                                // Si aplicaba anticipo y  ya se subieron todos los archivos de anticipo o no aplicaba antcipo se visualiza
                                // mensaje sin archivso o para ver los archivos subidos
                                if(row.procesoAnticiposFinalizado == 1 || row.aplicaAnticipo == 0){
                                    if (row.pdfFactura_total_restante == null) {
                                        contenido += `<br>${tituloMsjTabla}: Sin Archivo`;
                                    }else{
                                        contenido += `<div class="">
                                                        <a target="_blank" href="{{asset('archivos/${row.pdfFactura_total_restante}')}}">
                                                            <img src="{{asset('img/iconpdfdown.png')}}" height=50 width=50>
                                                        </a>
                                                    </div>`;
                                    }
                                }
                            }

                            return contenido;

                        }
                    },
                    {data: 'montoNotaTotal', render: function(data, type, row) {
                            var montos = "";
                            var tituloMsjTabla = "Total";
    
                            if(row.monedaNota == "USD"){
                                montos += `Cambio Dolar: MXN ${formatoNumerico(row.cambioDolar.toFixed(2))}<br>`;
                            }
    
                            if(row.aplicaAnticipo == 1){
                                tituloMsjTabla = "Restante";
                                montos +=  `Anticipo: ${row.monedaNota} ${formatoNumerico(row.cantidadAnticipo.toFixed(2))}<br>`;
                                
                                if(row.monedaNota == "USD"){
                                    montos +=  `Anticipo en MXN ${formatoNumerico(row.anticipoMXN.toFixed(2))}<br>`;
                                }
                            }
                            
                            montos += `${tituloMsjTabla}: ${row.monedaNota} ${formatoNumerico(row.montoNotaRestante.toFixed(2))}`;
                            
                            if(row.monedaNota == "USD"){
                                montos +=  `<br>${tituloMsjTabla} en MXN ${formatoNumerico(row.totalRestanteMXN.toFixed(2))}`;
                            }
    
                            if(row.aplicaAnticipo == 1){
                                montos +=  `<br>Total en MXN ${formatoNumerico((row.anticipoMXN + row.totalRestanteMXN).toFixed(2))}`;
                            }
                            
                            return montos;
                        }
                    },
                    // COLUMNA FECHA PAGO PROGRAMADO
                    {data: null,  render: function(data, type, row) {
                            var contenido = "";
                            var tituloMsjTabla = "Total";
    
                            // Si aplica anticipo mostramos btn para subir fecha o mostrar la fecha ya guardada
                            if(row.aplicaAnticipo == 1){
                                tituloMsjTabla = "Restante";
                                
                                if(row.fechaPagoProgramada_anticipo == null){
                                    contenido += `Anticipo: Sin Fecha<br>`;
                                }else{
                                    contenido += `Anticipo: ${row.fechaPagoProgramada_anticipo}<br>`;
                                }
                            }
    
                            if(row.fechaPagoProgramada_total == null){
                                contenido += `${tituloMsjTabla}: Sin Fecha`;
                            }else{
                                contenido += `${tituloMsjTabla}: ${row.fechaPagoProgramada_total}`;
                            }
    
                            return contenido;
                        }
                    },
                    // COLUMNA FECHA REAL PAGO
                    {data: null,  render: function(data, type, row) {
                            var contenido = "";
                            var tituloMsjTabla = "Total";
    
                            // Si aplica anticipo mostramos btn para subir fecha o mostrar la fecha ya guardada
                            if(row.aplicaAnticipo == 1){
                                tituloMsjTabla = "Restante";
    
                                if(row.fechaRealPago_anticipo == null){
                                    contenido += `Anticipo: Sin Fecha<br>`;
                                }else{
                                    contenido += `Anticipo: ${row.fechaRealPago_anticipo}<br>`;
                                }
                            }
    
                            if(row.fechaRealPago_total == null){
                                contenido += `${tituloMsjTabla}: Sin Fecha`;
                            }else{
                                contenido += `${tituloMsjTabla}: ${row.fechaRealPago_total}`;
                            }
    
                            return contenido;
                            
                        }
                    },
                    // COLUMNA EVIDENCIAS DE PAGO
                    {data: null, render: function(data, type, row) {
                            var contenido = "";
                            var tituloMsjTabla = "Total";
    
                            // Si aplica anticipo mostramos btn para subir fecha o mostrar la fecha ya guardada
                            if(row.aplicaAnticipo == 1){
                                tituloMsjTabla = "Restante";
    
                                if (row.evidenciaPago_anticipo == null){
                                    contenido += `Anticipo: Sin Evidencia<br>`;
                                }else{
                                    contenido += `<a target="_blank" href="{{asset('archivos/${row.evidenciaPago_anticipo}')}}">
                                                    <img src="{{asset('img/transferenciadown.png')}}" height=50 width=50>
                                                </a><br>`;
                                }
                            }
                                
                            if (row.evidenciaPago_total_restante == null) {
                                contenido += `${tituloMsjTabla}: Sin Evidencia`;
                            }else{
                                contenido += `<a target="_blank" href="{{asset('archivos/${row.evidenciaPago_total_restante}')}}">
                                                <img src="{{asset('img/transferenciadown.png')}}" height=50 width=50>
                                            </a>`;
                            }
    
                            return contenido;
    
                            
                            
                        }
                    },
                    // COLUMNA MONTOS COMPLEMENTOS
                    {data: null, render: function(data, type, row) {
                            // return `MXN $${parseFloat(data).toFixed(2)}`
                            var contenido = "";
                            var tituloMsjTabla = "Total";
                            
                            // Si aplica anticipo mostramos btn para subir fecha o mostrar la fecha ya guardada
                            if(row.aplicaAnticipo == 1){
                                tituloMsjTabla = "Restante";
    
                                if(row.montoComplemento_anticipo < 1){
                                    contenido += `Anticipo: Sin monto<br>`;
                                }else{
                                    if(row.monedaComplemento_anticipo == "USD"){
                                        contenido += `Cambio Dolar Anticipo: MXN ${formatoNumerico(row.cambioDolar_anticipoComplemento.toFixed(2))}<br>`;
                                    }
                                    
                                    contenido += `Anticipo: ${row.monedaComplemento_anticipo} ${formatoNumerico(row.montoComplemento_anticipo.toFixed(2))}<br>`;
                                    
                                    if(row.monedaComplemento_anticipo == "USD"){
                                        contenido +=  `Anticipo en MXN ${formatoNumerico(row.anticipoComplementoMXN.toFixed(2))}<br>`;
                                    }
                                }
                            }
    
    
                            if(row.montoComplemento_total < 1){
                                contenido += `${tituloMsjTabla}: Sin monto`;
                            }else{
                                if(row.monedaComplemento_total == "USD"){
                                    contenido += `Cambio Dolar ${tituloMsjTabla}: MXN ${formatoNumerico(row.cambioDolar_totalComplemento.toFixed(2))}<br>`;
                                }
    
                                contenido += `${tituloMsjTabla}: ${row.monedaComplemento_total} ${formatoNumerico(row.montoComplemento_total.toFixed(2))}`;
                            
                                if(row.monedaComplemento_total == "USD"){
                                    contenido +=  `<br>${tituloMsjTabla} en MXN ${formatoNumerico(row.totalRestanteComplementoMXN.toFixed(2))}`;
                                }
    
                                if(row.aplicaAnticipo == 1){
                                    contenido +=  `<br>Total en MXN ${formatoNumerico((row.anticipoComplementoMXN + row.totalRestanteComplementoMXN).toFixed(2))}`;
                                }
                            }
    
                            return contenido;
                        }
                    },
                    // COLUMNA ARCHIVOS COMPLEMENTOS
                    {data: null, render: function(data, type, row) {
                            var contenido = "";
                            var tituloMsjTabla = "Total";
                            
                            if(row.formato != "CMG USA"){
                                // Si aplica anticipo mostramos btn para subir fecha o mostrar la fecha ya guardada
                                if(row.aplicaAnticipo == 1){
                                    tituloMsjTabla = "Restante";

                                    if (row.pdfComplemento_anticipo == null || row.xmlComplemento_anticipo == null) {
                                        contenido += `Anticipo: Sin Archivos<br>`;
                                    }else{
                                        contenido += `<div class="">
                                                        <a target="_blank" href="{{asset('archivos/${row.pdfComplemento_anticipo}')}}">
                                                            <img src="{{asset('img/iconpdfdown.png')}}" height=50 width=50>
                                                        </a>
                                                        <a target="_blank" href="{{asset('archivos/${row.xmlComplemento_anticipo}')}}">
                                                            <img src="{{asset('img/iconxmldown.png')}}" height=50 width=50>
                                                        </a>
                                                    </div>`;
                                    }
                                }
                                
                                // Si aplicaba anticipo y  ya se subieron todos los archivos de anticipo o no aplicaba antcipo se visualiza
                                // el boton para guardar arcvhivos anticio o para ver los arcvhivos guardados
                                if(row.procesoAnticiposFinalizado == 1 || row.aplicaAnticipo == 0){
                                    if (row.pdfComplemento_total_restante == null || row.xmlComplemento_total_restante == null) {
                                        contenido += `${tituloMsjTabla}: Sin Archivos`;
                                    }else{
                                        contenido += `<div class="">
                                                        <a target="_blank" href="{{asset('archivos/${row.pdfComplemento_total_restante}')}}">
                                                            <img src="{{asset('img/iconpdfdown.png')}}" height=50 width=50>
                                                        </a>
                                                        <a target="_blank" href="{{asset('archivos/${row.xmlComplemento_total_restante}')}}">
                                                            <img src="{{asset('img/iconxmldown.png')}}" height=50 width=50>
                                                        </a>
                                                    </div>`;
                                    }
                                }
                            }else{
                                // Si aplica anticipo mostramos btn para subir fecha o mostrar la fecha ya guardada
                                if(row.aplicaAnticipo == 1){
                                    tituloMsjTabla = "Restante";

                                    if (row.pdfComplemento_anticipo == null) {
                                        contenido += `Anticipo: Sin Archivos<br>`;
                                    }else{
                                        contenido += `<div class="">
                                                        <a target="_blank" href="{{asset('archivos/${row.pdfComplemento_anticipo}')}}">
                                                            <img src="{{asset('img/iconpdfdown.png')}}" height=50 width=50>
                                                        </a>
                                                    </div>`;
                                    }
                                }
                                
                                // Si aplicaba anticipo y  ya se subieron todos los archivos de anticipo o no aplicaba antcipo se visualiza
                                // el boton para guardar arcvhivos anticio o para ver los arcvhivos guardados
                                if(row.procesoAnticiposFinalizado == 1 || row.aplicaAnticipo == 0){
                                    if (row.pdfComplemento_total_restante == null) {
                                        contenido += `${tituloMsjTabla}: Sin Archivos`;
                                    }else{
                                        contenido += `<div class="">
                                                        <a target="_blank" href="{{asset('archivos/${row.pdfComplemento_total_restante}')}}">
                                                            <img src="{{asset('img/iconpdfdown.png')}}" height=50 width=50>
                                                        </a>
                                                    </div>`;
                                    }
                                }
                            }

                            if(row.formato == "CMG USA"){
                                contenido = "";
                            }

                            return contenido;

                        }
                    },

                ],
                // Indicamos el indice de la columna a ordenar y tipo de ordenamiento
                order: [[0, 'desc']],
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
                dom: 'rt',
                columnDefs: [
                    { className: 'dt-center', targets: [2,3,4,5,9,11] },
                    { className: 'dt-right', targets: [ 6,7,8,10 ]},
                    { className: 'noWrap', targets: '_all'}
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
            $('#TbreporteNotasEntrada tbody').on('click', '.btnDetallesEntrega', function () {
                var dataRow = $("#TbreporteNotasEntrada").DataTable().row($(this).parents('tr')).data();

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

        function limpiarMdlArchivos(){
            $("#contenedorArchivos").html("");
        }

        function formatoNumerico(numero){
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(numero);
        }
    })
</script>
@stop