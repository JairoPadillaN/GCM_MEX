
@extends('principal')
@section('contenido')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">
{{Form::open(['id' => 'formularioEntradaAlmacen','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Registro Entrada Almacén</h1>
        </div>

        <!-- TABS -->
        <ul class="nav nav-tabs">
            <li class="active" id='tab1'><a href="#datosEntrada" data-toggle="tab"><label for="">Datos de la entrada</label></a></li>
            <li class="" id='tab2' style="pointer-events:none;opacity:0.4;"><a href="#registroProductos" data-toggle="tab"><label for="">Recepción de productos</label></a></li>
        </ul>

        <!-- Contenido tabs -->

        <div class="tab-content">
            <div class="tab-pane active" id="datosEntrada" role="tabpanel" aria-labelledby="home-tab">
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group col-md-7">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Registrado por:</div>
                                    <div>
                                        <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu')!!}">
                                        <input type="text" name="nombreUsuarioLogin" class="form-control" readonly="true" value="{!! Session::get('sesionname')!!} {!! Session::get('sesionpaterno')!!} {!!Session::get('sesionmaterno')!!}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">* Fecha de entrada:</div>
                                    <div>
                                        {{Form::date('fechaEntrada',\Carbon\Carbon::now(),['class' => 'form-control','id'=>'fechaEntrada'])}}
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <div class="sub-title">* Proveedor:</div>
                                <div>
                                    <select name='idProveedor' class="form-control" id="idProveedor">
                                        <option value=''>Seleciona proveedor</option>
                                        @foreach($proveedor as $prov)
                                            <option value='{{$prov->idProveedor}}'>{{$prov->razonSocialProv}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="">
                                <div class="sub-title">* Recibido en almacén por:</div>
                                <div>
                                    <input type="text" class="form-control" name="recibidoPor" id="recibidoPor" placeholder="Ejemplo: Luis Sánchez Júarez">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div style="text-align:center">
                                <button type="button" class="btn btn-success" id="crearEntrada">
                                    <span class="glyphicon glyphicon-plus-sign"></span> Recepción de productos
                                </button>
                                <a href="{{asset('reporteEntradaAlmacen')}}"><button type="button" class="btn btn-danger">Cancelar</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="registroProductos" role="tabpanel" aria-labelledby="profile-tab">
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group col-md-7">

                            <input type="hidden" name="idEntradaAlmacen" id="idEntradaAlmacen">
                            <input type="hidden" name="idFactura" id="idFactura">
                            <input type="hidden" name="idProducto" id="idProducto">
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">* Orden de compra:</div>
                                    <div>
                                        <select name='idOrden' class="form-control" id="idOrden">
                                            <option value=''>Selecionar orden</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">* Notas De Entrada:</div>
                                    <div>
                                        <select name='idNotaEntrada' class="form-control" id="idNotaEntrada">
                                            <option value=''>Selecionar nota</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-sm-6">
                                    <div class ="sub-title">Empresa:</div>
                                    <div>
                                        <input type="text" class="form-control" name="nombreEmpresa" id="nombreEmpresa" readonly="true">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class ="sub-title">Almacén:</div>
                                    <div>
                                        <input type="text" class="form-control" name="almacen" id="almacen" readonly="true">
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <div id="contenedor_productos_en_oc" style="display:">
                                    <div class="sub-title">* Seleccionar producto:</div>
                                    <select name='idDetalleNotasEntrada' class="form-control" id="idDetalleNotasEntrada">
                                        <option value=''>Selecionar producto</option>
                                    </select>
                                </div>
                                <div id="contenedor_partes_venta" style="display:none">
                                    <div class="sub-title">Seleccionar marca: </div>
                                    <div>
                                        <select name='idMarcaRefa' id='idMarcaRefa' class="form-control">
                                            <option value="">Seleccionar marca</option>
                                            @foreach($consultaMarcaPartesVenta as $consultaMpV)
                                                <option value='{{$consultaMpV->idMarcaRefa}}'>{{$consultaMpV->marcaRefaccion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="sub-title">Seleccionar SKU: </div>
                                    <div>
                                        <select name='idPartesVenta' id='idPartesVenta' class="form-control">
                                            <option value="">Seleccionar sku</option>                                        
                                        </select>
                                    </div>
                                    <div hidden>
                                        <div class="sub-title">Seleccionar motivo de entrada: </div>
                                        <div>
                                            <select name='op_motivo_entrada' id='op_motivo_entrada' class="form-control">
                                                <option value="">Seleccionar opción</option>
                                                <option value="1">Garantía</option>
                                                <option value="2">Merma</option>
                                                <option value="3">Devolución</option>
                                                <option value="4" selected>Stock</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="sub-title">Número de parte:</div>
                                    <div>
                                        {{Form::text('numeroParte','',['class' => 'form-control','id'=>'numeroParte'])}}
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="sub-title">Número de serie:</div>
                                    <div>
                                        {{Form::text('numeroSerie','',['class' => 'form-control','id'=>'numeroSerie'])}}
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="sub-title">Modelo:</div>
                                    <div>
                                        <input class="form-control" type='text' name='modeloProducto' id='modeloProducto'>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Presentación:</div>
                                    <div>
                                        <input type="radio" name="presentacion" value="Pieza" id="pieza" checked>Pieza &nbsp;&nbsp;
                                        <input type="radio" name="presentacion" value="Equipo" id="">Equipo &nbsp;&nbsp;
                                        <input type="radio" name="presentacion" value="Servicio" id="">Servicio
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Unidades por presentación:</div>
                                    <div>
                                        <input type="number" name="unidadesPorPresentacion" id="unidadesPorPresentacion" placeholder="Ejemplo: 1" class="form-control" min="1">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                <div class="sub-title">* Cantidad pendiente de recepción:</div>
                                    <div>
                                        <input type="number" name="cantidadPendientePorEntregar" id="cantidadPendientePorEntregar" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">* Cantidad recibida:</div>
                                    <div>
                                        <input type="number" name="cantidadRecibida" id="cantidadRecibida" placeholder="Ejemplo: 1" class="form-control" min="1">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-5">

                            <div class="sub-title">Foto placa</div>
                            <div>
                                {{Form::file('fotoPlaca',['id'=>'fotoPlaca'])}}
                            </div>
                            
                            <div class="sub-title">Foto principal</div>
                            <div>
                                {{Form::file('fotoPrincipal',['id'=>'fotoPrincipal'])}}
                            </div>

                            <div class="sub-title">Observaciones:</div>
                            <div>
                                {{Form::textarea('observaciones','',['class' => 'form-control', 'placeholder' => 'Escribe aqui las observaciones necesarias', 'rows' =>'5','id'=>'observaciones'])}}
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div style="text-align:center">
                            <button type="button" class="btn btn-success" id="ingresarProducto">
                                <span class="glyphicon glyphicon-plus-sign"></span> Ingresar producto
                            </button>
                        </div>
                    </div>

                    <!-- div para reporte agregando -->
                    <div class="">
                        <div id="reporteProductosAgregados" style="margin-top:15px">
                            <div class="alert alert-warning" style="text-align:center">Sin entradas</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" style="text-align:center">                            
                            <a href="{{asset('reporteEntradaAlmacen')}}"><button type="button" class="btn btn-default">Finalizar</button></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="text-align:right">
                            <button type="button" class="btn btn-info" onclick="reload()">Nueva Entrada</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
    var idOrden;
    var oc_mov_internos = [];

    $(document).ready(function(){
        $("#idOrden").select2({width:'100%'});
        $("#idDetalleNotasEntrada").select2({width:'100%'});
        $("#idProveedor").select2({width:'100%'});
        $("#idPartesVenta").select2({width:'100%'});
        $("#idMarcaRefa").select2({width:'100%'});
        
        $.ajax({
            url: "{{ route('validar_oc_mov_internos') }}",
            type: "get",
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false
        }).done(function(data){
            data.forEach(element => {
                oc_mov_internos.push(element.idOrden)
            });
        });
    });

    $("#crearEntrada").click(function(e) {

        var idu = $('#idu').val();
        var fechaEntrada = $('#fechaEntrada').val();
        var idProveedor = $('#idProveedor').val();
        var recibidoPor = $('#recibidoPor').val();

        if(idu == '' || fechaEntrada == '' || idProveedor == '' || recibidoPor == ''){
            if(idu == ''){
                alert('Registrado por: es requerido')
                $("#idu").css('border-color', 'red');
                $("#idu").css('color', 'red');
            }
            if(fechaEntrada == ''){
                alert('La fecha es requerida')
                $("#fechaEntrada").css('border-color', 'red');
                $("#fechaEntrada").css('color', 'red');
            }
            if(idProveedor == ''){
                alert('El proveedor es requerido')
                $("#idProveedor").css('border-color', 'red');
                $("#idProveedor").css('color', 'red');
            }
            if(recibidoPor == ''){
                alert('Recibido por: es requerido')
                $("#recibidoPor").css('border-color', 'red');
                $("#recibidoPor").css('color', 'red');
            }
        }else{
            var formData = new FormData(document.getElementById("formularioEntradaAlmacen"));

            $.ajax({
                url: "{{ route('crearEntradaAlmacen') }}",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            }).done(function(data){
                $("#idEntradaAlmacen").val(data);
                document.getElementById("tab1").style.cssText = "pointer-events:none;opacity:0.4;";
                $("#tab1").removeClass("active");
                $("#datosEntrada").removeClass("tab-pane active");
                $("#datosEntrada").toggleClass("tab-pane fade");

                $("#tab2").toggleClass("active");
                $("#registroProductos").removeClass("tab-pane fade");
                $("#registroProductos").toggleClass("tab-pane active");
                document.getElementById('tab2').removeAttribute("style");
            });
        }
    });

    $("#idProveedor").change(function(){
        idProveedor = this.options[this.selectedIndex].value;
        crearSelectOC(idProveedor);
    });

    $("#idOrden").change(function(){
        idOrden = this.options[this.selectedIndex].value;
        // // Limpiando el selecta alterar
        $("#idDetalleNotasEntrada").empty();
        mensajeCargandoCombo('idDetalleNotasEntrada');

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('obtenerEmpresaAndProvedor') }}",
            type: "post",
            data: {idOrden: idOrden},
        })
        .done(function(res){
            $("#nombreEmpresa,#almacen").val(res[0].nombreEmpresa);
            $("#idProveedor").val(res[0].idProveedor);
            crearSelectNotasEntrada();
        });

    });

    $("#idDetalleNotasEntrada").change(function(){
        // obteneidno el idDetalleNotasEntrada para la cosulta en el back
        let idDetalleNotasEntrada = this.options[this.selectedIndex].value;

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('obtenerDatosProductos') }}",
            type: "post",
            data: {idDetalleNotasEntrada: idDetalleNotasEntrada},
        })
        .done(function(data){
            $("#numeroParte").val(data[0].numeroParte);
            $("#idFactura").val(data[0].idFactura);
            $("#idProducto").val(data[0].idProducto);
            $("#cantidadPendientePorEntregar,#cantidadRecibida").val(data[0].pendientePorIngresar);
            document.getElementById("cantidadRecibida").removeAttribute("max");
            document.getElementById("cantidadRecibida").setAttribute("max", data[0].pendientePorIngresar);
        });

    });

    $("#ingresarProducto").click(function(){
        $("#ingresarProducto").css('pointer-events','none');
        $('#ingresarProducto').css("opacity","0.6");

        let idOrden = parseInt($('#idOrden').val());
        let idProveedor = $('#idProveedor').val();
        let nombreEmpresa = $('#nombreEmpresa').val();
        let almacen = $('#almacen').val();
        let idDetalleNotasEntrada = $('#idDetalleNotasEntrada').val();
        let unidadesPorPresentacion = parseInt($('#unidadesPorPresentacion').val() == '' ? 0 : $('#unidadesPorPresentacion').val() );
        let cantidadPendientePorEntregar = parseInt($('#cantidadPendientePorEntregar').val() == '' ? 0 : $('#cantidadPendientePorEntregar').val() );
        let cantidadRecibida = parseInt($('#cantidadRecibida').val() == '' ? 0 : $('#cantidadRecibida').val() );
        let idNotaEntrada = document.querySelector("#idNotaEntrada").value;
        let idPartesVenta = document.querySelector("#idPartesVenta").value;
        // let op_motivo_entrada = document.querySelector("#op_motivo_entrada").value;

        if(idOrden == '' || idProveedor == '' || nombreEmpresa == '' || almacen == ''){
            $('#ingresarProducto').css("opacity","");
            $("#ingresarProducto").css('pointer-events','');

            if(idOrden == ''){
                alert('La Orden de compra es requerida')
                $("#idOrden").css('border-color', 'red');
                $("#idOrden").css('color', 'red');
            }
            if(idProveedor == ''){
                alert('El proveedor es requerido')
                $("#idProveedor").css('border-color', 'red');
                $("#idProveedor").css('color', 'red');
            }
            if(nombreEmpresa == ''){
                alert('El nombre de la empresa es requerido')
                $("#nombreEmpresa").css('border-color', 'red');
                $("#nombreEmpresa").css('color', 'red');
            }
            if(almacen == ''){
                alert('El almacén requerido')
                $("#almacen").css('border-color', 'red');
                $("#almacen").css('color', 'red');
            }
        // }else if(oc_mov_internos.includes(idOrden) && idDetalleNotasEntrada == ''){
        }else if(oc_mov_internos.includes(idOrden) && idDetalleNotasEntrada != ''){
            $('#ingresarProducto').css("opacity","");
            $("#ingresarProducto").css('pointer-events','');
            if(idDetalleNotasEntrada == ''){
                alert('El producto es obligatorio')
                $("#idDetalleNotasEntrada").css('border-color', 'red');
                $("#idDetalleNotasEntrada").css('color', 'red');
            }
        }else if(oc_mov_internos.includes(idOrden) && idPartesVenta == "" ){
            $('#ingresarProducto').css("opacity","");
            $("#ingresarProducto").css('pointer-events','');
            if(idPartesVenta == ''){
                alert('El producto es obligatorio')
                $("#idPartesVenta").css('border-color', 'red');
                $("#idPartesVenta").css('color', 'red');
            }
        
        // else if(oc_mov_internos.includes(idOrden) && op_motivo_entrada == ""){
        //     $('#ingresarProducto').css("opacity","");
        //     $("#ingresarProducto").css('pointer-events','');

        //     alert('El motivo de entrada es obligatorio')
        //     $("#op_motivo_entrada").css('border-color', 'red');
        //     $("#op_motivo_entrada").css('color', 'red');
        // }else if(oc_mov_internos.includes(idOrden) && (cantidadPendientePorEntregar < 1 || unidadesPorPresentacion < 1 || cantidadRecibida < 1 || cantidadRecibida > cantidadPendientePorEntregar)){
        }else if(oc_mov_internos.includes(idOrden) && (unidadesPorPresentacion < 1 || cantidadRecibida < 1)){
            $('#ingresarProducto').css("opacity","");
            $("#ingresarProducto").css('pointer-events','');

            if(unidadesPorPresentacion < 1){
                alert('Las unidades por presentación deben ser mayor a 0')
                $("#unidadesPorPresentacion").css('border-color', 'red');
                $("#unidadesPorPresentacion").css('color', 'red');
            }
            // if(cantidadPendientePorEntregar < 1){
            //     $("#cantidadPendientePorEntregar").css('border-color', 'red');
            //     $("#cantidadPendientePorEntregar").css('color', 'red');
            // }
            // if(cantidadRecibida < 1 || cantidadRecibida > cantidadPendientePorEntregar){
            if(cantidadRecibida < 1){
                // alert('La cantidad recibida debe ser mayor a 0 y menor o igual a '+cantidadPendientePorEntregar)
                alert('La cantidad recibida debe ser mayor a 0')
                $("#cantidadRecibida").css('border-color', 'red');
                $("#cantidadRecibida").css('color', 'red');
            }
        }else if(oc_mov_internos.includes(idOrden) && cantidadRecibida < 1){
            $('#ingresarProducto').css("opacity","");
            $("#ingresarProducto").css('pointer-events','');

            alert('La cantidad recibida debe ser mayor a 0 ')
            $("#cantidadRecibida").css('border-color', 'red');
            $("#cantidadRecibida").css('color', 'red');
        }else{
            var formData = new FormData(document.getElementById("formularioEntradaAlmacen"));

            $.ajax({
                url: "{{ route('agregarProductosAentrada') }}",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            }).done(function(data){
                limpiarInputs()
                crearSelectOC(idProveedor);
                cargarTablaProductosAgregados($('#idEntradaAlmacen').val());
                $('#ingresarProducto').css("opacity","");
                $("#ingresarProducto").css('pointer-events','');
            });
        }
    });

    $("#idNotaEntrada").change(function(){
        let idOrden = parseInt($('#idOrden').val());
        let idNotaEntrada = this.options[this.selectedIndex].value;
        
        crearSelectProductos(idNotaEntrada);
        console.log(oc_mov_internos)
        console.log(idOrden)

        if(oc_mov_internos.includes(idOrden)){
            console.log("si es")
            document.querySelector("#contenedor_productos_en_oc").style.display = "none"
            document.querySelector("#contenedor_partes_venta").style.display = ""
            document.querySelector("#op_motivo_entrada").value = '4';
        }else{
            console.log("no")
            document.querySelector("#contenedor_productos_en_oc").style.display = ""
            document.querySelector("#contenedor_partes_venta").style.display = "none"
            document.querySelector("#op_motivo_entrada").value = '';
        }
    });

    $("#idMarcaRefa").change(function() {
        mensajeCargandoCombo('idPartesVenta')
        $("#idPartesVenta").load('{{url('obtenerPartesVenta')}}' + '?idMarcaRefa='  + this.value) ;
    });

    // funcion que hace mostrar un mesnaje en combos dinamicos mientras carga los datos
    function mensajeCargandoCombo(idElemento){
        $('#'+idElemento+'').val('')
        // $('#'+idElemento+'').attr('disabled',true)
        var select = document.getElementById(idElemento);
        var opt = document.createElement('option');
        opt.setAttribute('value','');
        opt.innerHTML='cargando...';
        opt.setAttribute('selected','selected');
        select.appendChild(opt);
    }

    function reload(){
        // cargando nuevamente la pagina
        location.reload();
    }

    function crearSelectNotasEntrada(){
        // obteneidno el idOrden para la cosulta en el back
        idOrden = document.getElementById("idOrden").value;
        // obteniendo el select a modificar
        var select = document.getElementById('idNotaEntrada');

        // peticion a la base
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('obtenerNotasEntradaOC') }}",
            type: "post",
            data: {idOrden: idOrden},
        })
        .done(function(data){
            // Limpiando el select
            $("#idNotaEntrada").empty();

            // creando el select con los productos en la OC
            select.innerHTML += "<option value=''>Selecionar nota</option>";
            data.forEach(data => {
                select.innerHTML += "<option value='"+data.idNotaEntrada+"'>"+data.numeroNota+"</option>";
            });
        });

    }

    function crearSelectProductos(idNotaEntrada){
        // obteniendo el select a modificar
        var select = document.getElementById('idDetalleNotasEntrada');

        // peticion a la base
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('obtenerProductosNE') }}",
            type: "post",
            data: {idNotaEntrada: idNotaEntrada},
        })
        .done(function(data){
            // Limpiando el select
            $("#idDetalleNotasEntrada").empty();

            // creando el select con los productos en la OC
            select.innerHTML += "<option value=''>Selecionar producto</option>";
            data.forEach(data => {
                select.innerHTML += "<option value='"+data.idDetalleNotasEntrada+"'>"+data.producto+"</option>";
            });
        });
        
    }

    function crearSelectOC(idProveedor){

        mensajeCargandoCombo('idOrden');

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('obtenerOCproveedor') }}",
            type: "post",
            data: {idProveedor: idProveedor},
        })
        .done(function(data){

            // Limpiando el select
            $("#idOrden").empty();
            // obteniendo el select a modificar
            var select = document.getElementById('idOrden');

            // creando el select con las OC del proveedor
            select.innerHTML += "<option value=''>Selecionar orden</option>";
            data.forEach(data => {
                select.innerHTML += "<option value='"+data.idOrden+"'>"+data.codigoOrden+"</option>";
            });
        });
    }
    

    function limpiarInputs(){
        document.getElementById("idFactura").value = "";
        document.getElementById("nombreEmpresa").value = "";
        document.getElementById("almacen").value = "";
        document.getElementById("numeroParte").value = "";
        document.getElementById("numeroSerie").value = "";
        document.getElementById("modeloProducto").value = "";
        // $("[name='presentacion']").prop("checked", false);
        $("#pieza").prop("checked", true);
        document.getElementById("unidadesPorPresentacion").value = "";
        document.getElementById("cantidadPendientePorEntregar").value = "";
        document.getElementById("cantidadRecibida").value = "";
        document.getElementById("fotoPlaca").value = "";
        document.getElementById("fotoPrincipal").value = "";
        document.getElementById("observaciones").value = "";

        document.getElementById("idMarcaRefa").value = "";
        document.getElementById("idPartesVenta").value = "";
        // document.getElementById("op_motivo_entrada").value = "";

        // // limpiando y reiniciando los combos con funcion select2
        $("#idOrden").val('').trigger('change')
        $("#idDetalleNotasEntrada").empty();
        $("#idNotaEntrada").empty();
    }

    // funcion que hace mostrar un mesnaje en combos dinamicos mientras carga los datos
    function mensajeCargandoCombo(idElemento){
        $('#'+idElemento+'').val('')
        // $('#'+idElemento+'').attr('disabled',true)
        var select = document.getElementById(idElemento);
        var opt = document.createElement('option');
        opt.setAttribute('value','');
        opt.innerHTML='cargando...';
        opt.setAttribute('selected','selected');
        select.appendChild(opt);
    }

    function ValidarArchivo(obj) {
        var uploadFile = obj.files[0];
        let datos4 = $("#archivo").val();
        if (!(/\.(jpeg|png|gif|jpg|pdf)$/i).test(uploadFile.name)) {
            alert('El archivo seleccionado no es valido.');
            $('#archivo').val('');
        }
    }

    function cargarTablaProductosAgregados(idEntradaAlmacen){
        var tablita = $("#reporteProductosAgregados").anexGrid({
            class: 'table table-striped table-bordered table-hover tablaAnexgrid',
            columnas: [
                {leyenda: 'SKU', columna: 'sku' , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Descripción', columna: 'producto', style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Orden compra', columna: 'ordenCompra', style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: '# Serie', columna: 'numeroSerie', style:'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Cantidad Recibida', columna: 'cantidadRecibida', style: 'background-color: #C5EBFB;text-align:center'},
                // {leyenda: 'Obs', columna: 'observaciones', style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: '',style: 'background-color: #C5EBFB;text-align:center'},
            ],
            modelo: [
                { propiedad: 'sku' ,style:'text-align:center'},
                { propiedad: 'producto' ,style:'text-align:center'},
                { propiedad: 'ordenCompra' ,style:'text-align:center'},
                { propiedad: 'numeroSerie' ,style:'text-align:center'},
                { propiedad: 'cantidadRecibida' ,style:'text-align:center'},
                // { propiedad: 'observaciones' ,style:'text-align:center'},
                { propiedad: 'idProductoEntrada',style:'text-align:center', 
                    formato:function(tr, obj,celda){
                        let botones = '';
                        botones += anexGrid_boton({
                            class: 'btn btn-danger btn-sm eliminarProductoEntrada',
                            contenido: '<i class="fa fa-trash"></i>',
                            value: obj.idProductoEntrada
                        });

                        botones += anexGrid_boton({
                            class: 'btn btn-default btn-sm etiqueta',
                            contenido: '<i class="fa fa-qrcode"></i>',
                            value: obj.idProductoEntrada
                        });

                        return botones;
                    }
                },

            ],
            url: 'anexgrid_get_agregadosAentrada',
            parametros:[idEntradaAlmacen],
            paginable: true,
            filtrable: true,
            limite: [5, 10],
            columna: 'idProductoEntrada',
            columna_orden: 'DESC'
        });

        tablita.tabla().on('click', '.eliminarProductoEntrada', function(){
            eliminarProducto($(this).val(),idEntradaAlmacen)
        });

        tablita.tabla().on('click', '.etiqueta', function(){
            etiqueta($(this).val())
        });
    }

    function eliminarProducto(idEliminar,idEntradaAlmacen){
        var idProveedor = $('#idProveedor').val();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('eliminarProductoEntrada') }}",
            type: "post",
            data: {idProductoEntrada: idEliminar},
        })
        .done(function(data){
            limpiarInputs()
            crearSelectOC(idProveedor);
            cargarTablaProductosAgregados(idEntradaAlmacen)
        });  
    }
    
    function etiqueta(idProductoEntrada){
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('generarEtiquetaEntradaAlmacen') }}",
            type: "post",
            xhrFields: {responseType: 'blob'},
            data: {idProductoEntrada: idProductoEntrada},
            success: function(response) {
                let blob = new Blob([response]);
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "etiqueta.pdf";
                console.log (link);
                link.click();
            },
        });
    }
</script>
@stop