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

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="{{asset('login/images/logo-punggul.png')}}" alt="IMG">
				</div>

				<form id="formLogin" class="login100-form validate-form" name="login" action="{{isset($request['link-login-only']) ? $request['link-login-only'] : '#' }}" method="post">
					<span class="login100-form-title">
						Login Hotspot Desa Punggul
					</span>

					@if($success = Session::get('userSuccess'))
						<div class="alert alert-success" role="alert">
							{{ $success }}
						</div>
						{{ session()->forget('userSuccess') }}
					@endif
					
					@if(isset($request['error']) &&  $request['error'] != null)
					<div class="alert alert-danger" role="alert">
						<p class="text-danger text-center">{{$request['error']}}</p>
					</div>
					@endif

					<div class="alert alert-info" role="alert">
						<p class="text-center">
							<b>PENGUMUMAN</b>, bagi yang sudah membuat akun tapi usernamenya berisi spasi, <b> harap membuat akun kembali </b>. Terima Kasih
						</p>
					</div>

					<div id="errorKategori"></div>


				
					<div class="wrap-input100">
						<input class="input100" type="text" name="username" placeholder="Username" value="{{old('username')}}">
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
						<button class="login100-form-btn" id="submitButton">
							Masuk Hotspot
						</button>
					</div>

					<div class="text-center p-t-14">
						<a class="txt3" href="{{route('hotspot.forgot.view')}}">
							Lupa  Password?
						</a>
					</div>

					<div class="text-center p-t-50">
						<span class="txt1">
							Belum Punya akun?
						</span>
						<br>

						{{ session()->put('request', $request) }}

						<a class="txt2" href="{{route('hotspot.register.view')}}">
							Buat Akun disini
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
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

	<script>

		$(document).ready(function(){

			var base_url = window.location.origin;

			$('#submitButton').click(function(){

				// var date = new Date();
				var date = new Date(new Date().toLocaleString("en-US", {timeZone: "Asia/Makassar"}));
				console.log(date);
				var input_user = $('#formLogin').find('input[name="username"]').val();
				console.log(input_user);
				console.log(base_url + '/api/user/cekKategori');

				$.ajax({
					url: base_url + '/api/user/cekKategori',
					type: 'GET',
					async: false,
					dataType : "JSON",
					headers: {'Accept': 'application/json'},
					data: { 'username': input_user.toString() },
					success: function(response){
							var result = response.data;
							console.log(result);

							if( result.isDeleted == 0 ){

								if( result.kategori.toString() == 'Anak' && date.getHours() > 20){

									$('#errorKategori').append('<div class="alert alert-danger" role="alert"><p class="text-center"><b>ERROR</b>, Sudah lewat batas waktu login untuk user anak anak. Terima Kasih</p></div>');
									$('#formLogin').submit(function(e){
										e.preventDefault();
									});

								} else {

									console.log(response);
									$('#formLogin').submit();
									
								}

							} else {

								$('#errorKategori').append('<div class="alert alert-danger" role="alert"><p class="text-center"><b>ERROR</b>, Akun anda di Non-Aktifkan oleh Admin Jaringan</p></div>');
								$('#formLogin').submit(function(e){
									e.preventDefault();
								});

							}

								
						},
					error: function(jqXHR, textStatus, errorThrown){
							console.log(textStatus);
							console.log(errorThrown);
					}
				});

			});

		});

	</script>

</body>
</html>