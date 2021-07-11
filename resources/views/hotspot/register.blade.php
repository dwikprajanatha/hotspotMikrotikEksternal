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

				@if($request->session()->has('error'))
					<div class="alert alert-danger" role="alert">
						{{$request->session('error')}}
					</div>
				@endif

				<form class="login100-form validate-form" action="{{route('hotspot.register')}}" method="POST">
					
					{{ csrf_field() }}

					<span class="login100-form-title">
						Registrasi Akun Hotspot
					</span>

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

                    <hr class="border-top: 3px solid #bbb">
                    

					<div class="text-center p-t-136">

					</div>
				</form>
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

</body>
</html>