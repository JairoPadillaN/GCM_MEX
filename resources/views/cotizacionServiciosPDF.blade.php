    <style>
        @page {
            margin-top: 100px;
            /* valor anterior */
            /* margin-bottom: 175px; */
            margin-bottom: 160px;

        }
        #header {
            position: fixed;
            left: 0px;
            top: -97px;
            right: 0px;
            text-align: center;
        }
        #footer { 
            position: fixed;
            left: 0px;
            /* top: 930px; */
            top: 863px;
            right: 0px;
            /* height: 142px; */
            height: 105px;
        }
    </style>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
    </style>
    
    <div id="header">
        @if($publicidad == "5")
            <img src="{!! asset('archivos/membreteSurja.png')!!}" style="height:90px; width:100%; margin-left:10px">
        @else
            <img src="{!! asset('archivos/membrete.jpeg')!!}" style="height:86px; width:100%; margin-left:10px">
        @endif
    </div>
    <div id="footer">
        <center>
            @if($publicidad == "1")
                <img src="{!! asset('archivos/publicidad1.jpg')!!}" style="height:90px; width:100%;">
            @endif
            @if($publicidad == "2")
                <img src="{!! asset('archivos/publicidad2.jpg')!!}" style="height:90px; width:100%;">
            @endif
            @if($publicidad == "3")
                <img src="{!! asset('archivos/membreteInferior.PNG')!!}">
            @endif
            @if($publicidad == "4")
                <img src="{!! asset('archivos/publicidad4.jpg')!!}" style="height:90px; width:100%;">
            @endif
            @if($publicidad == "5")
                <img src="{!! asset('archivos/publicidad5.png')!!}" style="height:90px; width:100%;">
            @endif
        </center>
    </div> 
    <div id="content">
        <div style="text-align:right"><font SIZE=1><b>
            @if($arrayFecha[1]=="01")
                January 
            @endif
            @if($arrayFecha[1]=="02")
                February 
            @endif
            @if($arrayFecha[1]=="03")
                March 
            @endif             
            @if($arrayFecha[1]=="04")
                April 
            @endif
            @if($arrayFecha[1]=="05")
                May 
            @endif
            @if($arrayFecha[1]=="06")
                June 
            @endif
            @if($arrayFecha[1]=="07")
                July 
            @endif
            @if($arrayFecha[1]=="08")
                August 
            @endif
            @if($arrayFecha[1]=="09")
                September 
            @endif
            @if($arrayFecha[1]=="10")
                Octobre 
            @endif
            @if($arrayFecha[1]=="11")
                November 
            @endif
            @if($arrayFecha[1]=="12")
                Deceember 
            @endif            
            {{$arrayFecha[2]}}, {{$arrayFecha[0]}}        
            </b></font>
        </div>
        @if($consultaCotizacion->numeroCotizacion != "") 
            <div style="text-align:right"><font SIZE=1 color="#F9B201"><b>QUOTE: {{$consultaCotizacion->numeroCotizacion}}</b></font></div>
        @endif
        @if($consultaCotizacion->razonSocial != "") 
            <br>
            <div style="text-align:left">
                <font SIZE=1 color="#02A8A2">
                    <b>Customer: 
                        <br>
                        {{$consultaCotizacion->razonSocial}}
                        <br>
                    </b>
                </font>
                <font SIZE=1 color="#162576">
                    <b>
                        <?php
                            echo  strtoupper($consultaCotizacion->sucursal);
                        ?>
                    </b>
                    <br>
                </font>
                <font SIZE=1 color="#8B8B8B">
                    <div>
                        @if($consultaCotizacion->dirigidoA != '')
                            Contact: {{$consultaCotizacion->dirigidoA}}                    
                            @if($cuantosContactos1 == 1)
                                @foreach($consultaContacto1 as $consultaContacto1)    
                                    @if($consultaContacto1->telefonoCont != '')
                                        Tel: {{$consultaContacto1->telefonoCont}}
                                    @endif
                                    @if($consultaContacto1->correoCont != '')
                                        Correo: {{$consultaContacto1->correoCont}} 
                                    @endif
                                @break;
                                @endforeach
                            @endif                            
                        @endif
                    </div>
                    <div>
                        @if($consultaCotizacion->conCopia != '')
                            {{$consultaCotizacion->conCopia}}                            
                            @if($cuantosContactos2 == 1)
                                @foreach($consultaContacto2 as $consultaContacto2)    
                                    @if($consultaContacto2->telefonoCont != '')
                                        Tel: {{$consultaContacto2->telefonoCont}}
                                    @endif
                                    @if($consultaContacto2->correoCont != '')
                                        Correo: {{$consultaContacto2->correoCont}}
                                    @endif
                                @break;
                                @endforeach
                            @endif
                        @endif
                    </div>                    
                </font>
            </div>
        @endif
        <!-- <br> -->
        <div class="table-responsive">
            <table>
                <thead>
                    <tr >
                        <th style="border-bottom: solid 1px black;width:30px;"><font SIZE=0>LINE</font></th>
                        <th style="border-bottom: solid 1px black;width:5px;"></th>
                        <th style="border-bottom: solid 1px black;width:315px;"><font SIZE=0>&nbsp;&nbsp;DESCRIPTION</font></th>
                        <th style="border-bottom: solid 1px black;width:68px;"><font SIZE=0>QUANTITY</f68t></th>
                        <th style="border-bottom: solid 1px black;width:68px;"><font SIZE=0>UOM</font></th>                        
                        <th style="border-bottom: solid 1px black;width:78;">
                            <font SIZE=0>
                                @if($tipoSesion != 'Compras')
                                    UNIT PRICE
                                @endif
                            </font>
                        </th>
                        <th style="border-bottom: solid 1px black;width:78;">
                            <font SIZE=0>
                                @if($tipoSesion != 'Compras')    
                                    TOTAL
                                @endif
                            </font>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr style=" background-color: #F3F3F3;">
                        <td HEIGHT="8"></td>
                        <td style="border-left: solid 1px black;"></td>
                        <td></td>
                        <td></td>
                        <td></td>                    
                        <td></td>
                        <td></td>
                    </tr>
                    <?php $x = 1;?>
                    @foreach($serviciosEncotizacion as $sEc)                    
                        <tr>
                            <td style="vertical-align:top;"><center><font SIZE=0 color="#162576">{{$x}}</font></center></td>
                            <?php $x = $x+1;?>
                            <td style="border-left: solid 1px black;"></td>                        
                            <td>
                                <font SIZE=0 color="#162576">
                                   <b>  {{$sEc->claveServicio}} - {{$sEc->servicioTecnico}}</b>
                                    <br>
                                        {{$sEc->detalle}}
                                    @if($sEc->notas !="")
                                        <br><br>
                                        <b>Notas:</b> {!! nl2br(e($sEc->notas))!!}
                                    @endif
                                    
                                    @if($sEc->semanasEntrega !="N/A")
                                        @if($sEc->semanasEntrega !="")
                                            <br>
                                            ETA: {{$sEc->semanasEntrega}}
                                        @endif
                                    @endif
                                </font>
                            </td>                            
                            <td style="vertical-align:baseline;"><font SIZE=0 color="#162576">{{$sEc->cantidad}}</font></td>
                            <td style="vertical-align:baseline;" ><font SIZE=0 color="#162576">{{$sEc->observacionCantidad}}</font></td>
                            <td style="vertical-align:baseline;">
                                <font SIZE=0 color="#162576">
                                    @if($tipoSesion != 'Compras')
                                        @if($sEc->descuento !="")
                                            <strike>{{$consultaCotizacion->tipoMoneda}} $<?php echo number_format ($sEc->montoEquipo,2)?></strike>
                                            <br>                                                   
                                            {{$consultaCotizacion->tipoMoneda}} $<?php $descuentoUnitario = $sEc->montoFinanciamiento / $sEc->cantidad; echo number_format ($descuentoUnitario,2);?>
                                        @else
                                            {{$consultaCotizacion->tipoMoneda}} ${{$sEc->montoEquipo}}
                                        @endif
                                    @endif
                                </font>
                            </td>
                            <td style="vertical-align:baseline;">
                                <font SIZE=0 color="#162576">
                                    @if($tipoSesion != 'Compras')
                                        @if($sEc->descuento !="")
                                        <strike>{{$consultaCotizacion->tipoMoneda}} $ <?php $totalff = $sEc->montoEquipo * $sEc->cantidad; echo number_format ($totalff,2)?></strike>
                                            <br>{{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($sEc->montoFinanciamiento,2)?>
                                        @else
                                            {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($sEc->montoFinanciamiento,2)?>
                                        @endif
                                    @endif
                                </font>
                            </td>
                        </tr>
                        <tr style=" background-color: #F3F3F3;">
                            <td HEIGHT="8"></td>
                            <td style="border-left: solid 1px black;"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>                                        
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($verTotal == 'Si' && $tipoSesion != "Compras")
                <div style="text-align:right; margin-right:10px"><font SIZE=0><b>Total: {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($consultaCotizacion->montoTotal,2)?></b></font></div>
            @endif
            <!-- <br> -->
            <font SIZE=0 color="#162576">NOTES:</font>
                <br>
            <font SIZE=0 color="#162576">
                <ol type="a">    
                    @if($consultaCotizacion->check1 == "si")
                        <li>&nbsp;{{$consultaCotizacion->textoCheck1}}<br>
                    @endif
                    
                    @if($consultaCotizacion->check2 == "si")
                        <li>&nbsp;{{$consultaCotizacion->textoCheck2}}<br>
                    @endif
                    
                    @if($consultaCotizacion->check3 == "si")
                        <li>&nbsp;{{$consultaCotizacion->textoCheck3}}<br>
                    @endif
                    
                    @if($consultaCotizacion->check4 == "si")
                        <li>&nbsp;{{$consultaCotizacion->textoCheck4}}<br>
                    @endif
                    
                    @if($consultaCotizacion->check5 == "si")
                        <li>&nbsp;{{$consultaCotizacion->textoCheck5}}<br>
                    @endif
                    
                    @if($consultaCotizacion->check6 == "si")
                        <li>&nbsp;{{$consultaCotizacion->textoCheck6}}<br>
                    @endif
                    
                    @if($consultaCotizacion->check7 == "si")
                        <li>&nbsp;{{$consultaCotizacion->textoCheck7}}
                    @endif
                    @if($consultaCotizacion->check8 == "si")
                        <li>&nbsp;{{$consultaCotizacion->textoCheck8}}
                    @endif
                    @if($verCambio == 'Si')
                        @if($consultaCotizacion->tipoMoneda == 'USD')
                            <br>
                            <li>&nbsp;1 UDS  = {{$consultaCotizacion->cambio}} MXN cotizado en Dólares Americanos pagado en Moneda Extrajera 
                            o bien en moneda nacional al tipo de cambio del día de pago, estipulado por 
                            el DOF
                        @endif
                    @endif

                </ol>
            </font>
            <div style="text-align:center">
                <font SIZE=1 color="#162576">
                    <!-- <br>
                    <br>
                    <br> -->
                    <br>
                    <br>
                    ________________________________________
                    <br>
                    {{$consultaCotizacion->usuario}}
                </font>
            </div>             
        </div>
    </div>