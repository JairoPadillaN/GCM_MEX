        <style>
            @page {
                margin-top: 100px;
                /* margin-bottom: 130px; */
                margin-bottom: 100px;
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
                top: 930px;
                right: 0px;
                height: 142px;
            }
            body { font-family: DejaVu Sans, sans-serif;}
            .Table {
                display: table;
            }
            .Title {
                display: table-caption;
                text-align: center;
                font-weight: bold;
                font-size: larger;                
            }
            .Heading {
                display: table-row;
                font-weight: bold;
                text-align: center;                
            }
            .CellHeading {
                display: table-cell;                
                padding-left: 1px;
                padding-right: 1px;
                border-top: solid 1px black;
            }
            .saltoEncabezado{
                border-top: solid 1px black;
                display: table-cell;
                height: 10px;
                background-color: #F3F3F3;
            }
            .saltoDeFila{        
                display: table-cell;
                height: 10px;
                background-color: #F3F3F3;
            }        
            .Row {
                display: table-row;
            }
            .Cell {
                display: table-cell;
                font-size:xx-small;
                color:#162576;
                text-align: center;
                /* border-width: thin;
                padding-left: 1px;
                padding-right: 1px; */
            }
        </style>
        
        <div id="header">
            @if($consultaOrden[0]->nombreEmpresa == "POJ")
                <center>
                    <h4>
                        PEDRO OCEGUEDA JIMENEZ <br>
                        @if($consultaOrden[0]->formato != "CMG USA")
                            RFC: OEJP740203NI2
                        @else
                            @if($consultaOrden[0]->mostrarTaxId == 'on')    
                                TAX ID : 88-0780071
                            @endif
                        @endif
                    </h4>
                </center>
            @elseif($consultaOrden[0]->nombreEmpresa == "YAGA")
                <center>
                    <h4>
                        YANETH ABILENE GONZALEZ ALVAREZ <br>
                        @if($consultaOrden[0]->formato != "CMG USA")
                            RFC: GOAY921221EH3
                        @else
                            @if($consultaOrden[0]->mostrarTaxId == 'on')    
                                TAX ID : 88-0780071
                            @endif
                        @endif
                    </h4>
                </center>
            @elseif($consultaOrden[0]->nombreEmpresa == "SURJA")
                <center>
                    <h4>
                        SURJA INGENIERIA S.A. DE C.V. <br>
                        @if($consultaOrden[0]->formato != "CMG USA")
                            RFC: SIN131106V82
                        @else
                            @if($consultaOrden[0]->mostrarTaxId == 'on')    
                                TAX ID : 88-0780071
                            @endif
                        @endif
                    </h4>
                </center>
            @elseif($consultaOrden[0]->nombreEmpresa == "CYM")
                <center>
                    <h4>
                        ARTURO YAMIL TORRES HAMDAN / C&M GROUP <br>
                        @if($consultaOrden[0]->formato != "CMG USA")
                            RFC: TOHA800103P69 
                        @else
                            @if($consultaOrden[0]->mostrarTaxId == 'on')    
                                TAX ID : 88-0780071
                            @endif
                        @endif
                    </h4>
                </center>
            @elseif($consultaOrden[0]->nombreEmpresa == "GCM")
                <center>
                    <h4>
                        GERMAN CONTROL MOTION S.A. DE C.V. <br>
                        @if($consultaOrden[0]->formato != "CMG USA")
                            RFC: GCM1809125LA
                        @else
                            @if($consultaOrden[0]->mostrarTaxId == 'on')    
                                TAX ID : 88-0780071
                            @endif
                        @endif
                    </h4>
                </center>
            @elseif($consultaOrden[0]->nombreEmpresa == "CMIN")
                <center>
                    <h4>
                        CONTROL & MOTION GROUP INC <br>
                        @if($consultaOrden[0]->formato == "CMG USA")
                            @if($consultaOrden[0]->mostrarTaxId == 'on')    
                                TAX ID : 88-0780071
                            @endif
                        @endif
                    </h4>
                </center>
			@else
                <img src="{!! asset('public/archivos/encabeadoOrdenCompraEs.jpeg')!!}" style="height:86px; width:100%; margin-left:10px">
            @endif
        </div>    
        <div id="footer">
            <center>
                <!-- <img src="{!! asset('public/archivos/pieOrdenCompraPDF.jpg')!!}" style="height:90px; width:100%;"> -->
            </center>
        </div> 
        <div id="content" style="height:99%;">
            <div style="text-align:right">
                <font SIZE=0>
                    <b>
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
                            October
                        @endif
                        @if($arrayFecha[1]=="11")
                            November 
                        @endif
                        @if($arrayFecha[1]=="12")
                            December 
                        @endif                        
                        {{$arrayFecha[2]}}, {{$arrayFecha[0]}}            
                    </b>
                </font>
            </div>                
            <div style="text-align:right"><font SIZE=1 color="#F9B201"><b>PURCHARSE ORDER: {{$consultaOrden[0]->codigoOrden}}</b></font></div>                

            <div class="Table">
                <div class="Row">
                    <div class="Cell" style="text-align:left; width:300px;">
                        <font SIZE=0 color="#02A8A2">
                            <b> 
                                VENDOR INFO:
                                <br>
                                    {{$consultaOrden[0]->nombreVendedor}} {{$consultaOrden[0]->nombreVendedor2}}
                                <br>
                                    {{$consultaOrden[0]->telefonoProveedor}}{{$consultaOrden[0]->telefonoProveedor2}}
                                <!-- <br> -->
                                    <!-- {{$consultaOrden[0]->correoProveedor}}{{$consultaOrden[0]->correoProveedor2}} -->
                                <br>
                                    {{$consultaOrden[0]->razonSocialProv}}
                                @if($consultaOrden[0]->mostrarRfc == 'on')
                                <br>
                                    {{$consultaOrden[0]->rfcProveedor}}
                                @endif
                            </b>
                        </font>
                        <font SIZE=0 color="#02A8A2">
                            <b>
                                <br>
                                {{$consultaOrden[0]->direccionProveedor}}
                            </b>
                        </font>
                    </div>
                    <div class="Cell" style="text-align:left; width:120px;">
                        <p style="font-size: 0.700em" color="#162576">
                            @if($consultaOrden[0]->domicilioEntrega != '')
                                <b>Ship To:</b>
                                <br>
                                {!! nl2br(e($consultaOrden[0]->domicilioEntrega))!!}
                                <br>
                                <br>
                            @endif
                            @if($consultaOrden[0]->billTo != '')
                                <b>Bill To:</b>
                                <br>
                                {!! nl2br(e($consultaOrden[0]->billTo))!!}
                                <br>
                            @endif
                        </p>
                    </div>
                    <div class="Cell" style="text-align:left; width:180px;">
                        <p style="font-size: 0.700em" color="#162576">
                            @if($consultaOrden[0]->detalleMetodoEnvio != '')
                                <b>Shipping Method:</b><br>
                                {!! nl2br(e(strtoupper($consultaOrden[0]->detalleMetodoEnvio)))!!}
                                <br>
                            @endif
                            @if($consultaOrden[0]->detalleIncoterm != '')
                                {!! nl2br(e(strtoupper($consultaOrden[0]->detalleIncoterm)))!!}
                                <br><br>
                            @endif
                            @if($consultaOrden[0]->incoterm != '')
                                <b>Incoterm:</b><br>
                                {!! nl2br(e($consultaOrden[0]->incoterm))!!}
                                <br><br>
                            @endif
                            @if($consultaOrden[0]->vendorQuote != '')
                                <b>Vendor Quote Nr./Reference:</b><br>
                                {!! nl2br(e($consultaOrden[0]->vendorQuote))!!}
                                <br><br>
                            @endif
                            @if($consultaOrden[0]->formato == "CMG USA")
                                @if($consultaOrden[0]->paymentTerms != '')
                                    <b>Payment Termns:</b><br>
                                    {!! nl2br(e($consultaOrden[0]->paymentTerms))!!}
                                    <br><br>
                                @endif
                            @else
                                {{$consultaOrden[0]->formaPago}} <br>
                                @if($consultaOrden[0]->tipoPago == 'PUE') 
                                    PUE: PAGO EN UNA SOLA EXHIBICIÓN <br>
                                @endif
                                @if($consultaOrden[0]->tipoPago == 'PPD') 
                                    PPD: PAGO EN PARCIALIDADES O DIFERIDO<br>
                                @endif
                                {{$consultaOrden[0]->cfdi}}
                                <br>
                            @endif
                            @if($consultaOrden[0]->dueDate != '')
                                <b>Due Date:</b><br>
                                <?php 
                                    $diaMes= substr($consultaOrden[0]->dueDate,0,6);
                                    $year= substr($consultaOrden[0]->dueDate,-5);
                                    echo $diaMes.$year;
                                ?>
                                <br><br>
                            @endif
                            @if($consultaOrden[0]->notasOrden != '')
                                <b>Notes:</b>
                                <br>
                                {!! nl2br(e($consultaOrden[0]->notasOrden))!!}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div style="height: 5px;"></div>
            <?php $x = 0?>
            <div class="Table">
                <div class="Heading">
                    <div class="CellHeading" style="width:32px;"><font SIZE=0>LINE</font></div>
                    <div class="CellHeading" style="width:35px;"><font SIZE=0>QTY</font></div>
                    @if($consultaOrden[0]->formato == "CMG USA")
                        <div class="CellHeading" style="width:316px; text-align:left;"><font SIZE=0>DESCRIPTION</font></div>
                        <div class="CellHeading" style="width:130px;"><font SIZE=0>UNIT PRICE</font></div>
                        <div class="CellHeading" style="width:72px;"><font SIZE=0>SALE TAX</font></div>
                        <div class="CellHeading" style="width:95px; text-align:right;"><font SIZE=0>EXTENDED PRICE</font></div>
                    @else
                        <div class="CellHeading" style="width:314px; text-align:left;"><font SIZE=0>DESCRIPTION</font></div>
                        <div class="CellHeading" style="width:108px;"><font SIZE=0>UNIT PRICE</font></div>
                        <div class="CellHeading" style="width:50px;"><font SIZE=0>SALE TAX</font></div>
                        <div class="CellHeading" style="width:50px;"><font SIZE=0>ISR</font></div>
                        <div class="CellHeading" style="width:90px; text-align:right;"><font SIZE=0>EXTENDED PRICE</font></div>
                    @endif
                </div>
                
                <div class="Row">
                    <div class="saltoEncabezado">
                    </div>
                    <div class="saltoEncabezado">
                    </div>
                    <div class="saltoEncabezado">
                    </div>
                    <div class="saltoEncabezado">
                    </div>
                    <div class="saltoEncabezado">
                    </div>
                    @if($consultaOrden[0]->formato != "CMG USA")
                        <div class="saltoEncabezado">
                        </div>
                    @endif
                    <div class="saltoEncabezado">
                    </div>                                        
                </div>

                @foreach($consultaOrden as $consultaOrden)
                    @foreach($consultaProductosOrden as $consultaProductosOrden)
                        @if($consultaOrden->idOrden == $consultaProductosOrden->idOrden)
                            <div class="Row">
                                <div class="Cell"><?php echo $x = $x + 1?></div>
                                <div class="Cell">{{$consultaProductosOrden->cantidadOrden}}</div>
                                <div class="Cell" style="text-align:left;">
                                    <!-- <div>
                                        {{$consultaProductosOrden->codigo}} {{$consultaProductosOrden->nombreRefaccion}}
                                    </div> -->
                                    @if($consultaProductosOrden->vendorPartNumber !='')
                                        <div>
                                            <b>Vendor Part Number:</b>
                                        </div>
                                        <div>
                                            {{$consultaProductosOrden->vendorPartNumber}}
                                        </div>
                                        <div>
                                            {{$consultaProductosOrden->nombreRefaccion}}
                                        </div>
                                    @endif
                                    @if($consultaProductosOrden->notasProducto != "")
                                        <!-- <div>
                                            <b>Notas:</b>
                                        </div> -->
                                        <div>
                                            {!! nl2br(e($consultaProductosOrden->notasProducto))!!}
                                        </div>                                            
                                    @endif
                                    <div>
                                    
                                        <?php
                                            $placa1 = public_path().'/archivos/'.$consultaProductosOrden->placa1;
                                            $placa2 = public_path().'/archivos/'.$consultaProductosOrden->placa1;
                                        ?>
                                                                                
                                        @if($consultaProductosOrden->placa1 != "Sin archivo" && file_exists($placa1) )
                                            <b>Placa 1:</b><br>
                                            <img src="{{asset ('public/archivos/'.$consultaProductosOrden->placa1.'')}}" style="height:300px; width:300px; margin-top:8px"><br><br>
                                        @endif
                                        @if($consultaProductosOrden->placa2 != "Sin archivo" && file_exists($placa2) )
                                            <b>Placa 2:</b> <br>
                                            <img src="{{asset ('public/archivos/'.$consultaProductosOrden->placa2.'')}}" style="height:300px; width:300px; margin-top:8px"><br><br>
                                        @endif
                                    </div>                                    
                                </div>
                                <div class="Cell">${{$consultaProductosOrden->precioOrden}}</div>
                                <div class="Cell">${{$consultaProductosOrden->ivaDinero}}</div>
                                @if($consultaOrden->formato != "CMG USA")
                                    <div class="Cell">${{$consultaProductosOrden->isrDinero}}</div>
                                @endif
                                <div class="Cell" style="text-align:right;">${{$consultaProductosOrden->subtotal}}</div>
                            </div>

                            <div class="Row">
                                <div class="saltoDeFila">
                                </div>
                                <div class="saltoDeFila">
                                </div>
                                <div class="saltoDeFila">
                                </div>
                                <div class="saltoDeFila">
                                </div>
                                <div class="saltoDeFila">
                                </div>
                                @if($consultaOrden->formato != "CMG USA")
                                    <div class="saltoDeFila">
                                    </div>
                                @endif
                                <div class="saltoDeFila">
                                </div>                    
                            </div>
                        @endif
                    @endforeach
                @endforeach                

                <!-- <font color="black"> -->
                    <div class="Row">
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        @if($consultaOrden->formato != "CMG USA")
                            <div class="Cell"></div>
                        @endif
                        <div class="Cell" style="text-align:right;">
                            AMOUNT:
                        </div>
                        <div class="Cell" style="text-align:right;">
                            {{$sumaTotalImpuestos->moneda}} ${{$sumaTotalImpuestos->importe}}
                        </div>
                    </div>
                    @if($consultaOrden->formato != "CMG USA")
                        <div class="Row">
                            <div class="Cell"></div>
                            <div class="Cell"></div>
                            <div class="Cell"></div>
                            <div class="Cell"></div>
                            <div class="Cell"></div>
                            <div class="Cell" style="text-align:right;">
                                ISR:
                            </div>
                            <div class="Cell" style="text-align:right;">
                                {{$sumaTotalImpuestos->moneda}} ${{$sumaTotalImpuestos->isrDineroTotal}}
                            </div>
                        </div>
                    @endif
                    <div class="Row">
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        @if($consultaOrden->formato != "CMG USA")
                            <div class="Cell"></div>
                        @endif
                        <div class="Cell" style="text-align:right;">
                            SALE TAX:
                        </div>
                        <div class="Cell" style="text-align:right;">
                            {{$sumaTotalImpuestos->moneda}} ${{$sumaTotalImpuestos->ivaDineroTotal}}
                        </div>
                    </div>
                    @if($consultaOrden->formato != "CMG USA")
                        <div class="Row">
                            <div class="Cell"></div>
                            <div class="Cell"></div>
                            <div class="Cell"></div>
                            <div class="Cell"></div>
                            <div class="Cell"></div>
                            <div class="Cell" style="text-align:right;">RETENTIONS:</div>
                            <div class="Cell" style="text-align:right;">
                                {{$consultaOrden->moneda}} ${{$sumaTotalImpuestos->retencionDineroTotal}}
                            </div>
                        </div>
                    @endif
                    <div class="Row">
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        @if($consultaOrden->formato != "CMG USA")
                            <div class="Cell"></div>
                        @endif
                        <div class="Cell" style="text-align:right;">
                            <b>TOTAL:</b></div>
                        <div class="Cell" style="text-align:right;">
                            <b>{{$consultaOrden->moneda}} ${{$consultaOrden->total}}</b>
                        </div>
                    </div>
                <!-- </font> -->



            </div>
            
            <!-- <div>
                <font SIZE=0 color="#162576">
                    @if($consultaOrden->domicilioEntrega != '')
                        <b>Shipping Address:</b>
                        <br>
                        {!! nl2br(e($consultaOrden->domicilioEntrega))!!}
                        <br>
                    @endif
                    
                    <strong>Billing Address:</strong><br>
                    @if($consultaOrden->nombreEmpresa == "POJ")                                        
                        PEDRO OCEGUEDA JIMENEZ
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        RFC: OEJP740203NI2 <br>
                    @elseif($consultaOrden->nombreEmpresa == "YAGA")                                        
                        YANETH ABILENE GONZALEZ ALVAREZ
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        RFC: GOAY921221EH3 <br>
                        LOS GIRASOLES #308, ALTAMIRA, TONALA C.P. 45400, JALISCO. <br>
					@elseif($consultaOrden->nombreEmpresa == "CMIN")                                        
                        CONTROL & MOTION GROUP INC
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <br>
                        4602 Modern Lane, 78041
						<br>
						Laredo Texas. USA<br>
                    @elseif($consultaOrden->nombreEmpresa == "SURJA")                                        
                        SURJA INGENIERIA S.A. DE C.V.
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        RFC: SIN131106V82 <br>
                        LA CANTERA #2550 INT #71, CIUDAD DEL SOL, QUERETARO C.P.76116, QUERETARO <br>
                    @elseif($consultaOrden->nombreEmpresa == "CYM")                                        
                        ARTURO YAMIL TORRES HAMDAN / C&M GROUP
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        RFC: TOHA800103P69  <br>
                        CAMINO AL CANAL #43, TOLUQUILLA, SAN PEDRO TLAQUEPAQUE C.P. 45610, JALISCO. <br>
                    @elseif($consultaOrden->nombreEmpresa == "GCM")
                        GERMAN CONTROL MOTION S.A. DE C.V.
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						RFC: GCM1809125LA <br>
						Calle Camino al Canal #43, Col. Toluquilla,  <br>
                          C.P. 45610, Tlaquepaque, Jal. México <br>                        
                    @else
                        -
                    @endif
                    @if($consultaOrden->formato == "CMG USA")
                        PAYMENT TERMS: {{$consultaOrden->paymentTerms}} 
                    @else
                        {{$consultaOrden->formaPago}} <br>
                        @if($consultaOrden->tipoPago == 'PUE') 
                            PUE: PAGO EN UNA SOLA EXHIBICIÓN <br>
                        @endif
                        @if($consultaOrden->tipoPago == 'PPD') 
                            PPD: PAGO EN PARCIALIDADES O DIFERIDO<br>
                        @endif
                        {{$consultaOrden->cfdi}}
                    @endif
                </font>
            </div> -->

            <!-- <br><br><br><br><br><br> -->
            <div>
                <font SIZE=0 color="">
                    @if ($consultaOrden->formato == "CMG USA")    
                        Thank you,
                    @else
                        Gracias / Thank you,
                    @endif
                    <br>
                    {{$consultaOrden->usuario}}<br>
                    procurement.int@gcmgdl.com<br>
                    <!-- ADMINISTRACION Y FINANZAS <br> -->
                    @if ($consultaOrden->formato == "CMG USA")
                        Laredo, Texas 1+ 956-717-3133<br>
                    @else
                        <!-- Movil: 1 +52 {{$consultaOrden->telCel}}<br>1 +52 3315257497 -->
                        Movil: 1 +52 33-1301-0829 <br>
                        Movil: 1 +52 33-1523-3094 <br>
                    @endif

                    {{$consultaOrden->telAdicional}}, {{$consultaOrden->correoAdicional}}
                    
                </font>
            </div>
        </div>