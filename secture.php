<?php

include_once('Database/database.php');

class Secture{

	private $db_connection;
	
	private $stack;
	private $stack_index;
	
	private $input_array;
	private $input_array_index; 
	
	private $grammar = array();
	private $expression = array();

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
	public function register($username, $algorithm){
		
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
	
	public function parser($algorithms){
		
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
		$state = 0;
		
		while($run){
			switch($state){
				case 0:
					if(preg_match("/\[/", $this->input_array[$this->input_array_index])){
						$this->shift($this->input_array[$this->input_array_index]);
						$state = 1;
					}else if(preg_match("/EOF/", $this->input_array[$this->input_array_index])){	
						$state = 0;
						$run = false;
						$this->accept();
						break;
					}else{
						$this->shift($this->input_array[$this->input_array_index]);
						$state = 4;
					}
					break;
				case 1:
					if(preg_match("/[0-9]/", $this->input_array[$this->input_array_index])){
						$this->shift($this->input_array[$this->input_array_index]);
						$state = 2;
					}else if(preg_match("/[DMYHI]/", $this->input_array[$this->input_array_index])){
						$this->shift($this->input_array[$this->input_array_index]);
						$state = 3;
					}else{
						$this->shift($this->input_array[$this->input_array_index]);
						$state = 4;
					}
					break;
				case 2:
					if(preg_match("/[0-9]/", $this->input_array[$this->input_array_index])){
						$this->shift($this->input_array[$this->input_array_index]);
						$state = 2;
					}else if(preg_match("/[\+\*]/", $this->input_array[$this->input_array_index])){
						$this->shift($this->input_array[$this->input_array_index]);
						$state = 6;
					}else if(preg_match("/\]/", $this->input_array[$this->input_array_index])){
						$this->shift($this->input_array[$this->input_array_index]);
						if(!$this->reduce(2)){
							return -1;
						}
						$state = 0;
					}else{
						$this->shift($this->input_array[$this->input_array_index]);
						$state = 4;
					}
					break;
				case 3:
					if(preg_match("/[\+\*]/", $this->input_array[$this->input_array_index])){
						$this->shift($this->input_array[$this->input_array_index]);
						$state = 6;
					}else if(preg_match("/\]/", $this->input_array[$this->input_array_index])){
						$this->shift($this->input_array[$this->input_array_index]);
						if(!$this->reduce(2)){
							return -1;
						}
						$state = 0;
					}else{
						$this->shift($this->input_array[$this->input_array_index]);
						$state = 4;
					}
					break;
				case 4:
					if(preg_match("/EOF/", $this->input_array[$this->input_array_index])){
						$state = 0;
						$run = false;
						$this->accept();
						break;
					}else if(preg_match("/\[/", $this->input_array[$this->input_array_index])){
						if(!$this->reduce(1)){
							return -1;
						}
						$this->shift($this->input_array[$this->input_array_index]);
						$state = 1;
					}else if(preg_match("/[a-zA-Z0-9\.\]\,\-\+\*\_\?\!\(\)\#\&\%]/", $this->input_array[$this->input_array_index])){
						$this->shift($this->input_array[$this->input_array_index]);
						$state = 4;
					}else{
						$this->shift($this->input_array[$this->input_array_index]);
						if(!$this->reduce(1)){
							return -1;
						}
						$state = 0;
					}
					break;
				case 5:
					break;
				case 6:
					if(preg_match("/[0-9]/", $this->input_array[$this->input_array_index])){
						$this->shift($this->input_array[$this->input_array_index]);
						$state = 2;
					}else if(preg_match("/[DMYHI]/", $this->input_array[$this->input_array_index])){
						$this->shift($this->input_array[$this->input_array_index]);
						$state = 3;
					}else if(preg_match("/\]/", $this->input_array[$this->input_array_index])){
						$this->shift($this->input_array[$this->input_array_index]);
						if(!$this->reduce(2)){
							return -1;
						}
						$state = 0;
					}else{
						$this->shift($this->input_array[$this->input_array_index]);
						$state = 4;
					}
					break;
				case 7:
					
					break;
				default:
			}
		}
		
		/* return grammar */
		return $this->grammar;	
	}
	
	private function shift($n){
		array_push($this->stack, $n);
		$this->input_array_index += 1; 
	}
	
	private function reduce($identifier){
		if($identifier == 1){
			while(end($this->stack) != "SOF"){
				$res = array_pop($this->stack); 
				
			}
		
			array_push($this->grammar, "STRING");
		}else{
			while(end($this->stack) != '['){
				$res = array_pop($this->stack);
				$this->expression[] = $res;
				if($res == "SOF"){
					return 0;
				}
			}
				array_push($this->grammar, "EXP");
		}
		return 1;
	}
	
	private function accept(){
		if(count($this->stack) != 2){
			$this->reduce(1);
		}
		
		for($i = (count($this->expression));$i>=0; $i--){
			echo $this->expression[$i];
		}
	}
}


?>