<div class="panel panel-default" style="margin-top:-55px">
    <center>
        <h2>Reporte de actividades por Trabajador </h2><br>
    </center>
    <div class="panel-body">
        <div class="table-responsive">
        
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr style="background-color: #B1EB85">
                        <!-- <th>Usuario</th> -->
                        <th>Nombre</th>
                        <th>Puesto</th>
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
                            <td>{{$consulta->nombreUsuario}}</td>
                            <td align="center">{{$consulta->tipo}}</td>
                            <td align="center">{{$consulta->total}}</td>
                            <td align="center">{{$consulta->abiertas}}</td>
                            <td align="center">{{$consulta->cerradas}}</td>
                            <td align="center">{{$consulta->canceladas}}</td>
                            <td>
                            <form action="">
                            @csrf
                                    <input type='hidden' value='{{$consulta->idu}}' name='idu' id='idu'>
                                    <input type='hidden' value='{{$consulta->idArea}}' name='idArea' id='idArea'>
                                    
                                    <input type='hidden' value='{{$idActividad}}' name='idActividad' id='idActividad'>
                                    <input type='hidden' value='{{$idc}}' name='idc' id='idc'>
                                    <input type='hidden' value='{{$filtroPor}}' name='filtroPor' id='filtroPor'>
                                    <input type='hidden' value='{{$fechaInicio}}' name='fechaInicio' id='fechaInicio'>
                                    <input type='hidden' value='{{$fechaFin}}' name='fechaFin' id='fechaFin'>
                                    <input type='hidden' value='{{$areaOEmpresa}}' name='areaOEmpresa' id='areaOEmpresa'>
                                    <button type="button" class="btn actividadTrabajador" style="background-color:#E8AD84">
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

    <!-- <div id="reporteAvance"></div> -->
        

<script type="text/javascript">
    $(document).ready(function() {
        $('.avance').click(function(){
            // alert("hola");
            $("#reporteAvance").load('{{url('reporteAvance')}}' + '?' + $(this).closest('form').serialize());
        });
        $('.actividadTrabajador').click(function(){
        // alert("hola");
        $("#reporteActividad").load('{{url('reportePorAct')}}' + '?' + $(this).closest('form').serialize());
            
            // document.getElementById('reporteTipo').style.display = 'none';
            // document.getElementById('reporteEmpresa').style.display = 'none';
            // document.getElementById('reporteTrabajador').style.display = 'none';
            document.getElementById('reporteActividad').style.display = 'block';
        });
    });
</script>
