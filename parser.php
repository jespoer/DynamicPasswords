<?php

class LR_Parser{

	private $grammar;
	private $source;
	
	private $input_array;
	private $stack;
	
	private $state;
	
	function __construct(){
	
		/* will be used in cleaner implementation */
	}
	
	/* -- PARSER ENGINE -- */
	public function parse($input){
	
		$this->grammar = array();
		$this->source = array();
		
		$this->stack = array();
		$this->stack[] = "SOF";
		
		/* remove whitespaces */
		$string = preg_replace("/\s\s*/", "", $input);
		
		$this->input_array = str_split($string);
		$this->input_array[] = "EOF";
		
		$this->input_array_index = 0;
		$this->state = 0;
		
		$run = true;
		
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
		
		/* return grammar and source algorithm */
		return array($this->grammar, $this->source);	
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
			$this->grammar[] = "STRING";
			$this->source[] = implode(array_reverse($tmp_array));	
		}else{
			while(end($this->stack) != "SOF"){
				$res = array_pop($this->stack);
				$tmp_array[] = $res;
			}
				$this->grammar[] = "EXP";
				$this->source[] = implode(array_reverse($tmp_array));
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