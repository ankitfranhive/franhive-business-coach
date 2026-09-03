<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$fs = isset($form_settings) ? $form_settings : [];
$variant = isset($variant) ? $variant : 'public';

$def_h = 'How we deliver our services';
$def_t = "Our services are primarily delivered online via Zoom to ensure accessibility and convenience.\n\nCertain trainings may also be offered in a hybrid format (online and in-person), where feasible. If a hybrid option is offered for your chosen training, you will be invited to indicate your preferred mode of attendance during the registration process to ensure you are fully supported by our team.";

$h = (isset($fs['delivery_services_heading']) && trim((string)$fs['delivery_services_heading']) !== '')
    ? $fs['delivery_services_heading']
    : $def_h;
$t = (isset($fs['delivery_services_text']) && trim((string)$fs['delivery_services_text']) !== '')
    ? $fs['delivery_services_text']
    : $def_t;

if ($variant === 'public'): ?>
<div class="delivery-services-notice">
    <div class="delivery-services-notice-title"><?= html_escape($h); ?></div>
    <div class="delivery-services-notice-body"><?= nl2br(html_escape($t)); ?></div>
</div>
<?php elseif ($variant === 'admin_detail'): ?>
<div class="detail-label delivery-services-notice-admin-title"><?= html_escape($h); ?></div>
<div class="detail-value delivery-services-notice-admin-body"><?= nl2br(html_escape($t)); ?></div>
<?php elseif ($variant === 'preview'): ?>
<div class="col-12 preview-field">
    <span class="preview-label"><?= html_escape($h); ?></span>
    <div class="preview-value delivery-services-preview-body"><?= nl2br(html_escape($t)); ?></div>
</div>
<?php elseif ($variant === 'pdf'): ?>
<div class="label"><?= html_escape($h); ?></div>
<div class="value delivery-services-pdf-body"><?= nl2br(html_escape($t)); ?></div>
<?php endif; ?>
