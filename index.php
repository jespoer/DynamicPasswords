<!DOCTYPE html>

<html>
<head>
	<title>Secture - Dynamic passwords</title>
	<link href="style.css" type="text/css" rel="stylesheet"/> 
</head>
<body>

	<?php 
		if(isset($_GET['content']) && $_GET['content'] == 'profile'){
		
		echo '
		<div class="main_box">
			<h1>profile</h1>
		</div>
		';
		
		}else{
		
		echo '
		<form class="main_box" method="POST" action="redirect.php">
			<h1>Dynamic passwords</h1>
			<a class="input_text">Username</a>
			<input class="input_field" type="text" name="username" id="username"/>
			<a class="input_text">Password/Algorithm</a>
			<input class="input_field" type="text" id="algorithm_input" name="algorithm_input"/>
			
		';
		
		if(isset($_GET['err']) && $_GET['err'] == 1){
			echo '
			<br>
			<a class="error_txt">Wrong Username And/Or Password</a>
			<br>
			<br>
			';
			}
		
		echo '	
			<button name="loginButton" class="button" type="submit" >Login</button>
		
			<a href="register.php" class="button">Register</a>	
		
			<input type="hidden" name="login_val" value="1">
		</form>
		
		';
		}
		
		echo '
			<footer>
				<a class="foot_txt">&#169 Copyright - Jespoer</a>
			</footer>
		';
		
		?>
	
</body>
</html>