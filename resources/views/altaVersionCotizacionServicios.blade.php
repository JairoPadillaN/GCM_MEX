<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script src="{{ asset('assets/js/jquery-1.10.2.js')}}"></script>

{{ Form::open(['route' => 'guardarCotizacionServicios','files'=>true])}}
{{ Form::token()}}

<ul class="nav nav-tabs">
    <li class="active" id='tab1' style="visibility:visible;"><a href="#1" data-toggle="tab"><label for="">Cotización</label></a></li>
    <li class="" id='tab2' style="visibility:hidden;"><a href="#2" data-toggle="tab"><label for="">Asignar servicio</label></a></li>
</ul>

<form action="">
    <div class="tab-content">
        <div class="tab-pane active" id="1">
            <div id='a'></div>
                <div class="container-fluid">
                    <br>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <div class="form-group row">
                                <div class="col-sm-4"><b>Registrado por:</b></div>
                                <div class="col-sm-8">
                                    {{ Form::hidden('idc',$idc,['class' => 'form-control rounded-0', 'readonly'])}}                                    
                                    <select name="idu" class="form-control rounded-0" id="idu">
                                        <option value="{{ Session::get('sesionidu')}}">{{ Session::get('sesionname')}} {{ Session::get('sesionpaterno')}} {{Session::get('sesionmaterno')}}</option>
                                        <option style="background-color: #dddddde0;" disabled>Administradores</option>
                                            @foreach($comboUsuariosAdmon as $comboUsuariosAdmon)
                                                <option value="{{$comboUsuariosAdmon->idu}}">{{$comboUsuariosAdmon->nombreUsuario}} {{$comboUsuariosAdmon->aPaterno}} {{$comboUsuariosAdmon->aMaterno}}</option>
                                            @endforeach
                                        <option style="background-color: #dddddde0;" disabled>Vendedores</option>
                                            @foreach($comboUsuariosVendedor as $comboUsuariosVendedor)
                                                <option value="{{$comboUsuariosVendedor->idu}}">{{$comboUsuariosVendedor->nombreUsuario}} {{$comboUsuariosVendedor->aPaterno}} {{$comboUsuariosVendedor->aMaterno}}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="nombreUsuario" id="nombreUsuario">
                            <div class="form-group row">
                                <div class="col-sm-3"><b>*Fecha:</b></div>
                                <div class="col-sm-9">
                                    {{ Form::date('fechaCotizacion',\Carbon\Carbon::now(),['class' => 'form-control','id'=>'fechaCotizacion'])}}
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3"><b>*Sucursal:</b></div>
                                <div class="col-sm-9">
                                    <select name='idSucursal' id='comboSucursal' class="form-control">
                                        <option value="">Seleccionar opción</option>
                                        @foreach($sucursales as $sucursal)
                                        <option value='{{$sucursal->idSucursal}}'>{{$sucursal->sucursal}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-5"><b>Número de cotización:</b></div>
                                <div class="col-sm-7 numeroCotizacionGenerado">
                                    {{ Form::text('numeroCotizacion',old ('numeroCotizacion'),['class' => 'form-control rounded-0', 'readonly'])}}
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3"><b>Dirigido a:</b></div>
                                <div class="col-sm-9">
                                    <select name='dirigidoA' class="form-control" id="comboContacto">
                                        <option value="">Seleccionar contacto</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-2"><b>Cc:</b></div>
                                <div class="col-sm-10">
                                    <select name='conCopia' class="form-control">
                                        <option value="">Seleccionar contacto</option>
                                            @foreach($comboConCopia as $comboConCopia)
                                                <option value='{{$comboConCopia->nombreCont}}'>{{$comboConCopia->nombreCont}} - {{$comboConCopia->sucursal}}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <b>Archivo de cotización:</b>{{ Form::file('archivoCotizacion',['class' => 'form-control rounded-0','id'=>'archivoCotizacion','onchange'=>'ValidarImagen(this);'])}}
                        </div>

                        <div class="form-group col-md-6">                            
                            <div class="form-group row">
                                <div class="col-sm-6"><b>Monto:</b>
                                    {{ Form::text('montoTotal',old ('montoTotal'),['id'=>'monto','class' => 'form-control rounded-0', 'readonly'])}}
                                </div>
                                <div class="col-sm-6" id="validarTipo"><b>*Tipo de moneda:</b> <br>                                    
                                    MXN <input type="radio" name="tipoMoneda" id="MXN" class="tip" value="MXN" >&nbsp;&nbsp;&nbsp;&nbsp;
                                    USD <input type="radio" name="tipoMoneda" id="USD" class="tip" value="USD" >&nbsp;&nbsp;&nbsp;&nbsp;
                                    <div id="cambio" style=""><br>
                                        <b>Cambio del dolar:</b><br> 
                                        {{ Form::text('cambio',old ('cambio'),['id'=>'cambioDolar','class' => 'form-control rounded-0','placeholder'=>'Ejemplo: 20.04'])}}
                                    </div>
                                </div>
                            </div>
                            <div class='form-group row' id='validarNotas'>
                                <div class='col-md-12'><b>*Notas y condiciones comerciales:</b></div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check1','si',true,['id'=>'check1','class'=>'cuadritos'])}}</div>
                                        <div class="col-md-10">{{ Form::text('textoCheck1','Precio unitario mas IVA',['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check2','si',true,['id'=>'check2','class'=>'cuadritos'])}}</div>
                                        <div class="col-md-10">{{ Form::text('textoCheck2','Vigencia de cotización 15 días',['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check3','si',true,['id'=>'check3','class'=>'cuadritos'])}}</div>
                                        <div class="col-md-10">{{ Form::text('textoCheck3','FOB',['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;','id'=>'fob'])}}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check4','si',true,['id'=>'check4','class'=>'cuadritos'])}}</div>
                                        <div class="col-md-10">{{ Form::text('textoCheck4','Tiempo de entrega estipulado en tabla anterior',['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check5','si',true,['id'=>'check5','class'=>'cuadritos'])}}</div>
                                        <div class="col-md-10">{{ Form::text('textoCheck5','Precio sujeto a cambios sin previo aviso',['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check6','si',true,['id'=>'check6','class'=>'cuadritos'])}}</div>
                                        <div class="col-md-10">{{ Form::text('textoCheck6','Recibida la orden de compra no se acepta cancelación',['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check7','si',true,['id'=>'check7','class'=>'cuadritos'])}}</div>
                                        <div class="col-md-10"><input type="text" name="textoCheck7" value="Condiciones de pago: <?php echo $diasDePago ?> días" style="margin: 2px;" class="form-control rounded-0 notas"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check8','si',true,['id'=>'check8','class'=>'cuadritos'])}}</div>
                                        <div class="col-md-10">{{ Form::text('textoCheck8','Compras mayores a $5,000.00 favor de incluir el 50% para confirmar su pedido',['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class='' id="validarEstatus">
                                <div class=''><b>*Estatus de cotización:</b></div>
                                <div class="row">
                                    <div class="form-group col-md-7">
                                        <div>{{ Form::radio('estatus','En revisión','true')}} En revisión</div>
                                        <div>{{ Form::radio('estatus','Pendiente de autorización')}} Pendiente de autorización</div>
                                        <div><input type="radio" name="estatus" id="aceptada" value="Aceptada con orden de compra"> Aceptada con orden de compra</div>                                        
                                    </div>
                                    <div class="form-group col-md-5">
                                        <!-- <div>{{ Form::radio('estatus','Cancelada')}} Cancelada</div> -->                                        
                                    </div>                                    
                                </div>
                                <div class=""style="visibility:hidden;" id="divOrdenCompra">
                                    <b>*Orden de compra:</b><input class="form-control" placeholder="Ejemplo: AC286639" name="ordenCompra" type="text">
                                    <b>*Orden de compra:</b>{{ Form::file('archivo',['class' => 'form-control rounded-0','id'=>'archivoOrden','onchange'=>'ValidarImagen2(this);'])}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="" align="center">
                        <button type="button" class="btn btn-success btn-sm" id="nuevaVersion">
                            <span class="glyphicon glyphicon-plus-sign"></span> Agregar servicios
                        </button>
                        <button type="button" class="btn btn-danger btn-sm cancelarAlta">
                            Cancelar cotización
                        </button>
                    </div>
                </div>
        </div>

        <div class="tab-pane fade" id="2">
            <div class="container-fluid">
                <div id="divIdCotizacion">
                {{ Form::hidden('idCotizacionServicios','0',['id'=>'idCotizacionServicios','readonly'])}}
                    <!-- <div class="form-group row">
                        <div class="col-sm-7 numeroCotizacionGenerado" style="visibility:hidden">
                        </div>                
                        
                        {{ Form::text('idCotizacionServicios','0',['id'=>'idCotizacionServicios','readonly'])}}
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
                    </div> -->
                </div>
            </div>
        </div>  
    </div>
</form>
<script>    
    $("#idu").change(function(e) {
        var comboUsuario = document.getElementById("idu");
        var selectedUsuario = comboUsuario.options[comboUsuario.selectedIndex].text;
        document.getElementById("nombreUsuario").value = selectedUsuario;        
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

    // $("#MXN").click(function(){ 
    //     $('#cambio').css('display', 'none');        
    // })
    
    // $("#USD").click(function(){
    //     $('#cambio').css('display', 'block');        

    // })

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
</script>

<script>
    $('.mi-selector').click(function() {
        $('.mi-selector').select2();
    });    
</script>

<script type="text/javascript">
    $(document).ready(function() {
         
        $("#comboSucursal").change(function(e) {
            var comboUsuario = document.getElementById("idu");
            var selectedUsuario = comboUsuario.options[comboUsuario.selectedIndex].text;
            document.getElementById("nombreUsuario").value = selectedUsuario;

            $("#comboContacto").load('{{url('comboContactosCotizacion')}}' + '?r=' + Date.now() + '&idSucursal=' + this.options[this.selectedIndex].value);

            let fechaCotizacion = $("#fechaCotizacion").val();
            let comboSucursal = $("#comboSucursal").val();

            var combo = document.getElementById("comboSucursal");
            var selected = combo.options[combo.selectedIndex].text;

            if (fechaCotizacion != '' && comboSucursal != '') {
                $(".numeroCotizacionGenerado").load('{{url('generarNumeroCotizacionServicios')}}' + '?' + $(this).closest('form').serialize());
            }

            document.getElementById("fob").value = 'FOB: ' + selected;
            
        });        

        $("#nuevaVersion").click(function(e) {

            let fechaCotizacion = $("#fechaCotizacion").val();
            let comboSucursal = $("#comboSucursal").val();
            let archivoCotizacion = $("#archivoCotizacion").val();
            let idCotizacionServicios = $("#idCotizacionServicios").val();

            if (fechaCotizacion == '' || comboSucursal == ''){
                if(fechaCotizacion == ''){
                    $("#fechaCotizacion").css('border-color', 'red');
                    $("#fechaCotizacion").css('color', 'red');
                    alert('El campo *Fecha es obligatorio.');
                }
                if(comboSucursal == ''){
                    $("#comboSucursal").css('border-color', 'red');
                    $("#comboSucursal").css('color', 'red');
                    alert('El campo *Sucursal es obligatorio.');
                }            
            }else if($("[name='tipoMoneda']:checked").val()==undefined){
                $("#validarTipo").css("color", "red");
                alert('El campo *Tipo de moneda es obligatorio.');
            }else if($("[type='checkbox']:checked").val()==undefined){
                $("#validarNotas").css("color", "red");
                $(".notas").css('border-color', 'red');
                alert('El campo *Notas y condiciones comerciales es obligatorio.');
            }else if($("[name='estatus']:checked").val()==undefined){
                $("#validarEstatus").css("color", "red");
                alert('El campo *Estatus es obligatorio.');                                            
            }else{
            
                if(idCotizacionServicios == 0){
                    $("#divIdCotizacion").load('{{url('creandoIdCotizacionServicios')}}' + '?' + $(this).closest('form').serialize());
                }

                $('#tab1').css('visibility', 'hidden');
                $("#tab1").removeClass("active");
                $("#1").removeClass("tab-pane active");
                $("#1").toggleClass("tab-pane fade");
                
                $("#tab2").toggleClass("active");
                $("#2").removeClass("tab-pane fade");
                $("#2").toggleClass("tab-pane active");
                $('#tab2').css('visibility', 'visible');

                // $("#a").load('{{url('guardarCotizacion')}}' + '?' + $(this).closest('form').serialize());
            }
            
        });

        $("#aceptada").click(function(e) {
        //     let archivoCotizacion = $("#archivoCotizacion").val();
        //     if (archivoCotizacion == ''){
        //         $("#archivoCotizacion").css('border-color', 'red');
        //         $("#archivoCotizacion").css('color', 'red');            
        //         alert('El campo *Archivo de cotización es obligatorio.');
        //         e.preventDefault(); 
        //     }else{
                $('#divOrdenCompra').css('visibility', 'visible');
        //     }
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
                let idCotizacionServicios = $("#idCotizacionServicios").val();
                $("#altaVersion").load('{{url('cancelarAltaVersionServicios')}}' + '?r=' + Date.now() + '&idCotizacionServicios=' + idCotizacionServicios);
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
    
    });
</script>

<script>
    $("#monto").on({
        "focus": function(event) {
            $(event.target).select();
        },
        "keyup": function(event) {
            $(event.target).val(function(index, value) {
                return value.replace(/\D/g, "")
                    .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
            });
        }
    });
</script>

<script>
    function ValidarImagen(obj) {
        var uploadFile = obj.files[0];
        let datos4 = $("#archivoCotizacion").val();
        if (!(/\.(jpeg|png|gif|jpg|pdf)$/i).test(uploadFile.name)) {
            alert('El archivo seleccionado no es valido.');
            $('#archivoCotizacion').val('');
        }
    }
</script>
<script>
    function ValidarImagen2(obj) {
        var uploadFile = obj.files[0];
        let datos4 = $("#archivoOrden").val();
        if (!(/\.(jpeg|png|gif|jpg|pdf)$/i).test(uploadFile.name)) {
            alert('El archivo seleccionado no es valido.');
            $('#archivoOrden').val('');
        }
    }
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
        }else if(diasDePago >= 90 && diasDePago<=120){
            calculo = monto * 0.17;
            var financiamiento = parseFloat(calculo);
            document.getElementById("conFinanciamiento").value = (financiamiento+montoEquipo).toFixed(2);
        }
		else if(diasDePago >= 120){
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