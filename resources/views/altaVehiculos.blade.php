@extends('principal')
@section('contenido')

<div class="header">
    <h1 class="page-header">
        Alta de Vehículos <small>Registre un nuevo vehículo</small>
    </h1>
</div>

{{Form::open(['route' => 'GuardarVehiculos'])}}
{{Form::token()}}

<div id="page-inner">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <div class="card-title">
                        <div class="title">Datos del vehículo</div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="form-group col-md-6">
                        <div class="sub-title">Nombre:</div>
                        <div>
                            @if($errors->first('nombreVehiculo'))
                            <p><i> {{ $errors->first('nombreVehiculo') }}</i></p>
                            @endif
                            {{Form::text('nombreVehiculo',old ('nombreVehiculo'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Peugeout 206i'])}}
                        </div>

                        <div class="form-group col-md-6">
                            <div class="sub-title">Marca:</div>
                            <div>
                                @if($errors->first('idMarca'))
                                <p><i> {{ $errors->first('idMarca') }}</i></p>
                                @endif
                                <select name='idMarca' class="form-control">
                                <option value="">Seleccione una marca</option>
                                    @foreach($marca as $marca)
                                        @if($marca->activo=="si")
                                            <option value='{{$marca->idMarca}}'>{{$marca->nombreMarca}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="sub-title">Tipo de vehículo:</div>
                            <div>
                                @if($errors->first('tipoVehiculo'))
                                <p><i> {{ $errors->first('tipoVehiculo') }}</i></p>
                                @endif
                                <select class="form-control" name="tipoVehiculo">
                                <option value="">Seleccione un tipo</option>
                                    <option value="Sedan">Sedan</option>
                                    <option value="Camioneta">Camioneta/PickUP</option>
                                    <option value="Camioneta">Estaca/Redilas</option>
                                    <option value="Torton">Torton</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="sub-title">Kilometraje actual:</div>
                            <div>
                                @if($errors->first('kmActual'))
                                <p><i> {{ $errors->first('kmActual') }}</i></p>
                                @endif
                                {{Form::text('kmActual',old ('kmActual'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 203000'])}}
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="sub-title">Número de serie (NIV):</div>
                            <div>
                                @if($errors->first('serial'))
                                <p><i> {{ $errors->first('serial') }}</i></p>
                                @endif
                                {{Form::text('serial',old ('serial'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 23467675'])}}
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="sub-title">Placas:</div>
                            <div>
                                @if($errors->first('placas'))
                                <p><i> {{ $errors->first('placas') }}</i></p>
                                @endif
                                {{Form::text('placas',old ('placas'),['class' => 'form-control', 'placeholder' => 'Ejemplo: NHC9723F'])}}
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="sub-title">Toneladas:</div>
                            <div>
                                @if($errors->first('toneladas'))
                                <p><i> {{ $errors->first('toneladas') }}</i></p>
                                @endif
                                {{Form::text('toneladas',old ('toneladas'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 10'])}}
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="sub-title">Motor:</div>
                            <div>
                                @if($errors->first('motor'))
                                <p><i> {{ $errors->first('motor') }}</i></p>
                                @endif
                                {{Form::text('motor',old ('motor'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 12656AsY'])}}
                            </div>
                        </div>

                        <div class="form-group col-md-6">

                            <div class="sub-title">Transmisión:</div>
                            <div>
                                @if($errors->first('transmision'))
                                <p><i> {{ $errors->first('transmision') }}</i></p>
                                @endif
                                Manual {{Form::radio('transmision','Manual')}}
                                Automática {{Form::radio('transmision','Automatica')}}

                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="form-group col-md-6">
                        <div class="sub-title">Empresa Aseguradora:</div>
                        <div>
                            @if($errors->first('empresaAseguradora'))
                            <p><i> {{ $errors->first('empresaAseguradora') }}</i></p>
                            @endif
                            {{Form::text('empresaAseguradora',old ('empresaAseguradora'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Metlife'])}}
                        </div>

                        <div class="sub-title">Número de póliza:</div>
                        <div>
                            @if($errors->first('numPoliza'))
                            <p><i> {{ $errors->first('numPoliza') }}</i></p>
                            @endif
                            {{Form::text('numPoliza',old ('numPoliza'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 236541'])}}
                        </div>

                        <div class="sub-title">Descripción de póliza:</div>
                        <div>
                            @if($errors->first('descripcionPoliza'))
                            <p><i> {{ $errors->first('descripcionPoliza') }}</i></p>
                            @endif
                            {{Form::textarea('descripcionPoliza',old ('descripcionPoliza'),['class' => 'form-control'])}}
                        </div>
                    </div>
                </div>
                <center>
                    {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}</center><br>
            </div>
        </div>
    </div>
</div>
</div>
@stop