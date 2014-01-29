<!DOCTYPE html>

<html>
<head>
	<title>Secture - Register</title>
	<link href="style.css" type="text/css" rel="stylesheet"/> 
</head>
<body>
	
	<form class="main_box" name="input_form" method="POST" action="redirect.php">
		<h1>Register</h1>
		<a class="input_text">Choose Username</a>
		<input class="input_field" type="text" name="username" id="username"/>
		<a class="input_text">Choose Password/Algorithm</a>
		<input class="input_field" type="text" id="algorithm_choose" name="algorithm_choose"/>
		<a class="input_text">Enter Complete Password</a>
		<input class="input_field" type="text" id="algorithm_submit" name="algorithm_submit"/>
		
		<button name="loginButton" class="button" type="submit">Submit</button>

		<?php
			if(isset($_GET['err']) && $_GET['err'] == 3){
				echo '
					<br>
					<br>
					<br>
					<br>
					<a class="error_txt">Could not register. Either username taken or algorithm didnt match password</a>
				';
			}		
		?>
		
		<input type="hidden" id="register_val" name="register_val" value="1">
	</form>
	
	<div class="main_box">
		<h1>info</h1>
		<p class="info_txt">The dynamic statements which can be used (at this moment in implementation) is current Year, Month(1-12), 
		Day(1-31), Hour(0-23) and minute (0-59). Each of these have a token given to them Y,M,D,H and I. To use
		these in your password you bracket them in. For instance [D+I] would correspond to Current day in month plus the 
		current minute. This would be equal to 10 at the third of july at 15:07.
		<br>
		<br>
		You can also use integer operations in these brackets. For instance [H+22] would correspond to 
		the current hour + 22. At 21:12 a clock this would be equal to 43. 
		<br>
		<br>
		Strings can ofcourse be used in the passwords aswell. You can choose a password that's just a regular string
		if you would like to. Strings could also be matched with algorithms. "hello[D*3]je_+2" could work as your algorithm and it
		would correspond to, first the string "hello" followed by the current day times 3 and end in the string "je_+2".
		The third in any month this password would be "hello9je_+2".
		<br>
		<br>
		Valid arithmetic operations in the brackets are at this moment + and * but more will soon be added.
		
		
	</div>
	
	<footer>
		<a class="foot_txt">&#169 Copyright - Jespoer</a>
	</footer>

	


</body>
</html>