<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
	<div class="xs-pd-20-10 pd-ltr-20">
		<div class="title pb-20">
			<h2 class="h3 mb-0">Campaign Overview</h2>
		</div>
		<div class="row pb-10">
			<div class="col-md-12 mb-20 row">
				<div class="col-md-6">
					<div class="card-box min-height-200px pd-20 mb-20" data-bgcolor="#455a64">
						<div class="d-flex justify-content-between pb-20 text-white">
							<div class="icon h1 text-white">
								<i class="fa fa-calendar" aria-hidden="true"></i>
								<!-- <i class="icon-copy fa fa-stethoscope" aria-hidden="true"></i> -->
							</div>

						</div>
						<div class="d-flex justify-content-between align-items-end">
							<div class="text-white">
								<div class="font-14">Total Active Campaign</div>
								<div class="font-24 weight-500">
									<?= $active_campaign_count ?>
								</div>
							</div>
							<div class="max-width-150">
								<div id="appointment-chart"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card-box min-height-200px pd-20" data-bgcolor="#265ed7">
						<div class="d-flex justify-content-between pb-20 text-white">
							<div class="icon h1 text-white">
								<i class="fa fa-stethoscope" aria-hidden="true"></i>
							</div>

						</div>
						<div class="d-flex justify-content-between align-items-end">
							<div class="text-white">
								<div class="font-14">Completed Completed Campaign</div>
								<div class="font-24 weight-500">
									<?= $completed_campaign_count ?>
								</div>
							</div>
							<div class="max-width-150">
								<div id="surgery-chart"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 mb-20">
				<div class="card-box height-100-p pd-20">
					<div class="d-flex flex-wrap justify-content-between align-items-center pb-0 pb-md-3">
						<div class="h5 mb-md-0">Recently Added Campaign</div>
					</div>
					<table class="data-table table stripe hover nowrap">
						<thead>
							<tr>
								<th>ID</th>
								<th>MODULE NAME</th>
								<th>NAME</th>
								<th>CREATED ON</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($recent_campaigns as $key => $data) : ?>
								<tr>
									<td><?= $key + 1 ?></td>
									<td><?= $data['MODULE_NAME'] ?></td>
									<td><?= $data['TITLE'] ?></td>
									<td><?= $data['CREATED_ON'] ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>

					</table>
				</div>
			</div>
			<div class="col-md-6 mb-20">
				<div class="card-box height-100-p pd-20">
					<div class="d-flex flex-wrap justify-content-between align-items-center pb-0 pb-md-3">
						<div class="h5 mb-md-0">Recently Completed Campaign</div>
					</div>
					<table class="data-table table stripe hover nowrap">
						<thead>
							<tr>
								<th>ID</th>
								<th>MODULE NAME</th>
								<th>NAME</th>
								<th>CREATED ON</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($completed_campaign as $key => $data) : ?>
								<tr>
									<td><?= $key + 1 ?></td>
									<td><?= $data['MODULE_NAME'] ?></td>
									<td><?= $data['TITLE'] ?></td>
									<td><?= $data['CREATED_ON'] ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>

					</table>
				</div>
			</div>
			<!-- <div id="activities-chart"></div> -->
		</div>
	</div>

</div>
</div>


</body>
<?php $this->load->view('includes/footer'); ?>

</html>