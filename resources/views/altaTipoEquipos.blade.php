@extends('principal')
@section('contenido')

{{Form::open(['route' => 'GuardarTipoEquipos','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Alta de tipos de equipos</h1>
        </div>

        <div class="panel-body">
            <div class="">
                <div class="sub-title">Tipo de equipo:</div>
                <div>
                    @if($errors->first('tipoEquipo'))
                    <i> {{ $errors->first('tipoEquipo') }}</i>
                    @endif
                    {{Form::text('tipoEquipo',old ('tipoEquipo'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Motor'])}}
                </div>

                <br>
                <center>
                    {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
            </div>
        </div>
    </div>
</div>
@stop