<!DOCTYPE html>
<html lang="en">
<head>
	<title>Perbaikan Berkas Validasi</title>
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

				<form id="formLogin" class="login100-form validate-form" name="resubmit" action="{{ route('user.resubmit.post') }}" method="post">
					<span class="login100-form-title">
						Perbaikan Berkas
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
							<b>PERHATIAN</b> Harap upload foto KTP/KK dengan jelas sehingga mempermudah proses validasi.
						</p>
					</div>
				
					<div class="wrap-input100">
						<input class="input100" type="text" name="username" placeholder="Username" value="{{$user->username}}" disabled>
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

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
						<input type="submit" class="login100-form-btn" id="submitButton" value="Upload">
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
	<script>
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