<!DOCTYPE html>

<?php 
	include_once('redirect.php');
	
?>

<script type="text/javascript">
	function addMinute(){
	
	
	}

</script>

<html>
<head>
	<title>Secture - Register</title>
	<link href="style.css" type="text/css" rel="stylesheet"/> 
</head>
<body>
	
	<p id="dem"></p>
	
	<form id="main_box" name="input_form" method="POST" action="redirect.php">
		<h1>Register</h1>
		<a class="input_text">Choose Username</a>
		<input class="input_field" type="text" id="username"/>
		<a class="input_text">Choose Password/Algorithm</a>
		<input class="input_field" type="text" id="algorithm_choose" name="algorithm_choose"/>
		<a class="input_text">Enter Password/Algorithm</a>
		<input class="input_field" type="text" id="algorithm_submit" name="algorithm_submit"/>
		
		<button name="loginButton" class="button" type="submit">Submit</button>	

		
		<input type="hidden" id="register_val" name="register_val" value="1">
	</form>
	


</body>
</html>