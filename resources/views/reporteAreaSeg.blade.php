
    <div class="panel panel-default" style="margin-top:-55px">
        <center>
            <h2>Reporte de actividades por Área</h2><br>
        </center>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr style="background-color: #B1E8DE">
                            <!-- <th>Usuario</th> -->
                            <th>Tipo</th>
                            <th>Número de Actividades</th>
                            <th>Actividades abiertas</th>
                            <th>Actividades cerradas</th>
                            <th>Actividades canceladas</th>
                            <th>Detalle por</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consultaActividad as $consulta)
                            
                            <tr>
                                <td>{{$consulta->nombreActividad}}</td>
                                <td align="center">{{$consulta->total}}</td>
                                <td align="center">{{$consulta->abiertas}}</td>
                                <td align="center">{{$consulta->cerradas}}</td>
                                <td align="center">{{$consulta->canceladas}}</td>
                                <td>
                                    <form action="">
                                    @csrf
                                        <input type='hidden' value='{{$consulta->idArea}}' name='idArea' id='idArea'>
                                        <input type='hidden' value='{{$consulta->idActividad}}' name='idActividad' id='idActividad'>
                                        <input type='hidden' value='{{$filtroPor}}' name='filtroPor' id='filtroPor'>
                                        <input type='hidden' value='{{$fechaInicio}}' name='fechaInicio' id='fechaInicio'>
                                        <input type='hidden' value='{{$fechaFin}}' name='fechaFin' id='fechaFin'>
                                        <button type="button" class="btn trabajadorArea" style="background-color:#B1EB85">
                                            Trabajador
                                        </button>
                                        <button type="button" class="btn empresaArea" style="background-color:#EBE550">
                                            Empresa
                                        </button>
                                        <button type="button" class="btn actividadArea" style="background-color:#E8AD84">
                                            Actividad
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
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
        $('.trabajadorArea').click(function(){
        // alert("hola");
        $("#reporteTrabajador").load('{{url('reporteTrabajadorSeg')}}' + '?' + $(this).closest('form').serialize());
            document.getElementById('reporteTrabajador').style.display = 'block';
            // document.getElementById('reporteTipo').style.display = 'none';
            document.getElementById('reporteActividad').style.display = 'none';
            document.getElementById('reporteEmpresa').style.display = 'none';
        });
        $('.empresaArea').click(function(){
        // alert("hola");
        $("#reporteEmpresa").load('{{url('reporteEmpresaSeg')}}' + '?' + $(this).closest('form').serialize());
            document.getElementById('reporteEmpresa').style.display = 'block';
            document.getElementById('reporteTrabajador').style.display = 'none';
            // document.getElementById('reporteTipo').style.display = 'none';
            document.getElementById('reporteActividad').style.display = 'none';
        });
        $('.actividadArea').click(function(){
        // alert("hola");
        $("#reporteActividad").load('{{url('reportePorAct')}}' + '?' + $(this).closest('form').serialize());
            
            // document.getElementById('reporteTipo').style.display = 'none';
            document.getElementById('reporteEmpresa').style.display = 'none';
            document.getElementById('reporteTrabajador').style.display = 'none';
            document.getElementById('reporteActividad').style.display = 'block';
        });
    });
</script>
