<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>

<br>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
            <tr style=" background-color: #C5EBFB;">
                <th>SKU</th>
                <th>Nombre</th>
                <th>Marca</th>
                <th>Tipo</th>
                <th>Costo Venta Pesos</th>
                <th>Costo Venta Dólares</th>
                <th>SKU en cotización</th>
                <th>
                    <center>Operaciones</center>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($agregado2 as $a2)
            <tr>
                <td>{{$a2->codigo}}</td>
                <td>{{$a2->nombreRefaccion}}</td>
                <td>{{$a2->marcaRefaccion}}</td>
                <td>{{$a2->nombreTipoRefaccion}}</td>
                <td style="text-align:right;">MXN ${{$a2->precioVentaPe}}</td>
                <td style="text-align:right;">USD ${{$a2->precioVentaDol}}</td>
                <td style="text-align:right;">{{$a2->apareceCoti}}</td>
                <td>
                    <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                        name='frmdo{{$a2->idDetallePaquete}}' id='frmdo{{$a2->idDetallePaquete}}' target='_self'>
                        <input type='hidden' value='{{$a2->idDetallePaquete}}' name='idDetallePaquete' id='idDetallePaquete'>
                        <input type='hidden' value='{{$a2->idPaquete}}' name='idPaquete' id='idPaquete'>
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
$(function() {
    $('.borrar').click(
        function() {
            // alert("borrar");
            $("#reporte").load('{{url('borrarPartePaq2')}}' + '?' + $(this).closest('form').serialize());
        }
    );
});
</script>