@extends('principalProv')
@section('contenidoProv')


<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">

        <div class="panel-body">

            <div class="panel-heading">
                <center>
                    <h1> Detalle de la orden de compra</h1>
                </center> <br><br>
                <div class="card-title">
                </div>
            </div>
            <div class="table-responsive tablaConCambios">
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
                                        <br>
                                        <!-- @if($oc->estatus != "Pagado" && $oc->estatus != "Pagado con complementos de pago" && $oc->estatus != "Cancelado")
                                            <button value="{{$oc->idOrden}}" class="modificarFechaEntrega2" style="background-color: Transparent;background-repeat:no-repeat;border: none;cursor:pointer;overflow: hidden;color:blue">
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
            </div>            

            <ul class="nav nav-tabs">
                @if($consulta[0]->estatus == "Sin programar pago")
                    <li class="active" id='tab1' style="visibility:visible;"><a href="#1" data-toggle="tab"><label for="">Agregar archivos</label></a></li>
                @else
                    <li class="active" id='tab2' style="visibility:visible;"><a href="#2" data-toggle="tab"><label for="">Evidencia de Pago</label></a></li>
                    <li class="" id='tab3' style="visibility:visible;"><a href="#3" data-toggle="tab"><label for="">Complementos de pago</label></a></li>
                @endif
            </ul>

            
        
            <div class="tab-content">
                @if($consulta[0]->estatus == "Sin programar pago")
                    <div class="tab-pane active" id="1">
                        {{Form::open(['route' => 'guardarXmlAndPdf','files'=>true])}}
                        {{Form::token()}}
                        <br>    
                        <div class="alert alert-success" role="alert">
                            <b>Subir aquí el archivo <u>PDF</u> de la factura</b>
                            {{ Form::file('pdfFacturaProveedor',['id'=>'pdfFacturaProveedor','onchange'=>'validarPdf(this);'])}}
                        </div>
                        @if($consulta[0]->idioma == "Español")
                            <div class="alert alert-success" role="alert">
                                <b>Subir aquí el archivo <u>XML</u> de la factura</b>
                                {{ Form::file('xmlFacturaProveedor',['id'=>'xmlFacturaProveedor','onchange'=>'validarXml(this);'])}}
                            </div>
                        @else
                            <input type="hidden" name="xmlFacturaProveedor">
                        @endif

                        <input type="hidden" name="idOrden" id="" value="{{$idOrden}}">
                        <input type="hidden" name="idProveedor" id="" value="{!! Session::get('sesionidproveedor')!!}">
                        <input type="hidden" value="{{$folioConfirmacion}}" name="folioConfirmacion" id="folioConfirmacion" >   
                        {{Form::submit('Guardar',['id'=>'guardar' ,'class' => 'btn  btn-info'])}}
                    </div>
                    <div>
                        <center>
                            <a href="{{asset('inicioProveedor')}}">
                                <button type="button" class="btn btn-danger">Regresar</button>
                            </a>
                        </center>
                    </div>
                @else
                    <div class="tab-pane active" id="2">
                        <br>
                        @if($consulta[0]->evidenciaTransferencia != "")
                            <a target="_blank" href="{{asset ('public/archivos/'.$consulta[0]->evidenciaTransferencia)}}">
                                @if(strpos($consulta[0]->evidenciaTransferencia,'pdf'))
                                    <i class="fa fa-file-pdf-o fa-5x" aria-hidden="true" style="color: #750404;background-color: #fff;border-color: #4d42ff59;"></i>
                                @else
                                    <img src="{{asset ('public/archivos/'.$consulta[0]->evidenciaTransferencia)}}" height=300 width=300>
                                @endif
                            </a>                            
                        @else
                            <div>
                                <h3 align="center"><span class="label label-warning">Sin archivo</span></h3>
                                <br><br>
                            </div>
                        @endif                        
                    </div>

                    <div class="tab-pane fade" id="3">
                        {{Form::open(['route' => 'guardarComplementoXmlAndPdf','files'=>true])}}
                        {{Form::token()}}
                        <br>    
                        <div class="alert alert-success" role="alert">
                            <b>Subir aquí el archivo <u>PDF</u> del complemento de pago</b>
                            {{ Form::file('pdfComplementoProveedor',['id'=>'pdfComplementoProveedor','onchange'=>'validarComplementoPdf(this);'])}}
                        </div>                        

                        @if($consulta[0]->idioma == "Español")
                        <div class="alert alert-success" role="alert">
                            <b>Subir aquí el archivo <u>XML</u> del complemento de pago</b>
                            {{ Form::file('xmlComplementoProveedor',['id'=>'xmlComplementoProveedor','onchange'=>'validarComplementoXml(this);'])}}
                        </div>
                        @else
                            <input type="hidden" name="xmlComplementoProveedor">
                        @endif

                        <input type="hidden" value="{{$consulta[0]->folioConfirmacion}}" name="folioConfirmacion" id="folioConfirmacion" >   

                        <input type="hidden" name="idOrden" id="" value="{{$idOrden}}">
                        <input type="hidden" name="idProveedor" id="" value="{!! Session::get('sesionidproveedor')!!}">
                        {{Form::submit('Guardar',['id'=>'guardarComplementos' ,'class' => 'btn  btn-info'])}}
                    </div>
                    <div>
                        <center>
                            <a href="{{asset('inicioProveedor')}}">
                                <button type="button" class="btn btn-danger">Regresar</button>
                            </a>
                        </center>
                    </div>
                @endif
            </div>
            
            <div id="cargarModalFechaP2"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $(".cargar").click();
});
</script>

<script>
    // $(".modificarFechaEntrega2").click(function (e) {
    //     var idOrden = $(this).val();
    //     var tipoReporte= '2';                
    //     $('#cargarModalFechaP2').load('{{url('modificarFechaEntregaProveedor')}}' + '?idOrden='+idOrden + '&tipoReporte=' + tipoReporte);
    //     e.preventDefault(); 
    // });

    function validarPdf(obj) {
        var uploadFile = obj.files[0];        
        if (!(/\.(pdf)$/i).test(uploadFile.name)) {
            alert('Error: Solo archivos PDF');
            $('#pdfFacturaProveedor').val('');
        }
    }    
</script>
<script>    
    function validarXml(obj) {
        var uploadFile = obj.files[0];        
        if (!(/\.(xml)$/i).test(uploadFile.name)) {
            alert('Error: Solo archivos XML');
            $('#xmlFacturaProveedor').val('');
        }
    }
</script>
<script>
    $("#guardar").click(function(e) {
        let pdfFacturaProveedor = $("#pdfFacturaProveedor").val();
        let xmlFacturaProveedor = $("#xmlFacturaProveedor").val();
        
        if (pdfFacturaProveedor == '' || xmlFacturaProveedor == ''){
            if (pdfFacturaProveedor == ''){
                $("#pdfFacturaProveedor").css('border-color', 'red');
                $("#pdfFacturaProveedor").css('color', 'red');            
                alert('El *Archivo PDF es obligatorio.');
                e.preventDefault(); 
            }

            
            if (xmlFacturaProveedor == ''){
                $("#xmlFacturaProveedor").css('border-color', 'red');
                $("#xmlFacturaProveedor").css('color', 'red');            
                alert('El *Archivo XML es obligatorio.');
                e.preventDefault(); 
            }
        }
    });
</script>


<script>    
    function validarComplementoPdf(obj) {
        var uploadFile = obj.files[0];        
        if (!(/\.(pdf)$/i).test(uploadFile.name)) {
            alert('Error: Solo archivos PDF');
            $('#pdfComplementoProveedor').val('');
        }
    }    
</script>
<script>    
    function validarComplementoXml(obj) {
        var uploadFile = obj.files[0];        
        if (!(/\.(xml)$/i).test(uploadFile.name)) {
            alert('Error: Solo archivos XML');
            $('#xmlComplementoProveedor').val('');
        }
    }
</script>
<script>
    $("#guardarComplementos").click(function(e) {
        let pdfComplementoProveedor = $("#pdfComplementoProveedor").val();
        let xmlComplementoProveedor = $("#xmlComplementoProveedor").val();
        
        if (pdfComplementoProveedor == '' || xmlComplementoProveedor == ''){
            if (pdfComplementoProveedor == ''){
                $("#pdfComplementoProveedor").css('border-color', 'red');
                $("#pdfComplementoProveedor").css('color', 'red');            
                alert('El *Archivo PDF es obligatorio.');
                e.preventDefault(); 
            }

            
            if (xmlComplementoProveedor == ''){
                $("#xmlComplementoProveedor").css('border-color', 'red');
                $("#xmlComplementoProveedor").css('color', 'red');            
                alert('El *Archivo XML es obligatorio.');
                e.preventDefault(); 
            }
        }
    });
</script>

@stop