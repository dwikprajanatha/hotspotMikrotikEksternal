<!DOCTYPE html>
<html lang="en">
<head>
	<title>Masuk Wifi</title>
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

	<!-- HASH MD5 PASSWORD  -->
	<script src="{{asset('login/js/md5.js')}}"></script>
	<script type="text/javascript">

	    function doLogin() {
			var chap_id = "<?php $request['chap-id'] ?>";
			var chap_challenge = "<?php $request['chap-challenge'] ?>";
			var hash_pass = hexMD5(chap_id + document.login.password.value + chap_challenge);
			document.login.password.value = hash_pass;
			console.log(hash_pass);
			document.login.submit();
			return false;
	    }

	</script>


	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="{{asset('login/images/Lambang_Kabupaten_Badung.png')}}" alt="IMG">
				</div>

				<form class="login100-form validate-form" name="login" action="{{$request['link-login-only']}}" onsubmit="return doLogin()">
					<span class="login100-form-title">
						Login Hotspot Badung
					</span>
					
					@if($request['error'] != null)
					<div class="alert alert-danger" role="alert">
						<p class="text-danger text-center">{{$request['error']}}</p>
					</div>
					@endif
				
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
							Masuk Hotspot
						</button>
					</div>
				</form>

				<div class="text-center p-t-12">
					<span class="txt1">
						Lupa 
					</span>
					<a class="txt2" href="#">
						Username / Password?
					</a>
				</div>

				<div class="text-center p-t-136">
					<span class="txt1">
						Belum Punya akun?
					</span>
					<a class="txt2" href="{{route('hotspot.create')}}">
						Buat Akun disini
						<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
					</a>
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

</body>
</html>