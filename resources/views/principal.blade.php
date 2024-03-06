<!DOCTYPE html>
<!--
Template Name: BRILLIANT Bootstrap Admin Template
Version: 4.5.6
Author: WebThemez
Website: http://www.webthemez.com/
-->
<style type="text/css">
    .container {
        text-align: center
    }

    .left {
        float: left;
        background: blue
    }

    .right {
        float: right;
        background: red
    }

    .center {
        background: green;
        display: inline-block
    }
</style>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="" name="description" />
    <meta content="webthemez" name="author" />
    <title>CM GROUP MEX</title>
    <!-- Tablas con filtros en las columnas -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.dataTables.min.css">
    <!--  -->
    <link rel="icon" type="image/png" href="{!! asset('estiloslogin/images/icons/favicon.ico') !!}" />
    <!-- Bootstrap Styles-->
    <link href="{!! asset('assets/css/bootstrap.css') !!}" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <!-- <link href="/your-path-to-fontawesome/css/fontawesome.css" rel="stylesheet"> -->
    <link href="{!! asset('assets/css/font-awesome.css') !!}" rel="stylesheet" />
    <!-- Morris Chart Styles-->
    <link href="{!! asset('assets/js/morris/morris-0.4.3.min.css') !!}" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="{!! asset('assets/css/custom-styles.css') !!}" rel="stylesheet" />
    <link href="{!! asset('assets/css/menu.css') !!}" rel="stylesheet" />
    <link href="{!! asset('assets/css/chosen.css') !!}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

    <!-- Google Fonts-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' /> -->
    <link rel="stylesheet" href="{!! asset('assets/js/Lightweight-Chart/cssCharts.css') !!}">
    <!-- jQuery Js -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <!-- Para validaciones a nivel cliente -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="{!! asset('dataTable/datatables-bs4/css/dataTables.bootstrap.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('dataTable/datatables-responsive/css/responsive.bootstrap.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('dataTable/datatables-buttons/css/buttons.bootstrap.min.css') !!}">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.20/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        /*estilos para la tabla*/
        table th {
            /* background-color: #659CCA; */
            text-align: center;
            /* color: black; */
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-default top-navbar" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse" style="width:38px;">
                <span class="sr-only" style="width:16px;">Toggle navigation</span>
                <span class="icon-bar" style="width:16px;"></span>
                <span class="icon-bar" style="width:16px;"></span>
                <span class="icon-bar" style="width:16px;"></span>
            </button>


            <a class="navbar-brand" href=""><img src="{!! asset('archivos/logoparasistema.PNG') !!}" style="position: absolute; top:0; left:0; height:60px; width:160px;"></a>
            <div id="sideNav" href="">
                <i class="fa fa-bars icon"></i>
            </div>
            <button type="button" class="navbar-toggle" data-toggle="collapse" style="position: absolute;height:34px; width:38px;margin-left:5px;" data-target=".navbar-ex1-collapse">
                <ul class="nav navbar-top-links" style="margin-top:-23px; margin-left:-20px;">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                            <i class="fa fa-user fa-fw"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user" style="margin-left:-270%;margin-top:-18%;">
                            <li><a><i class="fa fa-user fa-fw"></i>{!! Session::get('sesionname') !!} {!! Session::get('sesionpaterno') !!}
                                    {!! Session::get('sesionmaterno') !!}</a>
                            </li>
                            <li><a href="{!! URL::action('login@cerrarsesionuser') !!}"><i class="fa fa-sign-out fa-fw"></i>
                                    Salir</a>
                            </li>
                        </ul>
                    </li>
                    <!-- /.dropdown -->
                </ul>
            </button>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i>{!! Session::get('sesionname') !!}
                                {!! Session::get('sesionpaterno') !!}
                                {!! Session::get('sesionmaterno') !!}</a>
                        </li>
                        <li><a href="{!! URL::action('login@cerrarsesionuser') !!}"><i class="fa fa-sign-out fa-fw"></i>
                                Salir</a>
                        </li>
                    </ul>
                </li>
                <!-- /.dropdown -->
            </ul>
    </nav><!-- Grid column -->
    <nav class="navbar-default navbar-side" role="navigation" style="margin-top:-19px;">
        <div class="sidebar-collapse">
            <!-- Exaple 1 -->
            <div class="card example-1 scrollbar-ripe-malinka">
                @if (Session::get('sesiontipo') == 'Administrador')
                <ul class="nav" id="main-menu">
                    <li>
                        <a href="#"><i class="fa fa-sitemap"></i>Catalogos<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="#"><i class="fa fa-user fa-fw"></i>Usuarios<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('AltaUsuarios') !!}">Nuevo usuario</a>
                                    </li>
                                    <li>
                                        <a href="{!! asset('ReporteUsuarios') !!}">Consulta de usuarios</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-briefcase fa-fw"></i>Clientes<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaClientes') !!}">Nuevo Cliente</a>
                                    </li>
                                    <li>
                                        <a href="{!! asset('consultaClientes') !!}">Consultar Clientes</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-building-o" aria-hidden="true"></i>Sucursales<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaSucursales') !!}">Nueva Sucursal</a>
                                    </li>
                                    <li>
                                        <a href="{!! asset('reporteSucursales') !!}">Consultar Sucursal</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-tag fa-fw"></i>Marcas<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('AltaMarcas') !!}">Nueva Marca</a>
                                    </li>
                                    <li>
                                        <a href="{!! asset('reporteMarcas') !!}">Consultar Marcas</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-bell fa-fw"></i>Servicios<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaServicios') !!}">Nuevo Servicio</a>
                                    </li>
                                    <li>
                                        <a href="{!! asset('reporteServicios') !!}">Consultar Servicios</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-th-large fa-fw"></i>Tipos de equipo<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaTipoEquipos') !!}">Nuevo tipo</a>
                                    </li>
                                    <li>
                                        <a href="{!! asset('reporteTipoEquipos') !!}">Consultar tipos</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa fa-th fa-fw"></i>Subtipos<span class="fa arrow"></span></a>
                                        <ul class="nav nav-second-level">
                                            <li>
                                                <a href="{!! asset('altaSubtipoEquipos') !!}">Nuevo subtipo</a>
                                            </li>
                                            <li>
                                                <a href="{!! asset('reporteSubtipoEquipos') !!}">Consultar
                                                    subtipos</a>
                                            </li>
                                        </ul>
                                    <li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-truck fa-fw"></i>Vehículos<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('AltaVehiculos') !!}">Nuevo Vehículo</a>
                                    </li>
                                    <li>
                                        <a href="{!! asset('ReporteVehiculos') !!}">Consultar Vehículos</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-bitcoin fa-fw"></i>Bancos<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaBancos') !!}">Nuevo Banco</a>
                                        <a href="{!! asset('reporteBancos') !!}">Consultar Bancos</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-money fa-fw"></i>Tipos de gasto<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li><a href="{!! asset('AltaTipoGastos') !!}">Nuevo Tipo de gasto</a></li>
                                    <li><a href="{!! asset('ReporteTipoGastos') !!}">Consultar Tipos de gastos</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-map-marker fa-fw"></i>Proveedores<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaProveedores') !!}">Nuevo proveedor</a>
                                        <a href="{!! asset('reporteProveedores') !!}">Consultar proveedores</a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="#"><i class="fa fa-bitcoin fa-fw"></i>Cuentas<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaCuentas') !!}">Nueva cuenta</a>
                                        <a href="{!! asset('reporteCuentas') !!}">Consultar cuentas</a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="#"><i class="fa fa-wrench fa-fw"></i>Talleres<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaTaller') !!}">Nuevo taller</a>
                                        <a href="{!! asset('reporteTaller') !!}">Consultar talleres</a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="#"><i class="fa fa-cog fa-fw"></i>Servicios en taller<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaServTaller') !!}">Nuevo servicio en taller </a>
                                        <a href="{!! asset('reporteServTaller') !!}">Consultar servicios en taller</a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="#"><i class="fa fa-asterisk fa-fw"></i>Estatus de reparación<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaEstatusReparaciones') !!}">Nuevo estatus de
                                            reparación</a>
                                        <a href="{!! asset('reporteEstatusReparaciones') !!}">Consultar estatus de
                                            reparación</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-refresh fa-fw"></i>Alcances<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaAlcances') !!}">Nuevo alcance</a>
                                        <a href="{!! asset('reporteAlcances') !!}">Consultar alcances</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-credit-card"></i>Comisiones<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('rangocomisiones') !!}">Rango de Comisiones</a>
                                    </li>

                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-random fa-fw"></i>Movimientos de Salida<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{!! asset('reporteMovimientos') !!}">Caja chica </a>
                                <!--<a href="{!! asset('reporteMovimientos') !!}">Consultar movimientos</a> -->
                                <a href="{!! asset('') !!}">Nuevo movimiento viaticos</a>
                                <a href="{!! asset('') !!}">Nuevo movimiento gasolina </a>
                                <a href="{!! asset('') !!}">Nuevo movimiento compras</a>
                            </li>
                        </ul>
                    <li>
                        <a href="#"><i class="fa fa-road fa-fw"></i>Viajes<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{!! asset('altaSalidaViajes') !!}">Nuevo Viaje</a>
                                <a href="{!! asset('reporteViajes') !!}">Consultar Viajes</a>
                            </li>
                    </li>
                </ul>
                <li>
                    <a href="#"><i class="fa fa-folder fa-fw"></i>Archivos contables<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{!! asset('archivosContab') !!}">Archivos</a>

                        </li>
						<li>
                            <a href="{!! asset('archivosUnicos') !!}">Archivos Únicos</a>
                        </li>
                        <li>
                            <a href="{!! asset('reporteGastosOperativos') !!}">Estado de Resultados</a>

                        </li>
                    </ul>
                </li>
                <li> <a href="#"><i class="fa fa-list-alt fa-fw"></i>Servicios<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                        <li>
                            <a href="#"><i class="fa fa-list-alt fa-fw"></i>Opciones de Servicios<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaFacturas') !!}">Nuevo Servicio</a>
                                </li>
                                <!--<li>
                                    <a href="{!! asset('reporteFacturas') !!}">Consultar Servicios (old)</a>
                                </li>-->
                                <li>
                                    <a href="{!! asset('reporteFacturacion') !!}">Consultar Servicios</a>
                                </li>
                                <li>
                                    <a href="{!! asset('reporteGastos') !!}">Consultar Gastos</a>
                                </li>
                            </ul>
                        <li>
                            <a href="#"><i class="fa fa-list-alt fa-fw"></i>Reportes<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('reporteGeneralGastos') !!}">Reporte de gastos</a>
                                </li>
                                <li>
                                    <a href="{!! asset('reporteOrdenesCompra') !!}">Reporte de compras</a>
                                </li>
                                <!--<li>
                                    <a href="{{ route('reporteUtilidadGeneral') }}">Reporte general de utilidad</a>
                                </li>-->
                                <li>
                                    <a href="{!! asset('reporteCuentasContables') !!}">Reporte de cuentas contables</a>
                                </li>
                                <!--<li>
                                    <a href="{!! asset('reporteventas') !!}">Reporte de ventas</a>
                                </li>-->
                                <!--<li>
                                    <a href="{!! asset('reportecomisiones') !!}">Reporte de comisiones</a>
                                </li>-->
                                <li>
                                    <a href="{!! asset('reporteGlobalVentas') !!}">Reporte global de ventas</a>
                                </li>
                            </ul>
                        </li>
                        <!--<li>
                                <a href="{!! asset('reporteFacturasAsignadas') !!}"><i class="fa fa-list"></i>Consultar
                                    servicios asignados</a>
                            </li>-->
                </li>
                </ul>
                <li>
                    <a href="#"><i class="fa fa-file-text"></i>Ordenes de compra<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{!! asset('altaOrdenCompra') !!}">Nueva Orden</a>
                            <a href="{!! asset('reporteOrdenesCompra') !!}">Consultar Ordenes</a>
                            <a href="{!! asset('reporteProductosEnOC') !!}">Productos en Ordenes</a>
                            <a href="{!! asset('reporteProductosEnNotaEntrada') !!}">Productos en Notas entrada</a>
                            <a href="{!! asset('reportePagosProveedores') !!}">Reporte de pagos a proveedores</a>
                        </li>
                    </ul>
                </li>
                <li> <a href="#"><i class="fa fa-wrench fa-fw"></i>Recepción de equipo<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{!! asset('altaEquipos') !!}">Nueva entrada</a>
                        </li>
                        <li>
                            <a href="{!! asset('reporteEquipos') !!}">Consultar
                                entradas</a>
                        </li>
                        <li>
                            <a href="{!! asset('reportePorEmpresa') !!}">Consultar por empresa</a>
                        </li>
                        <li>
                            <a href="{!! asset('reportePorTaller') !!}">Consultar por taller</a>
                        </li>
                        <li>
                            <a href="{!! asset('reporteEquiposServicios') !!}">Reporte de equipos en servicios</a>
                        </li>
                        <li>
                            <a href="{!! asset('reporteEquiposPorTecnico') !!}">Reporte de equipos por tecnico</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#"><i class="fa fa-star fa-fw"></i>CRM<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <!-- <li>

                                <a href="#">Investigación de prospectos<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaProspectos') !!}">Agregar nuevo prospecto</a>
                                    </li>
                                    <li>
                                        <a href="{!! asset('reporteProspectos') !!}">Consultar prospecto</a>
                                    </li>
                                </ul>
                            </li> -->
                        <!-- <li>

                                <a href="#">Servicios de publicidad<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaServPublicidad') !!}">Agregar nuevo servicio de publicidad</a>
                                    </li>
                                    <li>
                                        <a href="{!! asset('reporteServPublicidad') !!}">Consultar servicios de publicidad</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                            <a href="#">Gestion de citas<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaCitas') !!}">Agregar nueva cita</a>
                                    </li>
                                    <li>
                                        <a href="{!! asset('reporteCitas') !!}">Consultar citas</a>
                                    </li>
                                </ul>
                            </li> -->
                        <li>
                            <a href="#">Seguimiento de proyectos<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaSeg') !!}">Agregar nuevo proyecto</a>
                                </li>
                                <li>
                                    <a href="{!! asset('reporteSeguimientoVista') !!}">Consultar proyectos</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-list-alt fa-fw"></i>Áreas<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaAreas') !!}">Nueva área</a>
                                    <a href="{!! asset('reporteAreas') !!}">Consultar áreas</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-list-alt fa-fw"></i>Actividades<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaActividades') !!}">Nueva actividad</a>
                                    <a href="{!! asset('reporteActividades') !!}">Consultar actividades</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="{!! asset('reporteActividadesSeg') !!}">Reporte General de actividades</a>
                        </li>

                    </ul>
                </li>

                <li>
                    <a href="#"><i class="fa fa-credit-card"></i>Catálogo de partes<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{!! asset('altaPartesVenta') !!}">Agregar parte</a>
                        </li>
                        <li>
                            <a href="{!! asset('reportePartesVenta') !!}">Consultar parte</a>
                        </li>
                        <li>
                            <a href="{!! asset('reporteSKUServicio') !!}">Reporte de SKU's en Servicios</a>
                        </li>
                        <li>
                            <a href="{!! asset('reporteSKUCotizacion') !!}">Reporte de SKU's en Cotizaciones</a>
                        </li>
                        <li>
                            <a href="{!! asset('skuVendido') !!}">Reporte de SKU's vendidos por servicio</a>
                        </li>
						<li>
                            <a href="{!! asset('reporteTopVentas') !!}">Reporte Top de ventas</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-cog"></i>Marcas Refacciones<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaMarcasRefaccion') !!}">Nueva Marca Refacciones</a>
                                </li>
                                <li>
                                    <a href="{!! asset('reporteMarcasRefaccion') !!}">Consultar Marcas Refacciones</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-th-large fa-fw"></i>Tipos de refacciones<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaTipoRef') !!}">Nuevo tipo</a>
                                </li>
                                <li>
                                    <a href="{!! asset('reporteTipoRef') !!}">Consultar tipos</a>
                                </li>
                                <!-- <li>
                                            <a href="#"><i class="fa fa-th fa-fw"></i>Refacciones<span
                                                    class="fa arrow"></span></a>
                                            <ul class="nav nav-second-level">
                                                <li>
                                                    <a href="{!! asset('altaRefacciones') !!}">Nueva refacción</a>
                                                </li>
                                                <li>
                                                    <a href="{!! asset('reporteRefacciones') !!}">Consultar
                                                        refacciones</a>
                                                </li>
                                            </ul>
                                        <li> -->
                            </ul>
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-book"></i>Paquetes<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaPaquetes') !!}">Nuevo paquete</a>
                                </li>
                                <li>
                                    <a href="{!! asset('reportePaquetes') !!}">Consultar paquetes</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                <li>
                    <a href="#"><i class="fa fa-calculator"></i>Cotizaciones<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{!! asset('cotizaciones') !!}">Cotización de equipos</a>
                        </li>
                        <li>
                            <a href="{!! asset('cotizacionServicios') !!}">Cotización de servicios</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-list-alt fa-fw"></i>Opciones de Servicios<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaServicioTecnico') !!}">Crear servicio</a>
                                </li>
                                <li>
                                    <a href="{!! asset('reporteServiciosTecnicos') !!}">Consultar servicios</a>
                                </li>
                            </ul>
                        <li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-cogs" aria-hidden="true"></i>Entradas a Almacén <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{!! asset('altaEntradaAlmacen') !!}">Nueva Entrada</a>
                        </li>
                        <li>
                            <a href="{!! asset('reporteEntradaAlmacen') !!}">Reporte Entradas a Almacén</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-sitemap"></i>Salidas de Almacén<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{!! asset('altaAsignacionProductosServicios') !!}">Asignar producto a servicio</a>
                        </li>
                        <li>
                            <a href="{!! asset('reporteAsignacionProductosServicios') !!}">Reporte productos en almacén y servicios</a>
                        </li>
                        <li>
                            <a href="{!! asset('reporteRastreoAsignacion_productos') !!}">Rastreo de asignación</a>
                        </li>
                    </ul>
                </li>

                <!-- Otros Ingresos -->
                <li>
                    <a href="#"><i class="fa fa-bar-chart fa-fw"></i>Ingresos <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{{ route('reporteOtrosing') }}">Otros Ingresos</a>
                        </li>
                    </ul>
                </li>

                <!-- Gastos Operativos -->
                <li>
                    <a href="#"><i class="fa fa-list-alt fa-fw"></i>Gastos operativos<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{{ route('GastosOperativos') }}">Consultar Gastos operativos</a>
                        </li>
                    </ul>
                </li>

                @if (Session::get('sesionidu') === 1 || Session::get('sesionidu') === 15)
                <li>
                    xd
                </li>
            @else
                <li>
                    <a href="#"><i class="fa fa-list-ul fa-fw"></i>RFQ<span
                            class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                        <li>
                            <a href="{!! asset('reporteRfqs') !!}">Consultar RFQ</a>
                    </ul>
                <li>
            @endif
                    @endif


                    @if (Session::get('sesiontipo') == 'Vendedor')
                    <ul class="nav" id="main-menu">

                        <li>
                            <a href="#"><i class="fa fa-sitemap"></i>Catálogos<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#"><i class="fa fa-briefcase fa-fw"></i>Clientes<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li>
                                            <a href="{!! asset('altaClientes') !!}">Nuevo Cliente</a>
                                        </li>
                                        <li>
                                            <a href="{!! asset('consultaClientes') !!}">Consultar Clientes</a>
                                        </li>
                                    </ul>
                                </li>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-building-o" aria-hidden="true"></i>Sucursales<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaSucursales') !!}">Nueva Sucursal</a>
                                </li>
                                <li>
                                    <a href="{!! asset('reporteSucursales') !!}">Consultar Sucursales</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-tag fa-fw"></i>Marcas<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('AltaMarcas') !!}">Nueva Marca</a>
                                </li>
                                <li>
                                    <a href="{!! asset('reporteMarcas') !!}">Consultar Marcas</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-th-large fa-fw"></i>Tipos de equipo<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaTipoEquipos') !!}">Nuevo tipo</a>
                                </li>
                                <li>
                                    <a href="{!! asset('reporteTipoEquipos') !!}">Consultar tipos</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fa fa-th fa-fw"></i>Subtipos<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li>
                                            <a href="{!! asset('altaSubtipoEquipos') !!}">Nuevo subtipo</a>
                                        </li>
                                        <li>
                                            <a href="{!! asset('reporteSubtipoEquipos') !!}">Consultar
                                                subtipos</a>
                                        </li>
                                    </ul>
                                <li>
                            </ul>
                        </li>
                        <!-- <li>
                            <a href="#"><i class="fa fa-map-marker fa-fw"></i>Proveedores<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaProveedores') !!}">Nuevo proveedor</a>
                                    <a href="{!! asset('reporteProveedores') !!}">Consultar proveedores</a>
                                </li>
                            </ul>
                        </li> -->

                        <li>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i>Talleres<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaTaller') !!}">Nuevo taller</a>
                                    <a href="{!! asset('reporteTaller') !!}">Consultar talleres</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-cog fa-fw"></i>Servicios en taller<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaServTaller') !!}">Nuevo servicio en taller </a>
                                    <a href="{!! asset('reporteServTaller') !!}">Consultar servicios en taller</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-refresh fa-fw"></i>Alcances<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaAlcances') !!}">Nuevo alcance</a>
                                    <a href="{!! asset('reporteAlcances') !!}">Consultar alcances</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-road fa-fw"></i>Viajes<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{!! asset('altaSalidaViajes') !!}">Nuevo Viaje</a>
                            <a href="{!! asset('reporteViajes') !!}">Consultar Viajes</a>
                        </li>
                    </ul>
                </li>

                <li> <a href="#"><i class="fa fa-list-alt fa-fw"></i>Servicios<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                        <li>
                            <!-- <a href="#"><i class="fa fa-list-alt fa-fw"></i>Opciones de Servicios<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level"> -->
                            <!--<li>
                                            <a href="{!! asset('altaFacturas') !!}">Nuevo Servicio</a>
                                        </li> -->
                        <!--<li>
                                    <a href="{!! asset('reporteFacturas') !!}">Consultar Servicios (old)</a>
                                </li>-->
                        <li>
                            <a href="{!! asset('reporteFacturacion') !!}">Consultar Servicios</a>
                        </li>
                    </ul>
                <li>
                    <!-- <a href="{!! asset('reporteFacturasAsignadas') !!}"><i class="fa fa-list"></i>Consultar servicios asignados</a>
                                    </li> -->
                </li>
                <li> <a href="#"><i class="fa fa-wrench fa-fw"></i>Recepción de equipo<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{!! asset('altaEquipos') !!}">Nueva entrada</a>
                        </li>
                        <li>
                            <a href="{!! asset('reporteEquipos') !!}">Consultar
                                entradas</a>
                        </li>
                        <li>
                            <a href="{!! asset('reporteEquiposServicios') !!}">Reporte de equipos en servicios</a>
                        </li>
                        <li>
                            <a href="{!! asset('reportePorEmpresa') !!}">Consultar por empresa</a>
                        </li>
                        <li>
                            <a href="{!! asset('reportePorTaller') !!}">Consultar por taller</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-star fa-fw"></i>CRM<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <!-- <li>

                                    <a href="#">Investigación de prospectos<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li>
                                            <a href="{!! asset('altaProspectos') !!}">Agregar nuevo prospecto</a>
                                        </li>
                                        <li>
                                            <a href="{!! asset('reporteProspectos') !!}">Consultar prospecto</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>

                                    <a href="#">Servicios de publicidad<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li>
                                            <a href="{!! asset('altaServPublicidad') !!}">Agregar nuevo servicio de publicidad</a>
                                        </li>
                                        <li>
                                            <a href="{!! asset('reporteServPublicidad') !!}">Consultar servicios de publicidad</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                <a href="#">Gestion de citas<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li>
                                            <a href="{!! asset('altaCitas') !!}">Agregar nueva cita</a>
                                        </li>
                                        <li>
                                            <a href="{!! asset('reporteCitas') !!}">Consultar citas</a>
                                        </li>
                                    </ul>
                                </li> -->

                        <li>
                            <a href="#">Seguimiento de proyectos<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaSeg') !!}">Agregar nuevo proyecto</a>
                                </li>
                                <li>
                                    <a href="{!! asset('reporteSeguimientoVista') !!}">Consultar proyectos</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-list-alt fa-fw"></i>Actividades<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaActividades') !!}">Nueva actividad</a>
                                    <a href="{!! asset('reporteActividades') !!}">Consultar actividades</a>
                                </li>
                            </ul>
                        </li>
                        <!-- <li>
                                    <a href="{!! asset('reporteDeActividades') !!}">Reporte de actividades</a>
                                </li> -->

                        <li>
                            <a href="{!! asset('reporteActividadesSeg') !!}">Reporte general de actividades</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <!-- <a href="#"><i class="fa fa-cogs" aria-hidden="true"></i>Entradas a Almacen <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('reporteEntradaAlmacen') !!}">Consultar Entradas</a>
                                </li>
                                <li>
                                    <a href="{!! asset('reporteRefacciones') !!}">Consultar refacciones</a>
                                </li>
                            </ul> -->
                </li>

                <li>
                    <a href="#"><i class="fa fa-credit-card"></i>Catálogo de partes<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{!! asset('altaPartesVenta') !!}">Agregar parte</a>
                        </li>
                        <li>
                            <a href="{!! asset('reportePartesVenta') !!}">Consultar parte</a>
                        </li>
                        <li>
                            <a href="{!! asset('reporteSKUServicio') !!}">Reporte de SKU's en Servicios</a>
                        </li>
                        <li>
                            <a href="{!! asset('reporteSKUCotizacion') !!}">Reporte de SKU's en Cotizaciones</a>
                        </li>
                        <li>
                            <a href="{!! asset('skuVendido') !!}">Reporte de SKU's vendidos por servicio</a>
                        </li>
						<li>
                            <a href="{!! asset('reporteTopVentas') !!}">Reporte Top de ventas</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-cog"></i>Marcas Refacciones<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaMarcasRefaccion') !!}">Nueva Marca Refacciones</a>
                                </li>
                                <li>
                                    <a href="{!! asset('reporteMarcasRefaccion') !!}">Consultar Marcas Refacciones</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-th-large fa-fw"></i>Tipos de refacciones<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaTipoRef') !!}">Nuevo tipo</a>
                                </li>
                                <li>
                                    <a href="{!! asset('reporteTipoRef') !!}">Consultar tipos</a>
                                </li>
                                <!-- <li>
                                                <a href="#"><i class="fa fa-th fa-fw"></i>Refacciones<span
                                                        class="fa arrow"></span></a>
                                                <ul class="nav nav-second-level">
                                                    <li>
                                                        <a href="{!! asset('altaRefacciones') !!}">Nueva refacción</a>
                                                    </li>
                                                    <li>
                                                        <a href="{!! asset('reporteRefacciones') !!}">Consultar
                                                            refacciones</a>
                                                    </li>
                                                </ul>
                                            <li> -->
                            </ul>
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-book"></i>Paquetes<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaPaquetes') !!}">Nuevo paquete</a>
                                </li>
                                <li>
                                    <a href="{!! asset('reportePaquetes') !!}">Consultar paquetes</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                <li>
                    <a href="#"><i class="fa fa-calculator"></i>Cotizaciones<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{!! asset('cotizaciones') !!}">Cotización de equipos</a>
                        </li>
                        <li>
                            <a href="{!! asset('cotizacionServicios') !!}">Cotización de servicios</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-list-alt fa-fw"></i>Opciones de Servicios<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('altaServicioTecnico') !!}">Crear servicio</a>
                                </li>
                                <li>
                                    <a href="{!! asset('reporteServiciosTecnicos') !!}">Consultar servicios</a>
                                </li>
                            </ul>
                        <li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-list-ul fa-fw"></i>RFQ<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                        <li>
                            <a href="{!! asset('reporteRfqs') !!}">Consultar RFQ</a>
                    </ul>
                <li>
                    </ul>
                </li>
                </ul>
                <!-- Gastos Operativos -->
                <!--
                    <li>
                        <a href="#"><i class="fa fa-list-alt fa-fw"></i>Gastos operativos<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{{ route('GastosOperativos') }}">Consultar Gastos operativos</a>
                            </li>
                        </ul>
                    </li>
                    -->
                @endif


                @if (Session::get('sesiontipo') == 'Chofer')
                <ul class="nav" id="main-menu">
                    <li>
                        <a href="#"><i class="fa fa-road fa-fw"></i>Viajes<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{!! asset('altaSalidaViajes') !!}">Nuevo Viaje</a>
                                <a href="{!! asset('reporteViajes') !!}">Consultar Viajes</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-star fa-fw"></i>CRM<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">

                            <li>
                                <a href="#">Seguimiento de proyectos<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaSeg') !!}">Agregar nuevo proyecto</a>
                                    </li>
                                    <li>
                                        <a href="{!! asset('reporteSeguimientoVista') !!}">Consultar proyectos</a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="#"><i class="fa fa-list-alt fa-fw"></i>Actividades<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaActividades') !!}">Nueva actividad</a>
                                        <a href="{!! asset('reporteActividades') !!}">Consultar actividades</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <!-- Gastos Operativos -->
                <!--
                    <li>
                        <a href="#"><i class="fa fa-list-alt fa-fw"></i>Gastos operativos<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{{ route('GastosOperativos') }}">Consultar Gastos operativos</a>
                        </ul>
                    </li>
                    -->
                @endif


                <!-- menu de tecnico -->
                @if (Session::get('sesiontipo') == 'Tecnico')
                <ul class="nav" id="main-menu">
                    <li>
                        <a href="#"><i class="fa fa-road fa-fw"></i>Viajes<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{!! asset('altaSalidaViajes') !!}">Nuevo Viaje</a>
                                <a href="{!! asset('reporteViajes') !!}">Consultar Viajes</a>
                            </li>
                        </ul>
                    </li>

                    <li> <a href="#"><i class="fa fa-list-alt fa-fw"></i>Servicios<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="#"><i class="fa fa-list-alt fa-fw"></i>Opciones de Servicios<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaFacturas') !!}">Nuevo Servicio</a>
                                    </li>
                                    <!--<li>
                                    <a href="{!! asset('reporteFacturas') !!}">Consultar Servicios (old)</a>
                                    </li>-->
                                    <li>
                                        <a href="{!! asset('reporteFacturacion') !!}">Consultar Servicios</a>
                                    </li>
                                </ul>
                                <!--<li>
                                    <a href="{!! asset('reporteFacturasAsignadas') !!}"><i class="fa fa-list"></i>Consultar
                                        servicios asignados</a>
                                </li>-->
                        </ul>
                    </li>

                    <li> <a href="#"><i class="fa fa-wrench fa-fw"></i>Recepción de equipo<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{!! asset('reporteEquipos') !!}">Consultar
                                    entradas</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-star fa-fw"></i>CRM<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">

                            <li>
                                <a href="#">Seguimiento de proyectos<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaSeg') !!}">Agregar nuevo proyecto</a>
                                    </li>
                                    <li>
                                        <a href="{!! asset('reporteSeguimientoVista') !!}">Consultar proyectos</a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="#"><i class="fa fa-list-alt fa-fw"></i>Actividades<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaActividades') !!}">Nueva actividad</a>
                                        <a href="{!! asset('reporteActividades') !!}">Consultar actividades</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <!-- Gastos Operativos -->
                    <!--
                        <li>
                            <a href="#"><i class="fa fa-list-alt fa-fw"></i>Gastos operativos<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{{ route('GastosOperativos') }}">Consultar Gastos operativos</a>
                            </ul>
                        </li>
                        -->
                </ul>
                @endif
                <!-- fin menu de tecnico -->

                @if (Session::get('sesiontipo') == 'Compras')
                <ul class="nav" id="main-menu">
                <li>
                        <a href="#"><i class="fa fa-user fa-fw"></i>Usuarios<span
                                class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{!! asset('AltaUsuarios') !!}">Nuevo usuario</a>
                            </li>
                            <li>
                                <a href="{!! asset('ReporteUsuarios') !!}">Consulta de usuarios</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-map-marker fa-fw"></i>Proveedores<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{!! asset('altaProveedores') !!}">Nuevo proveedor</a>
                                <a href="{!! asset('reporteProveedores') !!}">Consultar proveedores</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-file-text"></i>Ordenes de compra<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{!! asset('altaOrdenCompra') !!}">Nueva Orden</a>
                                <a href="{!! asset('reporteOrdenesCompra') !!}">Consultar Ordenes</a>
                                <a href="{!! asset('reporteProductosEnOC') !!}">Productos en Ordenes</a>
                                <a href="{!! asset('reporteProductosEnNotaEntrada') !!}">Productos en Notas entrada</a>
                                <a href="{!! asset('reportePagosProveedores') !!}">Reporte de pagos a proveedores</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-bitcoin fa-fw"></i>Cuentas<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{!! asset('reporteCuentas') !!}">Consultar cuentas</a>
                            </li>
                        </ul>
                    </li>
                    <!-- <li>
                            <a href="{!! asset('reporteCuentasContables') !!}"><i class="fa fa-list-alt fa-fw"></i>Reporte cuentas contables</a>
                        </li> -->

                    <!-- <li>
                            <a href="#"><i class="fa fa-list-alt fa-fw"></i>Servicios<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#"><i class="fa fa-list-alt fa-fw"></i>Opciones de Servicios<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li>
                                            <a href="{!! asset('altaFacturas') !!}">Nuevo Servicio</a>
                                        </li>
                                        <li>
                                            <a href="{!! asset('reporteFacturas') !!}">Consultar Servicios</a>
                                        </li>
                                    </ul>
                                <li>
                                <a href="{!! asset('reporteFacturasAsignadas') !!}"><i class="fa fa-list"></i>Consultar servicios asignados</a>
                            </ul>
                        </li> -->
                    <li>
                        <a href="#"><i class="fa fa-list-alt fa-fw"></i>Servicios<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <!-- <a href="#"><i class="fa fa-list-alt fa-fw"></i>Opciones de Servicios<span class="fa arrow"></span></a>
                                        <ul class="nav nav-second-level"> -->
                                <!--<li>
                                            <a href="{!! asset('altaFacturas') !!}">Nuevo Servicio</a>
                                        </li> -->
                            <!--<li>
                                    <a href="{!! asset('reporteFacturas') !!}">Consultar Servicios (old)</a>
                            </li>-->
                            <li>
                                <a href="{!! asset('reporteFacturacion') !!}">Consultar Servicios</a>
                            </li>
                        </ul>
                    <li>

                    <li>
                        <a href="#"><i class="fa fa-wrench fa-fw"></i>Recepción de equipo<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{!! asset('altaEquipos') !!}">Nueva entrada</a>
                            </li>
                            <li>
                                <a href="{!! asset('reporteEquipos') !!}">Consultar
                                    entradas</a>
                            </li>
                            <li>
                                <a href="{!! asset('reportePorEmpresa') !!}">Consultar por empresa</a>
                            </li>
                            <li>
                                <a href="{!! asset('reportePorTaller') !!}">Consultar por taller</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-credit-card"></i>Catálogo de partes<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{!! asset('altaPartesVenta') !!}">Agregar parte</a>
                            </li>
                            <li>
                                <a href="{!! asset('reportePartesVenta') !!}">Consultar parte</a>
                            </li>
                            <li>
                                <a href="{!! asset('reporteSKUServicio') !!}">Reporte de SKU's en Servicios</a>
                            </li>
                            <li>
                                <a href="{!! asset('reporteSKUCotizacion') !!}">Reporte de SKU's en Cotizaciones</a>
                            </li>
                            <li>
                                <a href="{!! asset('skuVendido') !!}">Reporte de SKU's vendidos por servicio</a>
                            </li>
							<li>
								<a href="{!! asset('reporteTopVentas') !!}">Reporte Top de ventas</a>
							</li>
                            <li>
                                <a href="#"><i class="fa fa-cog"></i>Marcas Refacciones<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaMarcasRefaccion') !!}">Nueva Marca Refacciones</a>
                                    </li>
                                    <li>
                                        <a href="{!! asset('reporteMarcasRefaccion') !!}">Consultar Marcas Refacciones</a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="#"><i class="fa fa-th-large fa-fw"></i>Tipos de refacciones<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaTipoRef') !!}">Nuevo tipo</a>
                                    </li>
                                    <li>
                                        <a href="{!! asset('reporteTipoRef') !!}">Consultar tipos</a>
                                    </li>
                                    <!-- <li>
                                            <a href="#"><i class="fa fa-th fa-fw"></i>Refacciones<span
                                                    class="fa arrow"></span></a>
                                            <ul class="nav nav-second-level">
                                                <li>
                                                    <a href="{!! asset('altaRefacciones') !!}">Nueva refacción</a>
                                                </li>
                                                <li>
                                                    <a href="{!! asset('reporteRefacciones') !!}">Consultar
                                                        refacciones</a>
                                                </li>
                                            </ul>
                                        <li> -->
                                </ul>
                            </li>

                            <li>
                                <a href="#"><i class="fa fa-book"></i>Paquetes<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaPaquetes') !!}">Nuevo paquete</a>
                                    </li>
                                    <li>
                                        <a href="{!! asset('reportePaquetes') !!}">Consultar paquetes</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-cogs" aria-hidden="true"></i>Entradas a Almacén <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{!! asset('altaEntradaAlmacen') !!}">Nueva Entrada</a>
                            </li>
                            <li>
                                <a href="{!! asset('reporteEntradaAlmacen') !!}">Reporte Entradas a Almacén</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-sitemap"></i>Salidas de Almacén<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{!! asset('altaAsignacionProductosServicios') !!}">Asignar producto a servicio</a>
                            </li>
                            <li>
                                <a href="{!! asset('reporteAsignacionProductosServicios') !!}">Reporte productos en almacén y servicios</a>
                            </li>
                            <li>
                                <a href="{!! asset('reporteRastreoAsignacion_productos') !!}">Rastreo de asignación</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-list-ul fa-fw"></i>RFQ<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                            <li>
                                <a href="{!! asset('reporteRfqs') !!}">Consultar RFQ</a>
                        </ul>
                    <li>
                        <!-- <li>
                            <a href="#"><i class="fa fa-calculator"></i>Cotizaciones<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="{!! asset('cotizaciones') !!}">Cotización de equipos</a>
                                </li>
                                <li>
                                    <a href="{!! asset('cotizacionServicios') !!}">Cotización de servicios</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fa fa-list-alt fa-fw"></i>Opciones de Servicios<span
                                            class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li>
                                            <a href="{!! asset('altaServicioTecnico') !!}">Crear servicio</a>
                                        </li>
                                        <li>
                                            <a href="{!! asset('reporteServiciosTecnicos') !!}">Consultar servicios</a>
                                        </li>
                                    </ul>
                                <li>
                            </ul>
                        </li> -->

                </ul>
                @endif

                @if (Session::get('sesiontipo') == 'Almacen')
                <ul class="nav" id="main-menu">
                    <li>
                        <a href="#"><i class="fa fa-sitemap"></i>Catalogos<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="#"><i class="fa fa-tag fa-fw"></i>Marcas<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('AltaMarcas') !!}">Nueva Marca</a>
                                    </li>
                                    <li>
                                        <a href="{!! asset('reporteMarcas') !!}">Consultar Marcas</a>
                                    </li>
                                </ul>
                            </li>


                            <li>
                                <a href="#"><i class="fa fa-truck fa-fw"></i>Vehículos<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('AltaVehiculos') !!}">Nuevo Vehículo</a>
                                    </li>
                                    <li>
                                        <a href="{!! asset('ReporteVehiculos') !!}">Consultar Vehículos</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-folder fa-fw"></i>Archivos contables<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{!! asset('archivosContab') !!}">Archivos</a>
                            </li>                            
                        </ul>
                    </li>
                   
                    <li>
                        <a href="#"><i class="fa fa-list-alt fa-fw"></i>Servicios<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="#"><i class="fa fa-list-alt fa-fw"></i>Opciones de Servicios<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <!--<li>
                                    <a href="{!! asset('reporteFacturas') !!}">Consultar Servicios (old)</a>
                                    </li>-->
                                    <li>
                                        <a href="{!! asset('reporteFacturacion') !!}">Consultar Servicios</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>


                    <li>
                        <a href="#"><i class="fa fa-cogs" aria-hidden="true"></i>Entradas a Almacén <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{!! asset('altaEntradaAlmacen') !!}">Nueva Entrada</a>
                            </li>
                            <li>
                                <a href="{!! asset('reporteEntradaAlmacen') !!}">Reporte Entradas a Almacén</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-sitemap"></i>Salidas de Almacén<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{!! asset('altaAsignacionProductosServicios') !!}">Asignar producto a servicio</a>
                            </li>
                            <li>
                                <a href="{!! asset('reporteAsignacionProductosServicios') !!}">Reporte productos en almacén y servicios</a>
                            </li>
                            <li>
                                <a href="{!! asset('reporteRastreoAsignacion_productos') !!}">Rastreo de asignación</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-star fa-fw"></i>CRM<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">

                            <li>
                                <a href="#">Seguimiento de proyectos<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaSeg') !!}">Agregar nuevo proyecto</a>
                                    </li>
                                    <li>
                                        <a href="{!! asset('reporteSeguimientoVista') !!}">Consultar proyectos</a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="#"><i class="fa fa-list-alt fa-fw"></i>Actividades<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{!! asset('altaActividades') !!}">Nueva actividad</a>
                                        <a href="{!! asset('reporteActividades') !!}">Consultar actividades</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="{!! asset('reporteActividadesSeg') !!}">Reporte General de actividades</a>
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-road fa-fw"></i>Viajes<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{!! asset('altaSalidaViajes') !!}">Nuevo Viaje</a>
                                <a href="{!! asset('reporteViajes') !!}">Consultar Viajes</a>
                            </li>
                        </ul>
                    </li>                    

                </ul>
                @endif

                @if (Session::get('sesiontipo') == 'Contador')
                <ul class="nav" id="main-menu">
               
                    <li>
                        <a href="#"><i class="fa fa-folder fa-fw"></i>Archivos contables<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{!! asset('archivosContab') !!}">Archivos</a>
                            </li>
                            <li>
                                <a href="{!! asset('archivosUnicos') !!}">Archivos Únicos</a>
                            </li>                            
                        </ul>
                    </li>            

                </ul>
                @endif
  

            </div>
        </div>
    </nav>
    <!-- /. NAV SIDE  -->

    <div id="page-wrapper">
        <div class="header">
            <h1 class="page-header"></h1>
        </div>
        <!-- <div style="margin-top:-50px;"> -->
        <!--CONTENIDO DE PAGINA  -->@yield('contenido')

        <!-- </div>        -->
        <div id="page-inner">
            <!--NO TOCAR DE AQUI-->
            <div class="row"></div>
            <div class="row"></div>
            <div class="row"></div>
            <div class="row" style="display: none; position: absolute!important;">
                <div class="col-md-5">
                    <div class="panel panel-default">
                        <div class="panel-heading"></div>
                        <div class="panel-body">
                            <div id="morris-line-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="panel panel-default">
                        <div class="panel-heading"></div>
                        <div class="panel-body">
                            <div id="morris-bar-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="display: none; position: absolute;">
                <div class="col-md-9 col-sm-12 col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"></div>
                        <div class="panel-body" style="display: none; position: absolute;">
                            <div id="morris-area-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"></div>
                        <div class="panel-body">
                            <div id="morris-donut-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12"></div>
            </div>
            <!--HASTA ACA-->
            <footer>
                <p>
                    <!--Aqui el pie de pagina footer-->
                </p>
            </footer>
        </div>
        <!-- /. PAGE INNER  -->
    </div>
    <!-- /. PAGE WRAPPER  -->
    <!-- /. WRAPPER  -->
    <!-- JS Scripts-->

    <!-- Bootstrap Js -->
    <script src="{!! asset('assets/js/bootstrap.min.js') !!}"></script>
    <script src="{!! asset('assets/js/jquery.anexgrid.js') !!}"></script>
    <script src="{!! asset('assets/js/zinggrid.min.js') !!}"></script>

    <!-- Metis Menu Js -->
    <script src="{!! asset('assets/js/jquery.metisMenu.js') !!}"></script>
    <!-- Morris Chart Js -->
    <script src="{!! asset('assets/js/morris/raphael-2.1.0.min.js') !!}"></script>
    <script src="{!! asset('assets/js/morris/morris.js') !!}"></script>
    <script src="{!! asset('assets/js/easypiechart.js') !!}"></script>
    <script src="{!! asset('assets/js/easypiechart-data.js') !!}"></script>
    <script src="{!! asset('assets/js/Lightweight-Chart/jquery.chart.js') !!}"></script>
    <!-- Custom Js -->
    <script src="{!! asset('assets/js/custom-scripts.js') !!}"></script>
    <!-- Chart Js -->
    <script type="text/javascript" src="{!! asset('assets/js/Chart.min.js') !!}"></script>
    <!-- <script type="text/javascript" src="{!! asset('assets/js/chartjs.js') !!}"></script> -->
    <script src="{!! asset('assets/js/chosen.jquery.js') !!}"></script>
    <script src="{!! asset('assets/js/init.js') !!}"></script>

    <!-- DataTables  & Plugins -->
    <script src="{!! asset('dataTable/datatables/jquery.dataTables.min.js') !!}"></script>
    <script src="{!! asset('dataTable/datatables-bs4/js/dataTables.bootstrap.min.js') !!}"></script>
    <script src="{!! asset('dataTable/datatables-responsive/js/dataTables.responsive.min.js') !!}"></script>
    <script src="{!! asset('dataTable/datatables-responsive/js/responsive.bootstrap.min.js') !!}"></script>
    <script src="{!! asset('dataTable/datatables-buttons/js/dataTables.buttons.min.js') !!}"></script>
    <script src="{!! asset('dataTable/datatables-buttons/js/buttons.bootstrap.min.js') !!}"></script>
    <script src="{!! asset('dataTable/datatables-buttons/js/buttons.html5.min.js') !!}"></script>
    <script src="{!! asset('dataTable/datatables-buttons/js/buttons.print.min.js') !!}"></script>
    <script src="{!! asset('dataTable/datatables-buttons/js/buttons.colVis.min.js') !!}"></script>
    <script src="{!! asset('dataTable/jszip/jszip.min.js') !!}"></script>
    <script src="{!! asset('dataTable/pdfmake/pdfmake.min.js') !!}"></script>
    <script src="{!! asset('dataTable/pdfmake/vfs_fonts.js') !!}"></script>
    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.20/dist/sweetalert2.all.min.js"></script>
</body>

</html>