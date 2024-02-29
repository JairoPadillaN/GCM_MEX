@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {
    $(".cargar").click();
});
</script>

{{Form::open(['route' => 'editarEquipos','files'=>true])}}
{{Form::token()}}
<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Reporte de la empresa <small style="color:#FF0000;">{{$consultap->razonSocial}}</small></h1><br><br>
            <div class="card-title">
                <div class="title">Ingrese los datos del equipo recibido</div>
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
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#piezas" role="tab"
                        aria-controls="pills-contact" aria-selected="false">División de piezas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#detalleCotizacion" role="tab"
                        aria-controls="pills-contact" aria-selected="false">Detalle de cotización</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#archivosFactura" role="tab"
                        aria-controls="pills-contact" aria-selected="false">Archivos de servicio</a>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">

                <!--Primer tab Datos de Factura --->
                <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="form-group col-md-6">
                        {{Form::hidden('idEquipos',$consulta->idEquipos)}}

                        <div class="sub-title">Empresa que da seguimiento:</div>
                        <div>
                            {{Form::text('nombreEmpresa',$consulta->nombreEmpresa,['class' => 'form-control','readonly'=>'readonly'])}}
                        </div>

                        <div class="sub-title">* Folio recepcion de equipo:</div>
                        <div>
                            @if($errors->first('folioRecepcion'))
                            <i> {{ $errors->first('folioRecepcion') }}</i>
                            @endif
                            <div id='divRadioGCM'>
                                <input class="form-control" type='text' name='folioRecepcion' id='folioRecepcion'
                                    readonly='readonly' value="{{$consulta->folioRecepcion}}">
                            </div>
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
                            {{Form::text('fechaRecoleccion',$consulta->fechaRecoleccion,['class' => 'form-control','readonly'=>'readonly'])}}
                        </div>

                        <div class="sub-title">* Fecha de registro: </div>
                        <div>
                            @if($errors->first('fechaRegistro'))
                            <i> {{ $errors->first('fechaRegistro') }}</i>
                            @endif
                            {{Form::text('fechaRegistro',$consulta->fechaRegistro,['class' => 'form-control','readonly'=>'readonly'])}}
                        </div>
                        <div class="sub-title">* Importancia:</div>
                        @if($errors->first('importancia'))
                        <i> {{ $errors->first('importancia') }}</i>
                        @endif
                        <div>
                            {{Form::text('importancia',$consulta->importancia,['class' => 'form-control','readonly'=>'readonly'])}}
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
                            {{Form::text('personaEntrega',$consulta->personaEntrega,['class' => 'form-control', 'placeholder' => 'Ejemplo: Luis Sánchez Júarez','readonly'=>'readonly'])}}
                        </div>

                        <div class="sub-title">* Persona que recibe:</div>
                        <div>
                            @if($errors->first('personaRecibe'))
                            <i> {{ $errors->first('personaRecibe') }}</i>
                            @endif
                            {{Form::text('personaRecibe',$consulta->personaRecibe,['class' => 'form-control', 'placeholder' => 'Ejemplo: Jorge Cisneros Hernández','readonly'=>'readonly'])}}
                        </div>
                        <div class="sub-title">Número de documento salida cliente:</div>
                        <div>
                            @if($errors->first('numeroDocumentoSalida'))
                            <i> {{ $errors->first('numeroDocumentoSalida') }}</i>
                            @endif
                            {{Form::text('numeroDocumentoSalida',$consulta->numeroDocumentoSalida,['class' => 'form-control', 'placeholder' => 'Ejemplo: 00001203','readonly'=>'readonly'])}}
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
                            <div class="sub-title">Subir documento de salida cliente:</div>
                            <div>
                                @if($errors->first('archivoDocumentoSalida'))
                                <i> {{ $errors->first('archivoDocumentoSalida') }}</i>
                                @endif
                                {{Form::file('archivoDocumentoSalida',['class' => 'form-control'])}}
                            </div>
                            @else
                            <div class="sub-title">Actualizar documento de salida cliente:</div>
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
                                        @if($dvs->archivoValeSalida=='Sin archivo' ||
                                        $dvs->archivoValeSalida=='')
                                        <br><br>
                                        <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                        @else
                                        <br>
                                        <a href="{{asset('archivos/'.$dvs->archivoValeSalida)}}" target="_blank">
                                            <img src="{{asset('img/descarga.png')}}" target="_blank" height=50
                                                width=50>
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
                                        @if($ds->archivoDocumentoSalida=='Sin archivo' ||
                                        $ds->archivoDocumentoSalida=='')
                                        <br><br>
                                        <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                        @else
                                        <br>
                                        <a href="{{asset('archivos/'.$ds->archivoDocumentoSalida)}}"
                                            target="_blank">
                                            <img src="{{asset('img/descarga.png')}}" height=50 width=50>
                                            <h6>Descargar documento de salida</h6>
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
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
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
                                    value="{{$consulta->complementoGCMid}}" placeholder="Ejemplo: 276381"
                                    readonly='readonly'>
                            </div>
                        </div>

                        <div class="sub-title">* Tipo:</div>
                        @if($errors->first('idTipoEquipo'))
                        <i> {{ $errors->first('idTipoEquipo') }}</i>
                        @endif
                        <div>
                            <select name='idTipoEquipo' id='idTipoEquipo' class="form-control">
                                <option value='{{$idTipoSel}}'>{{$nomTipo}}</option>
                            </select>
                        </div>
                        <div class="sub-title">* Subtipo:</div>
                        @if($errors->first('idSubtipoEquipo'))
                        <i> {{ $errors->first('idSubtipoEquipo') }}</i>
                        @endif
                        <div>
                            <select name='idSubtipoEquipo' id='idSubtipoEquipo' class="form-control">
                                <option value='{{$idSubtipoSel}}'>{{$nomSubtipo}}</option>
                            </select>
                        </div>
                        <div class="sub-title">* Serie:</div>
                        <div>
                            @if($errors->first('serie'))
                            <i> {{ $errors->first('serie') }}</i>
                            @endif
                            {{Form::text('serie',$consulta->serie,['class' => 'form-control', 'placeholder' => 'Ejemplo: 00001203','readonly'=>'readonly'])}}
                        </div>
                        <div class="sub-title">Datos para certificado:</div>
                        <div>
                            {{Form::textarea('datosCertificado',$consulta->datosCertificado,['class' => 'form-control','readonly'=>'readonly'])}}
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="sub-title">* Marca:</div>
                        <div>
                            @if($errors->first('marca'))
                            <i> {{ $errors->first('marca') }}</i>
                            @endif
                            {{Form::text('marca',$consulta->marca,['class' => 'form-control', 'placeholder' => 'Ejemplo: LouisV','readonly'=>'readonly'])}}
                        </div>
                        <div class="sub-title">* Modelo:</div>
                        <div>
                            @if($errors->first('modelo'))
                            <i> {{ $errors->first('modelo') }}</i>
                            @endif
                            {{Form::text('modelo',$consulta->modelo,['class' => 'form-control', 'placeholder' => 'Ejemplo: 206','readonly'=>'readonly'])}}
                        </div>

                        <div class="sub-title">* Estatus:</div>
                        <div>
                            @if($errors->first('estatus'))
                            <p><i> {{ $errors->first('estatus') }}</i></p>
                            @endif
                            <select name="estatus" class="form-control">
                                <option value="<?php echo ($consulta->estatus) ?>">
                                    <?php echo ($consulta->estatus) ?></option>
                            </select>
                        </div>
                        <div class="sub-title">Recurso:</div>
                        <div>
                            {{Form::text('recurso',$consulta->recurso,['class' => 'form-control','readonly'=>'readonly'])}}
                        </div>
                        <div class="sub-title">Descripción de falla:</div>
                        <div>
                            {{Form::textarea('descripcionFalla',$consulta->descripcionFalla,['class' => 'form-control','readonly'=>'readonly'])}}
                        </div>

                    </div>

                </div>
                <!--tercer tab Archivos --->
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">

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
                        <div class="sub-title">Actualizar foto vista superior:</div>
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
                                    @if($vvistaSuperior->vistaSuperior=='Sin archivo' ||
                                    $vvistaSuperior->vistaSuperior=='')
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
                                    @if($cvfi->vistaFrente=='Sin archivo' || $cvfi->vistaFrente=='')
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
                                    @if($cvti->vistaTrasera=='Sin archivo' || $cvti->vistaTrasera=='')
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
                                    @if($cli->lateralIzquierda=='Sin archivo' || $cli->lateralIzquierda=='')
                                    <br><br>
                                    <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                    @else
                                    <br>
                                    <a href="{{asset('archivos/'.$cli->lateralIzquierda)}}" target="_blank">
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
                                    @if($cld->lateralDerecha=='Sin archivo' || $cld->lateralDerecha=='')
                                    <br><br><br>
                                    <img src="{{asset('img/descargano.png')}}" height=50 width=50>
                                    @else
                                    <br>
                                    <a href="{{asset('archivos/'.$cld->lateralDerecha)}}" target="_blank">
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
                                    @if($cp1->placa_1=='Sin archivo' || $cp1->placa_1=='')
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
                                    @if($cp2->placa_2=='Sin archivo' || $cp2->placa_2=='')
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

                <!-- piezas -->
                <div class="tab-pane fade" id="piezas" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <br><br>
                    <div id='reportitoDePartesCreadas'>
                        @if($cuantasPartes < 1) <div class="alert alert-warning" role="alert" align="center">
                            <label for="">Este equipo no se ha dividido en partes</label>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr style=" background-color: #C5EBFB;">
                                    <th>GCM ID PARTE</th>
                                    <th>Nombre</th>
                                    <th>Fecha salida</th>
                                    <th>Fecha llegada</th>
                                    <th>Taller</th>
                                    <th>Tipo de servicio</th>
                                    <th>Estatus</th>
                                    <th>
                                        <center>Operaciones</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consultaPartesCreadas as $cpc)
                                <tr>
                                    <td>{{$cpc->GCMidParte}}</td>
                                    <td>{{$cpc->nombreParte}}</td>
                                    <td>{{$cpc->fechaSalida}}</td>
                                    <td>{{$cpc->fechaRecepcion}}</td>
                                    <td>{{$cpc->nombreTaller}}</td>
                                    <td>{{$cpc->prioridadRep}}</td>
                                    <td>{{$cpc->nombreEstatus}}</td>
                                    <td align="center">
                                        <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                                            target='_self'>
                                            <input type="hidden" value="{{$cpc->idParte}}" name="idParte">
                                            <button type="button" class="btn btn-sm btn-info detalle">
                                                <i class="ace-icon fa fa-pencil bigger"> Detalle</i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    <div id="detalleParte">

                    </div>
                </div>
            </div>

            <!-- tab de cotizacion -->
            <div class="tab-pane fade" id="detalleCotizacion" role="tabpanel" aria-labelledby="pills-profile-tab">
                <br>
                
                <div>
                    <div class="alert alert-warning" role="alert" align="center">
                        <label for="">Este equipo no esta asignado a una cotización.</label>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="archivosFactura" role="tabpanel" aria-labelledby="pills-profile-tab">
                <br>
                
                    <div class="alert alert-warning" role="alert" align="center">
                        <label for="">Este equipo no esta asignado a un servicio.</label>
                    </div>
            </div>
        </div>
    </div>
</div>




<script type="text/javascript">
$("#idp").click(function() {
    $("#divgcmid").load('{{url('
        generagcmid ')}}' + '?idp=' + this.options[this.selectedIndex].value);
    $("#divinfo").load('{{url('
        info ')}}' + '?' + $(this).closest('form').serialize());
    $("#divinfo").show("slow");
});
</script>

<script type="text/javascript">
$(".detalle").click(function() {
    $("#detalleParte").load('{{url('
        detallePorParte ')}}' + '?' + $(this).closest('form').serialize());
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