<?php

class WBS_TS extends mysqli 
{
	private static $instance = null;
	
	public static function getInstance() {
		if(!self::$instance instanceof self) 
		{
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function __clone()
	{
		trigger_error('Clone is not allowed.',E_USER_ERROR);
	}
	
	public function __wakeup()
	{
		trigger_error('Deserializing is not allowed.',E_USER_ERROR);
	}
	
	private function __construct() {
		$db = '../db_config/seiko_wbs_ts.php';
		if(file_exists($db)) {
			include($db);
		} else if(file_exists($db)) {
			include('../'.$db);
		}
		parent::__construct($server, $username, $password, $db_name);
		
		if(mysqli_connect_error())
		{
			exit('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
		}
		parent::set_charset('utf-8');
	}

	////////////////////////////////////UP TO HERE////////////////////////////////////////
	
	////////////////////////////////////PUBLIC FUNCTIONS STARTS HERE//////////////////////////////////////////////
	public function select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit){
		$query = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $array_fields) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit};";
		return $this->query($query);
	}
    
    public function select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit){
		$query = "<br>SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $array_fields) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit};<br>";
		return $query;
	}
}


?>