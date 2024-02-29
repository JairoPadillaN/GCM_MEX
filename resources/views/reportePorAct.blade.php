
    <div class="panel panel-default" style="margin-top:-55px">
    <center> <h2>Detalle de actividades </h2><br><br></center>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr style="background-color: #E8AD84">
                            <th>Folio</th>
                            <th>Empresa - Sucursal</th>
                            <th>Creado por</th>
                            <th>Asunto - Detalle</th>
                            <th>Periodo de atencón</th>
                            <th>Nivel de atención</th>
                            <th>Área - Tipo</th>
                            <th>Avance</th>
                            <th>Atendido por</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($seguimientoA as $consulta)
                        <tr>
                            <td>{{$consulta->folio}}</td>
                            <td>{{$consulta->cliente}}</td>
                            <td>{{$consulta->nombreUsuario}}</td>
                            <td>{{$consulta->asunto}}</td>
                            <td>{{$consulta->periodo}}</td>
                            <td>{{$consulta->importanciaSeguimiento}}</td>
                            <td>{{$consulta->nombreArea}}-{{$consulta->nombreActividad}}</td>
                            <td>{{$consulta->porcentaje}}%</td>
                            <td>{{$consulta->atendidopor}}</td>
                            <td>
                            <a href="{{URL::action('seguimientoActController@detalleSeg',['idSegActividad'=>$consulta->idSegActividad])}}"
                                                        type="submit" class="btn btn-xs btn-primary"><i
                                                            class="bigger-120"> Detalle</i>
                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>

<!-- <script type="text/javascript">
    $(document).ready(function() {
        
        $('.area').click(function(){
            // alert("hola");
            $("#reporteArea").load('{{url('reporteAreaSeg')}}' + '?' + $(this).closest('form').serialize());
        
    });
});
</script> -->
