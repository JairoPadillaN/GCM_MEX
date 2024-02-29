<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;">        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    </head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
    </style>

    <body>

    <table FRAME="border" RULES="none" width="100%">

        <tr>
            <td style="width:15% !important;vertical-align:top;" rowspan="2">
                <div style="width:230px !important; margin-left:6px;">
                    <div><font SIZE="9px">GCM ID: </font><font SIZE="10px" color="#2696DF"><b>{{$consulta->gcmid}}</b></font></div>
                    @if($consulta->recurso != "" || $consulta->recurso != "")
                        <div><font SIZE="10px" color="#2696DF">{{$consulta->recurso}}</font></div>
                    @endif
                    @if($consulta->datosCertificado != "" || $consulta->datosCertificado != "")
                        <div><font SIZE="10px" color="#2696DF">{{$consulta->datosCertificado}}</font></div>
                    @endif
                    <div><font SIZE="9px">N°S: {{$consulta->serie}}</font></div>
                    <div><font SIZE="9px">{{$consulta->equipo}}</font></div>
                    <div><font SIZE="9px">{{$consulta->marcaModelo}} </font></div>
                    <div><font SIZE="9px">CLIENTE: {{$consulta->razonSocial}} </font></div>
                    <!-- <div><font SIZE="9px">SUCURSAL: {{$consulta->sucursal}} </font></div> -->
                </div>
                <div><font SIZE="4px" color="white">salto de linea</font></div>
                <div style="width:220px !important;margin-left:6px;">
                    <div><font SIZE="9px">Fecha de recibido: {{$consulta->fecha}}</font></div>
                    <div><font SIZE="9px">Fecha de entrega: {{$datosFactura->fechaFactura}}</font></div>
                    <div>
                    
                    </div>
                </div>
            </td>

            <td style="width:15% !important;vertical-align:top;">
                <div align="right">
                    <img src="{{asset ('public/archivos/logo10.PNG')}}" height=45 width=70 style="margin-right:9px;margin-top:6px;">
                </div>
                <font SIZE="6px" color="white">salto de linea</font>
                <div style="width:250px;">
                    <img src="{{asset ('public/archivos/'.$consulta->vistaSuperior)}}" height=125 width=245>
                </div>      
            </td>

            <td style="width:17% !important; vertical-align:top;" rowspan="2">                
                <div style="margin-left:6px;">                    
                    <div>
                        <div>
                            @foreach($partes as $p)
                                @for ($i=0; $i < count($servicios) ; $i++)                                
                                    @if($p->idEquipos == $consulta->idEquipos && $p->idParte == $servicios[$i]->idParte)
                                    <div><font SIZE="9px">• {{$servicios[$i]->alcance}} - {{$servicios[$i]->nombreServTaller}} </font></div>
                                    @endif
                                @endfor
                            @endforeach  
                        </div>                                                                                                 
                    </div>                    
                </div>                
            </td>

            <td style="width:13% !important;vertical-align:top;">
                <div align="right" style="">
                    <img src="{{asset ('public/archivos/logo10.PNG')}}" height=45 width=70 style="margin-top:6px;margin-right:6px;">
                </div>
            </td>
        </tr>

        <tr>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td style="vertical-align:top;">
                <div>
                    <table style="text-align:center;font-size:7px; margin-left:6px; margin-bottom:6px;" border="1" >
                        <tr>
                            <td style="width:80px;"><center>EVALUACION</center></td>
                            <td style="width:38px;"><center>INICIAL</center></td>
                            <td style="width:38px;"><center>FINAL</center></td>
                        </tr>                       
                        <tr>
                            <td>TEST RUN</td>
                            <td>{{$consulta->testRunInicial}}</td>
                            <td>{{$consulta->testRunFinal}}</td>
                        </tr>
                        <tr>
                            <td>FEEDBACK</td>
                            <td>{{$consulta->feedbackInicial}}</td>
                            <td>{{$consulta->feedbackFinal}}</td>
                        </tr>
                        <tr>
                            <td>BALEROS</td>
                            <td>{{$consulta->balerosInicial}}</td>
                            <td>{{$consulta->balerosFinal}}</td>
                        </tr>
                        <tr>
                            <td>GEAR BOX</td>
                            <td>{{$consulta->gearBoxInicial}}</td>
                            <td>{{$consulta->gearBoxFinal}}</td>
                        </tr>
                        <tr>
                            <td>IMANES</td>
                            <td>{{$consulta->imanesInicial}}</td>
                            <td>{{$consulta->imanesFinal}}</td>
                        </tr>
                        <tr>
                            <td>DEBANADOS</td>
                            <td>{{$consulta->debanadosInicial}}</td>
                            <td>{{$consulta->debanadosFinal}}</td>
                        </tr>
                        <tr>
                            <td>ENCODER</td>
                            <td>{{$consulta->encoderInicial}}</td>
                            <td>{{$consulta->encoderFinal}}</td>
                        </tr>
                    </table>
                </div>
            </td>
            <td style="vertical-align:top;">
                <div>
                    <img src="{{asset ('public/archivos/'.$consulta->placa_1)}}" height=125 width=245 style="">
                </div>
            </td>
            <td style="vertical-align:top;">
                <div style="margin-left:6px;">
                @if($consulta->recurso != "" || $consulta->recurso != "")
                    <div><font SIZE="9px" color="#2696DF"><b>{{$consulta->recurso}}</b></font></div>
                @endif
                    <div><font SIZE="9px">ORDEN DE COMPRA: {{$datosFactura->ordenCompra}}</font></div>
                    <div><font SIZE="9px">DESCRIPCION PMI: <b>{{$consulta->equipo}}</b></font></div>
                    <div><font SIZE="9px">FACTURA: </font><font SIZE="11px" color="#BB0802"><b>{{$datosFactura->factura}}</b></font></div>
                    <div><font SIZE="9px" color="#2696DF">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>{{$datosFactura->fechaFactura}}</b></font></div>
                    <div><font SIZE="9px">PROVEEDOR: {{$consulta->numeroProveedor}}</font></div>
                </div> 
            </td>
            <td>
                <div style="text-align:right">
                    <table style="font-size:7px" border="1">
                        <tr>
                            <td></td>
                            <td colspan="3">Pruebas Finales / (60Hz)</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><center>(Sin carga)U/ver.</center></td>
                            <td><center>V/hor.</center></td>
                            <td><center>W/ax.</center></td>
                        </tr>
                        <tr>
                            <td align="right">CORRIENTE</td>
                            <td><center>{{$consulta->corrienteVer}}</center></td>
                            <td><center>{{$consulta->corrienteHor}}</center></td>
                            <td><center>{{$consulta->corrienteAx}}</center></td>

                        </tr>
                        <tr>
                            <td align="right">TENSION</td>
                            <td><center>{{$consulta->tensionVer}}</center></td>
                            <td><center>{{$consulta->tensionHor}}</center></td>
                            <td><center>{{$consulta->tensionAx}}</center></td>
                        </tr>
                        <tr>
                            <td align="right">TEMP.MOTOR</td>
                            <td colspan="3"><center>{{$consulta->tempMotor}}</center></td>
                        </tr>
                        <tr>
                            <td align="right">TEMP.TRANSMISION</td>
                            <td colspan="3"><center>{{$consulta->tempTransmision}}</center></td>
                        </tr>
                        <tr>
                            <td align="right">VIBRASION</td>
                            <td><center>{{$consulta->vibracionVer}}</center></td>
                            <td><center>{{$consulta->vibracionHor}}</center></td>
                            <td><center>{{$consulta->vibracionAx}}</center></td>
                        </tr>
                        <tr>
                            <td align="right">ENFRIAMIENTO</td>
                            <td><center>{{$consulta->enfriamientoVer}}</center></td>
                            <td><center>{{$consulta->enfriamientoHor}}</center></td>
                            <td><center>{{$consulta->enfriamientoAx}}</center></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    
    </table>
            
</body>
</html>