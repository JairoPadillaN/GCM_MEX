<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
<div class="sub-title" align="center">Seguimientos de la actividad:</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
        <tr style="background-color: #93BEE6;">
                <th>
                    <font SIZE=2>Número de seguimiento</font>
                </th>
                <th>
                    <font SIZE=2>Fecha de avance</font>
                </th>
                <th>
                    <font SIZE=2>Detalle</font>
                </th>
                <th>
                    <font SIZE=2>Estatus</font>
                </th>
                <th>
                    <font SIZE=2>Resultado</font>
                </th>
                <th>
                    <font SIZE=2>Notas</font>
                </th>
                <th>
                    <font SIZE=2>Propuesta</font>
                </th>
                <th>
                    <font SIZE=2>% de avance</font>
                </th>
                <th>
                    <font SIZE=2>Archivos</font>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalle as $det)
                <tr>
                    <td>{{$det->folio}}</td>
                    <td>{{$det->fechaDet}}</td>
                    <td>{{$det->detalleSeg}}</td>
                    <td>{{$det->estado}}</td>
                    <td>{{$det->resultadoSeg}}</td>
                    <td>{{$det->notasSeg}}</td>
                    <td>{{$det->propuestaSeg}}</td>
                    <td>{{$det->porcentaje}} %</td>
                    <td align="center">
                     <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                                            <input type='hidden' value='{{$det->idDetSeg}}' name='idDetSeg' id='idDetSeg'>
                                            <input type='hidden' value='{{$det->idSegActividad}}' name='idSegActividad' id='idSegActividad'>
                                        
                                            <button type="button" class="btn btn-sm btn-success verArchivos">
                                                <i class="fa fa-folder-open"></i>
                                            </button>
                                            </div>
                                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>



            <div id="archivosUsuario">
            
            </div>

<script type="text/javascript">
$(function() {
    $('.borrarSegAct').click(
        function() {
            // alert("borrar");
            $("#reportitoSeguimientos").load('{{url('borrarSegUs')}}' + '?' + $(this).closest('form').serialize());
        }
    );


    
});

$('.verArchivos').click(function(){
            // alert("archivos");
        
        // var detSeg = $("#idDetSeg" ).val();
        // console.log(detSeg);
        $("#archivosUsuario").load('{{url('archivosSeg')}}' + '?' + $(this).closest('form').serialize());
});
</script>