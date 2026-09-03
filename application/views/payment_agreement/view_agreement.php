<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<?php
$course_ids = !empty($agreement['course_name']) ? json_decode($agreement['course_name'], true) : [];
$course_dates = !empty($agreement['training_date_text']) ? json_decode($agreement['training_date_text'], true) : [];

$course_map = [];
if (!empty($all_courses)) {
    foreach ($all_courses as $course) {
        if (!empty($course['COURSE_ID']) && !empty($course['NAME'])) {
            $course_map[(string)$course['COURSE_ID']] = $course['NAME'];
        }
    }
}

function agreement_value($value)
{
    $value = trim((string)$value);
    return $value !== '' ? $value : '-';
}
?>

<style>
    /* ── Base card ── */
    .agreement-card {
        border: 1px solid #e5e9f2;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 4px 14px rgba(0,0,0,0.04);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .agreement-card-header {
        background: linear-gradient(135deg, #1b2a4e 0%, #2d467b 100%);
        color: #fff;
        padding: 16px 20px;
    }
    .agreement-card-header h5 {
        margin: 0;
        color: #fff;
        font-weight: 600;
    }
    .agreement-card-body { padding: 20px; }

    /* ── Generic 2-col grid ── */
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    /* ── Detail item tile ── */
    .detail-item {
        background: #f8fafc;
        border: 1px solid #e8edf5;
        border-radius: 12px;
        padding: 14px 16px;
    }
    .detail-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #6b7280;
        margin-bottom: 6px;
    }
    .detail-value {
        font-size: 14px;
        color: #111827;
        word-break: break-word;
        white-space: pre-wrap;
    }

    /* ══════════════════════════════════════════
       ADDRESS ROW  –  3 cols: 2fr | 1.2fr | 90px
       ══════════════════════════════════════════ */
    .detail-grid-address-row {
        grid-template-columns: minmax(0, 2fr) minmax(0, 1.2fr) 90px;
        margin-bottom: 16px;
    }
    /* Postcode tile: tighter padding, fixed width */
    .detail-item-postcode {
        padding-left: 10px;
        padding-right: 10px;
        max-width: 90px;
        min-width: 0;
    }
    .detail-item-postcode .detail-value {
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }

    /* ══════════════════════════════════════════
       CONTACT ROW  –  3 cols instead of 2
       Country | Mobile | Work
       ══════════════════════════════════════════ */
    .detail-grid-contact-row {
        grid-template-columns: minmax(0,1fr) minmax(0,1fr) minmax(0,1fr);
        margin-bottom: 16px;
    }

    /* ══════════════════════════════════════════
       EMAIL + DOB share one row  –  2:1 cols
       ══════════════════════════════════════════ */
    .detail-grid-email-dob-row {
        grid-template-columns: minmax(0, 1.5fr) minmax(0, 1fr);
        margin-bottom: 16px;
    }

    /* ══════════════════════════════════════════
       AMOUNT FIELDS  –  compact tiles
       3 per row, values are short numbers
       ══════════════════════════════════════════ */
    .detail-grid-amounts {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        margin-bottom: 0;
    }
    /* Make amount values smaller / more number-like */
    .detail-item-amount .detail-value {
        font-variant-numeric: tabular-nums;
        font-size: 15px;
        font-weight: 600;
        color: #1b2a4e;
    }

    /* ── Misc carry-overs ── */
    .delivery-services-notice-admin-title {
        white-space: normal; text-transform: none;
        letter-spacing: normal; font-size: 14px;
        line-height: 1.35; color: #1b2a4e;
    }
    .delivery-services-notice-admin-body { margin-top: 8px; }
    .payment-modes-notice-admin-title {
        white-space: normal; text-transform: none;
        letter-spacing: normal; font-size: 14px;
        line-height: 1.35; color: #1b2a4e;
    }
    .payment-modes-notice-admin-body { margin-top: 8px; }
    .consent-rich-body { word-break: break-word; }
    .consent-rich-body p { margin: 0 0 0.5em; }
    .course-box {
        background: #f8fafc;
        border: 1px solid #e8edf5;
        border-radius: 12px;
        padding: 12px 14px;
        margin-bottom: 10px;
    }
    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }
    .status-yes { background: #dcfce7; color: #166534; }
    .status-no  { background: #fee2e2; color: #991b1b; }
    .signature-preview {
        max-width: 100%;
        max-height: 220px;
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        padding: 8px;
        background: #fff;
    }
    .back-btn-wrap { text-align: right; }

    @media (max-width: 900px) {
        .detail-grid-amounts          { grid-template-columns: repeat(2, minmax(0,1fr)); }
        .detail-grid-contact-row      { grid-template-columns: repeat(2, minmax(0,1fr)); }
    }
    @media (max-width: 768px) {
        .detail-grid,
        .detail-grid-address-row,
        .detail-grid-contact-row,
        .detail-grid-email-dob-row,
        .detail-grid-amounts          { grid-template-columns: 1fr; }
        .detail-item-postcode         { max-width: 100%; }
        .back-btn-wrap                { text-align: left; margin-top: 12px; }
    }
</style>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-6 col-sm-12">
                    <div class="title"><h4>Payment Agreement Details</h4></div>
                </div>
                <div class="col-md-6 col-sm-12 back-btn-wrap">
                    <a href="<?= base_url('payment-agreement/requests'); ?>" class="btn btn-warning">Back to List</a>
                    <a href="<?= base_url('payment-agreement/preview/' . $agreement['id']); ?>" target="_blank" class="btn btn-primary">Preview Form</a>
                </div>
            </div>
        </div>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $success; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $error; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        <?php endif; ?>

        <!-- ── Approval ── -->
        <div class="agreement-card">
            <div class="agreement-card-header"><h5>Approval (Admin)</h5></div>
            <div class="agreement-card-body">
                <form method="post" action="<?= base_url('payment-agreement/update-approval/' . (int)$agreement['id']); ?>">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="detail-label" style="text-transform:none;letter-spacing:normal;">Approved By</label>
                            <input type="text" name="approved_by" class="form-control" maxlength="120"
                                   value="<?= html_escape($agreement['approved_by'] ?? ''); ?>" placeholder="Approver name">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="detail-label" style="text-transform:none;letter-spacing:normal;">Approval Date</label>
                            <input type="date" name="approval_date" class="form-control"
                                   value="<?= html_escape($agreement['approval_date'] ?? ''); ?>">
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Save Approval Details</button>
                            <small class="text-muted d-block mt-2">These fields are not editable on the client form; update them here after review.</small>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ── Basic Information ── -->
        <div class="agreement-card">
            <div class="agreement-card-header"><h5>Basic Information</h5></div>
            <div class="agreement-card-body">

                <!-- Row 1: Agreement ID | Created At | Full Name | Business Name -->
                <div class="detail-grid" style="margin-bottom:16px;">
                    <div class="detail-item">
                        <div class="detail-label">Agreement ID</div>
                        <div class="detail-value"><?= html_escape($agreement['id']); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Created At</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['created_at'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Full Name</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['full_name'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Business Name</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['business_name'] ?? '')); ?></div>
                    </div>
                </div>

                <!-- Row 2: Email (wider) | Date of Birth (narrower) — share ONE row -->
                <div class="detail-grid detail-grid-email-dob-row">
                    <div class="detail-item">
                        <div class="detail-label">Email</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['email_address'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Date of Birth</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['date_of_birth'] ?? '')); ?></div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ── Address & Contact ── -->
        <div class="agreement-card">
            <div class="agreement-card-header"><h5>Address &amp; Contact</h5></div>
            <div class="agreement-card-body">

                <!-- Row 1: Postal Address | City/Suburb | Postcode (fixed 90px) -->
                <div class="detail-grid detail-grid-address-row">
                    <div class="detail-item">
                        <div class="detail-label">Postal Address</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['postal_address'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">City / Suburb</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['city_suburb'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item detail-item-postcode">
                        <div class="detail-label">Postcode</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['postcode'] ?? '')); ?></div>
                    </div>
                </div>

                <!-- Row 2: Country | Mobile | Work  — 3 equal columns -->
                <div class="detail-grid detail-grid-contact-row">
                    <div class="detail-item">
                        <div class="detail-label">Country</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['country'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Mobile Number</div>
                        <div class="detail-value">
                            <?php
                                $mobile_code = trim((string)($agreement['country_code_mobile'] ?? ''));
                                $mobile_num  = trim((string)($agreement['contact_number_mobile'] ?? ''));
                                echo html_escape(agreement_value(trim($mobile_code . ' ' . $mobile_num)));
                            ?>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Work Number</div>
                        <div class="detail-value">
                            <?php
                                $work_code = trim((string)($agreement['country_code_work'] ?? ''));
                                $work_num  = trim((string)($agreement['contact_number_work'] ?? ''));
                                echo html_escape(agreement_value(trim($work_code . ' ' . $work_num)));
                            ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ── Emergency Contact ── -->
        <div class="agreement-card">
            <div class="agreement-card-header"><h5>Emergency Contact</h5></div>
            <div class="agreement-card-body">
                <!-- 3 cols: Name | Number | Relationship -->
                <div class="detail-grid detail-grid-contact-row">
                    <div class="detail-item">
                        <div class="detail-label">Emergency Contact Name</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['emergency_contact_name'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Emergency Contact Number</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['emergency_contact_number'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Relationship</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['emergency_contact_relationship'] ?? '')); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Course Details ── -->
        <div class="agreement-card">
            <div class="agreement-card-header"><h5>Course Details</h5></div>
            <div class="agreement-card-body">
                <div class="mb-3">
                    <div class="detail-label">Selected Courses</div>
                    <?php if (!empty($course_ids)): ?>
                        <?php foreach ($course_ids as $cid): ?>
                            <div class="course-box">
                                <strong><?= html_escape(isset($course_map[(string)$cid]) ? $course_map[(string)$cid] : $cid); ?></strong>
                                <?php if (!empty($course_dates[$cid])): ?>
                                    <div class="text-muted mt-1">Date: <?= html_escape($course_dates[$cid]); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if (!empty($agreement['other_course_name'])): ?>
                        <div class="course-box">
                            <strong>Other: <?= html_escape($agreement['other_course_name']); ?></strong>
                            <?php if (!empty($course_dates['other'])): ?>
                                <div class="text-muted mt-1">Date: <?= html_escape($course_dates['other']); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (empty($course_ids) && empty($agreement['other_course_name'])): ?>
                        <div class="detail-value">-</div>
                    <?php endif; ?>
                </div>

                <!-- Delivery services notice spans full width -->
                <div class="detail-grid" style="margin-bottom:16px;">
                    <div class="detail-item" style="grid-column: 1 / -1;">
                        <?php $this->load->view('payment_agreement/partials/delivery_services_notice', ['form_settings' => $form_settings ?? [], 'variant' => 'admin_detail']); ?>
                    </div>
                </div>

                <!-- Amount fields: 3 compact cols -->
                <div class="detail-grid detail-grid-amounts">
                    <div class="detail-item detail-item-amount">
                        <div class="detail-label">Total (inc GST)</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['total_inc_gst'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item detail-item-amount">
                        <div class="detail-label">Deposit Paid $</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['deposit_amount'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Deposit Paid Via</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['deposit_paid_via'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Deposit Paid On</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['deposit_paid_on'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item detail-item-amount">
                        <div class="detail-label">Balance Payable $</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['balance_amount'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Due Date</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['due_date'] ?? '')); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Payment Arrangement ── -->
        <?php
            $pa_json = !empty($agreement['payment_arrangement_json']) ? json_decode($agreement['payment_arrangement_json'], true) : null;
        ?>
        <?php if (is_array($pa_json) && !empty($pa_json['type'])): ?>
        <div class="agreement-card">
            <div class="agreement-card-header"><h5>Payment arrangement</h5></div>
            <div class="agreement-card-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Arrangement</div>
                        <div class="detail-value"><?= html_escape($pa_json['type'] === 'pay_full' ? 'Pay in full' : 'Payment plan'); ?></div>
                    </div>
                    <?php if ($pa_json['type'] === 'pay_full'): ?>
                    <div class="detail-item">
                        <div class="detail-label">Full payment by</div>
                        <div class="detail-value"><?= html_escape(agreement_value($pa_json['pay_full_commit_date'] ?? '')); ?></div>
                    </div>
                    <?php else: ?>
                    <div class="detail-item detail-item-amount">
                        <div class="detail-label">Total payable</div>
                        <div class="detail-value"><?= html_escape(agreement_value($pa_json['pay_plan_total'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Instalments</div>
                        <div class="detail-value"><?= html_escape(agreement_value($pa_json['pay_plan_num_instalments'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item detail-item-amount">
                        <div class="detail-label">Instalment amount</div>
                        <div class="detail-value"><?= html_escape(agreement_value($pa_json['pay_plan_instalment_amount'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Final payment date</div>
                        <div class="detail-value"><?= html_escape(agreement_value($pa_json['pay_plan_final_date'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item" style="grid-column: 1 / -1;">
                        <div class="detail-label">Plan notes</div>
                        <div class="detail-value"><?= nl2br(html_escape(agreement_value($pa_json['pay_plan_notes'] ?? ''))); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Acknowledgement (name / initials)</div>
                        <div class="detail-value"><?= html_escape(agreement_value($pa_json['pay_plan_ack_name'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Commitment date</div>
                        <div class="detail-value"><?= html_escape(agreement_value($pa_json['pay_plan_commit_date'] ?? '')); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- ── Certification ── -->
        <div class="agreement-card">
            <div class="agreement-card-header"><h5>Certification</h5></div>
            <div class="agreement-card-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Holds Certification</div>
                        <div class="detail-value">
                            <?php if (!empty($agreement['holds_certification'])): ?>
                                <span class="status-badge status-yes">Yes</span>
                            <?php else: ?>
                                <span class="status-badge status-no">No</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Certification Details</div>
                        <div class="detail-value"><?= nl2br(html_escape(agreement_value($agreement['certification_details'] ?? ''))); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Attachment</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['certification_attachment'] ?? '')); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Payment Details ── -->
        <div class="agreement-card">
            <div class="agreement-card-header"><h5>Payment Details</h5></div>
            <div class="agreement-card-body">
                <div class="detail-grid">
                    <div class="detail-item" style="grid-column: 1 / -1;">
                        <?php $this->load->view('payment_agreement/partials/payment_modes_notice', ['form_settings' => $form_settings ?? [], 'variant' => 'admin_detail']); ?>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Preferred payment method</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['payment_option'] ?? '')); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Consent & Signature ── -->
        <div class="agreement-card">
            <div class="agreement-card-header"><h5>Consent &amp; Signature</h5></div>
            <div class="agreement-card-body">
                <?php
                    $fsv = $form_settings ?? [];
                    $consent_lbls = isset($consent_term_labels) ? $consent_term_labels : [];
                    $rich_trim = isset($fsv['consent_rich_html']) ? trim((string)$fsv['consent_rich_html']) : '';
                    $show_consent_h = !empty($show_consent_terms_heading);
                    $has_consent_audit = $show_consent_h || ($rich_trim !== '');
                ?>
                <?php if ($has_consent_audit): ?>
                    <?php if ($show_consent_h): ?>
                        <div class="detail-label mb-2" style="white-space:normal;text-transform:none;letter-spacing:normal;font-size:15px;color:#1b2a4e;">
                            <?= html_escape(!empty($fsv['section_terms']) ? $fsv['section_terms'] : 'Consent & Terms'); ?>
                        </div>
                    <?php endif; ?>
                    <?php $this->load->view('payment_agreement/partials/consent_rich_display', ['form_settings' => $fsv, 'variant' => 'admin']); ?>
                <?php endif; ?>

                <div class="detail-grid">
                    <?php for ($ti = 1; $ti <= 6; $ti++): ?>
                        <?php
                            $lbl = isset($consent_lbls[$ti]) ? $consent_lbls[$ti] : '';
                            if ($lbl === '') continue;
                        ?>
                        <div class="detail-item">
                            <div class="detail-label">Agree Terms <?= (int)$ti; ?></div>
                            <div class="detail-value"><?= !empty($agreement['agree_terms_' . $ti]) ? 'Yes' : 'No'; ?> — <?= html_escape($lbl); ?></div>
                        </div>
                    <?php endfor; ?>
                    <div class="detail-item">
                        <div class="detail-label">Signature</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['signature'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Signature Date</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['signature_date'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Approved By</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['approved_by'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Approval Date</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['approval_date'] ?? '')); ?></div>
                    </div>
                </div>

                <?php if (!empty($agreement['signature_image'])): ?>
                    <div class="mt-4">
                        <div class="detail-label">Drawn Signature</div>
                        <img src="<?= $agreement['signature_image']; ?>" alt="Signature" class="signature-preview">
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ── Audit Details ── -->
        <div class="agreement-card">
            <div class="agreement-card-header"><h5>Audit Details</h5></div>
            <div class="agreement-card-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">IP Address</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['ip_address'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">User Agent</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['user_agent'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Client Timezone</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['client_timezone'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Client Time ISO</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['client_time_iso'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Screen Resolution</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['screen_resolution'] ?? '')); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Created At</div>
                        <div class="detail-value"><?= html_escape(agreement_value($agreement['created_at'] ?? '')); ?></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
<?php $this->load->view('includes/footer'); ?>
</html>