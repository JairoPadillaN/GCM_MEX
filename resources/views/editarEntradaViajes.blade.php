@extends('principal')
@section('contenido')


{{Form::open(['route' => 'modificarViajes2'])}}
{{Form::token()}}

<div id="page-inner">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="header">
                    <h1 class="	">
                        Finalizar Viaje
                    </h1>
                </div>
                {{Form::hidden('idViaje',$consulta->idViaje)}}

                <div class="panel-body">
                    <div class="form-group col-md-6">
                        <div class="sub-title">Usuario: <br>
                            <select name='idu'>

                                <option value='{{$idusuariosel}}'>{{$nomusuario}} {{$aPaterno}} {{$aMaterno}}</option>
                                @foreach($usuario as $us)
                                @if($us->activo=="Si")
                                <option value='{{$us->idu}}'>{{$us->tipo}} - {{$us->nombreUsuario}} {{$us->aPaterno}}
                                    {{$us->aMaterno}} </option>
                                @endif
                                @endforeach
                            </select>
                            {{Form::hidden('estatus','Finalizado')}}
                        </div>
                    </div>



                    <div class="form-group col-md-6">
                        <div class="sub-title">
                            @if($errors->first('Hola'))
                            <p><i> {{ $errors->first('Hola') }}</i></p>
                            @endif
                            Número de Placas:
                            {{Form::text('Hola', \Carbon\Carbon::now(),['class' => 'form-control', 'readonly' => 'true'])}}
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="sub-title">Vehiculo:
                            <select name='idVehiculo'>
                                <option value='{{$idVehiculosel}}'>{{$nomVehiculo}} {{$placas}} {{$idMarca}}</option>
                                @foreach($vehiculo as $vehiculo)
                                @if($vehiculo->activo=="Si")
                                <option value='{{$vehiculo->idVehiculo}}'>{{$vehiculo->nombreVehiculo}} {{$vehiculo->tipoVehiculo}}
                                    {{$vehiculo->placas}} {{$vehiculo->idMarca}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="sub-title">Tipo de viaje:</div>
                        {{Form::text('tipoViaje',($consulta->tipoViaje),['class' => 'form-control', 'readonly' => 'true'])}}

                    </div>
                    <div class="form-group col-md-6">
                        <div class="sub-title">Empresa a visitar:
                            <select name='idc'>
                                <option value='{{$idclientesel}}'>{{$nomcli}}</option>
                                @foreach($cli as $clientes)
								@if($clientes->activo=="si")
                                            <option value='{{$clientes->idc}}'>{{$clientes->razonSocial}}</option>
                                        @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="sub-title">
                            Monto:
                            {{Form::text('montoGasto',($consulta->montoGasto),['class' => 'form-control', 'readonly' => 'true'])}}
                        </div>
                    </div>





                    <div class="form-group col-md-6">
                        <div class="sub-title">Motivo:</div>
                        {{Form::textarea('motivoViaje',($consulta->motivoViaje),['class' => 'form-control', 'readonly' => 'true'])}}
                    </div>
                    <p></p><br><br>
                    <div class="form-group col-md-6">
                        <div class="sub-title">Observaciones de la unidad:
                            {{Form::textarea('observacionSalida',($consulta->observacionSalida),['class' => 'form-control', 'readonly' => 'true'])}}
                        </div>
                    </div>


                    <div class="form-group col-md-6">
                        <h4>Datos de salida de la unidad</h4>


                        <div class="sub-title">
                            Fecha de Salida:
                            {{Form::text('fechaInicio',($consulta->fechaInicio),['class' => 'form-control', 'readonly' => 'true'])}}
                        </div>


                        <div class="sub-title">
                            Kilometraje inicial:
                            {{Form::text('kmActual',($consulta->kmActual),['class' => 'form-control', 'readonly' => 'true'])}}
                        </div>



                        <div class="sub-title">Liquido de frenos:</div>
                        {{Form::text('liquidoFrenos',($consulta->liquidoFrenos),['class' => 'form-control', 'readonly' => 'true'])}}
                        <div class="sub-title">Parabrisas:</div>
                        {{Form::text('parabrisas',($consulta->parabrisas),['class' => 'form-control', 'readonly' => 'true'])}}
                        <div class="sub-title">Aire de llantas:</div>
                        {{Form::text('llantasSalida',($consulta->llantasSalida),['class' => 'form-control', 'readonly' => 'true'])}}
                        <div class="sub-title">Luces:</div>
                        {{Form::text('lucesSalida',($consulta->lucesSalida),['class' => 'form-control', 'readonly' => 'true'])}}
                        <div class="sub-title">Anticongelante:</div>
                        {{Form::text('anticongelante',($consulta->anticongelante),['class' => 'form-control', 'readonly' => 'true'])}}



                    </div>


                    <div class="form-group col-md-6">
                        <h4>Datos de entrego de la unidad</h4>


                        <div class="sub-title">
                            @if($errors->first('fechaFin'))
                            <p><i> {{ $errors->first('fechaFin') }}</i></p>
                            @endif
                            Fecha de Entrega:
                            {{Form::date('fechaFin', \Carbon\Carbon::now(),['class' => 'form-control'])}}
                        </div>

                        <div class="sub-title">


                            Kilometraje Final: {{Form::text('kmFinal',old ('kmFinal'),['class' => 'form-control'])}}


                        </div>

                        <div class="sub-title">Golpes:</div>
                        Si {{Form::radio('golpes','Si')}}
                        No {{Form::radio('golpes','No')}}
                        <div class="sub-title">Rayaduras:</div>
                        Si {{Form::radio('rayaduras','Si')}}
                        No {{Form::radio('rayaduras','Si')}}
                    </div>
                    <div class="form-group col-md-6">
                        <div class="sub-title">Aire de llantas:</div>
                        Si {{Form::radio('llantasEntrega','Si')}}
                        No {{Form::radio('llantasEntrega','No')}}
                        <div class="sub-title">Luces:</div>
                        Si {{Form::radio('lucesEntrega','Si')}}
                        No {{Form::radio('lucesEntrega','No')}}
                    </div>
                    <div class="form-group col-md-6">
                        <div class="sub-title">Observaciones de entrega de la unidad</div>
                        @if($errors->first('observacionEntrega'))
                        <p><i> {{ $errors->first('observacionEntrega') }}</i></p>
                        @endif
                        <div>{{Form::textarea('observacionEntrega')}}</div>
                    </div>


                </div>

                <center>{{Form::submit('Realizar entrega',['class' => 'btn  btn-default'])}}</center>
            </div>
        </div>
    </div>
</div>

@stop