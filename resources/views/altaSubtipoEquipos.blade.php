@extends('principal')
@section('contenido')

{{Form::open(['route' => 'GuardarSubtipoEquipos','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Alta de subtipos de equipos</h1>
        </div>

        <div class="panel-body">
            <div class="">

                <div class="sub-title">* Seleccione un Tipo de equipo:</div>
                <div>
                    @if($errors->first('idTipoEquipo'))
                    <i> {{ $errors->first('idTipoEquipo') }}</i>
                    @endif
                    <select name='idTipoEquipo' class="form-control">
                        <option value="">Seleccione un tipo de equipo</option>
                        @foreach($tipo as $tipo)
                            @if($tipo->activo=="si")
                                <option value='{{$tipo->idTipoEquipo}}'>{{$tipo->tipoEquipo}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="sub-title">Subtipo de equipo:</div>
                <div>
                    @if($errors->first('subtipoEquipo'))
                    <i> {{ $errors->first('subtipoEquipo') }}</i>
                    @endif
                    {{Form::text('subtipoEquipo',old ('subtipoEquipo'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Electrico'])}}
                </div>

                <br>
                <center>
                    {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
            </div>
        </div>
    </div>
</div>
@stop