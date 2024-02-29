@extends('principal')
@section('contenido')

{{Form::open(['route' => 'GuardarSalidaViajes','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>
                Nueva salida
            </h1>
        </div><br>
        <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu')!!}">
        <div class="panel-body">
            <div class="form-group col-md-6">
                <div class="row">
                    <h3>Datos de salida</h3>
                    <div class="sub-title">Usuario:</div>
                    <div>
                        <input type="text" name="usuarioViaje" class="form-control" readonly="true" value="{!! Session::get('sesionname')!!} {!! Session::get('sesionpaterno')!!} {!!
                            Session::get('sesionmaterno')!!}">
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <div class="sub-title"> Fecha de Salida: </div>
                            <div>
                                @if($errors->first('fechaInicio'))
                                <i> {{ $errors->first('fechaInicio') }}</i>
                                @endif
                                {{Form::date('fechaInicio', \Carbon\Carbon::now(),['class' => 'form-control'])}}
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="sub-title">Tipo de viaje:</div>
                            @if($errors->first('tipoViaje'))
                            <i> {{ $errors->first('tipoViaje') }}</i>
                            @endif<div>
                                GCM {{Form::radio('tipoViaje','GCM')}}&nbsp;&nbsp;&nbsp;
                                Personal {{Form::radio('tipoViaje','Personal')}}&nbsp;&nbsp;&nbsp;
                                CYM {{Form::radio('tipoViaje','CYM')}}&nbsp;&nbsp;&nbsp;
                                Proveedores {{Form::radio('tipoViaje','Proveedores')}}&nbsp;&nbsp;&nbsp;
                            </div>
                        </div>
                    </div>

                    <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="sub-title">Empresa a visitar:</div>
                            @if($errors->first('idc'))
                            <i> {{ $errors->first('idc') }}</i>
                            @endif<div>
                                <select name='idc' id='idc' class="form-control">
                                <option value="">Seleccione una empresa:</option>
                                @foreach($cliente as $clienteu)
                                        @if($clienteu->idc== 302 || $clienteu->idc== 303)
                                            <option value='{{$clienteu->idc}}'>{{$clienteu->razonSocial}}</option>
                                        @endif
                                    @endforeach
                                    @foreach($cliente as $cliente)
                                        @if($cliente->activo=="si")
                                            <option value='{{$cliente->idc}}'>{{$cliente->razonSocial}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="sub-title">Sucursal:</div>
                            @if($errors->first('idSucursal'))
                            <i> {{ $errors->first('idSucursal') }}</i>
                            @endif 
                            <div id='combop'>
                                <select name='idSucursal' id='idp' class="form-control">
                                    <option value="">Seleccione una sucursal:</option>                                
                                </select>   
                            </div>
                        </div>
                    </div>
                    </form>
                    <br>
                    <div id='divinfo' class="alert alert-info">
                            
                        </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <div class="sub-title">Monto del viaje:</div>
                            <div>
                                @if($errors->first('montoGasto'))
                                <i> {{ $errors->first('montoGasto') }}</i>
                                @endif
                                {{Form::text('montoGasto',old ('montoGasto'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 5000'])}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="sub-title">Motivo:</div>
                    <div>
                        @if($errors->first('motivoViaje'))
                        <i> {{ $errors->first('motivoViaje') }}</i>
                        @endif
                        <textarea name="motivoViaje" rows="5"
                            class="form-control rounded-0" placeholder="Describe el motivo de tu salida.">{!! old('motivoViaje') !!}</textarea></div>
                </div>
            </div>
            <div class="form-group col-md-6">
                <h3>Estado del vehículo</h3>
                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="sub-title">Vehículo:</div>
                        @if($errors->first('idVehiculo'))
                        <i> {{ $errors->first('idVehiculo') }}</i>
                        @endif<div>
                            <select name='idVehiculo' class="form-control" id="opcVehiculo">
                                <option value="">Seleccionar vehículo</option>
                                @foreach($vehiculo as $vehiculo)
                                    @if($vehiculo->activo=="Si")
                                        <option value='{{$vehiculo->idVehiculo}}' name="{{$vehiculo->kmActual}}">
                                            {{$vehiculo->nombreVehiculo}} {{$vehiculo->placas}} {{$vehiculo->idMarca}}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="sub-title">Kilometraje inicial: </div>
                        <div>
                            {{Form::text('kmActual',old ('kmActual'),['class' => 'form-control prueba', 'id' => 'recibeKm', 'readonly'=> 'true'])}}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="sub-title">Evidencia Km y Gasolina:</div>
                        <div>
                            @if($errors->first('fotoEvidenciaSalida'))
                            <i> {{ $errors->first('fotoEvidenciaSalida') }}</i>
                            @endif
                            {{Form::file('fotoEvidenciaSalida',['class' => 'form-control rounded-0'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="sub-title">Liquido de frenos:</div>
                        @if($errors->first('liquidoFrenos'))
                        <i> {{ $errors->first('liquidoFrenos') }}</i>
                        @endif
                        <div>
                            Si {{Form::radio('liquidoFrenos','Si')}}
                            No {{Form::radio('liquidoFrenos','No')}}
                        </div>
                        <div class="sub-title">Parabrisas:</div>
                        @if($errors->first('parabrisas'))
                        <i> {{ $errors->first('parabrisas') }}</i>
                        @endif
                        <div>
                            Si {{Form::radio('parabrisas','Si')}}
                            No {{Form::radio('parabrisas','No')}}
                        </div>
                        <div class="sub-title">Aire de llantas:</div>
                        @if($errors->first('llantasSalida'))
                        <i> {{ $errors->first('llantasSalida') }}</i>
                        @endif
                        <div>
                            Si {{Form::radio('llantasSalida','Si')}}
                            No {{Form::radio('llantasSalida','No')}}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="sub-title">Luces:</div>
                        @if($errors->first('lucesSalida'))
                        <i> {{ $errors->first('lucesSalida') }}</i>
                        @endif
                        <div>
                            Si {{Form::radio('lucesSalida','Si')}}
                            No {{Form::radio('lucesSalida','No')}}
                        </div>
                        <div class="sub-title">Anticongelante:</div>
                        @if($errors->first('anticongelante'))
                        <i> {{ $errors->first('anticongelante') }}</i>
                        @endif
                        <div>
                            Si {{Form::radio('anticongelante','Si')}}
                            No {{Form::radio('anticongelante','No')}}
                        </div>
                        <div class="sub-title">Unidad limpia:</div>
                        @if($errors->first('unidadLimpia'))
                        <i> {{ $errors->first('unidadLimpia') }}</i>
                        @endif
                        <div>
                            Si {{Form::radio('unidadLimpia','Si')}}
                            No {{Form::radio('unidadLimpia','No')}}
                        </div>
                    </div>
                </div>
                <div class="sub-title">Observaciones de la unidad:</div>
                <div>
                    @if($errors->first('observacionSalida'))
                    <i> {{ $errors->first('observacionSalida') }}</i>
                    @endif
                    <textarea name="observacionSalida" rows="5"
                        class="form-control rounded-0" placeholder="Escribe si la unidad cuenta con algun defecto pequeño o algúna observación que consideres necesaria.">{!! old('observacionSalida') !!}</textarea></div>
            </div>
        </div>
        <center>{{Form::submit('Guardar',['class' => 'btn  btn-default'])}}</center>
        <br>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $("#divinfo").hide(1500);
    $('#opcVehiculo').change(function() {
        var kilometraje = $("#opcVehiculo :selected").attr("name");
        console.log(kilometraje);
        $("#recibeKm").val(kilometraje);
    });
})

$("#idc").change(function() {
         $("#idp").load('{{url('comboca')}}' + '?r=' + Date.now() + '&idc='  + this.options[this.selectedIndex].value) ;
       });

$("#idp").change(function() {
        $("#divinfo").load('{{url('info')}}' + '?' + $(this).closest('form').serialize());
         $("#divinfo").show("slow");
       });

</script>

@stop