<!DOCTYPE html>
<html lang="en">
<head>
	<title>Masuk Wifi</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

	<!-- HASH MD5 PASSWORD  -->
	<script src="{{asset('login/js/md5.js')}}"></script>
	<script type="text/javascript">

	    function doLogin() {
			var chap_id = "<?php echo($request['chap-id']) ?>";
			var chap_challenge = "<?php echo($request['chap-challenge']) ?>";
			var hash_pass = hexMD5(chap_id + document.login.password.value + chap_challenge);
			document.login.password.value = hash_pass;
			document.login.submit();
			return false;
	    }

	</script>
	

				<form class="login100-form validate-form" name="login" action="{{$request['link-login-only']}}" method="post" onsubmit="return doLogin()">
				

					

				</form>


</body>
</html>