<!DOCTYPE html>
<html lang="en">
<head>
	<title>Buat Akun Baru</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{asset('login/images/icons/favicon.ico')}}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('login/vendor/bootstrap/css/bootstrap.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('login/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('login/vendor/animate/animate.css')}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset('login/vendor/css-hamburgers/hamburgers.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('login/vendor/select2/select2.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('login/css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('login/css/main.css')}}">
<!--===============================================================================================-->
</head>
<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="{{asset('login/images/Lambang_Kabupaten_Badung.png')}}" alt="IMG">
				</div>

				<div class="login100-form validate-form">


					<form action="{{route('hotspot.register')}}" method="POST">
					
						{{ csrf_field() }}
	
	
						<span class="login100-form-title">
							Registrasi Akun Hotspot
						</span>
	
						@if($error = Session::get('error'))
							<div class="alert alert-danger" role="alert">
								{{ $error }}
							</div>
						@endif
	
						@php
							if(session()->has('request')){
								$request = session()->get('request');
							} else {
								$request = ['mac' => '00:00:00:00:00', 'ip' => '0.0.0.0'];
							}
							
						@endphp
	
						<input type="hidden" name="ip" value="{{$request['ip']}}">
						<input type="hidden" name="mac" value="{{$request['mac']}}">
	
						<div class="wrap-input100">
							<input class="input100" type="text" name="nik" placeholder="NIK">
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-id-card-o" aria-hidden="true"></i>
							</span>
						</div>
	
						<div class="wrap-input100">
							<input class="input100" type="text" name="username" placeholder="Username">
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-user" aria-hidden="true"></i>
							</span>
						</div>
	
						<div class="wrap-input100">
							<input class="input100" type="password" name="password" placeholder="Password">
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-lock" aria-hidden="true"></i>
							</span>
						</div>
						
						<div class="container-login100-form-btn">
							<button class="login100-form-btn" type="submit">
								Buat Akun
							</button>
						</div>
	
					</form>

					<hr class="border-top: 3px solid #bbb">
							<span class="socialmedia-text">
								Bukan warga desa Punggul? <br> Login pakai Social Media aja!
							</span>
							
							<a href="{{url('auth/facebook')}}">
								<div class="container-login-social-form-btn">
									<button id="facebook-btn" class="login-form-btn-facebook">
										Login with Facebook
									</button>
								</div>
							</a>
							
							<a href="{{url('auth/google')}}">
								<div class="container-login-social-form-btn">
									<button id="google-btn" class="login-form-btn-google">
										Login with Google
									</button>
								</div>
							</a>

	
						<div class="text-center p-t-136">

				</div>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="{{asset('login/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('login/vendor/bootstrap/js/popper.js')}}"></script>
	<script src="{{asset('login/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('login/vendor/select2/select2.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('login/vendor/tilt/tilt.jquery.min.js')}}"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="{{asset('login/js/main.js')}}"></script>


	<script type="text/javascript">

		function facebook() {
			location.href = "<?= url('auth/facebook')?>";
		};

		function google() {
			location.href = "<?= url('auth/google')?>";
		};

	</script>

</body>
</html>