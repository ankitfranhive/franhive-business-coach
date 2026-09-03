<?php
$fs = isset($form_settings) ? $form_settings : [];
$consent_term_labels = isset($consent_term_labels) ? $consent_term_labels : [];
$show_consent_terms_heading = !empty($show_consent_terms_heading);
function fs($arr, $key, $default = '')
{
    return !empty($arr[$key]) ? $arr[$key] : $default;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Enrollment and Payment Agreement | Empower Your Destiny</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="<?= base_url('vendors/styles/core.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('vendors/styles/icon-font.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('vendors/styles/style.css'); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css">

    <style>
        :root{
            --brand:#fcb22b;
            --brand-dark:#1f1f1f;
            --brand-blue:#1b2a4e;
            --bg:#f5f6fa;
            --text:#222;
            --muted:#666;
            --border:#e8e8e8;
        }

        body{
            margin:0;
            background:var(--bg);
            color:var(--text);
            font-family:Arial, Helvetica, sans-serif;
        }

        .eyd-header{
            background:var(--brand);
            border-bottom:4px solid rgba(0,0,0,0.08);
        }
        .eyd-header-inner{
            max-width:1180px;
            margin:0 auto;
            padding:18px;
            display:flex;
            align-items:center;
            gap:18px;
        }
        .eyd-logo-wrap{
            width:90px;height:90px;border-radius:999px;
            background:var(--brand-dark);border:6px solid #fff;
            display:flex;align-items:center;justify-content:center;
            overflow:hidden;box-shadow:0 6px 18px rgba(0,0,0,0.18);flex:0 0 auto;
        }
        .eyd-logo{ width:92%;height:auto;display:block; }
        .eyd-header-text{ color:#fff;line-height:1.05;min-width:0; }
        .eyd-title{
            font-size:42px;font-weight:300;text-decoration:underline;
            text-underline-offset:10px;text-decoration-thickness:6px;
            margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
        }
        .eyd-contact{
            margin-top:10px;font-size:18px;font-weight:600;opacity:0.95;
            white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
        }

        .public-wrapper{ max-width:1100px;margin:30px auto;padding:15px; }
        .public-card{
            background:#fff;border-radius:14px;
            box-shadow:0 4px 24px rgba(0,0,0,0.08);
            overflow:visible;           /* was overflow:hidden — was clipping Bootstrap's
                                           position:absolute custom-control-input checkboxes */
            border:1px solid #f0f0f0;
        }
        /* Ensure Bootstrap custom checkboxes/radios are always clickable */
        .custom-control{
            position:relative;
            z-index:0;
        }
        .custom-control-input{
            position:absolute;
            z-index:1;                  /* above any stacking context siblings */
            pointer-events:auto !important;
        }
        .custom-control-label{
            cursor:pointer;
        }
        .public-header{ background:var(--brand-blue);color:#fff;padding:25px 30px; }
        .public-header h2{ margin:0 0 8px;color:#fff; }
        .public-body{ padding:30px; }

        .section-title{
            margin-top:28px;margin-bottom:16px;padding-bottom:8px;
            border-bottom:1px solid var(--border);font-weight:700;color:var(--brand-blue);
        }
        .form-group label{ font-weight:600;margin-bottom:6px;display:block; }
        .note-text{ font-size:13px;color:var(--muted); }
        .footer-note{ margin-top:20px;font-size:13px;color:var(--muted);text-align:center; }

        .eyd-footer{
            background:var(--brand);color:#fff;
            border-top:4px solid rgba(0,0,0,0.08);margin-top:24px;
        }
        .eyd-footer-inner{
            max-width:1180px;margin:0 auto;padding:14px;
            font-size:20px;font-weight:800;letter-spacing:0.2px;
            white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-align:center;
        }

        /* ── Signature ── */
        .signature-box{
            border:1px solid #dcdcdc;border-radius:12px;background:#fff;
            width:100%;height:220px;touch-action:none;display:block;
        }
        .signature-actions{ display:flex;gap:10px;flex-wrap:wrap;margin-top:12px; }
        .signature-note{ font-size:12px;color:#777;margin-top:6px; }

        /* ── Notices ── */
        .delivery-services-notice,.payment-modes-notice{
            border:1px solid var(--border);border-radius:12px;
            padding:16px 18px;background:#f9fafb;margin-bottom:8px;
        }
        .delivery-services-notice-title,.payment-modes-notice-title{
            font-weight:700;color:var(--brand-blue);font-size:16px;margin-bottom:10px;
        }
        .delivery-services-notice-body,.payment-modes-notice-body{
            font-size:14px;line-height:1.55;color:var(--text);
        }

        /* ── Consent ── */
        .consent-copy-panel,.consent-checkboxes-panel{
            --consent-panel-bg:#ffffff;
            border:1px solid var(--border);border-radius:12px;
            padding:16px 18px;margin-bottom:16px;
            background:var(--consent-panel-bg);
            box-shadow:0 1px 3px rgba(27,42,78,0.06);
        }
        .consent-checkboxes-panel{
            --consent-panel-bg:#f3f6fb;border-color:#c5d0e0;
        }
        .consent-copy-panel .consent-rich-block{ margin-bottom:0; }
        .consent-checkboxes-panel-title{
            font-size:15px;font-weight:700;color:var(--brand-blue);
            margin:0 0 14px;padding-bottom:8px;border-bottom:1px solid var(--border);
        }
        .consent-rich-heading{ font-weight:700;color:var(--brand-blue);font-size:16px; }
        .consent-rich-body{ font-size:14px;line-height:1.55;color:var(--text); }
        .consent-rich-body p{ margin:0 0 0.55em; }
        .consent-rich-body p:last-child{ margin-bottom:0; }
        .consent-rich-body ul,.consent-rich-body ol{ margin:0.4em 0 0.6em;padding-left:1.35em; }
        .consent-scroll-wrap{ position:relative;display:block;min-width:0; }
        .consent-scroll-box{
            --consent-line-h:1.55;--consent-visible-lines:6;
            line-height:var(--consent-line-h);
            max-height:calc(var(--consent-line-h) * var(--consent-visible-lines) * 1em);
            overflow-y:auto;overflow-x:hidden;-webkit-overflow-scrolling:touch;
            scrollbar-width:thin;scrollbar-color:rgba(27,42,78,0.35) transparent;
            padding-right:6px;
        }
        .consent-scroll-box::-webkit-scrollbar{ width:6px; }
        .consent-scroll-box::-webkit-scrollbar-thumb{ background:rgba(27,42,78,0.3);border-radius:4px; }
        .consent-scroll-wrap.has-more::after{
            content:'';position:absolute;left:0;right:0;bottom:38px;height:3em;
            pointer-events:none;
            background:linear-gradient(to bottom, rgba(255,255,255,0) 0%, var(--consent-scroll-fade-bg, var(--consent-panel-bg)) 88%);
            transition:opacity 0.2s ease;
        }
        .consent-scroll-wrap.has-more.is-at-bottom::after{ opacity:0; }
        .consent-scroll-hint{
            display:none;align-items:center;justify-content:center;gap:8px;
            margin-top:10px;padding:9px 14px;font-size:13px;font-weight:700;
            color:var(--brand-blue);letter-spacing:0.02em;border:1px dashed #9aafc8;
            border-radius:8px;background:linear-gradient(180deg,#fff9e8 0%,#fff3cc 100%);
            box-shadow:0 1px 2px rgba(27,42,78,0.08);
        }
        .consent-scroll-wrap.has-more .consent-scroll-hint{ display:flex; }
        .consent-scroll-wrap.has-more.is-at-bottom .consent-scroll-hint{ display:none; }
        .consent-scroll-hint-icon{
            display:inline-block;font-size:14px;line-height:1;
            animation:consent-scroll-bounce 1.4s ease-in-out infinite;
        }
        @keyframes consent-scroll-bounce{
            0%,100%{ transform:translateY(0); }
            50%{ transform:translateY(3px); }
        }
        .consent-checkboxes-panel .custom-control{
            --consent-scroll-fade-bg:#ffffff;
            align-items:flex-start;padding:10px 12px;margin-bottom:10px;
            border:1px solid #dbe2ea;border-radius:8px;background:#fff;
        }
        .consent-checkboxes-panel .custom-control:last-child{ margin-bottom:0; }
        .consent-checkboxes-panel .custom-control-input{ margin-top:0.35em; }
        .consent-checkboxes-panel .custom-control-label .consent-scroll-box{ font-size:14px;font-weight:400; }

        /* ══════════════════════════════════════════════════
           PHONE INPUT COMBO  –  flag+dialcode | number
           ══════════════════════════════════════════════════ */
        .phone-combo{
            display:flex;
            align-items:stretch;
            border:1px solid #ced4da;
            border-radius:6px;
            height: 45px;
            overflow:visible;
            background:#fff;
            position:relative;
            transition:border-color .15s ease, box-shadow .15s ease;
        }
        .phone-combo:focus-within{
            border-color:#80bdff;
            box-shadow:0 0 0 0.2rem rgba(0,123,255,.25);
        }
        /* iti wrapper — no border, no overflow clipping */
        .phone-combo .iti{
            flex:0 0 auto;
            border:none !important;
            box-shadow:none !important;
            overflow:visible !important;
        }
        .phone-combo .iti--separate-dial-code{ width:auto !important; }
        /* picker input: give it real height, no border, transparent */
        .phone-combo input.iti-phone-picker{
            border:none !important;
            box-shadow:none !important;
            background:transparent !important;
            height:38px !important;
            width:1px !important;
            min-width:0 !important;
            padding:0 !important;
            margin:0 !important;
            opacity:0 !important;
            pointer-events:none !important;  /* never intercept clicks */
        }
        /* Flag + dial-code button: vertically centered, proper padding */
        .phone-combo .iti--separate-dial-code .iti__selected-flag{
            height:38px !important;
            display:flex !important;
            align-items:center !important;
            padding:0 8px 0 10px !important;
            background:transparent !important;
            border-radius:5px 0 0 5px;
        }
        .phone-combo .iti--separate-dial-code .iti__selected-dial-code{
            font-size:14px;
            line-height:1;
            margin-left:4px;
        }
        .phone-combo .iti__flag-container{
            position:relative;
            height:38px;
            display:flex;
            align-items:center;
        }
        /* Country dropdown — use fixed positioning via JS override below
           so it escapes ALL overflow:hidden ancestors */
        .phone-combo .iti__country-list{
            z-index:9999 !important;
            min-width:280px !important;
            max-height:220px !important;
            overflow-y:auto !important;
            box-shadow:0 6px 20px rgba(0,0,0,0.15) !important;
            border:1px solid #ced4da !important;
            border-radius:6px !important;
            background:#fff !important;
        }
        /* Vertical divider */
        .phone-combo .phone-combo-divider{
            width:1px;
            background:#ced4da;
            flex:0 0 1px;
            align-self:stretch;
        }
        /* Number input half */
        .phone-combo input.phone-number-input{
            flex:1 1 0;
            border:none !important;
            box-shadow:none !important;
            border-radius:0 5px 5px 0 !important;
            background:transparent;
            padding:0 12px;
            font-size:14px;
            min-width:0;
            height:38px;
        }
        .phone-combo input.phone-number-input:focus,
        .phone-combo input.iti-phone-picker:focus{
            outline:none;
            box-shadow:none !important;
        }

        /* ══════════════════════════════════════
           POSTCODE  –  narrow
           ══════════════════════════════════════ */
        input[name="postcode"]{
            max-width:110px;
        }

        /* ══════════════════════════════════════
           ADDRESS ROW  –  Postal | City | Post
           ══════════════════════════════════════ */
        .address-row{
            display:flex;
            gap:16px;
            flex-wrap:nowrap;
            align-items:flex-end;
            margin-bottom:0;
        }
        .address-row .addr-postal{ flex:2 1 0;min-width:0; }
        .address-row .addr-city  { flex:1.2 1 0;min-width:0; }
        .address-row .addr-post  { flex:0 0 110px;width:110px; }

        /* ══════════════════════════════════════
           AMOUNT FIELDS  –  compact
           ══════════════════════════════════════ */
        input[name="total_inc_gst"],
        input[name="deposit_amount"],
        input[name="balance_amount"]{
            max-width:180px;
        }

        /* ── Misc ── */
        .other-course-wrap{ display:none;margin-top:12px; }

        @media (max-width:900px){
            .eyd-title{font-size:30px;}
            .eyd-contact{font-size:15px;}
            .eyd-footer-inner{font-size:16px;}
            .eyd-logo-wrap{width:78px;height:78px;}
            .address-row{ flex-wrap:wrap; }
            .address-row .addr-post{ flex:0 0 100%; width:100%; }
            input[name="postcode"]{ max-width:100%; }
            .email-dob-row{ flex-wrap:wrap; }
        }
        @media (max-width:600px){
            .phone-combo input.iti-phone-picker{ width:90px; }
        }
    </style>
</head>
<body>

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

<div class="public-wrapper">
    <div class="public-card">
        <div class="public-header">
            <h2><?= html_escape(fs($fs, 'form_title', 'Enrollment and Payment Agreement')); ?></h2>
        </div>

        <div class="public-body">
            <div class="mb-20">
                <p><strong>Client Name:</strong> <?= html_escape($request['client_name']); ?></p>
                <p><strong>Email:</strong> <?= html_escape($request['client_email']); ?></p>
                <?php
                    $req_total_display = isset($request['total_inc_gst']) && $request['total_inc_gst'] !== null && $request['total_inc_gst'] !== ''
                        ? number_format((float)$request['total_inc_gst'], 2, '.', '') : '';
                    $req_deposit_display = isset($request['deposit_amount']) && $request['deposit_amount'] !== null && $request['deposit_amount'] !== ''
                        ? number_format((float)$request['deposit_amount'], 2, '.', '') : '';
                    $req_deposit_date_display = !empty($request['deposit_paid_on']) ? (string)$request['deposit_paid_on'] : '';
                    $has_payment_summary = ($req_total_display !== '' || $req_deposit_display !== '' || $req_deposit_date_display !== '');
                ?>
                <?php if ($has_payment_summary): ?>
                <div class="alert alert-info mt-3 mb-3">
                    <strong>Payment summary for this agreement</strong>
                    <div class="mt-2">
                        <?php if ($req_total_display !== ''): ?>
                            <div><strong>Total (inc GST):</strong> $<?= html_escape($req_total_display); ?></div>
                        <?php endif; ?>
                        <?php if ($req_deposit_display !== ''): ?>
                            <div><strong>Deposit Amount:</strong> $<?= html_escape($req_deposit_display); ?></div>
                        <?php endif; ?>
                        <?php if ($req_deposit_date_display !== ''): ?>
                            <div><strong>Deposit Date:</strong> <?= html_escape($req_deposit_date_display); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (!empty($request['business_name'])): ?>
                <?php endif; ?>
                <p class="note-text mb-0"><?= html_escape(fs($fs, 'intro_text', 'This form has 16 required fields')); ?></p>
            </div>

            <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>
            <?php
                $flash_err = $this->session->flashdata('error');
                if ($flash_err):
            ?>
                <div class="alert alert-danger"><?= $flash_err; ?></div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('payment-agreement/submit/' . $request['token']); ?>" enctype="multipart/form-data" onsubmit="return beforeSubmit();">

                <h4 class="section-title"><?= html_escape(fs($fs, 'section_personal_details', 'Personal Details')); ?></h4>
                <?php
                    $prefill = isset($request['client_name']) ? trim((string)$request['client_name']) : '';
                    $prefill_first = $prefill_last = '';
                    if ($prefill !== '') {
                        $parts = preg_split('/\s+/', $prefill, 2);
                        $prefill_first = $parts[0] ?? '';
                        $prefill_last  = isset($parts[1]) ? trim($parts[1]) : '';
                    }
                ?>

                <!-- Row 1: First Name | Last Name | Business Name -->
                <div class="row">
                    <div class="col-md-3 form-group mb-3">
                        <label><?= html_escape(fs($fs, 'label_first_name', 'First Name')); ?> <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control js-alpha-name" value="<?= set_value('first_name', $prefill_first); ?>"
                               maxlength="60" required pattern="[A-Za-z][A-Za-z .'\-]{0,59}"
                               title="Enter a valid first name (letters only)">
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label><?= html_escape(fs($fs, 'label_last_name', 'Last Name')); ?> <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control js-alpha-name" value="<?= set_value('last_name', $prefill_last); ?>"
                               maxlength="60" required pattern="[A-Za-z][A-Za-z .'\-]{0,59}"
                               title="Enter a valid last name (letters only)">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label>Business / Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="business_name" class="form-control" value="<?= set_value('business_name'); ?>" maxlength="120" required
                               title="Business / company name is required">
                    </div>
                </div>

                <!-- Row 2: Postal Address | City/Suburb | Postcode (flex row, postcode fixed-width) -->
                <div class="address-row mb-3">
                    <div class="addr-postal form-group mb-0">
                        <label>Postal Address <span class="text-danger">*</span></label>
                        <input type="text" name="postal_address" class="form-control" value="<?= set_value('postal_address'); ?>" required
                               maxlength="200" title="Postal address is required">
                    </div>
                    <div class="addr-city form-group mb-0">
                        <label>City / Suburb <span class="text-danger">*</span></label>
                        <input type="text" name="city_suburb" class="form-control js-alpha-name" value="<?= set_value('city_suburb'); ?>" required
                               maxlength="80" pattern="[A-Za-z][A-Za-z .'\-]{0,79}"
                               title="Enter a valid city / suburb (letters only)">
                    </div>
                    <div class="addr-post form-group mb-0">
                        <label>Postcode <span class="text-danger">*</span></label>
                        <input type="text" name="postcode" id="postcode" class="form-control js-digits-only" value="<?= set_value('postcode'); ?>"
                               maxlength="10" required inputmode="numeric" pattern="[0-9]{3,10}"
                               title="Postcode must be 3–10 digits">
                    </div>
                </div>

                <!-- Row 3: Mobile phone combo (flag+dial | number) | Work phone combo -->
                <div class="row mb-3">
                    <div class="col-md-6 form-group mb-3 mb-md-0">
                        <label>Contact Number (Mobile) <span class="text-danger">*</span></label>
                        <div class="phone-combo" id="mobile_phone_combo">
                            <input type="tel" id="country_code_picker" class="iti-phone-picker" autocomplete="off">
                            <input type="hidden" name="country_code" id="country_code" value="<?= html_escape(set_value('country_code', '+61')); ?>">
                            <span class="phone-combo-divider"></span>
                            <input type="text" name="contact_number_mobile" class="phone-number-input js-digits-only" placeholder="Mobile number"
                                   value="<?= set_value('contact_number_mobile'); ?>"
                                   inputmode="numeric" pattern="[0-9]{7,15}" maxlength="15" required
                                   title="Enter 7–15 digits only">
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-0">
                        <label>Contact Number (Work) <span class="text-danger">*</span></label>
                        <div class="phone-combo" id="work_phone_combo">
                            <input type="tel" id="work_country_code_picker" class="iti-phone-picker" autocomplete="off">
                            <input type="hidden" name="country_code_work" id="country_code_work" value="<?= html_escape(set_value('country_code_work', '+61')); ?>">
                            <span class="phone-combo-divider"></span>
                            <input type="text" name="contact_number_work" class="phone-number-input js-digits-only" placeholder="Work number"
                                   value="<?= set_value('contact_number_work'); ?>"
                                   inputmode="numeric" pattern="[0-9]{7,15}" maxlength="15" required
                                   title="Enter 7–15 digits only">
                        </div>
                    </div>
                </div>

                <!-- Row 4: Date of Birth | Email -->
                <div class="row mt-3">
                    <div class="col-md-4 form-group mb-3">
                        <label>Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" name="date_of_birth" class="form-control" value="<?= set_value('date_of_birth'); ?>" required
                               max="<?= date('Y-m-d'); ?>" title="Enter a valid date of birth">
                    </div>
                    <div class="col-md-8 form-group mb-3">
                        <label>Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email_address" id="email_address" class="form-control"
                               value="<?= set_value('email_address', $request['client_email']); ?>" required maxlength="120"
                               pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}"
                               title="Enter a valid email address (e.g. name@example.com)">
                    </div>
                </div>

                <!-- ── Emergency Contact ── -->
                <h4 class="section-title">Emergency Contact</h4>
                <div class="row">
                    <div class="col-md-4 form-group mb-3">
                        <label>Emergency Contact Name <span class="text-danger">*</span></label>
                        <input type="text" name="emergency_contact_name" class="form-control js-alpha-name"
                               value="<?= set_value('emergency_contact_name'); ?>" maxlength="80" required
                               pattern="[A-Za-z][A-Za-z .'\-]{0,79}"
                               title="Enter a valid name (letters only)">
                    </div>
                    <div class="col-md-5 form-group mb-3">
                        <label>Emergency Contact Number <span class="text-danger">*</span></label>
                        <!-- Combined flag+dialcode + number in one bordered box -->
                        <div class="phone-combo" id="emergency_phone_combo">
                            <input type="tel" id="emergency_country_code_picker" class="iti-phone-picker" autocomplete="off">
                            <input type="hidden" name="emergency_country_code" id="emergency_country_code"
                                   value="<?= html_escape(set_value('emergency_country_code', set_value('country_code', '+61'))); ?>">
                            <span class="phone-combo-divider"></span>
                            <input type="text" name="emergency_contact_number" class="phone-number-input js-digits-only" placeholder="Contact number"
                                   value="<?= set_value('emergency_contact_number'); ?>"
                                   inputmode="numeric" pattern="[0-9]{7,15}" maxlength="15" required
                                   title="Enter 7–15 digits only">
                        </div>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label>Relationship <span class="text-danger">*</span></label>
                        <input type="text" name="emergency_contact_relationship" class="form-control js-alpha-name"
                               value="<?= set_value('emergency_contact_relationship'); ?>" required maxlength="60"
                               pattern="[A-Za-z][A-Za-z .'\-]{0,59}"
                               title="Enter a valid relationship (letters only)">
                    </div>
                </div>

                <!-- ── Course Details ── -->
                <h4 class="section-title">Course Details</h4>
                <div class="row">
                <?php
                    $locked_course_id    = isset($request['selected_course_id'])         ? (string)$request['selected_course_id']         : '';
                    $locked_course_start = isset($request['selected_course_start_date']) ? (string)$request['selected_course_start_date'] : '';
                    $locked_course_end   = isset($request['selected_course_end_date'])   ? (string)$request['selected_course_end_date']   : '';
                    $course_locked_for_request = ($locked_course_id !== '');

                    $selected_courses = $this->input->post('course_name');
                    if (!is_array($selected_courses)) {
                        $selected_courses = $course_locked_for_request ? array($locked_course_id) : [];
                    }
                    if ($course_locked_for_request) {
                        $selected_courses = array($locked_course_id);
                    }

                    $posted_course_dates = $this->input->post('course_dates');
                    if (!is_array($posted_course_dates)) $posted_course_dates = [];
                    if ($course_locked_for_request && $locked_course_start !== '') {
                        $posted_course_dates[$locked_course_id] = $locked_course_start;
                    }
                ?>

                <div class="col-md-12 form-group mb-3">
                    <label>Select Course(s) <span class="text-danger">*</span></label>
                    <div style="border:1px solid #ddd;border-radius:12px;padding:15px;background:#fafafa;">
                        <div class="row">
                            <?php if ($course_locked_for_request): ?>
                                <input type="hidden" name="course_name[]" value="<?= html_escape($locked_course_id); ?>">
                            <?php endif; ?>
                            <?php if (!empty($all_courses)): ?>
                                <?php foreach ($all_courses as $course): ?>
                                    <?php
                                        if (isset($course['RECORD_STATUS']) && (int)$course['RECORD_STATUS'] !== 0) continue;
                                        $course_id    = isset($course['COURSE_ID']) ? $course['COURSE_ID'] : '';
                                        $course_label = isset($course['NAME']) ? trim($course['NAME']) : '';
                                        if ($course_id === '' || $course_label === '') continue;
                                        $is_checked  = in_array((string)$course_id, array_map('strval', $selected_courses));
                                        $is_disabled = $course_locked_for_request;
                                    ?>
                                    <div class="col-md-6 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input course-checkbox"
                                                id="course_<?= html_escape($course_id); ?>"
                                                name="course_name[]" value="<?= html_escape($course_id); ?>"
                                                data-course-id="<?= html_escape($course_id); ?>"
                                                data-course-name="<?= html_escape($course_label); ?>"
                                                <?= $is_disabled ? 'disabled' : ''; ?>
                                                <?= $is_checked  ? 'checked'  : ''; ?>>
                                            <label class="custom-control-label" for="course_<?= html_escape($course_id); ?>">
                                                <?= html_escape($course_label); ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <div class="col-md-6 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input course-checkbox"
                                        id="course_other" name="course_name[]" value="other"
                                        data-course-id="other" data-course-name="Other"
                                        <?= $course_locked_for_request ? 'disabled' : ''; ?>
                                        <?= in_array('other', array_map('strval', $selected_courses)) ? 'checked' : ''; ?>>
                                    <label class="custom-control-label" for="course_other">Other</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($course_locked_for_request): ?>
                    <div class="col-md-12 form-group mb-2">
                        <div class="alert alert-info mb-0">
                            Assigned course schedule for this request:
                            <strong>Start:</strong> <?= html_escape($locked_course_start !== '' ? $locked_course_start : '-'); ?>,
                            <strong>End:</strong>   <?= html_escape($locked_course_end   !== '' ? $locked_course_end   : '-'); ?>.
                        </div>
                    </div>
                <?php endif; ?>

                <div class="col-md-12">
                    <div id="selected_courses_dates_wrapper">
                        <?php if (!empty($all_courses)): ?>
                            <?php foreach ($all_courses as $course): ?>
                                <?php
                                    if (isset($course['RECORD_STATUS']) && (int)$course['RECORD_STATUS'] !== 0) continue;
                                    $course_id    = isset($course['COURSE_ID']) ? $course['COURSE_ID'] : '';
                                    $course_label = isset($course['NAME']) ? trim($course['NAME']) : '';
                                    if ($course_id === '' || $course_label === '') continue;
                                    $show_block     = in_array((string)$course_id, array_map('strval', $selected_courses));
                                    $course_date_val = isset($posted_course_dates[$course_id]) ? $posted_course_dates[$course_id] : '';
                                    if ($course_locked_for_request && (string)$course_id === $locked_course_id && $locked_course_start !== '') {
                                        $course_date_val = $locked_course_start;
                                    }
                                ?>
                                <?php if ($course_locked_for_request && (string)$course_id === $locked_course_id): ?>
                                    <input type="hidden" name="course_dates[<?= html_escape($course_id); ?>]" value="<?= html_escape($course_date_val); ?>">
                                <?php else: ?>
                                <div class="course-date-block row <?= $show_block ? '' : 'd-none'; ?>" id="course_date_block_<?= html_escape($course_id); ?>">
                                    <div class="col-md-6 form-group mb-3">
                                        <label><?= html_escape($course_label); ?> - Date / Month / Year <span class="text-danger">*</span></label>
                                        <input type="date" name="course_dates[<?= html_escape($course_id); ?>]"
                                               class="form-control course-date-input" value="<?= html_escape($course_date_val); ?>"
                                               placeholder="Example: March 2026"
                                               <?= $show_block ? 'required' : ''; ?>>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php
                            $other_show        = in_array('other', array_map('strval', $selected_courses));
                            $other_course_name = set_value('other_course_name');
                            $other_course_date = isset($posted_course_dates['other']) ? $posted_course_dates['other'] : '';
                        ?>
                        <div class="course-date-block row <?= $other_show ? '' : 'd-none'; ?>" id="course_date_block_other">
                            <div class="col-md-6 form-group mb-3">
                                <label>Other Course Name <span class="text-danger">*</span></label>
                                <input type="text" name="other_course_name" id="other_course_name" class="form-control course-other-required"
                                       value="<?= html_escape($other_course_name); ?>" <?= $other_show ? 'required' : ''; ?>>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label>Other Course - Date / Month / Year <span class="text-danger">*</span></label>
                                <input type="date" name="course_dates[other]" class="form-control course-date-input"
                                       value="<?= html_escape($other_course_date); ?>" placeholder="Example: March 2026"
                                       <?= $other_show ? 'required' : ''; ?>>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery services notice -->
                <div class="col-12 form-group mb-3">
                    <?php $this->load->view('payment_agreement/partials/delivery_services_notice', ['form_settings' => $fs, 'variant' => 'public']); ?>
                </div>

                <?php
                    $req_total   = isset($request['total_inc_gst']) && $request['total_inc_gst'] !== null && $request['total_inc_gst'] !== ''
                                   ? (string)$request['total_inc_gst'] : '';
                    $total_locked = $req_total !== '';

                    $req_deposit = isset($request['deposit_amount']) && $request['deposit_amount'] !== null && $request['deposit_amount'] !== ''
                                   ? (string)$request['deposit_amount'] : '';
                    $deposit_locked = $req_deposit !== '';

                    $req_deposit_date = !empty($request['deposit_paid_on']) ? (string)$request['deposit_paid_on'] : '';
                    $deposit_date_locked = $req_deposit_date !== '';
                ?>

                <!-- Amount fields: 3 compact cols + date fields -->
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-4 form-group mb-3">
                            <label>Total (inc GST) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01"
                                   name="total_inc_gst" id="total_inc_gst_input"
                                   class="form-control <?= $total_locked ? 'bg-light' : ''; ?>"
                                   style="max-width:180px;"
                                   value="<?= set_value('total_inc_gst', $req_total); ?>"
                                   required
                                   <?= $total_locked ? 'readonly tabindex="-1"' : 'min="0"'; ?>>
                            <?php if ($total_locked): ?>
                                <small class="form-text text-muted">Set when this link was sent to you and cannot be changed here.</small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label>Deposit Paid $ <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0"
                                   name="deposit_amount" id="deposit_amount_input"
                                   class="form-control <?= $deposit_locked ? 'bg-light' : ''; ?>"
                                   style="max-width:180px;"
                                   value="<?= set_value('deposit_amount', $req_deposit); ?>"
                                   required
                                   <?= $deposit_locked ? 'readonly tabindex="-1"' : ''; ?>
                                   title="Enter a valid deposit amount (numbers only)">
                            <?php if ($deposit_locked): ?>
                                <small class="form-text text-muted">Set when this link was sent to you and cannot be changed here.</small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label>Deposit Paid Via <span class="text-danger">*</span></label>
                            <select name="deposit_paid_via" class="form-control" style="max-width:200px;" required>
                                <option value="">-- Select --</option>
                                <option value="Bank Transfer" <?= (set_value('deposit_paid_via') == 'Bank Transfer' ? 'selected' : ''); ?>>Bank Transfer</option>
                                <option value="Payment Link"  <?= (set_value('deposit_paid_via') == 'Payment Link'  ? 'selected' : ''); ?>>Payment Link</option>
                                <option value="Others"        <?= (set_value('deposit_paid_via') == 'Others'        ? 'selected' : ''); ?>>Others</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label>Deposit Paid On <span class="text-danger">*</span></label>
                            <input type="date" name="deposit_paid_on" class="form-control <?= $deposit_date_locked ? 'bg-light' : ''; ?>"
                                   style="max-width:200px;"
                                   value="<?= set_value('deposit_paid_on', $req_deposit_date); ?>"
                                   required
                                   <?= $deposit_date_locked ? 'readonly tabindex="-1"' : ''; ?>>
                            <?php if ($deposit_date_locked): ?>
                                <small class="form-text text-muted">Set when this link was sent to you and cannot be changed here.</small>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label>Balance Payable $ <span class="text-danger">*</span></label>
                            <input type="number" step="0.01"
                                   name="balance_amount" id="balance_amount_input"
                                   class="form-control bg-light" style="max-width:180px;"
                                   value="<?= set_value('balance_amount'); ?>" readonly tabindex="-1" required>
                            <small class="form-text text-muted">Calculated as Total (inc GST) minus Deposit Paid $.</small>
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label>Due Date <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" class="form-control" style="max-width:200px;" value="<?= set_value('due_date'); ?>" required>
                        </div>
                    </div>
                </div>

                </div><!-- /row (Course Details) -->

                <?php $this->load->view('payment_agreement/partials/payment_arrangement_section', ['form_settings' => $fs, 'request' => $request]); ?>

                <!-- ── Certification ── -->
                <h4 class="section-title">Certification <span class="text-muted font-weight-normal" style="font-size:14px;">(optional)</span></h4>
                <div class="form-group mb-3">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="holds_certification" name="holds_certification" value="1"  <?= set_checkbox('holds_certification', '1'); ?>>
                        <label class="custom-control-label" for="holds_certification">
                            I currently hold certification from your organization or another training provider
                        </label>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label>Certification Details</label>
                    <textarea name="certification_details" class="form-control" rows="4" ><?= set_value('certification_details'); ?></textarea>
                    <small class="form-text text-muted">Optional. Include qualification name, certification date, and training provider name if applicable.</small>
                </div>
                <div class="form-group mb-3">
                    <label>Attach Certification Copy</label>
                    <input type="file" name="certification_attachment" class="form-control-file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                    <small class="form-text text-muted">Optional. Accepted formats: JPG, PNG, PDF, DOC, DOCX (max 5MB).</small>
                </div>

                <!-- ── Payment Details ── -->
                <h4 class="section-title"><?= html_escape(fs($fs, 'section_payment_details', 'Payment Details')); ?></h4>
                <div class="form-group mb-3">
                    <?php $this->load->view('payment_agreement/partials/payment_modes_notice', ['form_settings' => $fs, 'variant' => 'public']); ?>
                </div>
                <div class="form-group mb-3">
                    <label class="d-block font-weight-bold mb-2">Your preferred payment method <span class="text-danger">*</span></label>
                    <div class="custom-control custom-radio mb-2">
                        <input type="radio" class="custom-control-input" id="payment_option_xero_stripe"
                               name="payment_option" value="Secure Online Payment (Xero &amp; Stripe)" required
                               <?= set_radio('payment_option', 'Secure Online Payment (Xero & Stripe)'); ?>>
                        <label class="custom-control-label" for="payment_option_xero_stripe">Secure Online Payment (Invoice via Xero &amp; Stripe)</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="payment_option_bank_payid"
                               name="payment_option" value="Bank Transfer / PayID"
                               <?= set_radio('payment_option', 'Bank Transfer / PayID'); ?>>
                        <label class="custom-control-label" for="payment_option_bank_payid">Bank Transfer / PayID</label>
                    </div>
                </div>

                <?php
                    $consent_rich_trim   = isset($fs['consent_rich_html']) ? trim((string)$fs['consent_rich_html']) : '';
                    $has_consent_block   = $show_consent_terms_heading || ($consent_rich_trim !== '');
                    $consent_inputs      = [
                        1=>['name'=>'agree_terms_1','id'=>'agree1'],
                        2=>['name'=>'agree_terms_2','id'=>'agree2'],
                        3=>['name'=>'agree_terms_3','id'=>'agree3'],
                        4=>['name'=>'agree_terms_4','id'=>'agree4'],
                        5=>['name'=>'agree_terms_5','id'=>'agree5'],
                        6=>['name'=>'agree_terms_6','id'=>'agree6'],
                    ];
                    $visible_term_indexes = [];
                    foreach ($consent_term_labels as $ti => $tlabel) {
                        if ($tlabel !== '') $visible_term_indexes[] = (int)$ti;
                    }
                    $has_consent_checkboxes = !empty($visible_term_indexes);
                ?>
                <?php if ($has_consent_block || $has_consent_checkboxes): ?>
                    <?php if ($show_consent_terms_heading): ?>
                        <h4 class="section-title"><?= html_escape(fs($fs, 'section_terms', 'Consent & Terms')); ?></h4>
                    <?php endif; ?>
                    <?php if ($consent_rich_trim !== ''): ?>
                    <div class="consent-copy-panel">
                        <?php $this->load->view('payment_agreement/partials/consent_rich_display', ['form_settings' => $fs, 'variant' => 'public']); ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($has_consent_checkboxes): ?>
                    <div class="consent-checkboxes-panel">
                        <p class="consent-checkboxes-panel-title">Please confirm each item below</p>
                        <?php foreach ($visible_term_indexes as $ti): ?>
                            <?php $tlabel = $consent_term_labels[$ti]; $inp = $consent_inputs[$ti]; ?>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input consent-term-checkbox" id="<?= html_escape($inp['id']); ?>"
                                   name="<?= html_escape($inp['name']); ?>" value="1" required <?= set_checkbox($inp['name'], '1'); ?>>
                            <label class="custom-control-label" for="<?= html_escape($inp['id']); ?>">
                                <span class="consent-scroll-wrap">
                                    <span class="consent-scroll-box"><?= html_escape($tlabel); ?></span>
                                    <span class="consent-scroll-hint" aria-hidden="true">
                                        <span>Scroll for more</span>
                                        <span class="consent-scroll-hint-icon" aria-hidden="true">↓</span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- ── Signature ── -->
                <h4 class="section-title">Signature</h4>
                <div class="row">
                    <div class="col-md-4 form-group mb-3">
                        <label>Signature First Name <span class="text-danger">*</span></label>
                        <input type="text" name="signature_first_name" class="form-control js-alpha-name"
                               value="<?= set_value('signature_first_name'); ?>" maxlength="60" required
                               pattern="[A-Za-z][A-Za-z .'\-]{0,59}"
                               title="Enter a valid first name (letters only)">
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label>Signature Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="signature_last_name" class="form-control js-alpha-name"
                               value="<?= set_value('signature_last_name'); ?>" maxlength="60" required
                               pattern="[A-Za-z][A-Za-z .'\-]{0,59}"
                               title="Enter a valid last name (letters only)">
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label>Signature Date <span class="text-danger">*</span></label>
                        <input type="date" name="signature_date" class="form-control" value="<?= set_value('signature_date', date('Y-m-d')); ?>" required>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label>Draw Signature <span class="text-danger">*</span></label>
                        <canvas id="signature_pad" class="signature-box"></canvas>
                        <input type="hidden" name="signature_data" id="signature_data">
                        <div class="signature-actions">
                            <button type="button" class="btn btn-secondary" id="clear_signature">Clear Signature</button>
                        </div>
                        <div class="signature-note">Draw your signature in the box above. This will be saved with the form.</div>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label>Approved By</label>
                        <input type="text" name="approved_by" class="form-control bg-light" value="<?= set_value('approved_by'); ?>" readonly disabled>
                        <small class="form-text text-muted">This field is managed by admin.</small>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label>Approval Date</label>
                        <input type="date" name="approval_date" class="form-control bg-light" value="<?= set_value('approval_date'); ?>" readonly disabled>
                        <small class="form-text text-muted">This field is managed by admin.</small>
                    </div>
                </div>

                <div class="mt-4">
                    <input type="hidden" name="client_timezone"   id="client_timezone">
                    <input type="hidden" name="client_time_iso"   id="client_time_iso">
                    <input type="hidden" name="screen_resolution" id="screen_resolution">
                    <button type="submit" class="btn btn-primary btn-lg">Submit Agreement</button>
                </div>
            </form>

            <div class="footer-note"></div>
        </div>
    </div>
</div>

<div class="eyd-footer">
    <div class="eyd-footer-inner">
        Empower Your Destiny I Enrollment and Payment Agreement I <?= date('Y'); ?>
    </div>
</div>

<script>
    document.getElementById('client_timezone').value   = Intl.DateTimeFormat().resolvedOptions().timeZone || '';
    document.getElementById('client_time_iso').value   = new Date().toISOString();
    document.getElementById('screen_resolution').value = (window.screen.width||'') + 'x' + (window.screen.height||'');
</script>

<!-- Consent scroll -->
<script>
(function(){
    function refreshConsentScrollWrap(wrap){
        var box=wrap.querySelector('.consent-scroll-box');
        if(!box)return;
        var hasMore=box.scrollHeight>box.clientHeight+1;
        wrap.classList.toggle('has-more',hasMore);
        var atBottom=!hasMore||(box.scrollTop+box.clientHeight>=box.scrollHeight-2);
        wrap.classList.toggle('is-at-bottom',atBottom);
    }
    function initConsentScrollWraps(){
        var wraps=document.querySelectorAll('.consent-copy-panel .consent-scroll-wrap,.consent-checkboxes-panel .consent-scroll-wrap');
        wraps.forEach(function(wrap){
            if(wrap.getAttribute('data-scroll-init')==='1'){refreshConsentScrollWrap(wrap);return;}
            wrap.setAttribute('data-scroll-init','1');
            var box=wrap.querySelector('.consent-scroll-box');
            if(!box)return;
            box.addEventListener('scroll',function(){refreshConsentScrollWrap(wrap);});
            refreshConsentScrollWrap(wrap);
        });
    }
    initConsentScrollWraps();
    window.addEventListener('resize',initConsentScrollWraps);
    window.addEventListener('load',initConsentScrollWraps);
})();
</script>

<!-- Signature pad -->
<script>
(function(){
    var canvas=document.getElementById('signature_pad');
    var hiddenInput=document.getElementById('signature_data');
    var clearBtn=document.getElementById('clear_signature');
    if(!canvas||!hiddenInput)return;
    var ctx=canvas.getContext('2d');
    var drawing=false,hasSignature=false,lastX=0,lastY=0;
    function resizeCanvas(){
        var ratio=Math.max(window.devicePixelRatio||1,1);
        var rect=canvas.getBoundingClientRect();
        canvas.width=rect.width*ratio;canvas.height=rect.height*ratio;
        ctx.setTransform(1,0,0,1,0,0);ctx.scale(ratio,ratio);
        ctx.lineWidth=2;ctx.lineCap='round';ctx.strokeStyle='#111';
    }
    function getPosition(e){
        var rect=canvas.getBoundingClientRect(),clientX,clientY;
        if(e.touches&&e.touches.length>0){clientX=e.touches[0].clientX;clientY=e.touches[0].clientY;}
        else{clientX=e.clientX;clientY=e.clientY;}
        return{x:clientX-rect.left,y:clientY-rect.top};
    }
    function startDrawing(e){drawing=true;var pos=getPosition(e);lastX=pos.x;lastY=pos.y;e.preventDefault();}
    function draw(e){
        if(!drawing)return;
        var pos=getPosition(e);
        ctx.beginPath();ctx.moveTo(lastX,lastY);ctx.lineTo(pos.x,pos.y);ctx.stroke();
        lastX=pos.x;lastY=pos.y;hasSignature=true;e.preventDefault();
    }
    function stopDrawing(){drawing=false;}
    function clearSignature(){ctx.clearRect(0,0,canvas.width,canvas.height);hasSignature=false;hiddenInput.value='';}

    /* Exposed globally so the form's onsubmit="return beforeSubmit()" works */
    window.beforeSubmit = function(){
        function fail(msg, el) {
            alert(msg);
            if (el && typeof el.focus === 'function') el.focus();
            return false;
        }

        function isDigits(val, minLen, maxLen) {
            var v = String(val || '').trim();
            return new RegExp('^[0-9]{' + minLen + ',' + maxLen + '}$').test(v);
        }

        function isValidEmail(val) {
            return /^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/.test(String(val || '').trim());
        }

        function isValidName(val) {
            return /^[A-Za-z][A-Za-z .'\-]*$/.test(String(val || '').trim());
        }

        var firstName = document.querySelector('input[name="first_name"]');
        var lastName = document.querySelector('input[name="last_name"]');
        var email = document.querySelector('input[name="email_address"]');
        var postcode = document.querySelector('input[name="postcode"]');
        var mobile = document.querySelector('input[name="contact_number_mobile"]');
        var work = document.querySelector('input[name="contact_number_work"]');
        var emergencyName = document.querySelector('input[name="emergency_contact_name"]');
        var emergencyPhone = document.querySelector('input[name="emergency_contact_number"]');
        var relationship = document.querySelector('input[name="emergency_contact_relationship"]');
        var city = document.querySelector('input[name="city_suburb"]');
        var deposit = document.querySelector('input[name="deposit_amount"]');
        var sigFirst = document.querySelector('input[name="signature_first_name"]');
        var sigLast = document.querySelector('input[name="signature_last_name"]');

        if (!isValidName(firstName && firstName.value)) return fail('First name must contain letters only.', firstName);
        if (!isValidName(lastName && lastName.value)) return fail('Last name must contain letters only.', lastName);
        if (!isValidName(city && city.value)) return fail('City / Suburb must contain letters only.', city);
        if (!isDigits(postcode && postcode.value, 3, 10)) return fail('Postcode must be 3–10 digits (numbers only).', postcode);
        if (!isDigits(mobile && mobile.value, 7, 15)) return fail('Mobile number must be 7–15 digits (numbers only).', mobile);
        if (!isDigits(work && work.value, 7, 15)) return fail('Work number must be 7–15 digits (numbers only).', work);
        if (!isValidEmail(email && email.value)) return fail('Please enter a valid email address.', email);
        if (!isValidName(emergencyName && emergencyName.value)) return fail('Emergency contact name must contain letters only.', emergencyName);
        if (!isDigits(emergencyPhone && emergencyPhone.value, 7, 15)) return fail('Emergency contact number must be 7–15 digits (numbers only).', emergencyPhone);
        if (!isValidName(relationship && relationship.value)) return fail('Relationship must contain letters only.', relationship);

        if (deposit) {
            var depVal = parseFloat(deposit.value);
            if (isNaN(depVal) || depVal < 0) return fail('Deposit amount must be a valid number (0 or greater).', deposit);
        }

        if (!isValidName(sigFirst && sigFirst.value)) return fail('Signature first name must contain letters only.', sigFirst);
        if (!isValidName(sigLast && sigLast.value)) return fail('Signature last name must contain letters only.', sigLast);

        var courseSelected = false;
        var lockedCourse = document.querySelector('input[type="hidden"][name="course_name[]"]');
        if (lockedCourse && lockedCourse.value) {
            courseSelected = true;
        } else {
            document.querySelectorAll('.course-checkbox:not(:disabled)').forEach(function(cb){
                if (cb.checked) courseSelected = true;
            });
        }
        if (!courseSelected) {
            return fail('Please select at least one course before submitting.');
        }

        var otherCb = document.getElementById('course_other');
        if (otherCb && otherCb.checked) {
            var otherName = document.getElementById('other_course_name');
            if (!otherName || !String(otherName.value || '').trim()) {
                return fail('Please enter the Other Course Name.', otherName);
            }
        }

        var paFull = document.getElementById('pa_type_full');
        var paPlan = document.getElementById('pa_type_plan');
        if ((!paFull || !paFull.checked) && (!paPlan || !paPlan.checked)) {
            return fail('Please select a preferred payment arrangement.');
        }

        if (!hasSignature) {
            return fail('Please draw your signature before submitting.');
        }
        hiddenInput.value = canvas.toDataURL('image/png');
        return true;
    };

    resizeCanvas();
    window.addEventListener('resize',resizeCanvas);
    canvas.addEventListener('mousedown',startDrawing);
    canvas.addEventListener('mousemove',draw);
    window.addEventListener('mouseup',stopDrawing);
    canvas.addEventListener('touchstart',startDrawing,{passive:false});
    canvas.addEventListener('touchmove',draw,{passive:false});
    window.addEventListener('touchend',stopDrawing);
    if(clearBtn)clearBtn.addEventListener('click',clearSignature);
})();
</script>

<!-- Basic input sanitizers: digits-only / letters-friendly names -->
<script>
(function(){
    function digitsOnly(el){
        el.addEventListener('input', function(){
            var cleaned = String(el.value || '').replace(/[^0-9]/g, '');
            if (el.value !== cleaned) el.value = cleaned;
        });
        el.addEventListener('paste', function(e){
            e.preventDefault();
            var text = (e.clipboardData || window.clipboardData).getData('text') || '';
            var start = el.selectionStart || 0;
            var end = el.selectionEnd || 0;
            var next = (el.value.slice(0, start) + text.replace(/[^0-9]/g, '') + el.value.slice(end));
            if (el.maxLength > 0) next = next.slice(0, el.maxLength);
            el.value = next;
        });
    }

    function alphaNameOnly(el){
        el.addEventListener('input', function(){
            var cleaned = String(el.value || '').replace(/[^A-Za-z .'\-]/g, '');
            if (el.value !== cleaned) el.value = cleaned;
        });
    }

    document.querySelectorAll('.js-digits-only').forEach(digitsOnly);
    document.querySelectorAll('.js-alpha-name').forEach(alphaNameOnly);
})();
</script>

<!-- Course date toggles -->
<script>
(function(){
    var checkboxes=document.querySelectorAll('.course-checkbox');
    function setBlockRequired(block, isRequired){
        if(!block)return;
        block.querySelectorAll('input.course-date-input, input.course-other-required, #other_course_name').forEach(function(i){
            if(isRequired){ i.setAttribute('required','required'); }
            else{ i.removeAttribute('required'); }
        });
    }
    function toggleDateBlock(courseId,isChecked){
        var block=document.getElementById('course_date_block_'+courseId);
        if(!block)return;
        if(isChecked){
            block.classList.remove('d-none');
            setBlockRequired(block, true);
        } else {
            block.classList.add('d-none');
            setBlockRequired(block, false);
            block.querySelectorAll('input').forEach(function(i){i.value='';});
        }
    }
    checkboxes.forEach(function(checkbox){
        checkbox.addEventListener('change',function(){
            toggleDateBlock(this.getAttribute('data-course-id'),this.checked);
        });
        // Ensure required state matches current checked state on load
        toggleDateBlock(checkbox.getAttribute('data-course-id'), checkbox.checked);
    });
})();
</script>

<!-- intl-tel-input: unified phone combos -->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
<script>
(function(){
    if(!window.intlTelInput||!window.intlTelInputGlobals)return;

    function normalizeDialCode(raw){
        var v=String(raw||'').trim();
        if(v==='')return'+61';
        return v.charAt(0)==='+'?v:('+'+v);
    }

    /**
     * initPhoneCombo(pickerId, hiddenId, initialCode)
     *
     * Initialises an intl-tel-input inside a .phone-combo div.
     * The picker input carries the flag + dial code selector.
     * The hidden input stores the final "+XX" value for form submission.
     */
    function initPhoneCombo(pickerId, hiddenId, initialCode){
        var pickerInput = document.getElementById(pickerId);
        var hiddenField = document.getElementById(hiddenId);
        if(!pickerInput||!hiddenField)return;

        var iti = window.intlTelInput(pickerInput,{
            initialCountry   : 'au',
            separateDialCode : true,
            autoPlaceholder  : 'off',
            utilsScript      : 'https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js'
        });

        /* Sync hidden field from currently selected country */
        function syncHidden(){
            var cd=iti.getSelectedCountryData();
            if(cd&&cd.dialCode) hiddenField.value='+'+cd.dialCode;
        }

        /* Keep picker input display text in sync with selected dial code */
        function syncPickerInputFromCountry(){
            var cd=iti.getSelectedCountryData();
            if(cd&&cd.dialCode) pickerInput.value='+'+cd.dialCode;
        }

        /* If user types a dial code manually, update the flag to match */
        function syncCountryFromTypedCode(){
            var raw=String(pickerInput.value||'').trim();
            var match=raw.match(/^\+?([0-9]{1,4})/);
            if(!match)return;
            var typedDial=match[1];
            var allCountries=window.intlTelInputGlobals.getCountryData();
            for(var i=0;i<allCountries.length;i++){
                if(String(allCountries[i].dialCode)===typedDial){
                    iti.setCountry(allCountries[i].iso2);
                    break;
                }
            }
            syncHidden();
        }

        /* Remove default border/width from iti wrapper so combo border takes over */
        var itiWrap=pickerInput.closest('.iti');
        if(itiWrap){
            itiWrap.style.border='none';
            itiWrap.style.boxShadow='none';
            itiWrap.style.width='auto';
        }

        /* Set the correct initial country from stored dial code */
        var code=normalizeDialCode(initialCode||hiddenField.value);
        var allCountries=window.intlTelInputGlobals.getCountryData();
        for(var i=0;i<allCountries.length;i++){
            if(('+'+ allCountries[i].dialCode)===code){
                iti.setCountry(allCountries[i].iso2);
                break;
            }
        }
        syncHidden();
        syncPickerInputFromCountry();

        pickerInput.addEventListener('countrychange', syncHidden);
        pickerInput.addEventListener('countrychange', syncPickerInputFromCountry);
        pickerInput.addEventListener('input',  syncCountryFromTypedCode);
        pickerInput.addEventListener('change', syncCountryFromTypedCode);
    }

    /* Mobile */
    initPhoneCombo('country_code_picker',          'country_code',          '+61');
    /* Work */
    initPhoneCombo('work_country_code_picker',      'country_code_work',     '+61');
    /* Emergency */
    initPhoneCombo('emergency_country_code_picker', 'emergency_country_code','+61');
})();
</script>

<!-- Fix iti dropdown positioning: use fixed so it escapes ALL overflow ancestors -->
<script>
(function(){
    function fixItiDropdown(pickerId){
        var picker = document.getElementById(pickerId);
        if(!picker) return;
        var flagBtn = picker.closest('.iti') && picker.closest('.iti').querySelector('.iti__selected-flag');
        if(!flagBtn) return;

        var list = picker.closest('.iti') && picker.closest('.iti').querySelector('.iti__country-list');
        if(!list) return;

        /* When dropdown opens, reposition it using fixed coords */
        function reposition(){
            var rect = flagBtn.getBoundingClientRect();
            list.style.position   = 'fixed';
            list.style.top        = (rect.bottom + 2) + 'px';
            list.style.left       = rect.left + 'px';
            list.style.width      = 'auto';
            list.style.minWidth   = '280px';
            list.style.zIndex     = '99999';
        }

        /* Observe when the list becomes visible */
        var observer = new MutationObserver(function(mutations){
            mutations.forEach(function(m){
                if(m.type === 'attributes' && m.attributeName === 'class'){
                    if(!list.classList.contains('hide')) reposition();
                }
            });
        });
        observer.observe(list, { attributes: true });

        /* Also reposition on scroll/resize while open */
        window.addEventListener('scroll',  function(){ if(!list.classList.contains('hide')) reposition(); }, true);
        window.addEventListener('resize',  function(){ if(!list.classList.contains('hide')) reposition(); });
    }

    window.addEventListener('load', function(){
        fixItiDropdown('country_code_picker');
        fixItiDropdown('work_country_code_picker');
        fixItiDropdown('emergency_country_code_picker');
    });
})();
</script>

<!-- Balance auto-calc -->
<script>
(function(){
    var totalInput   = document.getElementById('total_inc_gst_input');
    var depositInput = document.getElementById('deposit_amount_input');
    var balanceInput = document.getElementById('balance_amount_input');
    if(!totalInput||!depositInput||!balanceInput)return;
    function recalc(){
        var t=parseFloat(totalInput.value)||0;
        var d=parseFloat(depositInput.value)||0;
        balanceInput.value=(t-d).toFixed(2);
    }
    totalInput.addEventListener('input',recalc);
    depositInput.addEventListener('input',recalc);
    recalc();
})();
</script>

</body>
</html>