<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
<br>
@if($cuantos == 0)
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
        <tr style="background-color: #E8E8E8;">
                <th>
                    <font SIZE=2>Nombre usuario</font>
                </th>
                <th>
                    <font SIZE=2>Tipo de usuario</font>
                </th>
                 <th>
                    <font SIZE=2>¿Qué realizará</font>
                </th>
                <th>
                    <font SIZE=2>Opciones</font>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($seguimiento as $sg)
                <tr>
                    <td>{{$sg->nombreUsuario}} {{$sg->aPaterno}} {{$sg->aMaterno}}</td>
                    <td>{{$sg->tipo}}</td>
                    <td>{{$sg->queRealiza}}</td>
                    
                    <td align="center">
                        <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                            name='frmdo{{$sg->idAsigSeguimiento}}' id='frmdo{{$sg->idAsigSeguimiento}}' target='_self'>
                            <input type='hidden' value='{{$sg->idAsigSeguimiento}}' name='idAsigSeguimiento' id='idAsigSeguimiento'>
                            <input type='hidden' value='{{$sg->idSegActividad}}' name='idSegActividad' id='idSegActividad'>
                            <button type="button" class="btn btn-sm btn-danger borrarSeg">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@else
<br>
<div class="alert alert-danger" role="alert">
    <center>Error: El usuario ya ha sido asignado a esta actividad.</center>
</div>
<br>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
        <tr style="background-color: #E8E8E8;">
                <th>
                    <font SIZE=2>Nombre usuario</font>
                </th>
                <th>
                    <font SIZE=2>Tipo de usuario</font>
                </th>
                 <th>
                    <font SIZE=2>¿Qué realizará</font>
                </th>
                <th>
                    <font SIZE=2>Opciones</font>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($seguimiento as $sg)
                <tr>
                    <td>{{$sg->nombreUsuario}} {{$sg->aPaterno}} {{$sg->aMaterno}}</td>
                    <td>{{$sg->tipo}}</td>
                    <td>{{$sg->queRealiza}}</td>
                    
                    <td align="center">
                        <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                            name='frmdo{{$sg->idAsigSeguimiento}}' id='frmdo{{$sg->idAsigSeguimiento}}' target='_self'>
                            <input type='hidden' value='{{$sg->idAsigSeguimiento}}' name='idAsigSeguimiento' id='idAsigSeguimiento'>
                            <input type='hidden' value='{{$sg->idSegActividad}}' name='idSegActividad' id='idSegActividad'>
                            <button type="button" class="btn btn-sm btn-danger borrarSeg">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<script type="text/javascript">
$(function() {
    $('.borrarSeg').click(
        function() {
            // alert("borrar");
            $("#usuariosSeg").load('{{url('borrarSeg')}}' + '?' + $(this).closest('form').serialize());
        }
    );
});
</script>