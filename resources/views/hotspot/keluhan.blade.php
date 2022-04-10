<!DOCTYPE html>
<html lang="en">
<head>
	<title>Form Keluhan</title>
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
				
				if(empty($request['mac']) && empty($request['mac'])){
					$request = ['mac' => '00:00:00:00:00', 'ip' => '0.0.0.0'];
				}

			} else {
				$request = ['mac' => '00:00:00:00:00', 'ip' => '0.0.0.0'];
			}
			
		@endphp

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="{{asset('login/images/logo-punggul.png')}}" alt="IMG">
				</div>

				<div class="login100-form validate-form p-t-90">

 
					<form id="formKeluhan" action="{{route('hotspot.keluhan.post')}}" method="POST" enctype="multipart/form-data">
					
						{{ csrf_field() }}
	
	
						<span class="login100-form-title m-b-50">
							Form Keluhan, Kritik, atau Saran
						</span>
	
						@if($error = Session::get('error'))
							<div class="alert alert-danger" role="alert">
								{{ $error }}
							</div>
						@elseif($success = Session::get('success'))
							<div class="alert alert-success" role="alert">
								{{ $success }}
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
								<i class="fa fa-id-card-o" aria-hidden="true"></i>
							</span>
							<input class="input100" type="text" name="nama" placeholder="Nama" value="{{old('nama')}}">
						</div>
						@foreach ($errors->get('nama') as $err)
						<p class="text-danger">{{$err}}</p>
						@endforeach
	
						<div class="form-control m-t-30">
							<!-- <span class="focus-input100"></span> -->
							<!-- <input class="input100" type="text" name="text" placeholder="Isian" value="{{old('username')}}"> -->
                            <textarea name="isi" class="form-control" placeholder="Isi.." rows="3"></textarea>
						</div>
						@foreach ($errors->get('username') as $err)
							<p class="text-danger">{{$err}}</p>
						@endforeach
						
						<div class="container-login100-form-btn m-b-40">
							<button class="login100-form-btn" type="submit">
								Kirim
							</button>
						</div>
	
					</form>
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

</body>
</html>