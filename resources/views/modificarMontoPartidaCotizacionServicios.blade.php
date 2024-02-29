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
                    <div id="actualizandoCosto">
                        <input type="hidden" name="idCotizacionServicios" value="{{$idCotizacionServicios}}">
                        <input type="hidden" name="idDetalleCotizacionServ" value="{{$idDetalleCotizacionServ}}">
                        <div class="row" style="background-color:#F3FAF9">
                            <div>
                                <center>
                                    <br>
                                    <b>MODIFICAR COSTO</b>
                                    <br>
                                    <br>
                                </center>
                            </div>

                            <div class="col-sm-6">
                                <div>*Nuevo costo unitario:</div>
                                {{Form::text('montoServicioOriginal',$datos[0]->montoServicioOriginal,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390.50','id'=>'costoUnitarioMC','onblur'=>'costoYcantidad()'])}}
                                <br>
                            </div>
                            <div class="col-sm-6">
                                <div>*cantidad:</div>
                                {{Form::text('cantidad',$datos[0]->cantidad,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390.50','id'=>'cantidadMC','onblur'=>'costoYcantidad()'])}}
                                <br>
                            </div>

                            <div class="col-sm-6">
                                <div class=""><b>Días de pago:</b></div>
                                <div class="">
                                    <select class="form-control rounded-0" name="diasDePago" id="dias_mc" onchange=calcularFinanciamiento_mc()>
                                        <option value="{{$datos[0]->diasPago}}" selected>{{$datos[0]->diasPago}}</option>    
                                        <option value="0">0</option>
                                        <option value="30">30</option>                        
                                        <option value="60">60</option>
                                        <option value="90">90</option>
                                        <option value="120">120</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class=""><b>*Semanas de entrega:</b></div>
                                <div class="">
                                    <select name='semanasEntrega' id='semanasEntrega' class="form-control" onchange=calcularFinanciamiento_mc() onblur=calcularFinanciamiento_mc()>
                                        <option value="{{$datos[0]->semanasEntrega}}" selected>{{$datos[0]->semanasEntrega}}</option>
                                        <option value='N/A'>N/A</option>
                                        <option value='Stock'>Stock</option>
                                        <option value='2-4 días hábiles (bussines days)'>2-4 días hábiles (bussines days)</option>
                                        <option value='De 3 a 5 días(Days)'>De 3 a 5 días(Days)</option>
                                        <option value='5-7 días hábiles (bussines days)'>5-7 días hábiles (bussines days)</option>
                                        <option value='7-11 días hábiles (bussines days)'>7-11 días hábiles (bussines days)</option>
                                        <option value='11-15 días hábiles (bussines days)'>11-15 días hábiles (bussines days)</option>
                                        <option value='4 semanas (Weeks)'>4 semanas (Weeks)</option>
                                        <option value='5 semanas (Weeks)'>5 semanas (Weeks)</option>
                                        <option value='6 semanas (Weeks)'>6 semanas (Weeks)</option>
                                        <option value='7 semanas (Weeks)'>7 semanas (Weeks)</option>
                                        <option value='8 semanas (Weeks)'>8 semanas (Weeks)</option>
                                        <option value='9 semanas (Weeks)'>9 semanas (Weeks)</option>
                                        <option value='10 semanas (Weeks)'>10 semanas (Weeks)</option>
                                        <option value='10 a 12 semanas (Weeks)'>10 a 12 semanas (Weeks)</option>
                                        <option value='12 a 14 semanas (Weeks)'>12 a 14 semanas (Weeks)</option>
                                        <option value='13 a 15 semanas (Weeks)'>13 a 15 semanas (Weeks)</option>
                                    </select>
                                </div>
                            </div>
                            <br><br><br><br><br><br><br><br>
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6">
                                <div class=""><b>Notas:</b>                        
                                    <textarea name="notas" id="" class="form-control rounded-0" rows="4" onblur="">{{$datos[0]->notas}}</textarea>
                                </div>
                            </div>
                            <br><br><br><br><br><br><br><br>
                            <div class="col-sm-6" style="">
                                <div><b>*Sin financiamiento:</b></div>
                                {{Form::text('montoServicioOriginal',$datos[0]->montoServicioOriginal * $datos[0]->cantidad,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390.50','id'=>'totalFinMC', 'readonly'])}}
                                <!-- {{Form::text('montoServicioOriginal','0',['class' => 'form-control rounded-0','id'=>'conFinanciamiento_mc','onblur'=>'calcularPorcentaje_mc()','readonly'])}} -->
                            </div>
                            <div class="col-sm-6">
                                <div style="">Total:</div>
                                {{Form::text('montoEquipo',$datos[0]->montoEquipo * $datos[0]->cantidad,['class' => 'form-control rounded-0','id'=>'conFinanciamiento_mc','readonly'])}}
                                <!-- {{Form::text('montoEquipo','',['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390.50','id'=>'totalFinMC', 'readonly'])}} -->
                                <br>
                            </div>
                            <div class="col-sm-6" style="">
                            </div>
                            <div class="col-sm-6" style="">
                                <div><b>Aplicar descuento:</b></div>
                                @if($datos[0]->aplicaDescuento == "Si")
                                    Si <input type="radio" name="aplicaDescuento" id="siAplicaMC" value="Si" checked>&nbsp;&nbsp;&nbsp;&nbsp;
                                    No <input type="radio" name="aplicaDescuento" id="noAplicaMC" value="No" >&nbsp;&nbsp;&nbsp;&nbsp;
                                @else
                                    Si <input type="radio" name="aplicaDescuento" id="siAplicaMC" value="Si" >&nbsp;&nbsp;&nbsp;&nbsp;
                                    No <input type="radio" name="aplicaDescuento" id="noAplicaMC" value="No" checked>&nbsp;&nbsp;&nbsp;&nbsp;
                                @endif
                            </div>                                            
                        </div>
                        <br>
                        @if($datos[0]->aplicaDescuento == "Si")
                            <div class="form-group row" id="divSinDescuentoMC" style="display:none">
                        @else
                            <div class="form-group row" id="divSinDescuentoMC" style="display:block">
                        @endif
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3" style="">
                            </div>
                            <div class="col-sm-6" style="">
                                <button type="button" class="btn btn-success btn-sm form-control rounded-0 guardarModificacionCostoMC">
                                    <span class="glyphicon glyphicon-plus-sign"></span> Guardar cambio
                                </button>
                            </div>
                        </div>                        

                        @if($datos[0]->aplicaDescuento == "Si")
                            <div id="descuentosMC" class="alert alert-primary" role="alert" style="display:block;background-color:#F7F3FA">
                        @else
                            <div id="descuentosMC" class="alert alert-primary" role="alert" style="display:none;background-color:#F7F3FA">
                        @endif
                            <div class="form-group row divConDescuentoMC">
                                <div class="col-sm-6" id="validarTipoDescuentoMC"><div><b>*Tipo de descuento:</b></div>                
                                    <div>
                                        @if($datos[0]->tipoDescuento == "porcentaje")
                                            Porcentaje (%)<input type="radio" name="tipoDescuento" id="porcentajeMC" value="porcentaje" checked>&nbsp;&nbsp;
                                            Cantidad ($)<input type="radio" name="tipoDescuento" id="cantidadMC" value="cantidad">&nbsp;&nbsp;
                                        @elseif($datos[0]->tipoDescuento == "cantidad")
                                            Porcentaje (%)<input type="radio" name="tipoDescuento" id="porcentajeMC" value="porcentaje" >&nbsp;&nbsp;
                                            Cantidad ($)<input type="radio" name="tipoDescuento" id="cantidadMC" value="cantidad" checked>&nbsp;&nbsp;
                                        @else
                                            Porcentaje (%)<input type="radio" name="tipoDescuento" id="porcentajeMC" value="porcentaje">&nbsp;&nbsp;
                                            Cantidad ($)<input type="radio" name="tipoDescuento" id="cantidadMC" value="cantidad">&nbsp;&nbsp;
                                        @endif
                                    </div>
                                    <div>
                                        {{Form::text('descuento',$datos[0]->descuento,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'descuentoMC','onblur'=>'calcularDescuentoMC()'])}}                            
                                    </div>
                                </div>
                            
                                <div class="col-sm-6">
                                    <div>
                                        <b>*Financiamiento con descuento:</b> 
                                    </div>
                                    <br>
                                    <div>
                                        {{Form::text('montoFinanciamiento',$datos[0]->montoFinanciamiento,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'totalDescuentoMC','readonly'])}}
                                    </div>
                                </div>                
                            </div>
                            <div class="form-group row divConDescuentoMC">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-3" style="">
                                </div>
                                <div class="col-sm-6" style="">
                                    <button type="button" class="btn btn-success btn-sm form-control rounded-0 guardarModificacionCostoMC">
                                        <span class="glyphicon glyphicon-plus-sign"></span> Guardar cambio
                                    </button>
                                </div>
                            </div>
                        </div>                                
                    </div>
                    
                    <div id="actualizacionOk" style="display:none;">
                        <br>
                        <div class="alert alert-success" role="alert">
                            <center>
                                Costo modificado exitosamente.
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


<script>
    $(document).ready(function() {        
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
        // $(".modal").removeClass("modal fade bd-example-modal-lg in");
        $("#activar").click();
    });

    function calcularFinanciamiento_mc() {
        var diasDePago = $("#dias_mc").val();
        var monto = $("#totalFinMC").val();
        var montoEquipo = parseFloat(monto);
        var calculo = 0;
        if(diasDePago >= 60 &&  diasDePago < 90){
            calculo = monto * 0.085;
            var financiamiento = parseFloat(calculo);
            document.getElementById("conFinanciamiento_mc").value = (financiamiento+montoEquipo).toFixed(2);
        }else if(diasDePago >= 90 && diasDePago<120){
            calculo = monto * 0.17;
            var financiamiento = parseFloat(calculo);
            document.getElementById("conFinanciamiento_mc").value = (financiamiento+montoEquipo).toFixed(2);
        }else if(diasDePago >= 120){
            calculo = monto * 0.255;
            var financiamiento = parseFloat(calculo);
            document.getElementById("conFinanciamiento_mc").value = (financiamiento+montoEquipo).toFixed(2);
        }
		else{
            document.getElementById("conFinanciamiento_mc").value = monto;
        }
    }

    function cerrarM() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
        // $(".modal").removeClass("modal fade bd-example-modal-lg in");
    }

    function costoYcantidad(){
        var costoUnitarioMC = $("#costoUnitarioMC").val();
        var cantidadMC = $("#cantidadMC").val();

        var costoUnitarioMCEquipo = parseFloat(costoUnitarioMC);
        var cantidadMCEquipo = parseFloat(cantidadMC);

        var calculo = costoUnitarioMCEquipo * cantidadMCEquipo;
        var multiplicacion = parseFloat(calculo);

        document.getElementById("totalFinMC").value = (multiplicacion).toFixed(2);
        document.getElementById("conFinanciamiento_mc").value = (multiplicacion).toFixed(2);
                    
    }

    function calcularPorcentaje_mc() {
        var montoFinanciamiento = $("#conFinanciamiento").val();
        var cantidadMC = $("#cantidadMC").val();
        var montoFinanciamiento = parseFloat(montoFinanciamiento);
        var cantidadMC = parseFloat(cantidadMC);

        var calculo = montoFinanciamiento * cantidadMC;
        var financiamiento = parseFloat(calculo).toFixed(2);
        document.getElementById("totalFinMC").value = financiamiento;
        
        
    }

    function calcularDescuentoMC(){
        var financiamiento = $("#totalFinMC").val();
        var descuento = $("#descuentoMC").val();

        var financiamientoEquipo = parseFloat(financiamiento);
        var descuentoEquipo = parseFloat(descuento);
        
        var tipoDescuento=$('input:radio[name=tipoDescuento]:checked').val();
        
        if(tipoDescuento=="porcentaje"){
            var porcentajeDescuento = descuento/100;
            calculo = financiamiento * porcentajeDescuento;
            var descuentoCalculado = parseFloat(calculo);

            document.getElementById("totalDescuentoMC").value = (financiamientoEquipo-descuentoCalculado).toFixed(2);
        }else if(tipoDescuento=="cantidad"){

            document.getElementById("totalDescuentoMC").value = (financiamientoEquipo-descuentoEquipo).toFixed(2);
        }else
        {
            alert("Debe seleccionar el tipo de descuento");
            $("#validarTipoDescuentoMC").css("color", "red");
        }
    }

    $("#porcentajeMC").click(function(){
        document.getElementById("descuentoMC").value = "";
        document.getElementById("totalDescuentoMC").value = "";
        document.getElementById("descuentoMC").placeholder = "Ejemplo: 10";
    })

    $("#cantidadMC").click(function(){
        document.getElementById("descuentoMC").value = "";
        document.getElementById("totalDescuentoMC").value = "";
        document.getElementById("descuentoMC").placeholder = "Ejemplo: 150";
    })

    $("#siAplicaMC").click(function(){
        // $("#totalDescuentoMC").attr('name', 'montoEquipo');
        // $("#totalFinMC").attr('name', 'montoAnterior');
        document.getElementById("descuentoMC").value = "";
        document.getElementById("totalDescuentoMC").value="";
        $('#divSinDescuentoMC').css('display', 'none');
        $('.divConDescuentoMC').css('display', 'block');
        $('#descuentosMC').css('display', 'block');
        document.getElementById("descuentosMC").style.backgroundColor = '#F0F9FF';
    })
    
    $("#noAplicaMC").click(function(){
        // $("#totalDescuentoMC").attr('name', 'sinMonto');        
        // $("#totalFinMC").attr('name', 'montoEquipo');       
        document.getElementById("descuentoMC").value = "";
        document.getElementById("totalDescuentoMC").value="";
        $('#divSinDescuentoMC').css('display', 'block');
        $('.divConDescuentoMC').css('display', 'none');
        $('#descuentosMC').css('display', 'none');    
    })

    $(".guardarModificacionCostoMC").click(function() {
        var costoUnitarioMC = $("#costoUnitarioMC").val();        
        var cantidadMC = $("#cantidadMC").val();
        var totalFinMC = $("#totalFinMC").val();        

        if (cantidadMC == 0 || totalFinMC == 0 || costoUnitarioMC == 0) {
            
            if(costoUnitarioMC == 0){ 
                $("#costoUnitarioMC").css('border-color', 'red');
                $("#costoUnitarioMC").css('color', 'red');
                alert("El costo unitario es obligatorio.");
            }else if(cantidadMC == 0){
                $("#cantidadMC").css('border-color', 'red');
                $("#cantidadMC").css('color', 'red');
                alert("El número de cantidad es obligatorio.");
            }else{
                $("#totalFinMC").css('border-color', 'red');
                $("#totalFinMC").css('color', 'red');   
                alert("El total es obligatorio.");
            }

        }else{            
            
            $("#reporteReparaciones").load('{{url('guardarModificacionMontoPartidaCotizacionServicios')}}' + '?' + $(this).closest('form').serialize());
            $('#actualizandoCosto').css('display', 'none');
            $('#actualizacionOk').css('display', 'block');
            $(".modal-open").css('padding-right', '');
            $(".modal-open").removeClass("modal-open");
            $(".modal-backdrop").removeClass("modal-backdrop fade in");
        }
    });

    $("#dias_mc").change(function(){
        document.querySelector("#descuentoMC").focus();    
        document.querySelector("#descuentoMC").blur();    
    })

</script>