@extends('principal')
@section('contenido')



{{Form::open(['route' => 'editaPaquetes','files'=>true])}}
{{Form::token()}}


<div class="col-xs-12" style="margin-top:-45px">
    <div class="panel panel-default">

        <div class="panel-heading">
            <h1>Modificación de Marcas</h1>
        </div>

        <div class="panel-body">

            <div class="form-group col-md-6">
                <div class="sub-title">Nombre del paquete:</div>
                <div>
                    @if($errors->first('nombrePaquete'))
                    <p><i> {{ $errors->first('nombrePaquete') }}</i></p>
                    @endif
                    {{Form::hidden('idPaquete',$consulta->idPaquete)}}
                    {{Form::text('nombrePaquete',$consulta->nombrePaquete,['class' => 'form-control', 'placeholder' => 'Ejemplo: Paquete 127'])}}
                </div>
                <br>
                <center>
                    {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
                    <a href="{{asset('reportePaquetes')}}"><button type="button"
                            class="btn btn-default">Cancelar</button></a>
            </div>

        </div>
    </div>
</div>

@stop