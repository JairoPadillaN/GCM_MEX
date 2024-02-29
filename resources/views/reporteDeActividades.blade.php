@extends('principal')
@section('contenido')

<script>
$(document).ready(function() {
    $(".cargar").click();
    $("#prospectos").load('{{url('reporteProspectosAct')}}' + '?' + $(this).closest('form').serialize());
    $("#publicidad").load('{{url('reportePublicidadAct')}}' + '?' + $(this).closest('form').serialize());
    $("#citas").load('{{url('reporteCitasAsignadas')}}' + '?' + $(this).closest('form').serialize());
    $("#citasCreadas").load('{{url('reporteCitasCreadas')}}' + '?' + $(this).closest('form').serialize());
});
</script>

<form>
    <div class="col-md-12">
        <!-- Advanced Tables -->
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 align='center'>Reporte de actividades</h1>
            </div><br>
            <div class="panel-body">

                <div><br>
                    <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">

                        <li class="nav-item">
                            <a class="cargar" class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                                role="tab" aria-controls="pills-home" aria-selected="false">Investigación de
                                prospectos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile"
                                role="tab" aria-controls="pills-profile" aria-selected="false">Publicidad</a>
                        </li>

                        
                        <li class="nav-item">
                            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#citaCreada" role="tab"
                        aria-controls="pills-contact" aria-selected="false">Citas creadas</a>
                        
                        <li class="nav-item">
                            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#citasAsignadas"
                                role="tab" aria-controls="pills-contact" aria-selected="false">Citas asignadas</a>
                        </li>
                        
                </li>
                    </ul>
                </div><br>

                <div class="tab-content" id="pills-tabContent">

                    <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div>
                        <br><br><br>
                                <div id="prospectos">
                                </div>

                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-contact-tab">
                        <div>
                        <br><br><br>
                            <div id="publicidad">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="citasAsignadas" role="tabpanel" aria-labelledby="pills-contact-tab">
                        <div>
                        <br><br><br>
                            <div id="citas">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="citaCreada" role="tabpanel" aria-labelledby="pills-contact-tab">
                        <div>
                        <br><br><br>
                            <div id="citasCreadas">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@stop