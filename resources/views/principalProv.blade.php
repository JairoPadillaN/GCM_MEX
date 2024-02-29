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
    <title>CM GROUP US</title>
    <!-- Tablas con filtros en las columnas -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.dataTables.min.css">
    <!--  -->
    <link rel="icon" type="image/png" href="{!! asset('estiloslogin/images/icons/favicon.ico')!!}" />
    <!-- Bootstrap Styles-->
    <link href="{!! asset('assets/css/bootstrap.css')!!}" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <!-- <link href="/your-path-to-fontawesome/css/fontawesome.css" rel="stylesheet"> -->
    <link href="{!! asset('assets/css/font-awesome.css')!!}" rel="stylesheet" />
    <!-- Morris Chart Styles-->
    <link href="{!! asset('assets/js/morris/morris-0.4.3.min.css')!!}" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="{!! asset('assets/css/custom-styles.css')!!}" rel="stylesheet" />
    <link href="{!! asset('assets/css/menu.css')!!}" rel="stylesheet" />
    <link href="{!! asset('assets/css/chosen.css')!!}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>

    <!-- Google Fonts-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' /> -->
    <link rel="stylesheet" href="{!! asset('assets/js/Lightweight-Chart/cssCharts.css')!!}">
    <!-- jQuery Js -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<!-- Para validaciones a nivel cliente -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <!-- sweet alert para alertas con estilo -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- DataTables -->
    <link rel="stylesheet" href="{!! asset('dataTable/datatables-bs4/css/dataTables.bootstrap.min.css')!!}">
    <link rel="stylesheet" href="{!! asset('dataTable/datatables-responsive/css/responsive.bootstrap.min.css')!!}">
    <link rel="stylesheet" href="{!! asset('dataTable/datatables-buttons/css/buttons.bootstrap.min.css')!!}">

    <style>
        /*estilos para la tabla*/
        table th {
            /* background-color: #659CCA; */
            text-align:center;
            /* color: black; */
        }
    </style>   
</head>
<body>
    <nav class="navbar navbar-default top-navbar" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse"
                style="width:38px;">
                <span class="sr-only" style="width:16px;">Toggle navigation</span>
                <span class="icon-bar" style="width:16px;"></span>
                <span class="icon-bar" style="width:16px;"></span>
                <span class="icon-bar" style="width:16px;"></span>
            </button>


            <a class="navbar-brand" href=""><img src="{!! asset('archivos/logoparasistema.PNG')!!}"
                    style="position: absolute; top:0; left:0; height:60px; width:260px;"></a>
            <div id="sideNav" href="">
                <i class="fa fa-bars icon"></i>
            </div>
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                style="position: absolute;height:34px; width:38px;margin-left:5px;" data-target=".navbar-ex1-collapse">
                <ul class="nav navbar-top-links" style="margin-top:-23px; margin-left:-20px;">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                            <i class="fa fa-user fa-fw"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user" style="margin-left:-270%;margin-top:-18%;">
                            <li><a><i class="fa fa-user fa-fw"></i>{!! Session::get('sesionnameprov')!!} {!!
                                    Session::get('sesionsegnom')!!}
                                    {!! Session::get('sesionap')!!}</a>
                            </li>
                            <li><a href="{!! URL::action('loginProv@cerrarsesionprov')!!}"><i
                                        class="fa fa-sign-out fa-fw"></i>
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
                        <li><a href="#"><i class="fa fa-user fa-fw"></i>{!! Session::get('sesionnameprov')!!} {!!
                                Session::get('sesionsegnom')!!}
                                {!! Session::get('sesionap')!!}</a>
                        </li>
                        <li><a href="{!! URL::action('loginProv@cerrarsesionprov')!!}"><i class="fa fa-sign-out fa-fw"></i>
                                Salir</a>
                        </li>
                    </ul>
                </li>
                <!-- /.dropdown -->
            </ul>
    </nav><!-- Grid column -->
    </nav><!-- Grid column -->
    <nav class="navbar-default navbar-side" role="navigation" style="margin-top:-19px;">
        <div class="sidebar-collapse">
            <!-- Exaple 1 -->
            <div class="card example-1 scrollbar-ripe-malinka">
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{!! asset('inicioProveedor')!!}">Mis ordenes de compra</a>
                    </li>
                    <li>
                        <a href="{!! asset('cuentasProveedor')!!}">Cuentas bancarias</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- /. NAV SIDE  -->

    <div id="page-wrapper">
        <div class="header">
            <h1 class="page-header"></h1>
        </div>
        <!-- <div style="margin-top:-50px;"> -->
        <!--CONTENIDO DE PAGINA  -->@yield('contenidoProv')

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
    <script src="{!! asset('assets/js/bootstrap.min.js')!!}"></script>
    <script src="{!! asset('assets/js/jquery.anexgrid.js')!!}"></script>
    
    <!-- Metis Menu Js -->
    <script src="{!! asset('assets/js/jquery.metisMenu.js')!!}"></script>
    <!-- Morris Chart Js -->
    <script src="{!! asset('assets/js/morris/raphael-2.1.0.min.js')!!}"></script>
    <script src="{!! asset('assets/js/morris/morris.js')!!}"></script>
    <script src="{!! asset('assets/js/easypiechart.js')!!}"></script>
    <script src="{!! asset('assets/js/easypiechart-data.js')!!}"></script>
    <script src="{!! asset('assets/js/Lightweight-Chart/jquery.chart.js')!!}"></script>
    <!-- Custom Js -->
    <script src="{!! asset('assets/js/custom-scripts.js')!!}"></script>
    <!-- Chart Js -->
    <script type="text/javascript" src="{!! asset('assets/js/Chart.min.js')!!}"></script>
    <!-- <script type="text/javascript" src="{!! asset('assets/js/chartjs.js')!!}"></script> -->
    <script src="{!! asset('assets/js/chosen.jquery.js')!!}"></script>
    <script src="{!! asset('assets/js/init.js')!!}"></script>

    <!-- DataTables  & Plugins -->
    <script src="{!! asset('dataTable/datatables/jquery.dataTables.min.js')!!}"></script>
    <script src="{!! asset('dataTable/datatables-bs4/js/dataTables.bootstrap.min.js')!!}"></script>
    <script src="{!! asset('dataTable/datatables-responsive/js/dataTables.responsive.min.js')!!}"></script>
    <script src="{!! asset('dataTable/datatables-responsive/js/responsive.bootstrap.min.js')!!}"></script>
    <script src="{!! asset('dataTable/datatables-buttons/js/dataTables.buttons.min.js')!!}"></script>
    <script src="{!! asset('dataTable/datatables-buttons/js/buttons.bootstrap.min.js')!!}"></script>
    <script src="{!! asset('dataTable/datatables-buttons/js/buttons.html5.min.js')!!}"></script>
    <script src="{!! asset('dataTable/datatables-buttons/js/buttons.print.min.js')!!}"></script>
    <script src="{!! asset('dataTable/datatables-buttons/js/buttons.colVis.min.js')!!}"></script>
    <script src="{!! asset('dataTable/jszip/jszip.min.js')!!}"></script>
    <script src="{!! asset('dataTable/pdfmake/pdfmake.min.js')!!}"></script>
    <script src="{!! asset('dataTable/pdfmake/vfs_fonts.js')!!}"></script>

</body>

</html>