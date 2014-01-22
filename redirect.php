<?php

require_once('secture.php');

class redirect{

	global $secture;
	
	function __construct(){
		
		if(isset($_POST['login_val']){
			$this->redirect_login();
		}else if($_POST['logout_val']){
			$this->redirect_logout();
		}else if($_POST['register_val']){
			$this->redirect_register();
		}else{
			/* should not get here */
			header('Location:index.php?err=1');
		}
	}
	
	/* -- REDIRECT_LOGIN -- */
	private function redirect_login(){
		
		$result = $secture->login($_POST['username'], $_POST['password']);
		
		if($result==1){
			header('Location:index.php?content=profile');
		}else{
			header('Location:index.php?err=2');
		}
	}
	
	/* -- REDIRECT_LOGIN -- */
	private function redirect_logout(){
		$result = $secture->logout();
		
		if($result){
			header('Location:index.php');
		}else{
			header('Location:index.php?err=3');
		}
	}
	
	private function redirect_register(){
	
		/* check that algorithm matches */
		if($_POST['algorithm_1'] != $_POST['algorithm_2']){
			header('Location:index.php?err=4');
		}
	
		$result = $seucture->register($_POST['username'], $_POST['algorithm_1']);
	
		if($result){
			header('Location:index.php');
		}else{
			header('Location:index.php?err=5');
		}
	
	}

}

?>