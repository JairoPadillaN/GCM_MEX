@extends('principal')
@section('contenido')

{{Form::open(['route' => 'editarActividades','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">

        <div class="panel-heading" >
            <h1>Modificación de actividades</h1>
            <div class="card-title">
                <div class="title">Datos</div>
            </div>
        </div>

        <div class="panel-body">

            <div class="form-group col-md-6">
                <div class="sub-title">Área:</div>
                <div>
                    <select name="idArea" id="" class="form-control">
                        <option value="{{$areaSel->idArea}}">{{$nomArea}}</option>
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
                    {{Form::hidden('idActividad',$consulta->idActividad)}}
                    {{Form::text('nombreActividad',$consulta->nombreActividad,['class' => 'form-control', 'placeholder' => 'Ejemplo: Revisiones'])}}
                </div>
                <br>
                <center>
                    {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
                    <a href="{{asset('reporteActividades')}}"><button type="button"
                            class="btn btn-default">Cancelar</button></a>
            </div>

        </div>
    </div>
    @stop