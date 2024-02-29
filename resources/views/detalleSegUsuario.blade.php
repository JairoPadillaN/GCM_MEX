<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
@if($cuantos == 0)
<div class="alert alert-warning" role="alert" align="center">
                                <label for="">Sin seguimientos.</label>
                            </div>

@else
<div class="sub-title" align="center">Seguimientos de la actividad:</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
        <tr style="background-color: #93BEE6;">
                <th width="20px">
                    <font SIZE=2>Número de seguimiento</font>
                </th>
                <th>
                    <font SIZE=2>Fecha de avance</font>
                </th>
                <th>
                    <font SIZE=2>Fecha y hora de creación</font>
                </th>
                <th width="30%">
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
                    <font SIZE=2>Fecha de próximo seguimiento</font>
                </th>
                <th>
                    <font SIZE=2>% de avance</font>
                </th>
                <th colspan=2>
                    <font SIZE=2>Opciones</font>
                </th>
            </tr>
        </thead>
        <tbody>
                                        @foreach($detalle as $det)
                                        <tr>
                                            <td align="center">{{$det->folio}}</td>
                                            <td>{{$det->fechaDet}}</td>
                                            <td>{{$det->created_at}}</td>
                                            <td>{{$det->detalleSeg}}</td>
                                            <td>{{$det->estado}}</td>
                                            <td>{{$det->resultadoSeg}}</td>
                                            <td>{{$det->notasSeg}}</td>
                                            <td>{{$det->propuestaSeg}}</td>
                                            <td>{{$det->fechaSiguiente}}</td>
                                            <td align="center">{{$det->porcentaje}} %</td>
                                            <td align="center">
                                                <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                                                @csrf
                                                    <input type='hidden' value='{{$det->idDetSeg}}' name='idDetSeg' id='idDetSeg'>
                                                    <input type='hidden' value='{{$det->idSegActividad}}' name='idSegActividad' id='idSegActividad'>
                                                    <br><br>
                                                    <button type="button" class="btn btn-sm btn-success ver">
                                                        <i class="fa fa-folder-open"></i>
                                                    </button>
                                                </form>
                                            
                                        @endforeach
                                                <br>
                                                <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                                                @csrf
                                                    <input type='hidden' value='{{$det->idDetSeg}}' name='idDetSeg' id='idDetSeg'>
                                                    <input type='hidden' value='{{$det->idSegActividad}}' name='idSegActividad' id='idSegActividad'>
                                                    <button type="button" class="btn btn-sm btn-danger borrarSeguiUs" onclick="location.reload()" >
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
    </table>
</div>
@endif

<script type="text/javascript">
$(function() {
    $('.borrarSeguiUs').click(
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
        $("#archivos").load('{{url('archivosSeg')}}' + '?' + $(this).closest('form').serialize());
});
</script>