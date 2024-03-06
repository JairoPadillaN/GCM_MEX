@extends('principal')
@section('contenido')



<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Editar Ingreso </h1><br><br>
        </div>
        <div class="panel-body">
            <a href="{{ asset('reporteOtrosing') }}"><button type="button" class="botoncito">
               ← Volver al reporte
            </button></a>
        <div class="panel-body">
            <form action="{{ route('modificarIngreso') }}" method="POST" enctype="multipart/form-data" id="formi">
                @csrf
            <div class="form-group col-md-6">
                {{Form::hidden('idoi',$consul->idoi,['class' => 'form-control', 'id'=>'idoi'])}}
                <div class="sub-title">Fecha:</div>
                <div>
                    {{Form::date('fecha', $consul->fecha,['class' => 'form-control'])}}
                </div>
 

                <div class="sub-title">Monto</div>
                <div>
                    {{Form::text('monto',($consul->monto),['class' => 'form-control', 'placeholder' => 'Ejemplo: 1,500.00'])}}
                </div>

                <div class="sub-title">Moneda</div>
                <div>
                    <select id="moneda" name="moneda" class="form-control rounded-0">
                        <option value="<?php echo ($consul->moneda)?>"><?php echo ($consul->moneda)?></option>
                        <option value="MXN">Pesos Mexicanos MXN</option>
                        <option value="USD">Dolares USD</option>
                    </select>
                </div>

                <div class="sub-title">Tipo de cambio</div>
                <div>
                    {{Form::text('tipocambio',($consul->tipocambio),['id' => 'tipocambio', 'class' => 'form-control', 'placeholder' => 'Ejemplo: 20, 19'])}}
                </div>

                @foreach ($consultaArch as $Arch)
                    
                <div class="sub-title">Archivo 1</div>
                <div>
                    {{ Form::file('archivo1', ['class' => 'form-control rounded-0']) }}
                    <div class="sub-title">Archivo Actual 1: </div>
                    @if ($Arch->archivo1 == 'Sin archivo' || $Arch->archivo1 == '')
                    Sin archvo
                    @else
                    <a href="{{ asset('archivos/' . $Arch->archivo1) }}" target="_blank">{{ $Arch->archivo1 }}</a>
                    @endif
                </div>
                
                <div class="sub-title">Archivo 2</div>
                <div>
                    {{ Form::file('archivo2', ['class' => 'form-control rounded-0']) }}
                    <div class="sub-title">Archivo Actual 2: </div>
                    @if ($Arch->archivo2 == 'Sin archivo' || $Arch->archivo2 == '')
                    Sin archvo
                    @else
                    <a href="{{ asset('archivos/' . $Arch->archivo2) }}" target="_blank">{{ $Arch->archivo2 }}</a>
                    @endif
                </div>
                
                <div class="sub-title">Archivo 3</div>
                <div>
                    {{ Form::file('archivo3', ['class' => 'form-control rounded-0']) }}
                    <div class="sub-title">Archivo Actual 3: </div>
                    @if ($Arch->archivo3 == 'Sin archivo' || $Arch->archivo3 == '')
                    Sin archivo
                    @else
                    <a href="{{ asset('archivos/' . $Arch->archivo3) }}" target="_blank">{{ $Arch->archivo3 }}</a>
                    @endif
                </div>

                @endforeach
            </div>

            <div class="form-group col-md-6">
                <div class="sub-title">Tipo de Ingreso</div>
                <div>
                    <select name="tipoingreso" class="form-control rounded-0">
                        <option selected="selected" value="<?php echo ($consul->tipoingreso)?>"><?php echo ($consul->tipoingreso)?></option>
                        <option value="Aportación a Capital">Aportación a Capital</option>
                        <option value="Prestamo Bancario">Prestamo Bancario</option>
                        <option value="Crédito Revolvente ADTA">Crédito Revolvente ADTA</option>
                    </select>
                    <!--{{Form::text('tipoingreso',($consul->tipoingreso),['class' => 'form-control', 'placeholder' => 'Ejemplo: Aportación Capital'])}}-->
                </div>

                <div class="sub-title">Empresa: </div>
                <div>
                    <select name="empresa" class="form-control rounded-0">
                        <option value="<?php echo ($consul->empresa)?>"><?php echo ($consul->empresa)?></option>
                        <option value="GCM">GCM</option>
                        <!--<option value="USD">Dolares USD</option>-->
                    </select>
                   <!-- {{Form::text('empresa',($consul->empresa),['class' => 'form-control', 'placeholder' => 'Ejemplo: Lala'])}}-->
                </div>

                <div class="sub-title">Condición: </div>
                <div>
                    {{Form::text('condicion',($consul->condicion),['class' => 'form-control', 'placeholder' => 'Ejemplo: Colocar las condiciones necesarias...'])}}
                </div>

                <div class="sub-title">Descripcion: </div>
                <div>
                    {{Form::textarea('descripcion',($consul->descripcion),['class' => 'form-control', 'placeholder' => 'Ejemplo: Redactar la descripcion...'])}}
                </div>

            </div>

            <center>
                {{Form::submit('Guardar',['class' => 'boton'])}}
                <a href="{{asset('reporteOtrosing')}}"><button type="button"
                    class="bot">Cancelar</button></a>
            </form>

        </div>
    </div>
</div>


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

        // Para configurar el estado inicial del campo de tipo de cambio
        if (moneda.value === 'MXN') {
            tipocambio.value = '1';
            tipocambio.readOnly = true;
        }
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

    .bot {
        width: 100px;
        padding: 5px;
        border: 1px solid #ffffff;
        background-color: #c30000;
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