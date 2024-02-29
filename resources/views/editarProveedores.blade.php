@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {

    $(".cargar").click();


    $("input[name=condicionesProv]").change(function() {
        if ($('#condicionesProv').is(':checked')) {
            // alert("contado");
            $('#pago').css('display', 'none');
        } else if ($('#condicionesProv1').is(':checked')) {
            // alert("credito")
            $('#pago').css('display', 'block');
        }

    });

    $('#agregarCuenta').click(function() {
        var idProveedor = $("#idProveedor").val();
        // alert(idProveedor);
        $("#reportecuentas").load('{{url('agregarCuentas')}}' + '?' + $(this).closest('form').serialize());
    });

    $("input[name=tipoCuenta]").change(function() {
        if ($('#tipoCuenta').is(':checked')) {
            // alert("contado");
            $('#suc').css('display', 'block');
            $('#inst').css('display', 'none');
            $('#clabe').css('display', 'block');
            $('#code').css('display', 'none');
        } else if ($('#tipoCuenta1').is(':checked')) {
            // alert("credito")
            $('#inst').css('display', 'block');
            $('#suc').css('display', 'none');
            $('#code').css('display', 'block');
            $('#clabe').css('display', 'none');
        }

    });

    $('.borrar').click(
        function() {
            // alert("borrar");
            $("#reportecuentas").load('{{url('borrarCuenta')}}' + '?' + $(this).closest('form').serialize());
        });
});

jQuery("#diasVigencia").on('input', function (evt) {
		jQuery(this).val(jQuery(this).val().replace(/[^0-9]/g, ''));
});
</script>
<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Modificación de proveedor<small> *Campos Requeridos</small></h1>
        </div>
        <div class="panel-body">
            <!-- <div class="card-title">
                <div class="sub-title">Datos del proveedor</div>
            </div> -->

            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="cargar" class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                        role="tab" aria-controls="pills-home">Datos del proveedor</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                        aria-controls="pills-profile" aria-selected="false">Cuentas</a>
                </li>
            </ul>

            <!-- tab para datos del proveedor  -->
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <form action="{{ route('editarProveedores') }}" method='POST' enctype='multipart/form-data' id="">
                        @csrf
                        <input type="hidden" value="{{$consulta->idProveedor}}" name="idProveedor" id="idProveedor">
                        
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="sub-title">Título:</div>
                                <div>
                                    {{Form::text('tituloProv',$consulta->tituloProv,['class' => 'form-control'])}}
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="sub-title">*Nombre:</div>
                                    {{Form::text('nombreProv',$consulta->nombreProv,['class' => 'form-control'])}}
                            </div>
                            <div class="col-sm-2">
                                <div class="sub-title">Segundo nombre:</div>
                                {{Form::text('segundoNomProv',$consulta->segundoNomProv,['class' => 'form-control'])}}
                            </div>
                            <div class="col-sm-2">
                                <div class="sub-title">Apellido:</div>
                                {{Form::text('apellidoProv',$consulta->apellidoProv,['class' => 'form-control'])}}
                            </div>
                            <div class="col-sm-2">
                                <div class="sub-title">*Correo electrónico:</div>
                                <div>
                                    {{Form::email('correoProveedor',$consulta->correoProveedor,['class' => 'form-control', 'placeholder' => 'Ejemplo: ejemplo@ejemplo.com'])}}
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="sub-title">Teléfono:</div>
                                {{Form::text('telefonoProveedor',$consulta->telefonoProveedor,['class' => 'form-control'])}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="sub-title">Título:</div>
                                <div>
                                    {{Form::text('tituloProv2',$consulta->tituloProv2,['class' => 'form-control'])}}
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="sub-title">*Nombre:</div>
                                    {{Form::text('nombreProv2',$consulta->nombreProv2,['class' => 'form-control'])}}
                            </div>
                            <div class="col-sm-2">
                                <div class="sub-title">Segundo nombre:</div>
                                {{Form::text('segundoNomProv2',$consulta->segundoNomProv2,['class' => 'form-control'])}}
                            </div>
                            <div class="col-sm-2">
                                <div class="sub-title">Apellido:</div>
                                {{Form::text('apellidoProv2',$consulta->apellidoProv2,['class' => 'form-control'])}}
                            </div>
                            <div class="col-sm-2">
                                <div class="sub-title">*Correo electrónico:</div>
                                <div>
                                    {{Form::email('correoProveedor2',$consulta->correoProveedor2,['class' => 'form-control', 'placeholder' => 'Ejemplo: ejemplo@ejemplo.com'])}}
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="sub-title">Teléfono:</div>
                                {{Form::text('telefonoProveedor2',$consulta->telefonoProveedor2,['class' => 'form-control'])}}
                            </div>
                        </div>
                        
                        <div class="row" style="">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <div class="sub-title">*Razón social del proveedor:</div>
                                        <div>
                                            @if($errors->first('razonSocialProv'))
                                            <i> {{ $errors->first('razonSocialProv') }}</i>
                                            @endif
                                            {{Form::text('razonSocialProv',$consulta->razonSocialProv,['class' => 'form-control'])}}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="sub-title">*Iniciales:</div>
                                        <div>
                                            @if($errors->first('iniciales'))
                                            <i> {{ $errors->first('iniciales') }}</i>
                                            @endif
                                            {{Form::text('iniciales',$consulta->iniciales,['class' => 'form-control'])}}
                                        </div>
                                    </div>

                                </div>

                                <div class="sub-title">* RFC del proveedor:</div>
                                <div>
                                    @if($errors->first('rfcProveedor'))
                                    <i> {{ $errors->first('rfcProveedor') }}</i>
                                    @endif
                                    {{Form::text('rfcProveedor',$consulta->rfcProveedor,['class' => 'form-control', 'placeholder' => 'Ejemplo: EUSA892301'])}}
                                </div>


                                <div class="sub-title">Dirección:</div>
                                <div>
                                    {{Form::text('calleProv',$consulta->calleProv,['class' => 'form-control', 'placeholder' => 'Calle'])}}
                                </div>
                                <div class="row">
                                    <br>
                                    <div class="col-sm-6">
                                        {{Form::text('ciudadProv',$consulta->ciudadProv,['class' => 'form-control', 'placeholder' => 'Ciudad/Localidad'])}}
                                    </div>

                                    <div class="col-sm-6">
                                        {{Form::text('estadoProv',$consulta->estadoProv,['class' => 'form-control', 'placeholder' => 'Estado/Provincia'])}}
                                    </div>
                                </div>

                                <div class="row">
                                    <br>
                                    <div class="col-sm-6">
                                        {{Form::text('codigoPostalProv',$consulta->codigoPostalProv,['class' => 'form-control', 'placeholder' => 'Código Postal'])}}
                                    </div>

                                    <div class="col-sm-6">
                                        {{Form::text('paisProv',$consulta->paisProv,['class' => 'form-control', 'placeholder' => 'País'])}}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="sub-title">Usuario:</div>
                                        <input type="text" name="usuarioProv" id="usuarioProv" class="form-control"
                                            value="{{$consulta->usuarioProv}}">
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="sub-title">Password:</div>
                                        <input type="password" name="passwordProv" id="passwordProv"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row">                                                               
                                    <div class="col-sm-6">
                                        <div class="sub-title">*Región:</div>
                                        @if ($consulta->region == 'Nacional')                                        
                                            Nacional
                                            {{Form::radio('region','Nacional', 'true')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            Internacional
                                            {{Form::radio('region','Internacional')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        @elseif ($consulta->region == 'Internacional')
                                            Nacional
                                            {{Form::radio('region','Nacional')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            Internacional
                                            {{Form::radio('region','Internacional', 'true') }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        @else
                                            Nacional
                                            {{Form::radio('region','Nacional')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            Internacional
                                            {{Form::radio('region','Internacional') }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        @endif
                                    </div>                                    
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="sub-title">Móvil:</div>
                                        {{Form::text('movilProv',$consulta->movilProv,['class' => 'form-control'])}}
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="sub-title">Fax:</div>
                                        {{Form::text('faxProv',$consulta->faxProv,['class' => 'form-control'])}}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="sub-title">Otro:</div>
                                        {{Form::text('otroProv',$consulta->otroProv,['class' => 'form-control'])}}
                                    </div>

                                    <div class="col-sm-9">
                                        <div class="sub-title">Sitio web:</div>
                                        {{Form::text('sitioWebProv',$consulta->sitioWebProv,['class' => 'form-control', 'placeholder' => 'Ejemplo: https://germancontrolmotion.com'])}}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="sub-title">Días de vigencia:</div>
                                        <div>
                                            {{Form::text('diasVigencia',$consulta->diasVigencia,['class' => 'form-control', 'placeholder' => 'Ejemplo: 7','id' => 'diasVigencia'])}}
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="sub-title">Condiciones de pago:</div>                                
                                        @if ($consulta->condicionesProv == 'Contado')
                                            Contado
                                            {{Form::radio('condicionesProv','Contado', true, ['checked' => 'checked','id'=>'condicionesProv']) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            Crédito
                                            {{Form::radio('condicionesProv','Crédito',false, [ 'id'=>'condicionesProv1'])}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        @elseif ($consulta->condicionesProv == 'Crédito')
                                            Contado
                                            {{Form::radio('condicionesProv','Contado',false, ['id'=>'condicionesProv'])}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            Crédito
                                            {{Form::radio('condicionesProv','Crédito', true, ['checked' => 'checked', 'id'=>'condicionesProv1']) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                                                
                                        @else
                                            Contado
                                            {{Form::radio('condicionesProv','Contado',false, ['id'=>'condicionesProv'])}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            Crédito
                                            {{Form::radio('condicionesProv','Crédito', false, ['id'=>'condicionesProv1']) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                                                
                                        @endif
                                    </div>                                    
                                    <div class="col-sm-4">                                                                        
                                        @if ($consulta->condicionesProv == 'Crédito')                                           
                                            <div id="pago">
                                                <div class="sub-title">Días de pagos:</div>
                                                <div>
                                                    <select name='diasPago' id="diasPago" class="form-control">
                                                        <option value="{{$consulta->diasPago}}">{{$consulta->diasPago}}</option>
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
                                        @else                                            
                                            <div id="pago">
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
                                        @endif
                                    </div>
                                </div>

                                <div class="sub-title">Notas:</div>
                                <div>
                                    {{Form::textarea('notasProv',$consulta->notasProv,['class' => 'form-control', 'placeholder' => 'Agrega información adicional.','rounded 0', 'rows' => '3'])}}
                                </div>                                

                                <br> 
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div>
                                            <center>
                                                <div style='text-align'>
                                                    @foreach($consultaArchivoCu as $aC)
                                                        @if($aC->archivoCuentas=='Sin archivo' || $aC->archivoCuentas=='')
                                                            <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                                            <h6>Sin archivo cuentas bancarias</h6>
                                                        @else
                                                            <a target="_blank" href="{{asset('archivos/'.$aC->archivoCuentas)}}">
                                                                <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                                                <h6>Descargar archivo cuentas bancarias</h6>
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </center>
                                        </div>
                                        <div>
                                            @foreach($consultaArchivoCu as $aC)
                                                @if($aC->archivoCuentas=='Sin archivo' || $aC->archivoCuentas=='')
                                                    <div class="sub-title">Subir archivo cuentas bancarias:</div>
                                                    <div>
                                                        {{Form::file('archivoCuentas',['class' => 'form-control'])}}
                                                    </div>
                                                @else
                                                    <div class="sub-title">Actualizar archivo cuentas bancarias:</div>
                                                    <div>
                                                        {{Form::file('archivoCuentas',['class' => 'form-control'])}}
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div>
                                            <center>
                                                <div style='text-align'>
                                                    @foreach($consultaArchivoCu as $aC)
                                                        @if($aC->constanciaFiscal=='Sin archivo' || $aC->constanciaFiscal=='')
                                                            <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                                            <h6>Sin archivo constancia fiscal</h6>
                                                        @else
                                                            <a target="_blank" href="{{asset('archivos/'.$aC->constanciaFiscal)}}">
                                                                <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                                                <h6>Descargar archivo constancia fiscal</h6>
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </center>
                                        </div>
                                        <div>
                                            @foreach($consultaArchivoCu as $aC)
                                                @if($aC->constanciaFiscal=='Sin archivo' || $aC->constanciaFiscal=='')
                                                    <div class="sub-title">Subir archivo constancia fiscal:</div>
                                                    <div>
                                                        {{Form::file('constanciaFiscal',['class' => 'form-control'])}}
                                                    </div>
                                                @else
                                                    <div class="sub-title">Actualizar archivo constancia fiscal:</div>
                                                    <div>
                                                        {{Form::file('constanciaFiscal',['class' => 'form-control'])}}
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <br><br>
                        <center>
                            {{Form::submit('Guardar',['class' => 'btn  btn-success'])}}

                            <a href="{{asset('reporteProveedores')}}"><button type="button"
                                    class="btn btn-default">Cancelar</button></a>
                        </center>
                    </form>
                </div>
                <!-- termina tab de datos del proveedor -->

                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                    <form method='POST'>
                        <input type="hidden" value="{{$consulta->idProveedor}}" name="idProveedor" id="idProveedor">

                        <div class="sub-title">Tipo de cuenta:</div>
                        <div>
                            Nacional <input id="tipoCuenta" type="radio" name="tipoCuenta" value="Nacional" />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            Internacional <input id="tipoCuenta1" type="radio" name="tipoCuenta"
                                value="Internacional" />
                        </div>

                        <div class="form-group col-md-8">
                            <div>
                                <div class="row">
                                    <div class="sub-title">Nombre del banco:</div>
                                    <div>
                                        {{Form::text('bancoProv',old('bancoProv'),['class' => 'form-control'])}}
                                    </div>

                                    <div class="sub-title">Dirección del banco:</div>
                                    <div>
                                        {{Form::textarea('direccion',old('direccion'),['class' => 'form-control', 'placeholder' => 'Ingresa la dirección del banco.','rounded 0', 'rows' => '3'])}}
                                    </div>

                                    <div class="sub-title">Responsable de la cuenta:</div>
                                    <div>
                                        {{Form::text('nombreRespCuenta',old('nombreRespCuenta'),['class' => 'form-control'])}}
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="sub-title" style="display:block;" id="suc">Sucursal:</div>
                                            <div class="sub-title" style="display:none;" id="inst">Institucion No:</div>
                                            <div>
                                                {{Form::text('sucursal',old('sucursal'),['class' => 'form-control'])}}
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="sub-title">Cuenta:</div>
                                            {{Form::text('numeroCuentaProv',old('numeroCuentaProv'),['class' => 'form-control'])}}
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="sub-title" style="display:block;" id="clabe">Clabe intebancaria:</div>
                                            <div class="sub-title" style="display:none;" id="code">Swift code:</div>
                                            {{Form::text('clabeInterProv',old('clabeInterProv'),['class' => 'form-control'])}}
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="sub-title">Tipo de moneda:</div>
                                            {{Form::text('tipoMonedaProv',old('tipoMonedaProv'),['class' => 'form-control'])}}
                                        </div>
                                    </div>

                                    <div class="sub-title">Observaciones:</div>
                                    <div>
                                        {{Form::textarea('observaciones',old('observaciones'),['class' => 'form-control', 'placeholder' => 'Agrega información adicional.','rounded 0', 'rows' => '3'])}}
                                    </div>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <br><br>
                                    <button type="button" class="btn btn-success" id="agregarCuenta">
                                        <span class="glyphicon glyphicon-plus-sign"></span>Agregar
                                    </button>
                                </div>
                                <br><br>
                                <div class="form-group col-md-12">
                                    <div id="reportecuentas">
                                        @if($cuantos == 0)
                                        <br>
                                        
                                        <div class="alert alert-warning" role="alert" align="center">
                                            <label for="">Sin cuentas agregadas</label>
                                        </div> 
                                        @else
                                        <br>
                                        <!-- TIENE CUENTAS -->
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                                <thead>
                                                    <tr style="background-color: #E8E8E8;">
                                                        <th width = "90%">
                                                            <font SIZE=2>Proveedor</font>
                                                        </th>
                                                        <th width = "90%">
                                                            <font SIZE=2>Banco</font>
                                                        </th>
                                                        <th width = "90%">
                                                            <font SIZE=2>Número de cuenta</font>
                                                        </th>
                                                        <th width = "90%">
                                                            <font SIZE=2>Clabe int./Swift Code</font>
                                                        </th>
                                                        <th width = "90%">
                                                            <font SIZE=2>Tipo de moneda</font>
                                                        </th>
                                                        <th width = "90%">
                                                            <font SIZE=2>Sucursal/Inst. No</font>
                                                        </th>
                                                        <th width = "90%">
                                                            <font SIZE=2>Dirección</font>
                                                        </th>
                                                        <th width = "90%">
                                                            <font SIZE=2>Observaciones</font>
                                                        </th>
                                                        <th width = "10%">
                                                            <font SIZE=2>Opciones</font>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($cuentasagregadas as $ca)
                                                    <tr>
                                                        <td>{{$ca->nombreProv}}</td>
                                                        <td>{{$ca->bancoProv}}/{{$ca->tipoCuenta}}</td>
                                                        <td>{{$ca->numeroCuentaProv}}</td>
                                                        <td>{{$ca->clabeInterProv}}</td>
                                                        <td>{{$ca->tipoMonedaProv}}</td>
                                                        <td>{{$ca->sucursal}}</td>
                                                        <td>{{$ca->direccion}}</td>
                                                        <td>{{$ca->observaciones}}</td>
                                                        <td align="center">
                                                            <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                                                                name='frmdo{{$ca->idCuentaProv}}' id='frmdo{{$ca->idCuentaProv}}' target='_self'>
                                                                @csrf
                                                                <input type='hidden' value='{{$ca->idCuentaProv}}' name='idCuentaProv' id='idCuentaProv'>
                                                                <input type='hidden' value='{{$ca->idProveedor}}' name='idProveedor' id='idProveedor'>
                                                                <button type="button" class="btn btn-sm btn-danger borrar">
                                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop