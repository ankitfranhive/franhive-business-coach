<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$fs = isset($form_settings) ? $form_settings : [];
$variant = isset($variant) ? $variant : 'public';

$def_h = 'Mode of Payment:';
$def_t = "Please select your preferred payment method:\n\n"
    . "• Secure Online Payment (Invoice via Xero & Stripe)\n"
    . "All payments are issued through our secure accounting system (Xero) and processed via Stripe, a globally recognised and trusted payment provider.\n"
    . "You will receive an invoice via email with a secure \"Pay Now\" option to complete your payment directly through Stripe's protected system.\n\n"
    . "• Bank Transfer / PayID Details\n"
    . "I understand that I am responsible for making payments on or before the agreed due dates.\n"
    . "Account Name: Empower Your Destiny\n"
    . "BSB: 033161\n"
    . "Account Number: 249860\n"
    . "Bank Name: Westpac\n"
    . "To avoid any confusion please use your full name as the payment reference.\n\n"
    . "OR\n\n"
    . "PayID - 0426886501 (Account name: Empower Your Destiny)\n"
    . "To avoid any confusion please use your full name as the payment reference.";

$h = (isset($fs['payment_modes_heading']) && trim((string)$fs['payment_modes_heading']) !== '')
    ? $fs['payment_modes_heading']
    : $def_h;
$t = (isset($fs['payment_modes_body']) && trim((string)$fs['payment_modes_body']) !== '')
    ? $fs['payment_modes_body']
    : $def_t;

if ($variant === 'public'): ?>
<div class="payment-modes-notice">
    <div class="payment-modes-notice-title"><?= html_escape($h); ?></div>
    <div class="payment-modes-notice-body"><?= nl2br(html_escape($t)); ?></div>
</div>
<?php elseif ($variant === 'admin_detail'): ?>
<div class="detail-label payment-modes-notice-admin-title"><?= html_escape($h); ?></div>
<div class="detail-value payment-modes-notice-admin-body"><?= nl2br(html_escape($t)); ?></div>
<?php elseif ($variant === 'preview'): ?>
<div class="col-12 preview-field">
    <span class="preview-label"><?= html_escape($h); ?></span>
    <div class="preview-value payment-modes-preview-body"><?= nl2br(html_escape($t)); ?></div>
</div>
<?php elseif ($variant === 'pdf'): ?>
<div class="label"><?= html_escape($h); ?></div>
<div class="value payment-modes-pdf-body"><?= nl2br(html_escape($t)); ?></div>
<?php endif; ?>
