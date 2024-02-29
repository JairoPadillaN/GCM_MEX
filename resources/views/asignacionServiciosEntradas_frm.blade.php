
    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <div class="col-md-6">
                    <div hidden>
                        <input type="text" name="idGrupoAsignacion" id="idGrupoAsignacion">
                        <input type="text" name="idProducto" id="idProducto">
                        <input type="text" name="idProducto_destino" id="idProducto_destino">
                    </div>
                    
                    <div class="sub-title">* Orden de compra:</div>
                    <div>
                        <select name='idOrden' class="form-control" id="idOrden" style="pointer-events:none;">
                            <option value="">Seleccionar orden</option>
                            @foreach($ordenesCompra as $orden)
                                <option value='{{$orden->idOrden}}'>{{$orden->codigoOrden}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="sub-title">* Productos en almacén:</div>
                    <div>
                        <select name='idProductoEntrada' class="form-control" id="idProductoEntrada" style="pointer-events:none;">
                            <option value="">Seleccionar producto</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="sub-title">* Tipo de salida:</div>
                    <div>
                        <input type="radio" name="tipoAsignacion" value="a_servicio" id="tipoAsignacion_servicio" checked>
                        <label for="tipoAsignacion_servicio">Asignacion a servicio</label>
                    </div>
                    <!-- Se oculta a peticion de joel 07/08/23 -->
                    <div style="display:none">
                        <input type="radio" name="tipoAsignacion" value="a_oc" id="tipoAsignacion_oc">
                        <label for="tipoAsignacion_oc">Traspasos entre almacenes</label>
                    </div>
                    <div>
                        <input type="radio" name="tipoAsignacion" value="a_otro" id="tipoAsignacion_otro">
                        <label for="tipoAsignacion_otro">Otro</label>
                    </div>
                </div>
            </div>

            <div class="row" id="contenedor_servicio_asignar" style="display:">
                <div class="col-md-6">
                    <div class="sub-title">* Asignar al servicio:</div>
                    <div>
                        <select name='idFactura' class="form-control" id="idFactura">
                            <option value="">Seleccionar servicio</option>
                            @foreach($servicios as $servicio)
                                <option value='{{$servicio->idFactura}}'>{{$servicio->idServicios}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="div" id="contenedor_oc_asignar" style="display:none">
                <div class="row">
                    <div class="col-md-6">
                        <div class="sub-title">* Orden de compra destino:</div>
                        <div>
                            <select name='oc_destino' class="form-control" id="oc_destino" style="">
                                <option value="">Asignar a orden:</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="sub-title">* Servicio de referencia:</div>
                        <div>
                            <select name='id_factura_referencia' class="form-control" id="id_factura_referencia">
                                <option value="">Seleccionar servicio</option>
                                @foreach($servicios as $servicios_referencia)
                                    <option value='{{$servicios_referencia->idFactura}}'>{{$servicios_referencia->idServicios}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="sub-title">Cantidad solicitada:</div>
                        <input type="number" name="cantidad_solicitada_oc" id="cantidad_solicitada_oc" class="form-control" readonly>
                    </div>
                    <div class="col-md-6">
                        <div class="sub-title">Cantidad entregada:</div>
                        <input type="number" name="cantidad_entregada_oc" id="cantidad_entregada_oc" class="form-control" readonly>
                    </div>
                </div>
            </div>            

            <div id="contenedor_cantidades_a_servicio" style="display:">
                <div class="row">
                    <div class="col-md-6">
                        <div class="sub-title">Cantidad solicitada:</div>
                        <input type="number" name="cantidad_solicitada_servicio" id="cantidad_solicitada_servicio" class="form-control" readonly>
                    </div>
                    <div class="col-md-6">
                        <div class="sub-title">Cantidad entregada:</div>
                        <input type="number" name="cantidad_entregada_servicio" id="cantidad_entregada_servicio" class="form-control" readonly>
                    </div>
                </div>
            </div>

            <div id="contenedor_otras_salidas" style="display:none">
                <div class="row">
                    <div class="col-md-6">
                        <div class="sub-title">Seleccionar motivo:</div>
                        <select name='op_motivo_salida' id='op_motivo_salida' class="form-control">
                            <option value="">Seleccionar opción</option>
                            <option value="1">Garantía</option>
                            <option value="2">Merma</option>
                            <option value="3">Devolución</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row" id="contenedor_cantidad_almacen_y_asigar" style="display:">
                <div class="col-md-6">
                    <div class="sub-title">* Cantidad en almacén:</div>
                    <input type="number" name="cantidadEnAlmacen" id="cantidadEnAlmacen" class="form-control" readonly>
                </div>
                <div class="col-md-6">
                    <div class="sub-title">* Cantidad a salir:</div>
                    <input type="number" name="cantidadAservicio" id="cantidadAservicio" class="form-control">
                </div>
            </div>

        </div>
        
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-6">
                    <div class="sub-title">* Fecha de salida:</div>
                    {{Form::date('fechaAsignacion',\Carbon\Carbon::now(),['class' => 'form-control','id'=>'fechaAsignacion'])}}
                </div>
                <div class="col-md-6">
                    <div class="sub-title">* Atendió:</div>
                    <input type="radio" name="quienLLevo" value="Ocurre" id="ocurre" checked>Ocurre &nbsp;&nbsp;
                    <input type="radio" name="quienLLevo" value="Personal" id="">Personal
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="sub-title">Detalles:</div>
                    <textarea name="detalles" id="detalles" cols="" rows="" class="form-control"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="sub-title">Evidencia:</div>
                    <input type="file" name="evidencia" id="evidencia">
                </div>
            </div>
        </div>
    </div> 
    <div class="row" style="text-align:center;margin-top:8px">
        <button type="button" class="btn btn-success" id="asignarAservicio">
            <span class="glyphicon glyphicon-plus-sign"></span> Asignar
        </button>
    </div>

    <!-- div para reporte agregando -->
    <div class="">
        <div id="reporteProductosAgregados" style="margin-top:15px" class="table-responsive">
            <div class="alert alert-warning" style="text-align:center">Sin asignaciones</div>
        </div>
    </div>

    <!-- div para reporte agregando por OC-->
    <div class="">
        <div id="reporteProductosAgregadosPorOC" style="margin-top:15px" class="table-responsive">                    
        </div>
    </div>

    <script>
        var radio_asignacion_servicio = document.querySelector("#tipoAsignacion_servicio")
        var radio_asignacion_oc = document.querySelector("#tipoAsignacion_oc")
        var radio_asignacion_otro = document.querySelector("#tipoAsignacion_otro")
        var select_idOrden = document.querySelector("#idOrden")
        var select_oc_destino = document.querySelector("#oc_destino");
        var select_producto_en_almacen = document.querySelector("#idProductoEntrada");
        var optionSelected;

        $("#idProductoEntrada").change(crear_select_oc_destino);
        $("#oc_destino").change(obtener_cantidad_solicitada);
        radio_asignacion_servicio.addEventListener('click', asignar_servicio);
        radio_asignacion_oc.addEventListener('click', asignar_oc);
        radio_asignacion_otro.addEventListener('click', asignar_otro);
        $("#idFactura").change(obtener_cantidad_solicitada_servicio);


        $("#idProductoEntrada").change(function(){
            obtenerDatosProducto()
            $("#idFactura").val('').trigger('change')
        });

        $("#asignarAservicio").click(function(e) {

            var idOrden = $('#idOrden').val();
            var idProductoEntrada = $('#idProductoEntrada').val();
            var cantidadEnAlmacen = parseInt($('#cantidadEnAlmacen').val());
            var idFactura = $('#idFactura').val();
            var cantidadAservicio = parseInt($('#cantidadAservicio').val());
            var fechaAsignacion = $('#fechaAsignacion').val();

            if (idOrden == '' || idProductoEntrada == ''  || fechaAsignacion == '' || cantidadEnAlmacen < 1 || cantidadAservicio < 1 || cantidadAservicio > cantidadEnAlmacen){
                                
                if (idOrden == ''){
                    alert('La orden de compra es requerida')
                    $("#idOrden").css('border-color', 'red');
                    $("#idOrden").css('color', 'red');
                }
                if (idProductoEntrada == ''){
                    alert('El producto es requerido')
                    $("#idProductoEntrada").css('border-color', 'red');
                    $("#idProductoEntrada").css('color', 'red');
                }
                if (cantidadEnAlmacen < 1){
                    $("#cantidadEnAlmacen").css('border-color', 'red');
                    $("#cantidadEnAlmacen").css('color', 'red');
                }
                if (cantidadAservicio < 1 || cantidadAservicio > cantidadEnAlmacen){
                    alert('La cantidad '+cantidadAservicio+' a asignar debe ser mayor a 0 y menor o igual a '+ cantidadEnAlmacen)
                    $("#cantidadAservicio").css('border-color', 'red');
                    $("#cantidadAservicio").css('color', 'red');
                }
                if (fechaAsignacion == ''){
                    alert('La fecha es requerida')
                    $("#fechaAsignacion").css('border-color', 'red');
                    $("#fechaAsignacion").css('color', 'red');
                }
            }
            else if(document.querySelector("#tipoAsignacion_servicio").checked && idFactura == ''){
                alert('El servicio es requerido')
                $("#idFactura").css('border-color', 'red');
                $("#idFactura").css('color', 'red');
            }
            else if(document.querySelector("#tipoAsignacion_oc").checked && (document.querySelector("#oc_destino").value == '' || document.querySelector("#id_factura_referencia").value == '')){
                if(document.querySelector("#oc_destino").value == ''){
                    alert('La Orden destino es requerida')
                    $("#oc_destino").css('border-color', 'red');
                    $("#oc_destino").css('color', 'red');
                }

                if(document.querySelector("#id_factura_referencia").value == ''){
                    alert('El servicio de referencia es requerido')
                    $("#id_factura_referencia").css('border-color', 'red');
                    $("#id_factura_referencia").css('color', 'red');
                }
            } else{
                var formData = new FormData(document.getElementById("formularioAsignacionServicios"));

                $.ajax({
                    url: "{{ route('crearGrupoAsignacion') }}",
                    type: "post",
                    dataType: "html",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
                }).done(function(data){
                    // console.log(data)
                    $("#idGrupoAsignacion").val(data);
                    limpiarInputs();
                    crearSelectOC();
                    consultaProductosAsignados(data);
                });
            }

        });

        function limpiarInputs(){
            document.getElementById("idProducto").value = "";
            document.getElementById("detalles").value = "";
            document.getElementById("evidencia").value = "";
            document.getElementById("idProducto_destino").value = "";
            document.getElementById("cantidadEnAlmacen").value = 0;
            document.getElementById("cantidadAservicio").value = 0;

            cambio_tipo_asignacion()

            var fecha = new Date();
            document.getElementById("fechaAsignacion").value = fecha.toJSON().slice(0,10);
            
            $("#ocurre").prop("checked", true);
            $("#tipoAsignacion_servicio").click();
            
            // // limpiando y reiniciando los combos con funcion select2
            $("#idOrden").val('')
            // .trigger('change')
            $("#idProductoEntrada").empty();
            $("#idFactura").val('').trigger('change')
        }

        function asignar_servicio(){
            document.querySelector("#contenedor_servicio_asignar").style.display = "";
            document.querySelector("#contenedor_oc_asignar").style.display = "none";
            document.querySelector("#contenedor_cantidades_a_servicio").style.display = "";
            // document.querySelector("#contenedor_cantidad_almacen_y_asigar").style.display = "";
            document.querySelector("#contenedor_otras_salidas").style.display = "none"
            cambio_tipo_asignacion()
        }

        function asignar_oc(){
            document.querySelector("#contenedor_servicio_asignar").style.display = "none";
            document.querySelector("#contenedor_oc_asignar").style.display = "";
            document.querySelector("#contenedor_cantidades_a_servicio").style.display = "none";
            // document.querySelector("#contenedor_cantidad_almacen_y_asigar").style.display = "";
            document.querySelector("#contenedor_otras_salidas").style.display = "none"
            cambio_tipo_asignacion()
        }

        function asignar_otro(){
            document.querySelector("#contenedor_servicio_asignar").style.display = "none";
            document.querySelector("#contenedor_oc_asignar").style.display = "none";
            document.querySelector("#contenedor_cantidades_a_servicio").style.display = "none";
            // document.querySelector("#contenedor_cantidad_almacen_y_asigar").style.display = "none";
            document.querySelector("#contenedor_otras_salidas").style.display = ""
            cambio_tipo_asignacion()
        }

        function cambio_tipo_asignacion(){
            document.getElementById("oc_destino").value = "";
            document.getElementById("id_factura_referencia").value = "";
            document.querySelector("#cantidad_solicitada_oc").value = 0;
            document.querySelector("#cantidad_entregada_oc").value = 0;
            document.querySelector("#cantidad_solicitada_servicio").value = 0;
            document.querySelector("#cantidad_entregada_servicio").value = 0;
            $("#idFactura").val('').trigger('change')
        }

        function crear_select_oc_destino(){
            console.log(this.value)

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: `obtenerOrdenesCompra_destino/${this.value}`,
                type: "post",
                
            })
            .done(function(data){
                
                // Limpiando el select
                $("#oc_destino").empty();

                // creando el select con los productos en la OC
                select_oc_destino.innerHTML += "<option value=''>Seleccionar orden</option>";
                data.forEach(data => {
                    if(select_idOrden.value != data.idOrden){
                        select_oc_destino.innerHTML += "<option value='"+data.idOrden+"'>"+data.codigoOrden+"</option>";
                    }
                });
            });


            // for (var i=0; i<select_oc_destino.length; i++) {
            //     select_oc_destino.options[i].hidden = false;
            //     if (select_oc_destino.options[i].value == this.value){
            //         select_oc_destino.options[i].hidden = true;
            //     }
            // }

        }

        function obtener_cantidad_solicitada(){
            console.log(this.value)

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: `obetener_cantidad_solicitada_en_oc`,
                    type: "post",
                    data: {oc_destino:this.value, idProductoEntrada: select_producto_en_almacen.value},


                    success: function (res) {
                        console.log(res)
                        document.querySelector("#cantidad_solicitada_oc").value = res.cantidad_solicitada;
                        document.querySelector("#cantidad_entregada_oc").value = res.cantidad_entregada;
                        document.querySelector("#idProducto_destino").value = res.idProducto_destino;
                    },
                    error: function (err) {
                        console.log(err)
                        document.querySelector("#cantidad_solicitada_oc").value = 0;
                        document.querySelector("#cantidad_entregada_oc").value = 0;
                        document.querySelector("#idProducto_destino").value = 0;
                    }

                })
        }

        function obtenerDatosProducto(){
            // obteneidno el idProducto para la cosulta en el back
            // let idProductoEntrada = this.options[this.selectedIndex].value;
            let idProductoEntrada = $("#idProductoEntrada").val()

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('obtenerDatosProductosSinServicio') }}",
                type: "post",
                data: {idProductoEntrada: idProductoEntrada},
                success: function (data) {
                    let id_producto = 0
                    let sin_asignar = 0
                    if(data.length > 0){
                        id_producto = data[0].idProducto
                        sin_asignar = data[0].sinAsignar
                    }

                    $("#idProducto").val(id_producto);
                    $("#cantidadEnAlmacen,#cantidadAservicio").val(sin_asignar);
                },
                error: function (err) {
                    $("#idProducto").val(0);
                    $("#cantidadEnAlmacen,#cantidadAservicio").val(0);
                }
            })

        }

        function obtener_cantidad_solicitada_servicio(){
            // obteneidno el idProducto para la cosulta en el back
            // let idProductoEntrada = this.options[this.selectedIndex].value;
            let idProductoEntrada = $("#idProductoEntrada").val()
            let id_factura = $("#idFactura").val()

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('obtener_cantidad_solicitada_servicio') }}",
                type: "get",
                data: {idProductoEntrada: idProductoEntrada, idFactura: id_factura},
                success: function (data) {
                    console.log(data)
                    // let id_producto = 0
                    // let sin_asignar = 0
                    // if(data.length > 0){
                    //     id_producto = data[0].idProducto
                    //     sin_asignar = data[0].sinAsignar
                    // }

                    $("#cantidad_solicitada_servicio").val(data.cantidad_solicitada);
                    $("#cantidad_entregada_servicio").val(data.cantidad_asignada);
                },
                error: function (err) {
                    $("#cantidad_solicitada_servicio").val(0);
                    $("#cantidad_entregada_servicio").val(0);
                }
            })
        }

    </script>
