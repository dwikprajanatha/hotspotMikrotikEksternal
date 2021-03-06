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
							<b>PENGUMUMAN</b>, bagi yang sudah membuat akun <b> harap membuat akun kembali </b>. dikarenakan ada perubahan database. Terima Kasih
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

					<div class="text-center p-t-10">
						<a class="txt3" href="{{route('hotspot.forgot.view')}}">
							Lupa  Username atau Password?
						</a>
					</div>
					

					<div class="text-center p-t-20">
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

					<div class="text-center p-t-2">
						<span class="txt1">
							Punya keluhan, kritik atau saran?
						</span>
						<br>

						<a class="txt2" href="{{route('hotspot.keluhan.view')}}">
							Sampaikan Disini
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>

				</form>


			</div>
		</div>
	</div>

	<!-- MODALS -->
	<div class="modal fade" id="pengumumanModal" tabindex="-1" role="dialog" aria-labelledby="pengumumanModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="pengumumanModalLabel">Pengumuman</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<!-- Carousel -->
					<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
						<div class="carousel-inner">
                        @php $i = 0; @endphp
                        @foreach($pengumuman as $p)
							@foreach($p['images'] as $image)
                            @php $i++; @endphp

							<div class="carousel-item {{$i == 1 ? 'active' : ''}}">
								<img class="d-block w-100" src="{{asset('storage/'.$image->link)}}">
							</div>

							@endforeach
                        @endforeach
						</div>
						<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</a>
						<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</a>
					</div>
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
	<script>
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="{{asset('login/js/main.js')}}"></script>

	<script>

		$(document).ready(function(){

            <?php if(!empty($pengumuman)){
                echo("$('#pengumumanModal').modal('show');");
            }
            ?>

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

								} 

							} else {

								$('#errorKategori').append('<div class="alert alert-danger" role="alert"><p class="text-center"><b>ERROR</b>, Akun anda di Non-Aktifkan oleh Admin Jaringan</p></div>');
								$('#formLogin').submit(function(e){
									e.preventDefault();
								});

							}

								
						},
					complete: function(){
						
						console.log("MASUK ELSE");
						var url = base_url + '/api/user/cekValidasi';

						console.log(url);
						//Cek status validasi
						$.ajax({
							url: base_url + '/api/user/cekValidasi',
							type: 'GET',
							async: false,
							dataType : "JSON",
							headers: {'Accept': 'application/json'},
							data: { 'username': input_user.toString() },
							success: function(response){
								var result = response.data;
								console.log(result);

								if(result.status == 2){
									
									$('#errorKategori').append('<div class="alert alert-danger" role="alert"><p><b>ERROR</b>, Akun anda tidak valid, harap upload ulang foto KTP/KK anda pada link berikut <a href="'+ result.link +'"><em>Klik disini</em></a></div>');
									$('#formLogin').submit(function(e){
										e.preventDefault();
									});

								} else {

									$('#formLogin').submit();
									
								}
							},
							error: function(jqXHR, textStatus, errorThrown){
									console.log(textStatus);
									console.log(errorThrown);
							}
						});

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