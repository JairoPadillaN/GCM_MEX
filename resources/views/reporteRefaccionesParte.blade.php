<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
@if($cuantasRefaccionesYaHay < 1) <div class="alert alert-warning" role="alert" align="center">
    <label for="">No se le han asignado servicios de reparación a esta pieza.</label>
    </div>
    @else
    @if($cuantos==0)
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
            <thead>
                <tr style=" background-color: #C5EBFB;">
                    <th>Refacción</th>
                    <th>Tipo de refacción</th>
                    <th>Tipo de producto</th>
                    <th>Código</th>
                    <th>Precio Venta (dolares)</th>
                    <th>Cotización</th>
                    <th>
                        <center>Operaciones</center>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($consultaRefaccionesAsignados as $cra)
                <tr>
                    <td>{{$cra->codigo}}-{{$cra->nombreRefaccion}}</td>
                    <td>{{$cra->nombreTipoRefaccion}}</td>
                    @if($cra->tipoProducto == 'Paquete')
                        <td>{{$cra->tipoProducto}}-{{$cra->nombrePaquete}}</td> 
                    @else
                        <td>{{$cra->tipoProducto}}</td> 
                    @endif   
                    <td>{{$cra->codigo}}</td>
                    <td>USD $ {{$cra->precioVentaDol}}</td>
                    <td>{{$cra->apareceEnCoti}}</td>
                    <td align="center">
                        <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                            <input type="hidden" value="{{$cra->idParte}}" name="idParte">
                            <input type="hidden" value="{{$cra->idRefaccRepParte}}" name="idRefaccionBorrar">
                            <button type="button" class="btn btn-sm btn-danger borrarRefaccion">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="6" style="text-align:right;">Total:</td>
                    <td style="text-align:right;">${{$consultaSumaRef->total2}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @else
    <div class="alert alert-danger" role="alert">
        <center>Error: Este servicio de reparación ya ha sido asignado a esta pieza.</center>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
            <thead>
                <tr style=" background-color: #C5EBFB;">
                    <th>Refacción</th>
                    <th>Tipo de refacción</th>
                    <th>Tipo de producto</th>
                    <th>Código</th>
                    <th>Precio Venta (dolares)</th>
                    <th>Cotización</th>
                    <th>
                        <center>Operaciones</center>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($consultaRefaccionesAsignados as $cra)
                <tr>
                    <td>{{$cra->codigo}}-{{$cra->nombreRefaccion}}</td>
                    <td>{{$cra->nombreTipoRefaccion}}</td>
                    @if($cra->tipoProducto == 'Paquete')
                        <td>{{$cra->tipoProducto}}-{{$cra->nombrePaquete}}</td> 
                        @else
                        <td>{{$cra->tipoProducto}}</td> 
                    @endif   
                    <td>{{$cra->codigo}}</td>
                    <td>USD $ {{$cra->precioVentaDol}}</td>
                    <td>{{$cra->apareceEnCoti}}</td>
                    <td align="center">
                        <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                            <input type="hidden" value="{{$cra->idParte}}" name="idParte">
                            <input type="hidden" value="{{$cra->idRefaccRepParte}}" name="idRefaccionBorrar">
                            <button type="button" class="btn btn-sm btn-danger borrarRefaccion">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="6" style="text-align:right;">Total:</td>
                    <td style="text-align:right;">${{$consultaSumaRef->total2}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif
    @endif

    <script type="text/javascript">
    $(document).ready(function() {
        $(".borrarRefaccion").click(function() {
            $("#reporteRefaccionesAsignadas").load('{{url('borrarRefaccionParte')}}' + '?' + $(this).closest('form').serialize());
        });
    });
    </script>