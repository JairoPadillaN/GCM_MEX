@extends('principal')
@section('contenido')
    <script type="text/javascript">
        $(document).ready(function() {

            $("#agrega").click(function(e) {
                var fechaInicio = $("#fechaInicio").val();
                var fechaFin = $("#fechaFin").val();

                if (fechaInicio == '' || fechaFin == '') {
                    if (fechaInicio == '') {
                        alert("Debes seleccionar una fecha inicial")
                        e.preventDefault();

                    }
                    if (fechaFin == '') {
                        alert("Debes seleccionar una fecha final")
                        e.preventDefault();

                    }
                } else {
                    $("#filtroFacturas").load('{{ url('reporteFacturasAbajo') }}' + '?' + $(this).closest(
                        'form').serialize());
                }
            });
        });
    </script>
    <form>
        <div class="col-md-12">
            <div class="panel panel-default" style="margin-top:-45px">
                <div class="panel-heading">
                    <h1 class="">Reporte de Servicios</h1>
                </div><br>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            Fecha de Inicio:<input type='date' name='fechaInicio' id='fechaInicio'
                                class="form-control rounded-0">
                        </div>

                        <div class="col-md-3">
                            Fecha de Fin:<input type='date' name='fechaFin' id='fechaFin'
                                class="form-control rounded-0">
                        </div>
                        <div class="col-md-6" style="margin-top:10px">
                            <button type="button" class="btn btn-primary" name="agrega" id="agrega">Aplicar
                                filtro</button>
                            <a href="{{ asset('reporteFacturas') }}"><button type="button" class="btn btn-default">Limpiar
                                    filtro</button></a>
                            @if (Session::get('sesiontipo') == 'Administrador')
                                <a href="{{ asset('altaFacturas') }}"><button type="button" class="btn btn-default">Agregar
                                        nuevo
                                        Servicio</button></a>
                            @endif
                            @if (Session::get('sesiontipo') != 'Almacen')
                                <a href="{{ URL::action('HomeController@export') }}"><img
                                        src="{{ asset('img/excelico.png') }}" height=50 width=50></a>
                            @endif
                        </div>
                    </div>
                    <br><br>
                    <div id='filtroFacturas'>

                        <div id="list" class="table-responsive"></div>

                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php $tipoSession = Session::get('sesiontipo'); ?>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        var tipoSesion = "<?php echo $tipoSession; ?>";
        var clasePersonalizada = "";
        var clasePersonalizadaAlmacen = "";
        var aplicaFiltro = true;
        var verTotal = "Si";

        if (tipoSesion == "Compras") {
            clasePersonalizada += `hidden`;
            aplicaFIltro = false;
            verTotal = "No";
        }

        if (tipoSesion == "Almacen") {
            clasePersonalizadaAlmacen = `hidden`;
        }

        $(document).ready(function() {

            var tablita = $("#list").anexGrid({
                class: 'table table-striped table-bordered table-hover',
                columnas: [{
                        leyenda: 'Folio de Servicio Asignado',
                        ordenable: true,
                        columna: 'idServicios',
                        filtro: true
                    },
                    {
                        leyenda: 'Folio de ___Factura___',
                        ordenable: true,
                        columna: 'numeroFactura',
                        filtro: true,
						
                    },
                    {
                        leyenda: 'Cotización Equipo',
                        ordenable: true,
                        columna: 'numeroCotizacion',
                        filtro: true
                    },
                    {
                        leyenda: 'Cotizacion Servicio',
                        ordenable: true,
                        columna: 'numeroServicio',
                        filtro: true
                    },
                    {
                        leyenda: 'Fecha de Facturación',
                        ordenable: true,
                        columna: 'fecha'
                    },
                    {
                        leyenda: 'Fecha de Pago',
                        ordenable: true,
                        columna: 'fechap'
                    },
                    {
                        leyenda: 'Cliente',
                        columna: 'razonSocial',
                        ordenable: true,
                        filtro: true
                    },
                    {
                        leyenda: 'Sucursal',
                        columna: 'sucursales.sucursal',
                        ordenable: true,
                        filtro: true
                    },
                    {
                        leyenda: 'Estatus Entrega',
                        columna: 'estatusEntrega',
                        ordenable: `${aplicaFiltro}`,
                        class: `${clasePersonalizada}`,
                        filtro: function() {
                            var filtro = "";
                            filtro = anexGrid_select({
                                data: [{
                                        valor: '',
                                        contenido: 'Todos'
                                    },
                                    {
                                        valor: 'Pendiente',
                                        contenido: 'Pendiente'
                                    },
                                    {
                                        valor: 'Entregado',
                                        contenido: 'Entregado'
                                    },
                                ]
                            });

                            if (tipoSesion == "Compras") {
                                filtro = false;
                            }

                            return filtro;
                        }
                    },
                    {
                        leyenda: 'Estatus Portal',
                        columna: 'estatusPortal',
                        ordenable: `${aplicaFiltro}`,
                        class: `${clasePersonalizada}`,
                        filtro: function() {
                            var filtro = "";
                            filtro = anexGrid_select({
                                data: [{
                                        valor: '',
                                        contenido: 'Todos'
                                    },
                                    {
                                        valor: 'Pendiente',
                                        contenido: 'Pendiente'
                                    },
                                    {
                                        valor: 'Subido en el portal',
                                        contenido: 'Subido'
                                    },
                                ]
                            });

                            if (tipoSesion == "Compras") {
                                filtro = false;
                            }

                            return filtro;
                        }
                    },
                    {
                        leyenda: 'Estatus Pago',
                        columna: 'estatusPago',
                        ordenable: `${aplicaFiltro}`,
                        class: `${clasePersonalizada}`,
                        filtro: function() {
                            var filtro = "";
                            filtro = anexGrid_select({
                                data: [{
                                        valor: '',
                                        contenido: 'Todos'
                                    },
                                    {
                                        valor: 'Pendiente',
                                        contenido: 'Pendiente'
                                    },
                                    {
                                        valor: 'Pagada',
                                        contenido: 'Pagada'
                                    },
                                ]
                            });

                            if (tipoSesion == "Compras") {
                                filtro = false;
                            }

                            return filtro;
                        }
                    },
                    {
                        leyenda: 'Cash Flow',
                        columna: 'cashFlow',
                        ordenable: `${aplicaFiltro}`,
                        class: (tipoSesion === "Almacen") ? `${clasePersonalizadaAlmacen}` :
                            `${clasePersonalizada}`,
                        filtro: function() {
                            var filtro = "";
                            filtro = anexGrid_select({
                                data: [{
                                        valor: '',
                                        contenido: 'Todos'
                                    },
                                    {
                                        valor: 'Si',
                                        contenido: 'Si'
                                    },
                                    {
                                        valor: 'No',
                                        contenido: 'No'
                                    },
                                ]
                            });

                            if (tipoSesion == "Compras") {
                                filtro = false;
                            }

                            return filtro;
                        }
                    },
                    {
                        leyenda: 'Monto cotización',
                        class: (tipoSesion === "Almacen") ? `${clasePersonalizadaAlmacen}` :
                            `${clasePersonalizada}`,
                    },
                    {
                        leyenda: 'Total',
                        class: (tipoSesion === "Almacen") ? `${clasePersonalizadaAlmacen}` :
                            `${clasePersonalizada}`,
                    },
                    {
                        leyenda: 'Descargar Archivos',
                        class: (tipoSesion === "Almacen") ? `${clasePersonalizadaAlmacen}` :
                            `""`,
                    },
                    {
                        leyenda: 'Opciones',
                        class: `${clasePersonalizada}`
                    },
                ],
                modelo: [{
                        propiedad: 'idServicios',
                        class: 'text-center'
                    },
                    {
                        propiedad: 'numeroFactura',
                        class: 'text-center'
                    },
                    {
                        propiedad: 'numeroCoti',
                        class: 'text-center'
                    },
                    {
                        propiedad: 'numeroSer',
                        class: 'text-center'
                    },
                    {
                        propiedad: 'fecha',
                        class: 'text-center'
                    },
                    {
                        propiedad: 'fechap',
                        class: 'text-center'
                    },
                    {
                        propiedad: 'razonSocial'
                    },
                    {
                        propiedad: 'sucursal',
                        class: 'text-center'
                    },
                    {
                        propiedad: 'estatusEntrega',
                        class: `text-center ${clasePersonalizada}`
                    },
                    {
                        propiedad: 'estatusPortal',
                        class: `text-center ${clasePersonalizada}`
                    },
                    {
                        propiedad: 'estatusPago',
                        class: `text-center ${clasePersonalizada}`
                    },
                    {
                        propiedad: 'cashFlow',
                        class: `text-center ${(tipoSesion === "Almacen") ? clasePersonalizadaAlmacen : clasePersonalizada}`
                    },
                    {
                        propiedad: 'monto',
                        class: `text-center ${(tipoSesion === "Almacen") ? clasePersonalizadaAlmacen : clasePersonalizada}`

                    },
                    {
                        propiedad: 'total',
                        class: `text-center ${(tipoSesion === "Almacen") ? clasePersonalizadaAlmacen : clasePersonalizada}`

                    },
                    {
                        propiedad: 'descargarArchivos',
                        class: `text-center ${(tipoSesion === "Almacen") ? clasePersonalizadaAlmacen : ""}`,
                        formato: function(tr, obj, celda) {

                            let respuestaFinal = '';

                            if (obj.tipoServicio != 'Soporte Técnico') {

                                if (obj.archivoCotizacion && obj.archivoCotizacion !=
                                    'Sin archivo') {
                                    respuestaFinal += '<a target="_blank" href="archivos/' +
                                        obj.archivoCotizacion +
                                        '" class="btn btn-success btn-sm">Cotización ↓</a>';
                                } else {
                                    respuestaFinal +=
                                        '<a type="button" class="btn btn-default btn-sm">Cotización ↓</a>';
                                }

                                if (obj.idCotizacion) {
                                    respuestaFinal += anexGrid_boton({
                                        class: 'btn btn-success btn-sm pdfCotizacionEquipos',
                                        contenido: 'Cotización del Sistema ↓',
                                        value: obj.idCotizacion
                                    });
                                } else {
                                    respuestaFinal += anexGrid_boton({
                                        class: 'btn btn-default btn-sm',
                                        contenido: 'Cotización del Sistema ↓',
                                        value: obj.idCotizacion
                                    });
                                }

                            } else {

                                if (obj.archivoCotizacionServicios && obj
                                    .archivoCotizacionServicios != 'Sin archivo') {
                                    respuestaFinal += '<a target="_blank" href="archivos/' +
                                        obj.archivoCotizacionServicios +
                                        '" class="btn btn-success btn-sm">Cotización ↓</a>';
                                } else {
                                    respuestaFinal +=
                                        '<a type="button" class="btn btn-default btn-sm">Cotización ↓</a>';
                                }

                                if (obj.idCotizacionServicios) {
                                    respuestaFinal += anexGrid_boton({
                                        class: 'btn btn-success btn-sm pdfCotizacionServicios',
                                        contenido: 'Cotización del Sistema ↓',
                                        value: obj.idCotizacionServicios
                                    });
                                } else {
                                    respuestaFinal += anexGrid_boton({
                                        class: 'btn btn-default btn-sm ',
                                        contenido: 'Cotización del Sistema ↓',
                                        value: obj.idCotizacionServicios
                                    });
                                }
                            }

                            if (obj.archivo == 'Sin archivo') {
                                respuestaFinal +=
                                    '<a type="button" class="btn btn-default btn-sm">Orden ↓</a>';
                            } else {
                                respuestaFinal += '<a target="_blank" href="archivos/' + obj
                                    .archivo + '" class="btn btn-success btn-sm">Orden ↓</a>';
                            }

                            if (obj.archivoFactura == 'Sin archivo') {
                                respuestaFinal +=
                                    '<a type="button" class="btn btn-default btn-sm">Factura ↓</a>';
                            } else {
                                respuestaFinal += '<a target="_blank" href="archivos/' + obj
                                    .archivoFactura +
                                    '" class="btn btn-success btn-sm">Factura ↓</a>';
                            }

                            if (obj.archivoPago == 'Sin archivo') {
                                respuestaFinal +=
                                    '<a type="button" class="btn btn-default btn-sm">Pago ↓</a>';
                            } else {
                                respuestaFinal += '<a target="_blank" href="archivos/' + obj
                                    .archivoPago + '" class="btn btn-success btn-sm">Pago ↓</a>';
                            }

                            if (obj.xmlFactura == 'Sin archivo') {
                                respuestaFinal +=
                                    '<a type="button" class="btn btn-default btn-sm">XML ↓</a>';
                            } else {
                                respuestaFinal += '<a target="_blank" href="archivos/' + obj
                                    .xmlFactura + '" class="btn btn-success btn-sm">XML ↓</a>';
                            }

                            if (obj.archivoRemision == 'Sin archivo') {
                                respuestaFinal +=
                                    '<a type="button" class="btn btn-default btn-sm">Remisión ↓</a>';
                            } else {
                                respuestaFinal += '<a target="_blank" href="archivos/' + obj
                                    .archivoRemision +
                                    '" class="btn btn-success btn-sm">Remisión ↓</a>';
                            }

                            if (obj.archivoAdenda == 'Sin archivo') {
                                respuestaFinal +=
                                    '<a type="button" class="btn btn-default btn-sm">Adenda ↓</a>';
                            } else {
                                respuestaFinal += '<a target="_blank" href="archivos/' + obj
                                    .archivoAdenda +
                                    '" class="btn btn-success btn-sm">Adenda ↓</a>';
                            }

                            if (tipoSesion == "Compras") {
                                respuestaFinal = "";
                                if (obj.idCotizacion) {
                                    respuestaFinal += anexGrid_boton({
                                        class: 'btn btn-success btn-sm pdfCotizacionEquipos',
                                        contenido: 'Cotización del Sistema ↓',
                                        value: obj.idCotizacion
                                    });
                                } else {
                                    respuestaFinal += anexGrid_boton({
                                        class: 'btn btn-default btn-sm',
                                        contenido: 'Cotización del Sistema ↓',
                                        value: obj.idCotizacion
                                    });
                                }
                            }

                            return respuestaFinal;
                        },
                        style: 'width:300px;'
                    },
                    {
                        class: `text-center ${clasePersonalizada}`,
                        formato: function(tr, obj, celda) {

                            let botones = '';
                            if (tipoSesion == 'Administrador') {

                                if (obj.activo == 'si') {

                                    botones += anexGrid_link({
                                        class: 'btn btn-sm btn-info',
                                        contenido: '<i class="ace-icon fa fa-pencil bigger"></i>',
                                        href: 'modificarFacturas/' + obj.idFactura
                                    });

                                    botones += anexGrid_link({
                                        class: 'btn btn-sm btn-danger',
                                        contenido: '<i class="fa fa-trash-o"></i>',
                                        href: 'eliminarFacturas/' + obj.idFactura
                                    });

                                    if (obj.servicioActivo == 'Cerrado') {
                                        botones += anexGrid_link({
                                            class: 'btn btn-sm btn-success',
                                            contenido: 'Activar <i class="fa fa-check"></i>',
                                            href: 'activarServicio/' + obj.idFactura
                                        });

                                    } else {
                                        botones += anexGrid_link({
                                            class: 'btn btn-sm btn-danger',
                                            contenido: 'Cerrar <i class="fa fa-remove"></i>',
                                            href: 'cerrarServicio/' + obj.idFactura
                                        });
                                    }

                                } else {

                                    botones += anexGrid_link({
                                        class: 'btn btn-xs btn-warning',
                                        contenido: 'Restaurar',
                                        href: 'restaurarFacturas/' + obj.idFactura
                                    });

                                }

                            } else if (tipoSesion == 'Vendedor' || tipoSesion == 'Compras' || tipoSesion == 'Almacen') {

                                botones += anexGrid_link({
                                    class: 'btn btn-sm btn-info',
                                    contenido: '<i class="ace-icon fa fa-pencil bigger"></i>',
                                    href: 'modificarFacturas/' + obj.idFactura
                                });
                            }

                            return botones;

                        },
                    },
                ],
                url: 'get-data-facturas',
                paginable: true,
                filtrable: true,
                limite: [5, 10, 20, 50, 100],
                columna: 'idServicios',
                columna_orden: 'DESC'
            });

            tablita.tabla().on('click', '.pdfCotizacionEquipos', function() {
                var a = $(this).val();
                window.open('{{ url('pdfCotizacion') }}' + '?idCotizacion=' + a + '&verTotal=' + verTotal +
                    '&pld=1' + '&cD=Si');
            });

            tablita.tabla().on('click', '.pdfCotizacionServicios', function() {
                var a = $(this).val();
                window.open('{{ url('pdfCotizacionServicios') }}' + '?idCotizacionServicios=' + a +
                    '&verTotal=' + verTotal + '&pld=1' + '&cD=Si');
            });
        })
    </script>
@stop
