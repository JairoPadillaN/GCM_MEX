<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
@if($cuantasYaHay < 1) <div class="alert alert-warning" role="alert" align="center">
    <label for="">No se le han asignado servicios de reparación a esta pieza.</label>
    </div>
    @else
    @if($cuantos==0)
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
            <thead>
                <tr style=" background-color: #C5EBFB;">
                    <th>Alcance</th>
                    <th>Nombre del servicio</th>
                    <th>Precio Venta (Dólares)</th>
                    <th>Cotizacion</th>
                    <th>
                        <center>Operaciones</center>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($consultaServiciosAsignados as $csa)
                <tr>
                    <td>{{$csa->alcance}}</td>
                    <td>{{$csa->nombreServTaller}}</td>
                    <td>USD ${{$csa->precioDolar}}</td>
                    <td>{{$csa->tieneCotizacion}}</td>
                    <td align="center">
                        <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                            <input type="hidden" value="{{$csa->idParte}}" name="idParte">
                            <input type="hidden" value="{{$csa->idServReparacionParte}}" name="idServicioBorrar">
                            <button type="button" class="btn btn-sm btn-danger borrarServicio">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="4" style="text-align:right;">Total:</td>
                    <td style="align:right;">${{$consultaSuma->total}}</td>
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
                    <th>Alcance</th>
                    <th>Nombre del servicio</th>
                    <th>Cotizacion</th>
                    <th>
                        <center>Operaciones</center>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($consultaServiciosAsignados as $csa)
                <tr>
                    <td>{{$csa->alcance}}</td>
                    <td>{{$csa->nombreServTaller}}</td>
                    <td>{{$csa->tieneCotizacion}}</td>
                    <td align="center">
                        <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                            <input type="hidden" value="{{$csa->idParte}}" name="idParte">
                            <input type="hidden" value="{{$csa->idServReparacionParte}}" name="idServicioBorrar">
                            <button type="button" class="btn btn-sm btn-danger borrarServicio">
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
    @endif

    <script type="text/javascript">
    $(document).ready(function() {
        $(".borrarServicio").click(function() {
            $("#reporteServiciosAsignados").load('{{url('borrarServicioReparacionParte')}}' + '?' + $(this).closest('form').serialize());
        });
    });
    </script>