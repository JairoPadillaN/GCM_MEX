@extends('principal')
@section('contenido')

{{Form::open(['route' => 'guardarEstatusReparaciones','files'=>true])}}
{{Form::token()}}


<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Registro de estatus de reparación</h1>
            </h1>
        </div>
        <div class="panel-body">
            <div class="card-title">
                <div class="sub-title">Datos del estatus</div>
            </div>
            <div class="form-group col-md-6">
                <div class="sub-title">Nombre del estatus:</div>
                <div>
                    @if($errors->first('nombreEstatus'))
                    <p><i> {{ $errors->first('nombreEstatus') }}</i></p>
                    @endif
                    {{Form::text('nombreEstatus',old ('nombreEstatus'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Pendiente'])}}
                </div>
            </div>
            <br><br><br><br><br><br>
                <center>
                    {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
        </div>
    </div>
    @stop