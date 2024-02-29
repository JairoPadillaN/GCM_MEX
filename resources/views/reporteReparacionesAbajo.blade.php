<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
@if($cuantasReparaciones < 1) 
    <div class="alert alert-warning" role="alert" align="center">
        <label for="">No se han agregado equipos a cotización.</label>
        {{Form::text('validarCuantos',$cuantasReparaciones,['class' => 'validarCuantos'])}}
        <input type="hidden" name="" id="partidaReporte" value="{{$partidaSiguiente}}">
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
            <thead>
                <tr>
                    <th colspan="9" style="text-align:right"><font SIZE=2>Total: {{$tipoMoneda}} ${{$suma}}</font></th>
                    <th colspan="2"></th>
                </tr>
                <tr style="background-color: #C5EBFB;">
                    <th><font SIZE=2>Tipo de equipo</font></th>
                    <th><font SIZE=2>SKU / Descripción</font></th>
                    <th><font SIZE=2>Equipo</font></th>
                    <th><font SIZE=2>Modelo</font></th>
                    <th><font SIZE=2>GCMid Parte</font></th>
                    <th><font SIZE=2>Tiempo de entrega</font></th>
                    <th><font SIZE=2>Piezas</font></th>
                    <th style="width:400px;"><font SIZE=2>Costo unitario</font></th>
                    <th style="width:400px;"><font SIZE=2>Costo total</font></th>
                    <th><font SIZE=2>Notas</font></th>
                    <th><font SIZE=2><center>Operaciones</center></font></th>
                </tr>
            </thead>
            <tbody>
                @foreach($equiposNuevosYreparados as $equiposNYp)
                    <tr>
                        <td style="text-align:center;width:100px;">                    
                            <font SIZE=2>
                                @if($equiposNYp->tipoCotizacion == "reparacion")
                                    Reparación
                                @elseif($equiposNYp->tipoCotizacion == "nuevo")
                                    Nuevo
                                @else
                                    Refurbished
                                @endif
                            </font>
                        </td>
                        <td style="text-align:center;width:200px;">                    
                            <font SIZE=2>
                                {{$equiposNYp->descripcion}}
                            </font>
                        </td>
                        @if($equiposNYp->tipoCotizacion == "reparacion")
                            <td style="text-align:center;width:200px;">                    
                                <font SIZE=2>
                                    @foreach($datosEquipoCotizado as $dEc)                
                                        @if($equiposNYp->idEquipos == $dEc->idEquipos)
                                            {{$dEc->equipo}}
                                        @endif                
                                    @endforeach                    
                                </font>
                            </td>
                            <td style="text-align:center;width:200px;">                    
                                <font SIZE=2>
                                    {{$equiposNYp->modelo}}
                                </font>
                            </td> 
                            @foreach($equiposReparados as $equipoR)
                                @if($equiposNYp->idEquipos == $equipoR->idEquipos)
                                    <td style="text-align:center;width:200px;">
                                        <font SIZE=2 color="#2F7DA7">
                                            <button value="{{$equiposNYp->partida}}" class="verDetallePartida" style="background-color: Transparent;background-repeat:no-repeat;border: none;cursor:pointer;overflow: hidden;">
                                                {{$equipoR->GCMid}} - {{$equipoR->complementoGCMid}}
                                            </button>
                                        </font>
                                    </td>
                                    @break
                                @endif
                            @endforeach
                        @else
                            <td style="text-align:center;width:200px;">
                                <font SIZE=2>
                                    @foreach($datosEquiposNuevos as $dEn)                
                                        @if($equiposNYp->idPartesVenta == $dEn->idPartesVenta)
                                            {{$dEn->nombreRefaccion}}
                                            @break;
                                        @endif                
                                    @endforeach 
                                </font>
                            </td>
                            <td style="text-align:center;width:200px;">                    
                                <font SIZE=2>
                                    {{$equiposNYp->modelo}}
                                </font>
                            </td>
                            <td style="text-align:center;">
                                <font SIZE=2>
                                    @foreach($datosEquiposNuevos as $dEn)                
                                        @if($equiposNYp->idPartesVenta == $dEn->idPartesVenta)
                                            {{$dEn->numeroParte}}
                                            @break;
                                        @endif                
                                    @endforeach
                                </font>
                            </td>
                        @endif
                        <td style="text-align:center;"><font SIZE=2>{{$equiposNYp->semanasEntrega}}</font></td>
                        <td style="text-align:center;"><font SIZE=2>{{$equiposNYp->piezas}}</font></td>
                        <td style="text-align:center;width:350px;">
                            <font SIZE=2>
                                @if($equiposNYp->descuento !="")
                                    <strike>{{$tipoMoneda}} $<?php echo number_format ($equiposNYp->montoEquipoInicial,2)?></strike>
                                    <br>
                                    {{$tipoMoneda}} $
                                    <?php 
                                        //validando que no haga divicion entre cero y truene
                                        if ($equiposNYp->piezas >=1) {
                                            $descuentoUnitario = $equiposNYp->montoFinanciamiento / $equiposNYp->piezas;
                                        }else{
                                            $descuentoUnitario = $equiposNYp->montoFinanciamiento;
                                        }
                                        echo number_format ($descuentoUnitario,2);
                                    ?>
                                @else
                                    {{$tipoMoneda}} $<?php echo number_format ($equiposNYp->montoEquipo,2)?>
                                @endif
                            </font>
                        </td>
                        <td style="text-align:center; width:350px;">
                            <font SIZE=2>
                                @if($equiposNYp->descuento !="")
                                    <strike>{{$tipoMoneda}} $ <?php $totalff = $equiposNYp->montoEquipo * $equiposNYp->piezas; echo number_format ($totalff,2)?></strike>
                                    <br>
                                    - @if($equiposNYp->tipoDescuento == "porcentaje")
                                        {{$equiposNYp->descuento}}% 
                                    @else 
                                        ${{$equiposNYp->descuento}}
                                    @endif                                                    
                                    <br> {{$tipoMoneda}} $<?php echo number_format ($equiposNYp->montoFinanciamiento,2)?>
                                @else
                                    {{$tipoMoneda}} $<?php echo number_format ($equiposNYp->montoFinanciamiento,2)?>
                                @endif
                            </font>
                        </td>    
                        <td>
                            <font SIZE=2>
                                {{$equiposNYp->notas}}
                            </font>
                        </td>
                        <td align="center">
                            <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                                {{Form::hidden('numeroCotizacionB',$numeroCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                                {{Form::hidden('tipoCotizacionB',$equiposNYp->tipoCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                                {{Form::hidden('idSucursal',$idSucursal,['class' => 'form-control rounded-0', 'readonly'])}}
                                {{Form::hidden('idEquiposB',$equiposNYp->idEquipos,['class' => 'form-control rounded-0', 'readonly'])}}
                                {{Form::hidden('tipoMonedaB',$tipoMoneda,['class' => 'form-control rounded-0','id'=>'tipoMoneda','readonly'])}}
                                {{Form::hidden('partidaBorrar',$equiposNYp->partida,['class' => 'form-control rounded-0', 'readonly'])}}
                                <input type="hidden" value="{{$equiposNYp->idCotizacion}}" name="idCotizacionB" id="idCotizacion">
                                <input type="hidden" value="{{$equiposNYp->idRepCotizacion}}" name="idRepCotizacion">
                                @if($estatusCotizacion == "En revisión")
                                    <button value="{{$equiposNYp->partida}}" class="modificarMontoPartidaCotizacion btn btn-info btn-sm" type="button">
                                        <i class="fa fa-pencil bigger"></i>
                                    </button>
                                @endif
                                <button type="button" class="btn btn-sm btn-danger borrarReparacion">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="9" style="text-align:right"><font SIZE=2><b>Total: {{$tipoMoneda}} ${{$suma}}</b></font></td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
    </div>
    {{Form::hidden('validarCuantos',$cuantasReparaciones,['class' => 'validarCuantos'])}}
    {{Form::hidden('idSucursal',$idSucursal,['class' => 'form-control rounded-0','id'=>'idSucursal', 'readonly'])}}
    <input type="hidden" name="" id="partidaReporte" value="{{$partidaSiguiente}}">
    <div id="cargarModal"></div>
@endif

<script>
    $( document ).ready(function() {        
        var partidaReporte = $("#partidaReporte").val();        
        document.getElementById("partida").value = partidaReporte;
    })
    $(".borrarReparacion").click(function() {
        $("#reporteReparaciones").load('{{url('borrarReparacion')}}' + '?' + $(this).closest('form').serialize());
        $("#reporteRefaccionesAsignadas").empty();
        // $("#divPartida").load('{{url('generarPartida')}}' + '?' + $(this).closest('form').serialize());                 
        // $("#comboEquipos").load('{{url('comboEquiposCotizacion')}}' + '?r=' + Date.now() + '&idSucursal=' + idSucursal);
    });

    $(".verDetallePartida").click(function (e) {
        var partida = $(this).val();
        var idCotizacion=$('#idCotizacion').val();
        var tipoMoneda=$('#tipoMoneda').val();
        $('#cargarModal').load('{{url('detallePartidasCotizacion')}}' + '?idCotizacion='+idCotizacion+'&partida='+partida+'&tipoMoneda='+tipoMoneda);
        e.preventDefault();
    });

    $(".modificarMontoPartidaCotizacion").click(function (e) {
        var partida = $(this).val();
        var idCotizacion=$('#idCotizacion').val();    
        var tipoMoneda=$('#tipoMoneda').val();
        $('#cargarModal').load('{{url('modificarMontoPartidaCotizacion')}}' + '?idCotizacion='+idCotizacion+'&partida='+partida+'&tipoMoneda='+tipoMoneda);
        e.preventDefault(); 
    });
</script>