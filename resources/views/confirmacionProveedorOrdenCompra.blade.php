@extends('principalProv')
@section('contenidoProv')


<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">

        <div class="panel-body">

            <div class="panel-heading">
                <center>
                    <h1> Confirmación de la orden de compra</h1>
                </center> <br><br>                
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
            </div>

            <ul class="nav nav-tabs">                
                <li class="active" id='tab1' style="visibility:visible;"><a href="#1" data-toggle="tab"><label for="">Detalles</label></a></li>
            </ul>

            
        
            <div class="tab-content">
                <div class="tab-pane active" id="1">
                    {{Form::open(['route' => 'guardarConfirmacionOrdenCompra','files'=>true])}}
                    {{Form::token()}}
                    <br>
                    
                    @if($siNo == 'si')
                        @if($consulta[0]->fechaEntregaProveedor > "0000-00-00")
                            <div class="alert alert-secondary" role="alert" style="background-color:#EBE9F1">                        
                                <div class="row">
                                    <div class="col-md-2">
                                        <input type="hidden" name="idProveedor" id="idProveedor" value="{{$consulta[0]->idProveedor}}" class='form-control'>
                                        <b>Metodo de entrega: </b>
                                        <div><input type="radio" name="metodoEntrega" id="" value='Ocurre'> Ocurre</div>
                                        <div><input type="radio" name="metodoEntrega" id="" value='Entrega a domicilio'> Entrega a domicilio</div>
                                        <div><input type="radio" name="metodoEntrega" id="" value='Entrega en oficina'> Entrega en oficina</div>
                                    </div>
                                    <div class="col-md-5">
                                        <b>Detalles de entrega: </b>
                                        <textarea name="detalleEntrega" rows="3" class="form-control rounded-0" placeholder="Escribe aquí más detalles de la entrega."></textarea>
                                    </div>                        
                                    <div class="col-md-5">
                                        <b>Archivos para guía de entrega:</b>
                                        <div>{{ Form::file('archivoPaqueteria1',['id'=>'archivoPaqueteria1'])}}</div>
                                        <div>{{ Form::file('archivoPaqueteria2',['id'=>'archivoPaqueteria2'])}}</div>
                                        <div>{{ Form::file('archivoPaqueteria3',['id'=>'archivoPaqueteria3'])}}</div>
                                        <div>{{ Form::file('archivoPaqueteria4',['id'=>'archivoPaqueteria4'])}}</div>
                                    </div>
                            
                                    <input type="hidden" name="idOrden" id="" value="{{$idOrden}}">
                                    <input type="hidden" name="paso" id="" value="2">
                                    <input type="hidden" name="siNo" id="" value="{{$siNo}}">
                                </div>
                            </div>
                        @else   
                            <div class="alert alert-secondary" role="alert" style="background-color:#EBE9F1">                        
                                <div class="row">
                                    <div class="col-md-3">
                                        <b>Fecha de entrega del pedido:</b>
                                        <input type="date" name="fechaEntregaProveedor" id="fechaEntrega" class='form-control'>
                                        <input type="hidden" name="idProveedor" id="idProveedor" value="{{$consulta[0]->idProveedor}}" class='form-control'>
                                    </div>
                                    <div class="col-md-3">
                                        <b>Cotización del proveedor: </b>
                                        <a target="_blank" href="{{asset('archivos/'.$consulta[0]->cotizacionProveedor)}}">
                                            <img src="{{asset('img/iconpdfdown.png')}}" height=100 width=100>
                                        </a>
                                    </div>
                                    <input type="hidden" name="idOrden" id="" value="{{$idOrden}}">
                                    <input type="hidden" name="paso" id="" value="1">                            
                                    <input type="hidden" name="siNo" id="" value="{{$siNo}}">
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-secondary" role="alert" style="background-color:#F6E1DF">
                            <div class="row">
                                <div class="col-md-8">
                                    <b>Describa el motivo por el cual no acepta la orden de compra: </b>                                    
                                    <textarea name="motivoRechazoOrden" rows="3" class="form-control rounded-0" placeholder="Escribe aquí más detalles de la entrega."></textarea>
                                </div>
                                <div class="col-md-4">
                                    <b>Cotización del proveedor: </b>
                                    <a target="_blank" href="{{asset('archivos/'.$consulta[0]->cotizacionProveedor)}}">
                                        <img src="{{asset('img/iconpdfdown.png')}}" height=100 width=100>
                                    </a>
                                </div>
                                <input type="hidden" name="idOrden" id="" value="{{$idOrden}}">
                                <input type="hidden" name="siNo" id="" value="{{$siNo}}">
                            </div>
                        </div>
                    @endif
                    
                    {{Form::submit('Guardar',['id'=>'guardar' ,'class' => 'btn  btn-info'])}}                                                        
                    <div>
                        <center>
                            <a href="{{asset('inicioProveedor')}}">
                                <button type="button" class="btn btn-danger">Regresar</button>
                            </a>
                        </center>
                    </div>
                </div>
                <div id="cargarModalFechaP3"></div>
            </div>
        </div>
    </div>
</div>

<script>
    // $(".modificarFechaEntrega3").click(function (e) {
    //     var idOrden = $(this).val();
    //     var tipoReporte= '2';                
    //     $('#cargarModalFechaP3').load('{{url('modificarFechaEntregaProveedor')}}' + '?idOrden='+idOrden + '&tipoReporte=' + tipoReporte);
    //     e.preventDefault(); 
    // });
</script>

<script>    
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
        let fechaEntrega = $("#fechaEntrega").val();
        // let xmlFacturaProveedor = $("#xmlFacturaProveedor").val();
            
        if (fechaEntrega == ''){
            $("#fechaEntrega").css('border-color', 'red');
            $("#fechaEntrega").css('color', 'red');            
            alert('La fecha de entrega es obligatorio.');
            e.preventDefault(); 
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