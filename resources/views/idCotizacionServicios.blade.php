{{ Form::hidden('idCotizacionServicios',$idCotizacionServicios,['id'=>'idCotizacionServicios','readonly'])}}
@if($idCotizacionServicios == 0)
<div class="alert alert-danger" role="alert">
    <center>
        <div>
            <b>Número de cotización ya existente</b>
        </div>
        
        <div>
            <button type="button" class="btn btn-warning" id="corregirNumCot">
                <span class="glyphicon glyphicon-arrow-left"></span> Generar nuevo número de cotización
            </button>
        </div>
    </center>
</div>
@else
    <div class="form-group row">
        <div class="col-sm-7 numeroCotizacionGenerado" style="visibility:hidden">
        </div>                
                
        <div class="col-sm-6" id="divServicio">
            <div class="form-group"><b>*Servicio:</b>                                                    
                <select name="idServicioTecnico" id="servicio" class="form-control rounded-0" rows="4" onblur=calcularFinanciamiento()>
                    <option value="">Seleccionar opcion</option>
                    @foreach($comboServiciosTecnicos as $comboServiciosTecnicos)
                        <option value="{{$comboServiciosTecnicos->idServicioTecnico}}">{{$comboServiciosTecnicos->claveServicio}} {{$comboServiciosTecnicos->servicioTecnico}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" id="detalle"><b>*Detalle:</b>                        
                <textarea name="detalle" id="" class="form-control rounded-0" rows="3" onblur=calcularFinanciamiento()></textarea>
            </div>
        </div>
        
        <div class="col-sm-6">
            <div class=""><b>Notas:</b>                        
                <textarea name="notas" id="" class="form-control rounded-0" rows="7" onblur=calcularFinanciamiento()></textarea>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-6">
            <div class=""><b>Días de pago:</b>
                <select class="form-control rounded-0" name="diasDePago" id="dias" onchange=calcularFinanciamiento()>
                    <option selected="selected" value="{{$diasDePago}}">{{$diasDePago}}</option>
                    <option value="0">0</option>
                    <option value="30">30</option>                        
                    <option value="60">60</option>
                    <option value="90">90</option>
					<option value="120">120</option>
                </select>
            </div>
        </div>

        <div class="col-sm-6">
            <div class=""><b>*Semanas de entrega:</b>
                <select name='semanasEntrega' id='semanasEntrega' class="form-control" onblur=calcularFinanciamiento()>
                    <option value="">Seleccionar opción</option>
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
    </div>
    <div class="form-group row">
        <div class="col-sm-3" id="divMontoServicio">
            <div class="" ><b>*Monto del servicio:</b></div>
            <div class="" style="" id="montoDiv">
                {{Form::text('montoServicio',old('montoServicio'),['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'montoServicio','onblur'=>'calcularFinanciamiento()','readonly'])}}
            </div>                 
        </div>                        

        <div class="col-sm-3" style="">
            <div><b>*Con financiamiento:</b></div>
            {{Form::text('montoEquipoInicial','0',['class' => 'form-control rounded-0','id'=>'conFinanciamiento','onblur'=>'calcularPorcentaje()','readonly'])}}
        </div>
        <div class="col-sm-3" style="">
            <div><b>*Cantidad:</b></div>
            {{Form::text('cantidad',old('cantidad'),['class' => 'form-control rounded-0','placeholder' => 'Ejemplo: 1','id'=>'piezas','onblur'=>'calcularPorcentaje()','onclick'=>'calcularFinanciamiento()'])}}
        </div>
        <div class="col-sm-3" style="" id="unidadMedida">
            <div><b>*Unidad de medida:</b></div>
            {{Form::text('observacionCantidad',old('observacionCantidad'),['class' => 'form-control rounded-0','placeholder' => 'Ejemplo: personas, horas etc.','id'=>'obs','onblur'=>'calcularPorcentaje()','readonly'])}}
        </div>
    </div>                        

    <div class="form-group row">
        <div class="col-sm-4"><b>*Financiamiento Total:</b>
            {{Form::text('montoEquipo','0',['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'totalFin','readonly'])}}
        </div>
        <div class="col-sm-5" style="">
            <div><b>Aplicar descuento:</b></div>
                Si <input type="radio" name="aplicaDescuento" id="siAplica" value="Si" onclick=calcularFinanciamiento()>&nbsp;&nbsp;&nbsp;&nbsp;
                No <input type="radio" name="aplicaDescuento" id="noAplica" value="No" checked onclick=calcularFinanciamiento()>&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-5" id="divSinDescuento">
            <button type="button" class="btn btn-success btn-sm form-control rounded-0 agregarReparacion">
                <span class="glyphicon glyphicon-plus-sign"></span> Agregar servicio a cotización
            </button>                        
        </div>
    </div>

    <div id="descuentos" class="alert alert-primary" role="alert" style="display:none;">
        <div class="form-group row divConDescuento" style="display:none;">
            <div class="col-sm-6" id="validarTipoDescuento"><div><b>*Tipo de descuento:</b></div>                
                <div>
                    Porcentaje (%)<input type="radio" name="tipoDescuento" id="porcentaje" value="porcentaje">&nbsp;&nbsp;
                    Cantidad ($)<input type="radio" name="tipoDescuento" id="cantidad" value="cantidad">&nbsp;&nbsp;
                </div>
                <div>
                    {{Form::text('descuento',old('descuento'),['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'descuento','onblur'=>'calcularDescuento()'])}}                            
                </div>
            </div>
        
            <div class="col-sm-6">
                <div>
                    <b>*Financiamiento con descuento:</b> 
                </div>
                <br>
                <div>
                    {{Form::text('montoFinanciamiento','0',['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'totalDescuento','readonly'])}}
                </div>
            </div>                
        </div>
        <div class="col-sm-5 form-group row divConDescuento" style="display:none;">
            <button type="button" class="btn btn-success btn-sm form-control rounded-0 agregarReparacion">
                <span class="glyphicon glyphicon-plus-sign"></span> Agregar servicio a cotización
            </button>                            
        </div>
    </div>
    <div id="reporteReparaciones">
        <div class="" style="text-align:right;">
            <button type="button" class="btn btn-default" id="atras" style="color: #fff;background-color: #4d42ff59;border-color: #4d42ff59;">
                <span class="glyphicon glyphicon-arrow-left"></span> Cambiar de sucursal
            </button>
        </div>
    </div>
    <div id="reparaciones">
        <div class="seleccionarEquipo" style="visibility:hidden;">
            <button type="button" class="btn btn-danger btn-sm cancelarAlta">
                Cancelar cotización
            </button>
        </div>
    </div>
    <div id='botonParaGuardar' style="text-align:center; visibility:hidden;" >
        {{ Form::submit('Guardar',['class' => 'btn  btn-success','id'=>'guardar'])}}
        <button type="button" class="btn btn-danger btn-sm cancelarAlta">
            Cancelar cotización
        </button>                    
    </div>
@endif


<script>
$("#corregirNumCot").click(function(e) {
    $('#tab2').css('visibility', 'hidden');
    $("#tab2").removeClass("active");
    $("#2").removeClass("tab-pane active");
    $("#2").toggleClass("tab-pane fade");
    $("#reparaciones").empty();

    $("#tab1").toggleClass("active");
    $("#1").removeClass("tab-pane fade");
    $("#1").toggleClass("tab-pane active");
    $('#tab1').css('visibility', 'visible');

    $('#botonParaGuardar').css('visibility', 'hidden');
});

$("#servicio").change(function() {
    document.getElementById("totalFin").value = 0;
    document.getElementById("montoServicio").value = 0;
    document.getElementById("conFinanciamiento").value = 0;
    document.getElementById("piezas").value = 0;
    document.getElementById("obs").value = "";        
    $("#detalle").load('{{url('detalleServicio')}}' + '?r=' + Date.now() + '&idServicioTecnico=' + this.options[this.selectedIndex].value);
    $("#montoDiv").load('{{url('montoServicio')}}' + '?' + $(this).closest('form').serialize());
    $("#unidadMedida").load('{{url('unidadMedida')}}' + '?r=' + Date.now() + '&idServicioTecnico=' + this.options[this.selectedIndex].value);
})

$(".agregarReparacion").click(function() {
    var servicio = $("#servicio").val();
    var montoServicio = $("#montoServicio").val();
    var totalFin = $("#totalFin").val();
    var piezas = $("#piezas").val();
    

    if (montoServicio == 0 || totalFin == 0 || servicio == ""|| piezas == 0) {
        if(servicio == ""){
            $("#servicio").css('border-color', 'red');
            $("#servicio").css('color', 'red');   
            $("#divServicio").css('color', 'red');   
            alert("El campo servicio es obligatorio.");            
        }else if(montoServicio == 0){
            $("#montoServicio").css('border-color', 'red');
            $("#divMontoServicio").css('color', 'red');   
            alert("El monto del servicio es obligatorio.");
        }else if(piezas == 0){
            $("#piezas").css('border-color', 'red');
            $("#piezas").css('color', 'red');
            alert("La cantidad es obligatoria.");    
        }else{
            $("#totalFin").css('border-color', 'red');
            $("#totalFin").css('color', 'red');    
            alert("El financiamiento total es obligatorio.");
        }
    }else{
        $("#reporteReparaciones").load('{{url('asignarSercicioCotizacion')}}' + '?' + $(this).closest('form').serialize());
        $('#botonParaGuardar').css('visibility', 'visible');
        $('#atras').css('visibility', 'hidden');
    }
});

$("#atras").click(function(e) {

    $('#tab2').css('visibility', 'hidden');
    $("#tab2").removeClass("active");
    $("#2").removeClass("tab-pane active");
    $("#2").toggleClass("tab-pane fade");
    $("#reparaciones").empty();

    $("#tab1").toggleClass("active");
    $("#1").removeClass("tab-pane fade");
    $("#1").toggleClass("tab-pane active");
    $('#tab1').css('visibility', 'visible');

    $('#botonParaGuardar').css('visibility', 'hidden');

});

$(".cancelarAlta").click(function(e) {
    var validarCuantos = $('.validarCuantos').val();
    if (typeof validarCuantos === 'undefined' || validarCuantos == 0) {
        $("#altaVersion").empty();
        location.reload();   
    }else{
        alert('Error: Debe eliminar los servicios asignados para poder cancelar la cotización');
        e.preventDefault(); 
    }
});

$("#guardar").click(function(e){
    var validarCuantos = $('.validarCuantos').val();
    if (validarCuantos == 0) {
        alert('Error: La cotización no puede quedarse sin servicios, debe cancelar el alta.');
        e.preventDefault();    
    }        
});
</script>
<script>
    function calcularFinanciamiento() {
        var diasDePago = $("#dias").val();
        var monto = $("#montoServicio").val();
        var montoEquipo = parseFloat(monto);
        var calculo = 0;
        if(diasDePago >= 60 &&  diasDePago < 90){
            calculo = monto * 0.085;
            var financiamiento = parseFloat(calculo);
            document.getElementById("conFinanciamiento").value = (financiamiento+montoEquipo).toFixed(2);
        }else if(diasDePago >= 90 && diasDePago <120){
            calculo = monto * 0.17;
            var financiamiento = parseFloat(calculo);
            document.getElementById("conFinanciamiento").value = (financiamiento+montoEquipo).toFixed(2);
        }else if(diasDePago >= 120){
            calculo = monto * 0.255;
            var financiamiento = parseFloat(calculo);
            document.getElementById("conFinanciamiento").value = (financiamiento+montoEquipo).toFixed(2);
        }else{
            document.getElementById("conFinanciamiento").value = montoEquipo.toFixed(2);
        }
    }


    function calcularPorcentaje() {
        var montoFinanciamiento = $("#conFinanciamiento").val();
        var piezas = $("#piezas").val();
        var montoFinanciamiento = parseFloat(montoFinanciamiento);
        var piezas = parseFloat(piezas);

        var calculo = montoFinanciamiento * piezas;
        var financiamiento = parseFloat(calculo).toFixed(2);
        document.getElementById("totalFin").value = financiamiento;
        
        
    }

    function calcularDescuento(){
        var financiamiento = $("#totalFin").val();
        var descuento = $("#descuento").val();

        var financiamientoEquipo = parseFloat(financiamiento);
        var descuentoEquipo = parseFloat(descuento);
        
        var tipoDescuento=$('input:radio[name=tipoDescuento]:checked').val();
        
        if(tipoDescuento=="porcentaje"){
            var porcentajeDescuento = descuento/100;
            calculo = financiamiento * porcentajeDescuento;
            var descuentoCalculado = parseFloat(calculo);

            document.getElementById("totalDescuento").value = (financiamientoEquipo-descuentoCalculado).toFixed(2);
        }else if(tipoDescuento=="cantidad"){

            document.getElementById("totalDescuento").value = (financiamientoEquipo-descuentoEquipo).toFixed(2);
        }else
        {
            alert("Debe seleccionar el tipo de descuento");
            $("#validarTipoDescuento").css("color", "red");
        }
    }

    $("#porcentaje").click(function(){
        document.getElementById("descuento").value = "";
        document.getElementById("descuento").placeholder = "Ejemplo: 10";
    })

    $("#cantidad").click(function(){
        document.getElementById("descuento").value = "";
        document.getElementById("descuento").placeholder = "Ejemplo: 150";
    })

    $("#siAplica").click(function(){
        // $("#totalDescuento").attr('name', 'montoEquipo');
        // $("#totalFin").attr('name', 'montoAnterior');
        document.getElementById("descuento").value = "";
        document.getElementById("totalDescuento").value="";
        $('#divSinDescuento').css('display', 'none');
        $('.divConDescuento').css('display', 'block');
        $('#descuentos').css('display', 'block');
        document.getElementById("descuentos").style.backgroundColor = '#F0F9FF';
    })
    
    $("#noAplica").click(function(){
        // $("#totalDescuento").attr('name', 'sinMonto');        
        // $("#totalFin").attr('name', 'montoEquipo');       
        document.getElementById("descuento").value = "";
        document.getElementById("totalDescuento").value="";
        $('#divSinDescuento').css('display', 'block');
        $('.divConDescuento').css('display', 'none');
        $('#descuentos').css('display', 'none');    
    })
</script>