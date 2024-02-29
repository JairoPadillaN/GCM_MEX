@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function()
		{
		$("input[name=condicionesProv]").change(function () {	 
            if ($('#condicionesProv').is(':checked'))
         {
            // alert("contado");
            $('#pago').css('display', 'none'); 
         }  
         else if($('#condicionesProv1').is(':checked'))
         {
            // alert("credito")
            $('#pago').css('display', 'block');
         }
			
			}); 

        jQuery("#diasVigencia").on('input', function (evt) {
		jQuery(this).val(jQuery(this).val().replace(/[^0-9]/g, ''));
	    });
    }); 
// });
  
</script>

<script type="text/javascript">
$(document).ready(function()
		{
        $("#guardar").click(function(){
		$("#formularioABC").validate({
            onclick: false, 
            onkeyup: false,
            ignore: [],
			rules: {
                nombreProv: {required:true},
                razonSocialProv: {required:true},
                rfcProveedor: {required:true},
                calleProv: {required:true},
                ciudadProv: {required:true},
                estadoProv: {required:true},
                codigoPostalProv: {required:true},
                paisProv: {required:true},
                correoProveedor: {required:true},
                telefonoProveedor: {required:true},
			},
			messages: 
			{
                nombreProv: {required: 'Ingresa un nombre'},
                razonSocialProv: {required: 'Ingresa una razón social'},
                rfcProveedor: {required: 'Ingresa un RFC'},
                calleProv: {required: 'Ingresa una calle'},
                ciudadProv: {required: 'Ingresa una ciudad'},
                estadoProv: {required: 'Ingresa un estado'},
                codigoPostalProv: {required: 'Ingresa un codigo postal'},
                paisProv: {required: 'Ingresa un país'},
                correoProveedor: {required: 'Ingresa un correo'},
                telefonoProveedor: {required: 'Ingresa un telefono'},
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
		});
       
    }); 
});
  
</script>

{{Form::open(['route' => 'guardarProveedores','name' => 'formularioABC','id' => 'formularioABC','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Registro de nuevo proveedor<small> *Campos Requeridos</small></h1>
        </div>
        <div class="panel-body">
            <div class="card-title">
                <div class="sub-title">Datos del proveedor</div>                
            </div>

            <div class="">Primer contacto:</div>
            <div class="row">
                <div class="col-sm-2 form-group">
                    <div class="sub-title" style="padding:5px;">Título:</div>
                    <div>
                        {{Form::text('tituloProv',old ('tituloProv'),['class' => 'form-control'])}}
                    </div>
                </div>
                <div class="col-sm-2 form-group">
                    <div class="sub-title" style="padding:5px;">*Nombre:</div>
                        {{Form::text('nombreProv',old ('nombreProv'),['class' => 'form-control','id' => 'nombreProv'])}}
                </div>
                <div class="col-sm-2 form-group">
                    <div class="sub-title" style="padding:5px;">Segundo nombre:</div>
                        {{Form::text('segundoNomProv',old ('segundoNomProv'),['class' => 'form-control'])}}
                </div>
                <div class="col-sm-2 form-group">
                    <div class="sub-title" style="padding:5px;">Apellido:</div>
                        {{Form::text('apellidoProv',old ('apellidoProv'),['class' => 'form-control'])}}
                </div>
                <div class="col-sm-2 form-group">
                    <div class="sub-title" style="padding:5px;">*Correo electrónico:</div>
                    <div>
                        {{Form::email('correoProveedor',old ('correoProveedor'),['class' => 'form-control', 'placeholder' => 'Ejemplo: ejemplo@ejemplo.com','id' => 'correoProveedor'])}}
                    </div>
                </div>
                <div class="col-sm-2 form-group">
                    <div class="sub-title" style="padding:5px;"> *Teléfono:</div>
                    {{Form::text('telefonoProveedor',old ('telefonoProveedor'),['class' => 'form-control','id' => 'telefonoProveedor'])}}
                </div>
            </div>

            <div class="">Segundo contacto:</div>
            <div class="row">
                <div class="col-sm-2 form-group">
                    <div class="sub-title" style="padding:5px;">Título:</div>
                    <div>
                        {{Form::text('tituloProv2',old ('tituloProv2'),['class' => 'form-control'])}}
                    </div>
                </div>
                <div class="col-sm-2 form-group">
                    <div class="sub-title" style="padding:5px;">*Nombre:</div>
                    {{Form::text('nombreProv2',old ('nombreProv2'),['class' => 'form-control','id' => 'nombreProv2'])}}
                </div>
                <div class="col-sm-2 form-group">
                    <div class="sub-title" style="padding:5px;">Segundo nombre:</div>
                        {{Form::text('segundoNomProv2',old ('segundoNomProv2'),['class' => 'form-control'])}}
                </div>
                <div class="col-sm-2 form-group">
                    <div class="sub-title" style="padding:5px;">Apellido:</div>
                        {{Form::text('apellidoProv2',old ('apellidoProv2'),['class' => 'form-control'])}}
                </div>
                <div class="col-sm-2 form-group">
                    <div class="sub-title" style="padding:5px;">*Correo electrónico:</div>
                    <div>
                        {{Form::email('correoProveedor2',old ('correoProveedor2'),['class' => 'form-control', 'placeholder' => 'Ejemplo: ejemplo@ejemplo.com','id' => 'correoProveedor2'])}}
                    </div>
                </div>
                <div class="col-sm-2 form-group">
                    <div class="sub-title" style="padding:5px;"> *Teléfono:</div>
                    {{Form::text('telefonoProveedor2',old ('telefonoProveedor2'),['class' => 'form-control','id' => 'telefonoProveedor2'])}}
                </div>
            </div>

            <div class="row" style="">
                <div class="col-md-6">

                    <div class="row">
                        <div class="col-sm-9 form-group">
                            <div class="sub-title">*Razón social del proveedor:</div>
                            <div>
                                @if($errors->first('razonSocialProv'))
                                <i> {{ $errors->first('razonSocialProv') }}</i>
                                @endif
                                {{Form::text('razonSocialProv',old ('razonSocialProv'),['class' => 'form-control', 'id' => 'razonSocialProv'])}}
                            </div>
                        </div>
                        <div class="col-sm-3 form-group">
                            <div class="sub-title">*Iniciales:</div>
                            <div>
                                @if($errors->first('iniciales'))
                                <i> {{ $errors->first('iniciales') }}</i>
                                @endif
                                {{Form::text('iniciales',old ('iniciales'),['class' => 'form-control', 'id' => 'iniciales'])}}
                            </div>
                        </div>
                    </div>

                    <div class="sub-title">* RFC del proveedor:</div>
                    <div class="form-group">
                        @if($errors->first('rfcProveedor'))
                        <i> {{ $errors->first('rfcProveedor') }}</i>
                        @endif
                        {{Form::text('rfcProveedor',old ('rfcProveedor'),['class' => 'form-control', 'placeholder' => 'Ejemplo: EUSA892301', 'id' => 'rfcProveedor'])}}
                    </div>
                
                    <div class="sub-title">*Dirección:</div>
                    <div class="form-group">
                        {{Form::text('calleProv',old ('calleProv'),['class' => 'form-control', 'placeholder' => 'Calle','id' => 'calleProv'])}}
                    </div>

                    <div class="row">
                        <br>
                        <div class="col-sm-6 form-group">
                            {{Form::text('ciudadProv',old ('ciudadProv'),['class' => 'form-control', 'placeholder' => 'Ciudad/Localidad','id' => 'ciudadProv'])}}
                        </div>
                        
                        <div class="col-sm-6 form-group">
                            {{Form::text('estadoProv',old ('estadoProv'),['class' => 'form-control', 'placeholder' => 'Estado/Provincia', 'id' => 'estadoProv'])}}
                        </div>
                    </div>

                    <div class="row">
                        <br>
                        <div class="col-sm-6 form-group">
                            {{Form::text('codigoPostalProv',old ('codigoPostalProv'),['class' => 'form-control', 'placeholder' => 'Código Postal', 'id' => 'codigoPostalProv'])}}
                        </div>
                        
                        <div class="col-sm-6 form-group">
                            {{Form::text('paisProv',old ('paisProv'),['class' => 'form-control', 'placeholder' => 'País','id' => 'paisProv'])}}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <div class="sub-title">Usuario:</div>
                            <input type="text" name="usuarioProv" id="usuarioProv" class="form-control">
                        </div>
                        
                        <div class="col-sm-6 form-group">
                            <div class="sub-title">Password:</div>
                            <input type="password" name="passwordProv" id="passwordProv" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="">
                        <div class="sub-title">*Región:</div>
                        <div class="form-group">
                            Nacional <input type="radio" name="region" value="Nacional"/> &nbsp;&nbsp;
                            Internacional <input type="radio" name="region" value="Internacional" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 form-group">
                            <div class="sub-title">Móvil:</div>
                            {{Form::text('movilProv',old ('movilProv'),['class' => 'form-control'])}}
                        </div>
                        <div class="col-sm-4 form-group">
                            <div class="sub-title">Fax:</div>
                            {{Form::text('faxProv',old ('faxProv'),['class' => 'form-control'])}}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 form-group">
                            <div class="sub-title">Otro:</div>
                            {{Form::text('otroProv',old ('otroProv'),['class' => 'form-control'])}}
                        </div>                        
                        
                        <div class="col-sm-9 form-group">
                            <div class="sub-title">Sitio web:</div>
                            {{Form::text('sitioWebProv',old ('sitioWebProv'),['class' => 'form-control', 'placeholder' => 'Ejemplo: https://germancontrolmotion.com'])}}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4 form-group">
                            <div class="sub-title">Días de vigencia:</div>
                            <div>
                                {{Form::text('diasVigencia',old ('diasVigencia'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 7','id' => 'diasVigencia'])}}
                            </div>                    
                        </div>
                        <div class="col-sm-4 form-group">
                            <div class="sub-title">Condiciones de pago:</div>
                            <div>
                                Contado <input id="condicionesProv" type="radio" name="condicionesProv" value="Contado"/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                Crédito <input id="condicionesProv1" type="radio" name="condicionesProv" value="Crédito" /> 
                            </div>
                        </div>
                        <div class="col-sm-4 form-group">                            
                            <div style="display:none;" id="pago">
                                <div class="sub-title">Días de pago:</div>
                                <div>
                                    <select name='diasPago' id="diasPago" class="form-control">
                                        <option value="">Selecciona los días</option>
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
                    <div class="sub-title">Notas:</div>
                    <div class="form-group">
                        {{Form::textarea('notasProv',old ('notasProv'),['class' => 'form-control', 'placeholder' => 'Agrega información adicional.','rounded 0', 'rows' => '3'])}}
                    </div>

                    <div class="">
                        <div class="col-sm-6 form-group">
                            <div class="sub-title">Archivo de cuentas bancarias:</div>
                            <div>
                                {{Form::file('archivoCuentas',['class' => 'form-control'])}}
                            </div>
                        </div>
                        <div class="col-sm-6 form-group">
                            <div class="sub-title">Archivo de constancia fiscal:</div>
                            <div>
                                {{Form::file('archivoConstanciaFiscal',['class' => 'form-control'])}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <br><br>
            <center>
                {{Form::submit('Guardar',['class' => 'btn  btn-default', 'id' => 'guardar'])}}
            </center>
        </div>
    </div>
</div>

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