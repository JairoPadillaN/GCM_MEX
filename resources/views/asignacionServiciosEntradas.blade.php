
@extends('principal')
@section('contenido')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">
{{Form::open(['id' => 'formularioAsignacionServicios','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Salidas de Almacén</h1>
        </div>
        
        <div class="panel-body">
            <!-- Esta parte de la vista la carga de una vista que se llama asignacionServiciosEntradas_frm.blade.php
            y la comparte esta vista y la que se llama asignacionServiciosEntradas.blade.php, es para hacer una asignacion desde el reporte o dadnod clic en
            nueva asignacion, esto para hacer modificaciones en un solo archivo y no en dos-->
            @include('asignacionServiciosEntradas_frm')

            <div class="row">
                <div class="col-md-12" style="text-align:center">                            
                    <a href="{{asset('reporteAsignacionProductosServicios')}}"><button type="button" class="btn btn-default">Finalizar</button></a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="text-align:right">
                    <button type="button" class="btn btn-info" onclick="reload()">Nueva Asignación</button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    var idOrden;

    $(document).ready(function(){
        $("#idOrden").select2({width:'100%'});
        $("#idProductoEntrada").select2({width:'100%'});
        $("#idFactura").select2({width:'100%'});
    });

    $("#idOrden").change(function(){
        idOrden = this.options[this.selectedIndex].value;
        // // Limpiando el selecta alterar
        $("#idProductoEntrada").empty();
        mensajeCargandoCombo('idProductoEntrada');
        crearSelectProductos(idOrden);
        // consultaProductosAsignadosPorOC(idOrden);
    });


    // $("#idProductoEntrada").change(function(){
    //     // obteneidno el idProducto para la cosulta en el back
    //     let idProductoEntrada = this.options[this.selectedIndex].value;

    //     $.ajax({
    //         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    //         url: "{{ route('obtenerDatosProductosSinServicio') }}",
    //         type: "post",
    //         data: {idProductoEntrada: idProductoEntrada},
    //     })
    //     .done(function(data){
    //         $("#idProducto").val(data[0].idProducto);
    //         $("#cantidadEnAlmacen,#cantidadAservicio").val(data[0].sinAsignar);
    //     });

    // });

    // $("#asignarAservicio").click(function(e) {

    //     var idOrden = $('#idOrden').val();
    //     var idProductoEntrada = $('#idProductoEntrada').val();
    //     var cantidadEnAlmacen = parseInt($('#cantidadEnAlmacen').val());
    //     var idFactura = $('#idFactura').val();
    //     var cantidadAservicio = parseInt($('#cantidadAservicio').val());
    //     var fechaAsignacion = $('#fechaAsignacion').val();

    //     if (idOrden == '' || idProductoEntrada == ''  || fechaAsignacion == '' || cantidadEnAlmacen < 1 || cantidadAservicio < 1 || cantidadAservicio > cantidadEnAlmacen){
                            
    //         if (idOrden == ''){
    //             alert('La orden de compra es requerida')
    //             $("#idOrden").css('border-color', 'red');
    //             $("#idOrden").css('color', 'red');
    //         }
    //         if (idProductoEntrada == ''){
    //             alert('El producto es requerido')
    //             $("#idProductoEntrada").css('border-color', 'red');
    //             $("#idProductoEntrada").css('color', 'red');
    //         }
    //         if (cantidadEnAlmacen < 1){
    //             $("#cantidadEnAlmacen").css('border-color', 'red');
    //             $("#cantidadEnAlmacen").css('color', 'red');
    //         }
    //         if (cantidadAservicio < 1 || cantidadAservicio > cantidadEnAlmacen){
    //             alert('La cantidad '+cantidadAservicio+' a asignar debe ser mayor a 0 y menor o igual a '+ cantidadEnAlmacen)
    //             $("#cantidadAservicio").css('border-color', 'red');
    //             $("#cantidadAservicio").css('color', 'red');
    //         }
    //         if (fechaAsignacion == ''){
    //             alert('La fecha es requerida')
    //             $("#fechaAsignacion").css('border-color', 'red');
    //             $("#fechaAsignacion").css('color', 'red');
    //         }
    //     }
    //     else if(document.querySelector("#tipoAsignacion_servicio").checked && idFactura == ''){
    //         alert('El servicio es requerido')
    //         $("#idFactura").css('border-color', 'red');
    //         $("#idFactura").css('color', 'red');
    //     }
    //     else if(document.querySelector("#tipoAsignacion_oc").checked && (document.querySelector("#oc_destino").value == '' || document.querySelector("#id_factura_referencia").value == '')){
    //         if(document.querySelector("#oc_destino").value == ''){
    //             alert('La Orden destino es requerida')
    //             $("#oc_destino").css('border-color', 'red');
    //             $("#oc_destino").css('color', 'red');
    //         }

    //         if(document.querySelector("#id_factura_referencia").value == ''){
    //             alert('El servicio de referencia es requerido')
    //             $("#id_factura_referencia").css('border-color', 'red');
    //             $("#id_factura_referencia").css('color', 'red');
    //         }
    //     } else{
    //         var formData = new FormData(document.getElementById("formularioAsignacionServicios"));

    //         $.ajax({
    //             url: "{{ route('crearGrupoAsignacion') }}",
    //             type: "post",
    //             dataType: "html",
    //             data: formData,
    //             cache: false,
    //             contentType: false,
    //             processData: false
    //         }).done(function(data){
    //             // console.log(data)
    //             $("#idGrupoAsignacion").val(data);
    //             limpiarInputs();
    //             crearSelectOC();
    //             consultaProductosAsignados(data);
    //         });
    //     }

    // });


    function crearSelectOC(){

        mensajeCargandoCombo('idOrden');

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('obtenerOrdenesCompra') }}",
            type: "post",
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

    function crearSelectProductos(idOrden){

        // obteniendo el select a modificar
        var select = document.getElementById('idProductoEntrada');

        // peticion a la base
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('obtenerProductosSinServicio') }}",
            type: "post",
            data: {idOrden: idOrden},
        })
        .done(function(data){
            
            // Limpiando el select
            $("#idProductoEntrada").empty();

            // creando el select con los productos en la OC
            select.innerHTML += "<option value=''>Selecionar producto</option>";
            data.forEach(data => {
                select.innerHTML += "<option value='"+data.idProductoEntrada+"'>"+data.producto+"</option>";
            });
        });

    }

    // function limpiarInputs(){
    //     document.getElementById("idProducto").value = "";
    //     document.getElementById("detalles").value = "";
    //     document.getElementById("evidencia").value = "";
    //     document.getElementById("idProducto_destino").value = "";
    //     document.getElementById("cantidadEnAlmacen").value = 0;
    //     document.getElementById("cantidadAservicio").value = 0;

    //     cambio_tipo_asignacion()

    //     var fecha = new Date();
    //     document.getElementById("fechaAsignacion").value = fecha.toJSON().slice(0,10);
        
    //     $("#ocurre").prop("checked", true);
    //     $("#tipoAsignacion_servicio").click();
        
    //     // // limpiando y reiniciando los combos con funcion select2
    //     $("#idOrden").val('')
    //     // .trigger('change')
    //     $("#idProductoEntrada").empty();
    //     $("#idFactura").val('').trigger('change')
    // }

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

    function consultaProductosAsignados(idGrupoAsignacion){
         // peticion a la base
         $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('consultaProductosAsignados') }}",
            type: "post",
            data: {idGrupoAsignacion: idGrupoAsignacion},
        })
        .done(function(data){
            if(data.length < 1){
                $("#reporteProductosAgregados").empty();
                document.getElementById("reporteProductosAgregados").innerHTML = '<div class="alert alert-warning" style="text-align:center">Sin asignaciones</div>';
            }else{
                cargarTablaProductosAsignados(data,'reporteProductosAgregados')
            }         
        });
    }

    function consultaProductosAsignadosPorOC(idOrden){
         // peticion a la base
         $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('consultaProductosAsignadosPorOC') }}",
            type: "post",
            data: {idOrden: idOrden},
        })
        .done(function(data){
            if(data.length < 1){
                $("#reporteProductosAgregadosPorOC").empty();
                document.getElementById("reporteProductosAgregadosPorOC").innerHTML = '<div class="alert alert-warning" style="text-align:center">Orden de compra sin previas asignaciones</div>';
            }else{
                cargarTablaProductosAsignados(data,'reporteProductosAgregadosPorOC')
            }         
        });
    }

    function cargarTablaProductosAsignados(datosConsulta, divParaCargarTabla){
        $("#"+divParaCargarTabla+"").empty();

        // Ahora dibujamos la tabla
        let table = document.createElement('table');
        let thead = document.createElement('thead');
        let tbody = document.createElement('tbody');
        
        table.appendChild(thead);
        table.appendChild(tbody);

        // Adding the entire table to the body tag
        document.getElementById(divParaCargarTabla).appendChild(table);

        let filaEncabezados = document.createElement('tr');

        let columna_1 = document.createElement('th');
        columna_1.innerHTML = "SKU";

        let columna_2 = document.createElement('th');
        columna_2.innerHTML = "Descripción";

        let columna_3 = document.createElement('th');
        columna_3.innerHTML = "Orden compra";

        let columna_4 = document.createElement('th');
        columna_4.innerHTML = "# Serie";

        let columna_5 = document.createElement('th');
        columna_5.innerHTML = "Salida";

        let columna_6 = document.createElement('th');
        columna_6.innerHTML = "Cantidad asignada";

        let columna_7 = document.createElement('th');
        columna_7.innerHTML = "";

        filaEncabezados.appendChild(columna_1);
        filaEncabezados.appendChild(columna_2);
        filaEncabezados.appendChild(columna_3);
        filaEncabezados.appendChild(columna_4);
        filaEncabezados.appendChild(columna_5);
        filaEncabezados.appendChild(columna_6);
        filaEncabezados.appendChild(columna_7);
        
        // Color de fondo para el encabezado de la tabla
        filaEncabezados.style.backgroundColor = "#C5EBFB";
        table.setAttribute("class", "table table-striped table-hover table-bordered");

        // añadiendo las columnas a la tabla
        thead.appendChild(filaEncabezados);

        // Recorrer todos los productos
        datosConsulta.forEach(datos => {
            // Crear un <tr>
            const $tr = document.createElement("tr");
            // Creamos el <td> de nombre y lo adjuntamos a tr
            let $td_1 = document.createElement("td");
            $td_1.textContent = datos.sku; // el textContent del td es el nombre
            $td_1.setAttribute("align", "center");
            $tr.appendChild($td_1);

            // El td de precio
            let $td_2 = document.createElement("td");
            $td_2.textContent = datos.producto;
            $td_2.setAttribute("align", "center");
            $tr.appendChild($td_2);

            // El td del código
            let $td_3 = document.createElement("td");
            $td_3.textContent = datos.ordenCompra;
            $td_3.setAttribute("align", "center");
            $tr.appendChild($td_3);

            // El td del código
            let $td_4 = document.createElement("td");
            $td_4.textContent = datos.numeroSerie;
            $td_4.setAttribute("align", "center");
            $tr.appendChild($td_4);


            // El td del código
            let $td_5 = document.createElement("td");
            $td_5.textContent = datos.asignacion;
            $td_5.setAttribute("align", "center");
            $tr.appendChild($td_5);

            // El td del código
            let $td_6 = document.createElement("td");
            $td_6.textContent = datos.cantidadAservicio;
            $td_6.setAttribute("align", "center");
            $tr.appendChild($td_6);
            
            // Creando el td de opciones
            let $td_7 = document.createElement("td");            
            
            // Si es el reporte por ordenes de compra, no se activa la eliminacion
            if (divParaCargarTabla == 'reporteProductosAgregados') {                            
                // Creando el boton
                let btn = document.createElement('button');
                btn.type = "button";
                btn.className = "btn btn-danger btn-sm eliminarAsignacion";
                btn.value = datos.idProductoServAsig;
                // Creando el icono de basura
                let icono = document.createElement('i');
                icono.className ="fa fa-trash";
                // Insertando el icono en el boton
                btn.appendChild(icono);
                // Insertando el boton en el td
                $td_7.appendChild(btn);
            }

            $tr.appendChild($td_7);

            // Finalmente agregamos el <tr> al cuerpo de la tabla
            tbody.appendChild($tr);
            // Y el ciclo se repite hasta que se termina de recorrer todo el arreglo
        });
        
        $(".eliminarAsignacion").click(function(e) {        
            eliminarAsignacion($(this).val())
        });
    }

    function eliminarAsignacion(idEliminar){
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('eliminarAsignacion') }}",
            type: "post",
            data: {idProductoServAsig: idEliminar},
        })
        .done(function(data){
            limpiarInputs()
            crearSelectOC();
            consultaProductosAsignados(data)
        });
    }

    function reload(){
        // cargando nuevamente la pagina
        location.reload();
    }

    function ValidarArchivo(obj) {
        var uploadFile = obj.files[0];
        let datos4 = $("#archivo").val();
        if (!(/\.(jpeg|png|gif|jpg|pdf)$/i).test(uploadFile.name)) {
            alert('El archivo seleccionado no es valido.');
            $('#archivo').val('');
        }
    }

</script>
@stop