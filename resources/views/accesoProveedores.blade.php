<!DOCTYPE html>
<html lang="en">
<head>
	<title>Inicio de sesión </title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="{!! asset('estiloslogin/images/icons/favicon.ico')!!}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{!! asset('estiloslogin/vendor/bootstrap/css/bootstrap.min.css')!!}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{!! asset('estiloslogin/fonts/font-awesome-4.7.0/css/font-awesome.min.css')!!}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{!! asset('estiloslogin/fonts/Linearicons-Free-v1.0.0/icon-font.min.css')!!}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{!! asset('estiloslogin/vendor/animate/animate.css')!!}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{!! asset('estiloslogin/vendor/css-hamburgers/hamburgers.min.css')!!}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{!! asset('estiloslogin/vendor/animsition/css/animsition.min.css')!!}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{!! asset('estiloslogin/vendor/select2/select2.min.css')!!}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{!! asset('estiloslogin/vendor/daterangepicker/daterangepicker.css')!!}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{!! asset('estiloslogin/css/util.css')!!}">
	<link rel="stylesheet" type="text/css" href="{!! asset('estiloslogin/css/main.css')!!}">
<!--===============================================================================================-->
</head>
<body style="background-color: #666666;">

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form action="{{route('validauserProv')}}" method ='POST' class="login100-form validate-form">
					{{csrf_field()}}
					<span class="login100-form-title p-b-43">
						Inicio de Sesión
					</span>


					<div class="wrap-input100 validate-input" data-validate = "">
						<input class="input100" type="text" name="correoProveedor" value="{{old('correoProveedor')}}">
						<span class="focus-input100"></span>
						<span class="label-input100">Email</span>
					</div>


					<div class="wrap-input100 validate-input" data-validate="Password is required">
						<input class="input100" type="password" name="passwordProv" value="{{old('passwordProv')}}">
						<span class="focus-input100"></span>
						<span class="label-input100">Password</span>
					</div>

					<!-- <div class="flex-sb-m w-full p-t-3 p-b-32">
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Remember me
							</label>
						</div>

						<div>
							<a href="#" class="txt1">
								Forgot Password?
							</a>
						</div>
					</div> -->


					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Entrar
						</button>
					</div>

					<!-- <div class="text-center p-t-46 p-b-20">
						<span class="txt2">
							or sign up using
						</span>
					</div>

					<div class="login100-form-social flex-c-m">
						<a href="#" class="login100-form-social-item flex-c-m bg1 m-r-5">
							<i class="fa fa-facebook-f" aria-hidden="true"></i>
						</a>

						<a href="#" class="login100-form-social-item flex-c-m bg2 m-r-5">
							<i class="fa fa-twitter" aria-hidden="true"></i>
						</a>
					</div> -->
				</form>

				<div class="login100-more text-center" style="background-color: #FFFFFF">

					<img src="{!! asset('estiloslogin/images/logoiniciosesion.PNG')!!}" style="margin-top:14% !important;  width:789px">

				</div>
			</div>
		</div>
	</div>



	@if (Session::has('error'))
 <div>{{Session::get('error')}}</div>
 <script>
 	alert("{{Session::get('error')}}");

 </script>
 @endif



<!--===============================================================================================-->
	<script src="{!! asset('estiloslogin/vendor/jquery/jquery-3.2.1.min.js')!!}"></script>
<!--===============================================================================================-->
	<script src="{!! asset('estiloslogin/vendor/animsition/js/animsition.min.js')!!}"></script>
<!--===============================================================================================-->
	<script src="{!! asset('estiloslogin/vendor/bootstrap/js/popper.js')!!}"></script>
	<script src="{!! asset('estiloslogin/vendor/bootstrap/js/bootstrap.min.js')!!}"></script>
<!--===============================================================================================-->
	<script src="{!! asset('estiloslogin/vendor/select2/select2.min.js')!!}"></script>
<!--===============================================================================================-->
	<script src="{!! asset('estiloslogin/vendor/daterangepicker/moment.min.js')!!}"></script>
	<script src="{!! asset('estiloslogin/vendor/daterangepicker/daterangepicker.js')!!}"></script>
<!--===============================================================================================-->
	<script src="{!! asset('estiloslogin/vendor/countdowntime/countdowntime.js')!!}"></script>
<!--===============================================================================================-->
	<script src="{!! asset('estiloslogin/js/main.js"></script>

</body>
</html>
