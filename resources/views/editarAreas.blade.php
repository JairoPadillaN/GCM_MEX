@extends('principal')
@section('contenido')

{{Form::open(['route' => 'editarAreas','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">

        <div class="panel-heading" >
            <h1>Modificación de áreas</h1>
            <div class="card-title">
                <div class="title">Datos</div>
            </div>
        </div>

        <div class="panel-body">

            <div class="form-group col-md-6">
                <div class="sub-title">Nombre del área:</div>
                <div>
                    @if($errors->first('nombreArea'))
                    <p><i> {{ $errors->first('nombreArea') }}</i></p>
                    @endif
                    {{Form::hidden('idArea',$consulta->idArea)}}
                    {{Form::text('nombreArea',$consulta->nombreArea,['class' => 'form-control', 'placeholder' => 'Ejemplo: Administrativa'])}}
                </div>
                <br>
                <center>
                    {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
                    <a href="{{asset('reporteAreas')}}"><button type="button"
                            class="btn btn-default">Cancelar</button></a>
            </div>

        </div>
    </div>
    @stop