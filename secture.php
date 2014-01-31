<?php

/*	Copyright 2014 Jesper Westerberg
*	-- Secture -- 
*	@Author : Jesper Westerberg
*	@Version : 1.0
*/

include_once('Database/database.php');
include_once('parser.php');

class Secture{

	private $db_connection;
	private $parser;
	
	private $stack;
	
	private $input_array;
	private $input_array_index; 
	
	private $grammar = array();
	private $expressions;
	private $strings;

	function __construct(){
		session_start();
	
		$this->db_connection = new Connection();
		$this->parser = new LR_Parser();
	}

	/* -- LOGIN -- */
	public function login($username, $password){
	
		if($username == null || $password == null){
			return 0;
		}
		
		$result_array = $this->db_connection->get_value(TBL_USR, array('algorithm'), array('name'), array($username), NULL, NULL);
		
		if($result_array == -1 || $result_array == null){
			return 0;
		}
		
		$password_fetch = $this->calc_algorithm($result_array[0]['algorithm']);
		
		if($password_fetch != $password){
			return 0;
		}else{
			/* store username, session_id and given password in a session array */
			$_SESSION['user_array'] = array($username, $password);
			return 1;
		}
	}
	
	/* -- IS_ONLINE -- */
	public function is_online(){
		
		/* check if session array is set and has valid values */
		if(!isset($_SESSION['user_array']) || $_SESSION['user_array'][0] == null ||  $_SESSION['user_array'][1] == null){
			return 0;
		}
		
		$pw = $this->db_connection->get_value(TBL_USR, array('algorithm'), array('name'), array($_SESSION['user_array'][0]), NULL, NULL);
		
		if($pw == -1 || $pw == null || $pw == 0){
			return 0;
		}
		
		if($this->calc_algorithm($pw[0]['algorithm']) == $this->calc_algorithm($_SESSION['user_array'][1])){
			return 1;
		}
	
		return $this->calc_algorithm($pw[0]['algorithm']);
	}
	
	/* -- LOGOUT -- */
	public function logout(){
		session_unset();
		session_destroy();	

		if($this->is_online){
			return 0;
		}else{ 
			return 1;
		}
	}
	
	/* -- REGISTER --*/
	public function register($username, $algorithm, $password){
	
		/* check if input is invalid */
		if($username == null || $algorithm == null || $password == null){
			return 0;
		}
	
		$alg_pw = $this->calc_algorithm($algorithm);
		
		if($alg_pw != $password){
			return 0;
		}
		
		/* check that username is available */
		if($this->db_connection->num_rows(TBL_USR, "id", array("name"), array($username)) != 0){
			return 0;
		}
		
		/* get number of users for user id */
		if(($num_users = $this->db_connection->num_rows(TBL_USR, "id", array(), array())) == -1){
			return 0;
		}
		
		/* add user as row in database */
		if($this->db_connection->insert_row(TBL_USR, array("date", "algorithm", "id", "name"), array(date("Y:n:d"), $algorithm, ($num_users+1), $username ))!= 1){
			return 0;
		}
		
		return 1;
	}
	
	/*
	* PRIVATE FUNCTIONS
	*/
	
	/* -- CALC_ALGORITHM -- */
	private function calc_algorithm($algorithm){
		
		$result = "";
		$i = 0;	/* array index */
	
		$algorithm_array = $this->parser->parse($algorithm);
		
		$source_algorithm = $algorithm_array[1];
		
		foreach($algorithm_array[0] as $token){
			if($token == "STRING"){
				/* string given */
				$result = $result.$source_algorithm[$i];
				$i++;
			}else if($token == "EXP"){
				/* expression given */
				$exp = $source_algorithm[$i];
				$exp = preg_replace("/[\[\]]/","", $exp);
				$exp = preg_replace("/D/",date("j"), $exp); 
				$exp = preg_replace("/M/", date("n"), $exp);
				$exp = preg_replace("/Y/", date("y"), $exp);
				
				$exp = preg_replace("/H/", date("G"), $exp);
				$exp = preg_replace("/I/", date("i"), $exp);
				
				$resultt = $this->arithmetic_calc($exp);
				
				if($resultt == null){
					return 0;
				}
				
				$result = $result.$resultt;
				$i++;
			}else{
				/* should not get here, return error */
				return 0;
			}
		}
		return $result;
	}
	
	/* -- ARITHMETIC_CALC -- this needs to be changed to a different solution */
	private function arithmetic_calc($string){
		if(preg_match("/[return0-9\+\*\;\-]+/", $string)){
			$string = "return ".$string.";";	
			return eval($string);
		}else{
			return null;
		}	
	}
}


?>