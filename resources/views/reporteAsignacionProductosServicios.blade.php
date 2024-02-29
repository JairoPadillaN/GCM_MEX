@extends('principal')
@section('contenido')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<div class="col-md-12">
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1 class="">Reporte productos en almacén</h1>
        </div>
        <div class="panel-body">

            <div class="row">
                <div class="col-sm-12" style="margin-bottom:10px;text-align:right">
                    <a href="{{asset('altaAsignacionProductosServicios')}}">
                        <button type="button" class="btn btn-primary ">Nueva asignación</button>
                    </a>
                </div>
            </div>

            <ul class="nav nav-tabs">
                <li class="active" id='tab1'><a href="#sinAsignar" data-toggle="tab"><label for="">Productos en stock</label></a></li>
                <li class="" id='tab2'><a href="#asignados_a_servicio" data-toggle="tab"><label for="">Productos Asignados a Servicios</label></a></li>
                <li class="" id='tab3'><a href="#asignados_a_oc" data-toggle="tab"><label for="">Traspasos entre almacenes</label></a></li>
                <li class="" id='tab3'><a href="#otras_salidas" data-toggle="tab"><label for="">Otras salidas</label></a></li>
            </ul>
            
            <div class="tab-content">
                <div class="tab-pane active" id="sinAsignar" role="tabpanel">
                    <div id="reporte_sin_asignar" class="table-responsive"></div>
                </div>    
                <div class="tab-pane fade" id="asignados_a_servicio" role="tabpanel">
                    <div id="reporte_asignados_servicio" class="table-responsive"></div>
                </div>
                <div class="tab-pane fade" id="asignados_a_oc" role="tabpanel">
                    <div id="reporte_asignados_oc" class="table-responsive"></div>
                </div>
                <div class="tab-pane fade" id="otras_salidas" role="tabpanel">
                    <div id="reporte_otras_salidas" class="table-responsive"></div>
                </div>
            </div>
           
            <!-- Modal -->
            <div class="modal fade" id="modalParaAsignar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="row">
                                <div class="col-md-11">
                                    <h3 class="modal-title" id="exampleModalLabel">Asignación de servicios</h3>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body" id="contenidoModal">
                            ...
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    var tabla_sin_asignar = '';
    var tabla_asignados_servicio = '';
    var tabla_asignados_oc = '';
    var tabla_otras_salidas = '';
    
    $(document).ready(function(){
        cargarReporteProductosSinAsignar()
        cargarReporteProductos_asignados_servicio()
        cargarReporteProductos_asignados_a_oc()
        cargarReporteProductos_otras_salidas()
    });

    // Cuando el modal se cierra se actualizan las tablas
    $('#modalParaAsignar').on('hidden.bs.modal', function (e) {
        tabla_sin_asignar.refrescar();
        tabla_asignados_servicio.refrescar();
        tabla_asignados_oc.refrescar();
        tabla_otras_salidas.refrescar();
    });

    // Tabla de productos asignados_a_servicio a servicio
    function cargarReporteProductosSinAsignar(){
        tabla_sin_asignar = $("#reporte_sin_asignar").anexGrid({
            class: 'table table-striped table-bordered table-hover tablaAnexgrid layui-table',            
            columnas: [
                {leyenda: 'Folio de Orden', columna: 'ordenCompra', ordenable: true, filtro: true, style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'SKU', columna: 'sku' , ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Producto', columna: 'producto', ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Serie/Modelo', columna: 'serieYmodelo', ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Cantidad', columna: 'cantidadStock', ordenable: true, style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Almacen', columna: 'almacen', ordenable: true, filtro: true, style:'background-color: #C5EBFB;text-align:center'},
                // {leyenda: 'Fecha Inicial', columna: 'fechaInicioAsignacion', ordenable: true, style: 'background-color: #C5EBFB;text-align:center;',
                //     filtro: function(){
                //         return anexGrid_input({
                //             type: 'date',
                //             style: 'width:103px',
                //         });
                //     }
                // },
                // {leyenda: 'Fecha Final', columna: 'fechaFinAsignacion', style: 'background-color: #C5EBFB;text-align:center',
                //     filtro: function(){
                //         return anexGrid_input({
                //             type: 'date',
                //             style: 'width:103px',
                //         });
                //     }
                // },
                {leyenda: 'Proveedor',columna: 'proveedor', ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: '',style: 'background-color: #C5EBFB;text-align:center'},
            ],
            modelo: [
                { propiedad: 'ordenCompra' ,style:'text-align:center'},
                { propiedad: 'sku' ,style:'text-align:center'},
                { propiedad: 'producto' ,style:'text-align:center'},
                { propiedad: 'serieYmodelo' ,style:'text-align:center'},
                { propiedad: 'cantidadStock' ,style:'text-align:center'},
                { propiedad: 'almacen' ,style:'text-align:center'},
                // { propiedad: 'fechaInicioAsignacion' ,style:'text-align:center',colspan:'2',
                //     formato: function(tr, obj,celda){
                //         return obj.fecha_formato;
                //     }
                // },
                { propiedad: 'proveedor' ,style:'text-align:center'},
                { propiedad: '',style:'text-align:center', 
                    formato:function(tr, obj,celda){                                                                    
                        let botonAsignar = anexGrid_boton({
                            class: 'btn btn-info btn-sm asignarProducto',
                            contenido: '<i class="fa fa-sitemap fa-lg"></i>',
                            value: obj.idProductoEntrada,
                            attr: ['data-toggle="modal"','data-target="#modalParaAsignar"'],
                        });

                        return botonAsignar;
                    }
                },
            
            ],
            url: 'anexgrid_get_reporteProductosPorEntrada',
            /* Parametro 0 se manda para que muestra las entradas sin asignar, ya que tiene un filtro 
            para mandar todas las entradas en otro reporte de asignacion */
            parametros:[0],
            paginable: true,
            filtrable: true,
            limite: [10,15,20,25,30 ,50],
            columna: 'idProductoEntrada',
            columna_orden: 'DESC'
        });

        tabla_sin_asignar.tabla().on('click', '.asignarProducto', function(e){
            $("#contenidoModal").empty();
            asignarProducto($(this).val())
        });
    }

    function asignarProducto(idProductoEntrada){
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('obtenerDatosProductosSinServicio') }}",
            type: "post",
            data: {idProductoEntrada: idProductoEntrada},
        })
        .done(function(data){
            $("#contenidoModal").load('{{url('asignacionProductosServiciosModal')}}' + '?' +'idOrden='+ data[0].idOrden + '&idProducto='+data[0].idProductoEntrada+'&cantidad='+data[0].sinAsignar);
        });
    }

    // Tabla de productos Sin asignar a servicios
    function cargarReporteProductos_asignados_servicio(){
        tabla_asignados_servicio = $("#reporte_asignados_servicio").anexGrid({
            class: 'table table-striped table-bordered table-hover tablaAnexgrid',            
            columnas: [
                {leyenda: 'Folio de Orden', columna: 'ordenCompra', ordenable: true, filtro: true, style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'SKU', columna: 'sku' , ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Producto', columna: 'producto', ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Serie/Modelo', columna: 'serieYmodelo', ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Cantidad', columna: 'cantidadAsignada', ordenable: true, style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Servicio', columna: 'servicio', ordenable: true, filtro: true, style:'background-color: #C5EBFB;text-align:center'},
                // {leyenda: 'Detalles', columna: 'detalles', style: 'background-color: #C5EBFB;text-align:center'},
                // {leyenda: 'Fecha Inicial', columna: 'fechaInicioAsignacion', ordenable: true, style: 'background-color: #C5EBFB;text-align:center;',
                //     filtro: function(){
                //         return anexGrid_input({
                //             type: 'date',
                //             style: 'width:103px',
                //         });
                //     }
                // },
                // {leyenda: 'Fecha Final', columna: 'fechaFinAsignacion', style: 'background-color: #C5EBFB;text-align:center',
                //     filtro: function(){
                //         return anexGrid_input({
                //             type: 'date',
                //             style: 'width:103px',
                //         });
                //     }
                // },
                {leyenda: 'Salida de almacén',columna: 'almacen', ordenable: false, filtro: false , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Proveedor',columna: 'proveedor', ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: '',style: 'background-color: #C5EBFB;text-align:center'},
            ],
            modelo: [
                { propiedad: 'ordenCompra' ,style:'text-align:center'},
                { propiedad: 'sku' ,style:'text-align:center'},
                { propiedad: 'producto' ,style:'text-align:center'},
                { propiedad: 'serieYmodelo' ,style:'text-align:center'},
                { propiedad: 'cantidadAsignada' ,style:'text-align:center'},
                { propiedad: 'servicio' ,style:'text-align:center'},
                // { propiedad: 'detalles' ,style:'text-align:center'},
                // { propiedad: 'fechaInicioAsignacion' ,style:'text-align:center',colspan:'2',
                //     formato: function(tr, obj,celda){
                //         return obj.fecha_formato;
                //     }
                // },
                { propiedad: 'almacen',style:'text-align:center'},
                { propiedad: 'proveedor',style:'text-align:center'},
                { propiedad: 'idProductoServAsig',style:'text-align:center', 
                    formato:function(tr, obj,celda){                                                                    
                        if(obj.idGrupoAsignacion != null){
                            let botonEliminar = anexGrid_boton({
                                class: 'btn btn-danger btn-sm eliminar',
                                contenido: '<i class="fa fa-trash"></i>',
                                value: obj.idProductoServAsig
                            });

                            return botonEliminar;
                        }
                    }
                },
            
            ],
            url: 'anexgrid_get_reporteProductos_asignados_servicio',
            paginable: true,
            filtrable: true,
            limite: [10,15,20,25,30 ,50],
            columna: 'idProductoServAsig',
            columna_orden: 'DESC'
        });

        tabla_asignados_servicio.tabla().on('click', '.eliminar', function(){
            eliminar($(this).val())
        });
    }

    // Tabla de productos asignados a ordenes de compra
    function cargarReporteProductos_asignados_a_oc(){
        tabla_asignados_oc = $("#reporte_asignados_oc").anexGrid({
            class: 'table table-striped table-bordered table-hover tablaAnexgrid',            
            columnas: [
                {leyenda: 'Folio de Orden', columna: 'ordenCompra', ordenable: true, filtro: true, style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'SKU', columna: 'sku' , ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Producto', columna: 'producto', ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Serie/Modelo', columna: 'serieYmodelo', ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Cantidad', columna: 'cantidadAsignada', ordenable: true, style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Servicio de referencia', columna: 'servicio', ordenable: true, filtro: true, style:'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Orden de compra asignada', columna: 'servicio', ordenable: true, filtro: true, style:'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Salida de almacén',columna: 'almacen', ordenable: false, filtro: false , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Proveedor',columna: 'proveedor', ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: '',style: 'background-color: #C5EBFB;text-align:center'},
            ],
            modelo: [
                { propiedad: 'ordenCompra' ,style:'text-align:center'},
                { propiedad: 'sku' ,style:'text-align:center'},
                { propiedad: 'producto' ,style:'text-align:center'},
                { propiedad: 'serieYmodelo' ,style:'text-align:center'},
                { propiedad: 'cantidadAsignada' ,style:'text-align:center'},
                { propiedad: 'servicio_referencia' ,style:'text-align:center'},
                { propiedad: 'oc_asignada' ,style:'text-align:center'},
                { propiedad: 'almacen',style:'text-align:center'},
                { propiedad: 'proveedor',style:'text-align:center'},
                { propiedad: 'idProductoServAsig',style:'text-align:center', 
                    formato:function(tr, obj,celda){                                                                    
                        if(obj.idGrupoAsignacion != null){
                            let botonEliminar = anexGrid_boton({
                                class: 'btn btn-danger btn-sm eliminar',
                                contenido: '<i class="fa fa-trash"></i>',
                                value: obj.idProductoServAsig
                            });

                            return botonEliminar;
                        }
                    }
                },
            
            ],
            url: 'anexgrid_get_reporteProductos_asignados_a_oc',
            paginable: true,
            filtrable: true,
            limite: [10,15,20,25,30 ,50],
            columna: 'idProductoServAsig',
            columna_orden: 'DESC'
        });

        tabla_asignados_oc.tabla().on('click', '.eliminar', function(){
            eliminar($(this).val())
        });
    }

    // Tabla de productos asignados a ordenes de compra
    function cargarReporteProductos_otras_salidas(){
        tabla_otras_salidas = $("#reporte_otras_salidas").anexGrid({
            class: 'table table-striped table-bordered table-hover tablaAnexgrid',            
            columnas: [
                {leyenda: 'Folio de Orden', columna: 'ordenCompra', ordenable: true, filtro: true, style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'SKU', columna: 'sku' , ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Producto', columna: 'producto', ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Serie/Modelo', columna: 'serieYmodelo', ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Cantidad', columna: 'cantidadAsignada', ordenable: true, style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Salida', columna: 'motivo_salida', ordenable: true, filtro: false, style:'background-color: #C5EBFB;text-align:center'},
                // {leyenda: 'Salida de almacén',columna: 'almacen', ordenable: false, filtro: false , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Proveedor',columna: 'proveedor', ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: '',style: 'background-color: #C5EBFB;text-align:center'},
            ],
            modelo: [
                { propiedad: 'ordenCompra' ,style:'text-align:center'},
                { propiedad: 'sku' ,style:'text-align:center'},
                { propiedad: 'producto' ,style:'text-align:center'},
                { propiedad: 'serieYmodelo' ,style:'text-align:center'},
                { propiedad: 'cantidadAsignada' ,style:'text-align:center'},
                { propiedad: 'motivo_salida' ,style:'text-align:center'},
                // { propiedad: 'almacen',style:'text-align:center'},
                { propiedad: 'proveedor',style:'text-align:center'},
                { propiedad: 'idProductoServAsig',style:'text-align:center', 
                    formato:function(tr, obj,celda){
                        if(obj.idGrupoAsignacion != null){
                            let botonEliminar = anexGrid_boton({
                                class: 'btn btn-danger btn-sm eliminar',
                                contenido: '<i class="fa fa-trash"></i>',
                                value: obj.idProductoServAsig
                            });

                            return botonEliminar;
                        }
                    }
                },
            
            ],
            url: 'anexgrid_get_reporteProductos_otras_salidas',
            paginable: true,
            filtrable: true,
            limite: [10,15,20,25,30 ,50],
            columna: 'idProductoServAsig',
            columna_orden: 'DESC'
        });

        tabla_otras_salidas.tabla().on('click', '.eliminar', function(){
            eliminar($(this).val())
        });
    }

    function eliminar(idEliminar){
        console.log("elimo")
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('eliminarAsignacion') }}",
            type: "post",
            data: {idProductoServAsig: idEliminar},
        })
        // setTimeout(() => {
            .done(function(data){
                tabla_sin_asignar.refrescar();
                tabla_asignados_servicio.refrescar();
                tabla_asignados_oc.refrescar();
                tabla_otras_salidas.refrescar();
            });
        // }, 1300);
    }

</script>
@stop
