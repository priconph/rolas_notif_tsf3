<?php
	/*
	 * Script:    DataTables server-side script for PHP and MySQL
	 * Copyright: 2010 - Allan Jardine
	 * License:   GPL v2 or BSD (3-point)
	 */
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	// error_reporting(E_ALL);
	// ini_set('display_errors', 1);
	/* 
			Invoice #			Application Date		Inspection Date	
			Inspection 			Time					FY #	
			WW #				Sub						Part Code	
			Part Name			Supplier				Lot #	
			AQL					Judgement
	
	*/
	$aColumns = array( 
				'id', 
				'item', 
				'item_desc', 
				'supplier', 
				'qty', 
				'box_qty', 
				'lot_no', 
				'received_date', 
				'DATE_ADD(received_date, INTERVAL 6 MONTH) AS requali_date'
				);
	
	/* used this field for searching data typed in the search box */
	// $array_search = array('empno', 'LastName',
							// 'date', 'date_out');
	// $array_search = array('empno', 'LastName');
							
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "id";
	
	/* DB table to use */
	$sTable = "vw_wbs_inventory";
	
	$database_config = '../db_config/seiko_wbs_ts.php';
	
	if(!file_exists($database_config)){
		echo "config file does not exist!";
		exit;
	} else {
		require_once($database_config);
	}
	
	/* Database connection information */
	$gaSql['user']       = $username;
	$gaSql['password']   = $password;
	$gaSql['db']         = $db_name;
	$gaSql['server']     = $server;
	
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
	 * no need to edit below this line
	 */
	
	/* 
	 * MySQL connection
	 */
	$gaSql['link'] =  mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) or
		die( 'Could not open connection to server' );
	
	mysql_select_db( $gaSql['db'], $gaSql['link'] ) or 
		die( 'Could not select database '. $gaSql['db'] );
	
	
	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
			mysql_real_escape_string( $_GET['iDisplayLength'] );
	}
		
	/*
	 * Ordering
	 */
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
					".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	 
	 /* wag gagalawin yung sWhere default to para sa searchbox ng datatables check mo sa baba kung pano gagawin mo :) */
	 
	$sWhere = "";
	if ( $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if( (strpos($aColumns[$i]," as ") !== false) OR (strpos($aColumns[$i]," AS ") !== false) ){
				$modified_search = explode(" ",$aColumns[$i]);
				$modified_search_reduced = array();
				for($x=0;$x< (count($modified_search) -2); $x++){
					$modified_search_reduced[] = $modified_search[$x];
				}
				$sWhere .= implode(" ",$modified_search_reduced)." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
			} else{
				$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
			}
		}
		$sWhere = substr_replace($sWhere, '', -3);
		$sWhere .= ')';
	}else{
		$default_query = "WHERE `item` <> 'NULL' and `item_desc` <> 'NULL' ";
		$between_query = "BETWEEN '".$_GET['start_date']."' AND '".$_GET['end_date']."'";

		if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
			if (isset($_GET['filter_type']) && $_GET['filter_type'] == 'REQUALIFICATION') {
				$sWhere = $default_query . " AND (DATE_ADD(received_date, INTERVAL 6 MONTH) $between_query)";
			} else {
				$sWhere = $default_query . " AND (received_date $between_query)";
			}
		} else {
			$sWhere = $default_query;
		}
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
	}
	
	// // $default_query = "WHERE qty > 0 AND deleted = '0' AND received_date != '' AND (`item_desc` LIKE '%ct%' OR `item_desc` LIKE '%CONTACT%')";
	// // $default_query = "WHERE qty > 0 AND deleted = '0' AND received_date != '' ";

	$sOrder = 'ORDER BY requali_date DESC';

	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
	";
	$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
		
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.")
		FROM   $sTable
	";
	$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	$aResultTotal = mysql_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];
	
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);

	while ( $aRow = mysql_fetch_array( $rResult ) )
	{
		$row = array();
		unset($row);
		
		$row[] = $aRow['id'];
		$row[] = $aRow['item'];
		$row[] = $aRow['item_desc'];
		$row[] = $aRow['supplier'];
		// $row[] = $aRow['qty']; //rcv qty
		// $row[] = $aRow['qty']; // issuance qty
		$row[] = $aRow['qty']; // remaining qty
		$row[] = $aRow['lot_no'];
		$row[] = $aRow['received_date'];
		$row[] = $aRow['requali_date'];
		$row[] = get_lastrequali($aRow['lot_no']);

		if (get_lastrequali($aRow['lot_no']) == ''){
			$row[] = '';
		}else{
			$row[] = date('Y-m-d', strtotime('+6 months', strtotime(get_lastrequali($aRow['lot_no']))));
		}

		array_push($output['aaData'],$row);
	}
	
	echo json_encode( $output );
	
	function get_lastrequali($lot_no){
        require_once('../class/oop_rolas.php');
        $return             = $_POST;
        $result             = '';
        $array_fields       = array('*');
        $table              = 'tbl_requalification_details';
        $joins              = '';
        $sql_where          = 'WHERE lot_no="'.$lot_no.'"';
        $sql_order          = 'ORDER BY pkid DESC';
        $sql_limit          = 'LIMIT 0,1';
        $result             = ROLAS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
        
		if($row = mysqli_fetch_array($result)){
			$app_date = $row['application_date'];
			return $app_date;
        } else {
			return '';
		}
    }


?>