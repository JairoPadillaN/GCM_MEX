<!-- Trigger the modal with a button -->
<div hidden>
    <button id="activarModal" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4><center>Detalle</center></h4>
            </div>
            <div class="modal-body">
                <div class="row">                    
                    <div class="col-md-3">
                        <center> <div class="sub-title">Datos de entrega</div></center>
                        <div class="sub-title">Notas:</div>
                        <div>
                        {{Form::textarea('notasOrden', $consulta->notasOrden,['class'=>'form-control', 'readonly','rounded 0', 'rows' => '2'])}}
                        </div>  

                        <div class="sub-title">Domicilio de entrega:</div>
                        <div>
                        {{Form::textarea('domicilioEntrega', $consulta->domicilioEntrega,['class'=>'form-control', 'readonly','rounded 0', 'rows' => '2'])}}
                        </div> 
                        <div class="sub-title">Método de entrega:</div>
                        <div>
                        {{Form::textarea('metodoEntrega', $consulta->metodoEntrega,['class'=>'form-control', 'readonly','rounded 0', 'rows' => '2'])}}
                        </div> 

                        <div class="sub-title">Detalle de entrega:</div>
                        <div>
                        {{Form::textarea('detalleEntrega', $consulta->detalleEntrega,['class'=>'form-control', 'readonly','rounded 0', 'rows' => '2'])}}
                        </div> 
                        <br>

                        <div class="row">
                            <div class="col-sm-6">
                                <center>
                                <div>
                                    <div style='text-align'>
                                        @foreach($archivoPaq1 as $ap1)
                                        @if($ap1->archivoPaqueteria1=='Sin archivo' || $ap1->archivoPaqueteria1=='')
                                            <img src="{{asset('img/sinArch.png')}}" height=70 width=70>
                                            <h6>Sin evidencia</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$ap1->archivoPaqueteria1)}}">
                                            <img src="{{asset('img/package.png')}}" height=80 width=80>
                                            <h6>Descargar evidencia</h6>
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                </center>
                            </div>
                            <div class="col-sm-6">
                                <center>
                                <div>
                                    <div style='text-align'>
                                        @foreach($archivoPaq2 as $ap2)
                                        @if($ap2->archivoPaqueteria2=='Sin archivo' || $ap2->archivoPaqueteria2=='')
                                            <img src="{{asset('img/sinArch.png')}}" height=70 width=70>
                                            <h6>Sin evidencia</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$ap2->archivoPaqueteria2)}}">
                                            <img src="{{asset('img/package.png')}}" height=80 width=80>
                                            <h6>Descargar evidencia</h6>
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                </center>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <center>
                                <div>
                                    <div style='text-align'>
                                        @foreach($archivoPaq3 as $ap3)
                                        @if($ap3->archivoPaqueteria3=='Sin archivo' || $ap3->archivoPaqueteria3=='')
                                            <img src="{{asset('img/sinArch.png')}}" height=70 width=70>
                                            <h6>Sin evidencia</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$ap3->archivoPaqueteria3)}}">
                                            <img src="{{asset('img/package.png')}}" height=80 width=80>
                                            <h6>Descargar evidencia</h6>
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                </center>
                            </div>
                            <div class="col-sm-6">
                                <center>
                                <div>
                                    <div style='text-align'>
                                        @foreach($archivoPaq4 as $ap4)
                                        @if($ap4->archivoPaqueteria4=='Sin archivo' || $ap4->archivoPaqueteria4=='')
                                            <img src="{{asset('img/sinArch.png')}}" height=70 width=70>
                                            <h6>Sin evidencia</h6>
                                        @else
                                        <a target="_blank" href="{{asset('archivos/'.$ap4->archivoPaqueteria4)}}">
                                            <img src="{{asset('img/package.png')}}" height=80 width=80>
                                            <h6>Descargar evidencia</h6>
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                </center>
                            </div>
                        </div>
                        
                    </div>                
                    <center>
                    <div class="col-md-3">
                        <div class="sub-title">Archivo XML y PDF</div>
                        <br>
                            <div>
                                <div style='text-align'>
                                    @foreach($pdfProv as $pdfP)
                                    @if($pdfP->pdfFacturaProveedor=='Sin archivo' || $pdfP->pdfFacturaProveedor=='')
                                        <img src="{{asset('img/sinArch.png')}}" height=90 width=90>
                                        <h6>Sin PDF</h6>
                                    @else
                                    <a target="_blank" href="{{asset('archivos/'.$pdfP->pdfFacturaProveedor)}}">
                                        <img src="{{asset('img/iconpdfdown.png')}}" height=100 width=100>
                                        <h6>Descargar PDF</h6>
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </div>

                        <br>
                            <div>
                                <div style='text-align'>
                                    @foreach($xmlProv as $xmlP)
                                    @if($xmlP->xmlFacturaProveedor=='Sin archivo' || $xmlP->xmlFacturaProveedor=='')
                                        <img src="{{asset('img/sinArch.png')}}" height=90 width=90>
                                        <h6>Sin XML</h6>
                                    @else
                                    <a target="_blank" href="{{asset('archivos/'.$xmlP->xmlFacturaProveedor)}}">
                                        <img src="{{asset('img/iconxmldown.png')}}" height=100 width=100>
                                        <h6>Descargar XML</h6>
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                    </div>
                    </center>

                    <center>
                    <div class="col-md-3">
                        <div class="sub-title">Archivo de transferencia</div>
                        <br>
                            <div>
                                <div style='text-align'>
                                    @foreach($transf as $transf)
                                    @if($transf->evidenciaTransferencia=='Sin archivo' || $transf->evidenciaTransferencia=='')
                                        <img src="{{asset('img/sinArch.png')}}" height=90 width=90>
                                        <h6>Sin archivo de transferecia</h6>
                                    @else
                                    <a target="_blank" href="{{asset('archivos/'.$transf->evidenciaTransferencia)}}">
                                        <img src="{{asset('img/transferenciadown.png')}}" height=100 width=100>
                                        <h6>Descargar transferencia</h6>
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                    </div>
                    </center>

                    <center>
                    <div class="col-md-3">
                        <div class="sub-title">Archivos de complemento de pago</div>
                        <br>
                            <div>
                                <div style='text-align'>                                        
                                    @foreach($pdfComp as $pdfC)
                                    @if($pdfC->pdfComplementoProveedor=='Sin archivo' || $pdfC->pdfComplementoProveedor=='')
                                        <img src="{{asset('img/sinArch.png')}}" height=90 width=90>
                                        <h6>Sin PDF</h6>
                                    @else
                                    <a target="_blank" href="{{asset('archivos/'.$pdfC->pdfComplementoProveedor)}}">
                                        <img src="{{asset('img/iconpdfdown.png')}}" height=100 width=100>
                                        <h6>Descargar PDF</h6>
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </div>

                            <br>
                            <div>
                                <div style='text-align'>
                                    @foreach($xmlComp as $xmlC)
                                    @if($xmlC->xmlComplementoProveedor=='Sin archivo' || $xmlC->xmlComplementoProveedor=='')
                                        <img src="{{asset('img/sinArch.png')}}" height=90 width=90>
                                        <h6>Sin XML</h6>
                                    @else
                                    <a target="_blank" href="{{asset('archivos/'.$xmlC->xmlComplementoProveedor)}}">
                                        <img src="{{asset('img/iconxmldown.png')}}" height=100 width=100>
                                        <h6>Descargar XML</h6>
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </center>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

    <script>
    $(document).ready(function() {
        $("#activarModal").click();
    });
    </script>