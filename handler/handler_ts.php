<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	include('common_function.php');
	
	if(is_ajax()) {
		if(isset($_POST["action"]) && !empty($_POST["action"])) {
			$action = $_POST["action"];
			switch($action) {				
				case "reload_wbs_data" 						: reload_wbs_data(); break; 
				case "return_selected_requalification" 		: return_selected_requalification(); break; 
				case "get_po_list" 							: get_po_list(); break; 
				case "get_po_details" 						: get_po_details(); break; 
				case "get_invoice_num_list" 				: get_invoice_num_list(); break; 
				case "get_partname_by_partcode" 			: get_partname_by_partcode(); break; 
				case "save_whse_requalification" 			: save_whse_requalification(); break; 
				case "get_aql_list" 						: get_aql_list(); break; 
				case "get_family_list" 						: get_family_list(); break; 
				case "return_requalification_details" 		: return_requalification_details(); break; 
				case "return_inspection_matrix_value" 		: return_inspection_matrix_value(); break; 
				case "return_current_workweek" 				: return_current_workweek(); break; 
				case "return_current_fiscal_year" 			: return_current_fiscal_year(); break; 
				case "save_iqc_requalification" 			: save_iqc_requalification(); break; 
			}
		}
	}
	function is_ajax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
	
	function reload_wbs_data() {
		require_once('../class/oop_notif.php');
		require_once('../class/oop_rolas.php');
		$username	= $_POST['username'];
		$array_fields = array('id', 'wbs_mr_id', 'invoice_no', 'item', 'item_desc', 'qty', 'box', 'box_qty', 'lot_no', 'location', 'supplier', 'drawing_num', 'iqc_status', 'is_printed', 'for_kitting', 'not_for_iqc', 'iqc_result', 'received_date', 'create_pg', 'create_user', 'update_pg', 'update_user', 'created_at', 'updated_at', 'judgement', 'ins_date', 'ins_time', 'ins_by', 'app_date', 'app_time', 'app_by', 'mat_batch_id', 'loc_batch_id');
		$table 	   	= 'tbl_wbs_inventory';
		$joins 	   	= '';
		$sql_where 	= 'WHERE lot_no IS NOT NULL AND id > '.return_last_id_rolas_wbs_inventory();
		$sql_order 	= '';
		$sql_limit 	= '';
		$tbody	 	= '';
		$result = WBS_TS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($result->num_rows == 0) {
			$tbody .= '<tr>';
			$tbody .= '	<td colspan="7"><center>No new record to be inserted!</center></td>';
			$tbody .= '</tr>';
		} else {
			while($row = mysqli_fetch_array($result)){
				//Check if already exists on record			
				$is_exists = check_wbs_existing_record($row['id']);
				if($is_exists == 0) {
					$tbody .= insert_rolas_wbs_inventory(replace_null_to_empty($row['id']), replace_null_to_empty($row['wbs_mr_id']), replace_null_to_empty($row['invoice_no']), replace_null_to_empty($row['item']), replace_null_to_empty($row['item_desc']), replace_null_to_empty($row['qty']), replace_null_to_empty($row['box']), replace_null_to_empty($row['box_qty']), replace_null_to_empty($row['lot_no']), replace_null_to_empty($row['location']), replace_null_to_empty($row['supplier']), replace_null_to_empty($row['drawing_num']), replace_null_to_empty($row['iqc_status']), replace_null_to_empty($row['is_printed']), replace_null_to_empty($row['for_kitting']), replace_null_to_empty($row['not_for_iqc']), replace_null_to_empty($row['iqc_result']), replace_null_to_empty($row['received_date']), replace_null_to_empty($row['create_pg']), replace_null_to_empty($row['create_user']), replace_null_to_empty($row['update_pg']), replace_null_to_empty($row['update_user']), replace_null_to_empty($row['created_at']), replace_null_to_empty($row['updated_at']), replace_null_to_empty($row['judgement']), replace_null_to_empty($row['ins_date']), replace_null_to_empty($row['ins_time']), replace_null_to_empty($row['ins_by']), replace_null_to_empty($row['app_date']), replace_null_to_empty($row['app_time']), replace_null_to_empty($row['app_by']), replace_null_to_empty($row['mat_batch_id']), replace_null_to_empty($row['loc_batch_id']), $username);
				} else {
					$tbody .= '<tr>';
					$tbody .= '	<td>'.$row['invoice_no'].'</td>';
					$tbody .= '	<td>'.$row['item'].'</td>';
					$tbody .= '	<td>'.$row['item_desc'].'</td>';
					$tbody .= '	<td>'.$row['lot_no'].'</td>';
					$tbody .= '	<td>'.$row['qty'].'</td>';
					$tbody .= '	<td>'.$row['supplier'].'</td>';
					$tbody .= '	<td style="color:red">Already Exists!</td>';
					$tbody .= '</tr>';
				}
			}	
		}
		$table  = '<table class="table table-bordered table-condensed" id="">';
		$table .= '	<thead>';
		$table .= '		<th>Invoice #</th>';
		$table .= '		<th>Part Code</th>';
		$table .= '		<th>Part Name</th>';
		$table .= '		<th>Lot #</th>';
		$table .= '		<th>Lot Qty</th>';
		$table .= '		<th>Supplier</th>';
		$table .= '		<th>Remarks</th>';
		$table .= '	</thead>';
		$table .= $tbody;
		$table .= '</table>';
		$return['table'] = $table;		
		echo json_encode($return);
	}
	
	function replace_null_to_empty($value) {
		if (is_null($value)) {
			 $value = "";
		} 
		return $value;
	}
	
	function return_last_id_rolas_wbs_inventory() {
		require_once('../class/oop_rolas.php');
		$array_fields = array('id');
		$table 	   	= 'tbl_wbs_inventory';
		$joins 	   	= '';
		$sql_where 	= '';
		$sql_order 	= 'ORDER BY id DESC';
		$sql_limit 	= 'LIMIT 0,1';
		$result 	= ROLAS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_assoc($result)) {
			return $row['id'];
		} else {
			return 1;
		}	
	}
	
	function check_wbs_existing_record($id) {
		require_once('../class/oop_rolas.php');
		$array_fields = array('id');
		$table 	   	= 'tbl_wbs_inventory';
		$joins 	   	= '';
		$sql_where 	= 'WHERE id = "'.$id.'"';
		$sql_order 	= '';
		$sql_limit 	= 'LIMIT 0,1';
		$result 	= ROLAS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($result->num_rows == 0) {
			return 0;
		} else {
			return 1;
		}	
	}
	
	function insert_rolas_wbs_inventory($id, $wbs_mr_id, $invoice_no, $item, $item_desc, $qty, $box, $box_qty, $lot_no, $location, $supplier, $drawing_num, $iqc_status, $is_printed, $for_kitting, $not_for_iqc, $iqc_result, $received_date, $create_pg, $create_user, $update_pg, $update_user, $created_at, $updated_at, $judgement, $ins_date, $ins_time, $ins_by, $app_date, $app_time, $app_by, $mat_batch_id, $loc_batch_id, $username) {
		require_once('../class/oop_rolas.php');
		$date_time_today = date('Y-m-d H:i:s');
		$table 			= 'tbl_wbs_inventory';
		$array_fields 	= array('id', 'wbs_mr_id', 'invoice_no', 'item', 'item_desc', 'qty', 'box', 'box_qty', 'lot_no', 'location', 'supplier', 'drawing_num', 'iqc_status', 'is_printed', 'for_kitting', 'not_for_iqc', 'iqc_result', 'received_date', 'create_pg', 'create_user', 'update_pg', 'update_user', 'created_at', 'updated_at', 'judgement', 'ins_date', 'ins_time', 'ins_by', 'app_date', 'app_time', 'app_by', 'mat_batch_id', 'loc_batch_id', 'lastupdate', 'username');
		$array_values 	= array($id, $wbs_mr_id, $invoice_no, $item, $item_desc, $qty, $box, $box_qty, $lot_no, $location, $supplier, $drawing_num, $iqc_status, $is_printed, $for_kitting, $not_for_iqc, $iqc_result, $received_date, $create_pg, $create_user, $update_pg, $update_user, $created_at, $updated_at, $judgement, $ins_date, $ins_time, $ins_by, $app_date, $app_time, $app_by, $mat_batch_id, $loc_batch_id, $date_time_today, $username);
		$insert_query 	= ROLAS::getInstance()->insert_query($table,$array_fields,$array_values);
		$script 	= ROLAS::getInstance()->insert_query_script($table,$array_fields,$array_values);
		$tbody  = '<tr>';
		$tbody .= '	<td>'.$invoice_no.'</td>';
		$tbody .= '	<td>'.$item.'</td>';
		$tbody .= '	<td>'.$item_desc.'</td>';
		$tbody .= '	<td>'.$lot_no.'</td>';
		$tbody .= '	<td>'.$qty.'</td>';
		$tbody .= '	<td>'.$supplier.'</td>';
		$tbody .= '	<td style="color:green">Successfully added!</td>';
		$tbody .= '</tr>';
		return $tbody;
	}
	
	function return_selected_requalification() {
		require_once('../class/oop_notif.php');
		$id_array	= implode(',', $_POST['ids']);
		$id_array	= explode(',', $id_array);
		$tbody	 	= '';
		for($i=0; $i<count($id_array);$i++) {
			$row = return_wbs_inventory_data($id_array[$i]);
			$tbody .= '<tr>';
			$tbody .= '	<td>'.$row['item'].'</td>';
			$tbody .= '	<td>'.$row['item_desc'].'</td>';
			$tbody .= '	<td>'.$row['supplier'].'</td>';
			$tbody .= '	<td>'.$row['qty'].'</td>';
			$tbody .= '	<td>'.$row['lot_no'].'</td>';
			// $tbody .= '	<td>'.$row['drawing_num'].'</td>';
			// $tbody .= '	<td>'.($row['judgement'].' '.$row['ins_by'].' '.$row['ins_date']).'</td>';
			// $tbody .= '	<td><button type="button" class="btn btn-default fa fa-remove" name="id[]" value='.$row['id'].'></button></td>';
			$tbody .= '</tr>';	
		}	
		$return['tbody'] = $tbody;		
		$return['row'] = $row;		
		echo json_encode($return);
	}
	
	function return_wbs_inventory_data($wbs_id) {
		require_once('../class/oop_notif.php');
		$array_fields = array('*');
		$table 	   	= 'tbl_wbs_inventory';
		$joins 	   	= '';
		$tbody	 	= '';
		$sql_order 	= '';
		$sql_limit 	= 'LIMIT 0,1';
		$script 	= '';
		$sql_where 	= 'WHERE id="'.$wbs_id.'"';
		$result  	= WBS_TS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$script    .= WBS_TS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$row = mysqli_fetch_array($result);
		return $row;
	}
	
	function get_po_list() {
        require_once('../class/oop_ypics4.php');
		$YPICS         	= new YPICS4_TS;
		$pattern 		= $_POST['pattern'];
		// $part_code 		= $_POST['part_code']; 
		$array_fields 	= array("TOP 10 SORDER");
		$table 			= "VRECE";
		$joins 			= "";
		$sql_where 		= "WHERE SORDER LIKE '%".$pattern."%'";
		$sql_order 		= "";
		$result = $YPICS->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
		$return = array();
		while($row = mssql_fetch_array($result)){
			$return['po_number'][] 			= $row['SORDER'];
		} 
		echo json_encode($return);
    }
	
	function get_po_details() {
        require_once('../class/oop_ypics4.php');
		$YPICS         	= new YPICS4_TS;
		$po_number 		= $_POST['po_number'];
		$array_fields 	= array("XRECE.CODE","XRECE.KVOL","XCUST.CNAME");
		$table 			= "XRECE";
		$joins 			= "INNER JOIN XCUST ON XRECE.CUST = XCUST.CUST";
		$sql_where 		= "WHERE XRECE.SORDER = '$po_number'";
		$sql_order 		= "";
		$result = $YPICS->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
		if($row = mssql_fetch_array($result)){
			$return['device_code']			= $row['CODE'];
			$return['po_qty']				= $row['KVOL'];
			$return['customer_name']		= $row['CNAME'];
		} else{
			$return['device_code'] 			= '';
			$return['po_qty'] 				= '0';
			$return['customer_name'] 		= '';
		}
		$array_fields 	= array("XHEAD.NAME","XITEM.DRAWING_NUM");
		$table 			= "XHEAD";
		$joins 			= "INNER JOIN XITEM ON XHEAD.CODE = XITEM.CODE";
		$sql_where 		= "WHERE XHEAD.CODE = '".$return['device_code']."'";
		$sql_order 		= "";
		$result = $YPICS->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
		if($row = mssql_fetch_array($result)){
			$return['device_name']			= $row['NAME'];
			$return['drawing_number']		= $row['DRAWING_NUM'];
		} else{
			$return['device_name'] 			= '';
			$return['drawing_number']		= '';
		}
		echo json_encode($return);
    }
	
	function get_invoice_num_list(){
        require_once('../class/oop_ypics4.php');
        $YPICS          = new YPICS4_TS;
        $pattern        = $_POST['pattern']; 
        // $array_fields   = array("TOP 10 INVOICE_NUM");
        $array_fields   = array("DISTINCT TOP 10 INVOICE_NUM"); //as of 10/02/2018
        $table          = "XSACT";
        $joins          = "";
        $sql_where      = "WHERE INVOICE_NUM LIKE '%$pattern%'";
        $sql_order      = "";
        $result         = $YPICS->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
        $html    		= '';
        while($row = mssql_fetch_array($result)){
			$html .= '<option value="'.$row['INVOICE_NUM'].'">'.$row['INVOICE_NUM'].'</option>';
        }
        $return['html'] = $html;
        echo json_encode($return);
     }

	function get_partname_by_partcode(){
        require_once('../class/oop_ypics4.php');
		$YPICS         	= new YPICS4_TS;
        $partcode        = $_POST['partcode']; 
        $array_fields   = array("TOP 1 NAME");
        $table          = "XHEAD";
        $joins          = "";
        $sql_where      = "WHERE CODE = '$partcode'";
        $sql_order      = "";
        $result         = $YPICS->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
        $html    		= '';
        $partname		= 'No record found';
		if($row = mssql_fetch_array($result)){
			$partname 	= $row['NAME'];
        }
        $return['partname'] = $partname;
        echo json_encode($return);
	}
	
	function return_partname_by_partcode($partcode){
        require_once('../class/oop_ypics4.php');
		$YPICS         	= new YPICS4_TS;
        $array_fields   = array("TOP 1 NAME");
        $table          = "XHEAD";
        $joins          = "";
        $sql_where      = "WHERE CODE = '$partcode'";
        $sql_order      = "";
        $result         = $YPICS->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
        $html    		= '';
        $partname		= 'No record found';
		if($row = mssql_fetch_array($result)){
			return $row['NAME'];
        } else {
			return '';
		}
	}
	 
	function save_whse_requalification() {
		require_once('../class/oop_rolas.php');
		$date_time_today = date('Y-m-d H:i:s');
		$id_array		 = implode(',', $_POST['ids']);
		$wbs_id		 	 = explode(',', $id_array);
		$username 		 = $_POST["username"];
		$msg			 = 'test';
		
		$table 		  	= 'tbl_requalification_main';
		$values		  	= get_fields_values($_POST,array("action","ids","device_name","username","whse_remarks","application_date","application_time"));
		$array_fields 	= $values["array_fields"];
		$array_values 	= $values["array_values"];
		$array_fields[] = "date_time_created"; 	$array_values[] = $date_time_today;
		$array_fields[] = "created_by"; 		$array_values[] = $username;
		$array_fields[] = "control_no"; 		$array_values[] = return_ctrl_no();
		$array_fields[] = "lastupdate"; 		$array_values[] = $date_time_today;
		$array_fields[] = "username"; 			$array_values[] = $username;
		$pkid 			= ROLAS::getInstance()->insert_query_id($table,$array_fields,$array_values);
		$script 		= ROLAS::getInstance()->insert_query_script($table,$array_fields,$array_values);
		
		$table = 'tbl_requalification_details';		
		for($i=0; $i<count($wbs_id); $i++) {
			$row = return_wbs_inventory_data($wbs_id[$i]);
			$array_fields   = array('date_time_created', 'created_by', 'status', 'tbl_requalification_main_id', 'tbl_wbs_inventory_id', 'parts_code', 'supplier', 'received_date', 'qty', 'lot_no', 'iqc_result','application_date', 'application_time','whse_remarks','lastupdate', 'username');
			$array_values   = array($date_time_today,$username, 'PENDING', $pkid, $wbs_id[$i], $row['item'], $row['supplier'], $row['received_date'], $row['qty'], $row['lot_no'], $row['judgement'].' '.$row['ins_by'].' '.$row['ins_date'],$_POST['application_date'],$_POST['application_time'], $_POST['whse_remarks'], $date_time_today, $username);
			$msg 			= ROLAS::getInstance()->insert_query($table,$array_fields,$array_values);
			$script		    .= ROLAS::getInstance()->insert_query_script($table,$array_fields,$array_values);

			/* Update tbl_wbs_inventory.status_rolas of ROLAS. Thus will not be included on selection */
			$table_inv   		= 'tbl_wbs_inventory';
			$array_fields_inv   = array('status_rolas','lastupdate', 'username');
			$array_values_inv   = array('USED', $date_time_today, $username);
			$sql_where_inv		= 'WHERE id='.$wbs_id[$i];
			$msg 				= ROLAS::getInstance()->update_query_detailed($table_inv,$array_fields_inv,$array_values_inv,$sql_where_inv);
			$script 		   .= ROLAS::getInstance()->update_query_detailed_script($table_inv,$array_fields_inv,$array_values_inv,$sql_where_inv);
		}
		
		
		
		$return['id_array']		= $id_array;
		$return['count']		= count($wbs_id);
		$return['msg']		= $msg;
		$return['script']	= $script;
		echo json_encode($return);
	}	
	 
	function return_ctrl_no() {
		require_once('../class/oop_rolas.php');
		$array_fields = array('control_no');
		$table 	   	= 'tbl_requalification_main';
		$joins 	   	= '';
		$sql_order 	= 'ORDER BY pkid DESC';
		$sql_limit 	= 'LIMIT 0,1';
		$script 	= '';
		$sql_where 	= 'WHERE control_no LIKE "%'.date('y-md-').'%" AND logdel=0';
		$result  	= ROLAS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$script    .= ROLAS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)) {
			$old_ctr = explode('-', $row['control_no']);
			$series  = end($old_ctr);
			$series  = str_pad(($series+1), 3, '0', STR_PAD_LEFT);
			
			$ctrl_no = 'TS-'.date('y-md-').$series;
		} else {
			$ctrl_no = 'TS-'.date('y-md-').'001';
		}
		return $ctrl_no;
	}
	 
	function return_requalification_details() {
		require_once('../class/oop_rolas.php');
		$main_pkid	= $_POST['main_pkid'];
		$det_pkid	= $_POST['details_pkid'];
		$array_fields = array('*');
		$table 	   	= 'tbl_requalification_main';
		$joins 	   	= '';
		$sql_order 	= '';
		$sql_limit 	= 'LIMIT 0,1';
		$script 	= '';
		$sql_where 	= 'WHERE pkid = "'.$main_pkid.'" AND logdel=0';
		$result  	= ROLAS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$script    .= ROLAS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)) {
			$row['whse_created_by'] = get_emp_name_by_username_systemone($row['created_by']);
			$return['data_main']	= $row;
		}
		
		$array_fields = array('*');
		$table 	   	= 'vw_requalification';
		// $table 	   	= 'tbl_requalification_details';
		$joins 	   	= '';
		$sql_order 	= '';
		$sql_limit 	= '';
		$script 	= '';
		$tbody 		= '';
		$sql_where 	= 'WHERE pkid = "'.$det_pkid.'"';
		$result  	= ROLAS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$script    .= ROLAS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		while($row = mysqli_fetch_array($result)) {
			$row['created_by'] 		= get_emp_name_by_username_systemone($row['created_by']);
			$row['inspector'] 		= get_emp_name_by_username_systemone($row['inspector']);
			$row['ww'] 		   		= $row['ww'] == '' ? return_current_workweek() : $row['ww'];
			$row['date_inspected'] 	= $row['date_inspected'] == '' ? date('Y-m-d') : $row['date_inspected'];
			$row['time_inspected'] 	= $row['time_inspected'] == '' ? date('H:i') : $row['time_inspected'];
			
			$return['data_details']	= $row;
			
			$tbody .= '<tr>';
			$tbody .= '	<td>'.$row['parts_code'].'</td>';
			$tbody .= '	<td>'.return_partname_by_partcode($row['parts_code']).'</td>';
			$tbody .= '	<td>'.$row['supplier'].'</td>';
			$tbody .= '	<td>'.$row['qty'].'</td>';
			$tbody .= '	<td>'.$row['lot_no'].'</td>';
			$tbody .= '	<td>'.($row['judgement'] == '' ? '<i>[Pending]</i>' : $row['judgement']).'</td>';
			$tbody .= '	<td>'.$row['whse_remarks'].'</td>';
			$tbody .= '<tr>';
		}
		$return['script']			= $script;
		$return['table_details']	= $tbody;
		echo json_encode($return);
	}	
	
	function return_inspection_matrix_value() {
		require_once('../class/oop_rolas.php');
		$severity	= $_POST['severity'];
		$aql		= $_POST['aql'];
		$level		= $_POST['level'];
		$array_fields = array('sample_size', 'accept', 'reject');
		$table 	   	= 'tbl_inspection_matrix';
		$joins 	   	= '';
		$sql_order 	= '';
		$sql_limit 	= 'LIMIT 0,1';
		$script 	= '';
		$sql_where 	= 'WHERE `severity` = "'.$severity.'" AND `aql` = "'.$aql.'" AND `level` = "'.$level.'" AND logdel=0';
		$result  	= ROLAS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$script    .= ROLAS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)) {
			$return['matrix_value']	= $row;
		} else {
			$row['sample_size'] = '';
			$row['accept'] 		= '';
			$row['reject'] 		= '';
			$return['matrix_value']	= $row;
		}
		$return['script']	= $script;
		echo json_encode($return);
	}	
	
	function get_family_list() {
		require_once('../class/oop_wbs_common.php');
		$array_fields = array('description');
		$table 	   	= 'tbl_mdropdowns';
		$joins 	   	= '';
		$sql_order 	= '';
		$sql_limit 	= '';
		$script 	= '';
		$sql_where 	= 'WHERE category=8';
		$result  	= WBSTSCOMMON::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$script    .= WBSTSCOMMON::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$option = '<option value="N/A">N/A</option>';
		$option .= '<option value="IC SOCKETS AND CONNECTORS">IC SOCKETS AND CONNECTORS</option>';
		while($row = mysqli_fetch_array($result)) {
			$option .= '<option value="'.$row['description'].'">'.$row['description'].'</option>';
		}
		$return['html_select'] = $option;
		echo json_encode($return);
	}	
	
	function get_aql_list() {
		require_once('../class/oop_wbs_common.php');
		$array_fields = array('description');
		$table 	   	= 'tbl_mdropdowns';
		$joins 	   	= '';
		$sql_order 	= '';
		$sql_limit 	= '';
		$script 	= '';
		$sql_where 	= 'WHERE category=18';
		$result  	= WBSTSCOMMON::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$script    .= WBSTSCOMMON::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$option = '<option value="N/A">N/A</option>';
		while($row = mysqli_fetch_array($result)) {
			if(is_numeric($row['description'])) {
				$row['description'] = number_format($row['description'], 2);
			} 
			$option .= '<option value="'.$row['description'].'">'.$row['description'].'</option>';
		}
		$return['html_select'] = $option;
		echo json_encode($return);
	}	
		 
	function return_current_workweek() {
		$start 	= strtotime( (date('m') < 4 ? date('Y', strtotime(date('Y').' -1 year')) : date('Y')) .'-04-01' );
		$end   	= strtotime( date('Y-m-d') );
		
		$iter 	= 24*60*60; // whole day in seconds
		$ww 	= 0; // keep a count of Sats & Suns

		for($i = $start; $i <= $end; $i=$i+$iter)
		{
			if(Date('D',$i) == 'Sat')
			{
				$ww++;
			} 
		}
		if(Date('D',$end) != 'Sat')
		{
			$ww++;
		} 
		return $ww;
		// $return['ww'] = $ww;
		// echo json_encode($return);
	}
	
	function return_current_fiscal_year() {
		$fiscal_year 	= strtotime( (date('m') < 4 ? date('Y', strtotime(date('Y').' -1 year')) : date('Y')) );
		$fiscal_year	= 'FY'.date('Y', $fiscal_year);
		return $fiscal_year;
	} 
	
	function save_iqc_requalification() {
		require_once('../class/oop_rolas.php');
		$date_time_today = date('Y-m-d H:i:s');
		$main_pkid		 = $_POST['main_pkid'];
		$det_pkid		 = $_POST['details_pkid'];
		$username 		 = $_POST["username"];
		$fy			 	 = return_current_fiscal_year();
		$msg			 = '';
		
		$array_fields = array('parts_code', 'lot_no');
		$table 	   	= 'tbl_requalification_details';
		$joins 	   	= '';
		$sql_order 	= '';
		$sql_limit 	= 'LIMIT 0,1';
		$script 	= '';
		$sql_where 	= 'WHERE pkid = "'.$det_pkid.'" AND tbl_requalification_main_id = "'.$main_pkid.'" AND logdel=0';
		$result  	= ROLAS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$script    .= ROLAS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)) {
			$array_fields = array('*');
			$table 	   	= 'tbl_requalification_details';
			$joins 	   	= '';
			$sql_order 	= '';
			$sql_limit 	= '';
			$script 	= '';
			$sql_where 	= 'WHERE parts_code = "'.$row['parts_code'].'" AND lot_no = "'.$row['lot_no'].'" AND tbl_requalification_main_id = "'.$main_pkid.'" AND logdel=0';
			$result  	= ROLAS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
			$script    .= ROLAS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
			while($row_update = mysqli_fetch_array($result)) {
				$array_fields   = array('status', 'family', 'application_date', 'application_time', 'type_inspection', 'aql', 'severity_inspection', 'accept', 'inspection_level', 'reject', 'date_inspected', 'lot_inspected','ww','fy','lot_accepted','time_inspected','sample_size','shift','submission','inspector','judgement','iqc_remarks','lastupdate', 'username');
				$array_values   = array('DONE', $_POST['family'], $_POST['application_date'], $_POST['application_time'], $_POST['type_inspection'], $_POST['aql'], $_POST['severity_inspection'], $_POST['accept'], $_POST['inspection_level'], $_POST['reject'], $_POST['date_inspected'], $_POST['lot_inspected'], $_POST['ww'], $fy, $_POST['lot_accepted'], $_POST['time_inspected'], $_POST['sample_size'], $_POST['shift'], $_POST['submission'], $username, $_POST['judgement'], $_POST['iqc_remarks'], $date_time_today, $username);
				$msg 			= ROLAS::getInstance()->update_query($table,$array_fields,$array_values, $row_update['pkid']);
				$script 		= ROLAS::getInstance()->update_query_script($table,$array_fields,$array_values, $row_update['pkid']);
			}
		}
		
		$return['POST']		= $_POST;
		$return['msg']		= $msg;
		$return['script']	= $script;
		echo json_encode($return);
	}	
	 
	 
	 
	 
	 
	 ?>