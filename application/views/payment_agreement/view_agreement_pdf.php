<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Enrollment and Payment Agreement</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #222;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .header {
            background: #fcb22b;
            color: #fff;
            padding: 18px 24px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
        }

        .logo-box {
            width: 90px;
        }

        .logo {
            width: 70px;
            height: auto;
        }

        .brand-title {
            font-size: 28px;
            font-weight: bold;
            margin: 0 0 4px 0;
            color: #fff;
        }

        .brand-sub {
            font-size: 13px;
            color: #fff;
            margin: 0;
        }

        .hero {
            background: #1b2a4e;
            color: #fff;
            padding: 16px 24px;
        }

        .hero h2 {
            margin: 0 0 4px 0;
            color: #fff;
            font-size: 22px;
        }

        .hero p {
            margin: 0;
            color: #dbe7ff;
            font-size: 12px;
        }

        .container {
            padding: 20px 24px;
        }

        .section {
            margin-bottom: 18px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 14px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1b2a4e;
            margin: 0 0 12px 0;
            padding-bottom: 6px;
            border-bottom: 1px solid #e5e7eb;
        }

        table.grid {
            width: 100%;
            border-collapse: collapse;
        }

        table.grid td {
            width: 50%;
            vertical-align: top;
            padding: 6px 8px 10px 0;
        }

        .label {
            font-size: 10px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .value {
            border: 1px solid #dbe2ea;
            background: #fafafa;
            padding: 8px 10px;
            border-radius: 6px;
            min-height: 18px;
            word-wrap: break-word;
        }

        .course-box {
            border: 1px solid #dbe2ea;
            background: #fafafa;
            padding: 8px 10px;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        .term-item {
            border: 1px solid #dbe2ea;
            background: #fafafa;
            padding: 8px 10px;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        .signature-img {
            max-width: 320px;
            max-height: 140px;
            border: 1px solid #dbe2ea;
            padding: 6px;
            background: #fff;
        }

        .footer {
            background: #fcb22b;
            color: #fff;
            text-align: center;
            padding: 10px 16px;
            font-weight: bold;
            font-size: 12px;
            margin-top: 20px;
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

function pdfv($value) {
    $value = trim((string)$value);
    return $value !== '' ? htmlspecialchars($value) : '-';
}
?>

<div class="header">
    <table class="header-table">
        <tr>
            <td class="logo-box">
                <img class="logo" src="https://empoweryourdestiny.com.au/wp-content/uploads/2023/09/EYD-Logo-without-tag-line.png" alt="Logo">
            </td>
            <td>
                <div class="brand-title">Empower Your Destiny</div>
                <div class="brand-sub">Phone: 1800 MY DESTINY | Email: info@empoweryourdestiny.com.au</div>
            </td>
        </tr>
    </table>
</div>

<div class="hero">
    <h2>Enrollment and Payment Agreement</h2>
    <p>Filled form preview</p>
</div>

<div class="container">

    <div class="section">
        <div class="section-title">Personal Details</div>
        <table class="grid">
            <tr>
                <td><div class="label">Full Name</div><div class="value"><?= pdfv($agreement['full_name'] ?? '') ?></div></td>
                <td><div class="label">Business / Company Name</div><div class="value"><?= pdfv($agreement['business_name'] ?? '') ?></div></td>
            </tr>
            <tr>
                <td><div class="label">Postal Address</div><div class="value"><?= pdfv($agreement['postal_address'] ?? '') ?></div></td>
                <td><div class="label">City / Suburb</div><div class="value"><?= pdfv($agreement['city_suburb'] ?? '') ?></div></td>
            </tr>
            <tr>
                <td><div class="label">Postcode</div><div class="value"><?= pdfv($agreement['postcode'] ?? '') ?></div></td>
                <td><div class="label">Country</div><div class="value"><?= pdfv($agreement['country'] ?? '') ?></div></td>
            </tr>
            <tr>
                <td><div class="label">Contact Number (Mobile)</div><div class="value"><?= pdfv($agreement['contact_number_mobile'] ?? '') ?></div></td>
                <td><div class="label">Contact Number (Work)</div><div class="value"><?= pdfv($agreement['contact_number_work'] ?? '') ?></div></td>
            </tr>
            <tr>
                <td><div class="label">Date of Birth</div><div class="value"><?= pdfv($agreement['date_of_birth'] ?? '') ?></div></td>
                <td><div class="label">Email Address</div><div class="value"><?= pdfv($agreement['email_address'] ?? '') ?></div></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Emergency Contact</div>
        <table class="grid">
            <tr>
                <td><div class="label">Emergency Contact Name</div><div class="value"><?= pdfv($agreement['emergency_contact_name'] ?? '') ?></div></td>
                <td><div class="label">Emergency Contact Number</div><div class="value"><?= pdfv($agreement['emergency_contact_number'] ?? '') ?></div></td>
            </tr>
            <tr>
                <td><div class="label">Relationship</div><div class="value"><?= pdfv($agreement['emergency_contact_relationship'] ?? '') ?></div></td>
                <td></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Course Details</div>
        <div class="label">Selected Courses</div>
        <div class="value">
            <?php if (!empty($course_ids)): ?>
                <?php foreach ($course_ids as $cid): ?>
                    <div class="course-box">
                        <strong><?= htmlspecialchars(isset($course_map[(string)$cid]) ? $course_map[(string)$cid] : $cid); ?></strong>
                        <?php if (!empty($course_dates[$cid])): ?>
                            <div>Date: <?= htmlspecialchars($course_dates[$cid]); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($agreement['other_course_name'])): ?>
                <div class="course-box">
                    <strong>Other: <?= htmlspecialchars($agreement['other_course_name']); ?></strong>
                    <?php if (!empty($course_dates['other'])): ?>
                        <div>Date: <?= htmlspecialchars($course_dates['other']); ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (empty($course_ids) && empty($agreement['other_course_name'])): ?>
                -
            <?php endif; ?>
        </div>

        <table class="grid" style="margin-top:10px;">
            <tr>
                <td colspan="2"><?php $this->load->view('payment_agreement/partials/delivery_services_notice', ['form_settings' => $form_settings ?? [], 'variant' => 'pdf']); ?></td>
            </tr>
            <tr>
                <td><div class="label">Total (inc GST)</div><div class="value"><?= pdfv($agreement['total_inc_gst'] ?? '') ?></div></td>
                <td><div class="label">Deposit Paid $</div><div class="value"><?= pdfv($agreement['deposit_amount'] ?? '') ?></div></td>
            </tr>
            <tr>
                <td><div class="label">Deposit Paid Via</div><div class="value"><?= pdfv($agreement['deposit_paid_via'] ?? '') ?></div></td>
                <td><div class="label">Deposit Paid On</div><div class="value"><?= pdfv($agreement['deposit_paid_on'] ?? '') ?></div></td>
            </tr>
            <tr>
                <td><div class="label">Balance payable $</div><div class="value"><?= pdfv($agreement['balance_amount'] ?? '') ?></div></td>
                <td><div class="label">Due Date</div><div class="value"><?= pdfv($agreement['due_date'] ?? '') ?></div></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Certification</div>
        <table class="grid">
            <tr>
                <td><div class="label">Holds Certification</div><div class="value"><?= !empty($agreement['holds_certification']) ? 'Yes' : 'No' ?></div></td>
                <td><div class="label">Certification Details</div><div class="value"><?= pdfv($agreement['certification_details'] ?? '') ?></div></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Payment Details</div>
        <table class="grid">
            <tr>
                <td colspan="2"><?php $this->load->view('payment_agreement/partials/payment_modes_notice', ['form_settings' => $form_settings ?? [], 'variant' => 'pdf']); ?></td>
            </tr>
            <tr>
                <td colspan="2"><div class="label">Preferred payment method</div><div class="value"><?= pdfv($agreement['payment_option'] ?? '') ?></div></td>
            </tr>
        </table>
    </div>

    <?php
        $fspdf = $form_settings ?? [];
        $ct_pdf = isset($consent_term_labels) ? $consent_term_labels : [];
        $rich_pdf = isset($fspdf['consent_rich_html']) ? trim((string)$fspdf['consent_rich_html']) : '';
        $show_pdf_h = !empty($show_consent_terms_heading);
        $has_consent_pdf = $show_pdf_h || ($rich_pdf !== '');
    ?>
    <?php if ($has_consent_pdf): ?>
    <div class="section">
        <?php if ($show_pdf_h): ?>
        <div class="section-title"><?= htmlspecialchars(!empty($fspdf['section_terms']) ? $fspdf['section_terms'] : 'Consent & Terms', ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <?php $this->load->view('payment_agreement/partials/consent_rich_display', ['form_settings' => $fspdf, 'variant' => 'pdf']); ?>
        <?php for ($ti = 1; $ti <= 6; $ti++): ?>
            <?php
                $ltp = isset($ct_pdf[$ti]) ? $ct_pdf[$ti] : '';
                if ($ltp === '') {
                    continue;
                }
            ?>
        <div class="term-item"><?= !empty($agreement['agree_terms_' . $ti]) ? 'Yes' : 'No' ?> — <?= htmlspecialchars($ltp, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endfor; ?>
    </div>
    <?php endif; ?>

    <div class="section">
        <div class="section-title">Signature</div>
        <table class="grid">
            <tr>
                <td><div class="label">Signature Name</div><div class="value"><?= pdfv($agreement['signature'] ?? '') ?></div></td>
                <td><div class="label">Signature Date</div><div class="value"><?= pdfv($agreement['signature_date'] ?? '') ?></div></td>
            </tr>
        </table>

        <?php if (!empty($agreement['signature_image'])): ?>
            <div style="margin-top:12px;">
                <div class="label">Drawn Signature</div>
                <img src="<?= $agreement['signature_image']; ?>" class="signature-img" alt="Signature">
            </div>
        <?php endif; ?>
    </div>

    <div class="section">
        <div class="section-title">Audit</div>
        <table class="grid">
            <tr>
                <td><div class="label">IP Address</div><div class="value"><?= pdfv($agreement['ip_address'] ?? '') ?></div></td>
                <td><div class="label">Client Timezone</div><div class="value"><?= pdfv($agreement['client_timezone'] ?? '') ?></div></td>
            </tr>
            <tr>
                <td><div class="label">Client Time ISO</div><div class="value"><?= pdfv($agreement['client_time_iso'] ?? '') ?></div></td>
                <td><div class="label">Screen Resolution</div><div class="value"><?= pdfv($agreement['screen_resolution'] ?? '') ?></div></td>
            </tr>
            <tr>
                <td colspan="2"><div class="label">User Agent</div><div class="value"><?= pdfv($agreement['user_agent'] ?? '') ?></div></td>
            </tr>
        </table>
    </div>

</div>

<div class="footer">
    Empower Your Destiny I Enrollment and Payment Agreement I <?= date('Y'); ?>
</div>

</body>
</html>