@extends('principal')
@section('contenido')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $(".cargar").click();
    $("#idCuenta").select2({width:'100%'});
    $("#idFactura").select2({width:'100%'});
    $("#idFacturaConEquipos").select2({width:'100%'});
    $("#idMarcaRefa").select2({width:'100%'});
    $("#skuServicio").select2({width:'100%'});
    $("#skuPartesVenta").select2({width:'100%'});    
});
</script>

{{Form::open(['route' => 'editarOrdenCompra','id' => 'formularioOrden','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Editar orden de compra
                <!-- <small> *Campos Requeridos</small> -->
            </h1>
        </div>
        <div class="panel-body">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="cargar" class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#orden"
                        role="tab" aria-controls="pills-home" aria-selected="true">Datos de la orden</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#producto"
                        role="tab" aria-controls="pills-home" aria-selected="true">Datos de productos</a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade" id="orden" role="tabpanel" aria-labelledby="pills-home-tab">
                    <!-- DATOS DE ORDEN -->
                    <h4>Datos de la orden</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    {{Form::hidden('idOrden',($consulta->idOrden),['class' => 'form-control', 'readonly'=> 'true','id'=>'idOrden'])}}
                                    <div class="sub-title">Registrado por: </div>
                                    <div>
                                        <input type='hidden' name='idu' id='idu' value="{{$registradoPor->idu}}">
                                        <input type="text" name="registradoPor" class="form-control" readonly="true" value="{{$registradoPor->nombreUsuario}} {{$registradoPor->aPaterno}} {{$registradoPor->aMaterno}}">
                                    </div>
                                </div>
                                <div class="col-md-6">                                    
                                    <div class="sub-title">*Fecha de la orden: </div>
                                    <div>
                                        {{Form::date('fechaOrden',$consulta->fechaOrden,['class' => 'form-control'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="sub-title">Empresa: </div>
                            <div>
                                @if($consulta->nombreEmpresa == 'CYM')
                                    CYM {{Form::radio('nombreEmpresa','CYM',true)}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    GCM {{Form::radio('nombreEmpresa','GCM')}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    SURJA {{Form::radio('nombreEmpresa','SURJA')}}
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                   <br>
                                    Pedro Ocegueda J. {{Form::radio('nombreEmpresa','POJ')}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    Yaneth Abilene G. A. {{Form::radio('nombreEmpresa','YAGA')}}
                                @elseif($consulta->nombreEmpresa == 'GCM')
                                    CYM {{Form::radio('nombreEmpresa','CYM')}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    GCM {{Form::radio('nombreEmpresa','GCM',true)}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    SURJA {{Form::radio('nombreEmpresa','SURJA')}}
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                   <br>
                                    Pedro Ocegueda J. {{Form::radio('nombreEmpresa','POJ')}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    Yaneth Abilene G. A. {{Form::radio('nombreEmpresa','YAGA')}}
                                @elseif($consulta->nombreEmpresa == 'SURJA')
                                    CYM {{Form::radio('nombreEmpresa','CYM')}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    GCM {{Form::radio('nombreEmpresa','GCM')}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    SURJA {{Form::radio('nombreEmpresa','SURJA',true)}}
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                   <br>
                                    Pedro Ocegueda J. {{Form::radio('nombreEmpresa','POJ')}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    Yaneth Abilene G. A. {{Form::radio('nombreEmpresa','YAGA')}}
                                @elseif($consulta->nombreEmpresa == 'POJ')
                                    CYM {{Form::radio('nombreEmpresa','CYM')}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    GCM {{Form::radio('nombreEmpresa','GCM')}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    SURJA {{Form::radio('nombreEmpresa','SURJA')}}
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                   <br>
                                    Pedro Ocegueda J. {{Form::radio('nombreEmpresa','POJ',true)}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    Yaneth Abilene G. A. {{Form::radio('nombreEmpresa','YAGA')}}
                                @elseif($consulta->nombreEmpresa == 'YAGA')
                                    CYM {{Form::radio('nombreEmpresa','CYM')}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    GCM {{Form::radio('nombreEmpresa','GCM')}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    SURJA {{Form::radio('nombreEmpresa','SURJA')}}
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                   <br>
                                    Pedro Ocegueda J. {{Form::radio('nombreEmpresa','POJ')}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    Yaneth Abilene G. A. {{Form::radio('nombreEmpresa','YAGA',true)}}
							    
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">*Cuenta:</div>
                                    <div>
                                        <select name="idCuenta" id="idCuenta" class="form-control">
                                            <option value="{{$cuentaSel->idCuenta}}">{{$numeroCuenta}} - {{$nomCuenta}}</option>
                                            @foreach($consultaCuenta as $cuentas)
                                            <option value="{{$cuentas->idCuenta}}">{{$cuentas->numeroCu}} - {{$cuentas->nombreCuenta}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">*Uso del CFDI:</div>
                                    <select name="cfdi" id="cfdi" class="form-control">
                                        <option value="{{$consulta->cfdi}}">{{$consulta->cfdi}}</option>
                                        <option value="G01 Adquisición de mercancías">G01 Adquisición de mercancías</option>
                                        <option value="G02 Devoluciones descuentos o bonificaciones"> G02 Devoluciones descuentos o bonificaciones</option>
                                        <option value="G03 Gastos en general">G03 Gastos en general</option>
                                        <option value="I03 Equipo de transporte">I03 Equipo de transporte</option>
                                        <option value="I04 Equipo de cómputo y accesorios">I04 Equipo de cómputo y accesorios</option>
                                        <option value="I05 Dados, troqueles, moldes y herramienta">I05 Dados, troqueles, moldes y herramienta</option>
                                        <option value="I08 Otra maquinaria y equipo ">I08 Otra maquinaria y equipo</option>
                                        <option value="P01 Por definir">P01 Por definir</option>
                                    </select>
                                </div>
                            </div>
                            <div class="sub-title">*Proveedor: </div>
                            <div>
                                <select name="idProveedor" id="idProveedor" class="form-control">
                                    <option value="{{$proveedorSel->idProveedor}}">{{$nomProveedor}}</option>
                                    @foreach($consultaProveedor as $proveedor)
                                    <option value="{{$proveedor->idProveedor}}">{{$proveedor->razonSocialProv}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sub-title">*Vigencia cotización de proveedor (días): </div>
                            <div class="">
                                {{Form::text('vigenciaCotizacion',$consulta->vigenciaCotizacion,['class' => 'form-control', 'placeholder'=>'Ejemplo: 10'])}}
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="sub-title">*Cuenta a pagar: </div>
                                    <div>
                                        <select name="idCuentaProv" id="idCuentaProv" class="form-control">
                                            <option value="{{$cuentaProvSel->idCuentaProv}}">{{$numCuentaProv}} - {{$cuentaProvSel->bancoProv}} - {{$cuentaProvSel->tipoMonedaProv}}</option>
                                            @foreach($consultaCuentaProv as $cuenta)
                                            <option value="{{$cuenta->idcuentaProv}}">{{$cuenta->numeroCuentaProv}} - {{$cuenta->bancoProv}} - {{$cuenta->tipoMonedaProv}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="sub-title">*Forma de Pago: </div>
                                    <div>
                                        <select name="formaPago" id="formaPago" class="form-control">
                                            <option value="{{$consulta->formaPago}}">{{$consulta->formaPago}}</option>
                                            <option value="01 Efectivo">01 Efectivo</option>
                                            <option value="02 Cheque nominativo">02 Cheque nominativo</option>
                                            <option value="03 Transferencia electrónica">03 Transferencia electrónica</option>
                                            <option value="04 Tarjeta de crédito">04 Tarjeta de crédito</option>
                                            <option value="05 Monedero electrónico">05 Monedero electrónico</option>
                                            <option value="06 Dinero electrónico">06 Dinero electrónico</option>
                                            <option value="08 Vales de despensa">08 Vales de despensa</option>
                                            <option value="28  Tarjeta de debito">28  Tarjeta de debito</option>
                                            <option value="29 Tarjeta de servicios"> 29 Tarjeta de servicios</option>
                                            <option value="99 Por definir"> 99 Por definir</option>
                                            <option value="NA  No aplica"> NA  No aplica</option>
                                            <option value="30 Aplicación de anticipos"> 30 Aplicación de anticipos</option>
                                            <option value="14 Pago por consignación"> 14 Pago por consignación</option>
                                            <option value="15 Condonación"> 15 Condonación</option>
                                            <option value="17 Compensación"> 17 Compensación</option>
                                            <option value="27 A satisfacción del acreedor"> 27 A satisfacción del acreedor</option>
                                            <option value="25 Remisión de deuda"> 25 Remisión de deuda</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="sub-title">Notas de la orden: </div>
                            <div>
                                {{Form::textArea('notasOrden',$consulta->notasOrden,['class' => 'form-control','rows'=>'3'])}}
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Teléfono adicional: </div>
                                    {{Form::text('telAdicional',$consulta->telAdicional,['class' => 'form-control'])}}
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Correo adicioal: </div>
                                    {{Form::email('correoAdicional',$consulta->correoAdicional,['class' => 'form-control'])}}
                                </div>
                            </div>
                            <div>
                                <b>Visualizar RFC del proveedor en PDF de la OC:</b>
                                @if($consulta->mostrarRfc == 'on')
                                    <input type="checkbox" name="mostrarRfc" id="" checked>
                                @else
                                    <input type="checkbox" name="mostrarRfc" id="">
                                @endif
                            </div>
                            <div>
                                <b>Visualizar TAX ID en PDF de la OC:</b>
                                @if($consulta->mostrarTaxId == 'on')
                                    <input type="checkbox" name="mostrarTaxId" id="" checked>
                                @else
                                    <input type="checkbox" name="mostrarTaxId" id="">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <div class="sub-title">Incoterm: </div>
                                <select name="incoterm" id="" class="form-control">
                                    <option value="{{$consulta->incoterm}}">{{$consulta->incoterm}}</option>
                                    <option value="">Seleccionar opción</option>
                                    <option value="FOB - FREE ON BOARD">FOB - FREE ON BOARD</option>
                                    <option value="EXW - EX WORKS">EXW - EX WORKS</option>
                                    <option value="DDP - DELIVERY DUTY PAID">DDP - DELIVERY DUTY PAID</option>
                                </select>
                                <div class="sub-title">Shipping text: </div>
                                <div>
                                    {{Form::textArea('detalleIncoterm',$consulta->detalleIncoterm,['class' => 'form-control','rows'=>'2'])}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="sub-title">Moneda: </div>
                                    <div>
                                        @if($consulta->moneda=='MXN')
                                            @if($cuantosProductos==0)
                                                <input type="radio" name="monedaOrden" value="MXN" checked> MXN &nbsp;&nbsp;&nbsp;
                                                <input type="radio" name="monedaOrden" value="USD"> USD
                                            @else
                                                <input type="radio" name="" value="MXN" checked disabled> MXN &nbsp;&nbsp;&nbsp;
                                                <input type="radio" name="" value="USD" disabled> USD
                                                <input type="hidden" name="monedaOrden" value="MXN">
                                            @endif
                                        @endif
                                        @if($consulta->moneda=='USD')
                                            @if($cuantosProductos==0)
                                                <input type="radio" name="monedaOrden" value="MXN" > MXN &nbsp;&nbsp;&nbsp;
                                                <input type="radio" name="monedaOrden" value="USD" checked > USD
                                            @else
                                                <input type="radio" name="" value="MXN" disabled> MXN &nbsp;&nbsp;&nbsp;
                                                <input type="radio" name="" value="USD" checked disabled> USD
                                                <input type="hidden" name="monedaOrden" value="USD">
                                            @endif
                                        @endif
                                        @if($consulta->moneda=='')
                                            {{Form::radio('monedaOrden','MXN')}} MXN&nbsp;&nbsp;&nbsp;
                                            {{Form::radio('monedaOrden','USD')}} USD
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="sub-title">Cambio del dolar: </div>
                                    <div>
                                        {{Form::text('cambioDolarOrden',$consulta->cambioDolar,['class' => 'form-control', 'placeholder'=>'Ejemplo: 20.20'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="sub-title">Formato: </div>
                                    <div>
                                        @if($consulta->formato=='México')
                                            <div>
                                                <div>
                                                    {{Form::radio('formato','México',true)}} México
                                                </div>
                                                <div>
                                                    {{Form::radio('formato','CMG USA')}} CMG USA
                                                </div>
                                            </div>
                                        @endif
                                        @if($consulta->formato=='CMG USA')
                                            <div>
                                                <div>
                                                    {{Form::radio('formato','México')}} México
                                                </div>
                                                <div>
                                                    {{Form::radio('formato','CMG USA', true)}} 'CMG USA
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div>
                                        <div class="sub-title">Payment terms: </div>
                                        <select name="paymentTerms" id="paymentSelect" class="form-control">
                                        <option value="{{$consulta->paymentTerms}}">{{$consulta->paymentTerms}}</option>
                                            <option value="">Seleccionar opción</option>
                                            <option value="WIRE TRANSFER">WIRE TRANSFER</option>
                                            <option value="DEBIT-CREDIT CARD">DEBIT-CREDIT CARD</option>
                                            <option value="CHECK">CHECK</option>
                                            <option value="CREDIT">CREDIT</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="sub-title">Método de pago: </div>
                                    <div>
                                        @if($consulta->tipoPago=='PUE')
                                        {{Form::radio('tipoPago','PUE',true, ['id'=>'contado'])}} PUE&nbsp;&nbsp;&nbsp;
                                        {{Form::radio('tipoPago','PPD', false, ['id'=>'credito'])}} PPD
                                        @endif
                                        @if($consulta->tipoPago=='PPD')
                                        {{Form::radio('tipoPago','PUE',false, ['id'=>'contado'])}} PUE&nbsp;&nbsp;&nbsp;
                                        {{Form::radio('tipoPago','PPD', true, ['id'=>'credito'])}} PPD
                                        @endif
                                    </div>
                                </div>                                
                                <div class="col-sm-7">
                                    @if($consulta->tipoPago=='PPD')
                                        <div style="display:block;" id="mostrarPlazo">
                                            <div class="sub-title">Plazo en días:</div>
                                            <div>
                                                <select name="diasPlazo" id="diasPlazo" class="form-control">
                                                    <option value="{{$consulta->diasPlazo}}">{{$consulta->diasPlazo}}</option>
                                                    <option value="7">7</option>
                                                    <option value="14">14</option>
                                                    <option value="21">21</option>
                                                    <option value="28">28</option>
                                                    <option value="30">30</option>
                                                    <option value="45">45</option>
                                                    <option value="60">60</option>
                                                    <option value="90">90</option>
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    <div style="display:none;" id="mostrarPlazo">
                                        <div class="sub-title">Plazo en dias:</div>
                                        <div>
                                            <select name="diasPlazo2" id="diasPlazo" class="form-control">
                                                <option value="">Selecciona un plazo</option>
                                                <option value="7">7</option>
                                                <option value="14">14</option>
                                                <option value="21">21</option>
                                                <option value="28">28</option>
                                                <option value="30">30</option>
                                                <option value="45">45</option>
                                                <option value="60">60</option>
                                                <option value="90">90</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>                                
                            </div>                            
                            <div class="sub-title">*Cotización de proveedor: </div>
                            <div class="row">
                                <div class="col-md-10">
                                    @if($consulta->cotizacionProveedor!='')
                                    {{Form::file('cotizacionProveedor',['id'=>'cotizacionProveedor','class' => 'form-control', 'onchange'=>'validarCotizacion(this);'])}}
                                </div>
                                <div class="col-md-2">
                                    <a target="_blank" href="{{asset('archivos/'.$consulta->cotizacionProveedor)}}">
                                        @if(strpos($consulta->cotizacionProveedor,'pdf'))
                                            <img src="{{asset('img/iconpdfdown.png')}}" height=33 width=33>
                                        @else
                                            <!-- <img src="{{asset('/iconpdfdown.png')}}" height=80 width=80> -->
                                            <img src="{{asset ('public/archivos/'.$consulta->cotizacionProveedor)}}" height=33 width=33>
                                        @endif
                                    </a>
                                    @endif
                                </div>
                            </div>
                            <div style="" id="" class="row">
                                <div class="col-sm-8">
                                    <div class="sub-title">*Vendor Quote Nr. / Reference: </div>
                                    <div>
                                        {{Form::text('vendorQuote',$consulta->vendorQuote,['class' => 'form-control','id'=>'vendorQuote'])}}
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="sub-title">*Due Date: </div>
                                    <div>
                                        {{Form::date('dueDate',$consulta->dueDate,['class' => 'form-control'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="sub-title">Domicilio de entrega: </div>
                            <div>
                                {{Form::textArea('domicilioEntrega',$consulta->domicilioEntrega,['class' => 'form-control','rows'=>'3','id'=>'domicilioEntrega'])}}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="sub-title">Folio: </div>
                            <div id="cambiarCodigo">
                                {{Form::text('codigoOrden',$consulta->codigoOrden,['class' => 'form-control', 'readonly'])}}
                            </div>
                            <!-- <div class="sub-title">*Estatus proveedor: </div>
                            <div>
                                <select name="estatusProveedor" id="estatusProveedor" class="form-control" readonly>
                                    <option value="{{$consulta->estatusProveedor}}">{{$consulta->estatusProveedor}}</option> -->
                                    <!-- <option value="Pendiente de aceptación, sin fecha entrega">Pendiente de aceptación, sin fecha entrega</option>
                                    <option value="Pendiente de aceptación, con fecha entrega">Pendiente de aceptación, con fecha entrega</option>
                                    <option value="Aceptado con fecha de entrega">Aceptado con fecha de entrega</option>                                    
                                    <option value="No aceptado">No aceptado</option> -->
                                <!-- </select>
                            </div> -->
                            <!-- <div class="sub-title">Fecha de entrega:</div>
                            <div>
                                {{Form::date('fechaEntregaProveedor', $consulta->fechaEntregaProveedor,['class'=>'form-control','readonly'])}}
                            </div>
                            <div class="sub-title">*Estatus de la orden: </div>
                            <div>
                                @if($consulta->estatusProveedor == 'Aceptado con fecha de entrega')
                                    <select name="estatus" id="nuevoEstatusOrden" class="form-control">
                                        <option value="{{$consulta->estatus}}">{{$consulta->estatus}}</option>
                                        <option value="Cancelado">Cancelado</option>
                                        <option value="Garantia">Garantia</option>
                                        @if($consulta->estatus == 'Cancelado')
                                            <option value="Pagado">Pagado</option>
                                            <option value="Pago programado">Pago programado</option>
                                            <option value="Pendiente por pagar">Pendiente por pagar</option>
                                            <option value="Sin programar pago">Sin programar pago</option>
                                        @endif
                                    </select>
                                @else
                                    <select name="estatus" id="nuevoEstatusOrden" class="form-control" readonly>
                                        <option value="{{$consulta->estatus}}">{{$consulta->estatus}}</option>
                                    </select>
                                @endif
                            </div> -->
                            <div style="" id="">
                                <div class="sub-title">*Detalle metodo de envío:</div>
                                <select name="detalleMetodoEnvio" id="detalleMetodoEnvio" class="form-control">
                                    <option value="{{$consulta->detalleMetodoEnvio}}">{{$consulta->detalleMetodoEnvio}}</option>
                                    <option value="">Seleccionar</option>
                                    <option value="OUR UPS">OUR UPS</option>
                                    <option value="PPD & ADD">PPD & ADD</option>
                                </select>
                            </div>
                            <div style='' id="">
                                <div class="sub-title">*Bill To:</div>
                                {{Form::textArea('billTo',$consulta->billTo,['class' => 'form-control','rows'=>'4','id'=>'billTo'])}}
                            </div>

                            <!-- Esta validacion es para asegurar que no se programe el pago hasta que el proveedor haya aceptado la orden con fecha de entrega y haya subido pdf y xml -->
                            @if($consulta->estatusProveedor == 'Aceptado con fecha de entrega' && $consulta->fechaEntregaProveedor != '' && $consulta->estatus != 'Sin programar pago')
                                <div class="sub-title">Fecha programada de pago:</div>
                                <div style="margin-bottom:4px">
                                    {{Form::date('fechaProgPago',$consulta->fechaProgPago,['class' => 'form-control','id'=>'fechaProgramadaPago'])}}
                                </div>
                            @endif

                            @if($consulta->estatus == 'Pago programado')
                                <div class="alert alert-success" role="alert">
                                    <b>Fecha real de deposito: </b>
                                    <div>                                
                                        {{Form::date('fechaDeposito',$consulta->fechaDeposito,['class' => 'form-control','id'=>'fechaDePago'])}}                                
                                    </div>
                                    <div class="sub-title">Subir evidencia de transferencia: </div>
                                    <div>
                                        {{ Form::file('evidenciaTransferencia',['id'=>'evidenciaTransferencia','class' => 'form-control','onchange'=>'validarEvidenciaT(this);'])}}
                                    </div>                                    
                                    <div class="sub-title">Moneda de transferencia:</div>
                                    <div>
                                        {{Form::radio('monedaComplementoPago','MXN',true)}} MXN &nbsp;&nbsp;&nbsp;
                                        {{Form::radio('monedaComplementoPago','USD')}} USD
                                    </div>
                                    <div class="sub-title">Cambio del Dolar: </div>
                                    <div>
                                        <input type="text" name="cambiodolarComplemento" id="cambiodolarComplemento" class="form-control">
                                    </div>
                                    <div class="sub-title">Monto total de pago: </div>
                                    <div>
                                        <input type="text" name="montoComplementoPago" id="montoComplementoPago" class="form-control">
                                    </div>
                                </div>                            
                            @endif
                            @if($consulta->estatus == 'Pagado con complementos de pago' || $consulta->estatus == 'Pagado')
                                <div class="alert alert-success" role="alert">
                                    <center><b>Información final del pago </b></center>
                                    <div>
                                        Moneda de transferencia:
                                        @if($consulta->monedaComplementoPago == 'MXN')
                                            {{Form::radio('monedaComplementoPago','MXN',true)}} MXN &nbsp;&nbsp;&nbsp;
                                            {{Form::radio('monedaComplementoPago','USD')}} USD
                                        @else
                                            {{Form::radio('monedaComplementoPago','MXN')}} MXN &nbsp;&nbsp;&nbsp;
                                            {{Form::radio('monedaComplementoPago','USD',true)}} USD
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div>
                                                Monto final:
                                            </div>
                                            <div>
                                                <input type="text" name="montoComplementoPago" id="montoComplementoPago" class="form-control" value="{{$consulta->montoComplementoPago}}">
                                            </div>
                                        </div>
                                        <div>
                                            @if($consulta->evidenciaTransferencia =='Sin archivo' || $consulta->evidenciaTransferencia =='')
                                                <div>
                                                    <h5 align="center"><span class="label label-warning">Sin archivo</span></h5>
                                                    <input type="hidden" value="0" id="Asubido">
                                                </div>
                                            @else
                                                <input type="hidden" value="1" id="Asubido">
                                                <center>
                                                    <a target="_blank" href="{{asset ('public/archivos/'.$consulta->evidenciaTransferencia)}}">
                                                        @if(strpos($consulta->evidenciaTransferencia,'pdf'))
                                                            <i class="fa fa-file-pdf-o fa-5x" aria-hidden="true" style="color: #750404;background-color: #fff;border-color: #4d42ff59;"></i>
                                                        @else
                                                            <img src="{{asset ('public/archivos/'.$consulta->evidenciaTransferencia)}}" height=80 width=80>
                                                        @endif
                                                    </a>
                                                </center>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div>
                                                Cambio del dolar:
                                            </div>
                                            <div>
                                                <input type="text" name="" id="cambiodolarComplemento" class="form-control" value="{{$consulta->cambioComplementoPago}}">
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                            @endif                            
                        </div>
                    </div>
                </div>
                
                <!-- TAB DE PRODUCTOS -->
                <div class="tab-pane fade" id="producto" role="tabpanel" aria-labelledby="pills-home-tab">
                    <h4>Agregar productos</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="sub-title">Seleccionar tipo de entrada: </div>
                            <div>
                                No. De solicitud {{Form::radio('tipoEntrada','1')}}
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                Compra única {{Form::radio('tipoEntrada','2')}}
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                {{-- Equipos  --}}
                                {{Form::radio('tipoEntrada','3', true, ['hidden' => 'hidden'])}}
                            </div>
                            <div id="divServicios" style="display:none">
                                <div class="sub-title">Seleccionar servicio: </div>
                                <div>
                                    <select name='idFactura' id='idFactura' class="form-control">
                                        <option value="">Seleccionar servicio</option>
                                        @foreach($consultaFactura as $consultaFactura)
                                            <option value='{{$consultaFactura->idFactura}}'>{{$consultaFactura->idServicios}} - {{$consultaFactura->cliente}} - {{$consultaFactura->sucursal}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sub-title">Seleccionar SKU: </div>
                                <div>
                                    <select name='skuServicio' id='skuServicio' class="form-control">
                                        <option value="">Seleccionar sku</option>                                        
                                    </select>
                                </div>
                            </div>
                            <div id="divExterno" style="display:none">
                                <div class="sub-title">Seleccionar marca: </div>
                                <div>
                                    <select name='idMarcaRefa' id='idMarcaRefa' class="form-control">
                                        <option value="">Seleccionar marca</option>
                                        @foreach($consultaMarcaPartesVenta as $consultaMpV)
                                            <option value='{{$consultaMpV->idMarcaRefa}}'>{{$consultaMpV->marcaRefaccion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sub-title">Seleccionar SKU: </div>
                                <div>
                                    <select name='skuPartesVenta' id='skuPartesVenta' class="form-control">
                                        <option value="">Seleccionar sku</option>                                        
                                    </select>
                                </div>
                            </div>
                            <div id="divEquipos" style="display:none">
                                <div class="sub-title">Seleccionar servicio: </div>
                                <div>
                                    <select name='idFacturaConEquipos' id='idFacturaConEquipos' class="form-control">
                                        <option value="">Seleccionar servicio</option>
                                        @foreach($consultaFacturaConEquipos as $elementosConsulta)
                                            <option value='{{$elementosConsulta->idFactura}}'>{{$elementosConsulta->idServicios}} - {{$elementosConsulta->cliente}} - {{$elementosConsulta->sucursal}}</option>
                                        @endforeach                                        
                                    </select>
                                </div>
                                <div class="sub-title">Seleccionar Equipo: </div>
                                <div>
                                    <select name='idEquipo' id='idEquipo' class="form-control">
                                        <option value="">Seleccionar equipo</option>                                        
                                    </select>
                                </div>
                                <div class="sub-title">Seleccionar Pieza: </div>
                                <div>
                                    <select name='idParte' id='idParte' class="form-control">
                                        <option value="">Seleccionar pieza</option>                                        
                                    </select>
                                </div>
                            </div>
                            <div id="infoSku">
                                <div class="sub-title">SKU:</div>
                                <div>
                                    <input type="hidden" class="form-control" id="idPartesVentaDes"value="">
                                    <input type="text" class="form-control"  id="codigoDes" readonly>
                                </div>
                                <div class="sub-title">Descripción: </div>
                                <div>                                    
                                    <textarea  id="nombreRefaccionDes" cols="3" class="form-control" readonly></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="sub-title">Precio:</div>
                                    <div>
                                        {{Form::text('precioOrden', old('precioOrden'),['class'=>'form-control','id'=>'precioOrden','onkeyup'=>'calcularTotal()','onblur'=>'calcularTotal()'])}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="sub-title">Cantidad: </div>
                                    <div>                            
                                        {{Form::text('cantidadOrden', old('cantidadOrden'),['class'=>'form-control','id'=>'cantidadOrden','onkeyup'=>'calcularTotal()','onblur'=>'calcularTotal()'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="sub-title">IVA (%): </div>
                                    <div>
                                        {{Form::text('ivaOrden', '0',['class'=>'form-control', 'id'=>'ivaOrden','onkeyup'=>'calcularTotal()','onblur'=>'calcularTotal()'])}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="sub-title">ISR (%): </div>
                                    <div>
                                        {{Form::text('isrOrden', '0',['class'=>'form-control', 'id'=>'isrOrden','onkeyup'=>'calcularTotal()','onblur'=>'calcularTotal()'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="sub-title">Retención (%):</div>
                                    <div>
                                        {{Form::text('retencion', '0',['class'=>'form-control', 'id'=>'retencion','onkeyup'=>'calcularTotal()','onblur'=>'calcularTotal()'])}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="sub-title">TOTAL:</div>
                                    <div>
                                        {{Form::text('totalCalculo', old('totalCalculo'),['class'=>'form-control', 'id'=>'totalCalculo', 'readonly'])}}
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="col-md-4">
                            <div>
                                <div class="sub-title">*Vendor part number:</div>
                                <div>
                                    {{Form::text('vendorPartNumber', old('vendorPartNumber'),['class'=>'form-control','id'=>'vendorPartNumber'])}}
                                </div>
                            </div>
                            <div class="">
                                <div class="sub-title">Notas del producto:</div>
                                <div>
                                    {{Form::textArea('notasProducto', old('notasProducto'),['class'=>'form-control','id'=>'notasProducto','rows'=>'5'])}}
                                </div>
                            </div>
                            <div class="" style="text-align:right">
                                <div class="sub-title" id="cambio"><br>
                                    @if($consulta->estatus!='Pagado' && $consulta->estatus!='Pagado con complementos de pago')                                    
                                        <button type="button" class="btn btn-success" id="agregarProducto">
                                            <span class="glyphicon glyphicon-plus-sign"></span> Agregar producto
                                        </button>                                    
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div><br><br>
                    <div id="reporteProductos">
                        @if($cuantosProductos==0)
                        <div align="center" class="alert alert-info" role="alert">¡No tiene productos asignados!</div>
                        @else
                            @include('reporteOrdenAgregada')
                        @endif
                    </div>                    
                </div>

            </div>
        </div>
        @if($consulta->estatus!='Pagado' && $consulta->estatus!='Pagado con complementos de pago')
            <center>
                {{Form::submit('Guardar',['class' => 'btn  btn-success', 'id' => 'guardar'])}}
            </center><br>
        @endif
    </div>
</div>
<div id="modalEditar"></div>

<script>
    // funcion para limpiar todos los datos de los input que se eligen
    function limpiarDatosSku(){
        document.getElementById("cantidadOrden").value = "";
        document.getElementById("precioOrden").value = "";
        document.getElementById("totalCalculo").value = "";
        document.getElementById("ivaOrden").value = "0";
        document.getElementById("isrOrden").value = "0";
        document.getElementById("retencion").value = "0";
        document.getElementById("idPartesVentaDes").value = "";
        document.getElementById("codigoDes").value = "";
        document.getElementById("nombreRefaccionDes").value = "";
        document.getElementById("notasProducto").value = "";
        document.getElementById("vendorPartNumber").value = "";
        // limpiando y reiniciando los combos con funcion select2
        $("#idFactura").val('').trigger('change')
        $("#skuServicio").val('').trigger('change')
        // document.getElementById("skuServicio").value = "";
        $("#idMarcaRefa").val('').trigger('change')
        document.getElementById("skuPartesVenta").value = "";
        $("#idFacturaConEquipos").val('').trigger('change')
        $("#idEquipo").val('')
        $("#idParte").val('')
    }

    // funcion que hace mostrar un mesnaje en combos dinamicos mientras carga los datos
    function mensajeCargandoCombo(idElemento){
        $('#'+idElemento+'').val('')
        // $('#'+idElemento+'').attr('disabled',true)
        var select = document.getElementById(idElemento);
        var opt = document.createElement('option');
        opt.setAttribute('value','');
        opt.innerHTML='cargando...';
        opt.setAttribute('selected','selected');
        select.appendChild(opt);
    }

    function calcularTotal() {
        var cantidadOrden = $("#cantidadOrden").val();
        var precioOrden = $("#precioOrden").val();
        var ivaOrden = $("#ivaOrden").val();
        var isrOrden = $("#isrOrden").val();
        var retencion = $("#retencion").val();
        var subtotal=precioOrden*cantidadOrden;
        var iva=ivaOrden/100*subtotal;
        var isr=isrOrden/100*subtotal;
            retencion=retencion/100*(subtotal+iva+isr);
        var total=(subtotal+iva+isr)-retencion;
        totalFormato=total.toFixed(2);
        if(cantidadOrden >0 &&precioOrden >0){
            // alert("El total es: " + total);
            document.getElementById("totalCalculo").value = totalFormato;
        }
    }

    $("#credito").click(function(){
        document.getElementById('mostrarPlazo').style.display = 'block';
    })
    $("#contado").click(function(){
        document.getElementById('mostrarPlazo').style.display = 'none';
    })

    $("#idProveedor").change(function(){
        var idProveedor = $("#idProveedor").val();
        $("#idCuentaProv").load('{{url('comboCuentaProv')}}' + '?r=' + Date.now() + '&idProveedor=' + idProveedor);
    });
    $("#idProveedor").change(function(){
        var idProveedor = $("#idProveedor").val();
        var idFactura = $("#idFactura").val();
        $("#cambiarCodigo").load('{{url('inicialesProv')}}' + '?r=' + Date.now() + '&idProveedor=' + idProveedor + '&idFactura=' + idFactura);
    });

    $("#agregarProducto").click(function(){
        var cantidadOrden = $("#cantidadOrden").val();
        var precioOrden = $("#precioOrden").val();
        var idOrden = $("#idOrden").val();
        var totalCalculo = $("#totalCalculo").val();
        var producto = $("#nombreRefaccionDes").val();
        var vendorPartNumber = $("#vendorPartNumber").val();


        if(cantidadOrden=='' || cantidadOrden <=0 || precioOrden==''|| precioOrden<=0 || producto == ''
            ||totalCalculo=='' || totalCalculo <=0 || vendorPartNumber == ''){
            if(cantidadOrden=='' || cantidadOrden <=0){
                alert("Ingresa una cantidad mayor a 0");
            }
            if(precioOrden=='' || precioOrden<=0){
                alert("Ingresa un precio mayor a 0");
            }
            if(producto == ''){
                alert("Seleccionar un producto")
            }
            if(totalCalculo=='' || totalCalculo<=0){
                alert("El Total debe ser mayor a 0");
            } 
            if(vendorPartNumber=='' || vendorPartNumber<=0){
                alert("Ingresar Vendor Part Number");
            }   
        }else{
            $("#reporteProductos").load('{{url('registrarProductos')}}' + '?' + $(this).closest('form').serialize());
            limpiarDatosSku()           
        }

    });

    $("#idDetalleCotizacionServ").change(function(){
        var idDetalleCotizacionServ = $("#idDetalleCotizacionServ").val();
        $("#infoServicio").load('{{url('infoServicioOrden')}}' + '?r=' + Date.now()+'&idDetalleCotizacionServ='+idDetalleCotizacionServ);
        $("#cantidadServicio").load('{{url('cambiarCantidad')}}' + '?r=' + Date.now()+'&idDetalleCotizacionServ='+idDetalleCotizacionServ);
    })
    
    $("input[type=radio][name=nombreEmpresa]").change(function(){ 
        $("#idCuenta").load('{{url('obtenerCuentas')}}' + '?nombreEmpresa='  + this.value) ;
    });

    // funcion que muestra y oculta los select dependiendo el tipo de entrada que se seleccione
    $("input[type=radio][name=tipoEntrada]").change(function() {
        $("#divServicios").css('display','none')
        $("#divExterno").css('display','none')
        $("#divEquipos").css('display','none')
        limpiarDatosSku()        
        if(this.value == "1"){
            $("#divServicios").css('display','block')
        }else if(this.value == "2"){
            $("#divExterno").css('display','block')
        }else{
            $("#divEquipos").css('display','block')
        }
    });

    // funcion para que al seleccionar un servcios se carguen los sku correspondientes a esa factura
    $("#idFactura").change(function() {
        mensajeCargandoCombo('skuServicio')
        $("#skuServicio").load('{{url('obtenerSku')}}' + '?idFactura='  + this.value) ;
    });
    $("#idMarcaRefa").change(function() {
        mensajeCargandoCombo('skuPartesVenta')
        $("#skuPartesVenta").load('{{url('obtenerPartesVenta')}}' + '?idMarcaRefa='  + this.value) ;
    });    
    // funcion para que al seleccionar un servcio se carguen los equipos correspondientes a esa factura
    $("#idFacturaConEquipos").change(function() { 
        mensajeCargandoCombo('idEquipo')
        $("#idEquipo").load('{{url('obtenerEquiposEnFactura')}}' + '?idFactura='  + this.value) ;
    });
    // funcion para que al seleccionar un equipo carga las partes correspondientes a al equipo
    $("#idEquipo").change(function() {
        mensajeCargandoCombo('idParte')
        $("#idParte").load('{{url('obtenerPartesEquiposEnFactura')}}' + '?idEquipo='  + this.value) ;
    });

    //mostrar los detalles de la parte del equipo seleccionada
    $("#idParte").change(function(){
        var idParte = $("#idParte").val();
        $("#infoSku").load('{{url('infoSkuOrden')}}' + '?r=' + Date.now()+'&idParte='+idParte);
    });
    $("#skuPartesVenta").change(function(){
        var idPartesVenta = $("#skuPartesVenta").val();
        $("#infoSku").load('{{url('infoSkuOrden')}}' + '?r=' + Date.now()+'&idPartesVenta='+idPartesVenta);
    });
    //funcion para obtener la informacion del sku que se eliga desde el radio de servicios
    $("#skuServicio").change(function(){
        var idOcupar = $("#skuServicio").val();
        $("#infoSku").load('{{url('infoSkuOrden')}}' + '?r=' + Date.now()+'&idPartesVenta='+idOcupar);
    });

    // funcion que muestra y oculta el select de PAYMENT TERMS
    $("input[type=radio][name=formato]").change(function() {
        document.getElementById("paymentSelect").value ="";

        if(this.value == "CMG USA"){
            document.querySelector('#domicilioEntrega').value = `Control and Motion Group Inc.
Arturo Y Torres 4602 Modern Lane, 
Laredo Texas.US. ZIP 78041
arturo.hamdan@cmgroup-us.com
+1 956-717-3133`
            document.querySelector('#billTo').value = `Control and Motion Group Inc.
Zabdi Torres Hamdan 4602 Modern Lane, 
Laredo Texas.US. ZIP 78041
accounting@gcmgdl.com
+1 956-717-3133`
            document.getElementById('anexoShipping').style.display = 'block';
            document.getElementById('detalleEnvio').style.display = 'block';
        }else{
            document.querySelector('#domicilioEntrega').value = ''
            document.querySelector('#billTo').value = ''
        }
    });

</script>

<script>
    var estatusOrden = '<?php echo $consulta->estatus?>';
    // $("#fechaProgramadaPago").click(function(){
    //     if (estatusOrden != 'Archivos agregados, En espera de programación de pago') {            
    //         $("#nuevoEstatusOrden").css('border-color', 'red');
    //         $("#nuevoEstatusOrden").css('color', 'red');
    //         $("#fechaProgramadaPago").css('border-color', 'red');
    //         $("#fechaProgramadaPago").css('color', 'red');
    //         $("#nuevoEstatusOrden").val('Sin programar pago');
    //         $("#fechaProgramadaPago").val('');
    //         alert('No se puede programar el pago, el proveedor aún no agrega PDF y XML de factura');
    //     }
    // });

    $("#guardar").click(function(e) {        
        let nuevoEstatusOrden = $("#nuevoEstatusOrden").val();
        let fechaProgramadaPago = $("#fechaProgramadaPago").val();
        let fechaDePago = $("#fechaDePago").val();
        var evidencia = $('#evidenciaTransferencia').val();
        var montoComplementoPago = $('#montoComplementoPago').val();
        var estatusOrden = "<?php echo $consulta->estatus;?>"

        if (estatusOrden == 'Pago programado'){

            if (fechaDePago == "") {
                $("#fechaDePago").css('border-color', 'red');
                $("#fechaDePago").css('color', 'red');
                alert('Debe agregar fecha programada para el pago');
                e.preventDefault();
            }
        
        // }

        // if (nuevoEstatusOrden == 'Pagado'){
        //     if (estatusOrden != 'Pago programado') {            
        //         $("#nuevoEstatusOrden").css('border-color', 'red');
        //         $("#nuevoEstatusOrden").css('color', 'red');
        //         $("#fechaDePago").css('border-color', 'red');
        //         $("#fechaDePago").css('color', 'red');
        //         alert('El pago aun no se a programado');
        //         e.preventDefault();
        //     }

        //     if (fechaDePago == "") {
        //         $("#fechaDePago").css('border-color', 'red');
        //         $("#fechaDePago").css('color', 'red');
        //         alert('Debe agregar la fecha en que se realizó el pago');
        //         e.preventDefault();
        //     }

        //     // if (nuevoEstatusOrden != "Pagado") {
        //     //     $("#nuevoEstatusOrden").css('border-color', 'red');
        //     //     $("#nuevoEstatusOrden").css('color', 'red');
        //     //     $("#fechaDePago").css('border-color', 'red');
        //     //     $("#fechaDePago").css('color', 'red');
        //     //     alert('El estatus es incorrecto');
        //     //     e.preventDefault();
        //     // }

            if (evidencia == ''){
                $("#evidenciaTransferencia").css('border-color', 'red');
                $("#evidenciaTransferencia").css('color', 'red');            
                alert('El *Archivo de evidencia de transferencia es obligatorio.');
                e.preventDefault(); 
            }

            if (montoComplementoPago == '') {
                $("#montoComplementoPago").css('border-color', 'red');
                $("#montoComplementoPago").css('color', 'red');            
                alert('El Monto de transferencia es obligatorio.');
                e.preventDefault(); 
                
            }
        }
            

    });

    $("#guardar").click(function(e){    

        $("#guardar").css('pointer-events','none');
        $('#guardar').css("opacity","0.6");

        setTimeout(function(){
            $('#guardar').css("opacity","");
            $("#guardar").css('pointer-events','');
        }, 3000);

        $("#formularioOrden").validate({
            rules: {
                idProveedor: {required:true},
                idCuenta: {required:true},                   
                idCuentaProv: {required:true},
                fechaOrden: {required:true},
                formaPago: {required:true},
                cfdi: {required:true},
                vigenciaCotizacion: {required:true, number: true},
                estatus: {required:true}
            },
            messages: 
            {
                idProveedor: {required: 'Elige un proveedor'},                        
                idCuenta: {required: 'Elige una cuenta'}, 
                idCuentaProv: {required: 'Elige una cuenta proveedor'},                         
                fechaOrden: {required: 'La fecha es requerida'},
                formaPago: {required: 'La forma de pago es requerida'},
                cfdi: {required: 'Uso del CFDI es requerido'},
                vigenciaCotizacion: {required: 'La vigencia es requerida', number: 'Escribe un número'},
                estatus: {required: 'Elige un estatus'}
            }
            // this.disabled = true;
        });
        
    });

    function validarEvidenciaT(obj) {
        var uploadFile = obj.files[0];        
        if (!(/\.(jpeg|png|gif|jpg|pdf)$/i).test(uploadFile.name)) {
            alert('El archivo seleccionado no es valido.');
            $('#evidenciaTransferencia').val('');
        }
    }
    function validarCotizacion(obj) {
        var uploadFile = obj.files[0];        
        if (!(/\.(jpeg|png|gif|jpg|pdf)$/i).test(uploadFile.name)) {
            alert('El archivo seleccionado no es valido.');
            $('#cotizacionProveedor').val('');
        }
    }
</script>

@stop