
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr style="background-color: #ABEBC6">
                            <th>Área</th>
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
                                <td>{{$consulta->nombreArea}}</td>
                                <td align="center">{{$consulta->total}}</td>
                                <td align="center">{{$consulta->abiertas}}</td>
                                <td align="center">{{$consulta->cerradas}}</td>
                                <td align="center">{{$consulta->canceladas}}</td>
                                <td>
                                    <form action="">
                                        @csrf
                                        <input type='hidden' value='{{$consulta->idArea}}' name='idArea' id='idArea'>
                                        <input type='hidden' value='{{$filtroPor}}' name='filtroPor' id='filtroPor'>
                                        <input type='hidden' value='{{$fechaInicio}}' name='fechaInicio' id='fechaInicio'>
                                        <input type='hidden' value='{{$fechaFin}}' name='fechaFin' id='fechaFin'>
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
            
       
    <!--End Advanced Tables -->
    <br><br>

    <script type="text/javascript">
$(document).ready(function() {
    $("#cliente").chosen();

    $("#agrega").click(function() {
        //      alert ("hola");
        $("#filtroSeg").load('{{url('reporteActividadesSegAbajo')}}' + '?' + $(this).closest('form').serialize());

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
