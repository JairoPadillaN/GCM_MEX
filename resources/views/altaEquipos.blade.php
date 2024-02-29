@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {
    $("#divinfo").hide(1500);
    $(".cargar").click();

    $("#guardar").click(function(){
        
        // console.log("faaaaaaaaaaaaaaf");
		$("#formularioABC").validate({
            onclick: false, 
            onkeyup: false,
            ignore: [],
			rules: {
                idc: {required:true},
                idSucursal: {required:true},
                personaEntrega: {required:true},
                personaRecibe: {required:true},
                complementoGCMid: {required:true},
                marca: {required:true},
                idTipoEquipo: {required:true},
                idSubtipoEquipo: {required:true},
                serie: {required:true},
                modelo: {required:true},
                estatus: {required:true},
                estatusEntrega: {required:true},
                vistaSuperior: {required:true},
                vistaFrente: {required:true},
                placa_1: {required:true},
                
			},
			messages: 
			{
                idc: {required: 'Elige un cliente'},
                idSucursal: {required: 'Elige una sucursal'},
                personaEntrega: {required: 'El campo persona que entrega es obligatorio'},
                personaRecibe: {required: 'El campo persona que recibe es obligatorio'},
                complementoGCMid: {required: 'El complemento GCMid es obligatorio'},
                marca: {required: 'La marca es obligatoria'},
                idTipoEquipo: {required: 'Tipo es obligatorio'},
                idSubtipoEquipo: {required: 'Subtipo es obligatorio'},
                serie: {required: 'La serie es obligatoria'},
                modelo: {required: 'El modelo es obligatorio'},
                estatus: {required: 'El estatus es obligatorio'},
                estatusEntrega: {required: 'El estatus es obligatorio'},
                vistaSuperior: {required: 'Foto principal es obligatoria'},
                vistaFrente: {required: 'Foto vista frente es obligatoria'},
                placa_1: {required: 'Foto placa 1 es obligatoria'},
                
            },
            errorPlacement: function (error, element) {
            alert(error.text());
        }
		});
       
    });
    
});
</script>


{{Form::open(['route' => 'GuardarEquipos','name' => 'formularioABC','id' => 'formularioABC', 'files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Registro de equipos</h1><br><br>

            <div class="card-title">
                <div class="title">Datos del equipo</div>
            </div>
        </div>

        <div class="panel-body">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="cargar" class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                        role="tab" aria-controls="pills-home" aria-selected="true">Datos de
                        recepción</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                        aria-controls="pills-profile" aria-selected="false">Datos del equipo</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab"
                        aria-controls="pills-contact" aria-selected="false">Archivos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#qr" role="tab"
                        aria-controls="pills-contact" aria-selected="false">Etiqueta Qr</a>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">                
                <!--Primer tab Datos de recepcion --->
                <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="form-group col-md-6">
                        <div class="sub-title">* Empresa que da seguimiento:</div>
                        <div>
                            GCM <input id="nombreEmpresa" type="radio" name="nombreEmpresa" value="gcm"/>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            C&M <input id="nombreEmpresa1" type="radio" name="nombreEmpresa" value="cym" />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            SURJA <input id="nombreEmpresa2" type="radio" name="nombreEmpresa" value="surja" />
                        </div>

                        <div class="sub-title">* Folio recepción de equipo:</div>
                        <div>
                            @if($errors->first('folioRecepcion'))
                            <i> {{ $errors->first('folioRecepcion') }}</i>
                            @endif

                            <div id='divRadioGCM'>
                                <input class="form-control" type='text' name='folioRecepcion' id='folioRecepcion'
                                    readonly='readonly'>
                            </div>
                            <!-- <input class="form-control" name="folioRecepcion" value="{{$folio}}" type="text"
                                readonly="true"> -->
                        </div>

                        <div class="sub-title">Registrado por:</div>
                        <div>
                            <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu')!!}">
                            <input type="text" name="usuarioEquipos" class="form-control" readonly="true" value="{!! Session::get('sesionname')!!} {!! Session::get('sesionpaterno')!!} {!!
                                    Session::get('sesionmaterno')!!}">
                        </div>

                        <div class="sub-title">* Fecha de recolección: </div>
                        <div>
                            @if($errors->first('fechaRecoleccion'))
                            <i> {{ $errors->first('fechaRecoleccion') }}</i>
                            @endif
                            {{Form::date('fechaRecoleccion', \Carbon\Carbon::now(),['class' => 'form-control'])}}
                        </div>

                        <div class="sub-title">* Fecha de registro: </div>
                        <div>
                            @if($errors->first('fechaRegistro'))
                            <i> {{ $errors->first('fechaRegistro') }}</i>
                            @endif
                            {{Form::date('fechaRegistro', \Carbon\Carbon::now(),['class' => 'form-control'])}}
                        </div>
                        <div class="sub-title">* Importancia:</div>
                        @if($errors->first('importancia'))
                        <i> {{ $errors->first('importancia') }}</i>
                        @endif
                        <div>
                            Alta {{Form::radio('importancia','Alta')}}&nbsp;&nbsp;&nbsp;
                            Media {{Form::radio('importancia','Media', true)}}&nbsp;&nbsp;&nbsp;
                            Baja {{Form::radio('importancia','Baja')}}
                        </div>

                        <div class="sub-title">* Ubicación de equipo:</div>
                        @if($errors->first('ubicacionEquipo'))
                        <i> {{ $errors->first('ubicacionEquipo') }}</i>
                        @endif
                        <div>
                            En almacen {{Form::radio('ubicacionEquipo','En almacen',true)}}&nbsp;&nbsp;&nbsp;
                            Cotizado en sitio {{Form::radio('ubicacionEquipo','Cotizado en sitio')}}&nbsp;&nbsp;&nbsp;
                            
                        </div>
                        <div class="sub-title">Se le da seguimiento:</div>
                        @if($errors->first('seguimiento'))
                        <i> {{ $errors->first('seguimiento') }}</i>
                        @endif
                        <div>
                            Si {{Form::radio('seguimiento','Si',true)}}&nbsp;&nbsp;&nbsp;
                            No {{Form::radio('seguimiento','No')}}&nbsp;&nbsp;&nbsp;
                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="sub-title">* Cliente:</div>
                                    @if($errors->first('idc'))
                                    <i> {{ $errors->first('idc') }}</i>
                                    @endif
                                    <div>
                                        <select name='idc' id='idc' class="form-control">
                                            <option value="">Seleccionar empresa</option>
                                            @foreach($cliente as $cliente)
                                            @if($cliente->activo=="si")
                                            <option value='{{$cliente->idc}}'>{{$cliente->razonSocial}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        <div id = "alertaCliente" class="alert alert-warning">Debes seleccionar un cliente</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="sub-title">* Sucursal:</div>
                                    @if($errors->first('idSucursal'))
                                    <i> {{ $errors->first('idSucursal') }}</i>
                                    @endif
                                    <div id='combop'>
                                        <select name='idSucursal' id='idp' class="form-control">
                                            <option value="">Seleccione una sucursal</option>
                                        </select>
                                        <div id = "alertaSucursal" class="alert alert-warning">Debes seleccionar una sucursal</div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br>
                        <div id='divinfo' class="alert alert-info"></div>

                        <div class="sub-title">* Persona que entrega:</div>
                        @if($errors->first('personaEntrega'))
                        <i> {{ $errors->first('personaEntrega') }}</i>
                        @endif
                        <div>
                            {{Form::text('personaEntrega',old ('personaEntrega'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Luis Sánchez Júarez','id' => 'entrega'])}}
                            <div id = "alertaEntrega" class="alert alert-warning">Este campo es obligatorio</div>
                        </div>

                        <div class="sub-title">* Persona que recibe:</div>
                        <div>
                            @if($errors->first('personaRecibe'))
                            <i> {{ $errors->first('personaRecibe') }}</i>
                            @endif
                            {{Form::text('personaRecibe',old ('personaRecibe'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Jorge Cisneros Hernández','id' => 'recibe'])}}
                            <div id = "alertaRecibe" class="alert alert-warning">Este campo es obligatorio</div>
                        </div>
                        <div class="sub-title">Número de documento salida cliente:</div>
                        <div>
                            @if($errors->first('numeroDocumentoSalida'))
                            <i> {{ $errors->first('numeroDocumentoSalida') }}</i>
                            @endif
                            {{Form::text('numeroDocumentoSalida',old ('numeroDocumentoSalida'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 00001203'])}}
                        </div>
                        <div class="sub-title">Vale de salida cliente:</div>
                        <div>
                            @if($errors->first('archivoValeSalida'))
                            <i> {{ $errors->first('archivoValeSalida') }}</i>
                            @endif
                            {{Form::file('archivoValeSalida',['class' => 'form-control rounded-0'])}}
                        </div>
                        <div class="sub-title">Documento de salida empresa:</div>
                        <div>
                            @if($errors->first('archivoDocumentoSalida'))
                            <i> {{ $errors->first('archivoDocumentoSalida') }}</i>
                            @endif
                            {{Form::file('archivoDocumentoSalida',['class' => 'form-control rounded-0'])}}
                        </div>
                    </div>
                </div>
                <!--Segundo tab Datos de pago --->
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="form-group col-md-6">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="sub-title">* GCMid:</div>
                                @if($errors->first('GCMid'))
                                <i> {{ $errors->first('GCMid') }}</i>
                                @endif
                                <div id='divgcmid'>
                                    <input class="form-control" type='text' name='GCMid' id='GCMid' readonly='readonly'>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="sub-title">* Complemento del GCMid:</div>
                                @if($errors->first('complementoGCMid'))
                                <i> {{ $errors->first('complementoGCMid') }}</i>
                                @endif
                                <input class="form-control" type='text' name='complementoGCMid' id='complemento'
                                    placeholder="Ejemplo: 276381">
                                    <div id = "alertaGCMID" class="alert alert-warning">Este campo es obligatorio</div>
                            </div>
                        </div>

                        <div class="sub-title">* Tipo:</div>
                        @if($errors->first('idTipoEquipo'))
                        <i> {{ $errors->first('idTipoEquipo') }}</i>
                        @endif
                        <div>
                            <select name='idTipoEquipo' id='idTipoEquipo' class="form-control">
                                <option value="">Seleccionar tipo</option>
                                @foreach($tipoEquipo as $tipoEquipo)
                                @if($tipoEquipo->activo=="si")
                                <option value='{{$tipoEquipo->idTipoEquipo}}'>{{$tipoEquipo->tipoEquipo}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="sub-title">* Subtipo:</div>
                        @if($errors->first('idSubtipoEquipo'))
                        <i> {{ $errors->first('idSubtipoEquipo') }}</i>
                        @endif
                        <div id='comEquipos'>
                            <select name='idSubtipoEquipo' id="idSubtipoEquipo" class="form-control">
                            <option value="">Selecciones un subtipo</option></select>
                        </div>
                        <div class="sub-title">* Serie:</div>
                        <div>
                            @if($errors->first('serie'))
                            <i> {{ $errors->first('serie') }}</i>
                            @endif
                            {{Form::text('serie',old ('serie'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 00001203'])}}
                        </div>
                        <div class="sub-title">Datos para certificado:</div>
                        <div>
                            {{Form::textarea('datosCertificado',old ('datosCertificado'),['class' => 'form-control','rows'=>'3'])}}
                        </div>
                        <div class="sub-title">Equipos para cambio:</div>
                        <div>
                            {{Form::radio('cambio', 'Si', false, ['id'=>'cambioSi'])}} Si &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            {{Form::radio('cambio', 'No', true, ['id'=>'cambioNo'])}} No
                        </div>
                        <div id = "mostrar" style = "display:none">
                            <div class="sub-title">Marca: </div>
                                <select name='idMarcaRefa' class="form-control" id="idMarcaRefa">
                                    <option value="">Seleccione una marca</option> 
                                    @foreach($marca as $mr)
                                        @if($mr->activo=="Si")
                                        <option value='{{$mr->idMarcaRefa}}'>{{$mr->marcaRefaccion}}</option>
                                        @endif
                                    @endforeach 
                                </select>
                            <div class="sub-title">Tipo de refacción: </div>
                                <select name='idTipoRefacciones' class="form-control" id="idTipoRefacciones">
                                    <option value="">Seleccione tipo de refacción</option>  
                                </select>
                            <div class="sub-title">Refacciones disponibles: </div>
                                <select name="idRefaccion" id="comboRef" class="form-control">
                                    <option value="">Seleccione una refacción</option>  
                                </select>
                            <div class="consulta">        
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="sub-title">Numero de parte: </div>
                                            <div>
                                                {{Form::text('parte',old('parte'),['class' => 'form-control consulta','readonly' => 'true'])}}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="sub-title">Numero de serie: </div>
                                            <div>
                                                {{Form::text('serieNum',old('serieNum'),['class' => 'form-control consulta','readonly' => 'true'])}}
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="sub-title">Código:</div>
                                    <div id='codigoRefaccion'>
                                        {{Form::text('codigoRefaccion',old('codigoRefaccion'),['class' => 'form-control consulta','readonly' => 'true'])}}
                                    </div>
                                    <div class="sub-title">Observaciones:</div>
                                    <div id='codigoRefaccion'>
                                        {{Form::text('observaciones',old('observaciones'),['class' => 'form-control consulta','readonly' => 'true'])}}
                                    </div>
                                </div>
                        </div>
                        
                    </div>

                    <div class="form-group col-md-6">
                        <div class="sub-title">* Marca:</div>
                        <div>
                            @if($errors->first('marca'))
                            <i> {{ $errors->first('marca') }}</i>
                            @endif
                            {{Form::text('marca',old ('marca'),['class' => 'form-control', 'placeholder' => 'Ejemplo: LouisV','id' =>'marca'])}}
                        </div>
                        <div class="sub-title">* Modelo:</div>
                        <div>
                            @if($errors->first('modelo'))
                            <i> {{ $errors->first('modelo') }}</i>
                            @endif
                            {{Form::text('modelo',old ('modelo'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 206', 'id' =>'modelo'])}}
                        </div>

                        <div class="sub-title">* Estatus de reparación técnica:</div>
                        <div>
                            @if($errors->first('estatus'))
                            <i> {{ $errors->first('estatus') }}</i>
                            @endif
                            <select name="estatus" class="form-control">
                                <option value="" selected>Selecciona un estatus</option>
                                <option value="No tiene reparación/No se reparó">No tiene reparación/No se reparó</option>
                                <option value="Pendiente de revisión en taller">Pendiente de revisión en taller</option>
                                <option value="Reparación interna">Reparación interna</option>
                                <option value="Reparación externa">Reparación externa</option>
                                <option value="Reparado">Reparado</option>
                                <option value="Entregado de taller">Entregado de taller</option>
                            </select>
                        </div>

                        <div class="sub-title">* Estatus de entrega al cliente:</div>
                        <div>
                            @if($errors->first('estatusEntrega'))
                            <i> {{ $errors->first('estatusEntrega') }}</i>
                            @endif
                            <select name="estatusEntrega" class="form-control">
                                <option value="" selected>Selecciona un estatus</option>
                                <option value="Pendiente de cotización">Pendiente de cotización</option>
                                <option value="En revisión">En revisión</option>
                                <option value="Con garantía">Con garantía</option>
                                <option value="Cotizado">Cotizado</option>
                                <option value="con OC">con OC</option>
                                <option value="Facturado">Facturado</option>
                                <option value="Devuelto sin reparar">Devuelto sin reparar</option>
                            </select>
                        </div>

                        <div class="sub-title">Datos del cliente:</div>
                        <div>
                            {{Form::text('recurso',old ('recurso'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 00001203'])}}
                        </div>                        

                        <div class="sub-title">Descripción de falla:</div>
                        <div>
                            {{Form::textarea('descripcionFalla',old ('descripcionFalla'),['class' => 'form-control','rows'=>'9'])}}
                        </div>
                        <div id = "mostrar2" style = "display:none">
                            <div class="consulta2">        
                                <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Foto placa: </div>
                                    <div>
                                        {{Form::text('fotoPlaca',old('fotoPlaca'),['class' => 'form-control consulta2','readonly' => 'true'])}}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Foto principal: </div>
                                    <div>
                                        {{Form::text('fotoPrincipal',old('fotoPrincipal'),['class' => 'form-control consulta2','readonly' => 'true'])}}
                                    </div>
                                </div>    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--tercer tab Archivos --->
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                    <div class="form-group col-md-6">
                        <div class="sub-title">* Foto principal</div>
                        <div>
                            @if($errors->first('vistaSuperior'))
                            <i> {{ $errors->first('vistaSuperior') }}</i>
                            @endif
                            {{Form::file('vistaSuperior')}}
                        </div>
                        <br>

                        <div class="sub-title">* Foto vista frente :</div>
                        <div>
                            @if($errors->first('vistaFrente'))
                            <i> {{ $errors->first('vistaFrente') }}</i>
                            @endif
                            {{Form::file('vistaFrente')}}
                        </div>
                        <br>

                        <div class="sub-title">Foto vista trasera:</div>
                        <div>
                            @if($errors->first('vistaTrasera'))
                            <i> {{ $errors->first('vistaTrasera') }}</i>
                            @endif
                            {{Form::file('vistaTrasera')}}
                        </div>
                    </div>

                    <div class="form-group col-md-6">

                        <div class="sub-title">Foto lateral izquierda:</div>
                        <div>
                            @if($errors->first('lateralIzquierda'))
                            <i> {{ $errors->first('lateralIzquierda') }}</i>
                            @endif
                            {{Form::file('lateralIzquierda')}}
                        </div>
                        <br>
                        <div class="sub-title">Foto lateral derecha:</div>
                        <div>
                            @if($errors->first('lateralDerecha'))
                            <i> {{ $errors->first('lateralDerecha') }}</i>
                            @endif
                            {{Form::file('lateralDerecha')}}
                        </div>
                        <div class="sub-title">* Foto placa 1:</div>
                        <div>
                            @if($errors->first('placa_1'))
                            <i> {{ $errors->first('placa_1') }}</i>
                            @endif
                            {{Form::file('placa_1')}}
                        </div>
                        <div class="sub-title">Foto placa 2:</div>
                        <div>
                            @if($errors->first('placa_2'))
                            <i> {{ $errors->first('placa_2') }}</i>
                            @endif
                            {{Form::file('placa_2')}}
                        </div>
                    </div>
                </div>

             
                <!--TAB QR CON BOTON DE GUARDAR --->
                <div class="tab-pane fade" id="qr" role="tabpanel" aria-labelledby="pills-contact-tab">
                    <div class="form-group col-md-6">
                            <div class="sub-title">Información para ingresar a la etiqueta QR:</div>
                            <textarea name="textoQr" id="textoQr" class="form-control rounded-0" rows="5"
                                placeholder="Ingresar información..."></textarea>
                            <br>
                            <div class="form-group row">
                                <div class="form-group col-md-6">
                                    <button type="button" class="btn btn-info btn-lg" id="generarQrEquipo"><i
                                            class="fa fa-qrcode"></i> Generear etiqueta Qr</button>
                                </div>
                            </div>
                        
                            <center>
                            {{Form::submit('Guardar',['class' => 'btn  btn-default','id' => "guardar"])}}
                                <a href="{{asset('reporteEquipos')}}"><button type="button"
                                class="btn btn-default">Cancelar</button></a></center>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="sub-title"></div><br>
                        <div id="imgQr"></div>
                    </div>
                </div>
            </div>
        </div> <!-- aqui cierra el div del body -->
    </div>
</div>

<script type="text/javascript">
$("#generarQrEquipo").click(function(e) {
    let textoQr = $("#textoQr").val();
    if (textoQr == '') {
        alert("ERROR: El campo no puede estar vacio");
        e.preventDefault();
    }else{
        document.getElementById("imgQr").innerHTML = "Creando....";
        $("#imgQr").load('{{url('qrcodeEquipo')}}' + '?' + $(this).closest('form').serialize());
    }    
});
$("#idc").change(function() {
    $("#idp").load('{{url('comboca1')}}' + '?r=' + Date.now() + '&idc=' + this.options[this.selectedIndex].value);
    // alert("mensaje correcto")
});
</script>

<script type="text/javascript">
$("#idTipoEquipo").change(function() {
    $("#idSubtipoEquipo").load('{{url('comboca2')}}' + '?r=' + Date.now() + '&idTipoEquipo=' + this.options[this.selectedIndex].value);
    // alert("mensaje correcto")
});
$("#cambioSi").click(function(){
    // alert("Cambio si");
    document.getElementById('mostrar').style.display = 'block';
    document.getElementById('mostrar2').style.display = 'block';
});
$("#cambioNo").click(function(){
    // alert("Cambio No");
    document.getElementById('mostrar').style.display = 'none';
    document.getElementById('mostrar2').style.display = 'none';
    $("#idMarcaRefa, #idTipoRefacciones, #comboRef, .consulta").val('');
});
$("#idMarcaRefa").change(function(){
    // alert("hola");
    $("#idTipoRefacciones").load('{{url('comboTipoRefaccionCambio')}}' + '?r=' + Date.now() + '&idMarcaRefa=' + this.options[this.selectedIndex].value);
    $("#comboRef, #numeroParte, #numeroSerie, #codigo, #observaciones").val('');
});

$("#idTipoRefacciones").change(function() {
        $("#comboRef").load('{{url('comboRefaccionesCambio')}}' + '?r=' + Date.now() + '&idTipoRefacciones=' + this.options[this.selectedIndex].value);
        $("#numeroParte, #numeroSerie, #codigo, #observaciones").val('');
    });
$("#comboRef").change(function() {
        $(".consulta").load('{{url('datosRefaccion')}}' + '?r=' + Date.now() + '&idRefaccion=' + this.options[this.selectedIndex].value);
        
    });
$("#comboRef").change(function() {
        
        $(".consulta2").load('{{url('datosRefaccion2')}}' + '?r=' + Date.now() + '&idRefaccion=' + this.options[this.selectedIndex].value);
    });
</script>

<script type="text/javascript">
$("#idp").change(function() {
    $("#divgcmid").load('{{url('generagcmid')}}' + '?idp=' + this.options[this.selectedIndex].value);
    $("#divinfo").load('{{url('info')}}' + '?' + $(this).closest('form').serialize());
    $("#divinfo").show("slow");
    //alert("mensaje correcto")
});
</script>


<script type="text/javascript">
$(document).ready(function()
		{
		$("input[name=nombreEmpresa]").change(function () {	 
            if ($('#nombreEmpresa').is(':checked'))
         {
            $("#divRadioGCM").load('{{url('generafolio')}}' + '?nombreEmpresa=' + this.value);
         }  
         else if($('#nombreEmpresa1').is(':checked'))
         {
            $("#divRadioGCM").load('{{url('generacym')}}' + '?nombreEmpresa=' + this.value);
            // alert($(this).val());
         } 
         else if($('#nombreEmpresa2').is(':checked'))
         {
            $("#divRadioGCM").load('{{url('generasurja')}}' + '?nombreEmpresa=' + this.value);
            // alert($(this).val());
         } 
			
			});
 
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
.alert-warning {
    display: none;
}
.alert-info {
    display: none;
}

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