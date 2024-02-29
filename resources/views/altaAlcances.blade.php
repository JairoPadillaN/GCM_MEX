@extends('principal')
@section('contenido')

{{Form::open(['route' => 'guardarAlcance','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        
		<div class="panel-heading">
            <h1>Alta de Alcances</h1>            
        </div>

        <div class="panel-body">
            <div class="form-group col-md-6">
                <div class="sub-title">Nombre de alcance:</div>
                <div>
                    @if($errors->first('alcance'))
                    <p><i> {{ $errors->first('alcance') }}</i></p>
                    @endif
                    {{Form::text('alcance',old ('alcance'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Nissan'])}}
                </div>
                <br>
                <center>
                    {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
            </div>
        </div>

    </div>
</div>
@stop