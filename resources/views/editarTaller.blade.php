@extends('principal')
@section('contenido')

{{Form::open(['route' => 'editarTaller','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">

        <div class="panel-heading">
            <h1>Modificación de taller</h1>
        </div>
        <div class="panel-body">
            <div class="card-title">
                <div class="sub-title">Datos del taller</div>
            </div>
            <div class="form-group col-md-6">

                <div class="sub-title">Nombre del taller:</div>
                <div>
                    {{Form::hidden('idTaller',$consulta->idTaller)}}

                    @if($errors->first('nombreTaller'))
                    <p><i> {{ $errors->first('nombreTaller') }}</i></p>
                    @endif
                    {{Form::text('nombreTaller',$consulta->nombreTaller,['class' => 'form-control', 'placeholder' => 'Ejemplo: Freyer'])}}
                </div>

                <div class="sub-title">Tipo de taller:</div>

                <div>@if($consulta->tipoTaller=='Interno')
                    Interno {{Form::radio('tipoTaller','Interno',true,['cheked'=>'cheked'])}} &nbsp; &nbsp; &nbsp;
                    &nbsp; &nbsp;&nbsp;&nbsp;
                    Externo {{Form::radio('tipoTaller','Externo',false)}}
                    @else
                    Interno {{Form::radio('tipoTaller','Interno',false)}} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                    Externo {{Form::radio('tipoTaller','Externo',true,['cheked'=>'cheked'])}}
                    @endif
                </div>
            </div>
        </div>
        <center>
            {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
            <a href="{{asset('reporteTaller')}}"><button type="button" class="btn btn-default">Cancelar</button></a>
            <br><br>
    </div>
</div>
@stop