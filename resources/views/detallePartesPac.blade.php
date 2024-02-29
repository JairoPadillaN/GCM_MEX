<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
@if($cuantos == 0)
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
            @foreach($agregado as $a)
            <tr>
                <td>{{$a->codigo}}</td>
                <td>{{$a->nombreRefaccion}}</td>
                <td>{{$a->marcaRefaccion}}</td>
                <td>{{$a->nombreTipoRefaccion}}</td>
                <td style="text-align:right;">MXN ${{$a->precioVentaPe}}</td>
                <td style="text-align:right;">USD ${{$a->precioVentaDol}}</td>
                <td style="text-align:right;">{{$a->apareceCoti}}</td>
                <td>
                    <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                        name='frmdo{{$a->idDetallePaquete}}' id='frmdo{{$a->idDetallePaquete}}' target='_self'>
                        <input type='hidden' value='{{$a->idDetallePaquete}}' name='idDetallePaquete' id='idDetallePaquete'>
                        <input type='hidden' value='{{$a->idPaquete}}' name='idPaquete' id='idPaquete'>
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

@else
<br>
<div class="alert alert-danger" role="alert">
        <center>Error: Ya ha sido asignado a este paquete.</center>
</div>

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
            @foreach($agregado as $a)
            <tr>
                <td>{{$a->numeroParte}}</td>
                <td>{{$a->nombrePaquete}}</td>
                <td>{{$a->marcaRefaccion}}</td>
                <td>{{$a->nombreTipoRefaccion}}</td>
                <td style="text-align:right;">MXN ${{$a->precioVentaPe}}</td>
                <td style="text-align:right;">USD ${{$a->precioVentaDol}}</td>
                <td style="text-align:right;">{{$a->apareceCoti}}</td>
                <td>
                    <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                        name='frmdo{{$a->idDetallePaquete}}' id='frmdo{{$a->idDetallePaquete}}' target='_self'>
                        <input type='hidden' value='{{$a->idDetallePaquete}}' name='idDetallePaquete' id='idDetallePaquete'>
                        <input type='hidden' value='{{$a->idPaquete}}' name='idPaquete' id='idPaquete'>
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
@endif




<script type="text/javascript">
$(function() {
    $('.borrar').click(
        function() {
            // alert("borrar");
            $("#reportePac").load('{{url('borrarPartePaq')}}' + '?' + $(this).closest('form').serialize());
        }
    );
});
</script>