@extends('principal')
@section('contenido')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $(".cargar").click();
});
</script>

{{Form::open(['route' => 'editarEquipos','files'=>true])}}
{{Form::token()}}

<div id="page-inner">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default" style="margin-top:-55px">

                <div class="panel-heading">
                <h1 >Registro de equipos</h1><br><br>
                    <div class="card-title">
                        <div class="title">Ingrese los datos del equipo recibido</div>
                    </div>
                </div>
            
                <div class="panel-body">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="cargar" class="nav-link active" id="pills-home-tab" data-toggle="pill"
                                href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Datos de
                                recepción</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile"
                                role="tab" aria-controls="pills-profile" aria-selected="false">Datos del equipo</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact"
                                role="tab" aria-controls="pills-contact" aria-selected="false">Archivos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pruebas"
                                role="tab" aria-controls="pills-contact" aria-selected="false">Pruebas de laboratorio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#qr" role="tab"
                                aria-controls="pills-contact" aria-selected="false">Etiqueta Qr</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#actividades" role="tab"
                                aria-controls="pills-contact" aria-selected="false">Actividades</a>
                        </li>
                    </ul>
                @if($consulta->equipoActivo == 'Activado' || $consulta->equipoActivo == '')
                    <div class="tab-content" id="pills-tabContent">

                        <!--Primer tab Datos de Factura --->
                        <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="form-group col-md-6">
                                {{Form::hidden('idEquipos',$consulta->idEquipos,['id'=>'idEquipos'])}}

                                <div class="sub-title">Empresa que da segumiento:</div>
                                <div>
                                    @if ($consulta->nombreEmpresa == 'gcm')
                                    GCM {{Form::radio('nombreEmpresa','gcm', true, ['checked' => 'checked','id'=>'nombreEmpresa']) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    C&M {{Form::radio('nombreEmpresa','cym',false, [ 'id'=>'nombreEmpresa1'])}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    SURJA {{Form::radio('nombreEmpresa','surja',false, [ 'id'=>'nombreEmpresa2'])}}
                                    @elseif($consulta->nombreEmpresa == 'cym')
                                    GCM {{Form::radio('nombreEmpresa','gcm',false, ['id'=>'nombreEmpresa'])}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    C&M {{Form::radio('nombreEmpresa','cym', true, ['checked' => 'checked', 'id'=>'nombreEmpresa1']) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    SURJA {{Form::radio('nombreEmpresa','surja',false, [ 'id'=>'nombreEmpresa2'])}}
                                    @elseif($consulta->nombreEmpresa == 'surja')
                                    GCM {{Form::radio('nombreEmpresa','gcm',false, ['id'=>'nombreEmpresa'])}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    C&M {{Form::radio('nombreEmpresa','cym', false, ['id'=>'nombreEmpresa1']) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    SURJA {{Form::radio('nombreEmpresa','surja',true, ['checked' => 'checked', 'id'=>'nombreEmpresa2'])}}
                                    @else
                                    GCM {{Form::radio('nombreEmpresa','gcm',false, ['id'=>'nombreEmpresa'])}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    C&M {{Form::radio('nombreEmpresa','cym', false, ['id'=>'nombreEmpresa1']) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    SURJA {{Form::radio('nombreEmpresa','surja',false, ['id'=>'nombreEmpresa2'])}}
                                    @endif
                                </div>

                                <div class="sub-title">* Folio recepcion de equipo:</div>
                                <div>
                                    @if($errors->first('folioRecepcion'))
                                    <i> {{ $errors->first('folioRecepcion') }}</i>
                                    @endif
                                    <div id='divRadioGCM'>
                                        <input class="form-control" type='text' name='folioRecepcion'
                                            id='folioRecepcion' readonly='readonly'
                                            value="{{$consulta->folioRecepcion}}">
                                    </div>
                                </div>

                                <div class="sub-title">Registrado por:</div>
                                <div>
                                    <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu')!!}">
                                    <input type="text" name="usuarioEquipos" class="form-control" readonly="true" value="{{$consulta->usuarioEquipos}}">
                                </div>

                                <div class="sub-title">* Fecha de recolección: </div>
                                <div>
                                    @if($errors->first('fechaRecoleccion'))
                                    <i> {{ $errors->first('fechaRecoleccion') }}</i>
                                    @endif
                                    {{Form::date('fechaRecoleccion',$consulta->fechaRecoleccion,['class' => 'form-control'])}}
                                </div>

                                <div class="sub-title">* Fecha de registro: </div>
                                <div>
                                    @if($errors->first('fechaRegistro'))
                                    <i> {{ $errors->first('fechaRegistro') }}</i>
                                    @endif
                                    {{Form::date('fechaRegistro',$consulta->fechaRegistro,['class' => 'form-control'])}}
                                </div>
                                <div class="sub-title">* Importancia:</div>
                                @if($errors->first('importancia'))
                                <i> {{ $errors->first('importancia') }}</i>
                                @endif
                                <div>
                                    @if ($consulta->importancia == 'Alta')
                                    Alta
                                    {{Form::radio('importancia','Alta', true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                                    Media {{Form::radio('importancia','Media',false, [])}}&nbsp;&nbsp;&nbsp;
                                    Baja {{Form::radio('importancia','Baja',false, [])}}
                                    @elseif($consulta->importancia == 'Media')
                                    Alta {{Form::radio('importancia','Alta',false, [])}}&nbsp;&nbsp;&nbsp;
                                    Media
                                    {{Form::radio('importancia','Media', true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                                    Baja {{Form::radio('importancia','Baja',false, [])}}
                                    @else
                                    Alta {{Form::radio('importancia','Alta',false, [])}}&nbsp;&nbsp;&nbsp;
                                    Media {{Form::radio('importancia','Media',false, [])}}&nbsp;&nbsp;&nbsp;
                                    Baja {{Form::radio('importancia','Baja', true, ['checked' => 'checked'])}}
                                    @endif
                                </div>

                                <div class="sub-title">* Ubicación de equipo:</div>
                                @if($errors->first('ubicacionEquipo'))
                                <i> {{ $errors->first('ubicacionEquipo') }}</i>
                                @endif
                                <div>
                                    @if ($consulta->ubicacionEquipo == 'En almacen')
                                    En almacen {{Form::radio('ubicacionEquipo','En almacen', true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                                    Cotizado en sitio {{Form::radio('ubicacionEquipo','Cotizado en sitio', false)}}&nbsp;&nbsp;&nbsp;

                                    @elseif($consulta->ubicacionEquipo == 'Cotizado en sitio')
                                    En almacen {{Form::radio('ubicacionEquipo','En almacen', false)}}&nbsp;&nbsp;&nbsp;
                                    Cotizado en sitio {{Form::radio('ubicacionEquipo','Cotizado en sitio', true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;

                                    @else
                                    En almacen {{Form::radio('ubicacionEquipo','En almacen', false)}}&nbsp;&nbsp;&nbsp;
                                    Cotizado en sitio {{Form::radio('ubicacionEquipo','Cotizado en sitio', false)}}&nbsp;&nbsp;&nbsp;
                                    @endif
                                </div>
                                <div class="sub-title">Se le da seguimiento:</div>
                                @if($errors->first('ubicacionEquipo'))
                                <i> {{ $errors->first('ubicacionEquipo') }}</i>
                                @endif
                                <div>
                                @if($consulta->seguimiento == "Si")
                                    Si {{Form::radio('seguimiento','Si',true)}}&nbsp;&nbsp;&nbsp;
                                    No {{Form::radio('seguimiento','No')}}&nbsp;&nbsp;&nbsp;
                                @else
                                    Si {{Form::radio('seguimiento','Si')}}&nbsp;&nbsp;&nbsp;
                                    No {{Form::radio('seguimiento','No', true)}}&nbsp;&nbsp;&nbsp;
                                @endif

                                    
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <form action="" method="post">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <div class="sub-title">* Cliente:</div>
                                            <div>
                                                @if($errors->first('idc'))
                                                <i> {{ $errors->first('idc') }}</i>
                                                @endif
                                                <select name='idc' id='idc' class="form-control">
                                                    <option value='{{$idclientesel}}'>{{$nomcli}}</option>
                                                    @foreach($cliente as $cli)
                                                    @if($cli->activo=="si")
                                                    <option value='{{$cli->idc}}'>{{$cli->razonSocial}}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="sub-title">* Sucursal:</div>
                                            @if($errors->first('idSucursal'))
                                            <i> {{ $errors->first('idSucursal') }}</i>
                                            @endif
                                            <div>
                                                <select name='idSucursal' id="idp" class="form-control">
                                                    <option value='{{$idSucursalSel}}'>{{$nomSuc}}</option>
                                                    @foreach($sucursal as $s)
                                                    @if($s->activo=="Si")
                                                    <option value='{{$s->idSucursal}}'>{{$s->sucursal}}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div id='divinfo' class="alert alert-info">
                                    Cliente: {{$nomcli}} ------- Sucursal: {{$nomSuc}}
                                </div>

                                <div class="sub-title">* Persona que entrega:</div>
                                <div>
                                    @if($errors->first('personaEntrega'))
                                    <i> {{ $errors->first('personaEntrega') }}</i>
                                    @endif
                                    {{Form::text('personaEntrega',$consulta->personaEntrega,['class' => 'form-control', 'placeholder' => 'Ejemplo: Luis Sánchez Júarez'])}}
                                </div>

                                <div class="sub-title">* Persona que recibe:</div>
                                <div>
                                    @if($errors->first('personaRecibe'))
                                    <i> {{ $errors->first('personaRecibe') }}</i>
                                    @endif
                                    {{Form::text('personaRecibe',$consulta->personaRecibe,['class' => 'form-control', 'placeholder' => 'Ejemplo: Jorge Cisneros Hernández'])}}
                                </div>
                                <div class="sub-title">Número de documento salida cliente:</div>
                                <div>
                                    @if($errors->first('numeroDocumentoSalida'))
                                    <i> {{ $errors->first('numeroDocumentoSalida') }}</i>
                                    @endif
                                    {{Form::text('numeroDocumentoSalida',$consulta->numeroDocumentoSalida,['class' => 'form-control', 'placeholder' => 'Ejemplo: 00001203'])}}
                                </div>

                                <div class="form-group col-md-8">

                                    @foreach($carchivoValeSalida as $cas)
                                    @if($cas->archivoValeSalida=='Sin archivo')
                                    <div class="sub-title">Subir Vale de salida cliente:</div>
                                    <div>
                                        @if($errors->first('archivoValeSalida'))
                                        <i> {{ $errors->first('archivoValeSalida') }}</i>
                                        @endif
                                        {{Form::file('archivoValeSalida',['class' => 'form-control'])}}
                                    </div>
                                    @else
                                    <div class="sub-title">Actualizar Vale de salida cliente:</div>
                                    <div>
                                        @if($errors->first('archivoValeSalida'))
                                        <i> {{ $errors->first('archivoValeSalida') }}</i>
                                        @endif
                                        {{Form::file('archivoValeSalida',['class' => 'form-control'])}}
                                    </div>
                                    @endif
                                    @endforeach

                                    <br><br>

                                    @foreach($carchivoDocumentoSalida as $cads)
                                    @if($cads->archivoDocumentoSalida=='Sin archivo')
                                    <div class="sub-title">Subir Vale de salida cliente:</div>
                                    <div>
                                        @if($errors->first('archivoDocumentoSalida'))
                                        <i> {{ $errors->first('archivoDocumentoSalida') }}</i>
                                        @endif
                                        {{Form::file('archivoDocumentoSalida',['class' => 'form-control'])}}
                                    </div>
                                    @else
                                    <div class="sub-title">Actualizar Vale de salida cliente:</div>
                                    <div>
                                        @if($errors->first('archivoDocumentoSalida'))
                                        <i> {{ $errors->first('archivoDocumentoSalida') }}</i>
                                        @endif
                                        {{Form::file('archivoDocumentoSalida',['class' => 'form-control'])}}
                                    </div>
                                    @endif
                                    @endforeach

                                </div>
                                <div class="form-group col-md-4">
                                    <div>
                                        <center>
                                            <div style='text-align'>
                                                @foreach($carchivoValeSalida as $dvs)
                                                @if($dvs->archivoValeSalida=='Sin archivo')
                                                <br><br>
                                                <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                                @else
                                                <br>
                                                <a href="{{asset('archivos/'.$dvs->archivoValeSalida)}}"
                                                    target="_blank">
                                                    <img src="{{asset('img/descarga.png')}}" target="_blank"
                                                        height=50 width=50>
                                                    <h6>Descargar vale de salida</h6>
                                                </a>
                                                @endif
                                                @endforeach
                                            </div>
                                        </center>
                                    </div>
                                    <div>
                                        <center>
                                            <div style='text-align'>
                                                @foreach($carchivoDocumentoSalida as $ds)
                                                @if($ds->archivoDocumentoSalida=='Sin archivo')
                                                <br><br>
                                                <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                                @else
                                                <br>
                                                <a href="{{asset('archivos/'.$ds->archivoDocumentoSalida)}}"
                                                    target="_blank">
                                                    <img src="{{asset('img/descarga.png')}}" height=50 width=50>
                                                    <h6>Descargar vale de salida</h6>
                                                </a>
                                                @endif
                                                @endforeach
                                            </div>
                                        </center>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Segundo tab Datos de pago --->
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                            aria-labelledby="pills-profile-tab">
                            <div class="form-group col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="sub-title">* GCMid:</div>
                                        @if($errors->first('GCMid'))
                                        <i> {{ $errors->first('GCMid') }}</i>
                                        @endif
                                        <div id='divgcmid'>
                                            <input class="form-control" type='text' name='GCMid' id='GCMid'
                                                value="{{$consulta->GCMid}}" readonly='readonly'>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="sub-title">* Complemento del GCMid:</div>
                                        @if($errors->first('complementoGCMid'))
                                        <i> {{ $errors->first('complementoGCMid') }}</i>
                                        @endif
                                        <input class="form-control" type='text' name='complementoGCMid'
                                            value="{{$consulta->complementoGCMid}}" placeholder="Ejemplo: 276381">
                                    </div>
                                </div>

                                <div class="sub-title">* Tipo:</div>
                                @if($errors->first('idTipoEquipo'))
                                <i> {{ $errors->first('idTipoEquipo') }}</i>
                                @endif
                                <div>
                                    <select name='idTipoEquipo' id='idTipoEquipo' class="form-control">
                                        <option value='{{$idTipoSel}}'>{{$nomTipo}}</option>
                                        @foreach($tipo as $t)
                                        @if($t->activo=="si")
                                        <option value='{{$t->idTipoEquipo}}'>{{$t->tipoEquipo}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sub-title">* Subtipo:</div>
                                @if($errors->first('idSubtipoEquipo'))
                                <i> {{ $errors->first('idSubtipoEquipo') }}</i>
                                @endif
                                <div>
                                    <select name='idSubtipoEquipo' id='idSubtipoEquipo' class="form-control">
                                        <option value='{{$idSubtipoSel}}'>{{$nomSubtipo}}</option>
                                        @foreach($subtipo as $sub)
                                        @if($sub->activo=="si")
                                        <option value='{{$sub->idSubtipoEquipo}}'>{{$sub->subtipoEquipo}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sub-title">* Serie:</div>
                                <div>
                                    @if($errors->first('serie'))
                                    <i> {{ $errors->first('serie') }}</i>
                                    @endif
                                    {{Form::text('serie',$consulta->serie,['class' => 'form-control', 'placeholder' => 'Ejemplo: 00001203'])}}
                                </div>
                                <div class="sub-title">Datos para certificado:</div>
                                <div>
                                    {{Form::textarea('datosCertificado',$consulta->datosCertificado,['class' => 'form-control','rows'=>'3'])}}
                                </div>
                                <!-- {{Form::text('cambio',$consulta->cambio,['class' => 'form-control','rows'=>'3'])}} -->
                                @if($consulta->cambio=="Si")
                                    <div class="sub-title">Equipos para cambio:</div>
                                    <div>
                                        {{Form::radio('cambio', 'Si', true, ['id'=>'cambioSi'])}} Si &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        {{Form::radio('cambio', 'No', false, ['id'=>'cambioNo'])}} No
                                    </div>
                                    <div id = "mostrar" >
                                        <div class="sub-title">Marca: </div>
                                            <select name='idMarcaRefa' class="form-control" id="idMarcaRefa">
                                                <option value="">{{$nomMarca}}</option> 
                                                @foreach($marca as $mr)
                                                    @if($mr->activo=="Si")
                                                    <option value='{{$mr->idMarcaRefa}}'>{{$mr->marcaRefaccion}}</option>
                                                    @endif
                                                @endforeach 
                                            </select>
                                        <div class="sub-title">Tipo de refacción: </div>
                                            <select name='idTipoRefacciones' class="form-control" id="idTipoRefacciones">
                                                <option value="">{{$nombreTipoRefaccion}}</option>  
                                                @foreach($refSin as $mr)
                                                    @if($mr->activo=="Si")
                                                    <option value='{{$mr->idTipoRefacciones}}'>{{$mr->nombreTipoRefaccion}}</option>
                                                    @endif
                                                @endforeach 
                                            </select>
                                            <div class="sub-title">Refacciones disponibles: </div>
                                            {{Form::hidden('idRefaccionOld',$consulta->idRefaccion,['class' => 'form-control consulta','readonly' => 'true'])}}
                                            <select name="idRefaccion" id="comboRef" class="form-control mi-selector">
                                                <option value="">{{$codigo}}-{{$nombreRefaccion}}-{{$serie}}</option>
                                                @foreach($refaccionUso as $mr)
                                                    @if($mr->activo=="Si")
                                                    <option value='{{$mr->idRefaccion}}'>{{$mr->codigoRefaccion}}-{{$mr->nombreRefaccion}}-{{$mr->serie}}</option>
                                                    @endif
                                                @endforeach   
                                            </select>
                                        <div class="consulta">        
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="sub-title">Numero de parte: </div>
                                                    <div>
                                                        {{Form::text('parte',$parte,['class' => 'form-control consulta','readonly' => 'true'])}}
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="sub-title">Numero de serie: </div>
                                                    <div>
                                                        {{Form::text('serieNum',$serie,['class' => 'form-control consulta','readonly' => 'true'])}}
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="sub-title">Código:</div>
                                            <div id='codigoRefaccion'>
                                                {{Form::text('codigoRefaccion',$codigo,['class' => 'form-control consulta','readonly' => 'true'])}}
                                            </div>
                                            <div class="sub-title">Observaciones:</div>
                                            <div id='codigoRefaccion'>
                                                {{Form::text('observaciones',$observaciones,['class' => 'form-control consulta','readonly' => 'true'])}}
                                            </div>
                                        </div>    
                                    </div>
                               @else
                                <div class="sub-title">Equipos para cambio:</div>
                                    <div>
                                        {{Form::radio('cambio', 'Si', false, ['id'=>'cambioSi'])}} Si &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        {{Form::radio('cambio', 'No', True, ['id'=>'cambioNo'])}} No
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
                                        <select name="idRefaccion" id="comboRef" class="form-control mi-selector2">
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
                                                        {{Form::text('serieNum',old('serie'),['class' => 'form-control consulta','readonly' => 'true'])}}
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
                               @endif
                               
                            </div>

                            <div class="form-group col-md-6">
                                <div class="sub-title">* Marca:</div>
                                <div>
                                    @if($errors->first('marca'))
                                    <i> {{ $errors->first('marca') }}</i>
                                    @endif
                                    {{Form::text('marca',$consulta->marca,['class' => 'form-control', 'placeholder' => 'Ejemplo: SEW'])}}
                                </div>
                                <div class="sub-title">* Modelo:</div>
                                <div>
                                    @if($errors->first('modelo'))
                                    <i> {{ $errors->first('modelo') }}</i>
                                    @endif
                                    {{Form::text('modelo',$consulta->modelo,['class' => 'form-control', 'placeholder' => 'Ejemplo: 206'])}}
                                </div>

                                <div class="sub-title">* Estatus de reparación técnica:</div>
                                <div>
                                    @if($errors->first('estatus'))
                                    <p><i> {{ $errors->first('estatus') }}</i></p>
                                    @endif
                                    <select name="estatus" class="form-control">
                                        <option value="<?php echo ($consulta->estatus) ?>">
                                            <?php echo ($consulta->estatus) ?></option>
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
                                    <p><i> {{ $errors->first('estatusEntrega') }}</i></p>
                                    @endif
                                    <select name="estatusEntrega" class="form-control">
                                        <option value="<?php echo ($consulta->estatusEntrega) ?>">
                                            <?php echo ($consulta->estatusEntrega) ?></option>
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
                                    @if($errors->first('recurso'))
                                    <i> {{ $errors->first('recurso') }}</i>
                                    @endif
                                    {{Form::text('recurso',$consulta->recurso,['class' => 'form-control', 'placeholder' => 'Ejemplo: 12345678'])}}
                                </div>
                                <div class="sub-title">Descripción de falla:</div>
                                <div>
                                    {{Form::textarea('descripcionFalla',$consulta->descripcionFalla,['class' => 'form-control'])}}
                                </div>

                                @if($consulta->cambio=="Si")
                                 <div id = "mostrar2">
                                    <div class="consulta2">        
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="sub-title">Foto placa: </div>
                                                <div>
                                                    <center>
                                                    @if($fotoPlaca =="" || $fotoPlaca =="Sin archivo")
                                                        <div style="color:red" >Sin foto</div></center>
                                                    @else
                                                        <div><a target="_blank" href="{{asset ('public/archivos/'.$fotoPlaca)}}">
                                                        <img src="{{asset ('public/archivos/'.$fotoPlaca)}}" height=100 width=100></a></div>
                                                                    
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="sub-title">Foto principal: </div>
                                                <div>
                                                    <center>
                                                    @if($fotoPrincipal =="" || $fotoPrincipal =="Sin archivo")
                                                        <div style="color:red" >Sin foto</div></center>
                                                    @else
                                                        <div><a target="_blank" href="{{asset ('public/archivos/'.$fotoPrincipal)}}">
                                                        <img src="{{asset ('public/archivos/'.$fotoPrincipal)}}" height=100 width=100></a></div>
                                                                    
                                                    @endif
                                                </div>
                                            </div>                                            
                                        </div>                                    
                                    </div>    
                                </div>
                                @else
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
                                @endif
                            </div>

                        </div>
                        <!--tercer tab Archivos --->
                        <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                            aria-labelledby="pills-contact-tab">

                            <!-- columna 1 -->
                            <div class="form-group col-md-4">

                                <!--foto vista superior:  -->
                                @foreach($cvistaSuperior as $vsup)
                                @if($vsup->vistaSuperior=='Sin archivo')
                                <div class="sub-title">* Subir foto vista superior:</div>
                                <div>
                                    @if($errors->first('vistaSuperior'))
                                    <i> {{ $errors->first('vistaSuperior') }}</i>
                                    @endif
                                    {{Form::file('vistaSuperior',['class' => 'form-control'])}}
                                </div>
                                @else
                                <div class="sub-title">Actualizar foto principal:</div>
                                <div>
                                    @if($errors->first('vistaSuperior'))
                                    <i> {{ $errors->first('vistaSuperior') }}</i>
                                    @endif
                                    {{Form::file('vistaSuperior',['class' => 'form-control'])}}
                                </div>
                                @endif
                                @endforeach
                                <br><br>
                                <!--Foto vista frente -->
                                @foreach($cvistaFrente as $cvf)
                                @if($cvf->vistaFrente=='Sin archivo')
                                <div class="sub-title">* Subir Foto vista frente:</div>
                                <div>
                                    @if($errors->first('vistaFrente'))
                                    <i> {{ $errors->first('vistaFrente') }}</i>
                                    @endif
                                    {{Form::file('vistaFrente',['class' => 'form-control'])}}
                                </div>
                                @else
                                <div class="sub-title">Actualizar Foto vista frente</div>
                                <div>
                                    @if($errors->first('vistaFrente'))
                                    <i> {{ $errors->first('vistaFrente') }}</i>
                                    @endif
                                    {{Form::file('vistaFrente',['class' => 'form-control'])}}
                                </div>
                                @endif
                                @endforeach

                                <!-- vista trasera-->
                                <br><br>

                                @foreach($cvistaTrasera as $cvt)
                                @if($cvt->vistaTrasera=='Sin archivo')
                                <div class="sub-title">Subir Foto vista trasera:</div>
                                <div>
                                    @if($errors->first('vistaTrasera'))
                                    <i> {{ $errors->first('vistaTrasera') }}</i>
                                    @endif
                                    {{Form::file('vistaTrasera',['class' => 'form-control'])}}
                                </div>
                                @else
                                <div class="sub-title">Actualizar Foto vista trasera</div>
                                <div>
                                    @if($errors->first('vistaTrasera'))
                                    <i> {{ $errors->first('vistaTrasera') }}</i>
                                    @endif
                                    {{Form::file('vistaTrasera',['class' => 'form-control'])}}
                                </div>
                                @endif
                                @endforeach
                            </div>
                            <!-- columna 2 iconos de descarga-->
                            <div class="form-group col-md-2">
                                <div>
                                    <center>
                                        <div style='text-align'>
                                            @foreach($cvistaSuperior as $vvistaSuperior)
                                            @if($vvistaSuperior->vistaSuperior=='Sin archivo')
                                            <br><br>
                                            <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                            @else
                                            <br>
                                            <a href="{{asset('archivos/'.$vvistaSuperior->vistaSuperior)}}"
                                                target="_blank">
                                                <img src="{{asset('img/descarga.png')}}" height=50 width=50>
                                                <h6>Descargar foto vista superior</h6>
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
                                            @foreach($cvistaFrente as $cvfi)
                                            @if($cvfi->vistaFrente=='Sin archivo')
                                            <br>
                                            <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                            @else
                                            <a href="{{asset('archivos/'.$cvfi->vistaFrente)}}" target="_blank">
                                                <img src="{{asset('img/descarga.png')}}" height=50 width=50>
                                                <h6>Descargar foto vista Frente</h6>
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
                                            @foreach($cvistaTrasera as $cvti)
                                            @if($cvti->vistaTrasera=='Sin archivo')
                                            <br><br><br>
                                            <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                            @else
                                            <a href="{{asset('archivos/'.$cvti->vistaTrasera)}}" target="_blank">
                                                <img src="{{asset('img/descarga.png')}}" height=50 width=50>
                                                <h6>Descargar foto vista Frente</h6>
                                            </a>
                                            @endif
                                            @endforeach
                                        </div>
                                    </center>
                                </div>

                            </div>
                            <!--Columna 3 -->

                            <div class="form-group col-md-4">
                                @foreach($clateralIzquierda as $cvli)
                                @if($cvli->lateralIzquierda=='Sin archivo')
                                <div class="sub-title">Subir Foto lateral izquierda:</div>
                                <div>
                                    @if($errors->first('lateralIzquierda'))
                                    <i> {{ $errors->first('lateralIzquierda') }}</i>
                                    @endif
                                    {{Form::file('lateralIzquierda',['class' => 'form-control'])}}
                                </div>
                                @else
                                <div class="sub-title">Actualizar Foto lateral izquierda:</div>
                                <div>
                                    @if($errors->first('lateralIzquierda'))
                                    <i> {{ $errors->first('lateralIzquierda') }}</i>
                                    @endif
                                    {{Form::file('lateralIzquierda',['class' => 'form-control'])}}
                                </div>
                                @endif
                                @endforeach
                                <br><br>

                                @foreach($clateralDerecha as $cvld)
                                @if($cvld->lateralDerecha=='Sin archivo')
                                <div class="sub-title">Subir Foto lateral derecha:</div>
                                <div>
                                    @if($errors->first('lateralDerecha'))
                                    <i> {{ $errors->first('lateralDerecha') }}</i>
                                    @endif
                                    {{Form::file('lateralDerecha',['class' => 'form-control'])}}
                                </div>
                                @else
                                <div class="sub-title">Actualizar Foto lateral derecha:</div>
                                <div>
                                    @if($errors->first('lateralDerecha'))
                                    <i> {{ $errors->first('lateralDerecha') }}</i>
                                    @endif
                                    {{Form::file('lateralDerecha',['class' => 'form-control'])}}
                                </div>
                                @endif
                                @endforeach
                                <br><br>

                                @foreach($cplaca_1 as $cp1)
                                @if($cp1->placa_1=='Sin archivo')
                                <div class="sub-title">* Subir Foto placa 1:</div>
                                <div>
                                    @if($errors->first('placa_1'))
                                    <i> {{ $errors->first('placa_1') }}</i>
                                    @endif
                                    {{Form::file('placa_1',['class' => 'form-control'])}}
                                </div>
                                @else
                                <div class="sub-title">Actualizar Foto placa 1:</div>
                                <div>
                                    @if($errors->first('placa_1'))
                                    <i> {{ $errors->first('placa_1') }}</i>
                                    @endif
                                    {{Form::file('placa_1',['class' => 'form-control'])}}
                                </div>
                                @endif
                                @endforeach
                                <br><br>
                                @foreach($cplaca_2 as $cp2)
                                @if($cp2->placa_2=='Sin archivo')
                                <div class="sub-title">Subir Foto placa 2:</div>
                                <div>
                                    @if($errors->first('placa_2'))
                                    <i> {{ $errors->first('placa_2') }}</i>
                                    @endif
                                    {{Form::file('placa_2',['class' => 'form-control'])}}
                                </div>
                                @else
                                <div class="sub-title">Actualizar Foto placa 2:</div>
                                <div>
                                    @if($errors->first('placa_2'))
                                    <i> {{ $errors->first('placa_2') }}</i>
                                    @endif
                                    {{Form::file('placa_2',['class' => 'form-control'])}}
                                </div>
                                @endif
                                @endforeach

                            </div>

                            <div class="form-group col-md-2">
                                <div>
                                    <center>
                                        <div style='text-align'>
                                            @foreach($clateralIzquierda as $cli)
                                            @if($cli->lateralIzquierda=='Sin archivo')
                                            <br><br>
                                            <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                            @else
                                            <br>
                                            <a href="{{asset('archivos/'.$cli->lateralIzquierda)}}"
                                                target="_blank">
                                                <img src="{{asset('img/descarga.png')}}" height=50 width=50>
                                                <h6>Descargar foto vista lateral izquierda</h6>
                                            </a>
                                            @endif
                                            @endforeach
                                        </div>
                                    </center>
                                </div>

                                <div>
                                    <center>
                                        <div style='text-align'>
                                            @foreach($clateralDerecha as $cld)
                                            @if($cld->lateralDerecha=='Sin archivo')
                                            <br><br><br>
                                            <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                            @else
                                            <br>
                                            <a href="{{asset('archivos/'.$cld->lateralDerecha)}}"
                                                target="_blank">
                                                <img src="{{asset('img/descarga.png')}}" height=50 width=50>
                                                <h6>Descargar foto vista lateral </h6>
                                            </a>
                                            @endif
                                            @endforeach
                                        </div>
                                    </center>
                                </div>

                                <div>
                                    <center>
                                        <div style='text-align'>
                                            @foreach($cplaca_1 as $cp1)
                                            @if($cp1->placa_1=='Sin archivo')
                                            <br><br><br><br>
                                            <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                            @else
                                            <br>
                                            <a href="{{asset('archivos/'.$cp1->placa_1)}}" target="_blank">
                                                <img src="{{asset('img/descarga.png')}}" height=50 width=50>
                                                <h6>Descargar foto placa 1</h6>
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
                                            @foreach($cplaca_2 as $cp2)
                                            @if($cp2->placa_2=='Sin archivo')
                                            <br><br><br>
                                            <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                            @else
                                            <br>
                                            <a href="{{asset('archivos/'.$cp2->placa_2)}}" target="_blank">
                                                <img src="{{asset('img/descarga.png')}}" height=50 width=50>
                                                <h6>Descargar foto placa 2</h6>
                                            </a>
                                            @endif
                                            @endforeach
                                        </div>
                                    </center>
                                </div>
                                <br><br>
                            </div>
                        </div>

                        <!-- tab de pruebas de laboratorio  -->
                        
                        <div class="tab-pane fade" id="pruebas" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <div class="form-group col-md-6">
                                
                                <CENTER><div class="sub-title">EVALUACIÓN:</div></CENTER>
                                
                                <div class="sub-title">Test run:</div>
                                <div class="col-sm-3">Inicial:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('testRunInicial',$consulta->testRunInicial,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>
                                <div class="col-sm-3">Final:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('testRunFinal',$consulta->testRunFinal,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>


                                <br>
                                <div class="sub-title">Feedback:</div>
                                <div class="col-sm-3">Inicial:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('feedbackInicial',$consulta->feedbackInicial,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>
                                <div class="col-sm-3">Final:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('feedbackFinal',$consulta->feedbackFinal,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>
                                
                                <br>
                                <div class="sub-title">Baleros:</div>
                                <div class="col-sm-3">Inicial:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('balerosInicial',$consulta->balerosInicial,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>
                                <div class="col-sm-3">Final:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('balerosFinal',$consulta->balerosFinal,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>

                                <br>
                                <div class="sub-title">Gear box:</div>
                                <div class="col-sm-3">Inicial:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('gearBoxInicial',$consulta->gearBoxInicial,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>
                                <div class="col-sm-3">Final:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('gearBoxFinal',$consulta->gearBoxFinal,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>

                                <br>
                                <div class="sub-title">Imanes:</div>
                                <div class="col-sm-3">Inicial:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('imanesInicial',$consulta->imanesInicial,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>
                                <div class="col-sm-3">Final:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('imanesFinal',$consulta->imanesFinal,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>


                                <br>
                                <div class="sub-title">Debanados:</div>
                                <div class="col-sm-3">Inicial:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('debanadosInicial',$consulta->debanadosInicial,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>
                                <div class="col-sm-3">Final:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('debanadosFinal',$consulta->debanadosFinal,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>

                                <br>
                                <div class="sub-title">Encoder:</div>
                                <div class="col-sm-3">Inicial:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('encoderInicial',$consulta->encoderInicial,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>
                                <div class="col-sm-3">Final:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('encoderFinal',$consulta->encoderFinal,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <CENTER><div class="sub-title">PRUEBAS FINALES:</div></CENTER>
                                
                                <div class="sub-title">Corriente:</div>
                                <div class="col-sm-2">U/Ver:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('corrienteVer',$consulta->corrienteVer,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                <div class="col-sm-2">V/Hor:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('corrienteHor',$consulta->corrienteHor,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                <div class="col-sm-2">W/Ax:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('corrienteAx',$consulta->corrienteAx,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                
                                <br>
                                <div class="sub-title">Tensión:</div>
                                <div class="col-sm-2">U/Ver:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('tensionVer',$consulta->tensionVer,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                <div class="col-sm-2">V/Hor:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('tensionHor',$consulta->tensionHor,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                <div class="col-sm-2">W/Ax:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('tensionAx',$consulta->tensionAx,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>

                                <br>
                                <div class="sub-title">Temp.Motor:</div>
                                <div class="col-sm-12">
                                    {{ Form::text('tempMotor',$consulta->tempMotor,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                
                                <br>
                                <div class="sub-title">Temp.Transmisión:</div>
                                <div class="col-sm-12">
                                    {{ Form::text('tempTransmision',$consulta->tempTransmision,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>

                                <br>
                                <div class="sub-title">Vibración:</div>
                                <div class="col-sm-2">U/Ver:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('vibracionVer',$consulta->vibracionVer,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                <div class="col-sm-2">V/Hor:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('vibracionHor',$consulta->vibracionHor,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                <div class="col-sm-2">W/Ax:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('vibracionAx',$consulta->vibracionAx,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>

                                <br>
                                <div class="sub-title">Enfriamiento:</div>
                                <div class="col-sm-2">U/Ver:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('enfriamientoVer',$consulta->enfriamientoVer,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                <div class="col-sm-2">V/Hor:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('enfriamientoHor',$consulta->enfriamientoHor,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                <div class="col-sm-2">W/Ax:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('enfriamientoAx',$consulta->enfriamientoAx,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>                                                             
                            </div>
                            <div align="right">
                            @if($cuantosdatosFactura > 0)
                                <button id="pdf" class="btn btn-default btn-sm">
                                    Generar PDF
                                </button>
                            @else
                                <button id="pdfNO"class="btn btn-default btn-sm">
                                    Generar PDF
                                </button>
                            @endif
                            </div>
                        </div>
                        
                        
                        <!--TAB QR CON BOTON DE GUARDAR --->
                        <div class="tab-pane fade" id="qr" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <div class="form-group col-md-6">
                                    <div class="sub-title">Información para ingresar a la etiqueta QR:</div>
                                        @if($consulta->textoQr == 'SinEtiquetaQr')
                                        <textarea name="textoQr" id="textoQr" class="form-control rounded-0" rows="5"
                                            placeholder="Ingresar información..."></textarea>
                                        @else
                                        <textarea name="textoQr" id="textoQr" class="form-control rounded-0" rows="5"
                                            placeholder="Ingresar información...">{{$consulta->textoQr}}</textarea>
                                        @endif
                                    <br>
                                    <div class="form-group row">
                                        <div class="form-group col-md-6">
                                            <button type="button" class="btn btn-info btn-lg" id="generarQrEquipo"><i
                                                    class="fa fa-qrcode"></i> Generear etiqueta Qr</button>
                                        </div>
                                    </div>
                                <center>{{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
                                    <a href="{{asset('reporteEquipos')}}"><button type="button"
                                            class="btn btn-default">Cancelar</button></a></center>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="sub-title"></div><br>
                                <div id="imgQr">
                                    @if($consulta->textoQr != 'SinEtiquetaQr')
                                        {!! QrCode::size(117)->generate($consulta->textoQr); !!}
                                    @endif
                                </div>
                            </div>
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
                                                <a href="{{URL::action('seguimientoActController@detalleSeg',['idSegActividad'=>$seg->idSegActividad])}}"
                                                        type="submit" class="btn btn-xs btn-success"> Detalle
                                                    </a> 
                                                </td>
                                            </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    </div>                  
                    </div>
                </div> <!-- aqui cierra el div del body -->
            @else
                 <div class="tab-content" id="pills-tabContent">

                        <!--Primer tab Datos de Factura --->
                        <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="form-group col-md-6">
                                {{Form::hidden('idEquipos',$consulta->idEquipos,['id'=>'idEquipos'])}}

                                <div class="sub-title">Empresa que da segumiento:</div>
                                <div>
                                @if ($consulta->nombreEmpresa == 'gcm')
                                    GCM {{Form::radio('nombreEmpresa','gcm', true, ['checked' => 'checked','id'=>'nombreEmpresa']) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    C&M {{Form::radio('nombreEmpresa','cym',false, [ 'id'=>'nombreEmpresa1'])}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    SURJA {{Form::radio('nombreEmpresa','surja',false, [ 'id'=>'nombreEmpresa2'])}}
                                    @elseif($consulta->nombreEmpresa == 'cym')
                                    GCM {{Form::radio('nombreEmpresa','gcm',false, ['id'=>'nombreEmpresa'])}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    C&M {{Form::radio('nombreEmpresa','cym', true, ['checked' => 'checked', 'id'=>'nombreEmpresa1']) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    SURJA {{Form::radio('nombreEmpresa','surja',false, [ 'id'=>'nombreEmpresa2'])}}
                                    @elseif($consulta->nombreEmpresa == 'surja')
                                    GCM {{Form::radio('nombreEmpresa','gcm',false, ['id'=>'nombreEmpresa'])}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    C&M {{Form::radio('nombreEmpresa','cym', false, ['id'=>'nombreEmpresa1']) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    SURJA {{Form::radio('nombreEmpresa','surja',true, ['checked' => 'checked', 'id'=>'nombreEmpresa2'])}}
                                    @else
                                    GCM {{Form::radio('nombreEmpresa','gcm',false, ['id'=>'nombreEmpresa'])}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    C&M {{Form::radio('nombreEmpresa','cym', false, ['id'=>'nombreEmpresa1']) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    SURJA {{Form::radio('nombreEmpresa','surja',false, ['id'=>'nombreEmpresa2'])}}
                                    @endif
                                </div>

                                <div class="sub-title">* Folio recepcion de equipo:</div>
                                <div>
                                    @if($errors->first('folioRecepcion'))
                                    <i> {{ $errors->first('folioRecepcion') }}</i>
                                    @endif
                                    <div id='divRadioGCM'>
                                        <input class="form-control" type='text' name='folioRecepcion'
                                            id='folioRecepcion' readonly='readonly'
                                            value="{{$consulta->folioRecepcion}}">
                                    </div>
                                </div>

                                <div class="sub-title">Registrado por:</div>
                                <div>
                                    <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu')!!}">
                                    <input type="text" name="usuarioEquipos" class="form-control" readonly="true" value="{{$consulta->usuarioEquipos}}">
                                </div>

                                <div class="sub-title">* Fecha de recolección: </div>
                                <div>
                                    @if($errors->first('fechaRecoleccion'))
                                    <i> {{ $errors->first('fechaRecoleccion') }}</i>
                                    @endif
                                    {{Form::date('fechaRecoleccion',$consulta->fechaRecoleccion,['class' => 'form-control'])}}
                                </div>

                                <div class="sub-title">* Fecha de registro: </div>
                                <div>
                                    @if($errors->first('fechaRegistro'))
                                    <i> {{ $errors->first('fechaRegistro') }}</i>
                                    @endif
                                    {{Form::date('fechaRegistro',$consulta->fechaRegistro,['class' => 'form-control'])}}
                                </div>
                                <div class="sub-title">* Importancia:</div>
                                @if($errors->first('importancia'))
                                <i> {{ $errors->first('importancia') }}</i>
                                @endif
                                <div>
                                    @if ($consulta->importancia == 'Alta')
                                    Alta
                                    {{Form::radio('importancia','Alta', true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                                    Media {{Form::radio('importancia','Media',false, [])}}&nbsp;&nbsp;&nbsp;
                                    Baja {{Form::radio('importancia','Baja',false, [])}}
                                    @elseif($consulta->importancia == 'Media')
                                    Alta {{Form::radio('importancia','Alta',false, [])}}&nbsp;&nbsp;&nbsp;
                                    Media
                                    {{Form::radio('importancia','Media', true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                                    Baja {{Form::radio('importancia','Baja',false, [])}}
                                    @else
                                    Alta {{Form::radio('importancia','Alta',false, [])}}&nbsp;&nbsp;&nbsp;
                                    Media {{Form::radio('importancia','Media',false, [])}}&nbsp;&nbsp;&nbsp;
                                    Baja {{Form::radio('importancia','Baja', true, ['checked' => 'checked'])}}
                                    @endif
                                </div>

                                <div class="sub-title">* Ubicación de equipo:</div>
                                @if($errors->first('ubicacionEquipo'))
                                <i> {{ $errors->first('ubicacionEquipo') }}</i>
                                @endif
                                <div>
                                    @if ($consulta->ubicacionEquipo == 'En almacen')
                                    En almacen {{Form::radio('ubicacionEquipo','En almacen', true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                                    Cotizado en sitio {{Form::radio('ubicacionEquipo','Cotizado en sitio', false)}}&nbsp;&nbsp;&nbsp;

                                    @elseif($consulta->ubicacionEquipo == 'Cotizado en sitio')
                                    En almacen {{Form::radio('ubicacionEquipo','En almacen', false)}}&nbsp;&nbsp;&nbsp;
                                    Cotizado en sitio {{Form::radio('ubicacionEquipo','Cotizado en sitio', true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;

                                    @else
                                    En almacen {{Form::radio('ubicacionEquipo','En almacen', false)}}&nbsp;&nbsp;&nbsp;
                                    Cotizado en sitio {{Form::radio('ubicacionEquipo','Cotizado en sitio', false)}}&nbsp;&nbsp;&nbsp;
                                    @endif
                                </div>
                                <div class="sub-title">Se le da seguimiento:</div>
                                @if($errors->first('ubicacionEquipo'))
                                <i> {{ $errors->first('ubicacionEquipo') }}</i>
                                @endif
                                <div>
                                @if($consulta->seguimiento == "Si")
                                    Si {{Form::radio('seguimiento','Si',true)}}&nbsp;&nbsp;&nbsp;
                                    No {{Form::radio('seguimiento','No')}}&nbsp;&nbsp;&nbsp;
                                @else
                                    Si {{Form::radio('seguimiento','Si')}}&nbsp;&nbsp;&nbsp;
                                    No {{Form::radio('seguimiento','No', true)}}&nbsp;&nbsp;&nbsp;
                                @endif

                                    
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <form action="" method="post">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <div class="sub-title">* Cliente:</div>
                                            <div>
                                                @if($errors->first('idc'))
                                                <i> {{ $errors->first('idc') }}</i>
                                                @endif
                                                <select name='idc' id='idc' class="form-control">
                                                    <option value='{{$idclientesel}}'>{{$nomcli}}</option>
                                                    @foreach($cliente as $cli)
                                                    @if($cli->activo=="si")
                                                    <option value='{{$cli->idc}}'>{{$cli->razonSocial}}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="sub-title">* Sucursal:</div>
                                            @if($errors->first('idSucursal'))
                                            <i> {{ $errors->first('idSucursal') }}</i>
                                            @endif
                                            <div>
                                                <select name='idSucursal' id="idp" class="form-control">
                                                    <option value='{{$idSucursalSel}}'>{{$nomSuc}}</option>
                                                    @foreach($sucursal as $s)
                                                    @if($s->activo=="Si")
                                                    <option value='{{$s->idSucursal}}'>{{$s->sucursal}}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div id='divinfo' class="alert alert-info">
                                    Cliente: {{$nomcli}} ------- Sucursal: {{$nomSuc}}
                                </div>

                                <div class="sub-title">* Persona que entrega:</div>
                                <div>
                                    @if($errors->first('personaEntrega'))
                                    <i> {{ $errors->first('personaEntrega') }}</i>
                                    @endif
                                    {{Form::text('personaEntrega',$consulta->personaEntrega,['class' => 'form-control', 'placeholder' => 'Ejemplo: Luis Sánchez Júarez'])}}
                                </div>

                                <div class="sub-title">* Persona que recibe:</div>
                                <div>
                                    @if($errors->first('personaRecibe'))
                                    <i> {{ $errors->first('personaRecibe') }}</i>
                                    @endif
                                    {{Form::text('personaRecibe',$consulta->personaRecibe,['class' => 'form-control', 'placeholder' => 'Ejemplo: Jorge Cisneros Hernández'])}}
                                </div>
                                <div class="sub-title">Número de documento salida cliente:</div>
                                <div>
                                    @if($errors->first('numeroDocumentoSalida'))
                                    <i> {{ $errors->first('numeroDocumentoSalida') }}</i>
                                    @endif
                                    {{Form::text('numeroDocumentoSalida',$consulta->numeroDocumentoSalida,['class' => 'form-control', 'placeholder' => 'Ejemplo: 00001203'])}}
                                </div>

                                <div class="form-group col-md-8">

                                    @foreach($carchivoValeSalida as $cas)
                                    @if($cas->archivoValeSalida=='Sin archivo')
                                    <div class="sub-title">Subir Vale de salida cliente:</div>
                                    <div>
                                        @if($errors->first('archivoValeSalida'))
                                        <i> {{ $errors->first('archivoValeSalida') }}</i>
                                        @endif
                                        {{Form::file('archivoValeSalida',['class' => 'form-control'])}}
                                    </div>
                                    @else
                                    <div class="sub-title">Actualizar Vale de salida cliente:</div>
                                    <div>
                                        @if($errors->first('archivoValeSalida'))
                                        <i> {{ $errors->first('archivoValeSalida') }}</i>
                                        @endif
                                        {{Form::file('archivoValeSalida',['class' => 'form-control'])}}
                                    </div>
                                    @endif
                                    @endforeach

                                    <br><br>

                                    @foreach($carchivoDocumentoSalida as $cads)
                                    @if($cads->archivoDocumentoSalida=='Sin archivo')
                                    <div class="sub-title">Subir Vale de salida cliente:</div>
                                    <div>
                                        @if($errors->first('archivoDocumentoSalida'))
                                        <i> {{ $errors->first('archivoDocumentoSalida') }}</i>
                                        @endif
                                        {{Form::file('archivoDocumentoSalida',['class' => 'form-control'])}}
                                    </div>
                                    @else
                                    <div class="sub-title">Actualizar Vale de salida cliente:</div>
                                    <div>
                                        @if($errors->first('archivoDocumentoSalida'))
                                        <i> {{ $errors->first('archivoDocumentoSalida') }}</i>
                                        @endif
                                        {{Form::file('archivoDocumentoSalida',['class' => 'form-control'])}}
                                    </div>
                                    @endif
                                    @endforeach

                                </div>
                                <div class="form-group col-md-4">
                                    <div>
                                        <center>
                                            <div style='text-align'>
                                                @foreach($carchivoValeSalida as $dvs)
                                                @if($dvs->archivoValeSalida=='Sin archivo')
                                                <br><br>
                                                <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                                @else
                                                <br>
                                                <a href="{{asset('archivos/'.$dvs->archivoValeSalida)}}"
                                                    target="_blank">
                                                    <img src="{{asset('img/descarga.png')}}" target="_blank"
                                                        height=50 width=50>
                                                    <h6>Descargar vale de salida</h6>
                                                </a>
                                                @endif
                                                @endforeach
                                            </div>
                                        </center>
                                    </div>
                                    <div>
                                        <center>
                                            <div style='text-align'>
                                                @foreach($carchivoDocumentoSalida as $ds)
                                                @if($ds->archivoDocumentoSalida=='Sin archivo')
                                                <br><br>
                                                <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                                @else
                                                <br>
                                                <a href="{{asset('archivos/'.$ds->archivoDocumentoSalida)}}"
                                                    target="_blank">
                                                    <img src="{{asset('img/descarga.png')}}" height=50 width=50>
                                                    <h6>Descargar vale de salida</h6>
                                                </a>
                                                @endif
                                                @endforeach
                                            </div>
                                        </center>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Segundo tab Datos de pago --->
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                            aria-labelledby="pills-profile-tab">
                            <div class="form-group col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="sub-title">* GCMid:</div>
                                        @if($errors->first('GCMid'))
                                        <i> {{ $errors->first('GCMid') }}</i>
                                        @endif
                                        <div id='divgcmid'>
                                            <input class="form-control" type='text' name='GCMid' id='GCMid'
                                                value="{{$consulta->GCMid}}" readonly='readonly'>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="sub-title">* Complemento del GCMid:</div>
                                        @if($errors->first('complementoGCMid'))
                                        <i> {{ $errors->first('complementoGCMid') }}</i>
                                        @endif
                                        <input class="form-control" type='text' name='complementoGCMid'
                                            value="{{$consulta->complementoGCMid}}" placeholder="Ejemplo: 276381">
                                    </div>
                                </div>

                                <div class="sub-title">* Tipo:</div>
                                @if($errors->first('idTipoEquipo'))
                                <i> {{ $errors->first('idTipoEquipo') }}</i>
                                @endif
                                <div>
                                    <select name='idTipoEquipo' id='idTipoEquipo' class="form-control">
                                        <option value='{{$idTipoSel}}'>{{$nomTipo}}</option>
                                        @foreach($tipo as $t)
                                        @if($t->activo=="si")
                                        <option value='{{$t->idTipoEquipo}}'>{{$t->tipoEquipo}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sub-title">* Subtipo:</div>
                                @if($errors->first('idSubtipoEquipo'))
                                <i> {{ $errors->first('idSubtipoEquipo') }}</i>
                                @endif
                                <div>
                                    <select name='idSubtipoEquipo' id='idSubtipoEquipo' class="form-control">
                                        <option value='{{$idSubtipoSel}}'>{{$nomSubtipo}}</option>
                                        @foreach($subtipo as $sub)
                                        @if($sub->activo=="si")
                                        <option value='{{$sub->idSubtipoEquipo}}'>{{$sub->subtipoEquipo}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sub-title">* Serie:</div>
                                <div>
                                    @if($errors->first('serie'))
                                    <i> {{ $errors->first('serie') }}</i>
                                    @endif
                                    {{Form::text('serie',$consulta->serie,['class' => 'form-control', 'placeholder' => 'Ejemplo: 00001203'])}}
                                </div>
                                <div class="sub-title">Datos para certificado:</div>
                                <div>
                                    {{Form::textarea('datosCertificado',$consulta->datosCertificado,['class' => 'form-control','rows'=>'3'])}}
                                </div>
                                <!-- {{Form::text('cambio',$consulta->cambio,['class' => 'form-control','rows'=>'3'])}} -->
                                @if($consulta->cambio=="Si")
                                    <div class="sub-title">Equipos para cambio:</div>
                                    <div>
                                        {{Form::radio('cambio', 'Si', true, ['id'=>'cambioSi'])}} Si &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        {{Form::radio('cambio', 'No', false, ['id'=>'cambioNo'])}} No
                                    </div>
                                    <div id = "mostrar" >
                                        <div class="sub-title">Marca: </div>
                                            <select name='idMarcaRefa' class="form-control" id="idMarcaRefa">
                                                <option value="">{{$nomMarca}}</option> 
                                                @foreach($marca as $mr)
                                                    @if($mr->activo=="Si")
                                                    <option value='{{$mr->idMarcaRefa}}'>{{$mr->marcaRefaccion}}</option>
                                                    @endif
                                                @endforeach 
                                            </select>
                                        <div class="sub-title">Tipo de refacción: </div>
                                            <select name='idTipoRefacciones' class="form-control" id="idTipoRefacciones">
                                                <option value="">{{$nombreTipoRefaccion}}</option>  
                                                @foreach($refSin as $mr)
                                                    @if($mr->activo=="Si")
                                                    <option value='{{$mr->idTipoRefacciones}}'>{{$mr->nombreTipoRefaccion}}</option>
                                                    @endif
                                                @endforeach 
                                            </select>
                                            <div class="sub-title">Refacciones disponibles: </div>
                                            {{Form::hidden('idRefaccionOld',$consulta->idRefaccion,['class' => 'form-control consulta','readonly' => 'true'])}}
                                            <select name="idRefaccion" id="comboRef" class="form-control mi-selector">
                                                <option value="">{{$codigo}}-{{$nombreRefaccion}}-{{$serie}}</option>
                                                @foreach($refaccionUso as $mr)
                                                    @if($mr->activo=="Si")
                                                    <option value='{{$mr->idRefaccion}}'>{{$mr->codigoRefaccion}}-{{$mr->nombreRefaccion}}-{{$mr->serie}}</option>
                                                    @endif
                                                @endforeach   
                                            </select>
                                        <div class="consulta">        
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="sub-title">Numero de parte: </div>
                                                    <div>
                                                        {{Form::text('parte',$parte,['class' => 'form-control consulta','readonly' => 'true'])}}
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="sub-title">Numero de serie: </div>
                                                    <div>
                                                        {{Form::text('serieNum',$serie,['class' => 'form-control consulta','readonly' => 'true'])}}
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="sub-title">Código:</div>
                                            <div id='codigoRefaccion'>
                                                {{Form::text('codigoRefaccion',$codigo,['class' => 'form-control consulta','readonly' => 'true'])}}
                                            </div>
                                            <div class="sub-title">Observaciones:</div>
                                            <div id='codigoRefaccion'>
                                                {{Form::text('observaciones',$observaciones,['class' => 'form-control consulta','readonly' => 'true'])}}
                                            </div>
                                        </div>    
                                    </div>
                               @else
                                <div class="sub-title">Equipos para cambio:</div>
                                    <div>
                                        {{Form::radio('cambio', 'Si', false, ['id'=>'cambioSi'])}} Si &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        {{Form::radio('cambio', 'No', True, ['id'=>'cambioNo'])}} No
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
                                        <select name="idRefaccion" id="comboRef" class="form-control mi-selector2">
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
                                                        {{Form::text('serieNum',old('serie'),['class' => 'form-control consulta','readonly' => 'true'])}}
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
                               @endif
                               
                            </div>

                            <div class="form-group col-md-6">
                                <div class="sub-title">* Marca:</div>
                                <div>
                                    @if($errors->first('marca'))
                                    <i> {{ $errors->first('marca') }}</i>
                                    @endif
                                    {{Form::text('marca',$consulta->marca,['class' => 'form-control', 'placeholder' => 'Ejemplo: SEW'])}}
                                </div>
                                <div class="sub-title">* Modelo:</div>
                                <div>
                                    @if($errors->first('modelo'))
                                    <i> {{ $errors->first('modelo') }}</i>
                                    @endif
                                    {{Form::text('modelo',$consulta->modelo,['class' => 'form-control', 'placeholder' => 'Ejemplo: 206'])}}
                                </div>

                                <div class="sub-title">* Estatus de reparación técnica:</div>
                                <div>
                                    @if($errors->first('estatus'))
                                    <p><i> {{ $errors->first('estatus') }}</i></p>
                                    @endif
                                    <select name="estatus" class="form-control">
                                        <option value="<?php echo ($consulta->estatus) ?>">
                                            <?php echo ($consulta->estatus) ?></option>
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
                                    <p><i> {{ $errors->first('estatusEntrega') }}</i></p>
                                    @endif
                                    <select name="estatusEntrega" class="form-control">
                                        <option value="<?php echo ($consulta->estatusEntrega) ?>">
                                            <?php echo ($consulta->estatusEntrega) ?></option>
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
                                    @if($errors->first('recurso'))
                                    <i> {{ $errors->first('recurso') }}</i>
                                    @endif
                                    {{Form::text('recurso',$consulta->recurso,['class' => 'form-control', 'placeholder' => 'Ejemplo: 12345678'])}}
                                </div>
                                <div class="sub-title">Descripción de falla:</div>
                                <div>
                                    {{Form::textarea('descripcionFalla',$consulta->descripcionFalla,['class' => 'form-control'])}}
                                </div>

                                @if($consulta->cambio=="Si")
                                 <div id = "mostrar2">
                                    <div class="consulta2">        
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="sub-title">Foto placa: </div>
                                                <div>
                                                    <center>
                                                    @if($fotoPlaca =="" || $fotoPlaca =="Sin archivo")
                                                        <div style="color:red" >Sin foto</div></center>
                                                    @else
                                                        <div><a target="_blank" href="{{asset ('public/archivos/'.$fotoPlaca)}}">
                                                        <img src="{{asset ('public/archivos/'.$fotoPlaca)}}" height=100 width=100></a></div>
                                                                    
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="sub-title">Foto principal: </div>
                                                <div>
                                                    <center>
                                                    @if($fotoPrincipal =="" || $fotoPrincipal =="Sin archivo")
                                                        <div style="color:red" >Sin foto</div></center>
                                                    @else
                                                        <div><a target="_blank" href="{{asset ('public/archivos/'.$fotoPrincipal)}}">
                                                        <img src="{{asset ('public/archivos/'.$fotoPrincipal)}}" height=100 width=100></a></div>
                                                                    
                                                    @endif
                                                </div>
                                            </div>                                            
                                        </div>                                    
                                    </div>    
                                </div>
                                @else
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
                                @endif
                            </div>

                        </div>
                        <!--tercer tab Archivos --->
                        <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                            aria-labelledby="pills-contact-tab">

                            <!-- columna 1 -->
                            <div class="form-group col-md-4">

                                <!--foto vista superior:  -->
                                @foreach($cvistaSuperior as $vsup)
                                @if($vsup->vistaSuperior=='Sin archivo')
                                <div class="sub-title">* Subir foto vista superior:</div>
                                <div>
                                    @if($errors->first('vistaSuperior'))
                                    <i> {{ $errors->first('vistaSuperior') }}</i>
                                    @endif
                                    {{Form::file('vistaSuperior',['class' => 'form-control'])}}
                                </div>
                                @else
                                <div class="sub-title">Actualizar foto principal:</div>
                                <div>
                                    @if($errors->first('vistaSuperior'))
                                    <i> {{ $errors->first('vistaSuperior') }}</i>
                                    @endif
                                    {{Form::file('vistaSuperior',['class' => 'form-control'])}}
                                </div>
                                @endif
                                @endforeach
                                <br><br>
                                <!--Foto vista frente -->
                                @foreach($cvistaFrente as $cvf)
                                @if($cvf->vistaFrente=='Sin archivo')
                                <div class="sub-title">* Subir Foto vista frente:</div>
                                <div>
                                    @if($errors->first('vistaFrente'))
                                    <i> {{ $errors->first('vistaFrente') }}</i>
                                    @endif
                                    {{Form::file('vistaFrente',['class' => 'form-control'])}}
                                </div>
                                @else
                                <div class="sub-title">Actualizar Foto vista frente</div>
                                <div>
                                    @if($errors->first('vistaFrente'))
                                    <i> {{ $errors->first('vistaFrente') }}</i>
                                    @endif
                                    {{Form::file('vistaFrente',['class' => 'form-control'])}}
                                </div>
                                @endif
                                @endforeach

                                <!-- vista trasera-->
                                <br><br>

                                @foreach($cvistaTrasera as $cvt)
                                @if($cvt->vistaTrasera=='Sin archivo')
                                <div class="sub-title">Subir Foto vista trasera:</div>
                                <div>
                                    @if($errors->first('vistaTrasera'))
                                    <i> {{ $errors->first('vistaTrasera') }}</i>
                                    @endif
                                    {{Form::file('vistaTrasera',['class' => 'form-control'])}}
                                </div>
                                @else
                                <div class="sub-title">Actualizar Foto vista trasera</div>
                                <div>
                                    @if($errors->first('vistaTrasera'))
                                    <i> {{ $errors->first('vistaTrasera') }}</i>
                                    @endif
                                    {{Form::file('vistaTrasera',['class' => 'form-control'])}}
                                </div>
                                @endif
                                @endforeach
                            </div>
                            <!-- columna 2 iconos de descarga-->
                            <div class="form-group col-md-2">
                                <div>
                                    <center>
                                        <div style='text-align'>
                                            @foreach($cvistaSuperior as $vvistaSuperior)
                                            @if($vvistaSuperior->vistaSuperior=='Sin archivo')
                                            <br><br>
                                            <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                            @else
                                            <br>
                                            <a href="{{asset('archivos/'.$vvistaSuperior->vistaSuperior)}}"
                                                target="_blank">
                                                <img src="{{asset('img/descarga.png')}}" height=50 width=50>
                                                <h6>Descargar foto vista superior</h6>
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
                                            @foreach($cvistaFrente as $cvfi)
                                            @if($cvfi->vistaFrente=='Sin archivo')
                                            <br>
                                            <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                            @else
                                            <a href="{{asset('archivos/'.$cvfi->vistaFrente)}}" target="_blank">
                                                <img src="{{asset('img/descarga.png')}}" height=50 width=50>
                                                <h6>Descargar foto vista Frente</h6>
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
                                            @foreach($cvistaTrasera as $cvti)
                                            @if($cvti->vistaTrasera=='Sin archivo')
                                            <br><br><br>
                                            <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                            @else
                                            <a href="{{asset('archivos/'.$cvti->vistaTrasera)}}" target="_blank">
                                                <img src="{{asset('img/descarga.png')}}" height=50 width=50>
                                                <h6>Descargar foto vista Frente</h6>
                                            </a>
                                            @endif
                                            @endforeach
                                        </div>
                                    </center>
                                </div>

                            </div>
                            <!--Columna 3 -->

                            <div class="form-group col-md-4">
                                @foreach($clateralIzquierda as $cvli)
                                @if($cvli->lateralIzquierda=='Sin archivo')
                                <div class="sub-title">Subir Foto lateral izquierda:</div>
                                <div>
                                    @if($errors->first('lateralIzquierda'))
                                    <i> {{ $errors->first('lateralIzquierda') }}</i>
                                    @endif
                                    {{Form::file('lateralIzquierda',['class' => 'form-control'])}}
                                </div>
                                @else
                                <div class="sub-title">Actualizar Foto lateral izquierda:</div>
                                <div>
                                    @if($errors->first('lateralIzquierda'))
                                    <i> {{ $errors->first('lateralIzquierda') }}</i>
                                    @endif
                                    {{Form::file('lateralIzquierda',['class' => 'form-control'])}}
                                </div>
                                @endif
                                @endforeach
                                <br><br>

                                @foreach($clateralDerecha as $cvld)
                                @if($cvld->lateralDerecha=='Sin archivo')
                                <div class="sub-title">Subir Foto lateral derecha:</div>
                                <div>
                                    @if($errors->first('lateralDerecha'))
                                    <i> {{ $errors->first('lateralDerecha') }}</i>
                                    @endif
                                    {{Form::file('lateralDerecha',['class' => 'form-control'])}}
                                </div>
                                @else
                                <div class="sub-title">Actualizar Foto lateral derecha:</div>
                                <div>
                                    @if($errors->first('lateralDerecha'))
                                    <i> {{ $errors->first('lateralDerecha') }}</i>
                                    @endif
                                    {{Form::file('lateralDerecha',['class' => 'form-control'])}}
                                </div>
                                @endif
                                @endforeach
                                <br><br>

                                @foreach($cplaca_1 as $cp1)
                                @if($cp1->placa_1=='Sin archivo')
                                <div class="sub-title">* Subir Foto placa 1:</div>
                                <div>
                                    @if($errors->first('placa_1'))
                                    <i> {{ $errors->first('placa_1') }}</i>
                                    @endif
                                    {{Form::file('placa_1',['class' => 'form-control'])}}
                                </div>
                                @else
                                <div class="sub-title">Actualizar Foto placa 1:</div>
                                <div>
                                    @if($errors->first('placa_1'))
                                    <i> {{ $errors->first('placa_1') }}</i>
                                    @endif
                                    {{Form::file('placa_1',['class' => 'form-control'])}}
                                </div>
                                @endif
                                @endforeach
                                <br><br>
                                @foreach($cplaca_2 as $cp2)
                                @if($cp2->placa_2=='Sin archivo')
                                <div class="sub-title">Subir Foto placa 2:</div>
                                <div>
                                    @if($errors->first('placa_2'))
                                    <i> {{ $errors->first('placa_2') }}</i>
                                    @endif
                                    {{Form::file('placa_2',['class' => 'form-control'])}}
                                </div>
                                @else
                                <div class="sub-title">Actualizar Foto placa 2:</div>
                                <div>
                                    @if($errors->first('placa_2'))
                                    <i> {{ $errors->first('placa_2') }}</i>
                                    @endif
                                    {{Form::file('placa_2',['class' => 'form-control'])}}
                                </div>
                                @endif
                                @endforeach

                            </div>

                            <div class="form-group col-md-2">
                                <div>
                                    <center>
                                        <div style='text-align'>
                                            @foreach($clateralIzquierda as $cli)
                                            @if($cli->lateralIzquierda=='Sin archivo')
                                            <br><br>
                                            <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                            @else
                                            <br>
                                            <a href="{{asset('archivos/'.$cli->lateralIzquierda)}}"
                                                target="_blank">
                                                <img src="{{asset('img/descarga.png')}}" height=50 width=50>
                                                <h6>Descargar foto vista lateral izquierda</h6>
                                            </a>
                                            @endif
                                            @endforeach
                                        </div>
                                    </center>
                                </div>

                                <div>
                                    <center>
                                        <div style='text-align'>
                                            @foreach($clateralDerecha as $cld)
                                            @if($cld->lateralDerecha=='Sin archivo')
                                            <br><br><br>
                                            <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                            @else
                                            <br>
                                            <a href="{{asset('archivos/'.$cld->lateralDerecha)}}"
                                                target="_blank">
                                                <img src="{{asset('img/descarga.png')}}" height=50 width=50>
                                                <h6>Descargar foto vista lateral </h6>
                                            </a>
                                            @endif
                                            @endforeach
                                        </div>
                                    </center>
                                </div>

                                <div>
                                    <center>
                                        <div style='text-align'>
                                            @foreach($cplaca_1 as $cp1)
                                            @if($cp1->placa_1=='Sin archivo')
                                            <br><br><br><br>
                                            <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                            @else
                                            <br>
                                            <a href="{{asset('archivos/'.$cp1->placa_1)}}" target="_blank">
                                                <img src="{{asset('img/descarga.png')}}" height=50 width=50>
                                                <h6>Descargar foto placa 1</h6>
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
                                            @foreach($cplaca_2 as $cp2)
                                            @if($cp2->placa_2=='Sin archivo')
                                            <br><br><br>
                                            <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                            @else
                                            <br>
                                            <a href="{{asset('archivos/'.$cp2->placa_2)}}" target="_blank">
                                                <img src="{{asset('img/descarga.png')}}" height=50 width=50>
                                                <h6>Descargar foto placa 2</h6>
                                            </a>
                                            @endif
                                            @endforeach
                                        </div>
                                    </center>
                                </div>
                                <br><br>
                            </div>
                        </div>

                        <!-- tab de pruebas de laboratorio  -->
                        
                        <div class="tab-pane fade" id="pruebas" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <div class="form-group col-md-6">
                                
                                <CENTER><div class="sub-title">EVALUACIÓN:</div></CENTER>
                                
                                <div class="sub-title">Test run:</div>
                                <div class="col-sm-3">Inicial:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('testRunInicial',$consulta->testRunInicial,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>
                                <div class="col-sm-3">Final:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('testRunFinal',$consulta->testRunFinal,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>


                                <br>
                                <div class="sub-title">Feedback:</div>
                                <div class="col-sm-3">Inicial:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('feedbackInicial',$consulta->feedbackInicial,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>
                                <div class="col-sm-3">Final:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('feedbackFinal',$consulta->feedbackFinal,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>
                                
                                <br>
                                <div class="sub-title">Baleros:</div>
                                <div class="col-sm-3">Inicial:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('balerosInicial',$consulta->balerosInicial,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>
                                <div class="col-sm-3">Final:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('balerosFinal',$consulta->balerosFinal,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>

                                <br>
                                <div class="sub-title">Gear box:</div>
                                <div class="col-sm-3">Inicial:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('gearBoxInicial',$consulta->gearBoxInicial,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>
                                <div class="col-sm-3">Final:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('gearBoxFinal',$consulta->gearBoxFinal,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>

                                <br>
                                <div class="sub-title">Imanes:</div>
                                <div class="col-sm-3">Inicial:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('imanesInicial',$consulta->imanesInicial,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>
                                <div class="col-sm-3">Final:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('imanesFinal',$consulta->imanesFinal,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>


                                <br>
                                <div class="sub-title">Debanados:</div>
                                <div class="col-sm-3">Inicial:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('debanadosInicial',$consulta->debanadosInicial,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>
                                <div class="col-sm-3">Final:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('debanadosFinal',$consulta->debanadosFinal,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>

                                <br>
                                <div class="sub-title">Encoder:</div>
                                <div class="col-sm-3">Inicial:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('encoderInicial',$consulta->encoderInicial,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>
                                <div class="col-sm-3">Final:</div>
                                <div class="col-sm-3">
                                    {{ Form::text('encoderFinal',$consulta->encoderFinal,['class' => 'form-control rounded-0','placeholder' => 'Ej: OK' ])}}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <CENTER><div class="sub-title">PRUEBAS FINALES:</div></CENTER>
                                
                                <div class="sub-title">Corriente:</div>
                                <div class="col-sm-2">U/Ver:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('corrienteVer',$consulta->corrienteVer,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                <div class="col-sm-2">V/Hor:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('corrienteHor',$consulta->corrienteHor,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                <div class="col-sm-2">W/Ax:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('corrienteAx',$consulta->corrienteAx,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                
                                <br>
                                <div class="sub-title">Tensión:</div>
                                <div class="col-sm-2">U/Ver:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('tensionVer',$consulta->tensionVer,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                <div class="col-sm-2">V/Hor:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('tensionHor',$consulta->tensionHor,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                <div class="col-sm-2">W/Ax:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('tensionAx',$consulta->tensionAx,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>

                                <br>
                                <div class="sub-title">Temp.Motor:</div>
                                <div class="col-sm-12">
                                    {{ Form::text('tempMotor',$consulta->tempMotor,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                
                                <br>
                                <div class="sub-title">Temp.Transmisión:</div>
                                <div class="col-sm-12">
                                    {{ Form::text('tempTransmision',$consulta->tempTransmision,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>

                                <br>
                                <div class="sub-title">Vibración:</div>
                                <div class="col-sm-2">U/Ver:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('vibracionVer',$consulta->vibracionVer,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                <div class="col-sm-2">V/Hor:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('vibracionHor',$consulta->vibracionHor,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                <div class="col-sm-2">W/Ax:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('vibracionAx',$consulta->vibracionAx,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>

                                <br>
                                <div class="sub-title">Enfriamiento:</div>
                                <div class="col-sm-2">U/Ver:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('enfriamientoVer',$consulta->enfriamientoVer,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                <div class="col-sm-2">V/Hor:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('enfriamientoHor',$consulta->enfriamientoHor,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>
                                <div class="col-sm-2">W/Ax:</div>
                                <div class="col-sm-2">
                                    {{ Form::text('enfriamientoAx',$consulta->enfriamientoAx,['class' => 'form-control rounded-0','placeholder' => 'Ej:0.89' ])}}
                                </div>                                                             
                            </div>
                            <div align="right">
                            @if($cuantosdatosFactura > 0)
                                <button id="pdf" class="btn btn-default btn-sm">
                                    Generar PDF
                                </button>
                            @else
                                <button id="pdfNO"class="btn btn-default btn-sm">
                                    Generar PDF
                                </button>
                            @endif
                            </div>
                        </div>
                        
                        
                        <!--TAB QR CON BOTON DE GUARDAR --->
                        <div class="tab-pane fade" id="qr" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <div class="form-group col-md-6">
                                    <div class="sub-title">Información para ingresar a la etiqueta QR:</div>
                                        @if($consulta->textoQr == 'SinEtiquetaQr')
                                        <textarea name="textoQr" id="textoQr" class="form-control rounded-0" rows="5"
                                            placeholder="Ingresar información..."></textarea>
                                        @else
                                        <textarea name="textoQr" id="textoQr" class="form-control rounded-0" rows="5"
                                            placeholder="Ingresar información...">{{$consulta->textoQr}}</textarea>
                                        @endif
                                    <br>
                                    <div class="form-group row">
                                        <div class="form-group col-md-6">
                                            <button type="button" class="btn btn-info btn-lg" id="generarQrEquipo"><i
                                                    class="fa fa-qrcode"></i> Generear etiqueta Qr</button>
                                        </div>
                                    </div>
                                <center>
                                    <a href="{{asset('reporteEquipos')}}"><button type="button"
                                            class="btn btn-default">Cancelar</button></a></center>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="sub-title"></div><br>
                                <div id="imgQr">
                                    @if($consulta->textoQr != 'SinEtiquetaQr')
                                        {!! QrCode::size(117)->generate($consulta->textoQr); !!}
                                    @endif
                                </div>
                            </div>
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
                                                <a href="{{URL::action('seguimientoActController@detalleSeg',['idSegActividad'=>$seg->idSegActividad])}}"
                                                        type="submit" class="btn btn-xs btn-success"> Detalle
                                                    </a> 
                                                </td>
                                            </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    </div>  



                    </div>
                </div> <!-- aqui cierra el div del body -->
            @endif
            <center>{{Form::submit('Guardar',['class' => 'btn  btn-primary','style'=>'margin-bottom:10px'])}}
                                    <a href="{{asset('reporteEquipos')}}"><button type="button" style="margin-bottom:10px"
                                            class="btn btn-default">Cancelar</button></a> <br> </center>
            </div>
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
$("#idc").click(function() {
    $("#idp").load('{{url('comboca1')}}' + '?r=' + Date.now() + '&idc=' + this.options[this.selectedIndex].value);
    // alert("mensaje correcto")
});
</script>
<script type="text/javascript">
$("#idTipoEquipo").click(function() {
    $("#idSubtipoEquipo").load('{{url('comboca2')}}' + '?r=' + Date.now() + '&idTipoEquipo=' + this.options[this.selectedIndex].value);
    // alert("mensaje correcto")
});
</script>
<script type="text/javascript">
$("#idp").click(function() {
    $("#divgcmid").load('{{url('generagcmid')}}' + '?idp=' + this.options[this.selectedIndex].value);
    $("#divinfo").load('{{url('info')}}' + '?' + $(this).closest('form').serialize());
    $("#divinfo").show("slow");
    //alert("mensaje correcto")
});
$("#pdf").click(function (e) {
    var a=$('#idEquipos').val();    
    window.open('{{url('pdfEquipoReparado')}}' + '?eWqt='+a);
    e.preventDefault(); 

});
$("#pdfNO").click(function (e) {
    alert("Este equipo no ha sido facturado")
    e.preventDefault(); 
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
    // $("#comboRef,#idMarcaRefa").load('{{url('eliminarCambio')}}' + '?' + $(this).closest('form').serialize());
});
$("#idMarcaRefa").change(function(){
    // alert("hola");
    $("#idTipoRefacciones").load('{{url('comboTipoRefaccionCambio')}}' + '?r=' + Date.now() + '&idMarcaRefa=' + this.options[this.selectedIndex].value);
    $("#comboRef, #numeroParte, #numeroSerie, #codigo, #observaciones").val('');
});

$("#idTipoRefacciones").change(function() {
        $("#comboRef").load('{{url('comboRefaccionesCambio')}}' + '?r=' + Date.now() + '&idTipoRefacciones=' + this.options[this.selectedIndex].value);
        $("#numeroParte, #numeroSerie, #codigo, #observaciones").val('');
        $('.mi-selector').select2();
        $('.mi-selector2').select2();
    });

$("#comboRef").click(function() {
    $('.mi-selector').select2();
        
    });
$("#comboRef").change(function() {
        $(".consulta").load('{{url('datosRefaccion')}}' + '?r=' + Date.now() + '&idRefaccion=' + this.options[this.selectedIndex].value);
        
    });
$("#comboRef").change(function() {
        
        $(".consulta2").load('{{url('datosRefaccion2')}}' + '?r=' + Date.now() + '&idRefaccion=' + this.options[this.selectedIndex].value);
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
    </style>
    @stop