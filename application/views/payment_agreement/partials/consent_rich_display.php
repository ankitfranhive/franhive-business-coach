<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$fs = isset($form_settings) ? $form_settings : [];
$variant = isset($variant) ? $variant : 'public';

$h = isset($fs['consent_rich_heading']) ? trim((string)$fs['consent_rich_heading']) : '';
$html = isset($fs['consent_rich_html']) ? trim((string)$fs['consent_rich_html']) : '';

if ($html === '') {
    return;
}

if ($variant === 'public'): ?>
<div class="consent-rich-block">
    <?php if ($h !== ''): ?>
        <div class="consent-rich-heading mb-2"><?= html_escape($h); ?></div>
    <?php endif; ?>
    <div class="consent-scroll-wrap">
        <div class="consent-rich-body consent-scroll-box"><?= $html; ?></div>
        <div class="consent-scroll-hint" aria-hidden="true">
            <span>Scroll for more</span>
            <span class="consent-scroll-hint-icon" aria-hidden="true">↓</span>
        </div>
    </div>
</div>
<?php elseif ($variant === 'admin'): ?>
<div class="mb-4 consent-rich-audit">
    <?php if ($h !== ''): ?>
        <div class="detail-label consent-rich-admin-h" style="white-space:normal;text-transform:none;letter-spacing:normal;"><?= html_escape($h); ?></div>
    <?php endif; ?>
    <div class="detail-value consent-rich-body mt-2"><?= $html; ?></div>
</div>
<?php elseif ($variant === 'preview'): ?>
<div class="col-12 mb-3 consent-rich-preview-wrap">
    <?php if ($h !== ''): ?>
        <span class="preview-label d-block mb-2"><?= html_escape($h); ?></span>
    <?php endif; ?>
    <div class="preview-value consent-rich-body" style="white-space:normal;"><?= $html; ?></div>
</div>
<?php elseif ($variant === 'pdf'): ?>
<div style="margin-top:8px;margin-bottom:10px;">
    <?php if ($h !== ''): ?>
        <div class="label"><?= htmlspecialchars($h, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <div class="value" style="white-space:normal;"><?= $html; ?></div>
</div>
<?php endif; ?>
