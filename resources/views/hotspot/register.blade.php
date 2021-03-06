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
{{-- VALIDATOR --}}
	<script src="{{asset('login/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
	<script src="{{asset('login/vendor/validate/jquery.validate.min.js')}}"></script>
	<script src="{{asset('login/vendor/validate/additional-methods.min.js')}}"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$("#formDaftar").validate({
			errorElement: "p",
			errorClass: "text-danger",
			rules: {
				nik: {
					required: true,
					number: true,
					nowhitespace:true,
				},
				username: {
					required: true,
					nowhitespace:true,
				},
				password: {
					required: true,
					minlength: 8,
					nowhitespace:true,
				}
			}, 

			messages: {
				nik: {
					required: "Masukkan NIK anda",
					number: "Masukkan Angka saja",
					nowhitespace : "Mohon tidak menggunakan spasi",
				},
				username: {
					required : "Masukkan username anda",
					nowhitespace : "Mohon tidak menggunakan spasi",
				},
				password: {
					required: "Masukkan Password anda",
					minlength: "Password Minimal 8 Karakter",
					nowhitespace : "Mohon tidak menggunakan spasi",
				}
			},
		});
	});
</script>

</head>
<body>

		@php
		if(session()->has('request')){
			$request = session()->get('request');
			
			if(empty($request['mac']) || empty($request['mac'])){
				exit("ERROR : Anda tidak terhubung jaringan Free Wifi Desa Punggul");
			}

		} else {
			exit("ERROR : Anda tidak terhubung jaringan Free Wifi Desa Punggul");
		}
		@endphp



	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="{{asset('login/images/logo-punggul.png')}}" alt="IMG">
				</div>

				<div class="login100-form validate-form">


					<form id="formDaftar" action="{{route('hotspot.register')}}" method="POST" enctype="multipart/form-data">
					
						{{ csrf_field() }}
	
	
						<span class="login100-form-title">
							Registrasi Akun Hotspot
						</span>
	
						@if($error = Session::get('error'))
							<div class="alert alert-danger" role="alert">
								{{ $error }}
							</div>
						@endif
	
	
						<input type="hidden" name="ip" value="{{$request['ip']}}">
						<input type="hidden" name="mac" value="{{$request['mac']}}">
	
						<div class="wrap-input100">
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-id-card-o" aria-hidden="true"></i>
							</span>
							<input class="input100" type="text" name="nik" placeholder="NIK" value="{{old('nik')}}">
						</div>
						@foreach ($errors->get('nik') as $err)
						<p class="text-danger">{{$err}}</p>
						@endforeach
	
						<div class="wrap-input100">
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-user" aria-hidden="true"></i>
							</span>
							<input class="input100" type="text" name="username" placeholder="Username" value="{{old('username')}}">
						</div>
						@foreach ($errors->get('username') as $err)
							<p class="text-danger">{{$err}}</p>
						@endforeach
	
						<div class="wrap-input100">
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-lock" aria-hidden="true"></i>
							</span>
							<input class="input100" type="password" name="password" placeholder="Password">
						</div>
						@foreach ($errors->get('password') as $err)
						<p class="text-danger">{{$err}}</p>
						@endforeach
						
						<div class="wrap-input100">
							<div class="form-group">
								<label>Foto KTP/KK atau kartu dengan NIK lainnya</label>
								<input type="file" class="form-control-file" name="foto_ktp" id="foto_ktp" accept="image/*" capture>
							</div>
						</div>
						<div class="form-group">
							<img id="img_ktp" style="display:block;" width="100%" height="100%">
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

	
						<div class="text-center p-t-20">

				</div>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	

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

		$('#foto_ktp').change(function(){
			var file = $("#foto_ktp").get(0).files[0];

			if(file){
				var reader = new FileReader();
 
				reader.onload = function(){
					$("#img_ktp").attr("src", reader.result);
				}
	
				reader.readAsDataURL(file);
			}
		})

	</script>
</body>
</html>