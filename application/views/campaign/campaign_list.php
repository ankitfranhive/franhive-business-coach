<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('campaign/partials/campaign_styles'); ?>
<div class="mobile-menu-overlay"></div>
<div class="main-container">
	<div class="pd-ltr-20 xs-pd-20-10">
		<div class="min-height-200px">
			<div class="page-header">
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<div class="title">
							<h4>Campaigns</h4>
							<p class="text-muted mb-0">Create, set up, trigger, then watch send status.</p>
						</div>
					</div>
					<div class="col-md-6 col-sm-12 text-right">
						<a class="btn btn-outline-primary" href="<?= base_url('/campaign-dashboard'); ?>">Dashboard</a>
						<a class="btn btn-primary" href="<?= base_url('/add-campaign'); ?>">New campaign</a>
					</div>
				</div>
			</div>
			<div class="card-box mb-30">
				<div class="pb-20">
					<table class="data-table table stripe hover nowrap">
						<thead>
							<tr>
								<th>ID</th>
								<th>Campaign</th>
								<th>Audience</th>
								<th>Status</th>
								<th>Start</th>
								<th>End</th>
								<th class="datatable-nosort">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($all_campaign as $campaign) :
								$wf = $campaign['WORKFLOW_STATUS'] ?? (($campaign['STATUS'] ?? '') === '0' ? 'sent' : 'draft');
								$meta = campaign_status_meta($wf);
							?>
								<tr>
									<td><?= $campaign['CAMPAIGN_ID'] ?></td>
									<td><?= htmlspecialchars($campaign['TITLE']) ?></td>
									<td><?= htmlspecialchars(campaign_audience_label($campaign['MODULE_NAME'])) ?></td>
									<td><span class="badge <?= $meta['class'] ?>" title="<?= $meta['help'] ?>"><?= $meta['label'] ?></span></td>
									<td><?= $campaign['START_DATE'] ? date('Y-m-d', strtotime($campaign['START_DATE'])) : '' ?></td>
									<td><?= $campaign['END_DATE'] ? date('Y-m-d', strtotime($campaign['END_DATE'])) : '' ?></td>
									<td class="no-wrap">
										<div class="dropdown">
											<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
												<i class="dw dw-more"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
												<a class="dropdown-item" href="<?= base_url('view-campaign/' . $campaign['CAMPAIGN_ID']); ?>"><i class="dw dw-eye"></i> View</a>
												<a class="dropdown-item" href="<?= base_url('edit-campaign/' . $campaign['CAMPAIGN_ID']); ?>"><i class="dw dw-edit2"></i> Edit</a>
												<?php if (in_array($wf, ['draft', 'setup', 'paused'], true)): ?>
													<a class="dropdown-item" href="<?= base_url('activate-campaign/' . $campaign['CAMPAIGN_ID']); ?>"><i class="dw dw-checked"></i> Trigger send</a>
												<?php endif; ?>
												<?php if (in_array($wf, ['scheduled', 'in_progress'], true)): ?>
													<a class="dropdown-item" href="<?= base_url('pause-campaign/' . $campaign['CAMPAIGN_ID']); ?>"><i class="dw dw-stop"></i> Pause</a>
												<?php endif; ?>
												<a class="dropdown-item" href="<?= base_url('delete-campaign/' . $campaign['CAMPAIGN_ID']); ?>"><i class="dw dw-delete-3"></i> Delete</a>
											</div>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('includes/footer'); ?>
</html>
