@extends('principal')
@section('contenido')

{{Form::open(['route' => 'editarTipoEquipos','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Modificar tipos de equipos</h1>
        </div>

        <div class="panel-body">
            <div class="">
                <div class="sub-title">Tipo de equipo:</div>
                <div>
                    @if($errors->first('tipoEquipo'))
                    <i> {{ $errors->first('tipoEquipo') }}</i>
                    @endif
                    {{Form::hidden('idTipoEquipo',$consulta->idTipoEquipo)}}
                    {{Form::text('tipoEquipo',$consulta->tipoEquipo,['class' => 'form-control', 'placeholder' => 'Ejemplo: Motor'])}}
                </div>

                <br>
                <center>
                    {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
                    <a href="{{asset('reporteTipoEquipos')}}"><button type="button"
                                class="btn btn-default">Cancelar</button></a>
            </div>
        </div>
    </div>
</div>
@stop