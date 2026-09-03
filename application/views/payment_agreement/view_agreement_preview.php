<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Filled Enrollment and Payment Agreement | Empower Your Destiny</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="<?= base_url('vendors/styles/core.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('vendors/styles/icon-font.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('vendors/styles/style.css'); ?>">

    <style>
        :root{
            --brand:#fcb22b;
            --brand-dark:#111111;
            --brand-blue:#1b2a4e;
            --brand-blue-2:#243867;
            --bg:#f4f7fb;
            --surface:#ffffff;
            --text:#1f2937;
            --muted:#6b7280;
            --border:#dbe2ea;
            --soft:#f8fafc;
            --success:#198754;
            --danger:#dc3545;
            --shadow:0 10px 30px rgba(16,24,40,0.08);
        }

        *{
            box-sizing:border-box;
        }

        body{
            margin:0;
            background:linear-gradient(180deg, #f7f9fc 0%, #eef3f8 100%);
            color:var(--text);
            font-family:Arial, Helvetica, sans-serif;
        }

        .eyd-header{
            background:linear-gradient(90deg, #fcb22b 0%, #ffbf45 100%);
            border-bottom:5px solid rgba(0,0,0,0.08);
        }

        .eyd-header-inner{
            max-width:1180px;
            margin:0 auto;
            padding:18px 20px;
            display:flex;
            align-items:center;
            gap:18px;
        }

        .eyd-logo-wrap{
            width:92px;
            height:92px;
            border-radius:50%;
            background:var(--brand-dark);
            border:6px solid #fff;
            display:flex;
            align-items:center;
            justify-content:center;
            overflow:hidden;
            box-shadow:0 8px 20px rgba(0,0,0,0.18);
            flex:0 0 auto;
        }

        .eyd-logo{
            width:92%;
            height:auto;
            display:block;
        }

        .eyd-header-text{
            min-width:0;
            color:#ffffff;
            text-shadow:0 1px 1px rgba(0,0,0,0.15);
        }

        .eyd-title{
            font-size:40px;
            line-height:1.05;
            font-weight:700;
            margin:0;
            color:#ffffff;
            letter-spacing:0.2px;
        }

        .eyd-contact{
            margin-top:8px;
            font-size:17px;
            font-weight:600;
            color:#fff8ea;
        }

        .page-wrap{
            max-width:1120px;
            margin:28px auto;
            padding:0 16px 30px;
        }

        .top-actions{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
            margin-bottom:16px;
        }

        .top-actions .btn{
            border-radius:10px;
            padding:10px 16px;
            font-weight:600;
            box-shadow:0 4px 10px rgba(0,0,0,0.06);
        }

        .preview-card{
            background:var(--surface);
            border:1px solid #e9eef5;
            border-radius:18px;
            overflow:hidden;
            box-shadow:var(--shadow);
        }

        .preview-hero{
            background:linear-gradient(135deg, var(--brand-blue) 0%, var(--brand-blue-2) 100%);
            color:#ffffff;
            padding:28px 32px;
            position:relative;
        }

        .preview-hero:after{
            content:"";
            position:absolute;
            right:0;
            top:0;
            width:180px;
            height:100%;
            background:linear-gradient(135deg, rgba(255,255,255,0.10), rgba(255,255,255,0.02));
            pointer-events:none;
        }

        .preview-hero h2{
            margin:0 0 6px;
            color:#ffffff;
            font-size:30px;
            font-weight:700;
            letter-spacing:0.2px;
        }

        .preview-hero p{
            margin:0;
            color:#dbe7ff;
            font-size:15px;
        }

        .preview-body{
            padding:30px;
        }

        .section-block{
            background:#ffffff;
            border:1px solid #edf1f6;
            border-radius:16px;
            padding:22px;
            margin-bottom:22px;
            box-shadow:0 3px 12px rgba(15,23,42,0.03);
        }

        .section-title{
            margin:0 0 18px;
            display:flex;
            align-items:center;
            gap:10px;
            font-size:20px;
            font-weight:700;
            color:var(--brand-blue);
            padding-bottom:10px;
            border-bottom:2px solid #eef3f8;
        }

        .section-dot{
            width:12px;
            height:12px;
            border-radius:50%;
            background:var(--brand);
            box-shadow:0 0 0 4px rgba(252,178,43,0.18);
            flex:0 0 auto;
        }

        .preview-field{
            margin-bottom:18px;
        }

        .preview-label{
            display:block;
            margin-bottom:7px;
            font-size:13px;
            font-weight:700;
            color:#4b5563;
            letter-spacing:0.2px;
            text-transform:uppercase;
        }

        .preview-value{
            min-height:46px;
            padding:12px 14px;
            border:1px solid var(--border);
            border-radius:12px;
            background:linear-gradient(180deg, #ffffff 0%, #f9fbfd 100%);
            white-space:pre-wrap;
            line-height:1.45;
            color:var(--text);
        }

        .preview-value.empty{
            color:#9ca3af;
            font-style:italic;
        }

        .course-item{
            padding:10px 12px;
            border:1px dashed #d6deea;
            border-radius:10px;
            margin-bottom:10px;
            background:#fbfdff;
        }

        .term-list{
            display:grid;
            grid-template-columns:1fr;
            gap:10px;
        }

        .term-item{
            display:flex;
            align-items:flex-start;
            gap:10px;
            padding:12px 14px;
            border:1px solid #e9eef5;
            border-radius:12px;
            background:#fbfcfe;
        }

        .term-badge{
            min-width:28px;
            height:28px;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:15px;
            font-weight:700;
            color:#fff;
            margin-top:1px;
        }

        .term-badge.yes{
            background:var(--success);
        }

        .term-badge.no{
            background:var(--danger);
        }

        .term-text{
            color:#334155;
            line-height:1.45;
        }

        .signature-box{
            border:1px solid var(--border);
            border-radius:14px;
            background:linear-gradient(180deg, #ffffff 0%, #fafcfe 100%);
            padding:14px;
        }

        .signature-image{
            max-width:100%;
            max-height:220px;
            display:block;
            border:1px dashed #cfd8e3;
            border-radius:10px;
            background:#fff;
            padding:10px;
        }

        .audit-grid .preview-value{
            background:#fcfdff;
        }

        .eyd-footer{
            background:linear-gradient(90deg, #fcb22b 0%, #ffbf45 100%);
            color:#ffffff;
            border-top:5px solid rgba(0,0,0,0.08);
            margin-top:24px;
        }

        .eyd-footer-inner{
            max-width:1180px;
            margin:0 auto;
            padding:14px 18px;
            font-size:18px;
            font-weight:800;
            text-align:center;
            text-shadow:0 1px 1px rgba(0,0,0,0.10);
        }

        @media (max-width: 900px){
            .eyd-title{font-size:30px;}
            .eyd-contact{font-size:14px;}
            .preview-hero h2{font-size:24px;}
            .preview-body{padding:18px;}
            .section-block{padding:16px;}
        }

        @media print {
            body{
                background:#ffffff;
            }
            .top-actions{
                display:none !important;
            }
            .page-wrap{
                max-width:100%;
                margin:0;
                padding:0;
            }
            .preview-card{
                box-shadow:none;
                border:none;
                border-radius:0;
            }
            .section-block{
                box-shadow:none;
                break-inside:avoid;
                page-break-inside:avoid;
            }
            .eyd-header,
            .eyd-footer{
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .preview-hero{
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

<?php
$course_ids = !empty($agreement['course_name_decoded']) ? $agreement['course_name_decoded'] : array();
$course_dates = !empty($agreement['training_date_text_decoded']) ? $agreement['training_date_text_decoded'] : array();

$course_map = array();
if (!empty($all_courses)) {
    foreach ($all_courses as $course) {
        if (!empty($course['COURSE_ID']) && !empty($course['NAME'])) {
            $course_map[(string)$course['COURSE_ID']] = $course['NAME'];
        }
    }
}

function pv($value) {
    $value = trim((string)$value);
    if ($value === '') {
        return '<div class="preview-value empty">Not provided</div>';
    }
    return '<div class="preview-value">'.html_escape($value).'</div>';
}
?>

<div class="eyd-header">
    <div class="eyd-header-inner">
        <div class="eyd-logo-wrap">
            <img class="eyd-logo" src="https://empoweryourdestiny.com.au/wp-content/uploads/2023/09/EYD-Logo-without-tag-line.png" alt="Empower Your Destiny">
        </div>
        <div class="eyd-header-text">
            <div class="eyd-title">Empower Your Destiny</div>
            <div class="eyd-contact">Phone: 1800 MY DESTINY | Email: info@empoweryourdestiny.com.au</div>
        </div>
    </div>
</div>

<div class="page-wrap">
    <div class="top-actions">
        <button onclick="window.print();" class="btn btn-primary">Print / Save PDF</button>
        <a href="<?= current_url(); ?>" class="btn btn-outline-secondary">Refresh View</a>
    </div>

    <div class="preview-card">
        <div class="preview-hero">
            <h2>Enrollment and Payment Agreement</h2>
            <p>Filled form preview</p>
        </div>

        <div class="preview-body">

            <div class="section-block">
                <h4 class="section-title"><span class="section-dot"></span>Personal Details</h4>
                <div class="row">
                    <div class="col-md-6 preview-field">
                        <span class="preview-label">Full Name</span>
                        <?= pv($agreement['full_name'] ?? '') ?>
                    </div>
                    <div class="col-md-6 preview-field">
                        <span class="preview-label">Business / Company Name</span>
                        <?= pv($agreement['business_name'] ?? '') ?>
                    </div>
                    <div class="col-md-6 preview-field">
                        <span class="preview-label">Postal Address</span>
                        <?= pv($agreement['postal_address'] ?? '') ?>
                    </div>
                    <div class="col-md-3 preview-field">
                        <span class="preview-label">City / Suburb</span>
                        <?= pv($agreement['city_suburb'] ?? '') ?>
                    </div>
                    <div class="col-md-3 preview-field">
                        <span class="preview-label">Postcode</span>
                        <?= pv($agreement['postcode'] ?? '') ?>
                    </div>
                    <div class="col-md-4 preview-field">
                        <span class="preview-label">Country</span>
                        <?= pv($agreement['country'] ?? '') ?>
                    </div>
                    <div class="col-md-4 preview-field">
                        <span class="preview-label">Contact Number (Mobile)</span>
                        <?= pv($agreement['contact_number_mobile'] ?? '') ?>
                    </div>
                    <div class="col-md-4 preview-field">
                        <span class="preview-label">Contact Number (Work)</span>
                        <?= pv($agreement['contact_number_work'] ?? '') ?>
                    </div>
                    <div class="col-md-4 preview-field">
                        <span class="preview-label">Date of Birth</span>
                        <?= pv($agreement['date_of_birth'] ?? '') ?>
                    </div>
                    <div class="col-md-8 preview-field">
                        <span class="preview-label">Email Address</span>
                        <?= pv($agreement['email_address'] ?? '') ?>
                    </div>
                </div>
            </div>

            <div class="section-block">
                <h4 class="section-title"><span class="section-dot"></span>Emergency Contact</h4>
                <div class="row">
                    <div class="col-md-4 preview-field">
                        <span class="preview-label">Emergency Contact Name</span>
                        <?= pv($agreement['emergency_contact_name'] ?? '') ?>
                    </div>
                    <div class="col-md-4 preview-field">
                        <span class="preview-label">Emergency Contact Number</span>
                        <?= pv($agreement['emergency_contact_number'] ?? '') ?>
                    </div>
                    <div class="col-md-4 preview-field">
                        <span class="preview-label">Relationship</span>
                        <?= pv($agreement['emergency_contact_relationship'] ?? '') ?>
                    </div>
                </div>
            </div>

            <div class="section-block">
                <h4 class="section-title"><span class="section-dot"></span>Course Details</h4>

                <div class="preview-field">
                    <span class="preview-label">Selected Courses</span>
                    <div class="preview-value">
                        <?php if (!empty($course_ids)): ?>
                            <?php foreach ($course_ids as $cid): ?>
                                <div class="course-item">
                                    <strong><?= html_escape(isset($course_map[(string)$cid]) ? $course_map[(string)$cid] : $cid); ?></strong>
                                    <?php if (!empty($course_dates[$cid])): ?>
                                        <div style="margin-top:4px; color:#475467;">Date: <?= html_escape($course_dates[$cid]); ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if (!empty($agreement['other_course_name'])): ?>
                            <div class="course-item">
                                <strong>Other: <?= html_escape($agreement['other_course_name']); ?></strong>
                                <?php if (!empty($course_dates['other'])): ?>
                                    <div style="margin-top:4px; color:#475467;">Date: <?= html_escape($course_dates['other']); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (empty($course_ids) && empty($agreement['other_course_name'])): ?>
                            <span style="color:#9ca3af;font-style:italic;">No courses selected</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <?php $this->load->view('payment_agreement/partials/delivery_services_notice', ['form_settings' => $form_settings ?? [], 'variant' => 'preview']); ?>
                    <div class="col-md-4 preview-field">
                        <span class="preview-label">Total (inc GST)</span>
                        <?= pv($agreement['total_inc_gst'] ?? '') ?>
                    </div>
                    <div class="col-md-4 preview-field">
                        <span class="preview-label">Deposit Paid $</span>
                        <?= pv($agreement['deposit_amount'] ?? '') ?>
                    </div>
                    <div class="col-md-4 preview-field">
                        <span class="preview-label">Deposit Paid Via</span>
                        <?= pv($agreement['deposit_paid_via'] ?? '') ?>
                    </div>
                    <div class="col-md-4 preview-field">
                        <span class="preview-label">Deposit Paid On</span>
                        <?= pv($agreement['deposit_paid_on'] ?? '') ?>
                    </div>
                    <div class="col-md-4 preview-field">
                        <span class="preview-label">Balance payable $</span>
                        <?= pv($agreement['balance_amount'] ?? '') ?>
                    </div>
                    <div class="col-md-4 preview-field">
                        <span class="preview-label">Due Date</span>
                        <?= pv($agreement['due_date'] ?? '') ?>
                    </div>
                </div>
            </div>

            <div class="section-block">
                <h4 class="section-title"><span class="section-dot"></span>Certification</h4>
                <div class="row">
                    <div class="col-md-4 preview-field">
                        <span class="preview-label">Holds Certification</span>
                        <?= pv(!empty($agreement['holds_certification']) ? 'Yes' : 'No') ?>
                    </div>
                    <div class="col-md-8 preview-field">
                        <span class="preview-label">Certification Details</span>
                        <?= pv($agreement['certification_details'] ?? '') ?>
                    </div>
                </div>
            </div>

            <div class="section-block">
                <h4 class="section-title"><span class="section-dot"></span>Payment Details</h4>
                <div class="row">
                    <?php $this->load->view('payment_agreement/partials/payment_modes_notice', ['form_settings' => $form_settings ?? [], 'variant' => 'preview']); ?>
                    <div class="col-md-6 preview-field">
                        <span class="preview-label">Preferred payment method</span>
                        <?= pv($agreement['payment_option'] ?? '') ?>
                    </div>
                </div>
            </div>

            <?php
                $fsp = $form_settings ?? [];
                $ct_prev = isset($consent_term_labels) ? $consent_term_labels : [];
                $rich_p = isset($fsp['consent_rich_html']) ? trim((string)$fsp['consent_rich_html']) : '';
                $show_ct_h = !empty($show_consent_terms_heading);
                $has_consent_prev = $show_ct_h || ($rich_p !== '');
            ?>
            <?php if ($has_consent_prev): ?>
            <div class="section-block">
                <?php if ($show_ct_h): ?>
                <h4 class="section-title"><span class="section-dot"></span><?= html_escape(!empty($fsp['section_terms']) ? $fsp['section_terms'] : 'Consent & Terms'); ?></h4>
                <?php endif; ?>
                <div class="row">
                    <?php $this->load->view('payment_agreement/partials/consent_rich_display', ['form_settings' => $fsp, 'variant' => 'preview']); ?>
                </div>
                <div class="term-list">
                    <?php for ($ti = 1; $ti <= 6; $ti++): ?>
                        <?php
                            $lt = isset($ct_prev[$ti]) ? $ct_prev[$ti] : '';
                            if ($lt === '') {
                                continue;
                            }
                        ?>
                    <div class="term-item">
                        <div class="term-badge <?= !empty($agreement['agree_terms_' . $ti]) ? 'yes' : 'no'; ?>">
                            <?= !empty($agreement['agree_terms_' . $ti]) ? '✓' : '✕'; ?>
                        </div>
                        <div class="term-text"><?= html_escape($lt); ?></div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="section-block">
                <h4 class="section-title"><span class="section-dot"></span>Signature</h4>
                <div class="row">
                    <div class="col-md-6 preview-field">
                        <span class="preview-label">Signature Name</span>
                        <?= pv($agreement['signature'] ?? '') ?>
                    </div>
                    <div class="col-md-6 preview-field">
                        <span class="preview-label">Signature Date</span>
                        <?= pv($agreement['signature_date'] ?? '') ?>
                    </div>

                    <?php if (!empty($agreement['signature_image'])): ?>
                        <div class="col-md-12 preview-field">
                            <span class="preview-label">Drawn Signature</span>
                            <div class="signature-box">
                                <img src="<?= $agreement['signature_image']; ?>" class="signature-image" alt="Signature">
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="col-md-6 preview-field">
                        <span class="preview-label">Approved By</span>
                        <?= pv($agreement['approved_by'] ?? '') ?>
                    </div>
                    <div class="col-md-6 preview-field">
                        <span class="preview-label">Approval Date</span>
                        <?= pv($agreement['approval_date'] ?? '') ?>
                    </div>
                </div>
            </div>

            <div class="section-block">
                <h4 class="section-title"><span class="section-dot"></span>Audit</h4>
                <div class="row audit-grid">
                    <div class="col-md-4 preview-field">
                        <span class="preview-label">IP Address</span>
                        <?= pv($agreement['ip_address'] ?? '') ?>
                    </div>
                    <div class="col-md-4 preview-field">
                        <span class="preview-label">Client Timezone</span>
                        <?= pv($agreement['client_timezone'] ?? '') ?>
                    </div>
                    <div class="col-md-4 preview-field">
                        <span class="preview-label">Screen Resolution</span>
                        <?= pv($agreement['screen_resolution'] ?? '') ?>
                    </div>
                    <div class="col-md-6 preview-field">
                        <span class="preview-label">Client Time ISO</span>
                        <?= pv($agreement['client_time_iso'] ?? '') ?>
                    </div>
                    <div class="col-md-6 preview-field">
                        <span class="preview-label">User Agent</span>
                        <?= pv($agreement['user_agent'] ?? '') ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="eyd-footer">
    <div class="eyd-footer-inner">
        Empower Your Destiny I Enrollment and Payment Agreement I <?= date('Y'); ?>
    </div>
</div>

</body>
</html>