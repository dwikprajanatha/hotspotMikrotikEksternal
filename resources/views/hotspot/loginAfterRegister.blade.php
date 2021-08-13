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
			
			console.log("Password Hashes : " + hash_pass);
			document.login.password.value = hash_pass;
			document.login.submit();
			return false;
	    }

	</script>
	

				<form name="login" action="{{$request['link-login-only']}}" method="post" onsubmit="return doLogin()">
				
					@if($provider == 'facebook')
						<input type="hidden" name="username" value="facebook_user">
						<input type="hidden" name="password" value="facebook_user1234">
					@else
						<input type="hidden" name="username" value="google_user">
						<input type="hidden" name="password" value="google_user1234">
					@endif
					

				</form>

				<script type="text/javascript">
					window.onload=function(){
						var auto = setTimeout(function(){ autoRefresh(); }, 100);
				
						function submitform(){
						//   alert('test');
						  document.forms["login"].submit();
						}
				
						function autoRefresh(){
						   clearTimeout(auto);
						   auto = setTimeout(function(){ submitform(); }, 500);
						}
					}
				</script>


</body>
</html>