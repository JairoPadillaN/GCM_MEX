@extends('principal')
@section('contenido')

{{Form::open(['files'=>true])}}
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
                        </center> <br><br>
                        <div class="card-title">
                        </div>
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

                    <center>
                        <h2>Avance de responsables asignados a la actividad</h2> <br><br>
                    </center>

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
                                    <th>Operaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($seguimientoA as $seg)
                                <tr>
                                    <td>{{$seg->actor}}</td>
                                    <td>{{$seg->tipo}}</td>
                                    <td>{{$seg->queRealiza}}</td>
                                    
                                    @if($seg->porcentaje == '')
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
                                
                                    
                                    <td align="center">
                                                <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                                                    <input type='hidden' value='{{$seg->idSegActividad}}' name='idSegActividad' id='idSegActividad'>
                                                    <input type='hidden' value='{{$seg->idu}}' name='idu' id='idu'>
                                                    <!-- <button type="button" class="btn btn-sm btn-danger borrar">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </button>
                                                    <br><br> -->
                                                    <button type="button" class="btn btn-sm btn-success info">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    </div>
                                                </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div id="infoUsuario"></div>
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
    $('.info').click(function(){
        // alert("hola");
        // var detSeg = $("#idDetSeg" ).val();
        // console.log(detSeg);
        $("#infoUsuario").load('{{url('infoUsuarios')}}' + '?' + $(this).closest('form').serialize());
    
});
});
</script>

@stop