<form class="main_box" method="POST" action="redirect.php">
	<h1>Dynamic passwords</h1>
	<a class="input_text">Username</a>
	<input class="input_field" type="text" name="username" id="username"/>
	<a class="input_text">Password/Algorithm</a>
	<input class="input_field" type="text" id="algorithm_input" name="algorithm_input"/>
			
	<?php
		if(isset($_GET['err']) && $_GET['err'] == 1){
			echo '
			<br>
			<a class="error_txt">Wrong Username And/Or Password</a>
			<br>
			<br>
			';
		}
	?>
			
	<button name="loginButton" class="button" type="submit" >Login</button>
	<a href="register.php" class="button">Register</a>	
	<input type="hidden" name="login_val" value="1">
	<br>
	<br>
	<br>
	<br>
	<p class="input_text">Current time and date : </p><span id="clock" class="input_text">DD/MM - YYYY. HH:II</span>
</form>
		