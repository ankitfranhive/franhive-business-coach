<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('campaign/partials/campaign_styles'); ?>
<div class="mobile-menu-overlay"></div>
<div class="main-container">
	<div class="xs-pd-20-10 pd-ltr-20">
		<div class="title pb-20 d-flex justify-content-between align-items-center">
			<div>
				<h2 class="h3 mb-0">Campaign Center</h2>
				<p class="text-muted mb-0">Build an email, pick contacts, then trigger the send — like a CRM campaign.</p>
			</div>
			<div>
				<a class="btn btn-outline-primary" href="<?= base_url('/templates'); ?>">Email templates</a>
				<a class="btn btn-primary" href="<?= base_url('/add-campaign'); ?>">New campaign</a>
			</div>
		</div>

		<div class="row pb-10">
			<?php
			$cards = [
				['draft', 'Draft', '#6c757d'],
				['setup', 'Setup done', '#17a2b8'],
				['scheduled', 'Triggered', '#f39c12'],
				['in_progress', 'In progress', '#265ed7'],
				['sent', 'Sent', '#28a745'],
				['paused', 'Paused', '#343a40'],
			];
			foreach ($cards as $card):
				$key = $card[0];
			?>
			<div class="col-md-4 col-xl-2 mb-20">
				<div class="card-box pd-20 cm-status-card" style="background:<?= $card[2] ?>;">
					<p><?= $card[1] ?></p>
					<h3><?= (int)($counts[$key] ?? 0) ?></h3>
				</div>
			</div>
			<?php endforeach; ?>
		</div>

		<div class="row">
			<div class="col-md-6 mb-20">
				<div class="card-box height-100-p pd-20">
					<div class="h5 mb-3">Triggered / waiting to send</div>
					<table class="table table-sm">
						<thead><tr><th>Campaign</th><th>Audience</th><th>Status</th></tr></thead>
						<tbody>
						<?php if (empty($scheduled_campaigns)): ?>
							<tr><td colspan="3" class="text-muted">No triggered campaigns.</td></tr>
						<?php endif; ?>
						<?php foreach ($scheduled_campaigns as $row): $meta = campaign_status_meta($row['WORKFLOW_STATUS'] ?? 'scheduled'); ?>
							<tr>
								<td><a href="<?= base_url('view-campaign/'.$row['CAMPAIGN_ID']); ?>"><?= htmlspecialchars($row['TITLE']) ?></a></td>
								<td><?= htmlspecialchars(campaign_audience_label($row['MODULE_NAME'])) ?></td>
								<td><span class="badge <?= $meta['class'] ?>"><?= $meta['label'] ?></span></td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-6 mb-20">
				<div class="card-box height-100-p pd-20">
					<div class="h5 mb-3">In progress</div>
					<table class="table table-sm">
						<thead><tr><th>Campaign</th><th>Sent</th><th>Status</th></tr></thead>
						<tbody>
						<?php if (empty($in_progress_campaigns)): ?>
							<tr><td colspan="3" class="text-muted">Nothing sending right now.</td></tr>
						<?php endif; ?>
						<?php foreach ($in_progress_campaigns as $row): $meta = campaign_status_meta($row['WORKFLOW_STATUS'] ?? 'in_progress'); ?>
							<tr>
								<td><a href="<?= base_url('view-campaign/'.$row['CAMPAIGN_ID']); ?>"><?= htmlspecialchars($row['TITLE']) ?></a></td>
								<td><?= (int)($row['SENT_COUNT'] ?? 0) ?> / <?= (int)($row['TOTAL_RECIPIENTS'] ?? 0) ?></td>
								<td><span class="badge <?= $meta['class'] ?>"><?= $meta['label'] ?></span></td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-12 mb-20">
				<div class="card-box pd-20">
					<div class="h5 mb-3">All recent campaigns</div>
					<table class="data-table table stripe hover nowrap">
						<thead>
							<tr>
								<th>Campaign</th>
								<th>Audience</th>
								<th>Status</th>
								<th>Recipients</th>
								<th>Emails sent</th>
								<th>Created</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($recent_campaigns as $row): $meta = campaign_status_meta($row['WORKFLOW_STATUS'] ?? 'draft'); ?>
							<tr>
								<td><a href="<?= base_url('view-campaign/'.$row['CAMPAIGN_ID']); ?>"><?= htmlspecialchars($row['TITLE']) ?></a></td>
								<td><?= htmlspecialchars(campaign_audience_label($row['MODULE_NAME'])) ?></td>
								<td><span class="badge <?= $meta['class'] ?>"><?= $meta['label'] ?></span></td>
								<td><?= (int)($row['TOTAL_RECIPIENTS'] ?? 0) ?></td>
								<td><?= (int)($row['SENT_COUNT'] ?? 0) ?></td>
								<td><?= htmlspecialchars($row['CREATED_ON']) ?></td>
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
