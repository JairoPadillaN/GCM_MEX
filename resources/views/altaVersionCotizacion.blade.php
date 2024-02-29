<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script src="{{ asset('assets/js/jquery-1.10.2.js')}}"></script>

{{ Form::open(['route' => 'guardarCotizacion','files'=>true])}}
{{ Form::token()}}

<ul class="nav nav-tabs">
    <li class="active" id='tab1' style="visibility:visible;"><a href="#1" data-toggle="tab"><label for="">Cotización</label></a></li>
    <li class="" id='tab2' style="visibility:hidden;"><a href="#2" data-toggle="tab"><label for="">Asignar equipos</label></a></li>
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
                                        {{ Form::text('cambio',old ('cambio'),['id'=>'monto','class' => 'form-control rounded-0','placeholder'=>'Ejemplo: 20.04'])}}
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
                                        <div>{{Form::radio('estatus','Facturada',false,['disabled'])}} Facturada</div>
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
                    <div class="" align="center" id="botonesIniciales" style="display:block">
                        <button type="button" class="btn btn-success btn-sm" id="nuevaVersion">
                            <span class="glyphicon glyphicon-plus-sign"></span> Agregar equipos
                        </button>
                        <button type="button" class="btn btn-danger btn-sm cancelarAlta">
                            Cancelar cotización
                        </button>
                    </div>
                </div>
        </div>

        <div class="tab-pane fade" id="2">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-7 numeroCotizacionGenerado" style="visibility:hidden">
                    </div>
                    <div id="divIdCotizacion">
                        {{ Form::hidden('idCotizacion','0',['id'=>'idCotizacion','readonly'])}}
                    <!-- </div>
                    <div id="divValidar">                                             -->
                        <div class="form-group col-md-12">
                            <div class="form-group row">                            
                                    <div class="col-sm-3">
                                        <b>*¿Que tipo de equipo cotizas?:</b>
                                    </div>
                                    <div class="col-sm-3" >
                                        <select name='tipoCotizacion' id='tipoCotizacion' class="form-control">
                                            <option value="">Seleccionar opción</option>
                                            <option value="reparacion">Reparación  ua</option>
                                            <option value="nuevo">Nuevo</option>
                                            <option value="refurbished">Refurbished y/o Repair</option>
                                        </select>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row seleccionarEquipo" style="visibility:hidden;">
                    <div class="form-group col-md-6 seleccionarEquipo">
                        <div class="form-group row">                            
                            <div class="col-sm-3"><b>*Seleccionar equipo:</b></div>
                            <div class="col-sm-9">
                                <select name='idEquipos' id='comboEquipos' class="form-control rounded-0 mi-selector">
                                    <option value="">Seleccionar opción</option>                                       
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-5" align="right">
                        <button type="button" class="btn btn-default" id="atras" style="color: #fff;background-color: #4d42ff59;border-color: #4d42ff59;">
                            <span class="glyphicon glyphicon-arrow-left"></span> Cambiar de sucursal
                        </button>
                    </div>
                </div>
                <div id="reporteCotizacionSucursal"></div>
                
                <div id="reporteReparaciones">
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
            </div>
        </div>  
    </div>
</form>
<script>
    $('.mi-selector').click(function() {
        $('.mi-selector').select2({width:'100%'});
    });    
</script>

<script type="text/javascript">
    $("#idu").change(function(e) {
        var comboUsuario = document.getElementById("idu");
        var selectedUsuario = comboUsuario.options[comboUsuario.selectedIndex].text;
        document.getElementById("nombreUsuario").value = selectedUsuario;        
    });


    $("#comboSucursal").change(function(e) {
        $("#reporteCotizacionSucursal").empty();
        var comboUsuario = document.getElementById("idu");
        var selectedUsuario = comboUsuario.options[comboUsuario.selectedIndex].text;
        document.getElementById("nombreUsuario").value = selectedUsuario;

        $("#comboContacto").load('{{url('comboContactosCotizacion')}}' + '?r=' + Date.now() + '&idSucursal=' + this.options[this.selectedIndex].value);

        let fechaCotizacion = $("#fechaCotizacion").val();
        let comboSucursal = $("#comboSucursal").val();

        var combo = document.getElementById("comboSucursal");
        var selected = combo.options[combo.selectedIndex].text;


        if (fechaCotizacion != '' && comboSucursal != '') {
            $(".numeroCotizacionGenerado").load('{{url('generarNumeroCotizacion')}}' + '?' + $(this).closest('form').serialize());
        }            
        $("#comboEquipos").load('{{url('comboEquiposCotizacion')}}' + '?r=' + Date.now() + '&idSucursal=' + this.options[this.selectedIndex].value);        
        document.getElementById("fob").value = 'FOB: ' + selected;

    });        

    $("#nuevaVersion").click(function(e) {

        let fechaCotizacion = $("#fechaCotizacion").val();
        let comboSucursal = $("#comboSucursal").val();
        let archivoCotizacion = $("#archivoCotizacion").val();
        let idCotizacion = $("#idCotizacion").val();

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
            
            if(idCotizacion == 0){
                // $("#botonesIniciales").load('{{url('validarCotizacionesErroneas')}}' + '?' + $(this).closest('form').serialize());
                $("#divIdCotizacion").load('{{url('creandoIdCotizacion')}}' + '?' + $(this).closest('form').serialize());
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
        // let archivoCotizacion = $("#archivoCotizacion").val();
        // if (archivoCotizacion == ''){
        //     $("#archivoCotizacion").css('border-color', 'red');
        //     $("#archivoCotizacion").css('color', 'red');            
        //     alert('El campo *Archivo de cotización es obligatorio.');
        //     e.preventDefault(); 
        // }else{
            $('#divOrdenCompra').css('visibility', 'visible');
        // }
    });


    $("#atras").click(function(e) {

        $('#tab2').css('visibility', 'hidden');
        $("#tab2").removeClass("active");
        $("#2").removeClass("tab-pane active");
        $("#2").toggleClass("tab-pane fade");
        $("#reporteReparaciones").empty();

        $("#tab1").toggleClass("active");
        $("#1").removeClass("tab-pane fade");
        $("#1").toggleClass("tab-pane active");
        $('#tab1').css('visibility', 'visible');

        $('#botonParaGuardar').css('visibility', 'hidden');

    });

    $(".cancelarAlta").click(function(e) {

        var validarCuantos = $('.validarCuantos').val();
        if (typeof validarCuantos === 'undefined' || validarCuantos == 0) {
            let idCotizacion = $("#idCotizacion").val();
            $("#altaVersion").load('{{url('cancelarAltaVersion')}}' + '?r=' + Date.now() + '&idCotizacion=' + idCotizacion);
            location.reload();   
        }else{
            alert('Error: Debe eliminar los equipos asignados para poder cancelar la cotización');
            e.preventDefault(); 
        }
    });

    $("#guardar").click(function(e){
        var validarCuantos = $('.validarCuantos').val();
        if (validarCuantos == 0) {
            alert('Error: La cotización no puede quedarse sin equipos, debe cancelar el alta.');
            e.preventDefault();    
        }        
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
    $("#comboEquipos").change(function(){
        $("#reporteReparaciones").load('{{url('reparaciones')}}' + '?' + $(this).closest('form').serialize());
    });

    // $("#MXN").click(function(){ 
    //     $('#cambio').css('display', 'none');        
    // })
    
    // $("#USD").click(function(){
    //     $('#cambio').css('display', 'block');        

    // })
</script>