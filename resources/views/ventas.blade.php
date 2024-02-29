<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>German Control Motion</title>

    <!-- Bootstrap Core CSS -->
    <link href="{!! asset('css/bootstrap.min.css')!!}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{!! asset('css/small-business.css')!!}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href='http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css' rel="stylesheet"
        type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Great+Vibes' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900' rel='stylesheet' type='text/css'>
    <!-- jQuery Js -->
    <script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>

</head>

<body>

    <nav class="navbar navbar-default" role="navigation" style="background-color:#3576AB">
        <!-- El logotipo y el icono que despliega el menú se agrupan
       para mostrarlos mejor en los dispositivos móviles -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <div style="text-align:left;">
                <ul class="nav navbar-nav">
                    <li><a href="{!! asset('automatizacion')!!}" class="btn btn-danger"
                            style="background-color:#3576AB; border-color:#3576AB; color:#FFFFFF">Automatización</a>
                    </li>
                    <li><a href="{!! asset('ventas')!!}" class="btn btn-danger"
                            style="background-color:#3576AB; border-color:#3576AB; color:#FFFFFF">Venta
                            de refacciones</a></li>
                    <li><a href="{!! asset('husky')!!}" class="btn btn-danger"
                            style="background-color:#3576AB; border-color:#3576AB; color:#FFFFFF">Soluciones
                            husky</a></li>
                    <li><a href="{!! asset('productos2')!!}" class="btn btn-danger"
                            style="background-color:#3576AB; border-color:#3576AB; color:#FFFFFF">Nuestros
                            productos</a></li>
                    <li><a href="{!! asset('portada')!!}" class="btn btn-danger"
                            style="background-color:#3576AB; border-color:#3576AB; color:#FFFFFF">Contáctanos</a>
                    </li>
                    <li><a href="{!! asset('login')!!}" class="btn btn-danger"
                            style="background-color:#3576AB; border-color:#3576AB; color:#FFFFFF">Ingresar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <header class="intro-header" style="background-image: url('public/archivos/interior.jpeg');">
        <div class="container overlay">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading" style="">

                    </div>
                </div>
            </div>
        </div>
    </header>

    <script language="JavaScript" src="https://dunsregistered.dnb.com" type="text/javascript"></script>
    <script language="JavaScript" src="http://dunsregistered.dnb.com/QRcode.js" type="text/javascript"></script>

    <script src="{!! asset('js/jquery.js')!!}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{!! asset('js/bootstrap.min.js')!!}"></script>

    <!-- Custom Theme JavaScript -->
    <script src="{!! asset('js/small-business.min.js')!!}"></script>

    <!-- Smooth Scroll -->
    <script src="{!! asset('js/SmoothScroll.js')!!}"></script>


    <script src="{!! asset('assets/js/easypiechart.js')!!}"></script>
    <script src="{!! asset('assets/js/easypiechart-data.js')!!}"></script>

    <script src="{!! asset('assets/js/Lightweight-Chart/jquery.chart.js')!!}"></script>

    <!-- Custom Js -->
    <script src="{!! asset('assets/js/custom-scripts.js')!!}"></script>


    <!-- Chart Js -->
    <script type="text/javascript" src="{!! asset('assets/js/Chart.min.js')!!}"></script>
    <script type="text/javascript" src="{!! asset('assets/js/chartjs.js')!!}"></script>

</body>

</html>