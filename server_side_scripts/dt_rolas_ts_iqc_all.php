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
				'pkid', 
				'status', 
				'tbl_requalification_main_id', 
				'control_no', 
				'parts_code', 
				'supplier', 
				'received_date', 
				'total_qty', 
				'lot_no', 
				'received_date', 
				'judgement',
				'iqc_result'
				);
	
	/* used this field for searching data typed in the search box */
	// $array_search = array('empno', 'LastName',
							// 'date', 'date_out');
	// $array_search = array('empno', 'LastName');
							
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "pkid";
	
	/* DB table to use */
	// $sTable = "tbl_wbs_material_receiving_batch";
	$sTable = "vw_requalification";
	
	$database_config = '../db_config/config_rolas.php';
	
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
	
	/* 
		dito ka mag add ng where mo, una check mo kung may laman na yung $sWhere pag wala append mo yung where mo na meron
		kasama where kapag naman may laman na AND na syempre diba :)
	*/
	// if($sWhere == ""){
		// $sWhere .= "WHERE ";
	// }else{
		// $sWhere .= "AND ";
	// }
	
	// $sWhere .= "status = 'DONE'";
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
	
	// echo "limit: ".$sLimit." : ".$sQuery."<hr>";
	// echo $query_used = $sQuery;
	
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
		
		$button = '<center><button type="button" class="btn btn-default fa fa-eye" id="btn_view" data-main="'.$aRow['tbl_requalification_main_id'].'" data-det="'.$aRow['pkid'].'" value="'.$aRow['pkid'].'" style="width:70px;"> View</button></center>';	
				
		$lot_no_array 	= explode('/',$aRow['lot_no']);
		$press_lot_no 	= $lot_no_array[0];
		$plating_lot_no = $lot_no_array[1] == '' ? 'N/A' : $lot_no_array[1];
		$judgement 		= $aRow['judgement'] == 'Rejected' ? '<font color="red">'.$aRow['judgement'].'</font>' : $aRow['judgement'];
		
		$row[] = $aRow['control_no'];
		$row[] = $aRow['parts_code'];
		$row[] = get_partname_by_partcode($aRow['parts_code']);
		$row[] = $aRow['supplier'];
		$row[] = $aRow['received_date'] == '' ? '' : date('M d, Y', strtotime($aRow['received_date']));
		$row[] = $aRow['total_qty'];
		$row[] = $press_lot_no;
		$row[] = $plating_lot_no;
		$row[] = $judgement == '' ? '<i>[Pending]</i>' : $judgement;
		$row[] = $button;
		array_push($output['aaData'],$row);
	}
	
	function get_partname_by_partcode($partcode){
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
	
	echo json_encode( $output );
?>