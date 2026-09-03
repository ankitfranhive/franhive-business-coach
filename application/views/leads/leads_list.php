<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>

<div class="main-container">
	<div class="pd-ltr-20 xs-pd-20-10">
		<div class="min-height-200px">
			<div class="page-header">
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<div class="title">
							<h4>Listing of all Leads</h4>
						</div>
					</div>
					<div class="col-md-6 col-sm-12 text-right">
						<a class="btn btn-warning" href="<?= base_url('/add-lead'); ?>" role="button">
							Add new Lead
						</a>
					</div>
				</div>
			</div>



		<!-- Simple Datatable start -->
		<div class="card-box mb-30">
				<div class="pd-20">
					<?php if ($error = $this->session->flashdata('error')): ?>
						<div class="alert alert-danger">
							<?= $error; ?>
							<?php $this->session->unset_userdata('error'); // Clear the error flashdata 
							?>
						</div>
					<?php endif; ?>

					<?php if ($success = $this->session->flashdata('success')): ?>
						<div class="alert alert-success">
							<?= $success; ?>
							<?php $this->session->unset_userdata('success'); // Clear the success flashdata 
							?>
						</div>
					<?php endif; ?>
					<!-- <h4 class="text-blue h4">Listing of all Questions</h4> -->
				</div>
				<div class="pb-20">
					<table class="data-table table stripe hover nowrap">
						<thead>
							<tr>
							<th>LEAD Id</th>
								<th>NAME</th>
								<th>LEAD STATUS</th>
								<th>LEAD OWNER</th>
								<th>EMAIL</th>
								<th>PHONE</th>
								
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($all_leads as $lead) : ?>
								<tr>
								<td><?= $lead['ENTITY_ID'] ?></td>
									<td><?= $lead['ENTITY_NAME'] ?></td>
									<td><?= ($lead['LEAD_STATUS'] == -1) ? "Closed" : "NEW"; ?></td>
									<td><?= $lead['LEAD_OWNER_NAME'] ?></td>
									<td><?= $lead['EMAIL'] ?></td>
									<td><?= $lead['PHONE'] ?></td>
									<td class="no-wrap">
										<div class="dropdown">
											<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
												<i class="dw dw-more"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
												<a class="dropdown-item" href="<?php echo base_url('leadController/viewLead/' . $lead['ENTITY_ID'] . ''); ?>"><i class="dw dw-eye"></i> VIEW</a>
												<a class="dropdown-item" href="<?php echo base_url('lead-edit/' . $lead['ENTITY_ID'] . ''); ?>"><i class="dw dw-edit2"></i> Edit</a>

												<!-- UPDATED: added class 'delete-lead-link' -->
												<a class="dropdown-item delete-lead-link" href="<?php echo base_url('lead-delete/' . $lead['ENTITY_ID'] . ''); ?>"><i class="dw dw-eye"></i> Delete</a>

												<a class="dropdown-item" href="<?php echo base_url('leadController/moveLeadToClient/' . $lead['ENTITY_ID'] . ''); ?>"><i class="dw dw-eye"></i> Move to Client</a>
												<a class="dropdown-item" href="<?php echo base_url('change-status/' . $lead['ENTITY_ID'] . ''); ?>"><i class="dw dw-eye"></i> Change Status</a>
												<a class="dropdown-item" href="<?php echo base_url('leadController/addTask/' . $lead['ENTITY_ID'] . ''); ?>"><i class="dw dw-edit2"></i> Add Task</a>
												<!-- <a class="dropdown-item" href="<?php echo base_url('leadController/empAgreenentFrom/' . $lead['ENTITY_ID'] . ''); ?>"><i class="dw dw-edit2"></i> Enrollment and Payment Agreement</a> -->

												<a class="dropdown-item" href="<?php echo base_url('payment-agreement'); ?>"><i class="dw dw-edit2"></i> Enrollment and Payment Agreement</a>
												<!-- <a class="dropdown-item" href="<?php echo base_url('admin/underMaintenance/'); ?>"><i class="dw dw-text"></i> Send SMS</a> -->
												<a class="dropdown-item" href="<?php echo base_url('leadController/sendLeadEmail/' . $lead['ENTITY_ID'] . ''); ?>"><i class="dw dw-mail"></i> Send Email</a>
											</div>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>

					</table>
				</div>
			</div>
			<!-- Simple Datatable End -->

		</div>

	</div>
</div>

<!-- CONFIRM DELETE POPUP -->
<div id="delete-confirm-overlay" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:1050;">
	<div style="background:#fff; max-width:400px; margin:15% auto; padding:20px; border-radius:4px;">
		<h5>Confirm Delete</h5>
		<p>Are you sure you want to delete this lead?</p>
		<div class="text-right" style="margin-top:15px;">
			<button type="button" id="confirm-delete-no" class="btn btn-secondary" style="margin-right:10px;">Cancel</button>
			<button type="button" id="confirm-delete-yes" class="btn btn-danger">Yes</button>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('.alert').fadeOut(3000);

		// DELETE CONFIRMATION LOGIC
		var deleteUrl = null;

		// When user clicks on Delete in dropdown
		$(document).on('click', '.delete-lead-link', function(e) {
			e.preventDefault();
			deleteUrl = $(this).attr('href'); // get the URL from the link
			$('#delete-confirm-overlay').show(); // show popup
		});

		// On YES -> go to delete URL
		$('#confirm-delete-yes').on('click', function() {
			if (deleteUrl) {
				window.location.href = deleteUrl;
			}
		});

		// On CANCEL -> just hide popup
		$('#confirm-delete-no').on('click', function() {
			deleteUrl = null;
			$('#delete-confirm-overlay').hide();
		});
	});
</script>

</body>
<?php $this->load->view('includes/footer'); ?>
<script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
<!-- buttons for Export datatable -->
<script src="src/plugins/datatables/js/dataTables.buttons.min.js"></script>
<script src="src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
<script src="src/plugins/datatables/js/buttons.print.min.js"></script>
<script src="src/plugins/datatables/js/buttons.html5.min.js"></script>
<script src="src/plugins/datatables/js/buttons.flash.min.js"></script>
<script src="src/plugins/datatables/js/pdfmake.min.js"></script>
<script src="src/plugins/datatables/js/vfs_fonts.js"></script>
<!-- Datatable Setting js -->
<script src="vendors/scripts/datatable-setting.js"></script>

</html>
