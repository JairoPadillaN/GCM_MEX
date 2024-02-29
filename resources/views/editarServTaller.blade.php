@extends('principal')
@section('contenido')

{{Form::open(['route' => 'editarServTaller','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Editar servicios de taller</h1>
        </div>

        <div class="panel-body">
            <div class="card-title">
                <div class="sub-title">Datos del servicio</div>
            </div>
                
            <div class="sub-title">*Alcance:</div>
            <div>
                @if($errors->first('idAlcance'))
                    <i> {{ $errors->first('idAlcance') }}</i>
                @endif
                <select name="idAlcance" id="comboAlcance" class="form-control">
                    <option value='{{$idAlcanceConsulta}}'>{{$nombreAlcance}}</option>
                    @foreach($losOtrosAlcancesOrden as $alcancesOrden)
                        @if($alcancesOrden->activo=="si")
                            <option value='{{$alcancesOrden->idAlcance}}'>{{$alcancesOrden->alcance}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
                    <div class="sub-title">Nombre del servicio de taller:</div>
                    <div>
                        {{Form::hidden('idServicioTaller',$consulta->idServicioTaller)}}
                        @if($errors->first('nombreServTaller'))
                        <p><i> {{ $errors->first('nombreServTaller') }}</i></p>
                        @endif
                        {{Form::text('nombreServTaller',$consulta->nombreServTaller,['class' => 'form-control', 'placeholder' => 'Ejemplo: Reparación'])}}
                    </div>
                    {{Form::hidden('costoInterno',$consulta->costoInterno)}}
                    {{Form::hidden('costoExterno',$consulta->costoExterno)}}
                    @if($stipo=='Administrador')
                    <div class="sub-title">Precio venta (Pesos):</div>
                    <div>
                        @if($errors->first('costoInterno'))
                        <p><i> {{ $errors->first('costoInterno') }}</i></p>
                        @endif
                        {{Form::text('costoInterno',$consulta->costoInterno,['class' => 'form-control', 'placeholder' => 'Ejemplo: 230.00'])}}
                    </div>

                    <div class="sub-title">Precio venta (Dólares):</div>
                    <div>
                        @if($errors->first('costoExterno'))
                        <p><i> {{ $errors->first('costoExterno') }}</i></p>
                        @endif
                        {{Form::text('costoExterno',$consulta->costoExterno,['class' => 'form-control', 'placeholder' => 'Ejemplo: 230.00'])}}
                    </div>
                    @endif
                    <br>
                    <center>
                        {{Form::submit('Guardar',['class' => 'btn  btn-success'])}}
                        <a href="{{asset('reporteServTaller')}}"><button type="button"
                                            class="btn btn-default">Cancelar</button></a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop