<!-- <script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script> -->
    @if($tipoReporte == 2)

        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
            <thead>
                <tr style="background-color:#9ABCF4">
                    <th>No. orden de compra</th>
                    <th>Fecha de orden de compra</th>
                    <th>Fecha de entrega de pedido</th>
                    <th>Fecha Programada de pago</th>
                    <th>Fecha de pago</th>
                    <th>Monto final de pago</th>
                    <th>Status</th>
                    <th style="width:150px; text-align:right;">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($consulta as $oc)
                <tr>
                    <td><center>{{$oc->codigoOrden}}</td></center>
                    <td>
                        <center>
                            @if($oc->fechaOrden > "0000-00-00")
                                <?php                                
                                    $date = new DateTime($oc->fechaOrden);                                
                                    echo $date->format('d-m-Y'); // 31-07-2012
                                ?>
                            @else
                                Sin fecha
                            @endif
                        </center>
                    </td>
                    <td>
                        <center>
                            @if($oc->fechaEntregaProveedor > "0000-00-00")
                                <?php                                
                                    $date4 = new DateTime($oc->fechaEntregaProveedor);                                
                                    echo $date4->format('d-m-Y'); // 31-07-2012
                                ?>
                                <!-- @if($oc->estatus != "Pagado" && $oc->estatus != "Pagado con complementos de pago" && $oc->estatus != "Cancelado")
                                    <br>
                                    <button value="{{$oc->idOrden}}" class="modificarFechaEntrega3" style="background-color: Transparent;background-repeat:no-repeat;border: none;cursor:pointer;overflow: hidden;color:blue">
                                        <u>Modificar fecha de entrega</u>
                                    </button>
                                @endif -->
                            @else
                                Sin fecha
                            @endif
                        </center>
                    </td>
                    <td>
                        <center>
                            @if($oc->fechaProgPago > "0000-00-00")
                                <?php                                
                                    $date2 = new DateTime($oc->fechaProgPago);                                
                                    echo $date2->format('d-m-Y'); // 31-07-2012
                                ?>
                            @else
                                Sin fecha
                            @endif
                        </center>
                    </td>
                    <td>
                        <center>
                            @if($oc->fechaDeposito > "0000-00-00")
                                <?php                                
                                    $date3 = new DateTime($oc->fechaDeposito);                                
                                    echo $date3->format('d-m-Y'); // 31-07-2012
                                ?>
                            @else
                                Sin fecha
                            @endif
                        </center>
                    </td>
                    <td>
                        <center>
                            @if($oc->montoComplementoPago > 0)    
                                {{$oc->monedaComplementoPago}} $<?php echo number_format ($oc->montoComplementoPago,2)?>
                            @else
                                -
                            @endif
                        </center>
                    </td>
                    <td><center>{{$oc->estatus}}</td></center>                            
                    <td style="text-align: right;">
                        @if($oc->total > 0)
                            {{$oc->moneda}} $<?php echo number_format ($oc->total,2)?>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach                        
                <tr>
                    <td colspan="7" style="text-align: right;">Importe</td>
                    <td colspan="" style="text-align: right;">{{$sumaTotalImpuestos->moneda}} ${{$sumaTotalImpuestos->importe}}</td>
                </tr>
                <tr>
                    <td colspan="7" style="text-align: right;">ISR</td>
                    <td colspan="" style="text-align: right;">{{$sumaTotalImpuestos->moneda}} ${{$sumaTotalImpuestos->isrDineroTotal}}</td>
                </tr>
                <tr>
                    <td colspan="7" style="text-align: right;">IVA</td>
                    <td colspan="" style="text-align: right;">{{$sumaTotalImpuestos->moneda}} ${{$sumaTotalImpuestos->ivaDineroTotal}}</td>
                </tr>
                
                <tr>
                    <td colspan="7" style="text-align: right;"><b>Total:</b></td>
                    <td colspan="" style="text-align: right;"><b>{{$sumaTotalImpuestos->moneda}} ${{$sumaTotalImpuestos->total}}</td>
                </tr>
                @if($oc->notasCambioFechaEntregaProveedor != '')
                    <tr>
                        <td colspan="8"><b>Notas: </b>{{$oc->notasCambioFechaEntregaProveedor}}</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <input type="hidden" value="2" name="" id="tipoReporte">
    @else
        
        <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="text-align:center;">
            <thead>
                <tr style="background-color:#9ABCF4">
                    <th>No. orden de compra</th>
                    <th>Fecha de orden de compra</th>
                    <th>Fecha de entrega de pedido</th>
                    <th>Fecha Programada de pago</th>
                    <th>Fecha de pago</th>
                    <th>Monto de la orden de compra</th>
                    <th>Monto final de pago</th>                            
                    <th>Status</th>
                    <th>Archivos</th>
                    <th>Opciones</th>                                          
                    <th>Notas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($consulta as $oc)
                    <tr>
                        <td><center>{{$oc->codigoOrden}}</td></center>
                        <td>
                            <center>
                                @if($oc->fechaOrden > "0000-00-00")
                                    <?php                                
                                        $date = new DateTime($oc->fechaOrden);                                
                                        echo $date->format('d-m-Y'); // 31-07-2012
                                    ?>
                                @else
                                    Sin fecha
                                @endif
                            </center>
                        </td>
                        <td>
                            <center>
                                @if($oc->fechaEntregaProveedor > "0000-00-00")
                                    <?php                                
                                        $date4 = new DateTime($oc->fechaEntregaProveedor);                                
                                        echo $date4->format('d-m-Y'); // 31-07-2012
                                    ?>
                                    <!-- @if($oc->estatus != "Pagado" && $oc->estatus != "Pagado con complementos de pago" && $oc->estatus != "Cancelado")
                                        <br>
                                        <button value="{{$oc->idOrden}}" class="modificarFechaEntrega3" style="background-color: Transparent;background-repeat:no-repeat;border: none;cursor:pointer;overflow: hidden;color:blue">
                                            <u>Modificar fecha de entrega</u>
                                        </button>
                                    @endif -->
                                @else
                                    Sin fecha
                                @endif
                            </center>
                        </td>
                        <td>
                            <center>
                                @if($oc->fechaProgPago > "0000-00-00")
                                    <?php                                
                                        $date2 = new DateTime($oc->fechaProgPago);                                
                                        echo $date2->format('d-m-Y'); // 31-07-2012
                                    ?>
                                @else
                                    Sin fecha
                                @endif
                            </center>
                        </td>
                        <td>
                            <center>
                                @if($oc->fechaDeposito > "0000-00-00")
                                    <?php                                
                                        $date3 = new DateTime($oc->fechaDeposito);                                
                                        echo $date3->format('d-m-Y'); // 31-07-2012
                                    ?>
                                @else
                                    Sin fecha
                                @endif
                            </center>
                        </td>
                        <td>
                            @if($oc->total > 0)
                                {{$oc->moneda}} $<?php echo number_format ($oc->total,2)?>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($oc->montoComplementoPago > 0)    
                                {{$oc->monedaComplementoPago}} $<?php echo number_format ($oc->montoComplementoPago,2)?>
                            @else
                                -
                            @endif
                        </td>
                        <td><center>{{$oc->estatus}}</td></center>
                        <td>
                            <?php $parameter= Crypt::encrypt($oc->idOrden); ?>

                            @if($oc->total >= 1)
                                <div>
                                    <a href="{{URL::action('pdfXmlOrdenComprasController@pdfOrdenCompras',[$parameter])}}" target="_blank" class="btn btn-xs btn-info" style="margin-bottom:1px;width: 164px;">
                                        PDF Orden de compra
                                    </a>
                                </div>
                            @endif
                            
                            @if($oc->pdfFacturaProveedor != "")
                                <a target="_blank" href="{{asset('archivos/'.$oc->pdfFacturaProveedor)}}" class="btn btn-xs btn-info" style="margin-bottom:1px;">PDF Factura</a>
                            @endif

                            @if($oc->xmlFacturaProveedor != "" && $oc->xmlFacturaProveedor != "Sin archvio")
                                <a target="_blank" href="{{asset('archivos/'.$oc->xmlFacturaProveedor)}}" class="btn btn-xs btn-info" style="margin-bottom:1px;">XML Factura</a>
                                <br>
                            @endif
                            
                            @if($oc->evidenciaTransferencia != "")
                                <a target="_blank" href="{{asset('archivos/'.$oc->evidenciaTransferencia)}}" class="btn btn-xs btn-info" style="margin-bottom:1px; width: 164px;">Evidencia de transferencia</a>
                                <br>
                            @endif
                            
                            @if($oc->pdfComplementoProveedor != "")
                                <a target="_blank" href="{{asset('archivos/'.$oc->pdfComplementoProveedor)}}" class="btn btn-xs btn-info" style="margin-bottom:1px;">PDF Complemento de pago&nbsp;</a>
                                <br>
                            @endif
                            
                            @if($oc->xmlComplementoProveedor != "" && $oc->xmlComplementoProveedor != "Sin archvio")
                                <a target="_blank" href="{{asset('archivos/'.$oc->xmlComplementoProveedor)}}" class="btn btn-xs btn-info">XML Complemento de pago&nbsp;</a>
                            @endif
                            
                        </td>
                        <td>
                            @if($oc->estatusProveedor == 'Aceptado con fecha de entrega')
                                @if($oc->estatus == "Sin programar pago")
                                    <a href="{{URL::action('proveedoresController@subirArProv',[$parameter])}}" class="btn btn-xs btn-success">
                                        <i class="ace-icon fa fa-plus bigger-120"></i> Agregar archivos
                                    </a>
                                @endif
                                @if($oc->estatus == "Pagado" && ($oc->pdfComplementoProveedor == "" || $oc->xmlComplementoProveedor == ""))
                                    <a href="{{URL::action('proveedoresController@subirArProv',[$parameter])}}" class="btn btn-xs btn-warning">
                                        <i class="fa fa-cloud-upload"></i> Agregar complementos de pago
                                    </a>
                                @endif                                
                            @endif

                            @if($oc->estatusProveedor == 'Pendiente de aceptación' && $oc->fechaEntregaProveedor <= "0000-00-00")
                                <?php $validacionOk= Crypt::encrypt('si'); ?>
                                <a href="{{URL::action('pdfXmlOrdenComprasController@confirmarOrdenCompra',[$parameter,$validacionOk])}}" class="btn btn-xs btn-success">
                                    <i class="fa fa-check-square"></i> Aceptar orden de compra
                                </a>
                                <?php $validacion= Crypt::encrypt('no'); ?>
                                <a href="{{URL::action('pdfXmlOrdenComprasController@confirmarOrdenCompra',[$parameter,$validacion])}}" class="btn btn-xs btn-danger">
                                    <i class="fa fa-times" aria-hidden="true"></i> No aceptar orden de compra
                                </a>
                            @endif
                            
                            @if($oc->estatusProveedor == 'Pendiente de aceptación' && $oc->fechaEntregaProveedor > "0000-00-00")
                                <?php $validacionOk= Crypt::encrypt('si'); ?>
                                <a href="{{URL::action('pdfXmlOrdenComprasController@confirmarOrdenCompra',[$parameter,$validacionOk])}}" class="btn btn-xs btn-success">
                                    <i class="fa fa-check-square"></i> Finalizar confirmación
                                </a>
                            @endif

                        </td>
                        <td>{{$oc->notasCambioFechaEntregaProveedor}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <input type="hidden" value="1" name="" id="tipoReporte">
    @endif       
            
    <div id="cargarModalFechaP3"></div>
    


<script>
    // $(".modificarFechaEntrega3").click(function (e) {
    //     var idOrden = $(this).val();
    //     var tipoReporte= $("#tipoReporte").val();
    //     $('#cargarModalFechaP3').load('{{url('modificarFechaEntregaProveedor')}}' + '?idOrden='+idOrden + '&tipoReporte=' + tipoReporte);                
    //     e.preventDefault(); 
    // });
</script>
