<?php

include_once('secture.php');


class Redirect{
	
	private $secture;
	
	function __construct(){
	
		$this->secture = new Secture();

		if(isset($_POST['login_val'])){
			$this->redirect_login();
		}else if($_POST['logout_val']){
			$this->redirect_logout();
		}else if($_POST['register_val']){
			$this->redirect_register();
		}else{
			/* should not get here */
			header('Location:index.php');
		}
	}
	
	/* -- REDIRECT_LOGIN -- */
	private function redirect_login(){
		
		$result = $this->secture->login($_POST['username'], $_POST['algorithm_input']);
		
		if($result==1){
			header('Location:index.php?content=profile');
		}else{
			header('Location:index.php?err=1');
		}
	}
	
	/* -- REDIRECT_LOGIN -- */
	private function redirect_logout(){
		$result = $this->secture->logout();
		
		if($result){
			header('Location:index.php');
		}else{
			header('Location:index.php?err=2');
		}
	}
	
	/* -- REDIRECT_REGISTER -- */
	private function redirect_register(){
		
		
		$result = $this->secture->register($_POST['username'], $_POST['algorithm_choose'], $_POST['algorithm_submit']);
		
		if($result){
			header('Location:index.php');
		}else{
			header('Location:register.php?err=3');
		}
	}
}

$red = new Redirect;

?>