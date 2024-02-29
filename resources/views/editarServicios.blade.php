@extends('principal')
@section('contenido')

<div class="header">
    <h1 class="page-header">Modificación de Servicios</h1>
</div>

{{Form::open(['route' => 'editarServicios','files'=>true])}}
{{Form::token()}}

<div id="page-inner">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <div class="card-title">
                        <div class="title">Datos del Servicio</div>
                    </div>
                </div>

                <div class="panel-body">

                    <div class="form-group col-md-6">
                        <div class="sub-title">Nombre:</div>
                        <div>
                            @if($errors->first('nombreServicio'))
                            <p><i> {{ $errors->first('nombreServicio') }}</i></p>
                            @endif
                            {{Form::hidden('idServicio',$consulta->idServicio)}}
                            {{Form::text('nombreServicio',$consulta->nombreServicio,['class' => 'form-control', 'placeholder' => 'Ejemplo: Mantenimiento'])}}
                        </div>
                        <br>
                        <center>
                            {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
                            <a href="{{asset('reporteServicios')}}"><button type="button"
                                    class="btn btn-warning">Cancelar</button></a>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @stop