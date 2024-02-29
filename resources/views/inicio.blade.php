@extends('principal')
@section('contenido')

<script>
$(document).ready(function() {    
    $("#citasAsignadas").load('{{url('reporteCitasAsignadas')}}' + '?' + $(this).closest('form').serialize());
    $("#citasCreadas").load('{{url('reporteCitasCreadas')}}' + '?' + $(this).closest('form').serialize());
    $("#reporteSeguimiento").load('{{url('reporteSeguimientoInicio')}}' + '?' + $(this).closest('form').serialize());
    $(".cargar").click();
});
</script>


<div class="col-md-12">
    <div class="panel panel-default" style="margin-top:-45px">
        
        <div class="panel-body">
            <!-- <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="cargar" class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                        role="tab" aria-controls="pills-home">Reporte de proyectos</a>
                </li>
                <li class="nav-item">
                    <a  class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                        aria-controls="pills-profile" aria-selected="false">Citas asignadas</a>
                </li> -->
                <!-- <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab"
                        aria-controls="pills-contact" aria-selected="false">Citas creadas</a>
                </li> 
            </ul>-->

            <!-- <div class="tab-content" id="pills-tabContent"> -->
                <!-- <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"> -->
                <br><br><br>
                    @if(Session::get('sesiontipo')=="Compras")
                        <img src="{{asset ('archivos/CYMGROUPFONDO.png')}}" alt="" width="100%" height="100%" style="margin-top:-60px">
                    @else
                        <div id="reporteSeguimiento">
                        </div>
                    @endif
                <!-- </div> -->

                <!-- <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <br><br><br>
                    <div id="citasAsignadas">
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-profile-tab">
                <br><br><br>
                    <div id="citasCreadas">
                    </div>
                </div> -->
            <!-- </div> -->
        </div>
    </div>
</div>

@stop