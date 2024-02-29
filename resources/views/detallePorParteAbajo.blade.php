<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
<ul class="nav nav-tabs">
    <li class="active"><a href="#1" data-toggle="tab"><label for="">Editar información de la parte</label> <label
                style="color:#FF0000;">{{$consultaParte->GCMidParte}}</label></a></li>
    <li><a href="#2" data-toggle="tab"><label for="">Servicios a realizar</label> <label
                style="color:#FF0000;"></label></a></li>
    <li><a href="#3" data-toggle="tab"><label for="">Refacciones</label></a></li>
    <li><a href="#4" data-toggle="tab"><label for="">Etiqueta Qr</label></a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="1">
        <div class="container-fluid">
            <br>
            <div class="row">
                <div class="form-group col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-3">GCM ID Parte:</div>
                        <div class="col-sm-9">
                            {{Form::hidden('numParte',$consultaParte->numParte,['class' => 'form-control rounded-0', 'readonly'])}}
                            {{Form::hidden('idParte',$consultaParte->idParte,['class' => 'form-control rounded-0', 'readonly'])}}
                            {{Form::hidden('idEquipos',$consultaParte->idEquipos,['class' => 'form-control rounded-0', 'readonly'])}}
                            {{Form::hidden('GCMid',$consultaParte->GCMid,['class' => 'form-control rounded-0', 'readonly'])}}
                            {{Form::text('GCMidParte',$consultaParte->GCMidParte,['class' => 'form-control rounded-0', 'readonly'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">*Nombre:</div>
                        <div class="col-sm-9">
                            @if($errors->first('nombreParte'))
                            <i> {{ $errors->first('nombreParte') }}</i>
                            @endif
                            {{Form::text('nombreParte',$consultaParte->nombreParte,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: Flecha 234252','id'=>'req1', 'readonly'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">*Fecha de salida:</div>
                        <div class="col-sm-8">
                            @if($errors->first('fechaSalida'))
                            <i> {{ $errors->first('fechaSalida') }}</i>
                            @endif
                            {{Form::text('fechaSalida',$consultaParte->fechaSalida,['class' => 'form-control','id'=>'req2', 'readonly'])}}
                        </div>
                    </div>
                    ¿Qué se realizará?:<textarea name="queReparacion" rows="2" class="form-control rounded-0"
                        placeholder="Ejemplo: Reparar Flecha que esta rota de la punta"
                        readonly="readonly">{!!($consultaParte->queReparacion)!!}</textarea>
                    <br>
                    <div class="form-group row">
                        <div class="col-sm-4">Fecha de recepción:</div>
                        <div class="col-sm-8">
                            {{Form::text('fechaRecepcion',$consultaParte->fechaRecepcion,['class' => 'form-control', 'readonly'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">Orden decompra:</div>
                        <div class="col-sm-8">
                            {{Form::text('ordenCompra',$consultaParte->ordenCompra,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: OC-000043', 'readonly'])}}
                        </div>
                    </div>
                    Archivo de la Orden de compra:
                    @foreach($consultaArchivoOC as $consultaAOC)
                    @if($consultaAOC->archivoOrdenCompra=='Sin archivo')
                    <h5 align="center"><span class="label label-warning">Sin archivo</span></h5>
                    @else
                    <center>
                        <a target="_blank" href="{{asset('archivos/'.$consultaAOC->archivoOrdenCompra)}}"
                            style=" margin-top:1%; margin-bottom: 1%; border-radius: 40px;"
                            class="btn btn-success btn-xs">{{$consultaAOC->archivoOrdenCompra}} <span
                                class="glyphicon glyphicon-floppy-save"></span></a>
                    </center>
                    @endif
                    @endforeach
                    <br>
                    <div class="form-group row">
                        <div class="col-sm-6">Monto:
                            {{Form::text('montoReparacion',$consultaParte->montoReparacion,['id'=>'monto','class' => 'form-control rounded-0', 'readonly'])}}
                        </div>
                        <div class="col-sm-6">Tipo de moneda: <br>
                            {{Form::text('tipoMoneda',$consultaParte->tipoMoneda,['id'=>'tipoMoneda','class' => 'form-control rounded-0', 'readonly'])}}
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-2">*Taller:</div>
                        <div class="col-sm-10">

                            @if($errors->first('idTaller'))
                            <i> {{ $errors->first('idTaller') }}</i>
                            @endif<div>
                                <select name='idTaller' id='idTaller' class="form-control rounded-0">
                                    <option value='{{$idTallerSel}}'>{{$nombreTaller}} - {{$tipoTaller}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">¿Quién llevó?:</div>
                        <div class="col-sm-9">
                            {{Form::text('quienllevo',$consultaParte->quienllevo,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: Pedro Perez Lopez', 'readonly'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">¿Quién recibió?:</div>
                        <div class="col-sm-8">
                            {{Form::text('quienRecibio',$consultaParte->quienRecibio,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: Carlos Robles Carmona', 'readonly'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2">Prioridad:</div>
                        <div class="col-sm-10">
                            {{Form::text('prioridadRep',$consultaParte->prioridadRep,['class' => 'form-control rounded-0', 'readonly'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3" align="center">*Foto:
                            @foreach($consultaFotoParte as $consultaFp)
                            @if($consultaFp->fotoParte=='Sin archivo')
                            <h5 align="center"><span class="label label-warning">Sin archivo</span></h5>
                            @else
                            <a target="_blank" href="{{asset ('public/archivos/'.$consultaFp->fotoParte)}}">
                                <img src="{{asset ('public/archivos/'.$consultaFp->fotoParte)}}" height=80 width=80>
                            </a>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <div class="col-sm-2">*Estatus:</div>
                        <div class="col-sm-10">
                            @if($errors->first('idEstatus'))
                            <i> {{ $errors->first('idEstatus') }}</i>
                            @endif<div>
                                <select name='idEstatus' id='idEstatus' class="form-control rounded-0">
                                    <option value='{{$idEstatusSel}}'>{{$nombreEstatus}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="tab-pane" id="2">
        <div class="container-fluid">
            <br>
            <div id="reporteServiciosAsignados">
                @if($cuantasYaHay < 1) <div class="alert alert-warning" role="alert" align="center">
                    <label for="">No se le han asignado servicios de reparación a esta pieza.</label>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr style=" background-color: #C5EBFB;">
                            <th>Servicio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consultaServiciosAsignados as $csa)
                        <tr>
                            <td>{{$csa->nombreServTaller}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

    </div>
</div>

<div class="tab-pane" id="3">
    <div class="container-fluid">
        <br><br>
        <div id="reporteRefaccionesAsignadas">
            @if($cuantasRefaccionesYaHay < 1) <div class="alert alert-warning" role="alert" align="center">
                <label for="">No se han agregado refacciones.</label>
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr style=" background-color: #C5EBFB;">
                        <th>Refacción</th>
                        <th>Tipo de refacción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($consultaRefaccionesAsignados as $cra)
                    <tr>
                        <td>{{$cra->nombreRefaccion}}</td>
                        <td>{{$cra->nombreTipoRefaccion}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
</div>

<div class="tab-pane" id="4">
    <div class="container-fluid">
        <br>
        <div class="form-group row">
            <div class="form-group col-md-6">
                <div class="form-group row">
                    <div class="form-group col-md-6">
                        GCM
                        ID:{{Form::text('GCMid',$consultaQr1->GCMid,['class' => 'form-control rounded-0', 'readonly'])}}
                    </div>
                    <div class="form-group col-md-6">
                        GCM ID
                        Parte:{{Form::text('GCMidParte',$consultaQr1->GCMidParte,['class' => 'form-control rounded-0', 'readonly'])}}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group col-md-6">
                        Tipo de
                        equipo:{{Form::text('tipoEquipo',$consultaQr2->tipoEquipo,['class' => 'form-control rounded-0', 'readonly'])}}
                    </div>
                    <div class="form-group col-md-6">
                        Subclasificación:{{Form::text('subtipoEquipo',$consultaQr2->subtipoEquipo,['class' => 'form-control rounded-0', 'readonly'])}}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group col-md-6">
                        Nombre de la
                        pieza:{{Form::text('nombreParte',$consultaQr1->nombreParte,['class' => 'form-control rounded-0', 'readonly'])}}
                        <br>
                        Servicios de reparacion:<textarea name="queReparacion" rows="3" class="form-control rounded-0"
                            disabled>{{$consultaQr1->queReparacion}}</textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <center>
                            <!-- {!! QrCode::size(117)->generate('GCM ID: '.$consultaQr1->GCMid."\n".'GCM ID parte: '.$consultaQr1->GCMidParte."\n".'Tipo de equipo: '.$consultaQr2->tipoEquipo."\n".'Subclasificación: '.$consultaQr2->subtipoEquipo."\n".'Nombre de la pieza: '.$consultaQr1->nombreParte."\n".'Servicios de reparacion: '.$consultaQr1->queReparacion); !!} -->
                            {!! QrCode::size(117)->generate('GCM ID parte: '.$consultaQr1->GCMidParte); !!}
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>