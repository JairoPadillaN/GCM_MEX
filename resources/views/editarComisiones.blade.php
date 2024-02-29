@extends('principal')
@section('contenido')
<script type="text/javascript">
$(document).ready(function() {
     if ( $(".gastos1").length > 0 ) {
  $(".gastos2").click();
}else{
    $(".cargar").click();
}

$('#agregarArchivo').click(function(e) {
        // var idu = $("#idu").val();
        // var iduAsignado = $("#iduAsignado").val();
        // $("#reporteArchivos").load('{{url('agregarArchivos')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
        e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("formularioFacturas"));
            //formData.append("dato", "valor");
            //formData.append(f.attr("name"), $(this)[0].files[0]);
            $.ajax({
                url: "{{ route('agregarArchivos') }}",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
	            processData: false,
            })
                .done(function(res){
                    console.log("Res-->" + res);
                    $("#reporteArchivos").html(res);
                }).fail( function( jqXHR, textStatus, errorThrown ) {
                    console.log("Error: " + textStatus);
                    console.log("errorThrown: " + errorThrown);
                    console.log("jqXHR: " + jqXHR);
                });


    });

    $(".borrarArchivofac").click(function(){
        // alert("id Refaccion")
        $("#reporteArchivos").load('{{url('borrarArchivosFac')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
        });

         //calculos del carrito de datos de pago facturados
       $("#valorCambio, #valorSaldo").change(function(){
        var valorCambio = $("#valorCambio").val();
        var valorSaldo = $("#valorSaldo").val();
        var total=valorCambio*valorSaldo;
        var subtotal = (total/1.16);
        var iva = (total - subtotal);
        totalF=total.toFixed(2);
        ivaF = iva.toFixed(2);
        subF = subtotal.toFixed(2);
        document.getElementById("resultadoMonto").value = totalF;
        document.getElementById("ivaFacturado").value = ivaF;
        document.getElementById("subFacturado").value = subF;
    });

     //calculos del carrito de datos de pago reales 
     $("#valorSaldoR,#tcPag").change(function(){
        var valorSaldoR = $("#valorSaldoR").val();
        var tipoCam = $("#tcPag").val();
        var totalRe=valorSaldoR * tipoCam; //montoreal
        var subtotalR = (totalRe / 1.16); //subtotalreal
        var ivaR = (totalRe - subtotalR); //ivareal
        totalR=totalRe.toFixed(2);//montoreal
        ivaR = ivaR.toFixed(2); //ivaReal
        subR = subtotalR.toFixed(2); //SUBTOTALR
        document.getElementById("mReal").value = totalR;//montoreal
        document.getElementById("ivaFinal").value = ivaR;
        document.getElementById("subtotalFinal").value = subR;
    });

    // calculos del formulario en datos de pago
    $("#valorCambioFac, #valorSaldoFac").change(function(){
        var valorCambioFac = $("#valorCambioFac").val();
        var valorSaldoFac = $("#valorSaldoFac").val();
        var totalFac=valorCambioFac*valorSaldoFac;
        totalFactura=totalFac.toFixed(2);
        document.getElementById("resultadoMontoFac").value = totalFactura;
    });

    $("#datosPago").click(function(){
        // alert("boton agregar");
        $("#reporteDatosPago").load('{{url('agregarDatosPago')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
        });
    
    $('.borrarDatoPago').click(
        function() {
            $("#reporteDatosPago").load('{{url('borrarDatosPago')}}' + '?' + $(this).closest('form').serialize());
        });
    
});
</script>


<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Asignacion de Comisiones<small> *Campos obligatorios</small></h1>
        </div>
        <div class="panel-body">
            @if(Session::get('sesiontipo')=="Administrador")

            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="cargar" class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                        role="tab" aria-controls="pills-home" aria-selected="true">% Comisiones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-servicio" role="tab"
                        aria-controls="pills-profile" aria-selected="false">Datos del
                        Servicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                        aria-controls="pills-profile" aria-selected="false">Datos de Pago</a>
                </li>

                
               
               
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="col-12">
                            <div class="row align-items-center">
                                <div style="margin: auto; width: 80%">
                                    <h3 style="text-align: center;">Asignación de porcentaje a comisiones</h3><br>
                                    <form action="{{route('crearComision')}}" method="post">
                                        @csrf
                                        <label for="porcentaje" class="sub-title">Porcentaje: </label>
                                        <?php $subtotalFacTotal = 0; ?>
                                        @foreach($consultaDatosPago as $dp)
                                        <?php $subtotalFacTotal = $subtotalFacTotal + $dp->subtotalFinal;  ?>

                                        @endforeach 
                                        <input class="form-control" id="porcentajeInput" type="number" max="100" min="1" name="porcentaje" style="width: 10%; display: inline-block; text-align: right;" value="0">%
                                        <label for="monto" style="padding-left: 10px">Monto: $</label>
                                        <input style="width: 10%; display: inline-block; text-align: right;" class="form-control" type="text" value="{{number_format($subtotalFacTotal,2)}}" readonly>
                                        <input type="hidden" id="montoRealTotal" value="{{$subtotalFacTotal}}">
                                        <label for="monto" style="padding-left: 10px">= $</label>
                                        <input class="form-control" id="montoInput" type="text" style="width: 10%; display: inline-block;" value="" readonly><br>
                                        <label for="servicio" class="sub-title">Empleado</label>
                                        <select class="form-control" name="idUsuario" id="servicios" style="width: 45%; display: inline-block">
                                            
                                            @foreach ($empleados as $empleado)
                                            <option value="{{$empleado->idu}}">{{$empleado->nombreUsuario}}</option>

                                            @endforeach
                                        </select>
                                        <input type="hidden" value="{{$idFactura}}" name="idFactura">
                                        <button class="btn btn-success" type="submit" style="width: 10%;" id="agregarPorcentaje"><span class="glyphicon glyphicon-plus-sign"></span> Agregar</button>
                                    </form>
                                    <hr>
                                    <div id="cargando" style="width: 100%; text-align: center;"></div>
                                    <table class="table table-stripped table-hover" style="100%">
                                        <thead>
                                            <th style="width: 5%">#</th>
                                            <th style="width: 10%">Porcentaje</th>
                                            <th>Empleado</th>
                                            <th style="width: 10%">Monto</th>
                                            <th style="width: 10%">Opciones</th>
                                        </thead>
                                        <tbody >
                                        <?php $totalmontoempleados = 0; 
                                              $acumuladorporcentaje = 0; 
                                              $numporcentaje=0; ?>
                                            @foreach ($comisionesAll as $comision)
                                            <tr>
                                            <?php  $numporcentaje++; ?>
                                            <th>{{$numporcentaje}}</th>
                                            <td style="width: 10%">% {{$comision->porcentaje}}</td>
                                            <?php $acumuladorporcentaje = $acumuladorporcentaje+$comision->porcentaje; ?>
                                            <td>{{$comision->nombreUsuario}}</td>
                                            <td style="width: 10%">$ {{number_format(($subtotalFacTotal*$comision->porcentaje)/100,2)}}</td>
                                            <?php $totalmontoempleados = $totalmontoempleados+($subtotalFacTotal*$comision->porcentaje)/100; ?>
                                            <td style="width: 10%"><form action="/eliminarComision/{{$comision->idc}}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$comision->idc}}">
                                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                            </form></td>
                                                
                                            @endforeach
                                            <input type="hidden" id="acumuladorpercent" value="{{$acumuladorporcentaje}}">
                                            
            
                                        </tbody>
                                        <tfoot id="table-footer"> <th>Total</th><th>% {{number_format($acumuladorporcentaje)}}</th><th></th><th> ${{number_format($totalmontoempleados,2)}}</th><th></th></tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    
            

                </div>


                <!--Primer tab Datos de Factura --->
                <div class="tab-pane fade" id="pills-servicio" role="tabpanel" aria-labelledby="pills-home-tab">

                    <div class="form-group col-md-6">
                        <div class="sub-title">Número de servicio:</div>
                        <div>

                            {{Form::text('idServicios',($consulta->idServicios),['class' => 'form-control', 'readonly'=> 'true'])}}
                            {{Form::hidden('idServicios',$consulta->idServicios)}}
                        </div>
                        <div class="sub-title">Tipo de Servicio:</div>
                        <div>
                            @if($errors->first('tipoServicio'))
                            <i> {{ $errors->first('tipoServicio') }}</i>
                            @endif
                            <select name="tipoServicio" class="form-control rounded-0" readonly>
                                <option value="<?php echo ($consulta->tipoServicio) ?>" disabled="disabled" selected="selected">
                                    <?php echo ($consulta->tipoServicio) ?></option>

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
                        @if($errors->first('nombreEmpresa'))
                        <i> {{ $errors->first('nombreEmpresa') }}</i>
                        @endif
                        <div>

                            @if ($consulta->nombreEmpresa == 'CYM')
                            CYM {{Form::radio('nombreEmpresa','CYM', true, ['checked' => 'checked','readonly'=> 'true']) }}
                            GCM {{Form::radio('nombreEmpresa','GCM',false, ['readonly'=> 'true'])}}
                            SURJA {{Form::radio('nombreEmpresa','SURJA',false, ['readonly'=> 'true'])}}
                            @elseif($consulta->nombreEmpresa == 'GCM')
                            CYM {{Form::radio('nombreEmpresa','CYM', false, ['readonly'=> 'true']) }}
                            GCM {{Form::radio('nombreEmpresa','GCM',true, ['checked' => 'checked','readonly'=> 'true'])}}
                            SURJA {{Form::radio('nombreEmpresa','SURJA',false, ['readonly'=> 'true'])}}
                            @elseif($consulta->nombreEmpresa == 'SURJA')
                            CYM {{Form::radio('nombreEmpresa','CYM', false, ['readonly'=> 'true']) }}
                            GCM {{Form::radio('nombreEmpresa','GCM',false, ['readonly'=> 'true'])}}
                            SURJA {{Form::radio('nombreEmpresa','SURJA',true, ['checked' => 'checked','readonly'=> 'true'])}}
                            @else
                            CYM {{Form::radio('nombreEmpresa','CYM', false, ['readonly'=> 'true']) }}
                            GCM {{Form::radio('nombreEmpresa','GCM',false, ['readonly'=> 'true'])}}
                            SURJA {{Form::radio('nombreEmpresa','SURJA',false, ['readonly'=> 'true'])}}
                            @endif

                        </div>


                        <div class="sub-title">Fecha de subida al portal: </div>
                        <div>
                            @if($errors->first('fechaVencimiento'))
                            <i> {{ $errors->first('fechaVencimiento') }}</i>
                            @endif
                            {{Form::date('fechaVencimiento',$consulta->fechaVencimiento,['class' => 'form-control','readonly'=> 'true'])}}

                        </div>

                        <div class="sub-title">Banco:</div>
                        @if($errors->first('idb'))
                        <i> {{ $errors->first('idb') }}</i>
                        @endif
                        <div>
                        @if($cuantosBancos === 0)
                            <select name='idb' id="idb" class="form-control" readonly>
                            <option value="" disabled="disabled" selected="selected">Seleccione un banco </option>
                            @foreach($consultaBancos as $cB)
                            <option value="{{$cB->idb}}">{{$cB->nombre}}</option>
                            @endforeach
                            </select>
                        @else
                            <select name='idb' id="idb" class="form-control" readonly>
                            <option value="{{$idBan}}">{{$nomban}}</option disabled="disabled" selected="selected">
                            @foreach($consultaBancos as $cB)
                            <option value="{{$cB->idb}}">{{$cB->nombre}}</option>
                            @endforeach
                            </select>
                        @endif
                        </div>

                        <div class="sub-title">*Monto cotización:</div>
                        <div>
                            @if($errors->first('montoFactura'))
                            <i> {{ $errors->first('montoFactura') }}</i>
                            @endif
                            {{Form::text('montoFactura',($consulta->montoFactura),['class' => 'form-control', 'placeholder' => 'Ejemplo: 3000','readonly'=> 'true'])}}
                        </div>
                        <div class="sub-title">IVA:</div>
                        <div>
                            {{Form::text('ivaFactura',($consulta->ivaFactura),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10%','readonly'=> 'true'])}}
                        </div>
                        <div class="sub-title">Retención IVA:</div>
                        <div>
                            {{Form::text('retencionIva',($consulta->retencionIva),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10%','readonly'=> 'true'])}}
                        </div>
                        <div class="sub-title">Total:</div>
                        <div>
                            {{Form::text('totalConIva',($consulta->totalConIva),['class' => 'form-control', 'placeholder' => 'Ejemplo: 1000','readonly'=> 'true'])}}
                        </div>
                        
                    </div>

                    <div class="form-group col-md-6">

                        <div class="sub-title">Número de Factura:</div>
                        <div>

                            {{Form::text('numeroFactura',($consulta->numeroFactura),['class' => 'form-control', 'placeholder' => 'Ejemplo: A0001','readonly'=> 'true'])}}
                            {{Form::hidden('idFactura',$consulta->idFactura)}}
                        </div>

                        <div class="sub-title">Fecha de facturación: </div>
                        <div>
                            @if($errors->first('fechaFactura'))
                            <i> {{ $errors->first('fechaFactura') }}</i>
                            @endif
                            {{Form::date('fechaFactura',$consulta->fechaFactura,['class' => 'form-control', 'readonly'=> 'true'])}}
                        </div>

                        <div class="sub-title">Fecha de pago: </div>
                        <div>
                            @if($errors->first('fechaPago'))
                            <i> {{ $errors->first('fechaPago') }}</i>
                            @endif
                            {{Form::date('fechaPago',$consulta->fechaPago,['class' => 'form-control', 'readonly'=> 'true'])}}
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="sub-title">*Moneda de Pago:</div>
                                @if($errors->first('tipoMoneda'))
                                <i> {{ $errors->first('tipoMoneda') }}</i>
                                @endif
                                <div>

                                    @if ($consulta->tipoMoneda == 'USD')
                                    USD {{Form::radio('tipoMoneda','USD', true, ['checked' => 'checked']) }}
                                    MXN {{Form::radio('tipoMoneda','MXN',false, [])}}
                                    @elseif ($consulta->tipoMoneda == 'MXN')
                                    USD {{Form::radio('tipoMoneda','USD', true, []) }}
                                    MXN {{Form::radio('tipoMoneda','MXN',false, ['checked' => 'checked'])}}
                                    @else
                                    USD {{Form::radio('tipoMoneda','USD')}}
                                    MXN {{Form::radio('tipoMoneda','MXN')}}
                                    @endif

                                </div>


                                <!--RADIO-->
                                <div class="sub-title">*Estatus de Entrega:</div>
                                @if($errors->first('estatusEntrega'))
                                <i> {{ $errors->first('estatusEntrega') }}</i>
                                @endif
                                <div>

                                    @if ($consulta->estatusEntrega == 'Pendiente')
                                    Pendiente
                                    {{Form::radio('estatusEntrega','Pendiente', true, ['checked' => 'checked']) }}
                                    Entregado {{Form::radio('estatusEntrega','Entregado',false, [])}}
                                    @elseif ($consulta->estatusEntrega == 'Entregado')
                                    Pendiente {{Form::radio('estatusEntrega','Pendiente', true, []) }}
                                    Entregado
                                    {{Form::radio('estatusEntrega','Entregado',false, ['checked' => 'checked'])}}
                                    @else
                                    Pendiente {{Form::radio('estatusEntrega','Pendiente')}}
                                    Entregado
                                    {{Form::radio('estatusEntrega','Entregado')}}
                                    @endif

                                </div>
                                <!--FIN DE RADIO-->
                                <br>
                                <!--RADIO-->
                                <div class="sub-title">*Estatus de Pago:</div>
                                @if($errors->first('estatusPago'))
                                <i> {{ $errors->first('estatusPago') }}</i>
                                @endif
                                <div>

                                    @if ($consulta->estatusPago == 'Pagada')
                                    Pagada {{Form::radio('estatusPago','Pagada', true, ['checked' => 'checked']) }}
                                    Pendiente {{Form::radio('estatusPago','Pendiente por pagar',false, [])}}
                                    @else
                                    Pagada {{Form::radio('estatusPago','Pagada') }}
                                    Pendiente{{Form::radio('estatusPago','Pendiente por pagar', true, ['checked' => 'checked'])}}
                                    @endif

                                </div>
                                <!--FIN DE RADIO-->
                            </div>

                            <div class="form-group col-md-6">

                                <!--RADIO-->
                                <div class="sub-title">*Metodo de Pago:</div>
                                @if($errors->first('metodoPago'))
                                <i> {{ $errors->first('metodoPago') }}</i>
                                @endif
                                <div>

                                    @if ($consulta->metodoPago == 'PUE')
                                    PUE {{Form::radio('metodoPago','PUE', true, ['checked' => 'checked']) }}
                                    PPD {{Form::radio('metodoPago','PPD',false, [])}}
                                    @elseif ($consulta->metodoPago == 'PPD')
                                    PUE {{Form::radio('metodoPago','PUE', true, []) }}
                                    PPD {{Form::radio('metodoPago','PPD',false, ['checked' => 'checked'])}}
                                    @else
                                    PUE {{Form::radio('metodoPago','PUE')}}
                                    PPD {{Form::radio('metodoPago','PPD')}}
                                    @endif

                                </div>
                                <!--FIN DE RADIO-->

                                <!--RADIO-->
                                <div class="sub-title">*Estatus Portal:</div>
                                @if($errors->first('estatusPortal'))
                                <i> {{ $errors->first('estatusPortal') }}</i>
                                @endif
                                <div>

                                    @if ($consulta->estatusPortal == 'Pendiente en subir')
                                    Pendiente en subir {{Form::radio('estatusPortal','Pendiente en subir', true, ['checked' => 'checked']) }} <br>
                                    Subido en el portal {{Form::radio('estatusPortal','Subido en el portal',false, [])}}
                                    @else
                                    Pendiente de subir {{Form::radio('estatusPortal','Pendiente en subir', true, []) }}<br>
                                    Subido en el portal {{Form::radio('estatusPortal','Subido en el portal',false, ['checked' => 'checked'])}}
                                    @endif

                                </div>
                                <!--FIN DE RADIO-->

                                <!--RADIO-->
                                <div class="sub-title">*Registrado en CashFlow:</div>
                                @if($errors->first('cashFlow'))
                                <i> {{ $errors->first('cashFlow') }}</i>
                                @endif
                                <div>

                                    @if ($consulta->cashFlow == 'Si')
                                    Si {{Form::radio('cashFlow','Si', true, ['checked' => 'checked']) }}
                                    No {{Form::radio('cashFlow','No',false, [])}}
                                    @elseif ($consulta->cashFlow == 'No')
                                    Si {{Form::radio('cashFlow','Si', true, []) }}
                                    No {{Form::radio('cashFlow','No',false, ['checked' => 'checked'])}}
                                    @else
                                    Si {{Form::radio('cashFlow','Si')}}
                                    No {{Form::radio('cashFlow','No')}}
                                    @endif

                                </div>
                                <!--FIN DE RADIO-->


                            </div>

                        </div>

                        <div class="sub-title">Complemento de pago:</div>
                        <div>
                            @if($errors->first('complementoPago'))
                            <i> {{ $errors->first('complementoPago') }}</i>
                            @endif
                            {{Form::text('complementoPago',($consulta->complementoPago),['class' => 'form-control', 'placeholder' => 'Ejemplo: 350', 'readonly'=> 'true'])}}
                        </div>
                        <div class="sub-title">*Periodo de Pago:</div>
                        <div>
                            @if($errors->first('periodoPago'))
                            <i> {{ $errors->first('periodoPago') }}</i>
                            @endif
                            <select name="periodoPago" class="form-control rounded-0" readonly>
                                <option value="<?php echo ($consulta->periodoPago) ?>" disabled="disabled" selected="selected">
                                    <?php echo ($consulta->periodoPago) ?></option>
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
                        <div class="sub-title">*Cliente:</div>
                        @if($errors->first('idc'))
                        <i> {{ $errors->first('idc') }}</i>
                        @endif
                        <div>
                            <select name='idc' id='idc' class="form-control" rows="5" class="form-control rounded-0" readonly>
                                <option value='{{$idclientesel}}' disabled="disabled" selected="selected">{{$nomcli}}</option>
                                @foreach($cliente as $cli)
                                @if($cli->activo=="si")
                                <option value='{{$cli->idc}}' disabled="disabled" selected="selected">{{$cli->razonSocial}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        

                    </div>

                    <div class="form-group col-md-6">

                        <div class="sub-title">*Sucursal:</div>
                        @if($errors->first('sucursal'))
                        <i> {{ $errors->first('sucursal') }}</i>
                        @endif
                        <div id='combop'>
                            <select name='idSucursal' id='idp' class="form-control">
                                <option value='{{$idSucursal}}' disabled="disabled" selected="selected">{{$nomsuc}}</option>
                            </select>
                        </div>



                    </div>

                    


                    

                 
                    <br><br><br><br>
                    <div class="form-group col-md-12">
                        <div id="reporteDatosPago">
                            <br>
                        @if($cuantosDP == 0)
                             <br>
                            <div class="alert alert-warning" role="alert" align="center">
                                <label for="">Sin datos de pago</label>
                            </div> 

                        @else
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="width:90%"
                                align="center">
                                <thead>
                                    <tr style=" background-color: #78e08f;">
                                        <th style="width: 80px;">Número de factura</th>   
                                        <th style="width: 80px;">Tipo de cambio facturado</th>
                                        <th style="width: 80px;">Saldo facturado</th> 
                                        <th style="width: 80px;">Subtotal facturado</th>
                                        <th style="width: 80px;">IVA facturado</th>
                                        <th style="width: 80px;">Monto facturado</th>
                                        <th style="width: 80px;">Tipo de cambio pagado</th>
                                        <th style="width: 80px;">Saldo real</th> 
                                        <th style="width: 80px;">Subtotal real</th>
                                        <th style="width: 80px;">IVA real</th>
                                        <th style="width: 80px;">Monto real</th>
                                        <th style="width: 200px;">Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($consultaDatosPago as $dp)
                                    <tr>
                                    <td style="text-align: right;"> {{$dp->numeroFacturaDP}}</td>
                                    <td style="text-align: right;">$ {{ number_format($dp->tipoCambioFac, 2 ) }}</td>
                                    <td style="text-align: right;">$ {{ number_format($dp->saldoFac, 2 ) }}</td>
                                    <td style="text-align: right;">$ {{ number_format($dp->subtotalFac, 2) }}</td>
                                    <td style="text-align: right;">$ {{ number_format($dp->ivaFac, 2) }}</td>
                                    <td style="text-align: right;">$ {{ number_format($dp->montoPesosDP, 2) }}</td>
                                    <td style="text-align: right;">$ {{ number_format($dp->tipoCambioPagado, 2) }}</td>
                                    <td style="text-align: right;">$ {{ number_format($dp->saldoReal, 2) }}</td>
                                    <td style="text-align: right;">$ {{ number_format($dp->subtotalFinal, 2) }}</td>
                                    <td style="text-align: right;">$ {{ number_format($dp->ivaFinal, 2) }}</td>
                                    <td style="text-align: right;">$ {{ number_format($dp->montoReal, 2) }}</td>
                                    <td>{{$dp->observaciones}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                        </div>
                        </div>



                </div>


                
                
                <!-- archivos para auditoria -->
                <div class="tab-pane fade" id="archivosAuditoria" role="tabpanel" aria-labelledby="pills-contact-tab">
                    @if($consulta->servicioActivo == 'Cerrado')
                        <!-- cerrado -->
                        <div class="form-group col-md-4">
                        <div class="sub-title">Número de documento (Otros) :</div>
                            <div>
                                @if($errors->first('numeroDocumento'))
                                <i> {{ $errors->first('numeroDocumento') }}</i>
                                @endif
                                {{Form::text('numeroDocumento',$consulta->numeroDocumento,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10','readonly'])}}
                            </div>

                            <div class="sub-title">Anexo 1:</div>
                            <div>
                                @if($errors->first('numeroAnexo1'))
                                <i> {{ $errors->first('numeroAnexo1') }}</i>
                                @endif
                                {{Form::text('numeroAnexo1',$consulta->numeroAnexo1,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10','readonly'])}}
                            </div>

                            <div class="sub-title">Anexo 2:</div>
                            <div>
                                @if($errors->first('numeroAnexo2'))
                                <i> {{ $errors->first('numeroAnexo2') }}</i>
                                @endif
                                {{Form::text('numeroAnexo2',$consulta->numeroAnexo2,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10','readonly'])}}
                            </div>

                            <div class="sub-title">Anexo 3:</div>
                            <div>
                                @if($errors->first('numeroAnexo3'))
                                <i> {{ $errors->first('numeroAnexo3') }}</i>
                                @endif
                                {{Form::text('numeroAnexo3',$consulta->numeroAnexo3,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10','readonly'])}}
                            </div>

                            <div class="sub-title">Anexo 4:</div>
                            <div>
                                @if($errors->first('numeroAnexo4'))
                                <i> {{ $errors->first('numeroAnexo4') }}</i>
                                @endif
                                {{Form::text('numeroAnexo4',$consulta->numeroAnexo4,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10','readonly'])}}
                            </div>

                            <div class="sub-title">Anexo 5:</div>
                            <div>
                                @if($errors->first('numeroAnexo5'))
                                <i> {{ $errors->first('numeroAnexo5') }}</i>
                                @endif
                                {{Form::text('numeroAnexo5',$consulta->numeroAnexo5,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10','readonly'])}}
                            </div>

                            <div class="sub-title">Anexo 6:</div>
                            <div>
                                @if($errors->first('numeroAnexo6'))
                                <i> {{ $errors->first('numeroAnexo6') }}</i>
                                @endif
                                {{Form::text('numeroAnexo6',$consulta->numeroAnexo6,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10','readonly'])}}
                            </div>

                            <div class="sub-title">Anexo 7:</div>
                            <div>
                                @if($errors->first('numeroAnexo7'))
                                <i> {{ $errors->first('numeroAnexo7') }}</i>
                                @endif
                                {{Form::text('numeroAnexo7',$consulta->numeroAnexo7,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10','readonly'])}}
                            </div>

                            <div class="sub-title">Anexo 8:</div>
                            <div>
                                @if($errors->first('numeroAnexo8'))
                                <i> {{ $errors->first('numeroAnexo8') }}</i>
                                @endif
                                {{Form::text('numeroAnexo8',$consulta->numeroAnexo8,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10','readonly'])}}
                            </div>

                            <div class="sub-title">Anexo 9:</div>
                            <div>
                                @if($errors->first('numeroAnexo9'))
                                <i> {{ $errors->first('numeroAnexo9') }}</i>
                                @endif
                                {{Form::text('numeroAnexo9',$consulta->numeroAnexo9,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10','readonly'])}}
                            </div>

                            <div class="sub-title">Anexo 10:</div>
                            <div>
                                @if($errors->first('numeroAnexo10'))
                                <i> {{ $errors->first('numeroAnexo10') }}</i>
                                @endif
                                {{Form::text('numeroAnexo10',$consulta->numeroAnexo10,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10','readonly'])}}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($consultaOtro as $otro)
                                        @if($otro->archivoOtro=='Sin archivo')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo (otros)</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$otro->archivoOtro)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar archivo (otros)</h6>
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </center>
                            </div>

                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($anexos as $a)
                                        @if($a->anexo1=='Sin archivo' || $a->anexo1=='')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo anexo 1</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$a->anexo1)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar anexo 1</h6>
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </center>
                            </div>

                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($anexo2 as $a2)
                                        @if($a2->anexo2=='Sin archivo' || $a2->anexo2=='')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo anexo 2</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$a2->anexo2)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar anexo 2</h6>
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </center>
                            </div>

                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($anexo3 as $a3)
                                        @if($a3->anexo3=='Sin archivo' || $a3->anexo3=='')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo anexo 3</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$a3->anexo3)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar anexo 3</h6>
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </center>
                            </div>

                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($anexo4 as $a4)
                                        @if($a4->anexo4=='Sin archivo' || $a4->anexo4=='')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo anexo 4</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$a4->anexo4)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar anexo 4</h6>
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </center>
                            </div>

                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($anexo5 as $a5)
                                        @if($a5->anexo5=='Sin archivo' || $a5->anexo5=='')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo anexo 5</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$a5->anexo5)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar anexo 5</h6>
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </center>
                            </div>

                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($anexo6 as $a6)
                                        @if($a6->anexo6=='Sin archivo' || $a6->anexo6=='')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo anexo 6</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$a6->anexo6)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar anexo 6</h6>
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </center>
                            </div>

                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($anexo7 as $a7)
                                        @if($a7->anexo7=='Sin archivo' || $a7->anexo7=='')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo anexo 7</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$a7->anexo7)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar anexo 7</h6>
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </center>
                            </div>

                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($anexo8 as $a8)
                                        @if($a8->anexo8=='Sin archivo' || $a8->anexo8=='')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo anexo 8</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$a8->anexo8)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar anexo 8</h6>
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </center>
                            </div>

                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($anexo9 as $a9)
                                        @if($a9->anexo9=='Sin archivo' || $a9->anexo9=='')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo anexo 9</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$a9->anexo9)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar anexo 9</h6>
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </center>
                            </div>

                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($anexo10 as $a10)
                                        @if($a10->anexo10=='Sin archivo' || $a10->anexo10=='')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo anexo 10</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$a10->anexo10)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar anexo 10</h6>
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </center>
                            </div>
                        </div>
                        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                        <center>
                        <a href="{{asset('reporteFacturas')}}"><button type="button"
                                    class="btn btn-default">Cancelar</button></a>
                        </center>
                        
                    @else
                    <div class="form-group col-md-4">
                    <div class="sub-title">Número de documento (Otros) :</div>
                        <div>
                            @if($errors->first('numeroDocumento'))
                            <i> {{ $errors->first('numeroDocumento') }}</i>
                            @endif
                            {{Form::text('numeroDocumento',$consulta->numeroDocumento,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 1:</div>
                        <div>
                            @if($errors->first('numeroAnexo1'))
                            <i> {{ $errors->first('numeroAnexo1') }}</i>
                            @endif
                            {{Form::text('numeroAnexo1',$consulta->numeroAnexo1,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 2:</div>
                        <div>
                            @if($errors->first('numeroAnexo2'))
                            <i> {{ $errors->first('numeroAnexo2') }}</i>
                            @endif
                            {{Form::text('numeroAnexo2',$consulta->numeroAnexo2,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 3:</div>
                        <div>
                            @if($errors->first('numeroAnexo3'))
                            <i> {{ $errors->first('numeroAnexo3') }}</i>
                            @endif
                            {{Form::text('numeroAnexo3',$consulta->numeroAnexo3,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 4:</div>
                        <div>
                            @if($errors->first('numeroAnexo4'))
                            <i> {{ $errors->first('numeroAnexo4') }}</i>
                            @endif
                            {{Form::text('numeroAnexo4',$consulta->numeroAnexo4,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 5:</div>
                        <div>
                            @if($errors->first('numeroAnexo5'))
                            <i> {{ $errors->first('numeroAnexo5') }}</i>
                            @endif
                            {{Form::text('numeroAnexo5',$consulta->numeroAnexo5,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 6:</div>
                        <div>
                            @if($errors->first('numeroAnexo6'))
                            <i> {{ $errors->first('numeroAnexo6') }}</i>
                            @endif
                            {{Form::text('numeroAnexo6',$consulta->numeroAnexo6,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 7:</div>
                        <div>
                            @if($errors->first('numeroAnexo7'))
                            <i> {{ $errors->first('numeroAnexo7') }}</i>
                            @endif
                            {{Form::text('numeroAnexo7',$consulta->numeroAnexo7,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 8:</div>
                        <div>
                            @if($errors->first('numeroAnexo8'))
                            <i> {{ $errors->first('numeroAnexo8') }}</i>
                            @endif
                            {{Form::text('numeroAnexo8',$consulta->numeroAnexo8,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 9:</div>
                        <div>
                            @if($errors->first('numeroAnexo9'))
                            <i> {{ $errors->first('numeroAnexo9') }}</i>
                            @endif
                            {{Form::text('numeroAnexo9',$consulta->numeroAnexo9,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>

                        <div class="sub-title">Anexo 10:</div>
                        <div>
                            @if($errors->first('numeroAnexo10'))
                            <i> {{ $errors->first('numeroAnexo10') }}</i>
                            @endif
                            {{Form::text('numeroAnexo10',$consulta->numeroAnexo10,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div>
                            <center>
                                <div style='text-align'>
                                    @foreach($consultaOtro as $otro)
                                    @if($otro->archivoOtro=='Sin archivo')
                                    <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                    <h6>Sin archivo (otros)</h6>
                                    @else
                                    <a target="_blank" href="{{asset('archivos/'.$otro->archivoOtro)}}">
                                        <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                        <h6>Descargar archivo (otros)</h6>
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </center>
                        </div>

                        <div>
                            <center>
                                <div style='text-align'>
                                    @foreach($anexos as $a)
                                    @if($a->anexo1=='Sin archivo' || $a->anexo1=='')
                                    <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                    <h6>Sin archivo anexo 1</h6>
                                    @else
                                    <a target="_blank" href="{{asset('archivos/'.$a->anexo1)}}">
                                        <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                        <h6>Descargar anexo 1</h6>
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </center>
                        </div>

                        <div>
                            <center>
                                <div style='text-align'>
                                    @foreach($anexo2 as $a2)
                                    @if($a2->anexo2=='Sin archivo' || $a2->anexo2=='')
                                    <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                    <h6>Sin archivo anexo 2</h6>
                                    @else
                                    <a target="_blank" href="{{asset('archivos/'.$a2->anexo2)}}">
                                        <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                        <h6>Descargar anexo 2</h6>
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </center>
                        </div>

                        <div>
                            <center>
                                <div style='text-align'>
                                    @foreach($anexo3 as $a3)
                                    @if($a3->anexo3=='Sin archivo' || $a3->anexo3=='')
                                    <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                    <h6>Sin archivo anexo 3</h6>
                                    @else
                                    <a target="_blank" href="{{asset('archivos/'.$a3->anexo3)}}">
                                        <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                        <h6>Descargar anexo 3</h6>
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </center>
                        </div>

                        <div>
                            <center>
                                <div style='text-align'>
                                    @foreach($anexo4 as $a4)
                                    @if($a4->anexo4=='Sin archivo' || $a4->anexo4=='')
                                    <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                    <h6>Sin archivo anexo 4</h6>
                                    @else
                                    <a target="_blank" href="{{asset('archivos/'.$a4->anexo4)}}">
                                        <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                        <h6>Descargar anexo 4</h6>
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </center>
                        </div>

                        <div>
                            <center>
                                <div style='text-align'>
                                    @foreach($anexo5 as $a5)
                                    @if($a5->anexo5=='Sin archivo' || $a5->anexo5=='')
                                    <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                    <h6>Sin archivo anexo 5</h6>
                                    @else
                                    <a target="_blank" href="{{asset('archivos/'.$a5->anexo5)}}">
                                        <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                        <h6>Descargar anexo 5</h6>
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </center>
                        </div>

                        <div>
                            <center>
                                <div style='text-align'>
                                    @foreach($anexo6 as $a6)
                                    @if($a6->anexo6=='Sin archivo' || $a6->anexo6=='')
                                    <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                    <h6>Sin archivo anexo 6</h6>
                                    @else
                                    <a target="_blank" href="{{asset('archivos/'.$a6->anexo6)}}">
                                        <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                        <h6>Descargar anexo 6</h6>
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </center>
                        </div>

                        <div>
                            <center>
                                <div style='text-align'>
                                    @foreach($anexo7 as $a7)
                                    @if($a7->anexo7=='Sin archivo' || $a7->anexo7=='')
                                    <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                    <h6>Sin archivo anexo 7</h6>
                                    @else
                                    <a target="_blank" href="{{asset('archivos/'.$a7->anexo7)}}">
                                        <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                        <h6>Descargar anexo 7</h6>
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </center>
                        </div>

                        <div>
                            <center>
                                <div style='text-align'>
                                    @foreach($anexo8 as $a8)
                                    @if($a8->anexo8=='Sin archivo' || $a8->anexo8=='')
                                    <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                    <h6>Sin archivo anexo 8</h6>
                                    @else
                                    <a target="_blank" href="{{asset('archivos/'.$a8->anexo8)}}">
                                        <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                        <h6>Descargar anexo 8</h6>
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </center>
                        </div>

                        <div>
                            <center>
                                <div style='text-align'>
                                    @foreach($anexo9 as $a9)
                                    @if($a9->anexo9=='Sin archivo' || $a9->anexo9=='')
                                    <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                    <h6>Sin archivo anexo 9</h6>
                                    @else
                                    <a target="_blank" href="{{asset('archivos/'.$a9->anexo9)}}">
                                        <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                        <h6>Descargar anexo 9</h6>
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </center>
                        </div>

                        <div>
                            <center>
                                <div style='text-align'>
                                    @foreach($anexo10 as $a10)
                                    @if($a10->anexo10=='Sin archivo' || $a10->anexo10=='')
                                    <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                    <h6>Sin archivo anexo 10</h6>
                                    @else
                                    <a target="_blank" href="{{asset('archivos/'.$a10->anexo10)}}">
                                        <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                        <h6>Descargar anexo 10</h6>
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </center>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        @foreach($consultaOtro as $otro)
                        @if($otro->archivoOtro=='Sin archivo')
                        <div class="sub-title">Subir archivos (otros):</div>
                        <div>
                            @if($errors->first('archivoOtro'))
                            <i> {{ $errors->first('archivoOtro') }}</i>
                            @endif
                            {{Form::file('archivoOtro',['class' => 'form-control'])}}
                        </div>
                        @else
                        <div class="sub-title">Actualizar archivo (otros):</div>
                        <div>
                            @if($errors->first('archivoOtro'))
                            <i> {{ $errors->first('archivoOtro') }}</i>
                            @endif
                            {{Form::file('archivoOtro',['class' => 'form-control'])}}
                        </div>
                        @endif
                        @endforeach

                        
                        @foreach($anexos as $a)
                        @if($a->anexo1=='Sin archivo' || $a->anexo1=='')
                        <div class="sub-title">Subir archivos Anexo 1:</div>
                        <div>
                            @if($errors->first('anexo1'))
                            <i> {{ $errors->first('anexo1') }}</i>
                            @endif
                            {{Form::file('anexo1',['class' => 'form-control'])}}
                        </div>
                        @else
                        <div class="sub-title">Actualizar archivo Anexo 1:</div>
                        <div>
                            @if($errors->first('anexo1'))
                            <i> {{ $errors->first('anexo1') }}</i>
                            @endif
                            {{Form::file('anexo1',['class' => 'form-control'])}}
                        </div>
                        @endif
                        @endforeach
                        
                        
                        @foreach($anexo2 as $a2)
                        @if($a2->anexo2=='Sin archivo' || $a2->anexo2=='')
                        <div class="sub-title">Subir archivos Anexo 2</div>
                        <div>
                            @if($errors->first('anexo2'))
                            <i> {{ $errors->first('anexo2') }}</i>
                            @endif
                            {{Form::file('anexo2',['class' => 'form-control'])}}
                        </div>
                        @else
                        <div class="sub-title">Actualizar archivo Anexo 2:</div>
                        <div>
                            @if($errors->first('anexo2'))
                            <i> {{ $errors->first('anexo2') }}</i>
                            @endif
                            {{Form::file('anexo2',['class' => 'form-control'])}}
                        </div>
                        @endif
                        @endforeach

                        @foreach($anexo3 as $a3)
                        @if($a3->anexo3=='Sin archivo' || $a3->anexo3=='')
                        <div class="sub-title">Subir archivos Anexo 3:</div>
                        <div>
                            @if($errors->first('anexo3'))
                            <i> {{ $errors->first('anexo3') }}</i>
                            @endif
                            {{Form::file('anexo3',['class' => 'form-control'])}}
                        </div>
                        @else
                        <div class="sub-title">Actualizar archivo Anexo 3:</div>
                        <div>
                            @if($errors->first('anexo3'))
                            <i> {{ $errors->first('anexo3') }}</i>
                            @endif
                            {{Form::file('anexo3',['class' => 'form-control'])}}
                        </div>
                        @endif
                        @endforeach

                        
                        @foreach($anexo4 as $a4)
                        @if($a4->anexo4=='Sin archivo' || $a4->anexo4=='')
                        <div class="sub-title">Subir archivos Anexo 4:</div>
                        <div>
                            @if($errors->first('anexo4'))
                            <i> {{ $errors->first('anexo4') }}</i>
                            @endif
                            {{Form::file('anexo4',['class' => 'form-control'])}}
                        </div>
                        @else
                        <div class="sub-title">Actualizar archivo Anexo 4:</div>
                        <div>
                            @if($errors->first('anexo4'))
                            <i> {{ $errors->first('anexo4') }}</i>
                            @endif
                            {{Form::file('anexo4',['class' => 'form-control'])}}
                        </div>
                        @endif
                        @endforeach

                        
                        @foreach($anexo5 as $a5)
                        @if($a5->anexo5=='Sin archivo' || $a5->anexo5=='')
                        <div class="sub-title">Subir archivos Anexo 5:</div>
                        <div>
                            @if($errors->first('anexo5'))
                            <i> {{ $errors->first('anexo5') }}</i>
                            @endif
                            {{Form::file('anexo5',['class' => 'form-control'])}}
                        </div>
                        @else
                        <div class="sub-title">Actualizar archivo Anexo 5:</div>
                        <div>
                            @if($errors->first('anexo5'))
                            <i> {{ $errors->first('anexo5') }}</i>
                            @endif
                            {{Form::file('anexo5',['class' => 'form-control'])}}
                        </div>
                        @endif
                        @endforeach

                        @foreach($anexo6 as $a6)
                        @if($a6->anexo6=='Sin archivo' || $a6->anexo6=='')
                        <div class="sub-title">Subir archivos Anexo 6:</div>
                        <div>
                            @if($errors->first('anexo6'))
                            <i> {{ $errors->first('anexo6') }}</i>
                            @endif
                            {{Form::file('anexo6',['class' => 'form-control'])}}
                        </div>
                        @else
                        <div class="sub-title">Actualizar archivo Anexo 6:</div>
                        <div>
                            @if($errors->first('anexo6'))
                            <i> {{ $errors->first('anexo6') }}</i>
                            @endif
                            {{Form::file('anexo6',['class' => 'form-control'])}}
                        </div>
                        @endif
                        @endforeach

                        @foreach($anexo7 as $a7)
                        @if($a7->anexo7=='Sin archivo' || $a7->anexo7=='')
                        <div class="sub-title">Subir archivos Anexo 7:</div>
                        <div>
                            @if($errors->first('anexo7'))
                            <i> {{ $errors->first('anexo7') }}</i>
                            @endif
                            {{Form::file('anexo7',['class' => 'form-control'])}}
                        </div>
                        @else
                        <div class="sub-title">Actualizar archivo Anexo 7:</div>
                        <div>
                            @if($errors->first('anexo7'))
                            <i> {{ $errors->first('anexo7') }}</i>
                            @endif
                            {{Form::file('anexo7',['class' => 'form-control'])}}
                        </div>
                        @endif
                        @endforeach

                        @foreach($anexo8 as $a8)
                        @if($a8->anexo8=='Sin archivo' || $a8->anexo8=='')
                        <div class="sub-title">Subir archivos Anexo 8:</div>
                        <div>
                            @if($errors->first('anexo8'))
                            <i> {{ $errors->first('anexo8') }}</i>
                            @endif
                            {{Form::file('anexo8',['class' => 'form-control'])}}
                        </div>
                        @else
                        <div class="sub-title">Actualizar archivo Anexo 8:</div>
                        <div>
                            @if($errors->first('anexo8'))
                            <i> {{ $errors->first('anexo8') }}</i>
                            @endif
                            {{Form::file('anexo8',['class' => 'form-control'])}}
                        </div>
                        @endif
                        @endforeach

                        @foreach($anexo9 as $a9)
                        @if($a9->anexo9=='Sin archivo' || $a9->anexo9=='')
                        <div class="sub-title">Subir archivos Anexo 9:</div>
                        <div>
                            @if($errors->first('anexo9'))
                            <i> {{ $errors->first('anexo9') }}</i>
                            @endif
                            {{Form::file('anexo9',['class' => 'form-control'])}}
                        </div>
                        @else
                        <div class="sub-title">Actualizar archivo Anexo 9:</div>
                        <div>
                            @if($errors->first('anexo9'))
                            <i> {{ $errors->first('anexo9') }}</i>
                            @endif
                            {{Form::file('anexo9',['class' => 'form-control'])}}
                        </div>
                        @endif
                        @endforeach

                        @foreach($anexo10 as $a10)
                        @if($a10->anexo10=='Sin archivo' || $a10->anexo10=='')
                        <div class="sub-title">Subir archivos Anexo 10:</div>
                        <div>
                            @if($errors->first('anexo10'))
                            <i> {{ $errors->first('anexo10') }}</i>
                            @endif
                            {{Form::file('anexo10',['class' => 'form-control'])}}
                        </div>
                        @else
                        <div class="sub-title">Actualizar archivo Anexo 10:</div>
                        <div>
                            @if($errors->first('anexo10'))
                            <i> {{ $errors->first('anexo10') }}</i>
                            @endif
                            {{Form::file('anexo10',['class' => 'form-control'])}}
                        </div>
                        @endif
                        @endforeach
                    </div>

                    <br><br><br><br><br>
                        
                    @endif
                </div>

                <div class="tab-pane fade" id="skuProveedor" role="tabpanel" aria-labelledby="pills-contact-tab">
                    <br>
                    @if($cuantasPiezas == 0)
                        <div align="center" class="alert alert-info" role="alert">¡No tiene SKU's comprados a proveedor!</div>

                        
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr style="background-color: #D5F5E3;">                                
                                    <th><font SIZE=2>SKU</font></th>
                                    <th><font SIZE=2>Nombre</font></th>
                                    <th><font SIZE=2>Serial/Modelo</font></th>
                                    <th><font SIZE=2>Cantidad</font></th>
                                    <th><font SIZE=2>Unidad de medida</font></th>
                                    <th><font SIZE=2>Factura Proveedor</font></th>
                                    <th><font SIZE=2>Proveedor</font></th>
                                    <th><font SIZE=2>Fecha Compra</font></th> 
                                    <th><font SIZE=2>Facturado a Servicio</font></th> 
                            
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($consultaPiezas as $piezas)
                                <tr>
                                    <td>{{$piezas->codigoRefaccion}}</td>
                                    <td>{{$piezas->nombreRefaccion}}</td>
                                    <td>{{$piezas->serie}}</td>
                                    <td>{{$piezas->cantidadOcupada}}</td>
                                    <td>{{$piezas->presentacion}}</td>
                                    <td>{{$piezas->numeroFacturaProv}}</td>
                                    <td>{{$piezas->razonSocialProv}}</td>
                                    <td>{{$piezas->fechaCompra}}</td>
                                    <td>{{$piezas->numeroFactura}}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            
                <div class="tab-pane fade" id="SKUasignado" role="tabpanel" aria-labelledby="pills-contact-tab">
                    <br>

                    <input type="hidden" value="{{$idCot}}">

                    @if($consu == 'Soporte Técnico')
                    <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr style="background-color: #E8E8E8;">
                                        <th><font SIZE=2>Servicio</font></th>
                                        <th><font SIZE=2>Detalle</font></th>
                                        <th><font SIZE=2>Tiempo de entrega</font></th>
                                        <th><font SIZE=2>Cantidad</font></th>
                                        <th style="width:350px;"><font SIZE=2>Costo unitario</font></th>
                                        <th style="width:350px;"><font SIZE=2>Costo total</font></th>
                                        <th><font SIZE=2>Notas</font></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($serviciosEncotizacion as $sEc)
                                        <tr>                    
                                            <td style="text-align:center;width:300px;">                    
                                                <font SIZE=2>
                                                    {{$sEc->servicioTecnico}}                                        
                                                </font>
                                            </td>
                                            <td style="text-align:center;width:300px;">                    
                                                <font SIZE=2>
                                                    {{$sEc->detalle}}                                        
                                                </font>
                                            </td>
                                            <td style="text-align:center;">
                                                <font SIZE=2>
                                                    @if($sEc->semanasEntrega !="NA")
                                                        {{$sEc->semanasEntrega}}
                                                    @endif                                        
                                                </font>
                                            </td>
                                            <td style="text-align:center;">
                                                <font SIZE=2>
                                                    {{$sEc->cantidad}} {{$sEc->observacionCantidad}}
                                                </font>
                                            </td>                                                            
                                            <td style="text-align:center;">
                                                <font SIZE=2>
                                                    @if($sEc->descuento !="")
                                                        <strike>{{$sEc->tipoMoneda}} $<?php echo number_format ($sEc->montoEquipo,2)?></strike>
                                                        <br>                                                   
                                                        {{$sEc->tipoMoneda}} $<?php $descuentoUnitario = $sEc->montoFinanciamiento / $sEc->cantidad; echo number_format ($descuentoUnitario,2);?>
                                                    @else
                                                        {{$sEc->tipoMoneda}} ${{$sEc->montoEquipo}}
                                                    @endif                                            
                                                </font>
                                            </td>
                                            <td style="text-align:center;">
                                                <font SIZE=2>
                                                    @if($sEc->descuento !="")
                                                        <strike><?php $totalff = $sEc->cantidad * $sEc->montoEquipo; echo $sEc->tipoMoneda . ' $'.$totalff; ?></strike>
                                                        <br>
                                                        - @if($sEc->tipoDescuento == "porcentaje")
                                                            {{$sEc->descuento}} % 
                                                            @else 
                                                            $ {{$sEc->descuento}}
                                                            @endif
                                                        <br>{{$sEc->tipoMoneda}} ${{$sEc->montoFinanciamiento}}
                                                    @else
                                                        {{$sEc->tipoMoneda}} ${{$sEc->montoFinanciamiento}}
                                                    @endif                                            
                                                </font>
                                            </td>
                                            <td>
                                            <font SIZE=2>
                                                {{$sEc->notas}}                                        
                                            </font>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    </div>
                    <!-- </div> -->

                    @elseif($idCot != 0)
                    <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr style="background-color: #E8E8E8;">                                
                                        <th><font SIZE=2>Tipo de equipo</font></th>
                                        <th><font SIZE=2>Descripción</font></th>
                                        <th><font SIZE=2>Equipo</font></th>
                                        <th><font SIZE=2>Modelo</font></th>
                                        <th><font SIZE=2>GCMid Parte</font></th>
                                        <th><font SIZE=2>Tiempo de entrega</font></th>
                                        <th><font SIZE=2>Piezas</font></th>
                                        <th><font SIZE=2>Costo unitario</font></th>
                                        <th><font SIZE=2>Costo total</font></th>
                                        <th><font SIZE=2>Notas</font></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($equiposNuevosYreparados as $equiposNYp)
                                        <tr>
                                            <td style="text-align:center;width:100px;">                    
                                                <font SIZE=2>
                                                    @if($equiposNYp->tipoCotizacion == "reparacion")
                                                        Reparación
                                                    @elseif($equiposNYp->tipoCotizacion == "nuevo")
                                                        Nuevo
                                                    @else
                                                        Refurbished
                                                    @endif
                                                </font>
                                            </td>
                                            <td style="text-align:center;width:200px;">                    
                                                <font SIZE=2>
                                                    {{$equiposNYp->descripcion}}
                                                </font>
                                            </td>
                                            @if($equiposNYp->tipoCotizacion == "reparacion")
                                                <td style="text-align:center;width:200px;">                    
                                                    <font SIZE=2>
                                                        @foreach($datosEquipoCotizado as $dEc)                
                                                            @if($equiposNYp->idEquipos == $dEc->idEquipos)
                                                                {{$dEc->equipo}}
                                                            @endif                
                                                        @endforeach                    
                                                    </font>
                                                </td>
                                                <td style="text-align:center;width:200px;">                    
                                                    <font SIZE=2>
                                                        {{$equiposNYp->modelo}}
                                                    </font>
                                                </td> 
                                                @foreach($equiposReparados as $equipoR)
                                                    @if($equiposNYp->idEquipos == $equipoR->idEquipos)
                                                        <td style="text-align:center;width:200px;">
                                                        <font SIZE=2 color="#2F7DA7">
                                                            <button value="{{$equiposNYp->partida}}" class="verDetallePartida" style="background-color: Transparent;background-repeat:no-repeat;border: none;cursor:pointer;overflow: hidden;">
                                                                {{$equipoR->GCMid}} - {{$equipoR->complementoGCMid}}
                                                            </button>
                                                        </font>
                                                        </td>
                                                        @break
                                                    @endif
                                                @endforeach
                                            @else
                                                <td style="text-align:center;width:200px;">
                                                    <font SIZE=2>
                                                        @foreach($datosEquiposNuevos as $dEn)                
                                                            @if($equiposNYp->idEquipos == $dEn->idEquipos)
                                                                {{$dEn->nombreRefaccion}}
                                                                @break;
                                                            @endif                
                                                        @endforeach 
                                                    </font>
                                                </td>
                                                <td style="text-align:center;width:200px;">                    
                                                    <font SIZE=2>
                                                        {{$equiposNYp->modelo}}
                                                    </font>
                                                </td>
                                                <td style="text-align:center;">
                                                    <font SIZE=2>
                                                        @foreach($datosEquiposNuevos as $dEn)                
                                                            @if($equiposNYp->idEquipos == $dEn->idEquipos)
                                                                {{$dEn->numeroParte}}
                                                                @break;
                                                            @endif                
                                                        @endforeach
                                                    </font>
                                                </td>
                                            @endif
                                            <td style="text-align:center;"><font SIZE=2>{{$equiposNYp->semanasEntrega}}</font></td>
                                            <td style="text-align:center;"><font SIZE=2>{{$equiposNYp->piezas}}</font></td>
                                            <td style="text-align:center;width:350px;">
                                                <font SIZE=2>
                                                    @if($equiposNYp->descuento !="")
                                                        <strike>{{$cotizacionEditar1->tipoMoneda}} $<?php echo number_format ($equiposNYp->montoEquipo,2)?></strike>
                                                        <br>
                                                        {{$cotizacionEditar1->tipoMoneda}} $<?php $descuentoUnitario = $equiposNYp->montoFinanciamiento / $equiposNYp->piezas; echo number_format ($descuentoUnitario,2);?>
                                                    @else
                                                        {{$cotizacionEditar1->tipoMoneda}} $<?php echo number_format ($equiposNYp->montoEquipo,2)?>
                                                    @endif
                                                </font>
                                            </td>
                                            <td style="text-align:center; width:350px;">
                                                <font SIZE=2>
                                                    @if($equiposNYp->descuento !="")
                                                        <strike>{{$cotizacionEditar1->tipoMoneda}} $ <?php $totalff = $equiposNYp->montoEquipo * $equiposNYp->piezas; echo number_format ($totalff,2)?></strike>
                                                        <br>
                                                        - @if($equiposNYp->tipoDescuento == "porcentaje")
                                                            {{$equiposNYp->descuento}}% 
                                                        @else 
                                                            ${{$equiposNYp->descuento}}
                                                        @endif                                                    
                                                        <br> {{$cotizacionEditar1->tipoMoneda}} $<?php echo number_format ($equiposNYp->montoFinanciamiento,2)?>
                                                    @else
                                                        {{$cotizacionEditar1->tipoMoneda}} $<?php echo number_format ($equiposNYp->montoFinanciamiento,2)?>
                                                    @endif
                                                </font>
                                            </td>    
                                            <td>
                                                <font SIZE=2>
                                                    {{$equiposNYp->notas}}
                                                </font>
                                            </td>                                    
                                        </tr>
                                    @endforeach
                                
                                </tbody>
                            </table>
                        </div>
                    <!-- </div> -->
                        @else 
                        <div align="center" class="alert alert-info" role="alert">¡No tiene SKU's asignados a servicio!</div>
                        
                    @endif
                </div>

                <div class="tab-pane fade" id="actividades" role="tabpanel" aria-labelledby="pills-contact-tab">
                    <br><h3>Reporte de Actividades</h3><br><br>
                    @if($cuantosSeguimiento==0)
                        <div align="center" class="alert alert-info" role="alert">¡No tiene Actividades!</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr style="background-color: #ABEBC6">
                                        <th>Folio</th>
                                        <th>Fecha de creación</th>
                                        <th>Asunto</th>
                                        <th>Creado por</th>
                                        <th>Cliente / Sucursal</th>
                                        <th>Periódo de atención</th>
                                        <th>Nivel de atencion</th>
                                        <th>Área responsable</th>
                                        <th>Actividad</th>
                                        <th>Avance</th>
                                        <th>Atendido por</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($seguimiento as $seg)
                                            <tr>

                                                <td>{{$seg->folio}}</td>
                                                <td>{{$seg->fechaCreacionFormato}}</td>
                                                <td>{{$seg->asunto}}</td>
                                                <td>{{$seg->nombreUsuario}} {{$seg->aPaterno}} {{$seg->aMaterno}} </td>
                                                <td>{{$seg->clienteSucursal}}</td>
                                                <td>{{$seg->fechaInicioFormato}} - {{$seg->fechaTerminoFormato}} </td>
                                                <td>{{$seg->importanciaSeguimiento}}</td>
                                                <td>{{$seg->nombreArea}}</td>
                                                <td>{{$seg->nombreActividad}}</td>
                                                
                                                <?php 
                                                
                                                        $atendidoPor =\DB::select("SELECT CONCAT(SUM(r),' de ',COUNT(*)) AS atendidopor
                                                                    FROM (SELECT a.idsegactividad, a.idu,seguimiento($seg->idSegActividad, a.idu ) , IF(seguimiento($seg->idSegActividad, a.idu )>=1,1,0) AS r
                                                                    FROM asignacionseguimientos AS a
                                                                    INNER JOIN usuarios AS u ON u.idu = a.idu
                                                                    WHERE idsegactividad= $seg->idSegActividad) AS t1
                                                                    GROUP BY t1.idsegactividad");
                                                        
                                                        $porcentaje= \DB::select("SELECT t1.idsegactividad,TRUNCATE(AVG(t1.porcentaje),0) AS porcentaje
                                                                    FROM
                                                                    (SELECT idsegactividad, idu,IF(ultimoporcentaje($seg->idSegActividad,idu)IS NULL,0,ultimoporcentaje($seg->idSegActividad,idu)) AS porcentaje
                                                                    FROM asignacionseguimientos
                                                                    WHERE idsegactividad= $seg->idSegActividad) AS t1
                                                                    GROUP BY t1.idsegactividad");
                                                        $cuantoPor=count($porcentaje);
                                                        $cuantoAtendido=count($atendidoPor);
                                                        


                                                // ?>
                                                
                                                @if($cuantoPor=='0')
                                                    <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-danger" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 100%">0% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                @else
                                                
                                                    @if($porcentaje[0]->porcentaje == 0)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-danger" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 100%">0% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 1 && $porcentaje[0]->porcentaje < 11)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 10%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 11 && $porcentaje[0]->porcentaje < 21)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 20%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 21 && $porcentaje[0]->porcentaje < 31)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 30%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    
                                                    @if($porcentaje[0]->porcentaje > 31 && $porcentaje[0]->porcentaje < 41)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 40%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    
                                                    @if($porcentaje[0]->porcentaje > 41 && $porcentaje[0]->porcentaje < 51)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 50%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 51 && $porcentaje[0]->porcentaje < 61)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 60%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 61 && $porcentaje[0]->porcentaje < 71)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 70%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 71 && $porcentaje[0]->porcentaje < 81)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 80%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    
                                                    @if($porcentaje[0]->porcentaje > 81 && $porcentaje[0]->porcentaje < 91)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 90%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    
                                                    @if($porcentaje[0]->porcentaje > 91 && $porcentaje[0]->porcentaje < 100)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 95%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje == 100)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-success" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 100%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                @endif
                                                @if($cuantoAtendido=='0')
                                                <td>0 de 0</td>
                                                @else
                                                <td>{{$atendidoPor[0]->atendidopor}}</td>
                                                @endif
                                                <td>
                                                
                                                <!--
                                                </td>
                                            </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>


                <div class="tab-pane fade" id="ordenCompra" role="tabpanel" aria-labelledby="pills-contact-tab">
                <br><h3>Reporte ordenes de compra</h3><br><br>
                    @if($cuantasCompras==0)
                        <div align="center" class="alert alert-info" role="alert">¡No tiene orden de compra!</div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr style="background-color:#D6EAF8">
                                    <th>Folio</th>
                                    <th>Fecha de la orden</th>
                                    <th>Proveedor</th>
                                    <th>Enviar a</th>
                                    <th>Empresa</th>
                                    <th>Cuenta</th>
                                    <th>SKU</th>
                                    <th>Importe</th>
                                    <th>IVA</th>
                                    <th>ISR</th>
                                    <th>Total</th>
                                    <th>TotalMXN</th>
                                    <th>Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ordenCompra as $compra)
                                <tr>
                                    <td>{{$compra->codigoOrden}}</td>
                                    <td>{{$compra->fechaOrden}}</td>
                                    <td>{{$compra->razonSocialProv}}</td>
                                    <td>{{$compra->sucursal}}</td>
                                    <td>{{$compra->nombreEmpresa}}</td>
                                    <td style="width:150px;">{{$compra->numeroCu}} - {{$compra->nombreCuenta}}</td>
                                    <td></td>
                                    <td style="text-align: right; width:130px;"></td>
                                    <td style="text-align: right;"></td>
                                    <td style="text-align: right;"></td>
                                    <td style="text-align: right; width:130px;"></td>
                                    <td style="text-align: right; width:130px;"></td>
                                    @if($compra->estatus=='Pago programado')
                                        <td>{{$compra->estatus}} / {{$compra->fechaProgPago}}</td>
                                    @else
                                        <td>{{$compra->estatus}}</td>
                                    @endif                                    
                                </tr>
                                @endforeach
                                <tr style="background-color:#FEF9E7">
                                    <td colspan="11" style="text-align: right;"><strong>Total</strong></td>       
                                    <td style="text-align: right; width:130px;"><strong></strong></td>       
                                </tr>
                            </tbody>
                        </table>

                                        
                                
                    </div>
                    @endif
                </div>
<!-- vista de Gastos                        -->
<div class="tab-pane fade" id="gastos" role="tabpanel" aria-labelledby="pills-contact-tab">
                <br><h3>Reporte Gastos</h3><br><br>
                 @if(Session::has('success'))
           
            <div class="alert alert-info gastos1">
                {{Session::get('success')}}
            </div>
            @endif
                    <br><br>
                    <div class="table-responsive">
                    @if($contadorGastos>0)
                        <table class="table table-striped table-bordered table-hover" id="tGastos">
                            <thead>
                                <tr style="background-color:#D6EAF8">
                                    <th>Fecha de Pago</th>
                                    <th>Beneficiario</th>
                                    <th>Forma de Pago</th>
                                    <th>Referencia</th>
                                    <th>Etiqueta</th>
                                    <th>Descripción</th>
                                    <th>Subtotal</th>
                                    <th>IVA %</th>
                                    <th>IVA $</th>
                                    <th>ISR</th>
                                    <th>ISR $</th>
                                    <th>Total $</th>
                                    <th>Opciones</th>                                
                                </tr>
                                <!--<tr style="background-color:#D6EAF8">
                                    <td>Filtrar</td>
                                    <th colspan=3><input type="text" name="filtroBeneficiario" id="filtroBeneficiario" class="form-control"></th>
                                    <th colspan=9></th>                                
                                </tr>-->
                               
                            </thead>
                            <tbody id="tablaGastos">
                               
                           
                                
                                @php
                                    $suma_subtotal = 0;
                                    $suma_iva = 0;
                                    $suma_isr = 0;
                                    $suma_total = 0;
                                @endphp
                                @foreach($gastos as $gastoServicio)
                                @php
                                    $date = date_create($gastoServicio->fecha_pago);                                    
                                @endphp
                                <tr id="filaGastos">
                                    <td>{{date_format($date,'d/m/Y')}}</td>
                                    <td id="celdaBeneficiario">{{$gastoServicio->beneficiario}}</td>
                                    <td>{{$gastoServicio->forma_pago}}</td>
                                    <td id="celdaReferencia">{{$gastoServicio->referencia}}</td>
                                    <td id="celdaEtiqueta">{{$gastoServicio->etiquetas}}</td>
                                    <td id="celdaDescripcion">{{$gastoServicio->descripcion}}</td>
                                    <td align = "right">{{$gastoServicio->moneda}} ${{number_format($gastoServicio->factura,2)}}</td>
                                    <td>{{number_format($gastoServicio->iva)}} %</td>
                                    <td align = "right">${{number_format($gastoServicio->total_iva,2)}}</td>
                                    <td>{{number_format($gastoServicio->isr)}} %</td>
                                    <td align = "right">${{number_format($gastoServicio->total_isr,2)}}</td>
                                    <td align = "right">{{$gastoServicio->moneda}} ${{number_format($gastoServicio->total,2)}}</td>
                                    <td>
                                    @php
                                        $suma_subtotal = $suma_subtotal + $gastoServicio->factura;
                                        $suma_iva = $suma_iva + $gastoServicio->total_iva;
                                        $suma_isr = $suma_isr + $gastoServicio->total_isr;
                                        $suma_total = $suma_total + $gastoServicio->total;
                                    @endphp
                                       <!-- falta conectar estos servicios / acciones -->
                                    @if($gastoServicio->activo=='Si')
                                    
                                                <i class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
                                            </a> <br>
                                            <a href="{{url('editarGastos'.'/'.$gastoServicio->id.'/'.$consulta->idServicios)}}"
                                                class="btn btn-xs btn-info" style="width:67px; margin-top:5px">
                                                <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
                                            </a>
                                        @else
                                            <a href="{{url('baja_logica/'.$gastoServicio->id.'/'.$gastoServicio->id_factura)}}"
                                                class="btn btn-xs btn-warning" style="width:67px">Restaurar</a>
                                        @endif
                                   <!-- acciones de botones -->
                                    </td>
                                </tr>
                                @endforeach  
                            </tbody>
                            <tfoot>
                            <tr style="background-color:#D6EAF8" align = "right">
                                    <th colspan = "5"></th>
                                    <th>Subtotal</th>
                                    <td>${{number_format($suma_subtotal,2)}}</td>
                                    <th>Total IVA</th>
                                    <td>${{number_format($suma_iva,2)}}</td>
                                    <th>Total ISR</th>
                                    <td>${{number_format($suma_isr)}}</td>
                                    <td colspan ="2">${{number_format($suma_total,2)}}</td>
                                </tr>
                            </tfoot>
                        </table>
                        <div id="gastosTable"></div>
                        @else
                            <div align="center" class="alert alert-info" role="alert">¡No tiene gastos!</div>
                        @endif
                         
                        <a href="">
                         <!--  <button type="button" class="btn btn-primary ">Descargar</button> -->
                    </a><br><br>                   
                    </div>
                    <div id="list"></div>
                    <style>
                        .mostar{display:block;}
                        .ocultar{display:none;}
                    </style>
                    <!--<script>

                        var textbuscar = document.getElementById("filtroBeneficiario");
                        textbuscar.onkeyup = function(){
                            buscar(this);
                        }
                        function buscar(inputbuscar){
                            var valorabuscar = (inputbuscar.value).toLowerCase().trim();
                            var tabla_tr = document.getElementById("tGastos").getElementsByTagName("tbody")[0].rows;
                            for(var i=0; i<tabla_tr.length; i++){
                                var tr = tabla_tr[i];
                                var textotr = (tr.innerText).toLowerCase();
                                tr.className = (textotr.indexOf(valorabuscar)>=0)?"mostrar":"ocultar";
                            }
                        }

        
                    </script>-->
                </div>
<!-- termina vista gastos                         -->

<!--Empieza vista Utilidades  -->
<div class="tab-pane fade" id="utilidades" role="tabpanel" aria-labelledby="pills-contact-tab">
                <br><h3>Reporte de Utilidades</h3><br><br>
                 @if(Session::has('success'))
           
            <div class="alert alert-info gastos1">
                {{Session::get('success')}}
            </div>
            @endif
                    
                    <div class="table-responsive">
                    @if(count($ReporteUtilidad, COUNT_NORMAL)>0)
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr style="background-color:#D6EAF8">
                                    <th>Folio</th>
                                    <th>Fecha</th>
                                    <th>Cuenta</th>
                                    <th>Tipo</th>
                                    <th>Beneficiario</th>
                                    <th>Forma de Pago</th>
                                    <th>Importe</th>
                                    <th>IVA </th>
                                    <th>ISR</th>
                                    <th>Total</th>
                                    <th>Total MXN</th>                              
                                </tr>
                                
                               
                            </thead>
                            <tbody>             
                                @php
                                $totalmx = 0;
                                @endphp           
                                @foreach($ReporteUtilidad as $gastoUtilidad)
                                @php
                                    $date = date_create($gastoUtilidad->fechasalida); 
                                @endphp
                                <tr>
                                    <td>{{$gastoUtilidad->clavegasto}}</td>
                                    <td>{{date_format($date, "d M Y")}}</td>
                                    <td>{{$gastoUtilidad->cuentagasto}}</td>
                                    <td>{{$gastoUtilidad->tipo}}</td>
                                    <td>{{$gastoUtilidad->beneficiario}}</td>
                                    <td>{{$gastoUtilidad->formaPago}}</td>
                                    <td align = "right">{{$gastoUtilidad->moneda}} ${{$gastoUtilidad->subTotal,2}}</td>
                                    <td align = "right">${{$gastoUtilidad->ivaTotal}}</td>
                                    <td align = "right">${{$gastoUtilidad->isrTotal}}</td>
                                    <td align = "right">{{$gastoUtilidad->moneda}} ${{$gastoUtilidad->total,2}}</td>
                                    <td align = "right">MXN ${{number_format($gastoUtilidad->totalpesos,2)}}</td>
									@php
                                    $totalmx = $totalmx + $gastoUtilidad->totalpesos;
                                    @endphp
                                </tr>
                                   
                                @endforeach
                                
                             
                            <tfoot style="background-color:#D6EAF8">
                                    <th colspan="7" align = "right">Totales</th>
                                    <th  align = "right"> ${{number_format($totalIva,2)}}</th>
                                    <th  align = "right"> ${{number_format($totalIsr,2)}}</th>                                  
                                    <th  align = "right"></th>                                  
                                    <th  align = "right">{{$gastoUtilidad->moneda}} ${{number_format($totalmx,2)}} </th>
                                    
                            </tfoot>
                           
                        </table>   
                        
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr style="background-color:#D6EAF8">
                                    <th colspan="4">Reporte de Utilidad</th>                            
                                </tr>                              
                            </thead>   
                                @php
                                    $restaUtilidad = $servicioT - $totalmx;
                                @endphp                          
                            <tbody>
                                <tr>
                                    <th>Total del Servicio</th>
                                    <th>MXN ${{number_format($servicioT,2)}}</th>
                                </tr>
                                <tr>
                                    <th>Total de Gastos</th>
                                    <th>MXN ${{number_format($totalmx,2)}}</th>
                                </tr>
                            </tbody>                                
                            <tfoot>
                                <tr style="background-color:#D6EAF8">
                                    <th>Diferencia </th>
                                    <th>MXN ${{number_format($restaUtilidad,2)}}</th>
                                   
                                </tr>
                            </tfoot>
                        </table>   
                    @else
                        <div align="center" class="alert alert-info" role="alert">¡No hay utilidades!</div>
                    @endif    
                    </a><br><br>                   
                    </div>
                    
                   
                </div>
<!-- Termina vista Utilidades --> 
        
</div><!-------div agregado 151121--->
        @endif
        
<!-- termina vista admin             -->

        @if(Session::get('sesiontipo')=="Vendedor")

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
                    <a class="nav-link" class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact"
                        role="tab" aria-controls="pills-contact" aria-selected="false">Archivos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#actividades" role="tab"
                        aria-controls="pills-contact" aria-selected="false">Actividades</a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">


                <!--Primer tab Datos de Factura --->
                <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                    <div class="form-group col-md-6">
                        <div class="sub-title">Número de servicio:</div>
                        <div>

                            {{Form::text('idServicios',($consulta->idServicios),['class' => 'form-control', 'readonly'=> 'true'])}}
                            {{Form::hidden('idServicios',$consulta->idServicios)}}
                        </div>
                        <div class="sub-title">Tipo de Servicio:</div>
                        <div>
                            {{Form::text('tipoServicio',($consulta->tipoServicio),['class' => 'form-control', 'readonly'=> 'true'])}}
                            
                        </div>
                        <div class="sub-title">Selecciona el nombre de la empresa:</div>
                        <div>
                        {{Form::text('nombreEmpresa',($consulta->nombreEmpresa),['class' => 'form-control', 'readonly'=> 'true'])}}
                        </div>
                        <div class="sub-title">Fecha de subida al portal: </div>
                        <div>
                            {{Form::text('fechaVencimiento',$consulta->fechaVencimiento,['class' => 'form-control','readonly'=> 'true'])}}
                        </div>

                        
                        <div class="sub-title">*Banco:</div>
                        <div>
                        {{Form::hidden('idb',$consulta->idb)}}
                        <input type="text" name="idClienteSel" class="form-control" readonly="true" value="{{$nomban}}">
                        </div>
                        

                        <div class="sub-title">*Monto cotización:</div>
                        <div>
                            {{Form::text('montoFactura',($consulta->montoFactura),['class' => 'form-control','readonly'=> 'true', 'placeholder' => 'Ejemplo: 3000'])}}
                        </div>
                        
                        <div class="sub-title">*Periodo de Pago:</div>
                        <div>
                        {{Form::text('periodoPago',($consulta->periodoPago),['class' => 'form-control','readonly'=> 'true'])}}
                        </div>

                    </div>

                    <div class="form-group col-md-6">

                        <div class="sub-title">Número de Factura:</div>
                        <div>

                            {{Form::text('numeroFactura',($consulta->numeroFactura),['class' => 'form-control','readonly'=> 'true'])}}
                            {{Form::hidden('idFactura',$consulta->idFactura)}}
                        </div>

                        <div class="sub-title">*Fecha de facturación: </div>
                        <div>
                            {{Form::text('fechaFactura',$consulta->fechaFactura,['class' => 'form-control','readonly'=> 'true'])}}
                        </div>

                        <div class="sub-title">*Fecha de pago: </div>
                        <div>
                            {{Form::text('fechaPago',$consulta->fechaPago,['class' => 'form-control','readonly'=> 'true'])}}
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="sub-title">*Moneda de Pago:</div>
                                <div>
                                {{Form::text('tipoMoneda',$consulta->tipoMoneda,['class' => 'form-control','readonly'=> 'true'])}}
                                </div>


                                <!--RADIO-->
                                <div class="sub-title">*Estatus de Entrega:</div>
                                <div>
                                {{Form::text('estatusEntrega',$consulta->estatusEntrega,['class' => 'form-control','readonly'=> 'true'])}}
                                </div>
                                <!--FIN DE RADIO-->
                                
                                <!--RADIO-->
                                <div class="sub-title">*Estatus de Pago:</div>
                                <div>
                                {{Form::text('estatusPago',$consulta->estatusPago,['class' => 'form-control','readonly'=> 'true'])}}
                                </div>
                                <!--FIN DE RADIO-->
                            </div>

                            <div class="form-group col-md-6">

                                <!--RADIO-->
                                <div class="sub-title">*Metodo de Pago:</div>
                                <div>
                                {{Form::text('metodoPago',$consulta->metodoPago,['class' => 'form-control','readonly'=> 'true'])}}
                                </div>
                                <!--FIN DE RADIO-->

                                <!--RADIO-->
                                <div class="sub-title">*Estatus Portal:</div>
                                <div>
                                {{Form::text('estatusPortal',$consulta->estatusPortal,['class' => 'form-control','readonly'=> 'true'])}}
                                </div>
                                <!--FIN DE RADIO-->

                                <!--RADIO-->
                                <div class="sub-title">*Registrado en CashFlow:</div>
                                <div>
                                {{Form::text('cashFlow',$consulta->cashFlow,['class' => 'form-control','readonly'=> 'true'])}}
                                </div>
                                <!--FIN DE RADIO-->
                            </div>

                        </div>

                        <div class="sub-title">Complemento de pago:</div>
                        <div>
                            {{Form::text('complementoPago',($consulta->complementoPago),['class' => 'form-control','readonly'=> 'true'])}}
                        </div>

                    </div>


                </div>
                <!--Segundo tab Datos de pago --->
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="form-group col-md-6">
                        <div class="sub-title">*Cliente:</div>
                        <div>
                            {{Form::hidden('idc',$consulta->idc)}}
                            <input type="text" name="idClienteSel" class="form-control" readonly="true" value="{{$nomcli}}">
                        </div>
                        <div class="sub-title">Tipo de cambio facturado:</div>
                        <div>
                            {{Form::text('cambioFactura',($consulta->cambioFactura),['class' => 'form-control', 'readonly' => 'true'])}}
                        </div>



                        <div class="sub-title">Monto Pesos:</div>
                        <div>
                            @if($errors->first('montoPesos'))
                            <i> {{ $errors->first('montoPesos') }}</i>
                            @endif
                            {{Form::text('montoPesos',($consulta->montoPesos),['class' => 'form-control',  'readonly' => 'true'])}}
                        </div>

                    </div>

                    <div class="form-group col-md-6">

                        <div class="sub-title">*Sucursal:</div>
                        <div id='combop'>
                        {{Form::hidden('idSucursal',$consulta->idSucursal)}}
                        <input type="text" name="idSucursalSel" class="form-control" readonly="true"  value="{{$nomsuc}}">
                        </div>


                        <div class="sub-title">*Saldo:</div>
                        <div>
                            @if($errors->first('saldo'))
                            <i> {{ $errors->first('saldo') }}</i>
                            @endif
                            {{Form::text('saldo',($consulta->saldo),['class' => 'form-control', 'readonly' => 'true'])}}
                        </div>

                        <div class="sub-title">Tipo de cambio pagado:</div>
                        <div>
                            @if($errors->first('cambioReal'))
                            <i> {{ $errors->first('cambioReal') }}</i>
                            @endif
                            {{Form::text('cambioReal',($consulta->cambioReal),['class' => 'form-control', 'readonly' => 'true'])}}
                        </div>

                    </div>

                    <div class="sub-title">Observaciones:</div>
                    <div>
                        @if($errors->first('observacionesFactura'))
                        <i> {{ $errors->first('observacionesFactura') }}</i>
                        @endif
                        <textarea name="observacionesFactura" rows="5" class="form-control rounded-0" readonly="true"
                            placeholder="Escribe las observaciones que consideres necesarias.">{!!($consulta->observacionesFactura)!!}</textarea>
                    </div>


                </div>


                <!--tercer tab Archivos --->
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                   
                    @if($consulta->servicioActivo == 'Cerrado')
                        <div class="row">
                            <div class="col-lg-4">
                            <div class="sub-title">Asignar número de cotización:</div>
                            <div>
                                @if($errors->first('idCotizacion'))
                                <i> {{ $errors->first('idCotizacion') }}</i>
                                @endif
                                @if($consulta->tipoServicio == 'Soporte Técnico')
                                    <select name='idCotizacion' id="idCotizacion" class="form-control">
                                    @foreach($consultaServicioTecnico as $cs)
                                    <option value="{{$cs->idCotizacionServicios}}" disabled="disabled" selected="selected">{{$cs->numeroCotizacion}}</option>
                                    @endforeach
                                    </select>                          
                                @else
                                    <div id='combo'>
                                    @if($cuantos === 0)
                                        <select name='idCotizacion' id="idCotizacion" class="form-control" readonly>
                                        <option value="">Seleccione </option>
                                        @foreach($consultaCot2 as $cC2)
                                        <option value="{{$cC2->idCotizacion}}" disabled="disabled" selected="selected">{{$cC2->numeroCotizacion}}</option>
                                        @endforeach
                                        </select>
                                    @else
                                        <select name='idCotizacion' id="idCotizacion" class="form-control" readonly>
                                        <option value="{{$idCot}}">{{$nomCot}}</option>
                                        @foreach($consultaCot2 as $cC2)
                                        <option value="{{$cC2->idCotizacion}}" disabled="disabled" selected="selected">{{$cC2->numeroCotizacion}}</option>
                                        @endforeach
                                        </select>
                                        <input type="hidden" name="idCot2" value='{{$idCot}}'>
                                    @endif
                                    
                                    </div> 
                                @endif
                            </div>
                            </div>
                            <br><br>
                            <div class="col-lg-4">
                            @if($consulta->tipoServicio == 'Soporte Técnico')
                                @if($idCoti != 0)
                                <div id="cotizacion">
                                    <center>
                                        <div style='text-align'>
                                            @foreach($archivoServTec as $archivo2)
                                            @if($archivo2->archivoCotizacion=='Sin archivo' || $archivo2->archivoCotizacion=='')
                                            <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                            <h6>Sin archivo cotización</h6>
                                            @else
                                            <a target="_blank" href="{{asset('archivos/'.$archivo2->archivoCotizacion)}}">
                                                <img src="{{asset('img/archivosi.png')}}" height=50 width=50 >
                                                <h6>Descargar cotización</h6>
                                            </a>
                                            @endif
                                            @endforeach
                                        </div>
                                    </center>
                                </div>
                                @endif
                            @else
                                <div id="cotizacion">
                                    <center>
                                        <div style='text-align'>
                                            @foreach($archivo as $archivo)
                                            @if($archivo->archivoCotizacion=='Sin archivo' || $archivo->archivoCotizacion=='')
                                            <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                            <h6>Sin archivo cotización</h6>
                                            @else
                                            <a target="_blank" href="{{asset('archivos/'.$archivo->archivoCotizacion)}}">
                                                <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                                <h6>Descargar cotización</h6>
                                            </a>
                                            @endif
                                            @endforeach
                                        </div>
                                    </center>
                            
                                </div>
                            @endif
                                </div>
                        </div>
                        <br><br>
                        <div class="form-group col-md-4">
                            <div class="sub-title">Orden de Compra:</div>
                            <div>
                                @if($errors->first('ordenCompra'))
                                <i> {{ $errors->first('ordenCompra') }}</i>
                                @endif
                                {{Form::text('ordenCompra',($consulta->ordenCompra),['class' => 'form-control', 'placeholder' => 'Ejemplo: AC286639','readonly'])}}
                            </div>
                            <br><br>

                            <div class="sub-title">Factura:</div>
                            <div>
                                @if($errors->first('factura'))
                                <i> {{ $errors->first('factura') }}</i>
                                @endif
                                {{Form::text('factura',($consulta->factura),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10','readonly'])}}
                            </div>


                            <br><br><br><br>

                            <div class="sub-title">Comprobante de pago:</div>
                            <div>
                                @if($errors->first('comprobantePago'))
                                <i> {{ $errors->first('comprobantePago') }}</i>
                                @endif
                                {{Form::text('comprobantePago',($consulta->comprobantePago),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10','readonly'])}}
                            </div>


                            <div class="sub-title">Número de remisión:</div>
                            <div>
                                @if($errors->first('numeroRemision'))
                                <i> {{ $errors->first('numeroRemision') }}</i>
                                @endif
                                {{Form::text('numeroRemision',($consulta->numeroRemision),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10','readonly'])}}
                            </div>

                            <div class="sub-title">Número de entrada:</div>
                            <div>
                                @if($errors->first('numeroEntrada'))
                                <i> {{ $errors->first('numeroEntrada') }}</i>
                                @endif
                                {{Form::text('numeroEntrada',($consulta->numeroEntrada),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10','readonly'])}}
                            </div>
                            <div class="sub-title">ADENDA:</div>
                            <div>
                                @if($errors->first('adenda'))
                                <i> {{ $errors->first('adenda') }}</i>
                                @endif
                                {{Form::text('adenda',$consulta->adenda,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10','readonly'])}}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($consultaArchivo as $archi)
                                        @if($archi->archivo=='Sin archivo')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo Orden de Compra</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$archi->archivo)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar Orden de Compra</h6>
                                        </a>
                                        @endif
                                        @endforeach

                                    </div>
                                </center>
                            </div>
                            <br><br>
                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($consultaFactura as $fac)
                                        @if($fac->archivoFactura=='Sin archivo')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo Factura</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$fac->archivoFactura)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar Factura</h6>
                                        </a>
                                        @endif
                                        @endforeach



                                    </div>
                                </center>
                            </div>


                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($consultaxml as $xml)
                                        @if($xml->xmlFactura=='Sin archivo')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo XML de la Factura</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$xml->xmlFactura)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar XML de la Factura</h6>
                                        </a>
                                        @endif
                                        @endforeach



                                    </div>
                                </center>
                            </div>


                            <br>
                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($consultaPago as $pago)
                                        @if($pago->archivoPago=='Sin archivo')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo Comprobante de Pago</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$pago->archivoPago)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar Comprobante de Pago</h6>
                                        </a>
                                        @endif
                                        @endforeach



                                    </div>
                                </center>
                            </div>

                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($consultaRemision as $remision)
                                        @if($remision->archivoRemision=='Sin archivo')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo Comprobante de remisión</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$remision->archivoRemision)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar Comprobante de remisión</h6>
                                        </a>
                                        @endif
                                        @endforeach



                                    </div>
                                </center>
                            </div>
                            <br><br><br>
                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($consultaAdenda as $adenda)
                                        @if($adenda->archivoAdenda=='Sin archivo')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo ADENDA</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$adenda->archivoAdenda)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar ADENDA</h6>
                                        </a>
                                        @endif
                                        @endforeach



                                    </div>
                                </center>
                            </div>
                        </div>
                        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                        <center>
                            <a href="{{asset('reporteFacturas')}}"><button type="button"
                                    class="btn btn-default">Cancelar</button></a>
                        </center>
                        
                        <!-- </div> -->



                    @else
                        <div class="row">
                            <div class="col-lg-4">
                            <div class="sub-title">Asignar número de cotización:</div>
                            <div>
                                @if($errors->first('idCotizacion'))
                                <i> {{ $errors->first('idCotizacion') }}</i>
                                @endif

                            @if($consulta->tipoServicio == 'Soporte Técnico')
                                    <select name='idCotizacion' id="idCotizacion" class="form-control">
                                    @foreach($consultaServicioTecnico as $cs)
                                    <option value="{{$cs->idCotizacionServicios}}" disabled="disabled" selected="selected">{{$cs->numeroCotizacion}}</option>
                                    @endforeach
                                    </select>                          
                            @else
                                <div id='combo'>
                                @if($cuantos === 0)
                                    <select name='idCotizacion' id="idCotizacion" class="form-control">
                                    <option value="">Seleccione </option>
                                    @foreach($consultaCot2 as $cC2)
                                    <option value="{{$cC2->idCotizacion}}" disabled="disabled" selected="selected">{{$cC2->numeroCotizacion}}</option>
                                    @endforeach
                                    </select>
                                @else
                                    <select name='idCotizacion' id="idCotizacion" class="form-control">
                                    <option value="{{$idCot}}">{{$nomCot}}</option>
                                    @foreach($consultaCot2 as $cC2)
                                    <option value="{{$cC2->idCotizacion}}" disabled="disabled" selected="selected">{{$cC2->numeroCotizacion}}</option>
                                    @endforeach
                                    </select>
                                    <input type="hidden" name="idCot2" value='{{$idCot}}'>
                                @endif
                                
                                </div> 
                            @endif
                            </div>
                            </div>
                            <br><br>
                            <div class="col-lg-4">
                            @if($consulta->tipoServicio == 'Soporte Técnico')
                                @if($idCoti != 0)
                                <div id="cotizacion">
                                    <center>
                                        <div style='text-align'>
                                            @foreach($archivoServTec as $archivo2)
                                            @if($archivo2->archivoCotizacion=='Sin archivo' || $archivo2->archivoCotizacion=='')
                                            <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                            <h6>Sin archivo cotización</h6>
                                            @else
                                            <a target="_blank" href="{{asset('archivos/'.$archivo2->archivoCotizacion)}}">
                                                <img src="{{asset('img/archivosi.png')}}" height=50 width=50 >
                                                <h6>Descargar cotización</h6>
                                            </a>
                                            @endif
                                            @endforeach
                                        </div>
                                    </center>
                                </div>
                                @endif
                            @else
                                <div id="cotizacion">
                                        <center>
                                            <div style='text-align'>
                                                @foreach($archivo as $archivo)
                                                @if($archivo->archivoCotizacion=='Sin archivo' || $archivo->archivoCotizacion=='')
                                                <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                                <h6>Sin archivo cotización</h6>
                                                @else
                                                <a target="_blank" href="{{asset('archivos/'.$archivo->archivoCotizacion)}}">
                                                    <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                                    <h6>Descargar cotización</h6>
                                                </a>
                                                @endif
                                                @endforeach
                                            </div>
                                        </center>
                                
                                </div>
                                @endif
                                </div>
                        </div>
                        <br><br>
                        <div class="form-group col-md-4">
                            <div class="sub-title">Orden de Compra:</div>
                            <div>
                                @if($errors->first('ordenCompra'))
                                <i> {{ $errors->first('ordenCompra') }}</i>
                                @endif
                                {{Form::text('ordenCompra',($consulta->ordenCompra),['class' => 'form-control', 'placeholder' => 'Ejemplo: AC286639'])}}
                            </div>
                            <br><br>

                            <div class="sub-title">Factura:</div>
                            <div>
                                @if($errors->first('factura'))
                                <i> {{ $errors->first('factura') }}</i>
                                @endif
                                {{Form::text('factura',($consulta->factura),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                            </div>


                            <br><br><br><br>

                            <div class="sub-title">Comprobante de pago:</div>
                            <div>
                                @if($errors->first('comprobantePago'))
                                <i> {{ $errors->first('comprobantePago') }}</i>
                                @endif
                                {{Form::text('comprobantePago',($consulta->comprobantePago),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                            </div>


                            <div class="sub-title">Número de remisión:</div>
                            <div>
                                @if($errors->first('numeroRemision'))
                                <i> {{ $errors->first('numeroRemision') }}</i>
                                @endif
                                {{Form::text('numeroRemision',($consulta->numeroRemision),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                            </div>

                            <div class="sub-title">Número de entrada:</div>
                            <div>
                                @if($errors->first('numeroEntrada'))
                                <i> {{ $errors->first('numeroEntrada') }}</i>
                                @endif
                                {{Form::text('numeroEntrada',($consulta->numeroEntrada),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                            </div>
                            <div class="sub-title">ADENDA:</div>
                            <div>
                                @if($errors->first('adenda'))
                                <i> {{ $errors->first('adenda') }}</i>
                                @endif
                                {{Form::text('adenda',$consulta->adenda,['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($consultaArchivo as $archi)
                                        @if($archi->archivo=='Sin archivo')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo Orden de Compra</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$archi->archivo)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar Orden de Compra</h6>
                                        </a>
                                        @endif
                                        @endforeach

                                    </div>
                                </center>
                            </div>
                            <br><br>
                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($consultaFactura as $fac)
                                        @if($fac->archivoFactura=='Sin archivo')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo Factura</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$fac->archivoFactura)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar Factura</h6>
                                        </a>
                                        @endif
                                        @endforeach



                                    </div>
                                </center>
                            </div>


                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($consultaxml as $xml)
                                        @if($xml->xmlFactura=='Sin archivo')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo XML de la Factura</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$xml->xmlFactura)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar XML de la Factura</h6>
                                        </a>
                                        @endif
                                        @endforeach



                                    </div>
                                </center>
                            </div>


                            <br>
                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($consultaPago as $pago)
                                        @if($pago->archivoPago=='Sin archivo')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo Comprobante de Pago</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$pago->archivoPago)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar Comprobante de Pago</h6>
                                        </a>
                                        @endif
                                        @endforeach



                                    </div>
                                </center>
                            </div>

                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($consultaRemision as $remision)
                                        @if($remision->archivoRemision=='Sin archivo')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo Comprobante de remisión</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$remision->archivoRemision)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar Comprobante de remisión</h6>
                                        </a>
                                        @endif
                                        @endforeach



                                    </div>
                                </center>
                            </div>
                            <br><br><br>
                            <div>
                                <center>
                                    <div style='text-align'>
                                        @foreach($consultaAdenda as $adenda)
                                        @if($adenda->archivoAdenda=='Sin archivo')
                                        <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                        <h6>Sin archivo ADENDA</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$adenda->archivoAdenda)}}">
                                            <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                            <h6>Descargar ADENDA</h6>
                                        </a>
                                        @endif
                                        @endforeach



                                    </div>
                                </center>
                            </div>
                        </div>

                        <div class="form-group col-md-4">


                            @foreach($consultaArchivo as $archi)
                            @if($archi->archivo=='Sin archivo')
                            <div class="sub-title">Subir Orden de Compra:</div>
                            <div>
                                @if($errors->first('archivo'))
                                <i> {{ $errors->first('archivo') }}</i>
                                @endif
                                {{Form::file('archivo',['class' => 'form-control'])}}
                            </div>
                            @else
                            <div class="sub-title">Actualizar Orden de Compra:</div>
                            <div>
                                @if($errors->first('archivo'))
                                <i> {{ $errors->first('archivo') }}</i>
                                @endif
                                {{Form::file('archivo',['class' => 'form-control'])}}
                            </div>
                            @endif
                            @endforeach

                            <br>
                            @foreach($consultaFactura as $fac)
                            @if($fac->archivoFactura=='Sin archivo')
                            <div class="sub-title">*Subir el archivo de la Factura:</div>
                            <div>
                                @if($errors->first('archivoFactura'))
                                <i> {{ $errors->first('archivoFactura') }}</i>
                                @endif
                                {{Form::file('archivoFactura',['class' => 'form-control'])}}
                            </div>
                            @else
                            <div class="sub-title">Actualizar el archivo de la Factura:</div>
                            <div>
                                @if($errors->first('archivoFactura'))
                                <i> {{ $errors->first('archivoFactura') }}</i>
                                @endif
                                {{Form::file('archivoFactura',['class' => 'form-control'])}}
                            </div>

                            @endif
                            @endforeach

                            @foreach($consultaxml as $xml)
                            @if($xml->xmlFactura=='Sin archivo')
                            <div class="sub-title">Subir el archivo XML de la Factura:</div>
                            <div>
                                @if($errors->first('xmlFactura'))
                                <i> {{ $errors->first('xmlFactura') }}</i>
                                @endif
                                {{Form::file('xmlFactura',['class' => 'form-control'])}}
                            </div>
                            @else
                            <div class="sub-title">Actualizar el archivo XML de la Factura:</div>
                            <div>
                                @if($errors->first('xmlFactura'))
                                <i> {{ $errors->first('xmlFactura') }}</i>
                                @endif
                                {{Form::file('xmlFactura',['class' => 'form-control'])}}
                            </div>

                            @endif
                            @endforeach

                            <br><br>
                            @foreach($consultaPago as $pago)
                            @if($pago->archivoPago=='Sin archivo')
                            <div class="sub-title">Subir el archivo de comprobante de pago:</div>
                            <div>
                                @if($errors->first('archivoPago'))
                                <i> {{ $errors->first('archivoPago') }}</i>
                                @endif
                                {{Form::file('archivoPago',['class' => 'form-control'])}}
                            </div>
                            @else
                            <div class="sub-title">Actualizar el comprobante de pago:</div>
                            <div>
                                @if($errors->first('archivoPago'))
                                <i> {{ $errors->first('archivoPago') }}</i>
                                @endif
                                {{Form::file('archivoPago',['class' => 'form-control'])}}
                            </div>
                            @endif
                            @endforeach

                            <br>
                            @foreach($consultaRemision as $remision)
                            @if($remision->archivoRemision=='Sin archivo')
                            <div class="sub-title">Subir el comprobante de remisión:</div>
                            <div>
                                @if($errors->first('archivoRemision'))
                                <i> {{ $errors->first('archivoRemision') }}</i>
                                @endif
                                {{Form::file('archivoRemision',['class' => 'form-control'])}}
                            </div>
                            @else
                            <div class="sub-title">Actualizar el comprobante de remisión:</div>
                            <div>
                                @if($errors->first('archivoRemision'))
                                <i> {{ $errors->first('archivoRemision') }}</i>
                                @endif
                                {{Form::file('archivoRemision',['class' => 'form-control'])}}
                            </div>
                            @endif
                            @endforeach

                            <br><br>
                            @foreach($consultaAdenda as $adenda)
                            @if($adenda->archivoAdenda=='Sin archivo')
                            <div class="sub-title">Subir ADENDA:</div>
                            <div>
                                @if($errors->first('archivoAdenda'))
                                <i> {{ $errors->first('archivoAdenda') }}</i>
                                @endif
                                {{Form::file('archivoAdenda',['class' => 'form-control'])}}
                            </div>
                            @else
                            <div class="sub-title">Actualizar ADENDA:</div>
                            <div>
                                @if($errors->first('archivoAdenda'))
                                <i> {{ $errors->first('archivoAdenda') }}</i>
                                @endif
                                {{Form::file('archivoAdenda',['class' => 'form-control'])}}
                            </div>
                            @endif
                            @endforeach
                            <br><br><br><br><br>
                        </div>
                        

                        <br><br>
                        <div class="form-group col-md-6">
                    @if($consulta->servicioActivo == 'Activo' || $consulta->servicioActivo == '')
                        <center><div class="sub-title">Agregar archivos:</div></center>
                        
                        <div class="sub-title">Agregado por:</div>
                        <div>
                            <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu')!!}"></input>
                            <input type="text" name="nombreUsuario" class="form-control" readonly="true" value="{!! Session::get('sesionname')!!} {!! Session::get('sesionpaterno')!!} {!!
                                    Session::get('sesionmaterno')!!}"></input>
                        </div>

                        <div class="sub-title">Tipo de archivo:</div>
                        <select name='tipoArchivo' id='tipoArchivo' class="form-control">
                                <option value='' disabled="disabled" selected="selected">Selecciona un tipo de archivo</option>
                                <option value="Adenda">Adenda</option>
                                <option value="Comprobante de pago">Comprobante de pago</option>
                                <option value="Factura">Factura</option>
                                <option value="Número de entrada">Número de entrada</option>
                                <option value="Número de remisión">Número de remisión</option>
                                <option value="Orden de compra">Orden de compra</option>
                        </select>

                        <div class="sub-title">Número:</div>
                            <div>
                                {{Form::text('numeroArchivo',old('numeroArchivo'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                            </div>
                    
                        <div class="sub-title">Subir archivo:</div>
                            <div>
                                {{Form::file('archivoFac',['class' => 'form-control'])}}
                            </div>
                        <br>

                        <div class="sub-title">Observaciones:</div>
                            <div>
                                {{Form::textarea('observacionesAF',old('observacionesAF'),['class' => 'form-control', 'placeholder' => 'Agrega información adicional.'])}}
                            </div>

                        <!-- <div class="form-group col-md-6"> -->
                            <br><br>
                            
                        <!-- </div> -->
                        @endif    
                        <br>
                        <br>
                        <div id="reporteArchivos">
                            <br>
                        @if($cuantosAF == 0)
                             <br>
                            
                            <div class="alert alert-warning" role="alert" align="center">
                                <label for="">Sin archivos agregados</label>
                            </div> 

                        @else
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="width:90%"
                                align="center">
                                <thead>
                                    <tr style=" background-color: #78e08f;">
                                        <th style="width: 80px;">Agregado por</th>
                                        <th style="width: 80px;">Número de archivo</th>
                                        <th style="width: 80px;">Tipo de archivo</th>
                                        <th style="width: 80px;">Archivo</th>
                                        <th style="width: 80px;">Observaciones</th>
                                        @if($consulta->servicioActivo == 'Activo' || $consulta->servicioActivo == '')
                                        <th style="width: 150px;">
                                            <center>Opciones</center>
                                        </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($consultaArchivosFac as $aF)
                                    <tr>
                                    <td>{{$aF->nombreUsuario}}</td>
                                    <td>{{$aF->numeroArchivo}}</td>
                                    <td>{{$aF->tipoArchivo}}</td>
                                    @if($aF->archivoFac =="" || $aF->archivoFac =="Sin archivo")
                                        <td style="color:red" align="center" >Sin archivo</td>
                                    @else
                                        <td align="center"><a target="_blank" align="center"
                                                href="{{asset ('public/archivos/'.$aF->archivoFac)}}"><img
                                                    src="{{asset('img/archivosi.png')}}" height=70
                                                    width=70></a></td>
                                    @endif
                                    <td>{{$aF->observacionesAF}}</td>
                                    @if($consulta->servicioActivo == 'Activo' || $consulta->servicioActivo == '')
                                    <td align="center">
                                       
                                        
                                                    <input type="hidden" value="{{$aF->idArchivoFactura}}" name="idArchivoFactura">
                                                    <input type="hidden" value="{{$aF->idFactura}}" name="idFactura">
                                                    <button type="button" class="btn btn-sm btn-danger borrarArchivofac" style='width:40px; height: 35px;'>
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </button>
                                         
                                    </td>
                                    @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                        </div>
                    </div>
                        
                    
                    @endif
                </div>

                <div class="tab-pane fade" id="actividades" role="tabpanel" aria-labelledby="pills-contact-tab">
                    <br><h3>Reporte de Actividades</h3><br><br>
                    @if($cuantosSeguimiento==0)
                        <div align="center" class="alert alert-info" role="alert">¡No tiene Actividades!</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr style="background-color: #ABEBC6">
                                        <th>Folio</th>
                                        <th>Fecha de creación</th>
                                        <th>Asunto</th>
                                        <th>Creado por</th>
                                        <th>Cliente / Sucursal</th>
                                        <th>Periódo de atención</th>
                                        <th>Nivel de atencion</th>
                                        <th>Área responsable</th>
                                        <th>Actividad</th>
                                        <th>Avance</th>
                                        <th>Atendido por</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($seguimiento as $seg)
                                            <tr>

                                                <td>{{$seg->folio}}</td>
                                                <td>{{$seg->fechaCreacionFormato}}</td>
                                                <td>{{$seg->asunto}}</td>
                                                <td>{{$seg->nombreUsuario}} {{$seg->aPaterno}} {{$seg->aMaterno}} </td>
                                                <td>{{$seg->clienteSucursal}}</td>
                                                <td>{{$seg->fechaInicioFormato}} - {{$seg->fechaTerminoFormato}} </td>
                                                <td>{{$seg->importanciaSeguimiento}}</td>
                                                <td>{{$seg->nombreArea}}</td>
                                                <td>{{$seg->nombreActividad}}</td>
                                                
                                                <?php 
                                                
                                                        $atendidoPor =\DB::select("SELECT CONCAT(SUM(r),' de ',COUNT(*)) AS atendidopor
                                                                    FROM (SELECT a.idsegactividad, a.idu,seguimiento($seg->idSegActividad, a.idu ) , IF(seguimiento($seg->idSegActividad, a.idu )>=1,1,0) AS r
                                                                    FROM asignacionseguimientos AS a
                                                                    INNER JOIN usuarios AS u ON u.idu = a.idu
                                                                    WHERE idsegactividad= $seg->idSegActividad) AS t1
                                                                    GROUP BY t1.idsegactividad");
                                                        
                                                        $porcentaje= \DB::select("SELECT t1.idsegactividad,TRUNCATE(AVG(t1.porcentaje),0) AS porcentaje
                                                                    FROM
                                                                    (SELECT idsegactividad, idu,IF(ultimoporcentaje($seg->idSegActividad,idu)IS NULL,0,ultimoporcentaje($seg->idSegActividad,idu)) AS porcentaje
                                                                    FROM asignacionseguimientos
                                                                    WHERE idsegactividad= $seg->idSegActividad) AS t1
                                                                    GROUP BY t1.idsegactividad");
                                                        $cuantoPor=count($porcentaje);
                                                        $cuantoAtendido=count($atendidoPor);
                                                        


                                                ?>
                                                
                                                @if($cuantoPor=='0')
                                                    <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-danger" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 100%">0% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                @else
                                                
                                                    @if($porcentaje[0]->porcentaje == 0)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-danger" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 100%">0% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 1 && $porcentaje[0]->porcentaje < 11)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 10%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 11 && $porcentaje[0]->porcentaje < 21)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 20%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 21 && $porcentaje[0]->porcentaje < 31)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 30%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    
                                                    @if($porcentaje[0]->porcentaje > 31 && $porcentaje[0]->porcentaje < 41)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 40%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    
                                                    @if($porcentaje[0]->porcentaje > 41 && $porcentaje[0]->porcentaje < 51)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 50%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 51 && $porcentaje[0]->porcentaje < 61)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 60%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 61 && $porcentaje[0]->porcentaje < 71)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 70%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 71 && $porcentaje[0]->porcentaje < 81)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 80%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    
                                                    @if($porcentaje[0]->porcentaje > 81 && $porcentaje[0]->porcentaje < 91)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 90%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    
                                                    @if($porcentaje[0]->porcentaje > 91 && $porcentaje[0]->porcentaje < 100)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 95%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje == 100)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-success" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 100%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                @endif
                                                @if($cuantoAtendido=='0')
                                                <td>0 de 0</td>
                                                @else
                                                <td>{{$atendidoPor[0]->atendidopor}}</td>
                                                @endif
                                                <td>
                                                 
                                                
                                                </td>
                                            </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>
        @endif
        </div>
    </div>
</div>

<div id="modalCoti"></div>

<script type="text/javascript">
$('#agregarPorcentaje').hide();
$("#idc").change(function() {
    $("#idp").load('{{url('comboca1')}}' + '?r=' + Date.now() + '&idc=' + this.options[this.selectedIndex].value);
});
$(".verDetallePartida").click(function (e) {
    var partida = $(this).val();
    var idCotizacion=$('#idCotizacion').val();
    var tipoMoneda=$('#tipoMoneda').val();
    $('#modalCoti').load('{{url('detallePartidasCotizacion')}}' + '?idCotizacion='+idCotizacion+'&partida='+partida+'&tipoMoneda='+tipoMoneda);
    e.preventDefault();
});
$("#porcentajeInput").change(function() {
    
    var montototal = parseFloat($('#montoRealTotal').val());
    var porcentaje = parseFloat($('#porcentajeInput').val());
    var acumulador = parseFloat($('#acumuladorpercent').val());
    var porcentajedis = 100-acumulador;
    if (porcentajedis>=porcentaje){
        $('#montoInput').val(parseFloat((montototal*porcentaje)/100)); 
        
    }if(porcentajedis>=porcentaje){
        $('#agregarPorcentaje').show();
    }
    else{
        $('#montoInput').val(parseFloat(0));
        $('#agregarPorcentaje').hide();
    }
    
});

</script>

@if(Session::has('error'))
    <script>
      alert("{{Session::get('error')}}"); 
    </script>
@endif

<script type="text/javascript">
$("#idCotizacion").change(function() {
    // alert("Cotiza");
    $("#cotizacion").load('{{url('asignarCotizacion')}}' + '?r=' + Date.now() + '&idCotizacion=' + this.options[this.selectedIndex].value);
    
});
</script>

<script type="text/javascript">

$("#precioOrden, #cantidadOrden, #ivaOrden, #isrOrden").change(function(){
    var precio = $("#precioOrden").val();
    var cantidad = $("#cantidadOrden").val();
    var iva=$("#ivaOrden").val();
    var isr=$("#isrOrden").val();

    document.getElementById("importedOrden").value = (precio*cantidad).toFixed(2);    

    var valorImpuestos=1+(iva/100)+(isr/100);
    document.getElementById("totalOrden").value = (precio*cantidad*valorImpuestos).toFixed(2);      
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

hr { 
    background-color: #335EC8; 
    border: 0 none; 
    color: #335EC8; 
    height: 2px; 
}
</style>
@stop