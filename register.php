<!DOCTYPE html>

<?php 
	include_once('redirect.php');
?>

<script type="text/javascript">
	function addMinute(){
	
		//var field = document.getElementById("algorithm_field").;
	
		document.getElementById("dem").innerHTML = "aa";
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

		
		<form>
			<button onClick="addMinute()" class="button">Minute</button>
			<button onClick="addHour" class="button">Hour</button>
			<button onClick="addDay" class="button">Day</button>
			<button onClick="addMonth" class="button">Month</button>
		</form>
		
		<input type="hidden" name="register_val" value="1">
	</form>
	


</body>
</html>