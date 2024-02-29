@extends('principal')
@section('contenido')

<div class="header">
    <h1 class="page-header">Modificacion de Tipos de Gastos</h1>
</div>

{{Form::open(['route' => 'editarTipoGastos'])}}
{{Form::token()}}

<div id="page-inner">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="sub-title">Nombre:</div>
                    <div>
                        @if($errors->first('nombreTipoGasto'))
                        <p><i> {{ $errors->first('nombreTipoGasto') }}</i></p>
                        @endif
                        {{Form::hidden('idTipoGastos',$consulta->idTipoGastos)}}
                        <!--Este cuadro no se vera en la vista ya que es tipo hidden "oculto" solo sera para obetener el ide a modificar y lo enviara al metodo editarTipoGastos-->
                        {{Form::text('nombreTipoGasto',$consulta->nombreTipoGasto,['class' => 'form-control', 'placeholder' => 'Ejemplo: Caja chica'])}}
                    </div>

                    <div class="sub-title">Tipo</div>
                    <div>
                        <select class="selectbox" name="tipo">
                            <option value="<?php echo ($consulta->tipo) ?>"><?php echo ($consulta->tipo) ?></option>
                            <option value="Entrada">Entrada</option>
                            <option value="Salida">Salida</option>
                        </select>
                    </div>
                    <br>
                    <center>
                        {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
                        <a href="{{asset('ReporteTipoGastos')}}"><button type="button"
                                class="btn btn-default">Cancelar</button></a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop