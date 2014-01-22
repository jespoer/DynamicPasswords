<!DOCTYPE html>

<?php 
	include_once('redirect.php');
?>

<html>
<head>
	<title>Secture - Dynamic passwords</title>
	<link href="style.css" type="text/css" rel="stylesheet"/> 
	<script type="javascript">
	
		function addAlgorithm(){
			document.forms['input_fom']['algorithm_input'].value += '[(Todays day)]';
		}
	
	</script>
</head>
<body>

	<form id="main_box" method="POST" name="input_form">
		<h1>Login</h1>
		<a class="input_text">Username</a>
		<input class="input_field" type="text" id="username"/>
		<a class="input_text">Password/Algorithm</a>
		<input class="input_field" type="text" id="algorithm_field" name="algorithm_input"/>
		
		<button name="dayButton" onClick="addAlgorithm">Day</button>	
		
		<button name="submitButton" type="submit" action="redirect.php">Submit</button>
		
		<input type="hidden" name="login_val" value="1">
	</form>

</body>
</html>