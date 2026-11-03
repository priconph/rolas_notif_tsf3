<?php
	ini_set('display_errors', 1);
	// ini_set('display_errors', 0);
	ini_set('display_startup_errors', 1);
	// ini_set('display_startup_errors', 0);
	error_reporting(E_ALL);
	
	// require('../media/PHPMailer_SMTP/PHPMailerAutoload.php');
	/* This email must send everyday, 12:01AM */

	// date_default_timezone_set('Asia/Manila');
	if(date('H:i') == '00:01') {
	// if(date('H:i') == '13:42') {
		send_daily_email_for_ts();	

	} 

	// echo send_daily_email_for_ts();

	// echo send_rolas_logs();
	// send_daily_email_for_ts();
	function send_daily_email_for_ts() {
		/* Get email recipients of ROLAS */
		require_once('../class/oop_rolas.php');
		$array_fields 	= array('to_recipient', 'cc_recipient');
		$table 	   		= 'tbl_recipient';
		$joins 	   		= '';
		$sql_where 		= 'WHERE logdel=0';
		$sql_order 		= '';
		$sql_limit 		= 'LIMIT 0,1';
		$result = ROLAS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$script	= ROLAS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$return = array();
		$sql_data = array();
		if($row = mysqli_fetch_assoc($result)){
			$to = $row['to_recipient'];
			$cc = $row['cc_recipient'];
		}
		
		/* Generate email for ROLAS; insert to db_mailer */
		require_once('../class/oop_wbs.php');
		require_once('./send_email.php');
		$count = 0;
		$php_email 		= new email();
		// $table  		= "tbl_wbs_material_receiving_batch";
		$table  		= "tbl_wbs_inventory";
		$array_fields	= array("item","item_desc","lot_no","received_date","DATE_ADD(received_date, INTERVAL 6 MONTH) as requali_date");
		$joins  	 	= "";
		$sql_where  	= "WHERE (`item_desc` LIKE '%ct%' OR `item_desc` LIKE '%CONTACT%') AND DATE_ADD(received_date, INTERVAL 6 MONTH) <= '".date('Y-m-d')."'";
		$sql_order  	= "ORDER BY id DESC";
		$sql_limit  	= "";
		$result        	= WBSTS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);


		/* 08032024 by Nessa */
		// if($row = mysqli_fetch_array($result)){
		// 	$wbs_inv_qty = get_wbs_inventory($row['lot_no']);
		// 	$wbs_qty = $wbs_inv_qty['qty'];

		// 	if($wbs_qty != 0){
		// 		$count++;
		// 	}
		// }
		
		// Chris 080724
		$rolas_lot_no = array();
		$wbs_inv_qty = get_wbs_inventory();
		while($row = mysqli_fetch_assoc($result)){
			array_push($rolas_lot_no, $row['lot_no']);
		}
		$wbs = array_shift($wbs_inv_qty['lot_no']);
		ini_set('max_execution_time', '3000');
		for($x = 0; $x < count($rolas_lot_no); $x++){
			if(!isset($wbs[$rolas_lot_no[$x]])){
				$count++;
			}
		}

		// $from 		= 'ROLASAlertSystem@pricon.ph';
		$from 		= 'issinfoservice@pricon.ph'; //Chan 06-27-23
		$subject 	= 'TS-F3 Lot for Re-qualification: (Date: '.date('M d, Y').')';
		$body 	    = 'Hi Ma\'am / Sir!<br>';
		// $body 	   .= 'Please be informed that <b>'.number_format($result->num_rows).' item/s</b> are needed to be re-qualified. <br>';
		// $body 	   .= 'Please be informed that <b>'.number_format($count).' item/s</b> are needed to be re-qualified. <br>';
		$body 	   .= 'Please be informed that you have items needed to be re-qualified. <br>'; //10182024 by Nessa
		
		$msg = $php_email->send_email($to, $from, $cc, $subject, $body);
	}
	
	function send_rolas_logs() {				
		/* Populate data of sent emails from ROLASAlertSystem@pricon */
		require_once('../class/oop_mailer.php');
		$array_fields 	= array('*');
		$table 	   		= ' tbl_mailer';
		$joins 	   		= '';
		// $sql_where 		= 'WHERE `from`="ROLASAlertSystem@pricon.ph" AND from_name="ROLASAlertSystem@pricon.ph"';
		$sql_where 		= 'WHERE `from`="issinfoservice@pricon.ph" AND from_name="issinfoservice@pricon.ph"'; //Chan 06-27-23
		$sql_order 		= 'ORDER BY pkid DESC';
		$sql_limit 		= 'LIMIT 0,30';
		$result = MAILER::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$script	= MAILER::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$return = array();
		$sql_data = array();
		
		$tr_body = '';
		while($row = mysqli_fetch_assoc($result)){			
			$tr_body 	.= '<tr>';
			$tr_body 	.= '	<td>'.$row['subject'].'</td>';
			$tr_body 	.= '	<td>'.$row['message'].'</td>';
			$tr_body 	.= '	<td>'.$row['send_date_time'].'</td>';
			$tr_body 	.= '	<td>'.$row['date_time_sent'].'</td>';
			$tr_body 	.= '</tr>';
		}
		
		$table 	 = '<table border=1>';
		$table 	.= '	<thead>';
		$table 	.= '		<th>Subject</th>';
		$table 	.= '		<th>Messge</th>';
		$table 	.= '		<th>Date / Time Send</th>';
		$table 	.= '		<th>Date / Time Sent</th>';
		$table 	.= '	</thead>';
		$table 	.= '	<tbody>';
		$table 	.= $tr_body;		
		$table 	.= '	</tbody>';
		$table 	.= '</table>';		
		
		return $table;
	}

	/* added by Nessa 08032024 */
	// function get_wbs_inventory($lot_no){
	function get_wbs_inventory(){
        require_once('../class/oop_notif.php');
        $return             = $_POST;
        $result             = '';
        $array_fields       = array('*');
        $table              = 'tbl_wbs_inventory';
        $joins              = '';
        // $sql_where          = 'WHERE lot_no="'.$lot_no.'"';
        $sql_where          = 'WHERE `qty` = 0 AND `lot_no` IS NOT NULL AND `lot_no` <> "-" AND `lot_no` <> "." AND `lot_no` <> ""';
        $sql_order          = '';
        // $sql_limit          = 'LIMIT 0,1';
        $sql_limit          = '';
        $result             = WBS_TS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
        $ctr = 0;
        // if($row = mysqli_fetch_array($result)){
        while($row = mysqli_fetch_array($result)){
            $return['lot_no'][$ctr]         = $row['lot_no'];
            $return['qty'][$ctr]            = $row['qty'];
            $return['deleted'][$ctr]            = $row['deleted'];
            $ctr++;
               
        }
        // $return['ctr']  = $ctr;
        return $return;
    }

	
	
	
	
?>