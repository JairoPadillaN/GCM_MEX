@extends('principalProv')
@section('contenidoProv')

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
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1 class="">Ordenes de compra</h1>
        </div>
        <div class="panel-body">

            <br><br>
            <!-- <table id="" class="table table-striped table-bordered display" style="width:100%;">
                <thead>
                    <tr style="background-color:#9ABCF4">
                        <th style="text-align: center">No. Orden Compra</th>
                        <th style="text-align: center">Fecha</th>
                        <th style="text-align: center">Notas<br>Cerradas</th>
                        <th style="text-align: center">Notas<br>Pendientes</th>
                        <th style="text-align: center">Monto<br>Orden Compra</th>
                        <th style="text-align: center">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ordendesCompra as $oc)
                        <tr>
                            <td style="text-align: center">{{$oc->codigoOrden}}</td>
                            <td style="text-align: center">{{$oc->fechaOrden}}</td>
                            <td style="text-align: center">{{$oc->notasCerradas}}</td>
                            <td style="text-align: center">{{$oc->notasPendientes}}</td>
                            <td style="text-align: right">{{$oc->moneda}} $<?= number_format ($oc->total,2)?></td>
                            <td style="text-align: center">
                                <button class="btn btn-info btn-sm btnDetallesNotas" value="{{$oc->idOrden}}">Detalles</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table> -->

            <table id="tablaOrdenesCompra" class="table table-striped table-bordered display" style="width:100%;">
                <thead>
                    <tr style="background-color:#9ABCF4">
                        <th style="text-align: center !important">No. Orden Compra</th>
                        <th style="text-align: center !important">Fecha</th>
                        <th style="text-align: center !important">Notas Cerradas</th>
                        <th style="text-align: center !important">Notas Pendientes</th>
                        <th style="text-align: center !important">Monto Orden Compra</th>
                        <th style="text-align: center !important">Opciones</th>
                    </tr>
                </thead>
            </table>
            <br><br>

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
                        <th></th>
                    </tr>
                </thead>
            </table>

            <div id="cargarModalFechaP"></div>
            <div id="cargarModalDetalleOrden"></div>
        </div>
    </div>
</div>

<!-- ========== Modal productos en nota de entrada ============================================================-->
    <div class="modal fade" id="modalNuevaNota" data-backdrop="static" data-keyboard="false">
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
                    <div class="" id="contenedorFrm" style="display:;">
                        <form action="/agregarProductoNotaEntrada" id="frmProductosNotaEntrada" method="POST">
                            <!-- Campos ocultos -->
                            <div hidden>
                                <input type="text" name="idNotaEntrada" id="idNotaEntrada">
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <label for="numeroNota" class="col-form-label">Nota de entrada:</label>
                                    <input type="text" class="form-control" id="numeroNota" name="numeroNota" readonly="">
                                </div>
                                <div class="form-group col-sm-9">
                                    <label for="idProducto" class="col-form-label">SKU de OC:</label>
                                    <select class="form-control" name="idProducto" id="idProducto"></select>
                                </div>
                            </div>
                            <div class="row" style="display:flex; justify-content:flex-end; align-items: flex-end;">
                                <div class="form-group col-sm-3">
                                    <label for="piezasPendientes" class="col-form-label">Piezas pendientes:</label>
                                    <input type="number" class="form-control" id="piezasPendientes" name="piezasPendientes" readonly="">
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="piezasRecibidas" class="col-form-label">Piezas recibidas:</label>
                                    <input type="number" class="form-control" id="piezasRecibidas" name="piezasRecibidas" min="1">
                                </div>
                                <div class="form-group col-sm-6">
                                    <button type="submit" class="btn btn-primary btn-sm" id="btnAgregarProductoNotaEntrada">Agregar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="">
                        <div class="row" id="contenedorDetallesNotaEntrada" style="display:;"></div>

                        <div class="table-responsive" style="max-height:250px;">
                            <table class="table table-hover table-striped table-bordered" id="tablaProductosNota">
                                <thead style="position: sticky;top: 0;background-color:#C5EBFB">
                                    <tr>
                                        <th class="hidden">idProducto</th>
                                        <!-- <th style="padding: 5px 25px !important;">Servicio</th> -->
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
                                        <td colspan="8" style="text-align:center">Sin registros</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-align:right"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <div class="row" id="contenedorTotales" style="display:;">
                            <form action="/guardarDatosNotaEntrada" id="frmDatosNotaEntrada" method="post">
                                <div class="col-sm-2">
                                    <div class="form-check">
                                        <label class="" for="aplicaAnticipo">Anticipo</label>
                                        <input type="checkbox" class="form-check-input" id="aplicaAnticipo"name="aplicaAnticipo">
                                    </div>

                                    <div class="input-group">
                                        <input type="number" min="1" class="form-control" id="porcentajeAnticipo" name="porcentajeAnticipo" readonly="true">
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <label class="input-group" for="">Total anticipo:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">$ MXN</span>
                                        <input type="number" min="1" class="form-control" id="cantidadAnticipo"name="cantidadAnticipo" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="input-group" for="">Total Nota De Entrada:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">$ MXN</span>
                                        <input type="number" min="1" class="form-control" id="montoNotaTotal"name="montoNotaTotal" value="0.00" readonly>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" id="contenedorFooterMdl" style="display:flex; justify-content: flex-end">
                    <button type="button" class="btn btn-danger btn-sm" id="btnCancelarNota">Cancelar</button>
                    <button type="button" class="btn btn-success btn-sm col-sm-4" id="btnCrearNota">Guardar</button>
                </div>
            </div>
        </div>
    </div> 
<!-- /.modal -->

<!-- ========== Modal fechas ============================================================-->
    <div class="modal fade" id="modalFechas">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                
                <form action="/actualizarFechasNotaEntrada" id="frmFechas" method="post">
                    <!-- Cabecero del modal -->
                    <div class="modal-header" style="padding:10px">
                        <!-- modal header  -->
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                        <h4 class="modal-title" style="padding-top:0px"><strong id="tituloMdlFechas"></strong></h4>
                    </div>


                    <!-- Cuerpo del modal -->
                    <div class="modal-body">
                        <!-- Campos ocultos -->
                        <div hidden>
                            <input type="text" name="idNotaEntradaFechas" id="idNotaEntradaFechas">
                        </div>

                        <div class="form-group" id="contenedorFechaEntrega" style="display:none;">
                            <span id="msjFechaEntrega">Fecha de entrega:</span>
                            <input type="date" class="form-control" name="fechaEntrega" id="fechaEntrega">
                        </div>
                        <div class="form-group" id="contenedorFechaPagoProgramada_anticipo" style="display:none;">
                            <span id="msjFechaPagoProgramada_anticipo">Anticipo:</span>
                            <input type="date" class="form-control" name="fechaPagoProgramada_anticipo" id="fechaPagoProgramada_anticipo">
                        </div>
                        <div class="form-group" id="contenedorFechaPagoProgramada_total" style="display:none;">
                            <span id="msjFechaPagoProgramada_total">Total:</span>
                            <input type="date" class="form-control" name="fechaPagoProgramada_total" id="fechaPagoProgramada_total">
                        </div>
                        <div class="form-group" id="contenedorFechaRealPago_anticipo" style="display:none;">
                            <span id="msjFechaRealPago_anticipo">Anticipo:</span>
                            <input type="date" class="form-control" name="fechaRealPago_anticipo" id="fechaRealPago_anticipo">
                        </div>
                        <div class="form-group" id="contenedorFechaRealPago_total" style="display:none;">
                            <span id="msjFechaRealPago_total">Total:</span>
                            <input type="date" class="form-control" name="fechaRealPago_total" id="fechaRealPago_total">
                        </div>
                    </div>
                    
                    <div class="modal-footer" style="display:flex; justify-content: flex-end">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success btn-sm col-sm-4">Guardar</button>
                    </div>
                </form>
                    
            </div>
        </div>
    </div> 
<!-- /.modal -->

<!-- ========== Modal archivos PDF y XML ============================================================-->
    <div class="modal fade" id="modalArchivosFactura">
        <div class="modal-dialog">
            <div class="modal-content">
                
                <form action="/subirPDFyXML" id="frmArchivosPDFyXML" method="post">
                    <!-- Cabecero del modal -->
                    <div class="modal-header" style="padding:10px">
                        <!-- modal header  -->
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                        <h4 class="modal-title" style="padding-top:0px"><strong id="tituloMdlArchivos"></strong></h4>
                    </div>

                    <!-- Cuerpo del modal -->
                    <div class="modal-body">
                        <!-- Campos ocultos -->
                        <div hidden>
                            <input type="text" name="idNotaEntradaPDFyXML" id="idNotaEntradaPDFyXML">
                            <input type="text" name="esAnticipo" id="esAnticipo">
                            <input type="text" name="esComplemento" id="esComplemento">
                        </div>

                        <span id="msjArchivos"></span>

                        <div class="form-group" id="" style="display:;">
                            <label for="">PDF:</label>
                            <input type="file" class="form-control" name="archivoPdf" id="archivoPdf" onchange=validarPdf(this) required>
                        </div>
                        <div class="form-group" id="contenedorXML" style="display:;">
                            <label for="">XML:</label>
                            <input type="file" class="form-control" name="archivoXml" id="archivoXml" onchange=validarXml(this) required>
                        </div>
                        
                        <!-- contenedor para mesaje de error -->
                        <div class="alert alert-danger" role="alert" id="contenedorMsjError" style="display:none;"></div>

                    </div>
                        
                    <div class="modal-footer" style="display:flex; justify-content: flex-end">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success btn-sm col-sm-4" id="">Guardar</button>
                    </div>
                </form>   

            </div>
        </div>
    </div> 
<!-- /.modal -->

<!-- ========== Modal archivos evidencia de pago ============================================================-->
    <div class="modal fade" id="modalArchivosEvidencia">
        <div class="modal-dialog">
            <div class="modal-content">
                
                <form action="/subirEvidenciaPago" id="frmArchivosEvidencia" method="post">
                    <!-- Cabecero del modal -->
                    <div class="modal-header" style="padding:10px">
                        <!-- modal header  -->
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                        <h4 class="modal-title" style="padding-top:0px"><strong id="tituloMdlArchivosEvidencia">Evidencia de pago</strong></h4>
                    </div>

                    <!-- Cuerpo del modal -->
                    <div class="modal-body">
                        <!-- Campos ocultos -->
                        <div hidden>
                            <input type="text" name="idNotaEntradaArchivosEvidencia" id="idNotaEntradaArchivosEvidencia">
                            <input type="text" name="esAnticipoEvidencias" id="esAnticipoEvidencias">
                        </div>

                        <div class="form-group" id="" >
                            <input type="file" class="form-control" name="evidencia" id="evidencia" required>
                        </div>
                    </div>
                        
                    <div class="modal-footer" style="display:flex; justify-content: flex-end">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success btn-sm col-sm-4" id="">Guardar</button>
                    </div>
                </form>   

            </div>
        </div>
    </div> 
<!-- /.modal -->

<!-- ========== Modal montos complementos ============================================================-->
    <div class="modal fade" id="modalMontosComplementos">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                
                <form action="/guardarMontosComplementos" id="frmMontosComplementos" method="post">
                    <!-- Cabecero del modal -->
                    <div class="modal-header" style="padding:10px">
                        <!-- modal header  -->
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                        <h4 class="modal-title" style="padding-top:0px"><strong id="tituloMdlArchivosEvidencia">Montos De Complementos</strong></h4>
                    </div>

                    <!-- Cuerpo del modal -->
                    <div class="modal-body">
                        <!-- Campos ocultos -->
                        <div hidden>
                            <input type="text" name="idNotaEntradaMontosComplementos" id="idNotaEntradaMontosComplementos">
                        </div>

                        <div id="contenedorMontoComplemento_anticipo" style="display:;">
                            <div class="form-group">
                                <label for="">Moneda:</label>
                                <div>
                                    <input checked="checked" name="monedaComplemento_anticipo" type="radio" value="MXN"> MXN&nbsp;&nbsp;&nbsp;
                                    <input name="monedaComplemento_anticipo" type="radio" value="USD"> USD
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Anticipo:</label>
                                <input type="number" step="0.01" class="form-control" name="montoComplemento_anticipo" id="montoComplemento_anticipo">
                            </div>
                        </div>

                        <div id="contenedorMontoComplemento_total" style="display:;">
                            <div class="form-group">
                                <label for="">Moneda:</label>
                                <div>
                                    <input checked="checked" name="monedaComplemento_total" type="radio" value="MXN"> MXN&nbsp;&nbsp;&nbsp;
                                    <input name="monedaComplemento_total" type="radio" value="USD"> USD
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Total:</label>
                                <input type="number" step="0.01" class="form-control" name="montoComplemento_total" id="montoComplemento_total">
                            </div>
                        </div>

                    </div>
                        
                    <div class="modal-footer" style="display:flex; justify-content: flex-end">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success btn-sm col-sm-4" id="">Guardar</button>
                    </div>
                </form>   

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

                        <div id="contenedorGuardarDetallesEntrega" style="display:;">
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <label for="numGuia" class="col-form-label">Número de guia:</label>
                                    <input type="text" class="form-control" id="numGuia" name="numGuia" required>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="ciudadOrigen" class="col-form-label">Ciudad origen:</label>
                                    <input type="text" class="form-control" id="ciudadOrigen" name="ciudadOrigen">
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="ciudadDestino" class="col-form-label">Ciudad destino:</label>
                                    <input type="text" class="form-control" id="ciudadDestino" name="ciudadDestino">
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="paqueteria" class="col-form-label">Paqueteria:</label>
                                    <input type="text" class="form-control" id="paqueteria" name="paqueteria">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <label for="numGuia2" class="col-form-label">Número de guia:</label>
                                    <input type="text" class="form-control" id="numGuia2" name="numGuia2">
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="ciudadOrigen2" class="col-form-label">Ciudad origen:</label>
                                    <input type="text" class="form-control" id="ciudadOrigen2" name="ciudadOrigen2">
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="ciudadDestino2" class="col-form-label">Ciudad destino:</label>
                                    <input type="text" class="form-control" id="ciudadDestino2" name="ciudadDestino2">
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="paqueteria2" class="col-form-label">Paqueteria:</label>
                                    <input type="text" class="form-control" id="paqueteria2" name="paqueteria2">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <label for="folioFacturaImportacion" class="col-form-label">Folio Factura de importación:</label>
                                    <input type="text" class="form-control" id="folioFacturaImportacion" name="folioFacturaImportacion">
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="folioGastosImportacion" class="col-form-label">Folio de gastos de importación:</label>
                                    <input type="text" class="form-control" id="folioGastosImportacion" name="folioGastosImportacion">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <b>Metodo de entrega: </b>
                                    <div><input type="radio" name="metodoEntrega" id="op1" value='1' checked> Ocurre
                                    </div>
                                    <div><input type="radio" name="metodoEntrega" id="op2" value='2'> Entrega a
                                        domicilio</div>
                                    <div><input type="radio" name="metodoEntrega" id="op3" value='3'> Entrega en oficina
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <b>Detalles de entrega: </b>
                                    <textarea name="detalleEntrega" rows="5" class="form-control rounded-0"
                                        placeholder="Escribe aquí más detalles de la entrega."></textarea>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="numGuia" class="col-form-label">Archivos para guía de entrega:</label>
                                        <div>{{ Form::file('archivoPaqueteria1',['id'=>'archivoPaqueteria1'])}}</div>
                                        <div>{{ Form::file('archivoPaqueteria2',['id'=>'archivoPaqueteria2'])}}</div>
                                        <div>{{ Form::file('archivoPaqueteria3',['id'=>'archivoPaqueteria3'])}}</div>
                                        <div>{{ Form::file('archivoPaqueteria4',['id'=>'archivoPaqueteria4'])}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="" id="contenedorVerDetallesEntrega" style="display:none;">
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
                    
                    <div class="modal-footer" style="display:flex; justify-content: flex-end" id="footerMdl">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success btn-sm col-sm-4">Guardar</button>
                    </div>
                </form>
                    
            </div>
        </div>
    </div> 
<!-- /.modal -->

<script>
    /* Variables Globales */
    var idOrdenCompra = '';
    var idu = '<?= Session::get('sesionidu')?>';
    var datosProductosOc = [];
    var productoSeleccionado = [];
    var produtosEnNota = [];
    var datosTabla = [];
    var sumaSubtotal = 0;
    var sumaIva = 0;
    var sumaTotal = 0;
    var tablaNotasEntrada;
    var tablaOrdenesCompra;
    // $(".verDetalleOrden").click(function (e) {
    //     var idOrden = $(this).val();
    //     $('#cargarModalDetalleOrden').load('{{url('cargarModalDetalleOrden')}}' + '?idOrden='+idOrden);
    //     e.preventDefault(); 
    // });

    $( document ).ready(function() {
        var archivoCuentas = "<?php echo $consultaArchivosProveedor->archivoCuentas;?>"
        var constanciaFiscal = "<?php echo $consultaArchivosProveedor->constanciaFiscal;?>"
        
        if( archivoCuentas == 'Sin archivo' || archivoCuentas == '' ||  constanciaFiscal == 'Sin archivo' || constanciaFiscal == ''){            
            Swal.fire({            
            icon: 'error',
            showConfirmButton: false,
            html: '<h5>Subir archivos de cuentas bancarias para continuar</h5><br><a href="{!! asset("cuentasProveedor")!!}"><button type="button" class="btn btn-danger">Cuentas bancarias</button></a>',
            allowOutsideClick: false
            })            
        }
        
        const contenedorMsjError = document.querySelector('#contenedorMsjError');
        const contenidoTablaDetalle = document.querySelector('#tablaProductosNota tbody');
        const tablaDetalleNota = document.querySelector('#tablaProductosNota');
        const aplicaAnticipo = document.querySelector('#aplicaAnticipo');
        const inputPorcentajeAnticipo = document.querySelector("#porcentajeAnticipo");
        const inputCantidadAnticipo = document.querySelector("#cantidadAnticipo");
        const inputMontoNotaTotal = document.querySelector("#montoNotaTotal");
        const btnCrearNota = document.querySelector('#btnCrearNota');
        const btnCancelarNota = document.querySelector('#btnCancelarNota');
        const inputIdNotaEntrada = document.querySelector("#idNotaEntrada");
        const tituloMdlFechas = document.querySelector("#tituloMdlFechas");
        const tituloMdlArchivos = document.querySelector("#tituloMdlArchivos");
        const frmFechas = document.querySelector('#frmFechas');
        const frmArchivosPDFyXML = document.querySelector('#frmArchivosPDFyXML');
        const frmArchivosEvidencia = document.querySelector('#frmArchivosEvidencia');
        const frmMontosComplementos = document.querySelector('#frmMontosComplementos');
        const frmDetallesEntrega = document.querySelector('#frmDetallesEntrega');
        
        cargarEventListeners();      
        crearTabla(0)
        crearTablaOC()

        /* Funciones */
        function cargarEventListeners(){
            frmFechas.addEventListener('submit', (event) => {guardarFechasNotaEntrada(event)});
            frmArchivosEvidencia.addEventListener('submit', (event) => {guardarArchivosEvidencia(event)});
            frmArchivosPDFyXML.addEventListener('submit', (event) => {guardarPDFyXML(event)});
            frmMontosComplementos.addEventListener('submit', (event) => {guardarMontosComplementos(event)});
            frmDetallesEntrega.addEventListener('submit', (event) => {guardarDetallesEntrega(event)});
            tablaDetalleNota.addEventListener('click', eliminarProductoNotaEntrada);
            aplicaAnticipo.addEventListener('click', validarAnticipo);
            $(".btnDetallesNotas").click((event) => {obtenerIdOrden(event)});
            inputPorcentajeAnticipo.addEventListener('keyup', calcularAnticipo);
            $('#modalNuevaNota').on('hide.bs.modal', limpiarMdl);
            $('#modalFechas').on('hide.bs.modal', limpiarFrmFechas);
            $('#modalArchivosFactura').on('hide.bs.modal', limpiarFrmArchivos);
            $('#modalArchivosEvidencia').on('hide.bs.modal', limpiarFrmArchivosEvidencia);
            $('#modalMontosComplementos').on('hide.bs.modal', limpiarFrmMontosComplementos);
            $('#modalDetallesEntrega').on('hide.bs.modal', limpiarFrmDetallesEntrega);
            // $('#tablaOrdenesCompra').DataTable({
            //     order: [[3, 'desc']],
            // });
            
        }

        function obtenerIdOrden(event){
            if(event.target.classList.contains('btnDetallesNotas')){
                crearTabla(event.target.value)
            }
        }

        function crearTablaOC(){

            // Creando el cuerpo de la tabla con dataTable y ajax
            tablaOrdenesCompra = $("#tablaOrdenesCompra").dataTable({

                // Petición para llenar la tabla
                "ajax": {
                    url: `/consultaOrdenesCompra`,
                    dataSrc: ''
                },
                "columns": [
                    {data: "codigoOrden"},
                    {data: "fechaOrden"},
                    {data: "notasCerradas"},
                    {data: "notasPendientes"},
                    {data: null, render: function(data, type, row) {
                            return  `${row.moneda} ${formatoNumerico(parseFloat(row.total).toFixed(2))}`;
                        }
                    },
                    {data: null, render: function(data, type, row) {
                            return  `<button class="btn btn-info btn-sm btnDetallesNotas" value="${row.idOrden}">Detalles</button>`;
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
                dom: '<"row"<"col-sm-12"f>>rt<"row"<"col-sm-3"l><"col-sm-3"i><"col-sm-6"p>>',
                columnDefs: [
                    { className: 'dt-center', targets: [1,2,3,5] },
                    { className: 'dt-right', targets: [4]},
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
            
            $('#tablaOrdenesCompra tbody').on('click', '.btnDetallesNotas', function () {
                console.log('tn')
                crearTabla($(this)[0].value);
            });
        }        

        function crearTabla(idOrdenCompra){
            
            if(tablaNotasEntrada !== undefined){
                $('#TbreporteNotasEntrada').DataTable().clear().destroy();
            }
            

            // Creando el cuerpo de la tabla con dataTable y ajax
            tablaNotasEntrada = $("#TbreporteNotasEntrada").dataTable({

                // Petición para llenar la tabla
                "ajax": {
                    url: `/consultaNotasEntrada/${idOrdenCompra}`,
                    dataSrc: ''
                },
                "columns": [
                    {data: 'idNotaEntrada', visible: false},
                    {data: 'idOrden', visible: false},
                    {data: 'numeroNota', visible: true},
                    {data: 'fechaEntrega', render: function(data, type, row) {
                            if(data == null){
                                return `<button type="button" style="width: 100%" class="btn btn-default btn-sm btnAgregarFecha fechaEntrega">Agregar fecha</button>`;
                            }else{
                                return data;
                            }
                        }
                    },
                    {data: 'detallesEntrega', render: function(data, type, row) {
                            var contenido = "";
                            if (row.numGuia == null) {
                                contenido = `<button type="button" style="width: 100%" class="btn btn-default btn-sm btnDetallesEntrega">Agregar detalles</button><br>`;
                            } else {
                                contenido = `<button type="button" style="width: 100%" class="btn btn-default btn-sm btnDetallesEntrega">Ver detalles</button><br>`;
                            }
                            return contenido;
                        }
                    },
                    // COLUMNA SUBIR FACTURAS
                    {data: null, render: function(data, type, row) {
                        console.log(row.formato)
                            var contenido = "";
                            var tituloMsjTabla = "";

                            if(row.formato != "CMG USA"){
                                // Si aplica anticipo Mostramos subir los archivos o mostrar los archivos si ya se subieron
                                if (row.aplicaAnticipo == 1){
                                    tituloMsjTabla = "restante";
                                    if (row.pdfFactura_anticipo == null || row.xmlFactura_anticipo == null) {
                                        contenido += `<button type="button" style="width: 100%" class="btn btn-default btn-sm btnSubirArchivosFactura anticipo">Subir archivos anticipo</button><br>`;
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
                                // el boton para suibir archivso o para ver los archivos subidos
                                if(row.procesoAnticiposFinalizado == 1 || row.aplicaAnticipo == 0){
                                    if (row.pdfFactura_total_restante == null || row.xmlFactura_total_restante == null) {
                                        contenido += `<button type="button" style="width: 100%" class="btn btn-default btn-sm btnSubirArchivosFactura">Subir archivos ${tituloMsjTabla}</button>`;
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
                                // Si aplica anticipo Mostramos subir los archivos o mostrar los archivos si ya se subieron
                                if (row.aplicaAnticipo == 1){
                                    tituloMsjTabla = "restante";
                                    if (row.pdfFactura_anticipo == null) {
                                        contenido += `<button type="button" style="width: 100%" class="btn btn-default btn-sm btnSubirArchivosFacturaInternacionales anticipo">Subir archivos anticipo</button><br>`;
                                    }else{
                                        contenido += `<div class="">
                                                        <a target="_blank" href="{{asset('archivos/${row.pdfFactura_anticipo}')}}">
                                                            <img src="{{asset('img/iconpdfdown.png')}}" height=50 width=50>
                                                        </a>
                                                    </div>`;
                                    }
                                }
                                // Si aplicaba anticipo y  ya se subieron todos los archivos de anticipo o no aplicaba antcipo se visualiza
                                // el boton para suibir archivso o para ver los archivos subidos
                                if(row.procesoAnticiposFinalizado == 1 || row.aplicaAnticipo == 0){
                                    if (row.pdfFactura_total_restante == null) {
                                        contenido += `<button type="button" style="width: 100%" class="btn btn-default btn-sm btnSubirArchivosFacturaInternacionales">Subir archivos ${tituloMsjTabla}</button>`;
                                    }else{
                                        contenido += `<div class="">
                                                        <a target="_blank" href="{{asset('archivos/${row.pdfFactura_total_restante}')}}">
                                                            <img src="{{asset('img/iconpdfdown.png')}}" height=50 width=50>
                                                        </a>
                                                    </div>`;
                                    }
                                }
                            }

                            // Si antes no hay fecha de entrega no se mostrara nada
                            if(row.fechaEntrega == null){
                                contenido = "-";
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

                                    if ((row.pdfComplemento_anticipo == null || row.xmlComplemento_anticipo == null) && (row.montoComplemento_anticipo >= 1)) {
                                        contenido += `<button type="button" style="width: 100%" class="btn btn-default btn-sm btnSubirArchivosFactura anticipo complementos">Subir archivos anticipo</button><br>`;
                                    }else if((row.pdfComplemento_anticipo == null || row.xmlComplemento_anticipo == null) && (row.montoComplemento_anticipo < 1)){
                                        contenido += `Anticipo: Sin datos<br>`;
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
                                
                                if ((row.pdfComplemento_total_restante == null || row.xmlComplemento_total_restante == null) && (row.montoComplemento_total >= 1)) {
                                    contenido += `<button type="button" style="width: 100%" class="btn btn-default btn-sm btnSubirArchivosFactura complementos">Subir archivos</button>`;
                                }else if ((row.pdfComplemento_total_restante == null || row.xmlComplemento_total_restante == null) && (row.montoComplemento_total < 1)) {
                                    contenido += `${tituloMsjTabla}: Sin datos`;
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
                            }else{
                                // Si aplica anticipo mostramos btn para subir fecha o mostrar la fecha ya guardada
                                if(row.aplicaAnticipo == 1){
                                    tituloMsjTabla = "Restante";

                                    if (row.pdfComplemento_anticipo == null && row.montoComplemento_anticipo >= 1) {
                                        contenido += `<button type="button" style="width: 100%" class="btn btn-default btn-sm btnSubirArchivosFacturaInternacionales anticipo complementos">Subir archivos anticipo</button><br>`;
                                    }else if(row.pdfComplemento_anticipo == null && row.montoComplemento_anticipo < 1){
                                        contenido += `Anticipo: Sin datos<br>`;
                                    }else{
                                        contenido += `<div class="">
                                                        <a target="_blank" href="{{asset('archivos/${row.pdfComplemento_anticipo}')}}">
                                                            <img src="{{asset('img/iconpdfdown.png')}}" height=50 width=50>
                                                        </a>
                                                    </div>`;
                                    }
                                }
                                
                                if (row.pdfComplemento_total_restante == null && row.montoComplemento_total >= 1) {
                                    contenido += `<button type="button" style="width: 100%" class="btn btn-default btn-sm btnSubirArchivosFacturaInternacionales complementos">Subir archivos</button>`;
                                }else if (row.pdfComplemento_total_restante == null && row.montoComplemento_total < 1) {
                                    contenido += `${tituloMsjTabla}: Sin datos`;
                                }else{
                                    contenido += `<div class="">
                                                    <a target="_blank" href="{{asset('archivos/${row.pdfComplemento_total_restante}')}}">
                                                        <img src="{{asset('img/iconpdfdown.png')}}" height=50 width=50>
                                                    </a>
                                                </div>`;
                                }
                            }

                            if(row.formato == "CMG USA"){
                                contenido = "";
                            }
                            
                            return contenido;


                        }
                    },
                    // OPCIONES
                    { data: null, render: function ( data, type, row ) {
                            return `<button class="btn btn-info btn-sm detalleNotaEntrada" value="${row.idNotaEntrada}"><span class="glyphicon glyphicon-eye-open"></span></button>`;
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
                dom: '<"row"<"col-sm-12"f>>rt<"row"<"col-sm-3"l><"col-sm-3"i><"col-sm-6"p>>',
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

            // Muestra el contenido de la nota de entrda
            $('#TbreporteNotasEntrada tbody').on('click', '.detalleNotaEntrada', function () {
                var dataRow = $("#TbreporteNotasEntrada").DataTable().row($(this).parents('tr')).data();
                
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: `/consultaProdcutosPorNotaEntrada/${$(this)[0].value}`,
                    type: "GET",
                    dataType: 'json',
                    success: function (res) {
                        datosTabla = res;
                        limpiarFrm();
                        crearTablaProductosEnNota(res);
                        ocultarElementos(true);

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

            // Agregar Fechas al dar clic en los botones de fechas
            $('#TbreporteNotasEntrada tbody').on('click', '.btnAgregarFecha', function () {
                var dataRow = $("#TbreporteNotasEntrada").DataTable().row($(this).parents('tr')).data();

                document.querySelector("#idNotaEntradaFechas").value = dataRow.idNotaEntrada;

                ocultarElementosFechas();

                var clases = $(this)[0].classList;
                switch (true) {
                    case clases.contains('fechaEntrega'):
                        tituloMdlFechas.textContent = "Fecha De Entrega"
                        document.querySelector("#contenedorFechaEntrega").style.display = "";
                        document.querySelector("#fechaEntrega").setAttribute("required", "");
                        document.querySelector("#msjFechaEntrega").innerHTML = `Agregar fecha de entrega para la Nota de entrada <strong>${dataRow.numeroNota}</strong>:`;
                    break;
                    case clases.contains('fechaPagoProgramadaAnticipo'):
                        tituloMdlFechas.textContent = "Fecha De Pago"
                        document.querySelector("#contenedorFechaPagoProgramada_anticipo").style.display = "";
                        document.querySelector("#fechaPagoProgramada_anticipo").setAttribute("required", "");
                        document.querySelector("#msjFechaPagoProgramada_anticipo").innerHTML = ``;
                    break;
                    case clases.contains('fechaPagoProgramadaTotal'):
                        tituloMdlFechas.textContent = "Fecha De Pago"
                        document.querySelector("#contenedorFechaPagoProgramada_total").style.display = "";
                        document.querySelector("#fechaPagoProgramada_total").setAttribute("required", "");
                        document.querySelector("#msjFechaPagoProgramada_total").innerHTML = ``;
                    break;
                    case clases.contains('fechaRealPagoAnticipo'):
                        tituloMdlFechas.textContent = "Fecha Real De Pago"
                        document.querySelector("#contenedorFechaRealPago_anticipo").style.display = "";
                        document.querySelector("#fechaRealPago_anticipo").setAttribute("required", "");
                        document.querySelector("#msjFechaRealPago_anticipo").innerHTML = ``;
                    break;
                    case clases.contains('fechaRealPagoTotal'):
                        tituloMdlFechas.textContent = "Fecha Real De Pago"
                        document.querySelector("#contenedorFechaRealPago_total").style.display = "";
                        document.querySelector("#fechaRealPago_total").setAttribute("required", "");
                        document.querySelector("#msjFechaRealPago_total").textContent = ``;
                    break;
                    default:
                        break;
                }

                $('#modalFechas').modal('show');
            });

            // Subir archivos XML y PDF
            $('#TbreporteNotasEntrada tbody').on('click', '.btnSubirArchivosFactura', function () {
                var dataRow = $("#TbreporteNotasEntrada").DataTable().row($(this).parents('tr')).data();
                var titulo = "";
                var inputEsAnticipo = document.querySelector("#esAnticipo");
                var inputEsComplemento = document.querySelector("#esComplemento");

                document.querySelector("#idNotaEntradaPDFyXML").value = dataRow.idNotaEntrada;                
                
                var clases = $(this)[0].classList;
                if(!clases.contains('complementos')){
                    inputEsComplemento.value = 0;
                    if(clases.contains('anticipo')){
                        inputEsAnticipo.value = 1;
                        titulo = "Facturas Anticipo"
                        document.querySelector("#msjArchivos").innerHTML = `Agregar archivos de factura por el monto de <strong>${dataRow.monedaNota} $${dataRow.cantidadAnticipo.toFixed(2)}</strong> de la nota <strong>${dataRow.numeroNota}</strong>.`;
                    }else{
                        inputEsAnticipo.value = 0;
                        titulo = "Facturas"
                        document.querySelector("#msjArchivos").innerHTML = `Agregar archivos de factura por el monto de <strong>${dataRow.monedaNota} $${dataRow.montoNotaRestante.toFixed(2)}</strong> de la nota <strong>${dataRow.numeroNota}</strong>.`;
                    }
                }
                
                if (clases.contains('complementos')){
                    inputEsComplemento.value = 1;
                    if(clases.contains('anticipo')){
                        inputEsAnticipo.value = 1;
                        titulo = "Complementos Anticipo"
                        document.querySelector("#msjArchivos").innerHTML = `Agregar archivos de complemento por el monto de <strong>${dataRow.monedaComplemento_anticipo} $${dataRow.montoComplemento_anticipo.toFixed(2)}</strong> de la nota <strong>${dataRow.numeroNota}</strong>.`;
                    }else{
                        inputEsAnticipo.value = 0;
                        titulo = "Complementos"
                        document.querySelector("#msjArchivos").innerHTML = `Agregar archivos de complemento por el monto de <strong>${dataRow.monedaComplemento_total} $${dataRow.montoComplemento_total.toFixed(2)}</strong> de la nota <strong>${dataRow.numeroNota}</strong>.`;
                    }
                }

                tituloMdlArchivos.textContent = titulo;

                $('#modalArchivosFactura').modal('show');
            });

            // Subir solo PDF Internacionelas
            $('#TbreporteNotasEntrada tbody').on('click', '.btnSubirArchivosFacturaInternacionales', function () {
                var dataRow = $("#TbreporteNotasEntrada").DataTable().row($(this).parents('tr')).data();
                var titulo = "";
                var inputEsAnticipo = document.querySelector("#esAnticipo");
                var inputEsComplemento = document.querySelector("#esComplemento");
                // Ocultamos el xml porque es internacional
                document.querySelector("#contenedorXML").style.display = "none";
                document.querySelector("#archivoXml").removeAttribute("required");

                document.querySelector("#idNotaEntradaPDFyXML").value = dataRow.idNotaEntrada;
                
                var clases = $(this)[0].classList;
                if(!clases.contains('complementos')){
                    inputEsComplemento.value = 0;
                    if(clases.contains('anticipo')){
                        inputEsAnticipo.value = 1;
                        titulo = "Facturas Anticipo"
                        document.querySelector("#msjArchivos").innerHTML = `Agregar archivos de factura por el monto de <strong>${dataRow.monedaNota} $${dataRow.cantidadAnticipo.toFixed(2)}</strong> de la nota <strong>${dataRow.numeroNota}</strong>.`;
                    }else{
                        inputEsAnticipo.value = 0;
                        titulo = "Facturas"
                        document.querySelector("#msjArchivos").innerHTML = `Agregar archivos de factura por el monto de <strong>${dataRow.monedaNota} $${dataRow.montoNotaRestante.toFixed(2)}</strong> de la nota <strong>${dataRow.numeroNota}</strong>.`;
                    }
                }
                
                if (clases.contains('complementos')){
                    inputEsComplemento.value = 1;
                    if(clases.contains('anticipo')){
                        inputEsAnticipo.value = 1;
                        titulo = "Complementos Anticipo"
                        document.querySelector("#msjArchivos").innerHTML = `Agregar archivos de complemento por el monto de <strong>${dataRow.monedaComplemento_anticipo} $${dataRow.montoComplemento_anticipo.toFixed(2)}</strong> de la nota <strong>${dataRow.numeroNota}</strong>.`;
                    }else{
                        inputEsAnticipo.value = 0;
                        titulo = "Complementos"
                        document.querySelector("#msjArchivos").innerHTML = `Agregar archivos de complemento por el monto de <strong>${dataRow.monedaComplemento_total} $${dataRow.montoComplemento_total.toFixed(2)}</strong> de la nota <strong>${dataRow.numeroNota}</strong>.`;
                    }
                }

                tituloMdlArchivos.textContent = titulo;

                $('#modalArchivosFactura').modal('show');
            });

            // Subir archivos Evidencia
            $('#TbreporteNotasEntrada tbody').on('click', '.btnSubirArchivosEvidencia', function () {
                var dataRow = $("#TbreporteNotasEntrada").DataTable().row($(this).parents('tr')).data();
                document.querySelector("#idNotaEntradaArchivosEvidencia").value = dataRow.idNotaEntrada;
                
                var clases = $(this)[0].classList;
                if(clases.contains('anticipo')){
                    document.querySelector("#esAnticipoEvidencias").value = 1;
                    titulo = "Complementos Anticipo"
                }else{
                    document.querySelector("#esAnticipoEvidencias").value = 0;
                    titulo = "Complementos"
                }

                $('#modalArchivosEvidencia').modal('show');
            });

            // Agregar montos complementos
            $('#TbreporteNotasEntrada tbody').on('click', '.btnAgregarMontosComplemento', function () {
                limpiarFrmMontosComplementos();
                var dataRow = $("#TbreporteNotasEntrada").DataTable().row($(this).parents('tr')).data();
                var clases = $(this)[0].classList;
                document.querySelector("#idNotaEntradaMontosComplementos").value = dataRow.idNotaEntrada;

                var clases = $(this)[0].classList;
                if(clases.contains('anticipo')){
                    document.querySelector("#contenedorMontoComplemento_anticipo").style.display = "";
                    document.querySelector("#montoComplemento_anticipo").setAttribute("required", "");
                }else{
                    document.querySelector("#contenedorMontoComplemento_total").style.display = "";
                    document.querySelector("#montoComplemento_total").setAttribute("required", "");
                }

                $('#modalMontosComplementos').modal('show');
            });
            
        }

        // Agregar detalles de entrega
        $('#TbreporteNotasEntrada tbody').on('click', '.btnDetallesEntrega', function () {
            var dataRow = $("#TbreporteNotasEntrada").DataTable().row($(this).parents('tr')).data();
            document.querySelector("#idNotaEntradaDetallesEntrega").value = dataRow.idNotaEntrada;
            console.log(event.target)
            if(dataRow.numGuia != null){
                // ocultar Elementos del alta
                document.querySelector("#contenedorGuardarDetallesEntrega").style.display = "none";
                document.querySelector("#contenedorVerDetallesEntrega").style.display = "";
                document.querySelector("#footerMdl").style.display = "none";

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

            }

            $('#modalDetallesEntrega').modal('show');

        });

        function crearSelectProductos(){
            // peticion a la base
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('getProductosOC') }}",
                type: "post",
                data: {idOrden: idOrdenCompra},
                success: function(data){
                    datosProductosOc = data
                    // Limpiando el select
                    selectProductos.innerHTML = '';

                    // creando el select con los productos en la OC
                    selectProductos.innerHTML += "<option value=''>Selecionar producto</option>";
                    data.forEach(data => {
                        selectProductos.innerHTML += "<option value='"+data.idProducto+"'>"+data.producto+"</option>";
                    });
                },
                error: function (error) {
                },
            });
        }

        function  eliminarNotaEntrada(idNotaEliminar = null){
            if(idNotaEliminar == null){
                var idNotaEliminar = inputIdNotaEntrada.value;
            }

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {idNotaEntrada: idNotaEliminar},
                url: "/eliminarNotaEntrada",
                type: "POST",
                dataType: 'json',
                success: function (res) {
                    $('#TbreporteNotasEntrada').DataTable().ajax.reload();
                    $('#modalNuevaNota').modal('hide');
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function crearNotaEntrada(){
            ocultarElementos(false);
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '/crearNotaEntrada',
                type: 'POST',
                dataType: "json",
                success: function(data){
                    document.querySelector("#contenedorDetallesNotaEntrada").innerHTML ="";
                    document.querySelector("#numeroNota").value = data.numeroNota;
                    inputIdNotaEntrada.value = data.idNotaEntrada;
                    $('#modalNuevaNota').modal('show');
                },
                error: function (error) {
                },
            });
        }

        function datosProducto(){
            
            productoSeleccionado = datosProductosOc.filter(producto => producto.idProducto == selectProductos.value)
            console.log(productoSeleccionado)
            document.querySelector("#piezasPendientes").value = productoSeleccionado[0].faltaEntregar;
            document.querySelector("#piezasRecibidas").value = productoSeleccionado[0].faltaEntregar;

            document.getElementById("piezasRecibidas").removeAttribute("max");
            document.getElementById("piezasRecibidas").setAttribute("max", productoSeleccionado[0].faltaEntregar);
        }
    
        function agregarProductoNotaEntrada(event){
            event.preventDefault();

            // Obtenemos la operacion a realizar create ó update
            var form_action = $("#frmProductosNotaEntrada").attr("action");
            // Guardamos el form con los input file para subir archivos
            var formData = new FormData(document.getElementById("frmProductosNotaEntrada"));

            var objetformData = formDataToObjet(formData);
            
            var subtotal = (parseFloat(productoSeleccionado[0].precioUnidad) * parseFloat(objetformData.piezasRecibidas));
            var iva = (parseFloat(productoSeleccionado[0].ivaUnidad) * parseFloat(objetformData.piezasRecibidas));
            var total = (subtotal + iva);
            var piezasPendientes = (parseFloat(objetformData.piezasPendientes) - parseFloat(objetformData.piezasRecibidas));
            
            formData.append('piezasPendientes',piezasPendientes);
            formData.append('subtotal',subtotal.toFixed(2));
            formData.append('iva',iva.toFixed(2));
            formData.append('total',total.toFixed(2));
    
            // reiniciar totales
            reiniciartotales();

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: formData,
                url: form_action,
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (res) {
                    datosTabla = res;
                    limpiarFrm();
                    crearTablaProductosEnNota(res);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function crearTablaProductosEnNota(data){
            console.log(data)
            // reiniciar totales
            reiniciartotales();
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
                // let td_servicio = document.createElement("td");
                // td_servicio.setAttribute("align", "center");
                // td_servicio.textContent = producto.servicio;
                // trDatos.appendChild(td_servicio);

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

                // Creando el td de opciones
                let opciones = document.createElement("td");
                opciones.setAttribute("align", "center");
                // Creando boton de eliminar
                let btnEliminar = document.createElement("button");
                btnEliminar.className = "btn btn-danger btn-sm eliminarProducto";
                btnEliminar.value = producto.idDetalleNotasEntrada;
                btnEliminar.innerHTML = '<i class="fa fa-trash"></i>';
                opciones.appendChild(btnEliminar);
                trDatos.appendChild(opciones);

                // Totalmente agregamos el <tr> al cuerpo de la tabla
                contenidoTablaDetalle.appendChild(trDatos);
            })

            const trTotal = document.createElement("tr");
            
            // Creamos el <td> del total
            let td_total = document.createElement("td");
            td_total.setAttribute("colspan", "3");
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
            
            inputMontoNotaTotal.value = `${sumaTotal.toFixed(2)}`;
        }

        function limpiarFrm(){
            crearSelectProductos();
            aplicaAnticipo.checked = false;
            validarAnticipo();
            document.querySelector("#piezasPendientes").value = "";
            document.querySelector("#piezasRecibidas").value = "";
        }

        function eliminarProductoNotaEntrada(event) {
            var btnClick = "";

            if(event.target.classList.contains('fa')) {
                btnClick = event.target.parentElement;
            }else{
                btnClick = event.target;
            }
            
            var filaEliminar = btnClick.parentElement.parentElement;

            if(btnClick.classList.contains('eliminarProducto') ) {
                let resultado = datosTabla.filter( fila => fila.idDetalleNotasEntrada == btnClick.value);
                
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '/eliminarProductoNotaEntrada',
                    type: "POST",
                    dataType: 'json',
                    data: {
                        idProducto: resultado[0].idProducto,
                        idDetalleNotasEntrada: resultado[0].idDetalleNotasEntrada,
                        piezasRecibidas: resultado[0].piezasRecibidas
                    },
                    success: function (res) {
                        crearSelectProductos();
                        crearTablaProductosEnNota(res);
                        // filaEliminar.remove();

                        aplicaAnticipo.checked = false;
                        validarAnticipo();
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });

            }
        };

        function calcularAnticipo(){
            var anticipo = ((parseFloat(inputPorcentajeAnticipo.value) * sumaTotal) / 100).toFixed(2)
            inputCantidadAnticipo.value = anticipo;
        }

        function validarAnticipo(){
            if(aplicaAnticipo.checked){
                inputPorcentajeAnticipo.readOnly = false;
            }else{
                inputPorcentajeAnticipo.readOnly = true;
                inputPorcentajeAnticipo.value = '';
                inputCantidadAnticipo.value = '';
            }
        }

        function limpiarMdl(){
            reiniciartotales();
            contenidoTablaDetalle.innerHTML = "";
            limpiarFrm();
        }

        function guardarDatosNotaEntrada(){
            // Obtenemos la operacion a realizar create ó update
            var form_action = $("#frmDatosNotaEntrada").attr("action");
            // Guardamos el form con los input file para subir archivos
            var formData = new FormData(document.getElementById("frmDatosNotaEntrada"));
            // Agregando el idNotaEntrada
            formData.append('idNotaEntrada',inputIdNotaEntrada.value);
            
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: formData,
                url: form_action,
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (res) {
                    $('#TbreporteNotasEntrada').DataTable().ajax.reload();
                    $('#modalNuevaNota').modal('hide');
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
        
        function reiniciartotales(){
            sumaSubtotal = 0;
            sumaIva = 0;
            sumaTotal = 0;
            inputMontoNotaTotal.value = 0;
        }

        function ocultarElementos(ocultar){
            if (ocultar) {
                document.querySelector("#contenedorFooterMdl").style.display = "none";
                document.querySelector("#contenedorFrm").style.display = "none";
                document.querySelector("#contenedorTotales").style.display = "none";
                document.querySelector("#btnCerrarHeadMdl").style.display = "";
                $(".eliminarProducto").addClass("hidden");
            }else{
                document.querySelector("#contenedorFooterMdl").style.display = "flex";
                document.querySelector("#contenedorFrm").style.display = "";
                document.querySelector("#btnCerrarHeadMdl").style.display = "none";
                document.querySelector("#contenedorTotales").style.display = "";
                $(".eliminarProducto").removeClass("hidden");
            }
        }

        function limpiarFrmFechas(){
            ocultarElementosFechas();
            $('#frmFechas')[0].reset();
        }

        function ocultarElementosFechas(){
            document.querySelector("#contenedorFechaEntrega").style.display = "none";
            document.querySelector("#contenedorFechaPagoProgramada_anticipo").style.display = "none";
            document.querySelector("#contenedorFechaPagoProgramada_total").style.display = "none";
            document.querySelector("#contenedorFechaRealPago_anticipo").style.display = "none";
            document.querySelector("#contenedorFechaRealPago_total").style.display = "none";
            
            document.querySelector("#fechaEntrega").removeAttribute("required");
            document.querySelector("#fechaPagoProgramada_anticipo").removeAttribute("required");
            document.querySelector("#fechaPagoProgramada_total").removeAttribute("required");
            document.querySelector("#fechaRealPago_anticipo").removeAttribute("required");
            document.querySelector("#fechaRealPago_total").removeAttribute("required");
        }

        function guardarFechasNotaEntrada(event){
            event.preventDefault();

            // Obtenemos la operacion a realizar create ó update
            var form_action = $("#frmFechas").attr("action");
            // Guardamos el form con los input file para subir archivos
            var formData = new FormData(document.getElementById("frmFechas"));
            
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: formData,
                url: form_action,
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (res) {
                    $('#TbreporteNotasEntrada').DataTable().ajax.reload();
                    $('#modalFechas').modal('hide');
                },
                error: function (err) {
                    console.log(err);
                }
            }); 
        }

        function guardarPDFyXML(event){
            event.preventDefault();

            // Obtenemos la operacion a realizar create ó update
            var form_action = $("#frmArchivosPDFyXML").attr("action");
            // Guardamos el form con los input file para subir archivos
            var formData = new FormData(document.getElementById("frmArchivosPDFyXML"));

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: formData,
                url: form_action,
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (res) {
                    console.log(res)
                    if (res.validacion == "ok") {
                        $('#TbreporteNotasEntrada').DataTable().ajax.reload();
                        $('#modalArchivosFactura').modal('hide');
                    } else {
                        document.querySelector("#archivoPdf").value="";
                        document.querySelector("#archivoXml").value="";
                        var mensaje = "";
                        res.validacion.forEach(element => {
                            mensaje += `•${element}<br>`;
                        });
                        contenedorMsjError.innerHTML = mensaje;
                        contenedorMsjError.style.display = "";
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            }); 
        }

        function limpiarFrmArchivos(){
            $('#frmArchivosPDFyXML')[0].reset();
            contenedorMsjError.style.display = "none";
        }
        
        function guardarArchivosEvidencia(event){
            event.preventDefault();

            // Obtenemos la operacion a realizar create ó update
            var form_action = $("#frmArchivosEvidencia").attr("action");
            // Guardamos el form con los input file para subir archivos
            var formData = new FormData(document.getElementById("frmArchivosEvidencia"));
            
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: formData,
                url: form_action,
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (res) {
                    $('#TbreporteNotasEntrada').DataTable().ajax.reload();
                    $('#modalArchivosEvidencia').modal('hide');
                },
                error: function (err) {
                    console.log(err);
                }
            }); 
        }

        function limpiarFrmArchivosEvidencia(){
            $('#frmArchivosEvidencia')[0].reset();
        }

        function guardarMontosComplementos(event){
            event.preventDefault();

            // Obtenemos la operacion a realizar create ó update
            var form_action = $("#frmMontosComplementos").attr("action");
            // Guardamos el form con los input file para subir archivos
            var formData = new FormData(document.getElementById("frmMontosComplementos"));
            
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: formData,
                url: form_action,
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (res) {
                    $('#TbreporteNotasEntrada').DataTable().ajax.reload();
                    $('#modalMontosComplementos').modal('hide');
                },
                error: function (err) {
                    console.log(err);
                }
            }); 
        }

        function limpiarFrmMontosComplementos(){
            $('#frmMontosComplementos')[0].reset();
            document.querySelector("#contenedorMontoComplemento_anticipo").style.display = "none";
            document.querySelector("#contenedorMontoComplemento_total").style.display = "none";
            
            document.querySelector("#montoComplemento_anticipo").removeAttribute("required");
            document.querySelector("#montoComplemento_total").removeAttribute("required");
        }

        function guardarDetallesEntrega(event){
            event.preventDefault();

            // Obtenemos la operacion a realizar create ó update
            var form_action = $("#frmDetallesEntrega").attr("action");
            // Guardamos el form con los input file para subir archivos
            var formData = new FormData(document.getElementById("frmDetallesEntrega"));
            
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: formData,
                url: form_action,
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (res) {
                    $('#TbreporteNotasEntrada').DataTable().ajax.reload();
                    $('#modalDetallesEntrega').modal('hide');
                },
                error: function (err) {
                    console.log(err);
                }
            }); 
        }

        function limpiarFrmDetallesEntrega(){
            $('#frmDetallesEntrega')[0].reset();
            document.querySelector("#contenedorGuardarDetallesEntrega").style.display = "";
            document.querySelector("#contenedorVerDetallesEntrega").style.display = "none";
            document.querySelector("#footerMdl").style.display = "flex";
            $("#contenedorImg").html("");
        }
    
    });
    function formDataToObjet(formData){
        var object = {};
        formData.forEach(function(value, key){
            object[key] = value;
        });

        return object;
    }

    function validarPdf(obj) {
        var uploadFile = obj.files[0];        
        if (!(/\.(pdf)$/i).test(uploadFile.name)) {
            alert('Error: Solo archivos PDF');
            obj.value = "";
        }
    }    

    function validarXml(obj) {
        console.log(obj)
        var uploadFile = obj.files[0];        
        if (!(/\.(xml)$/i).test(uploadFile.name)) {
            alert('Error: Solo archivos XML');
            obj.value = "";
        }
    }

    function formatoNumerico(numero){
        return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(numero);
    }

</script>

@stop