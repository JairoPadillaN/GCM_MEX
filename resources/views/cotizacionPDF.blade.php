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
                top: 863px;
                right: 0px;
                height: 105px;
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
                text-align: left;
            }
            .CellHeading {
                display: table-cell;
                padding-left: 1px;
                padding-right: 1px;       
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
                /* border-width: thin;
                padding-left: 1px;
                padding-right: 1px; */
            }
        </style>
        
        <div id="header">
            @if($publicidad == "5")
                <img src="{!! asset('archivos/membreteSurja.png')!!}" style="height:90px; width:100%; margin-left:10px">
            @elseif($publicidad == "6")
                <img src="{!! asset('archivos/membreteCYMgroup.jpeg')!!}" style="height:95px; width:100%; margin-left:10px">
            @else
                <img src="{!! asset('archivos/membrete.jpeg')!!}" style="height:95px; width:100%; margin-left:10px">
            @endif
        </div>    
        <div id="footer">
            <center>
                @if($publicidad == "1" || $publicidad == "6")
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
                <div style="text-align:right">
                    <font SIZE=1>
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
                                Octobre 
                            @endif
                            @if($arrayFecha[1]=="11")
                                November 
                            @endif
                            @if($arrayFecha[1]=="12")
                                Deceember 
                            @endif                        
                            {{$arrayFecha[2]}}, {{$arrayFecha[0]}}            
                        </b>
                    </font>
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
                        <font SIZE=1 color="#162576">
                            <b>
                                <?php
                                echo  strtoupper($consultaCotizacion->direccion);
                                ?>
                            </b>
                            <br>
                        </font>
                        <font SIZE=2 color="#8B8B8B">
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
            <div class="Table">           
                <div class="Heading">
                    <div class="CellHeading" style="width:28px;padding-top: 1%;"><font SIZE=0>LINE</font></div>
                    <div class="CellHeading" style="width:60px;padding-top: 1%;padding-left:3px"><font SIZE=0>QUANTITY</font></div>
                    <div class="CellHeading" style="width:320px;padding-top: 1%;"><font SIZE=0>DESCRIPTION</font></div>
                    <div class="CellHeading" style="width:90px;padding-top: 1%;"><font SIZE=0>ETA</font></div>
                    <div class="CellHeading" style="width:90px;padding-top: 1%;">
                        <font SIZE=0>
                            @if($tipoSesion != 'Compras')
                                UNIT PRICE
                            @endif
                        </font>
                    </div>
                    <div class="CellHeading" style="">
                        <font SIZE=0>
                            @if($verTotal == 'Si' && $tipoSesion != 'Compras')
                                TOTAL <br>
                                {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($consultaCotizacion->montoTotal,2)?>
                            @endif
                        </font>
                    </div>                
                </div>
                <div class="Row">
                    <div class="saltoEncabezado">
                    </div>
                    <div class="saltoEncabezado" style="border-left: solid 1px black;">
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
                <?php $x = 1;?>
                    @foreach($equiposNuevosYreparados as $equiposNYp)
                        <div class="Row">
                            <div class="Cell">
                                <center>{{$x}}</center>
                            </div>
                            <?php $x = $x+1;?>
                            <div class="Cell" style="border-left: solid 1px black;padding-left:13px">
                                {{$equiposNYp->piezas}}
                            </div>                        
                            @if($equiposNYp->tipoCotizacion != "reparacion")
                                @if($equiposNYp->tipoCotizacion == "nuevo")
                                    <div class="Cell">
                                        @foreach($datosEquiposNuevos as $dEn)                
                                            @if($equiposNYp->idPartesVenta == $dEn->idPartesVenta)
                                            <?php
                                                $validandoTipo = strpos($dEn->presentacion,'Servicio');
                                            ?>
                                                @if($validandoTipo === FALSE)
                                                    <b>Brand New</b>
                                                @endif
                                            @break;
                                            @endif                
                                        @endforeach
                                        <div>
                                            <b>   
                                                @foreach($datosEquiposNuevos as $dEn)
                                                    @if($equiposNYp->idPartesVenta == $dEn->idPartesVenta)
                                                        {{$dEn->nombreRefaccion}}
                                                        @break;
                                                    @endif                
                                                @endforeach
                                            </b>
                                        </div>
                                        <div>
                                            SKU:
                                            @foreach($datosEquiposNuevos as $dEn)
                                                @if($equiposNYp->idPartesVenta == $dEn->idPartesVenta)
                                                    <?php
                                                        $codigoSeparado = explode('-',$dEn->codigo);
                                                        $quitandoCaracteres = substr($codigoSeparado[1], -5);
                                                    ?>
                                                    {{$codigoSeparado[0]}}-{{$quitandoCaracteres}}
                                                    @break;
                                                @endif                
                                            @endforeach
                                        </div>                                        
                                        @if($equiposNYp->verSku == "Si")
                                            @if($equiposNYp->skuEquivalente !="")
                                                <div>
                                                    SKU equivalente: {{$equiposNYp->skuEquivalente}}
                                                </div>
                                            @endif
                                            <!-- Se elimino el break y se coloco las etiquetas div para solucionar el problema Min/max width is undefined table rows -->
                                            <div></div>
                                        @endif
                                        @foreach($datosEquiposNuevos as $dEn)
                                            @if($equiposNYp->idPartesVenta == $dEn->idPartesVenta)
                                                @if($dEn->modelo !="")
                                                    <div>
                                                        Model: {{$dEn->modelo}}
                                                    </div>
                                                @endif
                                                @break;
                                            @endif
                                        @endforeach

                                        @foreach($datosEquiposNuevos as $dEn)
                                            @if($equiposNYp->idPartesVenta == $dEn->idPartesVenta)
                                                @if($dEn->marcaRefaccion !="")
                                                    <div>
                                                        MFG: {{$dEn->marcaRefaccion}}
                                                    </div>
                                                @endif
                                                @break;
                                            @endif
                                        @endforeach

                                        @if($equiposNYp->notas != "")
                                            <div>
                                                <b>Notas:</b>
                                            </div>
                                            <div>
                                                {!! nl2br(e($equiposNYp->notas))!!}
                                            </div>                                            
                                        @endif
                                    </div>
                                    <div class="Cell">
                                        @if($equiposNYp->semanasEntrega != "N/A")                                            
                                            <font SIZE=0>    
                                                {{$equiposNYp->semanasEntrega}}
                                            </font>
                                        @endif
                                    </div>
                                    <div class="Cell">
                                        <font SIZE=0>
                                            @if($tipoSesion != 'Compras')
                                                @if($equiposNYp->descuento !="")                                        
                                                    <div>
                                                        @if($equiposNYp->tipoCotizacion == "reparacion")
                                                            <strike>
                                                                {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($equiposNYp->montoEquipo,2)?>
                                                            </strike>
                                                        @else
                                                            <strike>
                                                                {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($equiposNYp->montoEquipoInicial,2)?>
                                                            </strike>
                                                        @endif
                                                    </div>                                                                                
                                                    <div>
                                                        {{$consultaCotizacion->tipoMoneda}} $
                                                        <?php 
                                                            //validando que no haga divicion entre cero y truene
                                                            if ($equiposNYp->piezas >=1) {
                                                                $descuentoUnitario = $equiposNYp->montoFinanciamiento / $equiposNYp->piezas;
                                                            }else{
                                                                $descuentoUnitario = $equiposNYp->montoFinanciamiento;
                                                            }
                                                            echo number_format ($descuentoUnitario,2);
                                                        ?>
                                                    </div>
                                                @else                                            
                                                    <div>
                                                        {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($equiposNYp->montoEquipo,2)?>
                                                    </div>
                                                @endif
                                            @endif
                                        <font>
                                    </div>
                                    <div class="Cell">
                                        <font SIZE=0>
                                            @if($tipoSesion != 'Compras')
                                                @if($equiposNYp->descuento !="")
                                                    <div>
                                                        <strike>
                                                            {{$consultaCotizacion->tipoMoneda}} $ <?php $totalff = $equiposNYp->montoEquipo * $equiposNYp->piezas; echo number_format ($totalff,2)?>
                                                        </strike>
                                                    </div>                                            
                                                    <div>
                                                        {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($equiposNYp->montoFinanciamiento,2)?>
                                                    </div>
                                                @else
                                                    <div>
                                                        {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($equiposNYp->montoEquipo * $equiposNYp->piezas,2)?>
                                                    </div>
                                                @endif
                                            @endif
                                        </font>
                                    </div>
                                @else
                                    <div class="Cell">
                                        <b>
                                            @foreach($datosEquiposNuevos as $dEn)
                                                @if($equiposNYp->idPartesVenta == $dEn->idPartesVenta)
                                                    {{$dEn->nombreRefaccion}}
                                                    @break;
                                                @endif                
                                            @endforeach
                                        </b>                                        
                                        <div>
                                            SKU:
                                            @foreach($datosEquiposNuevos as $dEn)                
                                                @if($equiposNYp->idPartesVenta == $dEn->idPartesVenta)
                                                    <?php
                                                        $codigoSeparado = explode('-',$dEn->codigo);
                                                        $quitandoCaracteres = substr($codigoSeparado[1], -7);                                                                                            
                                                    ?>
                                                    {{$codigoSeparado[0]}}-{{$quitandoCaracteres}}
                                                    @break;
                                                @endif                
                                            @endforeach
                                        </div>
                                        @if($equiposNYp->verSku == "Si")
                                            <div>
                                                SKU equivalente: {{$equiposNYp->skuEquivalente}}
                                            </div>
                                        @endif
                                        @foreach($datosEquiposNuevos as $dEn)                
                                            @if($equiposNYp->idPartesVenta == $dEn->idPartesVenta)
                                                @if($dEn->modelo !="")
                                                    <div>
                                                        Model: {{$dEn->modelo}}
                                                    </div>
                                                    @endif
                                                @break;
                                            @endif                
                                        @endforeach
                                        <div>
                                            <!-- MFG:
                                            @foreach($consultaEquiposNuevosYreparados as $cEnYr)                
                                                @if($equiposNYp->idPartesVenta == $cEnYr->idPartesVenta)
                                                    {{$cEnYr->marcaRefaccion}}
                                                    @break;
                                                @endif                
                                            @endforeach  -->
                                            @foreach($datosEquiposNuevos as $dEn)                
                                                @if($equiposNYp->idPartesVenta == $dEn->idPartesVenta)
                                                    @if($dEn->marcaRefaccion !="")
                                                        <div>
                                                            MFG: {{$dEn->marcaRefaccion}}
                                                        </div>
                                                        @endif
                                                    @break;
                                                @endif                
                                            @endforeach
                                        </div>
                                        @if($equiposNYp->notas != "")
                                            <div>
                                                <b>Notas:</b>
                                            </div>
                                            <div>
                                                {!! nl2br(e($equiposNYp->notas))!!}
                                            </div>                                            
                                        @endif
                                    </div>
                                    <div class="Cell">
                                        @if($equiposNYp->semanasEntrega != "N/A")
                                            <font SIZE=0>
                                                {{$equiposNYp->semanasEntrega}}
                                            </font>
                                        @endif
                                    </div>
                                    <div class="Cell">
                                        <font SIZE=0>
                                            @if($tipoSesion != 'Compras')
                                                @if($equiposNYp->descuento !="")
                                                    <div>
                                                        @if($equiposNYp->tipoCotizacion == "reparacion")
                                                            <strike>
                                                                {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($equiposNYp->montoEquipo,2)?>
                                                            </strike>
                                                        @else
                                                            <strike>
                                                                {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($equiposNYp->montoEquipoInicial,2)?>
                                                            </strike>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        {{$consultaCotizacion->tipoMoneda}} $ 
                                                            <?php 
                                                                //validando que no haga divicion entre cero y truene
                                                                if ($equiposNYp->piezas >=1) {
                                                                    $descuentoUnitario = $equiposNYp->montoFinanciamiento / $equiposNYp->piezas;
                                                                }else{
                                                                    $descuentoUnitario = $equiposNYp->montoFinanciamiento;
                                                                }
                                                                echo number_format ($descuentoUnitario,2);
                                                            ?>
                                                    </div>
                                                @else                                            
                                                    <div>
                                                        {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($equiposNYp->montoEquipo,2)?>
                                                    </div>                                        
                                                @endif
                                            @endif
                                        </font>
                                    </div>
                                    <div class="Cell">
                                        <font SIZE=0>
                                            @if($tipoSesion != 'Compras')
                                                @if($equiposNYp->descuento !="")
                                                    <div>
                                                        <strike>
                                                            {{$consultaCotizacion->tipoMoneda}} $ <?php $totalff = $equiposNYp->montoEquipo * $equiposNYp->piezas; echo number_format ($totalff,2)?>
                                                        </strike>
                                                    </div>
                                                    <div>
                                                        {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($equiposNYp->montoFinanciamiento,2)?>
                                                    </div>
                                                @else
                                                    <div>
                                                        {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($equiposNYp->montoEquipo * $equiposNYp->piezas,2)?>
                                                    </div>
                                                @endif
                                            @endif
                                        </font>
                                    </div>
                                @endif
                            @else
                                <div class="Cell">
                                    <b>Repair</b>
                                    <br>
                                    <b>
                                        @if($equiposNYp->tipoCotizacion == "reparacion")
                                            @foreach($consultaEquiposReparados as $c)
                                                @if($equiposNYp->idEquipos == $c->idEquipos)
                                                    {{$equiposNYp->descripcion}}
                                                    @break
                                                @endif
                                            @endforeach
                                        @endif
                                    </b>
                                    <br>
                                    ID: 
                                        @if($equiposNYp->tipoCotizacion == "reparacion")
                                            @foreach($equiposReparados as $equipoR)
                                                @if($equiposNYp->idEquipos == $equipoR->idEquipos)
                                                    {{$equipoR->GCMid}} - {{$equipoR->complementoGCMid}}
                                                    @break
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach($datosEquiposNuevos as $dEn)                
                                                @if($equiposNYp->idPartesVenta == $dEn->idPartesVenta)
                                                    {{$dEn->numeroParte}}
                                                    @break;
                                                @endif                
                                            @endforeach
                                        @endif
                                    <br>                             
                                    @foreach($consultaEquiposReparados as $c)
                                        @if($equiposNYp->idEquipos == $c->idEquipos)
                                            @if($c->modelo != "")
                                                <div>
                                                    Model: {{$c->modelo}}
                                                </div>
                                                
                                            @endif
                                        @endif
                                    @endforeach                                
                                    @foreach($consultaEquiposReparados as $c)
                                        @if($equiposNYp->idEquipos == $c->idEquipos)
                                            @if($c->serie != "")
                                                <div>
                                                    Serie: {{$c->serie}}
                                                </div>
                                                
                                            @endif
                                        @endif
                                    @endforeach                                                            
                                    @foreach($consultaEquiposReparados as $c)
                                        @if($equiposNYp->idEquipos == $c->idEquipos)                            
                                            @if($c->recurso != '')
                                                <div>
                                                    {!! nl2br(e($c->recurso))!!}                                                    
                                                </div>
                                                
                                            @endif
                                        @endif
                                    @endforeach
                                    @foreach($consultaEquiposReparados as $c)
                                        @if($equiposNYp->idEquipos == $c->idEquipos)
                                            @if($c->datosCertificado != '')
                                                <div>
                                                    {!! nl2br(e($c->datosCertificado))!!}
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach                                
                                    @foreach($consultaEquiposReparados as $c)
                                        @if($equiposNYp->idEquipos == $c->idEquipos)
                                            @if($c->marca != '')
                                                <div>
                                                    Marca: {{$c->marca}}
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                                <div class="Cell">
                                    @foreach($consultaEquiposReparados as $c)
                                        @if($equiposNYp->idEquipos == $c->idEquipos)
                                            @if($equiposNYp->semanasEntrega != "N/A")                                            
                                                <font SIZE=0>
                                                    {{$equiposNYp->semanasEntrega}}
                                                <font>
                                            @endif
                                            @break
                                        @endif
                                    @endforeach
                                </div>
                                <div class="Cell">
                                    @foreach($consultaEquiposReparados as $c)
                                        @if($equiposNYp->idEquipos == $c->idEquipos)
                                            <font SIZE=0>
                                                @if($tipoSesion != 'Compras')
                                                    @if($equiposNYp->descuento !="")                                        
                                                        <div>
                                                            @if($equiposNYp->tipoCotizacion == "reparacion")
                                                                <strike>
                                                                    {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($equiposNYp->montoEquipo,2)?>
                                                                </strike>
                                                            @else
                                                                <strike>
                                                                    {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($equiposNYp->montoEquipoInicial,2)?>
                                                                </strike>
                                                            @endif
                                                        </div>                                                                                    
                                                        <div>
                                                            {{$consultaCotizacion->tipoMoneda}} $
                                                            <?php 
                                                                //validando que no haga divicion entre cero y truene
                                                                if ($equiposNYp->piezas >=1) {
                                                                    $descuentoUnitario = $equiposNYp->montoFinanciamiento / $equiposNYp->piezas;
                                                                }else{
                                                                    $descuentoUnitario = $equiposNYp->montoFinanciamiento;
                                                                }
                                                                echo number_format ($descuentoUnitario,2);
                                                            ?>
                                                        </div>                                            
                                                    @else                                            
                                                        <div>
                                                            {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($equiposNYp->montoEquipo,2)?>
                                                        </div>                                            
                                                    @endif
                                                @endif
                                            </font>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="Cell">
                                    @foreach($consultaEquiposReparados as $c)
                                        @if($equiposNYp->idEquipos == $c->idEquipos)
                                            <font SIZE=0>
                                                @if($tipoSesion != 'Compras')
                                                    @if($equiposNYp->descuento !="")                                        
                                                        <div>
                                                            <strike>
                                                                <!-- {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($equiposNYp->montoEquipo,2)?> -->
                                                                {{$consultaCotizacion->tipoMoneda}} $ <?php $totalff = $equiposNYp->montoEquipo * $equiposNYp->piezas; echo number_format ($totalff,2)?>
                                                            </strike>
                                                        </div>                                        
                                                        <div>
                                                            {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($equiposNYp->montoFinanciamiento,2)?>
                                                        </div>
                                                    @else                                            
                                                        <div>
                                                            {{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($equiposNYp->montoEquipo * $equiposNYp->piezas,2)?>
                                                        </div>                                            
                                                    @endif
                                                    @break
                                                @endif
                                            </font>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @if($equiposNYp->tipoCotizacion == "reparacion")
                            @for ($iR=0; $iR < count($refaccionesIndividuales) ; $iR++)                                                
                                @if($equiposNYp->partida == $refaccionesIndividuales[$iR]->partida )
                                    <div class="Row">
                                        <div class="Cell"></div>
                                        <div class="Cell" style="border-left: solid 1px black;padding-left:13px"></div>
                                        <div class="Cell">
                                            <b>Refacciones: </b>                                        
                                        </div>
                                        <div class="Cell"></div>
                                        <div class="Cell"></div>
                                        <div class="Cell"></div>
                                    </div>
                                    @break    
                                @endif                                                
                            @endfor
                            @if($cuantasRefacciones >= 1)                                                                                                                        
                                @foreach($consultaEquiposReparados as $c)
                                    @if($equiposNYp->idEquipos == $c->idEquipos)                                   
                                        @foreach($partes as $p)                                        
                                            @for ($i=0; $i < count($refacciones) ; $i++)
                                                @if($refacciones[$i]->partida == $equiposNYp->partida && $p->idParte == $refacciones[$i]->idParte )
                                                    @if($refacciones[$i]->idPaquete == "")
                                                        @if($refacciones[$i]->codigo != "")
                                                            @if($refacciones[$i]->tipoProducto == "Nuevo")
                                                                <?php
                                                                    $codigoSeparado = explode('-',$refacciones[$i]->codigo);
                                                                    $quitandoCaracteres = substr($codigoSeparado[1], -5);                                                                                            
                                                                ?>                                                            
                                                                <div class="Row">
                                                                    <div class="Cell"></div>
                                                                    <div class="Cell" style="border-left: solid 1px black;padding-left:13px"></div>
                                                                    <div class="Cell">
                                                                        &nbsp;&nbsp;<b>•</b> {{$codigoSeparado[0]}}-{{$quitandoCaracteres}} - <?php echo idn_to_utf8($refacciones[$i]->nombreRefaccion) ?>
                                                                    </div>
                                                                    <div class="Cell"></div>
                                                                    <div class="Cell"></div>
                                                                    <div class="Cell"></div>
                                                                </div>
                                                            @else
                                                                <?php
                                                                    $codigoSeparado = explode('-',$refacciones[$i]->codigo);
                                                                    $quitandoCaracteres = substr($codigoSeparado[1], -7);                                                                                            
                                                                ?>                                                            
                                                                <div class="Row">
                                                                    <div class="Cell"></div>
                                                                    <div class="Cell" style="border-left: solid 1px black;padding-left:13px"></div>
                                                                    <div class="Cell">
                                                                        &nbsp;&nbsp;<b>•</b> {{$codigoSeparado[0]}}-{{$quitandoCaracteres}} - <?php echo idn_to_utf8($refacciones[$i]->nombreRefaccion) ?>
                                                                    </div>
                                                                    <div class="Cell"></div>
                                                                    <div class="Cell"></div>
                                                                    <div class="Cell"></div>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <div class="Row">
                                                                <div class="Cell"></div>
                                                                <div class="Cell" style="border-left: solid 1px black;padding-left:13px"></div>
                                                                <div class="Cell">
                                                                    &nbsp;&nbsp;<b>•</b> <?php echo idn_to_utf8($refacciones[$i]->nombreRefaccion) ?>
                                                                </div>
                                                                <div class="Cell"></div>
                                                                <div class="Cell"></div>
                                                                <div class="Cell"></div>
                                                            </div>                                                        
                                                        @endif
                                                    @endif
                                                @endif
                                            @endfor
                                        @endforeach
                                        @foreach($paquetes as $paq)
                                            @if($paq->partida == $equiposNYp->partida)
                                                <div class="Row">
                                                    <div class="Cell"></div>
                                                    <div class="Cell" style="border-left: solid 1px black;padding-left:13px"></div>
                                                    <div class="Cell">
                                                        <b>{{$paq->nombrePaquete}} (Alcance):</b>
                                                    </div>
                                                    <div class="Cell"></div>
                                                    <div class="Cell"></div>
                                                    <div class="Cell"></div>
                                                </div>
                                                @for ($i=0; $i < count($refacciones) ; $i++)
                                                    @if($paq->idPaquete == $refacciones[$i]->idPaquete)
                                                        @if($refacciones[$i]->idPaquete != "")                                                                
                                                            @if($refacciones[$i]->codigo != "")
                                                                @if($refacciones[$i]->tipoProducto == "Nuevo")
                                                                    <?php
                                                                        $codigoSeparado = explode('-',$refacciones[$i]->codigo);
                                                                        $quitandoCaracteres = substr($codigoSeparado[1], -5);                                                                                            
                                                                    ?>                                                                
                                                                    <div class="Row">
                                                                        <div class="Cell"></div>
                                                                        <div class="Cell" style="border-left: solid 1px black;padding-left:13px"></div>
                                                                        <div class="Cell">
                                                                            &nbsp;&nbsp;<b>•</b> {{$codigoSeparado[0]}}-{{$quitandoCaracteres}} - <?php echo idn_to_utf8($refacciones[$i]->nombreRefaccion) ?>
                                                                        </div>
                                                                        <div class="Cell"></div>
                                                                        <div class="Cell"></div>
                                                                        <div class="Cell"></div>
                                                                    </div>                                                                
                                                                @else
                                                                    <?php
                                                                        $codigoSeparado = explode('-',$refacciones[$i]->codigo);
                                                                        $quitandoCaracteres = substr($codigoSeparado[1], -7);                                                                                            
                                                                    ?>                                                                
                                                                    <div class="Row">
                                                                        <div class="Cell"></div>
                                                                        <div class="Cell" style="border-left: solid 1px black;padding-left:13px"></div>
                                                                        <div class="Cell">
                                                                            &nbsp;&nbsp;<b>•</b> {{$codigoSeparado[0]}}-{{$quitandoCaracteres}} - <?php echo idn_to_utf8($refacciones[$i]->nombreRefaccion) ?>
                                                                        </div>
                                                                        <div class="Cell"></div>
                                                                        <div class="Cell"></div>
                                                                        <div class="Cell"></div>
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <div class="Row">
                                                                    <div class="Cell"></div>
                                                                    <div class="Cell" style="border-left: solid 1px black;padding-left:13px"></div>
                                                                    <div class="Cell">
                                                                        &nbsp;&nbsp;<b>•</b> <?php echo idn_to_utf8($refacciones[$i]->nombreRefaccion) ?>
                                                                    </div>
                                                                    <div class="Cell"></div>
                                                                    <div class="Cell"></div>
                                                                    <div class="Cell"></div>
                                                                </div>                                                            
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endfor
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif

                            <div class="Row">
                                <div class="Cell"></div>
                                <div class="Cell" style="border-left: solid 1px black;padding-left:13px"></div>
                                <div class="Cell">
                                    @foreach($consultaEquiposReparados as $c)
                                        @if($equiposNYp->idEquipos == $c->idEquipos)
                                            @if($equiposNYp->notas != "")
                                            <div>
                                                <b>Notas:</b>
                                            </div>
                                                <div>
                                                {!! nl2br(e($equiposNYp->notas))!!}
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                                <div class="Cell"></div>
                                <div class="Cell"></div>
                                <div class="Cell"></div>
                            </div>                            
                        @endif
                        <div class="Row">
                            <div class="saltoDeFila">
                            </div>
                            <div class="saltoDeFila" style="border-left: solid 1px black;">
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
                    @endforeach
                    @if($verTotal == 'Si' && $tipoSesion != 'Compras')
                    <div class="Row">
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        <div class="Cell"></div>
                        <div class="Cell" style="text-align:right;">                            
                            <font SIZE=1 color="black">
                                <b>Total:</b>
                            </font>
                        </div>
                        <div class="Cell" style="text-align:left;">
                            <font SIZE=0 color="black">
                                <b>{{$consultaCotizacion->tipoMoneda}} $ <?php echo number_format ($consultaCotizacion->montoTotal,2)?></b>
                            </font>
                        </div>
                    </div>
                    @endif            
            </div>
            <div>
                <font SIZE=1 color="#162576">NOTES:</font>
            </div>       
            <font SIZE=1 color="#162576">
                <ol type="a">    
                    @if($consultaCotizacion->check1 == "si")
                        <li>&nbsp;{{$consultaCotizacion->textoCheck1}}
                    @endif            
                    @if($consultaCotizacion->check2 == "si")
                        <li>&nbsp;{{$consultaCotizacion->textoCheck2}}
                    @endif            
                    @if($consultaCotizacion->check3 == "si")
                        <li>&nbsp;{{$consultaCotizacion->textoCheck3}}
                    @endif            
                    @if($consultaCotizacion->check4 == "si")
                        <li>&nbsp;{{$consultaCotizacion->textoCheck4}}
                    @endif            
                    @if($consultaCotizacion->check5 == "si")
                        <li>&nbsp;{{$consultaCotizacion->textoCheck5}}
                    @endif            
                    @if($consultaCotizacion->check6 == "si")
                        <li>&nbsp;{{$consultaCotizacion->textoCheck6}}
                    @endif            
                    @if($consultaCotizacion->check7 == "si")
                        <li>&nbsp;{{$consultaCotizacion->textoCheck7}}
                    @endif
                    @if($consultaCotizacion->check8 == "si")
                        <li>&nbsp;{{$consultaCotizacion->textoCheck8}}
                    @endif
                    @if($verCambio == 'Si') 
                        @if($consultaCotizacion->tipoMoneda == 'USD')                            
                            <li>&nbsp;1 USD  = {{$consultaCotizacion->cambio}} MXN cotizado en Dólares Americanos pagado en Moneda Extrajera 
                            o bien en moneda nacional al tipo de cambio del día de pago, estipulado por 
                            el DOF
                        @endif
                    @endif
                </ol>
                <center>
                    <font SIZE=1 color="#162576">
                        <!-- <br>
                        <br> -->
                        <br>
                        <br>
                        <br>
                        ________________________________________
                        <br>
                        {{$consultaCotizacion->usuario}}
                    </font>
                </center>
            </font>
        </div>