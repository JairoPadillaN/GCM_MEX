<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<div class="row">
    <form action="" id="form_nuevo">
    @csrf
        <div class="form-group col-md-6">                    
            <div class="form-group row">
                {{Form::hidden('tipoCotizacion',$tipoCotizacion,['class' => 'form-control rounded-0','id' =>'tipCot', 'readonly'])}}
                {{Form::hidden('idSucursal',$idSucursal,['class' => 'form-control rounded-0', 'readonly'])}}
                {{Form::hidden('numeroCotizacion',$numeroCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                {{Form::hidden('tipoMoneda',$tipoMoneda,['class' => 'form-control rounded-0','id'=>'tipoMoneda', 'readonly'])}}
                {{Form::hidden('cambioDolar',$cambioDolar,['class' => 'form-control rounded-0','id'=>'cambioDolar', 'readonly'])}}
                {{Form::hidden('idCotizacion',$idCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                <div id="divPartida">
                    <input type="hidden" name="partida" id="partida" value="{{$partidaSiguiente}}">
                </div>                
                <div class="col-sm-3"><b>*Marca:</b></div>
                <div class="col-sm-9">
                    <select class="form-control rounded-0 marcas" id="marcas" name="idMarcaRefa">
                    <option value="">Seleccionar marca</option>
                        @foreach($marcasEquiposNuevos as $men)
                            <option value="{{$men->idMarcaRefa}}">{{$men->marcaRefaccion}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3"><b>*Número de parte:</b></div>
                <div class="col-sm-9">
                    <select class="form-control rounded-0 mi-selector" name="idPartesVenta" id="partes" onblur=calcularFinanciamiento()>
                        <option value="">Seleccionar parte</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3"><b>Modelo:</b></div>
                <div class="col-sm-9" id="modelo">
                    {{Form::text('modelo',old('modelo'),['class' => 'form-control rounded-0','readonly'])}}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3"><b>Descripción:</b></div>
                <div class="col-sm-9" id="descripcion">
                    {{Form::text('descripcion',old('descripcion'),['class' => 'form-control rounded-0','readonly'])}}
                </div>
            </div>
            <div class="" id="notaInternaYskuEquivalente">                
                
            </div>            
            <div class="form-group row">
                <div class="col-sm-3"><b>Notas:</b></div>
                <div class="col-sm-9">
                    <textarea name="notas" id="notitas" class="form-control rounded-0" rows="4" onblur=calcularFinanciamiento()></textarea>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3"><b>Días de pago:</b></div>
                <div class="col-sm-9">
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

            <div class="form-group row">
                <div class="col-sm-3"><b>*Semanas de entrega:</b></div>
                <div class="col-sm-9">
                    <select name='semanasEntrega' id='semanasEntrega' class="form-control" onchange=calcularFinanciamiento() onblur=calcularFinanciamiento()>
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

            <div class="form-group row">
                <div class="col-sm-4" id="precioVenta"><b>*Monto de venta:</b>
                    {{Form::text('precioVenta',old('precioVenta'),['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'precioV','readonly','onblur'=>'calcularFinanciamiento()'])}}
                </div> 
                <div class="col-sm-4" style="">
                    <div><b>*Con financiamiento:</b></div>
                    {{Form::text('montoEquipoInicial','0',['class' => 'form-control rounded-0','id'=>'conFinanciamiento','onblur'=>'calcularPorcentaje()','readonly'])}}
                </div>
                <div class="col-sm-4" style="">
                    <div><b>*Número de piezas:</b></div>
                    {{Form::text('piezas','0',['class' => 'form-control rounded-0','id'=>'piezas','onblur'=>'calcularPorcentaje()','onclick'=>'calcularFinanciamiento()'])}}
                </div>
            </div>                        

            <div class="form-group row">
                <div class="col-sm-3"><b>*Financiamiento Total:</b></div>
                <div class="col-sm-4" style="">
                    {{Form::text('montoEquipo','0',['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'totalFin','readonly'])}}
                </div>
                <div class="col-sm-5" style="">
                    <div><b>Aplicar descuento:</b></div>
                        Si <input type="radio" name="aplicaDescuento" id="siAplica" value="Si" >&nbsp;&nbsp;&nbsp;&nbsp;
                        No <input type="radio" name="aplicaDescuento" id="noAplica" value="No" checked>&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </div>

            <div class="form-group row" id="divSinDescuento">
                <div class="col-sm-3"></div>
                <div class="col-sm-3" style="">
                </div>
                <div class="col-sm-6" style="">
                    <button type="button" class="btn btn-success btn-sm form-control rounded-0 agregarReparacion">
                        <span class="glyphicon glyphicon-plus-sign"></span> Agregar SKU
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
                <div class="form-group row divConDescuento" style="display:none;">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-3" style="">
                    </div>
                    <div class="col-sm-6" style="">
                        <button type="button" class="btn btn-success btn-sm form-control rounded-0 agregarReparacion">
                            <span class="glyphicon glyphicon-plus-sign"></span> Agregar SKU
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
    </form>    
</div>
<div id="e"></div>
<div id="reporteReparaciones_nuevo">
    @if($cuantos>=1)
            <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr>
                        <th colspan="9" style="text-align:right"><font SIZE=2>Total: {{$tipoMoneda}} ${{$suma}}</font></th>
                        <th colspan="2"></th>
                    </tr>
                    <tr style="background-color: #C5EBFB;">
                        <th><font SIZE=2>Tipo de equipo</font></th>
                        <th><font SIZE=2>SKU / Descripción</font></th>
                        <th><font SIZE=2>Equipo</font></th>
                        <th><font SIZE=2>Modelo</font></th>
                        <th><font SIZE=2>GCMid Parte</font></th>
                        <th><font SIZE=2>Tiempo de entrega</font></th>
                        <th><font SIZE=2>Piezas</font></th>
                        <th style="width:400px;"><font SIZE=2>Costo unitario</font></th>
                        <th style="width:400px;"><font SIZE=2>Costo total</font></th>
                        <th><font SIZE=2>Notas</font></th>
                        <th><font SIZE=2><center>Operaciones</center></font></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equiposNuevosYreparados as $equiposNYp)
                        <tr>
                            <td style="text-align:center;width:100px;">                    
                                <font SIZE=2>
                                    @if($equiposNYp->tipoCotizacion == "reparacion")
                                        Reparación
                                    @elseif($equiposNYp->tipoCotizacion == "nuevo")
                                        Nuevo
                                    @else
                                        Refurbished
                                    @endif
                                </font>
                            </td>
                            <td style="text-align:center;width:200px;">                    
                                <font SIZE=2>
                                    {{$equiposNYp->descripcion}}
                                </font>
                            </td>
                            @if($equiposNYp->tipoCotizacion == "reparacion")
                                <td style="text-align:center;width:200px;">                    
                                    <font SIZE=2>
                                        @foreach($datosEquipoCotizado as $dEc)                
                                            @if($equiposNYp->idEquipos == $dEc->idEquipos)
                                                {{$dEc->equipo}}
                                            @endif                
                                        @endforeach                    
                                    </font>
                                </td>
                                <td style="text-align:center;width:200px;">                    
                                    <font SIZE=2>
                                        {{$equiposNYp->modelo}}
                                    </font>
                                </td> 
                                @foreach($equiposReparados as $equipoR)
                                    @if($equiposNYp->idEquipos == $equipoR->idEquipos)
                                        <td style="text-align:center;width:200px;">
                                            <font SIZE=2 color="#2F7DA7">
                                                <button value="{{$equiposNYp->partida}}" class="verDetallePartida" style="background-color: Transparent;background-repeat:no-repeat;border: none;cursor:pointer;overflow: hidden;">
                                                    {{$equipoR->GCMid}} - {{$equipoR->complementoGCMid}}
                                                </button>
                                            </font>
                                        </td>
                                        @break
                                    @endif
                                @endforeach
                            @else
                                <td style="text-align:center;width:200px;">
                                    <font SIZE=2>
                                        @foreach($datosEquiposNuevos as $dEn)                
                                            @if($equiposNYp->idPartesVenta == $dEn->idPartesVenta)
                                                {{$dEn->nombreRefaccion}}
                                                @break;
                                            @endif                
                                        @endforeach 
                                    </font>
                                </td>
                                <td style="text-align:center;width:200px;">                    
                                    <font SIZE=2>
                                        {{$equiposNYp->modelo}}
                                    </font>
                                </td>
                                <td style="text-align:center;">
                                    <font SIZE=2>
                                        @foreach($datosEquiposNuevos as $dEn)                
                                            @if($equiposNYp->idPartesVenta == $dEn->idPartesVenta)
                                                {{$dEn->numeroParte}}
                                                @break;
                                            @endif                
                                        @endforeach
                                    </font>
                                </td>
                            @endif
                            <td style="text-align:center;"><font SIZE=2>{{$equiposNYp->semanasEntrega}}</font></td>
                            <td style="text-align:center;"><font SIZE=2>{{$equiposNYp->piezas}}</font></td>
                            <td style="text-align:center;width:350px;">
                                <font SIZE=2>
                                    @if($equiposNYp->descuento !="")
                                        <strike>{{$tipoMoneda}} $<?php echo number_format ($equiposNYp->montoEquipoInicial,2)?></strike>
                                        <br>
                                        {{$tipoMoneda}} $
                                        <?php 
                                            //validando que no haga divicion entre cero y truene
                                            if ($equiposNYp->piezas >=1) {
                                                $descuentoUnitario = $equiposNYp->montoFinanciamiento / $equiposNYp->piezas;
                                            }else{
                                                $descuentoUnitario = $equiposNYp->montoFinanciamiento;
                                            }
                                            echo number_format ($descuentoUnitario,2);
                                        ?>
                                    @else
                                        {{$tipoMoneda}} $<?php echo number_format ($equiposNYp->montoEquipo,2)?>
                                    @endif
                                </font>
                            </td>
                            <td style="text-align:center; width:350px;">
                                <font SIZE=2>
                                    @if($equiposNYp->descuento !="")
                                        <strike>{{$tipoMoneda}} $ <?php $totalff = $equiposNYp->montoEquipo * $equiposNYp->piezas; echo number_format ($totalff,2)?></strike>
                                        <br>
                                        - @if($equiposNYp->tipoDescuento == "porcentaje")
                                            {{$equiposNYp->descuento}}% 
                                        @else 
                                            ${{$equiposNYp->descuento}}
                                        @endif                                                    
                                        <br> {{$tipoMoneda}} $<?php echo number_format ($equiposNYp->montoFinanciamiento,2)?>
                                    @else
                                        {{$tipoMoneda}} $<?php echo number_format ($equiposNYp->montoFinanciamiento,2)?>
                                    @endif
                                </font>
                            </td>    
                            <td>
                                <font SIZE=2>
                                    {{$equiposNYp->notas}}
                                </font>
                            </td>
                            <td align="center">
                                <form id='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                                    @csrf
                                    {{Form::hidden('numeroCotizacionB',$numeroCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                                    {{Form::hidden('tipoCotizacionB',$equiposNYp->tipoCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                                    {{Form::hidden('idSucursal',$idSucursal,['class' => 'form-control rounded-0', 'readonly'])}}
                                    {{Form::hidden('idEquiposB',$equiposNYp->idEquipos,['class' => 'form-control rounded-0', 'readonly'])}}
                                    {{Form::hidden('tipoMonedaB',$tipoMoneda,['class' => 'form-control rounded-0', 'readonly'])}}
                                    {{Form::hidden('partidaBorrar',$equiposNYp->partida,['class' => 'form-control rounded-0', 'readonly'])}}
                                    <input type="hidden" value="{{$equiposNYp->idCotizacion}}" name="idCotizacionB" id="idCotizacion">
                                    <input type="hidden" value="{{$equiposNYp->idRepCotizacion}}" name="idRepCotizacion">
                                    @if($estatusCotizacion == "En revisión")
                                        <button value="{{$equiposNYp->partida}}" class="modificarMontoPartidaCotizacion btn btn-info btn-sm" type="button">
                                            <i class="fa fa-pencil bigger"></i>
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-danger borrarReparacion">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="9" style="text-align:right"><font SIZE=2><b>Total: {{$tipoMoneda}} ${{$suma}}</b></font></td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
            </div>   
        </div>
        {{Form::hidden('validarCuantos',$cuantos,['class' => 'validarCuantos'])}}
    @else
        <div class="alert alert-warning" role="alert" align="center">
            <label for="">No se han agregado equipos a cotización.</label>
            {{Form::hidden('validarCuantos',$cuantos,['class' => 'validarCuantos'])}}
        </div>
    @endif
    <div id="cargarModal"></div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    // $(document).ready(function(){        
    //     // $("#marcas").select2({width:'100%'});
    //     // $("#partes").select2({width:'100%'});
    // });
    
$(".verDetallePartida").click(function (e) {
    var partida = $(this).val();
    var idCotizacion=$('#idCotizacion').val();
    var tipoMoneda=$('#tipoMoneda').val();
    $('#cargarModal').load('{{url('detallePartidasCotizacion')}}' + '?idCotizacion='+idCotizacion+'&partida='+partida+'&tipoMoneda='+tipoMoneda);
    e.preventDefault(); 
});
$(".modificarMontoPartidaCotizacion").click(function (e) {
    var partida = $(this).val();
    var idCotizacion=$('#idCotizacion').val();    
    var tipoMoneda=$('#tipoMoneda').val();
    $('#cargarModal').load('{{url('modificarMontoPartidaCotizacion')}}' + '?idCotizacion='+idCotizacion+'&partida='+partida+'&tipoMoneda='+tipoMoneda);
    e.preventDefault(); 
});
</script>
<script>
    $('.mi-selector').click(function() {
        $('.mi-selector').select2();
    });    
</script>
<script>

    $("#dias").change(function() {
        // document.getElementById("piezas").value = 0;
        // document.getElementById("totalFin").value = 0;
    });

    $("#marcas").change(function() {
        document.getElementById("conFinanciamiento").value = 0;
        document.getElementById("piezas").value = 0;
        document.getElementById("totalFin").value = 0;
        document.getElementById("precioV").value = 0;        
        var tipo = $("#tipCot").val();
        $("#partes").text('cargando...') 
        $("#partes").load('{{url('comboPartes')}}' + '?r=' + Date.now() + '&idMarcaRefa='  + this.options[this.selectedIndex].value + '&tipoCotizacion=' + tipo) ;
    });

    $("#partes").change(function() {
        document.getElementById("conFinanciamiento").value = 0;
        document.getElementById("piezas").value = 0;
        document.getElementById("totalFin").value = 0;
        document.getElementById("precioV").value = 0;
        var tipoMoneda = $("#tipoMoneda").val();
        var cambioDolar = $("#cambioDolar").val();
        $("#modelo").load('{{url('modeloParte')}}' + '?r=' + Date.now() + '&idPartesVenta='  + this.options[this.selectedIndex].value);
        $("#descripcion").load('{{url('descripcionParte')}}' + '?r=' + Date.now() + '&idPartesVenta='  + this.options[this.selectedIndex].value);
        $("#notaInternaYskuEquivalente").load('{{url('notaInternaYskuEquivalente')}}' + '?r=' + Date.now() + '&idPartesVenta='  + this.options[this.selectedIndex].value);
        $("#precioVenta").load('{{url('precioParte')}}' + '?r=' + Date.now() + '&idPartesVenta='  + this.options[this.selectedIndex].value + '&tipoMoneda=' + tipoMoneda + '&cambioDolar=' + cambioDolar,
            function() {
                setTimeout(() => {
                    document.querySelector("#conFinanciamiento").value = $("#precioV").val()
                    document.querySelector("#precioVista").focus();    
                    document.querySelector("#precioVista").blur();   
                }, 200);
            }
        );
        $("#semanasEntrega").load('{{url('semanasEntregaParte')}}' + '?r=' + Date.now() + '&idPartesVenta='  + this.options[this.selectedIndex].value);
        $("#modelo,#descripcion,#notaInternaYskuEquivalente,#precioVenta,#semanasEntrega").text('cargando...') 
    });

    $(".agregarReparacion").click(function() {
        var semanas = $("#semanasEntrega").val();        
        var piezas = $("#piezas").val();
        var marcas = $("#marcas").val();
        var partes = $("#partes").val();
        var precioV = $("#precioV").val();
        var totalFin = $("#totalFin").val();
        

        if (precioV == 0 || semanas == "" || piezas == 0 || totalFin == 0 || marcas == "" || partes == "") {
            
            if(marcas == ""){
                $("#marcas").css('border-color', 'red');
                $("#marcas").css('color', 'red');   
                alert("La marca es obligatoria.");            
            }else if(partes == 0){
                $("#partes").css('border-color', 'red');
                $("#partes").css('color', 'red');   
                alert("El número de parte es obligatorio.");
            }else if(semanas == ""){
                $("#semanasEntrega").css('border-color', 'red');
                $("#semanasEntrega").css('color', 'red');   
                alert("Las semanas de entrega son obligatorias.");
            }else if(precioV == 0){ 
                $("#precioV").css('border-color', 'red');
                $("#precioV").css('color', 'red');
                alert("El precio de venta es obligatorio.");            
            }else if(piezas == 0){
                $("#piezas").css('border-color', 'red');
                $("#piezas").css('color', 'red');
                alert("El número de piezas es obligatorio.");
            }else{
                $("#totalFin").css('border-color', 'red');
                $("#totalFin").css('color', 'red');    
                alert("El financiamiento total es obligatorio.");
            }

        }else{            
            // $("#reporteReparaciones_nuevo").load('{{url('reporteDeReparacionesAbajo')}}' + '?' + $(this).closest('form').serialize(),
            var formData = new FormData(document.getElementById("form_nuevo"));

            $.ajax({
                data: formData,
                url: '{{url('reporteDeReparacionesAbajo')}}',
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (res) {
                    setTimeout(() => {
                        document.querySelector("#tipoCotizacion").value = 'nuevo';
                        // Create a new 'change' event
                        var event = new Event('change');
                        // Dispatch it.
                        document.querySelector("#tipoCotizacion").dispatchEvent(event);
                    }, 100);
                },
                error: function (err) {
                    console.log(err);
                }
            });
                // function(){
                //     setTimeout(() => {
                //         document.querySelector("#tipoCotizacion").value = 'nuevo';
                //         // Create a new 'change' event
                //         var event = new Event('change');
                //         // Dispatch it.
                //         document.querySelector("#tipoCotizacion").dispatchEvent(event);
                //     }, 200);
                // }
            $('#botonParaGuardar').css('visibility', 'visible');
            $('#atras').css('visibility', 'hidden');
            $("#marcas,#partes,#semanasEntrega,#modelo,#notitas").val('');
            $("#precioV,#piezas,#totalFin,#precioVista,#conFinanciamiento").val('0.0');   
            $("#notaInternaYskuEquivalente").empty();
            $("#noAplica").click();
            $("[name='tipoDescuento']").prop("checked", false);
            document.getElementById("descuento").value = "";
            document.getElementById("totalDescuento").value="";
            // $("#divPartida").load('{{url('generarPartida')}}' + '?' + $(this).closest('form').serialize());
        }
    });

    $(".borrarReparacion").click(function(e) {

        let btn 

        if(e.target.classList.contains('fa')) {
            btn = e.target.parentElement;
        }else{
            btn = e.target;
        }
        
        console.log(e.target)
        console.log(btn)

        btn.classList.add("disabled");

        var formData = new FormData(btn.parentElement);
        $.ajax({
                data: formData,
                url: '{{url('borrarReparacion')}}',
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (res) {
                    setTimeout(() => {
                        document.querySelector("#tipoCotizacion").value = 'nuevo';
                        // Create a new 'change' event
                        var event = new Event('change');
                        // Dispatch it.
                        document.querySelector("#tipoCotizacion").dispatchEvent(event);
                    }, 100);
                },
                error: function (err) {
                    console.log(err);
                }
            });

        // $("#reporteReparaciones_nuevo").load('{{url('borrarReparacion')}}' + '?' + $(this).closest('form').serialize());
        // $("#reporteRefaccionesAsignadas").empty();
        // // $("#divPartida").load('{{url('generarPartida')}}' + '?' + $(this).closest('form').serialize());        
    });    

</script>

<script>

    function calcularFinanciamiento() {
        var diasDePago = $("#dias").val();
        var monto = $("#precioV").val();
        var montoEquipo = parseFloat(monto);
        var calculo = 0;
        if(diasDePago >= 60 &&  diasDePago < 90){
            calculo = monto * 0.085;
            var financiamiento = parseFloat(calculo);
            document.getElementById("conFinanciamiento").value = (financiamiento+montoEquipo).toFixed(2);
        }else if(diasDePago >= 90 && diasDePago<120){
            calculo = monto * 0.17;
            var financiamiento = parseFloat(calculo);
            document.getElementById("conFinanciamiento").value = (financiamiento+montoEquipo).toFixed(2);
        }else if(diasDePago >= 120){
            calculo = monto * 0.255;
            var financiamiento = parseFloat(calculo);
            document.getElementById("conFinanciamiento").value = (financiamiento+montoEquipo).toFixed(2);
        }
		else{
            document.getElementById("conFinanciamiento").value = monto;
        }

        setTimeout(() => {
            calcularDescuento()
        }, 200);
    }


    function calcularPorcentaje() {
        var montoFinanciamiento = $("#conFinanciamiento").val();
        var piezas = $("#piezas").val();
        var montoFinanciamiento = parseFloat(montoFinanciamiento);
        var piezas = parseFloat(piezas);

        var calculo = montoFinanciamiento * piezas;
        var financiamiento = parseFloat(calculo).toFixed(2);
        document.getElementById("totalFin").value = financiamiento; 

        setTimeout(() => {
            calcularDescuento()
        }, 200);

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
        }else if($('input:radio[name=aplicaDescuento]:checked').val() == "Si" && tipoDescuento == ""){
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

    document.querySelector("#dias").addEventListener("change", (event) => {
        console.log("cambio dias")
        document.querySelector("#piezas").focus()
        document.querySelector("#piezas").blur()
    });
    document.querySelector("#semanasEntrega").addEventListener("change", (event) => {
        console.log("cambio semanaas")
        document.querySelector("#piezas").focus()
        document.querySelector("#piezas").blur()
    });
</script>