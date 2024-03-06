<!-- Este boton se le da clic automaticamente con el script de abajo para que abra el modal-->
<button type="button" class="" data-toggle="modal" data-target="#exampleModal" id="activar" style= "visibility:hidden">
</button>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div style="text-align:right">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close" onclick="cerrarM()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <form action="">
                    <div id="actualizandoDatosPago">
                        <input type="hidden" name="idDatosPago" value="{{$idDatosPago}}">
                        <input type="hidden" name="idFactura" value="{{$idFactura}}">
                        <div class="row" style="background-color:#F3FAF9">
                            <div>
                                <center>
                                    <br>
                                    <b>MODIFICAR MONTO DE PAGO</b>
                                    <br>
                                </center>
                            </div>

                            <div class="form-group col-md-6">
                        <center><div class="sub-title">Datos de pago:</div></center>
                            <div class="sub-title">Número de factura:</div>
                            <div>
                                @if($errors->first('numeroFacturaDP'))
                                <i> {{ $errors->first('numeroFacturaDP') }}</i>
                                @endif
                                {{Form::text('numeroFacturaDP',$datosPago->numeroFacturaDP,['class' => 'form-control'])}}
                            </div>

                            <hr> <!-- linea de division -->

                            <div class="sub-title" style="text-align:center">Monto Facturado</div>
                            <div class="sub-title">Fecha de Factura:</div>
                                        <div>
                                             {{Form::date('fechafact',$datosPago->fechafactura,['class' => 'form-control'])}}
                                        </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                <div class="sub-title">Tipo de cambio facturado:</div>
                                    <div>
                                        @if($errors->first('tipoCambioFac'))
                                        <i> {{ $errors->first('tipoCambioFac') }}</i>
                                        @endif
                                        {{Form::text('tipoCambioFac',$datosPago->tipoCambioFac,[
                                        'class' => 'form-control numeric-input',//<- Identifica a los campos numericos xd
                                        'placeholder' => 'Ejemplo: 40.15',
                                        'id' => 'valorCambioM',
                                        ])}}
                                    </div>

                                    <div class="sub-title">Subtotal:</div>
                                        <div>
                                            @if($errors->first('subtotalFac'))
                                            <i> {{ $errors->first('subtotalFac') }}</i>
                                            @endif
                                            {{Form::text('subtotalFac',$datosPago->subtotalFac,['class' => 'form-control',
                                            'id' => 'subFacturadoM',
                                            'readonly'])}}
                                        </div>
                                </div>
                                <br>

                                <div class="form-group col-md-4">
                                    <div class="sub-title">*Saldo:</div>
                                        <div>
                                            @if($errors->first('saldoFac'))
                                            <i> {{ $errors->first('saldoFac') }}</i>
                                            @endif
                                            {{Form::text('saldoFac',$datosPago->saldoFac,[
                                            'class' => 'form-control numeric-input',//<- este tambien es numerico
                                            'placeholder' => 'Ejemplo: 3600',
                                            'id' => 'valorSaldoM'])}}
                                        </div>
                                    <div class="sub-title">IVA:</div>
                                        <div>
                                            @if($errors->first('ivaFac'))
                                            <i> {{ $errors->first('ivaFac') }}</i>
                                            @endif
                                            {{Form::text('ivaFac',$datosPago->ivaFac,[
                                                'class' => 'form-control',
                                                'id' => 'ivaFacturadoM',
                                                'readonly'])}}
                                        </div>
                                </div>

                            <div class="form-group col-md-4">
                                <br><br><br><br>
                                <div>
                                   <input type="hidden" value="-">
                                </div>

                                <div class="sub-title">Monto Pesos:</div>
                                <div>
                                    @if($errors->first('montoPesosDP'))
                                    <i> {{ $errors->first('montoPesosDP') }}</i>
                                    @endif
                                    {{Form::text('montoPesosDP',$datosPago->montoPesosDP,['class' => 'form-control', 'id' => 'resultadoMontoM','readonly'])}}
                                </div>
                            </div>

                        </div>
                            <hr> <!-- linea de division -->
                            <div class="sub-title">Pagado</div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    @if ($datosPago->pagada == 'Si')
                                     {{Form::radio('pagada','Si', true, ['checked' => 'checked']) }}&nbsp;<b>Si<b>&nbsp;&nbsp;&nbsp;
                                     {{Form::radio('pagada','No',false, [])}}&nbsp;<b>No<b>
                                    @elseif ($datosPago->pagada == 'No')
                                     {{Form::radio('pagada','Si', true, []) }}&nbsp;<b>Si<b>&nbsp;&nbsp;&nbsp;
                                     {{Form::radio('pagada','No',false, ['checked' => 'checked'])}}&nbsp;<b>No<b>
                                    @else
                                     {{Form::radio('pagada','Si')}}&nbsp;<b>Si<b>&nbsp;&nbsp;&nbsp;
                                     {{Form::radio('pagada','No')}}&nbsp;<b>No<b>
                                    @endif
                                </div>

                            </div>

                            <hr> <!-- linea de division -->
                    </div>


                    <div class="form-group col-md-6">
                        <div class="row">
                            <br>
                           <center> <div class="sub-title">Fecha de pago:</div></center>
                        <div>
                            {{Form::date('fechaDatosPago',$datosPago->fechaDatosPago,['class' => 'form-control'])}}
                        </div>
                        <hr>
                            <div class="sub-title" style="text-align:center">Monto real</div>
                            <div class="form-group col-md-4">
                                <div class="sub-title">Tipo de cambio pagado:</div>
                                <div>
                                    @if($errors->first('tipoCambioPagado'))
                                    <i> {{ $errors->first('tipoCambioPagado') }}</i>
                                    @endif
                                    {{Form::text('tipoCambioPagado',$datosPago->tipoCambioPagado,[
                                        'class' => 'form-control numeric-input',//<- Y este
                                        'placeholder' => 'Ejemplo: 19.30',
                                        'id' => 'tcPagM'])}}
                                </div>

                                <div class="sub-title">Subtotal:</div>
                                <div>
                                    @if($errors->first('subtotalFinal'))
                                    <i> {{ $errors->first('subtotalFinal') }}</i>
                                    @endif
                                    {{Form::text('subtotalFinal',$datosPago->subtotalFinal,['class' => 'form-control', 'placeholder' => 'Ejemplo: 40.15', 'id' => 'subtotalFinalM', 'readonly'])}}
                                </div>

                            </div>

                            <br>
                            <div class="form-group col-md-4">
                                    <div class="sub-title">*Saldo:</div>
                                        <div>
                                            @if($errors->first('saldoReal'))
                                            <i> {{ $errors->first('saldoReal') }}</i>
                                            @endif
                                            {{Form::text('saldoReal',$datosPago->saldoReal,[
                                            'class' => 'form-control numeric-input',//<- Tambien este
                                            'placeholder' => 'Ejemplo: 3600',
                                            'id' => 'valorSaldoRM'])}}
                                        </div>

                                    <div class="sub-title">IVA:</div>
                                        <div>
                                            @if($errors->first('ivaFinal'))
                                            <i> {{ $errors->first('ivaFinal') }}</i>
                                            @endif
                                            {{Form::text('ivaFinal',$datosPago->ivaFinal,['class' => 'form-control', 'placeholder' => 'Ejemplo: 40.15', 'id' => 'ivaFinalM', 'readonly'])}}
                                        </div>
                            </div>

                            <div class="form-group col-md-4">
                                <br><br><br><br>
                                <div>
                                   <input type="hidden" value="-">
                                </div>
                                <div class="sub-title">Monto Real:</div>
                                    <div>
                                        @if($errors->first('montoReal'))
                                        <i> {{ $errors->first('montoReal') }}</i>
                                        @endif
                                        {{Form::text('montoReal',$datosPago->montoReal,['class' => 'form-control', 'id' => 'mRealM', 'readonly'])}}
                                    </div>
                            </div>


                        </div>
                        <hr> <!-- linea de division -->
                            <div class="sub-title">Diferencia cambiaría:</div>
                                        <div>
                                            @if($errors->first('difCambiaria'))
                                            <i> {{ $errors->first('difCambiaria') }}</i>
                                            @endif
                                            {{Form::text('difCambiaria',$datosPago->difCambiaria,['class' => 'form-control', 'placeholder' => 'Ejemplo: 3600', 'id' => 'difM','readonly'])}}
                                        </div>
                            <div class="sub-title">Observaciones:</div>
                            <div>
                                @if($errors->first('observaciones'))
                                <i> {{ $errors->first('observaciones') }}</i>
                                @endif
                                {{Form::textarea('observaciones',$datosPago->observaciones,['class' => 'form-control', 'rounded-0', 'placeholder'=>'Escribe las observaciones que consideres necesarias.'])}}

                            </div>

                            <br><br>
                            <div class="form-group row" style="display:block;">
                                <div class="col-md-6" style="">
                                    <button type="button" class="btn btn-success btn-sm form-control rounded-0 guardarDatosPagoEdit">
                                        <span class="glyphicon glyphicon-plus-sign"></span> Guardar cambios
                                    </button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div id="actualizacionOk" style="display:none;">
                        <br>
                        <div class="alert alert-success" role="alert">
                            <center>
                                Datos de pago modificados correctamente.
                            </center>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.sinBorder{
    /* border-top: 0px;
    border-right: 0px;
    border-bottom: 1px solid black;
    border-left: 0px; */
    border-style: none;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    //Funcion para evitar que se escriban otro tipo de dato en los campos (en teoria)
    $(document).ready(function() {
        $('.numeric-input').on('input', function() {
            // Eliminar caracteres no numéricos y asegurarse de que solo haya un punto decimal dentro del campo
            this.value = this.value.replace(/[^0-9.]/g, '');
            this.value = this.value.replace(/(\..*)\./g, '$1');

            // Actualizar el valor del campo de entrada al instante
            $(this).val(this.value);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
        // $(".modal").removeClass("modal fade bd-example-modal-lg in");
        $("#activar").click();
    });

    function cerrarM() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
        // $(".modal").removeClass("modal fade bd-example-modal-lg in");
    }


    $(".guardarDatosPagoEdit").click(function() {
            $("#reporteDatosPago").load('{{url('guardarDatosPagoEdit')}}' + '?' + $(this).closest('form').serialize());
            $('#actualizandoDatosPago').css('display', 'none');
            $('#actualizacionOk').css('display', 'block');
            $(".modal-open").css('padding-right', '');
            $(".modal-open").removeClass("modal-open");
            $(".modal-backdrop").removeClass("modal-backdrop fade in");
    });

      //calculos del carrito de datos de pago facturados
      $("#valorCambioM, #valorSaldoM").change(function(){
        var valorCambioM = $("#valorCambioM").val();
        var valorSaldoM = $("#valorSaldoM").val();
        var total=valorCambioM*valorSaldoM;
        var subtotal = (total/1.16);
        var iva = (total - subtotal);
        totalF=total.toFixed(2);
        ivaF = iva.toFixed(2);
        subF = subtotal.toFixed(2);
        document.getElementById("resultadoMontoM").value = totalF;
        document.getElementById("ivaFacturadoM").value = ivaF;
        document.getElementById("subFacturadoM").value = subF;
    });

     //calculos del carrito de datos de pago reales
     $("#valorSaldoRM,#tcPagM").change(function(){
        var valorSaldoRM = $("#valorSaldoRM").val();
        var tipoCam = $("#tcPagM").val();
        var totalRe=valorSaldoRM * tipoCam; //montoreal
        var subtotalR = (totalRe / 1.16); //subtotalreal
        var ivaR = (totalRe - subtotalR); //ivareal
        totalR=totalRe.toFixed(2);//montoreal
        ivaR = ivaR.toFixed(2); //ivaReal
        subR = subtotalR.toFixed(2); //SUBTOTALR
        document.getElementById("mRealM").value = totalR;//montoreal
        document.getElementById("ivaFinalM").value = ivaR;
        document.getElementById("subtotalFinalM").value = subR;
    });

     //diferencia cambiaría
     $("#valorSaldoM,#valorSaldoRM,#resultadoMontoM,#mRealM,#ivaFacturadoM,#ivaFinalM,#tcPagM,#valorCambioM").change(function(){
        //  alert("hi");
        var montoPesos = $("#resultadoMontoM").val();
        var montoReal = $("#mRealM").val();
        var diferencia = (montoReal - montoPesos); //ivareal
        difCam = diferencia.toFixed(2); //SUBTOTALR
        document.getElementById("difM").value = difCam;
    });

</script>
