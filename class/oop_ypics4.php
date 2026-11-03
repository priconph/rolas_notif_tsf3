<?php

//THIS CLASS CONNECTS INTO THE YPICS SERVER OR MSSQL Server	4.0
class YPICS4_TS{
	//FUNCTION TO CONNECT TO MALACHI SERVER	
    private function connect_DBM(){
		$path 		= '../';
		$db_config  = 'db_config/db_ypics4_ts.php';
		if(file_exists($path.$db_config)){
			include($path.$db_config);
		}else{
			$path = '../../';
			if(file_exists($path.$db_config)){
				include($path.$db_config);
			}else{
				$path = '../../../';
				if(file_exists($path.$db_config)){
					include($path.$db_config);
				}else{
					exit;
				}
			}
		}
		$this->db_handleM = mssql_connect($server,$username,$password);
        return mssql_select_db($db_name,$this->db_handleM);
    }
   
    //FUNCTION TO CLOSE MALACHI SERVER
    private function close_DBM(){
        if(isset($this->db_handleM)){
            mssql_close($this->db_handleM);
        }
    }
	
	/////////////////////////////////PUBLIC FUNCTIONS///////////////////////////////////////////////	
	//FUNCTION TO LOAD PO NUM FROM MALACHI DATABASE
	public function select_query($array_fields,$table,$joins,$sql_where,$sql_order){ //sample
		$db_found = $this->connect_DBM();
		if($db_found){
			$array_fields = implode(",",$array_fields);
			$SQL = "SELECT $array_fields FROM {$table} {$joins} {$sql_where} {$sql_order};";
			$result = mssql_query($SQL);
			if(!$result){
				return mssql_get_last_message;
			}
			return $result;
			mssql_free_result($result);
		}
		else{
			$errorMsg = "Database not found, Please contact your administrator";
		}
		$this->close_DBM();
	}
	
	public function select_query_script($array_fields,$table,$joins,$sql_where,$sql_order){ //sample
		$db_found = $this->connect_DBM();
		if($db_found){
			$array_fields = implode(",",$array_fields);
			$SQL = "SELECT $array_fields FROM {$table} {$joins} {$sql_where} {$sql_order};";
			return $SQL;
			mssql_free_result($result);
		}
		else{
			$errorMsg = "Database not found, Please contact your administrator";
		}
		$this->close_DBM();
	}
}


?>