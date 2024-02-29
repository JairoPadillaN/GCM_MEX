@extends('principal')
@section('contenido')

{{Form::open(['route' => 'GuardarRegresoViajes','files'=>true, 'id' => 'formid'])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>
                Finalizar viaje
            </h1>
        </div><br>
        <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu')!!}">
        <div class="panel-body">
            <div class="form-group col-md-6">
                <div class="row">
                    <h3>Datos de salida</h3>
                    <div class="sub-title">Usuario:</div>
                    <div>
                        {{Form::text('usuarioViaje',($consulta->usuarioViaje),['class' => 'form-control', 'readonly' => 'true'])}}
                        {{Form::hidden('idViaje',$consulta->idViaje)}}
                        <!--Este cuadro no se vera en la vista ya que es tipo hidden "oculto" solo sera para obetener el ide a modificar y lo enviara al metodo editarUsuario-->
                        {{Form::hidden('idVehiculo',$consulta->idVehiculo)}}
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <div class="sub-title">Fecha de Salida: </div>
                            <div>
                                {{Form::text('fechaInicio',($consulta->fechaInicio),['class' => 'form-control', 'readonly' => 'true'])}}
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="sub-title">Fecha de Finzalización: </div>
                            <div>
                                @if($errors->first('fechaFin'))
                                <i> {{ $errors->first('fechaFin') }}</i>
                                @endif
                                {{Form::date('fechaFin', \Carbon\Carbon::now(),['class' => 'form-control'])}}
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="sub-title">Tipo de viaje:</div>
                            {{Form::text('tipoViaje',($consulta->tipoViaje),['class' => 'form-control', 'readonly' => 'true'])}}
                        </div>

                        <div class="form-group col-md-6">
                            <div class="sub-title">Empresa a visitar:</div>
                            <div>
                                <select name='idc' class="form-control" rows="5" class="form-control rounded-0"
                                    disabled>
                                    <option value='{{$idclientesel}}'>{{$nomcli}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="sub-title">Monto del viaje:</div>
                            <div>
                                {{Form::text('montoGasto',($consulta->montoGasto),['class' => 'form-control', 'readonly' => 'true'])}}
                            </div>
                        </div>
						<div class="col-md-6">
                            <div class="sub-title">Sucursal:</div>
                                <select name='idSucursal' id='idp' class="form-control" disabled>
                                    <option value='{{$idSucursal}}'>{{$nomsuc}}</option>
                                </select>
                        </div>
                    </div>

                    <div class="sub-title">Motivo:</div>
                    <div>
                        <textarea name="motivoViaje" rows="5" class="form-control rounded-0"
                            disabled>{!!($consulta->motivoViaje)!!}</textarea>
                    </div>
                </div>
            </div>

            <div class="form-group col-md-6">
                <h3>Estado del vehículo</h3>
                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="sub-title">Vehiculo:</div>
                        <div>
                            <select name="idVehiculo" class="form-control" rows="5" class="form-control rounded-0"
                                disabled>
                                @foreach($vehiculo as $v)
                                @if($v->activo=="Si")
                                <option value='{{$v->idVehiculo}}'>{{$v->nombreVehiculo}} {{$v->tipoVehiculo}}
                                    {{$v->placas}} {{$v->idMarca}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="sub-title">Kilometraje inicial: </div>
                        <div>
                            {{Form::number('kmActual',($consulta->kmActual),['class' => 'form-control', 'readonly' => 'true','id' => 'kmInicial'])}}

                            {{Form::hidden('kmActual',old ('kmActual'),['class' => 'form-control'])}}


                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="sub-title">Evidencia Km y gasolina salida:</div>
                        <div>
                            <center>
                                @foreach($consultaImg as $consImg)
                                <img src="{{asset ('/archivos/'.$consImg->fotoEvidenciaSalida)}}" height=60
                                    width=60>
                                <!---archivo---->
                                @endforeach
                            </center>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="sub-title">Evidencia Km y gasolina entrega:</div>
                        <div>
                            @if($errors->first('fotoEvidenciaRegreso'))
                            <i> {{ $errors->first('fotoEvidenciaRegreso') }}</i>
                            @endif
                            {{Form::file('fotoEvidenciaRegreso',['class' => 'form-control rounded-0'])}}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="sub-title">Kilometraje Final: </div>
                        <div>
                            @if($errors->first('kmFinal'))
                            <i> {{ $errors->first('kmFinal') }}</i>
                            @endif

                            {{Form::number('kmFinal',old ('kmFinal'),['class' => 'form-control','id' => 'idKmFin'])}}

                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="sub-title">Parabrisas:</div>
                        <div>
                            {{Form::text('parabrisas',($consulta->parabrisas),['class' => 'form-control', 'readonly' => 'true'])}}
                        </div>
                        <div class="sub-title">Luces:</div>
                        <div>
                            {{Form::text('lucesSalida',($consulta->lucesSalida),['class' => 'form-control', 'readonly' => 'true'])}}
                        </div>
                        <div class="sub-title">Anticongelante:</div>
                        <div>
                            {{Form::text('anticongelante',($consulta->anticongelante),['class' => 'form-control', 'readonly' => 'true'])}}
                        </div>
                        <div class="sub-title">Liquido de frenos:</div>
                        <div>
                            {{Form::text('liquidoFrenos',($consulta->liquidoFrenos),['class' => 'form-control', 'readonly' => 'true','style'=>'30px'])}}
                        </div>
                        <div class="sub-title">Aire de llantas:</div>
                        <div>
                            {{Form::text('llantasSalida',($consulta->llantasSalida),['class' => 'form-control', 'readonly' => 'true'])}}
                        </div>
                        <div class="sub-title">Unidad limpia salida:</div>
                        <div>
                            {{Form::text('unidadLimpia',($consulta->unidadLimpia),['class' => 'form-control', 'readonly' => 'true'])}}
                        </div>
                        <div class="sub-title">Observaciones de salida de la unidad:</div>
                        <div>
                            <textarea name="observacionSalida" rows="5" class="form-control rounded-0"
                                disabled>{!!($consulta->observacionSalida)!!}</textarea></div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="sub-title">Golpes:</div>
                        @if($errors->first('golpes'))
                        <i> {{ $errors->first('golpes') }}</i>
                        @endif
                        <div>
                            Si {{Form::radio('golpes','Si')}}
                            No {{Form::radio('golpes','No')}}
                        </div>
                        <div class="sub-title">Rayaduras:</div>
                        @if($errors->first('rayaduras'))
                        <i> {{ $errors->first('rayaduras') }}</i>
                        @endif
                        <div>
                            Si {{Form::radio('rayaduras','Si')}}
                            No {{Form::radio('rayaduras','No')}}
                        </div>
                        <div class="sub-title">Aire de llantas:</div>
                        @if($errors->first('llantasEntrega'))
                        <i> {{ $errors->first('llantasEntrega') }}</i>
                        @endif
                        <div>
                            Si {{Form::radio('llantasEntrega','Si')}}
                            No {{Form::radio('llantasEntrega','No')}}
                        </div>
                        <div class="sub-title">Luces:</div>
                        @if($errors->first('lucesEntrega'))
                        <i> {{ $errors->first('lucesEntrega') }}</i>
                        @endif
                        <div>
                            Si {{Form::radio('lucesEntrega','Si')}}
                            No {{Form::radio('lucesEntrega','No')}}
                        </div>
                        <div class="sub-title">Unidad limpia regeso:</div>
                        @if($errors->first('unidadLimpiaEntrega'))
                        <i> {{ $errors->first('unidadLimpiaEntrega') }}</i>
                        @endif
                        <div>
                            Si {{Form::radio('unidadLimpiaEntrega','Si')}}
                            No {{Form::radio('unidadLimpiaEntrega','No')}}
                        </div>
                        <div class="sub-title">Observaciones de entrega de la unidad:</div>
                        <div>
                            @if($errors->first('observacionEntrega'))
                            <i> {{ $errors->first('observacionEntrega') }}</i>
                            @endif
                            <textarea name="observacionEntrega" rows="5" class="form-control rounded-0"
                                placeholder="Escribe en que condiciones estas entregando la unidad.">{!! old('observacionEntrega') !!}</textarea>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <center>{{Form::submit('Guardar',['class' => 'btn  btn-default','id' => 'guardar'])}}
            <a href="{{asset('reporteViajes')}}"><button type="button" class="btn btn-default">Cancelar</button></a>
        </center>
        <br>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#formid').on('submit', function(e) {

        var va = validaDatos();
        console.log(va);
        if (va) {
            console.log('todo bien');
        } else {
            alert('Error: El Kilometraje Final debe ser mayor al Kilmetraje incial')
            e.preventDefault();
        }
    })

    function validaDatos() {

        var kmIni = $('#kmInicial').val();
        var x = parseInt(kmIni);
        console.log(kmIni);
        var a = $('#idKmFin').val();
        var y = parseInt(a);
        console.log(y);
        if (x < y) {
            return true;
        } else {
            return false;
        }
    }
});
</script>


@stop