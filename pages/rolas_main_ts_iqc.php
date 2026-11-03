<?php
	require_once('../common_functions/get_username.php');
?>

<input type="hidden" id="hd_username" value="<?php echo $username; ?>"> 

<!--Main Container-->
<div id="main_container" style="margin-top:20px;">
	<div class="panel panel-success">
		<div class="panel-heading">TS-F3 Re-qualification of Old Lots Alert System</div>
		<div class="panel-body">
			<div class="row" style="padding-bottom:20px;">
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#for_requalification_iqc" aria-controls="for_requalification_iqc" role="tab" data-toggle="tab">For Re-qualification (IQC)</a></li>
					<li role="presentation" class=""><a href="#all_records_iqc" aria-controls="all_records_iqc" role="tab" data-toggle="tab">All Records (IQC)</a></li>
				</ul>
			</div>
			
			<div class="row" style="padding-bottom:20px;">
				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="for_requalification_iqc">
						<div class="col-sm-12">
							<div class="row" id="">
								<div class="col-sm-12">
									<table class="table table-bordered table-striped" id="tbl_for_requalification_iqc">
										<thead>
											<tr>
												<th>Control #</th>
												<th>Part Code</th>
												<th>Material Name</th>
												<th>Supplier/Vendor</th>
												<th>Received Date</th>
												<th>Quantity</th>
												<th>Press Lot No.</th>
												<th>Plating Lot No.</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane" id="all_records_iqc">
						<div class="col-sm-12">
							<div class="row" id="">
								<div class="col-sm-12">
									<table class="table table-bordered table-striped" id="tbl_all_iqc">
										<thead>
											<tr>
												<th>Control #</th>
												<th>Part Code</th>
												<th>Material Name</th>
												<th>Supplier/Vendor</th>
												<th>Received Date</th>
												<th>Quantity</th>
												<th>Press Lot No.</th>
												<th>Plating Lot No.</th>
												<th>IQC Judgment</th>
												<th></th>
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

<div class="modal fade" tabindex="-1" role="dialog" id="modal_new_for_application_iqc">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> TS-F3 Re-qualification IQC Inspection Result</h4>
      </div>
	  <form id="frm_new_for_application_iqc">
		  <div class="modal-body">
			<div class="row">
				<div class="col-sm-2">
					<label class="fa fa-md">Ctrl #: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="control_no" name="control_no" readonly>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Invoice #: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="invoice_no" name="invoice_no" readonly>
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Part Code: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="parts_code" name="parts_code" readonly>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Parts Name: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="partname" name="partname" readOnly>
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Lot No: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="lot_no" name="lot_no" readOnly>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Quantity: </label>
				</div>
				<div class="col-sm-4">
					<input type="number" class="form-control" id="total_qty" name="total_qty" readOnly>
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Application Date: </label>
				</div>
				<div class="col-sm-4">
					<input type="date" class="form-control" id="application_date" name="application_date" readOnly>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Application Time: </label>
				</div>
				<div class="col-sm-4">
					<input type="time" class="form-control" id="application_time" name="application_time" readOnly>
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Application by: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="created_by" name="created_by" readOnly>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Supplier: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="supplier" name="supplier" readOnly>
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Family: </label>
				</div>
				<div class="col-sm-4">
					<select class="form-control" id="family" name="family">
					</select>
				</div>
			</div>
			<br>
			<hr class="graph-orange" style="margin-top:0px;">
			<div class="profile-info-title h4" style="padding-top:2px;">
				<span class="fa fa-newspaper-o"></span> Sampling Plan
			</div>			
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Type of inspection: </label>
				</div>
				<div class="col-sm-4">
					<select class="form-control" id="type_inspection" name="type_inspection">
						<option value="">-</option>
						<option value="Single">Single</option>
						<option value="Double">Double</option>
						<option value="N/A">N/A</option>
					</select>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">AQL: </label>
				</div>
				<div class="col-sm-4">
					<select class="form-control" id="aql" name="aql">
					</select>
				</div>
			</div>			
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Severity of inspection: </label>
				</div>
				<div class="col-sm-4">
					<select class="form-control" id="severity_inspection" name="severity_inspection">
						<option value="">-</option>
						<option value="Normal">Normal</option>
						<option value="Tightened">Tightened</option>
						<option value="Reduced">Reduced</option>
						<option value="N/A">N/A</option>
					</select>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Accept: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="accept" name="accept">
				</div>
			</div>	
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Inspection level: </label>
				</div>
				<div class="col-sm-4">
					<select class="form-control" id="inspection_level" name="inspection_level">
						<option value="">-</option>
						<option value="S1">S1</option>
						<option value="S2">S2</option>
						<option value="S3">S3</option>
						<option value="II">II</option>
						<option value="N/A">N/A</option>
					</select>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Reject: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="reject" name="reject">
				</div>
			</div><br>
			<hr class="graph-orange">
			<div class="profile-info-title h4" style="padding-top:2px;">
				<span class="fa fa-search-plus"></span> Visual Inspection Result
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Date inspected: </label>
				</div>
				<div class="col-sm-4">
					<input type="date" class="form-control" id="date_inspected" name="date_inspected" value="<?php echo date('Y-m-d'); ?>" readOnly>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Lot inspected: </label>
				</div>
				<div class="col-sm-4">
					<input type="number" class="form-control" id="lot_inspected" name="lot_inspected">
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">WW#: </label>
				</div>
				<div class="col-sm-4">
					<input type="number" class="form-control" id="ww" name="ww" readOnly>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Lot accepted: </label>
				</div>
				<div class="col-sm-4">
					<input type="number" class="form-control" id="lot_accepted" name="lot_accepted">
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Time inspected: </label>
				</div>
				<div class="col-sm-4">
					<input type="time" class="form-control" id="time_inspected" name="time_inspected" value="<?php echo date('H:i'); ?>">
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Sample size: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="sample_size" name="sample_size">
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Shift: </label>
				</div>
				<div class="col-sm-4">
					<select class="form-control" id="shift" name="shift">
						<option value="">-</option>
						<option value="Shift A">Shift A</option>
						<option value="Shift B">Shift B</option>
					</select>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Submission: </label>
				</div>
				<div class="col-sm-4">
					<select class="form-control" id="submission" name="submission">
						<option value="">-</option>
						<option value="1st">1st</option>
						<option value="2nd">2nd</option>
						<option value="3rd">3rd</option>
					</select>
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Judgment: </label>
				</div>
				<div class="col-sm-4">
					<select class="form-control" id="judgement" name="judgement">
						<option value="">-</option>
						<option value="Accepted">Accepted</option>
						<option value="Rejected">Rejected</option>
					</select>
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Remarks: </label>
				</div>
				<div class="col-sm-10">
					<textarea class="form-control" id="iqc_remarks" name="iqc_remarks" ></textarea>
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

<div class="modal fade" tabindex="-1" role="dialog" id="modal_view_for_application_iqc">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-eye"></i> TS-F3 Re-qualification IQC Inspection Result</h4>
      </div>
	  <form id="frm_view_for_application_iqc">
		  <div class="modal-body">
			<div class="row">
				<div class="col-sm-2">
					<label class="fa fa-md">Ctrl #: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="control_no" name="control_no" >
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Invoice #: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="invoice_no" name="invoice_no" >
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Part Code: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="parts_code" name="parts_code" >
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Parts Name: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="partname" name="partname" >
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Lot No: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="lot_no" name="lot_no" >
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Quantity: </label>
				</div>
				<div class="col-sm-4">
					<input type="number" class="form-control" id="total_qty" name="total_qty" >
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Application Date: </label>
				</div>
				<div class="col-sm-4">
					<input type="date" class="form-control" id="application_date" name="application_date">
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Application Time: </label>
				</div>
				<div class="col-sm-4">
					<input type="time" class="form-control" id="application_time" name="application_time">
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">					
					<label class="fa fa-md">Application by: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="created_by" name="created_by" readOnly>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Supplier: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="supplier" name="supplier" >
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Family: </label>
				</div>
				<div class="col-sm-4">
					<select class="form-control" id="family" name="family">
					</select>
				</div>
			</div><br>
			<hr class="graph-orange" style="margin-top:0px;">
			<div class="profile-info-title h4" style="padding-top:2px;">
				<span class="fa fa-newspaper-o"></span> Sampling Plan
			</div>			
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Type of inspection: </label>
				</div>
				<div class="col-sm-4">
					<select class="form-control" id="type_inspection" name="type_inspection">
						<option value="">-</option>
						<option value="Single">Single</option>
						<option value="Double">Double</option>
						<option value="N/A">N/A</option>
					</select>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">AQL: </label>
				</div>
				<div class="col-sm-4">
					<select class="form-control" id="aql" name="aql">
					</select>
				</div>
			</div>			
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Severity of inspection: </label>
				</div>
				<div class="col-sm-4">
					<select class="form-control" id="severity_inspection" name="severity_inspection">
						<option value="">-</option>
						<option value="Normal">Normal</option>
						<option value="Tightened">Tightened</option>
						<option value="Reduced">Reduced</option>
						<option value="N/A">N/A</option>
					</select>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Accept: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="accept" name="accept">
				</div>
			</div>	
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Inspection level: </label>
				</div>
				<div class="col-sm-4">
					<select class="form-control" id="inspection_level" name="inspection_level">
						<option value="">-</option>
						<option value="S1">S1</option>
						<option value="S2">S2</option>
						<option value="S3">S3</option>
						<option value="II">II</option>
						<option value="N/A">N/A</option>
					</select>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Reject: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="reject" name="reject">
				</div>
			</div><br>
			<hr class="graph-orange">
			<div class="profile-info-title h4" style="padding-top:2px;">
				<span class="fa fa-search-plus"></span> Visual Inspection Result
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Date inspected: </label>
				</div>
				<div class="col-sm-4">
					<input type="date" class="form-control" id="date_inspected" name="date_inspected">
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Lot inspected: </label>
				</div>
				<div class="col-sm-4">
					<input type="number" class="form-control" id="lot_inspected" name="lot_inspected">
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">WW#: </label>
				</div>
				<div class="col-sm-4">
					<input type="number" class="form-control" id="ww" name="ww" >
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Lot accepted: </label>
				</div>
				<div class="col-sm-4">
					<input type="number" class="form-control" id="lot_accepted" name="lot_accepted">
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Time inspected: </label>
				</div>
				<div class="col-sm-4">
					<input type="time" class="form-control" id="time_inspected" name="time_inspected">
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Sample size: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="sample_size" name="sample_size" >
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Shift: </label>
				</div>
				<div class="col-sm-4">
					<select class="form-control" id="shift" name="shift">
						<option value="">-</option>
						<option value="Shift A">Shift A</option>
						<option value="Shift B">Shift B</option>
					</select>
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Submission: </label>
				</div>
				<div class="col-sm-4">
					<select class="form-control" id="submission" name="submission">
						<option value="">-</option>
						<option value="1st">1st</option>
						<option value="2nd">2nd</option>
						<option value="3rd">3rd</option>
					</select>
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Inspector: </label>
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="inspector" name="inspector">
				</div>
				<div class="col-sm-2">
					<label class="fa fa-md">Judgment: </label>
				</div>
				<div class="col-sm-4">
					<select class="form-control" id="judgement" name="judgement">
						<option value="">-</option>
						<option value="Accepted">Accepted</option>
						<option value="Rejected">Rejected</option>
					</select>
				</div>
			</div>
			<div class="row" style="padding-top:3px;">
				<div class="col-sm-2">
					<label class="fa fa-md">Remarks: </label>
				</div>
				<div class="col-sm-10">
					<textarea class="form-control" id="iqc_remarks" name="iqc_remarks" ></textarea>
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
  <div class="modal-dialog modal-md" role="document">
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
<script src="./js/rolas_ts_whse.js"></script>
