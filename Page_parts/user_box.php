<form class="main_box" method="POST" action="redirect.php">
	<h1>Profile</h1>
	<p>Hi '.$_SESSION['user_array'][0].'!
	<input type="hidden" name="logout_val" value="1">
	<button name="logoutButton" class="button" type="submit" >Logout</button>
</form>