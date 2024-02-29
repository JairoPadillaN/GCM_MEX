<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
<br>
<div class="row">
    <div class="col-xs-6 col-md-4">
       Cliente:{{Form::text('nombre',($consultaD->razonSocial),['class' => 'form-control'])}}
    </div>
    <div class="col-xs-6 col-md-4">
        RFC:{{Form::text('nombre',($consultaD->rfc),['class' => 'form-control'])}}
    </div>
    <div class="col-xs-6 col-md-4">
        Contacto:{{Form::text('nombre',($consultaD->contacto),['class' => 'form-control'])}}
    </div>
</div>
<br>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Puesto</th>
                <th>Usuario</th>
                <!-- <th>Porcentaje</th> -->
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($consultaFactura as $cf)
            <tr>
                <td class="sorting_1">{{$cf->numeroFactura}}</td>
                <td class="sorting_1">{{$cf->tipo}}</td>
                <td class="sorting_1">{{$cf->nombreUsuario}} {{$cf->aPaterno}} {{$cf->aMaterno}}</td>
                <!-- <td class="sorting_1">{{$cf->porcentaje}}%</td>	 -->
                <td class="sorting_1">
                    <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                        name='frmdo{{$cf->idAsigDet}}' id='frmdo{{$cf->idAsigDet}}' target='_self'>
                        <input type='hidden' value='{{$cf->idAsigDet}}' name='idAsigDet' id='idAsigDet'>
                        <input type='hidden' value='{{$cf->idFactura}}' name='idFactura' id='idFactura'>
                        <input type='button' name='borrar' class='borrar' id='borrar' value='Borrar' />
                    </form>
                </td>
            </tr>
            @endforeach
            <!-- <tr>
        <td colspan=4 >Total</td>
        @if($resultado2->total <=100)
        <td align='right' id='total'>{{$resultado2->total}}%</td>
        @else
        <td align='right' id='total' style="background-color: brown; color: #FFFFFF;">{{$resultado2->total}}%</td>
        <input type="hidden" id="prueba" value="{{$resultado2->total}}">
        @endif
        <input type="hidden" id='validtext' value="{{$resultado2->total}}">
      </tr> -->
        </tbody>
    </table>
</div>

<script type="text/javascript">
$(function() {
    $('.borrar').click(
        function() {
            //var llega = $("#porcentaje").val();
            //porcentajes = parseInt(porcentajes) - parseInt(llega)
            //alert ('hai');
            formulario = this.form;
            $("#carrito").load('{{url('borraAsignacion')}}' + '?' + $(this).closest('form').serialize());
        }
    );
});
</script>