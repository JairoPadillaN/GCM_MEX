@extends('principal')
@section('contenido')
<script type="text/javascript">
$(document).ready(function() {

    // $("#guardar").click(function() {

    //     // console.log("faaaaaaaaaaaaaaf");
    //     $("#formularioSeguimiento").validate({
    //         onclick: false,
    //         onkeyup: false,
    //         ignore: [],
    //         rules: {
    //             idc: {required: true},
    //             idp: {required: true},
    //             idArea: {required: true},
    //             idActividad: {required: true}
    //         },
    //         messages: {
    //             idc: {required: 'Seleccione un cliente'},
    //             idp: {required: 'Seleccione una sucursal'},
    //             idArea: {required: 'Seleccione un área'},
    //             idActividad: {required: 'Seleccione una actividad'}
    //         },
    //         errorPlacement: function(error, element) {
    //             alert(error.text());
    //         }
    //     });

    // });

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
{{Form::open(['route' => 'guardarSeg','id' => 'formularioSeguimiento','files'=>true])}}
{{Form::token()}}
<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Proyectos</h1>
        </div>
        <div class="panel-body">
            <div class="form-group col-md-6">
                <div class="sub-title">Folio:</div>
                <div>
                    <input class="form-control" name="folio" type="text" value="{{$idSig}}" readonly="true">
                </div>

                <div class="sub-title">Fecha:</div>
                <div>
                    @if($errors->first('fechaCreacion'))
                    <i> {{ $errors->first('fechaCreacion') }}</i>
                    @endif
                    {{Form::date('fechaCreacion', \Carbon\Carbon::now(),['class' => 'form-control'])}}
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="sub-title">Creado por:</div>
                        <div>
                            <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu')!!}">
                            <input type="text" name="nombreUsuario" class="form-control" readonly="true" value="{!! Session::get('sesionname')!!} {!! Session::get('sesionpaterno')!!} {!!
                                    Session::get('sesionmaterno')!!}">

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="sub-title">Tipo de usuario: </div>
                        <input type="text" name="usuarioEquipos" class="form-control" readonly="true"
                            value="{!! Session::get('sesiontipo')!!}">
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
                                            <option value="">Seleccionar empresa</option>
                                            @foreach($cliente as $cliente)
                                            @if($cliente->activo=="si")
                                            <option value='{{$cliente->idc}}'>{{$cliente->razonSocial}}</option>
                                            @endif
                                            @endforeach
                                </select>
                                <div id = "alertaCliente" class="alert alert-warning">Debes seleccionar un cliente</div>
                            </div>
                    </div>
                    
                    <div class="col-sm-6">
                        <div class="sub-title">Sucursal:</div>
                            @if($errors->first('idSucursal'))
                            <i> {{ $errors->first('idSucursal') }}</i>
                            @endif
                            <div>
                                <select name='idSucursal' id="idp" class="form-control">
                                    <option value="">Seleccionar sucursal</option>

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
                                <select name='idArea' id="idArea" class="form-control">
                                    <option value="">Seleccione el área</option>
                                    @foreach($area as $area)
                                    @if($area->activo=="Si")
                                    <option value='{{$area->idArea}}'>{{$area->nombreArea}}
                                    </option>
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
                                    <option value="">Seleccionar actividad</option>

                                </select>
                            </div>
                    </div>

                </div>

                <div class="sub-title">Asunto:</div>
                <div>
                    @if($errors->first('asunto'))
                    <i> {{ $errors->first('asunto') }}</i>
                    @endif
                    {{Form::text('asunto',old ('asunto'),['class' => 'form-control'])}}
                </div>
                <div class="sub-title">Detalle: </div>
                <div>
                    {{Form::textarea('detalleSeguimiento', old('detalleSeguimiento'),['class' => 'form-control', 'placeholder' => 'Agregar información adicional'])}}
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
                            {{Form::date('fechaInicio', \Carbon\Carbon::now(),['class' => 'form-control'])}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="sub-title">Fecha Término: </div>
                        @if($errors->first('fechaTermino'))
                        <i> {{ $errors->first('fechaTermino') }}</i>
                        @endif
                        {{Form::date('fechaTermino', \Carbon\Carbon::now(),['class' => 'form-control'])}}
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="sub-title">Hora Inicio:</div>
                        <div>
                            @if($errors->first('horaInicio'))
                            <i> {{ $errors->first('horaInicio') }}</i>
                            @endif
                            {{Form::time('horaInicio', \Carbon\Carbon::now(),['class' => 'form-control'])}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="sub-title">Hora Término: </div>
                        @if($errors->first('horaTermino'))
                        <i> {{ $errors->first('horaTermino') }}</i>
                        @endif
                        {{Form::time('horaTermino', \Carbon\Carbon::now(),['class' => 'form-control'])}}
                    </div>

                </div>
                
                <div class="row">
                    <div class="col-sm-6">
                        <div class="sub-title">Estatus:</div>
                                @if($errors->first('estadoSeguimiento'))
                                <i> {{ $errors->first('estadoSeguimiento') }}</i>
                                @endif
                                <div>
                                    Concluido {{Form::radio('estadoSeguimiento','Concluido')}}&nbsp;&nbsp;
                                    En desarrollo {{Form::radio('estadoSeguimiento','En desarrollo',true)}}&nbsp;&nbsp;
                                    Cancelado {{Form::radio('estadoSeguimiento','Cancelado')}}&nbsp;&nbsp;
                        </div>
                    </div>
                    
                    <div class="col-sm-6">
                        <div class="sub-title">Importancia:</div>
                                @if($errors->first('importanciaSeguimiento'))
                                <i> {{ $errors->first('importanciaSeguimiento') }}</i>
                                @endif
                                <div>
                                    Alta {{Form::radio('importanciaSeguimiento','Alta',true)}}&nbsp;&nbsp;&nbsp;
                                    Media {{Form::radio('importanciaSeguimiento','Media')}}&nbsp;&nbsp;&nbsp;
                                    Baja {{Form::radio('importanciaSeguimiento','Baja')}}&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="sub-title">File 1:</div>
                        <div>
                            {{Form::file('archivo1')}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="sub-title">Link 1:</div>
                        <div>
                            {{Form::text('link1',old ('link1'),['class' => 'form-control', 'placeholder' => 'Ejemplo: https://www.pruebas.germancontrolmotion.com'])}}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="sub-title">File 2:</div>
                        <div>
                            {{Form::file('archivo2')}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="sub-title">Link 2:</div>
                        <div>
                            {{Form::text('link2',old ('link2'),['class' => 'form-control', 'placeholder' => 'Ejemplo: https://www.pruebas.germancontrolmotion.com'])}}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="sub-title">File 3:</div>
                        <div>
                            {{Form::file('archivo3')}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="sub-title">Link 3:</div>
                        <div>
                            {{Form::text('link3',old ('link3'),['class' => 'form-control', 'placeholder' => 'Ejemplo: https://www.pruebas.germancontrolmotion.com'])}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-6">
               
            </div>

        </div>
        <center> {{Form::submit('Guardar',['class' => 'btn  btn-success','id' => "guardar"])}}
        </center><br>
    </div>
</div>

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
.alert-warning {
    display: none;
}
.alert-info {
    display: none;
}

input.error {
    border-color: #f00 !important;
}

small.required {
    color:#f00;
}

textarea.error {
    border-color: #f00 !important;
}

.error {
    color: #f00 !important;
}
</style>

@stop