<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="UTF-8">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>
	<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="{{ asset("login-design/images/icons/favicon.ico") }}" />
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset("login-design/vendor/bootstrap/css/bootstrap.min.css") }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset("login-design/fonts/font-awesome-4.7.0/css/font-awesome.min.css") }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset("login-design/fonts/Linearicons-Free-v1.0.0/icon-font.min.css") }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset("login-design/vendor/animate/animate.css") }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset("login-design/vendor/css-hamburgers/hamburgers.min.css") }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset("login-design/vendor/select2/select2.min.css") }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset("login-design/css/util.css") }}">
	<link rel="stylesheet" type="text/css" href="{{ asset("login-design/css/main.css") }}">
	<!--===============================================================================================-->
</head>

<body>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-l-50 p-r-50 p-t-77 p-b-30">
				{!! Form::open(['route' => 'auth', 'method'=>'GET', 'class'=>"login100-form validate-form"]) !!}
					@csrf
					<span class="login100-form-title p-b-55">
						<img src="{{ asset('assets/logo.png') }}" width="200" height="auto" alt="" srcset="">
					</span>

					@error('auth')
					<div class="wrap-input100  text-center m-b-5">
						<span class="error " style="" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					</div>
					@enderror


					<div class="wrap-input100  m-b-10">
						<input class="input100  @error('email') is-invalid @enderror" type="email" name="email"
							placeholder="Correo Electronico" value="{{ old('email') }}" autocomplete="email" autofocus>
						<span class="focus-input100"></span>

						<span class="symbol-input100">
							<span class="lnr lnr-envelope"></span>
						</span>
					</div>

					@error('email')
					<div class="wrap-input100  m-b-5">
						<span class="error" style="" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					</div>
					@enderror


					<div class="wrap-input100 m-b-16 form-group">
						<input class="input100 @error('password') is-invalid @enderror" type="password" name="password"
							placeholder="Contraseña">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<span class="lnr lnr-lock"></span>
						</span>
					</div>

					@error('password')
					<div class="wrap-input100  m-b-5">
						<span class="error" style="" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					</div>
					@enderror


					<div class="contact100-form-checkbox m-l-4">
						<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember"
							{{ old('remember') ? 'checked' : '' }}>
						<label class="label-checkbox100" for="ckb1">
							Recuerdame
						</label>
					</div>

					<div class="container-login100-form-btn p-t-25">
						<button class="login100-form-btn">
							Iniciar sesion
						</button>
					</div>

					{{-- <div class="text-center w-full p-t-42 p-b-22">
						<span class="txt1">
							Or login with
						</span>
					</div>

					<a href="#" class="btn-face m-b-10">
						<i class="fa fa-facebook-official"></i>
						Facebook
					</a>

					<a href="#" class="btn-google m-b-10">
						<img src="{{ asset( "login-design/images/icons/icon-google.png") }}" alt="GOOGLE">
					Google
					</a>

					<div class="text-center w-full p-t-115">
						<span class="txt1">
							Not a member?
						</span>

						<a class="txt1 bo1 hov1" href="#">
							Sign up now
						</a>
					</div> --}}
				</form>
			</div>
		</div>
	</div>




	<!--===============================================================================================-->
	<script src="{{ asset("_login/vendor/jquery/jquery-3.2.1.min.js") }}"></script>
	<!--===============================================================================================-->
	<script src="{{ asset("_login/vendor/bootstrap/js/popper.js") }}"></script>
	<script src="{{ asset("_login/vendor/bootstrap/js/bootstrap.min.js") }}"></script>
	<!--===============================================================================================-->
	<script src="{{ asset("_login/vendor/select2/select2.min.js") }}"></script>
	<!--===============================================================================================-->
	<script src="{{ asset("_login/js/main.js") }}"></script>

</body>

</html>
