@extends('principal')
@section('contenido')

{{Form::token()}}

<style type="text/css">
.zoom {
    /* Aumentamos la anchura y altura durante 2 segundos */
    transition: width 3s, height 3s, transform 3s;
    -moz-transition: width 3s, height 3s, -moz-transform 3s;
    -webkit-transition: width 3s, height 3s, -webkit-transform 3s;
    -o-transition: width 3s, height 3s, -o-transform 3s;
}

.zoom:hover {
    /* tranformamos el elemento al pasar el mouse por encima al doble de
           su tamaño con scale(2). */
    transform: scale(4);
    -moz-transform: scale(4);
    /* Firefox */
    -webkit-transform: scale(4);
    /* Chrome - Safari */
    -o-transform: scale(4);
    /* Opera */
}

img.zoom {
    width: 60px;
    height: 60px;
    -webkit-transition: all .2s ease-in-out;
    -moz-transition: all .2s ease-in-out;
    -o-transition: all .2s ease-in-out;
    -ms-transition: all .2s ease-in-out;
}
</style>

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>
                Detalles de viaje
            </h1>
        </div><br>
        <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu')!!}">
        <div class="panel-body">
            <div class="form-group col-md-6">
                <div class="row">
                    <h3>Datos de salida</h3>

                    <div class="sub-title">Usuariox:</div>
                    <div>
                        {{Form::text('usuarioViaje',($consulta->usuarioViaje),['class' => 'form-control', 'readonly' => 'true'])}}
                        {{Form::hidden('idViaje',$consulta->idViaje)}}
                        <!--Este cuadro no se vera en la vista ya que es tipo hidden "oculto" solo sera para obetener el ide a modificar y lo enviara al metodo editarUsuario-->
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
                                {{Form::text('fechaFin',($consulta->fechaFin),['class' => 'form-control', 'readonly' => 'true'])}}
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
                                    @foreach($cliente as $cliente)
                                    <option value='{{$cliente->idc}}'>{{$cliente->razonSocial}}</option>
                                    @endforeach
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
                            disabled>{!!($consulta->motivoViaje)!!}</textarea></div>
                </div>
            </div>

            <div class="form-group col-md-6">
                <h3>Estado del vehículo</h3>
                <div class="sub-title">Vehiculo:</div>
                <div>
                    <select name='idVehiculo' class="form-control" disabled>
                        @foreach($vehiculo as $vehiculo)
                        <option value='{{$vehiculo->idVehiculo}}'>{{$vehiculo->nombreVehiculo}}
                            {{$vehiculo->tipoVehiculo}} {{$vehiculo->placas}} {{$vehiculo->idMarca}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="sub-title">Evidencia Km y gasolina salida:</div>
                        <div>
                            <center>
                                <div style='text-align'>
                                    @foreach($consultaImg as $consImg)
                                    <img class="zoom" src="{{asset ('/archivos/'.$consImg->fotoEvidenciaSalida)}}"
                                        height=60 width=60>
                                    <!---archivo---->
                                    @endforeach

                                    <a href="{{asset('/archivos/'.$consImg->fotoEvidenciaSalida)}}">
                                        <img src="{{asset('archivos/iconodescarga.png')}}" height=28 width=30>
                                    </a>

                                </div>
                            </center>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="sub-title">Kilometraje inicial: </div>
                        <div>
                            {{Form::text('kmActual',($consulta->kmActual),['class' => 'form-control', 'readonly' => 'true'])}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="sub-title">Evidencia Km y gasolina entrega:</div>
                        <div>
                            <center>
                                <div style='text-align'>
                                    @foreach($consultaImg2 as $consImg2)
                                    <img class="zoom"
                                        src="{{asset ('/archivos/'.$consImg2->fotoEvidenciaRegreso)}}" height=60
                                        width=60>
                                    <!---archivo---->
                                    @endforeach
                                    <a href="{{asset('archivos/'.$consImg2->fotoEvidenciaRegreso)}}">
                                        <img src="{{asset('archivos/iconodescarga.png')}}" height=28 width=30>
                                    </a>
                                </div>
                            </center>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="sub-title">Kilometraje Final: </div>
                        <div>
                            {{Form::text('kmActual',($consulta->kmFinal),['class' => 'form-control', 'readonly' => 'true'])}}
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
                    <div class="row">
                        <div class="form-group col-md-6">
                            <div class="sub-title">Golpes:</div>
                            <div>
                                {{Form::text('golpes',($consulta->golpes),['class' => 'form-control', 'readonly' => 'true'])}}
                            </div>
                            <div class="sub-title">Rayaduras:</div>
                            <div>
                                {{Form::text('rayaduras',($consulta->rayaduras),['class' => 'form-control', 'readonly' => 'true'])}}
                            </div>
                            <div class="sub-title">Aire de llantas:</div>
                            <div>
                                {{Form::text('llantasEntrega',($consulta->llantasEntrega),['class' => 'form-control', 'readonly' => 'true'])}}
                            </div>
                            <div class="sub-title">Luces:</div>
                            <div>
                                {{Form::text('lucesEntrega',($consulta->lucesEntrega),['class' => 'form-control', 'readonly' => 'true'])}}
                            </div>
                            <div class="sub-title">Unidad limpia regreso:</div>
                            <div>
                                {{Form::text('unidadLimpiaEntrega',($consulta->unidadLimpiaEntrega),['class' => 'form-control', 'readonly' => 'true'])}}
                            </div>
                            <div class="sub-title">Observaciones de entrega de la unidad:</div>
                            <div>
                                <textarea name="observacionEntrega" rows="5" class="form-control rounded-0"
                                    disabled>{!!($consulta->observacionEntrega)!!}</textarea></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <center>
            <a href="{{asset('reporte_Viajes')}}"><button type="button" class="btn btn-default">Regresar</button></a>
        </center>
        <br>
    </div>
</div>


@stop