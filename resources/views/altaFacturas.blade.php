@extends('principal')
@section('contenido')
    
<script type="text/javascript">
$(document).ready(function() {
    $("#divinfo").hide(1500);
    $(".cargar").click();

    $("#guardar").click(function(){
        
        // console.log("faaaaaaaaaaaaaaf");
		$("#formFacturas").validate({
            onclick: false, 
            onkeyup: false,
            ignore: [],
			rules: {
                
                montoFactura: {required:true},
                periodoPago: {required:true},
                idc: {required:true},
                idSucursal: {required:true},
                saldo: {required:true},
                
			},
			messages: 
			{
                
                montoFactura: {required: 'El monto de cotización es obligatorio'},
                periodoPago: {required: 'El periodo de pago es obligatorio'},
                idc: {required: 'Elige un cliente'},
                idSucursal: {required: 'Elige una sucursal'},
                saldo: {required: 'El saldo es obligatorio'},
                
                
            },
            errorPlacement: function (error, element) {
            alert(error.text());
        }
		});
       
    });

    $("#valorCambio, #valorSaldo").change(function(){
        var valorCambio = $("#valorCambio").val();
        var valorSaldo = $("#valorSaldo").val();
        var total=valorCambio*valorSaldo;
        totalF=total.toFixed(2);
        document.getElementById("resultadoMonto").value = totalF;
    });
    
});
</script>


<!-- <script type="text/javascript">
$(document).ready(function() {
    $(".cargar").click();
});
</script> -->

{{Form::open(['route' => 'GuardarFacturas','files'=>true, 'id' => 'formFacturas'])}}
{{Form::token()}}
<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Registro de nuevo Servicio<small> *Campos obligatorios</small></h1>
        </div>
        <div class="panel-body">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="cargar" class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                        role="tab" aria-controls="pills-home" aria-selected="true">Datos del
                        Servicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                        aria-controls="pills-profile" aria-selected="false">Datos de Pago</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab"
                        aria-controls="pills-contact" aria-selected="false">Archivos</a>
                </li>
            @if($stipo == 'Administrador')
                <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#archivosAuditoria" role="tab"
                        aria-controls="pills-contact" aria-selected="false">Archivos para auditoría</a>
                </li>
            @endif
            </ul>

            <div class="tab-content" id="pills-tabContent">

                <!--Primer tab Datos de Factura --->
                <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                    <div class="form-group col-md-6">

                    <input class="form-control" name="idFactura" type="hidden" value="{{$idFact}}"
                                readonly="true">
                        <div class="sub-title">Número de Servicio asignado:</div>
                        <div>
                            <input class="form-control" name="idServicios" type="text" value="{{$idServ}}"
                                readonly="true">
                        </div>
                        <div class="sub-title">Tipo de Servicio:</div>
                        <div>
                            <select name="tipoServicio" class="form-control rounded-0">
                                <option selected="selected" value="">Selecciona el tipo de servicio</option>
                                <option value="Venta de refacciones">Venta de refacciones</option>
                                <option value="Refurbished">Refurbished</option>
                                <option value="Soporte Técnico">Soporte Técnico</option>
                                <option value="Servicio de Cambio">Servicio de Cambio</option>
                                <option value="Reparaciones">Reparaciones</option>
                                <option value="Diseño y Fabricación">Diseño y Fabricación</option>
                                <option value="Mantenimiento preventivo">Mantenimiento preventivo</option>
                                <option value="Mantenimiento Correctivo">Mantenimiento Correctivo </option>
                            </select>
                        </div>

                        <div class="sub-title">Selecciona el nombre de la empresa:</div>
                        <div>
                            CYM {{Form::radio('nombreEmpresa','CYM ')}}
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            GCM {{Form::radio('nombreEmpresa','GCM')}}
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            SURJA {{Form::radio('nombreEmpresa','SURJA')}}
                        </div>

                        <div class="sub-title">Fecha de subida al portal: </div>
                        <div>
                            @if($errors->first('fechaVencimiento'))
                            <i> {{ $errors->first('fechaVencimiento') }}</i>
                            @endif
                            {{Form::date('fechaVencimiento', \Carbon\Carbon::now(),['class' => 'form-control'])}}
                        </div>

                        <div class="sub-title">Banco:</div>
                        @if($errors->first('idb'))
                        <i> {{ $errors->first('idb') }}</i>
                        @endif
                        <div>
                            <select name='idb' class="form-control">
                                <option value="">Seleccione el banco</option>
                                @foreach($bancos as $bancos)
                                @if($bancos->activo=="si")
                                <option value='{{$bancos->idb}}' name="{{$bancos->nombre}}">{{$bancos->nombre}}
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="sub-title">*Monto cotización:</div>
                        <div>
                            @if($errors->first('montoFactura'))
                            <i> {{ $errors->first('montoFactura') }}</i>
                            @endif
                            {{Form::text('montoFactura',old ('montoFactura'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 3000'])}}
                        </div>
                        <div class="sub-title">IVA:</div>
                        <div>
                            {{Form::text('ivaFactura',old ('ivaFactura'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10%'])}}
                        </div>
                        <div class="sub-title">Retención IVA:</div>
                        <div>
                            {{Form::text('retencionIva',old ('retencionIva'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10%'])}}
                        </div>
                        <div class="sub-title">Total:</div>
                        <div>
                            {{Form::text('totalConIva',old ('totalConIva'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 1000'])}}
                        </div>

                    </div>

                    <div class="form-group col-md-6">
                        <div class="sub-title">Número de Factura:</div>
                        <div>
                            @if($errors->first('numeroFactura'))
                            <i> {{ $errors->first('numeroFactura') }}</i>
                            @endif
                            {{Form::text('numeroFactura',old ('numeroFactura'),['class' => 'form-control', 'placeholder' => 'Ejemplo: A0001'])}}
                        </div>

                        <div class="sub-title">Fecha de Facturación: </div>
                        <div>
                            @if($errors->first('fechaFactura'))
                            <i> {{ $errors->first('fechaFactura') }}</i>
                            @endif
                            {{Form::date('fechaFactura', old ('fechaFactura'),['class' => 'form-control'])}}
                        </div>

                        <div class="sub-title">Fecha de pago: </div>
                        <div>
                            @if($errors->first('fechaPago'))
                            <i> {{ $errors->first('fechaPago') }}</i>
                            @endif
                            {{Form::date('fechaPago', old ('fechaPago'),['class' => 'form-control'])}}
                        </div>

                        <!--/////////////////////////////////////////////////////////////////////////////////////////////////////////  -->
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="sub-title">*Moneda de Pago:</div>
                                @if($errors->first('tipoMoneda'))
                                <i> {{ $errors->first('tipoMoneda') }}</i>
                                @endif
                                <div>
                                    USD {{Form::radio('tipoMoneda','USD')}}
                                    MXN {{Form::radio('tipoMoneda','MXN', true)}}
                                </div>
                                <div class="sub-title">*Estatus de Entrega:</div>
                                @if($errors->first('estatusEntrega'))
                                <i> {{ $errors->first('estatusEntrega') }}</i>
                                @endif
                                <div>
                                    Pendiente {{Form::radio('estatusEntrega','Pendiente', true)}}
                                    Entregado {{Form::radio('estatusEntrega','Entregado')}}
                                </div>
                                <br>
                                <div class="sub-title">*Estatus de Pago:</div>
                                @if($errors->first('estatusPago'))
                                <i> {{ $errors->first('estatusPago') }}</i>
                                @endif
                                <div>
                                    Pagada {{Form::radio('estatusPago','Pagada')}}
                                    Pendiente {{Form::radio('estatusPago','Pendiente por pagar',true)}}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="sub-title">*Metodo de Pago:</div>
                                @if($errors->first('metodoPago'))
                                <i> {{ $errors->first('metodoPago') }}</i>
                                @endif
                                <div>
                                    PUE {{Form::radio('metodoPago','PUE',true)}}
                                    PPD {{Form::radio('metodoPago','PPD')}}
                                </div>

                                <div class="sub-title">*Estatus Portal:</div>
                                @if($errors->first('estatusPortal'))
                                <i> {{ $errors->first('estatusPortal') }}</i>
                                @endif
                                <div>
                                    Pendiente en subir {{Form::radio('estatusPortal','Pendiente en subir', true)}} <br>
                                    Subido en el portal {{Form::radio('estatusPortal','Subido en el portal')}}
                                </div>

                                <div class="sub-title">*Registrado en CashFlow:</div>
                                @if($errors->first('cashFlow'))
                                <i> {{ $errors->first('cashFlow') }}</i>
                                @endif
                                <div>
                                    Si{{Form::radio('cashFlow','Si')}}
                                    No{{Form::radio('cashFlow','No', true)}}
                                </div>


                            </div>
                        </div>
                        <div class="sub-title">Complemento de Pago:</div>
                        <div>
                            @if($errors->first('complementoPago'))
                            <i> {{ $errors->first('complementoPago') }}</i>
                            @endif
                            {{Form::text('complementoPago',old ('complementoPago'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 350'])}}
                        </div>
                        <div class="sub-title">*Periodo de Pago:</div>
                        <div>
                            @if($errors->first('periodoPago'))
                            <i> {{ $errors->first('periodoPago') }}</i>
                            @endif
                            <select name="periodoPago" class="form-control rounded-0">
                                <option selected="selected" value="">Seleccione los días de pago</option>
                                <option value="7 Días">7 Días</option>
                                <option value="10 Días">10 Días</option>
                                <option value="15 Días">15 Días</option>
                                <option value="30 Días">30 Días</option>
                                <option value="40 Días">40 Días</option>
                                <option value="45 Días">45 Días</option>
                                <option value="60 Días">60 Días</option>
                                <option value="90 Días">90 Días</option>
                            </select>
                        </div>

                    </div>
                </div>
                <!--Segundo tab Datos de pago --->
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="form-group col-md-6">
                        <div class="sub-title">*Empresa a visitar:</div>
                        @if($errors->first('idc'))
                        <i> {{ $errors->first('idc') }}</i>
                        @endif<div>
                            <select name='idc' id='idc' class="form-control">
                                <option value="">Seleccionar empresa</option>
                                @foreach($cliente as $cliente)
                                @if($cliente->activo=="si")
                                <option value='{{$cliente->idc}}'>{{$cliente->razonSocial}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="sub-title">Tipo de cambio facturado:</div>
                        <div>
                            @if($errors->first('cambioFactura'))
                            <i> {{ $errors->first('cambioFactura') }}</i>
                            @endif
                            {{Form::text('cambioFactura',old ('cambioFactura'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 20.15', 'id' => 'valorCambio'])}}
                        </div>
                        <div class="sub-title">Monto Pesos:</div>
                        <div>
                            @if($errors->first('montoPesos'))
                            <i> {{ $errors->first('montoPesos') }}</i>
                            @endif
                            {{Form::text('montoPesos',old ('montoPesos'),['class' => 'form-control', 'id' => 'resultadoMonto','readonly'])}}
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="sub-title">*Sucursal:</div>
                        @if($errors->first('idSucursal'))
                        <i> {{ $errors->first('idSucursal') }}</i>
                        @endif
                        <div id='com'>
                            <select name='idSucursal' id="idp" class="form-control"></select>
                        </div>
                        <div class="sub-title">*Saldo:</div>
                        <div>
                            @if($errors->first('saldo'))
                            <i> {{ $errors->first('saldo') }}</i>
                            @endif
                            
                            <input type="text" class="form-control" name="saldo" id='valorSaldo' placeholder="Ejemplo: 3600" >
                        </div>
                        <div class="sub-title">Tipo de cambio pagado:</div>
                        <div>
                            @if($errors->first('cambioReal'))
                            <i> {{ $errors->first('cambioReal') }}</i>
                            @endif
                            {{Form::text('cambioReal',old ('cambioReal'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 19.30'])}}
                        </div>
                    </div>
                    <div class="sub-title">Observaciones:</div>
                    <div>
                        @if($errors->first('observacionesFactura'))
                        <i> {{ $errors->first('observacionesFactura') }}</i>
                        @endif
                        <textarea name="observacionesFactura" rows="5" class="form-control rounded-0"
                            placeholder="Escribe las observaciones que consideres necesarias.">{!! old('observacionesFactura') !!}</textarea>
                    </div>
                </div>
                <!--tercer tab Archivos --->
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">

                <div class="row">
                    <div class="col-lg-4">
                
                    <div class="sub-title">Asignar número de cotización:</div>
                    <div>
                        @if($errors->first('idCotizacion'))
                        <i> {{ $errors->first('idCotizacion') }}</i>
                        @endif
                        <div id='combo'>
                            <select name='idCotizacion' id="idCotizacion" class="form-control"></select>
                        </div>
                    </div>
                </div>
                <br><br>
                <div class="col-lg-4">
                    <div id="cotizacion">
                           
                    </div>
                    </div>
                </div>
           
                    <div class="form-group col-md-6">
                        <div class="sub-title">Orden de Compra:</div>
                        <div>
                            @if($errors->first('ordenCompra'))
                            <i> {{ $errors->first('ordenCompra') }}</i>
                            @endif
                            {{Form::text('ordenCompra',old ('ordenCompra'),['class' => 'form-control', 'placeholder' => 'Ejemplo: AC286639'])}}
                        </div>
                        <br><br>
                        <div class="sub-title">Factura y XML:</div>
                        <div>
                            @if($errors->first('factura'))
                            <i> {{ $errors->first('factura') }}</i>
                            @endif
                            {{Form::text('factura',old ('factura'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>
                        <br><br><br>

                        <div class="sub-title">Comprobante de pago:</div>
                        <div>
                            @if($errors->first('comprobantePago'))
                            <i> {{ $errors->first('comprobantePago') }}</i>
                            @endif
                            {{Form::text('comprobantePago',old ('comprobantePago'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Número de remisión:</div>
                        <div>
                            @if($errors->first('numeroRemision'))
                            <i> {{ $errors->first('numeroRemision') }}</i>
                            @endif
                            {{Form::text('numeroRemision',old ('numeroRemision'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>
                        <div class="sub-title">Número de entrada :</div>
                        <div>
                            @if($errors->first('numeroEntrada'))
                            <i> {{ $errors->first('numeroEntrada') }}</i>
                            @endif
                            {{Form::text('numeroEntrada',old ('numeroEntrada'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>
                        <div class="sub-title">ADENDA :</div>
                        <div>
                            @if($errors->first('adenda'))
                            <i> {{ $errors->first('adenda') }}</i>
                            @endif
                            {{Form::text('adenda',old ('adenda'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>
                    </div>

                    <div class="form-group col-md-6">

                        <div class="sub-title">Subir Orden de Compra:</div>
                        <div>
                            @if($errors->first('archivo'))
                            <i> {{ $errors->first('archivo') }}</i>
                            @endif
                            {{Form::file('archivo')}}
                        </div>
                        <br>

                        <div class="sub-title">Subir Factura:</div>
                        <div>
                            @if($errors->first('archivoFactura'))
                            <i> {{ $errors->first('archivoFactura') }}</i>
                            @endif
                            {{Form::file('archivoFactura')}}
                        </div>
                        <br>
                        <div class="sub-title">Subir XML de la Factura:</div>
                        <div>
                            @if($errors->first('xmlFactura'))
                            <i> {{ $errors->first('xmlFactura') }}</i>
                            @endif
                            {{Form::file('xmlFactura')}}
                        </div>
                        <br>
                        <div class="sub-title">Subir Comprobante de Pago:</div>
                        <div>
                            @if($errors->first('archivoPago'))
                            <i> {{ $errors->first('archivoPago') }}</i>
                            @endif
                            {{Form::file('archivoPago')}}
                        </div>
                        <br>
                        <div class="sub-title">Subir Comprobante de remisión:</div>
                        <div>
                            @if($errors->first('archivoRemision'))
                            <i> {{ $errors->first('archivoRemision') }}</i>
                            @endif
                            {{Form::file('archivoRemision')}}
                        </div>
                        <br><br><br><br><br>
                        <div class="sub-title">Subir archivo ADENDA:</div>
                        <div>
                            @if($errors->first('archivoAdenda'))
                            <i> {{ $errors->first('archivoAdenda') }}</i>
                            @endif
                            {{Form::file('archivoAdenda')}}
                        </div>
                    </div>
                </div>
<!-- tab de archivos para auditoria                  -->
                <div class="tab-pane fade" id="archivosAuditoria" role="tabpanel" aria-labelledby="pills-contact-tab">
                    <div class="form-group col-md-6">
                    <div class="sub-title">Número de documento (Otros) :</div>
                        <div>
                            @if($errors->first('numeroDocumento'))
                            <i> {{ $errors->first('numeroDocumento') }}</i>
                            @endif
                            {{Form::text('numeroDocumento',old ('numeroDocumento'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 1:</div>
                        <div>
                            @if($errors->first('numeroAnexo1'))
                            <i> {{ $errors->first('numeroAnexo1') }}</i>
                            @endif
                            {{Form::text('numeroAnexo1',old ('numeroAnexo1'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 2:</div>
                        <div>
                            @if($errors->first('numeroAnexo2'))
                            <i> {{ $errors->first('numeroAnexo2') }}</i>
                            @endif
                            {{Form::text('numeroAnexo2',old ('numeroAnexo2'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 3:</div>
                        <div>
                            @if($errors->first('numeroAnexo3'))
                            <i> {{ $errors->first('numeroAnexo3') }}</i>
                            @endif
                            {{Form::text('numeroAnexo3',old ('numeroAnexo3'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 4:</div>
                        <div>
                            @if($errors->first('numeroAnexo4'))
                            <i> {{ $errors->first('numeroAnexo4') }}</i>
                            @endif
                            {{Form::text('numeroAnexo4',old ('numeroAnexo4'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 5:</div>
                        <div>
                            @if($errors->first('numeroAnexo5'))
                            <i> {{ $errors->first('numeroAnexo5') }}</i>
                            @endif
                            {{Form::text('numeroAnexo5',old ('numeroAnexo5'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 6:</div>
                        <div>
                            @if($errors->first('numeroAnexo6'))
                            <i> {{ $errors->first('numeroAnexo6') }}</i>
                            @endif
                            {{Form::text('numeroAnexo6',old ('numeroAnexo6'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 7:</div>
                        <div>
                            @if($errors->first('numeroAnexo7'))
                            <i> {{ $errors->first('numeroAnexo7') }}</i>
                            @endif
                            {{Form::text('numeroAnexo7',old ('numeroAnexo7'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 8:</div>
                        <div>
                            @if($errors->first('numeroAnexo8'))
                            <i> {{ $errors->first('numeroAnexo8') }}</i>
                            @endif
                            {{Form::text('numeroAnexo8',old ('numeroAnexo8'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 9:</div>
                        <div>
                            @if($errors->first('numeroAnexo9'))
                            <i> {{ $errors->first('numeroAnexo9') }}</i>
                            @endif
                            {{Form::text('numeroAnexo9',old ('numeroAnexo9'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 10:</div>
                        <div>
                            @if($errors->first('numeroAnexo10'))
                            <i> {{ $errors->first('numeroAnexo10') }}</i>
                            @endif
                            {{Form::text('numeroAnexo10',old ('numeroAnexo10'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="sub-title">Archivo (Otros):</div>
                        <div>
                            @if($errors->first('archivoOtro'))
                            <i> {{ $errors->first('archivoOtro') }}</i>
                            @endif
                            {{Form::file('archivoOtro')}}
                        </div>
                        
                        <div class="sub-title">Archivo Anexo 1:</div>
                        <div>
                            @if($errors->first('anexo1'))
                            <i> {{ $errors->first('anexo1') }}</i>
                            @endif
                            {{Form::file('anexo1')}}
                        </div>
                        <br>
                        <div class="sub-title">Archivo Anexo 2:</div>
                        <div>
                            @if($errors->first('anexo2'))
                            <i> {{ $errors->first('anexo2') }}</i>
                            @endif
                            {{Form::file('anexo2')}}
                        </div>
                        <br>
                        <div class="sub-title">Archivo Anexo 3:</div>
                        <div>
                            @if($errors->first('anexo3'))
                            <i> {{ $errors->first('anexo3') }}</i>
                            @endif
                            {{Form::file('anexo3')}}
                        </div>
                        <br>
                        <div class="sub-title">Archivo Anexo 4:</div>
                        <div>
                            @if($errors->first('anexo4'))
                            <i> {{ $errors->first('anexo4') }}</i>
                            @endif
                            {{Form::file('anexo4')}}
                        </div>
                        
                        <div class="sub-title">Archivo Anexo 5:</div>
                        <div>
                            @if($errors->first('anexo5'))
                            <i> {{ $errors->first('anexo5') }}</i>
                            @endif
                            {{Form::file('anexo5')}}
                        </div>

                        <br>
                        <div class="sub-title">Archivo Anexo 6:</div>
                        <div>
                            @if($errors->first('anexo6'))
                            <i> {{ $errors->first('anexo6') }}</i>
                            @endif
                            {{Form::file('anexo6')}}
                        </div>

                        <br>
                        <div class="sub-title">Archivo Anexo 7:</div>
                        <div>
                            @if($errors->first('anexo7'))
                            <i> {{ $errors->first('anexo7') }}</i>
                            @endif
                            {{Form::file('anexo7')}}
                        </div>

                        <div class="sub-title">Archivo Anexo 8:</div>
                        <div>
                            @if($errors->first('anexo8'))
                            <i> {{ $errors->first('anexo8') }}</i>
                            @endif
                            {{Form::file('anexo8')}}
                        </div>

                        <br>
                        <div class="sub-title">Archivo Anexo 9:</div>
                        <div>
                            @if($errors->first('anexo9'))
                            <i> {{ $errors->first('anexo9') }}</i>
                            @endif
                            {{Form::file('anexo9')}}
                        </div>

                        <br>
                        <div class="sub-title">Archivo Anexo 10:</div>
                        <div>
                            @if($errors->first('anexo10'))
                            <i> {{ $errors->first('anexo10') }}</i>
                            @endif
                            {{Form::file('anexo10')}}
                        </div>

                        <br><br><br><br><br>
                    </div>
                    <center>{{Form::submit('Guardar',['class' => 'btn  btn-default', 'id' => 'guardar'])}}
                        <a href="{{asset('reporteFacturas')}}"><button type="button"
                                class="btn btn-default">Cancelar</button></a>
                    </center>
                </div>
<!-- acaba tab para auditoria  -->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$("#idc").change(function() {
    $("#idp").load('{{url('comboca1')}}' + '?r=' + Date.now() + '&idc=' + this.options[this.selectedIndex].value);
    // alert("mensaje correcto")
});
</script>

<script type="text/javascript">
$("#idp").click(function() {
    // alert("Cotiza");
    $("#idCotizacion").load('{{url('comboCotizaciones')}}' + '?r=' + Date.now() + '&idp=' + this.options[this.selectedIndex].value);
});
</script>

<script type="text/javascript">
$("#idCotizacion").change(function() {
    // alert("Cotiza");
    $("#cotizacion").load('{{url('asignarCotizacion')}}' + '?r=' + Date.now() + '&idCotizacion=' + this.options[this.selectedIndex].value);
});
</script>
<style>
.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}
input.error {
    border-color: #f00 !important;
}

small.required {
    color:#f00;
}

.error {
    color: #f00 !important;
}
</style>
@stop