@extends('principal')
@section('contenido')

{{Form::open(['route' => 'guardarAreas','files'=>true])}}
{{Form::token()}}
<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">

        <div class="panel-heading">
            <h1>Alta de áreas <small>Registre nuevas áreas</small></h1> <br><br>
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
                    {{Form::text('nombreArea',old ('nombreArea'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Administrativa'])}}
                </div>
                <br>
                <center>
                    {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
            </div>
        </div>
    </div>

    @stop