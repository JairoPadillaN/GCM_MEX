@extends('principal')
@section('contenido')

{{Form::open(['route' => 'guardarIngreso','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Alta de Ingresos </h1><br><br>
        </div>
        <div class="panel-body">
            <a href="{{ asset('reporteOtrosing') }}"><button type="button" class="botoncito">
               ← Volver al reporte
            </button></a>
        <div class="panel-body">
            <div class="form-group col-md-6">
                <div class="sub-title">Fecha:</div>
                <div>
                    {{Form::date('fecha', \Carbon\Carbon::now(),['class' => 'form-control'])}}
                </div>


                <div class="sub-title">Monto</div>
                <div>
                    @if($errors->first('monto'))
                    <i> {{ $errors->first('monto') }}</i>
                    @endif
                    {{Form::text('monto',old ('monto'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 1,500.00'])}}
                </div>

                <div class="sub-title">Moneda</div>
                <div>
                    @if($errors->first('moneda'))
                    <i> {{ $errors->first('moneda') }}</i>
                    @endif
                    <select id="moneda" name="moneda" class="form-control rounded-0">
                        <option selected="selected" value="">Seleccione el tipo de Moneda</option>
                        <option value="MXN">Pesos Mexicanos MXN</option>
                        <option value="USD">Dolares USD</option>
                    </select>
                </div>

                <div class="sub-title">Tipo de cambio</div>
                <div>
                    @if($errors->first('tipocambio'))
                    <i> {{ $errors->first('tipocambio') }}</i>
                    @endif
                    {{Form::text('tipocambio',old ('tipocambio'),['id' => 'tipocambio', 'class' => 'form-control', 'placeholder' => 'Ejemplo: 20, 19'])}}
                </div>

                <div class="sub-title">Archivo 1</div>
                <div>
                    {{Form::file('archivo1',['class' => 'form-control rounded-0'])}}
                </div>
                <div class="sub-title">Archivo 2</div>
                <div>
                    {{Form::file('archivo2',['class' => 'form-control rounded-0'])}}
                </div>
                <div class="sub-title">Archivo 3</div>
                <div>
                    {{Form::file('archivo3',['class' => 'form-control rounded-0'])}}
                </div>
            </div>

            <div class="form-group col-md-6">
                <div class="sub-title">Tipo de Ingreso</div>
                <div>
                    @if($errors->first('tipoingreso'))
                    <i> {{ $errors->first('tipoingreso') }}</i>
                    @endif
                    <select name="tipoingreso" class="form-control rounded-0">
                        <option selected="selected" value="">Seleccione el tipo de Ingreso</option>
                        <option value="Aportación a Capital">Aportación a Capital</option>
                        <option value="Prestamo Bancario">Prestamo Bancario</option>
                        <option value="Crédito Revolvente ADTA">Crédito Revolvente ADTA</option>
                    </select>
                    <!--{{Form::text('tipoingreso',old ('tipoingreso'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Aportación Capital'])}}-->
                </div>

                <div class="sub-title">Empresa: </div>
                <div>
                    @if($errors->first('empresa'))
                    <i> {{ $errors->first('empresa') }}</i>
                    @endif
                    <select name="empresa" class="form-control rounded-0">
                        <option selected="selected" value="GCM">GCM</option>
                       <!--<option value="MXN">Pesos Mexicanos MXN</option>
                        <option value="USD">Dolares USD</option>-->
                    </select>
                    <!--{{Form::text('empresa',old ('empresa'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Lala'])}}-->
                </div>

                <div class="sub-title">Condición: </div>
                <div>
                    @if($errors->first('condicion'))
                    <i> {{ $errors->first('condicion') }}</i>
                    @endif
                    {{Form::text('condicion',old ('condicion'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Colocar las condiciones necesarias...'])}}
                </div>

                <div class="sub-title">Descripcion: </div>
                <div>
                    {{Form::textarea('descripcion',old ('descripcion'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Redactar la descripcion...'])}}
                </div>

            </div>

            <center>
                {{Form::submit('Guardar',['class' => 'boton'])}}
        </div>
    </div>
</div>

{{ Form::close() }}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var moneda = document.getElementById('moneda');
        var tipocambio = document.getElementById('tipocambio');

        moneda.addEventListener('change', function() {
            if (this.value === 'MXN') {
                tipocambio.value = '1';
                tipocambio.readOnly = true;
            } else {
                tipocambio.value = '';
                tipocambio.readOnly = false;
            }
        });
    });
</script>

<style>
    .boton {
        width: 150px;
        padding: 5px;
        border: 1px solid #ffffff;
        background-color: #0049d1;
        color: #ffffff;
        border-radius: 5px;
    }

    .botoncito {
        width: 150px;
        padding: 6px;
        border: 1px solid #ffffff;
        background-color: #046d00;
        color: #ffffff;
        border-radius: 5px;
        position: absolute;
        left: 56px;
        top: 25px;
    }
</style>
@stop