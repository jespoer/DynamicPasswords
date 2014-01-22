<?php

include_once('Database/database.php');

class secture{

	private $db_connection;

	function __construct(){
		$this->db_connection = new Connection();
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
	
	private function fetch_algorithm($algorithms){
		
	}
	
}


?>