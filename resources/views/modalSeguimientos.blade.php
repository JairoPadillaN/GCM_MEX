<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
<!-- Este boton se le da clic automaticamente con el script de abajo para que abra el modal-->
<button type="button" class="" data-toggle="modal" data-target="#modalSeguimientos" id="activar">
</button>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="modalSeguimientos" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">

                <h3 class="modal-title" id="exampleModalLongTitle" align="center">Detalle del seguimiento</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="panel-body">

                <input type="hidden" value="{{$idUsuario}}">
                <input type="hidden" value="{{$idSegActividad}}">

            @if($cuantosSeg == 0)
                    <div class="alert alert-warning" role="alert" align="center">
                        <label for="">Sin seguimientos.</label>
                    </div>

            @else
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
                                    <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                                        target='_self'>
                                        <input type='hidden' value='{{$det->idDetSeg}}' name='idDetSeg' id='idDetSeg'>
                                        <input type='hidden' value='{{$det->idSegActividad}}' name='idSegActividad'
                                            id='idSegActividad'>
                                        <br><br>
                                        <button type="button" class="btn btn-sm btn-success archivos">
                                            <i class="fa fa-folder-open"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="archivos"></div>

            @endif
                <div class="modal-footer">
                    <!-- <button type="submit" class="btn btn-secondary" data-dismiss="modal">Guardar cambios</button> -->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <script>
    $("#activar").click();
    </script>


    <script type="text/javascript">
    $(function() {
        $('.archivos').click(function() {
            // alert("archivos");

            // var detSeg = $("#idDetSeg" ).val();
            // console.log(detSeg);
            $("#archivos").load('{{url('archivosSeg2')}}' + '?' + $(this).closest('form').serialize());
        });



    });
    </script>