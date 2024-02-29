@extends('principal')
@section('contenido')
 
<div class="col-md-12">
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1 class="">Reporte de Entrada Almacén</h1>
        </div>
        <div class="panel-body">

            <div class="row">
                <div class="col-sm-12" style="margin-bottom:10px;text-align:right">
                    <a href="{{asset('altaEntradaAlmacen')}}">
                        <button type="button" class="btn btn-primary ">Agregar entrada a Almacén</button>
                    </a>
                </div>
            </div>
           
            <div id="list" class="table-responsive"></div>

            <!-- Modal -->
            <div class="modal fade" id="modalParaFotos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="row">
                                <div class="col-md-11">
                                    <h3 class="modal-title" id="exampleModalLabel">Datos</h3>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body" id="contenidoModalFotos">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div><strong>Registrado por: </strong><span id="registradoPor" class="datosModal"></span></div>
                                    <div><strong>Recibido en almacén por: </strong><span id="recibidoPor" class="datosModal"></span></div>
                                    <div><strong>Empresa: </strong><span id="empresa" class="datosModal"></span></div>
                                    <div><strong>Número de parte: </strong><span id="numeroParte" class="datosModal"></span></div>
                                    <div><strong>Presentación: </strong><span id="presentacion" class="datosModal"></span></div>
                                    <div><strong>Unidades por presentación: </strong><span id="unidadesPorPresentacion" class="datosModal"></span></div>
                                    <div><strong>Observaciones: </strong><span id="observaciones" class="datosModal"></span></div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <strong>Placa</strong>
                                            <div id="fotoPlaca" class="datosModal"></div>
                                        </div>
                                        <div class="col-sm-6">
                                            <strong>Principal</strong>
                                            <div id="fotoPrincipal" class="datosModal"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                           
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal_editar_entrada" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h3 class="modal-title">Editar entrada</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div style="padding: 30px">

            <form id="form_editar_entrada" action="">
                <!-- <div class="sub-title">Seleccionar marca: </div>
                <div>
                    <select name='idMarcaRefa' id='idMarcaRefa' class="form-control">
                        <option value="">Seleccionar marca</option>
                    </select>
                </div>
                <div class="sub-title">Seleccionar SKU: </div>
                <div>
                    <select name='idPartesVenta' id='idPartesVenta' class="form-control">
                        <option value="">Seleccionar sku</option>
                    </select>
                </div> -->
                <input type="text" name="idProductoEntrada_edit" id="idProductoEntrada_edit" hidden>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="sub-title">Número de parte:</div>
                        <div>
                            {{Form::text('numeroParte_edit','',['class' => 'form-control','id'=>'numeroParte_edit'])}}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="sub-title">Número de serie:</div>
                        <div>
                            {{Form::text('numeroSerie_edit','',['class' => 'form-control','id'=>'numeroSerie_edit'])}}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="sub-title">Modelo:</div>
                        <div>
                            <input class="form-control" type='text' name='modeloProducto_edit' id='modeloProducto_edit'>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="sub-title">Presentación:</div>
                        <div>
                            <input type="radio" name="presentacion_edir" value="Pieza" id="pieza_edit" checked>Pieza &nbsp;&nbsp;
                            <input type="radio" name="presentacion_edir" value="Equipo" id="equipo_edit">Equipo &nbsp;&nbsp;
                            <input type="radio" name="presentacion_edir" value="Servicio" id="servicio_edit">Servicio
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="sub-title">Unidades por presentación:</div>
                        <div>
                            <input type="number" name="unidadesPorPresentacion_edit" id="unidadesPorPresentacion_edit" placeholder="Ejemplo: 1" class="form-control" min="1">
                        </div>
                    </div>
                </div>

                <div class="form-group">

                    <div class="sub-title">Foto placa</div>
                    <div>
                        {{Form::file('fotoPlaca_edit',['id'=>'fotoPlaca_edit'])}}
                    </div>
                    <div id="contenedor_img_placa">
                        <img src="" alt="" id="img_1" width="80" height="80">
                    </div>

                    <div class="sub-title">Foto principal</div>
                    <div>
                        {{Form::file('fotoPrincipal_edit',['id'=>'fotoPrincipal_edit'])}}
                    </div>
                    <div id="contenedor_img_principal">
                        <img src="" alt="" id="img_2" width="80" height="80">
                    </div>

                    <div class="sub-title">Observaciones:</div>
                    <div>
                        <textarea class="form-control" name="observaciones_edit" id="observaciones_edit" rows="5" placeholder="Escribe aqui las observaciones necesarias"></textarea>
                    </div>

                </div>
            </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar_editar_entrada()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    var tablaEntradas = '';
    var assetBaseUrl = "{{asset ('archivos/')}}";

    $(document).ready(function(){    
        cargarReporteProductosEntrada()
    });

    function cargarReporteProductosEntrada(){
        tablaEntradas = $("#list").anexGrid({
            class: 'table table-striped table-bordered table-hover tablaAnexgrid',            
            columnas: [
                {leyenda: 'Folio de Orden', columna: 'ordenCompra', ordenable: true, filtro: true, style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Nota de entrada', columna: 'notaEntrada' , ordenable: true, filtro: false , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'SKU', columna: 'sku' , ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Producto', columna: 'producto', ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Serie/Modelo', columna: 'serieYmodelo', ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Cantidad', columna: 'cantidadRecibida', ordenable: true, style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Servicio', columna: 'servicio', ordenable: true, filtro: true, style:'background-color: #C5EBFB;text-align:center'},
                // {leyenda: 'Notas', columna: 'observaciones', style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Fecha Inicial', columna: 'fechaInicioEntrada', ordenable: true, style: 'background-color: #C5EBFB;text-align:center;',
                    filtro: function(){
                        return anexGrid_input({
                            type: 'date',
                            style: 'width:103px',
                        });
                    }
                },
                {leyenda: 'Fecha Final', columna: 'fechaFinEntrada', style: 'background-color: #C5EBFB;text-align:center',
                    filtro: function(){
                        return anexGrid_input({
                            type: 'date',
                            style: 'width:103px',
                        });
                    }
                },
                {leyenda: 'Proveedor', columna: 'proveedor', ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                // {leyenda: 'Empresa', columna: 'empresa', ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Almacen', columna: 'almacen', ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: '',style: 'background-color: #C5EBFB;text-align:center'},
            ],
            modelo: [
                { propiedad: 'ordenCompra' ,style:'text-align:center'},
                { propiedad: 'notaEntrada' ,style:'text-align:center'},
                { propiedad: 'sku' ,style:'text-align:center',
                    formato:function(tr, obj,celda){
                        let linkFotos = anexGrid_link({
                            contenido: obj.sku,
                            href: '#',                            
                            attr: ['onClick="mostrarDatosModal(\''+tr[0].dataset.fila+'\')"']
                            // attr: ['onClick="mostrarDatosModal(\''+obj.registradoPor+ '\',\'' +obj.recibidoPor+ '\',\'' +obj.empresa+ '\',\'' +obj.numeroParte+ '\',\'' +obj.presentacion+ '\',\'' +obj.fotoPlaca+ '\',\'' +obj.fotoPrincipal+ '\')"']
                            // attr: ['onClick="mostrarDatosModal(\''+obj.fotoPrincipal+ '\',\'' +obj.fotoPlaca+ '\')"']
                        });

                        return linkFotos;
                    }
                },
                { propiedad: 'producto' ,style:'text-align:center'},
                { propiedad: 'serieYmodelo' ,style:'text-align:center'},
                { propiedad: 'cantidadRecibida' ,style:'text-align:center'},
                { propiedad: 'servicio' ,style:'text-align:center'},
                // { propiedad: 'observaciones' ,style:'text-align:center'},
                { propiedad: 'fechaInicioEntrada' ,style:'text-align:center',colspan:'2',
                    formato: function(tr, obj,celda){
                        return obj.fecha_formato;
                    }
                },
                { propiedad: 'proveedor' ,style:'text-align:center'},
                // { propiedad: 'empresa' ,style:'text-align:center'},
                { propiedad: 'almacen' ,style:'text-align:center'},
                { propiedad: 'idProductoEntrada',style:'text-align:center', 
                    formato:function(tr, obj,celda){
                        let botones = '';

                        botones += anexGrid_boton({
                            attr: ['title = "Editar entrada"'],
                            class: 'btn btn-info btn-sm editar',
                            contenido: '<i class="fa fa-pencil"></i>',
                            value: obj.idProductoEntrada,
                            // href: 'modificarOrden/' + obj.idOrden
                        });
                        
                        if(obj.cantidadAsignada == 0){
                            botones += anexGrid_boton({
                                class: 'btn btn-danger btn-sm eliminarProductoEntrada',
                                contenido: '<i class="fa fa-trash"></i>',
                                value: obj.idProductoEntrada
                            });
                        }

                        botones += anexGrid_boton({
                            class: 'btn btn-default btn-sm etiqueta',
                            contenido: '<i class="fa fa-qrcode"></i>',
                            value: obj.idProductoEntrada
                        });

                        return botones;
                    }
                },
            
            ],
            url: 'anexgrid_get_reporteProductosPorEntrada',
            /* Parametro all se manda para que muestra todos las entradas, ya que tiene un filtro 
            para mandar solo las no asignadas e otro reporte de asignacion */
            parametros:['all'],
            paginable: true,
            filtrable: true,
            limite: [15,20,25,30 ,50],
            columna: 'idProductoEntrada',
            columna_orden: 'DESC'
        });

        tablaEntradas.tabla().on('click', '.editar', function(){
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('obtener_datos_entrada') }}",
                type: "post",
                // xhrFields: {responseType: 'blob'},
                data: {idProductoEntrada: $(this).val()},
                success: function(response) {
                    let res = response[0]
                    console.log(res)
                    console.log(res.numeroParte)                    

                    document.querySelector("#idProductoEntrada_edit").value = res.idProductoEntrada
                    document.querySelector("#numeroParte_edit").value = res.numeroParte
                    document.querySelector("#numeroSerie_edit").value = res.numeroSerie
                    document.querySelector("#modeloProducto_edit").value = res.modelo
                    document.querySelector("#unidadesPorPresentacion_edit").value = res.unidadesPorPresentacion
                    document.querySelector("#observaciones_edit").textContent = res.observaciones

                    switch (res.presentacion) {
                        case "Pieza":
                            document.querySelector("#pieza_edit").checked = true
                        break;
                        case "Equipo":
                            document.querySelector("#equipo_edit").checked = true
                        break;
                        case "Servicio":
                            document.querySelector("#servicio_edit").checked = true
                        break;
                    }


                    document.querySelector("#contenedor_img_placa").style.display = ""
                    document.querySelector("#contenedor_img_principal").style.display = ""

                    if (res.fotoPlaca != '' && res.fotoPlaca != 'Sin archivo') {
                        document.querySelector("#img_1").src =  assetBaseUrl+'/'+res.fotoPlaca
                    }else{
                        document.querySelector("#contenedor_img_placa").style.display = "none"
                        let span = document.createElement("span");
                        let text = "Sin imagen"
                        document.querySelector("#contenedor_img_placa").after(span,text)
                    }
                    
                    if (res.fotoPrincipal != '' && res.fotoPrincipal != 'Sin archivo') {
                        document.querySelector("#img_2").src =  assetBaseUrl+'/'+res.fotoPrincipal
                    }else{
                        document.querySelector("#contenedor_img_principal").style.display = "none"
                        let span = document.createElement("span");
                        let text = "Sin imagen"
                        document.querySelector("#contenedor_img_principal").after(span,text)
                    }
                    
                    $('#modal_editar_entrada').modal('show')
                },
            });
        });

        tablaEntradas.tabla().on('click', '.eliminarProductoEntrada', function(){
            eliminarProducto($(this).val())
        });

        tablaEntradas.tabla().on('click', '.etiqueta', function(){
            etiqueta($(this).val())
        });
    }

    function eliminarProducto(idEliminar){
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('eliminarProductoEntrada') }}",
            type: "post",
            data: {idProductoEntrada: idEliminar},
        })
        .done(function(data){
            tablaEntradas.refrescar()
        });
    }

    function mostrarDatosModal(fila){
        var datos = tablaEntradas.obtener(fila);
        $('.datosModal').empty()

        document.getElementById('registradoPor').innerHTML= datos.registradoPor === null ? '-': datos.registradoPor;
        document.getElementById('recibidoPor').innerHTML= datos.recibidoPor === null ? '-': datos.recibidoPor;
        document.getElementById('empresa').innerHTML= datos.empresa === null ? '-': datos.empresa;
        document.getElementById('numeroParte').innerHTML= datos.numeroParte === null ? '-': datos.numeroParte;
        document.getElementById('presentacion').innerHTML= datos.presentacion === null ? '-': datos.presentacion;
        document.getElementById('unidadesPorPresentacion').innerHTML= datos.unidadesPorPresentacion === null ? '-': datos.unidadesPorPresentacion;
        document.getElementById('observaciones').innerHTML= datos.observaciones === null ? '-': datos.observaciones;

        var url1 = assetBaseUrl+'/'+datos.fotoPrincipal
        var url2=assetBaseUrl+'/'+datos.fotoPlaca

        if(datos.fotoPrincipal!= 'Sin archivo'){
            const image = document.createElement('img')
            image.src  = assetBaseUrl+'/'+datos.fotoPrincipal
            image.width="150"
            image.height="130"
            image.setAttribute('onclick',"window.open(this.src)")
            // image.style = 'display:inline-block;height:auto;overflow:hidden;word-wrap:break-word;max-width:100%;'
            document.querySelector('#fotoPrincipal').appendChild(image)
        }else{
            document.getElementById('fotoPrincipal').innerHTML ='<h3 align="center"><span class="label label-warning">Sin archivo</span></h3>'
        }

        if(datos.fotoPlaca!= 'Sin archivo'){
            const image = document.createElement('img')
            image.src  = assetBaseUrl+'/'+datos.fotoPlaca
            image.width="150"
            image.height="130"
            image.onclick= "openImg()"
            image.setAttribute('onclick',"window.open(this.src)")
            document.querySelector('#fotoPlaca').appendChild(image)
        }else{
            document.getElementById('fotoPlaca').innerHTML ='<h3 align="center"><span class="label label-warning">Sin archivo</span></h3>'
        }

        $('#modalParaFotos').modal('show')
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

    function guardar_editar_entrada(){
        var form_action = $("#form_editar_entrada").attr("action");
        // Guardamos el form con los input file para subir archivos
        var formData = new FormData(document.getElementById("form_editar_entrada"));
        
        $.ajax({
            data: formData,
            url: 'editarProductoEntrada',
            type: "POST",
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (res) {
                tablaEntradas.refrescar();
                $('#modal_editar_entrada').modal('hide')
            },
            error: function (err) {
                console.log(err.statusText);
            }
        });
    }

</script>
@stop
