<?php

require_once('secture.php');

class redirect{

	//global $secture;
	
	function __construct(){
		
		if(isset($_POST['login_val'])){
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
	
	/* -- REDIRECT_REGISTER -- */
	private function redirect_register(){
		
		$result = $seucture->register($_POST['username'], $_POST['algorithm_choose'], $_POST['algorithm_submit']);
	
		if($result){
			header('Location:index.php');
		}else{
			header('Location:index.php?err=5');
		}
	
	}

}

?>