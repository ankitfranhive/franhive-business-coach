<?php
/**
 * Floating CloudTalk Phone widget + optional right-hand sidebar.
 * Requires: CLOUDTALK_PARTNER in .env
 *
 * CloudTalk iFrame doc: https://help.cloudtalk.io/... (see citation in chat)
 */
?>



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
							<h4>Listing of All Recent Call Logs</h4>
						</div>
					</div>
					<!-- <div class="col-md-6 col-sm-12 text-right">
						<a class="btn btn-warning" href="<?= base_url('/add-user'); ?>" role="button">
							Add new User
						</a>
					</div> -->
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
								<th>User ID</th>
								<th>User Name</th>
								<th>User Email</th>
								<th>User Role</th>

								<th class="datatable-nosort">Action</th>
							</tr>
						</thead>
						<!-- <tbody>
							<?php foreach ($all_users as $user) : ?>
								<tr>
									<td><?= $user['USER_ID'] ?></td>
									<td><?= $user['NAME'] ?></td>
									<td><?= $user['EMAIL'] ?></td>
									<td><?= $user['ROLE_NAME'] ?></td>
									<td class="no-wrap">
										<div class="dropdown">
											<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
												<i class="dw dw-more"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
												<a class="dropdown-item" href="<?php echo base_url('userController/viewUser/' . $user['USER_ID'] . ''); ?>"><i class="dw dw-eye"></i> View</a>
												<a class="dropdown-item" href="<?php echo base_url('edit-user/' . $user['USER_ID'] . ''); ?>"><i class="dw dw-edit2"></i> Edit</a>
												<a class="dropdown-item" href="<?php echo base_url('userController/deleteUser/' . $user['USER_ID'] . ''); ?>"><i class="dw dw-delete-3"></i> Delete</a>
												<a class="dropdown-item" href="<?php echo base_url('userController/send_credentials/' . $user['USER_ID']); ?>">
    <i class="dw dw-email"></i> Send Credentials
</a>



											</div>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody> -->

					</table>
				</div>
			</div>

			<?php if ($this->session->flashdata('success')): ?>
    <div id="flashMessage" class="alert alert-success">
        <?= $this->session->flashdata('success'); ?>
    </div>
<?php elseif ($this->session->flashdata('error')): ?>
    <div id="flashMessage" class="alert alert-danger">
        <?= $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<script>
    setTimeout(function() {
        $('#flashMessage').fadeOut('slow');
    }, 4000);
</script>

			<!-- Simple Datatable End -->

		</div>

	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		// Hide the alert after 3 seconds (3000 milliseconds)
		setTimeout(function() {
			$('.alert').fadeOut('slow');
		}, 3000);
	});
</script>

</body>
<?php
// Show the CloudTalk phone on this page.
// You can pass a custom partner tag or omit the array to use ENV fallback.
$this->load->view('partials/cloudtalk_widget', [
    'partner' => 'drishti-lms',     // or getenv('CLOUDTALK_PARTNER')
    'sidebarEnabled' => true       // set true if you wire up Telephony/sidebar
]);
?>
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
