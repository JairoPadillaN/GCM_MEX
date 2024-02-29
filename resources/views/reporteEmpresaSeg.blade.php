<div class="panel panel-default" style="margin-top:-55px">
    <center>
        <h2>Reporte de actividades por Empresa </h2><br>
    </center>
    <div class="panel-body">

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr style="background-color: #EBE550">
                        <!-- <th>Usuario</th> -->
                        <th>Cliente</th>
                        <th>Sucursal</th>
                        <th>Número de Actividades</th>
                        <th>Actividades abiertas</th>
                        <th>Actividades cerradas</th>
                        <th>Actividades canceladas</th>
                        <th>Detalle por</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    @foreach($consultaActividad as $consulta)
                        
                        <tr>
                            <td>{{$consulta->razonSocial}}</td>
                            <td align="center">{{$consulta->sucursal}}</td>
                            <td align="center">{{$consulta->total}}</td>
                            <td align="center">{{$consulta->abiertas}}</td>
                            <td align="center">{{$consulta->cerradas}}</td>
                            <td align="center">{{$consulta->canceladas}}</td>
                            <td>
                                <form action="">
                                @csrf
                                    <input type='hidden' value='{{$consulta->idc}}' name='idc' id='idc'>
                                    <input type='hidden' value='{{$idArea}}' name='idArea' id='idArea'>
                                    <input type='hidden' value='{{$idActividad}}' name='idActividad' id='idActividad'>
                                    <input type='hidden' value='{{$filtroPor}}' name='filtroPor' id='filtroPor'>
                                    <input type='hidden' value='{{$fechaInicio}}' name='fechaInicio' id='fechaInicio'>
                                    <input type='hidden' value='{{$fechaFin}}' name='fechaFin' id='fechaFin'>
                                    
                                    <input type='hidden' value='{{$areaOEmpresa}}' name='areaOEmpresa' id='areaOEmpresa'>
                                    <!-- <button type="button" class="btn btn-info tipo">
                                        Detalle por tipo
                                    </button> -->
                                    <button type="button" class="btn trabajadorEmpresa" style="background-color:#B1EB85">
                                        Trabajador
                                    </button>
                                    <button type="button" class="btn actividadEmpresa" style="background-color:#E8AD84">
                                        Actividad
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<br><br>

<div id="reporteAvance"></div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.avance').click(function(){
            // alert("hola");
            $("#reporteAvance").load('{{url('reporteAvance')}}' + '?' + $(this).closest('form').serialize());
        });

        $('.trabajadorEmpresa').click(function(){
        // alert("hola");
        $("#reporteTrabajador").load('{{url('reporteTrabajadorSeg')}}' + '?' + $(this).closest('form').serialize());
            // document.getElementById('reporteEmpresa').style.display = 'none';
            document.getElementById('reporteTrabajador').style.display = 'block';
            // document.getElementById('reporteTipo').style.display = 'none';
            document.getElementById('reporteActividad').style.display = 'none'     
        });

        $('.actividadEmpresa').click(function(){
        $("#reporteActividad").load('{{url('reportePorAct')}}' + '?' + $(this).closest('form').serialize());
            
            // document.getElementById('reporteTipo').style.display = 'none';
            // document.getElementById('reporteEmpresa').style.display = 'none';
            document.getElementById('reporteTrabajador').style.display = 'none';
            document.getElementById('reporteActividad').style.display = 'block';
        });
    });
</script>
