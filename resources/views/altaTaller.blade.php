@extends('principal')
@section('contenido')

{{Form::open(['route' => 'guardarTaller','files'=>true])}}
{{Form::token()}}


<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Registro de taller</h1>
        </div>

        <div class="panel-body">
            <div class="card-title">
                <div class="sub-title">Datos del taller</div>
            </div>
            <div class="form-group col-md-6">

                <div class="sub-title">Nombre del taller:</div>
                <div>
                    @if($errors->first('nombreTaller'))
                    <p><i> {{ $errors->first('nombreTaller') }}</i></p>
                    @endif
                    {{Form::text('nombreTaller',old ('nombreTaller'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Freyer'])}}
                </div>

                <div class="sub-title">Tipo de taller:</div>
                @if($errors->first('tipoTaller'))
                <i> {{ $errors->first('tipoTaller') }}</i>
                @endif<div>
                    Interno {{Form::radio('tipoTaller','Interno')}} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
                    Externo {{Form::radio('tipoTaller','Externo')}}
                </div>
            </div>
        </div>
        <center>
            {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
        </center>
        <br>
    </div>
</div>

@stop