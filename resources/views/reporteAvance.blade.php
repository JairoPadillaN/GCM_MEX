<div class="panel panel-default" style="margin-top:-55px">
    <center>
        <h2>Avance de responsables asignados a la actividad</h2> <br><br>
    </center>
    <div class="panel-body"><div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
            <thead>
                <tr style="background-color: #93BEE6">
                    <th>Nombre de quien atiende</th>
                    <th>Cargo</th>
                    <th>¿Qué realiza?</th>
                    <th>Avance</th>
                    <th>Estatus de atención</th>
                    <th>Número de seguimientos</th>
                    <th>Operaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($seguimientoA as $seg)
                <tr>
                    <td>{{$seg->actor}}</td>
                    <td>{{$seg->tipo}}</td>
                    <td>{{$seg->queRealiza}}</td>
                    
                    @if($seg->porcentaje == '')
                    <td>
                    <div class="progress">
                    <div class="progress-bar progress-bar-danger" role="progressbar"
                            aria-valuemin="0" aria-valuemax="0"
                        style="width: 100%">0% completado
                        <span class="sr-only"></span>
                    </div>
                    </div>
                    </td>
                    @endif

                    @if($seg->porcentaje > 1 && $seg->porcentaje < 11)
                    <td>
                    <div class="progress">
                    <div class="progress-bar progress-bar-warning" role="progressbar"
                            aria-valuemin="0" aria-valuemax="0"
                        style="width: 10%">{{$seg->porcentaje}}% completado
                        <span class="sr-only"></span>
                    </div>
                    </div>
                    </td>
                    @endif

                    @if($seg->porcentaje > 11 && $seg->porcentaje < 21)
                    <td>
                    <div class="progress">
                    <div class="progress-bar progress-bar-warning" role="progressbar"
                            aria-valuemin="0" aria-valuemax="0"
                        style="width: 20%">{{$seg->porcentaje}}% completado
                        <span class="sr-only"></span>
                    </div>
                    </div>
                    </td>
                    @endif

                    @if($seg->porcentaje > 21 && $seg->porcentaje < 31)
                    <td>
                    <div class="progress">
                    <div class="progress-bar progress-bar-warning" role="progressbar"
                            aria-valuemin="0" aria-valuemax="0"
                        style="width: 30%">{{$seg->porcentaje}}% completado
                        <span class="sr-only"></span>
                    </div>
                    </div>
                    </td>
                    @endif

                    @if($seg->porcentaje > 31 && $seg->porcentaje < 41)
                    <td>
                    <div class="progress">
                    <div class="progress-bar progress-bar-warning" role="progressbar"
                            aria-valuemin="0" aria-valuemax="0"
                        style="width: 40%">{{$seg->porcentaje}}% completado
                        <span class="sr-only"></span>
                    </div>
                    </div>
                    </td>
                    @endif

                    @if($seg->porcentaje > 41 && $seg->porcentaje < 51)
                    <td>
                    <div class="progress">
                    <div class="progress-bar progress-bar-warning" role="progressbar"
                            aria-valuemin="0" aria-valuemax="0"
                        style="width: 50%">{{$seg->porcentaje}}% completado
                        <span class="sr-only"></span>
                    </div>
                    </div>
                    </td>
                    @endif

                    @if($seg->porcentaje > 51 && $seg->porcentaje < 61)
                    <td>
                    <div class="progress">
                    <div class="progress-bar progress-bar-warning" role="progressbar"
                            aria-valuemin="0" aria-valuemax="0"
                        style="width: 60%">{{$seg->porcentaje}}% completado
                        <span class="sr-only"></span>
                    </div>
                    </div>
                    </td>
                    @endif

                    @if($seg->porcentaje > 61 && $seg->porcentaje < 71)
                    <td>
                    <div class="progress">
                    <div class="progress-bar progress-bar-warning" role="progressbar"
                            aria-valuemin="0" aria-valuemax="0"
                        style="width: 70%">{{$seg->porcentaje}}% completado
                        <span class="sr-only"></span>
                    </div>
                    </div>
                    </td>
                    @endif

                    @if($seg->porcentaje > 71 && $seg->porcentaje < 81)
                    <td>
                    <div class="progress">
                    <div class="progress-bar progress-bar-warning" role="progressbar"
                            aria-valuemin="0" aria-valuemax="0"
                        style="width: 80%">{{$seg->porcentaje}}% completado
                        <span class="sr-only"></span>
                    </div>
                    </div>
                    </td>
                    @endif

                    @if($seg->porcentaje > 81 && $seg->porcentaje < 91)
                    <td>
                    <div class="progress">
                    <div class="progress-bar progress-bar-warning" role="progressbar"
                            aria-valuemin="0" aria-valuemax="0"
                        style="width: 90%">{{$seg->porcentaje}}% completado
                        <span class="sr-only"></span>
                    </div>
                    </div>
                    </td>
                    @endif

                    @if($seg->porcentaje > 91 && $seg->porcentaje < 100)
                    <td>
                    <div class="progress">
                    <div class="progress-bar progress-bar-warning" role="progressbar"
                            aria-valuemin="0" aria-valuemax="0"
                        style="width: 90%">{{$seg->porcentaje}}% completado
                        <span class="sr-only"></span>
                    </div>
                    </div>
                    </td>
                    @endif

                    @if($seg->porcentaje == 100)
                    <td>
                    <div class="progress">
                    <div class="progress-bar progress-bar-success" role="progressbar"
                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                        style="width: 100%">100% completado
                        <span class="sr-only"></span>
                    </div>
                    </div>
                    </td>
                    @endif
                    <td>{{$seg->statusactividad}}</td>
                    <td>{{$seg->numeroseguimientos}}</td>
                    
                    
                    <td align="center">
                                <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                                    <input type='hidden' value='{{$seg->idSegActividad}}' name='idSegActividad' id='idSegActividad'>
                                    <input type='hidden' value='{{$seg->idu}}' name='idu' id='idu'>
                                    <!-- <button type="button" class="btn btn-sm btn-danger borrar">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </button>
                                    <br><br> -->
                                    <button type="button" class="btn btn-sm btn-success info">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    </div>
                                </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="infoUsuario"></div>
    </div>
</div>



<script type="text/javascript">
$(document).ready(function() {
    
    $('.info').click(function(){
        // alert("hola");
        // var detSeg = $("#idDetSeg" ).val();
        // console.log(detSeg);
        $("#infoUsuario").load('{{url('infoUsuarios')}}' + '?' + $(this).closest('form').serialize());
    
});
});
</script>