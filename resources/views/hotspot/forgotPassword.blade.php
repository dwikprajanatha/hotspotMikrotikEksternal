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
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugins/toastr/toastr.min.css')}}">
<!--===============================================================================================-->
</head>
<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="{{asset('login/images/logo-punggul.png')}}" alt="IMG">
				</div>

				<div class="login100-form validate-form">

                <span class="login100-form-title">
							Pemulihan Akun Hotspot
	            </span>
	
                <!-- <div id="cari-akun"> -->

                    <!-- <div class="wrap-input100">
                        <input class="input100" type="text" id="nik" placeholder="NIK">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-id-card-o" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100">
                        <input class="input100" type="text" id="username" placeholder="Username">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div> -->

                    <!-- <div class="container-login100-form-btn">
                        <button class="login100-form-btn" id="cariAkun">
                            Cari Akun
                        </button>
                    </div> -->

                <!-- </div> -->

                <!-- <div id="ubah-akun"> -->

                    <form action="{{route('hotspot.forgot')}}" method="POST">
					
						{{ csrf_field() }}

						@if($error = Session::get('error'))
							<div class="alert alert-danger" role="alert">
								{{ $error }}
							</div>
						@endif

                        <!-- <input type="hidden" name="id_akun" id="id_akun"> -->
                        <div class="wrap-input100">
                            <input class="input100" type="text" id="nik" placeholder="NIK">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-id-card-o" aria-hidden="true"></i>
                            </span>
                        </div>

                        <div class="wrap-input100">
                            <input class="input100" type="text" id="username" placeholder="Username">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </span>
                        </div>
						
	
						<div class="wrap-input100">
							<input class="input100" type="password" name="password" placeholder="Password Baru">
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-lock" aria-hidden="true"></i>
							</span>
						</div>
	
						<div class="wrap-input100">
							<input class="input100" type="password" name="password_confirmation" placeholder="Konfirmasi Password Baru">
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-lock" aria-hidden="true"></i>
							</span>
						</div>
	
						<div class="container-login100-form-btn">
							<button class="login100-form-btn">
								Ganti Password
							</button>
						</div>
	
					</form>

                <!-- </div> -->

					


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
    <!-- Toastr -->
    <script src="{{asset('admin/plugins/toastr/toastr.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('login/js/main.js')}}"></script>


    <script>

        toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
        }

        $(document).ready(function(){
            @if(session()->has('error'))
                toastr['error']('<?php echo session()->pull('error'); ?>')
            @elseif(session()->has('success'))
                toastr['success']('<?php echo session()->pull('success'); ?>')
            @endif
        });


        // $(document).ready(function(){

        //     $('#ubah-akun').css("visibility", "hidden");

        //     var base_url = window.location.origin;

        //     $('#cariAkun').click(function(){

        //         var nik = $("#nik").val();
        //         var username = $("#username").val();

        //         $.get(base_url + '/api/user/checkUser', {'nik': nik, 'username': username})
        //             .done(function(data){

        //                 var d = JSON.parse(data);
        //                 console.log(d);

        //                 if(d.status == 200){
        //                     $("#nik").prop('disabled', true);
        //                     $("#username").prop('disabled', true);
        //                     $('#cariAkun').hide();

        //                     $('#ubah-akun').fadeIn();
        //                     $('#ubah-akun').css("visibility", "visible");

        //                     $('#id_akun').val(data.id_akun);

        //                     toastr['success']('Data Valid!');

        //                 } else {

        //                     toastr['error'](data.message)

        //                 }
        //             });

        //     });

        // });


    </script>


</body>
</html>