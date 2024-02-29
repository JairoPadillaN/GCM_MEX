<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
@if($cuantasServicios < 1) 
    <div class="alert alert-warning" role="alert" align="center">
        <label for="">No se han agregado servicios a la cotización.</label>
        {{Form::hidden('validarCuantos',$cuantasServicios,['class' => 'validarCuantos'])}}
    </div>
@else
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
            <tr>
                <th colspan="6" style="text-align:right"><font SIZE=2>Total: {{$tipoMoneda}} ${{$suma}}</font></th>
                <th colspan="2"></th>
            </tr>
            <tr style="background-color: #C5EBFB;">
                <th><font SIZE=2>Servicio</font></th>
                <th><font SIZE=2>Detalle</font></th>
                <th><font SIZE=2>Tiempo de entrega</font></th>
                <th><font SIZE=2>Cantidad</font></th>
                <th style="width:350px;"><font SIZE=2>Costo unitario</font></th>
                <th style="width:350px;"><font SIZE=2>Costo total</font></th>
                <th><font SIZE=2>Notas</font></th>
                <th><font SIZE=2><center>Operaciones</center></font></th>
            </tr>
        </thead>
        <tbody>
            @foreach($serviciosEncotizacion as $sEc)
                <tr>                    
                    <td style="text-align:center;width:300px;">                    
                        {{$sEc->servicioTecnico}}
                    </td>
                    <td style="text-align:center;width:300px;">                    
                        {{$sEc->detalle}}
                    </td>
                    <td style="text-align:center;">
                        @if($sEc->semanasEntrega !="NA")
                            {{$sEc->semanasEntrega}}
                        @endif
                    </td>
                    <td style="text-align:center;">{{$sEc->cantidad}} {{$sEc->observacionCantidad}}</td>                    
                    <td style="text-align:center;">
                        <font SIZE=2>
                            @if($sEc->descuento !="")
                                <strike>{{$tipoMoneda}} $<?php echo number_format ($sEc->montoEquipo,2)?></strike>
                                <br>                                                   
                                {{$tipoMoneda}} $
                                <?php 
                                    //validando que no haga divicion entre cero y truene
                                    if ($sEc->cantidad >=1) {
                                        $descuentoUnitario = $sEc->montoFinanciamiento / $sEc->cantidad;
                                    }else{
                                        $descuentoUnitario = $sEc->montoFinanciamiento;
                                    }
                                    echo number_format ($descuentoUnitario,2);
                                ?>
                            @else
                                {{$tipoMoneda}} ${{$sEc->montoEquipo}}
                            @endif
                        </font>
                    </td>
                    <td style="text-align:center;">
                        <font SIZE=2>
                            @if($sEc->descuento !="")
                                <strike><?php $totalff = $sEc->cantidad * $sEc->montoEquipo; echo $sEc->tipoMoneda . ' $'.$totalff; ?></strike>
                                <br>
                                - @if($sEc->tipoDescuento == "porcentaje")
                                    {{$sEc->descuento}} % 
                                    @else 
                                    $ {{$sEc->descuento}}
                                    @endif
                                <br>{{$tipoMoneda}} ${{$sEc->montoFinanciamiento}}
                            @else
                                {{$tipoMoneda}} ${{$sEc->montoFinanciamiento}}
                            @endif                                            
                        </font>
                    </td>
                    <td>
                        {{$sEc->notas}}
                    </td>
                    <td align="center">
                        <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                            {{Form::hidden('numeroCotizacionB',$numeroCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                            {{Form::hidden('idSucursal',$idSucursal,['class' => 'form-control rounded-0', 'readonly'])}}
                            {{Form::hidden('tipoMonedaB',$tipoMoneda,['class' => 'form-control rounded-0', 'readonly'])}}
                            <input type="hidden" value="{{$sEc->idCotizacionServicios}}" name="idCotizacionServicios" id="idCotizacionServicios">
                            <input type="hidden" value="{{$sEc->idDetalleCotizacionServ}}" name="idDetalleCotizacionServ">
                            @if($estatusCotizacion == "En revisión")
                                <button value="{{$sEc->idDetalleCotizacionServ}}" class="modificarMontoPartidaCotizacionServicios btn btn-info btn-sm" type="button">
                                    <i class="fa fa-pencil bigger"></i>
                                </button>
                            @endif
                            <button type="button" class="btn btn-sm btn-danger borrarServicio">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6" style="text-align:right"><font SIZE=2><b>Total: {{$tipoMoneda}} ${{$suma}}</b></font></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
    {{Form::hidden('validarCuantos',$cuantasServicios,['class' => 'validarCuantos'])}}
    {{Form::hidden('idSucursal',$idSucursal,['class' => 'form-control rounded-0','id'=>'idSucursal', 'readonly'])}}
    <div id="cargarModal"></div>
@endif

<script>
    $(".borrarServicio").click(function() {
        var idSucursal = $('#idSucursal').val();         
        $("#reporteReparaciones").load('{{url('borrarServicio')}}' + '?' + $(this).closest('form').serialize());
        // $("#comboEquipos").load('{{url('comboEquiposCotizacion')}}' + '?r=' + Date.now() + '&idSucursal=' + idSucursal);
    });

    $(".modificarMontoPartidaCotizacionServicios").click(function (e) {
        var idDetalleCotizacionServ = $(this).val();
        var idCotizacionServicios=$('#idCotizacionServicios').val();            
        $('#cargarModal').load('{{url('modificarMontoPartidaCotizacionServicios')}}' + '?idCotizacionServicios='+idCotizacionServicios+'&idDetalleCotizacionServ='+idDetalleCotizacionServ);
        e.preventDefault(); 
    });
</script>