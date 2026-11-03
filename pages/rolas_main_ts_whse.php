<?php
	require_once('../common_functions/get_username.php');
?>

<input type="hidden" id="hd_username" value="<?php echo $username; ?>"> 

<style>
	.highlight_fr {
		background-color: #f5b7b1; /* ref */
	}

	.highlight_next {
		background-color: #abebc6; /* green */
	}

	.highlight_nyr {
		background-color: #c7edff; /* blue */
	}

	.table-row{
		pointer-events: none;
	}

	.checkbox-row {
		display: none;
	}
</style>

<!--Main Container-->
<div id="main_container" style="margin-top:20px;">
	<div class="panel panel-success">
		<div class="panel-heading">TS-F3 Re-qualification of Old Lots Alert System</div>
		<div class="panel-body">
			<div class="row" style="padding-bottom:20px;">
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#for_requalification_whse" aria-controls="for_requalification_whse" role="tab" data-toggle="tab">For Re-qualification (Whse)</a></li>
					<li role="presentation" class=""><a href="#all_records_whse" aria-controls="all_records_whse" role="tab" data-toggle="tab">All Records (Whse)</a></li>
				</ul>
			</div>
			
			<div class="row" style="padding-bottom:20px;">
				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="for_requalification_whse">
						<div class="col-sm-12">
							<div class="row" style="padding-bottom:10px;">
								<form id="frm_search">
									<div class="col-sm-1">
										<h3>Search Date: </h3>
									</div>
									<div class="col-sm-2">
										<select class="form-control" id="cmb_type" required>
											<option value="" selected disabled> --- Select Category ---</option>
											<option value="RECEIVED">By Received Date</option>
											<option value="REQUALIFICATION">By Re-qualification Date</option>
										</select>
									</div>
									<div class="col-sm-2">
										<input type="date" class="form-control" id="txt_start_date" required>
									</div>
									<div class="col-sm-2">
										<input type="date" class="form-control" id="txt_end_date" required>
									</div>
									<div class="col-sm-5">
										<div style="position:relative;">
											<button type="submit" class="btn btn-primary btn-md fa fa-search" id="btn_search"> Search</button>
										</div>
									</div>
								</form>
							</div>
							<div class="col-sm-12">
								<label><b>LEGEND:</b></label>
								<div class="row">
									<div class="col-sm-1">
										<label style="background-color:#f5b7b1; width:50%;height:25px;"></label>
										<label>For Re-qualification </label>
									</div>
									<div class="col-sm-1">
										<label style="background-color:#abebc6; width:50%;height:25px;"></label>
										<label>IQC Re-qualification - OK</label>
									</div>
									<div class="col-sm-1">
										<label style="background-color:#c7edff; width:50%;height:25px;"></label>
										<label>Not yet required</label>
									</div>
									<div class="col-sm-1">
										<button class="btn btn-success btn-md fa fa-plus" id="btn_new_requalify"> Re-qualify Lot/s!</button>
									</div>
									

								
									
								</div>
							</div>

							<div class="row" style="padding-bottom:10px;">
								<div class="col-sm-12">									
								</div>
							</div>
							<div class="row" id="">
								<div class="col-sm-12">
									<table class="table table-bordered" id="tbl_for_requalification_whse">
										<thead>
											<tr>
												<th></th>
												<th>Part Code</th>
												<th>Material Name</th>
												<th>Supplier/Vendor</th>
												<!-- <th>Received Qty.</th> -->
												<!-- <th>Issuance Qty.</th> -->
												<th>Remaining Qty.</th>
												<th>Lot Number/s</th>
												<th>Received Date</th>
												<!-- <th>Press Date</th>
												<th>Plate Date</th> -->
												<th>Re-qualification Date</th>
												<th>Last Re-qualification Date</th>
												<th>Next Re-qualification Date</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane" id="all_records_whse">
						<div class="col-sm-12">
							<div class="row" id="">
								<div class="col-sm-12">
									<table class="table table-bordered table-striped" id="tbl_all_records_whse">
										<thead>
											<tr>
												<th>Ctrl #</th>
												<th>Part Code</th>
												<th>Material Name</th>
												<th>Supplier/Vendor</th>
												<th>Quantity</th>
												<th>Lot Number/s</th>
												<th>Received Date</th>
												<th>Re-qualification Date</th>
												<th>IQC Judgment</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- <div class="panel-footer"> <center><i>ROLAS date released: September 2018 </i></center></div> -->
	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal_new_for_application_whse">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> New Application for IQC Inspection!</h4>
      </div>
	  <form id="frm_new_for_application_whse" method="post">
		  <div class="modal-body">
			<div class="row">
				<div class="col-sm-2 col-sm-offset-6">
					<label class="fa fa-md">Date: </label>
				</div>
				<div class="col-sm-4">
					<input type="date" class="form-control" id="application_date" name="application_date" value="<?php echo date('Y-m-d'); ?>" required readOnly>
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Invoice #: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="invoice_no" name="invoice_no" list="list_invoice_no" required>
                    <datalist id="list_invoice_no"></datalist>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Time: </label>
				</div>
				<div class="col-sm-4">
					<input type="time" class="form-control" id="application_time" name="application_time" value="<?php echo date('H:i'); ?>" required>
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">PO #: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="po_number" name="po_number" list="list_po_number" required>
                    <datalist id="list_po_number"></datalist>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Series Name: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="device_name" name="device_name" readOnly required>
				</div>
			</div>
			<div class="row" style="padding-top:5px;">
				<div class="col-sm-12">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Part Code</th>
								<th>Material Name</th>
								<th>Supplier/Vendor</th>
								<th>Quantity</th>
								<th>Lot Number/s</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>			
			</div>
			<div class="row">
				<div class="col-sm-2">
					<label class="fa fa-md">Remarks: </label>
				</div>
				<div class="col-sm-10">
					<textarea class="form-control" id="whse_remarks" name="whse_remarks" ></textarea>
				</div>				
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="submit" class="btn btn-success fa fa-save"> Submit</button>
			<button type="button" class="btn btn-default fa fa-close" data-dismiss="modal"> Close</button>
		  </div>
	  </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="modal_view_for_application_whse">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-eye"></i> View Application for IQC Inspection</h4>
      </div>
	  <form id="frm_view_for_application_whse" method="post">
		  <div class="modal-body">
			<div class="row">
				<div class="col-sm-2">
					<label class="fa fa-md">Ctrl #: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="control_no" name="control_no" readOnly>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Date: </label>
				</div>
				<div class="col-sm-4">
					<input type="date" class="form-control" id="application_date" name="application_date" required>
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Invoice #: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="invoice_no" name="invoice_no" list="list_invoice_no">
                    <datalist id="list_invoice_no"></datalist>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Time: </label>
				</div>
				<div class="col-sm-4">
					<input type="time" class="form-control" id="application_time" name="application_time">
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">PO #: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="po_number" name="po_number" list="list_po_number">
                    <datalist id="list_po_number"></datalist>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Series Name: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="device_name" name="device_name" readOnly required>
				</div>
			</div>
			<div class="row" style="padding-top:5px;">
				<div class="col-sm-12">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Part Code</th>
								<th>Material Name</th>
								<th>Supplier/Vendor</th>
								<th>Quantity</th>
								<th>Lot Number/s</th>
								<th>IQC Result</th>
								<th>Remarks</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>			
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Prepared by: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="whse_created_by" name="whse_created_by">
				</div>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default fa fa-close" data-dismiss="modal"> Close</button>
		  </div>
	  </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="modal_system_message" style="z-index:1051">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-question-circle-o"></i> System Confirmation</h4>
      </div>
	  <div class="modal-body">
		<div class="row">
			<div class="col-sm-12" id="">				
				<div class="alert alert-success" role="alert" id="container_message">					
				</div>
			</div>
		</div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default fa fa-close" data-dismiss="modal" id="btn_close"> Close</button>
	  </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script></script>
<script src="./js/rolas_ts_whse.js?n=1234">

</script>
