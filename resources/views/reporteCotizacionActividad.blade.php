@if($cuantosCoti!=0)
<div class="alert alert-warning" role="alert">El servicio ya fue asignado</div>
@endif
<div id="reporteServicios">
    <div class="table-responsive">
    <div class="panel-heading">
        <center>
        <h3>Reporte de cotizaciones</h3>
        </center>
    </div>
        <table class="table table-striped table-bordered table-hover" id="dataTables-example" >
            <thead>
                <tr style=" background-color: #AED6F1;">
                    <th>Número de cotización</th>
                    <th>Fecha</th>
                    <th>Creada por</th>
                    <th>ITEMS</th>
                    <th>Monto</th>
                    <th>Estatus</th>
                    @if($botonDetalle=='activo')
                        <th>Eliminar</th>
                    @endif
                </tr>
            </thead>
            <tbody>
            
            @foreach($consultaCotizacion as $cotizacion)
                <tr>
                    <td>{{$cotizacion->numeroCotizacion}}</td>
                    <td>{{$cotizacion->fechaCotizacion}}</td>
                    <td>{{$cotizacion->usuario}}</td>
                    <td>{{$cotizacion->noEquipos}}</td>
                    <td>{{$cotizacion->montoMoneda}}</td>
                    <td>{{$cotizacion->estatus}}</td>
                    @if($botonDetalle=='activo')
                    <td>
                    <form action="">
                    @csrf
                        <input type="hidden" value="{{$cotizacion->idSegActividad}}" name="idSegBorrar">
                        <input type="hidden" value="{{$cotizacion->idEquipoActividad}}" name="idEquipoActBorrar">
                        <input type="hidden" value="{{$cotizacion->idCotizacion}}" name="idCotizacionBorrar">
                        
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