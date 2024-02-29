@extends('principal')
@section('contenido')

{{Form::open(['route'=>'seguimientoPorUsuario','files'=>true])}}
{{Form::token()}}
<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-body">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="cargar" class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                        role="tab" aria-controls="pills-home">Proyecto</a>
                </li>
                <li class="nav-item">
                    <a  class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                        aria-controls="pills-profile" aria-selected="false">Equipos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab"
                        aria-controls="pills-contact" aria-selected="false">Servicio</a>
                </li> 
                <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#contactos" role="tab"
                        aria-controls="pills-contact" aria-selected="false">Contactos</a>
                </li> 
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">                  
                    <div class="panel-heading">
                        <center>
                            <h1>Detalle del seguimiento</h1>
                        </center> <br>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr style="background-color: #93BEE6">
                                    <th>Folio</th>
                                    <th>Fecha de creación</th>
                                    <th>Asunto</th>
                                    <th>Creado por</th>
                                    <th>Periódo de atención</th>
                                    <th>Nivel de atención</th>
                                    <th>Área responsable</th>
                                    <th>Avance</th>
                                    <th>Atendido por</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consulta as $seg)
                                <tr>

                                    <td>{{$seg->folio}}</td>
                                    <td>{{$seg->fechaCreacion}}</td>
                                    <td>{{$seg->asunto}}</td>
                                    <td>{{$seg->nombreUsuario}} {{$seg->aPaterno}} {{$seg->aMaterno}} </td>
                                    <td>{{$seg->fechaInicio}} - {{$seg->fechaTermino}} </td>
                                    <td>{{$seg->importanciaSeguimiento}}</td>
                                    <td>{{$seg->nombreArea}}</td>
                                    @if($porcentaje->porcentaje == 0)
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

                                    @if($porcentaje->porcentaje > 1 && $porcentaje->porcentaje < 11)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                        style="width: 10%">{{$porcentaje->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($porcentaje->porcentaje > 11 && $porcentaje->porcentaje < 21)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                        style="width: 20%">{{$porcentaje->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($porcentaje->porcentaje > 21 && $porcentaje->porcentaje < 31)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                        style="width: 30%">{{$porcentaje->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($porcentaje->porcentaje > 31 && $porcentaje->porcentaje < 41)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                        style="width: 40%">{{$porcentaje->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($porcentaje->porcentaje > 41 && $porcentaje->porcentaje < 51)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                        style="width: 50%">{{$porcentaje->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($porcentaje->porcentaje > 51 && $porcentaje->porcentaje < 61)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                        style="width: 60%">{{$porcentaje->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($porcentaje->porcentaje > 61 && $porcentaje->porcentaje < 71)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                        style="width: 70%">{{$porcentaje->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($porcentaje->porcentaje > 71 && $porcentaje->porcentaje < 81)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                        style="width: 80%">{{$porcentaje->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($porcentaje->porcentaje > 81 && $porcentaje->porcentaje < 91)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                        style="width: 90%">{{$porcentaje->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($porcentaje->porcentaje > 91 && $porcentaje->porcentaje < 100)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                        style="width: 90%">{{$porcentaje->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($porcentaje->porcentaje == 100)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-success" role="progressbar"
                                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                        style="width: 100%">100% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif
                                    <td>{{$atendidoPor->atendidopor}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($cuantosSeg == 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr style="background-color: #93BEE6">
                                        <th>Nombre de quien atiende</th>
                                        <th>Cargo</th>
                                        <th>¿Qué realiza?</th>
                                        <th>Avance</th>
                                        <th>Estatus de atención</th>
                                        <th>Número de seguimientos</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($seguimientosRealizados as $segRe)
                                    <tr>
                                        <td>{{$segRe->actor}}</td>
                                        <td>{{$segRe->tipo}}</td>
                                        <td>{{$segRe->queRealiza}}</td>
                                        @if($segRe->porcentaje == 0)
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

                                        @if($segRe->porcentaje > 1 && $segRe->porcentaje < 11)
                                        <td>
                                        <div class="progress">
                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                            style="width: 10%">{{$segRe->porcentaje}}% completado
                                            <span class="sr-only"></span>
                                        </div>
                                        </div>
                                        </td>
                                        @endif

                                        @if($segRe->porcentaje > 11 && $segRe->porcentaje < 21)
                                        <td>
                                        <div class="progress">
                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                            style="width: 20%">{{$segRe->porcentaje}}% completado
                                            <span class="sr-only"></span>
                                        </div>
                                        </div>
                                        </td>
                                        @endif

                                        @if($segRe->porcentaje > 21 && $segRe->porcentaje < 31)
                                        <td>
                                        <div class="progress">
                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                            style="width: 30%">{{$segRe->porcentaje}}% completado
                                            <span class="sr-only"></span>
                                        </div>
                                        </div>
                                        </td>
                                        @endif

                                        @if($segRe->porcentaje > 31 && $segRe->porcentaje < 41)
                                        <td>
                                        <div class="progress">
                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                            style="width: 40%">{{$segRe->porcentaje}}% completado
                                            <span class="sr-only"></span>
                                        </div>
                                        </div>
                                        </td>
                                        @endif

                                        @if($segRe->porcentaje > 41 && $segRe->porcentaje < 51)
                                        <td>
                                        <div class="progress">
                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                            style="width: 50%">{{$segRe->porcentaje}}% completado
                                            <span class="sr-only"></span>
                                        </div>
                                        </div>
                                        </td>
                                        @endif

                                        @if($segRe->porcentaje > 51 && $segRe->porcentaje < 61)
                                        <td>
                                        <div class="progress">
                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                            style="width: 60%">{{$segRe->porcentaje}}% completado
                                            <span class="sr-only"></span>
                                        </div>
                                        </div>
                                        </td>
                                        @endif

                                        @if($segRe->porcentaje > 61 && $segRe->porcentaje < 71)
                                        <td>
                                        <div class="progress">
                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                            style="width: 70%">{{$segRe->porcentaje}}% completado
                                            <span class="sr-only"></span>
                                        </div>
                                        </div>
                                        </td>
                                        @endif

                                        @if($segRe->porcentaje > 71 && $segRe->porcentaje < 81)
                                        <td>
                                        <div class="progress">
                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                            style="width: 80%">{{$segRe->porcentaje}}% completado
                                            <span class="sr-only"></span>
                                        </div>
                                        </div>
                                        </td>
                                        @endif

                                        @if($segRe->porcentaje > 81 && $segRe->porcentaje < 91)
                                        <td>
                                        <div class="progress">
                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                            style="width: 90%">{{$segRe->porcentaje}}% completado
                                            <span class="sr-only"></span>
                                        </div>
                                        </div>
                                        </td>
                                        @endif

                                        @if($segRe->porcentaje > 91 && $segRe->porcentaje < 100)
                                        <td>
                                        <div class="progress">
                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                            aria-valuemin="0" aria-valuemax="0"
                                            style="width: 90%">{{$segRe->porcentaje}}% completado
                                            <span class="sr-only"></span>
                                        </div>
                                        </div>
                                        </td>
                                        @endif

                                        @if($segRe->porcentaje == 100)
                                        <td>
                                        <div class="progress">
                                        <div class="progress-bar progress-bar-success" role="progressbar"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                            style="width: 100%">100% completado
                                            <span class="sr-only"></span>
                                        </div>
                                        </div>
                                        </td>
                                        @endif
                                        <td>{{$segRe->statusactividad}} </td>
                                        <td>{{$segRe->numeroseguimientos}}</td>

                                        <td>
                                        <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                                                        <input type='hidden' value='{{$segRe->idSegActividad}}' name='idSegActividad' id='idSegActividad'>
                                                        <input type='hidden' value='{{$segRe->idu}}' name='idUsuario' id='idUsuario'>
                                                        <button type="button" class="btn btn-sm btn-success abrirModalLectura">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    
                                                    </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr style="background-color: #93BEE6">
                                    <th>Nombre de quien atiende</th>
                                    <th>Cargo</th>
                                    <th>¿Qué realiza?</th>
                                    <th>Avance</th>
                                    <th>Estatus de atención</th>
                                    <th>Número de seguimientos</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($seguimientosRealizados as $seg)
                                <tr>
                                    <td>{{$seg->actor}} </td>
                                    <td>{{$seg->tipo}}</td>
                                    <td>{{$seg->queRealiza}}</td>
                                    @if($seg->porcentaje == 0)
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

                                    @if($seg->porcentaje > 1 && $seg->porcentaje < 11)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                        aria-valuemin="0" aria-valuemax="0"
                                        style="width: 10%">{{$seg->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($seg->porcentaje > 11 && $seg->porcentaje < 21)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                        aria-valuemin="0" aria-valuemax="0"
                                        style="width: 20%">{{$seg->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($seg->porcentaje > 21 && $seg->porcentaje < 31)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                        aria-valuemin="0" aria-valuemax="0"
                                        style="width: 30%">{{$seg->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($seg->porcentaje > 31 && $seg->porcentaje < 41)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                        aria-valuemin="0" aria-valuemax="0"
                                        style="width: 40%">{{$seg->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($seg->porcentaje > 41 && $seg->porcentaje < 51)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                        aria-valuemin="0" aria-valuemax="0"
                                        style="width: 50%">{{$seg->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($seg->porcentaje > 51 && $seg->porcentaje < 61)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                        aria-valuemin="0" aria-valuemax="0"
                                        style="width: 60%">{{$seg->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($seg->porcentaje > 61 && $seg->porcentaje < 71)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                        aria-valuemin="0" aria-valuemax="0"
                                        style="width: 70%">{{$seg->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($seg->porcentaje > 71 && $seg->porcentaje < 81)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                        aria-valuemin="0" aria-valuemax="0"
                                        style="width: 80%">{{$seg->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($seg->porcentaje > 81 && $seg->porcentaje < 91)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                        aria-valuemin="0" aria-valuemax="0"
                                        style="width: 90%">{{$seg->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($seg->porcentaje > 91 && $seg->porcentaje < 100)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-warning" role="progressbar"
                                        aria-valuemin="0" aria-valuemax="0"
                                        style="width: 90%">{{$seg->porcentaje}}% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif

                                    @if($seg->porcentaje == 100)
                                    <td>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-success" role="progressbar"
                                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                        style="width: 100%">100% completado
                                        <span class="sr-only"></span>
                                    </div>
                                    </div>
                                    </td>
                                    @endif
                                    <td>{{$seg->statusactividad}}</td>
                                    <td>{{$seg->numeroseguimientos}}</td>

                                    <td>
                                        <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                                            <input type='hidden' value='{{$seg->idSegActividad}}' name='idSegActividad' id='idSegActividad'>
                                            <input type='hidden' value='{{$seg->idu}}' name='idUsuario' id='idUsuario'>
                                            <button type="button" class="btn btn-sm btn-success abrirModalLectura">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <br>
                    <div id="modal"></div>

                    <div class="form-group col-md-6">
                        <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu')!!}">
                        <input name="idSegActividad" id="idSegActividad" type="hidden" readonly="true" value="{{$info->idSegActividad}}">
                        <div class="sub-title">Folio:</div>
                        <div>
                            <input class="form-control" name="folio" type="text" readonly="true" value="{{$idSig}}">
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="sub-title">Creado por:</div>
                                <div>
                                    <input type="text" name="" class="form-control" readonly="true"
                                    value="{!! Session::get('sesionname')!!} {!! Session::get('sesionpaterno')!!} {!!Session::get('sesionmaterno')!!}">

                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="sub-title">Tipo de usuario: </div>
                                <input type="text" name="tipousuario" id="tipousuario" class="form-control" readonly="true" value="{!! Session::get('sesiontipo')!!}">
                            </div>

                        </div>

                        <div class="sub-title">Fecha de seguimiento:</div>
                        <div>
                            {{Form::date('fechaDet', \Carbon\Carbon::now(),['class' => 'form-control'])}}
                        </div>

                        <div class="sub-title">Detalle de actividad:</div>
                        @if($errors->first('detalleSeg'))
                        <i> {{ $errors->first('detalleSeg') }}</i>
                        @endif
                        <div>
                            {{Form::textarea('detalleSeg', old('detalleSeg'),['class' => 'form-control', 'placeholder' => 'Agregar detalles de la actividad asignada.'])}}
                        </div>

                        <div class="sub-title">Próxima fecha de seguimiento:</div>
                        <div>
                            <input type="date" name="fechaSiguiente" id="fechaSiguiente" class="form-control">
                        </div>

                        <input type="hidden" value="{{$porcentajeUltimoUs ->porcentaje}}" id="porcentajeAnt"> 
                        <div class="sub-title">Porcentaje:</div>
                        @if($errors->first('porcentaje'))
                        <i> {{ $errors->first('porcentaje') }}</i>
                        @endif
                        <div>
                            {{Form::number('porcentaje', old('porcentaje'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 100', 'id' => 'porcentajeAct','onchange'=>'validarPorc(this);'])}}
                        </div>

                        <div class="sub-title">Estatus:</div>
                        @if($errors->first('estado'))
                        <i> {{ $errors->first('estado') }}</i>
                        @endif
                        <div>
                            Concluido {{Form::radio('estado','Concluido')}}&nbsp;&nbsp;&nbsp;
                            En desarrollo {{Form::radio('estado','En desarrollo',true)}}&nbsp;&nbsp;&nbsp;
                            Cancelado {{Form::radio('estado','Cancelado')}}&nbsp;&nbsp;&nbsp;
                        </div>

                        <div class="sub-title">Resultado:</div>
                        @if($errors->first('resultadoSeg'))
                        <i> {{ $errors->first('resultadoSeg') }}</i>
                        @endif
                        <div>
                            Exitosa {{Form::radio('resultadoSeg','Exitosa',true)}}&nbsp;&nbsp;&nbsp;
                            No exitosa {{Form::radio('resultadoSeg','No exitosa')}}&nbsp;&nbsp;&nbsp;
                            Sin progreso {{Form::radio('resultadoSeg','Sin progreso')}}&nbsp;&nbsp;&nbsp;
                        </div>

                        <div class="sub-title">Notas:</div>
                        @if($errors->first('notasSeg'))
                        <i> {{ $errors->first('notasSeg') }}</i>
                        @endif
                        <div>
                            {{Form::textarea('notasSeg', old('notasSeg'),['class' => 'form-control', 'placeholder' => 'Agregar notas de la actividad asignada.', 'rows' => '10'])}}
                        </div>

                        <div class="sub-title">Propuesta:</div>
                        @if($errors->first('propuestaSeg'))
                        <i> {{ $errors->first('propuestaSeg') }}</i>
                        @endif
                        <div>
                            {{Form::textarea('propuestaSeg', old('propuestaSeg'),['class' => 'form-control', 'placeholder' => 'Agregar propuestas para la actividad asignada.'])}}
                        </div>

                        

                    </div>

                    <div class="form-group col-md-6">

                        <div class="sub-title" align="center">AGREGAR ARCHIVOS</div>

                                    <div class="sub-title" >Detalle:</div>
                                        <div>
                                            @if($errors->first('detalleArchivo1'))
                                            <i> {{ $errors->first('detalleArchivo1') }}</i>
                                            @endif
                                            {{Form::text('detalleArchivo1',old ('detalleArchivo1'),['class' => 'form-control','id'=>'detalleArchivo1'])}}
                                    </div>                                                
                                    <div class="sub-title">Archivo 1</div>
                                    <div>
                                        {{Form::file('archivo1',['class' => 'form-control'])}}
                                    </div>

                                    <div class="sub-title">¿Quién ve? (Archivo 1):</div>
                                        @if($errors->first('verArchivo1'))
                                        <i> {{ $errors->first('verArchivo1') }}</i>
                                        @endif
                                        <div>
                                            Solo yo {{Form::radio('verArchivo1','Solo yo')}}&nbsp;&nbsp;&nbsp;
                                            Mi área {{Form::radio('verArchivo1','Mi area')}}&nbsp;&nbsp;&nbsp;
                                            Todos {{Form::radio('verArchivo1','Todos')}}&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <br>

                                    <div class="sub-title" >Detalle:</div>
                                        <div>
                                            @if($errors->first('detalleArchivo2'))
                                            <i> {{ $errors->first('detalleArchivo2') }}</i>
                                            @endif
                                            {{Form::text('detalleArchivo2',old ('detalleArchivo2'),['class' => 'form-control','id'=>'detalleArchivo2'])}}
                                    </div>                                                
                                    <div class="sub-title">Archivo 2</div>
                                    <div>
                                        {{Form::file('archivo2',['class' => 'form-control'])}}
                                    </div>

                                    <div class="sub-title">¿Quién ve? (Archivo 2):</div>
                                        @if($errors->first('verArchivo2'))
                                        <i> {{ $errors->first('verArchivo2') }}</i>
                                        @endif
                                        <div>
                                            Solo yo {{Form::radio('verArchivo2','Solo yo')}}&nbsp;&nbsp;&nbsp;
                                            Mi área {{Form::radio('verArchivo2','Mi area')}}&nbsp;&nbsp;&nbsp;
                                            Todos {{Form::radio('verArchivo2','Todos')}}&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <br>

                                    <div class="sub-title" >Detalle:</div>
                                        <div>
                                            @if($errors->first('detalleArchivo3'))
                                            <i> {{ $errors->first('detalleArchivo3') }}</i>
                                            @endif
                                            {{Form::text('detalleArchivo3',old ('detalleArchivo3'),['class' => 'form-control','id'=>'detalleArchivo3'])}}
                                    </div>                                                
                                    <div class="sub-title">Archivo 3</div>
                                    <div>
                                        {{Form::file('archivo3',['class' => 'form-control'])}}
                                    </div>

                                    <div class="sub-title">¿Quién ve? (Archivo 3):</div>
                                        @if($errors->first('verArchivo3'))
                                        <i> {{ $errors->first('verArchivo3') }}</i>
                                        @endif
                                        <div>
                                            Solo yo {{Form::radio('verArchivo3','Solo yo')}}&nbsp;&nbsp;&nbsp;
                                            Mi área {{Form::radio('verArchivo3','Mi area')}}&nbsp;&nbsp;&nbsp;
                                            Todos {{Form::radio('verArchivo3','Todos')}}&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <br>

                                    <div class="sub-title" >Detalle:</div>
                                        <div>
                                            @if($errors->first('detalleArchivo4'))
                                            <i> {{ $errors->first('detalleArchivo4') }}</i>
                                            @endif
                                            {{Form::text('detalleArchivo4',old ('detalleArchivo4'),['class' => 'form-control','id'=>'detalleArchivo4'])}}
                                    </div>                                                
                                    <div class="sub-title">Archivo 4</div>
                                    <div>
                                        {{Form::file('archivo4',['class' => 'form-control'])}}
                                    </div>

                                    <div class="sub-title">¿Quién ve? (Archivo 4):</div>
                                        @if($errors->first('verArchivo4'))
                                        <i> {{ $errors->first('verArchivo4') }}</i>
                                        @endif
                                        <div>
                                            Solo yo {{Form::radio('verArchivo4','Solo yo')}}&nbsp;&nbsp;&nbsp;
                                            Mi área {{Form::radio('verArchivo4','Mi area')}}&nbsp;&nbsp;&nbsp;
                                            Todos {{Form::radio('verArchivo4','Todos')}}&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <br>

                                    <div class="sub-title" >Detalle:</div>
                                        <div>
                                            @if($errors->first('detalleArchivo5'))
                                            <i> {{ $errors->first('detalleArchivo5') }}</i>
                                            @endif
                                            {{Form::text('detalleArchivo5',old ('detalleArchivo5'),['class' => 'form-control','id'=>'detalleArchivo5'])}}
                                    </div>                                                
                                    <div class="sub-title">Archivo 5</div>
                                    <div>
                                        {{Form::file('archivo5',['class' => 'form-control'])}}
                                    </div>

                                    <div class="sub-title">¿Quién ve? (Archivo 5):</div>
                                        @if($errors->first('verArchivo5'))
                                        <i> {{ $errors->first('verArchivo5') }}</i>
                                        @endif
                                        <div>
                                            Solo yo {{Form::radio('verArchivo5','Solo yo')}}&nbsp;&nbsp;&nbsp;
                                            Mi área {{Form::radio('verArchivo5','Mi area')}}&nbsp;&nbsp;&nbsp;
                                            Todos {{Form::radio('verArchivo5','Todos')}}&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <br>

                        <div class="" align="right">
                            <button type="submit" class="btn btn-success btn-default" id="crearSeg">Crear seguimiento
                                <span class="glyphicon glyphicon-plus-sign"></span>
                            </button>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div id="reportitoSeguimientos">
                            <div class="sub-title" align="center">Seguimientos de la actividad:</div>
                            @if($cuantosDet == 0)
                            <div class="alert alert-warning" role="alert" align="center">
                                <label for="">Sin seguimientos.</label>
                            </div>

                            @else
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr style="background-color: #93BEE6;">
                                            <th width="20px">
                                                <font SIZE=2>Número de seguimiento</font>
                                            </th>
                                            <th>
                                                <font SIZE=2>Fecha de avance</font>
                                            </th>
                                            <th>
                                                <font SIZE=2>Fecha y hora de creación</font>
                                            </th>
                                            <th width="30%">
                                                <font SIZE=2>Detalle</font>
                                            </th>
                                            <th>
                                                <font SIZE=2>Estatus</font>
                                            </th>
                                            <th>
                                                <font SIZE=2>Resultado</font>
                                            </th>
                                            <th>
                                                <font SIZE=2>Notas</font>
                                            </th>
                                            <th>
                                                <font SIZE=2>Propuesta</font>
                                            </th>
                                            <th>
                                                <font SIZE=2>Fecha de próximo seguimiento</font>
                                            </th>
                                            <th>
                                                <font SIZE=2>% de avance</font>
                                            </th>
                                            <th colspan=2>
                                                <font SIZE=2>Opciones</font>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($detalle as $det)
                                        <tr>
                                            <td align="center">{{$det->folio}}</td>
                                            <td>{{$det->fechaDet}}</td>
                                            <td>{{$det->created_at}}</td>
                                            <td>{{$det->detalleSeg}}</td>
                                            <td>{{$det->estado}}</td>
                                            <td>{{$det->resultadoSeg}}</td>
                                            <td>{{$det->notasSeg}}</td>
                                            <td>{{$det->propuestaSeg}}</td>
                                            <td>{{$det->fechaSiguiente}}</td>
                                            <td align="center">{{$det->porcentaje}} %</td>
                                            <td align="center">
                                                <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                                                @csrf
                                                    <input type='hidden' value='{{$det->idDetSeg}}' name='idDetSeg' id='idDetSeg'>
                                                    <input type='hidden' value='{{$det->idSegActividad}}' name='idSegActividad' id='idSegActividad'>
                                                    <br><br>
                                                    <button type="button" class="btn btn-sm btn-success ver">
                                                        <i class="fa fa-folder-open"></i>
                                                    </button>
                                                </form>
                                            
                                        @endforeach
                                                <br>
                                                <form action="" method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                                                @csrf
                                                    <input type='hidden' value='{{$det->idDetSeg}}' name='idDetSeg' id='idDetSeg'>
                                                    <input type='hidden' value='{{$det->idSegActividad}}' name='idSegActividad' id='idSegActividad'>
                                                    <button type="button" class="btn btn-sm btn-danger borrar" onclick='location.reload()'>
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            @endif

                            <div id="archivos">
                            
                            </div>
                        </div>
                    </div>


                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="form-group col-md-12">
                        <div id="reporteEquipos">
                        @if($cuantosEquipos==0)
                            <center>
                            <div class="alert alert-warning" role="alert">
                                ¡Sin equipos!
                            </div>
                            </center>
                        @else
                            @include('reporteEquiposActividad')
                        @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="form-group col-md-12">
                        <div id="reporteServicio">
                        @if($cuantosServicios==0)
                            <center>
                            <div class="alert alert-warning" role="alert">
                                ¡Sin servicios!
                            </div>
                            </center>
                        @else
                            @include('reporteServiciosActividad')
                        @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="contactos" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="form-group col-md-12">
                        <div id="reporteContacto">
                        @if($cuantosContactos==0)
                            <center>
                            <div class="alert alert-warning" role="alert">
                                ¡Sin contactos!
                            </div>
                            </center>
                        @else
                            @include('reporteContactosActividad')
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

          
        
    </div>
</div>


    <script type="text/javascript">
$(document).ready(function() {
    $(".cargar").click();
    // $("#crearSeg").click(function() {
        
    //         var porcentajeAnt = $("#porcentajeAnt" ).val();
    //         var porcentajeAct = $("#porcentajeAct" ).val();

    //         if(porcentajeAct < porcentajeAnt){
    //             alert("El porcentaje debe ser mayor al anterior.")
    //         }else{

    //         }
        
    // });

    $('.borrar').click(
        function() {
            // alert("borrar");
            $("#reportitoSeguimientos").load('{{url('borrarSegUs')}}' + '?' + $(this).closest('form').serialize());
            // location.reload();
            
        }
    );

    $('.ver').click(function(){
        
        // var detSeg = $("#idDetSeg" ).val();
        // console.log(detSeg);
        $("#archivos").load('{{url('archivosSeg')}}' + '?' + $(this).closest('form').serialize());
    
});

$(".abrirModalLectura").click(function (e) {
    // var idu = $('#idUsuario').val();
    // var idSegActividad = $('#idSegActividad').val();
    $('#modal').load('{{url('modalSeguimientos')}}' + '?'+ $(this).closest('form').serialize());
    e.preventDefault();
});
});
</script>

<script>
function validarPorc(obj) {
    var porcentajeAnt = $("#porcentajeAnt" ).val();
            var porcentajeAct = $("#porcentajeAct" ).val();

            if(porcentajeAct < porcentajeAnt && porcentajeAct < 100){
                alert("El porcentaje debe ser mayor al anterior.")
            }
    }
</script>

@stop