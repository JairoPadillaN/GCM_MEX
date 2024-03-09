@extends('principal')
@section('contenido')

{{Form::open(['route' => 'editarSalida_Viajes','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>
                Editar salida
            </h1>
        </div><br>

        <div class="panel-body">
            <div class="form-group col-md-6">
                <div class="row">
                    <h3>Datos de salida</h3>
                    <div class="sub-title">Usuario:</div>
                    <div>
                        {{Form::text('usuarioViaje',($consulta->usuarioViaje),['class' => 'form-control', 'readonly' => 'true'])}}
                        {{Form::hidden('idViaje',$consulta->idViaje)}}
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <div class="sub-title"> Fecha de Salida: </div>
                            <div>
                                @if($errors->first('fechaInicio'))
                                <i> {{ $errors->first('fechaInicio') }}</i>
                                @endif
                                {{Form::date('fechaInicio',$consulta->fechaInicio,['class' => 'form-control'])}}
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                        <div class="sub-title">Tipo de viaje:</div>
                            @if($errors->first('tipoViaje'))
                            <i> {{ $errors->first('tipoViaje') }}</i>
                            @endif
                            @if ($consulta->tipoViaje == 'GCM')
                            GCM {{Form::radio('tipoViaje','GCM', true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                            Personal {{Form::radio('tipoViaje','Personal',false, [])}}&nbsp;&nbsp;&nbsp;
                            CYM {{Form::radio('tipoViaje','CYM',false, [])}}&nbsp;&nbsp;&nbsp;
                            Proveedores {{Form::radio('tipoViaje','Proveedores',false, [])}}&nbsp;&nbsp;&nbsp;
                            @endif
                            @if ($consulta->tipoViaje == 'Personal')
                            GCM {{Form::radio('tipoViaje','GCM', false,[])}}&nbsp;&nbsp;&nbsp;
                            Personal {{Form::radio('tipoViaje','Personal',true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                            CYM {{Form::radio('tipoViaje','CYM',false, [])}}&nbsp;&nbsp;&nbsp;
                            Proveedores {{Form::radio('tipoViaje','Proveedores',false, [])}}&nbsp;&nbsp;&nbsp;
                            @endif
                            @if ($consulta->tipoViaje == 'CYM')
                            GCM {{Form::radio('tipoViaje','GCM', false,[])}}&nbsp;&nbsp;&nbsp;
                            Personal {{Form::radio('tipoViaje','Personal',false,[])}}&nbsp;&nbsp;&nbsp;
                            CYM {{Form::radio('tipoViaje','CYM',true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                            Proveedores {{Form::radio('tipoViaje','Proveedores',false, [])}}&nbsp;&nbsp;&nbsp;
                            @endif
                            @if ($consulta->tipoViaje == 'Proveedores')
                            GCM {{Form::radio('tipoViaje','GCM', false,[])}}&nbsp;&nbsp;&nbsp;
                            Personal {{Form::radio('tipoViaje','Personal',false,[])}}&nbsp;&nbsp;&nbsp;
                            CYM {{Form::radio('tipoViaje','CYM',false,[])}}&nbsp;&nbsp;&nbsp;
                            Proveedores {{Form::radio('tipoViaje','Proveedores',true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                            @endif
                        </div>
                    </div>
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                            <div class="sub-title">Empresa a visitar:</div>
                                @if($errors->first('idc'))
                                <i> {{ $errors->first('idc') }}</i>
                                @endif<div>
                                    <select name='idc' id='idc' class="form-control" rows="5"
                                        class="form-control rounded-0">
                                        <option value='{{$idclientesel}}'>{{$nomcli}}</option>
                                        @foreach($cliente as $clienteu)
                                        @if($clienteu->idc== 302 || $clienteu->idc== 303)
                                        <option value='{{$clienteu->idc}}'>{{$clienteu->razonSocial}}</option>
                                        @endif
                                        @endforeach
                                        @foreach($clientec as $cli)
                                        @if($cli->activo=="si")
                                        <option value='{{$cli->idc}}'>{{$cli->razonSocial}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="sub-title">Sucursal:</div>
                                @if($errors->first('sucursal'))
                                <i> {{ $errors->first('sucursal') }}</i>
                                @endif
                                <div id='combop'>
                                    <select name='idSucursal' id='idp' class="form-control">
                                        <option value='{{$idSucursal}}'>{{$nomsuc}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        </form>
                        
                        <br>
                        <div id='divinfo' class="alert alert-info">
                        Cliente: {{$nomcli}} ------- Sucursal: {{$nomsuc}}
                        </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <div class="sub-title">Monto del viaje:</div>
                            <div>
                                @if($errors->first('montoGasto'))
                                <i> {{ $errors->first('montoGasto') }}</i>
                                @endif
                                {{Form::text('montoGasto',($consulta->montoGasto),['class' => 'form-control', 'placeholder' =>'Ejemplo:5000'])}}
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
                            class="form-control rounded-0" placeholder="Describe el motivo de tu salida.">{!!($consulta->motivoViaje)!!}</textarea></div>
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
                                <option value='{{$idVehiculosel}}'>{{$nomVehiculo}} {{$placas}} {{$idMarca}}
                                </option>
                                @foreach($vehiculo as $vehiculo)
                                @if($vehiculo->activo=="Si")
                                <option value='{{$vehiculo->idVehiculo}}'>{{$vehiculo->nombreVehiculo}} {{$vehiculo->tipoVehiculo}}
                                    {{$vehiculo->placas}} {{$vehiculo->idMarca}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="sub-title">Kilometraje inicial: </div>
                        <div>
                            {{Form::text('kmActual',($consulta->kmActual),['class' => 'form-control', 'readonly' => 'true', 'id' => 'recibeKm'])}}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="sub-title">Evidencia Km y Gasolina:</div>
                        <div>
                            @if($errors->first('fotoEvidenciaSalida'))
                            <i> {{ $errors->first('fotoEvidenciaSalida') }}</i>
                            @endif
                            @foreach($consultaImg as $consImg)
                            <img src="{{asset ('public/archivos/'.$consImg->fotoEvidenciaSalida)}}" height=60 width=60>
                            <!---archivo---->
                            @endforeach
                            {{Form::file('fotoEvidenciaSalida',['class' => 'form-control'])}}
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
                            @if ($consulta->liquidoFrenos == 'Si')
                            Si {{Form::radio('liquidoFrenos','Si', true, ['checked' => 'checked']) }}
                            No {{Form::radio('liquidoFrenos','No',false, [])}}
                            @else
                            Si {{Form::radio('liquidoFrenos','Si', true, []) }}
                            No {{Form::radio('liquidoFrenos','No',false, ['checked' => 'checked'])}}
                            @endif
                        </div>
                        <div class="sub-title">Parabrisas:</div>
                        @if($errors->first('parabrisas'))
                        <i> {{ $errors->first('parabrisas') }}</i>
                        @endif
                        <div>
                            @if ($consulta->parabrisas == 'Si')
                            Si {{Form::radio('parabrisas','Si', true, ['checked' => 'checked']) }}
                            No {{Form::radio('parabrisas','No',false, [])}}
                            @else
                            Si {{Form::radio('parabrisas','Si', true, []) }}
                            No {{Form::radio('parabrisas','No',false, ['checked' => 'checked'])}}
                            @endif
                        </div>
                        <div class="sub-title">Aire de llantas:</div>
                        @if($errors->first('llantasSalida'))
                        <i> {{ $errors->first('llantasSalida') }}</i>
                        @endif
                        <div>
                            @if ($consulta->llantasSalida == 'Si')
                            Si {{Form::radio('llantasSalida','Si', true, ['checked' => 'checked']) }}
                            No {{Form::radio('llantasSalida','No',false, [])}}
                            @else
                            Si {{Form::radio('llantasSalida','Si', true, []) }}
                            No {{Form::radio('llantasSalida','No',false, ['checked' => 'checked'])}}
                            @endif
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="sub-title">Luces:</div>
                        @if($errors->first('lucesSalida'))
                        <i> {{ $errors->first('lucesSalida') }}</i>
                        @endif
                        <div>
                            @if ($consulta->lucesSalida == 'Si')
                            Si {{Form::radio('lucesSalida','Si', true, ['checked' => 'checked']) }}
                            No {{Form::radio('lucesSalida','No',false, [])}}
                            @else
                            Si {{Form::radio('lucesSalida','Si', true, []) }}
                            No {{Form::radio('lucesSalida','No',false, ['checked' => 'checked'])}}
                            @endif
                        </div>
                        <div class="sub-title">Anticongelante:</div>
                        @if($errors->first('anticongelante'))
                        <i> {{ $errors->first('anticongelante') }}</i>
                        @endif
                        <div>
                            @if ($consulta->anticongelante == 'Si')
                            Si {{Form::radio('anticongelante','Si', true, ['checked' => 'checked']) }}
                            No {{Form::radio('anticongelante','No',false, [])}}
                            @else
                            Si {{Form::radio('anticongelante','Si', true, []) }}
                            No {{Form::radio('anticongelante','No',false, ['checked' => 'checked'])}}
                            @endif
                        </div>
                        <div class="sub-title">Unidad limpia salida:</div>
                        @if($errors->first('unidadLimpia'))
                        <i> {{ $errors->first('unidadLimpia') }}</i>
                        @endif
                        <div>
                            @if ($consulta->unidadLimpia == 'Si')
                            Si {{Form::radio('unidadLimpia','Si', true, ['checked' => 'checked']) }}
                            No {{Form::radio('unidadLimpia','No',false, [])}}
                            @else
                            Si {{Form::radio('unidadLimpia','Si', true, []) }}
                            No {{Form::radio('unidadLimpia','No',false, ['checked' => 'checked'])}}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="sub-title">Observaciones de la unidad:</div>
                <div>
                    @if($errors->first('observacionSalida'))
                    <i> {{ $errors->first('observacionSalida') }}</i>
                    @endif
                    <textarea name="observacionSalida" rows="5"
                        class="form-control rounded-0" placeholder="Escribe si la unidad cuenta con algun defecto pequeño o algúna observacion que consideres necesaria.">{!!($consulta->observacionSalida)!!}</textarea></div>
            </div>
        </div>
        <center>{{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
            <a href="{{asset('reporte_Viajes')}}"><button type="button" class="btn btn-default" >Cancelar</button></a>
        </center>

        <br>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#opcVehiculo').change(function() {
        var kilometraje = $("#opcVehiculo :selected").attr("name");
        console.log(kilometraje);
        $("#recibeKm").val(kilometraje);
    });
    $("#idc").change(function() {
        $("#idp").load('{{url('comboca')}}' + '?r=' + Date.now() + '&idc=' + this.options[this.selectedIndex].value);
    });

    $("#idp").change(function() {
        $("#divinfo").load('{{url('info')}}' + '?' + $(this).closest('form').serialize());
         $("#divinfo").show("slow");
       });
})
</script>

@stop