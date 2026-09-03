<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">

        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Payment Agreement Requests</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
    <a class="btn btn-primary" href="<?= base_url('payment-agreement/form-settings'); ?>" role="button">
        View / Edit Form Fields
    </a>
    <!-- <a class="btn btn-warning" href="<?= base_url('/payment-agreement'); ?>" role="button">
        Add Payment Agreement
    </a> -->
</div>
            </div>
        </div>
      

        <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $success; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $error; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <div class="pd-20 card-box mb-30">
            <h5 class="mb-20">Send Payment Agreement</h5>

            <form method="post" action="<?= base_url('payment-agreement/send'); ?>">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Client Name</label>
                        <input type="text" name="client_name" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Client Email</label>
                        <input type="email" name="client_email" class="form-control" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Form Subject Title</label>
                        <input type="text" name="business_name" class="form-control" placeholder="Payment Agreement form">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Invite Email Template <span class="text-danger">*</span></label>
                        <select name="email_template_id" class="form-control" required>
                            <option value="">-- Select template --</option>
                            <?php if (!empty($email_templates)): ?>
                                <?php foreach ($email_templates as $tpl): ?>
                                    <option value="<?= (int)$tpl['TEMPLATE_ID']; ?>">
                                        <?= html_escape($tpl['TEMPLATE_NAME']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-muted">
                            Sent now with the form link. Module: <strong>Payment Agreement</strong>.
                            <a href="<?= base_url('add-template'); ?>" target="_blank">Add template</a>
                            · <code>$Name$</code>, <code>$Link$</code>, <code>$BusinessName$</code>
                        </small>
                        <?php if (empty($email_templates)): ?>
                            <small class="text-danger d-block">No Payment Agreement templates yet. At <a href="<?= base_url('add-template'); ?>">Add Template</a>, set <strong>Template Module</strong> to <strong>Payment Agreement</strong>.</small>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Thank You Email Template <span class="text-danger">*</span></label>
                        <select name="thank_you_email_template_id" class="form-control" required>
                            <option value="">-- Select thank you template --</option>
                            <?php if (!empty($email_templates)): ?>
                                <?php foreach ($email_templates as $tpl): ?>
                                    <option value="<?= (int)$tpl['TEMPLATE_ID']; ?>">
                                        <?= html_escape($tpl['TEMPLATE_NAME']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-muted">Sent to the client after they successfully submit the form. Put Zoom/WhatsApp content in this template.</small>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Total (inc GST) <span class="text-danger">*</span></label>
                        <input type="number" name="total_inc_gst" class="form-control" step="0.01" min="0" placeholder="0.00" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Deposit Amount</label>
                        <input type="number" name="deposit_amount" class="form-control" step="0.01" min="0" placeholder="0.00">
                        <small class="text-muted">Optional. Pre-fills on the client form.</small>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Deposit Date</label>
                        <input type="date" name="deposit_paid_on" class="form-control">
                        <small class="text-muted">Optional. Pre-fills Deposit Paid On.</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Selected Course <span class="text-danger">*</span></label>
                        <select name="selected_course_id" class="form-control" required>
                            <option value="">-- Select Course --</option>
                            <?php if (!empty($all_courses)): ?>
                                <?php foreach ($all_courses as $course): ?>
                                    <?php
                                        if (isset($course['RECORD_STATUS']) && (int)$course['RECORD_STATUS'] !== 0) {
                                            continue;
                                        }
                                        $course_id = isset($course['COURSE_ID']) ? $course['COURSE_ID'] : '';
                                        $course_label = isset($course['NAME']) ? trim($course['NAME']) : '';
                                        if ($course_id === '' || $course_label === '') {
                                            continue;
                                        }
                                    ?>
                                    <option value="<?= html_escape($course_id); ?>"><?= html_escape($course_label); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Course Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="selected_course_start_date" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Course End Date <span class="text-danger">*</span></label>
                        <input type="date" name="selected_course_end_date" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Payment arrangement intro override (optional HTML)</label>
                        <textarea name="payment_arrangement_intro_override" class="form-control" rows="4" placeholder="Leave blank to use text from Form Settings → Payment arrangement. If filled, replaces the default intro for this link only."></textarea>
                        <small class="text-muted">For per-client wording; the pay-in-full / payment plan fields stay the same.</small>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Link expires in <span class="text-danger">*</span></label>
                        <input type="number" name="token_expiry_value" class="form-control" min="1" max="720" value="24" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>Expiry unit <span class="text-danger">*</span></label>
                        <select name="token_expiry_unit" class="form-control" required>
                            <option value="hours" selected>Hours</option>
                            <option value="days">Days</option>
                            <option value="weeks">Weeks</option>
                        </select>
                        <small class="text-muted">Default is 24 hours. Max: 720 hours, 90 days, or 12 weeks.</small>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">Send</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="pd-20 card-box mb-30">
            <h5 class="mb-20">Sent Requests</h5>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Email</th>
                            <th>Form Subject</th>
                            <th>Total (inc GST)</th>
                            <th>Deposit</th>
                            <th>Deposit Date</th>
                            <th>Status</th>
                            <th>Sent At</th>
                            <th>Opened At</th>
                            <th>Submitted At</th>
                            <th>Expires</th>
                            <th>Form Link</th>
                            <th>Submission</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($requests)): ?>
                        <?php foreach($requests as $r): ?>
                            <tr>
                                <td><?= (int)$r['id']; ?></td>
                                <td><?= html_escape($r['client_name']); ?></td>
                                <td><?= html_escape($r['client_email']); ?></td>
                                <td><?= html_escape($r['business_name']); ?></td>
                                <td><?= array_key_exists('total_inc_gst', $r) && $r['total_inc_gst'] !== null && $r['total_inc_gst'] !== '' ? html_escape(number_format((float)$r['total_inc_gst'], 2, '.', '')) : '—'; ?></td>
                                <td><?= array_key_exists('deposit_amount', $r) && $r['deposit_amount'] !== null && $r['deposit_amount'] !== '' ? html_escape(number_format((float)$r['deposit_amount'], 2, '.', '')) : '—'; ?></td>
                                <td><?= !empty($r['deposit_paid_on']) ? html_escape($r['deposit_paid_on']) : '—'; ?></td>
                                <td>
                                    <?php
                                        $status = strtolower($r['status']);
                                        $badge_class = 'secondary';
                                        if ($status === 'sent') $badge_class = 'primary';
                                        if ($status === 'opened') $badge_class = 'warning';
                                        if ($status === 'submitted') $badge_class = 'success';
                                        if ($status === 'expired') $badge_class = 'danger';
                                    ?>
                                    <span class="badge badge-<?= $badge_class; ?>">
                                        <?= html_escape(ucfirst($r['status'])); ?>
                                    </span>
                                </td>
                                <td><?= html_escape($r['sent_at']); ?></td>
                                <td><?= html_escape($r['opened_at']); ?></td>
                                <td><?= html_escape($r['submitted_at']); ?></td>
                                <td><?= html_escape($r['token_expires_at']); ?></td>
                                <td>
                                    <a class="btn btn-sm btn-outline-primary" target="_blank" href="<?= base_url('payment-agreement/form/'.$r['token']); ?>">
                                        Open Link
                                    </a>
                                </td>
                                <td>
                                    <?php if (!empty($r['agreement_id'])): ?>
                                        <div class="d-flex flex-column" style="gap:6px;">
                                            <a class="btn btn-sm btn-primary" target="_blank" href="<?= base_url('payment-agreement/preview/'.$r['agreement_id']); ?>">
                                                Preview Form
                                            </a>

                                            <a class="btn btn-sm btn-success" href="<?= base_url('payment-agreement/download/'.$r['agreement_id']); ?>">
                                                Download PDF
                                            </a>

                                            <a class="btn btn-sm btn-info" href="<?= base_url('payment-agreement/view/'.$r['agreement_id']); ?>">
                                                View Audit
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">Not submitted</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="post" action="<?= base_url('payment-agreement/resend'); ?>" class="resend-form" onsubmit="return confirm('Resend the payment agreement form to <?= html_escape($r['client_email']); ?>? A new link will be generated with the expiry duration you select below.');">
                                        <input type="hidden" name="request_id" value="<?= (int)$r['id']; ?>">
                                        <?php if (!empty($email_templates)): ?>
                                            <select name="email_template_id" class="form-control form-control-sm mb-2" required>
                                                <option value="">-- Invite email template --</option>
                                                <?php foreach ($email_templates as $tpl): ?>
                                                    <option value="<?= (int)$tpl['TEMPLATE_ID']; ?>">
                                                        <?= html_escape($tpl['TEMPLATE_NAME']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <?php
                                                $current_ty_tpl = isset($r['thank_you_email_template_id']) ? (int)$r['thank_you_email_template_id'] : 0;
                                            ?>
                                            <select name="thank_you_email_template_id" class="form-control form-control-sm mb-2" required>
                                                <option value="">-- Thank you email template --</option>
                                                <?php foreach ($email_templates as $tpl): ?>
                                                    <option value="<?= (int)$tpl['TEMPLATE_ID']; ?>" <?= $current_ty_tpl === (int)$tpl['TEMPLATE_ID'] ? 'selected' : ''; ?>>
                                                        <?= html_escape($tpl['TEMPLATE_NAME']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php endif; ?>
                                        <div class="d-flex mb-2" style="gap:6px;">
                                            <input type="number" name="token_expiry_value" class="form-control form-control-sm" min="1" max="720" value="24" required title="Expiry duration" style="width:72px;">
                                            <select name="token_expiry_unit" class="form-control form-control-sm" required title="Expiry unit">
                                                <option value="hours" selected>Hours</option>
                                                <option value="days">Days</option>
                                                <option value="weeks">Weeks</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-warning btn-block" <?= empty($email_templates) ? 'disabled title="Add a Payment Agreement email template first"' : ''; ?>>
                                            Resend Form
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="15" class="text-center">No requests found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

</body>
<?php $this->load->view('includes/footer'); ?>
</html>