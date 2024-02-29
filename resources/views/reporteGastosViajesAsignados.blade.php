@if(count($consultaGastos)>0)
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
            <tr>
                <th style="width:90px;background-color: #C5EBFB;text-align:center">Folio de servicio</th>
                <th style="background-color: #C5EBFB;text-align:center">Cliente</th>
                <th style="background-color: #C5EBFB;text-align:center">sucursal</th>            
                <th style="background-color: #C5EBFB;text-align:center">Monto</th>
                <th style="background-color: #C5EBFB;text-align:center">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($consultaGastos as $consultaGastos)
            <tr>
                <td>{{$consultaGastos->idServicios}}</td>
                <td style="text-align:left">{{$consultaGastos->cliente}}</td>
                <td style="text-align:left">{{$consultaGastos->sucursal}}</td>
                <td>{{$consultaGastos->total}}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger eliminarAsignacion" value="{{$consultaGastos->id}}">
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-warning" role="alert" align="center">
        <label for="">Sin asignaciones</label>                            
    </div>
@endif

<script type="text/javascript">    
    $(".eliminarAsignacion").click(function() {
        $('.eliminarAsignacion').attr("disabled", true);
        $("#reporteServiciosAgregados").load('{{url('eliminarServicioAviaje')}}' + '?' +'idGasto='+ $(this).val());
    });
</script>