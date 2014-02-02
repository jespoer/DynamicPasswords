<!DOCTYPE html>

<?php
	include_once('secture.php');
?>

<html>
<head>
	<title>Secture - Dynamic passwords</title>
	<link href="style.css" type="text/css" rel="stylesheet"/> 
	
	<!-- script for updating clock -->
	<script type="text/javascript" src="JS/update_clk.js"></script>
	
</head>
<body onLoad="update_clk(); setInterval('update_clk()', 1000)">

	<?php 
		$session = new Secture();
	
		/* check if user is online */
		if(isset($_GET['content']) && $_GET['content'] == 'profile' && $session->is_online() == 1){
			/* show user box */
			include('Page_parts/user_box.php');
		}else{
			/* not online, show login box */
			include('Page_parts/login_box.php');
		}
		
		/* always include footer */
		include('Page_parts/footer.php');
		
		?>
	
</body>
</html>