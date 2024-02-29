@extends('principal')
@section('contenido')



{{Form::open(['route' => 'guardarServTaller','files'=>true])}}
{{Form::token()}}


<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Registro de servicios de taller</h1>
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
                    <option value="">Seleccione una opción:</option>
                        @foreach($comboAlcances as $comboAlcances)
                            @if($comboAlcances->activo=="si")
                                <option value='{{$comboAlcances->idAlcance}}'>{{$comboAlcances->alcance}}</option>
                            @endif
                        @endforeach 
                </select>
            </div>
            
            <div class="sub-title">Nombre del servicio de taller:</div>
            <div>
                @if($errors->first('nombreServTaller'))
                <i> {{ $errors->first('nombreServTaller') }}</i>
                @endif
                {{Form::text('nombreServTaller',old ('nombreServTaller'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Reparación'])}}
            </div>
            
            @if($stipo=='Administrador')
            <div class="sub-title">Precio venta (Pesos):</div>
            <div>
                @if($errors->first('costoInterno'))
                <i> {{ $errors->first('costoInterno') }}</i>
                @endif
                {{Form::text('costoInterno',old ('costoInterno'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 230.00'])}}
            </div>

            <div class="sub-title">Precio venta (Dólares):</div>
            <div>
                @if($errors->first('costoExterno'))
                <i> {{ $errors->first('costoExterno') }}</i>
                @endif
                {{Form::text('costoExterno',old ('costoExterno'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 230.00'])}}
            </div>            
            @endif
            <br>
            <center>
                {{Form::submit('Guardar',['class' => 'btn  btn-success'])}}
        </div>
    </div>
</div>
@stop