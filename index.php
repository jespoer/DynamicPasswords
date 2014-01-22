<!DOCTYPE html>

<?php 
	include_once('redirect.php');
?>

<html>
<head>
	<title>Secture - Dynamic passwords</title>
	<link href="style.css" type="text/css" rel="stylesheet"/> 
</head>
<body>

	<form id="main_box" method="POST" action="redirect.php">
		<h1>Login</h1>
		<a class="input_text">Username</a>
		<input class="input_field" type="text" id="username"/>
		<a class="input_text">Password</a>
		<input class="input_field" type="password" id="password"/>
		<input type="hidden" name="login_val" value="1">
	</form>

</body>
</html>