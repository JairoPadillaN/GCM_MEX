@extends('principal')
@section('contenido')

{{Form::open(['route' => 'editarSubtipoEquipos','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Modificar subtipos de equipos</h1>
        </div>

        <div class="panel-body">
            <div class="">

            <div class="sub-title">* Tipo:</div>
                <div>
                    @if($errors->first('idTipoEquipo'))
                    <i> {{ $errors->first('idTipoEquipo') }}</i>
                    @endif
                    <select name='idTipoEquipo' class="form-control" rows="5" class="form-control rounded-0">
                        <option value='{{$idtiposel}}'>{{$nomtip}}</option>
                        @foreach($tipo as $tip)
                            @if($tip->activo=="si")
                                <option value='{{$tip->idTipoEquipo}}'>{{$tip->tipoEquipo}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="sub-title">Tipo de equipo:</div>
                <div>
                    @if($errors->first('subtipoEquipo'))
                    <i> {{ $errors->first('subtipoEquipo') }}</i>
                    @endif
                    {{Form::hidden('idSubtipoEquipo',$consulta->idSubtipoEquipo)}}
                    {{Form::text('subtipoEquipo',$consulta->subtipoEquipo,['class' => 'form-control', 'placeholder' => 'Ejemplo: Electrico'])}}
                </div>

                <br>
                <center>
                    {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
                    <a href="{{asset('reporteSubtipoEquipos')}}"><button type="button"
                                class="btn btn-default">Cancelar</button></a>
            </div>
        </div>
    </div>
</div>
@stop