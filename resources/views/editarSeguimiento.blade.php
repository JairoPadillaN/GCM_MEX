@extends('principal')
@section('contenido')
<script type="text/javascript">
$(document).ready(function() {
    $(".cargar").click();

    $("#idc").change(function() {
    $("#idp").load('{{url('comboca1')}}' + '?r=' + Date.now() + '&idc=' + this.options[this.selectedIndex].value);
    // alert("mensaje correcto")
    });

    $("#idArea").change(function() {
    $("#idActividad").load('{{url('comboActividades')}}' + '?r=' + Date.now() + '&idArea=' + this.options[this.selectedIndex].value);
    // alert("mensaje correcto")
    });

});
</script>
<!-- {{Form::open(['route' => 'editarSeg','id' => 'formularioSeg','files'=>true])}}
{{Form::token()}} -->
<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Proyectos</h1>
        </div>
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
                    <form action="{{ route('editarSeg') }}" method='POST' enctype='multipart/form-data' id="formularioSeg">
                    @csrf
                        <div class="form-group col-md-6">
                            <input type="hidden" name="idSegActividad" id="idSegActividad" class="form-control" readonly="true"
                                value="{{$consulta->idSegActividad}}">
                            
                            <div class="sub-title">Folio:</div>
                            <div>
                                <input class="form-control" name="folio" type="text" readonly="true" value="{{$consulta->folio}}">
                            </div>
                            <div class="sub-title">Fecha:</div>
                            <div>
                                @if($errors->first('fechaCreacion'))
                                <i> {{ $errors->first('fechaCreacion') }}</i>
                                @endif
                                {{Form::date('fechaCreacion', $consulta->fechaCreacion,['class' => 'form-control'])}}
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Creado por:</div>
                                    <div>
                                    <input class="form-control" name="idu" type="hidden" readonly="true" value="{{$consulta->idu}}">

                                        <input type="text" name="nombreUsuario" class="form-control" readonly="true" value="{{$consulta->nombreUsuario}}">

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Tipo de usuario: </div>
                                    <input type="text" name="" class="form-control" readonly="true" value="{{$tipo}}">
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Cliente:</div>
                                        @if($errors->first('idc'))
                                        <i> {{ $errors->first('idc') }}</i>
                                        @endif
                                        <div>
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
                                
                                <div class="col-sm-6">
                                    <div class="sub-title">Sucursal:</div>
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

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Área:</div>
                                        @if($errors->first('idArea'))
                                        <i> {{ $errors->first('idArea') }}</i>
                                        @endif
                                        <div>
                                            <select name='idArea' id='idArea' class="form-control">
                                                <option value='{{$idAreaSel}}'>{{$nomArea}}</option>
                                                @foreach($area as $area)
                                                @if($area->activo=="Si")
                                                <option value='{{$area->idArea}}'>{{$area->nombreArea}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="sub-title">Actividad:</div>
                                        @if($errors->first('idActividad'))
                                        <i> {{ $errors->first('idActividad') }}</i>
                                        @endif
                                        <div>
                                            <select name='idActividad' id="idActividad" class="form-control">
                                                <option value='{{$idActSel}}'>{{$nomAct}}</option>
                                            </select>
                                        </div>
                                </div>
                            </div>

                            <div class="sub-title">Asunto:</div>
                            <div>
                                @if($errors->first('asunto'))
                                <i> {{ $errors->first('asunto') }}</i>
                                @endif
                                {{Form::text('asunto',$consulta->asunto,['class' => 'form-control'])}}
                            </div>
                            <div class="sub-title">Detalle: </div>
                            <div>
                                {{Form::textarea('detalleSeguimiento', $consulta->detalleSeguimiento,['class' => 'form-control', 'placeholder' => 'Agregar información adicional'])}}
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Fecha Inicio:</div>
                                    <div>
                                        @if($errors->first('fechaInicio'))
                                        <i> {{ $errors->first('fechaInicio') }}</i>
                                        @endif
                                        {{Form::date('fechaInicio', $consulta->fechaInicio,['class' => 'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Fecha Término: </div>
                                    @if($errors->first('fechaTermino'))
                                    <i> {{ $errors->first('fechaTermino') }}</i>
                                    @endif
                                    {{Form::date('fechaTermino', $consulta->fechaTermino,['class' => 'form-control'])}}
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Hora Inicio:</div>
                                    <div>
                                        @if($errors->first('horaInicio'))
                                        <i> {{ $errors->first('horaInicio') }}</i>
                                        @endif
                                        {{Form::time('horaInicio', $consulta->horaInicio,['class' => 'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Hora Término: </div>
                                    @if($errors->first('horaTermino'))
                                    <i> {{ $errors->first('horaTermino') }}</i>
                                    @endif
                                    {{Form::time('horaTermino',$consulta->horaTermino,['class' => 'form-control'])}}
                                </div>

                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Estatus:</div>
                                    @if($errors->first('estadoSeguimiento'))
                                    <i> {{ $errors->first('estadoSeguimiento') }}</i>
                                    @endif
                                    <div>

                                        @if ($consulta->estadoSeguimiento == 'Concluido')
                                        Concluido{{Form::radio('estadoSeguimiento','Concluido',true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                                        En desarrollo {{Form::radio('estadoSeguimiento','En desarrollo',false)}}&nbsp;&nbsp;&nbsp;
                                        Cancelado {{Form::radio('estadoSeguimiento','Cancelado',false)}}&nbsp;&nbsp;&nbsp;
                                        
                                        @elseif($consulta->estadoSeguimiento == 'En desarrollo')
                                        Concluido {{Form::radio('estadoSeguimiento','Concluido',false)}}&nbsp;&nbsp;&nbsp;
                                        En desarrollo{{Form::radio('estadoSeguimiento','En desarrollo',true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                                        Cancelado {{Form::radio('estadoSeguimiento','Cancelado',false)}}&nbsp;&nbsp;&nbsp;
                                        
                                        @elseif($consulta->estadoSeguimiento == 'Cancelado')
                                        Concluido {{Form::radio('estadoSeguimiento','Concluido',false)}}&nbsp;&nbsp;&nbsp;
                                        En desarrollo {{Form::radio('estadoSeguimiento','En desarrollo',false)}}&nbsp;&nbsp;&nbsp;
                                        Cancelado{{Form::radio('estadoSeguimiento','Cancelado',true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                                        
                                        @else
                                        Concluido {{Form::radio('estadoSeguimiento','Concluido')}}&nbsp;&nbsp;&nbsp;
                                        En desarrollo {{Form::radio('estadoSeguimiento','En desarrollo')}}&nbsp;&nbsp;&nbsp;
                                        Cancelado {{Form::radio('estadoSeguimiento','Cancelado')}}&nbsp;&nbsp;&nbsp;
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="sub-title">Importancia:</div>
                                    @if($errors->first('importanciaSeguimiento'))
                                    <i> {{ $errors->first('importanciaSeguimiento') }}</i>
                                    @endif
                                    <div>
                                        @if ($consulta->importanciaSeguimiento == 'Alta')
                                        Alta
                                        {{Form::radio('importanciaSeguimiento','Alta',true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                                        Media {{Form::radio('importanciaSeguimiento','Media',false)}}&nbsp;&nbsp;&nbsp;
                                        Baja {{Form::radio('importanciaSeguimiento','Baja',false)}}&nbsp;&nbsp;&nbsp;
                                        @elseif($consulta->importanciaSeguimiento == 'Media')
                                        Alta {{Form::radio('importanciaSeguimiento','Alta',false)}}&nbsp;&nbsp;&nbsp;
                                        Media
                                        {{Form::radio('importanciaSeguimiento','Media',true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                                        Baja {{Form::radio('importanciaSeguimiento','Baja',false)}}&nbsp;&nbsp;&nbsp;
                                        @elseif($consulta->importanciaSeguimiento == 'Baja')
                                        Alta {{Form::radio('importanciaSeguimiento','Alta',false)}}&nbsp;&nbsp;&nbsp;
                                        Media {{Form::radio('importanciaSeguimiento','Media',false)}}&nbsp;&nbsp;&nbsp;
                                        Baja
                                        {{Form::radio('importanciaSeguimiento','Baja',true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                                        @else
                                        Alta {{Form::radio('importanciaSeguimiento','Alta')}}&nbsp;&nbsp;&nbsp;
                                        Media {{Form::radio('importanciaSeguimiento','Media')}}&nbsp;&nbsp;&nbsp;
                                        Baja {{Form::radio('importanciaSeguimiento','Baja')}}&nbsp;&nbsp;&nbsp;
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div>
                                        @foreach($archivo1 as $a1)
                                        @if($a1->archivo1=='Sin archivo' || $a1->archivo1=='' )
                                        <div class="sub-title">File 1:</div>
                                        <div>
                                            @if($errors->first('archivo1'))
                                            <i> {{ $errors->first('archivo1') }}</i>
                                            @endif
                                            {{Form::file('archivo1',['class' => 'form-control'])}}
                                        </div>
                                        @else
                                        <div class="sub-title">Actualizar file 1:</div>
                                        <div>
                                            @if($errors->first('archivo1'))
                                            <i> {{ $errors->first('archivo1') }}</i>
                                            @endif
                                            {{Form::file('archivo1',['class' => 'form-control'])}}
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <br>
                                    <center>
                                        <div style='text-align'>
                                            @foreach($archivo1 as $a1)
                                            @if($a1->archivo1=='Sin archivo' || $a1->archivo1=='')
                                            <img src="{{asset('img/archivono.png')}}" height="35" width="35">
                                            <h6>Sin archivo</h6>
                                            @else
                                            <a target="_blank" href="{{asset('archivos/'.$a1->archivo1)}}">
                                                <img src="{{asset('img/archivosi.png')}}" height=35 width=35>
                                                <h6>Descargar {{$consulta->archivo1}}</h6>
                                            </a>
                                            @endif
                                            @endforeach
                                        </div>
                                    </center>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">

                                    <div>
                                        @foreach($archivo2 as $a2)
                                        @if($a2->archivo2=='Sin archivo' || $a2->archivo2=='' )
                                        <div class="sub-title">File 2:</div>
                                        <div>
                                            @if($errors->first('archivo2'))
                                            <i> {{ $errors->first('archivo2') }}</i>
                                            @endif
                                            {{Form::file('archivo2',['class' => 'form-control'])}}
                                        </div>
                                        @else
                                        <div class="sub-title">Actualizar file 2:</div>
                                        <div>
                                            @if($errors->first('archivo2'))
                                            <i> {{ $errors->first('archivo2') }}</i>
                                            @endif
                                            {{Form::file('archivo2',['class' => 'form-control'])}}
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <br>
                                    <center>
                                        <div style='text-align'>
                                            @foreach($archivo2 as $a2)
                                            @if($a2->archivo2=='Sin archivo' || $a2->archivo2=='')
                                            <img src="{{asset('img/archivono.png')}}" height="35" width="35">
                                            <h6>Sin archivo</h6>
                                            @else
                                            <a target="_blank" href="{{asset('archivos/'.$a2->archivo2)}}">
                                                <img src="{{asset('img/archivosi.png')}}" height=35 width=35>
                                                <h6>Descargar {{$consulta->archivo2}}</h6>
                                            </a>
                                            @endif
                                            @endforeach
                                        </div>
                                    </center>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div>
                                        @foreach($archivo3 as $a3)
                                        @if($a3->archivo3=='Sin archivo' || $a3->archivo3=='' )
                                        <div class="sub-title">File 3:</div>
                                        <div>
                                            @if($errors->first('archivo3'))
                                            <i> {{ $errors->first('archivo3') }}</i>
                                            @endif
                                            {{Form::file('archivo3',['class' => 'form-control'])}}
                                        </div>
                                        @else
                                        <div class="sub-title">Actualizar file 3:</div>
                                        <div>
                                            @if($errors->first('archivo3'))
                                            <i> {{ $errors->first('archivo3') }}</i>
                                            @endif
                                            {{Form::file('archivo3',['class' => 'form-control'])}}
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <br>
                                    <center>
                                        <div style='text-align'>
                                            @foreach($archivo3 as $a3)
                                            @if($a3->archivo3=='Sin archivo' || $a3->archivo3=='')
                                            <img src="{{asset('img/archivono.png')}}" height="35" width="35">
                                            <h6>Sin archivo</h6>
                                            @else
                                            <a target="_blank" href="{{asset('archivos/'.$a3->archivo3)}}">
                                                <img src="{{asset('img/archivosi.png')}}" height=35 width=35>
                                                <h6>Descargar {{$consulta->archivo3}}</h6>
                                            </a>
                                            @endif
                                            @endforeach
                                        </div>
                                    </center>
                                </div>
                            </div>
                            <div class="sub-title">Link 1:</div>
                            <div>
                            {{Form::text('link1',$consulta->link1,['class' => 'form-control', 'placeholder' => 'Ejemplo: https://www.pruebas.germancontrolmotion.com'])}}
                                
                                
                                <!-- <button type="button" class="btn btn-success btn-default" >
                                    <span class="glyphicon glyphicon-plus-sign"></span>
                                </button> -->
                            </div>

                            <div class="sub-title">Link 2:</div>
                            <div>
                                {{Form::text('link2',$consulta->link2,['class' => 'form-control', 'placeholder' => 'Ejemplo: https://www.pruebas.germancontrolmotion.com'])}}
                            </div>

                            <div class="sub-title">Link 3:</div>
                            <div>
                                {{Form::text('link3',$consulta->link3,['class' => 'form-control', 'placeholder' => 'Ejemplo: https://www.pruebas.germancontrolmotion.com'])}}
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                        <center> {{Form::submit('Guardar',['class' => 'btn  btn-success','id' => "guardar"])}}
                            <a href="{{asset('reporteSeguimientoVista')}}"><button type="button" class="btn btn-default">Cancelar</button></a>
                        </center><br>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="form-group col-md-6">
                        <div class="sub-title">Equipo:</div>
                            <div>
                                <select name='idEquipos' id='idEquipos' class="form-control">
                                    <option value=''>Selecciona un equipo</option>
                                    @foreach($equipos as $equipos)
                                    <option value="{{$equipos->idEquipos}}">{{$equipos->GCMid}}-{{$equipos->complementoGCMid}}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="form-group col-md-6">
                        <br><br>
                            <button type="button" class="btn btn-success" id="agregarEquipo">
                                <span class="glyphicon glyphicon-plus-sign"></span> Agregar equipo
                            </button>
                    </div>
                    
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
                    <div class="form-group col-md-6">
                        <div class="sub-title">Servicio:</div>
                            <div>
                                <select name='idFactura' id='idFactura' class="form-control">
                                    <option value=''>Selecciona un servicio</option>
                                    @foreach($facturas as $facturas)
                                    <option value="{{$facturas->idFactura}}">{{$facturas->idServicios}}-{{$facturas->numeroFactura}}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="form-group col-md-6">
                        <br><br>
                            <button type="button" class="btn btn-success" id="agregarServicio">
                                <span class="glyphicon glyphicon-plus-sign"></span> Agregar servicio
                            </button>
                    </div>
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
                    <div class="form-group col-md-6">
                        <div class="sub-title">Contacto:</div>
                            <div>
                                <select name='idContactoSuc' id='idContactoSuc' class="form-control">
                                    <option value=''>Selecciona un contacto</option>
                                    @foreach($contactoSucursal as $contacto)
                                        <option value="{{$contacto->idContactoSuc}}">{{$contacto->nombreCont}}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="form-group col-md-6">
                        <br><br>
                            <button type="button" class="btn btn-success" id="agregarContacto">
                                <span class="glyphicon glyphicon-plus-sign"></span> Agregar contacto
                            </button>
                    </div>
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
    $("#agrega").click(function() {
        // alert("java");
        $("#usuariosSeg").load('{{url('seguimientoUsuarios')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
    });

    $('.borrar').click(
        function() {
            // alert("borrar");
            $("#usuariosSeg").load('{{url('borrarSeg')}}' + '?' + $(this).closest('form').serialize());
        }
    );
    $('#agregarEquipo').click(function(){
        var idEquipos=$("#idEquipos").val();
        var idSegActividad=$("#idSegActividad").val();
        // alert(idEquipos);
        if(idEquipos==''){
            alert("Selecciona un equipo");
        }else{
            $('#reporteEquipos').load('{{url('agregarEquiposActividad')}}' + '?r=' +  Date.now() + '&idEquipos=' + idEquipos + '&idSegActividad=' + idSegActividad);
        }
        
    });
    $('#agregarServicio').click(function(){
        var idFactura=$("#idFactura").val();
        var idSegActividad=$("#idSegActividad").val();
        // alert(idSegActividad);
        if(idFactura==''){
            alert("Selecciona un servicio");
        }else{
            $('#reporteServicio').load('{{url('agregarEquiposActividad')}}' + '?r=' +  Date.now() + '&idFactura=' + idFactura + '&idSegActividad=' + idSegActividad);
        }
        
    });
    $('#agregarContacto').click(function(){
        var idContactoSuc=$("#idContactoSuc").val();
        var idSegActividad=$("#idSegActividad").val();
        // alert(idSegActividad);
        if(idContactoSuc==''){
            alert("Selecciona un contacto");
        }else{
            $('#reporteContacto').load('{{url('agregarEquiposActividad')}}' + '?r=' +  Date.now() + '&idContactoSuc=' + idContactoSuc + '&idSegActividad=' + idSegActividad);
        }
        
    });

    // $(".editar").click(function() {
    //     $("#crearParte").load('{{url('editarParteAbajo')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
    // });

    // $(".borrar").click(function() {
    //     $("#reportitoDePartesCreadas").load('{{url('borrarParteCreada')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
    // });

    // $(".servicios").click(function() {
    //     $("#crearParte").load('{{url('serviciosParteCreada')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
    // });

});
</script>


<style>
input.error {
    border-color: #f00 !important;
}

small.required {
    color: #f00;
}

textarea.error {
    border-color: #f00 !important;
}

.error {
    color: #f00 !important;
}
</style>

@stop