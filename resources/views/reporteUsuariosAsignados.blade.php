<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
<br>
@if($cuantos == 0)
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr style="background-color: #E8E8E8;">
                                        <th width = "90%">
                                            <font SIZE=2>Nombre usuario</font>
                                        </th>
                                        <th width = "10%">
                                            <font SIZE=2>Opciones</font>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($usuariosAsignados as $uA)
                                    <tr>
                                        <td>{{$uA->usuario}}</td>
                                        <td align="center">
                                            <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                                                name='frmdo{{$uA->idACargo}}'
                                                id='frmdo{{$uA->idACargo}}' target='_self'>
                                                <input type='hidden' value='{{$uA->idACargo}}'
                                                    name='idACargo' id='idACargo'>
                                                <input type='hidden' value='{{$uA->idu}}'
                                                    name='idu' id='idu'>
                                                <button type="button" class="btn btn-sm btn-danger borrarUs">
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
                                        <th width = "90%">
                                            <font SIZE=2>Nombre usuario</font>
                                        </th>
                                        <th width = "10%">
                                            <font SIZE=2>Opciones</font>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($usuariosAsignados as $uA)
                                    <tr>
                                        <td>{{$uA->usuario}}</td>
                                        <td align="center">
                                            <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                                                name='frmdo{{$uA->idACargo}}'
                                                id='frmdo{{$uA->idACargo}}' target='_self'>
                                                <input type='hidden' value='{{$uA->idACargo}}'
                                                    name='idACargo' id='idACargo'>
                                                <input type='hidden' value='{{$uA->idu}}'
                                                    name='idu' id='idu'>
                                                <button type="button" class="btn btn-sm btn-danger borrarUs">
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
    $('.borrarUs').click(
        function() {
            // alert("borrar");
            $("#reporteUsuarios").load('{{url('borrarUsuariosAsignados')}}' + '?' + $(this).closest('form').serialize());
        });
});
</script>