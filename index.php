<!DOCTYPE html>

<?php 
	include_once('redirect.php');
	include_once('secture.php');
?>

<html>
<head>
	<title>Secture - Dynamic passwords</title>
	<link href="style.css" type="text/css" rel="stylesheet"/> 
</head>
<body>
	
	<form id="main_box" action="POST" name="input_form">
		<h1>Login</h1>
		<a class="input_text">Username</a>
		<input class="input_field" type="text" id="username"/>
		<a class="input_text">Password/Algorithm</a>
		<input class="input_field" type="text" id="algorithm_field" name="algorithm_input"/>
		
		<button name="loginButton" method="post" class="button" type="submit" action="redirect.php">Login</button>
		
		<a href="register.php" class="button">Register</a>	
		
		
		
		<input type="hidden" name="login_val" value="1">
	</form>
	
	<?php
		$sect = new Secture(); /*
		$s = $sect->LRParser("jesper[D+2*3+H*3*2*9]asd123[D]");
		print_r($s);*/
		$sect->calc_algorithm("[D+4]Jesper");
		
	?>

</body>
</html>