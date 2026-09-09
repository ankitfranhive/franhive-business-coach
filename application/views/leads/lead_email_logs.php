<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('campaign/partials/campaign_styles'); ?>
<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="page-header">
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <div class="title">
                        <h4>Lead email history</h4>
                        <small class="text-muted">
                            Emails sent to <?= htmlspecialchars($lead['NAME'] ?? '') ?>
                            <?php if (!empty($lead['EMAIL'])): ?>
                                (<?= htmlspecialchars($lead['EMAIL']) ?>)
                            <?php endif; ?>
                        </small>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 text-right">
                    <a class="btn btn-outline-secondary" href="<?= base_url('leadController/sendLeadEmail/' . (int)$lead['ENTITY_ID']); ?>">Send email</a>
                    <a class="btn btn-warning" href="<?= base_url('leads'); ?>">Back to leads</a>
                </div>
            </div>
        </div>

        <div class="pd-20 card-box mb-30">
            <h5 class="card-title">Sent emails</h5>
            <p class="text-muted mb-3">These are the emails already sent from our side. Open any email to see the same subject and body the client received — use that screen for screenshots.</p>
            <?php $this->load->view('leads/partials/sent_emails_table', ['sent_emails' => $sent_emails]); ?>
        </div>
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
</body>
</html>
