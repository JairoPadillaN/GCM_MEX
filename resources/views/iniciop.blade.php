<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Fashi Template">
    <meta name="keywords" content="Fashi, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>German Control Motion</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{!! asset('estilos/css/bootstrap.min.css')!!}" type="text/css">
    <link rel="stylesheet" href="{!! asset('estilos/css/font-awesome.min.css')!!}" type="text/css">
    <link rel="stylesheet" href="{!! asset('estilos/css/themify-icons.css')!!}" type="text/css">
    <link rel="stylesheet" href="{!! asset('estilos/css/elegant-icons.css')!!}" type="text/css">
    <link rel="stylesheet" href="{!! asset('estilos/css/owl.carousel.min.css')!!}" type="text/css">
    <link rel="stylesheet" href="{!! asset('estilos/css/nice-select.css')!!}" type="text/css">
    <link rel="stylesheet" href="{!! asset('estilos/css/jquery-ui.min.css')!!}" type="text/css">
    <link rel="stylesheet" href="{!! asset('estilos/css/slicknav.min.css')!!}" type="text/css">
    <link rel="stylesheet" href="{!! asset('estilos/css/style.css')!!}" type="text/css">
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Header Section Begin -->
    <header class="header-section">
        <div class="header-top">
            <div class="container">
                <div class="ht-left">
                    <div class="mail-service">
                        <i class=" fa fa-envelope"></i>
                        hello.colorlib@gmail.com
                    </div>
                    <div class="phone-service">
                        <i class=" fa fa-phone"></i>
                        +65 11.188.888
                    </div>
                </div>
                <div class="ht-right">
                    <a href="{!! asset('login')!!}" class="login-panel"><i class="fa fa-user"></i>Login</a>
                    <div class="lan-selector">
                        <select class="language_drop" name="countries" id="countries" style="width:300px;">
                            <option value='yt' data-image="estilos/img/flag-1.jpg" data-imagecss="flag yt"
                                data-title="English">English</option>
                            <option value='yu' data-image="estilos/img/flag-2.jpg" data-imagecss="flag yu"
                                data-title="Bangladesh">German </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="nav-item">
            <div class="container">
                <div class="nav-depart">
                    <div class="depart-btn">
                        
                        <span>Automatizaciòn</span>
                        
                    </div>
                </div>
                <nav class="nav-menu mobile-menu">
                    <ul>
                        <!--<li class="active"><a href="./index.html">Inicio</a></li>-->
                        <li><a href="{!! asset('ventaRefacciones')!!}">Venta de refacciones</a></li>
                        <li><a href="{!! asset('solucionesHusky')!!}">Soluciones Husky</a>
                        </li>
                        <li><a href="{!! asset('productos')!!}">Productos</a></li>
                        <li><a href="{!! asset('contacto')!!}">Contacto</a></li>
                    </ul>
                </nav>
                <div id="mobile-menu-wrap"></div>
            </div>
        </div>
    </header>
 
   @yield('conten')
    

    

    <!-- Footer Section Begin -->
    <footer class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="footer-left">
                        <div class="footer-logo">
                            <a href="#"><img src="img/footer-logo.png" alt=""></a>
                        </div>
                        <ul>
                            <li>Address: 60-49 Road 11378 New York</li>
                            <li>Phone: +65 11.188.888</li>
                            <li>Email: hello.colorlib@gmail.com</li>
                        </ul>
                        <div class="footer-social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-pinterest"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1">
                    <div class="footer-widget">
                        <h5>Information</h5>
                        <ul>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Checkout</a></li>
                            <li><a href="#">Contact</a></li>
                            <li><a href="#">Serivius</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="footer-widget">
                        <h5>My Account</h5>
                        <ul>
                            <li><a href="#">My Account</a></li>
                            <li><a href="#">Contact</a></li>
                            <li><a href="#">Shopping Cart</a></li>
                            <li><a href="#">Shop</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="newslatter-item">
                        <h5>Join Our Newsletter Now</h5>
                        <p>Get E-mail updates about our latest shop and special offers.</p>
                        <form action="#" class="subscribe-form">
                            <input type="text" placeholder="Enter Your Mail">
                            <button type="button">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-reserved">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="copyright-text">
                           Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. 
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
 Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. 
                        </div>
                        <div class="payment-pic">
                            <img src="img/payment-method.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="{!! asset('estilos/js/jquery-3.3.1.min.js')!!}"></script>
    <script src="{!! asset('estilos/js/bootstrap.min.js')!!}"></script>
    <script src="{!! asset('estilos/js/jquery-ui.min.js')!!}"></script>
    <script src="{!! asset('estilos/js/jquery.countdown.min.js')!!}"></script>
    <script src="{!! asset('estilos/js/jquery.nice-select.min.js')!!}"></script>
    <script src="{!! asset('estilos/js/jquery.zoom.min.js')!!}"></script>
    <script src="{!! asset('estilos/js/jquery.dd.min.js')!!}"></script>
    <script src="{!! asset('estilos/js/jquery.slicknav.js')!!}"></script>
    <script src="{!! asset('estilos/js/owl.carousel.min.js')!!}"></script>
    <script src="{!! asset('estilos/js/main.js')!!}"></script>
</body>

</html>
