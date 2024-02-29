        <style>
            @page {
                margin-top: 100px;
                margin-bottom: 130px;
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
                        RFC: OEJP740203NI2
                    </h4>
                </center>
            @elseif($consultaOrden[0]->nombreEmpresa == "YAGA")
                <center>
                    <h4>
                        YANETH ABILENE GONZALEZ ALVAREZ <br>
                        RFC: GOAY921221EH3
                    </h4>
                </center>
            @elseif($consultaOrden[0]->nombreEmpresa == "SURJA")
                <center>
                    <h4>
                        SURJA INGENIERIA S.A. DE C.V. <br>
                        RFC: SIN131106V82
                    </h4>
                </center>
            @elseif($consultaOrden[0]->nombreEmpresa == "CYM")
                <center>
                    <h4>
                        ARTURO YAMIL TORRES HAMDAN / C&M GROUP <br>
                        RFC: TOHA800103P69 
                    </h4>
                </center>
            @elseif($consultaOrden[0]->nombreEmpresa == "GCM")
                <center>
                    <h4>
                        GERMAN CONTROL MOTION S.A. DE C.V. <br>
                        RFC: GCM1809125LA
                    </h4>
                </center>
            @else
                <img src="{!! asset('archivos/encabeadoOrdenCompraEs.jpeg')!!}" style="height:86px; width:100%; margin-left:10px">
            @endif
        </div>    
        <div id="footer">
            <center>
                <!-- <img src="{!! asset('archivos/pieOrdenCompraPDF.jpg')!!}" style="height:90px; width:100%;"> -->
            </center>
        </div> 
        <div id="content" style="height:99%;">
            <div style="text-align:right">
                <font SIZE=0>
                    <b> Toluca Estado de México,
                        @if($arrayFecha[1]=="01")
                            Enero 
                        @endif
                        @if($arrayFecha[1]=="02")
                            Febrero 
                        @endif
                        @if($arrayFecha[1]=="03")
                            Marzo 
                        @endif             
                        @if($arrayFecha[1]=="04")
                            Abril 
                        @endif
                        @if($arrayFecha[1]=="05")
                            Mayo 
                        @endif
                        @if($arrayFecha[1]=="06")
                            Junio 
                        @endif
                        @if($arrayFecha[1]=="07")
                            Julio
                        @endif
                        @if($arrayFecha[1]=="08")
                            Agosto 
                        @endif
                        @if($arrayFecha[1]=="09")
                            Septiembre 
                        @endif
                        @if($arrayFecha[1]=="10")
                            Octubre 
                        @endif
                        @if($arrayFecha[1]=="11")
                            Noviembre 
                        @endif
                        @if($arrayFecha[1]=="12")
                            Diciembre 
                        @endif                        
                        {{$arrayFecha[2]}}, {{$arrayFecha[0]}}            
                    </b>
                </font>
            </div>                
            <div style="text-align:right"><font SIZE=1 color="#F9B201"><b>ORDEN DE COMPRA: {{$consultaOrden[0]->codigoOrden}}</b></font></div>                
            
            <br>
            <div style="text-align:left">
                <font SIZE=1 color="#02A8A2">
                    <b>
                        <br>
                            {{$consultaOrden[0]->razonSocialProv}}
                        <br>
                            {{$consultaOrden[0]->rfcProveedor}}
                        <br>
                    </b>
                </font>
                <font SIZE=1 color="#162576">
                    <b>
                        {{$consultaOrden[0]->direccionProveedor}}
                    </b>
                    <br>
                </font>
            </div>                
            
            <font SIZE=0>FAVOR DE SURTIR LOS SIGUIENTES PRODUCTOS</font>
            <div class="Table">           
                <div class="Heading">
                    <div class="CellHeading" style="width:68px;"><font SIZE=0>CANTIDAD</font></div>
                    <div class="CellHeading" style="width:314px; text-align:left;"><font SIZE=0>DESCRIPCION</font></div>                    
                    <div class="CellHeading" style="width:108px;"><font SIZE=0>PRECIO UNITARIO</font></div>
                    <div class="CellHeading" style="width:50px;"><font SIZE=0>IVA</font></div>
                    <div class="CellHeading" style="width:50px;"><font SIZE=0>ISR</font></div>
                    <div class="CellHeading" style="width:90px; text-align:right;"><font SIZE=0>SUBTOTAL</font></div>
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
                    <div class="saltoEncabezado">
                    </div>                                        
                </div>

                @foreach($consultaOrden as $consultaOrden)
                    @foreach($consultaProductosOrden as $consultaProductosOrden)
                        @if($consultaOrden->idOrden == $consultaProductosOrden->idOrden)
                            <div class="Row">
                                <div class="Cell">{{$consultaProductosOrden->cantidadOrden}}</div>
                                <div class="Cell" style="text-align:left;">
                                    <div>
                                        {{$consultaProductosOrden->nombreRefaccion}}
                                    </div>
                                    @if($consultaProductosOrden->notasProducto != "")
                                        <div>
                                            <b>Notas:</b>
                                        </div>
                                        <div>
                                            {!! nl2br(e($consultaProductosOrden->notasProducto))!!}
                                        </div>                                            
                                    @endif
                                    <div>
                                        @if($consultaProductosOrden->placa1 != "Sin archivo")
                                            <b>Placa 1:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                                                                                    
                                        @endif
                                        @if($consultaProductosOrden->placa2 != "Sin archivo")
                                            <b>Placa 2:</b>                                            
                                        @endif
                                    </div>
                                    <div>
                                        @if($consultaProductosOrden->placa1 != "Sin archivo")
                                            <img src="{{asset ('public/archivos/'.$consultaProductosOrden->placa1.'')}}" style="height:300px; width:300px; margin-top:8px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        @endif
                                        @if($consultaProductosOrden->placa2 != "Sin archivo")
                                            <img src="{{asset ('public/archivos/'.$consultaProductosOrden->placa2.'')}}" style="height:300px; width:300px; margin-top:8px">
                                        @endif
                                    </div>
                                </div>
                                <div class="Cell">${{$consultaProductosOrden->precioOrden}}</div>
                                <div class="Cell">${{$consultaProductosOrden->ivaDinero}}</div>
                                <div class="Cell">${{$consultaProductosOrden->isrDinero}}</div>
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
                                <div class="saltoDeFila">
                                </div>                    
                            </div>
                        @endif
                    @endforeach
                @endforeach                

                <!-- <font color="black">     -->
                    <div class="Row">
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        <div class="Cell" style="text-align:right;">
                            Importe:
                        </div>
                        <div class="Cell" style="text-align:right;">
                            {{$sumaTotalImpuestos->moneda}} ${{$sumaTotalImpuestos->importe}}
                        </div>
                    </div>
                    <div class="Row">
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
                    <div class="Row">
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        <div class="Cell" style="text-align:right;">
                            IVA:
                        </div>
                        <div class="Cell" style="text-align:right;">
                            {{$sumaTotalImpuestos->moneda}} ${{$sumaTotalImpuestos->ivaDineroTotal}}
                        </div>
                    </div>
                    <div class="Row">
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        <div class="Cell" style="text-align:right;">
                            <b>TOTAL:</b></div>
                        <div class="Cell" style="text-align:right;">
                            <b>{{$consultaOrden->moneda}} ${{$consultaOrden->total}}</b>                            
                        </div>
                    </div>
                <!-- </font> -->



            </div>
            
            <div>
                <font SIZE=0 color="#162576">
                    @if($consultaOrden->domicilioEntrega != '')                        
                        <b>Domicilio de entrega:</b>
                        <br>
                        {!! nl2br(e($consultaOrden->domicilioEntrega))!!}
                        <br>
                    @endif
                    @if($consultaOrden->notasOrden != '')                        
                        <b>Notas:</b>
                        <br>
                        {!! nl2br(e($consultaOrden->notasOrden))!!}
                        <br> <br>                    
                    @endif
                    FAVOR DE HACER LA FACTURA CON LOS SIGUIENTES DATOS <br>
                    GERMAN CONTROL MOTION S.A. DE C.V.
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    RFC: GCM1809125LA <br>
                    CAMINO AL CANAL #43 COLONIA TOLUQUILLA TLAQUEPAQUE, JALISCO CP45610 <br>
                    {{$consultaOrden->formaPago}} <br>
                    @if($consultaOrden->tipoPago == 'PUE') 
                        PUE: PAGO EN UNA SOLA EXHIBICIÓN <br>
                    @endif
                    @if($consultaOrden->tipoPago == 'PPD') 
                        PPD: PAGO EN PARCIALIDADES O DIFERIDO<br>
                    @endif
                    {{$consultaOrden->cfdi}}
                </font>
            </div>
            <br><br><br>
            <div>
                <center>
                    <font SIZE=0 color="">
                        Gracias,
                        <br>
                        <br>
                        <br>
                        ______________________________________
                        <br>
                        {{$consultaOrden->usuario}}<br>
                        <!-- ADMINISTRACION Y FINANZAS <br> -->
                        Cel: {{$consultaOrden->telCel}}<br>
                    </font>
                </center>
            </div>
        </div>