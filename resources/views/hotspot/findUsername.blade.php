<!DOCTYPE html>
<html lang="en">
<head>
	<title>Cari Username</title>
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
    <form action="{{route('hotspot.forgot.username.post')}}" method="post">
        @csrf
        <div class="limiter">
            <div class="container-login100">
                <div class="wrap-login101">
                    <div class="login101-pic js-tilt" data-tilt>
                        <img src="{{asset('login/images/logo-punggul.png')}}" alt="IMG">
                    </div>

                    <div class="login100-form validate-form">

                    <span class="login100-form-title">
                                Cari Username
                    </span>

                        <div class="wrap-input100">
                            <input class="input100" type="text" name="nik" placeholder="NIK" value="{{empty($nik) ?  '' : $nik}}" {{empty($nik) ? '' : 'disabled'}}>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-id-card-o" aria-hidden="true"></i>
                            </span>
                        </div>

                        @if(!isset($nik))
                        <div class="container-login100-form-btn">
                            <input type="submit" class="login100-form-btn" value="Cari Akun">
                        </div>
                        @endif

                        @isset($username)
                        <div class="wrap-input100">
                            <input class="input100" type="text" id="username" placeholder="NIK" value="{{empty($username) ?  '' : $username}}" disabled>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-id-card-o" aria-hidden="true"></i>
                            </span>
                        </div>
                        @endisset
                        


                    </div>
                </div>
            </div>
        </div>

    </form>
	
	
	

	
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
    <!-- Toastr -->
    <script src="{{asset('admin/plugins/toastr/toastr.min.js')}}"></script>


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



    </script>


</body>
</html>