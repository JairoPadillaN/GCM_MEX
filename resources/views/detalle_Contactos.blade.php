<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
<br>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
        <tr style="background-color: #E8E8E8;">
                <th>
                    <font SIZE=2>Puesto</font>
                </th>
                <th>
                    <font SIZE=2>Contacto</font>
                </th>
                <th>
                    <font SIZE=2>Teléfono</font>
                </th>
                <th>
                    <font SIZE=2>Correo</font>
                </th>
                <th>
                    <font SIZE=2>Extensión</font>
                </th>
                <th>
                    <font SIZE=2>WhatsApp</font>
                </th>
                <th>
                    <font SIZE=2>Contacto Adicional</font>
                </th>
                <th>
                    <font SIZE=2>Opciones</font>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($contactos as $cont)
            <tr>
                <td>{{$cont->puesto}}</td>
                <td>{{$cont->nombreCont}}</td>
                <td>{{$cont->telefonoCont}}</td>
                <td>{{$cont->correoCont}}</td>
                <td>{{$cont->extension}}</td>
                <td>{{$cont->whatsapp}}</td>
                <td>{{$cont->contactoAdicional}}</td>
                <td align="center">
                    <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                        name='frmdo{{$cont->idContactoSuc}}' id='frmdo{{$cont->idContactoSuc}}' target='_self'>
                        <input type='hidden' value='{{$cont->idContactoSuc}}' name='idContactoSuc' id='idContactoSuc'>
                        <input type='hidden' value='{{$cont->idSucursal}}' name='idSucursal' id='idSucursal'>
                        <button type="button" class="btn btn-sm btn-danger borrarContacto">
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
    $('.borrarContacto').click(
        function() {
            // alert("borrar");
            $("#reporteCont").load('{{url('borrar_Cont')}}' + '?' + $(this).closest('form').serialize());
        }
    );
});
</script>