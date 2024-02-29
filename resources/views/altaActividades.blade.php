@extends('principal')
@section('contenido')

{{Form::open(['route' => 'guardarActividades','files'=>true])}}
{{Form::token()}}
<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">

        <div class="panel-heading">
            <h1>Alta de actividades <small>Registre nuevas actividades</small></h1> <br><br>
            <div class="card-title">
                <div class="title">Datos</div>
            </div>
        </div>

        <div class="panel-body">

            <div class="form-group col-md-6">
                <div class="sub-title">Área:</div>
                <div>
                    <select name="idArea" id="" class="form-control">
                        <option value="">Selecciona área</option>
                        @foreach($area as $area)
                            <option value="{{$area->idArea}}">{{$area->nombreArea}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sub-title">Nombre de la actividad:</div>
                <div>
                    @if($errors->first('nombreActividad'))
                    <p><i> {{ $errors->first('nombreActividad') }}</i></p>
                    @endif
                    {{Form::text('nombreActividad',old ('nombreActividad'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Revisiones'])}}
                </div>
                <br>
                <center>
                    {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
            </div>
        </div>
    </div>

    @stop