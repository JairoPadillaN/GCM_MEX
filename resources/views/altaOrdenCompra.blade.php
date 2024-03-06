
@extends('principal')
@section('contenido')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{Form::open(['route' => 'guardarOrdenCompra','id' => 'formularioOrden','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <!-- Probando GIT Ashley -->
            <h1>Registro de nueva orden
                <!-- <small> *Campos Requeridos</smal l> -->
            </h1>
        </div>
        <div class="panel-body">

            <!-- DATOS DE ORDEN -->
            <h4>Datos de la orden</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="sub-title">Registrado por: </div>
                            <div>
                                {{Form::hidden('estatus','Sin programar pago')}}
                                <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu')!!}">
                                <input type="text" name="registradoPor" class="form-control" readonly="true" value="{!! Session::get('sesionname')!!} {!! Session::get('sesionpaterno')!!} {!!Session::get('sesionmaterno')!!}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="sub-title">*Fecha de la orden: </div>
                            <div>                                
                                {{Form::date('fechaOrden',\Carbon\Carbon::now(),['class' => 'form-control'])}}
                            </div>
                        </div>
                    </div>
                    <div class="sub-title">*Empresa: </div>
                    <div>
                            CYM {{Form::radio('nombreEmpresa','CYM')}}
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            GCM {{Form::radio('nombreEmpresa','GCM')}}
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            SURJA {{Form::radio('nombreEmpresa','SURJA')}}
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          
                            <br>
                            Pedro Ocegueda J. {{Form::radio('nombreEmpresa','POJ')}}
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            Yaneth Abilene G. A. {{Form::radio('nombreEmpresa','YAGA')}}
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="sub-title">*Cuenta:</div>
                            <div>
                                <select name="idCuenta" id="idCuenta" class="form-control" onfocus="validarEmpresa()">
                                    <option value="">Seleccionar cuenta</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="sub-title">*Uso del CFDI:</div>
                            <select name="cfdi" id="cfdi" class="form-control">
                                <option value="">Selecciona el CFDI</option>
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
                            <option value="">Selecciona proveedor</option>
                            @foreach($consultaProveedor as $proveedor)
                            <option value="{{$proveedor->idProveedor}}">{{$proveedor->razonSocialProv}}</option>
                            @endforeach
                        </select>
                    </div>                    
                    <div class="sub-title">*Vigencia cotización de proveedor (días): </div>
                    <div class="">
                        {{Form::text('vigenciaCotizacion',old('vigenciaCotizacion'),['class' => 'form-control', 'placeholder'=>'Ejem: 10'])}}
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="sub-title">*Cuenta a pagar: </div>
                            <div>
                                <select name="idCuentaProv" id="idCuentaProv" class="form-control">
                                    <option value="">Selecciona cuenta de proveedor</option>
                                </select>
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <div class="sub-title">*Forma de Pago: </div>
                            <div>
                                <select name="formaPago" id="formaPago" class="form-control">
                                    <option value="">Selecciona forma de pago</option>
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
                        {{Form::textArea('notasOrden',old('notasOrden'),['class' => 'form-control','rows'=>'3'])}}
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="sub-title">Teléfono adicional: </div>
                            {{Form::text('telAdicional',old('telAdicional'),['class' => 'form-control'])}}
                        </div>
                        <div class="col-sm-6">
                            <div class="sub-title">Correo adicioal: </div>
                            {{Form::email('correoAdicional',old('correoAdicional'),['class' => 'form-control'])}}
                        </div>
                    </div>
                    <div>
                        <b>Visualizar RFC del proveedor en PDF de la OC:</b>
                        <input type="checkbox" name="mostrarRfc" id="" checked>
                    </div>
                    <div>
                        <b>Visualizar TAX ID en PDF de la OC:</b>
                        <input type="checkbox" name="mostrarTaxId" id="" checked>
                    </div>
                </div>
                <div class="col-md-4">
                    <div>
                        <div class="sub-title">Incoterm: </div>
                        <select name="incoterm" id="" class="form-control">
                            <option value="">Seleccionar opción</option>
                            <option value="FOB - FREE ON BOARD">FOB - FREE ON BOARD</option>
                            <option value="EXW - EX WORKS">EXW - EX WORKS</option>
                            <option value="DDP - DELIVERY DUTY PAID">DDP - DELIVERY DUTY PAID</option>
                            <option value="DAP - DELIVERED AT PLACE">DAP - DELIVERED AT PLACE</option>
                        </select>
                        <div class="sub-title">Shipping text: </div>
                        <div>
                            {{Form::textArea('detalleIncoterm',old('detalleIncoterm'),['class' => 'form-control','rows'=>'2'])}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="sub-title">Moneda: </div>
                            <div>
                                {{Form::radio('moneda','MXN',true)}} MXN&nbsp;&nbsp;&nbsp;
                                {{Form::radio('moneda','USD')}} USD
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="sub-title">Cambio del dolar: </div>
                            <div>
                                {{Form::text('cambioDolar',old('cambioDolar'),['class' => 'form-control', 'placeholder'=>'Ejemplo: 20.20'])}}
                            </div>
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="sub-title">Formato: </div>
                            <div>
                                <div>
                                    {{Form::radio('formato','México',true)}} México
                                    &nbsp;&nbsp;&nbsp;
                                    {{Form::radio('formato','CMG USA')}} CMG USA
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div>
                                <div class="sub-title">Payment terms: </div>
                                <select name="paymentTerms" id="paymentSelect" class="form-control">
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
                                {{Form::radio('tipoPago','PUE',true, ['id'=>'contado'])}} PUE&nbsp;&nbsp;&nbsp;
                                {{Form::radio('tipoPago','PPD', false, ['id'=>'credito'])}} PPD
                            </div>
                        </div>                        
                        <div class="col-sm-7">
                            <div style="display:none;" id="mostrarPlazo">
                                <div class="sub-title">Plazo en días:</div>
                                <div>
                                    <select name="diasPlazo" id="diasPlazo" class="form-control">
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
                    <div>
                        {{Form::file('cotizacionProveedor',['id'=>'cotizacionProveedor','class' => 'form-control'])}}
                    </div>
                    <div class="row" style='' id="">
                        <div class="col-sm-8">
                            <div class="sub-title">*Vendor Quote Nr. / Reference: </div>
                            <div>
                                {{Form::text('vendorQuote',old('vendorQuote'),['class' => 'form-control','id'=>'vendorQuote'])}}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="sub-title">*Due Date: </div>
                            <div>
                                {{Form::date('dueDate',\Carbon\Carbon::now(),['class' => 'form-control'])}}
                            </div>
                        </div>
                    </div>
                    <div class="sub-title">Domicilio de entrega: </div>
                    <div>
                        {{Form::textArea('domicilioEntrega',old('domicilioEntrega'),['class' => 'form-control','rows'=>'3','id'=>'domicilioEntrega'])}}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sub-title">Folio: </div>
                    <div id="cambiarCodigo">
                        {{Form::text('codigoOrden',old('codigoOrden'),['class' => 'form-control', 'readonly','id'=>'codigoOrden'])}}
                    </div>
                    <!-- <div class="sub-title">*Estatus proveedor: </div>
                    <div>
                        <select name="estatusProveedor" id="estatusProveedor" class="form-control" readonly> -->
                            <!-- <option value="">Selecciona estatus del proveedor</option> -->
                            <!-- <option value="Pendiente de aceptación, sin fecha entrega" selected>Pendiente de aceptación, sin fecha entrega</option> -->
                            <!-- <option value="Pendiente de aceptación, con fecha entrega">Pendiente de aceptación, con fecha entrega</option>
                            <option value="Aceptado con fecha de entrega">Aceptado con fecha de entrega</option>                                    
                            <option value="No aceptado">No aceptado</option> -->
                        <!-- </select>
                    </div>
                    <div class="sub-title">Fecha de entrega:</div>
                    <div>
                        {{Form::date('fechaEntregaProveedor', old('fechaEntregaProveedor'),['class'=>'form-control','disabled'])}}
                    </div>
                    <div class="sub-title">*Estatus de la orden: </div>
                    <div>
                        {{Form::text('estatus','Sin programar pago',['class' => 'form-control', 'readonly'])}} -->
                        <!-- <select name="estatus" id="" class="form-control">
                            <option value="">Selecciona un estatus</option>   
                            <option value="Cancelado">Cancelado</option>
                            <option value="Garantia">Garantia</option>
                            <option value="Pagado">Pagado</option>
                            <option value="Pago programado">Pago programado</option>
                            <option value="Pendiente por pagar">Pendiente por pagar</option>
                            <option value="Sin programar pago">Sin programar pago</option>
                        </select> -->
                    <!-- </div> -->
                    <div style='' id="">
                        <div class="sub-title">*Detalle metodo de envío:</div>
                        <select name="detalleMetodoEnvio" id="detalleMetodoEnvio" class="form-control">
                            <option value="">Seleccionar</option>
                            <option value="OUR UPS">OUR UPS</option>
                            <option value="PPD & ADD">PPD & ADD</option>
                        </select>
                    </div>
                    <div style='' id="">
                        <div class="sub-title">*Bill To:</div>
                        {{Form::textArea('billTo',old('billTo'),['class' => 'form-control','rows'=>'4','id'=>'billTo'])}}
                    </div>
                    <div hidden>
                        <div class="sub-title">Fecha programada de pago: </div>
                        <div>
                            {{Form::date('fechaProgPago',old('fechaProgPago'),['class' => 'form-control'])}}
                        </div>
                        <div class="sub-title">Fecha real de deposito: </div>
                        <div>
                            {{Form::date('fechaDeposito',old('fechaDeposito'),['class' => 'form-control'])}}
                        </div>
                    </div>
                    <!-- <div>
                    <div class="alert alert-success" role="alert">
                        <b>Subir evidencia de transferencia:</b>
                        {{ Form::file('evidenciaTransferencia',['id'=>'evidenciaTransferencia','class' => 'form-control','onchange'=>'validarEvidenciaT(this);'])}}
                    </div>                        
                    </div> -->

                </div>
            </div>            
        </div>
        <center>
            {{Form::submit('Guardar',['class' => 'btn  btn-success', 'id' => 'guardar'])}}
        </center><br>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#idCuenta").select2({width:'100%'});
        $("#idc").select2({width:'100%'});
        $("#idp").select2({width:'100%'});
        $("#formaPago").select2({width:'100%'});
        $("#formaPago").select2({width:'100%'});
        $("#cfdi").select2({width:'100%'});
        $("#idProveedor").select2({width:'100%'});
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
                cotizacionProveedor: {required:true, extension: "jpg|jpeg|png|pdf"},
                estatus: {required:true},
                vendorQuote: {required:true},
                dueDate: {required:true},
                detalleMetodoEnvio: {required:true}
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
                cotizacionProveedor: {required: 'La cotización es requerida', extension: 'El formato no es valido'},
                estatus: {required: 'Elige un estatus'},
                vendorQuote: {required:'Vendor Quote requerido'},
                dueDate: {required:'Due Date requerido'},
                detalleMetodoEnvio: {required:'Seleccionar opción'}
            }
            // this.disabled = true;
        });
            
    });
    
    $("#credito").click(function(){
        document.getElementById('mostrarPlazo').style.display = 'block';
    });
    $("#contado").click(function(){
        document.getElementById('mostrarPlazo').style.display = 'none';
    });
    $("#idProveedor").change(function(){
        var idProveedor = $("#idProveedor").val();
        $("#idCuentaProv").load('{{url('comboCuentaProv')}}' + '?r=' + Date.now() + '&idProveedor=' + idProveedor);
    });
    $("#idProveedor").change(function(){
        var idProveedor = $("#idProveedor").val();
        var idFactura = $("#idFactura").val();
        $("#cambiarCodigo").load('{{url('inicialesProv')}}' + '?r=' + Date.now() + '&idProveedor=' + idProveedor + '&idFactura=' + idFactura);
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

    $("#idc").change(function() {
        mensajeCargandoCombo('idp')
        $("#idp").load('{{url('comboca')}}' + '?r=' + Date.now() + '&idc='  + this.options[this.selectedIndex].value) ;
    });

    $("input[type=radio][name=nombreEmpresa]").change(function() {        
        $("#idCuenta").load('{{url('obtenerCuentas')}}' + '?nombreEmpresa='  + this.value) ;
    });

    function validarEmpresa() {
        // alert('miau')
        // if($("input[type=radio][name=nombreEmpresa]").checked){
        //  alert('selec')
        // }else{
        //     alert('nosele')
        // }
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
</script>
<style>
input.error {
    border-color: #f00 !important;
}

small.required {
    color:#f00;
}

textarea.error {
    border-color: #f00 !important;
}

.error {
    color: #f00 !important;
}
</style>
@stop