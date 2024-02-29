<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
<br>

<div class="table-responsive">
<table class="table table-striped table-bordered table-hover" id="dataTables-example">
<thead>
                                    <tr style="background-color: #E8E8E8;">
                                        <th width = "90%">
                                            <font SIZE=2>Proveedor</font>
                                        </th>
                                        <th width = "90%">
                                            <font SIZE=2>Banco/Tipo</font>
                                        </th>
                                        <th width = "90%">
                                            <font SIZE=2>Número de cuenta</font>
                                        </th>
                                        <th width = "90%">
                                            <font SIZE=2>Clabe interbancaria/Swift Code</font>
                                        </th>
                                        <th width = "90%">
                                            <font SIZE=2>Tipo de moneda</font>
                                        </th>
                                        <th width = "90%">
                                            <font SIZE=2>Sucursal/Institucion No</font>
                                        </th>
                                        <th width = "90%">
                                            <font SIZE=2>Dirección</font>
                                        </th>
                                        <th width = "90%">
                                            <font SIZE=2>Observaciones</font>
                                        </th>
                                        <th width = "10%">
                                            <font SIZE=2>Opciones</font>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($cuentasagregadas as $ca)
                                    <tr>
                                        <td>{{$ca->nombreProv}}</td>
                                        <td>{{$ca->bancoProv}}</td>
                                        <td>{{$ca->numeroCuentaProv}}</td>
                                        <td>{{$ca->clabeInterProv}}</td>
                                        <td>{{$ca->tipoMonedaProv}}</td>
                                        <td>{{$ca->sucursal}}</td>
                                        <td>{{$ca->direccion}}</td>
                                        <td>{{$ca->observaciones}}</td>
                                        <td align="center">
                                            <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                                                name='frmdo{{$ca->idCuentaProv}}' id='frmdo{{$ca->idCuentaProv}}' target='_self'>
                                                <input type='hidden' value='{{$ca->idCuentaProv}}' name='idCuentaProv' id='idCuentaProv'>
                                                <input type='hidden' value='{{$ca->idProveedor}}' name='idProveedor' id='idProveedor'>
                                                <button type="button" class="btn btn-sm btn-danger borrarCu">
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
$(function() {
    $('.borrarCu').click(
        function() {
            // alert("borrar");
            $("#reportecuentas").load('{{url('borrarCuenta')}}' + '?' + $(this).closest('form').serialize());
        });
});
</script>