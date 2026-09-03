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
									<h4>Lesson Allocation to user</h4>
								</div>
								
							</div>
							<div class="col-md-6 col-sm-12 text-right">

                            <!-- <a
										class="btn btn-warning"
										href="<?= base_url('/add-test'); ?>"
										role="button"
										
									>
										Add new Test
									</a> -->
								
							</div>
						</div>
					</div>





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
								<th>User Mobile</th>

								<th class="datatable-nosort">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($users as $user) : ?>
								<tr>
								<td><?= $user->USER_ID ?></td>
								<td><?= $user->NAME ?></td>
								<td><?= $user->EMAIL ?></td>
								<td><?= $user->PHONE ?></td>
									<td>
									<a href="<?= base_url('lesson-allocation/' . $user->USER_ID) ?>" class="btn btn-primary btn-sm">Add lesson</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>

					</table>
				</div>
			</div>








					<!-- Simple Datatable start -->
					<!-- <div class="card-box mb-30">
						<div class="pd-20">
													
						</div>
						<div class="pb-20">
						<table class="data-table table stripe hover nowrap">
                            <thead>
									<tr>
										
                    <th> User ID</th>
                    <th> User Name</th>
                    <th>Email</th>
					<th>Allocated Tests</th>
                    <th>Actions</th>
									</tr>
								</thead>
                                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                <tr></td>
                        <td><?= $user->USER_ID ?></td>
                        <td><?= $user->NAME ?></td>
                        <td><?= $user->EMAIL ?></td>
                        <td><?= $user->EMAIL ?></td>
						             
                        <td>
                            <a href="<?= base_url('lesson-allocation/' . $user->USER_ID) ?>" class="btn btn-primary btn-sm">Add lesson</a>
                            
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
							</table>
						</div>
					</div> -->
				
					
				</div>
				
			</div>
		</div>


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

