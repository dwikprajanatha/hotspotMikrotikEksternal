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

		function autoLogIn(un, pw, link) {

			var form = document.createElement("form");
			var element1 = document.createElement("input"); 
			var element2 = document.createElement("input");  

			form.style.display = "none";
			
			form.method = "POST";
			form.action = link;   

			element1.value=un;
			element1.name="username";
			form.appendChild(element1);  

			element2.value=pw;
			element2.name="password";
			form.appendChild(element2);

			document.body.appendChild(form);

			form.submit();

		}

		var username = "<?php echo($username) ?>";
		var password = "<?php echo($password) ?>";
		var link_login = "https://mikrotik.lpkresortkuta.com/login";

		autoLogIn(username,password,link_login);
		
	</script>
	
				

</body>
</html>