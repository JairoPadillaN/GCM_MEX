@if($cuantasRefacciones>=1)
<div style="height: 100%; background-color: rgba(255, 255, 255);overflow: scroll;" id="scrol">

    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
            <thead>
                <tr style="background-color: #EDE5A6;">
                    <th><font SIZE=1>Parte</font></th>
                    <th><font SIZE=1>Refacciones</font></th>
                    <th><font SIZE=1>Tipo de producto</font></th>
                    <th><font SIZE=1>Visible en cotización</font></th>
                    <th><font SIZE=1>Precio de venta</font></th>                    
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($refacciones as $ref)
                <tr>
                    <td style="text-align:center;"><font SIZE=1>{{$ref->GCMidParte}}</font></td>
                    <td style="text-align:center;"><font SIZE=1>{{$ref->codigo}} - {{$ref->nombreRefaccion}}</font></td>
                    @if($ref->tipoProducto == "Paquete")
                        <td style="text-align:center;"><font SIZE=1>{{$ref->nombrePaquete}}</font></td>
                    @else
                        <td style="text-align:center;"><font SIZE=1>{{$ref->tipoProducto}}</font></td>
                    @endif
                    <td style="text-align:center;"><font SIZE=1>{{$ref->apareceEnCoti}}</font></td>
                    @if($tipoMoneda == "MXN")
                        <td style="text-align:center;">
                            <font SIZE=1>
                                @if($ref->precioPesos != "" || $ref->precioPesos > 0) 
                                    MXN $ {{$ref->precioPesos}}
                                @else
                                    MXN $ 0.00
                                @endif
                            </font>
                        </td>                                    
                    @else
                        <td style="text-align:center;">
                            <font SIZE=1>                                            
                                @if($ref->precioDolar != "" || $ref->precioDolar > 0) 
                                    USD $ {{$ref->precioDolar}}
                                @else
                                    USD $ 0.00
                                @endif
                            </font>
                        </td>                                    
                    @endif                    
                    <td align="center">
                        <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                        <input type="hidden" value="{{$tipoMoneda}}" name="tipoMoneda">
                        <input type="hidden" value="{{$ref->idCotizacion}}" name="idCotizacionBorrar">
                        <input type="hidden" value="{{$ref->partida}}" name="partidaBorrar">
                        <input type="hidden" value="{{$ref->idRefaccionCotizacion}}" name="idRefaccionBorrar">
                            <button type="button" class="btn btn-sm btn-danger borrarRefaccion">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                </tr>                
                @endforeach
                <tr>
                    <td colspan="4" style="text-align:right">
                        <font SIZE=2>
                            <b>Total: </b>
                        </font>
                    </td>
                    <td colspan=3>
                        <font SIZE=2>
                            @if($tipoMoneda == "MXN")
                                <b> MXN $ <?php echo number_format ($sumaPrecios->precioPesos,2) ?> </b>
                            @else
                                <b> USD $ <?php echo number_format ($sumaPrecios->precioDolar,2) ?> </b>
                            @endif
                        </font>
                    </td>                    
                </tr>
            </tbody>
        </table>
    </div>
    @if($tipoMoneda == "MXN")
        <input type="hidden" name="total" id="total" value="{{$sumaPrecios->precioPesos}}">
    @else
        <input type="hidden" name="total" id="total" value="{{$sumaPrecios->precioDolar}}">
    @endif

</div>    
@else
    <div class="alert alert-danger" role="alert" width=20 align="center">Sin refacciones</div>
@endif

<script>
    $( document ).ready(function() {        
        var total = $("#total").val();        
        document.getElementById("montoEquipo").value = total;
    });

    $(".borrarRefaccion").click(function() {
        $("#reporteRefaccionesAsignadas").load('{{url('borrarRefaccionParteCotizacion')}}' + '?' + $(this).closest('form').serialize());
    });    
</script>

