@if($cuantosContac!=0)
<div class="alert alert-warning" role="alert">El contacto ya fue asignado</div>
@endif
<div id="reporteContactos">
    <div class="table-responsive">
    <div class="panel-heading">
        <center>
        <h3>Reporte de contactos</h3>
        </center>
    </div>
        <table class="table table-striped table-bordered table-hover" id="dataTables-example" >
            <thead>
                <tr style=" background-color: #AED6F1;">
                    <th>Puesto</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Extensión</th>
                    <th>Whatsapp</th>
                    <th>Contacto Adicional</th>
                    @if($botonDetalle=='activo')
                    <th>Eliminar</th>
                    @endif
                </tr>
            </thead>
            <tbody>
            @foreach($consultaContactos as $contacto)
                <tr>
                    <td>{{$contacto->puesto}}</td>
                    <td>{{$contacto->nombreCont}}</td>
                    <td>{{$contacto->telefonoCont}}</td>
                    <td>{{$contacto->correoCont}}</td>
                    <td>{{$contacto->extension}}</td>
                    <td>{{$contacto->whatsapp}}</td>
                    <td>{{$contacto->contactoAdicional}}</td>
                    @if($botonDetalle=='activo')
                    <td>
                        <form action="">
                        @csrf                     
                            <input type="hidden" value="{{$contacto->idSegActividad}}" name="idSegBorrar">
                            <input type="hidden" value="{{$contacto->idEquipoActividad}}" name="idEquipoActBorrar">
                            <input type="hidden" value="{{$contacto->idContactoSuc}}" name="idContactoBorrar">
                            <button type="button" class="btn btn-sm btn-danger borrarContacto" style='width:40px; height: 35px;'>
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
        
    </div>
</div>
<script>
$(document).ready(function(){
    $('.borrarContacto').click(function(){
        $("#reporteContactos").load('{{url('borrarEquipoActividad')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
    });
});
</script>