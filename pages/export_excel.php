<?php
// mkdir('../exported_excel/', 0777, true);
require_once('../class/oop_notif.php');
$excel_class = '../class/excel.php';
if(file_exists($excel_class)){
	include($excel_class);
}else{
	echo 'File '.$excel_class.' does not exist';
	exit;
}

$excel = new EXCEL;
/* set title */
$excel->title = 'sample';
/* add a new page */
$excel->add_page();
/* use the default font style */
$excel->set_default_font_style();
/* select an active page */
$excel->set_active_sheet(0);

/* set width - Array Cells, Array Width */
$array_cell = array('A','B','C','D', 'E', 'F');
$array_width = array('5','30','50','30', '15', '15');
$excel->set_width($array_cell,$array_width);

/* set height - Array Rows, Array Height */
// $array_row = array('2','3');
// $array_height = array(25,50);
// $excel->set_height($array_row,$array_height);

/* borders - Cell, Left, Right, Top, Bottom */
// $excel->set_borders('A2',1,1,1,1);
// $excel->set_borders('B2:B3',1,1,1,1);
/* set file name */

/* set font - cell, bold, color, size, style */
$excel->set_font('A1',true,'',14,'Verdana');

/* place value - cell, value, type(string or blanl), alignment, wordwrap(true or false) */
$excel->place_value('A3','#','string','',true);
$excel->place_value('B3','Item','string','',true);
$excel->place_value('C3','Item Description','string','',true);
$excel->place_value('D3','Lot #','string','',true);
$excel->place_value('E3','Received Date','string','',true);
$excel->place_value('F3','Requalification Date','string','',true);

$ctr = 1;
$row_ctr = 4;
$table  		= "tbl_wbs_material_receiving_batch";
$array_fields	= array("item","`item_desc`","lot_no","received_date","DATE_ADD(received_date, INTERVAL 6 MONTH) as requali_date");
$joins  	 	= "";
// $sql_where  	= "WHERE DATE_ADD(received_date, INTERVAL 6 MONTH) <= '".date('Y-m-d')."'";
// $sql_where  	= "WHERE (item_desc LIKE '%ct%' OR item_desc LIKE '%CONTACT%') AND DATE_ADD(received_date, INTERVAL 6 MONTH) BETWEEN '2017-09-01' AND '".date('Y-m-d')."'";
$sql_where  	= "WHERE (`item_desc` LIKE '%ct%' OR `item_desc` LIKE '%CONTACT%') AND DATE_ADD(received_date, INTERVAL 6 MONTH) <= '".date('Y-m-d')."'";
// $sql_where  	= "";
// $sql_order  	= "ORDER BY id DESC";
$sql_order  	= "ORDER BY id ";
$sql_limit  	= "";
// $sql_limit  	= "LIMIT 0,1000";
$result        	= WBS_TS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);

$table = '';
$tbody = '';

while($row = mysqli_fetch_assoc($result)){
	$excel->place_value('A'.$row_ctr,$result->num_rows,'string','',true);
	// $excel->place_value('A'.$row_ctr,$ctr++,'string','',true);
	// $excel->place_value('B'.$row_ctr,$row['item'],'string','',true);
	// $excel->place_value('C'.$row_ctr,$row['item_desc'],'string','',true);
	// $excel->place_value('C'.$row_ctr, mb_convert_encoding($row['item_desc'],'utf-16','utf-8'),'string','',true);
	// $excel->place_value('D'.$row_ctr,$row['lot_no'],'string','',true);
	// $excel->place_value('E'.$row_ctr,date('M d, Y', strtotime($row['received_date'])),'string','',true);
	// $excel->place_value('F'.$row_ctr,date('M d, Y', strtotime($row['requali_date'])),'string','',true);
	// $row_ctr++;
}	

$filename = "sample"; 
/* output excel - filename, excel version (2003,2007) */
$excel->output($filename,'2007');