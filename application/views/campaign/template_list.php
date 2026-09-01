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
							<h4>Listing of Templates</h4>
						</div>
					</div>
					<div class="col-md-6 col-sm-12 text-right">
						<a class="btn btn-warning" href="<?= base_url('/add-template'); ?>" role="button">
							Add new Template
						</a>
					</div>
				</div>
			</div>
			<!-- Simple Datatable start -->
			<div class="card-box mb-30">
				<div class="pd-20">
					<!-- <h4 class="text-blue h4">Listing of all Questions</h4> -->

				</div>
				<div class="pb-20">
					<table class="data-table table stripe hover nowrap">
						<thead>
							<tr>
								<th>TEMPLATE ID</th>
								<th>TEMPLATE NAME</th>
								<th>MODULE NAME</th>
								<th>TEMPLATE SUBJECT</th>

								<th class="datatable-nosort">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($all_templates as $template) : ?>
								<tr>
									<td><?= $template['TEMPLATE_ID'] ?></td>
									<td><?= $template['TEMPLATE_NAME'] ?></td>
									<td><?= $template['MODULE_NAME'] ?></td>
									<td><?= $template['TEMPLATE_SUBJECT'] ?></td>


									<td class="no-wrap">
										<div class="dropdown">
											<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
												<i class="dw dw-more"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
												<a class="dropdown-item" href="<?php echo base_url('view-template/' . $template['TEMPLATE_ID'] . ''); ?>"><i class="dw dw-eye"></i> View</a>
												<a class="dropdown-item" href="<?php echo base_url('edit-template/' . $template['TEMPLATE_ID'] . ''); ?>"><i class="dw dw-edit2"></i> Edit</a>
												<a class="dropdown-item" href="<?php echo base_url('delete-template/' . $template['TEMPLATE_ID'] . ''); ?>"><i class="dw dw-delete-3"></i> Delete</a>
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