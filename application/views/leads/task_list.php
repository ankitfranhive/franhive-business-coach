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
							<h4>Listing of all Tasks</h4>
						</div>
					</div>
					<div class="col-md-6 col-sm-12 text-right">
						<a class="btn btn-warning" href="<?= base_url('/add-task'); ?>" role="button">
							Add new Task
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
								<th>TASK ID</th>
								<th>SUBJECT</th>
								<th>START DATE</th>
								<th>ASIGN TO</th>
								<!-- <th>START DATE</th> -->
								<!-- <th>PHONE</th>
								<th>CITY</th>
								<th>STATE</th>
								<th>COUNTRY</th> -->
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($all_task as $task) : ?>
								<tr>
								<tr>
									</td>
									<td><?= $task['TASK_ID'] ?></td>
									<td><?= $task['SUBJECT'] ?></td>

									<td><?= $task['START_DATE'] ?></td>
									<td><?= $task['ASSIGNED_TO_USER'] ?></td>
									<td>
										<a href="<?= base_url('leadController/editTask/' . $task['TASK_ID']) ?>" class="btn btn-primary btn-sm">Edit</a>
										<?php if ($user_role['role_name'] == 'Admin') : ?>
											<a href="<?= base_url('leadController/delete_task/' . $task['TASK_ID']) ?>" class="btn btn-danger btn-sm">Delete</a>
										<?php endif ?>

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

</html>