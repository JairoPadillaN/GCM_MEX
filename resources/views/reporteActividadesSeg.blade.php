@extends('principal')
@section('contenido')
<!-- <link rel="stylesheet" href="{{asset('chosen.min.css')}}">
    <script src="{{asset('chosen.jquery.min.js')}}"></script> -->
<div class="col-md-12">
    <!-- Advanced Tables -->
    <div class="panel panel-default" style="margin-top:-55px">
        <br><center><h1>Reporte general de actividades</h1></center><br>
        
        <div class="panel-body">
        
            <div class="row">
                <form action="">
                    <div class="col-md-12">
                        <div class="col-xs-6 col-md-3">
                            <div class="sub-title">Seleccione tipo de fecha:</div>
                            <div>
                                Fecha de inicio {{Form::radio('filtroPor','fechaInicio', true)}}&nbsp;&nbsp;
                                Fecha de término  {{Form::radio('filtroPor','fechaTermino')}}&nbsp;&nbsp;
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-3">
                            <div class="sub-title">Fecha de inicio</div>
                            <input type='date' name='fechaInicio' id='fechaInicio' class="form-control rounded-0">
                        </div>
                        <div class="col-xs-6 col-md-3">
                            <div class="sub-title">Fecha de Fin</div>
                            <input type='date' name='fechaFin' id='fechaFin' class="form-control rounded-0">
                        </div>
                        <br>
                    </div>
                    <div class="col-md-12">
                        <div class="col-xs-6">
                            <div class="sub-title">Seleccione una Empresa: </div>
                            <select name="empresa[]" data-placeholder="Seleccione una Empresa" id="cliente" class="form-control rounded-0" multiple>
                                <option value="">Seleccione una empresa</option>
                                <option value="todos">Todos</option>
                                @foreach($cliente as $cliente)
                                @if($cliente->activo=="si")
                                <option value='{{$cliente->idc}}'>{{$cliente->razonSocial}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    
                        <div class="col-xs-6 col-md-3">
                            <div class="sub-title">Seleccione filtrar por:</div>
                            <div>
                                Área {{Form::radio('areaOEmpresa','Area', true)}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                Empresa  {{Form::radio('areaOEmpresa','Empresa')}}&nbsp;&nbsp;
                            </div>
                        </div><br><br>
                        <div class="col-xs-6 col-md-3" >
                            <button type="button" class="btn  btn-default" name="agrega" id="agrega">Buscar</button>
                        </div>
                    </div>
                </form> 
            </div><br>
            <div id="filtroSeg">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr style="background-color: #ABEBC6">
                                <th>Área</th>
                                <th>Número de Actividades</th>
                                <th>Actividades abiertas</th>
                                <th>Actividades cerradas</th>
                                <th>Actividades canceladas</th>
                                <th>Porcentaje de avance</th>
                                <th>Detalle por</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($consulta as $consulta)
                            
                                <tr>
                                    <td>{{$consulta->nombreArea}}</td>
                                    <td align="center">{{$consulta->total}}</td>
                                    <td align="center">{{$consulta->abiertas}}</td>
                                    <td align="center">{{$consulta->cerradas}}</td>
                                    <td align="center">{{$consulta->canceladas}}</td>
                                    <td align="center">{{$consulta->porcentaje}}%</td>
                                    <td>
                                        <form action="">
                                        @csrf
                                            <input type='hidden' value='{{$consulta->idArea}}' name='idArea' id='idArea'>
                                            <input type='hidden' value='' name='filtroPor' id='filtroPor'>
                                            <button type="button" class="btn tipo" style="background-color:#B1E8DE">
                                                Tipo
                                            </button>

                                            
                                            <button type="button" class="btn trabajador" style="background-color:#B1EB85">
                                                Trabajador
                                            </button>
                                            <button type="button" class="btn empresa" style="background-color:#EBE550">
                                                Empresa
                                            </button>
                                            <button type="button" class="btn actividad" style="background-color:#E8AD84">
                                                Actividad
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        <tr></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--End Advanced Tables -->
    <br><br>
    <div id="reporteTipo"></div>
    <div id="reporteEmpresa"></div>
    <div id="reporteTrabajador"></div>
    <div id="reporteActividad"></div>
    
</div>


<script type="text/javascript">
$(document).ready(function() {
    $("#cliente").chosen();

    $("#agrega").click(function() {
        //      alert ("hola");
        $("#filtroSeg").load('{{url('reporteActividadesSegAbajo')}}' + '?' + $(this).closest('form').serialize());
            document.getElementById('reporteTipo').style.display = 'none';
            document.getElementById('reporteActividad').style.display = 'none';
            document.getElementById('reporteTrabajador').style.display = 'none';
            document.getElementById('reporteEmpresa').style.display = 'none';

    });
    
    $('.tipo').click(function(){
        // alert("hola");
        $("#reporteTipo").load('{{url('reporteTipoSeg')}}' + '?' + $(this).closest('form').serialize());
            document.getElementById('reporteTipo').style.display = 'block';
            document.getElementById('reporteActividad').style.display = 'none';
            document.getElementById('reporteTrabajador').style.display = 'none';
            document.getElementById('reporteEmpresa').style.display = 'none';
    });
    $('.trabajador').click(function(){
        // alert("hola");
        $("#reporteTrabajador").load('{{url('reporteTrabajadorSeg')}}' + '?' + $(this).closest('form').serialize());
            document.getElementById('reporteTrabajador').style.display = 'block';
            document.getElementById('reporteTipo').style.display = 'none';
            document.getElementById('reporteActividad').style.display = 'none';
            document.getElementById('reporteEmpresa').style.display = 'none';
    });
    $('.empresa').click(function(){
        // alert("hola");
        $("#reporteEmpresa").load('{{url('reporteEmpresaSeg')}}' + '?' + $(this).closest('form').serialize());
            document.getElementById('reporteEmpresa').style.display = 'block';
            document.getElementById('reporteTrabajador').style.display = 'none';
            document.getElementById('reporteTipo').style.display = 'none';
            document.getElementById('reporteActividad').style.display = 'none';
    });
    $('.actividad').click(function(){
        // alert("hola");
        $("#reporteActividad").load('{{url('reportePorAct')}}' + '?' + $(this).closest('form').serialize());
        document.getElementById('reporteActividad').style.display = 'block';
        document.getElementById('reporteTipo').style.display = 'none';
        document.getElementById('reporteEmpresa').style.display = 'none';
        document.getElementById('reporteTrabajador').style.display = 'none';
    });
});
</script>
@stop