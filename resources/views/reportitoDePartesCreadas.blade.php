<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
<div class="table-responsive">
    
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
            <tr style=" background-color: #C5EBFB;">
                <th>GCM ID PARTE</th>
                <th>Nombre</th>
                <th>Fecha salida</th>
                <th>Fecha llegada</th>
                <th>Taller</th>
                <th>Tipo de servicio</th>
                <th>Estatus</th>
                <th>
                    <center>Operaciones</center>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($consultaPartesCreadas as $cpc)
            <tr>
                <td>{{$cpc->GCMidParte}}</td>
                <td>{{$cpc->nombreParte}}</td>
                <td>{{$cpc->fechaSalida}}</td>
                <td>{{$cpc->fechaRecepcion}}</td>
                <td>{{$cpc->nombreTaller}}</td>
                <td>{{$cpc->prioridadRep}}</td>
                <td>{{$cpc->nombreEstatus}}</td>
                <td align="center">
                    <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                        name='frmdo{{$cpc->idParte}}' id='frmdo{{$cpc->idParte}}' target='_self'>

                        <input type="hidden" value="{{$cpc->idParte}}" name="idParteServicios" id='parServ'>
                        <button type="button" class="btn btn-sm btn-warning servicios">
                            <i class="glyphicon glyphicon-wrench" aria-hidden="true"></i>
                        </button>

                        <input type="hidden" value="{{$cpc->idParte}}" name="idParte" id='par'>
                        <button type="button" class="btn btn-sm btn-info editar">
                            <i class="ace-icon fa fa-pencil bigger"></i>
                        </button>
                        
                        <input type="hidden" value="{{$cpc->idParte}}" name="idParteBorrar" id='parB'>
                        <input type="hidden" value="{{$cpc->idEquipos}}" name="idEquipos" id='parB'>
                        <button type="button" class="btn btn-sm btn-danger borrar">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $("#agrega").click(function() {
        $("#crearParte").load('{{url('altaDividirParteAbajo')}}' + '?' + $(this).closest('form').serialize());
    });

    $(".editar").click(function() {
        $("#crearParte").load('{{url('editarParteAbajo')}}' + '?' + $(this).closest('form').serialize());
    });

    $(".borrar").click(function() {
        $("#reportitoDePartesCreadas").load('{{url('borrarParteCreada')}}' + '?' + $(this).closest('form').serialize());
    });

    $(".servicios").click(function() {
        $("#crearParte").load('{{url('serviciosParteCreada')}}' + '?' + $(this).closest('form').serialize());
    });
});
</script>