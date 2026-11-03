
$(document).ready(function(){
	var handler_ts = './handler/handler_ts.php';
	var username   = $('#hd_username').val();
   // Array holding selected row IDs
	var rows_selected = [];
	let dt_for_requalification_whse

	dt_for_requalification_whse = $('#tbl_for_requalification_whse').DataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "server_side_scripts/dt_rolas_ts_whse.php",
		"drawCallback": function( settings ) {
			$('#tbl_for_requalification_whse').attr('style','width:100%;');
		},
		'columnDefs': [{
			'targets': 0,
			'width':'1%',
			'className': 'dt-body-center',
			'render': function (data, type, full, meta){
				return '<input type="checkbox">';
			}
		}],
		'order': [1, 'asc'],
		'rowCallback': function(row, data, dataIndex){
			// Get row ID
			var rowId = data[0];

			// let date = new Date(data[9]); // date rcv old
			let date_rcv = new Date(data[6]); // date rcv
			let today = new Date(); // date today
			let date_req = new Date(data[7]); // date req

			
			if (data[9] != ''){
				let date_next_req = new Date(data[9]); // date next req

				if (date_next_req.toISOString().split('T')[0] > today.toISOString().split('T')[0]) {
					$(row).addClass('highlight_next');
				}else{
					$(row).addClass('highlight_fr');
				}

			}else{
				if (date_req.toISOString().split('T')[0] < today.toISOString().split('T')[0]) { /* updated by nessa 10202025 */
					$(row).addClass('highlight_fr');
				}else{
					$(row).addClass('highlight_nyr');
				}

			}
			
			// If row ID is in the list of selected row IDs
			if($.inArray(rowId, rows_selected) !== -1){
				$(row).find('input[type="checkbox"]').prop('checked', true);
				$(row).addClass('selected');
			}
		}
	});

	$('#frm_search').submit(function(e) {
		e.preventDefault(); // Prevent form submission
	
		let startDate = $('#txt_start_date').val();
		let endDate = $('#txt_end_date').val();
		let filterType = $('#cmb_type').val(); // Get selected filter type (REQUALIFICATION or RECEIVED)

		// let date = new Date(startDate);
		// 	date.setMonth(date.getMonth() - 6);
		// let newDate = date.toISOString().split('T')[0]; // Formats as YYYY-MM-DD
		// if(filterType == 'REQUALIFICATION'){
		// 	startDate = newDate
		// }

		dt_for_requalification_whse.ajax.url('server_side_scripts/dt_rolas_ts_whse.php?start_date=' + startDate + '&end_date=' + endDate + '&filter_type=' + filterType).load();	
	});

	var modal_new_for_application_whse 	= 'modal_new_for_application_whse';
	var frm_new_for_application_whse 	= 'frm_new_for_application_whse';
   
   // Handle click on checkbox
   $('#tbl_for_requalification_whse tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');

      // Get row data
      var data = dt_for_requalification_whse.row($row).data();

      // Get row ID
      var rowId = data[0];

      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, rows_selected);

      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         rows_selected.push(rowId);

      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         rows_selected.splice(index, 1);
      }

      if(this.checked){
         $row.addClass('selected');
      } else {
         $row.removeClass('selected');
      }
	  
      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#tbl_for_requalification_whse').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });
    
   // Handle form submission event 
   $('#frm-tbl_for_requalification_whse').on('submit', function(e){
      var form = this;

      // Iterate over all selected checkboxes
      $.each(rows_selected, function(index, rowId){
         // Create a hidden element 
         $(form).append(
             $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'id[]')
                .val(rowId)
         );
      });

      // FOR DEMONSTRATION ONLY     
      
      // Output form data to a console     
      $('#tbl_for_requalification_whse-console').text($(form).serialize());
      console.log("Form submission", $(form).serialize());
       
      // Remove added elements
      $('input[name="id\[\]"]', form).remove();
       
      // Prevent actual form submission
      e.preventDefault();
   });
   
   $('#'+frm_new_for_application_whse+' input[name="po_number"]').keyup(function(e){
		var key = e.which;
		if(key == 38 || key == 40){
			return false;
		}
		var pattern = $(this).val();
		fn_get_po_list(pattern,frm_new_for_application_whse+' #list_po_number');
	});
	
	$('#'+frm_new_for_application_whse+' #invoice_no').keyup(function(e){
		var key = e.which;
		if(key == 38 || key == 40){
			return false;
		}
		var pattern = $(this).val();
		fn_get_ng_invoice_num_datalist(pattern,frm_new_for_application_whse+' #list_invoice_no');
	});
	
	$('#'+frm_new_for_application_whse+' input[name="po_number"]').change(function(e){
		var po_number = $(this).val();
		fn_get_devicename_by_po(po_number,frm_new_for_application_whse);
	});
   
   $('#btn_reload_wbs').click(function() {
	   fn_reload_wbs_data();
   });
   
   $('#btn_new_requalify').click(function() {
	   if(rows_selected.length == 0) {
		    $('#container_message').attr('class','');
			$('#modal_system_message').modal();
			$('#container_message').html( 'No selected data!' );
	   } else {
		   console.log(rows_selected);
		   var serialized_data = $(this).serialize();
		   $('#'+frm_new_for_application_whse+' input,select').prop('required', true);
		   $('#'+frm_new_for_application_whse+' input,select').attr('autocomplete', 'off');
		   fn_return_selected_requalification(serialized_data, rows_selected);
	   }
	   
   });
   
   $('#'+frm_new_for_application_whse).submit(function(e) {
	   e.preventDefault();
	   var serialized_data = $(this).serialize();
	   var data = {
			"action"		: "save_whse_requalification",
			"pkid"			: 0,
			"ids"			: rows_selected,
			"username"		: username,
		}
	   fn_save_whse_requalification(data, serialized_data);
	   reload_datatables();
	   console.log(serialized_data);
   });
	
   function fn_reload_wbs_data() {
		var data = {
			"action" 		: "reload_wbs_data",
			"username" 		: username
		} 
		call_ajax(data, handler_ts, function(result){
			$('#container_message').attr('class','');
			$('#modal_system_message').modal();
			$('#container_message').html( result['table'] );
			reload_datatables();
		});
	}

   function fn_return_selected_requalification(serialized_data, rows_selected) {
	   $('#frm_new_for_application_whse table tbody').empty();
	   var data = {
			"action" 	: "return_selected_requalification",
			"ids"		: rows_selected
		} 
		call_ajax_serialize(data, serialized_data, handler_ts, function(result){	
			console.log(result);
			$('#frm_new_for_application_whse table tbody').append(result['tbody']);
			$('#'+modal_new_for_application_whse).modal('show');
		});
   }
   
   function fn_get_po_list(pattern,datalist_id){
		$('#'+datalist_id).empty(); 
		var data = {
			"action"	: "get_po_list",
			"pattern"	: pattern
		}
		call_ajax(data, handler_ts, function(result){
			// console.log(result);
			// $('#'+datalist_id).empty();
			for(x=0;x<result['po_number'].length;x++){
				$('#'+datalist_id).append('<option>'+result['po_number'][x]+'</option>');
			}
		});
	}

   function fn_get_devicename_by_po(po_number,frm_id){
		var data = {
			"action"	: "get_po_details",
			"po_number"	: po_number
		}
		call_ajax(data, handler_ts, function(result){
			$('#'+frm_id+' input[name="device_name"]').val(result['device_name']);
		});
	}
	
   function fn_get_partname_by_partcode(partcode,frm_id){
		var data = {
			"action"	: "get_partname_by_partcode",
			"partcode"	: partcode
		}
		call_ajax(data, handler_ts, function(result){
			$('#'+frm_id+' input[name="partname"]').val(result['partname']);
		});
	}
   
   function fn_get_ng_invoice_num_datalist(pattern,datalist_id){
	$('#'+datalist_id).empty(); 
	var data = {
		"action"	: "get_invoice_num_list",
		"pattern"	: pattern
	}
	call_ajax(data, handler_ts, function(result){
		console.log(result);
		$('#'+datalist_id).append(result['html']);
	});
}
      
   function fn_save_whse_requalification(data, serialized_data) {	   
	   call_ajax_serialize(data, serialized_data, handler_ts, function(result) {
		   rows_selected = [];
			$('.modal').modal('hide');
			$('#modal_system_message').modal();
			$('#container_message').attr('class','alert alert-success');
			$('#container_message').html( '<h4>'+result['msg']+'</h4>' );
			$('.btn').prop("disabled",false);
			reload_datatables();
			console.log(result);
		});
   }
   
   
   /* Functions and events for WHSE - All records*/
	var modal_view_for_application_whse 	= 'modal_view_for_application_whse';
	var frm_view_for_application_whse 	= 'frm_view_for_application_whse';
	
   var dt_all_whse = $('#tbl_all_records_whse').DataTable({
		"aaSorting"	: [],
		"bProcessing": true,
        "bServerSide": true,
		"sAjaxSource": "server_side_scripts/dt_rolas_ts_whse_all.php",
		rowGroup: {
			dataSrc: 0
		},
		"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false
            }
        ],
		"drawCallback": function( settings ) {
			$('#tbl_all_records_whse').attr('style','width:100%;');
		}
	});
	
	$('#tbl_all_records_whse tbody').on('click', 'tr .fa-eye', function() {
		var main_pkid 	 = $(this).data('main');
		var details_pkid = $(this).data('det');
		$('#'+modal_view_for_application_whse+' input, textarea').prop('readOnly', true);
		$('#'+modal_view_for_application_whse+' select').prop('disabled', true);
		$('#'+modal_view_for_application_whse).data('main', main_pkid);
		$('#'+modal_view_for_application_whse).data('det', details_pkid);
		fn_return_requalification_details(main_pkid, details_pkid, modal_view_for_application_whse, frm_view_for_application_whse, 'WHSE');
		$('#'+modal_view_for_application_whse).modal();
	});
   
   
   
	
   /* Functions and events for IQC - Add */
	var modal_new_for_application_iqc 	= 'modal_new_for_application_iqc';
	var frm_new_for_application_iqc 	= 'frm_new_for_application_iqc';
	
   var dt_for_requalification_iqc = $('#tbl_for_requalification_iqc').DataTable({
		"aaSorting"	: [],
		"bProcessing": true,
        "bServerSide": true,
		"sAjaxSource": "server_side_scripts/dt_rolas_ts_iqc.php",
		rowGroup: {
			dataSrc: 0
		},
		"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false
            }
        ],
		"drawCallback": function( settings ) {
			$('#tbl_for_requalification_iqc').attr('style','width:100%;');
		}
	});
	
	$('#tbl_for_requalification_iqc tbody').on('click', 'tr .fa-plus', function() {
		var main_pkid 	 = $(this).data('main');
		var details_pkid = $(this).data('det');
		$('#'+modal_new_for_application_iqc).data('main', main_pkid);
		$('#'+modal_new_for_application_iqc).data('det', details_pkid);
		fn_return_current_workweek(frm_new_for_application_iqc);
	    $('#'+frm_new_for_application_iqc+' input,select').prop('required', true);
	    $('#'+frm_new_for_application_iqc+' input,select').attr('autocomplete', 'off');
		fn_return_requalification_details(main_pkid, details_pkid, modal_new_for_application_iqc, frm_new_for_application_iqc, 'IQC');
		$('#'+modal_new_for_application_iqc).modal();
	});
	
	$('#'+frm_new_for_application_iqc+' select[name="severity_inspection"]').change(function() {
		var severity = $('#'+frm_new_for_application_iqc+' select[name="severity_inspection"]').val();
		var aql 	 = $('#'+frm_new_for_application_iqc+' select[name="aql"]').val();
		var level 	 = $('#'+frm_new_for_application_iqc+' select[name="inspection_level"]').val();
		fn_return_inspection_matrix_value(modal_new_for_application_iqc, severity, aql, level);
	});
	
	$('#'+frm_new_for_application_iqc+' select[name="aql"]').change(function() {
		var severity = $('#'+frm_new_for_application_iqc+' select[name="severity_inspection"]').val();
		var aql 	 = $('#'+frm_new_for_application_iqc+' select[name="aql"]').val();
		var level 	 = $('#'+frm_new_for_application_iqc+' select[name="inspection_level"]').val();
		fn_return_inspection_matrix_value(modal_new_for_application_iqc, severity, aql, level);
	});
	
	$('#'+frm_new_for_application_iqc+' select[name="inspection_level"]').change(function() {
		var severity = $('#'+frm_new_for_application_iqc+' select[name="severity_inspection"]').val();
		var aql 	 = $('#'+frm_new_for_application_iqc+' select[name="aql"]').val();
		var level 	 = $('#'+frm_new_for_application_iqc+' select[name="inspection_level"]').val();
		fn_return_inspection_matrix_value(modal_new_for_application_iqc, severity, aql, level);
	});
	
	$('#'+frm_new_for_application_iqc).on('submit',function(e) {
	   e.preventDefault();
	   var serialized_data = $(this).serialize();
	   var data = {
			"action"		: "save_iqc_requalification",
			"main_pkid"	 	: $('#'+modal_new_for_application_iqc).data('main'),
			"details_pkid"	: $('#'+modal_new_for_application_iqc).data('det'),
			"username"		: username,
		}
	   fn_save_iqc_requalification(data, serialized_data);
	   console.log(serialized_data);
    });
	
	function fn_return_inspection_matrix_value(modal_id, severity, aql, level){
		var data = {
			"action"	: "return_inspection_matrix_value",
			"severity"	: severity,
			"aql"		: aql,
			"level"		: level
		}
		call_ajax(data, handler_ts, function(result){
			console.log(result);
			$.each(result['matrix_value'], function(key, value) {
				$( '#' + modal_id + ' form input[name="' + key + '"]' ).val(value);
			});
		});
	}
	
	function fn_get_aql_list(frm_id){
		$('#'+frm_id+' select[name="aql"]').empty(); 
		var data = {
			"action"	: "get_aql_list"
		}
		call_ajax(data, handler_ts, function(result){
			$('#'+frm_id+' select[name="aql"]').append(result['html_select']);
		});
	}
	
	function fn_get_family_list(frm_id){
		$('#'+frm_id+' select[name="family"]').empty(); 
		var data = {
			"action"	: "get_family_list"
		}
		call_ajax(data, handler_ts, function(result){
			$('#'+frm_id+' select[name="family"]').append(result['html_select']);
		});
	}
	
   function fn_return_current_workweek(frm_id) {
		var data = {
			"action"	: "return_current_workweek"
		}
		call_ajax(data, handler_ts, function(result){console.log(result);
			$('#'+frm_id+' input[name="ww"]').val(result['ww']);
		}); 
   }
   
   function fn_return_requalification_details(main_pkid, details_pkid, modal_id, frm_id, user) {
		fn_get_aql_list(frm_id);
		fn_get_family_list(frm_id);
		var data = {
			"action" 		: "return_requalification_details",
			"main_pkid"	 	: main_pkid,
			"details_pkid"	: details_pkid
		}
		call_ajax( data, handler_ts, function(result) {
			console.log(result);
			$.each(result['data_main'], function(key,value) {
				$( '#' + modal_id + ' form input[name="' + key + '"]' ).val(value);
				$( '#' + modal_id + ' form select[name="' + key + '"]' ).val(value);
				$( '#' + modal_id + ' form textarea[name="' + key + '"]' ).val(value);
				
				if(key == 'po_number') {
					fn_get_devicename_by_po(value,frm_id);
				}
				
			});
			if(user == 'WHSE') {
				$('#'+frm_id+' table tbody').empty();
				$('#'+frm_id+' table tbody').append(result['table_details']);
				$( '#' + modal_id + ' form input[name="application_date"]' ).val(result['data_details']['application_date']);
				$( '#' + modal_id + ' form input[name="application_time"]' ).val(result['data_details']['application_time']);
			} else if(user == 'IQC') {
				$.each(result['data_details'], function(key,value) {
					$( '#' + modal_id + ' form input[name="' + key + '"]' ).val(value);
					$( '#' + modal_id + ' form select[name="' + key + '"]' ).val(value);
					$( '#' + modal_id + ' form textarea[name="' + key + '"]' ).val(value);
					
					if(key == 'parts_code') {
						fn_get_partname_by_partcode(value,frm_id);
					}
				});
			} 
		});
	}
   
   function fn_save_iqc_requalification(data, serialized_data) {	   
	   call_ajax_serialize(data, serialized_data, handler_ts, function(result) {
			$('.modal').modal('hide');
			$('#modal_system_message').modal();
			$('#container_message').attr('class','alert alert-success');
			$('#container_message').html( '<h4>'+result['msg']+'</h4>' );
			$('.btn').prop("disabled",false);
			reload_datatables();
			console.log(result);
		});
   }
   
   function reload_datatables() {	   
	   dt_for_requalification_iqc.ajax.reload(null, false);
	   dt_all_iqc.ajax.reload(null, false);
	   dt_for_requalification_whse.ajax.reload(null, false);
	   dt_all_whse.ajax.reload(null, false);
   }
   
   /* Functions and events for IQC - All records*/
	var modal_view_for_application_iqc 	= 'modal_view_for_application_iqc';
	var frm_view_for_application_iqc 	= 'frm_view_for_application_iqc';
	
   var dt_all_iqc = $('#tbl_all_iqc').DataTable({
		"aaSorting"	: [],
		"bProcessing": true,
        "bServerSide": true,
		"sAjaxSource": "server_side_scripts/dt_rolas_ts_iqc_all.php",
		rowGroup: {
			dataSrc: 0
		},
		"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false
            }
        ],
		"drawCallback": function( settings ) {
			$('#tbl_all_iqc').attr('style','width:100%;');
		}
	});
	
	$('#tbl_all_iqc tbody').on('click', 'tr .fa-eye', function() {
		var main_pkid 	 = $(this).data('main');
		var details_pkid = $(this).data('det');
		$('#'+modal_view_for_application_iqc+' input, textarea').prop('readOnly', true);
		$('#'+modal_view_for_application_iqc+' select').prop('disabled', true);
		$('#'+modal_view_for_application_iqc).data('main', main_pkid);
		$('#'+modal_view_for_application_iqc).data('det', details_pkid);
		fn_return_requalification_details(main_pkid, details_pkid, modal_view_for_application_iqc, frm_view_for_application_iqc, 'IQC');
		$('#'+modal_view_for_application_iqc).modal();
	});
	
});

