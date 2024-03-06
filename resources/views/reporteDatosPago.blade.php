<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
<br>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover" id="dataTables-example" style="width:90%"
                                align="center">
                                <thead>
                                    <tr style=" background-color: #78e08f;">
                                        <th style="width: 80px;">Número de factura</th>
                                        <th style="width: 80px;">Fecha de factura</th>   
                                        <th style="width: 80px;">Tipo de cambio facturado</th>
                                        <th style="width: 80px;">Saldo facturado</th> 
                                        <th style="width: 80px;">Subtotal facturado</th>
                                        <th style="width: 80px;">IVA facturado</th>
                                        <th style="width: 80px;">Monto facturado</th>
                                        <th style="width: 80px;">Tipo de cambio pagado</th> 
                                        <th style="width: 80px;">Saldo real</th> 
                                        <th style="width: 80px;">Subtotal real</th>
                                        <th style="width: 80px;">IVA real</th>
                                        <th style="width: 80px;">Monto real</th>
                                        <th style="width: 80px;">Diferencia cambiaría</th>
                                        <th style="width: 80px;">Pagado</th>
                                        <th style="width: 80px;">Fecha</th>
                                        <th style="width: 200px;">Observaciones</th>
                                        <th style="width: 80px;">
                                            <center>Opciones</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($consultaDatosPago as $dp)
                                    <tr>
                                        <td style="text-align: right;"> {{$dp->numeroFacturaDP}}</td>
                                            @if($dp->fechafactura)
                                        <td>{{ $dp->fechafactura->format('d/m/Y') }}</td>
                                            @else
                                        <td>Sin fecha de factura</td>
                                            @endif
                                        <td style="text-align: right;">$ {{ number_format($dp->tipoCambioFac, 2 ) }}</td>
                                        <td style="text-align: right;">$ {{ number_format($dp->saldoFac, 2 ) }}</td>
                                        <td style="text-align: right;">$ {{ number_format($dp->subtotalFac, 2) }}</td>
                                        <td style="text-align: right;">$ {{ number_format($dp->ivaFac, 2) }}</td>
                                        <td style="text-align: right;">$ {{ number_format($dp->montoPesosDP, 2) }}</td>
                                        <td style="text-align: right;">$ {{ number_format($dp->tipoCambioPagado, 2) }}</td>
                                        <td style="text-align: right;">$ {{ number_format($dp->saldoReal, 2) }}</td>
                                        <td style="text-align: right;">$ {{ number_format($dp->subtotalFinal, 2) }}</td>
                                        <td style="text-align: right;">$ {{ number_format($dp->ivaFinal, 2) }}</td>
                                        <td style="text-align: right;">$ {{ number_format($dp->montoReal, 2) }}</td>
                                        <td style="text-align: right;">$ {{ number_format($dp->difCambiaria, 2) }}</td>
                                        <td style="text-align: center;"> {{ $dp->pagada }}</td>
                                        @if($dp->fechaDatosPago)
                                        <td>{{ $dp->fechaDatosPago->format('d/m/Y') }}</td>
                                        @else
                                        <td>Sin fecha</td>
                                        @endif
                                        <td>{{$dp->observaciones}}</td>
                                    <td align="center">
                                        <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                                        @csrf
                                                    <input type="hidden" value="{{$dp->idDatosPago}}" name="idDatosPago">
                                                    <input type="hidden" value="{{$dp->idFactura}}" name="idFactura">
                                                    <button type="button" class="btn btn-sm btn-danger borrarDatoPago1" style='width:40px; height: 35px;'>
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </button>

                                                    <input type="hidden" value="{{$dp->idDatosPago}}" name="idDatosPago">
                                                    <input type="hidden" value="{{$dp->idFactura}}" name="idFactura">
                                                    <button type="button" class="btn btn-sm btn-info editarInfo1" style='width:40px; height: 35px;'>
                                                        <i class="glyphicon glyphicon-pencil" aria-hidden="true"></i>
                                                    </button>
                                        </form>   
                                    </td>
                                    </tr>
                                    @endforeach
                                   
                                </tbody>
                            </table>
</div>
<div id="modalInfoDatosPago"></div>

<script type="text/javascript">
$(function() {
    $('.borrarDatoPago1').click(
        function() {
            $("#reporteDatosPago").load('{{url('borrarDatosPago')}}' + '?' + $(this).closest('form').serialize());
        });

        $(".editarInfo1").click(function (e) {
    // var partida = $(this).val();
    // var idCotizacion=$('#idCotizacion').val();    
    // var tipoMoneda=$('#tipoMoneda').val();
    $('#modalInfoDatosPago').load('{{url('editarInfoDatosPago')}}' + '?' + $(this).closest('form').serialize());
    e.preventDefault(); 
    });
});
</script>