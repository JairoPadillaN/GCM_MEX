@if($cuantosFac!=0)
<div class="alert alert-warning" role="alert">El servicio ya fue asignado</div>
@endif
<div id="reporteServicios">
    <div class="table-responsive">
    <div class="panel-heading">
        <center>
        <h3>Reporte de servicios</h3>
        </center>
    </div>
        <table class="table table-striped table-bordered table-hover" id="dataTables-example" >
            <thead>
                <tr style=" background-color: #AED6F1;">
                    <th>Folio de servicio</th>
                    <th>Folio de factura</th>
                    <th>Fecha de servicio</th>
                    <th>Fecha de pago</th>
                    <th>Cliente</th>
                    <th>Sucursal</th>
                    <th>Estatus entrega</th>
                    <th>Estatus portal</th>
                    <th>Estatus pago</th>
                    <th>Cash flow</th>
                    @if($botonDetalle=='activo')
                        <th>Eliminar</th>
                    @endif
                </tr>
            </thead>
            <tbody>
            
            @foreach($consultaFacturas as $facturas)
                <tr>
                    <td>{{$facturas->idServicios}}</td>
                    <td>{{$facturas->numeroFactura}}</td>
                    <td>{{$facturas->fecha}}</td>
                    <td>{{$facturas->fechap}}</td>
                    <td>{{$facturas->cli}}</td>
                    <td>{{$facturas->suc}}</td>
                    <td>{{$facturas->estatusEntrega}}</td>
                    <td>{{$facturas->estatusPortal}}</td>
                    <td>{{$facturas->estatusPago}}</td>
                    <td>{{$facturas->cashFlow}}</td>
                    @if($botonDetalle=='activo')
                    <td>
                    <form action="">
                    @csrf
                                <input type="hidden" value="{{$facturas->idSegActividad}}" name="idSegBorrar">
                                <input type="hidden" value="{{$facturas->idEquipoActividad}}" name="idEquipoActBorrar">
                                <input type="hidden" value="{{$facturas->idFactura}}" name="idFacturaBorrar">
                                
                        <button type="button" class="btn btn-sm btn-danger borrar" style='width:40px; height: 35px;'>
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
    $('.borrar').click(function(){
        // var idFac=$("#idFac").val();
        // var idEquipoAct=$("#idEquipoAct").val();
        // alert(idEquipoAct);
        $("#reporteServicios").load('{{url('borrarEquipoActividad')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
    });
});
</script>