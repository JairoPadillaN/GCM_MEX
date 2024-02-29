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
                                
                                <td>
                                <div class="progress">
                                <div class="progress-bar progress-bar-danger" role="progressbar"
                                    aria-valuemin="0" aria-valuemax="0"
                                    style="width: 100%">0% completado
                                    <span class="sr-only"></span>
                                </div>
                                </div>
                                </td>
                        
                                <td>0 de 0</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
    $('.info').click(function(){
        // alert("hola");
        // var detSeg = $("#idDetSeg" ).val();
        // console.log(detSeg);
        $("#infoUsuario").load('{{url('infoUsuarios')}}' + '?' + $(this).closest('form').serialize());
    
});
});
</script>

@stop