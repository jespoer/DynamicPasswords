<?php

include_once('Database/database.php');

class Secture{

	private $db_connection;
	
	private $state;
	
	private $stack;
	private $stack_index;
	
	private $input_array;
	private $input_array_index; 
	
	private $grammar = array();
	private $expressions = array();
	
	private $strings = array();

	function __construct(){
		$this->db_connection = new Connection();
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
			return 1;
		}
	}
	
	/* -- LOGOUT -- */
	public function logout(){
	
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
	public function calc_algorithm($algorithm){
		
		$result = "";
		$i = 0;	/* string index */
		$j = 0; /* exp index */
	
		$grammar = $this->LRParser($algorithm);
		
		foreach($grammar as $token){
			if($token == "STRING"){
				/* string given */
				$result = $result.$this->strings[$i];
				$i++;
			}else if($token == "EXP"){
				/* expression given */
				$exp = $this->expressions[$j];
				$exp = preg_replace("/[\[\]]/","", $exp);
				$exp = preg_replace("/D/",date("j"), $exp); 
				$exp = preg_replace("/M/", date("n"), $exp);
				$exp = preg_replace("/Y/", date("y"), $exp);
				
				$exp = preg_replace("/H/", date("G"), $exp);
				$exp = preg_replace("/I/", date("i"), $exp);
				
				$exp = preg_replace("/[\+\*]/"," ", $exp); 
				
				$exp = explode(" ", $exp);
				$resultt = 0;
				foreach($exp as $value){
					$resultt += intval($value);
				}
				
				$result = $result.$resultt;
				$j++;
			}else{
				/* should not get here, return error */
				return 0;
			}
		}
		
		return $result;
	}

	/* -- LRPARSER -- */
	public function LRParser($string){
	
		$this->stack = array();
		
		array_push($this->stack, "SOF");
	
		$this->stack_index = 0;
		$this->input_array_index = 0;
	
		/* remove whitespaces */
		$string = preg_replace("/\s\s*/", "", $string); 
		
		/* remove other invalid characters */
		$string = $string;
		
		$this->input_array = str_split($string);
		
		array_push($this->input_array, "EOF");
		
		$run = true;
		$this->state = 0;
		
		while($run){
			switch($this->state){
				case 0:
					if(preg_match("/\[/", $this->input_array[$this->input_array_index])){
						$this->shift(1);
					}else if(preg_match("/EOF/", $this->input_array[$this->input_array_index])){	
						$this->state = 0;
						$run = false;
						$this->accept(0);
						break;
					}else{
						$this->shift(4);
					}
					break;
				case 1:
					if(preg_match("/[0-9]/", $this->input_array[$this->input_array_index])){
						$this->shift(2);
					}else if(preg_match("/[DMYHI]/", $this->input_array[$this->input_array_index])){
						$this->shift(3);
					}else{
						$this->shift(4);
					}
					break;
				case 2:
					if(preg_match("/[0-9]/", $this->input_array[$this->input_array_index])){
						$this->shift(2);
					}else if(preg_match("/[\+\*]/", $this->input_array[$this->input_array_index])){
						$this->shift(6);
					}else if(preg_match("/\]/", $this->input_array[$this->input_array_index])){
						$this->shift(0);
						$this->reduce(2);
					}else{
						$this->shift(4);
					}
					break;
				case 3:
					if(preg_match("/[\+\*]/", $this->input_array[$this->input_array_index])){
						$this->shift(6);
					}else if(preg_match("/\]/", $this->input_array[$this->input_array_index])){
						$this->shift(0);
						$this->reduce(2);
					}else{
						$this->shift(4);
					}
					break;
				case 4:
					if(preg_match("/EOF/", $this->input_array[$this->input_array_index])){
						$this->state = 0;
						$run = false;
						$this->accept(4);
						break;
					}else if(preg_match("/\[/", $this->input_array[$this->input_array_index])){
						$this->reduce(1);
						$this->shift(1);
					}else if(preg_match("/[a-zA-Z0-9\.\]\,\-\+\*\_\?\!\(\)\#\&\%]/", $this->input_array[$this->input_array_index])){
						$this->shift(4);
					}else{
						$this->shift(0);
						$this->reduce(1);
					}
					break;
				case 5:
					break;
				case 6:
					if(preg_match("/[0-9]/", $this->input_array[$this->input_array_index])){
						$this->shift(2);
					}else if(preg_match("/[DMYHI]/", $this->input_array[$this->input_array_index])){
						$this->shift(3);
					}else if(preg_match("/\]/", $this->input_array[$this->input_array_index])){
						$this->shift(0);
						$this->reduce(2);
					}else{
						$this->shift(4);
					}
					break;
				default:
			}
		}
		echo "<br> Expressions:";
		print_r($this->expressions);
		echo "<br> Strings:";
		print_r($this->strings);
		
		/* return grammar */
		return $this->grammar;	
	}
	
	/* -- SHIFT -- */
	private function shift($next_state){
		$this->stack[] = $this->input_array[$this->input_array_index];
		$this->input_array_index += 1; 
		$this->state = $next_state;
	}
	
	/* -- REDUCE -- */
	private function reduce($identifier){
	
		/* array used when poping stack */
		$tmp_array = array();
		
		if($identifier == 1){
			while(end($this->stack) != "SOF"){
				$res = array_pop($this->stack); 
				$tmp_array[] = $res;
			}
			$this->strings[] = implode(array_reverse($tmp_array));
			$this->grammar[] = "STRING";
		}else{
			while(end($this->stack) != "SOF"){
				$res = array_pop($this->stack);
				$tmp_array[] = $res;
			}
				$this->expressions[] = implode(array_reverse($tmp_array));
				$this->grammar[] = "EXP";
		}
	}
	/* -- ACCEPT -- */
	private function accept($state){
		if($state == 4){
			$this->reduce(1);
		}
			
	}
}


?>