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
	//	$this->db_connection = new Connection();
	}

	/* -- LOGIN -- */
	public function login($username, $password){
		
		/* check input parameters */
		if($username == NULL || $password == NULL){
			return 0;
		} 
		
		$result_array = array();
		
		$result_array = $this->db_connection->get_value();
		
	}
	
	/* -- LOGOUT -- */
	public function logout(){
	
	}
	
	/* -- REGISTER --*/
	public function register($username, $algorithm, $password){
	
		$alg_pw = $this->calc_algorithm($algorithm);
		
		if($alg_pw != $password){
			return 0;
		}
		
		/* check that username is available */
		if($this->db_connection->num_rows() != 0){
			return 0;
		}
		
		/* get number of users for user id */
		$num_users = $this->db_connection->num_rows(); 
		
		/* add user as row in database */
		if($this->db_connection->insert_row()!= 1){
			return 0;
		}
	}
	
	/*
	* PRIVATE FUNCTIONS
	*/
	
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
		
		echo $result;
	}
	
	
	
	/* -- LEX_ANALYSIS -- */
	public function lex_and_parse($string){
	
		$token_array = array(); /* initiate token array */
	
		/* remove whitespaces */
		$string = preg_replace("/\s\s*/", "", $string);
		
		/* split string into an array of characters */
		$char_array = str_split($string); 
		
		for($i=0;$i<count($char_array);$i++){
			
			/* left paranthesis */
			if(preg_match("/\[/", $char_array[$i])){
				$token_array[] = "ALGLEFT";
			/* right parenthesis */
			}else if(preg_match("/\]/", $char_array[$i])){
				$token_array[] = "ALGRIGHT";	
			/* plus match */
			}else if(preg_match("/\+/", $char_array[$i])){
				$token_array[] = "PLUS";		
			/* mul match */
			}else if(preg_match("/\*/", $char_array[$i])){
				$token_array[] = "MUL";
			/* integer match */
			}else if(preg_match("/[0-9]/", $char_array[$i])){
				
				while(preg_match("/[0-9]/", $char_array[$i])){
					$i++;
				}
				if(!preg_match("/[a-zA-Z\.\,\-\_\?\!\(\)\#\&\%]/", $char_array[$i])){
					$token_array[] = "INT";
				} 
				$i--;
			/* string match */
			}else if(preg_match("/[a-zA-Z\.\,\-\_\?\!\(\)\#\&\%]/", $char_array[$i])){
				
				while(preg_match("/[a-zA-Z0-9\.\,\-\_\?\!\(\)\#\&\%]/", $char_array[$i])){
					$i++;
					
					if($i==count($char_array)){
						break;
					}
				}
				$token_array[] = "STRING";
				$i--;
			}else{
				/* Given String is not valid */
				return false;
			}	
		}
		return $token_array;
	}
	
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